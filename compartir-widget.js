/**
 * Widget de Compartir Flotante - CCE
 * JavaScript unificado para todas las single templates
 */
document.addEventListener('DOMContentLoaded', function() {
    var widget = document.getElementById('cce-compartir-widget');
    var toggle = document.getElementById('cce-compartir-toggle');
    var overlay = document.getElementById('cce-compartir-overlay');
    var copiarBtn = document.getElementById('cce-copiar-enlace');
    
    if (!widget || !toggle) return;
    
    // Toggle panel
    toggle.addEventListener('click', function(e) {
        e.stopPropagation();
        widget.classList.toggle('active');
    });
    
    // Cerrar con overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            widget.classList.remove('active');
        });
    }
    
    // Cerrar al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!widget.contains(e.target)) {
            widget.classList.remove('active');
        }
    });
    
    // Cerrar con Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            widget.classList.remove('active');
        }
    });
    
    // Copiar enlace
    if (copiarBtn) {
        copiarBtn.addEventListener('click', function() {
            var url = window.location.href;
            var btn = this;
            var label = btn.querySelector('.cce-btn-label');
            
            // Intentar copiar
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function() {
                    mostrarCopiado(btn, label);
                }).catch(function() {
                    copiarFallback(url, btn, label);
                });
            } else {
                copiarFallback(url, btn, label);
            }
        });
    }
    
    function copiarFallback(texto, btn, label) {
        var input = document.createElement('input');
        input.style.position = 'fixed';
        input.style.opacity = '0';
        input.value = texto;
        document.body.appendChild(input);
        input.select();
        try {
            document.execCommand('copy');
            mostrarCopiado(btn, label);
        } catch (e) {
            // Silenciar error
        }
        document.body.removeChild(input);
    }
    
    function mostrarCopiado(btn, label) {
        // La clase 'copiado' maneja el cambio de icono via CSS
        btn.classList.add('copiado');
        if (label) label.textContent = '¡Copiado!';
        
        setTimeout(function() {
            btn.classList.remove('copiado');
            if (label) label.textContent = 'Copiar enlace';
        }, 2000);
    }
    
    // Cerrar los enlaces de compartir (para cerrar el panel tras clic en mobile)
    var shareLinks = widget.querySelectorAll('a.cce-compartir-btn');
    shareLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            setTimeout(function() {
                widget.classList.remove('active');
            }, 300);
        });
    });
});
