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

if ($_SESSION['role'] == 'user') {
  header("Location: ../user/user_dashboard.php");
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

  <title>Dashboard Admin</title>

  <!-- Custom fonts for this template-->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.css" rel="stylesheet" />
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php
    require_once('../include/navbar_admin.php')
    ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <?php
        require_once('../include/topbar_admin.php')
        ?>
        <!-- End of Topbar -->
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Tentang</h2>
          <!-- Content Row -->
          <div class="card shadow mb-4">
            <div class="information" style="text-align:center">
              <img src="../img/Logo.png" alt="Logo" style="width: 250px; height: auto;">
            </div>
            <div class="card-body">
              <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary">
                  Informasi !
                </h3>
              </div>
              <div class="card-body">
                <p>SERENITY merupakan aplikasi yang dapat membantu kamu untuk mendeteksi kesehatan mental secara mandiri (tahap awal). Jika ingin mendalami lebih lanjut tentang kesehatan mental kamu dapat menghubungi psikolog/psikiater. Selain itu, kamu juga bisa mencari rumah sakit terdekat yang bisa membantu kamu dalam menangani masalah kesehatan mental. Selamat menggunakan aplikasi SERENITY!</p>
              </div>
            </div>
            <div class="card-body">
              <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary">
                  Referensi
                </h3>
              </div>
              <div class="card-body">
                <li>Referensi1</li>
                <li>Referensi2</li>
                <li>Referensi3</li>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
      require_once('../include/footer.php')
      ?>
    </div>
  </div>

  <!-- Scroll to Top Button-->
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
  <script src="../js/demo/chart-area-demo.js"></script>
  <script src="../js/demo/chart-pie-demo.js"></script>
</body>

</html>