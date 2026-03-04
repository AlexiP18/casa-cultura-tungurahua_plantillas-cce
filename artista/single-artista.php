<?php
/**
 * Plantilla para visualizar un solo artista
 *
 * @package CasaDeLaCultura
 */

get_header();

// Obtener campos ACF
$disciplina_artistica = get_field('disciplina_artistica'); // Ahora es un array
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

// Obtener etiquetas de disciplinas (conversión de keys a labels)
$disciplinas_labels = cc_get_disciplinas_labels();

$especialidad_texto = $especialidad;

// Iconos por disciplina
$disciplina_icons = array(
    // Claves con guiones bajos (formato del sistema)
    'artes_visuales' => 'fa-palette',
    'artes_plasticas' => 'fa-paint-brush',
    'artes_literarias' => 'fa-book',
    'artes_escenicas' => 'fa-masks-theater',
    'artes_musicales' => 'fa-music',
    'artes_audiovisuales' => 'fa-video',
    'artes_digitales' => 'fa-laptop-code',
    'artes_aplicadas' => 'fa-pencil-ruler',
    'artes_tradicionales' => 'fa-landmark',
    'artes_corporales' => 'fa-person-dancing',
    'fotografia' => 'fa-camera',
    'arquitectura' => 'fa-building',
    
    // Claves antiguas o alternativas (por si acaso)
    'artes-visuales' => 'fa-palette',
    'artes-escenicas' => 'fa-masks-theater',
    'musica' => 'fa-music',
    'danza' => 'fa-person-dancing',
    'literatura' => 'fa-book',
    'cine' => 'fa-film',
    'audiovisual' => 'fa-video',
    'teatro' => 'fa-masks-theater',
    'formacion' => 'fa-graduation-cap',
    'investigacion' => 'fa-magnifying-glass',
    'patrimonio' => 'fa-landmark',
    'otros' => 'fa-star'
);
?>

