<?php
ini_set("error_reporting", 1);
session_start();
include "../koneksi.php";

// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

// Tanggal tutup = hari ini
$tgl_tutup = date('Y-m-d');

// Hitung tanggal kemarin dan hari ini dalam format datetime
$yesterday_start = date('Y-m-d 23:01:00', strtotime('-1 day'));
$today_end = date('Y-m-d 23:00:00');

// 🔒 Cek apakah tanggal tutup sudah pernah di-insert di SQL Server
$cekSql = "SELECT COUNT(*) AS total FROM db_adm.tbl_sisakk_brs WHERE tgl_tutup = ?";
$cekStmt = sqlsrv_query($cona, $cekSql, array($tgl_tutup));

if ($cekStmt === false) {
    echo "❌ Query cek SQL Server gagal: " . print_r(sqlsrv_errors(), true);
    echo "<br>Jendela akan ditutup dalam 5 detik...";
    echo "<script>setTimeout(() => { window.close(); }, 5000);</script>";
    exit;
}

$cekRow = sqlsrv_fetch_array($cekStmt, SQLSRV_FETCH_ASSOC);
$total = (int) ($cekRow['total'] ?? 0);

if ($total > 0) {
    echo "❌ Gagal: Data untuk tanggal $tgl_tutup sudah pernah ditutup.";
    echo "<br>Jendela akan ditutup dalam 5 detik...";
    echo "<script>setTimeout(() => { window.close(); }, 5000);</script>";
    exit;
}

// 🔹 Query utama (ke DB2)
$sql = "
WITH base AS (
    SELECT DISTINCT
        p.OPERATIONCODE,
        p.PRODUCTIONORDERCODE,
        pd.CODE AS DEMANDCODE,
        m.TOTALPRIMARYQUANTITY
    FROM PRODUCTIONPROGRESS p
    LEFT JOIN PRODUCTIONORDER m 
        ON m.CODE = p.PRODUCTIONORDERCODE
    LEFT JOIN PRODUCTIONRESERVATION pr 
        ON m.COMPANYCODE = pr.COMPANYCODE
        AND m.CODE = pr.PRODUCTIONORDERCODE
    LEFT JOIN PRODUCTIONDEMAND pd 
        ON pr.COMPANYCODE = pd.COMPANYCODE
        AND pr.ORDERCODE = pd.CODE
    WHERE
        TIMESTAMP(p.PROGRESSSTARTPROCESSDATE , p.PROGRESSSTARTPROCESSTIME ) 
            BETWEEN TIMESTAMP('$yesterday_start') AND TIMESTAMP('$today_end')
        AND p.OPERATIONCODE IN (
            'RSE1','RSE2','RSE3','RSE4','RSE5',
            'COM1','COM2',
            'SHR1','SHR2','SHR3','SHR4','SHR5',
            'TDR1',
            'SUE1','SUE2','SUE3','SUE4',
            'AIR1','POL1',
            'WET1','WET2','WET3','WET4'
        )
        AND pr.REFERENCEITEM = '1'
        AND p.PROGRESSTEMPLATECODE = 'S01'
        AND EXISTS (
            SELECT 1
            FROM PRODUCTIONDEMANDSTEP ds
            WHERE ds.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
              AND ds.OPERATIONCODE = p.OPERATIONCODE
              AND ds.PROGRESSSTATUS <> 3
        )
),
order_qty AS (
-- Ambil satu quantity unik per order per operation
    SELECT DISTINCT
        OPERATIONCODE,
        PRODUCTIONORDERCODE,
        TOTALPRIMARYQUANTITY
    FROM base
)
SELECT
    b.OPERATIONCODE,
    COUNT(DISTINCT b.DEMANDCODE) AS DEMAND_COUNT,
    ROUND(SUM(o.TOTALPRIMARYQUANTITY), 2) AS QUANTITY
FROM base b
JOIN order_qty o 
    ON b.OPERATIONCODE = o.OPERATIONCODE
   AND b.PRODUCTIONORDERCODE = o.PRODUCTIONORDERCODE
GROUP BY b.OPERATIONCODE
ORDER BY b.OPERATIONCODE;";

// 🟢 Jalankan ke DB2
$stmt = db2_exec($conn2, $sql);

if (!$stmt) {
    echo "❌ Query DB2 gagal: " . db2_stmt_errormsg();
    echo "<br>Jendela akan ditutup dalam 5 detik...";
    echo "<script>setTimeout(() => { window.close(); }, 5000);</script>";
    exit;
}

$rows_inserted = 0;

$insertSql = "
    INSERT INTO db_adm.tbl_sisakk_brs (operationcode, demand_count, qty_order, tgl_tutup)
    VALUES (?, ?, ?, ?)
";

while ($row = db2_fetch_assoc($stmt)) {
    $params = array(
        $row['OPERATIONCODE'],
        (int) $row['DEMAND_COUNT'],
        (float) $row['QUANTITY'],
        $tgl_tutup
    );

    $insertStmt = sqlsrv_query($cona, $insertSql, $params);

    if ($insertStmt === false) {
        echo "❌ Insert SQL Server gagal: " . print_r(sqlsrv_errors(), true);
        echo "<br>Data terakhir: " . htmlspecialchars(json_encode($row));
        echo "<br>Jendela akan ditutup dalam 5 detik...";
        echo "<script>setTimeout(() => { window.close(); }, 5000);</script>";
        exit;
    }

    $rows_inserted++;
}

if ($rows_inserted > 0) {
    echo "✅ $rows_inserted baris berhasil disimpan untuk tanggal $tgl_tutup (periode $yesterday_start s/d $today_end).";
} else {
    echo "⚠️ Tidak ada data untuk disimpan ($yesterday_start s/d $today_end).";
}

// Tutup koneksi
db2_close($conn2);

// sqlsrv_close (bukan $cona->close())
sqlsrv_close($cona);

// Tambahkan pesan auto-close 5 detik
echo "<br>Jendela akan ditutup dalam 5 detik...";
echo "<script>setTimeout(() => { window.close(); }, 5000);</script>";
?>