// Skills Page Interactive JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize progress bars animation
    initializeProgressBars();
    
    // Initialize skill item interactions
    initializeSkillInteractions();
    
    // Initialize intersection observer for animations
    initializeIntersectionObserver();
    
    // Initialize category filtering (if needed in future)
    initializeCategoryFilters();
});

// Progress Bar Animation
function initializeProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar');
    
    // Animate progress bars when they come into view
    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBar = entry.target;
                const progress = progressBar.getAttribute('data-progress');
                
                // Delay animation slightly for better visual effect
                setTimeout(() => {
                    progressBar.style.width = progress + '%';
                }, 300);
                
                // Unobserve after animation starts
                progressObserver.unobserve(progressBar);
            }
        });
    }, {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    });
    
    progressBars.forEach(bar => {
        progressObserver.observe(bar);
    });
}

// Skill Item Interactions
function initializeSkillInteractions() {
    const skillItems = document.querySelectorAll('.skill-item');
    
    skillItems.forEach(item => {
        // Add click interaction for skill details
        item.addEventListener('click', function() {
            const skillName = this.querySelector('.skill-name').textContent;
            const skillLevel = this.querySelector('.skill-level').textContent;
            const progress = this.querySelector('.progress-bar').getAttribute('data-progress');
            
            // Create a subtle pulse effect
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
            
            // Optional: Show skill details in console (can be extended to show modal)
            console.log(`Skill: ${skillName}, Level: ${skillLevel}, Proficiency: ${progress}%`);
        });
        
        // Add hover sound effect (optional)
        item.addEventListener('mouseenter', function() {
            // Can add subtle sound effect here if needed
            this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        // Enhanced hover effects
        item.addEventListener('mouseover', function() {
            const icon = this.querySelector('.skill-icon');
            const progressBar = this.querySelector('.progress-bar');
            
            // Add extra glow to progress bar on hover
            progressBar.style.filter = 'brightness(1.2) saturate(1.3)';
        });
        
        item.addEventListener('mouseout', function() {
            const progressBar = this.querySelector('.progress-bar');
            progressBar.style.filter = '';
        });
    });
}

// Intersection Observer for Staggered Animations
function initializeIntersectionObserver() {
    const animatedElements = document.querySelectorAll('.skill-category, .summary-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                
                // Add extra animation class for enhanced effects
                entry.target.classList.add('animate-in');
                
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

// Category Filtering (Future Enhancement)
function initializeCategoryFilters() {
    const categories = document.querySelectorAll('.skill-category');
    
    // Add data attributes for potential filtering
    categories.forEach((category, index) => {
        const categoryType = category.getAttribute('data-category');
        
        // Add subtle loading animation delay
        category.style.animationDelay = `${(index + 1) * 0.2}s`;
        
        // Optional: Add click handler for category expansion/collapse
        const categoryTitle = category.querySelector('.category-title');
        if (categoryTitle) {
            categoryTitle.addEventListener('click', function() {
                const skillsList = category.querySelector('.skills-list');
                const icon = this.querySelector('.category-icon');
                
                // Toggle expanded state (can be extended for collapse/expand functionality)
                category.classList.toggle('expanded');
                
                // Rotate icon slightly on interaction
                icon.style.transform = category.classList.contains('expanded') 
                    ? 'rotate(15deg) scale(1.1)' 
                    : '';
            });
        }
    });
}

// Utility function for smooth scrolling to skills section (if called from navigation)
function scrollToSkills() {
    const skillsContainer = document.querySelector('.skills-container');
    if (skillsContainer) {
        skillsContainer.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Add keyboard navigation support
document.addEventListener('keydown', function(event) {
    const focusedElement = document.activeElement;
    
    if (focusedElement && focusedElement.classList.contains('skill-item')) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            focusedElement.click();
        }
    }
});

// Make skill items focusable for accessibility
document.querySelectorAll('.skill-item').forEach(item => {
    item.setAttribute('tabindex', '0');
    item.setAttribute('role', 'button');
    item.setAttribute('aria-label', `View details for ${item.querySelector('.skill-name').textContent}`);
});

// Performance optimization: Debounce resize events
let resizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
        // Reinitialize animations if needed on resize
        const progressBars = document.querySelectorAll('.progress-bar[style*="width"]');
        if (progressBars.length === 0) {
            // Reinitialize if progress bars were reset
            initializeProgressBars();
        }
    }, 250);
});

// Export functions for potential external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        scrollToSkills,
        initializeProgressBars,
        initializeSkillInteractions
    };
}
