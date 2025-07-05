# 11.2. Proceso de Despliegue

# INSTALACIÓN DE DEPENDENCIAS

# Descargar e instalar XAMPP desde:
# https://www.apachefriends.org/
# (Instalar con las opciones predeterminadas: incluye Apache, MySQL, PHP, phpMyAdmin)
# Verifica que los servicios de Apache y MySQL estén en ejecución desde el panel de XAMPP

# Descargar e instalar Node.js desde:
# https://nodejs.org/
# (Ejecutar el instalador con la configuración predeterminada)

# Descargar e instalar Composer desde:
# https://getcomposer.org/
# (Durante la instalación, seleccionar PHP ubicado en: C:\xampp\php\php.exe)

# Descargar e instalar Git (opcional, si usarás clonación desde GitHub):
# https://git-scm.com/

# CONFIGURACIÓN INICIAL – OPCIÓN 1: CLONAR DESDE GITHUB (RECOMENDADO)

cd C:\xampp\htdocs
git clone https://github.com/ELVERANTONY/PROYECTO1.git PROYECTO1
cd PROYECTO1

# CONFIGURACIÓN INICIAL – OPCIÓN 2: USAR ARCHIVO ZIP
# (Solo si no usarás Git)

# 1. Descargar el archivo ZIP del proyecto
# 2. Extraer su contenido en C:\xampp\htdocs\aventuratec
# 3. Luego ejecutar:
cd C:\xampp\htdocs\aventuratec

# CONFIGURACIÓN DEL PROYECTO

composer install           # Instalar dependencias de PHP
npm install                # Instalar dependencias de Node.js
copy .env.example .env     # Copiar archivo de entorno
php artisan key:generate   # Generar clave de la aplicación

# CONFIGURACIÓN DE LA BASE DE DATOS

# 1. Abrir: http://localhost/phpmyadmin/
# 2. Crear base de datos llamada: bda_proy
# 3. Editar archivo .env con esta configuración:

# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=bda_proy
# DB_USERNAME=root
# DB_PASSWORD=

# INSTALACIÓN FINAL

php artisan migrate --seed   # Ejecutar migraciones y seeders
npm run build                # Compilar los assets
php artisan storage:link     # Crear enlace simbólico para storage

# PUESTA EN MARCHA (ENTORNO DE DESARROLLO)

php artisan serve            # Iniciar el servidor local
# El sistema estará disponible en:
# http://127.0.0.1:8000



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
