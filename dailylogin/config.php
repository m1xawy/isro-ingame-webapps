<?php
$_config['debug']=false;
$_config['mssql']=array
(
	'ip'=>'192.168.1.101,1433',
	'id'=>'sa',
	'pw'=>'123456',
	'db'=>'SILKROAD_R_ACCOUNT'
);
$_config['mysql']=array
(
	'ip'=>'127.0.0.1',
	'id'=>'root',
	'pw'=>'169841',
	'db'=>'rigid'
);
$_config['site_info']=array
(
	'domain'=>'192.168.1.101:84',
	'name'=>'Attendance Event',
	'desc'=>'The most updated Silkroad server with the newest files available is ready to bring this game to the next level. The future is finally here!',
	'kwrd'=>'rigid, rigid-online, rigidonline, silkroad, silkroadonline, onlinesilkroad, silkroad-online',
	'pass'=>'rigid.online', //used in url param encryption/decryption
	'vkey'=>'eset5ag.nsy-g6ky5.mp', //use for security verification
	'extn'=>'asp' //extension use in url
);
$_config['page']=array('gateway','event','error','reload','launcher');

$_config['seckey'] = $_config['site_info']['verifykey'];
