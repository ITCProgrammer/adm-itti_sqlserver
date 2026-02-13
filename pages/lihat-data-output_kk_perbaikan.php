<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Tutup KK (perbaikan)</title>
</head>
<body>
<?php
$Awal   = isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir  = isset($_POST['akhir']) ? $_POST['akhir'] : '';
$GShift = isset($_POST['gshift']) ? $_POST['gshift'] : '';
$jamA   = isset($_POST['jam_awal']) ? $_POST['jam_awal'] : '';
$jamAr  = isset($_POST['jam_akhir']) ? $_POST['jam_akhir'] : '';

if (strlen($jamA) == 5) {
  $start_date = $Awal . " " . $jamA;
} else {
  $start_date = $Awal . " 0" . $jamA;
}
if (strlen($jamAr) == 5) {
  $stop_date = $Akhir . " " . $jamAr;
} else {
  $stop_date = $Akhir . " 0" . $jamAr;
}
?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"> Filter Laporan OUTPUT KK (Perbaikan)</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>

  <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
    <div class="box-body">
      <div class="form-group">
        <div class="col-sm-2">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="awal" type="date" class="form-control pull-right" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off"/>
          </div>
        </div>
        <div class="col-sm-1">
          <input type="text" class="form-control timepicker" name="jam_awal" placeholder="00:00" value="<?php echo $jamA; ?>" autocomplete="off">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-2">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="akhir" type="date" class="form-control pull-right" placeholder="Tanggal Akhir" value="<?php echo $Akhir;  ?>" autocomplete="off"/>
          </div>
        </div>
        <div class="col-sm-1">
          <input type="text" class="form-control timepicker" name="jam_akhir" placeholder="00:00" value="<?php echo $jamAr; ?>" autocomplete="off">
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-2">
          <select name="gshift" class="form-control select2">
            <option value="ALL" <?php if($GShift=="ALL"){ echo "SELECTED";}?>>ALL</option>
            <option value="A" <?php if($GShift=="A"){ echo "SELECTED";}?>>A</option>
            <option value="B" <?php if($GShift=="B"){ echo "SELECTED";}?>>B</option>
            <option value="C" <?php if($GShift=="C"){ echo "SELECTED";}?>>C</option>
          </select>
        </div>
      </div>
    </div>
    <div class="box-footer">
      <div class="col-sm-2">
        <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>
      </div>
      <div class="pull-right">
        <input type="button" class="btn btn-block btn-social btn-linkedin btn-sm" name="lihat" value="Kembali" onClick="window.location.href='CWarnaDye'">
      </div>
    </div>
  </form>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Data OUTPUT KK (Perbaikan)</h3><br>
        <?php if(isset($_POST['awal']) && $_POST['awal']!="") { ?>
          <b>Periode: <?php echo $_POST['awal']." to ".$_POST['akhir']; ?></b>
        <?php } ?><br>
        <?php if(isset($_POST['gshift']) && $_POST['gshift']!="") { ?>
          <b>Shift: <?php echo $_POST['gshift']; ?></b>
        <?php } ?>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-hover table-striped nowrap" id="example1" style="width:100%">
          <thead class="bg-blue">
            <tr>
              <th><div align="center">No</div></th>
              <th><div align="center">Shift</div></th>
              <th><div align="center">Tgl Input</div></th>
              <th><div align="center">No KK</div></th>
              <th><div align="center">No Demand</div></th>
              <th><div align="center">Pelanggan</div></th>
              <th><div align="center">PO</div></th>
              <th><div align="center">Order</div></th>
              <th><div align="center">Item</div></th>
              <th><div align="center">Jenis Kain</div></th>
              <th><div align="center">Warna</div></th>
              <th><div align="center">No Warna</div></th>
              <th><div align="center">Prod. Order/Lot</div></th>
              <th><div align="center">Roll</div></th>
              <th><div align="center">Bruto</div></th>
              <th><div align="center">Asal kartu</div></th>
              <th><div align="center">Tindakan Perbaikan</div></th>
              <th><div align="center">Rincian Perbaikan</div></th>
              <th><div align="center">Hasil</div></th>
              <th><div align="center">Colorist</div></th>
            </tr>
          </thead>
          <tbody>
          <?php
            $no = 1;

            $params = array();
            $whereParts = array("t.dept = 'CQA'");

            if ($GShift != "" && $GShift != "ALL") {
              $whereParts[] = "t.shift = ?";
              $params[] = $GShift;
            }

            if ($Awal != "" && $Akhir != "") {
              $whereParts[] = "t.tgl_update BETWEEN ? AND ?";
              $params[] = $start_date . ":00";
              $params[] = $stop_date . ":00";
            }

            $whereSql = "";
            if (count($whereParts) > 0) {
              $whereSql = "WHERE " . implode(" AND ", $whereParts);
            }

            $sql = "
              SELECT *
              FROM db_adm.tbl_output_kk_perbaikan t
              $whereSql
              ORDER BY t.id ASC
            ";

            $qry1 = sqlsrv_query($cona, $sql, $params);
            if ($qry1 === false) {
              die(print_r(sqlsrv_errors(), true));
            }

            while ($row1 = sqlsrv_fetch_array($qry1, SQLSRV_FETCH_ASSOC)) {

              $tgl_update = $row1['tgl_update'];
              if ($tgl_update instanceof DateTime) {
                $tgl_update_show = $tgl_update->format('Y-m-d H:i:s');
              } else {
                $tgl_update_show = $tgl_update;
              }

              $pos = strpos($row1['pelanggan'], "/");
              if ($pos > 0) {
                $lgg1 = substr($row1['pelanggan'], 0, $pos);
                $byr1 = substr($row1['pelanggan'], $pos + 1, 100);
              } else {
                $lgg1 = $row1['pelanggan'];
                $byr1 = substr($row1['pelanggan'], $pos, 100);
              }
          ?>
            <tr bgcolor="<?php echo $bgcolor; ?>">
              <td align="center"><?php echo $no; ?></td>
              <td align="center"><?php echo $row1['shift']; ?></td>

              <td align="center">
                <a data-type="date" data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $tgl_update_show; ?>" class="tgl_update" href="javascipt:void(0)">
                  <?php echo $tgl_update_show; ?>
                </a>
              </td>
              <td align="center"><?php echo $row1['nokk']; ?></td>
              <td align="center"><?php echo $row1['nodemand']; ?></td>
              <td><?php echo $lgg1; ?></td>
              <td align="center"><?php echo $row1['no_po']; ?></td>
              <td align="center"><?php echo $row1['no_order']; ?></td>
              <td align="center"><?php echo $row1['no_item']; ?></td>
              <td><?php echo substr($row1['jenis_kain'], 0, 15) . "..."; ?></td>
              <td align="left"><?php echo substr($row1['warna'], 0, 10) . "..."; ?></td>
              <td align="left"><?php echo $row1['no_warna']; ?></td>
              <td align="center"><?php echo $row1['lot']; ?></td>

              <td align="center">
                <a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['jml_roll'] ?>" class="jml_roll" href="javascipt:void(0)">
                  <?php echo $row1['jml_roll']; ?>
                </a>
              </td>

              <td align="center">
                <a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['bruto'] ?>" class="bruto" href="javascipt:void(0)">
                  <?php echo $row1['bruto']; ?>
                </a>
              </td>

              <td align="center">
                <a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['asal_kartu'] ?>" class="asal_kartu" href="javascipt:void(0)">
                  <?php echo $row1['asal_kartu']; ?>
                </a>
              </td>

              <td align="center">
                <a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['tindakan_perbaikan'] ?>" class="tindakan_perbaikan" href="javascipt:void(0)">
                  <?php echo $row1['tindakan_perbaikan']; ?>
                </a>
              </td>

              <td align="center">
                <a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['rincian_perbaikan'] ?>" class="rincian_perbaikan" href="javascipt:void(0)">
                  <?php echo $row1['rincian_perbaikan']; ?>
                </a>
              </td>

              <td align="center">
                <a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['hasil'] ?>" class="hasil" href="javascipt:void(0)">
                  <?php echo $row1['hasil']; ?>
                </a>
              </td>

              <td align="center">
                <a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['colorist'] ?>" class="colorist" href="javascipt:void(0)">
                  <?php echo $row1['colorist']; ?>
                </a>
              </td>
            </tr>
          <?php
              $no++;
            }
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_del" tabindex="-1" >
  <div class="modal-dialog modal-sm" >
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
        <button type="button" class="close"  data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" style="text-align:center;">Are you sure to delete all data ?</h4>
      </div>

      <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
        <a href="#" class="btn btn-danger" id="delete_link">Delete</a>
        <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div id="CWarnaDyeEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

<script type="text/javascript">
  function confirm_delete(delete_url)
  {
    $('#modal_del').modal('show', {backdrop: 'static'});
    document.getElementById('delete_link').setAttribute('href' , delete_url);
  }
</script>

<script>
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>

</body>
</html>
