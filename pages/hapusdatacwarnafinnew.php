<?php
    ini_set("error_reporting", 1);
    session_start();
    include("../koneksi.php");
    $modal_id=$_POST['id'];
    $modal="DELETE FROM db_qc.tbl_lap_inspeksi WHERE id='$modal_id' ";
    $result = sqlsrv_query($cond,$modal) or die(sqlsrv_errors());
    //if ($modal) {
    //    echo "<script>window.location='LihatCWarnaFin';</script>";
    //} else {
    //    echo "<script>alert('Gagal Hapus');window.location='LihatCWarnaFin';</script>";
    //}
?>
