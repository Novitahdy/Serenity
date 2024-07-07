<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once "./include/koneksi.php";

  // Ambil nilai dari form
  $email = mysqli_real_escape_string($koneksi, $_POST['email']);
  $password = mysqli_real_escape_string($koneksi, $_POST['password']);

  // Query untuk mencari user berdasarkan email
  $query = "SELECT * FROM users WHERE email = ?";
  $stmt = mysqli_prepare($koneksi, $query);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // Periksa apakah query berhasil dijalankan dan data ditemukan
  if ($result && mysqli_num_rows($result) > 0) {
    // Data ditemukan, ambil data user
    $user = mysqli_fetch_assoc($result);

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
      // Set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['role'] = $user['role'];
      $_SESSION['user'] = $user;

      // Redirect based on user role
      if ($user['role'] == 'admin') {
        header("Location: ./admin/admin_dashboard.php");
      } else {
        header("Location: ./user/user_dashboard.php");
      }
      exit();
    } else {
      // Password tidak cocok, set pesan error
      $error_message = "Email atau password salah. Silakan coba lagi.";
    }
  } else {
    // Data tidak ditemukan, set pesan error
    $error_message = "Akun tidak terdaftar. Silakan coba lagi.";
  }

  // Tutup statement dan koneksi ke database
  mysqli_stmt_close($stmt);
  mysqli_close($koneksi);
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

  <title>Masuk</title>

  <!-- Custom fonts for this template-->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/unicons.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">

  <link rel="stylesheet" href="css/tooplate-style.css">

  <!-- Custom styles for this template-->
  <style>
    body {
      background: linear-gradient(to right, #FFFFFF 0%, #69BE9D 100%);
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <img src="img/Logo.png" alt="Logo" style="width: 150px; height: auto;">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register.php">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="about full-screen d-lg-flex justify-content-center align-items-center" id="about" style="min-height: 100vh;">
    <div class="container">
      <div class="row">
        <div class="row d-flex align-items-center" style="min-height: 100vh;">
          <div class="col-lg-6 col-md-12">
            <div class="card o-hidden border-0 shadow-lg my-5 mx-auto" style="max-width: 500px;">
              <div class="card-body p-0">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h2 mb-4">Silakan Masuk!</h1>
                  </div>
                  <?php
                  // Check if error message is set and not empty
                  if (isset($error_message) && !empty($error_message)) {
                    echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
                  }
                  ?>
                  <form class="user" method="post" action="">
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" name="email" id="email" placeholder="Email" required />
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password" required />
                        <div class="input-group-append">
                          <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                            <i id="password-icon" class="fas fa-eye-slash"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col text-right">
                        <div class="form-group">
                          <a class="small" href="forget_password.php">Lupa Password?</a>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn custom-btn custom-btn-bg1 custom-btn-link btn-block">
                      Login
                    </button>
                  </form>
                  <br>
                  <div class="text-center">
                    <a class="small">Belum punya akun?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.php">Buat akun sekarang!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-12 col-12">
            <div class="about-image svg text-center">
              <img src="img/Serenity.png" class="img-fluid" alt="svg image">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

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