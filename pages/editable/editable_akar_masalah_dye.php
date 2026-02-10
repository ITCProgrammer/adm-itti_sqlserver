<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

sqlsrv_query($cond,"UPDATE db_qc.tbl_ncp_qcf_now SET akar_masalah_dye = '$_POST[value]' where id = '$_POST[pk]'");

echo json_encode('success');
