<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$qry=mysqli_query($cona,"SELECT id_nsp FROM tbl_bonkain WHERE id='$modal_id'");
	$r=mysqli_fetch_array($qry);
	$id=$r['id_nsp'];
    $modal=mysqli_query($cona,"DELETE FROM tbl_bonkain WHERE id='$modal_id' ");
    if ($modal) {
        echo "<script>window.location='index1.php?p=input-bon-kain&id=$id';</script>";
    } else {
        echo "<script>alert('Gagal Hapus');window.location='index1.php?p=input-bon-kain&id=$id';</script>";
    }
