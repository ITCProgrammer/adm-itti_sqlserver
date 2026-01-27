
<?php
function sanitize_input_sql($input) {
    $input = trim($input);
    $input = str_replace("'", "`", $input);
    $input = str_replace('"', '``', $input);
    $input = str_replace('--', '', $input);
    $input = preg_replace('/[^\w\s.,;:()\-]/u', '', $input);

    return $input;
}
include"koneksi2.php";
ini_set("error_reporting", 1);
session_start();
$id_schedule = $_GET['schedule'];
$id_montemp = $_GET['montemp'];
$id_hasil_celup = $_GET['hasil_celup'];
$TindakLanjut   = isset($_POST['tindak_lanjut']) ? $_POST['tindak_lanjut'] : '';
$SolusiPanjang  = isset($_POST['hasil_tindak_lanjut']) ? $_POST['hasil_tindak_lanjut'] : '';
$sqlCekText = "SELECT *
				FROM db_dying.tbl_schedule b
				LEFT JOIN db_dying.tbl_montemp c ON c.id_schedule = b.id
				LEFT JOIN db_dying.tbl_hasilcelup a ON a.id_montemp = c.id
				LEFT JOIN db_dying.penyelesaian_gagalproses p
				ON p.id_schedule = b.id
				AND p.id_montemp = c.id
				AND p.id_hasil_celup = a.id
				WHERE a.status = ?
				AND b.id = ?
				AND c.id = ?
				AND a.id = ?";

	$paramsCek = ['Gagal Proses', $id_schedule, $id_montemp, $id_hasil_celup];
	$stmtCek = sqlsrv_query($con, $sqlCekText, $paramsCek);

	if ($stmtCek === false) {
	die(print_r(sqlsrv_errors(), true));
	}

	$rcek = sqlsrv_fetch_array($stmtCek, SQLSRV_FETCH_ASSOC);
	$cek  = $rcek ? 1 : 0;

	sqlsrv_free_stmt($stmtCek);

$sqlCekDataText = "SELECT TOP 1 *
						FROM db_dying.penyelesaian_gagalproses
						WHERE id_schedule = ?
						AND id_montemp = ?
						AND id_hasil_celup = ?
						ORDER BY id DESC";

$paramsCekData = [$id_schedule, $id_montemp, $id_hasil_celup];
$stmtCekData = sqlsrv_query($con, $sqlCekDataText, $paramsCekData);

if ($stmtCekData === false) {
  die(print_r(sqlsrv_errors(), true));
}

$rowCekData = sqlsrv_fetch_array($stmtCekData, SQLSRV_FETCH_ASSOC);
$cek_data   = $rowCekData ? 1 : 0;

