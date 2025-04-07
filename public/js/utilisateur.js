
        // Toggle sidebar functionality for responsive design
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    sidebar.classList.remove('active');
                }
            }
        });
        
        // Show mobile actions if screen width is small
        const updateMobileLayout = () => {
            const mobileActions = document.querySelector('.mobile-actions');
            const actionButtons = document.querySelector('.action-buttons');
            
            if (window.innerWidth <= 768) {
                mobileActions.style.display = 'block';
                actionButtons.style.display = 'none';
            } else {
                mobileActions.style.display = 'none';
                actionButtons.style.display = 'flex';
            }
        };
        
        // Run on page load and resize
        updateMobileLayout();
        window.addEventListener('resize', updateMobileLayout);
