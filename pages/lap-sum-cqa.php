<?PHP
ini_set("error_reporting", 1);

session_start();
include"koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Summary CQA</title>

</head>
<body>
<?php
$Awal	= isset($_POST['awal']) ? $_POST['awal'] : date('Y-m-01');
$Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : date('Y-m-d');

$Awal_Sebelum = date('Y-m-d', strtotime($Awal . ' -1 day'));
$Akhir_Sebelum = date('Y-m-d', strtotime($Akhir . ' -1 day'));	

// Untuk SQL Outpuput Greige, SQL Outpuput Greige+perbaikan, Gagal Proses, Disposisi Gagal Proses
function generateWeeklySqlBetween($startDate, $endDate, &$weekRanges = [])
{
    $start = new DateTime($startDate);
    $end   = new DateTime($endDate);
    $monthlySql = [];

    // Loop per bulan
    while ($start <= $end) {
        $year      = $start->format('Y');
        $month     = $start->format('m');
        $monthName = strtolower($start->format('M'));

        $firstOfMonth = new DateTime("$year-$month-01");
        $lastOfMonth  = (clone $firstOfMonth)->modify('last day of this month')->setTime(23, 0, 0);

        if ($lastOfMonth > $end) {
            $lastOfMonth = (clone $end)->setTime(23, 0, 0);
        }

        $weekday = (int) $firstOfMonth->format('w');
        if ($weekday === 6) {
            $weekStart = (clone $firstOfMonth)->setTime(23, 1, 0);
        } elseif ($weekday === 0) {
            $weekStart = (clone $firstOfMonth)->modify('-1 day')->setTime(23, 1, 0);
        } else {
            $weekStart = (clone $firstOfMonth)->modify('-1 day')->setTime(23, 1, 0);
        }

        $weekNumber = 1;
        $sqlParts   = [];

        // Loop per minggu dalam bulan
        while ($weekStart <= $lastOfMonth) {
            // Akhir minggu = Jumat 23:00
            $weekEnd = (clone $weekStart)->modify('next friday')->setTime(23, 0, 0);
            if ($weekEnd > $lastOfMonth) {
                $weekEnd = clone $lastOfMonth;
            }

            $startStr = $weekStart->format('Y-m-d H:i:s');
            $endStr   = $weekEnd->format('Y-m-d H:i:s');

            $rangeKey = "w{$weekNumber}_{$monthName}";
            $weekRanges[$rangeKey] = "$startStr s/d $endStr";

            $sqlParts[] = "SUM(CASE
                WHEN c.tgl_update BETWEEN '$startStr' AND '$endStr'
                 AND (
                      x.proses_eff = 'Celup Greige'
                      OR x.proses_eff LIKE '%CUCI YARN DYE%'
                      OR x.proses_eff LIKE '%CUCI MISTY%'
                 )
                THEN c.bruto ELSE 0 END) AS kg_w{$weekNumber}_{$monthName}";

            $sqlParts[] = "SUM(CASE
                WHEN c.tgl_update BETWEEN '$startStr' AND '$endStr'
                 AND a.status = 'Gagal Proses'
                THEN c.bruto ELSE 0 END) AS kg_gp_w{$weekNumber}_{$monthName}";

            $sqlParts[] = "SUM(CASE
                WHEN c.tgl_update BETWEEN '$startStr' AND '$endStr'
                 AND p.tindakan = 'Disposisi'
                 AND a.status = 'Gagal Proses'
                THEN c.bruto ELSE 0 END) AS kg_dispgp_w{$weekNumber}_{$monthName}";

            $sqlParts[] = "SUM(CASE
                WHEN c.tgl_update BETWEEN '$startStr' AND '$endStr'
                 AND x.proses_eff NOT IN ('Bakar Bulu','Relaxing - Priset','Scouring - Priset','Continuous - Bleaching')
                THEN c.bruto ELSE 0 END) AS kg_ogreige_w{$weekNumber}_{$monthName}";

            $weekNumber++;
            $weekStart = (clone $weekEnd)->modify('+1 minute');
        }

        if (!empty($sqlParts)) {
            $monthlySql[] = implode(",\n    ", $sqlParts);
        }

        $start = (clone $lastOfMonth)->modify('+1 day')->setTime(0, 0, 0);
    }

    $sql = "SELECT\n    " . implode(",\n    ", $monthlySql) . "
            FROM db_dying.tbl_schedule b
            LEFT JOIN db_dying.tbl_montemp c
                ON c.id_schedule = b.id
            LEFT JOIN db_dying.tbl_hasilcelup a
                ON a.id_montemp = c.id
            LEFT JOIN db_dying.penyelesaian_gagalproses p
                ON p.id_schedule = b.id
              AND p.id_hasil_celup = a.id
              AND p.id_montemp = c.id
            CROSS APPLY (
                SELECT COALESCE(NULLIF(LTRIM(RTRIM(a.proses)), ''), b.proses) AS proses_eff
            ) x;";

    return $sql;
}
// End of SQL Output Greige, SQL Output Greige+perbaikan, Gagal Proses, Disposisi Gagal Proses
// Untuk SQL Tolak Basah
function generateTolakBasahSqlBetween($startDate, $endDate)
{
    $start = new DateTime($startDate);
    $end   = new DateTime($endDate);
    $monthlySql = [];

    // Loop per bulan
    while ($start <= $end) {
        $year      = $start->format('Y');
        $month     = $start->format('m');
        $monthName = strtolower($start->format('M'));

        $firstOfMonth = new DateTime("$year-$month-01");
        $lastOfMonth  = (clone $firstOfMonth)->modify('last day of this month')->setTime(23, 0, 0);

        if ($lastOfMonth > $end) {
            $lastOfMonth = (clone $end)->setTime(23, 0, 0);
        }

        // Tentukan start minggu pertama
        $weekday = (int)$firstOfMonth->format('w');
        if ($weekday === 6) {
            $weekStart = (clone $firstOfMonth)->setTime(23, 1, 0);
        } elseif ($weekday === 0) {
            $weekStart = (clone $firstOfMonth)->modify('-1 day')->setTime(23, 1, 0);
        } else {
            $weekStart = (clone $firstOfMonth)->modify('-1 day')->setTime(23, 1, 0);
        }

        $weekNumber = 1;
        $sqlParts   = [];

        while ($weekStart <= $lastOfMonth) {
            $weekEnd = (clone $weekStart)->modify('next friday')->setTime(23, 0, 0);
            if ($weekEnd > $lastOfMonth) {
                $weekEnd = clone $lastOfMonth;
            }

            $startStr = $weekStart->format('Y-m-d H:i:s');
            $endStr   = $weekEnd->format('Y-m-d H:i:s');

            $sqlParts[] = "SUM(CASE
                WHEN t.tgl_update BETWEEN '$startStr' AND '$endStr'
                 AND t.status_warna LIKE '%TOLAK BASAH%'
                THEN t.bruto ELSE 0 END) AS kg_tb_w{$weekNumber}_{$monthName}";

            $sqlParts[] = "SUM(CASE
                WHEN t.tgl_update BETWEEN '$startStr' AND '$endStr'
                 AND t.status_warna LIKE '%TOLAK BASAH%'
                 AND p.tindakan = 'Disposisi'
                THEN t.bruto ELSE 0 END) AS kg_disptb_w{$weekNumber}_{$monthName}";

            $weekNumber++;
            $weekStart = (clone $weekEnd)->modify('+1 minute');
        }

        if (!empty($sqlParts)) {
            $monthlySql[] = implode(",\n    ", $sqlParts);
        }

        $start = (clone $lastOfMonth)->modify('+1 day')->setTime(0, 0, 0);
    }

    $sql = "SELECT\n    " . implode(",\n    ", $monthlySql) . "
            FROM db_qc.tbl_cocok_warna_dye t
            LEFT JOIN db_qc.penyelesaian_tolakbasah p
                ON t.id = p.id_cocok_warna;";

    return $sql;
}

