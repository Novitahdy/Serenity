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
  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php
    require_once('../include/navbar_user.php')
    ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <?php
        require_once('../include/topbar_user.php')
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
                <h5 class="m-0 font-weight-bold text-primary">
                  <center>SERENITY merupakan aplikasi yang dapat membantu kamu untuk mendeteksi kesehatan mental secara mandiri (tahap awal). Jika ingin mendalami lebih lanjut tentang kesehatan mental kamu dapat menghubungi psikolog/psikiater. Selain itu, kamu juga bisa mencari rumah sakit terdekat yang bisa membantu kamu dalam menangani masalah kesehatan mental.</center>
                </h5>
              </div>
            </div>

            <div class="card-body">
              <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary">
                  Faktor yang Mempengaruhi Kesehatan Mental
                </h4>
            </div>
            <div class="card-body">
              <ol>
                <li><b>Faktor Internal:</b>
                  <ul>
                    <li><b>Faktor Biologis: </b>Faktor biologis berkaitan dengan keturunan atau genetik serta kesehatan fisik seseorang.</li>
                    <li><b>Faktor Psikologis: </b>Faktor psikologis berkaitan dengan konflik, stres yang dihadapi, pengalaman masa lalu, dan kecemasan terhadap masa depan.</li>
                  </ul>
                </li>
                <li><b>Faktor Eksternal:</b>
                <ul>
                  <li><b>Peran Keluarga: </b>Keluarga berperan untuk memberikan dukungan, bantuan, dan kasih sayang kepada seseorang, sehingga keluarga merupakan faktor protektif terjadinya gangguan kesehatan mental bagi seseorang.</li>
                  <li><b>Lingkungan dan Hubungan Sosial: </b>Dukungan emosional yang diberikan oleh lingkungan sekitarnya dapat membangun perasaan seseorang menjadi lebih baik. Serta hubungan sosial yang tidak baik cenderung menyebabkan masalah-masalah psikologis.</li>
                </ul>
                </li>
              </ol>
            </div>

            <div class="card-body">
              <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary">
                  Dampak Negatif dari Gangguan Kesehatan Mental
                </h4>
            </div>
            <div class="card-body">
              <ol>
                <li><b>Emosional: </b>Merasa gelisah, cemas, depresi, sedih, perasaan diri kurang berharga, mudah marah, berperilaku impulsif, mudah panik, dan perasaan tidak mampu menyelesaikan pekerjaan.</li>
                <li><b>Fisik: </b>Merasa sakit kepala, pusing, kesulitan tidur dan jadwal tidur yang tidak teratur, pegal pada punggung, diare, selalu kelelahan, tidak memiliki motivasi untuk belajar, detak jantung yang lebih cepat, perubahan pola makan, sering membuang air kecil, dan kesulitan untuk menelan.</li>
                <li><b>Perilaku: </b>Sering mengerutkan dahi, sering bertindak agresif, kecenderungan untuk menyendiri, bersifat ceroboh, mudah menyalakan orang lain, sering melamun, tertawa bernada tinggi dengan perasaan gelisah, berjalan tanpa arah, dan adanya perubahan dalam perilaku sosial orang tersebut.</li>
              </ol>
            </div>
            
            <div class="card-body">
              <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary">
                  Referensi
                </h4>
              </div>
              <div class="card-body">
                <li>Alalalmeh, S. O., Hegazi, O. E., Shahwan, M., Hassan, N., Humaid Alnuaimi, G. R., Alaila, R. F., Jairoun, A., Tariq Hamdi, Y., Abdullah, M. T., Abdullah, R. M., & Zyoud, S. H. (2024). Assessing Mental Health among Students in the UAE: A Cross Sectional Study Utilizing the DASS-21 Scale. Saudi Pharmaceutical Journal, 101987. <a href="https://doi.org/10.1016/j.jsps.2024.101987">https://doi.org/10.1016/j.jsps.2024.101987</a></li>
                <li>Fakhriyani, D. V. (2019). KESEHATAN MENTAL (Dr. M. Thoha, Ed.). Duta Media Publishing. <a href="https://www.researchgate.net/publication/348819060">https://www.researchgate.net/publication/348819060</a></li>
                <li>Manurung, V., Christianto, & Citrayani, R. (2023). TINGKAT STRES PADA ANAK DALAM PEMBELAJARAN JARAK JAUH DI MASA PANDEMI COVID-19. Open Journal System, 17(10), 2485–2494. <a href="https://binapatria.id/index.php/MBI">https://binapatria.id/index.php/MBI</a></li>
                <li>Priya, A., Garg, S., & Tigga, N. P. (2020). Predicting Anxiety, Depression and Stress in Modern Life using Machine Learning Algorithms. Procedia Computer Science, 167, 1258–1267. <a href="https://doi.org/10.1016/j.procs.2020.03.442">https://doi.org/10.1016/j.procs.2020.03.442</a></li>
                <li>Rizkiah, A., Risanty, R. D., & Mujiastuti, R. (2020). SISTEM PENDETEKSI DINI KESEHATAN MENTAL EMOSIONAL ANAK USIA 4-17 TAHUN MENGGUNAKAN METODE FORWARD CHAINING. JUST IT : Jurnal Sistem Informasi, Teknologi Informasi Dan Komputer, 10(2), 83–93. <a href="https://jurnal.umj.ac.id/index.php/justit">https://jurnal.umj.ac.id/index.php/justit</a></li>
                <li>Zanon, C., Brenner, R. E., Baptista, M. N., Vogel, D. L., Rubin, M., Al-Darmaki, F. R., Gonçalves, M., Heath, P. J., Liao, H.-Y., Mackenzie, C. S., Topkaya, N., Wade, N. G., & Zlati, A. (2021). Examining the Dimensionality, Reliability, and Invariance of the Depression, Anxiety, and Stress Scale–21 (DASS-21) Across Eight Countries. Assessment, 28(6), 1531–1544. <a href="https://doi.org/10.1177/1073191119887449">https://doi.org/10.1177/1073191119887449</a></li>
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