<?php
/**
 * ========================================
 * CUSTOM POST TYPE: BLOG INSTITUCIONAL
 * Casa de la Cultura
 * ========================================
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registrar Custom Post Type: Blog
 */
function cc_registrar_post_type_blog() {
    $labels = array(
        'name' => 'Blog Institucional',
        'singular_name' => 'Entrada de Blog',
        'menu_name' => 'Blog',
        'name_admin_bar' => 'Entrada de Blog',
        'add_new' => 'Agregar Nueva',
        'add_new_item' => 'Agregar Nueva Entrada',
        'new_item' => 'Nueva Entrada',
        'edit_item' => 'Editar Entrada',
        'view_item' => 'Ver Entrada',
        'view_items' => 'Ver Entradas',
        'all_items' => 'Todas las Entradas',
        'search_items' => 'Buscar Entradas',
        'parent_item_colon' => 'Entrada Padre:',
        'not_found' => 'No se encontraron entradas',
        'not_found_in_trash' => 'No se encontraron entradas en la papelera',
        'archives' => 'Archivo de Blog',
        'attributes' => 'Atributos de Entrada',
        'insert_into_item' => 'Insertar en entrada',
        'uploaded_to_this_item' => 'Subido a esta entrada',
        'featured_image' => 'Imagen destacada',
        'set_featured_image' => 'Establecer imagen destacada',
        'remove_featured_image' => 'Remover imagen destacada',
        'use_featured_image' => 'Usar como imagen destacada',
        'filter_items_list' => 'Filtrar lista de entradas',
        'items_list_navigation' => 'Navegación de lista de entradas',
        'items_list' => 'Lista de entradas',
    );

    $args = array(
        'labels' => $labels,
        'description' => 'Blog institucional de la Casa de la Cultura',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-admin-post',
        'capability_type' => 'post',
        'hierarchical' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'author'),
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'blog',
            'with_front' => false,
            'feeds' => true,
            'pages' => true
        ),
        'show_in_rest' => true,
        'rest_base' => 'blog',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type('blog', $args);
}
add_action('init', 'cc_registrar_post_type_blog');

/**
 * Registrar Taxonomías para Blog
 */
function cc_registrar_taxonomias_blog() {
    
    // Taxonomía: Categorías del Blog
    $labels_categoria = array(
        'name' => 'Categorías del Blog',
        'singular_name' => 'Categoría',
        'search_items' => 'Buscar Categorías',
        'all_items' => 'Todas las Categorías',
        'parent_item' => 'Categoría Padre',
        'parent_item_colon' => 'Categoría Padre:',
        'edit_item' => 'Editar Categoría',
        'update_item' => 'Actualizar Categoría',
        'add_new_item' => 'Agregar Nueva Categoría',
        'new_item_name' => 'Nombre de Nueva Categoría',
        'menu_name' => 'Categorías',
    );

    $args_categoria = array(
        'hierarchical' => true,
        'labels' => $labels_categoria,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'blog-categoria'),
        'show_in_rest' => true,
    );

    register_taxonomy('blog_categoria_tax', array('blog'), $args_categoria);

    // Taxonomía: Etiquetas del Blog
    $labels_etiqueta = array(
        'name' => 'Etiquetas del Blog',
        'singular_name' => 'Etiqueta',
        'search_items' => 'Buscar Etiquetas',
        'popular_items' => 'Etiquetas Populares',
        'all_items' => 'Todas las Etiquetas',
        'edit_item' => 'Editar Etiqueta',
        'update_item' => 'Actualizar Etiqueta',
        'add_new_item' => 'Agregar Nueva Etiqueta',
        'new_item_name' => 'Nombre de Nueva Etiqueta',
        'menu_name' => 'Etiquetas',
    );

    $args_etiqueta = array(
        'hierarchical' => false,
        'labels' => $labels_etiqueta,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'blog-etiqueta'),
        'show_in_rest' => true,
    );

    register_taxonomy('blog_etiqueta_tax', array('blog'), $args_etiqueta);
}
add_action('init', 'cc_registrar_taxonomias_blog');

/**
 * Flush rewrite rules en activación
 */
