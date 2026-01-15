<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
include_once("classes/class.phpmailer.php");

if (!function_exists('sendEmailApproved')) {
  function sendEmailApproved($to, $subject, $bodyHtml, $fromEmail = 'dept.it@indotaichen.com', $fromName = 'DEPT IT', $cc = [], $bcc = [], $attachments = [])
  {
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
      // $mail->send();
      $GLOBAL_LAST_MAILER_ERROR = '';
      return true;
    } catch (Exception $e) {
      $GLOBAL_LAST_MAILER_ERROR = $mail->ErrorInfo;
      error_log('Mailer Error: ' . $mail->ErrorInfo);
      return false;
    }
  }
  function getLastMailerError()
  {
    global $GLOBAL_LAST_MAILER_ERROR;
    return $GLOBAL_LAST_MAILER_ERROR;
  }
}
$qryCek = sqlsrv_query($cona, "SELECT * FROM db_adm.tbl_gantikain WHERE id=?", [$_GET['id']]);
$rCek = sqlsrv_fetch_array($qryCek, SQLSRV_FETCH_ASSOC);
?>
<?php
function no_urut($x)
{
  include "koneksi.php";
  date_default_timezone_set("Asia/Jakarta");
  if ($x == "Reject Buyer") {
    $fk = "RB";
  } else if ($x == "Kurang Qty") {
    $fk = "GK";
  } else if ($x == "BS") {
    $fk = "BS";
  } else if ($x == "Oper Warna") {
    $fk = "OW";
  } else if ($x == "Untuk Stock") {
    $fk = "US";
  }
  $format = $fk . date("y/m/");
  $sql = sqlsrv_query($cona, "SELECT TOP 1 no_bon FROM db_adm.tbl_bonkain WHERE LEFT(no_bon,10) LIKE '".$format."%' ORDER BY no_bon DESC") or die(print_r(sqlsrv_errors(), true));
  $r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
  $d = ($r !== false) ? 1 : 0;
  if ($d > 0) {
    $d = $r['no_bon'];
    $str = substr($d, 8, 3);
    $Urut = (int)$str;
  } else {
    $Urut = 0;
  }
  $Urut = $Urut + 1;
  $Nol = "";
  $nilai = 3 - strlen($Urut);
  for ($i = 1; $i <= $nilai; $i++) {
    $Nol = $Nol . "0";
  }
  $nipbr = $format . $Nol . $Urut;
  return $nipbr;
}
function orderno($x, $odr)
{
  include "koneksi.php";
  date_default_timezone_set("Asia/Jakarta");
  if ($x == "Reject Buyer") {
    $fk = "GR";
  } else if ($x == "Kurang Qty") {
    $fk = "G";
  }
  $format = $odr;
  $sql = sqlsrv_query($cona, "SELECT no_order FROM db_adm.tbl_bonkain WHERE no_order='$format' ORDER BY no_order DESC LIMIT 1") or die(print_r(sqlsrv_errors(), true));
  $r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
  $d = ($r !== false) ? 1 : 0;
  if ($d > 0) {
    $d = $r['no_bon'];
    $str = substr($d, 8, 3);
    $Urut = (int)$str;
  } else {
    $Urut = 0;
  }
  $Urut = $Urut + 1;
  $Nol = "";
  $nilai = 3 - strlen($Urut);
  for ($i = 1; $i <= $nilai; $i++) {
    $Nol = $Nol . "0";
  }
  $nipbr = $format . $Nol . $Urut;
  return $nipbr;
}

