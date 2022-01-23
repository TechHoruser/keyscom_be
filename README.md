# Keyscom API

## About

This project is the backend app for the Keyscom. This application implements and easy method to add and remove users access by ssh to servers.

## Installation

Run `cp .env .env.local` and change the configuration settings in `.env.local`.

Run `docker-compose up -d` to run app.

Run `docker-compose exec php composer install` to install the vendors.

The application is running in `http://localhost:8080`

## Configurations

### PHPStorm

#### PHP Interpreter

1. Access to PHP Settings (`File > Settings > PHP`)
2. Select PHP language level (`PHP 7.4`)
3. Add new interpreter (`From Docker`)
   1. New server (`Docker`) - Unix socket defaut
   2. Configuration File `./docker-compose.yml`
   3. Service `php`
   4. Automatic path mapping

#### Tests

1. Add new Test Frameworks (`File > Settings > PHP > Test Framework`)
2. Add new
   1. Not modify path mapping
   2. Still mark `Use Composer Autoload`
   3. Fill path with `/var/www/html/vendor/autoload.php`
   4. Mark `Default configuration file` and fill in with `/var/www/html/phpunit.xml`
3. Create configuration file `cp ./phpunit.xml.dist ./phpunit.xml`

#### Database

1. Access to Database (right menu)
2. Click on `New > Datasource > PostgreSQL`
3. Config the connection by `.env.local` file params
4. Download drivers on the same window if you haven't
