<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan-Baru-NCP-" . substr($_GET['awal'], 0, 10) . ".xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");

include "../../koneksi.php";
include "../../tgl_indo.php";

$Awal = isset($_GET['awal']) ? $_GET['awal'] : '';
$Akhir = isset($_GET['akhir']) ? $_GET['akhir'] : '';
$Dept = isset($_GET['dept']) ? $_GET['dept'] : '';
$Kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$Cancel = isset($_GET['cancel']) ? $_GET['cancel'] : '';
$jamA = isset($_GET['jam_awal']) ? $_GET['jam_awal'] : '';
$jamAr = isset($_GET['jam_akhir']) ? $_GET['jam_akhir'] : '';

if (strlen($jamA) == 5) {
  $start_date = $Awal . " " . $jamA;
} else {
  $start_date = $Awal . " 0" . $jamA;
}
if (strlen($jamAr) == 5) {
  $stop_date = $Akhir . " " . $jamAr;
} else {
  $stop_date = $Akhir . " 0" . $jamAr;
}

/* SQL Server lebih aman pakai detik */
$start_date_sql = (strlen($start_date) == 16) ? $start_date . ":00" : $start_date;
$stop_date_sql = (strlen($stop_date) == 16) ? $stop_date . ":00" : $stop_date;

if ($Dept == "ALL") {
  $Wdept = " ";
} else {
  $Wdept = " AND dept='$Dept' ";
}

if ($Kategori == "ALL") {
  $Wkategori = " ";
} else if ($Kategori == "hitung") {
  $Wkategori = " AND ncp_hitung='ya' ";
} else if ($Kategori == "gerobak") {
  $Wkategori = " AND kain_gerobak='ya' ";
} else if ($Kategori == "tidakhitung") {
  $Wkategori = " AND ncp_hitung='tidak' ";
}

if ($Cancel != "1") {
  $sts = " AND [status] <> 'Cancel' ";
} else {
  $sts = "  ";
}

$sqlTotalNCP = "SELECT
                  SUM(berat) AS total_ncp_all_dept
                FROM db_qc.tbl_ncp_qcf_now
                WHERE
                  tgl_buat BETWEEN CONVERT(datetime, '$start_date_sql', 120) AND CONVERT(datetime, '$stop_date_sql', 120)
                  $Wdept $Wkategori $sts";
