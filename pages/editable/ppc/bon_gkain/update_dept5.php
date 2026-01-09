<?php
session_start();
include "../../../../koneksi.php";
if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400); 
    echo "Data tidak lengkap atau sesi tidak valid.";
    exit();
}
$user = $_SESSION['id10'];
$pk = mysqli_real_escape_string($cona, $_POST['pk']);
$newValue = mysqli_real_escape_string($cona, $_POST['value']);
$columnToUpdate = 't_jawab4';

$today = date('Y-m-d H:i:s'); 

$stmt_cek = $cona->prepare("SELECT id FROM tbl_gantikain WHERE id = ?");
$stmt_cek->bind_param("s", $pk);
$stmt_cek->execute();
$stmt_cek->store_result();

if ($stmt_cek->num_rows > 0) {
    $stmt_update = $cona->prepare("UPDATE tbl_gantikain SET {$columnToUpdate} = ?, tgl_update = ? WHERE id = ?");
    $stmt_update->bind_param("sss", $newValue, $today, $pk);
    if ($stmt_update->execute()) {
        http_response_code(200);
        echo "Update berhasil.";
    } else {
        http_response_code(400);
        echo "Error saat update: " . $stmt_update->error;
    }
    $stmt_update->close();
} 

$stmt_cek->close();
$con->close();

?>