<?php
class functions {
	function writelog($logmsg,$file="error.log"){
		error_log(date('[Y-m-d H:i:s]: '). $logmsg . PHP_EOL, 3, $_SERVER['DOCUMENT_ROOT'] . "/logs/" . $file);
	}
	function getreferer(){
		$domain = explode('?', $_SERVER['HTTP_REFERER']);
		return $domain[0];
	}
	function getloginstatistics($uid){
		$dbo=$this->mssqlexec("SELECT * FROM _Rigid_Login_Event_Calendar WHERE JID=?", $uid);
		if (!$dbo || $dbo->RowCount() == 0) return false;
		return $dbo->FetchRow();
	}	
	function getloginstatistics2($jid){
		//$this->updateplaytime($jid);
		$dbo=$this->mssqlexec("SELECT [1]+[2]+[3]+[4]+[5]+[6]+[7]+[8]+[9]+[10]+[11]+[12]+[13]+[14]+[15]+[16]+[17]+[18]+[19]+[20]+[21]+[22]+[23]+[24]+[25]+[26]+[27]+[28] FROM _Rigid_Login_Event_Calendar WHERE JID=?", $jid);
		if (!$dbo || $dbo->RowCount() == 0) -1;
		return $dbo->FetchRow()[0];
	}	
	function getloginrewards($day,$i=5){
		$dbo=$this->mssqlexec("SELECT TOP ".$i." * FROM _Rigid_Login_Event_Reward WHERE Day=? AND [Service]=1", $day);
		if (!$dbo || $dbo->RowCount() == 0) return false;
		return $dbo;
	}	
	function getitemname($item){
		$dbo=$this->mssqlexec("SELECT [ENG] FROM _Rigid_ItemNameDesc WHERE StrID=?", $item);
		if (!$dbo || $dbo->RowCount() == 0) return false;
		return str_replace('\'', '', $dbo->FetchRow()[0]);
	}	
	function isdeveloper($jid){
		$dbo=$this->mssqlexec("SELECT [sec_primary]+[sec_content] FROM TB_User WHERE JID=?", $jid);
		if (!$dbo || $dbo->RowCount() == 0) return false;
		if ($dbo->FetchRow()[0] != 2) return false;
		return true;
	}	
	function calendar_islogged($day,$x){
		$today = date_format($this->getservtime(),"j");		
		if ($day >= 26) return null;
		if ($day > $today) return null;
		if ($day < $today && $day <= $x) return 1;
		if ($day == $today && $x == $today) return 1;
	}	
	function claimrewards($jid, $day){
		$dbo=$this->mssqlexec("EXEC _Rigid_Login_Event_Reward_Give ?,?", array($jid, $day));
		return $dbo->FetchRow()[0];
	}	
	function isclaimed($jid,$day){		
		$col = null;		
		if ($day == 5)	$col = "[Claim01to05]";
		if ($day == 10)	$col = "[Claim06to10]";
		if ($day == 15)	$col = "[Claim11to15]";
		if ($day == 20)	$col = "[Claim16to20]";
		if ($day == 25)	$col = "[Claim21to25]";		
		$dbo=$this->mssqlexec("SELECT ".$col." FROM _Rigid_Login_Event_Reward_Log WHERE JID=? AND [Month]=?", array($jid, date("n")));
		return $dbo->FetchRow()[0];
	}	
	function certifykey($jid){
		$dbo=$this->mssqlexec("SELECT TOP 1 [Certifykey] FROM WEB_ITEM_CERTIFYKEY WITH (NOLOCK) WHERE UserJID=? ORDER BY [reg_date] DESC", $jid);
		if (!$dbo || $dbo->RowCount() == 0) return -1;
		return $dbo->FetchRow()[0];
	}
	function getlogintime($jid){
		$dbo=$this->mssqlexec("SELECT [LoginTime] FROM _Rigid_Login_Event WHERE JID=?", $jid);
		if (!$dbo || $dbo->RowCount() == 0) return -1;
		$logindate=date_create($dbo->FetchRow()[0]);
		return array('LoginTime'=>date_format($logindate,"F d, Y H:i:s"), 'ServerTime'=>date_format($this->getservtime(),"F d, Y H:i:s"));
	}
	function updateplaytime($jid){
		//$dbo=$this->mssqlexec("EXEC _Rigid_Login_Event_Playtime_Check ?,?", array($jid,0));
	}
	function getplaytime($jid){
		$today = date_format($this->getservtime(),"j");
		if ($today > 25) return 0;
		$dbo=$this->mssqlexec("SELECT [".$today."] FROM [SILKROAD_R_ACCOUNT].[dbo].[_Rigid_Login_Event_Calendar_Playtime] WHERE JID=?", $jid);
		if (!$dbo || $dbo->RowCount() == 0) return 0;
		return $dbo->FetchRow()[0];
	}
	function gencertkey($jidx,$vkey){
		$dbo=$this->mssqlexec("EXEC _CertifyUser_WebItemMall_rigid ?,?,?", array($jidx,1,time()));
		if (!$dbo) return false;		
		if ($dbo->FetchRow()[0] == 1) {
			return strtoupper(md5($jidx.$this->certifykey($jidx).$vkey));
		} else {
			return false;
		}
	}
	function isMD5($md5){
		return !empty($md5) && preg_match('/^[a-fA-F0-9]{32}$/', $md5);
	}
	function gen_token($length){
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		for($i=0;$i<$length;$i++){
			$token .= $codeAlphabet[$this->crypto_rand_secure(0,strlen($codeAlphabet))];
		}
		return $token;
	}
	function getservtime(){
		$dbo=$this->mssqlexec("SELECT GETDATE()");
		if (!$dbo || $dbo->RowCount() == 0) return 0;
		return date_create($dbo->FetchRow()[0]);
	}
	function getipvisitor(){
		$visitor_ip = '0.0.0.0';
		if (!empty($_SERVER["HTTP_CF_CONNECTING_IP"])){
			$visitor_ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
		} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$visitor_ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$visitor_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$visitor_ip = $_SERVER['REMOTE_ADDR'];
		}
		return $visitor_ip;
	}
	function crypto_rand_secure($min, $max) {
		$range = $max - $min;
		if ($range < 0) return $min; // not so random...
		$log = log($range, 2);
		$bytes = (int) ($log / 8) + 1; // length in bytes
		$bits = (int) $log + 1; // length in bits
		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd >= $range);
		return $min + $rnd;
	}
	function stringsplit($txt,$del) {
		$output = "";
		$delcnt = substr_count($txt,$del);
		if($delcnt != 0) {
			$delcnt = $delcnt+1;
			$output = explode($del,$delcnt.$del.$txt);
		} else {
			$output = -1;
		}
		return $output;
	}
	function secureid($str) {
		$pattern2 = "#[^a-z0-9]#";
		if(preg_match($pattern2,$str)==true){
			return false;
		} else {
			return $str;
		}
	}
	function securepw($str) {
		$pattern3 = "#[^a-zA-Z0-9]#";
		if(preg_match($pattern3,$str)==true){
			return false;
		} else {
			return $str;
		}
	}
	function fingerprint($data,$fname) {
		$my_file = $_SERVER['DOCUMENT_ROOT']."/logs/visitor/fingerprint_".$fname.'.txt';
		if (!is_dir(pathinfo($my_file)['dirname'])) mkdir(pathinfo($my_file)['dirname'], 0777, true);
		
		if (!file_exists($my_file)){			
			file_put_contents($my_file, $data);			
			return true;
		} else {
			return false;
		}
	}
	function param_encrypt($string, $key) {
	  $result = '';
	  $result = openssl_encrypt($string, "AES-256-CBC", $key, 0, substr(md5($key),0,-16));
	  if (!$result) return $result; //return false
	  return base64_encode($result);
	}	
	function param_decrypt($string, $key) {
	  $result = '';
	  $string = base64_decode($string);
	  $result = openssl_decrypt($string, "AES-256-CBC", $key, 0, substr(md5($key),0,-16));
	  if (!$result) return $result; //return false
	  return $result;
	}	
	function gentoken($params,$pass) {
		$token_enc = $this->param_encrypt($params.md5($params),$pass);
		if (!$token_enc) return false;
		return $token_enc;
	}
	function readtoken($token,$pass,$checkspan=true) {
		$token_dec = $this->param_decrypt($token,$pass);
		$mdhash = substr($token_dec,(strlen($token_dec)-32),32);
		$string = substr($token_dec,0,(strlen($token_dec)-32));
		$explod = explode("|",$string);
		if (!$token_dec) return false; // wrong password
		if ($mdhash != md5($string)) return -1; // token has been hijack/edited
		if ($checkspan) { if (time() - $explod[0] > 1800) return -2; } // expiration of token
		if (count($explod) == 3) return array('jid'=>$explod[1],'key'=>$explod[2]);
		if (count($explod) == 4) return array('jid'=>$explod[1],'key'=>$explod[2],'day'=>$explod[3]);
	}
}
