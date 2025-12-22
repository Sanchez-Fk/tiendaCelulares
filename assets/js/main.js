// Carousel automático
let currentSlide = 0;
let carouselInterval;

function initCarousel() {
    const items = document.querySelectorAll('.carousel-item');
    const indicators = document.querySelectorAll('.indicator');
    
    if(items.length === 0) return;

    function showSlide(index) {
        items.forEach((item, i) => {
            item.classList.remove('active');
            if(i === index) {
                item.classList.add('active');
            }
        });
        
        indicators.forEach((indicator, i) => {
            indicator.classList.remove('active');
            if(i === index) {
                indicator.classList.add('active');
            }
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % items.length;
        showSlide(currentSlide);
    }

    function startCarousel() {
        carouselInterval = setInterval(nextSlide, 5000); // Cambia cada 5 segundos
    }

    function stopCarousel() {
        clearInterval(carouselInterval);
    }

    // Iniciar carousel automático
    startCarousel();

    // Pausar al hacer hover
    const carouselContainer = document.querySelector('.carousel-container');
    if(carouselContainer) {
        carouselContainer.addEventListener('mouseenter', stopCarousel);
        carouselContainer.addEventListener('mouseleave', startCarousel);
    }

    // Click en items del carousel para redirigir
    items.forEach((item, index) => {
        item.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            if(productId) {
                window.location.href = `/proyectoFinalManolo/views/producto.php?id=${productId}`;
            }
        });
    });
}

// Funciones para controles manuales
function moveCarousel(direction) {
    const items = document.querySelectorAll('.carousel-item');
    if(items.length === 0) return;
    
    currentSlide += direction;
    if(currentSlide < 0) {
        currentSlide = items.length - 1;
    } else if(currentSlide >= items.length) {
        currentSlide = 0;
    }
    
    const indicators = document.querySelectorAll('.indicator');
    items.forEach((item, i) => {
        item.classList.remove('active');
        if(i === currentSlide) {
            item.classList.add('active');
        }
    });
    
    indicators.forEach((indicator, i) => {
        indicator.classList.remove('active');
        if(i === currentSlide) {
            indicator.classList.add('active');
        }
    });
}

function goToSlide(index) {
    currentSlide = index;
    const items = document.querySelectorAll('.carousel-item');
    const indicators = document.querySelectorAll('.indicator');
    
    items.forEach((item, i) => {
        item.classList.remove('active');
        if(i === index) {
            item.classList.add('active');
        }
    });
    
    indicators.forEach((indicator, i) => {
        indicator.classList.remove('active');
        if(i === index) {
            indicator.classList.add('active');
        }
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initCarousel();
    
    // Mostrar mensajes de éxito/error
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('success') === '1') {
        showNotification('Operación realizada exitosamente', 'success');
    }
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Formatear número de tarjeta
function formatCardNumber(value) {
    const v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    const matches = v.match(/\d{4,16}/g);
    const match = (matches && matches[0]) || '';
    const parts = [];

    for (let i = 0, len = match.length; i < len; i += 4) {
        parts.push(match.substring(i, i + 4));
    }

    if (parts.length) {
        return parts.join(' ');
    } else {
        return value;
    }
}

// Formatear fecha de expiración
function formatExpirationDate(value) {
    const v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    if (v.length >= 2) {
        return v.slice(0, 2) + '/' + v.slice(2, 4);
    }
    return v;
}

// Inicializar event listeners para formateo de tarjeta y fecha
document.addEventListener('DOMContentLoaded', function() {
    const cardInput = document.getElementById('numero_tarjeta');
    const expiryInput = document.getElementById('fecha_expiracion');
    const cvvInput = document.getElementById('cvv');

    if (cardInput) {
        cardInput.addEventListener('input', function(e) {
            this.value = formatCardNumber(this.value);
        });
    }

    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            this.value = formatExpirationDate(this.value);
        });
    }

    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
});



