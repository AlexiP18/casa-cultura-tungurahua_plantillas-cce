<?php
/**
 * Template Name: Listado de Blog
 * Plantilla para mostrar el listado completo de entradas del blog institucional
 *
 * @package CasaDeLaCultura
 */

get_header();

// Obtener todas las entradas del blog publicadas
$args = array(
    'post_type'      => 'blog',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$blog_query = new WP_Query($args);

// Categorías del blog
$categorias_blog = array(
    'mensaje_directora' => array(
        'label' => 'Mensaje de la Directora',
        'icono' => 'fa-envelope',
        'color' => '#8e44ad'
    ),
    'conmemoracion' => array(
        'label' => 'Conmemoración',
        'icono' => 'fa-calendar-alt',
        'color' => '#e74c3c'
    ),
    'rendicion_cuentas' => array(
        'label' => 'Rendición de Cuentas',
        'icono' => 'fa-chart-line',
        'color' => '#16a085'
    ),
    'logros' => array(
        'label' => 'Logros y Reconocimientos',
        'icono' => 'fa-trophy',
        'color' => '#f39c12'
    ),
    'proyectos' => array(
        'label' => 'Proyectos en Curso',
        'icono' => 'fa-lightbulb',
        'color' => '#3498db'
    ),
    'reflexion' => array(
        'label' => 'Reflexión Cultural',
        'icono' => 'fa-brain',
        'color' => '#9b59b6'
    ),
    'opinion' => array(
        'label' => 'Opinión y Análisis',
        'icono' => 'fa-comments',
        'color' => '#34495e'
    ),
    'general' => array(
        'label' => 'General',
        'icono' => 'fa-edit',
        'color' => '#95a5a6'
    ),
);
?>

<div class="blog-archive-wrapper">
    <!-- Hero -->
    <section class="blog-hero" style="background-image: url('<?php echo get_template_directory_uri(); ?>/images/hero-blog.jpg');">
        <div class="blog-hero-overlay"></div>
        <div class="container">
            <div class="blog-hero-content">
                <h1 class="blog-hero-titulo">
                    <i class="fas fa-blog"></i>
                    Blog Institucional
                </h1>
                <p class="blog-hero-descripcion">
                    Reflexiones, mensajes y actualizaciones de la Casa de la Cultura. 
                    Un espacio para compartir nuestra visión y compromiso con el arte y la cultura.
                </p>
            </div>
        </div>
    </section>

    <?php if ($blog_query->have_posts()) : ?>
        <!-- Sección de Buscador -->
        <section class="blog-buscador-section">
            <div class="container">
                <div class="blog-buscador">
                    <div class="buscador-container">
                        <i class="fas fa-search"></i>
                        <input type="text" id="blog-search" placeholder="Buscar por título o categoría..." autocomplete="off">
                        <button type="button" id="clear-search" class="clear-search" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Sección de Filtros -->
        <section class="blog-filtros-section">
            <div class="container">
                <div class="blog-filtros-container">
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
                                <?php foreach ($categorias_blog as $key => $cat_data) : ?>
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

    <div class="blog-archive-container">
        <!-- Contador de resultados + filtros de atajos -->
        <div class="blog-resultados">
            <div class="blog-filtros-especiales">
                <button class="filtro-especial-btn btn-urgente" id="toggleUrgentes" title="Filtrar entradas urgentes">
                    <i class="fas fa-bell"></i>
                </button>
                <button class="filtro-especial-btn btn-destacado" id="toggleDestacados" title="Filtrar entradas destacadas">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                    </svg>
                </button>
            </div>
            <span id="resultados-count"></span>
        </div>
        
        <div class="blog-grid">
            <?php while ($blog_query->have_posts()) : $blog_query->the_post(); 
                $categoria = get_field('blog_categoria');
                $resumen = get_field('blog_resumen');
                $imagen = get_field('blog_imagen_destacada');
                $destacada = get_field('blog_destacada');
                $urgente = get_field('blog_urgente');
                $tiempo_lectura = cc_calcular_tiempo_lectura();
                
                // Preparar datos para filtrado (solo título y categoría)
                $search_parts = array();
                $search_parts[] = get_the_title();
                
                if (!empty($categoria) && isset($categorias_blog[$categoria])) {
                    $search_parts[] = $categorias_blog[$categoria]['label'];
                }
                
                $search_data = strtolower(implode(' ', $search_parts));
                
                $clase_destacada = $destacada ? ' blog-destacada-item' : '';
                $clase_urgente = $urgente ? ' blog-urgente-item' : '';
                
                $categoria_info = $categorias_blog[$categoria] ?? $categorias_blog['general'];
            ?>
                <article class="blog-card<?php echo $clase_destacada . $clase_urgente; ?>" 
                         data-categoria="<?php echo esc_attr($categoria); ?>"
                         data-search="<?php echo esc_attr($search_data); ?>">
                    
                    <a href="<?php the_permalink(); ?>" class="blog-card-link">
                        <?php if ($imagen) : ?>
                            <div class="blog-card-imagen">
                                <?php if ($destacada) : ?>
                                    <div class="badge-destacada">
                                        <i class="fas fa-star"></i> DESTACADA
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($urgente) : ?>
                                    <div class="badge-urgente">
                                        <i class="fas fa-exclamation-triangle"></i> IMPORTANTE
                                    </div>
                                <?php endif; ?>

                                <img src="<?php echo esc_url($imagen['url']); ?>" alt="<?php the_title_attribute(); ?>">
                                <div class="blog-card-overlay"></div>
                                
                                <!-- Badge de categoría -->
                                <span class="badge-categoria" style="background: <?php echo esc_attr($categoria_info['color']); ?>;">
                                    <i class="fas <?php echo esc_attr($categoria_info['icono']); ?>"></i>
                                    <?php echo esc_html($categoria_info['label']); ?>
                                </span>
                                
                                <!-- Tiempo de lectura -->
                                <div class="tiempo-lectura-badge">
                                    <i class="fas fa-clock"></i> <?php echo $tiempo_lectura; ?> min
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="blog-card-contenido">
                            <h2 class="blog-card-titulo"><?php the_title(); ?></h2>
                            
                            <?php if ($resumen) : ?>
                                <p class="blog-card-resumen"><?php echo esc_html($resumen); ?></p>
                            <?php endif; ?>
                            
                            <div class="blog-card-meta">
                                <div class="meta-item">
                                    <i class="far fa-calendar"></i>
                                    <span><?php echo get_the_date('d/m/Y'); ?></span>
                                </div>
                                
                                <?php 
                                $autor = get_the_author();
                                if ($autor) : ?>
                                    <div class="meta-item">
                                        <i class="far fa-user"></i>
                                        <span><?php echo esc_html($autor); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="blog-card-footer">
                            <span class="ver-mas-link">
                                Leer más
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </a>
                </article>
                
            <?php endwhile; ?>
        </div>
        
        <?php wp_reset_postdata(); ?>
        
    <?php else : ?>
        
        <div class="no-blog-mensaje">
            <div class="no-blog-icon">
                <i class="fas fa-blog"></i>
            </div>
            <h2>No hay entradas publicadas</h2>
            <p>Pronto encontrarás nuevas publicaciones en nuestro blog institucional</p>
        </div>
        
    <?php endif; ?>
    
    </div>
</div>

<script>
// Sistema de filtrado y búsqueda
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('blog-search');
    const clearBtn = document.getElementById('clear-search');
    const chips = document.querySelectorAll('.chip-filtro');
    const cards = document.querySelectorAll('.blog-card');
    const resultadosCount = document.getElementById('resultados-count');
    
    // Navegación de filtros
    const chipsWrapper = document.querySelector('.categorias-chips-wrapper');
    const prevBtn = document.getElementById('filtro-prev');
    const nextBtn = document.getElementById('filtro-next');
    
    let categoriaActual = 'todas';
    let terminoBusqueda = '';
    let soloDestacados = false;
    let soloUrgentes = false;
    
    const toggleDestacados = document.getElementById('toggleDestacados');
    const toggleUrgentes = document.getElementById('toggleUrgentes');

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

    // Función para actualizar resultados
    function actualizarResultados() {
        let contadorVisibles = 0;
        
        cards.forEach(card => {
            const categoria = card.getAttribute('data-categoria');
            const searchData = card.getAttribute('data-search') || '';
            
            const cumpleCategoria = (categoriaActual === 'todas' || categoria === categoriaActual);
            const cumpleBusqueda = (terminoBusqueda === '' || searchData.includes(terminoBusqueda));
            
            const esDestacado = card.classList.contains('blog-destacada-item');
            const cumpleDestacado = (!soloDestacados || esDestacado);
            
            const esUrgente = card.classList.contains('blog-urgente-item');
            const cumpleUrgente = (!soloUrgentes || esUrgente);
            
            if (cumpleCategoria && cumpleBusqueda && cumpleDestacado && cumpleUrgente) {
                card.style.display = 'block';
                card.style.animation = 'fadeInUp 0.5s ease';
                contadorVisibles++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Actualizar contador
        if (terminoBusqueda !== '' || categoriaActual !== 'todas' || soloDestacados || soloUrgentes) {
            resultadosCount.textContent = contadorVisibles + ' de ' + cards.length + ' Entradas';
            resultadosCount.style.display = 'block';
        } else {
            resultadosCount.textContent = cards.length + ' Entradas en total';
            resultadosCount.style.display = 'block';
        }
    }
    
    // Búsqueda
    searchInput.addEventListener('input', function() {
        terminoBusqueda = this.value.toLowerCase().trim();
        clearBtn.style.display = terminoBusqueda ? 'flex' : 'none';
        actualizarResultados();
    });
    
    // Limpiar búsqueda
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        terminoBusqueda = '';
        this.style.display = 'none';
        actualizarResultados();
    });
    
    // Filtros por categoría
    chips.forEach(chip => {
        chip.addEventListener('click', function() {
            categoriaActual = this.getAttribute('data-categoria');
            
            chips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            
            actualizarResultados();
        });
    });
    
    // Navegación horizontal de filtros
    function actualizarBotonesNav() {
        const scrollLeft = chipsWrapper.scrollLeft;
        const maxScroll = chipsWrapper.scrollWidth - chipsWrapper.clientWidth;
        
        if (scrollLeft <= 0) {
            prevBtn.style.opacity = '0.3';
            prevBtn.style.cursor = 'not-allowed';
        } else {
            prevBtn.style.opacity = '1';
            prevBtn.style.cursor = 'pointer';
        }
        
        if (scrollLeft >= maxScroll) {
            nextBtn.style.opacity = '0.3';
            nextBtn.style.cursor = 'not-allowed';
        } else {
            nextBtn.style.opacity = '1';
            nextBtn.style.cursor = 'pointer';
        }
    }
    
    prevBtn.addEventListener('click', function() {
        chipsWrapper.scrollBy({ left: -200, behavior: 'smooth' });
        setTimeout(actualizarBotonesNav, 300);
    });
    
    nextBtn.addEventListener('click', function() {
        chipsWrapper.scrollBy({ left: 200, behavior: 'smooth' });
        setTimeout(actualizarBotonesNav, 300);
    });
    
    chipsWrapper.addEventListener('scroll', actualizarBotonesNav);
    actualizarBotonesNav();
    
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
