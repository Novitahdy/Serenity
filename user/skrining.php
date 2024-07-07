<?php
session_start();

// Set session timeout in seconds (e.g., 30 minutes)
$session_timeout = 1800; // 30 minutes * 60 seconds

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Check the time of the last activity
    if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $session_timeout) {
        // Session has expired, destroy the session and redirect to the login page
        session_unset();
        session_destroy();
        header("Location: ../login.php");
        exit();
    } else {
        // Update the last activity time
        $_SESSION['last_activity'] = time();
    }
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] == 'admin') {
    header("Location: ../admin/admin_dashboard.php");
    exit();
}

// Lakukan koneksi ke database
require_once '../include/koneksi.php';

// Ambil informasi pengguna dari database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($koneksi, $query);
if (!$result) {
    // Error saat mengambil data dari database
    die("Query error: " . mysqli_error($koneksi));
}
$user = mysqli_fetch_assoc($result);

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agree'])) {
    // Set session persetujuan ke true
    $_SESSION['agreement'] = true;
    // Redirect ke halaman mulai_skrining.php
    header("Location: mulai_skrining.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="../favicon.ico" type="image/x-icon">

    <title>Dashboard User</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet" />
</head>

<body id="page-top">
    <div id="wrapper">
        <?php
        require_once('../include/navbar_user.php')
        ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php
                require_once('../include/topbar_user.php')
                ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Skrining</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="mr-2 fas fa-info fa-1x text-gray-500"></i>
                                Informasi
                            </h6>
                        </div>
                        <div class="information" style="text-align:center; padding: 20px;">
                            <h1 class="h3 text-gray-800 font-weight-bold">Ketentuan Penggunaan</h1>
                            <ol style="text-align: left; margin-top:20px; margin-left: 20px;">
                                <li>Anda akan diminta untuk menjawab beberapa pertanyaan yang berkaitan dengan kesehatan mental.</li>
                                <li>Hasil skrining ini hanya sebagai tahap awal dan bukan sebagai diagnosa resmi.</li>
                                <li>Hasil skrining ini akan digunakan untuk penelitian, yang bertujuan untuk memahami lebih dalam tentang kesehatan mental di lingkungan Program Studi Manajemen Informatika Universitas Negeri Surabaya.</li>
                                <li>Hasil skrining ini akan digunakan untuk kebaikan bersama, baik bagi yang mengisi skrining maupun bagi peneliti yang melakukan penelitian.</li>
                                <li>Aplikasi ini dapat digunakan oleh mahasiswa yang berada di lingkungan Program Studi Manajemen Informatika Universitas Negeri Surabaya.</li>
                                <div style="margin-top: 20px;">
                                    <input type="checkbox" id="agreement-checkbox">
                                    <label for="agreement-checkbox" class="text-gray-800 font-weight-bold">Saya setuju dengan ketentuan di atas</label>
                                    <br>
                                    <form method="post">
                                        <button type="submit" name="agree" id="submit-button" class="btn btn-primary" disabled>Mulai Skrining</button>
                                    </form>
                                </div>
                            </ol>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header text-center py-3">
                            <h1 class="h3 text-gray-800 font-weight-bold">Penjelasan</h1>
                        </div>
                        <div class="card-body">
                            <h5 class="text-gray-800">Gejala Kognitif</h5>
                            <p class="card-text">Kesulitan fokus, lupa, kebingungan, dan masalah dalam pengambilan keputusan.</p>
                            <h5 class="text-gray-800">Gejala Depresi</h5>
                            <p class="card-text">Perasaan sedih, kehilangan minat, perubahan nafsu makan, gangguan tidur, dan kelelahan.</p>
                            <h5 class="text-gray-800">Gejala Somatik</h5>
                            <p class="card-text">Keluhan fisik tanpa sebab medis jelas seperti sakit kepala, nyeri, dan gangguan pencernaan.</p>
                            <h5 class="text-gray-800">Gejala Penurunan Energi</h5>
                            <p class="card-text">Kelelahan berlebihan dan kurangnya energi untuk aktivitas sehari-hari.</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            require_once('../include/footer.php')
            ?>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="..js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

    <script>
        document.getElementById('agreement-checkbox').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('submit-button').disabled = false;
                document.getElementById('submit-button').onclick = function() {
                    window.location.href = 'mulai_skrining.php';
                };
            } else {
                document.getElementById('submit-button').disabled = true;
                document.getElementById('submit-button').onclick = null;
            }
        });
    </script>

</body>

</html>