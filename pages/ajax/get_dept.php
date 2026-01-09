<?php
header('Content-Type: application/json; charset=utf-8');
include "../../koneksi.php";

// ... (kode pengecekan koneksi Anda sudah benar) ...
if ($cona->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => "Koneksi database gagal: " . $cona->connect_error]);
    exit;
}

$query = "SELECT nama FROM tbl_dept WHERE status_aktif = 1 ORDER BY nama";
$result = mysqli_query($cona, $query);

if ($result) {
    $options = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $nama = $row['nama'];
        $options[] = ['value' => $nama, 'text' => $nama];
    }
    echo json_encode($options);
} else {
    http_response_code(500);
    echo json_encode(['error' => "Query gagal dijalankan: " . mysqli_error($cona)]);
}

mysqli_close($cona);
exit;
?>