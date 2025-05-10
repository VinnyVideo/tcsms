<?php

class template_acp_mod_howtos {

function acp_edit_form ($res) {
global $STD;
return <<<HTML
<tr>
  <td class='title_fixed' valign='top'>
    Target Application
  </td>
  <td class='field_fixed'>
	{$res['cat1']}
  </td>
</tr>
<tr>
  <td class='title_fixed' valign='top'>
    Target Franchise
  </td>
  <td class='field_fixed'>
	{$res['cat2']}
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td style='border-bottom:1px solid #666666; font-size:14pt'>&#8212;&#8212; Part 2</td>
  <td style='border-bottom:1px solid #666666' valign='bottom'><b>Base Data and Information</b></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed' valign='top'>
    <label for="title">Title</label>
  </td>
  <td class='field_fixed'>
    <input type='text' id='title' name='title' value="{$res['title']}" size='40' class='textbox' />
  </td>
</tr>
<tr>
  <td class='title_fixed' valign='top'>
    <label for="author">Creator</label> <a href="javascript:show_hide('f1_h2a');show_hide('f1_h2b');show_hide('f1_h2c');">
				 <img src='./template/admin/images/info.gif' border='0' alt='[Info]' /></a>
  </td>
  <td class='field_fixed'>
    <input type='text' id="author" name='author' value="{$res['username']}" size='40' class='textbox' /> {$res['usericon']}
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
  <td class='title_fixed' valign='top'>
    <label for="author_override">Username Override</label>
  </td>
  <td class='field_fixed'>
    <input type='text' id='author_override' name='author_override' value="{$res['author_override']}" size='40' class='textbox' />
  </td>
</tr>
<tr>
  <td class='title_fixed' valign='top'>
    <label for="website_override">Website Override</label>
  </td>
  <td class='field_fixed'>
    <input type='text' id='website_override' name='website_override' value="{$res['website_override']}" size='40' class='textbox' /> {$res['website']}
  </td>
</tr>
<tr>
  <td class='title_fixed' valign='top'>
    <label for="weburl_override">Website URL Override</label>
  </td>
  <td class='field_fixed'>
    <input type='text' id='weburl_override' name='weburl_override' value="{$res['weburl_override']}" size='40' class='textbox' /> {$res['weburl']}
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed' valign='top'>
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
  <td class='title_fixed' valign='top'>
    Date Submitted
  </td>
  <td class='field_fixed'>
    {$res['created']}
  </td>
</tr>
<tr>
  <td class='title_fixed' valign='top'>
    Last Updated
  </td>
  <td class='field_fixed'>
    {$res['updated']}
  </td>
</tr>
<tr>
  <td class='title_fixed' valign='top'>
    Total Views
  </td>
  <td class='field_fixed'>
    {$res['views']}
  </td>
</tr>
<tr>
  <td class='title_fixed' valign='top'>
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
  <td class='title_fixed' valign='top'>
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
  <td valign='top' class='title_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray;'>
    Upload File
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-top: 1px solid gray;'>
    <input type='file' name='file' class='textbox' size='40' /> -OR-
  </td>
</tr>
<tr id='f1_3c' style='display:none'>
  <td valign='top' class='title_fixed' style='background-color: #BDC5EB; border-bottom: 1px solid gray;'>
    Specify Filename
  </td>
  <td class='field_fixed' style='background-color: #BDC5EB; border-bottom: 1px solid gray;'>
    <input type='text' name='file_name' value='' size='40' class='textbox' />
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td style='border-bottom:1px solid #666666; font-size:14pt'>&#8212;&#8212; Part 3</td>
  <td style='border-bottom:1px solid #666666' valign='bottom'><b>Commit Changes</b></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
HTML;
}

}