if (isset($_POST['save'])) {
  $bon = no_urut($_POST['alasan']);
  if ($_POST['analisa'] == "Reject Buyer") {
    $order = $rCek['no_order'] . " GR1";
  } else if ($_POST['analisa'] == "Kurang Qty") {
    $fk = $rCek['no_order'] . " G1";;
  }
  $analisa = str_replace("'", "''", $_POST['analisa']);
  $pencegahan = str_replace("'", "''", $_POST['pencegahan']);
  if (isset($_POST['akar_penyebab']) && is_array($_POST['akar_penyebab'])) {
    $akar_penyebab = implode(',', $_POST['akar_penyebab']);
  } else {
    $akar_penyebab = '';
  }
  $alasan = str_replace("'", "''", $_POST['alasan']);
  $pwar1 = strpos($_POST['warna1'], ';');
  $pwar2 = strpos($_POST['warna2'], ';');
  $pwar3 = strpos($_POST['warna3'], ';');
  $potW1 = substr($_POST['warna1'], 0, $pwar1);
  $potW2 = substr($_POST['warna2'], 0, $pwar2);
  $potW3 = substr($_POST['warna3'], 0, $pwar3);
  $potKK1 = substr($_POST['warna1'], $pwar1 + 1, 15);
  $potKK2 = substr($_POST['warna2'], $pwar2 + 1, 15);
  $potKK3 = substr($_POST['warna3'], $pwar3 + 1, 15);
  $kk1 = str_replace("'", "''", $potKK1);
  $kk2 = str_replace("'", "''", $potKK2);
  $kk3 = str_replace("'", "''", $potKK3);
  $warna1 = str_replace("'", "''", $potW1);
  $warna2 = str_replace("'", "''", $potW2);
  $warna3 = str_replace("'", "''", $potW3);
  $kg1 = str_replace("'", "''", $_POST['kg1']);
  $kg2 = str_replace("'", "''", $_POST['kg2']);
  $kg3 = str_replace("'", "''", $_POST['kg3']);
  $pjg1 = str_replace("'", "''", $_POST['pjg1']);
  $pjg2 = str_replace("'", "''", $_POST['pjg2']);
  $pjg3 = str_replace("'", "''", $_POST['pjg3']);
  $satuan1 = str_replace("'", "''", $_POST['satuan1']);
  $satuan2 = str_replace("'", "''", $_POST['satuan2']);
  $satuan3 = str_replace("'", "''", $_POST['satuan3']);
  $qry1 = sqlsrv_query($cona, "
    INSERT INTO db_adm.tbl_bonkain (
      id_nsp, no_bon, no_order, alasan, analisa, akar_penyebab, pencegahan,
      nokk1, nokk2, nokk3, warna1, warna2, warna3,
      kg1, kg2, kg3, pjg1, pjg2, pjg3, satuan1, satuan2, satuan3,
      tgl_buat, tgl_update
    ) VALUES (
      ?, ?, ?, ?, ?, ?, ?,
      ?, ?, ?, ?, ?, ?,
      ?, ?, ?, ?, ?, ?, ?, ?, ?,
      GETDATE(), GETDATE()
    )
  ", [
    $_GET['id'],
    $bon,
    $order,
    $alasan,
    $analisa,
    $akar_penyebab,
    $pencegahan,
    $kk1, $kk2, $kk3,
    $warna1, $warna2, $warna3,
    $kg1, $kg2, $kg3,
    $pjg1, $pjg2, $pjg3,
    $satuan1, $satuan2, $satuan3
  ]);
  if ($qry1 === false) { die(print_r(sqlsrv_errors(), true)); }

  if ($qry1) {
    echo "<script>swal({
      title: 'Data Telah diSimpan',   
      text: 'Klik Ok untuk input data kembali',
      type: 'success',
      }).then((result) => {
      if (result.value) {
          window.open('pages/cetak/cetak_bon_ganti.php?no_bon=$bon','_blank');
          window.location.href='index1.php?p=input-bon-kain&id=$_GET[id]';
      }
    });</script>";
  }
}

