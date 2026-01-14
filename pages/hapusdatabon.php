<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");

$modal_id = $_GET['id'];
$qry = sqlsrv_query($cona, "SELECT id_nsp FROM db_adm.tbl_bonkain WHERE id = ?", [$modal_id]);
if ($qry === false) {
    die(print_r(sqlsrv_errors(), true));
}
$r = sqlsrv_fetch_array($qry, SQLSRV_FETCH_ASSOC);
$id = $r ? $r['id_nsp'] : '';
if ($id === '') {
    echo "<script>alert('Data bon tidak ditemukan');window.location='index1.php?p=input-bon-kain';</script>";
    exit;
}
$modal = sqlsrv_query($cona, "DELETE FROM db_adm.tbl_bonkain WHERE id = ?", [$modal_id]);
if ($modal === false) {
    echo "<script>alert('Gagal Hapus');window.location='index1.php?p=input-bon-kain&id=$id';</script>";
    exit;
}
echo "<script>window.location='index1.php?p=input-bon-kain&id=$id';</script>";
