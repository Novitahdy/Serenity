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

// Get user_id from the URL
$user_id = $_GET['id'];

// Fetch user data from the database
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($koneksi, $query);

// Check if user exists
if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: pengguna.php"); // Redirect to user list if user does not exist
    exit();
}
$user = mysqli_fetch_assoc($result); // Fetch user data

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Retrieve form data
    $namaLengkap = mysqli_real_escape_string($koneksi, $_POST['Namalengkap']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $newPassword = mysqli_real_escape_string($koneksi, $_POST['newPassword']);
    $phoneNumber = mysqli_real_escape_string($koneksi, $_POST['phoneNumber']);
    $prodi = mysqli_real_escape_string($koneksi, $_POST['prodi']);
    $gender = mysqli_real_escape_string($koneksi, $_POST['gender']);

    // Update user data in the database
    $updateQuery = "UPDATE users SET Namalengkap='$namaLengkap', email='$email', phoneNumber='$phoneNumber', prodi='$prodi', gender='$gender'";

    // Check if a new password is provided and update it if necessary
    if (!empty($newPassword)) {
        // Hash the new password before storing it in the database
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateQuery .= ", password='$hashedPassword'";
    }

    $updateQuery .= " WHERE id=$user_id";

    if (mysqli_query($koneksi, $updateQuery)) {
        // Redirect ke halaman pengguna.php dengan pesan sukses edit
        header("Location: pengguna.php?success=edit");
        exit();
    } else {
        // Handle the update failure
        echo "Error updating record: " . mysqli_error($koneksi);
    }
}
// Close database connection
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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
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
                    <?php if (isset($_GET['success'])) : ?>
                        <div class="alert alert-success" role="alert">
                            Profile updated successfully!
                        </div>
                    <?php endif; ?>
                    <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Edit Pengguna</h2>
                    <a href="pengguna.php" class="btn btn-primary mt-3 mb-4"><i class="fa-solid fa-angle-left mr-2"></i> Kembali </a>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form method="post" action="edit_user.php?id=<?php echo $user_id; ?>" enctype="multipart/form-data">
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
                                    <label for="newPassword">New Password</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" pattern="(?=.*\d).{8,}" />
                                    <span class="text-muted">Password harus terdiri dari minimal 8 karakter dan mengandung angka.</span>
                                </div>
                                <div class="form-group">
                                    <label for="phoneNumber">Phone Number</label>
                                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10,}" value="<?php echo $user['phoneNumber']; ?>" required />
                                    <span class="text-muted">Nomor HP harus terdiri dari minimal 10 angka.</span>
                                </div>
                                <div class="form-group">
                                    <label for="prodi">Prodi</label>
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
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="Laki-Laki" <?php if ($user['gender'] == 'Laki-Laki') echo 'selected'; ?>>Laki-Laki</option>
                                        <option value="Perempuan" <?php if ($user['gender'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                                    </select>
                                </div>
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

</body>

</html>