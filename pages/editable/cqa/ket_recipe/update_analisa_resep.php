<?php
session_start();
include "../../../../koneksi.php";

if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400);
    echo "Permintaan tidak valid atau sesi telah berakhir.";
    exit();
}

$pk = $_POST['pk'];

$search  = ["'", '"'];
$replace = ["`", "``"];
$newValue = str_replace($search, $replace, $_POST['value']);

$columnToUpdate = 'analisa_resep';

$sqlCek  = "SELECT TOP 1 id FROM db_dying.tbl_hasilcelup WHERE id = ?";
$stmtCek = sqlsrv_query($con, $sqlCek, [$pk]);

if ($stmtCek === false) {
    http_response_code(400);
    die("Error cek data: " . print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmtCek, SQLSRV_FETCH_ASSOC);
if (!$row) {
    http_response_code(404);
    echo "Data tidak ditemukan.";
    exit();
}

$sqlUpdate  = "UPDATE db_dying.tbl_hasilcelup SET $columnToUpdate = ? WHERE id = ?";
$stmtUpdate = sqlsrv_query($con, $sqlUpdate, [$newValue, $pk]);

if ($stmtUpdate === false) {
    http_response_code(400);
    die("Error saat update: " . print_r(sqlsrv_errors(), true));
}

http_response_code(200);
echo "Update berhasil.";
?>
