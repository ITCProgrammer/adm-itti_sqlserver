<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
$rcode = $_POST['rcode'];
$sql = mysqli_query($cond,"SELECT warna from db_lab.tbl_matching WHERE no_resep = '$rcode'");
$result = mysqli_fetch_array($sql);
$response = json_encode($result['warna']);
echo $response;
