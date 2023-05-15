## Requerimientos

-   PHP >= 7.3
-   Extensión PHP `pdo_sqlite`
-   Una cuenta de MAILTRAP

## Instalación

-   Clonar el proyecto
-   Instalar dependencias: `composer install`
-   Copiar y reemplazar los valores de las variables de entorno: `cp .env.example .env`
    -   Crear una cuenta en MAILTRAP para el envío de correos
-   Generar una nueva APP_KEY: `php artisan key:generate`
-   Ejecutar migraciones con seeders `php artisan migrate:fresh --seed`

## Comandos

| Comando             | Acción                    |
| :------------------ | :------------------------ |
| `php artisan serve` | Inicializa el servidor    |
| `php artisan test`  | Ejecuta todas las pruebas |

## Consideraciones

-   La contraseña por defecto para las cuentas aleatorias es: `password`
