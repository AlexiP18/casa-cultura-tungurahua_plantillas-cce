# Sistema de Registro Público de Artistas
## Casa de la Cultura de Tungurahua

### 📋 Descripción

Sistema completo de registro público para artistas con aprobación administrativa. Permite que cualquier persona se registre como artista en el sitio web, quedando su perfil en estado pendiente hasta que un administrador lo apruebe.

---

## 🚀 Cómo Activar el Formulario

### Paso 1: Crear la Página de Registro

1. En el panel de WordPress, ve a **Páginas → Añadir nueva**
2. Dale un título a la página (ejemplo: "Registro de Artistas")
3. En la barra lateral derecha, busca **"Atributos de página"** o **"Plantilla"**
4. Selecciona la plantilla: **"Registro de Artista"**
5. Publica la página

### Paso 2: Obtener la URL

Una vez publicada, copia la URL de la página (ejemplo: `https://tusitio.com/registro-artistas`)

### Paso 3: Agregar a tu Menú (Opcional)

1. Ve a **Apariencia → Menús**
2. Agrega la página de registro a tu menú principal
3. Guarda los cambios

---

## ✨ Características del Sistema

### Para el Público:

- ✅ Formulario completo con todos los campos de artista
- ✅ Selección de disciplinas artísticas (hasta 13 categorías)
- ✅ Carga de hasta 5 imágenes para el slider
- ✅ Campos de contacto y redes sociales
- ✅ Mensaje de confirmación al enviar
- ✅ Diseño responsive para móviles y tablets

### Para los Administradores:

- 📧 **Notificación por Email** cuando se registra un nuevo artista
- 📊 **Columna de Estado** en la lista de artistas (Publicado, Pendiente, Borrador)
- ⚠️ **Alerta en el Panel** mostrando cuántos artistas hay pendientes de aprobación
- 🔍 **Revisión Fácil** con enlace directo para editar y aprobar perfiles

---

## 📧 Sistema de Notificaciones

Cuando un artista se registra:

1. El perfil se guarda con estado **"Pendiente"**
2. Se envía un email automático al administrador con:
   - Nombre del artista
   - Disciplinas seleccionadas
   - Información de contacto
   - Enlace directo para revisar el perfil
3. Aparece una alerta en el panel de WordPress

---

## 🛠️ Cómo Aprobar un Artista

### Opción 1: Desde el Email
1. Abre el email de notificación
2. Haz clic en el enlace "Revisar y aprobar"
3. Revisa la información
4. En la sección **"Publicar"**, cambia el estado a **"Publicado"**
5. Haz clic en **"Actualizar"**

### Opción 2: Desde el Panel de WordPress
1. Ve a **Artistas → Todos los artistas**
2. Haz clic en el filtro **"Pendiente"** en la parte superior
3. Selecciona el artista que quieres revisar
4. Edita la información si es necesario
5. Cambia el estado a **"Publicado"**
6. Haz clic en **"Actualizar"**

---

## 🎨 Personalización

### Cambiar el Email de Notificación

El email se envía a la dirección configurada en **Ajustes → Generales → Correo electrónico de la administración**.

Para cambiar los textos del email, edita la función `cc_enviar_notificacion_nuevo_artista()` en el archivo `artista-functions.php`.

### Cambiar los Campos del Formulario

Para ocultar o agregar campos al formulario de registro, edita el array `fields` en `page-registro-artista.php`:

```php
'fields' => array(
    'field_disciplina_artistica',
    'field_especialidad',
    // Agrega o quita campos aquí
),
```

### Cambiar los Colores

Los colores del formulario se definen en `registro-artista-styles.css`:
- **Rosa principal**: `#e71e8a`
- **Azul oscuro**: `#1d5695`
- **Azul claro**: `#0084ff`

---

## 📝 Campos del Formulario

### Información Básica:
- **Nombre del Artista** (Título del post)
- **Descripción** (Contenido principal)

### Disciplinas y Especialidad:
- **Disciplinas Artísticas** (Checkbox múltiple - 13 opciones)
- **Especialidad** (Textarea para detalles específicos)

### Imágenes:
- **Slider de Imágenes** (Hasta 5 imágenes)

### Trayectoria:
- **Trayectoria Artística** (Editor WYSIWYG)

### Contacto:
- **Teléfono**
- **Email**

### Redes Sociales:
- Facebook
- Instagram
- Twitter/X
- YouTube
- TikTok
- Sitio Web

---

## 🔒 Seguridad

- ✅ Los artistas registrados **NO aparecen públicamente** hasta ser aprobados
- ✅ Solo se muestran artistas con estado **"Publicado"**
- ✅ Los registros pendientes solo son visibles para administradores
- ✅ Validación de campos en el servidor (ACF)

---

## 🐛 Solución de Problemas

### El formulario no aparece
- Verifica que ACF Pro esté instalado y activado
- Asegúrate de haber seleccionado la plantilla "Registro de Artista"
- Verifica que los campos ACF estén sincronizados

### No llega el email de notificación
- Verifica la dirección de email en **Ajustes → Generales**
- Revisa la carpeta de spam
- Considera instalar un plugin de SMTP como "WP Mail SMTP"

### Los estilos no se aplican
- Limpia el caché del navegador (Ctrl + Shift + R)
- Verifica que el archivo `registro-artista-styles.css` exista en la carpeta correcta
- Revisa la consola del navegador (F12) por errores

---

## 📁 Archivos del Sistema

```
plantillas/artista/
├── page-registro-artista.php          # Template del formulario
├── registro-artista-styles.css        # Estilos del formulario
├── artista-functions.php              # Funciones (notificaciones, hooks)
├── single-artista.php                 # Perfil individual
├── archive-artista.php                # Listado de artistas
└── artista-styles.css                 # Estilos generales
```

---

## 🎯 Próximas Mejoras (Opcionales)

- [ ] Confirmación por email al artista cuando es aprobado
- [ ] Panel de control para artistas registrados
- [ ] Edición de perfil por el propio artista
- [ ] Sistema de categorías y filtros avanzados
- [ ] Estadísticas de visitas al perfil

---

## 📞 Soporte

Para problemas o personalizaciones adicionales, contacta con el equipo de desarrollo.

**Desarrollado para Casa de la Cultura de Tungurahua**
Versión: 1.0 | Fecha: Febrero 2026
