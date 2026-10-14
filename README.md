# website_wp

Repositorio del tema y los plugins propios para el sitio WordPress. El contenido, las imágenes subidas, los usuarios y la base de datos no se guardan aquí.

## Entorno local

El WordPress de desarrollo se ejecuta desde `/Users/seba/dev/tools/wordpress/` con Docker Compose. Su configuración y datos están en ese directorio, separados de este repositorio.

```sh
cd /Users/seba/dev/tools/wordpress
docker compose up -d
```

Sitio: <http://127.0.0.1:8088/>  
Administración: <http://127.0.0.1:8088/wp-admin/>

El usuario local es `admin`. La contraseña de prueba se guarda en `/Users/seba/dev/tools/wordpress/.env`; ese archivo no se sube a GitHub. Para detener los contenedores sin borrar datos, ejecuta `docker compose down` desde el directorio del entorno.

## Tema y obras

`themes/gc-ilustraciones/` es un tema hijo de Twenty Twenty-Five. Incluye cabecera, portada (Inicio, Obras y Contacto), pie y ficha individual. El tema base `twentytwentyfive` debe estar instalado también en el proveedor.

`plugins/gc-obras/` añade **Obras** al panel de WordPress. Cada obra usa título, descripción, resumen e imagen destacada. La galería de la portada muestra automáticamente las obras publicadas. De momento no hay precios, variantes, stock ni traducciones estructuradas; se añadirán cuando decidamos el modelo de contenido definitivo.

Las carpetas del tema y el plugin están montadas directamente en los servicios `wordpress` y `cli` de `/Users/seba/dev/tools/wordpress/compose.yaml`. Los cambios en sus archivos se reflejan al recargar la web. Tras crear o cambiar montajes, ejecutar `docker compose up -d --force-recreate` desde esa ruta.

En un proveedor, configura los enlaces permanentes en **Ajustes → Enlaces permanentes → Nombre de la entrada** para que las fichas usen rutas como `/obra/jardin-interior/`.

Para cargar cuatro obras ficticias en la instalación local:

```sh
cd /Users/seba/dev/tools/wordpress
docker compose --profile tools run --rm --entrypoint wp cli eval-file /workspace-scripts/seed-demo.php
```

El script solo se ejecuta si la URL del sitio apunta a localhost. Nunca sube esas obras a Git ni modifica obras existentes.

Para añadir contenido real, entra en **Obras → Añadir obra**. Rellena título, descripción, resumen e imagen destacada y publícala. La portada y su ficha se actualizan automáticamente. Los datos y las imágenes cargadas siguen fuera de este repositorio.

## Editar la portada y las obras

Para cambiar los textos de la portada, entra en **Apariencia → Editor → Plantillas → Portada**. Edita los bloques de **Inicio** y **Contacto** y guarda la plantilla. Los textos introductorios de **Obras** también están en esa plantilla. La galería, en cambio, se construye automáticamente con las obras publicadas: cada tarjeta se edita desde **Obras**, no desde la plantilla.

El Editor del sitio guarda los cambios de la plantilla en la **base de datos**. Esos cambios prevalecen sobre `themes/gc-ilustraciones/templates/front-page.html` y no modifican el repositorio. Para llevarlos a otra instalación, expórtalos o incorpora los cambios al archivo del tema. El banner y el logo siguen siendo recursos del tema.

Para editar una ficha, entra en **Obras → Todas las obras**, abre la obra y cambia sus bloques de texto. En la barra lateral de ajustes de **Obra**, pulsa **Imagen destacada / Set featured image**, selecciona o sube una imagen y guarda. Esa imagen aparecerá en la galería y en la ficha; no tienes que insertarla en el cuerpo del editor. Si una obra de muestra todavía no tiene imagen destacada, la web muestra temporalmente una ilustración del tema. Esa muestra no es un archivo adjunto de la biblioteca de medios y por eso no aparece entre los bloques de la obra.

Para crear paquetes instalables desde el panel de un proveedor WordPress:

```sh
./scripts/build-zips.sh
```

El script genera un ZIP por cada subdirectorio de `themes/` y `plugins/` en `dist/`. En un WordPress nuevo instala Twenty Twenty-Five, luego el plugin GC Obras y el tema hijo GC Ilustraciones. Después carga las obras y las imágenes del cliente. Los ZIP y los datos locales no forman parte de Git.

Las plantillas editadas en **Apariencia → Editor** se guardan en la base de datos y pueden prevalecer sobre los archivos del tema. Si el cambio debe acompañar a futuros despliegues, incorpóralo al tema del repositorio.

## GitHub

Repositorio público: <https://github.com/GC-Ilustraciones/website_wp>. Antes de cada envío, revisa `git status` para confirmar que solo hay código y documentación. El remoto `origin` usa SSH; no depende de la sesión de GitHub CLI.
