<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles_cetak_brs.css" rel="stylesheet" type="text/css">	
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
<table border="0" style="width: 11.69in;">
  <tbody>
    <tr>
      <td align="left" valign="top">
		<!-- awal -->
<?php
		// Ambil tahun dari $startDate
		$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

		// Hitung tahun sebelumnya
		$tahun_sebelumnya = $tahun - 1;
		$bln   = $_GET['bulan']; 
		  
        $input = $_GET['awal']; 

        $end_time = $input . ' 23:00:00'; 
        $Awal = $input . ' 00:00:00';

        // Ambil bulan dari tanggal input
        $startDate = new DateTime(date('Y-m-01', strtotime($input))); // Awal bulan input
        $endDate = new DateTime(date('Y-m-t', strtotime($input)));     // Akhir bulan input

        $date_start_tbl2 = new DateTime($input. '23:00:00');
        $date_end_tbl2 = clone $date_start_tbl2;
        $date_end_tbl2->modify('-1 day');
        $start_formatted = $date_end_tbl2->format('Y-m-d H:i:s');
        $end_formatted = $date_start_tbl2->format('Y-m-d H:i:s');

        
        // print_r($row_tbl2['brs_fleece_ulang']);
//        $start_ncp = $date_end_tbl2->format('Y-m-d');
//        $end_ncp = $date_start_tbl2->format('Y-m-d');

        $qry_ncp = "SELECT
                        SUM(berat) as qty_ncp
                    FROM
                        tbl_ncp_qcf_now
                    WHERE
                        STATUS IN ('Belum OK', 'OK', 'BS', 'Disposisi')
                        AND dept = 'BRS'
                        AND dept = 'BRS'
                        AND ncp_hitung = 'ya'
                        AND year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]' ";
        $qry1 = mysqli_query($cond, $qry_ncp);
        $row_ncp = mysqli_fetch_assoc($qry1);

        // print_r( $startDate);
    ?>
    <!-- Tabel-1.php -->
         <!-- LANJUTIN DIBAGIAN TOTAL PALING BAWAH -->		  
<table border="0" class="table-list1" width="100%">
  <tbody>
    <tr>
      <td width="5%"><img src="https://online.indotaichen.com/ADM-ITTI/pages/cetak/Indo.jpg" width="48" height="48" alt="logo"/></td>
      <td width="95%" align="center" valign="middle"><font size="+2"><strong>LAPORAN PRODUKSI BULANAN DEPARTEMEN BRUSHING</strong></font>
		  <br>
		<font size="-1"><strong>FW - 02 - BRS - 09 / 13</strong></font></td>
    </tr>
  </tbody>
