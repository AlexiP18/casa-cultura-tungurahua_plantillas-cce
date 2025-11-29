<?php
/**
 * Template para el archivo de noticias
 * Casa de la Cultura
 */

get_header(); ?>

<main id="main-content" class="site-main archivo-noticias">
    
    <header class="page-header">
        <h1 class="page-title">📰 Noticias de la Casa de la Cultura</h1>
        <p class="page-description">Mantente informado sobre todas nuestras actividades, eventos y novedades</p>
    </header>
    
    <?php
    // Filtros por categoría
    ?>
    <div class="noticias-filtros">
        <div class="filtros-container">
            <button class="filtro-btn active" data-categoria="todas">Todas</button>
            <button class="filtro-btn" data-categoria="eventos">Eventos</button>
            <button class="filtro-btn" data-categoria="talleres">Talleres</button>
            <button class="filtro-btn" data-categoria="exposiciones">Exposiciones</button>
            <button class="filtro-btn" data-categoria="actividades">Actividades</button>
            <button class="filtro-btn" data-categoria="comunicados">Comunicados</button>
            <button class="filtro-btn" data-categoria="convocatorias">Convocatorias</button>
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
                        <div class="badge-urgente">⚠️ URGENTE</div>
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
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                </svg>
                                <?php echo get_the_date('j M, Y'); ?>
                            </span>
                            
                            <a href="<?php the_permalink(); ?>" class="noticia-card-leer-mas">
                                Leer más
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                                </svg>
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
            <svg width="80" height="80" viewBox="0 0 16 16" fill="currentColor">
                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                <path d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z"/>
            </svg>
            <h2>No hay noticias disponibles</h2>
            <p>Vuelve pronto para ver las últimas novedades</p>
        </div>
        
    <?php endif; ?>
    
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>