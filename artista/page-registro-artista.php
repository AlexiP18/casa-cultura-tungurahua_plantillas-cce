<?php
/**
 * Template Name: Registro de Artista
 * Formulario público para registro de artistas
 * Los registros quedan pendientes de aprobación
 *
 * @package CasaDeLaCultura
 */

get_header();

// Verificar si el formulario fue enviado exitosamente
$success = isset($_GET['submitted']) && $_GET['submitted'] === 'true';

// Obtener disciplinas para el formulario manual
$disciplinas_labels = cc_get_disciplinas_labels();
?>

<div class="registro-artista-container">
    <header class="registro-header">
        <h1>Registro de Artista</h1>
        <p class="registro-descripcion">
            Completa este formulario para registrarte como artista en la Casa de la Cultura de Tungurahua. 
            Tu perfil será revisado por nuestro equipo antes de ser publicado.
        </p>
    </header>

    <?php if ($success) : ?>
        <div class="registro-mensaje-exito">
            <div class="mensaje-icono">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>¡Registro Exitoso!</h2>
            <p>
                Gracias por registrarte. Tu perfil ha sido enviado y está siendo revisado por nuestro equipo. 
                Te notificaremos por correo electrónico cuando tu perfil sea aprobado y publicado.
            </p>
            <a href="<?php echo home_url('/artistas'); ?>" class="btn-volver">Ver Artistas Publicados</a>
        </div>
    <?php else : ?>
        <div class="registro-formulario">
            <div class="registro-instrucciones">
                <i class="fas fa-info-circle"></i>
                <p>Todos los campos marcados con <span class="campo-requerido">*</span> son obligatorios.</p>
            </div>
            
            <form id="form-registro-artista" method="POST" enctype="multipart/form-data">
                <?php wp_nonce_field('registro_artista_nonce', '_registro_artista_nonce'); ?>
                
                <!-- SECCIÓN: Información Personal -->
                <div class="form-seccion">
                    <h2 class="form-seccion-titulo">
                        <i class="fas fa-user"></i> Información Personal
                    </h2>
                    
                    <div class="form-grupo">
                        <label for="nombre_completo">Nombre Completo <span class="campo-requerido">*</span></label>
                        <input type="text" id="nombre_completo" name="nombre_completo" required 
                               maxlength="100"
                               placeholder="Ej: Juan Carlos Pérez López">
                        <span class="char-counter"><span class="char-count">0</span>/100</span>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="descripcion">Descripción / Biografía Breve <span class="campo-requerido">*</span></label>
                        <textarea id="descripcion" name="descripcion" rows="4" required
                                  maxlength="500"
                                  placeholder="Escribe una breve descripción sobre ti como artista..."></textarea>
                        <span class="char-counter"><span class="char-count">0</span>/500</span>
                    </div>
                </div>

                <!-- SECCIÓN: Disciplinas y Especialidad -->
                <div class="form-seccion">
                    <h2 class="form-seccion-titulo">
                        <i class="fas fa-palette"></i> Disciplinas Artísticas
                    </h2>
                    
                    <div class="form-grupo">
                        <label>Disciplina Artística <span class="campo-requerido">*</span></label>
                        <p class="form-ayuda">Selecciona una o más disciplinas artísticas que practiques.</p>
                        <div class="checkbox-grid">
                            <?php foreach ($disciplinas_labels as $key => $label) : ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="disciplina_artistica[]" value="<?php echo esc_attr($key); ?>">
                                    <span class="checkbox-label"><?php echo esc_html($label); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" id="disciplina_required" name="disciplina_required" value="">
                    </div>
                    
                    <div class="form-grupo">
                        <label for="especialidad">Especialidad</label>
                        <textarea id="especialidad" name="especialidad" rows="3"
                                  maxlength="300"
                                  placeholder="Ej: Pintura al óleo, Arte abstracto, Retrato / Poesía contemporánea, Narrativa / Jazz, Piano, Composición"></textarea>
                        <span class="char-counter"><span class="char-count">0</span>/300</span>
                        <p class="form-ayuda">Detalla tus áreas de especialización dentro de las disciplinas seleccionadas.</p>
                    </div>
                </div>
                
                <!-- SECCIÓN: Trayectoria -->
                <div class="form-seccion">
                    <h2 class="form-seccion-titulo">
                        <i class="fas fa-road"></i> Trayectoria
                    </h2>
                    
                    <div class="form-grupo">
                        <label for="trayectoria">Trayectoria Artística</label>
                        <textarea id="trayectoria" name="trayectoria" rows="6"
                                  maxlength="2000"
                                  placeholder="Describe tu trayectoria artística: exposiciones, premios, participaciones, formación académica, experiencia, etc."></textarea>
                        <span class="char-counter"><span class="char-count">0</span>/2000</span>
                    </div>
                </div>

                <!-- SECCIÓN: Imágenes -->
                <div class="form-seccion">
                    <h2 class="form-seccion-titulo">
                        <i class="fas fa-images"></i> Imágenes
                    </h2>
                    <p class="form-ayuda">Sube hasta 5 imágenes que representen tu trabajo artístico. Formatos aceptados: JPG, PNG, GIF. Tamaño máximo: 2MB por imagen.</p>
                    
                    <div class="imagenes-grid">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <div class="form-grupo imagen-upload-grupo">
                                <label for="imagen_<?php echo $i; ?>" class="imagen-upload-label">
                                    <div class="imagen-upload-area" id="preview-area-<?php echo $i; ?>">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Imagen <?php echo $i; ?></span>
                                        <small>Clic para seleccionar</small>
                                    </div>
                                </label>
                                <input type="file" id="imagen_<?php echo $i; ?>" name="imagen_<?php echo $i; ?>" 
                                       accept="image/jpeg,image/png,image/gif" class="imagen-input"
                                       onchange="validarYPreviewImagen(this, <?php echo $i; ?>)">
                                <span class="imagen-error" id="imagen-error-<?php echo $i; ?>"></span>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- SECCIÓN: Información de Contacto -->
                <div class="form-seccion">
                    <h2 class="form-seccion-titulo">
                        <i class="fas fa-address-book"></i> Información de Contacto
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-grupo form-col">
                            <label for="telefono">Teléfono / WhatsApp</label>
                            <div class="input-icono">
                                <i class="fas fa-phone"></i>
                                <input type="tel" id="telefono" name="telefono" 
                                       pattern="09[0-9]{8}" maxlength="10"
                                       placeholder="Ej: 0999999999"
                                       title="Ingresa un número de celular válido (10 dígitos, empieza con 09)">
                            </div>
                            <span class="field-error" id="telefono-error"></span>
                        </div>
                        
                        <div class="form-grupo form-col">
                            <label for="email">Correo Electrónico <span class="campo-requerido">*</span></label>
                            <div class="input-icono">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" required
                                       maxlength="150"
                                       placeholder="Ej: artista@email.com"
                                       title="Ingresa un correo electrónico válido">
                            </div>
                            <span class="field-error" id="email-error"></span>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN: Redes Sociales -->
                <div class="form-seccion">
                    <h2 class="form-seccion-titulo">
                        <i class="fas fa-share-alt"></i> Redes Sociales
                    </h2>
                    <p class="form-ayuda">Comparte tus perfiles de redes sociales (opcional).</p>
                    
                    <div class="form-row">
                        <div class="form-grupo form-col">
                            <label for="facebook">Facebook</label>
                            <div class="input-icono">
                                <i class="fab fa-facebook-f"></i>
                                <input type="url" id="facebook" name="facebook" 
                                       maxlength="200"
                                       placeholder="https://facebook.com/tu-perfil">
                            </div>
                        </div>
                        
                        <div class="form-grupo form-col">
                            <label for="instagram">Instagram</label>
                            <div class="input-icono">
                                <i class="fab fa-instagram"></i>
                                <input type="url" id="instagram" name="instagram" 
                                       maxlength="200"
                                       placeholder="https://instagram.com/tu-perfil">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-grupo form-col">
                            <label for="twitter">Twitter / X</label>
                            <div class="input-icono">
                                <i class="fab fa-twitter"></i>
                                <input type="url" id="twitter" name="twitter" 
                                       maxlength="200"
                                       placeholder="https://x.com/tu-perfil">
                            </div>
                        </div>
                        
                        <div class="form-grupo form-col">
                            <label for="youtube">YouTube</label>
                            <div class="input-icono">
                                <i class="fab fa-youtube"></i>
                                <input type="url" id="youtube" name="youtube" 
                                       maxlength="200"
                                       placeholder="https://youtube.com/@tu-canal">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-grupo form-col">
                            <label for="tiktok">TikTok</label>
                            <div class="input-icono">
                                <i class="fab fa-tiktok"></i>
                                <input type="url" id="tiktok" name="tiktok" 
                                       maxlength="200"
                                       placeholder="https://tiktok.com/@tu-perfil">
                            </div>
                        </div>
                        
                        <div class="form-grupo form-col">
                            <label for="website">Sitio Web</label>
                            <div class="input-icono">
                                <i class="fas fa-globe"></i>
                                <input type="url" id="website" name="website" 
                                       maxlength="200"
                                       placeholder="https://tu-sitio-web.com">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- CAPTCHA de Seguridad -->
                <?php
                    $num_a = rand(1, 10);
                    $num_b = rand(1, 10);
                    $captcha_respuesta = $num_a + $num_b;
                    $captcha_hash = wp_hash( (string) $captcha_respuesta );
                ?>
                <div class="form-seccion form-seccion-captcha">
                    <h2 class="form-seccion-titulo">
                        <i class="fas fa-shield-alt"></i> Verificación de Seguridad
                    </h2>
                    
                    <div class="form-grupo captcha-grupo">
                        <label for="captcha_respuesta">
                            ¿Cuánto es <strong><?php echo $num_a; ?> + <?php echo $num_b; ?></strong>? <span class="campo-requerido">*</span>
                        </label>
                        <div class="captcha-container">
                            <div class="captcha-pregunta">
                                <span class="captcha-num"><?php echo $num_a; ?></span>
                                <span class="captcha-operador">+</span>
                                <span class="captcha-num"><?php echo $num_b; ?></span>
                                <span class="captcha-operador">=</span>
                            </div>
                            <input type="number" id="captcha_respuesta" name="captcha_respuesta" 
                                   required min="2" max="20" class="captcha-input"
                                   placeholder="?" autocomplete="off">
                            <input type="hidden" name="captcha_hash" value="<?php echo esc_attr($captcha_hash); ?>">
                        </div>
                        <span class="field-error" id="captcha-error"></span>
                        <p class="form-ayuda">Resuelve esta operación para verificar que eres una persona real.</p>
                    </div>
                </div>

                <!-- Política de privacidad -->
                <div class="form-grupo form-politica">
                    <label class="checkbox-item checkbox-politica">
                        <input type="checkbox" name="acepta_politica" required>
                        <span class="checkbox-label">Acepto que mis datos sean almacenados y utilizados para crear mi perfil público de artista en el sitio web de la Casa de la Cultura de Tungurahua. <span class="campo-requerido">*</span></span>
                    </label>
                </div>

                <!-- Botón de envío -->
                <div class="form-submit">
                    <button type="submit" class="btn-registro-submit" id="btn-submit">
                        <i class="fas fa-paper-plane"></i> Enviar Registro
                    </button>
                    <p class="form-nota">Tu perfil será revisado antes de ser publicado.</p>
                </div>
            </form>
        </div>
        
        <div class="registro-footer">
            <div class="registro-ayuda">
                <h3><i class="fas fa-question-circle"></i> ¿Necesitas ayuda?</h3>
                <p>Si tienes problemas con el registro, contáctanos:</p>
                <ul>
                    <li><i class="fas fa-envelope"></i> Email: info@casadelacultura.gob.ec</li>
                    <li><i class="fas fa-phone"></i> Teléfono: (03) 282-0461</li>
                </ul>
            </div>
            
            <div class="registro-politicas">
                <h3><i class="fas fa-shield-alt"></i> Política de Privacidad</h3>
                <p>
                    Al enviar este formulario, aceptas que tus datos sean almacenados y utilizados 
                    para crear tu perfil público de artista en nuestro sitio web. 
                    Tu información personal (teléfono y correo) solo será visible en tu perfil público.
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- JavaScript para preview de imágenes, validación y contadores -->
<script>
/* ====== Constantes ====== */
var MAX_FILE_SIZE = 2 * 1024 * 1024; /* 2MB en bytes */
var ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/gif'];

