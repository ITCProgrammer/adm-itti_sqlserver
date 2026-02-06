<?php
ini_set("error_reporting", 1);
session_start();
$id = $_GET['id'];
$AkarMasalah   = isset($_POST['akar_masalah']) ? $_POST['akar_masalah'] : '';
$SolusiPanjang = isset($_POST['solusi_panjang']) ? $_POST['solusi_panjang'] : '';
$opt = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
$sqlCek = sqlsrv_query($cond,"SELECT TOP 1 * FROM db_qc.tbl_ncp_qcf_now WHERE id = ? ORDER BY id DESC", array($id),$opt);
$cek  = sqlsrv_num_rows($sqlCek);
$rcek = sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC);
?>

<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Input Tindakan Penyelesaian</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<!-- col -->
			<div class="col-md-12">
				<?php if (strtoupper($_SESSION['user_id10']) == "ADM-DYE" or strtolower($_SESSION['user_id10']) == "mucharom.mustofa" or strtoupper($_SESSION['user_id10']) == "QCF") { ?>
					<div class="form-group">
						<label for="no_ncp" class="col-sm-2 control-label">No NCP</label>
						<div class="col-sm-2">
							<input name="no_ncp" type="text" class="form-control" id="no_ncp" value="<?= $rcek['no_ncp_gabungan']; ?>" placeholder="No NCP">
						</div>
					</div>
					<?php
						$nokk = $_POST['nokk'] ?? '';
						$serverName = "10.0.0.221";
						$connectionOptions = array(
							"Database" => "db_dying",
							"UID"      => "sa",
							"PWD"      => "Ind@taichen2024",
							"CharacterSet" => "UTF-8"
						);
						$con1 = sqlsrv_connect($serverName, $connectionOptions);
						if ($con1 === false) {
							die(print_r(sqlsrv_errors(), true));
						}
						$opt = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
						$qry1 = sqlsrv_query(
							$con1,
							"SELECT TOP 1
								a.no_mesin,
								b.g_shift,
								b.colorist
							FROM db_dying.dbo.tbl_schedule a
							LEFT JOIN db_dying.dbo.tbl_montemp b ON a.id = b.id_schedule
							WHERE a.nokk = ? AND a.proses = ?
							ORDER BY a.id DESC",
							array($nokk, 'Celup Greige'),
							$opt
						);
						if ($qry1 === false) {
							die(print_r(sqlsrv_errors(), true));
						}
						$dtDye = sqlsrv_fetch_array($qry1, SQLSRV_FETCH_ASSOC);
						$cDye  = sqlsrv_num_rows($qry1);
						$qryHC = sqlsrv_query(
							$con1,
							"SELECT TOP 1 *
							FROM db_dying.dbo.tbl_hasilcelup
							WHERE nokk = ? AND proses = ?
							ORDER BY id DESC",
							array($nokk, 'Celup Greige'),
							$opt
						);
						if ($qryHC === false) {
							die(print_r(sqlsrv_errors(), true));
						}
						$dtHC = sqlsrv_fetch_array($qryHC, SQLSRV_FETCH_ASSOC);
						$cHC  = sqlsrv_num_rows($qryHC);
					?>
					<div class="form-group">
						<label for="nokk" class="col-sm-2 control-label">No KK</label>
						<div class="col-sm-2">
							<div class="input-group">
								<input name="nokk" type="text" class="form-control" id="nokk" value="<?= $_POST['nokk']; ?>" placeholder="No KK">
								<span class="input-group-btn"><button type="submit" class="btn btn-default" name="cek" id="cek" value="cek"> <span class="fa fa-search"></span></button></span>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="form-group">
					<label for="tgl_rencana" class="col-sm-2 control-label">Tgl. Rencana Penyelesaian</label>
					<div class="col-sm-2">
						<div class="input-group date">
							<div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
							<input name="tgl_rencana" type="text" class="form-control pull-right" id="datepicker" placeholder="0000-00-00" value="<?php echo ($rcek['tgl_rencana'] instanceof DateTime) ? $rcek['tgl_rencana']->format('Y-m-d') : $rcek['tgl_rencana']; ?>" autocomplete="off" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="tgl_kembali_qcf" class="col-sm-2 control-label">Tgl. Kembali Ke QCF</label>
					<div class="col-sm-2">
						<div class="input-group date">
							<div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
							<input name="tgl_kembali_qcf" type="text" class="form-control pull-right" id="datepicker2" placeholder="0000-00-00" value="<?php echo ($rcek['tgl_kembali_qcf'] instanceof DateTime) ? $rcek['tgl_kembali_qcf']->format('Y-m-d') : $rcek['tgl_kembali_qcf']; ?>" autocomplete="off" />
						</div>
					</div>
				</div>
				<?php if($_SESSION['user_id10'] == 'adm-cqa'):?>
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
					<label for="akar_penyebab" class="col-sm-2 control-label">Akar Masalah</label>
					<div class="col-sm-3">
						<select class="form-control select2" name="akar_penyebab">
							<option value="">Pilih</option>
							<option value="MAN" <?php if ($rcek['akar_penyebab'] == "MAN") {
															echo "SELECTED";
														} ?>>MAN</option>
							<option value="MACHINE" <?php if ($rcek['akar_penyebab'] == "MACHINE") {
																	echo "SELECTED";
																} ?>>MACHINE</option>
							<option value="METHODE" <?php if ($rcek['akar_penyebab'] == "METHODE") {
														echo "SELECTED";
													} ?>>METHODE</option>
							<option value="MATERIAL" <?php if ($rcek['akar_penyebab'] == "MATERIAL") {
															echo "SELECTED";
														} ?>>MATERIAL</option>
							<option value="ENVIRONMENT" <?php if ($rcek['akar_penyebab'] == "ENVIRONMENT") {
															echo "SELECTED";
														} ?>>ENVIRONMENT</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="akar_masalah" class="col-sm-2 control-label">Rincian Akar Masalah</label>
					<div class="col-sm-3">
						<input name="akar_masalah" type="text" class="form-control" id="akar_masalah" value="<?php if($AkarMasalah!=""){ echo $AkarMasalah; } else { echo $rcek['akar_masalah']; } ?>" placeholder="Rincian Akar Masalah" required>
					</div>
				</div>
				<?php endif;?>
				<div class="form-group">
					<label for="ket" class="col-sm-2 control-label">Keterangan</label>
					<div class="col-sm-4">
						<textarea name="ket" rows="3" class="form-control" id="ket" placeholder="Keterangan"><?php if ($cek > 0) {
																													echo $rcek['ket_penyelesaian'];
																												} ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="ket_analisa" class="col-sm-2 control-label">Ket. Analisa</label>
					<div class="col-sm-3">
						<input name="ket_analisa" type="text" class="form-control" id="ket_analisa" value="<?php if($KetAnalisa!=""){ echo $KetAnalisa; } else { echo $rcek['ket_analisa']; } ?>" placeholder="Keterangan Analisa" >
					</div>
				</div>
				<?php if($_SESSION['user_id10'] != 'adm-cqa'):?>
				<div class="form-group">
					<label for="akar_masalah" class="col-sm-2 control-label">Akar Masalah</label>
					<div class="col-sm-3">
						<input name="akar_masalah" type="text" class="form-control" id="akar_masalah" value="<?php if($AkarMasalah!=""){ echo $AkarMasalah; } else { echo $rcek['akar_masalah']; } ?>" placeholder="Akar Masalah" required>
					</div>
				</div>
				<?php endif;?>
				<div class="form-group">
					<label for="solusi_panjang" class="col-sm-2 control-label">Solusi Jangka Panjang</label>
					<div class="col-sm-3">
						<input name="solusi_panjang" type="text" class="form-control" id="solusi_panjang" value="<?php if($SolusiPanjang!=""){ echo $SolusiPanjang; } else { echo $rcek['solusi_panjang']; } ?>" placeholder="Solusi Jangka Panjang" required>
					</div>
				</div>
				<div class="form-group">
					<label for="rincian" class="col-sm-2 control-label">Penyelesaian</label>
					<div class="col-sm-3">
						<select class="form-control select2" name="rincian">
							<option value="">Pilih</option>
							<option value="Celup Ulang" <?php if ($rcek['rincian'] == "Celup Ulang") {
															echo "SELECTED";
														} ?>>Celup Ulang</option>
							<option value="Tidak Celup Ulang" <?php if ($rcek['rincian'] == "Tidak Celup Ulang") {
																	echo "SELECTED";
																} ?>>Tidak Celup Ulang</option>
							<option value="Upgrade" <?php if ($rcek['rincian'] == "Upgrade") {
														echo "SELECTED";
													} ?>>Upgrade</option>
							<option value="Disposisi" <?php if ($rcek['rincian'] == "Disposisi") {
															echo "SELECTED";
														} ?>>Disposisi</option>
							<option value="BS" <?php if ($rcek['rincian'] == "BS") {
													echo "SELECTED";
												} ?>>BS</option>
							<option value="Tunggu Conform" <?php if ($rcek['rincian'] == "Tunggu Conform") {
																echo "SELECTED";
															} ?>>Tunggu Conform</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="multi" class="col-sm-2 control-label">Rincian</label>
					<div class="col-sm-4">
						<div class="input-group">
							<select class="form-control select2" multiple="multiple" data-placeholder="Jenis Masalah" name="rmp_benang[]" id="kerusakan">
								<?php
									$serverName = "10.0.0.221";
									$connectionOptions = array(
										"Database" => "db_qc",
										"UID" => "sa",
										"PWD" => "Ind@taichen2024",
										"CharacterSet" => "UTF-8"
									);
									$conn = sqlsrv_connect($serverName, $connectionOptions);
									if ($conn === false) {
										die(print_r(sqlsrv_errors(), true));
									}
									$dtArr = trim((string)$rcek['penyelesaian']);
									$data  = array_map('trim', explode(",", $dtArr));
									$qCek1 = sqlsrv_query(
										$conn,
										"SELECT nama FROM db_qc.tbl_masalah_ncp WHERE jenis = ? ORDER BY nama ASC",
										array('Penyelesaian')
									);
									$i = 0;
									while ($dCek1 = sqlsrv_fetch_array($qCek1, SQLSRV_FETCH_ASSOC)) {
										$nama = $dCek1['nama'];
										?>
										<option value="<?php echo $nama; ?>" <?php echo in_array($nama, $data, true) ? "selected" : ""; ?>>
											<?php echo $nama; ?>
										</option>
										<?php
										$i++;
									}
								?>
							</select>
							<span class="input-group-btn"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#DataPenyelesaian"> ...</button></span>
						</div>
					</div>
					<?php if (strtoupper($_SESSION['user_id10']) == "ADM-DYE" or strtolower($_SESSION['user_id10']) == "mucharom.mustofa" or strtoupper($_SESSION['user_id10']) == "QCF") { ?>
						<label for="multi" class="col-sm-1 control-label">Ke-</label>
						<div class="col-sm-2">
							<div class="input-group">
								<select class="form-control" name="ke" id="ke">
									<?php $q_data_ke			= sqlsrv_query($con, "SELECT * FROM tbl_ke ORDER BY id ASC"); ?>
									<?php while ($row_data_ke	= sqlsrv_fetch_array($q_data_ke, SQLSRV_FETCH_ASSOC)) { ?>
										<option value="<?= $row_data_ke['ke']; ?>" <?php if($rcek['data_ke'] == $row_data_ke['ke']){ echo "SELECTED"; } ?>><?= $row_data_ke['ke']; ?></option>
									<?php } ?>
								</select>
								<span class="input-group-btn"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#Datake"> ...</button></span>
							</div>
						</div>
						<label for="multi" class="col-sm-1 control-label">ACC Perbaikan</label>
						<div class="col-sm-2">
							<div class="input-group">
								<select class="form-control" name="acc_perbaikan" id="acc_perbaikan">
								<?php $q_namacolorist			= sqlsrv_query($con, "SELECT * FROM tbl_nama_colorist ORDER BY id ASC"); ?>
									<?php while ($row_namacolorist	= sqlsrv_fetch_array($q_namacolorist, SQLSRV_FETCH_ASSOC)) { ?>
										<option value="<?= $row_namacolorist['nama_colorist']; ?>" <?php if($rcek['nama_colorist'] == $row_namacolorist['nama_colorist']){ echo 'SELECTED'; } ?>><?= $row_namacolorist['nama_colorist']; ?></option>
									<?php } ?>
								</select>
								<span class="input-group-btn"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#DataColorist"> ...</button></span>
							</div>
						</div>
					<?php } ?>
				</div>
				<?php if (strtoupper($_SESSION['user_id10']) == "ADM-FIN") { ?>
					<div class="form-group">
						<label for="shift" class="col-sm-2 control-label">Shift</label>
						<div class="col-sm-2">
							<select class="form-control" name="shift">
								<option value="">Pilih</option>
								<option value="A" <?php if ($rcek['shift'] == "A") {
														echo "SELECTED";
													} ?>>A</option>
								<option value="B" <?php if ($rcek['shift'] == "B") {
														echo "SELECTED";
													} ?>>B</option>
								<option value="C" <?php if ($rcek['shift'] == "C") {
														echo "SELECTED";
													} ?>>C</option>
								<option value="A+B" <?php if ($rcek['shift'] == "A+B") {
														echo "SELECTED";
													} ?>>A+B</option>
								<option value="B+C" <?php if ($rcek['shift'] == "B+C") {
														echo "SELECTED";
													} ?>>B+C</option>
								<option value="C+A" <?php if ($rcek['shift'] == "C+A") {
														echo "SELECTED";
													} ?>>C+A</option>
								<option value="A+B+C" <?php if ($rcek['shift'] == "A+B+C") {
														echo "SELECTED";
													} ?>>A+B+C</option>
								<option value="Non-Shift" <?php if ($rcek['shift'] == "Non-Shift") {
																echo "SELECTED";
															} ?>>Non-Shift</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="mesin" class="col-sm-2 control-label">Mesin</label>
						<div class="col-sm-3">
							<select class="form-control" name="mesin">
								<option value="">Pilih</option>
								<option value="BELAH-CUCI 01" <?php if ($rcek['mesin'] == "BELAH-CUCI 01") {
																	echo "SELECTED";
																} ?>>BELAH-CUCI 01</option>
								<option value="BELAH-CUCI 02" <?php if ($rcek['mesin'] == "BELAH-CUCI 02") {
																	echo "SELECTED";
																} ?>>BELAH-CUCI 02</option>
								<option value="BELAH-CUCI 03" <?php if ($rcek['mesin'] == "BELAH-CUCI 03") {
																	echo "SELECTED";
																} ?>>BELAH-CUCI 03</option>
								<option value="BELAH-CUCI 04" <?php if ($rcek['mesin'] == "BELAH-CUCI 04") {
																	echo "SELECTED";
																} ?>>BELAH-CUCI 04</option>
								<option value="COMPACT 01" <?php if ($rcek['mesin'] == "COMPACT" or $rcek['mesin'] == "COMPACT 01") {
																echo "SELECTED";
															} ?>>COMPACT 01</option>
								<option value="COMPACT 02" <?php if ($rcek['mesin'] == "COMPACT 02") {
																echo "SELECTED";
															} ?>>COMPACT 02</option>
								<option value="COMPACT 03" <?php if ($rcek['mesin'] == "COMPACT 03") {
																echo "SELECTED";
															} ?>>COMPACT 03</option>
								<option value="OVEN-01" <?php if ($rcek['mesin'] == "OVEN-01") {
															echo "SELECTED";
														} ?>>OVEN-01</option>
								<option value="ST-01" <?php if ($rcek['mesin'] == "STENTER LK 01" or $rcek['mesin'] == "ST-01") {
															echo "SELECTED";
														} ?>>ST-01</option>
								<option value="ST-02" <?php if ($rcek['mesin'] == "STENTER FONG 2" or $rcek['mesin'] == "ST-02") {
															echo "SELECTED";
														} ?>>ST-02</option>
								<option value="ST-03" <?php if ($rcek['mesin'] == "STENTER LK 03" or $rcek['mesin'] == "ST-03") {
															echo "SELECTED";
														} ?>>ST-03</option>
								<option value="ST-04" <?php if ($rcek['mesin'] == "STENTER LK 04" or $rcek['mesin'] == "ST-04") {
															echo "SELECTED";
														} ?>>ST-04</option>
								<option value="ST-05" <?php if ($rcek['mesin'] == "STENTER LK 05" or $rcek['mesin'] == "ST-05") {
															echo "SELECTED";
														} ?>>ST-05</option>
								<option value="ST-06" <?php if ($rcek['mesin'] == "STENTER LK 06" or $rcek['mesin'] == "ST-06") {
															echo "SELECTED";
														} ?>>ST-06</option>
								<option value="ST-07" <?php if ($rcek['mesin'] == "STENTER LK 07" or $rcek['mesin'] == "ST-07") {
															echo "SELECTED";
														} ?>>ST-07</option>
								<option value="ST-08" <?php if ($rcek['mesin'] == "STENTER FONG 8" or $rcek['mesin'] == "ST-08") {
															echo "SELECTED";
														} ?>>ST-08</option>
								<option value="ST-09" <?php if ($rcek['mesin'] == "STENTER YT 09" or $rcek['mesin'] == "ST-09") {
															echo "SELECTED";
														} ?>>ST-09</option>
								<option value="ST-10" <?php if ($rcek['mesin'] == "STENTER YT 10" or $rcek['mesin'] == "ST-10") {
															echo "SELECTED";
														} ?>>ST-10</option>
								<option value="INSPEK" <?php if ($rcek['mesin'] == "INSPEK" or $rcek['mesin'] == "INSPEK") {
															echo "SELECTED";
														} ?>>INSPEK</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="acc" class="col-sm-2 control-label">ACC</label>
						<div class="col-sm-3">
							<input name="acc" type="text" class="form-control" id="acc" value="<?php echo $rcek['acc']; ?>" placeholder="Nama Staff">
						</div>
					</div>
					<div class="form-group">
						<label for="rekomendasi" class="col-sm-2 control-label">Rekomendasi</label>
						<div class="col-sm-3">
							<input name="rekomendasi" type="text" class="form-control" id="rekomendasi" value="<?php echo $rcek['rekomendasi']; ?>" placeholder="Nama Staff">
						</div>
					</div>
				<?php } ?>
				<?php if (strtoupper($_SESSION['user_id10']) == "ADM-DYE" or strtolower($_SESSION['user_id10']) == "mucharom.mustofa" or strtoupper($_SESSION['user_id10']) == "QCF") { ?>
					<div class="form-group">
						<label for="shift" class="col-sm-2 control-label">Shift</label>
						<div class="col-sm-1">
							<select class="form-control" name="shift">
								<option value="">Pilih</option>
								<option value="A" <?php if ($dtDye['g_shift'] == "A" or $rcek['shift'] == "A") {
														echo "SELECTED";
													} ?>>A</option>
								<option value="B" <?php if ($dtDye['g_shift'] == "B" or $rcek['shift'] == "B") {
														echo "SELECTED";
													} ?>>B</option>
								<option value="C" <?php if ($dtDye['g_shift'] == "C" or $rcek['shift'] == "C") {
														echo "SELECTED";
													} ?>>C</option>
								<option value="Non-Shift" <?php if ($dtDye['g_shift'] == "Non-Shift" or $rcek['shift'] == "Non-Shift") {
																echo "SELECTED";
															} ?>>Non-Shift</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="mesin" class="col-sm-2 control-label">Mesin</label>
						<div class="col-sm-2">
							<input name="mesin" type="text" class="form-control" id="mesin" value="<?php if ($cDye > 0) {
																										echo $dtDye['no_mesin'];
																									} else {
																										echo $rcek['mesin'];
																									} ?>" placeholder="No Mesin">
						</div>
					</div>
					<div class="form-group">
						<label for="acc" class="col-sm-2 control-label">Colorist</label>
						<div class="col-sm-3">
							<div class="input-group">
								<select class="form-control select2" name="acc" id="acc">
									<option value="">Pilih</option>
									<?php
									$qrytj = sqlsrv_query($conn, "SELECT nama FROM db_qc.tbl_tjawab_ncp ORDER BY nama ASC");
									while ($rtj = sqlsrv_fetch_array($qrytj, SQLSRV_FETCH_ASSOC)) {
									?>
										<option value="<?php echo $rtj['nama']; ?>" <?php if ($rcek['penanggung_jawab'] == $rtj['nama']) {
																						echo "SELECTED";
																					} ?>><?php echo $rtj['nama']; ?></option>
									<?php } ?>
								</select>
								<span class="input-group-btn"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#DataPenanggungJawab"> ...</button></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="penanggung_jawab" class="col-sm-2 control-label">Penanggung Jawab</label>
						<div class="col-sm-3">
							<div class="input-group">
								<select class="form-control select2" name="penanggung_jawab" id="penanggung_jawab">
									<option value="">Pilih</option>
									<?php
									$qrytj = sqlsrv_query($conn, "SELECT nama FROM tbl_tjawab_ncp ORDER BY nama ASC");
									while ($rtj = sqlsrv_fetch_array($qrytj, SQLSRV_FETCH_ASSOC)) {
									?>
										<option value="<?php echo $rtj['nama']; ?>" <?php if ($rcek['penanggung_jawab'] == $rtj['nama']) {
																						echo "SELECTED";
																					} ?>><?php echo $rtj['nama']; ?></option>
									<?php } ?>
								</select>
								<span class="input-group-btn"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#DataPenanggungJawab"> ...</button></span>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="form-group">
					<label for="penyebab" class="col-sm-2 control-label">Penyebab</label>
					<div class="col-sm-3">
						<input name="penyebab" type="text" class="form-control" id="penyebab" value="<?php if ($_SESSION['dept'] == "DYE") {
																											echo $_SESSION['dept'];
																										} else {
																											echo $rcek['penyebab'];
																										} ?>" placeholder="Nama Staff">
					</div>
				</div>
				<div class="form-group">
					<label for="perbaikan" class="col-sm-2 control-label">Perbaikan</label>
					<div class="col-sm-3">
						<input name="perbaikan" type="text" class="form-control" id="perbaikan" value="<?php echo $rcek['perbaikan']; ?>" placeholder="Nama Staff">
					</div>
				</div>
				<?php if (strtoupper($_SESSION['user_id10']) == "ADM-FIN") { ?>
					<div class="form-group">
						<label for="mesin_perbaikan" class="col-sm-2 control-label">Mesin</label>
						<div class="col-sm-3">
							<select class="form-control" name="mesin_perbaikan">
								<option value="">Pilih</option>
								<option value="BELAH-CUCI 01" <?php if ($rcek['mesin_perbaikan'] == "BELAH-CUCI 01") {
																	echo "SELECTED";
																} ?>>BELAH-CUCI 01</option>
								<option value="BELAH-CUCI 02" <?php if ($rcek['mesin_perbaikan'] == "BELAH-CUCI 02") {
																	echo "SELECTED";
																} ?>>BELAH-CUCI 02</option>
								<option value="BELAH-CUCI 03" <?php if ($rcek['mesin_perbaikan'] == "BELAH-CUCI 03") {
																	echo "SELECTED";
																} ?>>BELAH-CUCI 03</option>
								<option value="BELAH-CUCI 04" <?php if ($rcek['mesin_perbaikan'] == "BELAH-CUCI 04") {
																	echo "SELECTED";
																} ?>>BELAH-CUCI 04</option>
								<option value="COMPACT 01" <?php if ($rcek['mesin_perbaikan'] == "COMPACT" or $rcek['mesin_perbaikan'] == "COMPACT 01") {
																echo "SELECTED";
															} ?>>COMPACT 01</option>
								<option value="COMPACT 02" <?php if ($rcek['mesin_perbaikan'] == "COMPACT 02") {
																echo "SELECTED";
															} ?>>COMPACT 02</option>
								<option value="COMPACT 03" <?php if ($rcek['mesin_perbaikan'] == "COMPACT 03") {
																echo "SELECTED";
															} ?>>COMPACT 03</option>
								<option value="OVEN-01" <?php if ($rcek['mesin_perbaikan'] == "OVEN-01") {
															echo "SELECTED";
														} ?>>OVEN-01</option>
								<option value="ST-01" <?php if ($rcek['mesin_perbaikan'] == "STENTER LK 01" or $rcek['mesin_perbaikan'] == "ST-01") {
															echo "SELECTED";
														} ?>>ST-01</option>
								<option value="ST-02" <?php if ($rcek['mesin_perbaikan'] == "STENTER FONG 2" or $rcek['mesin_perbaikan'] == "ST-02") {
															echo "SELECTED";
														} ?>>ST-02</option>
								<option value="ST-03" <?php if ($rcek['mesin_perbaikan'] == "STENTER LK 03" or $rcek['mesin_perbaikan'] == "ST-03") {
															echo "SELECTED";
														} ?>>ST-03</option>
								<option value="ST-04" <?php if ($rcek['mesin_perbaikan'] == "STENTER LK 04" or $rcek['mesin_perbaikan'] == "ST-04") {
															echo "SELECTED";
														} ?>>ST-04</option>
								<option value="ST-05" <?php if ($rcek['mesin_perbaikan'] == "STENTER LK 05" or $rcek['mesin_perbaikan'] == "ST-05") {
															echo "SELECTED";
														} ?>>ST-05</option>
								<option value="ST-06" <?php if ($rcek['mesin_perbaikan'] == "STENTER LK 06" or $rcek['mesin_perbaikan'] == "ST-06") {
															echo "SELECTED";
														} ?>>ST-06</option>
								<option value="ST-07" <?php if ($rcek['mesin_perbaikan'] == "STENTER LK 07" or $rcek['mesin_perbaikan'] == "ST-07") {
															echo "SELECTED";
														} ?>>ST-07</option>
								<option value="ST-08" <?php if ($rcek['mesin_perbaikan'] == "STENTER FONG 8" or $rcek['mesin_perbaikan'] == "ST-08") {
															echo "SELECTED";
														} ?>>ST-08</option>
								<option value="ST-09" <?php if ($rcek['mesin_perbaikan'] == "STENTER YT 09" or $rcek['mesin_perbaikan'] == "ST-09") {
															echo "SELECTED";
														} ?>>ST-09</option>
								<option value="ST-10" <?php if ($rcek['mesin_perbaikan'] == "STENTER YT 10" or $rcek['mesin_perbaikan'] == "ST-10") {
															echo "SELECTED";
														} ?>>ST-10</option>
								<option value="INSPEK" <?php if ($rcek['mesin_perbaikan'] == "INSPEK" or $rcek['mesin_perbaikan'] == "INSPEK") {
															echo "SELECTED";
														} ?>>INSPEK</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="jmlperbaikan" class="col-sm-2 control-label">Jumlah Perbaikan</label>
						<div class="col-sm-1">
							<input name="jmlperbaikan" type="text" class="form-control" id="jmlperbaikan" value="<?php echo $rcek['jml_perbaikan']; ?>" placeholder="0">
						</div>
					</div>
					<div class="form-group">
						<label for="kategori" class="col-sm-2 control-label">Kategori</label>
						<div class="col-sm-3">
							<input name="kategori" type="text" class="form-control" id="kategori" value="<?php echo $rcek['kategori']; ?>" placeholder="Kategori">
						</div>
					</div>
				<?php } ?>
				
			</div>
		</div>
		<div class="box-footer">
			<button type="button" class="btn btn-default pull-left" name="save" Onclick="window.location='?p=Status-NCP-New'">Kembali <i class="fa fa-cycle"></i></button>
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
if ($_POST['save'] == "Simpan") {

    $ket = $_POST['ket'] ?? '';
    $ket = str_replace("'", "''", $ket);

    $kt1 = '';
    if (isset($_POST["rmp_benang"]) && is_array($_POST["rmp_benang"])) {
        $tmp = [];
        foreach ($_POST['rmp_benang'] as $subject1) {
            $subject1 = trim((string)$subject1);
            if ($subject1 !== '') $tmp[] = $subject1;
        }
        $kt1 = implode(",", $tmp);
    }
    $setParts = [];
    $params   = [];

    if (!empty($_POST['tgl_rencana'])) {
        $setParts[] = "tgl_rencana = ?";
        $params[]   = $_POST['tgl_rencana'];
    } else {
        $setParts[] = "tgl_rencana = NULL";
    }
    if (!empty($_POST['tgl_kembali_qcf'])) {
        $setParts[] = "tgl_kembali_qcf = ?";
        $params[]   = $_POST['tgl_kembali_qcf'];
    } else {
        $setParts[] = "tgl_kembali_qcf = NULL";
    }
    if (empty($rcek['tgl_terima'])) {
        $setParts[] = "tgl_terima = GETDATE()";
    }

    $setParts[] = "pemberi_instruksi = ?";  $params[] = $_POST['pemberi_instruksi'] ?? '';
    $setParts[] = "akar_penyebab = ?";      $params[] = $_POST['akar_penyebab'] ?? '';
    $setParts[] = "penyelesaian = ?";       $params[] = $kt1;
    $setParts[] = "acc = ?";                $params[] = $_POST['acc'] ?? '';
    $setParts[] = "rekomendasi = ?";        $params[] = $_POST['rekomendasi'] ?? '';
    $setParts[] = "mesin = ?";              $params[] = $_POST['mesin'] ?? '';
    $setParts[] = "shift = ?";              $params[] = $_POST['shift'] ?? '';
    $setParts[] = "penyebab = ?";           $params[] = $_POST['penyebab'] ?? '';
	$setParts[] = "perbaikan = ?";          $params[] = $_POST['perbaikan'] ?? '';
    $setParts[] = "penanggung_jawab = ?";   $params[] = $_POST['penanggung_jawab'] ?? '';
    $setParts[] = "rincian = ?";            $params[] = $_POST['rincian'] ?? '';
    $setParts[] = "ket_analisa = ?";        $params[] = $_POST['ket_analisa'] ?? '';
    $setParts[] = "akar_masalah = ?";       $params[] = $_POST['akar_masalah'] ?? '';
    $setParts[] = "solusi_pendek = ?";      $params[] = $_POST['solusi_pendek'] ?? '';
    $setParts[] = "solusi_panjang = ?";     $params[] = $_POST['solusi_panjang'] ?? '';
    $setParts[] = "mesin_perbaikan = ?";    $params[] = $_POST['mesin_perbaikan'] ?? '';
    $setParts[] = "jml_perbaikan = ?";      $params[] = $_POST['jmlperbaikan'] ?? '';
    $setParts[] = "kategori = ?";           $params[] = $_POST['kategori'] ?? '';
    $setParts[] = "ket_penyelesaian = ?";   $params[] = $ket;
    $setParts[] = "data_ke = ?";            $params[] = $_POST['ke'] ?? '';
    $setParts[] = "nama_colorist = ?";      $params[] = $_POST['acc_perbaikan'] ?? '';

    $params[] = $id;

    $sql = "
        UPDATE db_qc.tbl_ncp_qcf_now
        SET " . implode(",\n            ", $setParts) . "
        WHERE id = ?
    ";

    $sqlData = sqlsrv_query($conn, $sql, $params);

    if ($sqlData) {
        echo "<script>swal({
                title: 'Data Telah Tersimpan',
                text: 'Klik Ok untuk input data kembali',
                type: 'success',
                }).then((result) => {
                if (result.value) {
                        window.location.href='?p=Penyelesaian-New&id=$id';
                }
                });</script>";
    }
}
?>

