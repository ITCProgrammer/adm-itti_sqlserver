<?PHP
// ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

// mysqli_query($con,"UPDATE mutasi_bs_krah_detail SET `qty` = '$_POST[value]' where id = '$_POST[pk]'");

// echo json_encode('success');

$json = filter_input(INPUT_POST, 'json');
$json = json_decode($json);
$id = $json->id;
$idmutasi = $json->idmutasi;
$qty = $json->qty;


$sql = "SELECT 1
        FROM db_dying.mutasi_bs_krah_detail
        WHERE id = ? AND id_mutasi = ?";

$params = [$id, $idmutasi];
$stmt = sqlsrv_query($con, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$num = 0;
while (sqlsrv_fetch($stmt)) {
    $num++;
}S

if ($num > 0) {
    sqlsrv_query($con, "UPDATE db_dying.mutasi_bs_krah_detail SET qty = '$qty' where id = '$id'");
    $response = array();
    $response['id'] = $id;

    echo json_encode($response);
} else {
    $sql = "
            INSERT INTO db_dying.mutasi_bs_krah_detail (id_mutasi, qty, tgl_update)
            OUTPUT INSERTED.id_detail AS id
            VALUES (?, ?, SYSDATETIME());
            ";

    $params = [$idmutasi, $qty];

    $stmt = sqlsrv_query($con, $sql, $params);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => true, "details" => sqlsrv_errors()]);
        exit;
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    echo json_encode(["id" => (int) $row["id"]]);
}