# Documentación de APIs - Go Faster

Este documento describe las APIs que podrían estar disponibles para el proyecto Go Faster si implementas una arquitectura RESTful (usando Tokens JWT para autenticación) y detalla las instrucciones para probar estos procesos usando **Postman**.

---

## 🚀 APIs Disponibles (Ejemplos sugeridos)

*(Nota: Actualmente tu proyecto utiliza autenticación por sesiones nativas de PHP y vistas HTML/PHP. Si decides migrar a una arquitectura API RESTful, estos serían los endpoints y su estructura).*

### 1. Autenticación

#### **Iniciar Sesión (Login)**
- **Método:** `POST`
- **Endpoint:** `/api/login.php`
- **Descripción:** Permite a un usuario autenticarse y obtener su Bearer Token.
- **Body (Formato JSON):**
  ```json
  {
    "usuario_user": "nombre_usuario",
    "Password_usua": "tu_contraseña"
  }
  ```
- **Respuesta Exitosa (200 OK):**
  ```json
  {
    "status": "success",
    "message": "Login exitoso",
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
  }
  ```

#### **Registro de Usuario**
- **Método:** `POST`
- **Endpoint:** `/api/registro.php`
- **Descripción:** Crea un nuevo usuario en la base de datos.
- **Body (Formato JSON):**
  ```json
  {
    "nombres": "Juan",
    "apellidos": "Pérez",
    "correo": "juan@example.com",
    "direccion": "Calle 123",
    "telefono": "1234567890",
    "nombre_usuario": "juanp",
    "password": "password123"
  }
  ```

### 2. Usuarios (Rutas Protegidas)

#### **Obtener Perfil de Usuario**
- **Método:** `GET`
- **Endpoint:** `/api/usuario/perfil.php`
- **Headers Requeridos:** 
  - `Authorization: Bearer <tu_token>`
- **Respuesta Exitosa (200 OK):**
  ```json
  {
    "id_Usuario": 1,
    "Nombres_usua": "Juan",
    "Email_usua": "juan@example.com"
  }
  ```

### 3. Productos (Rutas Protegidas)

#### **Buscar Productos**
- **Método:** `GET`
- **Endpoint:** `/api/productos/buscar.php?q=hamburguesa`
- **Headers Requeridos:**
  - `Authorization: Bearer <tu_token>`

---

## 🛠️ Instrucciones para probar en Postman

Postman es una herramienta que te permite hacer peticiones HTTP hacia tu servidor y probar las APIs fácilmente.

### Paso 1: Configurar la petición básica
1. Abre **Postman** y crea una nueva petición haciendo clic en el botón **+** (o *New -> HTTP Request*).
2. Selecciona el **Método HTTP** (ej. `GET`, `POST`) en el menú desplegable que está a la izquierda de la barra de URL.
3. En la barra de URL, ingresa la dirección de tu endpoint. Ejemplo: `http://localhost/prueba_go_faster/api/login.php`.

### Paso 2: Enviar datos en el Body (Para POST/PUT)
Para peticiones como el Login o Registro, debes enviar datos en el cuerpo de la petición:
1. Debajo de la barra de URL, selecciona la pestaña **Body**.
2. Elige la opción **raw** (crudo).
3. A la derecha, verás un menú desplegable que dice `Text`. Cámbialo a **JSON**.
4. Pega la estructura JSON con los datos (ver ejemplos arriba) en el área de texto grande.
5. Haz clic en el botón azul **Send** (Enviar).

### Paso 3: Usar el Bearer Token (Para rutas protegidas)
Una vez que haces el Login y el servidor te responde con un "token", debes usar ese token para acceder a otras partes protegidas del sistema (como ver tu perfil o buscar productos).
1. Copia el token largo que recibiste en la respuesta del Login (ej. `eyJhbGci...`).
2. Crea una **nueva petición** (ej. `GET http://localhost/prueba_go_faster/api/usuario/perfil.php`).
3. Ve a la pestaña **Authorization** (debajo de la barra de URL).
4. En la sección **Type** (Tipo), abre el menú desplegable y selecciona **Bearer Token**.
5. En el campo que dice **Token** a la derecha, pega el token que copiaste.
6. Haz clic en **Send**. Postman automáticamente inyectará el encabezado `Authorization: Bearer <tu_token>` en la petición.

*(Alternativamente, puedes ir a la pestaña **Headers** y agregar una nueva fila manualmente donde la **Key** sea `Authorization` y el **Value** sea `Bearer tu_token_aqui`)*.

### Paso 4: Revisar la respuesta
En la parte inferior de la ventana de Postman verás la sección de respuesta (**Response**). Allí podrás:
- Ver el **Body** (Cuerpo de la respuesta en formato JSON).
- Ver el **Status** (Código de estado HTTP, ej. `200 OK`, `401 Unauthorized`, `404 Not Found`).
- Analizar el tiempo de respuesta y los headers devueltos por el servidor.