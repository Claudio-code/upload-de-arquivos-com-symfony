FROM nginx:alpine

WORKDIR /var/www

RUN chmod -R 777 /var/www/public/uploads

RUN chmod -R 777 /var/www/config/jwt

CMD ["nginx"]


EXPOSE 80 443
