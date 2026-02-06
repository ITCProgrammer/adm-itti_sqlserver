<?php
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=Laporan-GagalProses-".substr($_GET['awal'],0,10).".xls");//ganti nama sesuai keperluan
    header("Pragma: no-cache");
    header("Expires: 0");

    include "../../koneksi2.php";
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
      if($jamA!="" and $jamAr!=""){ 
        $where=" AND c.tgl_update BETWEEN '$start_date' AND '$stop_date' ";}
      else if($Awal!="" and $Akhir!=""){ 
        $where = " AND CONVERT(date, c.tgl_update) BETWEEN '$Awal' AND '$Akhir' ";}
      else{ $where=" ";}
      $sql = "SELECT
            b.id as id_schedule_1,
            c.id as id_montemp_1,
            a.id as id_hasil_celup_1,
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
            a.kd_stop,
            a.mulai_stop,
            a.selesai_stop,
            a.ket,
            CASE
                WHEN c.tgl_mulai IS NULL OR c.tgl_stop IS NULL THEN
                    CONVERT(varchar(5), ISNULL(TRY_CONVERT(time, a.lama_proses), '00:00:00'), 108)
                ELSE
                    CONVERT(varchar(5),
                        DATEADD(
                            MINUTE,
                            (
                                DATEDIFF(MINUTE, 0, ISNULL(TRY_CONVERT(time, a.lama_proses), '00:00:00'))
                                - DATEDIFF(MINUTE, c.tgl_stop, c.tgl_mulai)
                            ),
                            0
                        ),
                        108
                    )
            END AS lama_proses,
            a.status as sts,
            LEFT(
                CASE
                    WHEN c.tgl_mulai IS NULL OR c.tgl_stop IS NULL THEN
                        CONVERT(varchar(5), ISNULL(TRY_CONVERT(time, a.lama_proses), '00:00:00'), 108)
                    ELSE
                        CONVERT(varchar(5),
                            DATEADD(
                                MINUTE,
                                (
                                    DATEDIFF(MINUTE, 0, ISNULL(TRY_CONVERT(time, a.lama_proses), '00:00:00'))
                                    - DATEDIFF(MINUTE, c.tgl_stop, c.tgl_mulai)
                                ),
                                0
                            ),
                            108
                        )
                END, 2
            ) AS jam,
            RIGHT(
                CASE
                    WHEN c.tgl_mulai IS NULL OR c.tgl_stop IS NULL THEN
                        CONVERT(varchar(5), ISNULL(TRY_CONVERT(time, a.lama_proses), '00:00:00'), 108)
                    ELSE
                        CONVERT(varchar(5),
                            DATEADD(
                                MINUTE,
                                (
                                    DATEDIFF(MINUTE, 0, ISNULL(TRY_CONVERT(time, a.lama_proses), '00:00:00'))
                                    - DATEDIFF(MINUTE, c.tgl_stop, c.tgl_mulai)
                                ),
                                0
                            ),
                            108
                        )
                END, 2
            ) AS menit,
            a.point,
            CONVERT(date, a.mulai_stop)   as t_mulai,
            CONVERT(date, a.selesai_stop) as t_selesai,
            CONVERT(varchar(5), a.mulai_stop, 108)   as j_mulai,
            CONVERT(varchar(5), a.selesai_stop, 108) as j_selesai,
            DATEDIFF(MINUTE, a.mulai_stop, a.selesai_stop) as lama_stop_menit,
            a.acc_keluar,
            CASE
              WHEN a.proses = '' OR a.proses IS NULL THEN b.proses
              ELSE a.proses
            END as proses,
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
            CASE
              WHEN LEFT(b.kategori_warna, 1) = 'D' THEN 'Dark'
              WHEN LEFT(b.kategori_warna, 1) = 'H' THEN 'Heater'
              WHEN LEFT(b.kategori_warna, 1) = 'L' THEN 'Light'
              WHEN LEFT(b.kategori_warna, 1) = 'M' THEN 'Medium'
              WHEN LEFT(b.kategori_warna, 1) = 'S' THEN 'Dark'
              WHEN LEFT(b.kategori_warna, 1) = 'W' THEN 'White'
            END as kategori_warna,
            b.target,
            c.l_r,
            c.rol,
            c.bruto,
            c.colorist,
            c.pakai_air,
            c.no_program,
            c.pjng_kain,
            c.cycle_time,
            c.rpm,
            c.tekanan,
            c.nozzle,
            c.plaiter,
            c.blower,
            CONVERT(date, c.tgl_buat) as tgl_in,
            CONVERT(date, a.tgl_buat) as tgl_out,
            CONVERT(varchar(5), c.tgl_buat, 108) as jam_in,
            CONVERT(varchar(5), a.tgl_buat, 108) as jam_out,
            ISNULL(a.g_shift, c.g_shift) as shft,
            a.penanggungjawabbuyer,
            a.operator_keluar,
            a.k_resep,
            a.status,
            a.proses_point,
            a.analisa,
            b.nokk,
            a.status_resep,
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
            a.tambah_dyestuff,
            a.arah_warna,
            a.status_warna,
            COALESCE(a.point2, b.target) as point2,
            c.note_wt,
            a.operatorpolyester,
            a.operatorcotton,
            p.*
          FROM db_dying.tbl_schedule b
          LEFT JOIN db_dying.tbl_montemp c
            ON c.id_schedule = b.id
          LEFT JOIN db_dying.tbl_hasilcelup a
            ON a.id_montemp = c.id
          LEFT JOIN db_dying.penyelesaian_gagalproses p
            ON p.id_schedule = b.id
          AND p.id_hasil_celup = a.id
          AND p.id_montemp = c.id
          LEFT JOIN db_dying.tbl_keterangan_gagalproses k
            ON k.id_hasil_celup = a.id
          AND k.id_montemp = c.id
          WHERE a.status = 'Gagal Proses'
          $where
          ORDER BY b.id DESC";
          $qry1 = sqlsrv_query($con, $sql);  
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
</head>
<body>
  <h2 align="center">Laporan Gagal Proses</h2>
  <?php if(!empty($jamA) || !empty($jamAr)):?>
    <h3 align="center">Periode <?php echo tanggal_indo($Awal).' '.$jamA . " s/d " . tanggal_indo($Akhir).' '. $jamAr; ?></h3>
  <?php else:?>
    <h3 align="center">Periode <?php echo tanggal_indo($Awal) . " s/d " . tanggal_indo($Akhir); ?></h5>
  <?php endif;?>
  <br>
    <table border="1">
      <thead><tr>
            <td bgcolor='#99daffff' rowspan=2><div align="center">No</div></td>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Tgl Celup</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">No KK</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">No Demand</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Pelanggan</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Buyer</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">PO</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Order</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Item</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Jenis Kain</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Warna</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">No Warna</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Lot</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Roll</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Bruto</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">No Mesin</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Status</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Proses</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Status Warna</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Analisa Penyebab</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Dept Penyebab</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Colorist Dye</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Keterangan</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Penanggung Jawab Buyer</div></th>
            <th bgcolor="#99daffff" colspan=2><div align="center">ACC Resep Baru</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Status Resep</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Tindak Lanjut</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Pemberi Instruksi Tindak Lanjut</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Hasil Tindak Lanjut</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Tindakan</div></th>
            <th bgcolor="#99daffff" rowspan=2><div align="center">Keterangan Tindakan</div></th>
            </tr>
            <tr>
              <th>Colorist 1</th>
              <th>Colorist 2</th>
            </tr>
        </thead>
        <tbody>
          <?php
            function extractNumberBeforeParenthesis($string) {
                preg_match('/^(\d+)/', $string, $matches);
                return $matches[1] ?? $string;
            }
            $t_roll = 0;
            $t_bruto = 0;
                $no = 1;
                while ($row1 = sqlsrv_fetch_array($qry1)) {
                  $q_user = sqlsrv_query($cona, "SELECT TOP 1 * FROM db_adm.tbl_user_tindaklanjut WHERE id = ?", [$row1['pemberi_instruksi']]);
                  if ($q_user === false) { die(print_r(sqlsrv_errors(), true)); }
                  $row_user = sqlsrv_fetch_array($q_user, SQLSRV_FETCH_ASSOC);
                  sqlsrv_free_stmt($q_user);
                    $id_hasil_celup = $row1['id_hasil_celup_1'];
                    $id_schedule = $row1['id_schedule_1'];
                    $id_montemp = $row1['id_montemp_1'];
                    $rolls = extractNumberBeforeParenthesis($row1['rol']);

                    // Ambil data sebelumnya jika ada
                    $analisa = $keterangan = "";

                    $sql = "SELECT TOP 1 * 
                              FROM db_dying.tbl_keterangan_gagalproses 
                              WHERE id_hasil_celup = ?";

                      $stmt = sqlsrv_query($con, $sql, [$id_hasil_celup]);
                    if ($res = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                          $analisa = $res['analisa_penyebab'];
                          $keterangan = $res['keterangan_gagal_proses'];
                      }
                ?>
            <tr>
            <td><?= $no++; ?></td>
              <td><?php echo !empty($row1['tgl_out']) ? $row1['tgl_out']->format('Y-m-d') : ''; ?></td>
              <td><?= $row1['nokk'] ?></td>
              <td><?= $row1['nodemand'] ?></td>
              <td><?= $row1['langganan'] ?></td>
              <td><?= $row1['buyer'] ?></td>
              <td><?= $row1['po'] ?></td>
              <td><?= $row1['no_order'] ?></td>
              <td><?= $row1['no_item'] ?></td>
              <td><?= $row1['jenis_kain'] ?></td>
              <td><?= $row1['warna'] ?></td>
              <td><?= $row1['no_warna'] ?></td>
              <td align="right"><?= $row1['lot'] ?></td>
              <td align="right"><?= $row1['rol'] ?></td>
              <td align="right"><?= $row1['bruto'] ?></td>
              <td align="center"><?= $row1['no_mesin'] ?></td>
              <td><?= $row1['status'] ?></td>
              <td><?= $row1['proses'] ?></td>
              <td><?= $row1['status_warna'] ?></td>
              

              <!-- Editable: Analisa -->
              <td>
                  <?= $analisa ?>
              </td>
              <td>
                  <?php if(!empty($res['dept_penyebab'])){
                    echo $res['dept_penyebab'];
                  }else{
                    echo ""; }
                    ?>
              </td>
              <td align="left"><?php echo $row1['acc_keluar']; ?></td>
              <!-- Editable: Keterangan -->
              <td>
                  <?= $keterangan ?>
              </td>
              <td>
                  <?= $row1['penanggungjawabbuyer'];?>
              </td>
              <td>
                  <?php
                    $nama_tampil = '';

                    if (!empty($res['accresep'])) {
                        $accresep_id = (int)$res['accresep'];

                        $sql = "SELECT TOP 1 nama FROM db_dying.user_acc_resep WHERE id = ?";
                        $stmt = sqlsrv_query($con, $sql, [$accresep_id]);

                        if ($stmt === false) {
                            die(print_r(sqlsrv_errors(), true));
                        }

                        $row_nama = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                        if ($row_nama) {
                            $nama_tampil = htmlspecialchars($row_nama['nama']);
                        }

                        sqlsrv_free_stmt($stmt);
                    }

                    echo $nama_tampil;
                  ?>
              </td>
              <td>
                  <?php
                    $nama_tampil = '';

                    if (!empty($res['accresep2'])) {
                        $accresep_id = (int)$res['accresep2'];

                        $sql = "SELECT TOP 1 nama FROM db_dying.user_acc_resep WHERE id = ?";
                        $stmt = sqlsrv_query($con, $sql, [$accresep_id]);

                        if ($stmt === false) {
                            die(print_r(sqlsrv_errors(), true));
                        }

                        $row_nama = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                        if ($row_nama) {
                            $nama_tampil = htmlspecialchars($row_nama['nama']);
                        }

                        sqlsrv_free_stmt($stmt);
                    }

                    echo $nama_tampil;
                  ?>
              </td>
              <td><?= $row1['status_resep'] ?></td>
              <td align="left"><?php echo $row1['tindak_lanjut']; ?></td>
              <td align="left">
                  <?php 
                      $stmtNama = sqlsrv_query(
                                                  $cona,
                                                  "SELECT TOP 1 nama FROM db_adm.tbl_user_tindaklanjut WHERE id = ?",
                                                  [$row1['pemberi_instruksi']]
                                              );
                      if ($stmtNama === false) { die(print_r(sqlsrv_errors(), true)); }
                      $r_nama = sqlsrv_fetch_array($stmtNama, SQLSRV_FETCH_ASSOC);
                      echo htmlspecialchars($r_nama['nama'] ?? '');
                      sqlsrv_free_stmt($stmtNama);
                  ?>
              </td>
              <td align="left"><?php echo $row1['hasil_tindak_lanjut']; ?></td>
              <td align="left"><?php echo $row1['tindakan_tindak_lanjut']; ?></td>
              <td align="left"><?php echo $row1['keterangan_tindak_lanjut']; ?></td>
            </tr>
          <?php
              $t_roll += $rolls;
              $t_bruto += $row1['bruto'];

          }?>
        </tbody>
                    <tfoot>
                      <tr>
                        <td colspan='13'><strong>TOTAL</strong></td>
                        <td><strong><?php echo $t_roll;?></strong></td>
                        <td><strong><?php echo $t_bruto;?></strong></td>
                        <td colspan='11'></td>
                      </tr>
                    </tfoot>
                  </table>
</body>
<script>
   
</script>
</html>