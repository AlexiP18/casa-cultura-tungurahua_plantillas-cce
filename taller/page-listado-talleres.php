<?php
/**
 * Template Name: Listado de Talleres
 * Description: Página para mostrar el listado de talleres con filtros
 */

get_header();

// Construcción de la consulta - cargar TODOS los talleres (filtrado por JavaScript)
$args = array(
    'post_type' => 'taller',
    'posts_per_page' => -1, // Cargar todos para filtrado JS
    'orderby' => 'date',
    'order' => 'DESC'
);

$talleres_query = new WP_Query($args);

// Categorías disponibles del taller
$categorias = array(
    'todos' => array('label' => 'Todos', 'icon' => 'fa-th', 'color' => '#3498db'),
    'arte' => array('label' => 'Arte', 'icon' => 'fa-palette', 'color' => '#e74c3c'),
    'canto' => array('label' => 'Canto', 'icon' => 'fa-microphone', 'color' => '#9b59b6'),
    'danza' => array('label' => 'Danza', 'icon' => 'fa-music', 'color' => '#f39c12'),
    'musica' => array('label' => 'Música', 'icon' => 'fa-guitar', 'color' => '#3498db'),
    'teatro' => array('label' => 'Teatro', 'icon' => 'fa-theater-masks', 'color' => '#e67e22'),
    'otros' => array('label' => 'Otros', 'icon' => 'fa-star', 'color' => '#95a5a6')
);
?>

