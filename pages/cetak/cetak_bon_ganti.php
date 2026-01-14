<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
include "../../tgl_indo.php";
//--
$idkk = $_REQUEST['idkk'];
$act = $_GET['g'];
//-
$Awal = $_GET['awal'];
$Akhir = $_GET['akhir'];
$Dept = $_GET['dept'];
$Cancel = $_GET['cancel'];
$qTgl = sqlsrv_query($cona, "SELECT CONVERT(varchar(10), GETDATE(), 120) AS tgl_skrg, CONVERT(varchar(8),  GETDATE(), 108) AS jam_skrg");
$rTgl = sqlsrv_fetch_array($qTgl, SQLSRV_FETCH_ASSOC);
if ($Awal != "") {
  $tgl = substr($Awal, 0, 10);
  $jam = $Awal;
} else {
  $tgl = $rTgl['tgl_skrg'];
  $jam = $rTgl['jam_skrg'];
}
$noBon = $_GET['no_bon'];

$qry = sqlsrv_query($cona, "
  SELECT
    a.*,
    a.t_jawab, a.t_jawab1, a.t_jawab2,
    b.alasan,
    b.warna1, b.warna2, b.warna3,
    b.kg1, b.kg2, b.kg3,
    b.pjg1, b.pjg2, b.pjg3,
    b.satuan1, b.satuan2, b.satuan3,
    b.sebab,
    a.sebab AS sebab_analisa_old,
    b.akar_penyebab AS sebab_analisa,
    b.analisa, b.pencegahan,
    b.nokk1, b.nokk2, b.nokk3
  FROM db_adm.tbl_gantikain a
  INNER JOIN db_adm.tbl_bonkain b ON a.id = b.id_nsp
  WHERE b.no_bon = ?
", [$noBon]);

if ($qry === false) { die(print_r(sqlsrv_errors(), true)); }
$r = sqlsrv_fetch_array($qry, SQLSRV_FETCH_ASSOC);
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="styles_cetak.css" rel="stylesheet" type="text/css">
	<title>Cetak Bon Ganti Kain</title>
	<script>
	// set portrait orientation

	jsPrintSetup.setOption('orientation', jsPrintSetup.kPortraitOrientation);

	// set top margins in millimeters
	jsPrintSetup.setOption('marginTop', 0);
	jsPrintSetup.setOption('marginBottom', 0);
	jsPrintSetup.setOption('marginLeft', 0);
	jsPrintSetup.setOption('marginRight', 0);

	// set page header
	jsPrintSetup.setOption('headerStrLeft', '');
	jsPrintSetup.setOption('headerStrCenter', '');
	jsPrintSetup.setOption('headerStrRight', '');

	// set empty page footer
	jsPrintSetup.setOption('footerStrLeft', '');
	jsPrintSetup.setOption('footerStrCenter', '');
	jsPrintSetup.setOption('footerStrRight', '');

	// clears user preferences always silent print value
	// to enable using 'printSilent' option
	jsPrintSetup.clearSilentPrint();

	// Suppress print dialog (for this context only)
	jsPrintSetup.setOption('printSilent', 1);

	// Do Print 
	// When print is submitted it is executed asynchronous and
	// script flow continues after print independently of completetion of print process! 
	jsPrintSetup.print();

	window.addEventListener('load', function() {
		var rotates = document.getElementsByClassName('rotate');
		for (var i = 0; i < rotates.length; i++) {
			rotates[i].style.height = rotates[i].offsetWidth + 'px';
		}
	});
	// next commands
	</script>
	<style>
	.hurufvertical {
		writing-mode: tb-rl;
		-webkit-transform: rotate(-90deg);
		-moz-transform: rotate(-90deg);
		-o-transform: rotate(-90deg);
		-ms-transform: rotate(-90deg);
		transform: rotate(180deg);
		white-space: nowrap;
		float: left;
	}

	input {
		text-align: center;
		border: hidden;
		font-size: 9px;
		font-family: sans-serif, Roman, serif;
	}
	
	@media print {
		::-webkit-input-placeholder {
			/* WebKit browsers */
			color: transparent;
		}

		:-moz-placeholder {
			/* Mozilla Firefox 4 to 18 */
			color: transparent;
		}

		::-moz-placeholder {
			/* Mozilla Firefox 19+ */
			color: transparent;
		}

		:-ms-input-placeholder {
			/* Internet Explorer 10+ */
			color: transparent;
		}

		.pagebreak {
			page-break-before: always;
		}

		.header {
			display: block
		}

		table thead {
			display: table-header-group;
		}
	}
	</style>
</head>
<?php
$nmBln = array(1 => "JANUARI", "FEBUARI", "MARET", "APRIL", "MEI", "JUNI", "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DESEMBER");
?>

<body>
	<table>
		<thead>
			<tr>
				<td>
					<table border="1" class="table-list1" style="width:8in">
						<tr>
							<td width="10%" align="center"><img src="Indo.jpg" width="50" height="50
    " alt="" /></td>
							<td width="58%" align="center"><strong>
									<font size="+1">BON GANTI /TAMBAH KAIN GREIGE</font>
								</strong></td>
							<td width="32%" align="center">
								<table width="100%">
									<tbody>
										<tr>
											<td width="36%" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">No. Form</td>
											<td width="5%" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">:</td>
											<td width="59%" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">14-10</td>
										</tr>
										<tr>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">No Revisi</td>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">:</td>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">04</td>
										</tr>
										<tr>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Tgl. Terbit</td>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">:</td>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">17 Desember 2020</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</table>

				</td>
			</tr>
		</thead>

		<tr>
			<td>
				<table border="0" class="table-list1" style="width:8in">
					<tbody>
						<tr>
							<td width="11%" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">&nbsp;</td>
							<td width="39%" align="center" valign="top" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">
								<table width="77%">
									<tbody>
										<tr>
											<td width="11%" align="right" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php if($r['kategori']=="0"){echo "&#9745;";}else{echo "&#9744;";} ?></td>
											<td width="89%" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Internal</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="18%" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">&nbsp;</td>
							<td width="13%" align="center" valign="top" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">
								<table width="66%">
									<tbody>
										<tr>
											<td width="22%" align="right" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php if($r['kategori']=="1"){echo "&#9745;";}else{echo "&#9744;";} ?></td>
											<td width="78%" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">External</td>
										</tr>
										<tr>
											<td align="right" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php if($r['kategori']=="2"){echo "&#9745;";}else{echo "&#9744;";} ?></td>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">FOC</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="5%" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">&nbsp;</td>
							<td width="14%" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">&nbsp;</td>
						</tr>
						<tr valign="top">
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Langganan</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">: <?php echo $r['langganan']; ?></td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">No. Hanger</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">: <?php echo $r['no_hanger']; ?></td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Style</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">: <?php echo $r['style']; ?></td>
						</tr>
						<tr valign="top">
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">No. PO</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">: <?php echo $r['po']; ?></td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Lebar X Gramasi</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">: <?php echo $r['lebar'] . " X " . $r['gramasi']; ?></td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Lot</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">: <?php echo $r['lot']; ?></td>
						</tr>
						<tr valign="top">
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">No. Order</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">: <?php echo $r['no_order']; ?></td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Delivery Kain Greige</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">:</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">&nbsp;</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">&nbsp;</td>
						</tr>
						<tr valign="top">
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Jenis Kain</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">: <?php echo $r['no_item'] . "/" . $r['jenis_kain']; ?></td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Delivery Kain Jadi</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">: <?php echo $r['tgl_delivery']; ?></td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">&nbsp;</td>
							<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		</tbody>
		<tr>
			<td>
				<table border="0" class="table-list1" style="width:8in">
					<tbody>
						<tr>
							<td width="11%" rowspan="2" align="left" valign="top">Departemen Penanggung Jawab: <span
									style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php if ($r['t_jawab'] != "") {
    echo "<br>" . $r['t_jawab'] . " " . $r['persen'] . " %";
  }
  if ($r['t_jawab1'] != "") {
    echo "<br>" . $r['t_jawab1'] . " " . $r['persen1'] . " %";
  }
  if ($r['t_jawab2'] != "") {
    echo "<br>" . $r['t_jawab2'] . " " . $r['persen2'] . " %";
  }
  if ($r['t_jawab3'] != "") {
    echo "<br>" . $r['t_jawab3'] . " " . $r['persen3'] . " %";
  }
  if ($r['t_jawab4'] != "") {
    echo "<br>" . $r['t_jawab4'] . " " . $r['persen4'] . " %";
  }								
								?></span> 
							</td>
							<td height="60" colspan="3" valign="top" style="height: 0.7in;">Masalah: <span style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php echo $r['masalah']; ?></span></td>
							<?php 
                  $dtArr=$r['sebab_analisa'];
                  $data = explode(",",$dtArr);
				  $lowercaseData = array_map('strtolower', $data);
                ?>
							<td width="17%" rowspan="2" align="left" valign="top">Penyebab: <br />
								<table width="100%">
									<tbody>
										<tr>
											<td width="14%" align="right" valign="top" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php if(in_array("man",$lowercaseData)){ echo "&#9745;";}else{ echo "&#9744;";}?></td>
											<td width="86%" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Man</td>
										</tr>
										<tr>
											<td align="right" valign="top" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php if(in_array("methode",$lowercaseData)){ echo "&#9745;";}else{ echo "&#9744;";}?></td>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Methode</td>
										</tr>
										<tr>
											<td align="right" valign="top" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php if(in_array("machine",$lowercaseData)){ echo "&#9745;";}else{ echo "&#9744;";}?></td>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Machine</td>
										</tr>
										<tr>
											<td align="right" valign="top" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php if(in_array("material",$lowercaseData)){ echo "&#9745;";}else{ echo "&#9744;";}?></td>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Material</td>
										</tr>
										<tr>
											<td align="right" valign="top" style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php if(in_array("environment",$lowercaseData)){ echo "&#9745;";}else{ echo "&#9744;";}?></td>
											<td style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">Environment</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td colspan="4" align="left" valign="top">Analisa: <span style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php echo $r['analisa']; ?></span></td>
						</tr>
						<tr>
							<td colspan="3" valign="top" style="height: 0.7in;">Alasan: <span style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php echo $r['alasan']; ?></span></td>
							<td colspan="4" align="left" valign="top">Pencegahan: <span style="border-top:0px #000000 solid; 
  border-bottom:0px #000000 solid;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php echo $r['pencegahan']; ?></span></td>
						</tr>
						<tr>
							<td colspan="3" valign="top" style="height: 0.9in; border-right:0px #000000 solid;">1. Warna
								= <span style="font-size: 8px;"><?php echo $r['warna1']; ?> <br><?php if(!empty($r['warna1'])){ echo $r['no_warna'];} ?>
								<br><?php echo $r['nokk']; ?>
								<br><?php echo $r['nodemand']; ?></span><br>
								<br /><?php $qr1 = sqlsrv_query($cona, "SELECT * FROM db_adm.tbl_gantikain WHERE nokk='$r[nokk1]'");
                $rk1 = sqlsrv_fetch_array($qr1, SQLSRV_FETCH_ASSOC); ?>
								O: <?php echo $rk1['qty_order']; ?><br>
								K: <?php echo $rk1['qty_kirim']; ?><br>
								E: <?php echo $rk1['qty_foc']; ?>
							</td>
							<td width="11%" align="right" valign="top" style="height: 0.8in;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><?php echo $r['kg1']; ?> Kg<br><?php echo $r['pjg1'] . " " . $r['satuan1']; ?>
								<?php if ($r['pjg1'] != "") { ?><br>(Netto)<?php } ?>
							</td>
							<td colspan="2" valign="top" style="height: 0.8in; border-right:0px #000000 solid;">2. Warna
								= <span style="font-size: 8px;"><?php echo $r['warna2']; ?><br><?php if(!empty($r['warna2'])){ echo $r['no_warna'];} ?></span><br>
								<br />
								<?php $qr2 = sqlsrv_query($cona, "SELECT * FROM db_adm.tbl_gantikain WHERE nokk='$r[nokk2]'");
                $rk2 = sqlsrv_fetch_array($qr2, SQLSRV_FETCH_ASSOC); ?>
								O: <?php echo $rk2['qty_order']; ?><br>
								K: <?php echo $rk2['qty_kirim']; ?><br>
								E: <?php echo $rk2['qty_foc']; ?>
							</td>
							<td width="13%" align="right" valign="top" style="height: 0.8in; border-left:0px #000000 solid; 
  border-right:0px #000000 solid;"><span style="height: 0.8in;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">
									<?php if ($r['kg2'] > 0) { ?>
									<?php echo $r['kg2']; ?></span>Kg<br />
								<?php echo $r['pjg2']; ?> <?php echo $r['satuan2']; ?><br />
								(Netto)
								<?php } else {
                    echo "Kg";
                  } ?>
							</td>
							<td width="20%" valign="top" style="border-right:0px #000000 solid;"><span
									style="height: 0.8in; border-right:0px #000000 solid;">3. Warna = <span
										style="font-size: 8px;"><?php echo $r['warna3']; ?><br><?php if(!empty($r['warna3'])){ echo $r['no_warna'];} ?></span><br>
									<br />
									<?php $qr3 = sqlsrv_query($cona, "SELECT * FROM db_adm.tbl_gantikain WHERE nokk='$r[nokk3]'");
                  $rk3 = sqlsrv_fetch_array($qr3, SQLSRV_FETCH_ASSOC); ?>
									O: <?php echo $rk3['qty_order']; ?><br>
									K: <?php echo $rk3['qty_kirim']; ?><br>
									E: <?php echo $rk3['qty_foc']; ?></td>
							<td width="13%" align="right" valign="top" style="border-left:0px #000000 solid;"><span
									style="height: 0.8in;
  border-left:0px #000000 solid; 
  border-right:0px #000000 solid;">
									<?php if ($r['kg3'] > 0) { ?>
									<?php echo $r['kg3']; ?></span> Kg<br />
								<?php echo $r['pjg3']; ?> <?php echo $r['satuan3']; ?><br />
								(Netto)
								<?php } else {
                    echo "Kg";
                  } ?>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table style="width:8in" border="0" class="table-list1">
					<tr align="center">
						<td width="14%" rowspan="2">&nbsp;</td>
						<td width="17%" rowspan="2">Dibuat Oleh :</td>
						<td colspan="6">Diketahui Oleh:</td>
					</tr>
					<tr align="center">
						<td width="14%">PPC</td>
						<td width="11%">RMP</td>
						<td width="11%">MKT</td>
						<td width="11%">DMF</td>
						<td width="11%">DMK</td>
						<td width="11%">Asst. PRD</td>
					</tr>
					<tr>
						<td>Nama</td>
						<td align="center"><input name="nama5" type="text" placeholder="Ketik" size="12" /></td>
						<td align="center"><input name="nama13" type="text" placeholder="Ketik" size="10" /></td>
						<td align="center"><input name="nama3" type="text" placeholder="Ketik" size="10" /></td>
						<td align="center"><input name="nama6" type="text" placeholder="Ketik" size="10" /></td>
						<td align="center"><input name="nama8" type="text" placeholder="Ketik" size="10" /></td>
						<td align="center"><input name="nama10" type="text" placeholder="Ketik" size="10" /></td>
						<td align="center"><input name="nama12" type="text" placeholder="Ketik" size="10" /></td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td align="center"><input name="nama" type="text" placeholder="Ketik" size="12" /></td>
						<td align="center"><input name="nama2" type="text" placeholder="Ketik" size="10" /></td>
						<td align="center"><input name="nama4" type="text" placeholder="Ketik" size="10" /></td>
						<td align="center"><input name="nama7" type="text" placeholder="Ketik" size="10" /></td>
						<td align="center"><input name="nama9" type="text" placeholder="Ketik" size="10" /></td>
						<td align="center"><input name="nama11" type="text" placeholder="Ketik" size="10" /></td>
						<td align="center"><input name="nama14" type="text" placeholder="Ketik" size="10" /></td>
					</tr>
					<tr>
						<td>Tanggal</td>
						<td align="center"><?php echo date("d F Y", strtotime($rTgl['tgl_skrg'])); ?></td>
						<td align="center"><?php echo date("F Y", strtotime($rTgl['tgl_skrg'])); ?></td>
						<td align="center"><?php echo date("F Y", strtotime($rTgl['tgl_skrg'])); ?></td>
						<td align="center"><?php echo date("F Y", strtotime($rTgl['tgl_skrg'])); ?></td>
						<td align="center"><?php echo date("F Y", strtotime($rTgl['tgl_skrg'])); ?></td>
						<td align="center"><?php echo date("F Y", strtotime($rTgl['tgl_skrg'])); ?></td>
						<td align="center"><?php echo date("F Y", strtotime($rTgl['tgl_skrg'])); ?></td>
					</tr>
					<tr>
						<td valign="top" style="height: 0.6in;">TandaTangan</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>

	</table><br /><?php echo $_GET['no_bon']; echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "print out: " . date('Y-m-d H:i:s');  ?>
	<script>
	//alert('cetak');window.print();
	</script>
</body>

</html>