import './bootstrap';

// Import Bootstrap JavaScript
import * as bootstrap from 'bootstrap';

// Make Bootstrap available globally
window.bootstrap = bootstrap;

// Custom JavaScript
console.log('%c🏝️ Pariwisata Pesawaran', 'font-size: 24px; color: #667eea; font-weight: bold;');
console.log('%c✨ Website loaded successfully!', 'font-size: 14px; color: #764ba2;');

// ==========================================
// DOM Content Loaded Event
// ==========================================
document.addEventListener('DOMContentLoaded', function() {

    // Initialize Bootstrap Tooltips
    initializeTooltips();

    // Navbar Scroll Effect
    navbarScrollEffect();

    // Scroll to Top Button
    scrollToTopButton();

    // Card Animation on Scroll
    animateOnScroll();

    // Smooth Scroll for Links
    smoothScrollLinks();

    // Add Loading Animation
    addLoadingAnimation();
});

// ==========================================
// Initialize Bootstrap Tooltips
// ==========================================
function initializeTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl =>
        new bootstrap.Tooltip(tooltipTriggerEl)
    );
    console.log('✅ Tooltips initialized:', tooltipList.length);
}

// ==========================================
// Navbar Scroll Effect
// ==========================================
function navbarScrollEffect() {
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// ==========================================
// Scroll to Top Button
// ==========================================
function scrollToTopButton() {
    // Create button element
    const scrollBtn = document.createElement('div');
    scrollBtn.className = 'scroll-to-top';
    scrollBtn.innerHTML = '<i class="bi bi-arrow-up-circle-fill fs-4"></i>';
    document.body.appendChild(scrollBtn);

    // Show/Hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    });

    // Scroll to top on click
    scrollBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    console.log('✅ Scroll to top button created');
}

// ==========================================
// Animate Elements on Scroll
// ==========================================
function animateOnScroll() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards
    document.querySelectorAll('.card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });

    console.log('✅ Scroll animations initialized');
}

// ==========================================
// Smooth Scroll for Anchor Links
// ==========================================
function smoothScrollLinks() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

            // Skip if href is just "#"
            if (href === '#') {
                e.preventDefault();
                return;
            }

            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// ==========================================
// Add Loading Animation on Page Load
// ==========================================
function addLoadingAnimation() {
    // Fade in body content
    document.body.style.opacity = '0';

    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease';
        document.body.style.opacity = '1';
    }, 100);

    console.log('✅ Page loaded with animation');
}

// ==========================================
// Add Particle Background Effect
// ==========================================
function addParticleBackground() {
    const hero = document.querySelector('.hero-section');
    if (!hero) return;

    const particlesContainer = document.createElement('div');
    particlesContainer.className = 'particles-container';
    particlesContainer.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
    `;

    // Create particles
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: absolute;
            width: ${Math.random() * 5 + 2}px;
            height: ${Math.random() * 5 + 2}px;
            background: rgba(255,255,255,${Math.random() * 0.5 + 0.3});
            border-radius: 50%;
            left: ${Math.random() * 100}%;
            top: ${Math.random() * 100}%;
            animation: float ${Math.random() * 10 + 10}s infinite ease-in-out;
            animation-delay: ${Math.random() * 5}s;
        `;
        particlesContainer.appendChild(particle);
    }

    hero.insertBefore(particlesContainer, hero.firstChild);
    console.log('✅ Particle background added');
}

// Call particle background function
addParticleBackground();

// ==========================================
// Console Art
// ==========================================
console.log(`
╔═══════════════════════════════════════════╗
║   PARIWISATA PESAWARAN - WEBSITE v1.0    ║
║   Powered by Laravel 10 + Bootstrap 5    ║
║   Metode ARAS Implementation             ║
╚═══════════════════════════════════════════╝
`);

// ==========================================
// Export functions for global use
// ==========================================
window.PariwisataApp = {
    initializeTooltips,
    animateOnScroll
};
