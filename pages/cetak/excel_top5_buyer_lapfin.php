<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Top5-Buyer-Lap-Fin-" . substr($_GET['awal'], 0, 10) . ".xls"); //ganti nama sesuai keperluan
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
  $sqlball = sqlsrv_query($cond, "
    SELECT COUNT(a.nokk) AS jml_kk_all
    FROM db_qc.tbl_lap_inspeksi a
    WHERE a.proses NOT IN ('Oven','Fin 1X')
      AND a.dept = 'QCF'
      AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
  ");
  $rball = sqlsrv_fetch_array($sqlball, SQLSRV_FETCH_ASSOC);
  ?>
  <strong>Periode: <?php echo $Awal; ?> s/d <?php echo $Akhir; ?></strong><br>

  <table width="100%" border="1">
    <tr>
      <th bgcolor="#12C9F0">
        <div align="center">No</div>
      </th>
      <th bgcolor="#12C9F0">
        <div align="center">Buyer</div>
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

    $sqlby = sqlsrv_query($cond, "
      SELECT TOP 5
        RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) AS buyer,
        COUNT(a.nokk) AS jml_kk
      FROM db_qc.tbl_lap_inspeksi a
      WHERE a.proses NOT IN ('Oven','Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
      GROUP BY RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1)
      ORDER BY COUNT(a.nokk) DESC
    ");

    while ($rby = sqlsrv_fetch_array($sqlby, SQLSRV_FETCH_ASSOC)) {

      // GROUP A
      $sqlga = sqlsrv_query($cond, "
        SELECT COUNT(a.nokk) AS jml_kk_a
        FROM db_qc.tbl_lap_inspeksi a
        WHERE a.proses NOT IN ('Oven','Fin 1X')
          AND a.dept = 'QCF'
          AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
          AND a.[grouping] = 'A'
          AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = '$rby[buyer]'
      ");
      $rga = sqlsrv_fetch_array($sqlga, SQLSRV_FETCH_ASSOC);

      // GROUP B
      $sqlgb = sqlsrv_query($cond, "
        SELECT COUNT(a.nokk) AS jml_kk_b
        FROM db_qc.tbl_lap_inspeksi a
        WHERE a.proses NOT IN ('Oven','Fin 1X')
          AND a.dept = 'QCF'
          AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
          AND a.[grouping] = 'B'
          AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = '$rby[buyer]'
      ");
      $rgb = sqlsrv_fetch_array($sqlgb, SQLSRV_FETCH_ASSOC);

      // GROUP C
      $sqlgc = sqlsrv_query($cond, "
        SELECT COUNT(a.nokk) AS jml_kk_c
        FROM db_qc.tbl_lap_inspeksi a
        WHERE a.proses NOT IN ('Oven','Fin 1X')
          AND a.dept = 'QCF'
          AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
          AND a.[grouping] = 'C'
          AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = '$rby[buyer]'
      ");
      $rgc = sqlsrv_fetch_array($sqlgc, SQLSRV_FETCH_ASSOC);

      // GROUP D
      $sqlgd = sqlsrv_query($cond, "
        SELECT COUNT(a.nokk) AS jml_kk_d
        FROM db_qc.tbl_lap_inspeksi a
        WHERE a.proses NOT IN ('Oven','Fin 1X')
          AND a.dept = 'QCF'
          AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
          AND a.[grouping] = 'D'
          AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = '$rby[buyer]'
      ");
      $rgd = sqlsrv_fetch_array($sqlgd, SQLSRV_FETCH_ASSOC);

      // NULL / kosong
      $sqlgn = sqlsrv_query($cond, "
        SELECT COUNT(a.nokk) AS jml_kk_null
        FROM db_qc.tbl_lap_inspeksi a
        WHERE a.proses NOT IN ('Oven','Fin 1X')
          AND a.dept = 'QCF'
          AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
          AND (a.[grouping] = '' OR a.[grouping] IS NULL)
          AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = '$rby[buyer]'
      ");
      $rgn = sqlsrv_fetch_array($sqlgn, SQLSRV_FETCH_ASSOC);
    ?>
      <tr valign="top">
        <td align="center"><?php echo $no; ?></td>
        <td align="center"><?php echo $rby['buyer']; ?></td>
        <td align="center"><?php echo $rga['jml_kk_a']; ?></td>
        <td align="center"><?php echo $rgb['jml_kk_b']; ?></td>
        <td align="center"><?php echo $rgc['jml_kk_c']; ?></td>
        <td align="center"><?php echo $rgd['jml_kk_d']; ?></td>
        <td align="center"><?php echo $rgn['jml_kk_null']; ?></td>
        <td align="center">
          <?php
          $den = (float)($rball['jml_kk_all'] ?? 0);
          $num = (float)($rby['jml_kk'] ?? 0);
          echo $den > 0 ? number_format(($num / $den) * 100, 2) . " %" : "0.00 %";
          ?>
        </td>
      </tr>
    <?php
      $no++;
    } // end while buyer
    ?>
  </table>
</body>