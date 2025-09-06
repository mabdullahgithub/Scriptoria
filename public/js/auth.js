// Authentication Page JavaScript

class AuthHandler {
    constructor() {
        this.init();
    }

    init() {
        this.setupFormValidation();
        this.setupPasswordToggle();
        this.setupFormAnimations();
        this.setupKeyboardShortcuts();
    }

    setupFormValidation() {
        const forms = document.querySelectorAll('.auth-form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('.form-input');
            
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    this.validateInput(input);
                });
                
                input.addEventListener('blur', () => {
                    this.validateInput(input);
                });
            });
            
            form.addEventListener('submit', (e) => {
                this.handleFormSubmit(e, form);
            });
        });
    }

    validateInput(input) {
        const value = input.value.trim();
        const type = input.type;
        let isValid = true;
        let errorMessage = '';

        // Remove existing error state
        input.classList.remove('error');
        const existingError = input.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Validate based on input type
        switch (type) {
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (value && !emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address';
                }
                break;
                
            case 'password':
                if (input.name === 'password' && value && value.length < 8) {
                    isValid = false;
                    errorMessage = 'Password must be at least 8 characters long';
                }
                
                // Check password confirmation
                if (input.name === 'password_confirmation') {
                    const passwordInput = document.querySelector('input[name="password"]');
                    if (passwordInput && value && value !== passwordInput.value) {
                        isValid = false;
                        errorMessage = 'Passwords do not match';
                    }
                }
                break;
                
            case 'text':
                if (input.name === 'name' && value && value.length < 2) {
                    isValid = false;
                    errorMessage = 'Name must be at least 2 characters long';
                }
                break;
        }

        if (!isValid) {
            this.showInputError(input, errorMessage);
        }

        return isValid;
    }

    showInputError(input, message) {
        input.classList.add('error');
        
        const errorElement = document.createElement('span');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        
        input.parentNode.appendChild(errorElement);
    }

    setupPasswordToggle() {
        // Add password toggle functionality if needed
        const passwordInputs = document.querySelectorAll('input[type="password"]');
        
        passwordInputs.forEach(input => {
            // You can add a toggle button here if desired
            input.addEventListener('focus', () => {
                input.parentNode.classList.add('focused');
            });
            
            input.addEventListener('blur', () => {
                input.parentNode.classList.remove('focused');
            });
        });
    }

    setupFormAnimations() {
        const inputs = document.querySelectorAll('.form-input');
        
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', () => {
                input.style.transform = 'scale(1)';
            });
        });

        // Animate form elements on load
        const formElements = document.querySelectorAll('.form-group');
        formElements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Enter key to submit form
            if (e.key === 'Enter' && e.target.classList.contains('form-input')) {
                const form = e.target.closest('.auth-form');
                if (form) {
                    const submitButton = form.querySelector('.auth-button');
                    if (submitButton && !submitButton.disabled) {
                        submitButton.click();
                    }
                }
            }
        });
    }

    handleFormSubmit(e, form) {
        const submitButton = form.querySelector('.auth-button');
        const inputs = form.querySelectorAll('.form-input[required]');
        let isFormValid = true;

        // Validate all required inputs
        inputs.forEach(input => {
            if (!input.value.trim()) {
                this.showInputError(input, 'This field is required');
                isFormValid = false;
            } else if (!this.validateInput(input)) {
                isFormValid = false;
            }
        });

        if (isFormValid) {
            // Show loading state
            submitButton.disabled = true;
            const originalText = submitButton.querySelector('span').textContent;
            submitButton.querySelector('span').textContent = 'Please wait...';
            
            // Re-enable after 3 seconds (in case of network issues)
            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.querySelector('span').textContent = originalText;
            }, 3000);
        } else {
            e.preventDefault();
        }
    }

    // Utility method to show notifications
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type}`;
        notification.textContent = message;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.maxWidth = '300px';
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AuthHandler();
});

// Export for potential use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AuthHandler;
}
