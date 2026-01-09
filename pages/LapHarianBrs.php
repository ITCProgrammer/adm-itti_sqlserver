<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Filter Laporan Harian Brushing</title>
</head>
<body>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Filter Laporan Harian Brushing</h3>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <form method="get" action="pages/cetak/cetak_laporan_brs_excel.php" target="_blank" class="form-horizontal" id="formLaporan">
    <div class="box-body">
      <div class="form-group">
        <div class="col-sm-2">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="awal" type="text" class="form-control pull-right" id="datepicker" placeholder="Tanggal Awal" autocomplete="off" required />
          </div>
        </div>
		<div class="col-sm-2"  hidden="hide" >
			<input class="form-check-input" type="checkbox" value="1" id="previewCheckbox">
    		<label class="form-check-label" for="previewCheckbox">
      		Preview
    </label>
		</div>	
      
      <!-- <div class="form-group">
        <div class="col-sm-1">
          <div class="input-group">
            <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
            <input name="jam" type="time" class="form-control" placeholder="Jam" required />
          </div>
        </div>
      </div>
      </div> -->
      <!-- <div class="form-group">
        <div class="col-sm-3">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="akhir" type="text" class="form-control pull-right" id="datepicker1" placeholder="Tanggal Akhir" autocomplete="off" required />
          </div>
        </div>
      </div> -->
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <div class="col-sm-2">
      <button type="submit" id="submitButton" class="btn btn-block btn-social btn-linkedin btn-sm" style="width: 60%">
        Download Excel <i class="fa fa-download"></i>
      </button>
<!--	  <a href="pages/cetak/cetak_laporan_brs_view.php?awal=<?php $_POST['awal'];?>" class="btn btn-warning pull-right" target="_blank">Print <i class="fa fa-print"></i></a> 	-->
    </div>
    <!-- /.box-footer -->
  </form>
</div>
</body>
</html>
<script>
  const checkbox = document.getElementById("previewCheckbox");
  const form = document.getElementById("formLaporan");
  const submitButton = document.getElementById("submitButton");

  checkbox.addEventListener("change", function () {
    if (this.checked) {
      form.action = "pages/cetak/cetak_laporan_brs_view.php";
      submitButton.innerHTML = 'Preview <i class="fa fa-eye"></i>';
    } else {
      form.action = "pages/cetak/cetak_laporan_brs_excel.php";
      submitButton.innerHTML = 'Download Excel <i class="fa fa-download"></i>';
    }
  });
</script>	