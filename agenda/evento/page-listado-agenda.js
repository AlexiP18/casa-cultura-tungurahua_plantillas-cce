/**
 * AGENDA CULTURAL — JavaScript
 * Filtros: año, meses (multi-select), gratuito, próximos/pasados, tipo
 */
(function () {
    'use strict';

    // --- DOM refs ---
    const filtroAnio = document.getElementById('filtroAnio');
    const filtroGratuito = document.getElementById('filtroGratuito');
    const btnProximos = document.getElementById('btnProximos');
    const btnPasados = document.getElementById('btnPasados');
    const footerBtnProximos = document.getElementById('footerBtnProximos');
    const footerBtnPasados = document.getElementById('footerBtnPasados');
    const cardsContainer = document.getElementById('agendaCards');
    const noResults = document.getElementById('agendaNoResults');
    const mesesSlider = document.getElementById('mesesSlider');
    const mesesPrev = document.getElementById('mesesPrev');
    const mesesNext = document.getElementById('mesesNext');
    const mesBtns = document.querySelectorAll('.mes-btn');
    const tipoItems = document.querySelectorAll('.tipo-filtro-item');

    // Download refs
    const downloadDropdown = document.querySelector('.agenda-download-dropdown');
    const downloadTrigger = document.getElementById('downloadTrigger');
    const downloadOptions = document.querySelectorAll('.download-option');

    // --- Estado ---
    let state = {
        anio: filtroAnio ? parseInt(filtroAnio.value) : new Date().getFullYear(),
        meses: [],            // Array de meses activos (1-12)
        gratuito: false,
        modo: 'proximos',     // 'proximos' | 'pasados'
        tipos: []             // Array vacío = todos; o ['teatro', 'musica', ...]
    };

    // Init: Meses activos (el mes con clase .active al cargar)
    mesBtns.forEach(function (btn) {
        if (btn.classList.contains('active')) {
            state.meses.push(parseInt(btn.dataset.mes));
        }
    });

    // =========================================
    // FILTRO AÑO
    // =========================================
    if (filtroAnio) {
        filtroAnio.addEventListener('change', function () {
            state.anio = parseInt(this.value);
            applyFilters();
        });
    }

    // =========================================
    // FILTRO MESES (multi-select)
    // =========================================
    mesBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var mes = parseInt(this.dataset.mes);

            if (this.classList.contains('active')) {
                // Deseleccionar
                this.classList.remove('active');
                state.meses = state.meses.filter(function (m) { return m !== mes; });
            } else {
                // Seleccionar
                this.classList.add('active');
                state.meses.push(mes);
            }

            applyFilters();
        });
    });

    // Month slider navigation
    var mesesScrollAmount = 180;

    if (mesesPrev) {
        mesesPrev.addEventListener('click', function () {
            mesesSlider.scrollBy({ left: -mesesScrollAmount, behavior: 'smooth' });
        });
    }
    if (mesesNext) {
        mesesNext.addEventListener('click', function () {
            mesesSlider.scrollBy({ left: mesesScrollAmount, behavior: 'smooth' });
        });
    }

    // =========================================
    // FILTRO GRATUITO
    // =========================================
    if (filtroGratuito) {
        filtroGratuito.addEventListener('change', function () {
            state.gratuito = this.checked;
            applyFilters();
        });
    }

    // =========================================
    // MODO PRÓXIMOS / PASADOS  (sync sidebar + footer)
    // =========================================
    function setModo(modo) {
        state.modo = modo;
        // Sidebar buttons
        if (modo === 'proximos') {
            if (btnProximos) btnProximos.classList.add('active');
            if (btnPasados) btnPasados.classList.remove('active');
        } else {
            if (btnPasados) btnPasados.classList.add('active');
            if (btnProximos) btnProximos.classList.remove('active');
        }
        // Footer buttons
        if (modo === 'proximos') {
            if (footerBtnProximos) footerBtnProximos.classList.add('active');
            if (footerBtnPasados) footerBtnPasados.classList.remove('active');
        } else {
            if (footerBtnPasados) footerBtnPasados.classList.add('active');
            if (footerBtnProximos) footerBtnProximos.classList.remove('active');
        }
        applyFilters();
    }

    if (btnProximos) {
        btnProximos.addEventListener('click', function () { setModo('proximos'); });
    }
    if (btnPasados) {
        btnPasados.addEventListener('click', function () { setModo('pasados'); });
    }
    if (footerBtnProximos) {
        footerBtnProximos.addEventListener('click', function () { setModo('proximos'); });
    }
    if (footerBtnPasados) {
        footerBtnPasados.addEventListener('click', function () { setModo('pasados'); });
    }

    // =========================================
    // FOOTER: FILTRO POR TIPO (multi-select)
    // =========================================
    tipoItems.forEach(function (item) {
        item.addEventListener('click', function () {
            var tipo = this.dataset.tipo;

            if (tipo === 'todos') {
                // "Todos": limpiar selección individual
                state.tipos = [];
                tipoItems.forEach(function (el) { el.classList.remove('active'); });
                this.classList.add('active');
            } else {
                // Deseleccionar "Todos"
                var todosBtn = document.querySelector('.tipo-filtro-item[data-tipo="todos"]');
                if (todosBtn) todosBtn.classList.remove('active');

                if (this.classList.contains('active')) {
                    this.classList.remove('active');
                    state.tipos = state.tipos.filter(function (t) { return t !== tipo; });
                } else {
                    this.classList.add('active');
                    state.tipos.push(tipo);
                }

                // Si no queda nada seleccionado, volver a "Todos"
                if (state.tipos.length === 0 && todosBtn) {
                    todosBtn.classList.add('active');
                }
            }

            applyFilters();
        });
    });

    // =========================================
    // BADGE INSCRIPCION LINK
    // =========================================
    document.addEventListener('click', function (e) {
        var badge = e.target.closest('.badge-link');
        if (badge && badge.dataset.href) {
            e.preventDefault();
            e.stopPropagation();
            window.open(badge.dataset.href, '_blank');
        }
    });

    // =========================================
    // APPLY FILTERS
    // =========================================
    function applyFilters() {
        var groups = cardsContainer.querySelectorAll('.agenda-mes-group');
        var allCards = cardsContainer.querySelectorAll('.agenda-card');
        var visibleTotal = 0;

        // Iterar cada grupo de mes
        groups.forEach(function (group) {
            var groupAnio = parseInt(group.dataset.anio);
            var groupMes = parseInt(group.dataset.mes);

            // Filtro por año
            if (groupAnio !== state.anio) {
                group.classList.add('hidden');
                return;
            }

            // Filtro por mes (si hay meses seleccionados)
            if (state.meses.length > 0 && state.meses.indexOf(groupMes) === -1) {
                group.classList.add('hidden');
                return;
            }

            // Mostrar grupo, luego filtrar cards individuales
            group.classList.remove('hidden');

            var cards = group.querySelectorAll('.agenda-card');
            var visibleInGroup = 0;

            cards.forEach(function (card) {
                var show = true;

                // Filtro gratuito
                if (state.gratuito && card.dataset.gratuito !== '1') {
                    show = false;
                }

                // Filtro próximos/pasados
                if (state.modo === 'proximos' && card.dataset.pasado === '1') {
                    show = false;
                }
                if (state.modo === 'pasados' && card.dataset.pasado === '0') {
                    show = false;
                }

                // Filtro por tipo
                if (state.tipos.length > 0 && state.tipos.indexOf(card.dataset.tipo) === -1) {
                    show = false;
                }

                if (show) {
                    card.classList.remove('hidden');
                    visibleInGroup++;
                    visibleTotal++;
                } else {
                    card.classList.add('hidden');
                }
            });

            // Actualizar badge de conteo del mes
            var badge = group.querySelector('.mes-count-badge');
            if (badge) badge.textContent = visibleInGroup;

            // Si no hay cards visibles en este grupo, ocultar header
            if (visibleInGroup === 0) {
                group.classList.add('hidden');
            }
        });

        // Mostrar / ocultar "sin resultados"
        if (noResults) {
            noResults.style.display = visibleTotal === 0 ? 'block' : 'none';
        }
    }

    // =========================================
    // STICKY FILTERS SHADOW
    // =========================================
    var filtrosSection = document.querySelector('.agenda-filtros-section');
    if (filtrosSection) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    filtrosSection.classList.add('is-stuck');
                } else {
                    filtrosSection.classList.remove('is-stuck');
                }
            });
        }, { threshold: [1], rootMargin: '-1px 0px 0px 0px' });

        // Crear sentinel element
        var sentinel = document.createElement('div');
        sentinel.style.height = '1px';
        sentinel.style.visibility = 'hidden';
        filtrosSection.parentNode.insertBefore(sentinel, filtrosSection);
        observer.observe(sentinel);
    }

    // =========================================
    // INITIAL FILTER RUN
    // =========================================
    applyFilters();

    // =========================================
    // DOWNLOAD FEATURE
    // =========================================
    if (downloadTrigger) {
        // Toggle Dropdown
        downloadTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            downloadDropdown.classList.toggle('active');
        });

        // Close on click outside
        document.addEventListener('click', function (e) {
            if (downloadDropdown && !downloadDropdown.contains(e.target)) {
                downloadDropdown.classList.remove('active');
            }
        });

        // Handle Options
        downloadOptions.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var type = this.dataset.type; // 'image' | 'pdf'
                // 1. Close dropdown immediately
                downloadDropdown.classList.remove('active');
                // 2. Defer generation so dropdown closes visually first
                setTimeout(function () {
                    downloadAgenda(type);
                }, 100);
            });
        });
    }

    function downloadAgenda(type) {
        const timestamp = new Date().toISOString().slice(0, 10);
        const filename = `agenda-cultural-${timestamp}`;

        // Ensure html2canvas / jsPDF is loaded
        if (typeof html2canvas === 'undefined' || typeof window.jspdf === 'undefined') {
            alert('Error: Las librerías de descarga no se han cargado correctamente.');
            return;
        }

        // 3. Change icon to spinner immediately
        const icon = downloadTrigger.querySelector('i');
        const originalIconClass = icon.className;
        icon.className = 'fas fa-spinner fa-spin';
        document.body.style.cursor = 'wait';

        function restoreUI() {
            icon.className = originalIconClass;
            document.body.style.cursor = 'default';
        }

        // ==========================================
        // FORCE LAZY LOAD BY INCREMENTAL SCROLLING
        // ==========================================
        // WordPress lazy-load plugins use IntersectionObserver to detect when images enter
        // the viewport. A single jump to the bottom skips middle images. Instead, we scroll
        // through the ENTIRE page in small increments so every image enters the viewport.
        var originalScrollY = window.scrollY;
        var totalHeight = document.body.scrollHeight;
        var stepSize = 300; // pixels per step
        var stepDelay = 80; // ms pause at each position
        var currentPos = 0;

        function scrollStep() {
            currentPos += stepSize;
            if (currentPos < totalHeight) {
                window.scrollTo(0, currentPos);
                setTimeout(scrollStep, stepDelay);
            } else {
                // Reached the bottom, scroll back to original position
                window.scrollTo(0, originalScrollY);
                // Wait a bit for the last images to finish downloading from the network
                setTimeout(function () {
                    proceedWithDownload();
                }, 800);
            }
        }
        // Start the incremental scroll
        scrollStep();

        function proceedWithDownload() {
            if (type === 'pdf') {
                generateNativePDF(filename, icon, originalIconClass);
            } else {
                // IMAGE DOWNLOAD (html2canvas)
                // Step 1: Pre-load ALL card images and convert to base64 data URLs
                var allCardImages = cardsContainer.querySelectorAll('.card-col-imagen img');
                var imagePromises = [];

                allCardImages.forEach(function (img) {
                    imagePromises.push(new Promise(function (resolve) {
                        // Check for WP lazy-loading attributes first
                        var originalSrc = img.getAttribute('data-src') ||
                            img.getAttribute('data-lazy-src') ||
                            img.getAttribute('data-opt-src') ||
                            img.src;

                        // If the src is a tiny 1x1 base64 placeholder (typical of WP lazy load), ignore it
                        if (originalSrc && originalSrc.indexOf('data:image') === 0 && originalSrc.length < 500) {
                            originalSrc = '';
                        }

                        if (!originalSrc) { resolve({ img: img, dataUrl: '', originalSrc: img.src }); return; }

                        var tempImg = new Image();
                        tempImg.crossOrigin = 'Anonymous';
                        tempImg.onload = function () {
                            try {
                                var cvs = document.createElement('canvas');
                                cvs.width = tempImg.naturalWidth;
                                cvs.height = tempImg.naturalHeight;
                                var ctx = cvs.getContext('2d');
                                ctx.drawImage(tempImg, 0, 0);
                                var dataUrl = cvs.toDataURL('image/jpeg', 0.9);
                                resolve({ img: img, dataUrl: dataUrl, originalSrc: originalSrc });
                            } catch (e) {
                                console.warn('[Agenda] CORS blocked for image, using original src:', originalSrc);
                                resolve({ img: img, dataUrl: '', originalSrc: originalSrc });
                            }
                        };
                        tempImg.onerror = function () {
                            console.warn('[Agenda] Failed to load image:', originalSrc);
                            resolve({ img: img, dataUrl: '', originalSrc: originalSrc });
                        };
                        tempImg.src = originalSrc;
                    }));
                });

                Promise.all(imagePromises).then(function (results) {
                    // Step 2: Swap live DOM image src to base64 data URLs
                    var swapped = [];
                    results.forEach(function (r) {
                        if (r.dataUrl) {
                            r.img.src = r.dataUrl;
                            r.img.removeAttribute('loading');
                            r.img.style.objectFit = ''; // html2canvas can't handle object-fit
                            swapped.push(r);
                        }
                    });
                    console.log('[Agenda] Swapped', swapped.length, 'images to base64');

                    // Step 3: Measure containers for the clone
                    var measurements = [];
                    var cards = cardsContainer.querySelectorAll('.agenda-card');
                    cards.forEach(function (card) {
                        var imgCol = card.querySelector('.card-col-imagen');
                        var rowBottom = card.querySelector('.card-row-bottom');
                        measurements.push({
                            rowBottomH: rowBottom ? rowBottom.offsetHeight : 0,
                            imgColH: imgCol ? imgCol.offsetHeight : 0,
                            imgColW: imgCol ? imgCol.offsetWidth : 0
                        });
                    });

                    var targetH = cardsContainer.scrollHeight || cardsContainer.offsetHeight;

                    // Step 4: Run html2canvas
                    var options = {
                        scale: 2,
                        useCORS: true,
                        allowTaint: true,
                        backgroundColor: '#0f0f23',
                        windowWidth: 1200,
                        height: targetH,
                        windowHeight: targetH + 200,
                        ignoreElements: function (element) {
                            if (!element || !element.classList) return false;
                            return element.classList.contains('agenda-download-group') ||
                                element.classList.contains('descargar-flotante');
                        },
                        onclone: function (clonedDoc) {
                            var clonedContainer = clonedDoc.getElementById('agendaCards');
                            if (clonedContainer) {
                                clonedContainer.classList.add('is-capturing');
                                clonedContainer.style.width = '1200px';
                                clonedContainer.style.maxWidth = '1200px';
                                clonedContainer.style.minWidth = '1200px';
                                clonedContainer.style.minHeight = targetH + 'px';

                                // Apply explicit pixel dimensions to image containers
                                var clonedCards = clonedContainer.querySelectorAll('.agenda-card');
                                clonedCards.forEach(function (cc, i) {
                                    var m = measurements[i];
                                    if (!m) return;
                                    var rb = cc.querySelector('.card-row-bottom');
                                    if (rb && m.rowBottomH > 0) rb.style.minHeight = m.rowBottomH + 'px';
                                    var ic = cc.querySelector('.card-col-imagen');
                                    if (ic && m.imgColH > 0) {
                                        ic.style.width = m.imgColW + 'px';
                                        ic.style.minWidth = m.imgColW + 'px';
                                        ic.style.height = m.imgColH + 'px';
                                        ic.style.minHeight = m.imgColH + 'px';
                                        ic.style.overflow = 'hidden';
                                        ic.style.position = 'relative';
                                        var img = ic.querySelector('img');
                                        if (img) {
                                            img.style.position = 'absolute';
                                            img.style.top = '0';
                                            img.style.left = '0';
                                            img.style.width = '100%';
                                            img.style.height = '100%';
                                            img.style.display = 'block';
                                        }
                                    }
                                });
                            }
                            // Remove SiteSEO iframes that crash the clone
                            var iframes = clonedDoc.querySelectorAll('iframe');
                            iframes.forEach(function (iframe) { iframe.removeAttribute('onload'); });
                        }
                    };

                    html2canvas(cardsContainer, options).then(function (canvas) {
                        console.log('[Agenda] Canvas:', canvas.width, 'x', canvas.height);
                        canvas.toBlob(function (blob) {
                            if (blob) {
                                var url = URL.createObjectURL(blob);
                                var link = document.createElement('a');
                                link.download = filename + '.jpg';
                                link.href = url;
                                link.style.display = 'none';
                                document.body.appendChild(link);
                                link.click();
                                setTimeout(function () {
                                    document.body.removeChild(link);
                                    URL.revokeObjectURL(url);
                                }, 1500);
                            }
                            // Step 5: Restore original image src
                            swapped.forEach(function (r) { r.img.src = r.originalSrc; });
                            restoreUI();
                        }, 'image/jpeg', 0.85);
                    }).catch(function (err) {
                        console.error('[Agenda] html2canvas error:', err);
                        swapped.forEach(function (r) { r.img.src = r.originalSrc; });
                        alert('Hubo un error al generar la imagen.');
                        restoreUI();
                    });
                });
            }
        } // <-- Closes proceedWithDownload()

        async function generateNativePDF(filename, icon, originalIconClass) {
            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('p', 'mm', 'a4');
                const pageWidth = 210;
                const pageHeight = 297;
                const margin = 12;
                const boxesWidth = pageWidth - (margin * 2);

                let y = margin;

                // --- COLORS ---
                const colorBg = [15, 15, 35];      // #0f0f23
                const colorCard = [30, 30, 54];    // #1e1e36
                const colorPink = [231, 30, 138];  // #e71e8a
                const colorWhite = [255, 255, 255];
                const colorGrey = [142, 142, 160]; // #8e8ea0
                const colorYellow = [255, 193, 7]; // #ffc107 
                const colorBlue = [0, 123, 255];   // #007bff (Info)

                // Helper: Add Background
                const addPageBackground = () => {
                    doc.setFillColor(...colorBg);
                    doc.rect(0, 0, pageWidth, pageHeight, 'F');
                };

                // Helper: Check Page Break
                const checkPageBreak = (heightNeeded) => {
                    if (y + heightNeeded > pageHeight - margin) {
                        doc.addPage();
                        addPageBackground();
                        y = margin;
                        return true;
                    }
                    return false;
                };

                // Helper: Load Image
                const loadImage = (src) => {
                    return new Promise((resolve) => {
                        const img = new Image();
                        img.crossOrigin = "Anonymous";
                        img.onload = () => resolve(img);
                        img.onerror = () => resolve(null);
                        img.src = src;
                    });
                };

                // Initial Background
                addPageBackground();

                // Header Title
                doc.setFont("helvetica", "bold");
                doc.setFontSize(22);
                doc.setTextColor(...colorWhite);
                doc.text("Agenda Cultural CCE", pageWidth / 2, y + 8, { align: 'center' });
                y += 18;

                // Iterate Visible Content
                const groups = cardsContainer.querySelectorAll('.agenda-mes-group:not(.hidden)');

                for (const group of groups) {
                    const mesTitle = group.querySelector('.mes-titulo').innerText;

                    // Month Header
                    checkPageBreak(25);
                    doc.setFont("helvetica", "bold");
                    doc.setFontSize(14);
                    doc.setTextColor(...colorPink);
                    doc.text(mesTitle.toUpperCase(), margin, y + 6);
                    y += 10;

                    const cards = group.querySelectorAll('.agenda-card:not(.hidden)');

                    for (const card of cards) {
                        const cardHeight = 30; // Further reduced height (was 36)
                        checkPageBreak(cardHeight + 2); // Reduced gap to +2

                        // --- 1. Card Background ---
                        doc.setFillColor(...colorCard);
                        doc.roundedRect(margin, y, boxesWidth, cardHeight, 2, 2, 'F');

                        // --- 2. Category Stripe (Left) ---
                        const typeLabel = card.querySelector('.tipo-label').innerText;
                        const colTipoWidth = 18;

                        doc.setFillColor(20, 20, 40);
                        doc.rect(margin, y, colTipoWidth, cardHeight, 'F');

                        // Vertical Text (Centered)
                        doc.setFontSize(7);
                        doc.setTextColor(...colorPink);
                        doc.setFont("helvetica", "bold");

                        const textX = margin + (colTipoWidth / 2);
                        const charSpacing = 2.5;
                        const textHeight = typeLabel.length * charSpacing;

                        // Vertical centering: Start Y = Top of Card + (Card Height - Text Height) / 2
                        // Adjust slightly for baseline
                        const textYStart = y + (cardHeight - textHeight) / 2 + 1; // +1 visual correction

                        const splitText = typeLabel.split('');
                        splitText.forEach((char, i) => {
                            doc.text(char, textX, textYStart + (i * charSpacing), { align: 'center', baseline: 'middle' });
                        });


                        // --- 3. Date Column ---
                        const colDateX = margin + colTipoWidth;
                        const colDateWidth = 32;

                        // Day Number
                        const dayNumber = card.querySelector('.dia-unico, .dia-rango').innerText.trim();
                        doc.setFontSize(16);
                        doc.setTextColor(...colorWhite);
                        doc.text(dayNumber, colDateX + (colDateWidth / 2), y + 12, { align: 'center' }); // slightly up

                        // Day Name
                        const dayName = card.querySelector('.dia-semana').innerText.trim().toUpperCase();
                        doc.setFontSize(6);
                        doc.setTextColor(...colorGrey);
                        doc.text(dayName, colDateX + (colDateWidth / 2), y + 19, { align: 'center' });

                        // Time
                        const timeText = card.querySelector('.fecha-hora').innerText.trim();
                        doc.setFontSize(8);
                        doc.setTextColor(...colorPink);
                        doc.text(timeText, colDateX + (colDateWidth / 2), y + 26, { align: 'center' });

                        // Divider
                        doc.setDrawColor(255, 255, 255);
                        doc.setLineWidth(0.1);
                        doc.line(colDateX + colDateWidth, y + 6, colDateX + colDateWidth, y + cardHeight - 6);


                        // --- 4. Content Column ---
                        const colContentX = colDateX + colDateWidth + 4;
                        const colImageWidth = 40; // Reduced logic width
                        const colContentWidth = boxesWidth - colTipoWidth - colDateWidth - colImageWidth - 4;

                        // Title
                        const title = card.querySelector('.card-titulo').innerText.trim();
                        doc.setFontSize(11);
                        doc.setTextColor(...colorWhite);
                        doc.setFont("helvetica", "bold");

                        const splitTitle = doc.splitTextToSize(title, colContentWidth);
                        doc.text(splitTitle, colContentX, y + 8);

                        let currentY = y + 10 + (splitTitle.length * 4);

                        doc.setFontSize(7);
                        // doc.setFont("helvetica", "normal"); // Keep bold for specific items if requested?
                        // User wants Address, Place, Price in BOLD.

                        // Meta Rows: Address / Place / Price
                        const metaRows = card.querySelectorAll('.card-meta-row');
                        metaRows.forEach(row => {
                            let txt = row.querySelector('span') ? row.querySelector('span').innerText.trim() : '';
                            if (txt) {
                                let prefix = "";
                                let isBoldValue = false; // Default: Value is normal

                                // Detect type based on icon class
                                if (row.innerHTML.includes('fa-map-marker')) {
                                    prefix = "Dirección: ";
                                } else if (row.innerHTML.includes('fa-home')) {
                                    prefix = "Lugar: ";
                                } else if (row.innerHTML.includes('fa-dollar-sign') || txt.includes('$') || txt.toLowerCase() === 'gratuito') {
                                    // Price: No prefix usually, but whole text bold
                                    prefix = "";
                                    isBoldValue = true;
                                    // Optionally add "Valor:"? User didn't ask, but "el precio" implies the value.
                                } else {
                                    // Unknown meta, just print normal?
                                }

                                // Print Key (Bold)
                                let currentX = colContentX;
                                if (prefix) {
                                    doc.setFont("helvetica", "bold");
                                    doc.setTextColor(...colorGrey);
                                    doc.text(prefix, currentX, currentY);
                                    currentX += doc.getTextWidth(prefix);
                                }

                                // Print Value
                                doc.setFont("helvetica", isBoldValue ? "bold" : "normal");
                                // If price, use Pink?
                                if (isBoldValue) {
                                    doc.setTextColor(...colorPink);
                                } else {
                                    doc.setTextColor(...colorGrey);
                                }

                                // Split text if too long?
                                // Meta info usually short. If address is long, splitTextToSize might be needed but complex with X offset.
                                // Assuming single line for meta for now or simple wrap.
                                doc.text(txt, currentX, currentY);

                                currentY += 3.5;
                            }
                        });


                        // --- Badges ---
                        // currentY += 1; // spacer removed to move up
                        currentY -= 0.5; // Move up slightly as requested
                        let badgeX = colContentX;

                        // 1. Edad
                        const badgeEdad = card.querySelector('.badge-edad');
                        if (badgeEdad) {
                            const txt = badgeEdad.innerText.trim();
                            const w = doc.getTextWidth(txt) + 3;
                            doc.setDrawColor(255, 255, 255); // Border
                            doc.setLineWidth(0.1);
                            doc.roundedRect(badgeX, currentY, w, 4, 1, 1, 'S');

                            doc.setTextColor(...colorWhite);
                            doc.text(txt, badgeX + 1.5, currentY + 2.8);
                            badgeX += w + 2;
                        }

                        // 2. Estado (color background)
                        const badgeEstado = card.querySelector('.badge-estado');
                        if (badgeEstado) {
                            const txt = badgeEstado.innerText.trim();
                            const w = doc.getTextWidth(txt) + 3;
                            const bgColor = badgeEstado.style.backgroundColor || '#e71e8a'; // extract color?

                            // Convert generic color names/hex to RGB? jsPDF needs RGB. 
                            // Simple fallback: Pink if unknown.
                            // Actually, if it's inline style, retrieving exact RGB is hard from raw HTML parse unless we use computed style, 
                            // but here we are parsing DOM nodes. `badgeEstado.style.backgroundColor` gives string.
                            // jsPDF doesn't parse CSS color strings easily.
                            // Let's just use Pink for State.

                            doc.setFillColor(...colorPink);
                            doc.roundedRect(badgeX, currentY, w, 4, 1, 1, 'F');
                            doc.setTextColor(255, 255, 255);
                            doc.text(txt, badgeX + 1.5, currentY + 2.8);
                            badgeX += w + 2;
                        }

                        // 3. Inscripcion
                        const badgeInsc = card.querySelector('.badge-inscripcion');
                        if (badgeInsc) {
                            const txt = "Inscripciones"; // Shorten
                            const w = doc.getTextWidth(txt) + 3;
                            doc.setFillColor(...colorBlue);
                            doc.roundedRect(badgeX, currentY, w, 4, 1, 1, 'F');
                            doc.setTextColor(255, 255, 255);
                            doc.text(txt, badgeX + 1.5, currentY + 2.8);
                        }


                        // --- 5. Image (Right) ---
                        const imgEl = card.querySelector('.card-col-imagen img');
                        if (imgEl && imgEl.src) {
                            try {
                                const img = await loadImage(imgEl.src);
                                if (img) {
                                    const imgX = margin + boxesWidth - colImageWidth;
                                    doc.addImage(img, 'JPEG', imgX, y, colImageWidth, cardHeight, undefined, 'FAST');
                                }
                            } catch (e) {
                                // Ignore
                            }
                        }

                        y += cardHeight + 2; // Reduced Gap
                    }

                    y += 2; // Extra gap after month
                }

                doc.save(`${filename}.pdf`);

            } catch (err) {
                console.error('Error generating PDF:', err);
                alert('Hubo un error al generar el PDF.');
            } finally {
                // Always restore UI
                icon.className = originalIconClass;
                document.body.style.cursor = 'default';
            }
        }
    } // <-- Closes downloadAgenda

})();