<div class="modal fade" id="DataPenyelesaian">
	<div class="modal-dialog ">
		<div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Tindakan Penyelesaian</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id" name="id">
					<div class="form-group">
						<label for="penyelesaian" class="col-md-3 control-label">Jenis Penyelesaian</label>
						<div class="col-md-6">
							<input type="text" class="form-control" id="penyelesaian" name="penyelesaian" required>
							<span class="help-block with-errors"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<input type="submit" value="Simpan" name="simpan_penyelesaian" id="simpan_penyelesaian" class="btn btn-primary pull-right">
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="modal fade" id="Datake">
	<div class="modal-dialog ">
		<div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Data ke</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id" name="id">
					<div class="form-group">
						<label for="penyelesaian" class="col-md-3 control-label">Tambah Data Ke-</label>
						<div class="col-md-6">
							<input type="text" class="form-control" id="data_ke" name="data_ke" required>
							<span class="help-block with-errors"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<input type="submit" value="Simpan" name="simpan_data_ke" id="simpan_data_ke" class="btn btn-primary pull-right">
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="modal fade" id="DataColorist">
	<div class="modal-dialog ">
		<div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Data ke</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id" name="id">
					<div class="form-group">
						<label for="penyelesaian" class="col-md-3 control-label">Tambah Nama Colorist-</label>
						<div class="col-md-6">
							<input type="text" class="form-control" id="nama_colorist" name="nama_colorist" required>
							<span class="help-block with-errors"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<input type="submit" value="Simpan" name="simpan_nama_colorist" id="simpan_nama_colorist" class="btn btn-primary pull-right">
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<?php
if ($_POST['simpan_penyelesaian'] == "Simpan") {
    $sqlData1 = sqlsrv_query(
        $cond,
        "INSERT INTO db_qc.tbl_masalah_ncp (nama, jenis) VALUES (?, ?)",
        array($_POST['penyelesaian'], 'Penyelesaian')
    );
    if ($sqlData1) {
        echo "<script>swal({
                title: 'Data Telah Tersimpan',
                text: 'Klik Ok untuk input data kembali',
                type: 'success',
                }).then((result) => {
                if (result.value) {
                        window.location.href='?p=Penyelesaian-New&id=$id';
                }
                });</script>";
    }
} elseif ($_POST['simpan_data_ke'] == 'Simpan') {
    $sqlData1 = sqlsrv_query(
        $con,
        "INSERT INTO db_dying.tbl_ke (ke) VALUES (?)",
        array($_POST['data_ke'])
    );
    if ($sqlData1) {
        echo "<script>swal({
                title: 'Data Telah Tersimpan',
                text: 'Klik Ok untuk input data kembali',
                type: 'success',
                }).then((result) => {
                if (result.value) {
                        window.location.href='?p=Penyelesaian-New&id=$id';
                }
                });</script>";
    }
} elseif ($_POST['simpan_nama_colorist'] == 'Simpan') {
    $sqlData1 = sqlsrv_query(
        $con,
        "INSERT INTO db_dying.tbl_nama_colorist (nama_colorist) VALUES (?)",
        array($_POST['nama_colorist'])
    );
    if ($sqlData1) {
        echo "<script>swal({
                title: 'Data Telah Tersimpan',
                text: 'Klik Ok untuk input data kembali',
                type: 'success',
                }).then((result) => {
                if (result.value) {
                        window.location.href='?p=Penyelesaian-New&id=$id';
                }
                });</script>";
    }
}
?>
<div class="modal fade" id="DataPenanggungJawab">
	<div class="modal-dialog ">
		<div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Penanggung Jawab</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id" name="id">
					<div class="form-group">
						<label for="penanggung_jawab" class="col-md-3 control-label">Nama</label>
						<div class="col-md-6">
							<input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" required>
							<span class="help-block with-errors"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<input type="submit" value="Simpan" name="simpan_penanggung_jawab" id="simpan_penanggung_jawab" class="btn btn-primary pull-right">
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<?php
if ($_POST['simpan_penanggung_jawab'] == "Simpan") {
	$sqlData2 = sqlsrv_query($cond, "INSERT INTO tbl_tjawab_ncp SET 
		  nama='" . $_POST['penanggung_jawab'] . "',dept='DYE'");
	if ($sqlData2) {
		echo "<script>swal({
				title: 'Data Telah Tersimpan',   
				text: 'Klik Ok untuk input data kembali',
				type: 'success',
				}).then((result) => {
				if (result.value) {
						window.location.href='?p=Penyelesaian-New&id=$id';
					
				}
				});</script>";
	}
}
?>