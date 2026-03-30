#Admin-v2 (PHP 7.3/8.0) - (Laravel-8)

## Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation#installation)

Clone the repository

    git clone https://`<user-bitbucket-username>`@bitbucket.org/mylinex_dev/admin-v2.git


Switch to the repo folder

    cd admin-v2

**_Note_** :- Laravel backend configurations

Install all the dependencies using composer

    composer install

Install NPM Dependencies

    npm install

Copy the example env file and make the required configuration changes in the .env file in laravel backend

    cp .env.example .env

Configure the database connections

Next, open the .env file located at the root of your Laravel project and update the DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD variables to match your MySQL database configuration. Here is an example configuration:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=1521
    DB_DATABASE=mydatabase
    DB_USERNAME=myusername
    DB_PASSWORD=mypassword

Generate a new application key

    php artisan key:generate

Give permissions to storage and bootstrap folders

    chmod -R 777 storage
    chmod -R 777 bootstrap

after run command

    php artisan storage:link

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Run the database seeder and you're done

    php artisan db:seed   
