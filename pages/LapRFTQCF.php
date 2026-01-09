<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Harian QCF</title>

</head>
<body>
<?php
$Awal	= isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : '';
$JamAwal	= isset($_POST['tawal']) ? $_POST['tawal'] : '23:01';
$JamAkhir	= isset($_POST['takhir']) ? $_POST['takhir'] : '23:00';
$formatAwal = !empty($JamAwal) ? $Awal . ' ' . $JamAwal : $Awal;
$formatAkhir = !empty($JamAkhir) ? $Akhir . ' ' . $JamAkhir : $Akhir;
$obj_awal = new DateTime($Awal);
$obj_akhir = new DateTime($Akhir);

// $formatAwal2mth = date('Y-m-01 H:i', strtotime($formatAwal. ' -2 month'));
$Awal2mth = date('Y-m-01', strtotime($Awal. ' -2 month'));
$Akhir2mth = date('Y-m-01', strtotime($Awal2mth. ' +1 month'));
$Akhir2mth1 = date('Y-m-t', strtotime($Awal. ' -2 month'));

$Awal1mth = date('Y-m-01', strtotime($Awal. ' -1 month'));
$Akhir1mth = date('Y-m-01', strtotime($Awal1mth. ' +1 month'));
$Akhir1mth1 = date('Y-m-t', strtotime($Awal. ' -1 month'));

$formatAwal1mth = $Awal1mth . ' ' . '23:01';
$formatAkhir1mth = $Akhir1mth1 . ' ' . '23:00';
$formatAwal2mth = $Awal2mth . ' ' . '23:01';
$formatAkhir2mth = $Akhir2mth1 . ' ' . '23:00';
// echo $formatAwal2mth;

$Awal_Sebelum1mth = date('Y-m-d', strtotime($Awal1mth . ' -1 day'));
$Awal_Sebelum2mth = date('Y-m-d', strtotime($Awal2mth . ' -1 day'));
$Awal_Sebelum = date('Y-m-d', strtotime($Awal . ' -1 day'));
$Akhir_Sebelum = date('Y-m-d', strtotime($Akhir . ' -1 day'));
$Awalad1 = date('Y-m-d', strtotime($Awal . ' +1 day'));
$Akhirad1 = date('Y-m-d', strtotime($Akhir . ' +1 day'));

$formatAwal1mth1 = $Awal_Sebelum1mth . ' ' . '23:01';
$formatAwal2mth1 = $Awal_Sebelum2mth . ' ' . '23:01';

$view_tgl = '';
$view2mth = '';
$view1mth = '';
$nama_bulan_singkat = [
    1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR',
    5 => 'MEI', 6 => 'JUN', 7 => 'JUL', 8 => 'AGU',
    9 => 'SEP', 10 => 'OKT', 11 => 'NOV', 12 => 'DES'
];

$view2mth = $nama_bulan_singkat[date('n', strtotime($Awal2mth))] . '`' . date('y', strtotime($Awal2mth));
$view1mth = $nama_bulan_singkat[date('n', strtotime($Awal1mth))] . '`' . date('y', strtotime($Awal1mth));

// Ambil komponen tanggal (d = hari dengan 0, n = bulan tanpa 0, Y = tahun)
$hari_awal = $obj_awal->format('d');
$bulan_awal_int = (int)$obj_awal->format('n');
$tahun_awal = $obj_awal->format('Y');

$hari_akhir = $obj_akhir->format('d');
$bulan_akhir_int = (int)$obj_akhir->format('n');
$tahun_akhir = $obj_akhir->format('Y');


if ($Awal == $Akhir) {
    $view_tgl = $hari_awal . ' ' . $nama_bulan_singkat[$bulan_awal_int] . ' ' . $tahun_awal;
} elseif ($bulan_awal_int == $bulan_akhir_int && $tahun_awal == $tahun_akhir) {
    $view_tgl = $nama_bulan_singkat[$bulan_awal_int] . ' (' . $hari_awal . '-' . $hari_akhir . ') ' . $tahun_awal;
} elseif ($tahun_awal == $tahun_akhir) {
    $view_tgl = $hari_awal . ' ' . $nama_bulan_singkat[$bulan_awal_int] . ' - ' . $hari_akhir . ' ' . $nama_bulan_singkat[$bulan_akhir_int] . ' ' . $tahun_awal;
} else {
    $view_tgl = $hari_awal . ' ' . $nama_bulan_singkat[$bulan_awal_int] . ' ' . $tahun_awal . ' - ' . $hari_akhir . ' ' . $nama_bulan_singkat[$bulan_akhir_int] . ' ' . $tahun_akhir;
}

// echo $formatAwal;
// echo $formatAkhir;
// $Awal	= date('Y-m-d', strtotime($Akhir . ' -1 day'));

// Untuk Variablenya:
  $GREIGE_PERBAIKAN = $GREIGE_PERBAIKAN1 = $GREIGE_PERBAIKAN2 = 0;
  $GKAIN_RMP = $GKAIN_RMP1 = $GKAIN_RMP2 = $NCP_RMP = $NCP_RMP1 = $NCP_RMP2 = $DISP_NCP_RMP = $DISP_NCP_RMP1 = $DISP_NCP_RMP2 = 0;
  $GKAIN_LAB = $GKAIN_LAB1 = $GKAIN_LAB2 = $NCP_LAB = $NCP_LAB1 = $NCP_LAB2 = $DISP_NCP_LAB = $DISP_NCP_LAB1 = $DISP_NCP_LAB2 = $GPROSES_LAB = $GPROSES_LAB1 = $GPROSES_LAB2 = $DISP_GPROSES_LAB = $DISP_GPROSES_LAB1 = $DISP_GPROSES_LAB2 = 0;
  $GKAIN_DYE = $GKAIN_DYE1 = $GKAIN_DYE2 = $NCP_DYE = $NCP_DYE1 = $NCP_DYE2 = $DISP_NCP_DYE = $DISP_NCP_DYE1 = $DISP_NCP_DYE2 = $GPROSES_DYE = $GPROSES_DYE1 = $GPROSES_DYE2 = $DISP_GPROSES_DYE = $DISP_GPROSES_DYE1 = $DISP_GPROSES_DYE2 = $TBASAH_DYE = $TBASAH_DYE1 = $TBASAH_DYE2 = $DISP_TBASAH_DYE = $DISP_TBASAH_DYE1 = $DISP_TBASAH_DYE2 = 0;
  $GKAIN_CQA = $GKAIN_CQA1 = $GKAIN_CQA2 = $NCP_CQA = $NCP_CQA1 = $NCP_CQA2 = $DISP_NCP_CQA = $DISP_NCP_CQA1 = $DISP_NCP_CQA2 = $GPROSES_CQA = $GPROSES_CQA1 = $GPROSES_CQA2 = $DISP_GPROSES_CQA = $DISP_GPROSES_CQA1 = $DISP_GPROSES_CQA2 = $TBASAH_CQA = $TBASAH_CQA1 = $TBASAH_CQA2 = $DISP_TBASAH_CQA = $DISP_TBASAH_CQA1 = $DISP_TBASAH_CQA2 = 0;
  $GKAIN_KNT = $GKAIN_KNT1 = $GKAIN_KNT2 = $NCP_KNT = $NCP_KNT1 = $NCP_KNT2 = $DISP_NCP_KNT = $DISP_NCP_KNT1 = $DISP_NCP_KNT2 = 0;
  $GKAIN_FIN = $GKAIN_FIN1 = $GKAIN_FIN2 = $NCP_FIN = $NCP_FIN1 = $NCP_FIN2 = $DISP_NCP_FIN = $DISP_NCP_FIN1 = $DISP_NCP_FIN2 = 0;
  $GKAIN_BRS = $GKAIN_BRS1 = $GKAIN_BRS2 = $NCP_BRS = $NCP_BRS1 = $NCP_BRS2 = $DISP_NCP_BRS = $DISP_NCP_BRS1 = $DISP_NCP_BRS2 = 0;
  
  $QTY_KNT = $QTY_KNT1 = $QTY_KNT2 = 0;

// Global variable
?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"> Filter Laporan RFT QCF</h3>
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
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="awal" type="date" class="form-control pull-right" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off" required/>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-clock-o"></i> </div>
            <input name="tawal" type="time" class="form-control pull-right" placeholder="Tanggal Awal" value="<?php echo $JamAwal; ?>" autocomplete="off" readonly/>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-2">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="akhir" type="date" class="form-control pull-right" placeholder="Tanggal Akhir" value="<?php echo $Akhir;  ?>" autocomplete="off" required/>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-clock-o"></i> </div>
            <input name="takhir" type="time" class="form-control pull-right" placeholder="Tanggal Akhir" value="<?php echo $JamAkhir;  ?>" autocomplete="off" readonly/>
          </div>
        </div>
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
<?php if(!empty($_POST['awal'])&&!empty($_POST['akhir'])):
// Perbaikan Greige
  function greige_perbaikan($proses, $bruto)
  {
    global $GREIGE_PERBAIKAN;

    $listGreigePerbaikan = [
        'Celup Greige',
        'Celup Perbaikan DYE',
        'Celup Perbaikan FIN',
        'Levelling',
        'Mudain-LAB',
        'Pelunturan',
        'Reduction Clear (R/C)',
        'Soaping'
    ];

    if (in_array($proses, $listGreigePerbaikan, true)) {
        $GREIGE_PERBAIKAN += $bruto;
    }
  }

  function greige_perbaikan1($proses, $bruto)
  {
    global $GREIGE_PERBAIKAN1;

    $listGreigePerbaikan = [
        'Celup Greige',
        'Celup Perbaikan DYE',
        'Celup Perbaikan FIN',
        'Levelling',
        'Mudain-LAB',
        'Pelunturan',
        'Reduction Clear (R/C)',
        'Soaping'
    ];

    if (in_array($proses, $listGreigePerbaikan, true)) {
        $GREIGE_PERBAIKAN1 += $bruto;
    }
  }

  function greige_perbaikan2($proses, $bruto)
  {
    global $GREIGE_PERBAIKAN2;

    $listGreigePerbaikan = [
        'Celup Greige',
        'Celup Perbaikan DYE',
        'Celup Perbaikan FIN',
        'Levelling',
        'Mudain-LAB',
        'Pelunturan',
        'Reduction Clear (R/C)',
        'Soaping'
    ];

    if (in_array($proses, $listGreigePerbaikan, true)) {
        $GREIGE_PERBAIKAN2 += $bruto;
    }
  }
// End Perbaikan

// Tolak Basah
  function tbasah($proses, $bruto)
  {
    global $TBASAH_DYE, $TBASAH_CQA;

    if ($proses === 'TOLAK BASAH LUNTUR') {
        $TBASAH_DYE += $bruto;
    } else if ($proses === 'TOLAK BASAH BEDA WARNA') {
        $TBASAH_CQA += $bruto;
    } else if($proses === 'TOLAK BASAH BEDA WARNA + LUNTUR'){
      $div = $bruto / 2;
      $TBASAH_DYE += $div;
      $TBASAH_CQA += $div;
    }
  }

  function tbasah1($proses, $bruto)
  {
    global $TBASAH_DYE1, $TBASAH_CQA1;

    if ($proses === 'TOLAK BASAH LUNTUR') {
        $TBASAH_DYE1 += $bruto;
    } else if ($proses === 'TOLAK BASAH BEDA WARNA') {
        $TBASAH_CQA1 += $bruto;
    } else if($proses === 'TOLAK BASAH BEDA WARNA + LUNTUR'){
      $div_1 = $bruto / 2;
      $TBASAH_DYE1 += $div_1;
      $TBASAH_CQA1 += $div_1;
    }
  }

  function tbasah2($proses, $bruto)
  {
    global $TBASAH_DYE2, $TBASAH_CQA2;

    if ($proses === 'TOLAK BASAH LUNTUR') {
        $TBASAH_DYE2 += $bruto;
    } else if ($proses === 'TOLAK BASAH BEDA WARNA') {
        $TBASAH_CQA2 += $bruto;
    } else if($proses === 'TOLAK BASAH BEDA WARNA + LUNTUR'){
      $div_2 = $bruto / 2;
      $TBASAH_DYE2 += $div_2;
      $TBASAH_CQA2 += $div_2;
    }
  }

  function disp_tbasah($proses, $status, $bruto)
  {
    global $DISP_TBASAH_DYE, $DISP_TBASAH_CQA;

    if ($proses === 'TOLAK BASAH LUNTUR' && $status !== 'Disposisi') {
        $DISP_TBASAH_DYE += $bruto;
    } else if ($proses === 'TOLAK BASAH BEDA WARNA' && $status !== 'Disposisi') {
        $DISP_TBASAH_CQA += $bruto;
    } else if($proses === 'TOLAK BASAH BEDA WARNA + LUNTUR' && $status !== 'Disposisi'){
      $div = $bruto / 2;
      $DISP_TBASAH_DYE += $div;
      $DISP_TBASAH_CQA += $div;
    }
  }

  function disp_tbasah1($proses, $status, $bruto)
  {
    global $DISP_TBASAH_DYE1, $DISP_TBASAH_CQA1;

    if ($proses === 'TOLAK BASAH LUNTUR' && $status !== 'Disposisi') {
        $DISP_TBASAH_DYE1 += $bruto;
    } else if ($proses === 'TOLAK BASAH BEDA WARNA' && $status !== 'Disposisi') {
        $DISP_TBASAH_CQA1 += $bruto;
    } else if($proses === 'TOLAK BASAH BEDA WARNA + LUNTUR' && $status !== 'Disposisi'){
      $div_1 = $bruto / 2;
      $DISP_TBASAH_DYE1 += $div_1;
      $DISP_TBASAH_CQA1 += $div_1;
    }
  }

  function disp_tbasah2($proses, $status, $bruto)
  {
    global $DISP_TBASAH_DYE2, $DISP_TBASAH_CQA2;

    if ($proses === 'TOLAK BASAH LUNTUR' && $status !== 'Disposisi') {
        $DISP_TBASAH_DYE2 += $bruto;
    } else if ($proses === 'TOLAK BASAH BEDA WARNA' && $status !== 'Disposisi') {
        $DISP_TBASAH_CQA2 += $bruto;
    } else if($proses === 'TOLAK BASAH BEDA WARNA + LUNTUR' && $status !== 'Disposisi'){
      $div_2 = $bruto / 2;
      $DISP_TBASAH_DYE2 += $div_2;
      $DISP_TBASAH_CQA2 += $div_2;
    }
  }
