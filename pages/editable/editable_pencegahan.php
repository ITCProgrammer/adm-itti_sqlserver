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
// Function untuk mengirim email (sama seperti yang sudah ada)
include_once("../../classes/class.phpmailer.php");
if (!function_exists('sendEmailApproved')) {
    function sendEmailApproved($to, $subject, $bodyHtml, $fromEmail = 'dept.it@indotaichen.com', $fromName = 'DEPT IT', $cc = [], $bcc = [], $attachments = []) {
        global $GLOBAL_LAST_MAILER_ERROR;
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'mail.indotaichen.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dept.it@indotaichen.com';
            $mail->Password = 'Xr7PzUWoyPA';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $fromEmail = $mail->Username;
            $mail->setFrom($fromEmail, $fromName);
            if (is_array($to)) {
                foreach ($to as $addr) {
                    $mail->addAddress($addr);
                }
            } else {
                $mail->addAddress($to);
            }
            foreach ($cc as $addr) {
                $mail->addCC($addr);
            }
            foreach ($bcc as $addr) {
                $mail->addBCC($addr);
            }
            foreach ($attachments as $file) {
                $mail->addAttachment($file);
            }
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $bodyHtml;
            $mail->Hostname = 'mail.indotaichen.com';
            $mail->Helo = 'mail.indotaichen.com';
            $mail->send();
            $GLOBAL_LAST_MAILER_ERROR = '';
            return true;
        } catch (Exception $e) {
            $GLOBAL_LAST_MAILER_ERROR = $mail->ErrorInfo;
            error_log('Mailer Error: ' . $mail->ErrorInfo);
            return false;
        }
    }
    function getLastMailerError() {
        global $GLOBAL_LAST_MAILER_ERROR;
        return $GLOBAL_LAST_MAILER_ERROR;
    }
}
$sqlCek = mysqli_query($cona,"SELECT tg.id_nsp, tb.id_nsp_gk,tb.no_bon FROM tbl_bonkain tb LEFT JOIN tbl_gantikain tg ON tg.id =tb.id_nsp WHERE tb.id='$_POST[pk]' ");
$rCek = mysqli_fetch_array($sqlCek);
$idnsp=$rCek['id_nsp'];
$idnspgk=$rCek['id_nsp_gk'];
$value = str_replace("'","''",$_POST['value']);
mysqli_query($cona,"UPDATE tbl_bonkain SET `pencegahan` = '$value' where id = '$_POST[pk]'");
mysqli_query($cond,"UPDATE tbl_ganti_kain_now SET `pencegahan` = '$value' where id = '$idnspgk'");
mysqli_query($cona,"INSERT into tbl_log SET
	`what` = 'Pencegahan',
	`what_do` = 'Edit Pencegahan ( $_POST[value] )',
	`project` = '$rCek[no_bon]',
	`do_by` = '$_SESSION[user_id10]',
	`do_at` = now(),
	`ip` = '$ip_num',
	`os` = '$os'");

$result = mysqli_query($cond,"UPDATE tbl_ganti_kain_now SET `pencegahan` = '$value' where id = '$idnspgk'");
// Jika update berhasil, kirim email notifikasi
if($result){
    // Ambil data untuk isi email
    $qryBon = mysqli_query($cona, "SELECT * FROM tbl_bonkain WHERE id='$_POST[pk]' LIMIT 1");
    $bon = mysqli_fetch_assoc($qryBon);
    // Kirim email notifikasi
    $to = ['aftersales.adm@indotaichen.com','deden.kurnia@indotaichen.com'];
    $subject = "Notifikasi Update Pencegahan - Bon Ganti Kain: " . $bon['no_bon'];
    
    
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    $linkDetail = $baseUrl . "/adm-itti/index1.php?p=input-bon-kain&id=" . urlencode($bon['id_nsp']);
    
    $bodyHtml = "Pencegahan pada Bon Ganti Kain telah diupdate.<br><br>"
        . "<table>"
        . "<tr><td>No Bon</td><td>: <b>".$bon['no_bon']."</b></td></tr>"
        . "<tr><td>Analisa Baru</td><td>: " . $bon['analisa'] . "</td></tr>"
        . "<tr><td>Pencegahan Baru</td><td>: " . $value . "</td></tr>"
        . "<tr><td>Diupdate oleh</td><td>: " . $_SESSION['nama10'] . "</td></tr>"
        . "<tr><td>Waktu Update</td><td>: " . date('Y-m-d H:i:s') . "</td></tr>"
        . "</table><br>"
        . "Silakan cek aplikasi untuk detail lebih lanjut.<br>"
        . "<a href='".$linkDetail."' target='_blank' style='color: #337ab7; text-decoration: underline;'>Lihat Detail Bon</a>";

    $sendMailResult = sendEmailApproved($to, $subject, $bodyHtml);
    
    if(!$sendMailResult){
        error_log('Gagal mengirim email notifikasi update Pencegahan: ' . getLastMailerError());
    }
}

echo json_encode('success');
