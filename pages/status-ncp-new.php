<?PHP
ini_set("error_reporting", 1);
set_time_limit(0);
session_start();
include "koneksi.php";

?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Status NCP</title>
</head>

<body>
  <?php
  $Awal = isset($_POST['awal']) ? $_POST['awal'] : '';
  $Akhir = isset($_POST['akhir']) ? $_POST['akhir'] : '';
  $GShift = isset($_POST['gshift']) ? $_POST['gshift'] : '';
  $Dept = isset($_POST['dept']) ? $_POST['dept'] : '';
  $NCP = isset($_POST['no_ncp']) ? $_POST['no_ncp'] : '';
  $NoItem = isset($_POST['no_item']) ? $_POST['no_item'] : '';
	
  $FilterByStatus = isset($_POST['filter_by_status']) ? $_POST['filter_by_status'] : '';

  $hitung = isset($_POST['hitung']) ? $_POST['hitung'] : '';
  $posisi_terakhir = isset($_POST['posisi_terakhir']) ? $_POST['posisi_terakhir'] : '';

  if ($_POST['gshift'] == "ALL") {
    $shft = " ";
  } else {
    $shft = " AND b.g_shift = '$GShift' ";
  }

  function getStatusByDemand($demand)
  {
    global $conn2;

    $sql = "SELECT DISTINCT
                PRODUCTIONORDERCODE AS NO_KK,
                DEAMAND AS DEMAND,
                PROGRESSSTATUS_DEMAND,
                TRIM(PROGRESSSTATUS) AS PROGRESSSTATUS
            FROM
                ITXVIEWKK
            WHERE
                NOT ITEMTYPEAFICODE = 'KGF'
                AND DEAMAND = '$demand'";

    $stmt = db2_exec($conn2, $sql);
    $row = db2_fetch_assoc($stmt);
    return $row;
  }

  function getCloseByKK($kk)
  {
    global $conn2;

    $sql = "SELECT 
                p.GROUPSTEPNUMBER AS GROUPSTEPNUMBER,
                TRIM(p.PROGRESSSTATUS) AS PROGRESSSTATUS
            FROM 
                PRODUCTIONDEMANDSTEP p
            WHERE p.PRODUCTIONORDERCODE = '$kk'
                AND (p.PROGRESSSTATUS = '3' OR p.PROGRESSSTATUS = '2')
            ORDER BY p.GROUPSTEPNUMBER
            DESC LIMIT 1";

    $stmt = db2_exec($conn2, $sql);
    $row = db2_fetch_assoc($stmt);
    return $row;
  }

  function totalStepAndTotalClose($kk)
  {
    global $conn2;

    $sql = "SELECT TOTAL.TOTALSTEP, CLOSE.TOTALCLOSE
    FROM
    (SELECT COUNT(*) AS TOTALSTEP FROM PRODUCTIONDEMANDSTEP WHERE PRODUCTIONORDERCODE = '$kk') TOTAL,
    (SELECT COUNT(*) AS TOTALCLOSE FROM PRODUCTIONDEMANDSTEP WHERE PRODUCTIONORDERCODE = '$kk' AND PROGRESSSTATUS = 3) CLOSE";

    $stmt = db2_exec($conn2, $sql);
    $row = db2_fetch_assoc($stmt);

    return $row;
  }

  function cnpClose($kk)
  {
    global $conn2;

    $sql = "SELECT 
                TRIM(OPERATIONCODE) AS OPERATIONCODE,
                PROGRESSSTATUS
                -- ,CASE
                --     WHEN PROGRESSSTATUS = 0 THEN 'Entered'
                --     WHEN PROGRESSSTATUS = 1 THEN 'Planned'
                --     WHEN PROGRESSSTATUS = 2 THEN 'Progress'
                --     WHEN PROGRESSSTATUS = 3 THEN 'Closed'
                -- END AS STATUS_OPERATION
            FROM 
                PRODUCTIONDEMANDSTEP v
            WHERE 
                PRODUCTIONORDERCODE = '$kk' AND PROGRESSSTATUS = 3 
            ORDER BY 
                GROUPSTEPNUMBER DESC LIMIT 1";

    $stmt = db2_exec($conn2, $sql);
    $row = db2_fetch_assoc($stmt);

    return $row;
  }

  function notCnpClose($kk, $groupstep_option)
  {
    global $conn2;

    $sql = "SELECT 
                PROGRESSSTATUS
                -- ,CASE
                --     WHEN PROGRESSSTATUS = 0 THEN 'Entered'
                --     WHEN PROGRESSSTATUS = 1 THEN 'Planned'
                --     WHEN PROGRESSSTATUS = 2 THEN 'Progress'
                --     WHEN PROGRESSSTATUS = 3 THEN 'Closed'
                -- END AS STATUS_OPERATION
            FROM 
                PRODUCTIONDEMANDSTEP
            WHERE 
                PRODUCTIONORDERCODE = '$kk' AND 
                GROUPSTEPNUMBER $groupstep_option
            ORDER BY 
                GROUPSTEPNUMBER ASC LIMIT 1";

    $stmt = db2_exec($conn2, $sql);
    $row = db2_fetch_assoc($stmt);

    return $row;
  }

  function statusTerakhir($kk, $groupstep_option)
  {
    global $conn2;

    $sql = "SELECT
                p.PROGRESSSTATUS
                -- ,CASE
                --     WHEN p.PROGRESSSTATUS = 0 THEN 'Entered'
                --     WHEN p.PROGRESSSTATUS = 1 THEN 'Planned'
                --     WHEN p.PROGRESSSTATUS = 2 THEN 'Progress'
                --     WHEN p.PROGRESSSTATUS = 3 THEN 'Closed'
                -- END AS STATUS_OPERATION
            FROM 
                PRODUCTIONDEMANDSTEP p                     
            WHERE p.PRODUCTIONORDERCODE = '$kk'
                AND (p.PROGRESSSTATUS = '0' OR p.PROGRESSSTATUS = '1' OR p.PROGRESSSTATUS ='2') 
                AND p.GROUPSTEPNUMBER $groupstep_option
            ORDER BY p.GROUPSTEPNUMBER ASC LIMIT 1";

    $stmt = db2_exec($conn2, $sql);
    $row = db2_fetch_assoc($stmt);
    return $row;
  }

  function getStatusOperationByDemand($demand)
  {
    global $conn2;
    // $demand = "00179943";
    $status = getStatusByDemand($demand);

    if ($status['PROGRESSSTATUS_DEMAND'] == 6) {
      $get = 'KK Oke';
    } else {
      // 1. Deteksi Production Order Closed Atau Belum
      if ($status['PROGRESSSTATUS'] == 6) {
        $get = 'KK Oke';
      } else {

        // mendeteksi statusnya close
        $status_close = getCloseByKK($status['NO_KK']);

        $groupstepnumber = !empty($status_close['GROUPSTEPNUMBER']) ? $status_close['GROUPSTEPNUMBER'] : 0;

        $cnpClose = cnpClose($status['NO_KK']);

        if (@$cnpClose['PROGRESSSTATUS'] == 3) { // 3 is Closed From Demands Steps 
  
          if ($cnpClose['OPERATIONCODE'] == 'PPC4') {
            if ($status['PROGRESSSTATUS'] == 6) {
              $get = 'KK Oke';
            } else {
              $get = 'KK Oke';
            }
          } else {
            // oke5
  
            if ($status_close['PROGRESSSTATUS'] == 2) {
              $groupstep_option = "= '$groupstepnumber'";
            } else { //kalau status terakhirnya bukan PPC dan status terakhirnya CLOSED
              $step = totalStepAndTotalClose($status['NO_KK']);
              $groupstep_option = ($step['TOTALSTEP'] == $step['TOTALCLOSE']) ? "= '$groupstepnumber'" : "> '$groupstepnumber'";
            }

            $notCnpClose = notCnpClose($status['NO_KK'], $groupstep_option);

            if ($notCnpClose) {
              if ($notCnpClose['PROGRESSSTATUS'] == 3) {
                $get = 'KK Oke.';
              }
              //else {
              //$get = 'Oke 5.0 => ' . $notCnpClose['STATUS_OPERATION'];
              // }
              // $get = ($cnpClose['PROGRESSSTATUS']);
            } else {
              $groupstep_option2 = "= '$groupstepnumber'";
              $cnpClose2 = notCnpClose($status['NO_KK'], $groupstep_option2);
              if ($cnpClose2['PROGRESSSTATUS'] == 3) {
                $get = 'KK Oke.';
              }
              // else {
              // $get = 'Oke 5.1 => ' . $cnpClose2['STATUS_OPERATION'];
              // }
            }

            // end oke5
          }
        } else {

          $groupstep_option3 = (@$status_close['PROGRESSSTATUS'] == 2) ? "= '$groupstepnumber'" : "> '$groupstepnumber'";

          $status_terakhir = statusTerakhir($status['NO_KK'], $groupstep_option3);
          if ($status_terakhir['PROGRESSSTATUS'] == 3) {
            $get = 'KK Oke';
          }
          // else {
          // $get = 'Oke 6.0 => ' . $status_terakhir['STATUS_OPERATION'];
          // }
        }
      }
    }

    return $get;
  }

  function getLotLegacyByDemand($demand) {
    global $conn2;

    $sql = "SELECT 
              TRIM(DESCRIPTION) AS LOTLEGACY
            FROM PRODUCTIONDEMAND
            WHERE CODE = '$demand' ";

    $stmt = db2_exec($conn2, $sql);
    $row = db2_fetch_assoc($stmt);
    return $row['LOTLEGACY'];
  }

  ?>
  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-info"></i> Informasi</h4>
    Klik No. NCP Pada Tabel -> Kolom Order untuk input data Tindakan Penyelesaian.
  </div>
  <div class="box box-info collapsed-box">
    <!-- <div class="box box-info"> -->
    <div class="box-header with-border">
      <h3 class="box-title">Filter Status NCP </h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
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
          <input name="awal" type="text" class="form-control pull-right" id="datepicker" placeholder="Awal" value="<?=$Awal?>" autocomplete="off" />
        </div>
      </div>
      <div class="col-sm-2">
        <div class="input-group date">
          <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
          <input name="akhir" type="text" class="form-control pull-right" id="datepicker2" placeholder="Akhir" value="<?=$Akhir?>" autocomplete="off" />
        </div>
      </div>
          <div class="col-sm-1">
            <select class="form-control select2" name="dept" id="dept">
              <option value="">Pilih</option>
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
              <option value="BRS" <?php if ($Dept == "BRS") {
                echo "SELECTED";
              } ?>>BRS</option>
              <option value="PRT" <?php if ($Dept == "PRT") {
                echo "SELECTED";
              } ?>>PRT</option>
              <option value="TAS" <?php if ($Dept == "TAS") {
                echo "SELECTED";
              } ?>>TAS</option>
              <option value="YND" <?php if ($Dept == "YND") {
                echo "SELECTED";
              } ?>>YND</option>
              <option value="PRO" <?php if ($Dept == "PRO") {
                echo "SELECTED";
              } ?>>PRO</option>
              <option value="GAS" <?php if ($Dept == "GAS") {
                echo "SELECTED";
              } ?>>GAS</option>
            </select>
          </div>
          <div class="col-sm-1">
            <input name="no_ncp" type="text" class="form-control" id="no_ncp" placeholder="No NCP"
              value="<?php echo $NCP; ?>" />
          </div>
		  <div class="col-sm-1">
            <input name="no_item" type="text" class="form-control" id="no_item" placeholder="No Item"
              value="<?php echo $NoItem; ?>" />
          </div>	
          <div class="col-sm-4 bg-dark">
            <?php
            $FilterByStatusLabel = [
              'Belum OK',
              'OK',
              'BS',
              'Cancel',
              'Disposisi',
            ];

            foreach ($FilterByStatusLabel as $label) {
              ?>
              <label style="margin-right: 5px">
                <input type="checkbox" name="filter_by_status[]" value="<?= $label ?>" <?php foreach ($FilterByStatus as $FilterSelected) {
                    if ($label == $FilterSelected)
                      echo 'checked';
                  } ?>>
                <?= $label ?>
              </label>
              <?php } ?>

              <label style="margin-right: 5px">
                <input type="checkbox" name="hitung" value="ya" <?php echo $hitung != "" ? 'checked' : ''; ?>>
                Hitung
              </label>

              <label style="margin-right: 5px">
                <input type="checkbox" name="posisi_terakhir" value="1" <?php echo $posisi_terakhir != "" ? 'checked' : ''; ?>>
                Posisi Terakhir
              </label>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="submit" class="btn btn-success pull-right" name="cari"><i class="fa fa-search"></i> Cari
            Data</button>
        </div>
        <!-- /.box-footer -->

      </div>
    </form>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Status NCP</h3>
          <div class="pull-right">
            <?php
              $queryArray = array('filter_by_status' => $FilterByStatus);
              $queryString = http_build_query($queryArray, '', '&', PHP_QUERY_RFC3986);
            ?>
            <a href="pages/cetak/status_ncp_excel.php?awal=<?=$Awal?>&akhir=<?=$Akhir?>&gshift=<?=$GShift?>&dept=<?=$Dept?>&ncp=<?=$NCP?>&item=<?=$NoItem?>&<?=$queryString?>&hitung=<?=$hitung?>&posisi_terakhir=<?=$posisi_terakhir?>"
              class="btn btn-primary <?php if ($Awal == "") {
                echo "disabled";
              } ?>" target="_blank">Cetak Excel</a>
          </div>
        </div>
        <div class="box-body">
          <table class="table table-bordered table-hover" id="example3" style="width:100%">
            <thead class="bg-purple">
              <tr>
                <th width="3%">
                  <div align="center">No</div>
                </th>
                <th width="4%">
                  <div align="center">Tgl Buat</div>
                </th>
                <th width="11%">
                  <div align="center">Lama Proses</div>
                </th>
                <th width="11%">
                  <div align="center">No Demand</div>
                </th>
                <th width="11%">
                  <div align="center">Registration</div>
                </th>
                <th width="11%">
                  <div align="center">Langganan</div>
                </th>
                <th width="11%">
                  <div align="center">Status 1</div>
                </th>
                <th width="11%">
                  <div align="center">Status 2</div>
                </th>
                <th width="11%">
                  <div align="center">Status 3</div>
                </th>
                <th width="6%">
                  <div align="center">Buyer</div>
                </th>
                <th width="3%">
                  <div align="center">PO</div>
                </th>
                <th width="6%">
                  <div align="center">Order</div>
                </th>
                <th width="6%">
                  <div align="center">No NCP</div>
                </th>
                <th width="6%">
                  <div align="center">Tgl Target</div>
                </th>
                <th width="6%">
                  <div align="center">No Item</div>
                </th>
                <th width="20%">
                  <div align="center">Jenis_Kain</div>
                </th>
                <th width="7%">
                  <div align="center">Lebar &amp; Gramasi</div>
                </th>
                <th width="4%">
                  <div align="center">Lot</div>
                </th>
                <th width="7%">
                  <div align="center">Lot Salinan</div>
                </th>
                <th width="7%">
                  <div align="center">Lot Legacy</div>
                </th>
                <th width="7%">
                  <div align="center">Warna</div>
                </th>
                <th width="4%">
                  <div align="center">Rol</div>
                </th>
                <th width="6%">
                  <div align="center">Berat</div>
                </th>
                <th width="5%">
                  <div align="center">Dept</div>
                </th>
                <?php if($_SESSION['user_id10'] == 'adm-cqa'):?>
                <th width="9%">
                  <div align="center">Colorist Dye</div>
                </th>
                <th width="9%">
                  <div align="center">No Mesin</div>
                </th>
                <?php endif;?>
                <th width="9%">
                  <div align="center">Masalah</div>
                </th>
                <th width="9%">
                  <div align="center">Masalah Utama</div>
                </th>
                <th width="5%">
                  <div align="center">Tempat Kain</div>
                </th>
                <th width="5%">Ket. Penyelesaian</th>
                <th width="5%">
                  <div align="center">Ket</div>
                </th>
                <?php if($_SESSION['user_id10'] == 'adm-cqa'):?>
                <th width="5%">
                  <div align="center">Ket Analisa</div>
                </th>
                <th width="5%">
                  <div align="center">Akar Masalah</div>
                </th>
                <th width="5%">
                  <div align="center">Rincian Akar Masalah</div>
                </th>
                <th width="5%">
                  <div align="center">Penyelesaian</div>
                </th>
                <th width="5%">
                  <div align="center">Rincian</div>
                </th>
                <th width="5%">
                  <div align="center">Penyebab</div>
                </th>
                <th width="5%">
                  <div align="center">Pemberi Instruksi</div>
                </th>
                <th width="5%">
                  <div align="center">Status Resep</div>
                </th>
                <th width="9%">
                  <div align="center">Analisa Resep</div>
                </th>
                <?php endif;?>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $where = "";
			  $whereT1 = "";

			  if ($_SESSION['dept10'] == "QCF" || $_SESSION['dept10'] == "MNF" || strtolower($_SESSION['nama10']) == "eto") {
					$where = "";
					$whereT1 = "";
			  } else {
					$where = " AND dept='" . $_SESSION['dept10'] . "' ";
					$whereT1 = " AND t1.dept='" . $_SESSION['dept10'] . "' ";
			  }
              if ($Dept != "") {
                $where1 = " AND dept='$Dept' ";
                $where1T1 = " AND t1.dept='$Dept' ";
              }
              if ($NCP != "") {
                $where2 = " AND no_ncp='$NCP' ";
                $where2T1 = " AND t1.no_ncp='$NCP' ";
              }
			  if ($NoItem != "") {
                $where3 = " AND no_item='$NoItem' ";
                $where3T1 = " AND t1.no_item='$NoItem' ";
              }

              if ($FilterByStatus != "") {
                $filterStatus = "";
                if (count($FilterByStatus) > 1) {
                  $filterStatus .= "status IN ('" . implode("', '", $FilterByStatus) . "') ";
                  $filterStatusT1 .= "t1.status IN ('" . implode("', '", $FilterByStatus) . "') ";
                } else {
                  $filterStatus .= "status = '" . $FilterByStatus[0] . "' ";
                  $filterStatusT1 .= "t1.status = '" . $FilterByStatus[0] . "' ";
                }
              } else {
                $filterStatus .= "status IN ('Belum OK', 'OK', 'BS', 'Cancel', 'Disposisi') ";
                $filterStatusT1 .= "t1.status IN ('Belum OK', 'OK', 'BS', 'Cancel', 'Disposisi') ";
              }

              if(!isset($_POST['cari'])) {
                $Today   = " CAST(tgl_buat AS date) = CAST(GETDATE() AS date) AND ";
                $TodayT1 = " CAST(t1.tgl_buat AS date) = CAST(GETDATE() AS date) AND ";
              }

              if($hitung != "") {
                $where6 = " AND ncp_hitung = 'ya' ";
                $where6T1 = " AND t1.ncp_hitung = 'ya' ";
              }

              if($Awal != "" && $Akhir != "") {
                $where7   = " and tgl_buat between '$Awal' and '$Akhir' ";
                $where7T1 = " and t1.tgl_buat between '$Awal' and '$Akhir' ";
              }

              // print_r($filterStatus);
              if($posisi_terakhir != "") {
                $qry1 = sqlsrv_query($cond, "SELECT
                    t1.*,
                    DATEDIFF(day, CAST(GETDATE() AS date), CAST(t1.tgl_rencana AS date)) AS lama,
                    DATEDIFF(day, CAST(t1.tgl_rencana AS date), CAST(GETDATE() AS date)) AS delay
                FROM
                    db_qc.tbl_ncp_qcf_now t1
                JOIN (
                    SELECT
                        no_ncp,
                        MAX(tgl_update) AS max_tgl_update
                    FROM
                        db_qc.tbl_ncp_qcf_now
                    WHERE
                        " . $Today . $filterStatus . $where . $where1 . $where2 . $where3 . $where6 . $where7 . "
                    GROUP BY
                        no_ncp
                ) t2
                    ON t1.no_ncp = t2.no_ncp AND t1.tgl_update = t2.max_tgl_update
                WHERE
                    " . $TodayT1 . $filterStatusT1 . $whereT1 . $where1T1 . $where2T1 . $where3T1 . $where6T1 . $where7T1 . "
                ORDER BY
                    t1.id ASC, t1.no_ncp ASC, t1.tgl_update DESC;");
              } else {
                $qry1 = sqlsrv_query($cond, "SELECT
                    *,
                    DATEDIFF(day, CAST(GETDATE() AS date), CAST(tgl_rencana AS date)) AS lama,
                    DATEDIFF(day, CAST(tgl_rencana AS date), CAST(GETDATE() AS date)) AS delay
                FROM
                    db_qc.tbl_ncp_qcf_now
                WHERE
                    " . $Today . $filterStatus . $where . $where1 . $where2 . $where3 . $where6 . $where7 . "
                ORDER BY
                    id ASC");
              }
              while ($row1 = sqlsrv_fetch_array($qry1, SQLSRV_FETCH_ASSOC)) {

                if (!empty($row1['nokk_salinan'])) {
                    $nokk1 = $row1['nokk_salinan'];
                } else {
                    $nokk1 = $row1['nokk'];
                }

                $sql = sqlsrv_query(
                    $cond,
                    "SELECT TOP 1 COUNT(*) OVER() AS jml, tgl_terima, id
                    FROM db_qc.tbl_qcf_ncp_tolak_new
                    WHERE id_qcf_ncp = ?
                    ORDER BY id DESC",
                    [$row1['id']]
                );
                $r1 = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);

                // tbl_user_tindaklanjut
                $sql_instruksi = sqlsrv_query(
                    $cona,
                    "SELECT nama FROM db_adm.tbl_user_tindaklanjut WHERE id = ?",
                    [$row1['pemberi_instruksi']]
                );
                $r_instruksi = sqlsrv_fetch_array($sql_instruksi, SQLSRV_FETCH_ASSOC);

                $qdye = sqlsrv_query(
                    $con,
                    "SELECT TOP 1
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
                        a.analisa_resep,
                        a.status_resep,
                        b.rol,
                        b.bruto
                    FROM db_dying.tbl_hasilcelup a
                    LEFT JOIN db_dying.tbl_montemp c ON a.id_montemp = c.id
                    LEFT JOIN db_dying.tbl_schedule b ON c.id_schedule = b.id
                    WHERE a.nodemand LIKE ?
                    ORDER BY a.id DESC",
                    ['%' . $row1['nodemand'] . '%']
                );
                $row_dye = sqlsrv_fetch_array($qdye, SQLSRV_FETCH_ASSOC);
                ?>
                <tr bgcolor="<?php echo $bgcolor; ?>">
                  <td align="center">
                    <font size="-1">
                      <?php echo $no; ?>
                    </font>
                  </td>
                  <td align="center">
                    <font size="-1">
                      <?php
                        $tglBuat = $row1['tgl_buat'] ?? null;
                        if ($tglBuat instanceof DateTime) {
                            echo $tglBuat->format('Y-m-d H:i:s');
                        } else {
                            echo htmlspecialchars((string)($tglBuat ?? ''), ENT_QUOTES, 'UTF-8');
                        }
                      ?>
                    </font><br>
                    <div class="btn-group"><a href="pages/cetak/cetak_ncp_now.php?id=<?php echo $row1['id']; ?>" class="btn btn-xs btn-danger <?php if ($_SESSION['dept10'] != "QCF") {
                         echo "disabled";
                       } ?>" target="_blank"><i class="fa fa-print"></i></a><a
                        href="pages/cetak/cetak_ncp_now_pdf.php?id=<?php echo $row1['id']; ?>" class="btn btn-xs btn-info <?php if ($_SESSION['dept10'] != "QCF") {
                             echo "disabled";
                           } ?>" target="_blank"><i class="fa fa-file-pdf-o"></i></a></div>
                  </td>
                  <td align="center">
                    <?php if ($row1['delay'] > 0) {
                      echo "<span class='label label-danger'>Delay " . $row1['delay'] . " Hari</span>";
                    } else if ($row1['delay'] <= 0 and $row1['delay'] != "") {
                      echo "<span class='label label-success'>" . $row1['lama'] . " Hari Lagi</span>";
                    } else {
                      echo "<span class='label bg-fuchsia'>NCP belum-diterima</span>";
                    } ?>
                  </td>
                  <td>
                    <a href="http://10.0.0.10/laporan/ppc_filter_steps.php?demand=<?= $row1['nodemand'] ?>"
                      class="posisi_kk" id="<?php echo $row1['nodemand']; ?>" target="_blank">
                      <?php echo $row1['nodemand']; ?>
                    </a>
                  </td>
                  <td>
                    <a href="#" class="demanadno_" id="<?php echo $row1['reg_no']; ?>">
                      <?php echo $row1['reg_no']; ?>
                    </a>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['langganan']; ?>
                    </font>
                  </td>
                  <td>
                    <a href="?p=StatusncpNew_Ubah&id=<?php echo $row1['id']; ?>" class="btn 
                      <?php
                      // if ($_SESSION['dept10'] != "QCF") {
                      //      echo "disabled";
                      //    } 
                      ?>"><span class="label <?php if ($row1['status'] == "OK") {
                        echo "label-success";
                      } else {
                        echo "label-warning";
                      } ?> ">
                          <?php echo $row1['status']; ?>
                        </span></a>
                  </td>
                  <td>
                      <?php if ($row1['tgl_rencana'] != "" and $row1['penyelesaian'] == "") {
                        echo "<span class='label label-primary'>Sudah diterima " . $row1['dept'] . "</span>";
                      } else if ($row1['tgl_rencana'] != "" and $row1['penyelesaian'] != "") {
                        echo "<span class='label label-danger'>Tunggu OK dari QCF</span>";
                      } ?>
                  </td>
                  <td>
                      <!-- inistart -->
                      <?php
                      $status_operation = getStatusOperationByDemand($row1['nodemand']);
                      ?>
                      <?php if ($status_operation != null && $status_operation != "") { ?>
                        <span class='label label-danger'>
                          <?= $status_operation ?>
                        </span>
                      <?php } ?>
                      <!-- endinistart -->
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['buyer']; ?>
                    </font>
                  </td>
                  <td align="center">
                    <font size="-1">
                      <?php echo $row1['po']; ?>
                    </font>
                  </td>
                  <td align="center">
                    <font size="-1">
                      <?php echo $row1['no_order']; ?>
                    </font><br>
                    <?php if ($r1['tgl_terima'] == "" and $r1['jml'] > 0) { ?><a href="#" class="btn terima_ncp_lama"
                        id="<?php echo $r1['id']; ?>"><span class="label label-success">NCP Lama</span></a>
                    <?php } ?>
                  </td>
                  <td>
                    <a href="?p=Penyelesaian-New&id=<?php echo $row1['id']; ?>" class="btn"><span
                      class="label label-danger">
                      <?php echo $row1['no_ncp_gabungan']; ?>
                    </span></a>
                  </td>
                  <td>
                    <font size="-1">
                      <?php
                        $tgl = $row1['tgl_rencana'] ?? null; echo ($tgl instanceof DateTime) ? $tgl->format('Y-m-d') : htmlspecialchars((string)($tgl ?? ''), ENT_QUOTES, 'UTF-8');
                      ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['no_item']; ?>
                    </font>
                  </td>
                  <td>
                    <font size="-2">
                      <?php echo $row1['jenis_kain']; ?>
                    </font>
                  </td>
                  <td align="center">
                    <font size="-1">
                      <?php echo $row1['lebar'] . "x" . $row1['gramasi']; ?>
                    </font>
                  </td>
                  <td align="center">
                    <font size="-1">
                      <?php echo $row1['lot']; ?>
                    </font>
                  </td>
                  <td align="center">
                    <font size="-1">
                      <?php echo $row1['lot_salinan']; ?>
                    </font>
                  </td>
                  <td align="center">
                    <font size="-1">
                      <?php echo getLotLegacyByDemand($row1['nodemand']); ?>
                    </font>
                  </td>
                  <td align="center">
                    <font size="-2">
                      <?php echo $row1['warna']; ?>
                    </font>
                  </td>
                  <td align="right">
                    <font size="-1">
                      <?php echo $row1['rol']; ?>
                    </font>
                  </td>
                  <td align="right">
                    <font size="-1">
                      <?php echo $row1['berat']; ?>
                    </font>
                  </td>
                  <td align="center">
                    <font size="-1">
                      <?php echo $row1['dept']; ?>
                    </font>
                  </td>
                  <?php if($_SESSION['user_id10'] == 'adm-cqa'):?>
                  <td align="center">
                    <font size="-1">
                      <?php echo $row_dye['acc_keluar']; ?>
                    </font>
                  </td>
                  <td align="center">
                    <font size="-1">
                      <?php echo $row_dye['no_mesin']; ?>
                    </font>
                  </td>
                  <?php endif;?>
                  <td>
                    <font size="-1">
                      <?php echo $row1['masalah']; ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['masalah_dominan']; ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['tempat']; ?>
                    </font>
                  </td>
                  <td><font size="-1"><?php echo $row1['ket_penyelesaian']; ?></font></td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['ket']; ?>
                    </font>
                  </td>
                <?php if($_SESSION['user_id10'] == 'adm-cqa'):?>
                  <td>
                    <font size="-1">
                      <?php echo htmlspecialchars($row1['ket_analisa']); ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['akar_penyebab']; ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['akar_masalah']; ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['rincian']; ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['penyelesaian']; ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row1['penyebab']; ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $r_instruksi['nama']; ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo $row_dye['status_resep']; ?>
                    </font>
                  </td>
                  <td>
                    <font size="-1">
                      <?php echo htmlspecialchars($row_dye['analisa_resep']); ?>
                    </font>
                  </td>
                <?php endif;?>
                </tr>
                <?php $no++;
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
    <!--Modal -->
    <div id="StsNewEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true"></div>
    <div id="SelesaiNewEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true"></div>
    <div id="NcpLamaNew" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true"></div>
    <div id="NcpLamaTerimaNEw" class="modal fade modal-3d-slit" tabindex="-1" role="dialog"
      aria-labelledby="myModalLabel" aria-hidden="true"></div>
    <script>
      $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
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

function toggleKain2(id) {
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