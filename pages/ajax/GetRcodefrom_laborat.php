<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

$search = $_GET['search'];
$sql = mysqli_query($cond,"select no_resep, warna from db_lab.tbl_matching WHERE no_resep LIKE '%$search%' ORDER BY id desc");
$result = mysqli_num_rows($sql);

if ($result > 0) {
    $list = array();
    $key = 0;
    while ($row = mysqli_fetch_array($sql)) {
        $list[$key]['id'] = $row['no_resep'];
        $list[$key]['text'] = $row['no_resep'];
        $key++;
    }
    echo json_encode($list);
} else {
    echo "Keyword tidak cocok!";
}
