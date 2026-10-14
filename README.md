# Hollytree Pharmacy — website

Laravel 13 rebuild of sandwellpharmacygroup.co.uk.

## Running locally

```bash
composer install
npm install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm run dev        # or: npm run build
php artisan serve
```

## What's here

- **83 pages** — six branches, the full NHS and private service catalogue, a
  35-condition A–Z with a symptom checker on each, health hub articles and shop.
- **Service catalogue** — `config/services.php` drives `/services`,
  `/services/{slug}`, the menu and both hub pages, so a service cannot appear in
  a list without a page behind it.
- **Forms** — 12 enquiry forms defined in config and validated generically by
  `ServiceEnquiryController`; adding one needs no PHP.
- **Checkers** — blood pressure eligibility, MDS route, flu & Covid eligibility,
  and the per-condition symptom quiz.

## Colour system

The whole palette is built on one colour: **`#88E788`**, called `primary`. It is hue
120 at 72% lightness, which makes it a *fill* colour. Against white it reaches only
1.5:1, so it can carry dark text but can never be text, and white can never sit on it.
Every other colour is that hue taken lighter (grounds), darker (ink and the text-safe
accent) or desaturated (neutrals):

| role | token | value |
|---|---|---|
| brand fill: buttons, hero, footer, highlights | `brand-primary` / `-hover` / `-soft` | `#88e788` / `#6ad76a` / `#c4f3c4` |
| page and band grounds | `brand-ivory` / `brand-pistachio` | `#f3fcf3` / `#def8de` |
| ink: headings, text on primary | `brand-forest` | `#0f290f` |
| text-safe accent: links, eyebrows | `brand-moss` | `#176317` |
| body, secondary and small text | `brand-stone` / `-dark` / `-light` | `#465846` / `#2b3b2b` / `#586a58` |

Two colours are deliberately *not* derived from the brand because they carry meaning:
the NHS blue (`brand-nhs-blue`) and the danger red (`brand-danger*`).

Tokens live in `resources/css/app.css`. Tailwind's stock palette is switched off
(`--color-*: initial`), so a class like `text-slate-300` renders nothing rather than
quietly introducing an off-palette colour.

Rules that keep it working:

- **Never put text in `brand-primary`, and never put white on it.** Use `brand-forest`
  on a primary fill and `brand-moss` for accent text on a light ground.
- **A primary button on a primary ground vanishes.** On the hero, footer and other
  green grounds the main action is `<x-btn variant="dark">`.
- Selected states (chosen option, pressed toggle, active tab) use the brand colour.
- `.editorial-highlight` is a marker stripe in the brand colour. Green grounds set
  `--highlight: #fff` so the stripe still shows.

## Checks

```bash
php artisan serve --port=8899

python3 tests/manual/palette-check.py        # every colour on the primary hue; 29 contrast pairings
node tests/manual/crawl.mjs                  # every page, asset and #fragment resolves
node tests/manual/nhs-checklist.mjs          # 79 checks across the NHS pages
```

`palette-check.py` reads the tokens out of `app.css`, so the stylesheet stays the only
source of truth. If you change a colour, run it first.

## Deployment notes

`FORCE_HTTPS=true` is required behind any TLS-terminating proxy, and
`trustProxies` is enabled in `bootstrap/app.php`. SQLite lives on the container
filesystem, so enquiries reset on redeploy — fine for review, not for production.
