<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";

?>
<script src="dist/js/highchart/highcharts.js"></script>
<script src="dist/js/highchart/exporting.js"></script>
<script src="dist/js/highchart/export-data.js"></script>
<script src="dist/js/highchart/accessibility.js"></script>
<style>
/* Styling untuk wadah setiap tabel */
.table-container {
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 15px;
  margin-bottom: 20px; /* Jarak antar baris tabel di layar kecil */
  background-color: #fff;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Judul tabel */
.table-container .box-title {
  font-size: 16px;
  margin-top: 0;
  margin-bottom: 10px;
  font-weight: bold;
  color: #333;
}

/* Membuat tabel bisa di-scroll jika terlalu lebar */
.table-responsive {
  overflow-x: auto;
  width: 100%;
}

/* Menghapus margin default dari bootstrap agar lebih pas */
.row {
    margin-left: -10px;
    margin-right: -10px;
}
.col-lg-6, .col-md-12 {
    padding-left: 10px;
    padding-right: 10px;
}

/* Style dasar tabel agar tetap rapi */
.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
    border-collapse: collapse;
}

.table th,
.table td {
  padding: 8px !important; /* Sedikit lebih lega */
  vertical-align: middle;
  text-align: center;
  border-top: 1px solid #dee2e6;
  white-space: nowrap; /* Mencegah teks turun baris */
  font-size: 17px;
}

.table thead th {
  vertical-align: bottom;
  border-bottom: 2px solid #dee2e6;
}

/* Header biru dari kode Anda */
.bg-blue th {
  background-color: #0073b7 !important;
  color: #fff !important;
  border-color: #005a8e;
}
</style>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Summary Recipe</title>

</head>
<body>
<?php
 $usr_lab = [];
 $usr_dye = [];
 $usr_sblm = [];
 $usr_sudah = [];
  // $sql_col_lab = "SELECT * FROM tbl_user_colorist WHERE dept = 'LAB' and status_active  = 1 and name <> '-' order by name asc";
  // $stmt_col_lab = mysqli_query($cona, $sql_col_lab);
  // while($d_col_lab = mysqli_fetch_assoc($stmt_col_lab)){
  //   $usr_lab[] = $d_col_lab['name'];
  // }

  // $sql_col_dye = "SELECT * FROM tbl_user_colorist WHERE dept = 'DYE' and status_active  = 1 and name <> '-' order by name asc";
  // $stmt_col_dye = mysqli_query($cona, $sql_col_dye);
  // while($d_col_dye = mysqli_fetch_assoc($stmt_col_dye)){
  //   $usr_dye[] = $d_col_dye['name'];
  // }
  // print_r($usr_lab);
  

$Awal	= isset($_POST['awal']) ? $_POST['awal'] : date('Y-m-01');
$Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : date('Y-m-t');

// echo $Awal;
// echo $Akhir;

$Awal_Sebelum = date('Y-m-d', strtotime($Awal . ' -1 day'));
$Akhir_Sebelum = date('Y-m-d', strtotime($Akhir . ' -1 day'));	

$T_ANALISA = $ANALISA = $BELUM_ANALISA = $OK = $TOK = $PROSES = 0;
$T_ANALISA_KECIL = $ANALISA_KECIL = $BELUM_ANALISA_KECIL = $OK_KECIL = $TOK_KECIL = $PROSES_KECIL = 0;
$T_ANALISA_BESAR = $ANALISA_BESAR = $BELUM_ANALISA_BESAR = $OK_BESAR = $TOK_BESAR = $PROSES_BESAR = 0;

$R_LAMA = $R_BARU = $R_SETTING = 0;
$R_LAMA_KECIL = $R_BARU_KECIL = $R_SETTING_KECIL = 0;
$R_LAMA_BESAR = $R_BARU_BESAR = $R_SETTING_BESAR = 0;

$STS_RESEP_OK = $STS_RESEP_TOK = $STS_RESEP_PROGRESS = $STS_RESEP_TOBAT_OK = $STS_RESEP_TOBAT_TOK = $STS_RESEP_TOBAT_PROGRESS = 0;
$STS_RESEP_OK_KECIL = $STS_RESEP_TOK_KECIL = $STS_RESEP_PROGRESS_KECIL = $STS_RESEP_TOBAT_OK_KECIL = $STS_RESEP_TOBAT_TOK_KECIL = $STS_RESEP_TOBAT_PROGRESS_KECIL = 0;
$STS_RESEP_OK_BESAR = $STS_RESEP_TOK_BESAR = $STS_RESEP_PROGRESS_BESAR = $STS_RESEP_TOBAT_OK_BESAR = $STS_RESEP_TOBAT_TOK_BESAR = $STS_RESEP_TOBAT_PROGRESS_BESAR = 0;

$ROOT_MAN_KECIL= $ROOT_MACHINE_KECIL = $ROOT_METHODE_KECIL = $ROOT_MEASUREMENT_KECIL = $ROOT_MATERIAL_KECIL = $ROOT_ENVIRONMENT_KECIL = 0;
$ROOT_MAN_BESAR= $ROOT_MACHINE_BESAR = $ROOT_METHODE_BESAR = $ROOT_MEASUREMENT_BESAR = $ROOT_MATERIAL_BESAR = $ROOT_ENVIRONMENT_BESAR = 0;

$LOT_T_OBAT_0_LAMA = $LOT_T_OBAT_0_BARU = $LOT_T_OBAT_0_SETTING = $LOT_T_OBAT_0_LAMA_KECIL = $LOT_T_OBAT_0_BARU_KECIL = $LOT_T_OBAT_0_SETTING_KECIL = $LOT_T_OBAT_0_LAMA_BESAR = $LOT_T_OBAT_0_BARU_BESAR = $LOT_T_OBAT_0_SETTING_BESAR = 0;
$QTY_T_OBAT_0_LAMA = $QTY_T_OBAT_0_BARU = $QTY_T_OBAT_0_SETTING = $QTY_T_OBAT_0_LAMA_KECIL = $QTY_T_OBAT_0_BARU_KECIL = $QTY_T_OBAT_0_SETTING_KECIL = $QTY_T_OBAT_0_LAMA_BESAR = $QTY_T_OBAT_0_BARU_BESAR = $QTY_T_OBAT_0_SETTING_BESAR = 0;
$LOT_T_OBAT_1_LAMA = $LOT_T_OBAT_1_BARU = $LOT_T_OBAT_1_SETTING = $LOT_T_OBAT_1_LAMA_KECIL = $LOT_T_OBAT_1_BARU_KECIL = $LOT_T_OBAT_1_SETTING_KECIL = $LOT_T_OBAT_1_LAMA_BESAR = $LOT_T_OBAT_1_BARU_BESAR = $LOT_T_OBAT_1_SETTING_BESAR = 0;
$QTY_T_OBAT_1_LAMA = $QTY_T_OBAT_1_BARU = $QTY_T_OBAT_1_SETTING = $QTY_T_OBAT_1_LAMA_KECIL = $QTY_T_OBAT_1_BARU_KECIL = $QTY_T_OBAT_1_SETTING_KECIL = $QTY_T_OBAT_1_LAMA_BESAR = $QTY_T_OBAT_1_BARU_BESAR = $QTY_T_OBAT_1_SETTING_BESAR = 0;
$LOT_T_OBAT_2_LAMA = $LOT_T_OBAT_2_BARU = $LOT_T_OBAT_2_SETTING = $LOT_T_OBAT_2_LAMA_KECIL = $LOT_T_OBAT_2_BARU_KECIL = $LOT_T_OBAT_2_SETTING_KECIL = $LOT_T_OBAT_2_LAMA_BESAR = $LOT_T_OBAT_2_BARU_BESAR = $LOT_T_OBAT_2_SETTING_BESAR = 0;
$QTY_T_OBAT_2_LAMA = $QTY_T_OBAT_2_BARU = $QTY_T_OBAT_2_SETTING = $QTY_T_OBAT_2_LAMA_KECIL = $QTY_T_OBAT_2_BARU_KECIL = $QTY_T_OBAT_2_SETTING_KECIL = $QTY_T_OBAT_2_LAMA_BESAR = $QTY_T_OBAT_2_BARU_BESAR = $QTY_T_OBAT_2_SETTING_BESAR = 0;
$LOT_T_OBAT_3_LAMA = $LOT_T_OBAT_3_BARU = $LOT_T_OBAT_3_SETTING = $LOT_T_OBAT_3_LAMA_KECIL = $LOT_T_OBAT_3_BARU_KECIL = $LOT_T_OBAT_3_SETTING_KECIL = $LOT_T_OBAT_3_LAMA_BESAR = $LOT_T_OBAT_3_BARU_BESAR = $LOT_T_OBAT_3_SETTING_BESAR = 0;
$QTY_T_OBAT_3_LAMA = $QTY_T_OBAT_3_BARU = $QTY_T_OBAT_3_SETTING = $QTY_T_OBAT_3_LAMA_KECIL = $QTY_T_OBAT_3_BARU_KECIL = $QTY_T_OBAT_3_SETTING_KECIL = $QTY_T_OBAT_3_LAMA_BESAR = $QTY_T_OBAT_3_BARU_BESAR = $QTY_T_OBAT_3_SETTING_BESAR = 0;
$LOT_T_OBAT_4_LAMA = $LOT_T_OBAT_4_BARU = $LOT_T_OBAT_4_SETTING = $LOT_T_OBAT_4_LAMA_KECIL = $LOT_T_OBAT_4_BARU_KECIL = $LOT_T_OBAT_4_SETTING_KECIL = $LOT_T_OBAT_4_LAMA_BESAR = $LOT_T_OBAT_4_BARU_BESAR = $LOT_T_OBAT_4_SETTING_BESAR = 0;
$QTY_T_OBAT_4_LAMA = $QTY_T_OBAT_4_BARU = $QTY_T_OBAT_4_SETTING = $QTY_T_OBAT_4_LAMA_KECIL = $QTY_T_OBAT_4_BARU_KECIL = $QTY_T_OBAT_4_SETTING_KECIL = $QTY_T_OBAT_4_LAMA_BESAR = $QTY_T_OBAT_4_BARU_BESAR = $QTY_T_OBAT_4_SETTING_BESAR = 0;
$LOT_T_OBAT_5_LAMA = $LOT_T_OBAT_5_BARU = $LOT_T_OBAT_5_SETTING = $LOT_T_OBAT_5_LAMA_KECIL = $LOT_T_OBAT_5_BARU_KECIL = $LOT_T_OBAT_5_SETTING_KECIL = $LOT_T_OBAT_5_LAMA_BESAR = $LOT_T_OBAT_5_BARU_BESAR = $LOT_T_OBAT_5_SETTING_BESAR = 0;
$QTY_T_OBAT_5_LAMA = $QTY_T_OBAT_5_BARU = $QTY_T_OBAT_5_SETTING = $QTY_T_OBAT_5_LAMA_KECIL = $QTY_T_OBAT_5_BARU_KECIL = $QTY_T_OBAT_5_SETTING_KECIL = $QTY_T_OBAT_5_LAMA_BESAR = $QTY_T_OBAT_5_BARU_BESAR = $QTY_T_OBAT_5_SETTING_BESAR = 0;
$LOT_T_OBAT_6_LAMA = $LOT_T_OBAT_6_BARU = $LOT_T_OBAT_6_SETTING = $LOT_T_OBAT_6_LAMA_KECIL = $LOT_T_OBAT_6_BARU_KECIL = $LOT_T_OBAT_6_SETTING_KECIL = $LOT_T_OBAT_6_LAMA_BESAR = $LOT_T_OBAT_6_BARU_BESAR = $LOT_T_OBAT_6_SETTING_BESAR = 0;
$QTY_T_OBAT_6_LAMA = $QTY_T_OBAT_6_BARU = $QTY_T_OBAT_6_SETTING = $QTY_T_OBAT_6_LAMA_KECIL = $QTY_T_OBAT_6_BARU_KECIL = $QTY_T_OBAT_6_SETTING_KECIL = $QTY_T_OBAT_6_LAMA_BESAR = $QTY_T_OBAT_6_BARU_BESAR = $QTY_T_OBAT_6_SETTING_BESAR = 0;
$LOT_T_OBAT_SETTING = $QTY_T_OBAT_SETTING = $LOT_T_OBAT_LAMA = $QTY_T_OBAT_LAMA = $LOT_T_OBAT_BARU = $QTY_T_OBAT_BARU = 0;
$LOT_T_OBAT = $QTY_T_OBAT = 0;

$LAB_DYE_CQA_T_OBAT_KECIL = $LAB_DYE_CQA_T_OBAT_BESAR = $LAB_DYE_CQA_T_OBAT_TOTAL =  $LAB_DYE_CQA_TOK_TOTAL = $LAB_DYE_CQA_TOK_KECIL = $LAB_DYE_CQA_TOK_BESAR = 0; 
$LAIN_T_OBAT_KECIL = $LAIN_T_OBAT_BESAR = $LAIN_T_OBAT_TOTAL =  $LAIN_TOK_TOTAL = $LAIN_TOK_KECIL = $LAIN_TOK_BESAR = 0; 
$LAB_T_OBAT_KECIL = $LAB_T_OBAT_BESAR = $LAB_T_OBAT_TOTAL =  $LAB_TOK_TOTAL = $LAB_TOK_KECIL = $LAB_TOK_BESAR = 0; 
$DYE_T_OBAT_KECIL = $DYE_T_OBAT_BESAR = $DYE_T_OBAT_TOTAL =  $DYE_TOK_TOTAL = $DYE_TOK_KECIL = $DYE_TOK_BESAR = 0; 
$CQA_T_OBAT_KECIL = $CQA_T_OBAT_BESAR = $CQA_T_OBAT_TOTAL =  $CQA_TOK_TOTAL = $CQA_TOK_KECIL = $CQA_TOK_BESAR = 0; 
$LAB_DYE_T_OBAT_KECIL = $LAB_DYE_T_OBAT_BESAR = $LAB_DYE_T_OBAT_TOTAL =  $LAB_DYE_TOK_TOTAL = $LAB_DYE_TOK_KECIL = $LAB_DYE_TOK_BESAR = 0; 
$LAB_CQA_T_OBAT_KECIL = $LAB_CQA_T_OBAT_BESAR = $LAB_CQA_T_OBAT_TOTAL =  $LAB_CQA_TOK_TOTAL = $LAB_CQA_TOK_KECIL = $LAB_CQA_TOK_BESAR = 0; 
$DYE_CQA_T_OBAT_KECIL = $DYE_CQA_T_OBAT_BESAR = $DYE_CQA_T_OBAT_TOTAL =  $DYE_CQA_TOK_TOTAL = $DYE_CQA_TOK_KECIL = $DYE_CQA_TOK_BESAR = 0; 

$AGUNG_CAHYONO_OK_LAB = $CITRA_OK_LAB = $FERDINAND_OK_LAB = $GANANG_OK_LAB = $GUGUM_OK_LAB = $GUNAWAN_OK_LAB = $HENDRIK_OK_LAB = $HUANG_XIAOMING_OK_LAB = $INDAH_OK_LAB = $JONI_OK_LAB = $NOVIA_OK_LAB = $TIDAK_MATCHING_OK_LAB = 0;
$AGUNG_CAHYONO_TOK_LAB = $CITRA_TOK_LAB = $FERDINAND_TOK_LAB = $GANANG_TOK_LAB = $GUGUM_TOK_LAB = $GUNAWAN_TOK_LAB = $HENDRIK_TOK_LAB = $HUANG_XIAOMING_TOK_LAB = $INDAH_TOK_LAB = $JONI_TOK_LAB = $NOVIA_TOK_LAB = $TIDAK_MATCHING_TOK_LAB = 0;

// Function analisa resep
  function analisaResep($mesin, $proses, $analisa)
    {
      global $T_ANALISA_KECIL , $ANALISA_KECIL , $BELUM_ANALISA_KECIL , $OK_KECIL , $TOK_KECIL , $PROSES_KECIL, $T_ANALISA_BESAR , 
              $ANALISA_BESAR , $BELUM_ANALISA_BESAR , $OK_BESAR , $TOK_BESAR , $PROSES_BESAR, $T_ANALISA, $ANALISA, $BELUM_ANALISA, $OK, $TOK, $PROSES;   

      if($mesin==='kecil' && $proses === 'Celup Greige'){
        $T_ANALISA_KECIL +=1;
      }

      if($mesin==='kecil' && $proses === 'Celup Greige' && $analisa === 'Oke' ){
        $OK_KECIL +=1;
      }else if($mesin==='kecil' && $proses === 'Celup Greige' && $analisa === 'Tidak Oke' ){
        $TOK_KECIL +=1;
      }

      if($mesin==='kecil' && $proses === 'Celup Greige' && ($analisa === 'Test LAB'||$analisa === 'Follow'||$analisa === 'Review')){
        $PROSES_KECIL += 1;
      }else if($mesin==='kecil' && $proses === 'Celup Greige' && $analisa === 'Belum Analisa'){
        $BELUM_ANALISA_KECIL += 1; 
      }

      if($mesin==='kecil' && $proses === 'Celup Greige' && !empty($analisa) && $analisa !== 'Belum Analisa'){
        $ANALISA_KECIL += 1; 
      }

      if($mesin==='besar' && $proses === 'Celup Greige'){
        $T_ANALISA_BESAR +=1;
      }

      if($mesin==='besar' && $proses === 'Celup Greige' && $analisa === 'Oke' ){
        $OK_BESAR +=1;
      }else if($mesin==='besar' && $proses === 'Celup Greige' && $analisa === 'Tidak Oke' ){
        $TOK_BESAR +=1;
      }

      if($mesin==='besar' && $proses === 'Celup Greige' && ($analisa === 'Test LAB'||$analisa === 'Follow'||$analisa === 'Review')){
        $PROSES_BESAR += 1;
      }else if($mesin==='besar' && $proses === 'Celup Greige' && $analisa === 'Belum Analisa'){
        $BELUM_ANALISA_BESAR += 1; 
      }

      if($mesin==='besar' && $proses === 'Celup Greige' && !empty($analisa) &&  $analisa !== 'Belum Analisa'){
        $ANALISA_BESAR += 1; 
      }

      if($proses === 'Celup Greige'){
        $T_ANALISA +=1;
      }

      if($proses === 'Celup Greige' && $analisa === 'Oke' ){
        $OK +=1;
      }else if($proses === 'Celup Greige' && $analisa === 'Tidak Oke' ){
        $TOK +=1;
      }

      if($proses === 'Celup Greige' && ($analisa === 'Test LAB'||$analisa === 'Follow'||$analisa === 'Review')){
        $PROSES += 1;
      }else if($proses === 'Celup Greige' && $analisa === 'Belum Analisa'){
        $BELUM_ANALISA += 1; 
      }

      if($proses === 'Celup Greige' && !empty($analisa) && $analisa !== 'Belum Analisa'){
        $ANALISA += 1; 
      }
    }