// End TB

// Gproses
  function gproses($proses, $dept, $bruto)
  {
      global $GPROSES_LAB, $GPROSES_DYE, $GPROSES_CQA;

      if ($proses === 'Gagal Proses') {
          $departments = array_map('trim', explode(',', $dept));

          foreach ($departments as $d) {
              if ($d === 'LAB') {
                  $GPROSES_LAB += (float)$bruto;
              } elseif ($d === 'CQA') {
                  $GPROSES_CQA += (float)$bruto;
              } elseif ($d === 'DYE') {
                  $GPROSES_DYE += (float)$bruto;
              }
          }
      }
  }

  function gproses1($proses, $dept, $bruto)
  {
      global $GPROSES_LAB1, $GPROSES_DYE1, $GPROSES_CQA1;

      if ($proses === 'Gagal Proses') {
          $departments = array_map('trim', explode(',', $dept));

          foreach ($departments as $d) {
              if ($d === 'LAB') {
                  $GPROSES_LAB1 += (float)$bruto;
              } elseif ($d === 'CQA') {
                  $GPROSES_CQA1 += (float)$bruto;
              } elseif ($d === 'DYE') {
                  $GPROSES_DYE1 += (float)$bruto;
              }
          }
      }
  }

  function gproses2($proses, $dept, $bruto)
  {
      global $GPROSES_LAB2, $GPROSES_DYE2, $GPROSES_CQA2;

      if ($proses === 'Gagal Proses') {
          $departments = array_map('trim', explode(',', $dept));

          foreach ($departments as $d) {
              if ($d === 'LAB') {
                  $GPROSES_LAB2 += (float)$bruto;
              } elseif ($d === 'CQA') {
                  $GPROSES_CQA2 += (float)$bruto;
              } elseif ($d === 'DYE') {
                  $GPROSES_DYE2 += (float)$bruto;
              }
          }
      }
  }

  function disp_gproses($proses, $status, $dept, $bruto)
  {
      global $DISP_GPROSES_LAB, $DISP_GPROSES_DYE, $DISP_GPROSES_CQA;
      if($status !== 'Disposisi'){
        if ($proses === 'Gagal Proses') {
            $departments = array_map('trim', explode(',', $dept));
            foreach ($departments as $d) {
                if ($d === 'LAB') {
                    $DISP_GPROSES_LAB += (float)$bruto;
                } elseif ($d === 'CQA') {
                    $DISP_GPROSES_CQA += (float)$bruto;
                } elseif ($d === 'DYE') {
                    $DISP_GPROSES_DYE += (float)$bruto;
                }
            }
        }
      }
  }

  function disp_gproses1($proses, $status, $dept, $bruto)
  {
      global $DISP_GPROSES_LAB1, $DISP_GPROSES_DYE1, $DISP_GPROSES_CQA1;
      if($status !== 'Disposisi'){
        if ($proses === 'Gagal Proses') {
            $departments = array_map('trim', explode(',', $dept));
  
            foreach ($departments as $d) {
                if ($d === 'LAB') {
                    $DISP_GPROSES_LAB1 += (float)$bruto;
                } elseif ($d === 'CQA') {
                    $DISP_GPROSES_CQA1 += (float)$bruto;
                } elseif ($d === 'DYE') {
                    $DISP_GPROSES_DYE1 += (float)$bruto;
                }
            }
        }
      }
  }

  function disp_gproses2($proses, $status, $dept, $bruto)
  {
      global $DISP_GPROSES_LAB2, $DISP_GPROSES_DYE2, $DISP_GPROSES_CQA2;
      if($status !== 'Disposisi'){
        if ($proses === 'Gagal Proses') {
            $departments = array_map('trim', explode(',', $dept));
  
            foreach ($departments as $d) {
                if ($d === 'LAB') {
                    $DISP_GPROSES_LAB2 += (float)$bruto;
                } elseif ($d === 'CQA') {
                    $DISP_GPROSES_CQA2 += (float)$bruto;
                } elseif ($d === 'DYE') {
                    $DISP_GPROSES_DYE2 += (float)$bruto;
                }
            }
        }
      }
  }
// End Gproses

// NCP
  function ncp($dept, $qty)
  {
    global $NCP_RMP,$NCP_LAB,$NCP_DYE,$NCP_CQA,$NCP_KNT,$NCP_FIN,$NCP_BRS;

    if ($dept === 'RMP') {
        $NCP_RMP += (float)$qty;
    } elseif ($dept === 'LAB') {
        $NCP_LAB += (float)$qty;
    } elseif ($dept === 'DYE') {
        $NCP_DYE += (float)$qty;
    } elseif ($dept === 'CQA') {
        $NCP_CQA += (float)$qty;
    } elseif ($dept === 'KNT') {
        $NCP_KNT += (float)$qty;
    } elseif ($dept === 'FIN') {
        $NCP_FIN += (float)$qty;
    } elseif ($dept === 'BRS') {
        $NCP_BRS += (float)$qty;
    }
  }

  function ncp1($dept, $qty)
  {
    global $NCP_RMP1,$NCP_LAB1,$NCP_DYE1,$NCP_CQA1,$NCP_KNT1,$NCP_FIN1,$NCP_BRS1;

    if ($dept === 'RMP') {
        $NCP_RMP1 += (float)$qty;
    } elseif ($dept === 'LAB') {
        $NCP_LAB1 += (float)$qty;
    } elseif ($dept === 'DYE') {
        $NCP_DYE1 += (float)$qty;
    } elseif ($dept === 'CQA') {
        $NCP_CQA1 += (float)$qty;
    } elseif ($dept === 'KNT') {
        $NCP_KNT1 += (float)$qty;
    } elseif ($dept === 'FIN') {
        $NCP_FIN1 += (float)$qty;
    } elseif ($dept === 'BRS') {
        $NCP_BRS1 += (float)$qty;
    }
  }

  function ncp2($dept, $qty)
  {
    global $NCP_RMP2,$NCP_LAB2,$NCP_DYE2,$NCP_CQA2,$NCP_KNT2,$NCP_FIN2,$NCP_BRS2;

    if ($dept === 'RMP') {
        $NCP_RMP2 += (float)$qty;
    } elseif ($dept === 'LAB') {
        $NCP_LAB2 += (float)$qty;
    } elseif ($dept === 'DYE') {
        $NCP_DYE2 += (float)$qty;
    } elseif ($dept === 'CQA') {
        $NCP_CQA2 += (float)$qty;
    } elseif ($dept === 'KNT') {
        $NCP_KNT2 += (float)$qty;
    } elseif ($dept === 'FIN') {
        $NCP_FIN2 += (float)$qty;
    } elseif ($dept === 'BRS') {
        $NCP_BRS2 += (float)$qty;
    }
  }
// End NCP

// Disposisi
  function disp($dept, $status, $qty)
  {
    global 
    $DISP_NCP_RMP,
    $DISP_NCP_LAB,
    $DISP_NCP_DYE,
    $DISP_NCP_CQA,
    $DISP_NCP_KNT,
    $DISP_NCP_FIN,
    $DISP_NCP_BRS;

    if ($dept === 'RMP' && $status === 'Disposisi') {
        $DISP_NCP_RMP += (float)$qty;
    } elseif ($dept === 'LAB' && $status === 'Disposisi') {
        $DISP_NCP_LAB += (float)$qty;
    } elseif ($dept === 'DYE' && $status === 'Disposisi') {
        $DISP_NCP_DYE += (float)$qty;
    } elseif ($dept === 'CQA' && $status === 'Disposisi') {
        $DISP_NCP_CQA += (float)$qty;
    } elseif ($dept === 'KNT' && $status === 'Disposisi') {
        $DISP_NCP_KNT += (float)$qty;
    } elseif ($dept === 'FIN' && $status === 'Disposisi') {
        $DISP_NCP_FIN += (float)$qty;
    } elseif ($dept === 'BRS' && $status === 'Disposisi') {
        $DISP_NCP_BRS += (float)$qty;
    }
  }

  function disp1($dept, $status, $qty)
  {
    global 
    $DISP_NCP_RMP1,
    $DISP_NCP_LAB1,
    $DISP_NCP_DYE1,
    $DISP_NCP_CQA1,
    $DISP_NCP_KNT1,
    $DISP_NCP_FIN1,
    $DISP_NCP_BRS1;

    if ($dept === 'RMP' && $status === 'Disposisi') {
        $DISP_NCP_RMP1 += (float)$qty;
    } elseif ($dept === 'LAB' && $status === 'Disposisi') {
        $DISP_NCP_LAB1 += (float)$qty;
    } elseif ($dept === 'DYE' && $status === 'Disposisi') {
        $DISP_NCP_DYE1 += (float)$qty;
    } elseif ($dept === 'CQA' && $status === 'Disposisi') {
        $DISP_NCP_CQA1 += (float)$qty;
    } elseif ($dept === 'KNT' && $status === 'Disposisi') {
        $DISP_NCP_KNT1 += (float)$qty;
    } elseif ($dept === 'FIN' && $status === 'Disposisi') {
        $DISP_NCP_FIN1 += (float)$qty;
    } elseif ($dept === 'BRS' && $status === 'Disposisi') {
        $DISP_NCP_BRS1 += (float)$qty;
    }
  }

  function disp2($dept, $status, $qty)
  {
    global 
    $DISP_NCP_RMP2,
    $DISP_NCP_LAB2,
    $DISP_NCP_DYE2,
    $DISP_NCP_CQA2,
    $DISP_NCP_KNT2,
    $DISP_NCP_FIN2,
    $DISP_NCP_BRS2;

    if ($dept === 'RMP' && $status === 'Disposisi') {
        $DISP_NCP_RMP2 += (float)$qty;
    } elseif ($dept === 'LAB' && $status === 'Disposisi') {
        $DISP_NCP_LAB2 += (float)$qty;
    } elseif ($dept === 'DYE' && $status === 'Disposisi') {
        $DISP_NCP_DYE2 += (float)$qty;
    } elseif ($dept === 'CQA' && $status === 'Disposisi') {
        $DISP_NCP_CQA2 += (float)$qty;
    } elseif ($dept === 'KNT' && $status === 'Disposisi') {
        $DISP_NCP_KNT2 += (float)$qty;
    } elseif ($dept === 'FIN' && $status === 'Disposisi') {
        $DISP_NCP_FIN2 += (float)$qty;
    } elseif ($dept === 'BRS' && $status === 'Disposisi') {
        $DISP_NCP_BRS2 += (float)$qty;
    }
  }
// End Disp

