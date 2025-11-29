<?php
/**
 * Plantilla para mostrar talleres individuales
 * Template Name: Single Taller
 * Template Post Type: taller
 */

get_header();
?>

<div class="taller-container">
    <div class="taller-header">
        <h1><?php the_title(); ?></h1>
    </div>
    
    <div class="taller-content">
        <div class="taller-main">
            <!-- Slider de imágenes -->
            <div class="taller-slider">
                <div class="slider-wrapper">
                    <?php 
                    $imagenes = get_field('slider_imagenes');
                    if($imagenes): 
                        if(!empty($imagenes['imagen_1'])): ?>
                            <div class="slide">
                                <img src="<?php echo esc_url($imagenes['imagen_1']['url']); ?>" 
                                     alt="<?php echo esc_attr($imagenes['imagen_1']['alt']); ?>">
                            </div>
                        <?php endif;
                        
                        if(!empty($imagenes['imagen_2'])): ?>
                            <div class="slide">
                                <img src="<?php echo esc_url($imagenes['imagen_2']['url']); ?>" 
                                     alt="<?php echo esc_attr($imagenes['imagen_2']['alt']); ?>">
                            </div>
                        <?php endif;
                        
                        if(!empty($imagenes['imagen_3'])): ?>
                            <div class="slide">
                                <img src="<?php echo esc_url($imagenes['imagen_3']['url']); ?>" 
                                     alt="<?php echo esc_attr($imagenes['imagen_3']['alt']); ?>">
                            </div>
                        <?php endif;
                    endif; ?>
                </div>
                <button class="slider-nav prev" aria-label="Anterior"><i class="fas fa-chevron-left"></i></button>
                <button class="slider-nav next" aria-label="Siguiente"><i class="fas fa-chevron-right"></i></button>
            </div>
            
            <!-- Descripción del taller -->
            <div class="taller-descripcion">
                <?php echo get_field('descripcion'); ?>
            </div>
        </div>
        
        <div class="taller-sidebar">
            <!-- Botón de detalles -->
            <div class="detalles-boton">
                <button class="btn-detalles">
                    <i class="fas fa-info-circle"></i> DETALLES
                </button>
            </div>
            
            <!-- Información del instructor -->
            <div class="taller-info">
                <div class="info-item instructor">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-user-tie"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Instructor:</span>
                        <span class="value"><?php echo esc_html(get_field('instructor')); ?></span>
                    </div>
                </div>
                
                <!-- Costo -->
                <div class="info-item costo">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-dollar-sign"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Costo:</span>
                        <span class="value">$<?php echo number_format(get_field('costo'), 2); ?></span>
                    </div>
                </div>
                
                <!-- Edad -->
                <div class="info-item edad">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-users"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Edad:</span>
                        <span class="value"><?php echo esc_html(get_field('edad')); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Horarios -->
            <div class="horario-seccion">
                <div class="horario-header">
                    <i class="fas fa-clock"></i> HORARIO
                </div>
                
                <!-- Días -->
                <div class="info-item dias">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-calendar-alt"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Días:</span>
                        <span class="value">
                            <?php 
                            $dias = get_field('dias');
                            if (is_array($dias)) {
                                echo implode(' y ', $dias);
                            } else {
                                echo esc_html($dias);
                            }
                            ?>
                        </span>
                    </div>
                </div>
                
                <!-- Horario Matutino -->
                <div class="info-item horario">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-sun"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Matutino:</span>
                        <?php 
                        $horario_matutino = get_field('horario_matutino');
                        if (is_array($horario_matutino) && !empty($horario_matutino)) {
                            echo '<span class="value">' . implode(', ', $horario_matutino) . '</span>';
                        } else if (!empty($horario_matutino)) {
                            echo '<span class="value">' . esc_html($horario_matutino) . '</span>';
                        } else {
                            echo '<span class="value">------</span>';
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Horario Vespertino -->
                <div class="info-item horario">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-moon"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Vespertino:</span>
                        <?php 
                        $horario_vespertino = get_field('horario_vespertino');
                        if (is_array($horario_vespertino) && !empty($horario_vespertino)) {
                            echo '<span class="value">' . implode(', ', $horario_vespertino) . '</span>';
                        } else if (!empty($horario_vespertino)) {
                            echo '<span class="value">' . esc_html($horario_vespertino) . '</span>';
                        } else {
                            echo '<span class="value">------</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Botón de WhatsApp -->
            <?php 
            // Generar el enlace de WhatsApp para Ecuador
            $telefono = get_field('#_de_contacto');
            $instructor = get_field('instructor');
            $curso = get_the_title();
            
            // Formatear número para Ecuador
            $telefono = preg_replace('/[^0-9]/', '', $telefono);
            if (substr($telefono, 0, 1) == '0') {
                $telefono = '593' . substr($telefono, 1);
            } else {
                $telefono = '593' . $telefono;
            }
            
            $mensaje = "Hola " . $instructor . " me podría ayudar con más información del curso " . $curso;
            $whatsapp_link = 'https://api.whatsapp.com/send?phone=' . $telefono . '&text=' . urlencode($mensaje);
            ?>
            
            <a href="<?php echo esc_url($whatsapp_link); ?>" class="whatsapp-btn" target="_blank">
                <i class="fab fa-whatsapp"></i> Contacto vía Whatsapp
            </a>
        </div>
    </div>
</div>

<!-- JavaScript para el slider -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sliderWrapper = document.querySelector('.slider-wrapper');
    const slides = document.querySelectorAll('.slide');
    const prevButton = document.querySelector('.slider-nav.prev');
    const nextButton = document.querySelector('.slider-nav.next');
    
    if (!sliderWrapper || slides.length === 0) return;
    
    let currentSlide = 0;
    const totalSlides = slides.length;
    
    // Mostrar solo si hay más de una imagen
    if (totalSlides <= 1) {
        if (prevButton) prevButton.style.display = 'none';
        if (nextButton) nextButton.style.display = 'none';
        return;
    }
    
    function updateSliderPosition() {
        sliderWrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateSliderPosition();
    }
    
    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateSliderPosition();
    }
    
    // Event listeners para los botones
    if (prevButton) {
        prevButton.addEventListener('click', prevSlide);
    }
    
    if (nextButton) {
        nextButton.addEventListener('click', nextSlide);
    }
    
    // Auto rotación cada 5 segundos
    let autoSlide = setInterval(nextSlide, 5000);
    
    // Pausar auto rotación al pasar el mouse
    sliderWrapper.addEventListener('mouseenter', function() {
        clearInterval(autoSlide);
    });
    
    // Reanudar auto rotación al salir el mouse
    sliderWrapper.addEventListener('mouseleave', function() {
        autoSlide = setInterval(nextSlide, 5000);
    });
    
    // Soporte para swipe en dispositivos táctiles
    let touchStartX = 0;
    let touchEndX = 0;
    
    sliderWrapper.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    sliderWrapper.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        if (touchEndX < touchStartX - 50) {
            nextSlide();
        }
        if (touchEndX > touchStartX + 50) {
            prevSlide();
        }
    }
});
</script>

<?php get_footer(); ?>