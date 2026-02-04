<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

$value = $_POST['value'];
$pk    = $_POST['pk'];

sqlsrv_query(
    $cona,
    "UPDATE db_adm.tbl_bonkain
     SET pjg1 = ?
     WHERE id = ?",
    array($value, $pk)
);

echo json_encode('success');