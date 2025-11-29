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
    
    // SEO
    $meta_descripcion = get_field('blog_meta_descripcion');
    $enlace_externo = get_field('blog_enlace_externo');
?>

<article class="blog-entry-wrapper">
    
    <!-- Alert Urgente (si aplica) -->
    <?php if ($urgente): ?>
        <div class="blog-alert-urgente">
            <div class="container">
                <div class="alert-content-blog">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Comunicado Importante</strong>
                        <span>Esta es una publicación urgente que requiere atención inmediata</span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Hero del Blog -->
    <header class="blog-hero" style="background-image: linear-gradient(135deg, rgba(<?php echo hexdec(substr($categoria['color'],1,2)); ?>, <?php echo hexdec(substr($categoria['color'],3,2)); ?>, <?php echo hexdec(substr($categoria['color'],5,2)); ?>, 0.85) 0%, rgba(44, 62, 80, 0.9) 100%), url('<?php echo esc_url($imagen_banner ? $imagen_banner['url'] : $imagen_destacada['url']); ?>');">
        <div class="blog-hero-overlay"></div>
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
                
                <!-- Conmemoración Info -->
                <?php if ($es_conmemoracion && ($fecha_conmemoracion || $evento_historico)): ?>
                    <div class="conmemoracion-info-hero">
                        <i class="fas fa-calendar-star"></i>
                        <?php if ($evento_historico): ?>
                            <strong><?php echo esc_html($evento_historico); ?></strong>
                        <?php endif; ?>
                        <?php if ($fecha_conmemoracion): ?>
                            <span><?php echo date('j \d\e F \d\e Y', strtotime($fecha_conmemoracion)); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
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
                            <i class="fas fa-quote-left"></i>
                            <p><?php echo esc_html($resumen); ?></p>
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
                    
                    <!-- Datos Estadísticos (Rendición de Cuentas) -->
                    <?php if ($es_rendicion && $datos_estadisticos): ?>
                        <section class="blog-estadisticas-section">
                            <h3 class="section-title-blog">
                                <i class="fas fa-chart-bar"></i> Datos Estadísticos
                            </h3>
                            <div class="blog-estadisticas-contenido">
                                <?php echo $datos_estadisticos; ?>
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
                    
                    <!-- Compartir -->
                    <div class="blog-compartir-wrapper">
                        <strong><i class="fas fa-share-nodes"></i> Compartir:</strong>
                        <div class="blog-compartir-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                               target="_blank" 
                               class="share-btn facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                               target="_blank" 
                               class="share-btn twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" 
                               target="_blank" 
                               class="share-btn whatsapp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <button class="share-btn copiar" onclick="copiarEnlaceBlog()">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Autor Card -->
                    <?php if ($mostrar_autor): ?>
                        <div class="blog-autor-card">
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
                            <div class="autor-info-blog">
                                <h4><?php echo esc_html($autor_info['nombre']); ?></h4>
                                <?php if ($autor_info['cargo']): ?>
                                    <span class="autor-cargo"><?php echo esc_html($autor_info['cargo']); ?></span>
                                <?php endif; ?>
                                <?php if ($autor_info['bio']): ?>
                                    <p><?php echo esc_html($autor_info['bio']); ?></p>
                                <?php endif; ?>
                            </div>
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
                    
                    <!-- Buscador -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">
                            <i class="fas fa-search"></i> Buscar
                        </h3>
                        <form role="search" method="get" class="search-form-blog" action="<?php echo home_url('/'); ?>">
                            <input type="hidden" name="post_type" value="blog">
                            <input type="search" 
                                   class="search-field-blog" 
                                   placeholder="Buscar en el blog..." 
                                   name="s" 
                                   required>
                            <button type="submit" class="search-submit-blog">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Categorías -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">
                            <i class="fas fa-folder-open"></i> Categorías
                        </h3>
                        <ul class="categorias-list-blog">
                            <?php 
                            $categorias_blog = array(
                                'mensaje_directora' => array('label' => 'Mensaje de la Directora', 'icon' => 'fa-message', 'color' => '#8e44ad'),
                                'conmemoracion' => array('label' => 'Conmemoración', 'icon' => 'fa-calendar-star', 'color' => '#e74c3c'),
                                'rendicion_cuentas' => array('label' => 'Rendición de Cuentas', 'icon' => 'fa-chart-line', 'color' => '#16a085'),
                                'logros' => array('label' => 'Logros', 'icon' => 'fa-trophy', 'color' => '#f39c12'),
                                'proyectos' => array('label' => 'Proyectos', 'icon' => 'fa-lightbulb', 'color' => '#3498db'),
                                'reflexion' => array('label' => 'Reflexión Cultural', 'icon' => 'fa-brain', 'color' => '#9b59b6'),
                                'opinion' => array('label' => 'Opinión', 'icon' => 'fa-comments', 'color' => '#34495e'),
                                'general' => array('label' => 'General', 'icon' => 'fa-pen-to-square', 'color' => '#95a5a6')
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
                    
                    <!-- Enlace Externo (si aplica) -->
                    <?php if ($enlace_externo): ?>
                        <div class="sidebar-widget widget-enlace-externo">
                            <a href="<?php echo esc_url($enlace_externo); ?>" target="_blank" class="enlace-externo-card">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Recurso Externo Relacionado</span>
                                <small>Visitar enlace</small>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                </aside>
                
            </div>
        </div>
    </div>
    
</article>

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

// Copiar enlace
function copiarEnlaceBlog() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(function() {
        const btn = event.target.closest('.share-btn');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.background = '#27ae60';
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.style.background = '';
        }, 2000);
    }, function(err) {
        console.error('Error al copiar: ', err);
        alert('No se pudo copiar el enlace');
    });
}
</script>

<?php endwhile; ?>

<?php get_footer(); ?>