$queryTotalNCP = sqlsrv_query($cond, $sqlTotalNCP);
$rowTotalNcp = sqlsrv_fetch_array($queryTotalNCP, SQLSRV_FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
</head>

<body>
  <?php if ($rowTotalNcp['total_ncp_all_dept'] > 0) { ?>
    <table border="0">
      <tbody>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><strong>Total NCP:</strong>
            <?= number_format($rowTotalNcp['total_ncp_all_dept'], 2, ",", ".") ?></td>
        </tr>
        <?php
        $sqlDept = "SELECT
                      dept,
                      SUM(berat) AS total_berat_dept
                    FROM db_qc.tbl_ncp_qcf_now
                    WHERE
                      tgl_buat BETWEEN CONVERT(datetime, '$start_date_sql', 120) AND CONVERT(datetime, '$stop_date_sql', 120)
                      $Wdept $Wkategori $sts
                    GROUP BY dept
                    ORDER BY total_berat_dept DESC";

        $queryDept = sqlsrv_query($cond, $sqlDept, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
        $jumlah = sqlsrv_num_rows($queryDept);

        $num = 1;
        for ($i = 1; $i <= ceil($jumlah / 4); $i++) {
          ?>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <?php
            for ($j = 0; $j < 4; $j++) {
              if ($num <= $jumlah) {
                $row = sqlsrv_fetch_array($queryDept, SQLSRV_FETCH_ASSOC);
                ?>
                <td>&nbsp;</td>
                <td style="vertical-align: top;">
                  <table border="1">
                    <thead>
                      <tr>
                        <th colspan="3" bgcolor="green"><?= $row['dept'] ?></th>
                      </tr>
                      <tr>
                        <th bgcolor="green">Quality Issue</th>
                        <th bgcolor="green">Qty</th>
                        <th bgcolor="green">%</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sqlMasalahPerDept = "SELECT
                                              dept,
                                              masalah_dominan,
                                              SUM(berat) AS berat
                                            FROM db_qc.tbl_ncp_qcf_now
                                            WHERE
                                              tgl_buat BETWEEN CONVERT(datetime, '$start_date_sql', 120) AND CONVERT(datetime, '$stop_date_sql', 120)
                                              $Wkategori $sts
                                              AND dept = '" . $row['dept'] . "'
                                            GROUP BY dept, masalah_dominan
                                            ORDER BY berat DESC";
                      $queryMasalahPerDept = sqlsrv_query($cond, $sqlMasalahPerDept);

                      $total = 0;
                      while ($row2 = sqlsrv_fetch_array($queryMasalahPerDept, SQLSRV_FETCH_ASSOC)) {
                        ?>
                        <tr>
                          <td><?= $row2['masalah_dominan'] ?></td>
                          <td><?= number_format($row2['berat'], 2, ",", ".") ?></td>
                          <td></td>
                        </tr>
                        <?php
                        $total += $row2['berat'];
                      }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td><strong>TOTAL</strong></td>
                        <td><strong><?= number_format($total, 2, ",", ".") ?></strong></td>
                        <td><strong><?= number_format($total / $rowTotalNcp['total_ncp_all_dept'] * 100, 2) ?></strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </td>
                <?php
                $num++;
              }
            }
            ?>
          </tr>
          <?php
        }
        ?>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3">
            <?php
            /* ambil dept yang muncul pada TOP 5 defect (setara group_concat distinct dept) */
            $sqlDeptTop5 = "
              WITH TopDefect AS (
                SELECT TOP 5 masalah_dominan
                FROM db_qc.tbl_ncp_qcf_now
                WHERE
                  tgl_buat BETWEEN CONVERT(datetime, '$start_date_sql', 120) AND CONVERT(datetime, '$stop_date_sql', 120)
                  $Wdept $Wkategori $sts
                GROUP BY masalah_dominan
                ORDER BY SUM(berat) DESC
              )
              SELECT
                STUFF((
                  SELECT DISTINCT ',' + t2.dept
                  FROM db_qc.tbl_ncp_qcf_now t2
                  WHERE
                    t2.masalah_dominan = td.masalah_dominan
                    AND t2.tgl_buat BETWEEN CONVERT(datetime, '$start_date_sql', 120) AND CONVERT(datetime, '$stop_date_sql', 120)
                    $Wdept $Wkategori $sts
                  FOR XML PATH(''), TYPE
                ).value('.', 'nvarchar(max)'), 1, 1, '') AS dept
              FROM TopDefect td
            ";

            $queryDept = sqlsrv_query($cond, $sqlDeptTop5);
            $deptTop5 = [];
            while ($row = sqlsrv_fetch_array($queryDept, SQLSRV_FETCH_ASSOC)) {
              $deptTop5[] = $row['dept'];
            }
            // Menggabungkan array menjadi string dengan implode
            $stringTop5 = implode(",", $deptTop5);

            // Memecah string menjadi array dengan explode
            $arrayTop5 = explode(",", $stringTop5);

            // Menghilangkan duplikat dengan array_unique
            $uniqueTop5 = array_unique($arrayTop5);
            ?>
            <table border="1">
              <thead>
                <tr>
                  <th colspan="<?= count($uniqueTop5) + 3 ?>" bgcolor="green">TOP 5 DEFECT</th>
                </tr>
                <tr>
                  <th bgcolor="green">DEFECT</th>
                  <?php foreach ($uniqueTop5 as $dept): ?>
                    <th bgcolor="green"><?= $dept ?></th>
                  <?php endforeach; ?>
                  <th bgcolor="green">TOTAL</th>
                  <th bgcolor="green">%</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "";
                foreach ($uniqueTop5 as $dept) {
                  $deptVal = str_replace("'", "''", $dept);
                  $deptAlias = str_replace("]", "]]", $dept);
                  $sql .= "SUM(CASE WHEN dept = '$deptVal' THEN berat ELSE 0 END) AS [$deptAlias],";
                }

                $sqlTop5 = "SELECT TOP 5
                              masalah_dominan,
                              $sql
                              SUM(berat) AS total_berat
                            FROM db_qc.tbl_ncp_qcf_now
                            WHERE
                              tgl_buat BETWEEN CONVERT(datetime, '$start_date_sql', 120) AND CONVERT(datetime, '$stop_date_sql', 120)
                              $Wdept $Wkategori $sts
                            GROUP BY masalah_dominan
                            ORDER BY total_berat DESC";

                $queryTop5 = sqlsrv_query($cond, $sqlTop5);

                while ($rowTop5 = sqlsrv_fetch_array($queryTop5, SQLSRV_FETCH_ASSOC)) {
                  ?>
                  <tr>
                    <td><strong><?= $rowTop5['masalah_dominan'] ?></strong></td>
                    <?php foreach ($uniqueTop5 as $dept): ?>
                      <td><?= $rowTop5[$dept] > 0 ? number_format($rowTop5[$dept], 2, ",", ".") : '' ?></td>
                    <?php endforeach; ?>
                    <td><?= number_format($rowTop5['total_berat'], 2, ",", ".") ?></td>
                    <td><?= number_format($rowTop5['total_berat'] / $rowTotalNcp['total_ncp_all_dept'] * 100, 2) ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  <?php } ?>
</body>

</html>