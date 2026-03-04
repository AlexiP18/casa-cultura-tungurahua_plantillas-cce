<?php
/**
 * Plantilla para mostrar talleres individuales
 * Template Name: Single Taller
 * Template Post Type: taller
 */

get_header();

// Obtener datos del taller
$imagenes = get_field('slider_imagenes');
$descripcion = get_field('descripcion');
$categoria = get_field('categoria_taller');
$instructor = get_field('instructor');
$costo = get_field('costo');
$edad = get_field('edad');
$dias = get_field('dias');
$horario_matutino = get_field('horario_matutino');
$horario_vespertino = get_field('horario_vespertino');
$telefono = get_field('#_de_contacto');

// Iconos por categoría
$categoria_icons = array(
    'arte' => 'fa-palette',
    'canto' => 'fa-microphone',
    'danza' => 'fa-music',
    'musica' => 'fa-guitar',
    'teatro' => 'fa-theater-masks',
    'otros' => 'fa-star'
);

$categoria_colors = array(
    'arte' => '#e74c3c',
    'canto' => '#9b59b6',
    'danza' => '#f39c12',
    'musica' => '#3498db',
    'teatro' => '#e67e22',
    'otros' => '#95a5a6'
);

$cat_icon = isset($categoria_icons[$categoria]) ? $categoria_icons[$categoria] : 'fa-star';
$cat_color = isset($categoria_colors[$categoria]) ? $categoria_colors[$categoria] : '#3498db';

// Generar enlace de WhatsApp
$whatsapp_link = '';
if ($telefono) {
    $tel = preg_replace('/[^0-9]/', '', $telefono);
    if (substr($tel, 0, 1) == '0') {
        $tel = '593' . substr($tel, 1);
    } else {
        $tel = '593' . $tel;
    }
    $mensaje = "Hola " . $instructor . ", me podría ayudar con más información del curso " . get_the_title();
    $whatsapp_link = 'https://api.whatsapp.com/send?phone=' . $tel . '&text=' . urlencode($mensaje);
}
?>

