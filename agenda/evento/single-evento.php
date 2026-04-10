<?php
/**
 * Template para evento individual - ACTUALIZADO
 * Casa de la Cultura - Eventos Culturales
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); 
    
    // Obtener todos los campos
    $imagen_banner = get_field('evento_imagen_banner');
    $imagen_principal = get_field('evento_imagen_principal');
    $tipo = get_field('evento_tipo');
    $estado = cc_get_estado_evento();
    $destacado = get_field('evento_destacado');
    $subtitulo = get_field('evento_subtitulo');
    $fecha_formateada = cc_get_fecha_evento_formateada();
    $fecha_inicio = get_field('evento_fecha_inicio');
    $fecha_fin = get_field('evento_fecha_fin');
    $duracion = get_field('evento_duracion');
    $lugar = get_field('evento_lugar');
    $direccion = get_field('evento_direccion');
    $mapa = get_field('evento_mapa');
    $precio = cc_get_precio_evento();
    $requiere_inscripcion = get_field('evento_requiere_inscripcion');
    $enlace_inscripcion = get_field('evento_enlace_inscripcion');
    $capacidad = get_field('evento_capacidad_total');
    $ocupacion = cc_calcular_ocupacion();
    $edad_minima = get_field('evento_edad_minima');
    $artista = get_field('evento_artista');
    $artista_bio = get_field('evento_artista_bio');
    $multiples_fechas = get_field('evento_multiples_fechas');
    $fechas_adicionales = get_field('evento_fechas_adicionales');
    $programa = get_field('evento_programa');
    $requisitos = cc_get_requisitos_evento(); // ACTUALIZADO - función helper
    $incluye = cc_get_incluye_evento(); // ACTUALIZADO - función helper
    $video = get_field('evento_video');
    $video_2 = get_field('evento_video_2');
    $video_3 = get_field('evento_video_3');
    $organizador = get_field('evento_organizador');
    $patrocinadores = get_field('evento_patrocinadores'); // grupo ACF
    $contacto_nombre = get_field('evento_contacto_nombre');
    $contacto_telefono = get_field('evento_contacto_telefono');
    $contacto_email = get_field('evento_contacto_email');
    $facebook = get_field('evento_redes_facebook');
    $instagram = get_field('evento_redes_instagram');
    $twitter = get_field('evento_redes_twitter');
    $youtube = get_field('evento_redes_youtube');
    $tiktok = get_field('evento_redes_tiktok');
    $enlace_externo = get_field('evento_enlace_externo');
    $etiquetas = get_field('evento_etiquetas');
    
    // Archivos
    $archivo_programa = get_field('evento_archivo_programa');
    $archivo_adicional = get_field('evento_archivo_adicional');
    
    // Tipos de evento con iconos
    $tipos_evento = array(
        'teatro' => array('label' => 'Teatro', 'icon' => '<i class="fas fa-theater-masks"></i>'),
        'musica' => array('label' => 'Música y Conciertos', 'icon' => '<i class="fas fa-music"></i>'),
        'danza' => array('label' => 'Danza', 'icon' => '<i class="fas fa-running"></i>'),
        'exposicion' => array('label' => 'Exposición', 'icon' => '<i class="fas fa-palette"></i>'),
        'taller' => array('label' => 'Taller', 'icon' => '<i class="fas fa-paint-brush"></i>'),
        'conferencia' => array('label' => 'Conferencia', 'icon' => '<i class="fas fa-microphone-alt"></i>'),
        'conversatorio' => array('label' => 'Conversatorio', 'icon' => '<i class="fas fa-comments"></i>'),
        'cine' => array('label' => 'Cine', 'icon' => '<i class="fas fa-film"></i>'),
        'literario' => array('label' => 'Evento Literario', 'icon' => '<i class="fas fa-book-open"></i>'),
        'concurso' => array('label' => 'Concurso', 'icon' => '<i class="fas fa-trophy"></i>'),
        'festival' => array('label' => 'Festival', 'icon' => '<i class="fas fa-flag"></i>'),
        'otro' => array('label' => 'Otro', 'icon' => '<i class="fas fa-calendar-day"></i>')
    );
    
    $tipo_info = $tipos_evento[$tipo] ?? $tipos_evento['otro'];

    // URL del listado de eventos: prioriza la página con template de listado.
    $eventos_listado_url = get_post_type_archive_link('evento');
    $eventos_pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'agenda/evento/page-listado-eventos.php',
        'number' => 1,
    ));

    if (!empty($eventos_pages)) {
        $eventos_listado_url = get_permalink($eventos_pages[0]->ID);
    } else {
        $eventos_page_by_path = get_page_by_path('home/agenda-cultural/eventos');
        if ($eventos_page_by_path instanceof WP_Post) {
            $eventos_listado_url = get_permalink($eventos_page_by_path->ID);
        }
    }
?>

<div class="evento-wrapper">
    
    <!-- Hero del Evento -->
    <section class="evento-hero" style="background-image: url('<?php echo esc_url($imagen_banner ? $imagen_banner['url'] : $imagen_principal['url']); ?>');">
        <div class="evento-hero-overlay"></div>
        
        <?php if ($destacado): ?>
            <div class="evento-badge-destacado">
                <i class="fas fa-star"></i> <span>Destacado</span>
            </div>
        <?php endif; ?>
        
        <div class="container">
            <div class="evento-hero-content">
                
                <!-- Breadcrumb -->
                <div class="breadcrumb-evento">
                    <a href="<?php echo esc_url($eventos_listado_url); ?>">
                        <i class="fas fa-home"></i> Eventos
                    </a>
                    <span class="separator">/</span>
                    <span><?php echo esc_html($tipo_info['label']); ?></span>
                </div>
                
                <!-- Badges superiores -->
                <div class="evento-badges-top">
                    <span class="badge-tipo">
                        <?php echo $tipo_info['icon']; ?> <?php echo esc_html($tipo_info['label']); ?>
                    </span>
                    <span class="badge-estado" style="background: <?php echo $estado['color']; ?>;">
                        <?php echo $estado['icon']; ?> <?php echo esc_html($estado['label']); ?>
                    </span>
                </div>
                
                <h1 class="evento-hero-titulo"><?php the_title(); ?></h1>
                
                <?php if ($subtitulo): ?>
                    <p class="evento-hero-subtitulo"><?php echo esc_html($subtitulo); ?></p>
                <?php endif; ?>
                
                <!-- Meta info del evento -->
                <div class="evento-hero-meta">
                    <div class="meta-item-hero">
                        <i class="far fa-calendar"></i>
                        <span><?php echo $fecha_formateada; ?></span>
                    </div>
                    
                    <?php if ($lugar): ?>
                    <div class="meta-item-hero">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo esc_html($lugar); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="meta-item-hero precio-hero">
                        <i class="fas fa-ticket-alt"></i>
                        <span class="precio-valor"><?php echo esc_html($precio['texto']); ?></span>
                    </div>
                </div>
                

                
            </div>
        </div>
        
    </section>
    
    <!-- Contenido del Evento -->
    <div class="evento-contenedor">
        <div class="container">
            <div class="evento-layout">
                
                <!-- Contenido Principal -->
                <main class="evento-main">
                    
                    <!-- Descripción -->
                    <section class="evento-section">
                        <h2 class="section-title-evento"><i class="fas fa-info-circle"></i> Sobre el Evento</h2>
                        <div class="evento-descripcion">
                            <?php the_content(); ?>
                        </div>
                    </section>
                    
                    <!-- Artista/Ponente -->
                    <?php if ($artista): ?>
                        <section class="evento-section artista-section">
                            <h2 class="section-title-evento"><i class="fas fa-user"></i> <?php echo $tipo === 'taller' ? 'Tallerista' : ($tipo === 'conferencia' || $tipo === 'conversatorio' ? 'Ponente' : 'Artista'); ?></h2>
                            <div class="artista-box">
                                <h3><?php echo esc_html($artista); ?></h3>
                                <?php if ($artista_bio): ?>
                                    <p><?php echo nl2br(esc_html($artista_bio)); ?></p>
                                <?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Fechas Múltiples -->
                    <?php
                    // Construir lista de fechas adicionales desde el grupo ACF
                    $fechas_lista = array();
                    if ($multiples_fechas && is_array($fechas_adicionales)) {
                        for ($fi = 1; $fi <= 20; $fi++) {
                            $f_dt = isset($fechas_adicionales['fecha_' . $fi . '_dt']) ? $fechas_adicionales['fecha_' . $fi . '_dt'] : '';
                            if ($f_dt) {
                                $f_nombre = isset($fechas_adicionales['fecha_' . $fi . '_nombre']) ? $fechas_adicionales['fecha_' . $fi . '_nombre'] : 'Fecha #' . $fi;
                                $fechas_lista[] = array('nombre' => $f_nombre, 'dt' => $f_dt);
                            }
                        }
                    }
                    ?>
                    <?php if ($multiples_fechas && !empty($fechas_lista)): ?>
                        <section class="evento-section">
                            <h2 class="section-title-evento"><i class="far fa-calendar-alt"></i> Fechas del Evento</h2>
                            <div class="fechas-multiples">

                                <div class="fecha-principal-box">
                                    <div class="fecha-principal-label"><i class="fas fa-calendar-check"></i> Fecha Principal</div>
                                    <div class="fecha-principal-valor"><?php echo $fecha_formateada; ?></div>
                                </div>

                                <?php if (!empty($fechas_lista)): ?>
                                <div class="fechas-adicionales-box">
                                    <div class="fechas-adicionales-header">
                                        <i class="far fa-calendar-plus"></i>
                                        Fechas Adicionales
                                        <span class="fechas-count-badge"><?php echo count($fechas_lista); ?></span>
                                    </div>
                                    <div class="fechas-adicionales-grid">
                                        <?php foreach ($fechas_lista as $fi => $fecha_item): ?>
                                            <div class="fecha-adicional-card">
                                                <span class="fecha-adicional-numero"><?php echo $fi + 1; ?></span>
                                                <div class="fecha-adicional-info">
                                                    <span class="fecha-adicional-nombre"><?php echo esc_html($fecha_item['nombre']); ?></span>
                                                    <span class="fecha-adicional-dt">
                                                        <i class="far fa-clock"></i>
                                                        <?php echo date_i18n('j M Y', strtotime($fecha_item['dt'])); ?>
                                                        &nbsp;<strong><?php echo date_i18n('H:i', strtotime($fecha_item['dt'])); ?></strong>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Programa -->
                    <?php if ($programa): ?>
                        <section class="evento-section">
                            <h2 class="section-title-evento"><i class="fas fa-list-alt"></i> Programa</h2>
                            <div class="evento-programa">
                                <?php echo $programa; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Slider de Imágenes -->
                    <?php 
                    $imagenes_slider = cc_get_slider_evento();
                    if (count($imagenes_slider) > 1): 
                    ?>
                        <section class="evento-section">
                            <h2 class="section-title-evento"><i class="fas fa-images"></i> Galería</h2>
                            <div class="evento-slider">
                                <div class="slider-container-evento">
                                    <?php foreach ($imagenes_slider as $index => $imagen): ?>
                                        <div class="slide-evento <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <img src="<?php echo esc_url($imagen['url']); ?>" 
                                                 alt="<?php echo esc_attr($imagen['alt']); ?>">
                                            <?php if (!empty($imagen['caption'])): ?>
                                                <div class="slide-caption-evento">
                                                    <?php echo esc_html($imagen['caption']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <button class="slider-btn-evento prev" onclick="cambiarSlideEvento(-1)">
                                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                </button>
                                <button class="slider-btn-evento next" onclick="cambiarSlideEvento(1)">
                                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </button>
                                
                                <div class="slider-dots-evento">
                                    <?php foreach ($imagenes_slider as $index => $imagen): ?>
                                        <span class="dot-evento <?php echo $index === 0 ? 'active' : ''; ?>" 
                                              onclick="irASlideEvento(<?php echo $index; ?>)"></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Videos -->
                    <?php if ($video || $video_2 || $video_3): ?>
                        <section class="evento-section">
                            <h2 class="section-title-evento"><i class="fas fa-play-circle"></i> Video<?php echo ($video_2 || $video_3) ? 's' : ''; ?></h2>
                            <div class="videos-grid <?php echo ($video_2 || $video_3) ? 'videos-multiples' : ''; ?>">
                                <?php if ($video): ?>
                                    <div class="evento-video">
                                        <?php echo $video; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($video_2): ?>
                                    <div class="evento-video">
                                        <?php echo $video_2; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($video_3): ?>
                                    <div class="evento-video">
                                        <?php echo $video_3; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Requisitos e Incluye - ACTUALIZADO -->
                    <?php if ($requisitos || $incluye): ?>
                        <section class="evento-section">
                            <div class="req-incluye-grid">
                                <?php if ($requisitos && is_array($requisitos) && count($requisitos) > 0): ?>
                                    <div class="requisitos-box">
                                        <h3><i class="fas fa-check-circle"></i> Requisitos</h3>
                                        <ul class="lista-checks">
                                            <?php foreach ($requisitos as $req): ?>
                                                <li><?php echo esc_html($req); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($incluye && is_array($incluye) && count($incluye) > 0): ?>
                                    <div class="incluye-box">
                                        <h3><i class="fas fa-gift"></i> ¿Qué Incluye?</h3>
                                        <ul class="lista-checks">
                                            <?php foreach ($incluye as $item): ?>
                                                <li><?php echo esc_html($item); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Archivos -->
                    <?php if ($archivo_programa || $archivo_adicional): ?>
                        <section class="evento-section">
                            <h2 class="section-title-evento"><i class="fas fa-paperclip"></i> Archivos</h2>
                            <div class="evento-archivos">
                                <?php if ($archivo_programa): ?>
                                    <a href="<?php echo esc_url($archivo_programa['url']); ?>" class="archivo-evento-card" download target="_blank">
                                        <div class="archivo-icon-evento pdf">
                                            <span>PDF</span>
                                        </div>
                                        <div class="archivo-info-evento">
                                            <h4>Programa del Evento</h4>
                                            <span><?php echo size_format($archivo_programa['filesize']); ?></span>
                                        </div>
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($archivo_adicional): ?>
                                    <a href="<?php echo esc_url($archivo_adicional['url']); ?>" class="archivo-evento-card" download target="_blank">
                                        <div class="archivo-icon-evento">
                                            <span><?php echo strtoupper(pathinfo($archivo_adicional['filename'], PATHINFO_EXTENSION)); ?></span>
                                        </div>
                                        <div class="archivo-info-evento">
                                            <h4>Archivo Adicional</h4>
                                            <span><?php echo size_format($archivo_adicional['filesize']); ?></span>
                                        </div>
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Etiquetas -->
                    <?php if ($etiquetas && is_array($etiquetas)): ?>
                        <section class="evento-section">
                            <h2 class="section-title-evento"><i class="fas fa-tags"></i> Etiquetas</h2>
                            <div class="evento-etiquetas">
                                <?php 
                                $etiquetas_labels = array(
                                    'familiar' => 'Para toda la familia',
                                    'adultos' => 'Solo adultos',
                                    'ninos' => 'Para niños',
                                    'jovenes' => 'Para jóvenes',
                                    'profesional' => 'Profesional',
                                    'principiantes' => 'Principiantes',
                                    'avanzado' => 'Nivel avanzado',
                                    'certificado' => 'Otorga certificado',
                                    'al_aire_libre' => 'Al aire libre',
                                    'virtual' => 'Virtual/Online',
                                    'presencial' => 'Presencial'
                                );
                                
                                foreach ($etiquetas as $etiqueta): 
                                    $label = $etiquetas_labels[$etiqueta] ?? $etiqueta;
                                ?>
                                    <span class="tag-evento"><?php echo esc_html($label); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <!-- En móvil: aquí se reubican dinámicamente las cards de Información y Organización -->
                    <div class="evento-mobile-info-slot" id="evento-mobile-info-slot"></div>
                    
                    <!-- Navegación entre eventos -->
                    <div class="evento-navegacion">
                        <div class="nav-evento-item">
                            <?php 
                            $prev = get_previous_post();
                            if ($prev): 
                                $prev_imagen = get_field('evento_imagen_principal', $prev->ID);
                            ?>
                                <a href="<?php echo get_permalink($prev->ID); ?>" class="nav-evento-link prev">
                                    <?php if ($prev_imagen): ?>
                                        <div class="nav-evento-thumb" style="background-image: url('<?php echo esc_url($prev_imagen['sizes']['thumbnail'] ?? $prev_imagen['url']); ?>');"></div>
                                    <?php endif; ?>
                                    <div class="nav-evento-content">
                                        <span class="nav-label"><i class="fas fa-arrow-left"></i> Anterior</span>
                                        <span class="nav-title"><?php echo esc_html($prev->post_title); ?></span>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?php echo esc_url($eventos_listado_url); ?>" class="nav-evento-center">
                            <i class="fas fa-th"></i>
                        </a>
                        
                        <div class="nav-evento-item">
                            <?php 
                            $next = get_next_post();
                            if ($next): 
                                $next_imagen = get_field('evento_imagen_principal', $next->ID);
                            ?>
                                <a href="<?php echo get_permalink($next->ID); ?>" class="nav-evento-link next">
                                    <div class="nav-evento-content">
                                        <span class="nav-label">Siguiente <i class="fas fa-arrow-right"></i></span>
                                        <span class="nav-title"><?php echo esc_html($next->post_title); ?></span>
                                    </div>
                                    <?php if ($next_imagen): ?>
                                        <div class="nav-evento-thumb" style="background-image: url('<?php echo esc_url($next_imagen['sizes']['thumbnail'] ?? $next_imagen['url']); ?>');"></div>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Eventos Relacionados -->
                    <?php 
                    $relacionados = new WP_Query(array(
                        'post_type' => 'evento',
                        'posts_per_page' => 3,
                        'post__not_in' => array(get_the_ID()),
                        'meta_query' => array(
                            array(
                                'key' => 'evento_tipo',
                                'value' => $tipo,
                                'compare' => '='
                            )
                        ),
                        'orderby' => 'rand'
                    ));
                    
                    if ($relacionados->have_posts()): 
                    ?>
                        <section class="evento-section relacionades-section">
                            <h2 class="section-title-evento"><i class="fas fa-calendar-check"></i> Eventos Relacionados</h2>
                            <div class="eventos-grid relacionados-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 25px; margin-bottom: 30px;">
                                <?php while ($relacionados->have_posts()): $relacionados->the_post(); 
                                    $rel_imagen = get_field('evento_imagen_principal');
                                    $rel_fecha = get_field('evento_fecha_inicio');
                                ?>
                                    <div class="evento-relacionado-card" style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eee; transition: transform 0.3s ease;">
                                        <?php if ($rel_imagen): ?>
                                            <div class="rel-img" style="height: 180px; overflow: hidden;">
                                                <a href="<?php the_permalink(); ?>">
                                                    <img src="<?php echo esc_url($rel_imagen['sizes']['medium'] ?? $rel_imagen['url']); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <div class="rel-content" style="padding: 20px;">
                                            <h4 style="font-size: 16px; font-weight: 700; margin: 0 0 12px; line-height: 1.4;"><a href="<?php the_permalink(); ?>" style="color: #2c3e50; text-decoration: none;"><?php echo wp_trim_words(get_the_title(), 12); ?></a></h4>
                                            <?php if ($rel_fecha): ?>
                                                <div style="font-size: 13px; color: #666; display: flex; align-items: center; gap: 6px; font-weight: 500;">
                                                    <i class="far fa-calendar-alt" style="color: #3498db;"></i> <?php echo date('d M, Y', strtotime($rel_fecha)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </section>
                    <?php else: ?>
                        <section class="evento-section relacionades-section">
                            <h2 class="section-title-evento"><i class="fas fa-calendar-check"></i> Eventos Relacionados</h2>
                            <div class="blog-sin-relacionadas" style="text-align: center; padding: 40px 20px; background: #f8f9fa; border-radius: 16px; border: 1px dashed #ced4da; color: #6c757d;">
                                <i class="fas fa-folder-open" style="font-size: 2.5rem; margin-bottom: 15px; color: #adb5bd; display: block;"></i>
                                <p style="margin: 0; font-size: 1.1rem; font-weight: 500;">No hay eventos relacionados de este tipo</p>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                </main>
                
                <!-- Sidebar -->
                <aside class="evento-sidebar">
                    
                    <!-- Card de Información -->
                    <div class="evento-card-info" id="evento-card-info-principal">
                        <h3><i class="fas fa-info-circle"></i> Información</h3>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="far fa-calendar-alt"></i></div>
                            <div class="info-content">
                                <strong>Fecha</strong>
                                <span><?php echo date('j M Y', strtotime($fecha_inicio)); ?></span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="far fa-clock"></i></div>
                            <div class="info-content">
                                <strong>Hora</strong>
                                <span><?php echo date('H:i', strtotime($fecha_inicio)); ?></span>
                            </div>
                        </div>
                        
                        <?php if ($duracion): ?>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-hourglass-half"></i></div>
                                <div class="info-content">
                                    <strong>Duración</strong>
                                    <span><?php echo esc_html($duracion); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="info-content">
                                <strong>Lugar</strong>
                                <span><?php echo esc_html($lugar); ?></span>
                                <?php if ($direccion): ?>
                                    <small><?php echo esc_html($direccion); ?></small>
                                <?php endif; ?>
                                <?php if ($mapa): ?>
                                    <a href="<?php echo esc_url($mapa); ?>" target="_blank" class="btn-mapa">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z"/>
                                        </svg>
                                        Ver en Google Maps
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="info-item precio-item">
                            <div class="info-icon"><i class="fas fa-ticket-alt"></i></div>
                            <div class="info-content">
                                <strong>Precio</strong>
                                <span class="precio-grande"><?php echo esc_html($precio['texto']); ?></span>
                                <?php if (!empty($precio['precios_lista']) && is_array($precio['precios_lista'])): ?>
                                    <div class="precios-diferenciados-sidebar">
                                        <?php foreach ($precio['precios_lista'] as $pc): ?>
                                            <?php if (!empty($pc['nombre'])): ?>
                                                <div class="precio-cat-row">
                                                    <span class="precio-cat-nombre"><?php echo esc_html($pc['nombre']); ?></span>
                                                    <span class="precio-cat-valor"><?php echo $pc['valor'] !== '' ? '$' . number_format((float)$pc['valor'], 2) : 'Consultar'; ?></span>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($ocupacion): ?>
                            <div class="info-item cupos-item">
                                <div class="info-icon"><i class="fas fa-users"></i></div>
                                <div class="info-content">
                                    <strong>Cupos Disponibles</strong>
                                    <div class="cupos-barra">
                                        <div class="cupos-progreso" style="width: <?php echo $ocupacion['porcentaje']; ?>%;"></div>
                                    </div>
                                    <span><?php echo $ocupacion['disponibles']; ?> de <?php echo $ocupacion['capacidad']; ?> disponibles</span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // edad_minima es tipo range (0-18). 0 = Apto para todo público.
                        $edad_minima_int = (int) $edad_minima;
                        $edad_label = $edad_minima_int === 0 ? 'Apto para todo público' : '+' . $edad_minima_int . ' años';
                        $edad_icon = $edad_minima_int >= 18 ? 'fas fa-user-shield' : ($edad_minima_int >= 12 ? 'fas fa-user-graduate' : ($edad_minima_int >= 7 ? 'fas fa-child' : 'fas fa-baby'));
                        ?>
                        <?php if (isset($edad_minima) && $edad_minima !== '' && $edad_minima !== null): ?>
                            <div class="info-item">
                                <div class="info-icon"><i class="<?php echo $edad_icon; ?>"></i></div>
                                <div class="info-content">
                                    <strong>Clasificación de Edad</strong>
                                    <span class="edad-badge <?php echo $edad_minima_int >= 18 ? 'mayor-edad' : ($edad_minima_int === 0 ? 'todo-publico' : ''); ?>"><?php echo esc_html($edad_label); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($requiere_inscripcion && $enlace_inscripcion && $estado['label'] !== 'Finalizado'): ?>
                            <a href="<?php echo esc_url($enlace_inscripcion); ?>" target="_blank" class="btn-inscripcion-sidebar">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                </svg>
                                Inscríbete Ahora
                            </a>
                        <?php endif; ?>
                        
                    </div>
                    
                    <!-- Card de Organización -->
                    <?php if ($organizador || $contacto_nombre || $facebook || $instagram || $twitter || $youtube || $tiktok || !empty($patrocinadores)): ?>
                        <div class="evento-card-info" id="evento-card-info-organizacion">
                            <h3><i class="fas fa-building"></i> Organización</h3>
                            
                            <?php if ($organizador): ?>
                                <div class="info-item">
                                    <div class="info-icon"><i class="fas fa-user-tie"></i></div>
                                    <div class="info-content">
                                        <strong>Organizado por</strong>
                                        <span><?php echo esc_html($organizador); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Construir lista de patrocinadores desde el grupo ACF
                            $patrocinadores_lista = array();
                            if (is_array($patrocinadores)) {
                                for ($pi = 1; $pi <= 10; $pi++) {
                                    $p_nombre = isset($patrocinadores['patroc_' . $pi . '_nombre']) ? trim($patrocinadores['patroc_' . $pi . '_nombre']) : '';
                                    $p_link   = isset($patrocinadores['patroc_' . $pi . '_link'])   ? trim($patrocinadores['patroc_' . $pi . '_link'])   : '';
                                    if ($p_nombre !== '') {
                                        $patrocinadores_lista[] = array('nombre' => $p_nombre, 'link' => $p_link);
                                    }
                                }
                            }
                            ?>
                            <?php if (!empty($patrocinadores_lista)): ?>
                                <div class="info-item patrocinadores-item">
                                    <div class="info-icon"><i class="fas fa-handshake"></i></div>
                                    <div class="info-content">
                                        <strong>Patrocinadores</strong>
                                        <div class="patrocinadores-chips">
                                            <?php foreach ($patrocinadores_lista as $pat): ?>
                                                <?php if ($pat['link']): ?>
                                                    <a href="<?php echo esc_url($pat['link']); ?>" target="_blank" rel="noopener" class="patroc-chip"><?php echo esc_html($pat['nombre']); ?></a>
                                                <?php else: ?>
                                                    <span class="patroc-chip"><?php echo esc_html($pat['nombre']); ?></span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($contacto_nombre || $contacto_telefono || $contacto_email): ?>
                                <div class="info-item contacto-item">
                                    <div class="info-content">
                                        <strong><i class="fas fa-address-card"></i> Contacto</strong>
                                        <div class="contacto-badges">
                                            <?php if ($contacto_nombre): ?>
                                                <span class="contacto-badge contacto-badge-nombre">
                                                    <i class="fas fa-user"></i> <?php echo esc_html($contacto_nombre); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($contacto_telefono): ?>
                                                <a href="tel:+593<?php echo esc_attr(ltrim($contacto_telefono, '0')); ?>" class="contacto-badge contacto-badge-tel">
                                                    <i class="fas fa-phone-alt"></i> +593 <?php echo esc_html($contacto_telefono); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($contacto_email): ?>
                                                <a href="mailto:<?php echo esc_attr($contacto_email); ?>" class="contacto-badge contacto-badge-email">
                                                    <i class="fas fa-envelope"></i> <?php echo esc_html($contacto_email); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($facebook || $instagram || $twitter || $youtube || $tiktok || $enlace_externo): ?>
                                <div class="redes-evento">
                                    <?php if ($facebook): ?>
                                        <a href="<?php echo esc_url($facebook); ?>" target="_blank" class="red-link facebook" title="Facebook">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($instagram): ?>
                                        <a href="<?php echo esc_url($instagram); ?>" target="_blank" class="red-link instagram" title="Instagram">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($twitter): ?>
                                        <a href="<?php echo esc_url($twitter); ?>" target="_blank" class="red-link twitter" title="Twitter / X">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($youtube): ?>
                                        <a href="<?php echo esc_url($youtube); ?>" target="_blank" class="red-link youtube" title="YouTube">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($tiktok): ?>
                                        <a href="<?php echo esc_url($tiktok); ?>" target="_blank" class="red-link tiktok" title="TikTok">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M9 0h1.98c.144.715.54 1.617 1.235 2.512C12.895 3.389 13.797 4 15 4v2c-1.753 0-3.07-.814-4-1.829V11a5 5 0 1 1-5-5v2a3 3 0 1 0 3 3z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($enlace_externo): ?>
                                        <a href="<?php echo esc_url($enlace_externo); ?>" target="_blank" class="red-link web" title="Sitio web">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Categorías -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">
                            <i class="fas fa-folder-open"></i> Categorías
                        </h3>
                        <ul class="categorias-list-blog">
                            <?php 
                            $tipos_evento_ordenados = $tipos_evento;
                            uasort($tipos_evento_ordenados, static function ($a, $b) {
                                $label_a = remove_accents($a['label'] ?? '');
                                $label_b = remove_accents($b['label'] ?? '');
                                return strcasecmp($label_a, $label_b);
                            });

                            foreach ($tipos_evento_ordenados as $cat_key => $cat_info):
                                $count = new WP_Query(array(
                                    'post_type' => 'evento',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'evento_tipo',
                                            'value' => $cat_key,
                                            'compare' => '='
                                        )
                                    ),
                                    'posts_per_page' => -1
                                ));
                                
                                if ($count->found_posts > 0):
                            ?>
                                <li>
                                    <a href="<?php echo esc_url(add_query_arg('tipo', $cat_key, $eventos_listado_url)); ?>">
                                        <span class="cat-icon" style="background: <?php echo isset($cat_info['color']) ? $cat_info['color'] : '#3498db'; ?>;">
                                            <?php echo $cat_info['icon']; ?>
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
                    
                    <!-- Eventos Destacados -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">
                            <i class="fas fa-star"></i> Eventos Destacados
                        </h3>
                        <?php 
                        $eventos_destacados = new WP_Query(array(
                            'post_type' => 'evento',
                            'posts_per_page' => 4,
                            'meta_query' => array(
                                array(
                                    'key' => 'evento_destacado',
                                    'value' => '1',
                                    'compare' => '='
                                )
                            ),
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        if ($eventos_destacados->have_posts()):
                        ?>
                            <ul class="entradas-recientes-list">
                                <?php while ($eventos_destacados->have_posts()): $eventos_destacados->the_post(); 
                                    $dest_imagen = get_field('evento_imagen_principal');
                                ?>
                                    <li>
                                        <?php if ($dest_imagen): ?>
                                            <div class="reciente-thumb" style="background-image: url('<?php echo esc_url($dest_imagen['sizes']['thumbnail'] ?? $dest_imagen['url']); ?>');"></div>
                                        <?php endif; ?>
                                        <div class="reciente-info">
                                            <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 8); ?></a>
                                            <span class="reciente-fecha">
                                                <i class="far fa-calendar"></i> <?php echo get_field('evento_fecha_inicio') ? date('d M, Y', strtotime(get_field('evento_fecha_inicio'))) : get_the_date(); ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </ul>
                        <?php else: ?>
                            <p class="tab-empty-msg" style="height:auto; padding: 20px 10px;"><i class="fas fa-info-circle"></i> No hay eventos destacados en este momento.</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Eventos Recientes -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">
                            <i class="fas fa-clock"></i> Recientes
                        </h3>
                        <?php 
                        $recientes = new WP_Query(array(
                            'post_type' => 'evento',
                            'posts_per_page' => 5,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        if ($recientes->have_posts()):
                        ?>
                            <ul class="entradas-recientes-list">
                                <?php while ($recientes->have_posts()): $recientes->the_post(); 
                                    $rec_imagen = get_field('evento_imagen_principal');
                                ?>
                                    <li>
                                        <?php if ($rec_imagen): ?>
                                            <div class="reciente-thumb" style="background-image: url('<?php echo esc_url($rec_imagen['sizes']['thumbnail'] ?? $rec_imagen['url']); ?>');"></div>
                                        <?php endif; ?>
                                        <div class="reciente-info">
                                            <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 8); ?></a>
                                            <span class="reciente-fecha">
                                                <i class="far fa-calendar"></i> <?php echo get_field('evento_fecha_inicio') ? date('d M, Y', strtotime(get_field('evento_fecha_inicio'))) : get_the_date(); ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    
                </aside>
                
            </div>
        </div>
    </div>
    
</div>

<!-- Widget de Compartir -->
<?php include(get_stylesheet_directory() . '/compartir-widget.php'); ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/compartir-widget-styles.css">
<script src="<?php echo get_stylesheet_directory_uri(); ?>/compartir-widget.js"></script>

<script>
// Slider de eventos
let slideActualEvento = 0;

function cambiarSlideEvento(direccion) {
    const slides = document.querySelectorAll('.slide-evento');
    const dots = document.querySelectorAll('.dot-evento');
    
    if (slides.length === 0) return;
    
    slides[slideActualEvento].classList.remove('active');
    dots[slideActualEvento].classList.remove('active');
    
    slideActualEvento = (slideActualEvento + direccion + slides.length) % slides.length;
    
    slides[slideActualEvento].classList.add('active');
    dots[slideActualEvento].classList.add('active');
}

function irASlideEvento(index) {
    const slides = document.querySelectorAll('.slide-evento');
    const dots = document.querySelectorAll('.dot-evento');
    
    if (slides.length === 0) return;
    
    slides[slideActualEvento].classList.remove('active');
    dots[slideActualEvento].classList.remove('active');
    
    slideActualEvento = index;
    
    slides[slideActualEvento].classList.add('active');
    dots[slideActualEvento].classList.add('active');
}

// Auto-avanzar slider
if (document.querySelectorAll('.slide-evento').length > 1) {
    setInterval(() => {
        cambiarSlideEvento(1);
    }, 5000);
}

// La barra de compartir es estática, no necesita JS de toggle

// Reordenar cards de sidebar solo en móvil (después de Etiquetas)
document.addEventListener('DOMContentLoaded', function() {
    const mobileSlot = document.getElementById('evento-mobile-info-slot');
    const sidebar = document.querySelector('.evento-sidebar');
    const infoCard = document.getElementById('evento-card-info-principal');
    const orgCard = document.getElementById('evento-card-info-organizacion');
    const mq = window.matchMedia('(max-width: 768px)');

    if (!mobileSlot || !sidebar || !infoCard) return;

    function moveCardsForViewport() {
        const firstWidget = sidebar.querySelector('.sidebar-widget');

        if (mq.matches) {
            if (infoCard.parentNode !== mobileSlot) {
                mobileSlot.appendChild(infoCard);
            }
            if (orgCard && orgCard.parentNode !== mobileSlot) {
                mobileSlot.appendChild(orgCard);
            }
        } else {
            if (infoCard.parentNode !== sidebar) {
                sidebar.insertBefore(infoCard, firstWidget || null);
            }
            if (orgCard && orgCard.parentNode !== sidebar) {
                sidebar.insertBefore(orgCard, firstWidget || null);
            }
        }
    }

    moveCardsForViewport();
    if (typeof mq.addEventListener === 'function') {
        mq.addEventListener('change', moveCardsForViewport);
    } else if (typeof mq.addListener === 'function') {
        mq.addListener(moveCardsForViewport);
    }
});
</script>

<?php endwhile; ?>

<?php get_footer(); ?>
