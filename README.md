# Corpsee Site

Движок сайта [corpsee.com](https://corpsee.com).

## Установка (или переустановка/чистый запуск)

```shell
# Остановка приложения с удалением тома
docker compose down --volumes
# Запуск контейнеров с пересборкой образов и пересозданием из них контейнеров
docker compose up -d --force-recreate --build
```

```shell
symfony console doctrine:migrations:migrate --no-interaction
```

Импорт данных:  
```shell
symfony console app:import-pull-requests -v data/pull_requests.csv
symfony console app:import-projects -v data/projects.csv
symfony console app:import-pictures -v data/pictures.csv data/tags.csv data/pictures_tags.csv

cd ~/Projects/Personal/corpsee-site
cp --recursive --no-target-directory "data/images/picture" "public/images/picture"
cp --recursive --no-target-directory "data/images/picture_gray" "public/images/picture_gray"
cp --recursive --no-target-directory "data/images/picture_min" "public/images/picture_min"
```

Пароль (admin/admin):
```sql
symfony run psql -c "INSERT INTO admin (id, username, roles, password) VALUES ('018f3f30-29a5-7036-940b-64c7ce3fd498', 'admin', '[\"ROLE_ADMIN\"]', '\$2y\$13\$goaTn2PMTCvqmi5IdEF40O1mP/1WxXwiY4XgGahoR2yqAwrIPokK.')"
```

```shell
symfony server:ca:install
symfony server:start -d
```

## Запуск

```shell
docker compose up -d
symfony server:start -d
```

## Сборка frontend-а (dev)

```shell
symfony run npm run dev
symfony run -d npm run watch
```

## Обновление зависимостей

```shell
symfony composer update "symfony/*"
```

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

