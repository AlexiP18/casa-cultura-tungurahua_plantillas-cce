<?php
/**
 * Template para archivo de eventos
 * Casa de la Cultura - Eventos Culturales
 */

get_header(); ?>

<div class="archivo-eventos-wrapper">
    
    <!-- Hero del Archivo -->
    <section class="eventos-hero">
        <div class="container">
            <div class="eventos-hero-content">
                <h1 class="eventos-hero-titulo">🎭 Eventos Culturales</h1>
                <p class="eventos-hero-descripcion">
                    Descubre todas las actividades culturales que tenemos para ti en la Casa de la Cultura
                </p>
            </div>
        </div>
    </section>
    
    <!-- Filtros por Tipo de Evento -->
    <section class="eventos-filtros-section">
        <div class="container">
            <div class="filtros-eventos-wrapper">
                <button class="filtro-evento-btn active" data-tipo="todos">
                    <span class="filtro-icono">🎯</span>
                    <span>Todos</span>
                </button>
                <button class="filtro-evento-btn" data-tipo="teatro">
                    <span class="filtro-icono">🎭</span>
                    <span>Teatro</span>
                </button>
                <button class="filtro-evento-btn" data-tipo="musica">
                    <span class="filtro-icono">🎵</span>
                    <span>Música</span>
                </button>
                <button class="filtro-evento-btn" data-tipo="danza">
                    <span class="filtro-icono">💃</span>
                    <span>Danza</span>
                </button>
                <button class="filtro-evento-btn" data-tipo="exposicion">
                    <span class="filtro-icono">🖼️</span>
                    <span>Exposiciones</span>
                </button>
                <button class="filtro-evento-btn" data-tipo="taller">
                    <span class="filtro-icono">🎨</span>
                    <span>Talleres</span>
                </button>
                <button class="filtro-evento-btn" data-tipo="conferencia">
                    <span class="filtro-icono">🎤</span>
                    <span>Conferencias</span>
                </button>
                <button class="filtro-evento-btn" data-tipo="cine">
                    <span class="filtro-icono">🎬</span>
                    <span>Cine</span>
                </button>
            </div>
        </div>
    </section>
    
    <!-- Pestañas: Próximos / Pasados -->
    <section class="eventos-tabs-section">
        <div class="container">
            <div class="eventos-tabs">
                <button class="tab-btn active" data-tab="proximos">
                    📅 Próximos Eventos
                </button>
                <button class="tab-btn" data-tab="pasados">
                    📚 Eventos Pasados
                </button>
            </div>
        </div>
    </section>
    
    <!-- Grid de Eventos -->
    <section class="eventos-listado-section">
        <div class="container">
            
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
                                'teatro' => array('label' => 'Teatro', 'icon' => '🎭'),
                                'musica' => array('label' => 'Música', 'icon' => '🎵'),
                                'danza' => array('label' => 'Danza', 'icon' => '💃'),
                                'exposicion' => array('label' => 'Exposición', 'icon' => '🖼️'),
                                'taller' => array('label' => 'Taller', 'icon' => '🎨'),
                                'conferencia' => array('label' => 'Conferencia', 'icon' => '🎤'),
                                'conversatorio' => array('label' => 'Conversatorio', 'icon' => '💬'),
                                'cine' => array('label' => 'Cine', 'icon' => '🎬'),
                                'literario' => array('label' => 'Literario', 'icon' => '📚'),
                                'concurso' => array('label' => 'Concurso', 'icon' => '🏆'),
                                'festival' => array('label' => 'Festival', 'icon' => '🎪'),
                                'otro' => array('label' => 'Otro', 'icon' => '🎯')
                            );
                            
                            $tipo_info = $tipos_evento[$tipo] ?? $tipos_evento['otro'];
                            
                            $clase_destacado = $destacado ? ' evento-destacado-card' : '';
                        ?>
                            
                            <article class="evento-card<?php echo $clase_destacado; ?>" data-tipo="<?php echo esc_attr($tipo); ?>">
                                
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
                                            <img src="<?php echo esc_url($imagen['url']); ?>" 
                                                 alt="<?php echo esc_attr($imagen['alt']); ?>"
                                                 loading="lazy">
                                        <?php endif; ?>
                                        <div class="evento-card-overlay"></div>
                                        
                                        <!-- Badge de estado -->
                                        <span class="badge-estado-card" style="background: <?php echo $estado['color']; ?>;">
                                            <?php echo $estado['icon']; ?> <?php echo esc_html($estado['label']); ?>
                                        </span>
                                        
                                        <!-- Fecha grande -->
                                        <div class="fecha-badge-card">
                                            <div class="fecha-dia"><?php echo date('j', strtotime($fecha)); ?></div>
                                            <div class="fecha-mes"><?php echo date('M', strtotime($fecha)); ?></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Contenido -->
                                    <div class="evento-card-body">
                                        
                                        <div class="evento-card-header">
                                            <span class="tipo-badge">
                                                <?php echo $tipo_info['icon']; ?> <?php echo esc_html($tipo_info['label']); ?>
                                            </span>
                                        </div>
                                        
                                        <h3 class="evento-card-titulo"><?php the_title(); ?></h3>
                                        
                                        <?php if ($descripcion): ?>
                                            <p class="evento-card-descripcion"><?php echo esc_html($descripcion); ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="evento-card-meta">
                                            <div class="meta-item-card">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                                </svg>
                                                <?php echo date('H:i', strtotime($fecha)); ?>
                                            </div>
                                            
                                            <div class="meta-item-card">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                </svg>
                                                <?php echo esc_html($lugar); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="evento-card-footer">
                                            <div class="precio-card">
                                                <?php if ($precio['gratuito']): ?>
                                                    <span class="precio-gratuito">🎁 Gratis</span>
                                                <?php else: ?>
                                                    <span class="precio-valor"><?php echo esc_html($precio['texto']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <span class="ver-mas-link">
                                                Ver detalles
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                                                </svg>
                                            </span>
                                        </div>
                                        
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
                            
                            $tipos_evento = array(
                                'teatro' => array('label' => 'Teatro', 'icon' => '🎭'),
                                'musica' => array('label' => 'Música', 'icon' => '🎵'),
                                'danza' => array('label' => 'Danza', 'icon' => '💃'),
                                'exposicion' => array('label' => 'Exposición', 'icon' => '🖼️'),
                                'taller' => array('label' => 'Taller', 'icon' => '🎨'),
                                'conferencia' => array('label' => 'Conferencia', 'icon' => '🎤'),
                                'conversatorio' => array('label' => 'Conversatorio', 'icon' => '💬'),
                                'cine' => array('label' => 'Cine', 'icon' => '🎬'),
                                'literario' => array('label' => 'Literario', 'icon' => '📚'),
                                'concurso' => array('label' => 'Concurso', 'icon' => '🏆'),
                                'festival' => array('label' => 'Festival', 'icon' => '🎪'),
                                'otro' => array('label' => 'Otro', 'icon' => '🎯')
                            );
                            
                            $tipo_info = $tipos_evento[$tipo] ?? $tipos_evento['otro'];
                        ?>
                            
                            <article class="evento-card evento-pasado" data-tipo="<?php echo esc_attr($tipo); ?>">
                                
                                <a href="<?php the_permalink(); ?>" class="evento-card-link">
                                    
                                    <div class="evento-card-imagen">
                                        <?php if ($imagen): ?>
                                            <img src="<?php echo esc_url($imagen['url']); ?>" 
                                                 alt="<?php echo esc_attr($imagen['alt']); ?>"
                                                 loading="lazy"
                                                 style="filter: grayscale(50%);">
                                        <?php endif; ?>
                                        <div class="evento-card-overlay"></div>
                                        
                                        <span class="badge-estado-card" style="background: #95a5a6;">
                                            ✓ Finalizado
                                        </span>
                                        
                                        <div class="fecha-badge-card">
                                            <div class="fecha-dia"><?php echo date('j', strtotime($fecha)); ?></div>
                                            <div class="fecha-mes"><?php echo date('M', strtotime($fecha)); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="evento-card-body">
                                        
                                        <div class="evento-card-header">
                                            <span class="tipo-badge">
                                                <?php echo $tipo_info['icon']; ?> <?php echo esc_html($tipo_info['label']); ?>
                                            </span>
                                        </div>
                                        
                                        <h3 class="evento-card-titulo"><?php the_title(); ?></h3>
                                        
                                        <?php if ($descripcion): ?>
                                            <p class="evento-card-descripcion"><?php echo esc_html($descripcion); ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="evento-card-meta">
                                            <div class="meta-item-card">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                                </svg>
                                                <?php echo date('j M Y', strtotime($fecha)); ?>
                                            </div>
                                            
                                            <div class="meta-item-card">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                </svg>
                                                <?php echo esc_html($lugar); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="evento-card-footer">
                                            <span class="ver-mas-link">
                                                Ver galería
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                                                </svg>
                                            </span>
                                        </div>
                                        
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
    </section>
    
</div>

<script>
// Filtros por tipo
document.addEventListener('DOMContentLoaded', function() {
    const filtros = document.querySelectorAll('.filtro-evento-btn');
    const cards = document.querySelectorAll('.evento-card');
    
    filtros.forEach(filtro => {
        filtro.addEventListener('click', function() {
            const tipo = this.getAttribute('data-tipo');
            
            // Actualizar filtro activo
            filtros.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            
            // Filtrar cards
            cards.forEach(card => {
                const cardTipo = card.getAttribute('data-tipo');
                
                if (tipo === 'todos' || cardTipo === tipo) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.5s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Tabs: Próximos / Pasados
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.getAttribute('data-tab');
            
            // Actualizar tab activo
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Mostrar contenido correspondiente
            tabContents.forEach(content => {
                if (content.id === 'tab-' + tab) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
            
            // Resetear filtro al cambiar de tab
            const primerFiltro = document.querySelector('.filtro-evento-btn[data-tipo="todos"]');
            if (primerFiltro) {
                primerFiltro.click();
            }
        });
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