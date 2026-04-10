<?php
/**
 * Template para el archivo de noticias
 * Casa de la Cultura
 */

get_header(); ?>

<main id="main-content" class="site-main archivo-noticias">
    
    <header class="page-header">
        <h1 class="page-title"><i class="fas fa-newspaper"></i> Noticias de la Casa de la Cultura</h1>
        <p class="page-description">Manténte informado sobre todas nuestras actividades, eventos y novedades</p>
    </header>
    
    <?php
    // Filtros por categoría
    ?>
    <div class="noticias-filtros">
        <div class="filtros-container">
            <button class="filtro-btn active" data-categoria="todas">
                <i class="fas fa-layer-group"></i> Todas
            </button>
            <button class="filtro-btn" data-categoria="eventos">
                <i class="fas fa-calendar-star"></i> Eventos
            </button>
            <button class="filtro-btn" data-categoria="talleres">
                <i class="fas fa-chalkboard-teacher"></i> Talleres
            </button>
            <button class="filtro-btn" data-categoria="exposiciones">
                <i class="fas fa-image"></i> Exposiciones
            </button>
            <button class="filtro-btn" data-categoria="actividades">
                <i class="fas fa-music"></i> Actividades
            </button>
            <button class="filtro-btn" data-categoria="comunicados">
                <i class="fas fa-bullhorn"></i> Comunicados
            </button>
            <button class="filtro-btn" data-categoria="convocatorias">
                <i class="fas fa-clipboard-list"></i> Convocatorias
            </button>
        </div>
    </div>
    
    <?php if (have_posts()) : ?>
        
        <div class="noticias-grid" id="noticias-lista">
            <?php while (have_posts()) : the_post(); 
                
                $imagen = get_field('noticia_imagen_principal');
                $categoria = get_field('noticia_categoria');
                $resumen = get_field('noticia_resumen');
                $destacada = get_field('noticia_destacada');
                $urgente = get_field('noticia_urgente');
                
                $clase_destacada = $destacada ? ' noticia-destacada-item' : '';
                $clase_urgente = $urgente ? ' noticia-urgente-item' : '';
            ?>
                
                <article class="noticia-card<?php echo $clase_destacada . $clase_urgente; ?>" data-categoria="<?php echo esc_attr($categoria); ?>">
                    
                    <?php if ($urgente): ?>
                        <div class="badge-urgente"><i class="fas fa-exclamation-triangle"></i> URGENTE</div>
                    <?php endif; ?>
                    
                    <?php if ($imagen): ?>
                        <div class="noticia-card-imagen">
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url($imagen['sizes']['medium'] ?? $imagen['url']); ?>" 
                                     alt="<?php echo esc_attr($imagen['alt']); ?>"
                                     loading="lazy">
                            </a>
                            <?php if ($categoria): ?>
                                <span class="noticia-card-categoria categoria-<?php echo esc_attr($categoria); ?>">
                                    <?php 
                                    $categorias = array(
                                        'eventos' => 'Eventos',
                                        'talleres' => 'Talleres y Cursos',
                                        'exposiciones' => 'Exposiciones',
                                        'actividades' => 'Actividades Culturales',
                                        'comunicados' => 'Comunicados Oficiales',
                                        'convocatorias' => 'Convocatorias',
                                        'galeria' => 'Galería de Fotos',
                                        'premios' => 'Premios y Reconocimientos',
                                        'general' => 'General'
                                    );
                                    echo esc_html($categorias[$categoria] ?? $categoria);
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="noticia-card-contenido">
                        <h2 class="noticia-card-titulo">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        
                        <?php if ($resumen): ?>
                            <p class="noticia-card-resumen"><?php echo esc_html($resumen); ?></p>
                        <?php endif; ?>
                        
                        <div class="noticia-card-meta">
                            <span class="noticia-card-fecha">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo get_the_date('j M, Y'); ?>
                            </span>
                            
                            <a href="<?php the_permalink(); ?>" class="noticia-card-leer-mas">
                                Leer más
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    
                </article>
                
            <?php endwhile; ?>
        </div>
        
        <?php
        // Paginación
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => '← Anterior',
            'next_text' => 'Siguiente →',
            'class' => 'noticias-paginacion'
        ));
        ?>
        
    <?php else : ?>
        
        <div class="no-noticias">
            <i class="fas fa-newspaper" style="font-size: 80px; color: #ccc; margin-bottom: 20px;"></i>
            <h2>No hay noticias disponibles</h2>
            <p>Vuelve pronto para ver las últimas novedades</p>
        </div>
        
    <?php endif; ?>
    
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>