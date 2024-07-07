<?php
require_once "../include/koneksi.php";

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_soal'])) {
    $id_soal = $_POST['id_soal'];
    $kode = $_POST['kode'];
    $questionText = $_POST['questionText'];
    $nilaiA = $_POST['nilaiA'];
    $nilaiB = $_POST['nilaiB'];
    $nilaiC = $_POST['nilaiC'];
    $nilaiD = $_POST['nilaiD'];

    // Pastikan semua nilai diterima dengan benar
    if (!empty($id_soal) && !empty($questionText) && !empty($kode) && isset($nilaiA) && isset($nilaiB) && isset($nilaiC) && isset($nilaiD)) {
        // Update the question data in the database
        $query = "UPDATE questions_a SET kode = ?, question_text = ?, nilai_a = ?, nilai_b = ?, nilai_c = ?, nilai_d = ? WHERE id_soal = ?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ssiiiii", $kode, $questionText, $nilaiA, $nilaiB, $nilaiC, $nilaiD, $id_soal);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: soal_kecemasan.php?success=update");
            exit();
        } else {
            die("Query error: " . mysqli_error($koneksi));
        }
    } else {
        die("Error: Pastikan semua field diisi dengan benar.");
    }
}

require_once "../include/koneksi.php";

if (isset($_GET['id'])) {
    $id_soal = $_GET['id'];

    // Query untuk mengambil data soal berdasarkan id
    $query = "SELECT * FROM questions_a WHERE id_soal = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_soal);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Gejala tidak ditemukan!";
        exit();
    }
} else {
    header("Location: soal_kecemasan.php");
    exit();
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

<body>
    <div id="wrapper">
        <?php require_once('../include/navbar_admin.php') ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php
                require_once('../include/topbar_admin.php')
                ?>
                <div class="container-fluid">
                    <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Edit Gejala Kecemasan</h2>
                    <a href="soal_kecemasan.php" class="btn btn-primary mt-3 mb-4"><i class="fa-solid fa-angle-left mr-2"></i> Kembali </a>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form method="post" action="edit_soal_kecemasan.php">
                                <input type="hidden" name="id_soal" value="<?php echo $row['id_soal']; ?>">
                                <div class="form-group">
                                    <label for="kode">Kode Gejala</label>
                                    <input type="text" class="form-control" id="kode" name="kode" value="<?php echo $row['kode']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="questionText">Gejala</label>
                                    <textarea class="form-control" id="questionText" name="questionText"><?php echo $row['question_text']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="nilaiA">Nilai Opsi Tidak Pernah</label>
                                    <input type="number" class="form-control" id="nilaiA" name="nilaiA" value="<?php echo $row['nilai_a']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="nilaiB">Nilai Opsi Kadang-kadang</label>
                                    <input type="number" class="form-control" id="nilaiB" name="nilaiB" value="<?php echo $row['nilai_b']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="nilaiC">Nilai Opsi Sering</label>
                                    <input type="number" class="form-control" id="nilaiC" name="nilaiC" value="<?php echo $row['nilai_c']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="nilaiD">Nilai Opsi Sangat Sering</label>
                                    <input type="number" class="form-control" id="nilaiD" name="nilaiD" value="<?php echo $row['nilai_d']; ?>" required>
                                </div>
                                <!-- <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-weight-bold" id="confirmModalLabel">Konfirmasi</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin memperbarui Gejala?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" id="confirmButton">Perbarui</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once('../include/footer.php') ?>
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
    <!-- <script>
        // Function to handle form submission confirmation
        function confirmSubmission(event) {
            event.preventDefault(); // Prevent the default form submission
            $('#confirmModal').modal('show'); // Show the confirmation modal
        }

        // Add event listener to the form submit button
        document.querySelector('form').addEventListener('submit', confirmSubmission);

        // Add event listener to the modal confirm button
        document.getElementById('confirmButton').addEventListener('click', function() {
            // If the user confirms, submit the form
            document.querySelector('form').submit();
        });
    </script> -->
</body>

</html>