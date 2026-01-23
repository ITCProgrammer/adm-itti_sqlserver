<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
// ====== Tambahan logika kunci halaman ======
$user_ip = $_SERVER['REMOTE_ADDR'];

// Cek apakah sudah dibuka oleh IP 10.0.5.132
$qLock = "SELECT TOP 1 ip_address, unlocked_at FROM db_adm.tbl_firstlot_lock";
$stmtLock = sqlsrv_query($cona, $qLock);

if ($stmtLock === false) {
    die(print_r(sqlsrv_errors(), true));
}

$lockRow = sqlsrv_fetch_array($stmtLock, SQLSRV_FETCH_ASSOC);
$unlocked = ($lockRow !== null);

// if (!$unlocked && $user_ip === '10.0.5.132') {
//     $qIns = "INSERT INTO db_adm.tbl_firstlot_lock (ip_address, unlocked_at) VALUES (?, GETDATE())";
//     $stmtIns = sqlsrv_query($cona, $qIns, [$user_ip]);
//     if ($stmtIns === false) {
//         die(print_r(sqlsrv_errors(), true));
//     }
//     $unlocked = true;
// }

function firstlot_get_saved($cona, $buyer, $order, $style, $po, $item, $warna, $season) {

    $buyer  = trim((string)$buyer);
    $order  = trim((string)$order);
    $style  = trim((string)$style);
    $po     = trim((string)$po);
    $item   = trim((string)$item);
    $warna  = trim((string)$warna);
    $season = trim((string)$season);

    $seasonParam = ($season === '') ? null : $season;

    $sql = "
        SELECT TOP 1
            demand,
            lot,
            submit_round,
            comm_int_qc,
            comm_indra,
            comm_duc,
            CONVERT(varchar(10), tgl_kirim, 23)    AS tgl_kirim,
            CONVERT(varchar(10), tgl_approved, 23) AS tgl_approved
        FROM db_adm.tbl_firstlot
        WHERE buyer   = ?
          AND [order] = ?
          AND style   = ?
          AND po      = ?
          AND item    = ?
          AND warna   = ?
          AND (
                (season = ?)
             OR (season IS NULL AND ? IS NULL)
          )
    ";

    $params = [$buyer, $order, $style, $po, $item, $warna, $seasonParam, $seasonParam];

    $stmt = sqlsrv_query($cona, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row ?: [];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Summary Laporan First lot </title>
</head>

<style>
    /* efek blur seluruh konten */
    .locked-page {
        position: relative;
        filter: blur(6px);
        pointer-events: none;
        /* nonaktifkan klik */
        user-select: none;
        transition: filter 0.4s ease;
    }

    /* overlay transparan di atas */
    .lock-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(3px);
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-family: 'Segoe UI', sans-serif;
        font-size: 20px;
        z-index: 9999;
        animation: fadeIn 0.5s ease;
    }

    /* kotak pesan di tengah */
    .lock-message {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 16px;
        padding: 40px 60px;
        text-align: center;
        backdrop-filter: blur(10px);
        box-shadow: 0 0 25px rgba(255, 255, 255, 0.15);
    }

    /* animasi masuk */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .lock-message h1 {
        font-size: 28px;
        margin-bottom: 15px;
    }

    .lock-message p {
        color: #ddd;
        font-size: 16px;
    }
</style>

<body>
    <?php
    $Awal    = isset($_POST['awal']) ? $_POST['awal'] : '';
    $Akhir    = isset($_POST['akhir']) ? $_POST['akhir'] : '';
    $Order    = isset($_POST['order']) ? $_POST['order'] : '';
    $Hanger    = isset($_POST['hanger']) ? $_POST['hanger'] : '';
    $Masalah = isset($_POST['masalah']) ? $_POST['masalah'] : '';
    $Dept    = isset($_POST['dept']) ? $_POST['dept'] : '';
    ?>
    <div id="page-content" class="<?php if (!$unlocked) echo 'locked-page'; ?>">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Summary First Lot</h3><br>
                    </div>
                    <div class="box-body">
                        <ul class="nav nav-tabs">
                            <li role="presentation" class="active">
                                <a href="#tab-quality-warna" aria-controls="tab-quality-warna" role="tab" data-toggle="tab">Quality & Warna</a>
                            </li>
                            <li role="presentation">
                                <a href="#tab-warna" aria-controls="tab-warna" role="tab" data-toggle="tab">Warna</a>
                            </li>
                            <li role="presentation">
                                <a href="#tab-quality" aria-controls="tab-quality" role="tab" data-toggle="tab">Quality</a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" style="margin-top: 40px;">

                            <!-- TAB 1: Quality & Warna -->
                            <div role="tabpanel" class="tab-pane fade in active" id="tab-quality-warna">
                                <table class="table table-bordered table-hover table-striped w-100 table-first-lot" style="width:100%">
                                    <thead class="bg-blue">
                                        <tr>
                                            <th>
                                                <div align="center">NO</div>
                                            </th>
                                            <th>
                                                <div align="center">DEMAND</div>
                                            </th>
                                            <th>
                                                <div align="center">BUYER</div>
                                            </th>
                                            <th>
                                                <div align="center">ORDER</div>
                                            </th>
                                            <th>
                                                <div align="center">GARMENT STYLE</div>
                                            </th>
                                            <th>
                                                <div align="center">PO</div>
                                            </th>
                                            <th>
                                                <div align="center">ITEM</div>
                                            </th>
                                            <th>
                                                <div align="center">WARNA</div>
                                            </th>
                                            <th>
                                                <div align="center">LOT</div>
                                            </th>
                                            <th>
                                                <div align="center">SEASON</div>
                                            </th>
                                            <th>
                                                <div align="center">SUBMIT ROUND</div>
                                            </th>
                                            <th>
                                                <div align="center">Comment Internal QC</div>
                                            </th>
                                            <th>
                                                <div align="center">Comment Pak Indra</div>
                                            </th>
                                            <th>
                                                <div align="center">Comment MR DUC</div>
                                            </th>
                                            <th>
                                                <div align="center">Tgl Kirim</div>
                                            </th>
                                            <th>
                                                <div align="center">Tgl Approved</div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $qMain = "SELECT DISTINCT 
                                                s2.ORDERLINE,
                                                ip.BUYER,
                                                s.CODE AS SALESORDER,
                                                s2.INTERNALREFERENCE AS GARMENT_STYLE,
                                                COALESCE(s2.EXTERNALREFERENCE, s.EXTERNALREFERENCE) AS NO_PO,
                                                TRIM(s2.SUBCODE02) || TRIM(s2.SUBCODE03) AS ITEM,
                                                i.WARNA,
                                                s.STATISTICALGROUPCODE AS SEASON
                                            FROM
                                                SALESORDER s
                                            LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE 
                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s2.ABSUNIQUEID AND a.FIELDNAME = 'FirstLotwarnaQuality'
                                            LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE 
                                            LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.CODE = s.CODE AND ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                                            LEFT JOIN ITXVIEWCOLOR i ON i.ITEMTYPECODE = s2.ITEMTYPEAFICODE 
                                                                    AND i.SUBCODE01 = s2.SUBCODE01 
                                                                    AND i.SUBCODE02 = s2.SUBCODE02 
                                                                    AND i.SUBCODE03 = s2.SUBCODE03 
                                                                    AND i.SUBCODE04 = s2.SUBCODE04 
                                                                    AND i.SUBCODE05 = s2.SUBCODE05 
                                                                    AND i.SUBCODE06 = s2.SUBCODE06 
                                                                    AND i.SUBCODE07 = s2.SUBCODE07 
                                                                    AND i.SUBCODE08 = s2.SUBCODE08 
                                                                    AND i.SUBCODE09 = s2.SUBCODE09 
                                                                    AND i.SUBCODE10 = s2.SUBCODE10
                                            WHERE 
                                                a.VALUESTRING = 3
                                                AND p.ITEMTYPEAFICODE = 'KFF'
                                                AND p.PROGRESSSTATUS = '2'";
                                        $execMain = db2_exec($conn2, $qMain);
                                        $no = 1;
                                        ?>
                                        <?php while ($resMain = db2_fetch_assoc($execMain)) { ?>
                                            <?php
                                            $saved = firstlot_get_saved(
                                                        $cona,
                                                        $resMain['BUYER'],
                                                        $resMain['SALESORDER'],
                                                        $resMain['GARMENT_STYLE'],
                                                        $resMain['NO_PO'],
                                                        $resMain['ITEM'],
                                                        $resMain['WARNA'],
                                                        $resMain['SEASON']
                                                    );
                                            ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td>
                                                    <?php if ($saved['demand']) : ?>
                                                        <?= $saved['demand']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-demand"
                                                            data-type="select"
                                                            data-pk="<?= $resMain['SALESORDER'] . '|' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_demand.php"
                                                            data-source="pages/editable/get_demand.php?order=<?= $resMain['SALESORDER']; ?>&orderline=<?= $resMain['ORDERLINE']; ?>"
                                                            data-params='{
                                                                            "buyer":"<?= htmlspecialchars($resMain['BUYER']); ?>",
                                                                            "salesorder":"<?= htmlspecialchars($resMain['SALESORDER']); ?>",
                                                                            "garment_style":"<?= htmlspecialchars($resMain['GARMENT_STYLE']); ?>",
                                                                            "no_po":"<?= htmlspecialchars($resMain['NO_PO']); ?>",
                                                                            "item":"<?= htmlspecialchars($resMain['ITEM']); ?>",
                                                                            "warna":"<?= htmlspecialchars($resMain['WARNA']); ?>",
                                                                            "season":"<?= htmlspecialchars($resMain['SEASON']); ?>"
                                                                        }'>
                                                            Pilih Demand
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Read-only -->
                                                <td>
                                                    <input type="hidden" name="buyer" value="<?= htmlspecialchars($resMain['BUYER']); ?>">
                                                    <?= htmlspecialchars($resMain['BUYER']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="salesorder" value="<?= htmlspecialchars($resMain['SALESORDER']); ?>">
                                                    <?= htmlspecialchars($resMain['SALESORDER']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="garment_style" value="<?= htmlspecialchars($resMain['GARMENT_STYLE']); ?>">
                                                    <?= htmlspecialchars($resMain['GARMENT_STYLE']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="no_po" value="<?= htmlspecialchars($resMain['NO_PO']); ?>">
                                                    <?= htmlspecialchars($resMain['NO_PO']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="item" value="<?= htmlspecialchars($resMain['ITEM']); ?>">
                                                    <?= htmlspecialchars($resMain['ITEM']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="warna" value="<?= htmlspecialchars($resMain['WARNA']); ?>">
                                                    <?= htmlspecialchars($resMain['WARNA']); ?>
                                                </td>
                                                <td>
                                                    <?php if ($saved['lot']) : ?>
                                                        <?= $saved['lot']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-lot"
                                                            data-type="select"
                                                            data-pk="<?= $resMain['SALESORDER'] . '|' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_lot.php"
                                                            data-source="pages/editable/get_lot.php?order=<?= $resMain['SALESORDER']; ?>&orderline=<?= $resMain['ORDERLINE']; ?>"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>">
                                                            Pilih Lot
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td><?= $resMain['SEASON']; ?></td>

                                                <td>
                                                    <?php if ($saved['submit_round']) : ?>
                                                        <?= $saved['submit_round']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-submit-round"
                                                            data-type="text"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_submit_round.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value="">
                                                            pilih round
                                                        </a>
                                                    <?php endif; ?>

                                                </td>

                                                <td>
                                                    <?php if ($saved['comm_int_qc']) : ?>
                                                        <?= $saved['comm_int_qc']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-comm-int-qc"
                                                            data-type="textarea"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_comm_int_qc.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value=""
                                                            data-maxlength="200">
                                                            comment QC
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['comm_indra']) : ?>
                                                        <?= $saved['comm_indra']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-comm-indra"
                                                            data-type="textarea"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_comm_indra.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value="">
                                                            comment Pak Indra
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['comm_duc']) : ?>
                                                        <?= $saved['comm_duc']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-comm-duc"
                                                            data-type="textarea"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_comm_duc.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value="">
                                                            comment Mr Duc
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['tgl_kirim']) : ?>
                                                        <?= $saved['tgl_kirim']; ?>
                                                    <?php else : ?>
                                                        <input type="date"
                                                            class="tgl-kirim"
                                                            data-pk="<?= $saved['demand']; ?>"
                                                            value=""
                                                            style="width:140px;">
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['tgl_approved']) : ?>
                                                        <?= $saved['tgl_approved']; ?>
                                                    <?php else : ?>
                                                        <input type="date"
                                                            class="tgl-approve"
                                                            data-pk="<?= $saved['demand']; ?>"
                                                            value=""
                                                            style="width:140px;">
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- TAB 2: Warna -->
                            <div role="tabpanel" class="tab-pane fade" id="tab-warna">
                                <table class="table table-bordered table-hover table-striped w-100 table-first-lot" style="width:100%">
                                    <thead class="bg-blue">
                                        <tr>
                                            <th>
                                                <div align="center">NO</div>
                                            </th>
                                            <th>
                                                <div align="center">DEMAND</div>
                                            </th>
                                            <th>
                                                <div align="center">BUYER</div>
                                            </th>
                                            <th>
                                                <div align="center">ORDER</div>
                                            </th>
                                            <th>
                                                <div align="center">GARMENT STYLE</div>
                                            </th>
                                            <th>
                                                <div align="center">PO</div>
                                            </th>
                                            <th>
                                                <div align="center">ITEM</div>
                                            </th>
                                            <th>
                                                <div align="center">WARNA</div>
                                            </th>
                                            <th>
                                                <div align="center">LOT</div>
                                            </th>
                                            <th>
                                                <div align="center">SEASON</div>
                                            </th>
                                            <th>
                                                <div align="center">SUBMIT ROUND</div>
                                            </th>
                                            <th>
                                                <div align="center">Comment Internal QC</div>
                                            </th>
                                            <th>
                                                <div align="center">Comment Pak Indra</div>
                                            </th>
                                            <th>
                                                <div align="center">Comment MR DUC</div>
                                            </th>
                                            <th>
                                                <div align="center">Tgl Kirim</div>
                                            </th>
                                            <th>
                                                <div align="center">Tgl Approved</div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $qMain = "SELECT DISTINCT 
                                                s2.ORDERLINE,
                                                ip.BUYER,
                                                s.CODE AS SALESORDER,
                                                s2.INTERNALREFERENCE AS GARMENT_STYLE,
                                                COALESCE(s2.EXTERNALREFERENCE, s.EXTERNALREFERENCE) AS NO_PO,
                                                TRIM(s2.SUBCODE02) || TRIM(s2.SUBCODE03) AS ITEM,
                                                i.WARNA,
                                                s.STATISTICALGROUPCODE AS SEASON
                                            FROM
                                                SALESORDER s
                                            LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE 
                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s2.ABSUNIQUEID AND a.FIELDNAME = 'FirstLotwarnaQuality'
                                            LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE 
                                            LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.CODE = s.CODE AND ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                                            LEFT JOIN ITXVIEWCOLOR i ON i.ITEMTYPECODE = s2.ITEMTYPEAFICODE 
                                                                    AND i.SUBCODE01 = s2.SUBCODE01 
                                                                    AND i.SUBCODE02 = s2.SUBCODE02 
                                                                    AND i.SUBCODE03 = s2.SUBCODE03 
                                                                    AND i.SUBCODE04 = s2.SUBCODE04 
                                                                    AND i.SUBCODE05 = s2.SUBCODE05 
                                                                    AND i.SUBCODE06 = s2.SUBCODE06 
                                                                    AND i.SUBCODE07 = s2.SUBCODE07 
                                                                    AND i.SUBCODE08 = s2.SUBCODE08 
                                                                    AND i.SUBCODE09 = s2.SUBCODE09 
                                                                    AND i.SUBCODE10 = s2.SUBCODE10
                                            WHERE 
                                                a.VALUESTRING = 1
                                                AND p.ITEMTYPEAFICODE = 'KFF'
                                                AND p.PROGRESSSTATUS = '2'";
                                        $execMain = db2_exec($conn2, $qMain);
                                        $no = 1;
                                        ?>
                                        <?php while ($resMain = db2_fetch_assoc($execMain)) { ?>
                                            <?php
                                            $saved = firstlot_get_saved(
                                                        $cona,
                                                        $resMain['BUYER'],
                                                        $resMain['SALESORDER'],
                                                        $resMain['GARMENT_STYLE'],
                                                        $resMain['NO_PO'],
                                                        $resMain['ITEM'],
                                                        $resMain['WARNA'],
                                                        $resMain['SEASON']
                                                    );
                                            ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td>
                                                    <?php if ($saved['demand']) : ?>
                                                        <?= $saved['demand']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-demand"
                                                            data-type="select"
                                                            data-pk="<?= $resMain['SALESORDER'] . '|' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_demand.php"
                                                            data-source="pages/editable/get_demand.php?order=<?= $resMain['SALESORDER']; ?>&orderline=<?= $resMain['ORDERLINE']; ?>"
                                                            data-params='{
                                                    "buyer":"<?= htmlspecialchars($resMain['BUYER']); ?>",
                                                    "salesorder":"<?= htmlspecialchars($resMain['SALESORDER']); ?>",
                                                    "garment_style":"<?= htmlspecialchars($resMain['GARMENT_STYLE']); ?>",
                                                    "no_po":"<?= htmlspecialchars($resMain['NO_PO']); ?>",
                                                    "item":"<?= htmlspecialchars($resMain['ITEM']); ?>",
                                                    "warna":"<?= htmlspecialchars($resMain['WARNA']); ?>",
                                                    "season":"<?= htmlspecialchars($resMain['SEASON']); ?>"
                                                }'>
                                                            Pilih Demand
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Read-only -->
                                                <td>
                                                    <input type="hidden" name="buyer" value="<?= htmlspecialchars($resMain['BUYER']); ?>">
                                                    <?= htmlspecialchars($resMain['BUYER']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="salesorder" value="<?= htmlspecialchars($resMain['SALESORDER']); ?>">
                                                    <?= htmlspecialchars($resMain['SALESORDER']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="garment_style" value="<?= htmlspecialchars($resMain['GARMENT_STYLE']); ?>">
                                                    <?= htmlspecialchars($resMain['GARMENT_STYLE']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="no_po" value="<?= htmlspecialchars($resMain['NO_PO']); ?>">
                                                    <?= htmlspecialchars($resMain['NO_PO']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="item" value="<?= htmlspecialchars($resMain['ITEM']); ?>">
                                                    <?= htmlspecialchars($resMain['ITEM']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="warna" value="<?= htmlspecialchars($resMain['WARNA']); ?>">
                                                    <?= htmlspecialchars($resMain['WARNA']); ?>
                                                </td>
                                                <td>
                                                    <?php if ($saved['lot']) : ?>
                                                        <?= $saved['lot']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-lot"
                                                            data-type="select"
                                                            data-pk="<?= $resMain['SALESORDER'] . '|' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_lot.php"
                                                            data-source="pages/editable/get_lot.php?order=<?= $resMain['SALESORDER']; ?>&orderline=<?= $resMain['ORDERLINE']; ?>"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>">
                                                            Pilih Lot
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td><?= $resMain['SEASON']; ?></td>

                                                <td>
                                                    <?php if ($saved['submit_round']) : ?>
                                                        <?= $saved['submit_round']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-submit-round"
                                                            data-type="text"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_submit_round.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value="">
                                                            pilih round
                                                        </a>
                                                    <?php endif; ?>

                                                </td>

                                                <td>
                                                    <?php if ($saved['comm_int_qc']) : ?>
                                                        <?= $saved['comm_int_qc']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-comm-int-qc"
                                                            data-type="textarea"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_comm_int_qc.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value=""
                                                            data-maxlength="200">
                                                            comment QC
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['comm_indra']) : ?>
                                                        <?= $saved['comm_indra']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-comm-indra"
                                                            data-type="textarea"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_comm_indra.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value="">
                                                            comment Pak Indra
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['comm_duc']) : ?>
                                                        <?= $saved['comm_duc']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-comm-duc"
                                                            data-type="textarea"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_comm_duc.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value="">
                                                            comment Mr Duc
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['tgl_kirim']) : ?>
                                                        <?= $saved['tgl_kirim']; ?>
                                                    <?php else : ?>
                                                        <input type="date"
                                                            class="tgl-kirim"
                                                            data-pk="<?= $saved['demand']; ?>"
                                                            value=""
                                                            style="width:140px;">
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['tgl_approved']) : ?>
                                                        <?= $saved['tgl_approved']; ?>
                                                    <?php else : ?>
                                                        <input type="date"
                                                            class="tgl-approve"
                                                            data-pk="<?= $saved['demand']; ?>"
                                                            value=""
                                                            style="width:140px;">
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- TAB 3: Quality -->
                            <div role="tabpanel" class="tab-pane fade" id="tab-quality">
                                <table class="table table-bordered table-hover table-striped w-100 table-first-lot" style="width:100%">
                                    <thead class="bg-blue">
                                        <tr>
                                            <th>
                                                <div align="center">NO</div>
                                            </th>
                                            <th>
                                                <div align="center">DEMAND</div>
                                            </th>
                                            <th>
                                                <div align="center">BUYER</div>
                                            </th>
                                            <th>
                                                <div align="center">ORDER</div>
                                            </th>
                                            <th>
                                                <div align="center">GARMENT STYLE</div>
                                            </th>
                                            <th>
                                                <div align="center">PO</div>
                                            </th>
                                            <th>
                                                <div align="center">ITEM</div>
                                            </th>
                                            <th>
                                                <div align="center">WARNA</div>
                                            </th>
                                            <th>
                                                <div align="center">LOT</div>
                                            </th>
                                            <th>
                                                <div align="center">SEASON</div>
                                            </th>
                                            <th>
                                                <div align="center">SUBMIT ROUND</div>
                                            </th>
                                            <th>
                                                <div align="center">Comment Internal QC</div>
                                            </th>
                                            <th>
                                                <div align="center">Comment Pak Indra</div>
                                            </th>
                                            <th>
                                                <div align="center">Comment MR DUC</div>
                                            </th>
                                            <th>
                                                <div align="center">Tgl Kirim</div>
                                            </th>
                                            <th>
                                                <div align="center">Tgl Approved</div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $qMain = "SELECT DISTINCT 
                                                s2.ORDERLINE,
                                                ip.BUYER,
                                                s.CODE AS SALESORDER,
                                                s2.INTERNALREFERENCE AS GARMENT_STYLE,
                                                COALESCE(s2.EXTERNALREFERENCE, s.EXTERNALREFERENCE) AS NO_PO,
                                                TRIM(s2.SUBCODE02) || TRIM(s2.SUBCODE03) AS ITEM,
                                                i.WARNA,
                                                s.STATISTICALGROUPCODE AS SEASON
                                            FROM
                                                SALESORDER s
                                            LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE 
                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s2.ABSUNIQUEID AND a.FIELDNAME = 'FirstLotwarnaQuality'
                                            LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE 
                                            LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.CODE = s.CODE AND ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                                            LEFT JOIN ITXVIEWCOLOR i ON i.ITEMTYPECODE = s2.ITEMTYPEAFICODE 
                                                                    AND i.SUBCODE01 = s2.SUBCODE01 
                                                                    AND i.SUBCODE02 = s2.SUBCODE02 
                                                                    AND i.SUBCODE03 = s2.SUBCODE03 
                                                                    AND i.SUBCODE04 = s2.SUBCODE04 
                                                                    AND i.SUBCODE05 = s2.SUBCODE05 
                                                                    AND i.SUBCODE06 = s2.SUBCODE06 
                                                                    AND i.SUBCODE07 = s2.SUBCODE07 
                                                                    AND i.SUBCODE08 = s2.SUBCODE08 
                                                                    AND i.SUBCODE09 = s2.SUBCODE09 
                                                                    AND i.SUBCODE10 = s2.SUBCODE10
                                            WHERE 
                                                a.VALUESTRING = 2
                                                AND p.ITEMTYPEAFICODE = 'KFF'
                                                AND p.PROGRESSSTATUS = '2'";
                                        $execMain = db2_exec($conn2, $qMain);
                                        $no = 1;
                                        ?>
                                        <?php while ($resMain = db2_fetch_assoc($execMain)) { ?>
                                            <?php
                                            $saved = firstlot_get_saved(
                                                        $cona,
                                                        $resMain['BUYER'],
                                                        $resMain['SALESORDER'],
                                                        $resMain['GARMENT_STYLE'],
                                                        $resMain['NO_PO'],
                                                        $resMain['ITEM'],
                                                        $resMain['WARNA'],
                                                        $resMain['SEASON']
                                                    );
                                            ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td>
                                                    <?php if ($saved['demand']) : ?>
                                                        <?= $saved['demand']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-demand"
                                                            data-type="select"
                                                            data-pk="<?= $resMain['SALESORDER'] . '|' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_demand.php"
                                                            data-source="pages/editable/get_demand.php?order=<?= $resMain['SALESORDER']; ?>&orderline=<?= $resMain['ORDERLINE']; ?>"
                                                            data-params='{
                                                    "buyer":"<?= htmlspecialchars($resMain['BUYER']); ?>",
                                                    "salesorder":"<?= htmlspecialchars($resMain['SALESORDER']); ?>",
                                                    "garment_style":"<?= htmlspecialchars($resMain['GARMENT_STYLE']); ?>",
                                                    "no_po":"<?= htmlspecialchars($resMain['NO_PO']); ?>",
                                                    "item":"<?= htmlspecialchars($resMain['ITEM']); ?>",
                                                    "warna":"<?= htmlspecialchars($resMain['WARNA']); ?>",
                                                    "season":"<?= htmlspecialchars($resMain['SEASON']); ?>"
                                                }'>
                                                            Pilih Demand
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Read-only -->
                                                <td>
                                                    <input type="hidden" name="buyer" value="<?= htmlspecialchars($resMain['BUYER']); ?>">
                                                    <?= htmlspecialchars($resMain['BUYER']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="salesorder" value="<?= htmlspecialchars($resMain['SALESORDER']); ?>">
                                                    <?= htmlspecialchars($resMain['SALESORDER']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="garment_style" value="<?= htmlspecialchars($resMain['GARMENT_STYLE']); ?>">
                                                    <?= htmlspecialchars($resMain['GARMENT_STYLE']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="no_po" value="<?= htmlspecialchars($resMain['NO_PO']); ?>">
                                                    <?= htmlspecialchars($resMain['NO_PO']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="item" value="<?= htmlspecialchars($resMain['ITEM']); ?>">
                                                    <?= htmlspecialchars($resMain['ITEM']); ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="warna" value="<?= htmlspecialchars($resMain['WARNA']); ?>">
                                                    <?= htmlspecialchars($resMain['WARNA']); ?>
                                                </td>
                                                <td>
                                                    <?php if ($saved['lot']) : ?>
                                                        <?= $saved['lot']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-lot"
                                                            data-type="select"
                                                            data-pk="<?= $resMain['SALESORDER'] . '|' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_lot.php"
                                                            data-source="pages/editable/get_lot.php?order=<?= $resMain['SALESORDER']; ?>&orderline=<?= $resMain['ORDERLINE']; ?>"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>">
                                                            Pilih Lot
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td><?= $resMain['SEASON']; ?></td>

                                                <td>
                                                    <?php if ($saved['submit_round']) : ?>
                                                        <?= $saved['submit_round']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-submit-round"
                                                            data-type="text"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_submit_round.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value="">
                                                            pilih round
                                                        </a>
                                                    <?php endif; ?>

                                                </td>

                                                <td>
                                                    <?php if ($saved['comm_int_qc']) : ?>
                                                        <?= $saved['comm_int_qc']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-comm-int-qc"
                                                            data-type="textarea"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_comm_int_qc.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value=""
                                                            data-maxlength="200">
                                                            comment QC
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['comm_indra']) : ?>
                                                        <?= $saved['comm_indra']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-comm-indra"
                                                            data-type="textarea"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_comm_indra.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value="">
                                                            comment Pak Indra
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['comm_duc']) : ?>
                                                        <?= $saved['comm_duc']; ?>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)"
                                                            class="editable-comm-duc"
                                                            data-type="textarea"
                                                            data-pk="<?= $resMain['SALESORDER'] . '-' . $resMain['ORDERLINE']; ?>"
                                                            data-url="pages/editable/save_comm_duc.php"
                                                            data-demand="<?= htmlspecialchars($saved['demand'] ?? ''); ?>"
                                                            data-value="">
                                                            comment Mr Duc
                                                        </a>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['tgl_kirim']) : ?>
                                                        <?= $saved['tgl_kirim']; ?>
                                                    <?php else : ?>
                                                        <input type="date"
                                                            class="tgl-kirim"
                                                            data-pk="<?= $saved['demand']; ?>"
                                                            value=""
                                                            style="width:140px;">
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($saved['tgl_approved']) : ?>
                                                        <?= $saved['tgl_approved']; ?>
                                                    <?php else : ?>
                                                        <input type="date"
                                                            class="tgl-approve"
                                                            data-pk="<?= $saved['demand']; ?>"
                                                            value=""
                                                            style="width:140px;">
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$unlocked): ?>
        <div class="lock-overlay">
            <div class="lock-message">
                <h1>🔒 Akses Ditangguhkan</h1>
                <p>Halaman First Lot sementara dikunci karena <br><b><i>tidak ada aktivitas perubahan data.</i></b></p>
                <p>Silakan hubungi DIT untuk akses.</p>
            </div>
        </div>
    <?php endif; ?>

    <script>
        $(function () {

            function initEditable() {
                $('.editable-demand, .editable-lot, .editable-submit-round, .editable-comm-int-qc, .editable-comm-indra, .editable-comm-duc').editable('destroy');

                $('.editable-demand').editable({
                    mode: 'inline',
                    showbuttons: true,
                    onblur: 'ignore',
                    ajaxOptions: { type: 'POST', dataType: 'text' },

                    success: function (response, newValue) {
                        var demandBaru = (newValue || '').toString().trim();
                        var $tr = $(this).closest('tr');

                        $tr.find('.editable-lot').attr('data-demand', demandBaru);
                        $tr.find('.editable-submit-round').attr('data-demand', demandBaru);
                        $tr.find('.editable-comm-int-qc').attr('data-demand', demandBaru);
                        $tr.find('.editable-comm-indra').attr('data-demand', demandBaru);
                        $tr.find('.editable-comm-duc').attr('data-demand', demandBaru);

                        $tr.find('input.tgl-kirim').attr('data-pk', demandBaru);
                        $tr.find('input.tgl-approve').attr('data-pk', demandBaru);

                        // $(this).replaceWith('<span>' + $('<div/>').text(demandBaru).html() + '</span>');
                    }
                });

                // LOT
                $('.editable-lot').editable({
                    mode: 'inline',
                    showbuttons: true,
                    onblur: 'ignore',
                    ajaxOptions: { type: 'POST', dataType: 'text' },

                    params: function (params) {
                        var demand = ($(this).attr('data-demand') || '').toString().trim();
                        params.demand = demand;
                        return params;
                    }
                });

                $('.editable-submit-round').editable({
                    mode: 'inline',
                    showbuttons: true,
                    onblur: 'ignore',
                    ajaxOptions: { type: 'POST', dataType: 'text' },

                    params: function (params) {
                        var demand = ($(this).attr('data-demand') || '').toString().trim();
                        params.demand = demand;
                        return params;
                    }
                });

                $('.editable-comm-int-qc, .editable-comm-indra, .editable-comm-duc').editable({
                    mode: 'inline',
                    showbuttons: true,
                    onblur: 'ignore',
                    ajaxOptions: { type: 'POST', dataType: 'text' },
                    params: function (params) {
                        var demand = ($(this).attr('data-demand') || '').toString().trim();
                        params.demand = demand;
                        return params;
                    }
                });

            }

            $('.table-first-lot').each(function () {
                $(this).DataTable({
                    paging: true,
                    ordering: false,
                    info: false,
                    searching: true,
                    drawCallback: function () {
                    initEditable();
                    }
                });
            });

            initEditable();

            $(document).on('click',
                'a.editable-demand, a.editable-lot, a.editable-submit-round, a.editable-comm-int-qc, a.editable-comm-indra, a.editable-comm-duc',
                function(e){
                    e.stopPropagation();
                }
            );

        });
    </script>

</body>