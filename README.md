# Mini Blog - Documentación del Proyecto

## Descripción
Blog personal desarrollado en Laravel. Permite publicar artículos con texto, imágenes y videos de YouTube usando un editor de bloques (Editor.js). Incluye autenticación solo para el admin, panel de administración, y una landing page moderna y personalizable.

---

## Funcionalidades principales
- **Landing page pública**: moderna, con presentación, últimos posts y sección "Sobre mí". Textos e imagen editables desde el panel.
- **Blog público**: listado de posts en tarjetas, paginación, vista individual de post con bloques renderizados.
- **Panel de administración** (requiere login):
  - Crear, editar y eliminar posts (Editor.js con bloques de texto, imagen, lista, YouTube).
  - Subida y borrado de imágenes desde el editor.
  - Edición de textos e imagen de la landing page desde un formulario.
- **Menú adaptativo**: muestra opciones según autenticación.
- **Diseño responsive** y soporte dark mode.

---

## Estructura de carpetas relevante
- `app/Models/Post.php` — Modelo de post.
- `app/Models/LandingText.php` — Modelo para textos editables de la landing.
- `app/Http/Controllers/PostController.php` — Lógica de posts.
- `app/Http/Controllers/LandingTextController.php` — Lógica de edición de landing.
- `resources/views/` — Vistas Blade:
  - `blog.blade.php` — Landing page pública.
  - `posts/` — CRUD de posts.
  - `landing/edit.blade.php` — Edición de textos/imagen de la landing.
  - `layouts/` — Layouts y navegación.
- `config/landing.php` — Textos por defecto de la landing.

---

## Personalización de la landing
1. Ingresa como admin.
2. Ve a "Editar landing" en el menú.
3. Cambia textos o imagen y guarda. Los cambios se reflejan en la página principal.

---

## Crear/editar posts
1. Ingresa como admin.
2. Ve a "Crear Post" o edita uno existente desde el dashboard.
3. Usa el editor de bloques para agregar texto, imágenes, listas o videos de YouTube.

---

## Agregar o cambiar imagen de la landing
- En el formulario de edición de la landing, sube una nueva imagen o elimina la actual.
- La imagen se almacena en `/storage/landing/` y se muestra en la landing.

---

## Cambiar nombre del blog
- Edita el campo "blog_name" desde la edición de la landing. Se refleja en el menú y otros lugares donde se use.

---

## Notas técnicas
- Los textos de la landing se guardan en la tabla `landing_texts` y se pueden restaurar a los valores por defecto de `config/landing.php` si se eliminan.
- El editor de posts usa Editor.js con soporte para bloques de texto, imagen, lista y YouTube.
- El sistema de autenticación usa Laravel Breeze (solo login, sin registro público).

---

## Requisitos para desarrollo
- PHP 8.1+
- Composer
- Node.js 20+ y npm
- Extensión de PHP para SQLite (o configurar otra DB)

---

## Comandos útiles
- Instalar dependencias: `composer install && npm install`
- Compilar assets: `npm run dev` (o `npm run build` para producción)
- Ejecutar migraciones: `php artisan migrate`
- Poblar con datos de ejemplo: `php artisan db:seed`
- Iniciar servidor: `php artisan serve`

---

## Contacto y soporte
Para dudas o mejoras, contactar a Martín o al desarrollador del blog.
