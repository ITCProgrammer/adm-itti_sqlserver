<?php
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=Laporan-GagalProses-".substr($_GET['awal'],0,10).".xls");//ganti nama sesuai keperluan
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
      if($jamA!="" and $jamAr!=""){ 
        $where=" AND c.tgl_update BETWEEN '$start_date' AND '$stop_date' ";}
      else if($Awal!="" and $Akhir!=""){ 
        $where=" AND DATE_FORMAT(c.tgl_update, '%Y-%m-%d') BETWEEN '$Awal' AND '$Akhir' ";}
      else{ $where=" ";}  
    $qry1=mysqli_query($con,"SELECT
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
                                if(ISNULL(TIMEDIFF(c.tgl_mulai, c.tgl_stop)),
                                a.lama_proses,
                                CONCAT(LPAD(FLOOR((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))/ 60), 2, 0), ':', LPAD(((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))%60), 2, 0))) as lama_proses,
                                a.status as sts,
                                TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai, c.tgl_stop)), a.lama_proses, CONCAT(LPAD(FLOOR((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))/ 60), 2, 0), ':', LPAD(((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))%60), 2, 0))), '%H') as jam,
                                TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai, c.tgl_stop)), a.lama_proses, CONCAT(LPAD(FLOOR((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))/ 60), 2, 0), ':', LPAD(((((hour(a.lama_proses)* 60)+ minute(a.lama_proses))-((hour(TIMEDIFF(c.tgl_mulai, c.tgl_stop))* 60)+ minute(TIMEDIFF(c.tgl_mulai, c.tgl_stop))))%60), 2, 0))), '%i') as menit,
                                a.point,
                                DATE_FORMAT(a.mulai_stop, '%Y-%m-%d') as t_mulai,
                                DATE_FORMAT(a.selesai_stop, '%Y-%m-%d') as t_selesai,
                                TIME_FORMAT(a.mulai_stop, '%H:%i') as j_mulai,
                                TIME_FORMAT(a.selesai_stop, '%H:%i') as j_selesai,
                                TIMESTAMPDIFF(minute,
                                a.mulai_stop,
                                a.selesai_stop) as lama_stop_menit,
                                a.acc_keluar,
                                if(a.proses = ''
                                or ISNULL(a.proses),
                                b.proses,
                                a.proses) as proses,
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
                                case
                                  when SUBSTR(b.kategori_warna, 1, 1) = 'D' then 'Dark'
                                  when SUBSTR(b.kategori_warna, 1, 1) = 'H' then 'Heater'
                                  when SUBSTR(b.kategori_warna, 1, 1) = 'L' then 'Light'
                                  when SUBSTR(b.kategori_warna, 1, 1) = 'M' then 'Medium'
                                  when SUBSTR(b.kategori_warna, 1, 1) = 'S' then 'Dark'
                                  when SUBSTR(b.kategori_warna, 1, 1) = 'W' then 'White'
                                end as kategori_warna,
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
                                DATE_FORMAT(c.tgl_buat, '%Y-%m-%d') as tgl_in,
                                DATE_FORMAT(a.tgl_buat, '%Y-%m-%d') as tgl_out,
                                DATE_FORMAT(c.tgl_buat, '%H:%i') as jam_in,
                                DATE_FORMAT(a.tgl_buat, '%H:%i') as jam_out,
                                if(ISNULL(a.g_shift),
                                c.g_shift,
                                a.g_shift) as shft,
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
                                coalesce(a.point2, b.target) as point2,
                                c.note_wt,
                                a.operatorpolyester,
                                a.operatorcotton,
                                p.*
                              from
                                tbl_schedule b
                              left join tbl_montemp c on
                                c.id_schedule = b.id  
                              left join tbl_hasilcelup a on
                                a.id_montemp = c.id
                              left join penyelesaian_gagalproses p on
                                p.id_schedule = b.id
                                and p.id_hasil_celup = a.id
                                and p.id_montemp = c.id
                              left join tbl_keterangan_gagalproses k on
                                k.id_hasil_celup = a.id
                                and k.id_montemp = c.id
                              where
                                a.status = 'Gagal Proses'
                                $where
                         ORDER BY b.id DESC");
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
                while ($row1 = mysqli_fetch_array($qry1)) {
                  $q_user = mysqli_query($cona,"SELECT * FROM tbl_user_tindaklanjut WHERE id = '$row1[pemberi_instruksi]'");
                  $row_user = mysqli_fetch_array($q_user);
                    $id_hasil_celup = $row1['id_hasil_celup_1'];
                    $id_schedule = $row1['id_schedule_1'];
                    $id_montemp = $row1['id_montemp_1'];
                    $rolls = extractNumberBeforeParenthesis($row1['rol']);

                    // Ambil data sebelumnya jika ada
                    $analisa = $keterangan = "";

                    $qrySaved = mysqli_query($con, "SELECT * FROM tbl_keterangan_gagalproses WHERE id_hasil_celup = '$id_hasil_celup'");
                    if ($res = mysqli_fetch_assoc($qrySaved)) {
                        $analisa = $res['analisa_penyebab'];
                        $keterangan = $res['keterangan_gagal_proses'];
                    }
                ?>
            <tr>
            <td><?= $no++; ?></td>
              <td><?= $row1['tgl_out'] ?></td>
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
                      if (isset($res['accresep']) && !empty($res['accresep'])) {
                          $accresep_id = $res['accresep'];

                          $stmt = $con->prepare("SELECT nama FROM user_acc_resep WHERE id = ?");
                          $stmt->bind_param("i", $accresep_id);
                          $stmt->execute();
                          $result = $stmt->get_result();

                          if ($row_nama = $result->fetch_assoc()) {
                              $nama_tampil = htmlspecialchars($row_nama['nama']);
                          }
                          $stmt->close();
                      }

                      echo $nama_tampil;
                  ?>
              </td>
              <td>
                  <?php
                      $nama_tampil = '';
                      if (isset($res['accresep2']) && !empty($res['accresep2'])) {
                          $accresep2_id = $res['accresep2'];

                          // Query hanya berjalan jika ID valid
                          $stmt = $con->prepare("SELECT nama FROM user_acc_resep WHERE id = ?");
                          $stmt->bind_param("i", $accresep2_id);
                          $stmt->execute();
                          $result = $stmt->get_result();

                          if ($row_nama = $result->fetch_assoc()) {
                              $nama_tampil = htmlspecialchars($row_nama['nama']);
                          }
                          $stmt->close();
                      }

                      echo $nama_tampil;
                  ?>
              </td>
              <td><?= $row1['status_resep'] ?></td>
              <td align="left"><?php echo $row1['tindak_lanjut']; ?></td>
              <td align="left">
                  <?php 
                      $list_nama = "SELECT nama FROM tbl_user_tindaklanjut t WHERE t.id = '$row1[pemberi_instruksi]'";
                      $q_nama = mysqli_query($cona, $list_nama);
                      $r_nama = mysqli_fetch_array($q_nama);
                      echo ($r_nama) ? htmlspecialchars($r_nama['nama']) : '';
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