<?php
  // ini_set("error_reporting", 0);
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=report-ketrecipe-" . substr($_GET['awal'], 0, 10) . ".xls"); //ganti nama sesuai keperluan
  header("Pragma: no-cache");
  header("Expires: 0");
  //disini script laporan anda
?>
<?php
  ini_set("error_reporting", 1);
  include "../../koneksi.php";
  include "../../koneksiLAB.php";
  include "../../tgl_indo.php";
  //--
  $idkk = $_REQUEST['idkk'];
  $act = $_GET['g'];
  //-
  $qTgl = mysqli_query($con, "SELECT DATE_FORMAT(now(),'%Y-%m-%d') as tgl_skrg, DATE_FORMAT(now(),'%Y-%m-%d')+ INTERVAL 1 DAY as tgl_besok");
  $rTgl = mysqli_fetch_array($qTgl);
  $Awal = $_GET['awal'];
  $jamA = $_GET['jam_awal'];
  $Akhir = $_GET['akhir'];
  $jamAr = $_GET['jam_akhir'];
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
  $shft = $_GET['shft'];
?>

<body>
  <strong>Periode: <?php echo $start_date; ?> s/d <?php echo $stop_date; ?></strong><br>
  <table width="100%" border="1">
    <tr>
      <th rowspan="2" bgcolor="#99FF99">NO.</th>
      <th rowspan="2" bgcolor="#99FF99">SHIFT</th>
      <th rowspan="2" bgcolor="#99FF99">NO MC</th>
      <th rowspan="2" bgcolor="#99FF99">KAPASITAS</th>
      <th rowspan="2" bgcolor="#99FF99">LANGGANAN</th>
      <th rowspan="2" bgcolor="#99FF99">BUYER</th>
      <th rowspan="2" bgcolor="#99FF99">NO ORDER</th>
      <th rowspan="2" bgcolor="#99FF99">JENIS KAIN</th>
      <th rowspan="2" bgcolor="#99FF99">WARNA</th>
      <th rowspan="2" bgcolor="#99FF99">K.W</th>
      <th rowspan="2" bgcolor="#99FF99">LOT</th>
      <th rowspan="2" bgcolor="#99FF99">ROLL</th>
      <th rowspan="2" bgcolor="#99FF99">QTY</th>
      <th rowspan="2" bgcolor="#99FF99">PROSES</th>
      <th rowspan="2" bgcolor="#99FF99">% LOADING</th>
      <th rowspan="2" bgcolor="#99FF99">L:R</th>
      <th rowspan="2" bgcolor="#99FF99">PEMAKAIAN AIR</th>
      <th rowspan="2" bgcolor="#99FF99">KETERANGAN</th>
      <th rowspan="2" bgcolor="#99FF99">K.R</th>
      <th rowspan="2" bgcolor="#99FF99">R.B/R.L/R.S</th>
      <th rowspan="2" bgcolor="#99FF99">STATUS</th>
      <th rowspan="2" bgcolor="#99FF99">DYESTUFF</th>
      <th rowspan="2" bgcolor="#99FF99">ENERGY</th>
      <th colspan="4" bgcolor="#99FF99">JAM PROSES</th>
      <th rowspan="2" bgcolor="#99FF99">LAMA PROSES</th>
      <th rowspan="2" bgcolor="#99FF99">POINT</th>
      <th colspan="4" bgcolor="#99FF99">STOP MESIN</th>
      <th rowspan="2" bgcolor="#99FF99">LAMA STOP</th>
      <th rowspan="2" bgcolor="#99FF99">KODE STOP</th>
      <th rowspan="2" bgcolor="#99FF99">Acc Keluar Kain</th>
      <th rowspan="2" bgcolor="#99FF99">Operator</th>
      <th rowspan="2" bgcolor="#99FF99">NoKK</th>
      <th rowspan="2" bgcolor="#99FF99">No Warna</th>
      <th rowspan="2" bgcolor="#99FF99">Lebar</th>
      <th rowspan="2" bgcolor="#99FF99">Gramasi</th>
      <th rowspan="2" bgcolor="#99FF99">Carry Over</th>
      <th rowspan="2" bgcolor="#99FF99">ACUAN QUALITY</th>
      <th rowspan="2" bgcolor="#99FF99">ITEM</th>
      <th rowspan="2" bgcolor="#99FF99">NO PO</th>
      <th rowspan="2" bgcolor="#99FF99">TGL DELIVERY</th>
      <th rowspan="2" bgcolor="#99FF99">Point Proses</th>
      <th rowspan="2" bgcolor="#99FF99">Penanggung Jawab</th>
      <th rowspan="2" bgcolor="#99FF99">Analisa Penyebab</th>
      <th rowspan="2" bgcolor="#99FF99">No program</th>
      <th rowspan="2" bgcolor="#99FF99">Panjang kain</th>
      <th rowspan="2" bgcolor="#99FF99">Cycle time</th>
      <th rowspan="2" bgcolor="#99FF99">RPM</th>
      <th rowspan="2" bgcolor="#99FF99">Tekanan/press</th>
      <th rowspan="2" bgcolor="#99FF99">Nozzle</th>
      <th rowspan="2" bgcolor="#99FF99">Plaiter</th>
      <th rowspan="2" bgcolor="#99FF99">Blower</th>
      <th rowspan="2" bgcolor="#99FF99">Air Awal</th>
      <th rowspan="2" bgcolor="#99FF99">Air Akhir</th>
      <th rowspan="2" bgcolor="#99FF99">Total Pemakaian Air</th>
      <th rowspan="2" bgcolor="#99FF99">Std Target</th>
      <th rowspan="2" bgcolor="#99FF99">Jml Gerobak</th>
      <th rowspan="2" bgcolor="#99FF99">Jns Gerobak</th>
      <th rowspan="2" bgcolor="#99FF99">Nokk Legacy</th>
      <th rowspan="2" bgcolor="#99FF99">Prod. Demand</th>
      <th rowspan="2" bgcolor="#99FF99">Tambah Obat Terakhir</th>
      <th rowspan="2" bgcolor="#99FF99">Tambah Obat 1x</th>
      <th rowspan="2" bgcolor="#99FF99">Tambah Obat 2x</th>
      <th rowspan="2" bgcolor="#99FF99">Tambah Obat 3x</th>
      <th rowspan="2" bgcolor="#99FF99">Tambah Obat 4x</th>
      <th rowspan="2" bgcolor="#99FF99">Tambah Obat 5x</th>
      <th rowspan="2" bgcolor="#99FF99">Tambah Obat 6x</th>
      <th rowspan="2" bgcolor="#99FF99">Leader</th>
      <th rowspan="2" bgcolor="#99FF99">Suffix</th>
      <th rowspan="2" bgcolor="#99FF99">Suffix 2</th>
      <th rowspan="2" bgcolor="#99FF99">LR 2</th>
      <th rowspan="2" bgcolor="#99FF99">Lebar Aktual FIN</th>
      <th rowspan="2" bgcolor="#99FF99">Gramasi Aktual FIN</th>
      <th rowspan="2" bgcolor="#99FF99">Lebar Aktual DYE</th>
      <th rowspan="2" bgcolor="#99FF99">Gramasi Aktual DYE</th>
      <th rowspan="2" bgcolor="#99FF99">Operator</th>
      <th rowspan="2" bgcolor="#99FF99">LOT di NOW</th>
      <th rowspan="2" bgcolor="#99FF99">Status Resep</th>
      <th rowspan="2" bgcolor="#99FF99">Keterangan Analisa Resep</th>
      <th colspan="2" bgcolor="#99FF99">Cek Resep</th>
      <th colspan="2" bgcolor="#99FF99">Setting Resep</th>
      <th rowspan="2" bgcolor="#99FF99">Tindakan Perbaikan</th>
      <th rowspan="2" bgcolor="#99FF99">Analisa Penyebab</th>
      <th rowspan="2" bgcolor="#99FF99">Dept Penyebab</th>
      <th rowspan="2" bgcolor="#99FF99">Kategori</th>
      <th colspan="2" bgcolor="#99FF99">Status Warna</th>
      <th rowspan="2" bgcolor="#99FF99">Ket Hitung</th>
    </tr>
    <tr>
      <th bgcolor="#99FF99">TGL</th>
      <th bgcolor="#99FF99">IN</th>
      <th bgcolor="#99FF99">TGL</th>
      <th bgcolor="#99FF99">OUT</th>
      <th bgcolor="#99FF99">TGL</th>
      <th bgcolor="#99FF99">JAM</th>
      <th bgcolor="#99FF99">TGL</th>
      <th bgcolor="#99FF99">S/D</th>
      <th bgcolor="#99FF99">Colorist Lab</th>
      <th bgcolor="#99FF99">Colorist Dye</th>
      <th bgcolor="#99FF99">Sebelum</th>
      <th bgcolor="#99FF99">Sesudah</th>
      <th bgcolor="#99FF99">Celup</th>
      <th bgcolor="#99FF99">Fin Jadi</th>
    </tr>
    <?php
        ini_set("error_reporting", 0);
        $Awal = $_GET['awal'];
        $jamA = $_GET['jam_awal'];
        $Akhir = $_GET['akhir'];
        $jamAr = $_GET['jam_akhir'];
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
          $where=" c.tgl_update BETWEEN '$start_date' AND '$stop_date' ";}
        else if($Awal!="" and $Akhir!=""){ 
          $where=" DATE_FORMAT(c.tgl_update, '%Y-%m-%d') BETWEEN '$Awal' AND '$Akhir' ";}
        else{ $where=" ";}  
      if(!empty($Suffix)){
        $where2=" (x.suffix LIKE '%$Suffix%' OR x.suffix2 LIKE '%$Suffix%')" ;
      }else{
        $where2 ="";
      }
        $sql = mysqli_query($con, "SELECT
                                x.*
                              from
                                (
                                SELECT
                                  b.id as id_schedule_1,
                                  c.id as id_montemp_1,
                                  a.id as id_hasil_celup_1,
                                  a2.colorist_lab,
                                  a2.colorist_dye,
                                  a2.setting_sebelum,
                                  a2.setting_sesudah,
                                  a2.tindakan_perbaikan,
                                  a2.analisa_penyebab,
                                  a2.dept_penyebab2,
                                  a2.akar_penyebab,
                                  a2.ket_hitung,
                                  a.penanggungjawabbuyer,
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
                                  b.kategori_warna,
                                  b.target,
                                  c.l_r,
                                  c.rol,
                                  c.bruto,
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
                                  a.operator_keluar,
                                  a.k_resep,
                                  a.status,
                                  a.proses_point,
                                  a.analisa,
                                  b.nokk,
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
                                  a.status_resep,
                                  a.analisa_resep
                                from
                                  tbl_schedule b
                                left join tbl_montemp c on
                                  c.id_schedule = b.id
                                left join tbl_hasilcelup a on
                                  a.id_montemp = c.id
                                left join tbl_hasilcelup2 a2 on
		                              a2.id_hasilcelup = a.id
                                where
                                  $where ) x ");

        $no = 1;

        $c = 0;
        $totrol = 0;
        $totberat = 0;

        while ($rowd = mysqli_fetch_array($sql)) {
          if ($_GET['shft'] == "ALL") {
            $shftSM = " ";
          } else {
            $shftSM = " g_shift='$_GET[shft]' AND ";
          }
          $sqlSM = mysqli_query($con, "SELECT *, TIME_FORMAT(timediff(selesai,mulai),'%H:%i') as menitSM,
                                      DATE_FORMAT(mulai,'%Y-%m-%d') as tgl_masuk,
                                      DATE_FORMAT(selesai,'%Y-%m-%d') as tgl_selesai,
                                      TIME_FORMAT(mulai,'%H:%i') as jam_masuk,
                                      TIME_FORMAT(selesai,'%H:%i') as jam_selesai,
                                      kapasitas as kapSM,
                                      g_shift as shiftSM
                                      FROM tbl_stopmesin
                                      WHERE $shftSM tgl_update BETWEEN '$_GET[awal]' AND '$_GET[akhir]' AND no_mesin='$rowd[mc]'");
          $rowSM = mysqli_fetch_array($sqlSM);
          if (strlen($rowd['rol']) > 5) {
            $jk = strlen($rowd['rol']) - 5;
            $rl = substr($rowd['rol'], 0, $jk);
          } else {
            $rl = $rowd['rol'];
          }
    ?>
      <tr valign="top">
        <td><?php echo $no; ?></td>
        <td><?php if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
              echo $rowSM['shiftSM'];
            } else {
              echo $rowd['shft'];
            } ?></td>
        <td>'<?php echo $rowd['no_mesin']; ?></td>
        <td><?php if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
              echo $rowSM['kapSM'];
            } else {
              echo $rowd['kapasitas'];
            } ?></td>
        <td><?php echo $rowd['langganan']; ?></td>
        <td><?php echo $rowd['buyer']; ?></td>
        <td><?php echo $rowd['no_order']; ?></td>
        <td><?php echo $rowd['jenis_kain']; ?></td>
        <td><?php echo $rowd['warna']; ?></td>
        <td><?php echo $rowd['kategori_warna']; ?></td>
        <td>'<?php echo $rowd['lot']; ?></td>
        <td><?php if ($rowd['tgl_out'] != "") {
              $rol = $rowd['rol'];
            } else {
              $rol = 0;
            }
            echo $rol; ?></td>
        <td><?php if ($rowd['tgl_out'] != "") {
              $brt = $rowd['bruto'];
            } else {
              $brt = 0;
            }
            echo $brt; ?></td>
        <td><?php if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
              echo $rowSM['proses'];
            } else {
              echo $rowd['proses'];
            } ?></td>
        <td><?php echo $rowd['loading']; ?></td>
        <td>'<?php echo $rowd['l_r']; ?></td>
        <td><?php echo $rowd['pakai_air']; ?></td>
        <td><?php if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
              echo $rowSM['keterangan'] . "" . $rowSM['no_stop'];
            } else {
              echo $rowd['ket'] . "" . $rowd['status'];
            } ?><?php if ($rowd['kk_kestabilan'] == "1" and $rowd['kk_normal'] == "0") {
              echo "<br>Test Kestabilan";
            } ?></td>
        <td><?php echo $rowd['k_resep']; ?></td>
        <td><?php if ($rowd['ket_status'] == "") {
              echo "";
            } else if ($rowd['ket_status'] != "MC Stop") {
              if ($rowd['resep'] == "Baru") {
                echo "R.B";
              }elseif($rowd['resep'] == "Lama") {
                echo "R.L";
              }elseif($rowd['resep'] == "Setting") {
                echo "R.S";
              }
            } ?></td>
        <td><?php echo $rowd['sts']; ?></td>
        <td><?php echo $rowd['dyestuff']; ?></td>
        <td><?php echo $rowd['energi']; ?></td>
        <td><?php echo $rowd['tgl_in']; ?></td>
        <td><?php echo $rowd['jam_in']; ?></td>
        <td><?php echo $rowd['tgl_out']; ?></td>
        <td><?php echo $rowd['jam_out']; ?></td>
        <td><?php if ($rowd['lama_proses'] != "") {
              echo $rowd['jam'] . ":" . $rowd['menit'];
            } ?></td>
        <td><?php echo $rowd['point']; ?></td>
        <td><?php if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
              echo $rowSM['tgl_masuk'];
            } else {
              echo $rowd['t_mulai'];
            } ?></td>
        <td><?php if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
              echo $rowSM['jam_masuk'];
            } else {
              echo $rowd['j_mulai'];
            } ?></td>
        <td><?php if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
              echo $rowSM['tgl_selesai'];
            } else {
              echo $rowd['t_selesai'];
            } ?></td>
        <td><?php if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
              echo $rowSM['jam_selesai'];
            } else {
              echo $rowd['j_selesai'];
            } ?></td>
        <td><?php if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
              echo $rowSM['menitSM'];
            } else if ($rowd['lama_stop_menit'] != "") {
              $jam = floor(round($rowd['lama_stop_menit']) / 60);
              $menit = round($rowd['lama_stop_menit']) % 60;
              echo $jam . ":" . $menit;
            } ?></td>
        <td><?php if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
              echo $rowSM['kd_stopmc'];
            } else {
              echo $rowd['kd_stop'];
            } ?></td>
        <td><?php echo $rowd['acc_keluar']; ?></td>
        <td><?php echo $rowd['operator_keluar']; ?></td>
        <td>'<?php echo $rowd['nokk']; ?></td>
        <td><?php echo $rowd['no_warna']; ?></td>
        <td><?php echo $rowd['lebar']; ?></td>
        <td><?php echo $rowd['gramasi']; ?></td>
        <td><?php echo $rowd['carry_over']; ?></td>
        <td><?php echo $rowd['no_hanger']; ?></td>
        <td><?php echo $rowd['no_item']; ?></td>
        <td><?php echo $rowd['po']; ?></td>
        <td><?php echo $rowd['tgl_delivery']; ?></td>
        <td><?php echo $rowd['proses_point']; ?></td>
        <td><?php echo $rowd['penanggung_jawab']; ?></td>
        <td><?php echo $rowd['analisa']; ?></td>
        <td><?php echo $rowd['no_program']; ?></td>
        <td><?php echo $rowd['pjng_kain']; ?></td>
        <td><?php echo $rowd['cycle_time']; ?></td>
        <td><?php echo $rowd['rpm']; ?></td>
        <td><?php echo $rowd['tekanan']; ?></td>
        <td><?php echo $rowd['nozzle']; ?></td>
        <td><?php echo $rowd['plaiter']; ?></td>
        <td><?php echo $rowd['blower']; ?></td>
        <td><?php echo $rowd['air_awal']; ?></td>
        <td><?php echo $rowd['air_akhir']; ?></td>
        <td>
          <?php 
            if ($rowd['air_akhir']) {
              echo $rowd['air_akhir'] - $rowd['air_awal'];
            } 
          ?>
        </td>
        <td><?php echo $rowd['target']; ?></td>
        <td><?php echo $rowd['gerobak']; ?></td>
        <td><?php echo $rowd['jns_gerobak']; ?></td>
        <td>'<?php echo $rowd['nokk_legacy']; ?></td>
        <td>'<?php echo $rowd['nodemand']; ?></td>
        <td><?php echo $rowd['tambah_obat']; ?></td>
        <td><?php echo $rowd['tambah_obat1']; ?></td>
        <td><?php echo $rowd['tambah_obat2']; ?></td>
        <td><?php echo $rowd['tambah_obat3']; ?></td>
        <td><?php echo $rowd['tambah_obat4']; ?></td>
        <td><?php echo $rowd['tambah_obat5']; ?></td>
        <td><?php echo $rowd['tambah_obat6']; ?></td>
        <td><?= $rowd['leader']; ?></td>
        <td><?= $rowd['suffix']; ?></td>
        <td><?= $rowd['suffix2']; ?></td>
        <td><?= $rowd['l_r_2']; ?></td>
        <td><?= $rowd['lebar_fin']; ?></td>
        <td><?= $rowd['grm_fin']; ?></td>
        <td><?= $rowd['lebar_a']; ?></td>
        <td><?= $rowd['gramasi_a']; ?></td>
        <td><?= $rowd['operator']; ?></td>
        <td>'
          <?php
            $q_lot		= db2_exec($conn2, "SELECT * FROM ITXVIEWKK WHERE PRODUCTIONDEMANDCODE = '$rowd[nodemand]'");
            $d_lot		= db2_fetch_assoc($q_lot);
            echo $d_lot['LOT'];
          ?>
        </td>
        <td><?= $rowd['status_resep'] ?></td>
        <td><?= $rowd['analisa_resep'] ?></td>
        <td><?= $rowd['colorist_lab'] ?></td>
        <td><?= $rowd['colorist_dye'] ?></td>
        <td><?= $rowd['setting_sebelum'] ?></td>
        <td><?= $rowd['setting_sesudah'] ?></td>
        <td><?= $rowd['tindakan_perbaikan'] ?></td>
        <td><?= $rowd['analisa_penyebab'] ?></td>
        <td><?= $rowd['dept_penyebab2'] ?></td>
        <td><?= $rowd['akar_penyebab'] ?></td>
        <td align="left"><?php 
                                $nomor_array = explode(',', $rowd['nodemand']);
                                $quoted_nomor_array = [];
                                foreach ($nomor_array as $nomor) {
                                    $quoted_nomor_array[] = "'" . trim($nomor) . "'";
                                }
                                $in_clause_string = implode(',', $quoted_nomor_array);
                                $qry_dye = mysqli_query($cond, "SELECT
                                                                  GROUP_CONCAT(DISTINCT status_warna ORDER BY id ASC SEPARATOR ', ') AS status_warna
                                                                FROM
                                                                  tbl_cocok_warna_dye t
                                                                WHERE
                                                                  `dept` = 'QCF'
                                                                  AND nodemand IN ({$in_clause_string})
                                                        ");
                                $sts_dye = mysqli_fetch_assoc($qry_dye);
                                // echo $in_clause_string;
                                echo $sts_dye['status_warna']; 
                                ?></td>
            <td align="left"><?php 
                              $qry_fin = mysqli_query($cond, "SELECT DISTINCT 
                                                                status 
                                                              from
                                                                tbl_lap_inspeksi
                                                              where
                                                                `dept` = 'QCF'
                                                                and nokk ='$rowd[nokk]'
                                                                and proses in ('Fin', 'Comp')
                                                              order by
                                                                id asc
                                                      ");
                              $sts_fin = mysqli_fetch_assoc($qry_fin);
                              echo $sts_fin['status']; 
                              ?>
            </td>
            <td>
              <?= $rowd['ket_hitung'] == 1 ? 'TIDAK HITUNG' : 'HITUNG' ?>
            </td>
      </tr>
    <?php
      $totrol += $rol;
      $totberat += $brt;
      $no++;
    } ?>
    <tr>
      <td colspan="8" bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <th bgcolor="#99FF99">Total</th>
      <td bgcolor="#99FF99">&nbsp;</td>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99"><?php echo $totrol; ?></th>
      <th bgcolor="#99FF99"><?php echo $totberat; ?></th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <th bgcolor="#99FF99">&nbsp;</th>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
    </tr>
  </table>
</body>