if (isset($_POST['approve_bon']) && $_POST['approve_bon'] == '1') {
  include "koneksi.php";
  $id_bon = $_POST['id_bon'];

  $emailTambahan = isset($_POST['email']) ? $_POST['email'] : '';
  $namaTambahan = '';

  if ($emailTambahan != '') {
    $qEmail = sqlsrv_query($cona, "SELECT TOP 1 nama FROM db_adm.master_email WHERE email = ?", [$emailTambahan]);
    if ($qEmail === false) die(print_r(sqlsrv_errors(), true));
    $rowEmail = sqlsrv_fetch_array($qEmail, SQLSRV_FETCH_ASSOC);
    if ($rowEmail) $namaTambahan = $rowEmail['nama'];
  }

  $qry = sqlsrv_query($cona, "
    UPDATE db_adm.tbl_bonkain
    SET approved_buat = GETDATE(), personil_buat = ?, personil_ppc = ?
    WHERE id = ?
  ", [$_SESSION['nama10'], $namaTambahan, $id_bon]);

  if ($qry === false) die(print_r(sqlsrv_errors(), true));

  if ($qry) {
    $qBon = sqlsrv_query($cona, "SELECT * FROM db_adm.tbl_bonkain WHERE id = ?", [$id_bon]);
    if ($qBon === false) die(print_r(sqlsrv_errors(), true));
    $bon = sqlsrv_fetch_array($qBon, SQLSRV_FETCH_ASSOC);

    if (!empty($emailTambahan)) {
      $to[] = $emailTambahan;
    }
    $subject = "Bon Ganti Kain Telah Di-Approve";
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    $linkInputStok = $baseUrl . "/adm-itti/pages/input_stok_ppc.php?id=" . urlencode($bon['id']);
    $bodyHtml = "Bon dengan No: <b>" . $bon['no_bon'] . "</b> telah di-approve oleh " . $_SESSION['nama10'] . " pada " . $now . ".<br>"
      . "Silakan cek aplikasi untuk detail.<br>"
      . "<a href='" . $linkInputStok . "' target='_blank' style='color: #337ab7; text-decoration: underline;'>Input Stok PPC</a>";
    $sendMailResult = sendEmailApproved($to, $subject, $bodyHtml);
    if (!$sendMailResult) {
      echo "<script>alert('Gagal mengirim email notifikasi! Pesan: " . getLastMailerError() . "');</script>";
    }
    echo "<script>swal({
      title: 'Bon Telah di-Approve',
      text: 'Status bon sudah Approved',
      type: 'success',
    }).then((result) => {
      if (result.value) {
        window.location.href='index1.php?p=input-bon-kain&id=" . $_GET['id'] . "';
      }
    });</script>";
  }

}
// --- Pindahkan function ke atas sebelum blok approve ---
if (!function_exists('sendEmailApproved')) {
  function sendEmailApproved($to, $subject, $bodyHtml, $fromEmail = 'dept.it@indotaichen.com', $fromName = 'DEPT IT', $cc = [], $bcc = [], $attachments = [])
  {
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
      // $mail->send();
      $GLOBAL_LAST_MAILER_ERROR = '';
      return true;
    } catch (Exception $e) {
      $GLOBAL_LAST_MAILER_ERROR = $mail->ErrorInfo;
      error_log('Mailer Error: ' . $mail->ErrorInfo);
      return false;
    }
  }
  function getLastMailerError()
  {
    global $GLOBAL_LAST_MAILER_ERROR;
    return $GLOBAL_LAST_MAILER_ERROR;
  }
}
?>

