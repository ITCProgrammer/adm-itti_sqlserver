<?php
include '../../koneksi.php';
session_start();

$pk    = $_POST['pk'] ?? '';
$tgl_approve = $_POST['value'] ?? '';

if (!$pk || !$tgl_approve) {
    http_response_code(400);
    exit('Invalid');
}

$q = "UPDATE tbl_firstlot SET tgl_approved ='".addslashes($tgl_approve)."', lastupdatetime = NOW(), lastupdateuser = '".addslashes($_SESSION['nama10'])."' WHERE demand = '$pk' ";


if (mysqli_query($cona, $q)) {
    echo "OK";
} else {
    http_response_code(500);
    echo "DB Error: " . mysqli_error($cona);
}