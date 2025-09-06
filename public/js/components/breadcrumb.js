/**
 * Breadcrumb Component JavaScript
 * Handles interactive functionality for the breadcrumb navigation
 */

class BreadcrumbComponent {
    constructor() {
        this.breadcrumbNav = null;
        this.init();
    }

    init() {
        // Wait for DOM to be loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        this.breadcrumbNav = document.querySelector('.breadcrumb-nav');
        
        if (this.breadcrumbNav) {
            this.addEventListeners();
            this.handleResponsiveText();
            this.addKeyboardNavigation();
        }
    }

    addEventListeners() {
        // Add click analytics tracking
        const breadcrumbLinks = this.breadcrumbNav.querySelectorAll('.breadcrumb-link');
        
        breadcrumbLinks.forEach((link, index) => {
            link.addEventListener('click', (e) => {
                this.trackBreadcrumbClick(link, index);
            });

            // Add hover effects for better UX
            link.addEventListener('mouseenter', () => {
                this.onLinkHover(link);
            });

            link.addEventListener('mouseleave', () => {
                this.onLinkLeave(link);
            });
        });

        // Handle window resize for responsive behavior
        window.addEventListener('resize', () => {
            this.handleResponsiveText();
        });
    }

    trackBreadcrumbClick(link, index) {
        // Track breadcrumb clicks for analytics
        const href = link.getAttribute('href');
        const text = link.textContent.trim();
        
        console.log(`Breadcrumb clicked: ${text} (${href}) at position ${index}`);
        
        // You can integrate with analytics services here
        // Example: gtag('event', 'breadcrumb_click', { href, text, position: index });
    }

    onLinkHover(link) {
        // Add subtle animation on hover
        link.style.transform = 'translateY(-2px)';
    }

    onLinkLeave(link) {
        // Reset animation
        link.style.transform = 'translateY(-1px)';
    }

    handleResponsiveText() {
        if (!this.breadcrumbNav) return;

        const windowWidth = window.innerWidth;
        const breadcrumbItems = this.breadcrumbNav.querySelectorAll('.breadcrumb-item');

        // On very small screens, truncate long breadcrumb text
        if (windowWidth <= 480) {
            breadcrumbItems.forEach(item => {
                const link = item.querySelector('.breadcrumb-link');
                if (link) {
                    const originalText = link.getAttribute('data-original-text') || link.textContent.trim();
                    link.setAttribute('data-original-text', originalText);
                    
                    if (originalText.length > 12) {
                        link.childNodes.forEach(node => {
                            if (node.nodeType === Node.TEXT_NODE) {
                                node.textContent = originalText.substring(0, 10) + '...';
                            }
                        });
                    }
                }
            });
        } else {
            // Restore original text on larger screens
            breadcrumbItems.forEach(item => {
                const link = item.querySelector('.breadcrumb-link');
                if (link && link.getAttribute('data-original-text')) {
                    const originalText = link.getAttribute('data-original-text');
                    link.childNodes.forEach(node => {
                        if (node.nodeType === Node.TEXT_NODE) {
                            node.textContent = originalText;
                        }
                    });
                }
            });
        }
    }

    addKeyboardNavigation() {
        if (!this.breadcrumbNav) return;

        const breadcrumbLinks = this.breadcrumbNav.querySelectorAll('.breadcrumb-link');
        
        breadcrumbLinks.forEach((link, index) => {
            // Make links focusable and add keyboard navigation
            link.setAttribute('tabindex', '0');
            
            link.addEventListener('keydown', (e) => {
                switch (e.key) {
                    case 'Enter':
                    case ' ':
                        e.preventDefault();
                        link.click();
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        this.focusNextLink(index, breadcrumbLinks);
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        this.focusPrevLink(index, breadcrumbLinks);
                        break;
                    case 'Home':
                        e.preventDefault();
                        breadcrumbLinks[0]?.focus();
                        break;
                    case 'End':
                        e.preventDefault();
                        breadcrumbLinks[breadcrumbLinks.length - 1]?.focus();
                        break;
                }
            });
        });
    }

    focusNextLink(currentIndex, links) {
        const nextIndex = currentIndex + 1;
        if (nextIndex < links.length) {
            links[nextIndex].focus();
        }
    }

    focusPrevLink(currentIndex, links) {
        const prevIndex = currentIndex - 1;
        if (prevIndex >= 0) {
            links[prevIndex].focus();
        }
    }

    // Public method to update breadcrumb dynamically
    updateBreadcrumb(breadcrumbs) {
        if (!this.breadcrumbNav) return;

        const breadcrumbList = this.breadcrumbNav.querySelector('.breadcrumb-list');
        if (!breadcrumbList) return;

        // Clear existing breadcrumbs
        breadcrumbList.innerHTML = '';

        // Add new breadcrumbs
        breadcrumbs.forEach((breadcrumb, index) => {
            const li = document.createElement('li');
            li.className = `breadcrumb-item ${index === breadcrumbs.length - 1 ? 'active' : ''}`;

            if (index !== breadcrumbs.length - 1 && breadcrumb.url) {
                // Create link for non-last items
                const link = document.createElement('a');
                link.href = breadcrumb.url;
                link.className = 'breadcrumb-link';
                
                if (breadcrumb.icon) {
                    const iconSpan = document.createElement('span');
                    iconSpan.className = 'breadcrumb-icon';
                    iconSpan.innerHTML = breadcrumb.icon;
                    link.appendChild(iconSpan);
                }
                
                link.appendChild(document.createTextNode(breadcrumb.title));
                li.appendChild(link);
            } else {
                // Create text for last item
                if (breadcrumb.icon) {
                    const iconSpan = document.createElement('span');
                    iconSpan.className = 'breadcrumb-icon';
                    iconSpan.innerHTML = breadcrumb.icon;
                    li.appendChild(iconSpan);
                }
                
                li.appendChild(document.createTextNode(breadcrumb.title));
            }

            // Add separator for non-last items
            if (index !== breadcrumbs.length - 1) {
                const separator = document.createElement('span');
                separator.className = 'breadcrumb-separator';
                separator.innerHTML = `
                    <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.5 1L6.5 6L1.5 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                `;
                li.appendChild(separator);
            }

            breadcrumbList.appendChild(li);
        });

        // Re-setup event listeners for new elements
        this.addEventListeners();
        this.addKeyboardNavigation();
    }

    // Method to show/hide breadcrumb
    toggle(show = true) {
        if (this.breadcrumbNav) {
            this.breadcrumbNav.style.display = show ? 'flex' : 'none';
        }
    }

    // Method to get current breadcrumb path
    getCurrentPath() {
        if (!this.breadcrumbNav) return [];

        const links = this.breadcrumbNav.querySelectorAll('.breadcrumb-link');
        const items = this.breadcrumbNav.querySelectorAll('.breadcrumb-item');
        
        return Array.from(items).map((item, index) => {
            const link = item.querySelector('.breadcrumb-link');
            const icon = item.querySelector('.breadcrumb-icon');
            
            return {
                title: item.textContent.trim(),
                url: link ? link.getAttribute('href') : null,
                icon: icon ? icon.innerHTML : null,
                isActive: item.classList.contains('active')
            };
        });
    }
}

// Initialize breadcrumb component when DOM is ready
const breadcrumbComponent = new BreadcrumbComponent();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BreadcrumbComponent;
}

// Make available globally
window.BreadcrumbComponent = BreadcrumbComponent;
window.breadcrumbComponent = breadcrumbComponent;
