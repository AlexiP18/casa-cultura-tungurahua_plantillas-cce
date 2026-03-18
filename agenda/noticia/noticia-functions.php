<?php
/**
 * NOTICIAS
 * Funciones para el sistema de Noticias
 * Casa de la Cultura - WordPress
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ==============================================
 * REGISTRO DE POST TYPE PERSONALIZADO: NOTICIA
 * ==============================================
 */
function cc_registrar_post_type_noticia() {
    $labels = array(
        'name'                  => 'Noticias',
        'singular_name'         => 'Noticia',
        'menu_name'             => 'Noticias',
        'add_new'               => 'Agregar Nueva',
        'add_new_item'          => 'Agregar Nueva Noticia',
        'edit_item'             => 'Editar Noticia',
        'new_item'              => 'Nueva Noticia',
        'view_item'             => 'Ver Noticia',
        'view_items'            => 'Ver Noticias',
        'search_items'          => 'Buscar Noticias',
        'not_found'             => 'No se encontraron noticias',
        'not_found_in_trash'    => 'No se encontraron noticias en la papelera',
        'all_items'             => 'Todas las Noticias',
        'archives'              => 'Archivo de Noticias',
        'attributes'            => 'Atributos de Noticia',
        'insert_into_item'      => 'Insertar en noticia',
        'uploaded_to_this_item' => 'Subido a esta noticia',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-megaphone',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'noticias'),
        'show_in_rest'        => true,
        'rest_base'           => 'noticias',
    );

    register_post_type('noticia', $args);
}
add_action('init', 'cc_registrar_post_type_noticia');

/**
 * ==============================================
 * FUNCIONES HELPER PARA GALERÍA
 * ==============================================
 */

/**
 * Calcular tiempo estimado de lectura
 */
function cc_tiempo_lectura($contenido) {
    // Palabras por minuto promedio de lectura
    $palabras_por_minuto = 200;
    
    // Limpiar HTML y contar palabras
    $texto_limpio = wp_strip_all_tags($contenido);
    $num_palabras = str_word_count($texto_limpio);
    
    // Calcular minutos
    $minutos = ceil($num_palabras / $palabras_por_minuto);
    
    // Mínimo 1 minuto
    return max(1, $minutos);
}

/**
 * Obtener galería de imágenes
 */
function cc_get_galeria_imagenes($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $imagenes = array();
    
    // Recopilar imágenes individuales (imagen_2 hasta imagen_5)
    for ($i = 2; $i <= 5; $i++) {
        $imagen = get_field('noticia_imagen_' . $i, $post_id);
        if ($imagen && is_array($imagen)) {
            $imagenes[] = $imagen;
        }
    }
    
    return $imagenes;
}

/**
 * Mostrar galería de imágenes
 */
