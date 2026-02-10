<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

sqlsrv_query($cond,"UPDATE db_qc.tbl_cocok_warna_dye SET tgl_celup = '$_POST[value]' where id = '$_POST[pk]'");

echo json_encode('success');