// GKAIN
  function gkain($proses1, $proses2, $proses3, $proses4, $proses5,
                $qty1, $qty2, $qty3, $qty4, $qty5, )
  {
      global $GKAIN_RMP, $GKAIN_LAB, $GKAIN_DYE, $GKAIN_CQA,
            $GKAIN_KNT, $GKAIN_FIN, $GKAIN_BRS;
      $pairs = [
          [$proses1, $qty1],
          [$proses2, $qty2],
          [$proses3, $qty3],
          [$proses4, $qty4],
          [$proses5, $qty5],
      ];

      foreach ($pairs as [$proses, $qty]) {
          if ($proses === 'RMP') {
              $GKAIN_RMP += (float)$qty;
          } elseif ($proses === 'LAB') {
              $GKAIN_LAB += (float)$qty;
          } elseif ($proses === 'DYE') {
              $GKAIN_DYE += (float)$qty;
          } elseif ($proses === 'CQA') {
              $GKAIN_CQA += (float)$qty;
          } elseif ($proses === 'KNT') {
              $GKAIN_KNT += (float)$qty;
          } elseif ($proses === 'FIN') {
              $GKAIN_FIN += (float)$qty;
          } elseif ($proses === 'BRS') {
              $GKAIN_BRS += (float)$qty;
          }
      }
  }

  function gkain1($proses1, $proses2, $proses3, $proses4, $proses5,
                $qty1, $qty2, $qty3, $qty4, $qty5, )
  {
      global $GKAIN_RMP1, $GKAIN_LAB1, $GKAIN_DYE1, $GKAIN_CQA1,
            $GKAIN_KNT1, $GKAIN_FIN1, $GKAIN_BRS1;
      $pairs = [
          [$proses1, $qty1],
          [$proses2, $qty2],
          [$proses3, $qty3],
          [$proses4, $qty4],
          [$proses5, $qty5],
      ];

      foreach ($pairs as [$proses, $qty]) {
          if ($proses === 'RMP') {
              $GKAIN_RMP1 += (float)$qty;
          } elseif ($proses === 'LAB') {
              $GKAIN_LAB1 += (float)$qty;
          } elseif ($proses === 'DYE') {
              $GKAIN_DYE1 += (float)$qty;
          } elseif ($proses === 'CQA') {
              $GKAIN_CQA1 += (float)$qty;
          } elseif ($proses === 'KNT') {
              $GKAIN_KNT1 += (float)$qty;
          } elseif ($proses === 'FIN') {
              $GKAIN_FIN1 += (float)$qty;
          } elseif ($proses === 'BRS') {
              $GKAIN_BRS1 += (float)$qty;
          }
      }
  }

  function gkain2($proses1, $proses2, $proses3, $proses4, $proses5,
                $qty1, $qty2, $qty3, $qty4, $qty5, )
  {
      global $GKAIN_RMP2, $GKAIN_LAB2, $GKAIN_DYE2, $GKAIN_CQA2,
            $GKAIN_KNT2, $GKAIN_FIN2, $GKAIN_BRS2;
      $pairs = [
          [$proses1, $qty1],
          [$proses2, $qty2],
          [$proses3, $qty3],
          [$proses4, $qty4],
          [$proses5, $qty5],
      ];

      foreach ($pairs as [$proses, $qty]) {
          if ($proses === 'RMP') {
              $GKAIN_RMP2 += (float)$qty;
          } elseif ($proses === 'LAB') {
              $GKAIN_LAB2 += (float)$qty;
          } elseif ($proses === 'DYE') {
              $GKAIN_DYE2 += (float)$qty;
          } elseif ($proses === 'CQA') {
              $GKAIN_CQA2 += (float)$qty;
          } elseif ($proses === 'KNT') {
              $GKAIN_KNT2 += (float)$qty;
          } elseif ($proses === 'FIN') {
              $GKAIN_FIN2 += (float)$qty;
          } elseif ($proses === 'BRS') {
              $GKAIN_BRS2 += (float)$qty;
          }
      }
  }
// End GKAIN
// End Variable

// DYE
    $where_dye = "DATE_FORMAT(c.tgl_update, '%Y-%m-%d %H:%i') BETWEEN '$formatAwal' AND '$formatAkhir'";
            $q_dye =  "SELECT x.*, 
                            a.no_mesin as mc 
                        FROM tbl_mesin a
                            LEFT JOIN
                            (SELECT
                            c.bruto / (LENGTH(k.dept_penyebab) - LENGTH(REPLACE(k.dept_penyebab, ',', '')) + 1) as bruto_bagi,
                            k.analisa_penyebab,
                            k.dept_penyebab,
                            k.keterangan_gagal_proses,
                            k.accresep,
                            k.accresep2,
                            p.tindak_lanjut,
                            p.hasil_tindak_lanjut,
                            p.pemberi_instruksi,
                            p.keterangan as keterangan_tindak_lanjut,
                            p.tindakan as tindakan_tindak_lanjut,
                            a.ket,	if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))) as lama_proses,
                            a.status as sts,
                            TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%H') as jam,
                            TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%i') as menit,
                            a.proses as proses,
                            b.proses as schedule_proses,
                            b.buyer,
                            b.langganan,
                            b.no_order,
                            b.no_mesin,
                            b.warna,
                            b.dyestuff,	
                            b.kapasitas,
                            b.loading,
                            a.resep,
                            CASE
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'D' THEN 'Dark'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'H' THEN 'Heater'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'L' THEN 'Light'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'M' THEN 'Medium'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'S' THEN 'Dark'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'W' THEN 'White'
                            END AS kategori_warna,
                            c.l_r,
                            c.rol,
                            c.bruto,
                            a.g_shift as shft,
                            a.k_resep,
                            a.status,
                            a.proses_point,
                            b.nokk,
                            b.lebar,
                            c.carry_over,
                            b.po,	
                            b.kk_kestabilan,
                            b.kk_normal,
                            c.air_awal,
                            a.air_akhir,
                            c.nodemand,
                            c.operator,
                            a.id as idhslclp,   
                            b.id as idshedule,
                            c.id as idmontemp,
                            a.status_proses,
                            COALESCE(a.point2, b.target) as point2
                          FROM
                            tbl_schedule b
                              LEFT JOIN  tbl_montemp c ON c.id_schedule = b.id
                              LEFT JOIN tbl_hasilcelup a ON a.id_montemp=c.id	
                            left join penyelesaian_gagalproses p on
                                p.id_schedule = b.id
                                and p.id_hasil_celup = a.id
                                and p.id_montemp = c.id
                            left join tbl_keterangan_gagalproses k on
                              k.id_hasil_celup = a.id
                              and k.id_montemp = c.id
                          WHERE
                            $where_dye
                            )x ON (a.no_mesin=x.no_mesin) ORDER BY a.no_mesin";
                            // echo $q_dye;
              $sql_dye = mysqli_query($con,$q_dye);
        if ($sql_dye && mysqli_num_rows($sql_dye) > 0) {
            while ($row = mysqli_fetch_assoc($sql_dye)) {
                $bruto = (float)$row['bruto'];
                $bruto_bagi = (float)$row['bruto_bagi'];
                greige_perbaikan($row['proses'], $bruto);
                gproses($row['status'],$row['dept_penyebab'], $bruto_bagi);
                disp_gproses($row['status'],$row['tindakan_tindak_lanjut'],$row['dept_penyebab'], $bruto_bagi);
            }
        }
        // echo $formatAkhir1mth;
    $where_dye1 = "DATE_FORMAT(c.tgl_update, '%Y-%m-%d %H:%i') BETWEEN '$formatAwal1mth1' AND '$formatAkhir1mth'";
            $q_dye1 =  "SELECT x.*, 
                            a.no_mesin as mc 
                        FROM tbl_mesin a
                            LEFT JOIN
                            (SELECT
                            c.bruto / (LENGTH(k.dept_penyebab) - LENGTH(REPLACE(k.dept_penyebab, ',', '')) + 1) as bruto_bagi,
                            k.analisa_penyebab,
                            k.dept_penyebab,
                            k.keterangan_gagal_proses,
                            k.accresep,
                            k.accresep2,
                            p.tindak_lanjut,
                            p.hasil_tindak_lanjut,
                            p.pemberi_instruksi,
                            p.keterangan as keterangan_tindak_lanjut,
                            p.tindakan as tindakan_tindak_lanjut,
                            a.ket,	if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))) as lama_proses,
                            a.status as sts,
                            TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%H') as jam,
                            TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%i') as menit,
                            a.proses as proses,
                            b.proses as schedule_proses,
                            b.buyer,
                            b.langganan,
                            b.no_order,
                            b.no_mesin,
                            b.warna,
                            b.dyestuff,	
                            b.kapasitas,
                            b.loading,
                            a.resep,
                            CASE
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'D' THEN 'Dark'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'H' THEN 'Heater'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'L' THEN 'Light'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'M' THEN 'Medium'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'S' THEN 'Dark'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'W' THEN 'White'
                            END AS kategori_warna,
                            c.l_r,
                            c.rol,
                            c.bruto,
                            a.g_shift as shft,
                            a.k_resep,
                            a.status,
                            a.proses_point,
                            b.nokk,
                            b.lebar,
                            c.carry_over,
                            b.po,	
                            b.kk_kestabilan,
                            b.kk_normal,
                            c.air_awal,
                            a.air_akhir,
                            c.nodemand,
                            c.operator,
                            a.id as idhslclp,   
                            b.id as idshedule,
                            c.id as idmontemp,
                            a.status_proses,
                            COALESCE(a.point2, b.target) as point2
                          FROM
                            tbl_schedule b
                              LEFT JOIN  tbl_montemp c ON c.id_schedule = b.id
                              LEFT JOIN tbl_hasilcelup a ON a.id_montemp=c.id	
                            left join penyelesaian_gagalproses p on
                                p.id_schedule = b.id
                                and p.id_hasil_celup = a.id
                                and p.id_montemp = c.id
                            left join tbl_keterangan_gagalproses k on
                              k.id_hasil_celup = a.id
                              and k.id_montemp = c.id
                          WHERE
                            $where_dye1
                            )x ON (a.no_mesin=x.no_mesin) ORDER BY a.no_mesin";
              $sql_dye1 = mysqli_query($con,$q_dye1);
        if ($sql_dye1 && mysqli_num_rows($sql_dye1) > 0) {
            while ($row1 = mysqli_fetch_assoc($sql_dye1)) {
                $bruto1 = (float)$row1['bruto'];
                $bruto1_bagi = (float)$row1['bruto_bagi'];
                greige_perbaikan1($row1['proses'], $bruto1);
                gproses1($row1['status'],$row1['dept_penyebab'], $bruto1_bagi);
                disp_gproses1($row1['status'], $row1['tindakan_tindak_lanjut'], $row1['dept_penyebab'], $bruto1_bagi);
            }
        }

    $where_dye2 = "DATE_FORMAT(c.tgl_update, '%Y-%m-%d %H:%i') BETWEEN '$formatAwal2mth1' AND '$formatAkhir2mth'";
            $q_dye2 =  "SELECT x.*, 
                            a.no_mesin as mc 
                        FROM tbl_mesin a
                            LEFT JOIN
                            (SELECT
                            c.bruto / (LENGTH(k.dept_penyebab) - LENGTH(REPLACE(k.dept_penyebab, ',', '')) + 1) as bruto_bagi,
                            k.analisa_penyebab,
                            k.dept_penyebab,
                            k.keterangan_gagal_proses,
                            k.accresep,
                            k.accresep2,
                            p.tindak_lanjut,
                            p.hasil_tindak_lanjut,
                            p.pemberi_instruksi,
                            p.keterangan as keterangan_tindak_lanjut,
                            p.tindakan as tindakan_tindak_lanjut,
                            a.ket,	if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))) as lama_proses,
                            a.status as sts,
                            TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%H') as jam,
                            TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%i') as menit,
                            a.proses as proses,
                            b.proses as schedule_proses,
                            b.buyer,
                            b.langganan,
                            b.no_order,
                            b.no_mesin,
                            b.warna,
                            b.dyestuff,	
                            b.kapasitas,
                            b.loading,
                            a.resep,
                            CASE
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'D' THEN 'Dark'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'H' THEN 'Heater'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'L' THEN 'Light'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'M' THEN 'Medium'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'S' THEN 'Dark'
                              WHEN SUBSTR(b.kategori_warna, 1,1) = 'W' THEN 'White'
                            END AS kategori_warna,
                            c.l_r,
                            c.rol,
                            c.bruto,
                            a.g_shift as shft,
                            a.k_resep,
                            a.status,
                            a.proses_point,
                            b.nokk,
                            b.lebar,
                            c.carry_over,
                            b.po,	
                            b.kk_kestabilan,
                            b.kk_normal,
                            c.air_awal,
                            a.air_akhir,
                            c.nodemand,
                            c.operator,
                            a.id as idhslclp,   
                            b.id as idshedule,
                            c.id as idmontemp,
                            a.status_proses,
                            COALESCE(a.point2, b.target) as point2
                          FROM
                            tbl_schedule b
                              LEFT JOIN  tbl_montemp c ON c.id_schedule = b.id
                              LEFT JOIN tbl_hasilcelup a ON a.id_montemp=c.id	
                            left join penyelesaian_gagalproses p on
                                p.id_schedule = b.id
                                and p.id_hasil_celup = a.id
                                and p.id_montemp = c.id
                            left join tbl_keterangan_gagalproses k on
                              k.id_hasil_celup = a.id
                              and k.id_montemp = c.id
                          WHERE
                            $where_dye2
                            )x ON (a.no_mesin=x.no_mesin) ORDER BY a.no_mesin";
              // echo $q_dye2;
              $sql_dye2 = mysqli_query($con,$q_dye2);
        if ($sql_dye2 && mysqli_num_rows($sql_dye2) > 0) {
            while ($row2 = mysqli_fetch_assoc($sql_dye2)) {
                $bruto2 = (float)$row2['bruto'];
                $bruto2_bagi = (float)$row2['bruto_bagi'];
                greige_perbaikan2($row2['proses'], $bruto2);
                gproses2($row2['status'],$row2['dept_penyebab'], $bruto2_bagi);
                disp_gproses2($row2['status'],$row2['tindakan_tindak_lanjut'],$row2['dept_penyebab'], $bruto2_bagi);
            }
        }
// End Dye

