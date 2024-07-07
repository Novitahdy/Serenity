<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin_dashboard.php">
        <img src="../img/Logo.png" alt="Logo" style="width: 100px; height: auto;">
    </a>

    <hr class="sidebar-divider my-0" />
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="admin_dashboard.php">
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item collapsed <?php echo basename($_SERVER['PHP_SELF']) === 'soal_kecemasan.php' || basename($_SERVER['PHP_SELF']) === 'soal_depresi.php' || basename($_SERVER['PHP_SELF']) === 'soal_stres.php' ? 'active' : ''; ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAHP" aria-expanded="true" aria-controls="collapseAHP">
            <span>Data Gejala</span>
        </a>
        <div id="collapseAHP" class="collapse" aria-labelledby="headingAHP" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="soal_kecemasan.php">Soal Kecemasan (A)</a>
                <a class="collapse-item" href="soal_depresi.php">Soal Depresi (D)</a>
                <a class="collapse-item" href="soal_stres.php">Soal Stres (S)</a>
            </div>
        </div>
    </li>

    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'artikel.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="artikel.php">
            <span>Artikel Kesehatan Mental</span>
        </a>
    </li>

    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="laporan.php">
            <span>Riwayat Konsultasi Pengguna</span>
        </a>
    </li>

    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'pengguna.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="pengguna.php">
            <span>Data Pengguna</span>
        </a>
    </li>

    <hr class="sidebar-divider my-0" />

    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'admin_profile.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="admin_profile.php">
            <span>Profil Saya</span></a>
    </li>

    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'tentang.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="tentang.php">
            <span>Tentang</span></a>
    </li>

    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == '../include/logout.php' ? 'active' : ''; ?>">
        <a href="#" class="nav-link" data-toggle="modal" data-target="#logoutConfirmationModal">
            <span>Keluar</span>
        </a>
    </li>
    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="logoutConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="logoutConfirmationModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="../include/logout.php" class="btn btn-primary">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all the navigation links
        var navLinks = document.querySelectorAll('.nav-link');

        // Add click event listeners to each link
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                // Remove the "active" class from all links
                navLinks.forEach(function(navLink) {
                    navLink.classList.remove('active');
                });

                // Add the "active" class to the clicked link
                this.classList.add('active');
            });
        });
    });
</script>