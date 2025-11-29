/**
 * JavaScript para el slider de talleres de la Casa de la Cultura de Tungurahua
 */
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el slider
    const sliderWrapper = document.querySelector('.slider-wrapper');
    const slides = document.querySelectorAll('.slide');
    const prevBtn = document.querySelector('.slider-nav.prev');
    const nextBtn = document.querySelector('.slider-nav.next');
    
    // Si no hay slides, no continuar
    if (!sliderWrapper || !slides.length) {
        return;
    }
    
    // Variables para el slider
    let currentSlide = 0;
    const slideCount = slides.length;
    
    // Ocultar los botones de navegación si solo hay una imagen
    if (slideCount <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
    }
    
    // Función para mostrar una diapositiva específica
    function goToSlide(index) {
        if (index < 0) {
            currentSlide = slideCount - 1;
        } else if (index >= slideCount) {
            currentSlide = 0;
        } else {
            currentSlide = index;
        }
        
        sliderWrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
    }
    
    // Event listeners para los botones
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            goToSlide(currentSlide - 1);
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            goToSlide(currentSlide + 1);
        });
    }
    
    // Inicializar el slider
    goToSlide(0);
    
    // Auto play del slider
    let interval = setInterval(() => {
        goToSlide(currentSlide + 1);
    }, 5000);
    
    // Detener el auto play cuando el usuario interactúa con el slider
    sliderWrapper.addEventListener('mouseenter', () => {
        clearInterval(interval);
    });
    
    sliderWrapper.addEventListener('mouseleave', () => {
        interval = setInterval(() => {
            goToSlide(currentSlide + 1);
        }, 5000);
    });
    
    // Soporte para gestos táctiles
    let touchStartX = 0;
    let touchEndX = 0;
    
    sliderWrapper.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, {passive: true});
    
    sliderWrapper.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, {passive: true});
    
    function handleSwipe() {
        const swipeThreshold = 50; // mínimo desplazamiento para considerar un swipe
        
        if (touchEndX < touchStartX - swipeThreshold) {
            // Swipe hacia la izquierda - siguiente slide
            goToSlide(currentSlide + 1);
        }
        
        if (touchEndX > touchStartX + swipeThreshold) {
            // Swipe hacia la derecha - slide anterior
            goToSlide(currentSlide - 1);
        }
    }
});

// Toggle para la sección de detalles (opcional)
document.addEventListener('DOMContentLoaded', function() {
    const detallesBtn = document.querySelector('.btn-detalles');
    const tallerInfo = document.querySelector('.taller-info');
    
    if (detallesBtn && tallerInfo) {
        detallesBtn.addEventListener('click', function() {
            tallerInfo.classList.toggle('expanded');
        });
    }
});