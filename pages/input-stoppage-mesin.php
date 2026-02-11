<?php
	ini_set("error_reporting", 1);
	session_start();
	include "koneksi.php";
	if($_SESSION['dept10']=="CSR"){
	$demand = $_GET['demand'];	
	$child = $r['ChildLevel'];
	if ($demand != "") {		
	}	
		$sqlCek = sqlsrv_query($cona, "SELECT TOP 1 * FROM db_adm.tbl_stoppage WHERE nodemand = ? AND nodemand <> '' ORDER BY id DESC", [$demand]);
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

	$sqlCek = sqlsrv_query($cona, "SELECT TOP 1 * FROM db_adm.tbl_stoppage WHERE nokk = ? ORDER BY id DESC", [$nokk]);
	$rcek = sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC);
	$cek  = ($rcek !== false) ? 1 : 0;
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

		$tgl_delivery = $dt_ITXVIEWKK['DELIVERYDATE'] ?? '';
		if ($tgl_delivery instanceof DateTimeInterface) {
			$tgl_delivery = $tgl_delivery->format('Y-m-d');
		}
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
	
#mesin,
#mesin_stop {
    width: 100% !important;  /* pastikan full lebar container */
    min-width: 200px;        /* opsional: batas minimal */
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
			//document.form1.mesin.removeAttribute("readonly");
			//document.form1.mesin.setAttribute("required", true);			
			document.form1.mesin.removeAttribute("disabled");
			document.form1.mesin.setAttribute("required", true);
			document.form1.operator1.removeAttribute("disabled");
//			document.form1.operator1.setAttribute("required", true);
			
			document.form1.non_kk_stop.disabled = false;	
		} else if (document.forms['form1']['non_kk'].checked == true && document.forms['form1']['non_kk_stop'].checked == true) {			
			document.form1.nokk.removeAttribute("readonly");
			document.form1.nokk.setAttribute("required", true);
			document.form1.nokk.focus();
			//document.form1.mesin.setAttribute("readonly", true);
			//document.form1.mesin.removeAttribute("required");
			//document.form1.mesin.value = "";
			document.form1.mesin.setAttribute("disabled", true);
			document.form1.mesin.removeAttribute("required");
			document.form1.mesin.value = "";
			document.form1.operator1.setAttribute("disabled", true);
//			document.form1.operator1.removeAttribute("required");
			document.form1.operator1.value = "";
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
			//document.form1.mesin.removeAttribute("readonly");
			//document.form1.mesin.setAttribute("required", true);
			document.form1.mesin.removeAttribute("disabled");
			document.form1.mesin.setAttribute("required", true);
			document.form1.operator1.removeAttribute("disabled");
//			document.form1.operator1.setAttribute("required", true);
			
			document.form1.non_kk_stop.disabled = true;
			document.form1.non_kk_stop.checked = false;
			document.form1.non_kk_stop.value = "";
		}
	}
</script>
<script>
$(document).ready(function() {
    $('#kode_stop').change(function() {
        const selectedValue = $(this).val();

        if (selectedValue === 'LM') {
            $('#start_stop_input1').show();
            $('#stop_mulai_jam1, #stop_mulai_tgl1, #stop_selesai_jam1, #stop_selesai_tgl1').removeAttr('readonly');
        } else {
            $('#start_stop_input1').hide();
            $('#stop_mulai_jam1, #stop_mulai_tgl1, #stop_selesai_jam1, #stop_selesai_tgl1').attr('readonly', true);
        }
    });
});
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
					<div class="col-sm-4">
						<input type="checkbox" name="non_kk" id="non_kk" onClick="aktif();" value="1"> Tidak Ada Kartu <br>
						<input type="checkbox" name="non_kk_stop" id="non_kk_stop" onClick="aktif();" value="1" disabled> 
						Selesai Stop Mesin Tidak Ada Kartu <br>
						<input type="checkbox" name="mesin_garuk" id="mesin_garuk" value="1" <?php echo (!isset($_GET['nokk']) || empty($_GET['nokk'])) ? 'disabled' : ''; ?>> Mesin Garuk
					</div>
				</div>
				<div class="form-group">
					<label for="demand" class="col-sm-3 control-label">Production Demand</label>
					<div class="col-sm-8">
<!--						<input name="demand" type="text" class="form-control" id="demand" value="<?= ($cek > 0) ? $rcek['nodemand'] : $dt_demand['DEMAND']; ?>" placeholder="Production Demand">-->
						<input name="demand" type="text" class="form-control" id="demand" value="<?= $dt_demand['DEMAND']; ?>" placeholder="Production Demand">
					</div>					
				</div>
				<div class="form-group">
					<label for="no_order" class="col-sm-3 control-label">No Order</label>
					<div class="col-sm-4">
