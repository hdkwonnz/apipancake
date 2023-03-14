1. docker compose exec -it php sh
2. php artisan queue:work --tries=5 --sleepk=10 > storage/logs/queue.log &
