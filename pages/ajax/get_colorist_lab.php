<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
$query = "SELECT name FROM db_adm.tbl_user_colorist WHERE status_active ='1' AND dept ='LAB'";
$result = sqlsrv_query($cona, $query);
$options = [];
while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $options[] = ['value' => $row['name'], 'text' => $row['name']];
}
header('Content-Type: application/json');
echo json_encode($options);
?>
