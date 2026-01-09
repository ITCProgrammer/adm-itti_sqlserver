<?php
include '../../koneksi.php';
session_start();

// data dari X-Editable
$pk     = $_POST['pk'] ?? '';     // contoh: "SO123|10"
$demand = $_POST['value'] ?? '';  // demand terpilih

// data tambahan (dikirim lewat data-params)
$buyer        = $_POST['buyer'] ?? '';
$salesorder   = $_POST['salesorder'] ?? '';
$style        = $_POST['garment_style'] ?? '';
$no_po        = $_POST['no_po'] ?? '';
$item         = $_POST['item'] ?? '';
$warna        = $_POST['warna'] ?? '';
$season       = $_POST['season'] ?? '';

// cek apakah record sudah ada
$qCheck = "SELECT 1 FROM tbl_firstlot WHERE buyer = '$buyer' 
                                        AND `order` = '$salesorder'
                                        AND style  = '$style'
                                        AND po     = '$no_po'
                                        AND item   = '$item'
                                        AND warna  = '$warna'
                                        AND season = '$season'";
$res = mysqli_query($cona, $qCheck);

if (mysqli_fetch_assoc($res)) {
    // update demand + identitas
    $q = "UPDATE tbl_firstlot SET 
                demand  ='".addslashes($demand)."',
                buyer   ='".addslashes($buyer)."',
                `order` ='".addslashes($salesorder)."',
                style   ='".addslashes($style)."',
                po      ='".addslashes($no_po)."',
                item    ='".addslashes($item)."',
                warna   ='".addslashes($warna)."',
                season  ='".addslashes($season)."'
            WHERE buyer = '$buyer' 
                AND `order`  = '$salesorder'
                AND style  = '$style'
                AND po     = '$no_po'
                AND item   = '$item'
                AND warna  = '$warna'
                AND season = '$season'";
} else {
    // insert baru
    $q = "INSERT INTO tbl_firstlot 
          (demand,buyer,`order`,style,po,item,warna,season,creationuser) VALUES (
          '".addslashes($demand)."',
          '".addslashes($buyer)."',
          '".addslashes($salesorder)."',
          '".addslashes($style)."',
          '".addslashes($no_po)."',
          '".addslashes($item)."',
          '".addslashes($warna)."',
          '".addslashes($season)."',
          '".addslashes($_SESSION['nama10'])."')";
}

if (mysqli_query($cona, $q)) {
    echo "OK"; // respon sukses
} else {
    http_response_code(500);
    echo "Database Error";
}
