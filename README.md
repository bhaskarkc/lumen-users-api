# Lumen Users API

A simple API using laravel lumen framework.

## Commandline help

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
