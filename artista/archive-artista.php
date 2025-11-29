<?php
/**
 * Plantilla para el archivo de artistas
 *
 * @package CasaDeLaCultura
 */

get_header();
?>

<div class="artistas-archive-container">
    <header class="artistas-header">
        <h1>Nuestros Artistas</h1>
        <div class="artistas-descripcion">
            <p>Conoce a los talentosos artistas que forman parte de la Casa de la Cultura de Tungurahua. 
            Un espacio donde el talento local y nacional se reúne para compartir su arte con la comunidad.</p>
        </div>
    </header>

    <?php if (have_posts()) : ?>
        <div class="artistas-grid">
            <?php while (have_posts()) : the_post(); 
                // Obtener datos del artista
                $disciplina_artistica = get_field('disciplina_artistica');
                $slider = get_field('slider_imagenes');
                $imagen_destacada = !empty($slider['imagen_1']) ? $slider['imagen_1'] : null;
            ?>
                <article class="artista-card">
                    <div class="artista-card-image">
                        <?php if ($imagen_destacada) : ?>
                            <img src="<?php echo esc_url($imagen_destacada['url']); ?>" 
                                 alt="<?php the_title_attribute(); ?>">
                        <?php elseif (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large'); ?>
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/artista-placeholder.jpg" 
                                 alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                    </div>
                    
                    <div class="artista-card-content">
                        <h2 class="artista-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        
                        <?php if ($disciplina_artistica) : ?>
                            <div class="artista-card-disciplina">
                                <?php echo esc_html($disciplina_artistica); ?>
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
        
        <div class="artistas-pagination">
            <?php 
            echo paginate_links(array(
                'prev_text' => '&laquo; Anterior',
                'next_text' => 'Siguiente &raquo;',
            )); 
            ?>
        </div>
    <?php else : ?>
        <div class="artistas-empty">
            <p>No se encontraron artistas registrados.</p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>