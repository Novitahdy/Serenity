<?php
require_once "./include/koneksi.php";

require "./vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Deklarasi variabel pesan
$pesan = '';

// Jika tombol "Lupa password" diklik
if (isset($_POST['lupa_password'])) {
    // Ambil email dari form
    $email = $_POST['email'];

    // Validasi email (pastikan email terisi)
    if (empty($email)) {
        $pesan = "Harap isi kolom email";
    } else {
        // Query untuk memeriksa apakah email ada di database
        $checkQuery = "SELECT * FROM users WHERE email='$email'";
        $checkResult = mysqli_query($koneksi, $checkQuery);

        if (mysqli_num_rows($checkResult) == 0) {
            // Jika email tidak ditemukan, tampilkan pesan kesalahan
            $pesan = "Email tidak ditemukan";
        } else {
            // Jika email ditemukan, buat token untuk reset password
            $token = md5(uniqid(rand(), true));

            // Query untuk menyimpan token ke dalam database
            $updateQuery = "UPDATE users SET reset_token='$token' WHERE email='$email'";
            $updateResult = mysqli_query($koneksi, $updateQuery);

            if (!$updateResult) {
                // Jika gagal menyimpan token, tampilkan pesan kesalahan
                $pesan = "Gagal mengirim email reset password";
            } else {
                // Query untuk mengambil nama pengguna
                $namaQuery = "SELECT namaLengkap FROM users WHERE email='$email'";
                $namaResult = mysqli_query($koneksi, $namaQuery);

                if (mysqli_num_rows($namaResult) > 0) {
                    $row = mysqli_fetch_assoc($namaResult);
                    $nama = $row['namaLengkap'];
                }

                // Kirim email reset password
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 0; // Enable verbose debug output
                    $mail->isSMTP(); // Send using SMTP
                    $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
                    $mail->SMTPAuth   = true; // Enable SMTP authentication
                    $mail->Username   = 'shabianarsyly@gmail.com'; // SMTP username
                    $mail->Password   = 'nsgiifjueulfswul'; // SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                    $mail->Port       = 587; // TCP port to connect to

                    //Recipients
                    $mail->setFrom('shabianarsyly@gmail.com', 'Serenity');
                    $mail->addAddress($email); // Add a recipient

                    // Content
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = 'Reset Password Akun Serenity';
                    $mail->Body = '
                    <div style="text-align: center;">
                        <p>Hi ' . $nama . ',</p>
                        <p>We received a request to reset your Serenity Account password.</p>
                        <p>To reset your password, click the button below:</p>
                        <p><a href="http://localhost/jokiannovi/reset_password.php?email=' . $email . '&token=' . $token . '" target="_blank" style="padding: 10px 20px; background-color: #69BE9D; color: white; text-decoration: none; border-radius: 5px;">Reset Password</a></p>
                        <p>If you did not request a password reset, please ignore this email. Your password will remain unchanged.</p>
                        <p>Thank you for using our services.</p>
                        <p>Best regards,<br>Serenity Admin</p>
                    </div>
                    ';
                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    $mail->send();
                    $pesansuccess = "Email reset password telah dikirim. Silahkan cek email Anda.";
                } catch (Exception $e) {
                    $pesan = "Gagal mengirim email reset password";
                }
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

    <title>Lupa Password</title>

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
                                <h1 class="h4 text-gray-900 mb-4">Lupa Password</h1>
                            </div>
                            <?php if (!empty($pesan)) : ?>
                                <div class="alert alert-danger"><?php echo $pesan; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($pesansuccess)) : ?>
                                <div class="alert alert-success"><?php echo $pesansuccess; ?></div>
                            <?php endif; ?>
                            <form class="user" method="post" action="">
                                <div class="form-group">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" />
                                </div>
                                <input type="submit" name="lupa_password" value="Reset Password" class="btn btn-primary btn-user btn-block">
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
</body>

</html>