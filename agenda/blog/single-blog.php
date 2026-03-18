<?php
/**
 * Template para entrada de blog individual
 * Casa de la Cultura - Blog Institucional
 * Custom Post Type: blog
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); 
    
    // Obtener todos los campos
    $imagen_banner = get_field('blog_imagen_banner');
    $imagen_destacada = get_field('blog_imagen_destacada');
    $categoria = cc_get_blog_categoria_info();
    $resumen = get_field('blog_resumen');
    $subtitulo = get_field('blog_subtitulo');
    $urgente = get_field('blog_urgente');
    $destacada = get_field('blog_destacada');
    $tiempo_lectura = cc_calcular_tiempo_lectura();
    $autor_info = cc_get_blog_autor_info();
    $mostrar_autor = get_field('blog_mostrar_autor');
    $mostrar_relacionadas = get_field('blog_mostrar_relacionadas');
    $video = get_field('blog_video');
    $etiquetas = get_field('blog_etiquetas');
    
    // Campos condicionales de conmemoración
    $es_conmemoracion = (get_field('blog_categoria') === 'conmemoracion');
    $fecha_conmemoracion = get_field('blog_fecha_conmemoracion');
    $evento_historico = get_field('blog_evento_historico');
    
    // Campos condicionales de rendición de cuentas
    $es_rendicion = (get_field('blog_categoria') === 'rendicion_cuentas');
    $periodo_reporte = get_field('blog_periodo_reporte');
    $tipo_reporte = get_field('blog_tipo_reporte');
    $datos_estadisticos = get_field('blog_datos_estadisticos');
    $archivos = cc_get_blog_archivos();
    
    $enlace_externo = get_field('blog_enlace_externo');
?>

<article class="blog-entry-wrapper">
    
    
    <!-- Hero del Blog -->
    <header class="blog-hero" style="background-image: url('<?php echo esc_url($imagen_banner ? $imagen_banner['url'] : $imagen_destacada['url']); ?>');">
        <div class="blog-hero-overlay"></div>
        
        <div class="blog-badges-container">
            <?php if ($urgente): ?>
                <div class="blog-badge-urgente">
                    <i class="fas fa-bell"></i> <span>Urgente</span>
                </div>
            <?php endif; ?>
            
            <?php if ($destacada): ?>
                <div class="blog-badge-destacado">
                    <i class="fas fa-star"></i> <span>Destacado</span>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="container">
            <div class="blog-hero-content">
                
                <!-- Breadcrumb -->
                <div class="breadcrumb-blog">
                    <a href="<?php echo get_post_type_archive_link('blog'); ?>">
                        <i class="fas fa-home"></i> Blog
                    </a>
                    <span class="separator">/</span>
                    <span><?php echo esc_html($categoria['label']); ?></span>
                </div>
                
                <!-- Categoría Badge -->
                <div class="categoria-badge-hero" style="background: <?php echo $categoria['color']; ?>;">
                    <i class="fas <?php echo $categoria['icon']; ?>"></i>
                    <?php echo esc_html($categoria['label']); ?>
                </div>
                
                <!-- Título -->
                <h1 class="blog-hero-titulo"><?php the_title(); ?></h1>
                
                <!-- Subtítulo -->
                <?php if ($subtitulo): ?>
                    <p class="blog-hero-subtitulo"><?php echo esc_html($subtitulo); ?></p>
                <?php endif; ?>
                
                <!-- Meta Info -->
                <div class="blog-hero-meta">
                    <div class="meta-item-blog">
                        <i class="far fa-calendar"></i>
                        <span><?php echo get_the_date('j F, Y'); ?></span>
                    </div>
                    <div class="meta-item-blog">
                        <i class="far fa-clock"></i>
                        <span><?php echo $tiempo_lectura; ?> min de lectura</span>
                    </div>
                    <div class="meta-item-blog">
                        <i class="fas fa-user-tie"></i>
                        <span><?php echo esc_html($autor_info['nombre']); ?></span>
                    </div>
                </div>
                

                <!-- Rendición de Cuentas Info -->
                <?php if ($es_rendicion && ($periodo_reporte || $tipo_reporte)): ?>
                    <div class="rendicion-info-hero">
                        <i class="fas fa-chart-line"></i>
                        <?php if ($tipo_reporte): ?>
                            <strong>
                                <?php 
                                $tipos = array(
                                    'trimestral' => 'Reporte Trimestral',
                                    'semestral' => 'Reporte Semestral',
                                    'anual' => 'Reporte Anual',
                                    'proyecto' => 'Reporte de Proyecto',
                                    'financiero' => 'Reporte Financiero',
                                    'actividades' => 'Reporte de Actividades'
                                );
                                echo esc_html($tipos[$tipo_reporte] ?? $tipo_reporte);
                                ?>
                            </strong>
                        <?php endif; ?>
                        <?php if ($periodo_reporte): ?>
                            <span><?php echo esc_html($periodo_reporte); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </header>
    
    <!-- Contenedor Principal -->
    <div class="blog-contenedor">
        <div class="container">
            <div class="blog-layout">
                
                <!-- Contenido Principal -->
                <main class="blog-main">
                    
                    <!-- Resumen Destacado -->
                    <?php if ($resumen): ?>
                        <div class="blog-resumen-destacado">
                            <div class="resumen-card-header">
                                <span class="resumen-card-label"><i class="fas fa-align-left"></i> Resumen</span>
                                <i class="fas fa-quote-left resumen-quote-icon"></i>
                            </div>
                            <div class="resumen-card-body">
                                <p><?php echo esc_html($resumen); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Contenido del Post -->
                    <div class="blog-contenido">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Galería de Imágenes -->
                    <?php 
                    $galeria = cc_get_blog_galeria();
                    if (count($galeria) > 1): 
                    ?>
                        <section class="blog-galeria-section">
                            <h3 class="section-title-blog">
                                <i class="fas fa-images"></i> Galería de Imágenes
                            </h3>
                            <div class="blog-galeria-slider">
                                <div class="galeria-slider-blog">
                                    <?php foreach ($galeria as $index => $imagen): ?>
                                        <div class="galeria-slide-blog <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <img src="<?php echo esc_url($imagen['url']); ?>" 
                                                 alt="<?php echo esc_attr($imagen['alt']); ?>">
                                            <?php if (!empty($imagen['caption'])): ?>
                                                <div class="galeria-caption-blog">
                                                    <?php echo esc_html($imagen['caption']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <?php if (count($galeria) > 1): ?>
                                    <button class="galeria-btn-blog prev" onclick="cambiarSlideBlog(-1)">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="galeria-btn-blog next" onclick="cambiarSlideBlog(1)">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                    
                                    <div class="galeria-dots-blog">
                                        <?php foreach ($galeria as $index => $imagen): ?>
                                            <span class="dot-blog <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                  onclick="irASlideBlog(<?php echo $index; ?>)"></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Video -->
                    <?php if ($video): ?>
                        <section class="blog-video-section">
                            <h3 class="section-title-blog">
                                <i class="fas fa-video"></i> Video
                            </h3>
                            <div class="blog-video-container">
                                <?php echo $video; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Enlace Externo (si aplica) -->
                    <?php if ($enlace_externo): ?>
                        <section class="blog-cta-section" style="margin-bottom: 50px; text-align: center;">
                            <a href="<?php echo esc_url($enlace_externo); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="btn-cta-externo">
                                <i class="fas fa-external-link-alt"></i>
                                Recurso Externo Relacionado
                            </a>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Datos Estadísticos (Rendición de Cuentas) -->
                    <?php if ($es_rendicion && $datos_estadisticos): ?>
                        <section class="blog-estadisticas-section">
                            <div class="estadisticas-card">
                                <div class="estadisticas-card-header">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>Datos Estadísticos</span>
                                </div>
                                <div class="estadisticas-card-body">
                                    <?php echo $datos_estadisticos; ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Archivos Adjuntos (Rendición de Cuentas) -->
                    <?php if ($es_rendicion && !empty($archivos)): ?>
                        <section class="blog-archivos-section">
                            <h3 class="section-title-blog">
                                <i class="fas fa-file-arrow-down"></i> Documentos Adjuntos
                            </h3>
                            <div class="blog-archivos-grid">
                                <?php foreach ($archivos as $archivo_item): ?>
                                    <a href="<?php echo esc_url($archivo_item['archivo']['url']); ?>" 
                                       class="blog-archivo-card" 
                                       download 
                                       target="_blank">
                                        <div class="archivo-icon-blog">
                                            <?php 
                                            $extension = strtolower(pathinfo($archivo_item['archivo']['filename'], PATHINFO_EXTENSION));
                                            if ($extension === 'pdf'): ?>
                                                <i class="fas fa-file-pdf"></i>
                                            <?php elseif (in_array($extension, array('xls', 'xlsx'))): ?>
                                                <i class="fas fa-file-excel"></i>
                                            <?php elseif (in_array($extension, array('doc', 'docx'))): ?>
                                                <i class="fas fa-file-word"></i>
                                            <?php elseif (in_array($extension, array('ppt', 'pptx'))): ?>
                                                <i class="fas fa-file-powerpoint"></i>
                                            <?php else: ?>
                                                <i class="fas fa-file"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="archivo-info-blog">
                                            <h4><?php echo esc_html($archivo_item['titulo']); ?></h4>
                                            <span class="archivo-meta-blog">
                                                <?php echo strtoupper($extension); ?> • 
                                                <?php echo size_format($archivo_item['archivo']['filesize']); ?>
                                            </span>
                                        </div>
                                        <div class="archivo-download-blog">
                                            <i class="fas fa-download"></i>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Etiquetas -->
                    <?php if ($etiquetas && is_array($etiquetas) && count($etiquetas) > 0): ?>
                        <div class="blog-etiquetas-wrapper">
                            <strong><i class="fas fa-tags"></i> Etiquetas:</strong>
                            <div class="blog-etiquetas">
                                <?php 
                                $etiquetas_labels = array(
                                    'importante' => 'Importante',
                                    'urgente' => 'Urgente',
                                    'historico' => 'Histórico',
                                    'transparencia' => 'Transparencia',
                                    'comunidad' => 'Comunidad',
                                    'educacion' => 'Educación',
                                    'arte' => 'Arte',
                                    'musica' => 'Música',
                                    'teatro' => 'Teatro',
                                    'literatura' => 'Literatura',
                                    'patrimonio' => 'Patrimonio',
                                    'juventud' => 'Juventud',
                                    'inclusion' => 'Inclusión'
                                );
                                
                                foreach ($etiquetas as $etiqueta): 
                                    $label = $etiquetas_labels[$etiqueta] ?? $etiqueta;
                                ?>
                                    <span class="etiqueta-blog"><?php echo esc_html($label); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Autor Card -->
                    <?php if ($mostrar_autor): ?>
                        <div class="blog-autor-card">
                            <div class="autor-card-header">
                                <span class="autor-card-label"><i class="fas fa-pen-nib"></i> Sobre el Autor</span>
                                <div class="autor-avatar-blog">
                                    <?php if ($autor_info['foto']): ?>
                                        <?php if (is_array($autor_info['foto'])): ?>
                                            <img src="<?php echo esc_url($autor_info['foto']['url']); ?>" alt="<?php echo esc_attr($autor_info['nombre']); ?>">
                                        <?php else: ?>
                                            <img src="<?php echo esc_url($autor_info['foto']); ?>" alt="<?php echo esc_attr($autor_info['nombre']); ?>">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <i class="fas fa-user"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="autor-header-info">
                                    <h4><?php echo esc_html($autor_info['nombre']); ?></h4>
                                    <?php if ($autor_info['cargo']): ?>
                                        <span class="autor-cargo"><?php echo esc_html($autor_info['cargo']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($autor_info['bio']): ?>
                            <div class="autor-bio-body">
                                <p><?php echo esc_html($autor_info['bio']); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Navegación entre posts -->
                    <div class="blog-navegacion">
                        <div class="nav-post">
                            <?php 
                            $prev = get_previous_post();
                            if ($prev): 
                                $prev_imagen = get_field('blog_imagen_destacada', $prev->ID);
                            ?>
                                <a href="<?php echo get_permalink($prev->ID); ?>" class="nav-post-link prev">
                                    <?php if ($prev_imagen): ?>
                                        <div class="nav-post-thumb" style="background-image: url('<?php echo esc_url($prev_imagen['sizes']['thumbnail'] ?? $prev_imagen['url']); ?>');"></div>
                                    <?php endif; ?>
                                    <div class="nav-post-content">
                                        <span class="nav-label"><i class="fas fa-arrow-left"></i> Anterior</span>
                                        <span class="nav-title"><?php echo esc_html($prev->post_title); ?></span>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?php echo get_post_type_archive_link('blog'); ?>" class="nav-post-center">
                            <i class="fas fa-th"></i>
                        </a>
                        
                        <div class="nav-post">
                            <?php 
                            $next = get_next_post();
                            if ($next): 
                                $next_imagen = get_field('blog_imagen_destacada', $next->ID);
                            ?>
                                <a href="<?php echo get_permalink($next->ID); ?>" class="nav-post-link next">
                                    <div class="nav-post-content">
                                        <span class="nav-label">Siguiente <i class="fas fa-arrow-right"></i></span>
                                        <span class="nav-title"><?php echo esc_html($next->post_title); ?></span>
                                    </div>
                                    <?php if ($next_imagen): ?>
                                        <div class="nav-post-thumb" style="background-image: url('<?php echo esc_url($next_imagen['sizes']['thumbnail'] ?? $next_imagen['url']); ?>');"></div>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Entradas Relacionadas -->
                    <?php if ($mostrar_relacionadas): 
                        $relacionadas = cc_get_entradas_relacionadas(get_the_ID(), 3);
                        if ($relacionadas->have_posts()): 
                    ?>
                        <section class="blog-relacionadas-section">
                            <h3 class="section-title-blog">
                                <i class="fas fa-newspaper"></i> Entradas Relacionadas
                            </h3>
                            <div class="blog-relacionadas-grid">
                                <?php while ($relacionadas->have_posts()): $relacionadas->the_post(); 
                                    $rel_imagen = get_field('blog_imagen_destacada');
                                    $rel_categoria = cc_get_blog_categoria_info();
                                    $rel_resumen = get_field('blog_resumen');
                                ?>
                                    <article class="blog-relacionada-card">
                                        <?php if ($rel_imagen): ?>
                                            <div class="relacionada-imagen">
                                                <a href="<?php the_permalink(); ?>">
                                                    <img src="<?php echo esc_url($rel_imagen['sizes']['medium'] ?? $rel_imagen['url']); ?>" 
                                                         alt="<?php echo esc_attr($rel_imagen['alt']); ?>">
                                                </a>
                                                <span class="relacionada-categoria" style="background: <?php echo $rel_categoria['color']; ?>;">
                                                    <i class="fas <?php echo $rel_categoria['icon']; ?>"></i>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="relacionada-contenido">
                                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <?php if ($rel_resumen): ?>
                                                <p><?php echo esc_html(wp_trim_words($rel_resumen, 15)); ?></p>
                                            <?php endif; ?>
                                            <span class="relacionada-fecha">
                                                <i class="far fa-calendar"></i> <?php echo get_the_date(); ?>
                                            </span>
                                        </div>
                                    </article>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </section>
                    <?php else: ?>
                        <section class="blog-relacionadas-section">
                            <h3 class="section-title-blog">
                                <i class="fas fa-newspaper"></i> Entradas Relacionadas
                            </h3>
                            <div class="blog-sin-relacionadas" style="text-align: center; padding: 40px 20px; background: #f8f9fa; border-radius: 12px; border: 1px dashed #ced4da; color: #6c757d;">
                                <i class="fas fa-folder-open" style="font-size: 2.5rem; margin-bottom: 15px; color: #adb5bd; display: block;"></i>
                                <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No hay entradas relacionadas en esta categoría</p>
                                <p style="margin: 5px 0 0; font-size: 0.95rem; opacity: 0.8;">Continúa explorando nuestro blog para más contenido.</p>
                            </div>
                        </section>
                    <?php endif; endif; ?>
                    
                    <!-- Comentarios -->
                    <?php 
                    $permitir_comentarios = get_field('blog_permitir_comentarios');
                    if ($permitir_comentarios && (comments_open() || get_comments_number())): 
                    ?>
                        <div class="blog-comentarios-wrapper">
                            <?php comments_template(); ?>
                        </div>
                    <?php endif; ?>
                    
                </main>
                
                <!-- Sidebar -->
                <aside class="blog-sidebar">
                    
                    <!-- Categorías -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">
                            <i class="fas fa-folder-open"></i> Categorías
                        </h3>
                        <ul class="categorias-list-blog">
                            <?php 
                            $categorias_blog = array(
                                'mensaje_directora' => array('label' => 'Mensaje de la Directora', 'icon' => 'fa-envelope', 'color' => '#8e44ad'),
                                'conmemoracion' => array('label' => 'Conmemoración', 'icon' => 'fa-calendar-alt', 'color' => '#e74c3c'),
                                'rendicion_cuentas' => array('label' => 'Rendición de Cuentas', 'icon' => 'fa-chart-line', 'color' => '#16a085'),
                                'logros' => array('label' => 'Logros', 'icon' => 'fa-trophy', 'color' => '#f39c12'),
                                'proyectos' => array('label' => 'Proyectos', 'icon' => 'fa-lightbulb', 'color' => '#3498db'),
                                'reflexion' => array('label' => 'Reflexión Cultural', 'icon' => 'fa-brain', 'color' => '#9b59b6'),
                                'opinion' => array('label' => 'Opinión', 'icon' => 'fa-comments', 'color' => '#34495e'),
                                'general' => array('label' => 'General', 'icon' => 'fa-edit', 'color' => '#95a5a6')
                            );
                            
                            foreach ($categorias_blog as $cat_key => $cat_info):
                                $count = new WP_Query(array(
                                    'post_type' => 'blog',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'blog_categoria',
                                            'value' => $cat_key,
                                            'compare' => '='
                                        )
                                    ),
                                    'posts_per_page' => -1
                                ));
                                
                                if ($count->found_posts > 0):
                            ?>
                                <li>
                                    <a href="<?php echo add_query_arg('cat_blog', $cat_key, get_post_type_archive_link('blog')); ?>">
                                        <span class="cat-icon" style="background: <?php echo $cat_info['color']; ?>;">
                                            <i class="fas <?php echo $cat_info['icon']; ?>"></i>
                                        </span>
                                        <span class="cat-name"><?php echo esc_html($cat_info['label']); ?></span>
                                        <span class="cat-count"><?php echo $count->found_posts; ?></span>
                                    </a>
                                </li>
                            <?php 
                                endif;
                                wp_reset_postdata();
                            endforeach; 
                            ?>
                        </ul>
                    </div>
                    
                    <!-- Noticias Destacadas y Urgentes -->
                    <div class="sidebar-widget widget-noticias-tabs">
                        <div class="widget-tabs-header">
                            <button class="widget-tab-btn active" onclick="cambiarTabNoticias('urgentes')">
                                <i class="fas fa-exclamation-triangle"></i> Urgentes
                            </button>
                            <button class="widget-tab-btn" onclick="cambiarTabNoticias('destacadas')">
                                <i class="fas fa-star"></i> Destacadas
                            </button>
                        </div>
                        
                        <div class="widget-tabs-content">
                            <!-- Tab Urgentes -->
                            <div id="tab-urgentes" class="widget-tab-panel active">
                                <?php 
                                $noticias_urgentes = new WP_Query(array(
                                    'post_type' => 'noticia',
                                    'posts_per_page' => 15,
                                    'meta_query' => array(
                                        array(
                                            'key' => 'noticia_urgente',
                                            'value' => '1',
                                            'compare' => '='
                                        )
                                    ),
                                    'orderby' => 'date',
                                    'order' => 'DESC'
                                ));
                                
                                if ($noticias_urgentes->have_posts()):
                                ?>
                                    <ul class="entradas-recientes-list noticias-importantes-list scrollable-list">
                                        <?php while ($noticias_urgentes->have_posts()): $noticias_urgentes->the_post(); 
                                            $not_imagen = get_field('noticia_imagen_principal');
                                        ?>
                                            <li class="item-noticia-importante">
                                                <?php if ($not_imagen): ?>
                                                    <div class="reciente-thumb" style="background-image: url('<?php echo esc_url($not_imagen['sizes']['thumbnail'] ?? $not_imagen['url']); ?>');"></div>
                                                <?php elseif (has_post_thumbnail()): ?>
                                                    <div class="reciente-thumb" style="background-image: url('<?php echo get_the_post_thumbnail_url(null, 'thumbnail'); ?>');"></div>
                                                <?php endif; ?>
                                                <div class="reciente-info">
                                                    <span class="badge-importante urgente"><i class="fas fa-exclamation-triangle"></i> Urgente</span>
                                                    <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 8); ?></a>
                                                    <span class="reciente-fecha">
                                                        <i class="far fa-calendar"></i> <?php echo get_the_date('d M, Y'); ?>
                                                    </span>
                                                </div>
                                            </li>
                                        <?php endwhile; wp_reset_postdata(); ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="tab-empty-msg"><i class="fas fa-info-circle"></i> No hay noticias urgentes en este momento.</p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Tab Destacadas -->
                            <div id="tab-destacadas" class="widget-tab-panel">
                                <?php 
                                $noticias_destacadas = new WP_Query(array(
                                    'post_type' => 'noticia',
                                    'posts_per_page' => 15,
                                    'meta_query' => array(
                                        array(
                                            'key' => 'noticia_destacada',
                                            'value' => '1',
                                            'compare' => '='
                                        )
                                    ),
                                    'orderby' => 'date',
                                    'order' => 'DESC'
                                ));
                                
                                if ($noticias_destacadas->have_posts()):
                                ?>
                                    <ul class="entradas-recientes-list noticias-importantes-list scrollable-list">
                                        <?php while ($noticias_destacadas->have_posts()): $noticias_destacadas->the_post(); 
                                            $not_imagen = get_field('noticia_imagen_principal');
                                        ?>
                                            <li class="item-noticia-importante">
                                                <?php if ($not_imagen): ?>
                                                    <div class="reciente-thumb" style="background-image: url('<?php echo esc_url($not_imagen['sizes']['thumbnail'] ?? $not_imagen['url']); ?>');"></div>
                                                <?php elseif (has_post_thumbnail()): ?>
                                                    <div class="reciente-thumb" style="background-image: url('<?php echo get_the_post_thumbnail_url(null, 'thumbnail'); ?>');"></div>
                                                <?php endif; ?>
                                                <div class="reciente-info">
                                                    <span class="badge-importante destacada"><i class="fas fa-star"></i> Destacada</span>
                                                    <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 8); ?></a>
                                                    <span class="reciente-fecha">
                                                        <i class="far fa-calendar"></i> <?php echo get_the_date('d M, Y'); ?>
                                                    </span>
                                                </div>
                                            </li>
                                        <?php endwhile; wp_reset_postdata(); ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="tab-empty-msg"><i class="fas fa-info-circle"></i> No hay noticias destacadas en este momento.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Entradas Recientes -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">
                            <i class="fas fa-clock"></i> Recientes
                        </h3>
                        <?php 
                        $recientes = new WP_Query(array(
                            'post_type' => 'blog',
                            'posts_per_page' => 5,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        if ($recientes->have_posts()):
                        ?>
                            <ul class="entradas-recientes-list">
                                <?php while ($recientes->have_posts()): $recientes->the_post(); 
                                    $rec_imagen = get_field('blog_imagen_destacada');
                                ?>
                                    <li>
                                        <?php if ($rec_imagen): ?>
                                            <div class="reciente-thumb" style="background-image: url('<?php echo esc_url($rec_imagen['sizes']['thumbnail'] ?? $rec_imagen['url']); ?>');"></div>
                                        <?php endif; ?>
                                        <div class="reciente-info">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            <span class="reciente-fecha">
                                                <i class="far fa-calendar"></i> <?php echo get_the_date(); ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    
                    <!-- /End Sidebar Widgets -->
                    
                </aside>
                
            </div>
        </div>
    </div>
    
</article>

<!-- Widget de Compartir -->
<?php include(get_stylesheet_directory() . '/compartir-widget.php'); ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/compartir-widget-styles.css">
<script src="<?php echo get_stylesheet_directory_uri(); ?>/compartir-widget.js"></script>

<script>
// Slider de galería del blog
let slideActualBlog = 0;

function cambiarSlideBlog(direccion) {
    const slides = document.querySelectorAll('.galeria-slide-blog');
    const dots = document.querySelectorAll('.dot-blog');
    
    if (slides.length === 0) return;
    
    slides[slideActualBlog].classList.remove('active');
    if (dots.length > 0) dots[slideActualBlog].classList.remove('active');
    
    slideActualBlog = (slideActualBlog + direccion + slides.length) % slides.length;
    
    slides[slideActualBlog].classList.add('active');
    if (dots.length > 0) dots[slideActualBlog].classList.add('active');
}

function irASlideBlog(index) {
    const slides = document.querySelectorAll('.galeria-slide-blog');
    const dots = document.querySelectorAll('.dot-blog');
    
    if (slides.length === 0) return;
    
    slides[slideActualBlog].classList.remove('active');
    if (dots.length > 0) dots[slideActualBlog].classList.remove('active');
    
    slideActualBlog = index;
    
    slides[slideActualBlog].classList.add('active');
    if (dots.length > 0) dots[slideActualBlog].classList.add('active');
}

// Auto-avanzar galería
if (document.querySelectorAll('.galeria-slide-blog').length > 1) {
    setInterval(() => {
        cambiarSlideBlog(1);
    }, 5000);
}

// Tabs de Noticias Importantes
function cambiarTabNoticias(tabId) {
    // Actualizar botones
    document.querySelectorAll('.widget-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    
    // Actualizar paneles
    document.querySelectorAll('.widget-tab-panel').forEach(panel => {
        panel.classList.remove('active');
    });
    document.getElementById('tab-' + tabId).classList.add('active');
}
</script>

<?php endwhile; ?>

<?php get_footer(); ?>