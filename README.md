# API de Gestión de Actividades y Reservas

## Descripción del Proyecto

Este proyecto consiste en una API REST desarrollada con Symfony para la gestión integral de un sistema de reservas de actividades. La aplicación permite administrar clientes, actividades y reservas, proporcionando endpoints para consultar estadísticas, disponibilidad y detalles de las operaciones.

El sistema está diseñado siguiendo patrones de diseño robustos como DTO (Data Transfer Object) y Mappers, separando la lógica de negocio de la capa de presentación y asegurando una estructura escalable y mantenible.

## Tecnologías Utilizadas

El desarrollo se ha llevado a cabo utilizando las siguientes tecnologías y librerías:

*   **Lenguaje**: PHP 8.2+
*   **Framework**: Symfony 7.3
*   **Base de Datos**: MySQL / Doctrine ORM 3.6
*   **Gestión de Datos**: Doctrine Migrations, Doctrine Fixtures
*   **Serialización y Validación**: Symfony Serializer, Symfony Validator

## Requisitos Previos

Antes de instalar el proyecto, asegúrese de tener instalado en su sistema:

*   PHP versión 8.2 o superior
*   Composer
*   Symfony CLI
*   Servidor de base de datos MySQL o MariaDB

## Instalación y Configuración

Siga los siguientes pasos para configurar el proyecto en su entorno local:

1.  **Clonar el repositorio**

    Descargue el código fuente desde el repositorio remoto:

    ```bash
    git clone https://github.com/leyreromeroo/DW_2TrimestreExamenLeyreRomero.git
    cd DW_2TrimestreExamenLeyreRomero
    ```

2.  **Instalar dependencias**

    Ejecute el siguiente comando para instalar las librerías necesarias mediante Composer:

    ```bash
    composer install
    ```

3.  **Configurar variables de entorno**

    Copie el archivo `.env` a `.env.local` y modifique la variable `DATABASE_URL` con sus credenciales de base de datos:

    ```bash
    cp .env .env.local
    # Editar .env.local
    # DATABASE_URL="mysql://usuario:contraseña@127.0.0.1:3306/nombre_base_datos?serverVersion=8.0"
    ```

4.  **Crear la base de datos y esquema**

    Generé la base de datos y ejecute las migraciones para crear la estructura de tablas:

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

5.  **Cargar datos de prueba (Opcional)**

    Si desea poblar la base de datos con información inicial para pruebas:

    ```bash
    php bin/console doctrine:fixtures:load
    ```

6.  **Iniciar el servidor**

    Inicie el servidor local de Symfony:

    ```bash
    symfony server:start
    ```

## Funcionalidades Principales

La API expone diversos endpoints para interactuar con los recursos del sistema:

*   **Clientes**: Gestión de información de usuarios y clientes.
*   **Actividades**: Consulta, creación y administración de actividades disponibles.
*   **Reservas**: Manejo de inscripciones de clientes a actividades, control de aforo y validaciones.
*   **Estadísticas**: Generación de reportes sobre ocupación y métricas de uso por año y tipo de actividad.

## Autor

Desarrollado por **Leyre Romero**.