function cc_mostrar_galeria_noticia($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $imagenes = cc_get_galeria_imagenes($post_id);
    
    if (!empty($imagenes)) {
        echo '<div class="noticia-galeria-section">';
        echo '<h3>🖼️ Galería de Imágenes</h3>';
        echo '<div class="noticia-galeria-grid">';
        
        foreach ($imagenes as $index => $imagen) {
            echo '<div class="galeria-item">';
            echo '<a href="' . esc_url($imagen['url']) . '" data-lightbox="galeria-' . $post_id . '" data-title="' . esc_attr($imagen['caption']) . '">';
            echo '<img src="' . esc_url($imagen['sizes']['medium'] ?? $imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '" loading="lazy">';
            echo '</a>';
            if (!empty($imagen['caption'])) {
                echo '<p class="galeria-caption">' . esc_html($imagen['caption']) . '</p>';
            }
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}

/**
 * ==============================================
 * FUNCIONES HELPER PARA ARCHIVOS ADJUNTOS
 * ==============================================
 */

/**
 * Obtener archivos adjuntos
 */
function cc_get_archivos_adjuntos($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $archivos = array();
    
    // Recopilar hasta 3 archivos
    for ($i = 1; $i <= 3; $i++) {
        $archivo = get_field('noticia_archivo_' . $i, $post_id);
        $titulo = get_field('noticia_archivo_' . $i . '_titulo', $post_id);
        
        if ($archivo && is_array($archivo)) {
            $archivos[] = array(
                'archivo' => $archivo,
                'titulo' => $titulo ? $titulo : 'Archivo ' . $i
            );
        }
    }
    
    return $archivos;
}

/**
 * Mostrar archivos adjuntos
 */
function cc_mostrar_archivos_adjuntos($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $archivos = cc_get_archivos_adjuntos($post_id);
    
    if (!empty($archivos)) {
        echo '<div class="noticia-archivos-adjuntos">';
        echo '<h3>📎 Archivos Descargables</h3>';
        echo '<ul class="lista-archivos">';
        
        foreach ($archivos as $item) {
            $archivo = $item['archivo'];
            $titulo = $item['titulo'];
            
            echo '<li class="archivo-item">';
            echo '<a href="' . esc_url($archivo['url']) . '" target="_blank" class="archivo-link" download>';
            
            // Icono según extensión
            $extension = pathinfo($archivo['filename'], PATHINFO_EXTENSION);
            $icono = '📎';
            $tipo_archivo = 'Archivo';
            
            switch (strtolower($extension)) {
                case 'pdf':
                    $icono = '📄';
                    $tipo_archivo = 'PDF';
                    break;
                case 'doc':
                case 'docx':
                    $icono = '📝';
                    $tipo_archivo = 'Documento Word';
                    break;
                case 'xls':
                case 'xlsx':
                    $icono = '📊';
                    $tipo_archivo = 'Excel';
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                    $icono = '🖼️';
                    $tipo_archivo = 'Imagen';
                    break;
            }
            
            echo '<span class="archivo-icono" aria-label="' . esc_attr($tipo_archivo) . '">' . $icono . '</span>';
            echo '<span class="archivo-info">';
            echo '<strong class="archivo-titulo">' . esc_html($titulo) . '</strong>';
            echo '<span class="archivo-detalles">';
            echo '<span class="archivo-tipo">' . esc_html(strtoupper($extension)) . '</span>';
            echo '<span class="archivo-separador">•</span>';
            echo '<span class="archivo-size">' . size_format($archivo['filesize']) . '</span>';
            echo '</span>';
            echo '</span>';
            
            echo '<svg class="archivo-download-icon" width="20" height="20" viewBox="0 0 16 16" fill="currentColor">';
            echo '<path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>';
            echo '<path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>';
            echo '</svg>';
            
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * ==============================================
 * FUNCIONES HELPER
 * ==============================================
 */

/**
 * Obtener las etiquetas de categorías de noticias
 */
function cc_get_categorias_noticias_labels() {
    return array(
        'eventos' => 'Eventos',
        'talleres' => 'Talleres y Cursos',
        'exposiciones' => 'Exposiciones',
        'actividades' => 'Actividades Culturales',
        'comunicados' => 'Comunicados Oficiales',
        'convocatorias' => 'Convocatorias',
        'galeria' => 'Galería de Fotos',
        'premios' => 'Premios y Reconocimientos',
        'general' => 'General',
    );
}

/**
 * ==============================================
 * FUNCIONES DE CONSULTA
 * ==============================================
 */

/**
 * Obtener noticias destacadas
 */
function cc_get_noticias_destacadas($limit = 3) {
    $args = array(
        'post_type' => 'noticia',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'noticia_destacada',
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
 * Obtener noticias urgentes
 */
function cc_get_noticias_urgentes($limit = -1) {
    $args = array(
        'post_type' => 'noticia',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'noticia_urgente',
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
 * Obtener noticias por categoría
 */
function cc_get_noticias_por_categoria($categoria, $limit = 10) {
    $args = array(
        'post_type' => 'noticia',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'noticia_categoria',
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
 * Obtener noticias relacionadas
 */
function cc_get_noticias_relacionadas($post_id = null, $limit = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categoria = get_field('noticia_categoria', $post_id);
    
    $args = array(
        'post_type' => 'noticia',
        'posts_per_page' => $limit,
        'post__not_in' => array($post_id),
        'meta_query' => array(
            array(
                'key' => 'noticia_categoria',
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
 * ==============================================
 * SHORTCODES
 * ==============================================
 */

/**
 * Shortcode para noticias destacadas
 * Uso: [noticias_destacadas limit="3"]
 */
function cc_shortcode_noticias_destacadas($atts) {
    $atts = shortcode_atts(array(
        'limit' => 3,
    ), $atts);
    
    $query = cc_get_noticias_destacadas($atts['limit']);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="noticias-destacadas-grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('noticia_imagen_principal');
            $categoria = get_field('noticia_categoria');
            $resumen = get_field('noticia_resumen');
            $urgente = get_field('noticia_urgente');
            
            $clase_urgente = $urgente ? ' noticia-urgente' : '';
            
            echo '<article class="noticia-destacada' . $clase_urgente . '">';
            
            if ($imagen) {
                echo '<div class="noticia-imagen-container">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($imagen['sizes']['medium'] ?? $imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '" loading="lazy">';
                echo '</a>';
                if ($categoria) {
                    $badge_class = $urgente ? ' badge-urgente' : '';
                    echo '<span class="noticia-badge' . $badge_class . '">' . esc_html($categoria) . '</span>';
                }
                echo '</div>';
            }
            
            echo '<div class="noticia-contenido">';
            echo '<h3 class="noticia-titulo"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            
            if ($resumen) {
                echo '<p class="noticia-resumen">' . esc_html($resumen) . '</p>';
            }
            
            echo '<div class="noticia-meta">';
            echo '<span class="noticia-fecha">';
            echo '<svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>';
            echo ' ' . get_the_date('j M, Y');
            echo '</span>';
            echo '<a href="' . get_permalink() . '" class="noticia-leer-mas">Leer más →</a>';
            echo '</div>';
            
            echo '</div>';
            echo '</article>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="no-noticias-mensaje">No hay noticias destacadas en este momento.</p>';
    }
    
    return ob_get_clean();
}
add_shortcode('noticias_destacadas', 'cc_shortcode_noticias_destacadas');

/**
 * Shortcode para noticias por categoría
 * Uso: [noticias_categoria categoria="eventos" limit="5"]
 */
function cc_shortcode_noticias_categoria($atts) {
    $atts = shortcode_atts(array(
        'categoria' => 'general',
        'limit' => 5,
    ), $atts);
    
    $query = cc_get_noticias_por_categoria($atts['categoria'], $atts['limit']);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="noticias-lista-categoria">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $imagen = get_field('noticia_imagen_principal');
            $resumen = get_field('noticia_resumen');
            
            echo '<article class="noticia-item-categoria">';
            
            if ($imagen) {
                echo '<div class="noticia-thumb">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($imagen['sizes']['thumbnail'] ?? $imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '" loading="lazy">';
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="noticia-info">';
            echo '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
            
            if ($resumen) {
                echo '<p>' . esc_html(wp_trim_words($resumen, 15)) . '</p>';
            }
            
            echo '<span class="noticia-fecha">';
            echo '<svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>';
            echo ' ' . get_the_date('j M, Y');
            echo '</span>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="no-noticias-mensaje">No hay noticias en esta categoría.</p>';
    }
    
    return ob_get_clean();
}
add_shortcode('noticias_categoria', 'cc_shortcode_noticias_categoria');

/**
 * Shortcode para mostrar todas las noticias en grid
 * Uso: [mostrar_noticias cantidad="9" orden="date" direccion="DESC" categoria="" urgente="" destacada=""]
 */
function cc_shortcode_mostrar_noticias($atts) {
    $atts = shortcode_atts(array(
        'cantidad' => 9,
        'orden' => 'date',
        'direccion' => 'DESC',
        'categoria' => '',
        'urgente' => '',      // "si" para solo urgentes, "no" para excluir urgentes
        'destacada' => ''     // "si" para solo destacadas, "no" para excluir destacadas
    ), $atts);
    
    // Asegurar estilos cargados
    wp_enqueue_style('cc-noticias-styles', get_template_directory_uri() . '/plantillas/agenda/noticia/noticias-styles.css');
    
    $args = array(
        'post_type' => 'noticia',
        'posts_per_page' => intval($atts['cantidad']),
        'orderby' => $atts['orden'],
        'order' => $atts['direccion']
    );
    
    // Construir meta_query
    $meta_query = array();
    
    // Filtro por categoría
    if (!empty($atts['categoria'])) {
        $meta_query[] = array(
            'key' => 'noticia_categoria',
            'value' => $atts['categoria'],
            'compare' => '='
        );
    }
    
    // Filtro por urgente
    if ($atts['urgente'] === 'si') {
        $meta_query[] = array(
            'key' => 'noticia_urgente',
            'value' => '1',
            'compare' => '='
        );
    } elseif ($atts['urgente'] === 'no') {
        $meta_query[] = array(
            'relation' => 'OR',
            array(
                'key' => 'noticia_urgente',
                'value' => '0',
                'compare' => '='
            ),
            array(
                'key' => 'noticia_urgente',
                'compare' => 'NOT EXISTS'
            )
        );
    }
    
    // Filtro por destacada
    if ($atts['destacada'] === 'si') {
        $meta_query[] = array(
            'key' => 'noticia_destacada',
            'value' => '1',
            'compare' => '='
        );
    } elseif ($atts['destacada'] === 'no') {
        $meta_query[] = array(
            'relation' => 'OR',
            array(
                'key' => 'noticia_destacada',
                'value' => '0',
                'compare' => '='
            ),
            array(
                'key' => 'noticia_destacada',
                'compare' => 'NOT EXISTS'
            )
        );
    }
    
    // Aplicar meta_query si hay filtros
    if (!empty($meta_query)) {
        if (count($meta_query) > 1) {
            $meta_query['relation'] = 'AND';
        }
        $args['meta_query'] = $meta_query;
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) : ?>
        <div class="noticias-archive-container">
            <div class="noticias-grid">
                <?php while ($query->have_posts()) : $query->the_post();
                    $imagen = get_field('noticia_imagen_principal');
                    $categoria = get_field('noticia_categoria');
                    $resumen = get_field('noticia_resumen');
                    $urgente = get_field('noticia_urgente');
                    $destacada = get_field('noticia_destacada');
                    
                    $categorias_labels = array(
                        'general' => 'General',
                        'eventos' => 'Eventos',
                        'talleres' => 'Talleres',
                        'convenios' => 'Convenios',
                        'rendicion' => 'Rendicion de Cuentas'
                    );
                    
                    $clase_especial = '';
                    if ($urgente) $clase_especial .= ' noticia-urgente';
                    if ($destacada) $clase_especial .= ' noticia-destacada';
                ?>
                    <article class="noticia-card<?php echo esc_attr($clase_especial); ?>">
                        <?php if ($imagen) : ?>
                            <div class="noticia-card-image">
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo esc_url($imagen['sizes']['medium_large'] ?? $imagen['url']); ?>" alt="<?php echo esc_attr($imagen['alt']); ?>" loading="lazy">
                                </a>
                                <?php if ($urgente) : ?>
                                    <span class="noticia-badge urgente">Urgente</span>
                                <?php elseif ($destacada) : ?>
                                    <span class="noticia-badge destacada">Destacada</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="noticia-card-content">
                            <span class="noticia-categoria"><?php echo esc_html($categorias_labels[$categoria] ?? 'General'); ?></span>
                            
                            <h3 class="noticia-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <?php if ($resumen) : ?>
                                <p class="noticia-card-resumen"><?php echo esc_html(wp_trim_words($resumen, 20)); ?></p>
                            <?php endif; ?>
                            
                            <div class="noticia-card-meta">
                                <span class="noticia-fecha">
                                    <i class="far fa-calendar-alt"></i> <?php echo get_the_date('j M, Y'); ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="noticia-leer-mas">Leer mas <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="noticias-empty">
            <p>No hay noticias disponibles en este momento.</p>
        </div>
    <?php endif;
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('mostrar_noticias', 'cc_shortcode_mostrar_noticias');

/**
 * Shortcode para carrusel de noticias destacadas/urgentes
 * Uso: [noticias_carousel cantidad="-1" titulo="Noticias Importantes"]
 */
function cc_shortcode_noticias_carousel($atts) {
    $atts = shortcode_atts(array(
        'cantidad' => -1,
        'titulo'   => 'Noticias Importantes',
    ), $atts);

    // Encolar estilos del carrusel
    wp_enqueue_style(
        'cc-noticias-carousel-styles',
        get_template_directory_uri() . '/plantillas/agenda/noticia/noticias-carousel-styles.css',
        array(),
        '1.0.0'
    );

    // Query: noticias que son urgentes O destacadas
    $args = array(
        'post_type'      => 'noticia',
        'posts_per_page' => intval($atts['cantidad']),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => 'noticia_urgente',
                'value'   => '1',
                'compare' => '='
            ),
            array(
                'key'     => 'noticia_destacada',
                'value'   => '1',
                'compare' => '='
            ),
        ),
    );

    $query = new WP_Query($args);

    // Reordenar manual: Urgente > Destacada > Fecha
    if ($query->have_posts()) {
        $posts = $query->posts;
        usort($posts, function ($a, $b) {
            $a_urgente = get_field('noticia_urgente', $a->ID);
            $b_urgente = get_field('noticia_urgente', $b->ID);

            if ($a_urgente && !$b_urgente) return -1;
            if (!$a_urgente && $b_urgente) return 1;

            $a_destacada = get_field('noticia_destacada', $a->ID);
            $b_destacada = get_field('noticia_destacada', $b->ID);

            if ($a_destacada && !$b_destacada) return -1;
            if (!$a_destacada && $b_destacada) return 1;

            return strtotime($b->post_date) - strtotime($a->post_date);
        });
        $query->posts = $posts;
    }

    $carousel_id = 'nc-' . wp_rand(1000, 9999);

    $categorias_data = array(
        'eventos' => array('label' => 'Eventos', 'icon' => 'fa-calendar-alt'),
        'talleres' => array('label' => 'Talleres y Cursos', 'icon' => 'fa-chalkboard-teacher'),
        'exposiciones' => array('label' => 'Exposiciones', 'icon' => 'fa-image'),
        'actividades' => array('label' => 'Actividades Culturales', 'icon' => 'fa-music'),
        'comunicados' => array('label' => 'Comunicados Oficiales', 'icon' => 'fa-bullhorn'),
        'convocatorias' => array('label' => 'Convocatorias', 'icon' => 'fa-clipboard-list'),
        'galeria' => array('label' => 'Galería de Fotos', 'icon' => 'fa-images'),
        'premios' => array('label' => 'Premios y Reconocimientos', 'icon' => 'fa-trophy'),
        'general' => array('label' => 'General', 'icon' => 'fa-newspaper'),
        'convenios' => array('label' => 'Convenios', 'icon' => 'fa-handshake'),
        'rendicion' => array('label' => 'Rendición de Cuentas', 'icon' => 'fa-file-invoice'),
    );

    ob_start();
    ?>
    <div class="noticias-carousel-wrapper" id="<?php echo esc_attr($carousel_id); ?>">
        <!-- Header: Título + Filtros -->
        <div class="carousel-header">
            <h2>
                <i class="fas fa-newspaper"></i>
                <?php echo esc_html($atts['titulo']); ?>
            </h2>
            <div class="carousel-filtros">
                <button class="carousel-filtro-btn active" data-filter="todas">
                    <i class="fas fa-layer-group"></i> Todas
                </button>
                <button class="carousel-filtro-btn" data-filter="destacada">
                    <i class="fas fa-star"></i> Destacadas
                </button>
                <button class="carousel-filtro-btn" data-filter="urgente">
                    <i class="fas fa-exclamation-circle"></i> Urgentes
                </button>
            </div>
        </div>

        <?php if ($query->have_posts()) : ?>
            <!-- Carrusel -->
            <div class="carousel-container">
                <button class="carousel-nav prev" aria-label="Anterior">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <div class="carousel-track">
                    <?php while ($query->have_posts()) : $query->the_post();
                        $imagen    = get_field('noticia_imagen_principal');
                        $categoria = get_field('noticia_categoria');
                        $resumen   = get_field('noticia_resumen');
                        $urgente   = get_field('noticia_urgente');
                        $destacada = get_field('noticia_destacada');

                        // Determinar tipo para el filtro
                        $tipo = '';
                        if ($urgente && $destacada) {
                            $tipo = 'urgente destacada';
                        } elseif ($urgente) {
                            $tipo = 'urgente';
                        } elseif ($destacada) {
                            $tipo = 'destacada';
                        }
                        
                        // Datos de categoria
                        $cat_info = isset($categorias_data[$categoria]) ? $categorias_data[$categoria] : $categorias_data['general'];
                    ?>
                        <article class="carousel-card" data-tipo="<?php echo esc_attr($tipo); ?>">
                            <?php if ($imagen) : ?>
                                <div class="carousel-card-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url($imagen['sizes']['medium_large'] ?? $imagen['url']); ?>"
                                             alt="<?php echo esc_attr($imagen['alt']); ?>"
                                             loading="lazy">
                                    </a>
                                    <?php if ($urgente) : ?>
                                        <span class="carousel-badge urgente">
                                            <i class="fas fa-exclamation-circle"></i> Urgente
                                        </span>
                                    <?php elseif ($destacada) : ?>
                                        <span class="carousel-badge destacada">
                                            <i class="fas fa-star"></i> Destacada
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="carousel-card-content">
                                <span class="carousel-card-categoria categoria-<?php echo esc_attr($categoria); ?>">
                                    <i class="fas <?php echo esc_attr($cat_info['icon']); ?>"></i>
                                    <?php echo esc_html($cat_info['label']); ?>
                                </span>

                                <h3 class="carousel-card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <?php if ($resumen) : ?>
                                    <p class="carousel-card-resumen">
                                        <?php echo esc_html(wp_trim_words($resumen, 18)); ?>
                                    </p>
                                <?php endif; ?>

                                <div class="carousel-card-meta">
                                    <span class="carousel-card-fecha">
                                        <i class="far fa-calendar-alt"></i>
                                        <?php echo get_the_date('j M, Y'); ?>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="carousel-card-leer-mas">
                                        Leer más <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <button class="carousel-nav next" aria-label="Siguiente">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        <?php else : ?>
            <div class="carousel-empty">
                <i class="fas fa-newspaper"></i>
                <p>No hay noticias destacadas o urgentes en este momento.</p>
            </div>
        <?php endif;
        wp_reset_postdata();
        ?>
    </div>

    <script>
    (function() {
        var wrapper = document.getElementById('<?php echo esc_js($carousel_id); ?>');
        if (!wrapper) return;

        var track     = wrapper.querySelector('.carousel-track');
        var cards     = wrapper.querySelectorAll('.carousel-card');
        var prevBtn   = wrapper.querySelector('.carousel-nav.prev');
        var nextBtn   = wrapper.querySelector('.carousel-nav.next');
        var filterBtns = wrapper.querySelectorAll('.carousel-filtro-btn');

        if (!track || cards.length === 0) return;

        var scrollPos = 0;

        function getScrollAmount() {
            // Scroll by one card width + gap
            var card = track.querySelector('.carousel-card:not([style*="display: none"])');
            if (!card) return 300;
            return card.offsetWidth + 20; // 20 = gap
        }

        function getMaxScroll() {
            return Math.max(0, track.scrollWidth - track.parentElement.offsetWidth);
        }

        function updateButtons() {
            var max = getMaxScroll();
            if (prevBtn) prevBtn.disabled = (scrollPos <= 0);
            if (nextBtn) nextBtn.disabled = (scrollPos >= max);
        }

        function scrollTo(pos) {
            var max = getMaxScroll();
            scrollPos = Math.max(0, Math.min(pos, max));
            track.style.transform = 'translateX(-' + scrollPos + 'px)';
            updateButtons();
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                scrollTo(scrollPos - getScrollAmount());
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                scrollTo(scrollPos + getScrollAmount());
            });
        }

        // Filtros
        filterBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                filterBtns.forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');

                var filter = this.getAttribute('data-filter');

                cards.forEach(function(card) {
                    var tipo = card.getAttribute('data-tipo');
                    if (filter === 'todas') {
                        card.style.display = '';
                    } else if (tipo.indexOf(filter) !== -1) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Reset scroll
                scrollTo(0);
            });
        });

        // Touch swipe support
        var startX = 0;
        var isDragging = false;

        track.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
            isDragging = true;
        }, { passive: true });

        track.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            var diff = startX - e.touches[0].clientX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    scrollTo(scrollPos + getScrollAmount());
                } else {
                    scrollTo(scrollPos - getScrollAmount());
                }
                isDragging = false;
            }
        }, { passive: true });

        track.addEventListener('touchend', function() {
            isDragging = false;
        }, { passive: true });

        // Init
        updateButtons();

        // Recalculate on resize
        window.addEventListener('resize', function() {
            scrollTo(scrollPos);
        });
    })();
    </script>
    <?php

    return ob_get_clean();
}
add_shortcode('noticias_carousel', 'cc_shortcode_noticias_carousel');

/**
 * ==============================================
 * WIDGET DASHBOARD ADMIN
 * ==============================================
 */

/**
 * Widget de noticias urgentes en admin dashboard
 */
function cc_dashboard_widget_noticias_urgentes() {
    $query = cc_get_noticias_urgentes();
    
    if ($query->have_posts()) {
        echo '<div class="noticias-urgentes-dashboard">';
        echo '<p style="color: #d63638; font-weight: bold; font-size: 14px;">';
        echo '<span class="dashicons dashicons-warning" style="color: #d63638;"></span> Hay ' . $query->post_count . ' noticia(s) urgente(s) activa(s)';
        echo '</p>';
        echo '<ul style="list-style: none; padding: 0; margin: 15px 0 0 0;">';
        
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li style="padding: 8px 0; border-bottom: 1px solid #f0f0f1;">';
            echo '<a href="' . get_edit_post_link() . '" style="text-decoration: none;">';
            echo '<strong>' . get_the_title() . '</strong>';
            echo '</a>';
            echo '<br><span style="color: #787c82; font-size: 12px;">Publicado: ' . get_the_date() . '</span>';
            echo '</li>';
        }
        
        echo '</ul>';
        wp_reset_postdata();
        echo '</div>';
    } else {
        echo '<p style="color: #46b450;"><span class="dashicons dashicons-yes" style="color: #46b450;"></span> No hay noticias urgentes en este momento.</p>';
        echo '<p style="color: #787c82; font-size: 13px;">Las noticias marcadas como urgentes apareceran aqui.</p>';
    }
}

function cc_agregar_dashboard_widget() {
    wp_add_dashboard_widget(
        'cc_noticias_urgentes',
        'Noticias Urgentes - Casa de la Cultura',
        'cc_dashboard_widget_noticias_urgentes'
    );
}
add_action('wp_dashboard_setup', 'cc_agregar_dashboard_widget');

/**
 * ==============================================
 * FLUSH REWRITE RULES (solo en activación)
 * ==============================================
 */
function cc_noticias_flush_rewrites() {
    cc_registrar_post_type_noticia();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cc_noticias_flush_rewrites');

/**
 * Encolar estilos y scripts de noticias
 */
function cc_enqueue_noticias_assets() {
    // Solo cargar en páginas de noticias
    if (is_singular('noticia') || is_post_type_archive('noticia')) {
        
        // CSS
        wp_enqueue_style(
            'cc-noticias-styles',
            get_template_directory_uri() . '/plantillas/agenda/noticia/noticias-styles.css',
            array(),
            '1.0.0'
        );
        
        // JavaScript
        wp_enqueue_script(
            'cc-noticias-filtros',
            get_template_directory_uri() . '/plantillas/agenda/noticia/noticias-filtros.js',
            array(),
            '1.0.0',
            true
        );
        
        // Lightbox para galería (opcional - puedes usar cualquier librería)
        wp_enqueue_script(
            'lightbox',
            'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js',
            array('jquery'),
            '2.11.3',
            true
        );
        
        wp_enqueue_style(
            'lightbox-css',
            'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css',
            array(),
            '2.11.3'
        );
    }
    
    // Cargar estilos para la plantilla de listado de noticias
    if (is_page_template('page-listado-noticias.php')) {
        wp_enqueue_style(
            'cc-page-listado-noticias-styles',
            get_template_directory_uri() . '/plantillas/agenda/noticia/page-listado-noticias-styles.css',
            array(),
            '1.0.0'
        );

        // Sticky Filters Script (Mobile)
        wp_enqueue_script(
            'cc-sticky-filters',
            get_template_directory_uri() . '/plantillas/agenda/assets/js/sticky-filters.js',
            array(),
            filemtime(get_template_directory() . '/plantillas/agenda/assets/js/sticky-filters.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'cc_enqueue_noticias_assets');

/**
 * ========================================
 * COLUMNAS PERSONALIZADAS EN ADMIN
 * ========================================
 */

/**
 * Agregar columnas personalizadas
 */
function cc_noticias_columnas_admin($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'title') {
            $new_columns['noticia_categoria'] = 'Categoría';
            $new_columns['noticia_destacada'] = 'Destacada';
            $new_columns['noticia_urgente'] = 'Urgente';
        }
    }
    
    return $new_columns;
}
add_filter('manage_noticia_posts_columns', 'cc_noticias_columnas_admin');

/**
 * Rellenar columnas personalizadas
 */
function cc_noticias_columnas_contenido($column, $post_id) {
    switch ($column) {
        case 'noticia_categoria':
            $categoria = get_field('noticia_categoria', $post_id);
            $categorias = array(
                'general' => 'General',
                'eventos' => 'Eventos',
                'talleres' => 'Talleres',
                'convenios' => 'Convenios',
                'rendicion' => 'Rendicion de Cuentas'
            );
            echo isset($categorias[$categoria]) ? $categorias[$categoria] : '-';
            break;
            
        case 'noticia_destacada':
            $destacada = get_field('noticia_destacada', $post_id);
            if ($destacada) {
                echo '<span style="color: #e67e22; font-weight: bold;"><span class="dashicons dashicons-star-filled" style="color: #e67e22;"></span> Destacada</span>';
            } else {
                echo '-';
            }
            break;
            
        case 'noticia_urgente':
            $urgente = get_field('noticia_urgente', $post_id);
            if ($urgente) {
                echo '<span style="color: #d63638; font-weight: bold;"><span class="dashicons dashicons-megaphone" style="color: #d63638;"></span> Urgente</span>';
            } else {
                echo '-';
            }
            break;
    }
}
add_action('manage_noticia_posts_custom_column', 'cc_noticias_columnas_contenido', 10, 2);

/**
 * Hacer columnas ordenables
 */
function cc_noticias_columnas_ordenables($columns) {
    $columns['noticia_categoria'] = 'noticia_categoria';
    $columns['noticia_destacada'] = 'noticia_destacada';
    $columns['noticia_urgente'] = 'noticia_urgente';
    return $columns;
}
add_filter('manage_edit-noticia_sortable_columns', 'cc_noticias_columnas_ordenables');

/**
 * ========================================
 * MENSAJES PERSONALIZADOS
 * ========================================
 */

/**
 * Personalizar mensajes de actualización
 */
function cc_noticias_mensajes_personalizados($messages) {
    global $post;

    $messages['noticia'] = array(
        0  => '',
        1  => 'Noticia actualizada. <a href="' . esc_url(get_permalink($post->ID)) . '">Ver noticia</a>',
        2  => 'Campo personalizado actualizado.',
        3  => 'Campo personalizado eliminado.',
        4  => 'Noticia actualizada.',
        5  => isset($_GET['revision']) ? 'Noticia restaurada a revisión de ' . wp_post_revision_title((int) $_GET['revision'], false) : false,
        6  => 'Noticia publicada. <a href="' . esc_url(get_permalink($post->ID)) . '">Ver noticia</a>',
        7  => 'Noticia guardada.',
        8  => 'Noticia enviada. <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">Vista previa de la noticia</a>',
        9  => sprintf('Noticia programada para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Vista previa de la noticia</a>', date_i18n('M j, Y @ G:i', strtotime($post->post_date)), esc_url(get_permalink($post->ID))),
        10 => 'Borrador de noticia actualizado. <a target="_blank" href="' . esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))) . '">Vista previa de la noticia</a>',
    );

    return $messages;
}
add_filter('post_updated_messages', 'cc_noticias_mensajes_personalizados');
