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

## Checks

```bash
php artisan serve --port=8899
node tests/manual/nhs-checklist.mjs    # 79 checks across the NHS pages
```

## Deployment notes

`FORCE_HTTPS=true` is required behind any TLS-terminating proxy, and
`trustProxies` is enabled in `bootstrap/app.php`. SQLite lives on the container
filesystem, so enquiries reset on redeploy — fine for review, not for production.
