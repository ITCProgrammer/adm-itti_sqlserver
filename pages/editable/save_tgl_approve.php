<?php
include '../../koneksi.php';
session_start();

$pk          = $_POST['pk'] ?? '';
$tgl_approve = $_POST['value'] ?? '';

if ($pk === '' || $tgl_approve === '') {
    http_response_code(400);
    exit('Invalid');
}

$sql = "UPDATE db_adm.tbl_firstlot
        SET tgl_approved   = CAST(? AS date),
            lastupdatetime = GETDATE(),
            lastupdateuser = ?
        WHERE demand = ?";

$params = [
    $tgl_approve,
    ($_SESSION['nama10'] ?? ''),
    $pk
];

$stmt = sqlsrv_query($cona, $sql, $params);

if ($stmt === false) {
    http_response_code(500);
    echo print_r(sqlsrv_errors(), true);
    echo "DB Error";
    exit;
}

echo "OK";
