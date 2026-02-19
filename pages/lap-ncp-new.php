<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Laporan NCP</title>

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
          <h3 class="box-title"> Filter Laporan NCP </h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
          <div class="box-body">
            <div class="form-group">
              <div class="col-sm-8">
                <div class="input-group date">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input name="awal" type="text" class="form-control pull-right" id="datepicker" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off" />
                </div>
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control timepicker" name="jam_awal" placeholder="00:00" value="<?php echo $jamA; ?>" autocomplete="off">
              </div>
              <!-- /.input group -->
            </div>
            <div class="form-group">
              <div class="col-sm-8">
                <div class="input-group date">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input name="akhir" type="text" class="form-control pull-right" id="datepicker1" placeholder="Tanggal Akhir" value="<?php echo $Akhir; ?>" autocomplete="off" 1 />
                </div>
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control timepicker" name="jam_akhir" placeholder="00:00" value="<?php echo $jamAr; ?>" autocomplete="off">
              </div>
              <!-- /.input group -->
            </div>
            <div class="form-group">
              <div class="col-sm-10">
                <select class="form-control select2" name="dept" id="dept" required>
                  <option value="">Pilih</option>
                  <option value="ALL" <?php if ($Dept == "ALL") {
                                        echo "SELECTED";
                                      } ?>>ALL</option>
                  <option value="MKT" <?php if ($Dept == "MKT") {
                                        echo "SELECTED";
                                      } ?>>MKT</option>
                  <option value="FIN" <?php if ($Dept == "FIN") {
                                        echo "SELECTED";
                                      } ?>>FIN</option>
                  <option value="DYE" <?php if ($Dept == "DYE") {
                                        echo "SELECTED";
                                      } ?>>DYE</option>
                  <option value="KNT" <?php if ($Dept == "KNT") {
                                        echo "SELECTED";
                                      } ?>>KNT</option>
                  <option value="LAB" <?php if ($Dept == "LAB") {
                                        echo "SELECTED";
                                      } ?>>LAB</option>
                  <option value="PPC" <?php if ($Dept == "PPC") {
                                        echo "SELECTED";
                                      } ?>>PPC</option>
                  <option value="QCF" <?php if ($Dept == "QCF") {
                                        echo "SELECTED";
                                      } ?>>QCF</option>
                  <option value="CQA" <?php if ($Dept == "CQA") {
                                        echo "SELECTED";
                                      } ?>>CQA</option>
                  <option value="RMP" <?php if ($Dept == "RMP") {
                                        echo "SELECTED";
                                      } ?>>RMP</option>
                  <option value="KNK" <?php if ($Dept == "KNK") {
                                        echo "SELECTED";
                                      } ?>>KNK</option>
                  <option value="GKG" <?php if ($Dept == "GKG") {
                                        echo "SELECTED";
                                      } ?>>GKG</option>
                  <option value="GKJ" <?php if ($Dept == "GKJ") {
                                        echo "SELECTED";
                                      } ?>>GKJ</option>
                  <option value="GAS" <?php if ($Dept == "GAS") {
                                        echo "SELECTED";
                                      } ?>>GAS</option>
                  <option value="BRS" <?php if ($Dept == "BRS") {
                                        echo "SELECTED";
                                      } ?>>BRS</option>
                  <option value="PRT" <?php if ($Dept == "PRT") {
                                        echo "SELECTED";
                                      } ?>>PRT</option>
                  <option value="YND" <?php if ($Dept == "YND") {
                                        echo "SELECTED";
                                      } ?>>YND</option>
                  <option value="PRO" <?php if ($Dept == "PRO") {
                                        echo "SELECTED";
                                      } ?>>PRO</option>
                  <option value="TAS" <?php if ($Dept == "TAS") {
                                        echo "SELECTED";
                                      } ?>>TAS</option>
                </select>
              </div>
              <!-- /.input group -->
            </div>
            <div class="form-group">
              <div class="col-sm-10">
                <select class="form-control select2" name="kategori" id="kategori" required>
                  <option value="">Pilih</option>
                  <option value="ALL" <?php if ($Kategori == "ALL") {
                                        echo "SELECTED";
                                      } ?>>ALL</option>
                  <option value="hitung" <?php if ($Kategori == "hitung") {
                                            echo "SELECTED";
                                          } ?>>Hitung NCP</option>
                  <option value="tidakhitung" <?php if ($Kategori == "tidakhitung") {
                                                echo "SELECTED";
                                              } ?>>Tidak Hitung NCP</option>
                  <option value="gerobak" <?php if ($Kategori == "gerobak") {
                                            echo "SELECTED";
                                          } ?>>Kain diGerobak</option>
                </select>
              </div>
              <!-- /.input group -->
            </div>
            <div class="form-group">
              <div class="col-sm-10">
                <label>
                  <input type="checkbox" value="1" name="chkcancel" class="minimal-red" <?php if ($Cancel == "1") {
                                                                                          echo "checked";
                                                                                        } ?>>
                  Tampil Status Cancel
                </label>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-10">
                <label>
                  <input type="checkbox" value="1" name="chkrev" class="minimal-red" <?php if ($Rev2A == "1") {
                                                                                        echo "checked";
                                                                                      } ?>>
                  Tampil Revisi > 1
                </label>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="col-sm-2">
              <button type="submit" class="btn btn-social btn-linkedin btn-sm" name="save">Search <i class="fa fa-search"></i></button>
            </div>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>
    <div class="col-xs-5">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"> TOP 5 NCP Berdasarkan Masalah</h3>
          <?php if ($Awal != "") { ?><br><b>Periode:
            <?php echo tanggal_indo($Awal) . " - " . tanggal_indo($Akhir);
          } ?> Ket:
            <?php if ($Kategori == "ALL") {
              echo "ALL";
            } elseif ($Kategori == "hitung") {
              echo "NCP dihitung";
            } elseif ($Kategori == "tidakhitung") {
              echo "NCP tidak dihitung";
            } elseif ($Kategori == "gerobak") {
              echo "diGerobak";
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
                <th width="15%">
                  <div align="center">Masalah</div>
                </th>
                <th width="10%">
                  <div align="center">KG</div>
                </th>
                <th width="5%">
                  <div align="center">%</div>
                </th>
                <th width="10%">
                  <div align="center">Disposisi</div>
                </th>
                <th width="5%">
                  <div align="center">% Disp.</div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($Dept == "ALL") {
                $Wdept = " ";
              } else {
                $Wdept = " dept='$Dept' AND ";
              }

              if ($Kategori == "ALL") {
                $WKategori = " ";
              } else if ($Kategori == "hitung") {
                $WKategori = " ncp_hitung='ya' AND ";
              } else if ($Kategori == "tidakhitung") {
                $WKategori = " ncp_hitung='tidak' AND ";
              } else if ($Kategori == "gerobak") {
                $WKategori = " kain_gerobak='ya' AND ";
              }

              if ($Cancel != "1") {
                $sts = " AND [status] <> 'Cancel' ";
              } else {
                $sts = "  ";
              }

              if ($Rev2A == "1") {
                $WR2A = " and revisi > 1 and status='belum ok' ";
                $FR2A = " ";
                $GR2A = " ORDER BY revisi DESC ";
              } else {
                $WR2A = " ";
                $FR2A = " ";
                $GR2A = " ORDER BY id ASC ";
              }

              $totald = 0;
              $totaldll = 0;
              $totaldDis = 0;
              $totaldllDis = 0;

              $qryAll = sqlsrv_query($cond, "
                SELECT COUNT(*) AS jml_all, SUM(berat) AS berat_all
                FROM db_qc.tbl_ncp_qcf_new
                WHERE $WKategori
                  tgl_buat BETWEEN ? AND ?
                  AND (masalah_dominan IS NOT NULL AND LTRIM(RTRIM(masalah_dominan)) <> '')
                  $sts
              ", [$start_date, $stop_date]);
              $rAll = sqlsrv_fetch_array($qryAll, SQLSRV_FETCH_ASSOC);

              $qryAllDis = sqlsrv_query($cond, "
                SELECT COUNT(*) AS jml_all, SUM(berat) AS berat_all
                FROM db_qc.tbl_ncp_qcf_new
                WHERE $WKategori
                  tgl_buat BETWEEN ? AND ?
                  AND (masalah_dominan IS NOT NULL AND LTRIM(RTRIM(masalah_dominan)) <> '')
                  AND [status] = 'Disposisi'
                  $sts
              ", [$start_date, $stop_date]);
              $rAllDis = sqlsrv_fetch_array($qryAllDis, SQLSRV_FETCH_ASSOC);

              $qrydef = sqlsrv_query($cond, "
                SELECT TOP 5
                  SUM(berat) AS berat,
                  ROUND(
                    COUNT(masalah_dominan) / NULLIF((
                      SELECT COUNT(*)
                      FROM db_qc.tbl_ncp_qcf_new
                      WHERE $WKategori
                        tgl_buat BETWEEN ? AND ?
                        $sts
                        AND (masalah_dominan IS NOT NULL AND LTRIM(RTRIM(masalah_dominan)) <> '')
                    ), 0) * 100, 1
                  ) AS persen,
                  masalah_dominan
                FROM db_qc.tbl_ncp_qcf_new
                WHERE $WKategori
                  tgl_buat BETWEEN ? AND ?
                  AND (masalah_dominan IS NOT NULL AND LTRIM(RTRIM(masalah_dominan)) <> '')
                  $sts
                GROUP BY masalah_dominan
                ORDER BY SUM(berat) DESC
              ", [$start_date, $stop_date, $start_date, $stop_date]);

              $qryBDominan = sqlsrv_query($cond, "
                SELECT COUNT(*) AS jml_all, SUM(berat) AS berat_all
                FROM db_qc.tbl_ncp_qcf_new
                WHERE $WKategori
                  tgl_buat BETWEEN ? AND ?
                  AND (masalah_dominan IS NULL OR LTRIM(RTRIM(masalah_dominan)) = '')
                  $sts
              ", [$start_date, $stop_date]);
              $rBD = sqlsrv_fetch_array($qryBDominan, SQLSRV_FETCH_ASSOC);

              $qryAllDisBD = sqlsrv_query($cond, "
                SELECT COUNT(*) AS jml_all, SUM(berat) AS berat_all
                FROM db_qc.tbl_ncp_qcf_new
                WHERE $WKategori
                  tgl_buat BETWEEN ? AND ?
                  AND (masalah_dominan IS NULL OR LTRIM(RTRIM(masalah_dominan)) = '')
                  AND [status] = 'Disposisi'
                  $sts
              ", [$start_date, $stop_date]);
              $rAllDisBD = sqlsrv_fetch_array($qryAllDisBD, SQLSRV_FETCH_ASSOC);

              while ($rd = sqlsrv_fetch_array($qrydef, SQLSRV_FETCH_ASSOC)) {

                $mdVal = $rd['masalah_dominan'];

                $qrydefDis = sqlsrv_query($cond, "
                  SELECT
                    SUM(berat) AS berat,
                    ROUND(
                      COUNT(masalah_dominan) / NULLIF((
                        SELECT COUNT(*)
                        FROM db_qc.tbl_ncp_qcf_new
                        WHERE $WKategori
                          tgl_buat BETWEEN ? AND ?
                          AND [status] = 'Disposisi'
                          AND masalah_dominan = ?
                          $sts
                          AND (masalah_dominan IS NOT NULL AND LTRIM(RTRIM(masalah_dominan)) <> '')
                      ), 0) * 100, 1
                    ) AS persen,
                    masalah_dominan
                  FROM db_qc.tbl_ncp_qcf_new
                  WHERE $WKategori
                    tgl_buat BETWEEN ? AND ?
                    AND [status] = 'Disposisi'
                    AND masalah_dominan = ?
                    AND (masalah_dominan IS NOT NULL AND LTRIM(RTRIM(masalah_dominan)) <> '')
                    $sts
                  GROUP BY masalah_dominan
                ", [$start_date, $stop_date, $mdVal, $start_date, $stop_date, $mdVal]);

                $rdDis = sqlsrv_fetch_array($qrydefDis, SQLSRV_FETCH_ASSOC);
              ?>
                <tr valign="top">
                  <td align="center">
                    <?php echo $rd['masalah_dominan']; ?>
                  </td>
                  <td align="right">
                    <?php echo $rd['berat']; ?>
                  </td>
                  <td align="right">
                    <?php echo number_format(($rd['berat'] / $rAll['berat_all']) * 100, 2) . " %"; ?>
                  </td>
                  <td align="right">
                    <?php echo $rdDis['berat']; ?>
                  </td>
                  <td align="right">
                    <?php echo number_format(($rdDis['berat'] / $rAll['berat_all']) * 100, 2) . " %"; ?>
                  </td>
                </tr>
              <?php
                $totald = $totald + $rd['berat'];
                $totaldDis = $totaldDis + $rdDis['berat'];
              }
              $totaldll = $rAll['berat_all'] - $totald;
              $totaldllDis = $rAllDis['berat_all'] - $totaldDis; ?>
            </tbody>
            <tfoot>
              <tr valign="top">
                <td align="center"><strong>DLL</strong></td>
                <td align="right"><strong>
                    <?php echo number_format($totaldll, 2); ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php if ($rAll['berat_all'] > 0) {
                      echo number_format(($totaldll / $rAll['berat_all']) * 100, 2) . " %";
                    } else {
                      echo "0.00 %";
                    } ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php echo number_format($totaldllDis, 2); ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php if ($rAllDis['berat_all'] > 0) {
                      echo number_format(($totaldllDis / $rAll['berat_all']) * 100, 2) . " %";
                    } else {
                      echo "0.00 %";
                    } ?>
                  </strong></td>
              </tr>
              <tr valign="top">
                <td align="center"><strong>Bukan Masalah Dominan</strong></td>
                <td align="right"><strong>
                    <?php echo number_format($rBD['berat_all'], 2); ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php if ($rBD['berat_all'] > 0) {
                      echo number_format(($rBD['berat_all'] / $rAll['berat_all']) * 100, 2) . " %";
                    } else {
                      echo "0.00 %";
                    } ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php echo number_format($rAllDisBD['berat_all'], 2); ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php if ($rAllDisBD['berat_all'] > 0) {
                      echo number_format(($rAllDisBD['berat_all'] / $rAll['berat_all']) * 100, 2) . " %";
                    } else {
                      echo "0.00 %";
                    } ?>
                  </strong></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xs-5">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"> TOP 5 NCP Berdasarkan Dept Penyebab</h3>
          <?php if ($Awal != "") { ?><br><b>Periode:
            <?php echo tanggal_indo($Awal) . " - " . tanggal_indo($Akhir);
          } ?> Ket:
            <?php if ($Kategori == "ALL") {
              echo "ALL";
            } elseif ($Kategori == "hitung") {
              echo "NCP dihitung";
            } elseif ($Kategori == "tidakhitung") {
              echo "NCP tidak dihitung";
            } elseif ($Kategori == "gerobak") {
              echo "diGerobak";
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
                <th width="15%">
                  <div align="center">Dept</div>
                </th>
                <th width="10%">
                  <div align="center">KG</div>
                </th>
                <th width="5%">
                  <div align="center">%</div>
                </th>
                <th width="10%">
                  <div align="center">Disposisi</div>
                </th>
                <th width="5%">
                  <div align="center">% Disp.</div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              $totaldpt = 0;
              $totaldlldpt = 0;
              $totaldptDis = 0;
              $totaldlldptDis = 0;

              $qryAllDpt = sqlsrv_query(
                $cond,
                "SELECT COUNT(*) AS jml_all, SUM(berat) AS berat_all
                  FROM db_qc.tbl_ncp_qcf_new
                  WHERE tgl_buat BETWEEN ? AND ?
                    AND (dept IS NOT NULL AND LTRIM(RTRIM(dept)) <> '')
                    AND [status] <> 'Cancel'
                    AND ncp_hitung = 'ya' ",
                [$start_date, $stop_date]
              );
              $rAllDpt = sqlsrv_fetch_array($qryAllDpt, SQLSRV_FETCH_ASSOC);

              $qryAllDptDis = sqlsrv_query(
                $cond,
                "SELECT COUNT(*) AS jml_all, SUM(berat) AS berat_all
                  FROM db_qc.tbl_ncp_qcf_new
                  WHERE tgl_buat BETWEEN ? AND ?
                    AND (dept IS NOT NULL AND LTRIM(RTRIM(dept)) <> '')
                    AND [status] = 'Disposisi'
                    AND [status] <> 'Cancel'
                    AND ncp_hitung = 'ya' ",
                [$start_date, $stop_date]
              );
              $rAllDptDis = sqlsrv_fetch_array($qryAllDptDis, SQLSRV_FETCH_ASSOC);

              $qrydpt = sqlsrv_query(
                $cond,
                "SELECT TOP 5
                      SUM(berat) AS berat,
                      ROUND(
                        COUNT(dept) / NULLIF((
                          SELECT COUNT(*)
                          FROM db_qc.tbl_ncp_qcf_new
                          WHERE tgl_buat BETWEEN ? AND ?
                            AND [status] <> 'Cancel'
                            AND (dept IS NOT NULL AND LTRIM(RTRIM(dept)) <> '')
                            AND ncp_hitung = 'ya'
                        ), 0) * 100, 1
                      ) AS persen,
                      dept
                  FROM db_qc.tbl_ncp_qcf_new
                  WHERE tgl_buat BETWEEN ? AND ?
                    AND (dept IS NOT NULL AND LTRIM(RTRIM(dept)) <> '')
                    $WKategori
                    AND [status] <> 'Cancel'
                    AND ncp_hitung = 'ya'
                  GROUP BY dept
                  ORDER BY SUM(berat) DESC",
                [$start_date, $stop_date, $start_date, $stop_date]
              );
              while ($rdpt = sqlsrv_fetch_array($qrydpt, SQLSRV_FETCH_ASSOC)) {

                $deptVal = $rdpt['dept'];

                $qrydptDis = sqlsrv_query(
                  $cond,
                  "SELECT
                        SUM(berat) AS berat,
                        ROUND(
                          COUNT(dept) / NULLIF((
                            SELECT COUNT(*)
                            FROM db_qc.tbl_ncp_qcf_new
                            WHERE tgl_buat BETWEEN ? AND ?
                              AND [status] = 'Disposisi'
                              AND dept = ?
                              AND [status] <> 'Cancel'
                              AND (dept IS NOT NULL AND LTRIM(RTRIM(dept)) <> '')
                              AND ncp_hitung = 'ya'
                          ), 0) * 100, 1
                        ) AS persen,
                        dept
                    FROM db_qc.tbl_ncp_qcf_new
                    WHERE tgl_buat BETWEEN ? AND ?
                      AND [status] = 'Disposisi'
                      AND dept = ?
                      AND (dept IS NOT NULL AND LTRIM(RTRIM(dept)) <> '')
                      AND [status] <> 'Cancel'
                      AND ncp_hitung = 'ya'
                    GROUP BY dept",
                  [$start_date, $stop_date, $deptVal, $start_date, $stop_date, $deptVal]
                );

                $rdptDis = sqlsrv_fetch_array($qrydptDis, SQLSRV_FETCH_ASSOC);
              ?>
                <tr valign="top">
                  <td align="center">
                    <?php echo $rdpt['dept']; ?>
                  </td>
                  <td align="right">
                    <?php echo $rdpt['berat']; ?>
                  </td>
                  <td align="right">
                    <?php echo number_format(($rdpt['berat'] / $rAllDpt['berat_all']) * 100, 2) . " %"; ?>
                  </td>
                  <td align="right">
                    <?php echo $rdptDis['berat']; ?>
                  </td>
                  <td align="right">
                    <?php echo number_format(($rdptDis['berat'] / $rAllDpt['berat_all']) * 100, 2) . " %"; ?>
                  </td>
                </tr>
              <?php
                $totaldpt = $totaldpt + $rdpt['berat'];
                $totaldptDis = $totaldptDis + $rdptDis['berat'];
              }
              $totaldlldpt = $rAllDpt['berat_all'] - $totaldpt;
              $totaldlldptDis = $rAllDptDis['berat_all'] - $totaldptDis; ?>
            </tbody>
            <tfoot>
              <tr valign="top">
                <td align="center"><strong>DLL</strong></td>
                <td align="right"><strong>
                    <?php echo number_format($totaldlldpt, 2); ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php if ($rAllDpt['berat_all'] > 0) {
                      echo number_format(($totaldlldpt / $rAllDpt['berat_all']) * 100, 2) . " %";
                    } else {
                      echo "0";
                    } ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php echo number_format($totaldlldptDis, 2); ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php if ($rAllDptDis['berat_all'] > 0) {
                      echo number_format(($totaldlldptDis / $rAllDpt['berat_all']) * 100, 2) . " %";
                    } else {
                      echo "0.00 %";
                    } ?>
                  </strong></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php
    $qry1 = sqlsrv_query(
      $cond,
      "SELECT * $FR2A
      FROM db_qc.tbl_ncp_qcf_new
      WHERE $Wdept $WKategori
        tgl_buat BETWEEN CONVERT(datetime, ?, 120) AND CONVERT(datetime, ?, 120)
        $sts $WR2A
      $GR2A",
      [$start_date, $stop_date]
    );

    $qrySUM = sqlsrv_query(
      $cond,
      "SELECT COUNT(*) as Lot, SUM(rol) as Rol, SUM(berat) as Berat
      FROM db_qc.tbl_ncp_qcf_new
      WHERE $Wdept $WKategori
        tgl_buat BETWEEN CONVERT(datetime, ?, 120) AND CONVERT(datetime, ?, 120)
        $sts",
      [$start_date, $stop_date]
    );

    $rSUM = sqlsrv_fetch_array($qrySUM, SQLSRV_FETCH_ASSOC);
  ?>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Data NCP
            <?php echo $Dept; ?> Kategori
            <?php echo $Kategori; ?> <span class="pull-right-container">
              <small class="label pull-right bg-green blink_me">new</small>
            </span>
          </h3>
          <?php if ($Awal != "") { ?>
            <div class="pull-right">
              <a href="./index1.php?p=lappenyelesaian&awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&kategori=<?php echo $Kategori; ?>" class="btn btn-primary " target="_blank" data-toggle="tooltip" data-html="true" title="Laporan Penyelesaian"><i class="fa fa-file"></i> Detail Penyelesaian</a>
              <a href="pages/cetak/cetak_harianncp_new.php?&awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&dept=<?php echo $Dept; ?>&kategori=<?php echo $Kategori; ?>&cancel=<?php echo $Cancel; ?>&chkrev=<?php echo $Rev2A; ?>" class="btn btn-danger " target="_blank" data-toggle="tooltip" data-html="true" title="Laporan NCP"><i class="fa fa-print"></i> Cetak</a>
              <a href="pages/cetak/cetak_harianncpwrn_new.php?&awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&dept=<?php echo $Dept; ?>&kategori=<?php echo $Kategori; ?>&cancel=<?php echo $Cancel; ?>&chkrev=<?php echo $Rev2A; ?>" class="btn btn-danger " target="_blank" data-toggle="tooltip" data-html="true" title="Laporan NCP"><i class="fa fa-print"></i> Cetak Per Warna</a>
              <a href="pages/cetak/cetak_harianncp_excel_new.php?&awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&dept=<?php echo $Dept; ?>&kategori=<?php echo $Kategori; ?>&cancel=<?php echo $Cancel; ?>&chkrev=<?php echo $Rev2A; ?>" class="btn btn-danger " target="_blank" data-toggle="tooltip" data-html="true" title="Laporan NCP ke Excel"><i class="fa fa-file"></i> Cetak ke Excel</a>
              <a href="pages/cetak/cetak_rangkumanncp_new.php?&awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&dept=<?php echo $Dept; ?>&kategori=<?php echo $Kategori; ?>&cancel=<?php echo $Cancel; ?>" class="btn btn-success " target="_blank" data-toggle="tooltip" data-html="true" title="Laporan NCP Rakuman"><i class="fa fa-print"></i> Cetak Rangkuman</a>
            </div>
          <?php } ?>
          <?php if ($Awal != "") { ?><br><b>Periode:
              <?php echo tanggal_indo($Awal) . " - " . tanggal_indo($Akhir); ?>
            </b>
            <div style="font-size:20px; ">Total Lot: <span class="label label-info">
                <?php echo $rSUM['Lot']; ?>
              </span> || Total Rol: <span class="label label-warning">
                <?php echo number_format($rSUM['Rol']); ?>
              </span> || Total Qty : <span class="label label-danger">
                <?php echo number_format($rSUM['Berat'], "2") . " Kg"; ?>
              </span></div>

          <?php } ?>
        </div>
        <div class="box-body">
          <table class="table table-bordered table-hover table-striped nowrap" id="example3" style="width:100%">
            <thead class="bg-green">
              <tr>
                <th>
                  <div align="center">No</div>
                </th>
                <th>
                  <div align="center">Tgl</div>
                </th>
                <th>
                  <div align="center">Status</div>
                </th>

                <th align="center" class="table-list1">No Registrasi</th>
                <th align="center" class="table-list1">Production Order</th>
                <th align="center" class="table-list1">Production Demand</th>
                <th align="center" class="table-list1">Original PD Code</th>

                <th>
                  <div align="center">Langganan</div>
                </th>
                <th>
                  <div align="center">Buyer</div>
                </th>
                <th>
                  <div align="center">PO</div>
                </th>
                <th>
                  <div align="center">No NCP</div>
                </th>
                <th>
                  <div align="center">Order</div>
                </th>
                <th>
                  <div align="center">Hanger</div>
                </th>
                <th>
                  <div align="center">Jenis Kain</div>
                </th>
                <th>
                  <div align="center">Lebar &amp; Gramasi</div>
                </th>
                <th>
                  <div align="center">Lot</div>
                </th>
                <th>
                  <div align="center">Lot Legacy</div>
                </th>
                <th>
                  <div align="center">Lot Salinan</div>
                </th>
                <th>
                  <div align="center">Warna</div>
                </th>
                <th>
                  <div align="center">No Warna</div>
                </th>
                <th>
                  <div align="center">Rol</div>
                </th>
                <th>
                  <div align="center">Berat</div>
                </th>
                <th>
                  <div align="center">Dept</div>
                </th>
                <th>
                  <div align="center">Masalah</div>
                </th>
                <th>
                  <div align="center">Masalah Utama</div>
                </th>
                <th>
                  <div align="center">Proses</div>
                </th>
                <th>
                  <div align="center">Ket</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Rincian</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Penyelesaian</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Penyebab</div>
                </th>
                <th align="center" class="table-list1">Akar Masalah</th>
                <th align="center" class="table-list1">Solusi Jangka Panjang</th>
                <th align="center" class="table-list1">
                  <div align="center">Colorist DYE</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Perbaikan</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Catatan Verifikator</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Peninjau Akhir</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">NSP</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Rencana</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Aktual</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Tgl Delivery</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Nokk</div>
                </th>
                <th align="center" class="table-list1">NCP diHitung</th>
                <th align="center" class="table-list1">
                  <div align="center">Tempat Kain</div>
                </th>
                <?php if (strtoupper($_SESSION['usrid']) == "ADM-FIN") { ?>
                  <th align="center" class="table-list1">
                    <div align="center"> FIN</div>
                  </th>
                  <th align="center" class="table-list1">
                    <div align="center"> Recommendasi</div>
                  </th>
                  <th align="center" class="table-list1">
                    <div align="center"> Penyebab</div>
                  </th>
                <?php } ?>
                <th align="center" class="table-list1">
                  <div align="center">Shift FIN Penyebab</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Mesin FINPenyebab</div>
                </th>
                <th align="center" class="table-list1">Perbaikan</th>
                <th align="center" class="table-list1">
                  <div align="center">Mesin FIN Perbaikan</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Jml Perbaikan FIN</div>
                </th>
                <th align="center" class="table-list1">
                  <div align="center">Kategori FIN</div>
                </th>
                <th align="center" class="table-list1">Mesin Dye</th>
                <th align="center" class="table-list1">Ke-</th>
                <th align="center" class="table-list1">ACC Perbaikan</th>
                <th align="center" class="table-list1">Status Warna</th>
                <th align="center" class="table-list1">Disposisi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              while ($row1 = sqlsrv_fetch_array($qry1, SQLSRV_FETCH_ASSOC)) {
                if ($row1['nokk_salinan'] != "") {
                  $nokk1 = $row1['nokk_salinan'];
                } else {
                  $nokk1 = $row1['nokk'];
                }
                $qryckw = sqlsrv_query($cond, "SELECT * FROM db_qc.tbl_cocok_warna_dye WHERE dept='QCF' AND nodemand='$row1[nodemand]' ORDER BY id DESC");
                $rowckw = sqlsrv_fetch_array($qryckw, SQLSRV_FETCH_ASSOC);
                                      $sqlDB2 = "SELECT
                                p.DESCRIPTION
                              FROM
                                PRODUCTIONDEMAND p
                              WHERE
                                p.CODE = '$row1[nodemand]'";
                $stmt = db2_exec($conn2, $sqlDB2, array('cursor' => DB2_SCROLLABLE));
                $rowdb2 = db2_fetch_assoc($stmt);
              ?>
                <tr bgcolor="<?php echo $bgcolor; ?>">
                  <td height="39" align="center">
                    <?php echo $no; ?>
                  </td>
                  <td align="center">
                        <?php
                            $value = $row1['tgl_buat'];

                            if ($value instanceof DateTime) {
                                echo $value->format('Y-m-d H:i:s');
                            } else {
                                echo $value;
                            }
                        ?><br>
                    <div class="btn-group"><a href="pages/cetak/cetak_ncp_now.php?id=<?php echo $row1['id']; ?>" class="btn btn-xs btn-danger" target="_blank"><i class="fa fa-print"></i></a><a href="pages/cetak/cetak_ncp_now_pdf.php?id=<?php echo $row1['id']; ?>" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-file-pdf-o"></i></a></div>
                  </td>
                  <td>
                    <a href="#" class="btn sts_new_edit <?php if ($_SESSION['dept'] == "QC" or strtoupper($_SESSION['usrid']) == "TENNY" or strtoupper($_SESSION['usrid']) == "AISYAH" or strtoupper($_SESSION['usrid']) == "CRISTIN") {
                                                          echo "enabled";
                                                        } else {
                                                          echo "disabled";
                                                        } ?>" id="<?php echo $row1['id']; ?>"><span class="label <?php if ($row1['status'] == "OK") {
                                                                                                                    echo "label-success";
                                                                                                                  } else if ($row1['status'] == "Cancel") {
                                                                                                                    echo "label-danger";
                                                                                                                  } else {
                                                                                                                    echo "label-warning";
                                                                                                                  } ?> ">
                        <?php echo $row1['status']; ?>
                      </span></a>
                  </td>
                  <td>
                    <?php echo $row1['reg_no']; ?>
                  </td>
                  <td>
                    <?php echo $row1['prod_order']; ?>
                  </td>
                  <td>
                    <?php echo $row1['nodemand']; ?>
                  </td>
                  <td>
                    <?php
                    $sql_ori_pd_code = db2_exec($conn2, "SELECT p.CODE, SUBSTRING(a.VALUESTRING, 5) AS VALUESTRING 
                                                            FROM
                                                              PRODUCTIONDEMAND p
                                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'OriginalPDCode'
                                                            WHERE p.CODE = '$row1[nodemand]'");
                    $dt_ori_pd_code = db2_fetch_assoc($sql_ori_pd_code);
                    echo $dt_ori_pd_code['VALUESTRING'];
                    ?>
                  </td>
                  <td>
                    <?php echo $row1['langganan']; ?>
                  </td>
                  <td>
                    <?php echo $row1['buyer']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['po']; ?>
                  </td>
                  <td align="center"><a href="PenyelesaianNew-<?php echo $row1['id']; ?>" class="btn <?php if (strtoupper($_SESSION['usrid']) != "ARIF") {
                                                                                                        echo "disabled";
                                                                                                      } ?>"><span class="label label-danger">
                        <?php echo $row1['no_ncp_gabungan']; ?>
                      </span></a></td>
                  <td align="center">
                    <?php echo $row1['no_order']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['no_hanger']; ?>
                  </td>
                  <td>
                    <?php echo $row1['jenis_kain']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['lebar'] . "x" . $row1['gramasi']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['lot']; ?>
                  </td>
                  <td align="center">
                    <?php echo trim($rowdb2['DESCRIPTION']); ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['lot_salinan']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['warna']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['no_warna']; ?>
                  </td>
                  <td align="right">
                    <?php echo $row1['rol']; ?>
                  </td>
                  <td align="right">
                    <?php echo $row1['berat']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row1['dept']; ?>
                  </td>
                  <td>
                    <?php echo $row1['masalah']; ?>
                  </td>
                  <td>
                    <?php echo $row1['masalah_dominan']; ?>
                  </td>
                  <td>
                    <?php echo $row1['m_proses']; ?>
                  </td>
                  <td>
                    <?php echo $row1['ket']; ?>
                  </td>
                  <td>
                    <?php echo $row1['penyelesaian']; ?>
                  </td>
                  <td>
                    <?php echo $row1['rincian']; ?>
                  </td>
                  <td>
                    <?php echo $row1['penyebab']; ?>
                  </td>
                  <td>
                    <?php echo $row1['akar_masalah']; ?>
                  </td>
                  <td>
                    <?php echo $row1['solusi_panjang']; ?>
                  </td>
                  <td>
                    <?php echo $rowckw['colorist_dye']; ?>
                  </td>
                  <td>
                    <?php echo $row1['perbaikan']; ?>
                  </td>
                  <td>
                    <?php echo $row1['catat_verify']; ?>
                  </td>
                  <td>
                    <?php echo $row1['peninjau_akhir']; ?>
                  </td>
                  <td>
                    <?php echo $row1['nsp']; ?>
                  </td>
                  <td align="center">
                    <?php if ($row1['tgl_rencana'] != "") {
                            $value1 = $row1['tgl_rencana'];

                            if ($value1 instanceof DateTime) {
                                echo $value1->format('d/m/Y');
                            } else {
                                echo $value1;
                            }
                    } ?>
                  </td>
                  <td align="center">
                    <?php if ($row1['tgl_selesai'] != "") {
                            $value2 = $row1['tgl_selesai'];

                            if ($value2 instanceof DateTime) {
                                echo $value2->format('d/m/Y');
                            } else {
                                echo $value2;
                            }
                    } ?>
                  </td>
                  <td align="center">
                    <?php if ($row1['tgl_delivery'] != "") {
                            $value3 = $row1['tgl_delivery'];

                            if ($value3 instanceof DateTime) {
                                echo $value3->format('d/m/Y');
                            } else {
                                echo $value3;
                            }
                    } ?>
                  </td>
                  <td align="center">'
                    <?php if ($row1['nokk_salinan'] != "") {
                      echo $row1['nokk_salinan'];
                    } else {
                      echo $row1['nokk'];
                    } ?>
                  </td>
                  <td>
                    <?php echo $row1['ncp_hitung']; ?>
                  </td>
                  <td>
                    <?php echo $row1['tempat']; ?>
                  </td>
                  <?php if (strtoupper($_SESSION['usrid']) == "ADM-FIN") { ?>
                    <td>
                      <a href="#" class="btn fin_data_edit <?php if ($_SESSION['dept'] == "FIN" or strtoupper($_SESSION['usrid']) == "ADM-FIN") {
                                                              echo "enabled";
                                                            } else {
                                                              echo "disabled";
                                                            } ?>" id="<?php echo $row1['id']; ?>">
                        <span class="label label-info"> <i class="fa fa-edit"></i> </span>
                      </a>
                    </td>
                    <td>
                      <?php echo $row1['rekomendasi']; ?>
                    </td>
                    <td>
                      <?php echo $row1['penyebab']; ?>
                    </td>
                  <?php } ?>
                  <td>
                    <?php echo $row1['shift']; ?>
                  </td>
                  <td>
                    <?php echo $row1['mesin']; ?>
                  </td>
                  <td>
                    <?php echo $row1['perbaikan']; ?>
                  </td>
                  <td>
                    <?php echo $row1['mesin_perbaikan']; ?>
                  </td>
                  <td>
                    <?php echo $row1['jml_perbaikan']; ?>
                  </td>
                  <td>
                    <?php echo $row1['kategori']; ?>
                  </td>
                  <td>
                    <?php echo $rowckw['no_mesin']; ?>
                  </td>
                  <td>
                    <?php echo $row1['data_ke']; ?>
                  </td>
                  <td>
                    <?php echo $row1['nama_colorist']; ?>
                  </td>
                  <td>
                    <?php echo $row1['status_warna']; ?>
                  </td>
                  <td>
                    <?php echo $row1['disposisi']; ?>
                  </td>
                </tr>
              <?php $no++;
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div id="StsNewEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
  <div id="DataFinEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
</body>

</html>