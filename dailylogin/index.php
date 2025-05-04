<?php

ini_set('display_errors', '0');

$continue = true;
include_once('includes/connection.php');
$req = $_REQUEST['req'];
if (isset($req)) {
	switch($req) {
		case "gateway":
			include('modules/event_gateway.php');
			break;
		case "event":
			include('modules/event_calendar.php');
			break;
		case "claim":
			include('modules/event_claim.php');
			break;
		case "error":
			include('modules/event_error.php');
			break;
		case "reload":
			include('modules/event_reloadurl.php');
			break;
		case "launcher":
			include('modules/launcher_banner.php');
			break;
		default:
			header("Location: ".PAGE_ERROR."?code=C116");
			die();
			break;
	}
} else {
	header("Location: ".PAGE_ERROR."?code=C117");
	die();
}

