<?php
ini_set("display_errors", 0);

$uniques = [
    'MOB_CH_TIGERWOMAN' => [
        'id' => 1954,
        'name' => 'Tiger Girl',
        'image' => 'images/tw_icon_unique.png',
        'points' => 1
    ],
    'MOB_OA_URUCHI' => [
        'id' => 1982,
        'name' => 'Uruchi',
        'image' => 'images/tw_icon_unique.png',
        'points' => 2
    ],
    'MOB_KK_ISYUTARU' => [
        'id' => 2002,
        'name' => 'Isyutaru',
        'image' => 'images/tw_icon_unique.png',
        'points' => 3
    ],
    'MOB_TK_BONELORD' => [
        'id' => 3810,
        'name' => 'Lord Yarkan',
        'image' => 'images/tw_icon_unique.png',
        'points' => 4
    ],
    'MOB_RM_TAHOMET' => [
        'id' => 3875,
        'name' => 'Demon Shaitan',
        'image' => 'images/tw_icon_unique.png',
        'points' => 5
    ],
    'MOB_AM_IVY' => [
        'id' => 14778,
        'name' => 'Captain Ivy',
        'image' => 'images/tw_icon_unique.png',
        'points' => 2
    ],
    'MOB_EU_KERBEROS' => [
        'id' => 5871,
        'name' => 'Cerberus',
        'image' => 'images/tw_icon_unique.png',
        'points' => 1
    ],
    'MOB_RM_ROC' => [
        'id' => 3877,
        'name' => 'Roc',
        'image' => 'images/tw_icon_unique.png',
        'points' => 15
    ],
    'MOB_TQ_WHITESNAKE' => [
        'id' => 14839,
        'name' => 'Medusa',
        'image' => 'images/tw_icon_unique.png',
        'points' => 10
    ],
];

$serverName = "192.168.1.101";
$serverPort = "1433";
$database = "SILKROAD_R_SHARD";
$username = "sa";
$password = "123456";

try {
    $conn = new PDO("sqlsrv:Server={$serverName},{$serverPort};TrustServerCertificate=true;Database={$database}", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
