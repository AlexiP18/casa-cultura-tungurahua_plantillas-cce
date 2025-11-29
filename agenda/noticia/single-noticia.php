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
    
    <!-- Alert Urgente -->
    <?php if ($noticia_urgente): ?>
        <div class="alert-urgente">
            <div class="container-alert">
                <span class="alert-icon">⚠️</span>
                <div class="alert-content">
                    <strong>NOTICIA URGENTE</strong>
                    <p>Esta es una comunicación importante</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Hero Section -->
    <div class="noticia-hero" style="background-image: url('<?php echo esc_url($imagen_principal['url']); ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container">
                <?php 
                // Categoría
                if ($categoria): 
                    $categoria_label = '';
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
                    $categoria_label = $categorias[$categoria] ?? $categoria;
                ?>
                    <span class="hero-categoria cat-<?php echo esc_attr($categoria); ?>">
                        <?php echo esc_html($categoria_label); ?>
                    </span>
                <?php endif; ?>
                
                <h1 class="hero-titulo"><?php the_title(); ?></h1>
                
                <?php if ($subtitulo): ?>
                    <p class="hero-subtitulo"><?php echo esc_html($subtitulo); ?></p>
                <?php endif; ?>
                
                <div class="hero-meta">
                    <span class="meta-item">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                        </svg>
                        <?php echo get_the_date('j \d\e F \d\e Y'); ?>
                    </span>
                    
                    <span class="meta-item">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        </svg>
                        <?php the_author(); ?>
                    </span>
                </div>
            </div>
        </div>
        
        <?php if ($credito_imagen): ?>
            <span class="hero-credito">📷 <?php echo esc_html($credito_imagen); ?></span>
        <?php endif; ?>
    </div>
    
    <!-- Artículo -->
    <div class="noticia-articulo">
        <div class="container-articulo">
            
            <!-- Sidebar de acciones -->
            <aside class="sidebar-acciones">
                <button class="accion-btn" id="btn-compartir" aria-label="Compartir">
                    <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
                    </svg>
                </button>
                
                <div class="compartir-popup" id="compartir-popup">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                       target="_blank" 
                       class="share-link facebook">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                        </svg>
                    </a>
                    
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                       target="_blank" 
                       class="share-link twitter">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                        </svg>
                    </a>
                    
                    <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" 
                       target="_blank" 
                       class="share-link whatsapp">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                        </svg>
                    </a>
                </div>
                
                <button class="accion-btn" onclick="window.print()" aria-label="Imprimir">
                    <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                    </svg>
                </button>
            </aside>
            
            <!-- Contenido principal del artículo -->
            <div class="articulo-contenido">
                
                <?php if ($resumen): ?>
                    <div class="lead-paragraph">
                        <p><?php echo esc_html($resumen); ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="texto-articulo">
                    <?php the_content(); ?>
                </div>
                
                <?php if ($contenido_adicional): ?>
                    <div class="contenido-extra">
                        <h3 class="section-title">Información Adicional</h3>
                        <?php echo $contenido_adicional; ?>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Galería de imágenes (slider)
                if (!empty($imagenes_galeria)): 
                ?>
                    <div class="slider-imagenes-section">
                        <h3 class="section-title">Galería de Imágenes</h3>
                        <div class="imagen-slider">
                            <div class="slider-container">
                                <?php foreach ($imagenes_galeria as $index => $imagen): ?>
                                    <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <img src="<?php echo esc_url($imagen['url']); ?>" 
                                             alt="<?php echo esc_attr($imagen['alt']); ?>">
                                        <?php if (!empty($imagen['caption'])): ?>
                                            <div class="slide-caption"><?php echo esc_html($imagen['caption']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($imagenes_galeria) > 1): ?>
                                <button class="slider-btn prev" aria-label="Anterior">
                                    <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                </button>
                                <button class="slider-btn next" aria-label="Siguiente">
                                    <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </button>
                                <div class="slider-dots">
                                    <?php foreach ($imagenes_galeria as $index => $imagen): ?>
                                        <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></span>
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
                        <h3 class="section-title">Video</h3>
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
                        <h3 class="section-title">Archivos Descargables</h3>
                        <div class="archivos-grid">
                            <?php foreach ($archivos as $item): 
                                $archivo = $item['archivo'];
                                $titulo = $item['titulo'];
                                $extension = pathinfo($archivo['filename'], PATHINFO_EXTENSION);
                            ?>
                                <a href="<?php echo esc_url($archivo['url']); ?>" 
                                   download 
                                   class="archivo-card">
                                    <div class="file-icon file-<?php echo esc_attr(strtolower($extension)); ?>">
                                        <span class="extension"><?php echo esc_html(strtoupper($extension)); ?></span>
                                    </div>
                                    <div class="archivo-info-card">
                                        <h4><?php echo esc_html($titulo); ?></h4>
                                        <p class="archivo-size"><?php echo size_format($archivo['filesize']); ?></p>
                                    </div>
                                    <svg class="download-icon" width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                    </svg>
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
                            Más información
                            <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Tags/Etiquetas
                if ($etiquetas && is_array($etiquetas)): 
                ?>
                    <div class="tags-section">
                        <h4 class="tags-title">Etiquetas</h4>
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
                <nav class="nav-noticias">
                    <div class="nav-grid">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        
                        if ($prev_post):
                            $prev_thumb = get_the_post_thumbnail_url($prev_post, 'medium');
                        ?>
                            <a href="<?php echo get_permalink($prev_post); ?>" class="nav-card">
                                <?php if ($prev_thumb): ?>
                                    <div class="nav-image" style="background-image: url('<?php echo esc_url($prev_thumb); ?>');"></div>
                                <?php endif; ?>
                                <div class="nav-content">
                                    <span class="nav-label">← Noticia Anterior</span>
                                    <h4><?php echo get_the_title($prev_post); ?></h4>
                                </div>
                            </a>
                        <?php else: ?>
                            <div class="nav-empty"></div>
                        <?php endif; ?>
                        
                        <a href="<?php echo get_post_type_archive_link('noticia'); ?>" class="nav-archive">
                            <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
                            </svg>
                            <span>Ver todas las noticias</span>
                        </a>
                        
                        <?php 
                        if ($next_post):
                            $next_thumb = get_the_post_thumbnail_url($next_post, 'medium');
                        ?>
                            <a href="<?php echo get_permalink($next_post); ?>" class="nav-card">
                                <?php if ($next_thumb): ?>
                                    <div class="nav-image" style="background-image: url('<?php echo esc_url($next_thumb); ?>');"></div>
                                <?php endif; ?>
                                <div class="nav-content">
                                    <span class="nav-label">Siguiente Noticia →</span>
                                    <h4><?php echo get_the_title($next_post); ?></h4>
                                </div>
                            </a>
                        <?php else: ?>
                            <div class="nav-empty"></div>
                        <?php endif; ?>
                    </div>
                </nav>
                
            </div>
        </div>
    </div>
    
</article>

<script>
// Script para compartir
document.getElementById('btn-compartir')?.addEventListener('click', function() {
    document.getElementById('compartir-popup')?.classList.toggle('show');
});

// Slider de imágenes
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
const prevBtn = document.querySelector('.slider-btn.prev');
const nextBtn = document.querySelector('.slider-btn.next');
let currentSlide = 0;

function showSlide(n) {
    if (slides.length === 0) return;
    
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    if (n >= slides.length) currentSlide = 0;
    if (n < 0) currentSlide = slides.length - 1;
    
    slides[currentSlide]?.classList.add('active');
    dots[currentSlide]?.classList.add('active');
}

prevBtn?.addEventListener('click', () => {
    currentSlide--;
    showSlide(currentSlide);
});

nextBtn?.addEventListener('click', () => {
    currentSlide++;
    showSlide(currentSlide);
});

dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        currentSlide = index;
        showSlide(currentSlide);
    });
});
</script>

<?php endwhile; ?>

<?php get_footer(); ?>