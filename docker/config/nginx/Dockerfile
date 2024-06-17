FROM ubuntu:22.04
RUN apt-get update
RUN apt-get install -y \
   apt-utils \
   nginx \
   nano

RUN chown -Rf www-data:www-data /var/lib/nginx
COPY nginx.conf /etc/nginx/nginx.conf
COPY aaxistest.conf /etc/nginx/sites-enabled/aaxistest.conf
RUN rm /etc/nginx/sites-enabled/default

ENV TZ=America/Argentina/Buenos_Aires
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

WORKDIR /var/www/html

EXPOSE 80
EXPOSE 443

CMD ["nginx"]