<?php
/**
 * Template Name: Página de Talleres
 * Description: Muestra el listado de talleres en una página
 */

get_header();
?>

<div class="talleres-archive-container">
    <header class="talleres-header">
        <h1><?php the_title(); ?></h1>
        <div class="talleres-descripcion">
            <?php the_content(); ?>
        </div>
    </header>

    <?php
    // Consulta personalizada para obtener los talleres
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'taller',
        'posts_per_page' => 9,
        'paged' => $paged
    );
    $talleres_query = new WP_Query($args);
    
    if ($talleres_query->have_posts()) : 
    ?>
        <div class="talleres-grid">
            <?php while ($talleres_query->have_posts()) : $talleres_query->the_post(); 
                // Obtener datos del taller
                $imagenes = get_field('slider_imagenes');
                $imagen_destacada = !empty($imagenes['imagen_1']) ? $imagenes['imagen_1'] : null;
                $instructor = get_field('instructor');
                $costo = get_field('costo');
            ?>
                <article class="taller-card">
                    <div class="taller-card-image">
                        <?php if ($imagen_destacada) : ?>
                            <img src="<?php echo esc_url($imagen_destacada['url']); ?>" 
                                 alt="<?php the_title_attribute(); ?>">
                        <?php elseif (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large'); ?>
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/taller-placeholder.jpg" 
                                 alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
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
        
        <div class="talleres-pagination">
            <?php 
            echo paginate_links(array(
                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format' => '?paged=%#%',
                'current' => max(1, $paged),
                'total' => $talleres_query->max_num_pages,
                'prev_text' => '&laquo; Anterior',
                'next_text' => 'Siguiente &raquo;',
            )); 
            ?>
        </div>
        
        <?php wp_reset_postdata(); ?>
        
    <?php else : ?>
        <div class="talleres-empty">
            <p>No hay talleres disponibles en este momento.</p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>