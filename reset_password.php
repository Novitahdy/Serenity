<?php
require_once "./include/koneksi.php";

// Deklarasi variabel pesan
$pesan = '';

// Jika parameter email dan token tersedia dalam URL
if (isset($_GET['email']) && isset($_GET['token'])) {
    // Ambil email dan token dari URL
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Query untuk memeriksa apakah email dan token cocok dalam database
    $checkQuery = "SELECT * FROM users WHERE email='$email' AND reset_token='$token'";
    $checkResult = mysqli_query($koneksi, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        // Jika email dan token tidak cocok, tampilkan pesan kesalahan
        $pesan = "Email atau token reset password tidak valid.";
    } else {
        // Jika email dan token cocok, tampilkan form reset password
        if (isset($_POST['reset_password'])) {
            // Ambil password baru dari form
            $password = $_POST['password'];

            // Validasi password (minimal 8 karakter dan mengandung angka)
            if (strlen($password) < 8 || !preg_match("/\d/", $password)) {
                $pesan = "Password harus terdiri dari minimal 8 karakter dan mengandung angka.";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Query untuk menyimpan password baru yang sudah di-hash ke dalam database
                $updateQuery = "UPDATE users SET password='$hashedPassword', reset_token=NULL WHERE email='$email'";
                $updateResult = mysqli_query($koneksi, $updateQuery);

                if (!$updateResult) {
                    // Jika gagal menyimpan password baru, tampilkan pesan kesalahan
                    $pesan = "Gagal mereset password.";
                } else {
                    // Jika berhasil mereset password, set pesan sukses
                    $pesansuccess = "Password berhasil direset. Silakan login dengan password baru Anda.";
                }
            }
        }
    }
} else {
    // Jika parameter email dan token tidak tersedia dalam URL, tampilkan pesan kesalahan
    $pesan = "Email atau token reset password tidak valid.";
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
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <title>Reset Password</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet" />

    <style>
        .card {
            max-width: 500px;
            margin: 0 auto;
        }

        body {
            background: linear-gradient(to right, #FFFFFF 0%, #69BE9D 100%);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row d-flex align-items-center" style="min-height: 100vh;">
            <div class="col-lg-12">
                <div class="card o-hidden border-0 shadow-lg my-5 mx-auto" style="max-width: 500px;">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Reset Password</h1>
                            </div>
                            <?php if (!empty($pesan)) : ?>
                                <div class="alert alert-danger"><?php echo $pesan; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($pesansuccess)) : ?>
                                <div class="alert alert-success"><?php echo $pesansuccess; ?></div>
                            <?php endif; ?>
                            <form class="user" method="post" action="">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" pattern="(?=.*\d).{8,}" title="Password harus terdiri dari minimal 8 karakter dan mengandung angka" required />
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility('password')">
                                                <i id="password-icon" class="fas fa-eye-slash"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" name="reset_password" value="Reset Password" class="btn btn-primary btn-user btn-block">
                            </form>
                            <hr />
                            <div class="text-center mt-2">
                                <a class="small" style="color:black" href="login.php">Halaman Masuk</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var icon = document.getElementById("password-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>

</html>