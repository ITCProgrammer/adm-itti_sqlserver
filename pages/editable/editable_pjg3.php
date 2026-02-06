<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

// mysqli_query($cona,"UPDATE tbl_bonkain SET `pjg3` = '$_POST[value]' where id = '$_POST[pk]'");
$value = $_POST['value'];
$pk    = $_POST['pk'];

sqlsrv_query(
    $cona,
    "UPDATE db_adm.tbl_bonkain
     SET pjg3 = ?
     WHERE id = ?",
    array($value, $pk)
);

echo json_encode('success');
