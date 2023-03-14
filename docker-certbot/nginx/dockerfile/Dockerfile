FROM ubuntu:22.04

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=Pacific/Auckland
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get upgrade -y && apt-get autoremove -y \
    && apt-get install software-properties-common -y \
    && add-apt-repository -y ppa:ondrej/nginx \
    && apt-get install -y nginx && apt-get autoclean

RUN mkdir -p /var/www/api

RUN chown -R $USER:$USER /var/www/api

RUN chmod -R 755 /var/www/api

COPY vhost/api /etc/nginx/sites-available/api

RUN ln -s /etc/nginx/sites-available/api /etc/nginx/sites-enabled/

RUN unlink /etc/nginx/sites-enabled/default

# COPY nginx_conf/nginx.conf /etc/nginx/

##only for this versions of nginx below code otherwise erros comes back like as follow
##nginx: [warn] duplicate extension "woff", content type: "font/woff2", previous content type: "font/woff" in /etc/nginx/mime.types:29
##https://stackoverflow.com/questions/72097776/duplicate-extension-woff-content-type-font-woff2-previous-content-type
# COPY nginx_conf/mime.types /etc/nginx/mime.types

COPY start.sh /usr/local/bin/start
RUN chmod u+x /usr/local/bin/start
CMD ["/usr/local/bin/start"]
