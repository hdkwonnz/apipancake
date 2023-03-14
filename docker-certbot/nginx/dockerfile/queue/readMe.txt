1. docker compose exec -it php sh
2. cd cd /var/www/laravel8x/
3. php artisan queue:work --tries=5 --sleepk=10 --queue=user_queue > storage/logs/queue.log &
4. cd cd /var/www/seller.laravel8x/
5. cd /var/www/seller.laravel8x/
6. php artisan queue:work --tries=5 --sleep=10 --queue=seller_queue > storage/logs/queue.log &
