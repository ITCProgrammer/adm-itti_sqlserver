<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Bulanan BRS</title>

</head>
<body>
<?php
$tahun = isset($_POST['tahun']) ? (int)$_POST['tahun'] : date('Y');
$bulanDipilih = isset($_POST['bulan']) ? $_POST['bulan'] : 'all';	
$tahunSebelumnya = $tahun - 1;

$bulan = [
    "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
    "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
];
	
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
    <h3 class="box-title"> Filter Laporan Bulanan Brushing</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <form method="get" action="pages/cetak/cetak_laporan_bln_brs_excel.php" target="_blank" class="form-horizontal" id="formLaporan">
    <div class="box-body">
      <div class="form-group">
        <div class="col-sm-2">
          <select name="tahun" id="tahun" class="form-control form-control-sm select2">
        	<?php  
			  $thn_skr = date('Y');
			  for ($i = 2023; $i <= $thn_skr; $i++): ?>
            <option value="<?= $i ?>" <?= $i == $tahun ? "selected" : "" ?>><?= $i ?></option>
        	<?php endfor; ?>
    	</select>
        </div>
		<div class="col-sm-2">
          <select name="bulan" id="bulan" class="form-control form-control-sm select2">
        	<option value="all" <?= $bulanDipilih === 'all' ? "selected" : "" ?>>Pilih Bulan</option>
        	<?php foreach ($bulan as $index => $nama): ?>
            <?php $value = $index + 1; ?>
            <option value="<?= $value ?>" <?= (int)$bulanDipilih === $value ? "selected" : "" ?>>
                <?= $nama ?>
            </option>
        <?php endforeach; ?>
    	</select>
        </div>  
		<div class="col-sm-2" hidden="hide" >
			<input class="form-check-input" type="checkbox" value="1" id="previewCheckbox">
    		<label class="form-check-label" for="previewCheckbox">
      		Preview
    </label>
		</div>  
        <!-- /.input group -->
      </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
<!--
      <div class="col-sm-2">
        <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>		  
      </div>
-->
	  <div class="col-sm-2">
      <button type="submit" id="submitButton" class="btn btn-block btn-social btn-linkedin btn-sm" style="width: 60%">
        Download Excel <i class="fa fa-download"></i>
      </button>
    </div>	
    </div>
    <!-- /.box-footer -->
  </form>
</div>
<script>
  const checkbox = document.getElementById("previewCheckbox");
  const form = document.getElementById("formLaporan");
  const submitButton = document.getElementById("submitButton");

  checkbox.addEventListener("change", function () {
    if (this.checked) {
      form.action = "pages/cetak/cetak_laporan_bln_brs_view.php";
      submitButton.innerHTML = 'Preview <i class="fa fa-eye"></i>';
    } else {
      form.action = "pages/cetak/cetak_laporan_bln_brs_excel.php";
      submitButton.innerHTML = 'Download Excel <i class="fa fa-download"></i>';
    }
  });
</script>	
</body>
</html>