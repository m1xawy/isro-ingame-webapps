<?php
$token_param = $_GET['token'];
$request = $fn->readtoken($token_param, SITE_PASS, false);
if (!$request){ // wrong password
	$fn->writelog("[reload.asp]\t(".$fn->getipvisitor().")\tWRONG PASSWORD",'token_error.log');
	die();
} elseif ($request == -1) { // token has been modified
	$fn->writelog("[reload.asp]\t(".$fn->getipvisitor().")\tMODIFIED TOKEN",'token_error.log');
	die();
} elseif ($request == -2){ // token has expired
	$fn->writelog("[reload.asp]\t(".$fn->getipvisitor().")\tEXPIRED TOKEN",'token_error.log');
	die();
}

if ($fn->getreferer() != PAGE_EVENT)
{
	if (!$_config['debug']) //ignore this if debug is true
	{
		$fn->writelog("[reload.asp]\t(".$fn->getipvisitor().")\tNO REFERER URL",'direct_access.log');
		die();
	}
}

if (!is_numeric($request['jid']) || !$fn->isMD5($request['key']))
{
	if (!$_config['debug']) //ignore this if debug is true
	{
		$fn->writelog("[reload.asp]\t(".$fn->getipvisitor().")\t".$_SERVER['QUERY_STRING'],'direct_access.log');
		die();
	}
}

if (strtolower($request['key']) != strtolower(md5($request['jid'].$fn->certifykey($request['jid']).VERIFYKEY)))
{
	if (!$_config['debug']){
		$fn->writelog("[reload.asp]\t(".$fn->getipvisitor().")\tkey param and generated fingerprint key do not match",'fingerprint_error.log');
		die();
	}
}

//print new generated certkey
print PAGE_GATEWAY."?jid=".$request['jid']."&key=".$fn->gencertkey($request['jid'],VERIFYKEY);
