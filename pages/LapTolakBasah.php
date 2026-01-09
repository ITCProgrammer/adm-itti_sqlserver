<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Tolak Basah</title>

</head>
<body>
<?php
$Awal	= isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : '';
$GShift	= isset($_POST['gshift']) ? $_POST['gshift'] : '';
$jamA 	= isset($_POST['jam_awal']) ? $_POST['jam_awal'] : '';
$jamAr 	= isset($_POST['jam_akhir']) ? $_POST['jam_akhir'] : '';	
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
 if($Awal!="" and $Akhir!="" && $jamA=="" && $jamAr==""){ 
    $where=" AND DATE_FORMAT( tgl_update, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' ";
  }
 else if($Awal!="" && $Akhir!="" && $jamA!="" && $jamAr!=""){ 
    $where=" AND tgl_update BETWEEN '$start_date' AND '$stop_date' ";
  }else{ $where=" ";}  
 if($Awal!="" and $Akhir!=""){
    $qry1=mysqli_query($cond,"SELECT 
                                  t.*,
                                  p.hasil_tindak_lanjut,
                                  p.tindak_lanjut,
                                  p.tindakan,
                                  p.pemberi_instruksi,
                                  p.keterangan  
                                FROM 
                                  tbl_cocok_warna_dye t
                                LEFT JOIN penyelesaian_tolakbasah p
                                  ON t.id = p.id_cocok_warna 
                                WHERE 
                                  t.status_warna 
                                like '%TOLAK BASAH%' $where $shft 
                                ORDER BY 
                                  t.id DESC");
  }else{
    $qry1=mysqli_query($cond,"SELECT 
                                  t.*,
                                  p.hasil_tindak_lanjut,
                                  p.tindak_lanjut,
                                  p.tindakan,
                                  p.pemberi_instruksi,
                                  p.keterangan
                                FROM 
                                  tbl_cocok_warna_dye t
                                LEFT JOIN penyelesaian_tolakbasah p
                                  ON t.id = p.id_cocok_warna 
                                WHERE t.status_warna like '%TOLAK BASAH%' 
                                ORDER BY 
                                  t.id DESC 
                                LIMIT 100");
  }
?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"> Filter Laporan Tolak Basah</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
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
        <!-- /.input group -->
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
        <!-- /.input group -->
      </div>
	  	
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <div class="col-sm-2">
        <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>		  
      </div>
	  	
    </div>
    <!-- /.box-footer -->
  </form>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Data Laporan Tolak Basah</h3>
        <?php if ($Awal != "") { ?>
          <div class="pull-right">
            <a href="pages/cetak/cetak_tolak_basah.php?&awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&jam_awal=<?php echo $jamA; ?>&jam_akhir=<?php echo $jamAr; ?>" class="btn btn-success " target="_blank" data-toggle="tooltip" data-html="true" title="Laporan Tolak Basah"><i class="fa fa-file-excel-o"></i> Laporan Tolak Basah</a>
          </div>
          <?php } ?>
        </div>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" id="example3" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th><div align="center">No</div></th>
            <th><div align="center">Action</div></th>
            <th><div align="center">Tgl Celup</div></th>
            <th><div align="center">No KK</div></th>
            <th><div align="center">No Demand</div></th>
            <th><div align="center">Pelanggan</div></th>
            <th><div align="center">Buyer</div></th>
            <th><div align="center">PO</div></th>
            <th><div align="center">Order</div></th>
            <th><div align="center">Item</div></th>
            <th><div align="center">Jenis Kain</div></th>
            <th><div align="center">Warna</div></th>
            <th><div align="center">No Warna</div></th>
            <th><div align="center">Lot</div></th>
            <th><div align="center">Roll</div></th>
            <th><div align="center">Bruto</div></th>
            <th><div align="center">No Mesin</div></th>
            <th><div align="center">Status Warna</div></th>
            <th><div align="center">Colorist Dye</div></th>
            <th><div align="center">Colorist Qcf</div></th>
            <th><div align="center">Keterangan</div></th>
            <th><div align="center">Hasil Tindak Lanjut</div></th>
            <th><div align="center">Tindakan</div></th>
            <th><div align="center">Pemberi Tindakan</div></th>
            <th><div align="center">Keterangan Tindakan</div></th>
            </tr>
        </thead>
        <tbody>
		<?php
    $no=1;
        while($row1=mysqli_fetch_array($qry1)){
          $q_user = mysqli_query($cona,"SELECT * FROM tbl_user_tindaklanjut WHERE id = '$row1[pemberi_instruksi]'");
          $row_user = mysqli_fetch_array($q_user);
          $qdye = mysqli_query($con,"SELECT 
                                        b.langganan,
                                        b.po,
                                        b.no_order,
                                        b.jenis_kain,
                                        CASE
                                            WHEN b.no_item = '' OR b.no_item = null THEN b.no_hanger
                                            ELSE b.no_item 
                                        END AS no_item,
                                        b.warna,
                                        b.no_warna,
                                        b.no_mesin,
                                        a.acc_keluar,
                                        a.tgl_buat,
                                        a.nokk,
                                        b.rol,
                                        b.bruto
                                    FROM
                                        tbl_hasilcelup a
                                        LEFT JOIN tbl_montemp c ON a.id_montemp = c.id
                                        LEFT JOIN tbl_schedule b ON c.id_schedule = b.id
                                    WHERE
                                        a.nodemand LIKE '%$row1[nodemand]%'
                                    ORDER BY 
	                                    a.id DESC LIMIT 1");
              $row_dye=mysqli_fetch_array($qdye);
              $pos=strpos($row1['pelanggan'],"/");
              if($pos>0) {
              $lgg1=substr($row1['pelanggan'],0,$pos);
              $byr1=substr($row1['pelanggan'],$pos+1,100);	
              }else{
                $lgg1=$row1['pelanggan'];
                $byr1=substr($row1['pelanggan'],$pos,100);
              }	
		?>	
          <tr> 
            <td align="left"><?= $no++; ?></td>
            <td align="left"><a href="?p=Penyelesaian-tolakbasah&id=<?php echo $row1['id']; ?>" class="fa fa-pencil-square-o btn"><span class="label label-danger"></span></a></td>
            <td align="left"><?= $row1['tgl_celup']?></td>
            <td align="left"><?= $row_dye['nokk']?></td>
            <td align="left"><a target="_BLANK" href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=<?= $row1['nodemand']; ?>"><?= $row1['nodemand'];?></a></td>
            <td align="center"><?= $lgg1?></td>
            <td align="center"><?= $byr1?></td>
            <td align="center"><?= $row1['no_po'];?></td>
            <td align="center"><?= $row1['no_order'];?></td>
            <td align="center"><?= $row1['no_item'];?></td>
            <?php
                $full_text = htmlspecialchars($row1['jenis_kain']);
                $max_length = 25; // panjang maksimum sebelum dipotong

                if (strlen($full_text) > $max_length) {
                    $short_text = substr($full_text, 0, $max_length);
                    $element_id = 'kain_' . md5($row1['id']); // id unik supaya tidak bentrok

                    echo "<td align='left'>
                            <span id='{$element_id}_short'>
                                {$short_text}...
                                <a href='#' onclick=\"toggleKain('$element_id'); return false;\">lihat selengkapnya</a>
                            </span>
                            <span id='{$element_id}_full' style='display:none;'>
                                {$full_text}
                                <a href='#' onclick=\"toggleKain('$element_id'); return false;\">sembunyikan</a>
                            </span>
                          </td>";
                } else {
                    echo "<td align='left'>{$full_text}</td>";
                }
              ?>
            <td align="left"><?php echo $row1['warna'];?></td>
            <td align="left"><?php echo $row1['no_warna'];?></td>
            <td align="right"><?php echo $row1['lot'];?></td>
            <td align="right"><?php echo $row1['jml_roll'] ?></td>
            <td align="right"><?php echo $row1['bruto'] ?></td>
            <td align="center"><?php echo $row_dye['no_mesin'] ?></td>
            <td align="left"><?php echo $row1['status_warna'] ?></td>
            <td align="left"><?php echo !empty($row_dye['acc_keluar']) ? $row_dye['acc_keluar'] : $row1['colorist_dye']; ?></td>
            <td align="center"><?php echo $row1['colorist_qcf'] ?></td>
            <td align="left"><?php echo $row1['ket'] ?></td>
            <td align="left"><?php echo $row1['hasil_tindak_lanjut'] ?></td>
            <td align="left"><?php echo $row1['tindakan'] ?></td>
            <td align="center"><?php echo $row_user['nama'] ?></td>
            <td align="left"><?php echo htmlspecialchars($row1['tindak_lanjut'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
	  <br>
      </div>
    </div>
  </div>
</div>
</div>	
	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});

	</script>
</body>
</html>

	</script>
</body>
</html>
<script>
function toggleKain(id) {
    var shortEl = document.getElementById(id + '_short');
    var fullEl = document.getElementById(id + '_full');

    if (shortEl.style.display === 'none') {
        shortEl.style.display = '';
        fullEl.style.display = 'none';
    } else {
        shortEl.style.display = 'none';
        fullEl.style.display = '';
    }
}
</script>