// End analisa resep

// Function Status resep
  function statusResep($mesin, $kategori, $proses_t, $analisa, $penyebab)
    {
      global $STS_RESEP_OK , $STS_RESEP_TOK , $STS_RESEP_PROGRESS , $STS_RESEP_TOBAT_OK , $STS_RESEP_TOBAT_TOK , $STS_RESEP_TOBAT_PROGRESS , $STS_RESEP_OK_KECIL , $STS_RESEP_TOK_KECIL , $STS_RESEP_PROGRESS_KECIL , $STS_RESEP_TOBAT_OK_KECIL , $STS_RESEP_TOBAT_TOK_KECIL , $STS_RESEP_TOBAT_PROGRESS_KECIL , $STS_RESEP_OK_BESAR , $STS_RESEP_TOK_BESAR , $STS_RESEP_PROGRESS_BESAR , $STS_RESEP_TOBAT_OK_BESAR , $STS_RESEP_TOBAT_TOK_BESAR , $STS_RESEP_TOBAT_PROGRESS_BESAR, $ROOT_MAN_KECIL, $ROOT_MACHINE_KECIL , $ROOT_METHODE_KECIL , $ROOT_MEASUREMENT_KECIL , $ROOT_MATERIAL_KECIL, $ROOT_ENVIRONMENT_KECIL ,$ROOT_MAN_BESAR, $ROOT_MACHINE_BESAR , $ROOT_METHODE_BESAR , $ROOT_MEASUREMENT_BESAR , $ROOT_MATERIAL_BESAR, $ROOT_ENVIRONMENT_BESAR;

      if(!empty($proses_t))
        {
          // $proses = (int) preg_replace('/\D/', '', $proses_t);
          $proses = str_replace("x", "", $proses_t);
          
          if($kategori === 'Celup Greige' && $proses === '0' && ($analisa === 'Test LAB'||$analisa === 'Follow'||$analisa === 'Review')){
            $STS_RESEP_PROGRESS +=1;
          }else if($kategori === 'Celup Greige' && $proses === '0' && $analisa === 'Oke'){
            $STS_RESEP_OK +=1;
          }else if($kategori === 'Celup Greige' && $proses === '0' && $analisa === 'Tidak Oke'){
            $STS_RESEP_TOK +=1;
          }

          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses === '0' && $analisa === 'Oke' ){
            $STS_RESEP_OK_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses === '0' && $analisa === 'Tidak Oke' ){
            $STS_RESEP_TOK_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses === '0' && ($analisa === 'Test LAB'||$analisa === 'Follow'||$analisa === 'Review')){
            $STS_RESEP_PROGRESS_KECIL +=1;
          }

          if($kategori === 'Celup Greige' && $mesin==='besar' && $proses === '0' && $analisa === 'Oke' ){
            $STS_RESEP_OK_BESAR +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses === '0' && $analisa === 'Tidak Oke' ){
            $STS_RESEP_TOK_BESAR +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses === '0' && ($analisa === 'Test LAB'||$analisa === 'Follow'||$analisa === 'Review')){
            $STS_RESEP_PROGRESS_BESAR +=1;
          }

          if($kategori === 'Celup Greige' && $proses > '0' && ($analisa === 'Test LAB'||$analisa === 'Follow'||$analisa === 'Review')){
            $STS_RESEP_TOBAT_PROGRESS +=1;
          }else if($kategori === 'Celup Greige' && $proses > '0' && $analisa === 'Oke'){
            $STS_RESEP_TOBAT_OK +=1;
          }else if($kategori === 'Celup Greige' && $proses > '0' && $analisa === 'Tidak Oke'){
            $STS_RESEP_TOBAT_TOK +=1;
          }

          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0' && $analisa === 'Oke' ){
            $STS_RESEP_TOBAT_OK_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0' && $analisa === 'Tidak Oke' ){
            $STS_RESEP_TOBAT_TOK_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0' && ($analisa === 'Test LAB'||$analisa === 'Follow'||$analisa === 'Review')){
            $STS_RESEP_TOBAT_PROGRESS_KECIL +=1;
          }

          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0' && $penyebab === 'MAN' ){
            $ROOT_MAN_KECIL += 1;
          }else if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0' && $penyebab === 'MACHINE' ){
            $ROOT_MACHINE_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0' && $penyebab === 'METHODE' ){
            $ROOT_METHODE_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0' && $penyebab === 'MATERIAL' ){
            $ROOT_MATERIAL_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0' && $penyebab === 'MEASUREMENT' ){
            $ROOT_MEASUREMENT_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0' && $penyebab === 'ENVIRONMENT' ){
            $ROOT_ENVIRONMENT_KECIL +=1;
          }

          if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0' && $penyebab === 'MAN' ){
            $ROOT_MAN_BESAR +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0' && $penyebab === 'MACHINE' ){
            $ROOT_MACHINE_BESAR +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0' && $penyebab === 'METHODE' ){
            $ROOT_METHODE_BESAR +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0' && $penyebab === 'MATERIAL' ){
            $ROOT_MATERIAL_BESAR +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0' && $penyebab === 'MEASUREMENT' ){
            $ROOT_MEASUREMENT_BESAR +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0' && $penyebab === 'ENVIRONMENT' ){
            $ROOT_ENVIRONMENT_BESAR +=1;
          }

          if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0' && $analisa === 'Oke' ){
            $STS_RESEP_TOBAT_OK_BESAR +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0' && $analisa === 'Tidak Oke' ){
            $STS_RESEP_TOBAT_TOK_BESAR +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0' && ($analisa === 'Test LAB'||$analisa === 'Follow'||$analisa === 'Review')){
            $STS_RESEP_TOBAT_PROGRESS_BESAR +=1;
          }
        }
    }
// End status resep

// Function Jenis resep
  function jenisResep($mesin, $proses, $jresep, $bruto, $k_resep)
    {
      global $R_LAMA , $R_BARU , $R_SETTING , $R_LAMA_KECIL , $R_BARU_KECIL , $R_SETTING_KECIL , $R_LAMA_BESAR , $R_BARU_BESAR , $R_SETTING_BESAR, 
              $LOT_T_OBAT_0_LAMA, $LOT_T_OBAT_0_BARU, $LOT_T_OBAT_0_SETTING,
              $QTY_T_OBAT_0_LAMA, $QTY_T_OBAT_0_BARU, $QTY_T_OBAT_0_SETTING,
              $LOT_T_OBAT_1_LAMA, $LOT_T_OBAT_1_BARU, $LOT_T_OBAT_1_SETTING,
              $QTY_T_OBAT_1_LAMA, $QTY_T_OBAT_1_BARU, $QTY_T_OBAT_1_SETTING,
              $LOT_T_OBAT_2_LAMA, $LOT_T_OBAT_2_BARU, $LOT_T_OBAT_2_SETTING,
              $QTY_T_OBAT_2_LAMA, $QTY_T_OBAT_2_BARU, $QTY_T_OBAT_2_SETTING,
              $LOT_T_OBAT_3_LAMA, $LOT_T_OBAT_3_BARU, $LOT_T_OBAT_3_SETTING,
              $QTY_T_OBAT_3_LAMA, $QTY_T_OBAT_3_BARU, $QTY_T_OBAT_3_SETTING,
              $LOT_T_OBAT_4_LAMA, $LOT_T_OBAT_4_BARU, $LOT_T_OBAT_4_SETTING,
              $QTY_T_OBAT_4_LAMA, $QTY_T_OBAT_4_BARU, $QTY_T_OBAT_4_SETTING,
              $LOT_T_OBAT_5_LAMA, $LOT_T_OBAT_5_BARU, $LOT_T_OBAT_5_SETTING,
              $QTY_T_OBAT_5_LAMA, $QTY_T_OBAT_5_BARU, $QTY_T_OBAT_5_SETTING,
              $LOT_T_OBAT_6_LAMA, $LOT_T_OBAT_6_BARU, $LOT_T_OBAT_6_SETTING,
              $QTY_T_OBAT_6_LAMA, $QTY_T_OBAT_6_BARU, $QTY_T_OBAT_6_SETTING,
              $LOT_T_OBAT_0_LAMA_KECIL, $LOT_T_OBAT_0_BARU_KECIL, $LOT_T_OBAT_0_SETTING_KECIL,
              $QTY_T_OBAT_0_LAMA_KECIL, $QTY_T_OBAT_0_BARU_KECIL, $QTY_T_OBAT_0_SETTING_KECIL,
              $LOT_T_OBAT_1_LAMA_KECIL, $LOT_T_OBAT_1_BARU_KECIL, $LOT_T_OBAT_1_SETTING_KECIL,
              $QTY_T_OBAT_1_LAMA_KECIL, $QTY_T_OBAT_1_BARU_KECIL, $QTY_T_OBAT_1_SETTING_KECIL,
              $LOT_T_OBAT_2_LAMA_KECIL, $LOT_T_OBAT_2_BARU_KECIL, $LOT_T_OBAT_2_SETTING_KECIL,
              $QTY_T_OBAT_2_LAMA_KECIL, $QTY_T_OBAT_2_BARU_KECIL, $QTY_T_OBAT_2_SETTING_KECIL,
              $LOT_T_OBAT_3_LAMA_KECIL, $LOT_T_OBAT_3_BARU_KECIL, $LOT_T_OBAT_3_SETTING_KECIL,
              $QTY_T_OBAT_3_LAMA_KECIL, $QTY_T_OBAT_3_BARU_KECIL, $QTY_T_OBAT_3_SETTING_KECIL,
              $LOT_T_OBAT_4_LAMA_KECIL, $LOT_T_OBAT_4_BARU_KECIL, $LOT_T_OBAT_4_SETTING_KECIL,
              $QTY_T_OBAT_4_LAMA_KECIL, $QTY_T_OBAT_4_BARU_KECIL, $QTY_T_OBAT_4_SETTING_KECIL,
              $LOT_T_OBAT_5_LAMA_KECIL, $LOT_T_OBAT_5_BARU_KECIL, $LOT_T_OBAT_5_SETTING_KECIL,
              $QTY_T_OBAT_5_LAMA_KECIL, $QTY_T_OBAT_5_BARU_KECIL, $QTY_T_OBAT_5_SETTING_KECIL,
              $LOT_T_OBAT_6_LAMA_KECIL, $LOT_T_OBAT_6_BARU_KECIL, $LOT_T_OBAT_6_SETTING_KECIL,
              $QTY_T_OBAT_6_LAMA_KECIL, $QTY_T_OBAT_6_BARU_KECIL, $QTY_T_OBAT_6_SETTING_KECIL,
              $LOT_T_OBAT_0_LAMA_BESAR, $LOT_T_OBAT_0_BARU_BESAR, $LOT_T_OBAT_0_SETTING_BESAR,
              $QTY_T_OBAT_0_LAMA_BESAR, $QTY_T_OBAT_0_BARU_BESAR, $QTY_T_OBAT_0_SETTING_BESAR,
              $LOT_T_OBAT_1_LAMA_BESAR, $LOT_T_OBAT_1_BARU_BESAR, $LOT_T_OBAT_1_SETTING_BESAR,
              $QTY_T_OBAT_1_LAMA_BESAR, $QTY_T_OBAT_1_BARU_BESAR, $QTY_T_OBAT_1_SETTING_BESAR,
              $LOT_T_OBAT_2_LAMA_BESAR, $LOT_T_OBAT_2_BARU_BESAR, $LOT_T_OBAT_2_SETTING_BESAR,
              $QTY_T_OBAT_2_LAMA_BESAR, $QTY_T_OBAT_2_BARU_BESAR, $QTY_T_OBAT_2_SETTING_BESAR,
              $LOT_T_OBAT_3_LAMA_BESAR, $LOT_T_OBAT_3_BARU_BESAR, $LOT_T_OBAT_3_SETTING_BESAR,
              $QTY_T_OBAT_3_LAMA_BESAR, $QTY_T_OBAT_3_BARU_BESAR, $QTY_T_OBAT_3_SETTING_BESAR,
              $LOT_T_OBAT_4_LAMA_BESAR, $LOT_T_OBAT_4_BARU_BESAR, $LOT_T_OBAT_4_SETTING_BESAR,
              $QTY_T_OBAT_4_LAMA_BESAR, $QTY_T_OBAT_4_BARU_BESAR, $QTY_T_OBAT_4_SETTING_BESAR,
              $LOT_T_OBAT_5_LAMA_BESAR, $LOT_T_OBAT_5_BARU_BESAR, $LOT_T_OBAT_5_SETTING_BESAR,
              $QTY_T_OBAT_5_LAMA_BESAR, $QTY_T_OBAT_5_BARU_BESAR, $QTY_T_OBAT_5_SETTING_BESAR,
              $LOT_T_OBAT_6_LAMA_BESAR, $LOT_T_OBAT_6_BARU_BESAR, $LOT_T_OBAT_6_SETTING_BESAR,
              $QTY_T_OBAT_6_LAMA_BESAR, $QTY_T_OBAT_6_BARU_BESAR, $QTY_T_OBAT_6_SETTING_BESAR,
              $LOT_T_OBAT_SETTING, $QTY_T_OBAT_SETTING, $LOT_T_OBAT_LAMA, 
              $QTY_T_OBAT_LAMA, $LOT_T_OBAT_BARU, $QTY_T_OBAT_BARU, $LOT_T_OBAT, $QTY_T_OBAT; 

      if($mesin==='kecil' && $proses === 'Celup Greige' && $jresep === 'Setting' ){
        $R_SETTING_KECIL +=1;
      }else if($mesin==='kecil' && $proses === 'Celup Greige' && $jresep === 'Lama' ){
        $R_LAMA_KECIL +=1;
      }else if($mesin==='kecil' && $proses === 'Celup Greige' && $jresep === 'Baru' ){
        $R_BARU_KECIL +=1;
      }

      if($mesin==='besar' && $proses === 'Celup Greige' && $jresep === 'Setting' ){
        $R_SETTING_BESAR +=1;
      }else if($mesin==='besar' && $proses === 'Celup Greige' && $jresep === 'Lama' ){
        $R_LAMA_BESAR +=1;
      }else if($mesin==='besar' && $proses === 'Celup Greige' && $jresep === 'Baru' ){
        $R_BARU_BESAR +=1;
      }

      if($proses === 'Celup Greige' && $jresep === 'Setting' ){
        $R_SETTING +=1;
      }else if($proses === 'Celup Greige' && $jresep === 'Lama' ){
        $R_LAMA +=1;
      }else if($proses === 'Celup Greige' && $jresep === 'Baru' ){
        $R_BARU +=1;
      }

      if($proses === 'Celup Greige' && $jresep === 'Setting' ){
          $LOT_T_OBAT_SETTING += 1;
          $QTY_T_OBAT_SETTING += $bruto;
        }else if($proses === 'Celup Greige' && $jresep === 'Lama' ){
          $LOT_T_OBAT_LAMA += 1;
          $QTY_T_OBAT_LAMA += $bruto;
        }else if($proses === 'Celup Greige' && $jresep === 'Baru' ){
          $LOT_T_OBAT_BARU += 1;
          $QTY_T_OBAT_BARU += $bruto;
        }

      // if($proses === 'Celup Greige' && ($jresep === 'Setting'|| $jresep === 'Lama' || $jresep === 'Baru')){
      //     $LOT_T_OBAT += 1;
      //     $QTY_T_OBAT += $bruto;
      //   }

      if(!empty($k_resep)){
        $resep = str_replace("x", "", $k_resep);

        if($resep==='0' && $proses === 'Celup Greige' && $jresep === 'Setting' ){
          $LOT_T_OBAT_0_SETTING += 1;
          $QTY_T_OBAT_0_SETTING += $bruto;
        }else if($resep==='0' && $proses === 'Celup Greige' && $jresep === 'Lama' ){
          $LOT_T_OBAT_0_LAMA += 1;
          $QTY_T_OBAT_0_LAMA += $bruto;
        }else if($resep==='0' && $proses === 'Celup Greige' && $jresep === 'Baru' ){
          $LOT_T_OBAT_0_BARU += 1;
          $QTY_T_OBAT_0_BARU += $bruto;
        }

        if($resep==='0' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='kecil'){
          $LOT_T_OBAT_0_SETTING_KECIL += 1;
          $QTY_T_OBAT_0_SETTING_KECIL += $bruto;
        }else if($resep==='0' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='kecil'){
          $LOT_T_OBAT_0_LAMA_KECIL += 1;
          $QTY_T_OBAT_0_LAMA_KECIL += $bruto;
        }else if($resep==='0' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='kecil'){
          $LOT_T_OBAT_0_BARU_KECIL += 1;
          $QTY_T_OBAT_0_BARU_KECIL += $bruto;
        }

        if($resep==='0' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='besar'){
          $LOT_T_OBAT_0_SETTING_BESAR += 1;
          $QTY_T_OBAT_0_SETTING_BESAR += $bruto;
        }else if($resep==='0' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='besar'){
          $LOT_T_OBAT_0_LAMA_BESAR += 1;
          $QTY_T_OBAT_0_LAMA_BESAR += $bruto;
        }else if($resep==='0' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='besar'){
          $LOT_T_OBAT_0_BARU_BESAR += 1;
          $QTY_T_OBAT_0_BARU_BESAR += $bruto;
        }

        if($resep==='1' && $proses === 'Celup Greige' && $jresep === 'Setting' ){
          $LOT_T_OBAT_1_SETTING += 1;
          $QTY_T_OBAT_1_SETTING += $bruto;
        }else if($resep==='1' && $proses === 'Celup Greige' && $jresep === 'Lama' ){
          $LOT_T_OBAT_1_LAMA += 1;
          $QTY_T_OBAT_1_LAMA += $bruto;
        }else if($resep==='1' && $proses === 'Celup Greige' && $jresep === 'Baru' ){
          $LOT_T_OBAT_1_BARU += 1;
          $QTY_T_OBAT_1_BARU += $bruto;
        }

        if($resep==='1' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='kecil'){
          $LOT_T_OBAT_1_SETTING_KECIL += 1;
          $QTY_T_OBAT_1_SETTING_KECIL += $bruto;
        }else if($resep==='1' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='kecil'){
          $LOT_T_OBAT_1_LAMA_KECIL += 1;
          $QTY_T_OBAT_1_LAMA_KECIL += $bruto;
        }else if($resep==='1' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='kecil'){
          $LOT_T_OBAT_1_BARU_KECIL += 1;
          $QTY_T_OBAT_1_BARU_KECIL += $bruto;
        }

        if($resep==='1' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='besar'){
          $LOT_T_OBAT_1_SETTING_BESAR += 1;
          $QTY_T_OBAT_1_SETTING_BESAR += $bruto;
        }else if($resep==='1' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='besar'){
          $LOT_T_OBAT_1_LAMA_BESAR += 1;
          $QTY_T_OBAT_1_LAMA_BESAR += $bruto;
        }else if($resep==='1' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='besar'){
          $LOT_T_OBAT_1_BARU_BESAR += 1;
          $QTY_T_OBAT_1_BARU_BESAR += $bruto;
        }

        if($resep==='2' && $proses === 'Celup Greige' && $jresep === 'Setting' ){
          $LOT_T_OBAT_2_SETTING += 1;
          $QTY_T_OBAT_2_SETTING += $bruto;
        }else if($resep==='2' && $proses === 'Celup Greige' && $jresep === 'Lama' ){
          $LOT_T_OBAT_2_LAMA += 1;
          $QTY_T_OBAT_2_LAMA += $bruto;
        }else if($resep==='2' && $proses === 'Celup Greige' && $jresep === 'Baru' ){
          $LOT_T_OBAT_2_BARU += 1;
          $QTY_T_OBAT_2_BARU += $bruto;
        }

        if($resep==='2' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='kecil'){
          $LOT_T_OBAT_2_SETTING_KECIL += 1;
          $QTY_T_OBAT_2_SETTING_KECIL += $bruto;
        }else if($resep==='2' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='kecil'){
          $LOT_T_OBAT_2_LAMA_KECIL += 1;
          $QTY_T_OBAT_2_LAMA_KECIL += $bruto;
        }else if($resep==='2' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='kecil'){
          $LOT_T_OBAT_2_BARU_KECIL += 1;
          $QTY_T_OBAT_2_BARU_KECIL += $bruto;
        }

        if($resep==='2' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='besar'){
          $LOT_T_OBAT_2_SETTING_BESAR += 1;
          $QTY_T_OBAT_2_SETTING_BESAR += $bruto;
        }else if($resep==='2' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='besar'){
          $LOT_T_OBAT_2_LAMA_BESAR += 1;
          $QTY_T_OBAT_2_LAMA_BESAR += $bruto;
        }else if($resep==='2' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='besar'){
          $LOT_T_OBAT_2_BARU_BESAR += 1;
          $QTY_T_OBAT_2_BARU_BESAR += $bruto;
        }

        if($resep==='3' && $proses === 'Celup Greige' && $jresep === 'Setting' ){
          $LOT_T_OBAT_3_SETTING += 1;
          $QTY_T_OBAT_3_SETTING += $bruto;
        }else if($resep==='3' && $proses === 'Celup Greige' && $jresep === 'Lama' ){
          $LOT_T_OBAT_3_LAMA += 1;
          $QTY_T_OBAT_3_LAMA += $bruto;
        }else if($resep==='3' && $proses === 'Celup Greige' && $jresep === 'Baru' ){
          $LOT_T_OBAT_3_BARU += 1;
          $QTY_T_OBAT_3_BARU += $bruto;
        }

        if($resep==='3' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='kecil'){
          $LOT_T_OBAT_3_SETTING_KECIL += 1;
          $QTY_T_OBAT_3_SETTING_KECIL += $bruto;
        }else if($resep==='3' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='kecil'){
          $LOT_T_OBAT_3_LAMA_KECIL += 1;
          $QTY_T_OBAT_3_LAMA_KECIL += $bruto;
        }else if($resep==='3' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='kecil'){
          $LOT_T_OBAT_3_BARU_KECIL += 1;
          $QTY_T_OBAT_3_BARU_KECIL += $bruto;
        }

        if($resep==='3' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='besar'){
          $LOT_T_OBAT_3_SETTING_BESAR += 1;
          $QTY_T_OBAT_3_SETTING_BESAR += $bruto;
        }else if($resep==='3' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='besar'){
          $LOT_T_OBAT_3_LAMA_BESAR += 1;
          $QTY_T_OBAT_3_LAMA_BESAR += $bruto;
        }else if($resep==='3' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='besar'){
          $LOT_T_OBAT_3_BARU_BESAR += 1;
          $QTY_T_OBAT_3_BARU_BESAR += $bruto;
        }

        if($resep==='4' && $proses === 'Celup Greige' && $jresep === 'Setting' ){
          $LOT_T_OBAT_4_SETTING += 1;
          $QTY_T_OBAT_4_SETTING += $bruto;
        }else if($resep==='4' && $proses === 'Celup Greige' && $jresep === 'Lama' ){
          $LOT_T_OBAT_4_LAMA += 1;
          $QTY_T_OBAT_4_LAMA += $bruto;
        }else if($resep==='4' && $proses === 'Celup Greige' && $jresep === 'Baru' ){
          $LOT_T_OBAT_4_BARU += 1;
          $QTY_T_OBAT_4_BARU += $bruto;
        }

        if($resep==='4' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='kecil'){
          $LOT_T_OBAT_4_SETTING_KECIL += 1;
          $QTY_T_OBAT_4_SETTING_KECIL += $bruto;
        }else if($resep==='4' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='kecil'){
          $LOT_T_OBAT_4_LAMA_KECIL += 1;
          $QTY_T_OBAT_4_LAMA_KECIL += $bruto;
        }else if($resep==='4' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='kecil'){
          $LOT_T_OBAT_4_BARU_KECIL += 1;
          $QTY_T_OBAT_4_BARU_KECIL += $bruto;
        }

        if($resep==='4' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='besar'){
          $LOT_T_OBAT_4_SETTING_BESAR += 1;
          $QTY_T_OBAT_4_SETTING_BESAR += $bruto;
        }else if($resep==='4' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='besar'){
          $LOT_T_OBAT_4_LAMA_BESAR += 1;
          $QTY_T_OBAT_4_LAMA_BESAR += $bruto;
        }else if($resep==='4' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='besar'){
          $LOT_T_OBAT_4_BARU_BESAR += 1;
          $QTY_T_OBAT_4_BARU_BESAR += $bruto;
        }

        if($resep==='5' && $proses === 'Celup Greige' && $jresep === 'Setting' ){
          $LOT_T_OBAT_5_SETTING += 1;
          $QTY_T_OBAT_5_SETTING += $bruto;
        }else if($resep==='5' && $proses === 'Celup Greige' && $jresep === 'Lama' ){
          $LOT_T_OBAT_5_LAMA += 1;
          $QTY_T_OBAT_5_LAMA += $bruto;
        }else if($resep==='5' && $proses === 'Celup Greige' && $jresep === 'Baru' ){
          $LOT_T_OBAT_5_BARU += 1;
          $QTY_T_OBAT_5_BARU += $bruto;
        }

        if($resep==='5' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='kecil'){
          $LOT_T_OBAT_5_SETTING_KECIL += 1;
          $QTY_T_OBAT_5_SETTING_KECIL += $bruto;
        }else if($resep==='5' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='kecil'){
          $LOT_T_OBAT_5_LAMA_KECIL += 1;
          $QTY_T_OBAT_5_LAMA_KECIL += $bruto;
        }else if($resep==='5' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='kecil'){
          $LOT_T_OBAT_5_BARU_KECIL += 1;
          $QTY_T_OBAT_5_BARU_KECIL += $bruto;
        }

        if($resep==='5' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='besar'){
          $LOT_T_OBAT_5_SETTING_BESAR += 1;
          $QTY_T_OBAT_5_SETTING_BESAR += $bruto;
        }else if($resep==='5' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='besar'){
          $LOT_T_OBAT_5_LAMA_BESAR += 1;
          $QTY_T_OBAT_5_LAMA_BESAR += $bruto;
        }else if($resep==='5' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='besar'){
          $LOT_T_OBAT_5_BARU_BESAR += 1;
          $QTY_T_OBAT_5_BARU_BESAR += $bruto;
        }

        if($resep==='6' && $proses === 'Celup Greige' && $jresep === 'Setting' ){
          $LOT_T_OBAT_6_SETTING += 1;
          $QTY_T_OBAT_6_SETTING += $bruto;
        }else if($resep==='6' && $proses === 'Celup Greige' && $jresep === 'Lama' ){
          $LOT_T_OBAT_6_LAMA += 1;
          $QTY_T_OBAT_6_LAMA += $bruto;
        }else if($resep==='6' && $proses === 'Celup Greige' && $jresep === 'Baru' ){
          $LOT_T_OBAT_6_BARU += 1;
          $QTY_T_OBAT_6_BARU += $bruto;
        }

        if($resep==='6' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='kecil'){
          $LOT_T_OBAT_6_SETTING_KECIL += 1;
          $QTY_T_OBAT_6_SETTING_KECIL += $bruto;
        }else if($resep==='6' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='kecil'){
          $LOT_T_OBAT_6_LAMA_KECIL += 1;
          $QTY_T_OBAT_6_LAMA_KECIL += $bruto;
        }else if($resep==='6' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='kecil'){
          $LOT_T_OBAT_6_BARU_KECIL += 1;
          $QTY_T_OBAT_6_BARU_KECIL += $bruto;
        }

        if($resep==='6' && $proses === 'Celup Greige' && $jresep === 'Setting' && $mesin==='besar'){
          $LOT_T_OBAT_6_SETTING_BESAR += 1;
          $QTY_T_OBAT_6_SETTING_BESAR += $bruto;
        }else if($resep==='6' && $proses === 'Celup Greige' && $jresep === 'Lama' && $mesin==='besar'){
          $LOT_T_OBAT_6_LAMA_BESAR += 1;
          $QTY_T_OBAT_6_LAMA_BESAR += $bruto;
        }else if($resep==='6' && $proses === 'Celup Greige' && $jresep === 'Baru' && $mesin==='besar'){
          $LOT_T_OBAT_6_BARU_BESAR += 1;
          $QTY_T_OBAT_6_BARU_BESAR += $bruto;
        }
      }
    }
