# Corpsee Site

Site [corpsee.com](https://corpsee.com) sources.

## Install (clear start)

```shell
# Stop and remove volumes
docker compose down --volumes
# Start with build
docker compose up -d --force-recreate --build
```

```shell
symfony console doctrine:migrations:migrate --no-interaction
```

Data import:  
```shell
symfony console app:import-pull-requests -v data/pull_requests.csv
symfony console app:import-projects -v data/projects.csv
symfony console app:import-pictures -v data/pictures.csv data/tags.csv data/pictures_tags.csv

cd ~/Projects/Personal/corpsee-site
cp --recursive --no-target-directory "data/images/picture" "public/files/images/picture"
cp --recursive --no-target-directory "data/images/picture_gray" "public/files/images/picture_gray"
cp --recursive --no-target-directory "data/images/picture_min" "public/files/images/picture_min"
```

Login/password (admin/admin):
```sql
# symfony console security:hash-password

symfony run psql -c "INSERT INTO admin (id, username, roles, password) VALUES ('018f3f30-29a5-7036-940b-64c7ce3fd498', 'admin', '[\"ROLE_ADMIN\"]', '\$2y\$13\$goaTn2PMTCvqmi5IdEF40O1mP/1WxXwiY4XgGahoR2yqAwrIPokK.')"
```

```shell
symfony server:ca:install
symfony server:start -d
```

## Start

```shell
docker compose up -d && symfony server:start -d
```

## Frontend build (dev)

```shell
npm install

symfony run npm run dev
symfony run -d npm run watch
```

## Dependencies update

```shell
symfony composer update "symfony/*"
```
