<?php
/**
 * ARTISTAS
 * Funciones para el Custom Post Type: Artista
 * Casa de la Cultura de Tungurahua
 */

if ( ! defined( 'ABSPATH' ) ) {
exit;
}

// Cargar módulo de importación/exportación
require_once get_template_directory() . '/plantillas/artista/artista-import-export.php';

/**
 * Registrar Custom Post Type: Artista
 */
function cc_register_artista_post_type() {
$labels = array(
'name'               => 'Artistas',
'singular_name'      => 'Artista',
'menu_name'          => 'Artistas',
'add_new'            => 'Agregar Artista',
'add_new_item'       => 'Agregar Nuevo Artista',
'edit_item'          => 'Editar Artista',
'new_item'           => 'Nuevo Artista',
'view_item'          => 'Ver Artista',
'search_items'       => 'Buscar Artistas',
'not_found'          => 'No se encontraron artistas',
'not_found_in_trash' => 'No hay artistas en la papelera',
'all_items'          => 'Todos los Artistas',
);

$args = array(
'labels'              => $labels,
'public'              => true,
'has_archive'         => true,
'publicly_queryable'  => true,
'show_ui'             => true,
'show_in_menu'        => true,
'query_var'           => true,
	'rewrite'             => array( 'slug' => 'nuestros-artistas' ),
'capability_type'     => 'post',
'has_archive'         => true,
'hierarchical'        => false,
'menu_position'       => 6,
'menu_icon'           => 'dashicons-art',
'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
'show_in_rest'        => true,
);

register_post_type( 'artista', $args );
}
add_action( 'init', 'cc_register_artista_post_type' );

/**
 * Obtener array de etiquetas de disciplinas artísticas (macro-categorías)
 */
function cc_get_disciplinas_labels() {
	return array(
		'artes_visuales' => 'Artes Visuales',
		'artes_plasticas' => 'Artes Plásticas',
		'artes_literarias' => 'Artes Literarias',
		'artes_escenicas' => 'Artes Escénicas',
		'artes_musicales' => 'Artes Musicales',
		'artes_audiovisuales' => 'Artes Audiovisuales',
		'artes_digitales' => 'Artes Digitales y Nuevos Medios',
		'artes_aplicadas' => 'Artes Aplicadas y Diseño',
		'artes_tradicionales' => 'Artes Tradicionales y Populares',
		'artes_corporales' => 'Artes Corporales y Performance',
		'fotografia' => 'Fotografía',
		'arquitectura' => 'Arquitectura',
		'otra' => 'Otra',
	);
}

/**
 * Obtener imágenes del slider del artista
 */
function cc_get_artista_slider( $post_id = null ) {
if ( ! $post_id ) {
$post_id = get_the_ID();
}

$slider = get_field( 'slider_imagenes', $post_id );

if ( ! $slider || ! is_array( $slider ) ) {
return array();
}

$imagenes = array();
for ( $i = 1; $i <= 5; $i++ ) {
if ( ! empty( $slider['imagen_' . $i] ) ) {
$imagenes[] = $slider['imagen_' . $i];
}
}

return $imagenes;
}

/**
 * Obtener información de contacto del artista
 */
function cc_get_artista_contacto( $post_id = null ) {
if ( ! $post_id ) {
$post_id = get_the_ID();
}

$contacto = get_field( 'contacto', $post_id );

return array(
'telefono' => isset( $contacto['telefono'] ) ? $contacto['telefono'] : '',
'email'    => isset( $contacto['email'] ) ? $contacto['email'] : '',
);
}

/**
 * Obtener redes sociales del artista
 */
