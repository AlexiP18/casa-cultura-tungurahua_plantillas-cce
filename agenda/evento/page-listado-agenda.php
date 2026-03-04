<?php
/**
 * Template Name: Agenda Cultural
 * Plantilla para mostrar la agenda cultural con eventos agrupados por mes
 *
 * @package CasaDeLaCultura
 */

get_header();

// Meses en español
$meses_es = array(
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
);

$dias_es = array(
    'Monday' => 'LUNES', 'Tuesday' => 'MARTES', 'Wednesday' => 'MIÉRCOLES',
    'Thursday' => 'JUEVES', 'Friday' => 'VIERNES', 'Saturday' => 'SÁBADO', 'Sunday' => 'DOMINGO'
);

// Tipos de evento con iconos FA
$tipos_evento = array(
    'teatro'        => array('label' => 'Teatro',        'icon' => 'fa-theater-masks'),
    'musica'        => array('label' => 'Música',        'icon' => 'fa-music'),
    'danza'         => array('label' => 'Danza',         'icon' => 'fa-running'),
    'exposicion'    => array('label' => 'Exposiciones',  'icon' => 'fa-image'),
    'taller'        => array('label' => 'Talleres',      'icon' => 'fa-palette'),
    'conferencia'   => array('label' => 'Conferencias',  'icon' => 'fa-microphone'),
    'conversatorio' => array('label' => 'Conversatorios','icon' => 'fa-comments'),
    'cine'          => array('label' => 'Cine',          'icon' => 'fa-film'),
    'literario'     => array('label' => 'Literario',     'icon' => 'fa-book'),
    'concurso'      => array('label' => 'Concursos',     'icon' => 'fa-trophy'),
    'festival'      => array('label' => 'Festivales',    'icon' => 'fa-star'),
    'otro'          => array('label' => 'Otros',         'icon' => 'fa-calendar-check')
);

// Año actual
$anio_actual = date('Y');
$mes_actual = (int)date('n');

// Query: TODOS los eventos
$args_all = array(
    'post_type'      => 'evento',
    'posts_per_page' => -1,
    'meta_key'       => 'evento_fecha_inicio',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'post_status'    => 'publish',
);
$all_events = new WP_Query($args_all);

// Agrupar eventos por año > mes
$eventos_por_anio_mes = array();
$anios_disponibles = array();

if ($all_events->have_posts()) {
    while ($all_events->have_posts()) {
        $all_events->the_post();
        $fecha_inicio = get_field('evento_fecha_inicio');
        if (!$fecha_inicio) continue;

        $ts = strtotime($fecha_inicio);
        $anio = (int)date('Y', $ts);
        $mes  = (int)date('n', $ts);

        $anios_disponibles[$anio] = true;

        $fecha_fin = get_field('evento_fecha_fin');
        $ts_fin = $fecha_fin ? strtotime($fecha_fin) : null;

        // Hora
        $hora_inicio = date('H\hi', $ts);
        $hora_fin_str = $ts_fin ? date('H\hi', $ts_fin) : '';

        // Días
        $dia_inicio = (int)date('j', $ts);
        $dia_fin_num = $ts_fin ? (int)date('j', $ts_fin) : $dia_inicio;
        $dia_semana = $dias_es[date('l', $ts)] ?? '';

        // Datos del evento
        $tipo = get_field('evento_tipo') ?: 'otro';
        $tipo_info = $tipos_evento[$tipo] ?? $tipos_evento['otro'];
        $estado = cc_get_estado_evento(get_the_ID());
        $precio = cc_get_precio_evento(get_the_ID());
        $lugar = get_field('evento_lugar');
        $direccion = get_field('evento_direccion');
        $edad_minima = get_field('evento_edad_minima');
        $requiere_inscripcion = get_field('evento_requiere_inscripcion');
        $enlace_inscripcion = get_field('evento_enlace_inscripcion');
        $imagen = get_field('evento_imagen_principal');
        $es_gratuito = get_field('evento_es_gratuito');
        $ahora = current_time('timestamp');
        $es_pasado = $ts < $ahora;

        $eventos_por_anio_mes[$anio][$mes][] = array(
            'id'          => get_the_ID(),
            'titulo'      => get_the_title(),
            'permalink'   => get_permalink(),
            'tipo'        => $tipo,
            'tipo_label'  => $tipo_info['label'],
            'tipo_icon'   => $tipo_info['icon'],
            'hora_inicio' => $hora_inicio,
            'hora_fin'    => $hora_fin_str,
            'dia_inicio'  => $dia_inicio,
            'dia_fin'     => $dia_fin_num,
            'dia_semana'  => $dia_semana,
            'mes'         => $mes,
            'anio'        => $anio,
            'direccion'   => $direccion,
            'lugar'       => $lugar,
            'es_gratuito' => $es_gratuito,
            'precio'      => $precio,
            'estado'      => $estado,
            'edad_minima' => $edad_minima,
            'requiere_inscripcion' => $requiere_inscripcion,
            'enlace_inscripcion'   => $enlace_inscripcion,
            'imagen'      => $imagen,
            'es_pasado'   => $es_pasado,
            'timestamp'   => $ts,
        );
    }
    wp_reset_postdata();
}

