/* ================================== */
/* CRUSERTEL - OPTIMIZED JAVASCRIPT  */
/* ================================== */

/**
 * Main application object to avoid global namespace pollution
 */
const CrusertelApp = {
    
    // Configuration
    config: {
        animationDelay: 150,
        pulseInterval: 800,
        scrollThreshold: 20,
        debounceDelay: 250
    },

    // State management
    state: {
        isInitialized: false,
        currentPage: null,
        activeIntervals: new Map()
    },

    // ===================================
    // INITIALIZATION
    // ===================================
    
    init() {
        if (this.state.isInitialized) return;
        
        this.state.currentPage = this.getCurrentPage();
        console.log(`${this.state.currentPage} page loaded`);
        
        // Initialize all modules
        this.animations.init();
        this.header.init();
        this.interactions.init();
        this.forms.init();
        this.navigation.init();
        this.ui.init();
        
        this.state.isInitialized = true;
    },

    getCurrentPage() {
        const path = window.location.pathname;
        if (path.includes('contacto')) return 'contact';
        if (path.includes('faq')) return 'faq';
        if (path.includes('servicios')) return 'services';
        if (path.includes('tarifas')) return 'tarifs';
        if (path.includes('unete')) return 'joinUs';
        return 'home';
    },

    // ===================================
    // ANIMATIONS MODULE
    // ===================================
    
    animations: {
        animateElement(element, delay) {
            if (!element) return;
            setTimeout(() => {
                element.classList.add('animated-element');
            }, delay);
        },

        init() {
            const allAnimated = document.querySelectorAll(
                '.fade-in-up-initial, .fade-in-down-initial, .slide-in-from-left-initial, .pop-in-initial'
            );

            allAnimated.forEach((el, index) => {
                this.animateElement(el, 300 + index * CrusertelApp.config.animationDelay);
            });
        }
    },

    // ===================================
    // HEADER MODULE
    // ===================================
    
    header: {
        init() {
            const header = document.querySelector('header');
            if (!header) return;

            const scrollHandler = CrusertelApp.utils.debounce(() => {
                if (window.scrollY > CrusertelApp.config.scrollThreshold) {
                    header.classList.add('header-scrolled');
                } else {
                    header.classList.remove('header-scrolled');
                }
            }, CrusertelApp.config.debounceDelay);

            window.addEventListener('scroll', scrollHandler, { passive: true });
        }
    },

    // ===================================
    // INTERACTIONS MODULE
    // ===================================
    
    interactions: {
        initServiceItems() {
            // Remove conflicting JavaScript hover effects
            // CSS hover effects in .service-item:hover handle the smooth scaling
            const serviceItems = document.querySelectorAll('.service-item');
            
            // Just ensure the items are interactive - CSS handles the rest
            serviceItems.forEach((item) => {
                item.style.cursor = 'pointer';
            });
        },

        initImageHovers() {
            const hoverImages = document.querySelectorAll('.img-hover-grow');
            
            hoverImages.forEach(image => {
                // Add CSS transition via class instead of inline styles
                image.classList.add('js-hover-enabled');
                
                // Fallback for old version if CSS classes don't exist
                if (!image.style.transition) {
                    image.style.transition = 'transform 0.4s ease';
                }
                
                image.addEventListener('mouseenter', () => {
                    image.style.transform = 'scale(1.1)';
                });
                
                image.addEventListener('mouseleave', () => {
                    image.style.transform = 'scale(1)';
                });
            });
        },

        initFAQAccordion() {
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('h3');
                const answer = item.querySelector('.faq-respuesta');
                
                if (question && answer) {
                    question.classList.add('faq-question-clickable');
                    question.style.cursor = 'pointer';
                    
                    question.addEventListener('click', () => {
                        const isOpen = answer.classList.contains('faq-answer-open');
                        
                        // Close all other FAQ items
                        faqItems.forEach(otherItem => {
                            const otherAnswer = otherItem.querySelector('.faq-respuesta');
                            if (otherAnswer && otherAnswer !== answer) {
                                otherAnswer.classList.remove('faq-answer-open');
                            }
                        });
                        
                        // Toggle current item
                        if (isOpen) {
                            answer.classList.remove('faq-answer-open');
                        } else {
                            answer.classList.add('faq-answer-open');
                        }
                    });
                }
            });
        },

        initTarifasModal() {
            const images = document.querySelectorAll('.tarifa img');
            const modal = document.getElementById('modal');
            const modalImg = document.getElementById('imgAmpliada');
            const closeBtn = document.querySelector('.cerrar');

            if (!modal || !modalImg || images.length === 0) return;

            // Open modal
            images.forEach(img => {
                img.addEventListener('click', () => {
                    modal.style.display = 'block';
                    modal.classList.add('modal-open');
                    modalImg.src = img.src;
                    modalImg.alt = img.alt || '';
                    document.body.classList.add('modal-open-body');
                });
            });

            // Close modal function
            const closeModal = () => {
                modal.style.display = 'none';
                modal.classList.remove('modal-open');
                document.body.classList.remove('modal-open-body');
            };

            // Close events
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            window.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.style.display === 'block') {
                    closeModal();
                }
            });
        },

        initMobileMenu() {
            const menuToggle = document.getElementById('menu-toggle');
            const mainNav = document.querySelector('.main-nav');

            if (menuToggle && mainNav) {
                menuToggle.addEventListener('click', () => {
                    mainNav.classList.toggle('menu-abierto');
                });

                // Close menu when clicking outside
                document.addEventListener('click', (e) => {
                    if (!menuToggle.contains(e.target) && !mainNav.contains(e.target)) {
                        mainNav.classList.remove('menu-abierto');
                    }
                });
            }
        },

        initReturnButtons() {
            // Create return button for tarifs page
            if (CrusertelApp.state.currentPage === 'tarifs') {
                const returnBtn = document.createElement('button');
                returnBtn.textContent = '← Volver a Inicio';
                returnBtn.classList.add('boton-volver');
                returnBtn.onclick = () => {
                    window.location.href = 'pagina_Inicio.html';
                };
                document.body.appendChild(returnBtn);
            }
        },

        init() {
            this.initServiceItems();
            this.initImageHovers();
            this.initFAQAccordion();
            this.initTarifasModal();
            this.initMobileMenu();
            this.initReturnButtons();
        }
    },

    // ===================================
    // FORMS MODULE
    // ===================================
    
    forms: {
        initContactForm() {
            const contactForm = document.querySelector('#contact-form form, form[action*="guardar_contacto"]');
            
            if (!contactForm) return;
            
            contactForm.addEventListener('submit', (e) => {
                const formData = this.getFormData(contactForm);
                const errors = this.validateContactForm(formData);
                
                if (errors.length > 0) {
                    e.preventDefault();
                    CrusertelApp.ui.showAlert(errors.join(', '), 'error');
                    return false;
                }
            });
            
            this.showFormMessages();
        },

        getFormData(form) {
            // Support both field name conventions (old and new)
            return {
                name: form.querySelector('[name="name"], [name="nombre"]')?.value.trim() || '',
                email: form.querySelector('[name="email"]')?.value.trim() || '',
                phone: form.querySelector('[name="telefono"]')?.value.trim() || '',
                subject: form.querySelector('[name="subject"], [name="asunto"]')?.value.trim() || '',
                message: form.querySelector('[name="message"], [name="mensaje"]')?.value.trim() || ''
            };
        },

        validateContactForm({ name, email, subject, message }) {
            const errors = [];
            
            if (!name) errors.push('El nombre es obligatorio');
            if (!email || !this.isValidEmail(email)) errors.push('Email inválido');
            if (!subject) errors.push('El asunto es obligatorio');
            if (!message) errors.push('El mensaje es obligatorio');
            
            return errors;
        },

        isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        },

        showFormMessages() {
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');
            const error = urlParams.get('error');
            
            if (success === '1') {
                CrusertelApp.ui.showAlert('Mensaje enviado correctamente. Te contactaremos pronto.', 'success');
            } else if (error) {
                const errorMessages = {
                    'save_failed': 'Error al guardar el mensaje. Por favor, inténtalo de nuevo.',
                    'invalid_method': 'Método inválido'
                };
                const message = errorMessages[error] || decodeURIComponent(error);
                CrusertelApp.ui.showAlert(message, 'error');
            }
        },

        initFormEnhancements() {
            const formInputs = document.querySelectorAll('input, textarea');
            
            formInputs.forEach(input => {
                input.addEventListener('focus', () => {
                    input.parentElement?.classList.add('form-input-focused');
                });
                
                input.addEventListener('blur', () => {
                    if (!input.value) {
                        input.parentElement?.classList.remove('form-input-focused');
                    }
                });
            });
        },

        init() {
            this.initContactForm();
            this.initFormEnhancements();
        }
    },

    // ===================================
    // NAVIGATION MODULE
    // ===================================
    
    navigation: {
        initSmoothScrolling() {
            const anchorLinks = document.querySelectorAll('a[href^="#"]');
            
            anchorLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    const targetId = link.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        },

        initBackToTop() {
            const backToTopBtn = this.createBackToTopButton();
            document.body.appendChild(backToTopBtn);

            const scrollHandler = CrusertelApp.utils.debounce(() => {
                if (window.pageYOffset > 300) {
                    backToTopBtn.classList.add('back-to-top-visible');
                } else {
                    backToTopBtn.classList.remove('back-to-top-visible');
                }
            }, 100);

            window.addEventListener('scroll', scrollHandler, { passive: true });

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        },

        createBackToTopButton() {
            const button = document.createElement('button');
            button.innerHTML = '↑';
            button.className = 'back-to-top';
            button.setAttribute('aria-label', 'Volver arriba');
            return button;
        },

        init() {
            this.initSmoothScrolling();
            this.initBackToTop();
        }
    },

    // ===================================
    // UI MODULE
    // ===================================
    
    ui: {
        showAlert(message, type = 'info') {
            // Remove any existing alerts
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 300);
            }, 5000);
        },

        hideLoadingIndicator() {
            const loader = document.querySelector('.loading-indicator, .loader');
            if (loader) {
                loader.classList.add('loading-hidden');
            }
        },

        init() {
            this.hideLoadingIndicator();
        }
    },

    // ===================================
    // UTILITIES
    // ===================================
    
    utils: {
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func.apply(this, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },

        formatPhoneNumber(phoneNumber) {
            const cleaned = phoneNumber.replace(/\D/g, '');
            const match = cleaned.match(/^(\d{3})(\d{2})(\d{2})(\d{2})$/);
            return match ? `${match[1]} ${match[2]} ${match[3]} ${match[4]}` : phoneNumber;
        }
    },

    // ===================================
    // CLEANUP
    // ===================================
    
    cleanup() {
        // Clear all active intervals
        this.state.activeIntervals.forEach(interval => clearInterval(interval));
        this.state.activeIntervals.clear();
    }
};

