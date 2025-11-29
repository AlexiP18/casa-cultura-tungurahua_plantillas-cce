<?php
/**
 * ========================================
 * SISTEMA DE EVENTOS CULTURALES - ACTUALIZADO
 * Casa de la Cultura
 * ========================================
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registrar Custom Post Type: Evento
 */
function cc_registrar_post_type_evento() {
    $labels = array(
        'name'                  => 'Eventos Culturales',
        'singular_name'         => 'Evento',
        'menu_name'             => 'Eventos',
        'add_new'               => 'Agregar Nuevo',
        'add_new_item'          => 'Agregar Nuevo Evento',
        'edit_item'             => 'Editar Evento',
        'new_item'              => 'Nuevo Evento',
        'view_item'             => 'Ver Evento',
        'view_items'            => 'Ver Eventos',
        'search_items'          => 'Buscar Eventos',
        'not_found'             => 'No se encontraron eventos',
        'not_found_in_trash'    => 'No se encontraron eventos en la papelera',
        'all_items'             => 'Todos los Eventos',
        'archives'              => 'Archivo de Eventos',
        'attributes'            => 'Atributos de Evento',
        'insert_into_item'      => 'Insertar en evento',
        'uploaded_to_this_item' => 'Subido a este evento',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-tickets-alt',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'eventos'),
        'show_in_rest'        => true,
    );

    register_post_type('evento', $args);
}
add_action('init', 'cc_registrar_post_type_evento');

/**
 * Flush rewrite rules en activación
 */
