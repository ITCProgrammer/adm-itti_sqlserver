<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Harian Stoppage Mesin</title>

</head>
<body>
<?php
$Awal	= isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : '';
$Dept	= isset($_POST['dept']) ? $_POST['dept'] : '';	
	
function formatJamMenit($decimalHours) {
    if ($decimalHours == 0) {
        return "0 Jam 0 Menit";
    }

    $jam = floor($decimalHours); // Ambil bagian jam bulat
    $menit = round(($decimalHours - $jam) * 60); // Ambil bagian menit dari sisa jam

    return $jam . " Jam " . $menit . " Menit";
}
	
function formatJamMenit1($decimalMinutes) {
    if ($decimalMinutes == 0) {
        return "0 Jam 0 Menit";
    }

    $jam = floor($decimalMinutes / 60); // Ambil bagian jam
    $menit = round($decimalMinutes % 60); // Ambil sisa menit

    return $jam . " Jam " . $menit . " Menit";
}	
?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"> Filter Laporan Stoppage Mesin</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
    <div class="box-body">
      <div class="form-group">
        <div class="col-sm-3">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="awal" type="text" class="form-control pull-right" id="datepicker" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off"/>
          </div>
        </div>
        <!-- /.input group -->
      </div>
      <div class="form-group">
        <div class="col-sm-3">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="akhir" type="text" class="form-control pull-right" id="datepicker1" placeholder="Tanggal Akhir" value="<?php echo $Akhir;  ?>" autocomplete="off"/>
          </div>
        </div>
        <!-- /.input group -->
      </div>
	  <div class="form-group">
        <div class="col-sm-3">
		<?php if($_SESSION['dept10']=="RMP" or $_SESSION['dept10']=="PPC" or $_SESSION['dept10']=="DIT" or $_SESSION['dept10']=="MNF" or strtolower($_SESSION['nama10'])=="eto" or strtolower($_SESSION['nama10'])=="angela"){ ?>
            <select class="form-control select2" name="dept">
							<option value="" disabled selected>Pilih Departemen</option>				
							<option value="QCF" <?php if ($Dept == "QCF") {
													echo "SELECTED";
												} ?>>QCF</option>
							<option value="PPC" <?php if ($Dept == "PPC") {
													echo "SELECTED";
												} ?>>PPC</option>
							<option value="FIN" <?php if ($Dept == "FIN") {
													echo "SELECTED";
												} ?>>FIN</option>
							<option value="BRS" <?php if ($Dept == "BRS") {
													echo "SELECTED";
												} ?>>BRS</option>
							<option value="LAB" <?php if ($Dept == "LAB") {
													echo "SELECTED";
												} ?>>LAB</option>	
							<option value="DYE" <?php if ($Dept == "DYE") {
													echo "SELECTED";
												} ?>>DYE</option>
							<option value="KNT" <?php if ($Dept == "KNT") {
													echo "SELECTED";
												} ?>>KNT</option>
							<option value="GKG" <?php if ($Dept == "GKG") {
													echo "SELECTED";
												} ?>>GKG</option>
							<option value="TAS" <?php if ($Dept == "TAS") {
													echo "SELECTED";
												} ?>>TAS</option>
						</select>	
		<?php } else{ ?>
			<select class="form-control select2" name="dept">
<!--							<option value="" disabled selected>Pilih Departemen</option>-->
							<option value="<?php echo $_SESSION['dept10'];?>" <?php if ($Dept == $_SESSION['dept10']) {
													echo "SELECTED";
												} ?>><?php echo $_SESSION['dept10'];?></option>
							
						</select>
		<?php } ?>
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
        <h3 class="box-title">Data Stoppage Mesin</h3><br>
        <?php if($_POST['awal']!="") { ?><b>Periode: <?php echo $_POST['awal']." to ".$_POST['akhir']; ?></b>
		<?php } ?>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" id="example3" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th><div align="center">No</div></th>
            <th><div align="center">Tgl Buat</div></th>
            <th><div align="center">Prod. Order</div></th>
            <th><div align="center">Demand</div></th>
            <th><div align="center">Buyer</div></th>
            <th><div align="center">Langganan</div></th>
            <th><div align="center">PO</div></th>
            <th><div align="center">Order</div></th>
            <th><div align="center">Hanger</div></th>
            <th><div align="center">Jenis Kain</div></th>
            <th><div align="center">Lebar &amp; Gramasi</div></th>
            <th><div align="center">Lot</div></th>
            <th><div align="center">Delivery</div></th>
            <th><div align="center">Warna</div></th>
            <th><div align="center">Qty Order</div></th>
            <th><div align="center">Durasi</div></th>            
            <th><div align="center">Kode Stop</div></th>
            <th><div align="center">Mesin</div></th>
			<th><div align="center">Operator</div></th>  
            </tr>
        </thead>
        <tbody>
          <?php
	$no=1;
			if($Awal!="" and $Dept!=""){ $Where =" WHERE DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' AND dept= '$Dept' "; }
			else
			if($Awal!=""){ $Where =" WHERE DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' "; }
			else
			if($Dept!=""){ $Where =" WHERE dept= '$Dept'"; }
			
			else
			if($Awal=="" and $Order=="" and $Dept==""){ $Where =" WHERE DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' "; }
			$qry1=mysqli_query($cona,"SELECT * FROM tbl_stoppage $Where ORDER BY id ASC");
			while($row1=mysqli_fetch_array($qry1)){				
		 ?>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center"><?php echo $no; ?></td>
            <td align="center"><?php echo $row1['tgl_buat'];?></td>
            <td><?php echo $row1['nokk'];?></td>
            <td><?php echo $row1['nodemand'];?></td>
            <td><?php echo $row1['buyer'];?></td>
            <td><?php echo $row1['langganan'];?></td>
            <td align="center"><?php echo $row1['po'];?></td>
            <td align="center"><?php echo $row1['no_order'];?></td>
            <td align="center" valign="top"><?php echo $row1['no_hanger'];?></td>
            <td><?php echo $row1['jenis_kain'];?></td>
            <td align="center"><?php echo $row1['lebar']."x".$row1['gramasi'];?></td>
            <td align="center"><?php echo $row1['lot'];?></td>
            <td align="center"><?php echo $row1['tgl_delivery'];?></td>
            <td align="center"><?php echo $row1['warna'];?></td>
            <td align="right"><?php echo $row1['qty_order'];?></td>
            <td align="center"><?php echo formatJamMenit($row1['durasi_jam_stop']);?></td>            
            <td align="center"><?php echo $row1['kode_stop'];?></td>
			<td align="center"><?php echo $row1['mesin'];?></td>  
			<td align="center"><?php echo $row1['operator'];?></td>  
            </tr>
          <?php	$no++;  } ?>
        </tbody>
      </table>
      </div>
    </div>
  </div>
