<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
if ($_SESSION['dept10'] == "CSR") {
	$demand = $_GET['demand'];
	$child = $r['ChildLevel'];
	if ($demand != "") {
	}
	$sqlCek = sqlsrv_query($cona, "SELECT TOP 1 * FROM db_adm.tbl_gantikain WHERE nodemand='$demand' and nodemand <> '' ORDER BY id DESC");
	// $cek = mysqli_num_rows($sqlCek);
	$rcek = sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC);
	$cek = ($rcek !== false) ? 1 : 0;

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
												DELIVERYDATE
											FROM 
												ITXVIEWKK 
											WHERE 
												DEAMAND = '$demand'");
}
if ($_SESSION['dept10'] != "CSR") {
	$nokk = $_GET['nokk'];
	$demandno = $_GET['demand'];
	$child = $r['ChildLevel'];
	if ($nokk != "") {
	}
	$dept_user = $_SESSION['dept10'];
	// $sqlCek = sqlsrv_query($cona, "SELECT * FROM db_adm.tbl_gantikain WHERE nokk='$nokk' and nodemand='$demandno' and dept ='$dept_user' ORDER BY id DESC LIMIT 1");
	// $cek = mysqli_num_rows($sqlCek);
	// $rcek = sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC);
	// $cek = ($rcek !== false) ? 1 : 0;

	$sqlCek = sqlsrv_query($cona, "
		SELECT TOP 1 *
		FROM db_adm.tbl_gantikain
		WHERE nodemand = ? AND nodemand <> ''
		ORDER BY id DESC
	", [$demand]);
	if ($sqlCek === false) {
		die(print_r(sqlsrv_errors(), true));
	}

	$rcek = sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC);
	$cek  = ($rcek !== null && $rcek !== false) ? 1 : 0;

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
											DELIVERYDATE
										FROM 
											ITXVIEWKK 
										WHERE 
											PRODUCTIONORDERCODE = '$nokk' AND DEAMAND = '$demandno' ");
}


$dt_ITXVIEWKK	= db2_fetch_assoc($sql_ITXVIEWKK);

if (!empty($_GET['demand'])) {
	$nokk = $dt_ITXVIEWKK['PRODUCTIONORDERCODE'];
} else {
	$nokk = $_GET['nokk'];
}

