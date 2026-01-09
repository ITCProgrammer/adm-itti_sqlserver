<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=lap-grouping-cocok-warna-".date("F-Y", strtotime($_GET['awal'])).".xls");//ganti nama sesuai keperluan
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

    $shiftCondition = ($shift != "ALL") ? " AND `shift`='$shift' " : "";

    $query = "select
                SUBSTRING_INDEX(pelanggan, '/',-1) as buyer,
                trim(no_item) as no_item,
                group_concat(distinct trim(no_warna)) as no_warna_group
              from
                tbl_lap_inspeksi
              where
                `dept` = 'QCF'
                and DATE_FORMAT( CONCAT(tgl_update, ' ', jam_update), '%Y-%m-%d') between '$awal' AND '$akhir' $shiftCondition
                and `grouping` != ''
              group by
                buyer,
                no_item
              order by
                buyer asc";

    $result = mysqli_query($cond, $query);

    $no = 1;
    $satu = 1;
    $satuTemp = 1;
    while ($row = mysqli_fetch_array($result)) {
      $no_warna_group = explode(',', $row['no_warna_group']);
      $no_warna_group_string = "'" . implode("', '", explode(',', $row['no_warna_group'])) . "'";

      $query2 = "select 
                    count(*) as total_warna
                  from (
                  select
                    no_warna,
                    warna,
                    group_concat(`grouping`) as groupings
                  from
                    tbl_lap_inspeksi
                  where
                    `dept` = 'QCF'
                    and DATE_FORMAT( CONCAT(tgl_update, ' ', jam_update), '%Y-%m-%d') between '$awal' AND '$akhir' $shiftCondition
                    and SUBSTRING_INDEX(pelanggan, '/',-1) = '$row[buyer]'
                    and `grouping` != ''
                  group by
                    SUBSTRING_INDEX(pelanggan, '/',-1),
                    no_item,
                    no_warna) temp";

      $result2 = mysqli_query($cond, $query2);
      $row2 = mysqli_fetch_array($result2);
      $total_warna = $row2['total_warna'];

      $query3 = "select
                  no_warna,
                  warna,
                  group_concat(`grouping`) as groupings
                from
                  tbl_lap_inspeksi
                where
                  `dept` = 'QCF'
                  and DATE_FORMAT( CONCAT(tgl_update, ' ', jam_update), '%Y-%m-%d') between '$awal' AND '$akhir' $shiftCondition
                  and SUBSTRING_INDEX(pelanggan, '/',-1) = '$row[buyer]'
                  and no_item = '$row[no_item]'
                  and no_warna in ($no_warna_group_string)
                  and grouping != ''
                group by
                  no_warna";

      $result3 = mysqli_query($cond, $query3);
      $totalRowResult3 = mysqli_num_rows($result3);

      $dua = 1;
      while ($row3 = mysqli_fetch_array($result3)) {

        $A = 0;
        $B = 0;
        $C = 0;
        $D = 0;
        foreach (explode(",", $row3['groupings']) as $group) {
          if ($group == "A") {
            $A++;
          }
          if ($group == "B") {
            $B++;
          }
          if ($group == "C") {
            $C++;
          }
          if ($group == "D") {
            $D++;
          }
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
        if ($satuTemp < $row2['total_warna']) {
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