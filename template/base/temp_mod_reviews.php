<?php

class template_mod_reviews {

function resdb_page ($res) {
global $STD;

//If the replay field isn't empty then show it
$replay = "";

if ($res['replay'] != "") {
  $replay = "<tr>
  <td class='sformleft' style='width: 10% !important;'><div style='text-align:center'>Replay<br>
    <b>".$res['replay_score']." / 10</b></div></td>
  <td class='sformright'>".$res['replay']."<br>&nbsp;</td>
  </tr>";
}

return <<<HTML
<div class="sform">
<div class="sformstrip">Review Information</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Game Reviewed</td>
  <td class="sformright">{$res['game_title']}, by {$res['game_author']}</td>
</tr>
<tr>
  <td class="sformleft">Review Author</td>
  <td class="sformright">{$res['author']}</td>
</tr>
<tr>
  <td class="sformleft">Created</td>
  <td class="sformright">{$res['created']}</td>
</tr>
</table>
</div>
<br>
<div class="sform">
<div class="sformstrip">General Commentary and Game Overview</div>
<div class="sformblock">{$res['commentary']}<br>&nbsp;</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft" style="width: 10% !important;">Pros</td>
  <td class="sformright">{$res['pros']}<br>&nbsp;</td>
</tr>
<tr>
  <td class="sformleft" style="width: 10% !important;">Cons</td>
  <td class="sformright">{$res['cons']}<br>&nbsp;</td>
</tr>
</table>
<div class="sformstrip">Impressions</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft" style="width: 10% !important;"><div style='text-align:center'>Gameplay<br>
    <b>{$res['gameplay_score']} / 10</b></div></td>
  <td class="sformright">{$res['gameplay']}<br>&nbsp;</td>
</tr>
<tr>
  <td class="sformleft" style="width: 10% !important;"><div style='text-align:center'>Graphics<br>
    <b>{$res['graphics_score']} / 10</b></div></td>
  <td class="sformright">{$res['graphics']}<br>&nbsp;</td>
</tr>
<tr>
  <td class="sformleft" style="width: 10% !important;"><div style='text-align:center'>Sound<br>
    <b>{$res['sound_score']} / 10</b></div></td>
  <td class="sformright">{$res['sound']}<br>&nbsp;</td>
</tr>
{$replay}

</table>
<div class="sformstrip">Final Words</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft" style="width: 10% !important;"><div style='text-align:center'>{$res['score']}</div></td>
  <td class="sformright">{$res['description']}</td>
</tr>
</table>
</div>
<div style="padding-top:4px">
<img src="{$STD->tags['global_image_path']}/report.gif" alt="[!]" style="display:inline; vertical-align:middle">
<a href="{$res['report_url']}" style="vertical-align: middle" class="outlink">Report This Submission</a>
</div>
<br>
HTML;
}

function public_row ($res, $cat) {
global $STD;
return <<<HTML
<tr>
  <td class="sformlowline" style="padding:0px;text-align:left">
    <table style="border-spacing:0px;width:100%;height:100%;">
      <tr>
        <td style="width:60%;padding:2px;" class="sformsubstrip">
          <span style="display:inline; vertical-align:middle;">
          <a href="{$STD->tags['root_url']}act=resdb&amp;param=02&amp;c={$cat}&amp;id={$res['rid']}">
          <b>{$res['title']}</b></a></span>
        </td>
        <td style="width:25%;padding:2px;" class="sformstrip">
	      By: <b>{$res['author']}</b>
        </td>
        <td class="sformstrip" style="width:15%;text-align:right;padding:2px">
          {$res['email_icon']} {$res['website_icon']}
        </td>
      </tr>
      <tr>
        <td style="width:100%;height:50px;padding:2px;" colspan="3">
           {$res['description']}
        </td>
      </tr>
      <tr>
        <td style="vertical-align:bottom;padding:2px;">
          Score: <b>{$res['score']} / 10</b>
        </td>
        <td style="vertical-align:bottom;width:100%;" colspan="2">
          <table style="border-spacing:0px;width:100%;">
            <tr>
              <td style="width:50%;font-size:8pt;">
                Added: {$res['created']}
              </td>
              <td style="width:50%;font-size:8pt;">
                {$res['updated']}
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
HTML;
}

function manage_row ($res, $cat) {
global $STD;
return <<<HTML
<tr>
  <td class="sformlowline" style="padding:0px;text-align:left;">
    <table style="border-spacing:0px;width:100%;height:100%;">
      <tr>
        <td style="width:60%;padding:2px;" class="sformsubstrip">
          {$res['page_icon']}
          <span style="display:inline; vertical-align:middle">
          <a href="{$STD->tags['root_url']}act=user&amp;param=06&amp;c={$cat}&amp;rid={$res['rid']}">
          <b>{$res['title']}</b></a></span>
        </td>
        <td style="width:25%;padding:2px;" class="sformstrip">
	      By: <b>{$res['author']}</b>
        </td>
        <td class="sformstrip" style="width:15%;text-align:right;padding:2px">
          {$res['email_icon']} {$res['website_icon']}
        </td>
      </tr>
      <tr>
        <td style="width:100%;height:50px;padding:2px;" colspan="3">
           {$res['description']}
        </td>
      </tr>
      <tr>
        <td style="vertical-align:bottom;padding:2px;">
          Score: <b>{$res['score']} / 10</b>
        </td>
        <td style="vertical-align:bottom;width:100%;" colspan="2">
          <table style="border-spacing:0px;width:100%;">
            <tr>
              <td style="width:50%;font-size:8pt;">
                Added: {$res['created']}
              </td>
              <td style="width:50%;font-size:8pt;">
                {$res['updated']}
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
HTML;
}

function manage_page ($res, $token, $max_size) {
global $STD;
return <<<HTML
<div class="sform">
<form method="post" action="{$STD->tags['root_url']}act=user&amp;param=07" enctype="multipart/form-data">
<input type="hidden" name="security_token" value="{$token}">
<input type="hidden" name="c" value="{$res['type']}">
<input type="hidden" name="rid" value="{$res['rid']}">
<input type="hidden" name="gid" value="{$res['gid']}">
<div class="sformstrip">Information about your submission.  These values cannot be changed.</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Type</td>
  <td class="sformright">{$res['type_name']}</td>
</tr>
<tr>
  <td class="sformleft">Date Submitted</td>
  <td class="sformright">{$res['created']}</td>
</tr>
<tr>
  <td class="sformleft">Last Updated</td>
  <td class="sformright">{$res['updated']}</td>
</tr>
<tr>
  <td class="sformleft">Number of Views</td>
  <td class="sformright">{$res['views']}</td>
</tr>
</table>
<div class="sformstrip">Game under review</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Title</td>
  <td class="sformright">{$res['game_title']} (ID #{$res['gid']})</td>
</tr>
<tr>
  <td class="sformleft">Author</td>
  <td class="sformright">{$res['game_author']}</td>
</tr>
</table>
<div class="sformstrip">General Commentary and Game Overview</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="commentary">Commentary and Overview</label></td>
  <td class="sformright"><textarea id="commentary" name="commentary" cols="50" rows="12" class="textbox">{$res['commentary']}</textarea></td>
</tr>
</table>
<div class="sformstrip">Pros and Cons</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="pros">Pros</label></td>
  <td class="sformright"><textarea id="pros" name="pros" rows="8" cols="50" class="textbox">{$res['pros']}</textarea></td>
</tr>
<tr>
  <td class="sformleft"><label for="cons">Cons</label></td>
  <td class="sformright"><textarea id="cons" name="cons" rows="8" cols="50" class="textbox">{$res['cons']}</textarea></td>
</tr>
</table>
<div class="sformstrip">Final Impressions and Scoring</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="gameplay">Gameplay</label></td>
  <td class="sformright"><textarea id="gameplay" name="gameplay" rows="8" cols="50" class="textbox">{$res['gameplay']}</textarea><br>
  	Score: {$res['gameplay_score']}</td>
</tr>
<tr>
  <td class="sformleft"><label for="graphics">Graphics</label></td>
  <td class="sformright"><textarea id="graphics" name="graphics" rows="8" cols="50" class="textbox">{$res['graphics']}</textarea><br>
    Score: {$res['graphics_score']}</td>
</tr>
<tr>
  <td class="sformleft"><label for="sound">Sound</label></td>
  <td class="sformright"><textarea id="sound" name="sound" rows="6" cols="50" class="textbox">{$res['sound']}</textarea><br>
    Score: {$res['sound_score']}</td>
</tr>
</table>
<div class="sformstrip"><label for="description">Final Words and Overall Score</label></div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Final Words (be concise)</td>
  <td class="sformright"><textarea id="description" name="description" rows="4" cols="50" class="textbox">{$res['description']}</textarea><br>
    Score: {$res['score']}</td>
</tr>
</table>
<div class="sformstrip">Short description of this update</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="reason">Reason</label></td>
  <td class="sformright"><textarea id="reason" name="reason" rows="4" cols="40" class="textbox"></textarea>
</tr>
</table>
<div class="sformstrip" style="text-align: center">
  <input type="submit" value="Update Submission" class="button">
  <input type="submit" name="rem" value="Request Removal" class="button">
</div>
</form>
</div>
HTML;
}

function submit_form ($res, $max_size) {
global $STD;
return <<<HTML
<div class="sformstrip">Game under review</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Title</td>
  <td class="sformright">{$res['game_title']} (ID #{$res['gid']})
    <input type="hidden" name="gid" value="{$res['gid']}"></td>
</tr>
<tr>
  <td class="sformleft">Author</td>
  <td class="sformright">{$res['game_author']}</td>
</tr>
</table>
<div class="sformstrip">General Commentary and Game Overview</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="commentary">Commentary and Overview</label></td>
  <td class="sformright"><textarea id="commentary" name="commentary" cols="50" rows="12" class="textbox">{$res['commentary']}</textarea></td>
</tr>
</table>
<div class="sformstrip">Pros and Cons</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="pros">Pros</label></td>
  <td class="sformright"><textarea id="pros" name="pros" rows="8" cols="50" class="textbox">{$res['pros']}</textarea></td>
</tr>
<tr>
  <td class="sformleft"><label for="cons">Cons</label></td>
  <td class="sformright"><textarea id="cons" name="cons" rows="8" cols="50" class="textbox">{$res['cons']}</textarea></td>
</tr>
</table>
<div class="sformstrip">Final Impressions and Scoring</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="gameplay">Gameplay</label></td>
  <td class="sformright"><textarea id="gameplay" name="gameplay" rows="8" cols="50" class="textbox">{$res['gameplay']}</textarea><br>
  	Score: {$res['gameplay_score']}</td>
</tr>
<tr>
  <td class="sformleft"><label for="graphics">Graphics</label></td>
  <td class="sformright"><textarea id="graphics" name="graphics" rows="8" cols="50" class="textbox">{$res['graphics']}</textarea><br>
    Score: {$res['graphics_score']}</td>
</tr>
<tr>
  <td class="sformleft"><label for="sound">Sound</label></td>
  <td class="sformright"><textarea id="sound" name="sound" rows="6" cols="50" class="textbox">{$res['sound']}</textarea><br>
    Score: {$res['sound_score']}</td>
</tr>
</table>
<div class="sformstrip">Final Words and Overall Score</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="description">Final Words (be concise)</label></td>
  <td class="sformright"><textarea id="description" name="description" rows="4" cols="50" class="textbox">{$res['description']}</textarea><br>
    Score: {$res['score']}</td>
</tr>
</table>
HTML;
}

}