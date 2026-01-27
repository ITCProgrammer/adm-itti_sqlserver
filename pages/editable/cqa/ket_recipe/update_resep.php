<?php
session_start();
include "../../../../koneksi.php";

if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400);
    echo "Permintaan tidak valid atau sesi telah berakhir.";
    exit();
}

$pk       = $_POST['pk'];
$newValue = $_POST['value'];
$user     = $_SESSION['user_id10'];
$today    = date('Y-m-d H:i:s');
$columnToUpdate = 'resep';
$sql = "UPDATE db_dying.tbl_schedule SET $columnToUpdate = ? WHERE id = ?";
$params = [$newValue, $pk];
$stmt = sqlsrv_query($con, $sql, $params);

if ($stmt === false) {
    http_response_code(400);
    $errors = sqlsrv_errors();
    echo "Error saat update: " . ($errors[0]['message'] ?? 'Unknown error');
    exit();
}

http_response_code(200);
echo "Update berhasil.";
?>
