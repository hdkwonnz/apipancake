#!/usr/bin/env bash

#### https://laravel-news.com/laravel-scheduler-queue-docker

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

# if [ "$env" != "local" ]; then
#     echo "Caching configuration..."
#     (cd /var/www/html && php artisan config:cache && php artisan route:cache && php artisan view:cache)
# fi

if [ "$role" = "app" ]; then
    echo "Running the nginx...."
    exec nginx -g 'daemon off;'
    
elif [ "$role" = "queue" ]; then
    echo "Running the queue...."
    php /var/www/html/artisan queue:work --tries=2

elif [ "$role" = "scheduler" ]; then
    echo "Running the Scheduler...."
    while [ true ]
    do
      php /var/www/html/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
