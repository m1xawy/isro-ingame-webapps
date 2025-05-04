<?php
if ($continue) /* if $continue is false then escape this part */
{
	$token_param = $_GET['token'];
	$request = $fn->readtoken($token_param, SITE_PASS);
	if (!$request){ // wrong password
		header("Location: ".PAGE_ERROR."?code=C115");
		$continue = false;
	} elseif ($request == -1) { // token has been modified
		header("Location: ".PAGE_ERROR."?code=C112");
		$continue = false;
	} elseif ($request == -2){ // token has expired
		header("Location: ".PAGE_ERROR."?code=C113");
		$continue = false;
	}
}

if ($continue) /* if $continue is false then escape this part */
{
	if (!isset($token_param)) // token not set
	{
		$fn->writelog("[Unkown Parameter]\t(".$fn->getipvisitor().")\t".$_SERVER['QUERY_STRING'],'direct_access.log');
		header("Location: ".PAGE_ERROR."?code=C108");
		$continue = false;
	}
}

if ($continue) /* if $continue is false then escape this part */
{
	if ($fn->getreferer() != PAGE_GATEWAY) // referer not equal to gateway url
	{
		if (!$_config['debug']) //ignore this if debug is true
		{
			$fn->writelog("[Unkown Parameter]\t(".$fn->getipvisitor().")\tNO REFERER URL",'referrer.log');
			header("Location: ".PAGE_ERROR."?code=C108");
			$continue = false;
		}
	}
}

$param = array(0=>$request['jid'],1=>$request['key']);

$jid = $param[0];

$key = $param[1];

if ($continue) /* if $continue is false then escape this part */
{
	if (!is_numeric($jid) || !$fn->isMD5($key)) // jid is not number, key is not md5 hash
	{
		if (!$_config['debug']) //ignore this if debug is true
		{
			header("Location: ".PAGE_ERROR."?code=C105");
			$continue = false;
		}
	}
}

if ($continue) /* if $continue is false then escape this part */
{
	if (!$fn->fingerprint("JID:".$jid.",Key:".$key.",IP:".$fn->getipvisitor().",Timestamp:".time(),$key)) // detect duplicated fingerprint
	{
		if (!$_config['debug']) //ignore this if debug is true
		{
			header("Location: ".PAGE_ERROR."?code=C110");
			$continue = false;
		}
	}
}

if ($continue) /* if $continue is false then escape this part */
{
	if ($_config['debug'] && !$fn->isdeveloper($jid)) /* GM Only */
	{
		header("Location: ".PAGE_ERROR."?code=maintainance");
		$continue = false;
	}
}

if ($continue) /* if $continue is false then escape this part */
{
	if(date_format($fn->getservtime(),"j") >= 29) // attendance event is not active
	{
		if (!$_config['debug']) //ignore this if debug is true
		{
			header("Location: ".PAGE_ERROR."?code=C102");
			$continue = false;
		}
	}
}

if ($continue) /* if $continue is false then escape this part */
{
	$genkey = $fn->certifykey($jid);
	if ($genkey == -1) {
		if (!$_config['debug'])
		{
			header("Location: ".PAGE_ERROR."?code=C106");
			$continue = false;
		}
	} else {
		$fingerprint = md5($jid.$genkey.VERIFYKEY);	
	}
}

if ($continue) /* if $continue is false then escape this part */
{
	if (strtolower($key) != strtolower($fingerprint)) // key is not equal to generated fingerprint
	{
		if (!$_config['debug']) //ignore this if debug is true
		{
			header("Location: ".PAGE_ERROR."?code=C104");
			$continue = false;
		}
	}
}

if ($continue) /* if $continue is false then escape this part */
{	
	$calendar_info = $fn->getloginstatistics2($jid);
	if ($calendar_info == -1) {
		if (!$_config['debug'])
		{
			$fn->writelog($jid);
			header("Location: ".PAGE_ERROR."?code=C118");
			$continue = false;
		}
	}
}

if ($continue) /* if $continue is false then escape this part */
{
	if (!is_numeric($calendar_info)) // $calendar_info is not number ***CRITICAL***
	{
		if (!$_config['debug'])
		{
			header("Location: ".PAGE_ERROR."?code=C119");
			$continue = false;
		}
	}
}