function cc_eventos_flush_rewrites() {
    cc_registrar_post_type_evento();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cc_eventos_flush_rewrites');

/**
 * ========================================
 * FUNCIONES HELPER PARA EVENTOS
 * ========================================
 */

/**
 * Obtener estado del evento con estilo
 */
function cc_get_estado_evento($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $estado = get_field('evento_estado', $post_id);
    
    $estados = array(
        'proximo' => array(
            'label' => 'Próximamente',
            'color' => '#3498db',
            'icon' => '⏳'
        ),
        'inscripcion_abierta' => array(
            'label' => 'Inscripción Abierta',
            'color' => '#27ae60',
            'icon' => '✅'
        ),
        'cupos_limitados' => array(
            'label' => 'Cupos Limitados',
            'color' => '#f39c12',
            'icon' => '⚠️'
        ),
        'agotado' => array(
            'label' => 'Entradas Agotadas',
            'color' => '#e74c3c',
            'icon' => '🚫'
        ),
        'en_curso' => array(
            'label' => 'En Curso',
            'color' => '#9b59b6',
            'icon' => '▶️'
        ),
        'finalizado' => array(
            'label' => 'Finalizado',
            'color' => '#95a5a6',
            'icon' => '✓'
        ),
        'cancelado' => array(
            'label' => 'Cancelado',
            'color' => '#c0392b',
            'icon' => '✖️'
        )
    );
    
    return $estados[$estado] ?? $estados['proximo'];
}

/**
 * Calcular porcentaje de ocupación
 */
function cc_calcular_ocupacion($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $capacidad = get_field('evento_capacidad_total', $post_id);
    $disponibles = get_field('evento_cupos_disponibles', $post_id);
    
    if (!$capacidad || $capacidad == 0) {
        return null;
    }
    
    $ocupados = $capacidad - $disponibles;
    $porcentaje = ($ocupados / $capacidad) * 100;
    
    return array(
        'capacidad' => $capacidad,
        'disponibles' => $disponibles,
        'ocupados' => $ocupados,
        'porcentaje' => round($porcentaje, 1)
    );
}

/**
 * Verificar si el evento ya pasó
 */
function cc_evento_ha_pasado($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $fecha_inicio = get_field('evento_fecha_inicio', $post_id);
    
    if (!$fecha_inicio) {
        return false;
    }
    
    $ahora = current_time('timestamp');
    $fecha_evento = strtotime($fecha_inicio);
    
    return $fecha_evento < $ahora;
}

/**
 * Obtener fecha formateada en español
 */
function cc_get_fecha_evento_formateada($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $fecha_inicio = get_field('evento_fecha_inicio', $post_id);
    $fecha_fin = get_field('evento_fecha_fin', $post_id);
    
    if (!$fecha_inicio) {
        return '';
    }
    
    $meses = array(
        'January' => 'Enero',
        'February' => 'Febrero',
        'March' => 'Marzo',
        'April' => 'Abril',
        'May' => 'Mayo',
        'June' => 'Junio',
        'July' => 'Julio',
        'August' => 'Agosto',
        'September' => 'Septiembre',
        'October' => 'Octubre',
        'November' => 'Noviembre',
        'December' => 'Diciembre'
    );
    
    $dias = array(
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo'
    );
    
    $timestamp_inicio = strtotime($fecha_inicio);
    
    $dia_semana = date('l', $timestamp_inicio);
    $dia = date('j', $timestamp_inicio);
    $mes = date('F', $timestamp_inicio);
    $anio = date('Y', $timestamp_inicio);
    $hora = date('H:i', $timestamp_inicio);
    
    $fecha_formateada = $dias[$dia_semana] . ', ' . $dia . ' de ' . $meses[$mes] . ' de ' . $anio . ' - ' . $hora;
    
    if ($fecha_fin) {
        $timestamp_fin = strtotime($fecha_fin);
        $hora_fin = date('H:i', $timestamp_fin);
        
        // Si es el mismo día
        if (date('Y-m-d', $timestamp_inicio) === date('Y-m-d', $timestamp_fin)) {
            $fecha_formateada .= ' a ' . $hora_fin;
        } else {
            $dia_fin = date('j', $timestamp_fin);
            $mes_fin = date('F', $timestamp_fin);
            $anio_fin = date('Y', $timestamp_fin);
            $fecha_formateada .= ' hasta ' . $dia_fin . ' de ' . $meses[$mes_fin] . ' de ' . $anio_fin . ' - ' . $hora_fin;
        }
    }
    
    return $fecha_formateada;
}

/**
 * Obtener precio formateado - ACTUALIZADO
 */
function cc_get_precio_evento($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $es_gratuito = get_field('evento_es_gratuito', $post_id);
    
    if ($es_gratuito) {
        return array(
            'gratuito' => true,
            'texto' => 'Entrada Gratuita',
            'icono' => '🎁'
        );
    }
    
    $precio = get_field('evento_precio', $post_id);
    $precios_multiples = get_field('evento_precios_multiples', $post_id);
    
    if ($precios_multiples) {
        return array(
            'gratuito' => false,
            'multiple' => true,
            'texto' => 'Desde $' . $precio,
            'detalles' => $precios_multiples
        );
    }
    
    return array(
        'gratuito' => false,
        'multiple' => false,
        'texto' => '$' . $precio,
        'valor' => $precio
    );
}

/**
 * Obtener slider de imágenes - ACTUALIZADO (solo imágenes 4 y 5)
 */
function cc_get_slider_evento($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $imagenes = array();
    
    // Imagen principal primero
    $imagen_principal = get_field('evento_imagen_principal', $post_id);
    if ($imagen_principal && is_array($imagen_principal)) {
        $imagenes[] = $imagen_principal;
    }
    
    // Banner si existe
    $imagen_banner = get_field('evento_imagen_banner', $post_id);
    if ($imagen_banner && is_array($imagen_banner)) {
        $imagenes[] = $imagen_banner;
    }
    
    // Imágenes adicionales 4 y 5
    for ($i = 4; $i <= 5; $i++) {
        $imagen = get_field('evento_imagen_' . $i, $post_id);
        if ($imagen && is_array($imagen)) {
            $imagenes[] = $imagen;
        }
    }
    
    return $imagenes;
}

/**
 * Obtener requisitos formateados - ACTUALIZADO
 */
function cc_get_requisitos_evento($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $requisitos = get_field('evento_requisitos', $post_id);
    
    if (!$requisitos || !is_array($requisitos)) {
        return null;
    }
    
    $labels = array(
        'cedula' => 'Cédula de identidad',
        'amabilidad' => 'Amabilidad',
        'puntualidad' => 'Puntualidad'
    );
    
    $lista = array();
    foreach ($requisitos as $req) {
        $lista[] = $labels[$req] ?? $req;
    }
    
    return $lista;
}

/**
 * Obtener lo que incluye el evento - ACTUALIZADO
 */
function cc_get_incluye_evento($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $incluye = get_field('evento_incluye', $post_id);
    
    if (!$incluye || !is_array($incluye)) {
        return null;
    }
    
    $labels = array(
        'refrigerio' => 'Refrigerio',
        'material_didactico' => 'Material didáctico',
        'certificado' => 'Certificado de asistencia'
    );
    
    $lista = array();
    foreach ($incluye as $item) {
        $lista[] = $labels[$item] ?? $item;
    }
    
    return $lista;
}

/**
 * ========================================
 * QUERIES Y LISTADOS
 * ========================================
 */

/**
 * Obtener eventos próximos
 */
function cc_get_eventos_proximos($limit = 6) {
    $ahora = current_time('Y-m-d H:i:s');
    
    $args = array(
        'post_type' => 'evento',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'evento_fecha_inicio',
                'value' => $ahora,
                'compare' => '>=',
                'type' => 'DATETIME'
            )
        ),
        'meta_key' => 'evento_fecha_inicio',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    );
    
    return new WP_Query($args);
}

