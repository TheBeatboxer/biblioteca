Aquí tienes los pasos guiados para realizar la prueba de concepto solicitada:

### 1. Prueba de Concepto: API REST para Gestión de Libros (CRUD) en PHP

#### **Paso 1: Configuración del Entorno**
   1. **Instalar XAMPP o LAMP:** Si no tienes un servidor local instalado, instala XAMPP (Windows) o LAMP (Linux) para configurar un entorno de desarrollo PHP.
   2. **Configurar el Proyecto:** Crea un nuevo proyecto en la carpeta `htdocs` de XAMPP o la raíz de tu servidor en LAMP. Nombralo algo como `gestion_libros`.

#### **Paso 2: Crear la Base de Datos**
   1. **Acceder a phpMyAdmin:** Desde tu navegador, accede a `http://localhost/phpmyadmin`.
   2. **Crear una Base de Datos:** Crea una base de datos llamada `biblioteca`.
   3. **Crear la Tabla `libros`:**
   ```sql
   CREATE TABLE libros (
       id INT AUTO_INCREMENT PRIMARY KEY,
       titulo VARCHAR(255) NOT NULL,
       autor VARCHAR(255) NOT NULL,
       anio_publicacion INT NOT NULL,
       genero VARCHAR(100)
   );
   ```

#### **Paso 3: Crear el API REST**
   1. **Estructura del Proyecto:** Dentro del proyecto, organiza la estructura de carpetas como sigue:
   ```
   gestion_libros/
   ├── api/
   │   ├── config/
   │   ├── v1/
   │   └── index.php
   ├── .htaccess
   └── composer.json
   ```
   2. **Configurar `.htaccess` para Redirigir Solicitudes a `index.php`:**
   ```plaintext
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
   ```
   3. **Implementar Conexión a la Base de Datos:** Crea un archivo `config/database.php`:
   ```php
   <?php
   class Database {
       private $host = "localhost";
       private $db_name = "biblioteca";
       private $username = "root";
       private $password = "";
       public $conn;

       public function getConnection() {
           $this->conn = null;
           try {
               $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
               $this->conn->exec("set names utf8");
           } catch(PDOException $exception) {
               echo "Connection error: " . $exception->getMessage();
           }
           return $this->conn;
       }
   }
   ```
   4. **Crear las Rutas y Controladores para el CRUD de Libros:**
   - **`index.php` en `v1`**:
   ```php
   <?php
   require_once '../config/database.php';

   $database = new Database();
   $db = $database->getConnection();

   $url = isset($_GET['url']) ? $_GET['url'] : null;
   $method = $_SERVER['REQUEST_METHOD'];

   if ($url == 'libros' && $method == 'GET') {
       // Obtener todos los libros
       $stmt = $db->query("SELECT * FROM libros");
       $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
       echo json_encode($libros);
   }
   // Más rutas para POST, PUT, DELETE
   ```
   Puedes añadir más rutas para agregar (POST), actualizar (PUT), y eliminar (DELETE) libros.

#### **Paso 4: Probar la API**
   1. **Usar Postman o Insomnia:** Para probar las diferentes rutas de la API (GET, POST, PUT, DELETE).
   2. **Validar que los datos se gestionen correctamente** en la base de datos.

### 2. Implementar Funcionalidad con GIT

#### **Paso 1: Inicializar un Repositorio GIT**
   1. **Navega al Directorio del Proyecto:** Abre la terminal en el directorio `gestion_libros`.
   2. **Inicializar GIT:**
   ```bash
   git init
   ```

#### **Paso 2: Crear la Rama `feature/consulta_libros`**
   1. **Crear y Cambiar a la Rama:**
   ```bash
   git checkout -b feature/consulta_libros
   ```

#### **Paso 3: Implementar la Funcionalidad de Consulta**
   1. **Desarrolla la Funcionalidad en `index.php`:**
   - Añadir la funcionalidad de consulta de libros, si no se ha hecho ya.

#### **Paso 4: Cometer los Cambios**
   1. **Agregar y Cometer:**
   ```bash
   git add .
   git commit -m "Implementar funcionalidad de consulta de libros"
   ```

#### **Paso 5: Hacer Push al Repositorio Remoto**
   1. **Subir la Rama a un Repositorio en GitHub o GitLab:**
   ```bash
   git remote add origin <URL_DEL_REPOSITORIO>
   git push -u origin feature/consulta_libros
   ```

### 3. Conclusión

Este conjunto de pasos te guiará para desarrollar y probar una API REST básica para la gestión de libros en PHP, mientras gestionas el código fuente utilizando GIT. Si sigues cada paso, deberías poder completar la prueba de concepto de manera exitosa.

----
Para probar la API que acabas de implementar, puedes utilizar herramientas como **Postman**, **Insomnia** o realizar solicitudes directas utilizando **cURL** en la línea de comandos. A continuación, te explico cómo hacerlo usando **Postman** y **cURL**.

