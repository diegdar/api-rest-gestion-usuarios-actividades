# API para la Gestión de Usuarios y Actividades 🎉

## Enunciado 📜
Este proyecto consiste en una API REST diseñada para gestionar usuarios y actividades, permitiendo a las aplicaciones cliente consumir sus servicios e interactuar eficientemente con el sistema. El servicio ofrece funcionalidades como el registro de usuarios, la gestión de actividades, la inscripción de usuarios en una o varias actividades, y la importación y exportación de estas en formato JSON. Para garantizar la seguridad y un acceso adecuado, la API implementa un mecanismo de autenticación basado en tokens utilizando Laravel Passport.

## Requisitos Técnicos ⚙️
### 1. Gestión de Usuarios 👨‍👩‍👦‍👦
- **Registro de nuevos usuarios**: Permite crear un nuevo usuario.
- **Actualización de datos del usuario**: Permite modificar la información de un usuario existente.
- **Eliminación de usuarios**: Permite eliminar un usuario del sistema.
- **Consulta de información de usuarios**: Permite obtener la información de un usuario específico.

### 2. Gestión de Actividades 📅
- **Creación de una nueva actividad**: Permite añadir una nueva actividad al sistema.
- **Consulta de actividades**: Permite obtener una lista de todas las actividades.
- **Apuntarse a una actividad**: Permite a un usuario apuntarse a una actividad.

### 3. Exportación de Actividades 📤
- **Exportar actividades en formato JSON**: Permite exportar la lista de actividades en un archivo JSON.

### 4. Importación de Actividades 📥
- **Importar actividades desde un archivo JSON**: Permite cargar actividades desde un archivo JSON.

### 5. Configuración de la Base de Datos 🗄️
- Se ha establecido una conexión con una base de datos MySQL para almacenar los datos de usuarios, actividades y el registro de estos a una o varias actividades.

### 6. Autenticación de Usuarios 🔑
- El proyecto utiliza **autenticación de usuarios con tokens** mediante **Passport**, asegurando que las operaciones sobre la API sean seguras y que solo los usuarios autenticados puedan acceder a las funciones protegidas.

### 7. Test 🧪🔬
- Se empleó la metodología **TDD**, creando pruebas automatizadas antes del desarrollo del código funcional. Este enfoque asegura que cada funcionalidad esté respaldada por una prueba que verifica su correcto funcionamiento, promoviendo un diseño más limpio y reduciendo la probabilidad de errores. 

## Endpoints de la API 🔗
### 1. Usuarios
- `POST /appActivities/register`: Registro de un nuevo usuario.
- `PUT /appActivities/users/{user}`: Actualización de los datos de un usuario.
- `GET /appActivities/users/{user}`: Consulta de la información de un usuario.
- `DELETE /appActivities/users/{user}`: Eliminación de un usuario.

### 2. Actividades
- `POST /appActivities/activity`: Creación de una nueva actividad.
- `GET /appActivities/activities/{activity}`: Consulta de una actividad.
- `POST /appActivities/users/{user}/activities/{activity}`: un usuario se apunta a una actividad.

### 3. Importación/Exportación
- `POST /import/activities`: Importar actividades desde un archivo JSON.
- `GET /export/activities`: Exportar actividades en formato JSON.

## Formato del JSON 📄
```json
[
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
]

// TODO: 
// -Creara los test faltantes para delete y update activitie
// -crear un trait para juntar los metodos comunes que se utilizan en los test
// -Factorizar controllers.
// -Crear un services para el user
