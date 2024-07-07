<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="user_dashboard.php">
        <img src="../img/Logo.png" alt="Logo" style="width: 100px; height: auto;">
    </a>

    <hr class="sidebar-divider my-0" />
    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'user_dashboard.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="user_dashboard.php">
            <span>Dashboard</span></a>
    </li>

    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="riwayat.php">
            <span>Riwayat Konsultasi</span></a>
    </li>
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'baca_artikel.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="baca_artikel.php">
            <span>Artikel Kesehatan Mental</span></a>
    </li>

    <hr class="sidebar-divider my-0" />

    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="profile.php">
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

<!-- JavaScript with jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>