<div class="talleres-archive-wrapper">
    <!-- Hero -->
    <section class="talleres-hero" style="background-image: url('<?php echo esc_url(get_field('imagen_hero_talleres') ?: get_template_directory_uri() . '/images/hero-talleres.jpg'); ?>');">
        <div class="talleres-hero-overlay"></div>
        <div class="container">
            <div class="talleres-hero-content">
                <h1 class="talleres-hero-titulo">
                    <i class="fas fa-book-reader"></i>
                    Talleres Culturales
                </h1>
                <p class="talleres-hero-descripcion">
                    Descubre nuestra amplia oferta de talleres artísticos y culturales. 
                    Aprende, crea y desarrolla tu talento con instructores profesionales.
                </p>
            </div>
        </div>
    </section>

    <!-- Barra de búsqueda -->
    <section class="talleres-buscador-section">
        <div class="container">
            <div class="talleres-buscador">
                <div class="buscador-container">
                    <i class="fas fa-search"></i>
                    <input 
                        type="text" 
                        id="talleres-search"
                        placeholder="Buscar por nombre, categoría o instructor..." 
                        autocomplete="off"
                    >
                    <button type="button" id="clear-search" class="clear-search" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Filtros de categoría -->
    <section class="talleres-filtros-section">
        <div class="container">
            <!-- Filtros de categoría -->
            <div class="filtros-categorias-container">
                <div class="filtro-categoria-todos">
                    <button type="button" data-categoria="todos" class="filtro-categoria-btn active">
                        <span class="filtro-icono" style="color: <?php echo $categorias['todos']['color']; ?>">
                            <i class="fas <?php echo $categorias['todos']['icon']; ?>"></i>
                        </span>
                        <span class="filtro-texto">Todos</span>
                    </button>
                </div>

                <div class="filtros-categoria-lista">
                    <?php foreach ($categorias as $cat_key => $cat_data): 
                        if ($cat_key === 'todos') continue;
                    ?>
                        <button type="button" data-categoria="<?php echo esc_attr($cat_key); ?>" class="filtro-categoria-btn">
                            <span class="filtro-icono" style="color: <?php echo $cat_data['color']; ?>">
                                <i class="fas <?php echo $cat_data['icon']; ?>"></i>
                            </span>
                            <span class="filtro-texto"><?php echo $cat_data['label']; ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Listado de talleres -->
    <section class="talleres-listado-section">
        <div class="container">
            <!-- Contador de resultados -->
            <div class="talleres-resultados">
                <span id="talleres-count"></span>
            </div>
            
            <?php if ($talleres_query->have_posts()): ?>
                <div class="talleres-grid">
                    <?php while ($talleres_query->have_posts()): $talleres_query->the_post(); 
                        $imagenes = get_field('slider_imagenes');
                        $categoria = get_field('categoria_taller');
                        $instructor = get_field('instructor');
                        $costo = get_field('costo');
                        $edad = get_field('edad');
                        
                        // Imagen principal
                        $imagen_url = '';
                        if (!empty($imagenes['imagen_1'])) {
                            $imagen_url = $imagenes['imagen_1']['url'];
                        } elseif (has_post_thumbnail()) {
                            $imagen_url = get_the_post_thumbnail_url(null, 'large');
                        }
                        
                        // Color de categoría
                        $cat_color = isset($categorias[$categoria]) ? $categorias[$categoria]['color'] : '#3498db';
                        $cat_icon = isset($categorias[$categoria]) ? $categorias[$categoria]['icon'] : 'fa-star';
                        $cat_label = isset($categorias[$categoria]) ? $categorias[$categoria]['label'] : ucfirst($categoria);
                    ?>
                        <article class="taller-card" data-categoria="<?php echo esc_attr($categoria); ?>">
                            <a href="<?php the_permalink(); ?>" class="taller-card-link">
                                <!-- Imagen -->
                                <div class="taller-card-imagen">
                                    <?php if ($imagen_url): ?>
                                        <img src="<?php echo esc_url($imagen_url); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php endif; ?>
                                    <div class="taller-card-overlay"></div>
                                    
                                    <!-- Badge de categoría -->
                                    <span class="badge-categoria-card" style="background: <?php echo $cat_color; ?>;">
                                        <i class="fas <?php echo $cat_icon; ?>"></i>
                                        <?php echo $cat_label; ?>
                                    </span>
                                </div>

                                <!-- Cuerpo -->
                                <div class="taller-card-body">
                                    <h3 class="taller-card-titulo"><?php the_title(); ?></h3>
                                    
                                    <?php if ($instructor): ?>
                                        <div class="taller-card-instructor">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12z"/>
                                            </svg>
                                            <?php echo esc_html($instructor); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="taller-card-meta">
                                        <?php if ($edad): ?>
                                            <div class="meta-item-card-taller">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                                                </svg>
                                                <?php echo esc_html($edad); ?> años
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="taller-card-footer">
                                    <div class="precio-card-taller">
                                        <?php if ($costo): ?>
                                            <span class="precio-valor-taller">$<?php echo number_format($costo, 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ver-mas-link-taller">
                                        Ver detalles
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- Paginación -->
                <?php if ($talleres_query->max_num_pages > 1): ?>
                    <div class="talleres-paginacion">
                        <?php
                        echo paginate_links(array(
                            'total' => $talleres_query->max_num_pages,
                            'prev_text' => '<i class="fas fa-chevron-left"></i>',
                            'next_text' => '<i class="fas fa-chevron-right"></i>',
                        ));
                        ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- No hay resultados -->
                <div class="no-talleres-mensaje">
                    <div class="no-talleres-icon">
                        <svg width="80" height="80" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M4.285 9.567a.5.5 0 0 1 .683.183A3.498 3.498 0 0 0 8 11.5a3.498 3.498 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.498 4.498 0 0 1 8 12.5a4.498 4.498 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
                        </svg>
                    </div>
                    <h2>No se encontraron talleres</h2>
                    <p>
                        No hay talleres disponibles en este momento.
                    </p>
                </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>
</div>

<script>
// Filtros por categoría y búsqueda sin recarga
document.addEventListener('DOMContentLoaded', function() {
    const filtros = document.querySelectorAll('.filtro-categoria-btn');
    const cards = document.querySelectorAll('.taller-card');
    const talleresCount = document.getElementById('talleres-count');
    const searchInput = document.getElementById('talleres-search');
    const clearBtn = document.getElementById('clear-search');
    const totalCards = cards.length;
    
    let categoriaActiva = 'todos';
    let busquedaActiva = '';
    
    // Inicializar contador
    function actualizarContador(visibles) {
        if (visibles === totalCards) {
            talleresCount.textContent = totalCards + ' Talleres en total';
        } else {
            talleresCount.textContent = visibles + ' de ' + totalCards + ' Talleres';
        }
        talleresCount.style.display = 'block';
    }
    
    // Función para filtrar cards
    function filtrarCards() {
        let contadorVisibles = 0;
        
        cards.forEach(card => {
            const cardCategoria = card.getAttribute('data-categoria');
            const cardTitulo = card.querySelector('.taller-card-titulo').textContent.toLowerCase();
            
            const cumpleCategoria = categoriaActiva === 'todos' || cardCategoria === categoriaActiva;
            const cumpleBusqueda = busquedaActiva === '' || cardTitulo.includes(busquedaActiva);
            
            if (cumpleCategoria && cumpleBusqueda) {
                card.style.display = 'block';
                card.style.animation = 'fadeInUp 0.5s ease';
                contadorVisibles++;
            } else {
                card.style.display = 'none';
            }
        });
        
        actualizarContador(contadorVisibles);
    }
    
    // Mostrar todos al inicio
    actualizarContador(totalCards);
    
    // Filtros de categoría
    filtros.forEach(filtro => {
        filtro.addEventListener('click', function() {
            categoriaActiva = this.getAttribute('data-categoria');
            
            // Actualizar filtro activo
            filtros.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            
            filtrarCards();
        });
    });
    
    // Búsqueda en tiempo real
    searchInput.addEventListener('input', function() {
        busquedaActiva = this.value.toLowerCase().trim();
        clearBtn.style.display = busquedaActiva ? 'flex' : 'none';
        filtrarCards();
    });
    
    // Botón limpiar búsqueda
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        busquedaActiva = '';
        this.style.display = 'none';
        filtrarCards();
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
