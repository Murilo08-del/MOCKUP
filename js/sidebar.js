(function () {
    // Toggle sidebar via button
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('active');
    }

    document.addEventListener('DOMContentLoaded', function () {
        // attach toggle to buttons (if present)
        const toggles = document.querySelectorAll('.menu-toggle');
        toggles.forEach(btn => btn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleSidebar();
        }));

        // mark active link
        const currentPage = window.location.pathname.split('/').pop();
        const links = document.querySelectorAll('.sidebar-menu a');
        links.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
            // close when click link on mobile
            link.addEventListener('click', function () {
                if (window.innerWidth <= 768) closeSidebar();
            });
        });

        // close on click outside (mobile)
        document.addEventListener('click', function (event) {
            const sidebar = document.getElementById('sidebar');
            const isToggle = event.target.closest('.menu-toggle');
            const isInsideSidebar = event.target.closest('.sidebar');
            if (window.innerWidth <= 768 && !isToggle && !isInsideSidebar) {
                closeSidebar();
            }
        });

        // close on ESC
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') closeSidebar();
        });
    });
})();
