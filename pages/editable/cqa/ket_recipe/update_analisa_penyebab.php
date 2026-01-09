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
$columnToUpdate = 'analisa_penyebab';

$stmt_cek = $con->prepare("SELECT id_hasilcelup FROM tbl_hasilcelup2 WHERE id_hasilcelup = ?");
$stmt_cek->bind_param("s", $pk);
$stmt_cek->execute();
$stmt_cek->store_result();

if ($stmt_cek->num_rows > 0) {
    $stmt_update = $con->prepare("UPDATE tbl_hasilcelup2 SET {$columnToUpdate} = ?, update_user = ?, update_time = ? WHERE id_hasilcelup = ?");
    $stmt_update->bind_param("ssss", $newValue, $user, $today, $pk);
    if ($stmt_update->execute()) {
        http_response_code(200);
        echo "Update berhasil.";
    } else {
        http_response_code(400);
        echo "Error saat update: " . $stmt_update->error;
    }
    $stmt_update->close();
} else {
    $stmt_insert = $con->prepare("INSERT INTO tbl_hasilcelup2 (id_hasilcelup, {$columnToUpdate}, insert_user, insert_time) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param("ssss", $pk, $newValue, $user, $today);
    if ($stmt_insert->execute()) {
        http_response_code(200);
        echo "Insert berhasil.";
    } else {
        http_response_code(400);
        echo "Error saat insert: " . $stmt_insert->error;
    }
    $stmt_insert->close();
}

$stmt_cek->close();
$con->close();
?>