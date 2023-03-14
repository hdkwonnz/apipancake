FROM ubuntu:22.04

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=Pacific/Auckland
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get upgrade -y && apt-get autoremove -y \
    && apt-get install software-properties-common -y \
    && add-apt-repository -y ppa:ondrej/nginx \
    && apt-get install -y nginx && apt-get autoclean

COPY local/default.conf /etc/nginx/conf.d/default.conf

# RUN sed -i "s/user  nginx/user laravel/g" /etc/nginx/nginx.conf
COPY local/nginx.conf /etc/nginx/nginx.conf
RUN mkdir -p /var/www/html

COPY local/start.sh /usr/local/bin/start

RUN chmod u+x /usr/local/bin/start

CMD ["/usr/local/bin/start"]