<!--						<input name="no_order" type="text" class="form-control" id="no_order" value="<?= ($cek > 0) ? $rcek['no_order'] : $dt_ITXVIEWKK['PROJECTCODE']; ?>" placeholder="No Order" required>-->
						<input name="no_order" type="text" class="form-control" id="no_order" value="<?= $dt_ITXVIEWKK['PROJECTCODE']; ?>" placeholder="No Order" required>
					</div>
				</div>
				<div class="form-group">
					<label for="pelanggan" class="col-sm-3 control-label">Pelanggan</label>
					<div class="col-sm-8">
<!--						<input name="pelanggan" type="text" class="form-control" id="pelanggan" value="<?= ($cek > 0) ? $rcek['langganan'] : $dt_pelanggan_buyer['PELANGGAN']; ?>" placeholder="Pelanggan">-->
						<input name="pelanggan" type="text" class="form-control" id="pelanggan" value="<?= $dt_pelanggan_buyer['PELANGGAN']; ?>" placeholder="Pelanggan">
					</div>
				</div>
				<div class="form-group">
					<label for="buyer" class="col-sm-3 control-label">Buyer</label>
					<div class="col-sm-8">
<!--						<input name="buyer" type="text" class="form-control" id="buyer" value="<?= ($cek > 0) ? $rcek['buyer'] : $dt_pelanggan_buyer['BUYER']; ?>" placeholder="Buyer">-->
						<input name="buyer" type="text" class="form-control" id="buyer" value="<?= $dt_pelanggan_buyer['BUYER']; ?>" placeholder="Buyer">
					</div>
				</div>
				<div class="form-group">
					<label for="no_po" class="col-sm-3 control-label">PO</label>
					<div class="col-sm-5">
<!--						<input name="no_po" type="text" class="form-control" id="no_po" value="<?= ($cek > 0) ? $rcek['po'] : $dt_po['NO_PO']; ?>" placeholder="PO">-->
						<input name="no_po" type="text" class="form-control" id="no_po" value="<?= $dt_po['NO_PO']; ?>" placeholder="PO">
					</div>
				</div>
				<div class="form-group">
					<label for="no_hanger" class="col-sm-3 control-label">No Hanger / No Item</label>
					<div class="col-sm-3">
<!--						<input name="no_hanger" type="text" class="form-control" id="no_hanger" value="<?= ($cek > 0) ? $rcek['no_hanger'] : $dt_ITXVIEWKK['NO_HANGER']; ?>" placeholder="No Hanger">-->
						<input name="no_hanger" type="text" class="form-control" id="no_hanger" value="<?= $dt_ITXVIEWKK['NO_HANGER']; ?>" placeholder="No Hanger">
					</div>						
					<div class="col-sm-3">
<!--						<input name="no_item" type="text" class="form-control" id="no_item" value="<?= ($cek > 0) ? $rcek['no_item'] : $dt_item['EXTERNALITEMCODE'] ?>" placeholder="No Item">-->
						<input name="no_item" type="text" class="form-control" id="no_item" value="<?= $dt_item['EXTERNALITEMCODE'] ?>" placeholder="No Item">
				    </div>
				</div>
				<div class="form-group">
					<label for="jns_kain" class="col-sm-3 control-label">Jenis Kain</label>
					<div class="col-sm-8">
<!--						<textarea name="jns_kain" class="form-control" id="jns_kain" placeholder="Jenis Kain"><?= ($cek > 0) ? $rcek['jenis_kain'] :  $dt_ITXVIEWKK['ITEMDESCRIPTION'] ?></textarea>-->
						<textarea name="jns_kain" class="form-control" id="jns_kain" placeholder="Jenis Kain"><?= $dt_ITXVIEWKK['ITEMDESCRIPTION'] ?></textarea>
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
<!--						<input name="lebar" type="text" class="form-control" id="lebar" value="<?= ($cek > 0) ? $rcek['lebar'] : $dt_lg['LEBAR']; ?>" placeholder="0" required>-->
						<input name="lebar" type="text" class="form-control" id="lebar" value="<?= $dt_lg['LEBAR']; ?>" placeholder="0" required>
					</div>
					<div class="col-sm-2">
<!--						<input name="grms" type="text" class="form-control" id="grms" value="<?= ($cek > 0) ? $rcek['gramasi'] : $dt_lg['GRAMASI']; ?>" placeholder="0" required>-->
						<input name="grms" type="text" class="form-control" id="grms" value="<?= $dt_lg['GRAMASI']; ?>" placeholder="0" required>
					</div>
				</div>
				<div class="form-group">
					<label for="tgl_delivery" class="col-sm-3 control-label">Tgl Delivery</label>
					<div class="col-sm-3">						
					<div class="input-group date" id="tgl_d">
						<div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
