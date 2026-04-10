<?php
/**
 * Template Name: Listado de Eventos
 * Plantilla para mostrar el listado completo de eventos culturales
 *
 * @package CasaDeLaCultura
 */

get_header();

$tipos_evento_filtros = array(
    'teatro' => array('label' => 'Teatro', 'icono' => 'fa-theater-masks'),
    'musica' => array('label' => 'Música', 'icono' => 'fa-music'),
    'danza' => array('label' => 'Danza', 'icono' => 'fa-running'),
    'exposicion' => array('label' => 'Exposiciones', 'icono' => 'fa-image'),
    'taller' => array('label' => 'Talleres', 'icono' => 'fa-palette'),
    'conferencia' => array('label' => 'Conferencias', 'icono' => 'fa-microphone'),
    'conversatorio' => array('label' => 'Conversatorios', 'icono' => 'fa-comments'),
    'cine' => array('label' => 'Cine', 'icono' => 'fa-film'),
    'literario' => array('label' => 'Literario', 'icono' => 'fa-book'),
    'concurso' => array('label' => 'Concursos', 'icono' => 'fa-trophy'),
    'festival' => array('label' => 'Festivales', 'icono' => 'fa-star'),
    'otro' => array('label' => 'Otros', 'icono' => 'fa-calendar-check'),
);

// Ordenar filtros alfabéticamente por etiqueta.
uasort($tipos_evento_filtros, static function ($a, $b) {
    $label_a = remove_accents($a['label'] ?? '');
    $label_b = remove_accents($b['label'] ?? '');
    return strcasecmp($label_a, $label_b);
});

$tipo_preseleccionado = isset($_GET['tipo']) ? sanitize_key(wp_unslash($_GET['tipo'])) : '';
if (!isset($tipos_evento_filtros[$tipo_preseleccionado])) {
    $tipo_preseleccionado = '';
}
?>