// End

// Untuk SQL NCP
function generateNCPSqlBetween($startDate, $endDate)
{
    $start = new DateTime($startDate);
    $end   = new DateTime($endDate);
    $monthlySql = [];

    while ($start <= $end) {
        $year      = $start->format('Y');
        $month     = $start->format('m');
        $monthName = strtolower($start->format('M'));

        $firstOfMonth = new DateTime("$year-$month-01");
        $lastOfMonth  = (clone $firstOfMonth)->modify('last day of this month')->setTime(23, 0, 0);

        if ($lastOfMonth > $end) {
            $lastOfMonth = (clone $end)->setTime(23, 0, 0);
        }

        $weekday = (int)$firstOfMonth->format('w');
        if ($weekday === 6) {
            $weekStart = (clone $firstOfMonth)->setTime(23, 1, 0);
        } elseif ($weekday === 0) {
            $weekStart = (clone $firstOfMonth)->modify('-1 day')->setTime(23, 1, 0);
        } else {
            $weekStart = (clone $firstOfMonth)->modify('-1 day')->setTime(23, 1, 0);
        }

        $weekNumber = 1;
        $sqlParts   = [];

        while ($weekStart <= $lastOfMonth) {
            $weekEnd = (clone $weekStart)->modify('next friday')->setTime(23, 0, 0);
            if ($weekEnd > $lastOfMonth) {
                $weekEnd = clone $lastOfMonth;
            }

            $startStr = $weekStart->format('Y-m-d H:i:s');
            $endStr   = $weekEnd->format('Y-m-d H:i:s');

            $sqlParts[] = "SUM(CASE
                WHEN tgl_buat BETWEEN '$startStr' AND '$endStr'
                 AND ncp_hitung = 'ya'
                 AND dept = 'CQA'
                 AND [status] IN ('Belum OK', 'OK', 'BS', 'Cancel', 'Disposisi')
                THEN berat ELSE 0 END) AS kg_ncp_w{$weekNumber}_{$monthName}";

            $sqlParts[] = "SUM(CASE
                WHEN tgl_buat BETWEEN '$startStr' AND '$endStr'
                 AND [status] = 'Disposisi'
                 AND dept = 'CQA'
                 AND ncp_hitung = 'ya'
                THEN berat ELSE 0 END) AS kg_dispncp_w{$weekNumber}_{$monthName}";

            $sqlParts[] = "SUM(CASE
                WHEN tgl_buat BETWEEN '$startStr' AND '$endStr'
                 AND dept = 'CQA'
                 AND [status] IN ('Belum OK', 'OK', 'BS', 'Cancel', 'Disposisi')
                 AND masalah_dominan NOT LIKE '%BEDA WARNA%'
                 AND ncp_hitung = 'ya'
                THEN berat ELSE 0 END) AS kg_ncpq_w{$weekNumber}_{$monthName}";

            $sqlParts[] = "SUM(CASE
                WHEN tgl_buat BETWEEN '$startStr' AND '$endStr'
                 AND [status] = 'Disposisi'
                 AND masalah_dominan NOT LIKE '%BEDA WARNA%'
                 AND dept = 'CQA'
                 AND ncp_hitung = 'ya'
                THEN berat ELSE 0 END) AS kg_dispncpq_w{$weekNumber}_{$monthName}";

            $sqlParts[] = "SUM(CASE
                WHEN tgl_buat BETWEEN '$startStr' AND '$endStr'
                 AND dept = 'CQA'
                 AND [status] IN ('Belum OK', 'OK', 'BS', 'Cancel', 'Disposisi')
                 AND masalah_dominan LIKE '%BEDA WARNA%'
                 AND ncp_hitung = 'ya'
                THEN berat ELSE 0 END) AS kg_ncpbw_w{$weekNumber}_{$monthName}";

            $sqlParts[] = "SUM(CASE
                WHEN tgl_buat BETWEEN '$startStr' AND '$endStr'
                 AND [status] = 'Disposisi'
                 AND masalah_dominan LIKE '%BEDA WARNA%'
                 AND dept = 'CQA'
                 AND ncp_hitung = 'ya'
                THEN berat ELSE 0 END) AS kg_dispncpbw_w{$weekNumber}_{$monthName}";

            $weekNumber++;
            $weekStart = (clone $weekEnd)->modify('+1 minute');
        }

        if (!empty($sqlParts)) {
            $monthlySql[] = implode(",\n    ", $sqlParts);
        }

        $start = (clone $lastOfMonth)->modify('+1 day')->setTime(0, 0, 0);
    }

    $sql = "SELECT\n    " . implode(",\n    ", $monthlySql) . "
            FROM db_qc.tbl_ncp_qcf_now t;";

    return $sql;
}