<!--						<input name="tgl_delivery" type="text" class="form-control" id="datepicker" placeholder="Tanggal Delivery" value="<?= ($cek > 0) ? ($rcek['tgl_delivery'] ? date_format($rcek['tgl_delivery'], 'Y-m-d') : '') : $dt_ITXVIEWKK['DELIVERYDATE']; ?>" autocomplete="off"/>-->
						<input name="tgl_delivery" type="text" class="form-control" id="datepicker" placeholder="Tanggal Delivery" value="<?= $tgl_delivery; ?>" autocomplete="off"/>
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
<!--						<textarea name="warna" class="form-control" id="warna" placeholder="Warna"><?= ($cek > 0) ? $rcek['warna'] : $dt_warna['WARNA']; ?></textarea>-->
						<textarea name="warna" class="form-control" id="warna" placeholder="Warna"><?= $dt_warna['WARNA']; ?></textarea>
					</div>
				</div>
				<div class="form-group">

					<label for="no_warna" class="col-sm-3 control-label">No Warna</label>
					<div class="col-sm-8">
<!--						<textarea name="no_warna" class="form-control" id="no_warna" placeholder="No Warna"><?= ($cek > 0) ? $rcek['no_warna'] : $dt_ITXVIEWKK['NO_WARNA']; ?></textarea>-->
						<textarea name="no_warna" class="form-control" id="no_warna" placeholder="No Warna"><?= $dt_ITXVIEWKK['NO_WARNA']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="lot" class="col-sm-3 control-label">Lot</label>
					<div class="col-sm-3">
<!--						<input name="lot" type="text" class="form-control" id="lot" value="<?= ($cek > 0) ? $rcek['lot'] : $dt_ITXVIEWKK['LOT']; ?>" placeholder="Lot">-->
						<input name="lot" type="text" class="form-control" id="lot" value="<?= $dt_ITXVIEWKK['LOT']; ?>" placeholder="Lot">
					</div>
				</div>
				<div class="form-group">
					<label for="proses" class="col-sm-3 control-label">Qty Order</label>
					<div class="col-sm-4">
						<div class="input-group">
