<?php
// Lakukan koneksi ke database
require_once '../include/koneksi.php';

// Array untuk nama-nama bulan
$months = array(
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
);

// Query untuk mengambil data dari tabel skrining
$query = "SELECT DATE_FORMAT(timestamp, '%m') AS bulan, result_category, COUNT(*) AS total FROM consultation_results GROUP BY bulan, result_category";
$result = mysqli_query($koneksi, $query);

$data = array();

if ($result) {
    // Jika query berresult_category, ambil data
    while ($row = mysqli_fetch_assoc($result)) {
        $bulan = $row['bulan'];
        $result_category = $row['result_category'];
        $total = $row['total'];

        // Tambahkan data ke array
        if (!isset($data[$bulan])) {
            $data[$bulan] = array(
                'bulan' => $bulan,
                'stres' => 0,
                'kecemasan' => 0,
                'depresi' => 0,
            );
        }

        if ($result_category == 'Stres') {
            $data[$bulan]['stres'] += $total;
        } else if ($result_category == 'Kecemasan') {
            $data[$bulan]['kecemasan'] += $total;
        } else if ($result_category == 'Depresi') {
            $data[$bulan]['depresi'] += $total;
        }
    }

    // Urutkan data berdasarkan bulan
    ksort($data);

    // Ubah format bulan menjadi nama bulan dalam bahasa Indonesia
    foreach ($data as $key => $value) {
        $data[$key]['bulan'] = $months[intval($value['bulan']) - 1];
    }
} else {
    // Jika query gagal, tampilkan pesan error
    echo "Error: " . mysqli_error($koneksi);
}

// Mengembalikan data dalam format JSON
echo json_encode(array_values($data));

// Menutup koneksi database
mysqli_close($koneksi);
