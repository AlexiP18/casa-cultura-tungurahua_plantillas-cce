<?php
/**
 * Plantilla para visualizar un solo artista
 *
 * @package CasaDeLaCultura
 */

get_header();

// Obtener campos ACF
$disciplina_artistica = get_field('disciplina_artistica');
$especialidad = get_field('especialidad');
$slider = get_field('slider_imagenes');
$trayectoria = get_field('trayectoria');

// Obtener contacto desde el grupo
$contacto = get_field('contacto');
$numero_contacto = !empty($contacto['telefono']) ? $contacto['telefono'] : '';
$correo = !empty($contacto['email']) ? $contacto['email'] : '';

// Obtener redes sociales desde el grupo
$redes_sociales = get_field('redes_sociales');
$facebook = !empty($redes_sociales['facebook']) ? $redes_sociales['facebook'] : '';
$instagram = !empty($redes_sociales['instagram']) ? $redes_sociales['instagram'] : '';
$x_twitter = !empty($redes_sociales['twitter']) ? $redes_sociales['twitter'] : '';
$youtube = !empty($redes_sociales['youtube']) ? $redes_sociales['youtube'] : '';
$tiktok = !empty($redes_sociales['tiktok']) ? $redes_sociales['tiktok'] : '';
$otro_url = !empty($redes_sociales['website']) ? $redes_sociales['website'] : '';
$web = $otro_url;

// Procesar número para WhatsApp
$numero_whatsapp = preg_replace('/[^0-9]/', '', $numero_contacto);
// Si no empieza con 593, añadir código de país para Ecuador
if (substr($numero_whatsapp, 0, 3) !== '593') {
    // Si empieza con 0, reemplazarlo con 593
    if (substr($numero_whatsapp, 0, 1) === '0') {
        $numero_whatsapp = '593' . substr($numero_whatsapp, 1);
    } else {
        $numero_whatsapp = '593' . $numero_whatsapp;
    }
}

// Disciplina y especialidad son ahora campos de texto simples
$disciplina_texto = $disciplina_artistica;
$especialidad_texto = $especialidad;
?>

