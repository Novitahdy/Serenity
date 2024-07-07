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

// Handling
if ($_SESSION['role'] == 'user') {
  header("Location: ../user/user_dashboard.php");
  exit();
}
// Lakukan koneksi ke database
require_once '../include/koneksi.php';

// Query untuk mengambil jumlah pengguna dari database (misalnya dari tabel 'users')
$query = "SELECT COUNT(*) AS total_pengguna FROM users";
$result = mysqli_query($koneksi, $query);

if ($result) {
  // Jika query berhasil, ambil hasilnya
  $row = mysqli_fetch_assoc($result);
  $total_pengguna = $row['total_pengguna'];
} else {
  // Jika query gagal, atur jumlah pengguna menjadi 0 atau tampilkan pesan kesalahan
  $total_pengguna = 0;
  echo "Error: " . mysqli_error($koneksi);
}

// Query untuk mengambil jumlah soal dari tabel questions_a
$query_a = "SELECT COUNT(*) AS total_soal_a FROM questions_a";
$result_a = mysqli_query($koneksi, $query_a);
if ($result_a) {
  $row_a = mysqli_fetch_assoc($result_a);
  $total_soal_a = $row_a['total_soal_a'];
} else {
  $total_soal_a = 0;
  echo "Error: " . mysqli_error($koneksi);
}

// Query untuk mengambil jumlah soal dari tabel questions_d
$query_d = "SELECT COUNT(*) AS total_soal_d FROM questions_d";
$result_d = mysqli_query($koneksi, $query_d);
if ($result_d) {
  $row_d = mysqli_fetch_assoc($result_d);
  $total_soal_d = $row_d['total_soal_d'];
} else {
  $total_soal_d = 0;
  echo "Error: " . mysqli_error($koneksi);
}

// Query untuk mengambil jumlah soal dari tabel questions_s
$query_s = "SELECT COUNT(*) AS total_soal_s FROM questions_s";
$result_s = mysqli_query($koneksi, $query_s);
if ($result_s) {
  $row_s = mysqli_fetch_assoc($result_s);
  $total_soal_s = $row_s['total_soal_s'];
} else {
  $total_soal_s = 0;
  echo "Error: " . mysqli_error($koneksi);
}

// Jumlah total soal dari semua tabel
$total_soal = $total_soal_a + $total_soal_d + $total_soal_s;

// Query untuk mengambil jumlah artikel dari database 
$query = "SELECT COUNT(*) AS total_artikel FROM articles";
$result = mysqli_query($koneksi, $query);

if ($result) {
  // Jika query berhasil, ambil hasilnya
  $row = mysqli_fetch_assoc($result);
  $total_artikel = $row['total_artikel'];
} else {
  // Jika query gagal, atur jumlah artikel menjadi 0 atau tampilkan pesan kesalahan
  $total_artikel = 0;
  echo "Error: " . mysqli_error($koneksi);
}

//Query untuk mengambil jumlah konsultasi dari database
$query = "SELECT COUNT(*) AS total_konsultasi FROM consultation_results";
$result = mysqli_query($koneksi, $query);

