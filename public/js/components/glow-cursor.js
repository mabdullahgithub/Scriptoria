/**
 * Glowing Cursor Component JavaScript
 * Creates an interactive glowing cursor with particles and trail effects
 */

class GlowCursor {
    constructor(options = {}) {
        this.options = {
            // Cursor settings
            size: options.size || 20,
            trailLength: options.trailLength || 5,
            particleCount: options.particleCount || 5,
            
            // Animation settings
            followSpeed: options.followSpeed || 0.1,
            trailSpeed: options.trailSpeed || 0.3,
            
            // Visual settings
            glowIntensity: options.glowIntensity || 0.3,
            colors: options.colors || {
                primary: 'rgba(102, 126, 234, 0.3)',
                secondary: 'rgba(118, 75, 162, 0.3)',
                accent: 'rgba(255, 255, 255, 0.1)'
            },
            
            // Behavior settings
            hideOnTouch: options.hideOnTouch !== false,
            hideOnMobile: options.hideOnMobile !== false,
            enableParticles: options.enableParticles !== false,
            enableTrail: options.enableTrail !== false,
            
            // Selectors
            hoverSelectors: options.hoverSelectors || ['a', 'button', '.clickable', '[role="button"]'],
            hideSelectors: options.hideSelectors || ['input', 'textarea', 'select']
        };

        this.cursor = null;
        this.trail = [];
        this.mousePos = { x: 0, y: 0 };
        this.cursorPos = { x: 0, y: 0 };
        this.isVisible = true;
        this.isHovering = false;
        this.isClicking = false;
        
        this.init();
    }

    init() {
        // Check if we should initialize on this device
        if (!this.shouldInitialize()) {
            return;
        }

        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    shouldInitialize() {
        // Don't initialize on touch devices if disabled
        if (this.options.hideOnTouch && ('ontouchstart' in window || navigator.maxTouchPoints > 0)) {
            return false;
        }

        // Don't initialize on mobile if disabled
        if (this.options.hideOnMobile && window.innerWidth <= 768) {
            return false;
        }

        // Don't initialize if reduced motion is preferred
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return false;
        }

        return true;
    }

    setup() {
        this.createCursor();
        this.createTrail();
        this.addEventListeners();
        this.startAnimation();
    }

    createCursor() {
        this.cursor = document.createElement('div');
        this.cursor.className = 'glow-cursor';
        this.cursor.style.cssText = `
            position: fixed;
            width: ${this.options.size}px;
            height: ${this.options.size}px;
            border-radius: 50%;
            background: radial-gradient(circle, ${this.options.colors.primary} 0%, transparent 70%);
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.1s ease;
            mix-blend-mode: screen;
        `;
        
        document.body.appendChild(this.cursor);
    }

    createTrail() {
        if (!this.options.enableTrail) return;

        for (let i = 0; i < this.options.trailLength; i++) {
            const trailElement = document.createElement('div');
            trailElement.className = 'cursor-trail';
            trailElement.style.cssText = `
                position: fixed;
                width: ${this.options.size * 0.4}px;
                height: ${this.options.size * 0.4}px;
                border-radius: 50%;
                background: radial-gradient(circle, ${this.options.colors.secondary} 0%, transparent 70%);
                pointer-events: none;
                z-index: 9998;
                opacity: ${0.8 - (i * 0.15)};
                transition: opacity 0.3s ease;
            `;
            
            document.body.appendChild(trailElement);
            this.trail.push({
                element: trailElement,
                x: 0,
                y: 0
            });
        }
    }

