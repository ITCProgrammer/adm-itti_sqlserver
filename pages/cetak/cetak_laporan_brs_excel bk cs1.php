<?php
     $Awal = isset($_GET['awal']) ? $_GET['awal'] : '';
     header("Content-type: application/octet-stream");
     header("Content-Disposition: attachment; filename=REPORT-HARIAN-BRS-".$Awal.".xls"); // ganti nama sesuai keperluan
     header("Pragma: no-cache");
     header("Expires: 0");
?>
<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="styles_cetak_brs.css" rel="stylesheet" type="text/css">	-->
<title>Laporan Harian BRS</title>
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
                            tgl_buat between'$start_formatted' and '$end_formatted'";
        $stmt_tbl2 = mysqli_query($conb, $query_tbl2);
        $row_tbl2 = mysqli_fetch_assoc($stmt_tbl2);
        $cek_tbl2 = mysqli_num_rows($stmt_tbl2);
        // print_r($row_tbl2['brs_fleece_ulang']);
        $start_ncp = $date_end_tbl2->format('Y-m-d');
        $end_ncp = $date_start_tbl2->format('Y-m-d');

        $qry_ncp = "SELECT
                        SUM(berat) as qty_ncp
                    FROM
                        tbl_ncp_qcf_now
                    WHERE
                        STATUS IN ('Belum OK', 'OK', 'BS', 'Disposisi')
                        AND dept = 'BRS'
                        AND ncp_hitung = 'ya'
                        AND tgl_buat between '$end_ncp' and '$end_ncp'";
        $qry1 = mysqli_query($cond, $qry_ncp);
        $row_ncp = mysqli_fetch_assoc($qry1);

        // print_r( $startDate);
    ?>
    <!-- Tabel-1.php -->
         <!-- LANJUTIN DIBAGIAN TOTAL PALING BAWAH -->		  
<table border="1" class="table-list1" width="100%">
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
      <td width="95%" colspan="17" align="center" valign="top"><font size="+3"><strong>LAPORAN PRODUKSI HARIAN DEPARTEMEN BRUSHING</strong></font><p>&nbsp;</p></td>
    </tr>
    
  </tbody>
</table>
<?= isset($_GET['awal']) && strtotime($_GET['awal']) ? date('d M y', strtotime($_GET['awal'])) : ''; ?>
<br>
<table border="1" class="table-list1" width="100%">
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
      <td align="center" valign="middle"><strong>TANGGAL</strong></td>
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
	  <?php
                // Query tetap (kalau kamu perlu datanya juga)
                    $query = "SELECT DISTINCT CAST(tgl_buat AS DATE) AS tgl_cutoff
                            FROM tbl_produksi
                            WHERE tgl_buat >= '{$startDate->format('Y-m-d')} 23:00:00' 
                            AND tgl_buat <= '{$endDate->format('Y-m-d')} 23:00:00'
                            ORDER BY tgl_cutoff ASC";
                    $result = mysqli_query($conb, $query);

                // Array tanggal yang punya data
                    $tanggal_ada_data = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $tanggal_ada_data[$row['tgl_cutoff']] = true;
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

                foreach ($dateRange as $date) {
                    // print_r($dateRange);
                    $tanggal = $date->format('Y-m-d');
					$tanggal1 = $date->format('d M y');
                    // Default qty kosong
                    $qty = '-';

                    echo "<tr>";
                    echo "<td align='center'>{$tanggal1}</td>";
                    // Jika tanggal di loop masih <= tanggal input, jalankan query
                        if ($tanggal <= $input) {
                        // Hitung range waktu untuk query
                        $start_time = date('Y-m-d 23:01:00', strtotime($tanggal . ' -1 day')); // hari sebelumnya jam 23:00:00
                        $end_time   = $tanggal . ' 23:00:00'; // hari ini jam 23:00:00
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
                                            count(distinct id) as total_kk
                                        FROM
                                            tbl_produksi tp
                                        WHERE 
                                        tp.tgl_buat between '$start_time' and '$end_time'";
                        $stmt_qry = mysqli_query($conb, $query_table1);
                        $data_table1 = mysqli_fetch_assoc($stmt_qry);
                            // echo $tanggal;
                        // Hari kerja
                            $hari_kerja_query = "SELECT 1 FROM tbl_produksi 
                                                WHERE tgl_buat >= '$start_time' AND tgl_buat < '$end_time' LIMIT 1";
                            $hari_kerja_result = mysqli_query($conb, $hari_kerja_query);
                            $hari_kerja = mysqli_num_rows($hari_kerja_result) > 0 ? '1' : '0';
                            echo "<td align='center'>{$hari_kerja}</td>";
                            $totalHariKerja += $hari_kerja; // Tambahkan ke total hari kerja
                        // Hari kerja

                        // Jumlah KK
                            $total_kk = $data_table1['total_kk'];
                            $display_kk = ($total_kk != 0) ? $total_kk : '-';
                            echo "<td align='center'>$display_kk</td>";

                            // Hanya tambahkan angka ke total jika nilainya tidak nol
                            if ($total_kk != 0) {
                                $totalJumlahKK += $total_kk;
                            }
                        // Jumlah KK

                        // Garuk Fleece
//                        if($tanggal==$input){
//                                $qty_fleece = $data_table1['garuk_fleece'] - ($row_tbl2['brs_fleece_ulang'] + $row_tbl2['fin_fleece_ulang'] + $row_tbl2['dye_fleece_ulang'] + $row_tbl2['cqa_fleece_ulang']); 
//                                $display_fleece = ($qty_fleece != 0) ? $qty_fleece : '-';
//                                echo "<td align='center'>{$display_fleece}</td>";
//                                if ($qty_fleece != 0) {
//                                $total_garuk_fleece += $qty_fleece;
//                            }
//                        }else{
                            $qty_fleece = $data_table1['garuk_fleece'];
                            $display_fleece = ($qty_fleece != 0) ? $qty_fleece : '-';
                            echo "<td align='center'>{$display_fleece}</td>";
                            if ($qty_fleece != 0) {
                                $total_garuk_fleece += $qty_fleece;
                            }   
//                        }
                        // Garuk Fleece

                        // Potong Bulu Fleece
                            $qty_pot_bulu = $data_table1['potong_bulu_fleece'];
                            $display_bulu = ($qty_pot_bulu != 0) ? $qty_pot_bulu : '-';
                            echo "<td align='center'>{$display_bulu}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($qty_pot_bulu != 0) {
                                $total_potong_bulu_fleece += $qty_pot_bulu;
                            }
                        // Potong Bulu Fleece

                        // Proses Garuk Anti Pilling
//                        if($tanggal==$input){
//                                $qty_ap = $data_table1['garuk_ap'] - ($row_tbl2['brs_ap_ulang'] + $row_tbl2['fin_ap_ulang'] + $row_tbl2['dye_ap_ulang'] + $row_tbl2['cqa_ap_ulang']); 
//                                $display_ap = ($qty_ap != 0) ? $qty_ap : '-';
//                                echo "<td align='center'>{$display_ap}</td>";
//                                if ($qty_ap != 0) {
//                                $total_garuk_anti_pilling += $qty_ap;
//                            }
//                        }else{
                            $qty_ap = $data_table1['garuk_ap'];
                            $display_ap = ($qty_ap != 0) ? $qty_ap : '-';
                            echo "<td align='center'>{$display_ap}</td>";
                            if ($qty_ap != 0) {
                                $total_garuk_anti_pilling += $qty_ap;
                            }   
                       // }

                            // $qty_ap = ($data_table1['garuk_ap']!=0) ? $data_table1['garuk_ap'] : '-';
                            // echo "<td align='center'>{$qty_ap}</td>";
                            //     $total_garuk_anti_pilling += ($qty_ap === "-") ? 0 : $qty_ap;
                        // Proses Garuk Anti Pilling

                        // Proses Sisir Anti Pilling
                            $sisir_anti_pilling_row = $data_table1['sisir_ap'];
                            $display_sisir_ap = ($sisir_anti_pilling_row != 0) ? $sisir_anti_pilling_row : '-';
                            echo "<td align='center'>{$display_sisir_ap}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($sisir_anti_pilling_row != 0) {
                                $total_sisir_anti_pilling += $sisir_anti_pilling_row;
                            }
                            // $sisir_anti_pilling_row = ($data_table1['sisir_ap']!=0) ? $data_table1['sisir_ap'] : '-';
                            // echo "<td align='center'>{$sisir_anti_pilling_row}</td>";
                            //     $total_sisir_anti_pilling += ($sisir_anti_pilling_row === "-") ? 0 : $sisir_anti_pilling_row; //kalau 0 nilainya strip
                        // Proses Sisir Anti Pilling

                        // Proses Potong Bulu Anti Pilling
                            $potong_bulu_anti_pilling_row = $data_table1['pbulu_ap'];
                            $display_pb_ap = ($potong_bulu_anti_pilling_row != 0) ? $potong_bulu_anti_pilling_row : '-';
                            echo "<td align='center'>{$display_pb_ap}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($potong_bulu_anti_pilling_row != 0) {
                                $total_potong_bulu_anti_pilling += $potong_bulu_anti_pilling_row;
                            }
                            // $potong_bulu_anti_pilling_row = ($data_table1['pbulu_ap']!=0) ? $data_table1['pbulu_ap'] : '-';
                            // echo "<td align='center'>{$potong_bulu_anti_pilling_row}</td>";
                            //     $total_potong_bulu_anti_pilling += ($potong_bulu_anti_pilling_row === "-") ? 0 : $potong_bulu_anti_pilling_row; //kalau 0 nilainya strip
                        // Proses Potong Bulu Anti Pilling

                        // Oven Anti Pilling
                            $oven_anti_pilling_row = $data_table1['oven_ap'];
                            $display_oven = ($oven_anti_pilling_row != 0) ? $oven_anti_pilling_row : '-';
                            echo "<td align='center'>{$display_oven}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($oven_anti_pilling_row != 0) {
                                $total_oven_anti_pilling += $oven_anti_pilling_row;
                            }
                            // $oven_anti_pilling_row = ($data_table1['oven_ap']!=0) ? $data_table1['oven_ap'] : '-';
                            // echo "<td align='center'>{$oven_anti_pilling_row}</td>";
                            //     $total_oven_anti_pilling += ($oven_anti_pilling_row === "-") ? 0 : $oven_anti_pilling_row; //kalau 0 nilainya strip
                        // Oven Anti Pilling

                        // Proses Peach Skin
//                            if($tanggal==$input){
//                                $peach_skin_row = $data_table1['peach'] - ($row_tbl2['brs_peach_ulang'] + $row_tbl2['fin_peach_ulang'] + $row_tbl2['dye_peach_ulang'] + $row_tbl2['cqa_peach_ulang']); 
//                                $display_peach = ($peach_skin_row != 0) ? $peach_skin_row : '-';
//                                echo "<td align='center'>{$display_peach}</td>";
//                                if ($peach_skin_row != 0) {
//                                $total_peach_skin += $peach_skin_row;
//                            }
//                                }else{
                                    $peach_skin_row = $data_table1['peach'];
                                    $display_peach = ($peach_skin_row != 0) ? $peach_skin_row : '-';
                                    echo "<td align='center'>{$display_peach}</td>";
                                    if ($peach_skin_row != 0) {
                                        $total_peach_skin += $peach_skin_row;
                                    }   
                                //}
                            // $peach_skin_row = ($data_table1['peach']!=0) ? $data_table1['peach'] : '-';
                            // echo "<td align='center'>{$peach_skin_row}</td>";
                            //     $total_peach_skin += ($peach_skin_row === "-") ? 0 : $peach_skin_row; //kalau 0 nilainya strip
                        // Proses Peach Skin

                        // Potong Bulu Peach Skin
                            $potong_bulu_peach_skin_row = $data_table1['pb_peach'];
                            $display_bulu_peach = ($potong_bulu_peach_skin_row != 0) ? $potong_bulu_peach_skin_row : '-';
                            echo "<td align='center'>{$display_bulu_peach}</td>";
                                // $total_potong_bulu_fleece += ($qty_pot_bulu === "-") ? 0 : $qty_pot_bulu;
                            if ($potong_bulu_peach_skin_row != 0) {
                                $total_potong_bulu_peach_skin += $potong_bulu_peach_skin_row;
                            }
                            // $potong_bulu_peach_skin_row = ($data_table1['pb_peach']!=0) ? $data_table1['pb_peach'] : '-';
                            // echo "<td align='center'>{$potong_bulu_peach_skin_row}</td>";
                            //     $total_potong_bulu_peach_skin += ($potong_bulu_peach_skin_row === "-") ? 0 : $potong_bulu_peach_skin_row; //kalau 0 nilainya strip
                        // Potong Bulu Peach Skin

                        // AIRO
                            
                                $airo_row = $data_table1['airo'];
                                $display_airo = ($airo_row != 0) ? $airo_row : '-';
                                echo "<td align='center'>{$display_airo}</td>";
                                if ($airo_row != 0) {
                                    $total_airo += $airo_row;
                                }   

                            // $airo_row = ($data_table1['airo']!=0) ? $data_table1['airo'] : '-';
                            // echo "<td align='center'>{$airo_row}</td>";
                            //     $total_airo += ($airo_row === "-") ? 0 : $airo_row; //kalau 0 nilainya strip
                        // AIRO

                        // Potong Bulu Lain-Lain
//                        if($tanggal==$input){
//                                $potong_bulu_lain_lain_row = $data_table1['pb_lain'] - ($row_tbl2['brs_pb_ulang'] + $row_tbl2['fin_pb_ulang'] + $row_tbl2['dye_pb_ulang'] + $row_tbl2['cqa_pb_ulang']); 
//                                $display_pb = ($potong_bulu_lain_lain_row != 0) ? $potong_bulu_lain_lain_row : '-';
//                                echo "<td align='center'>{$display_pb}</td>";
//                                if ($potong_bulu_lain_lain_row != 0) {
//                                $total_potong_bulu_lain_lain += $potong_bulu_lain_lain_row;   
//                            }
//                                }else{
                                    $potong_bulu_lain_lain_row = $data_table1['pb_lain'];
                                    $display_pb = ($potong_bulu_lain_lain_row != 0) ? $potong_bulu_lain_lain_row : '-';
                                    echo "<td align='center'>{$display_pb}</td>";
                                    if ($potong_bulu_lain_lain_row != 0) {
                                        $total_potong_bulu_lain_lain += $potong_bulu_lain_lain_row;
                                    }   
                                // }


                            // $potong_bulu_lain_lain_row = ($data_table1['pb_lain']!=0) ? $data_table1['pb_lain'] : '-';
                            // echo "<td align='center'>{$potong_bulu_lain_lain_row}</td>";
                            //     $total_potong_bulu_lain_lain += ($potong_bulu_lain_lain_row === "-") ? 0 : $potong_bulu_lain_lain_row; //kalau 0 nilainya strip
                        // Potong Bulu Lain-Lain

                        // Oven Anti Pilling Lain-Lain
