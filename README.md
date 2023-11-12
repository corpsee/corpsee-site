# Corpsee Site

## Установка

docker compose up -d  

symfony console doctrine:migrations:migrate  

Импорт данных:  
symfony console app:import-pull-requests -v data/pull_requests.csv  
symfony console app:import-projects -v data/projects.csv  
symfony console app:import-pictures -v data/pictures.csv data/tags.csv data/pictures_tags.csv  

Пароль (admin/admin):
```sql
symfony run psql -c "INSERT INTO admin (id, username, roles, password) VALUES (nextval('admin_id_seq'), 'admin', '[\"ROLE_ADMIN\"]', '\$2y\$13\$goaTn2PMTCvqmi5IdEF40O1mP/1WxXwiY4XgGahoR2yqAwrIPokK.')"
```

symfony server:ca:install  
symfony server:start -d

## Запуск

docker compose up -d  
symfony server:start -d  


## Чистый запуск

docker compose down --volumes  
docker compose up -d --force-recreate --build  

symfony console doctrine:migrations:migrate  

symfony console app:import-pull-requests -v data/pull_requests.csv  
symfony console app:import-projects -v data/projects.csv  
symfony console app:import-pictures -v data/pictures.csv data/tags.csv data/pictures_tags.csv  

Пароль (admin/admin):
```sql
symfony run psql -c "INSERT INTO admin (id, username, roles, password) VALUES (nextval('admin_id_seq'), 'admin', '[\"ROLE_ADMIN\"]', '\$2y\$13\$goaTn2PMTCvqmi5IdEF40O1mP/1WxXwiY4XgGahoR2yqAwrIPokK.')"
```

symfony server:start -d  

## Сборка frontend-а

symfony run npm run dev

symfony run -d npm run watch

## Other

https://symfony.com/bundles/EasyAdminBundle/current/crud.html


symfony new guestbook --version=6.2 --php=8.1 --webapp --docker

symfony server:ca:install

symfony server:start -d
symfony server:stop

symfony open:local

symfony server:log

symfony local:php:list

symfony console make:controller MainController

docker compose up -d

symfony run psql
symfony run pg_dump --data-only > dump.sql
symfony run psql < dump.sql

symfony var:export

symfony console make:migration
symfony console doctrine:migrations:migrate

symfony composer req "admin:^4"
symfony console make:admin:dashboard
symfony console make:admin:crud

docker compose down --volumes
docker compose up -d --force-recreate --build