/* ====== Validar y previsualizar imagen ====== */
function validarYPreviewImagen(input, num) {
    var area = document.getElementById('preview-area-' + num);
    var errorSpan = document.getElementById('imagen-error-' + num);
    errorSpan.textContent = '';
    errorSpan.style.display = 'none';

    if (input.files && input.files[0]) {
        var file = input.files[0];

        /* Validar tipo */
        if (ALLOWED_TYPES.indexOf(file.type) === -1) {
            errorSpan.textContent = 'Formato no permitido. Usa JPG, PNG o GIF.';
            errorSpan.style.display = 'block';
            input.value = '';
            return;
        }

        /* Validar tamaño */
        if (file.size > MAX_FILE_SIZE) {
            var sizeMB = (file.size / (1024 * 1024)).toFixed(1);
            errorSpan.textContent = 'La imagen pesa ' + sizeMB + 'MB. Máximo permitido: 2MB.';
            errorSpan.style.display = 'block';
            input.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            area.innerHTML = '<img src="' + e.target.result + '" alt="Preview">' +
                             '<span class="imagen-cambiar">Cambiar imagen</span>';
            area.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    }
}

/* ====== Contadores de caracteres ====== */
function inicializarContadores() {
    var campos = document.querySelectorAll('input[maxlength], textarea[maxlength]');
    for (var i = 0; i < campos.length; i++) {
        (function(campo) {
            var counter = campo.parentNode.querySelector('.char-counter');
            if (!counter) return;
            var countSpan = counter.querySelector('.char-count');
            var maxLen = parseInt(campo.getAttribute('maxlength'), 10);

            function actualizarContador() {
                var len = campo.value.length;
                countSpan.textContent = len;
                if (len >= maxLen) {
                    counter.classList.add('char-limit');
                } else if (len >= maxLen * 0.9) {
                    counter.classList.remove('char-limit');
                    counter.classList.add('char-warning');
                } else {
                    counter.classList.remove('char-limit');
                    counter.classList.remove('char-warning');
                }
            }

            campo.addEventListener('input', actualizarContador);
            actualizarContador();
        })(campos[i]);
    }
}

/* ====== Validación de teléfono Ecuador ====== */
function validarTelefono(valor) {
    if (!valor || valor.trim() === '') return true; /* campo opcional */
    var regex = /^09[0-9]{8}$/;
    return regex.test(valor.trim());
}

/* ====== Validación de email ====== */
function validarEmail(valor) {
    if (!valor || valor.trim() === '') return false;
    var regex = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;
    return regex.test(valor.trim());
}

/* ====== Mostrar error en campo ====== */
function mostrarError(id, mensaje) {
    var el = document.getElementById(id);
    if (el) {
        el.textContent = mensaje;
        el.style.display = 'block';
    }
}

function limpiarError(id) {
    var el = document.getElementById(id);
    if (el) {
        el.textContent = '';
        el.style.display = 'none';
    }
}

/* ====== Validación en tiempo real: teléfono ====== */
function inicializarValidacionTelefono() {
    var telInput = document.getElementById('telefono');
    if (!telInput) return;

    /* Solo permitir números */
    telInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 0 && !validarTelefono(this.value)) {
            if (this.value.length === 10) {
                mostrarError('telefono-error', 'El número debe empezar con 09 (formato Ecuador).');
            } else if (this.value.length < 10) {
                limpiarError('telefono-error');
            }
        } else {
            limpiarError('telefono-error');
        }
    });

    telInput.addEventListener('blur', function() {
        if (this.value.length > 0 && !validarTelefono(this.value)) {
            mostrarError('telefono-error', 'Ingresa un número válido: 10 dígitos, empieza con 09.');
        }
    });
}

