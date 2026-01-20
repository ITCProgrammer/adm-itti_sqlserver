<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=lap-grouping-cocok-warna-" . date("F-Y", strtotime($_GET['awal'])) . ".xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda

include "../../koneksi.php";

set_time_limit(0);
?>

<div align="center">
  <h1>LAPORAN GROUPING COCOK WARNA DEPT. QCF</h1>
</div>
<!--script disini -->
<?php
$awal = isset($_GET['awal']) ? $_GET['awal'] : '';
$akhir = isset($_GET['akhir']) ? $_GET['akhir'] : '';
$shift = isset($_GET['shift']) ? $_GET['shift'] : '';

$timestamp = strtotime($awal);
$first = date('Y-m-01 00:00:00', $timestamp);
$last = date('Y-m-t 23:59:59', $timestamp); // A leap year!
?>
Tanggal : <?php echo $awal . " s/d " . $akhir; ?><br>
Shift : <?php echo $shift; ?>

<table width="100%" border="1">
  <thead>
    <tr>
      <th rowspan="2" bgcolor="yellow">No</th>
      <th rowspan="2" bgcolor="yellow">Buyer</th>
      <th rowspan="2" bgcolor="yellow">Item</th>
      <th rowspan="2" bgcolor="yellow">Warna</th>
      <th rowspan="2" bgcolor="yellow">No Warna</th>
      <th colspan="4" bgcolor="yellow">Grouping</th>
      <th rowspan="2" bgcolor="yellow">Total</th>
    </tr>

    <!-- Grouping -->
    <tr>
      <th bgcolor="orange">A</th>
      <th bgcolor="green">B</th>
      <th bgcolor="blue">C</th>
      <th bgcolor="pink">D</th>
    </tr>
  </thead>

  <tbody>
    <?php

    $shiftCondition = ($shift != "ALL") ? " AND [shift]='$shift' " : "";


    $query = "
      SELECT
        RIGHT(pelanggan, CHARINDEX('/', REVERSE(pelanggan) + '/') - 1) AS buyer,
        LTRIM(RTRIM(no_item)) AS no_item,
        (
          SELECT STRING_AGG(x.no_warna, ',')
          FROM (
            SELECT DISTINCT LTRIM(RTRIM(b.no_warna)) AS no_warna
            FROM db_qc.tbl_lap_inspeksi b
            WHERE b.dept = 'QCF'
              AND CAST(b.tgl_update AS date) BETWEEN '$awal' AND '$akhir'
              $shiftCondition
              AND RIGHT(b.pelanggan, CHARINDEX('/', REVERSE(b.pelanggan) + '/') - 1)
                  = RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1)
              AND LTRIM(RTRIM(b.no_item)) = LTRIM(RTRIM(a.no_item))
              AND ISNULL(LTRIM(RTRIM(b.[grouping])), '') <> ''
          ) x
        ) AS no_warna_group
      FROM db_qc.tbl_lap_inspeksi a
      WHERE a.dept = 'QCF'
        AND CAST(a.tgl_update AS date) BETWEEN '$awal' AND '$akhir'
        $shiftCondition
        AND ISNULL(LTRIM(RTRIM(a.[grouping])), '') <> ''
      GROUP BY
        RIGHT(pelanggan, CHARINDEX('/', REVERSE(pelanggan) + '/') - 1),
        LTRIM(RTRIM(no_item))
      ORDER BY buyer ASC
    ";

    $result = sqlsrv_query($cond, $query);

    $no = 1;
    $satu = 1;
    $satuTemp = 1;

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

      $no_warna_group_string = "'" . implode("','", array_map('trim', explode(',', $row['no_warna_group'] ?? ''))) . "'";

      $query2 = "
        SELECT COUNT(*) AS total_warna
        FROM (
          SELECT
            a.no_warna,
            a.warna,
            STRING_AGG(a.[grouping], ',') AS groupings
          FROM db_qc.tbl_lap_inspeksi a
          WHERE a.dept = 'QCF'
            AND CAST(a.tgl_update AS date) BETWEEN '$awal' AND '$akhir'
            $shiftCondition
            AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = '$row[buyer]'
            AND ISNULL(LTRIM(RTRIM(a.[grouping])), '') <> ''
          GROUP BY
            a.no_warna,
            a.warna
        ) temp
      ";

      $result2 = sqlsrv_query($cond, $query2);
      $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);
      $total_warna = (int)($row2['total_warna'] ?? 0);

      $query3 = "
        SELECT
          a.no_warna,
          a.warna,
          STRING_AGG(a.[grouping], ',') AS groupings
        FROM db_qc.tbl_lap_inspeksi a
        WHERE a.dept = 'QCF'
          AND CAST(a.tgl_update AS date) BETWEEN '$awal' AND '$akhir'
          $shiftCondition
          AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = '$row[buyer]'
          AND LTRIM(RTRIM(a.no_item)) = '$row[no_item]'
          AND LTRIM(RTRIM(a.no_warna)) IN ($no_warna_group_string)
          AND ISNULL(LTRIM(RTRIM(a.[grouping])), '') <> ''
        GROUP BY
          a.no_warna,
          a.warna
      ";

      $result3 = sqlsrv_query($cond, $query3, [], ["Scrollable" => SQLSRV_CURSOR_KEYSET]);
      $totalRowResult3 = sqlsrv_num_rows($result3);

      $dua = 1;

      while ($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {

        $A = 0;
        $B = 0;
        $C = 0;
        $D = 0;

        foreach (explode(",", $row3['groupings'] ?? '') as $group) {
          $group = trim($group);
          if ($group == "A") $A++;
          if ($group == "B") $B++;
          if ($group == "C") $C++;
          if ($group == "D") $D++;
        }

        $totalABCD = $A + $B + $C + $D;
    ?>
        <tr>
          <?php if ($satu > 0): ?>
            <td rowspan="<?= $total_warna ?>"><?= $no++ ?></td>
            <td rowspan="<?= $total_warna ?>"><?= $row['buyer'] ?></td>
          <?php endif; ?>

          <?php if ($dua > 0): ?>
            <td rowspan="<?= $totalRowResult3 ?>"><?= $row['no_item'] ?></td>
          <?php endif; ?>

          <td><?= $row3['warna'] ?></td>
          <td><?= $row3['no_warna'] ?></td>
          <td bgcolor="orange"><?= $A > 0 ? $A : '' ?></td>
          <td bgcolor="green"><?= $B > 0 ? $B : '' ?></td>
          <td bgcolor="blue"><?= $C > 0 ? $C : '' ?></td>
          <td bgcolor="pink"><?= $D > 0 ? $D : '' ?></td>
          <td><?= $totalABCD > 0 ? $totalABCD : '' ?></td>
        </tr>
    <?php

        if ($satuTemp < $total_warna) {
          $satuTemp++;
          $satu = 0;
          $dua = 0;
        } else {
          $satuTemp = 1;
          $satu = 1;
          $dua = 1;
        }
      }
    }
    ?>
  </tbody>
</table>