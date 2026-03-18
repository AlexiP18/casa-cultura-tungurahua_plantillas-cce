<?php
/**
 * Template para mostrar una noticia individual
 * Casa de la Cultura
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); 
    
    // Obtener todos los campos
    $imagen_principal = get_field('noticia_imagen_principal');
    $credito_imagen = get_field('noticia_credito_imagen');
    $categoria = get_field('noticia_categoria');
    $subtitulo = get_field('noticia_subtitulo');
    $resumen = get_field('noticia_resumen');
    $noticia_urgente = get_field('noticia_urgente');
    $noticia_destacada = get_field('noticia_destacada');
    $contenido_adicional = get_field('noticia_contenido_adicional');
    $video = get_field('noticia_video');
    $enlace_externo = get_field('noticia_enlace_externo');
    $etiquetas = get_field('noticia_etiquetas');
    
    // Imágenes de la galería
    $imagenes_galeria = cc_get_galeria_imagenes();
    
    // Archivos adjuntos
    $archivos = cc_get_archivos_adjuntos();
?>

<article class="noticia-wrapper">
    
    <!-- Hero Section -->
    <div class="noticia-hero" style="background-image: url('<?php echo esc_url($imagen_principal['url']); ?>');">
        <div class="hero-overlay"></div>
        
        <?php if ($noticia_urgente || $noticia_destacada): ?>
            <div class="noticia-badges-container">
                <?php if ($noticia_urgente): ?>
                    <div class="noticia-badge-urgente">
                        <i class="fas fa-bell"></i> <span>Urgente</span>
                    </div>
                <?php endif; ?>

                <?php if ($noticia_destacada): ?>
                    <div class="noticia-badge-destacado">
                        <i class="fas fa-star"></i> <span>Destacado</span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="hero-content">
            <div class="container">
                
                <!-- Breadcrumb -->
                <nav class="breadcrumb-noticia">
                    <a href="<?php echo get_post_type_archive_link('noticia'); ?>">
                        <i class="fas fa-home"></i> Noticias
                    </a>
                    <span class="separator">/</span>
                    <span>
                        <?php 
                        if ($categoria) {
                            $categorias = array(
                                'eventos' => 'Eventos',
                                'talleres' => 'Talleres',
                                'exposiciones' => 'Exposiciones',
                                'actividades' => 'Actividades',
                                'comunicados' => 'Comunicados',
                                'convocatorias' => 'Convocatorias',
                                'galeria' => 'Galería',
                                'premios' => 'Premios',
                                'general' => 'General'
                            );
                            echo $categorias[$categoria] ?? $categoria;
                        }
                        ?>
                    </span>
                </nav>



                <?php 
                // Categoría
                if ($categoria): 
                    $categoria_label = '';
                    $categoria_icono = '';
                    $categorias = array(
                        'eventos' => array('label' => 'Eventos', 'icono' => 'fa-calendar-alt'),
                        'talleres' => array('label' => 'Talleres y Cursos', 'icono' => 'fa-chalkboard-teacher'),
                        'exposiciones' => array('label' => 'Exposiciones', 'icono' => 'fa-image'),
                        'actividades' => array('label' => 'Actividades Culturales', 'icono' => 'fa-music'),
                        'comunicados' => array('label' => 'Comunicados Oficiales', 'icono' => 'fa-bullhorn'),
                        'convocatorias' => array('label' => 'Convocatorias', 'icono' => 'fa-clipboard-list'),
                        'galeria' => array('label' => 'Galería de Fotos', 'icono' => 'fa-images'),
                        'premios' => array('label' => 'Premios y Reconocimientos', 'icono' => 'fa-trophy'),
                        'general' => array('label' => 'General', 'icono' => 'fa-newspaper')
                    );
                    
                    if (isset($categorias[$categoria])) {
                        $categoria_label = $categorias[$categoria]['label'];
                        $categoria_icono = $categorias[$categoria]['icono'];
                    } else {
                        $categoria_label = $categoria;
                        $categoria_icono = 'fa-newspaper';
                    }
                ?>
                    <span class="hero-categoria cat-<?php echo esc_attr($categoria); ?>">
                        <i class="fas <?php echo esc_attr($categoria_icono); ?>"></i>
                        <?php echo esc_html($categoria_label); ?>
                    </span>
                <?php endif; ?>
                
                <h1 class="hero-titulo"><?php the_title(); ?></h1>
                
                <?php if ($subtitulo): ?>
                    <p class="hero-subtitulo"><?php echo esc_html($subtitulo); ?></p>
                <?php endif; ?>
                
                <div class="hero-meta">
                    <span class="meta-item-badge">
                        <i class="far fa-calendar-alt"></i>
                        <?php echo get_the_date('j \d\e F, Y'); ?>
                    </span>
                    
                    <span class="meta-item-badge">
                        <i class="far fa-user"></i>
                        <?php the_author(); ?>
                    </span>
                    
                    <span class="meta-item-badge">
                        <i class="far fa-clock"></i>
                        <?php echo cc_tiempo_lectura(get_the_content()); ?> min
                    </span>
                </div>
            </div>
        </div>
        
        <?php if ($credito_imagen): ?>
            <span class="hero-credito"><i class="fas fa-camera"></i> <?php echo esc_html($credito_imagen); ?></span>
        <?php endif; ?>
    </div>
    
    <!-- Artículo -->
    <div class="noticia-articulo">
        <div class="container-articulo">
            
            <!-- Contenido principal del artículo -->
            <div class="articulo-contenido-wrapper">
                <div class="articulo-contenido">
                
                <?php if ($resumen): ?>
                    <div class="lead-paragraph">
                        <div class="lead-card-header">
                            <span class="lead-card-label"><i class="fas fa-align-left"></i> Resumen</span>
                            <i class="fas fa-quote-left lead-quote-icon"></i>
                        </div>
                        <div class="lead-card-body">
                            <p><?php echo esc_html($resumen); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="texto-articulo">
                    <?php the_content(); ?>
                </div>
                
                <?php if ($contenido_adicional): ?>
                    <div class="contenido-extra">
                        <h3 class="section-title"><i class="fas fa-info-circle"></i> Información Adicional</h3>
                        <?php echo $contenido_adicional; ?>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Galería de imágenes (slider)
                if (!empty($imagenes_galeria)): 
                ?>
                    <div class="slider-imagenes-section">
                        <h3 class="section-title"><i class="fas fa-images"></i> Galería de Imágenes</h3>
                        <div class="noticia-galeria-slider">
                            <div class="galeria-slider-noticia">
                                <?php foreach ($imagenes_galeria as $index => $imagen): ?>
                                    <div class="galeria-slide-noticia <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <img src="<?php echo esc_url($imagen['url']); ?>" 
                                             alt="<?php echo esc_attr($imagen['alt']); ?>">
                                        <?php if (!empty($imagen['caption'])): ?>
                                            <div class="slide-caption-noticia"><?php echo esc_html($imagen['caption']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($imagenes_galeria) > 1): ?>
                                <button class="galeria-btn-noticia prev" aria-label="Anterior" onclick="cambiarSlideNoticia(-1)">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="galeria-btn-noticia next" aria-label="Siguiente" onclick="cambiarSlideNoticia(1)">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                                <div class="galeria-dots-noticia">
                                    <?php foreach ($imagenes_galeria as $index => $imagen): ?>
                                        <span class="dot-noticia <?php echo $index === 0 ? 'active' : ''; ?>" onclick="irASlideNoticia(<?php echo $index; ?>)"></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Video incrustado
                if ($video): 
                ?>
                    <div class="video-section">
                        <h3 class="section-title"><i class="fas fa-play-circle"></i> Video</h3>
                        <div class="video-container">
                            <?php echo $video; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Archivos descargables
                if (!empty($archivos)): 
                ?>
                    <div class="archivos-section">
                        <h3 class="section-title"><i class="fas fa-download"></i> Archivos Descargables</h3>
                        <div class="archivos-grid">
                            <?php foreach ($archivos as $item): 
                                $archivo = $item['archivo'];
                                $titulo = $item['titulo'];
                                $extension = strtolower(pathinfo($archivo['filename'], PATHINFO_EXTENSION));
                                
                                // Determinar icono según extensión
                                $icono_clase = 'fa-file';
                                if (in_array($extension, ['pdf'])) $icono_clase = 'fa-file-pdf';
                                elseif (in_array($extension, ['doc', 'docx'])) $icono_clase = 'fa-file-word';
                                elseif (in_array($extension, ['xls', 'xlsx'])) $icono_clase = 'fa-file-excel';
                                elseif (in_array($extension, ['ppt', 'pptx'])) $icono_clase = 'fa-file-powerpoint';
                                elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) $icono_clase = 'fa-file-image';
                                elseif (in_array($extension, ['zip', 'rar', '7z'])) $icono_clase = 'fa-file-archive';
                                elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) $icono_clase = 'fa-file-audio';
                                elseif (in_array($extension, ['mp4', 'avi', 'mov'])) $icono_clase = 'fa-file-video';
                            ?>
                                <a href="<?php echo esc_url($archivo['url']); ?>" 
                                   download 
                                   class="archivo-card">
                                    <div class="file-icon file-<?php echo esc_attr($extension); ?>">
                                        <i class="fas <?php echo $icono_clase; ?>"></i>
                                        <span class="extension"><?php echo esc_html(strtoupper($extension)); ?></span>
                                    </div>
                                    <div class="archivo-info-card">
                                        <h4><?php echo esc_html($titulo); ?></h4>
                                        <p class="archivo-size"><?php echo size_format($archivo['filesize']); ?></p>
                                    </div>
                                    <i class="fas fa-download download-icon"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Botón CTA para enlace externo
                if ($enlace_externo): 
                ?>
                    <div class="cta-section">
                        <a href="<?php echo esc_url($enlace_externo); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="btn-cta">
                            <i class="fas fa-external-link-alt"></i>
                            Más información
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Tags/Etiquetas
                if ($etiquetas && is_array($etiquetas)): 
                ?>
                    <div class="tags-section">
                        <h4 class="tags-title"><i class="fas fa-tags"></i> Etiquetas</h4>
                        <div class="tags-container">
                            <?php 
                            $etiquetas_labels = array(
                                'musica' => 'Música',
                                'teatro' => 'Teatro',
                                'danza' => 'Danza',
                                'literatura' => 'Literatura',
                                'artes-visuales' => 'Artes Visuales',
                                'fotografia' => 'Fotografía',
                                'cine' => 'Cine',
                                'artesanias' => 'Artesanías',
                                'gastronomia' => 'Gastronomía',
                                'ninos' => 'Para Niños',
                                'jovenes' => 'Para Jóvenes',
                                'adultos' => 'Para Adultos',
                                'tercera-edad' => 'Tercera Edad',
                                'familia' => 'Para toda la Familia',
                                'gratuito' => 'Gratuito',
                                'inscripcion' => 'Requiere Inscripción',
                                'certificado' => 'Otorga Certificado'
                            );
                            
                            foreach ($etiquetas as $etiqueta): 
                                $label = $etiquetas_labels[$etiqueta] ?? $etiqueta;
                            ?>
                                <span class="tag"><?php echo esc_html($label); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Navegación entre noticias -->
                <div class="noticia-navegacion">
                    <div class="nav-noticia-item">
                        <?php 
                        $prev = get_previous_post();
                        if ($prev): 
                            $prev_imagen = get_field('noticia_imagen_principal', $prev->ID) ?: get_the_post_thumbnail_url($prev->ID, 'thumbnail');
                        ?>
                            <a href="<?php echo get_permalink($prev->ID); ?>" class="nav-noticia-link prev">
                                <?php if ($prev_imagen): ?>
                                    <div class="nav-noticia-thumb" style="background-image: url('<?php echo esc_url(is_array($prev_imagen) ? ($prev_imagen['sizes']['thumbnail'] ?? $prev_imagen['url']) : $prev_imagen); ?>');"></div>
                                <?php endif; ?>
                                <div class="nav-noticia-content">
                                    <span class="nav-label"><i class="fas fa-arrow-left"></i> Anterior</span>
                                    <span class="nav-title"><?php echo esc_html($prev->post_title); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?php echo get_post_type_archive_link('noticia'); ?>" class="nav-noticia-center">
                        <i class="fas fa-th"></i>
                    </a>
                    
                    <div class="nav-noticia-item">
                        <?php 
                        $next = get_next_post();
                        if ($next): 
                            $next_imagen = get_field('noticia_imagen_principal', $next->ID) ?: get_the_post_thumbnail_url($next->ID, 'thumbnail');
                        ?>
                            <a href="<?php echo get_permalink($next->ID); ?>" class="nav-noticia-link next">
                                <div class="nav-noticia-content">
                                    <span class="nav-label">Siguiente <i class="fas fa-arrow-right"></i></span>
                                    <span class="nav-title"><?php echo esc_html($next->post_title); ?></span>
                                </div>
                                <?php if ($next_imagen): ?>
                                    <div class="nav-noticia-thumb" style="background-image: url('<?php echo esc_url(is_array($next_imagen) ? ($next_imagen['sizes']['thumbnail'] ?? $next_imagen['url']) : $next_imagen); ?>');"></div>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Noticias Relacionadas -->
                <?php 
                $relacionadas = cc_get_noticias_relacionadas(get_the_ID(), 3);
                if ($relacionadas->have_posts()): 
                ?>
                    <section class="blog-relacionadas-section">
                        <h3 class="section-title-blog">
                            <i class="fas fa-newspaper"></i> Noticias Relacionadas
                        </h3>
                        <div class="blog-relacionadas-grid">
                            <?php while ($relacionadas->have_posts()): $relacionadas->the_post(); 
                                $rel_imagen = get_field('noticia_imagen_principal');
                                $rel_categoria = get_field('noticia_categoria');
                                $rel_resumen = get_field('noticia_resumen');
                                
                                $categorias_data = array(
                                    'eventos' => array('label' => 'Eventos', 'icon' => 'fa-calendar-alt', 'color' => '#8e44ad'),
                                    'talleres' => array('label' => 'Talleres', 'icon' => 'fa-chalkboard-teacher', 'color' => '#e74c3c'),
                                    'exposiciones' => array('label' => 'Exposiciones', 'icon' => 'fa-image', 'color' => '#16a085'),
                                    'actividades' => array('label' => 'Actividades Culturales', 'icon' => 'fa-music', 'color' => '#f39c12'),
                                    'comunicados' => array('label' => 'Comunicados', 'icon' => 'fa-bullhorn', 'color' => '#3498db'),
                                    'convocatorias' => array('label' => 'Convocatorias', 'icon' => 'fa-clipboard-list', 'color' => '#9b59b6'),
                                    'galeria' => array('label' => 'Galería', 'icon' => 'fa-images', 'color' => '#34495e'),
                                    'premios' => array('label' => 'Premios', 'icon' => 'fa-trophy', 'color' => '#f1c40f'),
                                    'general' => array('label' => 'General', 'icon' => 'fa-newspaper', 'color' => '#95a5a6')
                                );
                                $cat_info = isset($categorias_data[$rel_categoria]) ? $categorias_data[$rel_categoria] : $categorias_data['general'];
                            ?>
                                <article class="blog-relacionada-card">
                                    <?php if ($rel_imagen): ?>
                                        <div class="relacionada-imagen">
                                            <a href="<?php the_permalink(); ?>">
                                                <img src="<?php echo esc_url($rel_imagen['sizes']['medium'] ?? $rel_imagen['url']); ?>" 
                                                     alt="<?php echo esc_attr($rel_imagen['alt']); ?>">
                                            </a>
                                            <span class="relacionada-categoria" style="background: <?php echo $cat_info['color']; ?>;">
                                                <i class="fas <?php echo $cat_info['icon']; ?>"></i>
                                            </span>
                                        </div>
                                    <?php elseif (has_post_thumbnail()): ?>
                                        <div class="relacionada-imagen">
                                            <a href="<?php the_permalink(); ?>">
                                                <img src="<?php echo get_the_post_thumbnail_url(null, 'medium'); ?>" 
                                                     alt="<?php the_title_attribute(); ?>">
                                            </a>
                                            <span class="relacionada-categoria" style="background: <?php echo $cat_info['color']; ?>;">
                                                <i class="fas <?php echo $cat_info['icon']; ?>"></i>
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
                            <i class="fas fa-newspaper"></i> Noticias Relacionadas
                        </h3>
                        <div class="blog-sin-relacionadas" style="text-align: center; padding: 40px 20px; background: #f8f9fa; border-radius: 12px; border: 1px dashed #ced4da; color: #6c757d;">
                            <i class="fas fa-folder-open" style="font-size: 2.5rem; margin-bottom: 15px; color: #adb5bd; display: block;"></i>
                            <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No hay noticias relacionadas en esta categoría</p>
                            <p style="margin: 5px 0 0; font-size: 0.95rem; opacity: 0.8;">Continúa explorando para más actualizaciones.</p>
                        </div>
                    </section>
                <?php endif; ?>
                
            </div>
            <!-- Fin Contenido Principal -->
            
            <!-- Sidebar de Información -->
            <aside class="noticia-sidebar-info">
                
                <?php 
                // Categorías de noticias
                $categorias_noticias = array(
                    'eventos' => array(
                        'label' => 'Eventos',
                        'icono' => 'fa-calendar-alt'
                    ),
                    'talleres' => array(
                        'label' => 'Talleres y Cursos',
                        'icono' => 'fa-chalkboard-teacher'
                    ),
                    'exposiciones' => array(
                        'label' => 'Exposiciones',
                        'icono' => 'fa-image'
                    ),
                    'actividades' => array(
                        'label' => 'Actividades Culturales',
                        'icono' => 'fa-music'
                    ),
                    'comunicados' => array(
                        'label' => 'Comunicados Oficiales',
                        'icono' => 'fa-bullhorn'
                    ),
                    'convocatorias' => array(
                        'label' => 'Convocatorias',
                        'icono' => 'fa-clipboard-list'
                    ),
                    'galeria' => array(
                        'label' => 'Galería de Fotos',
                        'icono' => 'fa-images'
                    ),
                    'premios' => array(
                        'label' => 'Premios y Reconocimientos',
                        'icono' => 'fa-trophy'
                    ),
                    'general' => array(
                        'label' => 'General',
                        'icono' => 'fa-newspaper'
                    ),
                );
                ?>
                
                <!-- Widget Categorías -->
                <div class="sidebar-widget widget-categorias">
                    <h3 class="widget-title">
                        <i class="fas fa-folder-open"></i> Categorías
                    </h3>
                    <ul class="categorias-list-sidebar">
                        <?php 
                        foreach ($categorias_noticias as $cat_key => $cat_data) :
                            // Contar noticias por categoría
                            $count_query = new WP_Query(array(
                                'post_type' => 'noticia',
                                'post_status' => 'publish',
                                'meta_query' => array(
                                    array(
                                        'key' => 'noticia_categoria',
                                        'value' => $cat_key,
                                        'compare' => '='
                                    )
                                ),
                                'posts_per_page' => -1
                            ));
                            
                            if ($count_query->found_posts > 0):
                        ?>
                            <li>
                                <a href="<?php echo add_query_arg('categoria', $cat_key, get_post_type_archive_link('noticia')); ?>" class="cat-link-sidebar">
                                    <span class="cat-icon">
                                        <i class="fas <?php echo esc_attr($cat_data['icono']); ?>"></i>
                                    </span>
                                    <span class="cat-name"><?php echo esc_html($cat_data['label']); ?></span>
                                    <span class="cat-count"><?php echo $count_query->found_posts; ?></span>
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
                    <div class="widget-noticias-header">
                        <div class="widget-tabs-header">
                            <button class="widget-tab-btn active" onclick="cambiarTabNoticias('urgentes')">
                                <i class="fas fa-exclamation-triangle"></i> Urgentes
                            </button>
                            <button class="widget-tab-btn" onclick="cambiarTabNoticias('destacadas')">
                                <i class="fas fa-star"></i> Destacadas
                            </button>
                        </div>
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
                
                <!-- Widget Noticias Recientes -->
                <div class="sidebar-widget widget-recientes">
                    <h3 class="widget-title">
                        <i class="fas fa-clock"></i> Recientes
                    </h3>
                    <?php 
                    $recientes = new WP_Query(array(
                        'post_type' => 'noticia',
                        'posts_per_page' => 5,
                        'post__not_in' => array(get_the_ID()),
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    if ($recientes->have_posts()):
                    ?>
                        <ul class="recientes-list">
                            <?php while ($recientes->have_posts()): $recientes->the_post(); 
                                $imagen = get_field('noticia_imagen_principal');
                            ?>
                                <li class="reciente-item">
                                    <?php if ($imagen): ?>
                                        <a href="<?php the_permalink(); ?>" class="reciente-thumb" style="background-image: url('<?php echo esc_url($imagen['sizes']['thumbnail'] ?? $imagen['url']); ?>');"></a>
                                    <?php elseif (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>" class="reciente-thumb" style="background-image: url('<?php echo get_the_post_thumbnail_url(null, 'thumbnail'); ?>');"></a>
                                    <?php endif; ?>
                                    <div class="reciente-info">
                                        <a href="<?php the_permalink(); ?>" class="reciente-titulo"><?php the_title(); ?></a>
                                        <span class="reciente-fecha">
                                            <i class="far fa-calendar"></i> <?php echo get_the_date('d M, Y'); ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    <?php endif; ?>
                </div>
                
            </aside>
            <!-- Fin Sidebar -->
            
            </div>
        </div>
    </div>
    
</article>

<!-- Widget de Compartir -->
<?php include(get_stylesheet_directory() . '/compartir-widget.php'); ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/compartir-widget-styles.css">
<script src="<?php echo get_stylesheet_directory_uri(); ?>/compartir-widget.js"></script>

<script>
// ===== SLIDER DE IMÁGENES =====
let slideActualNoticia = 0;

function cambiarSlideNoticia(direccion) {
    const slides = document.querySelectorAll('.galeria-slide-noticia');
    const dots = document.querySelectorAll('.dot-noticia');
    
    if (slides.length === 0) return;
    
    slides[slideActualNoticia].classList.remove('active');
    if (dots.length > 0) dots[slideActualNoticia].classList.remove('active');
    
    slideActualNoticia = (slideActualNoticia + direccion + slides.length) % slides.length;
    
    slides[slideActualNoticia].classList.add('active');
    if (dots.length > 0) dots[slideActualNoticia].classList.add('active');
}

function irASlideNoticia(index) {
    const slides = document.querySelectorAll('.galeria-slide-noticia');
    const dots = document.querySelectorAll('.dot-noticia');
    
    if (slides.length === 0) return;
    
    slides[slideActualNoticia].classList.remove('active');
    if (dots.length > 0) dots[slideActualNoticia].classList.remove('active');
    
    slideActualNoticia = index;
    
    slides[slideActualNoticia].classList.add('active');
    if (dots.length > 0) dots[slideActualNoticia].classList.add('active');
}

// Interacción auto y animaciones de entrada en Scroll
document.addEventListener('DOMContentLoaded', function() {
    // Auto-avanzar galería
    if (document.querySelectorAll('.galeria-slide-noticia').length > 1) {
        let slideInterval = setInterval(() => {
            cambiarSlideNoticia(1);
        }, 5000);
        
        // Pausar en hover
        const sliderContainer = document.querySelector('.noticia-galeria-slider');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => clearInterval(slideInterval));
            sliderContainer.addEventListener('mouseleave', () => {
                slideInterval = setInterval(() => {
                    cambiarSlideNoticia(1);
                }, 5000);
            });
        }
    }

    // Animación de entrada para elementos
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.section-title, .archivo-card, .tag').forEach(el => {
        observer.observe(el);
    });
});

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