<div class="archivo-eventos-wrapper">
    
    <!-- Hero del Archivo -->
    <section class="eventos-hero" style="background-image: url('<?php echo esc_url(get_field('imagen_hero_eventos') ?: get_template_directory_uri() . '/images/hero-eventos.jpg'); ?>');">
        <div class="eventos-hero-overlay"></div>
        <div class="container">
            <div class="eventos-hero-content">
                <h1 class="eventos-hero-titulo">
                    <i class="fas fa-theater-masks"></i> Eventos Culturales
                </h1>
                <p class="eventos-hero-descripcion">
                    Descubre todas las actividades culturales que tenemos para ti en la Casa de la Cultura
                </p>
            </div>
        </div>
    </section>
    
    <!-- Buscador de Eventos -->
    <section class="eventos-buscador-section">
        <div class="container">
            <div class="buscador-eventos-container">
                <div class="buscador-input-wrapper">
                    <i class="fas fa-search buscador-icon"></i>
                    <input 
                        type="text" 
                        id="buscadorEventos" 
                        class="buscador-eventos-input" 
                        placeholder="Buscar eventos por título, tipo o lugar..."
                    >
                    <button class="buscador-clear-btn" id="clearBuscador" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Filtros por Tipo de Evento -->
    <section class="eventos-filtros-section">
        <div class="container">
            <div class="filtros-eventos-container">
                
                <!-- Filtro "Todos" fijo -->
                <button class="filtro-evento-btn filtro-todos<?php echo $tipo_preseleccionado === '' ? ' active' : ''; ?>" data-tipo="todos">
                    <span class="filtro-icono"><i class="fas fa-th"></i></span>
                    <span>Todos</span>
                </button>
                
                <!-- Slider de filtros -->
                <div class="filtros-slider-wrapper">
                    <button class="slider-nav-btn prev" id="filtrosSliderPrev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div class="filtros-slider" id="filtrosSlider">
                        <div class="filtros-slider-track">
                            <?php foreach ($tipos_evento_filtros as $tipo_key => $tipo_data) : ?>
                                <button class="filtro-evento-btn<?php echo $tipo_preseleccionado === $tipo_key ? ' active' : ''; ?>" data-tipo="<?php echo esc_attr($tipo_key); ?>">
                                    <span class="filtro-icono"><i class="fas <?php echo esc_attr($tipo_data['icono']); ?>"></i></span>
                                    <span><?php echo esc_html($tipo_data['label']); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <button class="slider-nav-btn next" id="filtrosSliderNext">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                
            </div>
        </div>
    </section>
    
    <!-- Barra superior: estrella + contador -->
    <section class="eventos-tabs-section">
        <div class="container">
            <div class="eventos-tabs-left">
                <button class="destacados-star-btn" id="toggleDestacados" title="Filtrar eventos destacados">
                    <svg width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                </button>
                <div class="eventos-resultados">
                    <span id="resultadosCount"></span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Grid de Eventos con sidebar buttons -->
    <section class="eventos-listado-section">
        <div class="container">
            <div class="eventos-body-layout">

                <!-- Sidebar izquierdo: Pasados -->
                <div class="eventos-sidebar-column">
                    <button class="eventos-sidebar-btn sidebar-pasados-btn eventos-mode-btn active" data-tab="pasados" title="Eventos Pasados">
                        <span class="eventos-sidebar-icon"><i class="fas fa-history"></i></span>
                        <span class="eventos-sidebar-text"><span class="st-l1">Eventos</span><span class="st-l2">Pasados</span></span>
                    </button>
                </div>

                <!-- Contenido central -->
                <div class="eventos-grid-wrapper">
            
            <!-- Próximos Eventos -->
            <div id="tab-proximos" class="tab-content active">
                <?php 
                $eventos_proximos = cc_get_eventos_proximos(50);
                
                if ($eventos_proximos->have_posts()) : 
                ?>
                    <div class="eventos-grid">
                        <?php 
                        while ($eventos_proximos->have_posts()) : $eventos_proximos->the_post();
                            
                            $imagen = get_field('evento_imagen_principal');
                            $tipo = get_field('evento_tipo');
                            $estado = cc_get_estado_evento();
                            $fecha = get_field('evento_fecha_inicio');
                            $lugar = get_field('evento_lugar');
                            $precio = cc_get_precio_evento();
                            $descripcion = get_field('evento_descripcion_corta');
                            $destacado = get_field('evento_destacado');
                            
                            $tipos_evento = array(
                                'teatro' => array('label' => 'Teatro', 'icon' => '<i class="fas fa-theater-masks"></i>'),
                                'musica' => array('label' => 'Música', 'icon' => '<i class="fas fa-music"></i>'),
                                'danza' => array('label' => 'Danza', 'icon' => '<i class="fas fa-running"></i>'),
                                'exposicion' => array('label' => 'Exposición', 'icon' => '<i class="fas fa-image"></i>'),
                                'taller' => array('label' => 'Taller', 'icon' => '<i class="fas fa-palette"></i>'),
                                'conferencia' => array('label' => 'Conferencia', 'icon' => '<i class="fas fa-microphone"></i>'),
                                'conversatorio' => array('label' => 'Conversatorio', 'icon' => '<i class="fas fa-comments"></i>'),
                                'cine' => array('label' => 'Cine', 'icon' => '<i class="fas fa-film"></i>'),
                                'literario' => array('label' => 'Literario', 'icon' => '<i class="fas fa-book"></i>'),
                                'concurso' => array('label' => 'Concurso', 'icon' => '<i class="fas fa-trophy"></i>'),
                                'festival' => array('label' => 'Festival', 'icon' => '<i class="fas fa-star"></i>'),
                                'otro' => array('label' => 'Otro', 'icon' => '<i class="fas fa-calendar-check"></i>')
                            );
                            
                            $tipo_info = $tipos_evento[$tipo] ?? $tipos_evento['otro'];
                            
                            $clase_destacado = $destacado ? ' evento-destacado-card' : '';
                            $imagen_url = (is_array($imagen) && !empty($imagen['url'])) ? $imagen['url'] : '';
                            
                            // Preparar datos de búsqueda
                            $search_parts = array(
                                get_the_title(),
                                $tipo_info['label'],
                                $lugar ? $lugar : ''
                            );
                            $search_data = strtolower(implode(' ', array_filter($search_parts)));
                            $fecha_timestamp = $fecha ? strtotime($fecha) : 0;
                        ?>
                            
                            <article class="evento-card<?php echo $clase_destacado; ?>" data-tipo="<?php echo esc_attr($tipo); ?>" data-search="<?php echo esc_attr($search_data); ?>" data-fecha-ts="<?php echo esc_attr((string) $fecha_timestamp); ?>">
                                
                                <?php if ($destacado): ?>
                                    <div class="badge-destacado-card">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                        DESTACADO
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="evento-card-link">
                                    
                                    <!-- Imagen -->
                                    <div class="evento-card-imagen<?php echo $imagen ? '' : ' sin-imagen'; ?>"<?php if ($imagen_url) : ?> style="background-image: url('<?php echo esc_url($imagen_url); ?>');"<?php endif; ?>>
                                        <?php if ($imagen): ?>
                                            <img src="<?php echo esc_url($imagen['url']); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php else: ?>
                                            <div class="evento-card-placeholder" aria-hidden="true">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="evento-card-overlay"></div>
                                        
                                        <!-- Badge de estado -->
                                        <span class="badge-estado-card" style="background: <?php echo $estado['color']; ?>;">
                                            <?php echo $estado['icon']; ?> 
                                            <?php echo esc_html($estado['label']); ?>
                                        </span>
                                        
                                        <!-- Fecha grande -->
                                        <?php if ($fecha): 
                                            $timestamp = strtotime($fecha);
                                            $dia = date('d', $timestamp);
                                            $mes = date('M', $timestamp);
                                        ?>
                                            <div class="fecha-badge-card">
                                                <span class="fecha-dia"><?php echo $dia; ?></span>
                                                <span class="fecha-mes"><?php echo strtoupper($mes); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Contenido -->
                                    <div class="evento-card-body">
                                        
                                        <div class="evento-card-header">
                                            <span class="tipo-badge">
                                                <?php echo $tipo_info['icon']; ?> <?php echo esc_html($tipo_info['label']); ?>
                                            </span>
                                        </div>
                                        
                                        <h2 class="evento-card-titulo"><?php the_title(); ?></h2>
                                        
                                        <?php if ($descripcion): ?>
                                            <p class="evento-card-descripcion"><?php echo esc_html($descripcion); ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="evento-card-meta">
                                            <?php if ($fecha): ?>
                                                <div class="meta-item-card">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                                                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                                    </svg>
                                                    <span class="meta-item-card-text"><?php echo date('d/m/Y - H:i', strtotime($fecha)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($lugar): ?>
                                                <div class="meta-item-card">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                    </svg>
                                                    <span class="meta-item-card-text"><?php echo esc_html($lugar); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="evento-card-footer">
                                        <div class="precio-card">
                                            <?php if ($precio['gratuito']): ?>
                                                <span class="precio-gratuito"><i class="fas fa-check-circle"></i> GRATUITO</span>
                                            <?php elseif (!empty(trim((string) ($precio['texto'] ?? '')))): ?>
                                                <span class="precio-valor"><?php echo esc_html($precio['texto']); ?></span>
                                            <?php else: ?>
                                                <span class="precio-valor">POR CONFIRMAR</span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="ver-mas-link">
                                            Ver más
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                            </svg>
                                        </span>
                                    </div>
                                    
                                </a>
                                
                            </article>
                            
                        <?php endwhile; ?>
                    </div>
                    
                    <?php wp_reset_postdata(); ?>
                    
                <?php else : ?>
                    
                    <div class="no-eventos-mensaje">
                        <div class="no-eventos-icon">
                            <svg width="80" height="80" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                            </svg>
                        </div>
                        <h2>No hay eventos próximos</h2>
                        <p>Vuelve pronto para conocer nuestros nuevos eventos culturales</p>
                    </div>
                    
                <?php endif; ?>
            </div>
            
            <!-- Eventos Pasados -->
            <div id="tab-pasados" class="tab-content active">
                <?php 
                $eventos_pasados = cc_get_eventos_pasados(30);
                
                if ($eventos_pasados->have_posts()) : 
                ?>
                    <div class="eventos-grid">
                        <?php 
                        while ($eventos_pasados->have_posts()) : $eventos_pasados->the_post();
                            
                            $imagen = get_field('evento_imagen_principal');
                            $tipo = get_field('evento_tipo');
                            $fecha = get_field('evento_fecha_inicio');
                            $lugar = get_field('evento_lugar');
                            $precio = cc_get_precio_evento();
                            $descripcion = get_field('evento_descripcion_corta');
                            $destacado = get_field('evento_destacado');
                            
                            $tipos_evento = array(
                                'teatro' => array('label' => 'Teatro', 'icon' => '<i class="fas fa-theater-masks"></i>'),
                                'musica' => array('label' => 'Música', 'icon' => '<i class="fas fa-music"></i>'),
                                'danza' => array('label' => 'Danza', 'icon' => '<i class="fas fa-running"></i>'),
                                'exposicion' => array('label' => 'Exposición', 'icon' => '<i class="fas fa-image"></i>'),
                                'taller' => array('label' => 'Taller', 'icon' => '<i class="fas fa-palette"></i>'),
                                'conferencia' => array('label' => 'Conferencia', 'icon' => '<i class="fas fa-microphone"></i>'),
                                'conversatorio' => array('label' => 'Conversatorio', 'icon' => '<i class="fas fa-comments"></i>'),
                                'cine' => array('label' => 'Cine', 'icon' => '<i class="fas fa-film"></i>'),
                                'literario' => array('label' => 'Literario', 'icon' => '<i class="fas fa-book"></i>'),
                                'concurso' => array('label' => 'Concurso', 'icon' => '<i class="fas fa-trophy"></i>'),
                                'festival' => array('label' => 'Festival', 'icon' => '<i class="fas fa-star"></i>'),
                                'otro' => array('label' => 'Otro', 'icon' => '<i class="fas fa-calendar-check"></i>')
                            );
                            
                            $tipo_info = $tipos_evento[$tipo] ?? $tipos_evento['otro'];
                            
                            $clase_destacado = $destacado ? ' evento-destacado-card' : '';
                            $imagen_url = (is_array($imagen) && !empty($imagen['url'])) ? $imagen['url'] : '';
                            
                            // Preparar datos de búsqueda
                            $search_parts = array(
                                get_the_title(),
                                $tipo_info['label'],
                                $lugar ? $lugar : ''
                            );
                            $search_data = strtolower(implode(' ', array_filter($search_parts)));
                            $fecha_timestamp = $fecha ? strtotime($fecha) : 0;
                        ?>
                            
                            <article class="evento-card evento-pasado<?php echo $clase_destacado; ?>" data-tipo="<?php echo esc_attr($tipo); ?>" data-search="<?php echo esc_attr($search_data); ?>" data-fecha-ts="<?php echo esc_attr((string) $fecha_timestamp); ?>">
                                
                                <?php if ($destacado): ?>
                                    <div class="badge-destacado-card">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                        DESTACADO
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="evento-card-link">
                                    
                                    <div class="evento-card-imagen<?php echo $imagen ? '' : ' sin-imagen'; ?>"<?php if ($imagen_url) : ?> style="background-image: url('<?php echo esc_url($imagen_url); ?>');"<?php endif; ?>>
                                        <?php if ($imagen): ?>
                                            <img src="<?php echo esc_url($imagen['url']); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php else: ?>
                                            <div class="evento-card-placeholder" aria-hidden="true">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="evento-card-overlay"></div>
                                        
                                        <?php if ($fecha): 
                                            $timestamp = strtotime($fecha);
                                            $dia = date('d', $timestamp);
                                            $mes = date('M', $timestamp);
                                        ?>
                                            <div class="fecha-badge-card">
                                                <span class="fecha-dia"><?php echo $dia; ?></span>
                                                <span class="fecha-mes"><?php echo strtoupper($mes); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="evento-card-body">
                                        
                                        <div class="evento-card-header">
                                            <span class="tipo-badge">
                                                <?php echo $tipo_info['icon']; ?> <?php echo esc_html($tipo_info['label']); ?>
                                            </span>
                                        </div>
                                        
                                        <h2 class="evento-card-titulo"><?php the_title(); ?></h2>
                                        
                                        <?php if ($descripcion): ?>
                                            <p class="evento-card-descripcion"><?php echo esc_html($descripcion); ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="evento-card-meta">
                                            <?php if ($fecha): ?>
                                                <div class="meta-item-card">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                                                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                                    </svg>
                                                    <span class="meta-item-card-text"><?php echo date('d/m/Y', strtotime($fecha)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($lugar): ?>
                                                <div class="meta-item-card">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                    </svg>
                                                    <span class="meta-item-card-text"><?php echo esc_html($lugar); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="evento-card-footer">
                                        <div class="precio-card">
                                            <?php if ($precio['gratuito']): ?>
                                                <span class="precio-gratuito"><i class="fas fa-check-circle"></i> GRATUITO</span>
                                            <?php elseif (!empty(trim((string) ($precio['texto'] ?? '')))): ?>
                                                <span class="precio-valor"><?php echo esc_html($precio['texto']); ?></span>
                                            <?php else: ?>
                                                <span class="precio-valor">POR CONFIRMAR</span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="ver-mas-link">
                                            Ver más
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                            </svg>
                                        </span>
                                    </div>
                                    
                                </a>
                                
                            </article>
                            
                        <?php endwhile; ?>
                    </div>
                    
                    <?php wp_reset_postdata(); ?>
                    
                <?php else : ?>
                    
                    <div class="no-eventos-mensaje">
                        <div class="no-eventos-icon">
                            <svg width="80" height="80" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            </svg>
                        </div>
                        <h2>No hay eventos pasados registrados</h2>
                        <p>El historial de eventos aparecerá aquí</p>
                    </div>
                    
                <?php endif; ?>
            </div>
            
                </div>
                <!-- Fin grid wrapper -->

                <!-- Sidebar derecho: Próximos -->
                <div class="eventos-sidebar-column">
                    <button class="eventos-sidebar-btn sidebar-proximos-btn eventos-mode-btn active" data-tab="proximos" title="Próximos Eventos">
                        <span class="eventos-sidebar-icon"><i class="far fa-calendar-alt"></i></span>
                        <span class="eventos-sidebar-text"><span class="st-l1">Próximos</span><span class="st-l2">Eventos</span></span>
                    </button>
                </div>

            </div>
            <!-- Fin body layout -->
        </div>
    </section>
    
</div>

<style id="eventos-card-hard-fix">
    .archivo-eventos-wrapper .evento-card-link {
        display: flex !important;
        flex-direction: column !important;
        height: 100% !important;
        min-height: 100% !important;
    }

    .archivo-eventos-wrapper .evento-card-imagen {
        position: relative !important;
        height: 280px !important;
        min-height: 280px !important;
        max-height: 280px !important;
        overflow: hidden !important;
        background-size: cover !important;
        background-position: center top !important;
        background-repeat: no-repeat !important;
    }

    .archivo-eventos-wrapper .evento-card-imagen img {
        position: absolute !important;
        inset: 0 !important;
        width: 100% !important;
        height: 100% !important;
        min-height: 100% !important;
        object-fit: cover !important;
        object-position: center top !important;
        display: block !important;
    }

    .archivo-eventos-wrapper .evento-card-meta {
        max-height: 84px !important;
        overflow: hidden !important;
    }

    .archivo-eventos-wrapper .meta-item-card {
        width: 100% !important;
        min-width: 0 !important;
    }

    .archivo-eventos-wrapper .meta-item-card-text {
        display: block !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    .archivo-eventos-wrapper .evento-card-footer {
        flex: 0 0 70px !important;
        min-height: 70px !important;
        max-height: 70px !important;
        height: 70px !important;
        margin-top: 0 !important;
    }

    @media (max-width: 768px) {
        .archivo-eventos-wrapper .evento-card-imagen {
            height: 220px !important;
            min-height: 220px !important;
            max-height: 220px !important;
        }

        .archivo-eventos-wrapper .evento-card-footer {
            flex: 0 0 60px !important;
            min-height: 60px !important;
            max-height: 60px !important;
            height: 60px !important;
        }
    }

    @media (max-width: 580px) {
        .archivo-eventos-wrapper .evento-card-imagen {
            height: 200px !important;
            min-height: 200px !important;
            max-height: 200px !important;
        }
    }
</style>

<script>
// Buscador y Filtros
document.addEventListener('DOMContentLoaded', function() {
    // Variables de estado
    let terminoBusqueda = '';
    let filtroActivo = <?php echo wp_json_encode($tipo_preseleccionado ?: 'todos'); ?>;
    let soloDestacados = false;
    
    // Elementos del buscador
    const buscador = document.getElementById('buscadorEventos');
    const clearBtn = document.getElementById('clearBuscador');
    const resultadosCount = document.getElementById('resultadosCount');
    const toggleDestacados = document.getElementById('toggleDestacados');
    
    // Elementos de filtrado y tabs
    const filtros = document.querySelectorAll('.filtro-evento-btn');
    const cards = document.querySelectorAll('.evento-card');
    const allTabTriggers = document.querySelectorAll('.tab-btn, .eventos-mode-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    const eventosGridWrapper = document.querySelector('.eventos-grid-wrapper');
    const activeTabs = new Set();
    let gridUnificado = null;
    
    // Evento del Toggle Destacados (ahora es un botón)
    if(toggleDestacados) {
        toggleDestacados.addEventListener('click', function() {
            soloDestacados = !soloDestacados;
            this.classList.toggle('active', soloDestacados);
            actualizarResultados();
        });
    }

    // Slider de filtros
    const slider = document.getElementById('filtrosSlider');
    const sliderTrack = slider.querySelector('.filtros-slider-track');
    const prevBtn = document.getElementById('filtrosSliderPrev');
    const nextBtn = document.getElementById('filtrosSliderNext');
    const filtrosSection = document.querySelector('.eventos-filtros-section');
    
    let currentScroll = 0;
    const scrollAmount = 250;
    
    // Funcionalidad del slider
    function updateSliderButtons() {
        const maxScroll = sliderTrack.scrollWidth - slider.clientWidth;
        
        if (currentScroll <= 0) {
            prevBtn.style.opacity = '0.3';
            prevBtn.style.cursor = 'not-allowed';
        } else {
            prevBtn.style.opacity = '1';
            prevBtn.style.cursor = 'pointer';
        }
        
        if (currentScroll >= maxScroll) {
            nextBtn.style.opacity = '0.3';
            nextBtn.style.cursor = 'not-allowed';
        } else {
            nextBtn.style.opacity = '1';
            nextBtn.style.cursor = 'pointer';
        }
    }
    
    prevBtn.addEventListener('click', function() {
        if (currentScroll > 0) {
            currentScroll = Math.max(0, currentScroll - scrollAmount);
            slider.scrollTo({
                left: currentScroll,
                behavior: 'smooth'
            });
            setTimeout(updateSliderButtons, 300);
        }
    });
    
    nextBtn.addEventListener('click', function() {
        const maxScroll = sliderTrack.scrollWidth - slider.clientWidth;
        if (currentScroll < maxScroll) {
            currentScroll = Math.min(maxScroll, currentScroll + scrollAmount);
            slider.scrollTo({
                left: currentScroll,
                behavior: 'smooth'
            });
            setTimeout(updateSliderButtons, 300);
        }
    });
    
    // Actualizar al hacer scroll manual
    slider.addEventListener('scroll', function() {
        currentScroll = slider.scrollLeft;
        updateSliderButtons();
    });

    // En escritorio: scroll vertical del mouse dentro de filtros => scroll horizontal del slider.
    if (filtrosSection) {
        filtrosSection.addEventListener('wheel', function(e) {
            if (window.innerWidth <= 768) return;

            const maxScroll = sliderTrack.scrollWidth - slider.clientWidth;
            if (maxScroll <= 0) return;

            const delta = Math.abs(e.deltaY) > Math.abs(e.deltaX) ? e.deltaY : e.deltaX;
            if (delta === 0) return;

            const scrollAntes = slider.scrollLeft;
            const scrollDespues = Math.max(0, Math.min(maxScroll, scrollAntes + delta));

            if (scrollDespues !== scrollAntes) {
                e.preventDefault();
                slider.scrollLeft = scrollDespues;
                currentScroll = slider.scrollLeft;
                updateSliderButtons();
            }
        }, { passive: false });
    }
    
    // Inicializar estado de botones
    updateSliderButtons();

    function activarFiltroPorTipo(tipo) {
        if (!tipo) return false;

        const filtroBtn = document.querySelector('.filtro-evento-btn[data-tipo="' + tipo + '"]');
        if (!filtroBtn) return false;

        filtroActivo = tipo;
        filtros.forEach(f => f.classList.remove('active'));
        filtroBtn.classList.add('active');

        if (slider && typeof filtroBtn.scrollIntoView === 'function') {
            filtroBtn.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });
        }

        return true;
    }

    function inicializarGridUnificado() {
        if (!eventosGridWrapper) return;

        const gridExistente = document.getElementById('eventosGridUnificado');
        if (gridExistente) {
            gridUnificado = gridExistente;
            return;
        }

        const nuevoGrid = document.createElement('div');
        nuevoGrid.id = 'eventosGridUnificado';
        nuevoGrid.className = 'eventos-grid';

        let totalCardsMovidas = 0;

        tabContents.forEach(tabContent => {
            const tab = (tabContent.id || '').replace('tab-', '');
            const cardsEnTab = tabContent.querySelectorAll('.evento-card');

            cardsEnTab.forEach(card => {
                if (tab) {
                    card.setAttribute('data-tab', tab);
                }
                nuevoGrid.appendChild(card);
                totalCardsMovidas++;
            });
        });

        if (totalCardsMovidas === 0) {
            return;
        }

        const primeraTab = tabContents.length ? tabContents[0] : null;
        if (primeraTab) {
            eventosGridWrapper.insertBefore(nuevoGrid, primeraTab);
        } else {
            eventosGridWrapper.appendChild(nuevoGrid);
        }

        tabContents.forEach(tabContent => {
            tabContent.style.display = 'none';
        });

        gridUnificado = nuevoGrid;
    }

    function ordenarCardsPorFechaEnTabsActivos() {
        if (!gridUnificado) return;

        const cardsDelGrid = Array.from(gridUnificado.querySelectorAll('.evento-card'));

        cardsDelGrid.sort((cardA, cardB) => {
            const fechaA = parseInt(cardA.getAttribute('data-fecha-ts') || '0', 10);
            const fechaB = parseInt(cardB.getAttribute('data-fecha-ts') || '0', 10);
            return fechaB - fechaA;
        });

        cardsDelGrid.forEach(card => {
            gridUnificado.appendChild(card);
        });
    }
    
    // Función para actualizar resultados
    function actualizarResultados() {
        ordenarCardsPorFechaEnTabsActivos();

        // El filtro se aplica sobre tabs activos (pueden ser ambos).
        let visibles = 0;
        let total = 0;
        
        cards.forEach(card => {
            const cardTipo = card.getAttribute('data-tipo');
            const cardSearch = card.getAttribute('data-search') || '';
            const cardTab = card.getAttribute('data-tab') || 'proximos';
            const perteneceTabActivo = activeTabs.has(cardTab);
            
            // Verificar si pasa el filtro de tipo
            const pasaFiltroTipo = (filtroActivo === 'todos' || cardTipo === filtroActivo);
            
            // Verificar si pasa la búsqueda
            const pasaBusqueda = (terminoBusqueda === '' || cardSearch.includes(terminoBusqueda));
            
            // Verificar si pasa el filtro destacado
            const esDestacado = card.classList.contains('evento-destacado-card');
            const pasaFiltroDestacado = (!soloDestacados || esDestacado);
            
            if (pasaFiltroTipo && pasaBusqueda && pasaFiltroDestacado) {
                // Mantener el display nativo del CSS (.evento-card usa flex).
                card.style.display = '';
                card.style.animation = 'fadeInUp 0.5s ease';
                if (perteneceTabActivo) visibles++;
            } else {
                card.style.display = 'none';
            }

            if (perteneceTabActivo) total++;
        });
        
        // Actualizar contador
        if (terminoBusqueda !== '' || filtroActivo !== 'todos' || soloDestacados) {
            resultadosCount.textContent = visibles + ' de ' + total + ' Eventos';
        } else {
            resultadosCount.textContent = total + ' Eventos en total';
        }
        resultadosCount.style.display = 'block';
    }
    
    // Buscador
    buscador.addEventListener('input', function() {
        terminoBusqueda = this.value.toLowerCase().trim();
        
        if (terminoBusqueda !== '') {
            clearBtn.style.display = 'flex';
        } else {
            clearBtn.style.display = 'none';
        }
        
        actualizarResultados();
    });
    
    // Botón limpiar búsqueda
    clearBtn.addEventListener('click', function() {
        buscador.value = '';
        terminoBusqueda = '';
        clearBtn.style.display = 'none';
        actualizarResultados();
        buscador.focus();
    });
    
    // Filtros por tipo
    filtros.forEach(filtro => {
        filtro.addEventListener('click', function() {
            activarFiltroPorTipo(this.getAttribute('data-tipo'));
            
            // Actualizar resultados
            actualizarResultados();
        });
    });
    
    // Tabs (modo múltiple): se pueden activar Pasados y Próximos al mismo tiempo.
    function sincronizarTabsActivosDesdeBotones() {
        activeTabs.clear();

        allTabTriggers.forEach(trigger => {
            const tab = trigger.getAttribute('data-tab');
            if (tab && trigger.classList.contains('active')) {
                activeTabs.add(tab);
            }
        });

        if (activeTabs.size === 0) {
            activeTabs.add('proximos');
        }
    }

    function renderTabsActivosEnBotones() {
        allTabTriggers.forEach(trigger => {
            const tab = trigger.getAttribute('data-tab');
            trigger.classList.toggle('active', !!tab && activeTabs.has(tab));
        });
    }

    function toggleTab(tab) {
        if (!tab) return;

        if (activeTabs.has(tab)) {
            // Mantener al menos uno activo.
            if (activeTabs.size === 1) {
                return;
            }
            activeTabs.delete(tab);
        } else {
            activeTabs.add(tab);
        }

        renderTabsActivosEnBotones();
    }

    allTabTriggers.forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.getAttribute('data-tab');
            toggleTab(tab);
            
            // Limpiar búsqueda
            buscador.value = '';
            terminoBusqueda = '';
            clearBtn.style.display = 'none';
            
            actualizarResultados();
        });
    });

    inicializarGridUnificado();
    sincronizarTabsActivosDesdeBotones();
    renderTabsActivosEnBotones();

    function aplicarFiltroInicialDesdeUrl() {
        // Prioriza el valor validado desde PHP y luego URL.
        if (activarFiltroPorTipo(filtroActivo)) {
            actualizarResultados();
            return;
        }

        const params = new URLSearchParams(window.location.search);
        const tipoDesdeUrl = (params.get('tipo') || '').toLowerCase().trim().replace(/[^a-z0-9_-]/g, '');
        if (tipoDesdeUrl) {
            activarFiltroPorTipo(tipoDesdeUrl);
        } else {
            activarFiltroPorTipo('todos');
        }
        actualizarResultados();
    }

    // Aplicación inicial y reaplicación en load por si otro JS lo resetea.
    aplicarFiltroInicialDesdeUrl();
    window.addEventListener('load', function() {
        setTimeout(aplicarFiltroInicialDesdeUrl, 0);
    });
});

// Animación
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>

<?php get_footer(); ?>
