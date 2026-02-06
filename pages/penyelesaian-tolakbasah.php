
<?php
function sanitize_input_sql($input) {
    $input = trim($input);
    $input = str_replace("'", "`", $input);
    $input = str_replace('"', '``', $input);
    $input = str_replace('--', '', $input);
    $input = preg_replace('/[^\w\s.,;:()\-]/u', '', $input);

    return $input;
}

ini_set("error_reporting", 1);
session_start();
include_once __DIR__ . "/../koneksi.php";

$id = $_GET['id'] ?? '';
$TindakLanjut   = isset($_POST['tindak_lanjut']) ? $_POST['tindak_lanjut'] : '';
$SolusiPanjang  = isset($_POST['hasil_tindak_lanjut']) ? $_POST['hasil_tindak_lanjut'] : '';
$opt = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
$sqlCekText = "SELECT TOP 1
									* 
								FROM 
									db_qc.tbl_cocok_warna_dye t
								LEFT JOIN db_qc.penyelesaian_tolakbasah p ON p.id_cocok_warna = t.id
									WHERE t.id = ?
								ORDER BY 
									t.id 
								DESC";
$sqlCek = sqlsrv_query($cond, $sqlCekText, [$id], $opt);
if ($sqlCek === false) {
	die(print_r(sqlsrv_errors(), true));
}
$rcek = sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC);
$cek = $rcek ? 1 : 0;
sqlsrv_free_stmt($sqlCek);

