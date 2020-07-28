## About this test

1) You have to create a set of APIs with native PHP7 or using a framework, allowing a consumer to create their own wishlist and add products.

2) In addition, please develop a command (CLI) able to export on CSV file the wishlist of all users.
CSV file format: user;title wishlist;number of items

The project must run through a docker and be testable through unit tests.

Load the project on github or send it as a zip.

Bonus: protect the routes with the authentication.

Time limit: 1 week / 10 days - asap is better :)

## The project

My project is built using Laravel 7.x, MariaDB, Composer and PHP7.

## Running the project

To build the project to a docker container run into the main project dir:

    docker-compose build

    docker-compose up -d


To initialize Laravel after docker image were built:

    cp .env-docker.example .env
    
    docker-compose exec php /usr/local/bin/composer install

    docker-compose exec php php artisan key:generate

    docker-compose exec php php artisan migrate

To run tests:

    docker-compose exec php vendor/bin/phpunit

To list available APIs:

    docker-compose exec php php artisan route:list

To seed the DB to try CSV export:

    docker-compose exec php php artisan db:seed --class WishlistSeeder

To execute CSV export:

    docker-compose exec php php artisan report:csv prova.csv

Then you'll find a file called `prova.csv` into the local `src/` dir (or you can check the docker volume).


## License

This test is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