// End analisa resep

// Function Performance
    function performance_lab(&$data, $user, $kategori, $analisa, $jresep) {
      if ($kategori !== 'Celup Greige'  && $jresep !== 'Baru') {
          return;
      }

      $userKey = str_replace([' ', '.'], ['_', ''], strtoupper($user));

      $analisaKey = '';
      if ($analisa === 'Oke') {
          $analisaKey = 'OK_LAB';
      } else if ($analisa === 'Tidak Oke') {
          $analisaKey = 'TOK_LAB';
      } else {
          $analisaKey = 'LAINNYA_LAB';
      }

      if (!isset($data[$userKey])) {
          $data[$userKey] = [];
      }
      if (!isset($data[$userKey][$analisaKey])) {
          $data[$userKey][$analisaKey] = 0;
      }

      $data[$userKey][$analisaKey]++;
      $data[$userKey]['TOTAL']++;
  }

    function performance_dye(&$data, $user, $kategori, $analisa, $jresep) {
      if ($kategori !== 'Celup Greige' && $jresep !== 'Baru') {
          return;
      }

      $userKey = str_replace([' ', '.'], ['_', ''], strtoupper($user));

      $analisaKey = '';
      if ($analisa === 'Oke') {
          $analisaKey = 'OK_DYE';
      } else if ($analisa === 'Tidak Oke') {
          $analisaKey = 'TOK_DYE';
      } else {
          $analisaKey = 'LAINNYA_DYE';
      }

      if (!isset($data[$userKey])) {
          $data[$userKey] = [];
      }
      if (!isset($data[$userKey][$analisaKey])) {
          $data[$userKey][$analisaKey] = 0;
      }

      $data[$userKey][$analisaKey]++;
      $data[$userKey]['TOTAL']++;
  }

    function performance_setting_sebelum(&$data, $user, $kategori, $analisa, $jresep) {
      if ($kategori !== 'Celup Greige' && $jresep !== 'Setting') {
          return;
      }

      $userKey = str_replace([' ', '.'], ['_', ''], strtoupper($user));

      $analisaKey = '';
      if ($analisa === 'Oke') {
          $analisaKey = 'OK_SEBELUM';
      } else if ($analisa === 'Tidak Oke') {
          $analisaKey = 'TOK_SEBELUM';
      } else {
          $analisaKey = 'LAINNYA_SEBELUM';
      }

      if (!isset($data[$userKey])) {
          $data[$userKey] = [];
      }
      if (!isset($data[$userKey][$analisaKey])) {
          $data[$userKey][$analisaKey] = 0;
      }

      $data[$userKey][$analisaKey]++;
      $data[$userKey]['TOTAL']++;
  }

    function performance_setting_sesudah(&$data, $user, $kategori, $analisa, $jresep) {
      if ($kategori !== 'Celup Greige' && $jresep !== 'Setting') {
          return;
      }

      $userKey = str_replace([' ', '.'], ['_', ''], strtoupper($user));

      $analisaKey = '';
      if ($analisa === 'Oke') {
          $analisaKey = 'OK_SESUDAH';
      } else if ($analisa === 'Tidak Oke') {
          $analisaKey = 'TOK_SESUDAH';
      } else {
          $analisaKey = 'LAINNYA_SESUDAH';
      }

      if (!isset($data[$userKey])) {
          $data[$userKey] = [];
      }
      if (!isset($data[$userKey][$analisaKey])) {
          $data[$userKey][$analisaKey] = 0;
      }

      $data[$userKey][$analisaKey]++;
      $data[$userKey]['TOTAL']++;
  }
// End performance

