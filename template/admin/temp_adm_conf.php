<?php

class template_adm_conf {

function filter_group_header() {
global $STD;
return <<<HTML
<div style="margin-left:50px;">
<br>
<div class="rowfield">
<table class="rowtable" style="border-spacing:1px;">
<tr>
  <td class="rowtitle" style="width:45%;">Group</td>
  <td class="rowtitle" style="width:45%;">Keyword</td>
  <td class="rowtitle" style="width:10%;">&nbsp;</td>
</tr>
HTML;
}

function filter_group_row($fg) {
global $STD;
return <<<HTML
<tr>
  <td class="rowcell2">{$fg['name']}</td>
  <td class="rowcell2">{$fg['keyword']}</td>
  <td class="rowcell2" style="text-align:center">[Delete]</td>
</tr>
HTML;
}

function filter_group_footer() {
global $STD;
return <<<HTML
</table>
</div>
</div>
<br>
HTML;
}

function filter_group_detail($fg, $token) {
global $STD;
return <<<HTML
<form method="post" action="{$STD->tags['root_url']}act=conf&amp;param=03">
<input type="hidden" name="gid" value="{$fg['gid']}">
<input type="hidden" name="security_token" value="{$token}">
<div align="center">
<br>
<div class="rowfield">
<div class="rowtitle">Group Detail</div>
<table class="rowtable" style="border-spacing:1px;">
<tr>
  <td style="width:30%;" class="rowcell3"><label for="name">Group Name</label></td>
  <td style="width:70%;" class="rowcell2"><input type="text" id="name" name="name" size="40" value="{$fg['name']}"></td>
</tr>
<tr>
  <td class="rowcell3"><label for="keyword">Group Keyword</label></td>
  <td class="rowcell2"><input type="text" id="keyword" name="keyword" size="40" value="{$fg['keyword']}"><br>
    <span style="font-size:8pt;">Change this value only if you know what you're doing</span></td>
</tr>
</table>
<div class="rowstrip" style="text-align:center"><input type="submit" value="Update Group Filter"></div>
</div>
<br>
</div>
</form>
HTML;
}

function filter_list_header($fg, $token) {
global $STD;
return <<<HTML
<form method="post" action="{$STD->tags['root_url']}act=conf&amp;param=05">
<input type="hidden" name="gid" value="{$fg['gid']}">
<input type="hidden" name="security_token" value="{$token}">
<div align="center">
<div class="rowfield">
<div class="rowtitle">Filter Entries</div>
<table class="rowtable" style="border-spacing:1px;">
<tr>
  <td class="rowtitle" style="width:40%;">Filter Name</td>
  <td class="rowtitle" style="width:20%;">Short Name</td>
  <td class="rowtitle" style="width:30%;">Search Keys</td>
  <td class="rowtitle" style="width:10%;">&nbsp;</td>
</tr>
HTML;
}

function filter_list_footer() {
global $STD;
return <<<HTML
</table>
<div class="rowstrip" style="text-align:center"><input type="submit" value="Update Filter Entries"></div>
</div>
</div>
</form>
<br>
HTML;
}

function filter_list_row($fl) {
global $STD;
return <<<HTML
<tr>
  <td class="rowcell2"><input type="text" name="name[{$fl['fid']}]" value="{$fl['name']}" title="Name" size="30"></td>
  <td class="rowcell2"><input type="text" name="short_name[{$fl['fid']}]" value="{$fl['short_name']}" title="Short Name" size="14"></td>
  <td class="rowcell2"><input type="text" name="keywords[{$fl['fid']}]" value="{$fl['search_tags']}" title="Keywords" size="20"></td>
  <td class="rowcell2" style="text-align:center">
    <a href="{$STD->tags['root_url']}act=conf&amp;param=06&amp;fid={$fl['fid']}">[Delete]</a></td>
</tr>
HTML;
}

function filter_list_add($fg, $token) {
global $STD;
return <<<HTML
<form method="post" action="{$STD->tags['root_url']}act=conf&amp;param=04">
<input type="hidden" name="gid" value="{$fg['gid']}">
<input type="hidden" name="security_token" value="{$token}">
<div align="center">
<div class="rowfield">
<div class="rowtitle">Add New Entry</div>
<table class="rowtable" style="border-spacing:1px;">
<tr>
  <td class="rowcell3" style="width:30%;"><label for="fullname">Full Name</label></td>
  <td class="rowcell2" style="width:70%;"><input type="text" id="fullname" name="name" size="40"></td>
</tr>
<tr>
  <td class="rowcell3"><label for="short_name">Short Name</label></td>
  <td class="rowcell2"><input type="text" id="short_name" name="short_name" size="40"></td>
</tr>
<tr>
  <td class="rowcell3"><label for="keywords">Search Keywords</label></td>
  <td class="rowcell2"><input type="text" id="keywords" name="keywords" size="40"></td>
</tr>
</table>
<div class="rowstrip" style="text-align:center"><input type="submit" value="Add Filter Entry"></div>
</div>
</div>
</form>
<br>
HTML;
}

}

?>