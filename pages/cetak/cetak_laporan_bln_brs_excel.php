<?php
     $Thn0 = isset($_GET['tahun']) ? $_GET['tahun'] : '';
	 $Bln0 = isset($_GET['bulan']) ? $_GET['bulan'] : '';  
	 $tanggalH = DateTime::createFromFormat('!m', $Bln0); 
     $BlnSkrng= strtoupper($tanggalH->format('F')) . " " . $Thn0; 
     header("Content-type: application/octet-stream");
     header("Content-Disposition: attachment; filename=REPORT-BULANAN-BRS-".$Thn0."_".$Bln0.".xls"); // ganti nama sesuai keperluan
     header("Pragma: no-cache");
     header("Expires: 0");
?>
<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
include "../../helper.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="styles_cetak_brs.css" rel="stylesheet" type="text/css">	-->
<title>Laporan Bulanan BRS</title>
</head>
<style type="text/css">
.tombolkanan {
	text-align: right;
}
	input{
text-align:center;
border:hidden;
}
@media print {
  ::-webkit-input-placeholder { /* WebKit browsers */
      color: transparent;
  }
  :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
      color: transparent;
  }
  ::-moz-placeholder { /* Mozilla Firefox 19+ */
      color: transparent;
  }
  :-ms-input-placeholder { /* Internet Explorer 10+ */
      color: transparent;
  }
  .pagebreak { page-break-before:always; }
  .header {display:block}
  table thead 
   {
    display: table-header-group;
   }
}
</style>	
<body>
<?php
		// Ambil tahun dari $startDate
		$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

		// Hitung tahun sebelumnya
		$tahun_sebelumnya = $tahun - 1;
		$bln   = $_GET['bulan']; 
	
		$bulan_target = (int) $_GET['bulan'];
		$bulan_sebelumnya = $bulan_target - 1;
		  
        $input = $_GET['awal']; 

        $end_time = $input . ' 23:00:00'; 
        $Awal = $input . ' 00:00:00';

		//tahun sebelumnya
		$hari_kerja_query01 = "SELECT COUNT(DISTINCT CAST(tgl_buat AS DATE)) as jml FROM db_brushing.tbl_produksi 
                                                WHERE YEAR(tgl_buat) = '$tahun_sebelumnya'";
                            $hari_kerja_result01 = sqlsrv_query($conb, $hari_kerja_query01);
                            $hari_kerja01 = sqlsrv_fetch_array($hari_kerja_result01); 
		  
		$query_table01="SELECT
                                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' THEN qty ELSE 0 END) AS garuk_ap,
                                            -- GROUP_CONCAT(DISTINCT CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' THEN TRIM(nodemand) ELSE NULL END) AS demand_garuk_ap,
                                            SUM(CASE WHEN proses in('GARUK FLEECE (Normal)', 'GARUK SLIGHT BRUSH (Normal)', 'GARUK SLIGHTLY BRUS (Normal)', 'GARUK GREIGE (Normal)', 'GARUK BANTU - DYG (Bantu)', 'GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS garuk_fleece,
                                            SUM(CASE WHEN proses IN ('POTONG BULU FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK SLIGHT BRUSH (Normal)','GARUK SLIGHTLY BRUS (Normal)') THEN qty ELSE 0 END) AS potong_bulu_fleece,
                                            SUM(CASE WHEN proses IN ('SISIR ANTI PILLING (Normal)','SISIR BANTU (FIN) (Bantu)','SISIR LAIN-LAIN (Bantu)','GARUK ANTI PILLING (Normal)') THEN qty ELSE 0 END) AS sisir_ap,
                                            SUM(CASE WHEN proses IN ('POTONG BULU ANTI PILLING (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','ANTI PILLING (Khusus)','ANTI PILLING NORMAL (Normal)','ANTI PILLING (Normal)','ANTI PILLING BIASA (Normal)') THEN qty ELSE 0 END) AS pbulu_ap,
                                            SUM(CASE WHEN proses IN ('ANTI PILLING (Khusus)','ANTI PILLING (Normal)','ANTI PILLING NORMAL (Normal)','ANTI PILLING BIASA (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','POTONG BULU ANTI PILLING (Normal)','PEACH SKIN (Normal)','POTONG BULU PEACH SKIN (Normal)','POTONG BULU FLEECE (Normal)') THEN qty ELSE 0 END) AS oven_ap,
                                            SUM(CASE WHEN proses IN ('PEACH SKIN (Normal)','PEACHSKIN GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)','PEACH SKIN BANTU - FIN (Bantu)','POTONG BULU PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS peach,
                                            SUM(CASE WHEN proses IN ('POTONG BULU PEACH SKIN (Normal)','PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS pb_peach,
                                            SUM(CASE WHEN proses = 'AIRO (Normal)' THEN qty ELSE 0 END) AS airo,
                                            SUM(CASE WHEN proses IN ('Potong Bulu (Bantu)','POTONG BULU 07 (Bantu)','POTONG BULU LAIN-LAIN (Bantu)','POTONG BULU LAIN-LAIN (Khusus)','POTONG BULU BACK BANTU-DYEING (Bantu)','POTONG BULU BACK BANTU-FIN (Bantu)','POTONG BULU BACK TAS BANTU (Bantu)','POTONG BULU FACE BANTU-DYEING (Bantu)','POTONG BULU FACE BANTU-FIN (Bantu)','POTONG BULU FACE BANTU-TAS (Bantu)','POTONG BULU FACE TAS BANTU (Bantu)','POTONG BULU GREIGE (Bantu)','POTONG BULU GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)',
                                                                     'PEACH SKIN BANTU - FIN (Bantu)','GARUK BANTU - DYG (Bantu)','GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS pb_lain,
                                            SUM(CASE WHEN proses IN ('ANTI PILLING BANTU - DYE (Bantu)',
                                                                        'ANTI PILLING BANTU - FIN (Bantu)',
                                                                        'ANTI PILLING BANTU - QC (Bantu)',
                                                                        'ANTI PILLING BANTU - TAS (Bantu)',
                                                                        'ANTI PILLING BANTU-DYEING (Bantu)',
                                                                        'ANTI PILLING BANTU-FINISHING (Bantu)',
                                                                        'ANTI PILLING LAIN-LAIN (Bantu)',
                                                                        'ANTI PILLING LAIN-LAIN (Khusus)',
                                                                        'PEACH BANTU TAS (Bantu)',
                                                                        'PEACH SKIN (Bantu)',
                                                                        'PEACH SKIN BANTU - DYE (Bantu)',
                                                                        'PEACH SKIN BANTU - FIN (Bantu)',
                                                                        'GARUK BANTU - DYG (Bantu)',
                                                                        'GARUK BANTU - FIN (Bantu)',
                                                                        'GARUK GREIGE (Bantu)',
                                                                        'GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS ap_lain,
                                            SUM(CASE WHEN proses = 'POLISHING (Normal)' THEN qty ELSE 0 END) AS polish,
                                            SUM(CASE WHEN (proses LIKE '%bantu%' OR proses LIKE '%NCP%') THEN qty ELSE 0 END) AS lain,
                                            SUM(CASE WHEN proses IN ('WET SUEDING (Normal)','WET SUEDING FINISHED BACK (Normal)',
                                                                    'WET SUEDING FINISHED FACE (Normal)',
                                                                    'WET SUEDING GREIGE BACK (Normal)',
                                                                    'WET SUEDING GREIGE FACE (Normal)') THEN qty ELSE 0 END) AS wet_sue,
                                            SUM(CASE WHEN proses IN ('NCP - Tunggu Perbaikan (Normal)',
                                                                    'GARUK FLEECE ULANG-DYE (Ulang)',
                                                                    'GARUK FLEECE ULANG-FIN (Ulang)',
                                                                    'GARUK FLEECE ULANG-BRS (Ulang)',
                                                                    'GARUK FLEECE ULANG-CQA (Ulang)',
                                                                    'GARUK ANTI PILLING-FIN (Ulang)',
                                                                    'GARUK ANTI PILLING-DYE (Ulang)',
                                                                    'GARUK ANTI PILLING-BRS (Ulang)',
                                                                    'GARUK ANTI PILLING-CQA (Ulang)',
                                                                    'PEACHSKIN GREIGE (Ulang)',
                                                                    'PEACHSKIN ULANG-DYE (Ulang)',
                                                                    'PEACHSKIN ULANG-BRS (Ulang)',
                                                                    'PEACHSKIN ULANG-CQA (Ulang)',
                                                                    'PEACHSKIN ULANG-FIN (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-FIN (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-DYE (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-BRS (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-CQA (Ulang)',
                                                                    'ANTI PILLING LAIN-LAIN KHUSUS-FIN (Ulang)',
                                                                    'ANTI PILLING LAIN-LAIN KHUSUS-DYE (Ulang)',
																	'ANTI PILLING LAIN-LAIN KHUSUS-BRS (Ulang)',
																	'ANTI PILLING LAIN-LAIN KHUSUS-CQA (Ulang)',
																	'ANTI PILLING LAIN-LAIN-CQA (Ulang)') THEN qty ELSE 0 END) AS bantu,
                                            -- GROUP_CONCAT(DISTINCT CASE 
                                            --     WHEN proses = 'GARUK FLEECE (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                            --     THEN TRIM(nodemand) 
                                            --     ELSE NULL 
                                            -- END) AS demand_garuk_fleece,
                                            SUM(CASE WHEN proses LIKE '%(Bantu)%' THEN qty ELSE 0 END) AS produksi_ulang,
                                            -- GROUP_CONCAT(DISTINCT CASE 
                                            --     WHEN proses LIKE '%(Bantu)%' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                            --     THEN TRIM(nodemand) 
                                            --     ELSE NULL 
                                            -- END) AS demand_produksi_ulang,
                                            -- GROUP_CONCAT(DISTINCT CASE 
                                            --     WHEN proses = 'PEACH SKIN (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                            --     THEN TRIM(nodemand) 
                                            --     ELSE NULL 
                                            -- END) AS demand_peach_skin,
                                            count(distinct nodemand) as total_kk
                                        FROM
                                            db_brushing.tbl_produksi tp
                                        WHERE 
                                        YEAR(tp.tgl_buat)='$tahun_sebelumnya' ";
                        $stmt_qry01 = sqlsrv_query($conb, $query_table01);
                        $data_table01 = sqlsrv_fetch_array($stmt_qry01);  
		//GANTI KAIN
						//Internal	
						$sqlgk_in01=mysqli_query($cona," SELECT
							SUM(qty_order) as kg
						FROM
							tbl_gantikain tb
						WHERE
						YEAR(tgl_update) = '$tahun_sebelumnya'
						and (t_jawab='BRS' or t_jawab1='BRS' or t_jawab2='BRS' or t_jawab3='BRS' or t_jawab4='BRS' )
						and kategori = '0'
						");
						$rgIn01=mysqli_fetch_array($sqlgk_in01);
						//Eksternal	
						$sqlgk_ex01=mysqli_query($cona," SELECT
							SUM(qty_order) as kg
						FROM 
                        tbl_gantikain tb
						WHERE
						YEAR(tgl_update) = '$tahun_sebelumnya'
						and (t_jawab='BRS' or t_jawab1='BRS' or t_jawab2='BRS' or t_jawab3='BRS' or t_jawab4='BRS' )
						and kategori = '1'
						");
						$rgEx01=mysqli_fetch_array($sqlgk_ex01);  
		//Desember tahun sebelumnya
		$hari_kerja_query0 = "SELECT COUNT(DISTINCT CAST(tgl_buat AS DATE)) as jml FROM db_brushing.tbl_produksi 
                                                WHERE YEAR(tgl_buat) = '$tahun_sebelumnya' AND MONTH(tgl_buat) = '12'";
                            $hari_kerja_result0 = sqlsrv_query($conb, $hari_kerja_query0);
                            $hari_kerja0 = sqlsrv_fetch_array($hari_kerja_result0); 
		  
		$query_table0="SELECT
                                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' THEN qty ELSE 0 END) AS garuk_ap,
                                            -- GROUP_CONCAT(DISTINCT CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' THEN TRIM(nodemand) ELSE NULL END) AS demand_garuk_ap,
                                            SUM(CASE WHEN proses in('GARUK FLEECE (Normal)', 'GARUK SLIGHT BRUSH (Normal)', 'GARUK SLIGHTLY BRUS (Normal)', 'GARUK GREIGE (Normal)', 'GARUK BANTU - DYG (Bantu)', 'GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS garuk_fleece,
                                            SUM(CASE WHEN proses IN ('POTONG BULU FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK SLIGHT BRUSH (Normal)','GARUK SLIGHTLY BRUS (Normal)') THEN qty ELSE 0 END) AS potong_bulu_fleece,
                                            SUM(CASE WHEN proses IN ('SISIR ANTI PILLING (Normal)','SISIR BANTU (FIN) (Bantu)','SISIR LAIN-LAIN (Bantu)','GARUK ANTI PILLING (Normal)') THEN qty ELSE 0 END) AS sisir_ap,
                                            SUM(CASE WHEN proses IN ('POTONG BULU ANTI PILLING (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','ANTI PILLING (Khusus)','ANTI PILLING NORMAL (Normal)','ANTI PILLING (Normal)','ANTI PILLING BIASA (Normal)') THEN qty ELSE 0 END) AS pbulu_ap,
                                            SUM(CASE WHEN proses IN ('ANTI PILLING (Khusus)','ANTI PILLING (Normal)','ANTI PILLING NORMAL (Normal)','ANTI PILLING BIASA (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','POTONG BULU ANTI PILLING (Normal)','PEACH SKIN (Normal)','POTONG BULU PEACH SKIN (Normal)','POTONG BULU FLEECE (Normal)') THEN qty ELSE 0 END) AS oven_ap,
                                            SUM(CASE WHEN proses IN ('PEACH SKIN (Normal)','PEACHSKIN GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)','PEACH SKIN BANTU - FIN (Bantu)','POTONG BULU PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS peach,
                                            SUM(CASE WHEN proses IN ('POTONG BULU PEACH SKIN (Normal)','PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS pb_peach,
                                            SUM(CASE WHEN proses = 'AIRO (Normal)' THEN qty ELSE 0 END) AS airo,
                                            SUM(CASE WHEN proses IN ('Potong Bulu (Bantu)','POTONG BULU 07 (Bantu)','POTONG BULU LAIN-LAIN (Bantu)','POTONG BULU LAIN-LAIN (Khusus)','POTONG BULU BACK BANTU-DYEING (Bantu)','POTONG BULU BACK BANTU-FIN (Bantu)','POTONG BULU BACK TAS BANTU (Bantu)','POTONG BULU FACE BANTU-DYEING (Bantu)','POTONG BULU FACE BANTU-FIN (Bantu)','POTONG BULU FACE BANTU-TAS (Bantu)','POTONG BULU FACE TAS BANTU (Bantu)','POTONG BULU GREIGE (Bantu)','POTONG BULU GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)',
                                                                     'PEACH SKIN BANTU - FIN (Bantu)','GARUK BANTU - DYG (Bantu)','GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS pb_lain,
                                            SUM(CASE WHEN proses IN ('ANTI PILLING BANTU - DYE (Bantu)',
                                                                        'ANTI PILLING BANTU - FIN (Bantu)',
                                                                        'ANTI PILLING BANTU - QC (Bantu)',
                                                                        'ANTI PILLING BANTU - TAS (Bantu)',
                                                                        'ANTI PILLING BANTU-DYEING (Bantu)',
                                                                        'ANTI PILLING BANTU-FINISHING (Bantu)',
                                                                        'ANTI PILLING LAIN-LAIN (Bantu)',
                                                                        'ANTI PILLING LAIN-LAIN (Khusus)',
                                                                        'PEACH BANTU TAS (Bantu)',
                                                                        'PEACH SKIN (Bantu)',
                                                                        'PEACH SKIN BANTU - DYE (Bantu)',
                                                                        'PEACH SKIN BANTU - FIN (Bantu)',
                                                                        'GARUK BANTU - DYG (Bantu)',
                                                                        'GARUK BANTU - FIN (Bantu)',
                                                                        'GARUK GREIGE (Bantu)',
                                                                        'GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS ap_lain,
                                            SUM(CASE WHEN proses = 'POLISHING (Normal)' THEN qty ELSE 0 END) AS polish,
                                            SUM(CASE WHEN (proses LIKE '%bantu%' OR proses LIKE '%NCP%') THEN qty ELSE 0 END) AS lain,
                                            SUM(CASE WHEN proses IN ('WET SUEDING (Normal)','WET SUEDING FINISHED BACK (Normal)',
                                                                    'WET SUEDING FINISHED FACE (Normal)',
                                                                    'WET SUEDING GREIGE BACK (Normal)',
                                                                    'WET SUEDING GREIGE FACE (Normal)') THEN qty ELSE 0 END) AS wet_sue,
                                            SUM(CASE WHEN proses IN ('NCP - Tunggu Perbaikan (Normal)',
                                                                    'GARUK FLEECE ULANG-DYE (Ulang)',
                                                                    'GARUK FLEECE ULANG-FIN (Ulang)',
                                                                    'GARUK FLEECE ULANG-BRS (Ulang)',
                                                                    'GARUK FLEECE ULANG-CQA (Ulang)',
                                                                    'GARUK ANTI PILLING-FIN (Ulang)',
                                                                    'GARUK ANTI PILLING-DYE (Ulang)',
                                                                    'GARUK ANTI PILLING-BRS (Ulang)',
                                                                    'GARUK ANTI PILLING-CQA (Ulang)',
                                                                    'PEACHSKIN GREIGE (Ulang)',
                                                                    'PEACHSKIN ULANG-DYE (Ulang)',
                                                                    'PEACHSKIN ULANG-BRS (Ulang)',
                                                                    'PEACHSKIN ULANG-CQA (Ulang)',
                                                                    'PEACHSKIN ULANG-FIN (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-FIN (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-DYE (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-BRS (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-CQA (Ulang)',
                                                                    'ANTI PILLING LAIN-LAIN KHUSUS-FIN (Ulang)',
                                                                    'ANTI PILLING LAIN-LAIN KHUSUS-DYE (Ulang)',
																	'ANTI PILLING LAIN-LAIN KHUSUS-BRS (Ulang)',
																	'ANTI PILLING LAIN-LAIN KHUSUS-CQA (Ulang)',
																	'ANTI PILLING LAIN-LAIN-CQA (Ulang)') THEN qty ELSE 0 END) AS bantu,
                                            -- GROUP_CONCAT(DISTINCT CASE 
                                            --     WHEN proses = 'GARUK FLEECE (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                            --     THEN TRIM(nodemand) 
                                            --     ELSE NULL 
                                            -- END) AS demand_garuk_fleece,
                                            SUM(CASE WHEN proses LIKE '%(Bantu)%' THEN qty ELSE 0 END) AS produksi_ulang,
                                            -- GROUP_CONCAT(DISTINCT CASE 
                                            --     WHEN proses LIKE '%(Bantu)%' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                            --     THEN TRIM(nodemand) 
                                            --     ELSE NULL 
                                            -- END) AS demand_produksi_ulang,
                                            -- GROUP_CONCAT(DISTINCT CASE 
                                            --     WHEN proses = 'PEACH SKIN (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                            --     THEN TRIM(nodemand) 
                                            --     ELSE NULL 
                                            -- END) AS demand_peach_skin,
                                            count(distinct nodemand) as total_kk
                                        FROM
                                            db_brushing.tbl_produksi tp
                                        WHERE 
                                        YEAR(tp.tgl_buat)='$tahun_sebelumnya' AND MONTH(tp.tgl_buat)='12'  ";
                        $stmt_qry0 = sqlsrv_query($conb, $query_table0);
                        $data_table0 = sqlsrv_fetch_array($stmt_qry0); 
		  //GANTI KAIN
						//Internal	
						$sqlgk_in0=mysqli_query($cona," SELECT
							SUM(qty_order) as kg
						FROM
							tbl_gantikain tb
						WHERE
						MONTH(tgl_update) = '12' AND YEAR(tgl_update) = '$tahun_sebelumnya'
						and (t_jawab='BRS' or t_jawab1='BRS' or t_jawab2='BRS' or t_jawab3='BRS' or t_jawab4='BRS' )
						and kategori = '0'
						");
						$rgIn0=mysqli_fetch_array($sqlgk_in0);
						//Eksternal	
						$sqlgk_ex0=mysqli_query($cona," SELECT
							SUM(qty_order) as kg
						FROM
							tbl_gantikain tb
						WHERE
						MONTH(tgl_update) = '12' AND YEAR(tgl_update) = '$tahun_sebelumnya'
						and (t_jawab='BRS' or t_jawab1='BRS' or t_jawab2='BRS' or t_jawab3='BRS' or t_jawab4='BRS' )
						and kategori = '1'
						");
						$rgEx0=mysqli_fetch_array($sqlgk_ex0);
        // Ambil bulan dari tanggal input
        $startDate = new DateTime(date('Y-m-01', strtotime($input))); // Awal bulan input
        $endDate = new DateTime(date('Y-m-t', strtotime($input)));     // Akhir bulan input

        $date_start_tbl2 = new DateTime($input. '23:00:00');
        $date_end_tbl2 = clone $date_start_tbl2;
        $date_end_tbl2->modify('-1 day');
        $start_formatted = $date_end_tbl2->format('Y-m-d H:i:s');
        $end_formatted = $date_start_tbl2->format('Y-m-d H:i:s');
	
		// Buat DateTime awal bulan
		$tahunUlang1 = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
		$bulanUlang1 = isset($_GET['bulan']) ? str_pad(intval($_GET['bulan']), 2, '0', STR_PAD_LEFT) : date('m');

		$startDateUlang1 = new DateTime("$tahunUlang1-$bulanUlang1-01");
		// Format jam cutoff awal dan akhir
		$start_timeUlang1 = $startDateUlang1->format('Y-m-d'). " 23:01:00";
		$end_timeUlang1   = (clone $startDateUlang1)->modify('last day of this month')->format('Y-m-d'). " 23:00:00";
		 
		$query_tbl2 = "SELECT
                            SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_fleece_ulang,
                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_ap_ulang,
                            SUM(CASE WHEN proses IN('PEACHSKIN ULANG-BRS (Ulang)', 'PEACHSKIN GREIGE (Ulang)') THEN qty ELSE 0 END) AS brs_peach_ulang,
                            SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_pb_ulang,
                            SUM(CASE WHEN proses = 'ANTI PILLING LAIN-LAIN KHUSUS-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_oven_ulang,
                            SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_fleece_ulang,
                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_ap_ulang,
                            SUM(CASE WHEN proses = 'PEACHSKIN ULANG-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_peach_ulang,
                            SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_pb_ulang,
                            SUM(CASE WHEN proses = 'ANTI PILLING LAIN-LAIN KHUSUS-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_oven_ulang,
                            SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_fleece_ulang,
                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_ap_ulang,
                            SUM(CASE WHEN proses = 'PEACHSKIN ULANG-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_peach_ulang,
                            SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_pb_ulang,
                            SUM(CASE WHEN proses = 'ANTI PILLING LAIN-LAIN KHUSUS-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_oven_ulang,
                            SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_fleece_ulang,
                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_ap_ulang,
                            SUM(CASE WHEN proses = 'PEACHSKIN ULANG-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_peach_ulang,
                            SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_pb_ulang,
                            SUM(CASE WHEN proses = 'ANTI PILLING LAIN-LAIN KHUSUS-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_oven_ulang
                        FROM
                            db_brushing.tbl_produksi
                        WHERE
                            -- year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
							tgl_buat between '$start_timeUlang1' and '$end_timeUlang1'";
        $stmt_tbl2 = sqlsrv_query($conb, $query_tbl2);
        $row_tbl2 = sqlsrv_fetch_array($stmt_tbl2);
        $cek_tbl2 = sqlsrv_num_rows($stmt_tbl2);  
        
        // print_r($row_tbl2['brs_fleece_ulang']);
//        $start_ncp = $date_end_tbl2->format('Y-m-d');
//        $end_ncp = $date_start_tbl2->format('Y-m-d');
	 
		// Buat DateTime awal bulan
		$tahunNCP = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
		$bulanNCP = isset($_GET['bulan']) ? str_pad(intval($_GET['bulan']), 2, '0', STR_PAD_LEFT) : date('m');

		$startDateNCP = new DateTime("$tahunNCP-$bulanNCP-01");

		// Format jam cutoff awal dan akhir
		$start_timeNCP = $startDateNCP->format('Y-m-d');
		$end_timeNCP   = (clone $startDateNCP)->modify('last day of this month')->format('Y-m-d');
        $qry_ncp = "SELECT
                        SUM(berat) as qty_ncp
                    FROM
                        tbl_ncp_qcf_now
                    WHERE
                        STATUS IN ('Belum OK', 'OK', 'BS')
                        AND dept = 'BRS'
                        AND ncp_hitung = 'ya'
						AND tgl_buat BETWEEN '$start_timeNCP' AND '$end_timeNCP' ";
        $qry1 = mysqli_query($cond, $qry_ncp);
        $row_ncp = mysqli_fetch_assoc($qry1);

        // print_r( $startDate);
    ?>
    <!-- Tabel-1.php -->
         <!-- LANJUTIN DIBAGIAN TOTAL PALING BAWAH -->		  
<table border="1"  width="100%">
  <tbody>
	<tr>
      <td>&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
    </tr>  
    <tr>
      <td width="5%"><img src="https://online.indotaichen.com/ADM-ITTI/pages/cetak/Indo.jpg" width="48" height="48" alt="logo"/><p>&nbsp;</p></td>
      <td width="95%" colspan="17" align="center" valign="top"><font size="+2"><strong>LAPORAN PRODUKSI BULANAN DEPARTEMEN BRUSHING</strong></font>
		  <br>
		<font size="-1"><strong>FW - 02 - BRS - 09 / 13</strong></font><p>&nbsp;</p></td>
    </tr>
    
  </tbody>
</table>
<?= isset($_GET['awal']) && strtotime($_GET['awal']) ? date('d M y', strtotime($_GET['awal'])) : ''; ?>
<br>
<table border="1" width="100%">
  <tbody>
    <tr>
      <td align="center" valign="middle"><strong>PROSES</strong></td>
      <td rowspan="2" align="center" valign="middle"><strong>HARI KERJA</strong></td>
      <td rowspan="2" align="center" valign="middle"><strong>JUMLAH KK</strong></td>
      <td colspan="2" align="center" valign="middle"><strong>PROSES KAIN FLEECE</strong></td>
      <td colspan="4" align="center" valign="middle"><strong>PROSES KAIN ANTI PILLING</strong></td>
      <td colspan="2" align="center" valign="middle"><strong>PROSES PEACH SKIN</strong></td>
      <td rowspan="2" align="center" valign="middle"><strong>PROSES AIRO (D)</strong></td>
      <td colspan="2" align="center" valign="middle"><strong>PROESES BANTU</strong></td>
      <td rowspan="2" align="center" valign="middle"><strong>POLISHING (G)</strong></td>
      <td rowspan="2" align="center" valign="middle"><strong>WET SUEDING (H)</strong></td>
      <td rowspan="2" align="center" valign="middle"><strong>TOTAL PRODUKSI ULANG (H)</strong></td>
      <td colspan="2" align="center" valign="middle"><strong>TOTAL GANTI KAIN</strong></td>
      <td rowspan="2" align="center" valign="middle"><strong>TOTAL PRODUKSI (A+B+C+D+E+F+G+H)</strong></td>
    </tr>
    <tr>
      <td align="center" valign="middle"><strong>BULAN</strong></td>
      <td align="center" valign="middle"><strong>GARUK FLEECE (A)</strong></td>
      <td align="center" valign="middle"><strong>POTONG BULU FLEECE</strong></td>
      <td align="center" valign="middle"><strong>GARUK ANTI PILLING (B)</strong></td>
      <td align="center" valign="middle"><strong>SISIR ANTI PILLING</strong></td>
      <td align="center" valign="middle"><strong>POTONG BULU ANTI PILLING</strong></td>
      <td align="center" valign="middle"><strong>OVEN ANTI PILLING</strong></td>
      <td align="center" valign="middle"><strong>PEACH SKIN (C)</strong></td>
      <td align="center" valign="middle"><strong>POTONG BULU PEACH SKIN</strong></td>
      <td align="center" valign="middle"><strong>POTONG BULU LAIN - LAIN (E)</strong></td>
      <td align="center" valign="middle"><strong>OVEN ANTI PILLING LAIN - LAIN (F)</strong></td>
      <td align="center" valign="middle"><strong>INTERNAL</strong></td>
      <td align="center" valign="middle"><strong>EKSTERNAL</strong></td>
    </tr>	  
	<tr>
		<td align="left">JAN - DES <?php echo $tahun_sebelumnya; ?></td>
		<td align='center'><?php echo ($hari_kerja01['jml'] != 0) ? $hari_kerja01['jml'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['total_kk'] != 0) ? $data_table01['total_kk'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['garuk_fleece'] != 0) ? $data_table01['garuk_fleece'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['potong_bulu_fleece'] != 0) ? $data_table01['potong_bulu_fleece'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['garuk_ap'] != 0) ? $data_table01['garuk_ap'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['sisir_ap'] != 0) ? $data_table01['sisir_ap'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['pbulu_ap'] != 0) ? $data_table01['pbulu_ap'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['oven_ap'] != 0) ? $data_table01['oven_ap'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['peach'] != 0) ? $data_table01['peach'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['pb_peach'] != 0) ? $data_table01['pb_peach'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['airo'] != 0) ? $data_table01['airo'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['pb_lain'] != 0) ? $data_table01['pb_lain'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['ap_lain'] != 0) ? $data_table01['ap_lain'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['polish'] != 0) ? $data_table01['polish'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['wet_sue'] != 0) ? $data_table01['wet_sue'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table01['bantu'] != 0) ? $data_table01['bantu'] : '-'; ?></td>
		<td align='center'><?php echo ($rgIn01['kg'] != 0) ? $rgIn01['kg'] : '-'; ?></td> 
		<td align='center'><?php echo ($rgEx01['kg'] != 0) ? $rgEx01['kg'] : '-'; ?></td>
		<?php
		// Total Produksi tahun lalu
		$total_produksi01 =($data_table01['garuk_fleece']+$data_table01['garuk_ap']+$data_table01['peach']+$data_table01['airo']+$data_table01['pb_lain']+$data_table01['ap_lain']+$data_table01['polish']+ $data_table01['wet_sue']+$data_table01['bantu']);
		  ?>
		<td align='center'><?php echo ($total_produksi01 != 0) ? $total_produksi01 : '-'; ?></td>
    </tr>
	  <tr>
		<td align="left">DESEMBER '<?php echo substr($tahun_sebelumnya, 2, 2); ?></td>
		<td align='center'><?php echo ($hari_kerja0['jml'] != 0) ? $hari_kerja0['jml'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['total_kk'] != 0) ? $data_table0['total_kk'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['garuk_fleece'] != 0) ? $data_table0['garuk_fleece'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['potong_bulu_fleece'] != 0) ? $data_table0['potong_bulu_fleece'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['garuk_ap'] != 0) ? $data_table0['garuk_ap'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['sisir_ap'] != 0) ? $data_table0['sisir_ap'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['pbulu_ap'] != 0) ? $data_table0['pbulu_ap'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['oven_ap'] != 0) ? $data_table0['oven_ap'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['peach'] != 0) ? $data_table0['peach'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['pb_peach'] != 0) ? $data_table0['pb_peach'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['airo'] != 0) ? $data_table0['airo'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['pb_lain'] != 0) ? $data_table0['pb_lain'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['ap_lain'] != 0) ? $data_table0['ap_lain'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['polish'] != 0) ? $data_table0['polish'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['wet_sue'] != 0) ? $data_table0['wet_sue'] : '-'; ?></td>
		<td align='center'><?php echo ($data_table0['bantu'] != 0) ? $data_table0['bantu'] : '-'; ?></td>
		<td align='center'><?php echo ($rgIn0['kg'] != 0) ? $rgIn0['kg'] : '-'; ?></td>
		<td align='center'><?php echo ($rgEx0['kg'] != 0) ? $rgEx0['kg'] : '-'; ?></td>
		 <?php 
		  // Total Produksi desember
		$total_produksi0 =($data_table0['garuk_fleece']+$data_table0['garuk_ap']+$data_table0['peach']+$data_table0['airo']+$data_table0['pb_lain']+$data_table0['ap_lain']+$data_table0['polish']+ $data_table0['wet_sue']+$data_table0['bantu']);
		  ?>

		<td align='center'><?php echo ($total_produksi0 != 0) ? $total_produksi0 : '-'; ?></td>
	  </tr>
	  <?php
                // Ambil semua bulan dari Januari sampai Desember di tahun tersebut
				$tanggal_ada_data = [];

				// Query untuk ambil semua bulan yang punya data
				$query = "SELECT DISTINCT MONTH(tgl_buat) AS bulan 
						  FROM db_brushing.tbl_produksi
						  WHERE YEAR(tgl_buat) = '{$tahun}'
						  ORDER BY bulan ASC";
				$result = sqlsrv_query($conb, $query);

				// Tandai bulan yang punya data
				while ($row = sqlsrv_fetch_array($result)) {
					$tanggal_ada_data[(int)$row['bulan']] = true;
				}

                // Loop dari awal sampai akhir bulan
                $interval = new DateInterval('P1D');
                $dateRange = new DatePeriod($startDate, $interval, $endDate->modify('+1 day'));
                // print_r($dateRange);

                // UNTUK TOTAL TABEL 1
                    $totalHariKerja = 0;
                    $totalJumlahKK = 0;
                    $total_garuk_fleece = 0;
                    $total_potong_bulu_fleece = 0;
                    $total_potong_bulu_peach_skin = 0;
                    $total_garuk_anti_pilling = 0;
                    $total_sisir_anti_pilling = 0;
                    $total_potong_bulu_anti_pilling = 0;
                    $total_oven_anti_pilling = 0;
                    $total_peach_skin = 0;
                    $total_airo = 0;
                    $total_potong_bulu_lain_lain = 0;
                    $total_anti_pilling_lain_lain = 0;
                    $total_polishing = 0;
                    $total_wet_sueding = 0;
                    $total_bantu_ncp = 0;
                    $total_total_produksi = 0;
                    

                // UNTUK TOTAL TABEL 1

                for ($bulan = 1; $bulan <= 12; $bulan++) {
					//$nama_bulan = strtoupper(DateTime::createFromFormat('!m Y', "$bulan $tahun")->format('F \'y')); // contoh: January
					$nama_bulan_indonesia = [
						1 => 'Januari',
						2 => 'Februari',
						3 => 'Maret',
						4 => 'April',
						5 => 'Mei',
						6 => 'Juni',
						7 => 'Juli',
						8 => 'Agustus',
						9 => 'September',
						10 => 'Oktober',
						11 => 'November',
						12 => 'Desember'
					];

					$bulan_int = intval($bulan);
					$nama_bulan = strtoupper($nama_bulan_indonesia[$bulan_int] . " '" . substr($tahun, -2));
					$ada_data = isset($tanggal_ada_data[$bulan]);
					
                    // Default qty kosong
                    $qty = '-';

                    echo "<tr>";
                    echo "<td  align='left'>{$nama_bulan}</td>";
                    // Jika tanggal di loop masih <= tanggal input, jalankan query
						$hari_kerja=0;
                        if ($bulan <= $bln) {
						// Buat DateTime awal bulan
						$startDate = new DateTime("$tahun-$bulan-01");

						// Format jam cutoff awal dan akhir
						$start_time = $startDate->format('Y-m-d') . " 23:01:00";
						$end_time   = (clone $startDate)->modify('last day of this month')->format('Y-m-d') . " 23:00:00";
							
                        $query_table1="SELECT
                                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' THEN qty ELSE 0 END) AS garuk_ap,
                                            -- GROUP_CONCAT(DISTINCT CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' THEN TRIM(nodemand) ELSE NULL END) AS demand_garuk_ap,
                                            SUM(CASE WHEN proses in('GARUK FLEECE (Normal)', 'GARUK SLIGHT BRUSH (Normal)', 'GARUK SLIGHTLY BRUS (Normal)', 'GARUK GREIGE (Normal)', 'GARUK BANTU - DYG (Bantu)', 'GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS garuk_fleece,
                                            SUM(CASE WHEN proses IN ('POTONG BULU FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK SLIGHT BRUSH (Normal)','GARUK SLIGHTLY BRUS (Normal)') THEN qty ELSE 0 END) AS potong_bulu_fleece,
                                            SUM(CASE WHEN proses IN ('SISIR ANTI PILLING (Normal)','SISIR BANTU (FIN) (Bantu)','SISIR LAIN-LAIN (Bantu)','GARUK ANTI PILLING (Normal)') THEN qty ELSE 0 END) AS sisir_ap,
                                            SUM(CASE WHEN proses IN ('POTONG BULU ANTI PILLING (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','ANTI PILLING (Khusus)','ANTI PILLING NORMAL (Normal)','ANTI PILLING (Normal)','ANTI PILLING BIASA (Normal)') THEN qty ELSE 0 END) AS pbulu_ap,
                                            SUM(CASE WHEN proses IN ('ANTI PILLING (Khusus)','ANTI PILLING (Normal)','ANTI PILLING NORMAL (Normal)','ANTI PILLING BIASA (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','POTONG BULU ANTI PILLING (Normal)','PEACH SKIN (Normal)','POTONG BULU PEACH SKIN (Normal)','POTONG BULU FLEECE (Normal)') THEN qty ELSE 0 END) AS oven_ap,
                                            SUM(CASE WHEN proses IN ('PEACH SKIN (Normal)','PEACHSKIN GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)','PEACH SKIN BANTU - FIN (Bantu)','POTONG BULU PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS peach,
                                            SUM(CASE WHEN proses IN ('POTONG BULU PEACH SKIN (Normal)','PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS pb_peach,
                                            SUM(CASE WHEN proses = 'AIRO (Normal)' THEN qty ELSE 0 END) AS airo,
                                            SUM(CASE WHEN proses IN ('Potong Bulu (Bantu)','POTONG BULU 07 (Bantu)','POTONG BULU LAIN-LAIN (Bantu)','POTONG BULU LAIN-LAIN (Khusus)','POTONG BULU BACK BANTU-DYEING (Bantu)','POTONG BULU BACK BANTU-FIN (Bantu)','POTONG BULU BACK TAS BANTU (Bantu)','POTONG BULU FACE BANTU-DYEING (Bantu)','POTONG BULU FACE BANTU-FIN (Bantu)','POTONG BULU FACE BANTU-TAS (Bantu)','POTONG BULU FACE TAS BANTU (Bantu)','POTONG BULU GREIGE (Bantu)','POTONG BULU GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)',
                                                                     'PEACH SKIN BANTU - FIN (Bantu)','GARUK BANTU - DYG (Bantu)','GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS pb_lain,
                                            SUM(CASE WHEN proses IN ('ANTI PILLING BANTU - DYE (Bantu)',
                                                                        'ANTI PILLING BANTU - FIN (Bantu)',
                                                                        'ANTI PILLING BANTU - QC (Bantu)',
                                                                        'ANTI PILLING BANTU - TAS (Bantu)',
                                                                        'ANTI PILLING BANTU-DYEING (Bantu)',
                                                                        'ANTI PILLING BANTU-FINISHING (Bantu)',
                                                                        'ANTI PILLING LAIN-LAIN (Bantu)',
                                                                        'ANTI PILLING LAIN-LAIN (Khusus)',
                                                                        'PEACH BANTU TAS (Bantu)',
                                                                        'PEACH SKIN (Bantu)',
                                                                        'PEACH SKIN BANTU - DYE (Bantu)',
                                                                        'PEACH SKIN BANTU - FIN (Bantu)',
                                                                        'GARUK BANTU - DYG (Bantu)',
                                                                        'GARUK BANTU - FIN (Bantu)',
                                                                        'GARUK GREIGE (Bantu)',
                                                                        'GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS ap_lain,
                                            SUM(CASE WHEN proses = 'POLISHING (Normal)' THEN qty ELSE 0 END) AS polish,
                                            SUM(CASE WHEN (proses LIKE '%bantu%' OR proses LIKE '%NCP%') THEN qty ELSE 0 END) AS lain,
                                            SUM(CASE WHEN proses IN ('WET SUEDING (Normal)','WET SUEDING FINISHED BACK (Normal)',
                                                                    'WET SUEDING FINISHED FACE (Normal)',
                                                                    'WET SUEDING GREIGE BACK (Normal)',
                                                                    'WET SUEDING GREIGE FACE (Normal)') THEN qty ELSE 0 END) AS wet_sue,
                                            SUM(CASE WHEN proses IN ('NCP - Tunggu Perbaikan (Normal)',
                                                                    'GARUK FLEECE ULANG-DYE (Ulang)',
                                                                    'GARUK FLEECE ULANG-FIN (Ulang)',
                                                                    'GARUK FLEECE ULANG-BRS (Ulang)',
                                                                    'GARUK FLEECE ULANG-CQA (Ulang)',
                                                                    'GARUK ANTI PILLING-FIN (Ulang)',
                                                                    'GARUK ANTI PILLING-DYE (Ulang)',
                                                                    'GARUK ANTI PILLING-BRS (Ulang)',
                                                                    'GARUK ANTI PILLING-CQA (Ulang)',
                                                                    'PEACHSKIN GREIGE (Ulang)',
                                                                    'PEACHSKIN ULANG-DYE (Ulang)',
                                                                    'PEACHSKIN ULANG-BRS (Ulang)',
                                                                    'PEACHSKIN ULANG-CQA (Ulang)',
                                                                    'PEACHSKIN ULANG-FIN (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-FIN (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-DYE (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-BRS (Ulang)',
                                                                    'POTONG BULU LAIN-LAIN KHUSUS-CQA (Ulang)',
                                                                    'ANTI PILLING LAIN-LAIN KHUSUS-FIN (Ulang)',
                                                                    'ANTI PILLING LAIN-LAIN KHUSUS-DYE (Ulang)',
																	'ANTI PILLING LAIN-LAIN KHUSUS-BRS (Ulang)',
																	'ANTI PILLING LAIN-LAIN KHUSUS-CQA (Ulang)',
																	'ANTI PILLING LAIN-LAIN-CQA (Ulang)') THEN qty ELSE 0 END) AS bantu,
                                            -- GROUP_CONCAT(DISTINCT CASE 
                                            --     WHEN proses = 'GARUK FLEECE (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                            --     THEN TRIM(nodemand) 
                                            --     ELSE NULL 
                                            -- END) AS demand_garuk_fleece,
                                            SUM(CASE WHEN proses LIKE '%(Bantu)%' THEN qty ELSE 0 END) AS produksi_ulang,
                                            -- GROUP_CONCAT(DISTINCT CASE 
                                            --     WHEN proses LIKE '%(Bantu)%' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                            --     THEN TRIM(nodemand) 
                                            --     ELSE NULL 
                                            -- END) AS demand_produksi_ulang,
                                            -- GROUP_CONCAT(DISTINCT CASE 
                                            --     WHEN proses = 'PEACH SKIN (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                            --     THEN TRIM(nodemand) 
                                            --     ELSE NULL 
                                            -- END) AS demand_peach_skin,
                                            count(distinct nodemand) as total_kk
                                        FROM
                                            db_brushing.tbl_produksi tp
                                        WHERE 
                                        tp.tgl_buat between'$start_time' and '$end_time' ";
                        $stmt_qry = sqlsrv_query($conb, $query_table1);
                        $data_table1 = sqlsrv_fetch_array($stmt_qry);
							
							$hari_kerja_query = "SELECT 
                                    CONVERT(VARCHAR(10), tgl_buat, 23) AS tanggal
                                FROM 
                                    db_brushing.tbl_produksi
                                WHERE 
                                    tgl_buat >= '$start_time'
                                    AND tgl_buat < '$end_time'
                                GROUP BY 
                                    CONVERT(VARCHAR(10), tgl_buat, 23)
                                ORDER BY 
                                    tanggal
							";

							$hari_kerja_result = sqlsrv_query($conb, $hari_kerja_query);
							// Inisialisasi total jumlah produksi per hari
							$hari_kerja = 0;

							while ($rh_kerja = sqlsrv_fetch_array($hari_kerja_result)) {
								$hari_kerja += 1; // menjumlahkan semua jml dari tiap hari
							}
						
						//GANTI KAIN
						//Internal	
						$sqlgk_in=mysqli_query($cona," SELECT
							SUM(qty_order) as kg
						FROM
							tbl_gantikain tb
						WHERE
						MONTH(tgl_update) = '$bulan' AND YEAR(tgl_update) = '$tahun'
						and (t_jawab='BRS' or t_jawab1='BRS' or t_jawab2='BRS' or t_jawab3='BRS' or t_jawab4='BRS' )
						and kategori = '0'
						");
						$rgIn=mysqli_fetch_array($sqlgk_in);
						//Eksternal	
						$sqlgk_ex=mysqli_query($cona," SELECT
							SUM(qty_order) as kg
						FROM
							tbl_gantikain tb
						WHERE
						MONTH(tgl_update) = '$bulan' AND YEAR(tgl_update) = '$tahun'
						and (t_jawab='BRS' or t_jawab1='BRS' or t_jawab2='BRS' or t_jawab3='BRS' or t_jawab4='BRS' )
						and kategori = '1'
						");
						$rgEx=mysqli_fetch_array($sqlgk_ex);	
						
                            echo "<td align='center' >{$hari_kerja}</td>";
                            $totalHariKerja += $hari_kerja; // Tambahkan ke total hari kerja
							if($bulan==$_GET['bulan']){
								$hariKrjBln = $hari_kerja*24*60;
							}else{ 
								$hariKrjBln = 0;
							}
							
                        // Hari kerja

                        // Jumlah KK
//                            $total_kk = $data_table1['total_kk'];
							$total_kk = $data_table1['total_kk'];
                            $display_kk = ($total_kk != 0) ? $total_kk : '-';
                            echo "<td align='center' >$display_kk</td>";

                            // Hanya tambahkan angka ke total jika nilainya tidak nol
                            if ($total_kk != 0) {
                                $totalJumlahKK += $total_kk;
                            }
                        // Jumlah KK

                        // Garuk Fleece
                        if($bulan==$_GET['bulan']){
                                $qty_fleece = $data_table1['garuk_fleece'] - ($row_tbl2['brs_fleece_ulang'] + $row_tbl2['fin_fleece_ulang'] + $row_tbl2['dye_fleece_ulang']); 
                                $display_fleece = ($qty_fleece != 0) ? $qty_fleece : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' >{$display_fleece}</td>";
                                if ($qty_fleece != 0) {
                                $total_garuk_fleece += $qty_fleece;
                            }
                        }else{
                                $qty_fleece = $data_table1['garuk_fleece'];
                                $display_fleece = ($qty_fleece != 0) ? $qty_fleece : '-';
                                echo "<td align='center' >{$display_fleece}</td>";
                                if ($qty_fleece != 0) {
                                $total_garuk_fleece += $qty_fleece;
                            }
						}
                        // Garuk Fleece

                        // Potong Bulu Fleece
                            $qty_pot_bulu = $data_table1['potong_bulu_fleece'];
                            $display_bulu = ($qty_pot_bulu != 0) ? $qty_pot_bulu : '-';
                            echo "<td align='center' >{$display_bulu}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($qty_pot_bulu != 0) {
                                $total_potong_bulu_fleece += $qty_pot_bulu;
                            }
                        // Potong Bulu Fleece

                        // Proses Garuk Anti Pilling
                        if($bulan==$_GET['bulan']){
                                $qty_ap = $data_table1['garuk_ap'] - ($row_tbl2['brs_ap_ulang'] + $row_tbl2['fin_ap_ulang'] + $row_tbl2['dye_ap_ulang']); 
                                $display_ap = ($qty_ap != 0) ? $qty_ap : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' >{$display_ap}</td>";
                                if ($qty_ap != 0) {
                                $total_garuk_anti_pilling += $qty_ap;
                            }
                        }else{
							$qty_ap = $data_table1['garuk_ap'] ; 
                                $display_ap = ($qty_ap != 0) ? $qty_ap : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' >{$display_ap}</td>";
                                if ($qty_ap != 0) {

                                $total_garuk_anti_pilling += $qty_ap;
                            }                        
						}
                        // Proses Sisir Anti Pilling
                            $sisir_anti_pilling_row = $data_table1['sisir_ap'];
                            $display_sisir_ap = ($sisir_anti_pilling_row != 0) ? $sisir_anti_pilling_row : '-';
                            echo "<td align='center' >{$display_sisir_ap}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($sisir_anti_pilling_row != 0) {
                                $total_sisir_anti_pilling += $sisir_anti_pilling_row;
                            }                            

                        // Proses Potong Bulu Anti Pilling
                            $potong_bulu_anti_pilling_row = $data_table1['pbulu_ap'];
                            $display_pb_ap = ($potong_bulu_anti_pilling_row != 0) ? $potong_bulu_anti_pilling_row : '-';
                            echo "<td align='center' >{$display_pb_ap}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($potong_bulu_anti_pilling_row != 0) {
                                $total_potong_bulu_anti_pilling += $potong_bulu_anti_pilling_row; 
                            }                            

                        // Oven Anti Pilling
                            $oven_anti_pilling_row = $data_table1['oven_ap'];
                            $display_oven = ($oven_anti_pilling_row != 0) ? $oven_anti_pilling_row : '-';
                            echo "<td align='center' >{$display_oven}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($oven_anti_pilling_row != 0) {
                                $total_oven_anti_pilling += $oven_anti_pilling_row;
                            }                            

                        // Proses Peach Skin
                        if($bulan==$_GET['bulan']){
                                $peach_skin_row = $data_table1['peach'] - ($row_tbl2['brs_peach_ulang'] + $row_tbl2['fin_peach_ulang'] + $row_tbl2['dye_peach_ulang']); 
                                $display_peach = ($peach_skin_row != 0) ? $peach_skin_row : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center'>{$display_peach}</td>";
                                if ($peach_skin_row != 0) {
                                $total_peach_skin += $peach_skin_row;
                            	}
                         }else{
                                $peach_skin_row = $data_table1['peach'];
                                $display_peach = ($peach_skin_row != 0) ? $peach_skin_row : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' >{$display_peach}</td>";
                                if ($peach_skin_row != 0) {
                                $total_peach_skin += $peach_skin_row;
							}
						}
                        // Proses Peach Skin

                        // Potong Bulu Peach Skin
                            $potong_bulu_peach_skin_row = $data_table1['pb_peach'];
                            $display_bulu_peach = ($potong_bulu_peach_skin_row != 0) ? $potong_bulu_peach_skin_row : '-';
                            echo "<td align='center' >{$display_bulu_peach}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($potong_bulu_peach_skin_row != 0) {
                                $total_potong_bulu_peach_skin += $potong_bulu_peach_skin_row;
                            }
                            
                        // Potong Bulu Peach Skin

                        // AIRO                            
                                $airo_row = $data_table1['airo'];
                                $display_airo = ($airo_row != 0) ? $airo_row : '-';
                                echo "<td align='center' >{$display_airo}</td>";
                                if ($airo_row != 0) {
                                    $total_airo += $airo_row;
                                } 
                        // AIRO

                        // Potong Bulu Lain-Lain
                        if($bulan==$_GET['bulan']){
                                $potong_bulu_lain_lain_row = $data_table1['pb_lain'] - ($row_tbl2['brs_pb_ulang'] + $row_tbl2['fin_pb_ulang'] + $row_tbl2['dye_pb_ulang']); 
                                $display_pb = ($potong_bulu_lain_lain_row != 0) ? $potong_bulu_lain_lain_row : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center'>{$display_pb}</td>";
                                if ($potong_bulu_lain_lain_row != 0) {
                                $total_potong_bulu_lain_lain += $potong_bulu_lain_lain_row;   
                            	}
                         }else{
                                $potong_bulu_lain_lain_row = $data_table1['pb_lain'];
                                $display_pb = ($potong_bulu_lain_lain_row != 0) ? $potong_bulu_lain_lain_row : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' >{$display_pb}</td>";
                                if ($potong_bulu_lain_lain_row != 0) {
                                $total_potong_bulu_lain_lain += $potong_bulu_lain_lain_row;   
                            } 
						}
                        // Potong Bulu Lain-Lain

                        // Oven Anti Pilling Lain-Lain
                        if($bulan==$_GET['bulan']){
                                $anti_pilling_lain_lain_row = $data_table1['ap_lain'] - ($row_tbl2['brs_oven_ulang'] + $row_tbl2['fin_oven_ulang'] + $row_tbl2['dye_oven_ulang']); 
                                $display_oven_ap = ($anti_pilling_lain_lain_row != 0) ? $anti_pilling_lain_lain_row : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center'>{$display_oven_ap}</td>";
                                if ($anti_pilling_lain_lain_row != 0) {
                                $total_anti_pilling_lain_lain += $anti_pilling_lain_lain_row;
                            }
                         }else{
                                $anti_pilling_lain_lain_row = $data_table1['ap_lain'];
                                $display_oven_ap = ($anti_pilling_lain_lain_row != 0) ? $anti_pilling_lain_lain_row : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' >{$display_oven_ap}</td>";
                                if ($anti_pilling_lain_lain_row != 0) {
                                $total_anti_pilling_lain_lain += $anti_pilling_lain_lain_row;
                            }
						}
                        // Oven Anti Pilling Lain-Lain

                        // Polishing
                            $polishing_row = $data_table1['polish'];
                                $display_polish = ($polishing_row != 0) ? $polishing_row : '-';
                                echo "<td align='center' >{$display_polish}</td>";
                                if ($polishing_row != 0) {
                                    $total_polishing += $polishing_row;
                                }                               
                        // Polishing

                        // Wet Sueding
                             $wet_sueding_row = $data_table1['wet_sue'];
                                        $display_wet = ($wet_sueding_row != 0) ? $wet_sueding_row : '-';
                                        echo "<td align='center' >{$display_wet}</td>";
                                        if ($wet_sueding_row != 0) {
                                            $total_wet_sueding += $wet_sueding_row;
                                        } 
                        // Wet Sueding

                        // Bantu NCP
                            
                            $bantu_ncp_row = $data_table1['bantu'];
                                $display_ncp = ($bantu_ncp_row != 0) ? $bantu_ncp_row : '-';
                                echo "<td align='center' >{$display_ncp}</td>";
                                if ($bantu_ncp_row != 0) {
                                    $total_bantu_ncp += $bantu_ncp_row;
                                }   
                            
                        // Bantu NCP
						// TOTAL GANTI KAIN
							$display_gtIn = ($rgIn['kg'] > 0 ? htmlspecialchars($rgIn['kg']) : '-');
						echo "<td align='center'>{$display_gtIn}</td>";
							$total_gt_internal += $rgIn['kg']; 
							$display_gtEx = ($rgEx['kg'] > 0 ? htmlspecialchars($rgEx['kg']) : '-');
						echo "<td align='center'>{$display_gtEx}</td>";
							$total_gt_eksternal += $rgEx['kg'];
                        // Total Produksi
                            // $total_produksi =$bantu_ncp_row;
                            $total_produksi =($qty_fleece+$qty_ap+$peach_skin_row+$airo_row+$potong_bulu_lain_lain_row+$anti_pilling_lain_lain_row+$polishing_row+ $wet_sueding_row+$bantu_ncp_row);
                            // $total_produksi = $peach_skin_row + $qty;
                            $total_total_produksi += $total_produksi;
                            

                            echo "<td align='center'>" . ($total_produksi > 0 ? htmlspecialchars($total_produksi) : '-') . "</td>";
							
						if ($bulan == $bulan_sebelumnya) {
								$nilai_sebelumnyaFleece = $qty_fleece;
								$nilai_sebelumnyaPB = $qty_pot_bulu;
								$nilai_sebelumnyaAP = $qty_ap;
								$nilai_sebelumnyaSisir = $sisir_anti_pilling_row;
								$nilai_sebelumnyaPBap = $potong_bulu_anti_pilling_row;
								$nilai_sebelumnyaOVap = $oven_anti_pilling_row;
								$nilai_sebelumnyaPS = $peach_skin_row;
								$nilai_sebelumnyaPBpc = $potong_bulu_peach_skin_row;
								$nilai_sebelumnyaAIRO = $airo_row;
								$nilai_sebelumnyaPBlain = $potong_bulu_lain_lain_row;
								$nilai_sebelumnyaOVaplain = $anti_pilling_lain_lain_row;
								$nilai_sebelumnyaPOL = $polishing_row;
								$nilai_sebelumnyaWet = $wet_sueding_row;
								$nilai_sebelumnyaTotUL = $bantu_ncp_row;
								$nilai_sebelumnyaInt = $rgIn['kg'];
								$nilai_sebelumnyaEks = $rgEx['kg'];
							    $nilai_sebelumnyaproduksi = $total_produksi;
							}
							if ($bulan == $bulan_target) {
								$nilai_saat_iniFleece = $qty_fleece;
								$nilai_saat_iniPB = $qty_pot_bulu;
								$nilai_saat_iniAP = $qty_ap;
								$nilai_saat_iniSisir = $sisir_anti_pilling_row;
								$nilai_saat_iniPBap = $potong_bulu_anti_pilling_row;
								$nilai_saat_iniOVap = $oven_anti_pilling_row;
								$nilai_saat_iniPS = $peach_skin_row;
								$nilai_saat_iniPBpc = $potong_bulu_peach_skin_row;
								$nilai_saat_iniAIRO = $airo_row;
								$nilai_saat_iniPBlain = $potong_bulu_lain_lain_row;
								$nilai_saat_iniOVaplain = $anti_pilling_lain_lain_row;
								$nilai_saat_iniPOL = $polishing_row;
								$nilai_saat_iniWet = $wet_sueding_row;
								$nilai_saat_iniTotUL = $bantu_ncp_row;
								$nilai_saat_iniInt = $rgIn['kg'];
								$nilai_saat_iniEks = $rgEx['kg'];
								$nilai_saat_iniproduksi = $total_produksi;
								
							}
							
                    } else{
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
						echo "<td align='center' >-</td>";
                        echo "<td align='center' >-</td>";
                    }

                    echo "</tr>";
					$persentaseFleece = ($nilai_saat_iniFleece > 0) ? round((($nilai_saat_iniFleece - $nilai_sebelumnyaFleece) / $nilai_saat_iniFleece) * 100,2) : '0';
					$persentasePB = ($nilai_saat_iniPB > 0) ? round((($nilai_saat_iniPB - $nilai_sebelumnyaPB) / $nilai_saat_iniPB) * 100,2) : '0';
					$persentaseAP = ($nilai_saat_iniAP > 0) ? round((($nilai_saat_iniAP - $nilai_sebelumnyaAP) / $nilai_saat_iniAP) * 100,2) : '0';
					$persentaseSisir = ($nilai_saat_iniSisir > 0) ? round((($nilai_saat_iniSisir - $nilai_sebelumnyaSisir) / $nilai_saat_iniSisir) * 100,2) : '0';
					$persentasePBap = ($nilai_saat_iniPBap > 0) ? round((($nilai_saat_iniPBap - $nilai_sebelumnyaPBap) / $nilai_saat_iniPBap) * 100,2) : '0';
					$persentaseOVap = ($nilai_saat_iniOVap > 0) ? round((($nilai_saat_iniOVap - $nilai_sebelumnyaOVap) / $nilai_saat_iniOVap) * 100,2) : '0';
					$persentasePS = ($nilai_saat_iniPS > 0) ? round((($nilai_saat_iniPS - $nilai_sebelumnyaPS) / $nilai_saat_iniPS) * 100,2) : '0';
					$persentasePBpc = ($nilai_saat_iniPBpc > 0) ? round((($nilai_saat_iniPBpc - $nilai_sebelumnyaPBpc) / $nilai_saat_iniPBpc) * 100,2) : '0';
					$persentaseAIRO = ($nilai_saat_iniAIRO > 0) ? round((($nilai_saat_iniAIRO - $nilai_sebelumnyaAIRO) / $nilai_saat_iniAIRO) * 100,2) : '0';
					$persentasePBlain = ($nilai_saat_iniPBlain > 0) ? round((($nilai_saat_iniPBlain - $nilai_sebelumnyaPBlain) / $nilai_saat_iniPBlain) * 100,2) : '0';
					$persentaseOVaplain = ($nilai_saat_iniOVaplain > 0) ? round((($nilai_saat_iniOVaplain - $nilai_sebelumnyaOVaplain) / $nilai_saat_iniOVaplain) * 100,2) : '0';
					$persentasePOL = ($nilai_saat_iniPOL > 0) ? round((($nilai_saat_iniPOL - $nilai_sebelumnyaPOL) / $nilai_saat_iniPOL) * 100,2) : '0';
					$persentaseWet = ($nilai_saat_iniWet > 0) ? round((($nilai_saat_iniWet - $nilai_sebelumnyaWet) / $nilai_saat_iniWet) * 100,2) : '0';
					$persentaseTotUL = ($nilai_saat_iniTotUL > 0) ? round((($nilai_saat_iniTotUL - $nilai_sebelumnyaTotUL) / $nilai_saat_iniTotUL) * 100,2) : '0';
					$persentaseInt = ($nilai_saat_iniInt > 0) ? round((($nilai_saat_iniInt - $nilai_sebelumnyaInt) / $nilai_saat_iniInt) * 100,2) : '0';
					$persentaseEks = ($nilai_saat_iniEks > 0) ? round((($nilai_saat_iniEks - $nilai_sebelumnyaEks) / $nilai_saat_iniEks) * 100,2) : '0';
					$persentaseproduksi = ($nilai_saat_iniproduksi > 0) ? round((($nilai_saat_iniproduksi - $nilai_sebelumnyaproduksi) / $nilai_saat_iniproduksi) * 100,2) : '0';
                }
                ?>
  <td  align='center' colspan="3"><strong>&plusmn; %</strong></td>
                <?php
                // Tampilkan total di baris bawah
//                    echo "<td align='center' ><b>" . ($totalHariKerja ?: '-') . "</b></td>";
//                    echo "<td align='center' ><b>" . ($totalJumlahKK ?: '-') . "</b></td>";
                    echo "<td align='center' ><b>" . ($persentaseFleece ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentasePB ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentaseAP ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentaseSisir ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentasePBap ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentaseOVap ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentasePS ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentasePBpc ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentaseAIRO ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentasePBlain ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentaseOVaplain ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentasePOL ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentaseWet ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentaseTotUL ?: '0') . "%</b></td>";
	  				echo "<td align='center' ><b>" . ($persentaseInt ?: '0') . "%</b></td>";
	  				echo "<td align='center' ><b>" . ($persentaseEks ?: '0') . "%</b></td>";
                    echo "<td align='center' ><b>" . ($persentaseproduksi ?: '0') . "%</b></td>";
                ?>
</table>
<!-- End Table 1 -->
<!-- Tabel-2.php -->
<?php
						// Buat DateTime awal bulan
						$tahunUlang = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
						$bulanUlang = isset($_GET['bulan']) ? str_pad(intval($_GET['bulan']), 2, '0', STR_PAD_LEFT) : date('m');

						$startDateUlang = new DateTime("$tahunUlang-$bulanUlang-01");
						// Format jam cutoff awal dan akhir
						$start_timeUlang = $startDateUlang->format('Y-m-d'). " 23:01:00";
						$end_timeUlang   = (clone $startDateUlang)->modify('last day of this month')->format('Y-m-d'). " 23:00:00";
						$query_tbl2bln = "SELECT
                            SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_fleece_ulang,
                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_ap_ulang,
                            SUM(CASE WHEN proses IN('PEACHSKIN ULANG-BRS (Ulang)', 'PEACHSKIN GREIGE (Ulang)') THEN qty ELSE 0 END) AS brs_peach_ulang,
                            SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_pb_ulang,
                            SUM(CASE WHEN proses = 'ANTI PILLING LAIN-LAIN KHUSUS-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_oven_ulang,
                            SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_fleece_ulang,
                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_ap_ulang,
                            SUM(CASE WHEN proses = 'PEACHSKIN ULANG-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_peach_ulang,
                            SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_pb_ulang,
                            SUM(CASE WHEN proses = 'ANTI PILLING LAIN-LAIN KHUSUS-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_oven_ulang,
                            SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_fleece_ulang,
                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_ap_ulang,
                            SUM(CASE WHEN proses = 'PEACHSKIN ULANG-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_peach_ulang,
                            SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_pb_ulang,
                            SUM(CASE WHEN proses = 'ANTI PILLING LAIN-LAIN KHUSUS-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_oven_ulang,
                            SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_fleece_ulang,
                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_ap_ulang,
                            SUM(CASE WHEN proses = 'PEACHSKIN ULANG-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_peach_ulang,
                            SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_pb_ulang,
                            SUM(CASE WHEN proses = 'ANTI PILLING LAIN-LAIN KHUSUS-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_oven_ulang
                        FROM
                            db_brushing.tbl_produksi
                        WHERE
                            -- year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
							tgl_buat between '$start_timeUlang' and '$end_timeUlang' ";
						$stmt_tbl2bln = sqlsrv_query($conb, $query_tbl2bln);
						$row_tbl2bln = sqlsrv_fetch_array($stmt_tbl2bln);
						$cek_tbl2bln = sqlsrv_num_rows($stmt_tbl2bln);	  
?>			
<table width="100%" border="0">	
<tr>
  <td colspan="3" align="left"><strong> LAPORAN PROSES ULANG BULAN <?= $BlnSkrng; ?></strong></td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'>&nbsp;</td>
  <td align='center'><strong>TOTAL</strong></td>
</tr>
</table>	
<table width="100%" border="1">
<tr>
    <td colspan="3" align="left"><strong>BRUSHING NCP</strong></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>	  
    <td align='center'><?php $qty_ncp = $row_ncp['qty_ncp'];
                                echo ($qty_ncp!=0) ? $qty_ncp : '-';
                            ?></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><strong>BRUSHING ULANG</strong></td>
    <td colspan="-1" align="center" ><?php $brs_fleece_ulang = ($row_tbl2bln['brs_fleece_ulang']!=0) ? $row_tbl2bln['brs_fleece_ulang'] : '-';
                                echo $brs_fleece_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php 
                            $brs_ap_ulang = $row_tbl2bln['brs_ap_ulang'];
                                echo ($brs_ap_ulang!=0) ? $brs_ap_ulang : '-';
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $brs_peach_ulang = ($row_tbl2bln['brs_peach_ulang']!=0) ? $row_tbl2bln['brs_peach_ulang'] : '-';
                                echo $brs_peach_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $brs_pb_ulang = ($row_tbl2bln['brs_pb_ulang']!=0) ? $row_tbl2bln['brs_pb_ulang'] : '-';
                                echo $brs_pb_ulang;
                            ?></td>
    <td align="center" ><?php $brs_oven_ulang = ($row_tbl2bln['brs_oven_ulang']!=0) ? $row_tbl2bln['brs_oven_ulang'] : '-';
                                echo $brs_oven_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php 
                        $total_tbl2_brs = $row_tbl2bln['brs_fleece_ulang'] + $row_tbl2bln['brs_ap_ulang'] + $row_tbl2bln['brs_peach_ulang']+$row_tbl2bln['brs_pb_ulang']+$row_tbl2bln['brs_oven_ulang'];
                        echo $total_tbl2_brs > 0 ? $total_tbl2_brs : '-';?></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><strong>FINISHING ULANG</strong></td>
    <td colspan="-1" align="center" ><?php $fin_fleece_ulang = ($row_tbl2bln['fin_fleece_ulang']!=0) ? $row_tbl2bln['fin_fleece_ulang'] : '-';
                                echo $fin_fleece_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $fin_ap_ulang = ($row_tbl2bln['fin_ap_ulang']!=0) ? $row_tbl2bln['fin_ap_ulang'] : '-';
                                echo $fin_ap_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $fin_peach_ulang = ($row_tbl2bln['fin_peach_ulang']!=0) ? $row_tbl2bln['fin_peach_ulang'] : '-';
                                echo $fin_peach_ulang;
                            ?></td> 
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $fin_pb_ulang = ($row_tbl2bln['fin_pb_ulang']!=0) ? $row_tbl2bln['fin_pb_ulang'] : '-';
                                echo $fin_pb_ulang;
                            ?></td>
    <td align="center" ><?php $fin_oven_ulang = ($row_tbl2bln['fin_oven_ulang']!=0) ? $row_tbl2bln['fin_oven_ulang'] : '-';
                                echo $fin_oven_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php 
                            $total_tbl2_fin= $row_tbl2bln['fin_fleece_ulang']+$row_tbl2bln['fin_ap_ulang']+$row_tbl2bln['fin_peach_ulang']+$row_tbl2bln['fin_pb_ulang']+$row_tbl2bln['fin_oven_ulang'];
                            echo $total_tbl2_fin > 0 ? $total_tbl2_fin : '-';?></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><strong>DYEING ULANG</strong></td>
    <td colspan="-1" align="center" ><?php $dye_fleece_ulang = ($row_tbl2bln['dye_fleece_ulang']!=0) ? $row_tbl2bln['dye_fleece_ulang'] : '-';
                                echo $dye_fleece_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $dye_ap_ulang = ($row_tbl2bln['dye_ap_ulang']!=0) ? $row_tbl2bln['dye_ap_ulang'] : '-';
                                echo $dye_ap_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $dye_peach_ulang = ($row_tbl2bln['dye_peach_ulang']!=0) ? $row_tbl2bln['dye_peach_ulang'] : '-';
                                echo $dye_peach_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $dye_pb_ulang = ($row_tbl2bln['dye_pb_ulang']!=0) ? $row_tbl2bln['dye_pb_ulang'] : '-';
                                echo $dye_pb_ulang;
                            ?></td>
    <td align="center" ><?php $dye_oven_ulang = ($row_tbl2bln['dye_oven_ulang']!=0) ? $row_tbl2bln['dye_oven_ulang'] : '-';
                                echo $dye_oven_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php 
                            $total_tbl2_dye= $row_tbl2bln['dye_fleece_ulang']+$row_tbl2bln['dye_ap_ulang']+$row_tbl2bln['dye_peach_ulang']+$row_tbl2bln['dye_pb_ulang']+$row_tbl2bln['dye_oven_ulang'];
                            echo $total_tbl2_dye > 0 ? $total_tbl2_dye : '-';?></td>
  </tr>
  <tr>
	    <td colspan="3" align="center"><strong>TOTAL</strong></td>
	    <td colspan="-1" align="center" ><?php
                            $total_column1 =
                                ($row_tbl2bln['dye_fleece_ulang']+$row_tbl2bln['fin_fleece_ulang']+$row_tbl2bln['brs_fleece_ulang'] );
                            echo htmlspecialchars($total_column1 > 0 ? $total_column1 : '-');
                        ?></td>
	    <td align="center" ><?php
                            echo '-';
                        ?></td>
	    <td align="center" ><?php
                            $total_column3 = ($row_tbl2bln['dye_ap_ulang']+$row_tbl2bln['fin_ap_ulang']+$row_tbl2bln['brs_ap_ulang'] );
                            echo htmlspecialchars($total_column3 > 0 ? $total_column3 : '-');
                            ?></td>
	    <td align="center" ><?php
                            echo '-';
                        ?></td>
	    <td align="center" ><?php
                            echo '-';
                        ?></td>
	    <td align="center" ><?= '-' ?></td>
	    <td align="center" ><?php
                            $total_column7 = ($row_tbl2bln['dye_peach_ulang']+$row_tbl2bln['fin_peach_ulang']+$row_tbl2bln['brs_peach_ulang'] );
                            echo htmlspecialchars($total_column7 > 0 ? $total_column7 : '-');
                        ?></td>
	    <td align="center" ><?php
                            echo '-';
                        ?></td>
	    <td align="center" ><?php
                            echo '-';
                        ?></td>
	    <td align="center" ><?php
                            $total_column10 = ($row_tbl2bln['dye_pb_ulang']+$row_tbl2bln['fin_pb_ulang']+$row_tbl2bln['brs_pb_ulang'] );
                            echo htmlspecialchars($total_column10 > 0 ? $total_column10 : '-');
                        ?></td>
	    <td align="center" ><?php
                            $total_column11 = ($row_tbl2bln['dye_oven_ulang']+$row_tbl2bln['fin_oven_ulang']+$row_tbl2bln['brs_oven_ulang'] );
                            echo htmlspecialchars($total_column11 > 0 ? $total_column11 : '-');
                        ?></td>
	    <td align="center" ><?php
                           echo '-'
                        ?></td>
	    <td align='center'><?php
//                            $grand_total = $total_tbl2_cqa+$total_tbl2_dye+$total_tbl2_fin+$total_tbl2_brs+$qty_ncp;
                            $grand_total = $total_tbl2_dye+$total_tbl2_fin+$total_tbl2_brs+$qty_ncp;
                            echo htmlspecialchars($grand_total > 0 ? $grand_total : '-');
                            ?></td>
    </tr> 
</table>
<em><u>Ket : Brushing NCP hanya keterangan, tidak masuk hitungan total proses ulang.</u></em><br>	
<!-- End Table 2 -->
<!-- Tabel-3.php -->
<strong>JAM KERJA MESIN BULAN <?= $BlnSkrng; ?></strong><br>	
<strong>DATA STOPAGE MESIN DEPARTEMEN BRUSHING BULAN <?= $BlnSkrng; ?></strong>
<table border="0" style="width: 11in;">	
	<tr>
	<td width="70%" align="left" valign="top" colspan="13">

<table border="1"  width="100%">			
            <tr>
                <td align="center"><strong>Mesin</strong></td>
                <td align="center"><strong>No</strong></td>
                <td align="center"><strong>LM</strong></td>
                <td align="center">%</td>
                <td align="center"><strong>KM</strong></td>
                <td align="center">%</td>
                <td align="center"><strong>PT</strong></td>
                <td align="center">%</td>
                <td align="center"><strong>KO</strong></td>
                <td align="center">%</td>
                <td align="center"><strong>AP</strong></td>
                <td align="center">%</td>
                <td align="center"><strong>PA</strong></td>
                <td align="center">%</td>
                <td align="center"><strong>PM</strong></td>
                <td align="center">%</td>
                <td align="center"><strong>GT</strong></td>
                <td align="center">%</td>
                <td align="center"><strong>TG</strong></td>
                <td align="center">%</td>
                <td align="center"><strong>Total</strong></td>
                <td align="center">%</td>                
            </tr>
            <tbody>
                <?php
                    $tglInput_tbl3 = $_GET['awal'];

                    $tgl_tbl3 = new DateTime($tglInput_tbl3);
                    $tglSebelumnya_tbl3 = clone $tgl_tbl3;
                    $tglSebelumnya_tbl3->modify('-1 day');

                    $tanggalAwal_tbl3   = $tglSebelumnya_tbl3->format('Y-m-d');
                    $tanggalAkhir_tbl3  = $tgl_tbl3->format('Y-m-d');
                ?>
            <!-- Untuk Kolom Garuk -->
                <?php
                $query_garuk9 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_A_TG,                                    
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_A_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_B_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_B_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_C_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_C_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_D_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_D_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_E_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_E_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_F_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_F_TG
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_garuk9    = mysqli_query($cona,$query_garuk9);
                            $tg_g             = mysqli_fetch_assoc($stmt_garuk9);
                $query_garuk8 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_A_GT,                                    
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_A_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_B_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_B_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_C_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_C_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_D_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_D_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_E_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_E_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_F_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_F_GT
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_garuk8    = mysqli_query($cona,$query_garuk8);
                            $gt_g             = mysqli_fetch_assoc($stmt_garuk8);
                $query_garuk7 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_A_PM,                                    
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_A_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_B_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_B_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_C_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_C_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_D_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_D_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_E_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_E_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_F_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_F_PM
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_garuk7    = mysqli_query($cona,$query_garuk7);
                            $pm_g             = mysqli_fetch_assoc($stmt_garuk7);
                $query_garuk6 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_A_PA,                                    
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_A_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_B_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_B_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_C_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_C_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_D_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_D_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_E_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_E_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_F_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_F_PA
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_garuk6    = mysqli_query($cona,$query_garuk6);
                            $pa_g             = mysqli_fetch_assoc($stmt_garuk6);
                $query_garuk5 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_A_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_A_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_B_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_B_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_C_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_C_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_D_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_D_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_E_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_E_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_F_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_F_AP
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_garuk5    = mysqli_query($cona,$query_garuk5);
                            $ap_g             = mysqli_fetch_assoc($stmt_garuk5);
                $query_garuk4 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_A_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_A_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_B_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_B_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_C_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_C_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_D_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_D_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_E_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_E_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_F_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_F_KO
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_garuk4    = mysqli_query($cona,$query_garuk4);
                            $ko_g             = mysqli_fetch_assoc($stmt_garuk4);
                $query_garuk3 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_A_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_A_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_B_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_B_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_C_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_C_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_D_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_D_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_E_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_E_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_F_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_F_PT
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_garuk3    = mysqli_query($cona,$query_garuk3);
                            $pt_g             = mysqli_fetch_assoc($stmt_garuk3);
                $query_garuk2 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_A_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%A%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_A_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_B_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%B%' 
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_B_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_C_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%C%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_C_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_D_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%D%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_D_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_E_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%E%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_E_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_garuk_F_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_garuk_F_KM
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_garuk2    = mysqli_query($cona,$query_garuk2);
                            $km_g             = mysqli_fetch_assoc($stmt_garuk2);
                    $query_garuk1 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%A%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_A_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%A%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_A_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%B%' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_B_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%B%' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_B_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%C%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_C_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%C%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_C_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%D%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_D_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%D%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_D_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%E%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_E_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%E%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_E_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%F%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_F_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%F%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_F_LM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_garuk1    = mysqli_query($cona,$query_garuk1);
                                    $lm_g             = mysqli_fetch_assoc($stmt_garuk1);
                            // Total Garuk
                    $query_mesin_garuk = "SELECT
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%A%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_A,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%A%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_A,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%B%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_B,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%B%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_B,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%C%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_C,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%C%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_C,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%D%' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_D,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%D%' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_D,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%E%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_E,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%E%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_E,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%F%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_garuk_F,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                                    AND mesin like '%F%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_garuk_F
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_garuk= mysqli_query($cona,$query_mesin_garuk);
                        $sum_mesin_garuk= mysqli_fetch_assoc($stmt_mesin_garuk);
                    ?>
                <!-- Mesin A -->
                <tr>
                    <td rowspan="6" align="left"><strong>GARUK</strong></td>
                    <td align="center">A</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_A_LM'] != 0 || $lm_g['menit_garuk_A_LM'] != 0) {echo str_pad($lm_g['jam_garuk_A_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_A_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_g['jam_garuk_A_LM'] != 0 || $lm_g['menit_garuk_A_LM'] != 0) { 
						echo round(((($lm_g['jam_garuk_A_LM']*60)+$lm_g['menit_garuk_A_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?> %</td>
                    <td align="center"><?php if ($km_g['jam_garuk_A_KM'] != 0 || $km_g['menit_garuk_A_KM'] != 0) {echo str_pad($km_g['jam_garuk_A_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_A_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_A_KM'] != 0 || $km_g['menit_garuk_A_KM'] != 0) { 
						echo round(((($km_g['jam_garuk_A_KM']*60)+$km_g['menit_garuk_A_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pt_g['jam_garuk_A_PT'] != 0 || $pt_g['menit_garuk_A_PT'] != 0) {echo str_pad($pt_g['jam_garuk_A_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_A_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_A_PT'] != 0 || $pt_g['menit_garuk_A_PT'] != 0) { 
						echo round(((($pt_g['jam_garuk_A_PT']*60)+$pt_g['menit_garuk_A_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ko_g['jam_garuk_A_KO'] != 0 || $ko_g['menit_garuk_A_KO'] != 0) {echo str_pad($ko_g['jam_garuk_A_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_A_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_A_KO'] != 0 || $ko_g['menit_garuk_A_KO'] != 0) { 
						echo round(((($ko_g['jam_garuk_A_KO']*60)+$ko_g['menit_garuk_A_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ap_g['jam_garuk_A_AP'] != 0 || $ap_g['menit_garuk_A_AP'] != 0) {echo str_pad($ap_g['jam_garuk_A_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_A_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_A_AP'] != 0 || $ap_g['menit_garuk_A_AP'] != 0) { 
						echo round(((($ap_g['jam_garuk_A_AP']*60)+$ap_g['menit_garuk_A_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pa_g['jam_garuk_A_PA'] != 0 || $pa_g['menit_garuk_A_PA'] != 0) {echo str_pad($pa_g['jam_garuk_A_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_A_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_A_PA'] != 0 || $pa_g['menit_garuk_A_PA'] != 0) { 
						echo round(((($pa_g['jam_garuk_A_PA']*60)+$pa_g['menit_garuk_A_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pm_g['jam_garuk_A_PM'] != 0 || $pm_g['menit_garuk_A_PM'] != 0) {echo str_pad($pm_g['jam_garuk_A_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_A_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_A_PM'] != 0 || $pm_g['menit_garuk_A_PM'] != 0) { 
						echo round(((($pm_g['jam_garuk_A_PM']*60)+$pm_g['menit_garuk_A_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($gt_g['jam_garuk_A_GT'] != 0 || $gt_g['menit_garuk_A_GT'] != 0) {echo str_pad($gt_g['jam_garuk_A_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_A_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_A_GT'] != 0 || $gt_g['menit_garuk_A_GT'] != 0) { 
						echo round(((($gt_g['jam_garuk_A_GT']*60)+$gt_g['menit_garuk_A_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($tg_g['jam_garuk_A_TG'] != 0 || $tg_g['menit_garuk_A_TG'] != 0) {echo str_pad($tg_g['jam_garuk_A_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_A_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_A_TG'] != 0 || $tg_g['menit_garuk_A_TG'] != 0) { 
						echo round(((($tg_g['jam_garuk_A_TG']*60)+$tg_g['menit_garuk_A_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_A'] != 0 || $sum_mesin_garuk['menit_garuk_A'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_A'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_A'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_A'] != 0 || $sum_mesin_garuk['menit_garuk_A'] != 0) { 
						echo round(((($sum_mesin_garuk['jam_garuk_A']*60)+$sum_mesin_garuk['menit_garuk_A'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                </tr>
                <!-- Mesin B -->
                <tr>
                    <td align="center">B</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_B_LM'] != 0 || $lm_g['menit_garuk_B_LM'] != 0) {echo str_pad($lm_g['jam_garuk_B_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_B_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_g['jam_garuk_B_LM'] != 0 || $lm_g['menit_garuk_B_LM'] != 0) { 
						echo round(((($lm_g['jam_garuk_B_LM']*60)+$lm_g['menit_garuk_B_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_g['jam_garuk_B_KM'] != 0 || $km_g['menit_garuk_B_KM'] != 0) {echo str_pad($km_g['jam_garuk_B_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_B_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_B_KM'] != 0 || $km_g['menit_garuk_B_KM'] != 0) { 
						echo round(((($km_g['jam_garuk_B_KM']*60)+$km_g['menit_garuk_B_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_g['jam_garuk_B_PT'] != 0 || $pt_g['menit_garuk_B_PT'] != 0) {echo str_pad($pt_g['jam_garuk_B_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_B_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_B_PT'] != 0 || $pt_g['menit_garuk_B_PT'] != 0) { 
						echo round(((($pt_g['jam_garuk_B_PT']*60)+$pt_g['menit_garuk_B_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_g['jam_garuk_B_KO'] != 0 || $ko_g['menit_garuk_B_KO'] != 0) {echo str_pad($ko_g['jam_garuk_B_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_B_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_B_KO'] != 0 || $ko_g['menit_garuk_B_KO'] != 0) { 
						echo round(((($ko_g['jam_garuk_B_KO']*60)+$ko_g['menit_garuk_B_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_g['jam_garuk_B_AP'] != 0 || $ap_g['menit_garuk_B_AP'] != 0) {echo str_pad($ap_g['jam_garuk_B_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_B_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_B_AP'] != 0 || $ap_g['menit_garuk_B_AP'] != 0) { 
						echo round(((($ap_g['jam_garuk_B_AP']*60)+$ap_g['menit_garuk_B_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_g['jam_garuk_B_PA'] != 0 || $pa_g['menit_garuk_B_PA'] != 0) {echo str_pad($pa_g['jam_garuk_B_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_B_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_B_PA'] != 0 || $pa_g['menit_garuk_B_PA'] != 0) { 
						echo round(((($pa_g['jam_garuk_B_PA']*60)+$pa_g['menit_garuk_B_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_g['jam_garuk_B_PM'] != 0 || $pm_g['menit_garuk_B_PM'] != 0) {echo str_pad($pm_g['jam_garuk_B_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_B_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_B_PM'] != 0 || $pm_g['menit_garuk_B_PM'] != 0) { 
						echo round(((($pm_g['jam_garuk_B_PM']*60)+$pm_g['menit_garuk_B_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_g['jam_garuk_B_GT'] != 0 || $gt_g['menit_garuk_B_GT'] != 0) {echo str_pad($gt_g['jam_garuk_B_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_B_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_B_GT'] != 0 || $gt_g['menit_garuk_B_GT'] != 0) { 
						echo round(((($gt_g['jam_garuk_B_GT']*60)+$gt_g['menit_garuk_B_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_g['jam_garuk_B_TG'] != 0 || $tg_g['menit_garuk_B_TG'] != 0) {echo str_pad($tg_g['jam_garuk_B_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_B_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_B_TG'] != 0 || $tg_g['menit_garuk_B_TG'] != 0) { 
						echo round(((($tg_g['jam_garuk_B_TG']*60)+$tg_g['menit_garuk_B_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_B'] != 0 || $sum_mesin_garuk['menit_garuk_B'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_B'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_B'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_B'] != 0 || $sum_mesin_garuk['menit_garuk_B'] != 0) { 
						echo round(((($sum_mesin_garuk['jam_garuk_B']*60)+$sum_mesin_garuk['menit_garuk_B'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Mesin C -->
                <tr>
                    <td align="center">C</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_C_LM'] != 0 || $lm_g['menit_garuk_C_LM'] != 0) {echo str_pad($lm_g['jam_garuk_C_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_C_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_g['jam_garuk_C_LM'] != 0 || $lm_g['menit_garuk_C_LM'] != 0) { 
						echo round(((($lm_g['jam_garuk_C_LM']*60)+$lm_g['menit_garuk_C_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_g['jam_garuk_C_KM'] != 0 || $km_g['menit_garuk_C_KM'] != 0) {echo str_pad($km_g['jam_garuk_C_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_C_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_C_KM'] != 0 || $km_g['menit_garuk_C_KM'] != 0) { 
						echo round(((($km_g['jam_garuk_C_KM']*60)+$km_g['menit_garuk_C_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_g['jam_garuk_C_PT'] != 0 || $pt_g['menit_garuk_C_PT'] != 0) {echo str_pad($pt_g['jam_garuk_C_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_C_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_C_PT'] != 0 || $pt_g['menit_garuk_C_PT'] != 0) { 
						echo round(((($pt_g['jam_garuk_C_PT']*60)+$pt_g['menit_garuk_C_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_g['jam_garuk_C_KO'] != 0 || $ko_g['menit_garuk_C_KO'] != 0) {echo str_pad($ko_g['jam_garuk_C_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_C_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_C_KO'] != 0 || $ko_g['menit_garuk_C_KO'] != 0) { 
						echo round(((($ko_g['jam_garuk_C_KO']*60)+$ko_g['menit_garuk_C_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_g['jam_garuk_C_AP'] != 0 || $ap_g['menit_garuk_C_AP'] != 0) {echo str_pad($ap_g['jam_garuk_C_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_C_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_C_AP'] != 0 || $ap_g['menit_garuk_C_AP'] != 0) { 
						echo round(((($ap_g['jam_garuk_C_AP']*60)+$ap_g['menit_garuk_C_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_g['jam_garuk_C_PA'] != 0 || $pa_g['menit_garuk_C_PA'] != 0) {echo str_pad($pa_g['jam_garuk_C_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_C_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_C_PA'] != 0 || $pa_g['menit_garuk_C_PA'] != 0) { 
						echo round(((($pa_g['jam_garuk_C_PA']*60)+$pa_g['menit_garuk_C_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_g['jam_garuk_C_PM'] != 0 || $pm_g['menit_garuk_C_PM'] != 0) {echo str_pad($pm_g['jam_garuk_C_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_C_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_C_PM'] != 0 || $pm_g['menit_garuk_C_PM'] != 0) { 
						echo round(((($pm_g['jam_garuk_C_PM']*60)+$pm_g['menit_garuk_C_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_g['jam_garuk_C_GT'] != 0 || $gt_g['menit_garuk_C_GT'] != 0) {echo str_pad($gt_g['jam_garuk_C_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_C_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_C_GT'] != 0 || $gt_g['menit_garuk_C_GT'] != 0) { 
						echo round(((($gt_g['jam_garuk_C_GT']*60)+$gt_g['menit_garuk_C_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_g['jam_garuk_C_TG'] != 0 || $tg_g['menit_garuk_C_TG'] != 0) {echo str_pad($tg_g['jam_garuk_C_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_C_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_C_TG'] != 0 || $tg_g['menit_garuk_C_TG'] != 0) { 
						echo round(((($tg_g['jam_garuk_C_TG']*60)+$tg_g['menit_garuk_C_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_C'] != 0 || $sum_mesin_garuk['menit_garuk_C'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_C'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_C'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_C'] != 0 || $sum_mesin_garuk['menit_garuk_C'] != 0) { 
						echo round(((($sum_mesin_garuk['jam_garuk_C']*60)+$sum_mesin_garuk['menit_garuk_C'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Mesin D -->
                <tr>
                    <td align="center">D</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_D_LM'] != 0 || $lm_g['menit_garuk_D_LM'] != 0) {echo str_pad($lm_g['jam_garuk_D_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_D_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_g['jam_garuk_D_LM'] != 0 || $lm_g['menit_garuk_D_LM'] != 0) { 
						echo round(((($lm_g['jam_garuk_D_LM']*60)+$lm_g['menit_garuk_D_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_g['jam_garuk_D_KM'] != 0 || $km_g['menit_garuk_D_KM'] != 0) {echo str_pad($km_g['jam_garuk_D_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_D_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_D_KM'] != 0 || $km_g['menit_garuk_D_KM'] != 0) { 
						echo round(((($km_g['jam_garuk_D_KM']*60)+$km_g['menit_garuk_D_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_g['jam_garuk_D_PT'] != 0 || $pt_g['menit_garuk_D_PT'] != 0) {echo str_pad($pt_g['jam_garuk_D_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_D_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_D_PT'] != 0 || $pt_g['menit_garuk_D_PT'] != 0) { 
						echo round(((($pt_g['jam_garuk_D_PT']*60)+$pt_g['menit_garuk_D_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_g['jam_garuk_D_KO'] != 0 || $ko_g['menit_garuk_D_KO'] != 0) {echo str_pad($ko_g['jam_garuk_D_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_D_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_D_KO'] != 0 || $ko_g['menit_garuk_D_KO'] != 0) { 
						echo round(((($ko_g['jam_garuk_D_KO']*60)+$ko_g['menit_garuk_D_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_g['jam_garuk_D_AP'] != 0 || $ap_g['menit_garuk_D_AP'] != 0) {echo str_pad($ap_g['jam_garuk_D_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_D_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_D_AP'] != 0 || $ap_g['menit_garuk_D_AP'] != 0) { 
						echo round(((($ap_g['jam_garuk_D_AP']*60)+$ap_g['menit_garuk_D_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_g['jam_garuk_D_PA'] != 0 || $pa_g['menit_garuk_D_PA'] != 0) {echo str_pad($pa_g['jam_garuk_D_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_D_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_D_PA'] != 0 || $pa_g['menit_garuk_D_PA'] != 0) { 
						echo round(((($pa_g['jam_garuk_D_PA']*60)+$pa_g['menit_garuk_D_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_g['jam_garuk_D_PM'] != 0 || $pm_g['menit_garuk_D_PM'] != 0) {echo str_pad($pm_g['jam_garuk_D_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_D_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_D_PM'] != 0 || $pm_g['menit_garuk_D_PM'] != 0) { 
						echo round(((($pm_g['jam_garuk_D_PM']*60)+$pm_g['menit_garuk_D_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_g['jam_garuk_D_GT'] != 0 || $gt_g['menit_garuk_D_GT'] != 0) {echo str_pad($gt_g['jam_garuk_D_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_D_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_D_GT'] != 0 || $gt_g['menit_garuk_D_GT'] != 0) { 
						echo round(((($gt_g['jam_garuk_D_GT']*60)+$gt_g['menit_garuk_D_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_g['jam_garuk_D_TG'] != 0 || $tg_g['menit_garuk_D_TG'] != 0) {echo str_pad($tg_g['jam_garuk_D_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_D_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_D_TG'] != 0 || $tg_g['menit_garuk_D_TG'] != 0) { 
						echo round(((($tg_g['jam_garuk_D_TG']*60)+$tg_g['menit_garuk_D_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_D'] != 0 || $sum_mesin_garuk['menit_garuk_D'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_D'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_D'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_D'] != 0 || $sum_mesin_garuk['menit_garuk_D'] != 0) { 
						echo round(((($sum_mesin_garuk['jam_garuk_D']*60)+$sum_mesin_garuk['menit_garuk_D'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Mesin E -->
                <tr>
                    <td align="center">E</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_E_LM'] != 0 || $lm_g['menit_garuk_E_LM'] != 0) {echo str_pad($lm_g['jam_garuk_E_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_E_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_g['jam_garuk_E_LM'] != 0 || $lm_g['menit_garuk_E_LM'] != 0) { 
						echo round(((($lm_g['jam_garuk_E_LM']*60)+$lm_g['menit_garuk_E_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_g['jam_garuk_E_KM'] != 0 || $km_g['menit_garuk_E_KM'] != 0) {echo str_pad($km_g['jam_garuk_E_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_E_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_E_KM'] != 0 || $km_g['menit_garuk_E_KM'] != 0) { 
						echo round(((($km_g['jam_garuk_E_KM']*60)+$km_g['menit_garuk_E_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_g['jam_garuk_E_PT'] != 0 || $pt_g['menit_garuk_E_PT'] != 0) {echo str_pad($pt_g['jam_garuk_E_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_E_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_E_PT'] != 0 || $pt_g['menit_garuk_E_PT'] != 0) { 
						echo round(((($pt_g['jam_garuk_E_PT']*60)+$pt_g['menit_garuk_E_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_g['jam_garuk_E_KO'] != 0 || $ko_g['menit_garuk_E_KO'] != 0) {echo str_pad($ko_g['jam_garuk_E_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_E_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_E_KO'] != 0 || $ko_g['menit_garuk_E_KO'] != 0) { 
						echo round(((($ko_g['jam_garuk_E_KO']*60)+$ko_g['menit_garuk_E_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_g['jam_garuk_E_AP'] != 0 || $ap_g['menit_garuk_E_AP'] != 0) {echo str_pad($ap_g['jam_garuk_E_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_E_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_E_AP'] != 0 || $ap_g['menit_garuk_E_AP'] != 0) { 
						echo round(((($ap_g['jam_garuk_E_AP']*60)+$ap_g['menit_garuk_E_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_g['jam_garuk_E_PA'] != 0 || $pa_g['menit_garuk_E_PA'] != 0) {echo str_pad($pa_g['jam_garuk_E_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_E_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_E_PA'] != 0 || $pa_g['menit_garuk_E_PA'] != 0) { 
						echo round(((($pa_g['jam_garuk_E_PA']*60)+$pa_g['menit_garuk_E_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_g['jam_garuk_E_PM'] != 0 || $pm_g['menit_garuk_E_PM'] != 0) {echo str_pad($pm_g['jam_garuk_E_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_E_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_E_PM'] != 0 || $pm_g['menit_garuk_E_PM'] != 0) { 
						echo round(((($pm_g['jam_garuk_E_PM']*60)+$pm_g['menit_garuk_E_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_g['jam_garuk_E_GT'] != 0 || $gt_g['menit_garuk_E_GT'] != 0) {echo str_pad($gt_g['jam_garuk_E_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_E_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_E_GT'] != 0 || $gt_g['menit_garuk_E_GT'] != 0) { 
						echo round(((($gt_g['jam_garuk_E_GT']*60)+$gt_g['menit_garuk_E_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_g['jam_garuk_E_TG'] != 0 || $tg_g['menit_garuk_E_TG'] != 0) {echo str_pad($tg_g['jam_garuk_E_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_E_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_E_TG'] != 0 || $tg_g['menit_garuk_E_TG'] != 0) { 
						echo round(((($tg_g['jam_garuk_E_TG']*60)+$tg_g['menit_garuk_E_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_E'] != 0 || $sum_mesin_garuk['menit_garuk_E'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_E'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_E'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_E'] != 0 || $sum_mesin_garuk['menit_garuk_E'] != 0) { 
						echo round(((($sum_mesin_garuk['jam_garuk_E']*60)+$sum_mesin_garuk['menit_garuk_E'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Mesin F -->
                <tr>
                    <td align="center">F</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_F_LM'] != 0 || $lm_g['menit_garuk_F_LM'] != 0) {echo str_pad($lm_g['jam_garuk_F_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_F_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_g['jam_garuk_F_LM'] != 0 || $lm_g['menit_garuk_F_LM'] != 0) { 
						echo round(((($lm_g['jam_garuk_F_LM']*60)+$lm_g['menit_garuk_F_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_g['jam_garuk_F_KM'] != 0 || $km_g['menit_garuk_F_KM'] != 0) {echo str_pad($km_g['jam_garuk_F_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_F_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_F_KM'] != 0 || $km_g['menit_garuk_F_KM'] != 0) { 
						echo round(((($km_g['jam_garuk_F_KM']*60)+$km_g['menit_garuk_F_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_g['jam_garuk_F_PT'] != 0 || $pt_g['menit_garuk_F_PT'] != 0) {echo str_pad($pt_g['jam_garuk_F_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_F_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_F_PT'] != 0 || $pt_g['menit_garuk_F_PT'] != 0) { 
						echo round(((($pt_g['jam_garuk_F_PT']*60)+$pt_g['menit_garuk_F_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_g['jam_garuk_F_KO'] != 0 || $ko_g['menit_garuk_F_KO'] != 0) {echo str_pad($ko_g['jam_garuk_F_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_F_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_F_KO'] != 0 || $ko_g['menit_garuk_F_KO'] != 0) { 
						echo round(((($ko_g['jam_garuk_F_KO']*60)+$ko_g['menit_garuk_F_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_g['jam_garuk_F_AP'] != 0 || $ap_g['menit_garuk_F_AP'] != 0) {echo str_pad($ap_g['jam_garuk_F_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_F_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_F_AP'] != 0 || $ap_g['menit_garuk_F_AP'] != 0) { 
						echo round(((($ap_g['jam_garuk_F_AP']*60)+$ap_g['menit_garuk_F_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_g['jam_garuk_F_PA'] != 0 || $pa_g['menit_garuk_F_PA'] != 0) {echo str_pad($pa_g['jam_garuk_F_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_F_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_F_PA'] != 0 || $pa_g['menit_garuk_F_PA'] != 0) { 
						echo round(((($pa_g['jam_garuk_F_PA']*60)+$pa_g['menit_garuk_F_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_g['jam_garuk_F_PM'] != 0 || $pm_g['menit_garuk_F_PM'] != 0) {echo str_pad($pm_g['jam_garuk_F_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_F_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_F_PM'] != 0 || $pm_g['menit_garuk_F_PM'] != 0) { 
						echo round(((($pm_g['jam_garuk_F_PM']*60)+$pm_g['menit_garuk_F_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_g['jam_garuk_F_GT'] != 0 || $gt_g['menit_garuk_F_GT'] != 0) {echo str_pad($gt_g['jam_garuk_F_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_F_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_F_GT'] != 0 || $gt_g['menit_garuk_F_GT'] != 0) { 
						echo round(((($gt_g['jam_garuk_F_GT']*60)+$gt_g['menit_garuk_F_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_g['jam_garuk_F_TG'] != 0 || $tg_g['menit_garuk_F_TG'] != 0) {echo str_pad($tg_g['jam_garuk_F_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_F_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_F_TG'] != 0 || $tg_g['menit_garuk_F_TG'] != 0) { 
						echo round(((($tg_g['jam_garuk_F_TG']*60)+$tg_g['menit_garuk_F_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_F'] != 0 || $sum_mesin_garuk['menit_garuk_F'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_F'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_F'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_F'] != 0 || $sum_mesin_garuk['menit_garuk_F'] != 0) { 
						echo round(((($sum_mesin_garuk['jam_garuk_F']*60)+$sum_mesin_garuk['menit_garuk_F'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
            <!-- End Garuk -->
            <!-- Untuk Kolom Sisir -->
                <tr>
                    <?php 
                    $query_sisir9 = "SELECT
                                            FLOOR(SUM(CASE 
												WHEN kode_stop = 'TG' AND kode_operation IN ('COM1', 'COM2')
												THEN durasi_jam_stop 
												ELSE 0 
											END))  AS jam_sisir_TG,

											MOD(ROUND(SUM(CASE 
												WHEN kode_stop = 'TG' AND kode_operation IN ('COM1', 'COM2')
												THEN (durasi_jam_stop - FLOOR(durasi_jam_stop)) * 60 
												ELSE 0 
											END)), 60) AS menit_sisir_TG
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir9    = mysqli_query($cona,$query_sisir9);
                                    $tg_sisir             = mysqli_fetch_assoc($stmt_sisir9);
                    $query_sisir8 = "SELECT
                                            FLOOR(SUM(CASE 
												WHEN kode_stop = 'GT' AND kode_operation IN ('COM1', 'COM2')
												THEN durasi_jam_stop 
												ELSE 0 
											END))  AS jam_sisir_GT,

											MOD(ROUND(SUM(CASE 
												WHEN kode_stop = 'GT' AND kode_operation IN ('COM1', 'COM2')
												THEN (durasi_jam_stop - FLOOR(durasi_jam_stop)) * 60 
												ELSE 0 
											END)), 60) AS menit_sisir_GT
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir8    = mysqli_query($cona,$query_sisir8);
                                    $gt_sisir             = mysqli_fetch_assoc($stmt_sisir8);
                    $query_sisir7 = "SELECT
                                            FLOOR(SUM(CASE 
												WHEN kode_stop = 'PM' AND kode_operation IN ('COM1', 'COM2')
												THEN durasi_jam_stop 
												ELSE 0 
											END))  AS jam_sisir_PM,

											MOD(ROUND(SUM(CASE 
												WHEN kode_stop = 'PM' AND kode_operation IN ('COM1', 'COM2')
												THEN (durasi_jam_stop - FLOOR(durasi_jam_stop)) * 60 
												ELSE 0 
											END)), 60) AS menit_sisir_PM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir7    = mysqli_query($cona,$query_sisir7);
                                    $pm_sisir             = mysqli_fetch_assoc($stmt_sisir7);
                    $query_sisir6 = "SELECT
                                            FLOOR(SUM(CASE 
												WHEN kode_stop = 'PA' AND kode_operation IN ('COM1', 'COM2')
												THEN durasi_jam_stop 
												ELSE 0 
											END))  AS jam_sisir_PA,

											MOD(ROUND(SUM(CASE 
												WHEN kode_stop = 'PA' AND kode_operation IN ('COM1', 'COM2')
												THEN (durasi_jam_stop - FLOOR(durasi_jam_stop)) * 60 
												ELSE 0 
											END)), 60) AS menit_sisir_PA
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir6    = mysqli_query($cona,$query_sisir6);
                                    $pa_sisir             = mysqli_fetch_assoc($stmt_sisir6);
                    $query_sisir5 = "SELECT
                                            FLOOR(SUM(CASE 
												WHEN kode_stop = 'AP' AND kode_operation IN ('COM1', 'COM2')
												THEN durasi_jam_stop 
												ELSE 0 
											END))  AS jam_sisir_AP,

											MOD(ROUND(SUM(CASE 
												WHEN kode_stop = 'AP' AND kode_operation IN ('COM1', 'COM2')
												THEN (durasi_jam_stop - FLOOR(durasi_jam_stop)) * 60 
												ELSE 0 
											END)), 60) AS menit_sisir_AP
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir5    = mysqli_query($cona,$query_sisir5);
                                    $ap_sisir             = mysqli_fetch_assoc($stmt_sisir5);
                    $query_sisir4 = "SELECT
                                            FLOOR(SUM(CASE 
												WHEN kode_stop = 'KO' AND kode_operation IN ('COM1', 'COM2')
												THEN durasi_jam_stop 
												ELSE 0 
											END))  AS jam_sisir_KO,

											MOD(ROUND(SUM(CASE 
												WHEN kode_stop = 'KO' AND kode_operation IN ('COM1', 'COM2')
												THEN (durasi_jam_stop - FLOOR(durasi_jam_stop)) * 60 
												ELSE 0 
											END)), 60) AS menit_sisir_KO
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir4    = mysqli_query($cona,$query_sisir4);
                                    $ko_sisir             = mysqli_fetch_assoc($stmt_sisir4);
                    $query_sisir3 = "SELECT
                                            FLOOR(SUM(CASE 
												WHEN kode_stop = 'PT' AND kode_operation IN ('COM1', 'COM2')
												THEN durasi_jam_stop 
												ELSE 0 
											END))  AS jam_sisir_PT,

											MOD(ROUND(SUM(CASE 
												WHEN kode_stop = 'PT' AND kode_operation IN ('COM1', 'COM2')
												THEN (durasi_jam_stop - FLOOR(durasi_jam_stop)) * 60 
												ELSE 0 
											END)), 60) AS menit_sisir_PT
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir3    = mysqli_query($cona,$query_sisir3);
                                    $pt_sisir             = mysqli_fetch_assoc($stmt_sisir3);
                    $query_sisir2 = "SELECT
                                            FLOOR(SUM(CASE 
												WHEN kode_stop = 'KM' AND kode_operation IN ('COM1', 'COM2')
												THEN durasi_jam_stop 
												ELSE 0 
											END))  AS jam_sisir_KM,

											MOD(ROUND(SUM(CASE 
												WHEN kode_stop = 'KM' AND kode_operation IN ('COM1', 'COM2')
												THEN (durasi_jam_stop - FLOOR(durasi_jam_stop)) * 60 
												ELSE 0 
											END)), 60) AS menit_sisir_KM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir2    = mysqli_query($cona,$query_sisir2);
                                    $km_sisir             = mysqli_fetch_assoc($stmt_sisir2);
                    $query_sisir1 = "SELECT
                                            FLOOR(SUM(CASE 
												WHEN kode_stop = 'LM' AND kode_operation IN ('COM1', 'COM2')
												THEN durasi_jam_stop 
												ELSE 0 
											END))  AS jam_sisir_LM,

											MOD(ROUND(SUM(CASE 
												WHEN kode_stop = 'LM' AND kode_operation IN ('COM1', 'COM2')
												THEN (durasi_jam_stop - FLOOR(durasi_jam_stop)) * 60 
												ELSE 0 
											END)), 60) AS menit_sisir_LM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir1    = mysqli_query($cona,$query_sisir1);
                                    $lm_sisir             = mysqli_fetch_assoc($stmt_sisir1);
                            // Total Sisir
                    $query_mesin_sisir = "SELECT
                                            FLOOR(SUM(CASE 
												WHEN kode_operation IN ('COM1', 'COM2')
												THEN durasi_jam_stop 
												ELSE 0 
											END))  AS jam_sisir,

											MOD(ROUND(SUM(CASE 
												WHEN kode_operation IN ('COM1', 'COM2')
												THEN (durasi_jam_stop - FLOOR(durasi_jam_stop)) * 60 
												ELSE 0 
											END)), 60) AS menit_sisir
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_sisir= mysqli_query($cona,$query_mesin_sisir);
                        $sum_mesin_sisir= mysqli_fetch_assoc($stmt_mesin_sisir);
                        ?>
                    <td align="left"><strong>SISIR</strong></td>
                    <td align="center">'01</td>
                    <td align="center"><?php if ($lm_sisir['jam_sisir_LM'] != 0 || $lm_sisir['menit_sisir_LM'] != 0) {echo str_pad($lm_sisir['jam_sisir_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_sisir['menit_sisir_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_sisir['jam_sisir_LM'] != 0 || $lm_sisir['menit_sisir_LM'] != 0) { 
						echo round(((($lm_sisir['jam_sisir_LM']*60)+$lm_sisir['menit_sisir_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($km_sisir['jam_sisir_KM'] != 0 || $km_sisir['menit_sisir_KM'] != 0) {echo str_pad($km_sisir['jam_sisir_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_sisir['menit_sisir_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_sisir['jam_sisir_KM'] != 0 || $km_sisir['menit_sisir_KM'] != 0) { 
						echo round(((($km_sisir['jam_sisir_KM']*60)+$km_sisir['menit_sisir_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pt_sisir['jam_sisir_PT'] != 0 || $pt_sisir['menit_sisir_PT'] != 0) {echo str_pad($pt_sisir['jam_sisir_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_sisir['menit_sisir_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_sisir['jam_sisir_PT'] != 0 || $pt_sisir['menit_sisir_PT'] != 0) { 
						echo round(((($pt_sisir['jam_sisir_PT']*60)+$pt_sisir['menit_sisir_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ko_sisir['jam_sisir_KO'] != 0 || $ko_sisir['menit_sisir_KO'] != 0) {echo str_pad($ko_sisir['jam_sisir_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_sisir['menit_sisir_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_sisir['jam_sisir_KO'] != 0 || $ko_sisir['menit_sisir_KO'] != 0) { 
						echo round(((($ko_sisir['jam_sisir_KO']*60)+$ko_sisir['menit_sisir_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ap_sisir['jam_sisir_AP'] != 0 || $ap_sisir['menit_sisir_AP'] != 0) {echo str_pad($ap_sisir['jam_sisir_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_sisir['menit_sisir_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_sisir['jam_sisir_AP'] != 0 || $ap_sisir['menit_sisir_AP'] != 0) { 
						echo round(((($ap_sisir['jam_sisir_AP']*60)+$ap_sisir['menit_sisir_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pa_sisir['jam_sisir_PA'] != 0 || $pa_sisir['menit_sisir_PA'] != 0) {echo str_pad($pa_sisir['jam_sisir_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_sisir['menit_sisir_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_sisir['jam_sisir_PA'] != 0 || $pa_sisir['menit_sisir_PA'] != 0) { 
						echo round(((($pa_sisir['jam_sisir_PA']*60)+$pa_sisir['menit_sisir_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pm_sisir['jam_sisir_PM'] != 0 || $pm_sisir['menit_sisir_PM'] != 0) {echo str_pad($pm_sisir['jam_sisir_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_sisir['menit_sisir_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_sisir['jam_sisir_PM'] != 0 || $pm_sisir['menit_sisir_PM'] != 0) { 
						echo round(((($pm_sisir['jam_sisir_PM']*60)+$pm_sisir['menit_sisir_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($gt_sisir['jam_sisir_GT'] != 0 || $gt_sisir['menit_sisir_GT'] != 0) {echo str_pad($gt_sisir['jam_sisir_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_sisir['menit_sisir_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_sisir['jam_sisir_GT'] != 0 || $gt_sisir['menit_sisir_GT'] != 0) { 
						echo round(((($gt_sisir['jam_sisir_GT']*60)+$gt_sisir['menit_sisir_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($tg_sisir['jam_sisir_TG'] != 0 || $tg_sisir['menit_sisir_TG'] != 0) {echo str_pad($tg_sisir['jam_sisir_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_sisir['menit_sisir_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_sisir['jam_sisir_TG'] != 0 || $tg_sisir['menit_sisir_TG'] != 0) { 
						echo round(((($tg_sisir['jam_sisir_TG']*60)+$tg_sisir['menit_sisir_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?> %</td>
                    <td align="center"><?php if ($sum_mesin_sisir['jam_sisir'] != 0 || $sum_mesin_sisir['menit_sisir'] != 0) {echo str_pad($sum_mesin_sisir['jam_sisir'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_sisir['menit_sisir'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_sisir['jam_sisir'] != 0 || $sum_mesin_sisir['menit_sisir'] != 0) { 
						echo round(((($sum_mesin_sisir['jam_sisir']*60)+$sum_mesin_sisir['menit_sisir'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                </tr>
            <!-- End Sisir -->          
            <!-- Untuk Kolom Potong Bulu -->
                <tr>
                    <?php
                    $query_pb2 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_01_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_01_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_02_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_02_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_03_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_03_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_04_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_04_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_05_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_05_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_06_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_06_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_07_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_07_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_08_KM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_08_KM
                                        FROM
                                            tbl_stoppage
                                        WHERE dept ='BRS'
                                            AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                            AND tbl_stoppage.kode_stop <> ''";
                                $stmt_pb2    = mysqli_query($cona,$query_pb2);
                                $km_pb             = mysqli_fetch_assoc($stmt_pb2);
                    $query_pb3 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_01_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_01_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_02_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_02_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_03_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_03_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_04_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_04_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_05_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_05_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_06_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_06_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_07_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_07_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_08_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_08_PT
                                        FROM
                                            tbl_stoppage
                                        WHERE dept ='BRS'
                                            AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                            AND tbl_stoppage.kode_stop <> ''";
                                $stmt_pb3    = mysqli_query($cona,$query_pb3);
                                $pt_pb             = mysqli_fetch_assoc($stmt_pb3);
                    $query_pb4 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_01_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_01_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_02_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_02_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_03_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_03_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_04_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_04_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_05_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_05_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_06_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_06_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_07_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_07_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_08_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_08_KO
                                        FROM
                                            tbl_stoppage
                                        WHERE dept ='BRS'
                                            AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                            AND tbl_stoppage.kode_stop <> ''";
                                $stmt_pb4    = mysqli_query($cona,$query_pb4);
                                $ko_pb             = mysqli_fetch_assoc($stmt_pb4);
                    $query_pb5 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_01_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_01_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_02_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_02_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_03_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_03_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_04_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_04_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_05_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_05_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_06_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_06_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_07_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_07_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_08_AP,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'AP' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_08_AP
                                        FROM
                                            tbl_stoppage
                                        WHERE dept ='BRS'
                                            AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                            AND tbl_stoppage.kode_stop <> ''";
                                $stmt_pb5    = mysqli_query($cona,$query_pb5);
                                $ap_pb             = mysqli_fetch_assoc($stmt_pb5);
                    $query_pb6 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_01_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_01_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_02_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_02_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_03_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_03_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_04_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_04_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_05_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_05_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_06_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_06_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_07_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_07_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_08_PA,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PA' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_08_PA
                                        FROM
                                            tbl_stoppage
                                        WHERE dept ='BRS'
                                            AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                            AND tbl_stoppage.kode_stop <> ''";
                                $stmt_pb6    = mysqli_query($cona,$query_pb6);
                                $pa_pb             = mysqli_fetch_assoc($stmt_pb6);
                    $query_pb7 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_01_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_01_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_02_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_02_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_03_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_03_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_04_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_04_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_05_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_05_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_06_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_06_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_07_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_07_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_08_PM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_08_PM
                                        FROM
                                            tbl_stoppage
                                        WHERE dept ='BRS'
                                            AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                            AND tbl_stoppage.kode_stop <> ''";
                                $stmt_pb7    = mysqli_query($cona,$query_pb7);
                                $pm_pb             = mysqli_fetch_assoc($stmt_pb7);
                    $query_pb8 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_01_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_01_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_02_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_02_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_03_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_03_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_04_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_04_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_05_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_05_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_06_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_06_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_07_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_07_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_08_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_08_GT
                                        FROM
                                            tbl_stoppage
                                        WHERE dept ='BRS'
                                            AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                            AND tbl_stoppage.kode_stop <> ''";
                                $stmt_pb8    = mysqli_query($cona,$query_pb8);
                                $gt_pb             = mysqli_fetch_assoc($stmt_pb8);
                    $query_pb9 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_01_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_01_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_02_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_02_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_03_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_03_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_04_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_04_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_05_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_05_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_06_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_06_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_07_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_07_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_08_TG,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'TG' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_08_TG
                                        FROM
                                            tbl_stoppage
                                        WHERE dept ='BRS'
                                            AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                            AND tbl_stoppage.kode_stop <> ''";
                                $stmt_pb9    = mysqli_query($cona,$query_pb9);
                                $tg_pb             = mysqli_fetch_assoc($stmt_pb9);
                    $query_pb1 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_01_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH101'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_01_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_02_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH102'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_02_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_03_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH103'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_03_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_04_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH104'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_04_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_05_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH105'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_05_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_06_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH106'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_06_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_07_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH107'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_07_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_pb_08_LM,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'LM' 
                                                AND kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                AND mesin = 'P3SH108'
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_pb_08_LM
                                        FROM
                                            tbl_stoppage
                                        WHERE dept ='BRS'
                                            AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                            AND tbl_stoppage.kode_stop <> ''";
                                $stmt_pb1    = mysqli_query($cona,$query_pb1);
                                $lm_pb             = mysqli_fetch_assoc($stmt_pb1);
                            // Total Pb
                    $query_mesin_pb = "SELECT
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH101'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_pb_01,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH101'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_pb_01,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH102'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_pb_02,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH102'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_pb_02,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH103'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_pb_03,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH103'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_pb_03,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH104'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_pb_04,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH104'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_pb_04,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH105'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_pb_05,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH105'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_pb_05,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH106'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_pb_06,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH106'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_pb_06,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH107'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_pb_07,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH107'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_pb_07,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH108'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_pb_08,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                                                    AND mesin = 'P3SH108'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_pb_08
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_pb= mysqli_query($cona,$query_mesin_pb);
                        $sum_mesin_pb= mysqli_fetch_assoc($stmt_mesin_pb);
                    ?>
                <!-- Mesin 01 -->
                    <td rowspan="8" align="left"><strong>POTONG BULU</strong></td>
                    <td align="center">'01</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_01_LM'] != 0 || $lm_pb['menit_pb_01_LM'] != 0) {echo str_pad($lm_pb['jam_pb_01_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_01_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_pb['jam_pb_01_LM'] != 0 || $lm_pb['menit_pb_01_LM'] != 0) { 
						echo round(((($lm_pb['jam_pb_01_LM']*60)+$lm_pb['menit_pb_01_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($km_pb['jam_pb_01_KM'] != 0 || $km_pb['menit_pb_01_KM'] != 0) {echo str_pad($km_pb['jam_pb_01_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_01_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_01_KM'] != 0 || $km_pb['menit_pb_01_KM'] != 0) { 
						echo round(((($km_pb['jam_pb_01_KM']*60)+$km_pb['menit_pb_01_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pt_pb['jam_pb_01_PT'] != 0 || $pt_pb['menit_pb_01_PT'] != 0) {echo str_pad($pt_pb['jam_pb_01_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_01_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_01_PT'] != 0 || $pt_pb['menit_pb_01_PT'] != 0) { 
						echo round(((($pt_pb['jam_pb_01_PT']*60)+$pt_pb['menit_pb_01_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ko_pb['jam_pb_01_KO'] != 0 || $ko_pb['menit_pb_01_KO'] != 0) {echo str_pad($ko_pb['jam_pb_01_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_01_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_01_KO'] != 0 || $ko_pb['menit_pb_01_KO'] != 0) { 
						echo round(((($ko_pb['jam_pb_01_KO']*60)+$ko_pb['menit_pb_01_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ap_pb['jam_pb_01_AP'] != 0 || $ap_pb['menit_pb_01_AP'] != 0) {echo str_pad($ap_pb['jam_pb_01_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_01_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_01_AP'] != 0 || $ap_pb['menit_pb_01_AP'] != 0) { 
						echo round(((($ap_pb['jam_pb_01_AP']*60)+$ap_pb['menit_pb_01_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pa_pb['jam_pb_01_PA'] != 0 || $pa_pb['menit_pb_01_PA'] != 0) {echo str_pad($pa_pb['jam_pb_01_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_01_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_01_PA'] != 0 || $pa_pb['menit_pb_01_PA'] != 0) { 
						echo round(((($pa_pb['jam_pb_01_PA']*60)+$pa_pb['menit_pb_01_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pm_pb['jam_pb_01_PM'] != 0 || $pm_pb['menit_pb_01_PM'] != 0) {echo str_pad($pm_pb['jam_pb_01_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_01_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_01_PM'] != 0 || $pm_pb['menit_pb_01_PM'] != 0) { 
						echo round(((($pm_pb['jam_pb_01_PM']*60)+$pm_pb['menit_pb_01_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($gt_pb['jam_pb_01_GT'] != 0 || $gt_pb['menit_pb_01_GT'] != 0) {echo str_pad($gt_pb['jam_pb_01_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_01_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_01_GT'] != 0 || $gt_pb['menit_pb_01_GT'] != 0) { 
						echo round(((($gt_pb['jam_pb_01_GT']*60)+$gt_pb['menit_pb_01_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($tg_pb['jam_pb_01_TG'] != 0 || $tg_pb['menit_pb_01_TG'] != 0) {echo str_pad($tg_pb['jam_pb_01_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_01_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_01_TG'] != 0 || $tg_pb['menit_pb_01_TG'] != 0) { 
						echo round(((($tg_pb['jam_pb_01_TG']*60)+$tg_pb['menit_pb_01_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_01'] != 0 || $sum_mesin_pb['menit_pb_01'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_01'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_01'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_01'] != 0 || $sum_mesin_pb['menit_pb_01'] != 0) { 
						echo round(((($sum_mesin_pb['jam_pb_01']*60)+$sum_mesin_pb['menit_pb_01'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                </tr>
                <!-- Mesin 02 -->
                <tr>
                    <td align="center">'02</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_02_LM'] != 0 || $lm_pb['menit_pb_02_LM'] != 0) {echo str_pad($lm_pb['jam_pb_02_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_02_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_pb['jam_pb_02_LM'] != 0 || $lm_pb['menit_pb_02_LM'] != 0) { 
						echo round(((($lm_pb['jam_pb_02_LM']*60)+$lm_pb['menit_pb_02_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_pb['jam_pb_02_KM'] != 0 || $km_pb['menit_pb_02_KM'] != 0) {echo str_pad($km_pb['jam_pb_02_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_02_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_02_KM'] != 0 || $km_pb['menit_pb_02_KM'] != 0) { 
						echo round(((($km_pb['jam_pb_02_KM']*60)+$km_pb['menit_pb_02_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_pb['jam_pb_02_PT'] != 0 || $pt_pb['menit_pb_02_PT'] != 0) {echo str_pad($pt_pb['jam_pb_02_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_02_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_02_PT'] != 0 || $pt_pb['menit_pb_02_PT'] != 0) { 
						echo round(((($pt_pb['jam_pb_02_PT']*60)+$pt_pb['menit_pb_02_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_pb['jam_pb_02_KO'] != 0 || $ko_pb['menit_pb_02_KO'] != 0) {echo str_pad($ko_pb['jam_pb_02_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_02_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_02_KO'] != 0 || $ko_pb['menit_pb_02_KO'] != 0) { 
						echo round(((($ko_pb['jam_pb_02_KO']*60)+$ko_pb['menit_pb_02_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_pb['jam_pb_02_AP'] != 0 || $ap_pb['menit_pb_02_AP'] != 0) {echo str_pad($ap_pb['jam_pb_02_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_02_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_02_AP'] != 0 || $ap_pb['menit_pb_02_AP'] != 0) { 
						echo round(((($ap_pb['jam_pb_02_AP']*60)+$ap_pb['menit_pb_02_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_pb['jam_pb_02_PA'] != 0 || $pa_pb['menit_pb_02_PA'] != 0) {echo str_pad($pa_pb['jam_pb_02_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_02_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_02_PA'] != 0 || $pa_pb['menit_pb_02_PA'] != 0) { 
						echo round(((($pa_pb['jam_pb_02_PA']*60)+$pa_pb['menit_pb_02_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_pb['jam_pb_02_PM'] != 0 || $pm_pb['menit_pb_02_PM'] != 0) {echo str_pad($pm_pb['jam_pb_02_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_02_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_02_PM'] != 0 || $pm_pb['menit_pb_02_PM'] != 0) { 
						echo round(((($pm_pb['jam_pb_02_PM']*60)+$pm_pb['menit_pb_02_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_pb['jam_pb_02_GT'] != 0 || $gt_pb['menit_pb_02_GT'] != 0) {echo str_pad($gt_pb['jam_pb_02_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_02_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_02_GT'] != 0 || $gt_pb['menit_pb_02_GT'] != 0) { 
						echo round(((($gt_pb['jam_pb_02_GT']*60)+$gt_pb['menit_pb_02_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_pb['jam_pb_02_TG'] != 0 || $tg_pb['menit_pb_02_TG'] != 0) {echo str_pad($tg_pb['jam_pb_02_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_02_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_02_TG'] != 0 || $tg_pb['menit_pb_02_TG'] != 0) { 
						echo round(((($tg_pb['jam_pb_02_TG']*60)+$tg_pb['menit_pb_02_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_02'] != 0 || $sum_mesin_pb['menit_pb_02'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_01'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_02'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_02'] != 0 || $sum_mesin_pb['menit_pb_02'] != 0) { 
						echo round(((($sum_mesin_pb['jam_pb_02']*60)+$sum_mesin_pb['menit_pb_02'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Mesin 03 -->
                <tr>
                    <td align="center">'03</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_03_LM'] != 0 || $lm_pb['menit_pb_03_LM'] != 0) {echo str_pad($lm_pb['jam_pb_03_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_03_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_pb['jam_pb_03_LM'] != 0 || $lm_pb['menit_pb_03_LM'] != 0) { 
						echo round(((($lm_pb['jam_pb_03_LM']*60)+$lm_pb['menit_pb_03_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_pb['jam_pb_03_KM'] != 0 || $km_pb['menit_pb_03_KM'] != 0) {echo str_pad($km_pb['jam_pb_03_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_03_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_03_KM'] != 0 || $km_pb['menit_pb_03_KM'] != 0) { 
						echo round(((($km_pb['jam_pb_03_KM']*60)+$km_pb['menit_pb_03_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_pb['jam_pb_03_PT'] != 0 || $pt_pb['menit_pb_03_PT'] != 0) {echo str_pad($pt_pb['jam_pb_03_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_03_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_03_PT'] != 0 || $pt_pb['menit_pb_03_PT'] != 0) { 
						echo round(((($pt_pb['jam_pb_03_PT']*60)+$pt_pb['menit_pb_03_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_pb['jam_pb_03_KO'] != 0 || $ko_pb['menit_pb_03_KO'] != 0) {echo str_pad($ko_pb['jam_pb_03_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_03_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_03_KO'] != 0 || $ko_pb['menit_pb_03_KO'] != 0) { 
						echo round(((($ko_pb['jam_pb_03_KO']*60)+$ko_pb['menit_pb_03_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_pb['jam_pb_03_AP'] != 0 || $ap_pb['menit_pb_03_AP'] != 0) {echo str_pad($ap_pb['jam_pb_03_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_03_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_03_AP'] != 0 || $ap_pb['menit_pb_03_AP'] != 0) { 
						echo round(((($ap_pb['jam_pb_03_AP']*60)+$ap_pb['menit_pb_03_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_pb['jam_pb_03_PA'] != 0 || $pa_pb['menit_pb_03_PA'] != 0) {echo str_pad($pa_pb['jam_pb_03_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_03_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_03_PA'] != 0 || $pa_pb['menit_pb_03_PA'] != 0) { 
						echo round(((($pa_pb['jam_pb_03_PA']*60)+$pa_pb['menit_pb_03_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_pb['jam_pb_03_PM'] != 0 || $pm_pb['menit_pb_03_PM'] != 0) {echo str_pad($pm_pb['jam_pb_03_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_03_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_03_PM'] != 0 || $pm_pb['menit_pb_03_PM'] != 0) { 
						echo round(((($pm_pb['jam_pb_03_PM']*60)+$pm_pb['menit_pb_03_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_pb['jam_pb_03_GT'] != 0 || $gt_pb['menit_pb_03_GT'] != 0) {echo str_pad($gt_pb['jam_pb_03_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_03_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_03_GT'] != 0 || $gt_pb['menit_pb_03_GT'] != 0) { 
						echo round(((($gt_pb['jam_pb_03_GT']*60)+$gt_pb['menit_pb_03_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_pb['jam_pb_03_TG'] != 0 || $tg_pb['menit_pb_03_TG'] != 0) {echo str_pad($tg_pb['jam_pb_03_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_03_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_03_TG'] != 0 || $tg_pb['menit_pb_03_TG'] != 0) { 
						echo round(((($tg_pb['jam_pb_03_TG']*60)+$tg_pb['menit_pb_03_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_03'] != 0 || $sum_mesin_pb['menit_pb_03'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_03'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_03'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_03'] != 0 || $sum_mesin_pb['menit_pb_03'] != 0) { 
						echo round(((($sum_mesin_pb['jam_pb_03']*60)+$sum_mesin_pb['menit_pb_03'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Mesin 04 -->
                <tr>
                    <td align="center">'04</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_04_LM'] != 0 || $lm_pb['menit_pb_04_LM'] != 0) {echo str_pad($lm_pb['jam_pb_04_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_04_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_pb['jam_pb_04_LM'] != 0 || $lm_pb['menit_pb_04_LM'] != 0) { 
						echo round(((($lm_pb['jam_pb_04_LM']*60)+$lm_pb['menit_pb_04_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_pb['jam_pb_04_KM'] != 0 || $km_pb['menit_pb_04_KM'] != 0) {echo str_pad($km_pb['jam_pb_04_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_04_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_04_KM'] != 0 || $km_pb['menit_pb_04_KM'] != 0) { 
						echo round(((($km_pb['jam_pb_04_KM']*60)+$km_pb['menit_pb_04_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_pb['jam_pb_04_PT'] != 0 || $pt_pb['menit_pb_04_PT'] != 0) {echo str_pad($pt_pb['jam_pb_04_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_04_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_04_PT'] != 0 || $pt_pb['menit_pb_04_PT'] != 0) { 
						echo round(((($pt_pb['jam_pb_04_PT']*60)+$pt_pb['menit_pb_04_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_pb['jam_pb_04_KO'] != 0 || $ko_pb['menit_pb_04_KO'] != 0) {echo str_pad($ko_pb['jam_pb_04_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_04_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_04_KO'] != 0 || $ko_pb['menit_pb_04_KO'] != 0) { 
						echo round(((($ko_pb['jam_pb_04_KO']*60)+$ko_pb['menit_pb_04_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_pb['jam_pb_04_AP'] != 0 || $ap_pb['menit_pb_04_AP'] != 0) {echo str_pad($ap_pb['jam_pb_04_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_04_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_04_AP'] != 0 || $ap_pb['menit_pb_04_AP'] != 0) { 
						echo round(((($ap_pb['jam_pb_04_AP']*60)+$ap_pb['menit_pb_04_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_pb['jam_pb_04_PA'] != 0 || $pa_pb['menit_pb_04_PA'] != 0) {echo str_pad($pa_pb['jam_pb_04_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_04_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_04_PA'] != 0 || $pa_pb['menit_pb_04_PA'] != 0) { 
						echo round(((($pa_pb['jam_pb_04_PA']*60)+$pa_pb['menit_pb_04_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_pb['jam_pb_04_PM'] != 0 || $pm_pb['menit_pb_04_PM'] != 0) {echo str_pad($pm_pb['jam_pb_04_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_04_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_04_PM'] != 0 || $pm_pb['menit_pb_04_PM'] != 0) { 
						echo round(((($pm_pb['jam_pb_04_PM']*60)+$pm_pb['menit_pb_04_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_pb['jam_pb_04_GT'] != 0 || $gt_pb['menit_pb_04_GT'] != 0) {echo str_pad($gt_pb['jam_pb_04_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_04_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_04_GT'] != 0 || $gt_pb['menit_pb_04_GT'] != 0) { 
						echo round(((($gt_pb['jam_pb_04_GT']*60)+$gt_pb['menit_pb_04_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_pb['jam_pb_04_TG'] != 0 || $tg_pb['menit_pb_04_TG'] != 0) {echo str_pad($tg_pb['jam_pb_04_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_04_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_04_TG'] != 0 || $tg_pb['menit_pb_04_TG'] != 0) { 
						echo round(((($tg_pb['jam_pb_04_TG']*60)+$tg_pb['menit_pb_04_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_04'] != 0 || $sum_mesin_pb['menit_pb_04'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_04'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_04'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_04'] != 0 || $sum_mesin_pb['menit_pb_04'] != 0) { 
						echo round(((($sum_mesin_pb['jam_pb_04']*60)+$sum_mesin_pb['menit_pb_04'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Mesin 05 -->
                <tr>
                    <td align="center">'05</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_05_LM'] != 0 || $lm_pb['menit_pb_05_LM'] != 0) {echo str_pad($lm_pb['jam_pb_05_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_05_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_pb['jam_pb_05_LM'] != 0 || $lm_pb['menit_pb_05_LM'] != 0) { 
						echo round(((($lm_pb['jam_pb_05_LM']*60)+$lm_pb['menit_pb_05_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_pb['jam_pb_05_KM'] != 0 || $km_pb['menit_pb_05_KM'] != 0) {echo str_pad($km_pb['jam_pb_05_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_05_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_05_KM'] != 0 || $km_pb['menit_pb_05_KM'] != 0) { 
						echo round(((($km_pb['jam_pb_05_KM']*60)+$km_pb['menit_pb_05_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_pb['jam_pb_05_PT'] != 0 || $pt_pb['menit_pb_05_PT'] != 0) {echo str_pad($pt_pb['jam_pb_05_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_05_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_05_PT'] != 0 || $pt_pb['menit_pb_05_PT'] != 0) { 
						echo round(((($pt_pb['jam_pb_05_PT']*60)+$pt_pb['menit_pb_05_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_pb['jam_pb_05_KO'] != 0 || $ko_pb['menit_pb_05_KO'] != 0) {echo str_pad($ko_pb['jam_pb_05_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_05_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_05_KO'] != 0 || $ko_pb['menit_pb_05_KO'] != 0) { 
						echo round(((($ko_pb['jam_pb_05_KO']*60)+$ko_pb['menit_pb_05_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_pb['jam_pb_05_AP'] != 0 || $ap_pb['menit_pb_05_AP'] != 0) {echo str_pad($ap_pb['jam_pb_05_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_05_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_05_AP'] != 0 || $ap_pb['menit_pb_05_AP'] != 0) { 
						echo round(((($ap_pb['jam_pb_05_AP']*60)+$ap_pb['menit_pb_05_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_pb['jam_pb_05_PA'] != 0 || $pa_pb['menit_pb_05_PA'] != 0) {echo str_pad($pa_pb['jam_pb_05_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_05_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_05_PA'] != 0 || $pa_pb['menit_pb_05_PA'] != 0) { 
						echo round(((($pa_pb['jam_pb_05_PA']*60)+$pa_pb['menit_pb_05_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_pb['jam_pb_05_PM'] != 0 || $pm_pb['menit_pb_05_PM'] != 0) {echo str_pad($pm_pb['jam_pb_05_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_05_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_05_PM'] != 0 || $pm_pb['menit_pb_05_PM'] != 0) { 
						echo round(((($pm_pb['jam_pb_05_PM']*60)+$pm_pb['menit_pb_05_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_pb['jam_pb_05_GT'] != 0 || $gt_pb['menit_pb_05_GT'] != 0) {echo str_pad($gt_pb['jam_pb_05_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_05_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_05_GT'] != 0 || $gt_pb['menit_pb_05_GT'] != 0) { 
						echo round(((($gt_pb['jam_pb_05_GT']*60)+$gt_pb['menit_pb_05_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_pb['jam_pb_05_TG'] != 0 || $tg_pb['menit_pb_05_TG'] != 0) {echo str_pad($tg_pb['jam_pb_05_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_05_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_05_TG'] != 0 || $tg_pb['menit_pb_05_TG'] != 0) { 
						echo round(((($tg_pb['jam_pb_05_TG']*60)+$tg_pb['menit_pb_05_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_05'] != 0 || $sum_mesin_pb['menit_pb_05'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_05'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_05'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_05'] != 0 || $sum_mesin_pb['menit_pb_05'] != 0) { 
						echo round(((($sum_mesin_pb['jam_pb_05']*60)+$sum_mesin_pb['menit_pb_05'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Mesin 06 -->
                <tr>
                    <td align="center">'06</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_06_LM'] != 0 || $lm_pb['menit_pb_06_LM'] != 0) {echo str_pad($lm_pb['jam_pb_06_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_06_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_pb['jam_pb_06_LM'] != 0 || $lm_pb['menit_pb_06_LM'] != 0) { 
						echo round(((($lm_pb['jam_pb_06_LM']*60)+$lm_pb['menit_pb_06_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_pb['jam_pb_06_KM'] != 0 || $km_pb['menit_pb_06_KM'] != 0) {echo str_pad($km_pb['jam_pb_06_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_06_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_06_KM'] != 0 || $km_pb['menit_pb_06_KM'] != 0) { 
						echo round(((($km_pb['jam_pb_06_KM']*60)+$km_pb['menit_pb_06_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_pb['jam_pb_06_PT'] != 0 || $pt_pb['menit_pb_06_PT'] != 0) {echo str_pad($pt_pb['jam_pb_06_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_06_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_06_PT'] != 0 || $pt_pb['menit_pb_06_PT'] != 0) { 
						echo round(((($pt_pb['jam_pb_06_PT']*60)+$pt_pb['menit_pb_06_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_pb['jam_pb_06_KO'] != 0 || $ko_pb['menit_pb_06_KO'] != 0) {echo str_pad($ko_pb['jam_pb_06_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_06_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_06_KO'] != 0 || $ko_pb['menit_pb_06_KO'] != 0) { 
						echo round(((($ko_pb['jam_pb_06_KO']*60)+$ko_pb['menit_pb_06_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_pb['jam_pb_06_AP'] != 0 || $ap_pb['menit_pb_06_AP'] != 0) {echo str_pad($ap_pb['jam_pb_06_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_06_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_06_AP'] != 0 || $ap_pb['menit_pb_06_AP'] != 0) { 
						echo round(((($ap_pb['jam_pb_06_AP']*60)+$ap_pb['menit_pb_06_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_pb['jam_pb_06_PA'] != 0 || $pa_pb['menit_pb_06_PA'] != 0) {echo str_pad($pa_pb['jam_pb_06_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_06_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_06_PA'] != 0 || $pa_pb['menit_pb_06_PA'] != 0) { 
						echo round(((($pa_pb['jam_pb_06_PA']*60)+$pa_pb['menit_pb_06_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_pb['jam_pb_06_PM'] != 0 || $pm_pb['menit_pb_06_PM'] != 0) {echo str_pad($pm_pb['jam_pb_06_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_06_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_06_PM'] != 0 || $pm_pb['menit_pb_06_PM'] != 0) { 
						echo round(((($pm_pb['jam_pb_06_PM']*60)+$pm_pb['menit_pb_06_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_pb['jam_pb_06_GT'] != 0 || $gt_pb['menit_pb_06_GT'] != 0) {echo str_pad($gt_pb['jam_pb_06_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_06_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_06_GT'] != 0 || $gt_pb['menit_pb_06_GT'] != 0) { 
						echo round(((($gt_pb['jam_pb_06_GT']*60)+$gt_pb['menit_pb_06_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_pb['jam_pb_06_TG'] != 0 || $tg_pb['menit_pb_06_TG'] != 0) {echo str_pad($tg_pb['jam_pb_06_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_06_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_06_TG'] != 0 || $tg_pb['menit_pb_06_TG'] != 0) { 
						echo round(((($tg_pb['jam_pb_06_TG']*60)+$tg_pb['menit_pb_06_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_06'] != 0 || $sum_mesin_pb['menit_pb_06'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_06'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_06'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_06'] != 0 || $sum_mesin_pb['menit_pb_06'] != 0) { 
						echo round(((($sum_mesin_pb['jam_pb_06']*60)+$sum_mesin_pb['menit_pb_06'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Mesin 07 -->
                <tr>
                    <td align="center">'07</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_07_LM'] != 0 || $lm_pb['menit_pb_07_LM'] != 0) {echo str_pad($lm_pb['jam_pb_07_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_07_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_pb['jam_pb_07_LM'] != 0 || $lm_pb['menit_pb_07_LM'] != 0) { 
						echo round(((($lm_pb['jam_pb_07_LM']*60)+$lm_pb['menit_pb_07_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_pb['jam_pb_07_KM'] != 0 || $km_pb['menit_pb_07_KM'] != 0) {echo str_pad($km_pb['jam_pb_07_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_07_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_07_KM'] != 0 || $km_pb['menit_pb_07_KM'] != 0) { 
						echo round(((($km_pb['jam_pb_07_KM']*60)+$km_pb['menit_pb_07_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_pb['jam_pb_07_PT'] != 0 || $pt_pb['menit_pb_07_PT'] != 0) {echo str_pad($pt_pb['jam_pb_07_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_07_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_07_PT'] != 0 || $pt_pb['menit_pb_07_PT'] != 0) { 
						echo round(((($pt_pb['jam_pb_07_PT']*60)+$pt_pb['menit_pb_07_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_pb['jam_pb_07_KO'] != 0 || $ko_pb['menit_pb_07_KO'] != 0) {echo str_pad($ko_pb['jam_pb_07_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_07_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_07_KO'] != 0 || $ko_pb['menit_pb_07_KO'] != 0) { 
						echo round(((($ko_pb['jam_pb_07_KO']*60)+$ko_pb['menit_pb_07_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_pb['jam_pb_07_AP'] != 0 || $ap_pb['menit_pb_07_AP'] != 0) {echo str_pad($ap_pb['jam_pb_07_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_07_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_07_AP'] != 0 || $ap_pb['menit_pb_07_AP'] != 0) { 
						echo round(((($ap_pb['jam_pb_07_AP']*60)+$ap_pb['menit_pb_07_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_pb['jam_pb_07_PA'] != 0 || $pa_pb['menit_pb_07_PA'] != 0) {echo str_pad($pa_pb['jam_pb_07_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_07_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_07_PA'] != 0 || $pa_pb['menit_pb_07_PA'] != 0) { 
						echo round(((($pa_pb['jam_pb_07_PA']*60)+$pa_pb['menit_pb_07_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_pb['jam_pb_07_PM'] != 0 || $pm_pb['menit_pb_07_PM'] != 0) {echo str_pad($pm_pb['jam_pb_07_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_07_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_07_PM'] != 0 || $pm_pb['menit_pb_07_PM'] != 0) { 
						echo round(((($pm_pb['jam_pb_07_PM']*60)+$pm_pb['menit_pb_07_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_pb['jam_pb_07_GT'] != 0 || $gt_pb['menit_pb_07_GT'] != 0) {echo str_pad($gt_pb['jam_pb_07_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_07_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_07_GT'] != 0 || $gt_pb['menit_pb_07_GT'] != 0) { 
						echo round(((($gt_pb['jam_pb_07_GT']*60)+$gt_pb['menit_pb_07_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_pb['jam_pb_07_TG'] != 0 || $tg_pb['menit_pb_07_TG'] != 0) {echo str_pad($tg_pb['jam_pb_07_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_07_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_07_TG'] != 0 || $tg_pb['menit_pb_07_TG'] != 0) { 
						echo round(((($tg_pb['jam_pb_07_TG']*60)+$tg_pb['menit_pb_07_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_07'] != 0 || $sum_mesin_pb['menit_pb_07'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_07'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_07'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_07'] != 0 || $sum_mesin_pb['menit_pb_07'] != 0) { 
						echo round(((($sum_mesin_pb['jam_pb_07']*60)+$sum_mesin_pb['menit_pb_07'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Mesin 08 -->
                <tr>
                    <td align="center">'08</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_08_LM'] != 0 || $lm_pb['menit_pb_08_LM'] != 0) {echo str_pad($lm_pb['jam_pb_08_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_08_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_pb['jam_pb_08_LM'] != 0 || $lm_pb['menit_pb_08_LM'] != 0) { 
						echo round(((($lm_pb['jam_pb_08_LM']*60)+$lm_pb['menit_pb_08_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_pb['jam_pb_08_KM'] != 0 || $km_pb['menit_pb_08_KM'] != 0) {echo str_pad($km_pb['jam_pb_08_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_08_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_08_KM'] != 0 || $km_pb['menit_pb_08_KM'] != 0) { 
						echo round(((($km_pb['jam_pb_08_KM']*60)+$km_pb['menit_pb_08_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_pb['jam_pb_08_PT'] != 0 || $pt_pb['menit_pb_08_PT'] != 0) {echo str_pad($pt_pb['jam_pb_08_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_08_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_08_PT'] != 0 || $pt_pb['menit_pb_08_PT'] != 0) { 
						echo round(((($pt_pb['jam_pb_08_PT']*60)+$pt_pb['menit_pb_08_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_pb['jam_pb_08_KO'] != 0 || $ko_pb['menit_pb_08_KO'] != 0) {echo str_pad($ko_pb['jam_pb_08_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_08_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_08_KO'] != 0 || $ko_pb['menit_pb_08_KO'] != 0) { 
						echo round(((($ko_pb['jam_pb_08_KO']*60)+$ko_pb['menit_pb_08_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_pb['jam_pb_08_AP'] != 0 || $ap_pb['menit_pb_08_AP'] != 0) {echo str_pad($ap_pb['jam_pb_08_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_08_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_08_AP'] != 0 || $ap_pb['menit_pb_08_AP'] != 0) { 
						echo round(((($ap_pb['jam_pb_08_AP']*60)+$ap_pb['menit_pb_08_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_pb['jam_pb_08_PA'] != 0 || $pa_pb['menit_pb_08_PA'] != 0) {echo str_pad($pa_pb['jam_pb_08_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_08_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_08_PA'] != 0 || $pa_pb['menit_pb_08_PA'] != 0) { 
						echo round(((($pa_pb['jam_pb_08_PA']*60)+$pa_pb['menit_pb_08_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_pb['jam_pb_08_PM'] != 0 || $pm_pb['menit_pb_08_PM'] != 0) {echo str_pad($pm_pb['jam_pb_08_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_08_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_08_PM'] != 0 || $pm_pb['menit_pb_08_PM'] != 0) { 
						echo round(((($pm_pb['jam_pb_08_PM']*60)+$pm_pb['menit_pb_08_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_pb['jam_pb_08_GT'] != 0 || $gt_pb['menit_pb_08_GT'] != 0) {echo str_pad($gt_pb['jam_pb_08_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_08_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_08_GT'] != 0 || $gt_pb['menit_pb_08_GT'] != 0) { 
						echo round(((($gt_pb['jam_pb_08_GT']*60)+$gt_pb['menit_pb_08_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_pb['jam_pb_08_TG'] != 0 || $tg_pb['menit_pb_08_TG'] != 0) {echo str_pad($tg_pb['jam_pb_08_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_08_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_08_TG'] != 0 || $tg_pb['menit_pb_08_TG'] != 0) { 
						echo round(((($tg_pb['jam_pb_08_TG']*60)+$tg_pb['menit_pb_08_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_08'] != 0 || $sum_mesin_pb['menit_pb_08'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_08'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_08'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_08'] != 0 || $sum_mesin_pb['menit_pb_08'] != 0) { 
						echo round(((($sum_mesin_pb['jam_pb_08']*60)+$sum_mesin_pb['menit_pb_08'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
            <!-- End Potong Bulu -->
            <!-- Untuk Kolom Peach Skin -->
             <?php 
                    $query_peach9 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_05_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_05_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_04_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_04_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_03_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_03_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_02_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_02_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_01_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_01_LM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_peach9    = mysqli_query($cona,$query_peach9);
                                    $lm             = mysqli_fetch_assoc($stmt_peach9);
                    $query_peach8 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_05_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_05_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_04_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_04_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_03_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_03_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_02_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_02_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_01_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_01_KM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_peach8    = mysqli_query($cona,$query_peach8);
                                    $km             = mysqli_fetch_assoc($stmt_peach8);
                    $query_peach7 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_05_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_05_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_04_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_04_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_03_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_03_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_02_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_02_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_01_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_01_PT
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_peach7    = mysqli_query($cona,$query_peach7);
                                    $pt             = mysqli_fetch_assoc($stmt_peach7);
                    $query_peach6 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_05_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_05_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_04_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_04_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_03_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 

                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_03_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_02_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_02_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_01_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_01_KO
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_peach6    = mysqli_query($cona,$query_peach6);
                                    $ko             = mysqli_fetch_assoc($stmt_peach6);
                    $query_peach5 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_05_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_05_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_04_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_04_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_03_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_03_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_02_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_02_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_01_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_01_AP
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_peach5    = mysqli_query($cona,$query_peach5);
                                    $ap             = mysqli_fetch_assoc($stmt_peach5);
                    $query_peach4 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_05_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_05_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_04_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_04_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_03_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_03_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_02_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_02_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_01_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_01_PA
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_peach4    = mysqli_query($cona,$query_peach4);
                                    $pa             = mysqli_fetch_assoc($stmt_peach4);
                    $query_peach3 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_05_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_05_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_04_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_04_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_03_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_03_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_02_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_02_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_01_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_01_PM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_peach3    = mysqli_query($cona,$query_peach3);
                                    $pm             = mysqli_fetch_assoc($stmt_peach3);
                    $query_peach2 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_05_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_05_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_04_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_04_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_03_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_03_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_02_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_02_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_01_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_01_GT
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_peach2    = mysqli_query($cona,$query_peach2);
                                    $gt             = mysqli_fetch_assoc($stmt_peach2);
                    $query_peach1 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_05_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_05_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_04_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_04_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_03_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_03_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_02_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_02_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_01_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_01_TG
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_peach1= mysqli_query($cona,$query_peach1);
                        $tg= mysqli_fetch_assoc($stmt_peach1);
                    // Total Peach
                    $query_mesin_peach1 = "SELECT
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_05,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU105' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_05,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_04,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU104' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_04,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_03,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU103' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_03,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_02,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_02,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_peach_01,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('SUE1', 'SUE2', 'SUE3', 'SUE4') 
                                                    AND mesin = 'P3SU101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_peach_01
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_peach1= mysqli_query($cona,$query_mesin_peach1);
                        $sum_mesin_peach= mysqli_fetch_assoc($stmt_mesin_peach1);
                                        ?>
                <!-- Untuk Mesin 01 -->
                    <tr>
                    <td rowspan="5" align="left"><strong>PEACH SKIN</strong></td>
                    <td align="center">'01</td>
                    <td align="center"><?php if ($lm['jam_peach_01_LM'] != 0 || $lm['menit_peach_01_LM'] != 0) {echo str_pad($lm['jam_peach_01_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_01_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm['jam_peach_01_LM'] != 0 || $lm['menit_peach_01_LM'] != 0) { 
						echo round(((($lm['jam_peach_01_LM']*60)+$lm['menit_peach_01_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($km['jam_peach_01_KM'] != 0 || $km['menit_peach_01_KM'] != 0) {echo str_pad($km['jam_peach_01_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_01_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km['jam_peach_01_KM'] != 0 || $km['menit_peach_01_KM'] != 0) { 
						echo round(((($km['jam_peach_01_KM']*60)+$km['menit_peach_01_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pt['jam_peach_01_PT'] != 0 || $pt['menit_peach_01_PT'] != 0) {echo str_pad($pt['jam_peach_01_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_01_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt['jam_peach_01_PT'] != 0 || $pt['menit_peach_01_PT'] != 0) { 
						echo round(((($pt['jam_peach_01_PT']*60)+$pt['menit_peach_01_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ko['jam_peach_01_KO'] != 0 || $ko['menit_peach_01_KO'] != 0) {echo str_pad($ko['jam_peach_01_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_01_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko['jam_peach_01_KO'] != 0 || $ko['menit_peach_01_KO'] != 0) { 
						echo round(((($ko['jam_peach_01_KO']*60)+$ko['menit_peach_01_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ap['jam_peach_01_AP'] != 0 || $ap['menit_peach_01_AP'] != 0) {echo str_pad($ap['jam_peach_01_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_01_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap['jam_peach_01_AP'] != 0 || $ap['menit_peach_01_AP'] != 0) { 
						echo round(((($ap['jam_peach_01_AP']*60)+$ap['menit_peach_01_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pa['jam_peach_01_PA'] != 0 || $pa['menit_peach_01_PA'] != 0) {echo str_pad($pa['jam_peach_01_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_01_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa['jam_peach_01_PA'] != 0 || $pa['menit_peach_01_PA'] != 0) { 
						echo round(((($pa['jam_peach_01_PA']*60)+$pa['menit_peach_01_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pm['jam_peach_01_PM'] != 0 || $pm['menit_peach_01_PM'] != 0) {echo str_pad($pm['jam_peach_01_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_01_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm['jam_peach_01_PM'] != 0 || $pm['menit_peach_01_PM'] != 0) { 
						echo round(((($pm['jam_peach_01_PM']*60)+$pm['menit_peach_01_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($gt['jam_peach_01_GT'] != 0 || $gt['menit_peach_01_GT'] != 0) {echo str_pad($gt['jam_peach_01_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_01_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt['jam_peach_01_GT'] != 0 || $gt['menit_peach_01_GT'] != 0) { 
						echo round(((($gt['jam_peach_01_GT']*60)+$gt['menit_peach_01_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($tg['jam_peach_01_TG'] != 0 || $tg['menit_peach_01_TG'] != 0) {echo str_pad($tg['jam_peach_01_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_01_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg['jam_peach_01_TG'] != 0 || $tg['menit_peach_01_TG'] != 0) { 
						echo round(((($tg['jam_peach_01_TG']*60)+$tg['menit_peach_01_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_01'] != 0 || $sum_mesin_peach['menit_peach_01'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_01'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_01'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_01'] != 0 || $sum_mesin_peach['menit_peach_01'] != 0) { 
						echo round(((($sum_mesin_peach['jam_peach_01']*60)+$sum_mesin_peach['menit_peach_01'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                </tr>
                <!-- Untuk Mesin 02 -->
                    <tr>
                    <td align="center">'02</td>
                    <td align="center"><?php if ($lm['jam_peach_02_LM'] != 0 || $lm['menit_peach_02_LM'] != 0) {echo str_pad($lm['jam_peach_02_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_02_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm['jam_peach_02_LM'] != 0 || $lm['menit_peach_02_LM'] != 0) { 
						echo round(((($lm['jam_peach_02_LM']*60)+$lm['menit_peach_02_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km['jam_peach_02_KM'] != 0 || $km['menit_peach_02_KM'] != 0) {echo str_pad($km['jam_peach_02_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_02_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km['jam_peach_02_KM'] != 0 || $km['menit_peach_02_KM'] != 0) { 
						echo round(((($km['jam_peach_02_KM']*60)+$km['menit_peach_02_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt['jam_peach_02_PT'] != 0 || $pt['menit_peach_02_PT'] != 0) {echo str_pad($pt['jam_peach_02_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_02_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt['jam_peach_02_PT'] != 0 || $pt['menit_peach_02_PT'] != 0) { 
						echo round(((($pt['jam_peach_02_PT']*60)+$pt['menit_peach_02_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko['jam_peach_02_KO'] != 0 || $ko['menit_peach_02_KO'] != 0) {echo str_pad($ko['jam_peach_02_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_02_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko['jam_peach_02_KO'] != 0 || $ko['menit_peach_02_KO'] != 0) { 
						echo round(((($ko['jam_peach_02_KO']*60)+$ko['menit_peach_02_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap['jam_peach_02_AP'] != 0 || $ap['menit_peach_02_AP'] != 0) {echo str_pad($ap['jam_peach_02_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_02_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap['jam_peach_02_AP'] != 0 || $ap['menit_peach_02_AP'] != 0) { 
						echo round(((($ap['jam_peach_02_AP']*60)+$ap['menit_peach_02_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa['jam_peach_02_PA'] != 0 || $pa['menit_peach_02_PA'] != 0) {echo str_pad($pa['jam_peach_02_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_02_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa['jam_peach_02_PA'] != 0 || $pa['menit_peach_02_PA'] != 0) { 
						echo round(((($pa['jam_peach_02_PA']*60)+$pa['menit_peach_02_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm['jam_peach_02_PM'] != 0 || $pm['menit_peach_02_PM'] != 0) {echo str_pad($pm['jam_peach_02_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_02_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm['jam_peach_02_PM'] != 0 || $pm['menit_peach_02_PM'] != 0) { 
						echo round(((($pm['jam_peach_02_PM']*60)+$pm['menit_peach_02_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt['jam_peach_02_GT'] != 0 || $gt['menit_peach_02_GT'] != 0) {echo str_pad($gt['jam_peach_02_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_02_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt['jam_peach_02_GT'] != 0 || $gt['menit_peach_02_GT'] != 0) { 
						echo round(((($gt['jam_peach_02_GT']*60)+$gt['menit_peach_02_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg['jam_peach_02_TG'] != 0 || $tg['menit_peach_02_TG'] != 0) {echo str_pad($tg['jam_peach_02_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_02_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg['jam_peach_02_TG'] != 0 || $tg['menit_peach_02_TG'] != 0) { 
						echo round(((($tg['jam_peach_02_TG']*60)+$tg['menit_peach_02_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_02'] != 0 || $sum_mesin_peach['menit_peach_02'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_02'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_02'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_02'] != 0 || $sum_mesin_peach['menit_peach_02'] != 0) { 
						echo round(((($sum_mesin_peach['jam_peach_02']*60)+$sum_mesin_peach['menit_peach_02'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Untuk Mesin 03 -->
                    <tr>
                    <td align="center">'03</td>
                    <td align="center"><?php if ($lm['jam_peach_03_LM'] != 0 || $lm['menit_peach_03_LM'] != 0) {echo str_pad($lm['jam_peach_03_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_03_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm['jam_peach_03_LM'] != 0 || $lm['menit_peach_03_LM'] != 0) { 
						echo round(((($lm['jam_peach_03_LM']*60)+$lm['menit_peach_03_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km['jam_peach_03_KM'] != 0 || $km['menit_peach_03_KM'] != 0) {echo str_pad($km['jam_peach_03_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_03_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km['jam_peach_03_KM'] != 0 || $km['menit_peach_03_KM'] != 0) { 
						echo round(((($km['jam_peach_03_KM']*60)+$km['menit_peach_03_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt['jam_peach_03_PT'] != 0 || $pt['menit_peach_03_PT'] != 0) {echo str_pad($pt['jam_peach_03_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_03_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt['jam_peach_03_PT'] != 0 || $pt['menit_peach_03_PT'] != 0) { 
						echo round(((($pt['jam_peach_03_PT']*60)+$pt['menit_peach_03_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko['jam_peach_03_KO'] != 0 || $ko['menit_peach_03_KO'] != 0) {echo str_pad($ko['jam_peach_03_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_03_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko['jam_peach_03_KO'] != 0 || $ko['menit_peach_03_KO'] != 0) { 
						echo round(((($ko['jam_peach_03_KO']*60)+$ko['menit_peach_03_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap['jam_peach_03_AP'] != 0 || $ap['menit_peach_03_AP'] != 0) {echo str_pad($ap['jam_peach_03_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_03_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap['jam_peach_03_AP'] != 0 || $ap['menit_peach_03_AP'] != 0) { 
						echo round(((($ap['jam_peach_03_AP']*60)+$ap['menit_peach_03_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa['jam_peach_03_PA'] != 0 || $pa['menit_peach_03_PA'] != 0) {echo str_pad($pa['jam_peach_03_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_03_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa['jam_peach_03_PA'] != 0 || $pa['menit_peach_03_PA'] != 0) { 
						echo round(((($pa['jam_peach_03_PA']*60)+$pa['menit_peach_03_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm['jam_peach_03_PM'] != 0 || $pm['menit_peach_03_PM'] != 0) {echo str_pad($pm['jam_peach_03_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_03_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm['jam_peach_03_PM'] != 0 || $pm['menit_peach_03_PM'] != 0) { 
						echo round(((($pm['jam_peach_03_PM']*60)+$pm['menit_peach_03_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt['jam_peach_03_GT'] != 0 || $gt['menit_peach_03_GT'] != 0) {echo str_pad($gt['jam_peach_03_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_03_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt['jam_peach_03_GT'] != 0 || $gt['menit_peach_03_GT'] != 0) { 
						echo round(((($gt['jam_peach_03_GT']*60)+$gt['menit_peach_03_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg['jam_peach_03_TG'] != 0 || $tg['menit_peach_03_TG'] != 0) {echo str_pad($tg['jam_peach_03_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_03_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg['jam_peach_03_TG'] != 0 || $tg['menit_peach_03_TG'] != 0) { 
						echo round(((($tg['jam_peach_03_TG']*60)+$tg['menit_peach_03_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_03'] != 0 || $sum_mesin_peach['menit_peach_03'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_03'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_03'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_03'] != 0 || $sum_mesin_peach['menit_peach_03'] != 0) { 
						echo round(((($sum_mesin_peach['jam_peach_03']*60)+$sum_mesin_peach['menit_peach_03'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Untuk Mesin 04 -->
                    <tr>
                    <td align="center">'04</td>
                    <td align="center"><?php if ($lm['jam_peach_04_LM'] != 0 || $lm['menit_peach_04_LM'] != 0) {echo str_pad($lm['jam_peach_04_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_04_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm['jam_peach_04_LM'] != 0 || $lm['menit_peach_04_LM'] != 0) { 
						echo round(((($lm['jam_peach_04_LM']*60)+$lm['menit_peach_04_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km['jam_peach_04_KM'] != 0 || $km['menit_peach_04_KM'] != 0) {echo str_pad($km['jam_peach_04_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_04_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km['jam_peach_04_KM'] != 0 || $km['menit_peach_04_KM'] != 0) { 
						echo round(((($km['jam_peach_04_KM']*60)+$km['menit_peach_04_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt['jam_peach_04_PT'] != 0 || $pt['menit_peach_04_PT'] != 0) {echo str_pad($pt['jam_peach_04_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_04_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt['jam_peach_04_PT'] != 0 || $pt['menit_peach_04_PT'] != 0) { 
						echo round(((($pt['jam_peach_04_PT']*60)+$pt['menit_peach_04_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko['jam_peach_04_KO'] != 0 || $ko['menit_peach_04_KO'] != 0) {echo str_pad($ko['jam_peach_04_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_04_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko['jam_peach_04_KO'] != 0 || $ko['menit_peach_04_KO'] != 0) { 
						echo round(((($ko['jam_peach_04_KO']*60)+$ko['menit_peach_04_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap['jam_peach_04_AP'] != 0 || $ap['menit_peach_04_AP'] != 0) {echo str_pad($ap['jam_peach_04_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_04_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap['jam_peach_04_AP'] != 0 || $ap['menit_peach_04_AP'] != 0) { 
						echo round(((($ap['jam_peach_04_AP']*60)+$ap['menit_peach_04_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa['jam_peach_04_PA'] != 0 || $pa['menit_peach_04_PA'] != 0) {echo str_pad($pa['jam_peach_04_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_04_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa['jam_peach_04_PA'] != 0 || $pa['menit_peach_04_PA'] != 0) { 
						echo round(((($pa['jam_peach_04_PA']*60)+$pa['menit_peach_04_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm['jam_peach_04_PM'] != 0 || $pm['menit_peach_04_PM'] != 0) {echo str_pad($pm['jam_peach_04_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_04_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm['jam_peach_04_PM'] != 0 || $pm['menit_peach_04_PM'] != 0) { 
						echo round(((($pm['jam_peach_04_PM']*60)+$pm['menit_peach_04_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt['jam_peach_04_GT'] != 0 || $gt['menit_peach_04_GT'] != 0) {echo str_pad($gt['jam_peach_04_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_04_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt['jam_peach_04_GT'] != 0 || $gt['menit_peach_04_GT'] != 0) { 
						echo round(((($gt['jam_peach_04_GT']*60)+$gt['menit_peach_04_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg['jam_peach_04_TG'] != 0 || $tg['menit_peach_04_TG'] != 0) {echo str_pad($tg['jam_peach_04_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_04_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg['jam_peach_04_TG'] != 0 || $tg['menit_peach_04_TG'] != 0) { 
						echo round(((($tg['jam_peach_04_TG']*60)+$tg['menit_peach_04_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_04'] != 0 || $sum_mesin_peach['menit_peach_04'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_04'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_04'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_04'] != 0 || $sum_mesin_peach['menit_peach_04'] != 0) { 
						echo round(((($sum_mesin_peach['jam_peach_04']*60)+$sum_mesin_peach['menit_peach_04'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <!-- Untuk Mesin 05 -->
                    <tr>
                    <td align="center">'05</td>
                    <td align="center"><?php if ($lm['jam_peach_05_LM'] != 0 || $lm['menit_peach_05_LM'] != 0) {echo str_pad($lm['jam_peach_05_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_05_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm['jam_peach_05_LM'] != 0 || $lm['menit_peach_05_LM'] != 0) { 
						echo round(((($lm['jam_peach_05_LM']*60)+$lm['menit_peach_05_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km['jam_peach_05_KM'] != 0 || $km['menit_peach_05_KM'] != 0) {echo str_pad($km['jam_peach_05_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_05_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km['jam_peach_05_KM'] != 0 || $km['menit_peach_05_KM'] != 0) { 
						echo round(((($km['jam_peach_05_KM']*60)+$km['menit_peach_05_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt['jam_peach_05_PT'] != 0 || $pt['menit_peach_05_PT'] != 0) {echo str_pad($pt['jam_peach_05_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_05_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt['jam_peach_05_PT'] != 0 || $pt['menit_peach_05_PT'] != 0) { 
						echo round(((($pt['jam_peach_05_PT']*60)+$pt['menit_peach_05_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko['jam_peach_05_KO'] != 0 || $ko['menit_peach_05_KO'] != 0) {echo str_pad($ko['jam_peach_05_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_05_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko['jam_peach_05_KO'] != 0 || $ko['menit_peach_05_KO'] != 0) { 
						echo round(((($ko['jam_peach_05_KO']*60)+$ko['menit_peach_05_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap['jam_peach_05_AP'] != 0 || $ap['menit_peach_05_AP'] != 0) {echo str_pad($ap['jam_peach_05_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_05_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap['jam_peach_05_AP'] != 0 || $ap['menit_peach_05_AP'] != 0) { 
						echo round(((($ap['jam_peach_05_AP']*60)+$ap['menit_peach_05_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa['jam_peach_05_PA'] != 0 || $pa['menit_peach_05_PA'] != 0) {echo str_pad($pa['jam_peach_05_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_05_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa['jam_peach_05_PA'] != 0 || $pa['menit_peach_05_PA'] != 0) { 
						echo round(((($pa['jam_peach_05_PA']*60)+$pa['menit_peach_05_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm['jam_peach_05_PM'] != 0 || $pm['menit_peach_05_PM'] != 0) {echo str_pad($pm['jam_peach_05_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_05_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm['jam_peach_05_PM'] != 0 || $pm['menit_peach_05_PM'] != 0) { 
						echo round(((($pm['jam_peach_05_PM']*60)+$pm['menit_peach_05_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt['jam_peach_05_GT'] != 0 || $gt['menit_peach_05_GT'] != 0) {echo str_pad($gt['jam_peach_05_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_05_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt['jam_peach_05_GT'] != 0 || $gt['menit_peach_05_GT'] != 0) { 
						echo round(((($gt['jam_peach_05_GT']*60)+$gt['menit_peach_05_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg['jam_peach_05_TG'] != 0 || $tg['menit_peach_05_TG'] != 0) {echo str_pad($tg['jam_peach_05_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_05_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg['jam_peach_05_TG'] != 0 || $tg['menit_peach_05_TG'] != 0) { 
						echo round(((($tg['jam_peach_05_TG']*60)+$tg['menit_peach_05_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_05'] != 0 || $sum_mesin_peach['menit_peach_05'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_05'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_05'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_05'] != 0 || $sum_mesin_peach['menit_peach_05'] != 0) { 
						echo round(((($sum_mesin_peach['jam_peach_05']*60)+$sum_mesin_peach['menit_peach_05'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <!-- <td align="center">Ini untuk total</td> -->
                </tr>

            <!-- End Peach -->
            <!-- Untuk Kolom Airo -->
                <tr>
                    <?php 
                    $query_airo9 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_01_airo_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_01_airo_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_02_airo_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_02_airo_TG
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_airo9    = mysqli_query($cona,$query_airo9);
                                    $tg_airo             = mysqli_fetch_assoc($stmt_airo9);
                    $query_airo8 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('AIR1')
                                                AND mesin = 'P3AR101' 
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_01_airo_GT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'GT' 
                                                AND kode_operation IN ('AIR1')
                                                AND mesin = 'P3AR101' 
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_01_airo_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_02_airo_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_02_airo_GT
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_airo8    = mysqli_query($cona,$query_airo8);
                                    $gt_airo             = mysqli_fetch_assoc($stmt_airo8);
                    $query_airo7 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_01_airo_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_01_airo_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_02_airo_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_02_airo_PM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_airo7    = mysqli_query($cona,$query_airo7);
                                    $pm_airo             = mysqli_fetch_assoc($stmt_airo7);
                    $query_airo6 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_01_airo_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_01_airo_PA,
                                            SUM(

                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_02_airo_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_02_airo_PA
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_airo6    = mysqli_query($cona,$query_airo6);
                                    $pa_airo             = mysqli_fetch_assoc($stmt_airo6);
                    $query_airo5 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_01_airo_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_01_airo_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_02_airo_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_02_airo_AP
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_airo5    = mysqli_query($cona,$query_airo5);
                                    $ap_airo             = mysqli_fetch_assoc($stmt_airo5);
                    $query_airo4 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('AIR1')
                                                AND mesin = 'P3AR101' 
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_01_airo_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('AIR1')
                                                AND mesin = 'P3AR101' 
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_01_airo_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('AIR1')
                                                AND mesin = 'P3AR102' 
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_02_airo_KO,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'KO' 
                                                AND kode_operation IN ('AIR1')
                                                AND mesin = 'P3AR102' 
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_02_airo_KO
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_airo4    = mysqli_query($cona,$query_airo4);
                                    $ko_airo             = mysqli_fetch_assoc($stmt_airo4);
                    $query_airo3 = "SELECT
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('AIR1')
                                                AND mesin = 'P3AR101' 
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_01_airo_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('AIR1')
                                                AND mesin = 'P3AR101' 
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_01_airo_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('AIR1')
                                                AND mesin = 'P3AR102' 
                                                THEN FLOOR(durasi_jam_stop) 
                                                ELSE 0 
                                            END
                                            ) AS jam_02_airo_PT,
                                        SUM(
                                            CASE 
                                                WHEN kode_stop = 'PT' 
                                                AND kode_operation IN ('AIR1')
                                                AND mesin = 'P3AR102' 
                                                THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ELSE 0 
                                            END
                                            ) AS menit_02_airo_PT
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_airo3    = mysqli_query($cona,$query_airo3);
                                    $pt_airo             = mysqli_fetch_assoc($stmt_airo3);
                    $query_airo2 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_01_airo_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_01_airo_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_02_airo_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_02_airo_KM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_airo2    = mysqli_query($cona,$query_airo2);
                                    $km_airo             = mysqli_fetch_assoc($stmt_airo2);
                    $query_airo1 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_01_airo_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_01_airo_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_02_airo_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_02_airo_LM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_airo1    = mysqli_query($cona,$query_airo1);
                                    $lm_airo             = mysqli_fetch_assoc($stmt_airo1);
                            // Total airo
                    $query_mesin_airo = "SELECT
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_01_airo,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR101' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_01_airo,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_02_airo,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('AIR1')
                                                    AND mesin = 'P3AR102' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_02_airo
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_airo= mysqli_query($cona,$query_mesin_airo);
                        $sum_mesin_airo= mysqli_fetch_assoc($stmt_mesin_airo);
                        ?>
                    <td rowspan="2" align="left"><strong>AIRO</strong></td>
                    <td align="center">'01</td>
                    <td align="center"><?php if ($lm_airo['jam_01_airo_LM'] != 0 || $lm_airo['menit_01_airo_LM'] != 0) {echo str_pad($lm_airo['jam_01_airo_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_airo['menit_01_airo_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_airo['jam_01_airo_LM'] != 0 || $lm_airo['menit_01_airo_LM'] != 0) { 
						echo round(((($lm_airo['jam_01_airo_LM']*60)+$lm_airo['menit_01_airo_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($km_airo['jam_01_airo_KM'] != 0 || $km_airo['menit_01_airo_KM'] != 0) {echo str_pad($km_airo['jam_01_airo_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_airo['menit_01_airo_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($$km_airo['jam_01_airo_KM'] != 0 || $km_airo['menit_01_airo_KM'] != 0) { 
						echo round(((($km_airo['jam_01_airo_KM']*60)+$km_airo['menit_01_airo_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pt_airo['jam_01_airo_PT'] != 0 || $pt_airo['menit_01_airo_PT'] != 0) {echo str_pad($pt_airo['jam_01_airo_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_airo['menit_01_airo_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_airo['jam_01_airo_PT'] != 0 || $pt_airo['menit_01_airo_PT'] != 0) { 
						echo round(((($pt_airo['jam_01_airo_PT']*60)+$pt_airo['menit_01_airo_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ko_airo['jam_01_airo_KO'] != 0 || $ko_airo['menit_01_airo_KO'] != 0) {echo str_pad($ko_airo['jam_01_airo_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_airo['menit_01_airo_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_airo['jam_01_airo_KO'] != 0 || $ko_airo['menit_01_airo_KO'] != 0) { 
						echo round(((($ko_airo['jam_01_airo_KO']*60)+$ko_airo['menit_01_airo_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ap_airo['jam_01_airo_AP'] != 0 || $ap_airo['menit_01_airo_AP'] != 0) {echo str_pad($ap_airo['jam_01_airo_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_airo['menit_01_airo_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_airo['jam_01_airo_AP'] != 0 || $ap_airo['menit_01_airo_AP'] != 0) { 
						echo round(((($ap_airo['jam_01_airo_AP']*60)+$ap_airo['menit_01_airo_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pa_airo['jam_01_airo_PA'] != 0 || $pa_airo['menit_01_airo_PA'] != 0) {echo str_pad($pa_airo['jam_01_airo_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_airo['menit_01_airo_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_airo['jam_01_airo_PA'] != 0 || $pa_airo['menit_01_airo_PA'] != 0) { 
						echo round(((($pa_airo['jam_01_airo_PA']*60)+$pa_airo['menit_01_airo_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pm_airo['jam_01_airo_PM'] != 0 || $pm_airo['menit_01_airo_PM'] != 0) {echo str_pad($pm_airo['jam_01_airo_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_airo['menit_01_airo_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_airo['jam_01_airo_PM'] != 0 || $pm_airo['menit_01_airo_PM'] != 0) { 
						echo round(((($pm_airo['jam_01_airo_PM']*60)+$pm_airo['menit_01_airo_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($gt_airo['jam_01_airo_GT'] != 0 || $gt_airo['menit_01_airo_GT'] != 0) {echo str_pad($gt_airo['jam_01_airo_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_airo['menit_01_airo_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_airo['jam_01_airo_GT'] != 0 || $gt_airo['menit_01_airo_GT'] != 0) { 
						echo round(((($gt_airo['jam_01_airo_GT']*60)+$gt_airo['menit_01_airo_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($tg_airo['jam_01_airo_TG'] != 0 || $tg_airo['menit_01_airo_TG'] != 0) {echo str_pad($tg_airo['jam_01_airo_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_airo['menit_01_airo_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_airo['jam_01_airo_TG'] != 0 || $tg_airo['menit_01_airo_TG'] != 0) { 
						echo round(((($tg_airo['jam_01_airo_TG']*60)+$tg_airo['menit_01_airo_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($sum_mesin_airo['jam_01_airo'] != 0 || $sum_mesin_airo['menit_01_airo'] != 0) {echo str_pad($sum_mesin_airo['jam_01_airo'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_airo['menit_01_airo'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_airo['jam_01_airo'] != 0 || $sum_mesin_airo['menit_01_airo'] != 0) { 
						echo round(((($sum_mesin_airo['jam_01_airo']*60)+$sum_mesin_airo['menit_01_airo'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                </tr>
                <tr>
                    <td align="center">'02</td>
                    <td align="center"><?php if ($lm_airo['jam_02_airo_LM'] != 0 || $lm_airo['menit_02_airo_LM'] != 0) {echo str_pad($lm_airo['jam_02_airo_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_airo['menit_02_airo_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_airo['jam_02_airo_LM'] != 0 || $lm_airo['menit_02_airo_LM'] != 0) { 
						echo round(((($lm_airo['jam_02_airo_LM']*60)+$lm_airo['menit_02_airo_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_airo['jam_02_airo_KM'] != 0 || $km_airo['menit_02_airo_KM'] != 0) {echo str_pad($km_airo['jam_02_airo_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_airo['menit_02_airo_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($$km_airo['jam_02_airo_KM'] != 0 || $km_airo['menit_02_airo_KM'] != 0) { 
						echo round(((($km_airo['jam_02_airo_KM']*60)+$km_airo['menit_02_airo_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_airo['jam_02_airo_PT'] != 0 || $pt_airo['menit_02_airo_PT'] != 0) {echo str_pad($pt_airo['jam_02_airo_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_airo['menit_02_airo_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_airo['jam_02_airo_PT'] != 0 || $pt_airo['menit_02_airo_PT'] != 0) { 
						echo round(((($pt_airo['jam_02_airo_PT']*60)+$pt_airo['menit_02_airo_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_airo['jam_02_airo_KO'] != 0 || $ko_airo['menit_02_airo_KO'] != 0) {echo str_pad($ko_airo['jam_02_airo_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_airo['menit_02_airo_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_airo['jam_02_airo_KO'] != 0 || $ko_airo['menit_02_airo_KO'] != 0) { 
						echo round(((($ko_airo['jam_02_airo_KO']*60)+$ko_airo['menit_02_airo_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_airo['jam_02_airo_AP'] != 0 || $ap_airo['menit_02_airo_AP'] != 0) {echo str_pad($ap_airo['jam_02_airo_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_airo['menit_02_airo_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_airo['jam_02_airo_AP'] != 0 || $ap_airo['menit_02_airo_AP'] != 0) { 
						echo round(((($ap_airo['jam_02_airo_AP']*60)+$ap_airo['menit_02_airo_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_airo['jam_02_airo_PA'] != 0 || $pa_airo['menit_02_airo_PA'] != 0) {echo str_pad($pa_airo['jam_02_airo_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_airo['menit_02_airo_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_airo['jam_02_airo_PA'] != 0 || $pa_airo['menit_02_airo_PA'] != 0) { 
						echo round(((($pa_airo['jam_02_airo_PA']*60)+$pa_airo['menit_02_airo_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_airo['jam_02_airo_PM'] != 0 || $pm_airo['menit_02_airo_PM'] != 0) {echo str_pad($pm_airo['jam_02_airo_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_airo['menit_02_airo_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_airo['jam_02_airo_PM'] != 0 || $pm_airo['menit_02_airo_PM'] != 0) { 
						echo round(((($pm_airo['jam_02_airo_PM']*60)+$pm_airo['menit_02_airo_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_airo['jam_02_airo_GT'] != 0 || $gt_airo['menit_02_airo_GT'] != 0) {echo str_pad($gt_airo['jam_02_airo_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_airo['menit_02_airo_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_airo['jam_02_airo_GT'] != 0 || $gt_airo['menit_02_airo_GT'] != 0) { 
						echo round(((($gt_airo['jam_02_airo_GT']*60)+$gt_airo['menit_02_airo_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_airo['jam_02_airo_TG'] != 0 || $tg_airo['menit_02_airo_TG'] != 0) {echo str_pad($tg_airo['jam_02_airo_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_airo['menit_02_airo_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_airo['jam_02_airo_TG'] != 0 || $tg_airo['menit_02_airo_TG'] != 0) { 
						echo round(((($tg_airo['jam_02_airo_TG']*60)+$tg_airo['menit_02_airo_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_airo['jam_02_airo'] != 0 || $sum_mesin_airo['menit_02_airo'] != 0) {echo str_pad($sum_mesin_airo['jam_02_airo'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_airo['menit_02_airo'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_airo['jam_02_airo'] != 0 || $sum_mesin_airo['menit_02_airo'] != 0) { 
						echo round(((($sum_mesin_airo['jam_02_airo']*60)+$sum_mesin_airo['menit_02_airo'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
            <!-- End Airo -->
            <!-- Untuk Kolom Anti Piling1 -->
                <tr>
                    <?php $query_ap9 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_05_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_05_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 

                                                END
                                                ) AS jam_ap_04_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_04_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_03_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_03_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_02_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_02_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_01_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_01_LM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_ap9    = mysqli_query($cona,$query_ap9);
                                    $lm_ap             = mysqli_fetch_assoc($stmt_ap9);
                    $query_ap8 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_05_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_05_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_04_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_04_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_03_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_03_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_02_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_02_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_01_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_01_KM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_ap8    = mysqli_query($cona,$query_ap8);
                                    $km_ap             = mysqli_fetch_assoc($stmt_ap8);
                    $query_ap7 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_05_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_05_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_04_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_04_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_03_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_03_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_02_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_02_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_01_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_01_PT
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_ap7    = mysqli_query($cona,$query_ap7);
                                    $pt_ap             = mysqli_fetch_assoc($stmt_ap7);
                    $query_ap6 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_05_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_05_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_04_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_04_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_03_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_03_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_02_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_02_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_01_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_01_KO
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_ap6    = mysqli_query($cona,$query_ap6);
                                    $ko_ap             = mysqli_fetch_assoc($stmt_ap6);
                    $query_ap5 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_05_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_05_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_04_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_04_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_03_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_03_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_02_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_02_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_01_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_01_AP
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_ap5    = mysqli_query($cona,$query_ap5);
                                    $ap_ap             = mysqli_fetch_assoc($stmt_ap5);
                    $query_ap4 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_05_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_05_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_04_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_04_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_03_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_03_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_02_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_02_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_01_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_01_PA
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_ap4    = mysqli_query($cona,$query_ap4);
                                    $pa_ap             = mysqli_fetch_assoc($stmt_ap4);
                    $query_ap3 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_05_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_05_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_04_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_04_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_03_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_03_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_02_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_02_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_01_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_01_PM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_ap3    = mysqli_query($cona,$query_ap3);
                                    $pm_ap             = mysqli_fetch_assoc($stmt_ap3);
                    $query_ap2 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_05_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_05_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_04_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_04_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_03_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_03_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_02_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_02_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_01_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_01_GT
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_ap2    = mysqli_query($cona,$query_ap2);
                                    $gt_ap             = mysqli_fetch_assoc($stmt_ap2);
                    $query_ap1 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_05_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_05_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_04_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_04_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_03_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_03_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_02_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_02_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_01_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_01_TG
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_ap1= mysqli_query($cona,$query_ap1);
                        $tg_ap= mysqli_fetch_assoc($stmt_ap1);
                    // Total ap
                    $query_mesin_ap1 = "SELECT
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_05,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%05%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_05,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_04,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%04%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_04,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_03,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%03%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_03,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_02,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%02%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_02,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%' 
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_ap_01,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('TDR1') 
                                                    AND mesin LIKE '%01%' 
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_ap_01
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_ap1= mysqli_query($cona,$query_mesin_ap1);
                        $sum_mesin_ap= mysqli_fetch_assoc($stmt_mesin_ap1);
                                        ?>
                    <td rowspan="4" align="left"><strong>ANTI PILLING 01</strong></td>
                    <td align="center">'01</td>
                    <td align="center"><?php if ($lm_ap['jam_ap_01_LM'] != 0 || $lm_ap['menit_ap_01_LM'] != 0) {echo str_pad($lm_ap['jam_ap_01_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_01_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_ap['jam_ap_01_LM'] != 0 || $lm_ap['menit_ap_01_LM'] != 0) { 
						echo round(((($lm_ap['jam_ap_01_LM']*60)+$lm_ap['menit_ap_01_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($km_ap['jam_ap_01_KM'] != 0 || $km_ap['menit_ap_01_KM'] != 0) {echo str_pad($km_ap['jam_ap_01_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_01_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_ap['jam_ap_01_KM'] != 0 || $km_ap['menit_ap_01_KM'] != 0) { 
						echo round(((($km_ap['jam_ap_01_KM']*60)+$km_ap['menit_ap_01_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pt_ap['jam_ap_01_PT'] != 0 || $pt_ap['menit_ap_01_PT'] != 0) {echo str_pad($pt_ap['jam_ap_01_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_01_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_ap['jam_ap_01_PT'] != 0 || $pt_ap['menit_ap_01_PT'] != 0) { 
						echo round(((($pt_ap['jam_ap_01_PT']*60)+$pt_ap['menit_ap_01_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ko_ap['jam_ap_01_KO'] != 0 || $ko_ap['menit_ap_01_KO'] != 0) {echo str_pad($ko_ap['jam_ap_01_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_01_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_ap['jam_ap_01_KO'] != 0 || $ko_ap['menit_ap_01_KO'] != 0) { 
						echo round(((($ko_ap['jam_ap_01_KO']*60)+$ko_ap['menit_ap_01_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ap_ap['jam_ap_01_AP'] != 0 || $ap_ap['menit_ap_01_AP'] != 0) {echo str_pad($ap_ap['jam_ap_01_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_01_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_ap['jam_ap_01_AP'] != 0 || $ap_ap['menit_ap_01_AP'] != 0) { 
						echo round(((($ap_ap['jam_ap_01_AP']*60)+$ap_ap['menit_ap_01_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pa_ap['jam_ap_01_PA'] != 0 || $pa_ap['menit_ap_01_PA'] != 0) {echo str_pad($pa_ap['jam_ap_01_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_01_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_ap['jam_ap_01_PA'] != 0 || $pa_ap['menit_ap_01_PA'] != 0) { 
						echo round(((($pa_ap['jam_ap_01_PA']*60)+$pa_ap['menit_ap_01_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pm_ap['jam_ap_01_PM'] != 0 || $pm_ap['menit_ap_01_PM'] != 0) {echo str_pad($pm_ap['jam_ap_01_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_01_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_ap['jam_ap_01_PM'] != 0 || $pm_ap['menit_ap_01_PM'] != 0) { 
						echo round(((($pm_ap['jam_ap_01_PM']*60)+$pm_ap['menit_ap_01_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($gt_ap['jam_ap_01_GT'] != 0 || $gt_ap['menit_ap_01_GT'] != 0) {echo str_pad($gt_ap['jam_ap_01_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_01_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_ap['jam_ap_01_GT'] != 0 || $gt_ap['menit_ap_01_GT'] != 0) { 
						echo round(((($gt_ap['jam_ap_01_GT']*60)+$gt_ap['menit_ap_01_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($tg_ap['jam_ap_01_TG'] != 0 || $tg_ap['menit_ap_01_TG'] != 0) {echo str_pad($tg_ap['jam_ap_01_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_01_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_ap['jam_ap_01_TG'] != 0 || $tg_ap['menit_ap_01_TG']!= 0) { 
						echo round(((($tg_ap['jam_ap_01_TG']*60)+$tg_ap['menit_ap_01_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_01'] != 0 || $sum_mesin_ap['menit_ap_01'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_01'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_01'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_01'] != 0 || $sum_mesin_ap['menit_ap_01']!= 0) { 
						echo round(((($sum_mesin_ap['jam_ap_01']*60)+$sum_mesin_ap['menit_ap_01'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                </tr>

                <tr>
                    <td align="center">'02</td>
                    <td align="center"><?php if ($lm_ap['jam_ap_02_LM'] != 0 || $lm_ap['menit_ap_02_LM'] != 0) {echo str_pad($lm_ap['jam_ap_02_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_02_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_ap['jam_ap_02_LM'] != 0 || $lm_ap['menit_ap_02_LM'] != 0) { 
						echo round(((($lm_ap['jam_ap_02_LM']*60)+$lm_ap['menit_ap_02_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_ap['jam_ap_02_KM'] != 0 || $km_ap['menit_ap_02_KM'] != 0) {echo str_pad($km_ap['jam_ap_02_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_02_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_ap['jam_ap_02_KM'] != 0 || $km_ap['menit_ap_02_KM'] != 0) { 
						echo round(((($km_ap['jam_ap_02_KM']*60)+$km_ap['menit_ap_02_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_ap['jam_ap_02_PT'] != 0 || $pt_ap['menit_ap_02_PT'] != 0) {echo str_pad($pt_ap['jam_ap_02_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_02_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_ap['jam_ap_02_PT'] != 0 || $pt_ap['menit_ap_02_PT'] != 0) { 
						echo round(((($pt_ap['jam_ap_02_PT']*60)+$pt_ap['menit_ap_02_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_ap['jam_ap_02_KO'] != 0 || $ko_ap['menit_ap_02_KO'] != 0) {echo str_pad($ko_ap['jam_ap_02_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_02_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_ap['jam_ap_02_KO'] != 0 || $ko_ap['menit_ap_02_KO'] != 0) { 
						echo round(((($ko_ap['jam_ap_02_KO']*60)+$ko_ap['menit_ap_02_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_ap['jam_ap_02_AP'] != 0 || $ap_ap['menit_ap_02_AP'] != 0) {echo str_pad($ap_ap['jam_ap_02_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_02_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_ap['jam_ap_02_AP'] != 0 || $ap_ap['menit_ap_02_AP'] != 0) { 
						echo round(((($ap_ap['jam_ap_02_AP']*60)+$ap_ap['menit_ap_02_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_ap['jam_ap_02_PA'] != 0 || $pa_ap['menit_ap_02_PA'] != 0) {echo str_pad($pa_ap['jam_ap_02_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_02_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_ap['jam_ap_02_PA'] != 0 || $pa_ap['menit_ap_02_PA'] != 0) { 
						echo round(((($pa_ap['jam_ap_02_PA']*60)+$pa_ap['menit_ap_02_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_ap['jam_ap_02_PM'] != 0 || $pm_ap['menit_ap_02_PM'] != 0) {echo str_pad($pm_ap['jam_ap_02_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_02_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_ap['jam_ap_02_PM'] != 0 || $pm_ap['menit_ap_02_PM'] != 0) { 
						echo round(((($pm_ap['jam_ap_02_PM']*60)+$pm_ap['menit_ap_02_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_ap['jam_ap_02_GT'] != 0 || $gt_ap['menit_ap_02_GT'] != 0) {echo str_pad($gt_ap['jam_ap_02_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_02_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_ap['jam_ap_02_GT'] != 0 || $gt_ap['menit_ap_02_GT'] != 0) { 
						echo round(((($gt_ap['jam_ap_02_GT']*60)+$gt_ap['menit_ap_02_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_ap['jam_ap_02_TG'] != 0 || $tg_ap['menit_ap_02_TG'] != 0) {echo str_pad($tg_ap['jam_ap_02_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_02_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_ap['jam_ap_02_TG'] != 0 || $tg_ap['menit_ap_02_TG']!= 0) { 
						echo round(((($tg_ap['jam_ap_02_TG']*60)+$tg_ap['menit_ap_02_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_02'] != 0 || $sum_mesin_ap['menit_ap_02'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_02'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_02'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_02'] != 0 || $sum_mesin_ap['menit_ap_02']!= 0) { 
						echo round(((($sum_mesin_ap['jam_ap_02']*60)+$sum_mesin_ap['menit_ap_02'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <tr>
                    <td align="center">'03</td>
                    <td align="center"><?php if ($lm_ap['jam_ap_03_LM'] != 0 || $lm_ap['menit_ap_03_LM'] != 0) {echo str_pad($lm_ap['jam_ap_03_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_03_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_ap['jam_ap_03_LM'] != 0 || $lm_ap['menit_ap_03_LM'] != 0) { 
						echo round(((($lm_ap['jam_ap_03_LM']*60)+$lm_ap['menit_ap_03_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_ap['jam_ap_03_KM'] != 0 || $km_ap['menit_ap_03_KM'] != 0) {echo str_pad($km_ap['jam_ap_03_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_03_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_ap['jam_ap_03_KM'] != 0 || $km_ap['menit_ap_03_KM'] != 0) { 
						echo round(((($km_ap['jam_ap_03_KM']*60)+$km_ap['menit_ap_03_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_ap['jam_ap_03_PT'] != 0 || $pt_ap['menit_ap_03_PT'] != 0) {echo str_pad($pt_ap['jam_ap_03_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_03_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_ap['jam_ap_03_PT'] != 0 || $pt_ap['menit_ap_03_PT'] != 0) { 
						echo round(((($pt_ap['jam_ap_03_PT']*60)+$pt_ap['menit_ap_03_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_ap['jam_ap_03_KO'] != 0 || $ko_ap['menit_ap_03_KO'] != 0) {echo str_pad($ko_ap['jam_ap_03_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_03_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_ap['jam_ap_03_KO'] != 0 || $ko_ap['menit_ap_03_KO'] != 0) { 
						echo round(((($ko_ap['jam_ap_03_KO']*60)+$ko_ap['menit_ap_03_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_ap['jam_ap_03_AP'] != 0 || $ap_ap['menit_ap_03_AP'] != 0) {echo str_pad($ap_ap['jam_ap_03_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_03_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_ap['jam_ap_03_AP'] != 0 || $ap_ap['menit_ap_03_AP'] != 0) { 
						echo round(((($ap_ap['jam_ap_03_AP']*60)+$ap_ap['menit_ap_03_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_ap['jam_ap_03_PA'] != 0 || $pa_ap['menit_ap_03_PA'] != 0) {echo str_pad($pa_ap['jam_ap_03_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_03_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_ap['jam_ap_03_PA'] != 0 || $pa_ap['menit_ap_03_PA'] != 0) { 
						echo round(((($pa_ap['jam_ap_03_PA']*60)+$pa_ap['menit_ap_03_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_ap['jam_ap_03_PM'] != 0 || $pm_ap['menit_ap_03_PM'] != 0) {echo str_pad($pm_ap['jam_ap_03_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_03_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_ap['jam_ap_03_PM'] != 0 || $pm_ap['menit_ap_03_PM'] != 0) { 
						echo round(((($pm_ap['jam_ap_03_PM']*60)+$pm_ap['menit_ap_03_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_ap['jam_ap_03_GT'] != 0 || $gt_ap['menit_ap_03_GT'] != 0) {echo str_pad($gt_ap['jam_ap_03_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_03_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_ap['jam_ap_03_GT'] != 0 || $gt_ap['menit_ap_03_GT'] != 0) { 
						echo round(((($gt_ap['jam_ap_03_GT']*60)+$gt_ap['menit_ap_03_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_ap['jam_ap_03_TG'] != 0 || $tg_ap['menit_ap_03_TG'] != 0) {echo str_pad($tg_ap['jam_ap_03_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_03_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_ap['jam_ap_03_TG'] != 0 || $tg_ap['menit_ap_03_TG']!= 0) { 
						echo round(((($tg_ap['jam_ap_03_TG']*60)+$tg_ap['menit_ap_03_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_03'] != 0 || $sum_mesin_ap['menit_ap_03'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_03'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_03'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_03'] != 0 || $sum_mesin_ap['menit_ap_03']!= 0) { 
						echo round(((($sum_mesin_ap['jam_ap_03']*60)+$sum_mesin_ap['menit_ap_03'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
                <tr>
                    <td align="center">'04</td>
                    <td align="center"><?php if ($lm_ap['jam_ap_04_LM'] != 0 || $lm_ap['menit_ap_04_LM'] != 0) {echo str_pad($lm_ap['jam_ap_04_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_04_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_ap['jam_ap_04_LM'] != 0 || $lm_ap['menit_ap_04_LM'] != 0) { 
						echo round(((($lm_ap['jam_ap_04_LM']*60)+$lm_ap['menit_ap_04_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($km_ap['jam_ap_04_KM'] != 0 || $km_ap['menit_ap_04_KM'] != 0) {echo str_pad($km_ap['jam_ap_04_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_04_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_ap['jam_ap_04_KM'] != 0 || $km_ap['menit_ap_04_KM'] != 0) { 
						echo round(((($km_ap['jam_ap_04_KM']*60)+$km_ap['menit_ap_04_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pt_ap['jam_ap_04_PT'] != 0 || $pt_ap['menit_ap_04_PT'] != 0) {echo str_pad($pt_ap['jam_ap_04_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_04_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_ap['jam_ap_04_PT'] != 0 || $pt_ap['menit_ap_04_PT'] != 0) { 
						echo round(((($pt_ap['jam_ap_04_PT']*60)+$pt_ap['menit_ap_04_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ko_ap['jam_ap_04_KO'] != 0 || $ko_ap['menit_ap_04_KO'] != 0) {echo str_pad($ko_ap['jam_ap_04_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_04_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_ap['jam_ap_04_KO'] != 0 || $ko_ap['menit_ap_04_KO'] != 0) { 
						echo round(((($ko_ap['jam_ap_04_KO']*60)+$ko_ap['menit_ap_04_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($ap_ap['jam_ap_04_AP'] != 0 || $ap_ap['menit_ap_04_AP'] != 0) {echo str_pad($ap_ap['jam_ap_04_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_04_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_ap['jam_ap_04_AP'] != 0 || $ap_ap['menit_ap_04_AP'] != 0) { 
						echo round(((($ap_ap['jam_ap_04_AP']*60)+$ap_ap['menit_ap_04_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pa_ap['jam_ap_04_PA'] != 0 || $pa_ap['menit_ap_04_PA'] != 0) {echo str_pad($pa_ap['jam_ap_04_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_04_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_ap['jam_ap_04_PA'] != 0 || $pa_ap['menit_ap_04_PA'] != 0) { 
						echo round(((($pa_ap['jam_ap_04_PA']*60)+$pa_ap['menit_ap_04_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($pm_ap['jam_ap_04_PM'] != 0 || $pm_ap['menit_ap_04_PM'] != 0) {echo str_pad($pm_ap['jam_ap_04_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_04_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_ap['jam_ap_04_PM'] != 0 || $pm_ap['menit_ap_04_PM'] != 0) { 
						echo round(((($pm_ap['jam_ap_04_PM']*60)+$pm_ap['menit_ap_04_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($gt_ap['jam_ap_04_GT'] != 0 || $gt_ap['menit_ap_04_GT'] != 0) {echo str_pad($gt_ap['jam_ap_04_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_04_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_ap['jam_ap_04_GT'] != 0 || $gt_ap['menit_ap_04_GT'] != 0) { 
						echo round(((($gt_ap['jam_ap_04_GT']*60)+$gt_ap['menit_ap_04_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($tg_ap['jam_ap_04_TG'] != 0 || $tg_ap['menit_ap_04_TG'] != 0) {echo str_pad($tg_ap['jam_ap_04_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_04_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_ap['jam_ap_04_TG'] != 0 || $tg_ap['menit_ap_04_TG']!= 0) { 
						echo round(((($tg_ap['jam_ap_04_TG']*60)+$tg_ap['menit_ap_04_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_04'] != 0 || $sum_mesin_ap['menit_ap_04'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_04'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_04'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_04'] != 0 || $sum_mesin_ap['menit_ap_04']!= 0) { 
						echo round(((($sum_mesin_ap['jam_ap_04']*60)+$sum_mesin_ap['menit_ap_04'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
                      %</td>
                </tr>
            <!-- End Anti Piling1 -->
            <!-- Untuk Kolom Anti Piling2 -->
                <tr>
                    <td align="left"><strong>ANTI PILLING 02</strong></td>
                    <td align="center">'01</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                </tr>
            <!-- End Anti Piling2 -->
            <!-- Untuk Kolom Anti Piling3 -->
                <tr>
                    <td align="left"><strong>ANTI PILLING 03</strong></td>
                    <td align="center">'01</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                </tr>
            <!-- End Anti Piling3 -->
            <!-- Untuk Kolom Anti Piling4 -->
                <tr>
                    <td align="left"><strong>ANTI PILLING 04</strong></td>
                    <td align="center">'01</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                    <td align="center"><?php echo "00:00:00"; ?></td>
                    <td align="center">0.0 %</td>
                </tr>
            <!-- End Anti Piling4 -->
            <!-- Untuk Kolom Wet Sue -->
                <tr>
                    <?php $query_wet9 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_wet_F_TG,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'TG' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_wet_F_TG
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_wet9    = mysqli_query($cona,$query_wet9);
                            $tg_wet             = mysqli_fetch_assoc($stmt_wet9);
                $query_wet8 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_wet_F_GT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'GT' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_wet_F_GT
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_wet8    = mysqli_query($cona,$query_wet8);
                            $gt_wet             = mysqli_fetch_assoc($stmt_wet8);
                $query_wet7 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_wet_F_PM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PM' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_wet_F_PM
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_wet7    = mysqli_query($cona,$query_wet7);
                            $pm_wet             = mysqli_fetch_assoc($stmt_wet7);
                $query_wet6 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_wet_F_PA,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PA' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_wet_F_PA
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_wet6    = mysqli_query($cona,$query_wet6);
                            $pa_wet             = mysqli_fetch_assoc($stmt_wet6);
                $query_wet5 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_wet_F_AP,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'AP' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_wet_F_AP
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_wet5    = mysqli_query($cona,$query_wet5);
                            $ap_wet             = mysqli_fetch_assoc($stmt_wet5);
                $query_wet4 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_wet_F_KO,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KO' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_wet_F_KO
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_wet4    = mysqli_query($cona,$query_wet4);
                            $ko_wet             = mysqli_fetch_assoc($stmt_wet4);
                $query_wet3 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_wet_F_PT,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'PT' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_wet_F_PT
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_wet3    = mysqli_query($cona,$query_wet3);
                            $pt_wet             = mysqli_fetch_assoc($stmt_wet3);
                $query_wet2 = "SELECT
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN FLOOR(durasi_jam_stop) 
                                            ELSE 0 
                                        END
                                        ) AS jam_wet_F_KM,
                                    SUM(
                                        CASE 
                                            WHEN kode_stop = 'KM' 
                                            AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                            -- AND mesin like '%F%'
                                            THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                            ELSE 0 
                                        END
                                        ) AS menit_wet_F_KM
                                    FROM
                                        tbl_stoppage
                                    WHERE dept ='BRS'
                                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                        AND tbl_stoppage.kode_stop <> ''";
                            $stmt_wet2    = mysqli_query($cona,$query_wet2);
                            $km_wet             = mysqli_fetch_assoc($stmt_wet2);
                    $query_wet1 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                                    -- AND mesin like '%F%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_wet_F_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                                    -- AND mesin like '%F%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_wet_F_LM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_wet1    = mysqli_query($cona,$query_wet1);
                                    $lm_wet             = mysqli_fetch_assoc($stmt_wet1);
                            // Total Garuk
                    $query_mesin_wet = "SELECT
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                                    -- AND mesin like '%F%'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_wet_F,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('WET1', 'WET2', 'WET3', 'WET4')
                                                    -- AND mesin like '%F%'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_wet_F
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_wet= mysqli_query($cona,$query_mesin_wet);
                        $sum_mesin_wet= mysqli_fetch_assoc($stmt_mesin_wet);
                    ?>
                    <td align="left"><strong>WET SUEDING</strong></td>
                    <td align="center">'01</td>
                    <td align="center"><?php if ($lm_wet['jam_wet_F_LM'] != 0 || $lm_wet['menit_wet_F_LM'] != 0) {echo str_pad($lm_wet['jam_wet_F_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_wet['menit_wet_F_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($lm_wet['jam_wet_F_LM'] != 0 || $lm_wet['menit_wet_F_LM'] != 0) { 
						echo round(((($lm_wet['jam_wet_F_LM']*60)+$lm_wet['menit_wet_F_LM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($km_wet['jam_wet_F_KM'] != 0 || $km_wet['menit_wet_F_KM'] != 0) {echo str_pad($km_wet['jam_wet_F_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_wet['menit_wet_F_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_wet['jam_wet_F_KM'] != 0 || $km_wet['menit_wet_F_KM'] != 0) { 
						echo round(((($km_wet['jam_wet_F_KM']*60)+$km_wet['menit_wet_F_KM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pt_wet['jam_wet_F_PT'] != 0 || $pt_wet['menit_wet_F_PT'] != 0) {echo str_pad($pt_wet['jam_wet_F_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_wet['menit_wet_F_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_wet['jam_wet_F_PT'] != 0 || $pt_wet['menit_wet_F_PT'] != 0) { 
						echo round(((($pt_wet['jam_wet_F_PT']*60)+$pt_wet['menit_wet_F_PT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ko_wet['jam_wet_F_KO'] != 0 || $ko_wet['menit_wet_F_KO'] != 0) {echo str_pad($ko_wet['jam_wet_F_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_wet['menit_wet_F_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_wet['jam_wet_F_KO'] != 0 || $ko_wet['menit_wet_F_KO'] != 0) { 
						echo round(((($ko_wet['jam_wet_F_KO']*60)+$ko_wet['menit_wet_F_KO'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($ap_wet['jam_wet_F_AP'] != 0 || $ap_wet['menit_wet_F_AP'] != 0) {echo str_pad($ap_wet['jam_wet_F_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_wet['menit_wet_F_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_wet['jam_wet_F_AP'] != 0 || $ap_wet['menit_wet_F_AP'] != 0) { 
						echo round(((($ap_wet['jam_wet_F_AP']*60)+$ap_wet['menit_wet_F_AP'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pa_wet['jam_wet_F_PA'] != 0 || $pa_wet['menit_wet_F_PA'] != 0) {echo str_pad($pa_wet['jam_wet_F_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_wet['menit_wet_F_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_wet['jam_wet_F_PA'] != 0 || $pa_wet['menit_wet_F_PA'] != 0) { 
						echo round(((($pa_wet['jam_wet_F_PA']*60)+$pa_wet['menit_wet_F_PA'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($pm_wet['jam_wet_F_PM'] != 0 || $pm_wet['menit_wet_F_PM'] != 0) {echo str_pad($pm_wet['jam_wet_F_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_wet['menit_wet_F_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_wet['jam_wet_F_PM'] != 0 || $pm_wet['menit_wet_F_PM'] != 0) { 
						echo round(((($pm_wet['jam_wet_F_PM']*60)+$pm_wet['menit_wet_F_PM'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($gt_wet['jam_wet_F_GT'] != 0 || $gt_wet['menit_wet_F_GT'] != 0) {echo str_pad($gt_wet['jam_wet_F_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_wet['menit_wet_F_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_wet['jam_wet_F_GT'] != 0 || $gt_wet['menit_wet_F_GT'] != 0) { 
						echo round(((($gt_wet['jam_wet_F_GT']*60)+$gt_wet['menit_wet_F_GT'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($tg_wet['jam_wet_F_TG'] != 0 || $tg_wet['menit_wet_F_TG'] != 0) {echo str_pad($tg_wet['jam_wet_F_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_wet['menit_wet_F_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_wet['jam_wet_F_TG'] != 0 || $tg_wet['menit_wet_F_TG'] != 0) { 
						echo round(((($tg_wet['jam_wet_F_TG']*60)+$tg_wet['menit_wet_F_TG'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                    <td align="center"><?php if ($sum_mesin_wet['jam_wet_F'] != 0 || $sum_mesin_wet['menit_wet_F'] != 0) {echo str_pad($sum_mesin_wet['jam_wet_F'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_wet['menit_wet_F'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_wet['jam_wet_F'] != 0 || $sum_mesin_wet['menit_wet_F'] != 0) { 
						echo round(((($sum_mesin_wet['jam_wet_F']*60)+$sum_mesin_wet['menit_wet_F'])/$hariKrjBln)*100,2);} else {echo '0.0';}?>
%</td>
                </tr>
            <!-- End Wet Sue -->
            <!-- Untuk Kolom Total -->
                <tr>
                    <?php
                    $query_total_tbl3 = "SELECT
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'LM'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_total_LM,
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'LM'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_total_LM,
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'KM'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_total_KM,
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'KM'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_total_KM,                                                
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'PT'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_total_PT,
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'PT'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_total_PT,                                                
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'PM'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_total_PM,
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'PM'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_total_PM,                                                
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'GT'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_total_GT,
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'GT'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_total_GT,                                                
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'TG'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_total_TG,
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'TG'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_total_TG,                                                
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'PA'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_total_PA,
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'PA'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_total_PA,                                                
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'AP'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_total_AP,
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'AP'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_total_AP,                                                
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'KO'
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_total_KO,
                                            SUM(
                                                CASE
                                                    WHEN kode_stop = 'KO'
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_total_KO,
                                            SUM(
                                                FLOOR(durasi_jam_stop) 
                                                ) AS jam_total,
                                            SUM(
                                                round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                ) AS menit_total
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'
                                                AND tbl_stoppage.kode_stop <> ''
                                                and mesin <> ''";
                                                // echo $query_total_tbl3;
                        $stmt_total_tbl3= mysqli_query($cona,$query_total_tbl3);
                        $sum_tbl3= mysqli_fetch_assoc($stmt_total_tbl3);
                    ?>
                    <td align="center" colspan="2"><strong>TOTAL</strong></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_LM'] != 0 || $sum_tbl3['menit_total_LM'] != 0) {echo str_pad($sum_tbl3['jam_total_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center">&nbsp;</td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_KM'] != 0 || $sum_tbl3['menit_total_KM'] != 0) {echo str_pad($sum_tbl3['jam_total_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center">&nbsp;</td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_PT'] != 0 || $sum_tbl3['menit_total_PT'] != 0) {echo str_pad($sum_tbl3['jam_total_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center">&nbsp;</td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_KO'] != 0 || $sum_tbl3['menit_total_KO'] != 0) {echo str_pad($sum_tbl3['jam_total_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center">&nbsp;</td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_AP'] != 0 || $sum_tbl3['menit_total_AP'] != 0) {echo str_pad($sum_tbl3['jam_total_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center">&nbsp;</td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_PA'] != 0 || $sum_tbl3['menit_total_PA'] != 0) {echo str_pad($sum_tbl3['jam_total_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center">&nbsp;</td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_PM'] != 0 || $sum_tbl3['menit_total_PM'] != 0) {echo str_pad($sum_tbl3['jam_total_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center">&nbsp;</td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_GT'] != 0 || $sum_tbl3['menit_total_GT'] != 0) {echo str_pad($sum_tbl3['jam_total_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center">&nbsp;</td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_TG'] != 0 || $sum_tbl3['menit_total_TG'] != 0) {
					$Tmenit_tbl3 = ($sum_tbl3['jam_total_TG'] * 60) + $sum_tbl3['menit_total_TG'];
					$jam_tbl3   = floor($Tmenit_tbl3 / 60);
					$menit_tbl3 = $Tmenit_tbl3 % 60;
					echo str_pad($jam_tbl3, 2, '0', STR_PAD_LEFT) . ':' . str_pad($menit_tbl3, 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center">&nbsp;</td>
                    <td align="center"><?php if ($sum_tbl3['jam_total'] != 0 || $sum_tbl3['menit_total'] != 0) {
					$GTmenit_tbl3 = ($sum_tbl3['jam_total'] * 60) + $sum_tbl3['menit_total'];
					$Gjam_tbl3   = floor($GTmenit_tbl3 / 60);
					$Gmenit_tbl3 = $GTmenit_tbl3 % 60;
					echo str_pad($Gjam_tbl3, 2, '0', STR_PAD_LEFT) . ':' . str_pad($Gmenit_tbl3, 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center">&nbsp;</td>
                </tr>
            <!-- End Total -->
            </tbody>
      </table>		
		
	</td>
	<td width="30%" align="left" valign="top" colspan="7">
	 <table width="100%" border="0">
	  <tbody>
	    <tr>
	      <td width="20%" align="left" valign="top">&nbsp;</td>
	      <td width="78%" align="left" valign="top">KETERANGAN</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">LM :</td>
	      <td align="left" valign="top">Listrik Mati</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">KM : </td>
	      <td align="left" valign="top">Kerusakan Mesin</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">KO :</td>
	      <td align="left" valign="top">Kurang Order</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">AP :</td>
	      <td align="left" valign="top">Abnormal Produk</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">PM : </td>
	      <td align="left" valign="top">Pemeliharaan Mesin</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">GT : </td>
	      <td align="left" valign="top">Gangguan Teknis ( Gangguan yang di sebabkanoleh kerusakan pada mesin proses pendukung)</td>
	      </tr>
	    <tr>
	      <td align="right" valign="top">TG : </td>
	      <td align="left" valign="top">Tunggu (misalnya: oper produksi, tunggu buka kain, tunggu gerobak)</td>
	      </tr>
	    </tbody>
	  </table></td>	
	</tr>	
</table>
<!-- End Table 3-->	
<br>
<table width="70%" border="1">
  <tbody>
    <tr>
      <td colspan="33">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="8" align="center" valign="middle"><strong>Dibuat Oleh:</strong></td>
      <td colspan="8" align="center" valign="middle"><strong>Diperiksa Oleh:</strong></td>
      <td colspan="8" align="center" valign="middle"><strong>Diketahui Oleh:</strong></td>
      <td colspan="8" align="center" valign="middle"><strong>Disetujui Oleh:</strong></td>
    </tr>
    <tr>
      <td><strong>Nama</strong></td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Jabatan</strong></td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Tanggal</strong></td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Tanda Tangan</strong></td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
    </tr>
  </tbody>
</table>
<br />
KETERANGAN : <br />
¤ Proses Sisir setelah Printing sebanyak = - <br />
¤ Proses Garuk 2x Berghaus sebanyak = - <br />
¤ Proses Sisir 2x Berghaus sebanyak = - <br />
¤ Proses Anti Pilling 2x Berghaus sebanyak = - <br />
¤ Proses Peach Skin Anti Pilling = - <br />
</body>
</html>	