<!--							<input name="qty_order" type="text" class="form-control" id="qty_order" value="<?= ($cek > 0) ? $rcek['qty_order'] : $dt_qtyorder['QTY_ORDER']; ?>" placeholder="0.00" style="text-align: right;" required>-->
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
				<div id="form-mesin-stop-normal">
				<div class="form-group">
					<label for="mesin" class="col-sm-3 control-label">Mesin</label>
					<div class="col-sm-4">
						<select class="form-control select2" name="mesin" id="mesin" style="width: 100%;">
							<option value="">Pilih</option>
							<?php
							$whereMesin = ""; // default kosong
							
							$dept = $_SESSION['dept10'] ?? '';

							if ($dept == 'FIN') {
								$whereMesin = "
												WHERE
													SUBSTR(CODE,1,4) IN ('P3ST','P3CP','P3BC','P3DR','P3SM','P3LS')
													OR CODE = 'P3IN350'OR CODE ='P3PA101'OR code ='P3LI101'
											";
											} elseif ($dept == 'BRS') {
											$whereMesin = "
												WHERE
													(
														SUBSTR(CODE,1,4) IN ('P3AR','P3CO','P3SO','P3RS','P3SH','P3SU')
														OR CODE IN ('P3TD101','P3TD202','P3TD203','P3TD204')
													)
													AND LONGDESCRIPTION NOT LIKE '%DNU%'
													AND LONGDESCRIPTION NOT LIKE '%DO NOT USE%'
													AND CODE NOT IN ('P3RS11C','P3RS12C','P3RS13C','P3SU16A')
											";
											} elseif ($dept == 'QCF') {
											$whereMesin = "
												WHERE
													(
														SUBSTR(CODE,1,4) = 'P3IN'
														OR CODE = 'P3QC1010'
													)
													AND CODE NOT IN (
														'P3IN201','P3IN202','P3IN203','P3IN204','P3IN205',
														'P3IN323','P3IN324','P3IN350'
													)
											";
											} elseif ($dept == 'DYE') {
											$whereMesin = "
												WHERE
													(
														SUBSTR(CODE,1,4) IN ('P3DY','P3DR','P3CB','P3BB')
														OR CODE = 'P3RX101'
													)
													AND LONGDESCRIPTION NOT LIKE '%DNU%'
													AND LONGDESCRIPTION NOT LIKE '%DO NOT USE%'
													AND CODE NOT IN ('P3DY4479','P3DY4480')
											";
											} elseif ($dept == 'GKG') {
											$whereMesin = "
												WHERE
													SUBSTR(CODE,1,4) IN ('P3GR','P3BK','P3BL','P3JP')
													AND LONGDESCRIPTION NOT LIKE '%DNU%'
													AND LONGDESCRIPTION NOT LIKE '%DO NOT USE%'
											";
											}
							?>

						<?php
							$sql_mesin	= db2_exec($conn2, " SELECT
																						TRIM(CODE) AS CODE, LONGDESCRIPTION
																					FROM
																						RESOURCES r
																					$whereMesin
																					ORDER BY
																						SUBSTR(r.CODE, 1, 4), SUBSTR(r.CODE, 6, 2)
																					ASC ");
							while($dt_mesin   = db2_fetch_assoc($sql_mesin)){	
							?>
							<option value="<?php echo $dt_mesin['CODE'];?>"><?php echo $dt_mesin['CODE']." || ".$dt_mesin['LONGDESCRIPTION']." || ". $dept;?></option>
							<?php } ?>
						</select>
					</div>
					<?php if($cek>0){ echo $rcek['mesin']; } ?>
				</div>
			  </div>		
			  <div id="form-mesin-stop">
				<div class="form-group">
					<label for="mesin" class="col-sm-3 control-label">Mesin Stop 1</label>
					<div class="col-sm-4">
						<select class="form-control select2" name="mesin_mulai" id="mesin_mulai" style="width: 100%;">
							<option value="">Pilih</option>
							<?php
								$sql = "SELECT nama, dept, deskripsi FROM db_adm.tbl_mesin_now WHERE dept = ? AND deskripsi LIKE ? ORDER BY deskripsi";
								$sql_mesin = sqlsrv_query($cona, $sql, [$_SESSION['dept10'], 'Raising%']);
								if ($sql_mesin === false) { die(print_r(sqlsrv_errors(), true)); }
								while ($dt_mesin = sqlsrv_fetch_array($sql_mesin, SQLSRV_FETCH_ASSOC)) {
							?>
								<option value="<?php echo $dt_mesin['nama']; ?>">
								<?php echo $dt_mesin['nama']." || ".$dt_mesin['deskripsi']." || ".$dt_mesin['dept']; ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<?php if ($cek > 0) { echo $rcek['mesin']; } ?>
				</div>   
				<div class="form-group"> 					 
				  <label for="mesin_stop" class="col-sm-3 control-label">Mesin Stop 2</label>
					<div class="col-sm-4">
						<select class="form-control select2" name="mesin_stop" id="mesin_stop" style="width: 100%;">
							<option value="">Pilih</option>
							<?php
								$sql = "SELECT nama, dept, deskripsi FROM db_adm.tbl_mesin_now WHERE dept = ? AND deskripsi LIKE ? ORDER BY deskripsi";
								$sql_mesin = sqlsrv_query($cona, $sql, [$_SESSION['dept10'], 'Raising%']);
								if ($sql_mesin === false) { die(print_r(sqlsrv_errors(), true)); }
								while ($dt_mesin = sqlsrv_fetch_array($sql_mesin, SQLSRV_FETCH_ASSOC)) {
							?>
							<option value="<?php echo $dt_mesin['nama'];?>">
								<?php echo $dt_mesin['nama']." || ".$dt_mesin['deskripsi']." || ".$dt_mesin['dept'];?>
							</option>
							<?php } ?>
						</select>
					</div>
				</div>
			  </div>	
<!--
				<div class="form-group">
					<label for="mesin" class="col-sm-3 control-label">Mesin</label>
					<div class="col-sm-3">
						<input name="mesin" type="text" class="form-control" maxlength="8" id="mesin" value="" placeholder="mesin">
					</div>
				</div>
-->
				<div class="form-group">
					<label for="operator" class="col-sm-3 control-label">Operator
					  <?php if($cek>0){ echo $rcek['mesin']; } ?>
				    </label>
					<div class="col-sm-3">
						<input name="operator" type="hidden" class="form-control" id="operator" value="" placeholder="operator">
				  </div>
					<div class="col-sm-3">
						<input name="kode_operator" type="hidden" class="form-control" id="kode_operator" value="" placeholder="kode operator">
					</div>
					<div class="col-sm-4">
						<select class="form-control select2" name="operator1" id="operator1">
							<option value="">Pilih</option>
							<?php
								$sql_op = sqlsrv_query($cona, "SELECT nama, kode FROM db_adm.tbl_shift_operator WHERE dept = ?", [$_SESSION['dept10']]);
								if ($sql_op === false) { die(print_r(sqlsrv_errors(), true)); }
								while ($dt_op = sqlsrv_fetch_array($sql_op, SQLSRV_FETCH_ASSOC)) {
							?>
							<option value="<?php echo $dt_op['nama'];?>">
								<?php echo $dt_op['nama']." || ".$dt_op['kode'];?>
							</option>
							<?php } ?>
						</select>
					</div>
			  </div>
				<div class="form-group">
					<label for="kode_stop1" class="col-sm-3 control-label">Kode Stop Mesin</label>
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
					<label for="stop_mulai_proses" class="col-sm-3 control-label">Manual Mulai Stop</label>
					<div class="col-sm-2">
						<input name="stop_mulai_jam1" type="time" class="form-control" id="stop_mulai_jam1" value="" placeholder="00:00" readonly>
						
					</div>
					<div class="col-sm-3">
						<input name="stop_mulai_tgl1" type="date" class="form-control" id="stop_mulai_tgl1" value="" placeholder="tgl stop mulai" readonly>
						
					</div>
				</div>			
				<div class="form-group" >
					<label for="stop_selesai_proses" class="col-sm-3 control-label">Manual Mulai Jalan</label>
					<div class="col-sm-2">
						<input name="stop_selesai_jam1" type="time" class="form-control" id="stop_selesai_jam1" value="" placeholder="00:00" readonly>
					</div>
					<div class="col-sm-3">
						<input name="stop_selesai_tgl1" type="date" class="form-control" id="stop_selesai_tgl1" value="" placeholder="tgl stop selesai" readonly>
					</div>
				</div>
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
					<label for="stop_selesai_proses" class="col-sm-3 control-label">Mulai Jalan</label>
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
			<button type="submit" class="btn btn-success pull-left" name="btnStart" value="Start" id="start_button"><i class="fa fa-play-circle"> </i> MC mulai Stop</button>
			<button type="submit" class="btn btn-danger pull-right" name="btnStop" value="Stop" id="stop_button"><i class="fa fa-stop-circle"> </i> MC mulai Jalan</button>
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
document.addEventListener("DOMContentLoaded", function() {
    let checkbox = document.getElementById("mesin_garuk");
    let formStop = document.getElementById("form-mesin-stop");
	let formStopNormal = document.getElementById("form-mesin-stop-normal");

    function toggleMesinStop() {
        if (checkbox.checked) {
            formStop.style.display = "block";
			formStopNormal.style.display = "none";
        } else {
            formStop.style.display = "none";
			formStopNormal.style.display = "block";
        }
    }

    // Jalankan saat load halaman
    toggleMesinStop();

    // Jalankan saat checkbox berubah
    checkbox.addEventListener("change", toggleMesinStop);
});
</script>
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
                        resetFormFields();
                    } else {
                        $('#mulai_jam').val(response.PROGRESSSTARTPROCESSTIME || '');
                        $('#mulai_tgl').val(response.PROGRESSSTARTPROCESSDATE || '');
                        $('#selesai_jam').val(response.PROGRESSENDTIME || '');
                        $('#selesai_tgl').val(response.PROGRESSENDDATE || '');
                        $('#operator').val(response.NAMA || '');
                        $('#kode_operator').val(response.OPERATORCODE || '');

                        // Jika mesin adalah select
                        if ($('#mesin').is('select')) {
							var mesinCode = response.MACHINECODE || '';
							var mesinOptionExists = $('#mesin option[value="' + mesinCode + '"]').length > 0;

							if (mesinOptionExists) {
								$('#mesin').val(mesinCode).trigger('change'); // untuk update select2
							} else {
								$('#mesin').val('').trigger('change');
								console.warn('Kode mesin tidak ditemukan di daftar opsi:', mesinCode);
							}
						} else {
							$('#mesin').val(response.MACHINECODE || '');
						}


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
        resetFormFields();
    }

    function resetFormFields() {
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
    var isNonKK = document.getElementById('non_kk').checked;
    var isNonKK_Stop = document.getElementById('non_kk_stop').checked;
    var nokk = document.getElementById('nokk').value;
		
	if (isNonKK && isNonKK_Stop) {
        window.location = '?p=Input-Stoppage-Mesin&nokk=' + encodeURIComponent(nokk) +
                          '&non_kk=' + (isNonKK ? 1 : 0) +
                          '&non_kk_stop=' + (isNonKK_Stop ? 1 : 0);
    }else	
    if (!isNonKK) {
       window.location = '?p=Input-Stoppage-Mesin&nokk=' + nokk;
    }
    // Jika isNonKK dicentang, tidak melakukan apa-apa
}
</script>
<script>
window.onload = function() {
    const params = new URLSearchParams(window.location.search);

    if (params.get('non_kk') === '1') {
        document.getElementById('non_kk').checked = true;
		// Panggil fungsi saat checkbox tercentang
        aktif();
    }

    if (params.get('non_kk_stop') === '1') {
        document.getElementById('non_kk_stop').checked = true;
		// Panggil fungsi saat checkbox tercentang
        aktif();
    }
	// Optional: set value nokk jika ingin isi field tetap ada setelah reload
    if (params.get('nokk')) {
        document.getElementById('nokk').value = params.get('nokk');
    }
};
</script>
<script>
$(document).ready(function() {
    // Fungsi untuk mengatur status tombol berdasarkan input
    function updateButtonStates() {
        var selectedValue = $('#kode_stop').val();  
        var stopCode = $('#kode_stop').val(); // Ambil nilai dari dropdown
        var nokk = "<?= $nokk ?>"; // Pastikan nilai ini tersedia dari PHP

        if (selectedValue !== "") {
            $.ajax({
                url: "pages/ajax/cek_kode_stop.php",
                type: "POST",
                dataType: "json",
                data: { stopcode: stopCode, nokk: nokk },
                success: function (response) {
                    // Set nilai input berdasarkan response
					
					console.log();
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
                    }

                    // Cek kembali nilai field setelah AJAX berhasil
                    var stopMJamValue = $('#stop_mulai_jam').val();
                    var stopMTglValue = $('#stop_mulai_tgl').val();
                    var stopSJamValue = $('#stop_selesai_jam').val();
                    var stopSTglValue = $('#stop_selesai_tgl').val();

                    $('#start_stop_buttons').show();
                    $('#start_stop_input').show();

                    if (stopMJamValue && stopMTglValue && stopSJamValue && stopSTglValue) {
						// Jika semua terisi, aktifkan kembali tombol Start, Stop tetap disable
						$('#start_button').prop('disabled', false).addClass('blink');
						$('#stop_button').prop('disabled', true).removeClass('blink');
						$('#stop_mulai_jam1').prop('readonly', true).removeAttr('required');
						$('#stop_mulai_tgl1').prop('readonly', true).removeAttr('required');
						$('#stop_selesai_jam1').prop('readonly', true).removeAttr('required');
						$('#stop_selesai_tgl1').prop('readonly', true).removeAttr('required');
					} else if (stopMJamValue && stopMTglValue) {
						// Jika baru mulai saja, aktifkan tombol Stop
						$('#start_button').prop('disabled', true).removeClass('blink');
						$('#stop_button').prop('disabled', false).addClass('blink');						
						$('#stop_mulai_jam1').prop('readonly', true).removeAttr('required');
						$('#stop_mulai_tgl1').prop('readonly', true).removeAttr('required');
					} else {
						// Jika belum mulai, aktifkan tombol Start
						$('#start_button').prop('disabled', false).addClass('blink');
						$('#stop_button').prop('disabled', true).removeClass('blink');
						$('#stop_selesai_jam1').prop('readonly', true).removeAttr('required');
						$('#stop_selesai_tgl1').prop('readonly', true).removeAttr('required');
					}

                }
            });
        } else {
            $('#start_stop_buttons').hide();
            $('#start_stop_input').hide();
        }
    }

    // Trigger ketika dropdown atau input berubah
    $('#kode_stop, #stop_mulai_jam, #stop_mulai_tgl').change(updateButtonStates);

    // Hentikan efek blink pada tombol Stop saat diklik
    $('#stop_button').click(function() {
        $(this).removeClass('blink');
    });

    // Panggil saat halaman dimuat, jika data sudah ada
    updateButtonStates();
});
</script>


