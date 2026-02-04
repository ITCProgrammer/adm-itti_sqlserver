<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

sqlsrv_query($cond,"UPDATE db_qc.tbl_cocok_warna_dye SET colorist_qcf = '$_POST[value]' WHERE id = '$_POST[pk]'");

echo json_encode('success');
