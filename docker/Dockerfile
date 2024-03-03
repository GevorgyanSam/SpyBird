FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www/SpyBird

COPY . .

RUN chmod +x docker/boot.sh && \
    docker/boot.sh && \
    chown -R www-data:www-data /var/www/SpyBird && \
    chmod -R 755 /var/www/SpyBird

EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]
