<?php

class template_acp_mod_gfx {

function acp_edit_form ($res) {
global $STD;
return <<<HTML
<tr>
  <td class='title_fixed'>
    Format
  </td>
  <td class='field_fixed'>
	{$res['cat1']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    Contents
  </td>
  <td class='field_fixed'>
    {$res['cat2']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    Franchise
  </td>
  <td class='field_fixed'>
    {$res['cat6']}
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    Genres
  </td>
  <td class='field_fixed'>
    <a href="javascript:show_hide('f1_5a');show_hide('f1_5b');show_hide('f1_5c');">Show / Hide Selections</a>
  </td>
</tr>
<tr id='f1_5a' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray; border-bottom: 1px solid gray;'>
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray; border-bottom: 1px solid gray;'>
    {$res['cat3']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    Associated Games
  </td>
  <td class='field_fixed'>
    <a href="javascript:show_hide('f1_6a');show_hide('f1_6b');show_hide('f1_6c');">Show / Hide Selections</a>
  </td>
</tr>
<tr id='f1_6a' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray; border-bottom: 1px solid gray;'>
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray; border-bottom: 1px solid gray;'>
    {$res['cat4']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    Associated Characters
  </td>
  <td class='field_fixed'>
    <a href="javascript:show_hide('f1_7a');show_hide('f1_7b');show_hide('f1_7c');">Show / Hide Selections</a>
  </td>
</tr>
<tr id='f1_7a' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray; border-bottom: 1px solid gray;'>
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray; border-bottom: 1px solid gray;'>
    {$res['cat5']}
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>

<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td style='border-bottom:1px solid #666666; font-size:14pt;'>&#8212;&#8212; Part 2</td>
  <td style='border-bottom:1px solid #666666; vertical-align:bottom;'><b>Base Data and Information</b></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="title">Title</label>
  </td>
  <td class='field_fixed'>
    <input type='text' id='title' name='title' value="{$res['title']}" size='40' class='textbox'>
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="author">Creator</label> <a href="javascript:show_hide('f1_h2a');show_hide('f1_h2b');show_hide('f1_h2c');">
				 <img src='./template/admin/images/info.gif' alt='[Info]'></a>
  </td>
  <td class='field_fixed'>
    <input type='text' id='author' name='author' value="{$res['username']}" size='40' class='textbox'> {$res['usericon']}
  </td>
</tr>
<tr id='f1_h2a' style='display:none'>
  <td class='title_fixed'>
    &nbsp;
  </td>
  <td class='field_fixed' style='background-color:#FBFCCE'>
    The creator field specifies a registered user this submission belongs to.  This name can be visibly overridden by specifying a username override.  If this submission doesn't belong to a registered user, a username override value can be used instead.  At least one of the two fields must have a value.
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="author_override">Username Override</label>
  </td>
  <td class='field_fixed'>
    <input type='text' id='author_override' name='author_override' value="{$res['author_override']}" size='40' class='textbox'>
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="website_override">Website Override</label>
  </td>
  <td class='field_fixed'>
    <input type='text' id='website_override' name='website_override' value="{$res['website_override']}" size='40' class='textbox'> {$res['website']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="weburl_override">Website URL Override</label>
  </td>
  <td class='field_fixed'>
    <input type='text' id='weburl_override' name='weburl_override' value="{$res['weburl_override']}" size='40' class='textbox'> {$res['weburl']}
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="description">Description</label>
  </td>
  <td class='field_fixed'>
    <textarea rows='6' cols='38' id='description' name='description' class='textbox'>{$res['description']}</textarea>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    Date Submitted
  </td>
  <td class='field_fixed'>
    {$res['created']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    Last Updated
  </td>
  <td class='field_fixed'>
    {$res['updated']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    Total Views
  </td>
  <td class='field_fixed'>
    {$res['views']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    Total Downloads
  </td>
  <td class='field_fixed'>
    {$res['downloads']}
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    File <a href="javascript:show_hide('f1_3a');show_hide('f1_3b');show_hide('f1_3c');">(Replace)</a>
  </td>
  <td class='field_fixed'>
    <a href="{$res['file']}">[View / Download]</a>
  </td>
</tr>
<tr id='f1_3a' style='display:none'>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr id='f1_3b' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray;'>
    Upload File
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray;'>
    <input type='file' name='file' class='textbox' size='40'> -OR-
  </td>
</tr>
<tr id='f1_3c' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB; border-bottom: 1px solid gray;'>
    Specify Filename
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-bottom: 1px solid gray;'>
    <input type='text' name='file_name' value='' size='40' class='textbox'>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    Thumbnail <a href="javascript:show_hide('f1_4a');show_hide('f1_4b');show_hide('f1_4c');">(Replace)</a>
  </td>
  <td class='field_fixed'>
    {$res['thumbnail']}
  </td>
</tr>
<tr id='f1_4a' style='display:none'>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr id='f1_4b' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray;'>
    Upload File
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray;'>
    <input type='file' name='thumbnail' class='textbox' size='40'> -OR-
  </td>
</tr>
<tr id='f1_4c' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB; border-bottom: 1px solid gray;'>
    Specify Filename
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-bottom: 1px solid gray;'>
    <input type='text' name='thumbnail_name' value='' size='40' class='textbox'>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td style='border-bottom:1px solid #666666; font-size:14pt'>&#8212;&#8212; Part 3</td>
  <td style='border-bottom:1px solid #666666; vertical-align:bottom;'><b>Commit Changes</b></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
HTML;
}

}