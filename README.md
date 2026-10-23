# It's All Widgets!

[![Build Status](https://travis-ci.org/hillelcoren/itsallwidgets.svg?branch=master)](https://travis-ci.org/hillelcoren/itsallwidgets)

The website is built with:

- [Laravel](https://laravel.com/)
- [Vue.js](https://vuejs.org/)
- [Bulma](https://bulma.io/)

## Getting Started

If you plan to submit changes it's best to fork the repo, otherwise clone it.

`git clone git@github.com:hillelcoren/itsallwidgets.git`

Next `cd` into the project directory and run:

`composer install`

Duplicate `.env.example` and rename it `.env`

Then run:

`php artisan key:generate`

Once the .env file is setup you can initialize the database by running:

`php artisan migrate`

To support Google OAuth make sure to add the credentials in the .env file

## Export route

The new It's All Widgets app copies this app's data (tables, media and podcast files) through a
signed export route under `/iaw-export`. It can also put this app into maintenance for the final
copy on launch day (with a lease, so the site comes back up by itself if the new app never says so).

- It only answers on the legacy host (`LEGACY_EXPORT_HOST`, `legacy.itsallwidgets.com` by default),
  and only to requests signed with HMAC-SHA256 using `LEGACY_EXPORT_KEY` (at least 32 characters,
  the same value as in the new app). Anything else gets a plain 404.
- It only answers over HTTPS (plain HTTP gets the same 404).
- Maintenance it puts on is tagged in the down file. If the site was taken down by hand
  (`php artisan down`), the export leaves it alone and answers 409.
- While an `itsallwidgets:*` command runs it leaves a marker in `storage/framework/iaw-busy`, so
  the new app waits for it. That folder must be writable by the PHP-FPM user (the commands also
  run from web requests).
- The code lives in `routes/export.php`, `app/Http/Middleware/VerifyIawExport.php`,
  `app/Http/Controllers/ExportController.php` and `app/Support`.

Environment variables:

```
LEGACY_EXPORT_KEY=
LEGACY_EXPORT_HOST=legacy.itsallwidgets.com
```

To deploy (production runs PHP 7.3):

1. `git pull`
2. `composer install`, only when `composer.lock` changed
3. Set `LEGACY_EXPORT_KEY` (and `LEGACY_EXPORT_HOST` if it differs) in `.env`
4. `php artisan config:cache`
5. `php artisan route:clear`
6. Reload PHP-FPM: `sudo -n /usr/sbin/service php7.3-fpm reload`