$sql_pelanggan_buyer 	= db2_exec($conn2, "SELECT TRIM(LANGGANAN) AS PELANGGAN, TRIM(BUYER) AS BUYER FROM ITXVIEW_PELANGGAN 
													WHERE ORDPRNCUSTOMERSUPPLIERCODE = '$dt_ITXVIEWKK[ORDPRNCUSTOMERSUPPLIERCODE]' AND CODE = '$dt_ITXVIEWKK[PROJECTCODE]'");
$dt_pelanggan_buyer		= db2_fetch_assoc($sql_pelanggan_buyer);

$sql_demand		= db2_exec($conn2, "SELECT LISTAGG(TRIM(DEAMAND), ', ') AS DEMAND,
												LISTAGG(''''|| TRIM(ORIGDLVSALORDERLINEORDERLINE) ||'''', ', ')  AS ORIGDLVSALORDERLINEORDERLINE
										FROM ITXVIEWKK 
										WHERE PRODUCTIONORDERCODE = '$nokk'");
$dt_demand		= db2_fetch_assoc($sql_demand);

if (!empty($dt_demand['ORIGDLVSALORDERLINEORDERLINE'])) {
	$orderline	= $dt_demand['ORIGDLVSALORDERLINEORDERLINE'];
} else {
	$orderline	= '0';
}

$sql_po			= db2_exec($conn2, "SELECT TRIM(EXTERNALREFERENCE) AS NO_PO FROM ITXVIEW_KGBRUTO 
										WHERE PROJECTCODE = '$dt_ITXVIEWKK[PROJECTCODE]' AND ORIGDLVSALORDERLINEORDERLINE IN ($orderline)");
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

$sql_qtyorder   = db2_exec($conn2, "SELECT DISTINCT
						INITIALUSERPRIMARYQUANTITY AS QTY_ORDER,
						USERSECONDARYQUANTITY AS QTY_ORDER_YARD,
						CASE
							WHEN TRIM(USERSECONDARYUOMCODE) = 'yd' THEN 'Yard'
							WHEN TRIM(USERSECONDARYUOMCODE) = 'm' THEN 'Meter'
							ELSE 'PCS'
						END AS SATUAN_QTY
					FROM 
						ITXVIEW_RESERVATION 
					WHERE 
						PRODUCTIONORDERCODE = '$dt_ITXVIEWKK[PRODUCTIONORDERCODE]' AND ITEMTYPEAFICODE = 'RFD'");
$dt_qtyorder    = db2_fetch_assoc($sql_qtyorder);

$sql_roll		= db2_exec($conn2, "SELECT count(*) AS ROLL, s2.PRODUCTIONORDERCODE
											FROM STOCKTRANSACTION s2 
											WHERE s2.ITEMTYPECODE ='KGF' AND s2.PRODUCTIONORDERCODE = '$dt_ITXVIEWKK[PRODUCTIONORDERCODE]'
											GROUP BY s2.PRODUCTIONORDERCODE");
$dt_roll   		= db2_fetch_assoc($sql_roll);

$sql_mesinknt	= db2_exec($conn2, "SELECT DISTINCT
											s.LOTCODE,
											CASE
												WHEN a.VALUESTRING IS NULL THEN '-'
												ELSE a.VALUESTRING
											END AS VALUESTRING
										FROM STOCKTRANSACTION s 
										LEFT JOIN PRODUCTIONDEMAND p ON p.CODE = s.LOTCODE 
										LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.NAMENAME = 'MachineNo'
										WHERE s.PRODUCTIONORDERCODE = '$nokk'");
$dt_mesinknt	= db2_fetch_assoc($sql_mesinknt);

$sql_bonresep1	= db2_exec($conn2, "SELECT
											TRIM(PRODUCTIONRESERVATION.PRODUCTIONORDERCODE) AS PRODUCTIONORDERCODE,
											TRIM(PRODUCTIONRESERVATION.PRODUCTIONORDERCODE) || '-' || TRIM(PRODUCTIONRESERVATION.GROUPLINE) AS BONRESEP1,
											TRIM(SUFFIXCODE) AS SUFFIXCODE
										FROM
											PRODUCTIONRESERVATION PRODUCTIONRESERVATION 
										WHERE
											PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'RFD' AND PRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$nokk' 
											AND NOT SUFFIXCODE = '001'
										ORDER BY
											PRODUCTIONRESERVATION.GROUPLINE ASC LIMIT 1");
$dt_bonresep1	= db2_fetch_assoc($sql_bonresep1);

$sql_bonresep2	= db2_exec($conn2, "SELECT
											TRIM( PRODUCTIONRESERVATION.PRODUCTIONORDERCODE ) AS PRODUCTIONORDERCODE,
											TRIM(PRODUCTIONRESERVATION.PRODUCTIONORDERCODE) || '-' || TRIM(PRODUCTIONRESERVATION.GROUPLINE) AS BONRESEP2,
											TRIM(SUFFIXCODE) AS SUFFIXCODE
										FROM
											PRODUCTIONRESERVATION PRODUCTIONRESERVATION 
										WHERE
											PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'RFD' AND PRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$nokk' 
											AND NOT SUFFIXCODE = '001'
										ORDER BY
											PRODUCTIONRESERVATION.GROUPLINE DESC LIMIT 1");
$dt_bonresep2	= db2_fetch_assoc($sql_bonresep2);
// NOW
?>
<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Input Data Kartu Kerja</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="col-md-6">
				<?php if ($_SESSION['dept10'] != "CSR") { ?>
					<div class="form-group">
						<label for="nokk" class="col-sm-3 control-label">Production Order</label>
						<div class="col-sm-4">
							<input name="nokk" type="text" class="form-control" id="nokk" onchange="window.location='?p=Input-Bon&nokk='+this.value" value="<?php echo $_GET['nokk']; ?>" placeholder="Production Order" required>
						</div>
					</div>
					<?php
					// Ubah string menjadi array
					$data = $dt_demand['DEMAND'];
					$arrayData = explode(', ', $data);
					?>
					<div class="form-group">
						<label for="demand" class="col-sm-3 control-label">Production Demand</label>
						<div class="col-sm-4" hidden="hidden">
							<input name="demand" type="text" class="form-control" id="demand" value="<?= $dt_demand['DEMAND']; ?><?php if ($cek > 0) {
																																		echo $rcek['nodemand'];
																																	} ?>" placeholder="Production Demand">
						</div>
						<div class="col-sm-4">
							<select class="form-control select2" name="demand" onchange="window.location='?p=Input-Bon&nokk=<?php echo $_GET['nokk']; ?>&demand='+this.value" required>
								<option value="">-Pilih-</option>
								<?php foreach ($arrayData as $value): ?>
									<option value="<?= htmlspecialchars($value) ?>" <?php if ($_GET['demand'] == htmlspecialchars($value)) {
																						echo "SELECTED";
																					} ?>><?= htmlspecialchars($value) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

				<?php } ?>
				<?php if ($_SESSION['dept10'] == "CSR") { ?>
					<div class="form-group">
						<label for="demand" class="col-sm-3 control-label">Production Demand</label>
						<div class="col-sm-8">
							<input name="demand" type="text" class="form-control" id="demand" onchange="window.location='?p=Input-Bon&demand='+this.value" value="<?php echo $_GET['demand']; ?>" placeholder="Production Demand">
						</div>
					</div>
					<div class="form-group">
						<label for="nokk" class="col-sm-3 control-label">Production Order</label>
						<div class="col-sm-4">
							<input name="nokk" type="text" class="form-control" id="nokk" value="<?= $dt_ITXVIEWKK['PRODUCTIONORDERCODE']; ?><?php if ($cek > 0) {
																																					echo $rcek['nokk'];
																																				} ?>" placeholder="Production Order" required>
						</div>
					</div>
				<?php } ?>
				<div class="form-group">
					<label for="no_order" class="col-sm-3 control-label">No Order</label>
					<div class="col-sm-4">
						<input name="no_order" type="text" class="form-control" id="no_order" value="<?= $dt_ITXVIEWKK['PROJECTCODE']; ?>" placeholder="No Order" required>
					</div>
				</div>
				<div class="form-group">
					<label for="no_po" class="col-sm-3 control-label">Pelanggan</label>
					<div class="col-sm-8">
						<input name="pelanggan" type="text" class="form-control" id="no_po" value="<?= $dt_pelanggan_buyer['PELANGGAN']; ?>" placeholder="Pelanggan">
					</div>
				</div>
				<div class="form-group">
					<label for="no_po" class="col-sm-3 control-label">PO</label>
					<div class="col-sm-5">
						<input name="no_po" type="text" class="form-control" id="no_po" value="<?= $dt_po['NO_PO']; ?>" placeholder="PO">
					</div>
				</div>
				<div class="form-group">
					<label for="no_hanger" class="col-sm-3 control-label">No Hanger / No Item</label>
					<div class="col-sm-3">
						<input name="no_hanger" type="text" class="form-control" id="no_hanger" value="<?= $dt_ITXVIEWKK['NO_HANGER'] ?>" placeholder="No Hanger">
					</div>
					<div class="col-sm-3">
						<input name="no_item" type="text" class="form-control" id="no_item" value="<?= $dt_item['EXTERNALITEMCODE'] ?>
																								   <?php if ($rcek['no_item'] != "") {
																										echo $rcek['no_item'];
																									} else {
																										echo $r['ProductCode'];
																									} ?>" placeholder="No Item">
					</div>
				</div>
				<div class="form-group">
					<label for="jns_kain" class="col-sm-3 control-label">Jenis Kain</label>
					<div class="col-sm-8">
						<textarea name="jns_kain" class="form-control" id="jns_kain" placeholder="Jenis Kain"><?= $dt_ITXVIEWKK['ITEMDESCRIPTION'] ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="styl" class="col-sm-3 control-label">Style</label>
					<div class="col-sm-8">
						<input name="styl" type="text" class="form-control" id="styl" value="<?php if ($cek > 0) {
																									echo $rcek['styl'];
																								} else {
																									echo $r['OtherDesc'];
																								} ?>" placeholder="Style">
					</div>
				</div>
				<div class="form-group">
					<label for="l_g" class="col-sm-3 control-label">Lebar X Gramasi</label>
					<div class="col-sm-2">
						<input name="lebar" type="text" class="form-control" id="lebar" value="<?= round($dt_lg['LEBAR']); ?>" placeholder="0" required>
					</div>
					<div class="col-sm-2">
						<input name="grms" type="text" class="form-control" id="grms" value="<?= round($dt_lg['GRAMASI']); ?>" placeholder="0" required>
					</div>
				</div>
				<div class="form-group">
					<label for="tgl_delivery" class="col-sm-3 control-label">Tgl Delivery</label>
					<div class="col-sm-3">
						<div class="input-group date">
							<div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
							<input name="tgl_delivery" type="text" class="form-control" id="datepicker" placeholder="Tanggal Delivery" value="<?= $dt_ITXVIEWKK['DELIVERYDATE']; ?>" autocomplete="off" />
						</div>
					</div>
					<!-- /.input group -->
				</div>
				<div class="form-group">
					<label for="warna" class="col-sm-3 control-label">Warna</label>
					<div class="col-sm-8">
						<textarea name="warna" class="form-control" id="warna" placeholder="Warna"><?= $dt_warna['WARNA']; ?></textarea>
					</div>
				</div>
				<div class="form-group">

					<label for="no_warna" class="col-sm-3 control-label">No Warna</label>
					<div class="col-sm-8">
						<textarea name="no_warna" class="form-control" id="no_warna" placeholder="No Warna"><?= $dt_ITXVIEWKK['NO_WARNA']; ?></textarea>
					</div>
				</div>
			</div>
			<!-- col -->
			<div class="col-md-6">
				<div class="form-group">
					<label for="lot" class="col-sm-3 control-label">Lot</label>
					<div class="col-sm-3">
						<input name="lot" type="text" class="form-control" id="lot" value="<?php if ($cek > 0) {
																								echo $rcek['lot'];
																							} else {
																								echo $lotno;
																							} ?>" placeholder="Lot">
					</div>
				</div>
				<div class="form-group">
					<label for="proses" class="col-sm-3 control-label">Qty Order</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input name="qty_order" type="text" class="form-control" id="qty_order" value="<?= $dt_qtyorder['QTY_ORDER']; ?>" placeholder="0.00" style="text-align: right;" required>
							<span class="input-group-addon"><select name="satuan_o" style="font-size: 12px;" id="satuan1">
									<option value="KG" <?php if ($rcek['satuan_o'] == "KG") {
															echo "SELECTED";
														} ?>>KG</option>
									<option value="PCS" <?php if ($rcek['satuan_o'] == "PCS") {
															echo "SELECTED";
														} ?>>PCS</option>
								</select></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="kategori" class="col-sm-3 control-label">Kategori</label>
					<div class="col-sm-2">
						<select class="form-control select2" name="kategori">
							<option value="">Pilih</option>
							<option value="0" <?php if ($rcek['kategori'] == "0") {
													echo "SELECTED";
												} ?>>Internal</option>
							<option value="1" <?php if ($rcek['kategori'] == "1") {
													echo "SELECTED";
												} ?>>External</option>
							<option value="2" <?php if ($rcek['kategori'] == "2") {
													echo "SELECTED";
												} ?>>FOC</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="tangggung_jawab" class="col-sm-3 control-label">Tanggung Jawab 1</label>
					<div class="col-sm-2">
						<select class="form-control select2" name="t_jawab">
							<option value="">Pilih</option>
							<option value="MKT" <?php if ($rcek['t_jawab'] == "MKT") {
													echo "SELECTED";
												} ?>>MKT</option>
							<option value="FIN" <?php if ($rcek['t_jawab'] == "FIN") {
													echo "SELECTED";
												} ?>>FIN</option>
							<option value="DYE" <?php if ($rcek['t_jawab'] == "DYE") {
													echo "SELECTED";
												} ?>>DYE</option>
							<option value="GAS" <?php if ($rcek['t_jawab'] == "GAS") {
													echo "SELECTED";
												} ?>>GAS</option>
							<option value="KNT" <?php if ($rcek['t_jawab'] == "KNT") {
													echo "SELECTED";
												} ?>>KNT</option>
							<option value="LAB" <?php if ($rcek['t_jawab'] == "LAB") {
													echo "SELECTED";
												} ?>>LAB</option>
							<option value="PRT" <?php if ($rcek['t_jawab'] == "PRT") {
													echo "SELECTED";
												} ?>>PRT</option>
							<option value="KNK" <?php if ($rcek['t_jawab'] == "KNK") {
													echo "SELECTED";
												} ?>>KNK</option>
							<option value="QCF" <?php if ($rcek['t_jawab'] == "QCF") {
													echo "SELECTED";
												} ?>>QCF</option>
							<option value="CQA" <?php if ($rcek['t_jawab'] == "CQA") {
													echo "SELECTED";
												} ?>>CQA</option>
							<option value="GKG" <?php if ($rcek['t_jawab'] == "GKG") {
													echo "SELECTED";
												} ?>>GKG</option>
							<option value="PRO" <?php if ($rcek['t_jawab'] == "PRO") {
													echo "SELECTED";
												} ?>>PRO</option>
							<option value="RMP" <?php if ($rcek['t_jawab'] == "RMP") {
													echo "SELECTED";
												} ?>>RMP</option>
							<option value="PPC" <?php if ($rcek['t_jawab'] == "PPC") {
													echo "SELECTED";
												} ?>>PPC</option>
							<option value="TAS" <?php if ($rcek['t_jawab'] == "TAS") {
													echo "SELECTED";
												} ?>>TAS</option>
							<option value="GKJ" <?php if ($rcek['t_jawab'] == "GKJ") {
													echo "SELECTED";
												} ?>>GKJ</option>
							<option value="BRS" <?php if ($rcek['t_jawab'] == "BRS") {
													echo "SELECTED";
												} ?>>BRS</option>
							<option value="CST" <?php if ($rcek['t_jawab'] == "CST") {
													echo "SELECTED";
												} ?>>CST</option>
							<option value="YND" <?php if ($rcek['t_jawab'] == "YND") {
													echo "SELECTED";
												} ?>>YND</option>
						</select>
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<input name="persen" type="text" class="form-control" id="persen" value="<?php if ($cek > 0) {
																											echo $rcek['persen'];
																										} ?>" placeholder="0.00" style="text-align: right;">
							<span class="input-group-addon">%</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="tangggung_jawab" class="col-sm-3 control-label">Tanggung Jawab 2</label>
					<div class="col-sm-2">
						<select class="form-control select2" name="t_jawab1">
							<option value="">Pilih</option>
							<option value="MKT" <?php if ($rcek['t_jawab1'] == "MKT") {
													echo "SELECTED";
												} ?>>MKT</option>
							<option value="FIN" <?php if ($rcek['t_jawab1'] == "FIN") {
													echo "SELECTED";
												} ?>>FIN</option>
							<option value="DYE" <?php if ($rcek['t_jawab1'] == "DYE") {
													echo "SELECTED";
												} ?>>DYE</option>
							<option value="GAS" <?php if ($rcek['t_jawab'] == "GAS") {
													echo "SELECTED";
												} ?>>GAS</option>
							<option value="KNT" <?php if ($rcek['t_jawab1'] == "KNT") {
													echo "SELECTED";
												} ?>>KNT</option>
							<option value="LAB" <?php if ($rcek['t_jawab1'] == "LAB") {
													echo "SELECTED";
												} ?>>LAB</option>
							<option value="PRT" <?php if ($rcek['t_jawab1'] == "PRT") {
													echo "SELECTED";
												} ?>>PRT</option>
							<option value="KNK" <?php if ($rcek['t_jawab1'] == "KNK") {
													echo "SELECTED";
												} ?>>KNK</option>
							<option value="QCF" <?php if ($rcek['t_jawab1'] == "QCF") {
													echo "SELECTED";
												} ?>>QCF</option>
							<option value="CQA" <?php if ($rcek['t_jawab1'] == "CQA") {
													echo "SELECTED";
												} ?>>CQA</option>
							<option value="GKG" <?php if ($rcek['t_jawab1'] == "GKG") {
													echo "SELECTED";
												} ?>>GKG</option>
							<option value="PRO" <?php if ($rcek['t_jawab1'] == "PRO") {
													echo "SELECTED";
												} ?>>PRO</option>
							<option value="RMP" <?php if ($rcek['t_jawab1'] == "RMP") {
													echo "SELECTED";
												} ?>>RMP</option>
							<option value="PPC" <?php if ($rcek['t_jawab1'] == "PPC") {
													echo "SELECTED";
												} ?>>PPC</option>
							<option value="TAS" <?php if ($rcek['t_jawab1'] == "TAS") {
													echo "SELECTED";
												} ?>>TAS</option>
							<option value="GKJ" <?php if ($rcek['t_jawab1'] == "GKJ") {
													echo "SELECTED";
												} ?>>GKJ</option>
							<option value="BRS" <?php if ($rcek['t_jawab1'] == "BRS") {
													echo "SELECTED";
												} ?>>BRS</option>
							<option value="CST" <?php if ($rcek['t_jawab1'] == "CST") {
													echo "SELECTED";
												} ?>>CST</option>
							<option value="YND" <?php if ($rcek['t_jawab1'] == "YND") {
													echo "SELECTED";
												} ?>>YND</option>
						</select>
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<input name="persen1" type="text" class="form-control" id="persen1" value="<?php if ($cek > 0) {
																											echo $rcek['persen1'];
																										} ?>" placeholder="0.00" style="text-align: right;">
							<span class="input-group-addon">%</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="tangggung_jawab" class="col-sm-3 control-label">Tanggung Jawab 3</label>
					<div class="col-sm-2">
						<select class="form-control select2" name="t_jawab2">
							<option value="">Pilih</option>
							<option value="MKT" <?php if ($rcek['t_jawab2'] == "MKT") {
													echo "SELECTED";
												} ?>>MKT</option>
							<option value="FIN" <?php if ($rcek['t_jawab2'] == "FIN") {
													echo "SELECTED";
												} ?>>FIN</option>
							<option value="DYE" <?php if ($rcek['t_jawab2'] == "DYE") {
													echo "SELECTED";
												} ?>>DYE</option>
							<option value="GAS" <?php if ($rcek['t_jawab'] == "GAS") {
													echo "SELECTED";
												} ?>>GAS</option>
							<option value="KNT" <?php if ($rcek['t_jawab2'] == "KNT") {
													echo "SELECTED";
												} ?>>KNT</option>
							<option value="LAB" <?php if ($rcek['t_jawab2'] == "LAB") {
													echo "SELECTED";
												} ?>>LAB</option>
							<option value="PRT" <?php if ($rcek['t_jawab2'] == "PRT") {
													echo "SELECTED";
												} ?>>PRT</option>
							<option value="KNK" <?php if ($rcek['t_jawab2'] == "KNK") {
													echo "SELECTED";
												} ?>>KNK</option>
							<option value="QCF" <?php if ($rcek['t_jawab2'] == "QCF") {
													echo "SELECTED";
												} ?>>QCF</option>
							<option value="CQA" <?php if ($rcek['t_jawab2'] == "CQA") {
													echo "SELECTED";
												} ?>>CQA</option>
							<option value="GKG" <?php if ($rcek['t_jawab2'] == "GKG") {
													echo "SELECTED";
												} ?>>GKG</option>
							<option value="PRO" <?php if ($rcek['t_jawab2'] == "PRO") {
													echo "SELECTED";
												} ?>>PRO</option>
							<option value="RMP" <?php if ($rcek['t_jawab2'] == "RMP") {
													echo "SELECTED";
												} ?>>RMP</option>
							<option value="PPC" <?php if ($rcek['t_jawab2'] == "PPC") {
													echo "SELECTED";
												} ?>>PPC</option>
							<option value="TAS" <?php if ($rcek['t_jawab2'] == "TAS") {
													echo "SELECTED";
												} ?>>TAS</option>
							<option value="GKJ" <?php if ($rcek['t_jawab2'] == "GKJ") {
													echo "SELECTED";
												} ?>>GKJ</option>
							<option value="BRS" <?php if ($rcek['t_jawab2'] == "BRS") {
													echo "SELECTED";
												} ?>>BRS</option>
							<option value="CST" <?php if ($rcek['t_jawab2'] == "CST") {
													echo "SELECTED";
												} ?>>CST</option>
							<option value="YND" <?php if ($rcek['t_jawab2'] == "YND") {
													echo "SELECTED";
												} ?>>YND</option>
						</select>
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<input name="persen2" type="text" class="form-control" id="persen2" value="<?php if ($cek > 0) {
																											echo $rcek['persen2'];
																										} ?>" placeholder="0.00" style="text-align: right;">
							<span class="input-group-addon">%</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="tangggung_jawab" class="col-sm-3 control-label">Tanggung Jawab 4</label>
					<div class="col-sm-2">
						<select class="form-control select2" name="t_jawab3">
							<option value="">Pilih</option>
							<option value="MKT" <?php if ($rcek['t_jawab3'] == "MKT") {
													echo "SELECTED";
												} ?>>MKT</option>
							<option value="FIN" <?php if ($rcek['t_jawab3'] == "FIN") {
													echo "SELECTED";
												} ?>>FIN</option>
							<option value="DYE" <?php if ($rcek['t_jawab3'] == "DYE") {
													echo "SELECTED";
												} ?>>DYE</option>
							<option value="GAS" <?php if ($rcek['t_jawab'] == "GAS") {
													echo "SELECTED";
												} ?>>GAS</option>
							<option value="KNT" <?php if ($rcek['t_jawab3'] == "KNT") {
													echo "SELECTED";
												} ?>>KNT</option>
							<option value="LAB" <?php if ($rcek['t_jawab3'] == "LAB") {
													echo "SELECTED";
												} ?>>LAB</option>
							<option value="PRT" <?php if ($rcek['t_jawab3'] == "PRT") {
													echo "SELECTED";
												} ?>>PRT</option>
							<option value="KNK" <?php if ($rcek['t_jawab3'] == "KNK") {
													echo "SELECTED";
												} ?>>KNK</option>
							<option value="QCF" <?php if ($rcek['t_jawab3'] == "QCF") {
													echo "SELECTED";
												} ?>>QCF</option>
							<option value="CQA" <?php if ($rcek['t_jawab3'] == "CQA") {
													echo "SELECTED";
												} ?>>CQA</option>
							<option value="GKG" <?php if ($rcek['t_jawab3'] == "GKG") {
													echo "SELECTED";
												} ?>>GKG</option>
							<option value="PRO" <?php if ($rcek['t_jawab3'] == "PRO") {
													echo "SELECTED";
												} ?>>PRO</option>
							<option value="RMP" <?php if ($rcek['t_jawab3'] == "RMP") {
													echo "SELECTED";
												} ?>>RMP</option>
							<option value="PPC" <?php if ($rcek['t_jawab3'] == "PPC") {
													echo "SELECTED";
												} ?>>PPC</option>
							<option value="TAS" <?php if ($rcek['t_jawab3'] == "TAS") {
													echo "SELECTED";
												} ?>>TAS</option>
							<option value="GKJ" <?php if ($rcek['t_jawab3'] == "GKJ") {
													echo "SELECTED";
												} ?>>GKJ</option>
							<option value="BRS" <?php if ($rcek['t_jawab3'] == "BRS") {
													echo "SELECTED";
												} ?>>BRS</option>
							<option value="CST" <?php if ($rcek['t_jawab3'] == "CST") {
													echo "SELECTED";
												} ?>>CST</option>
							<option value="YND" <?php if ($rcek['t_jawab3'] == "YND") {
													echo "SELECTED";
												} ?>>YND</option>
						</select>
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<input name="persen3" type="text" class="form-control" id="persen3" value="<?php if ($cek > 0) {
																											echo $rcek['persen3'];
																										} ?>" placeholder="0.00" style="text-align: right;">
							<span class="input-group-addon">%</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="tangggung_jawab" class="col-sm-3 control-label">Tanggung Jawab 5</label>
					<div class="col-sm-2">
						<select class="form-control select2" name="t_jawab4">
							<option value="">Pilih</option>
							<option value="MKT" <?php if ($rcek['t_jawab4'] == "MKT") {
													echo "SELECTED";
												} ?>>MKT</option>
							<option value="FIN" <?php if ($rcek['t_jawab4'] == "FIN") {
													echo "SELECTED";
												} ?>>FIN</option>
							<option value="DYE" <?php if ($rcek['t_jawab4'] == "DYE") {
													echo "SELECTED";
												} ?>>DYE</option>
							<option value="GAS" <?php if ($rcek['t_jawab'] == "GAS") {
													echo "SELECTED";
												} ?>>GAS</option>
							<option value="KNT" <?php if ($rcek['t_jawab4'] == "KNT") {
													echo "SELECTED";
												} ?>>KNT</option>
							<option value="LAB" <?php if ($rcek['t_jawab4'] == "LAB") {
													echo "SELECTED";
												} ?>>LAB</option>
							<option value="PRT" <?php if ($rcek['t_jawab4'] == "PRT") {
													echo "SELECTED";
												} ?>>PRT</option>
							<option value="KNK" <?php if ($rcek['t_jawab4'] == "KNK") {
													echo "SELECTED";
												} ?>>KNK</option>
							<option value="QCF" <?php if ($rcek['t_jawab4'] == "QCF") {
													echo "SELECTED";
												} ?>>QCF</option>
							<option value="CQA" <?php if ($rcek['t_jawab4'] == "CQA") {
													echo "SELECTED";
												} ?>>CQA</option>
							<option value="GKG" <?php if ($rcek['t_jawab4'] == "GKG") {
													echo "SELECTED";
												} ?>>GKG</option>
							<option value="PRO" <?php if ($rcek['t_jawab4'] == "PRO") {
													echo "SELECTED";
												} ?>>PRO</option>
							<option value="RMP" <?php if ($rcek['t_jawab4'] == "RMP") {
													echo "SELECTED";
												} ?>>RMP</option>
							<option value="PPC" <?php if ($rcek['t_jawab4'] == "PPC") {
													echo "SELECTED";
												} ?>>PPC</option>
							<option value="TAS" <?php if ($rcek['t_jawab4'] == "TAS") {
													echo "SELECTED";
												} ?>>TAS</option>
							<option value="GKJ" <?php if ($rcek['t_jawab4'] == "GKJ") {
													echo "SELECTED";
												} ?>>GKJ</option>
							<option value="BRS" <?php if ($rcek['t_jawab4'] == "BRS") {
													echo "SELECTED";
												} ?>>BRS</option>
							<option value="CST" <?php if ($rcek['t_jawab4'] == "CST") {
													echo "SELECTED";
												} ?>>CST</option>
							<option value="YND" <?php if ($rcek['t_jawab4'] == "YND") {
													echo "SELECTED";
												} ?>>YND</option>
						</select>
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<input name="persen4" type="text" class="form-control" id="persen4" value="<?php if ($cek > 0) {
																											echo $rcek['persen4'];
																										} ?>" placeholder="0.00" style="text-align: right;">
							<span class="input-group-addon">%</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="penyebab" class="col-sm-3 control-label">Penyebab</label>
					<?php
					$dtArr = $rcek['sebab'];
					$data = explode(",", $dtArr);
					?>
					<div class="col-sm-8">
						<label><input type="checkbox" class="minimal" name="sebab[]" value="Man" <?php if (in_array("Man", $data)) {
																										echo "checked";
																									} ?>> Man &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						</label>
						<label><input type="checkbox" class="minimal" name="sebab[]" value="Methode" <?php if (in_array("Methode", $data)) {
																											echo "checked";
																										} ?>> Methode &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						</label>
						<label><input type="checkbox" class="minimal" name="sebab[]" value="Machine" <?php if (in_array("Machine", $data)) {
																											echo "checked";
																										} ?>> Machine &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						</label>
						<label><input type="checkbox" class="minimal" name="sebab[]" value="Material" <?php if (in_array("Material", $data)) {
																											echo "checked";
																										} ?>> Material &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						</label>
						<label><input type="checkbox" class="minimal" name="sebab[]" value="Environment" <?php if (in_array("Environment", $data)) {
																												echo "checked";
																											} ?>> Environment
						</label>
					</div>
				</div>

				<div class="form-group">
					<label for="masalah" class="col-sm-3 control-label">Masalah</label>
					<div class="col-sm-8">
						<textarea name="masalah" rows="5" class="form-control" id="masalah" placeholder="Masalah"><?php if ($cek > 0) {
																														echo $rcek['masalah'];
																													} ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="ket" class="col-sm-3 control-label">Keterangan</label>
					<div class="col-sm-8">
						<textarea name="ket" rows="5" class="form-control" id="ket" placeholder="Keterangan"><?php if ($cek > 0) {
																													echo $rcek['ket'];
																												} ?></textarea>
					</div>
				</div>
			</div>

		</div>
		<div class="box-footer">
			<?php if (($_GET['nokk'] != "" or $_GET['demand'] != "") and $cek == 0) { ?>
				<button type="submit" class="btn btn-primary pull-right" name="save" value="save"><i class="fa fa-save"></i> Simpan</button>
			<?php } ?>
		</div>
		<!-- /.box-footer -->
	</div>
