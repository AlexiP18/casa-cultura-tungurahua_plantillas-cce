# 🎭 Plantillas CCE - Casa de la Cultura de Tungurahua

Sistema de plantillas modulares para WordPress desarrollado para la **Casa de la Cultura Ecuatoriana Núcleo de Tungurahua**.

## 📋 Descripción

Este proyecto contiene un conjunto de plantillas personalizadas para WordPress que implementan **Custom Post Types (CPT)** con campos personalizados mediante **Advanced Custom Fields (ACF Free)**. Está diseñado para integrarse con el tema **PopularFX** y gestionar el contenido cultural de la institución.

## 🎯 Propósito

- Gestionar información de **artistas locales** con sus obras, disciplinas y datos de contacto
- Administrar **talleres culturales** con información de instructores, costos y horarios
- Publicar **eventos culturales** con fechas, ubicaciones y detalles
- Manejar **noticias institucionales** con categorías y filtros
- Mantener un **blog institucional** para contenido general

## 📁 Estructura del Proyecto

```
plantillas_cce/
├── README.md
├── artista/                    # CPT: Artistas
│   ├── acf_fields-artista.php  # Definición de campos ACF
│   ├── artista-functions.php   # Funciones, CPT, shortcodes
│   ├── single-artista.php      # Plantilla individual
│   ├── archive-artista.php     # Plantilla de archivo
│   ├── artista-styles.css      # Estilos single
│   └── archive-artista-styles.css
├── taller/                     # CPT: Talleres
│   ├── acf_fields-taller.php
│   ├── taller-functions.php
│   ├── single-taller.php
│   ├── archive-taller.php
│   ├── taller-styles.css
│   ├── archive-taller-styles.css
│   └── taller-slider.js        # JavaScript para slider
└── agenda/                     # Módulos de agenda
    ├── blog/                   # CPT: Blog Institucional
    │   ├── acf_fields-blog.php
    │   ├── blog-functions.php
    │   ├── single-blog.php
    │   ├── archive-blog.php
    │   └── blog-styles.css
    ├── evento/                 # CPT: Eventos
    │   ├── acf_fields-evento.php
    │   ├── evento-functions.php
    │   ├── single-evento.php
    │   ├── archive-evento.php
    │   └── eventos-styles.css
    └── noticia/                # CPT: Noticias
        ├── acf_fields-noticia.php
        ├── noticia-functions.php
        ├── single-noticia.php
        ├── archive-noticia.php
        ├── noticias-styles.css
        └── noticias-filtros.js
```

## 🔧 Instalación

### Requisitos
- WordPress 5.0+
- Tema PopularFX (o compatible)
- Plugin ACF (Advanced Custom Fields) - Versión Free
- PHP 7.4+

### Pasos de instalación

1. **Copiar la carpeta** `plantillas_cce` dentro del tema:
   ```
   /wp-content/themes/popularfx/plantillas/
   ```

2. **Agregar el cargador de módulos** en el `functions.php` del tema:
   ```php
   /**
    * Cargar módulos de Custom Post Types
    */
   function cc_cargar_modulos() {
       $modulos = array(
           'blog'    => '/plantillas/agenda/blog/blog-functions.php',
           'taller'  => '/plantillas/taller/taller-functions.php',
           'artista' => '/plantillas/artista/artista-functions.php',
           'evento'  => '/plantillas/agenda/evento/evento-functions.php',
           'noticia' => '/plantillas/agenda/noticia/noticia-functions.php',
       );

       foreach ( $modulos as $nombre => $ruta ) {
           $archivo = get_template_directory() . $ruta;
           if ( file_exists( $archivo ) ) {
               require_once $archivo;
           }
       }
   }
   cc_cargar_modulos();
   ```

3. **Guardar permalinks**: Ir a *Ajustes > Enlaces permanentes* y guardar sin cambios para regenerar las reglas de reescritura.

## 📦 Custom Post Types

| CPT | Slug | Descripción |
|-----|------|-------------|
| `artista` | `/artistas/` | Perfiles de artistas con galería, contacto y redes sociales |
| `taller` | `/talleres/` | Talleres culturales con instructor, costo y horarios |
| `evento` | `/eventos/` | Eventos con fecha, hora, ubicación y detalles |
| `noticia` | `/noticias/` | Noticias institucionales con categorías |
| `blog` | `/blog/` | Publicaciones del blog institucional |

## 🏷️ Shortcodes Disponibles

### Artistas
```php
[mostrar_artistas cantidad="9" disciplina="pintura" orden="title" direccion="ASC"]
[artistas limit="6"]
```

### Talleres
```php
[mostrar_talleres cantidad="9" categorias="arte,musica" orden="date" direccion="DESC"]
```

## 🎨 Campos ACF por CPT

### Artista
- `disciplina_artistica` - Disciplina del artista
- `especialidad` - Especialidad específica
- `slider_imagenes` - Grupo con 5 imágenes para galería
- `trayectoria` - Biografía/trayectoria (WYSIWYG)
- `contacto` - Grupo: teléfono, email
- `redes_sociales` - Grupo: Facebook, Instagram, Twitter, YouTube, TikTok, Website

### Taller
- `instructor` - Nombre del instructor
- `costo` - Precio del taller
- `slider_imagenes` - Galería de imágenes
- `horario` - Información de horarios
- `requisitos` - Requisitos para inscripción

### Evento
- `fecha_evento` - Fecha del evento
- `hora_inicio` / `hora_fin` - Horarios
- `ubicacion` - Lugar del evento
- `organizador` - Información del organizador

### Noticia
- `destacada` - Marcar como destacada
- `urgente` - Marcar como urgente
- `galeria` - Galería de imágenes

## ⚙️ Funcionalidades Incluidas

Cada módulo incluye:

- ✅ Registro del Custom Post Type
- ✅ Definición de campos ACF (compatible con versión Free)
- ✅ Plantillas single y archive
- ✅ Estilos CSS responsivos
- ✅ Shortcodes para insertar en páginas
- ✅ Columnas personalizadas en el admin
- ✅ Widget de dashboard con resumen
- ✅ Mensajes personalizados de actualización
- ✅ Soporte para Gutenberg (show_in_rest)

## 🛠️ Desarrollo

### Convenciones de código
- Prefijo de funciones: `cc_` (Casa de la Cultura)
- Prefijo talleres: `ccct_` (Casa de la Cultura Talleres)
- Seguir estándares de WordPress Coding Standards

### Rutas de assets
Los estilos y scripts se cargan desde:
```php
get_template_directory_uri() . '/plantillas/[modulo]/[archivo]'
```

## 📝 Notas Importantes

1. **ACF Free**: Este proyecto usa ACF Free. Los campos de galería se implementan como grupos con campos de imagen individuales.

2. **Tema padre**: Las plantillas están diseñadas para el tema PopularFX pero pueden adaptarse a otros temas.

3. **Permalinks**: Después de activar los CPT, siempre guardar los enlaces permanentes.

4. **Backups**: Se incluyen archivos `.backup` de versiones anteriores para referencia.

## 👥 Créditos

Desarrollado para la **Casa de la Cultura Ecuatoriana Núcleo de Tungurahua**

## 📄 Licencia

Uso exclusivo para la Casa de la Cultura Ecuatoriana Núcleo de Tungurahua.

---

*Versión 1.0 - Noviembre 2025*
