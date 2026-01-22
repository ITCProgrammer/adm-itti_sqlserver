<?php
include '../../koneksi.php';
session_start();

// data dari X-Editable
$pk  = $_POST['pk'] ?? '';
$lot = $_POST['value'] ?? '';   // lot terpilih

// data tambahan (dikirim lewat data-params)
$demand = $_POST['demand'] ?? '';

$lot = trim((string)$lot);
$demand = trim((string)$demand);

if ($demand === '') {
    http_response_code(400);
    echo "Wajib mengisi production demand terlebih dahulu";
    exit;
}

$updUser = $_SESSION['nama10'] ?? '';

// cek apakah demand ada
$qCheck = "SELECT TOP 1 1 AS ok FROM db_adm.tbl_firstlot WHERE demand = ?";
$stmtCheck = sqlsrv_query($cona, $qCheck, [$demand]);
if ($stmtCheck === false) {
    http_response_code(500);
    echo "Database Error (check)";
    exit;
}

$exists = (sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC) !== null);

if (!$exists) {
    http_response_code(400);
    echo "Wajib mengisi production demand terlebih dahulu";
    exit;
}

// update lot
$q = "
    UPDATE db_adm.tbl_firstlot
    SET lot = ?,
        lastupdatetime = GETDATE(),
        lastupdateuser = ?
    WHERE demand = ?
";
$stmt = sqlsrv_query($cona, $q, [$lot, $updUser, $demand]);

if ($stmt === false) {
    http_response_code(500);
    echo "Database Error";
    exit;
}

echo "OK";
