<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Bulanan FIN</title>

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
?>	
<?php	
$Awal	= isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : '';
$Awal_Sebelum = date('Y-m-d', strtotime($Awal . ' -1 day'));
$Akhir_Sebelum = date('Y-m-d', strtotime($Akhir . ' -1 day'));	
?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"> Filter Laporan Bulanan Finishing</h3>
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
		<?php if($_POST['awal']!="") {  ?>
		<a href="pages/cetak/cetak_lapharianfin.php?awal=<?php echo $_POST['awal']; ?>&akhir=<?php echo $_POST['akhir']; ?>" class="btn btn-warning btn-sm pull-right" target="_blank"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</a> <br> <?php } ?>
        <h3 class="box-title">Data Produksi Finishing</h3><br>		  
        <?php if($_POST['awal']!="") { ?><b>Periode: <?php echo $_POST['awal']." to ".$_POST['akhir']; ?></b>
		<?php } ?>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" id="example10" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th rowspan="3" align="center" valign="middle">NO.</th>
            <th rowspan="3" align="center" valign="middle">BULAN</th>
            <th colspan="14">MESIN STENTER</th>
            <th colspan="11">MESIN NON STENTER</th>
            <th colspan="3">TOTAL PRODUKSI KESELURUHAN</th>
            </tr>
          <tr>
            <th rowspan="2">FINISHING AKHIR (A)</th>
            <th rowspan="2">PRESET (B)</th>
            <th rowspan="2">TARIK LEBAR (C)</th>
            <th rowspan="2">FIN 1X (D)</th>
            <th colspan="5">OVEN</th>
            <th rowspan="2">NAIK SUHU (J)</th>
            <th rowspan="2">PADDER (K)</th>
            <th rowspan="2">POTONG PINGGIR (L)</th>
            <th rowspan="2">FIN ULANG (M)</th>
            <th rowspan="2">TOTAL PRODUKSI STENTER</th>
            <th colspan="3">COMPACT</th>
            <th rowspan="2">BELAH (P)</th>
            <th colspan="5">OVEN</th>
            <th rowspan="2">LIPAT (U)</th>
            <th rowspan="2">STEAMER (V)</th>
            <th rowspan="2">KAIN JADI<br />
              (A+K+L+M+N+O+Q)</th>
            <th rowspan="2">LOSS<br />
              (C+F+G+I+K+M+O+S)</th>
            <th rowspan="2">Total Ganti Kain</th>
            </tr>
          <tr>
            <th>OVEN STENTER FLEECE (E)</th>
            <th>OVEN FLEECE ULANG (F)</th>
            <th>OVEN STENTER ULANG (G)</th>
            <th>OVEN STENTER (H)</th>
            <th>OVEN STENTER DYEING (I)</th>
            <th>NORMAL (N)</th>
            <th>COMPACT PERBAIKAN (O)</th>
            <th>TOTAL PRODUKSI COMPACT</th>
            <th>OVEN KRAGH (Q)</th>
            <th>OVEN KERING ( R )</th>
            <th>OVEN DYEING (S)</th>
            <th>F 1X OVEN FONG (T)</th>
            <th>TOTAL PRODUKSI OVEN</th>
            </tr>
        </thead>
        <tbody>
          <tr>
			<?php
				$sqlsK_fin_jadi_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya' 
				and a.nama_mesin = 'FNJ1'");		
			$dts_K_fin_jadi_t_s	= sqlsrv_fetch_array($sqlsK_fin_jadi_t_s, SQLSRV_FETCH_ASSOC);
			
			  $sqlsK_preset_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin = 'PRE1'");		
			$dts_K_preset_t_s	= sqlsrv_fetch_array($sqlsK_preset_t_s, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_tarik_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and nama_mesin = 'FNJ1'
				and proses like '%Tarik Lebar%'");		
			$dts_K_tarik_t_s	= sqlsrv_fetch_array($sqlsK_tarik_t_s, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_fin_1x_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin IN('FIN1','FIN2')");		
		   $dts_K_fin_1x_t_s	= sqlsrv_fetch_array($sqlsK_fin_1x_t_s, SQLSRV_FETCH_ASSOC);
			  
		   $sqlsK_ov_fl_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Fleece (Normal)%'");		
			$dts_K_ov_fl_t_s	= sqlsrv_fetch_array($sqlsK_ov_fl_t_s, SQLSRV_FETCH_ASSOC);	
			
			$sqlsK_ov_fl_ul_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Fleece Ulang (Normal)%'");		
			$dts_K_ov_fl_ul_t_s	= sqlsrv_fetch_array($sqlsK_ov_fl_ul_t_s, SQLSRV_FETCH_ASSOC);	
			
			$sqlsK_ov_ul_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Stenter Ulang (Normal)%'");		
			$dts_K_ov_ul_t_s	= sqlsrv_fetch_array($sqlsK_ov_ul_t_s, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_s_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Stenter (Normal)%'");		
			$dts_K_ov_s_t_s	= sqlsrv_fetch_array($sqlsK_ov_s_t_s, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_dye_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Stenter Dyeing (Bantu)%'");		
			$dts_K_ov_dye_t_s	= sqlsrv_fetch_array($sqlsK_ov_dye_t_s, SQLSRV_FETCH_ASSOC);	  
			  
		   $sqlsK_naik_suhu_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Naik Suhu%'");		
			$dts_K_naik_suhu_t_s	= sqlsrv_fetch_array($sqlsK_naik_suhu_t_s, SQLSRV_FETCH_ASSOC);	
		   
		   $sqlsK_padder_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin IN('PAD1','PAD2','PAD3','PAD4','PAD5')");		
		   $dts_K_padder_t_s	= sqlsrv_fetch_array($sqlsK_padder_t_s, SQLSRV_FETCH_ASSOC);
			
		   $sqlsK_pot_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and nama_mesin = 'FNJ1'
				and proses like '%Potong Pinggir%'");		
			$dts_K_pot_t_s	= sqlsrv_fetch_array($sqlsK_pot_t_s, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_fin_ulang_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya' 
				and a.nama_mesin IN('FNJ2','FNJ3','FNJ4','FNJ5','FNJ6')");		
			$dts_K_fin_ulang_t_s	= sqlsrv_fetch_array($sqlsK_fin_ulang_t_s, SQLSRV_FETCH_ASSOC);	  
			 
			$tot_prd_stenter_t_s =  $dts_K_fin_jadi_t_s['kering']+$dts_K_preset_t_s['kering']+$dts_K_tarik_t_s['kering']+$dts_K_fin_1x_t_s['kering']+$dts_K_ov_fl_t_s['kering']+$dts_K_ov_fl_ul_t_s['kering']+$dts_K_ov_ul_t_s['kering']+$dts_K_ov_s_t_s['kering']+$dts_K_ov_dye_t_s['kering']+$dts_K_naik_suhu_t_s['kering']+$dts_K_padder_t_s['kering']+$dts_K_pot_t_s['kering']+$dts_K_fin_ulang_t_s['kering'];
			  
			$sqlsK_compact_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin = 'CPT1'");		
			$dts_K_compact_t_s	= sqlsrv_fetch_array($sqlsK_compact_t_s, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_compact_fin_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin IN('CPF2','CPF3','CPF4')");		
			$dts_K_compact_fin_t_s	= sqlsrv_fetch_array($sqlsK_compact_fin_t_s, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_compact_dye_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')");		
			$dts_K_compact_dye_t_s	= sqlsrv_fetch_array($sqlsK_compact_dye_t_s, SQLSRV_FETCH_ASSOC); 
			$tot_compact_t_s =  $dts_K_compact_t_s['kering']+$dts_K_compact_fin_t_s['kering']+$dts_K_compact_dye_t_s['kering'];
			$sqlsB_fin_bl_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin = 'OPW1'");		
			$dts_B_fin_bl_t_s	= sqlsrv_fetch_array($sqlsB_fin_bl_t_s, SQLSRV_FETCH_ASSOC); 
			
			$sqlsK_ov_krh_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Kragh (Normal)%'");		
			$dts_K_ov_krh_t_s	= sqlsrv_fetch_array($sqlsK_ov_krh_t_s, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_krg_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Kering (Normal)%'");		
			$dts_K_ov_krg_t_s	= sqlsrv_fetch_array($sqlsK_ov_krg_t_s, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_dyeing_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Dyeing (Bantu)%'");		
			$dts_K_ov_dyeing_t_s = sqlsrv_fetch_array($sqlsK_ov_dyeing_t_s, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_ov_fix_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Fix Fong%'");		
			$dts_K_ov_fix_t_s = sqlsrv_fetch_array($sqlsK_ov_fix_t_s, SQLSRV_FETCH_ASSOC); 
			$tot_prd_ov_t_s = $dts_K_ov_krh_t_s['kering']+$dts_K_ov_krg_t_s['kering']+$dts_K_ov_dyeing_t_s['kering']+$dts_K_ov_fix_t_s['kering'];
			  
			$sqlsK_lipat_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Lipat (Normal)%'");		
			$dts_K_lipat_t_s = sqlsrv_fetch_array($sqlsK_lipat_t_s, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_steamer_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin = 'STM1'");		
			$dts_K_steamer_t_s	= sqlsrv_fetch_array($sqlsK_steamer_t_s, SQLSRV_FETCH_ASSOC);  
			$tot_kj_t_s = $dts_K_fin_jadi_t_s['kering']+$dts_K_padder_t_s['kering']+$dts_K_pot_t_s['kering']+$dts_K_fin_ulang_t_s['kering']+$dts_K_compact_t_s['kering']+$dts_K_compact_fin_t_s['kering']+$dts_K_compact_dye_t_s['kering']+$dts_K_ov_krh_t_s['kering'];
			$tot_loss_t_s = $dts_K_tarik_t_s['kering']+$dts_K_ov_fl_ul_t_s['kering']+$dts_K_ov_ul_t_s['kering']+$dts_K_ov_dye_t_s['kering']+$dts_K_padder_t_s['kering']+$dts_K_fin_ulang_t_s['kering']+$dts_K_compact_t_s['kering']+$dts_K_ov_dyeing_t_s['kering']; 
			  
			$sqlgk_t_s=mysqli_query($cona," SELECT
				SUM(kg1+kg2+kg3) as kg
			FROM
				tbl_bonkain tb
			WHERE
			YEAR(tgl_update) = '$tahunSebelumnya'");
			$rg_t_s=mysqli_fetch_array($sqlgk_t_s);  
			?>  
            <td align="left">1</td>
            <td align="left">Total'<?= substr($tahunSebelumnya, 2); ?></td>
            <td align="right"><?= number_format($dts_K_fin_jadi_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_preset_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_tarik_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_fin_1x_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_fl_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_fl_ul_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_ul_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_s_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_dye_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_naik_suhu_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_padder_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_pot_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_fin_ulang_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_prd_stenter_t_s,2); ?></td>
            <td align="right"><?= number_format($dts_K_compact_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_compact_fin_t_s['kering']+$dts_K_compact_dye_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_compact_t_s,2); ?></td>
            <td align="right"><?= number_format($dts_B_fin_bl_t_s['basah'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_krh_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_krg_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_dyeing_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_fix_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_prd_ov_t_s,2); ?></td>
            <td align="right"><?= number_format($dts_K_lipat_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_steamer_t_s['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_kj_t_s,2); ?></td>
            <td align="right"><?= number_format($tot_loss_t_s,2); ?></td>
            <td align="right"><?= number_format($rg_t_s['kg'],2); ?></td>
            </tr>
          <tr>
			<?php
				$sqlsK_fin_jadi_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya' 
				and a.nama_mesin = 'FNJ1'");		
			$dts_K_fin_jadi_l	= sqlsrv_fetch_array($sqlsK_fin_jadi_l, SQLSRV_FETCH_ASSOC);
			  
			  	$sqlsK_preset_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin = 'PRE1'");		
			$dts_K_preset_l	= sqlsrv_fetch_array($sqlsK_preset_l, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_tarik_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and nama_mesin = 'FNJ1'
				and proses like '%Tarik Lebar%'");		
			$dts_K_tarik_l	= sqlsrv_fetch_array($sqlsK_tarik_l, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_fin_1x_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin IN('FIN1','FIN2')");		
		   $dts_K_fin_1x_l	= sqlsrv_fetch_array($sqlsK_fin_1x_l, SQLSRV_FETCH_ASSOC); 
			  
		   $sqlsK_ov_fl_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Fleece (Normal)%'");		
			$dts_K_ov_fl_l	= sqlsrv_fetch_array($sqlsK_ov_fl_l, SQLSRV_FETCH_ASSOC);	
			
			$sqlsK_ov_fl_ul_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Fleece Ulang (Normal)%'");		
			$dts_K_ov_fl_ul_l	= sqlsrv_fetch_array($sqlsK_ov_fl_ul_l, SQLSRV_FETCH_ASSOC);	
			
			$sqlsK_ov_ul_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Stenter Ulang (Normal)%'");		
			$dts_K_ov_ul_l	= sqlsrv_fetch_array($sqlsK_ov_ul_l, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_s_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Stenter (Normal)%'");		
			$dts_K_ov_s_l	= sqlsrv_fetch_array($sqlsK_ov_s_l, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_dye_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Stenter Dyeing (Bantu)%'");		
			$dts_K_ov_dye_l	= sqlsrv_fetch_array($sqlsK_ov_dye_l, SQLSRV_FETCH_ASSOC);	  
			  
		   $sqlsK_naik_suhu_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Naik Suhu%'");		
			$dts_K_naik_suhu_l	= sqlsrv_fetch_array($sqlsK_naik_suhu_l, SQLSRV_FETCH_ASSOC);	
		   
		   $sqlsK_padder_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin IN('PAD1','PAD2','PAD3','PAD4','PAD5')");		
		   $dts_K_padder_l	= sqlsrv_fetch_array($sqlsK_padder_l, SQLSRV_FETCH_ASSOC);
			
		   $sqlsK_pot_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and nama_mesin = 'FNJ1'
				and proses like '%Potong Pinggir%'");		
			$dts_K_pot_l	= sqlsrv_fetch_array($sqlsK_pot_l, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_fin_ulang_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya' 
				and a.nama_mesin IN('FNJ2','FNJ3','FNJ4','FNJ5','FNJ6')");		
			$dts_K_fin_ulang_l	= sqlsrv_fetch_array($sqlsK_fin_ulang_l, SQLSRV_FETCH_ASSOC);
			$tot_prd_stenter_l =  $dts_K_fin_jadi_l['kering']+$dts_K_preset_l['kering']+$dts_K_tarik_l['kering']+$dts_K_fin_1x_l['kering']+$dts_K_ov_fl_l['kering']+$dts_K_ov_fl_ul_l['kering']+$dts_K_ov_ul_l['kering']+$dts_K_ov_s_l['kering']+$dts_K_ov_dye_l['kering']+$dts_K_naik_suhu_l['kering']+$dts_K_padder_l['kering']+$dts_K_pot_l['kering']+$dts_K_fin_ulang_l['kering'];
			
			$sqlsK_compact_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin = 'CPT1'");		
			$dts_K_compact_l	= sqlsrv_fetch_array($sqlsK_compact_l, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_compact_fin_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin IN('CPF2','CPF3','CPF4')");		
			$dts_K_compact_fin_l	= sqlsrv_fetch_array($sqlsK_compact_fin_l, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_compact_dye_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')");		
			$dts_K_compact_dye_l	= sqlsrv_fetch_array($sqlsK_compact_dye_l, SQLSRV_FETCH_ASSOC);
			$tot_compact_l =  $dts_K_compact_l['kering']+$dts_K_compact_fin_l['kering']+$dts_K_compact_dye_l['kering'];
			$sqlsB_fin_bl_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS basah,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS basah_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin = 'OPW1'");		
			$dts_B_fin_bl_l	= sqlsrv_fetch_array($sqlsB_fin_bl_l, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_krh_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Kragh (Normal)%'");		
			$dts_K_ov_krh_l	= sqlsrv_fetch_array($sqlsK_ov_krh_l, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_krg_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Kering (Normal)%'");		
			$dts_K_ov_krg_l	= sqlsrv_fetch_array($sqlsK_ov_krg_l, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_dyeing_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Dyeing (Bantu)%'");		
			$dts_K_ov_dyeing_l = sqlsrv_fetch_array($sqlsK_ov_dyeing_l, SQLSRV_FETCH_ASSOC); 
			
			$sqlsK_ov_fix_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Oven Fix Fong%'");		
			$dts_K_ov_fix_l = sqlsrv_fetch_array($sqlsK_ov_fix_l, SQLSRV_FETCH_ASSOC); 
			$tot_prd_ov_l = $dts_K_ov_krh_l['kering']+$dts_K_ov_krg_l['kering']+$dts_K_ov_dyeing_l['kering']+$dts_K_ov_fix_l['kering'];
			  
			$sqlsK_lipat_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and proses like '%Lipat (Normal)%'");		
			$dts_K_lipat_l = sqlsrv_fetch_array($sqlsK_lipat_l, SQLSRV_FETCH_ASSOC); 
			
			$sqlsK_steamer_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				and a.nama_mesin = 'STM1'");		
			$dts_K_steamer_l	= sqlsrv_fetch_array($sqlsK_steamer_l, SQLSRV_FETCH_ASSOC);
			$tot_kj_l = $dts_K_fin_jadi_l['kering']+$dts_K_padder_l['kering']+$dts_K_pot_l['kering']+$dts_K_fin_ulang_l['kering']+$dts_K_compact_l['kering']+$dts_K_compact_fin_l['kering']+$dts_K_compact_dye_l['kering']+$dts_K_ov_krh_l['kering'];
			$tot_loss_l = $dts_K_tarik_l['kering']+$dts_K_ov_fl_ul_l['kering']+$dts_K_ov_ul_l['kering']+$dts_K_ov_dye_l['kering']+$dts_K_padder_l['kering']+$dts_K_fin_ulang_l['kering']+$dts_K_compact_l['kering']+$dts_K_ov_dyeing_l['kering']; 
			  
			$sqlgk_l=mysqli_query($cona," SELECT
				SUM(kg1+kg2+kg3) as kg
			FROM
				tbl_bonkain tb
			WHERE
			MONTH(tgl_update) = 12 AND YEAR(tgl_update) = '$tahunSebelumnya'");
			$rg_l=mysqli_fetch_array($sqlgk_l);  
			?>
            <td align="left">2</td>
            <td align="left">Des'<?= substr($tahunSebelumnya, 2); ?></td>
            <td align="right"><?= number_format($dts_K_fin_jadi_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_preset_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_tarik_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_fin_1x_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_fl_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_fl_ul_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_ul_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_s_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_dye_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_naik_suhu_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_padder_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_pot_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_fin_ulang_l['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_prd_stenter_l,2); ?></td>
            <td align="right"><?= number_format($dts_K_compact_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_compact_fin_l['kering']+$dts_K_compact_dye_l['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_compact_l,2); ?></td>
            <td align="right"><?= number_format($dts_B_fin_bl_l['basah'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_krh_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_krg_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_dyeing_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_fix_l['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_prd_ov_l,2); ?></td>
            <td align="right"><?= number_format($dts_K_lipat_l['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_steamer_l['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_kj_l,2); ?></td>
            <td align="right"><?= number_format($tot_loss_l,2); ?></td>
            <td align="right"><?= number_format($rg_l['kg'],2); ?></td>
            </tr>
		  <?php 
			$no=3;
			$bln=1;
			$bln1=1;
			$bulan_target = (int) $_POST['bulan'];
			$bulan_sebelumnya = $bulan_target - 1;

			$nilai_sebelumnya = 0;
			$nilai_saat_ini = 0;
			
			foreach ($bulan as $namaBulan): 
			
			if($bln > $_POST['bulan']){
				$bln1=0;	
			}else{ 
				$bln1=$bln;	
			}
			
			$sqlsK_fin_jadi  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun' 
				and a.nama_mesin = 'FNJ1'");		
			$dts_K_fin_jadi	= sqlsrv_fetch_array($sqlsK_fin_jadi, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_preset  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and a.nama_mesin = 'PRE1'");		
			$dts_K_preset	= sqlsrv_fetch_array($sqlsK_preset, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_tarik  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and nama_mesin = 'FNJ1'
				and proses like '%Tarik Lebar%'");		
			$dts_K_tarik	= sqlsrv_fetch_array($sqlsK_tarik, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_fin_1x  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun' 
				and a.nama_mesin IN('FIN1','FIN2')");		
		   $dts_K_fin_1x	= sqlsrv_fetch_array($sqlsK_fin_1x, SQLSRV_FETCH_ASSOC);
			
		   $sqlsK_ov_fl  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Fleece (Normal)%'");		
			$dts_K_ov_fl	= sqlsrv_fetch_array($sqlsK_ov_fl, SQLSRV_FETCH_ASSOC);	
			
			$sqlsK_ov_fl_ul  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Fleece Ulang (Normal)%'");		
			$dts_K_ov_fl_ul	= sqlsrv_fetch_array($sqlsK_ov_fl_ul, SQLSRV_FETCH_ASSOC);	
			
			$sqlsK_ov_ul  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Stenter Ulang (Normal)%'");		
			$dts_K_ov_ul	= sqlsrv_fetch_array($sqlsK_ov_ul, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Stenter (Normal)%'");		
			$dts_K_ov_s	= sqlsrv_fetch_array($sqlsK_ov_s, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_dye  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Stenter Dyeing (Bantu)%'");		
			$dts_K_ov_dye	= sqlsrv_fetch_array($sqlsK_ov_dye, SQLSRV_FETCH_ASSOC);
			
		   $sqlsK_naik_suhu  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Naik Suhu%'");		
			$dts_K_naik_suhu	= sqlsrv_fetch_array($sqlsK_naik_suhu, SQLSRV_FETCH_ASSOC);	
		   
		   $sqlsK_padder  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and a.nama_mesin IN('PAD1','PAD2','PAD3','PAD4','PAD5')");		
		   $dts_K_padder	= sqlsrv_fetch_array($sqlsK_padder, SQLSRV_FETCH_ASSOC);
			
		   $sqlsK_pot  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and nama_mesin = 'FNJ1'
				and proses like '%Potong Pinggir%'");		
			$dts_K_pot	= sqlsrv_fetch_array($sqlsK_pot, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_fin_ulang  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun' 
				and a.nama_mesin IN('FNJ2','FNJ3','FNJ4','FNJ5','FNJ6')");		
			$dts_K_fin_ulang	= sqlsrv_fetch_array($sqlsK_fin_ulang, SQLSRV_FETCH_ASSOC);
			$tot_prd_stenter =  $dts_K_fin_jadi['kering']+$dts_K_preset['kering']+$dts_K_tarik['kering']+$dts_K_fin_1x['kering']+$dts_K_ov_fl['kering']+$dts_K_ov_fl_ul['kering']+$dts_K_ov_ul['kering']+$dts_K_ov_s['kering']+$dts_K_ov_dye['kering']+$dts_K_naik_suhu['kering']+$dts_K_padder['kering']+$dts_K_pot['kering']+$dts_K_fin_ulang['kering'];
			$sqlsK_compact  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun' 
				and a.nama_mesin = 'CPT1'");		
			$dts_K_compact	= sqlsrv_fetch_array($sqlsK_compact, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_compact_fin  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun' 
				and a.nama_mesin IN('CPF2','CPF3','CPF4')");		
			$dts_K_compact_fin	= sqlsrv_fetch_array($sqlsK_compact_fin, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_compact_dye = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun' 
				and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')");		
			$dts_K_compact_dye	= sqlsrv_fetch_array($sqlsK_compact_dye, SQLSRV_FETCH_ASSOC);
			$tot_compact=  $dts_K_compact['kering']+$dts_K_compact_fin['kering']+$dts_K_compact_dye['kering'];
			$sqlsB_fin_bl  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and a.nama_mesin = 'OPW1'");		
			$dts_B_fin_bl	= sqlsrv_fetch_array($sqlsB_fin_bl, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_krh  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Kragh (Normal)%'");		
			$dts_K_ov_krh	= sqlsrv_fetch_array($sqlsK_ov_krh, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_krg  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Kering (Normal)%'");		
			$dts_K_ov_krg	= sqlsrv_fetch_array($sqlsK_ov_krg, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_dyeing  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Dyeing (Bantu)%'");		
			$dts_K_ov_dyeing	= sqlsrv_fetch_array($sqlsK_ov_dyeing, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_fix  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Fix Fong%'");		
			$dts_K_ov_fix = sqlsrv_fetch_array($sqlsK_ov_fix, SQLSRV_FETCH_ASSOC);
			$tot_prd_ov = $dts_K_ov_krh['kering']+$dts_K_ov_krg['kering']+$dts_K_ov_dyeing['kering']+$dts_K_ov_fix['kering'];
			
			$sqlsK_lipat  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Lipat (Normal)%'");		
			$dts_K_lipat = sqlsrv_fetch_array($sqlsK_lipat, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_steamer  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				and a.nama_mesin = 'STM1'");		
			$dts_K_steamer	= sqlsrv_fetch_array($sqlsK_steamer, SQLSRV_FETCH_ASSOC);
			$tot_kj = $dts_K_fin_jadi['kering']+$dts_K_padder['kering']+$dts_K_pot['kering']+$dts_K_fin_ulang['kering']+$dts_K_compact['kering']+$dts_K_compact_fin['kering']+$dts_K_compact_dye['kering']+$dts_K_ov_krh['kering'];
			$tot_loss = $dts_K_tarik['kering']+$dts_K_ov_fl_ul['kering']+$dts_K_ov_ul['kering']+$dts_K_ov_dye['kering']+$dts_K_padder['kering']+$dts_K_fin_ulang['kering']+$dts_K_compact['kering']+$dts_K_ov_dyeing['kering'];
			
			
			$sqlgk=mysqli_query($cona," SELECT
				SUM(kg1+kg2+kg3) as kg
			FROM
				tbl_bonkain tb
			WHERE
			MONTH(tgl_update) = '$bln1' AND YEAR(tgl_update) = '$tahun'");
			$rg=mysqli_fetch_array($sqlgk);			
		  ?>	
          <tr>
            <td align="left"><?= $no; ?></td>
            <td align="left"><?= $namaBulan . "'" . substr($tahun, 2); ?></td>
            <td align="right"><?= number_format($dts_K_fin_jadi['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_preset['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_tarik['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_fin_1x['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_fl['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_fl_ul['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_ul['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_s['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_dye['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_naik_suhu['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_padder['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_pot['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_fin_ulang['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_prd_stenter,2); ?></td>
            <td align="right"><?= number_format($dts_K_compact['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_compact_fin['kering']+$dts_K_compact_dye['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_compact,2); ?></td>
            <td align="right"><?= number_format($dts_B_fin_bl['basah'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_krh['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_krg['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_dyeing['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_ov_fix['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_prd_ov,2); ?></td>
            <td align="right"><?= number_format($dts_K_lipat['kering'],2); ?></td>
            <td align="right"><?= number_format($dts_K_steamer['kering'],2); ?></td>
            <td align="right"><?= number_format($tot_kj,2); ?></td>
            <td align="right"><?= number_format($tot_loss,2); ?></td>
            <td align="right"><?= number_format($rg['kg'],2); ?></td>
            </tr>
		  <?php 
			if ($bln == $bulan_sebelumnya) {
				$nilai_sebelumnyaA = $dts_K_fin_jadi['kering'];
				$nilai_sebelumnyaB = $dts_K_preset['kering'];
				$nilai_sebelumnyaC = $dts_K_tarik['kering'];
				$nilai_sebelumnyaD = $dts_K_fin_1x['kering'];
				$nilai_sebelumnyaE = $dts_K_ov_fl['kering'];
				$nilai_sebelumnyaF = $dts_K_ov_fl_ul['kering'];
				$nilai_sebelumnyaG = $dts_K_ov_ul['kering'];
				$nilai_sebelumnyaH = $dts_K_ov_s['kering'];
				$nilai_sebelumnyaI = $dts_K_ov_dye['kering'];
				$nilai_sebelumnyaJ = $dts_K_naik_suhu['kering'];
				$nilai_sebelumnyaK = $dts_K_padder['kering'];
				$nilai_sebelumnyaL = $dts_K_pot['kering'];
				$nilai_sebelumnyaM = $dts_K_fin_ulang['kering'];
				$nilai_sebelumnyaTotS = $tot_prd_stenter;
				$nilai_sebelumnyaN = $dts_K_compact['kering'];
				$nilai_sebelumnyaO = $dts_K_compact_fin['kering']+$dts_K_compact_dye['kering'];
				$nilai_sebelumnyaTotC = $tot_compact;
				$nilai_sebelumnyaP = $dts_B_fin_bl['kering'];
				$nilai_sebelumnyaQ = $dts_K_ov_krh['kering'];
				$nilai_sebelumnyaR = $dts_K_ov_krg['kering'];
				$nilai_sebelumnyaS = $dts_K_ov_dyeing['kering'];
				$nilai_sebelumnyaT = $dts_K_ov_fix['kering'];
				$nilai_sebelumnyaTotO = $tot_prd_ov;
				$nilai_sebelumnyaU = $dts_K_lipat['kering'];
				$nilai_sebelumnyaV = $dts_K_steamer['kering'];
				$nilai_sebelumnyaTotKJ = $tot_kj;
				$nilai_sebelumnyaTotLoss = $tot_loss;
				$nilai_sebelumnyaTotGK = $rg['kg'];
			}
			if ($bln == $bulan_target) {
				$nilai_saat_iniA = $dts_K_fin_jadi['kering'];
				$nilai_saat_iniB = $dts_K_preset['kering'];
				$nilai_saat_iniC = $dts_K_tarik['kering'];
				$nilai_saat_iniD = $dts_K_fin_1x['kering'];
				$nilai_saat_iniE = $dts_K_ov_fl['kering'];
				$nilai_saat_iniF = $dts_K_ov_fl_ul['kering'];
				$nilai_saat_iniG = $dts_K_ov_ul['kering'];
				$nilai_saat_iniH = $dts_K_ov_s['kering'];
				$nilai_saat_iniI = $dts_K_ov_dye['kering'];
				$nilai_saat_iniJ = $dts_K_naik_suhu['kering'];
				$nilai_saat_iniK = $dts_K_padder['kering'];
				$nilai_saat_iniL = $dts_K_pot['kering'];
				$nilai_saat_iniM = $dts_K_fin_ulang['kering'];
				$nilai_saat_iniTotS = $tot_prd_stenter;
				$nilai_saat_iniN = $dts_K_compact['kering'];
				$nilai_saat_iniO = $dts_K_compact_fin['kering']+$dts_K_compact_dye['kering'];
				$nilai_saat_iniTotC = $tot_compact;
				$nilai_saat_iniP = $dts_B_fin_bl['kering'];
				$nilai_saat_iniQ = $dts_K_ov_krh['kering'];
				$nilai_saat_iniR = $dts_K_ov_krg['kering'];
				$nilai_saat_iniS = $dts_K_ov_dyeing['kering'];
				$nilai_saat_iniT = $dts_K_ov_fix['kering'];
				$nilai_saat_iniTotO = $tot_prd_ov;
				$nilai_saat_iniU = $dts_K_lipat['kering'];
				$nilai_saat_iniV = $dts_K_steamer['kering'];
				$nilai_saat_iniTotKJ = $tot_kj;
				$nilai_saat_iniTotLoss = $tot_loss;
				$nilai_saat_iniTotGK = $rg['kg'];
			}
			$no++;
			$bln++;	
			
			endforeach; 				
		  ?>
		  <?php
			if($nilai_saat_iniA > 0){
				$persentaseA = round((($nilai_saat_iniA - $nilai_sebelumnyaA) / $nilai_saat_iniA) * 100,2);	
			}else{
				$persentaseA = 0;	
			}
			if($nilai_saat_iniB > 0){
				$persentaseB = round((($nilai_saat_iniB - $nilai_sebelumnyaB) / $nilai_saat_iniB) * 100,2);	
			}else{
				$persentaseB = 0;	
			}
			if($nilai_saat_iniC > 0){
				$persentaseC = round((($nilai_saat_iniC - $nilai_sebelumnyaC) / $nilai_saat_iniC) * 100,2);	
			}else{
				$persentaseC = 0;	
			}
			if($nilai_saat_iniD > 0){
				$persentaseD = round((($nilai_saat_iniD - $nilai_sebelumnyaD) / $nilai_saat_iniD) * 100,2);	
			}else{
				$persentaseD = 0;	
			}
			if($nilai_saat_iniE > 0){
				$persentaseE = round((($nilai_saat_iniE - $nilai_sebelumnyaE) / $nilai_saat_iniE) * 100,2);	
			}else{
				$persentaseE = 0;	
			}
			if($nilai_saat_iniF > 0){
				$persentaseF = round((($nilai_saat_iniF - $nilai_sebelumnyaF) / $nilai_saat_iniF) * 100,2);	
			}else{
				$persentaseF = 0;	
			}
			if($nilai_saat_iniG > 0){
				$persentaseG = round((($nilai_saat_iniG - $nilai_sebelumnyaG) / $nilai_saat_iniG) * 100,2);	
			}else{
				$persentaseG = 0;	
			}
			if($nilai_saat_iniH > 0){
				$persentaseH = round((($nilai_saat_iniH - $nilai_sebelumnyaH) / $nilai_saat_iniH) * 100,2);	
			}else{
				$persentaseH = 0;	
			}
			if($nilai_saat_iniI > 0){
				$persentaseI = round((($nilai_saat_iniI - $nilai_sebelumnyaI) / $nilai_saat_iniI) * 100,2);	
			}else{
				$persentaseI = 0;	
			}
			if($nilai_saat_iniJ > 0){
				$persentaseJ = round((($nilai_saat_iniJ - $nilai_sebelumnyaJ) / $nilai_saat_iniJ) * 100,2);	
			}else{
				$persentaseJ = 0;	
			}
			if($nilai_saat_iniK > 0){
				$persentaseK = round((($nilai_saat_iniK - $nilai_sebelumnyaK) / $nilai_saat_iniK) * 100,2);	
			}else{
				$persentaseK = 0;	
			}
			if($nilai_saat_iniL > 0){
				$persentaseL = round((($nilai_saat_iniL - $nilai_sebelumnyaL) / $nilai_saat_iniL) * 100,2);	
			}else{
				$persentaseL = 0;	
			}
			if($nilai_saat_iniM > 0){
				$persentaseM = round((($nilai_saat_iniM - $nilai_sebelumnyaM) / $nilai_saat_iniM) * 100,2);	
			}else{
				$persentaseM = 0;	
			}
			if($nilai_saat_iniTotS > 0){
				$persentaseTotS = round((($nilai_saat_iniTotS - $nilai_sebelumnyaTotS) / $nilai_saat_iniTotS) * 100,2);	
			}else{
				$persentaseTotS = 0;	
			}
			if($nilai_saat_iniN > 0){
				$persentaseN = round((($nilai_saat_iniN - $nilai_sebelumnyaN) / $nilai_saat_iniN) * 100,2);	
			}else{
				$persentaseN = 0;	
			}
			if($nilai_saat_iniO > 0){
				$persentaseO = round((($nilai_saat_iniO - $nilai_sebelumnyaO) / $nilai_saat_iniO) * 100,2);	
			}else{
				$persentaseO = 0;	
			}
			if($nilai_saat_iniTotC > 0){
				$persentaseTotC = round((($nilai_saat_iniTotC - $nilai_sebelumnyaTotC) / $nilai_saat_iniTotC) * 100,2);	
			}else{
				$persentaseTotC = 0;	
			}
			if($nilai_saat_iniP > 0){
				$persentaseP = round((($nilai_saat_iniP - $nilai_sebelumnyaP) / $nilai_saat_iniP) * 100,2);	
			}else{
				$persentaseP = 0;	
			}
			if($nilai_saat_iniQ > 0){
				$persentaseQ = round((($nilai_saat_iniQ - $nilai_sebelumnyaQ) / $nilai_saat_iniQ) * 100,2);	
			}else{
				$persentaseQ = 0;	
			}
			if($nilai_saat_iniR > 0){
				$persentaseR = round((($nilai_saat_iniR - $nilai_sebelumnyaR) / $nilai_saat_iniR) * 100,2);	
			}else{
				$persentaseR = 0;	
			}
			if($nilai_saat_iniS > 0){
				$persentaseS = round((($nilai_saat_iniS - $nilai_sebelumnyaS) / $nilai_saat_iniS) * 100,2);	
			}else{
				$persentaseS = 0;	
			}
			if($nilai_saat_iniT > 0){
				$persentaseT = round((($nilai_saat_iniT - $nilai_sebelumnyaT) / $nilai_saat_iniT) * 100,2);	
			}else{
				$persentaseT = 0;	
			}
			if($nilai_saat_iniTotO > 0){
				$persentaseTotO = round((($nilai_saat_iniTotO - $nilai_sebelumnyaTotO) / $nilai_saat_iniTotO) * 100,2);	
			}else{
				$persentaseTotO = 0;	
			}
			if($nilai_saat_iniU > 0){
				$persentaseU = round((($nilai_saat_iniU - $nilai_sebelumnyaU) / $nilai_saat_iniU) * 100,2);	
			}else{
				$persentaseU = 0;	
			}
			if($nilai_saat_iniV > 0){
				$persentaseV = round((($nilai_saat_iniV - $nilai_sebelumnyaV) / $nilai_saat_iniV) * 100,2);	
			}else{
				$persentaseV = 0;	
			}
			if($nilai_saat_iniTotKJ > 0){
				$persentaseTotKJ = round((($nilai_saat_iniTotKJ - $nilai_sebelumnyaTotKJ) / $nilai_saat_iniTotKJ) * 100,2);	
			}else{
				$persentaseTotKJ = 0;	
			}
			if($nilai_saat_iniTotLoss > 0){
				$persentaseTotLoss = round((($nilai_saat_iniTotLoss - $nilai_sebelumnyaTotLoss) / $nilai_saat_iniTotLoss) * 100,2);	
			}else{
				$persentaseTotLoss = 0;	
			}
			if($nilai_saat_iniTotGK > 0){
				$persentaseTotGK = round((($nilai_saat_iniTotGK - $nilai_sebelumnyaTotGK) / $nilai_saat_iniTotGK) * 100,2);	
			}else{
				$persentaseTotGK = 0;	
			}
		  ?>
          <tr>
            <td align="left">15</td>  
            <td align="left">%</td>
            <td align="right"><?= $persentaseA; ?> %</td>
            <td align="right"><?= $persentaseB; ?> %</td>
            <td align="right"><?= $persentaseC; ?> %</td>
            <td align="right"><?= $persentaseD; ?> %</td>
            <td align="right"><?= $persentaseE; ?> %</td>
            <td align="right"><?= $persentaseF; ?> %</td>
            <td align="right"><?= $persentaseG; ?> %</td>
            <td align="right"><?= $persentaseH; ?> %</td>
            <td align="right"><?= $persentaseI; ?> %</td>
            <td align="right"><?= $persentaseJ; ?> %</td>
            <td align="right"><?= $persentaseK; ?> %</td>
            <td align="right"><?= $persentaseL; ?> %</td>
            <td align="right"><?= $persentaseM; ?> %</td>
            <td align="right"><?= $persentaseTotS; ?> %</td>
            <td align="right"><?= $persentaseN; ?> %</td>
            <td align="right"><?= $persentaseO; ?> %</td>
            <td align="right"><?= $persentaseTotC; ?> %</td>
            <td align="right"><?= $persentaseP; ?> %</td>
            <td align="right"><?= $persentaseQ; ?> %</td>
            <td align="right"><?= $persentaseR; ?> %</td>
            <td align="right"><?= $persentaseS; ?> %</td>
            <td align="right"><?= $persentaseT; ?> %</td>
            <td align="right"><?= $persentaseTotO; ?> %</td>
            <td align="right"><?= $persentaseU; ?> %</td>
            <td align="right"><?= $persentaseV; ?> %</td> 
            <td align="right"><?= $persentaseTotKJ; ?> %</td>
            <td align="right"><?= $persentaseTotLoss; ?> %</td>
            <td align="right"><?= $persentaseTotGK; ?> %</td>
            </tr>
        </tbody>
		<tfoot>
		</tfoot>  
      </table>
      </div>
    </div>
  </div>
</div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
		<h3 class="box-title">Data Stoppage Machine</h3>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th align="center" valign="middle">Mesin</th>
            <th>Total Kerja Mesin</th>
            <th>Pemeliharaan Mesin</th>
            <th>Ganti Lebar</th>
            <th>Naik Suhu</th>
            <th>Turun Suhu</th>
            <th>Pembersihan Teknis</th>
            <th>Tunggu</th>
            <th>Persiapan Pot. Pinggir</th>
            <th>Persiapan Lem Pinggir</th>
            <th>Kerusakan Mesin</th>
            <th>Listrik Mati</th>
            <th>Gangguan Teknis</th>
            <th>Kurang Personel</th>
            <th>Kurang Order</th>
            <th>Total Stop Mesin</th>
            <th>Persentase Stop Mesin %</th>
            </tr>
        </thead>
        <tbody>
		  <?php
			$sqlS01 = mysqli_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL
			FROM
				tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST301'
				AND MONTH(ts.tgl_buat) = '".$_POST['bulan']."'");
			$rowdS01=mysqli_fetch_array($sqlS01);
			$sqlS02 = mysqli_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL
			FROM
				tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST302'
				AND MONTH(ts.tgl_buat) = '".$_POST['bulan']."'");
			$rowdS02=mysqli_fetch_array($sqlS02);
			$sqlS03 = mysqli_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL
			FROM
				tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST103'
				AND MONTH(ts.tgl_buat) = '".$_POST['bulan']."'");
			$rowdS03=mysqli_fetch_array($sqlS03);
			$sqlS04 = mysqli_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL
			FROM
				tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST304'
				AND MONTH(ts.tgl_buat) = '".$_POST['bulan']."'");
			$rowdS04=mysqli_fetch_array($sqlS04);
			$sqlS05 = mysqli_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL
			FROM
				tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST205'
				AND MONTH(ts.tgl_buat) = '".$_POST['bulan']."'");
			$rowdS05=mysqli_fetch_array($sqlS05);
			$sqlS06 = mysqli_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL
			FROM
				tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST206'
				AND MONTH(ts.tgl_buat) = '".$_POST['bulan']."'");
			$rowdS06=mysqli_fetch_array($sqlS06);
			$sqlS07 = mysqli_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL
			FROM
				tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST307'
				AND MONTH(ts.tgl_buat) = '".$_POST['bulan']."'");
			$rowdS07=mysqli_fetch_array($sqlS07);
			$sqlS08 = mysqli_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL
			FROM
				tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST208'
				AND MONTH(ts.tgl_buat) = '".$_POST['bulan']."'");
			$rowdS08=mysqli_fetch_array($sqlS08);
			$sqlS09 = mysqli_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL
			FROM
				tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST109'
				AND MONTH(ts.tgl_buat) = '".$_POST['bulan']."' ");
			$rowdS09=mysqli_fetch_array($sqlS09);
			$sqlSOv01 = mysqli_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL
			FROM
				tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3DR101'
				AND MONTH(ts.tgl_buat) = '".$_POST['bulan']."' ");
			$rowdSOv01=mysqli_fetch_array($sqlSOv01);
		  ?>	
          <tr>
            <td align="left">ST 01</td>
            <td align="right">488</td>
            <td align="right"><?= number_format(round($rowdS01['PM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['GL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['NS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['TS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['PS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['TG'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['PP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['PL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['KM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['LM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['AP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['KP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['KO'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS01['TOTAL'],2),2); ?></td>
            <td align="center"><?= number_format(round(($rowdS01['TOTAL']/488)*100,2),2); ?> %</td>
            </tr>
          <tr>
            <td align="left">ST 02</td>
            <td align="right">488</td>
            <td align="right"><?= number_format(round($rowdS02['PM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['GL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['NS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['TS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['PS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['TG'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['PP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['PL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['KM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['LM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['AP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['KP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['KO'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS02['TOTAL'],2),2); ?></td>
            <td align="center"><?= number_format(round(($rowdS02['TOTAL']/488)*100,2),2); ?> %</td>
            </tr>
          <tr>
            <td align="left">ST 03</td>
            <td align="right">488</td>
            <td align="right"><?= number_format(round($rowdS03['PM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['GL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['NS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['TS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['PS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['TG'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['PP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['PL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['KM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['LM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['AP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['KP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['KO'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS03['TOTAL'],2),2); ?></td>
            <td align="center"><?= number_format(round(($rowdS03['TOTAL']/488)*100,2),2); ?> %</td>
            </tr>
          <tr>
            <td align="left">ST 04</td>
            <td align="right">488</td>
            <td align="right"><?= number_format(round($rowdS04['PM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['GL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['NS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['TS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['PS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['TG'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['PP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['PL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['KM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['LM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['AP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['KP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['KO'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS04['TOTAL'],2),2); ?></td>
            <td align="center"><?= number_format(round(($rowdS04['TOTAL']/488)*100,2),2); ?> %</td>
            </tr>
          <tr>
            <td align="left">ST 05</td>
            <td align="right">488</td>
            <td align="right"><?= number_format(round($rowdS05['PM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['GL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['NS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['TS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['PS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['TG'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['PP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['PL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['KM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['LM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['AP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['KP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['KO'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS05['TOTAL'],2),2); ?></td>
            <td align="center"><?= number_format(round(($rowdS05['TOTAL']/488)*100,2),2); ?> %</td>
            </tr>
          <tr>
            <td align="left">ST 06</td>
            <td align="right">488</td>
            <td align="right"><?= number_format(round($rowdS06['PM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['GL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['NS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['TS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['PS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['TG'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['PP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['PL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['KM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['LM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['AP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['KP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['KO'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS06['TOTAL'],2),2); ?></td>
            <td align="center"><?= number_format(round(($rowdS06['TOTAL']/488)*100,2),2); ?> %</td>
            </tr>
          <tr>
            <td align="left">ST 07</td>
            <td align="right">488</td>
            <td align="right"><?= number_format(round($rowdS07['PM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['GL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['NS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['TS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['PS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['TG'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['PP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['PL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['KM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['LM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['AP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['KP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['KO'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS07['TOTAL'],2),2); ?></td>
            <td align="center"><?= number_format(round(($rowdS07['TOTAL']/488)*100,2),2); ?> %</td>
            </tr>
          <tr>
            <td align="left">ST 08</td>
            <td align="right">488</td>
            <td align="right"><?= number_format(round($rowdS08['PM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['GL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['NS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['TS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['PS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['TG'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['PP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['PL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['KM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['LM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['AP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['KP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['KO'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS08['TOTAL'],2),2); ?></td>
            <td align="center"><?= number_format(round(($rowdS08['TOTAL']/488)*100,2),2); ?> %</td>
            </tr>
          <tr>
            <td align="left">ST 09</td>
            <td align="right">488</td>
            <td align="right"><?= number_format(round($rowdS09['PM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['GL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['NS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['TS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['PS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['TG'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['PP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['PL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['KM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['LM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['AP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['KP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['KO'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdS09['TOTAL'],2),2); ?></td>
            <td align="center"><?= number_format(round(($rowdS09['TOTAL']/488)*100,2),2); ?> %</td>
            </tr>
          <tr>
            <td align="left">OV 01</td>
            <td align="right">488</td>
            <td align="right"><?= number_format(round($rowdSOv01['PM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['GL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['NS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['TS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['PS'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['TG'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['PP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['PL'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['KM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['LM'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['AP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['KP'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['KO'],2),2); ?></td>
            <td align="right"><?= number_format(round($rowdSOv01['TOTAL'],2),2); ?></td>
            <td align="center"><?= number_format(round(($rowdSOv01['TOTAL']/488)*100,2),2); ?> %</td>
            </tr>
          <tr>
		  <?php
			  $TotPM = $rowdS01['PM']+$rowdS02['PM']+$rowdS03['PM']+$rowdS04['PM']+$rowdS05['PM']+$rowdS06['PM']+$rowdS07['PM']+$rowdS08['PM']+$rowdS09['PM']+$rowdSOv01['PM'];
			  $TotGL = $rowdS01['GL']+$rowdS02['GL']+$rowdS03['GL']+$rowdS04['GL']+$rowdS05['GL']+$rowdS06['GL']+$rowdS07['GL']+$rowdS08['GL']+$rowdS09['GL']+$rowdSOv01['GL'];
			  $TotNS = $rowdS01['NS']+$rowdS02['NS']+$rowdS03['NS']+$rowdS04['NS']+$rowdS05['NS']+$rowdS06['NS']+$rowdS07['NS']+$rowdS08['NS']+$rowdS09['NS']+$rowdSOv01['NS'];
			  $TotTS = $rowdS01['TS']+$rowdS02['TS']+$rowdS03['TS']+$rowdS04['TS']+$rowdS05['TS']+$rowdS06['TS']+$rowdS07['TS']+$rowdS08['TS']+$rowdS09['TS']+$rowdSOv01['TS'];
			  $TotPT = $rowdS01['PT']+$rowdS02['PT']+$rowdS03['PT']+$rowdS04['PT']+$rowdS05['PT']+$rowdS06['PT']+$rowdS07['PT']+$rowdS08['PT']+$rowdS09['PT']+$rowdSOv01['PT'];
			  $TotTG = $rowdS01['TG']+$rowdS02['TG']+$rowdS03['TG']+$rowdS04['TG']+$rowdS05['TG']+$rowdS06['TG']+$rowdS07['TG']+$rowdS08['TG']+$rowdS09['TG']+$rowdSOv01['TG'];
			  $TotPP = $rowdS01['PP']+$rowdS02['PP']+$rowdS03['PP']+$rowdS04['PP']+$rowdS05['PP']+$rowdS06['PP']+$rowdS07['PP']+$rowdS08['PP']+$rowdS09['PP']+$rowdSOv01['PP'];
			  $TotPL = $rowdS01['PL']+$rowdS02['PL']+$rowdS03['PL']+$rowdS04['PL']+$rowdS05['PL']+$rowdS06['PL']+$rowdS07['PL']+$rowdS08['PL']+$rowdS09['PL']+$rowdSOv01['PL'];
			  $TotKM = $rowdS01['KM']+$rowdS02['KM']+$rowdS03['KM']+$rowdS04['KM']+$rowdS05['KM']+$rowdS06['KM']+$rowdS07['KM']+$rowdS08['KM']+$rowdS09['KM']+$rowdSOv01['KM'];
			  $TotLM = $rowdS01['LM']+$rowdS02['LM']+$rowdS03['LM']+$rowdS04['LM']+$rowdS05['LM']+$rowdS06['LM']+$rowdS07['LM']+$rowdS08['LM']+$rowdS09['LM']+$rowdSOv01['LM'];
			  $TotAP = $rowdS01['AP']+$rowdS02['AP']+$rowdS03['AP']+$rowdS04['AP']+$rowdS05['AP']+$rowdS06['AP']+$rowdS07['AP']+$rowdS08['AP']+$rowdS09['AP']+$rowdSOv01['AP'];
			  $TotKP = $rowdS01['KP']+$rowdS02['KP']+$rowdS03['KP']+$rowdS04['KP']+$rowdS05['KP']+$rowdS06['KP']+$rowdS07['KP']+$rowdS08['KP']+$rowdS09['KP']+$rowdSOv01['KP'];
			  $TotKO = $rowdS01['KO']+$rowdS02['KO']+$rowdS03['KO']+$rowdS04['KO']+$rowdS05['KO']+$rowdS06['KO']+$rowdS07['KO']+$rowdS08['KO']+$rowdS09['KO']+$rowdSOv01['KO'];
			  $TotStop = $rowdS01['TOTAL']+$rowdS02['TOTAL']+$rowdS03['TOTAL']+$rowdS04['TOTAL']+$rowdS05['TOTAL']+$rowdS06['TOTAL']+$rowdS07['TOTAL']+$rowdS08['TOTAL']+$rowdS09['TOTAL']+$rowdSOv01['TOTAL'];
			  ?>
            <td align="left">TOTAL</td>  
            <td align="right">&nbsp;</td>
            <td align="right"><?= number_format(round($TotPM,2),2); ?></td>
            <td align="right"><?= number_format(round($TotGL,2),2); ?></td>
            <td align="right"><?= number_format(round($TotNS,2),2); ?></td>
            <td align="right"><?= number_format(round($TotTS,2),2); ?></td>
            <td align="right"><?= number_format(round($TotPT,2),2); ?></td>
            <td align="right"><?= number_format(round($TotTG,2),2); ?></td>
            <td align="right"><?= number_format(round($TotPP,2),2); ?></td>
            <td align="right"><?= number_format(round($TotPL,2),2); ?></td>
            <td align="right"><?= number_format(round($TotKM,2),2); ?></td>
            <td align="right"><?= number_format(round($TotLM,2),2); ?></td>
            <td align="right"><?= number_format(round($TotAP,2),2); ?></td>
            <td align="right"><?= number_format(round($TotKP,2),2); ?></td>
            <td align="right"><?= number_format(round($TotKO,2),2); ?></td>
            <td align="right"><?= number_format(round($TotStop,2),2); ?></td>
            <td align="center">&nbsp;</td>
            </tr>
        </tbody>
		<tfoot>
		</tfoot>  
      </table>
      </div>
    </div>
  </div>
</div>
</div>	
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
		<h3 class="box-title">Data Production Per Machine</h3>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" style="width:100%; font-size: 7px;">
        <thead class="bg-green">
          <tr>
            <th rowspan="2" align="center" valign="middle">Mesin</th>
            <th colspan="13">Kgs</th>
            <th colspan="13">Yards</th>
            <th colspan="13">Yards / Menit</th>
            </tr>
          <tr>
            <th><?= substr($tahunSebelumnya, 0); ?></th>
            <th>JAN</th>
            <th>FEB</th>
            <th>MAR</th>
            <th>APR</th>
            <th>MEI</th>
            <th>JUN</th>
            <th>JUL</th>
            <th>AGUST</th>
            <th>SEPT</th>
            <th>OKT</th>
            <th>NOV</th>
            <th>DES</th>
            <th><?= substr($tahunSebelumnya, 0); ?></th>
            <th>JAN</th>
            <th>FEB</th>
            <th>MAR</th>
            <th>APR</th>
            <th>MEI</th>
            <th>JUN</th>
            <th>JUL</th>
            <th>AGUST</th>
            <th>SEPT</th>
            <th>OKT</th>
            <th>NOV</th>
            <th>DES</th>
            <th><?= substr($tahunSebelumnya, 0); ?></th>
            <th>JAN</th>
            <th>FEB</th>
            <th>MAR</th>
            <th>APR</th>
            <th>MEI</th>
            <th>JUN</th>
            <th>JUL</th>
            <th>AGUST</th>
            <th>SEPT</th>
            <th>OKT</th>
            <th>NOV</th>
            <th>DES</th>
            </tr>
        </thead>
        <tbody>
		  <?php
			$sql_tsblm = sqlsrv_query($conS, "SELECT
				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S01,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST301' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S01,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S02,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST302' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S02,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S03,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S03,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST103' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S03,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S04,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S04,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST304' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S04,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S05,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST205' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S06,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S06,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST206' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S06,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S07,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S07,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST307' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S07,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S08,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S08,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST208' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S08,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S09,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S09,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST109' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S09,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_Ov01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_Ov01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3DR101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_Ov01	    

			FROM db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE YEAR(a.tgl_update) = '$tahunSebelumnya'");
			$rTsblm = sqlsrv_fetch_array($sql_tsblm, SQLSRV_FETCH_ASSOC);
			$sql_bln1 = sqlsrv_query($conS, "SELECT
				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S01,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST301' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S01,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S02,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST302' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S02,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S03,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S03,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST103' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S03,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S04,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S04,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST304' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S04,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S05,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST205' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S06,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S06,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST206' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S06,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S07,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S07,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST307' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S07,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S08,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S08,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST208' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S08,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S09,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S09,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST109' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S09,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_Ov01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_Ov01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3DR101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_Ov01   

			FROM db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '1' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN1 = sqlsrv_fetch_array($sql_bln1, SQLSRV_FETCH_ASSOC);
			$sql_bln2 = sqlsrv_query($conS, "SELECT
				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S01,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST301' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S01,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S02,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST302' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S02,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S03,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S03,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST103' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S03,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S04,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S04,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST304' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S04,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S05,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST205' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S06,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S06,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST206' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S06,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S07,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S07,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST307' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S07,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S08,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S08,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST208' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S08,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S09,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S09,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST109' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S09,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_Ov01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_Ov01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3DR101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_Ov01	    

			FROM db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '2' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN2 = sqlsrv_fetch_array($sql_bln2, SQLSRV_FETCH_ASSOC);
			$sql_bln3 = sqlsrv_query($conS, "SELECT
				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S01,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST301' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S01,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S02,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST302' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S02,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S03,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S03,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST103' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S03,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S04,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S04,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST304' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S04,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S05,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST205' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S06,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S06,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST206' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S06,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S07,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S07,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST307' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S07,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S08,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S08,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST208' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S08,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S09,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S09,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST109' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S09,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_Ov01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_Ov01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3DR101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_Ov01    

			FROM db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '3' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN3 = sqlsrv_fetch_array($sql_bln3, SQLSRV_FETCH_ASSOC);
			$sql_bln4 = sqlsrv_query($conS, "SELECT
				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S01,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST301' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S01,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S02,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST302' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S02,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S03,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S03,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST103' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S03,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S04,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S04,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST304' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S04,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S05,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST205' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S06,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S06,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST206' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S06,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S07,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S07,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST307' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S07,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S08,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S08,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST208' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S08,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S09,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S09,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST109' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S09,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_Ov01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_Ov01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3DR101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_Ov01    

			FROM db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '4' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN4 = sqlsrv_fetch_array($sql_bln4, SQLSRV_FETCH_ASSOC);
			$sql_bln5 = sqlsrv_query($conS, "SELECT
				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST301' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S01,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST301' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S01,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST302' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S02,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST302' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S02,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S03,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST103' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S03,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST103' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S03,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S04,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST304' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S04,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST304' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S04,	

				 SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST205' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S05,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST205' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S05,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S06,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST206' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S06,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST206' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S06,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S07,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST307' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S07,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST307' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S07,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S08,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST208' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S08,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST208' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S08,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_S09,

				SUM(CASE 
						WHEN a.no_mesin = 'P3ST109' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_S09,
					
				SUM(CASE 
		            WHEN a.no_mesin = 'P3ST109' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_S09,	

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_Ov01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3DR101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_Ov01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3DR101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN DATEDIFF(MINUTE, a.jam_in, a.jam_out)
		            ELSE 0 
		        END) AS time_Ov01

			FROM db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '5' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN5 = sqlsrv_fetch_array($sql_bln5, SQLSRV_FETCH_ASSOC);
		  ?>	
          <tr>
            <td align="left">ST 01</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format(round($rTsblm['yard_S01']/$rTsblm['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S01'], 2) : '0.00'; ?></td>
            </tr>
          <tr>
            <td align="left">ST 02</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format(round($rTsblm['yard_S02']/$rTsblm['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S02'], 2) : '0.00'; ?></td>
            </tr>
          <tr>
            <td align="left">ST 03</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format(round($rTsblm['yard_S03']/$rTsblm['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S03'], 2) : '0.00'; ?></td>
            </tr>
          <tr>
            <td align="left">ST 04</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard__S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format(round($rTsblm['yard_S04']/$rTsblm['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard__S04'], 2) : '0.00'; ?></td>
            </tr>
          <tr>
            <td align="left">ST 05</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format(round($rTsblm['yard_S05']/$rTsblm['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S05'], 2) : '0.00'; ?></td>
            </tr>
          <tr>
            <td align="left">ST 06</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format(round($rTsblm['yard_S06']/$rTsblm['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S06'], 2) : '0.00'; ?></td>
            </tr>
          <tr>
            <td align="left">ST 07</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format(round($rTsblm['yard_S07']/$rTsblm['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S07'], 2) : '0.00'; ?></td>
            </tr>
          <tr>
            <td align="left">ST 08</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format(round($rTsblm['yard_S08']/$rTsblm['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S08'], 2) : '0.00'; ?></td>
            </tr>
          <tr>
            <td align="left">ST 09</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format(round($rTsblm['yard_S09']/$rTsblm['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_S09'], 2) : '0.00'; ?></td>
            </tr>
          <tr>
            <td align="left">OV 01</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblm['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format(round($rTsblm['yard_Ov01']/$rTsblm['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12['yard_Ov01'], 2) : '0.00'; ?></td>
            </tr>
          <tr>
		  <?php
			  $rTsblmTot = $rTsblm['kg_S01']+$rTsblm['kg_S02']+$rTsblm['kg_S03']+$rTsblm['kg_S04']+$rTsblm['kg_S05']+$rTsblm['kg_S06']+$rTsblm['kg_S07']+$rTsblm['kg_S08']+$rTsblm['kg_S09']+$rTsblm['kg_Ov01'];
			  $rTsblmTotY = $rTsblm['yard_S01']+$rTsblm['yard_S02']+$rTsblm['yard_S03']+$rTsblm['yard_S04']+$rTsblm['yard_S05']+$rTsblm['yard_S06']+$rTsblm['yard_S07']+$rTsblm['yard_S08']+$rTsblm['yard_S09']+$rTsblm['yard_Ov01'];
			  $rBLN1Tot = $rBLN1['kg_S01']+$rBLN1['kg_S02']+$rBLN1['kg_S03']+$rBLN1['kg_S04']+$rBLN1['kg_S05']+$rBLN1['kg_S06']+$rBLN1['kg_S07']+$rBLN1['kg_S08']+$rBLN1['kg_S09']+$rBLN1['kg_Ov01'];
			  $rBLN2Tot = $rBLN2['kg_S01']+$rBLN2['kg_S02']+$rBLN2['kg_S03']+$rBLN2['kg_S04']+$rBLN2['kg_S05']+$rBLN2['kg_S06']+$rBLN2['kg_S07']+$rBLN2['kg_S08']+$rBLN2['kg_S09']+$rBLN2['kg_Ov01'];
			  $rBLN3Tot = $rBLN3['kg_S01']+$rBLN3['kg_S02']+$rBLN3['kg_S03']+$rBLN3['kg_S04']+$rBLN3['kg_S05']+$rBLN3['kg_S06']+$rBLN3['kg_S07']+$rBLN3['kg_S08']+$rBLN3['kg_S09']+$rBLN3['kg_Ov01'];
			  $rBLN4Tot = $rBLN4['kg_S01']+$rBLN4['kg_S02']+$rBLN4['kg_S03']+$rBLN4['kg_S04']+$rBLN4['kg_S05']+$rBLN4['kg_S06']+$rBLN4['kg_S07']+$rBLN4['kg_S08']+$rBLN4['kg_S09']+$rBLN4['kg_Ov01'];
			  $rBLN5Tot = $rBLN5['kg_S01']+$rBLN5['kg_S02']+$rBLN5['kg_S03']+$rBLN5['kg_S04']+$rBLN5['kg_S05']+$rBLN5['kg_S06']+$rBLN5['kg_S07']+$rBLN5['kg_S07']+$rBLN5['kg_S08']+$rBLN5['kg_S09']+$rBLN5['kg_Ov01'];
			  $rBLN6Tot = $rBLN6['kg_S01']+$rBLN6['kg_S02']+$rBLN6['kg_S03']+$rBLN6['kg_S04']+$rBLN6['kg_S05']+$rBLN6['kg_S06']+$rBLN6['kg_S07']+$rBLN6['kg_S08']+$rBLN6['kg_S09']+$rBLN6['kg_Ov01'];
			  $rBLN7Tot = $rBLN7['kg_S01']+$rBLN7['kg_S02']+$rBLN7['kg_S03']+$rBLN7['kg_S04']+$rBLN7['kg_S05']+$rBLN7['kg_S06']+$rBLN7['kg_S07']+$rBLN7['kg_S08']+$rBLN7['kg_S09']+$rBLN7['kg_Ov01'];
			  $rBLN8Tot = $rBLN8['kg_S01']+$rBLN8['kg_S02']+$rBLN8['kg_S03']+$rBLN8['kg_S04']+$rBLN8['kg_S05']+$rBLN8['kg_S06']+$rBLN8['kg_S07']+$rBLN8['kg_S07']+$rBLN8['kg_S08']+$rBLN8['kg_S09']+$rBLN8['kg_Ov01'];
			  $rBLN9Tot = $rBLN9['kg_S01']+$rBLN9['kg_S02']+$rBLN9['kg_S03']+$rBLN9['kg_S04']+$rBLN9['kg_S05']+$rBLN9['kg_S06']+$rBLN9['kg_S07']+$rBLN9['kg_S08']+$rBLN9['kg_S09']+$rBLN9['kg_Ov01'];
			  $rBLN10Tot = $rBLN10['kg_S01']+$rBLN10['kg_S02']+$rBLN10['kg_S03']+$rBLN10['kg_S04']+$rBLN10['kg_S05']+$rBLN10['kg_S06']+$rBLN10['kg_S07']+$rBLN10['kg_S08']+$rBLN10['kg_S09']+$rBLN10['kg_Ov01'];
			  $rBLN11Tot = $rBLN11['kg_S01']+$rBLN11['kg_S02']+$rBLN11['kg_S03']+$rBLN11['kg_S04']+$rBLN11['kg_S05']+$rBLN11['kg_S06']+$rBLN11['kg_S07']+$rBLN11['kg_S08']+$rBLN11['kg_S09']+$rBLN11['kg_Ov01'];
			  $rBLN12Tot = $rBLN12['kg_S01']+$rBLN12['kg_S02']+$rBLN12['kg_S03']+$rBLN12['kg_S04']+$rBLN12['kg_S05']+$rBLN12['kg_S06']+$rBLN12['kg_S07']+$rBLN12['kg_S08']+$rBLN12['kg_S09']+$rBLN12['kg_Ov01'];
			  
			  $rBLN1TotY = $rBLN1['yard_S01']+$rBLN1['yard_S02']+$rBLN1['yard_S03']+$rBLN1['yard_S04']+$rBLN1['yard_S05']+$rBLN1['yard_S06']+$rBLN1['yard_S07']+$rBLN1['yard_S08']+$rBLN1['yard_S09']+$rBLN1['yard_Ov01'];
			  $rBLN2TotY = $rBLN2['yard_S01']+$rBLN2['yard_S02']+$rBLN2['yard_S03']+$rBLN2['yard_S04']+$rBLN2['yard_S05']+$rBLN2['yard_S06']+$rBLN2['yard_S07']+$rBLN2['yard_S08']+$rBLN2['yard_S09']+$rBLN2['yard_Ov01'];
			  $rBLN3TotY = $rBLN3['yard_S01']+$rBLN3['yard_S02']+$rBLN3['yard_S03']+$rBLN3['yard_S04']+$rBLN3['yard_S05']+$rBLN3['yard_S06']+$rBLN3['yard_S07']+$rBLN3['yard_S08']+$rBLN3['yard_S09']+$rBLN3['yard_Ov01'];
			  $rBLN4TotY = $rBLN4['yard_S01']+$rBLN4['yard_S02']+$rBLN4['yard_S03']+$rBLN4['yard_S04']+$rBLN4['yard_S05']+$rBLN4['yard_S06']+$rBLN4['yard_S07']+$rBLN4['yard_S08']+$rBLN4['yard_S09']+$rBLN4['yard_Ov01'];
			  $rBLN5TotY = $rBLN5['yard_S01']+$rBLN5['yard_S02']+$rBLN5['yard_S03']+$rBLN5['yard_S04']+$rBLN5['yard_S05']+$rBLN5['yard_S06']+$rBLN5['yard_S07']+$rBLN5['yard_S07']+$rBLN5['yard_S08']+$rBLN5['yard_S09']+$rBLN5['yard_Ov01'];
			  $rBLN6TotY = $rBLN6['yard_S01']+$rBLN6['yard_S02']+$rBLN6['yard_S03']+$rBLN6['yard_S04']+$rBLN6['yard_S05']+$rBLN6['yard_S06']+$rBLN6['yard_S07']+$rBLN6['yard_S08']+$rBLN6['yard_S09']+$rBLN6['yard_Ov01'];
			  $rBLN7TotY = $rBLN7['yard_S01']+$rBLN7['yard_S02']+$rBLN7['yard_S03']+$rBLN7['yard_S04']+$rBLN7['yard_S05']+$rBLN7['yard_S06']+$rBLN7['yard_S07']+$rBLN7['yard_S08']+$rBLN7['yard_S09']+$rBLN7['yard_Ov01'];
			  $rBLN8TotY = $rBLN8['yard_S01']+$rBLN8['yard_S02']+$rBLN8['yard_S03']+$rBLN8['yard_S04']+$rBLN8['yard_S05']+$rBLN8['yard_S06']+$rBLN8['yard_S07']+$rBLN8['yard_S07']+$rBLN8['yard_S08']+$rBLN8['yard_S09']+$rBLN8['yard_Ov01'];
			  $rBLN9TotY = $rBLN9['yard_S01']+$rBLN9['yard_S02']+$rBLN9['yard_S03']+$rBLN9['yard_S04']+$rBLN9['yard_S05']+$rBLN9['yard_S06']+$rBLN9['yard_S07']+$rBLN9['yard_S08']+$rBLN9['yard_S09']+$rBLN9['yard_Ov01'];
			  $rBLN10TotY = $rBLN10['yard_S01']+$rBLN10['yard_S02']+$rBLN10['yard_S03']+$rBLN10['yard_S04']+$rBLN10['yard_S05']+$rBLN10['yard_S06']+$rBLN10['yard_S07']+$rBLN10['yard_S08']+$rBLN10['yard_S09']+$rBLN10['yard_Ov01'];
			  $rBLN11TotY = $rBLN11['yard_S01']+$rBLN11['yard_S02']+$rBLN11['yard_S03']+$rBLN11['yard_S04']+$rBLN11['yard_S05']+$rBLN11['yard_S06']+$rBLN11['yard_S07']+$rBLN11['yard_S08']+$rBLN11['yard_S09']+$rBLN11['yard_Ov01'];
			  $rBLN12TotY = $rBLN12['yard_S01']+$rBLN12['yard_S02']+$rBLN12['yard_S03']+$rBLN12['yard_S04']+$rBLN12['yard_S05']+$rBLN12['yard_S06']+$rBLN12['yard_S07']+$rBLN12['yard_S08']+$rBLN12['yard_S09']+$rBLN12['yard_Ov01'];
			  
			  ?>
            <td align="left">TOTAL</td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblmTot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12Tot, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rTsblmTotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "1") ? number_format($rBLN1TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "2") ? number_format($rBLN2TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "3") ? number_format($rBLN3TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "4") ? number_format($rBLN4TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "5") ? number_format($rBLN5TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "6") ? number_format($rBLN6TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "7") ? number_format($rBLN7TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "8") ? number_format($rBLN8TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "9") ? number_format($rBLN9TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "10") ? number_format($rBLN10TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "11") ? number_format($rBLN11TotY, 2) : '0.00'; ?></td>
            <td align="right"><?= ($_POST['bulan'] >= "12") ? number_format($rBLN12TotY, 2) : '0.00'; ?></td>  
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            </tr>
        </tbody>
		<tfoot>
		</tfoot>  
      </table>
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