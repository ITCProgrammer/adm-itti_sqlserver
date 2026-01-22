<?php
include '../../koneksi.php';

$order     = $_GET['order'] ?? '';
$orderline = $_GET['orderline'] ?? '';

$order = trim((string)$order);
$orderline = trim((string)$orderline);

$qDemand = "
    SELECT DISTINCT
        p.CODE,
        p2.PRODUCTIONORDERCODE
    FROM SALESORDER s
    LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE
    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s2.ABSUNIQUEID AND a.FIELDNAME = 'FirstLot'
    LEFT JOIN PRODUCTIONDEMAND p
        ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE
       AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE
    LEFT JOIN (
        SELECT DISTINCT PRODUCTIONDEMANDCODE, PRODUCTIONORDERCODE
        FROM PRODUCTIONDEMANDSTEP
    ) p2 ON p.CODE = p2.PRODUCTIONDEMANDCODE
    WHERE
        a.VALUEBOOLEAN = 1
        AND p.ITEMTYPEAFICODE = 'KFF'
        AND p.PROGRESSSTATUS = '2'
        AND s2.SALESORDERCODE = ?
        AND s2.ORDERLINE = ?
";

$stmt = db2_prepare($conn2, $qDemand);
if (!$stmt) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(["error" => "DB2 prepare failed"]);
    exit;
}

$ok = db2_execute($stmt, [$order, $orderline]);
if (!$ok) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(["error" => "DB2 execute failed"]);
    exit;
}

$data = [];
while ($row = db2_fetch_assoc($stmt)) {
    $data[] = [
        "value" => $row['CODE'],
        "text"  => $row['CODE']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
