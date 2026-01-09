<?php
session_start();
include "../../koneksi.php"; // Pastikan file ini benar-benar memuat koneksi DB2

$id_schedule = $_POST['id_schedule'];
$id_montemp = $_POST['id_montemp'];
$id_hasil_celup = $_POST['id_hasil_celup'];
$analisa = mysqli_real_escape_string($con, $_POST['analisa_penyebab']);
$dept_arr = $_POST['dept_penyebab'] ?? [];
$keterangan = mysqli_real_escape_string($con, $_POST['keterangan_gagal_proses']);
$accresep = $_POST['accresep'];
$dept = implode(",", $dept_arr);
$user = $_SESSION['id10'];

// Cek apakah data sudah ada
$cek = mysqli_query($con, "SELECT * FROM tbl_keterangan_gagalproses WHERE id_hasil_celup = '$id_hasil_celup'");
if (mysqli_num_rows($cek) > 0) {
    // Update
    $sql = "UPDATE tbl_keterangan_gagalproses SET 
        analisa_penyebab='$analisa',
        dept_penyebab='$dept',
        keterangan_gagal_proses='$keterangan',
        accresep='$accresep',
        update_user='$user',
        update_date=NOW()
        WHERE id_hasil_celup='$id_hasil_celup'";
} else {
    // Insert
    $sql = "INSERT INTO tbl_keterangan_gagalproses 
    (id_montemp, id_schedule, id_hasil_celup, analisa_penyebab, dept_penyebab, keterangan_gagal_proses, accresep, creation_user, creation_date) 
    VALUES 
    ('$id_montemp', '$id_schedule', '$id_hasil_celup', '$analisa', '$dept', '$keterangan', '$accresep', '$user', NOW())";
}

if (mysqli_query($con, $sql)) {
    echo "success";
} else {
    echo "error: " . mysqli_error($con);
}
?>
