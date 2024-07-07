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

// Ambil semua pertanyaan dan nilai dari database
$questions_query = "SELECT question_text, nilai_a, nilai_b, nilai_c, nilai_d FROM questions_d";
$questions_result = mysqli_query($koneksi, $questions_query);
$questions = [];
if ($questions_result) {
    while ($row = mysqli_fetch_assoc($questions_result)) {
        $questions[] = $row;
    }
} else {
    die("Query error: " . mysqli_error($koneksi));
}
mysqli_close($koneksi);

function saveConsultationResult($user_id, $total_score, $result_category)
{
    require_once "../include/koneksi.php";

    $query = "INSERT INTO consultation_results (user_id, total_score, result_category) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "iis", $user_id, $total_score, $result_category);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($koneksi);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($koneksi);
        return false;
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
        p {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .radio {
            margin-bottom: 10px;
        }

        .radio input[type="radio"] {
            margin-right: 1px;
        }

        .radio label {
            cursor: pointer;
        }

        button {
            background-color: #69BE9D;
            color: #fff;
            border-radius: 0.35rem;
            cursor: pointer;
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php
        require_once('../include/navbar_user.php');
        ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php
                require_once('../include/topbar_user.php');
                ?>
                <div class="container-fluid">
                    <h2 class="card" style="background-color: #69BE9D; color: white; padding: 25px 50px;">Konsultasi Depresi</h2>
                    <a href="user_dashboard.php" class="btn btn-primary mb-3">
                        <i class="fa-solid fa-angle-left"></i> Kembali
                    </a>
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-xl-12">
                            <div class="card shadow mb-4">
                                <div class="card-body text-center">
                                    <p>Pikirkan selama 2 minggu terakhir, seberapa sering permasalahan berikut menggangu anda?</p>
                                    <b>Saya merasa</b>
                                    <p id="question-text" class="font-weight-bold text-dark"></p>
                                    <form id="question-form" class="form-row justify-content-center" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="col-auto">
                                            <div class="radio">
                                                <input type="radio" id="tidakPernah" name="jawaban" value="nilai_a">
                                                <label for="tidakPernah">Tidak Pernah</label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="radio">
                                                <input type="radio" id="kadangKadang" name="jawaban" value="nilai_b">
                                                <label for="kadangKadang">Kadang-kadang</label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="radio">
                                                <input type="radio" id="sering" name="jawaban" value="nilai_c">
                                                <label for="sering">Sering</label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="radio">
                                                <input type="radio" id="sangatSering" name="jawaban" value="nilai_d">
                                                <label for="sangatSering">Sangat Sering</label>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <button type="button" id="next-button" class="btn btn-primary">Selanjutnya</button>
                                        </div>
                                        <input type="hidden" name="submit" value="submit">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            require_once('../include/footer.php');
            ?>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">Hasil Konsultasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Total skor anda adalah: <span id="total-score"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

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

    <script>
        const questions = <?php echo json_encode($questions); ?>;
        let currentQuestionIndex = 0;
        let totalScore = 0;

        function displayQuestion(index) {
            const question = questions[index];
            document.getElementById('question-text').innerText = question.question_text;
            resetRadioButtons();
        }

        function resetRadioButtons() {
            const radioButtons = document.querySelectorAll('input[name="jawaban"]');
            radioButtons.forEach(radio => radio.checked = false);
        }

        document.getElementById('next-button').addEventListener('click', function() {
            const selectedRadio = document.querySelector('input[name="jawaban"]:checked');
            if (selectedRadio) {
                const question = questions[currentQuestionIndex];
                const selectedValue = question[selectedRadio.value];
                totalScore += parseInt(selectedValue);

                if (currentQuestionIndex < questions.length - 1) {
                    currentQuestionIndex++;
                    displayQuestion(currentQuestionIndex);

                    if (currentQuestionIndex === questions.length - 1) {
                        this.innerText = 'Lihat Hasil';
                    }
                } else {
                    document.getElementById('total-score').innerText = totalScore;
                    // Tampilkan kategori berdasarkan total skor
                    displayResultCategory(totalScore);
                    $('#resultModal').modal('show');
                }
            } else {
                alert('Pilih salah satu jawaban terlebih dahulu.');
            }
        });

        function saveConsultationResult(userId, totalScore, resultCategory) {
            $.ajax({
                url: 'save_consultation_result.php',
                type: 'POST',
                data: {
                    user_id: userId,
                    total_score: totalScore,
                    result_category: resultCategory
                },
                success: function(response) {
                    console.log('Consultation result saved successfully');
                },
                error: function(error) {
                    console.log('Error saving consultation result:', error);
                }
            });
        }

        function displayResultCategory(totalScore) {
            // Kalikan total skor dengan 2 sesuai metode FORWARD CHAINING
            let totalScoreForwardChaining = totalScore * 2;

            let resultCategory = 'Normal';
            let recommendations = [
                "Lanjutkan kebiasaan baik yang bikin kamu bahagia.",
                "Terus lakukan aktivitas yang kamu sukai dan jaga."
            ];

            if (totalScoreForwardChaining >= 0 && totalScoreForwardChaining <= 9) {
                resultCategory = 'Normal';
                recommendations = [
                    "Lanjutkan kebiasaan baik yang bikin kamu bahagia.",
                    "Terus lakukan aktivitas yang kamu sukai dan jaga."
                ];
            } else if (totalScoreForwardChaining > 10 && totalScoreForwardChaining <= 13) {
                resultCategory = 'Depresi Ringan';
                recommendations = [
                    "Cobalah olahraga ringan dan makan makanan sehat.",
                    "Lakukan meditasi atau yoga untuk relaksasi.",
                    "Habiskan waktu dengan teman dan keluarga yang mendukung."
                ];
            } else if (totalScoreForwardChaining > 14 && totalScoreForwardChaining <= 20) {
                resultCategory = 'Depresi Sedang';
                recommendations = [
                    "Bicaralah dengan seseorang yang kamu percayai tentang perasaanmu.",
                    "Pertimbangkan untuk ngobrol dengan konselor atau terapis.",
                    "Pastikan kamu cukup tidur dan makan dengan baik."
                ];
            } else if (totalScoreForwardChaining > 21 && totalScoreForwardChaining <= 27) {
                resultCategory = 'Depresi Berat';
                recommendations = [
                    "Temui profesional kesehatan mental untuk mendapatkan saran lebih lanjut.",
                    "Cobalah terapi seperti terapi kognitif perilaku(CBT).",
                    "Jangan isolasi diri, tetap aktif dan terhubung dengan orang lain."
                ];
            } else if (totalScoreForwardChaining > 28) {
                resultCategory = 'Depresi Sangat Berat';
                recommendations = [
                    "Segera cari bantuan dari profesional kesehatan mental.",
                    "Kombinasi terapi dan obat bisa membantu.",
                    "Jangan ragu untuk meminta dukungan dari teman dan keluarga."
                ];
            }

            const modalBody = document.querySelector('#resultModal .modal-body');
            modalBody.innerHTML = `Total skor anda adalah: <span id="total-score">${totalScoreForwardChaining}</span><br>Kategori: ${resultCategory}<br><br>Rekomendasi:<br>`;

            recommendations.forEach((recommendation, index) => {
                modalBody.innerHTML += `${index + 1}. ${recommendation}<br>`;
            });

            // Add the "Mencari Psikolog Terdekat" button for categories "Sedang", "Berat", and "Sangat Berat"
            if (resultCategory === 'Depresi Sedang' || resultCategory === 'Depresi Berat' || resultCategory === 'Depresi Sangat Berat') {
                modalBody.innerHTML += `<br>Rekomendasi lainnya:<br><button class="btn btn-primary" onclick="searchNearbyPsychologist()">Mencari Psikolog Terdekat</button>`;
            }

            // Save consultation result to the database
            saveConsultationResult(<?php echo $user_id; ?>, totalScoreForwardChaining, resultCategory);
        }

        function searchNearbyPsychologist() {
            window.location.href = "https://www.google.com/maps/search/?api=1&query=psikolog+terdekat&query_place_id=current+location";
        }

        // Display the first question when the page loads
        displayQuestion(currentQuestionIndex);

        // Menangkap peristiwa penutupan modal
        $('#resultModal').on('hidden.bs.modal', function() {
            // Arahkan ulang ke user_dashboard.php
            window.location.href = 'user_dashboard.php';
        });
    </script>

</body>

</html>