<div class="artista-container">
    <header class="artista-header">
        <h1><?php the_title(); ?></h1>
        <?php if ($disciplina_texto) : ?>
            <div class="artista-disciplinas">
                <?php echo esc_html($disciplina_texto); ?>
                <?php if ($especialidad_texto) : ?>
                    <span class="especialidad"> - <?php echo esc_html($especialidad_texto); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="artista-content">
        <div class="artista-main">
            <?php if (!empty($slider)) : ?>
                <div class="artista-slider">
                    <div class="slider-wrapper">
                        <?php
                        // Imágenes del slider
                        for ($i = 1; $i <= 5; $i++) {
                            $imagen_key = "imagen_{$i}";
                            if (!empty($slider[$imagen_key])) :
                                $imagen = $slider[$imagen_key];
                        ?>
                            <div class="slide">
                                <img src="<?php echo esc_url($imagen['url']); ?>" 
                                     alt="<?php echo esc_attr($imagen['alt'] ?: get_the_title()); ?>">
                            </div>
                        <?php 
                            endif;
                        } 
                        ?>
                    </div>
                    
                    <button class="slider-nav prev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="slider-nav next">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <div class="slider-indicators">
                        <?php
                        $count = 0;
                        for ($i = 1; $i <= 5; $i++) {
                            $imagen_key = "imagen_{$i}";
                            if (!empty($slider[$imagen_key])) :
                                $active = $count === 0 ? ' active' : '';
                        ?>
                            <div class="slider-dot<?php echo $active; ?>" data-index="<?php echo $count; ?>"></div>
                        <?php
                                $count++;
                            endif;
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="artista-descripcion">
                <?php the_content(); ?>
            </div>
            
            <?php if ($trayectoria) : ?>
                <div class="artista-trayectoria">
                    <h2>Trayectoria</h2>
                    <div class="trayectoria-content">
                        <?php echo $trayectoria; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="artista-sidebar">
            <div class="artista-info">
                <!-- Disciplina y especialidad -->
                <?php if ($disciplina_texto) : ?>
                    <div class="info-item">
                        <div class="icon-wrapper">
                            <i class="fas fa-palette" aria-hidden="true"></i>
                        </div>
                        <div class="info-content">
                            <span class="label">Disciplina:</span>
                            <span class="value"><?php echo esc_html($disciplina_texto); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($especialidad_texto) : ?>
                    <div class="info-item">
                        <div class="icon-wrapper">
                            <i class="fas fa-star" aria-hidden="true"></i>
                        </div>
                        <div class="info-content">
                            <span class="label">Especialidad:</span>
                            <span class="value"><?php echo esc_html($especialidad_texto); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($numero_contacto) : ?>
                    <div class="info-item">
                        <div class="icon-wrapper">
                            <i class="fas fa-phone" aria-hidden="true"></i>
                        </div>
                        <div class="info-content">
                            <span class="label">Teléfono:</span>
                            <span class="value">
                                <a href="tel:<?php echo esc_attr($numero_contacto); ?>">
                                    <?php echo esc_html($numero_contacto); ?>
                                </a>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            
                <?php if ($correo) : ?>
                    <div class="info-item">
                        <div class="icon-wrapper">
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                        </div>
                        <div class="info-content">
                            <span class="label">Correo:</span>
                            <span class="value">
                                <a href="mailto:<?php echo esc_attr($correo); ?>">
                                    <?php echo esc_html($correo); ?>
                                </a>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            
                <?php if ($web) : ?>
                    <div class="info-item">
                        <div class="icon-wrapper">
                            <i class="fas fa-globe" aria-hidden="true"></i>
                        </div>
                        <div class="info-content">
                            <span class="label">Página Web:</span>
                            <span class="value">
                                <a href="<?php echo esc_url($web); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html(preg_replace('#^https?://#', '', $web)); ?>
                                    <i class="fas fa-external-link-alt" aria-hidden="true" style="font-size: 0.75em; margin-left: 4px; opacity: 0.7;"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Redes Sociales -->
            <?php
            $tiene_redes = $facebook || $instagram || $x_twitter || $youtube || $tiktok || $otro_url;
            
            if ($tiene_redes) : 
            ?>
                <!-- Reemplaza la sección de redes sociales con este código actualizado -->
                <div class="artista-redes-sociales">
                    <h3>Redes Sociales</h3>
                    <div class="redes-iconos">
                        <?php if ($facebook) : ?>
                            <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" class="red-social facebook" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($instagram) : ?>
                            <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" class="red-social instagram" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($x_twitter) : ?>
                            <a href="<?php echo esc_url($x_twitter); ?>" target="_blank" rel="noopener noreferrer" class="red-social twitter" aria-label="X/Twitter">
                                <i class="fab fa-twitter"></i> <!-- Cambiado de fa-x-twitter a fa-twitter -->
                            </a>
                        <?php endif; ?>
                
                        <?php if ($tiktok) : ?>
                            <a href="<?php echo esc_url($tiktok); ?>" target="_blank" rel="noopener noreferrer" class="red-social tiktok" aria-label="TikTok">
                                <i class="fa-brands fa-tiktok"></i> <!-- Aseguramos que usamos la clase correcta -->
                            </a>
                        <?php endif; ?>
                
                        <?php if ($youtube) : ?>
                            <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener noreferrer" class="red-social youtube" aria-label="YouTube">
                                <i class="fa-brands fa-youtube"></i> <!-- Aseguramos que usamos la clase correcta -->
                            </a>
                        <?php endif; ?>
                
                        <?php if ($otro_url) : ?>
                            <a href="<?php echo esc_url($otro_url); ?>" target="_blank" rel="noopener noreferrer" class="red-social link" aria-label="Otros Enlaces">
                                <i class="fa-solid fa-link"></i> <!-- Cambiado a un ícono más reconocible -->
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($numero_whatsapp) : ?>
                <a href="https://wa.me/<?php echo $numero_whatsapp; ?>" class="whatsapp-btn" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-whatsapp"></i> Contactar por WhatsApp
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript para el slider -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sliderWrapper = document.querySelector('.slider-wrapper');
    const slides = document.querySelectorAll('.slide');
    const dotsContainer = document.querySelector('.slider-indicators');
    const dots = document.querySelectorAll('.slider-dot');
    const prevButton = document.querySelector('.slider-nav.prev');
    const nextButton = document.querySelector('.slider-nav.next');
    
    if (!sliderWrapper || slides.length === 0) return;
    
    let currentSlide = 0;
    
    // Inicializar slider
    updateSliderPosition();
    
    // Event listeners
    if (prevButton) {
        prevButton.addEventListener('click', prevSlide);
    }
    
    if (nextButton) {
        nextButton.addEventListener('click', nextSlide);
    }
    
    if (dots.length > 0) {
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                currentSlide = parseInt(this.dataset.index);
                updateSliderPosition();
            });
        });
    }
    
    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        updateSliderPosition();
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        updateSliderPosition();
    }
    
    function updateSliderPosition() {
        sliderWrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        // Actualizar dots
        if (dots.length > 0) {
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentSlide].classList.add('active');
        }
    }
    
    // Auto rotación (opcional)
    let interval = setInterval(nextSlide, 5000);
    
    sliderWrapper.addEventListener('mouseenter', () => clearInterval(interval));
    sliderWrapper.addEventListener('mouseleave', () => {
        interval = setInterval(nextSlide, 5000);
    });
});
</script>

<?php get_footer(); ?>