// Function Dept Penyebab
  function dept_penyebab($mesin, $kategori, $proses_t, $analisa, $dept_penyebab)
    {
      global $LAB_DYE_CQA_T_OBAT_KECIL , $LAB_DYE_CQA_T_OBAT_BESAR , $LAB_DYE_CQA_T_OBAT_TOTAL ,  $LAB_DYE_CQA_TOK_TOTAL , $LAB_DYE_CQA_TOK_KECIL , $LAB_DYE_CQA_TOK_BESAR , $LAB_T_OBAT_KECIL , $LAB_T_OBAT_BESAR , $LAB_T_OBAT_TOTAL ,  $LAB_TOK_TOTAL , $LAB_TOK_KECIL , $LAB_TOK_BESAR , 
              $DYE_T_OBAT_KECIL , $DYE_T_OBAT_BESAR , $DYE_T_OBAT_TOTAL ,  $DYE_TOK_TOTAL , $DYE_TOK_KECIL , $DYE_TOK_BESAR , $CQA_T_OBAT_KECIL , $CQA_T_OBAT_BESAR , $CQA_T_OBAT_TOTAL ,  $CQA_TOK_TOTAL , $CQA_TOK_KECIL , $CQA_TOK_BESAR , 
              $LAB_DYE_T_OBAT_KECIL , $LAB_DYE_T_OBAT_BESAR , $LAB_DYE_T_OBAT_TOTAL ,  $LAB_DYE_TOK_TOTAL , $LAB_DYE_TOK_KECIL , $LAB_DYE_TOK_BESAR , $LAB_CQA_T_OBAT_KECIL , $LAB_CQA_T_OBAT_BESAR , $LAB_CQA_T_OBAT_TOTAL ,  $LAB_CQA_TOK_TOTAL , $LAB_CQA_TOK_KECIL , $LAB_CQA_TOK_BESAR , $DYE_CQA_T_OBAT_KECIL , $DYE_CQA_T_OBAT_BESAR , 
              $DYE_CQA_T_OBAT_TOTAL ,  $DYE_CQA_TOK_TOTAL , $DYE_CQA_TOK_KECIL , $DYE_CQA_TOK_BESAR, $LAIN_T_OBAT_KECIL , $LAIN_T_OBAT_BESAR , $LAIN_T_OBAT_TOTAL ,  $LAIN_TOK_TOTAL , $LAIN_TOK_KECIL , $LAIN_DYE_CQA_TOK_BESAR ;

      if($dept_penyebab==='LAB/DYE/CQA'){
        if(!empty($proses_t))
        {
          $proses = str_replace("x", "", $proses_t);

          if($kategori === 'Celup Greige' && $proses > '0'){
            $LAB_DYE_CQA_T_OBAT_TOTAL +=1;
          } 

          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0'){
            $LAB_DYE_CQA_T_OBAT_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0'){
            $LAB_DYE_CQA_T_OBAT_BESAR +=1;
          }
        }

        if($kategori === 'Celup Greige' && $analisa === 'Tidak Oke'){
          $LAB_DYE_CQA_TOK_TOTAL +=1;
          }

        if($kategori === 'Celup Greige' && $mesin==='kecil' && $analisa === 'Tidak Oke'){
          $LAB_DYE_CQA_TOK_KECIL +=1;
        }else if($kategori === 'Celup Greige' && $mesin==='besar' && $analisa === 'Tidak Oke'){
          $LAB_DYE_CQA_TOK_BESAR +=1;
        }
      }

      if($dept_penyebab==='DEPT LAIN'){
        if(!empty($proses_t))
        {
          $proses = str_replace("x", "", $proses_t);

          if($kategori === 'Celup Greige' && $proses > '0'){
            $LAIN_T_OBAT_TOTAL +=1;
          } 
          
          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0'){
            $LAIN_T_OBAT_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0'){
            $LAIN_T_OBAT_BESAR +=1;
          }
        }

        if($kategori === 'Celup Greige' && $analisa === 'Tidak Oke'){
          $LAIN_TOK_TOTAL +=1;
        }

        if($kategori === 'Celup Greige' && $mesin==='kecil' && $analisa === 'Tidak Oke'){
          $LAIN_TOK_KECIL +=1;
        }else if($kategori === 'Celup Greige' && $mesin==='besar' && $analisa === 'Tidak Oke'){
          $LAIN_TOK_BESAR +=1;
        }
      }

      if($dept_penyebab==='LAB'){
        if(!empty($proses_t))
        {
          $proses = str_replace("x", "", $proses_t);

          if($kategori === 'Celup Greige' && $proses > '0'){
            $LAB_T_OBAT_TOTAL +=1;
          }

          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0'){
            $LAB_T_OBAT_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0'){
            $LAB_T_OBAT_BESAR +=1;
          }  
        }

        if($kategori === 'Celup Greige' && $analisa === 'Tidak Oke'){
          $LAB_TOK_TOTAL +=1;
        }

        if($kategori === 'Celup Greige' && $mesin==='kecil' && $analisa === 'Tidak Oke'){
          $LAB_TOK_KECIL +=1;
        }else if($kategori === 'Celup Greige' && $mesin==='besar' && $analisa === 'Tidak Oke'){
          $LAB_TOK_BESAR +=1;
        }
      }

      if($dept_penyebab==='DYE'){
        if(!empty($proses_t))
        {
          $proses = str_replace("x", "", $proses_t);

          if($kategori === 'Celup Greige' && $proses > '0'){
            $DYE_T_OBAT_TOTAL +=1;
          } 
          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0'){
            $DYE_T_OBAT_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0'){
            $DYE_T_OBAT_BESAR +=1;
          }
        }
        if($kategori === 'Celup Greige' && $analisa === 'Tidak Oke'){
          $DYE_TOK_TOTAL +=1;
        } 
        if($kategori === 'Celup Greige' && $mesin==='kecil' && $analisa === 'Tidak Oke'){
          $DYE_TOK_KECIL +=1;
        }else if($kategori === 'Celup Greige' && $mesin==='besar' && $analisa === 'Tidak Oke'){
          $DYE_TOK_BESAR +=1;
        }
      }

      if($dept_penyebab==='CQA'){
        if(!empty($proses_t))
        {
          $proses = str_replace("x", "", $proses_t);

          if($kategori === 'Celup Greige' && $proses > '0'){
            $CQA_T_OBAT_TOTAL +=1;
          } 
          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0'){
            $CQA_T_OBAT_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0'){
            $CQA_T_OBAT_BESAR +=1;
          } 
        }
        if($kategori === 'Celup Greige' && $analisa === 'Tidak Oke'){
          $CQA_TOK_TOTAL +=1;
        } 
        if($kategori === 'Celup Greige' && $mesin==='kecil' && $analisa === 'Tidak Oke'){
          $CQA_TOK_KECIL +=1;
        }else if($kategori === 'Celup Greige' && $mesin==='besar' && $analisa === 'Tidak Oke'){
          $CQA_TOK_BESAR +=1;
        }
      }

      if($dept_penyebab==='LAB/DYE'){
        if(!empty($proses_t))
        {
          $proses = str_replace("x", "", $proses_t);

          if($kategori === 'Celup Greige' && $proses > '0'){
            $LAB_DYE_T_OBAT_TOTAL +=1;
          } 
          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0'){
            $LAB_DYE_T_OBAT_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0'){
            $LAB_DYE_T_OBAT_BESAR +=1;
          }     
        }
        if($kategori === 'Celup Greige' && $analisa === 'Tidak Oke'){
          $LAB_DYE_TOK_TOTAL +=1;
        } 
        if($kategori === 'Celup Greige' && $mesin==='kecil' && $analisa === 'Tidak Oke'){
          $LAB_DYE_TOK_KECIL +=1;
        }else if($kategori === 'Celup Greige' && $mesin==='besar' && $analisa === 'Tidak Oke'){
          $LAB_DYE_TOK_BESAR +=1;
        }
      }
      if($dept_penyebab==='DYE/CQA'){
        if(!empty($proses_t))
        {
          $proses = str_replace("x", "", $proses_t);

          if($kategori === 'Celup Greige' && $proses > '0'){
            $DYE_CQA_T_OBAT_TOTAL +=1;
          } 
          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0'){
            $DYE_CQA_T_OBAT_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0'){
            $DYE_CQA_T_OBAT_BESAR +=1;
          }
        }
        if($kategori === 'Celup Greige' && $analisa === 'Tidak Oke'){
          $DYE_CQA_TOK_TOTAL +=1;
        } 
        if($kategori === 'Celup Greige' && $mesin==='kecil' && $analisa === 'Tidak Oke'){
          $DYE_CQA_TOK_KECIL +=1;
        }else if($kategori === 'Celup Greige' && $mesin==='besar' && $analisa === 'Tidak Oke'){
          $DYE_CQA_TOK_BESAR +=1;
        }
      }

      if($dept_penyebab==='LAB/CQA'){
        if(!empty($proses_t))
        {
          $proses = str_replace("x", "", $proses_t);

          if($kategori === 'Celup Greige' && $proses > '0'){
            $LAB_CQA_T_OBAT_TOTAL +=1;
          } 
          if($kategori === 'Celup Greige' && $mesin==='kecil' && $proses > '0'){
            $LAB_CQA_T_OBAT_KECIL +=1;
          }else if($kategori === 'Celup Greige' && $mesin==='besar' && $proses > '0'){
            $LAB_CQA_T_OBAT_BESAR +=1;
          }
        }
        if($kategori === 'Celup Greige' && $analisa === 'Tidak Oke'){
          $LAB_CQA_TOK_TOTAL +=1;
        }
        if($kategori === 'Celup Greige' && $mesin==='kecil' && $analisa === 'Tidak Oke'){
          $LAB_CQA_TOK_KECIL +=1;
        }else if($kategori === 'Celup Greige' && $mesin==='besar' && $analisa === 'Tidak Oke'){
          $LAB_CQA_TOK_BESAR +=1;
        }
      }
    }
// End Dept Penyebab

?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"> Filter Laporan Recipe</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
    <div class="box-body">

      <div class="form-group">
        <div class="col-sm-3">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="awal" type="text" class="form-control pull-right" id="datepicker" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off"/>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-3">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="akhir" type="text" class="form-control pull-right" id="datepicker1" placeholder="Tanggal" value="<?php echo $Akhir;  ?>" autocomplete="off"/>
          </div>
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
		<?php if($_POST['akhir']!="") {  ?>
		<!-- <a href="pages/cetak/cetak_lapharianfin.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>" class="btn btn-warning btn-sm pull-right" target="_blank"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</a> <br> --><?php } ?> 
        <h3 class="box-title">Data Grafik Summary Recipe</h3><br>		  
        <?php if($_POST['akhir']!="") { ?><b>Periode: <?php echo $Awal." to ".$Akhir; ?></b>
		<?php } ?>
      <div class="box-body">
       <?php
  // echo "Awal: $Awal<br>";
  // echo "Akhir: $Akhir<br>";
  $weekRanges = []; 
  $performanceLabData = [];
  $performanceDyeData = [];
  $performanceSettingSebelum = [];
  $performanceSettingSesudah = [];
  $user_lab = [];
  $user_dye = [];
  $user_sblm = [];
  $user_sudah = [];
  $sqlQuery = "SELECT
                  b.id as id_schedule_1,
                  c.id as id_montemp_1,
                  a.id as id_hasil_celup_1,
                  case
                    when m.kapasitas <= '200' then 'kecil'
                    else 'besar'
                  end as ket_kapasitas,
                  b.no_mesin,
                  a2.colorist_lab,
                  a2.colorist_dye,
                  a2.setting_sebelum,
                  a2.setting_sesudah,
                  a2.tindakan_perbaikan,
                  a2.analisa_penyebab,
                  a2.dept_penyebab2,
                  a2.akar_penyebab,
                  a2.ket_hitung,
                  a.penanggungjawabbuyer,
                  a.kd_stop,
                  a.mulai_stop,
                  a.selesai_stop,
                  a.ket,
                  if(ISNULL(TIMEDIFF(c.tgl_mulai, c.tgl_stop)),
                  a.lama_proses,
                  CONCAT(LPAD(FLOOR((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))/ 60), 2, 0), ':', LPAD(((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))%60), 2, 0))) as lama_proses,
                  a.status as sts,
                  TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai, c.tgl_stop)), a.lama_proses, CONCAT(LPAD(FLOOR((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))/ 60), 2, 0), ':', LPAD(((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))%60), 2, 0))), '%H') as jam,
                  TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai, c.tgl_stop)), a.lama_proses, CONCAT(LPAD(FLOOR((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))/ 60), 2, 0), ':', LPAD(((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))%60), 2, 0))), '%i') as menit,
                  a.point,
                  DATE_FORMAT(a.mulai_stop, '%Y-%m-%d') as t_mulai,
                  DATE_FORMAT(a.selesai_stop, '%Y-%m-%d') as t_selesai,
                  TIME_FORMAT(a.mulai_stop, '%H:%i') as j_mulai,
                  TIME_FORMAT(a.selesai_stop, '%H:%i') as j_selesai,
                  TIMESTAMPDIFF(minute,
                  a.mulai_stop,
                  a.selesai_stop) as lama_stop_menit,
                  a.acc_keluar,
                  if(a.proses = ''
                  or ISNULL(a.proses),
                  b.proses,
                  a.proses) as proses,
                  b.buyer,
                  b.langganan,
                  b.no_order,
                  b.jenis_kain,
                  b.no_mesin,
                  b.warna,
                  b.lot,
                  b.energi,
                  b.dyestuff,
                  b.ket_status,
                  b.kapasitas,
                  b.loading,
                  b.resep,
                  b.kategori_warna,
                  b.target,
                  c.l_r,
                  c.rol,
                  c.bruto,
                  c.pakai_air,
                  c.no_program,
                  c.pjng_kain,
                  c.cycle_time,
                  c.rpm,
                  c.tekanan,
                  c.nozzle,
                  c.plaiter,
                  c.blower,
                  DATE_FORMAT(c.tgl_buat, '%Y-%m-%d') as tgl_in,
                  DATE_FORMAT(a.tgl_buat, '%Y-%m-%d') as tgl_out,
                  DATE_FORMAT(c.tgl_buat, '%H:%i') as jam_in,
                  DATE_FORMAT(a.tgl_buat, '%H:%i') as jam_out,
                  if(ISNULL(a.g_shift),
                  c.g_shift,
                  a.g_shift) as shft,
                  a.operator_keluar,
                  a.k_resep,
                  a.status,
                  a.proses_point,
                  a.analisa,
                  b.nokk,
                  b.no_warna,
                  b.lebar,
                  b.gramasi,
                  c.carry_over,
                  b.no_hanger,
                  b.no_item,
                  b.po,
                  b.tgl_delivery,
                  b.kk_kestabilan,
                  b.kk_normal,
                  c.air_awal,
                  a.air_akhir,
                  c.nokk_legacy,
                  c.loterp,
                  c.nodemand,
                  a.tambah_obat,
                  a.tambah_obat1,
                  a.tambah_obat2,
                  a.tambah_obat3,
                  a.tambah_obat4,
                  a.tambah_obat5,
                  a.tambah_obat6,
                  c.leader,
                  b.suffix,
                  b.suffix2,
                  c.l_r_2,
                  c.lebar_fin,
                  c.grm_fin,
                  c.lebar_a,
                  c.gramasi_a,
                  c.operator,
                  a.status_resep,
                  a.analisa_resep,
                  c.tgl_update
                from
                  tbl_schedule b
                left join tbl_montemp c on
                  c.id_schedule = b.id
                left join tbl_hasilcelup a on
                  a.id_montemp = c.id
                left join tbl_hasilcelup2 a2 on
                      a2.id_hasilcelup = a.id
                left join tbl_mesin m on m.no_mesin = b.no_mesin
                where
                  DATE_FORMAT(c.tgl_update, '%Y-%m-%d') BETWEEN '$Awal' AND '$Akhir'
                  and if(a.proses = ''
                  or ISNULL(a.proses),
                  b.proses,
                  a.proses) in ('Cuci Misty', 'Celup Greige')
                  and (a2.ket_hitung is null or a2.ket_hitung = 0)
                  and b.langganan <> ''
                  and c.bruto > 0 ";
  // echo "<pre>$sqlQuery</pre>";
  $stmt_dye = mysqli_query($con, $sqlQuery);
  // $d_dye = mysqli_fetch_assoc($stmt_dye);
  while($d_dye = mysqli_fetch_assoc($stmt_dye)){
    // print_r($d_dye);
    $usr_lab[]= $d_dye['colorist_lab'];
    $usr_dye[]= $d_dye['colorist_dye'];
    $usr_sblm[]= $d_dye['setting_sebelum'];
    $usr_sudah[]= $d_dye['setting_sesudah'];
    analisaResep($d_dye['ket_kapasitas'], $d_dye['proses'], $d_dye['status_resep']);
    jenisResep($d_dye['ket_kapasitas'], $d_dye['proses'], $d_dye['resep'], $d_dye['bruto'], $d_dye['k_resep'] );
    statusResep($d_dye['ket_kapasitas'], $d_dye['proses'], $d_dye['k_resep'], $d_dye['status_resep'], $d_dye['akar_penyebab']);
    performance_lab($performanceLabData, $d_dye['colorist_lab'], $d_dye['proses'], $d_dye['status_resep'], $d_dye['resep']);
    performance_dye($performanceDyeData, $d_dye['colorist_dye'], $d_dye['proses'], $d_dye['status_resep'], $d_dye['resep']);
    performance_setting_sebelum($performanceSettingSebelum, $d_dye['setting_sebelum'], $d_dye['proses'], $d_dye['status_resep'], $d_dye['resep']);
    performance_setting_sesudah($performanceSettingSesudah, $d_dye['setting_sesudah'], $d_dye['proses'], $d_dye['status_resep'], $d_dye['resep']);
    dept_penyebab($d_dye['ket_kapasitas'], $d_dye['proses'], $d_dye['k_resep'], $d_dye['status_resep'], $d_dye['dept_penyebab2']);
  }

  if (is_array($usr_lab)) {
      $user_lab = array_unique($usr_lab);
  } else {
      $user_lab = []; 
  }
  $user_lab = array_filter($user_lab);
  sort($user_lab);
  if (is_array($usr_dye)) {
      $user_dye = array_unique($usr_dye);
  } else {
      $user_dye = [];
  }
  $user_dye = array_filter($user_dye);
  sort($user_dye);
  if (is_array($usr_sblm)) {
      $user_sblm = array_unique($usr_sblm);
  } else {
      $user_sblm = [];
  }
  $user_sblm = array_filter($user_sblm);
  sort($user_sblm);
  if (is_array($usr_sudah)) {
      $user_sudah = array_unique($usr_sudah);
  } else {
      $user_sudah = [];
  }
  $user_sudah = array_filter($user_sudah);
  sort($user_sudah);

  // Step 1: Ambil nama bulan dari key hasil query (contoh: roll_w1_jun)
  // preg_match('/kg_w\d+_(\w+)/', array_keys($d_dye)[0], $matches);
  // $monthKey = $matches[1] ?? 'jun';
  // print_r($search);
  // Step 2: Mapping nama bulan
  $monthMap = [
      'jan' => 'Januari',
      'feb' => 'Februari',
      'mar' => 'Maret',
      'apr' => 'April',
      'may' => 'Mei',
      'jun' => 'Juni',
      'jul' => 'Juli',
      'aug' => 'Agustus',
      'sep' => 'September',
      'oct' => 'Oktober',
      'nov' => 'November',
      'dec' => 'Desember',
  ];

  $monthName = $monthMap[$monthKey] ?? ucfirst($monthKey);

  // Step 3: Hitung jumlah minggu dari key
  // $weeks = [];
  // foreach (array_keys($d_dye) as $key) {
  //     if (preg_match('/kg_w(\d+)_/', $key, $m)) {
  //         $weeks[] = (int)$m[1];
  //     }
  // }
  // $weeks = array_unique($weeks);
  // sort($weeks);
