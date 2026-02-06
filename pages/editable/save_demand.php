<?php
include '../../koneksi.php';
session_start();

if (!isset($cona) || $cona === false) {
    http_response_code(500);
    exit("Koneksi SQL Server (db_adm) gagal");
}

// =====================
// Ambil payload X-Editable
// =====================
$pk     = isset($_POST['pk']) ? trim((string)$_POST['pk']) : '';
$demand = isset($_POST['value']) ? trim((string)$_POST['value']) : ''; // X-Editable -> value

$buyer      = isset($_POST['buyer']) ? trim((string)$_POST['buyer']) : '';
$salesorder = isset($_POST['salesorder']) ? trim((string)$_POST['salesorder']) : '';
$style      = isset($_POST['garment_style']) ? trim((string)$_POST['garment_style']) : '';
$no_po      = isset($_POST['no_po']) ? trim((string)$_POST['no_po']) : '';
$item       = isset($_POST['item']) ? trim((string)$_POST['item']) : '';
$warna      = isset($_POST['warna']) ? trim((string)$_POST['warna']) : '';
$seasonRaw  = isset($_POST['season']) ? trim((string)$_POST['season']) : '';
$season     = ($seasonRaw === '') ? null : $seasonRaw;

$creationUser = isset($_SESSION['nama10']) ? trim((string)$_SESSION['nama10']) : '';


if ($demand === '' || $buyer === '' || $salesorder === '' || $style === '' || $no_po === '' || $item === '' || $warna === '') {
    http_response_code(400);
    exit("Parameter tidak lengkap");
}

// =====================
// CHECK EXIST
// (pakai ISNULL supaya NULL dan '' dianggap sama)
// =====================
$qCheck = "
    SELECT TOP 1 1 AS ok
    FROM db_adm.tbl_firstlot
    WHERE buyer      = ?
      AND [order]    = ?
      AND style      = ?
      AND po         = ?
      AND item       = ?
      AND warna      = ?
      AND ISNULL(season,'') = ISNULL(?, '')
";
$paramsCheck = [$buyer, $salesorder, $style, $no_po, $item, $warna, $season];

$stmtCheck = sqlsrv_query($cona, $qCheck, $paramsCheck);
if ($stmtCheck === false) {
    http_response_code(500);
    exit("Database Error (check): " . print_r(sqlsrv_errors(), true));
}

$exists = (sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC) !== null);

// =====================
// UPDATE / INSERT
// =====================
if ($exists) {
    $q = "
        UPDATE db_adm.tbl_firstlot
        SET demand         = ?,
            buyer          = ?,
            [order]        = ?,
            style          = ?,
            po             = ?,
            item           = ?,
            warna          = ?,
            season         = ?,
            lastupdatetime = GETDATE(),
            lastupdateuser = ?
        WHERE buyer      = ?
          AND [order]    = ?
          AND style      = ?
          AND po         = ?
          AND item       = ?
          AND warna      = ?
          AND ISNULL(season,'') = ISNULL(?, '')
    ";
    $params = [
        $demand, $buyer, $salesorder, $style, $no_po, $item, $warna, $season,
        $creationUser,
        $buyer, $salesorder, $style, $no_po, $item, $warna, $season
    ];
} else {
    $q = "
        INSERT INTO db_adm.tbl_firstlot
            (demand, buyer, [order], style, po, item, warna, season, creationuser)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $params = [$demand, $buyer, $salesorder, $style, $no_po, $item, $warna, $season, $creationUser];
}

$stmt = sqlsrv_query($cona, $q, $params);
if ($stmt === false) {
    http_response_code(500);
    exit("Database Error (save): " . print_r(sqlsrv_errors(), true));
}

echo "OK";
