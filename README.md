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

## Tema y plugins

`themes/` y `plugins/` alojarán el código propio cuando se decida el tema base y las funciones del proyecto. Mientras tanto, la instalación local usa un tema incluido en WordPress. Al añadir un componente, monta su carpeta concreta en el servicio `wordpress` de `compose.yaml` (y en `cli` si se usa WP-CLI). No montes el directorio `themes/` vacío sobre todos los temas de WordPress.

Para crear paquetes instalables desde el panel de un proveedor WordPress:

```sh
./scripts/build-zips.sh
```

El script genera un ZIP por cada subdirectorio de `themes/` y `plugins/` en `dist/`. Instala y activa esos paquetes en un WordPress nuevo y luego carga el contenido del cliente. Los ZIP y los datos locales no forman parte de Git.

## GitHub

Repositorio público: <https://github.com/GC-Ilustraciones/website_wp>. Antes de cada envío, revisa `git status` para confirmar que solo hay código y documentación. El remoto `origin` usa SSH; no depende de la sesión de GitHub CLI.
