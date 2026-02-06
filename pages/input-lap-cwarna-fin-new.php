<script type="text/javascript">
    function tampil() {
        if (document.forms['form1']['status'].value == "BW" || document.forms['form1']['status'].value == "TBD") {
            $("#disposisi").css("display", ""); // To unhide
        } else {
            $("#disposisi").css("display", "none"); // To hide
        }
        if (document.forms['form1']['status'].value == "") {
            $("#disposisi").css("display", "none"); // To hide
        }
        if (document.forms['form1']['status'].value == "OK") {
            $("#disposisi").css("display", "none"); // To hide
        }
    }

    function proses_nodemand() {
        var nodemand = document.getElementById("nodemand").value;

        if (nodemand == 0) {
            window.location.href = '?p=Input-Lap-Cwarna-Fin-New';
        } else {
            window.location.href = '?p=Input-Lap-Cwarna-Fin-New&nodemand=' + nodemand;
        }
    }

    function proses_shift() {
        var nodemand = document.getElementById("nodemand").value;
        var shift = document.getElementById("shift").value;

        if (nodemand == "") {
            swal({
                title: 'Nomor Demand tidak boleh kosong',
                text: 'Klik Ok untuk input data kembali',
                type: 'error'
            });
        } else if (shift == 0) {
            swal({
                title: 'Shift tidak boleh kosong',
                text: 'Klik Ok untuk input data kembali',
                type: 'error'
            });
        } else {
            window.location.href = '?p=Input-Lap-Cwarna-Fin-New&nodemand=' + nodemand + '&shift=' + shift;
        }
    }
</script>
<?php
include "koneksi.php";
ini_set("error_reporting", 1);
if ($_POST['simpan'] == "simpan") {

    $ceksql = sqlsrv_query($cond, "
        SELECT TOP 1 *
        FROM db_qc.tbl_lap_inspeksi
        WHERE nodemand='$_GET[nodemand]'
          and shift='$_POST[shift]'
          AND pisah_kain='$_POST[no_revisi]'
          AND proses='$_POST[proses]'
          AND CAST(tgl_update AS date) = CAST(GETDATE() AS date)
          AND dept='QCF'
    ");

    $cekrow = sqlsrv_fetch_array($ceksql, SQLSRV_FETCH_ASSOC);
    $cek = ($cekrow ? 1 : 0);

    if ($cek > 0) {

        $pelanggan = str_replace("'", "''", $_POST['pelanggan']);
        $order = str_replace("'", "''", $_POST['no_order']);
        $po = str_replace("'", "''", $_POST['no_po']);
        $jns = str_replace("'", "''", $_POST['jenis_kain']);
        $warna = str_replace("'", "''", $_POST['warna']);
        $lot_lgcy = str_replace("'", "''", $_POST['lot_lgcy']);
        $spectro = str_replace("'", "''", $_POST['spectro']);
        $kk_lgcy = str_replace("'", "''", $_POST['kk_lgcy']);
        $catatan = str_replace("'", "''", $_POST['catatan']);
        $colorist_qcf = str_replace("'", "''", $_POST['colorist_qcf']);

        $sql1 = sqlsrv_query($cond, "
            UPDATE db_qc.tbl_lap_inspeksi SET
              no_order='$order',
              no_po='$po',
              pelanggan='$pelanggan',
              jenis_kain='$jns',
              no_item='$_POST[no_item]',
              warna='$warna',
              no_warna='$_POST[no_warna]',
              tgl_pengiriman='$_POST[awal]',
              lot='$_POST[lot]',
              pisah_kain='$_POST[no_revisi]',
              jml_roll='$_POST[rol]',
              bruto='$_POST[bruto]',
              proses='$_POST[proses]',
              status='$_POST[status]',
              spectro='$spectro',
              tgl_update='$_POST[tgl]',
              disposisi='$_POST[disposisi]',
              colorist_qcf='$colorist_qcf',
              lot_lgcy='$lot_lgcy',
              kk_lgcy='$kk_lgcy',
              catatan='$catatan'
            WHERE nodemand='$_POST[nodemand]'
        ");

        if ($sql1) {
            echo "<script>swal({
                title: 'Data has been updated!',
                text: 'Klik Ok untuk input data kembali',
                type: 'success',
            }).then((result) => {
                if (result.value) {
                    window.location.href='?p=Input-Lap-Cwarna-Fin-New&nodemand=$_POST[nodemand]';
                }
            });</script>";
        }

    } else {

        $pelanggan = str_replace("'", "''", $_POST['pelanggan']);
        $order = str_replace("'", "''", $_POST['no_order']);
        $po = str_replace("'", "''", $_POST['no_po']);
        $jns = str_replace("'", "''", $_POST['jenis_kain']);
        $warna = str_replace("'", "''", $_POST['warna']);
        $lot_lgcy = str_replace("'", "''", $_POST['lot_lgcy']);
        $kk_lgcy = str_replace("'", "''", $_POST['kk_lgcy']);
        $spectro = str_replace("'", "''", $_POST['spectro']);
        $catatan = str_replace("'", "''", $_POST['catatan']);
        $colorist_qcf = str_replace("'", "''", $_POST['colorist_qcf']);

        $sql = sqlsrv_query($cond, "
            INSERT INTO db_qc.tbl_lap_inspeksi
            (nokk, nodemand, no_order, no_po, pelanggan, jenis_kain, no_item, warna, no_warna, tgl_pengiriman, lot, shift,
             jml_roll, pisah_kain, bruto, proses, status, catatan, lot_lgcy, kk_lgcy, tgl_cwarna, spectro, dept,
             disposisi, colorist_qcf, jam_update, tgl_update)
            VALUES
            ('$_POST[nokk]', '$_POST[nodemand]', '$order', '$po', '$pelanggan', '$jns', '$_POST[no_item]', '$warna', '$_POST[no_warna]',
             '$_POST[awal]', '$_POST[lot]', '$_POST[shift]', '$_POST[rol]', '$_POST[no_revisi]', '$_POST[bruto]', '$_POST[proses]',
             '$_POST[status]', '$catatan', '$lot_lgcy', '$kk_lgcy', GETDATE(), '$spectro', 'QCF',
             '$_POST[disposisi]', '$colorist_qcf', GETDATE(), '$_POST[tgl]')
        ");

        if ($sql) {
            echo "<script>swal({
                title: 'Data has been saved!',
                text: 'Klik Ok untuk input data kembali',
                type: 'success',
            }).then((result) => {
                if (result.value) {
                    window.location.href='?p=Input-Lap-Cwarna-Fin-New&nodemand=$_POST[nodemand]';
                }
            });</script>";
        }
    }
}
?>