if ($result) {
  // Jika query berhasil, ambil hasilnya
  $row = mysqli_fetch_assoc($result);
  $total_konsultasi = $row['total_konsultasi'];
} else {
  // Jika query gagal, atur jumlah konsultasi menjadi 0 atau tampilkan pesan kesalahan
  $total_konsultasi = 0;
  echo "Error: " . mysqli_error($koneksi);
}

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
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php
        require_once('../include/topbar_admin.php')
        ?>
        <div class="container-fluid">
          <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Dashboard</h2>
          <div class="row">
            <!-- Gejala -->
            <div class="col-xl-6 col-md-6 mb-4 mt-2">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Jumlah Gejala
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo number_format($total_soal); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-fw fa-chart-area fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pengguna -->
            <div class="col-xl-6 col-md-6 mb-4 mt-2">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Jumlah Pengguna
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo number_format($total_pengguna); ?> <!-- Tampilkan jumlah pengguna dengan format ribuan -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i> <!-- Ganti ikon dengan ikon pengguna -->
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!--- Konsultasi --->
            <div class="col-xl-6 col-md-6 mb-4 mt-2">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Jumlah Konsultasi
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo number_format($total_konsultasi); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!--- Artikel --->
            <div class="col-xl-6 col-md-6 mb-4 mt-2">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Jumlah Artikel
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo number_format($total_artikel); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-fw fa-chart-area fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Data Soal Kecemasan</h2>
          <div class="d-flex justify-content-end mb-3">
            <a type="button" class="btn btn-primary" href="soal_kecemasan.php">Lihat lebih detail
            </a>
          </div>
          <!-- Display existing questions -->
          <?php
          $query = "SELECT questions_a.id_soal, questions_a.kode, questions_a.question_text, questions_a.nilai_a, questions_a.nilai_b, questions_a.nilai_c, questions_a.nilai_d FROM questions_a";
          $result = mysqli_query($koneksi, $query);
          // Check if the query was successful
          if ($result) {
            // Display the table headers
            echo "<div class='card shadow mb-4'>";
            echo "<div class='card-header py-3'>";
            echo "<h6 class='m-0 font-weight-bold text-primary'>";
            echo "Data Gejala";
            echo "</h6>";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Kode</th>";
            echo "<th>Nama Gejala</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            // Loop through the result set and display each row
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $row['kode'] . "</td>";
              echo "<td>" . $row['question_text'] . "</td>";
              echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
          } else {
            // Error saat mengambil data dari database
            die("Query error: " . mysqli_error($koneksi));
          }
          ?>

          <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Data Soal Depresi</h2>
          <div class="d-flex justify-content-end mb-3">
            <a type="button" class="btn btn-primary" href="soal_depresi.php">Lihat lebih detail
            </a>
          </div>
          <!-- Display existing questions -->
          <?php
          $query = "SELECT questions_d.id_soal, questions_d.kode, questions_d.question_text, questions_d.nilai_a, questions_d.nilai_b, questions_d.nilai_c, questions_d.nilai_d FROM questions_d";
          $result = mysqli_query($koneksi, $query);
          // Check if the query was successful
          if ($result) {
            // Display the table headers
            echo "<div class='card shadow mb-4'>";
            echo "<div class='card-header py-3'>";
            echo "<h6 class='m-0 font-weight-bold text-primary'>";
            echo "Data Gejala";
            echo "</h6>";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Kode</th>";
            echo "<th>Nama Gejala</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            // Loop through the result set and display each row
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $row['kode'] . "</td>";
              echo "<td>" . $row['question_text'] . "</td>";
              echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
          } else {
            // Error saat mengambil data dari database
            die("Query error: " . mysqli_error($koneksi));
          }
          ?>

          <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Data Soal Stres</h2>
          <div class="d-flex justify-content-end mb-3">
            <a type="button" class="btn btn-primary" href="soal_stres.php">Lihat lebih detail
            </a>
          </div>
          <!-- Display existing questions -->
          <?php
          $query = "SELECT questions_s.id_soal, questions_s.kode, questions_s.question_text, questions_s.nilai_a, questions_s.nilai_b, questions_s.nilai_c, questions_s.nilai_d FROM questions_s";
          $result = mysqli_query($koneksi, $query);
          // Check if the query was successful
          if ($result) {
            // Display the table headers
            echo "<div class='card shadow mb-4'>";
            echo "<div class='card-header py-3'>";
            echo "<h6 class='m-0 font-weight-bold text-primary'>";
            echo "Data Gejala";
            echo "</h6>";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Kode</th>";
            echo "<th>Nama Gejala</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            // Loop through the result set and display each row
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $row['kode'] . "</td>";
              echo "<td>" . $row['question_text'] . "</td>";
              echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
          } else {
            // Error saat mengambil data dari database
            die("Query error: " . mysqli_error($koneksi));
          }
          ?>

          <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Data Artikel</h2>
          <div class="d-flex justify-content-end mb-3">
            <a type="button" class="btn btn-primary" href="artikel.php">Lihat lebih detail
            </a>
          </div>
          <!-- Display existing articles -->
          <?php
          $query = "SELECT id, title, content FROM articles";
          $result = mysqli_query($koneksi, $query);

          // Query to retrieve articles from the database
          $query = "SELECT * FROM articles";
          $result = mysqli_query($koneksi, $query);
          // Check if the query was successful
          if ($result) {
            // Display the table headers
            echo "<div class='card shadow mb-4'>";
            echo "<div class='card-header py-3'>";
            echo "<h6 class='m-0 font-weight-bold text-primary'>";
            echo "Data Artikel";
            echo "</h6>";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>No</th>";
            echo "<th>Diperbarui</th>";
            echo "<th width='10%'>Judul</th>";
            echo "<th>Gambar</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $counter = 1; // Inisialisasi counter
            // Loop through the result set and display the articles
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $counter . "</td>";
              echo "<td>" . $row['updated_at'] . "</td>";
              echo "<td>" . $row['title'] . "</td>";
              echo "<td><img src='" . $row['image_path'] . "' alt='Article Image' style='max-width: 100px; max-height: 100px;'></td>";
              echo "</tr>";
              $counter++; // Tingkatkan counter setelah setiap baris
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>"; // This closes the div for the DataTables Example

          } else {
            die("Query error: " . mysqli_error($koneksi));
          }
          ?>
        </div>
      </div>
    </div>
    <?php
    require_once('../include/footer.php')
    ?>

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
    <!-- <script src="../js/demo/chart-area-demo.js"></script> -->

</body>

</html>