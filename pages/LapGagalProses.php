<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Gagal Proses</title>
<style>
        /* Taruh kode CSS di sini */
        .editable-checklist {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .editable-checklist label {
            white-space: normal;
        }
    </style>
</head>
<body>
<?php
$Awal	= isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : '';
$GShift	= isset($_POST['gshift']) ? $_POST['gshift'] : '';
$jamA 	= isset($_POST['jam_awal']) ? $_POST['jam_awal'] : '';
$jamAr 	= isset($_POST['jam_akhir']) ? $_POST['jam_akhir'] : '';	
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
    $where=" AND c.tgl_update BETWEEN '$start_date' AND '$stop_date' ";
  }
  else if($Awal!="" and $Akhir!=""){ 
    $where=" AND CONVERT(date, c.tgl_update) BETWEEN '$Awal' AND '$Akhir' ";
  }
  else{ 
    $where=" "; 
  }
  
 if($Awal!="" && $Akhir!=""){

  $sql1 = "SELECT
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

  $qry1 = sqlsrv_query($con, $sql1);
  if ($qry1 === false) { die(print_r(sqlsrv_errors(), true)); }

}else{

  $sql1 = "SELECT
            TOP 150
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
              WHEN c.tgl_mulai IS NULL OR c.tgl_stop IS NULL THEN CONVERT(varchar(5), a.lama_proses, 108)
              ELSE CONVERT(varchar(5),
                    DATEADD(
                      MINUTE,
                      (DATEDIFF(MINUTE, 0, CAST(a.lama_proses AS time))
                      - DATEDIFF(MINUTE, c.tgl_stop, c.tgl_mulai)),
                      0
                    ),
                    108
              )
            END AS lama_proses,
            a.status as sts,
            LEFT(
              CASE
                WHEN c.tgl_mulai IS NULL OR c.tgl_stop IS NULL THEN CONVERT(varchar(5), a.lama_proses, 108)
                ELSE CONVERT(varchar(5),
                      DATEADD(
                        MINUTE,
                        (DATEDIFF(MINUTE, 0, CAST(a.lama_proses AS time))
                        - DATEDIFF(MINUTE, c.tgl_stop, c.tgl_mulai)),
                        0
                      ),
                      108
                )
              END, 2
            ) AS jam,
            RIGHT(
              CASE
                WHEN c.tgl_mulai IS NULL OR c.tgl_stop IS NULL THEN CONVERT(varchar(5), a.lama_proses, 108)
                ELSE CONVERT(varchar(5),
                      DATEADD(
                        MINUTE,
                        (DATEDIFF(MINUTE, 0, CAST(a.lama_proses AS time))
                        - DATEDIFF(MINUTE, c.tgl_stop, c.tgl_mulai)),
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
          ORDER BY b.id DESC";

  $qry1 = sqlsrv_query($con, $sql1);
  if ($qry1 === false) { die(print_r(sqlsrv_errors(), true)); }
}

?>
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"> Filter Laporan Gagal Proses</h3>
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
        <h3 class="box-title">Data Laporan Gagal Proses</h3>
        <?php if ($Awal != "") { ?>
          <div class="pull-right">
            <a href="pages/cetak/cetak_gagal_proses.php?&awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&jam_awal=<?php echo $jamA; ?>&jam_akhir=<?php echo $jamAr; ?>" class="btn btn-success " target="_blank" data-toggle="tooltip" data-html="true" title="Laporan Gagal Proses"><i class="fa fa-file-excel-o"></i> Laporan Gagal Proses</a>
          </div>
          <?php } ?>
          </div>
      <div class="box-body">
      <table class="table table-bordered table-hover table-striped" id="example3" style="width:100%">
        <thead class="bg-blue">
          <tr>
            <th rowspan=2><div align="center">No</div></th>
            <th rowspan=2><div align="center">Action</div></th>
            <th rowspan=2><div align="center">Tgl Celup</div></th>
            <th rowspan=2><div align="center">No KK</div></th>
            <th rowspan=2><div align="center">No Demand</div></th>
            <th rowspan=2><div align="center">Pelanggan</div></th>
            <th rowspan=2><div align="center">Buyer</div></th>
            <th rowspan=2><div align="center">PO</div></th>
            <th rowspan=2><div align="center">Order</div></th>
            <th rowspan=2><div align="center">Item</div></th>
            <th rowspan=2><div align="center">Jenis Kain</div></th>
            <th rowspan=2><div align="center">Warna</div></th>
            <th rowspan=2><div align="center">No Warna</div></th>
            <th rowspan=2><div align="center">Lot</div></th>
            <th rowspan=2><div align="center">Roll</div></th>
            <th rowspan=2><div align="center">Bruto</div></th>
            <th rowspan=2><div align="center">No Mesin</div></th>
            <th rowspan=2><div align="center">Status</div></th>
            <th rowspan=2><div align="center">Proses Celup</div></th>
            <th rowspan=2><div align="center">Status Warna</div></th>
            <th rowspan=2><div align="center">Analisa Penyebab</div></th>
            <th rowspan=2><div align="center">Dept Penyebab</div></th>
            <th rowspan=2><div align="center">Colorist Dye</div></th>
            <th rowspan=2><div align="center">Keterangan</div></th>
            <th rowspan=2><div align="center">Penanggung Jawab Buyer</div></th>
            <th colspan=2><div align="center">ACC Resep Baru</div></th>
            <th rowspan=2><div align="center">Status Resep</div></th>
            <th rowspan=2><div align="center">Tindak Lanjut</div></th>
            <th rowspan=2><div align="center">Pemberi Instruksi Tindak Lanjut</div></th>
            <th rowspan=2><div align="center">Hasil Tindak Lanjut</div></th>
            <th rowspan=2><div align="center">Tindakan</div></th>
            <th rowspan=2><div align="center">Keterangan Tindakan</div></th>
            </tr>
            <tr>
              <th><div align="center">Colorist 1</div></th>
              <th><div align="center">Colorist 2</div></th>
            </tr>
        </thead>
        <tbody>
              <?php
          $no = 1;
          while ($row1 = sqlsrv_fetch_array($qry1, SQLSRV_FETCH_ASSOC)) {
            $stmtUser = sqlsrv_query($cona, "SELECT * FROM db_adm.tbl_user_tindaklanjut WHERE id = ?", [$row1['pemberi_instruksi']]);
            if ($stmtUser === false) { die(print_r(sqlsrv_errors(), true)); }
            $row_user = sqlsrv_fetch_array($stmtUser, SQLSRV_FETCH_ASSOC);
              $id_hasil_celup = $row1['id_hasil_celup_1'];
              $id_schedule = $row1['id_schedule_1'];
              $id_montemp = $row1['id_montemp_1'];

              // Ambil data sebelumnya jika ada
              $analisa = $keterangan = "";
              $saved_dept = [];
              $accresep = $row1['accresep'];
              $accresep2 = $row1['accresep2'];
              
              if (is_string($row1['dept_penyebab'])) {
                  $saved_dept = !empty($row1['dept_penyebab']) ? explode(',', $row1['dept_penyebab']) : [];
              } else {
                  $saved_dept = $row1['dept_penyebab'] ?: [];
              }
              // $qrySaved = mysqli_query($con, "SELECT * FROM tbl_keterangan_gagalproses WHERE id_hasil_celup = '$id_hasil_celup'");
              // if ($res = mysqli_fetch_assoc($qrySaved)) {
              //     $analisa = $res['analisa_penyebab'];
              //     $keterangan = $res['keterangan_gagal_proses'];
              //     $accresep = $res['accresep'];
              //     $saved_dept = explode(",", $res['dept_penyebab']);
              // }
          ?>
          <tr>
              <td><?= $no++; ?></td>
              <td>
                  <a href="?p=Penyelesaian-gagalproses&schedule=<?= $id_schedule ?>&montemp=<?= $id_montemp ?>&hasil_celup=<?= $id_hasil_celup ?>" class="fa fa-pencil-square-o btn">
                      <span class="label label-danger"></span>
                  </a>
              </td>
              <td><?= !empty($row1['tgl_out']) ? $row1['tgl_out']->format('Y-m-d') : '' ?></td>
              <td><?= $row1['nokk'] ?></td>
              <td><?= $row1['nodemand'] ?></td>
              <td><?= $row1['langganan'] ?></td>
              <td><?= $row1['buyer'] ?></td>
              <td><?= $row1['po'] ?></td>
              <td><?= $row1['no_order'] ?></td>
              <td><?= $row1['no_hanger'] ?></td>
              <td><?= htmlspecialchars($row1['jenis_kain']); ?></td>
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
              <td align="center">
                <a href="#" class="analisa-gproses-editable"
                  data-type="textarea"
                  data-pk="<?= htmlspecialchars($id_hasil_celup)?>"
                  data-montemp="<?= htmlspecialchars($id_montemp) ?>"
                  data-hasilcelup="<?= htmlspecialchars($id_hasil_celup) ?>"
                  data-schedule="<?= htmlspecialchars($id_schedule) ?>"
                  data-value="<?= htmlspecialchars($row1['analisa_penyebab']) ?>"
                  data-url="pages/editable/cqa/gagal_proses/update_analisa_proses.php"
                  data-title="Input Suffix">
                    <?php 
                        echo !empty($row1['analisa_penyebab']) ? htmlspecialchars($row1['analisa_penyebab']) : 'Klik untuk isi'; 
                    ?>
                </a>
              </td>
              <!-- Editable: Dept Penyebab -->
                <td align="center">
                   <a href="#" class="dept-gproses-editable" 
                    data-type="checklist" data-pk="<?= htmlspecialchars($id_hasil_celup) ?>" 
                    data-montemp="<?= htmlspecialchars($id_montemp) ?>"
                    data-hasilcelup="<?= htmlspecialchars($id_hasil_celup) ?>"
                    data-schedule="<?= htmlspecialchars($id_schedule) ?>"
                    data-value="<?= htmlspecialchars(json_encode($saved_dept)) ?>" 
                    data-url="pages/editable/cqa/gagal_proses/update_dept_penyebab.php" 
                    data-title="Pilih Dept Penyebab">
                      <?php
                          echo !empty($saved_dept) ? htmlspecialchars(implode(', ', $saved_dept)) : 'Pilih Dept'; 
                      ?>
                  </a>
                </td>
              
              <td align="left"><?php echo $row1['acc_keluar']; ?></td>
              
              <!-- Editable: Keterangan -->
              <td align="center">
                <a href="#" class="keterangan-gproses-editable"
                  data-type="textarea"
                  data-pk="<?= htmlspecialchars($id_hasil_celup)?>"
                  data-montemp="<?= htmlspecialchars($id_montemp) ?>"
                  data-hasilcelup="<?= htmlspecialchars($id_hasil_celup) ?>"
                  data-schedule="<?= htmlspecialchars($id_schedule) ?>"
                  data-value="<?= htmlspecialchars($row1['keterangan_gagal_proses']) ?>"
                  data-url="pages/editable/cqa/gagal_proses/update_keterangan_penyebab.php"
                  data-title="Input Suffix">
                    <?php 
                        echo !empty($row1['keterangan_gagal_proses']) ? htmlspecialchars($row1['keterangan_gagal_proses']) : 'Klik untuk isi'; 
                    ?>
                </a>
              </td>
              <!-- Editable: Penanggung Jawab -->
              <td style="width: 100%;">
                <div class="col-sm-12">
                  <select class="form-control penanggungjawab-select" 
                          data-id="<?= $id_hasil_celup ?>">
                    <option value="" disabled <?= empty($row1['penanggungjawabbuyer']) ? 'selected' : '' ?>>Pilih</option>
                    <?php
                      $stmtPJ = sqlsrv_query($con, "SELECT nama FROM db_dying.user_penanggungjawab WHERE status_active = 1");
                      if ($stmtPJ === false) { die(print_r(sqlsrv_errors(), true)); }

                      while ($row = sqlsrv_fetch_array($stmtPJ, SQLSRV_FETCH_ASSOC)) {
                        $selected = ($row1['penanggungjawabbuyer'] == $row['nama']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row['nama']) . '" ' . $selected . '>' . htmlspecialchars($row['nama']) . '</option>';
                      }
                    ?>
                  </select>
                </div>
              </td>
              <td style="width: 100%;">
                  <?php
                      $accresep_nama = 'Pilih';
                      if (!empty($accresep)) {
                          $stmtAcc = sqlsrv_query($con, "SELECT nama FROM db_dying.user_acc_resep WHERE id = ?", [$accresep]);
                          if ($stmtAcc === false) { die(print_r(sqlsrv_errors(), true)); }
                          if ($row_nama = sqlsrv_fetch_array($stmtAcc, SQLSRV_FETCH_ASSOC)) {
                              $accresep_nama = $row_nama['nama'];
                          }
                      }
                  ?>
                  <a href="#" class="acc-recipe-gproses-editable" 
                    data-type="select" 
                    data-pk="<?= htmlspecialchars($id_hasil_celup) ?>" 
                    data-montemp="<?= htmlspecialchars($id_montemp) ?>"
                    data-hasilcelup="<?= htmlspecialchars($id_hasil_celup) ?>"
                    data-schedule="<?= htmlspecialchars($id_schedule) ?>"
                    data-value="<?= htmlspecialchars($accresep) ?>" 
                    data-url="pages/editable/cqa/gagal_proses/update_acc_resep.php" 
                    data-title="Pilih ACC Resep">
                      <?= htmlspecialchars($accresep_nama) ?>
                  </a>
              </td>
              <td style="width: 100%;">
                  <?php
                      $accresep2_nama = 'Pilih';
                      if (!empty($accresep2)) {
                          $stmtAcc = sqlsrv_query($con, "SELECT nama FROM db_dying.user_acc_resep WHERE id = ?", [$accresep2]);
                          if ($stmtAcc === false) { die(print_r(sqlsrv_errors(), true)); }
                          if ($row_nama = sqlsrv_fetch_array($stmtAcc, SQLSRV_FETCH_ASSOC)) {
                              $accresep2_nama = $row_nama['nama'];
                          }
                      }
                  ?>
                  <a href="#" class="acc-recipe2-gproses-editable" 
                    data-type="select" 
                    data-pk="<?= htmlspecialchars($id_hasil_celup) ?>" 
                    data-montemp="<?= htmlspecialchars($id_montemp) ?>"
                    data-hasilcelup="<?= htmlspecialchars($id_hasil_celup) ?>"
                    data-schedule="<?= htmlspecialchars($id_schedule) ?>"
                    data-value="<?= htmlspecialchars($accresep2) ?>" 
                    data-url="pages/editable/cqa/gagal_proses/update_acc_resep2.php" 
                    data-title="Pilih ACC Resep">
                      <?= htmlspecialchars($accresep2_nama) ?>
                  </a>
              </td>
              <td align="left"><?php echo $row1['status_resep']; ?></td>
              <td align="left"><?php echo $row1['tindak_lanjut']; ?></td>
              <td align="left">
                              <?php 
                                  $list_nama = sqlsrv_query($cona, "SELECT nama FROM db_adm.tbl_user_tindaklanjut WHERE id = ?", [$row1['pemberi_instruksi']]);
                                  if ($list_nama === false) { die(print_r(sqlsrv_errors(), true)); }
                                  $r_nama = sqlsrv_fetch_array($list_nama, SQLSRV_FETCH_ASSOC);
                                  echo $r_nama['nama'] ?? '';
                                  ?>
                              <?php echo $r_nama['nama']; ?>
              </td>
              <td align="left"><?php echo $row1['hasil_tindak_lanjut']; ?></td>
              <td align="left"><?php echo $row1['tindakan_tindak_lanjut']; ?></td>
              <td align="left"><?php echo $row1['keterangan_tindak_lanjut']; ?></td>
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