<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Adjust Recipe</title>

</head>
<body>
<?php
$Awal	= isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : '';
$GShift	= isset($_POST['gshift']) ? $_POST['gshift'] : '';
$jamA 	= isset($_POST['jam_awal']) ? $_POST['jam_awal'] : '';
$jamAr 	= isset($_POST['jam_akhir']) ? $_POST['jam_akhir'] : '';	
$Suffix 	= isset($_POST['suffix']) ? $_POST['suffix'] : '';	
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
  if($jamA!="" && $jamAr!=""){ 
    $where = " c.tgl_update BETWEEN CONVERT(datetime,'$start_date',120) AND CONVERT(datetime,'$stop_date',120) ";
  }
  else if($Awal!="" && $Akhir!=""){ 
    $where = " CONVERT(date, c.tgl_update) BETWEEN CONVERT(date,'$Awal') AND CONVERT(date,'$Akhir') ";
  }
  else{ 
    $where = " 1=1 ";
  } 
if(!empty($Suffix)){
  $where2=" AND (b.suffix LIKE '%$Suffix%' OR b.suffix2 LIKE '%$Suffix%')" ;
}else{
  $where2 ="";
}
 if($Awal!="" and $Akhir!=""){
    $qry1 = sqlsrv_query($con, "SELECT
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
            CASE 
              WHEN TRY_CONVERT(time(0), a.lama_proses) IS NULL 
                OR c.tgl_mulai IS NULL 
                OR c.tgl_stop IS NULL
              THEN a.lama_proses
              ELSE
              RIGHT('0' + CAST(
                (DATEDIFF(MINUTE, '00:00', TRY_CONVERT(time(0), a.lama_proses))
                - DATEDIFF(MINUTE, c.tgl_mulai, c.tgl_stop)) / 60
                AS varchar(2)), 2)
              + ':' +
              RIGHT('0' + CAST(
                (DATEDIFF(MINUTE, '00:00', TRY_CONVERT(time(0), a.lama_proses))
                - DATEDIFF(MINUTE, c.tgl_mulai, c.tgl_stop)) % 60
                AS varchar(2)), 2)
            END AS lama_proses,
            a.status as sts,
            CASE 
              WHEN TRY_CONVERT(time(0), a.lama_proses) IS NULL THEN NULL
              ELSE RIGHT('0' + CAST(DATEPART(HOUR, TRY_CONVERT(time(0), a.lama_proses)) AS varchar(2)), 2)
            END AS jam,
            CASE 
              WHEN TRY_CONVERT(time(0), a.lama_proses) IS NULL THEN NULL
              ELSE RIGHT('0' + CAST(DATEPART(MINUTE, TRY_CONVERT(time(0), a.lama_proses)) AS varchar(2)), 2)
            END AS menit,
            a.point,
            CONVERT(varchar(10), a.mulai_stop, 23) as t_mulai,
            CONVERT(varchar(10), a.selesai_stop, 23) as t_selesai,
            CONVERT(varchar(5), a.mulai_stop, 108) as j_mulai,
            CONVERT(varchar(5), a.selesai_stop, 108) as j_selesai,
            DATEDIFF(minute, a.mulai_stop, a.selesai_stop) as lama_stop_menit,
            a.acc_keluar,
            CASE WHEN a.proses IS NULL OR a.proses = '' THEN b.proses ELSE a.proses END as proses,
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
            CONVERT(varchar(10), c.tgl_buat, 23) as tgl_in,
            CONVERT(varchar(10), a.tgl_buat, 23) as tgl_out,
            CONVERT(varchar(5),  c.tgl_buat, 108) as jam_in,
            CONVERT(varchar(5),  a.tgl_buat, 108) as jam_out,
            CASE WHEN a.g_shift IS NULL OR a.g_shift='' THEN c.g_shift ELSE a.g_shift END as shft,
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
          FROM db_dying.tbl_schedule b
          LEFT JOIN db_dying.tbl_montemp c ON c.id_schedule = b.id
          LEFT JOIN db_dying.tbl_hasilcelup a ON a.id_montemp = c.id
          LEFT JOIN db_dying.tbl_hasilcelup2 a2 ON a2.id_hasilcelup = a.id
          WHERE
            $where
            AND (CASE WHEN a.proses IS NULL OR a.proses='' THEN b.proses ELSE a.proses END) IN ('Cuci Misty', 'Celup Greige')
            $where2
        ");
        if ($qry1 === false) {
          die(print_r(sqlsrv_errors(), true));
        }
  }else{
    $qry1=sqlsrv_query($con,"SELECT TOP 100
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
                                  CASE 
                                    WHEN TRY_CONVERT(time(0), a.lama_proses) IS NULL 
                                      OR c.tgl_mulai IS NULL 
                                      OR c.tgl_stop IS NULL
                                    THEN a.lama_proses
                                    ELSE
                                    RIGHT('0' + CAST(
                                      (DATEDIFF(MINUTE, '00:00', TRY_CONVERT(time(0), a.lama_proses))
                                      - DATEDIFF(MINUTE, c.tgl_mulai, c.tgl_stop)) / 60
                                      AS varchar(2)), 2)
                                    + ':' +
                                    RIGHT('0' + CAST(
                                      (DATEDIFF(MINUTE, '00:00', TRY_CONVERT(time(0), a.lama_proses))
                                      - DATEDIFF(MINUTE, c.tgl_mulai, c.tgl_stop)) % 60
                                      AS varchar(2)), 2)
                                  END AS lama_proses,
                                  a.status as sts,
                                  CASE 
                                    WHEN TRY_CONVERT(time(0), a.lama_proses) IS NULL THEN NULL
                                    ELSE RIGHT('0' + CAST(DATEPART(HOUR, TRY_CONVERT(time(0), a.lama_proses)) AS varchar(2)), 2)
                                  END AS jam,
                                  CASE 
                                    WHEN TRY_CONVERT(time(0), a.lama_proses) IS NULL THEN NULL
                                    ELSE RIGHT('0' + CAST(DATEPART(MINUTE, TRY_CONVERT(time(0), a.lama_proses)) AS varchar(2)), 2)
                                  END AS menit,
                                  a.point,
                                  CONVERT(varchar(10), a.mulai_stop, 23) as t_mulai,
                                  CONVERT(varchar(10), a.selesai_stop, 23) as t_selesai,
                                  CONVERT(varchar(5), a.mulai_stop, 108) as j_mulai,
                                  CONVERT(varchar(5), a.selesai_stop, 108) as j_selesai,
                                  DATEDIFF(minute, a.mulai_stop, a.selesai_stop) as lama_stop_menit,
                                  a.acc_keluar,
                                  CASE WHEN a.proses IS NULL OR a.proses = '' THEN b.proses ELSE a.proses END as proses,
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
                                  CONVERT(varchar(10), c.tgl_buat, 23) as tgl_in,
                                  CONVERT(varchar(10), a.tgl_buat, 23) as tgl_out,
                                  CONVERT(varchar(5),  c.tgl_buat, 108) as jam_in,
                                  CONVERT(varchar(5),  a.tgl_buat, 108) as jam_out,
                                  CASE WHEN a.g_shift IS NULL OR a.g_shift='' THEN c.g_shift ELSE a.g_shift END as shft,
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
                                  db_dying.tbl_schedule b
                                left join db_dying.tbl_montemp c on
                                  c.id_schedule = b.id
                                left join db_dying.tbl_hasilcelup a on
                                  a.id_montemp = c.id
                                left join db_dying.tbl_hasilcelup2 a2 on
                                  a2.id_hasilcelup = a.id
                                WHERE
                                  (CASE WHEN a.proses IS NULL OR a.proses='' THEN b.proses ELSE a.proses END) IN ('Cuci Misty', 'Celup Greige')
                                  $where2
                                order by c.id desc");
  }
?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"> Filter Laporan Ket Recipe</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
    <div class="box-body">

     <div class="form-group">
        <div class="col-sm-2">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="awal" type="date" class="form-control pull-right" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off"/>
          </div>
        </div>
		<div class="col-sm-1">
                <input type="text" class="form-control timepicker" name="jam_awal" placeholder="00:00" value="<?php echo $jamA; ?>" autocomplete="off">
              </div>
        <!-- /.input group -->
      </div>
      <div class="form-group">
        <div class="col-sm-2">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="akhir" type="date" class="form-control pull-right" placeholder="Tanggal Akhir" value="<?php echo $Akhir;  ?>" autocomplete="off"/>
          </div>
        </div>
		    <div class="col-sm-1">
            <input type="text" class="form-control timepicker" name="jam_akhir" placeholder="00:00" value="<?php echo $jamAr; ?>" autocomplete="off">
        </div>   
        <!-- /.input group -->
        <div class="col-sm-2">
          <input name="suffix" class="form-control pull-right" placeholder="Suffix" value="<?php echo $Suffix;  ?>" autocomplete="off"/>
        </div>
      </div>
    </div>

    <!-- /.box-body -->
    <div class="box-footer">
      <div class="col-sm-2">
        <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>		  
      </div>
	  	
    </div>
    <!-- /.box-footer -->
  </form>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Data Laporan Ket Recipe</h3>
        <?php if ($Awal != "") { ?>
          <div class="pull-right">
            <a href="pages/cetak/cetak_laporanrecipe.php?&awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&jam_awal=<?php echo $jamA; ?>&jam_akhir=<?php echo $jamAr; ?>" class="btn btn-success " target="_blank" data-toggle="tooltip" data-html="true" title="Laporan Ket Recipe"><i class="fa fa-file-excel-o"></i> Laporan Ket Recipe</a>
          </div>
          <?php } ?>
          </div>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped" id="example99" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th rowspan="2"><div align="center">No</div></th>
            <th rowspan="2"><div align="center">Tgl Celup</div></th>
            <th rowspan="2"><div align="center">NO MC</div></th>
            <th rowspan="2"><div align="center">Kapasitas MC</div></th>
            <th rowspan="2"><div align="center">Langganan</div></th>
            <th rowspan="2"><div align="center">Buyer</div></th>
            <th rowspan="2"><div align="center">No Order</div></th>
            <th rowspan="2"><div align="center">Jenis Kain</div></th>
            <th rowspan="2"><div align="center">Warna</div></th>
            <th rowspan="2"><div align="center">LOT</div></th>
            <th rowspan="2"><div align="center">QTY</div></th>
            <th rowspan="2"><div align="center">Proses</div></th>
            <th rowspan="2"><div align="center">K.R</div></th>
            <th rowspan="2"><div align="center">Jenis Resep</div></th>
            <th rowspan="2"><div align="center">Status</div></th>
            <th rowspan="2"><div align="center">Acc Keluar Kain</div></th>
            <th rowspan="2"><div align="center">Prod Order</div></th>
            <th rowspan="2"><div align="center">No Warna</div></th>
            <th rowspan="2"><div align="center">Acuan Quality</div></th>
            <th rowspan="2"><div align="center">Prod Demand</div></th>
            <th rowspan="2"><div align="center">Suffix 1</div></th>
            <th rowspan="2"><div align="center">Suffix 2</div></th>
            <th rowspan="2"><div align="center">Status Resep</div></th>
            <th rowspan="2"><div align="center">Keterangan Analisa Resep</div></th>
            <th colspan="2"><div align="center">Cek Resep</div></th>
            <th colspan="2"><div align="center">Setting Resep</div></th>
            <th rowspan="2"><div align="center">Tindakan Perbaikan</div></th>
            <th rowspan="2"><div align="center">Analisa Penyebab</div></th>
            <th rowspan="2"><div align="center">Dept Penyebab</div></th>
            <th rowspan="2"><div align="center">Kategori</div></th>
            <th colspan="2"><div align="center">Status Warna</div></th>
            <th rowspan="2"><div align="center">Tidak Hitung</div></th>
          </tr>
          <tr>
            <th>Colorist Lab</th>
            <th>Colorist Dye</th>
            <th>Sebelum</th>
            <th>Sesudah</th>
            <th>Celup</th>
            <th>Fin Jadi</th>
          </tr>
        </thead>
        <tbody>
              <?php
          $no = 1;
          while ($row1 = sqlsrv_fetch_array($qry1, SQLSRV_FETCH_ASSOC)) {
            $q_user = sqlsrv_query($cona,"SELECT * FROM db_adm.tbl_user_tindaklanjut WHERE id = '$row1[pemberi_instruksi]'");
            $row_user = sqlsrv_fetch_array($q_user, SQLSRV_FETCH_ASSOC);
              $id_hasil_celup = $row1['id_hasil_celup_1'];
              $id_schedule = $row1['id_schedule_1'];
              $id_montemp = $row1['id_montemp_1'];

              // Ambil data sebelumnya jika ada
              $analisa = $keterangan = "";
              $saved_dept = [];

              $qrySaved = sqlsrv_query($con, "SELECT * FROM db_dying.tbl_keterangan_gagalproses WHERE id_hasil_celup = '$id_hasil_celup'");
              if ($res = sqlsrv_fetch_array($qrySaved, SQLSRV_FETCH_ASSOC)) {
                  $analisa = $res['analisa_penyebab'];
                  $keterangan = $res['keterangan_gagal_proses'];
                  $accresep = $res['accresep'];
                  $saved_dept = explode(",", $res['dept_penyebab']);
              }
          ?>
          <tr>
              <td><?= $no++; ?></td>
              <td><?= $row1['tgl_out'] ?></td>
              <td align="center"><?= $row1['no_mesin'] ?></td>
              <td align="center"><?= $row1['kapasitas'] ?></td>
              <td><?= $row1['langganan'] ?></td>
              <td><?= $row1['buyer'] ?></td>
              <td><?= $row1['no_order'] ?></td>
              <td><?= htmlspecialchars($row1['jenis_kain']); ?></td>
              <td><?= $row1['warna'] ?></td>
              <td><?= $row1['lot'] ?></td>
              <td align="right"><?= $row1['bruto'] ?></td>
              <td><?= $row1['proses'] ?></td>
              <td align="center">
                  <a href="#" class="kestabilan-resep-editable"
                    data-type="select"
                    data-pk="<?= htmlspecialchars($id_hasil_celup)?>"
                    data-value="<?= htmlspecialchars($row1['k_resep']) ?>"
                    data-url="pages/editable/cqa/ket_recipe/update_kestabilan_resep.php"
                    data-title="Pilih Kestabilan Resep">
                      <?php 
                          echo !empty($row1['k_resep']) ? htmlspecialchars($row1['k_resep']) : 'Pilih'; 
                      ?>
                  </a>
              </td>
                <td>
                    <a href="#" class="resep-editable"
                      data-type="select"
                      data-pk="<?= htmlspecialchars($id_schedule)?>"
                      data-value="<?= htmlspecialchars($row1['resep']) ?>"
                      data-url="pages/editable/cqa/ket_recipe/update_resep.php"
                      data-title="Pilih Jenis Resep">
                        <?php
                            if ($row1['ket_status'] == "") {
                                echo "Pilih"; 
                            } else if ($row1['ket_status'] != "MC Stop") {
                                if ($row1['resep'] == "Baru") {
                                    echo "R.B";
                                } elseif ($row1['resep'] == "Lama") {
                                    echo "R.L";
                                } elseif ($row1['resep'] == "Setting") {
                                    echo "R.S";
                                } else {
                                    echo "Pilih";
                                }
                            }
                        ?>
                    </a>
                </td>
                  <!-- kolom ini nanti editable -->
              <td><?= $row1['sts'] ?></td>
              <td><?= $row1['acc_keluar'] ?></td>
              <td><a href="https://online.indotaichen.com/laporan/ppc_filter.php?prod_order=<?= htmlspecialchars($row1['nokk']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($row1['nokk']) ?></a></td>
              <td><?= $row1['no_warna'] ?></td>
              <td><?= $row1['no_hanger'] ?></td>
              <td><?= $row1['nodemand'] ?></td>
              <!-- <td><?= $row1['suffix'] ?></td> -->
              <td align="center">
                <a href="#" class="suffix1-editable"
                  data-type="textarea"
                  data-pk="<?= htmlspecialchars($id_schedule)?>"
                  data-value="<?= htmlspecialchars($row1['suffix']) ?>"
                  data-url="pages/editable/cqa/ket_recipe/update_suffix.php"
                  data-title="Input Suffix">
                    <?php 
                        echo !empty($row1['suffix']) ? htmlspecialchars($row1['suffix']) : 'Klik untuk isi'; 
                    ?>
                </a>
              </td>
              <td align="center">
                <a href="#" class="suffix2-editable"
                  data-type="textarea"
                  data-pk="<?= htmlspecialchars($id_schedule)?>"
                  data-value="<?= htmlspecialchars($row1['suffix2']) ?>"
                  data-url="pages/editable/cqa/ket_recipe/update_suffix2.php"
                  data-title="Input Suffix">
                    <?php 
                        echo !empty($row1['suffix2']) ? htmlspecialchars($row1['suffix2']) : 'Klik untuk isi'; 
                    ?>
                </a>
              </td>
              <td align="center">
                  <a href="#" class="status-resep-editable"
                    data-type="select"
                    data-pk="<?= htmlspecialchars($id_hasil_celup)?>"
                    data-value="<?= htmlspecialchars($row1['status_resep']) ?>"
                    data-url="pages/editable/cqa/ket_recipe/update_status_resep.php"
                    data-title="Pilih Status Resep">
                      <?php 
                          echo !empty($row1['status_resep']) ? htmlspecialchars($row1['status_resep']) : 'Pilih'; 
                      ?>
                  </a>
              </td>
              <!-- <td><?= $row1['status_resep'] ?></td> -->
              <td align="left">
                  <a href="#" class="analisa-resep-editable" 
                    data-type="textarea" 
                    data-pk="<?= htmlspecialchars($id_hasil_celup) ?>"
                    data-value="<?= htmlspecialchars($row1['analisa_resep']) ?>"
                    data-url="pages/editable/cqa/ket_recipe/update_analisa_resep.php"
                    data-title="Masukkan Analisa Resep">
                      <?php 
                          echo !empty($row1['analisa_resep']) ? nl2br(htmlspecialchars($row1['analisa_resep'])) : 'Klik untuk isi'; 
                      ?>
                  </a>
              </td>
              <td style="width: 100%;">
                  <a href="#" class="colorist-lab-editable" 
                    data-type="select" 
                    data-pk="<?= htmlspecialchars($id_hasil_celup) ?>" 
                    data-value="<?= htmlspecialchars($row1['colorist_lab']) ?>" 
                    data-url="pages/editable/cqa/ket_recipe/update_colorist_lab.php" 
                    data-title="Pilih Colorist Lab">
                      <?php
                          echo !empty($row1['colorist_lab']) ? htmlspecialchars($row1['colorist_lab']) : 'Pilih'; 
                      ?>
                  </a>
              </td>
              <td style="width: 100%;">
                  <a href="#" class="colorist-dye-editable" 
                    data-type="select" 
                    data-pk="<?= htmlspecialchars($id_hasil_celup) ?>" 
                    data-value="<?= htmlspecialchars($row1['colorist_dye']) ?>" 
                    data-url="pages/editable/cqa/ket_recipe/update_colorist_dye.php" 
                    data-title="Pilih Colorist DYE">
                      <?php
                          echo !empty($row1['colorist_dye']) ? htmlspecialchars($row1['colorist_dye']) : 'Pilih'; 
                      ?>
                  </a>
              </td>
              <td style="width: 100%;">
                  <a href="#" class="setting-sebelum-editable" 
                    data-type="select" 
                    data-pk="<?= htmlspecialchars($id_hasil_celup) ?>" 
                    data-value="<?= htmlspecialchars($row1['setting_sebelum']) ?>" 
                    data-url="pages/editable/cqa/ket_recipe/update_setting_sebelum.php" 
                    data-title="Pilih Colorist DYE">
                      <?php
                          echo !empty($row1['setting_sebelum']) ? htmlspecialchars($row1['setting_sebelum']) : 'Pilih'; 
                      ?>
                  </a>
              </td>
              <td style="width: 100%;">
                  <a href="#" class="setting-sesudah-editable" 
                    data-type="select" 
                    data-pk="<?= htmlspecialchars($id_hasil_celup) ?>" 
                    data-value="<?= htmlspecialchars($row1['setting_sesudah']) ?>" 
                    data-url="pages/editable/cqa/ket_recipe/update_setting_sesudah.php" 
                    data-title="Pilih Colorist DYE">
                      <?php
                          echo !empty($row1['setting_sesudah']) ? htmlspecialchars($row1['setting_sesudah']) : 'Pilih'; 
                      ?>
                  </a>
              </td>
              <!-- <td align="left">
                  <a href="#" class="setting-sebelum-editable" 
                    data-type="textarea" 
                    data-pk="<?= htmlspecialchars($id_hasil_celup) ?>"
                    data-value="<?= htmlspecialchars($row1['setting_sebelum']) ?>"
                    data-url="pages/editable/cqa/ket_recipe/update_setting_sebelum.php"
                    data-title="Masukkan Setting Resep Sebelum">
                      <?php 
                          echo !empty($row1['setting_sebelum']) ? nl2br(htmlspecialchars($row1['setting_sebelum'])) : 'Klik untuk isi'; 
                      ?>
                  </a>
              </td> -->
              <!-- <td align="left">
                  <a href="#" class="setting-sesudah-editable" 
                    data-type="textarea" 
                    data-pk="<?= htmlspecialchars($id_hasil_celup) ?>"
                    data-value="<?= htmlspecialchars($row1['setting_sesudah']) ?>"
                    data-url="pages/editable/cqa/ket_recipe/update_setting_sesudah.php"
                    data-title="Masukkan Setting Resep Sesudah">
                      <?php 
                          echo !empty($row1['setting_sesudah']) ? nl2br(htmlspecialchars($row1['setting_sesudah'])) : 'Klik untuk isi'; 
                      ?>
                  </a>
              </td> -->
              <td align="center">
                  <a href="#" class="tindakan-perbaikan-editable"
                    data-type="select"
                    data-pk="<?= htmlspecialchars($id_hasil_celup)?>"
                    data-value="<?= htmlspecialchars($row1['tindakan_perbaikan']) ?>"
                    data-url="pages/editable/cqa/ket_recipe/update_tindakan_perbaikan.php"
                    data-title="Pilih tindakan perbaikan">
                      <?php 
                          echo !empty($row1['tindakan_perbaikan']) ? htmlspecialchars($row1['tindakan_perbaikan']) : 'Pilih'; 
                      ?>
                  </a>
              </td>
              <td align="left">
                  <a href="#" class="analisa-penyebab-editable" 
                    data-type="textarea" 
                    data-pk="<?= htmlspecialchars($id_hasil_celup) ?>"
                    data-value="<?= htmlspecialchars($row1['analisa_penyebab']) ?>"
                    data-url="pages/editable/cqa/ket_recipe/update_analisa_penyebab.php"
                    data-title="Masukkan Analisa Penyebab Tambah Obat">
                      <?php 
                          echo !empty($row1['analisa_penyebab']) ? nl2br(htmlspecialchars($row1['analisa_penyebab'])) : 'Klik untuk isi'; 
                      ?>
                  </a>
              </td>
              <td align="center">
                  <a href="#" class="dept-penyebab-editable"
                    data-type="select"
                    data-pk="<?= htmlspecialchars($id_hasil_celup)?>"
                    data-value="<?= htmlspecialchars($row1['dept_penyebab2']) ?>"
                    data-url="pages/editable/cqa/ket_recipe/update_dept_penyebab.php"
                    data-title="Pilih Dept Penyebab">
                      <?php 
                          echo !empty($row1['dept_penyebab2']) ? htmlspecialchars($row1['dept_penyebab2']) : 'Pilih'; 
                      ?>
                  </a>
              </td>
              <td align="center">
                  <a href="#" class="akar-penyebab-editable"
                    data-type="select"
                    data-pk="<?= htmlspecialchars($id_hasil_celup)?>"
                    data-value="<?= htmlspecialchars($row1['akar_penyebab']) ?>"
                    data-url="pages/editable/cqa/ket_recipe/update_akar_penyebab.php"
                    data-title="Pilih Akar Penyebab">
                      <?php 
                          echo !empty($row1['akar_penyebab']) ? htmlspecialchars($row1['akar_penyebab']) : 'Pilih'; 
                      ?>
                  </a>
              </td>
              <td align="left"><?php 
                                $nomor_array = explode(',', $row1['nodemand']);
                                $quoted_nomor_array = [];
                                foreach ($nomor_array as $nomor) {
                                    $quoted_nomor_array[] = "'" . trim($nomor) . "'";
                                }
                                $in_clause_string = implode(',', $quoted_nomor_array);
                                $qry_dye = sqlsrv_query($cond, "
                                    SELECT
                                      STRING_AGG(x.status_warna, ', ') AS status_warna
                                    FROM (
                                        SELECT DISTINCT t.status_warna
                                        FROM db_qc.tbl_cocok_warna_dye t
                                        WHERE
                                          t.dept = 'QCF'
                                          AND t.nodemand IN ($in_clause_string)
                                    ) x
                                ");
                                $sts_dye = sqlsrv_fetch_array($qry_dye, SQLSRV_FETCH_ASSOC);
                                echo $sts_dye['status_warna']; 
                                ?></td>
              <td align="left"><?php 
                  $qry_fin = sqlsrv_query($cond, "
                      SELECT TOP 1 status
                      FROM db_qc.tbl_lap_inspeksi
                      WHERE dept = 'QCF'
                        AND nokk = '".$row1['nokk']."'
                        AND proses IN ('Fin', 'Comp')
                      ORDER BY id ASC
                  ");

                  $sts_fin = sqlsrv_fetch_array($qry_fin, SQLSRV_FETCH_ASSOC);
                  echo $sts_fin['status'] ?? '';
                ?>
              </td>
              <td align="center">
                  <a href="#" class="ket-hitung-editable"
                    data-type="select"
                    data-pk="<?= htmlspecialchars($id_hasil_celup)?>"
                    data-value="<?= htmlspecialchars($row1['ket_hitung']) ?>"
                    data-url="pages/editable/cqa/ket_recipe/update_ket_hitung.php"
                    data-title="Pilih Akar Penyebab">
                       <?php 
                          if ($row1['ket_hitung'] === '1') {
                              echo '<span style="color:green">✔️</span>';
                          } elseif ($row1['ket_hitung'] === '0') {
                              echo '<span style="color:red">❌</span>';
                          } else {
                              echo 'Pilih';
                          }
                      ?>
                  </a>
              </td>
          </tr>
          <?php } ?>

        </tbody>
      </table>
	  <br>
      </div>
    </div>
  </div>
</div>
</div>	
	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});

	</script>
</body>
</html>
<script>
$(document).ready(function() {
     $('.select2').select2({
    });

    // Tangani perubahan pada semua input (textarea/select)
    $('.analisa, .dept, .keterangan, .accresep').on('change', function() {
        let id = $(this).data('id');
        let row = $(this).closest('tr');

        let analisa = row.find('.analisa').val();
        let dept = row.find('.dept').val(); // array
        let ket = row.find('.keterangan').val();
        let accresep = row.find('.accresep').val();
        console.log('Jumlah elemen .accresep ditemukan:', row.find('.accresep').length);

        // Ambil ID lainnya dari data-id (bisa juga embed ke input hidden)
        let id_schedule = row.find('a').attr('href').match(/schedule=(\d+)/)[1];
        let id_montemp = row.find('a').attr('href').match(/montemp=(\d+)/)[1];

         console.log('Data yang akan dikirim:', {
            id_schedule,
            id_montemp,
            // id_hasil_celup,
            analisa,
            dept,
            accresep,
            ket
        });
        $.ajax({
            url: 'pages/ajax/save_gagalproses.php',
            type: 'POST',
            data: {
                id_schedule: id_schedule,
                id_montemp: id_montemp,
                id_hasil_celup: id,
                analisa_penyebab: analisa,
                dept_penyebab: dept,
                accresep: accresep,
                keterangan_gagal_proses: ket
            },
            success: function(response) {
                console.log("Tersimpan: ", response);
            },
            error: function(err) {
                console.error("Gagal simpan", err);
            }
        });
    });
});
</script>

<!-- Editable untuk penanggung Jawab Buyer -->
<script>
$(document).ready(function(){
  $('.penanggungjawab-select').change(function(){
    var id = $(this).data('id');
    var value = $(this).val();

    $.ajax({
      url: 'pages/ajax/update_penanggungjawab.php',
      method: 'POST',
      data: { id: id, penanggungjawabbuyer: value },
      success: function(response){
        // optional: tampilkan notifikasi, log atau toast
        console.log('Data berhasil diupdate');
      },
      error: function(){
        alert('Terjadi kesalahan saat menyimpan data.');
      }
    });
  });
});
</script>
</body>
</html>

<script>
function toggleKain(id) {
    var shortEl = document.getElementById(id + '_short');
    var fullEl = document.getElementById(id + '_full');

    if (shortEl.style.display === 'none') {
        shortEl.style.display = '';
        fullEl.style.display = 'none';
    } else {
        shortEl.style.display = 'none';
        fullEl.style.display = '';
    }
}
</script>