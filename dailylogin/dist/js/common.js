var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = Base64._utf8_decode(output);
        return output;
    },
    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while (i < utftext.length) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
}
function disableDefaults() {
	var message = "";
	function clickIE() { if (document.all) { (message); return false; } }
	function clickNS(e) {
		if(document.layers || (document.getElementById && !document.all)) {
			if (e.which == 2 || e.which == 3) { (message); return false; }
		}
	}
	if (document.layers) {
		document.captureEvents(Event.MOUSEDOWN); document.onmousedown = clickNS;
	} else {
		document.onmouseup = clickNS; document.oncontextmenu = clickIE;
	}
	document.oncontextmenu = new Function("return false")
	document.onselectstart = new Function('return false');
	function dMDown(e) { return false; }
	function dOClick() { return true; }
	document.onmousedown = dMDown;
	document.onclick = dOClick;
}
function jmxModal(texts,title){
	$('#rewardTitle').html('<div class="'+title+'"></div>').val();
	$('#rewardContent').html(texts).val();
	$('#rewardModal').show();
	$('#rewardContent').scrollbar();
}
function claimReward(token,elemIdx){
	$(elemIdx).attr("disabled",true);
	$.ajax({url:'claim.asp?token='+token,type:'GET',cache:false,success:function(result)
		{
			if (result == '0') {
				$(elemIdx).text('Claimed');
				jmxModal('The reward has been claimed.<br><br><i>Reward will be send to your Item Storage, it might take a few minutes to appear in the list.</i>','con');
			} else if(result == '-2000'){
				jmxModal('You already claim this reward!','inf');
				$(elemIdx).attr("disabled",false);
			} else if (result == '-3000'){
				jmxModal('You have not yet completed the days for this reward!','inf');
				$(elemIdx).attr("disabled",false);
			} else {
				jmxModal('Please contact administrator [Code:'+result+']','inf');
				$(elemIdx).attr("disabled",false);
			}
		}
	});
}
function padlength(what){
	var output=(what.toString().length==1)? "0"+what : what;
	return output;
}
function iframeload(url, param){
	$('#frame').attr("src", url + '?' + param);	
}
function showiframe(){
	$('#frame').show();
};
function reload(tkn,idx){
	$(idx).removeAttr('onclick').toggleClass('reload-click');
	$.ajax({url:'reload.asp?token='+tkn,type:'GET',success:function(result){window.top.location.href=result;}});
}