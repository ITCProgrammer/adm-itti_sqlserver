<?php
session_start();
include "../../koneksi.php";

$id_schedule    = $_POST['id_schedule'] ?? null;
$id_montemp     = $_POST['id_montemp'] ?? null;
$id_hasil_celup = $_POST['id_hasil_celup'] ?? null;

$analisa    = $_POST['analisa_penyebab'] ?? '';
$dept_arr   = $_POST['dept_penyebab'] ?? [];
$keterangan = $_POST['keterangan_gagal_proses'] ?? '';
$accresep   = $_POST['accresep'] ?? null;

$dept = is_array($dept_arr) ? implode(",", $dept_arr) : (string)$dept_arr;
$user = $_SESSION['id10'] ?? null;

if (!$id_schedule || !$id_montemp || !$id_hasil_celup) {
    http_response_code(400);
    echo "error: missing required params";
    exit;
}

$sqlCek = "SELECT TOP 1 1
           FROM db_dying.tbl_keterangan_gagalproses
           WHERE id_hasil_celup = ?";
$stmtCek = sqlsrv_query($con, $sqlCek, [$id_hasil_celup]);
if ($stmtCek === false) {
    http_response_code(500);
    echo "error: " . print_r(sqlsrv_errors(), true);
    exit;
}
$exists = (sqlsrv_fetch_array($stmtCek, SQLSRV_FETCH_NUMERIC) !== null);
sqlsrv_free_stmt($stmtCek);

if ($exists) {
    // Update
    $sql = "UPDATE db_dying.tbl_keterangan_gagalproses
            SET analisa_penyebab        = ?,
                dept_penyebab           = ?,
                keterangan_gagal_proses = ?,
                accresep                = ?,
                update_user             = ?,
                update_date             = GETDATE()
            WHERE id_hasil_celup = ?";

    $params = [
        $analisa,
        $dept,
        $keterangan,
        $accresep,
        $user,
        $id_hasil_celup
    ];
} else {
    // Insert
    $sql = "INSERT INTO db_dying.tbl_keterangan_gagalproses
            (id_montemp, id_schedule, id_hasil_celup,
             analisa_penyebab, dept_penyebab, keterangan_gagal_proses, accresep,
             creation_user, creation_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, GETDATE())";

    $params = [
        $id_montemp,
        $id_schedule,
        $id_hasil_celup,
        $analisa,
        $dept,
        $keterangan,
        $accresep,
        $user
    ];
}

$stmt = sqlsrv_query($con, $sql, $params);
if ($stmt === false) {
    http_response_code(500);
    echo "error: " . print_r(sqlsrv_errors(), true);
    exit;
}
sqlsrv_free_stmt($stmt);

echo "success";
?>