<?php
if (isset($_POST['btnStart']) && $_POST['btnStart'] === "Start") {
	function normalize_numeric($value) {
		if ($value === null) {
			return null;
		}
		$value = trim((string)$value);
		if ($value === '') {
			return null;
		}
		$value = str_replace(' ', '', $value);
		if (strpos($value, ',') !== false) {
			if (strpos($value, '.') !== false) {
				$value = str_replace(',', '', $value);
			} else {
				$value = str_replace(',', '.', $value);
			}
		}
		if (!is_numeric($value)) {
			return null;
		}
		return $value;
	}

	function nourut(){
		include "koneksi.php";

		$format = date("ym");

		$sql = " SELECT TOP 1 nokk FROM db_adm.tbl_stoppage WHERE non_kk = '1' AND SUBSTRING(nokk, 1, 4) LIKE ? ORDER BY nokk DESC ";
		$stmt = sqlsrv_query($cona, $sql, ['%' . $format . '%']);
		if ($stmt === false) { die(print_r(sqlsrv_errors(), true)); }
		$r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

		if ($r !== false && !empty($r['nokk'])) {
			$d    = $r['nokk'];
			$str  = substr($d, 4, 4);
			$Urut = (int)$str;
		} else {
			$Urut = 0;
		}

		$Urut = $Urut + 1;

		$Nol   = "";
		$nilai = 4 - strlen((string)$Urut);
		for ($i = 1; $i <= $nilai; $i++) {
			$Nol .= "0";
		}

		$nipbr = $format . $Nol . $Urut;
		return $nipbr;
	}
	$nou = nourut();
    // Bersihkan dan validasi input
	$warna = $_POST['warna'];
    $nowarna = $_POST['no_warna'];
    $jns = $_POST['jns_kain'];
    $po =$_POST['no_po'];
    $lot = trim($_POST['lot']);
	$lebar = normalize_numeric($_POST['lebar']);
	$gramasi = normalize_numeric($_POST['grms']);
	$qty_order = normalize_numeric($_POST['qty_order']);
	$durasi_jam = normalize_numeric($_POST['durasi']);
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
	if (!empty($_POST['mesin_mulai'])) {
    $mesin = $_POST['mesin_mulai']; // kalau ceklis Mesin Garuk
	} else {
		$mesin = $_POST['mesin']; // kalau normal
	}
	if($_POST['kode_stop']=="LM"){

		// SQL Query (Pastikan jumlah `?` sesuai dengan jumlah parameter)
    $sql = "INSERT INTO db_adm.tbl_stoppage (
        nokk,
		nodemand,
		langganan,
		buyer,
		no_order,
		no_hanger,
		no_item,
		po, 
        jenis_kain,
		lebar,
		gramasi,
		qty_order,
		lot,
		tgl_delivery,
		warna,
		no_warna, 
        dept,
		kode_operation,
		mulai_jam,
		mulai_tgl,
		selesai_jam,
		selesai_tgl, 
        mesin,
		mesin_stop,
		kode_stop,
		operator,
		durasi_jam,
		durasi,
		non_kk,
		stop_mulai_jam,
		stop_mulai_tgl,
		tgl_buat,
		tgl_update
    ) VALUES (
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	GETDATE(),
	GETDATE())";

    // Siapkan statement (SQL Server)
	$stmt = sqlsrv_prepare($cona, $sql, [
		$nokk1, $_POST['demand'], $_POST['pelanggan'], $_POST['buyer'],
		$_POST['no_order'], $_POST['no_hanger'], $_POST['no_item'], $po,
		$jns, $lebar, $gramasi, $qty_order,
		$lot, $_POST['tgl_delivery'], $warna, $nowarna,
		$_SESSION['dept10'], $_POST['operation'], $_POST['mulai_jam'], $_POST['mulai_tgl'],
		$_POST['selesai_jam'], $_POST['selesai_tgl'], $mesin, $_POST['mesin_stop'], $_POST['kode_stop'],
		$_POST['operator1'], $durasi_jam, $_POST['durasijammenit'], $nonkk, $_POST['stop_mulai_jam1'], $_POST['stop_mulai_tgl1']
	]);

	if ($stmt) {
		// Eksekusi query
		if (sqlsrv_execute($stmt)) {
			echo "<script>swal({
			title: 'Data Tersimpan dengan NOKK: $nokk1',
			text: 'Klik Ok untuk input data kembali',
			type: 'success',
			}).then((result) => {
			if (result.value) {
				window.location.href='index1.php?p=Input-Stoppage-Mesin';
			}
			});</script>";
		} else {
			die(print_r(sqlsrv_errors(), true));
		}

		sqlsrv_free_stmt($stmt);
	} else {
		die(print_r(sqlsrv_errors(), true));
	}

		
	}else{
		
	// SQL Query (Pastikan jumlah `?` sesuai dengan jumlah parameter)
    $sql = "INSERT INTO db_adm.tbl_stoppage (
		nokk, nodemand, langganan, buyer, no_order, no_hanger, no_item, po,
		jenis_kain, lebar, gramasi, qty_order, lot, tgl_delivery, warna, no_warna,
		dept, kode_operation, mulai_jam, mulai_tgl, selesai_jam, selesai_tgl,
		mesin, mesin_stop, kode_stop, operator, durasi_jam, durasi, non_kk,
		stop_mulai_jam, stop_mulai_tgl, tgl_buat, tgl_update
	) VALUES (
		?,?,?,?,?,?,?,?,
		?,?,?,?,?,?,?,?,
		?,?,?,?,?,?,
		?,?,?,?,?,?,
		?,
		CONVERT(varchar(8), GETDATE(), 108),
		CONVERT(varchar(10), GETDATE(), 23),
		GETDATE(), GETDATE()
	)";

    // Siapkan statement (SQL Server)
	$stmt = sqlsrv_prepare($cona, $sql, [
		$nokk1,
		$_POST['demand'],
		$_POST['pelanggan'],
		$_POST['buyer'],
		$_POST['no_order'],
		$_POST['no_hanger'],
		$_POST['no_item'],
		$po,
		$jns,
		$lebar,
		$gramasi,
		$qty_order,
		$lot,
		$_POST['tgl_delivery'],
		$warna,
		$nowarna,
		$_SESSION['dept10'],
		$_POST['operation'],
		$_POST['mulai_jam'],
		$_POST['mulai_tgl'],
		$_POST['selesai_jam'],
		$_POST['selesai_tgl'],
		$mesin,
		$_POST['mesin_stop'],
		$_POST['kode_stop'],
		$_POST['operator1'],
		$durasi_jam,
		$_POST['durasijammenit'],
		$nonkk
	]);

	if ($stmt) {
		// Eksekusi query
		if (sqlsrv_execute($stmt)) {
			echo "<script>swal({
			title: 'Data Tersimpan dengan NOKK: $nokk1',
			text: 'Klik Ok untuk input data kembali',
			type: 'success',
			}).then((result) => {
			if (result.value) {
				window.location.href='index1.php?p=Input-Stoppage-Mesin';
			}
			});</script>";
		} else {
			die(print_r(sqlsrv_errors(), true));
		}

		// Tutup statement
		sqlsrv_free_stmt($stmt);
	} else {
		die(print_r(sqlsrv_errors(), true));
	}
		
	}
    // Tutup koneksi database
    sqlsrv_close($cona);
}

