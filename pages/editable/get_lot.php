<?php
include '../../koneksi.php';

$order     = $_GET['order'] ?? '';
$orderline = $_GET['orderline'] ?? '';

$qDemand = "SELECT DISTINCT
                p.CODE,
                p2.PRODUCTIONORDERCODE
            FROM SALESORDER s
            LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE 
            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s2.ABSUNIQUEID AND a.FIELDNAME = 'FirstLot'
            LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE 
            LEFT JOIN (
                    SELECT DISTINCT PRODUCTIONDEMANDCODE, PRODUCTIONORDERCODE
                    FROM PRODUCTIONDEMANDSTEP
                ) p2 ON p.CODE = p2.PRODUCTIONDEMANDCODE
            WHERE 
                    a.VALUEBOOLEAN = 1
                AND p.ITEMTYPEAFICODE = 'KFF'
                AND p.PROGRESSSTATUS = '2'
                AND s2.SALESORDERCODE = '".addslashes($order)."'
                AND s2.ORDERLINE = '".addslashes($orderline)."'";

$res = db2_exec($conn2, $qDemand);

$data = [];
while ($row = db2_fetch_assoc($res)) {
    $data[] = [
        "value" => $row['PRODUCTIONORDERCODE'],
        "text"  => $row['PRODUCTIONORDERCODE']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
