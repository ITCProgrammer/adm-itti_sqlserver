<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=lapharianfin-".substr($_GET['awal'],0,10).".xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
include "../../koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Harian Finishing</title>

</head>
<body>
<?php
$Awal	= $_GET['awal'];
$Akhir	= $_GET['akhir'];
$Awal_Sebelum = date('Y-m-d', strtotime($Awal . ' -1 day'));
$Akhir_Sebelum = date('Y-m-d', strtotime($Akhir . ' -1 day'));	
?>
<?php if($_GET['awal']!="") { ?><b>Periode: <?php echo $_GET['awal']." 23:01 to ".$_GET['akhir']." 23:00"; ?></b><?php } ?>
	<table width="100%" border="0">
  <tbody>
    <tr>
      <td width="57%"> 
	  <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%" border="1">
        <thead class="bg-blue">
          <tr>
            <th bgcolor="#72EF71"><div align="center">PROSES KAIN BASAH</div></th>
            <th bgcolor="#72EF71"><div align="center">Nama Operation di NOW</div></th>
            <th bgcolor="#72EF71"><div align="center">Stok Awal</div></th>
            <th bgcolor="#72EF71"><div align="center">Lot</div></th>
            <th bgcolor="#72EF71"><div align="center">Masuk</div></th>
            <th bgcolor="#72EF71"><div align="center">Lot</div></th>
            <th bgcolor="#72EF71"><div align="center">Keluar</div></th>
            <th bgcolor="#72EF71"><div align="center">Lot</div></th>
            <th bgcolor="#72EF71"><div align="center">Sisa</div></th>
            <th bgcolor="#72EF71"><div align="center">Lot</div></th>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['oven_b_basah']+(round($dt_B_oven['KG'],2)-round($dts_B_oven['basah'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['oven_b_basah_lot'])+($dt_B_oven['LOT']-$dts_B_oven['basah_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['oven_k_basah']+(round($dt_B_oven_k['KG'],2)-round($dts_B_oven_k['basah'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['oven_k_basah_lot'])+($dt_B_oven_k['LOT']-$dts_B_oven_k['basah_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['oven_b_st_basah']+(round($dt_B_stenter['KG'],2)-round($dts_B_stenter['basah'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['oven_b_st_basah_lot'])+($dt_B_stenter['LOT']-$dts_B_stenter['basah_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['oven_p_dye_basah']+(round($dt_B_pr_dye['KG'],2)-round($dts_B_pr_dye['basah'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['oven_p_dye_basah_lot'])+($dt_B_pr_dye['LOT']-$dts_B_pr_dye['basah_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['fin_jadi_basah']+(round($dt_B_fin_jadi['KG'],2)-round($dts_B_fin_jadi['basah'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['fin_jadi_basah_lot'])+($dt_B_fin_jadi['LOT']-$dts_B_fin_jadi['basah_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['fin_ul_basah']+(round($dt_B_fin_ulang['KG'],2)-round($dts_B_fin_ulang['basah'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['fin_ul_basah_lot'])+($dt_B_fin_ulang['LOT']-$dts_B_fin_ulang['basah_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['fin_ul_p_brs_basah']+(round($dt_B_fin_ulang_BRS['KG'],2)-round($dts_B_fin_ulang_BRS['basah'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['fin_ul_p_brs_basah_lot'])+($dt_B_fin_ulang_BRS['LOT']-$dts_B_fin_ulang_BRS['basah_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['fin_ul_p_dye_basah']+(round($dt_B_fin_ulang_DYE['KG'],2)-round($dts_B_fin_ulang_DYE['basah'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['fin_ul_p_dye_basah_lot'])+($dt_B_fin_ulang_DYE['LOT']-$dts_B_fin_ulang_DYE['basah_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Belah Cuci</td>
            <td align="left">OPW1, OPW2</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['belah_c_basah'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['belah_c_basah_lot'],0),0);?></td>
            <td align="right"><?php echo number_format(round($dt_B_fin_bl['KG'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dt_B_fin_bl['LOT'],2),0);?></td>
            <td align="right"><?php echo number_format(round($dts_B_fin_bl['basah'],2),2); ?></td>
            <td align="center"><?php echo round($dts_B_fin_bl['basah_lot']); ?></td>
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['belah_c_basah']+(round($dt_B_fin_bl['KG'],2)-round($dts_B_fin_bl['basah'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['belah_c_basah_lot'])+($dt_B_fin_bl['LOT']-$dts_B_fin_bl['basah_lot']); ?></td>
            </tr>
			<tr>
			  <td align="left">Steamer</td>
			  <td align="left">STM1</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_B_steamer['KG'],2),2);?></td>
			  <td align="center"><?php echo number_format(round($dt_B_steamer['LOT'],2),0);?></td>
			  <td align="right"><?php echo number_format(round($dts_B_steamer['basah'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_B_steamer['basah_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['belah_c_basah']+(round($dt_B_steamer['KG'],2)-round($dts_B_steamer['basah'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['belah_c_basah_lot'])+($dt_B_steamer['LOT']-$dts_B_steamer['basah_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Preset</td>
			  <td align="left">PRE1</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_B_preset['KG'],2),2);?></td>
			  <td align="center"><?php echo number_format(round($dt_B_preset['LOT'],2),0);?></td>
			  <td align="right"><?php echo number_format(round($dts_B_preset['basah'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_B_preset['basah_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['belah_c_basah']+(round($dt_B_preset['KG'],2)-round($dts_B_preset['basah'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['belah_c_basah_lot'])+($dt_B_preset['LOT']-$dts_B_preset['basah_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Oven Greige</td>
			  <td align="left">OVG1</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_B_ovenG['KG'],2),2); ?></td>
			  <td align="center"><?php echo round($dt_B_ovenG['LOT']); ?></td>
			  <td align="right"><?php echo number_format(round($dts_B_ovenG['basah'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_B_ovenG['basah_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['belah_c_basah']+(round($dt_B_ovenG['KG'],2)-round($dts_B_ovenG['basah'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['belah_c_basah_lot'])+($dt_B_ovenG['LOT']-$dts_B_ovenG['basah_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Belah Dyeing</td>
			  <td align="left">BLD1</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_B_dye_bl['KG'],2),2); ?></td>
			  <td align="center"><?php echo round($dt_B_dye_bl['LOT']); ?></td>
			  <td align="right"><?php echo number_format(round($dts_B_dye_bl['basah'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_B_dye_bl['basah_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['belah_c_basah']+(round($dt_B_dye_bl['KG'],2)-round($dts_B_dye_bl['basah'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['belah_c_basah_lot'])+($dt_B_dye_bl['LOT']-$dts_B_dye_bl['basah_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Belah Preset</td>
			  <td align="left">BLP1</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_B_pre_bl['KG'],2),2); ?></td>
			  <td align="center"><?php echo round($dt_B_pre_bl['LOT']); ?></td>
			  <td align="right"><?php echo number_format(round($dts_B_pre_bl['basah'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_B_pre_bl['basah_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['belah_c_basah']+(round($dt_B_pre_bl['KG'],2)-round($dts_B_pre_bl['basah'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['belah_c_basah_lot'])+($dt_B_pre_bl['LOT']-$dts_B_pre_bl['basah_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Lipat</td>
			  <td align="left">LIP1,LIP2</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_B_lipat['KG'],2),2); ?></td>
			  <td align="center"><?php echo round($dt_B_lipat['LOT']); ?></td>
			  <td align="right"><?php echo number_format(round($dts_B_lipat['basah'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_B_lipat['basah_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['belah_c_basah']+(round($dt_B_lipat['KG'],2)-round($dts_B_lipat['basah'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['belah_c_basah_lot'])+($dt_B_lipat['LOT']-$dts_B_lipat['basah_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Oven Fleece</td>
			  <td align="left">&nbsp;</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dts_B_Ov_fleece['basah'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_B_Ov_fleece['basah_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['belah_c_basah']+(round($dt_B_Ov_fleece1['KG'],2)-round($dts_B_Ov_fleece['basah'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['belah_c_basah_lot'])+($dt_B_Ov_fleece1['LOT']-$dts_B_Ov_fleece['basah_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Oven Stenter Ulang</td>
			  <td align="left">&nbsp;</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dts_B_Ov_strUlang['basah'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_B_Ov_strUlang['basah_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['belah_c_basah1']+(round($dt_B_Ov_strUlang1['KG'],2)-round($dts_B_Ov_strUlang['basah'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['belah_c_basah_lot'])+($dt_B_Ov_strUlang1['LOT']-$dts_B_Ov_strUlang['basah_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Finishing 1x</td>
			  <td align="left">FIN1, FIN2</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_B_fin_1x['KG'],2),2); ?></td>
			  <td align="center"><?php echo round($dt_B_fin_1x['LOT']); ?></td>
			  <td align="right"><?php echo number_format(round($dts_B_fin_1x['basah'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_B_fin_1x['basah_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['belah_c_basah1']+(round($dt_B_fin_1x['KG'],2)-round($dts_B_fin_1x['basah'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['belah_c_basah_lot'])+($dt_B_fin_1x['LOT']-$dts_B_fin_1x['basah_lot']); ?></td>
		    </tr>
        </tbody>
		<tfoot> 
			<?php
			$total_b_masuk_lot 	= $dt_B_oven['LOT']+$dt_B_oven_k['LOT']+$dt_B_stenter['LOT']+$dt_B_pr_dye['LOT']+$dt_B_fin_jadi['LOT']+$dt_B_fin_ulang['LOT']+$dt_B_fin_ulang_BRS['LOT']+$dt_B_fin_ulang_DYE['LOT']+$dt_B_fin_bl['LOT']+round($dt_B_steamer['LOT'],2)+$dt_B_preset['LOT']+$dt_B_ovenG['LOT']+$dt_B_dye_bl['LOT']+$dt_B_pre_bl['LOT']+$dt_B_lipat['LOT']+$dt_B_fin_1x['LOT'];
			$total_b_masuk_kg 	= round($dt_B_oven['KG'],2)+round($dt_B_oven_k['KG'],2)+round($dt_B_stenter['KG'],2)+round($dt_B_pr_dye['KG'],2)+round($dt_B_fin_jadi['KG'],2)+round($dt_B_fin_ulang['KG'],2)+round($dt_B_fin_ulang_BRS['KG'],2)+round($dt_B_fin_ulang_DYE['KG'],2)+round($dt_B_fin_bl['KG'],2)+round($dt_B_steamer['KG'],2)+round($dt_B_preset['KG'],2)+round($dt_B_ovenG['KG'],2)+round($dt_B_dye_bl['KG'],2)+round($dt_B_pre_bl['KG'],2)+round($dt_B_lipat['KG'],2)+round($dt_B_fin_1x['KG'],2);
			$total_b_keluar_lot 	= round($dts_B_oven['basah_lot'])+round($dts_B_oven_k['basah_lot'])+round($dts_B_stenter['basah_lot'])+round($dts_B_pr_dye['basah_lot'])+round($dts_B_fin_jadi['basah_lot'])+round($dts_B_fin_ulang['basah_lot'])+round($dts_B_fin_ulang_BRS['basah_lot'])+round($dts_B_fin_ulang_DYE['basah_lot'])+round($dts_B_fin_bl['basah_lot'])+round($dts_B_steamer['basah_lot'])+round($dts_B_preset['basah_lot'])+round($dts_B_ovenG['basah_lot'])+round($dts_B_dye_bl['basah_lot'])+round($dts_B_pre_bl['basah_lot'])+round($dts_B_lipat['basah_lot'])+round($dts_B_Ov_fleece['basah_lot'])+round($dts_B_Ov_strUlang['basah_lot'])+round($dts_B_fin_1x['basah_lot'],2);
			$total_b_keluar_kg 	= round($dts_B_oven['basah'],2)+round($dts_B_oven_k['basah'],2)+round($dts_B_stenter['basah'],2)+round($dts_B_pr_dye['basah'],2)+round($dts_B_fin_jadi['basah'],2)+round($dts_B_fin_ulang['basah'],2)+round($dts_B_fin_ulang_BRS['basah'],2)+round($dts_B_fin_ulang_DYE['basah'],2)+round($dts_B_fin_bl['basah'],2)+round($dts_B_steamer['basah'],2)+round($dts_B_preset['basah'],2)+round($dts_B_ovenG['basah'],2)+round($dts_B_dye_bl['basah'],2)+round($dts_B_pre_bl['basah'],2)+round($dts_B_lipat['basah'],2)+round($dts_B_Ov_fleece['basah'],2)+round($dts_B_Ov_strUlang['basah'],2)+round($dts_B_fin_1x['basah'],2);
			$total_b_sisa = $dts_B_sisa['oven_b_basah']+$dts_B_sisa['oven_k_basah']+$dts_B_sisa['oven_b_st_basah']+$dts_B_sisa['oven_p_dye_basah']+$dts_B_sisa['fin_jadi_basah']+$dts_B_sisa['fin_ul_basah']+$dts_B_sisa['fin_ul_p_brs_basah']+$dts_B_sisa['fin_ul_p_dye_basah']+$dts_B_sisa['belah_c_basah'];
			$total_b_sisa_lot = $dts_B_sisa['oven_b_basah_lot']+$dts_B_sisa['oven_k_basah_lot']+$dts_B_sisa['oven_b_st_basah_lot']+$dts_B_sisa['oven_p_dye_basah_lot']+$dts_B_sisa['fin_jadi_basah_lot']+$dts_B_sisa['fin_ul_basah_lot']+$dts_B_sisa['fin_ul_p_brs_basah_lot']+$dts_B_sisa['fin_ul_p_dye_basah_lot']+$dts_B_sisa['belah_c_basah_lot'];
      ?>
		    <tr>
            <td align="center" bgcolor="#FBF330">TOTAL BASAH</td>
            <td align="left" bgcolor="#FBF330">&nbsp;</td>
            <td align="right" bgcolor="#FBF330"><?php echo number_format($total_b_sisa,2); ?></td>
            <td align="center" bgcolor="#FBF330"><?php echo number_format($total_b_sisa_lot,0); ?></td>
            <td align="right" bgcolor="#FBF330"><?php echo number_format($total_b_masuk_kg,2); ?></td> 
            <td align="center" bgcolor="#FBF330"><?php echo number_format($total_b_masuk_lot,0); ?></td>
            <td align="right" bgcolor="#FBF330"><?php echo number_format($total_b_keluar_kg,2); ?></td>
            <td align="center" bgcolor="#FBF330"><?php echo number_format($total_b_keluar_lot,0); ?></td>
            <td align="right" bgcolor="#FBF330"><?php echo number_format(($total_b_sisa)+($total_b_masuk_kg-$total_b_keluar_kg),2); ?></td>
            <td align="center" bgcolor="#FBF330"><?php echo number_format(($total_b_sisa_lot)+($total_b_masuk_lot-$total_b_keluar_lot),0); ?></td>
            </tr>
		</tfoot>  
      </table>
	  <br>
	  <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%" border="1">
        <thead class="bg-blue">
          <tr>
            <th bgcolor="#72EF71"><div align="center">PROSES KAIN KERING</div></th>
            <th bgcolor="#72EF71"><div align="center">Nama Operation di NOW</div></th>
            <th bgcolor="#72EF71"><div align="center">Stok Awal</div></th>
            <th bgcolor="#72EF71"><div align="center">Lot</div></th>
            <th bgcolor="#72EF71"><div align="center">Masuk</div></th>
            <th bgcolor="#72EF71"><div align="center">Lot</div></th>
            <th bgcolor="#72EF71"><div align="center">Keluar</div></th>
            <th bgcolor="#72EF71"><div align="center">Lot</div></th>
            <th bgcolor="#72EF71"><div align="center">Sisa</div></th>
            <th bgcolor="#72EF71"><div align="center">Lot</div></th>
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
			
$sqlsK_fin_ul_dyeing  = sqlsrv_query($conS, "SELECT
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
  and a.proses IN('Finishing Ulang - Dyeing (Bantu)')");		
$dts_K_fin_ul_dyeing	= sqlsrv_fetch_array($sqlsK_fin_ul_dyeing, SQLSRV_FETCH_ASSOC);	
			
$sqlsK_Ov_kering = sqlsrv_query($conS, "SELECT
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
  and a.proses IN('Oven Kering (Normal)')");		
$dts_K_Ov_kering	= sqlsrv_fetch_array($sqlsK_Ov_kering, SQLSRV_FETCH_ASSOC);	
$sqlsK_Ov_fle_ul = sqlsrv_query($conS, "SELECT
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
  and a.proses IN('Oven Fleece Ulang (Normal)')");		
$dts_K_Ov_fle_ul	= sqlsrv_fetch_array($sqlsK_Ov_fle_ul, SQLSRV_FETCH_ASSOC);				

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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['steamer_kering']+(round($dt_K_steamer['KG'],2)-round($dts_K_steamer['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['steamer_kering_lot'])+($dt_K_steamer['LOT']-$dts_K_steamer['kering_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['preset_kering']+(round($dt_K_preset['KG'],2)-round($dts_K_preset['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['preset_kering_lot'])+($dt_K_preset['LOT']-$dts_K_preset['kering_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['fin_1x_kering']+(round($dt_K_fin_1x['KG'],2)-round($dts_K_fin_1x['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['fin_1x_kering_lot'])+($dt_K_fin_1x['LOT']-$dts_K_fin_1x['kering_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['fin_jadi_kering']+(round($dt_K_fin_jadi['KG'],2)-round($dts_K_fin_jadi['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['fin_jadi_kering_lot'])+($dt_K_fin_jadi['LOT']-$dts_K_fin_jadi['kering_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['fin_ul_kering']+(round($dt_K_fin_ulang['KG'],2)-round($dts_K_fin_ulang['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['fin_ul_kering_lot'])+($dt_K_fin_ulang['LOT']-$dts_K_fin_ulang['kering_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['oven_obat_kering']+(round($dt_K_padder['KG'],2)-round($dts_K_padder['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['oven_obat_kering_lot'])+($dt_K_padder['LOT']-$dts_K_padder['kering_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['padder_kering']+(round($dt_K_tambah_obat['KG'],2)-round($dts_K_tambah_obat['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['padder_kering_lot'])+($dt_K_tambah_obat['LOT']-$dts_K_tambah_obat['kering_lot']); ?></td>
            </tr>
			<tr>
            <td align="left">Pot. Pinggir</td>
            <td align="left">&nbsp;</td>
            <td align="right"><?php echo number_format(round($dts_B_sisa['pot_pinggir_kering'],2),2);?></td>
            <td align="center"><?php echo number_format(round($dts_B_sisa['pot_pinggir_kering_lot'],0),0);?></td>
            <td align="right">0.00</td>
            <td align="center">0</td>
            <td align="right">0.00</td>
            <td align="center">0</td>
            <td align="right" bgcolor="#B9E1F0">0.00</td>
            <td align="center" bgcolor="#B9E1F0">0</td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['tarik_lebar_kering']+(round($dt_K_tarik['KG'],2)-round($dts_K_tarik['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['tarik_lebar_kering_lot'])+($dt_K_tarik['LOT']-$dts_K_tarik['kering_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['compact_n_kering']+(round($dt_K_compact['KG'],2)-round($dts_K_compact['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['compact_n_kering_lot'])+($dt_K_compact['LOT']-$dts_K_compact['kering_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['compact_fin_kering']+(round($dt_K_compact_fin['KG'],2)-round($dts_K_compact_fin['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['compact_fin_kering_lot'])+($dt_K_compact_fin['LOT']-$dts_K_compact_fin['kering_lot']); ?></td>
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
            <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa['compact_dye_kering']+(round($dt_K_compact_dye['KG'],2)-round($dts_K_compact_dye['kering'],2)),2),2);?></td>
            <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa['compact_dye_kering_lot'])+($dt_K_compact_dye['LOT']-$dts_K_compact_dye['kering_lot']); ?></td>
            </tr>
			<tr>
			  <td align="left">Belah Dyeing</td>
			  <td align="left">BLD1</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_K_dye_bl['KG'],2),2); ?></td>
			  <td align="center"><?php echo round($dt_K_dye_bl['LOT']); ?></td>
			  <td align="right"><?php echo number_format(round($dts_K_dye_bl['kering'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_K_dye_bl['kering_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['compact_dye_kering']+(round($dt_K_dye_bl['KG'],2)-round($dts_K_dye_bl['kering'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['compact_dye_kering_lot'])+($dt_K_dye_bl['LOT']-$dts_K_dye_bl['kering_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Belah Preset</td>
			  <td align="left">BLP1</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_K_pre_bl['KG'],2),2); ?></td>
			  <td align="center"><?php echo round($dt_K_pre_bl['LOT']); ?></td>
			  <td align="right"><?php echo number_format(round($dts_K_pre_bl['kering'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_K_pre_bl['kering_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['compact_dye_kering']+(round($dt_K_pre_bl['KG'],2)-round($dts_K_pre_bl['kering'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['compact_dye_kering_lot'])+($dt_K_pre_bl['LOT']-$dts_K_pre_bl['kering_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Oven Greige</td>
			  <td align="left">OVG1</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_K_ovenG['KG'],2),2); ?></td>
			  <td align="center"><?php echo round($dt_K_ovenG['LOT']); ?></td>
			  <td align="right"><?php echo number_format(round($dts_K_oven['kering'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_K_oven['kering_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['compact_dye_kering']+(round($dt_K_oven['KG'],2)-round($dts_K_oven['kering'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['compact_dye_kering_lot'])+($dt_K_oven['LOT']-$dts_K_oven['kering_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Oven Body MC Stenter</td>
			  <td align="left">&nbsp;</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dts_K_stenter['kering'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_K_stenter['kering_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['compact_dye_kering']+(round($dt_K_stenter1['KG'],2)-round($dts_K_stenter['kering'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['compact_dye_kering_lot'])+($dt_K_stenter1['LOT']-$dts_K_stenter['kering_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Lipat</td>
			  <td align="left">LIP1,LIP2</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dt_K_lipat['KG'],2),2); ?></td>
			  <td align="center"><?php echo round($dt_K_lipat['LOT']); ?></td>
			  <td align="right"><?php echo number_format(round($dts_K_lipat['kering'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_K_lipat['kering_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_B_sisa1['compact_dye_kering']+(round($dt_K_lipat['KG'],2)-round($dts_K_lipat['kering'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_B_sisa1['compact_dye_kering_lot'])+($dt_K_lipat['LOT']-$dts_K_lipat['kering_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Oven Stenter Ulang</td>
			  <td align="left">&nbsp;</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dts_K_Ov_strUlang['kering'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_K_Ov_strUlang['kering_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_K_sisa1['compact_dye_kering']+(round($dt_K_Ov_strUlang1['KG'],2)-round($dts_K_Ov_strUlang['kering'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_K_sisa1['compact_dye_kering_lot'])+($dt_K_Ov_strUlang1['LOT']-$dts_K_Ov_strUlang['kering_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Oven Stenter</td>
			  <td align="left">&nbsp;</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dts_K_Ov_str['kering'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_K_Ov_str['kering_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_K_sisa1['compact_dye_kering']+(round($dt_K_Ov_str['KG'],2)-round($dts_K_Ov_str['kering'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_K_sisa1['compact_dye_kering_lot'])+($dt_K_Ov_str1['LOT']-$dts_K_Ov_str['kering_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Finishing Ulang Dyeing</td>
			  <td align="left">&nbsp;</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dts_K_fin_ul_dyeing['kering'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_K_fin_ul_dyeing['kering_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_K_sisa1['compact_dye_kering']+(round($dt_K_Ov_str['KG'],2)-round($dts_K_Ov_str['kering'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_K_sisa1['compact_dye_kering_lot'])+($dt_K_Ov_str1['LOT']-$dts_K_Ov_str['kering_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Oven Fleece Ulang</td>
			  <td align="left">&nbsp;</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dts_K_Ov_fle_ul['kering'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_K_Ov_fle_ul['kering_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_K_sisa1['compact_dye_kering']+(round($dt_K_Ov_str['KG'],2)-round($dts_K_Ov_str['kering'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_K_sisa1['compact_dye_kering_lot'])+($dt_K_Ov_str1['LOT']-$dts_K_Ov_str['kering_lot']); ?></td>
		    </tr>
			<tr>
			  <td align="left">Oven Kering</td>
			  <td align="left">&nbsp;</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right">0</td>
			  <td align="center">0</td>
			  <td align="right"><?php echo number_format(round($dts_K_Ov_kering['kering'],2),2); ?></td>
			  <td align="center"><?php echo round($dts_K_Ov_kering['kering_lot']); ?></td>
			  <td align="right" bgcolor="#B9E1F0"><?php echo number_format(round($dts_K_sisa1['compact_dye_kering']+(round($dt_K_Ov_str['KG'],2)-round($dts_K_Ov_str['kering'],2)),2),2);?></td>
			  <td align="center" bgcolor="#B9E1F0"><?php echo ($dts_K_sisa1['compact_dye_kering_lot'])+($dt_K_Ov_str1['LOT']-$dts_K_Ov_str['kering_lot']); ?></td>
		    </tr>
			
        </tbody>
		<tfoot>
			<?php
			$total_k_masuk_lot 	= $dt_K_steamer['LOT']+$dt_K_preset['LOT']+$dt_K_fin_1x['LOT']+$dt_K_fin_jadi['LOT']+$dt_K_fin_ulang['LOT']+$dt_K_padder['LOT']+$dt_K_tambah_obat['LOT']+$dt_K_compact['LOT']+$dt_K_compact_fin['LOT']+$dt_K_compact_dye['LOT']+$dt_K_dye_bl['LOT']+$dt_K_pre_bl['LOT']+$dt_K_ovenG['LOT']+$dt_K_lipat['LOT'];
			$total_k_masuk_kg 	= round($dt_K_steamer['KG'],2)+round($dt_K_preset['KG'],2)+round($dt_K_fin_1x['KG'],2)+round($dt_K_fin_jadi['KG'],2)+round($dt_K_fin_ulang['KG'],2)+round($dt_K_padder['KG'],2)+round($dt_K_tambah_obat['KG'],2)+round($dt_K_compact['KG'],2)+round($dt_K_compact_fin['KG'],2)+round($dt_K_compact_dye['KG'],2)+round($dt_K_dye_bl['KG'],2)+round($dt_K_pre_bl['KG'],2)+round($dt_K_ovenG['KG'],2)+round($dt_K_lipat['KG'],2);
			$total_k_keluar_kg 	= round($dts_K_steamer['kering'],2)+round($dts_K_preset['kering'],2)+round($dts_K_fin_1x['kering'],2)+round($dts_K_fin_jadi['kering'],2)+round($dts_K_fin_ulang['kering'],2)+round($dts_K_padder['kering'],2)+round($dts_K_pot['kering'],2)+round($dts_K_tarik['kering'],2)+round($dts_K_tambah_obat['kering'],2)+round($dts_K_compact['kering'],2)+round($dts_K_compact_fin['kering'],2)+round($dts_K_compact_dye['kering'],2)+round($dts_K_dye_bl['kering'],2)+round($dts_K_pre_bl['kering'],2)+round($dts_K_oven['kering'],2)+round($dts_K_stenter['kering'],2)+round($dts_K_lipat['kering'],2)+round($dts_K_Ov_strUlang['kering'],2)+round($dts_K_Ov_str['kering'],2)+round($dts_K_fin_ul_dyeing['kering'],2)+round($dts_K_Ov_fle_ul['kering'],2)+round($dts_K_Ov_kering['kering'],2);
			$total_k_keluar_lot 	= $dts_K_steamer['kering_lot']+$dts_K_preset['kering_lot']+$dts_K_fin_1x['kering_lot']+$dts_K_fin_jadi['kering_lot']+$dts_K_fin_ulang['kering_lot']+$dts_K_padder['kering_lot']+round($dts_K_pot['kering_lot'],2)+round($dts_K_tarik['kering_lot'],2)+$dts_K_tambah_obat['kering_lot']+$dts_K_compact['kering_lot']+$dts_K_compact_fin['kering_lot']+$dts_K_compact_dye['kering_lot']+round($dts_K_dye_bl['kering_lot'])+round($dts_K_pre_bl['kering_lot'])+round($dts_K_oven['kering_lot'])+round($dts_K_stenter['kering_lot'])+round($dts_K_lipat['kering_lot'])+round($dts_K_Ov_strUlang['kering_lot'])+round($dts_K_Ov_str['kering_lot'])+round($dts_K_fin_ul_dyeing['kering_lot'])+round($dts_K_Ov_fle_ul['kering_lot'])+round($dts_K_Ov_kering['kering_lot']);
			$total_k_sisa = $dts_B_sisa['steamer_kering']+$dts_B_sisa['preset_kering']+$dts_B_sisa['fin_1x_kering']+$dts_B_sisa['fin_jadi_kering']+$dts_B_sisa['fin_ul_kering']+$dts_B_sisa['oven_obat_kering']+$dts_B_sisa['padder_kering']+$dts_B_sisa['pot_pinggir_kering']+$dts_B_sisa['tarik_lebar_kering']+$dts_B_sisa['compact_n_kering']+$dts_B_sisa['compact_fin_kering']+$dts_B_sisa['compact_dye_kering'];
			$total_k_sisa_lot = $dts_B_sisa['steamer_kering_lot']+$dts_B_sisa['preset_kering_lot']+$dts_B_sisa['fin_1x_kering_lot']+$dts_B_sisa['fin_jadi_kering_lot']+$dts_B_sisa['fin_ul_kering_lot']+$dts_B_sisa['oven_obat_kering_lot']+$dts_B_sisa['padder_kering_lot']+$dts_B_sisa['pot_pinggir_kering_lot']+$dts_B_sisa['tarik_lebar_kering_lot']+$dts_B_sisa['compact_n_kering_lot']+$dts_B_sisa['compact_fin_kering_lot']+$dts_B_sisa['compact_dye_kering_lot'];
			?>
		    <tr>
            <td align="center" bgcolor="#FBF330">TOTAL KERING</td>
            <td align="left" bgcolor="#FBF330">&nbsp;</td>
            <td align="right" bgcolor="#FBF330"><?php echo number_format($total_k_sisa,2); ?></td>
            <td align="center" bgcolor="#FBF330"><?php echo $total_k_sisa_lot; ?></td>
            <td align="right" bgcolor="#FBF330"><?php echo number_format($total_k_masuk_kg,2); ?></td>
            <td align="center" bgcolor="#FBF330"><?php echo $total_k_masuk_lot; ?> </td>
            <td align="right" bgcolor="#FBF330"><?php echo number_format($total_k_keluar_kg,2); ?></td>
            <td align="center" bgcolor="#FBF330"><?php echo $total_k_keluar_lot; ?></td>
            <td align="right" bgcolor="#FBF330"><?php echo number_format(($total_k_sisa)+($total_k_masuk_kg-$total_k_keluar_kg),2); ?></td>
            <td align="center" bgcolor="#FBF330"><?php echo number_format(($total_k_sisa_lot)+($total_k_masuk_lot-$total_k_keluar_lot),0); ?></td>
            </tr>
		    <tr>
		      <td align="center" bgcolor="#7F9DF0">GRAND TOTAL</td>
		      <td align="left" bgcolor="#7F9DF0">&nbsp;</td>
		      <td align="right" bgcolor="#7F9DF0"><?php echo number_format($total_b_sisa+$total_k_sisa,2); ?></td>
		      <td align="center" bgcolor="#7F9DF0"><?php echo $total_b_sisa_lot+$total_k_sisa_lot; ?></td>
		      <td align="right" bgcolor="#7F9DF0"><?php echo number_format($total_b_masuk_kg+$total_k_masuk_kg,2); ?></td>
		      <td align="center" bgcolor="#7F9DF0"><?php echo $total_b_masuk_lot+$total_k_masuk_lot; ?></td>
		      <td align="right" bgcolor="#ECEF84"><?php echo number_format($total_b_keluar_kg+$total_k_keluar_kg,2); ?></td>
		      <td align="center" bgcolor="#7F9DF0"><?php echo $total_b_keluar_lot+$total_k_keluar_lot; ?></td>
		      <td align="right" bgcolor="#7F9DF0"><?php echo number_format(($total_b_sisa+$total_k_sisa)+(($total_b_masuk_kg+$total_k_masuk_kg)-($total_b_keluar_kg+$total_k_keluar_kg)),2); ?></td>
		      <td align="center" bgcolor="#7F9DF0"><?php echo ($total_b_sisa_lot+$total_k_sisa_lot)+(($total_b_masuk_lot+$total_k_masuk_lot)-($total_b_keluar_lot+$total_k_keluar_lot)); ?></td>
		      </tr>
		</tfoot>  
      </table>	
	  <br>	
      <table class="table table-bordered table-hover table-striped nowrap" id="example4" style="width:100%" border="1">
        <thead class="bg-blue">
          <tr>
            <th rowspan="2" bgcolor="#72EF71"><div align="center">MESIN</div></th>
            <th colspan="2" bgcolor="#72EF71"><div align="center">KAPASITAS</div></th>
            <th colspan="2" bgcolor="#72EF71"><div align="center">HASIL PRODUKSI</div></th>
            <th colspan="2" bgcolor="#72EF71"><div align="center">PRESENTASE</div></th>
            </tr>
          <tr>
            <th bgcolor="#72EF71"><div align="center">KG</div></th>
            <th bgcolor="#72EF71"><div align="center">YARDS</div></th>
            <th bgcolor="#72EF71"><div align="center">KG</div></th>
            <th bgcolor="#72EF71"><div align="center">YARDS</div></th>
            <th bgcolor="#72EF71"><div align="center">KG</div></th>
            <th bgcolor="#72EF71"><div align="center">YARDS</div></th>
            </tr>
        </thead>
        <tbody>
          <?php		
			$sql_st01 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin='P3ST301'
GROUP BY a.no_mesin");
$rowd_st01 = sqlsrv_fetch_array($sql_st01, SQLSRV_FETCH_ASSOC);
$sql_st02 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin='P3ST302'
GROUP BY a.no_mesin");
$rowd_st02 = sqlsrv_fetch_array($sql_st02, SQLSRV_FETCH_ASSOC);	
$sql_st03 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin='P3ST103'
GROUP BY a.no_mesin");
$rowd_st03 = sqlsrv_fetch_array($sql_st03, SQLSRV_FETCH_ASSOC);
$sql_st04 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin='P3ST304'
GROUP BY a.no_mesin");
$rowd_st04 = sqlsrv_fetch_array($sql_st04, SQLSRV_FETCH_ASSOC);	
$sql_st05 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin='P3ST205'
GROUP BY a.no_mesin");
$rowd_st05 = sqlsrv_fetch_array($sql_st05, SQLSRV_FETCH_ASSOC);	
$sql_st06 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin='P3ST206'
GROUP BY a.no_mesin");
$rowd_st06 = sqlsrv_fetch_array($sql_st06, SQLSRV_FETCH_ASSOC);	
$sql_st07 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin='P3ST307'
GROUP BY a.no_mesin");
$rowd_st07 = sqlsrv_fetch_array($sql_st07, SQLSRV_FETCH_ASSOC);	
$sql_st08 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin='P3ST208'
GROUP BY a.no_mesin");
$rowd_st08 = sqlsrv_fetch_array($sql_st08, SQLSRV_FETCH_ASSOC);
$sql_st09 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin='P3ST109'
GROUP BY a.no_mesin");
$rowd_st09 = sqlsrv_fetch_array($sql_st09, SQLSRV_FETCH_ASSOC);
$sql_st10 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin='P3ST110'
GROUP BY a.no_mesin");
$rowd_st10 = sqlsrv_fetch_array($sql_st10, SQLSRV_FETCH_ASSOC);
$sql_cp01 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='compact' and a.no_mesin='P3CP101'
GROUP BY a.no_mesin");
$rowd_cp01 = sqlsrv_fetch_array($sql_cp01, SQLSRV_FETCH_ASSOC);	
$sql_cp02 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='compact' and a.no_mesin='P3CP102'
GROUP BY a.no_mesin");
$rowd_cp02 = sqlsrv_fetch_array($sql_cp02, SQLSRV_FETCH_ASSOC);	
$sql_cp03 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='compact' and a.no_mesin='P3CP103'
GROUP BY a.no_mesin");
$rowd_cp03 = sqlsrv_fetch_array($sql_cp03, SQLSRV_FETCH_ASSOC);	
$sql_belah = sqlsrv_query($conS, "
			SELECT
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and jns_mesin ='belah'
  and a.no_mesin LIKE 'P3BC%'");
$rowd_belah = sqlsrv_fetch_array($sql_belah, SQLSRV_FETCH_ASSOC);
$sql_ovn_01 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='oven'
GROUP BY a.no_mesin");
$rowd_ovn_01 = sqlsrv_fetch_array($sql_ovn_01, SQLSRV_FETCH_ASSOC);			
$sql_stmr = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and a.nama_mesin = 'STM1'
GROUP BY a.no_mesin");
$rowd_stmr = sqlsrv_fetch_array($sql_stmr, SQLSRV_FETCH_ASSOC);
$sql_lip = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,    
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.qty ELSE 0 END) AS kg,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and  (a.shift= 'A' or a.shift= 'B' or a.shift= 'C')  THEN a.panjang ELSE 0 END) AS yard
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
  -- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  -- and a.nama_mesin = 'LIP1'
  and a.no_mesin='P3IN350'
GROUP BY a.no_mesin");
$rowd_lip = sqlsrv_fetch_array($sql_lip, SQLSRV_FETCH_ASSOC);			
		?>
          <tr>
            <td align="center">ST 01</td>
            <td align="right">15,000.00</td>
            <td align="right">34,500.00</td>
            <td align="right"><?php echo number_format($rowd_st01['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_st01['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_st01['kg']/15000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_st01['yard']/34500,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">ST 02</td>
            <td align="right">15,000.00</td>
            <td align="right">34,500.00</td>
            <td align="right"><?php echo number_format($rowd_st02['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_st02['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_st02['kg']/15000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_st02['yard']/34500,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">ST 03</td>
            <td align="right">15,000.00</td>
            <td align="right">34,500.00</td>
            <td align="right"><?php echo number_format($rowd_st03['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_st03['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_st03['kg']/15000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_st03['yard']/34500,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">ST 04</td>
            <td align="right">15,000.00</td>
            <td align="right">34,500.00</td>
            <td align="right"><?php echo number_format($rowd_st04['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_st04['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_st04['kg']/15000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_st04['yard']/34500,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">ST 05</td>
            <td align="right">9,000.00</td>
            <td align="right">20,700.00</td>
            <td align="right"><?php echo number_format($rowd_st05['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_st05['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_st05['kg']/9000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_st05['yard']/20700,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">ST 06</td>
            <td align="right">9,000.00</td>
            <td align="right">20,700.00</td>
            <td align="right"><?php echo number_format($rowd_st06['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_st06['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_st06['kg']/9000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_st06['yard']/20700,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">ST 07</td>
            <td align="right">15,000.00</td>
            <td align="right">34,500.00</td>
            <td align="right"><?php echo number_format($rowd_st07['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_st07['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_st07['kg']/15000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_st07['yard']/34500,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">ST 08</td>
            <td align="right">15,000.00</td>
            <td align="right">34,500.00</td>
            <td align="right"><?php echo number_format($rowd_st08['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_st08['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_st08['kg']/15000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_st08['yard']/34500,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">ST 09</td>
            <td align="right">10,500.00</td>
            <td align="right">24,150.00</td>
            <td align="right"><?php echo number_format($rowd_st09['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_st09['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_st09['kg']/10500,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_st09['yard']/24150,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">ST 10</td>
            <td align="right">9,000.00</td>
            <td align="right">20,700.00</td>
            <td align="right"><?php echo number_format($rowd_st10['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_st10['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_st10['kg']/9000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_st10['yard']/20700,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">BELAH CUCI</td>
            <td align="right">45,000.00</td>
            <td align="right">103,500.00</td>
            <td align="right"><?php echo number_format($rowd_belah['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_belah['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_belah['kg']/45000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_belah['yard']/103500,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">Oven 01</td>
            <td align="right">6,000.00</td>
            <td align="right">13,800.00</td>
            <td align="right"><?php echo number_format($rowd_ovn_01['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_ovn_01['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_ovn_01['kg']/6000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_ovn_01['yard']/13800,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">Oven Kragh</td>
            <td align="right">0.00</td>
            <td align="right">0.00</td>
            <td align="right">0.00</td>
            <td align="right">0.00</td>
            <td align="right">0.00 %</td>
            <td align="right">0.00 %</td>
          </tr>
          <tr>
            <td align="center">COMPACT 01</td>
            <td align="right">6,000.00</td>
            <td align="right">13,800.00</td>
            <td align="right"><?php echo number_format($rowd_cp01['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_cp01['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_cp01['kg']/6000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_cp01['yard']/13800,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">COMPACT 02</td>
            <td align="right">7,500.00</td>
            <td align="right">17,250.00</td>
            <td align="right"><?php echo number_format($rowd_cp02['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_cp02['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_cp02['kg']/7500,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_cp02['yard']/17250,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">COMPACT 03</td>
            <td align="right">7,500.00</td>
            <td align="right">17,250.00</td>
            <td align="right"><?php echo number_format($rowd_cp03['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_cp03['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_cp03['kg']/7500,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_cp03['yard']/17250,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">STEAMER</td>
            <td align="right">30,000.00</td>
            <td align="right">69,000.00</td>
            <td align="right"><?php echo number_format($rowd_stmr['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_stmr['yard'],2); ?></td>
            <td align="right"><?php echo number_format(round($rowd_stmr['kg']/30000,4)*100,2);?> %</td>
            <td align="right"><?php echo number_format(round($rowd_stmr['yard']/69000,4)*100,2);?> %</td>
            </tr>
          <tr>
            <td align="center">LIPAT</td>
            <td align="right">-</td>
            <td align="right">-</td>
            <td align="right"><?php echo number_format($rowd_lip['kg'],2); ?></td>
            <td align="right"><?php echo number_format($rowd_lip['yard'],2); ?></td>
            <td align="right">-</td>
            <td align="right">-</td>
          </tr>
          <tr>
			<?php
			  $total_kg = $rowd_st01['kg']+$rowd_st02['kg']+$rowd_st03['kg']+$rowd_st04['kg']+$rowd_st05['kg']+$rowd_st06['kg']+$rowd_st07['kg']+$rowd_st08['kg']+$rowd_st09['kg']+$rowd_st10['kg']+$rowd_belah['kg']+$rowd_ovn_01['kg']+$rowd_cp01['kg']+$rowd_cp02['kg']+$rowd_cp03['kg']+$rowd_stmr['kg']+$rowd_lip['kg'];
			  $total_yard = $rowd_st01['yard']+$rowd_st02['yard']+$rowd_st03['yard']+$rowd_st04['yard']+$rowd_st05['yard']+$rowd_st06['yard']+$rowd_st07['yard']+$rowd_st08['yard']+$rowd_st09['yard']+$rowd_st10['yard']+$rowd_belah['yard']+$rowd_ovn_01['yard']+$rowd_cp01['yard']+$rowd_cp02['yard']+$rowd_cp03['yard']+$rowd_stmr['yard']+$rowd_lip['yard'];
			?>  
            <td align="center">TOTAL</td>
            <td align="right">229,500.00</td>
            <td align="right">527,850.00</td>
            <td align="right" bgcolor="#ECEF84"><?php echo number_format($total_kg,2);?></td>
            <td align="right"><?php echo number_format($total_yard,2);?></td> 
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" align="center" bgcolor="#7F9DF0">TOTAL PRODUKSI ST+CP+OV</td>
            <td colspan="3" align="center" bgcolor="#7F9DF0"><?php echo number_format($total_kg-($rowd_stmr['kg']+$rowd_belah['kg']),2);?></td>
            </tr>
          <tr>
            <td colspan="4" align="center" bgcolor="#FBF330">TOTAL PROSES FINAL</td>
            <td colspan="3" align="center" bgcolor="#FBF330"><?php echo number_format(round($dts_B_oven_k['basah'],2)+round($dts_B_fin_jadi['basah'],2)+round($dts_B_fin_ulang['basah'],2)+round($dts_B_fin_ulang_BRS['basah'],2)+round($dts_B_fin_ulang_DYE['basah'],2)+round($dts_K_fin_jadi['kering'],2)+round($dts_K_fin_ulang['kering'],2)+round($dts_K_tambah_obat['kering'],2)+round($dts_K_padder['kering'],2)+round($dts_K_tarik['kering'],2)+round($dts_K_compact['kering'],2)+round($dts_K_compact_fin['kering'],2)+round($dts_K_compact_dye['kering'],2),2); ?></td>
            </tr>
        </tbody>
      </table>	  
		
		</td>
      <td width="1%">&nbsp;</td>
      <td width="42%">
	  <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%" border="1">
        <thead class="bg-blue">
          <tr>
            <th rowspan="2" bgcolor="#72EF71"><div align="center">MESIN</div></th>
            <th colspan="4" bgcolor="#72EF71"><div align="center">SHIFT A</div></th>
            <th bgcolor="#72EF71"><div align="center">TOTAL</div></th>
            <th colspan="4" bgcolor="#72EF71"><div align="center">SHIFT B</div></th>
            <th bgcolor="#72EF71"><div align="center">TOTAL</div></th>
            <th colspan="4" bgcolor="#72EF71"><div align="center">SHIFT C</div></th>
            <th bgcolor="#72EF71"><div align="center">TOTAL</div></th>
            <th bgcolor="#72EF71"><div align="center">GRAND TOTAL</div></th>
            <th bgcolor="#72EF71"><div align="center">GRAND TOTAL</div></th>
          </tr>
          <tr>
            <th bgcolor="#72EF71"><div align="center">BASAH</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">KERING</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">YARD</div></th>
            <th bgcolor="#72EF71"><div align="center">BASAH</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">KERING</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">YARD</div></th>
            <th bgcolor="#72EF71"><div align="center">BASAH</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">KERING</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">YARD</div></th>
            <th bgcolor="#72EF71"><div align="center">QUANTITY</div></th>
            <th bgcolor="#72EF71"><div align="center">YARD</div></th>
            </tr>
        </thead>
        <tbody>
          <?php
	$sqlS01 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A' THEN a.qty ELSE 0 END) AS basah_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN a.qty ELSE 0 END) AS kering_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS basah_lot_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS kering_lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B' THEN a.qty ELSE 0 END) AS basah_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN a.qty ELSE 0 END) AS kering_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS basah_lot_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS kering_lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C' THEN a.qty ELSE 0 END) AS basah_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN a.qty ELSE 0 END) AS kering_c,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS basah_lot_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS kering_lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin= 'P3ST301'
GROUP BY a.no_mesin");
$rowdS01 = sqlsrv_fetch_array($sqlS01, SQLSRV_FETCH_ASSOC);
			
$sqlS02 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A' THEN a.qty ELSE 0 END) AS basah_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN a.qty ELSE 0 END) AS kering_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS basah_lot_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS kering_lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B' THEN a.qty ELSE 0 END) AS basah_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN a.qty ELSE 0 END) AS kering_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS basah_lot_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS kering_lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C' THEN a.qty ELSE 0 END) AS basah_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN a.qty ELSE 0 END) AS kering_c,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS basah_lot_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS kering_lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin= 'P3ST302'
GROUP BY a.no_mesin");
$rowdS02 = sqlsrv_fetch_array($sqlS02, SQLSRV_FETCH_ASSOC);	

$sqlS03 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A' THEN a.qty ELSE 0 END) AS basah_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN a.qty ELSE 0 END) AS kering_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS basah_lot_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS kering_lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B' THEN a.qty ELSE 0 END) AS basah_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN a.qty ELSE 0 END) AS kering_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS basah_lot_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS kering_lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C' THEN a.qty ELSE 0 END) AS basah_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN a.qty ELSE 0 END) AS kering_c,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS basah_lot_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS kering_lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin= 'P3ST103'
GROUP BY a.no_mesin");
$rowdS03 = sqlsrv_fetch_array($sqlS03, SQLSRV_FETCH_ASSOC);		
			
$sqlS04 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A' THEN a.qty ELSE 0 END) AS basah_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN a.qty ELSE 0 END) AS kering_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS basah_lot_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS kering_lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B' THEN a.qty ELSE 0 END) AS basah_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN a.qty ELSE 0 END) AS kering_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS basah_lot_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS kering_lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C' THEN a.qty ELSE 0 END) AS basah_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN a.qty ELSE 0 END) AS kering_c,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS basah_lot_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS kering_lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin= 'P3ST304'
GROUP BY a.no_mesin");
$rowdS04 = sqlsrv_fetch_array($sqlS04, SQLSRV_FETCH_ASSOC);	
			
$sqlS05 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A' THEN a.qty ELSE 0 END) AS basah_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN a.qty ELSE 0 END) AS kering_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS basah_lot_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS kering_lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B' THEN a.qty ELSE 0 END) AS basah_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN a.qty ELSE 0 END) AS kering_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS basah_lot_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS kering_lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C' THEN a.qty ELSE 0 END) AS basah_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN a.qty ELSE 0 END) AS kering_c,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS basah_lot_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS kering_lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin= 'P3ST205'
GROUP BY a.no_mesin");
$rowdS05 = sqlsrv_fetch_array($sqlS05, SQLSRV_FETCH_ASSOC);	
			
$sqlS06 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A' THEN a.qty ELSE 0 END) AS basah_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN a.qty ELSE 0 END) AS kering_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS basah_lot_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS kering_lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B' THEN a.qty ELSE 0 END) AS basah_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN a.qty ELSE 0 END) AS kering_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS basah_lot_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS kering_lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C' THEN a.qty ELSE 0 END) AS basah_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN a.qty ELSE 0 END) AS kering_c,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS basah_lot_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS kering_lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00' 
  and jns_mesin ='stenter' and a.no_mesin= 'P3ST206'
GROUP BY a.no_mesin");
$rowdS06 = sqlsrv_fetch_array($sqlS06, SQLSRV_FETCH_ASSOC);	
			
$sqlS07 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A' THEN a.qty ELSE 0 END) AS basah_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN a.qty ELSE 0 END) AS kering_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS basah_lot_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS kering_lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B' THEN a.qty ELSE 0 END) AS basah_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN a.qty ELSE 0 END) AS kering_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS basah_lot_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS kering_lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C' THEN a.qty ELSE 0 END) AS basah_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN a.qty ELSE 0 END) AS kering_c,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS basah_lot_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS kering_lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin= 'P3ST307'
GROUP BY a.no_mesin");
$rowdS07 = sqlsrv_fetch_array($sqlS07, SQLSRV_FETCH_ASSOC);
			
$sqlS08 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A' THEN a.qty ELSE 0 END) AS basah_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN a.qty ELSE 0 END) AS kering_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS basah_lot_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS kering_lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B' THEN a.qty ELSE 0 END) AS basah_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN a.qty ELSE 0 END) AS kering_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS basah_lot_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS kering_lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C' THEN a.qty ELSE 0 END) AS basah_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN a.qty ELSE 0 END) AS kering_c,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS basah_lot_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS kering_lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin= 'P3ST208'
GROUP BY a.no_mesin");
$rowdS08 = sqlsrv_fetch_array($sqlS08, SQLSRV_FETCH_ASSOC);	
	
$sqlS09 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A' THEN a.qty ELSE 0 END) AS basah_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN a.qty ELSE 0 END) AS kering_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS basah_lot_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS kering_lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B' THEN a.qty ELSE 0 END) AS basah_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN a.qty ELSE 0 END) AS kering_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS basah_lot_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS kering_lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C' THEN a.qty ELSE 0 END) AS basah_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN a.qty ELSE 0 END) AS kering_c,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS basah_lot_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS kering_lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin= 'P3ST109'
GROUP BY a.no_mesin");
$rowdS09 = sqlsrv_fetch_array($sqlS09, SQLSRV_FETCH_ASSOC);		
			
$sqlS10 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A' THEN a.qty ELSE 0 END) AS basah_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN a.qty ELSE 0 END) AS kering_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS basah_lot_a,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'A'  THEN 1 ELSE 0 END) AS kering_lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B' THEN a.qty ELSE 0 END) AS basah_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN a.qty ELSE 0 END) AS kering_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS basah_lot_b,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'B'  THEN 1 ELSE 0 END) AS kering_lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C' THEN a.qty ELSE 0 END) AS basah_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN a.qty ELSE 0 END) AS kering_c,
    SUM(CASE WHEN a.kondisi_kain = 'BASAH' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS basah_lot_c,
    SUM(CASE WHEN a.kondisi_kain = 'KERING' and  a.shift= 'C'  THEN 1 ELSE 0 END) AS kering_lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='stenter' and a.no_mesin= 'P3ST110'
GROUP BY a.no_mesin");
$rowdS10 = sqlsrv_fetch_array($sqlS10, SQLSRV_FETCH_ASSOC);			
	
		 ?>
          <tr>
            <td align="center">ST 01</td>
            <td align="center"><?php echo number_format($rowdS01['basah_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS01['basah_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS01['kering_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS01['kering_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS01['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS01['basah_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS01['basah_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS01['kering_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS01['kering_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS01['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS01['basah_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS01['basah_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS01['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS01['kering_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS01['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS01['basah_a']+$rowdS01['basah_b']+$rowdS01['basah_c']+$rowdS01['kering_a']+$rowdS01['kering_b']+$rowdS01['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS01['yard_a']+$rowdS01['yard_b']+$rowdS01['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">ST 02</td>
            <td align="center"><?php echo number_format($rowdS02['basah_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS02['basah_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS02['kering_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS02['kering_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS02['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS02['basah_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS02['basah_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS02['kering_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS02['kering_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS02['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS02['basah_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS02['basah_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS02['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS02['kering_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS02['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS02['basah_a']+$rowdS02['basah_b']+$rowdS02['basah_c']+$rowdS02['kering_a']+$rowdS02['kering_b']+$rowdS02['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS02['yard_a']+$rowdS02['yard_b']+$rowdS02['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">ST 03</td>
            <td align="center"><?php echo number_format($rowdS03['basah_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS03['basah_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS03['kering_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS03['kering_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS03['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS03['basah_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS03['basah_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS03['kering_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS03['kering_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS03['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS03['basah_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS03['basah_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS03['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS03['kering_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS03['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS03['basah_a']+$rowdS03['basah_b']+$rowdS03['basah_c']+$rowdS03['kering_a']+$rowdS03['kering_b']+$rowdS03['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS03['yard_a']+$rowdS03['yard_b']+$rowdS03['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">ST 04</td>
            <td align="center"><?php echo number_format($rowdS04['basah_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS04['basah_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS04['kering_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS04['kering_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS04['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS04['basah_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS04['basah_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS04['kering_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS04['kering_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS04['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS04['basah_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS04['basah_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS04['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS04['kering_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS04['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS04['basah_a']+$rowdS04['basah_b']+$rowdS04['basah_c']+$rowdS04['kering_a']+$rowdS04['kering_b']+$rowdS04['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS04['yard_a']+$rowdS04['yard_b']+$rowdS04['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">ST 05</td>
            <td align="center"><?php echo number_format($rowdS05['basah_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS05['basah_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS05['kering_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS05['kering_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS05['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS05['basah_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS05['basah_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS05['kering_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS05['kering_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS05['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS05['basah_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS05['basah_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS05['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS05['kering_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS05['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS05['basah_a']+$rowdS05['basah_b']+$rowdS05['basah_c']+$rowdS05['kering_a']+$rowdS05['kering_b']+$rowdS05['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS05['yard_a']+$rowdS05['yard_b']+$rowdS05['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">ST 06</td>
            <td align="center"><?php echo number_format($rowdS06['basah_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS06['basah_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS06['kering_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS06['kering_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS06['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS06['basah_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS06['basah_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS06['kering_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS06['kering_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS06['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS06['basah_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS06['basah_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS06['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS06['kering_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS06['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS06['basah_a']+$rowdS06['basah_b']+$rowdS06['basah_c']+$rowdS06['kering_a']+$rowdS06['kering_b']+$rowdS06['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS06['yard_a']+$rowdS06['yard_b']+$rowdS06['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">ST 07</td>
            <td align="center"><?php echo number_format($rowdS07['basah_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS07['basah_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS07['kering_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS07['kering_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS07['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS07['basah_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS07['basah_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS07['kering_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS07['kering_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS07['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS07['basah_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS07['basah_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS07['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS07['kering_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS07['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS07['basah_a']+$rowdS07['basah_b']+$rowdS07['basah_c']+$rowdS07['kering_a']+$rowdS07['kering_b']+$rowdS07['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS07['yard_a']+$rowdS07['yard_b']+$rowdS07['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">ST 08</td>
            <td align="center"><?php echo number_format($rowdS08['basah_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS08['basah_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS08['kering_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS08['kering_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS08['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS08['basah_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS08['basah_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS08['kering_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS08['kering_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS08['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS08['basah_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS08['basah_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS08['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS08['kering_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS08['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS08['basah_a']+$rowdS08['basah_b']+$rowdS08['basah_c']+$rowdS08['kering_a']+$rowdS08['kering_b']+$rowdS08['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS08['yard_a']+$rowdS08['yard_b']+$rowdS08['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">ST 09</td>
            <td align="center"><?php echo number_format($rowdS09['basah_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS09['basah_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS09['kering_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS09['kering_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS09['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS09['basah_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS09['basah_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS09['kering_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS09['kering_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS09['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS09['basah_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS09['basah_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS09['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS09['kering_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS09['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS09['basah_a']+$rowdS09['basah_b']+$rowdS09['basah_c']+$rowdS09['kering_a']+$rowdS09['kering_b']+$rowdS09['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS09['yard_a']+$rowdS09['yard_b']+$rowdS09['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">ST 10</td>
            <td align="center"><?php echo number_format($rowdS10['basah_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS10['basah_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS10['kering_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS10['kering_lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS10['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS10['basah_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS10['basah_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS10['kering_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS10['kering_lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS10['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS10['basah_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS10['basah_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS10['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS10['kering_lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdS10['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS10['basah_a']+$rowdS10['basah_b']+$rowdS10['basah_c']+$rowdS10['kering_a']+$rowdS10['kering_b']+$rowdS10['kering_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdS10['yard_a']+$rowdS10['yard_b']+$rowdS10['yard_c'],2); ?></td>
            </tr>          
        </tbody>
		<tfoot>
			<?php	
			//basah
			$totalBAST01=$rowdS01['basah_a']+$rowdS02['basah_a']+$rowdS03['basah_a']+$rowdS04['basah_a']+$rowdS05['basah_a']+$rowdS06['basah_a']+$rowdS07['basah_a']+$rowdS08['basah_a']+$rowdS09['basah_a']+$rowdS10['basah_a'];
			$totalBAST01_lot=$rowdS01['basah_lot_a']+$rowdS02['basah_lot_a']+$rowdS03['basah_lot_a']+$rowdS04['basah_lot_a']+$rowdS05['basah_lot_a']+$rowdS06['basah_lot_a']+$rowdS07['basah_lot_a']+$rowdS08['basah_lot_a']+$rowdS09['basah_lot_a']+$rowdS10['basah_lot_a'];
			$totalBBST01=$rowdS01['basah_b']+$rowdS02['basah_b']+$rowdS03['basah_b']+$rowdS04['basah_b']+$rowdS05['basah_b']+$rowdS06['basah_b']+$rowdS07['basah_b']+$rowdS08['basah_b']+$rowdS09['basah_b']+$rowdS10['basah_b'];	
			$totalBBST01_lot=$rowdS01['basah_lot_b']+$rowdS02['basah_lot_b']+$rowdS03['basah_lot_b']+$rowdS04['basah_lot_b']+$rowdS05['basah_lot_b']+$rowdS06['basah_lot_b']+$rowdS07['basah_lot_b']+$rowdS08['basah_lot_b']+$rowdS09['basah_lot_b']+$rowdS10['basah_lot_b'];
			$totalBCST01=$rowdS01['basah_c']+$rowdS02['basah_c']+$rowdS03['basah_c']+$rowdS04['basah_c']+$rowdS05['basah_c']+$rowdS06['basah_c']+$rowdS07['basah_c']+$rowdS08['basah_c']+$rowdS09['basah_c']+$rowdS10['basah_c'];
			$totalBCST01_lot=$rowdS01['basah_lot_c']+$rowdS02['basah_lot_c']+$rowdS03['basah_lot_c']+$rowdS04['basah_lot_c']+$rowdS05['basah_lot_c']+$rowdS06['basah_lot_c']+$rowdS07['basah_lot_c']+$rowdS08['basah_lot_c']+$rowdS09['basah_lot_c']+$rowdS10['basah_lot_c'];
			
			//Kering
			$totalKAST01=$rowdS01['kering_a']+$rowdS02['kering_a']+$rowdS03['kering_a']+$rowdS04['kering_a']+$rowdS05['kering_a']+$rowdS06['kering_a']+$rowdS07['kering_a']+$rowdS08['kering_a']+$rowdS09['kering_a']+$rowdS10['kering_a'];
			$totalKAST01_lot=$rowdS01['kering_lot_a']+$rowdS02['kering_lot_a']+$rowdS03['kering_lot_a']+$rowdS04['kering_lot_a']+$rowdS05['kering_lot_a']+$rowdS06['kering_lot_a']+$rowdS07['kering_lot_a']+$rowdS08['kering_lot_a']+$rowdS09['kering_lot_a']+$rowdS10['kering_lot_a'];
			$totalKBST01=$rowdS01['kering_b']+$rowdS02['kering_b']+$rowdS03['kering_b']+$rowdS04['kering_b']+$rowdS05['kering_b']+$rowdS06['kering_b']+$rowdS07['kering_b']+$rowdS08['kering_b']+$rowdS09['kering_b']+$rowdS10['kering_b'];
			$totalKBST01_lot=$rowdS01['kering_lot_b']+$rowdS02['kering_lot_b']+$rowdS03['kering_lot_b']+$rowdS04['kering_lot_b']+$rowdS05['kering_lot_b']+$rowdS06['kering_lot_b']+$rowdS07['kering_lot_b']+$rowdS08['kering_lot_b']+$rowdS09['kering_lot_b']+$rowdS10['kering_lot_b'];
			$totalKCST01=$rowdS01['kering_c']+$rowdS02['kering_c']+$rowdS03['kering_c']+$rowdS04['kering_c']+$rowdS05['kering_c']+$rowdS06['kering_c']+$rowdS07['kering_c']+$rowdS08['kering_c']+$rowdS09['kering_c']+$rowdS10['kering_c'];
			$totalKCST01_lot=$rowdS01['kering_lot_c']+$rowdS02['kering_lot_c']+$rowdS03['kering_lot_c']+$rowdS04['kering_lot_c']+$rowdS05['kering_lot_c']+$rowdS06['kering_lot_c']+$rowdS07['kering_lot_c']+$rowdS08['kering_lot_c']+$rowdS09['kering_lot_c']+$rowdS10['kering_lot_c'];
			$totalAST01_yd=$rowdS01['yard_a']+$rowdS02['yard_a']+$rowdS03['yard_a']+$rowdS04['yard_a']+$rowdS05['yard_a']+$rowdS06['yard_a']+$rowdS07['yard_a']+$rowdS08['yard_a']+$rowdS09['yard_a']+$rowdS10['yard_a'];
			$totalBST01_yd=$rowdS01['yard_b']+$rowdS02['yard_b']+$rowdS03['yard_b']+$rowdS04['yard_b']+$rowdS05['yard_b']+$rowdS06['yard_b']+$rowdS07['yard_b']+$rowdS08['yard_b']+$rowdS09['yard_b']+$rowdS10['yard_b'];
			$totalCST01_yd=$rowdS01['yard_c']+$rowdS02['yard_c']+$rowdS03['yard_c']+$rowdS04['yard_c']+$rowdS05['yard_c']+$rowdS06['yard_c']+$rowdS07['yard_c']+$rowdS08['yard_c']+$rowdS09['yard_c']+$rowdS10['yard_c'];
			?>
			<tr>
            <td align="center" bgcolor="#ECEF84">TOTAL</td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalBAST01,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalBAST01_lot,0); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalKAST01,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalKAST01_lot,0); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalAST01_yd,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalBBST01,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalBBST01_lot,0); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalKBST01,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalKBST01_lot,0); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalBST01_yd,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalBCST01,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalBCST01_lot,0); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalKCST01,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalKCST01_lot,0); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalCST01_yd,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalBAST01+$totalBBST01+$totalBCST01+$totalKAST01+$totalKBST01+$totalKCST01,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totalAST01_yd+$totalBST01_yd+$totalCST01_yd,2); ?></td>
          </tr>  
		</tfoot>  
      </table>
      <br>
      <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%" border="1">
        <thead class="bg-blue">
          <tr>
            <th rowspan="2" bgcolor="#72EF71"><div align="center">MESIN</div></th>
            <th colspan="4" bgcolor="#72EF71"><div align="center">SHIFT A</div></th>
            <th bgcolor="#72EF71"><div align="center">TOTAL</div></th>
            <th colspan="4" bgcolor="#72EF71"><div align="center">SHIFT B</div></th>
            <th bgcolor="#72EF71"><div align="center">TOTAL</div></th>
            <th colspan="4" bgcolor="#72EF71"><div align="center">SHIFT C</div></th>
            <th bgcolor="#72EF71"><div align="center">TOTAL</div></th>
            <th bgcolor="#72EF71"><div align="center">GRAND TOTAL</div></th>
            <th bgcolor="#72EF71"><div align="center">GRAND TOTAL</div></th>
            <th bgcolor="#72EF71"><div align="center">GRAND TOTAL</div></th>
          </tr>
          <tr>
            <th bgcolor="#72EF71"><div align="center">KRAH</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">BODY</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">KG</div></th>
            <th bgcolor="#72EF71"><div align="center">KRAH</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">BODY</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">KG</div></th>
            <th bgcolor="#72EF71"><div align="center">KRAH</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">BODY</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">KG</div></th>
            <th bgcolor="#72EF71"><div align="center">BODY</div></th>
            <th bgcolor="#72EF71"><div align="center">KRAH</div></th>
            <th bgcolor="#72EF71"><div align="center">YARD</div></th>
            </tr>
        </thead>
        <tbody>
          <?php
			$sqlOvK = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and a.shift= 'A' THEN a.qty ELSE 0 END) AS body_a,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and a.shift= 'A' THEN 1 ELSE 0 END) AS body_lot_a,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and a.shift= 'A' THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and a.shift= 'B' THEN a.qty ELSE 0 END) AS body_b,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and a.shift= 'B' THEN 1 ELSE 0 END) AS body_lot_b,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and a.shift= 'B' THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and a.shift= 'C' THEN a.qty ELSE 0 END) AS body_c,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and a.shift= 'C' THEN 1 ELSE 0 END) AS body_lot_c,
    SUM(CASE WHEN (a.kondisi_kain = 'BASAH' or a.kondisi_kain = 'KERING') and a.shift= 'C' THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir'
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  AND jns_mesin ='oven' 	
GROUP BY a.no_mesin");
            $rowdOvK = sqlsrv_fetch_array($sqlOvK, SQLSRV_FETCH_ASSOC);
		 ?>
          <tr>
            <td align="center">OVEN KERING</td>
            <td align="center"><?php echo "0.00"; ?></td>
            <td align="center"><?php echo "0"; ?></td>
            <td align="center"><?php echo number_format($rowdOvK['body_a'],2); ?></td>
            <td align="center"><?php echo $rowdOvK['body_lot_a']; ?></td>
            <td align="center"><?php echo number_format($rowdOvK['body_a'],2); ?></td>
            <td align="center"><?php echo "0.00"; ?></td>
            <td align="center"><?php echo "0"; ?></td>
            <td align="center"><?php echo number_format($rowdOvK['body_b'],2); ?></td>
            <td align="center"><?php echo $rowdOvK['body_lot_b']; ?></td>
            <td align="center"><?php echo number_format($rowdOvK['body_b'],2); ?></td>
            <td align="center"><?php echo "0.00"; ?></td>
            <td align="center"><?php echo "0"; ?></td>
            <td align="center"><?php echo number_format($rowdOvK['body_c'],2); ?></td>
            <td align="center"><?php echo $rowdOvK['body_lot_c']; ?></td>
            <td align="center"><?php echo number_format($rowdOvK['body_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdOvK['body_a']+$rowdOvK['body_b']+$rowdOvK['body_c'],2); ?></td>
            <td align="center"><?php echo "0.00"; ?></td>
            <td align="center"><?php echo number_format($rowdOvK['yard_a']+$rowdOvK['yard_b']+$rowdOvK['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">F1X</td>
            <td align="center">0.00</td>
            <td align="center">0</td>
            <td align="center">0.00</td>
            <td align="center">0</td>
            <td align="center">0.00</td>
            <td align="center">0.00</td>
            <td align="center">0</td>
            <td align="center">0.00</td>
            <td align="center">0</td>
            <td align="center">0.00</td>
            <td align="center">0.00</td>
            <td align="center">0</td>
            <td align="center">0.00</td>
            <td align="center">0</td>
            <td align="center">0.00</td>
            <td align="center">0.00</td>
            <td align="center">0.00</td>
            <td align="center">0.00</td>
          </tr>
        </tbody>
      </table>
	  <br>
      <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%" border="1">
        <thead class="bg-blue">
          <tr>
            <th rowspan="2" bgcolor="#72EF71"><div align="center">MESIN</div></th>
            <th colspan="2" bgcolor="#72EF71"><div align="center">SHIFT A</div></th>
            <th bgcolor="#72EF71"><div align="center">TOTAL</div></th>
            <th colspan="2" bgcolor="#72EF71"><div align="center">SHIFT B</div></th>
            <th bgcolor="#72EF71"><div align="center">TOTAL</div></th>
            <th colspan="2" bgcolor="#72EF71"><div align="center">SHIFT C</div></th>
            <th bgcolor="#72EF71"><div align="center">TOTAL</div></th>
            <th bgcolor="#72EF71"><div align="center">GRAND TOTAL</div></th>
            <th bgcolor="#72EF71"><div align="center">GRAND TOTAL</div></th>
          </tr>
          <tr>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">QTY</div></th>
            <th bgcolor="#72EF71"><div align="center">YARD</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">QTY</div></th>
            <th bgcolor="#72EF71"><div align="center">YARD</div></th>
            <th bgcolor="#72EF71"><div align="center">LOT</div></th>
            <th bgcolor="#72EF71"><div align="center">QTY</div></th>
            <th bgcolor="#72EF71"><div align="center">YARD</div></th>
            <th bgcolor="#72EF71"><div align="center">QUANTITY</div></th>
            <th bgcolor="#72EF71"><div align="center">YARD</div></th>
            </tr>
        </thead>
        <tbody>
          <?php
			$sqlCPT1 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.shift= 'A' THEN a.qty ELSE 0 END) AS qty_a,
    SUM(CASE WHEN a.shift= 'A'  THEN 1 ELSE 0 END) AS lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.shift= 'B' THEN a.qty ELSE 0 END) AS qty_b,
    SUM(CASE WHEN a.shift= 'B'  THEN 1 ELSE 0 END) AS lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.shift= 'C' THEN a.qty ELSE 0 END) AS qty_c,
    SUM(CASE WHEN a.shift= 'C'  THEN 1 ELSE 0 END) AS lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='compact' and a.no_mesin = 'P3CP101'
GROUP BY a.no_mesin");
            $rowdCPT1 = sqlsrv_fetch_array($sqlCPT1, SQLSRV_FETCH_ASSOC);
			$sqlCPT2 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.shift= 'A' THEN a.qty ELSE 0 END) AS qty_a,
    SUM(CASE WHEN a.shift= 'A'  THEN 1 ELSE 0 END) AS lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.shift= 'B' THEN a.qty ELSE 0 END) AS qty_b,

    SUM(CASE WHEN a.shift= 'B'  THEN 1 ELSE 0 END) AS lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.shift= 'C' THEN a.qty ELSE 0 END) AS qty_c,
    SUM(CASE WHEN a.shift= 'C'  THEN 1 ELSE 0 END) AS lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='compact' and a.no_mesin = 'P3CP102'
GROUP BY a.no_mesin");
            $rowdCPT2 = sqlsrv_fetch_array($sqlCPT2, SQLSRV_FETCH_ASSOC);
			$sqlCPT3 = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.shift= 'A' THEN a.qty ELSE 0 END) AS qty_a,
    SUM(CASE WHEN a.shift= 'A'  THEN 1 ELSE 0 END) AS lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.shift= 'B' THEN a.qty ELSE 0 END) AS qty_b,
    SUM(CASE WHEN a.shift= 'B'  THEN 1 ELSE 0 END) AS lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.shift= 'C' THEN a.qty ELSE 0 END) AS qty_c,
    SUM(CASE WHEN a.shift= 'C'  THEN 1 ELSE 0 END) AS lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='compact' and a.no_mesin = 'P3CP103'
GROUP BY a.no_mesin");
            $rowdCPT3 = sqlsrv_fetch_array($sqlCPT3, SQLSRV_FETCH_ASSOC);
		 ?>
          <tr>
            <td align="center">COMPACT 1</td>
            <td align="center"><?php echo number_format($rowdCPT1['lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdCPT1['qty_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT1['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT1['lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdCPT1['qty_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT1['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT1['lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdCPT1['qty_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT1['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT1['qty_a']+$rowdCPT1['qty_b']+$rowdCPT1['qty_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT1['yard_a']+$rowdCPT1['yard_b']+$rowdCPT1['yard_c'],2); ?></td>
            </tr>
          <tr>
            <td align="center">COMPACT 2</td>
            <td align="center"><?php echo number_format($rowdCPT2['lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdCPT2['qty_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT2['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT2['lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdCPT2['qty_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT2['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT2['lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdCPT2['qty_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT2['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT2['qty_a']+$rowdCPT2['qty_b']+$rowdCPT2['qty_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT2['yard_a']+$rowdCPT2['yard_b']+$rowdCPT2['yard_c'],2); ?></td>
          </tr>
          <tr>
            <td align="center">COMPACT 3</td>
            <td align="center"><?php echo number_format($rowdCPT3['lot_a'],0); ?></td>
            <td align="center"><?php echo number_format($rowdCPT3['qty_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT3['yard_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT3['lot_b'],0); ?></td>
            <td align="center"><?php echo number_format($rowdCPT3['qty_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT3['yard_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT3['lot_c'],0); ?></td>
            <td align="center"><?php echo number_format($rowdCPT3['qty_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT3['yard_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT3['qty_a']+$rowdCPT3['qty_b']+$rowdCPT3['qty_c'],2); ?></td>
            <td align="center"><?php echo number_format($rowdCPT3['yard_a']+$rowdCPT3['yard_b']+$rowdCPT3['yard_c'],2); ?></td>
          </tr>          
        </tbody>
		<tfoot>  
		  <?php
			$totCPTA_lot = $rowdCPT1['lot_a']+$rowdCPT2['lot_a']+$rowdCPT3['lot_a'] ;
			$totCPTA_qty = $rowdCPT1['qty_a']+$rowdCPT2['qty_a']+$rowdCPT3['qty_a'] ;
			$totCPTA_yd = $rowdCPT1['yard_a']+$rowdCPT2['yard_a']+$rowdCPT3['yard_a'] ;
			$totCPTB_lot = $rowdCPT1['lot_b']+$rowdCPT2['lot_b']+$rowdCPT3['lot_b'] ;
			$totCPTB_qty = $rowdCPT1['qty_b']+$rowdCPT2['qty_b']+$rowdCPT3['qty_b'] ;
			$totCPTB_yd = $rowdCPT1['yard_b']+$rowdCPT2['yard_b']+$rowdCPT3['yard_b'] ;
			$totCPTC_lot = $rowdCPT1['lot_c']+$rowdCPT2['lot_c']+$rowdCPT3['lot_c'] ;
			$totCPTC_qty = $rowdCPT1['qty_c']+$rowdCPT2['qty_c']+$rowdCPT3['qty_c'] ;
			$totCPTC_yd = $rowdCPT1['yard_c']+$rowdCPT2['yard_c']+$rowdCPT3['yard_c'] ;
			$totG_qty = ($rowdCPT1['qty_a']+$rowdCPT1['qty_b']+$rowdCPT1['qty_c'])+($rowdCPT2['qty_a']+$rowdCPT2['qty_b']+$rowdCPT2['qty_c'])+($rowdCPT3['qty_a']+$rowdCPT3['qty_b']+$rowdCPT3['qty_c']);
			$totG_yd = ($rowdCPT1['yard_a']+$rowdCPT1['yard_b']+$rowdCPT1['yard_c'])+($rowdCPT2['yard_a']+$rowdCPT2['yard_b']+$rowdCPT2['yard_c'])+($rowdCPT3['yard_a']+$rowdCPT3['yard_b']+$rowdCPT3['yard_c']);
		  ?>	
		  <tr>
			<td align="center" bgcolor="#ECEF84">TOTAL</td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totCPTA_lot,0); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totCPTA_qty,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totCPTA_yd,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totCPTB_lot,0); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totCPTB_qty,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totCPTB_yd,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totCPTC_lot,0); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totCPTC_qty,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totCPTC_yd,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totG_qty,2); ?></td>
            <td align="center" bgcolor="#ECEF84"><?php echo number_format($totG_yd,2); ?></td>
          </tr>
		</tfoot>
      </table>
	  <br>
      <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%"border="1">
        <thead class="bg-blue">
          <tr>
            <th colspan="3" bgcolor="#E5B267"><div align="center">SHIFT</div></th>
            </tr>
          <tr>
            <th bgcolor="#E5B267"><div align="center">A</div></th>
            <th bgcolor="#E5B267"><div align="center">B</div></th>
            <th bgcolor="#E5B267"><div align="center">C</div></th>
            </tr>
        </thead>
        <tbody>
          <?php
	$no=1;
			$sqlb = sqlsrv_query($conS, "
			SELECT
    SUM(CASE WHEN a.shift= 'A' THEN a.qty ELSE 0 END) AS qty_a,
    SUM(CASE WHEN a.shift= 'A'  THEN 1 ELSE 0 END) AS lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.shift= 'B' THEN a.qty ELSE 0 END) AS qty_b,
    SUM(CASE WHEN a.shift= 'B'  THEN 1 ELSE 0 END) AS lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.shift= 'C' THEN a.qty ELSE 0 END) AS qty_c,
    SUM(CASE WHEN a.shift= 'C'  THEN 1 ELSE 0 END) AS lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
   -- and jns_mesin ='belah'
  and a.no_mesin LIKE 'P3BC%'");
            $rowdb = sqlsrv_fetch_array($sqlb, SQLSRV_FETCH_ASSOC);
		 ?>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center"><?php echo number_format($rowdb['qty_a'],2); ?></td>
            <td align="center"><?php echo number_format($rowdb['qty_b'],2); ?></td>
            <td align="center"><?php echo number_format($rowdb['qty_c'],2); ?></td>
          </tr>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center"><?php echo $rowdb['lot_a']; ?></td>
            <td align="center"><?php echo $rowdb['lot_b']; ?></td>
            <td align="center"><?php echo $rowdb['lot_c']; ?></td>
            </tr>          
        </tbody>
      </table>
      <br>
      <table class="table table-bordered table-hover table-striped nowrap" id="example1" style="width:100%" border="1">
        <thead class="bg-blue">
          <tr>
            <th colspan="2" bgcolor="#E5B267"><div align="center">Sisa Basah Per Jenis Kain</div></th>
            </tr>
        </thead>
        <tbody>
          <?php
	$no=1;
			$sql = sqlsrv_query($conS, "
			SELECT
    a.no_mesin,
    SUM(CASE WHEN a.shift= 'A' THEN a.qty ELSE 0 END) AS qty_a,
    SUM(CASE WHEN a.shift= 'A'  THEN 1 ELSE 0 END) AS lot_a,
    SUM(CASE WHEN a.shift= 'A'  THEN a.panjang ELSE 0 END) AS yard_a,
    SUM(CASE WHEN a.shift= 'B' THEN a.qty ELSE 0 END) AS qty_b,
    SUM(CASE WHEN a.shift= 'B'  THEN 1 ELSE 0 END) AS lot_b,
    SUM(CASE WHEN a.shift= 'B'  THEN a.panjang ELSE 0 END) AS yard_b,
    SUM(CASE WHEN a.shift= 'C' THEN a.qty ELSE 0 END) AS qty_c,
    SUM(CASE WHEN a.shift= 'C'  THEN 1 ELSE 0 END) AS lot_c,
    SUM(CASE WHEN a.shift= 'C'  THEN a.panjang ELSE 0 END) AS yard_c
FROM
	db_finishing.tbl_produksi a
LEFT JOIN db_finishing.tbl_no_mesin b ON
	a.no_mesin = b.no_mesin
WHERE
	-- CONVERT(DATE, a.tgl_update) BETWEEN '$Awal' AND '$Akhir' 
  CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal 23:01' AND '$Akhir 23:00'
  and jns_mesin ='compact'
GROUP BY a.no_mesin");
            $no = 1;
            $c = 0;
            $rowd = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
		 ?>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center">Catton</td>
            <td align="center"><?php echo "0"; ?></td>
          </tr>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center">CVC/TC</td>
            <td align="center"><?php echo "0"; ?></td>
          </tr>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center">Poly</td>
            <td align="center"><?php echo "0"; ?></td>
            </tr>
        </tbody>
      </table>	
	  </td>
    </tr>
    
  </tbody>
</table>     
      
      
	  
</body>
</html>nter"><?php echo "0"; ?></td>
          </tr>
          <tr bgcolor="<?php echo $bgcolor; ?>">
            <td align="center">Poly</td>
            <td align="center"><?php echo "0"; ?></td>
            </tr>
        </tbody>
      </table>	
	  </td>
    </tr>
    
  </tbody>
</table>     
      
      
	  
</body>
</html>