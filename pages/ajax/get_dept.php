<?php
header('Content-Type: application/json; charset=utf-8');
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

$options = [];
$query = "SELECT nama FROM db_adm.tbl_dept WHERE status_aktif = 1 ORDER BY nama";
$result = sqlsrv_query($cona, $query);

if ($result === false) {
    http_response_code(500);
    echo json_encode(['error' => "Query gagal dijalankan: " . print_r(sqlsrv_errors(), true)]);
    exit;
}

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $nama = $row['nama'];
    $options[] = ['value' => $nama, 'text' => $nama];
}

sqlsrv_free_stmt($result);
echo json_encode($options);
exit;
?>
