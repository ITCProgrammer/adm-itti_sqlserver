<?php
session_start();
include "../../../../koneksi.php";

if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400);
    echo "Permintaan tidak valid atau sesi telah berakhir.";
    exit();
}

$pk = $_POST['pk'];
$newValue = $_POST['value'];
$user = $_SESSION['user_id10'];
$today = date('Y-m-d H:i:s');
$columnToUpdate = 'k_resep';

$sqlCek = "SELECT id FROM db_dying.tbl_hasilcelup WHERE id = ?";
$stmtCek = sqlsrv_query($con, $sqlCek, [$pk]);

if ($stmtCek === false) {
    http_response_code(400);
    echo "Error cek data: " . print_r(sqlsrv_errors(), true);
    exit();
}

$rowCek = sqlsrv_fetch_array($stmtCek, SQLSRV_FETCH_ASSOC);
if (!$rowCek) {
    http_response_code(404);
    echo "Data tidak ditemukan.";
    exit();
}

$sqlUpdate = "UPDATE db_dying.tbl_hasilcelup SET $columnToUpdate = ? WHERE id = ?";
$stmtUpdate = sqlsrv_query($con, $sqlUpdate, [$newValue, $pk]);

if ($stmtUpdate === false) {
    http_response_code(400);
    echo "Error saat update: " . print_r(sqlsrv_errors(), true);
    exit();
}

http_response_code(200);
echo "Update berhasil.";
?>
