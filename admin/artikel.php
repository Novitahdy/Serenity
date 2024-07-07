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

// Inisialisasi variabel
$title = "";
$content = "";
$image_path = "";
$category = "";

// Jika tombol "Submit" diklik
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $title = $_POST["title"];
    $content = $_POST["content"];
    $category = $_POST["category"]; // Ambil kategori yang dipilih

    // Ambil informasi file gambar
    $image_name = $_FILES["image"]["name"];
    $image_tmp = $_FILES["image"]["tmp_name"];
    $image_path = "../uploads/" . $image_name;

    // Periksa ukuran file gambar (maksimal 2MB)
    if ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
        echo "<script>alert('Ukuran file gambar melebihi batas maksimal (2MB).');</script>";
    } else {
        // Pindahkan file gambar ke folder uploads
        move_uploaded_file($image_tmp, $image_path);

        // Query untuk menyimpan artikel ke database
        $query = "INSERT INTO articles (title, category, content, image_path) VALUES ('$title', '$category','$content', '$image_path')";
        $result = mysqli_query($koneksi, $query);

        // Jika query berhasil dijalankan
        if ($result) {
            echo "<script>alert('Artikel berhasil ditambahkan');</script>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
        }
    }
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
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .btn-transparent-green {
            background-color: transparent;
            border: 2px solid #69BE9D;
            color: #69BE9D;
            transition: background-color 0.3s, color 0.3s;
        }
    </style>
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
                <?php
                require_once('../include/topbar_admin.php')
                ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Artikel Kesehatan Mental</h2>
                    <!-- Tombol untuk membuat artikel baru -->
                    <div class="d-flex justify-content-end mb-3">
                        <a type="button" href="list_artikel.php" class="btn btn-transparent-green mr-2">Lihat List Artikel</a>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addArticleModal">
                            <i class="fa-solid fa-plus mr-2"></i> Tambah Data
                        </button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="addArticleModal" tabindex="-1" role="dialog" aria-labelledby="addArticleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addArticleModalLabel">Tambah Artikel Baru</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Isi formulir untuk menambahkan artikel -->
                                    <form method="post" action="artikel.php" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="title">Judul</label>
                                            <input type="text" class="form-control" id="title" name="title" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Kategori</label>
                                            <select class="form-control" id="category" name="category" required>
                                                <option value="">Pilih Kategori</option>
                                                <option value="Gangguan Kecemasan">Gangguan Kecemasan</option>
                                                <option value="Gangguan Kecemasan Umum">Gangguan Kecemasan Umum</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="content">Isi</label>
                                            <textarea class="form-control" id="isiberita" name="content" rows="5" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Gambar (maksimal 2MB)</label>
                                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required maxlength="2097152">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Tambah Artikel</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    require_once "../include/koneksi.php";
                    // Check if the 'success' parameter exists in the URL
                    if (isset($_GET['success'])) {
                        // Check the value of the 'success' parameter
                        if ($_GET['success'] === 'delete') {
                            // If the value is 'delete', display a success message
                            echo '<div class="alert alert-success" role="alert">Artikel berhasil dihapus.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                        }
                    }
                    // Check if the 'success' parameter exists in the URL
                    if (isset($_GET['success'])) {
                        // Check the value of the 'success' parameter
                        if ($_GET['success'] === '1') {
                            // If the value is '1', display a success message
                            echo '<div class="alert alert-success" role="alert">Artikel berhasil diperbarui.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button></div>';
                        }
                    }
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
                        echo "<th width='10%'>Kategori</th>";
                        echo "<th width='10%'>Isi</th>";
                        echo "<th>Gambar</th>";
                        echo "<th>Aksi</th>";
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
                            echo "<td>" . $row['category'] . "</td>";
                            echo "<td>" . substr($row['content'], 0, 100) . "</td>";
                            echo "<td><img src='" . $row['image_path'] . "' alt='Article Image' style='max-width: 100px; max-height: 100px;'></td>";
                            echo "<td style='text-align: center'>";
                            echo "<a href='lihat_artikel.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Detail</a>";
                            echo "&nbsp;";
                            echo "<a href='edit_artikel.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>";
                            echo "&nbsp;";
                            echo "<a href='../hapus_artikel.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>";
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
                <!-- /.container-fluid -->
                <?php
                require_once('../include/footer.php')
                ?>
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

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

    <!-- Load CKEditor -->
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <script>
        // Inisialisasi CKEditor pada textarea dengan id "content"
        CKEDITOR.replace('isiberita');
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get all the navigation links
            var navLinks = document.querySelectorAll('.nav-link');

            // Add click event listeners to each link
            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    // Remove the "active" class from all links
                    navLinks.forEach(function(navLink) {
                        navLink.classList.remove('active');
                    });

                    // Add the "active" class to the clicked link
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>

</html>