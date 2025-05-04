<?php
$token_param = $_GET['token'];
$request = $fn->readtoken($token_param,SITE_PASS,false);
if (!$request){
	$fn->writelog("[claim.asp]\t(".$fn->getipvisitor().")\tToken password is incorrect",'token_error.log');
	die('C0023');
} elseif ($request == -1) {
	$fn->writelog("[claim.asp]\t(".$fn->getipvisitor().")\tToken has been modified",'token_error.log');
	die('C0024');
} elseif ($request == -2){
	$fn->writelog("[claim.asp]\t(".$fn->getipvisitor().")\tToken is expired",'token_error.log');
	die('C0025');
}

$param = array(0=>$request['jid'],1=>$request['key'],2=>$request['day']);

if ($fn->getreferer() != PAGE_EVENT)
{
	if (!$_config['debug']) //ignore this if debug is true
	{
		$fn->writelog("[claim.asp]\t(".$fn->getipvisitor().")\tNO REFERER URL",'direct_access.log');
		die('C0022');
	}
}

if (!isset($param[0]) || !isset($param[1]) || !isset($param[2]))
{
	if (!$_config['debug']) //ignore this if debug is true
	{
		$fn->writelog("[claim.asp]\t(".$fn->getipvisitor().")\t".$_SERVER['QUERY_STRING'],'direct_access.log');
		die('C0020');
	}
}

if (!is_numeric($param[0]) || !$fn->isMD5($param[1]) || !is_numeric($param[2]))
{
	if (!$_config['debug']) //ignore this if debug is true
	{
		$fn->writelog("[claim.asp]\t(".$fn->getipvisitor().")\t".$_SERVER['QUERY_STRING'],'direct_access.log');
		die('C0021');
	}
}

if (strtolower($param[1]) != strtolower(md5($param[0].$fn->certifykey($param[0]).VERIFYKEY)))
{
	//Forward user to this page if found guilty :D
	if (!$_config['debug']){
		$fn->writelog("[claim.asp]\t(".$fn->getipvisitor().")\tkey param and generated fingerprint key do not match",'fingerprint_error.log');
		die('C0022');
	}
}

print $fn->claimrewards($param[0], $param[2]);
