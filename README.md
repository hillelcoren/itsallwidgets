# It's All Widgets!

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
