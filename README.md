# Установка проекта
Для начала нужно ввести в консоль

``docker-compose up -d``

Затем создадим базу pitch, для этого нужно ввести в консоль

``
 docker exec -it mysql mysql -p
``

затем пароль, по дефолту - root
затем вводим 

``
create database pitch
``

Далее нужно выполнить миграции для этого нужно ввести команду

``
composer migration-execute
``

далее жмем enter

## Готово.

