<p align="center"><img src="./resources/assets/icon.png" width="150" alt="SpyBird Logo"></p>

# SpyBird - Chat Application

**SpyBird** is a modern web chat application that redefines online communication. Whether connecting with friends, collaborating with colleagues, or meeting new people, SpyBird offers a seamless and secure platform for real-time conversations.

## Getting Started

### Prerequisites

Make sure you have the following dependencies installed on your Debian-based Linux system:

-   JavaScript: `nodejs`, `npm`
-   PHP: `php`, `composer`, `php-curl`, `php-xml`, `php-mysql`, `php-imagick`, `php-gd`
-   MySQL: `default-mysql-server`

### Installation

Follow these steps to install SpyBird:

1. **Clone Repository:**

    ```bash
    git clone https://github.com/GevorgyanSam/SpyBird.git
    cd ./SpyBird
    ```

2. **Install Dependencies:**

    ```bash
    composer install
    npm install
    ```

### Database Setup

Before building the project, set up the `.env` file and run the migrations:

1. **Run Migrations:**

    ```bash
    php artisan migrate
    ```

### Build and Run

After setting up the database, build and run the application:

1. **Create Storage Link:**

    ```bash
    php artisan storage:link
    ```

2. **Build Application:**

    ```bash
    npm run build
    # or
    npm run production
    ```

3. **Run Server:**

    ```bash
    php artisan serve
    ```

The application should be accessible in your web browser at [http://localhost:8000](http://localhost:8000).
