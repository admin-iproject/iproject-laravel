// ========================================
// iProject Navigation JavaScript
// Handles sidebar collapse and slideout panels
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ==================== SIDEBAR COLLAPSE ====================
    const sidebarToggleBtn = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('left-sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebarToggleBtn && sidebar) {
        sidebarToggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
            // Store state in localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });
        
        // Restore sidebar state from localStorage
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            sidebar.classList.add('collapsed');
        }
    }
    
    // ==================== SLIDEOUT PANELS ====================
    const slideoutTriggers = document.querySelectorAll('[data-slideout]');
    const slideoutPanels = document.querySelectorAll('.slideout-panel');
    const slideoutOverlay = document.getElementById('slideout-overlay');
    const slideoutCloseBtns = document.querySelectorAll('.slideout-close-btn');
    
    // Open slideout
    slideoutTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-slideout');
            const targetPanel = document.getElementById(targetId);
            
            if (targetPanel) {
                // Close all other panels
                slideoutPanels.forEach(panel => panel.classList.remove('open'));
                
                // Open target panel
                targetPanel.classList.add('open');
                
                // Show overlay
                if (slideoutOverlay) {
                    slideoutOverlay.classList.remove('hidden');
                    slideoutOverlay.classList.add('opacity-100');
                }
            }
        });
    });
    
    // Close slideout
    function closeAllSlideouts() {
        slideoutPanels.forEach(panel => panel.classList.remove('open'));
        if (slideoutOverlay) {
            slideoutOverlay.classList.add('hidden');
            slideoutOverlay.classList.remove('opacity-100');
        }
    }
    
    slideoutCloseBtns.forEach(btn => {
        btn.addEventListener('click', closeAllSlideouts);
    });
    
    if (slideoutOverlay) {
        slideoutOverlay.addEventListener('click', closeAllSlideouts);
    }
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllSlideouts();
        }
    });
    
    // ==================== EXPANDABLE MENUS ====================
    const expandableMenus = document.querySelectorAll('[data-expandable]');
    
    expandableMenus.forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-expandable');
            const targetMenu = document.getElementById(targetId);
            
            if (targetMenu) {
                const isOpen = targetMenu.style.maxHeight && targetMenu.style.maxHeight !== '0px';
                
                if (isOpen) {
                    targetMenu.style.maxHeight = '0px';
                    this.querySelector('svg:last-child')?.classList.remove('rotate-90');
                } else {
                    targetMenu.style.maxHeight = targetMenu.scrollHeight + 'px';
                    this.querySelector('svg:last-child')?.classList.add('rotate-90');
                }
            }
        });
    });
});