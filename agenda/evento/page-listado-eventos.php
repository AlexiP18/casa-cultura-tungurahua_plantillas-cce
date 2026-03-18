<?php
/**
 * Template Name: Listado de Eventos
 * Plantilla para mostrar el listado completo de eventos culturales
 *
 * @package CasaDeLaCultura
 */

get_header(); ?>

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
                <button class="filtro-evento-btn filtro-todos active" data-tipo="todos">
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
                            <button class="filtro-evento-btn" data-tipo="teatro">
                                <span class="filtro-icono"><i class="fas fa-theater-masks"></i></span>
                                <span>Teatro</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="musica">
                                <span class="filtro-icono"><i class="fas fa-music"></i></span>
                                <span>Música</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="danza">
                                <span class="filtro-icono"><i class="fas fa-running"></i></span>
                                <span>Danza</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="exposicion">
                                <span class="filtro-icono"><i class="fas fa-image"></i></span>
                                <span>Exposiciones</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="taller">
                                <span class="filtro-icono"><i class="fas fa-palette"></i></span>
                                <span>Talleres</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="conferencia">
                                <span class="filtro-icono"><i class="fas fa-microphone"></i></span>
                                <span>Conferencias</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="conversatorio">
                                <span class="filtro-icono"><i class="fas fa-comments"></i></span>
                                <span>Conversatorios</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="cine">
                                <span class="filtro-icono"><i class="fas fa-film"></i></span>
                                <span>Cine</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="literario">
                                <span class="filtro-icono"><i class="fas fa-book"></i></span>
                                <span>Literario</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="concurso">
                                <span class="filtro-icono"><i class="fas fa-trophy"></i></span>
                                <span>Concursos</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="festival">
                                <span class="filtro-icono"><i class="fas fa-star"></i></span>
                                <span>Festivales</span>
                            </button>
                            <button class="filtro-evento-btn" data-tipo="otro">
                                <span class="filtro-icono"><i class="fas fa-calendar-check"></i></span>
                                <span>Otros</span>
                            </button>
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
                    <button class="eventos-sidebar-btn sidebar-pasados-btn eventos-mode-btn" data-tab="pasados" title="Eventos Pasados">
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
                            
                            // Preparar datos de búsqueda
                            $search_parts = array(
                                get_the_title(),
                                $tipo_info['label'],
                                $lugar ? $lugar : ''
                            );
                            $search_data = strtolower(implode(' ', array_filter($search_parts)));
                        ?>
                            
                            <article class="evento-card<?php echo $clase_destacado; ?>" data-tipo="<?php echo esc_attr($tipo); ?>" data-search="<?php echo esc_attr($search_data); ?>">
                                
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
                                    <div class="evento-card-imagen">
                                        <?php if ($imagen): ?>
                                            <img src="<?php echo esc_url($imagen['url']); ?>" alt="<?php the_title_attribute(); ?>">
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
                                                    <span><?php echo date('d/m/Y - H:i', strtotime($fecha)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($lugar): ?>
                                                <div class="meta-item-card">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                    </svg>
                                                    <span><?php echo esc_html($lugar); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="evento-card-footer">
                                        <div class="precio-card">
                                            <?php if ($precio['gratuito']): ?>
                                                <span class="precio-gratuito"><i class="fas fa-check-circle"></i> GRATUITO</span>
                                            <?php elseif ($precio['multiple']): ?>
                                                <span class="precio-valor"><?php echo esc_html($precio['texto']); ?></span>
                                            <?php else: ?>
                                                <span class="precio-valor"><?php echo esc_html($precio['texto']); ?></span>
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
            <div id="tab-pasados" class="tab-content">
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
                            
                            // Preparar datos de búsqueda
                            $search_parts = array(
                                get_the_title(),
                                $tipo_info['label'],
                                $lugar ? $lugar : ''
                            );
                            $search_data = strtolower(implode(' ', array_filter($search_parts)));
                        ?>
                            
                            <article class="evento-card evento-pasado<?php echo $clase_destacado; ?>" data-tipo="<?php echo esc_attr($tipo); ?>" data-search="<?php echo esc_attr($search_data); ?>">
                                
                                <?php if ($destacado): ?>
                                    <div class="badge-destacado-card">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                        DESTACADO
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="evento-card-link">
                                    
                                    <div class="evento-card-imagen">
                                        <?php if ($imagen): ?>
                                            <img src="<?php echo esc_url($imagen['url']); ?>" alt="<?php the_title_attribute(); ?>">
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
                                                    <span><?php echo date('d/m/Y', strtotime($fecha)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($lugar): ?>
                                                <div class="meta-item-card">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                    </svg>
                                                    <span><?php echo esc_html($lugar); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="evento-card-footer">
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

<script>
// Buscador y Filtros
document.addEventListener('DOMContentLoaded', function() {
    // Variables de estado
    let terminoBusqueda = '';
    let filtroActivo = 'todos';
    let soloDestacados = false;
    
    // Elementos del buscador
    const buscador = document.getElementById('buscadorEventos');
    const clearBtn = document.getElementById('clearBuscador');
    const resultadosCount = document.getElementById('resultadosCount');
    const toggleDestacados = document.getElementById('toggleDestacados');
    
    // Elementos de filtrado
    const filtros = document.querySelectorAll('.filtro-evento-btn');
    const cards = document.querySelectorAll('.evento-card');
    
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
    
    // Inicializar estado de botones
    updateSliderButtons();
    
    // Función para actualizar resultados
    function actualizarResultados() {
        // Determinar el tab activo
        const activeTab = document.querySelector('.tab-content.active');
        const activeCards = activeTab ? activeTab.querySelectorAll('.evento-card') : cards;
        
        let visibles = 0;
        const total = activeCards.length;
        
        activeCards.forEach(card => {
            const cardTipo = card.getAttribute('data-tipo');
            const cardSearch = card.getAttribute('data-search') || '';
            
            // Verificar si pasa el filtro de tipo
            const pasaFiltroTipo = (filtroActivo === 'todos' || cardTipo === filtroActivo);
            
            // Verificar si pasa la búsqueda
            const pasaBusqueda = (terminoBusqueda === '' || cardSearch.includes(terminoBusqueda));
            
            // Verificar si pasa el filtro destacado
            const esDestacado = card.classList.contains('evento-destacado-card');
            const pasaFiltroDestacado = (!soloDestacados || esDestacado);
            
            if (pasaFiltroTipo && pasaBusqueda && pasaFiltroDestacado) {
                card.style.display = 'block';
                card.style.animation = 'fadeInUp 0.5s ease';
                visibles++;
            } else {
                card.style.display = 'none';
            }
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
            filtroActivo = this.getAttribute('data-tipo');
            
            // Actualizar filtro activo
            filtros.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            
            // Actualizar resultados
            actualizarResultados();
        });
    });
    
    // Tabs: Próximos / Pasados (incluye botones sidebar)
    const tabBtns = document.querySelectorAll('.tab-btn');
    const modeBtns = document.querySelectorAll('.eventos-mode-btn');
    const allTabTriggers = document.querySelectorAll('.tab-btn, .eventos-mode-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    allTabTriggers.forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.getAttribute('data-tab');
            
            // Actualizar activos en TODOS los triggers
            allTabTriggers.forEach(b => b.classList.remove('active'));
            // Marcar activo todos los que tengan el mismo data-tab
            allTabTriggers.forEach(b => {
                if (b.getAttribute('data-tab') === tab) b.classList.add('active');
            });
            
            // Mostrar contenido correspondiente
            tabContents.forEach(content => {
                if (content.id === 'tab-' + tab) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
            
            // Resetear filtro y búsqueda al cambiar de tab
            const primerFiltro = document.querySelector('.filtro-evento-btn[data-tipo="todos"]');
            if (primerFiltro) {
                filtroActivo = 'todos';
                filtros.forEach(f => f.classList.remove('active'));
                primerFiltro.classList.add('active');
            }
            
            // Limpiar búsqueda
            buscador.value = '';
            terminoBusqueda = '';
            clearBtn.style.display = 'none';
            
            actualizarResultados();
        });
    });
    
    // Inicializar contador al cargar la página
    actualizarResultados();
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