// End
?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"> Filter Laporan Summary CQA</h3>
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
        <h3 class="box-title">Data Summary CQA</h3><br>		  
        <?php if($_POST['akhir']!="") { ?><b>Periode: <?php echo $Awal." to ".$Akhir; ?></b>
		<?php } ?>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%">
       <?php
  // echo "Awal: $Awal<br>";
  // echo "Akhir: $Akhir<br>";
  $weekRanges = [];
  $sqlQuery   = generateWeeklySqlBetween($Awal, $Akhir, $weekRanges);
  // echo "<pre>$sqlQuery</pre>";
  $stmt_dye = sqlsrv_query($con, $sqlQuery);
  if ($stmt_dye === false) {
      die(print_r(sqlsrv_errors(), true));
  }
  $d_dye = sqlsrv_fetch_array($stmt_dye, SQLSRV_FETCH_ASSOC);

  $sqlQuery2 = generateTolakBasahSqlBetween($Awal, $Akhir);
  $stmt_qc = sqlsrv_query($cond, $sqlQuery2);
  if ($stmt_qc === false) {
      die(print_r(sqlsrv_errors(), true));
  }
  $d_qc = sqlsrv_fetch_array($stmt_qc, SQLSRV_FETCH_ASSOC);

  $sqlQuery3 = generateNCPSqlBetween($Awal, $Akhir);
  $stmt_qc2 = sqlsrv_query($cond, $sqlQuery3);
  if ($stmt_qc2 === false) {
      die(print_r(sqlsrv_errors(), true));
  }
  $d_qc2 = sqlsrv_fetch_array($stmt_qc2, SQLSRV_FETCH_ASSOC);

  // echo "<pre>$sqlQuery3</pre>";
  // Step 1: Ambil nama bulan dari key hasil query (contoh: roll_w1_jun)
  $monthKey = null;

  if (is_array($d_dye)) {
      foreach (array_keys($d_dye) as $k) {
          if (preg_match('/^kg_w\d+_(\w+)$/', $k, $m)) {
              $monthKey = strtolower($m[1]);
              break;
          }
      }
  }

  if ($monthKey === null) {
      $monthKey = strtolower(date('M', strtotime($Awal)));
  }

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
  $weeks = [];
  foreach (array_keys($d_dye) as $key) {
      if (preg_match('/kg_w(\d+)_/', $key, $m)) {
          $weeks[] = (int)$m[1];
      }
  }
  $weeks = array_unique($weeks);
  sort($weeks);
  ?>

  <thead class="bg-blue">
      <tr>
          <th><div align="center"><?= $monthName; ?></div></th>
          <th><div align="center">Output Greige</div></th>
          <th><div align="center">Output Greige + Perbaikan </div></th>
          <th colspan=2><div align="center">Gagal Proses </div></th>
          <th colspan=2><div align="center">Disposisi Gagal Proses </div></th>
          <th colspan=2><div align="center">Tolak Basah</div></th>
          <th colspan=2><div align="center">Disposisi Tolak Basah</div></th>
          <th colspan=2><div align="center">NCP</div></th>
          <th colspan=2><div align="center">Disposisi NCP</div></th>
          <th colspan=2><div align="center">NCP Quality</div></th>
          <th colspan=2><div align="center">Disposisi NCP Quality</div></th>
          <th colspan=2><div align="center">NCP Beda Warna</div></th>
          <th colspan=2><div align="center">Disposisi NCP Beda Warna</div></th>
      </tr>
  </thead>
  <tbody>
  <?php 
        $total_greige = 0;  
        $total_ogreige = 0;
        $total_gp = 0;
        $total_dispgp = 0;
        $total_tb = 0;
    foreach ($weeks as $week): ?>
      <?php
          $kg_griege_Key = "kg_w{$week}_{$monthKey}";
          $kg_ogreige_Key = "kg_ogreige_w{$week}_{$monthKey}";
          $kg_gp_Key = "kg_gp_w{$week}_{$monthKey}";
          $kg_dispgp_Key = "kg_dispgp_w{$week}_{$monthKey}";
          $kg_tb_Key = "kg_tb_w{$week}_{$monthKey}";
          $kg_disptb_Key = "kg_disptb_w{$week}_{$monthKey}";
          $kg_ncp_Key = "kg_ncp_w{$week}_{$monthKey}";
          $kg_dispncp_Key = "kg_dispncp_w{$week}_{$monthKey}";
          $kg_ncpbw_Key = "kg_ncpbw_w{$week}_{$monthKey}";
          $kg_dispncpbw_Key = "kg_dispncpbw_w{$week}_{$monthKey}";
          $kg_ncpq_Key = "kg_ncpq_w{$week}_{$monthKey}";
          $kg_dispncpq_Key = "kg_dispncpq_w{$week}_{$monthKey}";

          $kg_greige   = round(floatval($d_dye[$kg_griege_Key] ?? 0), 2);
          $kg_ogreige  = round(floatval($d_dye[$kg_ogreige_Key] ?? 0), 2);
          $kg_gp       = round(floatval($d_dye[$kg_gp_Key] ?? 0), 2);
          $kg_dispgp   = round(floatval($d_dye[$kg_dispgp_Key] ?? 0), 2);
          $kg_tb       = round(floatval($d_qc[$kg_tb_Key] ?? 0), 2);
          $kg_disptb   = round(floatval($d_qc[$kg_disptb_Key] ?? 0), 2);
          $kg_ncp   = round(floatval($d_qc2[$kg_ncp_Key] ?? 0), 2);
          $kg_dispncp   = round(floatval($d_qc2[$kg_dispncp_Key] ?? 0), 2);
          $kg_ncpbw   = round(floatval($d_qc2[$kg_ncpbw_Key] ?? 0), 2);
          $kg_dispncpbw   = round(floatval($d_qc2[$kg_dispncpbw_Key] ?? 0), 2);
          $kg_ncpq   = round(floatval($d_qc2[$kg_ncpq_Key] ?? 0), 2);
          $kg_dispncpq   = round(floatval($d_qc2[$kg_dispncpq_Key] ?? 0), 2);

      ?>
      <tr>
          <td align="left">Minggu <?= $week ?><br>
          <?php
              $rangeKey = "w{$week}_{$monthKey}";
              echo isset($weekRanges[$rangeKey]) ? $weekRanges[$rangeKey] : '-';
          ?>
        </td>
        <!-- Output Greige -->
          <td align="right"><?= number_format($kg_greige,2) ?></td>
        <!-- Output Greige + Perbaikan -->
          <td align="right"><?= number_format($kg_ogreige,2)  ?></td>
        <!-- Gagal Proses -->
          <td align="right"><?= number_format($kg_gp,2)  ?></td>
          <td align="center"><?php 
                                $p_gp = ($kg_ogreige != null && $kg_ogreige != 0) ? ($kg_gp / $kg_ogreige) * 100 : 0;
                                echo number_format($p_gp, 2) . ' %';
                              ?>
          </td>
        <!-- Disposisi Gagal Proses -->
          <td align="right"><?= $kg_dispgp ?></td>
          <td align="center"><?php 
                                $p_dispgp = ($kg_ogreige != null && $kg_ogreige != 0) ? ($kg_dispgp / $kg_ogreige) * 100 : 0;
                                echo number_format($p_dispgp, 2) . ' %';
                              ?>
          </td>
        <!-- Tolak Basah -->
          <td align="right"><?= number_format($kg_tb,2)  ?></td>
          <td align="center"><?php 
                                $p_tb = ($kg_ogreige != null && $kg_ogreige != 0) ? ($kg_tb / $kg_ogreige) * 100 : 0;
                                echo number_format($p_tb, 2) . ' %';
                              ?></td>
        <!-- Disposisi Tolak Basah -->
          <td align="right"><?= number_format($kg_disptb,2)  ?></td>
          <td align="center"><?php 
                                $p_disptb = ($kg_ogreige != null && $kg_ogreige != 0) ? ($kg_disptb / $kg_ogreige) * 100 : 0;
                                echo number_format($p_disptb, 2) . ' %';
                              ?></td>
        <!-- NCP -->
          <td align="right"><?= number_format($kg_ncp,2)  ?></td>
          <td align="center"><?php 
                                $p_ncp = ($kg_ogreige != null && $kg_ogreige != 0) ? ($kg_ncp / $kg_ogreige) * 100 : 0;
                                echo number_format($p_ncp, 2) . ' %';
                              ?></td>
        <!-- Disposisi NCP -->
          <td align="center"><?= number_format($kg_dispncp,2)  ?></td>
          <td align="center"><?php 
                                $p_dispncp = ($kg_ogreige != null && $kg_ogreige != 0) ? ($kg_dispncp / $kg_ogreige) * 100 : 0;
                                echo number_format($p_dispncp, 2) . ' %';
                              ?></td>
        <!-- NCP Quality -->
          <td align="right"><?= number_format($kg_ncpq,2)  ?></td>
          <td align="center"><?php 
                                $p_ncpq = ($kg_ogreige != null && $kg_ogreige != 0) ? ($kg_ncpq / $kg_ogreige) * 100 : 0;
                                echo number_format($p_ncpq, 2) . ' %';
                              ?></td>
        <!-- Disposisi NCP Quality -->
          <td align="right"><?= number_format($kg_dispncpq,2)  ?></td>
          <td align="center"><?php 
                                $p_dispncpq = ($kg_ogreige != null && $kg_ogreige != 0) ? ($kg_dispncpq / $kg_ogreige) * 100 : 0;
                                echo number_format($p_dispncpq, 2) . ' %';
                              ?></td>
        <!-- NCP Beda Warna -->
          <td align="right"><?= number_format($kg_ncpbw,2)  ?></td>
          <td align="center"><?php 
                                $p_ncpbw = ($kg_ogreige != null && $kg_ogreige != 0) ? ($kg_ncpbw / $kg_ogreige) * 100 : 0;
                                echo number_format($p_ncpbw, 2) . ' %';
                              ?></td>
        <!-- Disposisi NCP Beda Warna -->
          <td align="right"><?= number_format($kg_dispncpbw,2)  ?></td>
          <td align="center"><?php 
                                $p_dispncpbw = ($kg_ogreige != null && $kg_ogreige != 0) ? ($kg_dispncpbw / $kg_ogreige) * 100 : 0;
                                echo number_format($p_dispncpbw, 2) . ' %';
                              ?></td>
      </tr>
  <?php 
    $total_greige += $kg_greige;
    $total_ogreige += $kg_ogreige;
    $total_pgp += $p_gp;
    $total_gp += $kg_gp;
    $total_dispgp += $kg_dispgp;
    $total_pdispgp += $kg_dispgp;
    $total_tb += $kg_tb;
    $total_ptb += $p_tb;
    $total_disptb += $kg_disptb;
    $total_ncp += $kg_ncp;
    $total_dispncp += $kg_dispncp;
    $total_ncpq += $kg_ncpq;
    $total_dispncpq += $kg_dispncpq;
    $total_ncpbw += $kg_ncpbw;
    $total_dispncpbw += $kg_dispncpbw;
    endforeach; ?>
  </tbody>
      <tfoot> 
          <tr>
              <td align="center">Total Bulan <?= $monthName; ?> </td>
              <td align="right"><?= number_format($total_greige, 2);?></td>
              <td align="right"><?php echo number_format($total_ogreige,2); ?></td>
              <td align="right"><?php echo number_format($total_gp,2); ?></td>
              <td align="center"><?php 
                                $p_tgp = ($total_ogreige != null && $total_ogreige != 0) ? ($total_gp / $total_ogreige) * 100 : 0;
                                echo number_format($p_tgp, 2) . ' %';
                              ?></td> 
              <td align="right"><?php echo number_format($total_dispgp,2); ?></td>
              <td align="center"><?php 
                                $p_tdispgp = ($total_ogreige != null && $total_ogreige != 0) ? ($total_dispgp / $total_ogreige) * 100 : 0;
                                echo number_format($p_tdispgp, 2) . ' %';
                              ?></td>
              <td align="right"><?php echo number_format($total_tb,2); ?></td>
              <td align="center"><?php 
                                $p_ttb = ($total_ogreige != null && $total_ogreige != 0) ? ($total_tb / $total_ogreige) * 100 : 0;
                                echo number_format($p_ttb, 2) . ' %';
                              ?></td>
              <td align="right"><?php echo number_format($total_disptb,2); ?></td>
              <td align="center"><?php 
                                $p_tdisptb = ($total_ogreige != null && $total_ogreige != 0) ? ($total_disptb / $total_ogreige) * 100 : 0;
                                echo number_format($p_tdisptb, 2) . ' %';
                              ?></td>
              <td align="right"><?php echo number_format($total_ncp,2); ?></td>
              <td align="center"><?php 
                                $p_ncp = ($total_ogreige != null && $total_ogreige != 0) ? ($total_ncp / $total_ogreige) * 100 : 0;
                                echo number_format($p_ncp, 2) . ' %';
                              ?></td>
              <td align="right"><?php echo number_format($total_dispncp,2); ?></td>
              <td align="center"><?php 
                                $p_dispncp = ($total_ogreige != null && $total_ogreige != 0) ? ($total_dispncp / $total_ogreige) * 100 : 0;
                                echo number_format($p_dispncp, 2) . ' %';
                                ?></td>
              <td align="right"><?php echo number_format($total_ncpq,2); ?></td>
              <td align="center"><?php 
                                $p_ncpq = ($total_ogreige != null && $total_ogreige != 0) ? ($total_ncpq / $total_ogreige) * 100 : 0;
                                echo number_format($p_ncpq, 2) . ' %';
                              ?></td>
              <td align="right"><?php echo number_format($total_dispncpq,2); ?></td>
              <td align="center"><?php 
                                $p_dispncpq = ($total_ogreige != null && $total_ogreige != 0) ? ($total_dispncpq / $total_ogreige) * 100 : 0;
                                echo number_format($p_dispncpq, 2) . ' %';
                              ?></td>
              <td align="right"><?php echo number_format($total_ncpbw,2); ?></td>
              <td align="center"><?php 
                                $p_ncpbw = ($total_ogreige != null && $total_ogreige != 0) ? ($total_ncpbw / $total_ogreige) * 100 : 0;
                                echo number_format($p_ncpbw, 2) . ' %';
                              ?></td>
              <td align="right"><?php echo number_format($total_dispncpbw,2); ?></td>
              <td align="center"><?php 
                                $p_dispncpbw = ($total_ogreige != null && $total_ogreige != 0) ? ($total_dispncpbw / $total_ogreige) * 100 : 0;
                                echo number_format($p_dispncpbw, 2) . ' %';
                              ?></td>
              </tr>
      </tfoot>  
        </table>
      <br>	  
        </div>
      </div>
    <div class="box">
      <div class="box-header with-border">
		<?php if($_POST['akhir']!="") {  ?>
		<!-- <a href="pages/cetak/cetak_lapharianfin.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>" class="btn btn-warning btn-sm pull-right" target="_blank"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</a> <br> --><?php } ?> 
        <h3 class="box-title">Data Summary Actual</h3><br>		  
        <?php if($_POST['akhir']!="") { ?><b>Periode: <?php echo $Awal." to ".$Akhir; ?></b>
		<?php } ?>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped nowrap" id="examplex" style="width:100%">
       <?php
  // echo "Awal: $Awal<br>";
  // echo "Akhir: $Akhir<br>";
  $weekRanges = [];
  $sqlQuery  = generateWeeklySqlBetween($Awal, $Akhir, $weekRanges);
  // echo "<pre>$sqlQuery</pre>";
  $stmt_dye  = sqlsrv_query($con, $sqlQuery);
  if ($stmt_dye === false) { die(print_r(sqlsrv_errors(), true)); }
  $d_dye     = sqlsrv_fetch_array($stmt_dye, SQLSRV_FETCH_ASSOC);

  $sqlQuery2 = generateTolakBasahSqlBetween($Awal, $Akhir);
  $stmt_qc   = sqlsrv_query($cond, $sqlQuery2);
  if ($stmt_qc === false) { die(print_r(sqlsrv_errors(), true)); }
  $d_qc      = sqlsrv_fetch_array($stmt_qc, SQLSRV_FETCH_ASSOC);

  $sqlQuery3 = generateNcpSqlBetween($Awal, $Akhir);
  $stmt_qc2  = sqlsrv_query($cond, $sqlQuery3);
  if ($stmt_qc2 === false) { die(print_r(sqlsrv_errors(), true)); }
  $d_qc2     = sqlsrv_fetch_array($stmt_qc2, SQLSRV_FETCH_ASSOC);
  // echo "<pre>$sqlQuery3</pre>";
  // Step 1: Ambil nama bulan dari key hasil query (contoh: roll_w1_jun)
  preg_match('/kg_w\d+_(\w+)/', array_keys($d_dye)[0], $matches);
  $monthKey = $matches[1] ?? 'jun';
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
  $weeks = [];
  foreach (array_keys($d_dye) as $key) {
      if (preg_match('/kg_w(\d+)_/', $key, $m)) {
          $weeks[] = (int)$m[1];
      }
  }
  $weeks = array_unique($weeks);
  sort($weeks);
  ?>

  <thead class="bg-blue">
      <tr>
          <th><div align="center"><?= $monthName; ?></div></th>
          <th><div align="center">Output Greige</div></th>
          <th><div align="center">Output Greige + Perbaikan </div></th>
          <th colspan=2><div align="center">Actual Gagal Proses </div></th>
          <!-- <th colspan=2><div align="center">Disposisi Gagal Proses </div></th> -->
          <th colspan=2><div align="center">Actual Tolak Basah</div></th>
          <!-- <th colspan=2><div align="center">Disposisi Tolak Basah</div></th> -->
          <th colspan=2><div align="center">Actual NCP</div></th>
          <!-- <th colspan=2><div align="center">Disposisi NCP</div></th> -->
          <th colspan=2><div align="center">Actual NCP Quality</div></th>
          <!-- <th colspan=2><div align="center">Disposisi NCP Quality</div></th> -->
          <th colspan=2><div align="center">Actual NCP Beda Warna</div></th>
          <!-- <th colspan=2><div align="center">Disposisi NCP Beda Warna</div></th> -->
      </tr>
  </thead>
  <tbody>
  <?php 
        $gp = 0;  
        $total_ogreige = 0;
        $total_gp = 0;
        $total_dispgp = 0;
        $total_tb = 0;
        $total_greige = 0;
    foreach ($weeks as $week): ?>
      <?php
          $kg_griege_Key = "kg_w{$week}_{$monthKey}";
          $kg_ogreige_Key = "kg_ogreige_w{$week}_{$monthKey}";
          $kg_gp_Key = "kg_gp_w{$week}_{$monthKey}";
          $kg_dispgp_Key = "kg_dispgp_w{$week}_{$monthKey}";
          $kg_tb_Key = "kg_tb_w{$week}_{$monthKey}";
          $kg_disptb_Key = "kg_disptb_w{$week}_{$monthKey}";
          $kg_ncp_Key = "kg_ncp_w{$week}_{$monthKey}";
          $kg_dispncp_Key = "kg_dispncp_w{$week}_{$monthKey}";
          $kg_ncpbw_Key = "kg_ncpbw_w{$week}_{$monthKey}";
          $kg_dispncpbw_Key = "kg_dispncpbw_w{$week}_{$monthKey}";
          $kg_ncpq_Key = "kg_ncpq_w{$week}_{$monthKey}";
          $kg_dispncpq_Key = "kg_dispncpq_w{$week}_{$monthKey}";

          $kg_greige   = round(floatval($d_dye[$kg_griege_Key] ?? 0), 2);
          $kg_ogreige  = round(floatval($d_dye[$kg_ogreige_Key] ?? 0), 2);
          $kg_gp       = round(floatval($d_dye[$kg_gp_Key] ?? 0), 2);
          $kg_dispgp   = round(floatval($d_dye[$kg_dispgp_Key] ?? 0), 2);
          $kg_tb       = round(floatval($d_qc[$kg_tb_Key] ?? 0), 2);
          $kg_disptb   = round(floatval($d_qc[$kg_disptb_Key] ?? 0), 2);
          $kg_ncp   = round(floatval($d_qc2[$kg_ncp_Key] ?? 0), 2);
          $kg_dispncp   = round(floatval($d_qc2[$kg_dispncp_Key] ?? 0), 2);
          $kg_ncpbw   = round(floatval($d_qc2[$kg_ncpbw_Key] ?? 0), 2);
          $kg_dispncpbw   = round(floatval($d_qc2[$kg_dispncpbw_Key] ?? 0), 2);
          $kg_ncpq   = round(floatval($d_qc2[$kg_ncpq_Key] ?? 0), 2);
          $kg_dispncpq   = round(floatval($d_qc2[$kg_dispncpq_Key] ?? 0), 2);

      ?>
      <tr>
          <td align="left">Minggu <?= $week ?><br>
          <?php
              $rangeKey = "w{$week}_{$monthKey}";
              echo isset($weekRanges[$rangeKey]) ? $weekRanges[$rangeKey] : '-';
          ?>
        </td>
        <!-- Output Greige -->
          <td align="right"><?= number_format($kg_greige,2) ?></td>
        <!-- Output Greige + Perbaikan -->
          <td align="right"><?= number_format($kg_ogreige,2)  ?></td>
        <!-- Gagal Proses -->
          <td align="right"><?php 
                                $gp = ($kg_gp-$kg_dispgp);
                                echo number_format($gp,2);
                            ?>
          </td>
          <td align="center"><?php 
                                $p_gp = ($kg_ogreige != null && $kg_ogreige != 0) ? ($gp / $kg_ogreige) * 100 : 0;
                                echo number_format($p_gp, 2) . ' %';
                              ?>
          </td>
        <!-- Tolak Basah -->
          <td align="right"><?php 
                                $tb = ($kg_tb-$kg_disptb);
                                echo number_format($tb,2);
                              ?>
          </td>
          <td align="center"><?php 
                                $p_tb = ($kg_ogreige != null && $kg_ogreige != 0) ? ($tb / $kg_ogreige) * 100 : 0;
                                echo number_format($p_tb, 2) . ' %';
                              ?></td>
        <!-- NCP -->
          <td align="right"><?php 
                                 $ncp=  ($kg_ncp-$kg_dispncp);
                                 echo number_format($ncp,2);
                              ?>
          </td>
          <td align="center"><?php 
                                $p_ncp = ($kg_ogreige != null && $kg_ogreige != 0) ? ($ncp / $kg_ogreige) * 100 : 0;
                                echo number_format($p_ncp, 2) . ' %';
                              ?></td>
        <!-- NCP Quality -->
          <td align="right"><?php 
                                $ncp_qua= ($kg_ncpq-$kg_dispncpq) ;
                                echo number_format($ncp_qua,2);
                            ?>
          </td>
          <td align="center"><?php 
                                $p_ncpq = ($kg_ogreige != null && $kg_ogreige != 0) ? ($ncp_qua / $kg_ogreige) * 100 : 0;
                                echo number_format($p_ncpq, 2) . ' %';
                              ?></td>

        <!-- NCP Beda Warna -->
          <td align="right"><?php 
                                $ncp_bw = ($kg_ncpbw - $kg_dispncpbw);  
                                echo number_format($ncp_bw,2);
                              ?>
          </td>
          <td align="center"><?php 
                                $p_ncpbw = ($kg_ogreige != null && $kg_ogreige != 0) ? ($ncp_bw / $kg_ogreige) * 100 : 0;
                                echo number_format($p_ncpbw, 2) . ' %';
                              ?></td>
      </tr>
  <?php 
    $total_greige += $kg_greige;
    $total_ogreige += $kg_ogreige;
    $total_gp1 += $gp;
    $total_pdispgp += $kg_dispgp;
    $total_tb1 += $tb;
    $total_ptb += $p_tb;
    $total_disptb += $kg_disptb;
    $total_ncp1 += $ncp;
    $total_dispncp += $kg_dispncp;
    $total_ncpq1 += $ncp_qua;
    $total_dispncpq += $kg_dispncpq;
    $total_ncpbw1 += $ncp_bw;  
    $total_dispncpbw += $kg_dispncpbw;
    endforeach; ?>
  </tbody>
      <tfoot> 
          <tr>
              <td align="center">Total Bulan <?= $monthName; ?> </td>
              <td align="right"><?= number_format($total_greige, 2);?></td>
              <td align="right"><?php echo number_format($total_ogreige,2); ?></td>
              <td align="right"><?php echo number_format($total_gp1,2); ?></td>
              <td align="center"><?php 
                                $p_tgp1 = ($total_ogreige != null && $total_ogreige != 0) ? ($total_gp1 / $total_ogreige) * 100 : 0;
                                echo number_format($p_tgp1, 2) . ' %';
                              ?></td> 
              <td align="right"><?php echo number_format($total_tb1,2); ?></td>
              <td align="center"><?php 
                                $p_ttb1 = ($total_ogreige != null && $total_ogreige != 0) ? ($total_tb1 / $total_ogreige) * 100 : 0;
                                echo number_format($p_ttb1, 2) . ' %';
                              ?></td>
              <td align="right"><?php echo number_format($total_ncp1,2); ?></td>
              <td align="center"><?php 
                                $p_ncp = ($total_ogreige != null && $total_ogreige != 0) ? ($total_ncp1 / $total_ogreige) * 100 : 0;
                                echo number_format($p_ncp, 2) . ' %';
                              ?></td>
              <td align="right"><?php echo number_format($total_ncpq1,2); ?></td>
              <td align="center"><?php 
                                $p_ncpq = ($total_ogreige != null && $total_ogreige != 0) ? ($total_ncpq1 / $total_ogreige) * 100 : 0;
                                echo number_format($p_ncpq, 2) . ' %';
                              ?></td>
              <td align="right"><?php echo number_format($total_ncpbw1,2); ?></td>
              <td align="center"><?php 
                                $p_ncpbw = ($total_ogreige != null && $total_ogreige != 0) ? ($total_ncpbw1 / $total_ogreige) * 100 : 0;
                                echo number_format($p_ncpbw, 2) . ' %';
                              ?></td>
              </tr>
      </tfoot>  
        </table>
      <br>	  
        </div>
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

	</script>
</body>
</html>