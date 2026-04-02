

<?php

/**
 * TALLERES
 * Funciones para la integración de talleres de la Casa de la Cultura de Tungurahua
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registrar Custom Post Type: Taller
 */
function ccct_register_taller_post_type() {
	$labels = array(
		'name'               => 'Talleres',
		'singular_name'      => 'Taller',
		'menu_name'          => 'Talleres',
		'add_new'            => 'Agregar Taller',
		'add_new_item'       => 'Agregar Nuevo Taller',
		'edit_item'          => 'Editar Taller',
		'new_item'           => 'Nuevo Taller',
		'view_item'          => 'Ver Taller',
		'search_items'       => 'Buscar Talleres',
		'not_found'          => 'No se encontraron talleres',
		'not_found_in_trash' => 'No hay talleres en la papelera',
		'all_items'          => 'Todos los Talleres',
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'has_archive'         => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'talleres' ),
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'menu_position'       => 7,
		'menu_icon'           => 'dashicons-welcome-learn-more',
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest'        => true,
	);

	register_post_type( 'taller', $args );
}
add_action( 'init', 'ccct_register_taller_post_type' );

/**
 * Shortcode optimizado para mostrar talleres
 */
function ccct_mostrar_talleres_optimizado($atts) {
    // Extraer y definir atributos
    $atts = shortcode_atts(array(
        'cantidad' => 9,
        'categorias' => '',
        'orden' => 'date',
        'direccion' => 'DESC'
    ), $atts);
    
    // Asegurarnos que los estilos se cargan
    wp_enqueue_style('archive-taller-styles', get_template_directory_uri() . '/plantillas/taller/archive-taller-styles.css');
    
    // Construir argumentos para la consulta
    $args = array(
        'post_type' => 'taller',
        'posts_per_page' => intval($atts['cantidad']),
        'orderby' => $atts['orden'],
        'order' => $atts['direccion'],
        'no_found_rows' => true, // Optimización: evita contar filas
        'update_post_meta_cache' => false, // Optimización: no actualizar caché
        'update_post_term_cache' => false, // Optimización: no actualizar caché de términos
    );
    
    // Añadir filtro de categorías si se especifican
    if (!empty($atts['categorias'])) {
        $cat_array = explode(',', $atts['categorias']);
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $cat_array,
            ),
        );
    }
    
    // Iniciar buffer de salida
    ob_start();
    
    // Realizar la consulta
    $talleres_query = new WP_Query($args);
    
    if ($talleres_query->have_posts()) : ?>
        <div class="talleres-archive-container">
            <div class="talleres-grid">
                <?php while ($talleres_query->have_posts()) : $talleres_query->the_post(); 
                    // Obtener solo los datos necesarios
                    $imagen_url = '';
                    $imagen_alt = get_the_title();
                    
                    $imagenes = get_field('slider_imagenes');
                    if(!empty($imagenes['imagen_1'])) {
                        $imagen_url = $imagenes['imagen_1']['url'];
                        $imagen_alt = $imagenes['imagen_1']['alt'] ?: $imagen_alt;
                    } elseif (has_post_thumbnail()) {
                        $imagen_url = get_the_post_thumbnail_url(null, 'medium_large');
                    } else {
                        $imagen_url = get_template_directory_uri() . '/assets/img/taller-placeholder.jpg';
                    }
                    
                    $instructor = get_field('instructor');
                    $costo = get_field('costo');
                ?>
                    <article class="taller-card">
                        <div class="taller-card-image">
                            <img src="<?php echo esc_url($imagen_url); ?>" alt="<?php echo esc_attr($imagen_alt); ?>">
                        </div>
                        
                        <div class="taller-card-content">
                            <h2 class="taller-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <?php if ($instructor) : ?>
                                <div class="taller-card-instructor">
                                    <span class="instructor-label">Instructor:</span>
                                    <span class="instructor-name"><?php echo esc_html($instructor); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($costo) : ?>
                                <div class="taller-card-costo">
                                    <span class="costo-valor"><?php echo number_format($costo, 2); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="taller-card-btn">Ver detalles</a>
                        </div>
                    </article>
                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php else : ?>    <div class="talleres-empty">
            <p>No hay talleres disponibles en este momento.</p>
        </div>
    <?php endif;
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('mostrar_talleres', 'ccct_mostrar_talleres_optimizado');