</table>
<?= isset($_GET['awal']) && strtotime($_GET['awal']) ? date('d M y', strtotime($_GET['awal'])) : ''; ?>
<br>
<table border="0" class="table-list1" width="100%">
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
      <td rowspan="2" align="center" valign="middle"><strong>WET SUEDING (G)</strong></td>
      <td rowspan="2" align="center" valign="middle"><strong>TOTAL PRODUKSI ULANG (H)</strong></td>
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
      </tr>
	  <tr>
		<td align="left">JAN - DEC <?php echo $tahun_sebelumnya; ?></td>
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
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>  
	  </tr>
	  <tr>
		<td align="left">DECEMBER '<?php echo substr($tahun_sebelumnya, 2, 2); ?></td>
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
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>
		<td align='center'>&nbsp;</td>  
	  </tr>
	  <?php
                // Ambil semua bulan dari Januari sampai Desember di tahun tersebut
				$tanggal_ada_data = [];

				// Query untuk ambil semua bulan yang punya data
				$query = "SELECT DISTINCT MONTH(tgl_buat) AS bulan 
						  FROM tbl_produksi
						  WHERE YEAR(tgl_buat) = '{$tahun}'
						  ORDER BY bulan ASC";
				$result = mysqli_query($conb, $query);

				// Tandai bulan yang punya data
				while ($row = mysqli_fetch_assoc($result)) {
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
					$nama_bulan = strtoupper(DateTime::createFromFormat('!m Y', "$bulan $tahun")->format('F \'y')); // contoh: January
					$ada_data = isset($tanggal_ada_data[$bulan]);
                    // Default qty kosong
                    $qty = '-';

                    echo "<tr>";
                    echo "<td style='border: 1px solid black;' align='left'>{$nama_bulan}</td>";
                    // Jika tanggal di loop masih <= tanggal input, jalankan query
						$hari_kerja=0;
                        if ($bulan <= $bln) {
						// Tentukan awal dan akhir bulan
						$startDate = new DateTime("$tahun-$bulan-01");
						$endDate = (clone $startDate)->modify('last day of this month');

						// Loop harian
						$interval = new DateInterval('P1D');
						$range = new DatePeriod($startDate, $interval, (clone $endDate)->modify('+1 day'));
						$Rtotal_kk = 0;
						$hari_kerja = 0;
						$qty_fleece1 = 0;
						$qty_pot_bulu1 = 0;	
						$qty_ap1 = 0;	
						$sisir_anti_pilling_row1 = 0;
						$potong_bulu_anti_pilling_row1 = 0;	
						$oven_anti_pilling_row1 = 0;	
						$peach_skin_row1 = 0;
						$potong_bulu_peach_skin_row1 = 0;
						$airo_row1 = 0;	
						$potong_bulu_lain_lain_row1 = 0;
						$anti_pilling_lain_lain_row1 = 0;
						$polishing_row1 = 0;
						$wet_sueding_row1 = 0;
						$bantu_ncp_row1 = 0;	
						foreach ($range as $cutoffDate) {
						// Waktu cutoff: dari jam 23:01 hari sebelumnya sampai 23:00 hari ini
						$start_time = (clone $cutoffDate)->modify('-1 day')->format('Y-m-d') . " 23:01:00";
						$end_time   = $cutoffDate->format('Y-m-d') . " 23:00:00";
							
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
                            tbl_produksi
                        WHERE
                            tgl_buat between '$start_formatted' and '$end_formatted'";
						$stmt_tbl2 = mysqli_query($conb, $query_tbl2);
						$row_tbl2 = mysqli_fetch_assoc($stmt_tbl2);
						$cek_tbl2 = mysqli_num_rows($stmt_tbl2);	
							
                        $query_table1="SELECT
                                            SUM(CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' THEN qty ELSE 0 END) AS garuk_ap,
                                            GROUP_CONCAT(DISTINCT CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' THEN TRIM(nodemand) ELSE NULL END) AS demand_garuk_ap,
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
                                                                    'ANTI PILLING LAIN-LAIN KHUSUS-DYE (Ulang)','ANTI PILLING LAIN-LAIN KHUSUS-BRS (Ulang)','ANTI PILLING LAIN-LAIN KHUSUS-CQA (Ulang)') THEN qty ELSE 0 END) AS bantu,
                                            GROUP_CONCAT(DISTINCT CASE 
                                                WHEN proses = 'GARUK FLEECE (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                                THEN TRIM(nodemand) 
                                                ELSE NULL 
                                            END) AS demand_garuk_fleece,
                                            SUM(CASE WHEN proses LIKE '%(Bantu)%' THEN qty ELSE 0 END) AS produksi_ulang,
                                            GROUP_CONCAT(DISTINCT CASE 
                                                WHEN proses LIKE '%(Bantu)%' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                                THEN TRIM(nodemand) 
                                                ELSE NULL 
                                            END) AS demand_produksi_ulang,
                                            GROUP_CONCAT(DISTINCT CASE 
                                                WHEN proses = 'PEACH SKIN (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' 
                                                THEN TRIM(nodemand) 
                                                ELSE NULL 
                                            END) AS demand_peach_skin,
                                            count(distinct nokk) as total_kk
                                        FROM
                                            tbl_produksi tp
                                        WHERE 
                                        tp.tgl_buat between'$start_time' and '$end_time' ";
                        $stmt_qry = mysqli_query($conb, $query_table1);
                        $data_table1 = mysqli_fetch_assoc($stmt_qry);
							
							$hari_kerja_query = "SELECT 1 FROM tbl_produksi 
                                                WHERE tgl_buat >= '$start_time' AND tgl_buat < '$end_time' LIMIT 1";
                            $hari_kerja_result = mysqli_query($conb, $hari_kerja_query);
                            $hari_kerja1 = mysqli_num_rows($hari_kerja_result) > 0 ? '1' : '0';
							$hari_kerja+=$hari_kerja1;
							$Rtotal_kk += $data_table1['total_kk'];
							$qty_fleece1 += $data_table1['garuk_fleece'];
							$qty_pot_bulu1 += $data_table1['potong_bulu_fleece'];
							$qty_ap1 += $data_table1['garuk_ap'];
							$sisir_anti_pilling_row1 += $data_table1['sisir_ap'];
							$potong_bulu_anti_pilling_row1 += $data_table1['pbulu_ap'];
							$oven_anti_pilling_row1 += $data_table1['oven_ap'];
							$peach_skin_row1 += $data_table1['peach'];
							$potong_bulu_peach_skin_row1 += $data_table1['pb_peach'];
							$airo_row1 += $data_table1['airo'];
							$potong_bulu_lain_lain_row1 += $data_table1['pb_lain'];
							$anti_pilling_lain_lain_row1 += $data_table1['ap_lain'];
							$polishing_row1 += $data_table1['polish'];
							$wet_sueding_row1 += $data_table1['wet_sue'];
							$bantu_ncp_row1 += $data_table1['bantu'];
							
						}
                        // echo $tanggal;
                        // Hari kerja
							// Tanggal pertama bulan berjalan
							// $tanggal_awal_bulan = DateTime::createFromFormat('Y-n-j', "$tahun-$bulan-01");

							// Start time: 1 hari sebelumnya jam 23:01:00
							// $start_time_K = $tanggal_awal_bulan->modify('-1 day')->format('Y-m-d 23:01:00');

							// Reset lagi ke tanggal pertama untuk hitung akhir bulan
							// $tanggal_awal_bulan = DateTime::createFromFormat('Y-n-j', "$tahun-$bln-01");
							// $end_time_K = $tanggal_awal_bulan->modify('last day of this month')->format('Y-m-d 23:00:00');
							
                            //$hari_kerja_query = "SELECT COUNT(DISTINCT DATE(tgl_buat)) AS jmlhari FROM tbl_produksi 
                            //                    WHERE tgl_buat BETWEEN '$start_time_K' AND '$end_time_K' LIMIT 1";
                            //$hari_kerja_result = mysqli_query($conb, $hari_kerja_query);
							//$row_hari = mysqli_fetch_array($hari_kerja_result);
                            //$hari_kerja = mysqli_num_rows($hari_kerja_result) > 0 ? ($row_hari['jmlhari']) : '0';							
							
							
							
						
                            echo "<td align='center' style='border: 1px solid black;'>{$hari_kerja}</td>";
                            $totalHariKerja += $hari_kerja; // Tambahkan ke total hari kerja
                        // Hari kerja

                        // Jumlah KK
//                            $total_kk = $data_table1['total_kk'];
							$total_kk = $Rtotal_kk;
                            $display_kk = ($total_kk != 0) ? $total_kk : '-';
                            echo "<td align='center' style='border: 1px solid black;'>$display_kk</td>";

                            // Hanya tambahkan angka ke total jika nilainya tidak nol
                            if ($total_kk != 0) {
                                $totalJumlahKK += $total_kk;
                            }
                        // Jumlah KK

                        // Garuk Fleece
                        if($tanggal==$input){
                                $qty_fleece = $qty_fleece1 - ($row_tbl2['brs_fleece_ulang'] + $row_tbl2['fin_fleece_ulang'] + $row_tbl2['dye_fleece_ulang'] + $row_tbl2['cqa_fleece_ulang']); 
                                $display_fleece = ($qty_fleece1 != 0) ? $qty_fleece1 : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' style='border: 1px solid black;'>{$display_fleece}</td>";
                                if ($qty_fleece != 0) {
                                $total_garuk_fleece += $qty_fleece1;
                            }
                        }else{
                            $qty_fleece = $qty_fleece1;
                            $display_fleece = ($qty_fleece != 0) ? $qty_fleece : '-';
                            echo "<td align='center' style='border: 1px solid black;'>{$display_fleece}</td>";
                            if ($qty_fleece != 0) {
                                $total_garuk_fleece += $qty_fleece;
                            }   
                        }
                        // Garuk Fleece

                        // Potong Bulu Fleece
                            $qty_pot_bulu = $qty_pot_bulu1;
                            $display_bulu = ($qty_pot_bulu != 0) ? $qty_pot_bulu : '-';
                            echo "<td align='center' style='border: 1px solid black;'>{$display_bulu}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($qty_pot_bulu != 0) {
                                $total_potong_bulu_fleece += $qty_pot_bulu;
                            }
                        // Potong Bulu Fleece

                        // Proses Garuk Anti Pilling
                        if($tanggal==$input){
							$qty_ap = $qty_ap1 - ($row_tbl2['brs_ap_ulang'] + $row_tbl2['fin_ap_ulang'] + $row_tbl2['dye_ap_ulang'] + $row_tbl2['cqa_ap_ulang']); 	
//						    $qty_ap = $qty_ap1;
                                $display_ap = ($qty_ap != 0) ? $qty_ap : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' style='border: 1px solid black;'>{$display_ap}</td>";
                                if ($qty_ap != 0) {
                                $total_garuk_anti_pilling += $qty_ap;
                            }
                        }else{
                            $qty_ap = $qty_ap1;
                            $display_ap = ($qty_ap != 0) ? $qty_ap : '-';
                            echo "<td align='center' style='border: 1px solid black;'>1 {$display_ap}</td>";
                            if ($qty_ap != 0) {
                                $total_garuk_anti_pilling += $qty_ap;
                            }   
                        }

                            // $qty_ap = ($data_table1['garuk_ap']!=0) ? $data_table1['garuk_ap'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$qty_ap}</td>";
                            //     $total_garuk_anti_pilling += ($qty_ap === "-") ? 0 : $qty_ap;
                        // Proses Garuk Anti Pilling

                        // Proses Sisir Anti Pilling
                            $sisir_anti_pilling_row = $sisir_anti_pilling_row1;
                            $display_sisir_ap = ($sisir_anti_pilling_row != 0) ? $sisir_anti_pilling_row : '-';
                            echo "<td align='center' style='border: 1px solid black;'>{$display_sisir_ap}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($sisir_anti_pilling_row != 0) {
                                $total_sisir_anti_pilling += $sisir_anti_pilling_row;
                            }
                            // $sisir_anti_pilling_row = ($data_table1['sisir_ap']!=0) ? $data_table1['sisir_ap'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$sisir_anti_pilling_row}</td>";
                            //     $total_sisir_anti_pilling += ($sisir_anti_pilling_row === "-") ? 0 : $sisir_anti_pilling_row; //kalau 0 nilainya strip
                        // Proses Sisir Anti Pilling

                        // Proses Potong Bulu Anti Pilling
                            $potong_bulu_anti_pilling_row = $potong_bulu_anti_pilling_row1;
                            $display_pb_ap = ($potong_bulu_anti_pilling_row != 0) ? $potong_bulu_anti_pilling_row : '-';
                            echo "<td align='center' style='border: 1px solid black;'>{$display_pb_ap}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($potong_bulu_anti_pilling_row != 0) {
                                $total_potong_bulu_anti_pilling += $potong_bulu_anti_pilling_row; 
                            }
                            // $potong_bulu_anti_pilling_row = ($data_table1['pbulu_ap']!=0) ? $data_table1['pbulu_ap'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$potong_bulu_anti_pilling_row}</td>";
                            //     $total_potong_bulu_anti_pilling += ($potong_bulu_anti_pilling_row === "-") ? 0 : $potong_bulu_anti_pilling_row; //kalau 0 nilainya strip
                        // Proses Potong Bulu Anti Pilling

                        // Oven Anti Pilling
                            $oven_anti_pilling_row = $oven_anti_pilling_row1;
                            $display_oven = ($oven_anti_pilling_row != 0) ? $oven_anti_pilling_row : '-';
                            echo "<td align='center' style='border: 1px solid black;'>{$display_oven}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($oven_anti_pilling_row != 0) {
                                $total_oven_anti_pilling += $oven_anti_pilling_row;
                            }
                            // $oven_anti_pilling_row = ($data_table1['oven_ap']!=0) ? $data_table1['oven_ap'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$oven_anti_pilling_row}</td>";
                            //     $total_oven_anti_pilling += ($oven_anti_pilling_row === "-") ? 0 : $oven_anti_pilling_row; //kalau 0 nilainya strip
                        // Oven Anti Pilling

                        // Proses Peach Skin
                            if($tanggal==$input){
                                $peach_skin_row = $peach_skin_row1 - ($row_tbl2['brs_peach_ulang'] + $row_tbl2['fin_peach_ulang'] + $row_tbl2['dye_peach_ulang'] + $row_tbl2['cqa_peach_ulang']); 
                                $display_peach = ($peach_skin_row != 0) ? $peach_skin_row : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' style='border: 1px solid black;'>{$display_peach}</td>";
                                if ($peach_skin_row != 0) {
                                $total_peach_skin += $peach_skin_row;
                            }
                                }else{
                                    $peach_skin_row = $peach_skin_row1;
                                    $display_peach = ($peach_skin_row != 0) ? $peach_skin_row : '-';
                                    echo "<td align='center' style='border: 1px solid black;'>{$display_peach}</td>";
                                    if ($peach_skin_row != 0) {
                                        $total_peach_skin += $peach_skin_row;
                                    }   
                                }
                            // $peach_skin_row = ($data_table1['peach']!=0) ? $data_table1['peach'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$peach_skin_row}</td>";
                            //     $total_peach_skin += ($peach_skin_row === "-") ? 0 : $peach_skin_row; //kalau 0 nilainya strip
                        // Proses Peach Skin

                        // Potong Bulu Peach Skin
                            $potong_bulu_peach_skin_row = $potong_bulu_peach_skin_row1;
                            $display_bulu_peach = ($potong_bulu_peach_skin_row != 0) ? $potong_bulu_peach_skin_row : '-';
                            echo "<td align='center' style='border: 1px solid black;'>{$display_bulu_peach}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($potong_bulu_peach_skin_row != 0) {
                                $total_potong_bulu_peach_skin += $potong_bulu_peach_skin_row;
                            }
                            // $potong_bulu_peach_skin_row = ($data_table1['pb_peach']!=0) ? $data_table1['pb_peach'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$potong_bulu_peach_skin_row}</td>";
                            //     $total_potong_bulu_peach_skin += ($potong_bulu_peach_skin_row === "-") ? 0 : $potong_bulu_peach_skin_row; //kalau 0 nilainya strip
                        // Potong Bulu Peach Skin

                        // AIRO
                            
                                $airo_row = $airo_row1;
                                $display_airo = ($airo_row != 0) ? $airo_row : '-';
                                echo "<td align='center' style='border: 1px solid black;'>{$display_airo}</td>";
                                if ($airo_row != 0) {
                                    $total_airo += $airo_row;
                                }   

                            // $airo_row = ($data_table1['airo']!=0) ? $data_table1['airo'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$airo_row}</td>";
                            //     $total_airo += ($airo_row === "-") ? 0 : $airo_row; //kalau 0 nilainya strip
                        // AIRO

                        // Potong Bulu Lain-Lain
                        if($tanggal==$input){
                                $potong_bulu_lain_lain_row = $potong_bulu_lain_lain_row1 - ($row_tbl2['brs_pb_ulang'] + $row_tbl2['fin_pb_ulang'] + $row_tbl2['dye_pb_ulang'] + $row_tbl2['cqa_pb_ulang']); 
                                $display_pb = ($potong_bulu_lain_lain_row != 0) ? $potong_bulu_lain_lain_row : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' style='border: 1px solid black;'>{$display_pb}</td>";
                                if ($potong_bulu_lain_lain_row != 0) {
                                $total_potong_bulu_lain_lain += $potong_bulu_lain_lain_row;   
                            }
                                }else{
                                    $potong_bulu_lain_lain_row = $potong_bulu_lain_lain_row1;
                                    $display_pb = ($potong_bulu_lain_lain_row != 0) ? $potong_bulu_lain_lain_row : '-';
                                    echo "<td align='center' style='border: 1px solid black;'>{$display_pb}</td>";
                                    if ($potong_bulu_lain_lain_row != 0) {
                                        $total_potong_bulu_lain_lain += $potong_bulu_lain_lain_row;
                                    }   
                                }


                            // $potong_bulu_lain_lain_row = ($data_table1['pb_lain']!=0) ? $data_table1['pb_lain'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$potong_bulu_lain_lain_row}</td>";
                            //     $total_potong_bulu_lain_lain += ($potong_bulu_lain_lain_row === "-") ? 0 : $potong_bulu_lain_lain_row; //kalau 0 nilainya strip
                        // Potong Bulu Lain-Lain

                        // Oven Anti Pilling Lain-Lain
                        if($tanggal==$input){
                                $anti_pilling_lain_lain_row = $anti_pilling_lain_lain_row1 - ($row_tbl2['brs_oven_ulang'] + $row_tbl2['fin_oven_ulang'] + $row_tbl2['dye_oven_ulang'] + $row_tbl2['cqa_oven_ulang']); 
                                $display_oven_ap = ($anti_pilling_lain_lain_row != 0) ? $anti_pilling_lain_lain_row : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center' style='border: 1px solid black;'>{$display_oven_ap}</td>";
                                if ($anti_pilling_lain_lain_row != 0) {
                                $total_anti_pilling_lain_lain += $anti_pilling_lain_lain_row;
                            }
                                }else{
                                    $anti_pilling_lain_lain_row = $anti_pilling_lain_lain_row1;
                                    $display_oven_ap = ($anti_pilling_lain_lain_row != 0) ? $anti_pilling_lain_lain_row : '-';
                                    echo "<td align='center' style='border: 1px solid black;'>{$display_oven_ap}</td>";
                                    if ($anti_pilling_lain_lain_row != 0) {
                                        $total_anti_pilling_lain_lain += $anti_pilling_lain_lain_row;
                                    }   
                                }
                            // $anti_pilling_lain_lain_row = ($data_table1['ap_lain']!=0) ? $data_table1['ap_lain'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$anti_pilling_lain_lain_row}</td>";
                            //     $total_anti_pilling_lain_lain += ($anti_pilling_lain_lain_row === "-") ? 0 : $anti_pilling_lain_lain_row; //kalau 0 nilainya strip
                        // Oven Anti Pilling Lain-Lain

                        // Polishing
                            $polishing_row = $polishing_row1;
                                $display_polish = ($polishing_row != 0) ? $polishing_row : '-';
                                echo "<td align='center' style='border: 1px solid black;'>{$display_polish}</td>";
                                if ($polishing_row != 0) {
                                    $total_polishing += $polishing_row;
                                }   
                            // $polishing_row = ($data_table1['polish']!=0) ? $data_table1['polish'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$polishing_row}</td>";
                            //     $total_polishing += ($polishing_row === "-") ? 0 : $polishing_row; //kalau 0 nilainya strip
                        // Polishing

                        // Wet Sueding
                                $wet_sueding_row = $wet_sueding_row1;
                                        $display_wet = ($wet_sueding_row != 0) ? $wet_sueding_row : '-';
                                        echo "<td align='center' style='border: 1px solid black;'>{$display_wet}</td>";
                                        if ($wet_sueding_row != 0) {
                                            $total_wet_sueding += $wet_sueding_row;
                                        }   
                            //  $wet_sueding_row = ($data_table1['wet_sue']!=0) ? $data_table1['wet_sue'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$wet_sueding_row}</td>";
                            //     $total_wet_sueding += ($wet_sueding_row === "-") ? 0 : $wet_sueding_row; //kalau 0 nilainya strip
                        // Wet Sueding

                        // Bantu NCP
                            // $bantu_ncp = "SELECT SUM(qty) AS total_qty 
                            // FROM tbl_produksi 
                            // WHERE tgl_buat >= '$start_time' AND tgl_buat < '$end_time'
                            // AND (
                            //     proses LIKE '%bantu%' OR proses LIKE '%NCP%'
                            // )";
                            // $bantu_ncp_result = mysqli_query($conb, $bantu_ncp);
                            // $bantu_ncp_row = mysqli_fetch_assoc($bantu_ncp_result);
                            $bantu_ncp_row = $bantu_ncp_row1;
                                $display_ncp = ($bantu_ncp_row != 0) ? $bantu_ncp_row : '-';
                                echo "<td align='center' style='border: 1px solid black;'>{$display_ncp}</td>";
                                if ($bantu_ncp_row != 0) {
                                    $total_bantu_ncp += $bantu_ncp_row;
                                }   
                            // $bantu_ncp_row = ($data_table1['bantu']!=0) ? $data_table1['bantu'] : '-';
                            // echo "<td align='center' style='border: 1px solid black;'>{$bantu_ncp_row}</td>";
                            //     $total_bantu_ncp += ($bantu_ncp_row === "-") ? 0 : $bantu_ncp_row; //kalau 0 nilainya strip
                            // echo "<td align='center' style='border: 1px solid black;'>" .
                            //     (!empty($bantu_ncp_row['total_qty']) ? htmlspecialchars($bantu_ncp_row['total_qty']) : '-') .
                            //     "</td>";
                            //     $total_bantu_ncp += ($bantu_ncp_row['total_qty'] === "-") ? 0 : $bantu_ncp_row['total_qty']; // Tambahkan ke total bantu NCP
                        // Bantu NCP

                        // Total Produksi
                            // $total_produksi =$bantu_ncp_row;
                            $total_produksi =($qty_fleece+$qty_ap+$peach_skin_row+$airo_row+$potong_bulu_lain_lain_row+$anti_pilling_lain_lain_row+$polishing_row+ $wet_sueding_row+$bantu_ncp_row);
                            // $total_produksi = $peach_skin_row + $qty;
                            $total_total_produksi += $total_produksi;
                            

                            echo "<td align='center'style='border: 1px solid black;'>" . ($total_produksi > 0 ? htmlspecialchars($total_produksi) : '-') . "</td>";
                    } else{
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                        echo "<td align='center' style='border: 1px solid black;'>-</td>";
                    }

                    echo "</tr>";
                }
                ?>
            <td style='border: 1px solid black;' align='center'><strong>Total</strong></td>
                <?php
                // Tampilkan total di baris bawah
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($totalHariKerja ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($totalJumlahKK ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_garuk_fleece ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_potong_bulu_fleece ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_garuk_anti_pilling ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_sisir_anti_pilling ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_potong_bulu_anti_pilling ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_oven_anti_pilling ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_peach_skin ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_potong_bulu_peach_skin ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_airo ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_potong_bulu_lain_lain ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_anti_pilling_lain_lain ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_polishing ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_wet_sueding ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_bantu_ncp ?: '-') . "</b></td>";
                    echo "<td align='center' style='border: 1px solid black;'><b>" . ($total_total_produksi ?: '-') . "</b></td>";
                ?>
    
 
</table>
<!-- End Table 1 -->
<!-- Tabel-2.php -->
<?php
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
                            tbl_produksi
                        WHERE
                            year(tgl_buat)='$_GET[tahun]' and month(tgl_buat)='$_GET[bulan]'";
						$stmt_tbl2bln = mysqli_query($conb, $query_tbl2bln);
						$row_tbl2bln = mysqli_fetch_assoc($stmt_tbl2bln);
						$cek_tbl2bln = mysqli_num_rows($stmt_tbl2bln);	  
?>		  
<strong> LAPORAN PROSES ULANG</strong>		  
<table border="0" class="table-list1" width="100%">	            
            <?php
                $tglInput_tbl2 = $_GET['awal'];
            ?>
            <!-- BRUSHING NCP -->
                <tr>
                    <td style="border: 1px solid black;"><strong>BRUSHING NCP</strong></td>
                    <td colspan="-1" align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td style="border: 1px solid black;" align="center"><?php echo '-' ?></td>
                    <td style="border: 1px solid black;" align="center">
                        <?php $qty_ncp = $row_ncp['qty_ncp'];
                                echo ($qty_ncp!=0) ? $qty_ncp : '-';
                            ?></td>
                </tr>
            <!-- BRUSHING NCP -->

            <!-- BRUSHING ULANG -->
                <tr>
                    <td style="border: 1px solid black;"><strong>BRUSHING ULANG</strong></td>
                    <td colspan="-1" align="center" style="border: 1px solid black;">
                        <?php $brs_fleece_ulang = ($row_tbl2bln['brs_fleece_ulang']!=0) ? $row_tbl2bln['brs_fleece_ulang'] : '-';
                                echo $brs_fleece_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php 
                            $brs_ap_ulang = $row_tbl2bln['brs_ap_ulang'];
                                echo ($brs_ap_ulang!=0) ? $brs_ap_ulang : '-';
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $brs_peach_ulang = ($row_tbl2bln['brs_peach_ulang']!=0) ? $row_tbl2bln['brs_peach_ulang'] : '-';
                                echo $brs_peach_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $brs_pb_ulang = ($row_tbl2bln['brs_pb_ulang']!=0) ? $row_tbl2bln['brs_pb_ulang'] : '-';
                                echo $brs_pb_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $brs_oven_ulang = ($row_tbl2bln['brs_oven_ulang']!=0) ? $row_tbl2bln['brs_oven_ulang'] : '-';
                                echo $brs_oven_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?php 
                        $total_tbl2_brs = $row_tbl2bln['brs_fleece_ulang'] + $row_tbl2bln['brs_ap_ulang'] + $row_tbl2bln['brs_peach_ulang']+$row_tbl2bln['brs_pb_ulang']+$row_tbl2bln['brs_oven_ulang'];
                        echo $total_tbl2_brs > 0 ? $total_tbl2_brs : '-';?>
                    </td>
                </tr>
            <!-- BRUSHING ULANG -->

            <!-- FINISHING ULANG -->
                <tr>
                    <td style="border: 1px solid black;"><strong>FINISHING ULANG</strong></td>
                    <td colspan="-1" align="center" style="border: 1px solid black;">
                        <?php $fin_fleece_ulang = ($row_tbl2bln['fin_fleece_ulang']!=0) ? $row_tbl2bln['fin_fleece_ulang'] : '-';
                                echo $fin_fleece_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $fin_ap_ulang = ($row_tbl2bln['fin_ap_ulang']!=0) ? $row_tbl2bln['fin_ap_ulang'] : '-';
                                echo $fin_ap_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $fin_peach_ulang = ($row_tbl2bln['fin_peach_ulang']!=0) ? $row_tbl2bln['fin_peach_ulang'] : '-';
                                echo $fin_peach_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $fin_pb_ulang = ($row_tbl2bln['fin_pb_ulang']!=0) ? $row_tbl2bln['fin_pb_ulang'] : '-';
                                echo $fin_pb_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $fin_oven_ulang = ($row_tbl2bln['fin_oven_ulang']!=0) ? $row_tbl2bln['fin_oven_ulang'] : '-';
                                echo $fin_oven_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?php 
                            $total_tbl2_fin= $row_tbl2bln['fin_fleece_ulang']+$row_tbl2bln['fin_ap_ulang']+$row_tbl2bln['fin_peach_ulang']+$row_tbl2bln['fin_pb_ulang']+$row_tbl2bln['fin_oven_ulang'];
                            echo $total_tbl2_fin > 0 ? $total_tbl2_fin : '-';?>
                        <?php
                        // $total_rowncp =
                        //     ($rowngarukF['TOTAL_QTY'] ?? 0) +
                        //     ($rowpotongBuluF['TOTAL_POTONGBULUFLEECE'] ?? 0) +
                        //     ($rowngaruk['TOTAL_GARUK'] ?? 0) +
                        //     ($rowsisir['TOTAL_SISIR'] ?? 0) +
                        //     ($rowpotongbuluantipiling['TOTAL_POTONGBULUANTIPILING'] ?? 0) +
                        //     ($rowPeachSkin['TOTAL_PEACH_SKIN'] ?? 0) +
                        //     ($rowpotongbuluPeachSkin['TOTAL_POTONGBULUPEACH_SKIN'] ?? 0) +
                        //     ($rowARIO['TOTAL_AIRO'] ?? 0) +
                        //     ($rowPOLISHING['TOTAL_POLISHING'] ?? 0) +
                        //     ($rowWET_SUEDING['TOTAL_WET_SUEDING'] ?? 0);
                        // echo htmlspecialchars($total_rowncp > 0 ? $total_rowncp : '-');
                        ?>
                    </td>
                </tr>
            <!-- FINISHING ULANG -->

            <!-- DYE ULANG -->
                <tr>
                    <td style="border: 1px solid black;"><strong>DYEING ULANG</strong></td>
                    <td colspan="-1" align="center" style="border: 1px solid black;">
                        <?php $dye_fleece_ulang = ($row_tbl2bln['dye_fleece_ulang']!=0) ? $row_tbl2bln['dye_fleece_ulang'] : '-';
                                echo $dye_fleece_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $dye_ap_ulang = ($row_tbl2bln['dye_ap_ulang']!=0) ? $row_tbl2bln['dye_ap_ulang'] : '-';
                                echo $dye_ap_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $dye_peach_ulang = ($row_tbl2bln['dye_peach_ulang']!=0) ? $row_tbl2bln['dye_peach_ulang'] : '-';
                                echo $dye_peach_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $dye_pb_ulang = ($row_tbl2bln['dye_pb_ulang']!=0) ? $row_tbl2bln['dye_pb_ulang'] : '-';
                                echo $dye_pb_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $dye_oven_ulang = ($row_tbl2bln['dye_oven_ulang']!=0) ? $row_tbl2bln['dye_oven_ulang'] : '-';
                                echo $dye_oven_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?php 
                            $total_tbl2_dye= $row_tbl2bln['dye_fleece_ulang']+$row_tbl2bln['dye_ap_ulang']+$row_tbl2bln['dye_peach_ulang']+$row_tbl2bln['dye_pb_ulang']+$row_tbl2bln['dye_oven_ulang'];
                            echo $total_tbl2_dye > 0 ? $total_tbl2_dye : '-';?>
                        <?php
                        // $total_rowncp =
                        //     ($rowngarukF['TOTAL_QTY'] ?? 0) +
                        //     ($rowpotongBuluF['TOTAL_POTONGBULUFLEECE'] ?? 0) +
                        //     ($rowngaruk['TOTAL_GARUK'] ?? 0) +
                        //     ($rowsisir['TOTAL_SISIR'] ?? 0) +
                        //     ($rowpotongbuluantipiling['TOTAL_POTONGBULUANTIPILING'] ?? 0) +
                        //     ($rowPeachSkin['TOTAL_PEACH_SKIN'] ?? 0) +
                        //     ($rowpotongbuluPeachSkin['TOTAL_POTONGBULUPEACH_SKIN'] ?? 0) +
                        //     ($rowARIO['TOTAL_AIRO'] ?? 0) +
                        //     ($rowPOLISHING['TOTAL_POLISHING'] ?? 0) +
                        //     ($rowWET_SUEDING['TOTAL_WET_SUEDING'] ?? 0);
                        // echo htmlspecialchars($total_rowncp > 0 ? $total_rowncp : '-');
                        ?>
                    </td>
                </tr>
            <!-- DYE ULANG -->

            <!-- CQA ULANG -->
                <tr>
                    <td style="border: 1px solid black;"><strong>CQA ULANG</strong></td>
                    <td colspan="-1" align="center" style="border: 1px solid black;">
                        <?php $cqa_fleece_ulang = ($row_tbl2bln['cqa_fleece_ulang']!=0) ? $row_tbl2bln['cqa_fleece_ulang'] : '-';
                                echo $cqa_fleece_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $cqa_ap_ulang = ($row_tbl2bln['cqa_ap_ulang']!=0) ? $row_tbl2bln['cqa_ap_ulang'] : '-';
                                echo $cqa_ap_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $cqa_peach_ulang = ($row_tbl2bln['cqa_peach_ulang']!=0) ? $row_tbl2bln['cqa_peach_ulang'] : '-';
                                echo $cqa_peach_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $cqa_pb_ulang = ($row_tbl2bln['cqa_pb_ulang']!=0) ? $row_tbl2bln['cqa_pb_ulang'] : '-';
                                echo $cqa_pb_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php $cqa_oven_ulang = ($row_tbl2bln['cqa_oven_ulang']!=0) ? $row_tbl2bln['cqa_oven_ulang'] : '-';
                                echo $dye_oven_ulang;
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?= '-' ?></td>
                    <td align="center" style="border: 1px solid black;"><?php 
                            $total_tbl2_cqa= $row_tbl2bln['cqa_fleece_ulang']+$row_tbl2bln['cqa_ap_ulang']+$row_tbl2bln['cqa_peach_ulang']+$row_tbl2bln['cqa_pb_ulang']+$row_tbl2bln['cqa_oven_ulang'];
                            echo $total_tbl2_cqa > 0 ? $total_tbl2_cqa : '-';?>
                        <?php
                        // $total_rowncp =
                        //     ($rowngarukF['TOTAL_QTY'] ?? 0) +
                        //     ($rowpotongBuluF['TOTAL_POTONGBULUFLEECE'] ?? 0) +
                        //     ($rowngaruk['TOTAL_GARUK'] ?? 0) +
                        //     ($rowsisir['TOTAL_SISIR'] ?? 0) +
                        //     ($rowpotongbuluantipiling['TOTAL_POTONGBULUANTIPILING'] ?? 0) +
                        //     ($rowPeachSkin['TOTAL_PEACH_SKIN'] ?? 0) +
                        //     ($rowpotongbuluPeachSkin['TOTAL_POTONGBULUPEACH_SKIN'] ?? 0) +
                        //     ($rowARIO['TOTAL_AIRO'] ?? 0) +
                        //     ($rowPOLISHING['TOTAL_POLISHING'] ?? 0) +
                        //     ($rowWET_SUEDING['TOTAL_WET_SUEDING'] ?? 0);
                        // echo htmlspecialchars($total_rowncp > 0 ? $total_rowncp : '-');
                        ?>
                    </td>
                </tr>
            <!-- CQA ULANG -->

            <!-- TOTAL -->
                <tr>
                    <td style="border: 1px solid black;"><strong>TOTAL</strong></td>
                    <td colspan="-1" align="center" style="border: 1px solid black;">
                        <?php
                            $total_column1 =
                                ($row_tbl2bln['dye_fleece_ulang'] +$row_tbl2bln['cqa_fleece_ulang']+$row_tbl2bln['fin_fleece_ulang']+$row_tbl2bln['brs_fleece_ulang'] );
                            echo htmlspecialchars($total_column1 > 0 ? $total_column1 : '-');
                        ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                            echo '-';
                        ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                            $total_column3 = ($row_tbl2bln['dye_ap_ulang'] +$row_tbl2bln['cqa_ap_ulang']+$row_tbl2bln['fin_ap_ulang']+$row_tbl2bln['brs_ap_ulang'] );
                            echo htmlspecialchars($total_column3 > 0 ? $total_column3 : '-');
                            ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                            echo '-';
                        ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                            echo '-';
                        ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">-</td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                            $total_column7 = ($row_tbl2bln['dye_peach_ulang'] +$row_tbl2bln['cqa_peach_ulang']+$row_tbl2bln['fin_peach_ulang']+$row_tbl2bln['brs_peach_ulang'] );
                            echo htmlspecialchars($total_column7 > 0 ? $total_column7 : '-');
                        ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                            echo '-';
                        ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                            echo '-';
                        ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                            $total_column10 = ($row_tbl2bln['dye_pb_ulang'] +$row_tbl2bln['cqa_pb_ulang']+$row_tbl2bln['fin_pb_ulang']+$row_tbl2bln['brs_pb_ulang'] );
                            echo htmlspecialchars($total_column10 > 0 ? $total_column10 : '-');
                        ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                            $total_column11 = ($row_tbl2bln['dye_oven_ulang'] +$row_tbl2bln['cqa_oven_ulang']+$row_tbl2bln['fin_oven_ulang']+$row_tbl2bln['brs_oven_ulang'] );
                            echo htmlspecialchars($total_column11 > 0 ? $total_column11 : '-');
                        ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                           echo '-'
                        ?>
                    </td>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                           echo '-'
                        ?>
                    </td>
                            <?php
                            // Kolom bantu NCP
//                            $total_bantu_ncp_all = $total_bantu_ncp ?? 0;
						    $total_bantu_ncp_all = isset($total_bantu_ncp) ? $total_bantu_ncp : 0;
                            echo "<td style='border: 1px solid black;' align='center'>".'-'. "</td>";
                            ?>
                    <td align="center" style="border: 1px solid black;">
                        <?php
                            $grand_total = $total_tbl2_cqa+$total_tbl2_dye+$total_tbl2_fin+$total_tbl2_brs+$qty_ncp;
                                // ($total_rowncp ?? 0) +
                                // ($total_bantu_ncp ?? 0) +
                                // ($total_datafin ?? 0) +
                                // ($total_dyeing ?? 0);
                            echo htmlspecialchars($grand_total > 0 ? $grand_total : '-');
                            ?>
                    </td>
                </tr>
        </table>
<em>Ket : Brushing NCP hanya keterangan, tidak masuk hitungan total proses ulang.</em><br><br>		  
<!-- End Table 2 -->
<!-- Tabel-3.php -->
<strong>Data Stoppage Mesin Departemen Brushing</strong>
<table border="0" width="100%">
	<tr>
	<td width="70%" align="left" valign="top">

<table border="0" class="table-list1" width="100%">			
            <tr>
                <td align="center" colspan="3"><strong>Mesin</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>No</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>LM</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>KM</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>PT</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>KO</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>AP</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>PA</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>PM</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>GT</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>TG</strong></td>
                <td align="center" style="border: 1px solid black;"><strong>Total</strong></td>                
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
                    <td colspan="3" rowspan="6" align="left" style="border: 1px solid black;"><strong>GARUK</strong></td>
                    <td align="center" style="border: 1px solid black;">A</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_g['jam_garuk_A_LM'] != 0 || $lm_g['menit_garuk_A_LM'] != 0) {echo str_pad($lm_g['jam_garuk_A_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_A_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_g['jam_garuk_A_KM'] != 0 || $km_g['menit_garuk_A_KM'] != 0) {echo str_pad($km_g['jam_garuk_A_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_A_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_g['jam_garuk_A_PT'] != 0 || $pt_g['menit_garuk_A_PT'] != 0) {echo str_pad($pt_g['jam_garuk_A_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_A_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_g['jam_garuk_A_KO'] != 0 || $ko_g['menit_garuk_A_KO'] != 0) {echo str_pad($ko_g['jam_garuk_A_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_A_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_g['jam_garuk_A_AP'] != 0 || $ap_g['menit_garuk_A_AP'] != 0) {echo str_pad($ap_g['jam_garuk_A_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_A_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_g['jam_garuk_A_PA'] != 0 || $pa_g['menit_garuk_A_PA'] != 0) {echo str_pad($pa_g['jam_garuk_A_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_A_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_g['jam_garuk_A_PM'] != 0 || $pm_g['menit_garuk_A_PM'] != 0) {echo str_pad($pm_g['jam_garuk_A_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_A_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_g['jam_garuk_A_GT'] != 0 || $gt_g['menit_garuk_A_GT'] != 0) {echo str_pad($gt_g['jam_garuk_A_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_A_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_g['jam_garuk_A_TG'] != 0 || $tg_g['menit_garuk_A_TG'] != 0) {echo str_pad($tg_g['jam_garuk_A_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_A_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_garuk['jam_garuk_A'] != 0 || $sum_mesin_garuk['menit_garuk_A'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_A'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_A'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>                   
                </tr>
                <!-- Mesin B -->
                <tr>
                    <td align="center" style="border: 1px solid black;">B</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_g['jam_garuk_B_LM'] != 0 || $lm_g['menit_garuk_B_LM'] != 0) {echo str_pad($lm_g['jam_garuk_B_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_B_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_g['jam_garuk_B_KM'] != 0 || $km_g['menit_garuk_B_KM'] != 0) {echo str_pad($km_g['jam_garuk_B_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_B_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_g['jam_garuk_B_PT'] != 0 || $pt_g['menit_garuk_B_PT'] != 0) {echo str_pad($pt_g['jam_garuk_B_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_B_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_g['jam_garuk_B_KO'] != 0 || $ko_g['menit_garuk_B_KO'] != 0) {echo str_pad($ko_g['jam_garuk_B_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_B_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_g['jam_garuk_B_AP'] != 0 || $ap_g['menit_garuk_B_AP'] != 0) {echo str_pad($ap_g['jam_garuk_B_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_B_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_g['jam_garuk_B_PA'] != 0 || $pa_g['menit_garuk_B_PA'] != 0) {echo str_pad($pa_g['jam_garuk_B_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_B_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_g['jam_garuk_B_PM'] != 0 || $pm_g['menit_garuk_B_PM'] != 0) {echo str_pad($pm_g['jam_garuk_B_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_B_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_g['jam_garuk_B_GT'] != 0 || $gt_g['menit_garuk_B_GT'] != 0) {echo str_pad($gt_g['jam_garuk_B_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_B_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_g['jam_garuk_B_TG'] != 0 || $tg_g['menit_garuk_B_TG'] != 0) {echo str_pad($tg_g['jam_garuk_B_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_B_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_garuk['jam_garuk_B'] != 0 || $sum_mesin_garuk['menit_garuk_B'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_B'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_B'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>                    
                </tr>
                <!-- Mesin C -->
                <tr>
                    <td align="center" style="border: 1px solid black;">C</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_g['jam_garuk_C_LM'] != 0 || $lm_g['menit_garuk_C_LM'] != 0) {echo str_pad($lm_g['jam_garuk_C_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_C_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_g['jam_garuk_C_KM'] != 0 || $km_g['menit_garuk_C_KM'] != 0) {echo str_pad($km_g['jam_garuk_C_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_C_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_g['jam_garuk_C_PT'] != 0 || $pt_g['menit_garuk_C_PT'] != 0) {echo str_pad($pt_g['jam_garuk_C_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_C_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_g['jam_garuk_C_KO'] != 0 || $ko_g['menit_garuk_C_KO'] != 0) {echo str_pad($ko_g['jam_garuk_C_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_C_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_g['jam_garuk_C_AP'] != 0 || $ap_g['menit_garuk_C_AP'] != 0) {echo str_pad($ap_g['jam_garuk_C_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_C_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_g['jam_garuk_C_PA'] != 0 || $pa_g['menit_garuk_C_PA'] != 0) {echo str_pad($pa_g['jam_garuk_C_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_C_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_g['jam_garuk_C_PM'] != 0 || $pm_g['menit_garuk_C_PM'] != 0) {echo str_pad($pm_g['jam_garuk_C_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_C_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_g['jam_garuk_C_GT'] != 0 || $gt_g['menit_garuk_C_GT'] != 0) {echo str_pad($gt_g['jam_garuk_C_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_C_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_g['jam_garuk_C_TG'] != 0 || $tg_g['menit_garuk_C_TG'] != 0) {echo str_pad($tg_g['jam_garuk_C_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_C_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_garuk['jam_garuk_C'] != 0 || $sum_mesin_garuk['menit_garuk_C'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_C'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_C'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin D -->
                <tr>
                    <td align="center" style="border: 1px solid black;">D</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_g['jam_garuk_D_LM'] != 0 || $lm_g['menit_garuk_D_LM'] != 0) {echo str_pad($lm_g['jam_garuk_D_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_D_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_g['jam_garuk_D_KM'] != 0 || $km_g['menit_garuk_D_KM'] != 0) {echo str_pad($km_g['jam_garuk_D_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_D_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_g['jam_garuk_D_PT'] != 0 || $pt_g['menit_garuk_D_PT'] != 0) {echo str_pad($pt_g['jam_garuk_D_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_D_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_g['jam_garuk_D_KO'] != 0 || $ko_g['menit_garuk_D_KO'] != 0) {echo str_pad($ko_g['jam_garuk_D_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_D_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_g['jam_garuk_D_AP'] != 0 || $ap_g['menit_garuk_D_AP'] != 0) {echo str_pad($ap_g['jam_garuk_D_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_D_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_g['jam_garuk_D_PA'] != 0 || $pa_g['menit_garuk_D_PA'] != 0) {echo str_pad($pa_g['jam_garuk_D_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_D_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_g['jam_garuk_D_PM'] != 0 || $pm_g['menit_garuk_D_PM'] != 0) {echo str_pad($pm_g['jam_garuk_D_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_D_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_g['jam_garuk_D_GT'] != 0 || $gt_g['menit_garuk_D_GT'] != 0) {echo str_pad($gt_g['jam_garuk_D_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_D_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_g['jam_garuk_D_TG'] != 0 || $tg_g['menit_garuk_D_TG'] != 0) {echo str_pad($tg_g['jam_garuk_D_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_D_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_garuk['jam_garuk_D'] != 0 || $sum_mesin_garuk['menit_garuk_D'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_D'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_D'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin E -->
                <tr>
                    <td align="center" style="border: 1px solid black;">E</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_g['jam_garuk_E_LM'] != 0 || $lm_g['menit_garuk_E_LM'] != 0) {echo str_pad($lm_g['jam_garuk_E_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_E_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_g['jam_garuk_E_KM'] != 0 || $km_g['menit_garuk_E_KM'] != 0) {echo str_pad($km_g['jam_garuk_E_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_E_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_g['jam_garuk_E_PT'] != 0 || $pt_g['menit_garuk_E_PT'] != 0) {echo str_pad($pt_g['jam_garuk_E_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_E_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_g['jam_garuk_E_KO'] != 0 || $ko_g['menit_garuk_E_KO'] != 0) {echo str_pad($ko_g['jam_garuk_E_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_E_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_g['jam_garuk_E_AP'] != 0 || $ap_g['menit_garuk_E_AP'] != 0) {echo str_pad($ap_g['jam_garuk_E_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_E_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_g['jam_garuk_E_PA'] != 0 || $pa_g['menit_garuk_E_PA'] != 0) {echo str_pad($pa_g['jam_garuk_E_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_E_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_g['jam_garuk_E_PM'] != 0 || $pm_g['menit_garuk_E_PM'] != 0) {echo str_pad($pm_g['jam_garuk_E_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_E_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_g['jam_garuk_E_GT'] != 0 || $gt_g['menit_garuk_E_GT'] != 0) {echo str_pad($gt_g['jam_garuk_E_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_E_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_g['jam_garuk_E_TG'] != 0 || $tg_g['menit_garuk_E_TG'] != 0) {echo str_pad($tg_g['jam_garuk_E_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_E_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_garuk['jam_garuk_E'] != 0 || $sum_mesin_garuk['menit_garuk_E'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_E'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_E'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin F -->
                <tr>
                    <td align="center" style="border: 1px solid black;">F</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_g['jam_garuk_F_LM'] != 0 || $lm_g['menit_garuk_F_LM'] != 0) {echo str_pad($lm_g['jam_garuk_F_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_F_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_g['jam_garuk_F_KM'] != 0 || $km_g['menit_garuk_F_KM'] != 0) {echo str_pad($km_g['jam_garuk_F_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_F_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_g['jam_garuk_F_PT'] != 0 || $pt_g['menit_garuk_F_PT'] != 0) {echo str_pad($pt_g['jam_garuk_F_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_F_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_g['jam_garuk_F_KO'] != 0 || $ko_g['menit_garuk_F_KO'] != 0) {echo str_pad($ko_g['jam_garuk_F_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_F_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_g['jam_garuk_F_AP'] != 0 || $ap_g['menit_garuk_F_AP'] != 0) {echo str_pad($ap_g['jam_garuk_F_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_F_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_g['jam_garuk_F_PA'] != 0 || $pa_g['menit_garuk_F_PA'] != 0) {echo str_pad($pa_g['jam_garuk_F_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_F_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_g['jam_garuk_F_PM'] != 0 || $pm_g['menit_garuk_F_PM'] != 0) {echo str_pad($pm_g['jam_garuk_F_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_F_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_g['jam_garuk_F_GT'] != 0 || $gt_g['menit_garuk_F_GT'] != 0) {echo str_pad($gt_g['jam_garuk_F_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_F_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_g['jam_garuk_F_TG'] != 0 || $tg_g['menit_garuk_F_TG'] != 0) {echo str_pad($tg_g['jam_garuk_F_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_F_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_garuk['jam_garuk_F'] != 0 || $sum_mesin_garuk['menit_garuk_F'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_F'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_F'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
            <!-- End Garuk -->
            <!-- Untuk Kolom Sisir -->
                <tr>
                    <?php 
                    $query_sisir9 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_sisir_TG,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'TG' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_sisir_TG
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir9    = mysqli_query($cona,$query_sisir9);
                                    $tg_sisir             = mysqli_fetch_assoc($stmt_sisir9);
                    $query_sisir8 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_sisir_GT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'GT' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_sisir_GT
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir8    = mysqli_query($cona,$query_sisir8);
                                    $gt_sisir             = mysqli_fetch_assoc($stmt_sisir8);
                    $query_sisir7 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_sisir_PM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PM' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_sisir_PM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir7    = mysqli_query($cona,$query_sisir7);
                                    $pm_sisir             = mysqli_fetch_assoc($stmt_sisir7);
                    $query_sisir6 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_sisir_PA,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PA' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_sisir_PA
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir6    = mysqli_query($cona,$query_sisir6);
                                    $pa_sisir             = mysqli_fetch_assoc($stmt_sisir6);
                    $query_sisir5 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_sisir_AP,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'AP' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_sisir_AP
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir5    = mysqli_query($cona,$query_sisir5);
                                    $ap_sisir             = mysqli_fetch_assoc($stmt_sisir5);
                    $query_sisir4 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_sisir_KO,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KO' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_sisir_KO
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir4    = mysqli_query($cona,$query_sisir4);
                                    $ko_sisir             = mysqli_fetch_assoc($stmt_sisir4);
                    $query_sisir3 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_sisir_PT,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'PT' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_sisir_PT
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir3    = mysqli_query($cona,$query_sisir3);
                                    $pt_sisir             = mysqli_fetch_assoc($stmt_sisir3);
                    $query_sisir2 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_sisir_KM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'KM' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_sisir_KM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir2    = mysqli_query($cona,$query_sisir2);
                                    $km_sisir             = mysqli_fetch_assoc($stmt_sisir2);
                    $query_sisir1 = "SELECT
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_sisir_LM,
                                            SUM(
                                                CASE 
                                                    WHEN kode_stop = 'LM' 
                                                    AND kode_operation IN ('COM1', 'COM2')
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_sisir_LM
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                                    $stmt_sisir1    = mysqli_query($cona,$query_sisir1);
                                    $lm_sisir             = mysqli_fetch_assoc($stmt_sisir1);
                            // Total Sisir
                    $query_mesin_sisir = "SELECT
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('COM1', 'COM2')
                                                    THEN FLOOR(durasi_jam_stop) 
                                                    ELSE 0 
                                                END
                                                ) AS jam_sisir,
                                            SUM(
                                                CASE
                                                    WHEN kode_operation IN ('COM1', 'COM2')
                                                    THEN round((durasi_jam_stop-floor(durasi_jam_stop))*60) 
                                                    ELSE 0 
                                                END
                                                ) AS menit_sisir
                                            FROM
                                                tbl_stoppage
                                            WHERE dept ='BRS'
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_sisir= mysqli_query($cona,$query_mesin_sisir);
                        $sum_mesin_sisir= mysqli_fetch_assoc($stmt_mesin_sisir);
                        ?>
                    <td colspan="3" align="left" style="border: 1px solid black;"><strong>SISIR</strong></td>
                    <td align="center" style="border: 1px solid black;">01</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_sisir['jam_sisir_LM'] != 0 || $lm_sisir['menit_sisir_LM'] != 0) {echo str_pad($lm_sisir['jam_sisir_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_sisir['menit_sisir_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_sisir['jam_sisir_KM'] != 0 || $km_sisir['menit_sisir_KM'] != 0) {echo str_pad($km_sisir['jam_sisir_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_sisir['menit_sisir_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_sisir['jam_sisir_PT'] != 0 || $pt_sisir['menit_sisir_PT'] != 0) {echo str_pad($pt_sisir['jam_sisir_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_sisir['menit_sisir_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_sisir['jam_sisir_KO'] != 0 || $ko_sisir['menit_sisir_KO'] != 0) {echo str_pad($ko_sisir['jam_sisir_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_sisir['menit_sisir_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_sisir['jam_sisir_AP'] != 0 || $ap_sisir['menit_sisir_AP'] != 0) {echo str_pad($ap_sisir['jam_sisir_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_sisir['menit_sisir_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_sisir['jam_sisir_PA'] != 0 || $pa_sisir['menit_sisir_PA'] != 0) {echo str_pad($pa_sisir['jam_sisir_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_sisir['menit_sisir_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_sisir['jam_sisir_PM'] != 0 || $pm_sisir['menit_sisir_PM'] != 0) {echo str_pad($pm_sisir['jam_sisir_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_sisir['menit_sisir_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_sisir['jam_sisir_GT'] != 0 || $gt_sisir['menit_sisir_GT'] != 0) {echo str_pad($gt_sisir['jam_sisir_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_sisir['menit_sisir_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_sisir['jam_sisir_TG'] != 0 || $tg_sisir['menit_sisir_TG'] != 0) {echo str_pad($tg_sisir['jam_sisir_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_sisir['menit_sisir_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_sisir['jam_sisir'] != 0 || $sum_mesin_sisir['menit_sisir'] != 0) {echo str_pad($sum_mesin_sisir['jam_sisir'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_sisir['menit_sisir'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>                    
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
                                            AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                            AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                            AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                            AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                            AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                            AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                            AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                            AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                            AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_pb= mysqli_query($cona,$query_mesin_pb);
                        $sum_mesin_pb= mysqli_fetch_assoc($stmt_mesin_pb);
                    ?>
                <!-- Mesin 01 -->
                    <td colspan="3" rowspan="8" align="left" style="border: 1px solid black;"><strong>POTONG BULU</strong></td>
                    <td align="center" style="border: 1px solid black;">01</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_pb['jam_pb_01_LM'] != 0 || $lm_pb['menit_pb_01_LM'] != 0) {echo str_pad($lm_pb['jam_pb_01_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_01_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_pb['jam_pb_01_KM'] != 0 || $km_pb['menit_pb_01_KM'] != 0) {echo str_pad($km_pb['jam_pb_01_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_01_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_pb['jam_pb_01_PT'] != 0 || $pt_pb['menit_pb_01_PT'] != 0) {echo str_pad($pt_pb['jam_pb_01_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_01_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_pb['jam_pb_01_KO'] != 0 || $ko_pb['menit_pb_01_KO'] != 0) {echo str_pad($ko_pb['jam_pb_01_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_01_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_pb['jam_pb_01_AP'] != 0 || $ap_pb['menit_pb_01_AP'] != 0) {echo str_pad($ap_pb['jam_pb_01_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_01_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_pb['jam_pb_01_PA'] != 0 || $pa_pb['menit_pb_01_PA'] != 0) {echo str_pad($pa_pb['jam_pb_01_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_01_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_pb['jam_pb_01_PM'] != 0 || $pm_pb['menit_pb_01_PM'] != 0) {echo str_pad($pm_pb['jam_pb_01_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_01_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_pb['jam_pb_01_GT'] != 0 || $gt_pb['menit_pb_01_GT'] != 0) {echo str_pad($gt_pb['jam_pb_01_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_01_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_pb['jam_pb_01_TG'] != 0 || $tg_pb['menit_pb_01_TG'] != 0) {echo str_pad($tg_pb['jam_pb_01_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_01_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_pb['jam_pb_01'] != 0 || $sum_mesin_pb['menit_pb_01'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_01'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_01'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>                    
                </tr>
                <!-- Mesin 02 -->
                <tr>
                    <td align="center" style="border: 1px solid black;">02</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_pb['jam_pb_02_LM'] != 0 || $lm_pb['menit_pb_02_LM'] != 0) {echo str_pad($lm_pb['jam_pb_02_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_02_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_pb['jam_pb_02_KM'] != 0 || $km_pb['menit_pb_02_KM'] != 0) {echo str_pad($km_pb['jam_pb_02_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_02_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_pb['jam_pb_02_PT'] != 0 || $pt_pb['menit_pb_02_PT'] != 0) {echo str_pad($pt_pb['jam_pb_02_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_02_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_pb['jam_pb_02_KO'] != 0 || $ko_pb['menit_pb_02_KO'] != 0) {echo str_pad($ko_pb['jam_pb_02_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_02_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_pb['jam_pb_02_AP'] != 0 || $ap_pb['menit_pb_02_AP'] != 0) {echo str_pad($ap_pb['jam_pb_02_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_02_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_pb['jam_pb_02_PA'] != 0 || $pa_pb['menit_pb_02_PA'] != 0) {echo str_pad($pa_pb['jam_pb_02_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_02_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_pb['jam_pb_02_PM'] != 0 || $pm_pb['menit_pb_02_PM'] != 0) {echo str_pad($pm_pb['jam_pb_02_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_02_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_pb['jam_pb_02_GT'] != 0 || $gt_pb['menit_pb_02_GT'] != 0) {echo str_pad($gt_pb['jam_pb_02_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_02_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_pb['jam_pb_02_TG'] != 0 || $tg_pb['menit_pb_02_TG'] != 0) {echo str_pad($tg_pb['jam_pb_02_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_02_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_pb['jam_pb_02'] != 0 || $sum_mesin_pb['menit_pb_02'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_02'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_02'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 03 -->
                <tr>
                    <td align="center" style="border: 1px solid black;">03</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_pb['jam_pb_03_LM'] != 0 || $lm_pb['menit_pb_03_LM'] != 0) {echo str_pad($lm_pb['jam_pb_03_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_03_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_pb['jam_pb_03_KM'] != 0 || $km_pb['menit_pb_03_KM'] != 0) {echo str_pad($km_pb['jam_pb_03_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_03_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_pb['jam_pb_03_PT'] != 0 || $pt_pb['menit_pb_03_PT'] != 0) {echo str_pad($pt_pb['jam_pb_03_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_03_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_pb['jam_pb_03_KO'] != 0 || $ko_pb['menit_pb_03_KO'] != 0) {echo str_pad($ko_pb['jam_pb_03_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_03_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_pb['jam_pb_03_AP'] != 0 || $ap_pb['menit_pb_03_AP'] != 0) {echo str_pad($ap_pb['jam_pb_03_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_03_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_pb['jam_pb_03_PA'] != 0 || $pa_pb['menit_pb_03_PA'] != 0) {echo str_pad($pa_pb['jam_pb_03_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_03_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_pb['jam_pb_03_PM'] != 0 || $pm_pb['menit_pb_03_PM'] != 0) {echo str_pad($pm_pb['jam_pb_03_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_03_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_pb['jam_pb_03_GT'] != 0 || $gt_pb['menit_pb_03_GT'] != 0) {echo str_pad($gt_pb['jam_pb_03_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_03_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_pb['jam_pb_03_TG'] != 0 || $tg_pb['menit_pb_03_TG'] != 0) {echo str_pad($tg_pb['jam_pb_03_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_03_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_pb['jam_pb_03'] != 0 || $sum_mesin_pb['menit_pb_03'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_03'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_03'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 04 -->
                <tr>
                    <td align="center" style="border: 1px solid black;">04</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_pb['jam_pb_04_LM'] != 0 || $lm_pb['menit_pb_04_LM'] != 0) {echo str_pad($lm_pb['jam_pb_04_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_04_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_pb['jam_pb_04_KM'] != 0 || $km_pb['menit_pb_04_KM'] != 0) {echo str_pad($km_pb['jam_pb_04_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_04_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_pb['jam_pb_04_PT'] != 0 || $pt_pb['menit_pb_04_PT'] != 0) {echo str_pad($pt_pb['jam_pb_04_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_04_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_pb['jam_pb_04_KO'] != 0 || $ko_pb['menit_pb_04_KO'] != 0) {echo str_pad($ko_pb['jam_pb_04_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_04_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_pb['jam_pb_04_AP'] != 0 || $ap_pb['menit_pb_04_AP'] != 0) {echo str_pad($ap_pb['jam_pb_04_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_04_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_pb['jam_pb_04_PA'] != 0 || $pa_pb['menit_pb_04_PA'] != 0) {echo str_pad($pa_pb['jam_pb_04_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_04_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_pb['jam_pb_04_PM'] != 0 || $pm_pb['menit_pb_04_PM'] != 0) {echo str_pad($pm_pb['jam_pb_04_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_04_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_pb['jam_pb_04_GT'] != 0 || $gt_pb['menit_pb_04_GT'] != 0) {echo str_pad($gt_pb['jam_pb_04_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_04_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_pb['jam_pb_04_TG'] != 0 || $tg_pb['menit_pb_04_TG'] != 0) {echo str_pad($tg_pb['jam_pb_04_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_04_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_pb['jam_pb_04'] != 0 || $sum_mesin_pb['menit_pb_04'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_04'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_04'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 05 -->
                <tr>
                    <td align="center" style="border: 1px solid black;">05</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_pb['jam_pb_05_LM'] != 0 || $lm_pb['menit_pb_05_LM'] != 0) {echo str_pad($lm_pb['jam_pb_05_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_05_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_pb['jam_pb_05_KM'] != 0 || $km_pb['menit_pb_05_KM'] != 0) {echo str_pad($km_pb['jam_pb_05_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_05_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_pb['jam_pb_05_PT'] != 0 || $pt_pb['menit_pb_05_PT'] != 0) {echo str_pad($pt_pb['jam_pb_05_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_05_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_pb['jam_pb_05_KO'] != 0 || $ko_pb['menit_pb_05_KO'] != 0) {echo str_pad($ko_pb['jam_pb_05_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_05_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_pb['jam_pb_05_AP'] != 0 || $ap_pb['menit_pb_05_AP'] != 0) {echo str_pad($ap_pb['jam_pb_05_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_05_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_pb['jam_pb_05_PA'] != 0 || $pa_pb['menit_pb_05_PA'] != 0) {echo str_pad($pa_pb['jam_pb_05_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_05_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_pb['jam_pb_05_PM'] != 0 || $pm_pb['menit_pb_05_PM'] != 0) {echo str_pad($pm_pb['jam_pb_05_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_05_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_pb['jam_pb_05_GT'] != 0 || $gt_pb['menit_pb_05_GT'] != 0) {echo str_pad($gt_pb['jam_pb_05_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_05_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_pb['jam_pb_05_TG'] != 0 || $tg_pb['menit_pb_05_TG'] != 0) {echo str_pad($tg_pb['jam_pb_05_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_05_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_pb['jam_pb_05'] != 0 || $sum_mesin_pb['menit_pb_05'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_05'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_05'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 06 -->
                <tr>
                    <td align="center" style="border: 1px solid black;">06</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_pb['jam_pb_06_LM'] != 0 || $lm_pb['menit_pb_06_LM'] != 0) {echo str_pad($lm_pb['jam_pb_06_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_06_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_pb['jam_pb_06_KM'] != 0 || $km_pb['menit_pb_06_KM'] != 0) {echo str_pad($km_pb['jam_pb_06_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_06_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_pb['jam_pb_06_PT'] != 0 || $pt_pb['menit_pb_06_PT'] != 0) {echo str_pad($pt_pb['jam_pb_06_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_06_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_pb['jam_pb_06_KO'] != 0 || $ko_pb['menit_pb_06_KO'] != 0) {echo str_pad($ko_pb['jam_pb_06_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_06_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_pb['jam_pb_06_AP'] != 0 || $ap_pb['menit_pb_06_AP'] != 0) {echo str_pad($ap_pb['jam_pb_06_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_06_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_pb['jam_pb_06_PA'] != 0 || $pa_pb['menit_pb_06_PA'] != 0) {echo str_pad($pa_pb['jam_pb_06_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_06_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_pb['jam_pb_06_PM'] != 0 || $pm_pb['menit_pb_06_PM'] != 0) {echo str_pad($pm_pb['jam_pb_06_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_06_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_pb['jam_pb_06_GT'] != 0 || $gt_pb['menit_pb_06_GT'] != 0) {echo str_pad($gt_pb['jam_pb_06_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_06_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_pb['jam_pb_06_TG'] != 0 || $tg_pb['menit_pb_06_TG'] != 0) {echo str_pad($tg_pb['jam_pb_06_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_06_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_pb['jam_pb_06'] != 0 || $sum_mesin_pb['menit_pb_06'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_06'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_06'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 07 -->
                <tr>
                    <td align="center" style="border: 1px solid black;">07</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_pb['jam_pb_07_LM'] != 0 || $lm_pb['menit_pb_07_LM'] != 0) {echo str_pad($lm_pb['jam_pb_07_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_07_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_pb['jam_pb_07_KM'] != 0 || $km_pb['menit_pb_07_KM'] != 0) {echo str_pad($km_pb['jam_pb_07_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_07_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_pb['jam_pb_07_PT'] != 0 || $pt_pb['menit_pb_07_PT'] != 0) {echo str_pad($pt_pb['jam_pb_07_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_07_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_pb['jam_pb_07_KO'] != 0 || $ko_pb['menit_pb_07_KO'] != 0) {echo str_pad($ko_pb['jam_pb_07_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_07_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_pb['jam_pb_07_AP'] != 0 || $ap_pb['menit_pb_07_AP'] != 0) {echo str_pad($ap_pb['jam_pb_07_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_07_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_pb['jam_pb_07_PA'] != 0 || $pa_pb['menit_pb_07_PA'] != 0) {echo str_pad($pa_pb['jam_pb_07_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_07_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_pb['jam_pb_07_PM'] != 0 || $pm_pb['menit_pb_07_PM'] != 0) {echo str_pad($pm_pb['jam_pb_07_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_07_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_pb['jam_pb_07_GT'] != 0 || $gt_pb['menit_pb_07_GT'] != 0) {echo str_pad($gt_pb['jam_pb_07_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_07_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_pb['jam_pb_07_TG'] != 0 || $tg_pb['menit_pb_07_TG'] != 0) {echo str_pad($tg_pb['jam_pb_07_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_07_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_pb['jam_pb_07'] != 0 || $sum_mesin_pb['menit_pb_07'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_07'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_07'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 08 -->
                <tr>
                    <td align="center" style="border: 1px solid black;">08</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_pb['jam_pb_08_LM'] != 0 || $lm_pb['menit_pb_08_LM'] != 0) {echo str_pad($lm_pb['jam_pb_08_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_08_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_pb['jam_pb_08_KM'] != 0 || $km_pb['menit_pb_08_KM'] != 0) {echo str_pad($km_pb['jam_pb_08_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_08_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_pb['jam_pb_08_PT'] != 0 || $pt_pb['menit_pb_08_PT'] != 0) {echo str_pad($pt_pb['jam_pb_08_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_08_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_pb['jam_pb_08_KO'] != 0 || $ko_pb['menit_pb_08_KO'] != 0) {echo str_pad($ko_pb['jam_pb_08_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_08_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_pb['jam_pb_08_AP'] != 0 || $ap_pb['menit_pb_08_AP'] != 0) {echo str_pad($ap_pb['jam_pb_08_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_08_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_pb['jam_pb_08_PA'] != 0 || $pa_pb['menit_pb_08_PA'] != 0) {echo str_pad($pa_pb['jam_pb_08_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_08_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_pb['jam_pb_08_PM'] != 0 || $pm_pb['menit_pb_08_PM'] != 0) {echo str_pad($pm_pb['jam_pb_08_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_08_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_pb['jam_pb_08_GT'] != 0 || $gt_pb['menit_pb_08_GT'] != 0) {echo str_pad($gt_pb['jam_pb_08_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_08_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_pb['jam_pb_08_TG'] != 0 || $tg_pb['menit_pb_08_TG'] != 0) {echo str_pad($tg_pb['jam_pb_08_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_08_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_pb['jam_pb_08'] != 0 || $sum_mesin_pb['menit_pb_08'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_08'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_08'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_peach1= mysqli_query($cona,$query_mesin_peach1);
                        $sum_mesin_peach= mysqli_fetch_assoc($stmt_mesin_peach1);
                                        ?>
                <!-- Untuk Mesin 01 -->
                    <tr>
                    <td colspan="3" rowspan="5" align="left" style="border: 1px solid black;"><strong>PEACH SKIN</strong></td>
                    <td align="center" style="border: 1px solid black;">01</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm['jam_peach_01_LM'] != 0 || $lm['menit_peach_01_LM'] != 0) {echo str_pad($lm['jam_peach_01_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_01_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km['jam_peach_01_KM'] != 0 || $km['menit_peach_01_KM'] != 0) {echo str_pad($km['jam_peach_01_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_01_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt['jam_peach_01_PT'] != 0 || $pt['menit_peach_01_PT'] != 0) {echo str_pad($pt['jam_peach_01_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_01_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko['jam_peach_01_KO'] != 0 || $ko['menit_peach_01_KO'] != 0) {echo str_pad($ko['jam_peach_01_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_01_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap['jam_peach_01_AP'] != 0 || $ap['menit_peach_01_AP'] != 0) {echo str_pad($ap['jam_peach_01_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_01_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa['jam_peach_01_PA'] != 0 || $pa['menit_peach_01_PA'] != 0) {echo str_pad($pa['jam_peach_01_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_01_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm['jam_peach_01_PM'] != 0 || $pm['menit_peach_01_PM'] != 0) {echo str_pad($pm['jam_peach_01_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_01_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt['jam_peach_01_GT'] != 0 || $gt['menit_peach_01_GT'] != 0) {echo str_pad($gt['jam_peach_01_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_01_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg['jam_peach_01_TG'] != 0 || $tg['menit_peach_01_TG'] != 0) {echo str_pad($tg['jam_peach_01_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_01_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_peach['jam_peach_01'] != 0 || $sum_mesin_peach['menit_peach_01'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_01'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_01'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Untuk Mesin 02 -->
                    <tr>
                    <td align="center" style="border: 1px solid black;">02</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm['jam_peach_02_LM'] != 0 || $lm['menit_peach_02_LM'] != 0) {echo str_pad($lm['jam_peach_02_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_02_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km['jam_peach_02_KM'] != 0 || $km['menit_peach_02_KM'] != 0) {echo str_pad($km['jam_peach_02_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_02_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt['jam_peach_02_PT'] != 0 || $pt['menit_peach_02_PT'] != 0) {echo str_pad($pt['jam_peach_02_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_02_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko['jam_peach_02_KO'] != 0 || $ko['menit_peach_02_KO'] != 0) {echo str_pad($ko['jam_peach_02_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_02_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap['jam_peach_02_AP'] != 0 || $ap['menit_peach_02_AP'] != 0) {echo str_pad($ap['jam_peach_02_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_02_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa['jam_peach_02_PA'] != 0 || $pa['menit_peach_02_PA'] != 0) {echo str_pad($pa['jam_peach_02_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_02_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm['jam_peach_02_PM'] != 0 || $pm['menit_peach_02_PM'] != 0) {echo str_pad($pm['jam_peach_02_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_02_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt['jam_peach_02_GT'] != 0 || $gt['menit_peach_02_GT'] != 0) {echo str_pad($gt['jam_peach_02_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_02_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg['jam_peach_02_TG'] != 0 || $tg['menit_peach_02_TG'] != 0) {echo str_pad($tg['jam_peach_02_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_02_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_peach['jam_peach_02'] != 0 || $sum_mesin_peach['menit_peach_02'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_02'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_02'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Untuk Mesin 03 -->
                    <tr>
                    <td align="center" style="border: 1px solid black;">03</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm['jam_peach_03_LM'] != 0 || $lm['menit_peach_03_LM'] != 0) {echo str_pad($lm['jam_peach_03_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_03_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km['jam_peach_03_KM'] != 0 || $km['menit_peach_03_KM'] != 0) {echo str_pad($km['jam_peach_03_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_03_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt['jam_peach_03_PT'] != 0 || $pt['menit_peach_03_PT'] != 0) {echo str_pad($pt['jam_peach_03_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_03_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko['jam_peach_03_KO'] != 0 || $ko['menit_peach_03_KO'] != 0) {echo str_pad($ko['jam_peach_03_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_03_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap['jam_peach_03_AP'] != 0 || $ap['menit_peach_03_AP'] != 0) {echo str_pad($ap['jam_peach_03_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_03_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa['jam_peach_03_PA'] != 0 || $pa['menit_peach_03_PA'] != 0) {echo str_pad($pa['jam_peach_03_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_03_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm['jam_peach_03_PM'] != 0 || $pm['menit_peach_03_PM'] != 0) {echo str_pad($pm['jam_peach_03_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_03_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt['jam_peach_03_GT'] != 0 || $gt['menit_peach_03_GT'] != 0) {echo str_pad($gt['jam_peach_03_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_03_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg['jam_peach_03_TG'] != 0 || $tg['menit_peach_03_TG'] != 0) {echo str_pad($tg['jam_peach_03_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_03_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_peach['jam_peach_03'] != 0 || $sum_mesin_peach['menit_peach_03'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_03'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_03'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Untuk Mesin 04 -->
                    <tr>
                    <td align="center" style="border: 1px solid black;">04</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm['jam_peach_04_LM'] != 0 || $lm['menit_peach_04_LM'] != 0) {echo str_pad($lm['jam_peach_04_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_04_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km['jam_peach_04_KM'] != 0 || $km['menit_peach_04_KM'] != 0) {echo str_pad($km['jam_peach_04_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_04_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt['jam_peach_04_PT'] != 0 || $pt['menit_peach_04_PT'] != 0) {echo str_pad($pt['jam_peach_04_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_04_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko['jam_peach_04_KO'] != 0 || $ko['menit_peach_04_KO'] != 0) {echo str_pad($ko['jam_peach_04_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_04_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap['jam_peach_04_AP'] != 0 || $ap['menit_peach_04_AP'] != 0) {echo str_pad($ap['jam_peach_04_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_04_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa['jam_peach_04_PA'] != 0 || $pa['menit_peach_04_PA'] != 0) {echo str_pad($pa['jam_peach_04_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_04_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm['jam_peach_04_PM'] != 0 || $pm['menit_peach_04_PM'] != 0) {echo str_pad($pm['jam_peach_04_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_04_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt['jam_peach_04_GT'] != 0 || $gt['menit_peach_04_GT'] != 0) {echo str_pad($gt['jam_peach_04_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_04_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg['jam_peach_04_TG'] != 0 || $tg['menit_peach_04_TG'] != 0) {echo str_pad($tg['jam_peach_04_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_04_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_peach['jam_peach_04'] != 0 || $sum_mesin_peach['menit_peach_04'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_04'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_04'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Untuk Mesin 05 -->
                    <tr>
                    <td align="center" style="border: 1px solid black;">05</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm['jam_peach_05_LM'] != 0 || $lm['menit_peach_05_LM'] != 0) {echo str_pad($lm['jam_peach_05_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_05_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km['jam_peach_05_KM'] != 0 || $km['menit_peach_05_KM'] != 0) {echo str_pad($km['jam_peach_05_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_05_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt['jam_peach_05_PT'] != 0 || $pt['menit_peach_05_PT'] != 0) {echo str_pad($pt['jam_peach_05_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_05_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko['jam_peach_05_KO'] != 0 || $ko['menit_peach_05_KO'] != 0) {echo str_pad($ko['jam_peach_05_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_05_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap['jam_peach_05_AP'] != 0 || $ap['menit_peach_05_AP'] != 0) {echo str_pad($ap['jam_peach_05_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_05_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa['jam_peach_05_PA'] != 0 || $pa['menit_peach_05_PA'] != 0) {echo str_pad($pa['jam_peach_05_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_05_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm['jam_peach_05_PM'] != 0 || $pm['menit_peach_05_PM'] != 0) {echo str_pad($pm['jam_peach_05_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_05_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt['jam_peach_05_GT'] != 0 || $gt['menit_peach_05_GT'] != 0) {echo str_pad($gt['jam_peach_05_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_05_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg['jam_peach_05_TG'] != 0 || $tg['menit_peach_05_TG'] != 0) {echo str_pad($tg['jam_peach_05_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_05_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_peach['jam_peach_05'] != 0 || $sum_mesin_peach['menit_peach_05'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_05'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_05'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <!-- <td align="center" style="border: 1px solid black;">Ini untuk total</td> -->
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_airo= mysqli_query($cona,$query_mesin_airo);
                        $sum_mesin_airo= mysqli_fetch_assoc($stmt_mesin_airo);
                        ?>
                    <td colspan="3" rowspan="2" align="left" style="border: 1px solid black;"><strong>AIRO</strong></td>
                    <td align="center" style="border: 1px solid black;">01</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_airo['jam_01_airo_LM'] != 0 || $lm_airo['menit_01_airo_LM'] != 0) {echo str_pad($lm_airo['jam_01_airo_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_airo['menit_01_airo_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_airo['jam_01_airo_KM'] != 0 || $km_airo['menit_01_airo_KM'] != 0) {echo str_pad($km_airo['jam_01_airo_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_airo['menit_01_airo_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_airo['jam_01_airo_PT'] != 0 || $pt_airo['menit_01_airo_PT'] != 0) {echo str_pad($pt_airo['jam_01_airo_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_airo['menit_01_airo_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_airo['jam_01_airo_KO'] != 0 || $ko_airo['menit_01_airo_KO'] != 0) {echo str_pad($ko_airo['jam_01_airo_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_airo['menit_01_airo_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_airo['jam_01_airo_AP'] != 0 || $ap_airo['menit_01_airo_AP'] != 0) {echo str_pad($ap_airo['jam_01_airo_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_airo['menit_01_airo_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_airo['jam_01_airo_PA'] != 0 || $pa_airo['menit_01_airo_PA'] != 0) {echo str_pad($pa_airo['jam_01_airo_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_airo['menit_01_airo_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_airo['jam_01_airo_PM'] != 0 || $pm_airo['menit_01_airo_PM'] != 0) {echo str_pad($pm_airo['jam_01_airo_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_airo['menit_01_airo_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_airo['jam_01_airo_GT'] != 0 || $gt_airo['menit_01_airo_GT'] != 0) {echo str_pad($gt_airo['jam_01_airo_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_airo['menit_01_airo_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_airo['jam_01_airo_TG'] != 0 || $tg_airo['menit_01_airo_TG'] != 0) {echo str_pad($tg_airo['jam_01_airo_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_airo['menit_01_airo_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_airo['jam_01_airo'] != 0 || $sum_mesin_airo['menit_01_airo'] != 0) {echo str_pad($sum_mesin_airo['jam_01_airo'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_airo['menit_01_airo'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;">02</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_airo['jam_02_airo_LM'] != 0 || $lm_airo['menit_02_airo_LM'] != 0) {echo str_pad($lm_airo['jam_02_airo_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_airo['menit_02_airo_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_airo['jam_02_airo_KM'] != 0 || $km_airo['menit_02_airo_KM'] != 0) {echo str_pad($km_airo['jam_02_airo_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_airo['menit_02_airo_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_airo['jam_02_airo_PT'] != 0 || $pt_airo['menit_02_airo_PT'] != 0) {echo str_pad($pt_airo['jam_02_airo_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_airo['menit_02_airo_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_airo['jam_02_airo_KO'] != 0 || $ko_airo['menit_02_airo_KO'] != 0) {echo str_pad($ko_airo['jam_02_airo_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_airo['menit_02_airo_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_airo['jam_02_airo_AP'] != 0 || $ap_airo['menit_02_airo_AP'] != 0) {echo str_pad($ap_airo['jam_02_airo_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_airo['menit_02_airo_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_airo['jam_02_airo_PA'] != 0 || $pa_airo['menit_02_airo_PA'] != 0) {echo str_pad($pa_airo['jam_02_airo_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_airo['menit_02_airo_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_airo['jam_02_airo_PM'] != 0 || $pm_airo['menit_02_airo_PM'] != 0) {echo str_pad($pm_airo['jam_02_airo_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_airo['menit_02_airo_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_airo['jam_02_airo_GT'] != 0 || $gt_airo['menit_02_airo_GT'] != 0) {echo str_pad($gt_airo['jam_02_airo_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_airo['menit_02_airo_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_airo['jam_02_airo_TG'] != 0 || $tg_airo['menit_02_airo_TG'] != 0) {echo str_pad($tg_airo['jam_02_airo_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_airo['menit_02_airo_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_airo['jam_02_airo'] != 0 || $sum_mesin_airo['menit_02_airo'] != 0) {echo str_pad($sum_mesin_airo['jam_02_airo'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_airo['menit_02_airo'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_ap1= mysqli_query($cona,$query_mesin_ap1);
                        $sum_mesin_ap= mysqli_fetch_assoc($stmt_mesin_ap1);
                                        ?>
                    <td colspan="3" rowspan="4" align="left" style="border: 1px solid black;"><strong>ANTI PILLING 01</strong></td>
                    <td align="center" style="border: 1px solid black;">01</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_ap['jam_ap_01_LM'] != 0 || $lm_ap['menit_ap_01_LM'] != 0) {echo str_pad($lm_ap['jam_ap_01_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_01_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_ap['jam_ap_01_KM'] != 0 || $km_ap['menit_ap_01_KM'] != 0) {echo str_pad($km_ap['jam_ap_01_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_01_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_ap['jam_ap_01_PT'] != 0 || $pt_ap['menit_ap_01_PT'] != 0) {echo str_pad($pt_ap['jam_ap_01_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_01_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_ap['jam_ap_01_KO'] != 0 || $ko_ap['menit_ap_01_KO'] != 0) {echo str_pad($ko_ap['jam_ap_01_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_01_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_ap['jam_ap_01_AP'] != 0 || $ap_ap['menit_ap_01_AP'] != 0) {echo str_pad($ap_ap['jam_ap_01_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_01_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_ap['jam_ap_01_PA'] != 0 || $pa_ap['menit_ap_01_PA'] != 0) {echo str_pad($pa_ap['jam_ap_01_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_01_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_ap['jam_ap_01_PM'] != 0 || $pm_ap['menit_ap_01_PM'] != 0) {echo str_pad($pm_ap['jam_ap_01_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_01_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_ap['jam_ap_01_GT'] != 0 || $gt_ap['menit_ap_01_GT'] != 0) {echo str_pad($gt_ap['jam_ap_01_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_01_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_ap['jam_ap_01_TG'] != 0 || $tg_ap['menit_ap_01_TG'] != 0) {echo str_pad($tg_ap['jam_ap_01_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_01_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_ap['jam_ap_01'] != 0 || $sum_mesin_ap['menit_ap_01'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_01'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_01'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>

                <tr>
                    <td align="center" style="border: 1px solid black;">02</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_ap['jam_ap_02_LM'] != 0 || $lm_ap['menit_ap_02_LM'] != 0) {echo str_pad($lm_ap['jam_ap_02_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_02_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_ap['jam_ap_02_KM'] != 0 || $km_ap['menit_ap_02_KM'] != 0) {echo str_pad($km_ap['jam_ap_02_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_02_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_ap['jam_ap_02_PT'] != 0 || $pt_ap['menit_ap_02_PT'] != 0) {echo str_pad($pt_ap['jam_ap_02_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_02_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_ap['jam_ap_02_KO'] != 0 || $ko_ap['menit_ap_02_KO'] != 0) {echo str_pad($ko_ap['jam_ap_02_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_02_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_ap['jam_ap_02_AP'] != 0 || $ap_ap['menit_ap_02_AP'] != 0) {echo str_pad($ap_ap['jam_ap_02_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_02_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_ap['jam_ap_02_PA'] != 0 || $pa_ap['menit_ap_02_PA'] != 0) {echo str_pad($pa_ap['jam_ap_02_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_02_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_ap['jam_ap_02_PM'] != 0 || $pm_ap['menit_ap_02_PM'] != 0) {echo str_pad($pm_ap['jam_ap_02_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_02_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_ap['jam_ap_02_GT'] != 0 || $gt_ap['menit_ap_02_GT'] != 0) {echo str_pad($gt_ap['jam_ap_02_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_02_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_ap['jam_ap_02_TG'] != 0 || $tg_ap['menit_ap_02_TG'] != 0) {echo str_pad($tg_ap['jam_ap_02_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_02_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_ap['jam_ap_02'] != 0 || $sum_mesin_ap['menit_ap_02'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_02'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_02'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;">03</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_ap['jam_ap_03_LM'] != 0 || $lm_ap['menit_ap_03_LM'] != 0) {echo str_pad($lm_ap['jam_ap_03_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_03_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_ap['jam_ap_03_KM'] != 0 || $km_ap['menit_ap_03_KM'] != 0) {echo str_pad($km_ap['jam_ap_03_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_03_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_ap['jam_ap_03_PT'] != 0 || $pt_ap['menit_ap_03_PT'] != 0) {echo str_pad($pt_ap['jam_ap_03_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_03_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_ap['jam_ap_03_KO'] != 0 || $ko_ap['menit_ap_03_KO'] != 0) {echo str_pad($ko_ap['jam_ap_03_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_03_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_ap['jam_ap_03_AP'] != 0 || $ap_ap['menit_ap_03_AP'] != 0) {echo str_pad($ap_ap['jam_ap_03_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_03_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_ap['jam_ap_03_PA'] != 0 || $pa_ap['menit_ap_03_PA'] != 0) {echo str_pad($pa_ap['jam_ap_03_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_03_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_ap['jam_ap_03_PM'] != 0 || $pm_ap['menit_ap_03_PM'] != 0) {echo str_pad($pm_ap['jam_ap_03_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_03_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_ap['jam_ap_03_GT'] != 0 || $gt_ap['menit_ap_03_GT'] != 0) {echo str_pad($gt_ap['jam_ap_03_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_03_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_ap['jam_ap_03_TG'] != 0 || $tg_ap['menit_ap_03_TG'] != 0) {echo str_pad($tg_ap['jam_ap_03_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_03_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_ap['jam_ap_03'] != 0 || $sum_mesin_ap['menit_ap_03'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_03'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_03'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;">04</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_ap['jam_ap_04_LM'] != 0 || $lm_ap['menit_ap_04_LM'] != 0) {echo str_pad($lm_ap['jam_ap_04_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_04_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_ap['jam_ap_04_KM'] != 0 || $km_ap['menit_ap_04_KM'] != 0) {echo str_pad($km_ap['jam_ap_04_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_04_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_ap['jam_ap_04_PT'] != 0 || $pt_ap['menit_ap_04_PT'] != 0) {echo str_pad($pt_ap['jam_ap_04_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_04_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_ap['jam_ap_04_KO'] != 0 || $ko_ap['menit_ap_04_KO'] != 0) {echo str_pad($ko_ap['jam_ap_04_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_04_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_ap['jam_ap_04_AP'] != 0 || $ap_ap['menit_ap_04_AP'] != 0) {echo str_pad($ap_ap['jam_ap_04_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_04_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_ap['jam_ap_04_PA'] != 0 || $pa_ap['menit_ap_04_PA'] != 0) {echo str_pad($pa_ap['jam_ap_04_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_04_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_ap['jam_ap_04_PM'] != 0 || $pm_ap['menit_ap_04_PM'] != 0) {echo str_pad($pm_ap['jam_ap_04_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_04_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_ap['jam_ap_04_GT'] != 0 || $gt_ap['menit_ap_04_GT'] != 0) {echo str_pad($gt_ap['jam_ap_04_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_04_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_ap['jam_ap_04_TG'] != 0 || $tg_ap['menit_ap_04_TG'] != 0) {echo str_pad($tg_ap['jam_ap_04_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_04_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_ap['jam_ap_04'] != 0 || $sum_mesin_ap['menit_ap_04'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_04'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_04'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
            <!-- End Anti Piling1 -->
            <!-- Untuk Kolom Anti Piling2 -->
                <tr>
                    <td colspan="3" align="left" style="border: 1px solid black;"><strong>ANTI PILLING 02</strong></td>
                    <td align="center" style="border: 1px solid black;">01</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                </tr>
            <!-- End Anti Piling2 -->
            <!-- Untuk Kolom Anti Piling3 -->
                <tr>
                    <td colspan="3" align="left" style="border: 1px solid black;"><strong>ANTI PILLING 03</strong></td>
                    <td align="center" style="border: 1px solid black;">01</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                </tr>
            <!-- End Anti Piling3 -->
            <!-- Untuk Kolom Anti Piling4 -->
                <tr>
                    <td colspan="3" align="left" style="border: 1px solid black;"><strong>ANTI PILLING 04</strong></td>
                    <td align="center" style="border: 1px solid black;">01</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
                    <td align="center" style="border: 1px solid black;">&nbsp;</td>
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_wet= mysqli_query($cona,$query_mesin_wet);
                        $sum_mesin_wet= mysqli_fetch_assoc($stmt_mesin_wet);
                    ?>
                    <td colspan="3" align="left" style="border: 1px solid black;"><strong>WET SUEDING</strong></td>
                    <td align="center" style="border: 1px solid black;">01</td>
                    <td align="center" style="border: 1px solid black;"><?php if ($lm_wet['jam_wet_F_LM'] != 0 || $lm_wet['menit_wet_F_LM'] != 0) {echo str_pad($lm_wet['jam_wet_F_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_wet['menit_wet_F_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($km_wet['jam_wet_F_KM'] != 0 || $km_wet['menit_wet_F_KM'] != 0) {echo str_pad($km_wet['jam_wet_F_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_wet['menit_wet_F_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pt_wet['jam_wet_F_PT'] != 0 || $pt_wet['menit_wet_F_PT'] != 0) {echo str_pad($pt_wet['jam_wet_F_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_wet['menit_wet_F_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ko_wet['jam_wet_F_KO'] != 0 || $ko_wet['menit_wet_F_KO'] != 0) {echo str_pad($ko_wet['jam_wet_F_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_wet['menit_wet_F_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($ap_wet['jam_wet_F_AP'] != 0 || $ap_wet['menit_wet_F_AP'] != 0) {echo str_pad($ap_wet['jam_wet_F_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_wet['menit_wet_F_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pa_wet['jam_wet_F_PA'] != 0 || $pa_wet['menit_wet_F_PA'] != 0) {echo str_pad($pa_wet['jam_wet_F_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_wet['menit_wet_F_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($pm_wet['jam_wet_F_PM'] != 0 || $pm_wet['menit_wet_F_PM'] != 0) {echo str_pad($pm_wet['jam_wet_F_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_wet['menit_wet_F_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($gt_wet['jam_wet_F_GT'] != 0 || $gt_wet['menit_wet_F_GT'] != 0) {echo str_pad($gt_wet['jam_wet_F_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_wet['menit_wet_F_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($tg_wet['jam_wet_F_TG'] != 0 || $tg_wet['menit_wet_F_TG'] != 0) {echo str_pad($tg_wet['jam_wet_F_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_wet['menit_wet_F_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_mesin_wet['jam_wet_F'] != 0 || $sum_mesin_wet['menit_wet_F'] != 0) {echo str_pad($sum_mesin_wet['jam_wet_F'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_wet['menit_wet_F'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''
                                                and mesin <> ''";
                                                // echo $query_total_tbl3;
                        $stmt_total_tbl3= mysqli_query($cona,$query_total_tbl3);
                        $sum_tbl3= mysqli_fetch_assoc($stmt_total_tbl3);
                    ?>
                    <td colspan="4" align="center" style="border: 1px solid black;"><strong>TOTAL</strong></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_tbl3['jam_total_LM'] != 0 || $sum_tbl3['menit_total_LM'] != 0) {echo str_pad($sum_tbl3['jam_total_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_tbl3['jam_total_KM'] != 0 || $sum_tbl3['menit_total_KM'] != 0) {echo str_pad($sum_tbl3['jam_total_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_tbl3['jam_total_PT'] != 0 || $sum_tbl3['menit_total_PT'] != 0) {echo str_pad($sum_tbl3['jam_total_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_tbl3['jam_total_KO'] != 0 || $sum_tbl3['menit_total_KO'] != 0) {echo str_pad($sum_tbl3['jam_total_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_tbl3['jam_total_AP'] != 0 || $sum_tbl3['menit_total_AP'] != 0) {echo str_pad($sum_tbl3['jam_total_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_tbl3['jam_total_PA'] != 0 || $sum_tbl3['menit_total_PA'] != 0) {echo str_pad($sum_tbl3['jam_total_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_tbl3['jam_total_PM'] != 0 || $sum_tbl3['menit_total_PM'] != 0) {echo str_pad($sum_tbl3['jam_total_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_tbl3['jam_total_GT'] != 0 || $sum_tbl3['menit_total_GT'] != 0) {echo str_pad($sum_tbl3['jam_total_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_tbl3['jam_total_TG'] != 0 || $sum_tbl3['menit_total_TG'] != 0) {echo str_pad($sum_tbl3['jam_total_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center" style="border: 1px solid black;"><?php if ($sum_tbl3['jam_total'] != 0 || $sum_tbl3['menit_total'] != 0) {echo str_pad($sum_tbl3['jam_total'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
            <!-- End Total -->
            </tbody>
            <tr>
            </tr>
</table>		
		
	</td>
	<td width="30%" align="left" valign="top"><table width="100%" border="0">
	  <tbody>
	    <tr>
	      <td width="20%" align="left" valign="top">&nbsp;</td>
	      <td width="2%" align="left" valign="top">&nbsp;</td>
	      <td width="78%" align="left" valign="top">KETERANGAN</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">LM</td>
	      <td align="left" valign="top">:</td>
	      <td align="left" valign="top">Listrik Mati</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">KM </td>
	      <td align="left" valign="top">:</td>
	      <td align="left" valign="top">Kerusakan Mesin</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      <td align="left" valign="top">&nbsp;</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">KO</td>
	      <td align="left" valign="top">:</td>
	      <td align="left" valign="top">Kurang Order</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">AP</td>
	      <td align="left" valign="top">:</td>
	      <td align="left" valign="top">Abnormal Produk</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">PM </td>
	      <td align="left" valign="top">:</td>
	      <td align="left" valign="top">Pemeliharaan Mesin</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">GT </td>
	      <td align="left" valign="top">:</td>
	      <td align="left" valign="top">Gangguan Teknis ( Gangguan yang di sebabkanoleh kerusakan pada mesin proses pendukung)</td>
	      </tr>
	    <tr>
	      <td align="left" valign="top">TG </td>
	      <td align="left" valign="top">:</td>
	      <td align="left" valign="top">Tunggu (misalnya: oper produksi, tunggu buka kain, tunggu gerobak)</td>
	      </tr>
	    </tbody>
	  </table></td>	
	</tr>
</table>
<!-- End Table 3-->	
	</td>
    </tr>
	</tbody>
</table>	  
</body>
</html>	