function cc_get_artista_redes( $post_id = null ) {
if ( ! $post_id ) {
$post_id = get_the_ID();
}

$redes = get_field( 'redes_sociales', $post_id );

if ( ! $redes || ! is_array( $redes ) ) {
return array();
}

$redes_disponibles = array();
$redes_config = array(
'facebook'  => array( 'nombre' => 'Facebook', 'icono' => 'fab fa-facebook-f' ),
'instagram' => array( 'nombre' => 'Instagram', 'icono' => 'fab fa-instagram' ),
'twitter'   => array( 'nombre' => 'Twitter', 'icono' => 'fab fa-twitter' ),
'youtube'   => array( 'nombre' => 'YouTube', 'icono' => 'fab fa-youtube' ),
'tiktok'    => array( 'nombre' => 'TikTok', 'icono' => 'fab fa-tiktok' ),
'website'   => array( 'nombre' => 'Sitio Web', 'icono' => 'fas fa-globe' ),
);

foreach ( $redes_config as $key => $config ) {
if ( ! empty( $redes[ $key ] ) ) {
$redes_disponibles[ $key ] = array(
'url'    => $redes[ $key ],
'nombre' => $config['nombre'],
'icono'  => $config['icono'],
);
}
}

return $redes_disponibles;
}

/**
 * Forzar WordPress a usar nuestras plantillas personalizadas de artista
 * Prioridad 9999 para sobrescribir el tema PopularFX/PageLayer
 */
