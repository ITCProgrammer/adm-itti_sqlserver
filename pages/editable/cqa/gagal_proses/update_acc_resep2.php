<?php
session_start();
include "../../../../koneksi.php";
if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400); 
    echo "Data tidak lengkap atau sesi tidak valid.";
    exit();
}
$user = $_SESSION['id10'];
$pk = mysqli_real_escape_string($con, $_POST['pk']);
$montemp = $_POST['montemp'];
$hasilcelup = $_POST['hasilcelup'];
$schedule = $_POST['schedule'];
$newValue = mysqli_real_escape_string($con, $_POST['value']);
$columnToUpdate = 'accresep2';

$today = date('Y-m-d H:i:s'); 

$stmt_cek = $con->prepare("SELECT id_hasil_celup FROM tbl_keterangan_gagalproses WHERE id_hasil_celup = ?");
$stmt_cek->bind_param("s", $pk);
$stmt_cek->execute();
$stmt_cek->store_result();

if ($stmt_cek->num_rows > 0) {
    $stmt_update = $con->prepare("UPDATE tbl_keterangan_gagalproses SET {$columnToUpdate} = ?, update_user = ?, update_date = ? WHERE id_hasil_celup = ?");
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
    $stmt_insert = $con->prepare("INSERT INTO tbl_keterangan_gagalproses (id_hasil_celup, id_montemp, id_schedule, {$columnToUpdate}, creation_user, creation_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("ssssss", $pk, $montemp, $schedule, $newValue, $user, $today);
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