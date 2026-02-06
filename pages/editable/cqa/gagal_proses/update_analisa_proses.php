<?php
session_start();
include "../../../../koneksi2.php"; // koneksi SQLSRV: $con

if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400);
    echo "Permintaan tidak valid atau sesi telah berakhir.";
    exit();
}

$pk        = $_POST['pk'];
$montemp   = $_POST['montemp'] ?? null;
$hasilcelup= $_POST['hasilcelup'] ?? null; // tidak dipakai di query, tapi biarkan kalau perlu nanti
$schedule  = $_POST['schedule'] ?? null;

$user  = $_SESSION['id10'];
$today = date('Y-m-d H:i:s');

$search  = ["'", '"'];
$replace = ["`", "``"];
$newValue = str_replace($search, $replace, $_POST['value']);

$columnToUpdate = 'analisa_penyebab';

// CEK DATA ADA?
$sqlCek = "SELECT TOP 1 id_hasil_celup
           FROM db_dying.tbl_keterangan_gagalproses
           WHERE id_hasil_celup = ?";

$stmtCek = sqlsrv_query($con, $sqlCek, [$pk]);

if ($stmtCek === false) {
    http_response_code(400);
    echo "Error saat cek data: " . print_r(sqlsrv_errors(), true);
    exit();
}

$exists = sqlsrv_fetch_array($stmtCek, SQLSRV_FETCH_ASSOC) ? true : false;
sqlsrv_free_stmt($stmtCek);

if ($exists) {
    // UPDATE
    $sqlUpdate = "UPDATE db_dying.tbl_keterangan_gagalproses
                  SET {$columnToUpdate} = ?,
                      update_user = ?,
                      update_date = ?
                  WHERE id_hasil_celup = ?";

    $stmtUpdate = sqlsrv_query($con, $sqlUpdate, [$newValue, $user, $today, $pk]);

    if ($stmtUpdate === false) {
        http_response_code(400);
        echo "Error saat update: " . print_r(sqlsrv_errors(), true);
        exit();
    }

    sqlsrv_free_stmt($stmtUpdate);
    http_response_code(200);
    echo "Update berhasil.";

} else {
    // INSERT
    $sqlInsert = "INSERT INTO db_dying.tbl_keterangan_gagalproses
                    (id_hasil_celup, id_montemp, id_schedule, {$columnToUpdate}, creation_user, creation_date)
                  VALUES (?, ?, ?, ?, ?, ?)";

    $stmtInsert = sqlsrv_query($con, $sqlInsert, [$pk, $montemp, $schedule, $newValue, $user, $today]);

    if ($stmtInsert === false) {
        http_response_code(400);
        echo "Error saat insert: " . print_r(sqlsrv_errors(), true);
        exit();
    }

    sqlsrv_free_stmt($stmtInsert);
    http_response_code(200);
    echo "Insert berhasil.";
}

sqlsrv_close($con);
?>
