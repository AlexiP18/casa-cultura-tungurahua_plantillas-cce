<?php
/**
 * ========================================
 * SISTEMA DE IMPORTACIÓN/EXPORTACIÓN DE ARTISTAS
 * Casa de la Cultura
 * ========================================
 * 
 * Permite importar artistas desde CSV de WooCommerce
 * y exportar artistas existentes a CSV
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ========================================
 * PÁGINA DE ADMINISTRACIÓN
 * ========================================
 */

/**
 * Agregar menú de importación/exportación
 */
function cc_artistas_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=artista',
        'Importar/Exportar Artistas',
        'Importar/Exportar',
        'manage_options',
        'artistas-import-export',
        'cc_artistas_import_export_page'
    );
}
add_action('admin_menu', 'cc_artistas_admin_menu');

/**
 * Página de importación/exportación
 */
function cc_artistas_import_export_page() {
    // Verificar permisos
    if (!current_user_can('manage_options')) {
        wp_die('No tienes permisos para acceder a esta página.');
    }
    
    $mensaje = '';
    $tipo_mensaje = '';
    $resultados = array();
    
    // Procesar importación
    if (isset($_POST['cc_importar_artistas']) && check_admin_referer('cc_import_artistas_nonce')) {
        $resultado = cc_procesar_importacion_artistas();
        $mensaje = $resultado['mensaje'];
        $tipo_mensaje = $resultado['tipo'];
        $resultados = $resultado['detalles'] ?? array();
    }
    
    // Procesar exportación
    if (isset($_POST['cc_exportar_artistas']) && check_admin_referer('cc_export_artistas_nonce')) {
        cc_exportar_artistas_csv();
        exit;
    }
    
    // Contar artistas existentes
    $total_artistas = wp_count_posts('artista')->publish;
    
    ?>
    <div class="wrap">
        <h1><span class="dashicons dashicons-groups" style="font-size: 30px; margin-right: 10px;"></span> Importar/Exportar Artistas</h1>
        
        <?php if ($mensaje): ?>
            <div class="notice notice-<?php echo esc_attr($tipo_mensaje); ?> is-dismissible">
                <p><?php echo wp_kses_post($mensaje); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($resultados)): ?>
            <div class="card" style="max-width: 100%; margin-top: 20px;">
                <h3>Resultados de la Importación</h3>
                <div style="max-height: 300px; overflow-y: auto; background: #f9f9f9; padding: 15px; border-radius: 4px;">
                    <?php foreach ($resultados as $r): ?>
                        <div style="padding: 8px; margin-bottom: 5px; background: <?php echo $r['status'] === 'success' ? '#d4edda' : ($r['status'] === 'skipped' ? '#fff3cd' : '#f8d7da'); ?>; border-radius: 4px;">
                            <strong><?php echo esc_html($r['nombre']); ?></strong>
                            <span style="float: right; color: <?php echo $r['status'] === 'success' ? '#155724' : ($r['status'] === 'skipped' ? '#856404' : '#721c24'); ?>;">
                                <?php echo esc_html($r['mensaje']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div style="display: flex; gap: 30px; margin-top: 30px; flex-wrap: wrap;">
            
            <!-- IMPORTAR -->
            <div class="card" style="flex: 1; min-width: 400px; max-width: 600px;">
                <h2><span class="dashicons dashicons-upload"></span> Importar Artistas desde CSV</h2>
                <p>Sube un archivo CSV exportado de WooCommerce para importar artistas.</p>
                
                <form method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('cc_import_artistas_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="csv_file">Archivo CSV</label></th>
                            <td>
                                <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                                <p class="description">Formato: CSV exportado de WooCommerce</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Opciones</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="descargar_imagenes" value="1" checked>
                                    Descargar imágenes desde URLs
                                </label>
                                <p class="description">Las imágenes se descargarán y subirán a la biblioteca de medios</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Estado inicial</th>
                            <td>
                                <select name="estado_post">
                                    <option value="publish">Publicado</option>
                                    <option value="draft">Borrador</option>
                                    <option value="pending">Pendiente de revisión</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input type="submit" name="cc_importar_artistas" class="button button-primary button-hero" value="Importar Artistas">
                    </p>
                </form>
                
                <hr>
                
                <h3>Mapeo de Campos</h3>
                <p class="description">Soporta encabezados en inglés y español</p>
                <table class="widefat" style="margin-top: 15px;">
                    <thead>
                        <tr>
                            <th>Columna CSV (WooCommerce)</th>
                            <th>Campo Artista</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><code>Name</code> / <code>Nombre</code></td><td>Nombre del artista (título)</td></tr>
                        <tr><td><code>Short description</code> / <code>Descripción corta</code></td><td>Trayectoria</td></tr>
                        <tr><td><code>Description</code> / <code>Descripción</code></td><td>Contenido/Biografía</td></tr>
                        <tr><td><code>Categories</code> / <code>Categorías</code></td><td>Disciplina Artística</td></tr>
                        <tr><td><code>Images</code> / <code>Imágenes</code></td><td>Slider de imágenes (hasta 5)</td></tr>
                    </tbody>
                </table>
            </div>
            
            <!-- EXPORTAR -->
            <div class="card" style="flex: 1; min-width: 400px; max-width: 600px;">
                <h2><span class="dashicons dashicons-download"></span> Exportar Artistas a CSV</h2>
                <p>Descarga todos los artistas en formato CSV.</p>
                
                <div style="background: #f0f0f1; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0;">
                    <span class="dashicons dashicons-groups" style="font-size: 48px; color: #2271b1;"></span>
                    <h3 style="margin: 10px 0;"><?php echo number_format($total_artistas); ?></h3>
                    <p style="margin: 0; color: #666;">Artistas registrados</p>
                </div>
                
                <form method="post">
                    <?php wp_nonce_field('cc_export_artistas_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">Incluir en exportación</th>
                            <td>
                                <label><input type="checkbox" name="export_imagenes" value="1" checked> URLs de imágenes</label><br>
                                <label><input type="checkbox" name="export_contacto" value="1" checked> Datos de contacto</label><br>
                                <label><input type="checkbox" name="export_redes" value="1" checked> Redes sociales</label>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input type="submit" name="cc_exportar_artistas" class="button button-secondary button-hero" value="Exportar a CSV">
                    </p>
                </form>
                
                <hr>
                
                <h3>Campos Exportados</h3>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li>ID, Nombre, Slug</li>
                    <li>Disciplina Artística, Especialidad</li>
                    <li>Trayectoria, Contenido</li>
                    <li>URLs de Imágenes (5)</li>
                    <li>Teléfono, Email</li>
                    <li>Redes Sociales</li>
                    <li>Fecha de creación</li>
                </ul>
            </div>
            
        </div>
    </div>
    
    <style>
        .card { padding: 20px; background: #fff; border: 1px solid #c3c4c7; box-shadow: 0 1px 1px rgba(0,0,0,.04); }
        .card h2 { margin-top: 0; display: flex; align-items: center; gap: 10px; }
        .card h2 .dashicons { color: #2271b1; }
    </style>
    <?php
}

/**
 * ========================================
 * FUNCIÓN DE IMPORTACIÓN
 * ========================================
 */

/**
 * Buscar columna por múltiples nombres (inglés/español)
 */
function cc_buscar_columna($columnas, $nombres_posibles) {
    foreach ($nombres_posibles as $nombre) {
        if (isset($columnas[$nombre])) {
            return $columnas[$nombre];
        }
    }
    return false;
}

function cc_procesar_importacion_artistas() {
    $resultado = array(
        'mensaje' => '',
        'tipo' => 'error',
        'detalles' => array()
    );
    
    // Verificar archivo
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        $resultado['mensaje'] = 'Error al subir el archivo CSV.';
        return $resultado;
    }
    
    $archivo = $_FILES['csv_file']['tmp_name'];
    $descargar_imagenes = isset($_POST['descargar_imagenes']);
    $estado_post = sanitize_text_field($_POST['estado_post'] ?? 'publish');
    
    // Leer CSV
    $handle = fopen($archivo, 'r');
    if (!$handle) {
        $resultado['mensaje'] = 'No se pudo abrir el archivo CSV.';
        return $resultado;
    }
    
    // Obtener encabezados
    $encabezados = fgetcsv($handle);
    if (!$encabezados) {
        fclose($handle);
        $resultado['mensaje'] = 'El archivo CSV está vacío o tiene formato incorrecto.';
        return $resultado;
    }
    
    // Mapear índices de columnas (soporta inglés y español)
    $columnas = array();
    foreach ($encabezados as $index => $nombre) {
        $nombre_lower = strtolower(trim($nombre));
        $columnas[$nombre_lower] = $index;
    }
    
    // Mapeo de columnas inglés/español
    $col_nombre = cc_buscar_columna($columnas, array('name', 'nombre'));
    $col_descripcion_corta = cc_buscar_columna($columnas, array('short description', 'descripción corta', 'descripcion corta'));
    $col_descripcion = cc_buscar_columna($columnas, array('description', 'descripción', 'descripcion'));
    $col_categorias = cc_buscar_columna($columnas, array('categories', 'categorías', 'categorias'));
    $col_imagenes = cc_buscar_columna($columnas, array('images', 'imágenes', 'imagenes'));
    
    // Verificar columnas requeridas
    if ($col_nombre === false) {
        fclose($handle);
        $resultado['mensaje'] = 'El CSV no contiene la columna "Name" o "Nombre" requerida.';
        return $resultado;
    }
    
    $importados = 0;
    $omitidos = 0;
    $errores = 0;
    
    // Procesar cada fila
    while (($fila = fgetcsv($handle)) !== false) {
        $nombre = ($col_nombre !== false) ? trim($fila[$col_nombre] ?? '') : '';
        
        if (empty($nombre)) {
            continue;
        }
        
        // Verificar si ya existe
        $existe = get_posts(array(
            'post_type' => 'artista',
            'title' => $nombre,
            'posts_per_page' => 1,
            'post_status' => 'any'
        ));
        
        if (!empty($existe)) {
            $omitidos++;
            $resultado['detalles'][] = array(
                'nombre' => $nombre,
                'status' => 'skipped',
                'mensaje' => 'Ya existe'
            );
            continue;
        }
        
        // Obtener datos usando las columnas mapeadas
        $descripcion_corta = ($col_descripcion_corta !== false) ? $fila[$col_descripcion_corta] ?? '' : '';
        $descripcion = ($col_descripcion !== false) ? $fila[$col_descripcion] ?? '' : '';
        $categorias = ($col_categorias !== false) ? $fila[$col_categorias] ?? '' : '';
        $imagenes_url = ($col_imagenes !== false) ? $fila[$col_imagenes] ?? '' : '';
        
        // Crear el post
        $post_id = wp_insert_post(array(
            'post_type' => 'artista',
            'post_title' => sanitize_text_field($nombre),
            'post_content' => wp_kses_post($descripcion),
            'post_status' => $estado_post,
            'post_author' => get_current_user_id()
        ));
        
        if (is_wp_error($post_id)) {
            $errores++;
            $resultado['detalles'][] = array(
                'nombre' => $nombre,
                'status' => 'error',
                'mensaje' => 'Error al crear: ' . $post_id->get_error_message()
            );
            continue;
        }
        
        // Guardar trayectoria
        if (!empty($descripcion_corta)) {
            update_field('trayectoria', wp_kses_post($descripcion_corta), $post_id);
        }
        
        // Mapear disciplina artística
        $disciplina = cc_mapear_disciplina($categorias);
        if ($disciplina) {
            update_field('disciplina_artistica', $disciplina, $post_id);
        }
        
        // Procesar imágenes
        if (!empty($imagenes_url) && $descargar_imagenes) {
            $imagenes = array_map('trim', explode(',', $imagenes_url));
            $slider_imagenes = array();
            
            for ($i = 0; $i < min(5, count($imagenes)); $i++) {
                $url_imagen = $imagenes[$i];
                if (!empty($url_imagen) && filter_var($url_imagen, FILTER_VALIDATE_URL)) {
                    $attachment_id = cc_descargar_imagen_desde_url($url_imagen, $post_id, $nombre);
                    if ($attachment_id) {
                        $slider_imagenes['imagen_' . ($i + 1)] = $attachment_id;
                        
                        // Primera imagen como destacada
                        if ($i === 0) {
                            set_post_thumbnail($post_id, $attachment_id);
                        }
                    }
                }
            }
            
            if (!empty($slider_imagenes)) {
                update_field('slider_imagenes', $slider_imagenes, $post_id);
            }
        }
        
        $importados++;
        $resultado['detalles'][] = array(
            'nombre' => $nombre,
            'status' => 'success',
            'mensaje' => 'Importado correctamente'
        );
    }
    
    fclose($handle);
    
    $resultado['tipo'] = 'success';
    $resultado['mensaje'] = sprintf(
        'Importación completada: <strong>%d artistas importados</strong>, %d omitidos (ya existían), %d errores.',
        $importados,
        $omitidos,
        $errores
    );
    
    return $resultado;
}

/**
 * Mapear categorías de WooCommerce a disciplina artística
 */
function cc_mapear_disciplina($categorias) {
    $categorias = strtolower($categorias);
    
    $mapeo = array(
        'artes plasticas' => 'artes_plasticas',
        'artes plásticas' => 'artes_plasticas',
        'pintura' => 'artes_plasticas',
        'escultura' => 'artes_plasticas',
        'musica' => 'musica',
        'música' => 'musica',
        'danza' => 'danza',
        'baile' => 'danza',
        'teatro' => 'teatro',
        'literatura' => 'literatura',
        'letras' => 'literatura',
        'fotografia' => 'fotografia',
        'fotografía' => 'fotografia',
        'cine' => 'cine',
        'audiovisual' => 'cine',
        'artesania' => 'artesania',
        'artesanía' => 'artesania',
        'otro' => 'otro'
    );
    
    foreach ($mapeo as $buscar => $valor) {
        if (strpos($categorias, $buscar) !== false) {
            return $valor;
        }
    }
    
    return 'otro';
}

/**
 * Descargar imagen desde URL y subirla a la biblioteca de medios
 */
function cc_descargar_imagen_desde_url($url, $post_id, $titulo = '') {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    // Descargar archivo temporal
    $tmp = download_url($url);
    
    if (is_wp_error($tmp)) {
        return false;
    }
    
    // Obtener nombre del archivo
    $filename = basename(parse_url($url, PHP_URL_PATH));
    
    // Preparar array para wp_handle_sideload
    $file_array = array(
        'name' => sanitize_file_name($filename),
        'tmp_name' => $tmp
    );
    
    // Subir a la biblioteca de medios
    $attachment_id = media_handle_sideload($file_array, $post_id, $titulo);
    
    // Limpiar archivo temporal si hubo error
    if (is_wp_error($attachment_id)) {
        @unlink($tmp);
        return false;
    }
    
    return $attachment_id;
}

/**
 * ========================================
 * FUNCIÓN DE EXPORTACIÓN
 * ========================================
 */

function cc_exportar_artistas_csv() {
    $export_imagenes = isset($_POST['export_imagenes']);
    $export_contacto = isset($_POST['export_contacto']);
    $export_redes = isset($_POST['export_redes']);
    
    // Obtener todos los artistas
    $artistas = get_posts(array(
        'post_type' => 'artista',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    
    // Preparar encabezados
    $headers = array('ID', 'Nombre', 'Slug', 'Disciplina Artistica', 'Especialidad', 'Trayectoria', 'Contenido');
    
    if ($export_imagenes) {
        $headers = array_merge($headers, array('Imagen 1', 'Imagen 2', 'Imagen 3', 'Imagen 4', 'Imagen 5'));
    }
    
    if ($export_contacto) {
        $headers = array_merge($headers, array('Telefono', 'Email'));
    }
    
    if ($export_redes) {
        $headers = array_merge($headers, array('Facebook', 'Instagram', 'Twitter', 'YouTube', 'TikTok', 'Website'));
    }
    
    $headers[] = 'Fecha Creacion';
    
    // Configurar headers HTTP para descarga
    $filename = 'artistas-export-' . date('Y-m-d') . '.csv';
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Abrir output
    $output = fopen('php://output', 'w');
    
    // BOM para UTF-8 en Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Escribir encabezados
    fputcsv($output, $headers);
    
    // Escribir datos
    foreach ($artistas as $artista) {
        $post_id = $artista->ID;
        
        $fila = array(
            $post_id,
            $artista->post_title,
            $artista->post_name,
            get_field('disciplina_artistica', $post_id) ?: '',
            get_field('especialidad', $post_id) ?: '',
            get_field('trayectoria', $post_id) ?: '',
            strip_tags($artista->post_content)
        );
        
        if ($export_imagenes) {
            $slider = get_field('slider_imagenes', $post_id);
            for ($i = 1; $i <= 5; $i++) {
                $imagen = '';
                if ($slider && isset($slider['imagen_' . $i]) && is_array($slider['imagen_' . $i])) {
                    $imagen = $slider['imagen_' . $i]['url'] ?? '';
                }
                $fila[] = $imagen;
            }
        }
        
        if ($export_contacto) {
            $contacto = get_field('contacto', $post_id);
            $fila[] = $contacto['telefono'] ?? '';
            $fila[] = $contacto['email'] ?? '';
        }
        
        if ($export_redes) {
            $redes = get_field('redes_sociales', $post_id);
            $fila[] = $redes['facebook'] ?? '';
            $fila[] = $redes['instagram'] ?? '';
            $fila[] = $redes['twitter'] ?? '';
            $fila[] = $redes['youtube'] ?? '';
            $fila[] = $redes['tiktok'] ?? '';
            $fila[] = $redes['website'] ?? '';
        }
        
        $fila[] = $artista->post_date;
        
        fputcsv($output, $fila);
    }
    
    fclose($output);
    exit;
}
