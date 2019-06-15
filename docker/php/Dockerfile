FROM php:7.3-alpine

RUN apk add --update \
    coreutils \
    curl

RUN rm -rf /var/cache/apk/* && rm -rf /tmp/* && \
    echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
        && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
        && php composer-setup.php && rm composer-setup.php* \
        && chmod +x composer.phar && mv composer.phar /usr/bin/composer

ADD php.ini /etc/php7/conf.d/

CMD ["php"]

WORKDIR /app
