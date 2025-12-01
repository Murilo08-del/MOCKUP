(function () {
    // Alterna a sidebar pelo botão
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('active');
    }

    document.addEventListener('DOMContentLoaded', function () {
        // associar alternador aos botões (quando existir)
        const toggles = document.querySelectorAll('.menu-toggle');
        toggles.forEach(btn => btn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleSidebar();
        }));

        // marcar link ativo
        const currentPage = window.location.pathname.split('/').pop();
        const links = document.querySelectorAll('.sidebar-menu a');
        links.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
            // fechar sidebar ao clicar no link (celular)
            link.addEventListener('click', function () {
                if (window.innerWidth <= 768) closeSidebar();
            });
        });

        // fechar ao clicar fora (celular)
        document.addEventListener('click', function (event) {
            const sidebar = document.getElementById('sidebar');
            const isToggle = event.target.closest('.menu-toggle');
            const isInsideSidebar = event.target.closest('.sidebar');
            if (window.innerWidth <= 768 && !isToggle && !isInsideSidebar) {
                closeSidebar();
            }
        });

        // fechar ao pressionar ESC
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') closeSidebar();
        });
    });
})();
