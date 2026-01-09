<table border="1" cellspacing="0" cellpadding="3" width="80%" style="border-collapse: collapse; margin-top: 10px;">
        <?php
            $tglInput_tbl4 = $_GET['awal']; // misal '2025-05-20'

            // Ubah ke objek DateTime
            $date_tbl4 = new DateTime($tglInput_tbl4);

            // Tanggal sehari sebelumnya jam 23:00:00
            $tanggalAwal_tbl4 = (clone $date_tbl4)->modify('-1 day')->setTime(23, 0, 0);

            // Tanggal input jam 23:00:00
            $tanggalAkhir_tbl4 = (clone $date_tbl4)->setTime(23, 0, 0);

            // Format output
            $tglAwal_tbl4 = $tanggalAwal_tbl4->format('Y-m-d H:i:s');
            $tglAkhir_tbl4 = $tanggalAkhir_tbl4->format('Y-m-d H:i:s');
        ?>
         <td colspan="5" style="text-align:center;"> <center><b>LAPORAN QUANTITY MASUK,KELUAR DAN SISA DEPARTEMEN BRUSHING</b></center></td>
        <tr>
            <td valign="top" width="33%">
                <table border="1" cellspacing="0" cellpadding="3" width="100%" style="border-collapse: collapse;">
                    <tr>
                        <th colspan="3" style="text-align:center;">QUANTITY MASUK</th>
                    </tr>
                    <tr>
                        <th>JENIS PROSES</th>
                        <th>JUMLAH KK</th>
                        <th>QUANTITY</th>
                    </tr>
                    <tr>
                        <?php
                        // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for GARUK KAIN FLEECE (RSE1-RSE5) yang TIDAK ADA step TDR1
                        $query_garuk_fleece = "
                                        SELECT
                                        SUM(t.TOTALPRIMARYQUANTITY) AS TOTAL_QTY,
                                        COUNT(*) AS JUMLAHKK
                                    FROM (
                                        SELECT
                                            p.PRODUCTIONORDERCODE,
                                            MAX(m.TOTALPRIMARYQUANTITY) AS TOTALPRIMARYQUANTITY
                                        FROM
                                            PRODUCTIONPROGRESS p
                                        LEFT JOIN PRODUCTIONORDER m ON
                                            m.CODE = p.PRODUCTIONORDERCODE
                                        WHERE
                                            TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                            AND p.OPERATIONCODE IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                            AND p.PROGRESSTEMPLATECODE = 'S01'
                                            AND NOT EXISTS (
                                                SELECT 1
                                                FROM VIEWPRODUCTIONDEMANDSTEP v2
                                                WHERE v2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                                                AND v2.OPERATIONCODE = 'TDR1'
                                            )
                                        GROUP BY p.PRODUCTIONORDERCODE
                                    ) AS t
                                        ";
                        $result_garuk_fleece = db2_exec($conn2, $query_garuk_fleece);
                        $row_garuk_fleece = db2_fetch_assoc($result_garuk_fleece);
                        ?>
                        <td>GARUK KAIN FLEECE</td>
                        <td style="text-align:center;"><?= $row_garuk_fleece['JUMLAHKK'] ?? 0; ?></td>
                        <td style="text-align:center;"><?= number_format($row_garuk_fleece['TOTAL_QTY'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for GARUK KAIN ANTI PILLING (RSE1-RSE5) yang ada step TDR1
                        $query_garuk_anti_pilling = "
                            SELECT
                                SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY,
                                COUNT(*) AS JUMLAHKK
                            FROM (
                                SELECT DISTINCT
                                    p.PRODUCTIONORDERCODE,
                                    p.PROGRESSTEMPLATECODE,
                                    p.OPERATIONCODE,
                                    m.TOTALPRIMARYQUANTITY,
                                    m.CODE
                                FROM
                                    PRODUCTIONPROGRESS p
                                LEFT JOIN PRODUCTIONORDER m ON
                                    m.CODE = p.PRODUCTIONORDERCODE
                                WHERE
                                    TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                    AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    AND p.OPERATIONCODE IN ('RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5')
                                    AND p.PROGRESSTEMPLATECODE = 'S01'
                                    AND EXISTS (
                                        SELECT 1
                                        FROM VIEWPRODUCTIONDEMANDSTEP v2
                                        WHERE v2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                                        AND v2.OPERATIONCODE IN ('TDR1')
                                    )
                            ) AS t
                        ";
                        $result_garuk_anti_pilling = db2_exec($conn2, $query_garuk_anti_pilling);
                        $row_garuk_anti_pilling = db2_fetch_assoc($result_garuk_anti_pilling);
                        ?>
                        <td>GARUK KAIN ANTI PILLING</td>
                        <td style="text-align:center;"><?= $row_garuk_anti_pilling['JUMLAHKK'] ?? 0; ?></td>
                        <td style="text-align:center;"><?= number_format($row_garuk_anti_pilling['TOTAL_QTY'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        $query = "
                                    SELECT
                                    SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY,
                                    COUNT(*)AS JUMLAHKK
                                FROM
                                    (
                                    SELECT DISTINCT
                                        P.PRODUCTIONORDERCODE,
                                        P.PROGRESSTEMPLATECODE,
                                        P.OPERATIONCODE,
                                        m.TOTALPRIMARYQUANTITY,
                                        m.CODE
                                    FROM
                                        PRODUCTIONPROGRESS P
                                    LEFT JOIN PRODUCTIONORDER m ON
                                        m.CODE = P.PRODUCTIONORDERCODE
                                    WHERE
                                        TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                        AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                        AND PROGRESSTEMPLATECODE = 'S01'
                                        AND p.OPERATIONCODE IN ('SUE3', 'SUE4')
                                        ) AS t
                                    ";

                        $result = db2_exec($conn2, $query);
                        $rowpeachskin = db2_fetch_assoc($result);
                        ?>
                        <td>PEACH SKIN</td>
                        <td style="text-align:center;"><?= $rowpeachskin['JUMLAHKK']; ?></td>
                        <td style="text-align:center;"><?= number_format($rowpeachskin['TOTAL_QTY'], 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for POTONG BULU (SHR1 - SHR5)
                        $query_potongbulu = "
                            SELECT
                                SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY,
                                COUNT(*) AS JUMLAHKK
                            FROM
                                (
                                SELECT
                                P.PRODUCTIONORDERCODE,
                                P.PROGRESSTEMPLATECODE,
                                p.OPERATIONCODE,
                                LISTAGG(P.OPERATIONCODE, ', ') WITHIN GROUP (ORDER BY P.OPERATIONCODE) AS OPERATIONCODES,
                                m.TOTALPRIMARYQUANTITY,
                                m.CODE
                            FROM
                                PRODUCTIONPROGRESS P
                            LEFT JOIN PRODUCTIONORDER m ON
                                m.CODE = P.PRODUCTIONORDERCODE
                            WHERE
                                TIMESTAMP(P.PROGRESSSTARTPROCESSDATE, P.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                AND TIMESTAMP(P.PROGRESSSTARTPROCESSDATE, P.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                AND P.PROGRESSTEMPLATECODE = 'S01'
                                AND P.OPERATIONCODE IN ('SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5')
                            GROUP BY
                                P.PRODUCTIONORDERCODE,
                                P.PROGRESSTEMPLATECODE,
                                p.OPERATIONCODE,
                                m.TOTALPRIMARYQUANTITY,
                                m.CODE
                            ) AS t
                        ";
                        $result_potongbulu = db2_exec($conn2, $query_potongbulu);
                        $row_potongbulu = db2_fetch_assoc($result_potongbulu);
                        ?>
                        <td>POTONG BULU</td>
                        <td style="text-align:center;"><?= $row_potongbulu['JUMLAHKK'] ?? 0; ?></td>
                        <td style="text-align:center;"><?= number_format($row_potongbulu['TOTAL_QTY'] ?? 0, 2); ?></td>

                    </tr>
                    <tr>
                        <?php
                        // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for TDR1
                        $query_tdr1 = "
                            SELECT
                                SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY,
                                COUNT(*) AS JUMLAHKK
                            FROM (
                                SELECT DISTINCT
                                    P.PRODUCTIONORDERCODE,
                                    P.PROGRESSTEMPLATECODE,
                                    P.OPERATIONCODE,
                                    m.TOTALPRIMARYQUANTITY,
                                    m.CODE
                                FROM
                                    PRODUCTIONPROGRESS P
                                LEFT JOIN PRODUCTIONORDER m ON
                                    m.CODE = P.PRODUCTIONORDERCODE
                                WHERE
                                    TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                    AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    AND PROGRESSTEMPLATECODE = 'S01'
                                    AND p.OPERATIONCODE IN ('TDR1')
                            ) AS t
                        ";
                        $result_tdr1 = db2_exec($conn2, $query_tdr1);
                        $row_tdr1 = db2_fetch_assoc($result_tdr1);
                        ?>
                        <td>ANTI PILLING</td>
                        <td style="text-align:center;"><?= $row_tdr1['JUMLAHKK'] ?? 0; ?></td>
                        <td style="text-align:center;"><?= number_format($row_tdr1['TOTAL_QTY'] ?? 0, 2); ?></td>

                    </tr>
                    <tr>
                        <?php
                        // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for AIRO (AIR1)
                        $query_airo = "
                            SELECT
                                SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY,
                                COUNT(*) AS JUMLAHKK
                            FROM (
                                SELECT DISTINCT
                                    P.PRODUCTIONORDERCODE,
                                    P.PROGRESSTEMPLATECODE,
                                    P.OPERATIONCODE,
                                    m.TOTALPRIMARYQUANTITY,
                                    m.CODE
                                FROM
                                    PRODUCTIONPROGRESS P
                                LEFT JOIN PRODUCTIONORDER m ON
                                    m.CODE = P.PRODUCTIONORDERCODE
                                WHERE
                                    TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                    AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    AND PROGRESSTEMPLATECODE = 'S01'
                                    AND p.OPERATIONCODE IN ('AIR1')
                            ) AS t
                        ";
                        $result_airo = db2_exec($conn2, $query_airo);
                        $row_airo = db2_fetch_assoc($result_airo);
                        ?>
                        <td>AIRO</td>
                        <td style="text-align:center;"><?= $row_airo['JUMLAHKK'] ?? 0; ?></td>
                        <td style="text-align:center;"><?= number_format($row_airo['TOTAL_QTY'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for SISIR (COM1, COM2)
                        $query_sisir = "
                            SELECT
                                SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY,
                                COUNT(*) AS JUMLAHKK
                            FROM (
                                SELECT DISTINCT
                                    P.PRODUCTIONORDERCODE,
                                    P.PROGRESSTEMPLATECODE,
                                    P.OPERATIONCODE,
                                    m.TOTALPRIMARYQUANTITY,
                                    m.CODE
                                FROM
                                    PRODUCTIONPROGRESS P
                                LEFT JOIN PRODUCTIONORDER m ON
                                    m.CODE = P.PRODUCTIONORDERCODE
                                WHERE
                                    TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                    AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    AND PROGRESSTEMPLATECODE = 'S01'
                                    AND p.OPERATIONCODE IN ('COM1', 'COM2')
                            ) AS t
                        ";
                        $result_sisir = db2_exec($conn2, $query_sisir);
                        $row_sisir = db2_fetch_assoc($result_sisir);
                        ?>
                        <td>SISIR</td>
                        <td style="text-align:center;"><?= $row_sisir['JUMLAHKK'] ?? 0; ?></td>
                        <td style="text-align:center;"><?= number_format($row_sisir['TOTAL_QTY'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query DB2 for summary of USERPRIMARYQUANTITY and jumlah KK for PEACH SKIN GREIGE (SUE1, SUE2)
                        $query_peachskin_greige = "
                            SELECT
                                SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY,
                                COUNT(*) AS JUMLAHKK
                            FROM (
                                SELECT DISTINCT
                                    P.PRODUCTIONORDERCODE,
                                    P.PROGRESSTEMPLATECODE,
                                    P.OPERATIONCODE,
                                    m.TOTALPRIMARYQUANTITY,
                                    m.CODE
                                FROM
                                    PRODUCTIONPROGRESS P
                                LEFT JOIN PRODUCTIONORDER m ON
                                    m.CODE = P.PRODUCTIONORDERCODE
                                WHERE
                                    TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                    AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    AND PROGRESSTEMPLATECODE = 'S01'
                                    AND p.OPERATIONCODE IN ('SUE1', 'SUE2')
                            ) AS t
                        ";
                        $result_peachskin_greige = db2_exec($conn2, $query_peachskin_greige);
                        $row_peachskin_greige = db2_fetch_assoc($result_peachskin_greige);
                        ?>
                        <td>PEACH SKIN GREIGE</td>
                        <td style="text-align:center;"><?= $row_peachskin_greige['JUMLAHKK'] ?? 0; ?></td>
                        <td style="text-align:center;"><?= number_format($row_peachskin_greige['TOTAL_QTY'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <td>PERBAIKAN</td>
                        <td style="text-align:center;">0</td>
                        <td style="text-align:center;">0.00</td>
                    </tr>
                    <tr style="font-weight:bold; background-color:yellow;">
                        <?php
                        // Hitung total JUMLAHKK dan TOTAL_QTY dari semua proses di atas (kecuali PERBAIKAN)
                        $total_jumlahkk =
                            (int)($row_garuk_fleece['JUMLAHKK'] ?? 0) +
                            (int)($row_garuk_anti_pilling['JUMLAHKK'] ?? 0) +
                            (int)($rowpeachskin['JUMLAHKK'] ?? 0) +
                            (int)($row_potongbulu['JUMLAHKK'] ?? 0) +
                            (int)($row_tdr1['JUMLAHKK'] ?? 0) +
                            (int)($row_airo['JUMLAHKK'] ?? 0) +
                            (int)($row_sisir['JUMLAHKK'] ?? 0) +
                            (int)($row_peachskin_greige['JUMLAHKK'] ?? 0);
                        $total_qty =
                            (float)($row_garuk_fleece['TOTAL_QTY'] ?? 0) +
                            (float)($row_garuk_anti_pilling['TOTAL_QTY'] ?? 0) +
                            (float)($rowpeachskin['TOTAL_QTY'] ?? 0) +
                            (float)($row_potongbulu['TOTAL_QTY'] ?? 0) +
                            (float)($row_tdr1['TOTAL_QTY'] ?? 0) +
                            (float)($row_airo['TOTAL_QTY'] ?? 0) +
                            (float)($row_sisir['TOTAL_QTY'] ?? 0) +
                            (float)($row_peachskin_greige['TOTAL_QTY'] ?? 0);

                        ?>

                        <td style="text-align:center;">TOTAL MASUK</td>
                        <td style="text-align:center;"><?= $total_jumlahkk ?></td>
                        <td style="text-align:center;"><?= number_format($total_qty, 2) ?></td>
                    </tr>
                </table>
            </td>
            <td style="border-right: 2px solid black;"></td>
            <td valign="top" width="33%">
                <table border="1" cellspacing="0" cellpadding="3" width="100%" style="border-collapse: collapse;">
                    <tr>
                        <th colspan="4" style="text-align:center;">QUANTITY KELUAR</th>
                    </tr>
                    <tr>
                        <th colspan="2">JENIS PROSES</th>

                        <th width="26%">JUMLAH KK</th>
                        <th width="24%">QUANTITY</th>
                    </tr>
                    <tr>
                        <?php

                        $F3C20069 = "WITH FilteredProgress AS (
                                        SELECT
                                        DISTINCT 
                                        p.PRODUCTIONORDERCODE,
                                        p.OPERATIONCODE AS CURRENT_STEP,
                                        next_step.OPERATIONCODE AS NEXT_STEP,
                                    --	next_step.STEPNUMBER AS NEXT_STEP_GROUP,
                                        prod.TOTALPRIMARYQUANTITY,
                                        D.SUBCODE02,
	                                    D.SUBCODE03
                                    FROM
                                        PRODUCTIONPROGRESS p
                                    LEFT JOIN PRODUCTIONORDER prod ON
                                        prod.CODE = p.PRODUCTIONORDERCODE
                                    JOIN PRODUCTIONDEMANDSTEP curr_step ON
                                        p.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                        AND p.OPERATIONCODE = curr_step.OPERATIONCODE
                                    JOIN PRODUCTIONDEMANDSTEP next_step ON
                                        next_step.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                        AND next_step.STEPNUMBER = curr_step.STEPNUMBER + 1
                                        AND next_step.OPERATIONCODE = 'FIN1'
                                        LEFT JOIN PRODUCTIONDEMAND D ON D.CODE = curr_step.PRODUCTIONDEMANDCODE
                                    WHERE
                                         D.SUBCODE02 = 'F3C'
	                                    AND D.SUBCODE03 = '20069'
                                        AND p.OPERATIONCODE LIKE '%RSE%'
                                        AND p.PROGRESSTEMPLATECODE = 'S01'
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    )
                                    SELECT
                                        COUNT(*) AS JUMLAHKK,
                                        SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
                                    FROM
                                        FilteredProgress;";

                        $resultFF3C20069 = db2_exec($conn2, $F3C20069);
                        $rowFLEECEF3C20069 = db2_fetch_assoc($resultFF3C20069);


                        $F3C20069F = "WITH FilteredProgress AS (
                                        SELECT
                                        DISTINCT 
                                        p.PRODUCTIONORDERCODE,
                                        p.OPERATIONCODE AS CURRENT_STEP,
                                        next_step.OPERATIONCODE AS NEXT_STEP,
                                    --	next_step.STEPNUMBER AS NEXT_STEP_GROUP,
                                        prod.TOTALPRIMARYQUANTITY,
                                        D.SUBCODE02,
	                                    D.SUBCODE03
                                    FROM
                                        PRODUCTIONPROGRESS p
                                    LEFT JOIN PRODUCTIONORDER prod ON
                                        prod.CODE = p.PRODUCTIONORDERCODE
                                    JOIN PRODUCTIONDEMANDSTEP curr_step ON
                                        p.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                        AND p.OPERATIONCODE = curr_step.OPERATIONCODE
                                    JOIN PRODUCTIONDEMANDSTEP next_step ON
                                        next_step.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                        AND next_step.STEPNUMBER = curr_step.STEPNUMBER + 1
                                        AND next_step.OPERATIONCODE = 'FNJ1'
                                        LEFT JOIN PRODUCTIONDEMAND D ON D.CODE = curr_step.PRODUCTIONDEMANDCODE
                                    WHERE
                                         D.SUBCODE02 = 'F3C'
	                                    AND D.SUBCODE03 = '20069'
                                        AND p.OPERATIONCODE LIKE '%RSE%'
                                        AND p.PROGRESSTEMPLATECODE = 'S01'
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    )
                                    SELECT
                                        COUNT(*) AS JUMLAHKK,
                                        SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
                                    FROM
                                        FilteredProgress;";

                        $resultFF3C20069F = db2_exec($conn2, $F3C20069F);
                        $rowFLEECEF3C20069F = db2_fetch_assoc($resultFF3C20069F);

                        ?>
                        <td width="26%" rowspan="2">GRK FLEECE F3C-20069</td>
                        <td width="24%"><span style="text-align:center;">FIN 1X</span></td>
                        <td style="text-align:center;"><?= number_format($rowFLEECEF3C20069['JUMLAHKK'] ?? 0) ?></td>
                        <td style="text-align:center;"><?= number_format($rowFLEECEF3C20069['TOTAL_QTY'] ?? 0, 2) ?></td>
                    </tr>
                    <tr>
                        <td><span style="text-align:center;">FIN FINAL</span></td>
                        <td style="text-align:center;"><?= number_format($rowFLEECEF3C20069F['JUMLAHKK'] ?? 0) ?></td>
                        <td style="text-align:center;"><?= number_format($rowFLEECEF3C20069F['TOTAL_QTY'] ?? 0, 2) ?></td>
                    </tr>
                    <tr>
                        <?php
                        $queryFLEECE = "WITH FilteredProgress AS (
                                        SELECT
                                        DISTINCT 
                                        p.PRODUCTIONORDERCODE,
                                        p.OPERATIONCODE AS CURRENT_STEP,
                                        next_step.OPERATIONCODE AS NEXT_STEP,
                                    --	next_step.STEPNUMBER AS NEXT_STEP_GROUP,
                                        prod.TOTALPRIMARYQUANTITY
                                    FROM
                                        PRODUCTIONPROGRESS p
                                    LEFT JOIN PRODUCTIONORDER prod ON
                                        prod.CODE = p.PRODUCTIONORDERCODE
                                    JOIN PRODUCTIONDEMANDSTEP curr_step ON
                                        p.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                        AND p.OPERATIONCODE = curr_step.OPERATIONCODE
                                    JOIN PRODUCTIONDEMANDSTEP next_step ON
                                        next_step.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                        AND next_step.STEPNUMBER = curr_step.STEPNUMBER + 1
                                        AND next_step.OPERATIONCODE = 'FIN1'
                                    WHERE
                                        p.OPERATIONCODE LIKE '%RSE%'
                                        AND p.PROGRESSTEMPLATECODE = 'S01'
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    )
                                    SELECT
                                        COUNT(*) AS JUMLAHKK,
                                        SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
                                    FROM
                                        FilteredProgress;";
                        $resultF = db2_exec($conn2, $queryFLEECE);
                        $rowFLEECE = db2_fetch_assoc($resultF);

                        $queryFINAL = "WITH FilteredProgress AS (
                                        SELECT DISTINCT 
                                            p.PRODUCTIONORDERCODE,
                                            p.OPERATIONCODE AS CURRENT_STEP,
                                            next_step.OPERATIONCODE AS NEXT_STEP,
                                            --  next_step.STEPNUMBER AS NEXT_STEP_GROUP,
                                            prod.TOTALPRIMARYQUANTITY
                                        FROM
                                            PRODUCTIONPROGRESS p
                                        LEFT JOIN PRODUCTIONORDER prod ON
                                            prod.CODE = p.PRODUCTIONORDERCODE
                                        JOIN PRODUCTIONDEMANDSTEP curr_step ON
                                            p.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                            AND p.OPERATIONCODE = curr_step.OPERATIONCODE
                                        JOIN PRODUCTIONDEMANDSTEP next_step ON
                                            next_step.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                            AND next_step.STEPNUMBER = curr_step.STEPNUMBER + 1
                                            AND next_step.OPERATIONCODE = 'FNJ1'
                                        WHERE
                                            p.OPERATIONCODE LIKE '%RSE%'
                                            AND p.PROGRESSTEMPLATECODE = 'S01'
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    )
                                    SELECT
                                        COUNT(*) AS JUMLAHKK,
                                        SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTYY
                                    FROM
                                        FilteredProgress
                                ";
                        $resultFINAL = db2_exec($conn2, $queryFINAL);
                        $rowFINAL = db2_fetch_assoc($resultFINAL);
                        ?>
                        <td rowspan="2">GRK FLEECE</td>
                        <td><span style="text-align:center;">FIN 1X</span></td>
                        <td style="text-align:center;"><?= number_format($rowFLEECE['JUMLAHKK'] ?? 0); ?></td>
                        <td style="text-align:center;"><?= number_format($rowFLEECE['TOTAL_QTY'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <td>FIN FINAL</td>
                        <td style="text-align:center;"><?= number_format($rowFINAL['JUMLAHKK'] ?? 0); ?></td>
                        <td style="text-align:center;"><?= number_format($rowFINAL['TOTAL_QTY'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        $queryAP = "WITH FilteredProgress AS (
                                        SELECT DISTINCT 
                                            p.PRODUCTIONORDERCODE,
                                            p.OPERATIONCODE AS CURRENT_STEP,
                                            next_step.OPERATIONCODE AS NEXT_STEP,
                                            --  next_step.STEPNUMBER AS NEXT_STEP_GROUP,
                                            prod.TOTALPRIMARYQUANTITY
                                        FROM
                                            PRODUCTIONPROGRESS p
                                        LEFT JOIN PRODUCTIONORDER prod ON
                                            prod.CODE = p.PRODUCTIONORDERCODE
                                        JOIN PRODUCTIONDEMANDSTEP curr_step ON
                                            p.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                            AND p.OPERATIONCODE = curr_step.OPERATIONCODE
                                        JOIN PRODUCTIONDEMANDSTEP next_step ON
                                            next_step.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                            AND next_step.STEPNUMBER = curr_step.STEPNUMBER + 1
                                            AND next_step.OPERATIONCODE = 'TDR1  '
                                        WHERE
                                            p.OPERATIONCODE LIKE '%RSE%'
                                            AND p.PROGRESSTEMPLATECODE = 'S01'
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    )
                                    SELECT
                                        COUNT(*) AS JUMLAHKK,
                                        SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
                                    FROM
                                        FilteredProgress;
                                ";

                        $resultAP = db2_exec($conn2, $queryAP);
                        $rowAP = db2_fetch_assoc($resultAP);

                        $queryAPf = "WITH FilteredProgress AS (
                                        SELECT DISTINCT 
                                            p.PRODUCTIONORDERCODE,
                                            p.OPERATIONCODE AS CURRENT_STEP,
                                            next_step.OPERATIONCODE AS NEXT_STEP,
                                            --  next_step.STEPNUMBER AS NEXT_STEP_GROUP,
                                            prod.TOTALPRIMARYQUANTITY
                                        FROM
                                            PRODUCTIONPROGRESS p
                                        LEFT JOIN PRODUCTIONORDER prod ON
                                            prod.CODE = p.PRODUCTIONORDERCODE
                                        JOIN PRODUCTIONDEMANDSTEP curr_step ON
                                            p.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                            AND p.OPERATIONCODE = curr_step.OPERATIONCODE
                                        JOIN PRODUCTIONDEMANDSTEP next_step ON
                                            next_step.PRODUCTIONORDERCODE = curr_step.PRODUCTIONORDERCODE
                                            AND next_step.STEPNUMBER = curr_step.STEPNUMBER + 1
                                            AND next_step.OPERATIONCODE = 'FNJ1'
                                        WHERE
                                            p.OPERATIONCODE LIKE '%RSE%'
                                            AND p.PROGRESSTEMPLATECODE = 'S01'
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$tglAwal_tbl4')
                                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$tglAkhir_tbl4')
                                    )
                                    SELECT
                                        COUNT(*) AS JUMLAHKK,
                                        SUM(TOTALPRIMARYQUANTITY) AS TOTAL_QTY
                                    FROM
                                        FilteredProgress;
                                ";
                        $resultAPf = db2_exec($conn2, $queryAPf);
                        $rowAPf = db2_fetch_assoc($resultAPf);

                        ?>
                        <td rowspan="2">GRK AP</td>
                        <td><span style="text-align:center;">TAMBAH OBAT</span></td>
                        <td style="text-align:center;"><?= number_format($rowAP['JUMLAHKK'] ?? 0) ?></td>
                        <td style="text-align:center;"><?= number_format($rowAP['TOTAL_QTY'] ?? 0, 2) ?></td>
                    </tr>
                    <tr>
                        <td><span style="text-align:center;">FIN FINAL</span></td>
                        <td style="text-align:center;"><?= number_format($rowAPf['JUMLAHKK'] ?? 0) ?></td>
                        <td style="text-align:center;"><?= number_format($rowAPf['TOTAL_QTY'] ?? 0, 2) ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query for POTONG BULU LAIN-LAIN
                        $query_potongbulu_lainlain = "
                            SELECT
                                SUM(qty) AS qty_potongbulu_lain_lain,
                                COUNT(*) AS jumlah_kk
                            FROM (
                                SELECT 
                                    nokk,
                                    GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                    MAX(langganan) AS langganan,
                                    MAX(proses) AS proses,
                                    SUM(qty) AS qty
                                FROM
                                    tbl_produksi tp
                                WHERE
                                    tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                    AND proses IN ('POTONG BULU LAIN-LAIN (Khusus)', 'POTONG BULU LAIN-LAIN (Bantu)')
                                GROUP BY
                                    nokk
                            ) AS t
                        ";
                        $result_potongbulu_lainlain = mysqli_query($conb, $query_potongbulu_lainlain);
                        $row_potongbulu_lainlain = mysqli_fetch_assoc($result_potongbulu_lainlain);
                        ?>
                        <td colspan="2">POTONG BULU LAIN-LAIN</td>
                        <td style="text-align:center;"><?= number_format($row_potongbulu_lainlain['jumlah_kk'] ?? 0); ?></td>
                        <td style="text-align:center;"><?= number_format($row_potongbulu_lainlain['qty_potongbulu_lain_lain'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query for ANTI PILLING LAIN-LAIN
                        $query_anti_pilling_lainlain = "
                            SELECT
                                SUM(qty) AS qty_anti_pilling_lain_lain,
                                COUNT(*) AS jumlah_kk
                            FROM (
                                SELECT 
                                    nokk,
                                    GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                    MAX(langganan) AS langganan,
                                    MAX(proses) AS proses,
                                    SUM(qty) AS qty
                                FROM
                                    tbl_produksi tp
                                WHERE
                                    tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                    AND proses IN ('ANTI PILLING LAIN-LAIN (Khusus)', 'ANTI PILLING LAIN-LAIN (Bantu)')
                                GROUP BY
                                    nokk
                            ) AS t";
                        $result_anti_pilling_lainlain = mysqli_query($conb, $query_anti_pilling_lainlain);
                        $row_anti_pilling_lainlain = mysqli_fetch_assoc($result_anti_pilling_lainlain);
                        ?>
                        <td colspan="2">ANTI PILLING LAIN-LAIN</td>
                        <td style="text-align:center;"><?= number_format($row_anti_pilling_lainlain['jumlah_kk'] ?? 0); ?></td>
                        <td style="text-align:center;"><?= number_format($row_anti_pilling_lainlain['qty_anti_pilling_lain_lain'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query for ARIO   
                        $query_ario = "
                            SELECT
                                SUM(qty) AS qty_ario,
                                COUNT(*) AS jumlah_kk
                            FROM (
                                SELECT 
                                    nokk,
                                    GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                    MAX(langganan) AS langganan,
                                    MAX(proses) AS proses,
                                    SUM(qty) AS qty
                                FROM
                                    tbl_produksi tp
                                WHERE
                                    tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                    AND proses IN ('AIRO (Normal)')
                                GROUP BY
                                    nokk
                            ) AS t";
                        $result_ario = mysqli_query($conb, $query_ario);
                        $row_ario = mysqli_fetch_assoc($result_ario);
                        ?>
                        <td colspan="2">AIRO</td>
                        <td style="text-align:center;"><?= number_format($row_ario['jumlah_kk'] ?? 0); ?></td>
                        <td style="text-align:center;"><?= number_format($row_ario['qty_ario'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query for SISIR
                        $query_sisir = "
                            SELECT
                                SUM(qty) AS qty_sisir,
                                COUNT(*) AS jumlah_kk
                            FROM (
                                SELECT 
                                    nokk,
                                    GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                    MAX(langganan) AS langganan,
                                    MAX(proses) AS proses,
                                    SUM(qty) AS qty
                                FROM
                                    tbl_produksi tp
                                WHERE
                                    tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                    AND proses IN ('SISIR ANTI PILLING (Normal)', 'SISIR BANTU (FIN) (Bantu)', 'SISIR LAIN-LAIN (Bantu)')
                                GROUP BY
                                    nokk
                            ) AS t";
                        $result_sisir = mysqli_query($conb, $query_sisir);
                        $row_sisirr = mysqli_fetch_assoc($result_sisir);
                        ?>
                        <td colspan="2">SISIR</td>
                        <td style="text-align:center;"><?= number_format($row_sisirr['jumlah_kk'] ?? 0); ?></td>
                        <td style="text-align:center;"><?= number_format($row_sisirr['qty_sisir'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query for PEACH SKIN
                        $query_peach = "
                        SELECT
                            SUM(qty) AS qty_peach,
                            COUNT(*) AS jumlah_kk
                        FROM (
                            SELECT 
                                nokk,
                                GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                MAX(langganan) AS langganan,
                                MAX(proses) AS proses,
                                SUM(qty) AS qty
                            FROM
                                tbl_produksi tp
                            WHERE
                                tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                AND proses IN ('PEACH SKIN (Normal)')
                            GROUP BY
                                nokk
                        ) AS t";
                        $result_peach = mysqli_query($conb, $query_peach);
                        $row_peachh = mysqli_fetch_assoc($result_peach);
                        ?>
                        <td colspan="2">PEACH SKIN</td>
                        <td style="text-align:center;"><?= number_format($row_peachh['jumlah_kk'] ?? 0); ?></td>
                        <td style="text-align:center;"><?= number_format($row_peachh['qty_peach'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query for PEACH SKIN GREIGE
                        $query_peach_greige = "
                        SELECT
                            SUM(qty) AS qty_peach_greige,
                            COUNT(*) AS jumlah_kk
                            FROM (
                            SELECT 
                                nokk,
                                GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                MAX(langganan) AS langganan,
                                MAX(proses) AS proses,
                                SUM(qty) AS qty
                            FROM
                                tbl_produksi tp
                            WHERE
                                tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                AND proses IN ('PEACHSKIN GREIGE (Normal)')
                                GROUP BY
                                nokk
                            ) AS t";

                        $result_peach_greige = mysqli_query($conb, $query_peach_greige);
                        $row_peach_greige = mysqli_fetch_assoc($result_peach_greige);
                        ?>
                        <td colspan="2">PEACH SKIN GREIGE</td>
                        <td style="text-align:center;"><?= number_format($row_peach_greige['jumlah_kk'] ?? 0); ?></td>
                        <td style="text-align:center;"><?= number_format($row_peach_greige['qty_peach_greige'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query for GARUK GREIGE
                        $query_garuk_greige = "
                            SELECT
                                SUM(qty) AS qty_garuk_greige,
                                COUNT(*) AS jumlah_kk
                            FROM (
                                SELECT 
                                    nokk,
                                    GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                    MAX(langganan) AS langganan,
                                    MAX(proses) AS proses,
                                    SUM(qty) AS qty
                                FROM
                                    tbl_produksi tp
                                WHERE
                                    tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                    AND proses IN ('GARUK GREIGE (Normal)')
                                GROUP BY
                                    nokk
                            ) AS t";
                        $result_garuk_greige = mysqli_query($conb, $query_garuk_greige);
                        $row_garuk_greige = mysqli_fetch_assoc($result_garuk_greige);
                        ?>
                        <td colspan="2">GARUK GREIGE</td>
                        <td style="text-align:center;"><?= number_format($row_garuk_greige['jumlah_kk'] ?? 0); ?></td>
                        <td style="text-align:center;"><?= number_format($row_garuk_greige['qty_garuk_greige'] ?? 0, 2); ?></td>
                    </tr>
                    <tr>
                        <?php
                        // Query for GARUK FLEECE TAMBAH OBAT
                        $query_garuk_fleece_tambah_obat = "
                            SELECT
                                SUM(qty) AS qty_garuk_fleece_tambah_obat,
                                COUNT(*) AS jumlah_kk
                            FROM (
                                SELECT 
                                    nokk,
                                    GROUP_CONCAT(nodemand ORDER BY nodemand SEPARATOR ', ') AS nodemand,
                                    MAX(langganan) AS langganan,
                                    MAX(proses) AS proses,
                                    SUM(qty) AS qty
                                FROM
                                    tbl_produksi tp
                                WHERE
                                    tgl_buat BETWEEN '$tglAwal_tbl4' AND '$tglAkhir_tbl4'
                                    AND proses IN ('GARUK FLEECE (Normal)')
                                GROUP BY
                                    nokk
                            ) AS t";
                        $result_garuk_fleece_tambah_obat = mysqli_query(
                            $conb,
                            $query_garuk_fleece_tambah_obat
                        );
                        $row_garuk_fleece_tambah_obat = mysqli_fetch_assoc($result_garuk_fleece_tambah_obat);
                        ?>
                        <td colspan="2">GARUK FLEECE TAMBAH OBAT</td>
                        <td style="text-align:center;"><?= number_format($row_garuk_fleece_tambah_obat['jumlah_kk'] ?? 0); ?></td>
                        <td style="text-align:center;"><?= number_format($row_garuk_fleece_tambah_obat['qty_garuk_fleece_tambah_obat'] ?? 0, 2); ?></td>
                    </tr>
                    <tr style="font-weight:bold; background-color:yellow;">
                        <?php
                        // Hitung total JUMLAHKK dan TOTAL_QTY dari semua proses di atas (kecuali PERBAIKAN)
                        $total_jumlahkkk =
                            ((int)($row_potongbulu_lainlain['jumlah_kk'] ?? 0)) +
                            ((int)($row_anti_pilling_lainlain['jumlah_kk'] ?? 0)) +
                            ((int)($row_ario['jumlah_kk'] ?? 0)) +
                            ((int)($row_sisirr['jumlah_kk'] ?? 0)) +
                            ((int)($row_peachh['jumlah_kk'] ?? 0)) +
                            ((int)($row_peach_greige['jumlah_kk'] ?? 0)) +
                            ((int)($row_garuk_greige['jumlah_kk'] ?? 0)) +
                            ((int)($row_garuk_fleece_tambah_obat['jumlah_kk'] ?? 0)) +
                            ((int)($rowFLEECEF3C20069['JUMLAHKK'] ?? 0)) +
                            ((int)($rowFLEECEF3C20069F['JUMLAHKK'] ?? 0)) +
                            ((int)($rowFLEECE['JUMLAHKK'] ?? 0)) +
                            ((int)($rowFINAL['JUMLAHKK'] ?? 0)) +
                            ((int)($rowAP['JUMLAHKK'] ?? 0));
                        ((int)($rowAPf['JUMLAHKK'] ?? 0));

                        $total_qtyy =
                            ((float)($row_potongbulu_lainlain['qty_potongbulu_lain_lain'] ?? 0)) +
                            ((float)($row_anti_pilling_lainlain['qty_anti_pilling_lain_lain'] ?? 0)) +
                            ((float)($row_ario['qty_ario'] ?? 0)) +
                            ((float)($row_sisirr['qty_sisir'] ?? 0)) +
                            ((float)($row_peachh['qty_peach'] ?? 0)) +
                            ((float)($row_peach_greige['qty_peach_greige'] ?? 0)) +
                            ((float)($row_garuk_greige['qty_garuk_greige'] ?? 0)) +
                            ((float)($row_garuk_fleece_tambah_obat['qty_garuk_fleece_tambah_obat'] ?? 0)) +
                            ((float)($rowFLEECEF3C20069['TOTAL_QTY'] ?? 0)) +
                            ((float)($rowFLEECEF3C20069F['TOTAL_QTY'] ?? 0)) +
                            ((float)($rowFINAL['TOTAL_QTY'] ?? 0)) +
                            ((float)($rowFLEECE['TOTAL_QTY'] ?? 0)) +
                            ((float)($rowAP['TOTAL_QTY'] ?? 0)) +
                            ((float)($rowAPf['TOTAL_QTY'] ?? 0));
                        ?>

                        <td style="text-align:center;" colspan="2">TOTAL</td>
                        <!-- <td style="text-align:center;">&nbsp;</td> -->
                        <td style="text-align:center;"><?= $total_jumlahkkk ?></td>
                        <td style="text-align:center;"><?= number_format($total_qtyy, 2) ?></td>
                    </tr>
        </tr>
    </table>
    </td>
    <td style="border-right: 2px solid black;"></td>
    <td valign="top" width="33%">
        <table border="1" cellspacing="0" cellpadding="3" width="100%" style="border-collapse: collapse;">
            <tr>
                <th colspan="3" style="text-align:center;">QUANTITY SISA</th>
            </tr>
            <tr>
                <th>JENIS PROSES</th>
                <th>JUMLAH KK</th>
                <th>QUANTITY</th>
            </tr>
            <tr>
                <td>GARUK FLEECE</td>
                <?php
                // $total_kk_garukfleece = ($row_garuk_fleece['JUMLAHKK'] ?? 0) - ($row_garuk_greige['jumlah_kk'] ?? 0);
                // $total_qty_garukfleece = ($row_garuk_fleece['TOTAL_QTY'] ?? 0) - ($row_garuk_greige['qty_garuk_fleece_tambah_obat'] ?? 0);
                ?>
                <td style="text-align:center;">
                    <?= $total_kk_garukfleece; ?>
                </td>
                <td style="text-align:center;">
                    <?= number_format($total_qty_garukfleece, 2); ?>
                </td>
            </tr>
            <tr>

                <td>POTONG BULU FLEECE</td>
                <?php
                // $total_kk_potongbulu = ($row_potongbulu['JUMLAHKK'] ?? 0) - ($row_potongbulu_lainlain['jumlah_kk'] ?? 0);
                // $total_qtybulu = ($row_potongbulu['TOTAL_QTY'] ?? 0) - ($row_potongbulu_lainlain['qty_potongbulu_lain_lain'] ?? 0);
                ?>

                <td style="text-align:center;">
                    <?= $total_kk_potongbulu; ?>
                </td>
                <td style="text-align:center;">
                    <?= number_format($total_qtybulu, 2); ?>
                </td>
            </tr>
            <tr>
                <td>GARUK ANTI PILLING</td>
                <?php
                // $total_kk_garuk_kain_fleece = ($row_garuk_anti_pilling['JUMLAHKK'] ?? 0) - ($row_garuk_fleece_tambah_obat['jumlah_kk'] ?? 0);
                // $total_qty_garuk_kain_fleece = ($row_garuk_anti_pilling['TOTAL_QTY'] ?? 0) - ($row_garuk_fleece_tambah_obat['qty_garuk_fleece_tambah_obat'] ?? 0);
                ?>
                <td style="text-align:center;">
                    <?= $total_kk_garuk_kain_fleece; ?>
                </td>
                <td style="text-align:center;">
                    <?= number_format($total_qty_garuk_kain_fleece, 2); ?>
                </td>
            </tr>
            <tr>
                <td>SISIR LAIN-LAIN</td>
                <?php
                // $total_kk_sisir = ($row_sisir['JUMLAHKK'] ?? 0) - ($row_sisirr['jumlah_kk'] ?? 0);
                // $total_qty_sisir = ($row_sisir['TOTAL_QTY'] ?? 0) - ($row_sisirr['qty_sisir'] ?? 0);
                ?>
                <td style="text-align:center;">
                    <?= $total_kk_sisir; ?>
                </td>
                <td style="text-align:center;">
                    <?= number_format($total_qty_sisir, 2); ?>
                </td>
            </tr>
            <tr>
                <td>POTONG BULU LAIN LAIN</td>
                <?php
                // $total_kk_potongbulu_lainlain = ($row_potongbulu['JUMLAHKK'] ?? 0) - ($row_potongbulu_lainlain['jumlah_kk'] ?? 0);
                // $total_qty_potongbulu_lainlain = ($row_potongbulu['TOTAL_QTY'] ?? 0) - ($row_potongbulu_lainlain['qty_potongbulu_lain_lain'] ?? 0);
                ?>
                <td style="text-align:center;">
                    <?= $total_kk_potongbulu_lainlain; ?>
                </td>
                <td style="text-align:center;">
                    <?= number_format($total_qty_potongbulu_lainlain, 2); ?>
                </td>
            </tr>
            <tr>
                <td>OVEN ANTI PILLING</td>
                <td style="text-align:center;">&nbsp;</td>
                <td style="text-align:center;">&nbsp;</td>
            </tr>
            <tr>
                <td>PEACH SKIN</td>
                <?php
                // $total_kk_peachskin = ($rowpeachskin['JUMLAHKK'] ?? 0) - ($row_peach['jumlah_kk'] ?? 0);
                // $total_qty_peachskin = ($rowpeachskin['TOTAL_QTY'] ?? 0) - ($row_peach['qty_peach'] ?? 0);
                ?>
                <td style="text-align:center;">
                    <?= $total_kk_peachskin; ?>
                </td>
                <td style="text-align:center;">
                    <?= number_format($total_qty_peachskin, 2); ?>
                </td>

            </tr>
            <tr>
                <td>PEACH + GARUK GREIGE</td>
                <td style="text-align:center;">&nbsp;</td>
                <td style="text-align:center;">&nbsp;</td>
            </tr>
            <tr>
                <td>PEACH + GARUK CELUP</td>
                <td style="text-align:center;">&nbsp;</td>
                <td style="text-align:center;">&nbsp;</td>
            </tr>
            <tr>
                <td>WET SUEDING</td>
                <td style="text-align:center;">&nbsp;</td>
                <td style="text-align:center;">&nbsp;</td>
            </tr>
            <tr>
                <td>OVEN ANTI PILLING LAIN-</td>
                <td style="text-align:center;">&nbsp;</td>
                <td style="text-align:center;">&nbsp;</td>
            </tr>
            <tr>
                <td>PEACH SKIN GREIGE</td>
                <?php
                // $total_kk_peachskin_greige = ($row_peachskin_greige['JUMLAHKK'] ?? 0) - ($row_peach_greige['jumlah_kk'] ?? 0);
                // $total_qty_peachskin_greige = ($row_peachskin_greige['TOTAL_QTY'] ?? 0) - ($row_peach_greige['qty_peach_greige'] ?? 0);
                ?>
                <td style="text-align:center;">
                    <?= $total_kk_peachskin_greige; ?>
                </td>
                <td style="text-align:center;">
                    <?= number_format($total_qty_peachskin_greige, 2); ?>
                </td>
            </tr>
            <tr>
                <td>GARUK GREIGE</td>
                <td style="text-align:center;">&nbsp;</td>
                <td style="text-align:center;">&nbsp;</td>
            </tr>
            <tr>
                <td>BALIK KAIN SIAP FINISHING</td>
                <td style="text-align:center;">&nbsp;</td>
                <td style="text-align:center;">&nbsp;</td>
            </tr>
            <tr style="font-weight:bold; background-color:yellow;">
                <td style="text-align:center;">TOTAL SISA</td>
                <td style="text-align:center;">
                    <?php
                    $total_sisa_kk = $total_kk_garukfleece + $total_kk_potongbulu + $total_kk_garuk_kain_fleece + $total_kk_sisir + $total_kk_potongbulu_lainlain + $total_kk_peachskin + $total_kk_peachskin_greige;
                    echo $total_sisa_kk;
                    ?>
                </td>
                <td style="text-align:center;">
                    <?php
                    $total_sisa_qty = $total_qty_garukfleece + $total_qtybulu + $total_qty_garuk_kain_fleece + $total_qty_sisir + $total_qty_potongbulu_lainlain + $total_qty_peachskin + $total_qty_peachskin_greige;
                    echo number_format($total_sisa_qty, 2);
                    ?>
                </td>
            </tr>
        </table>
    </td>
    </tr>
    </table>
    <br><br>