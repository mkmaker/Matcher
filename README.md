# Matcher

## Requirements

-   PHP 8.1
-   composer 2

## How to run?

-   Clone the project and create .env file
-   Copy .env.example to .env and add database credentials
-   Create a database, for e.g "matcher", and add this name to .env `DB_DATABASE` field
-   Seed the data by running `php artisan db:seed`
-   Run `php artisan serve` and check `localhost:8000/api/match/1` (ids can be 1 to 20)