// Obtener años disponibles
$anios_list = array_keys($anios_disponibles);
sort($anios_list);
if (empty($anios_list)) {
    $anios_list = array($anio_actual);
}
?>

<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/agenda/evento/page-listado-agenda-styles.css">

<div class="agenda-wrapper">

    <!-- ===== HEADER / TÍTULO ===== -->
    <section class="agenda-header-section">
        <div class="agenda-container">
            <h1 class="agenda-titulo">AGENDA CULTURAL</h1>
        </div>
    </section>

    <!-- ===== FILTROS: AÑO, MESES, GRATUITO ===== -->
    <section class="agenda-filtros-section">
        <div class="agenda-container">
            <div class="agenda-filtros-bar">

                <!-- Filtro Año -->
                <div class="filtro-anio-group">
                    <label class="filtro-label" for="filtroAnio">Año</label>
                    <select class="filtro-anio-select" id="filtroAnio">
                        <?php foreach ($anios_list as $a): ?>
                            <option value="<?php echo $a; ?>" <?php selected($a, $anio_actual); ?>><?php echo $a; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>



                <!-- Filtro Meses (slider horizontal, multi-select) -->
                <div class="filtro-meses-group">
                    <label class="filtro-label">Meses</label>
                    <div class="meses-slider-wrapper">
                        <button class="meses-nav-btn prev" id="mesesPrev">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="meses-slider" id="mesesSlider">
                            <div class="meses-slider-track">
                                <?php foreach ($meses_es as $num => $nombre): ?>
                                    <button class="mes-btn<?php echo ($num === $mes_actual) ? ' active' : ''; ?>" data-mes="<?php echo $num; ?>">
                                        <?php echo $nombre; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button class="meses-nav-btn next" id="mesesNext">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Toggle Gratuito -->
                <div class="filtro-gratuito-group">
                    <label class="filtro-label" for="filtroGratuito">Gratuito</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="filtroGratuito">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <!-- Botón Descargar (Icono Redondo) -->
                <div class="agenda-download-group">
                    <div class="agenda-download-dropdown">
                        <button class="download-trigger" id="downloadTrigger" title="Descargar Agenda">
                            <i class="fas fa-download"></i>
                        </button>
                        <div class="download-menu">
                            <button class="download-option" data-type="image">
                                <i class="fas fa-image"></i>
                                <span>Imagen (PNG)</span>
                            </button>
                            <button class="download-option" data-type="pdf">
                                <i class="fas fa-file-pdf"></i>
                                <span>Documento (PDF)</span>
                            </button>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section>

    <!-- ===== CUERPO: SIDEBAR + CARDS ===== -->
    <section class="agenda-body-section">
        <div class="agenda-container agenda-body-layout">

            <!-- Sidebar izquierdo: Pasados -->
            <div class="agenda-sidebar-column sidebar-col-left">
                <button class="agenda-sidebar-btn sidebar-pasados" id="btnPasados" title="Eventos Pasados">
                    <span class="sidebar-icon"><i class="fas fa-history"></i></span>
                    <span class="sidebar-text">Eventos Pasados</span>
                </button>
            </div>

            <!-- Contenido central: cards agrupados por mes -->
            <div class="agenda-cards-container" id="agendaCards">

                <?php
                // Renderizar todos los eventos como data attributes para JS filtering
                // Agrupar por año y mes
                foreach ($eventos_por_anio_mes as $anio => $meses_data):
                    foreach ($meses_data as $mes_num => $eventos_mes):
                ?>
                    <div class="agenda-mes-group" 
                         data-anio="<?php echo $anio; ?>" 
                         data-mes="<?php echo $mes_num; ?>">
                        
                        <div class="mes-header">
                            <h2 class="mes-titulo"><?php echo strtoupper($meses_es[$mes_num]); ?></h2>
                            <span class="mes-count-badge"><?php echo count($eventos_mes); ?></span>
                        </div>

                        <?php foreach ($eventos_mes as $ev): ?>
                            <a href="<?php echo esc_url($ev['permalink']); ?>" 
                               class="agenda-card" 
                               data-tipo="<?php echo esc_attr($ev['tipo']); ?>"
                               data-anio="<?php echo $ev['anio']; ?>"
                               data-mes="<?php echo $ev['mes']; ?>"
                               data-gratuito="<?php echo $ev['es_gratuito'] ? '1' : '0'; ?>"
                               data-pasado="<?php echo $ev['es_pasado'] ? '1' : '0'; ?>">

                                <!-- Fila 1 mobile: Tipo + Fecha/Hora -->
                                <div class="card-row-top">
                                    <!-- Col 1: Tipo de evento (vertical) -->
                                    <div class="card-col-tipo">
                                        <span class="tipo-icono"><i class="fas <?php echo esc_attr($ev['tipo_icon']); ?>"></i></span>
                                        <span class="tipo-label"><?php echo esc_html($ev['tipo_label']); ?></span>
                                    </div>

                                    <!-- Col 2: Hora + Fecha -->
                                    <div class="card-col-fecha">
                                        <div class="fecha-dias">
                                            <?php if ($ev['dia_inicio'] !== $ev['dia_fin']): ?>
                                                <span class="dia-rango"><?php echo $ev['dia_inicio']; ?>-<?php echo $ev['dia_fin']; ?></span>
                                            <?php else: ?>
                                                <span class="dia-unico"><?php echo $ev['dia_inicio']; ?></span>
                                            <?php endif; ?>
                                            <span class="dia-semana"><?php echo esc_html($ev['dia_semana']); ?></span>
                                        </div>
                                        <div class="fecha-hora">
                                            <i class="far fa-clock"></i>
                                            <span><?php echo esc_html($ev['hora_inicio']); ?><?php if ($ev['hora_fin'] && $ev['hora_fin'] !== $ev['hora_inicio']): ?> - <?php echo esc_html($ev['hora_fin']); ?><?php endif; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fila 2 mobile: Info + Imagen -->
                                <div class="card-row-bottom">
                                    <!-- Col 3: Info del evento -->
                                    <div class="card-col-info">
                                        <h3 class="card-titulo"><?php echo esc_html($ev['titulo']); ?></h3>
                                        
                                        <?php if ($ev['direccion']): ?>
                                            <div class="card-meta-row">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span><?php echo esc_html($ev['direccion']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($ev['lugar']): ?>
                                            <div class="card-meta-row">
                                                <i class="fas fa-home"></i>
                                                <span><?php echo esc_html($ev['lugar']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="card-meta-row">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span><?php echo $ev['es_gratuito'] ? 'Gratuito' : esc_html($ev['precio']['texto']); ?></span>
                                        </div>

                                        <!-- Badges -->
                                        <div class="card-badges">
                                            <?php if ($ev['edad_minima']): ?>
                                                <span class="badge badge-edad"><i class="fas fa-info-circle"></i> +<?php echo esc_html($ev['edad_minima']); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-edad"><i class="fas fa-info-circle"></i> Apto para todo público</span>
                                            <?php endif; ?>

                                            <?php if ($ev['requiere_inscripcion']): ?>
                                                <?php if ($ev['enlace_inscripcion']): ?>
                                                    <span class="badge badge-inscripcion badge-link" data-href="<?php echo esc_url($ev['enlace_inscripcion']); ?>">
                                                        <i class="fas fa-edit"></i> Inscripciones
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-inscripcion"><i class="fas fa-edit"></i> Inscripciones</span>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <span class="badge badge-estado" style="background: <?php echo $ev['estado']['color']; ?>; color: #fff;">
                                                <?php echo esc_html($ev['estado']['label']); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Col 4: Imagen -->
                                    <div class="card-col-imagen">
                                        <?php if ($ev['imagen'] && is_array($ev['imagen'])): ?>
                                            <img src="<?php echo esc_url($ev['imagen']['sizes']['medium'] ?? $ev['imagen']['url']); ?>" 
                                                 alt="<?php echo esc_attr($ev['titulo']); ?>" loading="lazy">
                                        <?php else: ?>
                                            <div class="card-img-placeholder">
                                                <i class="fas <?php echo esc_attr($ev['tipo_icon']); ?>"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </a>
                        <?php endforeach; ?>

                    </div>
                <?php
                    endforeach;
                endforeach;
                ?>

                <!-- Mensaje sin resultados -->
                <div class="agenda-no-results" id="agendaNoResults" style="display: none;">
                    <i class="far fa-calendar-times"></i>
                    <h3>No se encontraron eventos</h3>
                    <p>Intenta con otros filtros o cambia el período seleccionado.</p>
                </div>

            </div>

            <!-- Sidebar derecho: Próximos -->
            <div class="agenda-sidebar-column sidebar-col-right">
                <button class="agenda-sidebar-btn sidebar-proximos active" id="btnProximos" title="Próximos Eventos">
                    <span class="sidebar-icon"><i class="far fa-calendar-alt"></i></span>
                    <span class="sidebar-text">Próximos Eventos</span>
                </button>
            </div>

        </div>
    </section>

    <!-- ===== FOOTER: FILTROS POR TIPO (iconos) ===== -->
    <section class="agenda-tipos-section">
        <div class="agenda-container">

            <!-- Botones Pasados/Próximos (visible solo en mobile) -->
            <div class="agenda-footer-mode-btns">
                <button class="agenda-footer-mode-btn footer-pasados" id="footerBtnPasados" title="Eventos Pasados">
                    <i class="fas fa-history"></i>
                    <span>Pasados</span>
                </button>
                <button class="agenda-footer-mode-btn footer-proximos active" id="footerBtnProximos" title="Próximos Eventos">
                    <i class="far fa-calendar-alt"></i>
                    <span>Próximos</span>
                </button>
            </div>

            <div class="tipos-filtros-bar">
                
                <!-- Botón "Todos" -->
                <div class="tipo-filtro-item active" data-tipo="todos">
                    <div class="tipo-icono-box">
                        <i class="fas fa-list"></i>
                    </div>
                    <span class="tipo-filtro-label">Todos</span>
                </div>

                <?php foreach ($tipos_evento as $key => $info): ?>
                    <div class="tipo-filtro-item" data-tipo="<?php echo esc_attr($key); ?>">
                        <div class="tipo-icono-box">
                            <i class="fas <?php echo esc_attr($info['icon']); ?>"></i>
                        </div>
                        <span class="tipo-filtro-label"><?php echo esc_html($info['label']); ?></span>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

</div>

<!-- Librerías para captura y PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/agenda/evento/page-listado-agenda.js"></script>

<?php get_footer(); ?>
