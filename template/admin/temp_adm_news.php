<?php

class template_adm_news {

function message ($message) {
global $STD;
return <<<HTML
<br>
{$message}
HTML;
}

function add_news ($token) {
global $STD;
return <<<HTML
Write a new news entry here.  {%recent_updates%} will insert the Recent Updates into your message.
<form method="post" action="{$STD->tags['root_url']}act=news&amp;param=02">
<input type="hidden" name="security_token" value="{$token}">
<div style="margin-left:20px;">
<br>
<table style="border-spacing:0px;width:90%;">
<tr>
  <td style="width:20%;" class="title"><label for="title">Title</label></td>
  <td style="width:80%;" class="field"><input type="text" id="title" name="title" size="60" class="textbox"></td>
</tr>
<tr>
  <td style="width:20%;" class="title"><label for="content">Content</label></td>
  <td style="width:80%;" class="field"><textarea id="content" name="content" rows="14" cols="80" class="textbox"></textarea></td>
</tr>
<tr>
  <td style="width:20%;" class="title">&nbsp;</td>
  <td style="width:80%;" class="field">&nbsp;</td>
</tr>
<tr>
  <td style="width:20%;" class="title">Complete Form</td>
  <td style="width:80%;" class="field"><input type="submit" value="Add Entry" class="button"></td>
</tr>
</table>
</div>
</form>
HTML;
}

function edit_header ($olinks) {
global $STD;
return <<<HTML
Edit or remove news entries.
<div style="margin-left:40px;">
<br>
<div class="rowfield">
<table class="rowtable" style="border-spacing:1px;">
<tr>
  <td class="rowtitle" style="width:60%;"><a href="{$olinks['t']['url']}">Title</a> {$olinks['t']['img']}</td>
  <td class="rowtitle" style="width:18%;"><a href="{$olinks['u']['url']}">Author</a> {$olinks['u']['img']}</td>
  <td class="rowtitle" style="width:12%;"><a href="{$olinks['d']['url']}">Date</a> {$olinks['d']['img']}</td>
  <td class="rowtitle" style="width:10%;">&nbsp;</td>
</tr>
HTML;
}

function edit_footer ($pages) {
global $STD;
return <<<HTML
</table>
</div>
<div style="width: 90%; text-align: left">Pages: {$pages}</div>
<br>
</div>
HTML;
}

function edit_row ($news) {
global $STD;
return <<<HTML
<tr>
  <td class="rowcell2">{$news['title']}</td>
  <td class="rowcell2">{$news['author']}</td>
  <td class="rowcell2">{$news['date']}</td>
  <td class="rowcell2" style="text-align: center;">{$news['delete']}</td>
</tr>
HTML;
}

function edit_entry ($news, $token) {
global $STD;
return <<<HTML
Write a new news entry here.  
<form method="post" action="{$STD->tags['root_url']}act=news&amp;param=06">
<input type="hidden" name="security_token" value="{$token}">
<input type="hidden" name="nid" value="{$news["nid"]}">
<div style="margin-left:20px;">
<br>
<table style="border-spacing:0px;width:90%;">
<tr>
  <td style="width:20%;" class="title"><label for="title">Title</label></td>
  <td style="width:80%;" class="field"><input type="text" id="title" name="title" size="60" value="{$news['title']}" class="textbox"></td>
</tr>
<tr>
  <td style="width:20%;" class="title"><label for="content">Content</label></td>
  <td style="width:80%;" class="field"><textarea id="content" name="content" rows="14" cols="80" class="textbox">{$news['message']}</textarea></td>
</tr>
<tr>
  <td style="width:20%;" class="title">&nbsp;</td>
  <td style="width:80%;" class="field">&nbsp;</td>
</tr>
<tr>
  <td style="width:20%;" class="title">Complete Form</td>
  <td style="width:80%;" class="field"><input type="submit" value="Modify Entry" class="button"></td>
</tr>
</table>
</div>
</form>
HTML;
}

}

?>