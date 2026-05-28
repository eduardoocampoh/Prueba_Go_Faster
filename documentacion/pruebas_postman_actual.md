# Guía de Pruebas con Postman - Go Faster (Versión Actual)

Este documento explica cómo utilizar **Postman** para probar el funcionamiento del backend de tu proyecto **Go Faster** tal y como está construido actualmente (aplicación web tradicional con PHP, envío de formularios y manejo de sesiones con cookies).

---

## 🛑 Diferencias con una API RESTful
Antes de empezar, es importante entender que tu proyecto actual **no es una API**. Por lo tanto:
1. **No enviamos JSON:** Los datos se envían simulando un formulario HTML (`x-www-form-urlencoded`).
2. **No usamos Tokens (Bearer):** La autenticación se maneja a través de las Cookies de sesión (`PHPSESSID`) nativas de PHP. Postman guarda estas cookies automáticamente tras un inicio de sesión exitoso.
3. **Las respuestas son en HTML:** No recibirás objetos JSON ordenados. Recibirás el código fuente de la página a la que se te redirija o alertas en JavaScript (ej. `<script>alert('Error')</script>`).

---

## 🛠️ Pruebas Paso a Paso

### 1. Probar el Inicio de Sesión (Login)

El inicio de sesión es procesado por el archivo `index.php`.

**Paso a paso:**
1. Abre Postman y crea un **New Request** (+).
2. Cambia el método HTTP a **`POST`**.
3. En la URL ingresa: `http://localhost/prueba_go_faster/index.php`
4. Ve a la pestaña **Body** (debajo de la URL).
5. Selecciona la opción **`x-www-form-urlencoded`**.
6. Agrega las siguientes filas en la tabla (*Key / Value*):
   - `login` : `1` *(Necesario porque tu PHP verifica `if (isset($_POST['login']))`)*.
   - `usuario_user` : *(Escribe un nombre de usuario que exista en tu BD)*.
   - `Password_usua` : *(Escribe la contraseña de ese usuario)*.
7. Haz clic en **Send**.

**Cómo interpretar el resultado:**
- **Si el login es exitoso:** Postman seguirá automáticamente la redirección (`header("Location: inicio.php")`) y en la pestaña de respuesta (Response -> Pretty/Preview) verás todo el código HTML de tu página `inicio.php`.
- **Si falla (usuario o clave incorrecta):** Verás una respuesta muy corta en HTML que contiene el script de alerta: `<script>alert('Contraseña incorrecta'); window.history.back();</script>`.

---

### 2. Probar una Ruta Protegida (Mi Cuenta)

Una vez que hiciste el login exitosamente en el paso 1, Postman habrá guardado la cookie de sesión (`PHPSESSID`). Ahora puedes probar páginas que requieren que el usuario esté autenticado.

**Paso a paso:**
1. Abre una **nueva pestaña** de petición en Postman.
2. Deja el método en **`GET`**.
3. En la URL ingresa: `http://localhost/prueba_go_faster/cuenta_usuario.php`
4. Haz clic en **Send**.

**Cómo interpretar el resultado:**
- **Si tu sesión está activa:** Verás el código HTML de la página de perfil con los datos del usuario cargados desde la base de datos.
- **Si falló o perdiste la sesión:** En lugar de ver el HTML de `cuenta_usuario.php`, verás el HTML de `autenticar.php`, ya que tu código tiene una validación que redirige a los usuarios no logueados (`header("Location: autenticar.php");`).

---

### 3. Probar el Registro de Usuario

El registro de un nuevo usuario es procesado por el archivo `registro.php`.

**Paso a paso:**
1. Crea una nueva petición con el método **`POST`**.
2. En la URL ingresa: `http://localhost/prueba_go_faster/registro.php`
3. Ve a la pestaña **Body** y selecciona **`x-www-form-urlencoded`**.
4. Agrega todos los campos requeridos por tu formulario (*Key / Value*):
   - `registrar` : `1` *(Para activar la condición `if (isset($_POST['registrar']))`)*.
   - `nombres` : `Juan Carlos`
   - `apellidos` : `Perez`
   - `correo` : `juan@correo.com`
   - `direccion` : `Calle Falsa 123`
   - `telefono` : `3001234567`
   - `nombre_usuario` : `juancp`
   - `password` : `secreto123`
   - `confirmacion_password` : `secreto123`
5. Haz clic en **Send**.

**Cómo interpretar el resultado:**
- **Si el registro es exitoso:** Verás un script de JavaScript que indica el éxito y redirige: `<script>alert('Usuario registrado exitosamente'); window.location='autenticar.php';</script>`.
- **Si las contraseñas no coinciden:** Verás: `<script>alert('Las contraseñas no coinciden'); window.history.back();</script>`.

---

### 4. Probar la Búsqueda de Productos

La búsqueda funciona mediante el método GET en el archivo `buscar.php`.

**Paso a paso:**
1. Crea una nueva petición con el método **`GET`**.
2. En la URL ingresa: `http://localhost/prueba_go_faster/buscar.php`
3. Ve a la pestaña **Params** (al lado de Authorization y Headers).
4. Agrega un parámetro (*Key / Value*):
   - `q` : `hamburguesa` *(o el término que desees buscar)*.
   - *Nota: Verás que la URL se actualiza automáticamente a `http://localhost/prueba_go_faster/buscar.php?q=hamburguesa`*.
5. Haz clic en **Send**.

**Cómo interpretar el resultado:**
- Verás el código HTML de la página de resultados. Si la búsqueda tuvo éxito, dentro de ese HTML estarán generados los bloques `div` correspondientes a los productos encontrados.

---

### 💡 Tip: Cómo ver y borrar las Cookies en Postman
Si deseas probar el sistema como si fueras un usuario que acaba de entrar (sin sesión iniciada), debes borrar la cookie que Postman guardó.
1. Debajo del botón azul "Send", haz clic en la palabra **Cookies** (suele estar en letras pequeñas de color gris o azul).
2. Se abrirá un panel con los dominios. Busca `localhost`.
3. Verás una cookie llamada `PHPSESSID`.
4. Haz clic en la **"X"** a la derecha para eliminarla.
5. Al hacer esto, simulas haber cerrado el navegador o haber hecho clic en "Cerrar sesión".