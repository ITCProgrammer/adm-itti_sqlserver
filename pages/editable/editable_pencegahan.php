<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

if (empty($_SESSION['user_id10'])) {
    http_response_code(401);
    echo "Silakan login terlebih dahulu.";
    exit;
}

$ip_num = $_SERVER['REMOTE_ADDR'];
$os     = $_SERVER['HTTP_USER_AGENT'];

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
                foreach ($to as $addr) $mail->addAddress($addr);
            } else {
                $mail->addAddress($to);
            }
            foreach ($cc as $addr) $mail->addCC($addr);
            foreach ($bcc as $addr) $mail->addBCC($addr);
            foreach ($attachments as $file) $mail->addAttachment($file);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $bodyHtml;

            $mail->Hostname = 'mail.indotaichen.com';
            $mail->Helo     = 'mail.indotaichen.com';

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

$pk    = $_POST['pk'];
$value = $_POST['value'];

$sqlCek = sqlsrv_query(
    $cona,
    "SELECT tg.id_nsp, tb.id_nsp_gk, tb.no_bon
     FROM db_adm.tbl_bonkain tb
     LEFT JOIN db_adm.tbl_gantikain tg ON tg.id = tb.id_nsp
     WHERE tb.id = ?",
    array($pk)
);

$rCek = sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC);
$idnsp   = $rCek['id_nsp'] ?? null;
$idnspgk = $rCek['id_nsp_gk'] ?? null;

sqlsrv_query(
    $cona,
    "UPDATE db_adm.tbl_bonkain
     SET pencegahan = ?
     WHERE id = ?",
    array($value, $pk)
);

$result = true;
if ($idnspgk !== null && $idnspgk !== '') {
    $result = sqlsrv_query(
        $cond,
        "UPDATE db_qc.tbl_ganti_kain_now
         SET pencegahan = ?
         WHERE id = ?",
        array($value, $idnspgk)
    );
}

sqlsrv_query(
    $cona,
    "INSERT INTO db_adm.tbl_log (what, what_do, project, do_by, do_at, ip, os)
     VALUES (?, ?, ?, ?, GETDATE(), ?, ?)",
    array(
        'Pencegahan',
        "Edit Pencegahan ( $value )",
        $rCek['no_bon'] ?? '',
        $_SESSION['user_id10'],
        $ip_num,
        $os
    )
);

if ($result) {
    $qryBon = sqlsrv_query(
        $cona,
        "SELECT TOP 1 *
         FROM db_adm.tbl_bonkain
         WHERE id = ?",
        array($pk)
    );
    $bon = sqlsrv_fetch_array($qryBon, SQLSRV_FETCH_ASSOC);

    if ($bon) {
        $to = ['aftersales.adm@indotaichen.com','deden.kurnia@indotaichen.com'];
        $subject = "Notifikasi Update Pencegahan - Bon Ganti Kain: " . ($bon['no_bon'] ?? '');

        $baseUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $linkDetail = $baseUrl . "/adm-itti/index1.php?p=input-bon-kain&id=" . urlencode($bon['id_nsp'] ?? '');

        $bodyHtml = "Pencegahan pada Bon Ganti Kain telah diupdate.<br><br>"
            . "<table>"
            . "<tr><td>No Bon</td><td>: <b>".($bon['no_bon'] ?? '')."</b></td></tr>"
            . "<tr><td>Analisa Baru</td><td>: ".($bon['analisa'] ?? '')."</td></tr>"
            . "<tr><td>Pencegahan Baru</td><td>: ".htmlspecialchars($value)."</td></tr>"
            . "<tr><td>Diupdate oleh</td><td>: ".($_SESSION['nama10'] ?? $_SESSION['user_id10'])."</td></tr>"
            . "<tr><td>Waktu Update</td><td>: ".date('Y-m-d H:i:s')."</td></tr>"
            . "</table><br>"
            . "Silakan cek aplikasi untuk detail lebih lanjut.<br>"
            . "<a href='".$linkDetail."' target='_blank' style='color: #337ab7; text-decoration: underline;'>Lihat Detail Bon</a>";

        $sendMailResult = sendEmailApproved($to, $subject, $bodyHtml);

        if (!$sendMailResult) {
            error_log('Gagal mengirim email notifikasi update Pencegahan: ' . getLastMailerError());
        }
    }
}

echo json_encode('success');