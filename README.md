[![Build Status](https://travis-ci.org/gitoshh/mangoma.svg?branch=develop)](https://travis-ci.org/gitoshh/mangoma)
[![StyleCI](https://github.styleci.io/repos/183754318/shield?branch=develop)](https://github.styleci.io/repos/183754318)

## mangoma
A music app API with an integrated payment feature for premium users. The app allows artistes and admins to add music that users can make use of either by integrating the API to the web frontend or by downloading the music from a http client app.


#### Getting Started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

##### Prerequisites
Before setting up this project. You will need to have the following installed

```
- virtualbox
- vagrant
- Homestead
- Composer
- Apache2
- Mysql
- PHP

```

##### Running project
To run this project locally. You will need to install the applications listed above. If you do not wish to
create a virtual environment to host the project, you do not require virtualbox, vagrant and Homestead.
However you do need to install Apache2, Mysql and PHP on your local machine.

If you would like to run your project on a virtual environment.You will install these applications with the exception of PHP and Mysql.
These come inbuilt with Homestead.

To setup the virtual environment to host your project follow the instructions in the laravel documentation

[Docs](https://laravel.com/docs/5.8/homestead)

To use the alternative modify your env to use your local database credentials.

Local setup: 
- clone the project to your local machine.
```git clone git@github.com:gitoshh/mangoma.git```
- Go to the project root directory
```cd mangoma```
- copy the .env.example to .env
```cp .env.example .env```
- power up vagrant by heading to the Homestead directory and running `vagrant up`
- Back to the root directory of the project generate keys
```php artisan key:generate```
- copy the generate keys to your .env `APP_KEY`
- generate more keys for `JWT_SECRET`
- Install all required packages
```composer install```

##### Running the tests
To run the tests you will need to the run the following command in the root of your project:

`vendor/bin/phpunit tests`

##### Deployment
Add additional notes will be added soon on how to deploy this project on live environments.

##### Built With

- Lumen  - The web framework used
- Jwt    - Token generation
- Stripe - For user subscription
- GCS    - For cloud file storage

##### Authors
Godwin Gitonga

##### License
This project is licensed under the MIT License

##### Additional links for the project
Apiary editor: https://app.apiary.io/godwingitonga/editor
