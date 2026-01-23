<?php
include '../../koneksi.php';
session_start();

$pk = $_POST['pk'] ?? '';
$comm_duc = $_POST['value'] ?? '';
$demand = $_POST['demand'] ?? '';

if ($demand === '') {
    http_response_code(400);
    echo "Demand kosong";
    exit;
}

$checkSql = "SELECT TOP 1 1 AS exist FROM db_adm.tbl_firstlot WHERE demand = ?";
$checkStmt = sqlsrv_query($cona, $checkSql, [$demand]);
if ($checkStmt === false) {
    http_response_code(500);
    echo "Database Error (check)";
    exit;
}
$exists = (sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC) !== null);

if (!$exists) {
    http_response_code(400);
    echo "Wajib mengisi production demand terlebih dahulu";
    exit;
}

$updSql = "UPDATE db_adm.tbl_firstlot
           SET comm_duc = ?,
               lastupdatetime = GETDATE(),
               lastupdateuser = ?
           WHERE demand = ?";
$updStmt = sqlsrv_query($cona, $updSql, [$comm_duc, ($_SESSION['nama10'] ?? ''), $demand]);

if ($updStmt === false) {
    http_response_code(500);
    echo "Database Error (update)";
    exit;
}

echo "OK";
