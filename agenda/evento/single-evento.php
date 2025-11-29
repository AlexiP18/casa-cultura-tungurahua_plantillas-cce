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
    $organizador = get_field('evento_organizador');
    $patrocinadores = get_field('evento_patrocinadores');
    $contacto_nombre = get_field('evento_contacto_nombre');
    $contacto_telefono = get_field('evento_contacto_telefono');
    $contacto_email = get_field('evento_contacto_email');
    $facebook = get_field('evento_redes_facebook');
    $instagram = get_field('evento_redes_instagram');
    $enlace_externo = get_field('evento_enlace_externo');
    $etiquetas = get_field('evento_etiquetas');
    
    // Archivos
    $archivo_programa = get_field('evento_archivo_programa');
    $archivo_adicional = get_field('evento_archivo_adicional');
    
    // Tipos de evento con iconos
    $tipos_evento = array(
        'teatro' => array('label' => 'Teatro', 'icon' => '🎭'),
        'musica' => array('label' => 'Música y Conciertos', 'icon' => '🎵'),
        'danza' => array('label' => 'Danza', 'icon' => '💃'),
        'exposicion' => array('label' => 'Exposición', 'icon' => '🖼️'),
        'taller' => array('label' => 'Taller', 'icon' => '🎨'),
        'conferencia' => array('label' => 'Conferencia', 'icon' => '🎤'),
        'conversatorio' => array('label' => 'Conversatorio', 'icon' => '💬'),
        'cine' => array('label' => 'Cine', 'icon' => '🎬'),
        'literario' => array('label' => 'Evento Literario', 'icon' => '📚'),
        'concurso' => array('label' => 'Concurso', 'icon' => '🏆'),
        'festival' => array('label' => 'Festival', 'icon' => '🎪'),
        'otro' => array('label' => 'Otro', 'icon' => '🎯')
    );
    
    $tipo_info = $tipos_evento[$tipo] ?? $tipos_evento['otro'];
?>