// QTY KNT
  $where_knt = "AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-07:00:00' AND '$Akhirad1-07:00:00'";
  $q_knt =  "SELECT
                SUM(WEIGHTNET) AS QTY
              FROM
                ELEMENTSINSPECTION
              LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON
                PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE
              WHERE
                ELEMENTITEMTYPECODE = 'KGF'
              $where_knt";
  $stmt1   = db2_exec($conn2,$q_knt, array('cursor'=>DB2_SCROLLABLE));
  $row_knt = db2_fetch_assoc($stmt1);

  $where_knt1 = "AND INSPECTIONSTARTDATETIME BETWEEN '$Awal1mth-07:00:00' AND '$Akhir1mth-07:00:00'";
  $q_knt1 =  "SELECT
                SUM(WEIGHTNET) AS QTY
              FROM
                ELEMENTSINSPECTION
              LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON
                PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE
              WHERE
                ELEMENTITEMTYPECODE = 'KGF'
              $where_knt1";
  $stmt2   = db2_exec($conn2,$q_knt1, array('cursor'=>DB2_SCROLLABLE));
  $row_knt1 = db2_fetch_assoc($stmt2);

  $where_knt2 = "AND INSPECTIONSTARTDATETIME BETWEEN '$Awal2mth-07:00:00' AND '$Akhir2mth-07:00:00'";
  $q_knt2 =  "SELECT
                SUM(WEIGHTNET) AS QTY
              FROM
                ELEMENTSINSPECTION
              LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON
                PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE
              WHERE
                ELEMENTITEMTYPECODE = 'KGF'
              $where_knt2";
  $stmt3   = db2_exec($conn2,$q_knt2, array('cursor'=>DB2_SCROLLABLE));
  $row_knt2 = db2_fetch_assoc($stmt3);
// End Knt

// QTY Fin
  $where_fin = "CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal_Sebelum 23:01' AND '$Akhir 23:00'";
  $q_fin = "SELECT
              SUM(CASE WHEN a.kondisi_kain2 = 'KERING' AND ((a.proses IN ('Compact (Normal)')) OR (a.proses IN ('Finishing Jadi (Normal)') AND a.no_mesin LIKE 'P3ST%') OR (a.proses IN('Oven Stenter (Normal)', 'Oven Kering (Normal)')and a.no_mesin = 'P3DR101')) THEN a.qty ELSE 0 END) AS QTY
            FROM
              db_finishing.tbl_produksi a
            LEFT JOIN db_finishing.tbl_no_mesin b ON
              a.no_mesin = b.no_mesin
            where
            $where_fin";
  // echo $where_fin;
  $stmt_fin   = sqlsrv_query($conS,$q_fin);
  $row_fin = sqlsrv_fetch_array($stmt_fin, SQLSRV_FETCH_ASSOC);

  $where_fin1 = "CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal_Sebelum1mth 23:01' AND '$Akhir1mth1 23:00'";
  $q_fin1 = "SELECT
              SUM(CASE WHEN a.kondisi_kain2 = 'KERING' AND ((a.proses IN ('Compact (Normal)')) OR (a.proses IN ('Finishing Jadi (Normal)') AND a.no_mesin LIKE 'P3ST%') OR (a.proses IN('Oven Stenter (Normal)', 'Oven Kering (Normal)')and a.no_mesin = 'P3DR101')) THEN a.qty ELSE 0 END) AS QTY
            FROM
              db_finishing.tbl_produksi a
            LEFT JOIN db_finishing.tbl_no_mesin b ON
              a.no_mesin = b.no_mesin
            where
            $where_fin1";
  // echo $where_fin;
  $stmt_fin1   = sqlsrv_query($conS,$q_fin1);
  $row_fin1 = sqlsrv_fetch_array($stmt_fin1, SQLSRV_FETCH_ASSOC);

  $where_fin2 = "CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN '$Awal_Sebelum2mth 23:01' AND '$Akhir2mth1 23:00'";
  $q_fin2 = "SELECT
              SUM(CASE WHEN a.kondisi_kain2 = 'KERING' AND ((a.proses IN ('Compact (Normal)')) OR (a.proses IN ('Finishing Jadi (Normal)') AND a.no_mesin LIKE 'P3ST%') OR (a.proses IN('Oven Stenter (Normal)', 'Oven Kering (Normal)')and a.no_mesin = 'P3DR101')) THEN a.qty ELSE 0 END) AS QTY
            FROM
              db_finishing.tbl_produksi a
            LEFT JOIN db_finishing.tbl_no_mesin b ON
              a.no_mesin = b.no_mesin
            where
            $where_fin2";
  // echo $q_fin2;
  $stmt_fin2   = sqlsrv_query($conS,$q_fin2);
  $row_fin2 = sqlsrv_fetch_array($stmt_fin2, SQLSRV_FETCH_ASSOC);
// End Fin

