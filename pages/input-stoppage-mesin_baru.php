<?php
	ini_set("error_reporting", 1);
	session_start();
	include "koneksi.php";
	if($_SESSION['dept10']=="CSR"){
	$demand = $_GET['demand'];	
	$child = $r['ChildLevel'];
	if ($demand != "") {		
	}	
		$sqlCek = mysqli_query($cona, "SELECT * FROM tbl_stoppage WHERE nodemand='$demand' and nodemand<>'' ORDER BY id DESC LIMIT 1");
		$cek = mysqli_num_rows($sqlCek);
		$rcek = mysqli_fetch_array($sqlCek);	
		
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
												LOT
											FROM 
												ITXVIEWKK 
											WHERE 
												DEAMAND = '$demand'");	
	}
	if($_SESSION['dept10']!="CSR"){
	$nokk = $_GET['nokk'];	
	$child = $r['ChildLevel'];
	if ($nokk != "") {		
	}

	$sqlCek = mysqli_query($cona, "SELECT * FROM tbl_stoppage WHERE nokk='$nokk' ORDER BY id DESC LIMIT 1");
	$cek = mysqli_num_rows($sqlCek);
	$rcek = mysqli_fetch_array($sqlCek);
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
											LOT
										FROM 
											ITXVIEWKK 
										WHERE 
											PRODUCTIONORDERCODE = '$nokk'");	
	}
	
	
		$dt_ITXVIEWKK	= db2_fetch_assoc($sql_ITXVIEWKK);

		if(!empty($_GET['demand'])){
		$nokk=$dt_ITXVIEWKK['PRODUCTIONORDERCODE'];	
		}else{
		$nokk= $_GET['nokk'];
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

		$sql_lebargramasi	= db2_exec($conn2, "SELECT CAST(i.LEBAR AS INT) AS LEBAR,
											CASE
												WHEN i2.GRAMASI_KFF IS NULL THEN CAST(i2.GRAMASI_FKF AS INT)
												ELSE CAST(i2.GRAMASI_KFF AS INT)
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
<style>
@keyframes blink {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        opacity: 1;
    }
}

.blink {
    animation: blink 1s infinite; /* 1s duration, infinitely repeating */
}

</style>
<script>
	function aktif() {
		if (document.forms['form1']['non_kk'].checked == true && document.forms['form1']['non_kk_stop'].checked == false) {
			document.form1.nokk.setAttribute("readonly", true);
			document.form1.nokk.removeAttribute("required");
			document.form1.nokk.value = "";
			//document.form1.nokk.focus();
			document.form1.mesin.value = "";
			document.form1.demand.setAttribute("readonly", true);
			document.form1.demand.removeAttribute("required");
			document.form1.demand.value = "";
			document.form1.no_order.setAttribute("readonly", true);
			document.form1.no_order.removeAttribute("required");
			document.form1.no_order.value = "";
			document.form1.pelanggan.setAttribute("readonly", true);
			document.form1.pelanggan.removeAttribute("required");
			document.form1.pelanggan.value = "";
			document.form1.buyer.setAttribute("readonly", true);
			document.form1.buyer.removeAttribute("required");
			document.form1.buyer.value = "";
			document.form1.no_po.setAttribute("readonly", true);
			document.form1.no_po.removeAttribute("required");
			document.form1.no_po.value = "";
			document.form1.no_hanger.setAttribute("readonly", true);
			document.form1.no_hanger.removeAttribute("required");
			document.form1.no_hanger.value = "";
			document.form1.no_item.setAttribute("readonly", true);
			document.form1.no_item.removeAttribute("required");
			document.form1.no_item.value = "";
			document.form1.jns_kain.setAttribute("readonly", true);
			document.form1.jns_kain.removeAttribute("required");
			document.form1.jns_kain.value = "";
			document.form1.styl.setAttribute("readonly", true);
			document.form1.styl.removeAttribute("required");
			document.form1.styl.value = "";
			document.form1.lebar.setAttribute("readonly", true);
			document.form1.lebar.removeAttribute("required");
			document.form1.lebar.value = "";
			document.form1.grms.setAttribute("readonly", true);
			document.form1.grms.removeAttribute("required");
			document.form1.grms.value = "";
			document.form1.datepicker.setAttribute("readonly", true);
			document.form1.datepicker.removeAttribute("required");
			document.form1.datepicker.value = "";
			document.form1.warna.setAttribute("readonly", true);
			document.form1.warna.removeAttribute("required");
			document.form1.warna.value = "";
			document.form1.no_warna.setAttribute("readonly", true);
			document.form1.no_warna.removeAttribute("required");
			document.form1.no_warna.value = "";
			document.form1.lot.setAttribute("readonly", true);
			document.form1.lot.removeAttribute("required");
			document.form1.lot.value = "";
			document.form1.qty_order.setAttribute("readonly", true);
			document.form1.qty_order.removeAttribute("required");
			document.form1.qty_order.value = "";
			document.form1.mulai_jam.setAttribute("readonly", true);
			document.form1.mulai_jam.removeAttribute("required");
			document.form1.mulai_jam.value = "";
			document.form1.mulai_tgl.setAttribute("readonly", true);
			document.form1.mulai_tgl.removeAttribute("required");
			document.form1.mulai_tgl.value = "";
			document.form1.selesai_jam.setAttribute("readonly", true);
			document.form1.selesai_jam.removeAttribute("required");
			document.form1.selesai_jam.value = "";
			document.form1.selesai_tgl.setAttribute("readonly", true);
			document.form1.selesai_tgl.removeAttribute("required");
			document.form1.selesai_tgl.value = "";
			document.form1.operator.setAttribute("readonly", true);
			document.form1.operator.removeAttribute("required");
			document.form1.operator.value = "";
			document.form1.kode_operator.setAttribute("readonly", true);
			document.form1.kode_operator.removeAttribute("required");
			document.form1.kode_operator.value = "";
			document.form1.durasi_stop_jm.setAttribute("readonly", true);
			document.form1.durasi_stop_jm.removeAttribute("required");
			
			document.form1.non_kk_stop.disabled = false;	
		} else if (document.forms['form1']['non_kk'].checked == true && document.forms['form1']['non_kk_stop'].checked == true) {			
			document.form1.nokk.removeAttribute("readonly");
			document.form1.nokk.setAttribute("required", true);
			document.form1.nokk.focus();
		} else {
			document.form1.nokk.value = "";
			document.form1.nokk.removeAttribute("readonly");
			document.form1.nokk.setAttribute("required", true);
			document.form1.nokk.focus();
			document.form1.demand.removeAttribute("readonly");
			document.form1.demand.setAttribute("required", true);
			document.form1.no_order.removeAttribute("readonly");
			document.form1.no_order.setAttribute("required", true);
			document.form1.pelanggan.removeAttribute("readonly");
			document.form1.pelanggan.setAttribute("required", true);
			document.form1.buyer.removeAttribute("readonly");
			document.form1.buyer.setAttribute("required", true);
			document.form1.no_po.removeAttribute("readonly");
			document.form1.no_po.setAttribute("required", true);
			document.form1.no_hanger.removeAttribute("readonly");
			document.form1.no_hanger.setAttribute("required", true);
			document.form1.no_item.removeAttribute("readonly");
			document.form1.no_item.setAttribute("required", true);
			document.form1.jns_kain.removeAttribute("readonly");
			document.form1.jns_kain.setAttribute("required", true);
			document.form1.styl.removeAttribute("readonly");
			document.form1.styl.setAttribute("required", true);
			document.form1.lebar.removeAttribute("readonly");
			document.form1.lebar.setAttribute("required", true);
			document.form1.grms.removeAttribute("readonly");
			document.form1.grms.setAttribute("required", true);
			document.form1.datepicker.removeAttribute("readonly");
			document.form1.datepicker.setAttribute("required", true);
			document.form1.warna.removeAttribute("readonly");
			document.form1.warna.setAttribute("required", true);
			document.form1.no_warna.removeAttribute("readonly");
			document.form1.no_warna.setAttribute("required", true);
			document.form1.lot.removeAttribute("readonly");
			document.form1.lot.setAttribute("required", true);
			document.form1.qty_order.removeAttribute("readonly");
			document.form1.qty_order.setAttribute("required", true);
			document.form1.mulai_jam.removeAttribute("readonly");
			document.form1.mulai_jam.setAttribute("required", true);
			document.form1.mulai_tgl.removeAttribute("readonly");
			document.form1.mulai_tgl.setAttribute("required", true);
			document.form1.selesai_jam.removeAttribute("readonly");
			document.form1.selesai_jam.setAttribute("required", true);
			document.form1.selesai_tgl.removeAttribute("readonly");
			document.form1.selesai_tgl.setAttribute("required", true);
			document.form1.operator.removeAttribute("readonly");
			document.form1.operator.setAttribute("required", true);
			document.form1.kode_operator.removeAttribute("readonly");
			document.form1.kode_operator.setAttribute("required", true);
			document.form1.durasi_stop_jm.removeAttribute("readonly");
			document.form1.durasi_stop_jm.setAttribute("required", true);
			
			document.form1.non_kk_stop.disabled = true;
			document.form1.non_kk_stop.checked = false;
			document.form1.non_kk_stop.value = "";
		}
	}
</script>	
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
				<div class="form-group">
					<label for="nokk" class="col-sm-3 control-label">Production Order</label>
					<div class="col-sm-4">
						<input name="nokk" type="text" class="form-control" id="nokk" 
							   onchange="handleNokkChange()" 
							   value="<?php echo $_GET['nokk']; ?>" placeholder="Production Order" required>
					</div>
					<div class="col-sm-3">
						<input type="checkbox" name="non_kk" id="non_kk" onClick="aktif();" value="1"> Tidak Ada Kartu <br>
						<input type="checkbox" name="non_kk_stop" id="non_kk_stop" onClick="aktif();" value="1" disabled> Stop Mesin Tidak Ada Kartu
					</div>
				</div>
				<div class="form-group">
					<label for="demand" class="col-sm-3 control-label">Production Demand</label>
					<div class="col-sm-8">
						<input name="demand" type="text" class="form-control" id="demand" value="<?= ($cek > 0) ? $rcek['nodemand'] : $dt_demand['DEMAND']; ?>" placeholder="Production Demand">
					</div>
				</div>
				<div class="form-group">
					<label for="no_order" class="col-sm-3 control-label">No Order</label>
					<div class="col-sm-4">
						<input name="no_order" type="text" class="form-control" id="no_order" value="<?= ($cek > 0) ? $rcek['no_order'] : $dt_ITXVIEWKK['PROJECTCODE']; ?>" placeholder="No Order" required>
					</div>
				</div>
				<div class="form-group">
					<label for="pelanggan" class="col-sm-3 control-label">Pelanggan</label>
					<div class="col-sm-8">
						<input name="pelanggan" type="text" class="form-control" id="pelanggan" value="<?= ($cek > 0) ? $rcek['langganan'] : $dt_pelanggan_buyer['PELANGGAN']; ?>" placeholder="Pelanggan">
					</div>
				</div>
				<div class="form-group">
					<label for="buyer" class="col-sm-3 control-label">Buyer</label>
					<div class="col-sm-8">
						<input name="buyer" type="text" class="form-control" id="buyer" value="<?= ($cek > 0) ? $rcek['buyer'] : $dt_pelanggan_buyer['BUYER']; ?>" placeholder="Buyer">
					</div>
				</div>
				<div class="form-group">
					<label for="no_po" class="col-sm-3 control-label">PO</label>
					<div class="col-sm-5">
						<input name="no_po" type="text" class="form-control" id="no_po" value="<?= ($cek > 0) ? $rcek['po'] : $dt_po['NO_PO']; ?>" placeholder="PO">
					</div>
				</div>
				<div class="form-group">
					<label for="no_hanger" class="col-sm-3 control-label">No Hanger / No Item</label>
					<div class="col-sm-3">
						<input name="no_hanger" type="text" class="form-control" id="no_hanger" value="<?= ($cek > 0) ? $rcek['no_hanger'] : $dt_ITXVIEWKK['NO_HANGER']; ?>" placeholder="No Hanger">
					</div>						
					<div class="col-sm-3">
						<input name="no_item" type="text" class="form-control" id="no_item" value="<?= ($cek > 0) ? $rcek['no_item'] : $dt_item['EXTERNALITEMCODE'] ?>" placeholder="No Item">
				    </div>
				</div>
				<div class="form-group">
					<label for="jns_kain" class="col-sm-3 control-label">Jenis Kain</label>
					<div class="col-sm-8">
						<textarea name="jns_kain" class="form-control" id="jns_kain" placeholder="Jenis Kain"><?= ($cek > 0) ? $rcek['jenis_kain'] :  $dt_ITXVIEWKK['ITEMDESCRIPTION'] ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="styl" class="col-sm-3 control-label">Style</label>
					<div class="col-sm-8">
						<input name="styl" type="text" class="form-control" id="styl" value="<?php ($cek > 0) ?  $rcek['styl']: ''; ?>" placeholder="Style">
					</div>
				</div>
				<div class="form-group">
					<label for="l_g" class="col-sm-3 control-label">Lebar X Gramasi</label>
					<div class="col-sm-2">
						<input name="lebar" type="text" class="form-control" id="lebar" value="<?= ($cek > 0) ? $rcek['lebar'] : $dt_lg['LEBAR']; ?>" placeholder="0" required>
					</div>
					<div class="col-sm-2">
						<input name="grms" type="text" class="form-control" id="grms" value="<?= ($cek > 0) ? $rcek['gramasi'] : $dt_lg['GRAMASI']; ?>" placeholder="0" required>
					</div>
				</div>
				<div class="form-group">
					<label for="tgl_delivery" class="col-sm-3 control-label">Tgl Delivery</label>
					<div class="col-sm-3">						
					<div class="input-group date" id="tgl_d">
						<div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
						<input name="tgl_delivery" type="text" class="form-control" id="datepicker" placeholder="Tanggal Delivery" value="<?= ($cek > 0) ? $rcek['tgl_delivery'] : $dt_ITXVIEWKK['DELIVERYDATE']; ?>" autocomplete="off"/>
					</div>
					</div>
					<!-- /.input group -->
				</div>				
			</div>
			<!-- col -->
			<div class="col-md-6">
				<div class="form-group">
					<label for="warna" class="col-sm-3 control-label">Warna</label>
					<div class="col-sm-8">
						<textarea name="warna" class="form-control" id="warna" placeholder="Warna"><?= ($cek > 0) ? $rcek['warna'] : $dt_warna['WARNA']; ?></textarea>
					</div>
				</div>
				<div class="form-group">

					<label for="no_warna" class="col-sm-3 control-label">No Warna</label>
					<div class="col-sm-8">
						<textarea name="no_warna" class="form-control" id="no_warna" placeholder="No Warna"><?= ($cek > 0) ? $rcek['no_warna'] : $dt_ITXVIEWKK['NO_WARNA']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="lot" class="col-sm-3 control-label">Lot</label>
					<div class="col-sm-3">
						<input name="lot" type="text" class="form-control" id="lot" value="<?= ($cek > 0) ? $rcek['lot'] : $dt_ITXVIEWKK['LOT']; ?>" placeholder="Lot">
					</div>
				</div>
				<div class="form-group">
					<label for="proses" class="col-sm-3 control-label">Qty Order</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input name="qty_order" type="text" class="form-control" id="qty_order" value="<?= ($cek > 0) ? $rcek['qty_order'] : $dt_qtyorder['QTY_ORDER']; ?>" placeholder="0.00" style="text-align: right;" required>
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
					<label for="operation" class="col-sm-3 control-label">Operation</label>
					<div class="col-sm-4">
						<select class="form-control select2" name="operation" id="operation" onchange="fetchOperationDetails()">
							<option value="">Pilih</option>
						<?php
							$sql_operation	= db2_exec($conn2, "SELECT
												ps.OPERATIONCODE, o.LONGDESCRIPTION 
											FROM
												VIEWPRODUCTIONDEMANDSTEP ps
											LEFT OUTER JOIN OPERATION o ON o.code =ps.OPERATIONCODE 
											WHERE
												ps.PRODUCTIONORDERCODE = '".$nokk."'												
											");
							while($dt_operation   = db2_fetch_assoc($sql_operation)){	
							?>
							<option value="<?php echo $dt_operation['OPERATIONCODE'];?>"><?php echo $dt_operation['OPERATIONCODE']." || ".$dt_operation['LONGDESCRIPTION'];?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="mulai_proses" class="col-sm-3 control-label">Mulai Proses</label>
					<div class="col-sm-2">
						<input name="mulai_jam" type="text" class="form-control" id="mulai_jam" value="" placeholder="00:00">
					</div>
					<div class="col-sm-2">
						<input name="mulai_tgl" type="text" class="form-control" id="mulai_tgl" value="" placeholder="tgl mulai">
					</div>
				</div>			
				<div class="form-group">
					<label for="selesai_proses" class="col-sm-3 control-label">Selesai Proses</label>
					<div class="col-sm-2">
						<input name="selesai_jam" type="text" class="form-control" id="selesai_jam" value="" placeholder="00:00">
					</div>
					<div class="col-sm-2">
						<input name="selesai_tgl" type="text" class="form-control" id="selesai_tgl" value="" placeholder="tgl selesai">
					</div>
				</div>
				<div class="form-group">
					<label for="mesin" class="col-sm-3 control-label">Mesin</label>
					<div class="col-sm-3">
						<input name="mesin" type="text" class="form-control" maxlength="8" id="mesin" value="" placeholder="mesin">
					</div>
				</div>
				<div class="form-group">
					<label for="operator" class="col-sm-3 control-label">Operator</label>
					<div class="col-sm-3">
						<input name="operator" type="text" class="form-control" id="operator" value="" placeholder="operator">
					</div>
					<div class="col-sm-3">
						<input name="kode_operator" type="text" class="form-control" id="kode_operator" value="" placeholder="kode operator">
					</div>
				</div>
				<div class="form-group">
					<label for="kode_stop" class="col-sm-3 control-label">Kode Stop Mesin</label>
					<div class="col-sm-3">
						<select class="form-control select2" name="kode_stop" id="kode_stop">
							<option value="">Pilih</option>
							<option value="LM">LM || Listrik Mati</option>
							<option value="KM">KM || Kerusakan Mesin</option>
							<option value="KO">KO || Kurang Order</option>
							<option value="AP">AP || Abnormal Produk</option>
							<option value="PA">PA || Pelaksanaan Apel</option>
							<option value="PM">PM || Pemeliharaan Mesin</option>
							<option value="GT">GT || Gangguan Teknis </option>
							<option value="TG">TG || Tunggu </option>
						</select>
					</div>
					<div class="col-sm-2">
						<input name="durasi_stop_jm" type="text" class="form-control" id="durasi_stop_jm" value="" placeholder="durasi jam menit">
					</div>
					<div class="col-sm-2">
						<input name="durasi_stop" type="hidden" class="form-control" id="durasi_stop" value="" placeholder="durasi jam menit">
					</div>
					<div class="col-sm-2">
						<input name="durasijammenit" type="hidden" class="form-control" id="durasijammenit" value="" placeholder="durasi jam menit">
					</div>
					<div class="col-sm-2">
						<input name="durasi" type="hidden" class="form-control" id="durasi" value="" placeholder="durasi per jam">
					</div>					
				</div>
				<div id="start_stop_input" style="display: none;">
				<div class="form-group">
					<label for="stop_mulai_proses" class="col-sm-3 control-label">Mulai Stop</label>
					<div class="col-sm-2">
						<input name="stop_mulai_jam" type="text" class="form-control" id="stop_mulai_jam" value="" placeholder="00:00" readonly>
						
					</div>
					<div class="col-sm-2">
						<input name="stop_mulai_tgl" type="text" class="form-control" id="stop_mulai_tgl" value="" placeholder="tgl stop mulai" readonly>
						
					</div>
				</div>			
				<div class="form-group" >
					<label for="stop_selesai_proses" class="col-sm-3 control-label">Selesai Stop</label>
					<div class="col-sm-2">
						<input name="stop_selesai_jam" type="text" class="form-control" id="stop_selesai_jam" value="" placeholder="00:00" readonly>
					</div>
					<div class="col-sm-2">
						<input name="stop_selesai_tgl" type="text" class="form-control" id="stop_selesai_tgl" value="" placeholder="tgl stop selesai" readonly>
					</div>
				</div>
			</div>
			</div>
		</div>
		<div class="box-footer">
			<?php if (($_GET['nokk'] != "" or $_GET['demand'] != "") and $cek == 0) { ?>
<!--				<button type="submit" class="btn btn-primary pull-right" name="save" value="save"><i class="fa fa-save"></i> Simpan</button>-->
			<?php } ?>
			<div id="start_stop_buttons" style="display: none;">
			<button type="submit" class="btn btn-success pull-left" name="btnStart" value="Start" id="start_button"><i class="fa fa-play-circle"> </i> Start</button>
			<button type="submit" class="btn btn-danger pull-right" name="btnStop" value="Stop" id="stop_button"><i class="fa fa-stop-circle"> </i> Stop</button>
			</div>
		</div>
		<!-- /.box-footer -->
				
	</div>
</form>
</div>
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function fetchOperationDetails() {
        var operationCode = $('#operation').val();
        var noKK = $('#nokk').val(); // Pastikan id ini benar ada di form HTML

        if (operationCode && noKK) { // Pastikan kedua nilai ada sebelum mengirim AJAX
            $.ajax({
                url: 'pages/ajax/fetch_operation_details.php', 
                type: 'POST',
                data: { operationCode: operationCode, noKK: noKK },
                dataType: 'json', // Memastikan server mengirim JSON
                success: function(response) {
                    try {
                        if (response.error) {
                            alert('Error: ' + response.error);
							$('#mulai_jam').val('');
							$('#mulai_tgl').val('');
							$('#selesai_jam').val('');
							$('#selesai_tgl').val('');
							$('#mesin').val('');
							$('#operator').val('');
							$('#kode_operator').val('');
							$('#durasi').val('');
                        } else {
                            $('#mulai_jam').val(response.PROGRESSSTARTPROCESSTIME || '');
                            $('#mulai_tgl').val(response.PROGRESSSTARTPROCESSDATE || '');
                            $('#selesai_jam').val(response.PROGRESSENDTIME || '');
                            $('#selesai_tgl').val(response.PROGRESSENDDATE || '');
							$('#operator').val(response.NAMA || '');
							$('#kode_operator').val(response.OPERATORCODE || '');
                            $('#mesin').val(response.MACHINECODE || '');

                            // Panggil fungsi hitung durasi
                            calculateDuration();
                        }
                    } catch (e) {
                        console.error('JSON Parsing Error:', e);
                        alert('Terjadi kesalahan dalam mengambil data.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    alert('Gagal mengambil data operasi.');
                }
            });
        } else {
            // Kosongkan input jika tidak ada yang dipilih
            $('#mulai_jam').val('');
            $('#mulai_tgl').val('');
            $('#selesai_jam').val('');
            $('#selesai_tgl').val('');
            $('#mesin').val('');
			$('#operator').val('');
			$('#kode_operator').val('');
            $('#durasi').val('');
        }
    }

    function calculateDuration() {
        var mulaiJam = $('#mulai_jam').val();
        var mulaiTgl = $('#mulai_tgl').val();
        var selesaiJam = $('#selesai_jam').val();
        var selesaiTgl = $('#selesai_tgl').val();
		
		var SmulaiJam = $('#stop_mulai_jam').val();
        var SmulaiTgl = $('#stop_mulai_tgl').val();
        var SselesaiJam = $('#stop_selesai_jam').val();
        var SselesaiTgl = $('#stop_selesai_tgl').val();

        if (mulaiJam && mulaiTgl && selesaiJam && selesaiTgl) {
            var startDateTime = new Date(mulaiTgl + ' ' + mulaiJam);
            var endDateTime = new Date(selesaiTgl + ' ' + selesaiJam);

            if (startDateTime > endDateTime) {
                alert("Waktu selesai harus lebih besar dari waktu mulai!");
                $('#durasi').val('');
                return;
            }
			

            var durationMs = endDateTime - startDateTime;
            var durationHours = durationMs / (1000 * 60 * 60); // Konversi ms ke jam
			var durationMinutes = Math.floor(durationMs / (1000 * 60)); // Konversi ms ke menit
			var hours = Math.floor(durationMinutes / 60); // Ambil jam
			var minutes = durationMinutes % 60; // Ambil sisa menit
			
			

            // Format hasil ke 2 angka desimal (contoh: 4.25 jam)
            var durationFormatted = durationHours.toFixed(2);
			// Format hasil dalam jam dan menit
        	var durationJamMenitFormatted = hours + ' Jam ' + minutes + ' Menit';

            $('#durasi').val(durationFormatted);
			$('#durasijammenit').val(durationJamMenitFormatted);
        } else {
            $('#durasi').val('');
			$('#durasijammenit').val('');
        }
		
		if (SmulaiJam && SmulaiTgl && SselesaiJam && SselesaiTgl) {
            var SstartDateTime = new Date(SmulaiTgl + ' ' + SmulaiJam);
            var SendDateTime = new Date(SselesaiTgl + ' ' + SselesaiJam);

            if (SstartDateTime > SendDateTime) {
                alert("Waktu selesai Stop harus lebih besar dari waktu mulai!");
                $('#durasi_stop').val('');
                return;
            }
			var durationSMs = SendDateTime - SstartDateTime;
            var durationSHours = durationSMs / (1000 * 60 * 60); // Konversi ms ke jam
			var durationSMinutes = Math.floor(durationSMs / (1000 * 60)); // Konversi ms ke menit
			var hoursS = Math.floor(durationSMinutes / 60); // Ambil jam
			var minutesS = durationSMinutes % 60; // Ambil sisa menit
			
			var durationJamStopFormatted = durationSHours.toFixed(2);
			// Format hasil dalam jam dan menit
        	var durationJamMenitStopFormatted = hoursS + ' Jam ' + minutesS + ' Menit';
			
			$('#durasi_stop').val(durationJamStopFormatted);
			$('#durasi_stop_jm').val(durationJamMenitStopFormatted);
			
		} else {
			$('#durasi_stop').val('');
			$('#durasi_stop_jm').val('');
        }	
    }
	function handleNokkChange() {
    // cek apakah checkbox 'non_kk' dicentang
    var isNonKK = document.getElementById('non_kk').checked;
	var isNonKK_Stop = document.getElementById('non_kk_stop').checked;	

    if (!isNonKK) {
        var nokk = document.getElementById('nokk').value;
        window.location = '?p=Input-Stoppage-Mesin&nokk=' + nokk;
    }
    // jika dicentang, tidak melakukan apa-apa
}
</script>
<script>
$(document).ready(function() {
    // Function to update button states
    function updateButtonStates() {
        var selectedValue = $('#kode_stop').val();
        var stopMJamValue = $('#stop_mulai_jam').val();
        var stopMTglValue = $('#stop_mulai_tgl').val();
		var stopSJamValue = $('#stop_selesai_jam').val();
        var stopSTglValue = $('#stop_selesai_tgl').val();

        if (selectedValue !== "") {
            $('#start_stop_buttons').show(); // Show buttons if a selection is made
			$('#start_stop_input').show();
			
			if (stopMJamValue !== "" && stopMTglValue !== "" && stopSJamValue !== "" && stopSTglValue !== "") {
                // Enable and blink the "Start" button
                $('#start_button').prop('disabled', true).removeClass('blink');
                $('#stop_button').prop('disabled', true).removeClass('blink'); // Ensure Stop is not blinking
            }
            else
            if (stopMJamValue !== "" && stopMTglValue !== "") {
                // Enable and blink the "Start" button
                $('#start_button').prop('disabled', true).removeClass('blink');
                $('#stop_button').prop('disabled', false).addClass('blink'); // Ensure Stop is not blinking
            } else {
                // Enable and blink the "Stop" button
                $('#stop_button').prop('disabled', true).removeClass('blink');
                $('#start_button').prop('disabled', false).addClass('blink'); // Ensure Start is not blinking
            }
        } else {
            $('#start_stop_buttons').hide(); // Hide buttons if no selection
			$('#start_stop_input').hide();
        }
    }

    // Listen for changes in the select dropdown
    $('#kode_stop, #stop_mulai_jam, #stop_mulai_tgl').change(updateButtonStates);

    // Add click event for "Stop" button (if needed)
    $('#stop_button').click(function() {
        $(this).removeClass('blink'); // Stop blinking once the stop button is clicked
    });

    // Initial state update in case fields are prefilled
    updateButtonStates();
});
</script>
<script>
    $(document).ready(function () {
        $("#kode_stop").change(function () {
            var stopCode = $(this).val(); // Ambil nilai kode_stop yang dipilih
			var operationCode = $("#operation").val();
            var nokk = "<?= $nokk ?>"; // Pastikan $nokk sudah ada sebelumnya

            if (stopCode !== "") {
                $.ajax({
                    url: "pages/ajax/cek_kode_stop.php", // File PHP untuk pengecekan
                    type: "POST",
                    dataType: "json", // Mengharapkan JSON dari server
                    data: { stopcode: stopCode, nokk: nokk },
                    success: function (response) {
                        if (response.success) {
                            $("#stop_mulai_jam").val(response.stop_mulai_jam);
                            $("#stop_mulai_tgl").val(response.stop_mulai_tgl);
							$("#stop_selesai_jam").val(response.stop_selesai_jam);
                            $("#stop_selesai_tgl").val(response.stop_selesai_tgl);
                        } else {
                            $("#stop_mulai_jam").val("");
                            $("#stop_mulai_tgl").val("");
							$("#stop_selesai_jam").val("");
                            $("#stop_selesai_tgl").val("");
							//  alert(response.message); // Menampilkan pesan jika tidak ditemukan
                        }
                    }
                });
            } else {
                $("#stop_mulai_jam").val("");
                $("#stop_mulai_tgl").val("");
            }
        });
    });
</script>

<?php
if (isset($_POST['btnStart']) && $_POST['btnStart'] === "Start") {
	function nourut(){
			include "koneksi.php";
			$format = date("ym");
			$sql = mysqli_query($cona, "SELECT nokk FROM tbl_stoppage WHERE non_kk = '1' AND substr(nokk,1,4) like '%" . $format . "%' ORDER BY nokk DESC LIMIT 1 ") or die(mysqli_error());
			$d = mysqli_num_rows($sql);
			if ($d > 0) {
				$r = mysqli_fetch_array($sql);
				$d = $r['nokk'];
				$str = substr($d, 4, 4);
				$Urut = (int)$str;
			} else {
				$Urut = 0;
			}
			$Urut = $Urut + 1;
			$Nol = "";
			$nilai = 4 - strlen($Urut);
			for ($i = 1; $i <= $nilai; $i++) {
				$Nol = $Nol . "0";
			}
			$nipbr = $format . $Nol . $Urut;
			return $nipbr;
	}
	$nou = nourut();
    // Bersihkan dan validasi input
    $warna = mysqli_real_escape_string($cona, $_POST['warna']);
    $nowarna = mysqli_real_escape_string($cona, $_POST['no_warna']);
    $jns = mysqli_real_escape_string($cona, $_POST['jns_kain']);
    $po = mysqli_real_escape_string($cona, $_POST['no_po']);
    $lot = trim($_POST['lot']);
	if(!empty($_POST['non_kk'])){
		$nonkk = $_POST['non_kk'];
	}else{
		$nonkk  = "0";
	}
	if(!empty($_POST['nokk'])){
		$nokk1 = $_POST['nokk'];
	}else{
		$nokk1  = $nou;
	}

    // SQL Query (Pastikan jumlah `?` sesuai dengan jumlah parameter)
    $sql = "INSERT INTO tbl_stoppage (
        nokk, nodemand, langganan, buyer, no_order, no_hanger, no_item, po, 
        jenis_kain, lebar, gramasi, qty_order, lot, tgl_delivery, warna, no_warna, 
        dept, kode_operation, mulai_jam, mulai_tgl, selesai_jam, selesai_tgl, 
        mesin, kode_stop, operator, durasi_jam, durasi, non_kk,
		stop_mulai_jam, stop_mulai_tgl, tgl_buat, tgl_update
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW(), NOW())";

    // Siapkan statement
    $stmt = mysqli_prepare($cona, $sql);

    if ($stmt) {
        // Bind parameter (HARUS sesuai dengan jumlah `?` dalam query)
        mysqli_stmt_bind_param(
            $stmt, "ssssssssssssssssssssssssssss",
            $nokk1, $_POST['demand'], $_POST['pelanggan'], $_POST['buyer'],
            $_POST['no_order'], $_POST['no_hanger'], $_POST['no_item'], $po,
            $jns, $_POST['lebar'], $_POST['grms'], $_POST['qty_order'],
			$lot, $_POST['tgl_delivery'], $warna, $nowarna,
			$_SESSION['dept10'], $_POST['operation'], $_POST['mulai_jam'], $_POST['mulai_tgl'],
			$_POST['selesai_jam'], $_POST['selesai_tgl'], $_POST['mesin'], $_POST['kode_stop'],
			$_POST['operator'], $_POST['durasi'], $_POST['durasijammenit'], $nonkk
        );

        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>swal({
			  title: 'Data Tersimpan',   
			  text: 'Klik Ok untuk input data kembali',
			  type: 'success',
			  }).then((result) => {
			  if (result.value) {
				  window.location.href='index1.php?p=Input-Stoppage-Mesin';

			  }
			});</script>";
        } 

        // Tutup statement
        mysqli_stmt_close($stmt);
    } 

    // Tutup koneksi database
    mysqli_close($cona);
}

if (isset($_POST['btnStop']) && $_POST['btnStop'] === "Stop") {
	
    require 'koneksi.php'; // Pastikan koneksi sudah di-include
    
    // Mulai transaksi untuk memastikan integritas data
    mysqli_begin_transaction($cona);
    
    try {
        // Ambil nilai nokk dari form
        $nokk = $_POST['nokk'];  

        // Query pertama: Perbarui waktu selesai stop
        $sql = "UPDATE tbl_stoppage 
                SET stop_selesai_jam = CURTIME(), stop_selesai_tgl = CURDATE() 
                WHERE nokk = ?";

        $stmt = mysqli_prepare($cona, $sql);

        if (!$stmt) {
            throw new Exception("Error preparing query 1: " . mysqli_error($cona));
        }

        mysqli_stmt_bind_param($stmt, "s", $nokk);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error executing query 1: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);

        // Query kedua: Hitung durasi stop setelah stop selesai diperbarui
        $sql1 = "UPDATE tbl_stoppage 
                 SET durasi_jam_stop = TIMESTAMPDIFF(SECOND, 
                     STR_TO_DATE(CONCAT(stop_mulai_tgl, ' ', stop_mulai_jam), '%Y-%m-%d %H:%i:%s'), 
                     STR_TO_DATE(CONCAT(stop_selesai_tgl, ' ', stop_selesai_jam), '%Y-%m-%d %H:%i:%s')
                 ) / 3600 
                 WHERE nokk = ?";

        $stmt1 = mysqli_prepare($cona, $sql1);

        if (!$stmt1) {
            throw new Exception("Error preparing query 2: " . mysqli_error($cona));
        }

        mysqli_stmt_bind_param($stmt1, "s", $nokk);
        if (!mysqli_stmt_execute($stmt1)) {
            throw new Exception("Error executing query 2: " . mysqli_stmt_error($stmt1));
        }
        mysqli_stmt_close($stmt1);

        // Commit transaksi jika semua berhasil
        mysqli_commit($cona);

        // Tampilkan alert sukses
        echo "<script>swal({
			  title: 'Data Stop Tersimpan',   
			  text: 'Klik Ok untuk input data kembali',
			  type: 'success',
			  }).then((result) => {
			  if (result.value) {
				  window.location.href='index1.php?p=Input-Stoppage-Mesin';

			  }
			});</script>";
    } catch (Exception $e) {
        // Rollback jika terjadi kesalahan
        mysqli_rollback($cona);
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }

    // Tutup koneksi
    mysqli_close($cona);
}
?>
