<?php
// Aktifkan error reporting untuk debugging
ini_set("display_errors", 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Pastikan respons dikirim dalam format JSON

include "../../koneksi.php"; // Pastikan file ini benar-benar memuat koneksi DB2

// Pastikan 'operationCode' dan 'noKK' dikirim melalui POST
if (isset($_POST['operationCode']) && isset($_POST['noKK'])) {
    $operationCode = trim($_POST['operationCode']);
    $nokk = trim($_POST['noKK']);

    // Pastikan koneksi database tersedia
    if (!$conn2) {
        echo json_encode(['error' => 'Database connection error']);
        exit;
    }

    // Query untuk mendapatkan waktu mulai proses
    $sqlMulai = "SELECT 
				TRIM(p.MACHINECODE) AS MACHINECODE, p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME, p.OPERATORCODE, r.LONGDESCRIPTION AS NAMA
				FROM DB2ADMIN.PRODUCTIONPROGRESS AS p
				LEFT OUTER JOIN DB2ADMIN.RESOURCES AS r ON p.OPERATORCODE = r.CODE
				WHERE p.PRODUCTIONORDERCODE = ?
				AND p.OPERATIONCODE = ? 
				AND p.PROGRESSTEMPLATECODE = 'S01'";

    // Query untuk mendapatkan waktu selesai proses
    $sqlSelesai = "SELECT TRIM(p.MACHINECODE) AS MACHINECODE, p.PROGRESSENDDATE , p.PROGRESSENDTIME, p.OPERATORCODE, r.LONGDESCRIPTION AS NAMA
				FROM DB2ADMIN.PRODUCTIONPROGRESS AS p 
				LEFT OUTER JOIN DB2ADMIN.RESOURCES AS r ON p.OPERATORCODE = r.CODE            
				WHERE p.PRODUCTIONORDERCODE = ? 
				AND p.OPERATIONCODE = ?
				AND p.PROGRESSTEMPLATECODE = 'E01'";

    // Siapkan statement pertama
    $stmtMulai = db2_prepare($conn2, $sqlMulai);
    if (!$stmtMulai) {
        echo json_encode(['error' => 'Error preparing query (Mulai): ' . htmlspecialchars(db2_stmt_errormsg())]);
        exit;
    }

    // Eksekusi statement pertama
    $executeMulai = db2_execute($stmtMulai, [$nokk, $operationCode]);
    if (!$executeMulai) {
        echo json_encode(['error' => 'Error executing query (Mulai): ' . htmlspecialchars(db2_stmt_errormsg())]);
        exit;
    }

    // Ambil hasil query pertama
    $dataMulai = db2_fetch_assoc($stmtMulai);
    db2_free_stmt($stmtMulai); // Bebaskan resource statement pertama

    // Siapkan statement kedua
    $stmtSelesai = db2_prepare($conn2, $sqlSelesai);
    if (!$stmtSelesai) {
        echo json_encode(['error' => 'Error preparing query (Selesai): ' . htmlspecialchars(db2_stmt_errormsg())]);
        exit;
    }

    // Eksekusi statement kedua
    $executeSelesai = db2_execute($stmtSelesai, [$nokk, $operationCode]);
    if (!$executeSelesai) {
        echo json_encode(['error' => 'Error executing query (Selesai): ' . htmlspecialchars(db2_stmt_errormsg())]);
        exit;
    }

    // Ambil hasil query kedua
    $dataSelesai = db2_fetch_assoc($stmtSelesai);
    db2_free_stmt($stmtSelesai); // Bebaskan resource statement kedua

    // Gabungkan hasil kedua query
    $response = array_merge($dataMulai ?: [], $dataSelesai ?: []);

    // Periksa apakah ada data yang ditemukan
    if (!empty($response)) {
        echo json_encode($response); // Kirim data dalam format JSON
    } else {
        echo json_encode(['error' => 'No data found for the given operation code']);
    }
} else {
    echo json_encode(['error' => 'Missing required parameters (operationCode or noKK)']);
}
?>
