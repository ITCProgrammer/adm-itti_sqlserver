<?PHP
ini_set("error_reporting", 1);
session_start();
include "../koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Harian Finishing</title>

</head>
<body>
<?php
$Awal = $_GET['awal']; // Kemarin
$Akhir = $_GET['akhir']; // Hari ini

$Awal_Sebelum = date('Y-m-d', strtotime($Awal . ' -1 day'));
$Akhir_Sebelum = date('Y-m-d', strtotime($Akhir . ' -1 day'));	
	
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Data Harian Finishing KAIN BASAH</h3><br>
        <b>Periode: <?php echo $Awal." 23:01 to ".$Akhir." 23:00"; ?></b>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th><div align="center">PROSES KAIN BASAH</div></th>
            <th><div align="center">Nama Operation di NOW</div></th>
            <th><div align="center">Stok Awal</div></th>
            <th><div align="center">Lot</div></th>
            <th><div align="center">Masuk</div></th>
            <th><div align="center">Lot</div></th>
            <th><div align="center">Keluar</div></th>
            <th><div align="center">Lot</div></th>
            <th><div align="center">Sisa</div></th>
            <th><div align="center">Lot</div></th>
            </tr>
        </thead>
        <tbody>
		<?php
$sqlsB_oven  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and a.no_mesin = 'P3DR101'
  -- and a.nama_mesin IN('OVG1')
  and a.proses not like '%Kragh%'");		
$dts_B_oven	= sqlsrv_fetch_array($sqlsB_oven, SQLSRV_FETCH_ASSOC);
			
$sqlB_oven  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND p.MACHINECODE ='P3DR101'
								AND (p.OPERATIONCODE = 'OVG1'
									OR p.OPERATIONCODE = 'OVN2')");		
$dt_B_oven	= db2_fetch_assoc($sqlB_oven);	
$sqlsB_oven_k  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and a.no_mesin = 'P3DR101'
  -- and a.nama_mesin IN('OVN1','OVN2','OVN3','OVN4','OVN5','OVN6')
  and a.proses like '%Kragh%'");		
$dts_B_oven_k	= sqlsrv_fetch_array($sqlsB_oven_k, SQLSRV_FETCH_ASSOC);

$sqlB_oven_k  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE p.KDKAIN = 'FKF'
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND p.MACHINECODE ='P3DR101'
								AND (p.OPERATIONCODE = 'OVN1'
									OR p.OPERATIONCODE = 'OVN2'
									OR p.OPERATIONCODE = 'OVN3'
									OR p.OPERATIONCODE = 'OVN4'
									OR p.OPERATIONCODE = 'OVN5'
									OR p.OPERATIONCODE = 'OVN6')");		
$dt_B_oven_k	= db2_fetch_assoc($sqlB_oven_k);

$sqlsB_stenter  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and a.no_mesin LIKE 'P3ST%' 
  -- and a.nama_mesin IN('OVN1','OVN2','OVN3','OVN4','OVN5','OVN6')
  and a.proses like '%Oven Stenter (Normal)%'");		
$dts_B_stenter	= sqlsrv_fetch_array($sqlsB_stenter, SQLSRV_FETCH_ASSOC);

$sqlB_stenter  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE 
								(p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
							    AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'OVN1'
									OR p.OPERATIONCODE = 'OVN2'
									OR p.OPERATIONCODE = 'OVN3'
									OR p.OPERATIONCODE = 'OVN4'
									OR p.OPERATIONCODE = 'OVN5'
									OR p.OPERATIONCODE = 'OVN6')
								-- AND p.MACHINECODE LIKE 'P3ST%'
								");		
$dt_B_stenter	= db2_fetch_assoc($sqlB_stenter);
			
$sqlsB_pr_dye  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and a.no_mesin LIKE 'P3ST%'
  and a.proses IN('Oven Stenter Dyeing (Bantu)')
  ");		
$dts_B_pr_dye	= sqlsrv_fetch_array($sqlsB_pr_dye, SQLSRV_FETCH_ASSOC);
			
$sqlB_pr_dye  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'OVD1'
									OR p.OPERATIONCODE = 'OVD2'
									OR p.OPERATIONCODE = 'OVD3'
									OR p.OPERATIONCODE = 'OVD4')
								AND p.MACHINECODE LIKE 'P3ST%'");		
$dt_B_pr_dye	= db2_fetch_assoc($sqlB_pr_dye);
			
$sqlsB_fin_jadi  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and a.no_mesin LIKE 'P3ST%'
  and a.proses IN('Finishing Jadi (Normal)')");		
$dts_B_fin_jadi	= sqlsrv_fetch_array($sqlsB_fin_jadi, SQLSRV_FETCH_ASSOC);
			
$sqlB_fin_jadi  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'FNJ1')");		
$dt_B_fin_jadi	= db2_fetch_assoc($sqlB_fin_jadi);
			
$sqlsB_fin_ulang  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin IN('FNJ2','FNJ3','FNJ4','FNJ5','FNJ6')
  and a.no_mesin LIKE 'P3ST%'
  and a.proses IN('Finishing Ulang (Normal)', 'Finishing Ulang 2 (Normal)', 'Finishing Ulang 3 (Normal)')  ");		
$dts_B_fin_ulang	= sqlsrv_fetch_array($sqlsB_fin_ulang, SQLSRV_FETCH_ASSOC);
			
$sqlB_fin_ulang  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'FNJ2'
									OR p.OPERATIONCODE = 'FNJ3'
									OR p.OPERATIONCODE = 'FNJ4'
									OR p.OPERATIONCODE = 'FNJ5'
									OR p.OPERATIONCODE = 'FNJ6')");		
$dt_B_fin_ulang	= db2_fetch_assoc($sqlB_fin_ulang);
			
$sqlsB_fin_ulang_BRS  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin IN('FNU3','FNU4')
  and a.no_mesin LIKE 'P3ST%'
  and a.proses IN('Finishing Ulang - Brushing (Bantu)')");		
$dts_B_fin_ulang_BRS	= sqlsrv_fetch_array($sqlsB_fin_ulang_BRS, SQLSRV_FETCH_ASSOC);
			
$sqlB_fin_ulang_BRS  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'FNU3'
									OR p.OPERATIONCODE = 'FNU4')");		
