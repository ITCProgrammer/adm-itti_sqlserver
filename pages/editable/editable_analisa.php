<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

if (empty($_SESSION['user_id10'])) {
    http_response_code(401);
    echo "Silakan login terlebih dahulu.";
    exit;
}

$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
$sqlCek = mysqli_query($cona,"SELECT tg.id_nsp, tb.id_nsp_gk,tb.no_bon FROM tbl_bonkain tb LEFT JOIN tbl_gantikain tg ON tg.id =tb.id_nsp WHERE tb.id='$_POST[pk]' ");
$rCek = mysqli_fetch_array($sqlCek);
$idnsp=$rCek['id_nsp'];
$idnspgk=$rCek['id_nsp_gk'];
$value = str_replace("'","''",$_POST['value']);
mysqli_query($cona,"UPDATE tbl_bonkain SET `analisa` = '$value' where id = '$_POST[pk]'");
mysqli_query($cond,"UPDATE tbl_ganti_kain_now SET `analisa` = '$value' where id = '$idnspgk'");

mysqli_query($cona,"INSERT into tbl_log SET
	`what` = 'Update Analisa',
	`what_do` = 'Edit Analisa ( $_POST[value] )',
	`project` = '$rCek[no_bon]',
	`do_by` = '$_SESSION[user_id10]',
	`do_at` = now(),
	`ip` = '$ip_num',
	`os` = '$os'");

echo json_encode('success');
