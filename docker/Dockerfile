FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www/SpyBird

COPY . .

# Update system
RUN apt update && \
    apt full-upgrade -y && \
    apt autoremove -y && \
    apt autoclean

# Install dependencies
RUN apt install -y software-properties-common apache2 nano && \
    add-apt-repository ppa:ondrej/php && \
    apt install -y composer php php-curl php-cli php-json php-zip php-mbstring php-xml php-mysql php-imagick php-gd nodejs npm

# Configure server
RUN cp /var/www/SpyBird/docker/spybird.conf /etc/apache2/sites-available/ && \
    cd /etc/apache2/sites-available && \
    a2dissite 000-default.conf && \
    a2ensite spybird.conf && \
    a2enmod rewrite && \
    service apache2 restart && \
    chmod -R 777 /var/www/SpyBird

EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]