</form>


</div>
</div>
</div>
</div>
<?php
if ($_POST['save'] == "save") {
	$warna = str_replace("'", "''", $_POST['warna']);
	$nowarna = str_replace("'", "''", $_POST['no_warna']);
	$jns = str_replace("'", "''", $_POST['jns_kain']);
	$po = str_replace("'", "''", $_POST['no_po']);
	$masalah = str_replace("'", "''", $_POST['masalah']);
	$ket = str_replace("'", "''", $_POST['ket']);
	$styl = str_replace("'", "''", $_POST['styl']);
	$lot = trim($_POST['lot']);
	if ($_POST['sts'] == "1") {
		$sts = "1";
	} else {
		$sts = "0";
	}
	$checkbox1 = $_POST['sebab'];
	$chkp = "";
	foreach ($checkbox1 as $chk1) {
		$chkp .= $chk1 . ",";
	}

	// $sqlData = mysqli_query($cona, "INSERT INTO tbl_gantikain SET 
	// 	  nokk='$_POST[nokk]',
	// 	  nodemand='$_POST[demand]',
	// 	  langganan='$_POST[pelanggan]',
	// 	  no_order='$_POST[no_order]',
	// 	  no_hanger='$_POST[no_hanger]',
	// 	  no_item='$_POST[no_item]',
	// 	  po='$po',
	// 	  jenis_kain='$jns',
	// 	  lebar='$_POST[lebar]',
	// 	  gramasi='$_POST[grms]',
	// 	  lot='$lot',
	// 	  tgl_delivery='$_POST[tgl_delivery]',
	// 	  warna='$warna',
	// 	  no_warna='$nowarna',
	// 	  masalah='$masalah',
	// 	  sebab='$chkp',
	// 	  qty_order='$_POST[qty_order]',
	// 	  t_jawab='$_POST[t_jawab]',
	// 	  t_jawab1='$_POST[t_jawab1]',
	// 	  t_jawab2='$_POST[t_jawab2]',
	// 	  t_jawab3='$_POST[t_jawab3]',
	// 	  t_jawab4='$_POST[t_jawab4]',
	// 	  persen='$_POST[persen]',
	// 	  persen1='$_POST[persen1]',
	// 	  persen2='$_POST[persen2]',
	// 	  persen3='$_POST[persen3]',
	// 	  persen4='$_POST[persen4]',
	// 	  satuan_o='$_POST[satuan_o]',
	// 	  personil='$_POST[personil]',
	// 	  shift='$_POST[shift]',
	// 	  penyebab='$_POST[penyebab]',
	// 	  sts='$sts',
	// 	  ket='$ket',
	// 	  kategori='$_POST[kategori]',
	// 	  dept='$_SESSION[dept10]',
	// 	  tgl_buat=now(),
	// 	  tgl_update=now()");

	$sqlData = sqlsrv_query($cona, "
    INSERT INTO db_adm.tbl_gantikain (
        nokk, nodemand, langganan, no_order, no_hanger, no_item,
        po, jenis_kain, lebar, gramasi, lot, tgl_delivery,
        warna, no_warna, masalah, sebab, qty_order,
        t_jawab, t_jawab1, t_jawab2, t_jawab3, t_jawab4,
        persen, persen1, persen2, persen3, persen4,
        satuan_o, personil, shift, penyebab, sts, ket,
        kategori, dept, tgl_buat, tgl_update
    ) VALUES (
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, GETDATE(), GETDATE()
    )
", [
		$_POST['nokk'] ?? '',
		$_POST['demand'] ?? '',
		$_POST['pelanggan'] ?? '',
		$_POST['no_order'] ?? '',
		$_POST['no_hanger'] ?? '',
		trim($_POST['no_item']) ?? '',

		$po ?? '',
		$jns ?? '',
		(float) $_POST['lebar'],
		(float) $_POST['grms'],
		$lot ?? '',
		$_POST['tgl_delivery'] ?? '',

		$warna ?? '',
		$nowarna ?? '',
		$masalah ?? '',
		$chkp ?? '',
		(float) $_POST['qty_order'],

		$_POST['t_jawab'] ?? '',
		$_POST['t_jawab1'] ?? '',
		$_POST['t_jawab2'] ?? '',
		$_POST['t_jawab3'] ?? '',
		$_POST['t_jawab4'] ?? '',

		(float) $_POST['persen'],
		(float) $_POST['persen1'],
		(float) $_POST['persen2'],
		(float) $_POST['persen3'],
		(float) $_POST['persen4'],

		$_POST['satuan_o'] ?? '',
		$_POST['personil'] ?? '',
		$_POST['shift'] ?? '',
		$_POST['penyebab'] ?? '',
		$sts ?? '',
		$ket ?? '',

		$_POST['kategori'] ?? '',
		$_SESSION['dept10'] ?? ''
	]);

	if ($sqlData === false) {
		die(print_r(sqlsrv_errors(), true));
	}

	if ($sqlData) {

		echo "<script>swal({
  title: 'Data Tersimpan',   
  text: 'Klik Ok untuk input data kembali',
  type: 'success',
  }).then((result) => {
  if (result.value) {
      window.location.href='index1.php?p=Input-Bon';
	 
  }
});</script>";
	}
}

?>