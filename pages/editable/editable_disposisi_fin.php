<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

mysqli_query($cond,"UPDATE tbl_lap_inspeksi SET `disposisi` = '$_POST[value]' where id = '$_POST[pk]'");

echo json_encode('success');
