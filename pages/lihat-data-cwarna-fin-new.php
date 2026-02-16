<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Laporan Harian QCF</title>
</head>

<body>
  <?php
  $Awal    = isset($_POST['awal']) ? trim($_POST['awal']) : '';
  $Akhir   = isset($_POST['akhir']) ? trim($_POST['akhir']) : '';
  $Dept    = isset($_POST['dept']) ? $_POST['dept'] : '';
  $Kategori = isset($_POST['kategori']) ? $_POST['kategori'] : '';
  $Cancel  = isset($_POST['chkcancel']) ? $_POST['chkcancel'] : '';
  $Rev2A   = isset($_POST['chkrev']) ? $_POST['chkrev'] : '';
  $jamA    = isset($_POST['jam_awal']) ? trim($_POST['jam_awal']) : '';
  $jamAr   = isset($_POST['jam_akhir']) ? trim($_POST['jam_akhir']) : '';

  if (isset($_POST['gshift']) && $_POST['gshift'] == "ALL") {
    $shft = " ";
  } else {
    $shft = " AND b.g_shift = '$GShift' ";
  }
  if ($jamA === '' || $jamA === '0')  $jamA  = '00:00';
  if ($jamAr === '' || $jamAr === '0') $jamAr = '23:59';

  if (strlen($jamA) == 4)  $jamA  = '0' . $jamA;
  if (strlen($jamAr) == 4) $jamAr = '0' . $jamAr;

  if ($Awal === '' || $Awal === '0')  $Awal  = date('Y-m-d');
  if ($Akhir === '' || $Akhir === '0') $Akhir = date('Y-m-d');

  $start_date = $Awal  . ' ' . $jamA  . ':00';
  $stop_date  = $Akhir . ' ' . $jamAr . ':59';
  ?>
  <div class="row">
    <div class="col-xs-2">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"> Filter Laporan Cocok Warna Finishing</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
          <div class="box-body">
            <div class="form-group">
              <div class="col-md-8">
                <div class="input-group date">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input name="awal" type="date" class="form-control pull-right" placeholder="Tanggal Awal" value="<?php if ($Awal1 != "") {
                                                                                                                      echo $Awal1;
                                                                                                                    } else {
                                                                                                                      echo $Awal;
                                                                                                                    } ?>" autocomplete="off" />
                </div>
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control timepicker" name="jam_awal" placeholder="00:00" value="<?php echo $jamA; ?>" autocomplete="off">
              </div>
              <!-- /.input group -->
            </div>
            <div class="form-group">
              <div class="col-md-8">
                <div class="input-group date">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input name="akhir" type="date" class="form-control pull-right" placeholder="Tanggal Akhir" value="<?php if ($Akhir1 != "") {
                                                                                                                        echo $Akhir1;
                                                                                                                      } else {
                                                                                                                        echo $Akhir;
                                                                                                                      } ?>" autocomplete="off" />
                </div>
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control timepicker" name="jam_akhir" placeholder="00:00" value="<?php echo $jamAr; ?>" autocomplete="off">
              </div>
              <!-- /.input group -->
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input name="no_order" type="text" class="form-control pull-right" id="no_order" placeholder="No Order"
                  value="<?php echo $Order; ?>" autocomplete="off" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <input name="po" type="text" class="form-control pull-right" id="po" placeholder="No PO"
                  value="<?php echo $PO; ?>" autocomplete="off" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <select name="gshift" class="form-control select2">
                  <option value="ALL" <?php if ($GShift == "ALL" or $GShift1 == "ALL") {
                                        echo "SELECTED";
                                      } ?>>ALL</option>
                  <option value="A" <?php if ($GShift == "A" or $GShift1 == "A") {
                                      echo "SELECTED";
                                    } ?>>A</option>
                  <option value="B" <?php if ($GShift == "B" or $GShift1 == "B") {
                                      echo "SELECTED";
                                    } ?>>B</option>
                  <option value="C" <?php if ($GShift == "C" or $GShift1 == "C") {
                                      echo "SELECTED";
                                    } ?>>C</option>
                </select>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="row">
              <div class="col-md-6">
                <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save">Search <i
                    class="fa fa-search"></i></button>
              </div>
              <div class="col-md-6">
                <button type="button" class="btn btn-block btn-social btn-linkedin btn-sm btn-default" <?php if ($_SESSION['lvl_id10'] == "AFTERSALES") {
                                                                                                          echo "disabled";
                                                                                                        } ?> name="lihat"
                  onClick="window.location.href='?p=Input-Lap-Cwarna-Fin-New'">Back <i class="fa fa-chevron-left"
                    aria-hidden="true"></i></button>
              </div>
            </div>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>

    <!-- TOP 5 Berdasarkan Buyer -->
    <div class="col-xs-5">
      <?php
      $sqlball = sqlsrv_query($cond, "
        SELECT COUNT(a.nokk) AS jml_kk_all
        FROM db_qc.tbl_lap_inspeksi a
        WHERE (a.proses <> 'Oven' OR a.proses <> 'Fin 1X')
          AND a.dept = 'QCF'
          AND CAST(a.tgl_update AS date) BETWEEN ? AND ?
      ", [$Awal, $Akhir]);

      $rball = sqlsrv_fetch_array($sqlball, SQLSRV_FETCH_ASSOC);
      ?>
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"> TOP 5 Berdasarkan Buyer</h3>
          <?php if ($Awal != "") { ?><br><b>Periode:
            <?php echo tanggal_indo($Awal) . " - " . tanggal_indo($Akhir);
          } ?>
            </b>
            <?php if ($rball['jml_kk_all'] != "") { ?><br><b>Jumlah KK:
              <?php echo $rball['jml_kk_all'];
            } ?>
              </b>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered table-striped" style="width: 100%;">
            <thead class="bg-blue">
              <tr>
                <th width="5%">
                  <div align="center">No</div>
                </th>
                <th width="15%">
                  <div align="center">Buyer</div>
                </th>
                <th width="5%">
                  <div align="center">A</div>
                </th>
                <th width="5%">
                  <div align="center">B</div>
                </th>
                <th width="5%">
                  <div align="center">C</div>
                </th>
                <th width="5%">
                  <div align="center">D</div>
                </th>
                <th width="5%">
                  <div align="center">NULL</div>
                </th>
                <th width="10%">
                  <div align="center">%</div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;

              $sqlby = sqlsrv_query($cond, "
                SELECT TOP 5
                  RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) AS buyer,
                  COUNT(a.nokk) AS jml_kk
                FROM db_qc.tbl_lap_inspeksi a
                WHERE a.proses NOT IN ('Oven','Fin 1X')
                  AND a.dept = 'QCF'
                  AND CONVERT(datetime,
                      CONVERT(varchar(10), a.tgl_update, 120) + ' ' + LEFT(CONVERT(varchar(8), a.jam_update, 108), 5),
                      120
                  ) BETWEEN CONVERT(datetime, ?, 120) AND CONVERT(datetime, ?, 120)
                GROUP BY RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1)
                ORDER BY COUNT(a.nokk) DESC
              ", [$start_date, $stop_date]);
              while ($rby = sqlsrv_fetch_array($sqlby, SQLSRV_FETCH_ASSOC)) {

                $buyerVal = $rby['buyer'];

                // GROUP A
                $sqlga = sqlsrv_query($cond, "
                  SELECT COUNT(a.nokk) AS jml_kk_a
                  FROM db_qc.tbl_lap_inspeksi a
                  WHERE a.proses NOT IN ('Oven','Fin 1X')
                    AND a.dept = 'QCF'
                    AND CONVERT(datetime,
                        CONVERT(varchar(10), a.tgl_update, 120) + ' ' + LEFT(CONVERT(varchar(8), a.jam_update, 108), 5),
                        120
                    ) BETWEEN CONVERT(datetime, ?, 120) AND CONVERT(datetime, ?, 120)
                    AND a.[grouping] = 'A'
                    AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = ?
                ", [$start_date, $stop_date, $buyerVal]);
                $rga = sqlsrv_fetch_array($sqlga, SQLSRV_FETCH_ASSOC);

                // GROUP B
                $sqlgb = sqlsrv_query($cond, "
                  SELECT COUNT(a.nokk) AS jml_kk_b
                  FROM db_qc.tbl_lap_inspeksi a
                  WHERE a.proses NOT IN ('Oven','Fin 1X')
                    AND a.dept = 'QCF'
                    AND CONVERT(datetime,
                        CONVERT(varchar(10), a.tgl_update, 120) + ' ' + LEFT(CONVERT(varchar(8), a.jam_update, 108), 5),
                        120
                    ) BETWEEN CONVERT(datetime, ?, 120) AND CONVERT(datetime, ?, 120)
                    AND a.[grouping] = 'B'
                    AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = ?
                ", [$start_date, $stop_date, $buyerVal]);
                $rgb = sqlsrv_fetch_array($sqlgb, SQLSRV_FETCH_ASSOC);

                // GROUP C
                $sqlgc = sqlsrv_query($cond, "
                  SELECT COUNT(a.nokk) AS jml_kk_c
                  FROM db_qc.tbl_lap_inspeksi a
                  WHERE a.proses NOT IN ('Oven','Fin 1X')
                    AND a.dept = 'QCF'
                    AND CONVERT(datetime,
                        CONVERT(varchar(10), a.tgl_update, 120) + ' ' + LEFT(CONVERT(varchar(8), a.jam_update, 108), 5),
                        120
                    ) BETWEEN CONVERT(datetime, ?, 120) AND CONVERT(datetime, ?, 120)
                    AND a.[grouping] = 'C'
                    AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = ?
                ", [$start_date, $stop_date, $buyerVal]);
                $rgc = sqlsrv_fetch_array($sqlgc, SQLSRV_FETCH_ASSOC);

                // GROUP D
                $sqlgd = sqlsrv_query($cond, "
                  SELECT COUNT(a.nokk) AS jml_kk_d
                  FROM db_qc.tbl_lap_inspeksi a
                  WHERE a.proses NOT IN ('Oven','Fin 1X')
                    AND a.dept = 'QCF'
                    AND CONVERT(datetime,
                        CONVERT(varchar(10), a.tgl_update, 120) + ' ' + LEFT(CONVERT(varchar(8), a.jam_update, 108), 5),
                        120
                    ) BETWEEN CONVERT(datetime, ?, 120) AND CONVERT(datetime, ?, 120)
                    AND a.[grouping] = 'D'
                    AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = ?
                ", [$start_date, $stop_date, $buyerVal]);
                $rgd = sqlsrv_fetch_array($sqlgd, SQLSRV_FETCH_ASSOC);

                // NULL / kosong
                $sqlgn = sqlsrv_query($cond, "
                  SELECT COUNT(a.nokk) AS jml_kk_null
                  FROM db_qc.tbl_lap_inspeksi a
                  WHERE a.proses NOT IN ('Oven','Fin 1X')
                    AND a.dept = 'QCF'
                    AND CONVERT(datetime,
                        CONVERT(varchar(10), a.tgl_update, 120) + ' ' + LEFT(CONVERT(varchar(8), a.jam_update, 108), 5),
                        120
                    ) BETWEEN CONVERT(datetime, ?, 120) AND CONVERT(datetime, ?, 120)
                    AND (a.[grouping] = '' OR a.[grouping] IS NULL)
                    AND RIGHT(a.pelanggan, CHARINDEX('/', REVERSE(a.pelanggan) + '/') - 1) = ?
                ", [$start_date, $stop_date, $buyerVal]);
                $rgn = sqlsrv_fetch_array($sqlgn, SQLSRV_FETCH_ASSOC);
              ?>
                <tr valign="top">
                  <td align="center"><?php echo $no; ?></td>
                  <td align="center"><?php echo $rby['buyer']; ?></td>
                  <td align="center"><?php echo (int)($rga['jml_kk_a'] ?? 0); ?></td>
                  <td align="center"><?php echo (int)($rgb['jml_kk_b'] ?? 0); ?></td>
                  <td align="center"><?php echo (int)($rgc['jml_kk_c'] ?? 0); ?></td>
                  <td align="center"><?php echo (int)($rgd['jml_kk_d'] ?? 0); ?></td>
                  <td align="center"><?php echo (int)($rgn['jml_kk_null'] ?? 0); ?></td>
                  <td align="center"><?php echo number_format(($rby['jml_kk'] / $rball['jml_kk_all']) * 100, 2) . " %"; ?></td>
                </tr>
              <?php
                $no++;
              }
              ?>

            </tbody>
          </table>
        </div>
        <div class="box-footer">
          <a href="pages/cetak/excel_top5_buyer_lapfin.php?awal=<?php echo $_POST['awal']; ?>&akhir=<?php echo $_POST['akhir']; ?>"
            class="btn btn-success <?php if ($_POST['awal'] == "") {
                                      echo "disabled";
                                    } ?>" target="_blank"><i class="fa fa-file-excel-o"></i></a>
        </div>
      </div>
    </div>

    <!-- TOP 5 Berdasarkan No Warna -->
    <div class="col-xs-5">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"> TOP 5 Berdasarkan No Warna</h3>
          <?php if ($Awal != "") { ?><br><b>Periode:
            <?php echo tanggal_indo($Awal) . " - " . tanggal_indo($Akhir);
          } ?>
            </b>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered table-striped" style="width: 100%;">
            <thead class="bg-blue">
              <tr>
                <th width="5%">
                  <div align="center">No</div>
                </th>
                <th width="10%">
                  <div align="center">No Warna</div>
                </th>
                <th width="10%">
                  <div align="center">Warna</div>
                </th>
                <th width="5%">
                  <div align="center">A</div>
                </th>
                <th width="5%">
                  <div align="center">B</div>
                </th>
                <th width="5%">
                  <div align="center">C</div>
                </th>
                <th width="5%">
                  <div align="center">D</div>
                </th>
                <th width="5%">
                  <div align="center">NULL</div>
                </th>
                <th width="5%">
                  <div align="center">%</div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;

              $sqlw = sqlsrv_query($cond, "SELECT TOP 5
                  a.no_warna,
                  a.warna,
                  COUNT(a.nokk) AS jml_kk
                FROM db_qc.tbl_lap_inspeksi a
                WHERE (a.proses != 'Oven' OR a.proses != 'Fin 1X')
                  AND a.dept = 'QCF'
                  AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
                GROUP BY a.no_warna, a.warna
                ORDER BY COUNT(a.nokk) DESC
              ");

              while ($rw = sqlsrv_fetch_array($sqlw)) {

                //GROUP A
                $sqlwa = sqlsrv_query($cond, "SELECT
                    a.no_warna,
                    a.warna,
                    COUNT(a.nokk) AS jml_kk_a
                  FROM db_qc.tbl_lap_inspeksi a
                  WHERE (a.proses != 'Oven' OR a.proses != 'Fin 1X')
                    AND a.dept = 'QCF'
                    AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
                    AND a.[grouping] = 'A'
                    AND a.no_warna = '$rw[no_warna]'
                    AND a.warna    = '$rw[warna]'
                  GROUP BY
                    a.no_warna,
                    a.warna
                ");
                $rwa = sqlsrv_fetch_array($sqlwa);

                //GROUP B
                $sqlwb = sqlsrv_query($cond, "SELECT
                    a.no_warna,
                    a.warna,
                    COUNT(a.nokk) AS jml_kk_b
                  FROM db_qc.tbl_lap_inspeksi a
                  WHERE (a.proses != 'Oven' OR a.proses != 'Fin 1X')
                    AND a.dept = 'QCF'
                    AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
                    AND a.[grouping] = 'B'
                    AND a.no_warna = '$rw[no_warna]'
                    AND a.warna    = '$rw[warna]'
                  GROUP BY
                    a.no_warna,
                    a.warna
                ");
                $rwb = sqlsrv_fetch_array($sqlwb);

                //GROUP C
                $sqlwc = sqlsrv_query($cond, "SELECT
                    a.no_warna,
                    a.warna,
                    COUNT(a.nokk) AS jml_kk_c
                  FROM db_qc.tbl_lap_inspeksi a
                  WHERE (a.proses != 'Oven' OR a.proses != 'Fin 1X')
                    AND a.dept = 'QCF'
                    AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
                    AND a.[grouping] = 'C'
                    AND a.no_warna = '$rw[no_warna]'
                    AND a.warna    = '$rw[warna]'
                  GROUP BY
                    a.no_warna,
                    a.warna
                ");
                $rwc = sqlsrv_fetch_array($sqlwc);

                //GROUP D
                $sqlwd = sqlsrv_query($cond, "SELECT
                    a.no_warna,
                    a.warna,
                    COUNT(a.nokk) AS jml_kk_d
                  FROM db_qc.tbl_lap_inspeksi a
                  WHERE (a.proses != 'Oven' OR a.proses != 'Fin 1X')
                    AND a.dept = 'QCF'
                    AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
                    AND a.[grouping] = 'D'
                    AND a.no_warna = '$rw[no_warna]'
                    AND a.warna    = '$rw[warna]'
                  GROUP BY
                    a.no_warna,
                    a.warna
                ");
                $rwd = sqlsrv_fetch_array($sqlwd);

                //NULL
                $sqlwn = sqlsrv_query($cond, "SELECT
                    a.no_warna,
                    a.warna,
                    COUNT(a.nokk) AS jml_kk_null
                  FROM db_qc.tbl_lap_inspeksi a
                  WHERE (a.proses != 'Oven' OR a.proses != 'Fin 1X')
                    AND a.dept = 'QCF'
                    AND CAST(a.tgl_update AS date) BETWEEN '$Awal' AND '$Akhir'
                    AND (a.[grouping] = '' OR a.[grouping] IS NULL)
                    AND a.no_warna = '$rw[no_warna]'
                    AND a.warna    = '$rw[warna]'
                  GROUP BY
                    a.no_warna,
                    a.warna
                ");
                $rwn = sqlsrv_fetch_array($sqlwn);
              ?>

                <tr valign="top">
                  <td align="center">
                    <?php echo $no; ?>
                  </td>
                  <td align="center">
                    <?php echo $rw['no_warna']; ?>
                  </td>
                  <td align="center">
                    <?php echo $rw['warna']; ?>
                  </td>
                  <td align="center">
                    <?php echo $rwa['jml_kk_a']; ?>
                  </td>
                  <td align="center">
                    <?php echo $rwb['jml_kk_b']; ?>
                  </td>
                  <td align="center">
                    <?php echo $rwc['jml_kk_c']; ?>
                  </td>
                  <td align="center">
                    <?php echo $rwd['jml_kk_d']; ?>
                  </td>
                  <td align="center">
                    <?php echo $rwn['jml_kk_null']; ?>
                  </td>
                  <td align="center">
                    <?php echo number_format(($rw['jml_kk'] / $rball['jml_kk_all']) * 100, 2) . " %"; ?>
                  </td>
                </tr>
              <?php
                $no++;
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="box-footer">
          <a href="pages/cetak/excel_top5_nowarna_lapfin.php?awal=<?php echo $_POST['awal']; ?>&akhir=<?php echo $_POST['akhir']; ?>"
            class="btn btn-success <?php if ($_POST['awal'] == "") {
                                      echo "disabled";
                                    } ?>" target="_blank"><i class="fa fa-file-excel-o"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Data Cocok Warna Finishing</h3><br>
          <?php if ($_GET['awal'] != "") { ?><b>Periode:
              <?php echo $_GET['awal'] . " to " . $_GET['akhir']; ?>
            </b>
          <?php } else if ($_POST['awal'] != "") { ?><b>Periode:
              <?php echo $_POST['awal'] . " to " . $_POST['akhir']; ?>
            </b>
          <?php } ?><br>
          <?php if ($_GET['shift'] != "") { ?><b>Shift:
              <?php echo $_GET['shift']; ?>
            </b>
          <?php } else if ($_POST['gshift'] != "") { ?><b>Shift:
              <?php echo $_POST['gshift']; ?>
            </b>
          <?php } ?>
          <div class="pull-right">
            <a href="pages/cetak/lap-grouping-cocok-warna-excel.php?awal=<?php echo $_POST['awal']; ?>&akhir=<?php echo $_POST['akhir']; ?>&shift=<?php echo $_POST['gshift']; ?>"
              class="btn btn-primary <?php if ($_POST['awal'] == "") {
                                        echo "disabled";
                                      } ?>" target="_blank">Cetak Grouping</a>
            <a href="pages/cetak/cetak-reports-cocok-warna.php?awal=<?php echo $_POST['awal']; ?>&jam_awal=<?php echo $_POST['jam_awal']; ?>&akhir=<?php echo $_POST['akhir']; ?>&jam_akhir=<?php echo $_POST['jam_akhir']; ?>&shift=<?php echo $_POST['gshift']; ?>"
              class="btn btn-primary <?php if ($_POST['awal'] == "") {
                                        echo "disabled";
                                      } ?>" target="_blank">Cetak</a>
            <a href="pages/cetak/lap-cocok-warna-excel.php?awal=<?php echo $_POST['awal']; ?>&jam_awal=<?php echo $_POST['jam_awal']; ?>&akhir=<?php echo $_POST['akhir']; ?>&jam_akhir=<?php echo $_POST['jam_akhir']; ?>&shift=<?php echo $_POST['gshift']; ?>"
              class="btn btn-primary <?php if ($_POST['awal'] == "") {
                                        echo "disabled";
                                      } ?>" target="_blank">Cetak
              Excel</a>
          </div>
        </div>
        <div class="box-body">
          <table class="table table-bordered table-hover table-striped nowrap" id="example1" style="width:100%">
            <thead class="bg-blue">
              <tr>
                <th>
                  <div align="center">No</div>
                </th>
                <th>
                  <div align="center">Shift</div>
                </th>
                <th>
                  <div align="center">Aksi</div>
                </th>
                <th>
                  <div align="center">Tgl Fin</div>
                </th>
                <th>
                  <div align="center">No KK</div>
                </th>
                <th>
                  <div align="center">No Demand</div>
                </th>
                <th>
                  <div align="center">Pelanggan</div>
                </th>
                <th>
                  <div align="center">Buyer</div>
                </th>
                <th>
                  <div align="center">PO</div>
                </th>
                <th>
                  <div align="center">Order</div>
                </th>
                <th>
                  <div align="center">Item</div>
                </th>
                <th>
                  <div align="center">Jenis Kain</div>
                </th>
                <th>
                  <div align="center">Warna</div>
                </th>
                <th>
                  <div align="center">No Warna</div>
                </th>
                <th>
                  <div align="center">Lot</div>
                </th>
                <th>
                  <div align="center">Roll</div>
                </th>
                <th>
                  <div align="center">Bruto</div>
                </th>
                <th>
                  <div align="center">Status Warna</div>
                </th>
                <th>
                  <div align="center">Grouping</div>
                </th>
                <th>
                  <div align="center">Hue</div>
                </th>
                <th>
                  <div align="center">Disposisi</div>
                </th>
                <th>
                  <div align="center">Colorist Qcf</div>
                </th>
                <th>
                  <div align="center">Code Proses</div>
                </th>
                <th>
                  <div align="center">Tgl Celup</div>
                </th>
                <th>
                  <div align="center">Review</div>
                </th>
                <th>
                  <div align="center">Remark</div>
                </th>
                <th>
                  <div align="center">No KK Legacy</div>
                </th>
                <th>
                  <div align="center">Lot Legacy</div>
                </th>
                <th>
                  <div align="center">Keterangan</div>
                </th>
                <th>
                  <div align="center">Spectro</div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;

              if ($GShift != "ALL" && $GShift != "") {
                $shft = " AND [shift]='$GShift' ";
              } else {
                $shft = " ";
              }

              if ($Awal != "") {
                $Where = " AND CONVERT(datetime,
                CONVERT(varchar(10), tgl_update, 120) + ' ' + LEFT(CONVERT(varchar(8), jam_update, 108), 5),
                120
              ) BETWEEN CONVERT(datetime, '$start_date', 120) AND CONVERT(datetime, '$stop_date', 120) ";
              }

              if ($Awal != "" or $Akhir != "" or $Order or $PO) {
                $qry1 = sqlsrv_query($cond, "SELECT * FROM db_qc.tbl_lap_inspeksi WHERE dept='QCF' AND no_order LIKE '%$Order%' AND no_po LIKE '%$PO%' $shft $Where ORDER BY id ASC");
              } else {
                $qry1 = sqlsrv_query($cond, "SELECT * FROM db_qc.tbl_lap_inspeksi WHERE dept='QCF' AND no_order LIKE '$Order' AND no_po LIKE '$PO' $shft $Where ORDER BY id ASC");
              }

              while ($row1 = sqlsrv_fetch_array($qry1)) {
                $pos = strpos($row1['pelanggan'], "/");
                if ($pos > 0) {
                  $lgg1 = substr($row1['pelanggan'], 0, $pos);
                  $byr1 = substr($row1['pelanggan'], $pos + 1, 100);
                } else {
                  $lgg1 = $row1['pelanggan'];
                  $byr1 = substr($row1['pelanggan'], $pos, 100);
                }
              ?>
              
                <tr bgcolor="<?php echo $bgcolor; ?>">
                  <td align="center">
                    <?php echo $no; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['shift']; ?>
                  </td>
                  <td align="center">
                    <div class="btn-group">
                      <!--<a href="#" class="btn btn-info btn-xs cwarnafin_edit <?php if ($_SESSION['akses10'] == 'biasa' and ($_SESSION['lvl_id10'] != 'PACKING' or $_SESSION['lvl_id10'] != 'NCP')) {
                                                                                  echo "disabled";
                                                                                } ?>" id="<?php echo $row1['id']; ?>"><i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit"></i> </a>-->
                      <!--<a href="#" class="btn btn-danger btn-xs <?php if ($_SESSION['akses10'] == 'biasa' and ($_SESSION['lvl_id10'] != 'PACKING' or $_SESSION['lvl_id10'] != 'NCP')) {
                                                                      echo "disabled";
                                                                    } ?>" onclick="confirm_delete('./HapusDataCWarnaFin-<?php echo $row1['id'] ?>');"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Hapus"></i> </a>-->
                      <button id="<?php echo $row1['id']; ?>" class="btn btn-danger btn-xs delcwarnafin" <?php if ($_SESSION['akses10'] == 'biasa' and ($_SESSION['lvl_id10'] != 'PACKING' or $_SESSION['lvl_id10'] != 'NCP')) {
                                                                                                            echo "disabled";
                                                                                                          } ?>><i class="fa fa-trash" data-toggle="tooltip" data-placement="top"
                          title="Hapus"></i></button>
                    </div>
                  </td>
                  <td align="center">
                    <?php
                      $tgl = $row1['tgl_update'] ?? null;
                      if ($tgl instanceof DateTime) {
                        echo $tgl->format('Y-m-d'); // atau 'Y-m-d H:i:s' kalau mau jam juga
                      } else {
                        echo $tgl; // fallback kalau ternyata sudah string
                      }
                    ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['nokk']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['nodemand']; ?>
                  </td>
                  <td>
                    <?php echo $lgg1; ?>
                  </td>
                  <td align="center">
                    <?php echo $byr1; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['no_po']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['no_order']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['no_item']; ?>
                  </td>
                  <td>
                    <?php echo substr($row1['jenis_kain'], 0, 15) . "..."; ?>
                  </td>
                  <td align="left">
                    <?php echo substr($row1['warna'], 0, 10) . "..."; ?>
                  </td>
                  <td align="left">
                    <?php echo $row1['no_warna']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['lot']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['jml_roll']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['bruto']; ?>
                  </td>
                  <td><a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['status'] ?>" class="sts_fin"
                      href="javascipt:void(0)">
                      <?php echo $row1['status'] ?>
                    </a>
                  </td>
                  <td><a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['grouping'] ?>"
                      class="grouping_fin" href="javascipt:void(0)">
                      <?php echo $row1['grouping'] ?>
                    </a></td>
                  <td><a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['hue'] ?>" class="hue_fin"
                      href="javascipt:void(0)">
                      <?php echo $row1['hue'] ?>
                    </a></td>
                  <td align="center"><a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['disposisi'] ?>"
                      class="disposisi_fin" href="javascipt:void(0)">
                      <?php echo $row1['disposisi'] ?>
                    </a></td>
                  <td align="center"><a data-pk="<?php echo $row1['id'] ?>"
                      data-value="<?php echo $row1['colorist_qcf'] ?>" class="colorist_qcf_fin" href="javascipt:void(0)">
                      <?php echo $row1['colorist_qcf'] ?>
                    </a></td>
                  <td align="center"><a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['proses'] ?>"
                      class="code_proses" href="javascipt:void(0)">
                      <?php echo $row1['proses'] ?>
                    </a></td>
                  <td align="center">
                    <?php
                      $tgl = $row1['tgl_pengiriman'] ?? null;
                      if ($tgl instanceof DateTime) {
                        echo $tgl->format('Y-m-d');
                      } else {
                        echo $tgl;
                      }
                    ?>
                  </td>
                  <td align="center"><a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['review_qcf'] ?>"
                      class="review_qcf" href="javascipt:void(0)">
                      <?php echo $row1['review_qcf'] ?>
                    </a></td>
                  <td align="center"><a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['remark_qcf'] ?>"
                      class="remark_qcf" href="javascipt:void(0)">
                      <?php echo $row1['remark_qcf'] ?>
                    </a></td>
                  <td align="center">
                    <?php echo $row1['kk_lgcy']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['lot_lgcy']; ?>
                  </td>
                  <td align="center"><a data-pk="<?php echo $row1['id'] ?>" data-value="<?php echo $row1['catatan'] ?>"
                      class="ket_fin" href="javascipt:void(0)">
                      <?php echo $row1['catatan'] ?>
                    </a></td>
                  <td align="center"><a data-pk="<?php echo $row1['id']; ?>" data-value="<?php echo ($row1['spectro'] === null ? '' : $row1['spectro']); ?>" class="spectro_fin" href="javascript:void(0)">
                      <?php
                      echo ($row1['spectro'] === null  ? '' : ($row1['spectro'] == 1 ? '✔' : '✖'));
                      ?>
                    </a></td>
                </tr>
              <?php $no++;
              } 
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal_del" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content" style="margin-top:100px;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Are you sure to delete all data ?</h4>
        </div>

        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
          <a href="#" class="btn btn-danger" id="delete_link">Delete</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  <div id="CWarnaFinEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true"></div>
  <script type="text/javascript">
    function confirm_delete(delete_url) {
      $('#modal_del').modal('show', {
        backdrop: 'static'
      });
      document.getElementById('delete_link').setAttribute('href', delete_url);
    }
  </script>
  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
</body>

</html>