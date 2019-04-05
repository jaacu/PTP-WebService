# Una integracion de PTP WebServices

## Descripcion

Una aplicacion web sencilla hecha con laravel para generar pagos consumiendo el webservice de PlaceToPay

## Features

Permite crear pagos atraves del WebService de PlaceToPay

## Especificaciones

Hecho con Laravel 5.8, uso GuzzleHttp

**Nota: Este proyecto usa json columns, probado con MySql 14.14

## Para ejecutarlo

Clonar este repositorio

Ejecutar composer install

Agregar en tu .env las variables:

- PTP_IDENTIFICADOR
- PTP_SECRET_KEY

Con sus respectivos valores.

Empezar a correr php artisan serve

Empezar a correr php artisan queue:work

Empezar a hacer pagos!