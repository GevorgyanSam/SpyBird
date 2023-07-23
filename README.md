<p align="center"><a href="#" target="_blank"><img src="./resources/assets/icon.png" width="150" alt="SpyBird Logo"></a></p>

# SpyBird - Chat Application

## About SpyBird

_**SpyBird** is a cutting-edge web chat application that redefines the way you communicate and connect with others online. Whether you're catching up with friends, collaborating with colleagues, or meeting new people, SpyBird offers a seamless and secure platform for real-time conversations._

## Installation

_Provide step-by-step instructions on how to install the project and its dependencies._

1. _Clone Repository_
    `git clone https://github.com/GevorgyanSam/SpyBird.git`
2. _Navigate To Project_
    `cd ./SpyBird`
3. _Install Dependencies_
    - `composer install`
    - `npm install`

## Database

_Before building the project, we need to set up the `.env` file and run the migrations_

1. _Run Migrations_
    `php artisan migrate`

## Build

_After setting up the database, we need to build the application_

1. _Create Storage Link_
    `php artisan storage:link`
2. _Run Scripts_
    `npm run build`

## Run

_After building the application, we need to run it on the server_

1. _Run Server_
    `php artisan serve`

_After running the server, the application should be accessible in your web browser at http://localhost:8000_