$qcek_data = sqlsrv_query($cond, "SELECT TOP 1 * FROM db_qc.penyelesaian_tolakbasah WHERE id_cocok_warna = ? ORDER BY id DESC", [$id], $opt);
if ($qcek_data === false) {
	die(print_r(sqlsrv_errors(), true));
}
$row_cek_data = sqlsrv_fetch_array($qcek_data, SQLSRV_FETCH_ASSOC);
$cek_data = $row_cek_data ? 1 : 0;
sqlsrv_free_stmt($qcek_data);
?>
<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Input Penyelesaian</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<!-- col -->
			<div class="col-md-12">
				<!-- <div class="form-group">
					<label for="ket" class="col-sm-2 control-label">Keterangan</label>
					<div class="col-sm-4">
						<textarea name="ket" rows="3" class="form-control" id="ket" placeholder="Keterangan"><?php if ($cek > 0) {
																													echo $rcek['ket_penyelesaian'];
																												} ?></textarea>
					</div>
				</div> -->
				<div class="form-group">
					<label for="pemberi_instruksi" class="col-sm-2 control-label">Pemberi Instruksi Tindak Lanjut</label>
					<div class="col-sm-3">
						<select class="form-control select2" name="pemberi_instruksi" id="pemberi_instruksi" required>
							<option value="">Pilih</option>
							<?php 
							$list_nama = "SELECT id, nama FROM db_adm.tbl_user_tindaklanjut t WHERE t.status_active = '1'";
							$q_nama = sqlsrv_query($cona, $list_nama);
							while ($r_nama = sqlsrv_fetch_array($q_nama, SQLSRV_FETCH_ASSOC)) { 
								$selected = ($rcek['pemberi_instruksi'] == $r_nama['id']) ? 'selected' : '';
							?>
								<option value="<?php echo $r_nama['id']; ?>" <?php echo $selected; ?>>
									<?php echo $r_nama['nama']; ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="hasil_tindak_lanjut" class="col-sm-2 control-label">Hasil Tindak Lanjut</label>
					<div class="col-sm-2">
						<select class="form-control select2" name="hasil_tindak_lanjut">
							<option value="">Pilih</option>
							<option value="OK" <?php if ($rcek['hasil_tindak_lanjut'] == "OK") {
															echo "SELECTED";
														} ?>>OK</option>
							<option value="NCP" <?php if ($rcek['hasil_tindak_lanjut'] == "NCP") {
																	echo "SELECTED";
																} ?>>NCP</option>
							<option value="OK Disposisi" <?php if ($rcek['hasil_tindak_lanjut'] == "OK Disposisi") {
														echo "SELECTED";
													} ?>>OK Disposisi</option>
							<option value="On Progress" <?php if ($rcek['hasil_tindak_lanjut'] == "On Progress") {
															echo "SELECTED";
														} ?>>On Progress</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="tindakan" class="col-sm-2 control-label">Tindakan</label>
					<div class="col-sm-2">
						<select class="form-control select2" name="tindakan">
							<option value="">Pilih</option>
							<option value="Celup Ulang" <?php if ($rcek['tindakan'] == "Celup Ulang") {
															echo "SELECTED";
														} ?>>Celup Ulang</option>
							<!-- <option value="Tidak Celup Ulang" <?php if ($rcek['tindakan'] == "Tidak Celup Ulang") {
																	echo "SELECTED";
																} ?>>Tidak Celup Ulang</option>
							<option value="Upgrade" <?php if ($rcek['tindakan'] == "Upgrade") {
														echo "SELECTED";
													} ?>>Upgrade</option> -->
							<option value="Disposisi" <?php if ($rcek['tindakan'] == "Disposisi") {
															echo "SELECTED";
														} ?>>Disposisi</option>
							<!-- <option value="BS" <?php if ($rcek['tindakan'] == "BS") {
													echo "SELECTED";
												} ?>>BS</option>
							<option value="Tunggu Conform" <?php if ($rcek['tindakan'] == "Tunggu Conform") {
																echo "SELECTED";
															} ?>>Tunggu Conform</option> -->
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="tindak_lanjut" class="col-sm-2 control-label">Tindak Lanjut</label>
					<div class="col-sm-3">
						<input name="tindak_lanjut" type="text" class="form-control" id="tindak_lanjut" value="<?php if($TindakLanjut!=""){ echo $TindakLanjut; } else { echo $rcek['tindak_lanjut']; } ?>" placeholder="Tindak Lanjut" >
					</div>
				</div>
				
		<div class="box-footer">
			<button type="button" class="btn btn-default pull-left" name="save" Onclick="window.location='?p=LapTolakBasah'">Kembali <i class="fa fa-cycle"></i></button>
			<input type="submit" value="Simpan" name="save" id="save" class="btn btn-primary pull-right">
		</div>
		<!-- /.box-footer -->
	</div>
</form>
</div>
</div>
</div>
</div>
<?php
if (isset($_POST['save']) && $_POST['save'] == "Simpan" && $cek_data < 1) {
	$user_insert = $_SESSION['id10'];
	$ket = sanitize_input_sql($_POST['keterangan'] ?? '');
	$hasil_lanjut = sanitize_input_sql($_POST['hasil_tindak_lanjut']);
	$tindak_lanjut = sanitize_input_sql($_POST['tindak_lanjut']);
	$pemberi_instruksi = sanitize_input_sql($_POST['pemberi_instruksi']);
	$tindakan = sanitize_input_sql($_POST['tindakan']);
	$sqlInsert = "INSERT INTO db_qc.penyelesaian_tolakbasah
					(id_cocok_warna, keterangan, hasil_tindak_lanjut, tindak_lanjut, pemberi_instruksi, tindakan, tanggal_insert, tanggal_update, user_insert, user_update)
				  VALUES (?, ?, ?, ?, ?, ?, GETDATE(), GETDATE(), ?, ?)";
	$paramsInsert = [
		$id,
		$ket,
		$hasil_lanjut,
		$tindak_lanjut,
		$pemberi_instruksi,
		$tindakan,
		$user_insert,
		$user_insert
	];
	$sqlData = sqlsrv_query($cond, $sqlInsert, $paramsInsert);
	if ($sqlData === false) {
		die(print_r(sqlsrv_errors(), true));
	}
	if ($sqlData) {
		echo "<script>swal({
				title: 'Data Telah Tersimpan',   
				text: 'Klik Ok untuk input data kembali',
				type: 'success',
				}).then((result) => {
				if (result.value) {
						window.location.href='?p=Penyelesaian-tolakbasah&id=$id';
					
				}
				});</script>";
	}
} else if (isset($_POST['save']) && $_POST['save'] == "Simpan" && $cek_data > 0) {
	$user_insert = $_SESSION['id10'];
	$ket = sanitize_input_sql($_POST['keterangan'] ?? '');
	$hasil_lanjut = sanitize_input_sql($_POST['hasil_tindak_lanjut']);
	$tindak_lanjut = sanitize_input_sql($_POST['tindak_lanjut']);
	$pemberi_instruksi = sanitize_input_sql($_POST['pemberi_instruksi']);
	$tindakan = sanitize_input_sql($_POST['tindakan']);
	$sqlUpdate = "UPDATE db_qc.penyelesaian_tolakbasah SET 
					keterangan = ?,
					hasil_tindak_lanjut = ?,
					tindak_lanjut = ?,
					pemberi_instruksi = ?,
					tindakan = ?,
					tanggal_update = GETDATE(),
					user_update = ?
				  WHERE id_cocok_warna = ?";
	$paramsUpdate = [
		$ket,
		$hasil_lanjut,
		$tindak_lanjut,
		$pemberi_instruksi,
		$tindakan,
		$user_insert,
		$id
	];
	$sqlData = sqlsrv_query($cond, $sqlUpdate, $paramsUpdate);
	if ($sqlData === false) {
		die(print_r(sqlsrv_errors(), true));
	}
	if ($sqlData) {
		echo "<script>swal({
				title: 'Data Telah Terupdate',   
				text: 'Klik Ok untuk input data kembali',
				type: 'success',
				}).then((result) => {
				if (result.value) {
						window.location.href='?p=Penyelesaian-tolakbasah&id=$id';
					
				}
				});</script>";
	}
}
?>
