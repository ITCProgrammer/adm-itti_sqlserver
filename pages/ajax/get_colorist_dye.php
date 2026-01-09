<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
$query = "SELECT name FROM tbl_user_colorist WHERE status_active ='1' AND dept ='DYE'";
$result = mysqli_query($cona, $query);
$options = [];
while($row = mysqli_fetch_assoc($result)) {
    $options[] = ['value' => $row['name'], 'text' => $row['name']];
}
header('Content-Type: application/json');
echo json_encode($options);
?>
