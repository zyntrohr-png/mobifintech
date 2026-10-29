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

## Paytm staging checkout

The staging branch includes a sandbox-only Paytm hosted checkout for a fixed INR 1.00 test order. It signs the request on the server with Paytm's official `paytm/paytmchecksum` package and verifies the callback signature and order amount before recording the result. It does not collect card details or fulfill orders.

Set these values only in the staging server's ignored `.env` file (or your local staging `.env` for development), then run `php artisan config:clear`:

```dotenv
PAYTM_ENVIRONMENT=TEST
PAYTM_MERCHANT_ID=your_rotated_sandbox_merchant_id
PAYTM_MERCHANT_KEY=your_rotated_sandbox_merchant_key
PAYTM_MERCHANT_WEBSITE=WEBSTAGING
PAYTM_INDUSTRY_TYPE=Retail
PAYTM_CHANNEL_ID=WEB
```

The callback URL must be publicly reachable over HTTPS for a real sandbox round trip. A local `localhost` URL cannot receive Paytm's callback. Never put merchant credentials in Git, a GitHub workflow, or chat. Any key previously shared outside a secret store should be rotated before use. This test checkout is not intended for production payments.

## GitHub Actions deployment to Hostinger

Pushing to `staging` or `production` runs the test suite. After tests pass, the `Test and deploy to Hostinger` workflow deploys to the matching GitHub Environment. Deployment is skipped until that environment has a `HOSTINGER_APP_PATH` variable.

Create GitHub Environments named exactly `staging` and `production` under **Settings → Environments**. In each environment, configure:

- Variable `HOSTINGER_APP_PATH`: the existing absolute server directory containing the Laravel app's `artisan` file. It must be separate for staging and production.
- Optional variable `HOSTINGER_PHP_BIN`: the PHP CLI command on the server; defaults to `php`.
- Secrets `HOSTINGER_SSH_HOST`, `HOSTINGER_SSH_USER`, `HOSTINGER_SSH_PORT`, `HOSTINGER_SSH_PRIVATE_KEY`, and `HOSTINGER_SSH_KNOWN_HOSTS`.

The target directory must already exist, be writable by the SSH user, and contain its environment-specific `.env` file before the first deployment. Configure the site's document root to point to that app directory's `public` folder. Keep each server `.env` and database separate; the workflow deliberately preserves `.env` and `storage`, and runs `php artisan migrate --force` after uploading.

Add the SSH private key only as the `HOSTINGER_SSH_PRIVATE_KEY` GitHub Environment secret. Set `HOSTINGER_SSH_KNOWN_HOSTS` to the server's verified known-hosts entry; verify its fingerprint with Hostinger before trusting it. Do not put keys, passwords, database credentials, or `.env` contents in the repository.
