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

2. **Check and Configure .env Files**

    <div style="margin-top: 15px;">
    Make sure to review and configure the `.env` files located in:<br><br>

    - **Root Directory of the Application**: Contains application-specific environment variables.
    - **Docker Directory**: Contains Docker-specific environment variables.

    These files are crucial for the correct setup and functioning of the application and Docker containers.
    </div>

3. **Build and Run Docker Containers:**

    ```bash
    docker compose up -d --build
    ```

4. **Build Application:**

    ```bash
    bash boot.sh
    ```
5. **Configure Nginx and Local Domains:**

    <div style="margin-top: 15px;">
    If you have Nginx installed on your PC, you can use local domains for your application instead of `localhost:3030`. Run the following command to set up the necessary configurations:

    ```bash
    sudo bash system.sh
    ```

    This command will configure Nginx and update your `/etc/hosts` file to use local domains for accessing SpyBird:

    - `http://app.PREFIX.local`
    - `http://db.PREFIX.local`
    - `http://mail.PREFIX.local`

    Replace `PREFIX` with your actual application prefix configured in `.env`.
    </div>

After completing these steps, SpyBird should be accessible in your web browser at [http://localhost:3030](http://localhost:3030), or using local domains if configured with Nginx. Ensure all steps are followed accurately for a smooth installation and setup process.
