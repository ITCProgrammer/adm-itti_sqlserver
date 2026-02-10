<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

sqlsrv_query($cond,"UPDATE tbl_cocok_warna_dye SET `hue` = '$_POST[value]' where id = '$_POST[pk]'");

echo json_encode('success');
