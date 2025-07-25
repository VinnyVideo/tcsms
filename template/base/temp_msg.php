<?php

class template_msg {

function start_rows ($order_list, $box, $dirsel, $dir_name) {
global $STD;
return <<<HTML
<script>
<!--
  function sel_all() {
    for (i=0; i<document.rowform.elements["mid[]"].length; i++) {
      document.rowform.elements["mid[]"][i].checked = document.rowform.selall.checked;
    }
  }
-->
</script>
<div class="sform">
<div class="sformstrip">Inbox</div>
<div style="padding:6px">
<form name="msgform" method="post" action="{$STD->tags['root_url']}act=msg&param=09">
<table class="sformtable" style="border-spacing:0px;"><tr>
  {$box}
  <td style="text-align:right"><b><a href="{$STD->tags['root_url']}act=msg&param=05">Compose New Message</a></b>
    <div style='margin-top:4px'>{$dirsel} <input type="submit" value="Go" class="button"></div></td>
</tr></table>
</form>
</div>
<form name="rowform" method="post" action="{$STD->tags['root_url']}act=msg&param=04">
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformtitle" style="width: 5%">&nbsp;</td>
  <td class="sformtitle" style="width: 40%"><a href="{$order_list['t']['url']}"><u>Subject</u></a> {$order_list['t']['img']}</td>
  <td class="sformtitle" style="width: 25%"><a href="{$order_list['u']['url']}"><u>{$dir_name}</u></a> {$order_list['u']['img']}</td>
  <td class="sformtitle" style="width: 25%"><a href="{$order_list['d']['url']}"><u>Date</u></a> {$order_list['d']['img']}</td>
  <td class="sformtitle" style="width: 5%;margin:auto;">
    <input type="checkbox" name="selall" title="Select All" class="checkbox" onclick="javascript:sel_all();"></td>
</tr>
HTML;
}

function storage_box ($current, $limit, $current_percent) {
global $STD;
$current_p = min(200, $current_percent*2);
$limit_p = 200 - $current_p;
$c_disp = $l_disp = '';
if ($current_p <= 0) $c_disp = ";display:none'";
if ($limit_p <= 0) $l_disp = ";display:none'";
return <<<HTML
  <td style="width:50px">Storage:&nbsp;</td>
  <td style="width:{$current_p}px{$c_disp}">
    <div style="height:16px; background:orange; border:1px solid gray"></div></td>
  <td style="width:{$limit_p}px{$l_disp}">
    <div style="height:16px; background:green; border:1px solid gray"></div></td>
  <td style="width:auto">&nbsp;{$current} / {$limit} messages</td>
HTML;
}

function end_rows ($pages) {
global $STD;
return <<<HTML
<tr>
  <td class="sformtitle" colspan="5" style="padding:2px">
  <table style="border-spacing:0px;width:100%;"><tr>
    <td style="width:50%;padding:2px;">
    </td>
    <td style="width:50%;padding:2px;float:right"><input type="submit" name="delete" value="Delete" class="button"> Selected Messages</td>
  </tr></table></td>
</tr>
</table>
</form>
<div class="sformstrip">Pages: {$pages}</div>
</div>
HTML;
}

function msg_row ($msg) {
global $STD;
return <<<HTML
<tr>
  <td class="sformfree" style="margin:auto;">{$msg['icon']}</td>
  <td class="sformfree">{$msg['title']}</td>
  <td class="sformfree">{$msg['sender']}</td>
  <td class="sformfree">{$msg['date']}</td>
  <td class="sformfree" style="margin:auto;"><input type='checkbox' name='mid[]' value='{$msg['mid']}' title="Select Message" class="checkbox"></td>
</tr>
HTML;
}

function msg_view ($msg) {
global $STD;
return <<<HTML
<script>
<!--
  function check_delete () {
  	form_check = confirm('Are you sure you want to delete this message?');
  	
  	if (form_check == true) {
  		return true;
  	} else {
  		return false;
  	}
  }
-->
</script>
<div class="sform">
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformtitle" style="width:20%;">{$msg['sender']}</td>
  <td class="sformtitle" style="width:80%;"><table style="border-spacing:0px;"><tr>
    <td>{$msg['title']}</td>
    <td style="float:right;"><a href="{$msg['report_url']}"><img src="{$STD->tags['global_image_path']}/report.gif" alt="[!]" title="Report this Message"></a></td>
  </tr></table></td>
</tr>
<tr>
  <td class="sformfree">&nbsp;</td>
  <td class="sformfree">{$msg['body']}<br>&nbsp;</td>
</tr>
<tr>
  <td class="sformtitle">&nbsp;</td>
  <td class="sformtitle"><table style="border-spacing:0px;float:right;width:100%;"><tr>
  	<td style="font-weight:normal;">{$msg['date']}</td>
    <td style="float:right;"><a href="{$STD->tags['root_url']}act=msg&amp;param=07&amp;mid={$msg['mid']}">[Reply]</a> &nbsp; 
    <a href="{$STD->tags['root_url']}act=msg&amp;param=04&amp;mid={$msg['mid']}" onclick="return check_delete();">[Delete]</a></td>
  </tr></table></td>
</tr>
</table>
</div>
HTML;
}

function msg_history ($list) {
global $STD;
return <<<HTML
<div id="chm" class="sform">
<div class="sformstrip"><a href="javascript:show_hide('ch'); show_hide('chm')">Show conversation history</a></div>
</div>
<div id="ch" class="sform" style="display:none">
  {$list}
  <div class="sformstrip"><a href="javascript:show_hide('ch'); show_hide('chm')">Hide conversation history</a></div>
</div>
<br>
HTML;
}

function msg_history_row ($msg) {
global $STD;
return <<<HTML
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformtitle" style="width: 20%">{$msg['sender']}</td>
  <td class="sformtitle" style="width: 80%;"><table style="border-spacing:0px;"><tr>
    <td>{$msg['title']}</td>
    <td style="float:right;font-weight:normal;">{$msg['date']}</td>
  </tr></table></td>
</tr>
<tr>
  <td class="sformfree">&nbsp;</td>
  <td class="sformfree">{$msg['body']}<br>&nbsp;</td>
</tr>
</table>
HTML;
}

function msg_compose ($msg, $token, $reply, $staff_reply) {
global $STD;
return <<<HTML
<script>
<!--
  function set_staff () {
  	document.compose.recipient.disabled = true;
  }
  function set_other () {
  	document.compose.recipient.disabled = false;
  }
  function staff_reply () {
  	if ({$staff_reply} == 1) {
  	  return checked;
  	}
  	return false;
  }
  function recip_reply () {
  	if ({$staff_reply} == 0) {
  	  return checked;
  	}
  	return false;
  }
-->
</script>
<div class="sform">
<form name="compose" method="post" action="{$STD->tags['root_url']}act=msg&amp;param=06" id="messageMainForm">
<input type="hidden" name="security_token" value="{$token}">
<input type="hidden" name="reply" value="{$reply}">
<div class="sformstrip">Compose a new message</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Send To</td>
  <td class="sformright"><input type="radio" name="to" value="staff" onclick="set_staff();" title="Send to Staff" class="radiobutton"> Site Staff<br>
  	<input type="radio" name="to" value="other" onclick="set_other();" title="Send to Other Recipient" class="radiobutton"> Other Recipient
  </td>
<tr>
  <td class="sformleft"><label for="recipient">Recipient</label></td>
  <td class="sformright"><input type="text" id="recipient" name="recipient" size="40" value="{$msg['recipient']}" title="Send to Other Recipient" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="subject">Subject</label></td>
  <td class="sformright"><input type="text" id="subject" name="subject" size="40" value="{$msg['subject']}" class="textbox"></td>
</tr>
</table>
<div class="sformstrip">Write your message</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="body">Message</label></td>
  <td class="sformright"><textarea name="body" id="body" rows="6" cols="40" class="textbox">{$msg['body']}</textarea><br>
    <input type="checkbox" name="copy" title="Keep copy in Sent Messages" checked="checked" value="1"> Keep copy in Sent Messages folder</td>
</tr>
</table>
<div class="sformstrip" style="text-align: center"><input type="submit" value="Send Message" class="button"></div>
</form>
</div>
<script>
<!--
  if ({$staff_reply} == 1) {
    document.compose.to[0].checked = true;
  } else {
    document.compose.to[1].checked = true;
  }
-->
</script>
HTML;
}

}