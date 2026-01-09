<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

mysqli_query($cona,"UPDATE tbl_bonkain SET `pjg3` = '$_POST[value]' where id = '$_POST[pk]'");

echo json_encode('success');
