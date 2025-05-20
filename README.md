# 🌱 Guía-Verde
Guía verde  Es una web desarrollada para los amantes de las plantas, dirigida a personas curiosas que desean descubrir nuevas especies y a quienes buscan aprender a cuidarlas mejor. Cuenta con un amplio catálogo de especies, un 'Jardín' para guardar tus plantas preferidas y un espacio amigable para compartir conocimientos y resolver dudas.

## 🌿 Características principales

- 🔍 Búsqueda de plantas por nombre o categoría
- 🪴 Página "Mi Jardín" para guardar favoritas
- 💬 Sistema de preguntas y respuestas sobre cada planta
- 🌟 Calificación de respuestas con estrellas
- 🌘 Modo oscuro
- 🔐 Autenticación de usuarios (login y registro)

---

## 🧰 Tecnologías utilizadas

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 8
- **Base de datos**: MySQL
- **AJAX** para interacción en tiempo real
- **API**: Conexión con [Perenual API](https://perenual.com/) para obtener datos de plantas
- **Diseño responsivo** con CSS puro

---
## 🛠️Estructura del Proyecto

```bash
/guia-plantas/
│
├── index.php              # Página principal con búsqueda
├── categoria.php          # Plantas por categoría
├── planta.php             # Ficha individual de la planta
├── jardin.php             # Página "Mi Jardín"
├── preguntas.php          # Listado de preguntas
├── pregunta.php           # Página individual de una pregunta
├── login.php              # Inicio de sesión
├── register.php           # Registro de usuario
├── logout.php             # Cierre de sesión
├── gracias.php            # Página de despedida
│
├── db.php                 # Conexión a la base de datos
├── auth.php               # Funciones de autenticación
│
├── /assets/
│   ├── style.css          # Estilos globales
│   ├── index.css, etc.    # Estilos por página
│   ├── js/
│   │   └── preguntas.js   # Funciones JS para preguntas
│
├── /api/
│   └── plantas.php        # Consulta a la API externa
│
├── /actions/
│   ├── guardar_favorito.php
│   ├── eliminar_favorito.php
│   ├── enviar_pregunta.php
│   ├── calificar.php
│   ├── eliminar_pregunta.php
│   ├── eliminar_respuesta.php
│   ├── guardar_busqueda.php
│
└── estructura.sql         # Script SQL de la base de datos
```
---

## 🧭 Guía de uso


### • 🏠 En la **página principal** puedes buscar plantas por nombre o navegar por categorías.

![visualización-web](https://github.com/AngelyVS/Gu-a-Verde/blob/dfbf7bc3fdaee1fdd00dfaeb4a14a6c5dba289f4/imagenes/img1.png)

 ### • 🪻 Puedes acceder a lacategoría que prefieras
 
 ![visualización-web](https://github.com/AngelyVS/Gu-a-Verde/blob/7d95deeb5eb25a33e4f3484ba049df309ff21372/imagenes/img2.png)
 
### • 🔍 Realizar búsquedas

 ![visualización-web](https://github.com/AngelyVS/Gu-a-Verde/blob/7d95deeb5eb25a33e4f3484ba049df309ff21372/imagenes/img2-5.png)

### • ✍️ Para añadir favoritas o enviar preguntas, debes registrarte o iniciar sesión.

![visualización-web](https://github.com/AngelyVS/Gu-a-Verde/blob/7d95deeb5eb25a33e4f3484ba049df309ff21372/imagenes/img1.1.png)

### • 📋 Puedes acceder a la ficha de la planta y conocer  sus características
![visualización-web](https://github.com/AngelyVS/Gu-a-Verde/blob/7d95deeb5eb25a33e4f3484ba049df309ff21372/imagenes/img3.png)

### • 💖 Guardarla en tu jardín (favoritos)

![visualización-web](https://github.com/AngelyVS/Gu-a-Verde/blob/7d95deeb5eb25a33e4f3484ba049df309ff21372/imagenes/img6.png)

### • ❓La sección "Preguntas" permite responder, calificar respuestas o gestionar las propias.

![visualización-web](https://github.com/AngelyVS/Gu-a-Verde/blob/2b68403c68db1bc3bdb322e25137a990c97d2d18/imagenes/img-5.1.png)
  
### • 👋 Al finalizar sesión puedes,cuentas con un mensaje de despedida y botones para desplazarte

![visualización-web](https://github.com/AngelyVS/Gu-a-Verde/blob/2b68403c68db1bc3bdb322e25137a990c97d2d18/imagenes/img8.png)

### • 🌚 Para finalizar, puedes escoger entre el modo claro u oscuro
 ![visualización-web](https://github.com/AngelyVS/Gu-a-Verde/blob/2b68403c68db1bc3bdb322e25137a990c97d2d18/imagenes/img7.png)


##
## 🛠️ Instalación y configuración

### Sigue estos pasos para instalar y ejecutar el proyecto localmente:

- Requisitos previos
PHP 7.4 o superior
- Servidor web local (como XAMPP, WAMP, Laragon o MAMP)
- MySQL / MariaDB
- Navegador web moderno

#### 1. Clonar el repositorio
 ``` bash
   git clone https://github.com/tu-usuario/guia-plantas.git
  `cd guia-plantas
```


#### 2. Importar la base de datos

- Abre phpMyAdmin o tu gestor de base de datos preferido.
- Crea una base de datos con el nombre que desees (por ejemplo: guia_plantas).
- Importa el archivo estructura.sql que se encuentra en la raíz del proyecto.

#### 3. Configurar la conexión a la base de datos
- Abre el archivo db.php.
- Ajusta las siguientes líneas con tus datos de acceso:
  ``` bash
  $host = 'localhost';
  $db = 'guia_plantas';      // Cambia esto si usaste otro nombre
  $user = 'root';            // Usuario de tu base de datos
  $pass = '';                // Contraseña (en XAMPP suele estar vacía)
  ```
  
#### 4. Ejecutar el proyecto

- Inicia tu servidor web (Apache) y tu servidor MySQL.
- Abre tu navegador y entra en la ruta del proyecto. Ejemplo:
``` bash
 http://localhost/guia-plantas/
```