// ===============
// HEADER
// ===============

// Simple header loader with debugging
console.log('main.js loaded - starting header injection');

function loadHeader() {
    console.log('loadHeader function called');
    
    fetch('header.html')
        .then(response => {
            console.log('Fetch response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.text();
        })
        .then(html => {
            console.log('Header HTML loaded successfully');
            // Create a temporary container to hold the header
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            const headerElement = tempDiv.firstElementChild;
            
            // Insert header at the beginning of body
            document.body.insertBefore(headerElement, document.body.firstChild);
            console.log('Header inserted into page');
            
            // Initialize burger menu
            initBurgerMenu();
        })
        .catch(error => {
            console.error('Error loading header:', error);
            // Fallback: create a basic header if loading fails
            createFallbackHeader();
        });
}

function createFallbackHeader() {
    console.log('Creating fallback header');
    const fallbackHeader = `
        <header class="fade-in-down-initial">
            <div class="container header-content">
                <div class="logo">
                    <a href="pagina_Inicio.html"><img src="img/imagen_logo_crusertel.jpg" alt="Logo Crusertel"></a>
                </div>
                <nav>
                    <ul>
                        <li><a href="pagina_servicios.html">Servicios</a></li>
                        <li><a href="pagina_faq.html">Preguntas Frecuentes</a></li>
                        <li><a href="pagina_contacto.php">Contacto</a></li>
                        <li><a href="pagina_unete.html">Únete</a></li>
                    </ul>
                </nav>
            </div>
        </header>
    `;
    document.body.insertAdjacentHTML('afterbegin', fallbackHeader);
}

function initBurgerMenu() {
    console.log('Initializing burger menu');
    const burgerMenu = document.querySelector('.burger-menu');
    const navMenu = document.querySelector('.nav-menu');
    
    if (!burgerMenu) {
        console.log('No burger menu found on this page');
        return;
    }

    console.log('Burger menu element found:', burgerMenu);

    // Create overlay element
    const overlay = document.createElement('div');
    overlay.className = 'nav-overlay';
    document.body.appendChild(overlay);

    function toggleMenu() {
        console.log('Toggling menu');
        burgerMenu.classList.toggle('active');
        navMenu.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
    }

    // Event listeners
    burgerMenu.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);

    // Close menu when clicking on links
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (navMenu.classList.contains('active')) {
                toggleMenu();
            }
        });
    });

    // Close menu with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && navMenu.classList.contains('active')) {
            toggleMenu();
        }
    });
    
    console.log('Burger menu initialized successfully');
}

// Load header when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadHeader);
} else {
    loadHeader();
}

// ===================================
// INITIALIZE APPLICATION
// ===================================

document.addEventListener('DOMContentLoaded', () => {
    CrusertelApp.init();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    CrusertelApp.cleanup();
});

// Export for potential external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CrusertelApp;
}