/* ====== Validación en tiempo real: email ====== */
function inicializarValidacionEmail() {
    var emailInput = document.getElementById('email');
    if (!emailInput) return;

    emailInput.addEventListener('blur', function() {
        if (this.value.length > 0 && !validarEmail(this.value)) {
            mostrarError('email-error', 'Ingresa un correo electrónico válido (ej: nombre@dominio.com).');
        } else {
            limpiarError('email-error');
        }
    });

    emailInput.addEventListener('input', function() {
        if (validarEmail(this.value)) {
            limpiarError('email-error');
        }
    });
}

/* ====== Inicialización ====== */
document.addEventListener('DOMContentLoaded', function() {
    inicializarContadores();
    inicializarValidacionTelefono();
    inicializarValidacionEmail();
});

/* ====== Validación al enviar formulario ====== */
document.getElementById('form-registro-artista').addEventListener('submit', function(e) {
    var errores = [];

    /* Validar disciplinas */
    var checkboxes = document.querySelectorAll('input[name="disciplina_artistica[]"]:checked');
    if (checkboxes.length === 0) {
        errores.push('Selecciona al menos una disciplina artística.');
    }

    /* Validar teléfono */
    var telVal = document.getElementById('telefono').value;
    if (telVal.length > 0 && !validarTelefono(telVal)) {
        errores.push('El teléfono debe tener 10 dígitos y empezar con 09.');
        mostrarError('telefono-error', 'Número de celular no válido.');
    }

    /* Validar email */
    var emailVal = document.getElementById('email').value;
    if (!validarEmail(emailVal)) {
        errores.push('Ingresa un correo electrónico válido.');
        mostrarError('email-error', 'Correo electrónico no válido.');
    }

    /* Validar CAPTCHA */
    var captchaVal = document.getElementById('captcha_respuesta').value;
    if (!captchaVal || captchaVal.trim() === '') {
        errores.push('Resuelve la operación de verificación.');
        mostrarError('captcha-error', 'Este campo es obligatorio.');
    }

    /* Validar imágenes seleccionadas */
    for (var i = 1; i <= 5; i++) {
        var imgInput = document.getElementById('imagen_' + i);
        if (imgInput && imgInput.files && imgInput.files[0]) {
            var file = imgInput.files[0];
            if (file.size > MAX_FILE_SIZE) {
                errores.push('La imagen ' + i + ' excede el tamaño máximo de 2MB.');
            }
            if (ALLOWED_TYPES.indexOf(file.type) === -1) {
                errores.push('La imagen ' + i + ' tiene un formato no permitido.');
            }
        }
    }

    /* Si hay errores, prevenir envío */
    if (errores.length > 0) {
        e.preventDefault();
        alert('Por favor corrige los siguientes errores:\n\n• ' + errores.join('\n• '));
        return false;
    }
    
    /* Deshabilitar botón para evitar doble envío */
    var btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
});
</script>

<?php get_footer(); ?>
