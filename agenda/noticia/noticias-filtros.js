/**
 * JavaScript para filtros de noticias
 * Casa de la Cultura
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Filtros de categorías
    const filtros = document.querySelectorAll('.filtro-btn');
    const noticias = document.querySelectorAll('.noticia-card');
    
    if (filtros.length > 0 && noticias.length > 0) {
        filtros.forEach(filtro => {
            filtro.addEventListener('click', function() {
                const categoria = this.getAttribute('data-categoria');
                
                // Remover clase active de todos
                filtros.forEach(f => f.classList.remove('active'));
                // Agregar clase active al clickeado
                this.classList.add('active');
                
                // Filtrar noticias
                noticias.forEach(noticia => {
                    const noticiaCategoria = noticia.getAttribute('data-categoria');
                    
                    if (categoria === 'todas' || noticiaCategoria === categoria) {
                        noticia.style.display = 'block';
                        noticia.style.animation = 'fadeIn 0.5s ease';
                    } else {
                        noticia.style.display = 'none';
                    }
                });
            });
        });
    }
    
    // Animación de fade in
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
    
});