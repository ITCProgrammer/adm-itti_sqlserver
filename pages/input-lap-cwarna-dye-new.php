<script>
    function tampil() {
        if (document.forms['form1']['status_warna'].value == "TOLAK BASAH") {
            $("#disposisi").css("display", ""); // To unhide
        } else {
            $("#disposisi").css("display", "none"); // To hide
        }
        if (document.forms['form1']['status_warna'].value == "") {
            $("#disposisi").css("display", "none"); // To hide
        }
        if (document.forms['form1']['status_warna'].value == "OK") {
            $("#disposisi").css("display", "none"); // To hide
        }
    }

    function proses_demand() {
        var nodemand = document.getElementById("nodemand").value;

        if (nodemand == 0) {
            window.location.href = '?p=Input-Lap-Cwarna-Dye-New';
        } else {
            window.location.href = '?p=Input-Lap-Cwarna-Dye-New&nodemand=' + nodemand;
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
            window.location.href = '?p=Input-Lap-Cwarna-Dye-New&nodemand=' + nodemand + '&shift=' + shift;
        }
    }
</script>
<?php
include "koneksi.php";
ini_set("error_reporting", 1);

if ($_POST['simpan'] == "simpan") {

    // ====== CEK DATA HARI INI (SQL SERVER) ======
    $ceksql = sqlsrv_query($cond, "
        SELECT COUNT(*) AS jml
        FROM db_qc.tbl_cocok_warna_dye
        WHERE nodemand = ?
          AND [shift]  = ?
          AND CAST(tgl_celup AS date) = CAST(GETDATE() AS date)
          AND dept = 'QCF'
    ", [$_GET['nodemand'], $_POST['shift']]);

    $rcek = sqlsrv_fetch_array($ceksql, SQLSRV_FETCH_ASSOC);
    $cek  = (int)($rcek['jml'] ?? 0);

    if ($cek > 0) {

        $pelanggan = str_replace("'", "''", $_POST['pelanggan']);
        $order = str_replace("'", "''", $_POST['no_order']);
        $po = str_replace("'", "''", $_POST['no_po']);
        $jns = str_replace("'", "''", $_POST['jenis_kain']);
        $warna = str_replace("'", "''", $_POST['warna']);
        $ket = str_replace("'", "''", $_POST['ket']);
        $spectro = str_replace("'", "''", $_POST['spectro']);
        $colorist_dye = str_replace("'", "''", $_POST['colorist_dye']);
        $colorist_qcf = str_replace("'", "''", $_POST['colorist_qcf']);

        // ====== UPDATE (SQL SERVER) ======
        $sql1 = sqlsrv_query($cond, "
            UPDATE db_qc.tbl_cocok_warna_dye SET
                no_order     = ?,
                no_po        = ?,
                pelanggan    = ?,
                jenis_kain   = ?,
                no_item      = ?,
                warna        = ?,
                no_warna     = ?,
                no_mesin     = ?,
                proses       = ?,
                colorist_dye = ?,
                tgl_celup    = ?,
                lot          = ?,
                jml_roll     = ?,
                bruto        = ?,
                status_warna = ?,
                disposisi    = ?,
                colorist_qcf = ?,
                ket          = ?,
                spectro      = ?,
                tgl_update   = GETDATE()
            WHERE nodemand = ?
        ", [
            $order,
            $po,
            $pelanggan,
            $jns,
            $_POST['no_item'],
            $warna,
            $_POST['no_warna'],
            $_POST['no_mesin'],
            $_POST['proses_dye'],
            $colorist_dye,
            $_POST['tgl_celup'],
            $_POST['lot'],
            $_POST['rol'],
            $_POST['bruto'],
            $_POST['status_warna'],
            $_POST['disposisi'],
            $colorist_qcf,
            $ket,
            $spectro,
            $_POST['nodemand']
        ]);

        if ($sql1) {
            echo "<script>swal({
                title: 'Data has been updated!',
                text: 'Klik Ok untuk input data kembali',
                type: 'success',
            }).then((result) => {
                if (result.value) {
                    window.location.href='?p=Input-Lap-Cwarna-Dye-New&nodemand=$_POST[nodemand]';
                }
            });</script>";
        } else {
            echo "<pre>";
            print_r(sqlsrv_errors());
            echo "</pre>";
            die;
        }

    } else {

        $pelanggan = str_replace("'", "''", $_POST['pelanggan']);
        $order = str_replace("'", "''", $_POST['no_order']);
        $po = str_replace("'", "''", $_POST['no_po']);
        $jns = str_replace("'", "''", $_POST['jenis_kain']);
        $warna = str_replace("'", "''", $_POST['warna']);
        $ket = str_replace("'", "''", $_POST['ket']);
        $spectro = str_replace("'", "''", $_POST['spectro']);
        $colorist_dye = str_replace("'", "''", $_POST['colorist_dye']);
        $colorist_qcf = str_replace("'", "''", $_POST['colorist_qcf']);

        // ====== INSERT (SQL SERVER) ======
        $sql = sqlsrv_query($cond, "
            INSERT INTO db_qc.tbl_cocok_warna_dye
            (
                nokk, nodemand, no_order, no_po, pelanggan, jenis_kain, no_item,
                warna, no_warna, no_mesin, proses, colorist_dye, tgl_celup, lot,
                [shift], dept, jml_roll, bruto, status_warna, disposisi,
                colorist_qcf, ket, spectro, tgl_update
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?,
                ?, 'QCF', ?, ?, ?, ?,
                ?, ?, ?, GETDATE()
            )
        ", [
            $_POST['nokk'],
            $_POST['nodemand'],
            $order,
            $po,
            $pelanggan,
            $jns,
            $_POST['no_item'],
            $warna,
            $_POST['no_warna'],
            $_POST['no_mesin'],
            $_POST['proses_dye'],
            $colorist_dye,
            $_POST['tgl_celup'],
            $_POST['lot'],
            $_POST['shift'],
            $_POST['rol'],
            $_POST['bruto'],
            $_POST['status_warna'],
            $_POST['disposisi'],
            $colorist_qcf,
            $ket,
            $spectro
        ]);

        if ($sql) {
            echo "<script>swal({
                title: 'Data has been saved!',
                text: 'Klik Ok untuk input data kembali',
                type: 'success',
            }).then((result) => {
                if (result.value) {
                    window.location.href='?p=Input-Lap-Cwarna-Dye-New&nodemand=&$_POST[nodemand]';
                }
            });</script>";
        } else {
            echo "<pre>";
            print_r(sqlsrv_errors());
            echo "</pre>";
            die;
        }
    }
}
?>

