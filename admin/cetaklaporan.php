<?php
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
require_once "../include/koneksi.php";

$id = $_GET['id'];

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Serenity');
$pdf->SetTitle('Data Laporan');
$pdf->SetSubject('Data Laporan');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->SetFont('helvetica', '', 10);

$pdf->AddPage();

$html = '<h1 style="margin-left: 20px;">Data Laporan Skrining Kesehatan Mental - Serenity</h1>';
$html .= '<table border="1" style="margin-left: 20px;">';
$html .= '<tr><th>Nama</th><th>Gender</th><th>Angkatan</th><th>Hasil</th><th>Nilai</th><th>Tanggal Tes</th><th>Waktu Tes</th><th>Gejala</th></tr>';

$query = "SELECT users.Namalengkap, users.gender, users.angkatan, skrining.hasil, skrining.nilai, skrining.waktu, skrining.timer, skrining.gejala FROM skrining JOIN users ON skrining.user_id = users.id WHERE skrining.id = $id";
$result = mysqli_query($koneksi, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>';
    $html .= '<td>' . $row['Namalengkap'] . '</td>';
    $html .= '<td>' . $row['gender'] . '</td>';
    $html .= '<td>' . $row['angkatan'] . '</td>';
    $html .= '<td>' . $row['hasil'] . '</td>';
    $html .= '<td>' . $row['nilai'] . '</td>';
    $html .= '<td>' . $row['waktu'] . '</td>';
    $html .= '<td>' . $row['timer'] . '</td>';
    $html .= '<td>' . $row['gejala'] . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('laporan_tes.pdf', 'D');
exit;
