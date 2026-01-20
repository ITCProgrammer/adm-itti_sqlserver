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
  $sqlball = sqlsrv_query($cond, "
    SELECT COUNT(a.nokk) AS jml_kk_all
    FROM db_qc.tbl_lap_inspeksi a
    WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
      AND a.dept = 'QCF'
      AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
  ", [$Awal, $Akhir]);

  $rball = sqlsrv_fetch_array($sqlball, SQLSRV_FETCH_ASSOC);
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

      // GROUP A
      $sqlwa = sqlsrv_query($cond, "
      SELECT
        a.no_warna,
        a.warna,
        COUNT(a.nokk) AS jml_kk_a
      FROM db_qc.tbl_lap_inspeksi a
      WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
        AND a.[grouping] = 'A'
        AND a.no_warna = ?
        AND a.warna = ?
      GROUP BY
        a.no_warna,
        a.warna
    ", [$Awal, $Akhir, $rw['no_warna'], $rw['warna']]);
      $rwa = sqlsrv_fetch_array($sqlwa, SQLSRV_FETCH_ASSOC);

      // GROUP B
      $sqlwb = sqlsrv_query($cond, "
      SELECT
        a.no_warna,
        a.warna,
        COUNT(a.nokk) AS jml_kk_b
      FROM db_qc.tbl_lap_inspeksi a
      WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
        AND a.[grouping] = 'B'
        AND a.no_warna = ?
        AND a.warna = ?
      GROUP BY
        a.no_warna,
        a.warna
    ", [$Awal, $Akhir, $rw['no_warna'], $rw['warna']]);
      $rwb = sqlsrv_fetch_array($sqlwb, SQLSRV_FETCH_ASSOC);

      // GROUP C
      $sqlwc = sqlsrv_query($cond, "
      SELECT
        a.no_warna,
        a.warna,
        COUNT(a.nokk) AS jml_kk_c
      FROM db_qc.tbl_lap_inspeksi a
      WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
        AND a.[grouping] = 'C'
        AND a.no_warna = ?
        AND a.warna = ?
      GROUP BY
        a.no_warna,
        a.warna
    ", [$Awal, $Akhir, $rw['no_warna'], $rw['warna']]);
      $rwc = sqlsrv_fetch_array($sqlwc, SQLSRV_FETCH_ASSOC);

      // GROUP D
      $sqlwd = sqlsrv_query($cond, "
      SELECT
        a.no_warna,
        a.warna,
        COUNT(a.nokk) AS jml_kk_d
      FROM db_qc.tbl_lap_inspeksi a
      WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
        AND a.[grouping] = 'D'
        AND a.no_warna = ?
        AND a.warna = ?
      GROUP BY
        a.no_warna,
        a.warna
    ", [$Awal, $Akhir, $rw['no_warna'], $rw['warna']]);
      $rwd = sqlsrv_fetch_array($sqlwd, SQLSRV_FETCH_ASSOC);

      // NULL / kosong
      $sqlwn = sqlsrv_query($cond, "
      SELECT
        a.no_warna,
        a.warna,
        COUNT(a.nokk) AS jml_kk_null
      FROM db_qc.tbl_lap_inspeksi a
      WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
        AND a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
        AND (a.[grouping] = '' OR a.[grouping] IS NULL)
        AND a.no_warna = ?
        AND a.warna = ?
      GROUP BY
        a.no_warna,
        a.warna
    ", [$Awal, $Akhir, $rw['no_warna'], $rw['warna']]);
      $rwn = sqlsrv_fetch_array($sqlwn, SQLSRV_FETCH_ASSOC);
    ?>
      <tr valign="top">
        <td align="center"><?php echo $no; ?></td>
        <td align="center"><?php echo $rw['no_warna']; ?></td>
        <td align="center"><?php echo $rw['warna']; ?></td>
        <td align="center"><?php echo $rwa['jml_kk_a'] ?? 0; ?></td>
        <td align="center"><?php echo $rwb['jml_kk_b'] ?? 0; ?></td>
        <td align="center"><?php echo $rwc['jml_kk_c'] ?? 0; ?></td>
        <td align="center"><?php echo $rwd['jml_kk_d'] ?? 0; ?></td>
        <td align="center"><?php echo $rwn['jml_kk_null'] ?? 0; ?></td>
        <td align="center">
          <?php
          $den = (float)($rball['jml_kk_all'] ?? 0);
          $num = (float)($rw['jml_kk'] ?? 0);
          echo $den > 0 ? number_format(($num / $den) * 100, 2) . " %" : "0.00 %";
          ?>
        </td>
      </tr>
    <?php
      $no++;
    }
    ?>
  </table>
</body>