<?php
$nodemand = $_GET['nodemand'];
$shiftGet = $_GET['shift'];

$msql = sqlsrv_query($cond, "
  SELECT TOP 1 *
  FROM db_qc.tbl_cocok_warna_dye
  WHERE nodemand LIKE ?
    AND [shift] = ?
    AND CAST(tgl_celup AS date) = CAST(GETDATE() AS date)
    AND dept = 'QCF'
", ["%$nodemand%", $shiftGet]);

if ($msql === false) {
    echo "<pre>";
    print_r(sqlsrv_errors());
    echo "</pre>";
    die;
}

$row  = sqlsrv_fetch_array($msql, SQLSRV_FETCH_ASSOC);
$crow = $row ? 1 : 0;

$con1 = sqlsrv_connect("10.0.0.221", array(
    "Database" => "db_dying",
    "UID"      => "sa",
    "PWD"      => "Ind@taichen2024",
    "CharacterSet" => "UTF-8"
));

if ($con1 === false) {
    echo "<pre>";
    print_r(sqlsrv_errors());
    echo "</pre>";
    die;
}

if (!empty($nodemand)) {

    $qryDye1 = sqlsrv_query($con1, "
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
    FROM db_dying.tbl_hasilcelup a
    LEFT JOIN db_dying.tbl_montemp  c ON a.id_montemp = c.id
    LEFT JOIN db_dying.tbl_schedule b ON c.id_schedule = b.id
    WHERE a.nodemand LIKE ?
    ORDER BY a.id DESC
  ", ["%$nodemand%"]);

    if ($qryDye1 === false) {
        echo "<pre>ERROR qryDye1:\n";
        print_r(sqlsrv_errors());
        echo "</pre>";
        die;
    }

    $dtDyeing = sqlsrv_fetch_array($qryDye1, SQLSRV_FETCH_ASSOC);

    if (empty($dtDyeing)) {
        echo "<script>swal({
      title: 'Data tidak dapat ditemukan <br>di hasil celup dyeing!',
      text: 'Klik Ok untuk input data kembali',
      type: 'warning',
    }).then((result) => {
      if (result.value) {
        window.location.href='?p=Input-Lap-Cwarna-Dye-New';
      }
    });</script>";
    } else {

        $qryDye2 = sqlsrv_query($con1, "
      SELECT
        SUM(a.rol)   AS jml_roll,
        SUM(a.bruto) AS jml_kg,
        a.no_mesin,
        a.proses,
        b.colorist
      FROM db_dying.tbl_schedule a
      LEFT JOIN db_dying.tbl_montemp b ON a.id = b.id_schedule
      WHERE a.nokk = ?
        AND a.[STATUS] = 'selesai'
      GROUP BY a.no_mesin, a.proses, b.colorist
    ", [$dtDyeing['nokk']]);

        if ($qryDye2 === false) {
            echo "<pre>ERROR qryDye2:\n";
            print_r(sqlsrv_errors());
            echo "</pre>";
            die;
        }

        $dtSch = sqlsrv_fetch_array($qryDye2, SQLSRV_FETCH_ASSOC);
    }
}
?>

<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
    <div class="box box-info">
        <div class="box-header with-border">
            <center>
                <h3 class="box-title">INPUT DATA <br>COCOK WARNA DYEING. <br> Berdasarkan data yang di input dari Hasil Celup Dyeing.</h3>
            </center>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nodemand" class="col-sm-3 control-label">No Demand</label>
                    <div class="col-sm-4">
                        <input name="nokk" type="hidden" class="form-control" id="nokk" value="<?php echo $rowdb2['PRODUCTIONORDERCODE']; ?>">
                        <input name="nodemand" type="text" class="form-control" id="nodemand" onchange="proses_demand()" value="<?php echo $_GET['nodemand']; ?>" placeholder="No Demand" required>
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
                        <input name="pelanggan" type="text" class="form-control" id="pelanggan" value="<?= $dtDyeing['langganan']; ?>" placeholder="Pelanggan">
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_po" class="col-sm-3 control-label">PO</label>
                    <div class="col-sm-5">
                        <input name="no_po" class="form-control" type="text" id="no_po" value="<?= $dtDyeing['po']; ?>" placeholder="PO" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_order" class="col-sm-3 control-label">No Order</label>
                    <div class="col-sm-4">
                        <input name="no_order" type="text" class="form-control" id="no_order" value="<?= $dtDyeing['no_order']; ?>" placeholder="No Order" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="jenis_kain" class="col-sm-3 control-label">Jenis Kain</label>
                    <div class="col-sm-8">
                        <textarea name="jenis_kain" class="form-control" id="jenis_kain" placeholder="Jenis Kain"><?= stripslashes($dtDyeing['jenis_kain']); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_item" class="col-sm-3 control-label">No Item</label>
                    <div class="col-sm-3">
                        <input name="no_item" type="text" class="form-control" id="no_item" value="<?= $dtDyeing['no_item']; ?>" placeholder="No Item">
                    </div>
                </div>
                <div class="form-group">
                    <label for="warna" class="col-sm-3 control-label">Warna</label>
                    <div class="col-sm-8">
                        <textarea name="warna" class="form-control" id="warna" placeholder="Warna"><?= $dtDyeing['warna']; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_warna" class="col-sm-3 control-label">No Warna</label>
                    <div class="col-sm-8">
                        <textarea name="no_warna" class="form-control" id="no_warna" placeholder="No Warna"><?= $dtDyeing['no_warna']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="no_mesin" class="col-sm-3 control-label">No MC</label>
                    <div class="col-sm-3">
                        <input name="no_mesin" type="text" class="form-control" id="no_mesin" value="<?= $dtDyeing['no_mesin']; ?>" placeholder="No MC">
                    </div>
                </div>
                <div class="form-group">
                    <label for="colorist_dye" class="col-sm-3 control-label">Colorist Dye</label>
                    <div class="col-sm-5">
                        <!-- <input name="colorist_dye" type="text" class="form-control" id="colorist_dye" value="<?= $dtSch['colorist']; ?><?= $dtDyeing['colorist']; ?>" placeholder="Colorist Dye"> -->
                        <input name="colorist_dye" type="text" class="form-control" id="colorist_dye" value="<?= $dtDyeing['acc_keluar']; ?>" placeholder="Colorist Dye">
                    </div>
                </div>
                <div class="form-group">
                    <label for="proses_dye" class="col-sm-3 control-label">Proses</label>
                    <div class="col-sm-5">
                        <input name="proses_dye" type="text" class="form-control" id="proses_dye" value="<?= $dtSch['proses']; ?>" placeholder="Colorist Dye">
                    </div>
                </div>
                <div class="form-group">
                    <label for="tgl_celup" class="col-sm-3 control-label">Tgl Celup</label>
                    <div class="col-sm-4">
                        <div class="input-group date">
                            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                            <input name="tgl_celup" type="text" class="form-control pull-right" id="datepicker1" placeholder="0000-00-00"
                                value="<?php
                                        if ($crow > 0) {
                                            if (!empty($row['tgl_celup'])) {
                                                if ($row['tgl_celup'] instanceof DateTime) {
                                                    echo $row['tgl_celup']->format('Y-m-d');
                                                } else {
                                                    echo substr((string)$row['tgl_celup'], 0, 10);
                                                }
                                            }
                                        } else {
                                            if (!empty($dtDyeing['tgl_buat'])) {
                                                if ($dtDyeing['tgl_buat'] instanceof DateTime) {
                                                    echo $dtDyeing['tgl_buat']->format('Y-m-d');
                                                } else {
                                                    echo substr((string)$dtDyeing['tgl_buat'], 0, 10);
                                                }
                                            }
                                        }
                                        ?>"
                                required />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lot" class="col-sm-3 control-label">Prod. Order/Lot</label>
                    <div class="col-sm-3">
                        <input name="lot" class="form-control" type="text" id="lot" value="<?= $dtDyeing['nokk']; ?>" placeholder="Lot">
                    </div>
                </div>
                <div class="form-group">
                    <label for="qty_bruto" class="col-sm-3 control-label">Qty Bruto</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input name="rol" type="text" class="form-control" id="rol" value="<?= $dtDyeing['rol']; ?>" placeholder="" required>
                            <span class="input-group-addon">Roll</span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input name="bruto" type="text" class="form-control" id="bruto" value="<?= $dtDyeing['bruto']; ?>" placeholder="0.00" required>
                            <span class="input-group-addon">KGs</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status_warna" class="col-sm-3 control-label">Status Warna</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="status_warna" onChange="tampil();">
                            <option value="">Pilih</option>
                            <option value="OK">OK</option>
                            <option value="TOLAK BASAH BEDA WARNA">TOLAK BASAH BEDA WARNA</option>
                            <option value="TOLAK BASAH LUNTUR">TOLAK BASAH LUNTUR</option>
                            <option value="TOLAK BASAH BEDA WARNA + LUNTUR">TOLAK BASAH BEDA WARNA + LUNTUR</option>
                            <option value="DISPOSISI">DISPOSISI</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="colorist_qcf" class="col-sm-3 control-label">Colorist QCF</label>
                    <div class="col-sm-5">
                        <input name="colorist_qcf" class="form-control" type="text" id="colorist_qcf" value="<?php echo $row['colorist_qcf']; ?>" placeholder="Colorist QCF">
                    </div>
                </div>
                <div class="form-group">
                    <label for="ket" class="col-sm-3 control-label">Keterangan</label>
                    <div class="col-sm-8">
                        <textarea name="ket" class="form-control" id="ket" placeholder="Keterangan"><?php echo $row['ket']; ?></textarea>
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
            <?php if ($cekcwarna > 0) { ?>
                <button type="submit" class="btn btn-primary pull-right" name="ubah" value="ubah"><i class="fa fa-edit"></i> Ubah</button>
            <?php } else if ($_GET['nodemand'] != "" and $_GET['shift'] != "" and $cekcwarna == 0) { ?>
                <button type="submit" class="btn btn-primary pull-right" name="simpan" value="simpan"><i class="fa fa-save"></i> Simpan</button>
            <?php } ?>

            <button type="button" class="btn btn-warning pull-left" name="lihat" value="lihat" onClick="window.location.href='?p=Lihat-Data-Cwarna-Dye-New'"><i class="fa fa-search"></i> Lihat Data</button>
        </div>
    </div>
</form>