<div class="box box-info">
  <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
    <div class="box-header with-border">
      <h3 class="box-title">Formulir Ganti Kain</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div class="box-body">
      <div class="form-group">
        <label for="alasan" class="col-sm-2 control-label">Alasan</label>
        <div class="col-sm-3">
          <select class="form-control select2" name="alasan" required>
            <option value="">Pilih</option>
            <option value="Kurang Qty">Kurang Qty</option>
            <option value="Reject Buyer">Reject Buyer</option>
            <option value="BS">BS</option>
            <option value="Oper Warna">Oper Warna</option>
            <option value="Untuk Stock">Untuk Stock</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <?php
        $selectedValues = [];
        if (isset($rCek['sebab']) && !empty($rCek['sebab'])) {
          $selectedValues = explode(',', $rCek['sebab']);
        }
        ?>
        <label for="akar_penyebab" class="col-sm-2 control-label">Kategori Penyebab</label>
        <div class="col-sm-3">
          <select class="form-control select2" name="akar_penyebab[]" multiple="multiple" required>
            <option value="MAN" <?php echo in_array("Man", $selectedValues) ? 'selected="selected"' : ''; ?>>MAN</option>
            <option value="MACHINE" <?php echo in_array("Machine", $selectedValues) ? 'selected="selected"' : ''; ?>>MACHINE</option>
            <option value="METHODE" <?php echo in_array("Methode", $selectedValues) ? 'selected="selected"' : ''; ?>>METHODE</option>
            <option value="MATERIAL" <?php echo in_array("Material", $selectedValues) ? 'selected="selected"' : ''; ?>>MATERIAL</option>
            <option value="ENVIRONMENT" <?php echo in_array("Environment", $selectedValues) ? 'selected="selected"' : ''; ?>>ENVIRONMENT</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="warna1" class="col-sm-2 control-label">Warna 1</label>
        <div class="col-sm-3">
          <select class="form-control select2" name="warna1" required>
            <option value="">Pilih</option>
            <?php
            $sqlw1 = sqlsrv_query(
              $cona,
              "SELECT warna, nokk
              FROM db_adm.tbl_gantikain
              WHERE no_order = ? AND no_hanger = ?
              GROUP BY warna, nokk
              ORDER BY warna",
              [ $rCek['no_order'], $rCek['no_hanger'] ]
            );

            if ($sqlw1 === false) { die(print_r(sqlsrv_errors(), true)); }

            while ($rwarna = sqlsrv_fetch_array($sqlw1, SQLSRV_FETCH_ASSOC)) { ?>
              <option value="<?php echo $rwarna['warna'] . ";" . $rwarna['nokk']; ?>">
                <?php echo $rwarna['warna']; ?>
              </option>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-2">
          <div class="input-group">
            <input name="kg1" type="text" class="form-control" id="kg1" value="<?php if ($cek > 0) {
                                                                                  echo $rcek['kg1'];
                                                                                } ?>" placeholder="0.00" style="text-align: right;" required>
            <span class="input-group-addon">Kg</span>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="input-group">
            <input name="pjg1" type="text" class="form-control" id="pjg1" value="<?php if ($cek > 0) {
                                                                                    echo $rcek['pjg1'];
                                                                                  } ?>" placeholder="0.00" style="text-align: right;" required>
            <span class="input-group-addon">
              <select name="satuan1" style="font-size: 12px;" id="satuan1">
                <option value="Yard" <?php if ($rcek['satuan1'] == "Yard") {
                                        echo "SELECTED";
                                      } ?>>Yard</option>
                <option value="Meter" <?php if ($rcek['satuan1'] == "Meter") {
                                        echo "SELECTED";
                                      } ?>>Meter</option>
                <option value="PCS" <?php if ($rcek['satuan1'] == "PCS") {
                                      echo "SELECTED";
                                    } ?>>PCS</option>
              </select>
            </span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="warna2" class="col-sm-2 control-label">Warna 2</label>
        <div class="col-sm-3">
          <select class="form-control select2" name="warna2">
            <option value="">Pilih</option>
            <?php
            $sqlw1 = sqlsrv_query(
              $cona,
              "SELECT warna, nokk
              FROM db_adm.tbl_gantikain
              WHERE no_order = ? AND no_hanger = ?
              GROUP BY warna, nokk
              ORDER BY warna",
              [ $rCek['no_order'], $rCek['no_hanger'] ]
            );

            if ($sqlw1 === false) { die(print_r(sqlsrv_errors(), true)); }

            while ($rwarna = sqlsrv_fetch_array($sqlw1, SQLSRV_FETCH_ASSOC)) { ?>
              <option value="<?php echo $rwarna['warna'] . ';' . $rwarna['nokk']; ?>">
                <?php echo $rwarna['warna']; ?>
              </option>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-2">
          <div class="input-group">
            <input name="kg2" type="text" class="form-control" id="kg2" value="<?php if ($cek > 0) {
                                                                                  echo $rcek['kg2'];
                                                                                } ?>" placeholder="0.00" style="text-align: right;">
            <span class="input-group-addon">Kg</span>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="input-group">
            <input name="pjg2" type="text" class="form-control" id="pjg2" value="<?php if ($cek > 0) {
                                                                                    echo $rcek['pjg2'];
                                                                                  } ?>" placeholder="0.00" style="text-align: right;">
            <span class="input-group-addon">
              <select name="satuan2" style="font-size: 12px;" id="satuan2">
                <option value="Yard" <?php if ($rcek['satuan2'] == "Yard") {
                                        echo "SELECTED";
                                      } ?>>Yard</option>
                <option value="Meter" <?php if ($rcek['satuan2'] == "Meter") {
                                        echo "SELECTED";
                                      } ?>>Meter</option>
                <option value="PCS" <?php if ($rcek['satuan2'] == "PCS") {
                                      echo "SELECTED";
                                    } ?>>PCS</option>
              </select>
            </span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="warna3" class="col-sm-2 control-label">Warna 3</label>
        <div class="col-sm-3">
          <select class="form-control select2" name="warna3">
            <option value="">Pilih</option>
            <?php
            $sqlw1 = sqlsrv_query(
              $cona,
              "SELECT warna, nokk
              FROM db_adm.tbl_gantikain
              WHERE no_order = ? AND no_hanger = ?
              GROUP BY warna, nokk
              ORDER BY warna",
              [ $rCek['no_order'], $rCek['no_hanger'] ]
            );

            if ($sqlw1 === false) { die(print_r(sqlsrv_errors(), true)); }

            while ($rwarna = sqlsrv_fetch_array($sqlw1, SQLSRV_FETCH_ASSOC)) { ?>
              <option value="<?php echo $rwarna['warna'] . ';' . $rwarna['nokk']; ?>">
                <?php echo $rwarna['warna']; ?>
              </option>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-2">
          <div class="input-group">
            <input name="kg3" type="text" class="form-control" id="kg3" value="<?php if ($cek > 0) {
                                                                                  echo $rcek['kg3'];
                                                                                } ?>" placeholder="0.00" style="text-align: right;">
            <span class="input-group-addon">Kg</span>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="input-group">
            <input name="pjg3" type="text" class="form-control" id="pjg3" value="<?php if ($cek > 0) {
                                                                                    echo $rcek['pjg3'];
                                                                                  } ?>" placeholder="0.00" style="text-align: right;">
            <span class="input-group-addon">
              <select name="satuan3" style="font-size: 12px;" id="satuan3">
                <option value="Yard" <?php if ($rcek['satuan3'] == "Yard") {
                                        echo "SELECTED";
                                      } ?>>Yard</option>
                <option value="Meter" <?php if ($rcek['satuan3'] == "Meter") {
                                        echo "SELECTED";
                                      } ?>>Meter</option>
                <option value="PCS" <?php if ($rcek['satuan3'] == "PCS") {
                                      echo "SELECTED";
                                    } ?>>PCS</option>
              </select>
            </span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="analisa" class="col-sm-2 control-label">Analisa</label>
        <div class="col-sm-6">
          <textarea name="analisa" class="form-control" id="analisa" placeholder="Analisa"></textarea>
        </div>
      </div>
      <div class="form-group">
        <label for="pencegahan" class="col-sm-2 control-label">Pencegahan</label>
        <div class="col-sm-6">
          <textarea name="pencegahan" class="form-control" id="pencegahan" placeholder="Pencegahan"></textarea>
        </div>
      </div>
      <div class="form-group hidden">
        <label for="warna" class="col-sm-2 control-label">Warna</label>
        <div class="col-sm-6">
          <textarea name="warna" class="form-control" id="warna" placeholder=""></textarea>
        </div>
      </div>
      <!-- /.box-footer -->
    </div>
    <div class="box-footer">
      <input type="submit" value="Simpan" name="save" id="save" class="btn btn-primary pull-right">
    </div>
  </form>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
      </div>
      <div class="box-body">
        <table id="example3" class="table table-bordered table-hover table-striped nowrap" width="100%">
          <thead class="bg-green">
            <tr>
              <th width="48">
                <div align="center">No</div>
              </th>
              <th width="149">
                <div align="center">No Bon</div>
              </th>
              <th width="301">
                <div align="center">Alasan</div>
              </th>
              <th width="343">
                <div align="center">Analisa</div>
              </th>
              <th width="331">
                <div align="center">Pencegahan</div>
              </th>
              <th width="331">
                <div align="center">Kategori Penyebab</div>
              </th>
              <th width="331">
                <div align="center">Warna</div>
              </th>
              <th width="331">
                <div align="center">Qty</div>
              </th>
              <th width="331">
                <div align="center">Qty Bruto</div>
              </th>
              <th width="331">
                <div align="center">Status</div>
              </th>
              <th width="331">
                <div align="center">Aksi</div>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = sqlsrv_query($cona, "SELECT * FROM db_adm.tbl_bonkain WHERE id_nsp = ? ORDER BY no_bon ASC", [$_GET['id']]);
            if ($sql === false) { die(print_r(sqlsrv_errors(), true)); }

            while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {

              $no++;
              $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';

            ?>
              <tr bgcolor="<?php echo $bgcolor; ?>">
                <td align="center"><?php echo $no; ?></td>
                <td align="center"><a href="#" class="edit_bon" id="<?php echo $r['id'] ?>"><?php echo $r['no_bon']; ?></a></td>
                <td align="center"><?php echo $r['alasan']; ?></td>
                <td align="left"><a data-pk="<?php echo $r['id'] ?>" data-value="<?php echo $r['analisa']; ?>" class="analisa" href="javascipt:void(0)"><?php echo $r['analisa']; ?></a></td>
                <td align="left"><a data-pk="<?php echo $r['id'] ?>" data-value="<?php echo $r['pencegahan']; ?>" class="pencegahan" href="javascipt:void(0)"><?php echo $r['pencegahan']; ?></a></td>
                <td align="left"><a data-pk="<?php echo $r['id'] ?>" data-value="<?php echo htmlspecialchars(json_encode(explode(',', $r['akar_penyebab'])), ENT_QUOTES, 'UTF-8'); ?>" class="akar_penyebab" href="javascript:void(0)"><?php echo $r['akar_penyebab']; ?></a></td>
                <td align="left" valign="top"><?php
                                              if ($r['warna1'] != "") {
                                                echo "1. " . $r['warna1'] . "<br>";
                                              }
                                              if ($r['warna2'] != "") {
                                                echo "2. " . $r['warna2'] . "<br>";
                                              }
                                              if ($r['warna3'] != "") {
                                                echo "3. " . $r['warna3'] . "<br>";
                                              }
                                              ?></td>
                <td align="right"><?php
                                  if ($r['kg1'] > 0) {
                                    echo "1. "; ?><a data-pk="<?php echo $r['id'] ?>" data-value="<?php echo $r['kg1']; ?>" class="kg1" href="javascipt:void(0)"><?php echo $r['kg1']; ?></a> <?php echo " Kg "; ?><a data-pk="<?php echo $r['id'] ?>" data-value="<?php echo $r['pjg1']; ?>" class="pjg1" href="javascipt:void(0)"><?php echo $r['pjg1']; ?></a> <?php echo " " . $r['satuan1'] . "<br>";
                                                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                                                if ($r['kg2'] > 0) {
                                                                                                                                                                                                                                                                                                                                                  echo "2. "; ?><a data-pk="<?php echo $r['id'] ?>" data-value="<?php echo $r['kg2']; ?>" class="kg2" href="javascipt:void(0)"><?php echo $r['kg2']; ?></a> <?php echo " Kg "; ?><a data-pk="<?php echo $r['id'] ?>" data-value="<?php echo $r['pjg2']; ?>" class="pjg2" href="javascipt:void(0)"><?php echo $r['pjg2']; ?></a> <?php echo " " . $r['satuan2'] . "<br>";
                                                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                                                if ($r['kg3'] > 0) {
                                                                                                                                                                                                                                                                                                                                                  echo "3. " . $r['kg3'] . " Kg "; ?><a data-pk="<?php echo $r['id'] ?>" data-value="<?php echo $r['pjg3']; ?>" class="pjg3" href="javascipt:void(0)"><?php echo $r['pjg3']; ?></a> <?php echo " " . $r['satuan3'] . "<br>";
                                                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                  ?></td>
                <td align="center"><?php if (strtolower($_SESSION['nama10']) == "angela" or $_SESSION['dept10'] == "RMP" or $_SESSION['dept10'] == "PPC") { ?>
                    <a data-pk="<?php echo $r['id'] ?>" data-value="<?php echo $r['kg_bruto']; ?>" class="kg_bruto" href="javascipt:void(0)"><?php echo $r['kg_bruto']; ?></a><?php } else {
                                                                                                                                                                              echo $r['kg_bruto'];
                                                                                                                                                                            } ?>
                </td>
                <td align="center"><?php if (strtolower($_SESSION['nama10']) == "aressa gasih") { ?><a data-pk="<?php echo $r['id'] ?>" data-value="<?php echo $r['sts']; ?>" class="sts_bon" href="javascipt:void(0)"><?php echo $r['sts']; ?></a><?php } else {
                                                                                                                                                                                                                                                echo $r['sts'];
                                                                                                                                                                                                                                              } ?></td>
                <td align="center">
                  <div class="btn-group">
                    <?php
                    // Cek jika sudah di-approve, disable tombol approve
                    if (empty($r['approved_buat']) && empty($r['personil_buat'])) { ?>
                      <a href="#" class="btn btn-success btn-xs" onclick="confirm_terima('ApprovedBon-<?php echo $r['id']; ?>-<?php echo $_SESSION['dept10']; ?>-<?php echo $_SESSION['nama10']; ?>-<?php echo $_SESSION['jabatanGKJ1']; ?>');"><i class="fa fa-check-circle" data-toggle="tooltip" data-placement="top" title="Approved"></i> </a>
                    <?php } else { ?>
                      <button class="btn btn-success btn-xs" disabled><i class="fa fa-check-circle"></i></button>
                    <?php } ?>
                    <a href="pages/cetak/cetak_bon_ganti.php?no_bon=<?php echo $r['no_bon'] ?>" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-print"></i> </a>
                    <a href="#" class="btn btn-danger btn-xs <?php if ($_SESSION['akses10'] == 'biasa') {
                                                                echo "disabled";
                                                              } ?>" onclick="confirm_delete('index1.php?p=hapusdatabon&id=<?php echo $r['id'] ?>');"><i class="fa fa-trash"></i> </a>
                  </div>
                </td>
              </tr>
            <?php
              $tpersen += $r['persen'];
            }
            ?>
          </tbody>
        </table>
        <div id="KodeEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
        <div id="PersenEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
        <div id="EditBon" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> </div>
      </div>
    </div>
    <div class="modal fade" id="modal_del" tabindex="-1">
      <div class="modal-dialog modal-sm">
        <div class="modal-content" style="margin-top:100px;">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" style="text-align:center;">Are you sure to delete all data ?</h4>
          </div>

          <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
            <a href="#" class="btn btn-danger" id="delete_link">Delete</a>
            <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal Popup untuk terima bon-->
    <div class="modal fade" id="terimaBon" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content" style="margin-top:100px;">
          <form method="post" action="">
            <div class="modal-header">
              <h4 class="modal-title">INFORMATION</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
              <h5 class="modal-title" style="text-align:center;"><span class='badge'>Approved</span> Bon Ganti Kain ?</h5>
              <input type="hidden" name="approve_bon" value="1">
              <input type="hidden" name="id_bon" id="id_bon_approve" value="">
            </div>
            <select class="form-control" name="email" id="email">
              <option value="">Pilih Email</option>
              <?php
              $queryEmail = sqlsrv_query($cona, "SELECT email FROM db_adm.master_email ORDER BY email ASC");
              if ($queryEmail === false) { die(print_r(sqlsrv_errors(), true)); }

              while ($rowEmail = sqlsrv_fetch_array($queryEmail, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($rowEmail['email']) . "'>" . htmlspecialchars($rowEmail['email']) . "</option>";
              }

              sqlsrv_free_stmt($queryEmail);
              ?>
            </select>
            <div class="modal-footer justify-content-between">
              <button type="submit" class="btn btn-success">Yes</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      function confirm_delete(delete_url) {
        $('#modal_del').modal('show', {
          backdrop: 'static'
        });
        document.getElementById('delete_link').setAttribute('href', delete_url);
      }

      function confirm_terima(terima_url) {
        $('#terimaBon').modal('show', {
          backdrop: 'static'
        });
        // terima_url format: ApprovedBon-ID-Dept-Nama-Jabatan
        var parts = terima_url.split('-');
        if (parts.length > 1) {
          document.getElementById('id_bon_approve').value = parts[1];
        }
      }
    </script>