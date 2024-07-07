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

require_once "../include/koneksi.php";

// Periksa apakah parameter id artikel telah diberikan
if (isset($_GET['id'])) {
    // Sanitasi inputan id artikel
    $article_id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM articles WHERE id = '$article_id'";
    $result = mysqli_query($koneksi, $query);
    // Periksa apakah query berhasil
    if ($result) {
        // Ambil data artikel
        $article = mysqli_fetch_assoc($result);
    } else {
        // Jika query gagal, tampilkan pesan error
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
} else {
    // Jika parameter id tidak diberikan, redirect ke halaman lain atau tampilkan pesan error
    header("Location: error.php");
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
// Tutup koneksi ke database
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

    <title>Dashboard User</title>

    <!-- Custom fonts for thi s template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet" />
    <style>
        .card-img-top {
            width: 100%;
            object-fit: cover;
        }

        .row-artikel-detail {
            margin-top: 20px;
        }

        /* 
        .article-title {
            margin-top: 20px;
            margin-bottom: 10px;
        } */
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
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
                    <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Artikel Kesehatan Mental</h2>
                    <a href="baca_artikel.php" class="btn btn-primary mb-3">
                        <i class="fa-solid fa-angle-left"></i> Kembali
                    </a>
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <?php if ($article && isset($article['category'])) : ?>
                                        <p class="h5 text-white badge bg-primary"><?php echo $article['category']; ?></p>
                                    <?php endif; ?>
                                    <?php if ($article && isset($article['title'])) : ?>
                                        <h2 class="article-title font-weight-bold text-dark"><?php echo $article['title']; ?></h2>
                                    <?php endif; ?>
                                    <?php if ($article && isset($article['updated_at'])) : ?>
                                        <small class='card-text text-dark' style='font-size:15px;'>Diperbarui pada <?php echo date('d M Y', strtotime($article['updated_at'])); ?> oleh Admin</small>
                                    <?php endif; ?>
                                    <?php if ($article && isset($article['image_path'])) : ?>
                                        <img class="card-img-top mt-2" src="<?php echo $article['image_path']; ?>" alt="">
                                    <?php endif; ?>

                                    <?php if ($article && isset($article['content'])) : ?>
                                        <p><?php echo $article['content']; ?></p>
                                    <?php endif; ?>
                                </div>
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