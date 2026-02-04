<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

if (empty($_SESSION['user_id10'])) {
    http_response_code(401);
    echo "Silakan login terlebih dahulu.";
    exit;
}

$ip_num = $_SERVER['REMOTE_ADDR'];
$os     = $_SERVER['HTTP_USER_AGENT'];

$pk    = $_POST['pk'];
$value = $_POST['value'];

$sqlCek = sqlsrv_query(
    $cona,
    "SELECT tg.id_nsp, tb.id_nsp_gk, tb.no_bon
     FROM db_adm.tbl_bonkain tb
     LEFT JOIN db_adm.tbl_gantikain tg ON tg.id = tb.id_nsp
     WHERE tb.id = ?",
    array($pk)
);

$rCek = sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC);
$idnsp   = $rCek['id_nsp'] ?? null;
$idnspgk = $rCek['id_nsp_gk'] ?? null;

sqlsrv_query(
    $cona,
    "UPDATE db_adm.tbl_bonkain
     SET analisa = ?
     WHERE id = ?",
    array($value, $pk)
);

if ($idnspgk !== null && $idnspgk !== '') {
    sqlsrv_query(
        $cond,
        "UPDATE db_qc.tbl_ganti_kain_now
         SET analisa = ?
         WHERE id = ?",
        array($value, $idnspgk)
    );
}

sqlsrv_query(
    $cona,
    "INSERT INTO db_adm.tbl_log (what, what_do, project, do_by, do_at, ip, os)
     VALUES (?, ?, ?, ?, GETDATE(), ?, ?)",
    array(
        'Update Analisa',
        "Edit Analisa ( $value )",
        $rCek['no_bon'] ?? '',
        $_SESSION['user_id10'],
        $ip_num,
        $os
    )
);

echo json_encode('success');