function cc_load_artista_templates( $template ) {
	// Template para archivo (listado de artistas)
	if ( is_post_type_archive( 'artista' ) ) {
		$custom_template = get_template_directory() . '/plantillas/artista/archive-artista.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	
	// Template para single (perfil individual)
	if ( is_singular( 'artista' ) ) {
		$custom_template = get_template_directory() . '/plantillas/artista/single-artista.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	
	return $template;
}
add_filter( 'template_include', 'cc_load_artista_templates', 9999 );

/**
 * Cargar estilos para artistas
 */
function cc_enqueue_artista_styles() {
	// Verificar si es la plantilla de listado de artistas
	$is_listado_template = is_page() && basename( get_page_template() ) === 'page-listado-artistas.php';
	
	if ( is_singular( 'artista' ) ) {
		wp_enqueue_style( 
			'artista-single', 
			get_template_directory_uri() . '/plantillas/artista/artista-styles.css',
			array(),
			filemtime( get_template_directory() . '/plantillas/artista/artista-styles.css' )
		);
	}

	// Cargar estilos de archivo tanto para archive como para la plantilla de página
	if ( is_post_type_archive( 'artista' ) || $is_listado_template ) {
		wp_enqueue_style( 
			'artista-archive', 
			get_template_directory_uri() . '/plantillas/artista/archive-artista-styles.css',
			array(),
			filemtime( get_template_directory() . '/plantillas/artista/archive-artista-styles.css' )
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

	// Font Awesome para iconos
	if ( is_singular( 'artista' ) || is_post_type_archive( 'artista' ) || $is_listado_template ) {
		wp_enqueue_style( 
			'font-awesome', 
			'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
			array(),
			'6.4.2'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'cc_enqueue_artista_styles' );

/**
 * Asegurar que los estilos se cargan cuando se usa el shortcode
 */
function cc_check_for_artistas_shortcode( $posts ) {
	if ( empty( $posts ) ) {
		return $posts;
	}

	$shortcode_found = false;

	// Buscar el shortcode en los posts
	foreach ( $posts as $post ) {
		if ( stripos( $post->post_content, '[mostrar_artistas' ) !== false || 
		     stripos( $post->post_content, '[artistas' ) !== false ) {
			$shortcode_found = true;
			break;
		}
	}

	// Si se encuentra el shortcode, cargar los estilos necesarios
	if ( $shortcode_found ) {
		add_action( 'wp_enqueue_scripts', 'cc_load_artistas_shortcode_styles', 100 );
	}

	return $posts;
}
add_action( 'the_posts', 'cc_check_for_artistas_shortcode' );

/**
 * Cargar los estilos cuando se usa el shortcode
 */
function cc_load_artistas_shortcode_styles() {
	wp_enqueue_style( 
		'artista-archive', 
		get_template_directory_uri() . '/plantillas/artista/archive-artista-styles.css',
		array(),
		'1.0.0'
	);
	
	wp_enqueue_style( 
		'font-awesome', 
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
		array(),
		'6.4.2'
	);
}

/**
 * Shortcode optimizado para mostrar artistas
 * Uso: [mostrar_artistas cantidad="9" disciplina="pintura" orden="title" direccion="ASC"]
 */
function cc_shortcode_artistas_optimizado( $atts ) {
	// Asegurar que los estilos se cargan
	wp_enqueue_style( 
		'artista-archive', 
		get_template_directory_uri() . '/plantillas/artista/archive-artista-styles.css',
		array(),
		'1.0.0'
	);
	// Extraer y definir atributos
	$atts = shortcode_atts( array(
		'cantidad'   => 9,
		'disciplina' => '',
		'orden'      => 'title',
		'direccion'  => 'ASC',
	), $atts );

	// Construir argumentos para la consulta
	$args = array(
		'post_type'              => 'artista',
		'posts_per_page'         => intval( $atts['cantidad'] ),
		'orderby'                => $atts['orden'],
		'order'                  => $atts['direccion'],
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);

	// Filtrar por disciplina si se especifica
	if ( ! empty( $atts['disciplina'] ) ) {
		$args['meta_query'] = array(
			array(
				'key'     => 'disciplina_artistica',
				'value'   => $atts['disciplina'],
				'compare' => 'LIKE',
			),
		);
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return '<div class="artistas-empty"><p>No se encontraron artistas.</p></div>';
	}

	ob_start();
	?>
	<div class="artistas-archive-container">
		<div class="artistas-grid">
			<?php while ( $query->have_posts() ) : $query->the_post(); 
				// Obtener datos del artista
				$disciplina_artistica = get_field( 'disciplina_artistica' ); // Array de disciplinas
				$slider = get_field( 'slider_imagenes' );
				$imagen_destacada = ! empty( $slider['imagen_1'] ) ? $slider['imagen_1'] : null;
				
				// Etiquetas de disciplinas
				$disciplinas_labels = cc_get_disciplinas_labels();
			?>
				<article class="artista-card">
					<div class="artista-card-image">
						<?php if ( $imagen_destacada ) : ?>
							<img src="<?php echo esc_url( $imagen_destacada['url'] ); ?>" 
								 alt="<?php the_title_attribute(); ?>">
						<?php elseif ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium_large' ); ?>
						<?php else : ?>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/img/artista-placeholder.jpg" 
								 alt="<?php the_title_attribute(); ?>">
						<?php endif; ?>
					</div>
					
					<div class="artista-card-content">
						<h2 class="artista-card-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						
						<?php if ( ! empty( $disciplina_artistica ) && is_array( $disciplina_artistica ) ) : ?>
							<div class="artista-card-disciplina">
								<div class="disciplina-tags">
									<?php foreach ( $disciplina_artistica as $disciplina_key ) : ?>
										<?php if ( isset( $disciplinas_labels[$disciplina_key] ) ) : ?>
											<span class="disciplina-tag"><?php echo esc_html( $disciplinas_labels[$disciplina_key] ); ?></span>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
						
						<div class="artista-card-excerpt">
							<?php the_excerpt(); ?>
						</div>
						
						<a href="<?php the_permalink(); ?>" class="artista-card-btn">Ver perfil</a>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	</div>
	<?php
	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode( 'mostrar_artistas', 'cc_shortcode_artistas_optimizado' );

/**
 * Shortcode simple (mantener compatibilidad)
 * Uso: [artistas limit="9"]
 */
function cc_shortcode_artistas( $atts ) {
	$atts = shortcode_atts( array(
		'limit' => 9,
	), $atts );

	return cc_shortcode_artistas_optimizado( array(
		'cantidad' => $atts['limit'],
	) );
}
add_shortcode( 'artistas', 'cc_shortcode_artistas' );

/**
 * Columnas personalizadas en admin
 */
function cc_artista_admin_columns( $columns ) {
	$new_columns = array();

	foreach ( $columns as $key => $title ) {
		$new_columns[ $key ] = $title;

		if ( $key === 'title' ) {
			$new_columns['disciplina'] = 'Disciplinas';
			$new_columns['contacto'] = 'Contacto';
		}
	}

	return $new_columns;
}
add_filter( 'manage_artista_posts_columns', 'cc_artista_admin_columns' );

/**
 * Contenido de columnas personalizadas
 */
function cc_artista_admin_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'disciplina':
			$disciplinas = get_field( 'disciplina_artistica', $post_id );
			if ( ! empty( $disciplinas ) && is_array( $disciplinas ) ) {
				// Obtener etiquetas de disciplinas
				$disciplinas_labels = cc_get_disciplinas_labels();
				$nombres_disciplinas = array();
				foreach ( $disciplinas as $disc_key ) {
					if ( isset( $disciplinas_labels[$disc_key] ) ) {
						$nombres_disciplinas[] = $disciplinas_labels[$disc_key];
					}
				}
				echo ! empty( $nombres_disciplinas ) ? esc_html( implode( ', ', $nombres_disciplinas ) ) : '—';
			} else {
				echo '—';
			}
			break;

case 'contacto':
$contacto = cc_get_artista_contacto( $post_id );
if ( $contacto['telefono'] ) {
echo '<span class="dashicons dashicons-phone"></span> ' . esc_html( $contacto['telefono'] );
}
if ( $contacto['email'] ) {
echo '<br><span class="dashicons dashicons-email"></span> ' . esc_html( $contacto['email'] );
}
if ( ! $contacto['telefono'] && ! $contacto['email'] ) {
echo '—';
}
break;
}
}
add_action( 'manage_artista_posts_custom_column', 'cc_artista_admin_column_content', 10, 2 );

/**
 * DEBUG: Verificar ruta de estilos (eliminar después de probar)
 */
function cc_debug_artista_styles() {
    if ( is_singular( 'artista' ) || is_post_type_archive( 'artista' ) ) {
        $css_path = get_template_directory() . '/plantillas/artista/artista-styles.css';
        $css_url = get_template_directory_uri() . '/plantillas/artista/artista-styles.css';
        
        echo '<!-- DEBUG ARTISTA STYLES -->';
        echo '<!-- Archivo existe: ' . ( file_exists( $css_path ) ? 'SÍ' : 'NO' ) . ' -->';
        echo '<!-- Ruta física: ' . $css_path . ' -->';
        echo '<!-- URL: ' . $css_url . ' -->';
        echo '<!-- is_singular artista: ' . ( is_singular( 'artista' ) ? 'SÍ' : 'NO' ) . ' -->';
        echo '<!-- is_post_type_archive artista: ' . ( is_post_type_archive( 'artista' ) ? 'SÍ' : 'NO' ) . ' -->';
    }
}
add_action( 'wp_head', 'cc_debug_artista_styles' );

/* ========================================================================
 * REGISTRO PÚBLICO DE ARTISTAS
 * ======================================================================== */

/**
 * Cargar estilos del formulario de registro
 */
function cc_registro_artista_styles() {
    if ( is_page_template( 'page-registro-artista.php' ) ) {
        wp_enqueue_style( 
            'registro-artista-styles', 
            get_template_directory_uri() . '/plantillas/artista/registro-artista-styles.css',
            array(),
            filemtime( get_template_directory() . '/plantillas/artista/registro-artista-styles.css' )
        );
        
        // Font Awesome para iconos
        wp_enqueue_style( 
            'font-awesome', 
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
            array(),
            '6.4.0'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'cc_registro_artista_styles' );

/**
 * Procesar formulario de registro de artista (formulario manual)
 */
function cc_procesar_formulario_registro_artista() {
    // Verificar que el formulario fue enviado
    if ( ! isset( $_POST['_registro_artista_nonce'] ) ) {
        return;
    }
    
    // Verificar nonce de seguridad
    if ( ! wp_verify_nonce( $_POST['_registro_artista_nonce'], 'registro_artista_nonce' ) ) {
        wp_die( 'Error de seguridad. Intenta de nuevo.' );
    }
    
    // --- Verificar CAPTCHA ---
    $captcha_respuesta = isset( $_POST['captcha_respuesta'] ) ? intval( $_POST['captcha_respuesta'] ) : 0;
    $captcha_hash = isset( $_POST['captcha_hash'] ) ? sanitize_text_field( $_POST['captcha_hash'] ) : '';
    
    if ( empty( $captcha_respuesta ) || wp_hash( (string) $captcha_respuesta ) !== $captcha_hash ) {
        wp_die( 'La respuesta de verificación es incorrecta. Por favor vuelve atrás e intenta de nuevo.' );
    }
    
    // Sanitizar datos del formulario
    $nombre = sanitize_text_field( $_POST['nombre_completo'] );
    $descripcion = wp_kses_post( $_POST['descripcion'] );
    $disciplinas = isset( $_POST['disciplina_artistica'] ) ? array_map( 'sanitize_text_field', $_POST['disciplina_artistica'] ) : array();
    $especialidad = sanitize_textarea_field( $_POST['especialidad'] );
    $trayectoria = wp_kses_post( $_POST['trayectoria'] );
    $telefono = sanitize_text_field( $_POST['telefono'] );
    $email = sanitize_email( $_POST['email'] );
    $facebook = esc_url_raw( $_POST['facebook'] );
    $instagram = esc_url_raw( $_POST['instagram'] );
    $twitter = esc_url_raw( $_POST['twitter'] );
    $youtube = esc_url_raw( $_POST['youtube'] );
    $tiktok = esc_url_raw( $_POST['tiktok'] );
    $website = esc_url_raw( $_POST['website'] );
    
    // --- Validar teléfono Ecuador (si fue ingresado) ---
    if ( ! empty( $telefono ) && ! preg_match( '/^09[0-9]{8}$/', $telefono ) ) {
        wp_die( 'El número de teléfono no es válido. Debe tener 10 dígitos y empezar con 09.' );
    }
    
    // --- Validar email ---
    if ( empty( $email ) || ! is_email( $email ) ) {
        wp_die( 'El correo electrónico ingresado no es válido.' );
    }
    
    // Validaciones básicas
    if ( empty( $nombre ) || empty( $descripcion ) || empty( $disciplinas ) ) {
        wp_die( 'Por favor completa todos los campos obligatorios.' );
    }
    
    // Aplicar límites de caracteres del lado del servidor
    $nombre = mb_substr( $nombre, 0, 100 );
    $descripcion = mb_substr( $descripcion, 0, 500 );
    $especialidad = mb_substr( $especialidad, 0, 300 );
    $trayectoria = mb_substr( $trayectoria, 0, 2000 );
    
    // Crear el post de artista con estado "pending"
    $post_id = wp_insert_post( array(
        'post_title'   => $nombre,
        'post_content' => $descripcion,
        'post_type'    => 'artista',
        'post_status'  => 'pending',
    ) );
    
    if ( is_wp_error( $post_id ) ) {
        wp_die( 'Error al crear el registro. Por favor intenta de nuevo.' );
    }
    
    // Guardar campos ACF
    update_field( 'disciplina_artistica', $disciplinas, $post_id );
    update_field( 'especialidad', $especialidad, $post_id );
    update_field( 'trayectoria', $trayectoria, $post_id );
    
    // Guardar contacto
    update_field( 'contacto', array(
        'telefono' => $telefono,
        'email'    => $email,
    ), $post_id );
    
    // Guardar redes sociales
    update_field( 'redes_sociales', array(
        'facebook'  => $facebook,
        'instagram' => $instagram,
        'twitter'   => $twitter,
        'youtube'   => $youtube,
        'tiktok'    => $tiktok,
        'website'   => $website,
    ), $post_id );
    
    // Procesar imágenes del slider
    // Aumentar límites de PHP para subida de archivos
    @ini_set( 'max_file_uploads', '10' );
    @ini_set( 'upload_max_filesize', '5M' );
    @ini_set( 'post_max_size', '25M' );
    
    // Cargar funciones de WordPress para subir archivos
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
    }
    if ( ! function_exists( 'media_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
    }
    
    $slider_data = array();
    $max_size = 2 * 1024 * 1024; /* 2MB */
    $allowed_mimes = array( 'image/jpeg', 'image/png', 'image/gif' );
    
    for ( $i = 1; $i <= 5; $i++ ) {
        $file_key = 'imagen_' . $i;
        
        // Verificar que el archivo existe en $_FILES y no tiene errores
        if ( ! isset( $_FILES[ $file_key ] ) ) {
            continue;
        }
        if ( $_FILES[ $file_key ]['error'] === UPLOAD_ERR_NO_FILE ) {
            continue;
        }
        if ( $_FILES[ $file_key ]['error'] !== UPLOAD_ERR_OK ) {
            continue;
        }
        if ( empty( $_FILES[ $file_key ]['name'] ) ) {
            continue;
        }
        
        // Validar tamaño
        if ( $_FILES[ $file_key ]['size'] > $max_size ) {
            continue;
        }
        
        // Validar tipo MIME
        $file_type = wp_check_filetype( $_FILES[ $file_key ]['name'] );
        if ( ! $file_type['type'] || ! in_array( $_FILES[ $file_key ]['type'], $allowed_mimes, true ) ) {
            continue;
        }
        
        $attachment_id = media_handle_upload( $file_key, $post_id );
        
        if ( ! is_wp_error( $attachment_id ) ) {
            $slider_data[ 'imagen_' . $i ] = $attachment_id;
        }
    }
    
    // Guardar slider si hay imágenes
    if ( ! empty( $slider_data ) ) {
        update_field( 'slider_imagenes', $slider_data, $post_id );
    }
    
    // Enviar notificación al administrador
    cc_enviar_notificacion_nuevo_artista( $post_id );
    
    // Redirigir con mensaje de éxito
    $redirect_url = add_query_arg( 'submitted', 'true', wp_get_referer() );
    wp_safe_redirect( $redirect_url );
    exit;
}
add_action( 'template_redirect', 'cc_procesar_formulario_registro_artista' );

/**
 * Enviar notificación por email al admin cuando se registra un artista
 */
function cc_enviar_notificacion_nuevo_artista( $post_id ) {
    $post = get_post( $post_id );
    
    if ( ! $post || $post->post_status !== 'pending' ) {
        return;
    }
    
    // Email del administrador
    $admin_email = get_option( 'admin_email' );
    
    // Obtener datos del artista
    $nombre_artista = $post->post_title;
    $disciplinas = get_field( 'disciplina_artistica', $post_id );
    $contacto = get_field( 'contacto', $post_id );
    $email_artista = ! empty( $contacto['email'] ) ? $contacto['email'] : 'No proporcionado';
    $telefono_artista = ! empty( $contacto['telefono'] ) ? $contacto['telefono'] : 'No proporcionado';
    
    // Convertir disciplinas a texto
    $disciplinas_texto = '';
    if ( ! empty( $disciplinas ) && is_array( $disciplinas ) ) {
        $disciplinas_labels = cc_get_disciplinas_labels();
        $disciplinas_nombres = array();
        foreach ( $disciplinas as $disc_key ) {
            if ( isset( $disciplinas_labels[ $disc_key ] ) ) {
                $disciplinas_nombres[] = $disciplinas_labels[ $disc_key ];
            }
        }
        $disciplinas_texto = implode( ', ', $disciplinas_nombres );
    } else {
        $disciplinas_texto = 'No especificadas';
    }
    
    // URL para revisar el artista
    $edit_url = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
    
    // Asunto del email
    $subject = '[Casa de la Cultura] Nuevo registro de artista: ' . $nombre_artista;
    
    // Cuerpo del email
    $message = "Se ha registrado un nuevo artista en el sitio web.\n\n";
    $message .= "INFORMACIÓN DEL ARTISTA:\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "Nombre: " . $nombre_artista . "\n";
    $message .= "Disciplinas: " . $disciplinas_texto . "\n";
    $message .= "Email: " . $email_artista . "\n";
    $message .= "Teléfono: " . $telefono_artista . "\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "ACCIONES REQUERIDAS:\n\n";
    $message .= "Para revisar y aprobar este registro, ingresa al siguiente enlace:\n";
    $message .= $edit_url . "\n\n";
    $message .= "Una vez revisado, puedes:\n";
    $message .= "• Publicar el perfil si la información es correcta\n";
    $message .= "• Editar los datos si es necesario\n";
    $message .= "• Eliminar el registro si no cumple los requisitos\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "Este es un mensaje automático del sistema de registro de artistas.\n";
    $message .= "Casa de la Cultura de Tungurahua\n";
    $message .= home_url();
    
    // Headers del email
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: Casa de la Cultura <' . $admin_email . '>'
    );
    
    // Enviar email
    wp_mail( $admin_email, $subject, $message, $headers );
}

/**
 * Agregar columna de estado en la lista de artistas del admin
 */
function cc_artista_estado_column( $columns ) {
    $new_columns = array();
    
    foreach ( $columns as $key => $value ) {
        $new_columns[ $key ] = $value;
        
        // Insertar columna de estado después del título
        if ( $key === 'title' ) {
            $new_columns['estado'] = 'Estado';
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_artista_posts_columns', 'cc_artista_estado_column' );

/**
 * Mostrar contenido de la columna de estado
 */
function cc_artista_estado_column_content( $column, $post_id ) {
    if ( $column === 'estado' ) {
        $status = get_post_status( $post_id );
        
        switch ( $status ) {
            case 'publish':
                echo '<span style="color: #46b450; font-weight: 600;">✓ Publicado</span>';
                break;
            case 'pending':
                echo '<span style="color: #f0b849; font-weight: 600;">⏳ Pendiente</span>';
                break;
            case 'draft':
                echo '<span style="color: #72aee6; font-weight: 600;">📝 Borrador</span>';
                break;
            case 'trash':
                echo '<span style="color: #d63638; font-weight: 600;">🗑️ Papelera</span>';
                break;
            default:
                echo '<span style="color: #8c8f94;">❓ ' . esc_html( $status ) . '</span>';
        }
    }
}
add_action( 'manage_artista_posts_custom_column', 'cc_artista_estado_column_content', 10, 2 );

/**
 * Hacer la columna de estado ordenable
 */
function cc_artista_estado_sortable( $columns ) {
    $columns['estado'] = 'post_status';
    return $columns;
}
add_filter( 'manage_edit-artista_sortable_columns', 'cc_artista_estado_sortable' );

/**
 * Agregar notificación en el admin para artistas pendientes
 */
function cc_artistas_pendientes_admin_notice() {
    $screen = get_current_screen();
    
    // Contar artistas pendientes
    $pending_count = wp_count_posts( 'artista' )->pending;
    
    if ( $pending_count > 0 ) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <strong>🎨 Artistas Pendientes de Aprobación:</strong> 
                Hay <strong><?php echo $pending_count; ?></strong> 
                <?php echo $pending_count === 1 ? 'artista pendiente' : 'artistas pendientes'; ?> 
                de revisión.
                <a href="<?php echo admin_url( 'edit.php?post_type=artista&post_status=pending' ); ?>">
                    Ver ahora →
                </a>
            </p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'cc_artistas_pendientes_admin_notice' );
