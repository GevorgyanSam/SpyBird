FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive
ENV LC_ALL=C.UTF-8

WORKDIR /var/www/SpyBird

COPY . .

# Update system
RUN apt update && \
    apt full-upgrade -y && \
    apt autoremove -y && \
    apt autoclean

# Install dependencies
RUN apt install -y software-properties-common apache2 nano curl gnupg && \
    add-apt-repository ppa:ondrej/php && \
    apt install -y composer php php-curl php-cli php-json php-zip php-mbstring php-xml php-mysql php-imagick php-gd nodejs npm

# Configure server
RUN cp /var/www/SpyBird/docker/spybird.conf /etc/apache2/sites-available/ && \
    cd /etc/apache2/sites-available && \
    a2dissite 000-default.conf && \
    a2ensite spybird.conf && \
    a2enmod rewrite && \
    service apache2 restart

# Configure user and permissions
RUN groupadd -g 1000 spybird && \
    useradd -u 1000 -ms /bin/bash -g spybird spybird && \
    chown -R spybird:spybird /var/www/SpyBird && \
    chmod -R 755 /var/www/SpyBird

# Change apache2 executor
RUN sed -i 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=spybird/g' /etc/apache2/envvars && \
    sed -i 's/APACHE_RUN_GROUP=www-data/APACHE_RUN_GROUP=spybird/g' /etc/apache2/envvars

EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]