<div class="artista-container">
    <header class="artista-header">
        <h1><?php the_title(); ?></h1>
        <?php if (!empty($disciplina_artistica) && is_array($disciplina_artistica)) : ?>
            <div class="artista-disciplinas">
                <div class="disciplina-tags">
                    <?php foreach ($disciplina_artistica as $disciplina_key) : ?>
                        <?php if (isset($disciplinas_labels[$disciplina_key])) : 
                            $icon_class = isset($disciplina_icons[$disciplina_key]) ? $disciplina_icons[$disciplina_key] : 'fa-star';
                        ?>
                            <span class="disciplina-tag">
                                <i class="fas <?php echo $icon_class; ?>"></i> <?php echo esc_html($disciplinas_labels[$disciplina_key]); ?>
                            </span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php if ($especialidad_texto) : ?>
                    <div style="margin-top: 10px; font-size: 1.2rem; color: #6c3483; font-style: italic;">
                        Especialidad: <?php echo esc_html($especialidad_texto); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="artista-content">
        <div class="artista-main">
            <?php if (!empty($slider)) : ?>
            <div class="artista-galeria-slider">
                <div class="galeria-slider-artista">
                    <?php
                    $count = 0;
                    for ($i = 1; $i <= 5; $i++) {
                        $imagen_key = "imagen_{$i}";
                        if (!empty($slider[$imagen_key])) :
                            $imagen = $slider[$imagen_key];
                            $active = $count === 0 ? ' active' : '';
                    ?>
                        <div class="galeria-slide-artista<?php echo $active; ?>">
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
                    <button class="galeria-btn-artista prev" onclick="cambiarSlideArtista(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="galeria-btn-artista next" onclick="cambiarSlideArtista(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <div class="galeria-dots-artista">
                        <?php
                        for ($i = 0; $i < $count; $i++) {
                            $active = $i === 0 ? ' active' : '';
                        ?>
                            <span class="dot-artista<?php echo $active; ?>" onclick="irASlideArtista(<?php echo $i; ?>)"></span>
                        <?php } ?>
                    </div>
                <?php endif; ?>
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
                <!-- Disciplinas artísticas -->
                <?php if (!empty($disciplina_artistica) && is_array($disciplina_artistica)) : ?>
                    <div class="info-item">
                        <div class="icon-wrapper">
                            <i class="fas fa-palette" aria-hidden="true"></i>
                        </div>
                        <div class="info-content">
                            <span class="label">Disciplinas:</span>
                            <div class="disciplina-tags">
                                <?php foreach ($disciplina_artistica as $disciplina_key) : ?>
                                    <?php if (isset($disciplinas_labels[$disciplina_key])) : 
                                        $icon_class = isset($disciplina_icons[$disciplina_key]) ? $disciplina_icons[$disciplina_key] : 'fa-star';
                                    ?>
                                        <span class="disciplina-tag">
                                            <i class="fas <?php echo $icon_class; ?>"></i> <?php echo esc_html($disciplinas_labels[$disciplina_key]); ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
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
            $redes = array();
            if ($facebook)  $redes[] = array('url' => $facebook,  'class' => 'facebook',  'icon' => 'fab fa-facebook-f',    'label' => 'Facebook');
            if ($instagram) $redes[] = array('url' => $instagram, 'class' => 'instagram', 'icon' => 'fab fa-instagram',      'label' => 'Instagram');
            if ($x_twitter) $redes[] = array('url' => $x_twitter, 'class' => 'x-twitter', 'icon' => 'fab fa-x-twitter',      'label' => 'X (Twitter)');
            if ($tiktok)    $redes[] = array('url' => $tiktok,    'class' => 'tiktok',    'icon' => 'fab fa-tiktok',          'label' => 'TikTok');
            if ($youtube)   $redes[] = array('url' => $youtube,   'class' => 'youtube',   'icon' => 'fab fa-youtube',         'label' => 'YouTube');
            if ($otro_url)  $redes[] = array('url' => $otro_url,  'class' => 'link',      'icon' => 'fas fa-link',            'label' => 'Enlace');

            if (!empty($redes)) :
            ?>
                <div class="artista-redes-sociales">
                    <h3>Redes Sociales</h3>
                    <div class="redes-iconos">
                        <?php foreach ($redes as $red) : ?>
                            <a href="<?php echo esc_url($red['url']); ?>" target="_blank" rel="noopener noreferrer" class="red-social <?php echo esc_attr($red['class']); ?>" aria-label="<?php echo esc_attr($red['label']); ?>">
                                <i class="<?php echo esc_attr($red['icon']); ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($numero_whatsapp) : ?>
                <a href="https://wa.me/<?php echo $numero_whatsapp; ?>" class="whatsapp-btn" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-whatsapp"></i> <span class="whatsapp-text" data-hover="WhatsApp">Contactar por WhatsApp</span>
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
// Slider de galería del artista
let slideActualArtista = 0;

function cambiarSlideArtista(direccion) {
    const slides = document.querySelectorAll('.galeria-slide-artista');
    const dots = document.querySelectorAll('.dot-artista');
    
    if (slides.length === 0) return;
    
    slides[slideActualArtista].classList.remove('active');
    if (dots.length > 0) dots[slideActualArtista].classList.remove('active');
    
    slideActualArtista = (slideActualArtista + direccion + slides.length) % slides.length;
    
    slides[slideActualArtista].classList.add('active');
    if (dots.length > 0) dots[slideActualArtista].classList.add('active');
}

function irASlideArtista(index) {
    const slides = document.querySelectorAll('.galeria-slide-artista');
    const dots = document.querySelectorAll('.dot-artista');
    
    if (slides.length === 0) return;
    
    slides[slideActualArtista].classList.remove('active');
    if (dots.length > 0) dots[slideActualArtista].classList.remove('active');
    
    slideActualArtista = index;
    
    slides[slideActualArtista].classList.add('active');
    if (dots.length > 0) dots[slideActualArtista].classList.add('active');
}

// Auto-avanzar galería
if (document.querySelectorAll('.galeria-slide-artista').length > 1) {
    setInterval(() => {
        cambiarSlideArtista(1);
    }, 5000);
}
</script>
    


<?php get_footer(); ?>