// QTY BRS
  $where_brs = " tp.tgl_buat between '$Awal_Sebelum 23:01' and '$Akhir 23:00'";
  $q_brs ="SELECT (garuk_fleece + garuk_ap + peach + airo + polish )as QTY from (SELECT
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08','09') AND nama_mesin IN ('Garuk A','Garuk B','Garuk C','Garuk D','Garuk E','Garuk F') and proses = 'GARUK ANTI PILLING (Normal)' THEN qty ELSE 0 END) AS garuk_ap,
                                            GROUP_CONCAT(DISTINCT CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' THEN TRIM(nodemand) ELSE NULL END) AS demand_garuk_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08','09') AND nama_mesin IN ('Garuk A','Garuk B','Garuk C','Garuk D','Garuk E','Garuk F') and  proses in('GARUK FLEECE (Normal)', 'GARUK SLIGHT BRUSH (Normal)', 'GARUK SLIGHTLY BRUS (Normal)', 'GARUK GREIGE (Normal)', 'GARUK BANTU - DYG (Bantu)', 'GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS garuk_fleece,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('POTONG BULU FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK SLIGHT BRUSH (Normal)','GARUK SLIGHTLY BRUS (Normal)') THEN qty ELSE 0 END) AS potong_bulu_fleece,
                                            SUM(CASE WHEN no_mesin IN ('01') AND nama_mesin IN ('Sisir') and proses IN ('SISIR ANTI PILLING (Normal)','SISIR BANTU (FIN) (Bantu)','SISIR LAIN-LAIN (Bantu)','GARUK ANTI PILLING (Normal)') THEN qty ELSE 0 END) AS sisir_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('POTONG BULU ANTI PILLING (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','ANTI PILLING (Khusus)','ANTI PILLING NORMAL (Normal)','ANTI PILLING (Normal)','ANTI PILLING BIASA (Normal)') THEN qty ELSE 0 END) AS pbulu_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04') AND nama_mesin IN ('Anti Pilling') and proses IN ('ANTI PILLING (Khusus)','ANTI PILLING (Normal)','ANTI PILLING NORMAL (Normal)','ANTI PILLING BIASA (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','POTONG BULU ANTI PILLING (Normal)','PEACH SKIN (Normal)','POTONG BULU PEACH SKIN (Normal)','POTONG BULU FLEECE (Normal)') THEN qty ELSE 0 END) AS oven_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06') AND nama_mesin IN ('Peach Skin') and  proses IN ('PEACH SKIN (Normal)','PEACHSKIN GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)','PEACH SKIN BANTU - FIN (Bantu)','POTONG BULU PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS peach,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('POTONG BULU PEACH SKIN (Normal)','PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS pb_peach,
                                            SUM(CASE WHEN no_mesin IN ('01','02') AND nama_mesin IN ('Airo') and proses = 'AIRO (Normal)' THEN qty ELSE 0 END) AS airo,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('Potong Bulu (Bantu)','POTONG BULU 07 (Bantu)','POTONG BULU LAIN-LAIN (Bantu)','POTONG BULU LAIN-LAIN (Khusus)','POTONG BULU BACK BANTU-DYEING (Bantu)','POTONG BULU BACK BANTU-FIN (Bantu)','POTONG BULU BACK TAS BANTU (Bantu)','POTONG BULU FACE BANTU-DYEING (Bantu)','POTONG BULU FACE BANTU-FIN (Bantu)','POTONG BULU FACE BANTU-TAS (Bantu)','POTONG BULU FACE TAS BANTU (Bantu)','POTONG BULU GREIGE (Bantu)','POTONG BULU GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)',
                                                                     'PEACH SKIN BANTU - FIN (Bantu)','GARUK BANTU - DYG (Bantu)','GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS pb_lain,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04') AND nama_mesin IN ('Anti Pilling') and proses IN ('ANTI PILLING BANTU - DYE (Bantu)',
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
                                            SUM(CASE WHEN no_mesin IN ('01') AND nama_mesin IN ('Wet Sueding') and proses IN ('WET SUEDING (Normal)','WET SUEDING FINISHED BACK (Normal)',
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
                                            count(distinct nodemand) as total_kk
                                        FROM
                                            tbl_produksi tp
                                        WHERE  
                                        $where_brs) t";
    // echo $q_brs;
    $stmt_brs = mysqli_query($conb, $q_brs);
    $row_brs = mysqli_fetch_assoc($stmt_brs);

    $where_brs1 = " tp.tgl_buat between '$Awal_Sebelum1mth 23:01' and '$Akhir1mth1 23:00'";
  $q_brs1 ="SELECT (garuk_fleece + garuk_ap + peach + airo + polish )as QTY from (SELECT
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08','09') AND nama_mesin IN ('Garuk A','Garuk B','Garuk C','Garuk D','Garuk E','Garuk F') and proses = 'GARUK ANTI PILLING (Normal)' THEN qty ELSE 0 END) AS garuk_ap,
                                            GROUP_CONCAT(DISTINCT CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' THEN TRIM(nodemand) ELSE NULL END) AS demand_garuk_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08','09') AND nama_mesin IN ('Garuk A','Garuk B','Garuk C','Garuk D','Garuk E','Garuk F') and  proses in('GARUK FLEECE (Normal)', 'GARUK SLIGHT BRUSH (Normal)', 'GARUK SLIGHTLY BRUS (Normal)', 'GARUK GREIGE (Normal)', 'GARUK BANTU - DYG (Bantu)', 'GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS garuk_fleece,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('POTONG BULU FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK SLIGHT BRUSH (Normal)','GARUK SLIGHTLY BRUS (Normal)') THEN qty ELSE 0 END) AS potong_bulu_fleece,
                                            SUM(CASE WHEN no_mesin IN ('01') AND nama_mesin IN ('Sisir') and proses IN ('SISIR ANTI PILLING (Normal)','SISIR BANTU (FIN) (Bantu)','SISIR LAIN-LAIN (Bantu)','GARUK ANTI PILLING (Normal)') THEN qty ELSE 0 END) AS sisir_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('POTONG BULU ANTI PILLING (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','ANTI PILLING (Khusus)','ANTI PILLING NORMAL (Normal)','ANTI PILLING (Normal)','ANTI PILLING BIASA (Normal)') THEN qty ELSE 0 END) AS pbulu_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04') AND nama_mesin IN ('Anti Pilling') and proses IN ('ANTI PILLING (Khusus)','ANTI PILLING (Normal)','ANTI PILLING NORMAL (Normal)','ANTI PILLING BIASA (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','POTONG BULU ANTI PILLING (Normal)','PEACH SKIN (Normal)','POTONG BULU PEACH SKIN (Normal)','POTONG BULU FLEECE (Normal)') THEN qty ELSE 0 END) AS oven_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06') AND nama_mesin IN ('Peach Skin') and  proses IN ('PEACH SKIN (Normal)','PEACHSKIN GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)','PEACH SKIN BANTU - FIN (Bantu)','POTONG BULU PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS peach,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('POTONG BULU PEACH SKIN (Normal)','PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS pb_peach,
                                            SUM(CASE WHEN no_mesin IN ('01','02') AND nama_mesin IN ('Airo') and proses = 'AIRO (Normal)' THEN qty ELSE 0 END) AS airo,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('Potong Bulu (Bantu)','POTONG BULU 07 (Bantu)','POTONG BULU LAIN-LAIN (Bantu)','POTONG BULU LAIN-LAIN (Khusus)','POTONG BULU BACK BANTU-DYEING (Bantu)','POTONG BULU BACK BANTU-FIN (Bantu)','POTONG BULU BACK TAS BANTU (Bantu)','POTONG BULU FACE BANTU-DYEING (Bantu)','POTONG BULU FACE BANTU-FIN (Bantu)','POTONG BULU FACE BANTU-TAS (Bantu)','POTONG BULU FACE TAS BANTU (Bantu)','POTONG BULU GREIGE (Bantu)','POTONG BULU GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)',
                                                                     'PEACH SKIN BANTU - FIN (Bantu)','GARUK BANTU - DYG (Bantu)','GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS pb_lain,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04') AND nama_mesin IN ('Anti Pilling') and proses IN ('ANTI PILLING BANTU - DYE (Bantu)',
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
                                            SUM(CASE WHEN no_mesin IN ('01') AND nama_mesin IN ('Wet Sueding') and proses IN ('WET SUEDING (Normal)','WET SUEDING FINISHED BACK (Normal)',
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
                                            count(distinct nodemand) as total_kk
                                        FROM
                                            tbl_produksi tp
                                        WHERE 
                                        $where_brs1) t";
    $stmt_brs1 = mysqli_query($conb, $q_brs1);
    $row_brs1 = mysqli_fetch_assoc($stmt_brs1);

    $where_brs2 = " tp.tgl_buat between '$Awal_Sebelum2mth 23:01' and '$Akhir2mth1 23:00'";
  $q_brs2 ="SELECT (garuk_fleece + garuk_ap + peach + airo + polish )as QTY from (SELECT
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08','09') AND nama_mesin IN ('Garuk A','Garuk B','Garuk C','Garuk D','Garuk E','Garuk F') and proses = 'GARUK ANTI PILLING (Normal)' THEN qty ELSE 0 END) AS garuk_ap,
                                            GROUP_CONCAT(DISTINCT CASE WHEN proses = 'GARUK ANTI PILLING (Normal)' AND nodemand IS NOT NULL AND TRIM(nodemand) != '' THEN TRIM(nodemand) ELSE NULL END) AS demand_garuk_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08','09') AND nama_mesin IN ('Garuk A','Garuk B','Garuk C','Garuk D','Garuk E','Garuk F') and  proses in('GARUK FLEECE (Normal)', 'GARUK SLIGHT BRUSH (Normal)', 'GARUK SLIGHTLY BRUS (Normal)', 'GARUK GREIGE (Normal)', 'GARUK BANTU - DYG (Bantu)', 'GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS garuk_fleece,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('POTONG BULU FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK FLEECE (Normal)','GARUK SLIGHT BRUSH (Normal)','GARUK SLIGHTLY BRUS (Normal)') THEN qty ELSE 0 END) AS potong_bulu_fleece,
                                            SUM(CASE WHEN no_mesin IN ('01') AND nama_mesin IN ('Sisir') and proses IN ('SISIR ANTI PILLING (Normal)','SISIR BANTU (FIN) (Bantu)','SISIR LAIN-LAIN (Bantu)','GARUK ANTI PILLING (Normal)') THEN qty ELSE 0 END) AS sisir_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('POTONG BULU ANTI PILLING (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','ANTI PILLING (Khusus)','ANTI PILLING NORMAL (Normal)','ANTI PILLING (Normal)','ANTI PILLING BIASA (Normal)') THEN qty ELSE 0 END) AS pbulu_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04') AND nama_mesin IN ('Anti Pilling') and proses IN ('ANTI PILLING (Khusus)','ANTI PILLING (Normal)','ANTI PILLING NORMAL (Normal)','ANTI PILLING BIASA (Normal)','GARUK ANTI PILLING (Normal)','SISIR ANTI PILLING (Normal)','POTONG BULU ANTI PILLING (Normal)','PEACH SKIN (Normal)','POTONG BULU PEACH SKIN (Normal)','POTONG BULU FLEECE (Normal)') THEN qty ELSE 0 END) AS oven_ap,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06') AND nama_mesin IN ('Peach Skin') and  proses IN ('PEACH SKIN (Normal)','PEACHSKIN GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)','PEACH SKIN BANTU - FIN (Bantu)','POTONG BULU PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS peach,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('POTONG BULU PEACH SKIN (Normal)','PEACH SKIN (Normal)') THEN qty ELSE 0 END) AS pb_peach,
                                            SUM(CASE WHEN no_mesin IN ('01','02') AND nama_mesin IN ('Airo') and proses = 'AIRO (Normal)' THEN qty ELSE 0 END) AS airo,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04','05','06','07','08') AND nama_mesin IN ('Potong Bulu') and  proses IN ('Potong Bulu (Bantu)','POTONG BULU 07 (Bantu)','POTONG BULU LAIN-LAIN (Bantu)','POTONG BULU LAIN-LAIN (Khusus)','POTONG BULU BACK BANTU-DYEING (Bantu)','POTONG BULU BACK BANTU-FIN (Bantu)','POTONG BULU BACK TAS BANTU (Bantu)','POTONG BULU FACE BANTU-DYEING (Bantu)','POTONG BULU FACE BANTU-FIN (Bantu)','POTONG BULU FACE BANTU-TAS (Bantu)','POTONG BULU FACE TAS BANTU (Bantu)','POTONG BULU GREIGE (Bantu)','POTONG BULU GREIGE (Normal)','PEACH BANTU TAS (Bantu)','PEACH SKIN (Bantu)','PEACH SKIN BANTU - DYE (Bantu)',
                                                                     'PEACH SKIN BANTU - FIN (Bantu)','GARUK BANTU - DYG (Bantu)','GARUK BANTU - FIN (Bantu)','GARUK GREIGE (Bantu)','GARUK PERBAIKAN DYG (Bantu)') THEN qty ELSE 0 END) AS pb_lain,
                                            SUM(CASE WHEN no_mesin IN ('01','02','03','04') AND nama_mesin IN ('Anti Pilling') and proses IN ('ANTI PILLING BANTU - DYE (Bantu)',
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
                                            SUM(CASE WHEN no_mesin IN ('01') AND nama_mesin IN ('Wet Sueding') and proses IN ('WET SUEDING (Normal)','WET SUEDING FINISHED BACK (Normal)',
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
                                            count(distinct nodemand) as total_kk
                                        FROM
                                            tbl_produksi tp
                                        WHERE 
                                        $where_brs2) t";
    $stmt_brs2 = mysqli_query($conb, $q_brs2);
    $row_brs2 = mysqli_fetch_assoc($stmt_brs2);

// End BRS

// G KAIN
    $where_gkain = " DATE_FORMAT(t.tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal' AND '$Akhir'";
    $q_gkain = "SELECT
                  t.t_jawab as dept1,
                  b.kg_bruto*t.persen/100 as kg_1,
                  t.t_jawab1 as dept2,
                  b.kg_bruto*t.persen1/100 as kg_2,
                  t.t_jawab2 as dept3,
                  b.kg_bruto*t.persen2/100 as kg_3,
                  t.t_jawab3 as dept4,
                  b.kg_bruto*t.persen3/100 as kg_4,
                  t.t_jawab4 as dept5,
                  b.kg_bruto*t.persen4/100 as kg_5
                FROM
                  tbl_gantikain t
                LEFT JOIN (
                        SELECT *
                        FROM (
                            SELECT b.*,
                                  row_number() OVER (partition by b.id_nsp order by b.no_bon ASC) as rn
                            FROM tbl_bonkain b
                        ) x
                        where rn = 1
                      ) b on b.id_nsp = t.id
                WHERE 
                  $where_gkain
                  ";
      // echo $q_gkain;
      $stmt_gkain = mysqli_query($cona,$q_gkain);
      if ($stmt_gkain && mysqli_num_rows($stmt_gkain) > 0) {
              while ($row_gkain = mysqli_fetch_assoc($stmt_gkain)) {
                  $bruto1 = (float)$row_gkain['kg_1'];
                  $bruto2 = (float)$row_gkain['kg_2'];
                  $bruto3 = (float)$row_gkain['kg_3'];
                  $bruto4 = (float)$row_gkain['kg_4'];
                  $bruto5 = (float)$row_gkain['kg_5'];
                  gkain($row_gkain['dept1'], $row_gkain['dept2'], $row_gkain['dept3'], $row_gkain['dept4'],$row_gkain['dept5'], $bruto1, $bruto2, $bruto3, $bruto4, $bruto5);
              }
          }

    $where_gkain1 = " DATE_FORMAT(t.tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal1mth' AND '$Akhir1mth1'";
    $q_gkain1 = "SELECT
                  t.t_jawab as dept1,
                  b.kg_bruto*t.persen/100 as kg_1,
                  t.t_jawab1 as dept2,
                  b.kg_bruto*t.persen1/100 as kg_2,
                  t.t_jawab2 as dept3,
                  b.kg_bruto*t.persen2/100 as kg_3,
                  t.t_jawab3 as dept4,
                  b.kg_bruto*t.persen3/100 as kg_4,
                  t.t_jawab4 as dept5,
                  b.kg_bruto*t.persen4/100 as kg_5
                FROM
                  tbl_gantikain t
                LEFT JOIN (
                        SELECT *
                        FROM (
                            SELECT b.*,
                                  row_number() OVER (partition by b.id_nsp order by b.no_bon ASC) as rn
                            FROM tbl_bonkain b
                        ) x
                        where rn = 1
                      ) b on b.id_nsp = t.id
                WHERE 
                  $where_gkain1
                  ";
      $stmt_gkain1 = mysqli_query($cona,$q_gkain1);
      if ($stmt_gkain1 && mysqli_num_rows($stmt_gkain1) > 0) {
              while ($row_gkain1 = mysqli_fetch_assoc($stmt_gkain1)) {
                  $bruto11 = (float)$row_gkain1['kg_1'];
                  $bruto12 = (float)$row_gkain1['kg_2'];
                  $bruto13 = (float)$row_gkain1['kg_3'];
                  $bruto14 = (float)$row_gkain1['kg_4'];
                  $bruto15 = (float)$row_gkain1['kg_5'];
                  gkain1($row_gkain1['dept1'], $row_gkain1['dept2'], $row_gkain1['dept3'], $row_gkain1['dept4'],$row_gkain1['dept5'], $bruto11, $bruto12, $bruto13, $bruto14, $bruto15);
              }
          }

    $where_gkain2 = " DATE_FORMAT(t.tgl_buat, '%Y-%m-%d' ) BETWEEN '$Awal2mth' AND '$Akhir2mth1'";
    $q_gkain2 = "SELECT
                  t.t_jawab as dept1,
                  b.kg_bruto*t.persen/100 as kg_1,
                  t.t_jawab1 as dept2,
                  b.kg_bruto*t.persen1/100 as kg_2,
                  t.t_jawab2 as dept3,
                  b.kg_bruto*t.persen2/100 as kg_3,
                  t.t_jawab3 as dept4,
                  b.kg_bruto*t.persen3/100 as kg_4,
                  t.t_jawab4 as dept5,
                  b.kg_bruto*t.persen4/100 as kg_5
                FROM
                  tbl_gantikain t
                LEFT JOIN (
                        SELECT *
                        FROM (
                            SELECT b.*,
                                  row_number() OVER (partition by b.id_nsp order by b.no_bon ASC) as rn
                            FROM tbl_bonkain b
                        ) x
                        where rn = 1
                      ) b on b.id_nsp = t.id
                WHERE 
                  $where_gkain2
                  ";
      $stmt_gkain2 = mysqli_query($cona,$q_gkain2);
      if ($stmt_gkain2 && mysqli_num_rows($stmt_gkain2) > 0) {
              while ($row_gkain2 = mysqli_fetch_assoc($stmt_gkain2)) {
                  $bruto21 = (float)$row_gkain2['kg_1'];
                  $bruto22 = (float)$row_gkain2['kg_2'];
                  $bruto23 = (float)$row_gkain2['kg_3'];
                  $bruto24 = (float)$row_gkain2['kg_4'];
                  $bruto25 = (float)$row_gkain2['kg_5'];
                  gkain2($row_gkain2['dept1'], $row_gkain2['dept2'], $row_gkain2['dept3'], $row_gkain2['dept4'],$row_gkain2['dept5'], $bruto21, $bruto22, $bruto23, $bruto24, $bruto25);
              }
          }
// End G KAIN

// Query NCP
    $where_ncp = " AND tgl_buat between '$Awal' and '$Akhir'";
    $q_ncp = "SELECT
                  *,
                  DATEDIFF(tgl_rencana, DATE_FORMAT(now(), '%Y-%m-%d')) as lama,
                  DATEDIFF(DATE_FORMAT(now(), '%Y-%m-%d'), tgl_rencana) as delay
                from
                  tbl_ncp_qcf_now
                where
                  status in ('Belum OK', 'OK', 'BS', 'Cancel', 'Disposisi')
                  AND ncp_hitung = 'ya'
                  $where_ncp    
                order by
                  id asc";
    $stmt_ncp = mysqli_query($cond,$q_ncp);
    if ($stmt_ncp && mysqli_num_rows($stmt_ncp) > 0) {
            while ($row_ncp = mysqli_fetch_assoc($stmt_ncp)) {
                $bruto_ncp = (float)$row_ncp['berat'];
                ncp($row_ncp['dept'], $bruto_ncp);
                disp($row_ncp['dept'], $row_ncp['status'], $bruto_ncp);
            }
        }
      
    $where_ncp1 = " AND tgl_buat between '$Awal1mth' and '$Akhir1mth1'";
    $q_ncp1 = "SELECT
                  *,
                  DATEDIFF(tgl_rencana, DATE_FORMAT(now(), '%Y-%m-%d')) as lama,
                  DATEDIFF(DATE_FORMAT(now(), '%Y-%m-%d'), tgl_rencana) as delay
                from
                  tbl_ncp_qcf_now
                where
                  status in ('Belum OK', 'OK', 'BS', 'Cancel', 'Disposisi')
                  AND ncp_hitung = 'ya'
                  $where_ncp1    
                order by
                  id asc";
    $stmt_ncp1 = mysqli_query($cond,$q_ncp1);
    if ($stmt_ncp1 && mysqli_num_rows($stmt_ncp1) > 0) {
            while ($row_ncp1 = mysqli_fetch_assoc($stmt_ncp1)) {
                $bruto_ncp1 = (float)$row_ncp1['berat'];
                ncp1($row_ncp1['dept'], $bruto_ncp1);
                disp1($row_ncp1['dept'], $row_ncp1['status'], $bruto_ncp1);
            }
        }

    $where_ncp2 = " AND tgl_buat between '$Awal2mth' and '$Akhir2mth1'";
    $q_ncp2 = "SELECT
                  *,
                  DATEDIFF(tgl_rencana, DATE_FORMAT(now(), '%Y-%m-%d')) as lama,
                  DATEDIFF(DATE_FORMAT(now(), '%Y-%m-%d'), tgl_rencana) as delay
                from
                  tbl_ncp_qcf_now
                where
                  status in ('Belum OK', 'OK', 'BS', 'Cancel', 'Disposisi')
                  AND ncp_hitung = 'ya'
                  $where_ncp2    
                order by
                  id asc";
    $stmt_ncp2 = mysqli_query($cond,$q_ncp2);
    if ($stmt_ncp2 && mysqli_num_rows($stmt_ncp2) > 0) {
            while ($row_ncp2 = mysqli_fetch_assoc($stmt_ncp2)) {
                $bruto_ncp2 = (float)$row_ncp2['berat'];
                ncp2($row_ncp2['dept'], $bruto_ncp2);
                disp2($row_ncp2['dept'], $row_ncp2['status'], $bruto_ncp2);
            }
        }
// End NCP
  
// Tolak Basah
  $where_tb = "AND tgl_update BETWEEN '$formatAwal' AND '$formatAkhir'";
  $q_tb = "SELECT 
              t.*,
              p.hasil_tindak_lanjut,
              p.tindak_lanjut,
              p.tindakan,
              p.pemberi_instruksi,
              p.keterangan  
            FROM 
              tbl_cocok_warna_dye t
            LEFT JOIN penyelesaian_tolakbasah p
              ON t.id = p.id_cocok_warna 
            WHERE 
              t.status_warna LIKE '%TOLAK BASAH%'
              $where_tb
            ORDER BY 
              t.id DESC";
  // echo $q_tb;
  $stmt_tb = mysqli_query($cond,$q_tb);
  if ($stmt_tb && mysqli_num_rows($stmt_tb) > 0) {
            while ($row_tb = mysqli_fetch_assoc($stmt_tb)) {
                $bruto_tb = (float)$row_tb['bruto'];
                tbasah($row_tb['status_warna'], $bruto_tb);
                disp_tbasah($row_tb['status_warna'],$row_tb['tindakan'], $bruto_tb);
                // disp($row_ncp['dept'], $row_ncp['status'], $bruto_ncp);
            }
        }

  $where_tb1 = "AND tgl_update BETWEEN '$formatAwal1mth1' AND '$formatAkhir1mth'";
  $q_tb1 = "SELECT 
              t.*,
              p.hasil_tindak_lanjut,
              p.tindak_lanjut,
              p.tindakan,
              p.pemberi_instruksi,
              p.keterangan  
            FROM 
              tbl_cocok_warna_dye t
            LEFT JOIN penyelesaian_tolakbasah p
              ON t.id = p.id_cocok_warna 
            WHERE 
              t.status_warna LIKE '%TOLAK BASAH%'
              $where_tb1
            ORDER BY 
              t.id DESC";
  $stmt_tb1 = mysqli_query($cond,$q_tb1);
  if ($stmt_tb1 && mysqli_num_rows($stmt_tb1) > 0) {
            while ($row_tb1 = mysqli_fetch_assoc($stmt_tb1)) {
                $bruto_tb1 = (float)$row_tb1['bruto'];
                tbasah1($row_tb1['status_warna'], $bruto_tb1);
                disp_tbasah1($row_tb1['status_warna'],$row_tb1['tindakan'], $bruto_tb1);
                // disp($row_ncp['dept'], $row_ncp['status'], $bruto_ncp);
            }
        }

  $where_tb2 = "AND tgl_update BETWEEN '$formatAwal2mth1' AND '$formatAkhir2mth'";
  $q_tb2 = "SELECT 
              t.*,
              p.hasil_tindak_lanjut,
              p.tindak_lanjut,
              p.tindakan,
              p.pemberi_instruksi,
              p.keterangan  
            FROM 
              tbl_cocok_warna_dye t
            LEFT JOIN penyelesaian_tolakbasah p
              ON t.id = p.id_cocok_warna 
            WHERE 
              t.status_warna LIKE '%TOLAK BASAH%'
              $where_tb2
            ORDER BY 
              t.id DESC";
  // echo $q_tb2;
  $stmt_tb2 = mysqli_query($cond,$q_tb2);
  if ($stmt_tb2 && mysqli_num_rows($stmt_tb2) > 0) {
            while ($row_tb2 = mysqli_fetch_assoc($stmt_tb2)) {
                $bruto_tb2 = (float)$row_tb2['bruto'];
                tbasah2($row_tb2['status_warna'], $bruto_tb2);
                disp_tbasah2($row_tb2['status_warna'],$row_tb2['tindakan'], $bruto_tb2);
                // disp($row_ncp['dept'], $row_ncp['status'], $bruto_ncp);
            }
        }
// End TB


// Calculation
  function safe_division($numerator, $denominator, $precision = 2) {
      if (empty($denominator) || $denominator == 0) {
          return 0;
      }
      return round(($numerator / $denominator) * 100, $precision);
  }
// RMP
  $DISP_NCP_CAL_RMP2  = $NCP_RMP2 - $DISP_NCP_RMP2;
  $DISP_NCP_CAL_RMP1  = $NCP_RMP1 - $DISP_NCP_RMP1;
  $DISP_NCP_CAL_RMP   = $NCP_RMP  - $DISP_NCP_RMP;

  $QR_RMP2 = safe_division(($GREIGE_PERBAIKAN2 - ($GKAIN_RMP2 + $NCP_RMP2)), $GREIGE_PERBAIKAN2);
  $QR_RMP1 = safe_division(($GREIGE_PERBAIKAN1 - ($GKAIN_RMP1 + $NCP_RMP1)), $GREIGE_PERBAIKAN1);
  $QR_RMP  = safe_division(($GREIGE_PERBAIKAN  - ($GKAIN_RMP  + $NCP_RMP)),  $GREIGE_PERBAIKAN);

  $DISP_QR_RMP2 = safe_division(($GREIGE_PERBAIKAN2 - ($GKAIN_RMP2 + $DISP_NCP_CAL_RMP2)), $GREIGE_PERBAIKAN2);
  $DISP_QR_RMP1 = safe_division(($GREIGE_PERBAIKAN1 - ($GKAIN_RMP1 + $DISP_NCP_CAL_RMP1)), $GREIGE_PERBAIKAN1);
  $DISP_QR_RMP  = safe_division(($GREIGE_PERBAIKAN  - ($GKAIN_RMP  + $DISP_NCP_CAL_RMP)),  $GREIGE_PERBAIKAN);


// LAB
  $NCP_CAL_LAB2  = ($NCP_LAB2+$GPROSES_LAB2);
  $NCP_CAL_LAB1  = ($NCP_LAB1+$GPROSES_LAB1);
  $NCP_CAL_LAB   = ($NCP_LAB+$GPROSES_LAB);

  $DISP_NCP_CAL_LAB2  = ($NCP_LAB2 - $DISP_NCP_LAB2 + $DISP_GPROSES_LAB2);
  $DISP_NCP_CAL_LAB1  = ($NCP_LAB1 - $DISP_NCP_LAB1 + $DISP_GPROSES_LAB1);
  $DISP_NCP_CAL_LAB   = ($NCP_LAB - $DISP_NCP_LAB + $DISP_GPROSES_LAB);

  $QR_LAB2 = safe_division(($GREIGE_PERBAIKAN2 - ($GKAIN_LAB2 + $NCP_CAL_LAB2)), $GREIGE_PERBAIKAN2);
  $QR_LAB1 = safe_division(($GREIGE_PERBAIKAN1 - ($GKAIN_LAB1 + $NCP_CAL_LAB1)), $GREIGE_PERBAIKAN1);
  $QR_LAB = safe_division(($GREIGE_PERBAIKAN - ($GKAIN_LAB + $NCP_CAL_LAB)), $GREIGE_PERBAIKAN);
  
  $DISP_QR_LAB2  = safe_division(($GREIGE_PERBAIKAN2  - ($GKAIN_LAB2  + $DISP_NCP_CAL_LAB2)),  $GREIGE_PERBAIKAN2);
  $DISP_QR_LAB1  = safe_division(($GREIGE_PERBAIKAN1  - ($GKAIN_LAB1  + $DISP_NCP_CAL_LAB1)),  $GREIGE_PERBAIKAN1);
  $DISP_QR_LAB  = safe_division(($GREIGE_PERBAIKAN  - ($GKAIN_LAB  + $DISP_NCP_CAL_LAB)),  $GREIGE_PERBAIKAN);

// DYE
  $NCP_CAL_DYE2  = ($NCP_DYE2+$TBASAH_DYE2+$GPROSES_DYE2);
  $NCP_CAL_DYE1  = ($NCP_DYE1+$TBASAH_DYE1+$GPROSES_DYE1);
  $NCP_CAL_DYE   = ($NCP_DYE+$TBASAH_DYE+$GPROSES_DYE);

  $DISP_NCP_CAL_DYE2  = ($NCP_DYE2 - $DISP_NCP_DYE2+$DISP_TBASAH_DYE2+$DISP_GPROSES_DYE2);
  $DISP_NCP_CAL_DYE1  = ($NCP_DYE1 - $DISP_NCP_DYE1+$DISP_TBASAH_DYE1+$DISP_GPROSES_DYE1);
  $DISP_NCP_CAL_DYE   = ($NCP_DYE - $DISP_NCP_DYE+$DISP_TBASAH_DYE+$DISP_GPROSES_DYE);

  $QR_DYE2 = safe_division(($GREIGE_PERBAIKAN2 - ($GKAIN_DYE2 + $NCP_CAL_DYE2)), $GREIGE_PERBAIKAN2);
  $QR_DYE1 = safe_division(($GREIGE_PERBAIKAN1 - ($GKAIN_DYE1 + $NCP_CAL_DYE1)), $GREIGE_PERBAIKAN1);
  $QR_DYE = safe_division(($GREIGE_PERBAIKAN - ($GKAIN_DYE + $NCP_CAL_DYE)), $GREIGE_PERBAIKAN);
  
  $DISP_QR_DYE2  = safe_division(($GREIGE_PERBAIKAN2  - ($GKAIN_DYE2  + $DISP_NCP_CAL_DYE2)),  $GREIGE_PERBAIKAN2);
  $DISP_QR_DYE1  = safe_division(($GREIGE_PERBAIKAN1  - ($GKAIN_DYE1  + $DISP_NCP_CAL_DYE1)),  $GREIGE_PERBAIKAN1);
  $DISP_QR_DYE  = safe_division(($GREIGE_PERBAIKAN  - ($GKAIN_DYE  + $DISP_NCP_CAL_DYE)),  $GREIGE_PERBAIKAN);

// CQA
  $NCP_CAL_CQA2  = ($NCP_CQA2+$TBASAH_CQA2+$GPROSES_CQA2);
  $NCP_CAL_CQA1  = ($NCP_CQA1+$TBASAH_CQA1+$GPROSES_CQA1);
  $NCP_CAL_CQA   = ($NCP_CQA+$TBASAH_CQA+$GPROSES_CQA);

  $DISP_NCP_CAL_CQA2  = ($NCP_CQA2 - $DISP_NCP_CQA2+$DISP_TBASAH_CQA2+$DISP_GPROSES_CQA2);
  $DISP_NCP_CAL_CQA1  = ($NCP_CQA1 - $DISP_NCP_CQA1+$DISP_TBASAH_CQA1+$DISP_GPROSES_CQA1);
  $DISP_NCP_CAL_CQA   = ($NCP_CQA - $DISP_NCP_CQA+$DISP_TBASAH_CQA+$DISP_GPROSES_CQA);

  $QR_CQA2 = safe_division(($GREIGE_PERBAIKAN2 - ($GKAIN_CQA2 + $NCP_CAL_CQA2)), $GREIGE_PERBAIKAN2);
  $QR_CQA1 = safe_division(($GREIGE_PERBAIKAN1 - ($GKAIN_CQA1 + $NCP_CAL_CQA1)), $GREIGE_PERBAIKAN1);
  $QR_CQA = safe_division(($GREIGE_PERBAIKAN - ($GKAIN_CQA + $NCP_CAL_CQA)), $GREIGE_PERBAIKAN);
  
  $DISP_QR_CQA2  = safe_division(($GREIGE_PERBAIKAN2  - ($GKAIN_CQA2  + $DISP_NCP_CAL_CQA2)),  $GREIGE_PERBAIKAN2);
  $DISP_QR_CQA1  = safe_division(($GREIGE_PERBAIKAN1  - ($GKAIN_CQA1  + $DISP_NCP_CAL_CQA1)),  $GREIGE_PERBAIKAN1);
  $DISP_QR_CQA  = safe_division(($GREIGE_PERBAIKAN  - ($GKAIN_CQA  + $DISP_NCP_CAL_CQA)),  $GREIGE_PERBAIKAN);

// KNT
  $DISP_NCP_CAL_KNT2  = $NCP_KNT2 - $DISP_NCP_KNT2;
  $DISP_NCP_CAL_KNT1  = $NCP_KNT1 - $DISP_NCP_KNT1;
  $DISP_NCP_CAL_KNT   = $NCP_KNT  - $DISP_NCP_KNT;

  $QR_KNT2 = safe_division(($row_knt2['QTY'] - ($GKAIN_KNT2 + $NCP_KNT2)), $row_knt2['QTY']);
  $QR_KNT1 = safe_division(($row_knt1['QTY'] - ($GKAIN_KNT1 + $NCP_KNT1)), $row_knt1['QTY']);
  $QR_KNT  = safe_division(($row_knt['QTY']  - ($GKAIN_KNT  + $NCP_KNT)),  $row_knt['QTY']);

  $DISP_QR_KNT2 = safe_division(($row_knt2['QTY'] - ($GKAIN_KNT2 + $DISP_NCP_CAL_KNT2)), $row_knt2['QTY']);
  $DISP_QR_KNT1 = safe_division(($row_knt1['QTY'] - ($GKAIN_KNT1 + $DISP_NCP_CAL_KNT1)), $row_knt1['QTY']);
  $DISP_QR_KNT  = safe_division(($row_knt['QTY']  - ($GKAIN_KNT  + $DISP_NCP_CAL_KNT)),  $row_knt['QTY']);

  
// FIN
  $DISP_NCP_CAL_FIN2  = $NCP_FIN2 - $DISP_NCP_FIN2;
  $DISP_NCP_CAL_FIN1  = $NCP_FIN1 - $DISP_NCP_FIN1;
  $DISP_NCP_CAL_FIN   = $NCP_FIN  - $DISP_NCP_FIN;

  $QR_FIN2 = safe_division(($row_fin2['QTY'] - ($GKAIN_FIN2 + $NCP_FIN2)), $row_fin2['QTY']);
  $QR_FIN1 = safe_division(($row_fin1['QTY'] - ($GKAIN_FIN1 + $NCP_FIN1)), $row_fin1['QTY']);
  $QR_FIN  = safe_division(($row_fin['QTY']  - ($GKAIN_FIN  + $NCP_FIN)),  $row_fin['QTY']);

  $DISP_QR_FIN2 = safe_division(($row_fin2['QTY'] - ($GKAIN_FIN2 + $DISP_NCP_CAL_FIN2)), $row_fin2['QTY']);
  $DISP_QR_FIN1 = safe_division(($row_fin1['QTY'] - ($GKAIN_FIN1 + $DISP_NCP_CAL_FIN1)), $row_fin1['QTY']);
  $DISP_QR_FIN  = safe_division(($row_fin['QTY']  - ($GKAIN_FIN  + $DISP_NCP_CAL_FIN)),  $row_fin['QTY']);

// BRS
  $DISP_NCP_CAL_BRS2  = $NCP_BRS2 - $DISP_NCP_BRS2;
  $DISP_NCP_CAL_BRS1  = $NCP_BRS1 - $DISP_NCP_BRS1;
  $DISP_NCP_CAL_BRS   = $NCP_BRS  - $DISP_NCP_BRS;

  $QR_BRS2 = safe_division(($row_brs2['QTY'] - ($GKAIN_BRS2 + $NCP_BRS2)), $row_brs2['QTY']);
  $QR_BRS1 = safe_division(($row_brs1['QTY'] - ($GKAIN_BRS1 + $NCP_BRS1)), $row_brs1['QTY']);
  $QR_BRS  = safe_division(($row_brs['QTY']  - ($GKAIN_BRS  + $NCP_BRS)),  $row_brs['QTY']);

  $DISP_QR_BRS2 = safe_division(($row_brs2['QTY'] - ($GKAIN_BRS2 + $DISP_NCP_CAL_BRS2)), $row_brs2['QTY']);
  $DISP_QR_BRS1 = safe_division(($row_brs1['QTY'] - ($GKAIN_BRS1 + $DISP_NCP_CAL_BRS1)), $row_brs1['QTY']);
  $DISP_QR_BRS  = safe_division(($row_brs['QTY']  - ($GKAIN_BRS  + $DISP_NCP_CAL_BRS)),  $row_brs['QTY']);

?>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
		<?php if($_POST['akhir']!="") {  ?>
		<!-- <a href="pages/cetak/cetak_lapharianfin.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>" class="btn btn-warning btn-sm pull-right" target="_blank"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</a> <br> <?php } ?> -->
        <h3 class="box-title">Data RFT QCF</h3><br>		  
        <?php if($_POST['akhir']!="") { ?><b>Periode: <?php echo $formatAwal." to ".$formatAkhir; ?></b>
		<?php } ?>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th colspan='2'><div align="center">RFT TREND</div></th>
            <th><div align="center"><?= $view2mth;?></div></th>
            <th><div align="center"><?= $view1mth;?></div></th>
            <th><div align="center"><?= $view_tgl;?></div></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th colspan='2'><div align="left">Production Quantity Dyeing (Grey) + Perbaikan</div></th>
            <th><div align="center"><?= $GREIGE_PERBAIKAN2;?></div></th>
            <th><div align="center"><?= $GREIGE_PERBAIKAN1;?></div></th>
            <th><div align="center"><?= $GREIGE_PERBAIKAN;?></div></th>
          </tr>
        <!-- Untuk Kolom RMP -->		
          <tr> 
            <td rowspan='5'  align="center">RMP</td>
            <td align="left">Internal Ganti Kain</td>
            <td><div align="center"><?= $GKAIN_RMP2;?></div></td>
            <td><div align="center"><?= $GKAIN_RMP1;?></div></td>
            <td><div align="center"><?= $GKAIN_RMP;?></div></td>
          </tr>
          <tr>
            <td align="left">NCP</td>
            <td><div align="center"><?= $NCP_RMP2;?></div></td>
            <td><div align="center"><?= $NCP_RMP1;?></div></td>
            <td><div align="center"><?= $NCP_RMP;?></div></td>
          </tr>
			    <tr>
            <td align="left">NCP (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $DISP_NCP_CAL_RMP2;?></div></td>
            <td><div align="center"><?= $DISP_NCP_CAL_RMP1;?></div></td>
            <td><div align="center"><?= $DISP_NCP_CAL_RMP;?></div></td>
          </tr> 
          <tr>
            <td align="left">Quality Rate %</td>
            <td><div align="center"><?= $QR_RMP2.'%';?></div></td>
            <td><div align="center"><?= $QR_RMP1.'%';?></div></td>
            <td><div align="center"><?= $QR_RMP.'%';?></div></td>
          </tr>
          <tr style="background-color:#00ff95ff">
            <td align="left">Quality Rate % (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $DISP_QR_RMP2.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_RMP1.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_RMP.'%';?></div></td>
          </tr>
        <!-- END Kolom RMP -->
        <!-- Untuk Kolom Lab -->
          <tr> 
            <td rowspan='5'  align="center">LAB</td>
            <td align="left">Internal Ganti Kain</td>
            <td><div align="center"><?= $GKAIN_LAB2;?></div></td>
            <td><div align="center"><?= $GKAIN_LAB1;?></div></td>
            <td><div align="center"><?= $GKAIN_LAB;?></div></td>
          </tr>
          <tr>
            <td align="left">NCP + Gagal Proses</td>
            <td><div align="center"><?= $NCP_LAB2 . '+' . $GPROSES_LAB2. ' = '. $NCP_CAL_LAB2 ;?></div></td>
            <td><div align="center"><?= $NCP_LAB1 . '+' . $GPROSES_LAB1. ' = '. $NCP_CAL_LAB1 ;?></div></td>
            <td><div align="center"><?= $NCP_LAB . '+' . $GPROSES_LAB. ' = '. $NCP_CAL_LAB  ;?></div></td>
          </tr>
			    <tr>
            <td align="left">NCP (Setelah Dikurangi Disposisi NCP + Gagal Proses)</td>
            <td><div align="center"><?= $NCP_LAB2 .' - '. $DISP_NCP_LAB2 . ' + '.  $DISP_GPROSES_LAB2.' = '. $DISP_NCP_CAL_LAB2;?></div></td>
            <td><div align="center"><?= $NCP_LAB1 .' - '. $DISP_NCP_LAB1 . ' + '.  $DISP_GPROSES_LAB1.' = '. $DISP_NCP_CAL_LAB1;?></div></td>
            <td><div align="center"><?= $NCP_LAB .' - '. $DISP_NCP_LAB . ' + '.  $DISP_GPROSES_LAB.' = '. $DISP_NCP_CAL_LAB ;?></div></td>
          </tr> 
          <tr>
            <td align="left">Quality Rate %</td>
            <td><div align="center"><?= $QR_LAB2.'%';?></div></td>
            <td><div align="center"><?= $QR_LAB1.'%';?></div></td>
            <td><div align="center"><?= $QR_LAB.'%';?></div></td>
          </tr>
          <tr style="background-color:#00ff95ff">
            <td align="left">Quality Rate % (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $DISP_QR_LAB2.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_LAB1.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_LAB.'%';?></div></td>
          </tr>
        <!-- End Lab -->
        <!-- Untuk DYE -->
          <tr> 
            <td rowspan='5'  align="center">DYE</td>
            <td align="left">Internal Ganti Kain</td>
            <td><div align="center"><?= $GKAIN_DYE2;?></div></td>
            <td><div align="center"><?= $GKAIN_DYE1;?></div></td>
            <td><div align="center"><?= $GKAIN_DYE;?></div></td>
          </tr>
          <tr>
            <td align="left">NCP + Tolak Basah + Gagal Proses</td>
            <td><div align="center"><?= $NCP_DYE2 . ' + ' . $TBASAH_DYE2 . ' + '. $GPROSES_DYE2 . ' = '. $NCP_CAL_DYE2;?></div></td>
            <td><div align="center"><?= $NCP_DYE1 . ' + ' . $TBASAH_DYE1 . ' + '. $GPROSES_DYE1 . ' = '. $NCP_CAL_DYE1;?></div></td>
            <td><div align="center"><?= $NCP_DYE . ' + ' . $TBASAH_DYE . ' + '. $GPROSES_DYE . ' = '. $NCP_CAL_DYE;?></div></td>
          </tr>
			    <tr>
            <td align="left">NCP (Setelah Dikurangi Disposisi NCP + Tolak Basah + Gagal Proses)</td>
            <td><div align="center"><?= $NCP_DYE2 . ' - ' . $DISP_NCP_DYE2 . ' + '. $DISP_TBASAH_DYE2 . ' + '. $DISP_GPROSES_DYE2 . ' = '. $DISP_NCP_CAL_DYE2;?></div></td>
            <td><div align="center"><?= $NCP_DYE1 . ' - ' . $DISP_NCP_DYE1 . ' + '. $DISP_TBASAH_DYE1 . ' + '. $DISP_GPROSES_DYE1 . ' = '. $DISP_NCP_CAL_DYE1;?></div></td>
            <td><div align="center"><?= $NCP_DYE . ' - ' . $DISP_NCP_DYE . ' + '. $DISP_TBASAH_DYE . ' + '. $DISP_GPROSES_DYE . ' = '. $DISP_NCP_CAL_DYE ;?></div></td>
          </tr> 
          <tr>
            <td align="left">Quality Rate %</td>
            <td><div align="center"><?= $QR_DYE2.'%';?></div></td>
            <td><div align="center"><?= $QR_DYE1.'%';?></div></td>
            <td><div align="center"><?= $QR_DYE.'%';?></div></td>
          </tr>
          <tr style="background-color:#00ff95ff">
            <td align="left">Quality Rate % (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $DISP_QR_DYE2.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_DYE1.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_DYE.'%';?></div></td>
          </tr>
        <!-- End Dye -->
        <!-- Kolom CQA -->
          <tr> 
            <td rowspan='5'  align="center">CQA</td>
            <td align="left">Internal Ganti Kain</td>
            <td><div align="center"><?= $GKAIN_CQA2;?></div></td>
            <td><div align="center"><?= $GKAIN_CQA1;?></div></td>
            <td><div align="center"><?= $GKAIN_CQA;?></div></td>
          </tr>
          <tr>
            <td align="left">NCP + Tolak Basah + Gagal Proses</td>
            <td><div align="center"><?= $NCP_CQA2 . ' + ' . $TBASAH_CQA2. ' + '. $GPROSES_CQA2 . ' = '. ($NCP_CQA2+$TBASAH_CQA2+$GPROSES_CQA2);?></div></td>
            <td><div align="center"><?= $NCP_CQA1 . ' + ' . $TBASAH_CQA1. ' + '. $GPROSES_CQA1 . ' = '. ($NCP_CQA1+$TBASAH_CQA1+$GPROSES_CQA1);?></div></td>
            <td><div align="center"><?= $NCP_CQA . ' + ' . $TBASAH_CQA. ' + '. $GPROSES_CQA . ' = '. ($NCP_CQA+$TBASAH_CQA+$GPROSES_CQA);?></div></td>
          </tr>
			    <tr>
            <td align="left">NCP (Setelah Dikurangi Disposisi NCP + Tolak Basah + Gagal Proses)</td>
            <td><div align="center"><?= $NCP_CQA2 . ' - ' . $DISP_NCP_CQA2 . ' + '. $DISP_TBASAH_CQA2 . ' + '. $DISP_GPROSES_CQA2 . ' = '. ($NCP_CQA2 - $DISP_NCP_CQA2+$DISP_TBASAH_CQA2+$DISP_GPROSES_CQA2);?></div></td>
            <td><div align="center"><?= $NCP_CQA1 . ' - ' . $DISP_NCP_CQA1 . ' + '. $DISP_TBASAH_CQA1 . ' + '. $DISP_GPROSES_CQA1 . ' = '. ($NCP_CQA1 - $DISP_NCP_CQA1+$DISP_TBASAH_CQA1+$DISP_GPROSES_CQA1);?></div></td>
            <td><div align="center"><?= $NCP_CQA . ' - ' . $DISP_NCP_CQA . ' + '. $DISP_TBASAH_CQA . ' + '. $DISP_GPROSES_CQA . ' = '. ($NCP_CQA - $DISP_NCP_CQA+$DISP_TBASAH_CQA+$DISP_GPROSES_CQA);?></div></td>
          </tr> 
          <tr>
            <td align="left">Quality Rate %</td>
            <td><div align="center"><?= $QR_CQA2.'%';?></div></td>
            <td><div align="center"><?= $QR_CQA1.'%';?></div></td>
            <td><div align="center"><?= $QR_CQA.'%';?></div></td>
          </tr>
          <tr style="background-color:#00ff95ff">
            <td align="left">Quality Rate % (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $DISP_QR_CQA2.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_CQA1.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_CQA.'%';?></div></td>
          </tr>
        <!-- End CQA -->
        <!-- Start KNT -->
          <tr> 
            <td rowspan='6'  align="center">KNT</td>
            <td align="left">Quantity Knitting</td>
            <td><div align="center"><?= $row_knt2['QTY'];?></div></td>
            <td><div align="center"><?= $row_knt1['QTY'];?></div></td>
            <td><div align="center"><?= $row_knt['QTY'];?></div></td>
          </tr>
          <tr>
            <td align="left">Internal Ganti Kain</td>
            <td><div align="center"><?= $GKAIN_KNT2;?></div></td>
            <td><div align="center"><?= $GKAIN_KNT1;?></div></td>
            <td><div align="center"><?= $GKAIN_KNT;?></div></td>
          </tr>
          <tr>
            <td align="left">NCP</td>
            <td><div align="center"><?= $NCP_KNT2;?></div></td>
            <td><div align="center"><?= $NCP_KNT1;?></div></td>
            <td><div align="center"><?= $NCP_KNT;?></div></td>
          </tr>
			    <tr>
            <td align="left">NCP (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $DISP_NCP_CAL_KNT2;?></div></td>
            <td><div align="center"><?= $DISP_NCP_CAL_KNT1;?></div></td>
            <td><div align="center"><?= $DISP_NCP_CAL_KNT;?></div></td>
          </tr> 
          <tr>
            <td align="left">Quality Rate %</td>
            <td><div align="center"><?= $QR_KNT2.'%';?></div></td>
            <td><div align="center"><?= $QR_KNT1.'%';?></div></td>
            <td><div align="center"><?= $QR_KNT.'%';?></div></td>
          </tr>
          <tr style="background-color:#00ff95ff">
            <td align="left">Quality Rate % (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $DISP_QR_KNT2.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_KNT1.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_KNT.'%';?></div></td>
          </tr>
        <!-- End KNT -->
        <!-- Start FIN -->
          <tr> 
            <td rowspan='6'  align="center">FIN</td>
            <td align="left">Quantity Finishing</td>
            <td><div align="center"><?= $row_fin2['QTY'];?></div></td>
            <td><div align="center"><?= $row_fin1['QTY'];?></div></td>
            <td><div align="center"><?= $row_fin['QTY'];?></div></td>
          </tr>
          <tr>
            <td align="left">Internal Ganti Kain</td>
            <td><div align="center"><?= $GKAIN_FIN2;?></div></td>
            <td><div align="center"><?= $GKAIN_FIN1;?></div></td>
            <td><div align="center"><?= $GKAIN_FIN;?></div></td>
          </tr>
          <tr>
            <td align="left">NCP</td>
            <td><div align="center"><?= $NCP_FIN2;?></div></td>
            <td><div align="center"><?= $NCP_FIN1;?></div></td>
            <td><div align="center"><?= $NCP_FIN;?></div></td>
          </tr>
			    <tr>
            <td align="left">NCP (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $NCP_FIN2 - $DISP_NCP_FIN2;?></div></td>
            <td><div align="center"><?= $NCP_FIN1 - $DISP_NCP_FIN1;?></div></td>
            <td><div align="center"><?= $NCP_FIN - $DISP_NCP_FIN;?></div></td>
          </tr> 
          <tr>
            <td align="left">Quality Rate %</td>
            <td><div align="center"><?= $QR_FIN2.'%';?></div></td>
            <td><div align="center"><?= $QR_FIN1.'%';?></div></td>
            <td><div align="center"><?= $QR_FIN.'%';?></div></td>
          </tr>
          <tr style="background-color:#00ff95ff">
            <td align="left">Quality Rate % (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $DISP_QR_FIN2.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_FIN1.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_FIN.'%';?></div></td>
          </tr>
        <!-- End Fin -->
        <!-- Start BRS -->
          <tr> 
            <td rowspan='6'  align="center">BRS</td>
            <td align="left">Quantity Brushing</td>
            <td><div align="center"><?= $row_brs2['QTY'];?></div></td>
            <td><div align="center"><?= $row_brs1['QTY'];?></div></td>
            <td><div align="center"><?= $row_brs['QTY'];?></div></td>
          </tr>
          <tr>
            <td align="left">Internal Ganti Kain</td>
            <td><div align="center"><?= $GKAIN_BRS2;?></div></td>
            <td><div align="center"><?= $GKAIN_BRS1;?></div></td>
            <td><div align="center"><?= $GKAIN_BRS;?></div></td>
          </tr>
          <tr>
            <td align="left">NCP</td>
            <td><div align="center"><?= $NCP_BRS2;?></div></td>
            <td><div align="center"><?= $NCP_BRS1;?></div></td>
            <td><div align="center"><?= $NCP_BRS;?></div></td>
          </tr>
			    <tr>
            <td align="left">NCP (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $NCP_BRS2 - $DISP_NCP_BRS2;?></div></td>
            <td><div align="center"><?= $NCP_BRS1 - $DISP_NCP_BRS1;?></div></td>
            <td><div align="center"><?= $NCP_BRS - $DISP_NCP_BRS;?></div></td>
          </tr>
          <tr>
            <td align="left">Quality Rate %</td>
            <td><div align="center"><?= $QR_BRS2.'%';?></div></td>
            <td><div align="center"><?= $QR_BRS1.'%';?></div></td>
            <td><div align="center"><?= $QR_BRS.'%';?></div></td>
          </tr>
          <tr style="background-color:#00ff95ff">
            <td align="left">Quality Rate % (Setelah Dikurangi Disposisi)</td>
            <td><div align="center"><?= $DISP_QR_BRS2.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_BRS1.'%';?></div></td>
            <td><div align="center"><?= $DISP_QR_BRS.'%';?></div></td>
          </tr>
        <!-- End BRS -->
        </tbody>
		<!-- <tfoot> 
			<?php
			$total_b_masuk_lot 	= $dt_B_oven['LOT']+$dt_B_oven_k['LOT']+$dt_B_stenter['LOT']+$dt_B_pr_dye['LOT']+$dt_B_fin_jadi['LOT']+$dt_B_fin_ulang['LOT']+$dt_B_fin_ulang_BRS['LOT']+$dt_B_fin_ulang_DYE['LOT']+$dt_B_fin_bl['LOT']+round($dt_B_steamer['LOT'],2)+$dt_B_preset['LOT']+$dt_B_ovenG['LOT']+$dt_B_dye_bl['LOT']+$dt_B_pre_bl['LOT']+$dt_B_lipat['LOT']+$dt_B_fin_1x['LOT'];
			$total_b_masuk_kg 	= round($dt_B_oven['KG'],2)+round($dt_B_oven_k['KG'],2)+round($dt_B_stenter['KG'],2)+round($dt_B_pr_dye['KG'],2)+round($dt_B_fin_jadi['KG'],2)+round($dt_B_fin_ulang['KG'],2)+round($dt_B_fin_ulang_BRS['KG'],2)+round($dt_B_fin_ulang_DYE['KG'],2)+round($dt_B_fin_bl['KG'],2)+round($dt_B_steamer['KG'],2)+round($dt_B_preset['KG'],2)+round($dt_B_ovenG['KG'],2)+round($dt_B_dye_bl['KG'],2)+round($dt_B_pre_bl['KG'],2)+round($dt_B_lipat['KG'],2)+round($dt_B_fin_1x['KG'],2);
			$total_b_keluar_lot 	= round($dts_B_oven['basah_lot'])+round($dts_B_oven_k['basah_lot'])+round($dts_B_stenter['basah_lot'])+round($dts_B_pr_dye['basah_lot'])+round($dts_B_fin_jadi['basah_lot'])+round($dts_B_fin_ulang['basah_lot'])+round($dts_B_fin_ulang_BRS['basah_lot'])+round($dts_B_fin_ulang_DYE['basah_lot'])+round($dts_B_fin_bl['basah_lot'])+round($dts_B_steamer['basah_lot'])+round($dts_B_preset['basah_lot'])+round($dts_B_ovenG['basah_lot'])+round($dts_B_dye_bl['basah_lot'])+round($dts_B_pre_bl['basah_lot'])+round($dts_B_lipat['basah_lot'])+round($dts_B_Ov_fleece['basah_lot'])+round($dts_B_Ov_strUlang['basah_lot'])+round($dts_B_fin_1x['basah_lot'],2);
			$total_b_keluar_kg 	= round($dts_B_oven['basah'],2)+round($dts_B_oven_k['basah'],2)+round($dts_B_stenter['basah'],2)+round($dts_B_pr_dye['basah'],2)+round($dts_B_fin_jadi['basah'],2)+round($dts_B_fin_ulang['basah'],2)+round($dts_B_fin_ulang_BRS['basah'],2)+round($dts_B_fin_ulang_DYE['basah'],2)+round($dts_B_fin_bl['basah'],2)+round($dts_B_steamer['basah'],2)+round($dts_B_preset['basah'],2)+round($dts_B_ovenG['basah'],2)+round($dts_B_dye_bl['basah'],2)+round($dts_B_pre_bl['basah'],2)+round($dts_B_lipat['basah'],2)+round($dts_B_Ov_fleece['basah'],2)+round($dts_B_Ov_strUlang['basah'],2)+round($dts_B_fin_1x['basah'],2);
			$total_b_sisa = $dts_B_sisa['oven_b_basah']+$dts_B_sisa['oven_k_basah']+$dts_B_sisa['oven_b_st_basah']+$dts_B_sisa['oven_p_dye_basah']+$dts_B_sisa['fin_jadi_basah']+$dts_B_sisa['fin_ul_basah']+$dts_B_sisa['fin_ul_p_brs_basah']+$dts_B_sisa['fin_ul_p_dye_basah']+$dts_B_sisa['belah_c_basah'];
			$total_b_sisa_lot = $dts_B_sisa['oven_b_basah_lot']+$dts_B_sisa['oven_k_basah_lot']+$dts_B_sisa['oven_b_st_basah_lot']+$dts_B_sisa['oven_p_dye_basah_lot']+$dts_B_sisa['fin_jadi_basah_lot']+$dts_B_sisa['fin_ul_basah_lot']+$dts_B_sisa['fin_ul_p_brs_basah_lot']+$dts_B_sisa['fin_ul_p_dye_basah_lot']+$dts_B_sisa['belah_c_basah_lot'];
      ?>
		    <tr>
            <td align="center">TOTAL</td>
            <td align="left">&nbsp;</td>
            <td align="right"><?php echo number_format($total_b_sisa,2); ?></td>
            <td align="center"><?php echo number_format($total_b_sisa_lot,0); ?></td>
            <td align="center"><?php echo number_format($total_b_keluar_lot,0); ?></td>
            <td align="right"><?php echo number_format(($total_b_sisa)+($total_b_masuk_kg-$total_b_keluar_kg),2); ?></td>
            <td align="center"><?php echo number_format(($total_b_sisa_lot)+($total_b_masuk_lot-$total_b_keluar_lot),0); ?></td>
            </tr>
		</tfoot>   -->
      </table>
    </div>
  </div>
</div>
</div>
<?php endif;?>
</div>	
	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});

	</script>
</body>
</html>