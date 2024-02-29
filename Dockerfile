FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www/SpyBird

COPY . .

RUN chmod +x conf/boot.sh && \
    conf/boot.sh && \
    chown -R www-data:www-data /var/www/SpyBird && \
    chmod -R 755 /var/www/SpyBird

EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]
