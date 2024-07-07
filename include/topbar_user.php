<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <?php
            // Mendapatkan path gambar profil
            $profileImage = !empty($user['profile_image']) ? $user['profile_image'] : '../img/circle-user-solid.svg';
            ?>
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="<?php echo $profileImage; ?>" />
                <span class="ml-2 d-none d-lg-inline text-dark"><?php echo $user['Namalengkap']; ?></span>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="../user/profile.php">
                    Profil
                </a>
                <a href="../include/logout.php" class="dropdown-item" data-toggle="modal" data-target="#logoutConfirmationModal">
                    <span>Keluar</span>
                </a>
            </div>
        </li>
    </ul>
</nav>