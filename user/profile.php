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

// Ambil informasi pengguna dari database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($koneksi, $query);
if (!$result) {
    // Error saat mengambil data dari database
    die("Query error: " . mysqli_error($koneksi));
}
$user = mysqli_fetch_assoc($result);

// Tangani permintaan perubahan informasi pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari formulir
    $namaLengkap = $_POST['Namalengkap'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['newPassword'];
    $password = $_POST['password'];
    $phoneNumber = $_POST['phoneNumber'];
    $prodi = $_POST['prodi'];
    $gender = $_POST['gender'];

    // Handle profile image upload
    if ($_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
        $uploadDir = '../uploads/profile/';
        $uploadFile = $uploadDir . basename($_FILES['profileImage']['name']);

        // Check file size
        if ($_FILES['profileImage']['size'] > $maxFileSize) {
            $pesan = "Ukuran file terlalu besar. Maksimum 2MB.";
        } else {
            // Move the uploaded file to the uploads directory
            if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadFile)) {
                // File uploaded successfully, update the profile image path in the database
                $updateQuery = "UPDATE users SET profile_image = '$uploadFile' WHERE id = $user_id";
                $updateResult = mysqli_query($koneksi, $updateQuery);
                if (!$updateResult) {
                    // Error updating the profile image path in the database
                    die("Update error: " . mysqli_error($koneksi));
                }
                // Redirect back to the profile page with a success message
                header("Location: profile.php?success=1");
                exit();
            } else {
                // Failed to upload the file, show an error message
                $pesan = "Gagal mengunggah foto profil.";
            }
        }
    }

    // Periksa apakah email baru sudah terdaftar dalam database
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$newEmail' AND id != $user_id"; // Pastikan tidak memeriksa email pengguna yang sedang diedit
    $checkEmailResult = mysqli_query($koneksi, $checkEmailQuery);
    if (!$checkEmailResult) {
        // Error saat memeriksa email dalam database
        die("Query error: " . mysqli_error($koneksi));
    }

    if (mysqli_num_rows($checkEmailResult) > 0) {
        // Email baru sudah terdaftar, tampilkan pesan kesalahan
        $pesan = "Email sudah terdaftar. Gunakan email lain.";
    } else {
        // Email baru belum terdaftar, lanjutkan dengan pembaruan profil
        // Perbarui informasi pengguna dalam database
        if (!empty($newPassword)) {
            // Jika newPassword diisi, update password
            $updateQuery = "UPDATE users SET Namalengkap = '$namaLengkap', email = '$newEmail', password = '$newPassword', phoneNumber = '$phoneNumber', gender = '$gender', prodi = '$prodi' WHERE id = $user_id";
        } else {
            // Jika newPassword tidak diisi, jangan update password
            $updateQuery = "UPDATE users SET Namalengkap = '$namaLengkap', email = '$newEmail', phoneNumber = '$phoneNumber', gender = '$gender', prodi = '$prodi' WHERE id = $user_id";
        }
        $updateResult = mysqli_query($koneksi, $updateQuery);
        if (!$updateResult) {
            // Error saat memperbarui data dalam database
            die("Update error: " . mysqli_error($koneksi));
        }
        // Redirect kembali ke halaman profil dengan pesan sukses
        header("Location: profile.php?success=1");
        exit();
    }
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

    <title>Dashboard User</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.css" rel="stylesheet" />
    <style>
        .profile-image-container {
            width: 150px;
            /* Adjust this value to your desired size */
            height: 150px;
            /* Adjust this value to your desired size */
            overflow: hidden;
            border-radius: 50%;
            cursor: pointer;
            border: 10px solid #eaecf4;
            /* Makes the container circular */
        }

        .profile-image-container img.rounded-circle {
            width: 100%;
            /* Ensures the image fills the container */
            height: 100%;
            /* Ensures the image fills the container */
            object-fit: cover;
            /* Maintains aspect ratio and covers the container */
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php require_once('../include/navbar_user.php') ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once('../include/topbar_user.php') ?>
                <div class="container-fluid">
                    <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Profil Saya</h2>
                    <?php if (isset($_GET['success'])) : ?>
                        <div class="alert alert-success" role="alert">
                            Profil berhasil diperbarui.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form method="post" action="profile.php" enctype="multipart/form-data">
                                <div class="text-center">
                                    <div class="form-group">
                                        <label for="profileImage" class="profile-image-container mb-3">
                                            <img id="preview" src="<?php echo $profileImage; ?>" class="h3 text-gray-800 rounded-circle" />
                                            <input type="file" name="profileImage" id="profileImage" accept="image/*" class="d-none" onchange="previewImage(event)">
                                        </label>
                                        <p class="form-text text-dark">Klik foto untuk mengganti foto profil.</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Namalengkap">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="Namalengkap" name="Namalengkap" value="<?php echo $user['Namalengkap']; ?>" required />
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required />
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="text" class="form-control" id="password" name="password" value="********" disabled />
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                </div>
                                <div class="form-group">
                                    <label for="newPassword">Password Baru</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" pattern="(?=.*\d).{8,}" />
                                    <small class="text-muted">Password harus terdiri dari minimal 8 karakter dan mengandung angka.</small>
                                </div>
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="Laki-Laki" <?php if ($user['gender'] == 'Laki-Laki') echo 'selected'; ?>>Laki-Laki</option>
                                        <option value="Perempuan" <?php if ($user['gender'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="phoneNumber">No. Handphone</label>
                                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10,}" value="<?php echo $user['phoneNumber']; ?>" required />
                                    <small class="text-muted">Nomor HP harus terdiri dari minimal 10 angka.</small>
                                </div>
                                <div class="form-group">
                                    <label for="prodi">Program Studi</label>
                                    <select class="form-control" id="prodi" name="prodi">
                                        <option value="DG" <?php if ($user['prodi'] == 'DG') echo 'selected'; ?>>D4 Desain Grafis</option>
                                        <option value="AN" <?php if ($user['prodi'] == 'AN') echo 'selected'; ?>>D4 Administrasi Negara</option>
                                        <option value="MI" <?php if ($user['prodi'] == 'MI') echo 'selected'; ?>>D4 Manajemen Informatika</option>
                                        <option value="TBog" <?php if ($user['prodi'] == 'Tbog') echo 'selected'; ?>>D4 Tata Boga</option>
                                        <option value="TBus" <?php if ($user['prodi'] == 'TBus') echo 'selected'; ?>>D4 Tata Busana</option>
                                        <option value="TL" <?php if ($user['prodi'] == 'TL') echo 'selected'; ?>>D4 Teknik Listrik</option>
                                        <option value="TM" <?php if ($user['prodi'] == 'TM') echo 'selected'; ?>>D4 Teknik Mesin</option>
                                        <option value="TS" <?php if ($user['prodi'] == 'TS') echo 'selected'; ?>>D4 Teknik Sipil</option>
                                        <option value="T" <?php if ($user['prodi'] == 'T') echo 'selected'; ?>>D4 Transportasi</option>
                                        <option value="KO" <?php if ($user['prodi'] == 'KO') echo 'selected'; ?>>D4 Kepelatihan Olahraga</option>
                                    </select>
                                </div>
                                <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-weight-bold" id="confirmModalLabel">Konfirmasi</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin memperbarui profil?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" id="confirmButton">Perbarui</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3 mb-4">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once('../include/footer.php') ?>
        </div>
    </div>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script>
        // Function to preview the selected image before upload
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
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
    </script>
</body>

</html>