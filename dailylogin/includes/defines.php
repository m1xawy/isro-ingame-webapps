<?php
require_once('config.php');

define('RDBHOST0', $_config['mssql']['ip']);
define('RDBUSER0', $_config['mssql']['id']);
define('RDBPASS0', $_config['mssql']['pw']);
define('RDBNAME0', $_config['mssql']['db']);

define('RDBHOST1', $_config['mysql']['ip']);
define('RDBUSER1', $_config['mysql']['id']);
define('RDBPASS1', $_config['mysql']['pw']);
define('RDBNAME1', $_config['mysql']['db']);

define('HTTP_DOMAIN', "http://".$_config['site_info']['domain']);

define('SITE_NAME', $_config['site_info']['name']);
define('SITE_DESC', $_config['site_info']['desc']);
define('SITE_KWRD', $_config['site_info']['kwrd']);
define('SITE_PASS', $_config['site_info']['pass']);

define('PAGE_GATEWAY', HTTP_DOMAIN."/".$_config['page'][0].".".$_config['site_info']['extn']);
define('PAGE_EVENT', HTTP_DOMAIN."/".$_config['page'][1].".".$_config['site_info']['extn']);
define('PAGE_ERROR', HTTP_DOMAIN."/".$_config['page'][2].".".$_config['site_info']['extn']);
define('PAGE_REFRESH', HTTP_DOMAIN."/".$_config['page'][3].".".$_config['site_info']['extn']);

define('VERIFYKEY', $_config['site_info']['vkey']);