### **1. Probar la API usando Postman**

**1.1.** **Instala y abre Postman:**

   - Si aún no lo tienes, puedes descargar Postman desde [aquí](https://www.postman.com/downloads/).

**1.2.** **Configura las Solicitudes:**

   - **GET - Obtener todos los libros:**
     1. Crea una nueva solicitud en Postman.
     2. Selecciona el método `GET`.
     3. Ingresa la URL: `http://localhost/gestion_libros/api/v1/libros`.
     4. Haz clic en **Send** para ver la lista de todos los libros.

   - **GET - Obtener un libro por ID:**
     1. Crea una nueva solicitud.
     2. Selecciona el método `GET`.
     3. Ingresa la URL: `http://localhost/gestion_libros/api/v1/libros/{id}`, reemplazando `{id}` con el ID del libro que deseas consultar.
     4. Haz clic en **Send**.

   - **POST - Crear un nuevo libro:**
     1. Crea una nueva solicitud.
     2. Selecciona el método `POST`.
     3. Ingresa la URL: `http://localhost/gestion_libros/api/v1/libros`.
     4. Ve a la pestaña **Body**, selecciona **raw** y elige **JSON** en el menú desplegable.
     5. Ingresa un JSON con los datos del nuevo libro, por ejemplo:
     ```json
     {
       "titulo": "El Señor de los Anillos",
       "autor": "J.R.R. Tolkien",
       "anio_publicacion": 1954,
       "genero": "Fantasía"
     }
     ```
     6. Haz clic en **Send**.

   - **PUT - Actualizar un libro por ID:**
     1. Crea una nueva solicitud.
     2. Selecciona el método `PUT`.
     3. Ingresa la URL: `http://localhost/gestion_libros/api/v1/libros/{id}`, reemplazando `{id}` con el ID del libro que deseas actualizar.
     4. Ve a la pestaña **Body**, selecciona **raw** y elige **JSON**.
     5. Ingresa el JSON con los nuevos datos, por ejemplo:
     ```json
     {
       "titulo": "El Hobbit",
       "autor": "J.R.R. Tolkien",
       "anio_publicacion": 1937,
       "genero": "Fantasía"
     }
     ```
     6. Haz clic en **Send**.

   - **DELETE - Eliminar un libro por ID:**
     1. Crea una nueva solicitud.
     2. Selecciona el método `DELETE`.
     3. Ingresa la URL: `http://localhost/gestion_libros/api/v1/libros/{id}`, reemplazando `{id}` con el ID del libro que deseas eliminar.
     4. Haz clic en **Send**.

### **2. Probar la API usando cURL**

Si prefieres usar la línea de comandos, aquí tienes los comandos cURL correspondientes:

- **GET - Obtener todos los libros:**
  ```bash
  curl -X GET http://localhost/gestion_libros/api/v1/libros
  ```

- **GET - Obtener un libro por ID:**
  ```bash
  curl -X GET http://localhost/gestion_libros/api/v1/libros/1
  ```

- **POST - Crear un nuevo libro:**
  ```bash
  curl -X POST http://localhost/gestion_libros/api/v1/libros \
  -H "Content-Type: application/json" \
  -d '{"titulo": "El Señor de los Anillos", "autor": "J.R.R. Tolkien", "anio_publicacion": 1954, "genero": "Fantasía"}'
  ```

- **PUT - Actualizar un libro por ID:**
  ```bash
  curl -X PUT http://localhost/gestion_libros/api/v1/libros/1 \
  -H "Content-Type: application/json" \
  -d '{"titulo": "El Hobbit", "autor": "J.R.R. Tolkien", "anio_publicacion": 1937, "genero": "Fantasía"}'
  ```

- **DELETE - Eliminar un libro por ID:**
  ```bash
  curl -X DELETE http://localhost/gestion_libros/api/v1/libros/1
  ```

### **Verificación de Resultados**

- **Respuesta Exitosa:** Deberías recibir respuestas en formato JSON que confirmen la creación, actualización o eliminación de libros.
- **Verificar la Base de Datos:** Después de realizar las solicitudes, verifica en `phpMyAdmin` o en tu base de datos que los registros se hayan actualizado como esperabas.

Estas herramientas te permitirán interactuar con tu API para asegurarte de que todas las operaciones CRUD funcionen correctamente.

Listar todos los libros: Accede a http://localhost/gestion_libros/api/v1/index.php?url=libros
Obtener un libro por ID: Accede a http://localhost/gestion_libros/api/v1/index.php?url=libros/1
Crear un libro: Envía una solicitud POST a http://localhost/gestion_libros/api/v1/index.php?url=libros con el cuerpo JSON adecuado.
Actualizar un libro: Envía una solicitud PUT a http://localhost/gestion_libros/api/v1/index.php?url=libros/1 con el cuerpo JSON adecuado.
Eliminar un libro: Envía una solicitud DELETE a http://localhost/gestion_libros/api/v1/index.php?url=libros/1.