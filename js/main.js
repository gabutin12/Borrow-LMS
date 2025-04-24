document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const wrapper = document.querySelector('.wrapper');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            wrapper.classList.toggle('sidebar-collapsed');
            
            // Add rotation animation to the hamburger icon
            this.style.transition = 'transform 0.3s ease';
            if (wrapper.classList.contains('sidebar-collapsed')) {
                this.style.transform = 'rotate(180deg)';
            } else {
                this.style.transform = 'rotate(0deg)';
            }
        });
    }
    
    // Save sidebar state in localStorage
    function saveSidebarState() {
        const isCollapsed = wrapper.classList.contains('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }
    
    // Restore sidebar state on page load
    function restoreSidebarState() {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            wrapper.classList.add('sidebar-collapsed');
            if (sidebarToggle) {
                sidebarToggle.style.transform = 'rotate(180deg)';
            }
        }
    }
    
    // Add event listener for saving state
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', saveSidebarState);
    }
    
    // Restore state on page load
    restoreSidebarState();
});
