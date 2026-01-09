<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
$sqlCek = mysqli_query($cona,"SELECT tg.id_nsp, tb.id_nsp_gk FROM tbl_bonkain tb LEFT JOIN tbl_gantikain tg ON tg.id =tb.id_nsp WHERE tb.id='$_POST[pk]' ");
$rCek = mysqli_fetch_array($sqlCek);
$idnsp=$rCek['id_nsp'];
$idnspgk=$rCek['id_nsp_gk'];
$value = $_POST['value'];
mysqli_query($cona,"UPDATE tbl_bonkain SET akar_penyebab = '$value' where id = '$_POST[pk]'");
mysqli_query($cond,"UPDATE tbl_ganti_kain_now SET akar_penyebab = '$value' where id = '$idnspgk'");

echo json_encode('success');
