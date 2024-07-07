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

    <style>
        .artikel-card {
            display: flex;
            flex-direction: row;
            /* height: 100%; */
        }

        .artikel-card img {
            width: 25%;
            height: auto;
            object-fit: cover;
        }

        .artikel-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 60%;
            padding-left: 20px;
        }
    </style>
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
                    <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Artikel Kesehatan Mental</h2>
                    <a href="artikel.php" class="btn btn-primary mb-3">
                        <i class="fa-solid fa-angle-left"></i> Kembali
                    </a>
                    <div class="row" id="artikelList">
                        <?php
                        require_once "../include/koneksi.php";
                        // Query untuk mengambil semua data artikel
                        $query = "SELECT * FROM articles";
                        $result = mysqli_query($koneksi, $query);
                        // Periksa apakah query berhasil
                        if ($result) {
                            // Tampilkan data artikel dalam bentuk card
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='col-xl-12 col-md-12 mb-4'>";
                                echo "<div class='card card artikel-card'>";
                                echo "<img src='" . $row['image_path'] . "' class='card-img-top' alt='Gambar artikel'>";
                                echo "<div class='card-body'>";
                                // echo "<small class='card-text'><i class='fas fa-clock mr-2 mb-2'></i>" . date('d M Y', strtotime($row['updated_at'])) . "</small>";
                                // echo "<br>";
                                echo "<p class='card-text mb-2 text-white badge bg-primary'>" . $row['category'] . "</p>";
                                echo "<h5 class='card-title mb-2 text-dark font-weight-bold'>" . substr($row['title'], 0, 100) . "</h5>";
                                echo "<p class='card-text'>" . substr($row['content'], 0, 180) . "....." . "</p>";
                                echo "<a href='article_detail.php?id=" . $row['id'] . "'>Baca Selengkapnya</a>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            // Jika query gagal, tampilkan pesan error
                            echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
                        }
                        // Tutup koneksi ke database
                        mysqli_close($koneksi);
                        ?>
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
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

    <!-- <script>
        document.getElementById('categoryFilter').addEventListener('change', function() {
            var filterValue = this.value;
            var articles = document.querySelectorAll('#artikelList .col-xl-4');

            articles.forEach(function(article) {
                var category = article.querySelector('.badge').textContent.trim().toLowerCase();

                if (filterValue === 'all' || category === filterValue) {
                    article.style.display = 'block';
                } else {
                    article.style.display = 'none';
                }
            });
        });
    </script> -->

</body>

</html>