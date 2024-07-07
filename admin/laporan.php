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

// Include file koneksi ke database
require_once "../include/koneksi.php";

// Ambil informasi pengguna dari database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($koneksi, $query);
if (!$result) {
  // Error saat mengambil data dari database
  die("Query error: " . mysqli_error($koneksi));
}
$user = mysqli_fetch_assoc($result);

// Export to excel
if (isset($_POST['export'])) {
  // Nama file Excel yang akan dihasilkan
  $filename = "data_laporan_" . date('Ymd') . ".xls";

  // Header untuk menghasilkan file Excel
  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  // // Query untuk mengambil data pengguna dari database
  // $query = "SELECT * FROM consultation_results WHERE user_id = $user_id ORDER BY timestamp DESC";
  // $result = mysqli_query($koneksi, $query);

  // // Mulai output buffer agar hasil query tidak langsung ditampilkan
  // ob_start();

  // // Mulai tabel Excel
  // echo "<table border='1'>";
  // echo "<tr><th>Nama</th><th>Hasil</th><th>Tanggal Tes</th></tr>";

  // $counter = 1; // Inisialisasi counter
  // // Tampilkan data pengguna ke dalam tabel Excel
  // while ($row = mysqli_fetch_assoc($result)) {
  //   echo "<tr>";
  //   echo "<td>" . $counter . "</td>";
  //   echo "<td>" . $row['Namalengkap'] . "</td>";
  //   echo "<td>" . $row['hasil'] . "</td>";
  //   echo "<td>" . $row['nilai'] . "</td>";
  //   echo "</tr>";
  //   $counter++; // Tingkatkan counter setelah setiap baris
  // }
  // echo "</table>";
  // // Flush output buffer agar hasil query ditampilkan dalam file Excel
  // ob_end_flush();
  // exit;
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

  <title>Dashboard Admin</title>

  <!-- Custom fonts for this template-->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.css" rel="stylesheet">
  <!-- Custom styles for this page -->
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
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
          <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Riwayat Konsultasi Pengguna</h2>
          <?php
          // Check if the 'success' parameter exists in the URL
          if (isset($_GET['success'])) {
            // Check the value of the 'success' parameter
            if ($_GET['success'] === 'delete') {
              // If the value is 'delete', display a success message
              echo '<div class="alert alert-success" role="alert">Riwayat berhasil dihapus.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
            }
          }
          ?>
          <!-- <form method="post" action="laporan.php">
            <button type="submit" name="export" class="btn btn-success mb-3"><i class="fa-regular fa-file-excel mr-3"></i>Cetak Data Excel</button>
          </form> -->
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Riwayat Konsultasi Pengguna</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Hasil</th>
                      <th>Tanggal Tes</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    require_once "../include/koneksi.php";
                    // Query untuk mengambil data pengguna dari database
                    $query = "SELECT * FROM consultation_results ORDER BY timestamp DESC";
                    $result = mysqli_query($koneksi, $query);

                    if ($result) {
                      $counter = 1; // Inisialisasi counter
                      while ($row = mysqli_fetch_assoc($result)) {
                        $formatted_timestamp = date('d F Y', strtotime($row['timestamp']));

                        echo "<tr>";
                        echo "<td>" . $counter . "</td>";
                        echo "<td>" . $row['result_category'] . "</td>";
                        echo "<td>" . $formatted_timestamp . "</td>";
                        echo "<td style='text-align: center;'>";
                        echo "<a href='perhitungan.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Detail</a>";
                        echo "&nbsp;";
                        echo "<a href='../hapus_laporan.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                        $counter++; // Tingkatkan counter setelah setiap baris
                      }
                      // Free result set
                      mysqli_free_result($result);
                    } else {
                      // Jika query gagal dijalankan
                      echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
                    }
                    // Tutup koneksi ke database
                    mysqli_close($koneksi);
                    ?>
                  </tbody>
                </table>
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
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="../js/demo/datatables-demo.js"></script>
</body>

</html>