<div class="evento-wrapper">
    
    <!-- Hero del Evento -->
    <section class="evento-hero" style="background-image: url('<?php echo esc_url($imagen_banner ? $imagen_banner['url'] : $imagen_principal['url']); ?>');">
        <div class="evento-hero-overlay"></div>
        <div class="container">
            <div class="evento-hero-content">
                
                <!-- Badges superiores -->
                <div class="evento-badges-top">
                    <span class="badge-tipo" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
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
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                        </svg>
                        <div>
                            <strong>Fecha</strong>
                            <span><?php echo $fecha_formateada; ?></span>
                        </div>
                    </div>
                    
                    <div class="meta-item-hero">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                        </svg>
                        <div>
                            <strong>Lugar</strong>
                            <span><?php echo esc_html($lugar); ?></span>
                        </div>
                    </div>
                    
                    <div class="meta-item-hero precio-hero">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                        </svg>
                        <div>
                            <strong>Precio</strong>
                            <span class="precio-valor"><?php echo esc_html($precio['texto']); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="evento-hero-actions">
                    <?php if ($requiere_inscripcion && $enlace_inscripcion && $estado['label'] !== 'Finalizado'): ?>
                        <a href="<?php echo esc_url($enlace_inscripcion); ?>" target="_blank" class="btn-inscripcion">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                            </svg>
                            Inscríbete Ahora
                        </a>
                    <?php endif; ?>
                    
                    <button class="btn-compartir-evento" onclick="toggleCompartirEvento()">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5z"/>
                        </svg>
                        Compartir
                    </button>
                    
                    <div class="compartir-popup-evento" id="compartirPopup">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="share-link facebook">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share-link twitter">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057a3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                            </svg>
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank" class="share-link whatsapp">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                            </svg>
                        </a>
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
                        <h2 class="section-title-evento">📋 Sobre el Evento</h2>
                        <div class="evento-descripcion">
                            <?php the_content(); ?>
                        </div>
                    </section>
                    
                    <!-- Artista/Ponente -->
                    <?php if ($artista): ?>
                        <section class="evento-section artista-section">
                            <h2 class="section-title-evento">👤 <?php echo $tipo === 'taller' ? 'Tallerista' : ($tipo === 'conferencia' || $tipo === 'conversatorio' ? 'Ponente' : 'Artista'); ?></h2>
                            <div class="artista-box">
                                <h3><?php echo esc_html($artista); ?></h3>
                                <?php if ($artista_bio): ?>
                                    <p><?php echo nl2br(esc_html($artista_bio)); ?></p>
                                <?php endif; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Fechas Múltiples -->
                    <?php if ($multiples_fechas && $fechas_adicionales): ?>
                        <section class="evento-section">
                            <h2 class="section-title-evento">📅 Fechas del Evento</h2>
                            <div class="fechas-multiples">
                                <div class="fecha-principal-box">
                                    <strong>Fecha principal:</strong><br>
                                    <?php echo $fecha_formateada; ?>
                                </div>
                                <div class="fechas-adicionales-box">
                                    <strong>Fechas adicionales:</strong>
                                    <?php 
                                    // Verificar si es array o string
                                    if (is_array($fechas_adicionales)) {
                                        echo '<ul class="lista-fechas">';
                                        foreach ($fechas_adicionales as $fecha) {
                                            echo '<li>' . esc_html($fecha) . '</li>';
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo wpautop(esc_html($fechas_adicionales));
                                    }
                                    ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Programa -->
                    <?php if ($programa): ?>
                        <section class="evento-section">
                            <h2 class="section-title-evento">📑 Programa</h2>
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
                            <h2 class="section-title-evento">🖼️ Galería</h2>
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
                    
                    <!-- Video -->
                    <?php if ($video): ?>
                        <section class="evento-section">
                            <h2 class="section-title-evento">🎥 Video</h2>
                            <div class="evento-video">
                                <?php echo $video; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Requisitos e Incluye - ACTUALIZADO -->
                    <?php if ($requisitos || $incluye): ?>
                        <section class="evento-section">
                            <div class="req-incluye-grid">
                                <?php if ($requisitos && is_array($requisitos) && count($requisitos) > 0): ?>
                                    <div class="requisitos-box">
                                        <h3>✓ Requisitos</h3>
                                        <ul class="lista-checks">
                                            <?php foreach ($requisitos as $req): ?>
                                                <li><?php echo esc_html($req); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($incluye && is_array($incluye) && count($incluye) > 0): ?>
                                    <div class="incluye-box">
                                        <h3>🎁 ¿Qué Incluye?</h3>
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
                            <h2 class="section-title-evento">📎 Archivos</h2>
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
                    
                </main>
                
                <!-- Sidebar -->
                <aside class="evento-sidebar">
                    
                    <!-- Card de Información -->
                    <div class="evento-card-info">
                        <h3>📌 Información</h3>
                        
                        <div class="info-item">
                            <div class="info-icon">📅</div>
                            <div class="info-content">
                                <strong>Fecha</strong>
                                <span><?php echo date('j M Y', strtotime($fecha_inicio)); ?></span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">🕐</div>
                            <div class="info-content">
                                <strong>Hora</strong>
                                <span><?php echo date('H:i', strtotime($fecha_inicio)); ?></span>
                            </div>
                        </div>
                        
                        <?php if ($duracion): ?>
                            <div class="info-item">
                                <div class="info-icon">⏱️</div>
                                <div class="info-content">
                                    <strong>Duración</strong>
                                    <span><?php echo esc_html($duracion); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <div class="info-icon">📍</div>
                            <div class="info-content">
                                <strong>Lugar</strong>
                                <span><?php echo esc_html($lugar); ?></span>
                                <?php if ($direccion): ?>
                                    <small><?php echo esc_html($direccion); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($mapa): ?>
                            <a href="<?php echo esc_url($mapa); ?>" target="_blank" class="btn-mapa">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z"/>
                                </svg>
                                Ver en Google Maps
                            </a>
                        <?php endif; ?>
                        
                        <div class="info-item precio-item">
                            <div class="info-icon">💰</div>
                            <div class="info-content">
                                <strong>Precio</strong>
                                <span class="precio-grande"><?php echo esc_html($precio['texto']); ?></span>
                                <?php if (!empty($precio['detalles'])): ?>
                                    <div class="precios-detalle">
                                        <?php echo wpautop($precio['detalles']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($ocupacion): ?>
                            <div class="info-item cupos-item">
                                <div class="info-icon">👥</div>
                                <div class="info-content">
                                    <strong>Cupos Disponibles</strong>
                                    <div class="cupos-barra">
                                        <div class="cupos-progreso" style="width: <?php echo $ocupacion['porcentaje']; ?>%;"></div>
                                    </div>
                                    <span><?php echo $ocupacion['disponibles']; ?> de <?php echo $ocupacion['capacidad']; ?> disponibles</span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($edad_minima): ?>
                            <div class="info-item">
                                <div class="info-icon">🔞</div>
                                <div class="info-content">
                                    <strong>Edad Mínima</strong>
                                    <span><?php echo esc_html($edad_minima); ?> años</span>
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
                    <?php if ($organizador || $contacto_nombre || $facebook || $instagram): ?>
                        <div class="evento-card-info">
                            <h3>🏢 Organización</h3>
                            
                            <?php if ($organizador): ?>
                                <div class="info-item">
                                    <strong>Organizado por:</strong>
                                    <p><?php echo esc_html($organizador); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($patrocinadores): ?>
                                <div class="info-item">
                                    <strong>Patrocinadores:</strong>
                                    <p><?php echo esc_html($patrocinadores); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($contacto_nombre || $contacto_telefono || $contacto_email): ?>
                                <div class="info-item">
                                    <strong>Contacto:</strong>
                                    <?php if ($contacto_nombre): ?>
                                        <p><?php echo esc_html($contacto_nombre); ?></p>
                                    <?php endif; ?>
                                    <?php if ($contacto_telefono): ?>
                                        <p>
                                            <a href="tel:<?php echo esc_attr($contacto_telefono); ?>">
                                                📞 <?php echo esc_html($contacto_telefono); ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($contacto_email): ?>
                                        <p>
                                            <a href="mailto:<?php echo esc_attr($contacto_email); ?>">
                                                ✉️ <?php echo esc_html($contacto_email); ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($facebook || $instagram || $enlace_externo): ?>
                                <div class="redes-evento">
                                    <?php if ($facebook): ?>
                                        <a href="<?php echo esc_url($facebook); ?>" target="_blank" class="red-link facebook">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($instagram): ?>
                                        <a href="<?php echo esc_url($instagram); ?>" target="_blank" class="red-link instagram">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($enlace_externo): ?>
                                        <a href="<?php echo esc_url($enlace_externo); ?>" target="_blank" class="red-link web">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                </aside>
                
            </div>
        </div>
    </div>
    
</div>

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

// Toggle compartir
function toggleCompartirEvento() {
    const popup = document.getElementById('compartirPopup');
    popup.classList.toggle('show');
}

// Cerrar popup al hacer clic fuera
document.addEventListener('click', function(e) {
    const popup = document.getElementById('compartirPopup');
    const btn = document.querySelector('.btn-compartir-evento');
    
    if (popup && btn && !btn.contains(e.target) && !popup.contains(e.target)) {
        popup.classList.remove('show');
    }
});
</script>

<?php endwhile; ?>

<?php get_footer(); ?>