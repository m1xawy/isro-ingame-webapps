<?php $msg = $_REQUEST['code'];
if (isset($msg))
{
	switch(strtolower($msg))
	{
		case "c101": // debugging mode is active
			$errormsg = "This webpage is currently under maintainance";
			break;
		case "c102": // calendar is current close, will be open next month
			$errormsg = "Attendance event of this month has ended, will be open again in next month.";
			break;
		case "c103": // not-used
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c104": // key not matched in fingerprint
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c105": // passed jid is not a number and passed key is not md5 hash
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c106": // ***CRITICAL*** $genkey return -1
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please try reopening this page, if error still occur contact an administrator ASAP.";
			break;
		case "c107": // no jid,key,day or token parameter was pass
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c108": // someone is trying to direct access the event.asp
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c109": // someone is trying to sqli
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c110": // fingerprint key already existed
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c111": // fingerprint insert error
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c112": // unknown token
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c113": // expired token
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c114": // token encryption failed
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c115": // token decryption failed
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c116": // requested module not found
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c117": // no module requested
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
		case "c118": // ***CRITICAL*** $calendar_info returned false
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please try reopening this page, if error still occur contact an administrator ASAP.";
			break;
		case "c119": // ***CRITICAL*** $calendar did not return number.
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please try reopening this page, if error still occur contact an administrator ASAP.";
			break;
		case "c120": // ***CRITICAL*** $logintime returned -1
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please try reopening this page, if error still occur contact an administrator ASAP.";
			break;
		default: // nothng is match from the above cases
			$errormsg = "An error[".strtoupper($msg)."] occured.<br>Please contact administrator immediately.";
			break;
	}
} else { // variable is empty
	$errormsg = "An error [C300] occured.<br>Please contact administrator immediately.";
}?>
<html xmlns="//www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="rigid, rigid-online, rigidonline, silkroad, silkroadonline, onlinesilkroad, silkroad-online" />
<meta http-equiv="x-ua-compatible" content="IE=9">
<link rel="stylesheet" type="text/css" media="all" href="dist/css/itemmall_game.css" />
<script type="text/javascript" src="dist/js/jquery-1.4.2.min.js"></script>
<title><?=SITE_NAME?></title>
</head>
<body class="mig " ondragstart="return false" onselectstart="return false">
<div id="wrap" class="error">
	<h1><?=SITE_NAME?></h1>
	<div id="screen">
		<div class="opener mold"></div>
		<div class="cropped">
			<p class="msg"><?=$errormsg?></p>
		</div>
		<div class="closer mold"></div>
	</div>
</div>
<script>
$(window).load(function(){
	$('#loading',window.parent.document).hide();
	$('#wrap').show();
});
</script>
</body>
</html>