FROM php:7.4-fpm-alpine

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it

RUN apk --update --no-cache add git

RUN docker-php-ext-install pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ; cd /root ; wget https://get.symfony.com/cli/installer -O - | bash ;  php-fpm ; chmod -R 777 /var/www/public/uploads ; chmod -R 777 /var/www/config/jwt

EXPOSE 9000
