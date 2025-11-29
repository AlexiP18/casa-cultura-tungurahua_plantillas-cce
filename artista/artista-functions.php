<?php
/**
 * ARTISTAS
 * Funciones para el Custom Post Type: Artista
 * Casa de la Cultura de Tungurahua
 */

if ( ! defined( 'ABSPATH' ) ) {
exit;
}

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
'rewrite'             => array( 'slug' => 'artistas' ),
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
 * Cargar estilos para artistas
 */
function cc_enqueue_artista_styles() {
if ( is_singular( 'artista' ) ) {
wp_enqueue_style( 
'artista-single', 
get_template_directory_uri() . '/plantillas/artista/artista-styles.css',
array(),
'1.0.0'
);
}

if ( is_post_type_archive( 'artista' ) ) {
wp_enqueue_style( 
'artista-archive', 
get_template_directory_uri() . '/plantillas/artista/archive-artista-styles.css',
array(),
'1.0.0'
);
}

// Font Awesome para iconos
if ( is_singular( 'artista' ) || is_post_type_archive( 'artista' ) ) {
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
				$disciplina_artistica = get_field( 'disciplina_artistica' );
				$slider = get_field( 'slider_imagenes' );
				$imagen_destacada = ! empty( $slider['imagen_1'] ) ? $slider['imagen_1'] : null;
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
						
						<?php if ( $disciplina_artistica ) : ?>
							<div class="artista-card-disciplina">
								<?php echo esc_html( $disciplina_artistica ); ?>
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
add_shortcode( 'artistas', 'cc_shortcode_artistas' );/**
 * Columnas personalizadas en admin
 */
function cc_artista_admin_columns( $columns ) {
$new_columns = array();

foreach ( $columns as $key => $title ) {
$new_columns[ $key ] = $title;

if ( $key === 'title' ) {
$new_columns['disciplina'] = 'Disciplina';
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
$disciplina = get_field( 'disciplina_artistica', $post_id );
echo $disciplina ? esc_html( $disciplina ) : '—';
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
