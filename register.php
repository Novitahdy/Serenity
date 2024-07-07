<?php
require_once "./include/koneksi.php";

// Deklarasi variabel pesan
$pesan = '';

// Jika tombol "Buat akun" diklik
if (isset($_POST['submit'])) {
  // Ambil nilai dari form
  $namaLengkap = $_POST['Namalengkap'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $phoneNumber = $_POST['phoneNumber'];
  $prodi = $_POST['prodi'];
  $gender = $_POST['gender'];

  // Validasi form (pastikan semua field terisi)
  if (empty($namaLengkap) || empty($email) || empty($password) || empty($phoneNumber) || empty($prodi) || empty($gender)) {
    $pesan = "Harap isi semua kolom";
  } else {
    // Query untuk memeriksa apakah email sudah ada di database
    $checkQuery = "SELECT * FROM users WHERE email='$email'";
    $checkResult = mysqli_query($koneksi, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
      // Jika email sudah ada, tampilkan pesan kesalahan
      $pesan = "Email sudah digunakan, gunakan email lain";
    } else {
      // Jika email belum ada, lakukan penyimpanan data
      // Set default role to "user"
      $role = "user";

      // Hash the password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Query untuk menyimpan data ke dalam database, termasuk role dan gender
      $query = "INSERT INTO users (Namalengkap, email, password, phoneNumber, prodi, role, gender) VALUES ('$namaLengkap', '$email', '$hashedPassword', '$phoneNumber', '$prodi', '$role', '$gender')";

      // Jalankan query
      if (mysqli_query($koneksi, $query)) {
        // Redirect ke halaman login.php
        header("Location: register.php?success=register");
        exit();
      } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
      }
    }
  }

  // Tutup koneksi ke database
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

  <title>Daftar</title>

  <!-- Custom fonts for this template-->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/unicons.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">

  <link rel="stylesheet" href="css/tooplate-style.css">
  <!-- <link href="css/sb-admin-2.css" rel="stylesheet" /> -->

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

  <section class="about full-screen d-lg-flex justify-content-center align-items-center" id="about" style="min-height: 100vh; padding-top: 80px;">
    <div class="container">
      <div class="row">
        <div class="row d-flex align-items-center" style="min-height: 100vh;">
          <!-- Kolom untuk form registrasi -->
          <div class="col-lg-6 col-md-12">
            <div class="card o-hidden border-0 shadow-lg my-5 mx-auto" style="max-width: 500px;">
              <div class="card-body p-0">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h2 mb-4">Register</h1>
                  </div>
                  <!-- Check if the 'success' parameter exists in the URL -->
                  <?php if (isset($_GET['success']) && $_GET['success'] === 'register') : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                      Berhasil Daftar, Silahkan
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <a class="regular" href="login.php">Masuk</a>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($pesan)) : ?>
                    <div class="alert alert-danger"><?php echo $pesan; ?></div>
                  <?php endif; ?>
                  <form class="user" method="post" action="" onsubmit="return validatePassword()">
                    <div class="form-group">
                      <input type="text" class="form-control" id="Namalengkap" name="Namalengkap" placeholder="Nama Lengkap" required />
                    </div>
                    <div class="form-group">
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
                    </div>
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
                    <div class="form-group">
                      <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Konfirmasi Password" pattern="(?=.*\d).{8,}" title="Password harus terdiri dari minimal 8 karakter dan mengandung angka" required />
                    </div>
                    <div class="form-group">
                      <!-- <label for="gender">Jenis Kelamin</label> -->
                      <select class="form-control" id="gender" name="gender" required>
                        <option value="" selected disabled hidden>Pilih Jenis Kelamin</option>
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <!-- <label for="phoneNumber">Nomor HP</label> -->
                      <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Nomor HP" pattern="[0-9]{10,}" title="Nomor HP harus terdiri dari minimal 10 angka" required />
                    </div>
                    <div class="form-group">
                      <!-- <label for="prodi">Prodi</label> -->
                      <select class="form-control" id="prodi" name="prodi" required>
                        <option value="" selected disabled hidden>Program Studi</option>
                        <option value="DG">D4 Desain Grafis</option>
                        <option value="AN">D4 Administrasi Negara</option>
                        <option value="MI">D4 Manajemen Informatika</option>
                        <option value="TBog">D4 Tata Boga</option>
                        <option value="TBus">D4 Tata Busana</option>
                        <option value="TL">D4 Teknik Listrik</option>
                        <option value="TM">D4 Teknik Mesin</option>
                        <option value="TS">D4 Teknik Sipil</option>
                        <option value="T">D4 Transportasi</option>
                        <option value="KO">D4 Kepelatihan Olahraga</option>
                      </select>
                    </div>
                    <input type="submit" name="submit" value="Register" class="btn custom-btn custom-btn-bg1 custom-btn-link btn-block">
                  </form>
                  <br>
                  <div class="text-center">
                    <a class="small">Sudah punya akun?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="login.php">Masuk sekarang!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Kolom untuk gambar SVG -->
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

  <script>
    // Fungsi untuk memeriksa apakah kolom password dan konfirmasi password sama
    function validatePassword() {
      var password = document.getElementById("password").value;
      var confirmPassword = document.getElementById("confirmPassword").value;

      if (password != confirmPassword) {
        alert("Password dan konfirmasi password harus sama.");
        return false;
      }
      return true;
    }
  </script>
</body>

</html>