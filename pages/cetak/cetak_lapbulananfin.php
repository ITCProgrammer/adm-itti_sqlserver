<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=lapbulananfin-".$_GET['tahun']."_".$_GET['bulan'].".xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
include "../../koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Bulanan FIN</title>

</head>
<body>
<?php
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
$bulanDipilih = isset($_GET['bulan']) ? $_GET['bulan'] : 'all';	
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
?>
<table width="100%" border="1">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center">No. Form</td>
      <td align="center">: FW-02-FIN-02</td>
    </tr>
    <tr>
      <td colspan="27" align="center"><strong>LAPORAN PRODUKSI DEPARTEMEN FINISHING</strong></td>
      <td align="center">No. Revisi</td>
      <td align="center">:13</td>
    </tr>
    <tr>
      <td colspan="27"><strong>TAHUN : </strong></td>
      <td align="center">Tgl. Terbit</td>
      <td align="center">: 20 Juni 2022</td>
    </tr>
  </tbody>
</table>
<br>	
<table border="1">
        <thead class="bg-blue">
          <tr>
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
				and a.no_mesin like 'P3ST%'
				and a.proses LIKE '%Finishing Jadi (Normal)%'");		
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
				and a.no_mesin like 'P3ST%'
				and a.proses IN('Preset (Normal)','Oven Greige (Normal)')");		
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
				and a.no_mesin like 'P3ST%'
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
				and a.no_mesin LIKE 'P3ST%'
				and proses LIKE '%Finishing 1X%' ");		
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
				and a.no_mesin like 'P3ST%'
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
				and a.no_mesin like 'P3ST%'
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
				and a.no_mesin like 'P3ST%'
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
				and proses like '%Oven Stenter (Normal)%'
				and a.no_mesin like 'P3ST%' ");		
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
				and a.no_mesin LIKE 'P3ST%'
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
				and a.no_mesin LIKE 'P3ST%'
				and proses like '%Suhu%'");		
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
				-- and a.nama_mesin IN('PAD1','PAD2','PAD3','PAD4','PAD5')
				and a.no_mesin like 'P3ST%'
				and a.proses IN ('Padder - Dyeing (Bantu)','Padder 2x - Dyeing (Bantu)','Padder 3x - Dyeing (Bantu)','Padder 4x - Dyeing (Bantu)')");		
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
				and a.no_mesin like 'P3ST%'
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
				-- and a.nama_mesin IN('FNJ2','FNJ3','FNJ4','FNJ5','FNJ6')
				and a.no_mesin like 'P3ST%'
				and a.proses IN ('Finishing Ulang (Normal)','Finishing Ulang - Brushing (Bantu)','Finishing Ulang 2 (Normal)','Finishing Ulang 3 (Normal)','Finishing Ulang - Dyeing (Bantu)','Finishing Ulang - Dyeing 2 (Bantu)','Finishing Ulang - Dyeing 3 (Bantu)')");		
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
				and a.no_mesin like 'P3CP%'
				and proses IN('Compact (Normal)','Compact - Dyeing (Bantu)','Compact - Dyeing 2 (Bantu)','Compact - Dyeing 3 (Bantu)')");		
			$dts_K_compact_t_s	= sqlsrv_fetch_array($sqlsK_compact_t_s, SQLSRV_FETCH_ASSOC);
			  
