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

// Periksa apakah parameter id telah diterima dari URL
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Query untuk mengambil data artikel berdasarkan id
    $query = "SELECT * FROM articles WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $article_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $article = mysqli_fetch_assoc($result);
    } else {
        echo "Article not found.";
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    // Jika parameter id tidak ditemukan, redirect ke halaman lain atau tampilkan pesan error
    header("Location: artikel.php");
    exit();
}

// Tangani permintaan perubahan artikel
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari formulir
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    // Periksa apakah file gambar baru diunggah
    if ($_FILES['image']['size'] > 0 && $_FILES['image']['size'] <= 2097152) { // 2MB = 2 * 1024 * 1024 bytes
        $image_name = $_FILES['image']['name'];
        $image_temp = $_FILES['image']['tmp_name'];
        $image_path = "../uploads/" . $image_name;

        // Pindahkan file gambar ke folder uploads
        move_uploaded_file($image_temp, $image_path);

        // Update artikel beserta gambar baru
        $updateQuery = "UPDATE articles SET title = ?, category = ?, content = ?, image_path = ? WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ssssi", $title, $category, $content, $image_path, $article_id);
    } else {
        // Jika tidak ada gambar baru diunggah, update artikel tanpa mengubah gambar
        $updateQuery = "UPDATE articles SET title = ?, category = ?, content = ? WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $updateQuery);
        mysqli_stmt_bind_param($stmt, "sssi", $title, $category, $content, $article_id);
    }

    // Eksekusi query untuk memperbarui artikel dalam database
    $updateResult = mysqli_stmt_execute($stmt);

    // Periksa apakah query berhasil
    if ($updateResult) {
        // Redirect kembali ke halaman artikel dengan pesan sukses
        header("Location: artikel.php?id=" . $article_id . "&success=1");
        exit();
    } else {
        // Jika query gagal, tampilkan pesan error
        echo "Error updating record: " . mysqli_error($koneksi);
    }
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
// Tutup koneksi database
mysqli_close($koneksi);
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
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet" />
</head>

<body id="page-top">
    <div id="wrapper">
        <?php require_once('../include/navbar_admin.php') ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once('../include/topbar_admin.php') ?>
                <div class="container-fluid">
                    <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Edit Artikel</h2>
                    <a href="artikel.php" class="btn btn-primary mt-3 mb-4"><i class="fa-solid fa-angle-left mr-2"></i> Kembali </a>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form method="post" action="edit_artikel.php?id=<?php echo $article_id; ?>" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="title">Judul</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $article['title']; ?>" required />
                                </div>
                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="Gangguan Kecemasan" <?php if ($article['category'] == 'Gangguan Kecemasan') echo 'selected'; ?>>Gangguan Kecemasan</option>
                                        <option value="Gangguan Kecemasan Umum" <?php if ($article['category'] == 'Gangguan Kecemasan Umum') echo 'selected'; ?>>Gangguan Kecemasan Umum</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="content">Isi</label>
                                    <textarea class="form-control" id="isiberita" name="content" rows="5" required><?php echo $article['content']; ?></textarea>
                                </div>
                                <div>
                                    <label for="content">Tampilan gambar: </label>
                                    <br>
                                    <?php if (!empty($article['image_path'])) : ?>
                                        <img src="<?php echo $article['image_path']; ?>" alt="Article Image" style="max-width: 300px;">
                                    <?php endif; ?>
                                </div>
                                <div class="form-group mt-3">
                                    <label style="cursor: pointer;" for="image">Perbarui gambar (maksimal 2MB)</label>
                                    <input style="cursor: pointer;" type="file" class="form-control-file mb-3" id="image" name="image" accept="image/*" maxlength="2097152" />
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
                                                Apakah Anda yakin ingin memperbarui Artikel?
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

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>
    <!-- Load CKEditor -->
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <script>
        // Inisialisasi CKEditor pada textarea dengan id "content"
        CKEDITOR.replace('isiberita');
    </script>
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