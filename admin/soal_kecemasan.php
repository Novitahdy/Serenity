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

require_once "../include/koneksi.php";
$retrieveQuery = "SELECT * FROM `questions_a`";
$retrieveResult = mysqli_query($koneksi, $retrieveQuery);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $kode = $_POST['kode'];
    $questionText = $_POST['questionText'];
    $nilaiA = $_POST['nilaiA'];
    $nilaiB = $_POST['nilaiB'];
    $nilaiC = $_POST['nilaiC'];
    $nilaiD = $_POST['nilaiD'];

    $insertQuery = "INSERT INTO questions_a (kode, nilai_a, nilai_b, nilai_c, nilai_d, question_text) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare(
        $koneksi,
        $insertQuery
    );
    mysqli_stmt_bind_param($stmt, "sdddds", $kode, $nilaiA, $nilaiB, $nilaiC, $nilaiD, $questionText);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo '<script>alert("Data berhasil ditambahkan.");</script>';
    } else {
        echo "Error creating gejala: " . mysqli_error($koneksi);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
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
    <link href="../css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php require_once('../include/navbar_admin.php') ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php
                require_once('../include/topbar_admin.php')
                ?>
                <div class="container-fluid">
                    <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Data Soal Kecemasan</h2>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQuestionModal">
                            <i class="fa-solid fa-plus mr-2"></i> Tambah Data
                        </button>
                    </div>

                    <div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addQuestionModalLabel">Tambah Gejala Baru</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="soal_kecemasan.php">
                                        <div class="form-group">
                                            <label for="kode">Kode</label>
                                            <input type="text" class="form-control" id="kode" name="kode" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="questionText">Gejala</label>
                                            <textarea class="form-control" id="questionText" name="questionText" rows="3" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="nilaiA">Nilai Opsi Tidak Pernah</label>
                                            <input type="number" class="form-control" id="nilaiA" name="nilaiA" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nilaiB">Nilai Opsi Kadang-kadang</label>
                                            <input type="number" class="form-control" id="nilaiB" name="nilaiB" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nilaiC">Nilai Opsi Sering</label>
                                            <input type="number" class="form-control" id="nilaiC" name="nilaiC" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nilaiD">Nilai Opsi Sangat Sering</label>
                                            <input type="number" class="form-control" id="nilaiD" name="nilaiD" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Tambah Gejala</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Display existing questions -->
                    <?php
                    require_once "../include/koneksi.php";
                    // Check if the 'success' parameter exists in the URL
                    if (isset($_GET['success'])) {
                        // Check the value of the 'success' parameter
                        if ($_GET['success'] === 'delete') {
                            // If the value is 'delete', display a success message
                            echo '<div class="alert alert-success" role="alert">
                            Gejala berhasil dihapus.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                        }
                    }
                    // Check if the 'success' parameter exists in the URL
                    if (isset($_GET['success'])) {
                        // Check the value of the 'success' parameter
                        if ($_GET['success'] === 'update') {
                            // If the value is 'update', display a success message
                            echo '<div class="alert alert-success" role="alert">
                            Gejala berhasil diperbarui.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button></div>';
                        }
                    }
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
                        echo "<th>No</th>";
                        echo "<th>Kode Gejala</th>";
                        echo "<th>Gejala</th>";
                        echo "<th>Nilai Opsi Tidak Pernah</th>";
                        echo "<th>Nilai Opsi Kadang-kadang</th>";
                        echo "<th>Nilai Opsi Sering</th>";
                        echo "<th>Nilai Opsi Sangat Sering</th>";
                        echo "<th>Aksi</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        $counter = 1; // Inisialisasi counter
                        // Loop through the result set and display the articles
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $counter . "</td>";
                            echo "<td>" . $row['kode'] . "</td>";
                            echo "<td>" . $row['question_text'] . "</td>";
                            echo "<td>" . $row['nilai_a'] . "</td>";
                            echo "<td>" . $row['nilai_b'] . "</td>";
                            echo "<td>" . $row['nilai_c'] . "</td>";
                            echo "<td>" . $row['nilai_d'] . "</td>";
                            echo "<td style='text-align: center'>";
                            echo "<a href='edit_soal_kecemasan.php?id=" . $row['id_soal'] . "' class='btn btn-warning btn-sm'>Edit</a>";
                            echo "&nbsp;";
                            echo "<a href='../hapus_soal_kecemasan.php?id=" . $row['id_soal'] . "' class='btn btn-danger btn-sm'>Hapus</a>";
                            echo "</td>";
                            echo "</tr>";
                            $counter++; // Tingkatkan counter setelah setiap baris
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>"; // This closes the div for the DataTables Example

                        // Free the result set
                        mysqli_free_result($result);
                    } else {
                        // If the query was not successful, display an error message
                        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
                    }
                    // Close the database connection
                    mysqli_close($koneksi);
                    ?>
                </div>
                <?php require_once('../include/footer.php') ?>
            </div>
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