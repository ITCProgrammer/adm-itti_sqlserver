<?php
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=Laporan-TolakBasah-".substr($_GET['awal'],0,10).".xls");//ganti nama sesuai keperluan
    header("Pragma: no-cache");
    header("Expires: 0");

    include "../../koneksi.php";
    include "../../tgl_indo.php";

    $Awal  = isset($_GET['awal']) ? $_GET['awal'] : '';
    $Akhir = isset($_GET['akhir']) ? $_GET['akhir'] : '';
    $jamA  = isset($_GET['jam_awal']) ? $_GET['jam_awal'] : '';
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
    if ($Awal != "" && $Akhir != "" && $jamA == "" && $jamAr == "") {
      $where = " AND CONVERT(date, t.tgl_update) BETWEEN CONVERT(date, '$Awal') AND CONVERT(date, '$Akhir') ";
    } else if ($Awal != "" && $Akhir != "" && $jamA != "" && $jamAr != "") {
      $where = " AND t.tgl_update BETWEEN CONVERT(datetime, '$start_date', 120) AND CONVERT(datetime, '$stop_date', 120) ";
    } else {
      $where = " ";
    }
    $qry1 = sqlsrv_query($cond, "
      SELECT
        t.*,
        p.hasil_tindak_lanjut,
        p.tindak_lanjut,
        p.tindakan,
        p.pemberi_instruksi,
        p.keterangan
      FROM db_qc.tbl_cocok_warna_dye t
      LEFT JOIN db_qc.penyelesaian_tolakbasah p
        ON t.id = p.id_cocok_warna
      WHERE
        t.status_warna LIKE '%TOLAK BASAH%' $where
      ORDER BY
        t.id DESC
    ");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
</head>

<body>
  <h2 align="center">Laporan Tolak Basah</h2>
  <?php if(!empty($jamA) || !empty($jamAr)):?>
    <h3 align="center">Periode <?php echo tanggal_indo($Awal).' '.$jamA . " s/d " . tanggal_indo($Akhir).' '. $jamAr; ?></h3>
  <?php else:?>
    <h3 align="center">Periode <?php echo tanggal_indo($Awal) . " s/d " . tanggal_indo($Akhir); ?></h5>
  <?php endif;?>
  <br>
    <table border="1">
      <thead>
          <tr>
            <th bgcolor="#99daffff"><div align="center">No</div></th>
            <th bgcolor="#99daffff"><div align="center">Tgl Celup</div></th>
            <th bgcolor="#99daffff"><div align="center">No KK</div></th>
            <th bgcolor="#99daffff"><div align="center">No Demand</div></th>
            <th bgcolor="#99daffff"><div align="center">Pelanggan</div></th>
            <th bgcolor="#99daffff"><div align="center">Buyer</div></th>
            <th bgcolor="#99daffff"><div align="center">PO</div></th>
            <th bgcolor="#99daffff"><div align="center">Order</div></th>
            <th bgcolor="#99daffff"><div align="center">Item</div></th>
            <th bgcolor="#99daffff"><div align="center">Jenis Kain</div></th>
            <th bgcolor="#99daffff"><div align="center">Warna</div></th>
            <th bgcolor="#99daffff"><div align="center">No Warna</div></th>
            <th bgcolor="#99daffff"><div align="center">Lot</div></th>
            <th bgcolor="#99daffff"><div align="center">Roll</div></th>
            <th bgcolor="#99daffff"><div align="center">Bruto</div></th>
            <th bgcolor="#99daffff"><div align="center">No Mesin</div></th>
            <th bgcolor="#99daffff"><div align="center">Status Warna</div></th>
            <th bgcolor="#99daffff"><div align="center">Colorist Dye</div></th>
            <th bgcolor="#99daffff"><div align="center">Colorist Qcf</div></th>
            <th bgcolor="#99daffff"><div align="center">Keterangan</div></th>
            <th bgcolor="#99daffff"><div align="center">Hasil Tindak Lanjut</div></th>
            <th bgcolor="#99daffff"><div align="center">Tindakan</div></th>
            <th bgcolor="#99daffff"><div align="center">Pemberi Tindakan</div></th>
            <th bgcolor="#99daffff"><div align="center">Keterangan Tindakan</div></th>
            </tr>
        </thead>
        <tbody>
		<?php
            $no      = 1;
            $t_roll  = 0;
            $t_bruto = 0;
            while ($row1 = sqlsrv_fetch_array($qry1, SQLSRV_FETCH_ASSOC)) {
                $q_user   = sqlsrv_query($cona, "SELECT * FROM db_adm.tbl_user_tindaklanjut WHERE id = '$row1[pemberi_instruksi]'");
                $row_user = sqlsrv_fetch_array($q_user, SQLSRV_FETCH_ASSOC);
                $qdye = sqlsrv_query($con, "SELECT TOP 1
                            b.langganan,
                            b.po,
                            b.no_order,
                            b.jenis_kain,
                            CASE
                                WHEN b.no_item = '' OR b.no_item IS NULL THEN b.no_hanger
                                ELSE b.no_item
                            END AS no_item,
                            b.warna,
                            b.no_warna,
                            b.no_mesin,
                            a.acc_keluar,
                            a.tgl_buat,
                            a.nokk,
                            b.rol,
                            b.bruto
                        FROM
                            db_dying.tbl_hasilcelup a
                            LEFT JOIN db_dying.tbl_montemp c ON a.id_montemp = c.id
                            LEFT JOIN db_dying.tbl_schedule b ON c.id_schedule = b.id
                        WHERE
                            a.nodemand LIKE '%".$row1['nodemand']."%'
                        ORDER BY
                            a.id DESC");
                $row_dye = sqlsrv_fetch_array($qdye, SQLSRV_FETCH_ASSOC);
                $pos     = strpos($row1['pelanggan'], "/");
                if ($pos > 0) {
                    $lgg1 = substr($row1['pelanggan'], 0, $pos);
                    $byr1 = substr($row1['pelanggan'], $pos + 1, 100);
                } else {
                    $lgg1 = $row1['pelanggan'];
                    $byr1 = substr($row1['pelanggan'], $pos, 100);
                }
            ?>
          <tr>
            <td align="left"><?php echo $no++;?></td>
            <td align="left">
              <?= ($row1['tgl_celup'] instanceof DateTime)
                    ? $row1['tgl_celup']->format('Y-m-d')
                    : $row1['tgl_celup']; ?>
            </td>
            <td align="left"><?php echo $row_dye['nokk']?></td>
            <td align="left"><a target="_BLANK" href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=<?php echo $row1['nodemand'];?>">`<?php echo $row1['nodemand'];?></a></td>
            <td align="center"><?php echo $lgg1?></td>
            <td align="center"><?php echo $byr1?></td>
            <td align="center"><?php echo $row1['no_po'];?></td>
            <td align="center"><?php echo $row1['no_order'];?></td>
            <td align="center"><?php echo $row1['no_item'];?></td>
            <td align="center"><?php echo $row1['jenis_kain'];?></td>
            <td align="left"><?php echo $row1['warna']; ?></td>
            <td align="left"><?php echo $row1['no_warna']; ?></td>
            <td align="right"><?php echo $row1['lot']; ?></td>
            <td align="right"><?php echo $row1['jml_roll'] ?></td>
            <td align="right"><?php echo $row1['bruto'] ?></td>
            <td align="center"><?php echo $row_dye['no_mesin'] ?></td>
            <td align="left"><?php echo $row1['status_warna'] ?></td>
            <td align="left"><?php echo ! empty($row_dye['acc_keluar']) ? $row_dye['acc_keluar'] : $row1['colorist_dye']; ?></td>
            <td align="center"><?php echo $row1['colorist_qcf'] ?></td>
            <td align="left"><?php echo $row1['ket'] ?></td>
            <td align="left"><?php echo $row1['hasil_tindak_lanjut'] ?></td>
            <td align="left"><?php echo $row1['tindakan'] ?></td>
            <td align="center"><?php if(!empty($row_user['nama'])){echo $row_user['nama'];}else{echo '';} ?></td>
            <td align="left"><?php if(!empty($row1['tindak_lanjut'])){echo htmlspecialchars($row1['tindak_lanjut'], ENT_QUOTES, 'UTF-8');}else{echo '';} ?></td>
            </tr>
          <?php
              $t_roll += $row1['jml_roll'];
              $t_bruto += $row1['bruto'];

          }?>
        </tbody>
                    <tfoot>
                      <tr>
                        <td colspan='13'><strong>TOTAL</strong></td>
                        <!-- <td colspan='12'></td> -->
                        <!-- <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td> -->
                        <td><strong><?php echo $t_roll;?></strong></td>
                        <td><strong><?php echo $t_bruto;?></strong></td>
                        <td colspan='9'></td>
                      </tr>
                    </tfoot>
                  </table>
</body>

</html>