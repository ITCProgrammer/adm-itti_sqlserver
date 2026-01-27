<?php
     $Awal = isset($_GET['awal']) ? $_GET['awal'] : '';
	 $tgl  = isset($_GET['awal']) ? $_GET['awal'] : '';
	 $today = date('Y-m-d'); 	
     header("Content-type: application/octet-stream");
     header("Content-Disposition: attachment; filename=REPORT-HARIAN-BRS-".$Awal.".xls"); // ganti nama sesuai keperluan
     header("Pragma: no-cache");
     header("Expires: 0");

?>
<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
include "../../helper.php";

function getSisakkData($conn, $tgl_tutup, $operationcode, $tipe = 'all') {
    $stmt = $conn->prepare("
        SELECT demand_count, qty_order
        FROM tbl_sisakk_brs
        WHERE tgl_tutup = ? AND operationcode = ?
        LIMIT 1
    ");

    if (!$stmt) {
        die("Query gagal: " . $conn->error);
    }

    $stmt->bind_param("ss", $tgl_tutup, $operationcode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // jika hanya ingin salah satu nilai
        if ($tipe === 'demand') {
            return (int)$row['demand_count'];
        } elseif ($tipe === 'qty') {
            return (float)$row['qty_order'];
        } else {
            // default: kembalikan array
            return [
                'demand_count' => (int)$row['demand_count'],
                'qty_order'    => (float)$row['qty_order']
            ];
        }
    }

    return null; // data tidak ditemukan
}

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
        $date_end_tbl2 = new DateTime($input. '23:01:00');
        $date_end_tbl2->modify('-1 day');
        $start_formatted = $date_end_tbl2->format('Y-m-d H:i:s');
        $end_formatted = $date_start_tbl2->format('Y-m-d H:i:s');
	
		// Data NCP
        $qry_ncp = "
			SELECT
				SUM(berat) AS qty_ncp,

				SUM(
					CASE 
						WHEN 
							masalah LIKE '%Bulu%' OR
							masalah LIKE '%Permukaan Berbulu%' OR
							masalah LIKE '%Bulu Garukan Reject%' OR
							masalah LIKE '%Bulu Garukan Tipis%' OR
							masalah LIKE '%Bulu Garukan Tebal%' OR
							masalah LIKE '%Bulu Garukan Tidak Rata%' OR
							masalah LIKE '%Bulu Garukan Rontok%' OR
							masalah LIKE '%Botak%' OR
							masalah LIKE '%Bulu Garukan Berbiji%' OR
							masalah LIKE '%Bulu Kain Panjang%' OR
							masalah LIKE '%Tidak Kena Garuk%' OR
							masalah LIKE '%Flamability Reject%' OR
							masalah LIKE '%After Wash Reject%' OR
							masalah LIKE '%Bulkines Reject%' OR
							masalah LIKE '%Permukaan Kain Berbulu%' OR
							masalah LIKE '%Bulu Garukan Pecah%' OR
							masalah LIKE '%Permukaan Reject%' OR
							masalah LIKE '%Serat Bengkok%' OR
							masalah LIKE '%Serat Renggang%' OR
							masalah LIKE '%Strectch Mati%' OR
							masalah LIKE '%Stretch Kurang Elastis%' OR
							masalah LIKE '%Garukan di Muka Kain%' OR
							masalah LIKE '%Horizon%' OR
							masalah LIKE '%Thickness Reject%' OR
							masalah LIKE '%Mengkerut%'
						THEN berat ELSE 0 
					END
				) AS garuk_fleece,

				SUM(
					CASE 
						WHEN 
							masalah LIKE '%Kaitan Jarum%' OR
							masalah LIKE '%Snagging%' OR
							masalah LIKE '%Bekas Tarik-tarik Snagging%' OR
							masalah LIKE '%Serat Pecah%'
						THEN berat ELSE 0 
					END
				) AS garuk_ap,

				SUM(
					CASE 
						WHEN 
							masalah LIKE '%Biji Anti Pilling Reject%' OR
							masalah LIKE '%Biji Anti Pilling Tidak Rata%' OR
							masalah LIKE '%Biji Anti Pilling Besar%' OR
							masalah LIKE '%Biji Anti Pilling Kecil%' OR
							masalah LIKE '%Pilling Reject%' OR
							masalah LIKE '%Appearance Jelek%' OR
							masalah LIKE '%Gesekan%'
						THEN berat ELSE 0 
					END
				) AS oven_ap,

				SUM(
					CASE 
						WHEN 
							masalah LIKE '%Garis Vertikal%' OR
							masalah LIKE '%Garis Diagonal%' OR
							masalah LIKE '%Horizontal%' OR
							masalah LIKE '%Shading (Garukan, Peachskin, Potong Bulu)%' OR
							masalah LIKE '%Rapuh%' OR
							masalah LIKE '%Kena Peach Skin di Terry%' OR
							masalah LIKE '%Beda Warna%'
						THEN berat ELSE 0 
					END
				) AS peach_skin,

				SUM(
					CASE 
						WHEN 
							masalah LIKE '%Kena Potong Shearing%' OR
							masalah LIKE '%Terry Putus%' OR
							masalah LIKE '%Sobek%' OR
							masalah LIKE '%Lebar Reject%' OR
							masalah LIKE '%Lebar Kurang%' OR
							masalah LIKE '%Lebar Lebih%' OR
							masalah LIKE '%Kena Pisau Garuk%'
						THEN berat ELSE 0 
					END
				) AS pb_lain,

				SUM(
					CASE 
						WHEN 
							masalah LIKE '%Kotor Tanah%' OR
							masalah LIKE '%Kontaminasi%' OR
							masalah LIKE '%Pinggiran Kain Kuning%' OR
							masalah LIKE '%Kena Warna%' OR
							masalah LIKE '%Kena Minyak%' OR
							masalah LIKE '%Kotor Kapas%' OR
							masalah LIKE '%Bintik Oksidasi%' OR
							masalah LIKE '%Gosong%' OR
							masalah LIKE '%Belang Steam%' OR
							masalah LIKE '%Belang Yang Hwa%' OR
							masalah LIKE '%Belang Spiral%' OR
							masalah LIKE '%Bercak Steam%' OR
							masalah LIKE '%Bolong%' OR
							masalah LIKE '%Bocor%' OR
							masalah LIKE '%Cabut-Cabut%' OR
							masalah LIKE '%Peach Skin Reject%' OR
							masalah LIKE '%Peachskin Tipis%' OR
							masalah LIKE '%Peachskin Tebal%' OR
							masalah LIKE '%Peachskin Tidak Rata%' OR
							masalah LIKE '%Serat Kayu%' OR
							masalah LIKE '%Tidak Kena Peach Skin%' OR
							masalah LIKE '%Beda Roll%' OR
							masalah LIKE '%Bursting Strength Reject%' OR
							masalah LIKE '%Salah Peach Skin%' OR
							masalah LIKE '%Peached Berbiji%' OR
							masalah LIKE '%Gramasi Ringan%' OR
							masalah LIKE '%Gramasi Berat%' OR
							masalah LIKE '%Cakar Ayam%' OR
							masalah LIKE '%Krismark%' OR
							masalah LIKE '%Garis Lipat%' OR
							masalah LIKE '%Keriput%' OR
							masalah LIKE '%Bekas Lipatan Anti Pilling%' OR
							masalah LIKE '%Gesekan Anti Pilling%' OR
							masalah LIKE '%Kusut%' OR
							masalah LIKE '%Kena Angin%' OR
							masalah LIKE '%Handfeel Reject%' OR
							masalah LIKE '%Handfeel Kurang Soft%' OR
							masalah LIKE '%Luntur%'
						THEN berat ELSE 0 
					END
				) AS oven_ap_lain

			FROM db_qc.tbl_ncp_qcf_now
			WHERE
				[status] IN ('Belum OK', 'OK', 'BS', 'Disposisi')
				AND dept = 'BRS'
				AND ncp_hitung = 'ya'
				AND tgl_buat BETWEEN '$start_formatted' AND '$end_formatted'
			";
        $qry1 = sqlsrv_query($cond, $qry_ncp);
        $row_ncp = sqlsrv_fetch_array($qry1, SQLSRV_FETCH_ASSOC);

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
							FROM db_brushing.tbl_produksi
							WHERE tgl_buat >= '{$startDate->format("Y-m-d")} 23:00:00'
							AND tgl_buat <= '{$endDate->format("Y-m-d")} 23:00:00'
							ORDER BY tgl_cutoff ASC
						";
				$result = sqlsrv_query($conb, $query);

				// Array tanggal yang punya data
				$tanggal_ada_data = [];
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					// sqlsrv bisa return DateTime object
					$tgl_cutoff = ($row['tgl_cutoff'] instanceof DateTime)
						? $row['tgl_cutoff']->format('Y-m-d')
						: $row['tgl_cutoff'];

					$tanggal_ada_data[cekTanggal($tgl_cutoff,'Y-m-d')] = true;
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
											SUM(CASE WHEN no_mesin IN ('01', '02', '03', '04', '05', '06', '07', '08', '09') AND nama_mesin IN ('Garuk A', 'Garuk B', 'Garuk C', 'Garuk D', 'Garuk E', 'Garuk F') and proses = 'GARUK ANTI PILLING (Normal)' THEN qty ELSE 0 END) AS garuk_ap,
											SUM(CASE WHEN no_mesin IN ('01', '02', '03', '04', '05', '06', '07', '08', '09') AND nama_mesin IN ('Garuk A', 'Garuk B', 'Garuk C', 'Garuk D', 'Garuk E', 'Garuk F') and proses in('GARUK FLEECE (Normal)', 'GARUK SLIGHT BRUSH (Normal)', 'GARUK SLIGHTLY BRUS (Normal)', 'GARUK GREIGE (Normal)', 'GARUK BANTU - DYG (Bantu)', 'GARUK BANTU - FIN (Bantu)', 'GARUK GREIGE (Bantu)', 'GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS garuk_fleece,
											SUM(CASE WHEN no_mesin IN ('01', '02', '03', '04', '05', '06', '07', '08') AND nama_mesin IN ('Potong Bulu') and proses IN ('POTONG BULU FLEECE (Normal)', 'GARUK FLEECE (Normal)', 'GARUK FLEECE (Normal)', 'GARUK SLIGHT BRUSH (Normal)', 'GARUK SLIGHTLY BRUS (Normal)') THEN qty ELSE 0 END) AS potong_bulu_fleece,
											SUM(CASE WHEN no_mesin IN ('01') AND nama_mesin IN ('Sisir') and proses IN ('SISIR ANTI PILLING (Normal)', 'SISIR BANTU (FIN) (Bantu)', 'SISIR LAIN-LAIN (Bantu)', 'GARUK ANTI PILLING (Normal)') THEN qty ELSE 0 END) AS sisir_ap,
											SUM(CASE WHEN no_mesin IN ('01', '02', '03', '04', '05', '06', '07', '08') AND nama_mesin IN ('Potong Bulu') and proses IN ('POTONG BULU ANTI PILLING (Normal)', 'GARUK ANTI PILLING (Normal)', 'SISIR ANTI PILLING (Normal)', 'ANTI PILLING (Khusus)', 'ANTI PILLING NORMAL (Normal)', 'ANTI PILLING (Normal)', 'ANTI PILLING BIASA (Normal)') THEN qty ELSE 0 END) AS pbulu_ap,
											SUM(CASE WHEN no_mesin IN ('01', '02', '03', '04') AND nama_mesin IN ('Anti Pilling') and proses IN ('ANTI PILLING (Khusus)', 'ANTI PILLING (Normal)', 'ANTI PILLING NORMAL (Normal)', 'ANTI PILLING BIASA (Normal)', 'ANTI PILLING (BIASA) (Normal)', 'GARUK ANTI PILLING (Normal)', 'SISIR ANTI PILLING (Normal)', 'POTONG BULU ANTI PILLING (Normal)', 'PEACH SKIN (Normal)', 'POTONG BULU PEACH SKIN (Normal)', 'POTONG BULU FLEECE (Normal)') THEN qty ELSE 0 END) AS oven_ap,
											SUM(CASE WHEN no_mesin IN ('01', '02', '03', '04', '05', '06') AND nama_mesin IN ('Peach Skin') and proses IN ('PEACH SKIN (Normal)', 'PEACHSKIN GREIGE (Normal)', 'PEACH BANTU TAS (Bantu)', 'PEACH SKIN (Bantu)', 'PEACH SKIN BANTU - DYE (Bantu)', 'PEACH SKIN BANTU - FIN (Bantu)', 'POTONG BULU PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS peach,
											SUM(CASE WHEN no_mesin IN ('01', '02', '03', '04', '05', '06', '07', '08') AND nama_mesin IN ('Potong Bulu') and proses IN ('POTONG BULU PEACH SKIN (Normal)', 'PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS pb_peach,
											SUM(CASE WHEN no_mesin IN ('01', '02') AND nama_mesin IN ('Airo') and proses = 'AIRO (Normal)' THEN qty ELSE 0 END) AS airo,
											SUM(CASE WHEN no_mesin IN ('01', '02', '03', '04', '05', '06', '07', '08') AND nama_mesin IN ('Potong Bulu') and proses IN ('Potong Bulu (Bantu)', 'POTONG BULU 07 (Bantu)', 'POTONG BULU LAIN-LAIN (Bantu)', 'POTONG BULU LAIN-LAIN (Khusus)', 'POTONG BULU BACK BANTU-DYEING (Bantu)', 'POTONG BULU BACK BANTU-FIN (Bantu)', 'POTONG BULU BACK TAS BANTU (Bantu)', 'POTONG BULU FACE BANTU-DYEING (Bantu)', 'POTONG BULU FACE BANTU-FIN (Bantu)', 'POTONG BULU FACE BANTU-TAS (Bantu)', 'POTONG BULU FACE TAS BANTU (Bantu)', 'POTONG BULU GREIGE (Bantu)', 'POTONG BULU GREIGE (Normal)', 'PEACH BANTU TAS (Bantu)', 'PEACH SKIN (Bantu)', 'PEACH SKIN BANTU - DYE (Bantu)',
																	'PEACH SKIN BANTU - FIN (Bantu)', 'GARUK BANTU - DYG (Bantu)', 'GARUK BANTU - FIN (Bantu)', 'GARUK GREIGE (Bantu)', 'GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS pb_lain,
											SUM(CASE WHEN no_mesin IN ('01', '02', '03', '04') AND nama_mesin IN ('Anti Pilling') and proses IN ('ANTI PILLING BANTU - DYE (Bantu)',
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
											SUM(CASE WHEN no_mesin IN ('01') AND nama_mesin IN ('Polishing') and proses = 'POLISHING (Normal)' THEN qty ELSE 0 END) AS polish,
											SUM(CASE WHEN (proses LIKE '%bantu%' OR proses LIKE '%NCP%') THEN qty ELSE 0 END) AS lain,
											SUM(CASE WHEN no_mesin IN ('01') AND nama_mesin IN ('Wet Sueding') and proses IN ('WET SUEDING (Normal)', 'WET SUEDING FINISHED BACK (Normal)',
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
																	'ANTI PILLING LAIN-LAIN KHUSUS-DYE (Ulang)', 'ANTI PILLING LAIN-LAIN KHUSUS-BRS (Ulang)', 'ANTI PILLING LAIN-LAIN KHUSUS-CQA (Ulang)') THEN qty ELSE 0 END) AS bantu,
											SUM(CASE WHEN proses LIKE '%(Bantu)%' THEN qty ELSE 0 END) AS produksi_ulang,
											count(distinct nodemand) as total_kk
										FROM
											db_brushing.tbl_produksi tp
										WHERE
											tp.tgl_buat between '$start_time' and '$end_time'";
							$stmt_qry = sqlsrv_query($conb, $query_table1);
							$data_table1 = sqlsrv_fetch_array($stmt_qry, SQLSRV_FETCH_ASSOC);
                            // echo $start_time;
                        // Hari kerja
                            $hari_kerja_query = "SELECT TOP 1 1 
										FROM db_brushing.tbl_produksi
										WHERE tgl_buat >= '$start_time'
										AND tgl_buat < '$end_time'
									";

									$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);

									$hari_kerja_result = sqlsrv_query($conb, $hari_kerja_query, [], $options);

									if ($hari_kerja_result === false) {
										print_r(sqlsrv_errors());
										die;
									}

									$hari_kerja = sqlsrv_num_rows($hari_kerja_result) > 0 ? '1' : '0';
                            echo "<td align='center'>{$hari_kerja}</td>";
                            $totalHariKerja += $hari_kerja; // Tambahkan ke total hari kerja
                        // Hari kerja
                        						
						// Looping table 2
							$query_tbl2 = "SELECT
								SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_fleece_ulang,
								SUM(CASE WHEN proses = 'GARUK ANTI PILLING-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_ap_ulang,
								SUM(CASE WHEN proses IN('PEACHSKIN ULANG-BRS (Ulang)', 'PEACHSKIN GREIGE (Ulang)') THEN qty ELSE 0 END) AS brs_peach_ulang,
								SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_pb_ulang,
								SUM(CASE WHEN no_mesin IN ('01','02','03','04') AND nama_mesin IN ('Anti Pilling') and proses = 'ANTI PILLING LAIN-LAIN KHUSUS-BRS (Ulang)' THEN qty ELSE 0 END) AS brs_oven_ulang,
								SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_fleece_ulang,
								SUM(CASE WHEN proses = 'GARUK ANTI PILLING-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_ap_ulang,
								SUM(CASE WHEN proses = 'PEACHSKIN ULANG-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_peach_ulang,
								SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_pb_ulang,
								SUM(CASE WHEN no_mesin IN ('01','02','03','04') AND nama_mesin IN ('Anti Pilling') and proses = 'ANTI PILLING LAIN-LAIN KHUSUS-FIN (Ulang)' THEN qty ELSE 0 END) AS fin_oven_ulang,
								SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_fleece_ulang,
								SUM(CASE WHEN proses = 'GARUK ANTI PILLING-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_ap_ulang,
								SUM(CASE WHEN proses = 'PEACHSKIN ULANG-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_peach_ulang,
								SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_pb_ulang,
								SUM(CASE WHEN no_mesin IN ('01','02','03','04') AND nama_mesin IN ('Anti Pilling') and proses = 'ANTI PILLING LAIN-LAIN KHUSUS-DYE (Ulang)' THEN qty ELSE 0 END) AS dye_oven_ulang,
								SUM(CASE WHEN proses = 'GARUK FLEECE ULANG-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_fleece_ulang,
								SUM(CASE WHEN proses = 'GARUK ANTI PILLING-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_ap_ulang,
								SUM(CASE WHEN proses = 'PEACHSKIN ULANG-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_peach_ulang,
								SUM(CASE WHEN proses = 'POTONG BULU LAIN-LAIN KHUSUS-CQA (Ulang)' THEN qty ELSE 0 END) AS cqa_pb_ulang,
								SUM(CASE WHEN no_mesin IN ('01','02','03','04') AND nama_mesin IN ('Anti Pilling') and proses IN('ANTI PILLING LAIN-LAIN KHUSUS-CQA (Ulang)','ANTI PILLING LAIN-LAIN-CQA (Ulang)') THEN qty ELSE 0 END) AS cqa_oven_ulang
							FROM
								db_brushing.tbl_produksi
							WHERE
								tgl_buat between'$start_time' and '$end_time'";
							$stmt_tbl2 = sqlsrv_query($conb, $query_tbl2);
							$row_tbl2 = sqlsrv_fetch_array($stmt_tbl2, SQLSRV_FETCH_ASSOC);
							// $cek_tbl2 = sqlsrv_num_rows($stmt_tbl2);
						// End
						// Jumlah KK perbaikan
						
							$query_kkmasuk = "WITH base AS (
												SELECT 
													pd.CODE AS DEMANDNO,
													p.PRODUCTIONORDERCODE,
													p.GROUPSTEPNUMBER, 
													p.OPERATIONCODE, 
													m.TOTALPRIMARYQUANTITY,
													ROW_NUMBER() OVER (
														PARTITION BY pd.CODE 
														ORDER BY p.GROUPSTEPNUMBER ASC
													) AS rn_demand
												FROM PRODUCTIONPROGRESS p
												LEFT JOIN PRODUCTIONORDER m 
													ON m.CODE = p.PRODUCTIONORDERCODE
												LEFT JOIN PRODUCTIONRESERVATION pr 
													ON m.COMPANYCODE = pr.COMPANYCODE
													AND m.CODE = pr.PRODUCTIONORDERCODE
												LEFT JOIN PRODUCTIONDEMAND pd 
													ON pr.COMPANYCODE = pd.COMPANYCODE
													AND pr.ORDERCODE = pd.CODE
												WHERE
													TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) 
														BETWEEN TIMESTAMP('$start_time') AND TIMESTAMP('$end_time')
													AND p.OPERATIONCODE IN (
														'RSE1','RSE2','RSE3','RSE4','RSE5',
														'COM1','COM2','SHR1','SHR2','SHR3','SHR4','SHR5',
														'TDR1','SUE1','SUE2','SUE3','SUE4',
														'AIR1','POL1','WET1','WET2','WET3','WET4'
													)
													AND p.PROGRESSTEMPLATECODE = 'S01'
											),
											ranked AS (
												SELECT 
													b.*,
													ROW_NUMBER() OVER (
														PARTITION BY b.OPERATIONCODE, b.PRODUCTIONORDERCODE
														ORDER BY b.GROUPSTEPNUMBER, b.DEMANDNO
													) AS qty_row
												FROM base b
												WHERE b.rn_demand = 1
											),
											valid AS (
												SELECT 
													r.PRODUCTIONORDERCODE, 
													r.DEMANDNO, 
													r.OPERATIONCODE, 
													CASE 
														WHEN r.qty_row = 1 THEN r.TOTALPRIMARYQUANTITY 
														ELSE 0
													END AS TOTALPRIMARYQUANTITY
												FROM ranked r
												WHERE EXISTS (
													SELECT 1
													FROM PRODUCTIONDEMANDSTEP ds
													WHERE ds.PRODUCTIONORDERCODE = r.PRODUCTIONORDERCODE
													  AND ds.PLANNEDOPERATIONCODE = r.OPERATIONCODE
													  AND NOT EXISTS (
														  SELECT 1
														  FROM PRODUCTIONDEMANDSTEP ds_prev
														  WHERE ds_prev.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
															AND ds_prev.PLANNEDOPERATIONCODE IN (
																'RSE1','RSE2','RSE3','RSE4','RSE5','COM1','COM2',
																'SHR1','SHR2','SHR3','SHR4','SHR5','TDR1',
																'SUE1','SUE2','SUE3','SUE4','AIR1','POL1',
																'WET1','WET2','WET3','WET4'
															)
															AND ds_prev.STEPNUMBER = (
																SELECT MAX(ds2.STEPNUMBER)
																FROM PRODUCTIONDEMANDSTEP ds2
																WHERE ds2.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
																  AND ds2.STEPNUMBER < ds.STEPNUMBER
															)
													  )
												)
											)
											SELECT  
												COUNT(DISTINCT CASE WHEN OPERATIONCODE IN (
																'RSE1','RSE2','RSE3','RSE4','RSE5','COM1','COM2',
																'SHR1','SHR2','SHR3','SHR4','SHR5','TDR1',
																'SUE1','SUE2','SUE3','SUE4','AIR1','POL1',
																'WET1','WET2','WET3','WET4') THEN DEMANDNO END) AS KK_DEMAND
											FROM valid;";
                            $result_kkmasuk = db2_exec($conn2, $query_kkmasuk);
                            $row_kkmasuk = db2_fetch_assoc($result_kkmasuk);	
						
						// Jumlah KK perbaikan	
							
						// Jumlah KK
							//$total_kk = $data_table1['total_kk'];
                            $total_kk = $row_kkmasuk['KK_DEMAND'];
                            $display_kk = ($total_kk != 0) ? $total_kk : '-';
                            echo "<td align='center'>$display_kk</td>";

                            // Hanya tambahkan angka ke total jika nilainya tidak nol
                            if ($total_kk != 0) {
                                $totalJumlahKK += $total_kk;
                            }
                        // Jumlah KK

                        // Garuk Fleece
                        if($tanggal==$input){
                                $qty_fleece = $data_table1['garuk_fleece'] - ($row_tbl2['brs_fleece_ulang'] + $row_tbl2['fin_fleece_ulang'] + $row_tbl2['dye_fleece_ulang'] + $row_tbl2['cqa_fleece_ulang']); 
                                $display_fleece = ($qty_fleece != 0) ? $qty_fleece : '-';
                                echo "<td align='center'>{$display_fleece}</td>";
                                if ($qty_fleece != 0) {
                                $total_garuk_fleece += $qty_fleece;
                            }
                        }else{
                            $qty_fleece = $data_table1['garuk_fleece'] - ($row_tbl2['brs_fleece_ulang'] + $row_tbl2['fin_fleece_ulang'] + $row_tbl2['dye_fleece_ulang'] + $row_tbl2['cqa_fleece_ulang']);
                            $display_fleece = ($qty_fleece != 0) ? $qty_fleece : '-';
                            echo "<td align='center'>{$display_fleece}</td>";
                            if ($qty_fleece != 0) {
                                $total_garuk_fleece += $qty_fleece;
                            }   
                        }
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
                        if($tanggal==$input){
                                $qty_ap = $data_table1['garuk_ap'] - ($row_tbl2['brs_ap_ulang'] + $row_tbl2['fin_ap_ulang'] + $row_tbl2['dye_ap_ulang'] + $row_tbl2['cqa_ap_ulang']); 
                                $display_ap = ($qty_ap != 0) ? $qty_ap : '-';
                                echo "<td align='center'>{$display_ap}</td>";
                                if ($qty_ap != 0) {
                                $total_garuk_anti_pilling += $qty_ap;
                            }
                        }else{
                            $qty_ap = $data_table1['garuk_ap'] - ($row_tbl2['brs_ap_ulang'] + $row_tbl2['fin_ap_ulang'] + $row_tbl2['dye_ap_ulang'] + $row_tbl2['cqa_ap_ulang']);
                            $display_ap = ($qty_ap != 0) ? $qty_ap : '-';
                            echo "<td align='center'>{$display_ap}</td>";
                            if ($qty_ap != 0) {
                                $total_garuk_anti_pilling += $qty_ap;
                            }   
                        }

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
                            if($tanggal==$input){
                                $peach_skin_row = $data_table1['peach'] - ($row_tbl2['brs_peach_ulang'] + $row_tbl2['fin_peach_ulang'] + $row_tbl2['dye_peach_ulang'] + $row_tbl2['cqa_peach_ulang']); 
                                $display_peach = ($peach_skin_row != 0) ? $peach_skin_row : '-';
                                echo "<td align='center'>{$display_peach}</td>";
                                if ($peach_skin_row != 0) {
                                $total_peach_skin += $peach_skin_row;
                            }
                                }else{
                                    $peach_skin_row = $data_table1['peach'] - ($row_tbl2['brs_peach_ulang'] + $row_tbl2['fin_peach_ulang'] + $row_tbl2['dye_peach_ulang'] + $row_tbl2['cqa_peach_ulang']);
                                    $display_peach = ($peach_skin_row != 0) ? $peach_skin_row : '-';
                                    echo "<td align='center'>{$display_peach}</td>";
                                    if ($peach_skin_row != 0) {
                                        $total_peach_skin += $peach_skin_row;
                                    }   
                                }
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
                        if($tanggal==$input){
                                $potong_bulu_lain_lain_row = $data_table1['pb_lain'] - ($row_tbl2['brs_pb_ulang'] + $row_tbl2['fin_pb_ulang'] + $row_tbl2['dye_pb_ulang'] + $row_tbl2['cqa_pb_ulang']); 
                                $display_pb = ($potong_bulu_lain_lain_row != 0) ? $potong_bulu_lain_lain_row : '-';
                                echo "<td align='center'>{$display_pb}</td>";
                                if ($potong_bulu_lain_lain_row != 0) {
                                $total_potong_bulu_lain_lain += $potong_bulu_lain_lain_row;   
                            }
                                }else{
                                    $potong_bulu_lain_lain_row = $data_table1['pb_lain'] - ($row_tbl2['brs_pb_ulang'] + $row_tbl2['fin_pb_ulang'] + $row_tbl2['dye_pb_ulang'] + $row_tbl2['cqa_pb_ulang']);
                                    $display_pb = ($potong_bulu_lain_lain_row != 0) ? $potong_bulu_lain_lain_row : '-';
                                    echo "<td align='center'>{$display_pb}</td>";
                                    if ($potong_bulu_lain_lain_row != 0) {
                                        $total_potong_bulu_lain_lain += $potong_bulu_lain_lain_row;
                                    }   
                                 }


                            // $potong_bulu_lain_lain_row = ($data_table1['pb_lain']!=0) ? $data_table1['pb_lain'] : '-';
                            // echo "<td align='center'>{$potong_bulu_lain_lain_row}</td>";
                            //     $total_potong_bulu_lain_lain += ($potong_bulu_lain_lain_row === "-") ? 0 : $potong_bulu_lain_lain_row; //kalau 0 nilainya strip
                        // Potong Bulu Lain-Lain

                        // Oven Anti Pilling Lain-Lain
                        if($tanggal==$input){
                                $anti_pilling_lain_lain_row = $data_table1['ap_lain'] - ($row_tbl2['brs_oven_ulang'] + $row_tbl2['fin_oven_ulang'] + $row_tbl2['dye_oven_ulang'] + $row_tbl2['cqa_oven_ulang']); 
                                $display_oven_ap = ($anti_pilling_lain_lain_row != 0) ? $anti_pilling_lain_lain_row : '-';
                                // ($data_table1['garuk_fleece']!=0) ? $data_table1['garuk_fleece'] : '-';
                                echo "<td align='center'>{$display_oven_ap}</td>";
                                if ($anti_pilling_lain_lain_row != 0) {
                                $total_anti_pilling_lain_lain += $anti_pilling_lain_lain_row;
                            }
                                }else{
                                    $anti_pilling_lain_lain_row = $data_table1['ap_lain'] - ($row_tbl2['brs_oven_ulang'] + $row_tbl2['fin_oven_ulang'] + $row_tbl2['dye_oven_ulang'] + $row_tbl2['cqa_oven_ulang']);
                                    $display_oven_ap = ($anti_pilling_lain_lain_row != 0) ? $anti_pilling_lain_lain_row : '-';
                                    echo "<td align='center'>{$display_oven_ap}</td>";
                                    if ($anti_pilling_lain_lain_row != 0) {
                                        $total_anti_pilling_lain_lain += $anti_pilling_lain_lain_row;
                                    }   
                                 }
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
                            // $bantu_ncp_result = sqlsrv_query($conb, $bantu_ncp);
                            // $bantu_ncp_row = sqlsrv_fetch_array($bantu_ncp_result);
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
                            

                            echo "<td align='center'>" . ($total_produksi > 0 ? htmlspecialchars($total_produksi) : '-') . "</td>";
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
  <!-- Tabel-2.php -->
<table width="100%" border="0">
  <tr>
    <td colspan="3" align="left"><strong> LAPORAN PROSES ULANG </strong></td>
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
    <td align='center'><?php $garuk_fleece = $row_ncp['garuk_fleece'];
                                echo ($garuk_fleece!=0) ? $garuk_fleece : '-'; ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?php $garuk_ap = $row_ncp['garuk_ap'];
                                echo ($garuk_ap!=0) ? $garuk_ap : '-'; ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?php $oven_ap = $row_ncp['oven_ap'];
                                echo ($oven_ap!=0) ? $oven_ap : '-'; ?></td>
    <td align='center'><?php $peach_skin = $row_ncp['peach_skin'];
                                echo ($peach_skin!=0) ? $peach_skin : '-'; ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?= '-' ?></td>
    <td align='center'><?php $pb_lain = $row_ncp['pb_lain'];
                                echo ($pb_lain!=0) ? $pb_lain : '-'; ?></td>
    <td align='center'><?php $oven_ap_lain = $row_ncp['oven_ap_lain'];
                                echo ($oven_ap_lain!=0) ? $oven_ap_lain : '-';
                            ?></td>
    <td align='center'><?= '-' ?></td>	  
    <td align='center'><?php $qty_ncp1 = $row_ncp['qty_ncp'];
                                echo ($qty_ncp1!=0) ? $qty_ncp1 : '-';
                            ?></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><strong>BRUSHING ULANG</strong></td>
    <td colspan="-1" align="center" ><?php $brs_fleece_ulang = ($row_tbl2['brs_fleece_ulang']!=0) ? $row_tbl2['brs_fleece_ulang'] : '-';
                                echo $brs_fleece_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php 
                            $brs_ap_ulang = $row_tbl2['brs_ap_ulang'];
                                echo ($brs_ap_ulang!=0) ? $brs_ap_ulang : '-';
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $brs_peach_ulang = ($row_tbl2['brs_peach_ulang']!=0) ? $row_tbl2['brs_peach_ulang'] : '-';
                                echo $brs_peach_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $brs_pb_ulang = ($row_tbl2['brs_pb_ulang']!=0) ? $row_tbl2['brs_pb_ulang'] : '-';
                                echo $brs_pb_ulang;
                            ?></td>
    <td align="center" ><?php $brs_oven_ulang = ($row_tbl2['brs_oven_ulang']!=0) ? $row_tbl2['brs_oven_ulang'] : '-';
                                echo $brs_oven_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php 
                        $total_tbl2_brs = $row_tbl2['brs_fleece_ulang'] + $row_tbl2['brs_ap_ulang'] + $row_tbl2['brs_peach_ulang']+$row_tbl2['brs_pb_ulang']+$row_tbl2['brs_oven_ulang'];
                        echo $total_tbl2_brs > 0 ? $total_tbl2_brs : '-';?></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><strong>FINISHING ULANG</strong></td>
    <td colspan="-1" align="center" ><?php $fin_fleece_ulang = ($row_tbl2['fin_fleece_ulang']!=0) ? $row_tbl2['fin_fleece_ulang'] : '-';
                                echo $fin_fleece_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $fin_ap_ulang = ($row_tbl2['fin_ap_ulang']!=0) ? $row_tbl2['fin_ap_ulang'] : '-';
                                echo $fin_ap_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $fin_peach_ulang = ($row_tbl2['fin_peach_ulang']!=0) ? $row_tbl2['fin_peach_ulang'] : '-';
                                echo $fin_peach_ulang;
                            ?></td> 
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $fin_pb_ulang = ($row_tbl2['fin_pb_ulang']!=0) ? $row_tbl2['fin_pb_ulang'] : '-';
                                echo $fin_pb_ulang;
                            ?></td>
    <td align="center" ><?php $fin_oven_ulang = ($row_tbl2['fin_oven_ulang']!=0) ? $row_tbl2['fin_oven_ulang'] : '-';
                                echo $fin_oven_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php 
                            $total_tbl2_fin= $row_tbl2['fin_fleece_ulang']+$row_tbl2['fin_ap_ulang']+$row_tbl2['fin_peach_ulang']+$row_tbl2['fin_pb_ulang']+$row_tbl2['fin_oven_ulang'];
                            echo $total_tbl2_fin > 0 ? $total_tbl2_fin : '-';?></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><strong>DYEING ULANG</strong></td>
    <td colspan="-1" align="center" ><?php $dye_fleece_ulang = ($row_tbl2['dye_fleece_ulang']!=0) ? $row_tbl2['dye_fleece_ulang'] : '-';
                                echo $dye_fleece_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $dye_ap_ulang = ($row_tbl2['dye_ap_ulang']!=0) ? $row_tbl2['dye_ap_ulang'] : '-';
                                echo $dye_ap_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $dye_peach_ulang = ($row_tbl2['dye_peach_ulang']!=0) ? $row_tbl2['dye_peach_ulang'] : '-';
                                echo $dye_peach_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $dye_pb_ulang = ($row_tbl2['dye_pb_ulang']!=0) ? $row_tbl2['dye_pb_ulang'] : '-';
                                echo $dye_pb_ulang;
                            ?></td>
    <td align="center" ><?php $dye_oven_ulang = ($row_tbl2['dye_oven_ulang']!=0) ? $row_tbl2['dye_oven_ulang'] : '-';
                                echo $dye_oven_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php 
                            $total_tbl2_dye= $row_tbl2['dye_fleece_ulang']+$row_tbl2['dye_ap_ulang']+$row_tbl2['dye_peach_ulang']+$row_tbl2['dye_pb_ulang']+$row_tbl2['dye_oven_ulang'];
                            echo $total_tbl2_dye > 0 ? $total_tbl2_dye : '-';?></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><strong>CQA ULANG</strong></td>
    <td colspan="-1" align="center" ><?php $cqa_fleece_ulang = ($row_tbl2['cqa_fleece_ulang']!=0) ? $row_tbl2['cqa_fleece_ulang'] : '-';
                                echo $cqa_fleece_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $cqa_ap_ulang = ($row_tbl2['cqa_ap_ulang']!=0) ? $row_tbl2['cqa_ap_ulang'] : '-';
                                echo $cqa_ap_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $cqa_peach_ulang = ($row_tbl2['cqa_peach_ulang']!=0) ? $row_tbl2['cqa_peach_ulang'] : '-';
                                echo $cqa_peach_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align="center" ><?php $cqa_pb_ulang = ($row_tbl2['cqa_pb_ulang']!=0) ? $row_tbl2['cqa_pb_ulang'] : '-';
                                echo $cqa_pb_ulang;
                            ?></td>
    <td align="center" ><?php $cqa_oven_ulang = ($row_tbl2['cqa_oven_ulang']!=0) ? $row_tbl2['cqa_oven_ulang'] : '-';
                                echo $cqa_oven_ulang;
                            ?></td>
    <td align="center" ><?= '-' ?></td>
    <td align='center'><?php 
                            $total_tbl2_cqa= $row_tbl2['cqa_fleece_ulang']+$row_tbl2['cqa_ap_ulang']+$row_tbl2['cqa_peach_ulang']+$row_tbl2['cqa_pb_ulang']+$row_tbl2['cqa_oven_ulang'];
                            echo $total_tbl2_cqa > 0 ? $total_tbl2_cqa : '-';?></td>
  </tr>
  <tr>
	    <td colspan="3" align="center"><strong>TOTAL</strong></td>
	    <td colspan="-1" align="center" ><?php
                            $total_column1 =
                                ($row_tbl2['dye_fleece_ulang'] +$row_tbl2['cqa_fleece_ulang']+$row_tbl2['fin_fleece_ulang']+$row_tbl2['brs_fleece_ulang'] );
                            echo htmlspecialchars($total_column1 > 0 ? $total_column1 : '-');
                        ?></td>
	    <td align="center" ><?php
                            echo '-';
                        ?></td>
	    <td align="center" ><?php
                            $total_column3 = ($row_tbl2['dye_ap_ulang'] +$row_tbl2['cqa_ap_ulang']+$row_tbl2['fin_ap_ulang']+$row_tbl2['brs_ap_ulang'] );
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
                            $total_column7 = ($row_tbl2['dye_peach_ulang'] +$row_tbl2['cqa_peach_ulang']+$row_tbl2['fin_peach_ulang']+$row_tbl2['brs_peach_ulang'] );
                            echo htmlspecialchars($total_column7 > 0 ? $total_column7 : '-');
                        ?></td>


	    <td align="center" ><?php
                            echo '-';
                        ?></td>
	    <td align="center" ><?php
                            echo '-';
                        ?></td>
	    <td align="center" ><?php
                            $total_column10 = ($row_tbl2['dye_pb_ulang'] +$row_tbl2['cqa_pb_ulang']+$row_tbl2['fin_pb_ulang']+$row_tbl2['brs_pb_ulang'] );
                            echo htmlspecialchars($total_column10 > 0 ? $total_column10 : '-');
                        ?></td>
	    <td align="center" ><?php
                            $total_column11 = ($row_tbl2['dye_oven_ulang'] +$row_tbl2['cqa_oven_ulang']+$row_tbl2['fin_oven_ulang']+$row_tbl2['brs_oven_ulang'] );
                            echo htmlspecialchars($total_column11 > 0 ? $total_column11 : '-');
                        ?></td>
	    <td align="center" ><?php
                           echo '-'
                        ?></td>
	    <td align='center'><?php
                            $grand_total = $total_tbl2_cqa+$total_tbl2_dye+$total_tbl2_fin+$total_tbl2_brs+$qty_ncp;
                                // ($total_rowncp ?? 0) +
                                // ($total_bantu_ncp ?? 0) +
                                // ($total_datafin ?? 0) +
                                // ($total_dyeing ?? 0);
                            echo htmlspecialchars($grand_total > 0 ? $grand_total : '-');
                            ?></td>
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
                <td align="center"><strong>Mesin</strong></td>
                <td width="14%" align="center"><strong>No</strong></td>
                <td width="7%" align="center"><strong>LM</strong></td>
                <td width="7%" align="center"><strong>KM</strong></td>
                <td width="5%" align="center"><strong>PT</strong></td>
                <td width="6%" align="center"><strong>KO</strong></td>
                <td width="5%" align="center"><strong>AP</strong></td>
                <td width="4%" align="center"><strong>PA</strong></td>
                <td width="6%" align="center"><strong>PM</strong></td>
                <td width="5%" align="center"><strong>GT</strong></td>
                <td width="5%" align="center"><strong>TG</strong></td>
                <td width="9%" align="center"><strong>Total</strong></td>                
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
						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'TG' AND mesin IN ('P3RS1A1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_A_TG,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'TG' AND mesin IN ('P3RS1B1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_B_TG,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'TG' AND mesin IN ('P3RS1C1','P3RS1C2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_C_TG,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'TG' AND mesin IN ('P3RS1D1','P3RS1D2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_D_TG,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'TG' AND mesin IN ('P3RS1E1','P3RS1E2','P3RS1E3')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_E_TG,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'TG' AND mesin IN ('P3RS1F1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_F_TG
					FROM
						db_adm.tbl_stoppage
					WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";

                            $stmt_garuk9    = sqlsrv_query($cona,$query_garuk9);
                            $tg_g           = sqlsrv_fetch_array($stmt_garuk9, SQLSRV_FETCH_ASSOC);
                $query_garuk8 = "SELECT
						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'GT' AND mesin IN ('P3RS1A1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_A_GT,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'GT' AND mesin IN ('P3RS1B1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_B_GT,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'GT' AND mesin IN ('P3RS1C1','P3RS1C2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_C_GT,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'GT' AND mesin IN ('P3RS1D1','P3RS1D2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_D_GT,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'GT' AND mesin IN ('P3RS1E1','P3RS1E2','P3RS1E3')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_E_GT,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'GT' AND mesin IN ('P3RS1F1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_F_GT
					FROM
						db_adm.tbl_stoppage
					WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                            $stmt_garuk8    = sqlsrv_query($cona,$query_garuk8);
                            $gt_g             = sqlsrv_fetch_array($stmt_garuk8, SQLSRV_FETCH_ASSOC);
                $query_garuk7 = "SELECT
						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PM' AND mesin IN ('P3RS1A1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_A_PM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PM' AND mesin IN ('P3RS1B1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_B_PM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PM' AND mesin IN ('P3RS1C1','P3RS1C2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_C_PM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PM' AND mesin IN ('P3RS1D1','P3RS1D2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_D_PM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PM' AND mesin IN ('P3RS1E1','P3RS1E2','P3RS1E3')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_E_PM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PM' AND mesin IN ('P3RS1F1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_F_PM
					FROM
						db_adm.tbl_stoppage
					WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";

                            $stmt_garuk7    = sqlsrv_query($cona,$query_garuk7);
                            $pm_g             = sqlsrv_fetch_array($stmt_garuk7, SQLSRV_FETCH_ASSOC);
                $query_garuk6 = "SELECT
						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PA' AND mesin IN ('P3RS1A1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_A_PA,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PA' AND mesin IN ('P3RS1B1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_B_PA,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PA' AND mesin IN ('P3RS1C1','P3RS1C2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_C_PA,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PA' AND mesin IN ('P3RS1D1','P3RS1D2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_D_PA,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PA' AND mesin IN ('P3RS1E1','P3RS1E2','P3RS1E3')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_E_PA,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PA' AND mesin IN ('P3RS1F1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_F_PA
					FROM
						db_adm.tbl_stoppage
					WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";

                            $stmt_garuk6    = sqlsrv_query($cona,$query_garuk6);
                            $pa_g             = sqlsrv_fetch_array($stmt_garuk6, SQLSRV_FETCH_ASSOC);
                $query_garuk5 = "SELECT
						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'AP' AND mesin IN ('P3RS1A1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_A_AP,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'AP' AND mesin IN ('P3RS1B1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_B_AP,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'AP' AND mesin IN ('P3RS1C1','P3RS1C2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_C_AP,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'AP' AND mesin IN ('P3RS1D1','P3RS1D2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_D_AP,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'AP' AND mesin IN ('P3RS1E1','P3RS1E2','P3RS1E3')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_E_AP,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'AP' AND mesin IN ('P3RS1F1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_F_AP
					FROM
						db_adm.tbl_stoppage
					WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";

                            $stmt_garuk5    = sqlsrv_query($cona,$query_garuk5);
                            $ap_g             = sqlsrv_fetch_array($stmt_garuk5, SQLSRV_FETCH_ASSOC);
                $query_garuk4 = "SELECT
						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KO' AND mesin IN ('P3RS1A1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_A_KO,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KO' AND mesin IN ('P3RS1B1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_B_KO,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KO' AND mesin IN ('P3RS1C1','P3RS1C2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_C_KO,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KO' AND mesin IN ('P3RS1D1','P3RS1D2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_D_KO,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KO' AND mesin IN ('P3RS1E1','P3RS1E2','P3RS1E3')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_E_KO,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KO' AND mesin IN ('P3RS1F1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_F_KO
					FROM
						db_adm.tbl_stoppage
					WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                            $stmt_garuk4    = sqlsrv_query($cona,$query_garuk4);
                            $ko_g             = sqlsrv_fetch_array($stmt_garuk4, SQLSRV_FETCH_ASSOC);
                $query_garuk3 = "SELECT
						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PT' AND mesin IN ('P3RS1A1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_A_PT,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PT' AND mesin IN ('P3RS1B1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_B_PT,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PT' AND mesin IN ('P3RS1C1','P3RS1C2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_C_PT,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PT' AND mesin IN ('P3RS1D1','P3RS1D2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_D_PT,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PT' AND mesin IN ('P3RS1E1','P3RS1E2','P3RS1E3')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_E_PT,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'PT' AND mesin IN ('P3RS1F1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_F_PT
					FROM
						db_adm.tbl_stoppage
					WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                            $stmt_garuk3    = sqlsrv_query($cona,$query_garuk3);
                            $pt_g             = sqlsrv_fetch_array($stmt_garuk3, SQLSRV_FETCH_ASSOC);
                $query_garuk2 = "SELECT
						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KM' AND mesin IN ('P3RS1A1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_A_KM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KM' AND mesin IN ('P3RS1B1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_B_KM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KM' AND mesin IN ('P3RS1C1','P3RS1C2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_C_KM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KM' AND mesin IN ('P3RS1D1','P3RS1D2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_D_KM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KM' AND mesin IN ('P3RS1E1','P3RS1E2','P3RS1E3')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_E_KM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'KM' AND mesin IN ('P3RS1F1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_F_KM
					FROM
						db_adm.tbl_stoppage
					WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                            $stmt_garuk2    = sqlsrv_query($cona,$query_garuk2);
                            $km_g             = sqlsrv_fetch_array($stmt_garuk2, SQLSRV_FETCH_ASSOC);
				$query_garuk1 = "SELECT
						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'LM' AND mesin IN ('P3RS1A1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_A_LM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'LM' AND mesin IN ('P3RS1B1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_B_LM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'LM' AND mesin IN ('P3RS1C1','P3RS1C2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_C_LM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'LM' AND mesin IN ('P3RS1D1','P3RS1D2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_D_LM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'LM' AND mesin IN ('P3RS1E1','P3RS1E2','P3RS1E3')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_E_LM,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN kode_stop = 'LM' AND mesin IN ('P3RS1F1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS garuk_F_LM
					FROM
						db_adm.tbl_stoppage
					WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
								$stmt_garuk1    = sqlsrv_query($cona,$query_garuk1);
								$lm_g             = sqlsrv_fetch_array($stmt_garuk1, SQLSRV_FETCH_ASSOC);
						// Total Garuk
				$query_mesin_garuk = "SELECT
						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN mesin IN ('P3RS1A1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS menit_garuk_A,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN mesin IN ('P3RS1B1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS menit_garuk_B,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN mesin IN ('P3RS1C1','P3RS1C2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS menit_garuk_C,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN mesin IN ('P3RS1D1','P3RS1D2')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS menit_garuk_D,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN mesin IN ('P3RS1E1','P3RS1E2','P3RS1E3')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS menit_garuk_E,

						CONVERT(varchar(8), DATEADD(SECOND,
							CAST(ROUND(SUM(
								CASE
									WHEN mesin IN ('P3RS1F1')
									THEN durasi_jam_stop * 3600
									ELSE 0
								END
							), 0) AS int), 0), 108) AS menit_garuk_F
					FROM db_adm.tbl_stoppage
					WHERE dept = 'BRS'
					AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
					AND ISNULL(kode_stop,'') <> ''";
					$stmt_mesin_garuk= sqlsrv_query($cona,$query_mesin_garuk);
					$sum_mesin_garuk= sqlsrv_fetch_array($stmt_mesin_garuk, SQLSRV_FETCH_ASSOC);
                    ?>
                <!-- Mesin A -->
                <tr>
                    <td rowspan="6" align="left"><strong>GARUK</strong></td>
                    <td align="center">A</td>
                    <td align="center"><?php echo $lm_g['garuk_A_LM']; ?></td>
                    <td align="center"><?php echo $km_g['garuk_A_KM']; ?></td>
                    <td align="center"><?php echo $pt_g['garuk_A_PT']; ?></td>
                    <td align="center"><?php echo $ko_g['garuk_A_KO']; ?></td>
                    <td align="center"><?php echo $ap_g['garuk_A_AP']; ?></td>
                    <td align="center"><?php echo $pa_g['garuk_A_PA']; ?></td>
                    <td align="center"><?php echo $pm_g['garuk_A_PM']; ?></td>
                    <td align="center"><?php echo $gt_g['garuk_A_GT']; ?></td>
                    <td align="center"><?php echo $tg_g['garuk_A_TG']; ?></td>
                    <td align="center"><?php echo $sum_mesin_garuk['menit_garuk_A'];?></td>                   
                </tr>
                <!-- Mesin B -->
                <tr>
                    <td align="center">B</td>
                    <td align="center"><?php echo $lm_g['garuk_B_LM']; ?></td>
                    <td align="center"><?php echo $km_g['garuk_B_KM']; ?></td>
                    <td align="center"><?php echo $pt_g['garuk_B_PT']; ?></td>
                    <td align="center"><?php echo $ko_g['garuk_B_KO']; ?></td>
                    <td align="center"><?php echo $ap_g['garuk_B_AP']; ?></td>
                    <td align="center"><?php echo $pa_g['garuk_B_PA']; ?></td>
                    <td align="center"><?php echo $pm_g['garuk_B_PM']; ?></td>
                    <td align="center"><?php echo $gt_g['garuk_B_GT']; ?></td>
                    <td align="center"><?php echo $tg_g['garuk_B_TG']; ?></td>
                    <td align="center"><?php echo $sum_mesin_garuk['menit_garuk_B'];?></td>                    
                </tr>
                <!-- Mesin C -->
                <tr>
                    <td align="center">C</td>
                    <td align="center"><?php echo $lm_g['garuk_C_LM']; ?></td>
                    <td align="center"><?php echo $km_g['garuk_C_KM']; ?></td>
                    <td align="center"><?php echo $pt_g['garuk_C_PT']; ?></td>
                    <td align="center"><?php echo $ko_g['garuk_C_KO']; ?></td>
                    <td align="center"><?php echo $ap_g['garuk_C_AP']; ?></td>
                    <td align="center"><?php echo $pa_g['garuk_C_PA']; ?></td>
                    <td align="center"><?php echo $pm_g['garuk_C_PM']; ?></td>
                    <td align="center"><?php echo $gt_g['garuk_C_GT']; ?></td>
                    <td align="center"><?php echo $tg_g['garuk_C_TG']; ?></td>
                    <td align="center"><?php echo $sum_mesin_garuk['menit_garuk_C'];?></td>
                </tr>
                <!-- Mesin D -->
                <tr>
                    <td align="center">D</td>
                    <td align="center"><?php echo $lm_g['garuk_D_LM']; ?></td>
                    <td align="center"><?php echo $km_g['garuk_D_KM']; ?></td>
                    <td align="center"><?php echo $pt_g['garuk_D_PT']; ?></td>
                    <td align="center"><?php echo $ko_g['garuk_D_KO']; ?></td>
                    <td align="center"><?php echo $ap_g['garuk_D_AP']; ?></td>
                    <td align="center"><?php echo $pa_g['garuk_D_PA']; ?></td>
                    <td align="center"><?php echo $pm_g['garuk_D_PM']; ?></td>
                    <td align="center"><?php echo $gt_g['garuk_D_GT']; ?></td>
                    <td align="center"><?php echo $tg_g['garuk_D_TG']; ?></td>
                    <td align="center"><?php echo $sum_mesin_garuk['menit_garuk_D'];?></td>
                </tr>
                <!-- Mesin E -->
                <tr>
                    <td align="center">E</td>
                    <td align="center"><?php echo $lm_g['garuk_E_LM']; ?></td>
                    <td align="center"><?php echo $km_g['garuk_E_KM']; ?></td>
                    <td align="center"><?php echo $pt_g['garuk_E_PT']; ?></td>
                    <td align="center"><?php echo $ko_g['garuk_E_KO']; ?></td>
                    <td align="center"><?php echo $ap_g['garuk_E_AP']; ?></td>
                    <td align="center"><?php echo $pa_g['garuk_E_PA']; ?></td>
                    <td align="center"><?php echo $pm_g['garuk_E_PM']; ?></td>
                    <td align="center"><?php echo $gt_g['garuk_E_GT']; ?></td>
                    <td align="center"><?php echo $tg_g['garuk_E_TG']; ?></td>
                    <td align="center"><?php echo $sum_mesin_garuk['menit_garuk_E'];?></td>
                </tr>
                <!-- Mesin F -->
                <tr>
                    <td align="center">F</td>
                    <td align="center"><?php echo $lm_g['garuk_F_LM']; ?></td>
                    <td align="center"><?php echo $km_g['garuk_F_KM']; ?></td>
                    <td align="center"><?php echo $pt_g['garuk_F_PT']; ?></td>
                    <td align="center"><?php echo $ko_g['garuk_F_KO']; ?></td>
                    <td align="center"><?php echo $ap_g['garuk_F_AP']; ?></td>
                    <td align="center"><?php echo $pa_g['garuk_F_PA']; ?></td>
                    <td align="center"><?php echo $pm_g['garuk_F_PM']; ?></td>
                    <td align="center"><?php echo $gt_g['garuk_F_GT']; ?></td>
                    <td align="center"><?php echo $tg_g['garuk_F_TG']; ?></td>
                    <td align="center"><?php echo $sum_mesin_garuk['menit_garuk_F'];?></td>
                </tr>
            <!-- End Garuk -->
            <!-- Untuk Kolom Sisir -->
                <tr>
                    <?php 
                    $query_sisir9 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
											AND mesin IN ('P3CO101','P3CO102')
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS sisir_TG
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_sisir9    = sqlsrv_query($cona,$query_sisir9);
						$tg_sisir             = sqlsrv_fetch_array($stmt_sisir9, SQLSRV_FETCH_ASSOC);
                    $query_sisir8 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
											AND mesin IN ('P3CO101','P3CO102')
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS sisir_GT
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_sisir8    = sqlsrv_query($cona,$query_sisir8);
						$gt_sisir             = sqlsrv_fetch_array($stmt_sisir8, SQLSRV_FETCH_ASSOC);
                    $query_sisir7 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
											AND mesin IN ('P3CO101','P3CO102')
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS sisir_PM
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_sisir7    = sqlsrv_query($cona,$query_sisir7);
						$pm_sisir             = sqlsrv_fetch_array($stmt_sisir7, SQLSRV_FETCH_ASSOC);
                    $query_sisir6 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
											AND mesin IN ('P3CO101','P3CO102')
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS sisir_PA
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_sisir6    = sqlsrv_query($cona,$query_sisir6);
						$pa_sisir             = sqlsrv_fetch_array($stmt_sisir6, SQLSRV_FETCH_ASSOC);
                    $query_sisir5 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
											AND mesin IN ('P3CO101','P3CO102')
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS sisir_AP
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_sisir5    = sqlsrv_query($cona,$query_sisir5);
						$ap_sisir             = sqlsrv_fetch_array($stmt_sisir5, SQLSRV_FETCH_ASSOC);
                    $query_sisir4 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(FLOOR(SUM(
									CASE
										WHEN kode_stop = 'KO'
											AND mesin IN ('P3CO101','P3CO102')
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								)) AS int), 0), 108) AS sisir_KO
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_sisir4    = sqlsrv_query($cona,$query_sisir4);
						$ko_sisir             = sqlsrv_fetch_array($stmt_sisir4, SQLSRV_FETCH_ASSOC);
					$query_sisir3 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
											AND mesin IN ('P3CO101','P3CO102')
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS sisir_PT
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_sisir3    = sqlsrv_query($cona,$query_sisir3);
						$pt_sisir             = sqlsrv_fetch_array($stmt_sisir3, SQLSRV_FETCH_ASSOC);
                    $query_sisir2 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
											AND mesin IN ('P3CO101','P3CO102')
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS sisir_KM
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_sisir2    = sqlsrv_query($cona,$query_sisir2);
						$km_sisir             = sqlsrv_fetch_array($stmt_sisir2, SQLSRV_FETCH_ASSOC);
                    $query_sisir1 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
											AND mesin IN ('P3CO101','P3CO102')
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS sisir_LM
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_sisir1    = sqlsrv_query($cona,$query_sisir1);
						$lm_sisir             = sqlsrv_fetch_array($stmt_sisir1, SQLSRV_FETCH_ASSOC);
                            // Total Sisir
                    $query_mesin_sisir = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin IN ('P3CO101','P3CO102')
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_sisir
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                        $stmt_mesin_sisir= sqlsrv_query($cona,$query_mesin_sisir);
                        $sum_mesin_sisir= sqlsrv_fetch_array($stmt_mesin_sisir, SQLSRV_FETCH_ASSOC);
                    ?>
                    <td align="left"><strong>SISIR</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php echo $lm_sisir['sisir_LM']; ?></td>
                    <td align="center"><?php echo $km_sisir['sisir_KM']; ?></td>
                    <td align="center"><?php echo $pt_sisir['sisir_PT']; ?></td>
                    <td align="center"><?php echo $ko_sisir['sisir_KO']; ?></td>
                    <td align="center"><?php echo $ap_sisir['sisir_AP']; ?></td>
                    <td align="center"><?php echo $pa_sisir['sisir_PA']; ?></td>
                    <td align="center"><?php echo $pm_sisir['sisir_PM']; ?></td>
                    <td align="center"><?php echo $gt_sisir['sisir_GT']; ?></td>
                    <td align="center"><?php echo $tg_sisir['sisir_TG']; ?></td>
                    <td align="center"><?php echo $sum_mesin_sisir['menit_sisir'];?></td>                    
                </tr>
            <!-- End Sisir -->          
            <!-- Untuk Kolom Potong Bulu -->
                <tr>
                    <?php
                    $query_pb2 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
										AND mesin = 'P3SH101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_01_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
										AND mesin = 'P3SH102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_02_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
										AND mesin = 'P3SH103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_03_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
										AND mesin = 'P3SH104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_04_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
										AND mesin = 'P3SH105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_05_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
										AND mesin = 'P3SH106'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_06_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
										AND mesin = 'P3SH107'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_07_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
										AND mesin = 'P3SH108'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_08_KM
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_pb2    = sqlsrv_query($cona,$query_pb2);
						$km_pb             = sqlsrv_fetch_array($stmt_pb2, SQLSRV_FETCH_ASSOC);
                    $query_pb3 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
										AND mesin = 'P3SH101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_01_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
										AND mesin = 'P3SH102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_02_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
										AND mesin = 'P3SH103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_03_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
										AND mesin = 'P3SH104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_04_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
										AND mesin = 'P3SH105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_05_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
										AND mesin = 'P3SH106'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_06_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
										AND mesin = 'P3SH107'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_07_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
										AND mesin = 'P3SH108'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_08_PT
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_pb3    = sqlsrv_query($cona,$query_pb3);
						$pt_pb             = sqlsrv_fetch_array($stmt_pb3, SQLSRV_FETCH_ASSOC);
                    $query_pb4 = "SELECT
								CONVERT(varchar(8), DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE
											WHEN kode_stop = 'KO'
											AND mesin = 'P3SH101'
											THEN durasi_jam_stop * 3600
											ELSE 0
										END
									), 0) AS int), 0), 108) AS pb_01_KO,

								CONVERT(varchar(8), DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE
											WHEN kode_stop = 'KO'
											AND mesin = 'P3SH102'
											THEN durasi_jam_stop * 3600
											ELSE 0
										END
									), 0) AS int), 0), 108) AS pb_02_KO,

								CONVERT(varchar(8), DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE
											WHEN kode_stop = 'KO'
											AND mesin = 'P3SH103'
											THEN durasi_jam_stop * 3600
											ELSE 0
										END
									), 0) AS int), 0), 108) AS pb_03_KO,

								CONVERT(varchar(8), DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE
											WHEN kode_stop = 'KO'
											AND mesin = 'P3SH104'
											THEN durasi_jam_stop * 3600
											ELSE 0
										END
									), 0) AS int), 0), 108) AS pb_04_KO,

								CONVERT(varchar(8), DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE
											WHEN kode_stop = 'KO'
											AND mesin = 'P3SH105'
											THEN durasi_jam_stop * 3600
											ELSE 0
										END
									), 0) AS int), 0), 108) AS pb_05_KO,

								CONVERT(varchar(8), DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE
											WHEN kode_stop = 'KO'
											AND mesin = 'P3SH106'
											THEN durasi_jam_stop * 3600
											ELSE 0
										END
									), 0) AS int), 0), 108) AS pb_06_KO,

								CONVERT(varchar(8), DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE
											WHEN kode_stop = 'KO'
											AND mesin = 'P3SH107'
											THEN durasi_jam_stop * 3600
											ELSE 0
										END
									), 0) AS int), 0), 108) AS pb_07_KO,

								CONVERT(varchar(8), DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE
											WHEN kode_stop = 'KO'
											AND mesin = 'P3SH108'
											THEN durasi_jam_stop * 3600
											ELSE 0
										END
									), 0) AS int), 0), 108) AS pb_08_KO
							FROM db_adm.tbl_stoppage
							WHERE dept = 'BRS'
							AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
							AND ISNULL(kode_stop,'') <> ''";
							$stmt_pb4    = sqlsrv_query($cona,$query_pb4);
							$ko_pb             = sqlsrv_fetch_array($stmt_pb4, SQLSRV_FETCH_ASSOC);
                    $query_pb5 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
										AND mesin = 'P3SH101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_01_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
										AND mesin = 'P3SH102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_02_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
										AND mesin = 'P3SH103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_03_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
										AND mesin = 'P3SH104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_04_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
										AND mesin = 'P3SH105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_05_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
										AND mesin = 'P3SH106'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_06_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
										AND mesin = 'P3SH107'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_07_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
										AND mesin = 'P3SH108'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_08_AP
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_pb5    = sqlsrv_query($cona,$query_pb5);
						$ap_pb             = sqlsrv_fetch_array($stmt_pb5, SQLSRV_FETCH_ASSOC);
                    $query_pb6 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
										AND mesin = 'P3SH101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_01_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
										AND mesin = 'P3SH102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_02_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
										AND mesin = 'P3SH103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_03_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
										AND mesin = 'P3SH104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_04_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
										AND mesin = 'P3SH105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_05_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
										AND mesin = 'P3SH106'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_06_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
										AND mesin = 'P3SH107'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_07_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
										AND mesin = 'P3SH108'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_08_PA
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_pb6    = sqlsrv_query($cona,$query_pb6);
						$pa_pb             = sqlsrv_fetch_array($stmt_pb6, SQLSRV_FETCH_ASSOC);
                    $query_pb7 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
										AND mesin = 'P3SH101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_01_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
										AND mesin = 'P3SH102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_02_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
										AND mesin = 'P3SH103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_03_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
										AND mesin = 'P3SH104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_04_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
										AND mesin = 'P3SH105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_05_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
										AND mesin = 'P3SH106'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_06_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
										AND mesin = 'P3SH107'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_07_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
										AND mesin = 'P3SH108'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_08_PM
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_pb7    = sqlsrv_query($cona,$query_pb7);
						$pm_pb             = sqlsrv_fetch_array($stmt_pb7, SQLSRV_FETCH_ASSOC);
                    $query_pb8 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
										AND mesin = 'P3SH101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_01_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
										AND mesin = 'P3SH102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_02_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
										AND mesin = 'P3SH103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_03_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
										AND mesin = 'P3SH104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_04_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
										AND mesin = 'P3SH105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_05_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
										AND mesin = 'P3SH106'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_06_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
										AND mesin = 'P3SH107'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_07_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
										AND mesin = 'P3SH108'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_08_GT
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_pb8    = sqlsrv_query($cona,$query_pb8);
						$gt_pb             = sqlsrv_fetch_array($stmt_pb8, SQLSRV_FETCH_ASSOC);
                    $query_pb9 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
										AND mesin = 'P3SH101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_01_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
										AND mesin = 'P3SH102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_02_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
										AND mesin = 'P3SH103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_03_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
										AND mesin = 'P3SH104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_04_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
										AND mesin = 'P3SH105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_05_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
										AND mesin = 'P3SH106'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_06_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
										AND mesin = 'P3SH107'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_07_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
										AND mesin = 'P3SH108'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_08_TG
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_pb9    = sqlsrv_query($cona,$query_pb9);
						$tg_pb             = sqlsrv_fetch_array($stmt_pb9, SQLSRV_FETCH_ASSOC);
                    $query_pb1 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
										AND mesin = 'P3SH101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_01_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
										AND mesin = 'P3SH102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_02_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
										AND mesin = 'P3SH103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_03_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
										AND mesin = 'P3SH104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_04_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
										AND mesin = 'P3SH105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_05_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
										AND mesin = 'P3SH106'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_06_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
										AND mesin = 'P3SH107'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_07_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
										AND mesin = 'P3SH108'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS pb_08_LM
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_pb1    = sqlsrv_query($cona,$query_pb1);
						$lm_pb             = sqlsrv_fetch_array($stmt_pb1, SQLSRV_FETCH_ASSOC);
                            // Total Pb
                    $query_mesin_pb = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SH101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_pb_01,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SH102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_pb_02,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SH103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_pb_03,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SH104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_pb_04,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SH105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_pb_05,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SH106'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_pb_06,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SH107'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_pb_07,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SH108'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_pb_08
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                        $stmt_mesin_pb= sqlsrv_query($cona,$query_mesin_pb);
                        $sum_mesin_pb= sqlsrv_fetch_array($stmt_mesin_pb, SQLSRV_FETCH_ASSOC);
                    ?>
                <!-- Mesin 01 -->
                    <td rowspan="8" align="left"><strong>POTONG BULU</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php echo $lm_pb['pb_01_LM'];?></td>
                    <td align="center"><?php echo $km_pb['pb_01_KM'];?></td>
                    <td align="center"><?php echo $pt_pb['pb_01_PT'];?></td>
                    <td align="center"><?php echo $ko_pb['pb_01_KO'];?></td>
                    <td align="center"><?php echo $ap_pb['pb_01_AP'];?></td>
                    <td align="center"><?php echo $pa_pb['pb_01_PA'];?></td>
                    <td align="center"><?php echo $pm_pb['pb_01_PM'];?></td>
                    <td align="center"><?php echo $gt_pb['pb_01_GT'];?></td>
                    <td align="center"><?php echo $tg_pb['pb_01_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_pb['menit_pb_01'];?></td>                    
                </tr>
                <!-- Mesin 02 -->
                <tr>
                    <td align="center">02</td>
                    <td align="center"><?php echo $lm_pb['pb_02_LM'];?></td>
                    <td align="center"><?php echo $km_pb['pb_02_KM'];?></td>
                    <td align="center"><?php echo $pt_pb['pb_02_PT'];?></td>
                    <td align="center"><?php echo $ko_pb['pb_02_KO'];?></td>
                    <td align="center"><?php echo $ap_pb['pb_02_AP'];?></td>
                    <td align="center"><?php echo $pa_pb['pb_02_PA'];?></td>
                    <td align="center"><?php echo $pm_pb['pb_02_PM'];?></td>
                    <td align="center"><?php echo $gt_pb['pb_02_GT'];?></td>
                    <td align="center"><?php echo $tg_pb['pb_02_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_pb['menit_pb_02'];?></td>
                </tr>
                <!-- Mesin 03 -->
                <tr>
                    <td align="center">03</td>
                    <td align="center"><?php echo $lm_pb['pb_03_LM'];?></td>
                    <td align="center"><?php echo $km_pb['pb_03_KM'];?></td>
                    <td align="center"><?php echo $pt_pb['pb_03_PT'];?></td>
                    <td align="center"><?php echo $ko_pb['pb_03_KO'];?></td>
                    <td align="center"><?php echo $ap_pb['pb_03_AP'];?></td>
                    <td align="center"><?php echo $pa_pb['pb_03_PA'];?></td>
                    <td align="center"><?php echo $pm_pb['pb_03_PM'];?></td>
                    <td align="center"><?php echo $gt_pb['pb_03_GT'];?></td>
                    <td align="center"><?php echo $tg_pb['pb_03_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_pb['menit_pb_03'];?></td>
                </tr>
                <!-- Mesin 04 -->
                <tr>
                    <td align="center">04</td>
                    <td align="center"><?php echo $lm_pb['pb_04_LM'];?></td>
                    <td align="center"><?php echo $km_pb['pb_04_KM'];?></td>
                    <td align="center"><?php echo $pt_pb['pb_04_PT'];?></td>
                    <td align="center"><?php echo $ko_pb['pb_04_KO'];?></td>
                    <td align="center"><?php echo $ap_pb['pb_04_AP'];?></td>
                    <td align="center"><?php echo $pa_pb['pb_04_PA'];?></td>
                    <td align="center"><?php echo $pm_pb['pb_04_PM'];?></td>
                    <td align="center"><?php echo $gt_pb['pb_04_GT'];?></td>
                    <td align="center"><?php echo $tg_pb['pb_04_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_pb['menit_pb_04'];?></td>
                </tr>
                <!-- Mesin 05 -->
                <tr>
                    <td align="center">05</td>
                    <td align="center"><?php echo $lm_pb['pb_05_LM'];?></td>
                    <td align="center"><?php echo $km_pb['pb_05_KM'];?></td>
                    <td align="center"><?php echo $pt_pb['pb_05_PT'];?></td>
                    <td align="center"><?php echo $ko_pb['pb_05_KO'];?></td>
                    <td align="center"><?php echo $ap_pb['pb_05_AP'];?></td>
                    <td align="center"><?php echo $pa_pb['pb_05_PA'];?></td>
                    <td align="center"><?php echo $pm_pb['pb_05_PM'];?></td>
                    <td align="center"><?php echo $gt_pb['pb_05_GT'];?></td>
                    <td align="center"><?php echo $tg_pb['pb_05_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_pb['menit_pb_05'];?></td>
                </tr>
                <!-- Mesin 06 -->
                <tr>
                    <td align="center">06</td>
                    <td align="center"><?php echo $lm_pb['pb_06_LM'];?></td>
                    <td align="center"><?php echo $km_pb['pb_06_KM'];?></td>
                    <td align="center"><?php echo $pt_pb['pb_06_PT'];?></td>
                    <td align="center"><?php echo $ko_pb['pb_06_KO'];?></td>
                    <td align="center"><?php echo $ap_pb['pb_06_AP'];?></td>
                    <td align="center"><?php echo $pa_pb['pb_06_PA'];?></td>
                    <td align="center"><?php echo $pm_pb['pb_06_PM'];?></td>
                    <td align="center"><?php echo $gt_pb['pb_06_GT'];?></td>
                    <td align="center"><?php echo $tg_pb['pb_06_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_pb['menit_pb_06'];?></td>
                </tr>
                <!-- Mesin 07 -->
                <tr>
                    <td align="center">07</td>
                    <td align="center"><?php echo $lm_pb['pb_07_LM'];?></td>
                    <td align="center"><?php echo $km_pb['pb_07_KM'];?></td>
                    <td align="center"><?php echo $pt_pb['pb_07_PT'];?></td>
                    <td align="center"><?php echo $ko_pb['pb_07_KO'];?></td>
                    <td align="center"><?php echo $ap_pb['pb_07_AP'];?></td>
                    <td align="center"><?php echo $pa_pb['pb_07_PA'];?></td>
                    <td align="center"><?php echo $pm_pb['pb_07_PM'];?></td>
                    <td align="center"><?php echo $gt_pb['pb_07_GT'];?></td>
                    <td align="center"><?php echo $tg_pb['pb_07_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_pb['menit_pb_07'];?></td>
                </tr>
                <!-- Mesin 08 -->
                <tr>
                    <td align="center">08</td>
                    <td align="center"><?php echo $lm_pb['pb_08_LM'];?></td>
                    <td align="center"><?php echo $km_pb['pb_08_KM'];?></td>
                    <td align="center"><?php echo $pt_pb['pb_08_PT'];?></td>
                    <td align="center"><?php echo $ko_pb['pb_08_KO'];?></td>
                    <td align="center"><?php echo $ap_pb['pb_08_AP'];?></td>
                    <td align="center"><?php echo $pa_pb['pb_08_PA'];?></td>
                    <td align="center"><?php echo $pm_pb['pb_08_PM'];?></td>
                    <td align="center"><?php echo $gt_pb['pb_08_GT'];?></td>
                    <td align="center"><?php echo $tg_pb['pb_08_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_pb['menit_pb_08'];?></td>
                </tr>
            <!-- End Potong Bulu -->
            <!-- Untuk Kolom Peach Skin -->
             	<?php 
                    $query_peach9 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM' AND mesin = 'P3SU105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_05_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM' AND mesin = 'P3SU104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_04_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM' AND mesin = 'P3SU103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_03_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM' AND mesin = 'P3SU102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_02_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM' AND mesin = 'P3SU101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_01_LM
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_peach9    = sqlsrv_query($cona,$query_peach9);
						$lm             = sqlsrv_fetch_array($stmt_peach9, SQLSRV_FETCH_ASSOC);
                    $query_peach8 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM' AND mesin = 'P3SU105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_05_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM' AND mesin = 'P3SU104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_04_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM' AND mesin = 'P3SU103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_03_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM' AND mesin = 'P3SU102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_02_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM' AND mesin = 'P3SU101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_01_KM
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_peach8    = sqlsrv_query($cona,$query_peach8);
						$km             = sqlsrv_fetch_array($stmt_peach8, SQLSRV_FETCH_ASSOC);
                    $query_peach7 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT' AND mesin = 'P3SU105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_05_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT' AND mesin = 'P3SU104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_04_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT' AND mesin = 'P3SU103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_03_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT' AND mesin = 'P3SU102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_02_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT' AND mesin = 'P3SU101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_01_PT
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_peach7    = sqlsrv_query($cona,$query_peach7);
						$pt             = sqlsrv_fetch_array($stmt_peach7, SQLSRV_FETCH_ASSOC);
                    $query_peach6 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO' AND mesin = 'P3SU105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_05_KO,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO' AND mesin = 'P3SU104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_04_KO,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO' AND mesin = 'P3SU103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_03_KO,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO' AND mesin = 'P3SU102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_02_KO,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO' AND mesin = 'P3SU101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_01_KO
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_peach6    = sqlsrv_query($cona,$query_peach6);
						$ko             = sqlsrv_fetch_array($stmt_peach6, SQLSRV_FETCH_ASSOC);
                    $query_peach5 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP' AND mesin = 'P3SU105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_05_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP' AND mesin = 'P3SU104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_04_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP' AND mesin = 'P3SU103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_03_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP' AND mesin = 'P3SU102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_02_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP' AND mesin = 'P3SU101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_01_AP
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_peach5    = sqlsrv_query($cona,$query_peach5);
						$ap             = sqlsrv_fetch_array($stmt_peach5, SQLSRV_FETCH_ASSOC);
                    $query_peach4 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA' AND mesin = 'P3SU105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_05_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA' AND mesin = 'P3SU104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_04_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA' AND mesin = 'P3SU103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_03_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA' AND mesin = 'P3SU102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_02_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA' AND mesin = 'P3SU101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_01_PA
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_peach4    = sqlsrv_query($cona,$query_peach4);
						$pa             = sqlsrv_fetch_array($stmt_peach4, SQLSRV_FETCH_ASSOC);
                    $query_peach3 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM' AND mesin = 'P3SU105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_05_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM' AND mesin = 'P3SU104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_04_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM' AND mesin = 'P3SU103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_03_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM' AND mesin = 'P3SU102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_02_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM' AND mesin = 'P3SU101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_01_PM
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_peach3    = sqlsrv_query($cona,$query_peach3);
						$pm             = sqlsrv_fetch_array($stmt_peach3, SQLSRV_FETCH_ASSOC);
                    $query_peach2 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT' AND mesin = 'P3SU105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_05_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT' AND mesin = 'P3SU104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_04_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT' AND mesin = 'P3SU103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_03_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT' AND mesin = 'P3SU102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_02_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT' AND mesin = 'P3SU101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_01_GT
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_peach2    = sqlsrv_query($cona,$query_peach2);
						$gt             = sqlsrv_fetch_array($stmt_peach2, SQLSRV_FETCH_ASSOC);
                    $query_peach1 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG' AND mesin = 'P3SU105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_05_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG' AND mesin = 'P3SU104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_04_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG' AND mesin = 'P3SU103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_03_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG' AND mesin = 'P3SU102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_02_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG' AND mesin = 'P3SU101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS peach_01_TG
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                        $stmt_peach1= sqlsrv_query($cona,$query_peach1);
                        $tg= sqlsrv_fetch_array($stmt_peach1, SQLSRV_FETCH_ASSOC);
                    // Total Peach
                    $query_mesin_peach1 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SU105'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_peach_05,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SU104'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_peach_04,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SU103'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_peach_03,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SU102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_peach_02,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3SU101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_peach_01
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                        $stmt_mesin_peach1= sqlsrv_query($cona,$query_mesin_peach1);
                        $sum_mesin_peach= sqlsrv_fetch_array($stmt_mesin_peach1, SQLSRV_FETCH_ASSOC);
                ?>
                <!-- Untuk Mesin 01 -->
                    <tr>
                    <td rowspan="5" align="left"><strong>PEACH SKIN</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php echo $lm['peach_01_LM'];?></td>
                    <td align="center"><?php echo $km['peach_01_KM'];?></td>
                    <td align="center"><?php echo $pt['peach_01_PT'];?></td>
                    <td align="center"><?php echo $ko['peach_01_KO'];?></td>
                    <td align="center"><?php echo $ap['peach_01_AP'];?></td>
                    <td align="center"><?php echo $pa['peach_01_PA'];?></td>
                    <td align="center"><?php echo $pm['peach_01_PM'];?></td>
                    <td align="center"><?php echo $gt['peach_01_GT'];?></td>
                    <td align="center"><?php echo $tg['peach_01_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_peach['menit_peach_01'];?></td>
                </tr>
                <!-- Untuk Mesin 02 -->
                    <tr>
                    <td align="center">02</td>
                    <td align="center"><?php echo $lm['peach_02_LM'];?></td>
                    <td align="center"><?php echo $km['peach_02_KM'];?></td>
                    <td align="center"><?php echo $pt['peach_02_PT'];?></td>
                    <td align="center"><?php echo $ko['peach_02_KO'];?></td>
                    <td align="center"><?php echo $ap['peach_02_AP'];?></td>
                    <td align="center"><?php echo $pa['peach_02_PA'];?></td>
                    <td align="center"><?php echo $pm['peach_02_PM'];?></td>
                    <td align="center"><?php echo $gt['peach_02_GT'];?></td>
                    <td align="center"><?php echo $tg['peach_02_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_peach['menit_peach_02'];?></td>
                </tr>
                <!-- Untuk Mesin 03 -->
                    <tr>
                    <td align="center">03</td>
                    <td align="center"><?php echo $lm['peach_03_LM'];?></td>
                    <td align="center"><?php echo $km['peach_03_KM'];?></td>
                    <td align="center"><?php echo $pt['peach_03_PT'];?></td>
                    <td align="center"><?php echo $ko['peach_03_KO'];?></td>
                    <td align="center"><?php echo $ap['peach_03_AP'];?></td>
                    <td align="center"><?php echo $pa['peach_03_PA'];?></td>
                    <td align="center"><?php echo $pm['peach_03_PM'];?></td>
                    <td align="center"><?php echo $gt['peach_03_GT'];?></td>
                    <td align="center"><?php echo $tg['peach_03_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_peach['menit_peach_03'];?></td>
                </tr>
                <!-- Untuk Mesin 04 -->
                    <tr>
                    <td align="center">04</td>
                    <td align="center"><?php echo $lm['peach_04_LM'];?></td>
                    <td align="center"><?php echo $km['peach_04_KM'];?></td>
                    <td align="center"><?php echo $pt['peach_04_PT'];?></td>
                    <td align="center"><?php echo $ko['peach_04_KO'];?></td>
                    <td align="center"><?php echo $ap['peach_04_AP'];?></td>
                    <td align="center"><?php echo $pa['peach_04_PA'];?></td>
                    <td align="center"><?php echo $pm['peach_04_PM'];?></td>
                    <td align="center"><?php echo $gt['peach_04_GT'];?></td>
                    <td align="center"><?php echo $tg['peach_04_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_peach['menit_peach_04'];?></td>
                </tr>
                <!-- Untuk Mesin 05 -->
                    <tr>
                    <td align="center">05</td>
                    <td align="center"><?php echo $lm['peach_05_LM'];?></td>
                    <td align="center"><?php echo $km['peach_05_KM'];?></td>
                    <td align="center"><?php echo $pt['peach_05_PT'];?></td>
                    <td align="center"><?php echo $ko['peach_05_KO'];?></td>
                    <td align="center"><?php echo $ap['peach_05_AP'];?></td>
                    <td align="center"><?php echo $pa['peach_05_PA'];?></td>
                    <td align="center"><?php echo $pm['peach_05_PM'];?></td>
                    <td align="center"><?php echo $gt['peach_05_GT'];?></td>
                    <td align="center"><?php echo $tg['peach_05_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_peach['menit_peach_05'];?></td>
                    <!-- <td align="center">Ini untuk total</td> -->
                </tr>

            <!-- End Peach -->
            <!-- Untuk Kolom Airo -->
                <tr>
                    <?php 
                    $query_airo9 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
											AND mesin = 'P3AR101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_01_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
											AND mesin = 'P3AR102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_02_TG
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_airo9    = sqlsrv_query($cona,$query_airo9);
						$tg_airo             = sqlsrv_fetch_array($stmt_airo9, SQLSRV_FETCH_ASSOC);
                    $query_airo8 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
											AND mesin = 'P3AR101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_01_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
											AND mesin = 'P3AR102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_02_GT
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_airo8    = sqlsrv_query($cona,$query_airo8);
						$gt_airo             = sqlsrv_fetch_array($stmt_airo8, SQLSRV_FETCH_ASSOC);
                    $query_airo7 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
											AND mesin = 'P3AR101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_01_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
											AND mesin = 'P3AR102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_02_PM
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_airo7    = sqlsrv_query($cona,$query_airo7);
						$pm_airo             = sqlsrv_fetch_array($stmt_airo7, SQLSRV_FETCH_ASSOC);
                    $query_airo6 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
											AND mesin = 'P3AR101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_01_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
											AND mesin = 'P3AR102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_02_PA
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_airo6    = sqlsrv_query($cona,$query_airo6);
						$pa_airo             = sqlsrv_fetch_array($stmt_airo6, SQLSRV_FETCH_ASSOC);
                    $query_airo5 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
											AND mesin = 'P3AR101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_01_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
											AND mesin = 'P3AR102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_02_AP
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_airo5    = sqlsrv_query($cona,$query_airo5);
						$ap_airo             = sqlsrv_fetch_array($stmt_airo5, SQLSRV_FETCH_ASSOC);
                    $query_airo4 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO'
											AND mesin = 'P3AR101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_01_KO,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO'
											AND mesin = 'P3AR102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_02_KO
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_airo4    = sqlsrv_query($cona,$query_airo4);
						$ko_airo       = sqlsrv_fetch_array($stmt_airo4, SQLSRV_FETCH_ASSOC);
                    $query_airo3 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
											AND mesin = 'P3AR101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_01_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
											AND mesin = 'P3AR102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_02_PT
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_airo3    = sqlsrv_query($cona,$query_airo3);
						$pt_airo       = sqlsrv_fetch_array($stmt_airo3, SQLSRV_FETCH_ASSOC);
                    $query_airo2 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
											AND mesin = 'P3AR101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_01_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
											AND mesin = 'P3AR102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_02_KM
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_airo2    = sqlsrv_query($cona,$query_airo2);
						$km_airo             = sqlsrv_fetch_array($stmt_airo2, SQLSRV_FETCH_ASSOC);
                    $query_airo1 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
											AND mesin = 'P3AR101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_01_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
											AND mesin = 'P3AR102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS airo_02_LM
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_airo1    = sqlsrv_query($cona,$query_airo1);
						$lm_airo             = sqlsrv_fetch_array($stmt_airo1, SQLSRV_FETCH_ASSOC);
                    // Total airo
                    $query_mesin_airo = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3AR101'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_01_airo,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin = 'P3AR102'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_02_airo

						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                        $stmt_mesin_airo= sqlsrv_query($cona,$query_mesin_airo);
                        $sum_mesin_airo= sqlsrv_fetch_array($stmt_mesin_airo, SQLSRV_FETCH_ASSOC);
                        ?>
                    <td rowspan="2" align="left"><strong>AIRO</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php echo $lm_airo['airo_01_LM'];?></td>
                    <td align="center"><?php echo $km_airo['airo_01_KM'];?></td>
                    <td align="center"><?php echo $pt_airo['airo_01_PT'];?></td>
                    <td align="center"><?php echo $ko_airo['airo_01_KO'];?></td>
                    <td align="center"><?php echo $ap_airo['airo_01_AP'];?></td>
                    <td align="center"><?php echo $pa_airo['airo_01_PA'];?></td>
                    <td align="center"><?php echo $pm_airo['airo_01_PM'];?></td>
                    <td align="center"><?php echo $gt_airo['airo_01_GT'];?></td>
                    <td align="center"><?php echo $tg_airo['airo_01_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_airo['menit_01_airo'];?></td>
                </tr>
                <tr>
                    <td align="center">02</td>
                    <td align="center"><?php echo $lm_airo['airo_02_LM'];?></td>
                    <td align="center"><?php echo $km_airo['airo_02_KM'];?></td>
                    <td align="center"><?php echo $pt_airo['airo_02_PT'];?></td>
                    <td align="center"><?php echo $ko_airo['airo_02_KO'];?></td>
                    <td align="center"><?php echo $ap_airo['airo_02_AP'];?></td>
                    <td align="center"><?php echo $pa_airo['airo_02_PA'];?></td>
                    <td align="center"><?php echo $pm_airo['airo_02_PM'];?></td>
                    <td align="center"><?php echo $gt_airo['airo_02_GT'];?></td>
                    <td align="center"><?php echo $tg_airo['airo_02_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_airo['menit_02_airo'];?></td>
                </tr>
            <!-- End Airo -->
            <!-- Untuk Kolom Anti Piling1 -->
                <tr>
                    <?php 
					$query_ap9 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
											AND mesin LIKE 'P3TD201'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_01_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
											AND mesin LIKE 'P3TD202'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_02_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
											AND mesin LIKE 'P3TD203'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_03_LM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'LM'
											AND mesin LIKE 'P3TD204'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_04_LM

						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_ap9    = sqlsrv_query($cona,$query_ap9);
						$lm_ap             = sqlsrv_fetch_array($stmt_ap9, SQLSRV_FETCH_ASSOC);
                    $query_ap8 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
											AND mesin LIKE 'P3TD201'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_01_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
											AND mesin LIKE 'P3TD202'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_02_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
											AND mesin LIKE 'P3TD203'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_03_KM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KM'
											AND mesin LIKE 'P3TD204'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_04_KM

						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_ap8    = sqlsrv_query($cona,$query_ap8);
						$km_ap             = sqlsrv_fetch_array($stmt_ap8, SQLSRV_FETCH_ASSOC);
                    $query_ap7 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
											AND mesin LIKE 'P3TD201'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_01_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
											AND mesin LIKE 'P3TD202'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_02_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
											AND mesin LIKE 'P3TD203'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_03_PT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PT'
											AND mesin LIKE 'P3TD204'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_04_PT

						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_ap7    = sqlsrv_query($cona,$query_ap7);
						$pt_ap             = sqlsrv_fetch_array($stmt_ap7, SQLSRV_FETCH_ASSOC);
                    $query_ap6 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO'
											AND mesin LIKE 'P3TD201'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_01_KO,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO'
											AND mesin LIKE 'P3TD202'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_02_KO,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO'
											AND mesin LIKE 'P3TD203'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_03_KO,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'KO'
											AND mesin LIKE 'P3TD204'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_04_KO

						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_ap6    = sqlsrv_query($cona,$query_ap6);
						$ko_ap             = sqlsrv_fetch_array($stmt_ap6, SQLSRV_FETCH_ASSOC);
                    $query_ap5 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
											AND mesin = 'P3TD201'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_01_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
											AND mesin = 'P3TD202'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_02_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
											AND mesin = 'P3TD203'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_03_AP,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'AP'
											AND mesin = 'P3TD204'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_04_AP

						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_ap5    = sqlsrv_query($cona,$query_ap5);
						$ap_ap             = sqlsrv_fetch_array($stmt_ap5, SQLSRV_FETCH_ASSOC);
                    $query_ap4 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
											AND mesin = 'P3TD201'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_01_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
											AND mesin = 'P3TD202'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_02_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
											AND mesin = 'P3TD203'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_03_PA,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PA'
											AND mesin = 'P3TD204'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_04_PA

						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_ap4    = sqlsrv_query($cona,$query_ap4);
						$pa_ap             = sqlsrv_fetch_array($stmt_ap4, SQLSRV_FETCH_ASSOC);
                    $query_ap3 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
											AND mesin = 'P3TD201'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_01_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
											AND mesin = 'P3TD202'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_02_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
											AND mesin = 'P3TD203'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_03_PM,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'PM'
											AND mesin = 'P3TD204'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_04_PM

						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_ap3    = sqlsrv_query($cona,$query_ap3);
						$pm_ap             = sqlsrv_fetch_array($stmt_ap3, SQLSRV_FETCH_ASSOC);
                    $query_ap2 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
											AND mesin = 'P3TD201'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_01_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
											AND mesin = 'P3TD202'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_02_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
											AND mesin = 'P3TD203'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_03_GT,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'GT'
											AND mesin = 'P3TD204'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_04_GT

						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_ap2    = sqlsrv_query($cona,$query_ap2);
						$gt_ap             = sqlsrv_fetch_array($stmt_ap2, SQLSRV_FETCH_ASSOC);
                    $query_ap1 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
											AND mesin = 'P3TD201'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_01_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
											AND mesin = 'P3TD202'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_02_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
											AND mesin = 'P3TD203'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_03_TG,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN kode_stop = 'TG'
											AND mesin = 'P3TD204'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS ap_04_TG
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                        $stmt_ap1= sqlsrv_query($cona,$query_ap1);
                        $tg_ap= sqlsrv_fetch_array($stmt_ap1, SQLSRV_FETCH_ASSOC);
                    // Total ap
                    $query_mesin_ap1 = "SELECT
							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin LIKE 'P3TD204'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_ap_04,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin LIKE 'P3TD203'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_ap_03,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin LIKE 'P3TD202'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_ap_02,

							CONVERT(varchar(8), DATEADD(SECOND,
								CAST(ROUND(SUM(
									CASE
										WHEN mesin LIKE 'P3TD201'
										THEN durasi_jam_stop * 3600
										ELSE 0
									END
								), 0) AS int), 0), 108) AS menit_ap_01
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                        $stmt_mesin_ap1= sqlsrv_query($cona,$query_mesin_ap1);
                        $sum_mesin_ap= sqlsrv_fetch_array($stmt_mesin_ap1, SQLSRV_FETCH_ASSOC);
                    ?>
                    <td align="left"><strong>ANTI PILLING 01</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php echo $lm_ap['ap_01_LM'];?></td>
                    <td align="center"><?php echo $km_ap['ap_01_KM'];?></td>
                    <td align="center"><?php echo $pt_ap['ap_01_PT'];?></td>
                    <td align="center"><?php echo $ko_ap['ap_01_KO'];?></td>
                    <td align="center"><?php echo $ap_ap['ap_01_AP'];?></td>
                    <td align="center"><?php echo $pa_ap['ap_01_PA'];?></td>
                    <td align="center"><?php echo $pm_ap['ap_01_PM'];?></td>
                    <td align="center"><?php echo $gt_ap['ap_01_GT'];?></td>
                    <td align="center"><?php echo $tg_ap['ap_01_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_ap['menit_ap_01'];?></td>
                </tr>
            <!-- End Anti Piling1 -->
            <!-- Untuk Kolom Anti Piling2 -->
                <tr>
                    <td align="left"><strong>ANTI PILLING 02</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php echo $lm_ap['ap_02_LM'];?></td>
                    <td align="center"><?php echo $km_ap['ap_02_KM'];?></td>
                    <td align="center"><?php echo $pt_ap['ap_02_PT'];?></td>
                    <td align="center"><?php echo $ko_ap['ap_02_KO'];?></td>
                    <td align="center"><?php echo $ap_ap['ap_02_AP'];?></td>
                    <td align="center"><?php echo $pa_ap['ap_02_PA'];?></td>
                    <td align="center"><?php echo $pm_ap['ap_02_PM'];?></td>
                    <td align="center"><?php echo $gt_ap['ap_02_GT'];?></td>
                    <td align="center"><?php echo $tg_ap['ap_02_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_ap['menit_ap_02'];?></td>
                </tr>
            <!-- End Anti Piling2 -->
            <!-- Untuk Kolom Anti Piling3 -->
                <tr>
                    <td align="left"><strong>ANTI PILLING 03</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php echo $lm_ap['ap_03_LM'];?></td>
                    <td align="center"><?php echo $km_ap['ap_03_KM'];?></td>
                    <td align="center"><?php echo $pt_ap['ap_03_PT'];?></td>
                    <td align="center"><?php echo $ko_ap['ap_03_KO'];?></td>
                    <td align="center"><?php echo $ap_ap['ap_03_AP'];?></td>
                    <td align="center"><?php echo $pa_ap['ap_03_PA'];?></td>
                    <td align="center"><?php echo $pm_ap['ap_03_PM'];?></td>
                    <td align="center"><?php echo $gt_ap['ap_03_GT'];?></td>
                    <td align="center"><?php echo $tg_ap['ap_03_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_ap['menit_ap_03'];?></td>
                </tr>
            <!-- End Anti Piling3 -->
            <!-- Untuk Kolom Anti Piling4 -->
                <tr>
                    <td align="left"><strong>ANTI PILLING 04</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php echo $lm_ap['ap_04_LM'];?></td>
                    <td align="center"><?php echo $km_ap['ap_04_KM'];?></td>
                    <td align="center"><?php echo $pt_ap['ap_04_PT'];?></td>
                    <td align="center"><?php echo $ko_ap['ap_04_KO'];?></td>
                    <td align="center"><?php echo $ap_ap['ap_04_AP'];?></td>
                    <td align="center"><?php echo $pa_ap['ap_04_PA'];?></td>
                    <td align="center"><?php echo $pm_ap['ap_04_PM'];?></td>
                    <td align="center"><?php echo $gt_ap['ap_04_GT'];?></td>
                    <td align="center"><?php echo $tg_ap['ap_04_TG'];?></td>
                    <td align="center"><?php echo $sum_mesin_ap['menit_ap_04'];?></td>
                </tr>
            <!-- End Anti Piling4 -->
            <!-- Untuk Kolom Wet Sue -->
                <tr>
                    <?php
					$query_wet9 = "SELECT
							CONVERT(varchar(8),
								DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE 
											WHEN kode_stop = 'TG' 
												AND mesin IN ('P3SU201')
											THEN (durasi_jam_stop * 60) * 60
											ELSE 0
										END
									),0) AS int),
								0),
							108) AS wet_F_TG
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_wet9    = sqlsrv_query($cona,$query_wet9);
						$tg_wet             = sqlsrv_fetch_array($stmt_wet9, SQLSRV_FETCH_ASSOC);
					$query_wet8 = "SELECT
							CONVERT(varchar(8),
								DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE 
											WHEN kode_stop = 'GT'
												AND mesin IN ('P3SU201')
											THEN (durasi_jam_stop * 60) * 60
											ELSE 0
										END
									),0) AS int),
								0),
							108) AS wet_F_GT
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_wet8    = sqlsrv_query($cona,$query_wet8);
						$gt_wet             = sqlsrv_fetch_array($stmt_wet8, SQLSRV_FETCH_ASSOC);
					$query_wet7 = "SELECT
							CONVERT(varchar(8),
								DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE 
											WHEN kode_stop = 'PM'
												AND mesin IN ('P3SU201')
											THEN (durasi_jam_stop * 60) * 60
											ELSE 0
										END
									),0) AS int),
								0),
							108) AS wet_F_PM
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_wet7    = sqlsrv_query($cona,$query_wet7);
						$pm_wet             = sqlsrv_fetch_array($stmt_wet7, SQLSRV_FETCH_ASSOC);
                	$query_wet6 = "SELECT
							CONVERT(varchar(8),
								DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE 
											WHEN kode_stop = 'PA'
												AND mesin IN ('P3SU201')
											THEN (durasi_jam_stop * 60) * 60
											ELSE 0
										END
									),0) AS int),
								0),
							108) AS wet_F_PA
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_wet6    = sqlsrv_query($cona,$query_wet6);
						$pa_wet             = sqlsrv_fetch_array($stmt_wet6, SQLSRV_FETCH_ASSOC);
					$query_wet5 = "SELECT
							CONVERT(varchar(8),
								DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE 
											WHEN kode_stop = 'AP'
												AND mesin IN ('P3SU201')
											THEN (durasi_jam_stop * 60) * 60
											ELSE 0
										END
									),0) AS int),
								0),
							108) AS wet_F_AP
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_wet5    = sqlsrv_query($cona,$query_wet5);
						$ap_wet             = sqlsrv_fetch_array($stmt_wet5, SQLSRV_FETCH_ASSOC);
					$query_wet4 = "SELECT
							CONVERT(varchar(8),
								DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE 
											WHEN kode_stop = 'KO'
												AND mesin IN ('P3SU201')
											THEN (durasi_jam_stop * 60) * 60
											ELSE 0
										END
									),0) AS int),
								0),
							108) AS wet_F_KO
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_wet4    = sqlsrv_query($cona,$query_wet4);
						$ko_wet             = sqlsrv_fetch_array($stmt_wet4, SQLSRV_FETCH_ASSOC);
					$query_wet3 = "SELECT
							CONVERT(varchar(8),
								DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE 
											WHEN kode_stop = 'PT'
												AND mesin IN ('P3SU201')
											THEN (durasi_jam_stop * 60) * 60
											ELSE 0
										END
									),0) AS int),
								0),
							108) AS wet_F_PT
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_wet3    = sqlsrv_query($cona,$query_wet3);
						$pt_wet             = sqlsrv_fetch_array($stmt_wet3, SQLSRV_FETCH_ASSOC);
					$query_wet2 = "SELECT
							CONVERT(varchar(8),
								DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE 
											WHEN kode_stop = 'KM'
												AND mesin IN ('P3SU201')
											THEN (durasi_jam_stop * 60) * 60
											ELSE 0
										END
									),0) AS int),
								0),
							108) AS wet_F_KM
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_wet2    = sqlsrv_query($cona,$query_wet2);
						$km_wet             = sqlsrv_fetch_array($stmt_wet2, SQLSRV_FETCH_ASSOC);
                    $query_wet1 = "SELECT
							CONVERT(varchar(8),
								DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE 
											WHEN kode_stop = 'LM'
												AND mesin IN ('P3SU201')
											THEN (durasi_jam_stop * 60) * 60
											ELSE 0
										END
									),0) AS int),
								0),
							108) AS wet_F_LM
						FROM db_adm.tbl_stoppage
						WHERE dept ='BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
						$stmt_wet1    = sqlsrv_query($cona,$query_wet1);
						$lm_wet             = sqlsrv_fetch_array($stmt_wet1, SQLSRV_FETCH_ASSOC);
                    // Total Garuk
                    $query_mesin_wet = "SELECT
							CONVERT(varchar(8),
								DATEADD(SECOND,
									CAST(ROUND(SUM(
										CASE
											WHEN mesin IN ('P3SU201')
											THEN (durasi_jam_stop*60) * 60
											ELSE 0
										END
									),0) AS int),
								0),
							108) AS menit_wet_F
						FROM db_adm.tbl_stoppage
						WHERE dept = 'BRS'
						AND CONVERT(date, tgl_buat) BETWEEN '$tanggalAkhir_tbl3' AND '$tanggalAkhir_tbl3'
						AND ISNULL(kode_stop,'') <> ''";
                        $stmt_mesin_wet= sqlsrv_query($cona,$query_mesin_wet);
                        $sum_mesin_wet= sqlsrv_fetch_array($stmt_mesin_wet, SQLSRV_FETCH_ASSOC);
                    ?>
                    <td align="left"><strong>WET SUEDING</strong></td>
                    <td align="center">01</td>
                    <td align="center"><?php echo $lm_wet['wet_F_LM']; ?></td>
                    <td align="center"><?php echo $km_wet['wet_F_KM']; ?></td>
                    <td align="center"><?php echo $pt_wet['wet_F_PT']; ?></td>
                    <td align="center"><?php echo $ko_wet['wet_F_KO']; ?></td>
                    <td align="center"><?php echo $ap_wet['wet_F_AP']; ?></td>
                    <td align="center"><?php echo $pa_wet['wet_F_PA']; ?></td>
                    <td align="center"><?php echo $pm_wet['wet_F_PM']; ?></td>
                    <td align="center"><?php echo $gt_wet['wet_F_GT']; ?></td>
                    <td align="center"><?php echo $tg_wet['wet_F_TG']; ?></td>
                    <td align="center"><?php echo $sum_mesin_wet['menit_wet_F'];?></td>
                </tr>
            <!-- End Wet Sue -->
            <!-- Untuk Kolom Total -->
				<?php
				function hitungGrandTotal($data_mesin) {
					$total_menit = 0;

					foreach ($data_mesin as $row) {
						$jam   = isset($row['jam']) ? (int)$row['jam'] : 0;
						$menit = isset($row['menit']) ? (int)$row['menit'] : 0;
						$total_menit += ($jam * 60) + $menit;
					}

					$jam   = floor($total_menit / 60);
					$menit = $total_menit % 60;
					$detik = 0;

					return str_pad($jam, 2, '0', STR_PAD_LEFT) . ':' .
						   str_pad($menit, 2, '0', STR_PAD_LEFT) . ':' .
						   str_pad($detik, 2, '0', STR_PAD_LEFT);
				}
				
				function timeToMinutes($timeStr) {
					list($h, $m, $s) = explode(":", $timeStr);
					return ($h * 60) + $m + floor($s / 60);
				}

				function formatTotalTime($arr) {
					$total_minutes = 0;

					foreach ($arr as $timeStr) {
						if (!empty($timeStr) && $timeStr != "00:00:00") {
							$total_minutes += timeToMinutes($timeStr);
						}
					}

					$hours = floor($total_minutes / 60);
					$minutes = $total_minutes % 60;
					$seconds = 0;

					return str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" .
						   str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" .
						   str_pad($seconds, 2, "0", STR_PAD_LEFT);
				}

				// ====================
				// (isi sesuai hasil query masing-masing mesin)								
				// ====================
				$totalSemuaLM = [
					$lm_g['garuk_A_LM'],
					$lm_g['garuk_B_LM'],
					$lm_g['garuk_C_LM'],
					$lm_g['garuk_D_LM'],
					$lm_g['garuk_E_LM'],
					$lm_g['garuk_F_LM'],
					$lm_sisir['sisir_LM'],
					$lm_pb['pb_01_LM'],
					$lm_pb['pb_02_LM'],
					$lm_pb['pb_03_LM'],
					$lm_pb['pb_04_LM'],
					$lm_pb['pb_05_LM'],
					$lm_pb['pb_06_LM'],
					$lm_pb['pb_07_LM'],
					$lm_pb['pb_08_LM'],
					$lm['peach_01_LM'],
					$lm['peach_02_LM'],
					$lm['peach_03_LM'],
					$lm['peach_04_LM'],
					$lm['peach_05_LM'],
					$lm_airo['airo_01_LM'],
					$lm_airo['airo_02_LM'],									
					$lm_ap['ap_01_LM'],
					$lm_ap['ap_02_LM'],
					$lm_ap['ap_03_LM'],
					$lm_ap['ap_04_LM'],
					$lm_wet['wet_F_LM'],
					
				];
				
				$totalSemuaKM = [
					$km_g['garuk_A_KM'],
					$km_g['garuk_B_KM'],
					$km_g['garuk_C_KM'],
					$km_g['garuk_D_KM'],
					$km_g['garuk_E_KM'],
					$km_g['garuk_F_KM'],
					$km_sisir['sisir_KM'],
					$km_pb['pb_01_KM'],
					$km_pb['pb_02_KM'],
					$km_pb['pb_03_KM'],
					$km_pb['pb_04_KM'],
					$km_pb['pb_05_KM'],
					$km_pb['pb_06_KM'],
					$km_pb['pb_07_KM'],
					$km_pb['pb_08_KM'],
					$km['peach_01_KM'],
					$km['peach_02_KM'],
					$km['peach_03_KM'],
					$km['peach_04_KM'],
					$km['peach_05_KM'],
					$km_airo['airo_01_KM'],
					$km_airo['airo_02_KM'],								
					$km_ap['ap_01_KM'],
					$km_ap['ap_02_KM'],
					$km_ap['ap_03_KM'],
					$km_ap['ap_04_KM'],
					$km_wet['wet_F_KM'],
					
				];
				
				$totalSemuaPT = [
					$pt_g['garuk_A_PT'],
					$pt_g['garuk_B_PT'],
					$pt_g['garuk_C_PT'],
					$pt_g['garuk_D_PT'],
					$pt_g['garuk_E_PT'],
					$pt_g['garuk_F_PT'],
					$pt_sisir['sisir_PT'],
					$pt_pb['pb_01_PT'],
					$pt_pb['pb_02_PT'],
					$pt_pb['pb_03_PT'],
					$pt_pb['pb_04_PT'],
					$pt_pb['pb_05_PT'],
					$pt_pb['pb_06_PT'],
					$pt_pb['pb_07_PT'],
					$pt_pb['pb_08_PT'],
					$pt['peach_01_PT'],
					$pt['peach_02_PT'],
					$pt['peach_03_PT'],
					$pt['peach_04_PT'],
					$pt['peach_05_PT'],
					$pt_airo['airo_01_PT'],
					$pt_airo['airo_02_PT'],			
					$pt_ap['ap_01_PT'],
					$pt_ap['ap_02_PT'],
					$pt_ap['ap_03_PT'],
					$pt_ap['ap_04_PT'],
					$pt_wet['wet_F_PT'],
					
				];
				
				$totalSemuaKO = [
					$ko_g['garuk_A_KO'],
					$ko_g['garuk_B_KO'],
					$ko_g['garuk_C_KO'],
					$ko_g['garuk_D_KO'],
					$ko_g['garuk_E_KO'],
					$ko_g['garuk_F_KO'],
					$ko_sisir['sisir_KO'],
					$ko_pb['pb_01_KO'],
					$ko_pb['pb_02_KO'],
					$ko_pb['pb_03_KO'],
					$ko_pb['pb_04_KO'],
					$ko_pb['pb_05_KO'],
					$ko_pb['pb_06_KO'],
					$ko_pb['pb_07_KO'],
					$ko_pb['pb_08_KO'],
					$ko['peach_01_KO'],
					$ko['peach_02_KO'],
					$ko['peach_03_KO'],
					$ko['peach_04_KO'],
					$ko['peach_05_KO'],
					$ko_airo['airo_01_KO'],
					$ko_airo['airo_02_KO'],									
					$ko_ap['ap_01_KO'],
					$ko_ap['ap_02_KO'],
					$ko_ap['ap_03_KO'],
					$ko_ap['ap_04_KO'],
					$ko_wet['wet_F_KO'],
					
				];
				
				$totalSemuaAP = [
					$ap_g['garuk_A_AP'],
					$ap_g['garuk_B_AP'],
					$ap_g['garuk_C_AP'],
					$ap_g['garuk_D_AP'],
					$ap_g['garuk_E_AP'],
					$ap_g['garuk_F_AP'],
					$ap_sisir['sisir_AP'],
					$ap_pb['pb_01_AP'],
					$ap_pb['pb_02_AP'],
					$ap_pb['pb_03_AP'],
					$ap_pb['pb_04_AP'],
					$ap_pb['pb_05_AP'],
					$ap_pb['pb_06_AP'],
					$ap_pb['pb_07_AP'],
					$ap_pb['pb_08_AP'],
					$ap['peach_01_AP'],
					$ap['peach_02_AP'],
					$ap['peach_03_AP'],
					$ap['peach_04_AP'],
					$ap['peach_05_AP'],
					$ap_airo['airo_01_AP'],
					$ap_airo['airo_02_AP'],								
					$ap_ap['ap_01_AP'],
					$ap_ap['ap_02_AP'],
					$ap_ap['ap_03_AP'],
					$ap_ap['ap_04_AP'],
					$ap_wet['wet_F_AP'],
					
				];
				
				$totalSemuaPA = [
					$pa_g['garuk_A_PA'],
					$pa_g['garuk_B_PA'],
					$pa_g['garuk_C_PA'],
					$pa_g['garuk_D_PA'],
					$pa_g['garuk_E_PA'],
					$pa_g['garuk_F_PA'],
					$pa_sisir['sisir_PA'],
					$pa_pb['pb_01_PA'],
					$pa_pb['pb_02_PA'],
					$pa_pb['pb_03_PA'],
					$pa_pb['pb_04_PA'],
					$pa_pb['pb_05_PA'],
					$pa_pb['pb_06_PA'],
					$pa_pb['pb_07_PA'],
					$pa_pb['pb_08_PA'],
					$pa['peach_01_PA'],
					$pa['peach_02_PA'],
					$pa['peach_03_PA'],
					$pa['peach_04_PA'],
					$pa['peach_05_PA'],
					$pa_airo['airo_01_PA'],
					$pa_airo['airo_02_PA'],			
					$pa_ap['ap_01_PA'],
					$pa_ap['ap_02_PA'],
					$pa_ap['ap_03_PA'],
					$pa_ap['ap_04_PA'],
					$pa_wet['wet_F_PA'],
					
				];
				
				$totalSemuaPM = [
					$pm_g['garuk_A_PM'],
					$pm_g['garuk_B_PM'],
					$pm_g['garuk_C_PM'],
					$pm_g['garuk_D_PM'],
					$pm_g['garuk_E_PM'],
					$pm_g['garuk_F_PM'],
					$pm_sisir['sisir_PM'],
					$pm_pb['pb_01_PM'],
					$pm_pb['pb_02_PM'],
					$pm_pb['pb_03_PM'],
					$pm_pb['pb_04_PM'],
					$pm_pb['pb_05_PM'],
					$pm_pb['pb_06_PM'],
					$pm_pb['pb_07_PM'],
					$pm_pb['pb_08_PM'],
					$pm['peach_01_PM'],
					$pm['peach_02_PM'],
					$pm['peach_03_PM'],
					$pm['peach_04_PM'],
					$pm['peach_05_PM'],
					$pm_airo['airo_01_PM'],
					$pm_airo['airo_02_PM'],									
					$pm_ap['ap_01_PM'],
					$pm_ap['ap_02_PM'],
					$pm_ap['ap_03_PM'],
					$pm_ap['ap_04_PM'],
					$pm_wet['wet_F_PM'],
					
				];
				
				$totalSemuaGT = [
					$gt_g['garuk_A_GT'],
					$gt_g['garuk_B_GT'],
					$gt_g['garuk_C_GT'],
					$gt_g['garuk_D_GT'],
					$gt_g['garuk_E_GT'],
					$gt_g['garuk_F_GT'],
					$gt_sisir['sisir_GT'],
					$gt_airo['airo_01_GT'],
					$gt_airo['airo_02_GT'],
					$gt_pb['pb_01_GT'],
					$gt_pb['pb_02_GT'],
					$gt_pb['pb_03_GT'],
					$gt_pb['pb_04_GT'],
					$gt_pb['pb_05_GT'],
					$gt_pb['pb_06_GT'],
					$gt_pb['pb_07_GT'],
					$gt_pb['pb_08_GT'],
					$gt['peach_01_GT'],
					$gt['peach_02_GT'],
					$gt['peach_03_GT'],
					$gt['peach_04_GT'],
					$gt['peach_05_GT'],					
					$gt_ap['ap_01_GT'],
					$gt_ap['ap_02_GT'],
					$gt_ap['ap_03_GT'],
					$gt_ap['ap_04_GT'],
					$gt_wet['wet_F_GT'],
					
				];
				
				$totalSemuaTG = [
					$tg_g['garuk_A_TG'],
					$tg_g['garuk_B_TG'],
					$tg_g['garuk_C_TG'],
					$tg_g['garuk_D_TG'],
					$tg_g['garuk_E_TG'],
					$tg_g['garuk_F_TG'],
					$tg_sisir['sisir_TG'],
					$tg_airo['airo_01_TG'],
					$tg_airo['airo_02_TG'],
					$tg_pb['pb_01_TG'],
					$tg_pb['pb_02_TG'],
					$tg_pb['pb_03_TG'],
					$tg_pb['pb_04_TG'],
					$tg_pb['pb_05_TG'],
					$tg_pb['pb_06_TG'],
					$tg_pb['pb_07_TG'],
					$tg_pb['pb_08_TG'],
					$tg['peach_01_TG'],
					$tg['peach_02_TG'],
					$tg['peach_03_TG'],
					$tg['peach_04_TG'],
					$tg['peach_05_TG'],					
					$tg_ap['ap_01_TG'],
					$tg_ap['ap_02_TG'],
					$tg_ap['ap_03_TG'],
					$tg_ap['ap_04_TG'],
					$tg_wet['wet_F_TG'],
				];
				
				$totalSemua = [
					$sum_mesin_garuk['menit_garuk_A'],
					$sum_mesin_garuk['menit_garuk_B'],
					$sum_mesin_garuk['menit_garuk_C'],
					$sum_mesin_garuk['menit_garuk_D'],
					$sum_mesin_garuk['menit_garuk_E'],
					$sum_mesin_garuk['menit_garuk_F'],
					$sum_mesin_sisir['menit_sisir'],
					$sum_mesin_pb['menit_pb_01'],
					$sum_mesin_pb['menit_pb_02'],
					$sum_mesin_pb['menit_pb_03'],
					$sum_mesin_pb['menit_pb_04'],
					$sum_mesin_pb['menit_pb_05'],
					$sum_mesin_pb['menit_pb_06'],
					$sum_mesin_pb['menit_pb_07'],
					$sum_mesin_pb['menit_pb_08'],
					$sum_mesin_peach['menit_peach_01'],
					$sum_mesin_peach['menit_peach_02'],
					$sum_mesin_peach['menit_peach_03'],
					$sum_mesin_peach['menit_peach_04'],
					$sum_mesin_peach['menit_peach_05'],
					$sum_mesin_airo['menit_01_airo'],
					$sum_mesin_airo['menit_02_airo'],
					$sum_mesin_ap['menit_ap_01'],
					$sum_mesin_ap['menit_ap_02'],
					$sum_mesin_ap['menit_ap_03'],
					$sum_mesin_ap['menit_ap_04'],
					$sum_mesin_wet['menit_wet_F'],
					
				];
				
				?>

                <tr>                    
                    <td colspan="2" align="center"><strong>GRAND TOTAL</strong></td>
                    <td align="center"><?= formatTotalTime($totalSemuaLM) ?></td>
                    <td align="center"><?= formatTotalTime($totalSemuaKM) ?></td>
                    <td align="center"><?= formatTotalTime($totalSemuaPT) ?></td>
                    <td align="center"><?= formatTotalTime($totalSemuaKO) ?></td>
                    <td align="center"><?= formatTotalTime($totalSemuaAP) ?></td>
                    <td align="center"><?= formatTotalTime($totalSemuaPA) ?></td>
                    <td align="center"><?= formatTotalTime($totalSemuaPM) ?></td>
                    <td align="center"><?= formatTotalTime($totalSemuaGT) ?></td>
                    <td align="center"><?= formatTotalTime($totalSemuaTG) ?></td>
                    <td align="center"><?= formatTotalTime($totalSemua) ?></td>
                </tr>
            <!-- End Total -->
            </tbody>
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
                             $query_masuk = "WITH base AS (
												SELECT 
													pd.CODE AS DEMANDNO,
													p.PRODUCTIONORDERCODE,
													p.GROUPSTEPNUMBER, 
													p.OPERATIONCODE, 
													m.TOTALPRIMARYQUANTITY,
													ROW_NUMBER() OVER (
														PARTITION BY pd.CODE 
														ORDER BY p.GROUPSTEPNUMBER ASC
													) AS rn_demand
												FROM PRODUCTIONPROGRESS p
												LEFT JOIN PRODUCTIONORDER m 
													ON m.CODE = p.PRODUCTIONORDERCODE
												LEFT JOIN PRODUCTIONRESERVATION pr 
													ON m.COMPANYCODE = pr.COMPANYCODE
													AND m.CODE = pr.PRODUCTIONORDERCODE
												LEFT JOIN PRODUCTIONDEMAND pd 
													ON pr.COMPANYCODE = pd.COMPANYCODE
													AND pr.ORDERCODE = pd.CODE
												WHERE
													TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) 
														BETWEEN TIMESTAMP('$tglAwal_tbl4') AND TIMESTAMP('$tglAkhir_tbl4')
													AND p.OPERATIONCODE IN (
														'RSE1','RSE2','RSE3','RSE4','RSE5',
														'COM1','COM2','SHR1','SHR2','SHR3','SHR4','SHR5',
														'TDR1','SUE1','SUE2','SUE3','SUE4',
														'AIR1','POL1','WET1','WET2','WET3','WET4'
													)
													AND p.PROGRESSTEMPLATECODE = 'S01'
											),
											ranked AS (
												SELECT 
													b.*,
													ROW_NUMBER() OVER (
														PARTITION BY b.OPERATIONCODE, b.PRODUCTIONORDERCODE
														ORDER BY b.GROUPSTEPNUMBER, b.DEMANDNO
													) AS qty_row
												FROM base b
												WHERE b.rn_demand = 1
											),
											valid AS (
												SELECT 
													r.PRODUCTIONORDERCODE, 
													r.DEMANDNO, 
													r.OPERATIONCODE, 
													CASE 
														WHEN r.qty_row = 1 THEN r.TOTALPRIMARYQUANTITY 
														ELSE 0
													END AS TOTALPRIMARYQUANTITY
												FROM ranked r
												WHERE EXISTS (
													SELECT 1
													FROM PRODUCTIONDEMANDSTEP ds
													WHERE ds.PRODUCTIONORDERCODE = r.PRODUCTIONORDERCODE
													  AND ds.PLANNEDOPERATIONCODE = r.OPERATIONCODE
													  AND NOT EXISTS (
														  SELECT 1
														  FROM PRODUCTIONDEMANDSTEP ds_prev
														  WHERE ds_prev.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
															AND ds_prev.PLANNEDOPERATIONCODE IN (
																'RSE1','RSE2','RSE3','RSE4','RSE5','COM1','COM2',
																'SHR1','SHR2','SHR3','SHR4','SHR5','TDR1',
																'SUE1','SUE2','SUE3','SUE4','AIR1','POL1',
																'WET1','WET2','WET3','WET4'
															)
															AND ds_prev.STEPNUMBER = (
																SELECT MAX(ds2.STEPNUMBER)
																FROM PRODUCTIONDEMANDSTEP ds2
																WHERE ds2.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
																  AND ds2.STEPNUMBER < ds.STEPNUMBER
															)
													  )
												)
											)
											SELECT  
												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE1' THEN DEMANDNO END) AS RSE1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE2' THEN DEMANDNO END) AS RSE2_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE2_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE3' THEN DEMANDNO END) AS RSE3_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE3_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE4' THEN DEMANDNO END) AS RSE4_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE4_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE5' THEN DEMANDNO END) AS RSE5_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE5' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE5_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'COM1' THEN DEMANDNO END) AS COM1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'COM1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS COM1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'COM2' THEN DEMANDNO END) AS COM2_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'COM2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS COM2_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR1' THEN DEMANDNO END) AS SHR1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR2' THEN DEMANDNO END) AS SHR2_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR2_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR3' THEN DEMANDNO END) AS SHR3_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR3_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR4' THEN DEMANDNO END) AS SHR4_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR4_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR5' THEN DEMANDNO END) AS SHR5_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR5' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR5_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'TDR1' THEN DEMANDNO END) AS TDR1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'TDR1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS TDR1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE1' THEN DEMANDNO END) AS SUE1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE2' THEN DEMANDNO END) AS SUE2_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE2_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE3' THEN DEMANDNO END) AS SUE3_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE3_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE4' THEN DEMANDNO END) AS SUE4_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE4_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'AIR1' THEN DEMANDNO END) AS AIR1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'AIR1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS AIR1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'POL1' THEN DEMANDNO END) AS POL1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'POL1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS POL1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET1' THEN DEMANDNO END) AS WET1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET2' THEN DEMANDNO END) AS WET2_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET2_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET3' THEN DEMANDNO END) AS WET3_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET3_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET4' THEN DEMANDNO END) AS WET4_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET4_QUANTITY
											FROM valid;
												";
                            $result_masuk = db2_exec($conn2, $query_masuk);
                            $row_masuk = db2_fetch_assoc($result_masuk);
                            ?>
                            <td><strong>RSE1</strong></td>
                            <td style="text-align:center;"><?= $row_masuk['RSE1_DEMAND']; ?></td>
                            <td style="text-align:center;"><?= number_format($row_masuk['RSE1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                            
                            <td><strong>RSE2</strong></td>
                            <td style="text-align:center;"><?= $row_masuk['RSE2_DEMAND']; ?></td>
                            <td style="text-align:center;"><?= number_format($row_masuk['RSE2_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>                            
                            <td><strong>RSE3</strong></td>
                            <td style="text-align:center;"><?= $row_masuk['RSE3_DEMAND']; ?></td>
                            <td style="text-align:center;"><?= number_format($row_masuk['RSE3_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>                            
                            <td><strong>RSE4</strong></td>
                            <td style="text-align:center;"><?= $row_masuk['RSE4_DEMAND']; ?></td>
                            <td style="text-align:center;"><?= number_format($row_masuk['RSE4_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>                            
                            <td><strong>RSE5</strong></td>
                            <td style="text-align:center;"><?= $row_masuk['RSE5_DEMAND']; ?></td>
                            <td style="text-align:center;"><?= number_format($row_masuk['RSE5_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>                            
                            <td><strong>COM1</strong></td>
                            <td style="text-align:center;"><?= $row_masuk['COM1_DEMAND']; ?></td>
                            <td style="text-align:center;"><?= number_format($row_masuk['COM1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>                            
                            <td><strong>COM2</strong></td>
                            <td style="text-align:center;"><?= $row_masuk['COM2_DEMAND']; ?></td>
                            <td style="text-align:center;"><?= number_format($row_masuk['COM2_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>                            
                            <td><strong>SHR1</strong></td>
                            <td style="text-align:center;"><?= $row_masuk['SHR1_DEMAND']; ?></td>
                            <td style="text-align:center;"><?= number_format($row_masuk['SHR1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>SHR2</strong></td>
                            <td style="text-align:center;"><?= $row_masuk['SHR2_DEMAND']; ?></td>
                            <td style="text-align:center;"><?= number_format($row_masuk['SHR2_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SHR3</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['SHR3_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['SHR3_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SHR4</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['SHR4_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['SHR4_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SHR5</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['SHR5_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['SHR5_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>TDR1</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['TDR1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['TDR1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SUE1</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['SUE1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['SUE1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SUE2</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['SUE2_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['SUE2_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SUE3</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['SUE3_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['SUE3_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SUE4</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['SUE4_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['SUE4_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>AIR1</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['AIR1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['AIR1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>POL1</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['POL1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['POL1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>WET1</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['WET1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['WET1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>WET2</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['WET2_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['WET2_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>WET3</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['WET3_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['WET3_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>WET4</strong></td>
                          <td style="text-align:center;"><?= $row_masuk['WET4_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_masuk['WET4_QUANTITY'],2); ?></td>
                        </tr>
                        <tr style="font-weight:bold; background-color:yellow;">
                            <?php
                            // Hitung total JUMLAHKK dan TOTAL_QTY dari semua proses di atas (kecuali PERBAIKAN)
                            $total_jumlahkk = 
								$row_masuk['RSE1_DEMAND']+
								$row_masuk['RSE2_DEMAND']+
								$row_masuk['RSE3_DEMAND']+
								$row_masuk['RSE4_DEMAND']+
								$row_masuk['RSE5_DEMAND']+
								$row_masuk['COM1_DEMAND']+
								$row_masuk['COM2_DEMAND']+
								$row_masuk['SHR1_DEMAND']+
								$row_masuk['SHR2_DEMAND']+
								$row_masuk['SHR3_DEMAND']+
								$row_masuk['SHR4_DEMAND']+
								$row_masuk['SHR5_DEMAND']+
								$row_masuk['TDR1_DEMAND']+
								$row_masuk['SUE1_DEMAND']+
								$row_masuk['SUE2_DEMAND']+
								$row_masuk['SUE3_DEMAND']+
								$row_masuk['SUE4_DEMAND']+
								$row_masuk['AIR1_DEMAND']+
								$row_masuk['POL1_DEMAND']+
								$row_masuk['WET1_DEMAND']+
								$row_masuk['WET2_DEMAND']+
								$row_masuk['WET3_DEMAND']+
								$row_masuk['WET4_DEMAND']							

								;
                            $total_qty =
								$row_masuk['RSE1_QUANTITY']+
								$row_masuk['RSE2_QUANTITY']+
								$row_masuk['RSE3_QUANTITY']+
								$row_masuk['RSE4_QUANTITY']+
								$row_masuk['RSE5_QUANTITY']+
								$row_masuk['COM1_QUANTITY']+
								$row_masuk['COM2_QUANTITY']+
								$row_masuk['SHR1_QUANTITY']+
								$row_masuk['SHR2_QUANTITY']+
								$row_masuk['SHR3_QUANTITY']+
								$row_masuk['SHR4_QUANTITY']+
								$row_masuk['SHR5_QUANTITY']+
								$row_masuk['TDR1_QUANTITY']+
								$row_masuk['SUE1_QUANTITY']+
								$row_masuk['SUE2_QUANTITY']+
								$row_masuk['SUE3_QUANTITY']+
								$row_masuk['SUE4_QUANTITY']+
								$row_masuk['AIR1_QUANTITY']+
								$row_masuk['POL1_QUANTITY']+
								$row_masuk['WET1_QUANTITY']+
								$row_masuk['WET2_QUANTITY']+
								$row_masuk['WET3_QUANTITY']+
								$row_masuk['WET4_QUANTITY'];

                            ?>

                            <td style="text-align:center;">TOTAL MASUK</td>
                            <td style="text-align:center;"><?= $total_jumlahkk ?></td>
                            <td style="text-align:center;"><?= number_format($total_qty,2) ?></td>
                        </tr>
                </table>	
	  </td>
      <td width="5%">&nbsp;</td>
      <td width="30%" align="left" valign="top">		  
		<table border="1" class="table-list1" width="100%">
                        <tr>
                            <td colspan="3" style="text-align:center;"><strong>QUANTITY KELUAR</strong></td>
                        </tr>
                        <tr>
							<?php                          
                             $query_keluar = "WITH base AS (
									SELECT 
										pd.PRODUCTIONDEMANDCODE  AS DEMANDNO,
										p.PRODUCTIONORDERCODE,
										p.GROUPSTEPNUMBER, 
										p.OPERATIONCODE, 
										m.TOTALPRIMARYQUANTITY        
									FROM PRODUCTIONPROGRESS p
									LEFT JOIN PRODUCTIONORDER m 
										ON m.CODE = p.PRODUCTIONORDERCODE
									LEFT JOIN ( SELECT DISTINCT pd1.CODE AS PRODUCTIONDEMANDCODE, pr.PRODUCTIONORDERCODE,pr.COMPANYCODE    FROM PRODUCTIONRESERVATION pr 
									LEFT JOIN PRODUCTIONDEMAND pd1 
										ON pr.COMPANYCODE = pd1.COMPANYCODE
										AND pr.ORDERCODE = pd1.CODE
									INNER JOIN PRODUCTIONDEMANDSTEP ds
										ON ds.PRODUCTIONORDERCODE = pr.PRODUCTIONORDERCODE ) pd ON pd.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND pd.COMPANYCODE =p.COMPANYCODE
									WHERE
										TIMESTAMP(p.PROGRESSENDDATE, p.PROGRESSENDTIME) 
											BETWEEN TIMESTAMP('$tglAwal_tbl4') AND TIMESTAMP('$tglAkhir_tbl4')
										AND p.OPERATIONCODE IN (
											'RSE1','RSE2','RSE3','RSE4','RSE5',
											'COM1','COM2','SHR1','SHR2','SHR3','SHR4','SHR5',
											'TDR1','SUE1','SUE2','SUE3','SUE4',
											'AIR1','POL1','WET1','WET2','WET3','WET4'
										)
										AND p.PROGRESSTEMPLATECODE = 'E01'
										-- AND p.OPERATIONCODE = 'SHR3'
								) ,
								ranked AS (
									SELECT 
										b.*,
										ROW_NUMBER() OVER (
											PARTITION BY b.OPERATIONCODE, b.PRODUCTIONORDERCODE
											ORDER BY b.GROUPSTEPNUMBER, b.DEMANDNO
										) AS qty_row
									FROM base b
								),
								valid AS (
									SELECT 
										r.PRODUCTIONORDERCODE, 
										r.DEMANDNO, 
										r.OPERATIONCODE,
										r.GROUPSTEPNUMBER,
										CASE 
										WHEN r.qty_row = 1 THEN r.TOTALPRIMARYQUANTITY 
										ELSE 0
										END AS TOTALPRIMARYQUANTITY
									FROM ranked r
									WHERE EXISTS (
										SELECT 1
										FROM PRODUCTIONDEMANDSTEP ds
										WHERE ds.PRODUCTIONORDERCODE = r.PRODUCTIONORDERCODE
										  AND ds.PLANNEDOPERATIONCODE = r.OPERATIONCODE
										  AND ds.GROUPSTEPNUMBER = r.GROUPSTEPNUMBER
										  AND NOT EXISTS (
											  SELECT 1
											  FROM PRODUCTIONDEMANDSTEP ds_next
											  WHERE ds_next.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
												AND ds_next.PLANNEDOPERATIONCODE IN (
													'RSE1','RSE2','RSE3','RSE4','RSE5','COM1','COM2',
													'SHR1','SHR2','SHR3','SHR4','SHR5','TDR1',
													'SUE1','SUE2','SUE3','SUE4','AIR1','POL1',
													'WET1','WET2','WET3','WET4'
												)
												AND ds_next.STEPNUMBER = (
													SELECT MIN(ds2.STEPNUMBER)
													FROM PRODUCTIONDEMANDSTEP ds2
													WHERE ds2.PRODUCTIONORDERCODE = ds.PRODUCTIONORDERCODE
													  AND ds2.STEPNUMBER > ds.STEPNUMBER
												)
										  )
									)
								)
								SELECT
												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE1' THEN DEMANDNO END) AS RSE1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE2' THEN DEMANDNO END) AS RSE2_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE2_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE3' THEN DEMANDNO END) AS RSE3_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE3_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE4' THEN DEMANDNO END) AS RSE4_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE4_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE5' THEN DEMANDNO END) AS RSE5_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE5' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE5_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'COM1' THEN DEMANDNO END) AS COM1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'COM1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS COM1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'COM2' THEN DEMANDNO END) AS COM2_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'COM2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS COM2_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR1' THEN DEMANDNO END) AS SHR1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR2' THEN DEMANDNO END) AS SHR2_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR2_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR3' THEN DEMANDNO END) AS SHR3_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR3_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR4' THEN DEMANDNO END) AS SHR4_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR4_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR5' THEN DEMANDNO END) AS SHR5_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR5' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR5_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'TDR1' THEN DEMANDNO END) AS TDR1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'TDR1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS TDR1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE1' THEN DEMANDNO END) AS SUE1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE2' THEN DEMANDNO END) AS SUE2_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE2_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE3' THEN DEMANDNO END) AS SUE3_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE3_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE4' THEN DEMANDNO END) AS SUE4_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE4_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'AIR1' THEN DEMANDNO END) AS AIR1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'AIR1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS AIR1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'POL1' THEN DEMANDNO END) AS POL1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'POL1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS POL1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET1' THEN DEMANDNO END) AS WET1_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET1_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET2' THEN DEMANDNO END) AS WET2_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET2_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET3' THEN DEMANDNO END) AS WET3_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET3_QUANTITY,

												COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET4' THEN DEMANDNO END) AS WET4_DEMAND,
												ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET4_QUANTITY
								FROM valid;";
                            $result_keluar = db2_exec($conn2, $query_keluar);
                            $row_keluar = db2_fetch_assoc($result_keluar);
                            ?>
                            <td width="24%"><strong>JENIS PROSES</strong></td>

                            <td width="26%"><strong>JUMLAH KK</strong></td>
                            <td width="24%"><strong>QUANTITY</strong></td>
                        </tr>
                        <tr>
                          <td><strong>RSE1</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['RSE1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['RSE1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>RSE2</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['RSE2_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['RSE2_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>RSE3</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['RSE3_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['RSE3_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>RSE4</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['RSE4_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['RSE4_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>RSE5</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['RSE5_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['RSE5_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>COM1</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['COM1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['COM1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>COM2</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['COM2_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['COM2_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SHR1</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['SHR1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['SHR1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SHR2</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['SHR2_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['SHR2_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SHR3</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['SHR3_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['SHR3_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SHR4</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['SHR4_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['SHR4_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SHR5</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['SHR5_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['SHR5_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>TDR1</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['TDR1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['TDR1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SUE1</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['SUE1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['SUE1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SUE2</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['SUE2_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['SUE2_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SUE3</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['SUE3_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['SUE3_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>SUE4</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['SUE4_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['SUE4_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>AIR1</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['AIR1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['AIR1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>POL1</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['POL1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['POL1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>WET1</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['WET1_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['WET1_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>WET2</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['WET2_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['WET2_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>WET3</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['WET3_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['WET3_QUANTITY'],2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>WET4</strong></td>
                          <td style="text-align:center;"><?= $row_keluar['WET4_DEMAND']; ?></td>
                          <td style="text-align:center;"><?= number_format($row_keluar['WET4_QUANTITY'],2); ?></td>
                        </tr>
                        <tr style="font-weight:bold; background-color:yellow;">
                            <?php
                            // Hitung total JUMLAHKK dan TOTAL_QTY dari semua proses di atas (kecuali PERBAIKAN)
                            $total_jumlahkkKeluar = 
								$row_keluar['RSE1_DEMAND']+
								$row_keluar['RSE2_DEMAND']+
								$row_keluar['RSE3_DEMAND']+
								$row_keluar['RSE4_DEMAND']+
								$row_keluar['RSE5_DEMAND']+
								$row_keluar['COM1_DEMAND']+
								$row_keluar['COM2_DEMAND']+
								$row_keluar['SHR1_DEMAND']+
								$row_keluar['SHR2_DEMAND']+
								$row_keluar['SHR3_DEMAND']+
								$row_keluar['SHR4_DEMAND']+
								$row_keluar['SHR5_DEMAND']+
								$row_keluar['TDR1_DEMAND']+
								$row_keluar['SUE1_DEMAND']+
								$row_keluar['SUE2_DEMAND']+
								$row_keluar['SUE3_DEMAND']+
								$row_keluar['SUE4_DEMAND']+
								$row_keluar['AIR1_DEMAND']+
								$row_keluar['POL1_DEMAND']+
								$row_keluar['WET1_DEMAND']+
								$row_keluar['WET2_DEMAND']+
								$row_keluar['WET3_DEMAND']+
								$row_keluar['WET4_DEMAND']							
								;
                            $total_qtyKeluar =
								$row_keluar['RSE1_QUANTITY']+
								$row_keluar['RSE2_QUANTITY']+
								$row_keluar['RSE3_QUANTITY']+
								$row_keluar['RSE4_QUANTITY']+
								$row_keluar['RSE5_QUANTITY']+
								$row_keluar['COM1_QUANTITY']+
								$row_keluar['COM2_QUANTITY']+
								$row_keluar['SHR1_QUANTITY']+
								$row_keluar['SHR2_QUANTITY']+
								$row_keluar['SHR3_QUANTITY']+
								$row_keluar['SHR4_QUANTITY']+
								$row_keluar['SHR5_QUANTITY']+
								$row_keluar['TDR1_QUANTITY']+
								$row_keluar['SUE1_QUANTITY']+
								$row_keluar['SUE2_QUANTITY']+
								$row_keluar['SUE3_QUANTITY']+
								$row_keluar['SUE4_QUANTITY']+
								$row_keluar['AIR1_QUANTITY']+
								$row_keluar['POL1_QUANTITY']+
								$row_keluar['WET1_QUANTITY']+
								$row_keluar['WET2_QUANTITY']+
								$row_keluar['WET3_QUANTITY']+
								$row_keluar['WET4_QUANTITY'];
                            ?>

                            <td style="text-align:center;">TOTAL</td>
                            <td style="text-align:center;"><?= $total_jumlahkkKeluar ?></td>
                            <td style="text-align:center;"><?= number_format($total_qtyKeluar,2) ?></td>
                            <!-- <td style="text-align:center;">&nbsp;</td> -->                        </tr>
<!--            </tr>-->
        </table>
	  </td>
      <td width="5%">&nbsp;</td>
      <td width="30%" align="left" valign="top">
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
			<?php                          
                             $query_sisa = "WITH base AS (
											SELECT 
												pd.PRODUCTIONDEMANDCODE AS DEMANDNO,
												p.PRODUCTIONORDERCODE,
												p.GROUPSTEPNUMBER, 
												p.OPERATIONCODE, 
												m.TOTALPRIMARYQUANTITY
											FROM PRODUCTIONPROGRESS p
											LEFT JOIN PRODUCTIONORDER m 
												ON m.CODE = p.PRODUCTIONORDERCODE
											LEFT JOIN ( SELECT DISTINCT pd1.CODE AS PRODUCTIONDEMANDCODE, pr.PRODUCTIONORDERCODE,pr.COMPANYCODE    FROM PRODUCTIONRESERVATION pr 
											LEFT JOIN PRODUCTIONDEMAND pd1 
												ON pr.COMPANYCODE = pd1.COMPANYCODE
												AND pr.ORDERCODE = pd1.CODE
											INNER JOIN PRODUCTIONDEMANDSTEP ds
												ON ds.PRODUCTIONORDERCODE = pr.PRODUCTIONORDERCODE ) pd ON pd.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND pd.COMPANYCODE =p.COMPANYCODE
											WHERE
												TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) 
													BETWEEN TIMESTAMP('$tglAwal_tbl4') AND TIMESTAMP('$tglAkhir_tbl4')
												AND p.OPERATIONCODE IN (
													'RSE1','RSE2','RSE3','RSE4','RSE5',
													'COM1','COM2','SHR1','SHR2','SHR3','SHR4','SHR5',
													'TDR1','SUE1','SUE2','SUE3','SUE4',
													'AIR1','POL1','WET1','WET2','WET3','WET4'
												)
												AND p.PROGRESSTEMPLATECODE = 'S01'
										),
										valid AS (
											SELECT 
												r.PRODUCTIONORDERCODE, 
												r.DEMANDNO, 
												r.OPERATIONCODE,
												r.TOTALPRIMARYQUANTITY
											FROM base r
											WHERE 
												EXISTS (
													SELECT 1
													FROM PRODUCTIONPROGRESS pe
													LEFT JOIN PRODUCTIONORDER m 
													ON m.CODE = pe.PRODUCTIONORDERCODE
													LEFT JOIN ( SELECT DISTINCT pd1.CODE AS PRODUCTIONDEMANDCODE, pr.PRODUCTIONORDERCODE,pr.COMPANYCODE    FROM PRODUCTIONRESERVATION pr 
													LEFT JOIN PRODUCTIONDEMAND pd1 
														ON pr.COMPANYCODE = pd1.COMPANYCODE
														AND pr.ORDERCODE = pd1.CODE
													INNER JOIN PRODUCTIONDEMANDSTEP ds
														ON ds.PRODUCTIONORDERCODE = pr.PRODUCTIONORDERCODE ) pd ON pd.PRODUCTIONORDERCODE = pe.PRODUCTIONORDERCODE AND pd.COMPANYCODE =pe.COMPANYCODE
													WHERE pe.PRODUCTIONORDERCODE   = r.PRODUCTIONORDERCODE
													  AND pe.OPERATIONCODE         = r.OPERATIONCODE
													  AND pd.PRODUCTIONDEMANDCODE  = r.DEMANDNO
													  AND pe.GROUPSTEPNUMBER       = r.GROUPSTEPNUMBER
													  AND pe.PROGRESSTEMPLATECODE  = 'E01'
													  AND TIMESTAMP(pe.PROGRESSENDDATE, pe.PROGRESSENDTIME) 
															> TIMESTAMP('$tglAkhir_tbl4')
												) OR   
												NOT EXISTS (
													SELECT 1
													FROM PRODUCTIONPROGRESS pe
													LEFT JOIN PRODUCTIONORDER m 
													ON m.CODE = pe.PRODUCTIONORDERCODE
													LEFT JOIN ( SELECT DISTINCT pd1.CODE AS PRODUCTIONDEMANDCODE, pr.PRODUCTIONORDERCODE,pr.COMPANYCODE    FROM PRODUCTIONRESERVATION pr 
													LEFT JOIN PRODUCTIONDEMAND pd1 
														ON pr.COMPANYCODE = pd1.COMPANYCODE
														AND pr.ORDERCODE = pd1.CODE
													INNER JOIN PRODUCTIONDEMANDSTEP ds
														ON ds.PRODUCTIONORDERCODE = pr.PRODUCTIONORDERCODE ) pd ON pd.PRODUCTIONORDERCODE = pe.PRODUCTIONORDERCODE AND pd.COMPANYCODE =pe.COMPANYCODE
													WHERE pe.PRODUCTIONORDERCODE   = r.PRODUCTIONORDERCODE
													  AND pe.OPERATIONCODE         = r.OPERATIONCODE
													  AND pd.PRODUCTIONDEMANDCODE  = r.DEMANDNO
													  AND pe.GROUPSTEPNUMBER       = r.GROUPSTEPNUMBER
													  AND pe.PROGRESSTEMPLATECODE  = 'E01'
													  AND TIMESTAMP(pe.PROGRESSENDDATE, pe.PROGRESSENDTIME) 
															< TIMESTAMP('$tglAkhir_tbl4')
												)        
										)
										SELECT 
										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE1' THEN DEMANDNO END) AS RSE1_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE1_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE2' THEN DEMANDNO END) AS RSE2_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE2_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE3' THEN DEMANDNO END) AS RSE3_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE3_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE4' THEN DEMANDNO END) AS RSE4_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE4_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'RSE5' THEN DEMANDNO END) AS RSE5_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'RSE5' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS RSE5_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'COM1' THEN DEMANDNO END) AS COM1_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'COM1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS COM1_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'COM2' THEN DEMANDNO END) AS COM2_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'COM2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS COM2_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR1' THEN DEMANDNO END) AS SHR1_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR1_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR2' THEN DEMANDNO END) AS SHR2_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR2_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR3' THEN DEMANDNO END) AS SHR3_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR3_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR4' THEN DEMANDNO END) AS SHR4_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR4_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SHR5' THEN DEMANDNO END) AS SHR5_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'SHR5' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SHR5_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'TDR1' THEN DEMANDNO END) AS TDR1_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'TDR1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS TDR1_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE1' THEN DEMANDNO END) AS SUE1_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE1_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE2' THEN DEMANDNO END) AS SUE2_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE2_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE3' THEN DEMANDNO END) AS SUE3_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE3_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'SUE4' THEN DEMANDNO END) AS SUE4_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'SUE4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS SUE4_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'AIR1' THEN DEMANDNO END) AS AIR1_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'AIR1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS AIR1_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'POL1' THEN DEMANDNO END) AS POL1_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'POL1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS POL1_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET1' THEN DEMANDNO END) AS WET1_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET1' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET1_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET2' THEN DEMANDNO END) AS WET2_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET2' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET2_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET3' THEN DEMANDNO END) AS WET3_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET3' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET3_QUANTITY,

										COUNT(DISTINCT CASE WHEN OPERATIONCODE = 'WET4' THEN DEMANDNO END) AS WET4_DEMAND,
										ROUND(SUM(CASE WHEN OPERATIONCODE = 'WET4' THEN TOTALPRIMARYQUANTITY ELSE 0 END),2) AS WET4_QUANTITY
										FROM valid;
";
                            $result_sisa = db2_exec($conn2, $query_sisa);
                            $row_sisa = db2_fetch_assoc($result_sisa);
                            ?>	
              <td><strong>RSE1</strong></td>
              
				  <td style="text-align:center;"><?= $row_sisa['RSE1_DEMAND']; ?></td>
				  <td style="text-align:center;"><?= number_format($row_sisa['RSE1_QUANTITY'],2); ?></td>
			  
            </tr>
            <tr>
			  <td><strong>RSE2</strong></td>
			  
				  <td style="text-align:center;"><?= $row_sisa['RSE2_DEMAND']; ?></td>
				  <td style="text-align:center;"><?= number_format($row_sisa['RSE2_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>RSE3</strong></td>
			  
				  <td style="text-align:center;"><?= $row_sisa['RSE3_DEMAND']; ?></td>
				  <td style="text-align:center;"><?= number_format($row_sisa['RSE3_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>RSE4</strong></td>
			  
				  <td style="text-align:center;"><?= $row_sisa['RSE4_DEMAND']; ?></td>
				  <td style="text-align:center;"><?= number_format($row_sisa['RSE4_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>RSE5</strong></td>
			  
				  <td style="text-align:center;"><?= $row_sisa['RSE5_DEMAND']; ?></td>
				  <td style="text-align:center;"><?= number_format($row_sisa['RSE5_QUANTITY'],2); ?></td>
			  
			</tr>

			<!-- ========================= COM ========================= -->
			<tr>
			  <td><strong>COM1</strong></td>
			  
				  <td style="text-align:center;"><?= $row_sisa['COM1_DEMAND']; ?></td>
				  <td style="text-align:center;"><?= number_format($row_sisa['COM1_QUANTITY'],2); ?></td>
			 
			</tr>

			<tr>
			  <td><strong>COM2</strong></td>
			  
				  <td style="text-align:center;"><?= $row_sisa['COM2_DEMAND']; ?></td>
				  <td style="text-align:center;"><?= number_format($row_sisa['COM2_QUANTITY'],2); ?></td>
			  
			</tr>
            <!-- ========================= SHR ========================= -->
			<tr>
			  <td><strong>SHR1</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['SHR1_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['SHR1_QUANTITY'],2); ?></td>
			 
			</tr>

			<tr>
			  <td><strong>SHR2</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['SHR2_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['SHR2_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>SHR3</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['SHR3_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['SHR3_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>SHR4</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['SHR4_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['SHR4_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>SHR5</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['SHR5_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['SHR5_QUANTITY'],2); ?></td>
			  
			</tr>

			<!-- ========================= TDR, SUE, AIR, POL, WET ========================= -->
			<tr>
			  <td><strong>TDR1</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['TDR1_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['TDR1_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>SUE1</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['SUE1_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['SUE1_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>SUE2</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['SUE2_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['SUE2_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>SUE3</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['SUE3_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['SUE3_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>SUE4</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['SUE4_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['SUE4_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>AIR1</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['AIR1_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['AIR1_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>POL1</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['POL1_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['POL1_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>WET1</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['WET1_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['WET1_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>WET2</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['WET2_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['WET2_QUANTITY'],2); ?></td>
			  
			</tr>
			<tr>
			  <td><strong>WET3</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['WET3_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['WET3_QUANTITY'],2); ?></td>
			  
			</tr>

			<tr>
			  <td><strong>WET4</strong></td>
			  
				<td style="text-align:center;"><?= $row_sisa['WET4_DEMAND']; ?></td>
				<td style="text-align:center;"><?= number_format($row_sisa['WET4_QUANTITY'],2); ?></td>
			  
			</tr>  
            <tr style="font-weight:bold; background-color:yellow;">
			<?php
			
				$total_jumlahkkSisa =
					$row_sisa['RSE1_DEMAND'] + $row_sisa['RSE2_DEMAND'] + $row_sisa['RSE3_DEMAND'] + $row_sisa['RSE4_DEMAND'] + $row_sisa['RSE5_DEMAND'] +
					$row_sisa['COM1_DEMAND'] + $row_sisa['COM2_DEMAND'] +
					$row_sisa['SHR1_DEMAND'] + $row_sisa['SHR2_DEMAND'] + $row_sisa['SHR3_DEMAND'] + $row_sisa['SHR4_DEMAND'] + $row_sisa['SHR5_DEMAND'] +
					$row_sisa['TDR1_DEMAND'] +
					$row_sisa['SUE1_DEMAND'] + $row_sisa['SUE2_DEMAND'] + $row_sisa['SUE3_DEMAND'] + $row_sisa['SUE4_DEMAND'] +
					$row_sisa['AIR1_DEMAND'] + $row_sisa['POL1_DEMAND'] +
					$row_sisa['WET1_DEMAND'] + $row_sisa['WET2_DEMAND'] + $row_sisa['WET3_DEMAND'] + $row_sisa['WET4_DEMAND'];

				$total_qtySisa =
					$row_sisa['RSE1_QUANTITY'] + $row_sisa['RSE2_QUANTITY'] + $row_sisa['RSE3_QUANTITY'] + $row_sisa['RSE4_QUANTITY'] + $row_sisa['RSE5_QUANTITY'] +
					$row_sisa['COM1_QUANTITY'] + $row_sisa['COM2_QUANTITY'] +
					$row_sisa['SHR1_QUANTITY'] + $row_sisa['SHR2_QUANTITY'] + $row_sisa['SHR3_QUANTITY'] + $row_sisa['SHR4_QUANTITY'] + $row_sisa['SHR5_QUANTITY'] +
					$row_sisa['TDR1_QUANTITY'] +
					$row_sisa['SUE1_QUANTITY'] + $row_sisa['SUE2_QUANTITY'] + $row_sisa['SUE3_QUANTITY'] + $row_sisa['SUE4_QUANTITY'] +
					$row_sisa['AIR1_QUANTITY'] + $row_sisa['POL1_QUANTITY'] +
					$row_sisa['WET1_QUANTITY'] + $row_sisa['WET2_QUANTITY'] + $row_sisa['WET3_QUANTITY'] + $row_sisa['WET4_QUANTITY'];
			
			?>

				<td style="text-align:center;">TOTAL</td>
				<td style="text-align:center;"><?= $total_jumlahkkSisa ?></td>
				<td style="text-align:center;"><?= number_format($total_qtySisa, 2) ?></td>
			</tr>

        </table>
	  </td>
    </tr>
  </tbody>
</table>
<!-- End Table 4-->		

</body>
</html>	