//			$sqlsK_compact_fin_t_s  = sqlsrv_query($conS, "SELECT
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
//			FROM
//				db_finishing.tbl_produksi a
//			LEFT JOIN db_finishing.tbl_no_mesin b ON
//				a.no_mesin = b.no_mesin
//			WHERE
//				YEAR(a.tgl_update) = '$tahunSebelumnya'
//				and a.nama_mesin IN('CPF2','CPF3','CPF4')");		
//			$dts_K_compact_fin_t_s	= sqlsrv_fetch_array($sqlsK_compact_fin_t_s, SQLSRV_FETCH_ASSOC);
//			  
//			$sqlsK_compact_dye_t_s  = sqlsrv_query($conS, "SELECT
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
//			FROM
//				db_finishing.tbl_produksi a
//			LEFT JOIN db_finishing.tbl_no_mesin b ON
//				a.no_mesin = b.no_mesin
//			WHERE
//				YEAR(a.tgl_update) = '$tahunSebelumnya'
//				and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')");		
//			$dts_K_compact_dye_t_s	= sqlsrv_fetch_array($sqlsK_compact_dye_t_s, SQLSRV_FETCH_ASSOC); 
			$sqlsK_compact_perbaikan_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				-- and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')
				and a.no_mesin LIKE 'P3CP%'
				-- and a.nama_mesin LIKE 'CPF1%'
				and a.proses LIKE '%Compact Perbaikan (Normal)%'");		
			$dts_K_compact_perbaikan_t_s	= sqlsrv_fetch_array($sqlsK_compact_perbaikan_t_s, SQLSRV_FETCH_ASSOC);  
			$tot_compact_t_s =  $dts_K_compact_t_s['kering']+$dts_K_compact_perbaikan_t_s['kering'];
			$sqlsB_fin_bl_t_s  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS basah,
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS basah_lot,
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				YEAR(a.tgl_update) = '$tahunSebelumnya'
				-- and a.nama_mesin = 'OPW1'
				and a.no_mesin like 'P3BC%'
				and proses IN('Belah Cuci (Normal)','Belah Preset (Normal)','Belah Cuci ulang (Normal)','Belah Dyeing (Bantu)')");		
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
				and proses IN('Finishing Jadi (Normal)','Finishing 1X (Normal)','Finishing 1X ulang (Normal)','Finishing 1X (ov) (Normal)','Finishing Ulang (Normal)','Finishing Ulang 2 (Normal)','Finishing Ulang 3 (Normal)','Oven Stenter (Normal)','Oven Stenter Dyeing (Bantu)','Oven Tambah Obat (Khusus)') 
				and a.no_mesin like 'P3DR%'");		
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
			$tot_kj_t_s = $dts_K_fin_jadi_t_s['kering']+$dts_K_padder_t_s['kering']+$dts_K_pot_t_s['kering']+$dts_K_fin_ulang_t_s['kering']+$dts_K_compact_t_s['kering']+$dts_K_compact_perbaikan_t_s['kering']+$dts_K_ov_krh_t_s['kering'];
			$tot_loss_t_s = $dts_K_tarik_t_s['kering']+$dts_K_ov_fl_ul_t_s['kering']+$dts_K_ov_ul_t_s['kering']+$dts_K_ov_dye_t_s['kering']+$dts_K_padder_t_s['kering']+$dts_K_fin_ulang_t_s['kering']+$dts_K_compact_perbaikan_t_s['kering']+$dts_K_ov_dyeing_t_s['kering']; 
			  
			$sqlgk_t_s=sqlsrv_query($cona," SELECT
				SUM(qty_order) as kg
			FROM
				db_adm.tbl_gantikain tb
			WHERE
			YEAR(tgl_update) = '$tahunSebelumnya'
			and (t_jawab='FIN' or t_jawab1='FIN' or t_jawab2='FIN' or t_jawab3='FIN' or t_jawab4='FIN')");
			$rg_t_s=sqlsrv_fetch_array($sqlgk_t_s, SQLSRV_FETCH_ASSOC);  
			?>  
            <td align="center" valign="middle"><strong>Total'
            <?= substr($tahunSebelumnya, 2); ?>
            </strong></td>
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
            <td align="right"><?= number_format($dts_K_compact_perbaikan_t_s['kering'],2); ?></td>
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
				and a.no_mesin like 'P3ST%'
				and a.proses LIKE '%Finishing Jadi (Normal)%'
				");		
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
				and a.no_mesin like 'P3ST%'
				and a.proses IN('Preset (Normal)','Oven Greige (Normal)')");		
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
				and a.no_mesin like 'P3ST%'
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
				and a.no_mesin LIKE 'P3ST%'
				and proses LIKE '%Finishing 1X%' ");		
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
				and a.no_mesin like 'P3ST%'
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
				and a.no_mesin like 'P3ST%'
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
				and a.no_mesin like 'P3ST%'
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
				and proses like '%Oven Stenter (Normal)%'
				and a.no_mesin like 'P3ST%'");		
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
				and a.no_mesin LIKE 'P3ST%'
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
				and a.no_mesin LIKE 'P3ST%'
				and proses like '%Suhu%'");		
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
				-- and a.nama_mesin IN('PAD1','PAD2','PAD3','PAD4','PAD5')
				and a.no_mesin like 'P3ST%'
				and a.proses IN ('Padder - Dyeing (Bantu)','Padder 2x - Dyeing (Bantu)','Padder 3x - Dyeing (Bantu)','Padder 4x - Dyeing (Bantu)')");		
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
				and a.no_mesin like 'P3ST%'
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
				-- and a.nama_mesin IN('FNJ2','FNJ3','FNJ4','FNJ5','FNJ6')
				and a.no_mesin like 'P3ST%'
				and a.proses IN ('Finishing Ulang (Normal)','Finishing Ulang - Brushing (Bantu)','Finishing Ulang 2 (Normal)','Finishing Ulang 3 (Normal)','Finishing Ulang - Dyeing (Bantu)','Finishing Ulang - Dyeing 2 (Bantu)','Finishing Ulang - Dyeing 3 (Bantu)')");		
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
				and a.no_mesin like 'P3CP%'
				and proses IN('Compact (Normal)','Compact - Dyeing (Bantu)','Compact - Dyeing 2 (Bantu)','Compact - Dyeing 3 (Bantu)')");		
			$dts_K_compact_l	= sqlsrv_fetch_array($sqlsK_compact_l, SQLSRV_FETCH_ASSOC);
			  
//			$sqlsK_compact_fin_l  = sqlsrv_query($conS, "SELECT
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
//			FROM
//				db_finishing.tbl_produksi a
//			LEFT JOIN db_finishing.tbl_no_mesin b ON
//				a.no_mesin = b.no_mesin
//			WHERE
//				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
//				and a.nama_mesin IN('CPF2','CPF3','CPF4')");		
//			$dts_K_compact_fin_l	= sqlsrv_fetch_array($sqlsK_compact_fin_l, SQLSRV_FETCH_ASSOC);
//			  
//			$sqlsK_compact_dye_l  = sqlsrv_query($conS, "SELECT
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
//			FROM
//				db_finishing.tbl_produksi a
//			LEFT JOIN db_finishing.tbl_no_mesin b ON
//				a.no_mesin = b.no_mesin
//			WHERE
//				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
//				and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')");		
//			$dts_K_compact_dye_l	= sqlsrv_fetch_array($sqlsK_compact_dye_l, SQLSRV_FETCH_ASSOC);  
			$sqlsK_compact_perbaikan_l  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = 12 AND YEAR(a.tgl_update) = '$tahunSebelumnya'
				-- and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')
				and a.no_mesin LIKE 'P3CP%'
				-- and a.nama_mesin LIKE 'CPF1%'
				and a.proses LIKE '%Compact Perbaikan (Normal)%'");		
			$dts_K_compact_perbaikan_l	= sqlsrv_fetch_array($sqlsK_compact_perbaikan_l, SQLSRV_FETCH_ASSOC);
//			$tot_compact_l =  $dts_K_compact_l['kering']+$dts_K_compact_fin_l['kering']+$dts_K_compact_dye_l['kering'];
			$tot_compact_l =  $dts_K_compact_l['kering']+$dts_K_compact_perbaikan_l['kering'];
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
				-- and a.nama_mesin = 'OPW1'
				and a.no_mesin like 'P3BC%'
				and proses IN('Belah Cuci (Normal)','Belah Preset (Normal)','Belah Cuci ulang (Normal)','Belah Dyeing (Bantu)')");		
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
				and proses IN('Finishing Jadi (Normal)','Finishing 1X (Normal)','Finishing 1X ulang (Normal)','Finishing 1X (ov) (Normal)','Finishing Ulang (Normal)','Finishing Ulang 2 (Normal)','Finishing Ulang 3 (Normal)','Oven Stenter (Normal)','Oven Stenter Dyeing (Bantu)','Oven Tambah Obat (Khusus)') 
				and a.no_mesin like 'P3DR%'");		
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
			$tot_kj_l = $dts_K_fin_jadi_l['kering']+$dts_K_padder_l['kering']+$dts_K_pot_l['kering']+$dts_K_fin_ulang_l['kering']+$dts_K_compact_l['kering']+$dts_K_compact_perbaikan_l['kering']+$dts_K_ov_krh_l['kering'];
			$tot_loss_l = $dts_K_tarik_l['kering']+$dts_K_ov_fl_ul_l['kering']+$dts_K_ov_ul_l['kering']+$dts_K_ov_dye_l['kering']+$dts_K_padder_l['kering']+$dts_K_fin_ulang_l['kering']+$dts_K_compact_perbaikan_l['kering']+$dts_K_ov_dyeing_l['kering'];  
			  
			$sqlgk_l=sqlsrv_query($cona," SELECT
				SUM(qty_order) as kg
			FROM
				db_adm.tbl_gantikain tb
			WHERE
			MONTH(tgl_update) = 12 AND YEAR(tgl_update) = '$tahunSebelumnya'
			and (t_jawab='FIN' or t_jawab1='FIN' or t_jawab2='FIN' or t_jawab3='FIN' or t_jawab4='FIN')");
			$rg_l=sqlsrv_fetch_array($sqlgk_l, SQLSRV_FETCH_ASSOC);  
			?>
            <td align="center" valign="middle"><strong>Des'
            <?= substr($tahunSebelumnya, 2); ?>
            </strong></td>
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
            <td align="right"><?= number_format($dts_K_compact_perbaikan_l['kering'],2); ?></td>
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
			$bulan_target = (int) $_GET['bulan'];
			$bulan_sebelumnya = $bulan_target - 1;

			$nilai_sebelumnya = 0;
			$nilai_saat_ini = 0;
			
			foreach ($bulan as $namaBulan): 
			
			if($bln > $_GET['bulan']){
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
				and a.no_mesin like 'P3ST%'
				and a.proses LIKE '%Finishing Jadi (Normal)%'");		
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
				and a.no_mesin like 'P3ST%'
				and a.proses IN('Preset (Normal)','Oven Greige (Normal)')");		
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
				and a.no_mesin like 'P3ST%'
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
				and a.no_mesin LIKE 'P3ST%'
				and proses LIKE '%Finishing 1X%' ");		
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
				and a.no_mesin like 'P3ST%'
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
				and a.no_mesin like 'P3ST%'
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
				and a.no_mesin like 'P3ST%'
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
				and proses like '%Oven Stenter (Normal)%'
				and a.no_mesin like 'P3ST%'");		
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
				and a.no_mesin LIKE 'P3ST%'
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
				and a.no_mesin LIKE 'P3ST%'
				and proses like '%Suhu%'");		
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
				-- and a.nama_mesin IN('PAD1','PAD2','PAD3','PAD4','PAD5')
				and a.no_mesin like 'P3ST%'
				and a.proses IN ('Padder - Dyeing (Bantu)','Padder 2x - Dyeing (Bantu)','Padder 3x - Dyeing (Bantu)','Padder 4x - Dyeing (Bantu)') ");		
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
				and a.no_mesin like 'P3ST%'
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
				-- and a.nama_mesin IN('FNJ2','FNJ3','FNJ4','FNJ5','FNJ6')
				and a.no_mesin like 'P3ST%'
				and a.proses IN ('Finishing Ulang (Normal)','Finishing Ulang - Brushing (Bantu)','Finishing Ulang 2 (Normal)','Finishing Ulang 3 (Normal)','Finishing Ulang - Dyeing (Bantu)','Finishing Ulang - Dyeing 2 (Bantu)','Finishing Ulang - Dyeing 3 (Bantu)')");		
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
				and a.no_mesin like 'P3CP%'
				and proses IN('Compact (Normal)','Compact - Dyeing (Bantu)','Compact - Dyeing 2 (Bantu)','Compact - Dyeing 3 (Bantu)')
				");	 
			$dts_K_compact	= sqlsrv_fetch_array($sqlsK_compact, SQLSRV_FETCH_ASSOC);
			  
//			$sqlsK_compact_fin  = sqlsrv_query($conS, "SELECT
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
//			FROM
//				db_finishing.tbl_produksi a
//			LEFT JOIN db_finishing.tbl_no_mesin b ON
//				a.no_mesin = b.no_mesin
//			WHERE
//				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun' 
//				and a.nama_mesin IN('CPF2','CPF3','CPF4')");		
//			$dts_K_compact_fin	= sqlsrv_fetch_array($sqlsK_compact_fin, SQLSRV_FETCH_ASSOC);
			  
//			$sqlsK_compact_dye = sqlsrv_query($conS, "SELECT
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
//				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
//			FROM
//				db_finishing.tbl_produksi a
//			LEFT JOIN db_finishing.tbl_no_mesin b ON
//				a.no_mesin = b.no_mesin
//			WHERE
//				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun' 
//				and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')");		
//			$dts_K_compact_dye	= sqlsrv_fetch_array($sqlsK_compact_dye, SQLSRV_FETCH_ASSOC);
			$sqlsK_compact_perbaikan = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot,
				SUM(CASE WHEN (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun' 
				-- and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')
				and a.no_mesin LIKE 'P3CP%'
				-- and a.nama_mesin LIKE 'CPF1%'
				and a.proses LIKE '%Compact Perbaikan (Normal)%'
				");		
			$dts_K_compact_perbaikan	= sqlsrv_fetch_array($sqlsK_compact_perbaikan, SQLSRV_FETCH_ASSOC);
//			$tot_compact=  $dts_K_compact['kering']+$dts_K_compact_fin['kering']+$dts_K_compact_dye['kering'];
			$tot_compact=  $dts_K_compact['kering']+$dts_K_compact_perbaikan['kering'];
			$sqlsB_fin_bl  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING' THEN a.qty ELSE 0 END) AS basah,
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING' THEN 1 ELSE 0 END) AS basah_lot,
				SUM(CASE WHEN a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING' THEN a.panjang ELSE 0 END) AS yard
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'
				-- and a.nama_mesin = 'OPW1'
				and a.no_mesin like 'P3BC%'
				and proses IN('Belah Cuci (Normal)','Belah Preset (Normal)','Belah Cuci ulang (Normal)','Belah Dyeing (Bantu)')");		
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
				and proses IN('Finishing Jadi (Normal)','Finishing 1X (Normal)','Finishing 1X ulang (Normal)','Finishing 1X (ov) (Normal)','Finishing Ulang (Normal)','Finishing Ulang 2 (Normal)','Finishing Ulang 3 (Normal)','Oven Stenter (Normal)','Oven Stenter Dyeing (Bantu)','Oven Tambah Obat (Khusus)') 
				and a.no_mesin like 'P3DR%'");		
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
			$tot_kj = $dts_K_fin_jadi['kering']+$dts_K_padder['kering']+$dts_K_pot['kering']+$dts_K_fin_ulang['kering']+$dts_K_compact['kering']+$dts_K_compact_perbaikan['kering']+$dts_K_ov_krh['kering'];
			$tot_loss = $dts_K_tarik['kering']+$dts_K_ov_fl_ul['kering']+$dts_K_ov_ul['kering']+$dts_K_ov_dye['kering']+$dts_K_padder['kering']+$dts_K_fin_ulang['kering']+$dts_K_compact_perbaikan['kering']+$dts_K_ov_dyeing['kering'];
			
			
			$sqlgk=sqlsrv_query($cona," SELECT
				SUM(qty_order) as kg
			FROM
				db_adm.tbl_gantikain tb
			WHERE
			MONTH(tgl_update) = '$bln1' AND YEAR(tgl_update) = '$tahun'
			and (t_jawab='FIN' or t_jawab1='FIN' or t_jawab2='FIN' or t_jawab3='FIN' or t_jawab4='FIN' )
			");
			$rg=sqlsrv_fetch_array($sqlgk, SQLSRV_FETCH_ASSOC);			
		  ?>	
          <tr>
            <td align="center" valign="middle"><strong>
            <?= $namaBulan . "'" . substr($tahun, 2); ?>
            </strong></td>
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
            <td align="right"><?= number_format($dts_K_compact_perbaikan['kering'],2); ?></td>
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
				$nilai_sebelumnyaO = $dts_K_compact_perbaikan['kering'];
				$nilai_sebelumnyaTotC = $tot_compact;
				$nilai_sebelumnyaP = $dts_B_fin_bl['basah'];
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
				$nilai_saat_iniO = $dts_K_compact_perbaikan['kering'];
				$nilai_saat_iniTotC = $tot_compact;
				$nilai_saat_iniP = $dts_B_fin_bl['basah'];
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
            <td align="center" valign="middle"><strong>%</strong></td>
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
<br>	
<table border="1">
        <thead class="bg-blue">
          <tr>
            <th colspan="17" align="center" valign="middle">Stopage Machine</th>
          </tr>
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
			$sqlS01 = sqlsrv_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL,
				SUM(ts.durasi_jam_stop) AS TOTAL_M
			FROM
				db_adm.tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST301'
				AND YEAR(ts.tgl_buat) = '".$_GET['tahun']."'
				AND MONTH(ts.tgl_buat) = '".$_GET['bulan']."'");
			$rowdS01=sqlsrv_fetch_array($sqlS01, SQLSRV_FETCH_ASSOC);
			$sqlS02 = sqlsrv_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL,
				SUM(ts.durasi_jam_stop) AS TOTAL_M
			FROM
				db_adm.tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST302'
				AND YEAR(ts.tgl_buat) = '".$_GET['tahun']."'
				AND MONTH(ts.tgl_buat) = '".$_GET['bulan']."'");
			$rowdS02=sqlsrv_fetch_array($sqlS02, SQLSRV_FETCH_ASSOC);
			$sqlS03 = sqlsrv_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL,
				SUM(ts.durasi_jam_stop) AS TOTAL_M
			FROM
				db_adm.tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST103'
				AND YEAR(ts.tgl_buat) = '".$_GET['tahun']."'
				AND MONTH(ts.tgl_buat) = '".$_GET['bulan']."'");
			$rowdS03=sqlsrv_fetch_array($sqlS03, SQLSRV_FETCH_ASSOC);
			$sqlS04 = sqlsrv_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL,
				SUM(ts.durasi_jam_stop) AS TOTAL_M
			FROM
				db_adm.tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST304'
				AND YEAR(ts.tgl_buat) = '".$_GET['tahun']."'
				AND MONTH(ts.tgl_buat) = '".$_GET['bulan']."'");
			$rowdS04=sqlsrv_fetch_array($sqlS04, SQLSRV_FETCH_ASSOC);
			$sqlS05 = sqlsrv_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL,
				SUM(ts.durasi_jam_stop) AS TOTAL_M
			FROM
				db_adm.tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST205'
				AND YEAR(ts.tgl_buat) = '".$_GET['tahun']."'
				AND MONTH(ts.tgl_buat) = '".$_GET['bulan']."'");
			$rowdS05=sqlsrv_fetch_array($sqlS05, SQLSRV_FETCH_ASSOC);
			$sqlS06 = sqlsrv_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL,
				SUM(ts.durasi_jam_stop) AS TOTAL_M
			FROM
				db_adm.tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST206'
				AND YEAR(ts.tgl_buat) = '".$_GET['tahun']."'
				AND MONTH(ts.tgl_buat) = '".$_GET['bulan']."'");
			$rowdS06=sqlsrv_fetch_array($sqlS06, SQLSRV_FETCH_ASSOC);
			$sqlS07 = sqlsrv_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL,
				SUM(ts.durasi_jam_stop) AS TOTAL_M
			FROM
				db_adm.tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST307'
				AND YEAR(ts.tgl_buat) = '".$_GET['tahun']."'
				AND MONTH(ts.tgl_buat) = '".$_GET['bulan']."'");
			$rowdS07=sqlsrv_fetch_array($sqlS07, SQLSRV_FETCH_ASSOC);
			$sqlS08 = sqlsrv_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL,
				SUM(ts.durasi_jam_stop) AS TOTAL_M
			FROM
				db_adm.tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST208'
				AND YEAR(ts.tgl_buat) = '".$_GET['tahun']."'
				AND MONTH(ts.tgl_buat) = '".$_GET['bulan']."'");
			$rowdS08=sqlsrv_fetch_array($sqlS08, SQLSRV_FETCH_ASSOC);
			$sqlS09 = sqlsrv_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL,
				SUM(ts.durasi_jam_stop) AS TOTAL_M
			FROM
				db_adm.tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3ST109'
				AND YEAR(ts.tgl_buat) = '".$_GET['tahun']."'
				AND MONTH(ts.tgl_buat) = '".$_GET['bulan']."' ");
			$rowdS09=sqlsrv_fetch_array($sqlS09, SQLSRV_FETCH_ASSOC);
			$sqlSOv01 = sqlsrv_query($cona," SELECT
				SUM(CASE WHEN ts.kode_stop = 'PM' THEN ts.durasi_jam_stop ELSE 0 END) AS PM,
				SUM(CASE WHEN ts.kode_stop = 'KO' THEN ts.durasi_jam_stop ELSE 0 END) AS KO,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'LM' THEN ts.durasi_jam_stop ELSE 0 END) AS LM,
				SUM(CASE WHEN ts.kode_stop = 'AP' THEN ts.durasi_jam_stop ELSE 0 END) AS AP,
				SUM(CASE WHEN ts.kode_stop = 'PA' THEN ts.durasi_jam_stop ELSE 0 END) AS PA,
				SUM(CASE WHEN ts.kode_stop = 'TG' THEN ts.durasi_jam_stop ELSE 0 END) AS TG,
				SUM(CASE WHEN ts.kode_stop = 'KM' THEN ts.durasi_jam_stop ELSE 0 END) AS KM,
				SUM(ts.durasi_jam) AS TOTAL,
				SUM(ts.durasi_jam_stop) AS TOTAL_M
			FROM
				db_adm.tbl_stoppage ts
			WHERE
				ts.dept = 'FIN'
				AND ts.mesin = 'P3DR101'
				AND YEAR(ts.tgl_buat) = '".$_GET['tahun']."'
				AND MONTH(ts.tgl_buat) = '".$_GET['bulan']."' ");
			$rowdSOv01=sqlsrv_fetch_array($sqlSOv01, SQLSRV_FETCH_ASSOC);
		  ?>	
          <tr>
            <td align="center" valign="middle"><strong>ST 01</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit(488-$rowdS01['TOTAL_M']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['PM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['GL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['NS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['TS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['PS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['TG']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['PP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['PL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['KM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['LM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['AP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['KP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['KO']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS01['TOTAL_M']); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap"><?= number_format(round(($rowdS01['TOTAL_M']/488)*100,2),2); ?>
              %</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 02</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit(488-$rowdS02['TOTAL_M']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['PM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['GL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['NS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['TS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['PS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['TG']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['PP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['PL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['KM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['LM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['AP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['KP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['KO']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS02['TOTAL_M']); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap"><?= number_format(round(($rowdS02['TOTAL_M']/488)*100,2),2); ?>
              %</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 03</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit(488-$rowdS03['TOTAL_M']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['PM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['GL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['NS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['TS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['PS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['TG']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['PP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['PL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['KM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['LM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['AP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['KP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['KO']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS03['TOTAL_M']); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap"><?= number_format(round(($rowdS03['TOTAL_M']/488)*100,2),2); ?>
              %</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 04</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit(488-$rowdS04['TOTAL_M']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['PM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['GL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['NS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['TS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['PS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['TG']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['PP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['PL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['KM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['LM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['AP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['KP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['KO']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS04['TOTAL_M']); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap"><?= number_format(round(($rowdS04['TOTAL_M']/488)*100,2),2); ?>
              %</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 05</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit(488-$rowdS05['TOTAL_M']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['PM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['GL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['NS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['TS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['PS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['TG']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['PP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['PL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['KM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['LM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['AP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['KP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['KO']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS05['TOTAL_M']); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap"><?= number_format(round(($rowdS05['TOTAL_M']/488)*100,2),2); ?>
              %</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 06</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit(488-$rowdS06['TOTAL_M']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['PM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['GL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['NS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['TS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['PS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['TG']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['PP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['PL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['KM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['LM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['AP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['KP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['KO']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS06['TOTAL_M']); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap"><?= number_format(round(($rowdS06['TOTAL_M']/488)*100,2),2); ?>
              %</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 07</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit(488-$rowdS07['TOTAL_M']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['PM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['GL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['NS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['TS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['PS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['TG']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['PP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['PL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['KM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['LM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['AP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['KP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['KO']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS07['TOTAL_M']); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap"><?= number_format(round(($rowdS07['TOTAL_M']/488)*100,2),2); ?>
              %</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 08</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit(488-$rowdS08['TOTAL_M']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['PM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['GL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['NS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['TS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['PS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['TG']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['PP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['PL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['KM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['LM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['AP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['KP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['KO']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS08['TOTAL_M']); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap"><?= number_format(round(($rowdS08['TOTAL_M']/488)*100,2),2); ?>
              %</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 09</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit(488-$rowdS09['TOTAL_M']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['PM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['GL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['NS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['TS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['PS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['TG']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['PP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['PL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['KM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['LM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['AP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['KP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['KO']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdS09['TOTAL_M']); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap"><?= number_format(round(($rowdS09['TOTAL_M']/488)*100,2),2); ?>
              %</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>OV 01</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit(488-$rowdSOv01['TOTAL_M']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['PM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['GL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['NS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['TS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['PS']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['TG']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['PP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['PL']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['KM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['LM']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['AP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['KP']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['KO']); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($rowdSOv01['TOTAL_M']); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap"><?= number_format(round(($rowdSOv01['TOTAL_M']/488)*100,2),2); ?>
              %</td>
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
			  $TotStop = $rowdS01['TOTAL_M']+$rowdS02['TOTAL_M']+$rowdS03['TOTAL_M']+$rowdS04['TOTAL_M']+$rowdS05['TOTAL_M']+$rowdS06['TOTAL_M']+$rowdS07['TOTAL_M']+$rowdS08['TOTAL_M']+$rowdS09['TOTAL_M']+$rowdSOv01['TOTAL_M'];
			  ?>
            <td align="center" valign="middle"><strong>TOTAL</strong></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap">&nbsp;</td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotPM); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotGL); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotNS); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotTS); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotPT); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotTG); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotPP); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotPL); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotKM); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotLM); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotAP); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotKP); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotKO); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?php echo formatJamMenit($TotStop); ?></td>
            <td align="center" class="table table-bordered table-hover table-striped nowrap">&nbsp;</td>  
          </tr>
        </tbody>
		<tfoot>
		</tfoot>  
</table>
<br>	
<table border="1">
        <thead class="bg-green">
          <tr>
            <th colspan="40" align="center" valign="middle">PRODUKSI / MESIN</th>
          </tr>
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 

						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02   

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02	    

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02   

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02    

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '5' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN5 = sqlsrv_fetch_array($sql_bln5, SQLSRV_FETCH_ASSOC);
			$sql_bln6 = sqlsrv_query($conS, "SELECT
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '6' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN6 = sqlsrv_fetch_array($sql_bln6, SQLSRV_FETCH_ASSOC);
			$sql_bln7 = sqlsrv_query($conS, "SELECT
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '7' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN7 = sqlsrv_fetch_array($sql_bln7, SQLSRV_FETCH_ASSOC);
			$sql_bln8 = sqlsrv_query($conS, "SELECT
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '8' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN8 = sqlsrv_fetch_array($sql_bln8, SQLSRV_FETCH_ASSOC);
			$sql_bln9 = sqlsrv_query($conS, "SELECT
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '9' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN9 = sqlsrv_fetch_array($sql_bln9, SQLSRV_FETCH_ASSOC);
			$sql_bln10 = sqlsrv_query($conS, "SELECT
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '10' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN10 = sqlsrv_fetch_array($sql_bln10, SQLSRV_FETCH_ASSOC);
			$sql_bln11 = sqlsrv_query($conS, "SELECT
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '$bln1' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN11 = sqlsrv_fetch_array($sql_bln11, SQLSRV_FETCH_ASSOC);
			$sql_bln12 = sqlsrv_query($conS, "SELECT
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
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
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_Ov01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP01,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP101' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP01,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP101' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP01,
				
				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.qty 
						ELSE 0 
					END) AS kg_CP02,

				SUM(CASE 
						WHEN a.no_mesin = 'P3CP102' 
						  AND a.kondisi_kain IN ('BASAH', 'KERING') 
						  AND a.shift IN ('A', 'B', 'C') 
						THEN a.panjang 
						ELSE 0 
					END) AS yard_CP02,
				SUM(CASE 
		            WHEN a.no_mesin = 'P3CP102' 
		              AND a.kondisi_kain IN ('BASAH', 'KERING') 
		              AND a.shift IN ('A', 'B', 'C') 
		              AND a.jam_in IS NOT NULL AND a.jam_out IS NOT NULL
					  AND a.jam_in NOT LIKE '24:%' AND a.jam_out NOT LIKE '24:%'
		            THEN
						CASE 
							WHEN x.t_out < x.t_in
								THEN DATEDIFF(MINUTE, x.t_in, DATEADD(DAY, 1, CAST(x.t_out AS datetime)))
							ELSE DATEDIFF(MINUTE, x.t_in, CAST(x.t_out AS datetime))
						END
		            ELSE 0 
		        END) AS time_CP02

			FROM db_finishing.tbl_produksi a
			CROSS APPLY (
				SELECT
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_in))  = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_in))  END) AS t_in,
					TRY_CONVERT(time(0), CASE WHEN LTRIM(RTRIM(a.jam_out)) = '0000' THEN '00:00' ELSE LTRIM(RTRIM(a.jam_out)) END) AS t_out
			) x
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin
			WHERE MONTH(a.tgl_update) = '12' AND YEAR(a.tgl_update) = '$tahun'");
			$rBLN12 = sqlsrv_fetch_array($sql_bln12, SQLSRV_FETCH_ASSOC);
		  ?>	
          <tr>
            <td align="center" valign="middle"><strong>ST 01</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_S01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_S01'] <> 0) ? number_format(round($rTsblm['yard_S01']/$rTsblm['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_S01'] <> 0) ? number_format(round($rBLN1['yard_S01']/$rBLN1['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_S01'] <> 0) ? number_format(round($rBLN2['yard_S01']/$rBLN2['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_S01'] <> 0 ) ? number_format(round($rBLN3['yard_S01']/$rBLN3['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_S01'] <> 0) ? number_format(round($rBLN4['yard_S01']/$rBLN4['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_S01'] <> 0) ? number_format(round($rBLN5['yard_S01']/$rBLN5['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_S01'] <> 0) ? number_format(round($rBLN6['yard_S01']/$rBLN6['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_S01'] <> 0) ? number_format(round($rBLN7['yard_S01']/$rBLN7['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_S01'] <> 0) ? number_format(round($rBLN8['yard_S01']/$rBLN8['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_S01'] <> 0) ? number_format(round($rBLN9['yard_S01']/$rBLN9['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_S01'] <> 0) ? number_format(round($rBLN10['yard_S01']/$rBLN10['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_S01'] <> 0) ? number_format(round($rBLN11['yard_S01']/$rBLN11['time_S01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_S01'] <> 0) ? number_format(round($rBLN12['yard_S01']/$rBLN12['time_S01'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 02</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_S02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_S02'] <> 0) ? number_format(round($rTsblm['yard_S02']/$rTsblm['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_S02'] <> 0) ? number_format(round($rBLN1['yard_S02']/$rBLN1['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_S02'] <> 0) ? number_format(round($rBLN2['yard_S02']/$rBLN2['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_S02'] <> 0) ? number_format(round($rBLN3['yard_S02']/$rBLN3['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_S02'] <> 0) ? number_format(round($rBLN4['yard_S02']/$rBLN4['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_S02'] <> 0) ? number_format(round($rBLN5['yard_S02']/$rBLN5['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_S02'] <> 0) ? number_format(round($rBLN6['yard_S02']/$rBLN6['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_S02'] <> 0) ? number_format(round($rBLN7['yard_S02']/$rBLN7['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_S02'] <> 0) ? number_format(round($rBLN8['yard_S02']/$rBLN8['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_S02'] <> 0) ? number_format(round($rBLN9['yard_S02']/$rBLN9['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_S02'] <> 0) ? number_format(round($rBLN10['yard_S02']/$rBLN10['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_S02'] <> 0) ? number_format(round($rBLN11['yard_S02']/$rBLN11['time_S02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_S02'] <> 0) ? number_format(round($rBLN12['yard_S02']/$rBLN12['time_S02'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 03</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_S03'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_S03'] <> 0) ? number_format(round($rTsblm['yard_S03']/$rTsblm['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_S03'] <> 0) ? number_format(round($rBLN1['yard_S03']/$rBLN1['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_S03'] <> 0) ? number_format(round($rBLN2['yard_S03']/$rBLN2['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_S03'] <> 0) ? number_format(round($rBLN3['yard_S03']/$rBLN3['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_S03'] <> 0) ? number_format(round($rBLN4['yard_S03']/$rBLN4['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_S03'] <> 0) ? number_format(round($rBLN5['yard_S03']/$rBLN5['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_S03'] <> 0) ? number_format(round($rBLN6['yard_S03']/$rBLN6['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_S03'] <> 0) ? number_format(round($rBLN7['yard_S03']/$rBLN7['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_S03'] <> 0) ? number_format(round($rBLN8['yard_S03']/$rBLN8['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_S03'] <> 0) ? number_format(round($rBLN9['yard_S03']/$rBLN9['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_S03'] <> 0) ? number_format(round($rBLN10['yard_S03']/$rBLN10['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_S03'] <> 0) ? number_format(round($rBLN11['yard_S03']/$rBLN11['time_S03'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_S03'] <> 0) ? number_format(round($rBLN12['yard_S03']/$rBLN12['time_S03'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 04</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_S04'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_S04'] <> 0) ? number_format(round($rTsblm['yard_S04']/$rTsblm['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_S04'] <> 0) ? number_format(round($rBLN1['yard_S04']/$rBLN1['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_S04'] <> 0) ? number_format(round($rBLN2['yard_S04']/$rBLN2['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_S04'] <> 0) ? number_format(round($rBLN3['yard_S04']/$rBLN3['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_S04'] <> 0) ? number_format(round($rBLN4['yard_S04']/$rBLN4['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_S04'] <> 0) ? number_format(round($rBLN5['yard_S04']/$rBLN5['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_S04'] <> 0) ? number_format(round($rBLN6['yard_S04']/$rBLN6['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_S04'] <> 0) ? number_format(round($rBLN7['yard_S04']/$rBLN7['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_S04'] <> 0) ? number_format(round($rBLN8['yard_S04']/$rBLN8['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_S04'] <> 0) ? number_format(round($rBLN9['yard_S04']/$rBLN9['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_S04'] <> 0) ? number_format(round($rBLN10['yard_S04']/$rBLN10['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_S04'] <> 0) ? number_format(round($rBLN11['yard_S04']/$rBLN11['time_S04'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_S04'] <> 0) ? number_format(round($rBLN12['yard_S04']/$rBLN12['time_S04'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 05</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_S05'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_S05'] <> 0) ? number_format(round($rTsblm['yard_S05']/$rTsblm['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_S05'] <> 0) ? number_format(round($rBLN1['yard_S05']/$rBLN1['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_S05'] <> 0) ? number_format(round($rBLN2['yard_S05']/$rBLN2['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_S05'] <> 0) ? number_format(round($rBLN3['yard_S05']/$rBLN3['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_S05'] <> 0) ? number_format(round($rBLN4['yard_S05']/$rBLN4['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_S05'] <> 0) ? number_format(round($rBLN5['yard_S05']/$rBLN5['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_S05'] <> 0) ? number_format(round($rBLN6['yard_S05']/$rBLN6['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_S05'] <> 0) ? number_format(round($rBLN7['yard_S05']/$rBLN7['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_S05'] <> 0) ? number_format(round($rBLN8['yard_S05']/$rBLN8['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_S05'] <> 0) ? number_format(round($rBLN9['yard_S05']/$rBLN9['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_S05'] <> 0) ? number_format(round($rBLN10['yard_S05']/$rBLN10['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_S05'] <> 0) ? number_format(round($rBLN11['yard_S05']/$rBLN11['time_S05'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_S05'] <> 0) ? number_format(round($rBLN12['yard_S05']/$rBLN12['time_S05'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 06</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_S06'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_S06'] <> 0) ? number_format(round($rTsblm['yard_S06']/$rTsblm['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_S06'] <> 0) ? number_format(round($rBLN1['yard_S06']/$rBLN1['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_S06'] <> 0) ? number_format(round($rBLN2['yard_S02']/$rBLN2['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_S06'] <> 0) ? number_format(round($rBLN3['yard_S06']/$rBLN3['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_S06'] <> 0) ? number_format(round($rBLN4['yard_S06']/$rBLN4['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_S06'] <> 0) ? number_format(round($rBLN5['yard_S06']/$rBLN5['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_S06'] <> 0) ? number_format(round($rBLN6['yard_S06']/$rBLN6['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_S06'] <> 0) ? number_format(round($rBLN7['yard_S06']/$rBLN7['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_S06'] <> 0) ? number_format(round($rBLN8['yard_S06']/$rBLN8['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_S06'] <> 0) ? number_format(round($rBLN9['yard_S06']/$rBLN9['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_S06'] <> 0) ? number_format(round($rBLN10['yard_S06']/$rBLN10['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_S06'] <> 0) ? number_format(round($rBLN11['yard_S06']/$rBLN11['time_S06'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_S06'] <> 0) ? number_format(round($rBLN12['yard_S06']/$rBLN12['time_S06'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 07</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_S07'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_S07'] <> 0) ? number_format(round($rTsblm['yard_S07']/$rTsblm['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_S07'] <> 0) ? number_format(round($rBLN1['yard_S07']/$rBLN1['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_S07'] <> 0) ? number_format(round($rBLN2['yard_S07']/$rBLN2['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_S07'] <> 0) ? number_format(round($rBLN3['yard_S07']/$rBLN3['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_S07'] <> 0) ? number_format(round($rBLN4['yard_S07']/$rBLN4['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_S07'] <> 0) ? number_format(round($rBLN5['yard_S07']/$rBLN5['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_S07'] <> 0) ? number_format(round($rBLN6['yard_S07']/$rBLN6['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_S07'] <> 0) ? number_format(round($rBLN7['yard_S07']/$rBLN7['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_S07'] <> 0) ? number_format(round($rBLN8['yard_S07']/$rBLN8['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_S07'] <> 0) ? number_format(round($rBLN9['yard_S07']/$rBLN9['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_S07'] <> 0) ? number_format(round($rBLN10['yard_S07']/$rBLN10['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_S07'] <> 0) ? number_format(round($rBLN11['yard_S07']/$rBLN11['time_S07'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_S07'] <> 0) ? number_format(round($rBLN12['yard_S07']/$rBLN12['time_S07'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 08</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_S08'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_S08'] <> 0) ? number_format(round($rTsblm['yard_S08']/$rTsblm['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_S08'] <> 0) ? number_format(round($rBLN1['yard_S08']/$rBLN1['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_S08'] <> 0) ? number_format(round($rBLN2['yard_S08']/$rBLN2['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_S08'] <> 0) ? number_format(round($rBLN3['yard_S08']/$rBLN3['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_S08'] <> 0) ? number_format(round($rBLN4['yard_S08']/$rBLN4['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_S08'] <> 0) ? number_format(round($rBLN5['yard_S08']/$rBLN5['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_S08'] <> 0) ? number_format(round($rBLN6['yard_S08']/$rBLN6['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_S08'] <> 0) ? number_format(round($rBLN7['yard_S08']/$rBLN7['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_S08'] <> 0) ? number_format(round($rBLN8['yard_S08']/$rBLN8['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_S08'] <> 0) ? number_format(round($rBLN9['yard_S08']/$rBLN9['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_S08'] <> 0) ? number_format(round($rBLN10['yard_S08']/$rBLN10['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_S08'] <> 0) ? number_format(round($rBLN11['yard_S08']/$rBLN11['time_S08'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_S08'] <> 0) ? number_format(round($rBLN12['yard_S08']/$rBLN12['time_S08'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>ST 09</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_S09'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_S09'] <> 0) ? number_format(round($rTsblm['yard_S09']/$rTsblm['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_S09'] <> 0) ? number_format(round($rBLN1['yard_S09']/$rBLN1['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_S09'] <> 0) ? number_format(round($rBLN2['yard_S09']/$rBLN2['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_S09'] <> 0) ? number_format(round($rBLN3['yard_S09']/$rBLN3['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_S09'] <> 0) ? number_format(round($rBLN4['yard_S09']/$rBLN4['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_S09'] <> 0) ? number_format(round($rBLN5['yard_S09']/$rBLN5['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_S09'] <> 0) ? number_format(round($rBLN6['yard_S09']/$rBLN6['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_S09'] <> 0) ? number_format(round($rBLN7['yard_S09']/$rBLN7['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_S09'] <> 0) ? number_format(round($rBLN8['yard_S09']/$rBLN8['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_S09'] <> 0) ? number_format(round($rBLN9['yard_S09']/$rBLN9['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_S09'] <> 0) ? number_format(round($rBLN10['yard_S09']/$rBLN10['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_S09'] <> 0) ? number_format(round($rBLN11['yard_S09']/$rBLN11['time_S09'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_S09'] <> 0) ? number_format(round($rBLN12['yard_S09']/$rBLN12['time_S09'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>OV 01</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_Ov01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_Ov01'] <> 0) ? number_format(round($rTsblm['yard_Ov01']/$rTsblm['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_Ov01'] <> 0) ? number_format(round($rBLN1['yard_Ov01']/$rBLN1['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_Ov01'] <> 0) ? number_format(round($rBLN2['yard_Ov01']/$rBLN2['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_Ov01'] <> 0) ? number_format(round($rBLN3['yard_Ov01']/$rBLN3['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_Ov01'] <> 0) ? number_format(round($rBLN4['yard_Ov01']/$rBLN4['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_Ov01'] <> 0) ? number_format(round($rBLN5['yard_Ov01']/$rBLN5['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_Ov01'] <> 0) ? number_format(round($rBLN6['yard_Ov01']/$rBLN6['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_Ov01'] <> 0) ? number_format(round($rBLN7['yard_Ov01']/$rBLN7['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_Ov01'] <> 0) ? number_format(round($rBLN8['yard_Ov01']/$rBLN8['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_Ov01'] <> 0) ? number_format(round($rBLN9['yard_Ov01']/$rBLN9['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_Ov01'] <> 0) ? number_format(round($rBLN10['yard_Ov01']/$rBLN10['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_Ov01'] <> 0) ? number_format(round($rBLN11['yard_Ov01']/$rBLN11['time_Ov01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_Ov01'] <> 0) ? number_format(round($rBLN12['yard_Ov01']/$rBLN12['time_Ov01'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>CP 01</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_CP01'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_CP01'] <> 0) ? number_format(round($rTsblm['yard_CP01']/$rTsblm['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_CP01'] <> 0) ? number_format(round($rBLN1['yard_CP01']/$rBLN1['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_CP01'] <> 0) ? number_format(round($rBLN2['yard_CP01']/$rBLN2['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_CP01'] <> 0) ? number_format(round($rBLN3['yard_CP01']/$rBLN3['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_CP01'] <> 0) ? number_format(round($rBLN4['yard_CP01']/$rBLN4['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_CP01'] <> 0) ? number_format(round($rBLN5['yard_CP01']/$rBLN5['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_CP01'] <> 0) ? number_format(round($rBLN6['yard_CP01']/$rBLN6['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_CP01'] <> 0) ? number_format(round($rBLN7['yard_CP01']/$rBLN7['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_CP01'] <> 0) ? number_format(round($rBLN8['yard_CP01']/$rBLN8['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_CP01'] <> 0) ? number_format(round($rBLN9['yard_CP01']/$rBLN9['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_CP01'] <> 0) ? number_format(round($rBLN10['yard_CP01']/$rBLN10['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_CP01'] <> 0) ? number_format(round($rBLN11['yard_CP01']/$rBLN11['time_CP01'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_CP01'] <> 0) ? number_format(round($rBLN12['yard_CP01']/$rBLN12['time_CP01'],2), 2) : '0.00'; ?></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><strong>CP 02</strong></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['kg_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rTsblm['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1") ? number_format($rBLN1['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2") ? number_format($rBLN2['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3") ? number_format($rBLN3['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4") ? number_format($rBLN4['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5") ? number_format($rBLN5['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6") ? number_format($rBLN6['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7") ? number_format($rBLN7['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8") ? number_format($rBLN8['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9") ? number_format($rBLN9['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10") ? number_format($rBLN10['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11") ? number_format($rBLN11['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12") ? number_format($rBLN12['yard_CP02'], 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rTsblm['time_CP02'] <> 0) ? number_format(round($rTsblm['yard_CP02']/$rTsblm['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "1" and $rBLN1['time_CP02'] <> 0) ? number_format(round($rBLN1['yard_CP02']/$rBLN1['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "2" and $rBLN2['time_CP02'] <> 0) ? number_format(round($rBLN2['yard_CP02']/$rBLN2['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "3" and $rBLN3['time_CP02'] <> 0) ? number_format(round($rBLN3['yard_CP02']/$rBLN3['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "4" and $rBLN4['time_CP02'] <> 0) ? number_format(round($rBLN4['yard_CP02']/$rBLN4['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "5" and $rBLN5['time_CP02'] <> 0) ? number_format(round($rBLN5['yard_CP02']/$rBLN5['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "6" and $rBLN6['time_CP02'] <> 0) ? number_format(round($rBLN6['yard_CP02']/$rBLN6['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "7" and $rBLN7['time_CP02'] <> 0) ? number_format(round($rBLN7['yard_CP02']/$rBLN7['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "8" and $rBLN8['time_CP02'] <> 0) ? number_format(round($rBLN8['yard_CP02']/$rBLN8['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "9" and $rBLN9['time_CP02'] <> 0) ? number_format(round($rBLN9['yard_CP02']/$rBLN9['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "10" and $rBLN10['time_CP02'] <> 0) ? number_format(round($rBLN10['yard_CP02']/$rBLN10['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "11" and $rBLN11['time_CP02'] <> 0) ? number_format(round($rBLN11['yard_CP02']/$rBLN11['time_CP02'],2), 2) : '0.00'; ?></td>
            <td align="right"><?= ($_GET['bulan'] >= "12" and $rBLN12['time_CP02'] <> 0) ? number_format(round($rBLN12['yard_CP02']/$rBLN12['time_CP02'],2), 2) : '0.00'; ?></td>
          </tr>
        </tbody>
		<tfoot>
		</tfoot>  
</table>	
<br>
<table width="100%" border="0">
  <tbody>
    <tr>
      <td width="73%" align="left" valign="top">
		<table border= "1">
        <thead class="bg-blue">
          <tr>
            <th colspan="2" align="center" valign="middle">PER SHIFT</th>
            <th align="center">QTY</th>
            <th align="center">NCP</th>
            <th align="center">KAIN JADI</th>
            <th align="center">NON JADI</th>
            <th align="center">LOT</th>
            <th align="center">TOTAL POINT</th>
            <th align="center">TOTAL MC JALAN</th>
            <th align="center">TOTAL HARI KERJA</th>
            <th align="center">POINT AKHIR</th>
            </tr>
        </thead>
        <tbody>
		 <?php
			$bln = $_GET['bulan'];
			$Tahun1 = substr($tahun, 2, 2);
			$Bulan = str_pad($bln, 2, '0', STR_PAD_LEFT);

			$sqlNCP = sqlsrv_query($cond, "SELECT 
				ROUND(SUM(
					CASE 
						WHEN shift = 'A' THEN berat
						-- WHEN shift = 'A+B' AND berat >= 1 THEN (FLOOR(berat)/2) + mod(berat,1) 
						-- WHEN shift = 'C+A' AND berat >= 1 THEN (FLOOR(berat)/2)
						WHEN shift = 'A+B' AND berat >= 1 THEN (berat/2) 
						WHEN shift = 'C+A' AND berat >= 1 THEN (berat/2) 
						ELSE 0 
					END
				),2) AS kg_a,

				ROUND(SUM(
					CASE 
						WHEN shift = 'B' THEN berat
						-- WHEN shift = 'B+C' AND berat >= 1 THEN (FLOOR(berat)/2) + mod(berat,1) 
						-- WHEN shift = 'A+B' AND berat >= 1 THEN (FLOOR(berat)/2)
						WHEN shift = 'B+C' AND berat >= 1 THEN (berat/2) 
						WHEN shift = 'A+B' AND berat >= 1 THEN (berat/2) 
						ELSE 0 
					END
				),2) AS kg_b,
			
				ROUND(SUM(
					CASE 
						WHEN shift = 'C' THEN berat             
						-- WHEN shift = 'C+A' AND berat >= 1 THEN (FLOOR(berat)/2) + mod(berat,1) 
						-- WHEN shift = 'B+C' AND berat >= 1 THEN (FLOOR(berat)/2)
						WHEN shift = 'C+A' AND berat >= 1 THEN (berat/2) 
						WHEN shift = 'B+C' AND berat >= 1 THEN (berat/2)
						ELSE 0 
					END
				),2) AS kg_c

			FROM db_qc.tbl_ncp_qcf_now 
			WHERE dept = 'FIN' 
			AND ncp_hitung = 'ya' 
			AND no_ncp LIKE '".$Tahun1."/".$Bulan."/%'
			AND NOT (
				perbaikan LIKE '%DISPOSISI%' 
				OR perbaikan LIKE '%BS%' 
				OR perbaikan LIKE '%CUT LOSS%' 
				OR perbaikan LIKE '%POTONG BUANG%'
			)
			AND NOT status = 'Cancel'");
			$rowNCP = sqlsrv_fetch_array($sqlNCP, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_fin_jadiSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun' 
				and a.no_mesin like 'P3ST%'
				and a.proses LIKE '%Finishing Jadi (Normal)%'");		
			$dts_K_fin_jadiSHF	= sqlsrv_fetch_array($sqlsK_fin_jadiSHF, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_presetSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				and a.no_mesin like 'P3ST%'
				and a.proses IN('Preset (Normal)','Oven Greige (Normal)')");		
			$dts_K_presetSHF	= sqlsrv_fetch_array($sqlsK_presetSHF, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_tarikSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				and a.no_mesin like 'P3ST%'
				and proses like '%Tarik Lebar%'");		
			$dts_K_tarikSHF	= sqlsrv_fetch_array($sqlsK_tarikSHF, SQLSRV_FETCH_ASSOC);
			  
			$sqlsK_fin_1xSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun' 
				and a.no_mesin LIKE 'P3ST%'
				and proses LIKE '%Finishing 1X%' ");		
		   $dts_K_fin_1xSHF	= sqlsrv_fetch_array($sqlsK_fin_1xSHF, SQLSRV_FETCH_ASSOC);
			
		   $sqlsK_ov_flSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Fleece (Normal)%'");		
			$dts_K_ov_flSHF	= sqlsrv_fetch_array($sqlsK_ov_flSHF, SQLSRV_FETCH_ASSOC);	
			
			$sqlsK_ov_fl_ulSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Fleece Ulang (Normal)%'");		
			$dts_K_ov_fl_ulSHF	= sqlsrv_fetch_array($sqlsK_ov_fl_ulSHF, SQLSRV_FETCH_ASSOC);	
			
			$sqlsK_ov_ulSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Stenter Ulang (Normal)%'");		
			$dts_K_ov_ulSHF	= sqlsrv_fetch_array($sqlsK_ov_ulSHF, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_sSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Stenter (Normal)%'
				and a.no_mesin like 'P3ST%'");		
			$dts_K_ov_sSHF	= sqlsrv_fetch_array($sqlsK_ov_sSHF, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_dyeSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				and a.no_mesin LIKE 'P3ST%'
				and proses like '%Oven Stenter Dyeing (Bantu)%'");		
			$dts_K_ov_dyeSHF	= sqlsrv_fetch_array($sqlsK_ov_dyeSHF, SQLSRV_FETCH_ASSOC);
			
		   $sqlsK_naik_suhuSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				and a.no_mesin LIKE 'P3ST%'
				and proses like '%Suhu%'");		

			$dts_K_naik_suhuSHF	= sqlsrv_fetch_array($sqlsK_naik_suhuSHF, SQLSRV_FETCH_ASSOC);	
		   
		   $sqlsK_padderSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				-- and a.nama_mesin IN('PAD1','PAD2','PAD3','PAD4','PAD5')
				and a.proses IN ('Padder - Dyeing (Bantu)','Padder 2x - Dyeing (Bantu)','Padder 3x - Dyeing (Bantu)','Padder 4x - Dyeing (Bantu)')");		
		   $dts_K_padderSHF	= sqlsrv_fetch_array($sqlsK_padderSHF, SQLSRV_FETCH_ASSOC);
			
		   $sqlsK_potSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				and nama_mesin = 'FNJ1'
				and proses like '%Potong Pinggir%'");		
			$dts_K_potSHF	= sqlsrv_fetch_array($sqlsK_potSHF, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_fin_ulangSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun' 
				-- and a.nama_mesin IN('FNJ2','FNJ3','FNJ4','FNJ5','FNJ6')
				and a.no_mesin like 'P3ST%'
				and a.proses IN ('Finishing Ulang (Normal)','Finishing Ulang - Brushing (Bantu)','Finishing Ulang 2 (Normal)','Finishing Ulang 3 (Normal)','Finishing Ulang - Dyeing (Bantu)','Finishing Ulang - Dyeing 2 (Bantu)','Finishing Ulang - Dyeing 3 (Bantu)')");		
			$dts_K_fin_ulangSHF	= sqlsrv_fetch_array($sqlsK_fin_ulangSHF, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_compactSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun' 
				and a.no_mesin like 'P3CP%'
				and proses IN('Compact (Normal)','Compact - Dyeing (Bantu)','Compact - Dyeing 2 (Bantu)','Compact - Dyeing 3 (Bantu)')");		
			$dts_K_compactSHF	= sqlsrv_fetch_array($sqlsK_compactSHF, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_compact_perbaikanSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun' 
				-- and a.nama_mesin IN('CPF2','CPF3','CPF4')
				and a.no_mesin LIKE 'P3CP%'
				-- and a.nama_mesin LIKE 'CPF1%'
				and a.proses LIKE '%Compact Perbaikan (Normal)%'");		
			$dts_K_compact_perbaikanSHF	= sqlsrv_fetch_array($sqlsK_compact_perbaikanSHF, SQLSRV_FETCH_ASSOC);
			  
//			$sqlsK_compact_finSHF  = sqlsrv_query($conS, "SELECT
//				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
//				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
//				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
//				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
//				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
//				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
//				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
//				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
//				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
//			FROM
//				db_finishing.tbl_produksi a
//			LEFT JOIN db_finishing.tbl_no_mesin b ON
//				a.no_mesin = b.no_mesin
//			WHERE
//				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun' 
//				and a.nama_mesin IN('CPF2','CPF3','CPF4')");		
//			$dts_K_compact_finSHF	= sqlsrv_fetch_array($sqlsK_compact_finSHF, SQLSRV_FETCH_ASSOC);
			  
//			$sqlsK_compact_dyeSHF = sqlsrv_query($conS, "SELECT
//				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
//				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
//				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
//				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
//				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
//				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
//				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
//				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
//				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
//			FROM
//				db_finishing.tbl_produksi a
//			LEFT JOIN db_finishing.tbl_no_mesin b ON
//				a.no_mesin = b.no_mesin
//			WHERE
//				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun' 
//				and a.nama_mesin IN('CPD1','CPD2','CPD3','CPD4')");		
//			$dts_K_compact_dyeSHF	= sqlsrv_fetch_array($sqlsK_compact_dyeSHF, SQLSRV_FETCH_ASSOC);
			
			$sqlsK_ov_krhSHF  = sqlsrv_query($conS, "SELECT
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_a,
				SUM(CASE WHEN a.shift='A' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_a,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_b,
				SUM(CASE WHEN a.shift='B' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_b,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.qty ELSE 0 END) AS kering_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN 1 ELSE 0 END) AS kering_lot_c,
				SUM(CASE WHEN a.shift='C' AND (a.kondisi_kain = 'BASAH' OR a.kondisi_kain = 'KERING') THEN a.panjang ELSE 0 END) AS yard_c
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b ON
				a.no_mesin = b.no_mesin
			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'
				and proses like '%Oven Kragh (Normal)%'");		
			$dts_K_ov_krhSHF	= sqlsrv_fetch_array($sqlsK_ov_krhSHF, SQLSRV_FETCH_ASSOC);
			
			$sqlTotMC  = sqlsrv_query($conS, "SELECT
				COUNT(DISTINCT CASE WHEN a.shift = 'A' THEN a.no_mesin END) AS t_mc_A,
				COUNT(DISTINCT CASE WHEN a.shift = 'B' THEN a.no_mesin END) AS t_mc_B,
				COUNT(DISTINCT CASE WHEN a.shift = 'C' THEN a.no_mesin END) AS t_mc_C,
				SUM(CASE WHEN a.shift = 'A' THEN 1 ELSE 0 END) AS t_lot_A,
				SUM(CASE WHEN a.shift = 'B' THEN 1 ELSE 0 END) AS t_lot_B,
				SUM(CASE WHEN a.shift = 'C' THEN 1 ELSE 0 END) AS t_lot_C
			FROM
				db_finishing.tbl_produksi a
			LEFT JOIN db_finishing.tbl_no_mesin b 
				ON a.no_mesin = b.no_mesin

			WHERE
				MONTH(a.tgl_update) = '$bln' AND YEAR(a.tgl_update) = '$tahun'");		
			$dts_TotMC	= sqlsrv_fetch_array($sqlTotMC, SQLSRV_FETCH_ASSOC);
			
			$sqlObat  = db2_exec($conn2, "SELECT
				ROUND(SUM(USERPRIMARYQUANTITY), 2) AS KG
			FROM
				STOCKTRANSACTION s
			WHERE
				TEMPLATECODE = '120'
				AND ITEMTYPECODE = 'DYC'
				AND LOGICALWAREHOUSECODE = 'M512'
				AND YEAR(TRANSACTIONDATE) = '$tahun'
				AND MONTH(TRANSACTIONDATE) = '$bln'");		
			$dtObat= db2_fetch_assoc($sqlObat);	
			
			$tot_prd_stenterSHF_A =  $dts_K_fin_jadiSHF['kering_a']+$dts_K_presetSHF['kering_a']+$dts_K_tarikSHF['kering_a']+$dts_K_fin_1xSHF['kering_a']+$dts_K_ov_flSHF['kering_a']+$dts_K_ov_fl_ulSHF['kering_a']+$dts_K_ov_ulSHF['kering_a']+$dts_K_ov_sSHF['kering_a']+$dts_K_ov_dyeSHF['kering_a']+$dts_K_naik_suhuSHF['kering_a']+$dts_K_padderSHF['kering_a']+$dts_K_potSHF['kering_a']+$dts_K_fin_ulangSHF['kering_a'];
			$tot_prd_stenterSHF_B =  $dts_K_fin_jadiSHF['kering_b']+$dts_K_presetSHF['kering_b']+$dts_K_tarikSHF['kering_b']+$dts_K_fin_1xSHF['kering_b']+$dts_K_ov_flSHF['kering_b']+$dts_K_ov_fl_ulSHF['kering_b']+$dts_K_ov_ulSHF['kering_b']+$dts_K_ov_sSHF['kering_b']+$dts_K_ov_dyeSHF['kering_b']+$dts_K_naik_suhuSHF['kering_b']+$dts_K_padderSHF['kering_b']+$dts_K_potSHF['kering_b']+$dts_K_fin_ulangSHF['kering_b'];
			$tot_prd_stenterSHF_C =  $dts_K_fin_jadiSHF['kering_c']+$dts_K_presetSHF['kering_c']+$dts_K_tarikSHF['kering_c']+$dts_K_fin_1xSHF['kering_c']+$dts_K_ov_flSHF['kering_c']+$dts_K_ov_fl_ulSHF['kering_c']+$dts_K_ov_ulSHF['kering_c']+$dts_K_ov_sSHF['kering_c']+$dts_K_ov_dyeSHF['kering_c']+$dts_K_naik_suhuSHF['kering_c']+$dts_K_padderSHF['kering_c']+$dts_K_potSHF['kering_c']+$dts_K_fin_ulangSHF['kering_c'];
			
			$totKJSHF_A = $dts_K_fin_jadiSHF['kering_a']+$dts_K_padderSHF['kering_a']+$dts_K_fin_ulangSHF['kering_a']+($dts_K_compactSHF['kering_a']+$dts_K_compact_perbaikanSHF['kering_a'])+$dts_K_ov_krhSHF['kering_a'];
			
			$totKJSHF_B = $dts_K_fin_jadiSHF['kering_b']+$dts_K_padderSHF['kering_b']+$dts_K_fin_ulangSHF['kering_b']+($dts_K_compactSHF['kering_b']+$dts_K_compact_perbaikanSHF['kering_b'])+$dts_K_ov_krhSHF['kering_b'];
			$totKJSHF_C = $dts_K_fin_jadiSHF['kering_c']+$dts_K_padderSHF['kering_c']+$dts_K_fin_ulangSHF['kering_c']+($dts_K_compactSHF['kering_c']+$dts_K_compact_perbaikanSHF['kering_c'])+$dts_K_ov_krhSHF['kering_c'];
			
			$tot_prd_stenterLOTSHF_A =  $dts_K_fin_jadiSHF['kering_lot_a']+$dts_K_presetSHF['kering_lot_a']+$dts_K_tarikSHF['kering_lot_a']+$dts_K_fin_1xSHF['kering_lot_a']+$dts_K_ov_flSHF['kering_lot_a']+$dts_K_ov_fl_ulSHF['kering_lot_a']+$dts_K_ov_ulSHF['kering_lot_a']+$dts_K_ov_sSHF['kering_lot_a']+$dts_K_ov_dyeSHF['kering_lot_a']+$dts_K_naik_suhuSHF['kering_lot_a']+$dts_K_padderSHF['kering_lot_a']+$dts_K_potSHF['kering_lot_a']+$dts_K_fin_ulangSHF['kering_lot_a'];
			$tot_prd_stenterLOTSHF_B =  $dts_K_fin_jadiSHF['kering_lot_b']+$dts_K_presetSHF['kering_lot_b']+$dts_K_tarikSHF['kering_lot_b']+$dts_K_fin_1xSHF['kering_lot_b']+$dts_K_ov_flSHF['kering_lot_b']+$dts_K_ov_fl_ulSHF['kering_lot_b']+$dts_K_ov_ulSHF['kering_lot_b']+$dts_K_ov_sSHF['kering_lot_b']+$dts_K_ov_dyeSHF['kering_lot_b']+$dts_K_naik_suhuSHF['kering_lot_b']+$dts_K_padderSHF['kering_lot_b']+$dts_K_potSHF['kering_lot_b']+$dts_K_fin_ulangSHF['kering_lot_b'];
			$tot_prd_stenterLOTSHF_C =  $dts_K_fin_jadiSHF['kering_lot_c']+$dts_K_presetSHF['kering_lot_c']+$dts_K_tarikSHF['kering_lot_c']+$dts_K_fin_1xSHF['kering_lot_c']+$dts_K_ov_flSHF['kering_lot_c']+$dts_K_ov_fl_ulSHF['kering_lot_c']+$dts_K_ov_ulSHF['kering_lot_c']+$dts_K_ov_sSHF['kering_lot_c']+$dts_K_ov_dyeSHF['kering_lot_c']+$dts_K_naik_suhuSHF['kering_lot_c']+$dts_K_padderSHF['kering_lot_c']+$dts_K_potSHF['kering_lot_c']+$dts_K_fin_ulangSHF['kering_lot_c'];
			?>
          <tr>
            <td rowspan="3" align="center" valign="middle"><strong>Produksi</strong></td>
            <td align="right"><strong>A</strong></td>
            <td align="right"><?= number_format($tot_prd_stenterSHF_A,2); ?></td>
            <td align="right"><?= number_format(round($rowNCP['kg_a'],2),2); ?></td>
            <td align="right"><?= number_format($totKJSHF_A,2); ?></td>
            <td align="right"><?= number_format($tot_prd_stenterSHF_A-$totKJSHF_A,2); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?= number_format($dts_TotMC['t_lot_B'],0); ?></td>
            <td align="right">&nbsp;</td>
            <td align="right"><?= number_format($dts_TotMC['t_mc_B'],0); ?></td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            </tr>
          <tr>
            <td align="right"><strong>B</strong></td>
            <td align="right"><?= number_format($tot_prd_stenterSHF_B,2); ?></td>
            <td align="right"><?= number_format(round($rowNCP['kg_b'],2),2); ?></td>
            <td align="right"><?= number_format($totKJSHF_B,2); ?></td>
            <td align="right"><?= number_format($tot_prd_stenterSHF_B-$totKJSHF_B,2); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?= number_format($dts_TotMC['t_lot_A'],0); ?></td>
            <td align="right">&nbsp;</td>
            <td align="right"><?= number_format($dts_TotMC['t_mc_A'],0); ?></td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            </tr>
          <tr>
            <td align="right"><strong>C</strong></td>
            <td align="right"><?= number_format($tot_prd_stenterSHF_C,2); ?></td>
            <td align="right"><?= number_format(round($rowNCP['kg_c'],2),2); ?></td>
            <td align="right"><?= number_format($totKJSHF_C,2); ?></td>
            <td align="right"><?= number_format($tot_prd_stenterSHF_C-$totKJSHF_C,2); ?></td>
            <td align="right" class="table table-bordered table-hover table-striped nowrap"><?= number_format($dts_TotMC['t_lot_C'],0); ?></td>
            <td align="right">&nbsp;</td>
            <td align="right"><?= number_format($dts_TotMC['t_mc_C'],0); ?></td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="2" align="right" valign="middle"><strong>Total</strong></td>
            <td align="right"><?= number_format($tot_prd_stenterSHF_A+$tot_prd_stenterSHF_B+$tot_prd_stenterSHF_C,2); ?></td>
            <td align="right"><?= number_format(round($rowNCP['kg_a'],2)+round($rowNCP['kg_b'],2)+round($rowNCP['kg_c'],2),2); ?></td>
            <td align="right"><?= number_format($totKJSHF_A+$totKJSHF_B+$totKJSHF_C,2); ?></td>
            <td align="right"><?= number_format(($tot_prd_stenterSHF_A-$totKJSHF_A)+($tot_prd_stenterSHF_B-$totKJSHF_B)+($tot_prd_stenterSHF_C-$totKJSHF_C),2); ?></td>
            <td align="right"><?= number_format($dts_TotMC['t_lot_A']+$dts_TotMC['t_lot_B']+$dts_TotMC['t_lot_C'],0); ?></td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            </tr>
        </tbody>
		<tfoot>
		</tfoot>  
      </table>
		</td>
      <td width="4%">&nbsp;</td>
      <td width="23%" align="left" valign="top">
	<table border="1">
        <thead class="bg-blue">
          <tr>
            <th colspan="3">&nbsp;  </th>
            </tr>
        </thead>
        <tbody>
		  	
          <tr>
            <td align="left"><strong>TOTAL YANG DIPAKAI</strong></td>
            <td align="center"><strong>=</strong></td>
            <td align="right"><?= number_format($dtObat['KG'],2);?> gr</td>
            </tr>
          <tr>
            <td align="left"><strong>TOTAL PRODUKSI STENTER</strong></td>
            <td align="center"><strong>=</strong></td>
            <td align="right"><?= number_format($tot_prd_stenterSHF_A+$tot_prd_stenterSHF_B+$tot_prd_stenterSHF_C,2); ?> Kg</td>
            </tr>
          <tr>
            <td align="left"><strong>RATA-RATA (gr/1 kg kain)</strong></td>
            <td align="center"><strong>=</strong></td>
            <td align="right"><?= number_format(round($dtObat['KG']/($tot_prd_stenterSHF_A+$tot_prd_stenterSHF_B+$tot_prd_stenterSHF_C),2),2); ?></td>
            </tr>
        </tbody>
		<tfoot>
		</tfoot>  
      </table>	
		</td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" border="1">
  <tbody>
    <tr>
      <td colspan="25">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="8" align="center" valign="middle"><strong>Dibuat Oleh:</strong></td>
      <td colspan="8" align="center" valign="middle"><strong>Diperiksa Oleh:</strong></td>
      <td colspan="8" align="center" valign="middle"><strong>Diketahui Oleh:</strong></td>
    </tr>
    <tr>
      <td><strong>Nama</strong></td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Jabatan</strong></td>
      <td colspan="16">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Tanggal</strong></td>
      <td colspan="16">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Tanda Tangan</strong></td>
      <td colspan="16">&nbsp;</td>
      <td colspan="8">&nbsp;</td>
    </tr>
  </tbody>
</table>
	
</body>
</html>