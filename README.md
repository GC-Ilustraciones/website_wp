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

Para crear paquetes instalables desde el panel de un proveedor WordPress:

```sh
./scripts/build-zips.sh
```

El script genera un ZIP por cada subdirectorio de `themes/` y `plugins/` en `dist/`. En un WordPress nuevo instala Twenty Twenty-Five, luego el plugin GC Obras y el tema hijo GC Ilustraciones. Después carga las obras y las imágenes del cliente. Los ZIP y los datos locales no forman parte de Git.

Las plantillas editadas en **Apariencia → Editor** se guardan en la base de datos y pueden prevalecer sobre los archivos del tema. Si el cambio debe acompañar a futuros despliegues, incorpóralo al tema del repositorio.

## GitHub

Repositorio público: <https://github.com/GC-Ilustraciones/website_wp>. Antes de cada envío, revisa `git status` para confirmar que solo hay código y documentación. El remoto `origin` usa SSH; no depende de la sesión de GitHub CLI.