</div>
</div>	
<div class="row">
  <div class="col-xs-6">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Data Stoppage Mesin Per Kategori</h3><br>
        <?php if($_POST['awal']!="") { ?><b>Periode: <?php echo $_POST['awal']." to ".$_POST['akhir']; ?></b>
		<?php } ?>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" id="example1" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th><div align="center">No</div></th>
            <th><div align="center">Mesin</div></th>
            <th><div align="center">LM</div></th>
            <th><div align="center">KM</div></th>
            <th><div align="center">KO</div></th>
            <th><div align="center">AP</div></th>
            <th><div align="center">PA</div></th>
            <th><div align="center">PM</div></th>
            <th><div align="center">GT</div></th>
            <th><div align="center">TG</div></th>
            </tr>
        </thead>
        <tbody>
          <?php
	$no=1;
			if($Awal!="" and $Dept!=""){ $Where =" WHERE DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' AND dept= '$Dept' "; }
			else
			if($Awal!=""){ $Where =" WHERE DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' "; }
			else
			if($Dept!=""){ $Where =" WHERE dept= '$Dept'"; }
			
			else
			if($Awal=="" and $Order=="" and $Dept==""){ $Where =" WHERE DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' "; }
			$qry1=mysqli_query($cona,"SELECT 
    ts.mesin,
    SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
    SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
    SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
    SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
    SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
    SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
    SUM(CASE WHEN ts.kode_stop = 'GT' THEN ts.durasi_jam_stop ELSE 0 END) AS GT,
    SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG
FROM tbl_stoppage ts
$Where
GROUP BY ts.mesin");
			while($row1=mysqli_fetch_array($qry1)){	
	
		 ?>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center"><?php echo $no; ?></td>
            <td align="center"><?php echo $row1['mesin'];?></td>
            <td align="center"><?php echo formatJamMenit($row1['LM']);?></td>
            <td align="center"><?php echo formatJamMenit($row1['KM']);?></td>
            <td align="center"><?php echo formatJamMenit($row1['KO']);?></td>
            <td align="center"><?php echo formatJamMenit($row1['AP']);?></td>
            <td align="center"><?php echo formatJamMenit($row1['PA']);?></td>
            <td align="center"><?php echo formatJamMenit($row1['PM']);?></td>
            <td align="center"><?php echo formatJamMenit($row1['GT']);?></td>
            <td align="center"><?php echo formatJamMenit($row1['TG']);?></td>
            </tr>
          <?php	$no++;  } ?>
        </tbody>
      </table>
      </div>
    </div>
  </div>
</div>
  <div class="col-xs-6">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Data Stoppage Mesin Per Shift</h3><br>
        <?php if($_POST['awal']!="") { ?><b>Periode: <?php echo $_POST['awal']." to ".$_POST['akhir']; ?></b>
		<?php } ?>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" id="example4" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th><div align="center">No</div></th>
            <th><div align="center">Mesin</div></th>
            <th><div align="center">Shift A</div></th>
            <th><div align="center">Shift B</div></th>
            <th><div align="center">Shift C</div></th>
            </tr>
        </thead>
        <tbody>
          <?php
	$no=1;
			if($Awal!="" and $Dept!=""){ $Where =" WHERE DATE_FORMAT( ts.tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' AND ts.dept= '$Dept' "; }
			else
			if($Awal!=""){ $Where =" WHERE DATE_FORMAT( ts.tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' "; }
			else
			if($Dept!=""){ $Where =" WHERE ts.dept= '$Dept'"; }
			
			else
			if($Awal=="" and $Order=="" and $Dept==""){ $Where =" WHERE DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir' "; }
			$qry1=mysqli_query($cona,"SELECT 
					ts.mesin,
					SUM(CASE WHEN tso.shift = 'A' THEN ts.durasi_jam_stop ELSE 0 END) AS A,
					SUM(CASE WHEN tso.shift = 'B' THEN ts.durasi_jam_stop ELSE 0 END) AS B,
					SUM(CASE WHEN tso.shift = 'C' THEN ts.durasi_jam_stop ELSE 0 END) AS C
				FROM tbl_stoppage ts left join tbl_shift_operator tso on ts.operator = tso.nama
				$Where
				GROUP BY ts.mesin");
			while($row1=mysqli_fetch_array($qry1)){	
	
		 ?>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center"><?php echo $no; ?></td>
            <td align="center"><?php echo $row1['mesin'];?></td>
            <td align="center"><?php echo formatJamMenit($row1['A']);?></td>
            <td align="center"><?php echo formatJamMenit($row1['B']);?></td>
            <td align="center"><?php echo formatJamMenit($row1['C']);?></td>
            </tr>
          <?php	$no++;  } ?>
        </tbody>
      </table>
      </div>
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