/**
 * Asegurar que los estilos se cargan cuando se usa el shortcode
 */
function ccct_check_for_talleres_shortcode($posts) {
    if (empty($posts)) return $posts;
    
    $shortcode_found = false;
    
    // Buscar el shortcode en los posts
    foreach ($posts as $post) {
        if (stripos($post->post_content, '[mostrar_talleres') !== false) {
            $shortcode_found = true;
            break;
        }
    }
    
    // Si se encuentra el shortcode, cargar los estilos necesarios
    if ($shortcode_found) {
        add_action('wp_enqueue_scripts', 'ccct_load_talleres_styles', 100);
    }
    
    return $posts;
}
add_action('the_posts', 'ccct_check_for_talleres_shortcode');

/**
 * Cargar los estilos de talleres
 */
function ccct_load_talleres_styles() {
    wp_enqueue_style('archive-taller-styles', get_template_directory_uri() . '/plantillas/taller/archive-taller-styles.css');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
}

/**
 * Enqueue scripts y estilos para la plantilla de talleres
 */
function ccct_enqueue_taller_assets() {
    if (is_singular('taller') || is_page_template('page-listado-talleres.php')) {
        wp_enqueue_style('taller-styles', get_template_directory_uri() . '/plantillas/taller/taller-styles.css', array(), '1.0.10');
        
        // Sticky Filters for Listado
        if (is_page_template('page-listado-talleres.php')) {
            wp_enqueue_script(
                'cc-sticky-filters',
                get_template_directory_uri() . '/plantillas/agenda/assets/js/sticky-filters.js',
                array(),
                filemtime(get_template_directory() . '/plantillas/agenda/assets/js/sticky-filters.js'),
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'ccct_enqueue_taller_assets');

/**
 * Genera un enlace de WhatsApp formateado para Ecuador con un mensaje predefinido
 * 
 * @param string $telefono Número de teléfono (solo números)
 * @param string $instructor Nombre del instructor
 * @param string $curso Nombre del curso/taller
 * @return string URL completa para WhatsApp
 */
function ccct_generar_link_whatsapp($telefono, $instructor, $curso) {
    // Limpiar el número de teléfono (solo números)
    $telefono = preg_replace('/[^0-9]/', '', $telefono);
    
    // Formatear para Ecuador (+593)
    if (substr($telefono, 0, 1) == '0') {
        $telefono = '593' . substr($telefono, 1);
    } else {
        $telefono = '593' . $telefono;
    }
    
    // Crear el mensaje personalizado
    $mensaje = "Hola " . $instructor . " me podría ayudar con más información del curso " . $curso;
    
    // Generar la URL de WhatsApp
    return 'https://api.whatsapp.com/send?phone=' . $telefono . '&text=' . urlencode($mensaje);
}

/**
 * ========================================
 * COLUMNAS PERSONALIZADAS EN ADMIN
 * ========================================
 */

/**
 * Agregar columnas personalizadas
 */
function ccct_talleres_columnas_admin($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'title') {
            $new_columns['taller_instructor'] = 'Instructor';
            $new_columns['taller_costo'] = 'Costo';
            $new_columns['taller_imagen'] = 'Imagen';
        }
    }
    
    return $new_columns;
}
add_filter('manage_taller_posts_columns', 'ccct_talleres_columnas_admin');

/**
 * Rellenar columnas personalizadas
 */
function ccct_talleres_columnas_contenido($column, $post_id) {
    switch ($column) {
        case 'taller_instructor':
            $instructor = get_field('instructor', $post_id);
            echo $instructor ? esc_html($instructor) : '—';
            break;
            
        case 'taller_costo':
            $costo = get_field('costo', $post_id);
            if ($costo) {
                echo '<strong style="color: #e67e22;">$' . number_format($costo, 2) . '</strong>';
            } else {
                echo '—';
            }
            break;
            
        case 'taller_imagen':
            $imagenes = get_field('slider_imagenes', $post_id);
            if (!empty($imagenes['imagen_1'])) {
                echo '<img src="' . esc_url($imagenes['imagen_1']['sizes']['thumbnail']) . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">';
            } elseif (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50), array('style' => 'border-radius: 4px;'));
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_taller_posts_custom_column', 'ccct_talleres_columnas_contenido', 10, 2);

/**
 * Hacer columnas ordenables
 */
function ccct_talleres_columnas_ordenables($columns) {
    $columns['taller_instructor'] = 'instructor';
    $columns['taller_costo'] = 'costo';
    return $columns;
}
add_filter('manage_edit-taller_sortable_columns', 'ccct_talleres_columnas_ordenables');

/**
 * ========================================
 * WIDGET DASHBOARD ADMIN
 * ========================================
 */

/**
 * Dashboard widget: Resumen de talleres
 */
function ccct_dashboard_widget_talleres() {
    $recientes = new WP_Query(array(
        'post_type' => 'taller',
        'posts_per_page' => 5,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    $total_talleres = wp_count_posts('taller')->publish;
    
    echo '<div class="talleres-dashboard">';
    
    echo '<div class="taller-stats">';
    echo '<div class="stat-box">';
    echo '<span class="stat-number" style="font-size: 32px; font-weight: bold; color: #8e44ad;">' . $total_talleres . '</span>';
    echo '<span class="stat-label" style="color: #666; font-size: 14px;">Talleres Activos</span>';
    echo '</div>';
    echo '</div>';
    
    if ($recientes->have_posts()) {
        echo '<h4 style="margin: 20px 0 10px 0; color: #333;"><span class="dashicons dashicons-book" style="color: #8e44ad;"></span> Talleres Recientes</h4>';
        echo '<ul style="list-style: none; padding: 0; margin: 0;">';
        
        while ($recientes->have_posts()) {
            $recientes->the_post();
            $instructor = get_field('instructor');
            
            echo '<li style="padding: 8px 0; border-bottom: 1px solid #eee;">';
            echo '<a href="' . get_edit_post_link() . '" style="text-decoration: none; color: #8e44ad; font-weight: 500;">';
            echo get_the_title();
            echo '</a>';
            if ($instructor) {
                echo '<span style="color: #999; font-size: 12px; margin-left: 8px;">• ' . esc_html($instructor) . '</span>';
            }
            echo '</li>';
        }
        
        echo '</ul>';
        wp_reset_postdata();
    }
    
    echo '<p style="margin-top: 15px;">';
    echo '<a href="' . admin_url('edit.php?post_type=taller') . '" class="button button-primary">Ver Todos los Talleres</a> ';
    echo '<a href="' . admin_url('post-new.php?post_type=taller') . '" class="button">Nuevo Taller</a>';
    echo '</p>';
    
    echo '</div>';
}

function ccct_agregar_dashboard_widget_talleres() {
    wp_add_dashboard_widget(
        'ccct_talleres_resumen',
        'Talleres - Casa de la Cultura',
        'ccct_dashboard_widget_talleres'
    );
}
add_action('wp_dashboard_setup', 'ccct_agregar_dashboard_widget_talleres');

/**
 * ========================================
 * MENSAJES PERSONALIZADOS
 * ========================================
 */

/**
 * Personalizar mensajes de actualización
 */
function ccct_talleres_mensajes_personalizados($messages) {
    global $post;

    $messages['taller'] = array(
        0  => '',
        1  => 'Taller actualizado. <a href="' . esc_url(get_permalink($post->ID)) . '">Ver taller</a>',
        2  => 'Campo personalizado actualizado.',
        3  => 'Campo personalizado eliminado.',
        4  => 'Taller actualizado.',
        5  => isset($_GET['revision']) ? 'Taller restaurado a revisión de ' . wp_post_revision_title((int) $_GET['revision'], false) : false,
        6  => 'Taller publicado. <a href="' . esc_url(get_permalink($post->ID)) . '">Ver taller</a>',
        7  => 'Taller guardado.',
        8  => 'Taller enviado. <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">Vista previa del taller</a>',
        9  => sprintf('Taller programado para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Vista previa del taller</a>', date_i18n('M j, Y @ G:i', strtotime($post->post_date)), esc_url(get_permalink($post->ID))),
        10 => 'Borrador de taller actualizado. <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">Vista previa del taller</a>',
    );

    return $messages;
}
add_filter('post_updated_messages', 'ccct_talleres_mensajes_personalizados');

?>
