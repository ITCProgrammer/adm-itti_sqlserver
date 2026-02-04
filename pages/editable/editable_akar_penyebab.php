<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

$pk    = $_POST['pk'];
$value = $_POST['value'];

$sqlCek = sqlsrv_query(
    $cona,
    "SELECT tg.id_nsp, tb.id_nsp_gk
     FROM db_adm.tbl_bonkain tb
     LEFT JOIN db_adm.tbl_gantikain tg ON tg.id = tb.id_nsp
     WHERE tb.id = ?",
    array($pk)
);

$rCek = sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC);

$idnsp   = isset($rCek['id_nsp']) ? $rCek['id_nsp'] : null;
$idnspgk = isset($rCek['id_nsp_gk']) ? $rCek['id_nsp_gk'] : null;

sqlsrv_query(
    $cona,
    "UPDATE db_adm.tbl_bonkain
     SET akar_penyebab = ?
     WHERE id = ?",
    array($value, $pk)
);

if ($idnspgk !== null && $idnspgk !== '') {
    sqlsrv_query(
        $cond,
        "UPDATE db_qc.tbl_ganti_kain_now
         SET akar_penyebab = ?
         WHERE id = ?",
        array($value, $idnspgk)
    );
}

echo json_encode('success');
