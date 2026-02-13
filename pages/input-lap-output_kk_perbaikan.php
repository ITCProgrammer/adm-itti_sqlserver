<script>
    function tampil() {
        if (document.forms['form1']['status_warna'].value == "TOLAK BASAH") {
            $("#disposisi").css("display", "");
        } else {
            $("#disposisi").css("display", "none");
        }
        if (document.forms['form1']['status_warna'].value == "") {
            $("#disposisi").css("display", "none");
        }
        if (document.forms['form1']['status_warna'].value == "OK") {
            $("#disposisi").css("display", "none");
        }
    }

    function proses_demand() {
        var nodemand = document.getElementById("nodemand").value;

        if (nodemand == 0) {
            window.location.href = '?p=input-lap-output_kk_perbaikan';
        } else {
            window.location.href = '?p=input-lap-output_kk_perbaikan&nodemand=' + nodemand;
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
            window.location.href = '?p=input-lap-output_kk_perbaikan&nodemand=' + nodemand + '&shift=' + shift;
        }
    }
</script>
<?php
include "koneksi.php";
ini_set("error_reporting", 1);

// ===============================
$serverName = "10.0.0.10"; // atau "10.0.0.10\\SQLEXPRESS"
$connectionInfo = array(
    "Database" => "db_dying",
    "UID" => "dit",
    "PWD" => "4dm1n",
    "CharacterSet" => "UTF-8"
);
$con1 = sqlsrv_connect($serverName, $connectionInfo);
if ($con1 === false) {
    die(print_r(sqlsrv_errors(), true));
}
if (isset($_POST['simpan']) && $_POST['simpan'] == "simpan") {

    // cek data existing
    $ceksql = sqlsrv_query($cona, "
        SELECT TOP 1 *
        FROM db_adm.tbl_output_kk_perbaikan
        WHERE nodemand = ? AND shift = ? AND dept = 'QCF'
    ", array($_GET['nodemand'] ?? '', $_POST['shift'] ?? ''));

    if ($ceksql === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $cek = 0;
    $rowCek = sqlsrv_fetch_array($ceksql, SQLSRV_FETCH_ASSOC);
    if ($rowCek)
        $cek = 1;

    $pelanggan = str_replace("'", "''", $_POST['pelanggan'] ?? '');
    $order = str_replace("'", "''", $_POST['no_order'] ?? '');
    $po = str_replace("'", "''", $_POST['no_po'] ?? '');
    $jns = str_replace("'", "''", $_POST['jenis_kain'] ?? '');
    $warna = str_replace("'", "''", $_POST['warna'] ?? '');
    $rincian_perbaikan = str_replace("'", "''", $_POST['rincian_perbaikan'] ?? '');

    if ($cek > 0) {
        // UPDATE
        $sql1 = sqlsrv_query($cona, "
            UPDATE db_adm.tbl_output_kk_perbaikan SET
                no_order = ?,
                no_po = ?,
                pelanggan = ?,
                jenis_kain = ?,
                no_item = ?,
                warna = ?,
                no_warna = ?,
                lot = ?,
                shift = ?,
                dept = 'CQA',
                jml_roll = ?,
                bruto = ?,
                asal_kartu = ?,
                tindakan_perbaikan = ?,
                rincian_perbaikan = ?,
                hasil = ?,
                colorist = ?,
                tgl_update = GETDATE()
            WHERE nodemand = ?
        ", array(
            $order,
            $po,
            $pelanggan,
            $jns,
            $_POST['no_item'] ?? '',
            $warna,
            $_POST['no_warna'] ?? '',
            $_POST['lot'] ?? '',
            $_POST['shift'] ?? '',
            $_POST['rol'] ?? 0,
            $_POST['bruto'] ?? 0,
            $_POST['asal_kartu'] ?? '',
            $_POST['tindakan_perbaikan'] ?? '',
            $rincian_perbaikan,
            $_POST['hasil'] ?? '',
            $_POST['colorist'] ?? '',
            $_POST['nodemand'] ?? ''
        ));

        if ($sql1 === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        echo "<script>swal({
            title: 'Data has been updated!',
            text: 'Klik Ok untuk input data kembali',
            type: 'success',
        }).then((result) => {
            if (result.value) {
                window.location.href='?p=input-lap-output_kk_perbaikan&nodemand=" . ($_POST['nodemand'] ?? '') . "';
            }
        });</script>";

    } else {
        // INSERT
        $sql = sqlsrv_query($cona, "
            INSERT INTO db_adm.tbl_output_kk_perbaikan (
                nokk, nodemand, no_order, no_po, pelanggan, jenis_kain, no_item,
                warna, no_warna, lot, shift, dept, jml_roll, bruto,
                asal_kartu, tindakan_perbaikan, rincian_perbaikan, hasil, colorist, tgl_update
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'CQA', ?, ?, ?, ?, ?, ?, ?, GETDATE()
            )
        ", array(
            $_POST['nokk'] ?? '',
            $_POST['nodemand'] ?? '',
            $order,
            $po,
            $pelanggan,
            $jns,
            $_POST['no_item'] ?? '',
            $warna,
            $_POST['no_warna'] ?? '',
            $_POST['lot'] ?? '',
            $_POST['shift'] ?? '',
            $_POST['rol'] ?? 0,
            $_POST['bruto'] ?? 0,
            $_POST['asal_kartu'] ?? '',
            $_POST['tindakan_perbaikan'] ?? '',
            $rincian_perbaikan,
            $_POST['hasil'] ?? '',
            $_POST['colorist'] ?? ''
        ));

        if ($sql === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        echo "<script>swal({
            title: 'Data has been saved!',
            text: 'Klik Ok untuk input data kembali',
            type: 'success',
        }).then((result) => {
            if (result.value) {
                window.location.href='?p=input-lap-output_kk_perbaikan&nodemand=" . ($_POST['nodemand'] ?? '') . "';
            }
        });</script>";
    }
}
?>

