<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

sqlsrv_query($cond,"UPDATE tbl_tempel_beda_roll SET `catatan` = '$_POST[value]' where id = '$_POST[pk]'");

echo json_encode('success');
