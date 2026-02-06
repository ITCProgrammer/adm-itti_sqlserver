<?php
session_start();
include "../../../../koneksi.php";

if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400);
    echo "Permintaan tidak valid atau sesi telah berakhir.";
    exit();
}

$pk   = $_POST['pk'];
$user = $_SESSION['user_id10'];
$today = date('Y-m-d H:i:s');

$search  = ["'", '"'];
$replace = ["`", "``"];
$newValue = str_replace($search, $replace, $_POST['value']);

$columnToUpdate = 'analisa_penyebab';

$sqlCek  = "SELECT TOP 1 id_hasilcelup FROM db_dying.tbl_hasilcelup2 WHERE id_hasilcelup = ?";
$stmtCek = sqlsrv_query($con, $sqlCek, [$pk]);

if ($stmtCek === false) {
    http_response_code(400);
    die("SQL ERROR (cek): " . print_r(sqlsrv_errors(), true));
}

$rowCek = sqlsrv_fetch_array($stmtCek, SQLSRV_FETCH_ASSOC);

if ($rowCek) {
    $sqlUpdate = "UPDATE db_dying.tbl_hasilcelup2
                  SET {$columnToUpdate} = ?, update_user = ?, update_time = ?
                  WHERE id_hasilcelup = ?";

    $stmtUpdate = sqlsrv_query($con, $sqlUpdate, [$newValue, $user, $today, $pk]);

    if ($stmtUpdate === false) {
        http_response_code(400);
        die("SQL ERROR (update): " . print_r(sqlsrv_errors(), true));
    }

    http_response_code(200);
    echo "Update berhasil.";
} else {
    $sqlInsert = "INSERT INTO db_dying.tbl_hasilcelup2 (id_hasilcelup, {$columnToUpdate}, insert_user, insert_time)
                  VALUES (?, ?, ?, ?)";

    $stmtInsert = sqlsrv_query($con, $sqlInsert, [$pk, $newValue, $user, $today]);

    if ($stmtInsert === false) {
        http_response_code(400);
        die("SQL ERROR (insert): " . print_r(sqlsrv_errors(), true));
    }

    http_response_code(200);
    echo "Insert berhasil.";
}
?>