sqlsrv_free_stmt($stmtCekData);

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
					<label for="tindak_lanjut" class="col-sm-2 control-label">Tindak Lanjut</label>
					<div class="col-sm-3">
						<input name="tindak_lanjut" type="text" class="form-control" id="tindak_lanjut" value="<?php if($TindakLanjut!=""){ echo $TindakLanjut; } else { echo $rcek['tindak_lanjut']; } ?>" placeholder="Tindak Lanjut" >
					</div>
				</div>
				<div class="form-group">
					<label for="pemberi_instruksi" class="col-sm-2 control-label">Pemberi Instruksi Tindak Lanjut</label>
					<div class="col-sm-3">
						<select class="form-control select2" name="pemberi_instruksi" id="pemberi_instruksi" required>
							<option value="">Pilih</option>
							<?php 
							$sqlNama = "SELECT id, nama
										FROM db_adm.tbl_user_tindaklanjut
										WHERE status_active = 1
										ORDER BY nama";

							$stmtNama = sqlsrv_query($cona, $sqlNama);
							if ($stmtNama === false) { die(print_r(sqlsrv_errors(), true)); }

							while ($r_nama = sqlsrv_fetch_array($stmtNama, SQLSRV_FETCH_ASSOC)) {
								$selected = ($rcek['pemberi_instruksi'] == $r_nama['id']) ? 'selected' : '';
								?>
								<option value="<?= htmlspecialchars($r_nama['id']) ?>" <?= $selected ?>>
									<?= htmlspecialchars($r_nama['nama']) ?>
								</option>
								<?php
							}
							sqlsrv_free_stmt($stmtNama);
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="hasil_tindak_lanjut" class="col-sm-2 control-label">Hasil Tindak Lanjut</label>
					<div class="col-sm-3">
						<select class="form-control select2" name="hasil_tindak_lanjut">
							<option value="">Pilih</option>
							<option value="OK" <?php if ($rcek['hasil_tindak_lanjut'] == "OK") { echo "SELECTED"; } ?>>OK</option>
							<option value="NCP" <?php if ($rcek['hasil_tindak_lanjut'] == "NCP") { echo "SELECTED"; } ?>>NCP</option>
							<option value="OK DISPOSISI" <?php if ($rcek['hasil_tindak_lanjut'] == "OK DISPOSISI") { echo "SELECTED"; } ?>>OK DISPOSISI</option>
							<option value="ON PROGRESS" <?php if ($rcek['hasil_tindak_lanjut'] == "ON PROGRESS") { echo "SELECTED"; } ?>>ON PROGRESS</option>
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
					<div class="col-sm-4">
						<textarea name="keterangan" rows="3" class="form-control" id="keterangan" placeholder="Keterangan"><?php if ($cek > 0) {
																													echo $rcek['keterangan'];
																												} ?></textarea>
					</div>
				</div>
				
		<div class="box-footer">
			<button type="button" class="btn btn-default pull-left" name="save" Onclick="window.location='?p=LapGagalProses'">Kembali <i class="fa fa-cycle"></i></button>
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
if ($_POST['save'] == "Simpan" && $cek_data<1) {
	$user_insert = $_SESSION['id10'];
	$ket = sanitize_input_sql($_POST['keterangan']);
	$hasil_lanjut = sanitize_input_sql($_POST['hasil_tindak_lanjut']);
	$tindak_lanjut = sanitize_input_sql($_POST['tindak_lanjut']);
	$pemberi_instruksi = sanitize_input_sql($_POST['pemberi_instruksi']);
	$tindakan = sanitize_input_sql($_POST['tindakan']);
	$sqlInsert = "INSERT INTO db_dying.penyelesaian_gagalproses 
						(id_hasil_celup, id_montemp, id_schedule, keterangan, hasil_tindak_lanjut, tindak_lanjut, pemberi_instruksi, tindakan, tanggal_insert, tanggal_update, user_insert, user_update)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, GETDATE(), GETDATE(), ?, ?)
				";

				$paramsInsert = [
				$id_hasil_celup,
				$id_montemp,
				$id_schedule,
				$ket,
				$hasil_lanjut,
				$tindak_lanjut,
				$pemberi_instruksi,
				$tindakan,
				(int)$user_insert,
				(int)$user_insert
				];

				$stmtInsert = sqlsrv_query($con, $sqlInsert, $paramsInsert);
				if ($stmtInsert === false) {
				die(print_r(sqlsrv_errors(), true));
				}
				sqlsrv_free_stmt($stmtInsert);

	if ($stmtInsert) {
		echo "<script>swal({
				title: 'Data Telah Tersimpan',   
				text: 'Klik Ok untuk input data kembali',
				type: 'success',
				}).then((result) => {
				if (result.value) {
						window.location.href='?p=Penyelesaian-gagalproses&schedule=$id_schedule&montemp=$id_montemp&hasil_celup=$id_hasil_celup';
					
				}
				});</script>";
	}
}else if ($_POST['save'] == "Simpan" && $cek_data>0) {
	$user_insert = $_SESSION['id10'];
	$ket = sanitize_input_sql($_POST['keterangan']);
	$hasil_lanjut = sanitize_input_sql($_POST['hasil_tindak_lanjut']);
	$tindak_lanjut = sanitize_input_sql($_POST['tindak_lanjut']);
	$pemberi_instruksi = sanitize_input_sql($_POST['pemberi_instruksi']);
	$tindakan = sanitize_input_sql($_POST['tindakan']);
	$sqlUpdate = " UPDATE db_dying.penyelesaian_gagalproses
					SET
					keterangan = ?,
					hasil_tindak_lanjut = ?,
					tindak_lanjut = ?,
					pemberi_instruksi = ?,
					tindakan = ?,
					tanggal_update = GETDATE(),
					user_update = ?
					WHERE id_hasil_celup = ?
					AND id_montemp = ?
					AND id_schedule = ?
					";

					$paramsUpdate = [
					$ket,
					$hasil_lanjut,
					$tindak_lanjut,
					$pemberi_instruksi,
					$tindakan,
					(int)$user_insert,
					$id_hasil_celup,
					$id_montemp,
					$id_schedule
					];

					$stmtUpdate = sqlsrv_query($con, $sqlUpdate, $paramsUpdate);
					if ($stmtUpdate === false) {
					die(print_r(sqlsrv_errors(), true));
					}
	if ($stmtUpdate) {
		echo "<script>swal({
				title: 'Data Telah Terupdate',   
				text: 'Klik Ok untuk input data kembali',
				type: 'success',
				}).then((result) => {
				if (result.value) {
						window.location.href='?p=Penyelesaian-gagalproses&schedule=$id_schedule&montemp=$id_montemp&hasil_celup=$id_hasil_celup';
					
				}
				});</script>";
	}
}
?>