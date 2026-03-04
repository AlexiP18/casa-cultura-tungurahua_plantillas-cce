<?php
/**
 * Template Name: Listado de Noticias
 * Plantilla para mostrar el listado completo de noticias con filtros
 *
 * @package CasaDeLaCultura
 */

get_header();

// Obtener todas las noticias publicadas
$args = array(
    'post_type'      => 'noticia',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$noticias_query = new WP_Query($args);

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

<div class="noticias-archive-wrapper">
    <!-- Hero -->
    <section class="noticias-hero" style="background-image: url('<?php echo get_template_directory_uri(); ?>/images/hero-noticias.jpg');">
        <div class="noticias-hero-overlay"></div>
        <div class="container">
            <div class="noticias-hero-content">
                <h1 class="noticias-hero-titulo">
                    <i class="fas fa-newspaper"></i>
                    Noticias y Actualidad
                </h1>
                <p class="noticias-hero-descripcion">
                    Mantente informado sobre todas nuestras actividades, eventos y las últimas novedades 
                    de la Casa de la Cultura de Tungurahua.
                </p>
            </div>
        </div>
    </section>

    <?php if ($noticias_query->have_posts()) : ?>
        <!-- Sección de Buscador -->
        <section class="noticias-buscador-section">
            <div class="container">
                <div class="noticias-buscador">
                    <div class="buscador-container">
                        <i class="fas fa-search"></i>
                        <input type="text" id="noticias-search" placeholder="Buscar noticias por título o categoría..." autocomplete="off">
                        <button type="button" id="clear-search" class="clear-search" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Sección de Filtros -->
        <section class="noticias-filtros-section">
            <div class="container">
                <div class="noticias-filtros-container">
                    <!-- Filtro "Todas" fijo -->
                    <button class="chip-filtro filtro-todas active" data-categoria="todas">
                        <i class="fas fa-th"></i>
                        <span>Todas</span>
                    </button>
                    
                    <!-- Slider de filtros -->
                    <div class="filtros-slider-wrapper">
                        <button class="filtro-nav-btn filtro-prev" id="filtro-prev">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="categorias-chips-wrapper">
                            <div class="categorias-chips">
                                <?php foreach ($categorias_noticias as $key => $cat_data) : ?>
                                    <button class="chip-filtro" data-categoria="<?php echo esc_attr($key); ?>">
                                        <i class="fas <?php echo esc_attr($cat_data['icono']); ?>"></i>
                                        <span><?php echo esc_html($cat_data['label']); ?></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button class="filtro-nav-btn filtro-next" id="filtro-next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

    <div class="noticias-archive-container">
        <!-- Contador de resultados + filtros de atajos -->
        <div class="noticias-resultados">
            <div class="noticias-filtros-especiales">
                <button class="filtro-especial-btn btn-urgente" id="toggleUrgentes" title="Filtrar noticias urgentes">
                    <i class="fas fa-bell"></i>
                </button>
                <button class="filtro-especial-btn btn-destacado" id="toggleDestacados" title="Filtrar noticias destacadas">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                </button>
            </div>
            <span id="resultados-count"></span>
        </div>
        
        <div class="noticias-grid">
                    <?php while ($noticias_query->have_posts()) : $noticias_query->the_post(); 
                $categoria = get_field('noticia_categoria');
                $resumen = get_field('noticia_resumen');
                $imagen = get_field('noticia_imagen_principal');
                $destacada = get_field('noticia_destacada');
                $urgente = get_field('noticia_urgente');
                
                // Preparar datos para filtrado (solo título y categoría)
                $search_parts = array();
                $search_parts[] = get_the_title();
                
                if (!empty($categoria) && isset($categorias_noticias[$categoria])) {
                    $search_parts[] = $categorias_noticias[$categoria]['label'];
                }
                
                $search_data = strtolower(implode(' ', $search_parts));
                
                $clase_destacada = $destacada ? ' noticia-destacada-item' : '';
                $clase_urgente = $urgente ? ' noticia-urgente-item' : '';
            ?>
                <article class="noticia-card<?php echo $clase_destacada . $clase_urgente; ?>" 
                         data-categoria="<?php echo esc_attr($categoria); ?>"
                         data-search="<?php echo esc_attr($search_data); ?>">
                    
                    <?php if ($urgente) : ?>
                        <div class="badge-urgente">
                            <i class="fas fa-exclamation-triangle"></i> URGENTE
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($destacada) : ?>
                        <div class="badge-destacada">
                            <i class="fas fa-star"></i> DESTACADA
                        </div>
                    <?php endif; ?>
                    
                    <div class="noticia-card-imagen">
                        <?php if ($imagen) : ?>
                            <img src="<?php echo esc_url($imagen['sizes']['medium_large'] ?? $imagen['url']); ?>" 
                                 alt="<?php the_title_attribute(); ?>">
                        <?php elseif (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large'); ?>
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/noticia-placeholder.jpg" 
                                 alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                        
                        <?php if ($categoria && isset($categorias_noticias[$categoria])) : ?>
                            <div class="categoria-badge categoria-<?php echo esc_attr($categoria); ?>">
                                <i class="fas <?php echo esc_attr($categorias_noticias[$categoria]['icono']); ?>"></i>
                                <span><?php echo esc_html($categorias_noticias[$categoria]['label']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="noticia-card-content">
                        <div class="noticia-card-meta">
                            <span class="noticia-fecha">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo get_the_date('d M, Y'); ?>
                            </span>
                            <?php 
                            $tiempo_lectura = cc_tiempo_lectura(get_the_content());
                            ?>
                            <span class="noticia-lectura">
                                <i class="far fa-clock"></i>
                                <?php echo $tiempo_lectura; ?> min lectura
                            </span>
                        </div>
                        
                        <h2 class="noticia-card-titulo">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        
                        <?php if ($resumen) : ?>
                            <div class="noticia-card-resumen">
                                <?php echo esc_html($resumen); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="noticia-card-footer">
                        <a href="<?php the_permalink(); ?>" class="noticia-card-btn">
                            Leer más <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <div class="noticias-empty">
            <p>No se encontraron noticias registradas.</p>
        </div>
    <?php endif; 
    
    wp_reset_postdata();
    ?>
    </div>
</div>

<!-- Script de Filtrado -->
<script>
(function() {
    var searchInput = document.getElementById('noticias-search');
    var clearBtn = document.getElementById('clear-search');
    var chipsButtons = document.querySelectorAll('.chip-filtro');
    var noticiaCards = document.querySelectorAll('.noticia-card');
    var resultadosCount = document.getElementById('resultados-count');
    
    var filtroActivo = 'todas';
    var busquedaActiva = '';
    var soloDestacados = false;
    var soloUrgentes = false;
    
    var toggleDestacados = document.getElementById('toggleDestacados');
    var toggleUrgentes = document.getElementById('toggleUrgentes');

    if (toggleDestacados) {
        toggleDestacados.addEventListener('click', function() {
            soloDestacados = !soloDestacados;
            this.classList.toggle('active', soloDestacados);
            actualizarResultados();
        });
    }

    if (toggleUrgentes) {
        toggleUrgentes.addEventListener('click', function() {
            soloUrgentes = !soloUrgentes;
            this.classList.toggle('active', soloUrgentes);
            actualizarResultados();
        });
    }

    function actualizarResultados() {
        var visibles = 0;
        var total = noticiaCards.length;
        
        noticiaCards.forEach(function(card) {
            var categoria = card.getAttribute('data-categoria');
            var searchData = card.getAttribute('data-search');
            
            var cumpleCategoria = filtroActivo === 'todas' || categoria === filtroActivo;
            var cumpleBusqueda = busquedaActiva === '' || searchData.indexOf(busquedaActiva) !== -1;
            
            var esDestacado = card.classList.contains('noticia-destacada-item');
            var cumpleDestacado = !soloDestacados || esDestacado;
            
            var esUrgente = card.classList.contains('noticia-urgente-item');
            var cumpleUrgente = !soloUrgentes || esUrgente;
            
            if (cumpleCategoria && cumpleBusqueda && cumpleDestacado && cumpleUrgente) {
                card.style.display = '';
                card.classList.add('noticia-visible');
                visibles++;
            } else {
                card.style.display = 'none';
                card.classList.remove('noticia-visible');
            }
        });
        
        // Actualizar contador
        if (busquedaActiva || filtroActivo !== 'todas' || soloDestacados || soloUrgentes) {
            resultadosCount.textContent = visibles + ' de ' + total + ' Noticias';
            resultadosCount.style.display = 'block';
        } else {
            resultadosCount.textContent = total + ' Noticias en total';
            resultadosCount.style.display = 'block';
        }
        
        // Mostrar mensaje si no hay resultados
        var gridContainer = document.querySelector('.noticias-grid');
        var emptyMessage = document.querySelector('.noticias-no-resultados');
        
        if (visibles === 0) {
            if (!emptyMessage) {
                emptyMessage = document.createElement('div');
                emptyMessage.className = 'noticias-no-resultados';
                emptyMessage.innerHTML = '<i class="fas fa-search"></i><p>No se encontraron noticias con los criterios seleccionados.</p><button class="btn-limpiar-filtros">Limpiar filtros</button>';
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
        filtroActivo = 'todas';
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
    
    // Event listeners para chips de categoría
    chipsButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            chipsButtons.forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
            filtroActivo = this.getAttribute('data-categoria');
            actualizarResultados();
        });
    });
    
    // Inicializar contador
    actualizarResultados();
    
    // Navegación de filtros con botones
    var chipsWrapper = document.querySelector('.categorias-chips-wrapper');
    var prevBtn = document.getElementById('filtro-prev');
    var nextBtn = document.getElementById('filtro-next');
    
    if (chipsWrapper && prevBtn && nextBtn) {
        prevBtn.addEventListener('click', function() {
            chipsWrapper.scrollBy({
                left: -200,
                behavior: 'smooth'
            });
        });
        
        nextBtn.addEventListener('click', function() {
            chipsWrapper.scrollBy({
                left: 200,
                behavior: 'smooth'
            });
        });
        
        // Actualizar visibilidad de botones según scroll
        function actualizarBotonesNav() {
            var scrollLeft = chipsWrapper.scrollLeft;
            var maxScroll = chipsWrapper.scrollWidth - chipsWrapper.clientWidth;
            
            if (scrollLeft <= 0) {
                prevBtn.style.opacity = '0.3';
                prevBtn.style.cursor = 'default';
            } else {
                prevBtn.style.opacity = '1';
                prevBtn.style.cursor = 'pointer';
            }
            
            if (scrollLeft >= maxScroll - 1) {
                nextBtn.style.opacity = '0.3';
                nextBtn.style.cursor = 'default';
            } else {
                nextBtn.style.opacity = '1';
                nextBtn.style.cursor = 'pointer';
            }
        }
        
        chipsWrapper.addEventListener('scroll', actualizarBotonesNav);
        actualizarBotonesNav();
    }
})();
</script>

<?php get_footer(); ?>
