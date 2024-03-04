<p align="center"><img src="./resources/assets/icon.png" width="150" alt="SpyBird Logo"></p>

# SpyBird - Chat Application

**SpyBird** is a modern web chat application that redefines online communication. Whether connecting with friends, collaborating with colleagues, or meeting new people, SpyBird offers a seamless and secure platform for real-time conversations.

## Getting Started

### Installation

To install SpyBird using Docker, follow these steps:

1. **Clone Repository:**

    ```bash
    git clone https://github.com/GevorgyanSam/SpyBird.git
    cd ./SpyBird/docker
    ```

2. **Build and Run Docker Containers:**

    ```bash
    docker-compose up -d --build
    # or
    docker compose up -d --build
    ```

3. **Run Migrations:**

    ```bash
    docker-compose exec app php artisan migrate
    # or
    docker compose exec app php artisan migrate
    ```

The application should be accessible in your web browser at [http://localhost:8080](http://localhost:8080).
