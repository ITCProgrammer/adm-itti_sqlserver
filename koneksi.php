<?php
date_default_timezone_set('Asia/Jakarta');
// $con            = mysqli_connect("10.0.0.10","dit","4dm1n","db_dying");
// $cond           = mysqli_connect("10.0.0.10","dit","4dm1n","db_qc");
// $conb           = mysqli_connect("10.0.0.10","dit","4dm1n","db_brushing");
// $con_nowprd     = mysqli_connect("10.0.0.10","dit","4dm1n","nowprd");
// $cona           = mysqli_connect("10.0.0.10","dit","4dm1n","db_adm");
// if (mysqli_connect_errno()) {
//     printf("Connect failed: %s\n", mysqli_connect_error());
//     exit();
//     }

$hostSVR19 = "10.0.0.221";
$usernameSVR19 = "sa";
$passwordSVR19 = "Ind@taichen2024";
$finishing = "db_finishing";
$brushing = "db_brushingt";
$dyeing = "db_dying";
$qc = "db_qc";
$adm = "db_adm";
$db_finishing = array("Database" => $finishing, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$db_brushing = array("Database" => $brushing, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$db_dyeing = array("Database" => $dyeing, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$db_qc = array("Database" => $qc, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$db_adm = array("Database" => $adm, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$conS = sqlsrv_connect($hostSVR19, $db_finishing);
$conb = sqlsrv_connect($hostSVR19, $db_brushing);
$con  = sqlsrv_connect($hostSVR19, $db_dyeing);
$cond = sqlsrv_connect($hostSVR19, $db_qc);
$cona = sqlsrv_connect($hostSVR19, $db_adm);
// pdo
try {
    $pdo = new PDO("sqlsrv:server=10.0.0.221;Database=db_finishing_test", "sa", "Ind@taichen2024");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$hostname="10.0.0.21";
$database = "NOWPRD";
$user = "db2admin";
$passworddb2 = "Sunkam@24809";
$port="25000";
$conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
$conn2 = db2_connect($conn_string,'', '');
if($conn2) {
}
else{
    exit("DB2 Connection failed");
    }


