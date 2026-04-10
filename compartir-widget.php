<?php
/**
 * Widget de Compartir - Componente Reutilizable
 * Incluir en todos los single templates
 * 
 * Uso: <?php include(get_stylesheet_directory() . '/compartir-widget.php'); ?>
 */

$share_url = urlencode(get_permalink());
$share_title = urlencode(get_the_title());
$share_text = urlencode(get_the_title() . ' - ' . get_permalink());
$share_image = '';
if (has_post_thumbnail()) {
    $share_image = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'large'));
}
?>

<!-- Widget de Compartir Flotante -->
<div class="cce-compartir-widget" id="cce-compartir-widget">
    
    <!-- Botón principal flotante -->
    <button class="cce-compartir-toggle" id="cce-compartir-toggle" aria-label="Compartir" title="Compartir esta página">
        <svg class="cce-icon-share" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
            <path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
        </svg>
        <svg class="cce-icon-close" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
        </svg>
    </button>
    
    <!-- Panel de opciones -->
    <div class="cce-compartir-panel" id="cce-compartir-panel">
        
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" 
           target="_blank" rel="noopener noreferrer"
           class="cce-compartir-btn cce-facebook" 
           title="Compartir en Facebook">
            <span class="cce-btn-icon cce-icon-fb">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                </svg>
            </span>
            <span class="cce-btn-label">Facebook</span>
        </a>
        
        <a href="https://x.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" 
           target="_blank" rel="noopener noreferrer"
           class="cce-compartir-btn cce-xtwitter" 
           title="Compartir en X">
            <span class="cce-btn-icon cce-icon-x">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </span>
            <span class="cce-btn-label">X</span>
        </a>
        
        <a href="https://wa.me/?text=<?php echo $share_text; ?>" 
           target="_blank" rel="noopener noreferrer"
           class="cce-compartir-btn cce-whatsapp" 
           title="Compartir en WhatsApp">
            <span class="cce-btn-icon cce-icon-wa">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                </svg>
            </span>
            <span class="cce-btn-label">WhatsApp</span>
        </a>
        
        <a href="https://t.me/share/url?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" 
           target="_blank" rel="noopener noreferrer"
           class="cce-compartir-btn cce-telegram" 
           title="Compartir en Telegram">
            <span class="cce-btn-icon cce-icon-tg">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.229.05-.01.119-.025.166.016.047.04.042.117.037.138-.03.11-1.14 1.04-1.71 1.548-.18.16-.308.274-.334.3a8.58 8.58 0 0 1-.2.19c-.421.396-.737.664.018 1.13.36.222.648.408.937.594.316.204.632.407 1.032.658.102.064.197.133.291.197.353.245.666.461 1.05.404.222-.033.451-.244.568-.907.276-1.558.827-4.935.953-6.34.012-.134.002-.306-.028-.442a.398.398 0 0 0-.137-.283c-.108-.09-.275-.112-.35-.105-.33.03-1.836.93-5.148 2.727z"/>
                </svg>
            </span>
            <span class="cce-btn-label">Telegram</span>
        </a>
        
        <a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?><?php if ($share_image) echo '&media=' . $share_image; ?>&description=<?php echo $share_title; ?>" 
           target="_blank" rel="noopener noreferrer"
           class="cce-compartir-btn cce-pinterest" 
           title="Compartir en Pinterest">
            <span class="cce-btn-icon cce-icon-pin">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 0a8 8 0 0 0-2.915 15.452c-.07-.633-.134-1.606.027-2.297.146-.625.938-3.977.938-3.977s-.239-.479-.239-1.187c0-1.113.645-1.943 1.448-1.943.682 0 1.012.512 1.012 1.127 0 .686-.437 1.712-.663 2.663-.188.796.4 1.446 1.185 1.446 1.422 0 2.515-1.5 2.515-3.664 0-1.915-1.377-3.254-3.342-3.254-2.276 0-3.612 1.707-3.612 3.471 0 .688.265 1.425.595 1.826a.24.24 0 0 1 .056.23c-.061.252-.196.796-.222.907-.035.146-.116.177-.268.107-1-.465-1.624-1.926-1.624-3.1 0-2.523 1.834-4.84 5.286-4.84 2.775 0 4.932 1.977 4.932 4.62 0 2.757-1.739 4.976-4.151 4.976-.811 0-1.573-.421-1.834-.919l-.498 1.902c-.181.695-.669 1.566-.995 2.097A8 8 0 1 0 8 0z"/>
                </svg>
            </span>
            <span class="cce-btn-label">Pinterest</span>
        </a>
        
        <button class="cce-compartir-btn cce-copiar" id="cce-copiar-enlace" title="Copiar enlace">
            <span class="cce-btn-icon cce-icon-copy">
                <svg class="cce-icon-link" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                    <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z"/>
                </svg>
                <svg class="cce-icon-check" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/>
                </svg>
            </span>
            <span class="cce-btn-label">Copiar enlace</span>
        </button>
        
    </div>
    
    <!-- Overlay para cerrar en móvil -->
    <div class="cce-compartir-overlay" id="cce-compartir-overlay"></div>
</div>