if ($continue) /* if $continue is false then escape this part */
{
	$logintime = $fn->getlogintime($jid);
	if ($logintime == -1){
		if (!$_config['debug'])
		{
			header("Location: ".PAGE_ERROR."?code=C120");
			$continue = false;
		}
	}
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="x-ua-compatible" content="IE=9">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?=SITE_KWRD?>" />
<meta name="Descrition" content="<?=SITE_DESC?>" />
<link rel="stylesheet" type="text/css" media="all" href="dist/css/itemmall_game.css?v=<?=rand(1111,9999)?>" />
<script type="text/javascript" src="dist/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="dist/js/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="dist/js/jquery.pngFix.js"></script>
<script type="text/javascript" src="dist/js/jquery.sexy-combo.min.js"></script>
<script type="text/javascript" src="dist/js/jquery.cluetip.js"></script>
<script type="text/javascript" src="dist/js/jquery.scroll.js"></script>
<script type="text/javascript" src="dist/js/ingame_shell.js"></script>
<script type="text/javascript" src="dist/js/common.js?v=<?=rand(1111,9999)?>"></script>
<title><?=SITE_NAME?></title>
</head>
<body class="mig" ondragstart="return false" onselectstart="return false">
<div id="wrap" class="rigid">
	<h1><?=SITE_NAME?></h1><div class="clock"><div class="time">[Server Time: <span id="servertime"><?=date("F d, Y H:i:s")?></span>]<br>[Today's Total Play Time: <span id="playtime"><?=date("H:i:s")?></span>]</div></div><div class="help" onclick="jmxModal('<p><?=str_replace(array("\n", "\r"), '',file_get_contents("help.txt", true));?></p>','inf')"></div><div class="reload" onclick="reload('<?=urlencode($fn->gentoken(time()."|".$param[0]."|".$param[1],SITE_PASS))?>',this)"></div>
	<div id="screen">
		<div class="opener mold"></div>
		<div class="container">
			<div class="daypanel"><div class="day01"><div class="day-logged-<?=$fn->calendar_islogged(1,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day02"><div class="day-logged-<?=$fn->calendar_islogged(2,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day03"><div class="day-logged-<?=$fn->calendar_islogged(3,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day04"><div class="day-logged-<?=$fn->calendar_islogged(4,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day05"><div class="day-logged-<?=$fn->calendar_islogged(5,$calendar_info)?>"></div></div></div>
			<div class="rwdpanel">
				<div class="dlist">				
					<ul class="ulist">
					
					<?php $num=0; $rewards=$fn->getloginrewards(5); if ($rewards) { foreach ($rewards as $reward) { $num++; ?>
						<li class="item" <?php if ($num == 5) echo 'id="last"'; ?>><img src="./silkroadr/media/icon/<?=$reward['RefAssocFileIcon128']?>" alt="<?=$fn->getitemname($reward['RefNameStrID128'])?> x<?=$reward['Count']?>" /></li>
					<?php } } ?>
					
					</ul>
				</div>
				<button class="button-dls" onclick="jmxModal('<table border=0 cellpadding=0 cellspacing=0 class=reward_list><?php $rewards=$fn->getloginrewards(5); if ($rewards) { foreach ($rewards as $reward) { if($reward['RefItemID'] == "-1") continue;?><tr><td class=reward_icon><img src=./silkroadr/media/icon/<?=$reward['RefAssocFileIcon128']?>></td><td class=reward_name><?=$fn->getitemname($reward['RefNameStrID128'])?></td><td class=reward_qnty>x<?=$reward['Count']?></td></tr><?php }}?></table>','dtl');">Reward Details</button><?php $isclaimed = $fn->isclaimed($jid,5); if ($isclaimed == 0) { ?><button class="button-clm" onclick="claimReward('<?=urlencode($fn->gentoken(time()."|".$jid."|".$fingerprint."|5",SITE_PASS))?>',this);">Claim Reward</button><?php } else { ?><div class="claimed">Claimed</div><?php } ?>
				
			</div>
			<div class="daypanel"><div class="day06"><div class="day-logged-<?=$fn->calendar_islogged(6,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day07"><div class="day-logged-<?=$fn->calendar_islogged(7,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day08"><div class="day-logged-<?=$fn->calendar_islogged(8,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day09"><div class="day-logged-<?=$fn->calendar_islogged(9,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day10"><div class="day-logged-<?=$fn->calendar_islogged(10,$calendar_info)?>"></div></div></div>
			<div class="rwdpanel">
				<div class="dlist">
					<ul class="ulist">
					
					<?php $num=0; $rewards=$fn->getloginrewards(10); if ($rewards) { foreach ($rewards as $reward) { $num++; ?>
						<li class="item" <?php if ($num == 5) echo 'id="last"'; ?>><img src="./silkroadr/media/icon/<?=$reward['RefAssocFileIcon128']?>" alt="<?=$fn->getitemname($reward['RefNameStrID128'])?> x<?=$reward['Count']?>" /></li>
					<?php } } ?>
					
					</ul>
				</div>
				<button class="button-dls" onclick="jmxModal('<table border=0 cellpadding=0 cellspacing=0 class=reward_list><?php $rewards=$fn->getloginrewards(10); if ($rewards) { foreach ($rewards as $reward) { if($reward['RefItemID'] == "-1") continue;?><tr><td class=reward_icon><img src=./silkroadr/media/icon/<?=$reward['RefAssocFileIcon128']?>></td><td class=reward_name><?=$fn->getitemname($reward['RefNameStrID128'])?></td><td class=reward_qnty>x<?=$reward['Count']?></td></tr><?php }}?></table>','dtl');">Reward Details</button><?php $isclaimed = $fn->isclaimed($jid,10); if ($isclaimed == 0) { ?><button class="button-clm" onclick="claimReward('<?=urlencode($fn->gentoken(time()."|".$jid."|".$fingerprint."|10",SITE_PASS))?>',this);">Claim Reward</button><?php } else { ?><div class="claimed">Claimed</div><?php } ?>
				
			</div>
			<div class="daypanel"><div class="day11"><div class="day-logged-<?=$fn->calendar_islogged(11,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day12"><div class="day-logged-<?=$fn->calendar_islogged(12,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day13"><div class="day-logged-<?=$fn->calendar_islogged(13,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day14"><div class="day-logged-<?=$fn->calendar_islogged(14,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day15"><div class="day-logged-<?=$fn->calendar_islogged(15,$calendar_info)?>"></div></div></div>
			<div class="rwdpanel">
				<div class="dlist">
					<ul class="ulist">
					
					<?php $num=0; $rewards=$fn->getloginrewards(15); if ($rewards) { foreach ($rewards as $reward) { $num++; ?>
						<li class="item" <?php if ($num == 5) echo 'id="last"'; ?>><img src="./silkroadr/media/icon/<?=$reward['RefAssocFileIcon128']?>" alt="<?=$fn->getitemname($reward['RefNameStrID128'])?> x<?=$reward['Count']?>" /></li>
					<?php } } ?>
					
					</ul>
				</div>
				<button class="button-dls" onclick="jmxModal('<table border=0 cellpadding=0 cellspacing=0 class=reward_list><?php $rewards=$fn->getloginrewards(15); if ($rewards) { foreach ($rewards as $reward) { if($reward['RefItemID'] == "-1") continue;?><tr><td class=reward_icon><img src=./silkroadr/media/icon/<?=$reward['RefAssocFileIcon128']?>></td><td class=reward_name><?=$fn->getitemname($reward['RefNameStrID128'])?></td><td class=reward_qnty>x<?=$reward['Count']?></td></tr><?php }}?></table>','dtl');">Reward Details</button><?php $isclaimed = $fn->isclaimed($jid,15); if ($isclaimed == 0) { ?><button class="button-clm" onclick="claimReward('<?=urlencode($fn->gentoken(time()."|".$jid."|".$fingerprint."|15",SITE_PASS))?>',this);">Claim Reward</button><?php } else { ?><div class="claimed">Claimed</div><?php } ?>
				
			</div>
			<div class="daypanel"><div class="day16"><div class="day-logged-<?=$fn->calendar_islogged(16,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day17"><div class="day-logged-<?=$fn->calendar_islogged(17,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day18"><div class="day-logged-<?=$fn->calendar_islogged(18,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day19"><div class="day-logged-<?=$fn->calendar_islogged(19,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day20"><div class="day-logged-<?=$fn->calendar_islogged(20,$calendar_info)?>"></div></div></div>
			<div class="rwdpanel">
				<div class="dlist">
					<ul class="ulist">
					
					<?php $num=0; $rewards=$fn->getloginrewards(20); if ($rewards) { foreach ($rewards as $reward) { $num++; ?>
						<li class="item" <?php if ($num == 5) echo 'id="last"'; ?>><img src="./silkroadr/media/icon/<?=$reward['RefAssocFileIcon128']?>" alt="<?=$fn->getitemname($reward['RefNameStrID128'])?> x<?=$reward['Count']?>" /></li>
					<?php } } ?>
					
					</ul>
				</div>
				<button class="button-dls" onclick="jmxModal('<table border=0 cellpadding=0 cellspacing=0 class=reward_list><?php $rewards=$fn->getloginrewards(20); if ($rewards) { foreach ($rewards as $reward) { if($reward['RefItemID'] == "-1") continue;?><tr><td class=reward_icon><img src=./silkroadr/media/icon/<?=$reward['RefAssocFileIcon128']?>></td><td class=reward_name><?=$fn->getitemname($reward['RefNameStrID128'])?></td><td class=reward_qnty>x<?=$reward['Count']?></td></tr><?php }}?></table>','dtl');">Reward Details</button><?php $isclaimed = $fn->isclaimed($jid,20); if ($isclaimed == 0) { ?><button class="button-clm" onclick="claimReward('<?=urlencode($fn->gentoken(time()."|".$jid."|".$fingerprint."|20",SITE_PASS))?>',this);">Claim Reward</button><?php } else { ?><div class="claimed">Claimed</div><?php } ?>
				
			</div>
			<div class="daypanel"><div class="day21"><div class="day-logged-<?=$fn->calendar_islogged(21,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day22"><div class="day-logged-<?=$fn->calendar_islogged(22,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day23"><div class="day-logged-<?=$fn->calendar_islogged(23,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day24"><div class="day-logged-<?=$fn->calendar_islogged(24,$calendar_info)?>"></div></div></div>
			<div class="daypanel"><div class="day25"><div class="day-logged-<?=$fn->calendar_islogged(25,$calendar_info)?>"></div></div></div>
			<div class="rwdpanel">
				<div class="dlist">
					<ul class="ulist">
					
					<?php $num=0; $rewards=$fn->getloginrewards(25); if ($rewards) { foreach ($rewards as $reward) { $num++; ?>
						<li class="item" <?php if ($num == 5) echo 'id="last"'; ?>><img src="./silkroadr/media/icon/<?=$reward['RefAssocFileIcon128']?>" alt="<?=$fn->getitemname($reward['RefNameStrID128'])?> x<?=$reward['Count']?>" /></li>
					<?php } } ?>
					
					</ul>
				</div>
				<button class="button-dls" onclick="jmxModal('<table border=0 cellpadding=0 cellspacing=0 class=reward_list><?php $rewards=$fn->getloginrewards(25, 25); if ($rewards) { foreach ($rewards as $reward) { if($reward['RefItemID'] == "-1") continue;?><tr><td class=reward_icon><img src=./silkroadr/media/icon/<?=$reward['RefAssocFileIcon128']?>></td><td class=reward_name><?=$fn->getitemname($reward['RefNameStrID128'])?></td><td class=reward_qnty>x<?=$reward['Count']?></td></tr><?php }}?></table>','dtl');">Reward Details</button><?php $isclaimed = $fn->isclaimed($jid,25); if ($isclaimed == 0) { ?><button class="button-clm" onclick="claimReward('<?=urlencode($fn->gentoken(time()."|".$jid."|".$fingerprint."|25",SITE_PASS))?>',this);">Claim Reward</button><?php } else { ?><div class="claimed">Claimed</div><?php } ?>
				
			</div>
		</div>
		<div class="closer mold"></div>
		<div id="rewardModal" class="modal">
			<div class="box-container">
				<div class="box-header">
					<div id="rewardTitle" class="box-title"></div>
					<span id="rewardClose" class="box-close"></span>		
				</div>
				<div class="box-body">
					<div id="rewardContent" class="context"></div>
				</div>
				<button id="rewardButton" class="box-button">Close</button>
			</div>
		</div>
	</div>
</div>
<script>
var serverdate=new Date('<?=$logintime['ServerTime']?>'); //static datetime
var lastlogindate=new Date('<?=$logintime['LoginTime']?>'); //static datetime
var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var divState = {};
function showhide(id) {
    if (document.getElementById) {
        var divid = document.getElementById(id);
        divState[id] = (divState[id]) ? false : true;
        for (var div in divState){
            if (divState[div] && div != id){
                document.getElementById(div).style.display = 'none';
                divState[div] = false;
            }
        }
        divid.style.display = (divid.style.display == 'block' ? 'none' : 'block');
    }
}
function servertime(){
	/** SERVERTIME **/
	serverdate.setSeconds(serverdate.getSeconds()+1);
	var sdatestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
	var stimestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
	$('#servertime').html(sdatestring+" - "+stimestring);
	
	/** PLAYTIME **/
	var timediff = Math.abs(serverdate - lastlogindate) / 1000 + <?=$fn->getplaytime($jid)?>;
	var hh = Math.floor(timediff / 60 / 60 % 24);
	var mm = Math.floor(timediff / 60 % 60);
	var ss = Math.floor(timediff % 60);
	var ptimestring = padlength(hh) + ":" + padlength(mm) + ":" + padlength(ss);
	$('#playtime').html(ptimestring);
}
$(window).load(function(){
	disableDefaults();
	setInterval("servertime()", 1000);
	$('#loading', window.parent.document).hide();
	$('#wrap').fadeIn(200).delay(200);	
});
</script>
</body>
</html>