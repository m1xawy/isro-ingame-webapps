<?php
ini_set("display_errors", 0);

$saltKey = 'eset5ag.nsy-g6ky5.mp';

$serverName = "192.168.1.101";
$serverPort = "1433";
$database = "SILKROAD_R_ACCOUNT";
$username = "sa";
$password = "123456";

try {
    $conn = new PDO("sqlsrv:Server={$serverName},{$serverPort};TrustServerCertificate=true;Database={$database}", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
