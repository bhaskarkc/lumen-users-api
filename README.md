# Lumen Users API

A simple API using laravel lumen framework.

This API is just a demo of using Laravel Lumen framework to create an API and use redis websockets to broadcast events.

## Routes

```sh
+--------+-------------------+------+------------+------------+------------------------------------------------+
| Method | URI               | Name | Action     | Middleware | Map To                                         |
+--------+-------------------+------+------------+------------+------------------------------------------------+
| GET    | /                 |      | Closure    |            |                                                |
| POST   | /api/v1/user      |      | Controller |            | App\Http\Controllers\UserController@createUser |
| GET    | /api/v1/user      |      | Controller |            | App\Http\Controllers\UserController@index      |
| GET    | /api/v1/user/{id} |      | Controller |            | App\Http\Controllers\UserController@show       |
| PUT    | /api/v1/user/{id} |      | Controller |            | App\Http\Controllers\UserController@update     |
| DELETE | /api/v1/user/{id} |      | Controller |            | App\Http\Controllers\UserController@destroy    |
+--------+-------------------+------+------------+------------+------------------------------------------------+
```

## Commandline help

### Makefile

All commands are included in `Makefile`

> make

```sh
================================================
                 Lumen Users API
================================================
up              Spins up docker container
down            Tear down the docker container
recreate        Force recreate and start the docker container
dbshow          Show tables
dbschema        Dump mysql db
testbuild       Build test docker image
phpunit         Builds a new test container and runs phpunit test on it
phpunit-local   Runs phpunit from the application container.
help            Prints this help screen.
```

## PHP artisan command via `Makefile`

> make artisan migrate

```sh
docker exec -it lumen-user-api bash -c "php artisan migrate:status"
+------+--------------------------------------+-------+
| Ran? | Migration                            | Batch |
+------+--------------------------------------+-------+
| Yes  | 2020_12_27_160122_create_table_users | 1     |
+------+--------------------------------------+-------+
```

> make dbshow lumen users

```sh
docker exec -it lumen-user-api bash -c 'mysqlshow -hlumen-user-api-db -ulumen -plumen lumen users'
Database: lumen  Table: users
+------------+---------------------+--------------------+------+-----+---------+----------------+---------------------------------+---------+
| Field      | Type                | Collation          | Null | Key | Default | Extra          | Privileges                      | Comment |
+------------+---------------------+--------------------+------+-----+---------+----------------+---------------------------------+---------+
| id         | bigint(20) unsigned |                    | NO   | PRI |         | auto_increment | select,insert,update,references |         |
| name       | varchar(255)        | utf8mb4_unicode_ci | NO   |     |         |                | select,insert,update,references |         |
| email      | varchar(255)        | utf8mb4_unicode_ci | NO   |     |         |                | select,insert,update,references |         |
| created_at | timestamp           |                    | YES  |     |         |                | select,insert,update,references |         |
| updated_at | timestamp           |                    | YES  |     |         |                | select,insert,update,references |         |
+------------+---------------------+--------------------+------+-----+---------+----------------+---------------------------------+---------+
```

### Send data via redis Websocket: (Demo)

![SVG](broadcasting-demo.svg)
