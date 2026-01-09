<?php
session_start();
include "../../../../koneksi.php";
if (!isset($_SESSION['user_id10']) || !isset($_POST['pk']) || !isset($_POST['value'])) {
    http_response_code(400); 
    echo "Data tidak lengkap atau sesi tidak valid.";
    exit();
}

$user = $_SESSION['user_id10'];
$pk = mysqli_real_escape_string($con, $_POST['pk']);
$newValue = mysqli_real_escape_string($con, $_POST['value']);
$columnToUpdate = 'colorist_dye';

$today = date('Y-m-d H:i:s'); 

$query_cek = "SELECT id_hasilcelup FROM tbl_hasilcelup2 WHERE id_hasilcelup = '$pk'";
$test = mysqli_query($con, $query_cek);
$datas = mysqli_num_rows($test);

if ($datas > 0) {
    $query_update = "UPDATE tbl_hasilcelup2 
                     SET 
                         $columnToUpdate = '$newValue', 
                         update_user = '$user', 
                         update_time = '$today'
                     WHERE 
                         id_hasilcelup = '$pk'";

    if (mysqli_query($con, $query_update)) {
        http_response_code(200);
        echo "Update berhasil.";
    } else {
        http_response_code(400);
        echo "Error Update: " . mysqli_error($con);
    }
} else {
    $query_insert = "INSERT INTO tbl_hasilcelup2 
                     SET 
                         id_hasilcelup = '$pk',
                         $columnToUpdate = '$newValue', 
                         insert_user = '$user', 
                         insert_time = '$today'";

    if (mysqli_query($con, $query_insert)) {
        http_response_code(200);
        echo "Insert berhasil.";
    } else {
        http_response_code(400);
        echo "Error Insert: " . mysqli_error($con);
    }
}
?>