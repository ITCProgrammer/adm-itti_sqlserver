<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
$query = "SELECT id, nama FROM user_acc_resep WHERE status_active ='1'";
$result = mysqli_query($con, $query);
$options = [];
while($row = mysqli_fetch_assoc($result)) {
    $options[] = ['value' => $row['id'], 'text' => $row['nama']];
}
header('Content-Type: application/json');
echo json_encode($options);
?>
