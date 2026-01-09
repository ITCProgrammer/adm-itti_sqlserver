<?PHP
  ini_set("error_reporting", 1);
  session_start();
  include "koneksi.php";
  $username = $_SESSION['user_id10'];

?>

<?php
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  $daftarProses = [];
  $queryProses = mysqli_query($con, "SELECT DISTINCT proses FROM tbl_proses ORDER BY proses ASC");
  while ($rowProses = mysqli_fetch_assoc($queryProses)) {
    $daftarProses[] = $rowProses['proses'];
  }
  $statusProses = [];
  $queryStatusProses = mysqli_query($con, "SELECT * FROM tbl_status_proses ORDER BY nama ASC");
  while ($rowStatusProses = mysqli_fetch_assoc($queryStatusProses)) {
    $statusProses[] = $rowStatusProses['nama'];
  }
?>
<?php
  $Awal  = isset($_POST['awal']) ? $_POST['awal'] : '';
  $Akhir  = isset($_POST['akhir']) ? $_POST['akhir'] : '';
  $GShift  = isset($_POST['gshift']) ? $_POST['gshift'] : '';
  $Fs    = isset($_POST['fasilitas']) ? $_POST['fasilitas'] : '';
  $jamA  = isset($_POST['jam_awal']) ? $_POST['jam_awal'] : '';
  $jamAr  = isset($_POST['jam_akhir']) ? $_POST['jam_akhir'] : '';
  $Rcode  = isset($_POST['rcode']) ? $_POST['rcode'] : '';
  if (strlen($jamA) == 5) {
    $start_date = $Awal . ' ' . $jamA;
  } else {
    $start_date = $Awal . ' 0' . $jamA;
  }
  if (strlen($jamAr) == 5) {
    $stop_date  = $Akhir . ' ' . $jamAr;
  } else {
    $stop_date  = $Akhir . ' 0' . $jamAr;
  }	
    
  if($Awal!="" && $Akhir!=""){
    $Tgl = substr($start_date, 0, 10);
    if ($start_date != $stop_date) {
      $Where = " DATE_FORMAT(c.tgl_update, '%Y-%m-%d %H:%i') BETWEEN '$start_date' AND '$stop_date' ";
    } else {
      $Where = " DATE_FORMAT(c.tgl_update, '%Y-%m-%d')='$Tgl' ";
    }
    if ($GShift == "ALL") {
      $shft = " ";
    } else {
      $shft = " if(ISNULL(a.g_shift),c.g_shift,a.g_shift)='$GShift' AND ";
    }
    $sql = mysqli_query($con, "SELECT x.*, a.no_mesin as mc 
                                  FROM tbl_mesin a
                                      LEFT JOIN
                                      (SELECT
                                      a.ket,	if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))) as lama_proses,
                                      a.status as sts,
                                      TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%H') as jam,
                                      TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%i') as menit,
                                      a.proses as proses,
                                      b.proses as schedule_proses,
                                      b.buyer,
                                      b.langganan,
                                      b.no_order,
                                      b.no_mesin,
                                      b.warna,
                                      b.dyestuff,	
                                      b.kapasitas,
                                      b.loading,
                                      a.resep,
                                      CASE
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'D' THEN 'Dark'
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'H' THEN 'Heater'
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'L' THEN 'Light'
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'M' THEN 'Medium'
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'S' THEN 'Dark'
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'W' THEN 'White'
                                      END AS kategori_warna,
                                      c.l_r,
                                      c.rol,
                                      c.bruto,
                                      a.g_shift as shft,
                                      a.k_resep,
                                      a.status,
                                      a.proses_point,
                                      b.nokk,
                                      b.lebar,
                                      c.carry_over,
                                      b.po,	
                                      b.kk_kestabilan,
                                      b.kk_normal,
                                      c.air_awal,
                                      a.air_akhir,
                                      c.nodemand,
                                      c.operator,
                                      a.id as idhslclp,   
                                      b.id as idshedule,
                                      c.id as idmontemp,
		                                  a.status_proses,
                                      COALESCE(a.point2, b.target) as point2
                                    FROM
                                      tbl_schedule b
                                        LEFT JOIN  tbl_montemp c ON c.id_schedule = b.id
                                        LEFT JOIN tbl_hasilcelup a ON a.id_montemp=c.id	
                                    WHERE
                                      $shft 
                                      $Where
                                      )x ON (a.no_mesin=x.no_mesin) ORDER BY a.no_mesin");
    $no = 1;
    $c = 0;
    $totrol = 0;
    $totberat = 0;
    $data=array();
    $mc_all=array();
    $nokk_all=array();
    while ($rowd = mysqli_fetch_assoc($sql)) {
      $rl ="";
      if (strlen($rowd['rol']) > 5) {
        $jk = strlen($rowd['rol']) - 5;
        $rl = substr($rowd['rol'], 0, $jk);
      } else {
        $rl = $rowd['rol'];
      }
      $rowd['rl']=$rl;
      
      $data[]=$rowd;
      $mc_all[]=$rowd['mc'];
      if($rowd['nokk']!=""){
        $nokk_all[]=$rowd['nokk'];
      }
    }
    $mesin_join= join("','",$mc_all);
    if ($GShift == "ALL") {
      $shftSM = " ";
    } else {
      $shftSM = " g_shift='$GShift' AND ";
    }
    $mesin=array();
    $sqlSM = mysqli_query($con, "SELECT *, TIME_FORMAT(timediff(selesai,mulai),'%H:%i') as menitSM,
                    DATE_FORMAT(mulai,'%Y-%m-%d') as tgl_masuk,
                    DATE_FORMAT(selesai,'%Y-%m-%d') as tgl_selesai,
                    TIME_FORMAT(mulai,'%H:%i') as jam_masuk,
                    TIME_FORMAT(selesai,'%H:%i') as jam_selesai,
                    kapasitas as kapSM,
                    g_shift as shiftSM
                    FROM tbl_stopmesin
                    WHERE $shftSM tgl_update BETWEEN '$start_date' AND '$stop_date' AND no_mesin IN ('$mesin_join')");
    while ($rowSM = mysqli_fetch_assoc($sqlSM)) {
      $mesin[$rowSM['no_mesin']]=$rowSM;
    }
                
    $subcode_all=array();
    $nokk_join= join("','",$nokk_all);
                
    $q_itxviewkk = db2_exec($conn2, "SELECT TRIM(PRODUCTIONORDERCODE) AS PRODUCTIONORDERCODE,
                                        LISTAGG(DISTINCT TRIM(LOT), ', ') AS LOT,
                                        LISTAGG(DISTINCT TRIM(SUBCODE01), ', ') AS SUBCODE01 
                                      FROM 
                                        ITXVIEWKK 
                                        WHERE PRODUCTIONORDERCODE IN ('$nokk_join')
                                  GROUP BY PRODUCTIONORDERCODE; ");
    while ($rowCode = db2_fetch_assoc($q_itxviewkk)) {
      $subcode_all[$rowCode['PRODUCTIONORDERCODE']]=$rowCode;
    }
    $lama_proses_all=array();
    $qryLama = mysqli_query($con, "SELECT b.nokk,
                                      TIME_FORMAT( timediff( now(), b.tgl_buat ), '%H:%i' ) AS lama 
                                    FROM
                                      tbl_schedule a
                                    LEFT JOIN tbl_montemp b ON a.id = b.id_schedule 
                                    WHERE
                                       b.nokk IN ('$nokk_join')
                                    AND b.STATUS = 'sedang jalan' 
                                      ORDER BY
                                    a.no_urut ASC");
    while ($rLama = mysqli_fetch_array($qryLama)) {
      $lama_proses_all[$rLama['nokk']]=$rLama;
    }
  }  
  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Laporan Harian Produksi (Baru)</title>
  <style>
    #editModal{
      overflow-x: hidden !important;
      overflow-y: auto !important;
    }

    .sticky-col {
      position: sticky;
      left: 0;
      z-index: 2;
    }

    thead .sticky-col {
      z-index: 3;
    }
  </style>
</head>

<body>
  
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title"> Filter Laporan Harian Produksi (Baru)</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
      <div class="box-body">
        <div class="form-group">
          <div class="col-sm-3">
            <div class="input-group date">
              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
              <input name="awal" type="text" class="form-control pull-right" id="datepicker" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off" />
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="text" class="form-control " name="jam_awal" placeholder="00:00" value="<?php echo $jamA; ?>"  autocomplete="off">
              <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
              </div>
            </div>
            <div>
            </div>
          </div>
          <!-- /.input group -->
        </div>

        <div class="form-group">
          <div class="col-sm-3">
            <div class="input-group date">
              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
              <input name="akhir" type="text" class="form-control pull-right" id="datepicker1" placeholder="Tanggal Akhir" value="<?php echo $Akhir;  ?>" autocomplete="off" />
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="text" class="form-control " name="jam_akhir" placeholder="00:00" value="<?php echo $jamAr; ?>" autocomplete="off">
              <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-3">
            <select name="gshift" class="form-control pull-right">
              <option value="ALL">ALL</option>
              <option value="A" <?php if ($GShift == "A") {
                                  echo "SELECTED";
                                } ?>>A</option>
              <option value="B" <?php if ($GShift == "B") {
                                  echo "SELECTED";
                                } ?>>B</option>
              <option value="C" <?php if ($GShift == "C") {
                                  echo "SELECTED";
                                } ?>>C</option>
            </select>
          </div>
        
          <div class="col-sm-2">
            <!-- <input type="text" class="form-control" name="rcode" value="<?= $Rcode; ?>" placeholder="Rcode"> -->
          </div>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <div class="col-sm-2">
          <button type="submit" class="btn btn-block btn-social btn-default btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>
        </div>
        
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Laporan Harian Produksi (Baru)</h3><br><br>
          <div class="btn-group pull-right">
            <a href="pages/cetak/NewCetakReportDyeing.php?&awal=<?php echo $start_date; ?>&akhir=<?php echo $stop_date; ?>&shft=<?php echo $GShift; ?>" class="btn bg-maroon" target="_blank" data-toggle="tooltip" data-html="true" title="New Format Harian Produksi Excel"><i class="fa fa-file-excel-o"></i> </a>
          </div>
          <?php if ($_POST['awal'] != "") { ?><b>Periode: <?php echo $start_date . " to " . $stop_date; ?></b>
            <div class="btn-group pull-right">
             
            </div>
          <?php }elseif($Rcode != ""){ ?>
            <div class="btn-group pull-right">              
              
            </div>
          <?php } ?>
        </div>
        <div class="box-body">
          <table id="example1" class="table table-bordered table-hover" width="100%">
            <thead class="btn-danger">
              <tr>
                <th class="sticky-col" width="38">
                  <div >Aksi</div>
                </th>
                <th width="38">
                  <div >NO.</div>
                </th>
                <th width="224">
                  <div >NO MC</div>
                </th>
                <th width="215">
                  <div align="center">KAPASITAS</div>
                </th>
                <th width="38">SHIFT</th>
                <th width="314">
                  <div align="center">BUYER</div>
                </th>
                <th width="404">
                  <div align="center">NO ORDER</div>
                </th>
                <th width="215">
                  <div align="center">Prod. Demand</div>
                </th>
                <th width="404">
                  <div align="center">KODE</div>
                </th>
                <th width="404">
                  <div align="center">QTY</div>
                </th>
                <th width="404">
                  <div align="center">K.W</div>
                </th>
                <th width="404">
                  <div align="center">WARNA</div>
                </th>
                <th width="404">
                  <div align="center">PROSES</div>
                </th>
                <th width="215">
                  <div align="center">LOADING</div>
                </th>
                <th width="215">
                  <div align="center">POINT PROSES</div>
                </th>
                <th width="215">
                  <div align="center">SCHEDULE PROSES</div>
                </th>
                <th width="215">
                  <div align="center">L:R</div>
                </th>
                <th width="215">
                  <div align="center">KETERANGAN</div>
                </th>
                <th width="215">
                  <div align="center">K.R</div>
                </th>
                <th width="215">
                  <div align="center">R.B/R.L</div>
                </th>
                <th width="215">
                  <div align="center">STATUS</div>
                </th>
                <th width="215">
                  <div align="center">REMARKS</div>
                </th>
                <th width="215">
                  <div align="center">DYESTUFF</div>
                </th>
                <th width="215">
                  <div align="center">LAMA PROSES</div>
                </th>
                <th width="215">
                  <div align="center">POINT</div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 1;
                foreach ($data as $rowd) {
                ?>
                  <tr data-id="<?=$rowd['id']?>" class="table table-bordered table-hover table-striped">
                    <td class="sticky-col">
                      <button class="btn btn-primary btn-edit"
                              data-id="<?= $rowd['id'] ?>"
                              data-shift="<?= $rowd['shft'] ?>"
                              data-mc="<?= $rowd['mc'] ?>"
                              data-kapasitas="<?= $rowd['kapasitas'] ?>"
                              data-buyer="<?= $rowd['buyer'] ?>"
                              data-no_order="<?= $rowd['no_order'] ?>"
                              data-nodemand="<?= $rowd['nodemand'] ?>"
                              data-SUBCODE01="<?=$subcode_all[$rowd['nokk']]['SUBCODE01'] ?? ''?>"
                              data-kategori_warna="<?= $rowd['kategori_warna'] ?>"
                              data-proses="<?= $rowd['proses'] ?>",
                              data-status_proses="<?= $rowd['status_proses'] ?>",
                              data-loading="<?= $rowd['loading'] ?>"
                              data-l_r="<?= $rowd['l_r'] ?>"
                              data-ket="<?= $rowd['ket'] ?>"
                              data-k_resep="<?= $rowd['k_resep'] ?>"
                              data-resep="<?= $rowd['resep'] ?>"
                              data-sts="<?= $rowd['sts'] ?>"
                              data-dyestuff="<?= $rowd['dyestuff'] ?>"
                              data-lama_proses="<?= $rowd['lama_proses'] ?>"
                              data-point2="<?= $rowd['point2'] ?>"
                              data-nokk="<?= $rowd['nokk'] ?>"
                              data-idhslclp="<?= $rowd['idhslclp'] ?>"
                              data-idshedule="<?= $rowd['idshedule'] ?>"
                              data-idmontemp="<?= $rowd['idmontemp'] ?>"

                      >Edit
                      </button>
                    </td>
                    <td><?=$no?></td>
                    <td><?=$rowd['mc']?></td>
                    <td><?=$rowd['kapasitas']?></td>
                    <td id="shift<?= $rowd['idhslclp'] ?>"><?=$rowd['shft']?></td>
                    <td id="buyer<?= $rowd['idhslclp'] ?>"><?=$rowd['buyer']?></td>
                    <td><?=$rowd['no_order']?></td>
                    <td><?=$rowd['nodemand']?></td>
                    <td><?=$subcode_all[$rowd['nokk']]['SUBCODE01'] ?? ''?></td>
                    <td><?= empty($rowd['lama_proses']) ? '-' : $rowd['bruto'] ?></td>
                    <td id="kategori_warna<?= $rowd['idhslclp'] ?>"><?=$rowd['kategori_warna']?></td>
                    <td><?=$rowd['warna']?></td></td>
                    <td id="proses<?= $rowd['idhslclp'] ?>"><?=$rowd['proses']?></td>
                    <td><?=$rowd['loading']?> %</td>
                    <td><?=$rowd['proses_point']?></td>
                    <td><?=$rowd['schedule_proses']?></td>
                    <td><?=$rowd['l_r']?></td>
                    <td><?=$rowd['ket']?></td>
                    <td id="k_resep<?= $rowd['idhslclp'] ?>"><?=$rowd['k_resep']?></td>
                    <td id="resep<?= $rowd['idhslclp'] ?>"><?=$rowd['resep']?></td>
                    <td id="sts<?= $rowd['idhslclp'] ?>"><?=$rowd['sts']?></td>
                    <td><?=$rowd['status_proses']?></td>
                    <td><?=$rowd['dyestuff']?></td>
                    <td><?=$rowd['lama_proses']?></td>
                    <td><?=$rowd['point2']?></td>
                  </tr>
                <?php
                  $no++;
                }
                ?>
            </tbody>
          </table>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="modalDetailPoLabel" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <h4 class="mb-2 font-weight-bold text-primary">Form Edit</h4>
                    <div class="row">
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputShift" class="form-label">Shift</label>
                        <select class="form-control" id="inputShift" name="shift" required>
                          <option value="">-- Pilih Shift --</option>
                          <option value="A">A</option>
                          <option value="B">B</option>
                          <option value="C">C</option>
                        </select>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputMc" class="form-label">Mesin</label>
                        <input type="text" class="form-control" id="inputMc" placeholder="0" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputKapasitas" class="form-label">Kapasitas</label>
                        <input type="text" class="form-control readonly" id="inputKapasitas" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputBuyer" class="form-label">Buyer</label>
                        <input type="text" class="form-control readonly" id="inputBuyer" autocomplete="off">
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputNoOrder" class="form-label">No Order</label>
                        <input type="text" class="form-control readonly" id="inputNoOrder" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputNodemand" class="form-label">No Demand</label>
                        <input type="text" class="form-control readonly" id="inputNodemand" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputSubcode01" class="form-label">Kode</label>
                        <input type="text" class="form-control readonly" id="inputSubcode01" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputKategoriWarna" class="form-label">K Warna</label>
                        <input type="text" class="form-control readonly" id="inputKategoriWarna" autocomplete="off">
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputProses" class="form-label" style="display: block;">Proses</label>
                        <select class="form-control" id="inputProses" name="proses" style="display: inline;width: 85%;" required>
                            <option value="">-- Pilih Proses --</option>
                            <!-- Tambahan manual -->
                            <option value="Gagal Proses">Gagal Proses</option>
                            <option value="Tolak Basah">Tolak Basah</option>
                            <option value="Tolak Basah Luntur">Tolak Basah Luntur</option>
                            <?php foreach ($daftarProses as $proses): ?>
                              <option value="<?= htmlspecialchars($proses) ?>"><?= htmlspecialchars($proses) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button href="#" data-toggle="modal" data-target="#DataLogProses" class="btn btn-primary" style="display: inline;width: 13%;height: 28px;">...</button>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputLoading" class="form-label">Loading</label>
                        <input type="text" class="form-control readonly" id="inputLoading" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputLR" class="form-label">L R</label>
                        <input type="text" class="form-control readonly" id="inputLR" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputKet" class="form-label">Keterangan</label>
                        <input type="text" class="form-control readonly" id="inputKet" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputKResep" class="form-label">K Resep</label>
                        <select class="form-control" id="inputKResep" name="KResep" required>
                          <option value="" >-</option>
                          <option value="0x">0x</option>
                          <option value="1x">1x</option>
                          <option value="2x">2x</option>
                          <option value="3x">3x</option>
                          <option value="4x">4x</option>
                          <option value="5x">5x</option>
                          <option value="6x">6x</option>
                          <option value="7x">7x</option>
                          <option value="8x">8x</option>
                          <option value="9x">9x</option>
                          <option value="10x">10x</option>
                          <option value=">10x">>10x</option>
                        </select>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputResep" class="form-label">Resep</label>
                        <select class="form-control" id="inputResep" name="Resep" required>
                          <option value="">-</option>
                          <option value="Baru">Baru</option>
                          <option value="Lama">Lama</option>
                          <option value="Setting">Setting</option>
                        </select>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputSts" class="form-label">Status</label>
                        <select class="form-control" id="inputSts" autocomplete="off" required>
						            	<option value="">Pilih</option>
						            	<option value="OK">OK</option>
						            	<option value="Celup Poly Dulu-Matching">Celup Poly Dulu-Matching</option>
						            	<option value="Gagal Proses">Gagal Proses</option>
						            	<option value="Levelling-Matching">Levelling-Matching</option>
						            	<option value="Pelunturan-Matching">Pelunturan-Matching</option>
						            	<option value="Scouring Turun">Scouring Turun</option>
						            	<option value="Continuous - Bleaching">Continuous - Bleaching</option>
						            	<option value="Relaxing - Priset">Relaxing - Priset</option>
						            	<option value="Tunggu Review">Tunggu Review</option>
						            </select>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputRemarks" class="form-label">Remarks</label>
                        <select class="form-control" id="inputRemarks" name="Remarks">
                          <option value=""></option>
                          <?php foreach ($statusProses as $sts): ?>
                            <option value="<?= htmlspecialchars($sts) ?>"><?= htmlspecialchars($sts) ?></option>
                          <?php endforeach; ?>
                        </select>
                        <!-- <button href="#" data-toggle="modal" data-target="#DataStatusProses" class="btn btn-primary" style="display: inline;width: 13%;height: 28px;">...</button> -->
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputDyestuff" class="form-label">Dye Stuff</label>
                        <input type="text" class="form-control readonly" id="inputDyestuff" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputLamaProses" class="form-label">Lama Proses</label>
                        <input type="text" class="form-control readonly" id="inputLamaProses" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputPoint2" class="form-label">Point</label>
                        <input type="text" class="form-control readonly" id="inputPoint2" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputCarryOver" class="form-label">Carry Over</label>
                        <input type="text" class="form-control readonly" id="inputCarryOver" autocomplete="off" readonly>
                      </div>
                      <!-- <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputAirAwal" class="form-label">Air Awal</label>
                        <input type="text" class="form-control readonly" id="inputAirAwal" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputAirAkhir" class="form-label">Air Akhir</label>
                        <input type="text" class="form-control readonly" id="inputAirAkhir" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputPakeAir" class="form-label">Total Pemakaian Air</label>
                        <input type="text" class="form-control readonly" id="inputPakeAir" autocomplete="off" readonly>
                      </div> -->

                      <input type="hidden" id="typeSave" name="typeSave" value="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="btnModalCancel" type="cancel" class="btn btn-secondary">Cancel</button>
          <button id="btnModalSave" type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade modal-super-scaled" id="DataLogProses" >
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Data Log Proses</h4>
          </div>
          <div class="modal-body">
            <table id="log_proses" class="table table-bordered table-hover table-striped" width="100%">
              <thead class="bg-green">
                <tr>
                  <th width="144">
                    <div align="center">Waktu</div>
                  </th>
                  <th width="144">
                    <div align="center">Data Awal</div>
                  </th><th width="144">
                    <div align="center">Data Akhir</div>
                  </th>
                </tr>
              </thead>
              <tbody id="log_body">
            
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>

  <div class="modal fade modal-super-scaled" id="DataStatusProses">
	<div class="modal-dialog ">
		<div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=simpan_status_proses" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Data Status Proses</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="nama" class="col-md-3 control-label">Input Status Proses</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="nama" name="nama" required>
							<span class="help-block with-errors"></span>
						</div>
					</div>
					<table id="example2" class="table table-bordered table-hover table-striped" width="100%">
						<thead class="bg-green">
							<tr>
								<th width="144">
									<div align="center">Status Proses</div>
								</th>
								<th width="144">
									<div align="center">Action</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$c = 1;
							$sqlAn1 = mysqli_query($con, "SELECT * FROM tbl_status_proses ORDER BY nama ASC");
							while ($rAn1 = mysqli_fetch_array($sqlAn1)) {
								$bgcolor = ($c++ & 1) ? '#33CCFF' : '#FFCC99';
							?>
								<tr bgcolor="<?php echo $bgcolor; ?>">
									<td align="center">
										<?php echo $rAn1['nama']; ?>
									</td>
									<td align="center">
										<a href="#" class="btn btn-danger btn-xs" onClick="hapusStatusProses('<?php echo $rAn1['id']; ?>');" title="Hapus Status Proses"><i class="fa fa-trash"></i></a>
									</td>
								</tr>
							<?php
							} ?>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript" src="dist/js/jquery.redirect.js"></script>

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
      $('#DataLogProses').on('show.bs.modal', function() {
        let dataPost={status:"get_log",id_dt: idhasilcelup};
        $("#log_body").html("");
        $.ajax({
          url: "pages/ajax/simpan_harian_produksi_baru.php",
          type: "POST",
          data: dataPost,
          dataType: "JSON",
          success: function(response){
            if(response.success){  
              let row="";
              $.each( response.data, function( key, value ) {
                row+=`
                <tr>
                  <td>`+value.update_at+`</td>
                  <td>`+value.proses_lama+`</td>
                  <td>`+value.proses_baru+`</td>
                </tr>
                `;
              });
              $("#log_body").append(row);
            }
          }
        });
      });
    });
  </script>

  <script>
    let originalData = {};
    let no_kk = "NONE"
    let idhasilcelup = 0
    let idshedule = 0    
    let idmontemp = 0
    const editable=["shift","buyer","kategori_warna","proses","k_resep","resep","sts","air_awal","air_akhir"];   
    var btn=null;

    $(document).ready(function () {
      $('.btn-edit').click(function () {
        btn = $(this);

        no_kk = btn.data('nokk')
        idhasilcelup = btn.data('idhslclp')
        idshedule = btn.data('idshedule')
        idmontemp = btn.data('idmontemp')

        // Simpan data awal
        originalData = {
          shift: btn.data('shift'),
          mc: btn.data('mc'),
          kapasitas: btn.data('kapasitas'),
          buyer: btn.data('buyer'),
          no_order: btn.data('no_order'),
          nodemand: btn.data('nodemand'),
          subcode01: btn.data('subcode01'),
          kategori_warna: btn.data('kategori_warna'),
          proses: btn.data('proses'),
          remarks: btn.data('status_proses'),
          loading: btn.data('loading'),
          l_r: btn.data('l_r'),
          ket: btn.data('ket'),
          k_resep: btn.data('k_resep'),
          resep: btn.data('resep'),
          sts: btn.data('sts'),
          dyestuff: btn.data('dyestuff'),
          lama_proses: btn.data('lama_proses'),
          point2: btn.data('point2'),
          carry_over: btn.data('carry_over'),
          air_awal: btn.data('air_awal'),
          air_akhir: btn.data('air_akhir'),
          nokk: btn.data('nokk'),
          idhslclp: btn.data('idhslclp')
        };

        for (const key in originalData) {
          $('#input' + toCamelCase(key)).val(originalData[key]);
        }

        $('#typeSave').val(btn.data('id'));

        $('#editModal').modal({
          backdrop: 'static',
          keyboard: false
        });
      });
      $('#btnModalCancel').click(function () {
        if (isFormChanged()) {
          if (confirm("Ada data yang dirubah. Yakin ingin keluar tanpa menyimpan?")) {
            $('#editModal').modal('hide');
          }
        } else {
          $('#editModal').modal('hide');
        }
      });
      $('#btnModalSave').click(function () {
        const nokk = $(this).data('nokk');
        const id = $(this).data('idhslclp');

        if (!isFormChanged()) {
          alert("Tidak ada perubahan data untuk disimpan.");
          return;
        }

        let dataPost={status:"insert_log", id_dt : idhasilcelup, proses_lama:originalData['proses'],proses_baru:$('#inputProses').val()};
        $.ajax({
          url: "pages/ajax/simpan_harian_produksi_baru.php",
          type: "POST",
          data: dataPost,
          dataType: "JSON",
          success: function(response){
            if(response.success){  
              console.log("Log Proses Saved");
            }else{
              console.log("Log Proses NOT SAVED : "+response.messages.join(" , "));
            }
          }
        });

        let dataPostUpdate={status:"update_laporan", id_dt : idhasilcelup, idshedule : idshedule, idmontemp : idmontemp, nokk: no_kk};
        for (let i = 0; i < editable.length; i++) {
          let key =editable[i];
          dataPostUpdate[key]= $('#input' + toCamelCase(key)).val();
        }
        
        $.ajax({
          url: "pages/ajax/simpan_harian_produksi_baru.php",
          type: "POST",
          data: dataPostUpdate,
          dataType: "JSON",
          success: function(response){
            if(response.success){  
              console.log("Data Updated");
              
              for (let i = 0; i < editable.length; i++) {
                let key =editable[i];
                $('#'+key+idhasilcelup).html(dataPostUpdate[key]);
                btn.data(key,dataPostUpdate[key])
              }
              $('#editModal').modal("hide");
            }else{
              console.log("Data Not Updated : "+response.messages.join(" , "));
            }
          }
        });
      });

      window.addEventListener('beforeunload', function (e) {
        if ($('#editModal').hasClass('show') && isFormChanged()) {
          e.preventDefault();
          e.returnValue = '';
        }
      });

      function getCurrentFormData() {
        return {
          shift: $('#inputShift').val(),
          mc: $('#inputMc').val(),
          kapasitas: $('#inputKapasitas').val(),
          buyer: $('#inputBuyer').val(),
          no_order: $('#inputNoOrder').val(),
          nodemand: $('#inputNodemand').val(),
          subcode01: $('#inputSubcode01').val(),
          kategori_warna: $('#inputKategoriWarna').val(),
          proses: $('#inputProses').val(),
          loading: $('#inputLoading').val(),
          l_r: $('#inputLR').val(),
          ket: $('#inputKet').val(),
          k_resep: $('#inputKResep').val(),
          resep: $('#inputResep').val(),
          sts: $('#inputSts').val(),
          dyestuff: $('#inputDyestuff').val(),
          lama_proses: $('#inputLamaProses').val(),
          point2: $('#inputPoint2').val(),
          carry_over: $('#inputCarryOver').val(),
          air_awal: $('#inputAirAwal').val(),
          air_akhir: $('#inputAirAkhir').val(),
          pake_air: $('#inputPakeAir').val()
        };
      }
      function isFormChanged() {
        const currentData = getCurrentFormData();
         
        let changed = false;
        for (let i = 0; i < editable.length; i++) {
          let key =editable[i];
          if (originalData[key] != currentData[key]) {
            changed = true;
          }
        }
        return changed;
      }
      function toCamelCase(str) {
        return str.replace(/_([a-z])/g, function (g) { return g[1].toUpperCase(); }).replace(/^([a-z])/,
          function (g) { return g.toUpperCase(); });
      }
      function toPascalCase(str) {
        return str
          .split('_')
          .map(word => word.charAt(0).toUpperCase() + word.slice(1))
          .join('');
      }
    });
  </script>
</body>

</html>