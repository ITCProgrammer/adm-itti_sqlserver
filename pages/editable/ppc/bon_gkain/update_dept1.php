<?php
session_start();
include "../../../../koneksi.php"; 

header('Content-Type: text/plain; charset=utf-8');

if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400);
    echo "Data tidak lengkap atau sesi tidak valid.";
    exit();
}

$user = $_SESSION['id10'] ?? null;

$pk = $_POST['pk'];
$newValue = $_POST['value'];

$columnToUpdate = 't_jawab';

$sqlCek = "SELECT 1 FROM db_adm.tbl_gantikain WHERE id = ?";
$stmtCek = sqlsrv_query($cona, $sqlCek, [$pk]);

if ($stmtCek === false) {
    http_response_code(500);
    echo "Error saat cek data: " . print_r(sqlsrv_errors(), true);
    exit();
}

$exists = (sqlsrv_fetch($stmtCek) !== false);
sqlsrv_free_stmt($stmtCek);

if ($exists) {
    $sqlUpdate = "UPDATE db_adm.tbl_gantikain
                  SET {$columnToUpdate} = ?, tgl_update = SYSDATETIME()
                  WHERE id = ?";

    $stmtUpdate = sqlsrv_query($cona, $sqlUpdate, [$newValue, $pk]);

    if ($stmtUpdate === false) {
        http_response_code(400);
        echo "Error saat update: " . print_r(sqlsrv_errors(), true);
        exit();
    }

    sqlsrv_free_stmt($stmtUpdate);

    http_response_code(200);
    echo "Update berhasil.";
} else {
    http_response_code(404);
    echo "Data tidak ditemukan.";
}

sqlsrv_close($cona);
?>