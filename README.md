# Corpsee Site

## Установка

docker-compose up -d  

symfony console doctrine:migrations:migrate  

Импорт данных:  
symfony console app:import-pull-requests -v pull_requests.csv  
symfony console app:import-projects -v projects.csv  
symfony console app:import-pictures -v pictures.csv tags.csv pictures_tags.csv  

symfony server:ca:install  
symfony server:start -d  

## Запуск

docker-compose up -d  
symfony server:start -d  

## Чистый запуск

docker-compose down --volumes  
docker-compose up -d --force-recreate --build  

symfony console doctrine:migrations:migrate  

symfony console app:import-pull-requests -v pull_requests.csv  
symfony console app:import-projects -v projects.csv  
symfony console app:import-pictures -v pictures.csv tags.csv pictures_tags.csv  

symfony server:start -d  






symfony new guestbook --version=6.2 --php=8.1 --webapp --docker

symfony server:ca:install

symfony server:start -d
symfony server:stop

symfony open:local

symfony server:log

symfony local:php:list

symfony console make:controller MainController

docker-compose up -d

symfony run psql
symfony run pg_dump --data-only > dump.sql
symfony run psql < dump.sql

symfony var:export

symfony console make:migration
symfony console doctrine:migrations:migrate

symfony composer req "admin:^4"
symfony console make:admin:dashboard
symfony console make:admin:crud

docker-compose down --volumes
docker-compose up -d --force-recreate --build

