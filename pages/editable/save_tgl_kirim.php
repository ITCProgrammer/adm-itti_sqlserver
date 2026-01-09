<?php
include '../../koneksi.php';
session_start();

$pk    = $_POST['pk'] ?? '';
$tgl_kirim = $_POST['value'] ?? '';

if (!$pk || !$tgl_kirim) {
    http_response_code(400);
    exit('Invalid');
}

$q = "UPDATE tbl_firstlot SET tgl_kirim ='".addslashes($tgl_kirim)."', lastupdatetime = NOW(), lastupdateuser = '".addslashes($_SESSION['nama10'])."' WHERE demand = '$pk' ";


if (mysqli_query($cona, $q)) {
    echo "OK";
} else {
    http_response_code(500);
    echo "DB Error: " . mysqli_error($cona);
}