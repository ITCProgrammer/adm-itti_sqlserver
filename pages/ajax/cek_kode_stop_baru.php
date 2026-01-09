<?php
// Aktifkan error reporting untuk debugging
ini_set("display_errors", 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Pastikan respons dikirim dalam format JSON

include "../../koneksi.php"; // Pastikan file ini benar-benar memuat koneksi DB2

if (isset($_POST['stopcode']) && isset($_POST['nokk'])) {
    $stopcode = $_POST['stopcode'];
    $nokk = $_POST['nokk'];

    // Query untuk cek kode_stop berdasarkan nokk
    $sqlCek = mysqli_query($cona, "SELECT * FROM tbl_stoppage WHERE nokk='$nokk' AND kode_stop='$stopcode' ORDER BY id DESC LIMIT 1");
    $cek = mysqli_num_rows($sqlCek);

    if ($cek > 0) {
        $rcek = mysqli_fetch_array($sqlCek);

        echo json_encode([
            "success" => true,
            "stop_mulai_jam" => $rcek['stop_mulai_jam'], 
            "stop_mulai_tgl" => $rcek['stop_mulai_tgl'],
			"stop_selesai_jam" => $rcek['stop_selesai_jam'], 
            "stop_selesai_tgl" => $rcek['stop_selesai_tgl']
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Data tidak ditemukan!"
        ]);
    }
}
?>
