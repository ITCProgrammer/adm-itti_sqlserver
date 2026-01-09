<?php
$host = "10.0.0.183,1433"; // Gunakan koma untuk SQL Server instance dan port default 1433
$username = "sa";
$password = "F9TAvM@T44Zx";
$db_name = "ORGATEX-INTEG";

// Pengaturan koneksi
$connInfo = array( 
    "Database" => $db_name, 
    "UID" => $username, 
    "PWD" => $password,
    "CharacterSet" => "UTF-8" // Disarankan untuk menghindari masalah encoding
);

// Coba koneksi
$connORG = sqlsrv_connect( $host, $connInfo);

// Cek koneksi
if( $connORG === false ) {
    die( print_r( sqlsrv_errors(), true));
}
