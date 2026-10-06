# Despliegue

La aplicación puede desplegarse como contenedor Docker. La configuración incluida apunta a Render y PostgreSQL; no subas `.env` ni una base SQLite local.

## Render para una demo

1. Sube este repositorio a GitHub y crea una cuenta en Render.
2. En Render, crea un servicio desde el repositorio. Render detectará `render.yaml` y construirá `Dockerfile`.
3. Antes de iniciar el servicio, configura estas variables en Render:
   - `APP_URL`: la URL HTTPS asignada al servicio, por ejemplo `https://genesis.onrender.com`.
   - `DB_URL`: una URL de conexión PostgreSQL externa con SSL, por ejemplo la cadena de conexión de Neon.
   - `ADMIN_EMAIL` y `ADMIN_PASSWORD`: el correo y una contraseña larga y única para el administrador.
4. Despliega. El contenedor migra la base de datos y crea los roles y el administrador. Entra a `/` y accede con el correo y la contraseña que configuraste.
5. Crea los productos desde el panel de administración.

El cliente del navegador usa `/api`, por lo que frontend y API deben servirse desde el mismo dominio. `APP_KEY` se genera desde el blueprint; no reutilices una clave pública ni la compartas.

## Límites del plan gratuito

Render Free duerme tras 15 minutos sin tráfico y puede tardar alrededor de un minuto en despertar. Su sistema de archivos es efímero: las imágenes subidas al disco local desaparecen al reiniciar, dormir o desplegar. Usa almacenamiento de objetos externo antes de guardar imágenes reales. La base de datos PostgreSQL gratuita de Render expira a los 30 días; para conservar los datos, conecta un proveedor PostgreSQL externo con plan gratuito, como Neon, y revisa sus cuotas vigentes. Los planes gratuitos son para demos, no para una tienda de producción.

Railway es otra opción con contenedores, pero su plan Free ofrece crédito mensual limitado, no alojamiento ilimitado; el uso superior al crédito puede generar cargos. Revisa el precio y configura límites antes de desplegar.

## Ejecutar localmente

Requiere PHP 8.3+, Composer, Node.js y SQLite. Desde la raíz del repo:

```bash
composer run setup
composer run dev
```

La aplicación local queda en `http://127.0.0.1:8000`. `composer run setup` crea `.env`, genera `APP_KEY`, crea SQLite, ejecuta migraciones y compila los recursos.