//                        if($tanggal==$input){
//                                $anti_pilling_lain_lain_row = $data_table1['ap_lain'] - ($row_tbl2['brs_oven_ulang'] + $row_tbl2['fin_oven_ulang'] + $row_tbl2['dye_oven_ulang'] + $row_tbl2['cqa_oven_ulang']); 
//                                $display_oven_ap = ($anti_pilling_lain_lain_row != 0) ? $anti_pilling_lain_lain_row : '-';
//                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
//                                echo "<td align='center'>{$display_oven_ap}</td>";
//                                if ($anti_pilling_lain_lain_row != 0) {
//                                $total_anti_pilling_lain_lain += $anti_pilling_lain_lain_row;
//                            }
//                                }else{
                                    $anti_pilling_lain_lain_row = $data_table1['ap_lain'];
                                    $display_oven_ap = ($anti_pilling_lain_lain_row != 0) ? $anti_pilling_lain_lain_row : '-';
                                    echo "<td align='center'>{$display_oven_ap}</td>";
                                    if ($anti_pilling_lain_lain_row != 0) {
                                        $total_anti_pilling_lain_lain += $anti_pilling_lain_lain_row;
                                    }   
                                // }
                            // $anti_pilling_lain_lain_row = ($data_table1['ap_lain']!=0) ? $data_table1['ap_lain'] : '-';
                            // echo "<td align='center'>{$anti_pilling_lain_lain_row}</td>";
                            //     $total_anti_pilling_lain_lain += ($anti_pilling_lain_lain_row === "-") ? 0 : $anti_pilling_lain_lain_row; //kalau 0 nilainya strip
                        // Oven Anti Pilling Lain-Lain

                        // Polishing
                            $polishing_row = $data_table1['polish'];
                                $display_polish = ($polishing_row != 0) ? $polishing_row : '-';
                                echo "<td align='center'>{$display_polish}</td>";
                                if ($polishing_row != 0) {
                                    $total_polishing += $polishing_row;
                                }   
                            // $polishing_row = ($data_table1['polish']!=0) ? $data_table1['polish'] : '-';
                            // echo "<td align='center'>{$polishing_row}</td>";
                            //     $total_polishing += ($polishing_row === "-") ? 0 : $polishing_row; //kalau 0 nilainya strip
                        // Polishing

                        // Wet Sueding
                                $wet_sueding_row = $data_table1['wet_sue'];
                                        $display_wet = ($wet_sueding_row != 0) ? $wet_sueding_row : '-';
                                        echo "<td align='center'>{$display_wet}</td>";
                                        if ($wet_sueding_row != 0) {
                                            $total_wet_sueding += $wet_sueding_row;
                                        }   
                            //  $wet_sueding_row = ($data_table1['wet_sue']!=0) ? $data_table1['wet_sue'] : '-';
                            // echo "<td align='center'>{$wet_sueding_row}</td>";
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
                            $bantu_ncp_row = $data_table1['bantu'];
                                $display_ncp = ($bantu_ncp_row != 0) ? $bantu_ncp_row : '-';
                                echo "<td align='center'>{$display_ncp}</td>";
                                if ($bantu_ncp_row != 0) {
                                    $total_bantu_ncp += $bantu_ncp_row;
                                }   
                            // $bantu_ncp_row = ($data_table1['bantu']!=0) ? $data_table1['bantu'] : '-';
                            // echo "<td align='center'>{$bantu_ncp_row}</td>";
                            //     $total_bantu_ncp += ($bantu_ncp_row === "-") ? 0 : $bantu_ncp_row; //kalau 0 nilainya strip
                            // echo "<td align='center'>" .
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
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                        echo "<td align='center'>-</td>";
                    }

                    echo "</tr>";
                }
                ?>
            <td align='center'><strong>Total</strong></td>
                <?php
                // Tampilkan total di baris bawah
                    echo "<td align='center'><b>" . ($totalHariKerja ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($totalJumlahKK ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_garuk_fleece ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_potong_bulu_fleece ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_garuk_anti_pilling ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_sisir_anti_pilling ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_potong_bulu_anti_pilling ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_oven_anti_pilling ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_peach_skin ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_potong_bulu_peach_skin ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_airo ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_potong_bulu_lain_lain ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_anti_pilling_lain_lain ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_polishing ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_wet_sueding ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_bantu_ncp ?: '-') . "</b></td>";
                    echo "<td align='center'><b>" . ($total_total_produksi ?: '-') . "</b></td>";
                ?>
    
 
</table>
<!-- End Table 1 -->
<br>	
<!-- Tabel-2.php -->
<strong> LAPORAN PROSES ULANG</strong>		  
<table border="1" class="table-list1" width="100%">	            
            <?php
                $tglInput_tbl2 = $_GET['awal'];
            ?>
            <!-- BRUSHING NCP -->
                <tr>
                    <td><strong>BRUSHING NCP</strong></td>
                    <td colspan="-1" align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?php echo '-' ?></td>
                    <td align="center">
                        <?php $qty_ncp = $row_ncp['qty_ncp'];
                                echo ($qty_ncp!=0) ? $qty_ncp : '-';
                            ?></td>
                </tr>
            <!-- BRUSHING NCP -->

            <!-- BRUSHING ULANG -->
                <tr>
                    <td><strong>BRUSHING ULANG</strong></td>
                    <td colspan="-1" align="center">
                        <?php $brs_fleece_ulang = ($row_tbl2['brs_fleece_ulang']!=0) ? $row_tbl2['brs_fleece_ulang'] : '-';
                                echo $brs_fleece_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php 
                            $brs_ap_ulang = $row_tbl2['brs_ap_ulang'];
                                echo ($brs_ap_ulang!=0) ? $brs_ap_ulang : '-';
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $brs_peach_ulang = ($row_tbl2['brs_peach_ulang']!=0) ? $row_tbl2['brs_peach_ulang'] : '-';
                                echo $brs_peach_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $brs_pb_ulang = ($row_tbl2['brs_pb_ulang']!=0) ? $row_tbl2['brs_pb_ulang'] : '-';
                                echo $brs_pb_ulang;
                            ?>
                    </td>
                    <td align="center">
                        <?php $brs_oven_ulang = ($row_tbl2['brs_oven_ulang']!=0) ? $row_tbl2['brs_oven_ulang'] : '-';
                                echo $brs_oven_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?php 
                        $total_tbl2_brs = $row_tbl2['brs_fleece_ulang'] + $row_tbl2['brs_ap_ulang'] + $row_tbl2['brs_peach_ulang']+$row_tbl2['brs_pb_ulang']+$row_tbl2['brs_oven_ulang'];
                        echo $total_tbl2_brs > 0 ? $total_tbl2_brs : '-';?>
                    </td>
                </tr>
            <!-- BRUSHING ULANG -->

            <!-- FINISHING ULANG -->
                <tr>
                    <td><strong>FINISHING ULANG</strong></td>
                    <td colspan="-1" align="center">
                        <?php $fin_fleece_ulang = ($row_tbl2['fin_fleece_ulang']!=0) ? $row_tbl2['fin_fleece_ulang'] : '-';
                                echo $fin_fleece_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $fin_ap_ulang = ($row_tbl2['fin_ap_ulang']!=0) ? $row_tbl2['fin_ap_ulang'] : '-';
                                echo $fin_ap_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $fin_peach_ulang = ($row_tbl2['fin_peach_ulang']!=0) ? $row_tbl2['fin_peach_ulang'] : '-';
                                echo $fin_peach_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $fin_pb_ulang = ($row_tbl2['fin_pb_ulang']!=0) ? $row_tbl2['fin_pb_ulang'] : '-';
                                echo $fin_pb_ulang;
                            ?>
                    </td>
                    <td align="center">
                        <?php $fin_oven_ulang = ($row_tbl2['fin_oven_ulang']!=0) ? $row_tbl2['fin_oven_ulang'] : '-';
                                echo $fin_oven_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?php 
                            $total_tbl2_fin= $row_tbl2['fin_fleece_ulang']+$row_tbl2['fin_ap_ulang']+$row_tbl2['fin_peach_ulang']+$row_tbl2['fin_pb_ulang']+$row_tbl2['fin_oven_ulang'];
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
                    <td><strong>DYEING ULANG</strong></td>
                    <td colspan="-1" align="center">
                        <?php $dye_fleece_ulang = ($row_tbl2['dye_fleece_ulang']!=0) ? $row_tbl2['dye_fleece_ulang'] : '-';
                                echo $dye_fleece_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $dye_ap_ulang = ($row_tbl2['dye_ap_ulang']!=0) ? $row_tbl2['dye_ap_ulang'] : '-';
                                echo $dye_ap_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $dye_peach_ulang = ($row_tbl2['dye_peach_ulang']!=0) ? $row_tbl2['dye_peach_ulang'] : '-';
                                echo $dye_peach_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $dye_pb_ulang = ($row_tbl2['dye_pb_ulang']!=0) ? $row_tbl2['dye_pb_ulang'] : '-';
                                echo $dye_pb_ulang;
                            ?>
                    </td>
                    <td align="center">
                        <?php $dye_oven_ulang = ($row_tbl2['dye_oven_ulang']!=0) ? $row_tbl2['dye_oven_ulang'] : '-';
                                echo $dye_oven_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?php 
                            $total_tbl2_dye= $row_tbl2['dye_fleece_ulang']+$row_tbl2['dye_ap_ulang']+$row_tbl2['dye_peach_ulang']+$row_tbl2['dye_pb_ulang']+$row_tbl2['dye_oven_ulang'];
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
                    <td><strong>CQA ULANG</strong></td>
                    <td colspan="-1" align="center">
                        <?php $cqa_fleece_ulang = ($row_tbl2['cqa_fleece_ulang']!=0) ? $row_tbl2['cqa_fleece_ulang'] : '-';
                                echo $cqa_fleece_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $cqa_ap_ulang = ($row_tbl2['cqa_ap_ulang']!=0) ? $row_tbl2['cqa_ap_ulang'] : '-';
                                echo $cqa_ap_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $cqa_peach_ulang = ($row_tbl2['cqa_peach_ulang']!=0) ? $row_tbl2['cqa_peach_ulang'] : '-';
                                echo $cqa_peach_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center">
                        <?php $cqa_pb_ulang = ($row_tbl2['cqa_pb_ulang']!=0) ? $row_tbl2['cqa_pb_ulang'] : '-';
                                echo $cqa_pb_ulang;
                            ?>
                    </td>
                    <td align="center">
                        <?php $cqa_oven_ulang = ($row_tbl2['cqa_oven_ulang']!=0) ? $row_tbl2['cqa_oven_ulang'] : '-';
                                echo $dye_oven_ulang;
                            ?>
                    </td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?= '-' ?></td>
                    <td align="center"><?php 
                            $total_tbl2_cqa= $row_tbl2['cqa_fleece_ulang']+$row_tbl2['cqa_ap_ulang']+$row_tbl2['cqa_peach_ulang']+$row_tbl2['cqa_pb_ulang']+$row_tbl2['cqa_oven_ulang'];
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
                    <td><strong>TOTAL</strong></td>
                    <td colspan="-1" align="center">
                        <?php
                            $total_column1 =
                                ($row_tbl2['dye_fleece_ulang'] +$row_tbl2['cqa_fleece_ulang']+$row_tbl2['fin_fleece_ulang']+$row_tbl2['brs_fleece_ulang'] );
                            echo htmlspecialchars($total_column1 > 0 ? $total_column1 : '-');
                        ?>
                    </td>
                    <td align="center">
                        <?php
                            echo '-';
                        ?>
                    </td>
                    <td align="center">
                        <?php
                            $total_column3 = ($row_tbl2['dye_ap_ulang'] +$row_tbl2['cqa_ap_ulang']+$row_tbl2['fin_ap_ulang']+$row_tbl2['brs_ap_ulang'] );
                            echo htmlspecialchars($total_column3 > 0 ? $total_column3 : '-');
                            ?>
                    </td>
                    <td align="center">
                        <?php
                            echo '-';
                        ?>
                    </td>
                    <td align="center">
                        <?php
                            echo '-';
                        ?>
                    </td>
                    <td align="center">-</td>
                    <td align="center">
                        <?php
                            $total_column7 = ($row_tbl2['dye_peach_ulang'] +$row_tbl2['cqa_peach_ulang']+$row_tbl2['fin_peach_ulang']+$row_tbl2['brs_peach_ulang'] );
                            echo htmlspecialchars($total_column7 > 0 ? $total_column7 : '-');
                        ?>
                    </td>
                    <td align="center">
                        <?php
                            echo '-';
                        ?>
                    </td>
                    <td align="center">
                        <?php
                            echo '-';
                        ?>
                    </td>
                    <td align="center">
                        <?php
                            $total_column10 = ($row_tbl2['dye_pb_ulang'] +$row_tbl2['cqa_pb_ulang']+$row_tbl2['fin_pb_ulang']+$row_tbl2['brs_pb_ulang'] );
                            echo htmlspecialchars($total_column10 > 0 ? $total_column10 : '-');
                        ?>
                    </td>
                    <td align="center">
                        <?php
                            $total_column11 = ($row_tbl2['dye_oven_ulang'] +$row_tbl2['cqa_oven_ulang']+$row_tbl2['fin_oven_ulang']+$row_tbl2['brs_oven_ulang'] );
                            echo htmlspecialchars($total_column11 > 0 ? $total_column11 : '-');
                        ?>
                    </td>
                    <td align="center">
                        <?php
                           echo '-'
                        ?>
                    </td>
                    <td align="center">
                        <?php
                           echo '-'
                        ?>
                    </td>
                            <?php
                            // Kolom bantu NCP
//                            $total_bantu_ncp_all = $total_bantu_ncp ?? 0;
						    $total_bantu_ncp_all = isset($total_bantu_ncp) ? $total_bantu_ncp : 0;
                            echo "<td align='center'>".'-'. "</td>";
                            ?>
                    <td align="center">
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
<strong>DATA STOPAGE MESIN DEPARTEMEN BRUSHING</strong>
<table border="0" width="100%">
	<tr>
	<td width="70%" align="left" valign="top">

<table border="1" class="table-list1" width="100%">			
            <tr>
                <td align="center" colspan="3"><strong>Mesin</strong></td>
                <td align="center"><strong>No</strong></td>
                <td align="center"><strong>LM</strong></td>
                <td align="center"><strong>KM</strong></td>
                <td align="center"><strong>PT</strong></td>
                <td align="center"><strong>KO</strong></td>
                <td align="center"><strong>AP</strong></td>
                <td align="center"><strong>PA</strong></td>
                <td align="center"><strong>PM</strong></td>
                <td align="center"><strong>GT</strong></td>
                <td align="center"><strong>TG</strong></td>
                <td align="center"><strong>Total</strong></td>                
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                        AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
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
                                                AND  DATE_FORMAT( tgl_buat, '%Y-%m-%d' ) BETWEEN '$tanggalAwal_tbl3' AND '$tanggalAkhir_tbl3'
                                                AND tbl_stoppage.kode_stop <> ''";
                        $stmt_mesin_garuk= mysqli_query($cona,$query_mesin_garuk);
                        $sum_mesin_garuk= mysqli_fetch_assoc($stmt_mesin_garuk);
                    ?>
                <!-- Mesin A -->
                <tr>
                    <td colspan="3" rowspan="6" align="left"><strong>GARUK</strong></td>
                    <td align="center">A</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_A_LM'] != 0 || $lm_g['menit_garuk_A_LM'] != 0) {echo str_pad($lm_g['jam_garuk_A_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_A_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_A_KM'] != 0 || $km_g['menit_garuk_A_KM'] != 0) {echo str_pad($km_g['jam_garuk_A_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_A_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_A_PT'] != 0 || $pt_g['menit_garuk_A_PT'] != 0) {echo str_pad($pt_g['jam_garuk_A_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_A_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_A_KO'] != 0 || $ko_g['menit_garuk_A_KO'] != 0) {echo str_pad($ko_g['jam_garuk_A_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_A_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_A_AP'] != 0 || $ap_g['menit_garuk_A_AP'] != 0) {echo str_pad($ap_g['jam_garuk_A_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_A_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_A_PA'] != 0 || $pa_g['menit_garuk_A_PA'] != 0) {echo str_pad($pa_g['jam_garuk_A_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_A_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_A_PM'] != 0 || $pm_g['menit_garuk_A_PM'] != 0) {echo str_pad($pm_g['jam_garuk_A_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_A_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_A_GT'] != 0 || $gt_g['menit_garuk_A_GT'] != 0) {echo str_pad($gt_g['jam_garuk_A_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_A_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_A_TG'] != 0 || $tg_g['menit_garuk_A_TG'] != 0) {echo str_pad($tg_g['jam_garuk_A_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_A_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_A'] != 0 || $sum_mesin_garuk['menit_garuk_A'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_A'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_A'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>                   
                </tr>
                <!-- Mesin B -->
                <tr>
                    <td align="center">B</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_B_LM'] != 0 || $lm_g['menit_garuk_B_LM'] != 0) {echo str_pad($lm_g['jam_garuk_B_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_B_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_B_KM'] != 0 || $km_g['menit_garuk_B_KM'] != 0) {echo str_pad($km_g['jam_garuk_B_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_B_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_B_PT'] != 0 || $pt_g['menit_garuk_B_PT'] != 0) {echo str_pad($pt_g['jam_garuk_B_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_B_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_B_KO'] != 0 || $ko_g['menit_garuk_B_KO'] != 0) {echo str_pad($ko_g['jam_garuk_B_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_B_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_B_AP'] != 0 || $ap_g['menit_garuk_B_AP'] != 0) {echo str_pad($ap_g['jam_garuk_B_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_B_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_B_PA'] != 0 || $pa_g['menit_garuk_B_PA'] != 0) {echo str_pad($pa_g['jam_garuk_B_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_B_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_B_PM'] != 0 || $pm_g['menit_garuk_B_PM'] != 0) {echo str_pad($pm_g['jam_garuk_B_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_B_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_B_GT'] != 0 || $gt_g['menit_garuk_B_GT'] != 0) {echo str_pad($gt_g['jam_garuk_B_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_B_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_B_TG'] != 0 || $tg_g['menit_garuk_B_TG'] != 0) {echo str_pad($tg_g['jam_garuk_B_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_B_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_B'] != 0 || $sum_mesin_garuk['menit_garuk_B'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_B'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_B'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>                    
                </tr>
                <!-- Mesin C -->
                <tr>
                    <td align="center">C</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_C_LM'] != 0 || $lm_g['menit_garuk_C_LM'] != 0) {echo str_pad($lm_g['jam_garuk_C_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_C_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_C_KM'] != 0 || $km_g['menit_garuk_C_KM'] != 0) {echo str_pad($km_g['jam_garuk_C_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_C_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_C_PT'] != 0 || $pt_g['menit_garuk_C_PT'] != 0) {echo str_pad($pt_g['jam_garuk_C_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_C_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_C_KO'] != 0 || $ko_g['menit_garuk_C_KO'] != 0) {echo str_pad($ko_g['jam_garuk_C_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_C_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_C_AP'] != 0 || $ap_g['menit_garuk_C_AP'] != 0) {echo str_pad($ap_g['jam_garuk_C_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_C_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_C_PA'] != 0 || $pa_g['menit_garuk_C_PA'] != 0) {echo str_pad($pa_g['jam_garuk_C_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_C_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_C_PM'] != 0 || $pm_g['menit_garuk_C_PM'] != 0) {echo str_pad($pm_g['jam_garuk_C_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_C_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_C_GT'] != 0 || $gt_g['menit_garuk_C_GT'] != 0) {echo str_pad($gt_g['jam_garuk_C_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_C_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_C_TG'] != 0 || $tg_g['menit_garuk_C_TG'] != 0) {echo str_pad($tg_g['jam_garuk_C_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_C_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_C'] != 0 || $sum_mesin_garuk['menit_garuk_C'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_C'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_C'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin D -->
                <tr>
                    <td align="center">D</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_D_LM'] != 0 || $lm_g['menit_garuk_D_LM'] != 0) {echo str_pad($lm_g['jam_garuk_D_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_D_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_D_KM'] != 0 || $km_g['menit_garuk_D_KM'] != 0) {echo str_pad($km_g['jam_garuk_D_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_D_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_D_PT'] != 0 || $pt_g['menit_garuk_D_PT'] != 0) {echo str_pad($pt_g['jam_garuk_D_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_D_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_D_KO'] != 0 || $ko_g['menit_garuk_D_KO'] != 0) {echo str_pad($ko_g['jam_garuk_D_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_D_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_D_AP'] != 0 || $ap_g['menit_garuk_D_AP'] != 0) {echo str_pad($ap_g['jam_garuk_D_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_D_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_D_PA'] != 0 || $pa_g['menit_garuk_D_PA'] != 0) {echo str_pad($pa_g['jam_garuk_D_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_D_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_D_PM'] != 0 || $pm_g['menit_garuk_D_PM'] != 0) {echo str_pad($pm_g['jam_garuk_D_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_D_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_D_GT'] != 0 || $gt_g['menit_garuk_D_GT'] != 0) {echo str_pad($gt_g['jam_garuk_D_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_D_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_D_TG'] != 0 || $tg_g['menit_garuk_D_TG'] != 0) {echo str_pad($tg_g['jam_garuk_D_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_D_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_D'] != 0 || $sum_mesin_garuk['menit_garuk_D'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_D'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_D'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin E -->
                <tr>
                    <td align="center">E</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_E_LM'] != 0 || $lm_g['menit_garuk_E_LM'] != 0) {echo str_pad($lm_g['jam_garuk_E_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_E_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_E_KM'] != 0 || $km_g['menit_garuk_E_KM'] != 0) {echo str_pad($km_g['jam_garuk_E_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_E_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_E_PT'] != 0 || $pt_g['menit_garuk_E_PT'] != 0) {echo str_pad($pt_g['jam_garuk_E_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_E_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_E_KO'] != 0 || $ko_g['menit_garuk_E_KO'] != 0) {echo str_pad($ko_g['jam_garuk_E_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_E_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_E_AP'] != 0 || $ap_g['menit_garuk_E_AP'] != 0) {echo str_pad($ap_g['jam_garuk_E_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_E_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_E_PA'] != 0 || $pa_g['menit_garuk_E_PA'] != 0) {echo str_pad($pa_g['jam_garuk_E_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_E_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_E_PM'] != 0 || $pm_g['menit_garuk_E_PM'] != 0) {echo str_pad($pm_g['jam_garuk_E_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_E_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_E_GT'] != 0 || $gt_g['menit_garuk_E_GT'] != 0) {echo str_pad($gt_g['jam_garuk_E_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_E_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_E_TG'] != 0 || $tg_g['menit_garuk_E_TG'] != 0) {echo str_pad($tg_g['jam_garuk_E_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_E_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_E'] != 0 || $sum_mesin_garuk['menit_garuk_E'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_E'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_E'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin F -->
                <tr>
                    <td align="center">F</td>
                    <td align="center"><?php if ($lm_g['jam_garuk_F_LM'] != 0 || $lm_g['menit_garuk_F_LM'] != 0) {echo str_pad($lm_g['jam_garuk_F_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_g['menit_garuk_F_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_g['jam_garuk_F_KM'] != 0 || $km_g['menit_garuk_F_KM'] != 0) {echo str_pad($km_g['jam_garuk_F_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_g['menit_garuk_F_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_g['jam_garuk_F_PT'] != 0 || $pt_g['menit_garuk_F_PT'] != 0) {echo str_pad($pt_g['jam_garuk_F_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_g['menit_garuk_F_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_g['jam_garuk_F_KO'] != 0 || $ko_g['menit_garuk_F_KO'] != 0) {echo str_pad($ko_g['jam_garuk_F_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_g['menit_garuk_F_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_g['jam_garuk_F_AP'] != 0 || $ap_g['menit_garuk_F_AP'] != 0) {echo str_pad($ap_g['jam_garuk_F_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_g['menit_garuk_F_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_g['jam_garuk_F_PA'] != 0 || $pa_g['menit_garuk_F_PA'] != 0) {echo str_pad($pa_g['jam_garuk_F_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_g['menit_garuk_F_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_g['jam_garuk_F_PM'] != 0 || $pm_g['menit_garuk_F_PM'] != 0) {echo str_pad($pm_g['jam_garuk_F_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_g['menit_garuk_F_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_g['jam_garuk_F_GT'] != 0 || $gt_g['menit_garuk_F_GT'] != 0) {echo str_pad($gt_g['jam_garuk_F_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_g['menit_garuk_F_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_g['jam_garuk_F_TG'] != 0 || $tg_g['menit_garuk_F_TG'] != 0) {echo str_pad($tg_g['jam_garuk_F_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_g['menit_garuk_F_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_garuk['jam_garuk_F'] != 0 || $sum_mesin_garuk['menit_garuk_F'] != 0) {echo str_pad($sum_mesin_garuk['jam_garuk_F'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_garuk['menit_garuk_F'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
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
                    <td colspan="3" align="left"><strong>SISIR</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php if ($lm_sisir['jam_sisir_LM'] != 0 || $lm_sisir['menit_sisir_LM'] != 0) {echo str_pad($lm_sisir['jam_sisir_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_sisir['menit_sisir_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_sisir['jam_sisir_KM'] != 0 || $km_sisir['menit_sisir_KM'] != 0) {echo str_pad($km_sisir['jam_sisir_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_sisir['menit_sisir_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_sisir['jam_sisir_PT'] != 0 || $pt_sisir['menit_sisir_PT'] != 0) {echo str_pad($pt_sisir['jam_sisir_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_sisir['menit_sisir_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_sisir['jam_sisir_KO'] != 0 || $ko_sisir['menit_sisir_KO'] != 0) {echo str_pad($ko_sisir['jam_sisir_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_sisir['menit_sisir_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_sisir['jam_sisir_AP'] != 0 || $ap_sisir['menit_sisir_AP'] != 0) {echo str_pad($ap_sisir['jam_sisir_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_sisir['menit_sisir_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_sisir['jam_sisir_PA'] != 0 || $pa_sisir['menit_sisir_PA'] != 0) {echo str_pad($pa_sisir['jam_sisir_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_sisir['menit_sisir_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_sisir['jam_sisir_PM'] != 0 || $pm_sisir['menit_sisir_PM'] != 0) {echo str_pad($pm_sisir['jam_sisir_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_sisir['menit_sisir_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_sisir['jam_sisir_GT'] != 0 || $gt_sisir['menit_sisir_GT'] != 0) {echo str_pad($gt_sisir['jam_sisir_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_sisir['menit_sisir_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_sisir['jam_sisir_TG'] != 0 || $tg_sisir['menit_sisir_TG'] != 0) {echo str_pad($tg_sisir['jam_sisir_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_sisir['menit_sisir_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_sisir['jam_sisir'] != 0 || $sum_mesin_sisir['menit_sisir'] != 0) {echo str_pad($sum_mesin_sisir['jam_sisir'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_sisir['menit_sisir'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>                    
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
                    <td colspan="3" rowspan="8" align="left"><strong>POTONG BULU</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_01_LM'] != 0 || $lm_pb['menit_pb_01_LM'] != 0) {echo str_pad($lm_pb['jam_pb_01_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_01_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_01_KM'] != 0 || $km_pb['menit_pb_01_KM'] != 0) {echo str_pad($km_pb['jam_pb_01_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_01_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_01_PT'] != 0 || $pt_pb['menit_pb_01_PT'] != 0) {echo str_pad($pt_pb['jam_pb_01_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_01_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_01_KO'] != 0 || $ko_pb['menit_pb_01_KO'] != 0) {echo str_pad($ko_pb['jam_pb_01_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_01_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_01_AP'] != 0 || $ap_pb['menit_pb_01_AP'] != 0) {echo str_pad($ap_pb['jam_pb_01_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_01_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_01_PA'] != 0 || $pa_pb['menit_pb_01_PA'] != 0) {echo str_pad($pa_pb['jam_pb_01_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_01_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_01_PM'] != 0 || $pm_pb['menit_pb_01_PM'] != 0) {echo str_pad($pm_pb['jam_pb_01_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_01_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_01_GT'] != 0 || $gt_pb['menit_pb_01_GT'] != 0) {echo str_pad($gt_pb['jam_pb_01_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_01_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_01_TG'] != 0 || $tg_pb['menit_pb_01_TG'] != 0) {echo str_pad($tg_pb['jam_pb_01_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_01_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_01'] != 0 || $sum_mesin_pb['menit_pb_01'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_01'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_01'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>                    
                </tr>
                <!-- Mesin 02 -->
                <tr>
                    <td align="center">02</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_02_LM'] != 0 || $lm_pb['menit_pb_02_LM'] != 0) {echo str_pad($lm_pb['jam_pb_02_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_02_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_02_KM'] != 0 || $km_pb['menit_pb_02_KM'] != 0) {echo str_pad($km_pb['jam_pb_02_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_02_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_02_PT'] != 0 || $pt_pb['menit_pb_02_PT'] != 0) {echo str_pad($pt_pb['jam_pb_02_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_02_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_02_KO'] != 0 || $ko_pb['menit_pb_02_KO'] != 0) {echo str_pad($ko_pb['jam_pb_02_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_02_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_02_AP'] != 0 || $ap_pb['menit_pb_02_AP'] != 0) {echo str_pad($ap_pb['jam_pb_02_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_02_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_02_PA'] != 0 || $pa_pb['menit_pb_02_PA'] != 0) {echo str_pad($pa_pb['jam_pb_02_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_02_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_02_PM'] != 0 || $pm_pb['menit_pb_02_PM'] != 0) {echo str_pad($pm_pb['jam_pb_02_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_02_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_02_GT'] != 0 || $gt_pb['menit_pb_02_GT'] != 0) {echo str_pad($gt_pb['jam_pb_02_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_02_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_02_TG'] != 0 || $tg_pb['menit_pb_02_TG'] != 0) {echo str_pad($tg_pb['jam_pb_02_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_02_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_02'] != 0 || $sum_mesin_pb['menit_pb_02'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_02'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_02'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 03 -->
                <tr>
                    <td align="center">03</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_03_LM'] != 0 || $lm_pb['menit_pb_03_LM'] != 0) {echo str_pad($lm_pb['jam_pb_03_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_03_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_03_KM'] != 0 || $km_pb['menit_pb_03_KM'] != 0) {echo str_pad($km_pb['jam_pb_03_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_03_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_03_PT'] != 0 || $pt_pb['menit_pb_03_PT'] != 0) {echo str_pad($pt_pb['jam_pb_03_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_03_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_03_KO'] != 0 || $ko_pb['menit_pb_03_KO'] != 0) {echo str_pad($ko_pb['jam_pb_03_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_03_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_03_AP'] != 0 || $ap_pb['menit_pb_03_AP'] != 0) {echo str_pad($ap_pb['jam_pb_03_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_03_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_03_PA'] != 0 || $pa_pb['menit_pb_03_PA'] != 0) {echo str_pad($pa_pb['jam_pb_03_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_03_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_03_PM'] != 0 || $pm_pb['menit_pb_03_PM'] != 0) {echo str_pad($pm_pb['jam_pb_03_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_03_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_03_GT'] != 0 || $gt_pb['menit_pb_03_GT'] != 0) {echo str_pad($gt_pb['jam_pb_03_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_03_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_03_TG'] != 0 || $tg_pb['menit_pb_03_TG'] != 0) {echo str_pad($tg_pb['jam_pb_03_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_03_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_03'] != 0 || $sum_mesin_pb['menit_pb_03'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_03'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_03'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 04 -->
                <tr>
                    <td align="center">04</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_04_LM'] != 0 || $lm_pb['menit_pb_04_LM'] != 0) {echo str_pad($lm_pb['jam_pb_04_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_04_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_04_KM'] != 0 || $km_pb['menit_pb_04_KM'] != 0) {echo str_pad($km_pb['jam_pb_04_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_04_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_04_PT'] != 0 || $pt_pb['menit_pb_04_PT'] != 0) {echo str_pad($pt_pb['jam_pb_04_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_04_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_04_KO'] != 0 || $ko_pb['menit_pb_04_KO'] != 0) {echo str_pad($ko_pb['jam_pb_04_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_04_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_04_AP'] != 0 || $ap_pb['menit_pb_04_AP'] != 0) {echo str_pad($ap_pb['jam_pb_04_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_04_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_04_PA'] != 0 || $pa_pb['menit_pb_04_PA'] != 0) {echo str_pad($pa_pb['jam_pb_04_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_04_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_04_PM'] != 0 || $pm_pb['menit_pb_04_PM'] != 0) {echo str_pad($pm_pb['jam_pb_04_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_04_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_04_GT'] != 0 || $gt_pb['menit_pb_04_GT'] != 0) {echo str_pad($gt_pb['jam_pb_04_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_04_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_04_TG'] != 0 || $tg_pb['menit_pb_04_TG'] != 0) {echo str_pad($tg_pb['jam_pb_04_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_04_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_04'] != 0 || $sum_mesin_pb['menit_pb_04'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_04'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_04'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 05 -->
                <tr>
                    <td align="center">05</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_05_LM'] != 0 || $lm_pb['menit_pb_05_LM'] != 0) {echo str_pad($lm_pb['jam_pb_05_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_05_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_05_KM'] != 0 || $km_pb['menit_pb_05_KM'] != 0) {echo str_pad($km_pb['jam_pb_05_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_05_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_05_PT'] != 0 || $pt_pb['menit_pb_05_PT'] != 0) {echo str_pad($pt_pb['jam_pb_05_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_05_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_05_KO'] != 0 || $ko_pb['menit_pb_05_KO'] != 0) {echo str_pad($ko_pb['jam_pb_05_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_05_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_05_AP'] != 0 || $ap_pb['menit_pb_05_AP'] != 0) {echo str_pad($ap_pb['jam_pb_05_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_05_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_05_PA'] != 0 || $pa_pb['menit_pb_05_PA'] != 0) {echo str_pad($pa_pb['jam_pb_05_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_05_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_05_PM'] != 0 || $pm_pb['menit_pb_05_PM'] != 0) {echo str_pad($pm_pb['jam_pb_05_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_05_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_05_GT'] != 0 || $gt_pb['menit_pb_05_GT'] != 0) {echo str_pad($gt_pb['jam_pb_05_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_05_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_05_TG'] != 0 || $tg_pb['menit_pb_05_TG'] != 0) {echo str_pad($tg_pb['jam_pb_05_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_05_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_05'] != 0 || $sum_mesin_pb['menit_pb_05'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_05'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_05'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 06 -->
                <tr>
                    <td align="center">06</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_06_LM'] != 0 || $lm_pb['menit_pb_06_LM'] != 0) {echo str_pad($lm_pb['jam_pb_06_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_06_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_06_KM'] != 0 || $km_pb['menit_pb_06_KM'] != 0) {echo str_pad($km_pb['jam_pb_06_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_06_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_06_PT'] != 0 || $pt_pb['menit_pb_06_PT'] != 0) {echo str_pad($pt_pb['jam_pb_06_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_06_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_06_KO'] != 0 || $ko_pb['menit_pb_06_KO'] != 0) {echo str_pad($ko_pb['jam_pb_06_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_06_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_06_AP'] != 0 || $ap_pb['menit_pb_06_AP'] != 0) {echo str_pad($ap_pb['jam_pb_06_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_06_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_06_PA'] != 0 || $pa_pb['menit_pb_06_PA'] != 0) {echo str_pad($pa_pb['jam_pb_06_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_06_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_06_PM'] != 0 || $pm_pb['menit_pb_06_PM'] != 0) {echo str_pad($pm_pb['jam_pb_06_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_06_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_06_GT'] != 0 || $gt_pb['menit_pb_06_GT'] != 0) {echo str_pad($gt_pb['jam_pb_06_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_06_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_06_TG'] != 0 || $tg_pb['menit_pb_06_TG'] != 0) {echo str_pad($tg_pb['jam_pb_06_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_06_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_06'] != 0 || $sum_mesin_pb['menit_pb_06'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_06'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_06'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 07 -->
                <tr>
                    <td align="center">07</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_07_LM'] != 0 || $lm_pb['menit_pb_07_LM'] != 0) {echo str_pad($lm_pb['jam_pb_07_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_07_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_07_KM'] != 0 || $km_pb['menit_pb_07_KM'] != 0) {echo str_pad($km_pb['jam_pb_07_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_07_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_07_PT'] != 0 || $pt_pb['menit_pb_07_PT'] != 0) {echo str_pad($pt_pb['jam_pb_07_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_07_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_07_KO'] != 0 || $ko_pb['menit_pb_07_KO'] != 0) {echo str_pad($ko_pb['jam_pb_07_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_07_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_07_AP'] != 0 || $ap_pb['menit_pb_07_AP'] != 0) {echo str_pad($ap_pb['jam_pb_07_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_07_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_07_PA'] != 0 || $pa_pb['menit_pb_07_PA'] != 0) {echo str_pad($pa_pb['jam_pb_07_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_07_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_07_PM'] != 0 || $pm_pb['menit_pb_07_PM'] != 0) {echo str_pad($pm_pb['jam_pb_07_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_07_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_07_GT'] != 0 || $gt_pb['menit_pb_07_GT'] != 0) {echo str_pad($gt_pb['jam_pb_07_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_07_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_07_TG'] != 0 || $tg_pb['menit_pb_07_TG'] != 0) {echo str_pad($tg_pb['jam_pb_07_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_07_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_07'] != 0 || $sum_mesin_pb['menit_pb_07'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_07'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_07'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Mesin 08 -->
                <tr>
                    <td align="center">08</td>
                    <td align="center"><?php if ($lm_pb['jam_pb_08_LM'] != 0 || $lm_pb['menit_pb_08_LM'] != 0) {echo str_pad($lm_pb['jam_pb_08_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_pb['menit_pb_08_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_pb['jam_pb_08_KM'] != 0 || $km_pb['menit_pb_08_KM'] != 0) {echo str_pad($km_pb['jam_pb_08_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_pb['menit_pb_08_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_pb['jam_pb_08_PT'] != 0 || $pt_pb['menit_pb_08_PT'] != 0) {echo str_pad($pt_pb['jam_pb_08_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_pb['menit_pb_08_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_pb['jam_pb_08_KO'] != 0 || $ko_pb['menit_pb_08_KO'] != 0) {echo str_pad($ko_pb['jam_pb_08_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_pb['menit_pb_08_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_pb['jam_pb_08_AP'] != 0 || $ap_pb['menit_pb_08_AP'] != 0) {echo str_pad($ap_pb['jam_pb_08_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_pb['menit_pb_08_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_pb['jam_pb_08_PA'] != 0 || $pa_pb['menit_pb_08_PA'] != 0) {echo str_pad($pa_pb['jam_pb_08_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_pb['menit_pb_08_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_pb['jam_pb_08_PM'] != 0 || $pm_pb['menit_pb_08_PM'] != 0) {echo str_pad($pm_pb['jam_pb_08_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_pb['menit_pb_08_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_pb['jam_pb_08_GT'] != 0 || $gt_pb['menit_pb_08_GT'] != 0) {echo str_pad($gt_pb['jam_pb_08_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_pb['menit_pb_08_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_pb['jam_pb_08_TG'] != 0 || $tg_pb['menit_pb_08_TG'] != 0) {echo str_pad($tg_pb['jam_pb_08_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_pb['menit_pb_08_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_pb['jam_pb_08'] != 0 || $sum_mesin_pb['menit_pb_08'] != 0) {echo str_pad($sum_mesin_pb['jam_pb_08'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_pb['menit_pb_08'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
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
                    <td colspan="3" rowspan="5" align="left"><strong>PEACH SKIN</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php if ($lm['jam_peach_01_LM'] != 0 || $lm['menit_peach_01_LM'] != 0) {echo str_pad($lm['jam_peach_01_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_01_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km['jam_peach_01_KM'] != 0 || $km['menit_peach_01_KM'] != 0) {echo str_pad($km['jam_peach_01_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_01_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt['jam_peach_01_PT'] != 0 || $pt['menit_peach_01_PT'] != 0) {echo str_pad($pt['jam_peach_01_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_01_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko['jam_peach_01_KO'] != 0 || $ko['menit_peach_01_KO'] != 0) {echo str_pad($ko['jam_peach_01_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_01_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap['jam_peach_01_AP'] != 0 || $ap['menit_peach_01_AP'] != 0) {echo str_pad($ap['jam_peach_01_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_01_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa['jam_peach_01_PA'] != 0 || $pa['menit_peach_01_PA'] != 0) {echo str_pad($pa['jam_peach_01_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_01_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm['jam_peach_01_PM'] != 0 || $pm['menit_peach_01_PM'] != 0) {echo str_pad($pm['jam_peach_01_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_01_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt['jam_peach_01_GT'] != 0 || $gt['menit_peach_01_GT'] != 0) {echo str_pad($gt['jam_peach_01_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_01_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg['jam_peach_01_TG'] != 0 || $tg['menit_peach_01_TG'] != 0) {echo str_pad($tg['jam_peach_01_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_01_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_01'] != 0 || $sum_mesin_peach['menit_peach_01'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_01'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_01'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Untuk Mesin 02 -->
                    <tr>
                    <td align="center">02</td>
                    <td align="center"><?php if ($lm['jam_peach_02_LM'] != 0 || $lm['menit_peach_02_LM'] != 0) {echo str_pad($lm['jam_peach_02_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_02_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km['jam_peach_02_KM'] != 0 || $km['menit_peach_02_KM'] != 0) {echo str_pad($km['jam_peach_02_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_02_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt['jam_peach_02_PT'] != 0 || $pt['menit_peach_02_PT'] != 0) {echo str_pad($pt['jam_peach_02_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_02_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko['jam_peach_02_KO'] != 0 || $ko['menit_peach_02_KO'] != 0) {echo str_pad($ko['jam_peach_02_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_02_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap['jam_peach_02_AP'] != 0 || $ap['menit_peach_02_AP'] != 0) {echo str_pad($ap['jam_peach_02_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_02_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa['jam_peach_02_PA'] != 0 || $pa['menit_peach_02_PA'] != 0) {echo str_pad($pa['jam_peach_02_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_02_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm['jam_peach_02_PM'] != 0 || $pm['menit_peach_02_PM'] != 0) {echo str_pad($pm['jam_peach_02_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_02_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt['jam_peach_02_GT'] != 0 || $gt['menit_peach_02_GT'] != 0) {echo str_pad($gt['jam_peach_02_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_02_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg['jam_peach_02_TG'] != 0 || $tg['menit_peach_02_TG'] != 0) {echo str_pad($tg['jam_peach_02_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_02_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_02'] != 0 || $sum_mesin_peach['menit_peach_02'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_02'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_02'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Untuk Mesin 03 -->
                    <tr>
                    <td align="center">03</td>
                    <td align="center"><?php if ($lm['jam_peach_03_LM'] != 0 || $lm['menit_peach_03_LM'] != 0) {echo str_pad($lm['jam_peach_03_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_03_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km['jam_peach_03_KM'] != 0 || $km['menit_peach_03_KM'] != 0) {echo str_pad($km['jam_peach_03_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_03_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt['jam_peach_03_PT'] != 0 || $pt['menit_peach_03_PT'] != 0) {echo str_pad($pt['jam_peach_03_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_03_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko['jam_peach_03_KO'] != 0 || $ko['menit_peach_03_KO'] != 0) {echo str_pad($ko['jam_peach_03_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_03_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap['jam_peach_03_AP'] != 0 || $ap['menit_peach_03_AP'] != 0) {echo str_pad($ap['jam_peach_03_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_03_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa['jam_peach_03_PA'] != 0 || $pa['menit_peach_03_PA'] != 0) {echo str_pad($pa['jam_peach_03_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_03_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm['jam_peach_03_PM'] != 0 || $pm['menit_peach_03_PM'] != 0) {echo str_pad($pm['jam_peach_03_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_03_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt['jam_peach_03_GT'] != 0 || $gt['menit_peach_03_GT'] != 0) {echo str_pad($gt['jam_peach_03_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_03_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg['jam_peach_03_TG'] != 0 || $tg['menit_peach_03_TG'] != 0) {echo str_pad($tg['jam_peach_03_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_03_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_03'] != 0 || $sum_mesin_peach['menit_peach_03'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_03'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_03'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Untuk Mesin 04 -->
                    <tr>
                    <td align="center">04</td>
                    <td align="center"><?php if ($lm['jam_peach_04_LM'] != 0 || $lm['menit_peach_04_LM'] != 0) {echo str_pad($lm['jam_peach_04_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_04_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km['jam_peach_04_KM'] != 0 || $km['menit_peach_04_KM'] != 0) {echo str_pad($km['jam_peach_04_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_04_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt['jam_peach_04_PT'] != 0 || $pt['menit_peach_04_PT'] != 0) {echo str_pad($pt['jam_peach_04_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_04_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko['jam_peach_04_KO'] != 0 || $ko['menit_peach_04_KO'] != 0) {echo str_pad($ko['jam_peach_04_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_04_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap['jam_peach_04_AP'] != 0 || $ap['menit_peach_04_AP'] != 0) {echo str_pad($ap['jam_peach_04_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_04_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa['jam_peach_04_PA'] != 0 || $pa['menit_peach_04_PA'] != 0) {echo str_pad($pa['jam_peach_04_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_04_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm['jam_peach_04_PM'] != 0 || $pm['menit_peach_04_PM'] != 0) {echo str_pad($pm['jam_peach_04_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_04_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt['jam_peach_04_GT'] != 0 || $gt['menit_peach_04_GT'] != 0) {echo str_pad($gt['jam_peach_04_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_04_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg['jam_peach_04_TG'] != 0 || $tg['menit_peach_04_TG'] != 0) {echo str_pad($tg['jam_peach_04_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_04_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_04'] != 0 || $sum_mesin_peach['menit_peach_04'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_04'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_04'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <!-- Untuk Mesin 05 -->
                    <tr>
                    <td align="center">05</td>
                    <td align="center"><?php if ($lm['jam_peach_05_LM'] != 0 || $lm['menit_peach_05_LM'] != 0) {echo str_pad($lm['jam_peach_05_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm['menit_peach_05_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km['jam_peach_05_KM'] != 0 || $km['menit_peach_05_KM'] != 0) {echo str_pad($km['jam_peach_05_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km['menit_peach_05_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt['jam_peach_05_PT'] != 0 || $pt['menit_peach_05_PT'] != 0) {echo str_pad($pt['jam_peach_05_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt['menit_peach_05_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko['jam_peach_05_KO'] != 0 || $ko['menit_peach_05_KO'] != 0) {echo str_pad($ko['jam_peach_05_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko['menit_peach_05_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap['jam_peach_05_AP'] != 0 || $ap['menit_peach_05_AP'] != 0) {echo str_pad($ap['jam_peach_05_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap['menit_peach_05_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa['jam_peach_05_PA'] != 0 || $pa['menit_peach_05_PA'] != 0) {echo str_pad($pa['jam_peach_05_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa['menit_peach_05_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm['jam_peach_05_PM'] != 0 || $pm['menit_peach_05_PM'] != 0) {echo str_pad($pm['jam_peach_05_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm['menit_peach_05_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt['jam_peach_05_GT'] != 0 || $gt['menit_peach_05_GT'] != 0) {echo str_pad($gt['jam_peach_05_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt['menit_peach_05_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg['jam_peach_05_TG'] != 0 || $tg['menit_peach_05_TG'] != 0) {echo str_pad($tg['jam_peach_05_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg['menit_peach_05_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_peach['jam_peach_05'] != 0 || $sum_mesin_peach['menit_peach_05'] != 0) {echo str_pad($sum_mesin_peach['jam_peach_05'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_peach['menit_peach_05'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
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
                    <td colspan="3" rowspan="2" align="left"><strong>AIRO</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php if ($lm_airo['jam_01_airo_LM'] != 0 || $lm_airo['menit_01_airo_LM'] != 0) {echo str_pad($lm_airo['jam_01_airo_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_airo['menit_01_airo_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_airo['jam_01_airo_KM'] != 0 || $km_airo['menit_01_airo_KM'] != 0) {echo str_pad($km_airo['jam_01_airo_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_airo['menit_01_airo_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_airo['jam_01_airo_PT'] != 0 || $pt_airo['menit_01_airo_PT'] != 0) {echo str_pad($pt_airo['jam_01_airo_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_airo['menit_01_airo_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_airo['jam_01_airo_KO'] != 0 || $ko_airo['menit_01_airo_KO'] != 0) {echo str_pad($ko_airo['jam_01_airo_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_airo['menit_01_airo_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_airo['jam_01_airo_AP'] != 0 || $ap_airo['menit_01_airo_AP'] != 0) {echo str_pad($ap_airo['jam_01_airo_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_airo['menit_01_airo_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_airo['jam_01_airo_PA'] != 0 || $pa_airo['menit_01_airo_PA'] != 0) {echo str_pad($pa_airo['jam_01_airo_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_airo['menit_01_airo_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_airo['jam_01_airo_PM'] != 0 || $pm_airo['menit_01_airo_PM'] != 0) {echo str_pad($pm_airo['jam_01_airo_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_airo['menit_01_airo_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_airo['jam_01_airo_GT'] != 0 || $gt_airo['menit_01_airo_GT'] != 0) {echo str_pad($gt_airo['jam_01_airo_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_airo['menit_01_airo_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_airo['jam_01_airo_TG'] != 0 || $tg_airo['menit_01_airo_TG'] != 0) {echo str_pad($tg_airo['jam_01_airo_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_airo['menit_01_airo_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_airo['jam_01_airo'] != 0 || $sum_mesin_airo['menit_01_airo'] != 0) {echo str_pad($sum_mesin_airo['jam_01_airo'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_airo['menit_01_airo'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <tr>
                    <td align="center">02</td>
                    <td align="center"><?php if ($lm_airo['jam_02_airo_LM'] != 0 || $lm_airo['menit_02_airo_LM'] != 0) {echo str_pad($lm_airo['jam_02_airo_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_airo['menit_02_airo_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_airo['jam_02_airo_KM'] != 0 || $km_airo['menit_02_airo_KM'] != 0) {echo str_pad($km_airo['jam_02_airo_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_airo['menit_02_airo_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_airo['jam_02_airo_PT'] != 0 || $pt_airo['menit_02_airo_PT'] != 0) {echo str_pad($pt_airo['jam_02_airo_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_airo['menit_02_airo_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_airo['jam_02_airo_KO'] != 0 || $ko_airo['menit_02_airo_KO'] != 0) {echo str_pad($ko_airo['jam_02_airo_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_airo['menit_02_airo_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_airo['jam_02_airo_AP'] != 0 || $ap_airo['menit_02_airo_AP'] != 0) {echo str_pad($ap_airo['jam_02_airo_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_airo['menit_02_airo_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_airo['jam_02_airo_PA'] != 0 || $pa_airo['menit_02_airo_PA'] != 0) {echo str_pad($pa_airo['jam_02_airo_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_airo['menit_02_airo_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_airo['jam_02_airo_PM'] != 0 || $pm_airo['menit_02_airo_PM'] != 0) {echo str_pad($pm_airo['jam_02_airo_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_airo['menit_02_airo_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_airo['jam_02_airo_GT'] != 0 || $gt_airo['menit_02_airo_GT'] != 0) {echo str_pad($gt_airo['jam_02_airo_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_airo['menit_02_airo_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_airo['jam_02_airo_TG'] != 0 || $tg_airo['menit_02_airo_TG'] != 0) {echo str_pad($tg_airo['jam_02_airo_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_airo['menit_02_airo_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_airo['jam_02_airo'] != 0 || $sum_mesin_airo['menit_02_airo'] != 0) {echo str_pad($sum_mesin_airo['jam_02_airo'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_airo['menit_02_airo'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
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
                    <td colspan="3" rowspan="4" align="left"><strong>ANTI PILLING 01</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php if ($lm_ap['jam_ap_01_LM'] != 0 || $lm_ap['menit_ap_01_LM'] != 0) {echo str_pad($lm_ap['jam_ap_01_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_01_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_ap['jam_ap_01_KM'] != 0 || $km_ap['menit_ap_01_KM'] != 0) {echo str_pad($km_ap['jam_ap_01_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_01_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_ap['jam_ap_01_PT'] != 0 || $pt_ap['menit_ap_01_PT'] != 0) {echo str_pad($pt_ap['jam_ap_01_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_01_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_ap['jam_ap_01_KO'] != 0 || $ko_ap['menit_ap_01_KO'] != 0) {echo str_pad($ko_ap['jam_ap_01_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_01_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_ap['jam_ap_01_AP'] != 0 || $ap_ap['menit_ap_01_AP'] != 0) {echo str_pad($ap_ap['jam_ap_01_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_01_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_ap['jam_ap_01_PA'] != 0 || $pa_ap['menit_ap_01_PA'] != 0) {echo str_pad($pa_ap['jam_ap_01_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_01_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_ap['jam_ap_01_PM'] != 0 || $pm_ap['menit_ap_01_PM'] != 0) {echo str_pad($pm_ap['jam_ap_01_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_01_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_ap['jam_ap_01_GT'] != 0 || $gt_ap['menit_ap_01_GT'] != 0) {echo str_pad($gt_ap['jam_ap_01_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_01_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_ap['jam_ap_01_TG'] != 0 || $tg_ap['menit_ap_01_TG'] != 0) {echo str_pad($tg_ap['jam_ap_01_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_01_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_01'] != 0 || $sum_mesin_ap['menit_ap_01'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_01'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_01'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>

                <tr>
                    <td align="center">02</td>
                    <td align="center"><?php if ($lm_ap['jam_ap_02_LM'] != 0 || $lm_ap['menit_ap_02_LM'] != 0) {echo str_pad($lm_ap['jam_ap_02_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_02_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_ap['jam_ap_02_KM'] != 0 || $km_ap['menit_ap_02_KM'] != 0) {echo str_pad($km_ap['jam_ap_02_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_02_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_ap['jam_ap_02_PT'] != 0 || $pt_ap['menit_ap_02_PT'] != 0) {echo str_pad($pt_ap['jam_ap_02_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_02_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_ap['jam_ap_02_KO'] != 0 || $ko_ap['menit_ap_02_KO'] != 0) {echo str_pad($ko_ap['jam_ap_02_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_02_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_ap['jam_ap_02_AP'] != 0 || $ap_ap['menit_ap_02_AP'] != 0) {echo str_pad($ap_ap['jam_ap_02_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_02_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_ap['jam_ap_02_PA'] != 0 || $pa_ap['menit_ap_02_PA'] != 0) {echo str_pad($pa_ap['jam_ap_02_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_02_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_ap['jam_ap_02_PM'] != 0 || $pm_ap['menit_ap_02_PM'] != 0) {echo str_pad($pm_ap['jam_ap_02_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_02_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_ap['jam_ap_02_GT'] != 0 || $gt_ap['menit_ap_02_GT'] != 0) {echo str_pad($gt_ap['jam_ap_02_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_02_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_ap['jam_ap_02_TG'] != 0 || $tg_ap['menit_ap_02_TG'] != 0) {echo str_pad($tg_ap['jam_ap_02_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_02_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_02'] != 0 || $sum_mesin_ap['menit_ap_02'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_02'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_02'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <tr>
                    <td align="center">03</td>
                    <td align="center"><?php if ($lm_ap['jam_ap_03_LM'] != 0 || $lm_ap['menit_ap_03_LM'] != 0) {echo str_pad($lm_ap['jam_ap_03_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_03_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_ap['jam_ap_03_KM'] != 0 || $km_ap['menit_ap_03_KM'] != 0) {echo str_pad($km_ap['jam_ap_03_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_03_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_ap['jam_ap_03_PT'] != 0 || $pt_ap['menit_ap_03_PT'] != 0) {echo str_pad($pt_ap['jam_ap_03_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_03_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_ap['jam_ap_03_KO'] != 0 || $ko_ap['menit_ap_03_KO'] != 0) {echo str_pad($ko_ap['jam_ap_03_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_03_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_ap['jam_ap_03_AP'] != 0 || $ap_ap['menit_ap_03_AP'] != 0) {echo str_pad($ap_ap['jam_ap_03_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_03_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_ap['jam_ap_03_PA'] != 0 || $pa_ap['menit_ap_03_PA'] != 0) {echo str_pad($pa_ap['jam_ap_03_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_03_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_ap['jam_ap_03_PM'] != 0 || $pm_ap['menit_ap_03_PM'] != 0) {echo str_pad($pm_ap['jam_ap_03_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_03_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_ap['jam_ap_03_GT'] != 0 || $gt_ap['menit_ap_03_GT'] != 0) {echo str_pad($gt_ap['jam_ap_03_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_03_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_ap['jam_ap_03_TG'] != 0 || $tg_ap['menit_ap_03_TG'] != 0) {echo str_pad($tg_ap['jam_ap_03_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_03_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_03'] != 0 || $sum_mesin_ap['menit_ap_03'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_03'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_03'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
                <tr>
                    <td align="center">04</td>
                    <td align="center"><?php if ($lm_ap['jam_ap_04_LM'] != 0 || $lm_ap['menit_ap_04_LM'] != 0) {echo str_pad($lm_ap['jam_ap_04_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_ap['menit_ap_04_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_ap['jam_ap_04_KM'] != 0 || $km_ap['menit_ap_04_KM'] != 0) {echo str_pad($km_ap['jam_ap_04_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_ap['menit_ap_04_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_ap['jam_ap_04_PT'] != 0 || $pt_ap['menit_ap_04_PT'] != 0) {echo str_pad($pt_ap['jam_ap_04_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_ap['menit_ap_04_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_ap['jam_ap_04_KO'] != 0 || $ko_ap['menit_ap_04_KO'] != 0) {echo str_pad($ko_ap['jam_ap_04_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_ap['menit_ap_04_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_ap['jam_ap_04_AP'] != 0 || $ap_ap['menit_ap_04_AP'] != 0) {echo str_pad($ap_ap['jam_ap_04_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_ap['menit_ap_04_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_ap['jam_ap_04_PA'] != 0 || $pa_ap['menit_ap_04_PA'] != 0) {echo str_pad($pa_ap['jam_ap_04_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_ap['menit_ap_04_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_ap['jam_ap_04_PM'] != 0 || $pm_ap['menit_ap_04_PM'] != 0) {echo str_pad($pm_ap['jam_ap_04_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_ap['menit_ap_04_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_ap['jam_ap_04_GT'] != 0 || $gt_ap['menit_ap_04_GT'] != 0) {echo str_pad($gt_ap['jam_ap_04_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_ap['menit_ap_04_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_ap['jam_ap_04_TG'] != 0 || $tg_ap['menit_ap_04_TG'] != 0) {echo str_pad($tg_ap['jam_ap_04_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_ap['menit_ap_04_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_ap['jam_ap_04'] != 0 || $sum_mesin_ap['menit_ap_04'] != 0) {echo str_pad($sum_mesin_ap['jam_ap_04'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_ap['menit_ap_04'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
            <!-- End Anti Piling1 -->
            <!-- Untuk Kolom Anti Piling2 -->
                <tr>
                    <td colspan="3" align="left"><strong>ANTI PILLING 02</strong></td>
                    <td align="center">01</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                </tr>
            <!-- End Anti Piling2 -->
            <!-- Untuk Kolom Anti Piling3 -->
                <tr>
                    <td colspan="3" align="left"><strong>ANTI PILLING 03</strong></td>
                    <td align="center">01</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                </tr>
            <!-- End Anti Piling3 -->
            <!-- Untuk Kolom Anti Piling4 -->
                <tr>
                    <td colspan="3" align="left"><strong>ANTI PILLING 04</strong></td>
                    <td align="center">01</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
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
                    <td colspan="3" align="left"><strong>WET SUEDING</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php if ($lm_wet['jam_wet_F_LM'] != 0 || $lm_wet['menit_wet_F_LM'] != 0) {echo str_pad($lm_wet['jam_wet_F_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($lm_wet['menit_wet_F_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($km_wet['jam_wet_F_KM'] != 0 || $km_wet['menit_wet_F_KM'] != 0) {echo str_pad($km_wet['jam_wet_F_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($km_wet['menit_wet_F_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pt_wet['jam_wet_F_PT'] != 0 || $pt_wet['menit_wet_F_PT'] != 0) {echo str_pad($pt_wet['jam_wet_F_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pt_wet['menit_wet_F_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ko_wet['jam_wet_F_KO'] != 0 || $ko_wet['menit_wet_F_KO'] != 0) {echo str_pad($ko_wet['jam_wet_F_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ko_wet['menit_wet_F_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($ap_wet['jam_wet_F_AP'] != 0 || $ap_wet['menit_wet_F_AP'] != 0) {echo str_pad($ap_wet['jam_wet_F_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($ap_wet['menit_wet_F_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pa_wet['jam_wet_F_PA'] != 0 || $pa_wet['menit_wet_F_PA'] != 0) {echo str_pad($pa_wet['jam_wet_F_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pa_wet['menit_wet_F_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($pm_wet['jam_wet_F_PM'] != 0 || $pm_wet['menit_wet_F_PM'] != 0) {echo str_pad($pm_wet['jam_wet_F_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($pm_wet['menit_wet_F_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($gt_wet['jam_wet_F_GT'] != 0 || $gt_wet['menit_wet_F_GT'] != 0) {echo str_pad($gt_wet['jam_wet_F_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($gt_wet['menit_wet_F_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($tg_wet['jam_wet_F_TG'] != 0 || $tg_wet['menit_wet_F_TG'] != 0) {echo str_pad($tg_wet['jam_wet_F_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($tg_wet['menit_wet_F_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_mesin_wet['jam_wet_F'] != 0 || $sum_mesin_wet['menit_wet_F'] != 0) {echo str_pad($sum_mesin_wet['jam_wet_F'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_mesin_wet['menit_wet_F'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
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
                    <td colspan="4" align="center"><strong>TOTAL</strong></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_LM'] != 0 || $sum_tbl3['menit_total_LM'] != 0) {echo str_pad($sum_tbl3['jam_total_LM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_LM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_KM'] != 0 || $sum_tbl3['menit_total_KM'] != 0) {echo str_pad($sum_tbl3['jam_total_KM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_KM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_PT'] != 0 || $sum_tbl3['menit_total_PT'] != 0) {echo str_pad($sum_tbl3['jam_total_PT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_PT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_KO'] != 0 || $sum_tbl3['menit_total_KO'] != 0) {echo str_pad($sum_tbl3['jam_total_KO'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_KO'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_AP'] != 0 || $sum_tbl3['menit_total_AP'] != 0) {echo str_pad($sum_tbl3['jam_total_AP'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_AP'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_PA'] != 0 || $sum_tbl3['menit_total_PA'] != 0) {echo str_pad($sum_tbl3['jam_total_PA'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_PA'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_PM'] != 0 || $sum_tbl3['menit_total_PM'] != 0) {echo str_pad($sum_tbl3['jam_total_PM'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_PM'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_GT'] != 0 || $sum_tbl3['menit_total_GT'] != 0) {echo str_pad($sum_tbl3['jam_total_GT'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_GT'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total_TG'] != 0 || $sum_tbl3['menit_total_TG'] != 0) {echo str_pad($sum_tbl3['jam_total_TG'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total_TG'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                    <td align="center"><?php if ($sum_tbl3['jam_total'] != 0 || $sum_tbl3['menit_total'] != 0) {echo str_pad($sum_tbl3['jam_total'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($sum_tbl3['menit_total'], 2, '0', STR_PAD_LEFT) . ':00';} else {echo '00:00:00';}?></td>
                </tr>
            <!-- End Total -->
            </tbody>
            <tr>
            </tr>
</table>		
		
	</td>
	<td width="30%" align="left" valign="top">
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
<!-- Tabel-4.php -->
<table width="100%" border="1">
  <tbody>
    <tr>
      <td colspan="5">
	  <?php
                $tglInput_tbl4 = $_GET['awal']; // misal '2025-05-20'

                // Ubah ke objek DateTime
                $date_tbl4 = new DateTime($tglInput_tbl4);

                // Tanggal sehari sebelumnya jam 23:00:00
                $tanggalAwal_tbl4 = (clone $date_tbl4)->modify('-1 day')->setTime(23, 01, 0);

                // Tanggal input jam 23:00:00
                $tanggalAkhir_tbl4 = (clone $date_tbl4)->setTime(23, 0, 0);

                // Format output
                $tglAwal_tbl4 = $tanggalAwal_tbl4->format('Y-m-d H:i:s');
                $tglAkhir_tbl4 = $tanggalAkhir_tbl4->format('Y-m-d H:i:s');

                // print_r($tglAwal_tbl4);
            ?> 	  
        <center><b>LAPORAN QUANTITY MASUK,KELUAR DAN SISA DEPARTEMEN BRUSHING</b></center>
      </td>
      </tr>
    <tr>
      <td width="30%" align="left" valign="top">
	  <table border="1" class="table-list1" width="100%">
                        <tr>
                            <td colspan="3" style="text-align:center;"><strong>QUANTITY MASUK</strong></td>
                        </tr>
                        <tr>
                            <td><strong>JENIS PROSES</strong></td>
                            <td><strong>JUMLAH KK</strong></td>
                            <td><strong>QUANTITY</strong></td>
                        </tr>
                        <tr>
                            <?php
                            // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for GARUK KAIN FLEECE (RSE1-RSE5) yang TIDAK ADA step TDR1
                             $query_garuk_fleece = "WITH base_data AS (
									SELECT
										p.PRODUCTIONORDERCODE,
										pd.CODE AS DEMANDNO,
										MAX(m.TOTALPRIMARYQUANTITY) AS TOTALPRIMARYQUANTITY
									FROM PRODUCTIONPROGRESS p
									LEFT JOIN PRODUCTIONORDER m ON m.CODE = p.PRODUCTIONORDERCODE
									LEFT OUTER JOIN PRODUCTIONRESERVATION pr ON m.COMPANYCODE = pr.COMPANYCODE
										AND m.CODE = pr.PRODUCTIONORDERCODE
									LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pr.COMPANYCODE = pd.COMPANYCODE
										AND pr.ORDERCODE = pd.CODE
									WHERE
										TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
										AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
										AND p.OPERATIONCODE IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
										AND p.PROGRESSTEMPLATECODE = 'S01'
										AND pd.CODE IS NOT NULL
										AND NOT EXISTS (
											SELECT 1 FROM VIEWPRODUCTIONDEMANDSTEP v2
											WHERE v2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
											AND v2.OPERATIONCODE = 'TDR1'
										)
									GROUP BY p.PRODUCTIONORDERCODE, pd.CODE
								),
								unique_qty_per_order AS (
									SELECT 
										PRODUCTIONORDERCODE,
										MAX(TOTALPRIMARYQUANTITY) AS UNIQUE_QTY
									FROM base_data
									GROUP BY PRODUCTIONORDERCODE
								),
								total_qty AS (
									SELECT SUM(UNIQUE_QTY) AS TOTAL_QTY FROM unique_qty_per_order
								),
								jumlah_kk AS (
									SELECT COUNT(DISTINCT DEMANDNO) AS JUMLAHKK FROM base_data
								)

								SELECT
									tq.TOTAL_QTY,
									jk.JUMLAHKK
								FROM total_qty tq
								CROSS JOIN jumlah_kk jk ";
                            $result_garuk_fleece = db2_exec($conn2, $query_garuk_fleece);
                            $row_garuk_fleece = db2_fetch_assoc($result_garuk_fleece);
                            ?>
                            <td><strong>GARUK KAIN FLEECE</strong></td>
                            <td style="text-align:center;"><?= $row_garuk_fleece['JUMLAHKK'] ?? 0; ?></td>
                            <td style="text-align:center;"><?= number_format($row_garuk_fleece['TOTAL_QTY'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for GARUK KAIN ANTI PILLING (RSE1-RSE5) yang ada step TDR1
                            $query_garuk_anti_pilling = "WITH base_data AS (
									SELECT
										p.PRODUCTIONORDERCODE,
										pd.CODE AS DEMANDNO,
										MAX(m.TOTALPRIMARYQUANTITY) AS TOTALPRIMARYQUANTITY
									FROM PRODUCTIONPROGRESS p
									LEFT JOIN PRODUCTIONORDER m ON m.CODE = p.PRODUCTIONORDERCODE
									LEFT OUTER JOIN PRODUCTIONRESERVATION pr ON m.COMPANYCODE = pr.COMPANYCODE
										AND m.CODE = pr.PRODUCTIONORDERCODE
									LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pr.COMPANYCODE = pd.COMPANYCODE
										AND pr.ORDERCODE = pd.CODE
									WHERE
										TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
										AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
										AND p.OPERATIONCODE IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
										AND p.PROGRESSTEMPLATECODE = 'S01'
										AND pd.CODE IS NOT NULL
										AND EXISTS (
											SELECT 1 FROM VIEWPRODUCTIONDEMANDSTEP v2
											WHERE v2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
											AND v2.OPERATIONCODE = 'TDR1'
										)
									GROUP BY p.PRODUCTIONORDERCODE, pd.CODE
								),
								unique_qty_per_order AS (
									SELECT 
										PRODUCTIONORDERCODE,
										MAX(TOTALPRIMARYQUANTITY) AS UNIQUE_QTY
									FROM base_data
									GROUP BY PRODUCTIONORDERCODE
								),
								total_qty AS (
									SELECT SUM(UNIQUE_QTY) AS TOTAL_QTY FROM unique_qty_per_order
								),
								jumlah_kk AS (
									SELECT COUNT(DISTINCT DEMANDNO) AS JUMLAHKK FROM base_data
								)

								SELECT
									tq.TOTAL_QTY,
									jk.JUMLAHKK
								FROM total_qty tq
								CROSS JOIN jumlah_kk jk
                            ";
                            $result_garuk_anti_pilling = db2_exec($conn2, $query_garuk_anti_pilling);
                            $row_garuk_anti_pilling = db2_fetch_assoc($result_garuk_anti_pilling);
                            ?>
                            <td><strong>GARUK KAIN ANTI PILLING</strong></td>
                            <td style="text-align:center;"><?= $row_garuk_anti_pilling['JUMLAHKK'] ?? 0; ?></td>
                            <td style="text-align:center;"><?= number_format($row_garuk_anti_pilling['TOTAL_QTY'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
							// 'SUE3','SUE4'
                            $query = "
                                        WITH base_data AS (
										SELECT
											p.PRODUCTIONORDERCODE,
											pd.CODE AS DEMANDNO,
											p.OPERATIONCODE,
											MAX(m.TOTALPRIMARYQUANTITY) AS TOTALPRIMARYQUANTITY
										FROM PRODUCTIONPROGRESS p
										LEFT JOIN PRODUCTIONORDER m ON m.CODE = p.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONRESERVATION pr ON m.COMPANYCODE = pr.COMPANYCODE
											AND m.CODE = pr.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pr.COMPANYCODE = pd.COMPANYCODE
											AND pr.ORDERCODE = pd.CODE
										WHERE
											TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
											AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
											AND PROGRESSTEMPLATECODE = 'S01'
											AND p.OPERATIONCODE IN ('SUE3','SUE4')
											AND pd.CODE IS NOT NULL
										GROUP BY p.PRODUCTIONORDERCODE, pd.CODE, p.OPERATIONCODE
									),
									unique_qty_per_order AS (
										SELECT 
											PRODUCTIONORDERCODE,
											MAX(TOTALPRIMARYQUANTITY) AS UNIQUE_QTY
										FROM base_data
										GROUP BY PRODUCTIONORDERCODE
									),
									total_qty AS (
										SELECT SUM(UNIQUE_QTY) AS TOTAL_QTY FROM unique_qty_per_order
									),
									jumlah_kk AS (
										SELECT
											COUNT(DISTINCT DEMANDNO || '-' || OPERATIONCODE) AS JUMLAHKK
										FROM base_data
									)

									SELECT
										tq.TOTAL_QTY,
										jk.JUMLAHKK
									FROM total_qty tq
									CROSS JOIN jumlah_kk jk;
                                        ";

                            $result = db2_exec($conn2, $query);
                            $rowpeachskin = db2_fetch_assoc($result);
                            ?>
                            <td><strong>PEACH SKIN</strong></td>
                            <td style="text-align:center;"><?= $rowpeachskin['JUMLAHKK']; ?></td>
                            <td style="text-align:center;"><?= number_format($rowpeachskin['TOTAL_QTY'], 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for POTONG BULU (SHR1 - SHR5)
                            $query_potongbulu = "
                                
                                        WITH base_data AS (
										SELECT
											p.PRODUCTIONORDERCODE,
											pd.CODE AS DEMANDNO,
											p.OPERATIONCODE,
											MAX(m.TOTALPRIMARYQUANTITY) AS TOTALPRIMARYQUANTITY
										FROM PRODUCTIONPROGRESS p
										LEFT JOIN PRODUCTIONORDER m ON m.CODE = p.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONRESERVATION pr ON m.COMPANYCODE = pr.COMPANYCODE
											AND m.CODE = pr.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pr.COMPANYCODE = pd.COMPANYCODE
											AND pr.ORDERCODE = pd.CODE
										WHERE
											TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
											AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
											AND PROGRESSTEMPLATECODE = 'S01'
											AND p.OPERATIONCODE IN ('SHR1','SHR2','SHR3','SHR4','SHR5')
											AND pd.CODE IS NOT NULL
										GROUP BY p.PRODUCTIONORDERCODE, pd.CODE, p.OPERATIONCODE
									),
									unique_qty_per_order AS (
										SELECT 
											PRODUCTIONORDERCODE,
											MAX(TOTALPRIMARYQUANTITY) AS UNIQUE_QTY
										FROM base_data
										GROUP BY PRODUCTIONORDERCODE
									),
									total_qty AS (
										SELECT SUM(UNIQUE_QTY) AS TOTAL_QTY FROM unique_qty_per_order
									),
									jumlah_kk AS (
										SELECT
											COUNT(DISTINCT DEMANDNO || '-' || OPERATIONCODE) AS JUMLAHKK
										FROM base_data
									)

									SELECT
										tq.TOTAL_QTY,
										jk.JUMLAHKK
									FROM total_qty tq
									CROSS JOIN jumlah_kk jk;
                            ";
                            $result_potongbulu = db2_exec($conn2, $query_potongbulu);
                            $row_potongbulu = db2_fetch_assoc($result_potongbulu);
                            ?>
                            <td><strong>POTONG BULU</strong></td>
                            <td style="text-align:center;"><?= $row_potongbulu['JUMLAHKK'] ?? 0; ?></td>
                            <td style="text-align:center;"><?= number_format($row_potongbulu['TOTAL_QTY'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for TDR1
                            $query_tdr1 = "
                                WITH base_data AS (
										SELECT
											p.PRODUCTIONORDERCODE,
											pd.CODE AS DEMANDNO,
											p.OPERATIONCODE,
											MAX(m.TOTALPRIMARYQUANTITY) AS TOTALPRIMARYQUANTITY
										FROM PRODUCTIONPROGRESS p
										LEFT JOIN PRODUCTIONORDER m ON m.CODE = p.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONRESERVATION pr ON m.COMPANYCODE = pr.COMPANYCODE
											AND m.CODE = pr.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pr.COMPANYCODE = pd.COMPANYCODE
											AND pr.ORDERCODE = pd.CODE
										WHERE
											TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
											AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
											AND PROGRESSTEMPLATECODE = 'S01'
											AND p.OPERATIONCODE IN ('TDR1')
											AND pd.CODE IS NOT NULL
										GROUP BY p.PRODUCTIONORDERCODE, pd.CODE, p.OPERATIONCODE
									),
									unique_qty_per_order AS (
										SELECT 
											PRODUCTIONORDERCODE,
											MAX(TOTALPRIMARYQUANTITY) AS UNIQUE_QTY
										FROM base_data
										GROUP BY PRODUCTIONORDERCODE
									),
									total_qty AS (
										SELECT SUM(UNIQUE_QTY) AS TOTAL_QTY FROM unique_qty_per_order
									),
									jumlah_kk AS (
										SELECT
											COUNT(DISTINCT DEMANDNO || '-' || OPERATIONCODE) AS JUMLAHKK
										FROM base_data
									)

									SELECT
										tq.TOTAL_QTY,
										jk.JUMLAHKK
									FROM total_qty tq
									CROSS JOIN jumlah_kk jk;
                            ";
                            $result_tdr1 = db2_exec($conn2, $query_tdr1);
                            $row_tdr1 = db2_fetch_assoc($result_tdr1);
                            ?>
                            <td><strong>ANTI PILLING</strong></td>
                            <td style="text-align:center;"><?= $row_tdr1['JUMLAHKK'] ?? 0; ?></td>
                            <td style="text-align:center;"><?= number_format($row_tdr1['TOTAL_QTY'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for AIRO (AIR1)
                            $query_airo = "
                                WITH base_data AS (
										SELECT
											p.PRODUCTIONORDERCODE,
											pd.CODE AS DEMANDNO,
											p.OPERATIONCODE,
											MAX(m.TOTALPRIMARYQUANTITY) AS TOTALPRIMARYQUANTITY
										FROM PRODUCTIONPROGRESS p
										LEFT JOIN PRODUCTIONORDER m ON m.CODE = p.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONRESERVATION pr ON m.COMPANYCODE = pr.COMPANYCODE
											AND m.CODE = pr.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pr.COMPANYCODE = pd.COMPANYCODE
											AND pr.ORDERCODE = pd.CODE
										WHERE
											TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
											AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
											AND PROGRESSTEMPLATECODE = 'S01'
											AND p.OPERATIONCODE IN ('AIR1')
											AND pd.CODE IS NOT NULL
										GROUP BY p.PRODUCTIONORDERCODE, pd.CODE, p.OPERATIONCODE
									),
									unique_qty_per_order AS (
										SELECT 
											PRODUCTIONORDERCODE,
											MAX(TOTALPRIMARYQUANTITY) AS UNIQUE_QTY
										FROM base_data
										GROUP BY PRODUCTIONORDERCODE
									),
									total_qty AS (
										SELECT SUM(UNIQUE_QTY) AS TOTAL_QTY FROM unique_qty_per_order
									),
									jumlah_kk AS (
										SELECT
											COUNT(DISTINCT DEMANDNO || '-' || OPERATIONCODE) AS JUMLAHKK
										FROM base_data
									)

									SELECT
										tq.TOTAL_QTY,
										jk.JUMLAHKK
									FROM total_qty tq
									CROSS JOIN jumlah_kk jk;
                            ";
                            $result_airo = db2_exec($conn2, $query_airo);
                            $row_airo = db2_fetch_assoc($result_airo);
                            ?>
                            <td><strong>AIRO</strong></td>
                            <td style="text-align:center;"><?= $row_airo['JUMLAHKK'] ?? 0; ?></td>
                            <td style="text-align:center;"><?= number_format($row_airo['TOTAL_QTY'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for SISIR (COM1, COM2)
                            $query_sisir = "
                                WITH base_data AS (
										SELECT
											p.PRODUCTIONORDERCODE,
											pd.CODE AS DEMANDNO,
											p.OPERATIONCODE,
											MAX(m.TOTALPRIMARYQUANTITY) AS TOTALPRIMARYQUANTITY
										FROM PRODUCTIONPROGRESS p
										LEFT JOIN PRODUCTIONORDER m ON m.CODE = p.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONRESERVATION pr ON m.COMPANYCODE = pr.COMPANYCODE
											AND m.CODE = pr.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pr.COMPANYCODE = pd.COMPANYCODE
											AND pr.ORDERCODE = pd.CODE
										WHERE
											TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
											AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
											AND PROGRESSTEMPLATECODE = 'S01'
											AND p.OPERATIONCODE IN ('COM1','COM2')
											AND pd.CODE IS NOT NULL
										GROUP BY p.PRODUCTIONORDERCODE, pd.CODE, p.OPERATIONCODE
									),
									unique_qty_per_order AS (
										SELECT 
											PRODUCTIONORDERCODE,
											MAX(TOTALPRIMARYQUANTITY) AS UNIQUE_QTY
										FROM base_data
										GROUP BY PRODUCTIONORDERCODE
									),
									total_qty AS (
										SELECT SUM(UNIQUE_QTY) AS TOTAL_QTY FROM unique_qty_per_order
									),
									jumlah_kk AS (
										SELECT
											COUNT(DISTINCT DEMANDNO || '-' || OPERATIONCODE) AS JUMLAHKK
										FROM base_data
									)

									SELECT
										tq.TOTAL_QTY,
										jk.JUMLAHKK
									FROM total_qty tq
									CROSS JOIN jumlah_kk jk;
                            ";
                            $result_sisir = db2_exec($conn2, $query_sisir);
                            $row_sisir = db2_fetch_assoc($result_sisir);
                            ?>
                            <td><strong>SISIR</strong></td>
                            <td style="text-align:center;"><?= $row_sisir['JUMLAHKK'] ?? 0; ?></td>
                            <td style="text-align:center;"><?= number_format($row_sisir['TOTAL_QTY'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for PEACH SKIN GREIGE (SUE1, SUE2)
                            $query_peachskin_greige = "
                                WITH base_data AS (
										SELECT
											p.PRODUCTIONORDERCODE,
											pd.CODE AS DEMANDNO,
											p.OPERATIONCODE,
											MAX(m.TOTALPRIMARYQUANTITY) AS TOTALPRIMARYQUANTITY
										FROM PRODUCTIONPROGRESS p
										LEFT JOIN PRODUCTIONORDER m ON m.CODE = p.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONRESERVATION pr ON m.COMPANYCODE = pr.COMPANYCODE
											AND m.CODE = pr.PRODUCTIONORDERCODE
										LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pr.COMPANYCODE = pd.COMPANYCODE
											AND pr.ORDERCODE = pd.CODE
										WHERE
											TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
											AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
											AND PROGRESSTEMPLATECODE = 'S01'
											AND p.OPERATIONCODE IN ('SUE1','SUE2')
											AND pd.CODE IS NOT NULL
										GROUP BY p.PRODUCTIONORDERCODE, pd.CODE, p.OPERATIONCODE
									),
									unique_qty_per_order AS (
										SELECT 
											PRODUCTIONORDERCODE,
											MAX(TOTALPRIMARYQUANTITY) AS UNIQUE_QTY
										FROM base_data
										GROUP BY PRODUCTIONORDERCODE
									),
									total_qty AS (
										SELECT SUM(UNIQUE_QTY) AS TOTAL_QTY FROM unique_qty_per_order
									),
									jumlah_kk AS (
										SELECT
											COUNT(DISTINCT DEMANDNO || '-' || OPERATIONCODE) AS JUMLAHKK
										FROM base_data
									)

									SELECT
										tq.TOTAL_QTY,
										jk.JUMLAHKK
									FROM total_qty tq
									CROSS JOIN jumlah_kk jk;
                            ";
                            $result_peachskin_greige = db2_exec($conn2, $query_peachskin_greige);
                            $row_peachskin_greige = db2_fetch_assoc($result_peachskin_greige);
                            ?>
                            <td><strong>PEACH SKIN GREIGE</strong></td>
                            <td style="text-align:center;"><?= $row_peachskin_greige['JUMLAHKK'] ?? 0; ?></td>
                            <td style="text-align:center;"><?= number_format($row_peachskin_greige['TOTAL_QTY'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>PERBAIKAN</strong></td>
                            <td style="text-align:center;">0</td>
                            <td style="text-align:center;">0.00</td>
                        </tr>
                        <tr style="font-weight:bold; background-color:yellow;">
                            <?php
                            // Hitung total JUMLAHKK dan TOTAL_QTY dari semua proses di atas (kecuali PERBAIKAN)
                            $total_jumlahkk =
                                (int)($row_garuk_fleece['JUMLAHKK'] ?? 0) +
                                (int)($row_garuk_anti_pilling['JUMLAHKK'] ?? 0) +
                                (int)($rowpeachskin['JUMLAHKK'] ?? 0) +
                                (int)($row_potongbulu['JUMLAHKK'] ?? 0) +
                                (int)($row_tdr1['JUMLAHKK'] ?? 0) +
                                (int)($row_airo['JUMLAHKK'] ?? 0) +
                                (int)($row_sisir['JUMLAHKK'] ?? 0) +
                                (int)($row_peachskin_greige['JUMLAHKK'] ?? 0);
                            $total_qty =
                                (float)($row_garuk_fleece['TOTAL_QTY'] ?? 0) +
                                (float)($row_garuk_anti_pilling['TOTAL_QTY'] ?? 0) +
                                (float)($rowpeachskin['TOTAL_QTY'] ?? 0) +
                                (float)($row_potongbulu['TOTAL_QTY'] ?? 0) +
                                (float)($row_tdr1['TOTAL_QTY'] ?? 0) +
                                (float)($row_airo['TOTAL_QTY'] ?? 0) +
                                (float)($row_sisir['TOTAL_QTY'] ?? 0) +
                                (float)($row_peachskin_greige['TOTAL_QTY'] ?? 0);

                            ?>

                            <td style="text-align:center;">TOTAL MASUK</td>
                            <td style="text-align:center;"><?= $total_jumlahkk ?></td>
                            <td style="text-align:center;"><?= number_format($total_qty, 2) ?></td>
                        </tr>
                </table>	
	  </td>
      <td width="5%">&nbsp;</td>
      <td width="30%" align="left" valign="top">
		<table border="1" class="table-list1" width="100%">
                        <tr>
                            <td colspan="4" style="text-align:center;"><strong>QUANTITY KELUAR</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>JENIS PROSES</strong></td>

                            <td width="26%"><strong>JUMLAH KK</strong></td>
                            <td width="24%"><strong>QUANTITY</strong></td>
                        </tr>
                        <tr>
                            <?php

                            $F3C20069 = "WITH StepRank AS (  
										SELECT 
											p.PRODUCTIONORDERCODE,
											ds.OPERATIONCODE,
											ds.STEPNUMBER,
											ds.PRODUCTIONDEMANDCODE,
											prod.TOTALPRIMARYQUANTITY,
											ROW_NUMBER() OVER (
												PARTITION BY ds.PRODUCTIONDEMANDCODE 
												ORDER BY ds.STEPNUMBER
											) AS STEP_ORDER,
											TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) AS JAM,
											D.SUBCODE02,
											D.SUBCODE03
										FROM 
											PRODUCTIONPROGRESS p    
										JOIN PRODUCTIONDEMANDSTEP ds 
											ON p.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
											AND p.OPERATIONCODE = ds.OPERATIONCODE
										LEFT JOIN PRODUCTIONORDER prod 
											ON prod.CODE = p.PRODUCTIONORDERCODE
										LEFT JOIN PRODUCTIONDEMAND D ON D.CODE = ds.PRODUCTIONDEMANDCODE    
										WHERE
											D.SUBCODE02 = 'F3C'
											AND D.SUBCODE03 = '20069'
											AND p.PROGRESSTEMPLATECODE = 'S01'
											AND p.OPERATIONCODE LIKE 'RSE%'        
											AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
											AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
									),
									AllSteps AS (
										SELECT 
											ds.PRODUCTIONORDERCODE,
											ds.PRODUCTIONDEMANDCODE,
											ds.OPERATIONCODE,
											ds.STEPNUMBER
										FROM PRODUCTIONDEMANDSTEP ds
									),
									Filtered AS (
										SELECT 
											r.PRODUCTIONORDERCODE,
											r.TOTALPRIMARYQUANTITY,
											r.STEPNUMBER AS RSE_STEP,
											MIN(s.STEPNUMBER) AS FIN1_STEP
										FROM StepRank r
										JOIN AllSteps s 
											ON r.PRODUCTIONDEMANDCODE = s.PRODUCTIONDEMANDCODE
											AND s.STEPNUMBER > r.STEPNUMBER
											AND s.OPERATIONCODE = 'FIN1'
										WHERE NOT EXISTS (
											SELECT 1 FROM AllSteps x
											WHERE 
												x.PRODUCTIONDEMANDCODE = r.PRODUCTIONDEMANDCODE
												AND x.STEPNUMBER > r.STEPNUMBER
												AND x.STEPNUMBER < s.STEPNUMBER
												AND x.OPERATIONCODE NOT LIKE 'WAIT%'
												AND x.OPERATIONCODE NOT LIKE 'NCP%'
										)
										GROUP BY r.PRODUCTIONORDERCODE, r.TOTALPRIMARYQUANTITY, r.STEPNUMBER
									)
									SELECT 
										COUNT(*) AS JUMLAHKK,
										SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
									FROM Filtered;";

                            $resultFF3C20069 = db2_exec($conn2, $F3C20069);
                            $rowFLEECEF3C20069 = db2_fetch_assoc($resultFF3C20069);


                            $F3C20069F = "WITH StepRank AS (  
										SELECT 
											p.PRODUCTIONORDERCODE,
											ds.OPERATIONCODE,
											ds.STEPNUMBER,
											ds.PRODUCTIONDEMANDCODE,
											prod.TOTALPRIMARYQUANTITY,
											ROW_NUMBER() OVER (
												PARTITION BY ds.PRODUCTIONDEMANDCODE 
												ORDER BY ds.STEPNUMBER
											) AS STEP_ORDER,
											TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) AS JAM,
											D.SUBCODE02,
											D.SUBCODE03
										FROM 
											PRODUCTIONPROGRESS p    
										JOIN PRODUCTIONDEMANDSTEP ds 
											ON p.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
											AND p.OPERATIONCODE = ds.OPERATIONCODE
										LEFT JOIN PRODUCTIONORDER prod 
											ON prod.CODE = p.PRODUCTIONORDERCODE
										LEFT JOIN PRODUCTIONDEMAND D ON D.CODE = ds.PRODUCTIONDEMANDCODE    
										WHERE
											D.SUBCODE02 = 'F3C'
											AND D.SUBCODE03 = '20069'
											AND p.PROGRESSTEMPLATECODE = 'S01'
											AND p.OPERATIONCODE LIKE 'RSE%'        
											AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
											AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
									),
									AllSteps AS (
										SELECT 
											ds.PRODUCTIONORDERCODE,
											ds.PRODUCTIONDEMANDCODE,
											ds.OPERATIONCODE,
											ds.STEPNUMBER
										FROM PRODUCTIONDEMANDSTEP ds
									),
									Filtered AS (
										SELECT 
											r.PRODUCTIONORDERCODE,
											r.TOTALPRIMARYQUANTITY,
											r.STEPNUMBER AS RSE_STEP,
											MIN(s.STEPNUMBER) AS FIN1_STEP
										FROM StepRank r
										JOIN AllSteps s 
											ON r.PRODUCTIONDEMANDCODE = s.PRODUCTIONDEMANDCODE
											AND s.STEPNUMBER > r.STEPNUMBER
											AND s.OPERATIONCODE = 'FNJ1'
										WHERE NOT EXISTS (
											SELECT 1 FROM AllSteps x
											WHERE 
												x.PRODUCTIONDEMANDCODE = r.PRODUCTIONDEMANDCODE
												AND x.STEPNUMBER > r.STEPNUMBER
												AND x.STEPNUMBER < s.STEPNUMBER
												AND x.OPERATIONCODE NOT LIKE 'WAIT%'
												AND x.OPERATIONCODE NOT LIKE 'NCP%'
										)
										GROUP BY r.PRODUCTIONORDERCODE, r.TOTALPRIMARYQUANTITY, r.STEPNUMBER
									)
									SELECT 
										COUNT(*) AS JUMLAHKK,
										SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
									FROM Filtered;";

                            $resultFF3C20069F = db2_exec($conn2, $F3C20069F);
                            $rowFLEECEF3C20069F = db2_fetch_assoc($resultFF3C20069F);

                            ?>
                            <td width="26%" rowspan="2"><strong>GRK FLEECE F3C-20069</strong></td>
                            <td width="24%"><strong><span style="text-align:center;">FIN 1X</span></strong></td>
                            <td style="text-align:center;"><?= number_format($rowFLEECEF3C20069['JUMLAHKK'] ?? 0) ?></td>
                            <td style="text-align:center;"><?= number_format($rowFLEECEF3C20069['TOTAL_QTY'] ?? 0, 2) ?></td>
                        </tr>
                        <tr>
                            <td><strong><span style="text-align:center;">FIN FINAL</span></strong></td>
                            <td style="text-align:center;"><?= number_format($rowFLEECEF3C20069F['JUMLAHKK'] ?? 0) ?></td>
                            <td style="text-align:center;"><?= number_format($rowFLEECEF3C20069F['TOTAL_QTY'] ?? 0, 2) ?></td>
                        </tr>
                        <tr>
                            <?php
                            $queryFLEECE = "WITH StepRank AS (  
									SELECT 
										p.PRODUCTIONORDERCODE,
										ds.OPERATIONCODE,
										ds.STEPNUMBER,
										ds.PRODUCTIONDEMANDCODE,
										prod.TOTALPRIMARYQUANTITY,
										ROW_NUMBER() OVER (
											PARTITION BY ds.PRODUCTIONDEMANDCODE 
											ORDER BY ds.STEPNUMBER
										) AS STEP_ORDER,
										TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) AS JAM
									FROM 
										PRODUCTIONPROGRESS p    
									JOIN PRODUCTIONDEMANDSTEP ds 
										ON p.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
										AND p.OPERATIONCODE = ds.OPERATIONCODE
									LEFT JOIN PRODUCTIONORDER prod 
										ON prod.CODE = p.PRODUCTIONORDERCODE
									WHERE 
										p.PROGRESSTEMPLATECODE = 'S01'
										AND p.OPERATIONCODE LIKE 'RSE%'        
										AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
										AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
								),
								AllSteps AS (
									SELECT 
										ds.PRODUCTIONORDERCODE,
										ds.PRODUCTIONDEMANDCODE,
										ds.OPERATIONCODE,
										ds.STEPNUMBER
									FROM PRODUCTIONDEMANDSTEP ds
								),
								Filtered AS (
									SELECT 
										r.PRODUCTIONORDERCODE,
										r.TOTALPRIMARYQUANTITY,
										r.STEPNUMBER AS RSE_STEP,
										MIN(s.STEPNUMBER) AS FIN1_STEP
									FROM StepRank r
									JOIN AllSteps s 
										ON r.PRODUCTIONDEMANDCODE = s.PRODUCTIONDEMANDCODE
										AND s.STEPNUMBER > r.STEPNUMBER
										AND s.OPERATIONCODE = 'FIN1'
									WHERE NOT EXISTS (
										SELECT 1 FROM AllSteps x
										WHERE 
											x.PRODUCTIONDEMANDCODE = r.PRODUCTIONDEMANDCODE
											AND x.STEPNUMBER > r.STEPNUMBER
											AND x.STEPNUMBER < s.STEPNUMBER
											AND x.OPERATIONCODE NOT LIKE 'WAIT%'
											AND x.OPERATIONCODE NOT LIKE 'NCP%'
									)
									GROUP BY r.PRODUCTIONORDERCODE, r.TOTALPRIMARYQUANTITY, r.STEPNUMBER
								)
								SELECT 
									COUNT(*) AS JUMLAHKK,
									SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
								FROM Filtered;";
                            $resultF = db2_exec($conn2, $queryFLEECE);
                            $rowFLEECE = db2_fetch_assoc($resultF);

                            $queryFINAL = "WITH StepRank AS (  
									SELECT 
										p.PRODUCTIONORDERCODE,
										ds.OPERATIONCODE,
										ds.STEPNUMBER,
										ds.PRODUCTIONDEMANDCODE,
										prod.TOTALPRIMARYQUANTITY,
										ROW_NUMBER() OVER (
											PARTITION BY ds.PRODUCTIONDEMANDCODE 
											ORDER BY ds.STEPNUMBER
										) AS STEP_ORDER,
										TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) AS JAM
									FROM 
										PRODUCTIONPROGRESS p    
									JOIN PRODUCTIONDEMANDSTEP ds 
										ON p.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
										AND p.OPERATIONCODE = ds.OPERATIONCODE
									LEFT JOIN PRODUCTIONORDER prod 
										ON prod.CODE = p.PRODUCTIONORDERCODE
									WHERE 
										p.PROGRESSTEMPLATECODE = 'S01'
										AND p.OPERATIONCODE LIKE 'RSE%'        
										AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
										AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
								),
								AllSteps AS (
									SELECT 
										ds.PRODUCTIONORDERCODE,
										ds.PRODUCTIONDEMANDCODE,
										ds.OPERATIONCODE,
										ds.STEPNUMBER
									FROM PRODUCTIONDEMANDSTEP ds
								),
								Filtered AS (
									SELECT 
										r.PRODUCTIONORDERCODE,
										r.TOTALPRIMARYQUANTITY,
										r.STEPNUMBER AS RSE_STEP,
										MIN(s.STEPNUMBER) AS FIN1_STEP
									FROM StepRank r
									JOIN AllSteps s 
										ON r.PRODUCTIONDEMANDCODE = s.PRODUCTIONDEMANDCODE
										AND s.STEPNUMBER > r.STEPNUMBER
										AND s.OPERATIONCODE = 'FNJ1'
									WHERE NOT EXISTS (
										SELECT 1 FROM AllSteps x
										WHERE 
											x.PRODUCTIONDEMANDCODE = r.PRODUCTIONDEMANDCODE
											AND x.STEPNUMBER > r.STEPNUMBER
											AND x.STEPNUMBER < s.STEPNUMBER
											AND x.OPERATIONCODE NOT LIKE 'WAIT%'
											AND x.OPERATIONCODE NOT LIKE 'NCP%'
									)
									GROUP BY r.PRODUCTIONORDERCODE, r.TOTALPRIMARYQUANTITY, r.STEPNUMBER
								)
								SELECT 
									COUNT(*) AS JUMLAHKK,
									SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
								FROM Filtered;
                                    ";
                            $resultFINAL = db2_exec($conn2, $queryFINAL);
                            $rowFINAL = db2_fetch_assoc($resultFINAL);
                            ?>
                            <td rowspan="2"><strong>GRK FLEECE</strong></td>
                            <td><strong><span style="text-align:center;">FIN 1X</span></strong></td>
                            <td style="text-align:center;"><?= number_format($rowFLEECE['JUMLAHKK'] ?? 0); ?></td>
                            <td style="text-align:center;"><?= number_format($rowFLEECE['TOTAL_QTY'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>FIN FINAL</strong></td>
                            <td style="text-align:center;"><?= number_format($rowFINAL['JUMLAHKK'] ?? 0); ?></td>
                            <td style="text-align:center;"><?= number_format($rowFINAL['TOTAL_QTY'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            $queryAP = "WITH FilteredProgress AS (
                                            SELECT DISTINCT 
                                                p.PRODUCTIONORDERCODE,
                                                p.OPERATIONCODE AS CURRENT_STEP,
                                                next_step.OPERATIONCODE AS NEXT_STEP,
                                                --  next_step.STEPNUMBER AS NEXT_STEP_GROUP,
                                                prod.TOTALPRIMARYQUANTITY
                                            FROM
                                                PRODUCTIONPROGRESS p
                                            LEFT JOIN PRODUCTIONORDER prod ON
                                                prod.CODE = p.PRODUCTIONORDERCODE
                                            JOIN PRODUCTIONDEMANDSTEP curr_step ON
                                                p.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                                AND p.OPERATIONCODE = curr_step.OPERATIONCODE
                                            JOIN PRODUCTIONDEMANDSTEP next_step ON
                                                next_step.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                                AND next_step.STEPNUMBER = curr_step.STEPNUMBER + 1
                                                AND TRIM(next_step.OPERATIONCODE) = 'TDR1'
                                            WHERE
                                                p.OPERATIONCODE LIKE '%1RSE%'
                                                AND p.PROGRESSTEMPLATECODE = 'S01'
                                                AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                                AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                        )
                                        SELECT
                                            COUNT(*) AS JUMLAHKK,
                                            SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
                                        FROM
                                            FilteredProgress;
                                    ";

                            $resultAP = db2_exec($conn2, $queryAP);
                            $rowAP = db2_fetch_assoc($resultAP);

                            $queryAPf = "WITH StepRank AS (  
									SELECT 
										p.PRODUCTIONORDERCODE,
										ds.OPERATIONCODE,
										ds.STEPNUMBER,
										ds.PRODUCTIONDEMANDCODE,
										prod.TOTALPRIMARYQUANTITY,
										ROW_NUMBER() OVER (
											PARTITION BY ds.PRODUCTIONDEMANDCODE 
											ORDER BY ds.STEPNUMBER
										) AS STEP_ORDER,
										TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) AS JAM
									FROM 
										PRODUCTIONPROGRESS p    
									JOIN PRODUCTIONDEMANDSTEP ds 
										ON p.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
										AND p.OPERATIONCODE = ds.OPERATIONCODE
									LEFT JOIN PRODUCTIONORDER prod 
										ON prod.CODE = p.PRODUCTIONORDERCODE
									WHERE 
										p.PROGRESSTEMPLATECODE = 'S01'
										AND p.OPERATIONCODE LIKE 'RSE%'        
										AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
										AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
								),
								AllSteps AS (
									SELECT 
										ds.PRODUCTIONORDERCODE,
										ds.PRODUCTIONDEMANDCODE,
										ds.OPERATIONCODE,
										ds.STEPNUMBER
									FROM PRODUCTIONDEMANDSTEP ds
								),
								Filtered AS (
									SELECT 
										r.PRODUCTIONORDERCODE,
										r.TOTALPRIMARYQUANTITY,
										r.STEPNUMBER AS RSE_STEP,
										MIN(s.STEPNUMBER) AS FIN1_STEP
									FROM StepRank r
									JOIN AllSteps s 
										ON r.PRODUCTIONDEMANDCODE = s.PRODUCTIONDEMANDCODE
										AND s.STEPNUMBER > r.STEPNUMBER
										AND s.OPERATIONCODE = 'TDR1'
									WHERE NOT EXISTS (
										SELECT 1 FROM AllSteps x
										WHERE 
											x.PRODUCTIONDEMANDCODE = r.PRODUCTIONDEMANDCODE
											AND x.STEPNUMBER > r.STEPNUMBER
											AND x.STEPNUMBER < s.STEPNUMBER
											AND x.OPERATIONCODE NOT LIKE 'WAIT%'
											AND x.OPERATIONCODE NOT LIKE 'NCP%'
									)
									GROUP BY r.PRODUCTIONORDERCODE, r.TOTALPRIMARYQUANTITY, r.STEPNUMBER
								)
								SELECT 
									COUNT(*) AS JUMLAHKK,
									SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
								FROM Filtered;        
                                    ";
                            $resultAPf = db2_exec($conn2, $queryAPf);
                            $rowAPf = db2_fetch_assoc($resultAPf);

                            ?>
                            <td rowspan="2"><strong>GRK AP</strong></td>
                            <td><strong><span style="text-align:center;">TAMBAH OBAT</span></strong></td>
                            <td style="text-align:center;"><?= number_format($rowAP['JUMLAHKK'] ?? 0) ?></td>
                            <td style="text-align:center;"><?= number_format($rowAP['TOTAL_QTY'] ?? 0, 2) ?></td>
                        </tr>
                        <tr>
                            <td><strong><span style="text-align:center;">FIN FINAL</span></strong></td>
                            <td style="text-align:center;"><?= number_format($rowAPf['JUMLAHKK'] ?? 0) ?></td>
                            <td style="text-align:center;"><?= number_format($rowAPf['TOTAL_QTY'] ?? 0, 2) ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query for POTONG BULU LAIN-LAIN
                            $query_potongbulu_lainlain = "
                                SELECT
                                    SUM(qty) AS qty_potongbulu_lain_lain,
                                    COUNT(*) AS jumlah_kk
                                FROM (
                                    SELECT 
                                        nokk,
                                        GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                        MAX(langganan) AS langganan,
                                        MAX(proses) AS proses,
                                        SUM(qty) AS qty
                                    FROM
                                        tbl_produksi tp
                                    WHERE
                                        tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
										AND proses LIKE 'POTONG BULU LAIN-LAIN%'
                                        -- AND proses IN (
										-- 'Potong Bulu (Bantu)',
										-- 'POTONG BULU 07 (Bantu)',
										-- 'POTONG BULU LAIN-LAIN (Bantu)',
										-- 'POTONG BULU LAIN-LAIN (Khusus)',
										-- 'POTONG BULU BACK BANTU-DYEING (Bantu)',
										-- 'POTONG BULU BACK BANTU-FIN (Bantu)',
										-- 'POTONG BULU BACK TAS BANTU (Bantu)',
										-- 'POTONG BULU FACE BANTU-DYEING (Bantu)',
										-- 'POTONG BULU FACE BANTU-FIN (Bantu)',
										-- 'POTONG BULU FACE BANTU-TAS (Bantu)',
										-- 'POTONG BULU FACE TAS BANTU (Bantu)',
										-- 'POTONG BULU GREIGE (Bantu)',
										-- 'POTONG BULU GREIGE (Normal)',
										-- 'PEACH BANTU TAS (Bantu)',
										-- 'PEACH SKIN (Bantu)',
										-- 'PEACH SKIN BANTU - DYE (Bantu)',
										-- 'PEACH SKIN BANTU - FIN (Bantu)',
										-- 'GARUK BANTU - DYG (Bantu)',
										-- 'GARUK BANTU - FIN (Bantu)',
										-- 'GARUK GREIGE (Bantu)',
										-- 'GARUK PERBAIKAN DYG (Bantu)'
										-- )
                                    GROUP BY
                                        id
                                ) AS t
                            ";
                            $result_potongbulu_lainlain = mysqli_query($conb, $query_potongbulu_lainlain);
                            $row_potongbulu_lainlain = mysqli_fetch_assoc($result_potongbulu_lainlain);
                            ?>
                            <td colspan="2"><strong>POTONG BULU LAIN-LAIN</strong></td>
                            <td style="text-align:center;"><?= number_format($row_potongbulu_lainlain['jumlah_kk'] ?? 0); ?></td>
                            <td style="text-align:center;"><?= number_format($row_potongbulu_lainlain['qty_potongbulu_lain_lain'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query for ANTI PILLING LAIN-LAIN
                            $query_anti_pilling_lainlain = "
                                SELECT
                                    SUM(qty) AS qty_anti_pilling_lain_lain,
                                    COUNT(*) AS jumlah_kk
                                FROM (
                                    SELECT 
                                        nokk,
                                        GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                        MAX(langganan) AS langganan,
                                        MAX(proses) AS proses,
                                        SUM(qty) AS qty
                                    FROM
                                        tbl_produksi tp
                                    WHERE
                                        tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
										AND proses IN ('ANTI PILLING LAIN-LAIN (Bantu)','ANTI PILLING LAIN-LAIN (Khusus)')
                                        -- AND proses IN (
										-- 'ANTI PILLING BANTU - DYE (Bantu)',
										-- 'ANTI PILLING BANTU - FIN (Bantu)',
										-- 'ANTI PILLING BANTU - QC (Bantu)',
										-- 'ANTI PILLING BANTU - TAS (Bantu)',
										-- 'ANTI PILLING BANTU-DYEING (Bantu)',
										-- 'ANTI PILLING BANTU-FINISHING (Bantu)',
										-- 'ANTI PILLING LAIN-LAIN (Bantu)',
										-- 'ANTI PILLING LAIN-LAIN (Khusus)',
										-- 'PEACH BANTU TAS (Bantu)',
										-- 'PEACH SKIN (Bantu)',
										-- 'PEACH SKIN BANTU - DYE (Bantu)',
										-- 'PEACH SKIN BANTU - FIN (Bantu)',
										-- 'GARUK BANTU - DYG (Bantu)',
										-- 'GARUK BANTU - FIN (Bantu)',
										-- 'GARUK GREIGE (Bantu)',
										-- 'GARUK PERBAIKAN DYG (Bantu)'
										-- )
                                    GROUP BY
                                        id
                                ) AS t";
                            $result_anti_pilling_lainlain = mysqli_query($conb, $query_anti_pilling_lainlain);
                            $row_anti_pilling_lainlain = mysqli_fetch_assoc($result_anti_pilling_lainlain);
                            ?>
                            <td colspan="2"><strong>ANTI PILLING LAIN-LAIN</strong></td>
                            <td style="text-align:center;"><?= number_format($row_anti_pilling_lainlain['jumlah_kk'] ?? 0); ?></td>
                            <td style="text-align:center;"><?= number_format($row_anti_pilling_lainlain['qty_anti_pilling_lain_lain'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query for ARIO   
                            $query_ario = "
                                SELECT
                                    SUM(qty) AS qty_ario,
                                    COUNT(*) AS jumlah_kk
                                FROM (
                                    SELECT 
                                        nokk,
                                        GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                        MAX(langganan) AS langganan,
                                        MAX(proses) AS proses,
                                        SUM(qty) AS qty
                                    FROM
                                        tbl_produksi tp
                                    WHERE
                                        tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                        AND proses IN ('AIRO (Normal)')
                                    GROUP BY
                                        id
                                ) AS t";
                            $result_ario = mysqli_query($conb, $query_ario);
                            $row_ario = mysqli_fetch_assoc($result_ario);
                            ?>
                            <td colspan="2"><strong>AIRO</strong></td>
                            <td style="text-align:center;"><?= number_format($row_ario['jumlah_kk'] ?? 0); ?></td>
                            <td style="text-align:center;"><?= number_format($row_ario['qty_ario'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query for SISIR
                             $query_sisir = "
                                SELECT
                                    SUM(qty) AS qty_sisir,
                                    COUNT(*) AS jumlah_kk
                                FROM (
                                    SELECT 
                                        nokk,
                                        GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                        MAX(langganan) AS langganan,
                                        MAX(proses) AS proses,
                                        SUM(qty) AS qty
                                    FROM
                                        tbl_produksi tp
                                    WHERE
                                        tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                        AND proses IN (
										'SISIR ANTI PILLING (Normal)',
										'SISIR BANTU (FIN) (Bantu)',
										'SISIR LAIN-LAIN (Bantu)'
										)
                                    GROUP BY
                                        id
                                ) AS t";
                            $result_sisir = mysqli_query($conb, $query_sisir);
                            $row_sisirr = mysqli_fetch_assoc($result_sisir);
                            ?>
                            <td colspan="2"><strong>SISIR</strong></td>
                            <td style="text-align:center;"><?= number_format($row_sisirr['jumlah_kk'] ?? 0); ?></td>
                            <td style="text-align:center;"><?= number_format($row_sisirr['qty_sisir'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query for PEACH SKIN
                           $query_peach = "
                            SELECT
                                SUM(qty) AS qty_peach,
                                COUNT(*) AS jumlah_kk
                            FROM (
                                SELECT 
                                    nokk,
                                    GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                    MAX(langganan) AS langganan,
                                    MAX(proses) AS proses,
                                    SUM(qty) AS qty
                                FROM
                                    tbl_produksi tp
                                WHERE
                                    tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                    AND proses IN (
									'PEACH SKIN (Normal)'
									)
                                GROUP BY
                                    id
                            ) AS t";
                            $result_peach = mysqli_query($conb, $query_peach);
                            $row_peachh = mysqli_fetch_assoc($result_peach);
                            ?>
                            <td colspan="2"><strong>PEACH SKIN</strong></td>
                            <td style="text-align:center;"><?= number_format($row_peachh['jumlah_kk'] ?? 0); ?></td>
                            <td style="text-align:center;"><?= number_format($row_peachh['qty_peach'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query for PEACH SKIN GREIGE
                           $query_peach_greige = "
                            SELECT
                                SUM(qty) AS qty_peach_greige,
                                COUNT(*) AS jumlah_kk
                                FROM (
                                SELECT 
                                    nokk,
                                    GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                    MAX(langganan) AS langganan,
                                    MAX(proses) AS proses,
                                    SUM(qty) AS qty
                                FROM
                                    tbl_produksi tp
                                WHERE
                                    tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                    AND proses IN ('PEACHSKIN GREIGE (Normal)')
                                    GROUP BY
                                    id
                                ) AS t";

                            $result_peach_greige = mysqli_query($conb, $query_peach_greige);
                            $row_peach_greige = mysqli_fetch_assoc($result_peach_greige);
                            ?>
                            <td colspan="2"><strong>PEACH SKIN GREIGE</strong></td>
                            <td style="text-align:center;"><?= number_format($row_peach_greige['jumlah_kk'] ?? 0); ?></td>
                            <td style="text-align:center;"><?= number_format($row_peach_greige['qty_peach_greige'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query for GARUK GREIGE
                            $query_garuk_greige = "
                                SELECT
                                    SUM(qty) AS qty_garuk_greige,
                                    COUNT(*) AS jumlah_kk
                                FROM (
                                    SELECT 
                                        nokk,
                                        GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                        MAX(langganan) AS langganan,
                                        MAX(proses) AS proses,
                                        SUM(qty) AS qty
                                    FROM
                                        tbl_produksi tp
                                    WHERE
                                        tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                        AND proses IN ('GARUK GREIGE (Normal)')
                                    GROUP BY
                                        id
                                ) AS t";
                            $result_garuk_greige = mysqli_query($conb, $query_garuk_greige);
                            $row_garuk_greige = mysqli_fetch_assoc($result_garuk_greige);
                            ?>
                            <td colspan="2"><strong>GARUK GREIGE</strong></td>
                            <td style="text-align:center;"><?= number_format($row_garuk_greige['jumlah_kk'] ?? 0); ?></td>
                            <td style="text-align:center;"><?= number_format($row_garuk_greige['qty_garuk_greige'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <?php
                            // Query for GARUK FLEECE TAMBAH OBAT
                            $query_garuk_fleece_tambah_obat = "
                                SELECT
                                    SUM(qty) AS qty_garuk_fleece_tambah_obat,
                                    COUNT(*) AS jumlah_kk
                                FROM (
                                    SELECT 
                                        nokk,
                                        GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                        MAX(langganan) AS langganan,
                                        MAX(proses) AS proses,
                                        SUM(qty) AS qty
                                    FROM
                                        tbl_produksi tp
                                    WHERE
                                        tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                        AND proses IN ('GARUK FLEECE Tambah Obat (Normal)')
                                    GROUP BY
                                        id
                                ) AS t";
                            $result_garuk_fleece_tambah_obat = mysqli_query(
                                $conb,
                                $query_garuk_fleece_tambah_obat
                            );
                            $row_garuk_fleece_tambah_obat = mysqli_fetch_assoc($result_garuk_fleece_tambah_obat);
                            ?>
                            <td colspan="2"><strong>GARUK FLEECE TAMBAH OBAT</strong></td>
                            <td style="text-align:center;"><?= number_format($row_garuk_fleece_tambah_obat['jumlah_kk'] ?? 0); ?></td>
                            <td style="text-align:center;"><?= number_format($row_garuk_fleece_tambah_obat['qty_garuk_fleece_tambah_obat'] ?? 0, 2); ?></td>
                        </tr>
                        <tr style="font-weight:bold; background-color:yellow;">
                            <?php
                            // Hitung total JUMLAHKK dan TOTAL_QTY dari semua proses di atas (kecuali PERBAIKAN)
                            $total_jumlahkkk =
                                ((int)($rowFLEECEF3C20069['JUMLAHKK'] ?? 0)) +
                                ((int)($rowFLEECEF3C20069F['JUMLAHKK'] ?? 0)) +
                                ((int)($rowFLEECE['JUMLAHKK'] ?? 0)) +
                                ((int)($rowFINAL['JUMLAHKK'] ?? 0)) +
                                ((int)($rowAP['JUMLAHKK'] ?? 0)) +
                                ((int)($rowAPf['JUMLAHKK'] ?? 0)) +
                                ((int)($row_potongbulu_lainlain['jumlah_kk'] ?? 0)) +
                                ((int)($row_anti_pilling_lainlain['jumlah_kk'] ?? 0)) +
                                ((int)($row_ario['jumlah_kk'] ?? 0)) +
                                ((int)($row_sisirr['jumlah_kk'] ?? 0)) +
                                ((int)($row_peachh['jumlah_kk'] ?? 0)) +
                                ((int)($row_peach_greige['jumlah_kk'] ?? 0)) +
                                ((int)($row_garuk_greige['jumlah_kk'] ?? 0)) +
                            ((int)($row_garuk_fleece_tambah_obat['jumlah_kk'] ?? 0));

                            $total_qtyy =
                                ((float)($row_potongbulu_lainlain['qty_potongbulu_lain_lain'] ?? 0)) +
                                ((float)($row_anti_pilling_lainlain['qty_anti_pilling_lain_lain'] ?? 0)) +
                                ((float)($row_ario['qty_ario'] ?? 0)) +
                                ((float)($row_sisirr['qty_sisir'] ?? 0)) +
                                ((float)($row_peachh['qty_peach'] ?? 0)) +
                                ((float)($row_peach_greige['qty_peach_greige'] ?? 0)) +
                                ((float)($row_garuk_greige['qty_garuk_greige'] ?? 0)) +
                                ((float)($row_garuk_fleece_tambah_obat['qty_garuk_fleece_tambah_obat'] ?? 0)) +
                                ((float)($rowFLEECEF3C20069['TOTAL_QTY'] ?? 0)) +
                                ((float)($rowFLEECEF3C20069F['TOTAL_QTY'] ?? 0)) +
                                ((float)($rowFINAL['TOTAL_QTY'] ?? 0)) +
                                ((float)($rowFLEECE['TOTAL_QTY'] ?? 0)) +
                                ((float)($rowAP['TOTAL_QTY'] ?? 0)) +
                                ((float)($rowAPf['TOTAL_QTY'] ?? 0));
                            ?>

                            <td style="text-align:center;" colspan="2">TOTAL</td>
                            <td style="text-align:center;"><?= $total_jumlahkkk ?></td>
                            <td style="text-align:center;"><?= number_format($total_qtyy, 2) ?></td>
                            <!-- <td style="text-align:center;">&nbsp;</td> -->                        </tr>
<!--            </tr>-->
        </table>
	  </td>
      <td width="5%">&nbsp;</td>
      <td width="30%" align="left" valign="top">
		<?php
                            // Query for Quantity Sisa
							$tgl_awal_sebelumnya = date('Y-m-d', strtotime($tglAwal_tbl4 . ' -1 day'));
							$tgl_akhir_sebelumnya = date('Y-m-d', strtotime($tglAkhir_tbl4 . ' -1 day'));
                            $query_qty_sisa = "
							SELECT tth.* FROM tbl_tutup_harian AS tth
							WHERE
								tth.tgl_awal = '$tgl_awal_sebelumnya'
								and tth.tgl_akhir = '$tgl_akhir_sebelumnya'
                                ";
                            $result_qty_sisa = mysqli_query(
                                $conb,
                                $query_qty_sisa
                            );
                            $row_qty_sisa = mysqli_fetch_assoc($result_qty_sisa);
        ?>    
		<table border="1" class="table-list1" width="100%">
                <tr>
                    <td colspan="3" style="text-align:center;"><strong>QUANTITY SISA</strong></td>
                </tr>
                <tr>
                    <td><strong>JENIS PROSES</strong></td>
                    <td><strong>JUMLAH KK</strong></td>
                    <td><strong>QUANTITY</strong></td>
                </tr>
                <tr>
                    <td><strong>GARUK FLEECE</strong></td>
					<?php                    
					   $total_kk_garukfleece = (($row_garuk_fleece['JUMLAHKK']) ?? 0) - (($rowFLEECEF3C20069['JUMLAHKK']+$rowFLEECEF3C20069F['JUMLAHKK']+$rowFLEECE['JUMLAHKK']+$rowFINAL['JUMLAHKK']) ?? 0);
                       $total_qty_garukfleece = (($row_garuk_fleece['TOTAL_QTY']) ?? 0) - (($rowFLEECEF3C20069['TOTAL_QTY']+$rowFLEECEF3C20069F['TOTAL_QTY']+$rowFLEECE['TOTAL_QTY']+$rowFINAL['TOTAL_QTY']) ?? 0);
                    ?>
                    <td style="text-align:center;"><?= $total_kk_garukfleece; ?></td>
                    <td style="text-align:center;"><?= number_format($total_qty_garukfleece, 2); ?></td>
                    <?php
                    // $total_kk_garukfleece = ($row_garuk_fleece['JUMLAHKK'] ?? 0) - ($row_garuk_greige['jumlah_kk'] ?? 0);
                    // $total_qty_garukfleece = ($row_garuk_fleece['TOTAL_QTY'] ?? 0) - ($row_garuk_greige['qty_garuk_fleece_tambah_obat'] ?? 0);
                    ?>
                </tr>
                <tr>

                    <td><strong>POTONG BULU FLEECE</strong></td>
					<?php
//                     $total_kk_potongbulu = (($row_garuk_anti_pilling['JUMLAHKK']) ?? 0) - (($rowAP['JUMLAHKK']+$rowAPf['JUMLAHKK']) ?? 0);
//                     $total_qtybulu = (($row_garuk_anti_pilling['TOTAL_QTY']) ?? 0) - (($rowAP['TOTAL_QTY']+$rowAPf['TOTAL_QTY']) ?? 0);
                    ?>
                    <td style="text-align:center;"><?= 0 //$total_kk_potongbulu; ?></td>
                    <td style="text-align:center;"><?= number_format(0,2) //number_format($total_qtybulu, 2); ?></td>                    
                </tr>
                <tr>
                    <td><strong>GARUK ANTI PILLING</strong></td>
					<?php
                     $total_kk_garuk_kain_fleece = (($row_garuk_anti_pilling['JUMLAHKK']) ?? 0) - (($rowAP['JUMLAHKK']+$rowAPf['JUMLAHKK']) ?? 0);
                     $total_qty_garuk_kain_fleece = (($row_garuk_anti_pilling['TOTAL_QTY']) ?? 0) - (($rowAP['TOTAL_QTY']+$rowAPf['TOTAL_QTY']) ?? 0);
                    ?>
                    <td style="text-align:center;"><?= $total_kk_garuk_kain_fleece; ?></td>
                    <td style="text-align:center;"><?= number_format($total_qty_garuk_kain_fleece, 2); ?></td>                    
                </tr>
                <tr>
                    <td><strong>SISIR LAIN-LAIN</strong></td>
					<?php
                     $total_kk_sisir = (($row_sisir['JUMLAHKK']+$row_qty_sisa['sisir_lain_lain_lot']) ?? 0) - ($row_sisirr['jumlah_kk'] ?? 0);
                     $total_qty_sisir = (($row_sisir['TOTAL_QTY']+$row_qty_sisa['sisir_lain_lain']) ?? 0) - ($row_sisirr['qty_sisir'] ?? 0);
                    ?>
                    <td style="text-align:center;"><?= $total_kk_sisir; ?></td>
                    <td style="text-align:center;"><?= number_format($total_qty_sisir, 2); ?></td>
                </tr>
                <tr>
                    <td><strong>POTONG BULU LAIN LAIN</strong></td>
					<?php                    
//                     $total_kk_potongbulu_lainlain = (($row_potongbulu['JUMLAHKK']) ?? 0) - ($row_potongbulu_lainlain['jumlah_kk'] ?? 0);
//                     $total_qty_potongbulu_lainlain = (($row_potongbulu['TOTAL_QTY']) ?? 0) - ($row_potongbulu_lainlain['qty_potongbulu_lain_lain'] ?? 0);
                    ?>
                    <td style="text-align:center;"><?= 0//$total_kk_potongbulu_lainlain; ?></td>
                    <td style="text-align:center;"><?= number_format(0, 2)//number_format($total_qty_potongbulu_lainlain, 2); ?></td>                    
                </tr>
                <tr>
                    <td><strong>OVEN ANTI PILLING</strong></td>
					<?php                    
                     $total_kk_ov_ap = (($row_tdr1['JUMLAHKK']) ?? 0) - ($row_anti_pilling_lainlain['jumlah_kk'] ?? 0);
                     $total_qty_ov_ap = (($row_tdr1['TOTAL_QTY']) ?? 0) - ($row_anti_pilling_lainlain['qty_anti_pilling_lain_lain'] ?? 0);
                    ?>
                    <td style="text-align:center;"><?= $total_kk_ov_ap; ?></td>
                    <td style="text-align:center;"><?= number_format($total_qty_ov_ap, 2); ?></td>
                </tr>
                <tr>
                    <td><strong>PEACH SKIN</strong></td>
					<?php                    
                     $total_kk_peachskin = (($rowpeachskin['JUMLAHKK']) ?? 0) - ($row_peachh['jumlah_kk'] ?? 0);
                     $total_qty_peachskin = (($rowpeachskin['TOTAL_QTY']) ?? 0) - ($row_peachh['qty_peach'] ?? 0);
                    ?>
                    <td style="text-align:center;"><?= $total_kk_peachskin; ?></td>
                    <td style="text-align:center;"><?= number_format($total_qty_peachskin, 2); ?></td>                    
                </tr>
                <tr>
                    <td><strong>PEACH + GARUK GREIGE</strong></td>
                    <td style="text-align:center;">0</td>
                    <td style="text-align:center;">0.00</td>
                </tr>
                <tr>
                    <td><strong>PEACH + GARUK CELUP</strong></td>
                    <td style="text-align:center;">0</td>
                    <td style="text-align:center;">0.00</td>
                </tr>
                <tr>
                    <td><strong>WET SUEDING</strong></td>
                    <td style="text-align:center;">0</td>
                    <td style="text-align:center;">0.00</td>
                </tr>
                <tr>
                    <td><strong>OVEN ANTI PILLING LAIN-</strong></td>
                    <td style="text-align:center;">0</td>
                    <td style="text-align:center;">0.00</td>
                </tr>
                <tr>
                    <td><strong>PEACH SKIN GREIGE</strong></td>
					<?php
                    // $total_kk_peachskin_greige = ($row_peachskin_greige['JUMLAHKK'] ?? 0) - ($row_peach_greige['jumlah_kk'] ?? 0);
                    // $total_qty_peachskin_greige = ($row_peachskin_greige['TOTAL_QTY'] ?? 0) - ($row_peach_greige['qty_peach_greige'] ?? 0);
                     $total_kk_peachskin_greige = (($row_peachskin_greige['JUMLAHKK']+$row_qty_sisa['peach_skin_greige_lot']) ?? 0) - ($row_peach_greige['jumlah_kk'] ?? 0);
                     $total_qty_peachskin_greige = (($row_peachskin_greige['TOTAL_QTY']+$row_qty_sisa['peach_skin_greige']) ?? 0) - ($row_peach_greige['qty_peach_greige'] ?? 0);
                    ?>
                    <td style="text-align:center;"><?= $total_kk_peachskin_greige; ?></td>
                    <td style="text-align:center;"><?= number_format($total_qty_peachskin_greige, 2); ?></td>
                    <?php
                    // $total_kk_peachskin_greige = ($row_peachskin_greige['JUMLAHKK'] ?? 0) - ($row_peach_greige['jumlah_kk'] ?? 0);
                    // $total_qty_peachskin_greige = ($row_peachskin_greige['TOTAL_QTY'] ?? 0) - ($row_peach_greige['qty_peach_greige'] ?? 0);
                    ?>
                </tr>
                <tr>
                    <td><strong>GARUK GREIGE</strong></td>
                    <td style="text-align:center;">0</td>
                    <td style="text-align:center;">0.00</td>
                </tr>
                <tr>
                    <td><strong>BALIK KAIN SIAP FINISHING</strong></td>
                    <td style="text-align:center;">0</td>
                    <td style="text-align:center;">0.00</td>
                </tr>
                <tr style="font-weight:bold; background-color:yellow;">
                    <td style="text-align:center;">TOTAL SISA</td>
                    <td style="text-align:center;"><?php
                        $total_sisa_kk = $total_kk_garukfleece +
								$total_kk_potongbulu +
								$total_kk_garuk_kain_fleece +
								$total_kk_sisir +
								$total_kk_potongbulu_lainlain +
								$total_kk_ov_ap +
								$total_kk_peachskin +
								$total_kk_peachskin_greige;
                        echo $total_sisa_kk;
                        ?></td>
                    <td style="text-align:center;"><?php
                        $total_sisa_qty = $total_qty_garukfleece +
									$total_qtybulu +
									$total_qty_garuk_kain_fleece +
									$total_qty_sisir +
									$total_qty_potongbulu_lainlain +
									$total_qty_ov_ap +
									$total_qty_peachskin +
									$total_qty_peachskin_greige;
                        echo number_format($total_sisa_qty, 2);
                        ?></td>
                </tr>
            </table>
	  </td>
    </tr>
  </tbody>
</table>
<!-- End Table 4-->		
<br>	
<!-- Tabel-5.php -->

        <table border="1" class="table-list1" width="100%">
            <td colspan="10" style="text-align:center;">
                <center><b>LAPORAN PENCAPAIAN KARTU KERJA DEPARTEMEN BRUSHING</b></center>
            </td>
            <tr style="background: #fff;">
                <td rowspan="2" style="text-align:center; vertical-align:middle;"><strong>JENIS KARTU KERJA</strong></td>
                <td rowspan="2" style="text-align:center; vertical-align:middle;"><strong>TARGET</strong></td>
                <td rowspan="2" style="text-align:center; vertical-align:middle;"><strong>KARTU KERJA MASUK</strong></td>
                <td colspan="3" style="text-align:center;"><strong>KARTU KERJA KELUAR</strong></td>
                <td rowspan="2" style="text-align:center; vertical-align:middle;"><strong>KARTU KERJA SISA</strong></td>
                <td colspan="2" style="text-align:center;"><strong>PERSENTASE KARTU KERJA TIDAK TERCAPAI</strong></td>
                <td rowspan="2" style="text-align:center; vertical-align:middle;"><strong>PENCAPAIAN</strong></td>
            </tr>
            <tr style="background: #fff;">
                <td style="text-align:center;"><strong>TERCAPAI</strong></td>
                <td style="text-align:center;"><strong>TIDAK TERCAPAI LIBUR</strong></td>
                <td style="text-align:center;"><strong>TIDAK TERCAPAI PROSES</strong></td>
                <td style="text-align:center;"><strong>LIBUR</strong></td>
                <td style="text-align:center;"><strong>PROSES</strong></td>
            </tr>
            <?php
            // Query for total not achieved for DOMESTIC
            $query_not_achieved_DOM =
                "select
                    COUNT(*) as total_not_achieved
                from
                    (
                with ranked_data as (
                    select
                        *,
                        row_number() over (partition by id
                    order by
                        tgl_buat desc) as rn,
                        TIMESTAMPDIFF(hour,
                            CONCAT(tgl_proses_in, ' ', jam_in),
                            CONCAT(tgl_proses_out, ' ', jam_out)
                        ) as durasi_jam
                    from
                        tbl_produksi
                    where
                        tgl_buat >= '$start'
                        and tgl_buat < '$tglAkhir_tbl4'
                        and ( no_order like 'DOM%' or
									no_order like 'EXP%' or 
                                    no_order like 'OPN%' )
                )
                select
                    *
                from
                    ranked_data
                where
                    rn = 1
                    and (durasi_jam > 30 or durasi_jam is null)) as t";

            $result_not_achieved_DOM = mysqli_query($conb, $query_not_achieved_DOM);
            $row_not_achieved_DOM = mysqli_fetch_assoc($result_not_achieved_DOM);
            $total_not_achieved_DOM = $row_not_achieved_DOM['total_not_achieved'] ?? 0;

            // Query for total not achieved for REP
            $query_not_achieved_REP =
                "select
                                COUNT(*) as total_count
                            from
                                (
                            with ranked_data as (
                                select
                                    *,
                                    row_number() over (partition by id
                                order by
                                    tgl_buat desc) as rn,
                                    TIMESTAMPDIFF(hour,
                                        CONCAT(tgl_proses_in, ' ', jam_in),
                                        CONCAT(tgl_proses_out, ' ', jam_out)
                                    ) as durasi_jam
                                from
                                    tbl_produksi
                                where
                                    tgl_buat >= '$start'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and no_order like 'REP%'
                            )
                            select
                                *
                            from
                                ranked_data
                            where
                                rn = 1
                                and (durasi_jam > 12 or durasi_jam is null)) as t";
            $result_not_achieved_REP = mysqli_query($conb, $query_not_achieved_REP);
            $row_not_achieved_REP = mysqli_fetch_assoc($result_not_achieved_REP);
            $total_not_achieved_REP = $row_not_achieved_REP['total_not_achieved'] ?? 0;

            // Query for total not achieved for MBE
            $query_not_achieved_MBE =
                "select
                COUNT(*) as total_not_achieved
                from
                (
                with ranked_data as (
                select
                    *,
                    row_number() over (partition by id
                order by
                    tgl_buat desc) as rn,
                    TIMESTAMPDIFF(hour,
                    CONCAT(tgl_proses_in, ' ', jam_in),
                    CONCAT(tgl_proses_out, ' ', jam_out)
                    ) as durasi_jam
                from
                    tbl_produksi
                where
                    tgl_buat >= '$start'
                    and tgl_buat < '$tglAkhir_tbl4'
                    and (no_order like 'MBE%' or no_order like 'MNB%')
                )
                select
                *
                from
                ranked_data
                where
                rn = 1
                and (durasi_jam > 12 or durasi_jam is null)) as t";
            $result_not_achieved_MBE = mysqli_query($conb, $query_not_achieved_MBE);
            $row_not_achieved_MBE = mysqli_fetch_assoc($result_not_achieved_MBE);
            $total_not_achieved_MBE = $row_not_achieved_MBE['total_not_achieved'] ?? 0;

            // Query for total not achieved for SAM
            $query_not_achieved_SAM =
                "select
                                COUNT(*) as total_count
                            from
                                (
                            with ranked_data as (
                                select
                                    *,
                                    row_number() over (partition by id
                                order by
                                    tgl_buat desc) as rn,
                                    TIMESTAMPDIFF(hour,
                                        CONCAT(tgl_proses_in, ' ', jam_in),
                                        CONCAT(tgl_proses_out, ' ', jam_out)
                                    ) as durasi_jam
                                from
                                    tbl_produksi
                                where
                                    tgl_buat >= '$start'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and no_order like 'SAM%'
                            )
                            select
                                *
                            from
                                ranked_data
                            where
                                rn = 1
                                and (durasi_jam > 12 or durasi_jam is null)) as t";
            $result_not_achieved_SAM = mysqli_query($conb, $query_not_achieved_SAM);
            $row_not_achieved_SAM = mysqli_fetch_assoc($result_not_achieved_SAM);
            $total_not_achieved_SAM = $row_not_achieved_SAM['total_not_achieved'] ?? 0;

            // Query for total not achieved for BP
            $query_not_achieved_BP =
                "select
                                COUNT(*) as total_count
                            from
                                (
                            with ranked_data as (
                                select
                                    *,
                                    row_number() over (partition by id
                                order by
                                    tgl_buat desc) as rn,
                                    TIMESTAMPDIFF(hour,
                                        CONCAT(tgl_proses_in, ' ', jam_in),
                                        CONCAT(tgl_proses_out, ' ', jam_out)
                                    ) as durasi_jam
                                from
                                    tbl_produksi
                                where
                                    tgl_buat >= '$start'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and no_order like 'BP%'
                            )
                            select
                                *
                            from
                                ranked_data
                            where
                                rn = 1
                                and (durasi_jam > 12 or durasi_jam is null)) as t";
            $result_not_achieved_BP = mysqli_query($conb, $query_not_achieved_BP);
            $row_not_achieved_BP = mysqli_fetch_assoc($result_not_achieved_BP);
            $total_not_achieved_BP = $row_not_achieved_BP['total_not_achieved'] ?? 0;

            // Query for total not achieved for RET
            $query_not_achieved_RET =
                "select
                                COUNT(*) as total_count
                            from
                                (
                            with ranked_data as (
                                select
                                    *,
                                    row_number() over (partition by id
                                order by
                                    tgl_buat desc) as rn,
                                    TIMESTAMPDIFF(hour,
                                        CONCAT(tgl_proses_in, ' ', jam_in),
                                        CONCAT(tgl_proses_out, ' ', jam_out)
                                    ) as durasi_jam
                                from
                                    tbl_produksi
                                where
                                    tgl_buat >= '$start'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and ( no_order like 'RET%' or
									no_order like 'RFD%' )
                            )
                            select
                                *
                            from
                                ranked_data
                            where
                                rn = 1
                                and (durasi_jam > 12 or durasi_jam is null)) as t";
            $result_not_achieved_RET = mysqli_query($conb, $query_not_achieved_RET);
            $row_not_achieved_RET = mysqli_fetch_assoc($result_not_achieved_RET);
            $total_not_achieved_RET = $row_not_achieved_RET['total_not_achieved'] ?? 0;
            ?>

            <tr>
              <td><strong>KARTU KERJA BIASA</strong></td>
              <?php
                $query_KARTU_KERJA_BIASA =
                    "SELECT COUNT(*) AS TOTAL_KARTU_KERJA_BIASA
                    FROM (
                        SELECT DISTINCT 
						    d.CODE,
                            p.PRODUCTIONORDERCODE,
                            p2.PRODUCTIONORDERCODE,
							p.OPERATIONCODE
                        FROM
                            PRODUCTIONPROGRESS p
                        LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                        LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                        WHERE
                            TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                        AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                            AND p.PROGRESSTEMPLATECODE = 'S01'  
                            AND d.DLVSALORDLINESALORDCNTCODE IN ('DOMESTIC', 'EXPORT', 'OPEN')
                            AND p.OPERATIONCODE IN (
                                'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                                'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                                'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                            )
                    ) AS t;
                ";


                $resultKARTU_KERJA_BIASA = db2_exec($conn2, $query_KARTU_KERJA_BIASA);
                $row_KARTU_KERJA_BIASA = db2_fetch_assoc($resultKARTU_KERJA_BIASA);
                $total_KARTU_KERJA_BIASA = $row_KARTU_KERJA_BIASA['TOTAL_KARTU_KERJA_BIASA'] ?? 0;
                ?>
              <td>30 Jam</td>
              <td align="center"><?= htmlspecialchars($total_KARTU_KERJA_BIASA); ?></td>
              <?php
                
                //DOMESTIC
                $query = "select
                                COUNT(*) as total_count
                            from
                                (
                            with ranked_data as (
                                select
                                    *,
                                    row_number() over (partition by id
                                order by
                                    tgl_buat desc) as rn,
                                    TIMESTAMPDIFF(hour,
                                        CONCAT(tgl_proses_in, ' ', jam_in),
                                        CONCAT(tgl_proses_out, ' ', jam_out)
                                    ) as durasi_jam
                                from
                                    tbl_produksi
                                where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and (no_order like 'DOM%' or  
                                    no_order like 'EXP%' or 
                                    no_order like 'OPN%' or 
                                    no_order = '' 
                                    )
                            )
                            select
                                *
                            from
                                ranked_data
                            where
                                rn = 1
                                and durasi_jam <= 30) as t
                            ";
                $result = mysqli_query($conb, $query);
                $row = mysqli_fetch_assoc($result);
                $total_tercapai_30_jamDOM = $row['total_count'] ?? 0;



                $query_tidak_tercapai_30_jam_libur =
                    "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI_LIBUR
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                and (no_order like 'DOM%' or  
                                    no_order like 'EXP%' or 
                                    no_order like 'OPN%' or 
                                    no_order = '' 
                                    )
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 30 or durasi_jam is null)
                            and hari_ke = 1 -- 1: Minggu saja
                    ) as t";
                        


                $result_tidak_tercapai_30_jam_libur = mysqli_query($conb, $query_tidak_tercapai_30_jam_libur);
                $row_tidak_tercapai_30_jam_libur = mysqli_fetch_assoc($result_tidak_tercapai_30_jam_libur);
                $total_tidak_tercapai_30_jam_libur = $row_tidak_tercapai_30_jam_libur['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
				
				$query_tidak_tercapai_30_jam =
                    "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                and (no_order like 'DOM%' or  
                                    no_order like 'EXP%' or 
                                    no_order like 'OPN%' or 
                                    no_order = '' 
                                    )
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 30 or durasi_jam is null)
                            and hari_ke > 1 -- 1: Minggu saja
                    ) as t";
                        


                $result_tidak_tercapai_30_jam = mysqli_query($conb, $query_tidak_tercapai_30_jam);
                $row_tidak_tercapai_30_jam = mysqli_fetch_assoc($result_tidak_tercapai_30_jam);
                $total_tidak_tercapai_30_jam = $row_tidak_tercapai_30_jam['TOTAL_TIDAK_TERCAPAI'] ?? 0;


                ?>
              <td align="center"><?= htmlspecialchars($total_tercapai_30_jamDOM); ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_30_jam_libur); ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_30_jam); ?></td>
              <!-- persentase libur -->
              <td align="center"><?php
                    if ($total_KARTU_KERJA_BIASA-($total_tercapai_30_jamDOM + $total_tidak_tercapai_30_jam_libur + $total_tidak_tercapai_30_jam) > 0) {
                        $persentase_sisa_kartu = (($total_KARTU_KERJA_BIASA-($total_tercapai_30_jamDOM + $total_tidak_tercapai_30_jam_libur + $total_tidak_tercapai_30_jam)) / $total_KARTU_KERJA_BIASA) * 100;
                        echo number_format($persentase_sisa_kartu, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_KARTU_KERJA_BIASA > 0) {
                        $persentase_tidak_tercapai_proses = ($total_tidak_tercapai_30_jam_libur / ($total_tercapai_30_jamDOM+$total_tidak_tercapai_30_jam_libur+$total_tidak_tercapai_30_jam)) * 100;
                        echo number_format($persentase_tidak_tercapai_proses, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_tidak_tercapai_30_jam > 0) {
                        $percentage = ($total_tidak_tercapai_30_jam / ($total_tercapai_30_jamDOM+$total_tidak_tercapai_30_jam_libur+$total_tidak_tercapai_30_jam)) * 100;
                        echo number_format($percentage, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_tercapai_30_jamDOM > 0) {
                        $percentage_tercapai = ($total_tercapai_30_jamDOM / ($total_tercapai_30_jamDOM+$total_tidak_tercapai_30_jam_libur+$total_tidak_tercapai_30_jam)) * 100;
                        echo number_format($percentage_tercapai, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
            </tr>
            <tr>
              <td><strong>GANTI KAIN EKSTERNAL</strong></td>
              <?php
                $query_GANTI_KAIN_EKSTERNAL =
                    "SELECT COUNT(*) AS TOTAL_GANTI_KAIN_EKSTERNAL
                    FROM (
                        SELECT DISTINCT 
						    d.CODE,
                            p.PRODUCTIONORDERCODE,
                            p2.PRODUCTIONORDERCODE,
							p.OPERATIONCODE
                        FROM
                            PRODUCTIONPROGRESS p
                        LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                        LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                        WHERE
                            TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                        AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                            AND p.PROGRESSTEMPLATECODE = 'S01'
                            AND d.DLVSALORDLINESALORDCNTCODE IN ('REPLCEXP')
                            AND p.OPERATIONCODE IN (
                                'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                                'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                                'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                            )
                    ) AS t
                    ";
                $resultGANTI_KAIN_EKSTERNAL = db2_exec($conn2, $query_GANTI_KAIN_EKSTERNAL);
                $row_GANTI_KAIN_EKSTERNAL = db2_fetch_assoc($resultGANTI_KAIN_EKSTERNAL);
                $total_GANTI_KAIN_EKSTERNAL = $row_GANTI_KAIN_EKSTERNAL['TOTAL_GANTI_KAIN_EKSTERNAL'] ?? 0;
                ?>
              <td>12 Jam</td>
              <td align="center"><?= htmlspecialchars($total_GANTI_KAIN_EKSTERNAL); ?></td>
              <?php
                $query = "select
                                COUNT(*) as total_count
                            from
                                (
                            with ranked_data as (
                                select
                                    *,
                                    row_number() over (partition by id
                                order by
                                    tgl_buat desc) as rn,
                                    TIMESTAMPDIFF(hour,
                                        CONCAT(tgl_proses_in, ' ', jam_in),
                                        CONCAT(tgl_proses_out, ' ', jam_out)
                                    ) as durasi_jam
                                from
                                    tbl_produksi
                                where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and no_order like 'REP%'
                            )
                            select
                                *
                            from
                                ranked_data
                            where
                                rn = 1
                                and durasi_jam <= 12) as t";

                $result = mysqli_query($conb, $query);
                $row = mysqli_fetch_assoc($result);
                $total_tercapai_12_jamREP = $row['total_count'] ?? 0;


                $query_tidak_tercapai_12_jam_libur = "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI_LIBUR
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                and no_order like 'REP%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 12 or durasi_jam is null)
                            and hari_ke = 1 -- 1: Minggu saja
                    ) as t";

                $result_tidak_tercapai_12_jam_libur = mysqli_query($conb, $query_tidak_tercapai_12_jam_libur);
                $row_tidak_tercapai_12_jam_libur = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_libur);
                $total_tidak_tercapai_12_jam_liburREP = $row_tidak_tercapai_12_jam_libur['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
				
				$query_tidak_tercapai_12_jam = "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                and no_order like 'REP%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 12 or durasi_jam is null)
                            and hari_ke > 1 -- 1: Minggu saja
                    ) as t";

                $result_tidak_tercapai_12_jam = mysqli_query($conb, $query_tidak_tercapai_12_jam);
                $row_tidak_tercapai_12_jam = mysqli_fetch_assoc($result_tidak_tercapai_12_jam);
                $total_tidak_tercapai_12_jamREP = $row_tidak_tercapai_12_jam['TOTAL_TIDAK_TERCAPAI'] ?? 0;
                ?>
              <td align="center"><?= htmlspecialchars($total_tercapai_12_jamREP) ?? 0; ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_liburREP) ?? 0; ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jamREP) ?? 0; ?></td>
              <td align="center"><?php
                    if ($total_GANTI_KAIN_EKSTERNAL-($total_tercapai_12_jamREP+$total_tidak_tercapai_12_jam_liburREP+$total_tidak_tercapai_12_jamREP) > 0) {
                        $persentase_sisa_kartuREP = (($total_GANTI_KAIN_EKSTERNAL-($total_tercapai_12_jamREP+$total_tidak_tercapai_12_jam_liburREP+$total_tidak_tercapai_12_jamREP)) / $total_GANTI_KAIN_EKSTERNAL) * 100;
                        echo number_format($persentase_sisa_kartuREP, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_tidak_tercapai_12_jam_liburREP > 0) {
                        $persentase_tidak_tercapai_prosesREP = ($total_tidak_tercapai_12_jam_liburREP / ($total_tercapai_12_jamREP+$total_tidak_tercapai_12_jam_liburREP+$total_tidak_tercapai_12_jamREP)) * 100;
                        echo number_format($persentase_tidak_tercapai_prosesREP, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <!-- pencapaian -->
              <td align="center"><?php
                    if ($total_tidak_tercapai_12_jamREP > 0) {
                        $percentageREP = ($total_tidak_tercapai_12_jamREP / ($total_tercapai_12_jamREP+$total_tidak_tercapai_12_jam_liburREP+$total_tidak_tercapai_12_jamREP)) * 100;
                        echo number_format($percentageREP, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_tercapai_12_jamREP > 0) {
                        $percentageREP_tercapai = ($total_tercapai_12_jamREP / ($total_tercapai_12_jamREP+$total_tidak_tercapai_12_jam_liburREP+$total_tidak_tercapai_12_jamREP)) * 100;
                        echo number_format($percentageREP_tercapai, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
            </tr>
            <tr>
              <td><strong>MINI BULK</strong></td>
              <?php
                $query_MINI_BULK =
                    "SELECT COUNT(*) AS TOTAL_MINI_BULK
                    FROM (
                        SELECT DISTINCT 
						    d.CODE,
                            p.PRODUCTIONORDERCODE,
                            p2.PRODUCTIONORDERCODE,
							p.OPERATIONCODE
                        FROM
                            PRODUCTIONPROGRESS p
                        LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                        LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                        WHERE
                            TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                        AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                            AND p.PROGRESSTEMPLATECODE = 'S01'
                            -- AND d.DLVSALORDLINESALORDCNTCODE IN ('MNB','MBE', 'TBG')
							AND d.DLVSALORDLINESALORDCNTCODE IN ('MNB','MBE')
                            AND p.OPERATIONCODE IN (
                                'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                                'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                                'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                            )
                    ) AS t
                    ";
                $resultMINI_BULK = db2_exec($conn2, $query_MINI_BULK);
                $row_MINI_BULK = db2_fetch_assoc($resultMINI_BULK);
                $total_MINI_BULK = $row_MINI_BULK['TOTAL_MINI_BULK'] ?? 0;
                ?>
              <td>12 Jam</td>
              <td align="center"><?= htmlspecialchars($total_MINI_BULK); ?></td>
              <?php
                $query = "select
                                COUNT(*) as total_count
                            from
                                (
                            with ranked_data as (
                                select
                                    *,
                                    row_number() over (partition by nokk
                                order by
                                    tgl_buat desc) as rn,
                                    TIMESTAMPDIFF(hour,
                                        CONCAT(tgl_proses_in, ' ', jam_in),
                                        CONCAT(tgl_proses_out, ' ', jam_out)
                                    ) as durasi_jam
                                from
                                    tbl_produksi
                                where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and no_order like 'MBE%'
                            )
                            select
                                *
                            from
                                ranked_data
                            where
                                rn = 1
                                and durasi_jam <= 12) as t";
                $result = mysqli_query($conb, $query);
                $row = mysqli_fetch_assoc($result);
                $total_tercapai_12_jamMBE = $row['total_count'] ?? 0;
				
                $query_tidak_tercapai_12_jam_hari_libur = "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI_LIBUR
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                and no_order like 'MBE%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 12 or durasi_jam is null)
                            and hari_ke = 1 -- 1: Minggu saja
                    ) as t";

                $result_tidak_tercapai_12_jam_hari_libur = mysqli_query($conb, $query_tidak_tercapai_12_jam_hari_libur);
                $row_tidak_tercapai_12_jam_hari_libur = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_hari_libur);
                $total_tidak_tercapai_12_jam_hari_liburMBE = $row_tidak_tercapai_12_jam_hari_libur['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
				
				$query_tidak_tercapai_12_jam_hari = "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                and no_order like 'MBE%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 12 or durasi_jam is null)
                            and hari_ke > 1 -- 1: Minggu saja
                    ) as t";

                $result_tidak_tercapai_12_jam_hari = mysqli_query($conb, $query_tidak_tercapai_12_jam_hari);
                $row_tidak_tercapai_12_jam_hari = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_hari);
                $total_tidak_tercapai_12_jam_hariMBE = $row_tidak_tercapai_12_jam_hari['TOTAL_TIDAK_TERCAPAI'] ?? 0;
                ?>
              <td align="center"><?= htmlspecialchars($total_tercapai_12_jamMBE) ?? 0; ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_hari_liburMBE) ?? 0; ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_hariMBE) ?? 0; ?></td>
              <td align="center"><?php
                    if ($total_MINI_BULK-($total_tercapai_12_jamMBE+$total_tidak_tercapai_12_jam_hari_liburMBE+$total_tidak_tercapai_12_jam_hariMBE) > 0) {
                        $persentase_sisa_kartuMBE = (($total_MINI_BULK-($total_tercapai_12_jamMBE+$total_tidak_tercapai_12_jam_hari_liburMBE+$total_tidak_tercapai_12_jam_hariMBE)) / $total_MINI_BULK) * 100;
                        echo number_format($persentase_sisa_kartuMBE, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_tidak_tercapai_12_jam_hari_liburMBE > 0) {
                        $persentase_tidak_tercapai_prosesMBE = ($total_tidak_tercapai_12_jam_hari_liburMBE /($total_tercapai_12_jamMBE+$total_tidak_tercapai_12_jam_hari_liburMBE+$total_tidak_tercapai_12_jam_hariMBE)) * 100;
                        echo number_format($persentase_tidak_tercapai_prosesMBE, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <!-- pencapaian -->
              <td align="center"><?php
                    if ($total_tidak_tercapai_12_jam_hariMBE > 0) {
                        $percentage_mini_bulk = ($total_tidak_tercapai_12_jam_hariMBE /($total_tercapai_12_jamMBE+$total_tidak_tercapai_12_jam_hari_liburMBE+$total_tidak_tercapai_12_jam_hariMBE)) * 100;
                        echo number_format($percentage_mini_bulk, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_tercapai_12_jamMBE > 0) {
                        $percentage_mini_bulk_tercapai = ($total_tercapai_12_jamMBE /($total_tercapai_12_jamMBE+$total_tidak_tercapai_12_jam_hari_liburMBE+$total_tidak_tercapai_12_jam_hariMBE)) * 100;
                        echo number_format($percentage_mini_bulk_tercapai, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
            </tr>
            <tr>
              <td><strong>SALESMAN SAMPLE</strong></td>
              <td>12 Jam</td>
              <?php
                $query_SALESMAN_SAMPLE =
                    "SELECT COUNT(*) AS TOTAL_SALESMAN_SAMPLE
                    FROM (
                        SELECT DISTINCT 
						    d.CODE,
                            p.PRODUCTIONORDERCODE,
                            p2.PRODUCTIONORDERCODE,
							p.OPERATIONCODE
                        FROM
                            PRODUCTIONPROGRESS p
                        LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                        LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                        WHERE
                            TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                            AND p.PROGRESSTEMPLATECODE = 'S01'
                            AND d.DLVSALORDLINESALORDCNTCODE IN ('SAMPDOM', 'SAMPLE')
                            AND p.OPERATIONCODE IN (
                                'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                                'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                                'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                            )
                    ) AS t;
                ";

                $result_SALESMAN_SAMPLE = db2_exec($conn2, $query_SALESMAN_SAMPLE);
                $row_SALESMAN_SAMPLE = db2_fetch_assoc($result_SALESMAN_SAMPLE);
                $total_SALESMAN_SAMPLE = $row_SALESMAN_SAMPLE['TOTAL_SALESMAN_SAMPLE'] ?? 0;
                ?>
              <td align="center"><?= htmlspecialchars($total_SALESMAN_SAMPLE); ?></td>
              <?php
                $query_SALESMAN_SAMPLE_12_JAM =
                    "select
                                COUNT(*) as total_count
                            from
                                (
                            with ranked_data as (
                                select
                                    *,
                                    row_number() over (partition by id
                                order by
                                    tgl_buat desc) as rn,
                                    TIMESTAMPDIFF(hour,
                                        CONCAT(tgl_proses_in, ' ', jam_in),
                                        CONCAT(tgl_proses_out, ' ', jam_out)
                                    ) as durasi_jam
                                from
                                    tbl_produksi
                                where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and (no_order like 'SAM%' OR no_order like 'SME%')
                            )
                            select
                                *
                            from
                                ranked_data
                            where
                                rn = 1
                                and durasi_jam <= 12) as t";
                $result_SALESMAN_SAMPLE_12_JAM = mysqli_query($conb, $query_SALESMAN_SAMPLE_12_JAM);
                $row_SALESMAN_SAMPLE_12_JAM = mysqli_fetch_assoc($result_SALESMAN_SAMPLE_12_JAM);
                $total_SALESMAN_SAMPLE_12_JAMSAM = $row_SALESMAN_SAMPLE_12_JAM['TOTAL_SALESMAN_SAMPLE_12_JAM'] ?? 0;

                $query_tidak_tercapai_12_jam_libur_SAM = "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI_LIBUR
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and (no_order like 'SAM%' OR no_order like 'SME%')
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 12 or durasi_jam is null)
                            and hari_ke = 1 -- 1: Minggu saja
                    ) as t";

                $result_tidak_tercapai_12_jam_libur_SAM = mysqli_query($conb, $query_tidak_tercapai_12_jam_libur_SAM);
                $row_tidak_tercapai_12_jam_libur_SAM = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_libur_SAM);
                $total_tidak_tercapai_12_jam_libur_SAM = $row_tidak_tercapai_12_jam_libur_SAM['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
				
				$query_tidak_tercapai_12_jam_SAM = "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and (no_order like 'SAM%' OR no_order like 'SME%')
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 12 or durasi_jam is null)
                            and hari_ke = 1 -- 1: Minggu saja
                    ) as t";

                $result_tidak_tercapai_12_jam_SAM = mysqli_query($conb, $query_tidak_tercapai_12_jam_SAM);
                $row_tidak_tercapai_12_jam_SAM = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_SAM);
                $total_tidak_tercapai_12_jam_SAM = $row_tidak_tercapai_12_jam_SAM['TOTAL_TIDAK_TERCAPAI'] ?? 0;
                ?>
              <td align="center"><?= htmlspecialchars($total_SALESMAN_SAMPLE_12_JAMSAM); ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_libur_SAM); ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_SAM); ?></td>
              <td align="center"><?php
				if ($total_SALESMAN_SAMPLE - ($total_SALESMAN_SAMPLE_12_JAMSAM + $total_tidak_tercapai_12_jam_libur_SAM + $total_tidak_tercapai_12_jam_SAM) > 0) {
					$persentase_sisa_kartu_SAM1 = (($total_SALESMAN_SAMPLE - ($total_SALESMAN_SAMPLE_12_JAMSAM + $total_tidak_tercapai_12_jam_libur_SAM + $total_tidak_tercapai_12_jam_SAM)) / $total_SALESMAN_SAMPLE) * 100;
					echo number_format($persentase_sisa_kartu_SAM1, 2) . '%';
				} else {
					echo '0.00%';
				}
				?></td>
              <td align="center"><?php
                    if ($total_tidak_tercapai_12_jam_libur_SAM > 0) {
                        $persentase_tidak_tercapai_proses_SAM = ($total_tidak_tercapai_12_jam_libur_SAM / ($total_SALESMAN_SAMPLE_12_JAMSAM+$total_tidak_tercapai_12_jam_libur_SAM+$total_tidak_tercapai_12_jam_SAM)) * 100;
                        echo number_format($persentase_tidak_tercapai_proses_SAM, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <!-- pencapaian -->
              <td align="center"><?php
                    if ($total_tidak_tercapai_12_jam_SAM > 0) {
                        $percentage_sample = ($total_tidak_tercapai_12_jam_SAM / ($total_SALESMAN_SAMPLE_12_JAMSAM+$total_tidak_tercapai_12_jam_libur_SAM+$total_tidak_tercapai_12_jam_SAM)) * 100;
                        echo number_format($percentage_sample, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_SALESMAN_SAMPLE_12_JAMSAM > 0) {
                        $percentage_sample_tercapai = ($total_SALESMAN_SAMPLE_12_JAMSAM / ($total_SALESMAN_SAMPLE_12_JAMSAM+$total_tidak_tercapai_12_jam_libur_SAM+$total_tidak_tercapai_12_jam_SAM)) * 100;
                        echo number_format($percentage_sample_tercapai, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
            </tr>
            <tr>
              <td><strong>DEVELOPMENT SAMPLE</strong></td>
              <td>12 Jam</td>
              <?php
                $query_DEVELOPMENT_SAMPLE =
                    "SELECT COUNT(*) AS TOTAL_DEVELOPMENT_SAMPLE
                        FROM (
                            SELECT DISTINCT
							    d.CODE,
								p.PRODUCTIONORDERCODE,
								p2.PRODUCTIONORDERCODE,
								p.OPERATIONCODE
                            FROM
                                PRODUCTIONPROGRESS p
                            LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                            LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                            WHERE
                                TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                AND p.PROGRESSTEMPLATECODE = 'S01'
                                AND ( d.DLVSALORDLINESALORDCNTCODE IN (
                                    'DEVBP', 'DEVINDGA', 'DEVINDGB', 'DEVINDGC', 'DEVINDGD', 'DEVINDMA', 'DEVINDMB', 
                                    'DEVINDMC', 'DEVINDMD', 'DEVINDME', 'DEVINDMF', 'DEVINDMG', 'DEVINDMH', 'DEVINDMI', 
                                    'DEVINDTA', 'DEVINDTB', 'TAS', 'TR', 'TS', 'S'
                                ) OR NOT d.PROJECTCODE IS NULL)								
                                AND p.OPERATIONCODE IN (
                                    'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                                    'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                                    'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                                )
                        ) AS t;
                    ";

                $result_DEVELOPMENT_SAMPLE = db2_exec($conn2, $query_DEVELOPMENT_SAMPLE);
                $row_DEVELOPMENT_SAMPLE = db2_fetch_assoc($result_DEVELOPMENT_SAMPLE);
                $total_DEVELOPMENT_SAMPLE = $row_DEVELOPMENT_SAMPLE['TOTAL_DEVELOPMENT_SAMPLE'] ?? 0;

                ?>
              <td align="center"><?= htmlspecialchars($total_DEVELOPMENT_SAMPLE); ?></td>
              <?php
                $query = "select
                                COUNT(*) as total_count
                            from
                                (
                            with ranked_data as (
                                select
                                    *,
                                    row_number() over (partition by id
                                order by
                                    tgl_buat desc) as rn,
                                    TIMESTAMPDIFF(hour,
                                        CONCAT(tgl_proses_in, ' ', jam_in),
                                        CONCAT(tgl_proses_out, ' ', jam_out)
                                    ) as durasi_jam
                                from
                                    tbl_produksi
                                where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and (no_order like 'BP%' OR no_order like 'TS%' OR no_order like 'S%' OR no_order like 'TR%' OR no_order like 'DG%')
                            )
                            select
                                *
                            from
                                ranked_data
                            where
                                rn = 1
                                and durasi_jam <= 12) as t";
                                
                $result = mysqli_query($conb, $query);
                $row = mysqli_fetch_assoc($result);
                $total_tercapai_12_jamBP = $row['total_count'] ?? 0;
				
                $query_tidak_tercapai_12_jam_hari_libur_BP = "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI_LIBUR
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and (no_order like 'BP%' OR no_order like 'TS%' OR no_order like 'S%' OR no_order like 'TR%' OR no_order like 'DG%')
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 12 or durasi_jam is null)
                            and hari_ke = 1 -- 1: Minggu saja
                    ) as t";

                $result_tidak_tercapai_12_jam_hari_libur_BP = mysqli_query($conb, $query_tidak_tercapai_12_jam_hari_libur_BP);
                $row_tidak_tercapai_12_jam_hari_libur_BP = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_hari_libur_BP);
                $total_tidak_tercapai_12_jam_hari_libur_BP = $row_tidak_tercapai_12_jam_hari_libur_BP['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
				
				$query_tidak_tercapai_12_jam_hari_BP = "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and (no_order like 'BP%' OR no_order like 'TS%' OR no_order like 'S%' OR no_order like 'TR%' OR no_order like 'DG%')
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 12 or durasi_jam is null)
                            and hari_ke > 1 -- 1: Minggu saja
                    ) as t";

                $result_tidak_tercapai_12_jam_hari_BP = mysqli_query($conb, $query_tidak_tercapai_12_jam_hari_BP);
                $row_tidak_tercapai_12_jam_hari_BP = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_hari_BP);
                $total_tidak_tercapai_12_jam_hari_BP = $row_tidak_tercapai_12_jam_hari_BP['TOTAL_TIDAK_TERCAPAI'] ?? 0;
                ?>
              <td align="center"><?= htmlspecialchars($total_tercapai_12_jamBP); ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_hari_libur_BP); ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_hari_BP); ?></td>
              <td align="center"><?php
                    if ($total_DEVELOPMENT_SAMPLE-($total_tercapai_12_jamBP+$total_tidak_tercapai_12_jam_hari_libur_BP+$total_tidak_tercapai_12_jam_hari_BP) > 0) {
                        $persentase_sisa_kartu_BP = (($total_DEVELOPMENT_SAMPLE-($total_tercapai_12_jamBP+$total_tidak_tercapai_12_jam_hari_libur_BP+$total_tidak_tercapai_12_jam_hari_BP)) / $total_DEVELOPMENT_SAMPLE) * 100;
                        echo number_format($persentase_sisa_kartu_BP, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_tidak_tercapai_12_jam_hari_libur_BP > 0) {
                        $persentase_tidak_tercapai_proses_BP = ($total_tidak_tercapai_12_jam_hari_libur_BP / ($total_tercapai_12_jamBP+$total_tidak_tercapai_12_jam_hari_libur_BP+$total_tidak_tercapai_12_jam_hari_BP)) * 100;
                        echo number_format($persentase_tidak_tercapai_proses_BP, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <!-- pencapaian -->
              <td align="center"><?php
                    if ($total_tidak_tercapai_12_jam_hari_BP > 0) {
                        $percentageDS = ($total_tidak_tercapai_12_jam_hari_BP / ($total_tercapai_12_jamBP+$total_tidak_tercapai_12_jam_hari_libur_BP+$total_tidak_tercapai_12_jam_hari_BP)) * 100;
                        echo number_format($percentageDS, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_tercapai_12_jamBP > 0) {
                        $percentageDS_tercapai = ($total_tercapai_12_jamBP / ($total_tercapai_12_jamBP+$total_tidak_tercapai_12_jam_hari_libur_BP+$total_tidak_tercapai_12_jam_hari_BP)) * 100;
                        echo number_format($percentageDS_tercapai, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
            </tr>
            <tr>
              <td><strong>RETURN</strong></td>
              <td>12 Jam</td>
              <?php
                $query_RETURN =
                    "SELECT COUNT(*) AS TOTAL_RETURN
                        FROM (
                            SELECT DISTINCT
							    d.CODE,
								p.PRODUCTIONORDERCODE,
								p2.PRODUCTIONORDERCODE,
								p.OPERATIONCODE
                            FROM
                                PRODUCTIONPROGRESS p
                            LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                            LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                            WHERE
                                TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                AND p.PROGRESSTEMPLATECODE = 'S01'
                                AND d.DLVSALORDLINESALORDCNTCODE IN ('RETRNFAB','RETRNEXP')
                                AND p.OPERATIONCODE IN (
                                    'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                                    'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                                    'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                                )
                        ) AS t;
                    ";

                $result_RETURN = db2_exec($conn2, $query_RETURN);
                $row_RETURN = db2_fetch_assoc($result_RETURN);
                $total_RETURN = $row_RETURN['TOTAL_RETURN'] ?? 0;
                ?>
              <td align="center"><?= htmlspecialchars($total_RETURN); ?></td>
              <?php
                $query = "select
                                COUNT(*) as total_count
                            from
                                (
                            with ranked_data as (
                                select
                                    *,
                                    row_number() over (partition by id
                                order by
                                    tgl_buat desc) as rn,
                                    TIMESTAMPDIFF(hour,
                                        CONCAT(tgl_proses_in, ' ', jam_in),
                                        CONCAT(tgl_proses_out, ' ', jam_out)
                                    ) as durasi_jam
                                from
                                    tbl_produksi
                                where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and (no_order like 'RET%' or no_order like 'RFD%')
                            )
                            select
                                *
                            from
                                ranked_data
                            where
                                rn = 1
                                and durasi_jam <= 12) as t";
                $result = mysqli_query($conb, $query);
                $row = mysqli_fetch_assoc($result);
                $total_tercapai_12_jamRET = $row['total_count'] ?? 0;

                $query_tidak_tercapai_12_jam_hari_libur_RET = "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI_LIBUR
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and (no_order like 'RET%' or no_order like 'RFD%')
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 12 or durasi_jam is null)
                            and hari_ke = 1 -- 1: Minggu saja
                    ) as t";
                $result_tidak_tercapai_12_jam_hari_libur_RET = mysqli_query($conb, $query_tidak_tercapai_12_jam_hari_libur_RET);
                $row_tidak_tercapai_12_jam_hari_libur_RET = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_hari_libur_RET);
                $total_tidak_tercapai_12_jam_hari_libur_RET = $row_tidak_tercapai_12_jam_hari_libur_RET['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
				
				$query_tidak_tercapai_12_jam_hari_RET = "select
                        COUNT(*) as TOTAL_TIDAK_TERCAPAI
                    from
                    (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by id order by tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam,
                                DAYOFWEEK(tgl_buat) as hari_ke
                            from
                                tbl_produksi
                            where
                                    tgl_buat >= '$tglAwal_tbl4'
                                    and tgl_buat < '$tglAkhir_tbl4'
                                    and (no_order like 'RET%' or no_order like 'RFD%')
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and (durasi_jam > 12 or durasi_jam is null)
                            and hari_ke > 1 -- 1: Minggu saja
                    ) as t";
                $result_tidak_tercapai_12_jam_hari_RET = mysqli_query($conb, $query_tidak_tercapai_12_jam_hari_RET);
                $row_tidak_tercapai_12_jam_hari_RET = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_hari_RET);
                $total_tidak_tercapai_12_jam_hari_RET = $row_tidak_tercapai_12_jam_hari_RET['TOTAL_TIDAK_TERCAPAI'] ?? 0;
				
                ?>
              <td align="center"><?= htmlspecialchars($total_tercapai_12_jamRET); ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_hari_libur_RET); ?></td>
              <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_hari_RET); ?></td>
              <td align="center"><?php
                    if ($total_RETURN-($total_tercapai_12_jamRET+$total_tidak_tercapai_12_jam_hari_libur_RET+$total_tidak_tercapai_12_jam_hari_RET) > 0) {
                        $persentase_sisa_kartu_RET = (($total_RETURN-($total_tercapai_12_jamRET+$total_tidak_tercapai_12_jam_hari_libur_RET+$total_tidak_tercapai_12_jam_hari_RET)) / $total_RETURN) * 100;
                        echo number_format($persentase_sisa_kartu_RET, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_tidak_tercapai_12_jam_hari_libur_RET > 0) {
                        $persentase_tidak_tercapai_prosesRET = ($total_tidak_tercapai_12_jam_hari_libur_RET / ($total_tercapai_12_jamRET+$total_tidak_tercapai_12_jam_hari_libur_RET+$total_tidak_tercapai_12_jam_hari_RET)) * 100;
                        echo number_format($persentase_tidak_tercapai_prosesRET, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <!-- pencapaian -->
              <td align="center"><?php
                    if ($total_tidak_tercapai_12_jam_hari_RET > 0) {
                        $percentage_RETURN = ($total_tidak_tercapai_12_jam_hari_RET / ($total_tercapai_12_jamRET+$total_tidak_tercapai_12_jam_hari_libur_RET+$total_tidak_tercapai_12_jam_hari_RET)) * 100;
                        echo number_format($percentage_RETURN, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
              <td align="center"><?php
                    if ($total_tercapai_12_jamRET > 0) {
                        $percentage_RETURN_tercapai = ($total_tercapai_12_jamRET / ($total_tercapai_12_jamRET+$total_tidak_tercapai_12_jam_hari_libur_RET+$total_tidak_tercapai_12_jam_hari_RET)) * 100;
                        echo number_format($percentage_RETURN_tercapai, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
            </tr>
            <tr style="font-weight:bold; background-color:yellow;">
                <td align="center">TOTAL</td>
                <td>&nbsp;</td>
                <td align="center"><?php
                    $total_kartu_kerja = $total_KARTU_KERJA_BIASA + $total_GANTI_KAIN_EKSTERNAL + $total_MINI_BULK + $total_SALESMAN_SAMPLE + $total_DEVELOPMENT_SAMPLE + $total_RETURN;
                    echo htmlspecialchars($total_kartu_kerja);
                    ?></td>
                <td align="center"><?php
                    $total_tercapai = $total_tercapai_30_jamDOM + $total_tercapai_12_jamRET + $total_tercapai_12_jamREP + $total_tercapai_12_jamMBE + $total_SALESMAN_SAMPLE_12_JAMSAM + $total_tercapai_12_jamBP;
                    echo htmlspecialchars($total_tercapai);
                    ?></td>
                <td align="center"><?php
                    $total_tidak_tercapai_hari_libur = $total_tidak_tercapai_30_jam_libur+$total_tidak_tercapai_12_jam_liburREP+$total_tidak_tercapai_12_jam_hari_liburMBE+$total_tidak_tercapai_12_jam_libur_SAM+$total_tidak_tercapai_12_jam_hari_libur_BP+$total_tidak_tercapai_12_jam_hari_libur_RET;
                    echo htmlspecialchars($total_tidak_tercapai_hari_libur);
                    ?></td>
                <td align="center"><?php
                    $total_tidak_tercapai = $total_tidak_tercapai_30_jam+$total_tidak_tercapai_12_jamREP+$total_tidak_tercapai_12_jam_hariMBE+$total_tidak_tercapai_12_jam_SAM+$total_tidak_tercapai_12_jam_hari_BP+$total_tidak_tercapai_12_jam_hari_RET;
                    echo htmlspecialchars($total_tidak_tercapai);
                    ?></td>
                <td align="center"><?php
                    // $total_kartu_kerja_sisa = $sisakartu_kerja_development_sample + $sisakartu_kerja_salesman_sample + $sisakartu_kerja_return + $sisakartu_kerja_mini_bulk + $sisakartu_kerja_ganti_kain_eksternal + $sisakartu_kerja_biasa;
                    
                     echo htmlspecialchars($total_kartu_kerja_sisa_1);
                    ?></td>
                <td align="center"><?php
                    if ($total_tidak_tercapai_hari_libur > 0) {
                        $persentase_tidak_tercapai_hari_libur_total = ($total_tidak_tercapai_hari_libur / ($total_tercapai+$total_tidak_tercapai_hari_libur+$total_tidak_tercapai)) * 100;
                        echo number_format($persentase_tidak_tercapai_hari_libur_total, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
                <td align="center"><?php
                    if ($total_tidak_tercapai > 0) {
                        $persentase_tidak_tercapai_proses_total = ($total_tidak_tercapai / ($total_tercapai+$total_tidak_tercapai_hari_libur+$total_tidak_tercapai)) * 100;
                        echo number_format($persentase_tidak_tercapai_proses_total, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
                <td align="center"><?php
                    if ($total_tercapai > 0) {
                        $persentase_tercapai_proses_total = ($total_tercapai / ($total_tercapai+$total_tidak_tercapai_hari_libur+$total_tidak_tercapai)) * 100;
                        echo number_format($persentase_tercapai_proses_total, 2) . '%';
                    } else {
                        echo '0.00%';
                    }
                    ?></td>
                <!-- <td align="center">RATA - RATA</td> -->            </tr>
        </table>
    <!-- End Table 5 -->
</body>
</html>	