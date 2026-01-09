<table border="1" cellspacing="0" cellpadding="3" width="100%" style="border-collapse: collapse; margin-top: 10px;">
        <td colspan="10" style="text-align:center;">
            <center><b>LAPORAN PENCAPAIAN KARTU KERJA DEPARTEMEN BRUSHING</b></center>
        </td>
        <tr style="background: #fff;">
            <th rowspan="2" style="text-align:center; vertical-align:middle;">JENIS KARTU KERJA</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle;">TARGET</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle;">KARTU KERJA MASUK</th>
            <th colspan="3" style="text-align:center;">KARTU KERJA KELUAR</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle;">KARTU KERJA SISA</th>
            <th colspan="2" style="text-align:center;">PERSENTASE KARTU KERJA TIDAK TERCAPAI</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle;">PENCAPAIAN</th>
        </tr>
        <tr style="background: #fff;">
            <th style="text-align:center;">TERCAPAI</th>
            <th style="text-align:center;">TIDAK TERCAPAI LIBUR</th>
            <th style="text-align:center;">TIDAK TERCAPAI PROSES</th>
            <th style="text-align:center;">LIBUR</th>
            <th style="text-align:center;">PROSES</th>
        </tr>
        <?php
        // Query for total not achieved for DOMESTIC
        $query_not_achieved_DOM =
            "select
                COUNT(*) as total_not_achieved
            from
                (
            with ranked_data as (
                select
                    *,
                    row_number() over (partition by nokk
                order by
                    tgl_buat desc) as rn,
                    TIMESTAMPDIFF(hour,
                        CONCAT(tgl_proses_in, ' ', jam_in),
                        CONCAT(tgl_proses_out, ' ', jam_out)
                    ) as durasi_jam
                from
                    tbl_produksi
                where
                    tgl_buat >= '$start'
                    and tgl_buat < '$end'
                    and no_order like '%DOM%'
            )
            select
                *
            from
                ranked_data
            where
                rn = 1
                and durasi_jam > 30) as t";

        $result_not_achieved_DOM = mysqli_query($conb, $query_not_achieved_DOM);
        $row_not_achieved_DOM = mysqli_fetch_assoc($result_not_achieved_DOM);
        $total_not_achieved_DOM = $row_not_achieved_DOM['total_not_achieved'] ?? 0;

        // Query for total not achieved for REP
        $query_not_achieved_REP =
            "select
                            COUNT(*) as total_count
                        from
                            (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by nokk
                            order by
                                tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam
                            from
                                tbl_produksi
                            where
                                tgl_buat >= '$start'
                                and tgl_buat < '$end'
                                and no_order like '%REP%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and durasi_jam > 12) as t";
        $result_not_achieved_REP = mysqli_query($conb, $query_not_achieved_REP);
        $row_not_achieved_REP = mysqli_fetch_assoc($result_not_achieved_REP);
        $total_not_achieved_REP = $row_not_achieved_REP['total_not_achieved'] ?? 0;

        // Query for total not achieved for MBE
        $query_not_achieved_MBE =
            "select
            COUNT(*) as total_not_achieved
            from
            (
            with ranked_data as (
            select
                *,
                row_number() over (partition by nokk
            order by
                tgl_buat desc) as rn,
                TIMESTAMPDIFF(hour,
                CONCAT(tgl_proses_in, ' ', jam_in),
                CONCAT(tgl_proses_out, ' ', jam_out)
                ) as durasi_jam
            from
                tbl_produksi
            where
                tgl_buat >= '$start'
                and tgl_buat < '$end'
                and (no_order like '%MBE%' or no_order like '%MNB%')
            )
            select
            *
            from
            ranked_data
            where
            rn = 1
            and durasi_jam > 12) as t";
        $result_not_achieved_MBE = mysqli_query($conb, $query_not_achieved_MBE);
        $row_not_achieved_MBE = mysqli_fetch_assoc($result_not_achieved_MBE);
        $total_not_achieved_MBE = $row_not_achieved_MBE['total_not_achieved'] ?? 0;

        // Query for total not achieved for SAM
        $query_not_achieved_SAM =
            "select
                            COUNT(*) as total_count
                        from
                            (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by nokk
                            order by
                                tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam
                            from
                                tbl_produksi
                            where
                                tgl_buat >= '$start'
                                and tgl_buat < '$end'
                                and no_order like '%SAM%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and durasi_jam > 12) as t";
        $result_not_achieved_SAM = mysqli_query($conb, $query_not_achieved_SAM);
        $row_not_achieved_SAM = mysqli_fetch_assoc($result_not_achieved_SAM);
        $total_not_achieved_SAM = $row_not_achieved_SAM['total_not_achieved'] ?? 0;

        // Query for total not achieved for BP
        $query_not_achieved_BP =
            "select
                            COUNT(*) as total_count
                        from
                            (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by nokk
                            order by
                                tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam
                            from
                                tbl_produksi
                            where
                                tgl_buat >= '$start'
                                and tgl_buat < '$end'
                                and no_order like '%BP%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and durasi_jam > 12) as t";
        $result_not_achieved_BP = mysqli_query($conb, $query_not_achieved_BP);
        $row_not_achieved_BP = mysqli_fetch_assoc($result_not_achieved_BP);
        $total_not_achieved_BP = $row_not_achieved_BP['total_not_achieved'] ?? 0;

        // Query for total not achieved for RET
        $query_not_achieved_RET =
            "select
                            COUNT(*) as total_count
                        from
                            (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by nokk
                            order by
                                tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam
                            from
                                tbl_produksi
                            where
                                tgl_buat >= '$start'
                                and tgl_buat < '$end'
                                and no_order like '%RET%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and durasi_jam > 12) as t";
        $result_not_achieved_RET = mysqli_query($conb, $query_not_achieved_RET);
        $row_not_achieved_RET = mysqli_fetch_assoc($result_not_achieved_RET);
        $total_not_achieved_RET = $row_not_achieved_RET['total_not_achieved'] ?? 0;
        ?>

        <tr>
            <td>KARTU KERJA BIASA</td>
            <?php
            $query_KARTU_KERJA_BIASA =
                "SELECT COUNT(*) AS TOTAL_KARTU_KERJA_BIASA
                FROM (
                    SELECT DISTINCT 
                        p.PRODUCTIONORDERCODE,
                        p2.PRODUCTIONORDERCODE
                    FROM
                        PRODUCTIONPROGRESS p
                    LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                    LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                    WHERE
                        TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$start')
                                    AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$end')
                        AND p.PROGRESSTEMPLATECODE = 'S01'  
                        AND d.DLVSALORDLINESALORDCNTCODE IN ('DOMESTIC', 'EXPORT', 'OPN')
                        AND p.OPERATIONCODE IN (
                            'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                            'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                            'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                        )
                ) AS t;
            ";


            $resultKARTU_KERJA_BIASA = db2_exec($conn2, $query_KARTU_KERJA_BIASA);
            $row_KARTU_KERJA_BIASA = db2_fetch_assoc($resultKARTU_KERJA_BIASA);
            $total_KARTU_KERJA_BIASA = $row_KARTU_KERJA_BIASA['TOTAL_KARTU_KERJA_BIASA'] ?? 0;
            ?>
            <td>30 Jam</td>
            <td align="center"><?= htmlspecialchars($total_KARTU_KERJA_BIASA); ?></td>

            <?php
            
            //DOMESTIC
            $query = "select
                            COUNT(*) as total_count
                        from
                            (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by nokk
                            order by
                                tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam
                            from
                                tbl_produksi
                            where
                                tgl_buat >= '$start'
                                and tgl_buat < '$end'
                                and no_order like '%DOM%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and durasi_jam < 30) as t
                        ";
            $result = mysqli_query($conb, $query);
            $row = mysqli_fetch_assoc($result);
            $total_tercapai_30_jamDOM = $row['total_count'] ?? 0;



            $query_tidak_tercapai_30_jam_libur =
                "select
                    COUNT(*) as TOTAL_TIDAK_TERCAPAI_LIBUR
                from
                (
                    with ranked_data as (
                        select
                            *,
                            row_number() over (partition by nokk order by tgl_buat desc) as rn,
                            TIMESTAMPDIFF(hour,
                                CONCAT(tgl_proses_in, ' ', jam_in),
                                CONCAT(tgl_proses_out, ' ', jam_out)
                            ) as durasi_jam,
                            DAYOFWEEK(tgl_buat) as hari_ke
                        from
                            tbl_produksi
                        where
                            tgl_buat >= '$start'
                            and tgl_buat < '$end'
                            and no_order like '%DOM%'
                    )
                    select
                        *
                    from
                        ranked_data
                    where
                        rn = 1
                        and durasi_jam > 30
                        and hari_ke = 1 -- 1: Minggu saja
                ) as t";
                     


            $result_tidak_tercapai_30_jam_libur = mysqli_query($conb, $query_tidak_tercapai_30_jam_libur);
            $row_tidak_tercapai_30_jam_libur = mysqli_fetch_assoc($result_tidak_tercapai_30_jam_libur);
            $total_tidak_tercapai_30_jam_libur = $row_tidak_tercapai_30_jam_libur['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;


            ?>
            <td align="center"><?= htmlspecialchars($total_tercapai_30_jamDOM); ?></td>
        <!--            <td align="center"><?= htmlspecialchars($total_tidak_tercapai_30_jam_libur); ?></td>-->
            <td align="center"><?= htmlspecialchars($total_not_achieved_DOM); ?></td>
            <td align="center">
                <?php
                $sisakartu_kerja_biasa = $total_KARTU_KERJA_BIASA - $total_tercapai_30_jamDOM ;
                echo htmlspecialchars($sisakartu_kerja_biasa) ?? 0;
                ?>
            </td>
            <!-- persentase libur -->
            <td align="center">

                <?php
                if ($total_tercapai_30_jamDOM + $total_tidak_tercapai_30_jam_libur > 0) {
                    $persentase_tidak_tercapai_libur = ($total_not_achieved_DOM / ($total_tercapai_30_jamDOM + $total_tidak_tercapai_30_jam_libur)) * 100;
                    echo number_format($persentase_tidak_tercapai_libur, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_tercapai_30_jamDOM + $total_not_achieved_DOM > 0) {
                    $persentase_tidak_tercapai_proses = ($total_not_achieved_DOM / ($total_tercapai_30_jamDOM + $total_not_achieved_DOM)) * 100;
                    echo number_format($persentase_tidak_tercapai_proses, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <td align="center">
                <?php
                if (($total_tercapai_30_jamDOM + $total_tidak_tercapai_30_jam_libur + $total_not_achieved_DOM) > 0) {
                    $percentage = ($total_tidak_tercapai_30_jam_libur / ($total_tercapai_30_jamDOM + $total_tidak_tercapai_30_jam_libur + $total_not_achieved_DOM)) * 100;
                    echo number_format($percentage, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>GANTI KAIN EKSTERNAL</td>
            <?php
            $query_GANTI_KAIN_EKSTERNAL =
                "SELECT COUNT(*) AS TOTAL_GANTI_KAIN_EKSTERNAL
                FROM (
                    SELECT DISTINCT 
                        p.PRODUCTIONORDERCODE,
                        p2.PRODUCTIONORDERCODE
                    FROM
                        PRODUCTIONPROGRESS p
                    LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                    LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                    WHERE
                        TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$start 23:00:00')
                                    AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$end 23:00:00')
                        AND p.PROGRESSTEMPLATECODE = 'S01'
                        AND d.DLVSALORDLINESALORDCNTCODE IN ('REPLCEXP')
                        AND p.OPERATIONCODE IN (
                            'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                            'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                            'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                        )
                ) AS t
                 ";
            $resultGANTI_KAIN_EKSTERNAL = db2_exec($conn2, $query_GANTI_KAIN_EKSTERNAL);
            $row_GANTI_KAIN_EKSTERNAL = db2_fetch_assoc($resultGANTI_KAIN_EKSTERNAL);
            $total_GANTI_KAIN_EKSTERNAL = $row_GANTI_KAIN_EKSTERNAL['TOTAL_GANTI_KAIN_EKSTERNAL'] ?? 0;
            ?>
            <td>12 Jam</td>
            <td align="center"><?= htmlspecialchars($total_GANTI_KAIN_EKSTERNAL); ?></td>
            <?php
            $query = "select
                            COUNT(*) as total_count
                        from
                            (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by nokk
                            order by
                                tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam
                            from
                                tbl_produksi
                            where
                                tgl_buat >= '$start'
                                and tgl_buat < '$end'
                                and no_order like '%REP%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and durasi_jam < 12) as t";

            $result = mysqli_query($conb, $query);
            $row = mysqli_fetch_assoc($result);
            $total_tercapai_12_jamREP = $row['total_count'] ?? 0;


            $query_tidak_tercapai_12_jam_libur = "
                select
                    COUNT(*) as TOTAL_TIDAK_TERCAPAI_LIBUR
                from
                (
                    with ranked_data as (
                        select
                            *,
                            row_number() over (partition by nokk order by tgl_buat desc) as rn,
                            TIMESTAMPDIFF(hour,
                                CONCAT(tgl_proses_in, ' ', jam_in),
                                CONCAT(tgl_proses_out, ' ', jam_out)
                            ) as durasi_jam,
                            DAYOFWEEK(tgl_buat) as hari_ke
                        from
                            tbl_produksi
                        where
                            tgl_buat >= '$start'
                            and tgl_buat < '$end'
                            and no_order like '%REP%'
                    )
                    select
                        *
                    from
                        ranked_data
                    where
                        rn = 1
                        and durasi_jam > 12
                        and hari_ke = 1 -- 1: Minggu saja
                ) as t";

            $result_tidak_tercapai_12_jam_libur = mysqli_query($conb, $query_tidak_tercapai_12_jam_libur);
            $row_tidak_tercapai_12_jam_libur = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_libur);
            $total_tidak_tercapai_12_jam_liburREP = $row_tidak_tercapai_12_jam_libur['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
            ?>
            <td align="center"><?= htmlspecialchars($total_tercapai_12_jamREP) ?? 0; ?></td>
        <!--            <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_liburREP) ?? 0; ?></td>-->
            <td align="center"><?= htmlspecialchars($total_not_achieved_REP) ?? 0; ?></td>
            <td align="center">
                <?php
                $sisakartu_kerja_ganti_kain = $total_GANTI_KAIN_EKSTERNAL - $total_tercapai_12_jamREP;
                echo htmlspecialchars($sisakartu_kerja_ganti_kain) ?? 0;
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_tercapai_12_jamREP > 0) {
                    $persentase_tidak_tercapai_liburREP = ($total_tidak_tercapai_12_jam_liburREP / $total_tercapai_12_jamREP) * 100;
                    echo number_format($persentase_tidak_tercapai_liburREP, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_tercapai_12_jamREP > 0) {
                    $persentase_tidak_tercapai_prosesREP = ($total_not_achieved_REP / $total_tercapai_12_jamREP) * 100;
                    echo number_format($persentase_tidak_tercapai_prosesREP, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <!-- pencapaian -->
            <td align="center">
                <?php
                if (($total_tercapai_12_jamREP + $total_tidak_tercapai_12_jam_liburREP + $total_not_achieved_REP) > 0) {
                    $percentage = ($total_tidak_tercapai_12_jam_liburREP / ($total_tercapai_12_jamREP + $total_tidak_tercapai_12_jam_liburREP + $total_not_achieved_REP)) * 100;
                    echo number_format($percentage, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>

            </td>
        </tr>

        <tr>
            <td>MINI BULK</td>
            <?php
            $query_MINI_BULK =
                "SELECT COUNT(*) AS TOTAL_MINI_BULK
                FROM (
                    SELECT DISTINCT 
                        p.PRODUCTIONORDERCODE,
                        p2.PRODUCTIONORDERCODE
                    FROM
                        PRODUCTIONPROGRESS p
                    LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                    LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                    WHERE
                        TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$start')
                                    AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$end')
                        AND p.PROGRESSTEMPLATECODE = 'S01'
                        AND d.DLVSALORDLINESALORDCNTCODE IN ('MNB','MBE')
                        AND p.OPERATIONCODE IN (
                            'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                            'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                            'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                        )
                ) AS t
                 ";
            $resultMINI_BULK = db2_exec($conn2, $query_MINI_BULK);
            $row_MINI_BULK = db2_fetch_assoc($resultMINI_BULK);
            $total_MINI_BULK = $row_MINI_BULK['TOTAL_MINI_BULK'] ?? 0;
            ?>
            <td>12 Jam</td>
            <td align="center"><?= htmlspecialchars($total_MINI_BULK); ?></td>
            <?php
            $query = "select
                            COUNT(*) as total_count
                        from
                            (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by nokk
                            order by
                                tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam
                            from
                                tbl_produksi
                            where
                                tgl_buat >= '$start'
                                and tgl_buat < '$end'
                                and no_order like '%MBE%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and durasi_jam < 12) as t";
            $result = mysqli_query($conb, $query);
            $row = mysqli_fetch_assoc($result);
            $total_tercapai_12_jamMBE = $row['total_count'] ?? 0;



            $query_tidak_tercapai_12_jam_hari_libur = "
                SELECT COUNT(*) AS TOTAL_TIDAK_TERCAPAI_LIBUR
                FROM (
                    SELECT DISTINCT *, 
                        TIMESTAMP(tgl_proses_in, jam_in) AS start_datetime,
                        CASE 
                            WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                            ELSE TIMESTAMP(tgl_proses_out, jam_out)
                        END AS end_datetime,
                        TIMESTAMPDIFF(MINUTE, TIMESTAMP(tgl_proses_in, jam_in), 
                            CASE 
                                WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                                ELSE TIMESTAMP(tgl_proses_out, jam_out)
                            END
                        ) / 60 AS durasi_jam,
                        CASE 
                            WHEN TIMESTAMPDIFF(MINUTE, TIMESTAMP(tgl_proses_in, jam_in), 
                                CASE 
                                    WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                                    ELSE TIMESTAMP(tgl_proses_out, jam_out)
                                END
                            ) / 60 > 12 THEN '> 12 jam'
                            ELSE '≤ 12 jam'
                        END AS kategori
                    FROM tbl_produksi
                    WHERE tgl_buat >= '$start' AND tgl_buat < '$end'
                    AND no_order LIKE '%MBE%'
                    AND DAYOFWEEK(tgl_buat) IN (1, 7) -- Hari Minggu (1) dan Sabtu (7)
                ) AS t
                WHERE kategori = '> 12 jam'";

            $result_tidak_tercapai_12_jam_hari_libur = mysqli_query($conb, $query_tidak_tercapai_12_jam_hari_libur);
            $row_tidak_tercapai_12_jam_hari_libur = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_hari_libur);
            $total_tidak_tercapai_12_jam_hari_liburMBE = $row_tidak_tercapai_12_jam_hari_libur['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
            ?>
            <td align="center"><?= htmlspecialchars($total_tercapai_12_jamMBE) ?? 0; ?></td>
        <!--            <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_hari_liburMBE) ?? 0; ?></td>-->
            <td align="center"><?= htmlspecialchars($total_not_achieved_MBE) ?? 0; ?></td>
            <td align="center">
                <?php
                $sisakartu_kerja_mini_bulk = $total_MINI_BULK - $total_tercapai_12_jamMBE;
                echo htmlspecialchars($sisakartu_kerja_mini_bulk) ?? 0;
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_tercapai_12_jamMBE > 0) {
                    $persentase_tidak_tercapai_liburMBE = ($total_tidak_tercapai_12_jam_hari_liburMBE / $total_tercapai_12_jamMBE) * 100;
                    echo number_format($persentase_tidak_tercapai_liburMBE, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_tercapai_12_jamMBE > 0) {
                    $persentase_tidak_tercapai_prosesMBE = ($total_not_achieved_MBE / $total_tercapai_12_jamMBE) * 100;
                    echo number_format($persentase_tidak_tercapai_prosesMBE, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <!-- pencapaian -->
            <td align="center">
                <?php
                if (($total_tercapai_12_jamMBE + $total_tidak_tercapai_12_jam_hari_liburMBE + $total_not_achieved_MBE) > 0) {
                    $percentage = ($total_tidak_tercapai_12_jam_hari_liburMBE / ($total_tercapai_12_jamMBE + $total_tidak_tercapai_12_jam_hari_liburMBE + $total_not_achieved_MBE)) * 100;
                    echo number_format($percentage, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>SALESMAN SAMPLE</td>
            <td>12 Jam</td>
            <?php
            $query_SALESMAN_SAMPLE =
                "SELECT COUNT(*) AS TOTAL_SALESMAN_SAMPLE
                FROM (
                    SELECT DISTINCT 
                        p.PRODUCTIONORDERCODE,
                        p2.PRODUCTIONORDERCODE
                    FROM
                        PRODUCTIONPROGRESS p
                    LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                    LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                    WHERE
                        TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$start')
                        AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$end')
                        AND p.PROGRESSTEMPLATECODE = 'S01'
                        AND d.DLVSALORDLINESALORDCNTCODE IN ('SAMPDOM', 'SAMPLE')
                        AND p.OPERATIONCODE IN (
                            'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                            'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                            'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                        )
                ) AS t;
            ";

            $result_SALESMAN_SAMPLE = db2_exec($conn2, $query_SALESMAN_SAMPLE);
            $row_SALESMAN_SAMPLE = db2_fetch_assoc($result_SALESMAN_SAMPLE);
            $total_SALESMAN_SAMPLE = $row_SALESMAN_SAMPLE['TOTAL_SALESMAN_SAMPLE'] ?? 0;
            ?>
            <td align="center"><?= htmlspecialchars($total_SALESMAN_SAMPLE); ?></td>
            <?php
            $query_SALESMAN_SAMPLE_12_JAM =
                "select
                            COUNT(*) as total_count
                        from
                            (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by nokk
                            order by
                                tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam
                            from
                                tbl_produksi
                            where
                                tgl_buat >= '$start'
                                and tgl_buat < '$end'
                                and no_order like '%SAM%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and durasi_jam < 12) as t";
            $result_SALESMAN_SAMPLE_12_JAM = mysqli_query($conb, $query_SALESMAN_SAMPLE_12_JAM);
            $row_SALESMAN_SAMPLE_12_JAM = mysqli_fetch_assoc($result_SALESMAN_SAMPLE_12_JAM);
            $total_SALESMAN_SAMPLE_12_JAMSAM = $row_SALESMAN_SAMPLE_12_JAM['TOTAL_SALESMAN_SAMPLE_12_JAM'] ?? 0;


            $query_tidak_tercapai_12_jam_libur_SAM = "
            SELECT COUNT(*) AS TOTAL_TIDAK_TERCAPAI_LIBUR
            FROM (
                SELECT DISTINCT *, 
                    TIMESTAMP(tgl_proses_in, jam_in) AS start_datetime,
                    CASE 
                        WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                        ELSE TIMESTAMP(tgl_proses_out, jam_out)
                    END AS end_datetime,
                    TIMESTAMPDIFF(MINUTE, TIMESTAMP(tgl_proses_in, jam_in), 
                        CASE 
                            WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                            ELSE TIMESTAMP(tgl_proses_out, jam_out)
                        END
                    ) / 60 AS durasi_jam,
                    CASE 
                        WHEN TIMESTAMPDIFF(MINUTE, TIMESTAMP(tgl_proses_in, jam_in), 
                            CASE 
                                WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                                ELSE TIMESTAMP(tgl_proses_out, jam_out)
                            END
                        ) / 60 > 12 THEN '> 12 jam'
                        ELSE '≤ 12 jam'
                    END AS kategori
                FROM tbl_produksi
                WHERE tgl_buat >= '$start' AND tgl_buat < '$end'
                AND no_order LIKE '%SAM%'
                AND DAYOFWEEK(tgl_buat) IN (1, 7) -- Hari Minggu (1) dan Sabtu (7)
            ) AS t
            WHERE kategori = '> 12 jam'";

            $result_tidak_tercapai_12_jam_libur_SAM = mysqli_query($conb, $query_tidak_tercapai_12_jam_libur_SAM);
            $row_tidak_tercapai_12_jam_libur_SAM = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_libur_SAM);
            $total_tidak_tercapai_12_jam_libur_SAM = $row_tidak_tercapai_12_jam_libur_SAM['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
            ?>
            <td align="center"><?= htmlspecialchars($total_SALESMAN_SAMPLE_12_JAMSAM); ?></td>
        <!-- <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_libur_SAM); ?></td>-->
            <td align="center"><?= htmlspecialchars($total_not_achieved_SAM); ?></td>
            <td align="center">
                <?php
                $sisakartu_kerja_salesman_sample = $total_SALESMAN_SAMPLE - $total_SALESMAN_SAMPLE_12_JAMSAM;
                echo htmlspecialchars($sisakartu_kerja_salesman_sample) ?? 0;
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_SALESMAN_SAMPLE_12_JAMSAM > 0) {
                    $persentase_tidak_tercapai_libur_SAM = ($total_tidak_tercapai_12_jam_libur_SAM / $total_SALESMAN_SAMPLE_12_JAMSAM) * 100;
                    echo number_format($persentase_tidak_tercapai_libur_SAM, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_SALESMAN_SAMPLE_12_JAMSAM > 0) {
                    $persentase_tidak_tercapai_proses_SAM = ($total_not_achieved_SAM / $total_SALESMAN_SAMPLE_12_JAMSAM) * 100;
                    echo number_format($persentase_tidak_tercapai_proses_SAM, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <!-- pencapaian -->
            <td align="center">
                <?php
                if (($total_SALESMAN_SAMPLE_12_JAMSAM + $total_tidak_tercapai_12_jam_libur_SAM + $total_not_achieved_SAM) > 0) {
                    $percentage = ($total_tidak_tercapai_12_jam_libur_SAM / ($total_SALESMAN_SAMPLE_12_JAMSAM + $total_tidak_tercapai_12_jam_libur_SAM + $total_not_achieved_SAM)) * 100;
                    echo number_format($percentage, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>DEVELOPMENT SAMPLE</td>
            <td>12 Jam</td>
            <?php
            $query_DEVELOPMENT_SAMPLE =
                "SELECT COUNT(*) AS TOTAL_DEVELOPMENT_SAMPLE
                    FROM (
                        SELECT DISTINCT 
                            p.PRODUCTIONORDERCODE,
                            p2.PRODUCTIONORDERCODE
                        FROM
                            PRODUCTIONPROGRESS p
                        LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                        LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                        WHERE
                            TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$start')
                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$end')
                            AND p.PROGRESSTEMPLATECODE = 'S01'
                            AND d.DLVSALORDLINESALORDCNTCODE IN (
                                'DEVBP', 'DEVINDGA', 'DEVINDGB', 'DEVINDGC', 'DEVINDGD', 'DEVINDMA', 'DEVINDMB', 
                                'DEVINDMC', 'DEVINDMD', 'DEVINDME', 'DEVINDMF', 'DEVINDMG', 'DEVINDMH', 'DEVINDMI', 
                                'DEVINDTA', 'DEVINDTB', 'TAS', 'TR'
                            )
                            AND p.OPERATIONCODE IN (
                                'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                                'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                                'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                            )
                    ) AS t;
                ";

            $result_DEVELOPMENT_SAMPLE = db2_exec($conn2, $query_DEVELOPMENT_SAMPLE);
            $row_DEVELOPMENT_SAMPLE = db2_fetch_assoc($result_DEVELOPMENT_SAMPLE);
            $total_DEVELOPMENT_SAMPLE = $row_DEVELOPMENT_SAMPLE['TOTAL_DEVELOPMENT_SAMPLE'] ?? 0;

            ?>
            <td align="center"><?= htmlspecialchars($total_DEVELOPMENT_SAMPLE); ?></td>
            <?php
            $query = "select
                            COUNT(*) as total_count
                        from
                            (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by nokk
                            order by
                                tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam
                            from
                                tbl_produksi
                            where
                                tgl_buat >= '$start'
                                and tgl_buat < '$end'
                                and no_order like '%BP%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and durasi_jam < 12) as t";
                            
            $result = mysqli_query($conb, $query);
            $row = mysqli_fetch_assoc($result);
            $total_tercapai_12_jamBP = $row['total_count'] ?? 0;


            $query_tidak_tercapai_12_jam_hari_libur_BP = "
                SELECT COUNT(*) AS TOTAL_TIDAK_TERCAPAI_LIBUR
                FROM (
                    SELECT DISTINCT *, 
                        TIMESTAMP(tgl_proses_in, jam_in) AS start_datetime,
                        CASE 
                            WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                            ELSE TIMESTAMP(tgl_proses_out, jam_out)
                        END AS end_datetime,
                        TIMESTAMPDIFF(MINUTE, TIMESTAMP(tgl_proses_in, jam_in), 
                            CASE 
                                WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                                ELSE TIMESTAMP(tgl_proses_out, jam_out)
                            END
                        ) / 60 AS durasi_jam,
                        CASE 
                            WHEN TIMESTAMPDIFF(MINUTE, TIMESTAMP(tgl_proses_in, jam_in), 
                                CASE 
                                    WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                                    ELSE TIMESTAMP(tgl_proses_out, jam_out)
                                END
                            ) / 60 > 12 THEN '> 12 jam'
                            ELSE '≤ 12 jam'
                        END AS kategori
                    FROM tbl_produksi
                    WHERE tgl_buat >= '$start' AND tgl_buat < '$end'
                    AND no_order LIKE '%BP%'
                    AND DAYOFWEEK(tgl_buat) IN (1, 7) -- Hari Minggu (1) dan Sabtu (7)
                ) AS t
                WHERE kategori = '> 12 jam'";

            $result_tidak_tercapai_12_jam_hari_libur_BP = mysqli_query($conb, $query_tidak_tercapai_12_jam_hari_libur_BP);
            $row_tidak_tercapai_12_jam_hari_libur_BP = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_hari_libur_BP);
            $total_tidak_tercapai_12_jam_hari_libur_BP = $row_tidak_tercapai_12_jam_hari_libur_BP['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
            ?>
            <td align="center"><?= htmlspecialchars($total_tercapai_12_jamBP); ?></td>
        <!--            <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_hari_libur_BP); ?></td>-->
            <td align="center"><?= htmlspecialchars($total_not_achieved_BP); ?></td>
            <td align="center">
                <?php
                $sisakartu_kerja_development_sample = $total_DEVELOPMENT_SAMPLE - $total_tercapai_12_jamBP;
                echo htmlspecialchars($sisakartu_kerja_development_sample) ?? 0;
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_tercapai_12_jamBP > 0) {
                    $persentase_tidak_tercapai_libur_BP = ($total_tidak_tercapai_12_jam_hari_libur_BP / $total_tercapai_12_jamBP) * 100;
                    echo number_format($persentase_tidak_tercapai_libur_BP, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_tercapai_12_jamBP > 0) {
                    $persentase_tidak_tercapai_proses_BP = ($total_not_achieved_BP / $total_tercapai_12_jamBP) * 100;
                    echo number_format($persentase_tidak_tercapai_proses_BP, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <!-- pencapaian -->
            <td align="center">
                <?php
                if (($total_tercapai_12_jamBP + $total_tidak_tercapai_12_jam_hari_libur_BP + $total_not_achieved_BP) > 0) {
                    $percentage = ($total_tidak_tercapai_12_jam_hari_libur_BP / ($total_tercapai_12_jamBP + $total_tidak_tercapai_12_jam_hari_libur_BP + $total_not_achieved_BP)) * 100;
                    echo number_format($percentage, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>RETURN</td>
            <td>12 Jam</td>
            <?php
            $query_RETURN =
                "SELECT COUNT(*) AS TOTAL_RETURN
                    FROM (
                        SELECT DISTINCT 
                            p.PRODUCTIONORDERCODE,
                            p2.PRODUCTIONORDERCODE
                        FROM
                            PRODUCTIONPROGRESS p
                        LEFT JOIN ITXVIEWKK p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                        LEFT JOIN PRODUCTIONDEMAND d ON d.CODE = p2.DEAMAND
                        WHERE
                            TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) >= TIMESTAMP('$start')
                            AND TIMESTAMP(p.PROGRESSSTARTPROCESSDATE, p.PROGRESSSTARTPROCESSTIME) < TIMESTAMP('$end')
                            AND p.PROGRESSTEMPLATECODE = 'S01'
                            AND d.DLVSALORDLINESALORDCNTCODE IN ('RETRNFAB','RETRNEXP')
                            AND p.OPERATIONCODE IN (
                                'AIR1', 'COM1', 'COM2', 'POL1', 'RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5',
                                'SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5', 'SUE1', 'SUE2', 'SUE3', 'SUE4',
                                'TDR1', 'WET1', 'WET2', 'WET3', 'WET4'
                            )
                    ) AS t;
                ";

            $result_RETURN = db2_exec($conn2, $query_RETURN);
            $row_RETURN = db2_fetch_assoc($result_RETURN);
            $total_RETURN = $row_RETURN['TOTAL_RETURN'] ?? 0;
            ?>
            <td align="center"><?= htmlspecialchars($total_RETURN); ?></td>
            <?php
            $query = "select
                            COUNT(*) as total_count
                        from
                            (
                        with ranked_data as (
                            select
                                *,
                                row_number() over (partition by nokk
                            order by
                                tgl_buat desc) as rn,
                                TIMESTAMPDIFF(hour,
                                    CONCAT(tgl_proses_in, ' ', jam_in),
                                    CONCAT(tgl_proses_out, ' ', jam_out)
                                ) as durasi_jam
                            from
                                tbl_produksi
                            where
                                tgl_buat >= '$start'
                                and tgl_buat < '$end'
                                and no_order like '%RET%'
                        )
                        select
                            *
                        from
                            ranked_data
                        where
                            rn = 1
                            and durasi_jam < 12) as t";
            $result = mysqli_query($conb, $query);
            $row = mysqli_fetch_assoc($result);
            $total_tercapai_12_jamRET = $row['total_count'] ?? 0;


            $query_tidak_tercapai_12_jam_hari_libur_RET = "
                SELECT COUNT(*) AS TOTAL_TIDAK_TERCAPAI_LIBUR
                FROM (
                    SELECT DISTINCT *, 
                        TIMESTAMP(tgl_proses_in, jam_in) AS start_datetime,
                        CASE 
                            WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                            ELSE TIMESTAMP(tgl_proses_out, jam_out)
                        END AS end_datetime,
                        TIMESTAMPDIFF(MINUTE, TIMESTAMP(tgl_proses_in, jam_in), 
                            CASE 
                                WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                                ELSE TIMESTAMP(tgl_proses_out, jam_out)
                            END
                        ) / 60 AS durasi_jam,
                        CASE 
                            WHEN TIMESTAMPDIFF(MINUTE, TIMESTAMP(tgl_proses_in, jam_in), 
                                CASE 
                                    WHEN jam_out < jam_in THEN TIMESTAMP(DATE_ADD(tgl_proses_out, INTERVAL 1 DAY), jam_out)
                                    ELSE TIMESTAMP(tgl_proses_out, jam_out)
                                END
                            ) / 60 > 12 THEN '> 12 jam'
                            ELSE '≤ 12 jam'
                        END AS kategori
                    FROM tbl_produksi
                    WHERE tgl_buat >= '$start' AND tgl_buat < '$end'
                    AND no_order LIKE '%RET%'
                    AND DAYOFWEEK(tgl_buat) IN (1, 7) -- Hari Minggu (1) dan Sabtu (7)
                ) AS t
                WHERE kategori = '> 12 jam'";
            $result_tidak_tercapai_12_jam_hari_libur_RET = mysqli_query($conb, $query_tidak_tercapai_12_jam_hari_libur_RET);
            $row_tidak_tercapai_12_jam_hari_libur_RET = mysqli_fetch_assoc($result_tidak_tercapai_12_jam_hari_libur_RET);
            $total_tidak_tercapai_12_jam_hari_libur_RET = $row_tidak_tercapai_12_jam_hari_libur_RET['TOTAL_TIDAK_TERCAPAI_LIBUR'] ?? 0;
            ?>
            <td align="center"><?= htmlspecialchars($total_tercapai_12_jamRET); ?></td>
        <!--            <td align="center"><?= htmlspecialchars($total_tidak_tercapai_12_jam_hari_libur_RET); ?></td>-->
            <td align="center"><?= htmlspecialchars($total_not_achieved_RET); ?></td>
            <td align="center"> 
                <?php
                $sisakartu_kerja_return = $total_RETURN - $total_tercapai_12_jamRET;
                echo htmlspecialchars($sisakartu_kerja_return) ?? 0;
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_tercapai_12_jamRET > 0) {
                    $persentase_tidak_tercapai_libur_RET = ($total_tidak_tercapai_12_jam_hari_libur_RET / $total_tercapai_12_jamRET) * 100;
                    echo number_format($persentase_tidak_tercapai_libur_RET, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <td align="center">
                <?php
                if ($total_tercapai_12_jamRET > 0) {
                    $persentase_tidak_tercapai_prosesRET = ($total_not_achieved_RET / $total_tercapai_12_jamRET) * 100;
                    echo number_format($persentase_tidak_tercapai_prosesRET, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <!-- pencapaian -->
            <td align="center">

                <?php
                if (($total_tercapai_12_jamRET + $total_tidak_tercapai_12_jam_hari_libur_RET + $total_not_achieved_RET) > 0) {
                    $percentage = ($total_tidak_tercapai_12_jam_hari_libur_RET / ($total_tercapai_12_jamRET + $total_tidak_tercapai_12_jam_hari_libur_RET + $total_not_achieved_RET)) * 100;
                    echo number_format($percentage, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
        </tr>
        <tr style="font-weight:bold; background-color:yellow;">
            <td align="center">TOTAL</td>
            <td></td>
            <td align="center">
                <?php
                $total_kartu_kerja = $total_KARTU_KERJA_BIASA + $total_GANTI_KAIN_EKSTERNAL + $total_MINI_BULK + $total_SALESMAN_SAMPLE + $total_DEVELOPMENT_SAMPLE + $total_RETURN;
                echo htmlspecialchars($total_kartu_kerja);
                ?>
            </td>
            <td align="center">
                <?php
                $total_tercapai = $total_tercapai_30_jamDOM + $total_tercapai_12_jamRET + $total_tercapai_12_jamREP + $total_tercapai_12_jamMBE + $total_SALESMAN_SAMPLE_12_JAMSAM + $total_tercapai_12_jamBP;
                echo htmlspecialchars($total_tercapai);
                ?>
            </td>
            <td align="center">
                <?php
                $total_tidak_tercapai_hari_libur = $total_tidak_tercapai_30_jam_libur + $total_tidak_tercapai_12_jam_liburREP + $total_tidak_tercapai_12_jam_hari_liburMBE + $total_tidak_tercapai_12_jam_libur_SAM + $total_tidak_tercapai_12_jam_hari_libur_BP + $total_tidak_tercapai_12_jam_hari_libur_RET;
                echo htmlspecialchars($total_tidak_tercapai_hari_libur);
                ?>
            </td>
            <td align="center">
                <?php
                $total_tidak_tercapai = $total_not_achieved_DOM + $total_not_achieved_REP + $total_not_achieved_MBE + $total_not_achieved_SAM + $total_not_achieved_BP + $total_not_achieved_RET;
                echo htmlspecialchars($total_tidak_tercapai);
                ?>
            </td>
            <td align="center">
                <?php
                // $total_kartu_kerja_sisa = $sisakartu_kerja_development_sample + $sisakartu_kerja_salesman_sample + $sisakartu_kerja_return + $sisakartu_kerja_mini_bulk + $sisakartu_kerja_ganti_kain_eksternal + $sisakartu_kerja_biasa;
                
                // echo htmlspecialchars($total_kartu_kerja_sisa);
                ?>
            </td>
            <td align="center">
                <?php
                $total_persentase_tidak_tercapai_libur = 0;
                $divisors = 0;

                if ($total_tercapai_30_jamDOM + $total_tidak_tercapai_30_jam_libur > 0) {
                    $total_persentase_tidak_tercapai_libur += ($total_tidak_tercapai_30_jam_libur / ($total_tercapai_30_jamDOM + $total_tidak_tercapai_30_jam_libur)) * 100;
                    $divisors++;
                }
                if ($total_tercapai_12_jamREP + $total_tidak_tercapai_12_jam_liburREP > 0) {
                    $total_persentase_tidak_tercapai_libur += ($total_tidak_tercapai_12_jam_liburREP / ($total_tercapai_12_jamREP + $total_tidak_tercapai_12_jam_liburREP)) * 100;
                    $divisors++;
                }
                if ($total_tercapai_12_jamMBE + $total_tidak_tercapai_12_jam_hari_liburMBE > 0) {
                    $total_persentase_tidak_tercapai_libur += ($total_tidak_tercapai_12_jam_hari_liburMBE / ($total_tercapai_12_jamMBE + $total_tidak_tercapai_12_jam_hari_liburMBE)) * 100;
                    $divisors++;
                }
                if ($total_SALESMAN_SAMPLE_12_JAMSAM + $total_tidak_tercapai_12_jam_libur_SAM > 0) {
                    $total_persentase_tidak_tercapai_libur += ($total_tidak_tercapai_12_jam_libur_SAM / ($total_SALESMAN_SAMPLE_12_JAMSAM + $total_tidak_tercapai_12_jam_libur_SAM)) * 100;
                    $divisors++;
                }
                if ($total_tercapai_12_jamBP + $total_tidak_tercapai_12_jam_hari_libur_BP > 0) {
                    $total_persentase_tidak_tercapai_libur += ($total_tidak_tercapai_12_jam_hari_libur_BP / ($total_tercapai_12_jamBP + $total_tidak_tercapai_12_jam_hari_libur_BP)) * 100;
                    $divisors++;
                }
                if ($total_tercapai_12_jamRET + $total_tidak_tercapai_12_jam_hari_libur_RET > 0) {
                    $total_persentase_tidak_tercapai_libur += ($total_tidak_tercapai_12_jam_hari_libur_RET / ($total_tercapai_12_jamRET + $total_tidak_tercapai_12_jam_hari_libur_RET)) * 100;
                    $divisors++;
                }

                if ($divisors > 0) {
                    $average_persentase_tidak_tercapai_libur = $total_persentase_tidak_tercapai_libur / $divisors;
                    echo number_format($average_persentase_tidak_tercapai_libur, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <td align="center">
                <?php
                $total_persentase_tidak_tercapai_proses = 0;
                $divisors = 0;

                if ($total_tercapai_30_jamDOM > 0) {
                    $total_persentase_tidak_tercapai_proses += ($total_not_achieved_DOM / $total_tercapai_30_jamDOM) * 100;
                    $divisors++;
                }
                if ($total_tercapai_12_jamREP > 0) {
                    $total_persentase_tidak_tercapai_proses += ($total_not_achieved_REP / $total_tercapai_12_jamREP) * 100;
                    $divisors++;
                }
                if ($total_tercapai_12_jamMBE > 0) {
                    $total_persentase_tidak_tercapai_proses += ($total_not_achieved_MBE / $total_tercapai_12_jamMBE) * 100;
                    $divisors++;
                }
                if ($total_SALESMAN_SAMPLE_12_JAMSAM > 0) {
                    $total_persentase_tidak_tercapai_proses += ($total_not_achieved_SAM / $total_SALESMAN_SAMPLE_12_JAMSAM) * 100;
                    $divisors++;
                }
                if ($total_tercapai_12_jamBP > 0) {
                    $total_persentase_tidak_tercapai_proses += ($total_not_achieved_BP / $total_tercapai_12_jamBP) * 100;
                    $divisors++;
                }
                if ($total_tercapai_12_jamRET > 0) {
                    $total_persentase_tidak_tercapai_proses += ($total_not_achieved_RET / $total_tercapai_12_jamRET) * 100;
                    $divisors++;
                }

                if ($divisors > 0) {
                    $average_persentase_tidak_tercapai_proses = $total_persentase_tidak_tercapai_proses / $divisors;
                    echo number_format($average_persentase_tidak_tercapai_proses, 2) . '%';
                } else {
                    echo '0.00%';
                }
                ?>
            </td>
            <!-- <td align="center">RATA - RATA</td> -->
            <td align="center">
                <?php
                $total_pencapaian = 0;
                $divisors = 0;

                if (($total_tercapai_12_jamRET + $total_tidak_tercapai_12_jam_hari_libur_RET + $total_not_achieved_RET) > 0) {
                    $total_pencapaian += (($total_tidak_tercapai_12_jam_hari_libur_RET / ($total_tercapai_12_jamRET + $total_tidak_tercapai_12_jam_hari_libur_RET + $total_not_achieved_RET)) * 100);
                    $divisors++;
                }
                if (($total_tercapai_12_jamBP + $total_tidak_tercapai_12_jam_hari_libur_BP + $total_not_achieved_BP) > 0) {
                    $total_pencapaian += (($total_tidak_tercapai_12_jam_hari_libur_BP / ($total_tercapai_12_jamBP + $total_tidak_tercapai_12_jam_hari_libur_BP + $total_not_achieved_BP)) * 100);
                    $divisors++;
                }
                if (($total_SALESMAN_SAMPLE_12_JAMSAM + $total_tidak_tercapai_12_jam_libur_SAM + $total_not_achieved_SAM) > 0) {
                    $total_pencapaian += (($total_tidak_tercapai_12_jam_libur_SAM / ($total_SALESMAN_SAMPLE_12_JAMSAM + $total_tidak_tercapai_12_jam_libur_SAM + $total_not_achieved_SAM)) * 100);
                    $divisors++;
                }
                if (($total_tercapai_12_jamMBE + $total_tidak_tercapai_12_jam_hari_liburMBE + $total_not_achieved_MBE) > 0) {
                    $total_pencapaian += (($total_tidak_tercapai_12_jam_hari_liburMBE / ($total_tercapai_12_jamMBE + $total_tidak_tercapai_12_jam_hari_liburMBE + $total_not_achieved_MBE)) * 100);
                    $divisors++;
                }
                if (($total_tercapai_12_jamREP + $total_tidak_tercapai_12_jam_liburREP + $total_not_achieved_REP) > 0) {
                    $total_pencapaian += (($total_tidak_tercapai_12_jam_liburREP / ($total_tercapai_12_jamREP + $total_tidak_tercapai_12_jam_liburREP + $total_not_achieved_REP)) * 100);
                    $divisors++;
                }
                if (($total_tercapai_30_jamDOM + $total_tidak_tercapai_30_jam_libur + $total_not_achieved_DOM) > 0) {
                    $total_pencapaian += (($total_tidak_tercapai_30_jam_libur / ($total_tercapai_30_jamDOM + $total_tidak_tercapai_30_jam_libur + $total_not_achieved_DOM)) * 100);
                    $divisors++;
                }

                $average_pencapaian = $divisors > 0 ? $total_pencapaian / $divisors : 0;

                echo number_format($average_pencapaian, 2) . '%';
                ?>
            </td>
        </tr>
    </table>