$dt_B_fin_ulang_BRS	= db2_fetch_assoc($sqlB_fin_ulang_BRS);	
			
$sqlsB_fin_ulang_DYE  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and NOT proses like '%Tarik Lebar%' 
  -- and a.nama_mesin IN('FNU1','FNU2')
  and a.no_mesin LIKE 'P3ST%'
  and a.proses IN ('Finishing Ulang - Dyeing (Bantu)','Finishing Ulang - Dyeing 2 (Bantu)','Finishing Ulang - Dyeing 3 (Bantu)')");		
$dts_B_fin_ulang_DYE	= sqlsrv_fetch_array($sqlsB_fin_ulang_DYE, SQLSRV_FETCH_ASSOC);
			
$sqlB_fin_ulang_DYE  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'FNU1'
									OR p.OPERATIONCODE = 'FNU2')");		
$dt_B_fin_ulang_DYE	= db2_fetch_assoc($sqlB_fin_ulang_DYE);	

$sqlsB_fin_bl  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('OPW1','BLP1','BLD1')
  and a.proses IN('Belah Cuci (Normal)','Belah Cuci ulang (Normal)')");		
$dts_B_fin_bl	= sqlsrv_fetch_array($sqlsB_fin_bl, SQLSRV_FETCH_ASSOC);
			
$sqlB_fin_bl  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND p.OPERATIONCODE IN ('OPW1','OPW2')");		
$dt_B_fin_bl	= db2_fetch_assoc($sqlB_fin_bl);

$sqlsB_steamer  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin = 'STM1'
  and a.proses IN('Steamer (Normal)')");		
$dts_B_steamer	= sqlsrv_fetch_array($sqlsB_steamer, SQLSRV_FETCH_ASSOC);
			
$sqlsB_preset  = sqlsrv_query($conS, "SELECT

	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin = 'PRE1'
  and a.proses IN('Preset (Normal)')");		
$dts_B_preset	= sqlsrv_fetch_array($sqlsB_preset, SQLSRV_FETCH_ASSOC);
			
$sqlsB_ovenG  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.no_mesin = 'P3DR101'
  -- and a.nama_mesin IN('OVG1')
  -- and a.proses not like '%Kragh%'
  and a.proses IN ('Oven Greige (Normal)')");		
$dts_B_ovenG	= sqlsrv_fetch_array($sqlsB_ovenG, SQLSRV_FETCH_ASSOC);			
			
$sqlsB_dye_bl  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('OPW1','BLP1','BLD1')
  and a.proses IN('Belah Dyeing (Bantu)')");		
$dts_B_dye_bl	= sqlsrv_fetch_array($sqlsB_dye_bl, SQLSRV_FETCH_ASSOC);
			
$sqlsB_pre_bl  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('OPW1','BLP1','BLD1')
  and a.proses IN('Belah Preset (Normal)')");		
$dts_B_pre_bl	= sqlsrv_fetch_array($sqlsB_pre_bl, SQLSRV_FETCH_ASSOC);
			
$sqlsB_lipat  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('LIP1')
  and a.proses IN('Lipat (Normal)')");		
$dts_B_lipat	= sqlsrv_fetch_array($sqlsB_lipat, SQLSRV_FETCH_ASSOC);	
			
$sqlsB_Ov_fleece  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('LIP1')
  and a.proses IN('Oven Fleece (Normal)')");		
$dts_B_Ov_fleece	= sqlsrv_fetch_array($sqlsB_Ov_fleece, SQLSRV_FETCH_ASSOC);	
			
$sqlsB_Ov_strUlang  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('LIP1')
  and a.proses IN('Oven Stenter Ulang (Normal)')");		
$dts_B_Ov_strUlang	= sqlsrv_fetch_array($sqlsB_Ov_strUlang, SQLSRV_FETCH_ASSOC);				
			
$sqlsB_fin_1x  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.qty ELSE 0 END) AS basah,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN 1 ELSE 0 END) AS basah_lot,
	SUM(CASE WHEN a.kondisi_kain = 'BASAH' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('LIP1')
  and a.proses IN('Finishing 1X (Normal)','Finishing 1X ulang (Normal)')");		
$dts_B_fin_1x	= sqlsrv_fetch_array($sqlsB_fin_1x, SQLSRV_FETCH_ASSOC);
			
$sqlB_steamer  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								-- AND p.CREATIONDATETIME BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'STM1')");		
$dt_B_steamer	= db2_fetch_assoc($sqlB_steamer);	
			
$sqlB_preset  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'PRE1')");		
$dt_B_preset	= db2_fetch_assoc($sqlB_preset);	
			
$sqlB_ovenG  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'OVG1')");		
$dt_B_ovenG	= db2_fetch_assoc($sqlB_ovenG);	

$sqlB_dye_bl = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'BLD1')");		
$dt_B_dye_bl	= db2_fetch_assoc($sqlB_dye_bl);

$sqlB_pre_bl = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'BLP1')");		
$dt_B_pre_bl	= db2_fetch_assoc($sqlB_pre_bl);
			
$sqlB_lipat = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND p.OPERATIONCODE IN ('LIP1','LIP2')");		
$dt_B_lipat	= db2_fetch_assoc($sqlB_lipat);
			
$sqlB_fin_1x = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND p.OPERATIONCODE IN ('FIN1','FIN2')");		
$dt_B_fin_1x	= db2_fetch_assoc($sqlB_fin_1x);			
			
