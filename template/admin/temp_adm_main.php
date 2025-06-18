<?php

class template_adm_main {

function main_page ($notepad, $url, $data, $delurl, $uid) {
global $STD;
$ret = <<<HTML
From the ACP you'll be able to manage users and submissions, update the front page, respond to messages, and control how the site operates.  You should always log out when you finish.
<br><br>
<div>
<table style="border-spacing:0px; width:100%;">
<tr>
  <td style="width:50%;padding:0px;">
    <form method="post" action="{$url}" name="notepadform">
		<label for="notepad"><b>Staff Discussion</b></label> <input type="submit" value="Submit" class="button" tabindex="2"><br>
		<textarea id="notepad" name="notepad" rows="2" cols="100" style="border:1px solid black; background-color:#FBFCCE;" tabindex="1"></textarea><br>
    </form>
  </td>
  <td>
    &nbsp;
  </td>
</tr>
</table><br><div class="rowfield" style="width:100%">
<table class="rowtable" style="border-spacing:1px;width:100%;">
HTML;
foreach ($data as $dat) {
		// rowcell2
		$ret .= "<tr>
		<td style=\"width:12%;text-align:right;\" class=\"rowtitle\" ><a href=\"{$dat['uidurl']}\">{$dat['name']}</a></td>
		<td class=\"rowcell2 canquote\" onClick=\"quote('{$dat['name']}', {$dat['id']});\">{$dat['message']}</td>
		<td style=\"width:23%;\" class=\"rowtitle\">{$dat['date']}<div style=\"display:none\" id=\"msg{$dat['id']}\">{$dat['raw']}</div>";
		if ($dat['uid'] == $uid) 
			$ret .= " <a title=\"Delete\" href=\"{$delurl}&id={$dat['id']}\" style=\"color:maroon\" onClick=\"if(!confirm('Are you sure?'))return false;\">X</a>";
		$ret .= "</td></tr>";
}
$ret .= <<<HTML
</table></div>
</div>
HTML;
return $ret;
}

}
?>