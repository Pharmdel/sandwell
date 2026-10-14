#!/usr/bin/env python3
"""
Palette check: reads the brand tokens straight out of resources/css/app.css (so
the stylesheet stays the only source of truth) and verifies two things.

  1. DERIVATION. Every brand colour must sit on the primary's hue, so the palette
     is "in respect to" #88E788 and nothing else. The only exceptions are listed
     in SEMANTIC: the NHS's own blue and the danger reds, which carry meaning that
     must not be tied to the brand.

  2. CONTRAST. Every ink-on-ground pairing the design actually uses must clear its
     WCAG 2.x threshold. #88E788 is a fill colour (1.5:1 against white), so the
     pairings that matter are ink ON it, never it on white.

    python3 tests/manual/palette-check.py            # prints both tables
    python3 tests/manual/palette-check.py --quiet    # failures only

Exit code 1 if anything fails.
"""
import colorsys
import pathlib
import re
import sys

CSS = pathlib.Path(__file__).resolve().parents[2] / 'resources' / 'css' / 'app.css'
SEMANTIC = {'nhs-blue', 'danger', 'danger-soft', 'danger-line'}
HUE_TOLERANCE = 10  # degrees; low-saturation neutrals round noisily in hex


def load_tokens():
    text = CSS.read_text()
    block = text[text.index('@theme'):]
    raw = dict(re.findall(r'--color-([a-z0-9-]+):\s*([^;]+);', block))
    tokens = {}
    for name, value in raw.items():
        value = value.strip()
        alias = re.fullmatch(r'var\(--color-([a-z0-9-]+)\)', value)
        if alias:
            value = raw[alias.group(1)].strip()
        if re.fullmatch(r'#[0-9a-fA-F]{6}', value):
            tokens[name] = value.lower()
    return tokens


def rgb(hexv):
    h = hexv.lstrip('#')
    return [int(h[i:i + 2], 16) / 255 for i in (0, 2, 4)]


def luminance(hexv):
    c = [x / 12.92 if x <= 0.03928 else ((x + 0.055) / 1.055) ** 2.4 for x in rgb(hexv)]
    return 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2]


def contrast(a, b):
    la, lb = sorted([luminance(a), luminance(b)], reverse=True)
    return (la + 0.05) / (lb + 0.05)


def hls(hexv):
    h, l, s = colorsys.rgb_to_hls(*rgb(hexv))
    return round(h * 360), round(s * 100), round(l * 100)


def main(quiet):
    t = load_tokens()
    b = lambda n: t['brand-' + n]
    primary = b('primary')
    hue = hls(primary)[0]
    failures = 0

    # ---- 1. derivation ---------------------------------------------------------
    if not quiet:
        print(f'DERIVATION  primary {primary} = hue {hue}; every brand colour must share it\n')
        print(f'  {"token":<22}{"hex":<10}{"hue":>5}{"sat":>6}{"light":>7}   verdict')
    for name, value in sorted(t.items()):
        short = name.replace('brand-', '')
        h, s, l = hls(value)
        if name == 'white' or name.startswith('footer'):
            continue  # white is neutral, footer tokens alias brand tokens
        if short in SEMANTIC:
            verdict = 'semantic (exempt)'
        elif s < 6:
            verdict = 'neutral'
        else:
            off = min(abs(h - hue), 360 - abs(h - hue))
            ok = off <= HUE_TOLERANCE
            failures += not ok
            verdict = 'on hue' if ok else f'OFF-HUE by {off} degrees'
        if not quiet or 'OFF' in verdict:
            print(f'  {name:<22}{value:<10}{h:>5}{s:>6}{l:>7}   {verdict}')

    # ---- 2. contrast -----------------------------------------------------------
    W = '#ffffff'
    pairs = [
        ('heading forest on white', b('forest'), W, 7.0),
        ('heading forest on ivory', b('forest'), b('ivory'), 7.0),
        ('heading forest on pistachio', b('forest'), b('pistachio'), 7.0),
        ('heading forest on primary', b('forest'), primary, 7.0),
        ('heading forest on primary-hover', b('forest'), b('primary-hover'), 7.0),
        ('heading forest on primary-soft', b('forest'), b('primary-soft'), 7.0),
        ('body stone on white', b('stone'), W, 4.5),
        ('body stone on ivory', b('stone'), b('ivory'), 4.5),
        ('body stone on pistachio', b('stone'), b('pistachio'), 4.5),
        ('body stone on primary-soft', b('stone'), b('primary-soft'), 4.5),
        ('secondary stone-dark on primary', b('stone-dark'), primary, 4.5),
        ('secondary stone-dark on primary-hover', b('stone-dark'), b('primary-hover'), 4.5),
        ('meta stone-light on white', b('stone-light'), W, 4.5),
        ('meta stone-light on ivory', b('stone-light'), b('ivory'), 4.5),
        ('meta stone-light on pistachio', b('stone-light'), b('pistachio'), 4.5),
        ('accent moss on white', b('moss'), W, 4.5),
        ('accent moss on ivory', b('moss'), b('ivory'), 4.5),
        ('accent moss on pistachio', b('moss'), b('pistachio'), 4.5),
        ('accent moss on primary-soft', b('moss'), b('primary-soft'), 4.5),
        ('accent moss on primary', b('moss'), primary, 4.5),
        ('accent moss-hover on white', b('moss-hover'), W, 4.5),
        ('dark fill: white on forest', W, b('forest'), 7.0),
        ('dark fill: white on moss', W, b('moss'), 4.5),
        ('NHS blue on white', b('nhs-blue'), W, 4.5),
        ('NHS blue on ivory', b('nhs-blue'), b('ivory'), 4.5),
        ('danger on danger-soft', b('danger'), b('danger-soft'), 7.0),
        ('footer ink on footer ground', t['footer-ink'], t['footer'], 7.0),
        ('footer text on footer ground', t['footer-text'], t['footer'], 4.5),
        ('footer meta on footer ground', t['footer-meta'], t['footer'], 4.5),
    ]
    if not quiet:
        print(f'\nCONTRAST  (ink on ground; #88E788 itself is {contrast(primary, W):.2f}:1 on white, so it only ever carries fills)\n')
        print(f'  {"pairing":<40}{"ratio":>9}{"min":>6}   verdict')
    for label, ink, ground, minimum in pairs:
        r = contrast(ink, ground)
        ok = r >= minimum
        failures += not ok
        if not quiet or not ok:
            print(f'  {label:<40}{r:8.2f}:1{minimum:5.1f}   {"PASS" if ok else "FAIL"}')

    print(f'\n{"FAILED: " + str(failures) if failures else "OK: every colour is on the primary hue and every pairing passes"}')
    return 1 if failures else 0


if __name__ == '__main__':
    sys.exit(main('--quiet' in sys.argv))
