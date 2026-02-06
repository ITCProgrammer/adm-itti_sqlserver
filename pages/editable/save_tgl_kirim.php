<?php
include '../../koneksi.php';
session_start();

$pk        = $_POST['pk'] ?? '';
$tgl_kirim = $_POST['value'] ?? '';

if ($pk === '' || $tgl_kirim === '') {
    http_response_code(400);
    exit('Invalid');
}

$sql = "UPDATE db_adm.tbl_firstlot
        SET tgl_kirim = CAST(? AS date),
            lastupdatetime = GETDATE(),
            lastupdateuser = ?
        WHERE demand = ?";

$params = [$tgl_kirim, ($_SESSION['nama10'] ?? ''), $pk];
$stmt   = sqlsrv_query($cona, $sql, $params);

if ($stmt === false) {
    http_response_code(500);
    echo "DB Error";
    echo print_r(sqlsrv_errors(), true);
    exit;
}

echo "OK";



