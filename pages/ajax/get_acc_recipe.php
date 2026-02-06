<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

$query = "SELECT id, nama FROM db_dying.user_acc_resep WHERE status_active = 1 ORDER BY nama";
$result = sqlsrv_query($con, $query);

if ($result === false) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => "Query gagal dijalankan: " . print_r(sqlsrv_errors(), true)]);
    exit;
}

$options = [];
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $options[] = ['value' => $row['id'], 'text' => $row['nama']];
}

sqlsrv_free_stmt($result);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($options);
exit;
?>