// Calculation
  $P_OK         = ($ANALISA > 0) ? ROUND(($OK/$ANALISA)*100, 2) : 0;
  $P_TOK        = ($ANALISA > 0) ? ROUND(($TOK/$ANALISA)*100, 2) : 0;
  $P_ANALISA    = ($T_ANALISA > 0) ? ROUND(($ANALISA/$T_ANALISA)*100, 2) : 0;

  $P_OK_KECIL   = ($ANALISA_KECIL > 0) ? ROUND(($OK_KECIL/$ANALISA_KECIL)*100, 2) : 0;
  $P_TOK_KECIL  = ($ANALISA_KECIL > 0) ? ROUND(($TOK_KECIL/$ANALISA_KECIL)*100, 2) : 0;
  $P_ANALISA_KECIL = ($T_ANALISA_KECIL > 0) ? ROUND(($ANALISA_KECIL/$T_ANALISA_KECIL)*100, 2) : 0;

  $P_OK_BESAR   = ($ANALISA_BESAR > 0) ? ROUND(($OK_BESAR/$ANALISA_BESAR)*100, 2) : 0;
  $P_TOK_BESAR  = ($ANALISA_BESAR > 0) ? ROUND(($TOK_BESAR/$ANALISA_BESAR)*100, 2) : 0;
  $P_ANALISA_BESAR = ($T_ANALISA_BESAR > 0) ? ROUND(($ANALISA_BESAR/$T_ANALISA_BESAR)*100, 2) : 0;

 $TOT_R        = $R_LAMA + $R_BARU + $R_SETTING;
  $P_R_LAMA     = ($TOT_R > 0) ? ROUND(($R_LAMA/$TOT_R)*100, 2) : 0;
  $P_R_BARU     = ($TOT_R > 0) ? ROUND(($R_BARU/$TOT_R)*100, 2) : 0;
  $P_R_SETTING  = ($TOT_R > 0) ? ROUND(($R_SETTING/$TOT_R)*100, 2) : 0;

  $TOT_R_KECIL  = $R_LAMA_KECIL + $R_BARU_KECIL + $R_SETTING_KECIL;
  $P_R_LAMA_KECIL    = ($TOT_R_KECIL > 0) ? ROUND(($R_LAMA_KECIL/$TOT_R_KECIL)*100, 2) : 0;
  $P_R_BARU_KECIL    = ($TOT_R_KECIL > 0) ? ROUND(($R_BARU_KECIL/$TOT_R_KECIL)*100, 2) : 0;
  $P_R_SETTING_KECIL = ($TOT_R_KECIL > 0) ? ROUND(($R_SETTING_KECIL/$TOT_R_KECIL)*100, 2) : 0;

  $TOT_R_BESAR  = $R_LAMA_BESAR + $R_BARU_BESAR + $R_SETTING_BESAR;
  $P_R_LAMA_BESAR    = ($TOT_R_BESAR > 0) ? ROUND(($R_LAMA_BESAR/$TOT_R_BESAR)*100, 2) : 0;
  $P_R_BARU_BESAR    = ($TOT_R_BESAR > 0) ? ROUND(($R_BARU_BESAR/$TOT_R_BESAR)*100, 2) : 0;
  $P_R_SETTING_BESAR = ($TOT_R_BESAR > 0) ? ROUND(($R_SETTING_BESAR/$TOT_R_BESAR)*100, 2) : 0;

  $TOT_STS_RESEP = $STS_RESEP_OK + $STS_RESEP_TOK + $STS_RESEP_PROGRESS;
  $P_STS_RESEP_OK       = ($TOT_STS_RESEP > 0) ? ROUND(($STS_RESEP_OK/$TOT_STS_RESEP)*100, 2) : 0;
  $P_STS_RESEP_TOK      = ($TOT_STS_RESEP > 0) ? ROUND(($STS_RESEP_TOK/$TOT_STS_RESEP)*100, 2) : 0;
  $P_STS_RESEP_PROGRESS = ($TOT_STS_RESEP > 0) ? ROUND(($STS_RESEP_PROGRESS/$TOT_STS_RESEP)*100, 2) : 0;

  $TOT_STS_RESEP_TOBAT = $STS_RESEP_TOBAT_OK + $STS_RESEP_TOBAT_TOK + $STS_RESEP_TOBAT_PROGRESS;
  $P_STS_RESEP_TOBAT_OK       = ($TOT_STS_RESEP_TOBAT > 0) ? ROUND(($STS_RESEP_TOBAT_OK/$TOT_STS_RESEP_TOBAT)*100, 2) : 0;
  $P_STS_RESEP_TOBAT_TOK      = ($TOT_STS_RESEP_TOBAT > 0) ? ROUND(($STS_RESEP_TOBAT_TOK/$TOT_STS_RESEP_TOBAT)*100, 2) : 0;
  $P_STS_RESEP_TOBAT_PROGRESS = ($TOT_STS_RESEP_TOBAT > 0) ? ROUND(($STS_RESEP_TOBAT_PROGRESS/$TOT_STS_RESEP_TOBAT)*100, 2) : 0;

  $TOT_STS_RESEP_KECIL = $STS_RESEP_OK_KECIL + $STS_RESEP_TOK_KECIL + $STS_RESEP_PROGRESS_KECIL;
  $P_STS_RESEP_OK_KECIL       = ($TOT_STS_RESEP_KECIL > 0) ? ROUND(($STS_RESEP_OK_KECIL/$TOT_STS_RESEP_KECIL)*100, 2) : 0;
  $P_STS_RESEP_TOK_KECIL      = ($TOT_STS_RESEP_KECIL > 0) ? ROUND(($STS_RESEP_TOK_KECIL/$TOT_STS_RESEP_KECIL)*100, 2) : 0;
  $P_STS_RESEP_PROGRESS_KECIL = ($TOT_STS_RESEP_KECIL > 0) ? ROUND(($STS_RESEP_PROGRESS_KECIL/$TOT_STS_RESEP_KECIL)*100, 2) : 0;

  $TOT_STS_RESEP_TOBAT_KECIL = $STS_RESEP_TOBAT_OK_KECIL + $STS_RESEP_TOBAT_TOK_KECIL + $STS_RESEP_TOBAT_PROGRESS_KECIL;
  $P_STS_RESEP_TOBAT_OK_KECIL       = ($TOT_STS_RESEP_TOBAT_KECIL > 0) ? ROUND(($STS_RESEP_TOBAT_OK_KECIL/$TOT_STS_RESEP_TOBAT_KECIL)*100, 2) : 0;
  $P_STS_RESEP_TOBAT_TOK_KECIL      = ($TOT_STS_RESEP_TOBAT_KECIL > 0) ? ROUND(($STS_RESEP_TOBAT_TOK_KECIL/$TOT_STS_RESEP_TOBAT_KECIL)*100, 2) : 0;
  $P_STS_RESEP_TOBAT_PROGRESS_KECIL = ($TOT_STS_RESEP_TOBAT_KECIL > 0) ? ROUND(($STS_RESEP_TOBAT_PROGRESS_KECIL/$TOT_STS_RESEP_TOBAT_KECIL)*100, 2) : 0;

  $TOT_STS_RESEP_BESAR = $STS_RESEP_OK_BESAR + $STS_RESEP_TOK_BESAR + $STS_RESEP_PROGRESS_BESAR;
  $P_STS_RESEP_OK_BESAR       = ($TOT_STS_RESEP_BESAR > 0) ? ROUND(($STS_RESEP_OK_BESAR/$TOT_STS_RESEP_BESAR)*100, 2) : 0;
  $P_STS_RESEP_TOK_BESAR      = ($TOT_STS_RESEP_BESAR > 0) ? ROUND(($STS_RESEP_TOK_BESAR/$TOT_STS_RESEP_BESAR)*100, 2) : 0;
  $P_STS_RESEP_PROGRESS_BESAR = ($TOT_STS_RESEP_BESAR > 0) ? ROUND(($STS_RESEP_PROGRESS_BESAR/$TOT_STS_RESEP_BESAR)*100, 2) : 0;

  $TOT_STS_RESEP_TOBAT_BESAR = $STS_RESEP_TOBAT_OK_BESAR + $STS_RESEP_TOBAT_TOK_BESAR + $STS_RESEP_TOBAT_PROGRESS_BESAR;
  $P_STS_RESEP_TOBAT_OK_BESAR       = ($TOT_STS_RESEP_TOBAT_BESAR > 0) ? ROUND(($STS_RESEP_TOBAT_OK_BESAR/$TOT_STS_RESEP_TOBAT_BESAR)*100, 2) : 0;
  $P_STS_RESEP_TOBAT_TOK_BESAR      = ($TOT_STS_RESEP_TOBAT_BESAR > 0) ? ROUND(($STS_RESEP_TOBAT_TOK_BESAR/$TOT_STS_RESEP_TOBAT_BESAR)*100, 2) : 0;
  $P_STS_RESEP_TOBAT_PROGRESS_BESAR = ($TOT_STS_RESEP_TOBAT_BESAR > 0) ? ROUND(($STS_RESEP_TOBAT_PROGRESS_BESAR/$TOT_STS_RESEP_TOBAT_BESAR)*100, 2) : 0;

  $TOT_ROOT                   = $ROOT_MAN_KECIL + $ROOT_MAN_BESAR + $ROOT_MACHINE_KECIL + $ROOT_MACHINE_BESAR + $ROOT_MATERIAL_BESAR + $ROOT_MATERIAL_KECIL + $ROOT_MEASUREMENT_BESAR + $ROOT_MEASUREMENT_KECIL + $ROOT_METHODE_BESAR + $ROOT_METHODE_KECIL;
  $P_TOT_MAN                  = ($TOT_ROOT > 0) ? ROUND((($ROOT_MAN_KECIL + $ROOT_MAN_BESAR)/$TOT_ROOT)*100, 2) : 0;
  $P_TOT_MACHINE              = ($TOT_ROOT > 0) ? ROUND((($ROOT_MACHINE_KECIL + $ROOT_MACHINE_BESAR)/$TOT_ROOT)*100, 2) : 0;
  $P_TOT_MATERIAL             = ($TOT_ROOT > 0) ? ROUND((($ROOT_MATERIAL_KECIL + $ROOT_MATERIAL_BESAR)/$TOT_ROOT)*100, 2) : 0;
  $P_TOT_ENVIRONMENT             = ($TOT_ROOT > 0) ? ROUND((($ROOT_ENVIRONMENT_KECIL + $ROOT_ENVIRONMENT_BESAR)/$TOT_ROOT)*100, 2) : 0;
  $P_TOT_METHODE              = ($TOT_ROOT > 0) ? ROUND((($ROOT_METHODE_KECIL + $ROOT_METHODE_BESAR)/$TOT_ROOT)*100, 2) : 0;
  $P_TOT_MEASUREMENT          = ($TOT_ROOT > 0) ? ROUND((($ROOT_MEASUREMENT_KECIL + $ROOT_MEASUREMENT_BESAR)/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_MAN_KECIL           = ($TOT_ROOT > 0) ? ROUND(($ROOT_MAN_KECIL/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_MACHINE_KECIL       = ($TOT_ROOT > 0) ? ROUND(($ROOT_MACHINE_KECIL/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_MATERIAL_KECIL      = ($TOT_ROOT > 0) ? ROUND(($ROOT_MATERIAL_KECIL/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_METHODE_KECIL       = ($TOT_ROOT > 0) ? ROUND(($ROOT_METHODE_KECIL/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_MEASUREMENT_KECIL   = ($TOT_ROOT > 0) ? ROUND(($ROOT_MEASUREMENT_KECIL/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_ENVIRONMENT_KECIL   = ($TOT_ROOT > 0) ? ROUND(($ROOT_ENVIRONMENT_KECIL/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_MAN_BESAR           = ($TOT_ROOT > 0) ? ROUND(($ROOT_MAN_BESAR/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_MACHINE_BESAR       = ($TOT_ROOT > 0) ? ROUND(($ROOT_MACHINE_BESAR/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_MATERIAL_BESAR      = ($TOT_ROOT > 0) ? ROUND(($ROOT_MATERIAL_BESAR/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_METHODE_BESAR       = ($TOT_ROOT > 0) ? ROUND(($ROOT_METHODE_BESAR/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_MEASUREMENT_BESAR   = ($TOT_ROOT > 0) ? ROUND(($ROOT_MEASUREMENT_BESAR/$TOT_ROOT)*100, 2) : 0;
  $P_ROOT_ENVIRONMENT_BESAR   = ($TOT_ROOT > 0) ? ROUND(($ROOT_ENVIRONMENT_BESAR/$TOT_ROOT)*100, 2) : 0;

  $LOT_T_OBAT_LAMA = $LOT_T_OBAT_6_LAMA + $LOT_T_OBAT_5_LAMA + $LOT_T_OBAT_4_LAMA + $LOT_T_OBAT_3_LAMA + $LOT_T_OBAT_2_LAMA + $LOT_T_OBAT_1_LAMA + $LOT_T_OBAT_0_LAMA;
  $QTY_T_OBAT_LAMA = $QTY_T_OBAT_6_LAMA + $QTY_T_OBAT_5_LAMA + $QTY_T_OBAT_4_LAMA + $QTY_T_OBAT_3_LAMA + $QTY_T_OBAT_2_LAMA + $QTY_T_OBAT_1_LAMA + $QTY_T_OBAT_0_LAMA;
  $LOT_T_OBAT_BARU = $LOT_T_OBAT_6_BARU + $LOT_T_OBAT_5_BARU + $LOT_T_OBAT_4_BARU + $LOT_T_OBAT_3_BARU + $LOT_T_OBAT_2_BARU + $LOT_T_OBAT_1_BARU + $LOT_T_OBAT_0_BARU;
  $QTY_T_OBAT_BARU = $QTY_T_OBAT_6_BARU + $QTY_T_OBAT_5_BARU + $QTY_T_OBAT_4_BARU + $QTY_T_OBAT_3_BARU + $QTY_T_OBAT_2_BARU + $QTY_T_OBAT_1_BARU + $QTY_T_OBAT_0_BARU;
  $LOT_T_OBAT_SETTING = $LOT_T_OBAT_6_SETTING + $LOT_T_OBAT_5_SETTING + $LOT_T_OBAT_4_SETTING + $LOT_T_OBAT_3_SETTING + $LOT_T_OBAT_2_SETTING + $LOT_T_OBAT_1_SETTING + $LOT_T_OBAT_0_SETTING;
  $QTY_T_OBAT_SETTING = $QTY_T_OBAT_6_SETTING + $QTY_T_OBAT_5_SETTING + $QTY_T_OBAT_4_SETTING + $QTY_T_OBAT_3_SETTING + $QTY_T_OBAT_2_SETTING + $QTY_T_OBAT_1_SETTING + $QTY_T_OBAT_0_SETTING;

  $LOT_T_OBAT_LAMA_KECIL = $LOT_T_OBAT_6_LAMA_KECIL + $LOT_T_OBAT_5_LAMA_KECIL + $LOT_T_OBAT_4_LAMA_KECIL + $LOT_T_OBAT_3_LAMA_KECIL + $LOT_T_OBAT_2_LAMA_KECIL + $LOT_T_OBAT_1_LAMA_KECIL + $LOT_T_OBAT_0_LAMA_KECIL;
  $QTY_T_OBAT_LAMA_KECIL = $QTY_T_OBAT_6_LAMA_KECIL + $QTY_T_OBAT_5_LAMA_KECIL + $QTY_T_OBAT_4_LAMA_KECIL + $QTY_T_OBAT_3_LAMA_KECIL + $QTY_T_OBAT_2_LAMA_KECIL + $QTY_T_OBAT_1_LAMA_KECIL + $QTY_T_OBAT_0_LAMA_KECIL;
  $LOT_T_OBAT_BARU_KECIL = $LOT_T_OBAT_6_BARU_KECIL + $LOT_T_OBAT_5_BARU_KECIL + $LOT_T_OBAT_4_BARU_KECIL + $LOT_T_OBAT_3_BARU_KECIL + $LOT_T_OBAT_2_BARU_KECIL + $LOT_T_OBAT_1_BARU_KECIL + $LOT_T_OBAT_0_BARU_KECIL;
  $QTY_T_OBAT_BARU_KECIL = $QTY_T_OBAT_6_BARU_KECIL + $QTY_T_OBAT_5_BARU_KECIL + $QTY_T_OBAT_4_BARU_KECIL + $QTY_T_OBAT_3_BARU_KECIL + $QTY_T_OBAT_2_BARU_KECIL + $QTY_T_OBAT_1_BARU_KECIL + $QTY_T_OBAT_0_BARU_KECIL;
  $LOT_T_OBAT_SETTING_KECIL = $LOT_T_OBAT_6_SETTING_KECIL + $LOT_T_OBAT_5_SETTING_KECIL + $LOT_T_OBAT_4_SETTING_KECIL + $LOT_T_OBAT_3_SETTING_KECIL + $LOT_T_OBAT_2_SETTING_KECIL + $LOT_T_OBAT_1_SETTING_KECIL + $LOT_T_OBAT_0_SETTING_KECIL;
  $QTY_T_OBAT_SETTING_KECIL = $QTY_T_OBAT_6_SETTING_KECIL + $QTY_T_OBAT_5_SETTING_KECIL + $QTY_T_OBAT_4_SETTING_KECIL + $QTY_T_OBAT_3_SETTING_KECIL + $QTY_T_OBAT_2_SETTING_KECIL + $QTY_T_OBAT_1_SETTING_KECIL + $QTY_T_OBAT_0_SETTING_KECIL;

  $P_LOT_T_OBAT_LAMA_KECIL   = ($LOT_T_OBAT_LAMA_KECIL > 0) ? ROUND(($LOT_T_OBAT_0_LAMA_KECIL/$LOT_T_OBAT_LAMA_KECIL)*100, 2) : 0;
  $P_QTY_T_OBAT_LAMA_KECIL   = ($QTY_T_OBAT_LAMA_KECIL > 0) ? ROUND(($QTY_T_OBAT_0_LAMA_KECIL/$QTY_T_OBAT_LAMA_KECIL)*100, 2) : 0;
  $P_LOT_T_OBAT_BARU_KECIL   = ($LOT_T_OBAT_BARU_KECIL > 0) ? ROUND(($LOT_T_OBAT_0_BARU_KECIL/$LOT_T_OBAT_BARU_KECIL)*100, 2) : 0;
  $P_QTY_T_OBAT_BARU_KECIL   = ($QTY_T_OBAT_BARU_KECIL > 0) ? ROUND(($QTY_T_OBAT_0_BARU_KECIL/$QTY_T_OBAT_BARU_KECIL)*100, 2) : 0;
  $P_LOT_T_OBAT_SETTING_KECIL   = ($LOT_T_OBAT_SETTING_KECIL > 0) ? ROUND(($LOT_T_OBAT_0_SETTING_KECIL/$LOT_T_OBAT_SETTING_KECIL)*100, 2) : 0;
  $P_QTY_T_OBAT_SETTING_KECIL   = ($QTY_T_OBAT_SETTING_KECIL > 0) ? ROUND(($QTY_T_OBAT_0_SETTING_KECIL/$QTY_T_OBAT_SETTING_KECIL)*100, 2) : 0;
  
  $LOT_T_OBAT_LAMA_BESAR = $LOT_T_OBAT_6_LAMA_BESAR + $LOT_T_OBAT_5_LAMA_BESAR + $LOT_T_OBAT_4_LAMA_BESAR + $LOT_T_OBAT_3_LAMA_BESAR + $LOT_T_OBAT_2_LAMA_BESAR + $LOT_T_OBAT_1_LAMA_BESAR + $LOT_T_OBAT_0_LAMA_BESAR;
  $QTY_T_OBAT_LAMA_BESAR = $QTY_T_OBAT_6_LAMA_BESAR + $QTY_T_OBAT_5_LAMA_BESAR + $QTY_T_OBAT_4_LAMA_BESAR + $QTY_T_OBAT_3_LAMA_BESAR + $QTY_T_OBAT_2_LAMA_BESAR + $QTY_T_OBAT_1_LAMA_BESAR + $QTY_T_OBAT_0_LAMA_BESAR;
  $LOT_T_OBAT_BARU_BESAR = $LOT_T_OBAT_6_BARU_BESAR + $LOT_T_OBAT_5_BARU_BESAR + $LOT_T_OBAT_4_BARU_BESAR + $LOT_T_OBAT_3_BARU_BESAR + $LOT_T_OBAT_2_BARU_BESAR + $LOT_T_OBAT_1_BARU_BESAR + $LOT_T_OBAT_0_BARU_BESAR;
  $QTY_T_OBAT_BARU_BESAR = $QTY_T_OBAT_6_BARU_BESAR + $QTY_T_OBAT_5_BARU_BESAR + $QTY_T_OBAT_4_BARU_BESAR + $QTY_T_OBAT_3_BARU_BESAR + $QTY_T_OBAT_2_BARU_BESAR + $QTY_T_OBAT_1_BARU_BESAR + $QTY_T_OBAT_0_BARU_BESAR;
  $LOT_T_OBAT_SETTING_BESAR = $LOT_T_OBAT_6_SETTING_BESAR + $LOT_T_OBAT_5_SETTING_BESAR + $LOT_T_OBAT_4_SETTING_BESAR + $LOT_T_OBAT_3_SETTING_BESAR + $LOT_T_OBAT_2_SETTING_BESAR + $LOT_T_OBAT_1_SETTING_BESAR + $LOT_T_OBAT_0_SETTING_BESAR;
  $QTY_T_OBAT_SETTING_BESAR = $QTY_T_OBAT_6_SETTING_BESAR + $QTY_T_OBAT_5_SETTING_BESAR + $QTY_T_OBAT_4_SETTING_BESAR + $QTY_T_OBAT_3_SETTING_BESAR + $QTY_T_OBAT_2_SETTING_BESAR + $QTY_T_OBAT_1_SETTING_BESAR + $QTY_T_OBAT_0_SETTING_BESAR;

  $P_LOT_T_OBAT_LAMA_BESAR   = ($LOT_T_OBAT_LAMA_BESAR > 0) ? ROUND(($LOT_T_OBAT_0_LAMA_BESAR/$LOT_T_OBAT_LAMA_BESAR)*100, 2) : 0;
  $P_QTY_T_OBAT_LAMA_BESAR   = ($QTY_T_OBAT_LAMA_BESAR > 0) ? ROUND(($QTY_T_OBAT_0_LAMA_BESAR/$QTY_T_OBAT_LAMA_BESAR)*100, 2) : 0;
  $P_LOT_T_OBAT_BARU_BESAR   = ($LOT_T_OBAT_BARU_BESAR > 0) ? ROUND(($LOT_T_OBAT_0_BARU_BESAR/$LOT_T_OBAT_BARU_BESAR)*100, 2) : 0;
  $P_QTY_T_OBAT_BARU_BESAR   = ($QTY_T_OBAT_BARU_BESAR > 0) ? ROUND(($QTY_T_OBAT_0_BARU_BESAR/$QTY_T_OBAT_BARU_BESAR)*100, 2) : 0;
  $P_LOT_T_OBAT_SETTING_BESAR   = ($LOT_T_OBAT_SETTING_BESAR > 0) ? ROUND(($LOT_T_OBAT_0_SETTING_BESAR/$LOT_T_OBAT_SETTING_BESAR)*100, 2) : 0;
  $P_QTY_T_OBAT_SETTING_BESAR   = ($QTY_T_OBAT_SETTING_BESAR > 0) ? ROUND(($QTY_T_OBAT_0_SETTING_BESAR/$QTY_T_OBAT_SETTING_BESAR)*100, 2) : 0;

  $P_LOT_T_OBAT_LAMA   = ($LOT_T_OBAT_LAMA > 0) ? ROUND(($LOT_T_OBAT_0_LAMA/$LOT_T_OBAT_LAMA)*100, 2) : 0;
  $P_QTY_T_OBAT_LAMA   = ($QTY_T_OBAT_LAMA > 0) ? ROUND(($QTY_T_OBAT_0_LAMA/$QTY_T_OBAT_LAMA)*100, 2) : 0;
  $P_LOT_T_OBAT_BARU   = ($LOT_T_OBAT_BARU > 0) ? ROUND(($LOT_T_OBAT_0_BARU/$LOT_T_OBAT_BARU)*100, 2) : 0;
  $P_QTY_T_OBAT_BARU   = ($QTY_T_OBAT_BARU > 0) ? ROUND(($QTY_T_OBAT_0_BARU/$QTY_T_OBAT_BARU)*100, 2) : 0;
  $P_LOT_T_OBAT_SETTING   = ($LOT_T_OBAT_SETTING > 0) ? ROUND(($LOT_T_OBAT_0_SETTING/$LOT_T_OBAT_SETTING)*100, 2) : 0;
  $P_QTY_T_OBAT_SETTING   = ($QTY_T_OBAT_SETTING > 0) ? ROUND(($QTY_T_OBAT_0_SETTING/$QTY_T_OBAT_SETTING)*100, 2) : 0;

  $rowspan_count = 0;
  $show_row_0 = !empty($LOT_T_OBAT_0_LAMA) || !empty($QTY_T_OBAT_0_LAMA) || !empty($LOT_T_OBAT_0_BARU) || !empty($QTY_T_OBAT_0_BARU) || !empty($LOT_T_OBAT_0_SETTING) || !empty($QTY_T_OBAT_0_SETTING);
  if ($show_row_0) {
      $rowspan_count++;
  }
  $show_row_1 = !empty($LOT_T_OBAT_1_LAMA) || !empty($QTY_T_OBAT_1_LAMA) || !empty($LOT_T_OBAT_1_BARU) || !empty($QTY_T_OBAT_1_BARU) || !empty($LOT_T_OBAT_1_SETTING) || !empty($QTY_T_OBAT_1_SETTING);
  if ($show_row_1) {
      $rowspan_count++;
  }
  $show_row_2 = !empty($LOT_T_OBAT_2_LAMA) || !empty($QTY_T_OBAT_2_LAMA) || !empty($LOT_T_OBAT_2_BARU) || !empty($QTY_T_OBAT_2_BARU) || !empty($LOT_T_OBAT_2_SETTING) || !empty($QTY_T_OBAT_2_SETTING);
  if ($show_row_2) {
      $rowspan_count++;
  }
  $show_row_3 = !empty($LOT_T_OBAT_3_LAMA) || !empty($QTY_T_OBAT_3_LAMA) || !empty($LOT_T_OBAT_3_BARU) || !empty($QTY_T_OBAT_3_BARU) || !empty($LOT_T_OBAT_3_SETTING) || !empty($QTY_T_OBAT_3_SETTING);
  if ($show_row_3) {
      $rowspan_count++;
  }
  $show_row_4 = !empty($LOT_T_OBAT_4_LAMA) || !empty($QTY_T_OBAT_4_LAMA) || !empty($LOT_T_OBAT_4_BARU) || !empty($QTY_T_OBAT_4_BARU) || !empty($LOT_T_OBAT_4_SETTING) || !empty($QTY_T_OBAT_4_SETTING);
  if ($show_row_4) {
      $rowspan_count++;
  }
  $show_row_5 = !empty($LOT_T_OBAT_5_LAMA) || !empty($QTY_T_OBAT_5_LAMA) || !empty($LOT_T_OBAT_5_BARU) || !empty($QTY_T_OBAT_5_BARU) || !empty($LOT_T_OBAT_5_SETTING) || !empty($QTY_T_OBAT_5_SETTING);
  if ($show_row_5) {
      $rowspan_count++;
  }
  $show_row_6 = !empty($LOT_T_OBAT_6_LAMA) || !empty($QTY_T_OBAT_6_LAMA) || !empty($LOT_T_OBAT_6_BARU) || !empty($QTY_T_OBAT_6_BARU) || !empty($LOT_T_OBAT_6_SETTING) || !empty($QTY_T_OBAT_6_SETTING);
  if ($show_row_6) {
      $rowspan_count++;
  }


  // Dept 
  $deptData = [
    [
        'dept' => 'CQA',
        'T_OBAT_TOTAL' => $CQA_T_OBAT_TOTAL,
        'T_OBAT_KECIL' => $CQA_T_OBAT_KECIL,
        'T_OBAT_BESAR' => $CQA_T_OBAT_BESAR,
        'TOK_TOTAL'    => $CQA_TOK_TOTAL,
        'TOK_KECIL'    => $CQA_TOK_KECIL,
        'TOK_BESAR'    => $CQA_TOK_BESAR,
    ],
    [
        'dept' => 'DYE',
        'T_OBAT_TOTAL' => $DYE_T_OBAT_TOTAL,
        'T_OBAT_KECIL' => $DYE_T_OBAT_KECIL,
        'T_OBAT_BESAR' => $DYE_T_OBAT_BESAR,
        'TOK_TOTAL'    => $DYE_TOK_TOTAL,
        'TOK_KECIL'    => $DYE_TOK_KECIL,
        'TOK_BESAR'    => $DYE_TOK_BESAR,
    ],
    [
        'dept' => 'LAB',
        'T_OBAT_TOTAL' => $LAB_T_OBAT_TOTAL,
        'T_OBAT_KECIL' => $LAB_T_OBAT_KECIL,
        'T_OBAT_BESAR' => $LAB_T_OBAT_BESAR,
        'TOK_TOTAL'    => $LAB_TOK_TOTAL,
        'TOK_KECIL'    => $LAB_TOK_KECIL,
        'TOK_BESAR'    => $LAB_TOK_BESAR,
    ],
    [
        'dept' => 'LAB/DYE',
        'T_OBAT_TOTAL' => $LAB_DYE_T_OBAT_TOTAL,
        'T_OBAT_KECIL' => $LAB_DYE_T_OBAT_KECIL,
        'T_OBAT_BESAR' => $LAB_DYE_T_OBAT_BESAR,
        'TOK_TOTAL'    => $LAB_DYE_TOK_TOTAL,
        'TOK_KECIL'    => $LAB_DYE_TOK_KECIL,
        'TOK_BESAR'    => $LAB_DYE_TOK_BESAR,
    ],
    [
        'dept' => 'DYE/CQA',
        'T_OBAT_TOTAL' => $DYE_CQA_T_OBAT_TOTAL,
        'T_OBAT_KECIL' => $DYE_CQA_T_OBAT_KECIL,
        'T_OBAT_BESAR' => $DYE_CQA_T_OBAT_BESAR,
        'TOK_TOTAL'    => $DYE_CQA_TOK_TOTAL,
        'TOK_KECIL'    => $DYE_CQA_TOK_KECIL,
        'TOK_BESAR'    => $DYE_CQA_TOK_BESAR,
    ],
    [
        'dept' => 'LAB/CQA',
        'T_OBAT_TOTAL' => $LAB_CQA_T_OBAT_TOTAL,
        'T_OBAT_KECIL' => $LAB_CQA_T_OBAT_KECIL,
        'T_OBAT_BESAR' => $LAB_CQA_T_OBAT_BESAR,
        'TOK_TOTAL'    => $LAB_CQA_TOK_TOTAL,
        'TOK_KECIL'    => $LAB_CQA_TOK_KECIL,
        'TOK_BESAR'    => $LAB_CQA_TOK_BESAR,
    ],
    [
        'dept' => 'LAB/DYE/CQA',
        'T_OBAT_TOTAL' => $LAB_DYE_CQA_T_OBAT_TOTAL,
        'T_OBAT_KECIL' => $LAB_DYE_CQA_T_OBAT_KECIL,
        'T_OBAT_BESAR' => $LAB_DYE_CQA_T_OBAT_BESAR,
        'TOK_TOTAL'    => $LAB_DYE_CQA_TOK_TOTAL,
        'TOK_KECIL'    => $LAB_DYE_CQA_TOK_KECIL,
        'TOK_BESAR'    => $LAB_DYE_CQA_TOK_BESAR,
    ],
    [
        'dept' => 'Lain - Lain',
        'T_OBAT_TOTAL' => $LAIN_T_OBAT_TOTAL,
        'T_OBAT_KECIL' => $LAIN_T_OBAT_KECIL,
        'T_OBAT_BESAR' => $LAIN_T_OBAT_BESAR,
        'TOK_TOTAL'    => $LAIN_TOK_TOTAL,
        'TOK_KECIL'    => $LAIN_TOK_KECIL,
        'TOK_BESAR'    => $LAIN_TOK_BESAR,
    ]
];
usort($deptData, function ($a, $b) {
    return $b['T_OBAT_TOTAL'] <=> $a['T_OBAT_TOTAL']; // dari terbesar ke kecil
});


$hc_categories = [];
$hc_data_t_obat = [];
$hc_data_tok = [];

foreach ($deptData as $d) {
    $hc_categories[] = $d['dept'];
    $hc_data_t_obat[] = [
        'y' => (int)$d['T_OBAT_TOTAL'],
        'detail_kecil' => (int)$d['T_OBAT_KECIL'],
        'detail_besar' => (int)$d['T_OBAT_BESAR']
    ];
    
    $hc_data_tok[] = [
        'y' => (int)$d['TOK_TOTAL'],
        'detail_kecil' => (int)$d['TOK_KECIL'],
        'detail_besar' => (int)$d['TOK_BESAR']
    ];
}

$js_categories = json_encode($hc_categories);
$js_data_t_obat = json_encode($hc_data_t_obat);
$js_data_tok = json_encode($hc_data_tok);

// Root Cause
$rootCauseData = [
    [
        'name' => 'Machine',
        'total' => $ROOT_MACHINE_KECIL + $ROOT_MACHINE_BESAR,
        'kecil' => $ROOT_MACHINE_KECIL,
        'besar' => $ROOT_MACHINE_BESAR,
        'p_total' => $P_TOT_MACHINE,
        'p_kecil' => $P_ROOT_MACHINE_KECIL,
        'p_besar' => $P_ROOT_MACHINE_BESAR,
    ],
    [
        'name' => 'Man',
        'total' => $ROOT_MAN_KECIL + $ROOT_MAN_BESAR,
        'kecil' => $ROOT_MAN_KECIL,
        'besar' => $ROOT_MAN_BESAR,
        'p_total' => $P_TOT_MAN,
        'p_kecil' => $P_ROOT_MAN_KECIL,
        'p_besar' => $P_ROOT_MAN_BESAR,
    ],
    [
        'name' => 'Methode',
        'total' => $ROOT_METHODE_KECIL + $ROOT_METHODE_BESAR,
        'kecil' => $ROOT_METHODE_KECIL,
        'besar' => $ROOT_METHODE_BESAR,
        'p_total' => $P_TOT_METHODE,
        'p_kecil' => $P_ROOT_METHODE_KECIL,
        'p_besar' => $P_ROOT_METHODE_BESAR,
    ],
    [
        'name' => 'Measurement',
        'total' => $ROOT_MEASUREMENT_KECIL + $ROOT_MEASUREMENT_BESAR,
        'kecil' => $ROOT_MEASUREMENT_KECIL,
        'besar' => $ROOT_MEASUREMENT_BESAR,
        'p_total' => $P_TOT_MEASUREMENT,
        'p_kecil' => $P_ROOT_MEASUREMENT_KECIL,
        'p_besar' => $P_ROOT_MEASUREMENT_BESAR,
    ],
    [
        'name' => 'Material',
        'total' => $ROOT_MATERIAL_KECIL + $ROOT_MATERIAL_BESAR,
        'kecil' => $ROOT_MATERIAL_KECIL,
        'besar' => $ROOT_MATERIAL_BESAR,
        'p_total' => $P_TOT_MATERIAL,
        'p_kecil' => $P_ROOT_MATERIAL_KECIL,
        'p_besar' => $P_ROOT_MATERIAL_BESAR,
    ],
    [
        'name' => 'Environment',
        'total' => $ROOT_ENVIRONMENT_KECIL + $ROOT_ENVIRONMENT_BESAR,
        'kecil' => $ROOT_ENVIRONMENT_KECIL,
        'besar' => $ROOT_ENVIRONMENT_BESAR,
        'p_total' => $P_TOT_ENVIRONMENT,
        'p_kecil' => $P_ROOT_ENVIRONMENT_KECIL,
        'p_besar' => $P_ROOT_ENVIRONMENT_BESAR,
    ]
];


usort($rootCauseData, function($a, $b) {
    return $b['total'] <=> $a['total'];
});
?>
<div class="row">

  <div class="col-lg-6 col-md-12">
    <div class="table-container">
      <h3 class="box-title">Analisa Bulan <?= strtoupper(date('M', strtotime($Akhir))); ?></h3>
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped" style="font-size: 25px;">
          <thead class="bg-blue">
            <tr>
              <th><div align="center">Keterangan</div></th>
              <th><div align="center">Resep</div></th>
              <th><div align="center">Target Analisa</div></th>
              <th><div align="center">Analisa</div></th>
              <th>On Proses</th>
              <th>Belum Analisa</th>
              <th>Ok</th>
              <th>Tidak Ok</th>
              <th>% Ok</th>
              <th>%Tidak Ok</th>
              <th>% Analisa</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td align="left">Total</td>
              <td><div align="center"><?= $T_ANALISA; ?></div></td>
              <td><div align="center"><?= $T_ANALISA; ?></div></td>
              <td><div align="center"><?= $ANALISA; ?></div></td>
              <td><div align="center"><?= $PROSES; ?></div></td>
              <td><div align="center"><?= $BELUM_ANALISA; ?></div></td>
              <td><div align="center"><?= $OK; ?></div></td>
              <td><div align="center"><?= $TOK; ?></div></td>
              <td><div align="center"><?= $P_OK . ' %'; ?></div></td>
              <td><div align="center"><?= $P_TOK . ' %'; ?></div></td>
              <td><div align="center"><?= $P_ANALISA . ' %'; ?></div></td>
            </tr>
            <tr>
              <td align="left">MC &lt; 300</td>
              <td><div align="center"><?= $T_ANALISA_KECIL; ?></div></td>
              <td><div align="center"><?= $T_ANALISA_KECIL; ?></div></td>
              <td><div align="center"><?= $ANALISA_KECIL; ?></div></td>
              <td><div align="center"><?= $PROSES_KECIL; ?></div></td>
              <td><div align="center"><?= $BELUM_ANALISA_KECIL; ?></div></td>
              <td><div align="center"><?= $OK_KECIL; ?></div></td>
              <td><div align="center"><?= $TOK_KECIL; ?></div></td>
              <td><div align="center"><?= $P_OK_KECIL . ' %'; ?></div></td>
              <td><div align="center"><?= $P_TOK_KECIL . ' %'; ?></div></td>
              <td><div align="center"><?= $P_ANALISA_KECIL . ' %'; ?></div></td>
            </tr>
            <tr>
              <td align="left">MC &gt; 300</td>
              <td><div align="center"><?= $T_ANALISA_BESAR; ?></div></td>
              <td><div align="center"><?= $T_ANALISA_BESAR; ?></div></td>
              <td><div align="center"><?= $ANALISA_BESAR; ?></div></td>
              <td><div align="center"><?= $PROSES_BESAR; ?></div></td>
              <td><div align="center"><?= $BELUM_ANALISA_BESAR; ?></div></td>
              <td><div align="center"><?= $OK_BESAR; ?></div></td>
              <td><div align="center"><?= $TOK_BESAR; ?></div></td>
              <td><div align="center"><?= $P_OK_BESAR . ' %'; ?></div></td>
              <td><div align="center"><?= $P_TOK_BESAR . ' %'; ?></div></td>
              <td><div align="center"><?= $P_ANALISA_BESAR . ' %'; ?></div></td>
            </tr>
          </tbody>
        </table>
        <br>
        <figure class="highcharts-figure">
            <div id="container-grafik-persentase"></div>
        </figure>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
      </div>
    </div>
  </div>
  
  <div class="col-lg-6 col-md-12">
    <div class="table-container">
        <h3 class="box-title">Data Status Recipe</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="bg-blue">
                    <tr>
                        <th rowspan="2"><div align="center">Status Resep</div></th>
                        <th colspan="3"><div align="center">0x</div></th>
                        <th colspan="3"><div align="center">Tambah Obat</div></th>
                    </tr>
                    <tr>
                        <th><div align="center">OK</div></th>
                        <th><div align="center">Tidak OK</div></th>
                        <th><div align="center">Progress</div></th>
                        <th><div align="center">OK</div></th>
                        <th><div align="center">Tidak OK</div></th>
                        <th><div align="center">Progress</div></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan='2'><div align="center">Total</div></td>
                        <td><div align="center"><?= $STS_RESEP_OK; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOK; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_PROGRESS; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOBAT_OK; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOBAT_TOK; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOBAT_PROGRESS; ?></div></td>
                    </tr>
                    <tr>
                        <td><div align="center"><?= $P_STS_RESEP_OK . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOK . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_PROGRESS . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOBAT_OK . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOBAT_TOK . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOBAT_PROGRESS . '%'; ?></div></td>
                    </tr>
                    <tr>
                        <td rowspan='2'><div align="center">MC &lt; 300</div></td>
                        <td><div align="center"><?= $STS_RESEP_OK_KECIL; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOK_KECIL; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_PROGRESS_KECIL; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOBAT_OK_KECIL; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOBAT_TOK_KECIL; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOBAT_PROGRESS_KECIL; ?></div></td>
                    </tr>
                    <tr>
                        <td><div align="center"><?= $P_STS_RESEP_OK_KECIL . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOK_KECIL . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_PROGRESS_KECIL . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOBAT_OK_KECIL . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOBAT_TOK_KECIL . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOBAT_PROGRESS_KECIL . '%'; ?></div></td>
                    </tr>
                    <tr>
                        <td rowspan='2'><div align="center">MC &gt; 300</div></td>
                        <td><div align="center"><?= $STS_RESEP_OK_BESAR; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOK_BESAR; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_PROGRESS_BESAR; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOBAT_OK_BESAR; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOBAT_TOK_BESAR; ?></div></td>
                        <td><div align="center"><?= $STS_RESEP_TOBAT_PROGRESS_BESAR; ?></div></td>
                    </tr>
                    <tr>
                        <td><div align="center"><?= $P_STS_RESEP_OK_BESAR . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOK_BESAR . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_PROGRESS_BESAR . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOBAT_OK_BESAR . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOBAT_TOK_BESAR . '%'; ?></div></td>
                        <td><div align="center"><?= $P_STS_RESEP_TOBAT_PROGRESS_BESAR . '%'; ?></div></td>
                    </tr>
                </tbody>
            </table>
            <br>
                <figure class="highcharts-figure">
                    <div id="container-status-recipe"></div>
                </figure>
        </div>
    </div>
  </div>


  <div class="col-lg-6 col-md-12">
      <div class="table-container">
          <h3 class="box-title">Data Dept Penyebab</h3>
          <div class="table-responsive">
              <table class="table table-bordered table-hover table-striped">
                  <thead class="bg-blue">
                      <tr>
                          <th rowspan="2"><div align="center">DEPT PENYEBAB</div></th>
                          <th colspan="3"><div align="center">Tambah Obat</div></th>
                          <th colspan="3"><div align="center">Resep Tidak Ok</div></th>
                      </tr>
                      <tr>
                          <th><div align="center">Total</div></th>
                          <th><div align="center">MC &lt; 300 Kg</div></th>
                          <th><div align="center">MC &gt; 300 Kg</div></th>
                          <th><div align="center">Total</div></th>
                          <th><div align="center">MC &lt; 300 Kg</div></th>
                          <th><div align="center">MC &gt; 300 Kg</div></th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php $list_dept = [];
                        foreach ($deptData as $d): 
                        $list_dept[] = $d['dept'];?>
                    <tr>
                        <td align="left"><?= htmlspecialchars($d['dept']); ?></td>
                        <td align="center"><?= $d['T_OBAT_TOTAL']; ?></td>
                        <td align="center"><?= $d['T_OBAT_KECIL']; ?></td>
                        <td align="center"><?= $d['T_OBAT_BESAR']; ?></td>
                        <td align="center"><?= $d['TOK_TOTAL']; ?></td>
                        <td align="center"><?= $d['TOK_KECIL']; ?></td>
                        <td align="center"><?= $d['TOK_BESAR']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
              </table>
              <br>
                <figure class="highcharts-figure">
                    <div id="container-grafik-departemen"></div>
                </figure>
          </div>
      </div>
  </div>

  <div class="col-lg-12 col-md-12">
      <div class="table-container">
          <h3 class="box-title">Data Kestabilan Resep</h3>
          <div class="table-responsive">
              <table class="table table-bordered table-hover table-striped">
                  <thead class="bg-blue">
                      <tr>
                          <th rowspan="3"><div align="center">BULAN <?= strtoupper(date('M', strtotime($Akhir))); ?></div></th>
                          <th colspan="19"><div align="center">KESTABILAN RESEP</div></th>
                      </tr>
                      <tr>
                          <th rowspan="2"><div align="center">TAMBAH OBAT</div></th>
                          <th colspan="2"><div align="center">RESEP LAMA</div></th>
                          <th colspan="2"><div align="center">RESEP LAMA MC &lt; 300 </div></th>
                          <th colspan="2"><div align="center">RESEP LAMA MC &gt; 300 </div></th>
                          <th colspan="2"><div align="center">RESEP BARU</div></th>
                          <th colspan="2"><div align="center">RESEP BARU MC &lt; 300</div></th>
                          <th colspan="2"><div align="center">RESEP BARU MC &gt; 300</div></th>
                          <th colspan="2"><div align="center">RESEP SETTING</div></th>
                          <th colspan="2"><div align="center">RESEP SETTING MC &lt; 300</div></th>
                          <th colspan="2"><div align="center">RESEP SETTING MC &gt; 300</div></th>
                      </tr>
                      <tr>
                          <th><div align="center">LOT</div></th>
                          <th><div align="center">QTY</div></th>
                          <th><div align="center">LOT</div></th>
                          <th><div align="center">QTY</div></th>
                          <th><div align="center">LOT</div></th>
                          <th><div align="center">QTY</div></th>
                          <th><div align="center">LOT</div></th>
                          <th><div align="center">QTY</div></th>
                          <th><div align="center">LOT</div></th>
                          <th><div align="center">QTY</div></th>
                          <th><div align="center">LOT</div></th>
                          <th><div align="center">QTY</div></th>
                          <th><div align="center">LOT</div></th>
                          <th><div align="center">QTY</div></th>
                          <th><div align="center">LOT</div></th>
                          <th><div align="center">QTY</div></th>
                          <th><div align="center">LOT</div></th>
                          <th><div align="center">QTY</div></th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php if($show_row_0):?>
                      <tr>
                          <td rowspan="<?= $rowspan_count ?>"><div align="center">Total</div></td>
                          <td><div align="center">0x</div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_0_LAMA; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_0_LAMA; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_0_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_0_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_0_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_0_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_0_BARU; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_0_BARU; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_0_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_0_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_0_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_0_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_0_SETTING; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_0_SETTING; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_0_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_0_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_0_SETTING_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_0_SETTING_BESAR; ?></div></td>
                      </tr>
                    <?php endif; ?>
                    <?php if($show_row_1):?>
                      <tr>
                          <td><div align="center">1x</div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_1_LAMA; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_1_LAMA; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_1_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_1_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_1_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_1_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_1_BARU; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_1_BARU; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_1_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_1_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_1_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_1_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_1_SETTING; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_1_SETTING; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_1_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_1_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_1_SETTING_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_1_SETTING_BESAR; ?></div></td>
                      </tr>
                    <?php endif; ?>
                    <?php if($show_row_2):?>
                      <tr>
                          <td><div align="center">2x</div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_2_LAMA; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_2_LAMA; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_2_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_2_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_2_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_2_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_2_BARU; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_2_BARU; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_2_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_2_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_2_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_2_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_2_SETTING; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_2_SETTING; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_2_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_2_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_2_SETTING_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_2_SETTING_BESAR; ?></div></td>
                      </tr>
                    <?php endif; ?>
                    <?php if($show_row_3):?>
                      <tr>
                          <td><div align="center">3x</div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_3_LAMA; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_3_LAMA; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_3_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_3_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_3_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_3_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_3_BARU; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_3_BARU; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_3_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_3_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_3_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_3_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_3_SETTING; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_3_SETTING; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_3_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_3_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_3_SETTING_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_3_SETTING_BESAR; ?></div></td>
                      </tr>
                    <?php endif; ?>
                    <?php if($show_row_4):?>
                      <tr>
                          <td><div align="center">4x</div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_4_LAMA; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_4_LAMA; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_4_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_4_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_4_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_4_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_4_BARU; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_4_BARU; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_4_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_4_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_4_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_4_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_4_SETTING; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_4_SETTING; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_4_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_4_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_4_SETTING_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_4_SETTING_BESAR; ?></div></td>
                      </tr>
                    <?php endif; ?>
                    <?php if($show_row_5):?>
                      <tr>
                          <td><div align="center">5x</div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_5_LAMA; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_5_LAMA; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_5_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_5_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_5_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_5_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_5_BARU; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_5_BARU; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_5_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_5_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_5_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_5_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_5_SETTING; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_5_SETTING; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_5_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_5_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_5_SETTING_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_5_SETTING_BESAR; ?></div></td>
                      </tr>
                    <?php endif; ?>
                    <?php if($show_row_6):?>
                      <tr>
                          <td><div align="center">6x</div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_6_LAMA; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_6_LAMA; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_6_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_6_LAMA_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_6_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_6_LAMA_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_6_BARU; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_6_BARU; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_6_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_6_BARU_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_6_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_6_BARU_BESAR; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_6_SETTING; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_6_SETTING; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_6_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_6_SETTING_KECIL; ?></div></td>
                          <td><div align="center"><?= $LOT_T_OBAT_6_SETTING_BESAR; ?></div></td>
                          <td><div align="center"><?= $QTY_T_OBAT_6_SETTING_BESAR; ?></div></td>
                      </tr>
                    <?php endif; ?>
                    <tr>
                      <td colspan="2"><div align="center">%</div></td>
                      <td><div align="center"><?= $P_LOT_T_OBAT_LAMA . '%'; ?></div></td>
                      <td><div align="center"><?= $P_QTY_T_OBAT_LAMA . '%'; ?></div></td>
                      <td><div align="center"><?= $P_LOT_T_OBAT_LAMA_KECIL . '%'; ?></div></td>
                      <td><div align="center"><?= $P_QTY_T_OBAT_LAMA_KECIL . '%'; ?></div></td>
                      <td><div align="center"><?= $P_LOT_T_OBAT_LAMA_BESAR . '%'; ?></div></td>
                      <td><div align="center"><?= $P_QTY_T_OBAT_LAMA_BESAR . '%'; ?></div></td>
                      <td><div align="center"><?= $P_LOT_T_OBAT_BARU . '%'; ?></div></td>
                      <td><div align="center"><?= $P_QTY_T_OBAT_BARU . '%'; ?></div></td>
                      <td><div align="center"><?= $P_LOT_T_OBAT_BARU_KECIL . '%'; ?></div></td>
                      <td><div align="center"><?= $P_QTY_T_OBAT_BARU_KECIL . '%'; ?></div></td>
                      <td><div align="center"><?= $P_LOT_T_OBAT_BARU_BESAR . '%'; ?></div></td>
                      <td><div align="center"><?= $P_QTY_T_OBAT_BARU_BESAR . '%'; ?></div></td>
                      <td><div align="center"><?= $P_LOT_T_OBAT_SETTING . '%'; ?></div></td>
                      <td><div align="center"><?= $P_QTY_T_OBAT_SETTING . '%'; ?></div></td>
                      <td><div align="center"><?= $P_LOT_T_OBAT_SETTING_KECIL . '%'; ?></div></td>
                      <td><div align="center"><?= $P_QTY_T_OBAT_SETTING_KECIL . '%'; ?></div></td>
                      <td><div align="center"><?= $P_LOT_T_OBAT_SETTING_BESAR . '%'; ?></div></td>
                      <td><div align="center"><?= $P_QTY_T_OBAT_SETTING_BESAR . '%'; ?></div></td>
                    </tr>
                  </tbody>
              </table>
              <br>
                <figure class="highcharts-figure">
                    <div id="container-kestabilan-resep"></div>
                </figure>
          </div>
      </div>
  </div>

  <div class="col-lg-6 col-md-12">
    <div class="table-container">
        <h3 class="box-title">Data Performance Lab</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="bg-blue">
                    <tr>
                        <th rowspan="2"><div align="center">RESEP BARU (LAB)</div></th>
                        <th colspan="5"><div align="center">BULAN <?= strtoupper(date('M', strtotime($Akhir))); ?></div></th>
                    </tr>
                    <tr>
                        <th><div align="center">TOTAL</div></th>
                        <th><div align="center">OKE</div></th>
                        <th><div align="center">TIDAK OKE</div></th>
                        <th><div align="center">% OKE</div></th>
                        <th><div align="center">% TIDAK OKE</div></th>
                    </tr>
                </thead>
                <?php
                  $hasilData = [];
                    $hc_lab_categories = []; 
                    $hc_lab_data_p_ok  = []; 
                    $hc_lab_data_p_tok = []; 
                  foreach ($user_lab as $user) {
                      if (trim($user) === '-' || trim($user) === '') continue; 

                      $userKey = str_replace([' ', '.'], ['_', ''], strtoupper($user));

                      $jumlah_ok  = $performanceLabData[$userKey]['OK_LAB'] ?? 0;
                      $jumlah_tok = $performanceLabData[$userKey]['TOK_LAB'] ?? 0;
                      $total_user = $performanceLabData[$userKey]['TOTAL']   ?? 0;

                      $hasilData[] = [
                          'user'        => $user,
                          'jumlah_ok'   => $jumlah_ok,
                          'jumlah_tok'  => $jumlah_tok,
                          'total_user'  => $total_user
                      ];
                  }

                  usort($hasilData, function ($a, $b) {
                      return $b['total_user'] <=> $a['total_user'];
                  });
                  foreach ($hasilData as $row) {
                        $hc_lab_categories[] = htmlspecialchars($row['user']);                        
                        $total_user = (int)$row['total_user'];
                        $jumlah_ok  = (int)$row['jumlah_ok'];
                        $jumlah_tok = (int)$row['jumlah_tok'];
    
                        $persen_ok  = ($total_user > 0) ? round(($jumlah_ok / $total_user) * 100, 2) : 0;
                        $persen_tok = ($total_user > 0) ? round(($jumlah_tok / $total_user) * 100, 2) : 0;
                        
                        $hc_lab_data_p_ok[]  = $persen_ok;
                        $hc_lab_data_p_tok[] = $persen_tok;
                        $hc_lab_total_user[] = $total_user;
                    }

                    $js_lab_categories = json_encode($hc_lab_categories);
                    $js_lab_data_p_ok  = json_encode($hc_lab_data_p_ok);
                    $js_lab_data_p_tok = json_encode($hc_lab_data_p_tok);
                    $js_lab_total_user = json_encode($hc_lab_total_user);
                  ?>

                  <tbody>
                    <?php foreach ($hasilData as $row): ?>
                        <tr>
                            <td align="left"><?= htmlspecialchars($row['user']); ?></td>
                            <td align="center"><?= $row['total_user']; ?></td>
                            <td align="center"><b><?= $row['jumlah_ok']; ?></b></td>
                            <td align="center"><b><?= $row['jumlah_tok']; ?></b></td>
                            <td align="center"><b><?= ($row['total_user'] > 0) ? number_format(($row['jumlah_ok'] / $row['total_user']) * 100, 2) . ' %' : '0.00 %'; ?></b></td>
                            <td align="center"><b><?= ($row['total_user'] > 0) ? number_format(($row['jumlah_tok'] / $row['total_user']) * 100, 2) . ' %' : '0.00 %'; ?></b></td>
                        </tr>
                    <?php endforeach; ?>
                  </tbody>
            </table>
            <br>
            <figure class="highcharts-figure">
                <div id="container-grafik-lab-persen"></div>
            </figure>
        </div>
    </div>
  </div>

  <div class="col-lg-6 col-md-12">
    <div class="table-container">
        <h3 class="box-title" style="margin-top: 20px;">Data Performance Dye</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="bg-blue">
                    <tr>
                        <th rowspan="2"><div align="center">RESEP BARU (DYE)</div></th>
                        <th colspan="5"><div align="center">BULAN <?= strtoupper(date('M', strtotime($Akhir))); ?></div></th>
                    </tr>
                    <tr>
                        <th><div align="center">TOTAL</div></th>
                        <th><div align="center">OKE</div></th>
                        <th><div align="center">TIDAK OKE</div></th>
                        <th><div align="center">% OKE</div></th>
                        <th><div align="center">% TIDAK OKE</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $data_dye = [];
                        $allowed_users = [ 'DWIJO MAULANA',
                                            'JIANG MINGWEI',
                                            'M. SUBIHADI',
                                            'TIDAK MATCHING'
                                            ];
                        $hc_dye_categories = []; 
                        $hc_dye_data_p_ok  = []; 
                        $hc_dye_data_p_tok = []; 
                        foreach($user_dye as $user):
                        $userKey = str_replace([' ', '.'], ['_', ''], strtoupper($user));
                        $jumlah_ok_dye  = $performanceDyeData[$userKey]['OK_DYE'] ?? 0;
                        $jumlah_tok_dye = $performanceDyeData[$userKey]['TOK_DYE'] ?? 0;
                        $total_user_dye = $performanceDyeData[$userKey]['TOTAL'] ?? 0;
                        $data_dye[] = [
                          'user'        => $user,
                          'jumlah_ok'   => $jumlah_ok_dye,
                          'jumlah_tok'  => $jumlah_tok_dye,
                          'total_user'  => $total_user_dye
                      ];
                      
                      endforeach;
                      usort($data_dye, function ($a, $b) {
                          return $b['total_user'] <=> $a['total_user'];
                      });
                      foreach ($data_dye as $row) {
                       if (in_array(strtoupper(trim($row['user'])), $allowed_users)) {
                            $hc_dye_categories[] = htmlspecialchars($row['user']);
                            $total_user = (int)$row['total_user'];
                            $jumlah_ok  = (int)$row['jumlah_ok'];
                            $jumlah_tok = (int)$row['jumlah_tok'];
                            
                            $persen_ok  = ($total_user > 0) ? round(($jumlah_ok / $total_user) * 100, 2) : 0;
                            $persen_tok = ($total_user > 0) ? round(($jumlah_tok / $total_user) * 100, 2) : 0;
                            
                            $hc_dye_data_p_ok[]  = $persen_ok;
                            $hc_dye_data_p_tok[] = $persen_tok;
                            $hc_dye_total_user[] = $total_user;
                        }
                    }

                    $js_dye_categories = json_encode($hc_dye_categories);
                    $js_dye_data_p_ok  = json_encode($hc_dye_data_p_ok);
                    $js_dye_data_p_tok = json_encode($hc_dye_data_p_tok);
                    $js_dye_data_p_tok = json_encode($hc_dye_data_p_tok);
                    $js_dye_total_user = json_encode($hc_dye_total_user);
                    ?>
                    <?php foreach ($data_dye as $row): ?>
                    <tr>
                          <td align="left"><?= htmlspecialchars($row['user']); ?></td>
                          <td align="center"><?= $row['total_user']; ?></td>
                          <td align="center"><b><?= $row['jumlah_ok']; ?></b></td>
                          <td align="center"><b><?= $row['jumlah_tok']; ?></b></td>
                          <td align="center"><b><?= ($row['total_user'] > 0) ? number_format(($row['jumlah_ok'] / $row['total_user']) * 100, 2) . ' %' : '0.00 %'; ?></b></td>
                          <td align="center"><b><?= ($row['total_user'] > 0) ? number_format(($row['jumlah_tok'] / $row['total_user']) * 100, 2) . ' %' : '0.00 %'; ?></b></td>
                      </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <figure class="highcharts-figure">
                <div id="container-grafik-dye-persen"></div>
            </figure>
        </div>
    </div>
  </div>

  <div class="col-lg-6 col-md-12">
    <div class="table-container">
        <h3 class="box-title">Data Performance Setting</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="bg-blue">
                    <tr>
                        <th rowspan="2"><div align="center">RESEP SETTING SEBELUM</div></th>
                        <th colspan="5"><div align="center">BULAN <?= strtoupper(date('M', strtotime($Akhir))); ?></div></th>
                    </tr>
                    <tr>
                        <th><div align="center">TOTAL</div></th>
                        <th><div align="center">OKE</div></th>
                        <th><div align="center">TIDAK OKE</div></th>
                        <th><div align="center">% OKE</div></th>
                        <th><div align="center">% TIDAK OKE</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                      $data_sblm = [];
                      $allowed_users = [ 'DWIJO MAULANA',
                                            'JIANG MINGWEI',
                                            'M. SUBIHADI',
                                            'TIDAK MATCHING'
                                            ];
                        $hc_sebelum_categories = []; 
                        $hc_sebelum_data_p_ok  = []; 
                        $hc_sebelum_data_p_tok = []; 
                        foreach($user_sblm as $user):
                        $userKey = str_replace([' ', '.'], ['_', ''], strtoupper($user));
                        $jumlah_ok_sebelum  = $performanceSettingSebelum[$userKey]['OK_SEBELUM'] ?? 0;
                        $jumlah_tok_sebelum = $performanceSettingSebelum[$userKey]['TOK_SEBELUM'] ?? 0;
                        $total_user_sebelum = $performanceSettingSebelum[$userKey]['TOTAL']??0;
                        $data_sblm[] = [
                          'user'        => $user,
                          'jumlah_ok'   => $jumlah_ok_sebelum,
                          'jumlah_tok'  => $jumlah_tok_sebelum,
                          'total_user'  => $total_user_sebelum
                        ];
                        endforeach;
                        usort($data_sblm, function ($a, $b) {
                            return $b['total_user'] <=> $a['total_user'];
                        });
                        foreach ($data_sblm as $row) {
                        if (in_array(strtoupper(trim($row['user'])), $allowed_users)) {
                                $hc_sebelum_categories[] = htmlspecialchars($row['user']);
                                $total_user = (int)$row['total_user'];
                                $jumlah_ok  = (int)$row['jumlah_ok'];
                                $jumlah_tok = (int)$row['jumlah_tok'];
                                
                                $persen_ok  = ($total_user > 0) ? round(($jumlah_ok / $total_user) * 100, 2) : 0;
                                $persen_tok = ($total_user > 0) ? round(($jumlah_tok / $total_user) * 100, 2) : 0;
                                
                                $hc_sebelum_data_p_ok[]  = $persen_ok;
                                $hc_sebelum_data_p_tok[] = $persen_tok;
                                $hc_sebelum_total_user[] = $total_user;
                            }
                        }

                        $js_sebelum_categories = json_encode($hc_sebelum_categories);
                        $js_sebelum_data_p_ok  = json_encode($hc_sebelum_data_p_ok);
                        $js_sebelum_data_p_tok = json_encode($hc_sebelum_data_p_tok);
                        $js_sebelum_total_user = json_encode($hc_sebelum_total_user);
                    ?>
                    <?php foreach ($data_sblm as $row): ?>
                     <tr>
                          <td align="left"><?= htmlspecialchars($row['user']); ?></td>
                          <td align="center"><?= $row['total_user']; ?></td>
                          <td align="center"><b><?= $row['jumlah_ok']; ?></b></td>
                          <td align="center"><b><?= $row['jumlah_tok']; ?></b></td>
                          <td align="center"><b><?= ($row['total_user'] > 0) ? number_format(($row['jumlah_ok'] / $row['total_user']) * 100, 2) . ' %' : '0.00 %'; ?></b></td>
                          <td align="center"><b><?= ($row['total_user'] > 0) ? number_format(($row['jumlah_tok'] / $row['total_user']) * 100, 2) . ' %' : '0.00 %'; ?></b></td>
                      </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <figure class="highcharts-figure">
                <div id="container-grafik-setting-sebelum"></div>
            </figure>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">
Highcharts.chart('container-grafik-persentase', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Laporan Analisa (Persentase)'
    },
    subtitle: {
        text: 'Sumber Data dari Tabel'
    },
    xAxis: {
        categories: [
            '% OK',
            '% Tidak Ok',
            '% On Proses'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Persentase (%)' 
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.2f} %</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%' 
            }
        }
    },
    
    series: [{
        name: 'Total',
        data: [
            <?= $P_OK; ?>, 
            <?= $P_TOK; ?>, 
            (<?= $T_ANALISA; ?> > 0 ? (<?= $PROSES; ?> / <?= $T_ANALISA; ?>) * 100 : 0)
        ]

    }, {
        name: 'MC < 300',
        data: [
            <?= $P_OK_KECIL; ?>, 
            <?= $P_TOK_KECIL; ?>, 
            (<?= $T_ANALISA_KECIL; ?> > 0 ? (<?= $PROSES_KECIL; ?> / <?= $T_ANALISA_KECIL; ?>) * 100 : 0)
        ]

    }, {
        name: 'MC > 300',
        data: [
            <?= $P_OK_BESAR; ?>, 
            <?= $P_TOK_BESAR; ?>, 
            (<?= $T_ANALISA_BESAR; ?> > 0 ? (<?= $PROSES_BESAR; ?> / <?= $T_ANALISA_BESAR; ?>) * 100 : 0)
        ]
    }]
});
</script>

<script type="text/javascript">

Highcharts.chart('container-status-recipe', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Laporan Status Recipe'
    },
    xAxis: {
        categories: [
            '% OK',
            '% Tidak Ok',
            '% On Proses'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Persentase (%)' 
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.2f} %</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%' 
            }
        }
    },
    
    series: [{
        name: 'Total 0x',
        data: [
            <?= $P_STS_RESEP_OK; ?>,
            <?= $P_STS_RESEP_TOK; ?>,
            <?= $P_STS_RESEP_PROGRESS; ?>
        ]
    }, 
    {
        name: 'MC < 300 0x',
        data: [
            <?= $P_STS_RESEP_OK_KECIL; ?>, 
            <?= $P_STS_RESEP_TOK_KECIL; ?>, 
            <?= $P_STS_RESEP_PROGRESS_KECIL; ?>
        ]

    },
    {
        name: 'MC > 300 0x',
        data: [
            <?= $P_STS_RESEP_OK_BESAR; ?>, 
            <?= $P_STS_RESEP_TOK_BESAR; ?>, 
            <?= $P_STS_RESEP_PROGRESS_BESAR; ?>
        ]
    }, 
    {
        name: 'Total T.Obat',
        data: [
            <?= $P_STS_RESEP_TOBAT_OK; ?>,
            <?= $P_STS_RESEP_TOBAT_TOK; ?>,
            <?= $P_STS_RESEP_TOBAT_PROGRESS; ?>
        ]
    },
    {
        name: 'MC < 300 T.Obat',
        data: [
            <?= $P_STS_RESEP_TOBAT_OK_KECIL; ?>, 
            <?= $P_STS_RESEP_TOBAT_TOK_KECIL; ?>, 
            <?= $P_STS_RESEP_TOBAT_PROGRESS_KECIL; ?>,
        ]

    }, 
    {
        name: 'MC > 300 T.Obat',
        data: [ 
            <?= $P_STS_RESEP_TOBAT_OK_BESAR; ?>, 
            <?= $P_STS_RESEP_TOBAT_TOK_BESAR; ?>, 
            <?= $P_STS_RESEP_TOBAT_PROGRESS_BESAR; ?>,
        ]
    }
]
});
</script>

<script type="text/javascript">
Highcharts.chart('container-grafik-departemen', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Laporan Departemen Penyebab'
    },
    xAxis: {
        categories: <?= $js_categories; ?>,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Jumlah Kasus'
        }
    },
    tooltip: {
        useHTML: true,
        formatter: function () {
            let s = '<b>' + this.key + '</b><br/>'; 
            s += '<span style="color:' + this.series.color + '">●</span> ' + this.series.name + '<br/><br/>'; // Nama Seri
            
            // Format mirip gambar Anda
            s += 'Total: <b>' + this.point.y + '</b><br/>';
            s += 'MC &lt; 300 Kg: <b>' + this.point.options.detail_kecil + '</b><br/>';
            s += 'MC &gt; 300 Kg: <b>' + this.point.options.detail_besar + '</b>';
            
            return s;
        }
    },
    
    plotOptions: {
        column: {
            pointPadding: 0,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },
    
    series: [{
        name: 'Tambah Obat',
        data: <?= $js_data_t_obat; ?> 
    }, {
        name: 'Resep Tidak Ok',
        data: <?= $js_data_tok; ?> 
    }]
});
</script>

<script type="text/javascript">
Highcharts.chart('container-kestabilan-resep', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Laporan Kestabilan Recipe'
    },
    xAxis: {
        categories: [
            'Resep Lama',
            'Resep Baru',
            'Resep Setting'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Persentase (%)' 
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.2f} %</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%' 
            }
        }
    },
    series: [{
        name: 'Total',
        data: [
            <?= $P_LOT_T_OBAT_LAMA; ?>,
            <?= $P_LOT_T_OBAT_BARU; ?>, 
            <?= $P_LOT_T_OBAT_SETTING; ?>, 
            
        ]
    }, 
    {
        name: 'MC < 300',
        data: [
            <?= $P_LOT_T_OBAT_LAMA_KECIL; ?>,
            <?= $P_LOT_T_OBAT_BARU_KECIL; ?>, 
            <?= $P_LOT_T_OBAT_SETTING_KECIL; ?>, 
        ]

    },
    {
        name: 'MC > 300',
        data: [
            <?= $P_LOT_T_OBAT_LAMA_BESAR; ?>,
            <?= $P_LOT_T_OBAT_BARU_BESAR; ?>,
            <?= $P_LOT_T_OBAT_SETTING_BESAR; ?>
        ]
    }
]
});
</script>

<script type="text/javascript">
const totalUser = <?= $js_lab_total_user; ?>; 
Highcharts.chart('container-grafik-lab-persen', {
    chart: {
        type: 'column'
    },
    
    title: {
        text: 'Laporan Performa Resep Baru (LAB) - Persentase'
    },
    xAxis: {
        categories: <?= $js_lab_categories; ?>,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Persentase (%)' 
        },
        labels: {
            format: '{value} %' 
        }
    },
      tooltip: {
      shared: true,
      useHTML: true,
      formatter: function () {
        const index = this.points[0].point.index; 
        const total = totalUser[index];           
        let tooltip = `<b>${this.key}</b><br/>`;

        this.points.forEach(p => {
          tooltip += `${p.series.name}: <b>${p.y.toFixed(2)} %</b><br/>`;
        });

        tooltip += `<b>Total: ${total}</b>`;
        return tooltip;
      }
    },
    plotOptions: {
        column: {
            pointPadding: 0,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f} %', 
                style: {
                    fontSize: '10px'
                }
            }
        }
    },
    
    series: [{
        name: '% Oke',
        data: <?= $js_lab_data_p_ok; ?>
    }, {
        name: '% Tidak Oke',
        data: <?= $js_lab_data_p_tok; ?>
    }]
});
</script>

<script type="text/javascript">
const totalUserDye = <?= $js_dye_total_user; ?>; 
Highcharts.chart('container-grafik-dye-persen', {
    chart: {
        type: 'column'
    },
    
    title: {
        text: 'Laporan Performa Resep Baru (DYE) - Persentase'
    },
    xAxis: {
        categories: <?= $js_dye_categories; ?>,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Persentase (%)' 
        },
        labels: {
            format: '{value} %' 
        }
    },
     tooltip: {
      shared: true,
      useHTML: true,
      formatter: function () {
        const index = this.points[0].point.index; // posisi user
        const total = totalUserDye[index];           // ambil total dari array PHP
        let tooltip = `<b>${this.key}</b><br/>`;

        this.points.forEach(p => {
          tooltip += `${p.series.name}: <b>${p.y.toFixed(2)} %</b><br/>`;
        });

        tooltip += `<b>Total: ${total}</b>`;
        return tooltip;
      }
    },
    plotOptions: {
        column: {
            pointPadding: 0,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f} %', 
                style: {
                    fontSize: '10px'
                }
            }
        }
    },
    
    series: [{
        name: '% Oke',
        data: <?= $js_dye_data_p_ok; ?>
    }, {
        name: '% Tidak Oke',
        data: <?= $js_dye_data_p_tok; ?>
    }]
});
</script>

<script type="text/javascript">
  const totalUserSebelum = <?= $js_sebelum_total_user; ?>; 
Highcharts.chart('container-grafik-setting-sebelum', {
    chart: {
        type: 'column'
    },
    
    title: {
        text: 'Laporan Performa Resep Setting'
    },
    xAxis: {
        categories: <?= $js_sebelum_categories; ?>,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Persentase (%)' 
        },
        labels: {
            format: '{value} %' 
        }
    },
     tooltip: {
      shared: true,
      useHTML: true,
      formatter: function () {
        const index = this.points[0].point.index; // posisi user
        const total = totalUserSebelum[index];           // ambil total dari array PHP
        let tooltip = `<b>${this.key}</b><br/>`;

        this.points.forEach(p => {
          tooltip += `${p.series.name}: <b>${p.y.toFixed(2)} %</b><br/>`;
        });

        tooltip += `<b>Total: ${total}</b>`;
        return tooltip;
      }
    },
    plotOptions: {
        column: {
            pointPadding: 0,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f} %', 
                style: {
                    fontSize: '10px'
                }
            }
        }
    },
    
    series: [{
        name: '% Oke',
        data: <?= $js_sebelum_data_p_ok; ?>
    }, {
        name: '% Tidak Oke',
        data: <?= $js_sebelum_data_p_tok; ?>
    }]
});
</script>