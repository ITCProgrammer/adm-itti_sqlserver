<?php
$data = mysqli_query($con,"SELECT b.* from tbl_mesin b ORDER BY b.kapasitas DESC,b.no_mesin ASC");

function tampil($mc, $no)
{
    $qCek = mysqli_query($con,"SELECT sum(rol) as rol, sum(bruto) as bruto, GROUP_CONCAT(DISTINCT no_order SEPARATOR '-' ) AS no_order
                        FROM tbl_schedule 
                        WHERE (`status` = 'sedang jalan' or `status` ='antri mesin') and no_urut='$no' and no_mesin='$mc'
                        GROUP BY no_mesin, no_urut 
                        ORDER BY id ASC");
    $row = mysqli_fetch_array($qCek);
    $dt[] = $row;
    return $dt;
}

$nestedMsn = array();
$oneS = array();
$twoS = array();
$triS = array();
$fourS = array();
$fiveS = array();
$sixS = array();
$sevenS = array();
while ($li = mysqli_fetch_array($data)) {
    $nestedMsn[] = $li['no_mesin'] . "_" . $li['kode'] . " (" . $li['kapasitas'] . ")";
    // $nestedMsn[] = "1. Mesin " . $li['no_mesin'] . "_" . $li['kode'] . " (" . $li['kapasitas'] . ")";
    // $nestedMsn[] = "2. Mesin " . $li['no_mesin'] . "_" . $li['kode'] . " (" . $li['kapasitas'] . ")";
    // $nestedMsn[] = "3. Mesin " . $li['no_mesin'] . "_" . $li['kode'] . " (" . $li['kapasitas'] . ")";
    // $nestedMsn[] = "4. Mesin " . $li['no_mesin'] . "_" . $li['kode'] . " (" . $li['kapasitas'] . ")";
    // $nestedMsn[] = "5. Mesin " . $li['no_mesin'] . "_" . $li['kode'] . " (" . $li['kapasitas'] . ")";
    // $nestedMsn[] = "6. Mesin " . $li['no_mesin'] . "_" . $li['kode'] . " (" . $li['kapasitas'] . ")";
    // $nestedMsn[] = "7. Mesin " . $li['no_mesin'] . "_" . $li['kode'] . " (" . $li['kapasitas'] . ")";

    foreach (tampil($li['no_mesin'], "1") as $one) {
        $Nested_oneS = array();
        $Nested_oneS['y'] = floatval($one['bruto']);
        $Nested_oneS['myData'] = $one['no_order'];

        $oneS[] = $Nested_oneS;
    }
    foreach (tampil($li['no_mesin'], "2") as $two) {
        $Nested_twoS = array();
        $Nested_twoS['y'] = floatval($two['bruto']);
        $Nested_twoS['myData'] = $two['no_order'];

        $twoS[] = $Nested_twoS;
    }
    foreach (tampil($li['no_mesin'], "3") as $tri) {
        $Nested_triS = array();
        $Nested_triS['y'] = floatval($tri['bruto']);
        $Nested_triS['myData'] = $tri['no_order'];

        $triS[] = $Nested_triS;
    }
    foreach (tampil($li['no_mesin'], "4") as $four) {
        $Nested_fourS = array();
        $Nested_fourS['y'] = floatval($four['bruto']);
        $Nested_fourS['myData'] = $four['no_order'];

        $fourS[] = $Nested_fourS;
    }
    foreach (tampil($li['no_mesin'], "5") as $five) {
        $Nested_fiveS = array();
        $Nested_fiveS['y'] = floatval($five['bruto']);
        $Nested_fiveS['myData'] = $five['no_order'];

        $fiveS[] = $Nested_fiveS;
    }
    foreach (tampil($li['no_mesin'], "6") as $six) {
        $Nested_sixS = array();
        $Nested_sixS['y'] = floatval($six['bruto']);
        $Nested_sixS['myData'] = $six['no_order'];

        $sixS[] = $Nested_sixS;
    }
    foreach (tampil($li['no_mesin'], "7") as $seven) {
        $Nested_sevenS = array();
        $Nested_sevenS['y'] = floatval($seven['bruto']);
        $Nested_sevenS['myData'] = $seven['no_order'];

        $sevenS[] = $Nested_sevenS;
    }
}

$data_one = json_encode($oneS);
$data_two = json_encode($twoS);
$data_tri = json_encode($triS);
$data_four = json_encode($fourS);
$data_five = json_encode($fiveS);
$data_six = json_encode($sixS);
$data_seven = json_encode($sevenS);
$data_mesin = json_encode($nestedMsn);