/**
 * Obtener eventos destacados
 */
function cc_get_eventos_destacados($limit = 3) {
    $ahora = current_time('Y-m-d H:i:s');
    
    $args = array(
        'post_type' => 'evento',
        'posts_per_page' => $limit,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'evento_destacado',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => 'evento_fecha_inicio',
                'value' => $ahora,
                'compare' => '>=',
                'type' => 'DATETIME'
            )
        ),
        'meta_key' => 'evento_fecha_inicio',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    );
    
    return new WP_Query($args);
}

/**
 * Obtener eventos por tipo
 */
function cc_get_eventos_por_tipo($tipo, $limit = 10) {
    $ahora = current_time('Y-m-d H:i:s');
    
    $args = array(
        'post_type' => 'evento',
        'posts_per_page' => $limit,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'evento_tipo',
                'value' => $tipo,
                'compare' => '='
            ),
            array(
                'key' => 'evento_fecha_inicio',
                'value' => $ahora,
                'compare' => '>=',
                'type' => 'DATETIME'
            )
        ),
        'meta_key' => 'evento_fecha_inicio',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    );
    
    return new WP_Query($args);
}

/**
 * Obtener eventos pasados
 */
function cc_get_eventos_pasados($limit = 10) {
    $ahora = current_time('Y-m-d H:i:s');
    
    $args = array(
        'post_type' => 'evento',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'evento_fecha_inicio',
                'value' => $ahora,
                'compare' => '<',
                'type' => 'DATETIME'
            )
        ),
        'meta_key' => 'evento_fecha_inicio',
        'orderby' => 'meta_value',
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
 * Shortcode: Eventos destacados
 * Uso: [eventos_destacados limit="3"]
 */
function cc_shortcode_eventos_destacados($atts) {
    $atts = shortcode_atts(array(
        'limit' => 3,
    ), $atts);
    
    $query = cc_get_eventos_destacados($atts['limit']);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="eventos-destacados-slider">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('evento_imagen_principal');
            $fecha = cc_get_fecha_evento_formateada();
            $precio = cc_get_precio_evento();
            $estado = cc_get_estado_evento();
            
            echo '<div class="evento-destacado-item">';
            
            if ($imagen) {
                echo '<div class="evento-destacado-image">';
                echo '<img src="' . esc_url($imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '">';
                echo '<div class="evento-overlay"></div>';
                echo '</div>';
            }
            
            echo '<div class="evento-destacado-content">';
            echo '<span class="evento-estado" style="background: ' . $estado['color'] . ';">';
            echo $estado['icon'] . ' ' . $estado['label'];
            echo '</span>';
            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            echo '<p class="evento-fecha">📅 ' . $fecha . '</p>';
            echo '<p class="evento-precio">' . $precio['texto'] . '</p>';
            echo '<a href="' . get_permalink() . '" class="btn-ver-evento">Ver Detalles</a>';
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    }
    
    return ob_get_clean();
}
add_shortcode('eventos_destacados', 'cc_shortcode_eventos_destacados');

/**
 * Shortcode: Próximos eventos
 * Uso: [proximos_eventos limit="6"]
 */
