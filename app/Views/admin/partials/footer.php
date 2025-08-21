</main> </div> </div> <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sol Menü Dropdown'ları
        var sidebarDropdowns = document.querySelectorAll('.sidebar .menu-item-has-children > a');
        sidebarDropdowns.forEach(function(dropdown) {
            dropdown.addEventListener('click', function(event) {
                event.preventDefault();
                var parentLi = this.parentElement;

                var openMenus = document.querySelectorAll('.sidebar .menu-item-has-children.open');
                openMenus.forEach(function(menu) {
                    if (menu !== parentLi) {
                        menu.classList.remove('open');
                    }
                });

                parentLi.classList.toggle('open');
            });
        });

        // Üst Menü Dropdown'ları
        var headerDropdowns = document.querySelectorAll('.header-dropdown');
        headerDropdowns.forEach(function(dropdown) {
            var button = dropdown.querySelector('.header-dropdown-button');
            button.addEventListener('click', function(event) {
                event.stopPropagation();
                
                var wasOpen = dropdown.classList.contains('open');

                // Önce tüm üst menüleri kapat
                document.querySelectorAll('.header-dropdown').forEach(function(d) {
                    d.classList.remove('open');
                });

                if (!wasOpen) {
                    dropdown.classList.add('open');
                }
            });
        });

        // Dışarıya tıklandığında üst menüleri kapat
        window.addEventListener('click', function(event) {
            document.querySelectorAll('.header-dropdown').forEach(function(d) {
                d.classList.remove('open');
            });
        });
    });
</script>

</body>
</html>