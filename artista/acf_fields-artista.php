<?php
/**
 * Campos ACF para Artistas
 * Casa de la Cultura de Tungurahua
 */

if ( ! defined( 'ABSPATH' ) ) {
exit;
}

if ( function_exists( 'acf_add_local_field_group' ) ) :

acf_add_local_field_group( array(
'key' => 'group_artista_info',
'title' => 'Información del Artista',
'fields' => array(

// DISCIPLINA ARTÍSTICA
array(
'key' => 'field_disciplina_artista',
'label' => 'Disciplina Artística',
'name' => 'disciplina_artistica',
'type' => 'text',
'instructions' => 'Ej: Pintor, Músico, Escultor, Poeta, etc.',
'required' => 1,
'wrapper' => array(
'width' => '50',
),
),

// ESPECIALIDAD
array(
'key' => 'field_especialidad_artista',
'label' => 'Especialidad',
'name' => 'especialidad',
'type' => 'text',
'instructions' => 'Ej: Arte Abstracto, Jazz, Cerámica, etc.',
'required' => 0,
'wrapper' => array(
'width' => '50',
),
),

// SLIDER DE IMÁGENES
array(
'key' => 'field_slider_artista',
'label' => 'Slider de Imágenes',
'name' => 'slider_imagenes',
'type' => 'group',
'instructions' => 'Agregue hasta 5 imágenes para el slider',
'required' => 0,
'layout' => 'block',
'sub_fields' => array(
array(
'key' => 'field_imagen_1',
'label' => 'Imagen 1',
'name' => 'imagen_1',
'type' => 'image',
'required' => 0,
'return_format' => 'array',
'preview_size' => 'medium',
'library' => 'all',
'wrapper' => array(
'width' => '50',
),
),
array(
'key' => 'field_imagen_2',
'label' => 'Imagen 2',
'name' => 'imagen_2',
'type' => 'image',
'required' => 0,
'return_format' => 'array',
'preview_size' => 'medium',
'library' => 'all',
'wrapper' => array(
'width' => '50',
),
),
array(
'key' => 'field_imagen_3',
'label' => 'Imagen 3',
'name' => 'imagen_3',
'type' => 'image',
'required' => 0,
'return_format' => 'array',
'preview_size' => 'medium',
'library' => 'all',
'wrapper' => array(
'width' => '50',
),
),
array(
'key' => 'field_imagen_4',
'label' => 'Imagen 4',
'name' => 'imagen_4',
'type' => 'image',
'required' => 0,
'return_format' => 'array',
'preview_size' => 'medium',
'library' => 'all',
'wrapper' => array(
'width' => '50',
),
),
array(
'key' => 'field_imagen_5',
'label' => 'Imagen 5',
'name' => 'imagen_5',
'type' => 'image',
'required' => 0,
'return_format' => 'array',
'preview_size' => 'medium',
'library' => 'all',
'wrapper' => array(
'width' => '50',
),
),
),
),

// TRAYECTORIA
array(
'key' => 'field_trayectoria_artista',
'label' => 'Trayectoria',
'name' => 'trayectoria',
'type' => 'wysiwyg',
'instructions' => 'Biografía y trayectoria del artista',
'required' => 0,
'tabs' => 'all',
'toolbar' => 'full',
'media_upload' => 1,
),

// CONTACTO
array(
'key' => 'field_contacto_artista',
'label' => 'Información de Contacto',
'name' => 'contacto',
'type' => 'group',
'layout' => 'block',
'sub_fields' => array(
array(
'key' => 'field_telefono_artista',
'label' => 'Teléfono / WhatsApp',
'name' => 'telefono',
'type' => 'text',
'wrapper' => array(
'width' => '50',
),
),
array(
'key' => 'field_email_artista',
'label' => 'Correo Electrónico',
'name' => 'email',
'type' => 'email',
'wrapper' => array(
'width' => '50',
),
),
),
),

// REDES SOCIALES
array(
'key' => 'field_redes_artista',
'label' => 'Redes Sociales',
'name' => 'redes_sociales',
'type' => 'group',
'layout' => 'block',
'sub_fields' => array(
array(
'key' => 'field_facebook_artista',
'label' => 'Facebook',
'name' => 'facebook',
'type' => 'url',
'wrapper' => array(
'width' => '50',
),
),
array(
'key' => 'field_instagram_artista',
'label' => 'Instagram',
'name' => 'instagram',
'type' => 'url',
'wrapper' => array(
'width' => '50',
),
),
array(
'key' => 'field_twitter_artista',
'label' => 'Twitter / X',
'name' => 'twitter',
'type' => 'url',
'wrapper' => array(
'width' => '50',
),
),
array(
'key' => 'field_youtube_artista',
'label' => 'YouTube',
'name' => 'youtube',
'type' => 'url',
'wrapper' => array(
'width' => '50',
),
),
array(
'key' => 'field_tiktok_artista',
'label' => 'TikTok',
'name' => 'tiktok',
'type' => 'url',
'wrapper' => array(
'width' => '50',
),
),
array(
'key' => 'field_website_artista',
'label' => 'Sitio Web',
'name' => 'website',
'type' => 'url',
'wrapper' => array(
'width' => '50',
),
),
),
),

),
'location' => array(
array(
array(
'param' => 'post_type',
'operator' => '==',
'value' => 'artista',
),
),
),
'menu_order' => 0,
'position' => 'normal',
'style' => 'default',
'label_placement' => 'top',
'instruction_placement' => 'label',
'active' => true,
) );

endif;
