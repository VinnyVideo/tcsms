<?php

class template_acp_mod_reviews {

function acp_edit_form ($res) {
global $STD;
return <<<HTML
<tr>
  <td style='border-bottom:1px solid #666666; font-size:14pt;'>&#8212;&#8212; Part 2</td>
  <td style='border-bottom:1px solid #666666;vertical-align:bottom;'><b>Base Data and Information</b></td>
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
    <input type='text' id="website_override" name='website_override' value="{$res['website_override']}" size='40' class='textbox'> {$res['website']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for='weburl_override'>Website URL Override</label>
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
    Game Associated
  </td>
  <td class='field_fixed'>
    {$res['game_title']} (ID #{$res['gid']})
    <input type="hidden" name="gid" value="{$res['gid']}">
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="commentary">Commentary</label>
  </td>
  <td class='field_fixed'>
    <textarea id="commentary" name="commentary" cols="50" rows="12">{$res['commentary']}</textarea>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="pros">Pros</label>
  </td>
  <td class='field_fixed'>
    <textarea id="pros" name="pros" cols="50" rows="8">{$res['pros']}</textarea>
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="cons">Cons</label>
  </td>
  <td class='field_fixed'>
    <textarea id="cons" name="cons" rows="8" cols="50">{$res['cons']}</textarea>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="gameplay">Gameplay</label>
  </td>
  <td class='field_fixed'>
    <textarea id="gameplay" name="gameplay" rows="12" cols="50">{$res['gameplay']}</textarea><br>
  	Score: {$res['gameplay_score']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
   <label for="graphics">Graphics</label>
  </td>
  <td class='field_fixed'>
    <textarea id="graphics" name="graphics" rows="10" cols="50">{$res['graphics']}</textarea><br>
    Score: {$res['graphics_score']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="sound">Sound</label>
  </td>
  <td class='field_fixed'>
    <textarea id="sound" name="sound" rows="6" cols="50">{$res['sound']}</textarea><br>
    Score: {$res['sound_score']}
  </td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="replay">Replay</label>
  </td>
  <td class='field_fixed'>
    <textarea id="replay" name="replay" rows="6" cols="50">{$res['replay']}</textarea><br>
    Score: {$res['replay_score']}
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class='title_fixed'>
    <label for="description">Final Words</label>
  </td>
  <td class='field_fixed'>
    <textarea rows='6' cols='50' id='description' name='description' class='textbox'>{$res['description']}</textarea><br>
    Score: {$res['score']}
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
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td style='border-bottom:1px solid #666666; font-size:14pt'>&#8212;&#8212; Part 3</td>
  <td style='border-bottom:1px solid #666666;vertical-align:bottom;'><b>Commit Changes</b></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
HTML;
}

}
?>