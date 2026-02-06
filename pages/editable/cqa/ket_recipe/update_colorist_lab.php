<?php
session_start();
include "../../../../koneksi.php";

if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400);
    echo "Data tidak lengkap atau sesi tidak valid.";
    exit();
}

$user = $_SESSION['user_id10'];
$pk = $_POST['pk'];
$newValue = $_POST['value'];

$columnToUpdate = 'colorist_lab';
$today = date('Y-m-d H:i:s');

$query_cek = "SELECT TOP 1 id_hasilcelup FROM db_dying.tbl_hasilcelup2 WHERE id_hasilcelup = ?";
$test = sqlsrv_query($con, $query_cek, [$pk]);

if ($test === false) {
    http_response_code(400);
    die("SQL ERROR (cek): " . print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($test, SQLSRV_FETCH_ASSOC);

if ($row) {
    $query_update = "UPDATE db_dying.tbl_hasilcelup2
                     SET
                         $columnToUpdate = ?,
                         update_user = ?,
                         update_time = ?
                     WHERE
                         id_hasilcelup = ?";

    $stmt = sqlsrv_query($con, $query_update, [$newValue, $user, $today, $pk]);

    if ($stmt === false) {
        http_response_code(400);
        die("SQL ERROR (update): " . print_r(sqlsrv_errors(), true));
    }

    http_response_code(200);
    echo "Update berhasil.";
} else {
    $query_insert = "INSERT INTO db_dying.tbl_hasilcelup2
                        (id_hasilcelup, $columnToUpdate, insert_user, insert_time)
                     VALUES
                        (?, ?, ?, ?)";

    $stmt = sqlsrv_query($con, $query_insert, [$pk, $newValue, $user, $today]);

    if ($stmt === false) {
        http_response_code(400);
        die("SQL ERROR (insert): " . print_r(sqlsrv_errors(), true));
    }

    http_response_code(200);
    echo "Insert berhasil.";
}
?>
