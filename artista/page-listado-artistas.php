<?php
/**
 * Template Name: Listado de Artistas
 * Plantilla para mostrar el listado completo de artistas con filtros
 *
 * @package CasaDeLaCultura
 */

get_header();

// Obtener todos los artistas publicados
$args = array(
    'post_type'      => 'artista',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
);

$artistas_query = new WP_Query($args);

// Iconos por disciplina
$disciplinas_iconos = array(
    'artes_visuales' => 'fa-eye',
    'artes_plasticas' => 'fa-paint-brush',
    'artes_literarias' => 'fa-book',
    'artes_escenicas' => 'fa-theater-masks',
    'artes_musicales' => 'fa-music',
    'artes_audiovisuales' => 'fa-film',
    'artes_digitales' => 'fa-laptop-code',
    'artes_aplicadas' => 'fa-pencil-ruler',
    'artes_tradicionales' => 'fa-landmark',
    'artes_corporales' => 'fa-running',
    'fotografia' => 'fa-camera',
    'arquitectura' => 'fa-building',
    'otra' => 'fa-ellipsis-h'
);
?>

<div class="artistas-archive-wrapper">
    <!-- Hero -->
    <section class="artistas-hero" style="background-image: url('<?php echo esc_url(get_field('imagen_hero_artistas') ?: get_template_directory_uri() . '/images/hero-artistas.jpg'); ?>');">
        <div class="artistas-hero-overlay"></div>
        <div class="container">
            <div class="artistas-hero-content">
                <h1 class="artistas-hero-titulo">
                    <i class="fas fa-palette"></i>
                    Nuestros Artistas
                </h1>
                <p class="artistas-hero-descripcion">
                    Conoce a los talentosos artistas que forman parte de la Casa de la Cultura de Tungurahua. 
                    Un espacio donde el talento local y nacional se reúne para compartir su arte con la comunidad.
                </p>
                
                <!-- Llamada a la acción para registro -->
                <div class="artistas-cta-banner">
                    <div class="cta-content">
                        <i class="fas fa-user-plus"></i>
                        <p class="cta-texto">¿Qué esperas? ¿Quieres unirte a nuestro catálogo de Artistas?</p>
                    </div>
                    <a href="https://plantilla.culturatungurahua.com/opc_5/registro-de-artistas/" class="cta-btn" target="_blank">
                        <i class="fas fa-edit"></i> Formulario
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Buscador -->
    <section class="artistas-buscador-section">
        <div class="container">
            <div class="buscador-artistas-container">
                <div class="artistas-buscador">
                    <div class="buscador-container">
                        <i class="fas fa-search"></i>
                        <input type="text" id="artistas-search" placeholder="Buscar por nombre, disciplina o especialidad..." autocomplete="off">
                        <button type="button" id="clear-search" class="clear-search" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Sección de Filtros -->
    <section class="artistas-filtros-section">
        <div class="container">
            <div class="artistas-filtros-container">
                
                <!-- Filtro "Todas" fijo -->
                <button class="chip-filtro filtro-todas active" data-disciplina="all">
                    <span class="filtro-icono"><i class="fas fa-th"></i></span>
                    <span>Todas</span>
                </button>
                
                <!-- Slider de filtros -->
                <div class="filtros-slider-wrapper">
                    <button class="slider-nav-btn prev" id="disciplinasSliderPrev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div class="disciplinas-slider" id="disciplinasSlider">
                        <div class="disciplinas-slider-track">
                            <?php 
                            // $disciplinas_iconos is defined at the top of the file
                            $disciplinas_labels = cc_get_disciplinas_labels();
                            foreach ($disciplinas_labels as $key => $label) :  
                                $icono = isset($disciplinas_iconos[$key]) ? $disciplinas_iconos[$key] : 'fa-circle';
                            ?>
                                <button class="chip-filtro" data-disciplina="<?php echo esc_attr($key); ?>">
                                    <span class="filtro-icono"><i class="fas <?php echo esc_attr($icono); ?>"></i></span>
                                    <span><?php echo esc_html($label); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <button class="slider-nav-btn next" id="disciplinasSliderNext">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                
            </div>
        </div>
    </section>

    <div class="artistas-archive-container">
        <!-- Contador de resultados -->
        <div class="artistas-resultados">
            <span id="resultados-count"></span>
        </div>
        
    <?php if ($artistas_query->have_posts()) : ?>
        <div class="artistas-grid">
            <?php while ($artistas_query->have_posts()) : $artistas_query->the_post(); 
                // Obtener datos del artista
                $disciplina_artistica = get_field('disciplina_artistica');
                $especialidad = get_field('especialidad');
                $slider = get_field('slider_imagenes');
                $imagen_destacada = !empty($slider['imagen_1']) ? $slider['imagen_1'] : null;
                
                // Etiquetas de disciplinas
                $disciplinas_labels = cc_get_disciplinas_labels();
                
                // Preparar datos para filtrado
                $disciplinas_string = is_array($disciplina_artistica) ? implode(',', $disciplina_artistica) : '';
                
                // Construir cadena de búsqueda con título, especialidad y disciplinas
                $search_parts = array();
                $search_parts[] = get_the_title();
                
                if (!empty($especialidad)) {
                    $search_parts[] = $especialidad;
                }
                
                if (is_array($disciplina_artistica)) {
                    foreach ($disciplina_artistica as $disc_key) {
                        if (isset($disciplinas_labels[$disc_key])) {
                            $search_parts[] = $disciplinas_labels[$disc_key];
                        }
                    }
                }
                
                $search_data = strtolower(implode(' ', $search_parts));
            ?>
                <article class="artista-card" 
                         data-disciplinas="<?php echo esc_attr($disciplinas_string); ?>"
                         data-search="<?php echo esc_attr($search_data); ?>">
                    <div class="artista-card-image">
                        <?php if ($imagen_destacada) : ?>
                            <img src="<?php echo esc_url($imagen_destacada['url']); ?>" 
                                 alt="<?php the_title_attribute(); ?>">
                        <?php elseif (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large'); ?>
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/artista-placeholder.jpg" 
                                 alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                        
                        <?php if (!empty($disciplina_artistica) && is_array($disciplina_artistica)) : ?>
                            <?php 
                            // Obtener primera disciplina para badge destacado
                            $primera_disciplina = reset($disciplina_artistica);
                            if (isset($disciplinas_labels[$primera_disciplina])) : 
                            ?>
                                <div class="disciplina-badge-destacado">
                                    <?php $icono = isset($disciplinas_iconos[$primera_disciplina]) ? $disciplinas_iconos[$primera_disciplina] : 'fa-circle'; ?>
                                    <i class="fas <?php echo esc_attr($icono); ?>" style="margin-right: 5px;"></i>
                                    <span><?php echo esc_html($disciplinas_labels[$primera_disciplina]); ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="artista-card-content">
                        <h2 class="artista-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        
                        <?php if (!empty($disciplina_artistica) && is_array($disciplina_artistica)) : ?>
                            <div class="artista-card-disciplina">
                                <div class="disciplina-tags">
                                    <?php 
                                    foreach ($disciplina_artistica as $disciplina_key) : 
                                        if (isset($disciplinas_labels[$disciplina_key])) : 
                                    ?>
                                            <?php $icono = isset($disciplinas_iconos[$disciplina_key]) ? $disciplinas_iconos[$disciplina_key] : 'fa-circle'; ?>
                                            <span class="disciplina-tag">
                                                <i class="fas <?php echo esc_attr($icono); ?>" style="margin-right: 4px; font-size: 0.8em;"></i>
                                                <?php echo esc_html($disciplinas_labels[$disciplina_key]); ?>
                                            </span>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="artista-card-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                    
                    <div class="artista-card-footer">
                        <a href="<?php the_permalink(); ?>" class="artista-card-btn">Ver perfil</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        
    <?php else : ?>
        <div class="artistas-empty">
            <p>No se encontraron artistas registrados.</p>
        </div>
    <?php endif; 
    
    wp_reset_postdata();
    ?>
    </div>
</div>

<!-- Script de Filtrado -->
<script>
(function() {
    var searchInput = document.getElementById('artistas-search');
    var clearBtn = document.getElementById('clear-search');
    var chipsButtons = document.querySelectorAll('.chip-filtro');
    var artistCards = document.querySelectorAll('.artista-card');
    var resultadosCount = document.getElementById('resultados-count');
    var slider = document.getElementById('disciplinasSlider');
    var sliderTrack = slider.querySelector('.disciplinas-slider-track');
    var prevBtn = document.getElementById('disciplinasSliderPrev');
    var nextBtn = document.getElementById('disciplinasSliderNext');
    
    var filtroActivo = 'all';
    var busquedaActiva = '';
    var currentScroll = 0;
    var scrollAmount = 250;
    
    // Funcionalidad del slider
    function updateSliderButtons() {
        var maxScroll = sliderTrack.scrollWidth - slider.clientWidth;
        
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
        var maxScroll = sliderTrack.scrollWidth - slider.clientWidth;
        if (currentScroll < maxScroll) {
            currentScroll = Math.min(maxScroll, currentScroll + scrollAmount);
            slider.scrollTo({
                left: currentScroll,
                behavior: 'smooth'
            });
            setTimeout(updateSliderButtons, 300);
        }
    });
    
    slider.addEventListener('scroll', function() {
        currentScroll = slider.scrollLeft;
        updateSliderButtons();
    });
    
    updateSliderButtons();
    
    function actualizarResultados() {
        var visibles = 0;
        var total = artistCards.length;
        
        artistCards.forEach(function(card) {
            var disciplinas = card.getAttribute('data-disciplinas').split(',');
            var searchData = card.getAttribute('data-search');
            
            var cumpleDisciplina = filtroActivo === 'all' || disciplinas.indexOf(filtroActivo) !== -1;
            var cumpleBusqueda = busquedaActiva === '' || searchData.indexOf(busquedaActiva) !== -1;
            
            if (cumpleDisciplina && cumpleBusqueda) {
                card.style.display = '';
                card.classList.add('artista-visible');
                visibles++;
            } else {
                card.style.display = 'none';
                card.classList.remove('artista-visible');
            }
        });
        
        // Actualizar contador
        if (busquedaActiva || filtroActivo !== 'all') {
            resultadosCount.textContent = visibles + ' de ' + total + ' Artistas';
            resultadosCount.style.display = 'block';
        } else {
            resultadosCount.textContent = total + ' Artistas en total';
            resultadosCount.style.display = 'block';
        }
        
        // Mostrar mensaje si no hay resultados
        var gridContainer = document.querySelector('.artistas-grid');
        var emptyMessage = document.querySelector('.artistas-no-resultados');
        
        if (visibles === 0) {
            if (!emptyMessage) {
                emptyMessage = document.createElement('div');
                emptyMessage.className = 'artistas-no-resultados';
                emptyMessage.innerHTML = '<i class="fas fa-search"></i><p>No se encontraron artistas con los criterios seleccionados.</p><button class="btn-limpiar-filtros">Limpiar filtros</button>';
                gridContainer.parentNode.insertBefore(emptyMessage, gridContainer.nextSibling);
                
                emptyMessage.querySelector('.btn-limpiar-filtros').addEventListener('click', limpiarFiltros);
            }
            emptyMessage.style.display = 'block';
            gridContainer.style.display = 'none';
        } else {
            if (emptyMessage) {
                emptyMessage.style.display = 'none';
            }
            gridContainer.style.display = 'flex';
        }
    }
    
    function limpiarFiltros() {
        searchInput.value = '';
        busquedaActiva = '';
        filtroActivo = 'all';
        clearBtn.style.display = 'none';
        
        chipsButtons.forEach(function(btn) {
            btn.classList.remove('active');
        });
        chipsButtons[0].classList.add('active');
        
        actualizarResultados();
    }
    
    // Event listeners para búsqueda
    searchInput.addEventListener('input', function() {
        busquedaActiva = this.value.toLowerCase().trim();
        clearBtn.style.display = busquedaActiva ? 'flex' : 'none';
        actualizarResultados();
    });
    
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        busquedaActiva = '';
        this.style.display = 'none';
        actualizarResultados();
    });
    
    // Event listeners para chips de disciplina
    chipsButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            chipsButtons.forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
            filtroActivo = this.getAttribute('data-disciplina');
            actualizarResultados();
        });
    });
    
    // Inicializar contador
    actualizarResultados();
})();
</script>

<?php get_footer(); ?>