<div class="taller-container">
    <div class="taller-header">
        <?php if ($categoria): ?>
            <span class="taller-categoria" style="background: <?php echo $cat_color; ?>;">
                <i class="fas <?php echo $cat_icon; ?>"></i> <?php echo ucfirst($categoria); ?>
            </span>
        <?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <?php if ($instructor): ?>
            <p class="taller-subtitulo"><i class="fas fa-user-tie"></i> Instructor: <?php echo esc_html($instructor); ?></p>
        <?php endif; ?>
    </div>
    
    <div class="taller-content">
        <div class="taller-main">
            <!-- Slider de imágenes -->
            <?php if (!empty($imagenes)) : ?>
            <div class="taller-galeria-slider">
                <div class="galeria-slider-taller">
                    <?php
                    $count = 0;
                    for ($i = 1; $i <= 3; $i++) {
                        $imagen_key = "imagen_{$i}";
                        if (!empty($imagenes[$imagen_key])) :
                            $imagen = $imagenes[$imagen_key];
                            $active = $count === 0 ? ' active' : '';
                    ?>
                        <div class="galeria-slide-taller<?php echo $active; ?>">
                            <img src="<?php echo esc_url($imagen['url']); ?>" 
                                 alt="<?php echo esc_attr($imagen['alt'] ?: get_the_title()); ?>">
                        </div>
                    <?php 
                            $count++;
                        endif;
                    } 
                    ?>
                </div>
                
                <?php if ($count > 1): ?>
                    <button class="galeria-btn-taller prev" onclick="cambiarSlideTaller(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="galeria-btn-taller next" onclick="cambiarSlideTaller(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <div class="galeria-dots-taller">
                        <?php
                        for ($i = 0; $i < $count; $i++) {
                            $active = $i === 0 ? ' active' : '';
                        ?>
                            <span class="dot-taller<?php echo $active; ?>" onclick="irASlideTaller(<?php echo $i; ?>)"></span>
                        <?php } ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Descripción del taller -->
            <div class="seccion-header-taller">
                <span class="btn-seccion-taller">
                    <i class="fas fa-info-circle"></i> ACERCA DEL TALLER
                </span>
            </div>
            <div class="taller-descripcion">
                <?php echo $descripcion; ?>
            </div>
        </div>
        
        <div class="taller-sidebar">
            <!-- Botón de detalles -->
            <div class="detalles-boton">
                <button class="btn-detalles">
                    <i class="fas fa-info-circle"></i> DETALLES
                </button>
            </div>
            
            <!-- Información del taller -->
            <div class="taller-info">
                <?php if ($instructor): ?>
                <div class="info-item instructor">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-user-tie"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Instructor:</span>
                        <span class="value"><?php echo esc_html($instructor); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($costo !== null && $costo !== ''): ?>
                <div class="info-item costo">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-dollar-sign"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Costo:</span>
                        <span class="value precio">$<?php echo number_format($costo, 2); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($edad): ?>
                <div class="info-item edad">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-users"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Edad:</span>
                        <span class="value"><?php echo esc_html($edad); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Horarios -->
            <div class="detalles-boton taller-seccion-titulo">
                <span class="btn-detalles">
                    <i class="fas fa-clock"></i> HORARIO
                </span>
            </div>
            <div class="horario-seccion">
                
                <?php if ($dias): ?>
                <div class="info-item dias">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-calendar-alt"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Días:</span>
                        <span class="value">
                            <?php 
                            if (is_array($dias)) {
                                echo implode(' y ', $dias);
                            } else {
                                echo esc_html($dias);
                            }
                            ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="info-item horario">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-sun"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Matutino:</span>
                        <?php 
                        if (is_array($horario_matutino) && !empty($horario_matutino)) {
                            echo '<span class="value">' . implode(', ', $horario_matutino) . '</span>';
                        } else if (!empty($horario_matutino)) {
                            echo '<span class="value">' . esc_html($horario_matutino) . '</span>';
                        } else {
                            echo '<span class="value sin-horario">------</span>';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="info-item horario">
                    <div class="icon-wrapper">
                        <i class="icon fas fa-moon"></i>
                    </div>
                    <div class="info-content">
                        <span class="label">Vespertino:</span>
                        <?php 
                        if (is_array($horario_vespertino) && !empty($horario_vespertino)) {
                            echo '<span class="value">' . implode(', ', $horario_vespertino) . '</span>';
                        } else if (!empty($horario_vespertino)) {
                            echo '<span class="value">' . esc_html($horario_vespertino) . '</span>';
                        } else {
                            echo '<span class="value sin-horario">------</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Botón de WhatsApp -->
            <?php if ($whatsapp_link): ?>
            <a href="<?php echo esc_url($whatsapp_link); ?>" class="whatsapp-btn" target="_blank">
                <i class="fab fa-whatsapp"></i> Contacto vía Whatsapp
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Widget de Compartir -->
<?php include(get_stylesheet_directory() . '/compartir-widget.php'); ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/compartir-widget-styles.css">
<script src="<?php echo get_stylesheet_directory_uri(); ?>/compartir-widget.js"></script>

<!-- JavaScript para el slider -->
<script>
// Slider de galería del taller
let slideActualTaller = 0;

function cambiarSlideTaller(direccion) {
    const slides = document.querySelectorAll('.galeria-slide-taller');
    const dots = document.querySelectorAll('.dot-taller');
    
    if (slides.length === 0) return;
    
    slides[slideActualTaller].classList.remove('active');
    if (dots.length > 0) dots[slideActualTaller].classList.remove('active');
    
    slideActualTaller = (slideActualTaller + direccion + slides.length) % slides.length;
    
    slides[slideActualTaller].classList.add('active');
    if (dots.length > 0) dots[slideActualTaller].classList.add('active');
}

function irASlideTaller(index) {
    const slides = document.querySelectorAll('.galeria-slide-taller');
    const dots = document.querySelectorAll('.dot-taller');
    
    if (slides.length === 0) return;
    
    slides[slideActualTaller].classList.remove('active');
    if (dots.length > 0) dots[slideActualTaller].classList.remove('active');
    
    slideActualTaller = index;
    
    slides[slideActualTaller].classList.add('active');
    if (dots.length > 0) dots[slideActualTaller].classList.add('active');
}

// Auto-avanzar galería
if (document.querySelectorAll('.galeria-slide-taller').length > 1) {
    setInterval(() => {
        cambiarSlideTaller(1);
    }, 5000);
}
</script>
    

    


<?php get_footer(); ?>
