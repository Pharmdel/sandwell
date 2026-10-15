import colorsys, json, sys

def hsl(h, s, l):
    r, g, b = colorsys.hls_to_rgb(h/360, l/100, s/100)
    return '#%02x%02x%02x' % (round(r*255), round(g*255), round(b*255))

def lum(hexv):
    h = hexv.lstrip('#'); c = [int(h[i:i+2], 16)/255 for i in (0, 2, 4)]
    c = [x/12.92 if x <= 0.03928 else ((x+0.055)/1.055)**2.4 for x in c]
    return 0.2126*c[0] + 0.7152*c[1] + 0.0722*c[2]

def ratio(a, b):
    la, lb = sorted([lum(a), lum(b)], reverse=True)
    return (la+0.05)/(lb+0.05)

def hsl_of(hexv):
    h = hexv.lstrip('#'); r, g, b = [int(h[i:i+2], 16)/255 for i in (0, 2, 4)]
    hh, l, s = colorsys.rgb_to_hls(r, g, b)
    return round(hh*360), round(s*100), round(l*100)

PRIMARY = '#88e788'
H = hsl_of(PRIMARY)[0]          # 120 -> every colour below shares this hue

P = {
  # the brand colour itself, and its relatives
  'primary':         PRIMARY,                   # fills: buttons, highlights, active states, footer
  'primary-hover':   hsl(H, 58, 63),            # a touch deeper for hover on fills
  'primary-soft':    hsl(H, 66, 86),            # selected / active tint
  # light grounds, all lifted from the same hue
  'ivory':           hsl(H, 62, 97),            # page canvas
  'pistachio':       hsl(H, 64, 92),            # soft band
  'pistachio-line':  hsl(H, 46, 80),            # border on a band
  'hairline':        hsl(H, 26, 87),            # card/input borders
  # ink: the same hue taken dark
  'forest':          hsl(H, 46, 11),            # headings, text on primary, dark fills
  'forest-deep':     hsl(H, 50, 7),             # darkest (rare)
  # text-safe accent: the same hue taken to a readable mid-dark
  'moss':            hsl(H, 62, 24),            # accent text on light grounds
  'moss-hover':      hsl(H, 66, 17),
  # neutrals: low-saturation tints of the same hue
  'stone':           hsl(H, 11, 31),            # body text
  'stone-dark':      hsl(H, 15, 20),            # secondary text sitting on primary
  'stone-light':     hsl(H, 9, 38),             # small meta text
  'nhs-blue':        '#005eb8',                 # the NHS's own colour, never derived
}
W = '#ffffff'

grounds = {'white': W, 'ivory': P['ivory'], 'pistachio': P['pistachio'],
           'primary-soft': P['primary-soft'], 'primary': P['primary'], 'primary-hover': P['primary-hover']}

rows = [
 # (label, ink, ground, minimum)
 ('heading  forest on white',        P['forest'],     W,                    7.0),
 ('heading  forest on ivory',        P['forest'],     P['ivory'],           7.0),
 ('heading  forest on pistachio',    P['forest'],     P['pistachio'],       7.0),
 ('heading  forest on primary',      P['forest'],     P['primary'],         7.0),
 ('heading  forest on primary-hover',P['forest'],     P['primary-hover'],   7.0),
 ('heading  forest on primary-soft', P['forest'],     P['primary-soft'],    7.0),
 ('body     stone on white',         P['stone'],      W,                    4.5),
 ('body     stone on ivory',         P['stone'],      P['ivory'],           4.5),
 ('body     stone on pistachio',     P['stone'],      P['pistachio'],       4.5),
 ('body     stone on primary-soft',  P['stone'],      P['primary-soft'],    4.5),
 ('2nd      stone-dark on primary',  P['stone-dark'], P['primary'],         4.5),
 ('2nd      stone-dark on prim-hover',P['stone-dark'],P['primary-hover'],    4.5),
 ('meta     stone-light on white',   P['stone-light'],W,                    4.5),
 ('meta     stone-light on ivory',   P['stone-light'],P['ivory'],           4.5),
 ('meta     stone-light on pistachio',P['stone-light'],P['pistachio'],      4.5),
 ('accent   moss on white',          P['moss'],       W,                    4.5),
 ('accent   moss on ivory',          P['moss'],       P['ivory'],           4.5),
 ('accent   moss on pistachio',      P['moss'],       P['pistachio'],       4.5),
 ('accent   moss on primary-soft',   P['moss'],       P['primary-soft'],    4.5),
 ('accent   moss-hover on white',    P['moss-hover'], W,                    4.5),
 ('accent   moss on primary',        P['moss'],       P['primary'],         4.5),
 ('dark fill white on forest',       W,               P['forest'],          7.0),
 ('dark fill white on moss',         W,               P['moss'],            4.5),
 ('nhs blue on white',               P['nhs-blue'],   W,                    4.5),
 ('nhs blue on ivory',               P['nhs-blue'],   P['ivory'],           4.5),
]
bad = 0
print(f"  primary {PRIMARY} = hsl{hsl_of(PRIMARY)}  -> hue {H} anchors everything\n")
print(f"  {'pair':<36}{'ratio':>8}{'min':>6}   verdict")
print('  ' + '-'*62)
for label, a, b, m in rows:
    r = ratio(a, b); ok = r >= m
    bad += (not ok)
    print(f"  {label:<36}{r:7.2f}:1{m:5.1f}   {'PASS' if ok else 'FAIL'}")
print(f"\n  {len(rows)-bad}/{len(rows)} pass\n")
print('  derived values:')
for k, v in P.items():
    print(f"    {k:<15}{v}   hsl{hsl_of(v)}   lum {lum(v):.3f}")
json.dump(P, open('/tmp/hollytree-design/palette.json', 'w'), indent=1)
