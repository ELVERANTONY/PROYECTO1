11.2. Proceso de Despliegue

Instalación de Dependencias

Instalar XAMPP (Recomendado para entorno de desarrollo)

Descargar XAMPP desde https://www.apachefriends.org/

Instalar con las opciones predeterminadas (incluye Apache, MySQL, PHP, phpMyAdmin)

Asegurarse de que los servicios de Apache y MySQL estén en ejecución

Instalar Node.js

Descargar el instalador de Node.js desde https://nodejs.org/

Ejecutar el instalador con configuración predeterminada

Instalar Composer

Descargar el instalador de Composer desde https://getcomposer.org/

Durante la instalación, seleccionar el PHP que viene con XAMPP (normalmente en C:\xampp\php\php.exe)

Instalar Git (opcional, solo para clonar repositorio)

Descargar Git desde https://git-scm.com/

Instalar con configuración predeterminada

Configuración Inicial

Opción 1: Desde Repositorio Git (Recomendado)

Pasos Completos para Clonar el Repositorio

Abrir el Símbolo del Sistema (CMD) o PowerShell

Presiona Windows + R, escribe cmd y presiona Enter

O busca "PowerShell" en el menú de inicio

Navegar al directorio de XAMPP

cmd
cd C:\xampp\htdocs

Clonar el repositorio

cmd
git clone https://github.com/ELVERANTONY/PROYECTO1.git aventuratec

Navegar al directorio del proyecto

cmd
cd PROYECTO1

Opción 2: Desde Archivo ZIP

Descargar el archivo ZIP del proyecto

Extraer el contenido en C:\xampp\htdocs\aventuratec

Abrir CMD o PowerShell y navegar al directorio:
cd C:\xampp\htdocs\PROYECTO1

Configuración del Proyecto

Instalar dependencias de PHP

composer install

Instalar dependencias de Node.js

npm install

Copiar el archivo .env de ejemplo

copy .env.example .env

Generar clave de aplicación

php artisan key:generate

Configuración de la Base de Datos

Abrir phpMyAdmin en http://localhost/phpmyadmin/

Crear una nueva base de datos llamada bda_proy

Editar el archivo .env con los datos de conexión:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bda_proy
DB_USERNAME=root
DB_PASSWORD=

Instalación Final
cmd
Ejecutar migraciones y seeders

php artisan migrate --seed


# Compilar assets
npm run build

# Configurar permisos de almacenamiento
php artisan storage:link

Puesta en Marcha
Para Entorno de Desarrollo

# Iniciar servidor de desarrollo
php artisan serve

El sitio estará disponible en http://127.0.0.1:8000



## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
