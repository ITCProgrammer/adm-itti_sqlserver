<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

sqlsrv_query($cond,"UPDATE db_qc.tbl_lap_inspeksi SET proses = '$_POST[value]' where id = '$_POST[pk]'");

echo json_encode('success');