if (isset($_POST['btnStop']) && $_POST['btnStop'] === "Stop") {
    require 'koneksi.php';

    // Mulai transaksi
    sqlsrv_begin_transaction($cona);

    try {
        $nokk = $_POST['nokk'];

        $getIdSql = "SELECT TOP 1 id
                     FROM db_adm.tbl_stoppage
                     WHERE nokk = ? AND kode_stop = ?
                     ORDER BY id DESC";

        $stmtGet = sqlsrv_prepare($cona, $getIdSql, [$nokk, $_POST['kode_stop']]);
        if (!$stmtGet) {
            throw new Exception("Error preparing getIdSql: " . print_r(sqlsrv_errors(), true));
        }
        if (!sqlsrv_execute($stmtGet)) {
            throw new Exception("Error executing getIdSql: " . print_r(sqlsrv_errors(), true));
        }

        $rowGet = sqlsrv_fetch_array($stmtGet, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($stmtGet);

        $idStoppage = ($rowGet && isset($rowGet['id'])) ? $rowGet['id'] : null;

        if (empty($idStoppage)) {
            throw new Exception("Data stoppage tidak ditemukan untuk nokk: $nokk");
        }

        // Update stop selesai (beda jika kode_stop = LM)
        if ($_POST['kode_stop'] == "LM") {
            $sql = "UPDATE db_adm.tbl_stoppage
                    SET stop_selesai_jam = ?, stop_selesai_tgl = ?
                    WHERE id = ?";

            $stmt = sqlsrv_prepare($cona, $sql, [
                $_POST['stop_selesai_jam1'],
                $_POST['stop_selesai_tgl1'],
                $idStoppage
            ]);

            if (!$stmt) {
                throw new Exception("Error preparing query update LM: " . print_r(sqlsrv_errors(), true));
            }
        } else {
            $sql = "UPDATE db_adm.tbl_stoppage
                    SET stop_selesai_jam = CONVERT(varchar(8), GETDATE(), 108),
                        stop_selesai_tgl = CONVERT(varchar(10), GETDATE(), 23)
                    WHERE id = ?";

            $stmt = sqlsrv_prepare($cona, $sql, [$idStoppage]);

            if (!$stmt) {
                throw new Exception("Error preparing query update default: " . print_r(sqlsrv_errors(), true));
            }
        }

        if (!sqlsrv_execute($stmt)) {
            throw new Exception("Error executing update stop_selesai: " . print_r(sqlsrv_errors(), true));
        }
        sqlsrv_free_stmt($stmt);

        // Update durasi stop (jam)
        $sql1 = "UPDATE db_adm.tbl_stoppage
                 SET durasi_jam_stop =
                     DATEDIFF(SECOND,
                         TRY_CONVERT(datetime,TRY_CONVERT(VARCHAR(19), CONCAT(stop_mulai_tgl ,' ' , stop_mulai_jam))),
                         TRY_CONVERT(datetime,TRY_CONVERT(VARCHAR(19), CONCAT(stop_selesai_tgl , ' ' , stop_selesai_jam)))
                     ) / 3600.0
                 WHERE id = ?";

        $stmt1 = sqlsrv_prepare($cona, $sql1, [$idStoppage]);
        if (!$stmt1) {
            throw new Exception("Error preparing query durasi: " . print_r(sqlsrv_errors(), true));
        }
        if (!sqlsrv_execute($stmt1)) {
            throw new Exception("Error executing query durasi: " . print_r(sqlsrv_errors(), true));
        }
        sqlsrv_free_stmt($stmt1);

        // Commit transaksi
        sqlsrv_commit($cona);

        echo "<script>
            swal({
                title: 'Data Stop Tersimpan',
                text: 'Klik Ok untuk input data kembali',
                type: 'success',
            }).then((result) => {
                if (result.value) {
                    window.location.href='index1.php?p=Input-Stoppage-Mesin';
                }
            });
        </script>";

    } catch (Exception $e) {
        sqlsrv_rollback($cona);
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }

    // Tutup koneksi database
    sqlsrv_close($cona);
}


?>