function cc_blog_flush_rewrites() {
    cc_registrar_post_type_blog();
    cc_registrar_taxonomias_blog();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cc_blog_flush_rewrites');

/**
 * ========================================
 * FUNCIONES HELPER PARA BLOG
 * ========================================
 */

/**
 * Obtener información de la categoría del blog
 */
function cc_get_blog_categoria_info($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categoria = get_field('blog_categoria', $post_id);
    
    $categorias = array(
        'mensaje_directora' => array('label' => 'Mensaje de la Directora', 'icon' => 'fa-message', 'color' => '#8e44ad'),
        'conmemoracion' => array('label' => 'Conmemoración', 'icon' => 'fa-calendar-star', 'color' => '#e74c3c'),
        'rendicion_cuentas' => array('label' => 'Rendición de Cuentas', 'icon' => 'fa-chart-line', 'color' => '#16a085'),
        'logros' => array('label' => 'Logros y Reconocimientos', 'icon' => 'fa-trophy', 'color' => '#f39c12'),
        'proyectos' => array('label' => 'Proyectos en Curso', 'icon' => 'fa-lightbulb', 'color' => '#3498db'),
        'reflexion' => array('label' => 'Reflexión Cultural', 'icon' => 'fa-brain', 'color' => '#9b59b6'),
        'opinion' => array('label' => 'Opinión y Análisis', 'icon' => 'fa-comments', 'color' => '#34495e'),
        'general' => array('label' => 'General', 'icon' => 'fa-pen-to-square', 'color' => '#95a5a6')
    );
    
    return $categorias[$categoria] ?? $categorias['general'];
}

/**
 * Obtener galería de imágenes del blog
 */
function cc_get_blog_galeria($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $imagenes = array();
    
    // Imagen destacada primero
    $imagen_destacada = get_field('blog_imagen_destacada', $post_id);
    if ($imagen_destacada && is_array($imagen_destacada)) {
        $imagenes[] = $imagen_destacada;
    }
    
    // Imágenes adicionales (2-5)
    for ($i = 2; $i <= 5; $i++) {
        $imagen = get_field('blog_imagen_' . $i, $post_id);
        if ($imagen && is_array($imagen)) {
            $imagenes[] = $imagen;
        }
    }
    
    return $imagenes;
}

/**
 * Obtener archivos adjuntos de rendición de cuentas
 */
function cc_get_blog_archivos($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $archivos = array();
    
    for ($i = 1; $i <= 3; $i++) {
        $titulo = get_field('blog_archivo_' . $i . '_titulo', $post_id);
        $archivo = get_field('blog_archivo_' . $i, $post_id);
        
        if ($archivo && is_array($archivo)) {
            $archivos[] = array(
                'titulo' => $titulo ? $titulo : 'Archivo ' . $i,
                'archivo' => $archivo
            );
        }
    }
    
    return $archivos;
}

/**
 * Calcular tiempo de lectura
 */
function cc_calcular_tiempo_lectura($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Si hay un tiempo personalizado, usarlo
    $tiempo_custom = get_field('blog_tiempo_lectura', $post_id);
    if ($tiempo_custom) {
        return intval($tiempo_custom);
    }
    
    // Calcular automáticamente
    $contenido = get_post_field('post_content', $post_id);
    $contenido_limpio = strip_tags($contenido);
    $palabras = str_word_count($contenido_limpio);
    
    // Promedio de 200 palabras por minuto
    $minutos = ceil($palabras / 200);
    
    return max(1, $minutos);
}

/**
 * Obtener información del autor (personalizado o WP)
 */
function cc_get_blog_autor_info($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $usar_personalizado = get_field('blog_autor_personalizado', $post_id);
    
    if ($usar_personalizado) {
        $nombre = get_field('blog_autor_nombre', $post_id);
        $cargo = get_field('blog_autor_cargo', $post_id);
        $bio = get_field('blog_autor_bio', $post_id);
        $foto = get_field('blog_autor_foto', $post_id);
        
        return array(
            'nombre' => $nombre ? $nombre : get_the_author_meta('display_name'),
            'cargo' => $cargo,
            'bio' => $bio,
            'foto' => $foto,
            'personalizado' => true
        );
    }
    
    // Usar datos de WordPress
    $author_id = get_post_field('post_author', $post_id);
    
    return array(
        'nombre' => get_the_author_meta('display_name', $author_id),
        'cargo' => get_the_author_meta('description', $author_id),
        'bio' => get_the_author_meta('description', $author_id),
        'foto' => get_avatar_url($author_id, array('size' => 150)),
        'personalizado' => false
    );
}

/**
 * Obtener entradas relacionadas
 */
function cc_get_entradas_relacionadas($post_id = null, $limit = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categoria = get_field('blog_categoria', $post_id);
    
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $limit,
        'post__not_in' => array($post_id),
        'meta_query' => array(
            array(
                'key' => 'blog_categoria',
                'value' => $categoria,
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $query = new WP_Query($args);
    
    // Si no hay suficientes, obtener las más recientes
    if ($query->post_count < $limit) {
        $args = array(
            'post_type' => 'blog',
            'posts_per_page' => $limit,
            'post__not_in' => array($post_id),
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $query = new WP_Query($args);
    }
    
    return $query;
}

/**
 * Obtener entradas destacadas
 */
function cc_get_entradas_destacadas($limit = 3) {
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'blog_destacada',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    return new WP_Query($args);
}

/**
 * Obtener entradas por categoría ACF
 */
function cc_get_entradas_por_categoria($categoria, $limit = 10) {
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'blog_categoria',
                'value' => $categoria,
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    return new WP_Query($args);
}

/**
 * ========================================
 * SHORTCODES
 * ========================================
 */

/**
 * Shortcode: Entradas destacadas
 * Uso: [blog_destacadas limit="3"]
 */
function cc_shortcode_blog_destacadas($atts) {
    $atts = shortcode_atts(array(
        'limit' => 3,
    ), $atts);
    
    $query = cc_get_entradas_destacadas($atts['limit']);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="blog-destacadas-grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('blog_imagen_destacada');
            $categoria = cc_get_blog_categoria_info();
            $resumen = get_field('blog_resumen');
            $tiempo = cc_calcular_tiempo_lectura();
            
            echo '<article class="blog-destacada-card">';
            
            if ($imagen) {
                echo '<div class="blog-card-imagen">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '">';
                echo '</a>';
                echo '<span class="blog-categoria-badge" style="background: ' . $categoria['color'] . ';">';
                echo '<i class="fas ' . $categoria['icon'] . '"></i> ' . $categoria['label'];
                echo '</span>';
                echo '</div>';
            }
            
            echo '<div class="blog-card-contenido">';
            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            if ($resumen) {
                echo '<p>' . esc_html($resumen) . '</p>';
            }
            echo '<div class="blog-card-meta">';
            echo '<span><i class="far fa-calendar"></i> ' . get_the_date() . '</span>';
            echo '<span><i class="far fa-clock"></i> ' . $tiempo . ' min</span>';
            echo '</div>';
            echo '<a href="' . get_permalink() . '" class="btn-leer-mas">Leer más <i class="fas fa-arrow-right"></i></a>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    }
    
    return ob_get_clean();
}
add_shortcode('blog_destacadas', 'cc_shortcode_blog_destacadas');

/**
 * Shortcode: Últimas entradas
 * Uso: [ultimas_entradas limit="6"]
 */
function cc_shortcode_ultimas_entradas($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
    ), $atts);
    
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $atts['limit'],
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="ultimas-entradas-grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('blog_imagen_destacada');
            $categoria = cc_get_blog_categoria_info();
            $resumen = get_field('blog_resumen');
            
            echo '<article class="entrada-mini-card">';
            
            if ($imagen) {
                echo '<div class="entrada-mini-imagen">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($imagen['sizes']['medium'] ?? $imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '">';
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="entrada-mini-contenido">';
            echo '<span class="mini-categoria" style="color: ' . $categoria['color'] . ';">';
            echo '<i class="fas ' . $categoria['icon'] . '"></i> ' . $categoria['label'];
            echo '</span>';
            echo '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
            echo '<span class="mini-fecha">' . get_the_date() . '</span>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    }
    
    return ob_get_clean();
}
add_shortcode('ultimas_entradas', 'cc_shortcode_ultimas_entradas');

/**
 * ========================================
 * DASHBOARD WIDGET
 * ========================================
 */

/**
 * Dashboard widget: Resumen del blog
 */
function cc_dashboard_widget_blog() {
    $recientes = new WP_Query(array(
        'post_type' => 'blog',
        'posts_per_page' => 5,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    $total_entradas = wp_count_posts('blog')->publish;
    
    echo '<div class="blog-dashboard">';
    
    echo '<div class="blog-stats">';
    echo '<div class="stat-box">';
    echo '<span class="stat-number">' . $total_entradas . '</span>';
    echo '<span class="stat-label">Entradas Publicadas</span>';
    echo '</div>';
    echo '</div>';
    
    if ($recientes->have_posts()) {
        echo '<h4 style="margin-top: 20px; margin-bottom: 10px;"><i class="fas fa-blog"></i> Entradas Recientes</h4>';
        echo '<ul style="list-style: none; padding: 0; margin: 0;">';
        
        while ($recientes->have_posts()) {
            $recientes->the_post();
            $categoria = cc_get_blog_categoria_info();
            
            echo '<li style="padding: 10px 0; border-bottom: 1px solid #f0f0f1;">';
            echo '<strong><a href="' . get_edit_post_link() . '">' . get_the_title() . '</a></strong>';
            echo '<br><span style="font-size: 12px; color: #666;"><i class="far fa-calendar"></i> ' . get_the_date() . '</span>';
            echo '<br><span style="display: inline-block; padding: 3px 8px; background: ' . $categoria['color'] . '; color: #fff; border-radius: 10px; font-size: 11px; margin-top: 5px;">';
            echo '<i class="fas ' . $categoria['icon'] . '"></i> ' . $categoria['label'];
            echo '</span>';
            echo '</li>';
        }
        
        echo '</ul>';
        wp_reset_postdata();
    }
    
    echo '<p style="margin-top: 15px;">';
    echo '<a href="' . admin_url('edit.php?post_type=blog') . '" class="button button-primary">Ver Todas las Entradas</a> ';
    echo '<a href="' . admin_url('post-new.php?post_type=blog') . '" class="button">Nueva Entrada</a>';
    echo '</p>';
    
    echo '</div>';
}

function cc_agregar_dashboard_widget_blog() {
    wp_add_dashboard_widget(
        'cc_blog_dashboard',
        '<i class="fas fa-blog"></i> Blog Institucional - Casa de la Cultura',
        'cc_dashboard_widget_blog'
    );
}
add_action('wp_dashboard_setup', 'cc_agregar_dashboard_widget_blog');

/**
 * ========================================
 * ENQUEUE STYLES & SCRIPTS
 * ========================================
 */

/**
 * Cargar estilos y scripts para blog
 */
function cc_enqueue_blog_assets() {
    if (is_singular('blog') || is_post_type_archive('blog') || is_tax('blog_categoria_tax') || is_tax('blog_etiqueta_tax')) {
        
        // CSS
        wp_enqueue_style(
            'cc-blog-styles',
            get_template_directory_uri() . '/plantillas/agenda/blog/blog-styles.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript
        wp_enqueue_script(
            'cc-blog-scripts',
            get_template_directory_uri() . '/plantillas/agenda/blog/blog-scripts.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'cc_enqueue_blog_assets');

/**
 * ========================================
 * COLUMNAS PERSONALIZADAS EN ADMIN
 * ========================================
 */

/**
 * Agregar columnas personalizadas al listado de entradas
 */
function cc_blog_columnas_admin($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'title') {
            $new_columns['blog_categoria'] = 'Categoría';
            $new_columns['blog_destacada'] = 'Destacada';
            $new_columns['blog_tiempo_lectura'] = 'Lectura';
        }
    }
    
    return $new_columns;
}
add_filter('manage_blog_posts_columns', 'cc_blog_columnas_admin');

/**
 * Rellenar columnas personalizadas
 */
function cc_blog_columnas_contenido($column, $post_id) {
    switch ($column) {
        case 'blog_categoria':
            $categoria = cc_get_blog_categoria_info($post_id);
            echo '<span style="display: inline-block; padding: 5px 10px; background: ' . $categoria['color'] . '; color: #fff; border-radius: 12px; font-size: 11px; font-weight: 600;">';
            echo '<i class="fas ' . $categoria['icon'] . '"></i> ' . $categoria['label'];
            echo '</span>';
            break;
            
        case 'blog_destacada':
            $destacada = get_field('blog_destacada', $post_id);
            if ($destacada) {
                echo '<span style="color: #f39c12; font-size: 18px;" title="Destacada"><i class="fas fa-star"></i></span>';
            } else {
                echo '<span style="color: #ddd; font-size: 18px;"><i class="far fa-star"></i></span>';
            }
            break;
            
        case 'blog_tiempo_lectura':
            $tiempo = cc_calcular_tiempo_lectura($post_id);
            echo '<i class="far fa-clock"></i> ' . $tiempo . ' min';
            break;
    }
}
add_action('manage_blog_posts_custom_column', 'cc_blog_columnas_contenido', 10, 2);

/**
 * Hacer columnas ordenables
 */
function cc_blog_columnas_ordenables($columns) {
    $columns['blog_categoria'] = 'blog_categoria';
    return $columns;
}
add_filter('manage_edit-blog_sortable_columns', 'cc_blog_columnas_ordenables');

/**
 * ========================================
 * MENSAJES PERSONALIZADOS
 * ========================================
 */

/**
 * Personalizar mensajes de actualización
 */
function cc_blog_mensajes_personalizados($messages) {
    global $post;

    $messages['blog'] = array(
        0  => '',
        1  => 'Entrada actualizada. <a href="' . esc_url(get_permalink($post->ID)) . '">Ver entrada</a>',
        2  => 'Campo personalizado actualizado.',
        3  => 'Campo personalizado eliminado.',
        4  => 'Entrada actualizada.',
        5  => isset($_GET['revision']) ? 'Entrada restaurada a revisión de ' . wp_post_revision_title((int) $_GET['revision'], false) : false,
        6  => 'Entrada publicada. <a href="' . esc_url(get_permalink($post->ID)) . '">Ver entrada</a>',
        7  => 'Entrada guardada.',
        8  => 'Entrada enviada. <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">Vista previa</a>',
        9  => sprintf(
            'Entrada programada para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Vista previa</a>',
            date_i18n('M j, Y @ g:i a', strtotime($post->post_date)),
            esc_url(get_permalink($post->ID))
        ),
        10 => 'Borrador de entrada actualizado. <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">Vista previa</a>',
    );

    return $messages;
}
add_filter('post_updated_messages', 'cc_blog_mensajes_personalizados');
