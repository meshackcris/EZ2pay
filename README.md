# Orion Pay

Orion Pay is a Laravel 12 + Livewire 3 application for managing payments and related back‑office workflows. It includes user authentication, referrer/brand management, KYC review, withdrawals, and transaction tables (Interac, EFT, FTD).

## Tech Stack

- PHP 8.2+, Laravel 12, Composer
- Livewire 3, Blade, Alpine.js
- Tailwind CSS, Vite
- Pest (tests), Laravel Breeze (auth scaffold)

## Prerequisites

- PHP 8.2+
- Composer 2.x
- Node.js 18+ and npm
- SQLite (default) or MySQL/PostgreSQL if preferred

## Quick Start

1. Clone and install dependencies
   - `composer install`
   - `npm install`
2. Environment
   - Copy env: `cp .env.example .env` (Windows: `copy .env.example .env`)
   - Generate key: `php artisan key:generate`
   - By default the app uses SQLite (`config/database.php`). Ensure the file exists: `database/database.sqlite` (created automatically in many setups).
3. Database
   - Run migrations: `php artisan migrate`
   - Seed sample data (creates a test user): `php artisan db:seed`
4. Run the app (single command)
   - `composer run dev` (runs PHP server, queue listener, and Vite dev server concurrently)

Alternatively run services separately:

- API/App: `php artisan serve`
- Queue (if using async queues): `php artisan queue:listen --tries=1`
- Frontend assets: `npm run dev`

Open the app at the URL shown by `php artisan serve` (usually http://127.0.0.1:8000).

## Configuration

Update `.env` as needed:

- App: `APP_NAME`, `APP_ENV`, `APP_URL`
- Database (to switch from SQLite): `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Queue: `QUEUE_CONNECTION` (e.g., `sync` or `database`)
- Mail: `MAIL_MAILER`, `MAIL_HOST`, etc.
- Storage (optional S3): configure `FILESYSTEM_DISK` and S3 keys if used

If using `QUEUE_CONNECTION=database`, create the jobs table then migrate:

```
php artisan queue:table
php artisan migrate
```

## Testing

- Run test suite: `./vendor/bin/pest` or `php artisan test`

## Build for Production

- Assets: `npm run build`
- Optimize: `php artisan optimize`
- Cache config/routes (optional): `php artisan config:cache && php artisan route:cache`

## Notable Paths

- Controllers: `app/Http/Controllers`
- Livewire Views: `resources/views/livewire`
- Layouts/Views: `resources/views`
- Migrations: `database/migrations`
- Seeders: `database/seeders`

Examples in this project:

- Transactions table: `resources/views/livewire/transaction-table.blade.php`
- Payments tables: `resources/views/livewire/{interac,eft,ftd}-payments-table.blade.php`
- KYC & Withdrawals: `resources/views/kyc.blade.php`, `resources/views/withdrawals.blade.php`

## Optional: Docker via Laravel Sail

Sail is included as a dev dependency.

```
composer install
cp .env.example .env
php artisan key:generate
php artisan vendor:publish --tag=laravel-assets --force
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
```

## Contributing

This is a private, proprietary project. External contributions are not accepted. For internal changes, follow the team’s engineering guidelines.

## License

Proprietary and confidential. All rights reserved. Unauthorized copying, modification, distribution, or use is strictly prohibited without explicit written permission from the owner.
