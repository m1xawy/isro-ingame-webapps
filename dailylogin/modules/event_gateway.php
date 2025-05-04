<?php
$iframesrc = "about:blank";
$param = [0=>$_REQUEST['jid'],1=>$_REQUEST['key']];
if (!isset($param[0]) || !isset($param[1]))
{
	$fn->writelog("[Unkown Parameter]\t(".$fn->getipvisitor().")\t".$_SERVER['QUERY_STRING'],'direct_access.log');
	$iframesrc = PAGE_ERROR."?code=C107";
} else {
	$token = $fn->gentoken(time()."|".$param[0]."|".$param[1],SITE_PASS);
	if (!$token) {
		$iframesrc = PAGE_ERROR."?code=C114";
	} else {
		$iframesrc = PAGE_EVENT."?token=".urlencode($token);
	}
}
?>
<html xmlns="//www.w3.org/1999/xhtml" >
<head>
<title><?=SITE_NAME?></title>
<meta http-equiv="x-ua-compatible" content="IE=9">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="silkroad, silkroadonline, joymax, onlinesilkroad, silkroad-online" />
<link rel="stylesheet" type="text/css" media="all" href="dist/css/gateway.css?v=<?=rand(1111,9999)?>" />
<script type="text/javascript" src="dist/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="dist/js/common.js?v=<?=rand(1111,9999)?>"></script>
</head>
<body ondragstart="return false" onselectstart="return false">
<div id="container">
	<div id="loading" class="loading"></div>
	<iframe id="frame" src="<?=$iframesrc?>" onload="showiframe()"></iframe>
</div>
<script>
$(window).load(function(){
	disableDefaults();
});
</script>
</body>
</html>