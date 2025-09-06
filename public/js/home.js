/**
 * Scriptoria Home Page JavaScript
 * Handles search functionality, animations, and interactions
 */

class ScriptoriaHome {
    constructor() {
        this.searchInput = null;
        this.searchTimeout = null;
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        this.setupSearch();
        this.setupAnimations();
        this.setupKeyboardShortcuts();
        this.setupSmoothScrolling();
    }

    setupSearch() {
        this.searchInput = document.getElementById('searchInput');
        
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                this.handleSearch(e.target.value);
            });
        }
    }

    handleSearch(searchTerm) {
        // Clear previous timeout
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
        
        const normalizedTerm = searchTerm.toLowerCase().trim();
        
        // If search is empty, show all articles
        if (normalizedTerm === '') {
            this.showAllArticles();
            return;
        }
        
        // Debounce search to avoid too many operations
        this.searchTimeout = setTimeout(() => {
            this.performSearch(normalizedTerm);
        }, 300);
    }

    showAllArticles() {
        const articleCards = document.querySelectorAll('.article-card');
        articleCards.forEach(card => {
            card.style.display = 'block';
            card.style.opacity = '1';
        });
    }

    performSearch(searchTerm) {
        const articleCards = document.querySelectorAll('.article-card');
        let hasResults = false;
        
        articleCards.forEach(card => {
            const title = card.getAttribute('data-title') || '';
            const content = card.getAttribute('data-content') || '';
            
            if (title.includes(searchTerm) || content.includes(searchTerm)) {
                card.style.display = 'block';
                card.style.opacity = '1';
                hasResults = true;
            } else {
                card.style.display = 'none';
                card.style.opacity = '0';
            }
        });

        // Show no results message if needed
        this.toggleNoResultsMessage(!hasResults && searchTerm !== '');
    }

    toggleNoResultsMessage(show) {
        let noResultsMsg = document.getElementById('no-results-message');
        
        if (show && !noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.id = 'no-results-message';
            noResultsMsg.className = 'error-message';
            noResultsMsg.innerHTML = `
                <h3>No Articles Found</h3>
                <p>Try adjusting your search terms or browse all articles.</p>
                <button onclick="scriptoriaHome.clearSearch()" class="read-more-btn" style="margin-top: 15px;">
                    Clear Search
                </button>
            `;
            
            const articlesGrid = document.getElementById('articlesGrid');
            if (articlesGrid) {
                articlesGrid.appendChild(noResultsMsg);
            }
        } else if (!show && noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    clearSearch() {
        if (this.searchInput) {
            this.searchInput.value = '';
            this.showAllArticles();
            this.toggleNoResultsMessage(false);
        }
    }

    setupAnimations() {
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Animate article cards
        const articleCards = document.querySelectorAll('.article-card');
        articleCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });

        // Animate stats
        const stats = document.querySelectorAll('.stat');
        stats.forEach((stat, index) => {
            stat.style.opacity = '0';
            stat.style.transform = 'translateY(20px)';
            stat.style.transition = `opacity 0.8s ease ${index * 0.2}s, transform 0.8s ease ${index * 0.2}s`;
            observer.observe(stat);
        });
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (this.searchInput) {
                    this.searchInput.focus();
                }
            }
            
            // Escape to clear search
            if (e.key === 'Escape' && document.activeElement === this.searchInput) {
                this.clearSearch();
                this.searchInput.blur();
            }
        });
    }

    setupSmoothScrolling() {
        // Add smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // Method to highlight search terms in results
    highlightSearchTerm(text, searchTerm) {
        if (!searchTerm) return text;
        
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return text.replace(regex, '<mark class="search-highlight">$1</mark>');
    }

    // Method to update statistics (can be called externally)
    updateStats(stats) {
        Object.keys(stats).forEach(key => {
            const statElement = document.querySelector(`[data-stat="${key}"] .stat-number`);
            if (statElement) {
                this.animateNumber(statElement, parseInt(statElement.textContent), stats[key]);
            }
        });
    }

    // Animate number changes
    animateNumber(element, from, to, duration = 1000) {
        const start = Date.now();
        const range = to - from;
        
        function step() {
            const now = Date.now();
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function
            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(from + range * easeOutCubic);
            
            element.textContent = current;
            
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        }
        
        step();
    }

    // Add loading state to buttons
    setButtonLoading(button, loading = true) {
        if (loading) {
            button.dataset.originalText = button.textContent;
            button.textContent = 'Loading...';
            button.disabled = true;
            button.style.opacity = '0.7';
        } else {
            button.textContent = button.dataset.originalText;
            button.disabled = false;
            button.style.opacity = '1';
        }
    }

    // Handle article card interactions
    setupArticleInteractions() {
        const articleCards = document.querySelectorAll('.article-card');
        
        articleCards.forEach(card => {
            // Add hover sound effect (optional)
            card.addEventListener('mouseenter', () => {
                this.playHoverSound();
            });
            
            // Add click analytics
            card.addEventListener('click', () => {
                this.trackArticleClick(card);
            });
        });
    }

    playHoverSound() {
        // Optional: Add subtle hover sound
        // Can be implemented if audio feedback is desired
    }

    trackArticleClick(card) {
        const title = card.querySelector('.article-title')?.textContent;
        const author = card.querySelector('.article-author')?.textContent;
        
        // Send analytics data
        console.log('Article clicked:', { title, author });
        
        // Can integrate with Google Analytics or other tracking services
        // gtag('event', 'article_click', { article_title: title, author: author });
    }

    // Method to refresh content dynamically
    async refreshContent() {
        try {
            const response = await fetch('/api/home-data');
            const data = await response.json();
            
            if (data.success) {
                this.updateStats(data.stats);
                // Can update articles if needed
            }
        } catch (error) {
            console.error('Failed to refresh content:', error);
        }
    }
}

// Initialize the home page functionality
const scriptoriaHome = new ScriptoriaHome();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ScriptoriaHome;
}

// Make available globally
window.ScriptoriaHome = ScriptoriaHome;
window.scriptoriaHome = scriptoriaHome;
