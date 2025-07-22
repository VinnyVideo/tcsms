<?php

class template_acp_mod_games {

function acp_edit_form ($res) {
global $STD;
return <<<HTML
<tr>
  <td class='title_fixed'>
    Completion
  </td>
  <td class='field_fixed'>
	{$res['cat1']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    Genre
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
    {$res['cat3']}
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td style='border-bottom:1px solid #666666; font-size:14pt'>&#8212;&#8212; Part 2</td>
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
    <input type='text' id="author" name='author' value="{$res['username']}" size='40' class='textbox'> {$res['usericon']}
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
    <textarea rows='8' cols='55' id='description' name='description' class='textbox'>{$res['description']}</textarea>
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
    <label for="file_html5">HTML5 Folder Path</label>
  </td>
  <td class='field_fixed'>
    <input type='text' id='file_html5' name='file_html5' value="{$res['file_html5']}" size='50' class='textbox'>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    Preview <a href="javascript:show_hide('f1_4a');show_hide('f1_4b');show_hide('f1_4c');">(Replace)</a>
  </td>
  <td class='field_fixed'>
    {$res['preview']}
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
    <input type='file' name='preview' class='textbox' size='40'> -OR-
  </td>
</tr>
<tr id='f1_4c' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB; border-bottom: 1px solid gray;'>
    Specify Filename
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-bottom: 1px solid gray;'>
    <input type='text' name='preview_name' value='' size='40' class='textbox'>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    Thumbnail <a href="javascript:show_hide('f1_5a');show_hide('f1_5b');show_hide('f1_5c');show_hide('f1_5d');">(Replace)</a>
  </td>
  <td class='field_fixed'>
    {$res['thumbnail']}
  </td>
</tr>
<tr id='f1_5a' style='display:none'>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr id='f1_5b' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray;'>
    Upload File
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray;'>
    <input type='file' name='thumbnail' class='textbox' size='40'> -OR-
  </td>
</tr>
<tr id='f1_5c' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB;'>
    Specify Filename
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB;'>
    <input type='text' name='thumbnail_name' value='' size='40' class='textbox'> -OR-
  </td>
</tr>
<tr id='f1_5d' style='display:none'>
  <td class='title_fixed' style='background-color: #BDC5EB; border-bottom: 1px solid gray;'>
    Generate From Preview
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-bottom: 1px solid gray;'>
    <input type='checkbox' name='thumbnail_gen' value='1' class='checkbox'>
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
?>