# It's All Widgets!

An open list of apps built with Google Flutter

## Getting Started

If you plan to submit changes it's best to fork the repo, otherwise you can just clone it.

`git clone git@github.com:hillelcoren/itsallwidgets.git`

Next `cd` into the project directory and run:

`composer install`

Duplicate `.env.example` and rename it `.env`

Then run:

`php artisan key:generate`

Once the .env file is setup you can initialize the database by running:

`php artisan migrate`

To support Google OAuth make sure to add the credentials in the .env file