<?Php
if ($_GET['nodemand'] != "") {
    $nodemand = $_GET['nodemand'];
} else {
    $nodemand = " ";
}
if ($_GET['shift'] != "") {
    $shift = $_GET['shift'];
} else {
    $shift = " ";
}

//Data sudah disimpan di database (SQL Server)
$msql = sqlsrv_query($cond, "SELECT TOP 1 * FROM db_qc.tbl_lap_inspeksi WHERE nodemand='$nodemand' and shift='$_GET[shift]' AND CAST(tgl_update AS date) = CAST(GETDATE() AS date) AND dept='QCF'");
$row  = sqlsrv_fetch_array($msql, SQLSRV_FETCH_ASSOC);
$msqlc = sqlsrv_query($cond, "SELECT COUNT(*) AS jml FROM db_qc.tbl_lap_inspeksi WHERE nodemand='$nodemand' and shift='$_GET[shift]' AND CAST(tgl_update AS date) = CAST(GETDATE() AS date) AND dept='QCF'");
$rc   = sqlsrv_fetch_array($msqlc, SQLSRV_FETCH_ASSOC);
$crow = (int)$rc['jml'];


//Data sudah disimpan di database (SQL Server)
$msql1 = sqlsrv_query($cond, "SELECT TOP 1 * FROM db_qc.tbl_lap_inspeksi WHERE nodemand='$nodemand' and shift='$_GET[shift]' AND dept='QCF'");
$row1  = sqlsrv_fetch_array($msql1, SQLSRV_FETCH_ASSOC);
$msql1c = sqlsrv_query($cond, "SELECT COUNT(*) AS jml FROM db_qc.tbl_lap_inspeksi WHERE nodemand='$nodemand' and shift='$_GET[shift]' AND dept='QCF'");
$rc1   = sqlsrv_fetch_array($msql1c, SQLSRV_FETCH_ASSOC);
$crow1 = (int)$rc1['jml'];


