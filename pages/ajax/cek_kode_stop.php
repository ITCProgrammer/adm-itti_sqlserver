<?php
// Aktifkan error reporting untuk debugging
ini_set("display_errors", 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Pastikan respons dikirim dalam format JSON

include "../../koneksi.php"; // Pastikan file ini benar-benar memuat koneksi DB2

if (isset($_POST['stopcode']) && isset($_POST['nokk'])) {
    $stopcode = $_POST['stopcode'];
    $nokk     = $_POST['nokk'];

    $sqlCek = sqlsrv_query(
        $cona,
        "SELECT TOP 1 * FROM db_adm.tbl_stoppage WHERE nokk = ? AND kode_stop = ? ORDER BY id DESC",
        [$nokk, $stopcode]
    );

    // kalau query error
    if ($sqlCek === false) {
        echo json_encode([
            "success" => false,
            "message" => "Query error",
            "errors"  => sqlsrv_errors()
        ]);
        exit;
    }

    $rcek = sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC);

    if ($rcek) {
        echo json_encode([
            "success" => true,
            "stop_mulai_jam"    => $rcek['stop_mulai_jam'] ?? "",
            "stop_mulai_tgl"    => $rcek['stop_mulai_tgl'] ?? "",
            "stop_selesai_jam"  => $rcek['stop_selesai_jam'] ?? "",
            "stop_selesai_tgl"  => $rcek['stop_selesai_tgl'] ?? ""
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Data tidak ditemukan!"
        ]);
    }

    sqlsrv_free_stmt($sqlCek);
    exit;
}

echo json_encode([
    "success" => false,
    "message" => "Parameter stopcode/nokk tidak lengkap"
]);
?>