    addEventListeners() {
        // Mouse movement
        document.addEventListener('mousemove', (e) => this.onMouseMove(e));
        
        // Mouse enter/leave
        document.addEventListener('mouseenter', () => this.show());
        document.addEventListener('mouseleave', () => this.hide());
        
        // Click events
        document.addEventListener('mousedown', (e) => this.onMouseDown(e));
        document.addEventListener('mouseup', () => this.onMouseUp());
        
        // Click particles
        if (this.options.enableParticles) {
            document.addEventListener('click', (e) => this.createParticles(e));
        }
        
        // Hover effects
        this.addHoverListeners();
        
        // Window resize
        window.addEventListener('resize', () => this.onResize());
        
        // Visibility change
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.hide();
            } else {
                this.show();
            }
        });
    }

    addHoverListeners() {
        const hoverElements = document.querySelectorAll(this.options.hoverSelectors.join(', '));
        const hideElements = document.querySelectorAll(this.options.hideSelectors.join(', '));
        
        hoverElements.forEach(element => {
            element.addEventListener('mouseenter', () => this.onHoverStart());
            element.addEventListener('mouseleave', () => this.onHoverEnd());
        });
        
        hideElements.forEach(element => {
            element.addEventListener('mouseenter', () => this.hide());
            element.addEventListener('mouseleave', () => this.show());
        });
    }

    onMouseMove(e) {
        this.mousePos.x = e.clientX;
        this.mousePos.y = e.clientY;
        
        if (!this.isVisible) {
            this.show();
        }
    }

    onMouseDown(e) {
        this.isClicking = true;
        if (this.cursor) {
            this.cursor.classList.add('click');
        }
    }

    onMouseUp() {
        this.isClicking = false;
        if (this.cursor) {
            this.cursor.classList.remove('click');
        }
    }

    onHoverStart() {
        this.isHovering = true;
        if (this.cursor) {
            this.cursor.classList.add('hover');
        }
    }

    onHoverEnd() {
        this.isHovering = false;
        if (this.cursor) {
            this.cursor.classList.remove('hover');
        }
    }

    onResize() {
        // Re-check if we should be visible on resize
        if (!this.shouldInitialize() && this.isVisible) {
            this.destroy();
        }
    }

    createParticles(e) {
        const particleCount = this.options.particleCount;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'cursor-particle';
            
            // Calculate random direction
            const angle = (Math.PI * 2 * i) / particleCount + (Math.random() - 0.5) * 0.5;
            const velocity = 50 + Math.random() * 50;
            const x = Math.cos(angle) * velocity;
            const y = Math.sin(angle) * velocity;
            
            particle.style.cssText = `
                position: fixed;
                width: 4px;
                height: 4px;
                background: linear-gradient(135deg, #667eea, #764ba2);
                border-radius: 50%;
                pointer-events: none;
                z-index: 1000;
                left: ${e.clientX - 2}px;
                top: ${e.clientY - 2}px;
            `;
            
            document.body.appendChild(particle);
            
            // Animate particle
            particle.animate([
                { 
                    transform: 'translate(0, 0) scale(1)', 
                    opacity: 1 
                },
                { 
                    transform: `translate(${x}px, ${y}px) scale(0)`, 
                    opacity: 0 
                }
            ], {
                duration: 800 + Math.random() * 400,
                easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
            }).onfinish = () => {
                if (document.body.contains(particle)) {
                    document.body.removeChild(particle);
                }
            };
        }
    }

    updateCursorPosition() {
        // Smooth follow animation
        this.cursorPos.x += (this.mousePos.x - this.cursorPos.x) * this.options.followSpeed;
        this.cursorPos.y += (this.mousePos.y - this.cursorPos.y) * this.options.followSpeed;
        
        if (this.cursor) {
            this.cursor.style.left = (this.cursorPos.x - this.options.size / 2) + 'px';
            this.cursor.style.top = (this.cursorPos.y - this.options.size / 2) + 'px';
        }
    }

    updateTrail() {
        if (!this.options.enableTrail || this.trail.length === 0) return;
        
        // Update trail positions
        for (let i = this.trail.length - 1; i > 0; i--) {
            this.trail[i].x += (this.trail[i - 1].x - this.trail[i].x) * this.options.trailSpeed;
            this.trail[i].y += (this.trail[i - 1].y - this.trail[i].y) * this.options.trailSpeed;
            
            this.trail[i].element.style.left = (this.trail[i].x - this.options.size * 0.2) + 'px';
            this.trail[i].element.style.top = (this.trail[i].y - this.options.size * 0.2) + 'px';
        }
        
        // First trail element follows cursor with delay
        if (this.trail[0]) {
            this.trail[0].x += (this.cursorPos.x - this.trail[0].x) * (this.options.trailSpeed * 0.7);
            this.trail[0].y += (this.cursorPos.y - this.trail[0].y) * (this.options.trailSpeed * 0.7);
            
            this.trail[0].element.style.left = (this.trail[0].x - this.options.size * 0.2) + 'px';
            this.trail[0].element.style.top = (this.trail[0].y - this.options.size * 0.2) + 'px';
        }
    }

    startAnimation() {
        const animate = () => {
            if (this.isVisible) {
                this.updateCursorPosition();
                this.updateTrail();
            }
            requestAnimationFrame(animate);
        };
        animate();
    }

    show() {
        this.isVisible = true;
        if (this.cursor) {
            this.cursor.classList.remove('hidden');
        }
        this.trail.forEach(trailItem => {
            trailItem.element.style.opacity = '';
        });
    }

    hide() {
        this.isVisible = false;
        if (this.cursor) {
            this.cursor.classList.add('hidden');
        }
        this.trail.forEach(trailItem => {
            trailItem.element.style.opacity = '0';
        });
    }

    updateColors(colors) {
        this.options.colors = { ...this.options.colors, ...colors };
        
        if (this.cursor) {
            this.cursor.style.background = `radial-gradient(circle, ${this.options.colors.primary} 0%, transparent 70%)`;
        }
        
        this.trail.forEach(trailItem => {
            trailItem.element.style.background = `radial-gradient(circle, ${this.options.colors.secondary} 0%, transparent 70%)`;
        });
    }

    setSize(size) {
        this.options.size = size;
        
        if (this.cursor) {
            this.cursor.style.width = size + 'px';
            this.cursor.style.height = size + 'px';
        }
        
        this.trail.forEach(trailItem => {
            const trailSize = size * 0.4;
            trailItem.element.style.width = trailSize + 'px';
            trailItem.element.style.height = trailSize + 'px';
        });
    }

    destroy() {
        // Remove cursor
        if (this.cursor && document.body.contains(this.cursor)) {
            document.body.removeChild(this.cursor);
        }
        
        // Remove trail elements
        this.trail.forEach(trailItem => {
            if (document.body.contains(trailItem.element)) {
                document.body.removeChild(trailItem.element);
            }
        });
        
        // Remove particles
        const particles = document.querySelectorAll('.cursor-particle');
        particles.forEach(particle => {
            if (document.body.contains(particle)) {
                document.body.removeChild(particle);
            }
        });
        
        // Clear references
        this.cursor = null;
        this.trail = [];
        this.isVisible = false;
    }
}

// Auto-initialize if no manual initialization is detected
let glowCursorInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    // Only auto-initialize if not manually created
    if (!window.glowCursorManualInit) {
        glowCursorInstance = new GlowCursor();
    }
});

// Export for manual initialization
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GlowCursor;
}

// Make available globally
window.GlowCursor = GlowCursor;
window.glowCursorInstance = glowCursorInstance;