$sqlB_sisa  = sqlsrv_query($conS, "SELECT
	*
FROM
	db_finishing.tbl_tutup_harian a
WHERE
	CONVERT(DATE, a.tgl_awal) = '$Awal_Sebelum' AND CONVERT(DATE, a.tgl_akhir) = '$Akhir_Sebelum'");		
$dts_B_sisa	= sqlsrv_fetch_array($sqlB_sisa, SQLSRV_FETCH_ASSOC);				
		?>	
          <tr>  
            <td align="left">Oven Body MC Oven</td>
            <td align="left">OVG1,OVN2 </td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['oven_b_basah'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['oven_b_basah_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_B_oven['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_B_oven['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_B_oven['basah'],2),2); ?></td>
            <td align="center"><?php echo round($dts_B_oven['basah_lot']); ?></td>
            <td align="right"><?php echo $BasahS1=round($dts_B_sisa['oven_b_basah']+($dt_B_oven['KG']-$dts_B_oven['basah']),2);?></td>
            <td align="center"><?php echo $BasahLotS1=($dts_B_sisa['oven_b_basah_lot'])+($dt_B_oven['LOT']-$dts_B_oven['basah_lot']); ?></td>
            </tr>
          <tr>
            <td align="left">Oven Krah MC Oven</td>
            <td align="left">OVN1,OVN2,OVN3,OVN4,OVN5,OVN6</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['oven_k_basah'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['oven_k_basah_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_B_oven_k['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_B_oven_k['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_B_oven_k['basah'],2),2); ?></td>
            <td align="center"><?php echo round($dts_B_oven_k['basah_lot']); ?></td>
            <td align="right"><?php echo $BasahS2=round($dts_B_sisa['oven_k_basah']+($dt_B_oven_k['KG']-$dts_B_oven_k['basah']),2);?></td>
            <td align="center"><?php echo $BasahLotS2=($dts_B_sisa['oven_k_basah_lot'])+($dt_B_oven_k['LOT']-$dts_B_oven_k['basah_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Oven Body MC Stenter</td>
            <td align="left">OVN1,OVN2,OVN3,OVN4,OVN5,OVN6</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['oven_b_st_basah'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['oven_b_st_basah_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_B_stenter['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_B_stenter['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_B_stenter['basah'],2),2); ?></td>
            <td align="center"><?php echo round($dts_B_stenter['basah_lot']); ?></td>
            <td align="right"><?php echo $BasahS3=round($dts_B_sisa['oven_b_st_basah']+($dt_B_stenter['KG']-$dts_B_stenter['basah']),2);?></td>
            <td align="center"><?php echo $BasahLotS3=($dts_B_sisa['oven_b_st_basah_lot'])+($dt_B_stenter['LOT']-$dts_B_stenter['basah_lot']); ?></td>
            </tr> 
			<tr>
            <td align="left">Oven Perb. Dye (STENTER+OV PONG)</td>
            <td align="left">OVD1,OVD2,OVD3,OVD4</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['oven_p_dye_basah'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['oven_p_dye_basah_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_B_pr_dye['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_B_pr_dye['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_B_pr_dye['basah'],2),2); ?></td>
            <td align="center"><?php echo round($dts_B_pr_dye['basah_lot']); ?></td>
            <td align="right"><?php echo $BasahS4=round($dts_B_sisa['oven_p_dye_basah']+($dt_B_pr_dye['KG']-$dts_B_pr_dye['basah']),2);?></td>
            <td align="center"><?php echo $BasahLotS4=($dts_B_sisa['oven_p_dye_basah_lot'])+($dt_B_pr_dye['LOT']-$dts_B_pr_dye['basah_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Finishing Jadi</td>
            <td align="left">FNJ1</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['fin_jadi_basah'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['fin_jadi_basah_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_B_fin_jadi['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_B_fin_jadi['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_B_fin_jadi['basah'],2),2); ?></td>
            <td align="center"><?php echo round($dts_B_fin_jadi['basah_lot']); ?></td>
            <td align="right"><?php echo $BasahS5=round($dts_B_sisa['fin_jadi_basah']+($dt_B_fin_jadi['KG']-$dts_B_fin_jadi['basah']),2);?></td>
            <td align="center"><?php echo $BasahLotS5=($dts_B_sisa['fin_jadi_basah_lot'])+($dt_B_fin_jadi['LOT']-$dts_B_fin_jadi['basah_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Finishing Ulang BASAH</td>
            <td align="left">FNJ2,FNJ3,FNJ4,FNJ5,FNJ6</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['fin_ul_basah'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['fin_ul_basah_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_B_fin_ulang['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_B_fin_ulang['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_B_fin_ulang['basah'],2),2); ?></td>
            <td align="center"><?php echo round($dts_B_fin_ulang['basah_lot']); ?></td>
            <td align="right"><?php echo $BasahS6=round($dts_B_sisa['fin_ul_basah']+($dt_B_fin_ulang['KG']-$dts_B_fin_ulang['basah']),2);?></td>
            <td align="center"><?php echo $BasahLotS6=($dts_B_sisa['fin_ul_basah_lot'])+($dt_B_fin_ulang['LOT']-$dts_B_fin_ulang['basah_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Finishing Ulang Perb. BRS</td>
            <td align="left">FNU3,FNU4</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['fin_ul_p_brs_basah'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['fin_ul_p_brs_basah_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_B_fin_ulang_BRS['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_B_fin_ulang_BRS['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_B_fin_ulang_BRS['basah'],2),2); ?></td>
            <td align="center"><?php echo round($dts_B_fin_ulang_BRS['basah_lot']); ?></td>
            <td align="right"><?php echo $BasahS7=round($dts_B_sisa['fin_ul_p_brs_basah']+($dt_B_fin_ulang_BRS['KG']-$dts_B_fin_ulang_BRS['basah']),2);?></td>
            <td align="center"><?php echo $BasahLotS7=($dts_B_sisa['fin_ul_p_brs_basah_lot'])+($dt_B_fin_ulang_BRS['LOT']-$dts_B_fin_ulang_BRS['basah_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Finishing Ulang Perb. DYE</td>
            <td align="left">FNU1,FNU2</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['fin_ul_p_dye_basah'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['fin_ul_p_dye_basah_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_B_fin_ulang_DYE['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_B_fin_ulang_DYE['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_B_fin_ulang_DYE['basah'],2),2); ?></td>
            <td align="center"><?php echo round($dts_B_fin_ulang_DYE['basah_lot']); ?></td>
            <td align="right"><?php echo $BasahS8=round($dts_B_sisa['fin_ul_p_dye_basah']+($dt_B_fin_ulang_DYE['KG']-$dts_B_fin_ulang_DYE['basah']),2);?></td>
            <td align="center"><?php echo $BasahLotS8=($dts_B_sisa['fin_ul_p_dye_basah_lot'])+($dt_B_fin_ulang_DYE['LOT']-$dts_B_fin_ulang_DYE['basah_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Belah Cuci</td>
            <td align="left">OPW1,OPW2</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['belah_c_basah'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['belah_c_basah_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_B_fin_bl['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_B_fin_bl['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_B_fin_bl['basah'],2),2); ?></td>
            <td align="center"><?php echo round($dts_B_fin_bl['basah_lot']); ?></td>
            <td align="right"><?php echo $BasahS9=round($dts_B_sisa['belah_c_basah']+($dt_B_fin_bl['KG']-$dts_B_fin_bl['basah']),2);?></td>
            <td align="center"><?php echo $BasahLotS9=($dts_B_sisa['belah_c_basah_lot'])+($dt_B_fin_bl['LOT']-$dts_B_fin_bl['basah_lot']); ?></td>
            </tr>
        </tbody>
		<tfoot> 
			<?php
			$total_b_masuk_lot 	= $dt_B_oven['LOT']+$dt_B_oven_k['LOT']+$dt_B_stenter['LOT']+$dt_B_pr_dye['LOT']+$dt_B_fin_jadi['LOT']+$dt_B_fin_ulang['LOT']+$dt_B_fin_ulang_BRS['LOT']+$dt_B_fin_ulang_DYE['LOT']+$dt_B_fin_bl['LOT'];
			$total_b_masuk_kg 	= round($dt_B_oven['KG'],2)+round($dt_B_oven_k['KG'],2)+round($dt_B_stenter['KG'],2)+round($dt_B_pr_dye['KG'],2)+round($dt_B_fin_jadi['KG'],2)+round($dt_B_fin_ulang['KG'],2)+round($dt_B_fin_ulang_BRS['KG'],2)+round($dt_B_fin_ulang_DYE['KG'],2)+round($dt_B_fin_bl['KG'],2);
			$total_b_keluar_lot 	= $dts_B_oven['basah_lot']+$dts_B_oven_k['basah_lot']+$dts_B_stenter['basah_lot']+$dts_B_pr_dye['basah_lot']+$dts_B_fin_jadi['basah_lot']+$dts_B_fin_ulang['basah_lot']+$dts_B_fin_ulang_BRS['basah_lot']+$dts_B_fin_ulang_DYE['basah_lot']+$dts_B_fin_bl['basah_lot'];
			$total_b_keluar_kg 	= round($dts_B_oven['basah'],2)+round($dts_B_oven_k['basah'],2)+round($dts_B_stenter['basah'],2)+round($dts_B_pr_dye['basah'],2)+round($dts_B_fin_jadi['basah'],2)+round($dts_B_fin_ulang['basah'],2)+round($dts_B_fin_ulang_BRS['basah'],2)+round($dts_B_fin_ulang_DYE['basah'],2)+round($dts_B_fin_bl['basah'],2);
			$total_b_sisa = $dts_B_sisa['oven_b_basah']+$dts_B_sisa['oven_k_basah']+$dts_B_sisa['oven_b_st_basah']+$dts_B_sisa['oven_p_dye_basah']+$dts_B_sisa['fin_jadi_basah']+$dts_B_sisa['fin_ul_basah']+$dts_B_sisa['fin_ul_p_brs_basah']+$dts_B_sisa['fin_ul_p_dye_basah']+$dts_B_sisa['belah_c_basah'];
			$total_b_sisa_lot = $dts_B_sisa['oven_b_basah_lot']+$dts_B_sisa['oven_k_basah_lot']+$dts_B_sisa['oven_b_st_basah_lot']+$dts_B_sisa['oven_p_dye_basah_lot']+$dts_B_sisa['fin_jadi_basah_lot']+$dts_B_sisa['fin_ul_basah_lot']+$dts_B_sisa['fin_ul_p_brs_basah_lot']+$dts_B_sisa['fin_ul_p_dye_basah_lot']+$dts_B_sisa['belah_c_basah_lot'];
      ?>
		    <tr>
            <td align="center">TOTAL BASAH</td>
            <td align="left">&nbsp;</td>
            <td align="right"><?php echo number_format($total_b_sisa,2); ?></td>
            <td align="center"><?php echo number_format($total_b_sisa_lot,0); ?></td>
            <td align="right"><?php echo number_format($total_b_masuk_kg,2); ?></td> 
            <td align="center"><?php echo number_format($total_b_masuk_lot,0); ?></td>
            <td align="right"><?php echo number_format($total_b_keluar_kg,2); ?></td>
            <td align="center"><?php echo number_format($total_b_keluar_lot,0); ?></td>
            <td align="right"><?php echo number_format(($total_b_sisa)+($total_b_masuk_kg-$total_b_keluar_kg),2); ?></td>
            <td align="center"><?php echo number_format(($total_b_sisa_lot)+($total_b_masuk_lot-$total_b_keluar_lot),0); ?></td>
            </tr>
		</tfoot>  
      </table>
	  <br>
	  <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th><div align="center">PROSES KAIN KERING</div></th>
            <th><div align="center">Nama Operation di NOW</div></th>
            <th><div align="center">Stok Awal</div></th>
            <th><div align="center">Lot</div></th>
            <th><div align="center">Masuk</div></th>
            <th><div align="center">Lot</div></th>
            <th><div align="center">Keluar</div></th>
            <th><div align="center">Lot</div></th>
            <th><div align="center">Sisa</div></th>
            <th><div align="center">Lot</div></th>
            </tr>
        </thead>
        <tbody>
		  <?php
$sqlsK_steamer  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin = 'STM1'
  and a.proses IN('Steamer (Normal)')");		
$dts_K_steamer	= sqlsrv_fetch_array($sqlsK_steamer, SQLSRV_FETCH_ASSOC);
			
$sqlK_steamer  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								-- AND p.CREATIONDATETIME BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'STM1')");		
$dt_K_steamer	= db2_fetch_assoc($sqlK_steamer);	
			
$sqlsK_preset  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin = 'PRE1'
  and a.proses IN('Preset (Normal)')");		
$dts_K_preset	= sqlsrv_fetch_array($sqlsK_preset, SQLSRV_FETCH_ASSOC);
			
$sqlK_preset  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'PRE1')");		
$dt_K_preset	= db2_fetch_assoc($sqlK_preset);
			
$sqlsK_fin_1x  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
 -- and a.nama_mesin IN('FIN1','FIN2')
  and a.proses IN('Finishing 1X (Normal)','Finishing 1X ulang (Normal)')");		
$dts_K_fin_1x	= sqlsrv_fetch_array($sqlsK_fin_1x, SQLSRV_FETCH_ASSOC);
			
$sqlK_fin_1x  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'FIN1'
									OR p.OPERATIONCODE = 'FIN2')");		
$dt_K_fin_1x	= db2_fetch_assoc($sqlK_fin_1x);

$sqlsK_fin_jadi  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin = 'FNJ1'
  and a.proses IN ('Finishing Jadi (Normal)')");		
$dts_K_fin_jadi	= sqlsrv_fetch_array($sqlsK_fin_jadi, SQLSRV_FETCH_ASSOC);
			
$sqlK_fin_jadi  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE 
								(p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'FNJ1')");		
$dt_K_fin_jadi	= db2_fetch_assoc($sqlK_fin_jadi);	
			
$sqlsK_fin_ulang  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00' 
  -- and a.nama_mesin IN('FNJ2','FNJ3','FNJ4','FNJ5','FNJ6')
  and a.proses IN ('Finishing Ulang (Normal)','Finishing Ulang 2 (Normal)','Finishing Ulang 3 (Normal)')");		
$dts_K_fin_ulang	= sqlsrv_fetch_array($sqlsK_fin_ulang, SQLSRV_FETCH_ASSOC);
			
$sqlK_fin_ulang  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								-- AND p.CREATIONDATETIME BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'FNJ2'
									OR p.OPERATIONCODE = 'FNJ3'
									OR p.OPERATIONCODE = 'FNJ4'
									OR p.OPERATIONCODE = 'FNJ5'
									OR p.OPERATIONCODE = 'FNJ6')");		
$dt_K_fin_ulang	= db2_fetch_assoc($sqlK_fin_ulang);	
			
$sqlsK_tambah_obat  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
  -- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin IN('OVN1','OVN2','OVN3','OVN4','OVB1','OVB2')
  and a.proses IN ('Oven Tambah Obat (Khusus)')");		
$dts_K_tambah_obat 	= sqlsrv_fetch_array($sqlsK_tambah_obat , SQLSRV_FETCH_ASSOC);
			
$sqlK_tambah_obat  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								-- AND p.CREATIONDATETIME BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'OVB1'
									OR p.OPERATIONCODE = 'OVB2')");	
$dt_K_tambah_obat	= db2_fetch_assoc($sqlK_tambah_obat);
			
$sqlsK_padder  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
	and a.nama_mesin IN('PAD1','PAD2','PAD3','PAD4','PAD5')");		
$dts_K_padder	= sqlsrv_fetch_array($sqlsK_padder, SQLSRV_FETCH_ASSOC);
			
$sqlK_padder  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								-- AND p.CREATIONDATETIME BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'PAD1'
									OR p.OPERATIONCODE = 'PAD2'
									OR p.OPERATIONCODE = 'PAD3'
									OR p.OPERATIONCODE = 'PAD4'
									OR p.OPERATIONCODE = 'PAD5')");		
$dt_K_padder	= db2_fetch_assoc($sqlK_padder);

$sqlsK_pot  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
	and proses like '%Potong Pinggir%'");		
$dts_K_pot	= sqlsrv_fetch_array($sqlsK_pot, SQLSRV_FETCH_ASSOC);
			
$sqlsK_tarik  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
	and proses like '%Tarik Lebar%'");		
$dts_K_tarik	= sqlsrv_fetch_array($sqlsK_tarik, SQLSRV_FETCH_ASSOC);

$sqlsK_compact  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
  -- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin = 'CPT1'
  and a.proses IN ('Compact (Normal)')");		
$dts_K_compact	= sqlsrv_fetch_array($sqlsK_compact, SQLSRV_FETCH_ASSOC);			
			
$sqlK_compact  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE 
								(p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								-- AND p.CREATIONDATETIME BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'CPT1')");		
$dt_K_compact	= db2_fetch_assoc($sqlK_compact);
			
$sqlsK_compact_fin  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin IN('CPF1','CPF2','CPF3','CPF4')
  and a.proses IN ('Compact Perbaikan (Normal)')");		
$dts_K_compact_fin	= sqlsrv_fetch_array($sqlsK_compact_fin, SQLSRV_FETCH_ASSOC);
			
$sqlK_compact_fin  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'CPF1'
									OR p.OPERATIONCODE = 'CPF2'
									OR p.OPERATIONCODE = 'CPF3'
									OR p.OPERATIONCODE = 'CPF4')");		
$dt_K_compact_fin	= db2_fetch_assoc($sqlK_compact_fin);	
			
$sqlsK_compact_dye  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')
  and a.proses IN ('Compact - Dyeing (Bantu)','Compact - Dyeing 2 (Bantu)','Compact - Dyeing 3 (Bantu)')");		
$dts_K_compact_dye	= sqlsrv_fetch_array($sqlsK_compact_dye, SQLSRV_FETCH_ASSOC);
			
$sqlK_compact_dye  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								-- AND p.CREATIONDATETIME BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND p.OPERATIONCODE IN ('CPD1','CPD2','CPD3','CPD4')");		
$dt_K_compact_dye	= db2_fetch_assoc($sqlK_compact_dye);
		
$sqlsK_dye_bl  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('OPW1','BLP1','BLD1')
  and a.proses IN('Belah Dyeing (Bantu)')");		
$dts_K_dye_bl	= sqlsrv_fetch_array($sqlsK_dye_bl, SQLSRV_FETCH_ASSOC);
			
$sqlsK_pre_bl  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('OPW1','BLP1','BLD1')
  and a.proses IN('Belah Preset (Normal)')");		
$dts_K_pre_bl	= sqlsrv_fetch_array($sqlsK_pre_bl, SQLSRV_FETCH_ASSOC);			
			
$sqlsK_oven  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.no_mesin = 'P3DR101'
  -- and a.nama_mesin IN('OVG1')
  -- and a.proses not like '%Kragh%'
  and a.proses IN ('Oven Greige (Normal)')");		
$dts_K_oven	= sqlsrv_fetch_array($sqlsK_oven, SQLSRV_FETCH_ASSOC);	
			
$sqlsK_stenter  = sqlsrv_query($conS, "SELECT
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and a.no_mesin LIKE 'P3ST%' 
  -- and a.nama_mesin IN('OVN1','OVN2','OVN3','OVN4','OVN5','OVN6')
  and a.proses like '%Oven Stenter (Normal)%'");		
$dts_K_stenter	= sqlsrv_fetch_array($sqlsK_stenter, SQLSRV_FETCH_ASSOC);	

$sqlsK_lipat  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('LIP1')
  and a.proses IN('Lipat (Normal)')");		
$dts_K_lipat	= sqlsrv_fetch_array($sqlsK_lipat, SQLSRV_FETCH_ASSOC);	
			
$sqlsK_Ov_strUlang  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('LIP1')
  and a.proses IN('Oven Stenter Ulang (Normal)')");		
$dts_K_Ov_strUlang	= sqlsrv_fetch_array($sqlsK_Ov_strUlang, SQLSRV_FETCH_ASSOC);
			
$sqlsK_Ov_str  = sqlsrv_query($conS, "SELECT
    SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS kering,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS kering_lot,
	SUM(CASE WHEN a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  --	and a.nama_mesin IN('LIP1')
  and a.proses IN('Oven Stenter (Normal)')");		
$dts_K_Ov_str	= sqlsrv_fetch_array($sqlsK_Ov_str, SQLSRV_FETCH_ASSOC);			

$sqlK_ovenG  = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'OVG1')");		
$dt_K_ovenG	= db2_fetch_assoc($sqlK_ovenG);	

$sqlK_dye_bl = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'BLD1')");		
$dt_K_dye_bl	= db2_fetch_assoc($sqlK_dye_bl);

$sqlK_pre_bl = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND (p.OPERATIONCODE = 'BLP1')");		
$dt_K_pre_bl	= db2_fetch_assoc($sqlK_pre_bl);
			
$sqlK_lipat = db2_exec($conn2, "SELECT SUM(USERPRIMARYQUANTITY) AS KG, COUNT(PRODUCTIONORDERCODE) AS LOT FROM ITXVIEWPRODUCTIONPROGRESSMULAI p
								WHERE (p.KDKAIN = 'KFF' OR p.KDKAIN IS NULL)
								AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) BETWEEN '$Awal 23:01:00' AND '$Akhir 23:00:00'
								AND p.OPERATIONCODE IN ('LIP1','LIP2')");		
$dt_K_lipat	= db2_fetch_assoc($sqlK_lipat);					
		?>	
          <tr>
            <td align="left">Steamer</td>
            <td align="left">STM1</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['steamer_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['steamer_kering_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_K_steamer['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_K_steamer['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_K_steamer['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_steamer['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS1=round($dts_B_sisa['steamer_kering']+($dt_K_steamer['KG']-$dts_K_steamer['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS1=($dts_B_sisa['steamer_kering_lot'])+($dt_K_steamer['LOT']-$dts_K_steamer['kering_lot']); ?></td>
            </tr>
          <tr>
            <td align="left">Preset</td>
            <td align="left">PRE1</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['preset_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['preset_kering_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_K_preset['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_K_preset['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_K_preset['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_preset['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS2=round($dts_B_sisa['preset_kering']+($dt_K_preset['KG']-$dts_K_preset['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS2=($dts_B_sisa['preset_kering_lot'])+($dt_K_preset['LOT']-$dts_K_preset['kering_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Finishing 1x</td>
            <td align="left">FIN1, FIN2</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['fin_1x_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['fin_1x_kering_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_K_fin_1x['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_K_fin_1x['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_K_fin_1x['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_fin_1x['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS3=round($dts_B_sisa['fin_1x_kering']+($dt_K_fin_1x['KG']-$dts_K_fin_1x['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS3=($dts_B_sisa['fin_1x_kering_lot'])+($dt_K_fin_1x['LOT']-$dts_K_fin_1x['kering_lot']); ?></td>
            </tr>
			<tr>
			<td align="left">Finishing Jadi</td>
            <td align="left">FNJ1</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['fin_jadi_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['fin_jadi_kering_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_K_fin_jadi['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_K_fin_jadi['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_K_fin_jadi['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_fin_jadi['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS4=round($dts_B_sisa['fin_jadi_kering']+($dt_K_fin_jadi['KG']-$dts_K_fin_jadi['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS4=($dts_B_sisa['fin_jadi_kering_lot'])+($dt_K_fin_jadi['LOT']-$dts_K_fin_jadi['kering_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Finishing Ulang</td>
            <td align="left">FNJ2,FNJ3,FNJ4,FNJ5,FNJ6</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['fin_ul_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['fin_ul_kering_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_K_fin_ulang['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_K_fin_ulang['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_K_fin_ulang['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_fin_ulang['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS5=round($dts_B_sisa['fin_ul_kering']+($dt_K_fin_ulang['KG']-$dts_K_fin_ulang['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS5=($dts_B_sisa['fin_ul_kering_lot'])+($dt_K_fin_ulang['LOT']-$dts_K_fin_ulang['kering_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Oven Tambah Obat (STENTER+OV FONG)</td>
            <td align="left">OVB1,OVB2</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['oven_obat_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['oven_obat_kering_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_K_tambah_obat['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_K_tambah_obat['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_K_tambah_obat['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_tambah_obat['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS6=round($dts_B_sisa['oven_obat_kering']+($dt_K_tambah_obat['KG']-$dts_K_tambah_obat['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS6=($dts_B_sisa['oven_obat_kering_lot'])+($dt_K_tambah_obat['LOT']-$dts_K_tambah_obat['kering_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Padder</td>
            <td align="left">PAD1,PAD2,PAD3,PAD4,PAD5</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['padder_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['padder_kering_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_K_padder['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_K_padder['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_K_padder['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_padder['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS7=round($dts_B_sisa['padder_kering']+($dt_K_padder['KG']-$dts_K_padder['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS7=($dts_B_sisa['padder_kering_lot'])+($dt_K_padder['LOT']-$dts_K_padder['kering_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Pot. Pinggir</td>
            <td align="left">&nbsp;</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['pot_pinggir_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['pot_pinggir_kering_lot'],0),0);?></td>
            <td align="right">0.00</td>
            <td align="center">0</td>
            <td align="right"><?php echo number_format(round($dts_K_pot['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_pot['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS8=round($dts_B_sisa['pot_pinggir_kering']+($dt_K_pot['KG']-$dts_K_pot['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS8=($dts_B_sisa['pot_pinggir_kering_lot'])+($dt_K_pot['LOT']-$dts_K_pot['kering_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Tarik Lebar</td>
            <td align="left">&nbsp;</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['tarik_lebar_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['tarik_lebar_kering_lot'],0),0);?></td>
            <td align="right">0.00</td>
            <td align="center">0</td>
            <td align="right"><?php echo number_format(round($dts_K_tarik['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_tarik['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS9=round($dts_B_sisa['tarik_lebar_kering']+($dt_K_tarik['KG']-$dts_K_tarik['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS9=($dts_B_sisa['tarik_lebar_kering_lot'])+($dt_K_tarik['LOT']-$dts_K_tarik['kering_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Compact Normal</td>
            <td align="left">CPT1</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['compact_n_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['compact_n_kering_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_K_compact['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_K_compact['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_K_compact['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_compact['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS10=round($dts_B_sisa['compact_n_kering']+($dt_K_compact['KG']-$dts_K_compact['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS10=($dts_B_sisa['compact_n_kering_lot'])+($dt_K_compact['LOT']-$dts_K_compact['kering_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Compact Perb. FIN</td>
            <td align="left">CPF2,CPF3,CPF4</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['compact_fin_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['compact_fin_kering_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_K_compact_fin['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_K_compact_fin['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_K_compact_fin['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_compact_fin['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS11=round($dts_B_sisa['compact_fin_kering']+($dt_K_compact_fin['KG']-$dts_K_compact_fin['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS11=($dts_B_sisa['compact_fin_kering_lot'])+($dt_K_compact_fin['LOT']-$dts_K_compact_fin['kering_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Compact Perb. DYE</td>
            <td align="left">CPD1,CPD2,CPD3,CPD4 </td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['compact_dye_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['compact_dye_kering_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_K_compact_dye['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_K_compact_dye['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_K_compact_dye['kering'],2),2); ?></td>
            <td align="center"><?php echo round($dts_K_compact_dye['kering_lot']); ?></td>
            <td align="right"><?php echo $KeringS12=round($dts_B_sisa['compact_dye_kering']+($dt_K_compact_dye['KG']-$dts_K_compact_dye['kering']),2);?></td>
            <td align="center"><?php echo $KeringLotS12=($dts_B_sisa['compact_dye_kering_lot'])+($dt_K_compact_dye['LOT']-$dts_K_compact_dye['kering_lot']); ?></td>
            </tr>
			
        </tbody>
		<tfoot>
			<?php
			$total_k_masuk_lot 	= $dt_K_steamer['LOT']+$dt_K_preset['LOT']+$dt_K_fin_1x['LOT']+$dt_K_fin_jadi['LOT']+$dt_K_fin_ulang['LOT']+$dt_K_padder['LOT']+$dt_K_tambah_obat['LOT']+$dt_K_compact['LOT']+$dt_K_compact_fin['LOT']+$dt_K_compact_dye['LOT'];
			$total_k_masuk_kg 	= round($dt_K_steamer['KG'],2)+round($dt_K_preset['KG'],2)+round($dt_K_fin_1x['KG'],2)+round($dt_K_fin_jadi['KG'],2)+round($dt_K_fin_ulang['KG'],2)+round($dt_K_padder['KG'],2)+round($dt_K_tambah_obat['KG'],2)+round($dt_K_compact['KG'],2)+round($dt_K_compact_fin['KG'],2)+round($dt_K_compact_dye['KG'],2);
			$total_k_keluar_kg 	= round($dts_K_steamer['kering'],2)+round($dts_K_preset['kering'],2)+round($dts_K_fin_1x['kering'],2)+round($dts_K_fin_jadi['kering'],2)+round($dts_K_fin_ulang['kering'],2)+round($dts_K_padder['kering'],2)+round($dts_K_tambah_obat['kering'],2)+round($dts_K_compact['kering'],2)+round($dts_K_compact_fin['kering'],2)+round($dts_K_compact_dye['kering'],2);
			$total_k_keluar_lot 	= $dts_K_steamer['kering_lot']+$dts_K_preset['kering_lot']+$dts_K_fin_1x['kering_lot']+$dts_K_fin_jadi['kering_lot']+$dts_K_fin_ulang['kering_lot']+$dts_K_padder['kering_lot']+$dts_K_tambah_obat['kering_lot']+$dts_K_compact['kering_lot']+$dts_K_compact_fin['kering_lot']+$dts_K_compact_dye['kering_lot'];
			$total_k_sisa = $dts_B_sisa['steamer_kering']+$dts_B_sisa['preset_kering']+$dts_B_sisa['fin_1x_kering']+$dts_B_sisa['fin_jadi_kering']+$dts_B_sisa['fin_ul_kering']+$dts_B_sisa['oven_obat_kering']+$dts_B_sisa['padder_kering']+$dts_B_sisa['pot_pinggir_kering']+$dts_B_sisa['tarik_lebar_kering']+$dts_B_sisa['compact_n_kering']+$dts_B_sisa['compact_fin_kering']+$dts_B_sisa['compact_dye_kering'];
			$total_k_sisa_lot = $dts_B_sisa['steamer_kering_lot']+$dts_B_sisa['preset_kering_lot']+$dts_B_sisa['fin_1x_kering_lot']+$dts_B_sisa['fin_jadi_kering_lot']+$dts_B_sisa['fin_ul_kering_lot']+$dts_B_sisa['oven_obat_kering_lot']+$dts_B_sisa['padder_kering_lot']+$dts_B_sisa['pot_pinggir_kering_lot']+$dts_B_sisa['tarik_lebar_kering_lot']+$dts_B_sisa['compact_n_kering_lot']+$dts_B_sisa['compact_fin_kering_lot']+$dts_B_sisa['compact_dye_kering_lot'];
			?>
		    <tr>
            <td align="center">TOTAL KERING</td>
            <td align="left">&nbsp;</td>
            <td align="right"><?php echo number_format($total_k_sisa,2); ?></td>
            <td align="center"><?php echo $total_k_sisa_lot; ?></td>
            <td align="right"><?php echo number_format($total_k_masuk_kg,2); ?></td>
            <td align="center"><?php echo $total_k_masuk_lot; ?> </td>
            <td align="right"><?php echo number_format($total_k_keluar_kg,2); ?></td>
            <td align="center"><?php echo $total_k_keluar_lot; ?></td>
            <td align="right"><?php echo number_format(($total_k_sisa)+($total_k_masuk_kg-$total_k_keluar_kg),2); ?></td>
            <td align="center"><?php echo number_format(($total_k_sisa_lot)+($total_k_masuk_lot-$total_k_keluar_lot),0); ?></td>
            </tr>
		    <tr>
		      <td align="center">GRAND TOTAL</td>
		      <td align="left">&nbsp;</td>
		      <td align="right"><?php echo number_format($total_b_sisa+$total_k_sisa,2); ?></td>
		      <td align="center"><?php echo $total_b_sisa_lot+$total_k_sisa_lot; ?></td>
		      <td align="right"><?php echo number_format($total_b_masuk_kg+$total_k_masuk_kg,2); ?></td>
		      <td align="center"><?php echo $total_b_masuk_lot+$total_k_masuk_lot; ?></td>
		      <td align="right"><?php echo number_format($total_b_keluar_kg+$total_k_keluar_kg,2); ?></td>
		      <td align="center"><?php echo $total_b_keluar_lot+$total_k_keluar_lot; ?></td>
		      <td align="right"><?php echo number_format(($total_b_sisa+$total_k_sisa)+(($total_b_masuk_kg+$total_k_masuk_kg)-($total_b_keluar_kg+$total_k_keluar_kg)),2); ?></td>
		      <td align="center"><?php echo ($total_b_sisa_lot+$total_k_sisa_lot)+(($total_b_masuk_lot+$total_k_masuk_lot)-($total_b_keluar_lot+$total_k_keluar_lot)); ?></td>
		      </tr>
		</tfoot>  
      </table>	  
      </div>
    </div>
  </div>
</div>
</div>	
</body>
</html>
<?php
$cektgl=sqlsrv_query($conS, "SELECT     
    tgl_akhir,
    CONVERT(VARCHAR(10), GETDATE(), 120) AS tgl,       
    COUNT(*) AS ck,                                     
    FORMAT(GETDATE(), 'HH') AS jam,                     
    FORMAT(GETDATE(), 'HH:mm') AS jam1                  
FROM 
    db_finishing.tbl_tutup_harian
WHERE 
    tgl_akhir = CONVERT(VARCHAR(10), GETDATE(), 120)
GROUP BY 
    tgl_akhir");		
$dcek	= sqlsrv_fetch_array($cektgl, SQLSRV_FETCH_ASSOC);
if($dcek['ck']>0){
	echo "<script>";
	echo "alert('Stok Tgl ".$dcek['tgl_akhir']." Ini Sudah Pernah ditutup')";
	echo "</script>";
}else if($_GET['note']!="" or $_GET['note']=="Berhasil"){
	echo "Tutup Transaksi Berhasil";
}else{
$sqlInsert = "INSERT INTO db_finishing.tbl_tutup_harian (
	tgl_awal,
	tgl_akhir,
	oven_b_basah,
	oven_k_basah,
	oven_b_st_basah,
	oven_p_dye_basah,
	fin_jadi_basah,
	fin_ul_basah,
	fin_ul_p_brs_basah,
	fin_ul_p_dye_basah,
	belah_c_basah,
	oven_b_basah_lot,
	oven_k_basah_lot,
	oven_b_st_basah_lot,
	oven_p_dye_basah_lot,
	fin_jadi_basah_lot,
	fin_ul_basah_lot,
	fin_ul_p_brs_basah_lot,
	fin_ul_p_dye_basah_lot,
	belah_c_basah_lot,
	steamer_kering,
	preset_kering,
	fin_1x_kering,
	fin_jadi_kering,
	fin_ul_kering,
	oven_obat_kering,
	padder_kering,
	pot_pinggir_kering,
	tarik_lebar_kering,
	compact_n_kering,
	compact_fin_kering,
	compact_dye_kering,
	steamer_kering_lot,
	preset_kering_lot,
	fin_1x_kering_lot,
	fin_jadi_kering_lot,
	fin_ul_kering_lot,
	oven_obat_kering_lot,
	padder_kering_lot,
	pot_pinggir_kering_lot,
	tarik_lebar_kering_lot,
	compact_n_kering_lot,
	compact_fin_kering_lot,
	compact_dye_kering_lot
) VALUES (
	?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";
$params = [
	$Awal,
	$Akhir,
	$BasahS1,
	$BasahS2,
	$BasahS3,
	$BasahS4,
	$BasahS5,
	$BasahS6,
	$BasahS7,
	$BasahS8,
	$BasahS9,
	$BasahLotS1,
	$BasahLotS2,
	$BasahLotS3,
	$BasahLotS4,
	$BasahLotS5,
	$BasahLotS6,
	$BasahLotS7,
	$BasahLotS8,
	$BasahLotS9,
	$KeringS1,
	$KeringS2,
	$KeringS3,
	$KeringS4,
	$KeringS5,
	$KeringS6,
	$KeringS7,
	$KeringS8,
	$KeringS9,
	$KeringS10,
	$KeringS11,
	$KeringS12,
	$KeringLotS1,
	$KeringLotS2,
	$KeringLotS3,
	$KeringLotS4,
	$KeringLotS5,
	$KeringLotS6,
	$KeringLotS7,
	$KeringLotS8,
	$KeringLotS9,
	$KeringLotS10,
	$KeringLotS11,
	$KeringLotS12
];

$sqlB_sisaInsert = sqlsrv_query($conS, $sqlInsert, $params);
// Tambahan: cek hasil eksekusi query
if ($sqlB_sisaInsert === false) {
    die(print_r(sqlsrv_errors(), true));
}else{
	//echo "<meta http-equiv='refresh' content='0; url=LapHarianFINManual.php?note=Berhasil'>";
}
			
        		
}	
?>
