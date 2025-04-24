document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const wrapper = document.querySelector('.wrapper');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            wrapper.classList.toggle('sidebar-collapsed');
            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', wrapper.classList.contains('sidebar-collapsed'));
        });
    }
    
    // Restore sidebar state on page load
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        wrapper.classList.add('sidebar-collapsed');
    }

    // Handle responsive sidebar for mobile devices
    function handleResponsiveSidebar() {
        if (window.innerWidth <= 768) {
            wrapper.classList.add('sidebar-collapsed');
        } else {
            if (localStorage.getItem('sidebarCollapsed') !== 'true') {
                wrapper.classList.remove('sidebar-collapsed');
            }
        }
    }

    // Initial check and listen for window resize
    handleResponsiveSidebar();
    window.addEventListener('resize', handleResponsiveSidebar);
});