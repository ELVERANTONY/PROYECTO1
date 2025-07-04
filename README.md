ClassCraft - Plataforma Educativa
Este proyecto es una página web desarrollada con Laravel y está diseñada para facilitar la interacción entre profesores y alumnos en un entorno educativo gamificado, al estilo de Classcraft.

Tecnologías utilizadas:

Laravel (Framework PHP)

PHP 8.1.2

MySQL (utilizando XAMPP)

Blade (motor de plantillas)

Bootstrap (para estilos)

XAMPP como entorno de servidor local

Instalación y configuración:

Clonar el repositorio:

bash
Copiar
Editar
git clone https://github.com/ELVERANTONY/PROYECTO1.git
cd PROYECTO1
Instalar dependencias con Composer:

nginx
Copiar
Editar
composer install
Copiar el archivo .env y configurar la base de datos:

bash
Copiar
Editar
cp .env.example .env
Luego editar el archivo .env con los datos de conexión:

makefile
Copiar
Editar
DB_DATABASE=nombre_de_tu_bd
DB_USERNAME=root
DB_PASSWORD=
Generar la clave de la aplicación:

vbnet
Copiar
Editar
php artisan key:generate
Ejecutar las migraciones para crear las tablas:

nginx
Copiar
Editar
php artisan migrate
Iniciar el servidor local:

nginx
Copiar
Editar
php artisan serve
Accede desde el navegador a http://127.0.0.1:8000

Roles del sistema:

Profesor: puede gestionar tareas, crear misiones, asignar puntos, etc.

Alumno: visualiza su progreso, cumple tareas y misiones.

Estructura del proyecto:

app/Models: Modelos Eloquent

resources/views: Vistas Blade (interfaz)

routes/web.php: Rutas principales

database/migrations: Migraciones de base de datos

Funcionalidades principales:

Registro e inicio de sesión de usuarios (profesores y alumnos)

Gestión de puntos de experiencia, misiones y tareas

Interfaz amigable para entornos educativos

Requisitos:

PHP 8.1.2 o superior

Composer

XAMPP o similar (con MySQL y PHP)

Notas:

Si hay errores de caché, usar php artisan config:clear o php artisan cache:clear

Asegúrate de que el puerto 8000 esté libre

Autor:
Elver Antony
Repositorio: https://github.com/ELVERANTONY/PROYECTO1


