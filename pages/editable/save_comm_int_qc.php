<?php
include '../../koneksi.php';
session_start();

// data dari X-Editable
$pk     = $_POST['pk'] ?? '';     // contoh: "SO123|10"
$comm_int_qc = $_POST['value'] ?? '';  // demand terpilih

// data tambahan (dikirim lewat data-params)
$demand        = $_POST['demand'] ?? '';

// cek apakah record sudah ada
$qCheck = "SELECT 1 FROM tbl_firstlot WHERE demand = '$demand'";
$res = mysqli_query($cona, $qCheck);

if (mysqli_fetch_assoc($res)) {
    // update demand + identitas
    $q = "UPDATE tbl_firstlot SET comm_int_qc ='".addslashes($comm_int_qc)."', lastupdatetime = NOW(), lastupdateuser = '".addslashes($_SESSION['nama10'])."' WHERE demand = '$demand' ";
} else {
    // insert baru
    $q = "Wajib mengisi production demand terlebih dahulu";
}

if (mysqli_query($cona, $q)) {
    echo "OK"; // respon sukses
} else {
    http_response_code(500);
    echo $q;
}