function cc_shortcode_proximos_eventos($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
    ), $atts);
    
    $query = cc_get_eventos_proximos($atts['limit']);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="proximos-eventos-grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('evento_imagen_principal');
            $tipo = get_field('evento_tipo');
            $fecha = get_field('evento_fecha_inicio');
            $precio = cc_get_precio_evento();
            
            echo '<article class="evento-card-mini">';
            
            if ($imagen) {
                echo '<div class="evento-card-image">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($imagen['sizes']['medium'] ?? $imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '">';
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="evento-card-content">';
            echo '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
            echo '<p class="evento-fecha-mini">📅 ' . date('j M, H:i', strtotime($fecha)) . '</p>';
            echo '<p class="evento-precio-mini">' . $precio['texto'] . '</p>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="no-eventos">No hay eventos próximos programados.</p>';
    }
    
    return ob_get_clean();
}
add_shortcode('proximos_eventos', 'cc_shortcode_proximos_eventos');

/**
 * ========================================
 * WIDGET DASHBOARD ADMIN
 * ========================================
 */

/**
 * Dashboard widget: Resumen de eventos
 */
function cc_dashboard_widget_eventos() {
    $proximos = cc_get_eventos_proximos(5);
    $total_proximos = $proximos->found_posts;
    
    echo '<div class="eventos-dashboard">';
    
    echo '<div class="evento-stats">';
    echo '<div class="stat-box">';
    echo '<span class="stat-number">' . $total_proximos . '</span>';
    echo '<span class="stat-label">Eventos Próximos</span>';
    echo '</div>';
    echo '</div>';
    
    if ($proximos->have_posts()) {
        echo '<h4 style="margin-top: 20px; margin-bottom: 10px;">📅 Próximos Eventos</h4>';
        echo '<ul style="list-style: none; padding: 0; margin: 0;">';
        
        while ($proximos->have_posts()) {
            $proximos->the_post();
            $fecha = get_field('evento_fecha_inicio');
            $estado = cc_get_estado_evento();
            
            echo '<li style="padding: 10px 0; border-bottom: 1px solid #f0f0f1;">';
            echo '<strong><a href="' . get_edit_post_link() . '">' . get_the_title() . '</a></strong>';
            echo '<br><span style="font-size: 12px; color: #666;">📅 ' . date('j M Y, H:i', strtotime($fecha)) . '</span>';
            echo '<br><span style="display: inline-block; padding: 3px 8px; background: ' . $estado['color'] . '; color: #fff; border-radius: 10px; font-size: 11px; margin-top: 5px;">';
            echo $estado['icon'] . ' ' . $estado['label'];
            echo '</span>';
            echo '</li>';
        }
        
        echo '</ul>';
        wp_reset_postdata();
    }
    
    echo '<p style="margin-top: 15px;">';
    echo '<a href="' . admin_url('edit.php?post_type=evento') . '" class="button button-primary">Ver Todos los Eventos</a>';
    echo '</p>';
    
    echo '</div>';
}

function cc_agregar_dashboard_widget_eventos() {
    wp_add_dashboard_widget(
        'cc_eventos_dashboard',
        '🎭 Eventos Culturales - Casa de la Cultura',
        'cc_dashboard_widget_eventos'
    );
}
add_action('wp_dashboard_setup', 'cc_agregar_dashboard_widget_eventos');

/**
 * ========================================
 * ENQUEUE STYLES & SCRIPTS
 * ========================================
 */

/**
 * Cargar estilos y scripts para eventos
 */
function cc_enqueue_eventos_assets() {
    if (is_singular('evento') || is_post_type_archive('evento')) {
        
        // CSS
        wp_enqueue_style(
            'cc-eventos-styles',
            get_template_directory_uri() . '/plantillas/agenda/evento/eventos-styles.css',
            array(),
            '1.0.1'
        );
        
        // JavaScript
        wp_enqueue_script(
            'cc-eventos-scripts',
            get_template_directory_uri() . '/plantillas/agenda/evento/eventos-scripts.js',
            array('jquery'),
            '1.0.1',
            true
        );
        
        // Pasar datos PHP a JavaScript
        wp_localize_script('cc-eventos-scripts', 'eventosData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('eventos_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'cc_enqueue_eventos_assets');

/**
 * ========================================
 * COLUMNAS PERSONALIZADAS EN ADMIN
 * ========================================
 */

/**
 * Agregar columnas personalizadas
 */
function cc_eventos_columnas_admin($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'title') {
            $new_columns['evento_fecha'] = '📅 Fecha';
            $new_columns['evento_tipo'] = 'Tipo';
            $new_columns['evento_estado'] = 'Estado';
            $new_columns['evento_cupos'] = 'Cupos';
        }
    }
    
    return $new_columns;
}
add_filter('manage_evento_posts_columns', 'cc_eventos_columnas_admin');

