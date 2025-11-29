<?php
/**
 * Template para el listado de entradas del blog
 * Casa de la Cultura - Blog Institucional
 * Custom Post Type: blog
 */

get_header(); 

// Obtener filtro de categoría si existe
$filtro_categoria = isset($_GET['cat_blog']) ? sanitize_text_field($_GET['cat_blog']) : '';

?>

<div class="blog-archivo-wrapper">
    
    <!-- Hero del Blog -->
    <section class="blog-archivo-hero">
        <div class="container">
            <div class="archivo-hero-content">
                <h1 class="archivo-hero-titulo">
                    <i class="fas fa-blog"></i> Blog Institucional
                </h1>
                <p class="archivo-hero-descripcion">
                    Comunicados, reflexiones y noticias de la Casa de la Cultura
                </p>
            </div>
        </div>
    </section>
    
    <!-- Filtros de Categoría -->
    <section class="blog-filtros-section">
        <div class="container">
            <div class="filtros-blog-wrapper">
                <button class="filtro-blog-btn <?php echo empty($filtro_categoria) ? 'active' : ''; ?>" 
                        onclick="window.location.href='<?php echo get_post_type_archive_link('blog'); ?>'">
                    <i class="fas fa-th"></i>
                    <span>Todas</span>
                </button>
                
                <button class="filtro-blog-btn <?php echo $filtro_categoria === 'mensaje_directora' ? 'active' : ''; ?>" 
                        onclick="window.location.href='<?php echo add_query_arg('cat_blog', 'mensaje_directora', get_post_type_archive_link('blog')); ?>'">
                    <i class="fas fa-message"></i>
                    <span>Directora</span>
                </button>
                
                <button class="filtro-blog-btn <?php echo $filtro_categoria === 'conmemoracion' ? 'active' : ''; ?>" 
                        onclick="window.location.href='<?php echo add_query_arg('cat_blog', 'conmemoracion', get_post_type_archive_link('blog')); ?>'">
                    <i class="fas fa-calendar-star"></i>
                    <span>Conmemoraciones</span>
                </button>
                
                <button class="filtro-blog-btn <?php echo $filtro_categoria === 'rendicion_cuentas' ? 'active' : ''; ?>" 
                        onclick="window.location.href='<?php echo add_query_arg('cat_blog', 'rendicion_cuentas', get_post_type_archive_link('blog')); ?>'">
                    <i class="fas fa-chart-line"></i>
                    <span>Rendición</span>
                </button>
                
                <button class="filtro-blog-btn <?php echo $filtro_categoria === 'logros' ? 'active' : ''; ?>" 
                        onclick="window.location.href='<?php echo add_query_arg('cat_blog', 'logros', get_post_type_archive_link('blog')); ?>'">
                    <i class="fas fa-trophy"></i>
                    <span>Logros</span>
                </button>
                
                <button class="filtro-blog-btn <?php echo $filtro_categoria === 'proyectos' ? 'active' : ''; ?>" 
                        onclick="window.location.href='<?php echo add_query_arg('cat_blog', 'proyectos', get_post_type_archive_link('blog')); ?>'">
                    <i class="fas fa-lightbulb"></i>
                    <span>Proyectos</span>
                </button>
            </div>
        </div>
    </section>
    
    <!-- Entradas Destacadas -->
    <?php 
    $destacadas = cc_get_entradas_destacadas(3);
    if ($destacadas->have_posts() && empty($filtro_categoria)): 
    ?>
        <section class="blog-destacadas-section">
            <div class="container">
                <h2 class="section-title-destacadas">
                    <i class="fas fa-star"></i> Entradas Destacadas
                </h2>
                <div class="destacadas-slider">
                    <?php while ($destacadas->have_posts()): $destacadas->the_post(); 
                        $imagen = get_field('blog_imagen_destacada');
                        $categoria = cc_get_blog_categoria_info();
                        $resumen = get_field('blog_resumen');
                        $tiempo = cc_calcular_tiempo_lectura();
                    ?>
                        <article class="destacada-card">
                            <a href="<?php the_permalink(); ?>" class="destacada-link">
                                <div class="destacada-imagen" style="background-image: url('<?php echo esc_url($imagen['url']); ?>');">
                                    <div class="destacada-overlay"></div>
                                    <div class="destacada-badge">
                                        <i class="fas fa-star"></i> DESTACADA
                                    </div>
                                </div>
                                <div class="destacada-contenido">
                                    <span class="destacada-categoria" style="background: <?php echo $categoria['color']; ?>;">
                                        <i class="fas <?php echo $categoria['icon']; ?>"></i>
                                        <?php echo esc_html($categoria['label']); ?>
                                    </span>
                                    <h3><?php the_title(); ?></h3>
                                    <?php if ($resumen): ?>
                                        <p><?php echo esc_html($resumen); ?></p>
                                    <?php endif; ?>
                                    <div class="destacada-meta">
                                        <span><i class="far fa-calendar"></i> <?php echo get_the_date(); ?></span>
                                        <span><i class="far fa-clock"></i> <?php echo $tiempo; ?> min</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Grid de Entradas -->
    <section class="blog-grid-section">
        <div class="container">
            
            <?php
            // Preparar query según filtro
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            
            $args = array(
                'post_type' => 'blog',
                'posts_per_page' => 12,
                'paged' => $paged,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            
            if (!empty($filtro_categoria)) {
                $args['meta_query'] = array(
                    array(
                        'key' => 'blog_categoria',
                        'value' => $filtro_categoria,
                        'compare' => '='
                    )
                );
                
                // Mostrar título de categoría filtrada
                $cat_info = cc_get_blog_categoria_info();
                echo '<h2 class="filtro-titulo-activo">';
                echo '<i class="fas ' . $cat_info['icon'] . '"></i> ';
                echo esc_html($cat_info['label']);
                echo '</h2>';
            }
            
            $entradas_query = new WP_Query($args);
            
            if ($entradas_query->have_posts()): 
            ?>
                <div class="blog-entradas-grid">
                    <?php while ($entradas_query->have_posts()): $entradas_query->the_post(); 
                        $imagen = get_field('blog_imagen_destacada');
                        $categoria = cc_get_blog_categoria_info();
                        $resumen = get_field('blog_resumen');
                        $tiempo = cc_calcular_tiempo_lectura();
                        $urgente = get_field('blog_urgente');
                        $autor_info = cc_get_blog_autor_info();
                    ?>
                        <article class="blog-entrada-card <?php echo $urgente ? 'urgente' : ''; ?>">
                            <?php if ($urgente): ?>
                                <div class="urgente-badge">
                                    <i class="fas fa-exclamation-triangle"></i> URGENTE
                                </div>
                            <?php endif; ?>
                            
                            <div class="entrada-imagen-wrapper">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if ($imagen): ?>
                                        <img src="<?php echo esc_url($imagen['sizes']['medium_large'] ?? $imagen['url']); ?>" 
                                             alt="<?php echo esc_attr($imagen['alt']); ?>"
                                             loading="lazy">
                                    <?php endif; ?>
                                </a>
                                <div class="entrada-overlay"></div>
                                <span class="entrada-categoria-badge" style="background: <?php echo $categoria['color']; ?>;">
                                    <i class="fas <?php echo $categoria['icon']; ?>"></i>
                                </span>
                            </div>
                            
                            <div class="entrada-contenido">
                                <div class="entrada-meta-top">
                                    <span class="entrada-fecha">
                                        <i class="far fa-calendar"></i> <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="entrada-tiempo">
                                        <i class="far fa-clock"></i> <?php echo $tiempo; ?> min
                                    </span>
                                </div>
                                
                                <h3 class="entrada-titulo">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <?php if ($resumen): ?>
                                    <p class="entrada-resumen"><?php echo esc_html($resumen); ?></p>
                                <?php endif; ?>
                                
                                <div class="entrada-footer">
                                    <div class="entrada-autor">
                                        <i class="fas fa-user-tie"></i>
                                        <span><?php echo esc_html($autor_info['nombre']); ?></span>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="btn-leer-entrada">
                                        Leer más <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Paginación -->
                <?php if ($entradas_query->max_num_pages > 1): ?>
                    <div class="blog-paginacion">
                        <?php
                        $big = 999999999;
                        echo paginate_links(array(
                            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                            'format' => '?paged=%#%',
                            'current' => max(1, $paged),
                            'total' => $entradas_query->max_num_pages,
                            'prev_text' => '<i class="fas fa-chevron-left"></i>',
                            'next_text' => '<i class="fas fa-chevron-right"></i>',
                            'mid_size' => 2
                        ));
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php wp_reset_postdata(); ?>
                
            <?php else: ?>
                
                <div class="no-entradas-mensaje">
                    <div class="no-entradas-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h2>No hay entradas disponibles</h2>
                    <p>No se encontraron entradas en esta categoría.</p>
                    <a href="<?php echo get_post_type_archive_link('blog'); ?>" class="btn-volver">
                        <i class="fas fa-arrow-left"></i> Ver todas las entradas
                    </a>
                </div>
                
            <?php endif; ?>
            
        </div>
    </section>
    
    <!-- Newsletter / CTA (Opcional) -->
    <section class="blog-newsletter-section">
        <div class="container">
            <div class="newsletter-card">
                <div class="newsletter-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="newsletter-content">
                    <h3>Mantente Informado</h3>
                    <p>Suscríbete para recibir las últimas noticias y actualizaciones de la Casa de la Cultura</p>
                </div>
                <div class="newsletter-form">
                    <form method="post" action="#" class="newsletter-form-inline">
                        <input type="email" 
                               name="email" 
                               placeholder="Tu correo electrónico" 
                               required 
                               class="newsletter-input">
                        <button type="submit" class="newsletter-btn">
                            <i class="fas fa-paper-plane"></i> Suscribirse
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
</div>

<?php get_footer(); ?>