<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto: API REST</title>
    {{-- SEO --}}
    <meta name="description" content="Portafolio de Diego Chacón Delgado, desarrollador web">
    <meta name="keywords"
        content="portafolio, desarrollador web, backend, frondtend, css, html, php, c#, mysql, sqlserver, mongodb, laravel, postman, docker, phpmyadmin, Diego Chacón Delgado">
    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicon/site.webmanifest') }}">
    {{-- Font Awesome: icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- Google fonts --}}
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <!-- Preload Google Fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,500&display=swap"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    {{-- it gives an alternative charge in case JS is unavailable --}}
    <noscript>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,500&display=swap">
    </noscript>
    {{-- Internal styles --}}
    @vite('resources/css/app.css')
    <title>{{ $title ?? 'Home' }}</title>
</head>

<body>
    {{-- Navigation Menu --}}
    <x-navbar></x-navbar>
    <main>
        <div class="container my-5">
            <h1 class="text-center">API para la Gestión de Usuarios y Actividades 🎉</h1>
            <!-- project description -->
            <p class="lead">Este proyecto consiste en una API REST diseñada para gestionar usuarios y actividades,
                permitiendo a las aplicaciones cliente consumir sus servicios e interactuar eficientemente con el
                sistema.Para garantizar la seguridad y un acceso adecuado, la API implementa un mecanismo de
                autenticación basado en tokens utilizando Laravel Passport.
            </p>
            {{-- link to repo --}}
            <div class="text-center m-4">
                <a href="https://github.com/diegdar/api-rest-gestion-usuarios-actividades"
                    class="btn btn-dark btn-github btn-lg" target="_blank">
                    <i class="fab fa-github fa-xl"></i> Ver repositorio
                </a>
            </div>
            {{-- Requisitos Tecnicos  --}}
            <div>
                <h2>Requisitos Técnicos</h2>
                <h3>Requisitos Técnicos</h3>
                <ol>
                {{-- Gestión de Usuarios --}}
                    <h5><li>Gestión de Usuarios👨‍👩‍👦‍👦</h5></li>
                    <ul>
                        <li><strong>Registro de nuevos usuarios:</strong> Permite a un nuevo usuario crear una cuenta en el sistema
                            proporcionando información esencial, facilitando su acceso a las funcionalidades
                            personalizadas de la plataforma.
                        </li>
                        <li><strong>Actualización de datos del usuario:</strong> Los usuarios pueden modificar su información personal,
                            asegurando que sus detalles estén siempre actualizados y reflejen cambios como dirección de
                            correo o número de teléfono.
                        </li>
                        <li><strong>Eliminación de usuarios:</strong> Posibilidad de un usuario de eliminar su cuenta.
                        </li>
                        <li><strong>Consulta de información de usuarios:</strong> Acceso a detalles específicos de usuarios registrados,
                            útil para administración y soporte dentro del sistema.
                        </li>
                    </ul>
                {{-- Gestión de Actividades --}}
                    <h5><li>Gestión de Actividades📅</h5></li>
                    <ul>
                        <li><strong>Creación de nuevas actividades:</strong> Los administradores pueden definir y programar eventos o
                            tareas, especificando detalles como fecha, hora, lugar y capacidad máxima de
                            participantes.
                        </li>
                        <li><strong>Consulta de actividades:</strong> Los usuarios pueden explorar las actividades disponibles,
                            obteniendo información detallada para decidir en cuáles desean participar.
                        </li>
                        <li><strong>Apuntarse a una actividad:</strong> Funcionalidad que permite a los usuarios inscribirse en
                            actividades de su interés, gestionando cupos y confirmaciones de asistencia de manera
                            eficiente.
                        </li>
                    </ul>
                {{-- Importación/Exportación --}}
                    <h5><li>Importación/Exportación📥📤</h5></li>
                    <ul>
                        <li><strong>Importar actividades desde un archivo JSON:</strong> Facilita la carga masiva de actividades
                            al
                            sistema mediante archivos JSON estructurados, agilizando la actualización y gestión
                            de
                            eventos.
                        </li>
                        <li><strong>Exportar actividades en formato JSON:</strong> Permite extraer y respaldar la información de
                            las
                            actividades en archivos JSON, facilitando la integración con otras aplicaciones o
                            análisis
                            externos.
                        </li>
                    </ul>
                {{-- Roles --}}
                    <h5><li>Roles de los usuarios 📜</h5></li>
                    <p>Se ha utilizado la librería Spatie de Laravel para la gestión de roles y permisos, proporcionando una forma flexible y eficiente de controlar el acceso a las funcionalidades de la API.
                    </p>
                    <ol>
                      <li>
                        <h6>Role User 🧑‍💻:</h6>
                        <ul>
                          <li><strong>Puede:</strong> crear, ver, editar y eliminar su propia cuenta; listar e inscribirse en actividades.</li>
                          <li><strong>No puede:</strong> ver, crear, editar, borrar la cuenta de otro usuario; crear o importar actividades.</li>
                        </ul>
                      </li>
                       <li><h6>Role Admin 🧑‍🎓:</h6></li>
                        <ul>
                            <li><strong>Puede:</strong> ver, editar y eliminar cualquier cuenta; crear, ver, editar, borrar actividades; importar y exportar actividades.</li>
                        </ul>
                    </ol>                    
                {{-- Base de Datos --}}
                    <h5><li>Base de Datos🗄️</h5></li>
                    <ul>
                        <li style="list-style-type: none;">La API se integra con una base de datos MySQL para gestionar y
                            almacenar de manera eficiente la información de usuarios y actividades. Esta
                            elección garantiza
                            una
                            gestión robusta de los datos, permitiendo operaciones CRUD seguras y consultas
                            optimizadas que
                            aseguran la integridad y consistencia de la información.
                        </li>
                    </ul>
                {{-- Autenticación --}}
                    <h5><li>Autenticación🔐</h5></li>
                    <ul>
                        <li style="list-style-type: none;">Se implementa autenticación basada en tokens utilizando Laravel
                            Passport, proporcionando una capa de seguridad que regula el acceso a los
                            recursos de la API.
                            Este
                            enfoque garantiza que solo usuarios autenticados puedan interactuar con el
                            sistema, protegiendo
                            los
                            datos sensibles y manteniendo la integridad de las operaciones.
                        </li>
                    </ul>
                {{-- Test --}}
                    <h5><li>Test 🧪🔬</h5></li>
                    <ul>
                        <li style="list-style-type: none;">Se empleó la metodología TDD, creando pruebas automatizadas antes del
                            desarrollo del código funcional. Este enfoque asegura que cada
                            funcionalidad esté respaldada por
                            una
                            prueba que verifica su correcto funcionamiento, promoviendo un diseño
                            más limpio y reduciendo la
                            probabilidad de errores.
                        </li>
                    </ul>
                </ol>
            </div>
            <!-- API endpoints -->
            <div>
                <h2>Endpoints</h2>
                <h3>Endpoints</h3>
                <p>A continuación, se detallan los principales endpoints disponibles:</p>
                <ol>
                    <h5><li>Usuario</h5></li>
                    <ul>
                        <li><code>POST /appActivities/register</code>: Registro de un nuevo usuario.</li>
                        <li><code>PUT /appActivities/users/{user}</code>: Actualización de los datos de un usuario.</li>
                        <li><code>GET /appActivities/users/{user}</code>: Consulta de la información de un usuario.</li>
                        <li><code>DELETE /appActivities/users/{user}</code>: Eliminación de un usuario.</li>
                    </ul>
                    <h5><li>Actividade</h5></li>
                    <ul>
                        <li><code>POST /appActivities/activity</code>: Creación de una nueva actividad.</li>
                        <li><code>GET /appActivities/activities/{activity}</code>: Consulta de una actividad.</li>
                        <li><code>POST /appActivities/users/{user}/activities/{activity}</code>: Un usuario se
                            apunta a una
                            actividad.</li>
                    </ul>
                    <h5><li>Importación/Exportació</h5></li>
                    <ul>
                        <li><code>POST /import/activities</code>: Importar actividades desde un archivo JSON.
                        </li>
                        <li><code>GET /export/activities</code>: Exportar actividades en formato JSON.</li>
                    </ul>
                </ol>
            </div>
            <!-- JSON format -->
            <div>
                <h2>Formato del JSON</h2>
                <h3>Formato del JSON</h3>
                <p>Ejemplo de cómo se estructuran las actividades en formato JSON:</p>
                <pre><code>[
    {
        "name": "Sesión de Yoga",
        "description": "Clase de yoga para relajarse y estirar los músculos.",
        "max_capacity": 20
    },
    {
        "name": "Taller de cocina",
        "description": "Aprender a cocinar platos mediterráneos.",
        "max_capacity": 15
    },
    {
        "name": "Curso de fotografía",
        "description": "Taller para mejorar tus habilidades de fotografía.",
        "max_capacity": 10
    },
    {
        "name": "Escalada en roca",
        "description": "Actividad de escalada en un muro de escalada interior.",
        "max_capacity": 12
    },
    {
        "name": "Sesión de meditación",
        "description": "Sesión guiada de meditación para la paz interior.",
        "max_capacity": 30
    }
]</code></pre>
            </div>
        </div>
    </main>
    {{-- JS: Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
