# Mobifintech

A minimal Laravel application with separate `staging` and `production` branches. The home page shows the current `APP_ENV`, so each server can identify its environment.

## Requirements

- PHP 8.3 or later
- Composer
- SQLite for the default local setup, or MySQL for Hostinger

## Run locally

From the repository root:

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

Open `http://127.0.0.1:8000`. The default `.env.example` uses SQLite. Keep `.env` out of Git; it contains the app key and environment-specific settings.

Run the test suite with:

```bash
php artisan test
```

## Branch workflow

Make and test changes on `staging` first:

```bash
git switch staging
git pull origin staging
# edit and test the app
php artisan test
git add .
git commit -m "Describe the change"
git push origin staging
```

After the staging site has been tested, promote the tested commit to `production`:

```bash
git switch production
git pull origin production
git merge --ff-only staging
git push origin production
```

Use separate server-side `.env` files and databases for staging and production. Set `APP_ENV=staging` on the staging server and `APP_ENV=production` on the live server; keep `APP_DEBUG=false` on both public sites. Create each database and database user in Hostinger before configuring the corresponding `.env`. Laravel migrations create or update tables inside that database; they do not provision the database itself.
