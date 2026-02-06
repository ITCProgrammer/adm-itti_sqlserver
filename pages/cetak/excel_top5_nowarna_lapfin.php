<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Top5-NoWarna-Lap-Fin-" . substr($_GET['awal'], 0, 10) . ".xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>
<?php
include "../../koneksi.php";
//--
$Awal = $_GET['awal'];
$Akhir = $_GET['akhir'];
?>

<body>
  <?php
  // TOTAL (denominator)
  $sqlball = sqlsrv_query($cond, "
    SELECT COUNT(a.nokk) AS jml_kk_all
    FROM db_qc.tbl_lap_inspeksi a
    WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
      AND a.dept = 'QCF'
      AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
", [$Awal, $Akhir]);

  $rball = sqlsrv_fetch_array($sqlball, SQLSRV_FETCH_ASSOC);
  $all  = (int)($rball['jml_kk_all'] ?? 0);
  ?>
  <strong>Periode: <?php echo $Awal; ?> s/d <?php echo $Akhir; ?></strong><br>

  <table width="100%" border="1">
    <tr>
      <th bgcolor="#12C9F0">
        <div align="center">No</div>
      </th>
      <th bgcolor="#12C9F0">
        <div align="center">No Warna</div>
      </th>
      <th bgcolor="#12C9F0">
        <div align="center">Warna</div>
      </th>
      <th bgcolor="#12C9F0">
        <div align="center">A</div>
      </th>
      <th bgcolor="#12C9F0">
        <div align="center">B</div>
      </th>
      <th bgcolor="#12C9F0">
        <div align="center">C</div>
      </th>
      <th bgcolor="#12C9F0">
        <div align="center">D</div>
      </th>
      <th bgcolor="#12C9F0">
        <div align="center">NULL</div>
      </th>
      <th bgcolor="#12C9F0">
        <div align="center">%</div>
      </th>
    </tr>

    <?php
    $no = 1;

    // TOP 5 (pengganti LIMIT 5)
    $sqlw = sqlsrv_query($cond, "
    SELECT TOP 5
      a.no_warna,
      a.warna,
      COUNT(a.nokk) AS jml_kk
    FROM db_qc.tbl_lap_inspeksi a
    WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
      AND a.dept = 'QCF'
      AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
    GROUP BY a.no_warna, a.warna
    ORDER BY COUNT(a.nokk) DESC
", [$Awal, $Akhir]);

    while ($rw = sqlsrv_fetch_array($sqlw, SQLSRV_FETCH_ASSOC)) {

      $no_warna = $rw['no_warna'];
      $warna    = $rw['warna'];
      $jml_kk   = (int)($rw['jml_kk'] ?? 0);

      // GROUP A
      $sqlwa = sqlsrv_query($cond, "
      SELECT COUNT(a.nokk) AS jml_kk_a
      FROM db_qc.tbl_lap_inspeksi a
      WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
        AND a.[grouping] = 'A'
        AND a.no_warna = ?
        AND a.warna = ?
  ", [$Awal, $Akhir, $no_warna, $warna]);
      $rwa = sqlsrv_fetch_array($sqlwa, SQLSRV_FETCH_ASSOC);

      // GROUP B
      $sqlwb = sqlsrv_query($cond, "
      SELECT COUNT(a.nokk) AS jml_kk_b
      FROM db_qc.tbl_lap_inspeksi a
      WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
        AND a.[grouping] = 'B'
        AND a.no_warna = ?
        AND a.warna = ?
  ", [$Awal, $Akhir, $no_warna, $warna]);
      $rwb = sqlsrv_fetch_array($sqlwb, SQLSRV_FETCH_ASSOC);

      // GROUP C
      $sqlwc = sqlsrv_query($cond, "
      SELECT COUNT(a.nokk) AS jml_kk_c
      FROM db_qc.tbl_lap_inspeksi a
      WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
        AND a.[grouping] = 'C'
        AND a.no_warna = ?
        AND a.warna = ?
  ", [$Awal, $Akhir, $no_warna, $warna]);
      $rwc = sqlsrv_fetch_array($sqlwc, SQLSRV_FETCH_ASSOC);

      // GROUP D
      $sqlwd = sqlsrv_query($cond, "
      SELECT COUNT(a.nokk) AS jml_kk_d
      FROM db_qc.tbl_lap_inspeksi a
      WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
        AND a.[grouping] = 'D'
        AND a.no_warna = ?
        AND a.warna = ?
  ", [$Awal, $Akhir, $no_warna, $warna]);
      $rwd = sqlsrv_fetch_array($sqlwd, SQLSRV_FETCH_ASSOC);

      // NULL / kosong
      $sqlwn = sqlsrv_query($cond, "
      SELECT COUNT(a.nokk) AS jml_kk_null
      FROM db_qc.tbl_lap_inspeksi a
      WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
        AND (a.[grouping] = '' OR a.[grouping] IS NULL)
        AND a.no_warna = ?
        AND a.warna = ?
  ", [$Awal, $Akhir, $no_warna, $warna]);
      $rwn = sqlsrv_fetch_array($sqlwn, SQLSRV_FETCH_ASSOC);

      $a = (int)($rwa['jml_kk_a'] ?? 0);
      $b = (int)($rwb['jml_kk_b'] ?? 0);
      $c = (int)($rwc['jml_kk_c'] ?? 0);
      $d = (int)($rwd['jml_kk_d'] ?? 0);
      $n = (int)($rwn['jml_kk_null'] ?? 0);

      $persen = ($all > 0)
        ? number_format(($jml_kk / $all) * 100, 2) . " %"
        : "0.00 %";
    ?>
      <tr valign="top">
        <td align="center"><?php echo $no; ?></td>
        <td align="center"><?php echo htmlspecialchars((string)$no_warna); ?></td>
        <td align="center"><?php echo htmlspecialchars((string)$warna); ?></td>
        <td align="center"><?php echo $a; ?></td>
        <td align="center"><?php echo $b; ?></td>
        <td align="center"><?php echo $c; ?></td>
        <td align="center"><?php echo $d; ?></td>
        <td align="center"><?php echo $n; ?></td>
        <td align="center"><?php echo $persen; ?></td>
      </tr>
    <?php
      $no++;
    }
    ?>
  </table>
</body>