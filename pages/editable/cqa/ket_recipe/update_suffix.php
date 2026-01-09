<?php
session_start();
include "../../../../koneksi.php";

if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400);
    echo "Permintaan tidak valid atau sesi telah berakhir.";
    exit();
}

$pk = $_POST['pk'];
$user = $_SESSION['user_id10'];
$today = date('Y-m-d H:i:s');

$search  = ["'", '"'];
$replace = ["`", "``"];
$newValue = str_replace($search, $replace, $_POST['value']);
$columnToUpdate = 'suffix';

$stmt_cek = $con->prepare("SELECT id FROM tbl_schedule WHERE id = ?");
$stmt_cek->bind_param("s", $pk);
$stmt_cek->execute();
$stmt_cek->store_result();

$stmt_update = $con->prepare("UPDATE tbl_schedule SET {$columnToUpdate} = ? WHERE id = ?");
$stmt_update->bind_param("ss", $newValue, $pk);
if ($stmt_update->execute()) {
    http_response_code(200);
    echo "Update berhasil.";
} else {
    http_response_code(400);
    echo "Error saat update: " . $stmt_update->error;
}
$stmt_update->close();

$stmt_cek->close();
$con->close();
?>