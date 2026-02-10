<?php
session_start();
include "../../../../koneksi.php";

if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400);
    echo "Data tidak lengkap atau sesi tidak valid.";
    exit();
}

$user = $_SESSION['id10'];

$pk = $_POST['pk'];
$newValue = $_POST['value'];

$columnToUpdate = 'persen3';

$sql_cek = "SELECT 1 FROM db_adm.tbl_gantikain WHERE id = ?";
$stmt_cek = sqlsrv_query($cona, $sql_cek, [$pk]);

if ($stmt_cek === false) {
    http_response_code(500);
    echo "Error saat cek data: " . print_r(sqlsrv_errors(), true);
    exit();
}

$exists = (sqlsrv_fetch($stmt_cek) !== false);
sqlsrv_free_stmt($stmt_cek);

if ($exists) {
    $sql_update = "UPDATE db_adm.tbl_gantikain
                    SET {$columnToUpdate} = ?, tgl_update = SYSDATETIME()
                    WHERE id = ?";
    $stmt_update = sqlsrv_query($cona, $sql_update, [$newValue, $pk]);

    if ($stmt_update === false) {
        http_response_code(400);
        echo "Error saat update: " . print_r(sqlsrv_errors(), true);
        exit();
    }

    sqlsrv_free_stmt($stmt_update);

    http_response_code(200);
    echo "Update berhasil.";
} else {
    http_response_code(404);
    echo "Data tidak ditemukan.";
}

sqlsrv_close($cona);
?>