/**
 * Rellenar columnas personalizadas
 */
function cc_eventos_columnas_contenido($column, $post_id) {
    switch ($column) {
        case 'evento_fecha':
            $fecha = get_field('evento_fecha_inicio', $post_id);
            if ($fecha) {
                echo date('j M Y, H:i', strtotime($fecha));
                
                if (cc_evento_ha_pasado($post_id)) {
                    echo '<br><span style="color: #999;">✓ Finalizado</span>';
                }
            } else {
                echo '—';
            }
            break;
            
        case 'evento_tipo':
            $tipo = get_field('evento_tipo', $post_id);
            $tipos = array(
                'teatro' => 'Teatro',
                'musica' => 'Música',
                'danza' => 'Danza',
                'exposicion' => 'Exposición',
                'taller' => 'Taller',
                'conferencia' => 'Conferencia',
                'conversatorio' => 'Conversatorio',
                'cine' => 'Cine',
                'literario' => 'Literario',
                'concurso' => 'Concurso',
                'festival' => 'Festival',
                'otro' => 'Otro'
            );
            echo $tipos[$tipo] ?? $tipo;
            break;
            
        case 'evento_estado':
            $estado = cc_get_estado_evento($post_id);
            echo '<span style="display: inline-block; padding: 5px 10px; background: ' . $estado['color'] . '; color: #fff; border-radius: 12px; font-size: 11px; font-weight: 600;">';
            echo $estado['icon'] . ' ' . $estado['label'];
            echo '</span>';
            break;
            
        case 'evento_cupos':
            $ocupacion = cc_calcular_ocupacion($post_id);
            if ($ocupacion) {
                echo '<strong>' . $ocupacion['disponibles'] . '</strong> / ' . $ocupacion['capacidad'];
                echo '<br><small>' . $ocupacion['porcentaje'] . '% ocupado</small>';
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_evento_posts_custom_column', 'cc_eventos_columnas_contenido', 10, 2);

/**
 * Hacer columnas ordenables
 */
function cc_eventos_columnas_ordenables($columns) {
    $columns['evento_fecha'] = 'evento_fecha_inicio';
    $columns['evento_tipo'] = 'evento_tipo';
    return $columns;
}
add_filter('manage_edit-evento_sortable_columns', 'cc_eventos_columnas_ordenables');

/**
 * ========================================
 * MENSAJES PERSONALIZADOS
 * ========================================
 */

/**
 * Personalizar mensajes de actualización
 */
function cc_eventos_mensajes_personalizados($messages) {
    global $post;

    $messages['evento'] = array(
        0  => '',
        1  => 'Evento actualizado. <a href="' . esc_url(get_permalink($post->ID)) . '">Ver evento</a>',
        2  => 'Campo personalizado actualizado.',
        3  => 'Campo personalizado eliminado.',
        4  => 'Evento actualizado.',
        5  => isset($_GET['revision']) ? 'Evento restaurado a revisión de ' . wp_post_revision_title((int) $_GET['revision'], false) : false,
        6  => 'Evento publicado. <a href="' . esc_url(get_permalink($post->ID)) . '">Ver evento</a>',
        7  => 'Evento guardado.',
        8  => 'Evento enviado. <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">Vista previa del evento</a>',
        9  => sprintf('Evento programado para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Vista previa del evento</a>', date_i18n('M j, Y @ G:i', strtotime($post->post_date)), esc_url(get_permalink($post->ID))),
        10 => 'Borrador de evento actualizado. <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">Vista previa del evento</a>',
    );

    return $messages;
}
add_filter('post_updated_messages', 'cc_eventos_mensajes_personalizados');
