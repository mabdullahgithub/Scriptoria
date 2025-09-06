/**
 * Form Handler JavaScript
 * Handles auto-resizing textareas and form focus management
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea functionality
    const contentTextarea = document.getElementById('content');
    
    if (contentTextarea) {
        function autoResize() {
            contentTextarea.style.height = 'auto';
            contentTextarea.style.height = contentTextarea.scrollHeight + 'px';
        }
        
        contentTextarea.addEventListener('input', autoResize);
        autoResize(); // Initial resize
    }
    
    // Focus management
    const titleInput = document.getElementById('title');
    if (titleInput) {
        titleInput.focus();
    }
    
    // Auto-resize for excerpt textarea as well
    const excerptTextarea = document.getElementById('excerpt');
    if (excerptTextarea) {
        function autoResizeExcerpt() {
            excerptTextarea.style.height = 'auto';
            excerptTextarea.style.height = excerptTextarea.scrollHeight + 'px';
        }
        
        excerptTextarea.addEventListener('input', autoResizeExcerpt);
        autoResizeExcerpt(); // Initial resize
    }
    
    // Form validation helpers
    const forms = document.querySelectorAll('form[data-confirm]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const message = form.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