<?php
$nodemand = $_GET['nodemand'] ?? '';

$msql = sqlsrv_query($cona, "
    SELECT TOP 1 *
    FROM db_adm.tbl_output_kk_perbaikan
    WHERE nodemand LIKE ? AND shift = ? AND dept = 'CQA'
", array("%$nodemand%", $_GET['shift'] ?? ''));

if ($msql === false) {
    die(print_r(sqlsrv_errors(), true));
}
$row = sqlsrv_fetch_array($msql, SQLSRV_FETCH_ASSOC);
$crow = $row ? 1 : 0;

if (!empty($nodemand)) {

    $qryDye1 = sqlsrv_query($conb, "
        SELECT TOP 1
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
            c.colorist,
            a.acc_keluar,
            a.tgl_buat,
            a.nokk,
            b.rol,
            b.bruto
        FROM db_dyeing.tbl_hasilcelup a
        LEFT JOIN db_dying.tbl_montemp c ON a.id_montemp = c.id
        LEFT JOIN db_dying.tbl_schedule b ON c.id_schedule = b.id
        WHERE a.nodemand LIKE ?
        ORDER BY a.id DESC
    ", array("%$nodemand%"));

    if ($qryDye1 === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $dtDyeing = sqlsrv_fetch_array($qryDye1, SQLSRV_FETCH_ASSOC);

    if (empty($dtDyeing)) {
        echo "<script>swal({
            title: 'Data tidak dapat ditemukan <br>di hasil Output KK Perbaikan!',
            text: 'Klik Ok untuk input data kembali',
            type: 'warning',
        }).then((result) => {
            if (result.value) {
                window.location.href='?p=input-lap-output_kk_perbaikan';
            }
        });</script>";
    }

    $qryDye2 = sqlsrv_query($conb, "
        SELECT
            SUM(a.rol) AS jml_roll,
            SUM(a.bruto) AS jml_kg,
            a.no_mesin,
            a.proses,
            b.colorist
        FROM db_dying.tbl_schedule a
        LEFT JOIN db_dying.tbl_montemp b ON a.id = b.id_schedule
        WHERE a.nokk = ?
          AND a.STATUS = 'selesai'
        GROUP BY a.no_mesin, a.proses, b.colorist
    ", array($dtDyeing['nokk'] ?? ''));

    if ($qryDye2 === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $dtSch = sqlsrv_fetch_array($qryDye2, SQLSRV_FETCH_ASSOC);
}
?>

<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
    <div class="box box-info">
        <div class="box-header with-border">
            <center>
                <h3 class="box-title">INPUT DATA <br>OUTPUT KK (PERBAIKAN). <br> Berdasarkan data yang di input dari
                    Hasil Celup Dyeing.</h3>
            </center>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                        class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nodemand" class="col-sm-3 control-label">No Demand</label>
                    <div class="col-sm-4">
                        <input name="nokk" type="hidden" class="form-control" id="nokk"
                            value="<?php echo $rowdb2['PRODUCTIONORDERCODE']; ?>">
                        <input name="nodemand" type="text" class="form-control" id="nodemand" onchange="proses_demand()"
                            value="<?php echo $_GET['nodemand']; ?>" placeholder="No Demand" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="shift" class="col-sm-3 control-label">Shift</label>
                    <div class="col-sm-2">
                        <select class="form-control select2" name="shift" required id="shift" onchange="proses_shift()">
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
                        <input name="pelanggan" type="text" class="form-control" id="pelanggan"
                            value="<?= $dtDyeing['langganan']; ?>" placeholder="Pelanggan">
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_po" class="col-sm-3 control-label">PO</label>
                    <div class="col-sm-5">
                        <input name="no_po" class="form-control" type="text" id="no_po" value="<?= $dtDyeing['po']; ?>"
                            placeholder="PO" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_order" class="col-sm-3 control-label">No Order</label>
                    <div class="col-sm-4">
                        <input name="no_order" type="text" class="form-control" id="no_order"
                            value="<?= $dtDyeing['no_order']; ?>" placeholder="No Order" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="jenis_kain" class="col-sm-3 control-label">Jenis Kain</label>
                    <div class="col-sm-8">
                        <textarea name="jenis_kain" class="form-control" id="jenis_kain"
                            placeholder="Jenis Kain"><?= stripslashes($dtDyeing['jenis_kain']); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_item" class="col-sm-3 control-label">No Item</label>
                    <div class="col-sm-3">
                        <input name="no_item" type="text" class="form-control" id="no_item"
                            value="<?= $dtDyeing['no_item']; ?>" placeholder="No Item">
                    </div>
                </div>
                <div class="form-group">
                    <label for="warna" class="col-sm-3 control-label">Warna</label>
                    <div class="col-sm-8">
                        <textarea name="warna" class="form-control" id="warna"
                            placeholder="Warna"><?= $dtDyeing['warna']; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_warna" class="col-sm-3 control-label">No Warna</label>
                    <div class="col-sm-8">
                        <textarea name="no_warna" class="form-control" id="no_warna"
                            placeholder="No Warna"><?= $dtDyeing['no_warna']; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="lot" class="col-sm-3 control-label">Prod. Order/Lot</label>
                    <div class="col-sm-3">
                        <input name="lot" class="form-control" type="text" id="lot" value="<?= $dtDyeing['nokk']; ?>"
                            placeholder="Lot">
                    </div>
                </div>
                <div class="form-group">
                    <label for="qty_bruto" class="col-sm-3 control-label">Qty Bruto</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input name="rol" type="text" class="form-control" id="rol" value="<?= $dtDyeing['rol']; ?>"
                                placeholder="" required>
                            <span class="input-group-addon">Roll</span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input name="bruto" type="text" class="form-control" id="bruto"
                                value="<?= $dtDyeing['bruto']; ?>" placeholder="0.00" required>
                            <span class="input-group-addon">KGs</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="asal_kartu" class="col-sm-3 control-label">Asal kartu</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="asal_kartu">
                            <option value="">Pilih</option>
                            <option value="PERBAIKAN CQA">PERBAIKAN CQA</option>
                            <option value="PERBAIKAN DYE">PERBAIKAN DYE</option>
                            <option value="PERBAIKAN FIN">PERBAIKAN FIN</option>
                            <option value="PERBAIKAN BRS">PERBAIKAN BRS</option>
                            <option value="PERBAIKAN DEPT LAIN">PERBAIKAN DEPT LAIN</option>
                            <option value="TOLAK BASAH">TOLAK BASAH</option>
                            <option value="GAGAL PROSES">GAGAL PROSES</option>
                            <option value="PERBAIKAN FLOT">PERBAIKAN FLOT</option>
                            <option value="TEST OBAT FIN JADI">TEST OBAT FIN JADI</option>
                            <option value="REVIEW WARNA">REVIEW WARNA</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tindakan_perbaikan" class="col-sm-3 control-label">Tindakan Perbaikan</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="tindakan_perbaikan">
                            <option value="">Pilih</option>
                            <option value="CELUP ULANG">CELUP ULANG</option>
                            <option value="TIDAK CELUP ULANG">TIDAK CELUP ULANG</option>
                            <option value="OKE DISPOSISI">OKE DISPOSISI</option>
                            <option value="BS">BS</option>
                            <option value="LANJUT PROSES (OK)">LANJUT PROSES (OK)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="rincian_perbaikan" class="col-sm-3 control-label">Rincian Perbaikan</label>
                    <div class="col-sm-5">
                        <textarea name="rincian_perbaikan" class="form-control" id="rincian_perbaikan"
                            placeholder="Rincian Perbaikan"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="hasil" class="col-sm-3 control-label">Hasil</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="hasil">
                            <option value="">Pilih</option>
                            <option value="OK">OK</option>
                            <option value="TIDAK OK">TIDAK OK</option>
                            <option value="OKE DISPOSISI">OKE DISPOSISI</option>
                            <option value="ON PROGRESS">ON PROGRESS</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="colorist" class="col-sm-3 control-label">Colorist</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="colorist">
                            <option value="">Pilih</option>
                            <option value="WISNU">WISNU</option>
                            <option value="AGUNG CAHYONO">AGUNG CAHYONO</option>
                            <option value="DEWI">DEWI</option>
                            <option value="Mr Xiaoming">Mr Xiaoming</option>
                            <option value="Mrs Paulina">Mrs Paulina</option>
                            <option value="M SUBIHADI">M SUBIHADI</option>
                            <option value="M SUBIHADI">M SUBIHADI</option>
                            <option value="JIANG MINGWEI">JIANG MINGWEI</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <?php if ($cekcwarna > 0) { ?>
                <button type="submit" class="btn btn-primary pull-right" name="ubah" value="ubah"><i class="fa fa-edit"></i>
                    Ubah</button>
            <?php } else if ($_GET['nodemand'] != "" and $_GET['shift'] != "" and $cekcwarna == 0) { ?>
                    <button type="submit" class="btn btn-primary pull-right" name="simpan" value="simpan"><i
                            class="fa fa-save"></i> Simpan</button>
            <?php } ?>

            <button type="button" class="btn btn-warning pull-left" name="lihat" value="lihat"
                onClick="window.location.href='?p=lihat-data-output_kk_perbaikan'"><i class="fa fa-search"></i> Lihat
                Data</button>
        </div>
    </div>
</form>