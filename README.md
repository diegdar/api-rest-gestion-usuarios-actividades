# API para la Gestión de Usuarios y Actividades 🎉

## Enunciado 📜

Este proyecto consiste en desarrollar una API para una aplicación web que gestiona usuarios y actividades. El servicio permite el registro de usuarios, la gestión de datos de las actividades y la importación/exportación de estas actividades en formato JSON.

## Requisitos Técnicos ⚙️

### 1. Gestión de Usuarios 👤
- **Registro de nuevos usuarios**: Permite crear un nuevo usuario.
- **Actualización de datos del usuario**: Permite modificar la información de un usuario existente.
- **Eliminación de usuarios**: Permite eliminar un usuario del sistema.
- **Consulta de información de usuarios**: Permite obtener la información de un usuario específico.

### 2. Gestión de Actividades 📅
- **Creación de una nueva actividad**: Permite añadir una nueva actividad al sistema.
- **Consulta de actividades**: Permite obtener una lista de todas las actividades.
- **Apuntarse a una actividad**: Permite a un usuario registrarse en una actividad.

### 3. Exportación de Actividades 📤
- **Exportar actividades en formato JSON**: Permite exportar la lista de actividades en un archivo JSON.

### 4. Importación de Actividades 📥
- **Importar actividades desde un archivo JSON**: Permite cargar actividades desde un archivo JSON.

### 5. Configuración de la Base de Datos 🗄️
- Se ha establecido una conexión con una base de datos MySQL para almacenar los datos de usuarios y actividades.

### 6. Autenticación de Usuarios 🔑
- El proyecto utiliza **autenticación de usuarios con tokens** mediante **Passport**, asegurando que las operaciones sobre la API sean seguras y que solo los usuarios autenticados puedan acceder a las funciones protegidas.

## Endpoints de la API 🔗

### 1. Usuarios
- `POST /appActivities/register`: Registro de un nuevo usuario.
- `PUT /appActivities/users/{user}`: Actualización de los datos de un usuario.
- `GET /appActivities/users/{user}`: Consulta de la información de un usuario.
- `DELETE /appActivities/users/{user}`: Eliminación de un usuario.

### 2. Actividades
- `POST /appActivities/activity`: Creación de una nueva actividad.
- `GET /appActivities/activities/{activity}`: Consulta de una actividad.
- `POST /appActivities/users/{user}/activities/{activity}`: Apuntarse a una actividad.

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
