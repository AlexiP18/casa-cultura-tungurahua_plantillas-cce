<?php
/**
 * Plantilla para el archivo de artistas
 *
 * @package CasaDeLaCultura
 */

get_header();
?>

<div class="artistas-archive-container">
    <header class="artistas-header">
        <h1>Nuestros Artistas</h1>
        <div class="artistas-descripcion">
            <p>Conoce a los talentosos artistas que forman parte de la Casa de la Cultura de Tungurahua. 
            Un espacio donde el talento local y nacional se reúne para compartir su arte con la comunidad.</p>
        </div>
    </header>

    <?php if (have_posts()) : ?>
        <!-- Sección de Filtros -->
        <div class="artistas-filtros">
            <!-- Buscador -->
            <div class="artistas-buscador">
                <div class="buscador-container">
                    <i class="fas fa-search"></i>
                    <input type="text" id="artistas-search" placeholder="Buscar por nombre, disciplina o especialidad..." autocomplete="off">
                    <button type="button" id="clear-search" class="clear-search" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Filtros por Disciplina -->
            <div class="disciplinas-filtro">
                <div class="filtro-label">
                    <i class="fas fa-filter"></i>
                    <span>Filtrar por disciplina:</span>
                </div>
                <div class="disciplinas-chips">
                    <button class="chip-filtro active" data-disciplina="all">
                        <span>Todas</span>
                    </button>
                    <?php 
                    $disciplinas_labels = cc_get_disciplinas_labels();
                    foreach ($disciplinas_labels as $key => $label) : 
                    ?>
                        <button class="chip-filtro" data-disciplina="<?php echo esc_attr($key); ?>">
                            <span><?php echo esc_html($label); ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Contador de resultados -->
            <div class="artistas-resultados">
                <span id="resultados-count"></span>
            </div>
        </div>

        <div class="artistas-grid">
            <?php while (have_posts()) : the_post(); 
                // Obtener datos del artista
                $disciplina_artistica = get_field('disciplina_artistica'); // Array de disciplinas
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
                                    $max_disciplinas = 3;
                                    $contador = 0;
                                    foreach ($disciplina_artistica as $disciplina_key) : 
                                        if ($contador >= $max_disciplinas) break;
                                        if (isset($disciplinas_labels[$disciplina_key])) : 
                                    ?>
                                            <span class="disciplina-tag"><?php echo esc_html($disciplinas_labels[$disciplina_key]); ?></span>
                                    <?php 
                                        $contador++;
                                        endif;
                                    endforeach; 
                                    
                                    // Mostrar "..." si hay más disciplinas
                                    if (count($disciplina_artistica) > $max_disciplinas) : 
                                    ?>
                                        <span class="disciplina-tag disciplina-mas">...</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="artista-card-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="artista-card-btn">Ver perfil</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        
        <div class="artistas-pagination">
            <?php 
            echo paginate_links(array(
                'prev_text' => '&laquo; Anterior',
                'next_text' => 'Siguiente &raquo;',
            )); 
            ?>
        </div>
    <?php else : ?>
        <div class="artistas-empty">
            <p>No se encontraron artistas registrados.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Script de Filtrado -->
<script>
(function() {
    var searchInput = document.getElementById('artistas-search');
    var clearBtn = document.getElementById('clear-search');
    var chipsButtons = document.querySelectorAll('.chip-filtro');
    var artistCards = document.querySelectorAll('.artista-card');
    var resultadosCount = document.getElementById('resultados-count');
    
    var filtroActivo = 'all';
    var busquedaActiva = '';
    
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
            resultadosCount.textContent = visibles + ' de ' + total + ' artistas';
            resultadosCount.style.display = 'block';
        } else {
            resultadosCount.textContent = total + ' artistas en total';
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