//Data sudah disimpan di database (SQL Server)
$qryfin = sqlsrv_query($cond, "SELECT TOP 1 * FROM db_qc.tbl_lap_inspeksi WHERE nodemand='$nodemand' AND dept='QCF' ORDER BY id DESC");
$rfin   = sqlsrv_fetch_array($qryfin, SQLSRV_FETCH_ASSOC);
$qryfinc = sqlsrv_query($cond, "SELECT COUNT(*) AS jml FROM db_qc.tbl_lap_inspeksi WHERE nodemand='$nodemand' AND dept='QCF'");
$rc2    = sqlsrv_fetch_array($qryfinc, SQLSRV_FETCH_ASSOC);
$cekfin = (int)$rc2['jml'];

// NOW
$sql_ITXVIEWKK  = db2_exec($conn2, "SELECT
                                        TRIM(PRODUCTIONORDERCODE) AS PRODUCTIONORDERCODE,
                                        TRIM(DEAMAND) AS DEMAND,
                                        ORIGDLVSALORDERLINEORDERLINE,
                                        PROJECTCODE,
                                        ORDPRNCUSTOMERSUPPLIERCODE,
                                        TRIM(SUBCODE01) AS SUBCODE01, TRIM(SUBCODE02) AS SUBCODE02, TRIM(SUBCODE03) AS SUBCODE03, TRIM(SUBCODE04) AS SUBCODE04,
                                        TRIM(SUBCODE05) AS SUBCODE05, TRIM(SUBCODE06) AS SUBCODE06, TRIM(SUBCODE07) AS SUBCODE07, TRIM(SUBCODE08) AS SUBCODE08,
                                        TRIM(SUBCODE09) AS SUBCODE09, TRIM(SUBCODE10) AS SUBCODE10, 
                                        TRIM(ITEMTYPEAFICODE) AS ITEMTYPEAFICODE,
                                        TRIM(DSUBCODE05) AS NO_WARNA,
                                        TRIM(DSUBCODE02) || '-' || TRIM(DSUBCODE03)  AS NO_HANGER,
                                        TRIM(ITEMDESCRIPTION) AS ITEMDESCRIPTION,
                                        DELIVERYDATE,
                                        CREATIONUSER_SALESORDER,
                                        LOT,
                                        QTY_PACKING_KG,
                                        QTY_PACKING_YARD
                                        -- ABSUNIQUEID_DEMAND
                                    FROM 
                                        ITXVIEWKK 
                                    WHERE 
                                        DEAMAND = '$nodemand'");
$dt_ITXVIEWKK	= db2_fetch_assoc($sql_ITXVIEWKK);

$sql_pelanggan_buyer 	= db2_exec($conn2, "SELECT TRIM(LANGGANAN) AS PELANGGAN, TRIM(BUYER) AS BUYER FROM ITXVIEW_PELANGGAN 
                                            WHERE ORDPRNCUSTOMERSUPPLIERCODE = '$dt_ITXVIEWKK[ORDPRNCUSTOMERSUPPLIERCODE]' 
                                            AND CODE = '$dt_ITXVIEWKK[PROJECTCODE]'");
$dt_pelanggan_buyer		= db2_fetch_assoc($sql_pelanggan_buyer);

$sql_po			= db2_exec($conn2, "SELECT 
                                        TRIM(EXTERNALREFERENCE) AS NO_PO, 
                                        SUM(USERPRIMARYQUANTITY) AS QTY_BRUTO 
                                    FROM 
                                        ITXVIEW_KGBRUTO 
                                    WHERE 
                                        PROJECTCODE = '$dt_ITXVIEWKK[PROJECTCODE]' 	
                                    AND ORIGDLVSALORDERLINEORDERLINE = '$dt_ITXVIEWKK[ORIGDLVSALORDERLINEORDERLINE]'
                                    GROUP BY
                                        EXTERNALREFERENCE");
$dt_po    		= db2_fetch_assoc($sql_po);

$sql_noitem     = db2_exec($conn2, "SELECT * FROM ORDERITEMORDERPARTNERLINK WHERE ORDPRNCUSTOMERSUPPLIERCODE = '$dt_ITXVIEWKK[ORDPRNCUSTOMERSUPPLIERCODE]' 
                                    AND SUBCODE01 = '$dt_ITXVIEWKK[SUBCODE01]' AND SUBCODE02 = '$dt_ITXVIEWKK[SUBCODE02]' 
                                    AND SUBCODE03 = '$dt_ITXVIEWKK[SUBCODE03]' AND SUBCODE04 = '$dt_ITXVIEWKK[SUBCODE04]' 
                                    AND SUBCODE05 = '$dt_ITXVIEWKK[SUBCODE05]' AND SUBCODE06 = '$dt_ITXVIEWKK[SUBCODE06]'
                                    AND SUBCODE07 = '$dt_ITXVIEWKK[SUBCODE07]' AND SUBCODE08 ='$dt_ITXVIEWKK[SUBCODE08]'
                                    AND SUBCODE09 = '$dt_ITXVIEWKK[SUBCODE09]' AND SUBCODE10 ='$dt_ITXVIEWKK[SUBCODE10]'");
$dt_item        = db2_fetch_assoc($sql_noitem);

$sql_lebargramasi	= db2_exec($conn2, "SELECT i.LEBAR,
                                                CASE
                                                    WHEN i2.GRAMASI_KFF IS NULL THEN i2.GRAMASI_FKF
                                                    ELSE i2.GRAMASI_KFF
                                                END AS GRAMASI 
                                                FROM 
                                                    ITXVIEWLEBAR i 
                                                LEFT JOIN ITXVIEWGRAMASI i2 ON i2.SALESORDERCODE = '$dt_ITXVIEWKK[PROJECTCODE]' AND i2.ORDERLINE = '$dt_ITXVIEWKK[ORIGDLVSALORDERLINEORDERLINE]'
                                                WHERE 
                                                    i.SALESORDERCODE = '$dt_ITXVIEWKK[PROJECTCODE]' AND i.ORDERLINE = '$dt_ITXVIEWKK[ORIGDLVSALORDERLINEORDERLINE]'");
$dt_lg				= db2_fetch_assoc($sql_lebargramasi);

$sql_warna		= db2_exec($conn2, "SELECT DISTINCT TRIM(WARNA) AS WARNA FROM ITXVIEWCOLOR 
                                    WHERE ITEMTYPECODE = '$dt_ITXVIEWKK[ITEMTYPEAFICODE]' 
                                    AND SUBCODE01 = '$dt_ITXVIEWKK[SUBCODE01]' 
                                    AND SUBCODE02 = '$dt_ITXVIEWKK[SUBCODE02]'
                                    AND SUBCODE03 = '$dt_ITXVIEWKK[SUBCODE03]' 
                                    AND SUBCODE04 = '$dt_ITXVIEWKK[SUBCODE04]'
                                    AND SUBCODE05 = '$dt_ITXVIEWKK[SUBCODE05]' 
                                    AND SUBCODE06 = '$dt_ITXVIEWKK[SUBCODE06]'
                                    AND SUBCODE07 = '$dt_ITXVIEWKK[SUBCODE07]' 
                                    AND SUBCODE08 = '$dt_ITXVIEWKK[SUBCODE08]'
                                    AND SUBCODE09 = '$dt_ITXVIEWKK[SUBCODE09]' 
                                    AND SUBCODE10 = '$dt_ITXVIEWKK[SUBCODE10]'");
$dt_warna		= db2_fetch_assoc($sql_warna);

$sql_roll		= db2_exec($conn2, "SELECT count(*) AS ROLL, s2.PRODUCTIONORDERCODE
                                    FROM STOCKTRANSACTION s2 
                                    WHERE s2.ITEMTYPECODE ='KGF' AND s2.PRODUCTIONORDERCODE = '$dt_ITXVIEWKK[PRODUCTIONORDERCODE]'
                                    GROUP BY s2.PRODUCTIONORDERCODE");
$dt_roll   		= db2_fetch_assoc($sql_roll);

$sql_qtyorder   = db2_exec($conn2, "SELECT DISTINCT
                                        USEDUSERPRIMARYQUANTITY AS QTY_ORDER,
                                        USEDUSERSECONDARYQUANTITY AS QTY_ORDER_YARD,
                                    CASE
                                        WHEN TRIM(USERSECONDARYUOMCODE) = 'kg' THEN 'Kg'
                                        WHEN TRIM(USERSECONDARYUOMCODE) = 'yd' THEN 'Yard'
                                        WHEN TRIM(USERSECONDARYUOMCODE) = 'm' THEN 'Meter'
                                        ELSE 'PCS'
                                    END AS SATUAN_QTY
                                    FROM 
                                    ITXVIEW_RESERVATION_KK 
                                    WHERE 
                                    ORDERCODE = '$nodemand'");
$dt_qtyorder    = db2_fetch_assoc($sql_qtyorder);

$sql_netto		= db2_exec($conn2, "SELECT * FROM ITXVIEW_NETTO WHERE CODE = '$nodemand' AND SALESORDERLINESALESORDERCODE = '$dt_ITXVIEWKK[PROJECTCODE]'");
$d_netto		= db2_fetch_assoc($sql_netto);
// NOW
?>
<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Input Data</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nodemand" class="col-sm-3 control-label">No Demand</label>
                    <div class="col-sm-4">
                        <input name="nokk" type="hidden" class="form-control" id="nokk" value="<?php echo $dt_ITXVIEWKK['PRODUCTIONORDERCODE']; ?>">
                        <input name="nodemand" type="text" class="form-control" id="nodemand" onchange="proses_nodemand()" value="<?php echo $_GET['nodemand']; ?>" placeholder="No Demand" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="shift" class="col-sm-3 control-label">Shift</label>
                    <div class="col-sm-2">
                        <select class="form-control select2" required name="shift" id="shift" onchange="proses_shift()">
                            <option value="0">Pilih</option>
                            <option value="A" <?php if ($_GET['shift'] == "A") {
                                                    echo "SELECTED";
                                                } ?>>A</option>
                            <option value="B" <?php if ($_GET['shift'] == "B") {
                                                    echo "SELECTED";
                                                } ?>>B</option>
                            <option value="C" <?php if ($_GET['shift'] == "C") {
                                                    echo "SELECTED";
                                                } ?>>C</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pelanggan" class="col-sm-3 control-label">Pelanggan</label>
                    <div class="col-sm-8">
                        <input name="pelanggan" type="text" class="form-control" id="pelanggan" value="<?php if ($crow > 0) {
                                                                                                            echo $row['pelanggan'];
                                                                                                        } else {
                                                                                                            echo $dt_pelanggan_buyer['PELANGGAN']."/".$dt_pelanggan_buyer['BUYER'];
                                                                                                        } ?>" placeholder="Pelanggan">
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_po" class="col-sm-3 control-label">PO</label>
                    <div class="col-sm-5">
                        <input name="no_po" class="form-control" type="text" id="no_po" value="<?php if ($crow > 0) {
                                                                                                    echo $row['no_po'];
                                                                                                } else {
                                                                                                    echo $dt_po['NO_PO'];
                                                                                                } ?>" placeholder="PO" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_order" class="col-sm-3 control-label">No Order</label>
                    <div class="col-sm-4">
                        <input name="no_order" type="text" class="form-control" id="no_order" value="<?php if ($crow > 0) {
                                                                                                            echo $row['no_order'];
                                                                                                        } else {
																											echo $dt_ITXVIEWKK['PROJECTCODE'];
                                                                                                        } ?>" placeholder="No Order" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="jenis_kain" class="col-sm-3 control-label">Jenis Kain</label>
                    <div class="col-sm-8">
                        <textarea name="jenis_kain" class="form-control" id="jenis_kain" placeholder="Jenis Kain"><?php if ($crow > 0) {
                                                                                                                        echo $row['jenis_kain'];
                                                                                                                    } else {
    																													echo stripslashes($dt_ITXVIEWKK['ITEMDESCRIPTION']);
                                                                                                                    } ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_item" class="col-sm-3 control-label">No Item</label>
                    <div class="col-sm-3">
                        <input name="no_item" type="text" class="form-control" id="no_item" value="<?php if ($crow > 0) {
                                                                                                        echo $row['no_item'];
																										}else if ($dt_item['EXTERNALITEMCODE'] != "") {
                                                                                                        echo $dt_item['EXTERNALITEMCODE'];
                                                                                                    } else{
																										echo $dt_ITXVIEWKK['SUBCODE02']."".$dt_ITXVIEWKK['SUBCODE03'];
                                                                                                    } ?>" placeholder="No Item">
                    </div>
                </div>
                <div class="form-group">
                    <label for="warna" class="col-sm-3 control-label">Warna</label>
                    <div class="col-sm-8">
                        <textarea name="warna" class="form-control" id="warna" placeholder="Warna"><?php if ($crow > 0) {
                                                                                                        echo $row['warna'];
                                                                                                    } else {
																										echo stripslashes($dt_warna['WARNA']);
                                                                                                    } ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_warna" class="col-sm-3 control-label">No Warna</label>
                    <div class="col-sm-8">
                        <textarea name="no_warna" class="form-control" id="no_warna" placeholder="No Warna"><?php if ($crow > 0) {
                                                                                                                echo $row['no_warna'];
                                                                                                            } else {
																												echo stripslashes($dt_ITXVIEWKK['NO_WARNA']);
                                                                                                            } ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tgl" class="col-sm-3 control-label">Tgl Fin</label>
                    <div class="col-sm-4">
                        <div class="input-group date">
                            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                            <input name="tgl" type="text" class="form-control pull-right" id="datepicker" placeholder="0000-00-00" value="<?php if ($crow > 0) {
                                                                                                                                                echo $row['tgl_update'];
                                                                                                                                            } ?>" required />
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group">		  	
                <label for="awal" class="col-sm-3 control-label">Tgl Celup</label>
                    <div class="col-sm-4">					  
                        <div class="input-group date">
                            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                                <input name="awal" type="text" class="form-control pull-right" id="datepicker1" placeholder="0000-00-00" value="<?php if ($crow > 0) {
                                                                                                                                                    echo $row['tgl_pengiriman'];
                                                                                                                                                } ?>" required/>
                        </div>
                    </div>
            </div> -->
                <div class="form-group">
                    <label for="no_revisi" class="col-sm-3 control-label">Revisi Ke</label>
                    <div class="col-sm-2">
                        <select class="form-control select2" name="no_revisi" required>
                            <option value="0" <?php if ($row['pisah_kain'] == "0") {
                                                    echo "SELECTED";
                                                } ?>>1</option>
                            <option value="1" <?php if ($row['pisah_kain'] == "1") {
                                                    echo "SELECTED";
                                                } ?>>2</option>
                            <option value="2" <?php if ($row['pisah_kain'] == "2") {
                                                    echo "SELECTED";
                                                } ?>>3</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lot" class="col-sm-3 control-label">Prod. Order/Lot</label>
                    <div class="col-sm-3">
                        <input name="lot" class="form-control" type="text" id="lot" value="<?php if ($crow > 0) {
                                                                                                echo $row['lot'];
                                                                                            } else {
																								echo $dt_ITXVIEWKK['PRODUCTIONORDERCODE'];
                                                                                            } ?>" placeholder="Lot">
                    </div>
                </div>
                <div class="form-group">
                    <label for="kk_lgcy" class="col-sm-3 control-label">No KK Legacy</label>
                    <div class="col-sm-3">
                        <input name="kk_lgcy" class="form-control" type="text" id="kk_lgcy" value="<?php if ($crow > 0) {
                                                                                                        echo $row['kk_lgcy'];
                                                                                                    } ?>" placeholder="No KK Legacy" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lot_lgcy" class="col-sm-3 control-label">Lot Legacy</label>
                    <div class="col-sm-3">
                        <input name="lot_lgcy" class="form-control" type="text" id="lot_lgcy" value="<?php if ($crow > 0) {
                                                                                                            echo $row['lot_lgcy'];
                                                                                                        } ?>" placeholder="Lot Legacy" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="qty_bruto" class="col-sm-3 control-label">Qty Bruto</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input name="rol" type="text" class="form-control" id="rol" value="<?php if ($crow > 0) {
                                                                                                    echo $row['jml_roll'];
                                                                                                } else {
                                                                                                    echo $dt_roll['ROLL'];
                                                                                                } ?>" placeholder="" required>
                            <span class="input-group-addon">Roll</span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input name="bruto" type="text" class="form-control" id="bruto" value="<?php if ($crow > 0) {
                                                                                                        echo $row['bruto'];
                                                                                                    } else {
                                                                                                        echo number_format($dt_po['QTY_BRUTO'], '2', '.', '');
                                                                                                    } ?>" placeholder="0.00" required>
                            <span class="input-group-addon">KGs</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="proses" class="col-sm-3 control-label">Code Proses</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="proses">
                            <option value="Fin" <?php if ($row['proses'] == "Fin") {
                                                    echo "SELECTED";
                                                } ?>>Fin</option>
                            <option value="Fin 1X" <?php if ($row['proses'] == "Fin 1X") {
                                                        echo "SELECTED";
                                                    } ?>>Fin 1X</option>
                            <option value="Pdr" <?php if ($row['proses'] == "Pdr") {
                                                    echo "SELECTED";
                                                } ?>>Pdr</option>
                            <option value="Oven" <?php if ($row['proses'] == "Oven") {
                                                        echo "SELECTED";
                                                    } ?>>Oven</option>
                            <option value="Comp" <?php if ($row['proses'] == "Comp") {
                                                        echo "SELECTED";
                                                    } ?>>Comp</option>
                            <option value="Setting" <?php if ($row['proses'] == "Setting") {
                                                        echo "SELECTED";
                                                    } ?>>Setting</option>
                            <option value="AP" <?php if ($row['proses'] == "AP") {
                                                    echo "SELECTED";
                                                } ?>>AP</option>
                            <option value="PB" <?php if ($row['proses'] == "PB") {
                                                    echo "SELECTED";
                                                } ?>>PB</option>
                            <option value="LSR" <?php if ($row['proses'] == "LSR") {
                                                    echo "SELECTED";
                                                } ?>>LSR</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">Status Warna</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="status" onChange="tampil();">
                            <option value="">Pilih</option>
                            <option value="OK" <?php if ($row['status'] == "OK") {
                                                    echo "SELECTED";
                                                } ?>>OK</option>
                            <option value="BW" <?php if ($row['status'] == "BW") {
                                                    echo "SELECTED";
                                                } ?>>BW</option>
                            <option value="TBD" <?php if ($row['status'] == "TBD") {
                                                    echo "SELECTED";
                                                } ?>>TBD</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="disposisi" style="display:none;">
                    <label for="disposisi" class="col-sm-3 control-label">Disposisi</label>
                    <div class="col-sm-5">
                        <input name="disposisi" class="form-control" type="text" id="disposisi" value="<?php echo $row['disposisi']; ?>" placeholder="Disposisi">
                    </div>
                </div>
                <div class="form-group">
                    <label for="colorist_qcf" class="col-sm-3 control-label">Colorist QCF</label>
                    <div class="col-sm-5">
                        <input name="colorist_qcf" class="form-control" type="text" id="colorist_qcf" value="<?php echo $row['colorist_qcf']; ?>" placeholder="Colorist QCF">
                    </div>
                </div>
                <div class="form-group">
                    <label for="catatan" class="col-sm-3 control-label">Ket</label>
                    <div class="col-sm-8">
                        <textarea name="catatan" class="form-control" id="catatan" placeholder="Keterangan"><?php echo $row['catatan']; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="spectro" class="col-sm-3 control-label">Spectro</label>
                    <div class="col-sm-8">
                        <div class="radio">
                            <label>
                                <input type="radio" name="spectro" value="1" 
                                <?php echo ($row['spectro'] === 1) ? 'checked' : ''; ?>>
                                Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="spectro" value="0" 
                                <?php echo ($row['spectro'] === 0 || $row['spectro'] === null) ? 'checked' : ''; ?>>
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <?php if ($_GET['nodemand'] != "" and $_GET['shift'] != "" and $cek == 0) { ?>
                <button type="submit" class="btn btn-primary pull-right" name="simpan" value="simpan"><i class="fa fa-save"></i> Simpan</button>
            <?php } ?>
            <!--<button type="submit" class="btn btn-primary pull-right" name="ubah" value="ubah"><i class="fa fa-edit"></i> Ubah</button> -->

            <button type="button" class="btn btn-warning pull-left" name="lihat" value="lihat" onClick="window.location.href='?p=Lihat-Data-Cwarna-Fin-New'"><i class="fa fa-search"></i> Lihat Data</button>
        </div>
    </div>
</form>