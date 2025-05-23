<?php

class template_user {

function userpage ($user) {
global $STD;
return <<<HTML
<div class="sform">
&nbsp;
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td style="width:50%; margin:auto;">
    <div class="sform" style="width:90%">
    <div class="sformstrip" style="text-align:center"><span class="highlight">Contact/Social Media</span></div>
    <table class="sformtable">
      <tr>
        <td class="sformleft">PM</td>
        <td class="sformright">
          [<a href="{$STD->tags['root_url']}act=msg&amp;param=05&amp;uid={$user['uid']}">Send Message</a>]</td>
      </tr>
      <tr>
        <td class="sformleft">Email</td>
        <td class="sformirght">{$user['email']}</td>
      </tr>
	  <tr>
        <td class="sformleft">Discord</td>
        <td class="sformright">{$user['discord']}</td>
      </tr>
	  <tr>
        <td class="sformleft">Twitter</td>
        <td class="sformright">{$user['twitter']}</td>
      </tr>
	  <tr>
        <td class="sformleft">YouTube</td>
        <td class="sformright">{$user['youtube']}</td>
      </tr>
	  <tr>
        <td class="sformleft">Twitch</td>
        <td class="sformright">{$user['twitch']}</td>
      </tr>
	  <tr>
        <td class="sformleft">Reddit</td>
        <td class="sformright">{$user['reddit']}</td>
      </tr>
	  <tr>
        <td class="sformleft">Steam</td>
        <td class="sformright">{$user['steam']}</td>
      </tr>
	  <tr>
        <td class="sformleft">Bluesky</td>
        <td class="sformright">{$user['bluesky']}</td>
      </tr>
    </table>
   </div>
    &nbsp;
  </td>
  <td>
    <div class="sform" style="width:90%">
    <div class="sformstrip" style="text-align:center"><span class="highlight">Profile</span></div>
    <table class="sformtable" style="border-spacing:1px">
	  <tr>
        <td class="sformleft" style="vertical-align: top;">Icon</td>
        <td class="sformright">
	      <div style="display: table-cell; width: 80px; height: 80px; vertical-align: middle; text-align: center;">{$user['icon']}</div>
		</td>
	  </tr>
      <tr>
        <td class="sformleft">User ID</td>
        <td class="sformright"><b>{$user['uid']}</b></td>
      </tr>
	  <tr>
        <td class="sformleft">Rank</td>
        <td class="sformright">{$user['group_title']}</td>
      </tr>
      <tr>
        <td class="sformleft">Join Date</td>
        <td class="sformright">{$user['join_date']}</td>
      </tr>
      <tr>
        <td class="sformleft">Website</td>
        <td class="sformright">{$user['website']}</td>
      </tr>
      <tr>
        <td class="sformleft">Submissions</td>
        <td class="sformright"><b>{$user['submissions']}</b> - <a href="{$STD->tags['root_url']}act=user&amp;param=09&uid={$user['uid']}" title="View Submissions">View Submissions</a></td>
      </tr>
      <tr>
        <td class="sformleft">Comments</td>
        <td class="sformright"><b>{$user['comments']}</b> - <a href="{$STD->tags['root_url']}act=user&amp;param=10&uid={$user['uid']}" title="View Comments">View Comments</a></td>
      </tr>
    </table>
    </div>
    &nbsp;
  </td>
</tr>
</table>
</div>
HTML;
}

function queue_text () {
global $STD;
return <<<HTML
<span class="queued"> (QUEUED)</span>
HTML;
}

function get_user_icon ($input) {
	global $CFG;
	if (empty($input))
	{
		return "<img id='uavatar' src='{$CFG['default_icon']}' alt='No Icon' class='avatar'>";
	}
	
	$img_final = preg_replace("/^http:/i", "https:", $input);
	
	return "<img id='uavatar' src='{$img_final}' alt='User Icon' class='avatar' onerror='this.src=".'"'.$CFG['default_icon_defective'].'";'."'>";
}

function prefs_page ($user, $form_elements, $token) {
global $STD;
$myicon = $this->get_user_icon($user['icon']);
return <<<HTML
<form method="post" action="{$STD->tags['root_url']}act=user&amp;param=04">
<input type="hidden" name="security_token" value="{$token}">
<input type="hidden" name="uid" value="{$user['uid']}">
<div class="sform">
<div class="sformstrip">Account Information</div>
<table class="sformtable">
<tr>
  <td class="sformleft">Username</td>
  <td class="sformright"><b>{$user['username']}</b> (<a href="{$STD->tags['root_url']}act=main&param=15">request a name change</a>)</td>
</tr>
<tr>
  <td class="sformleft">User ID</td>
  <td class="sformright"><b>{$user['uid']}</b></td>
</tr>
</table>
<div class="sformstrip">Profile Information</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="email">Email Address</label></td>
  <td class="sformright"><input type="text" size="40" name="email" id="email" value="{$user['email']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="website">Website</label></td>
  <td class="sformright"><input type="text" size="40" name="website" id="website" value="{$user['website']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="weburl">Website URL</label></td>
  <td class="sformright"><input type="text" size="40" name="weburl" id="weburl" value="{$user['weburl']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="icon">Avatar</label><br><span class="subtext">Image URL must be in HTTPS! Avoid unstable services like DeviantArt, Discord CDN, ImageShack, or Imgur.</span></td>
  <td class="sformright"><input type="text" size="40" name="icon" id="icon" value="{$user['icon']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft">Icon Dimensions<br><span class="subtext">Maximum dimensions: {$form_elements['max_dims']}</span></td>
  <td class="sformright"><input type="text" size="4" name="dimw" title="Icon Width" value="{$user['icon_dimw']}" class="textbox"> x <input type="text" size="4" name="dimh" title="Icon Height" value="{$user['icon_dimh']}" class="textbox"></td>
</tr>

<script> function test () {
	var iurl = document.getElementById('number').value;
	document.getElementById('uavatar').src = iurl;
	}
</script>
<tr>
  <td class="sformleft" style="vertical-align: top;">Icon Preview<br><span class="subtext">Refreshing the icon is just refreshes the preview, you need to submit your changes from to save it to your profile.</span></td>
  <td class="sformright">
	<span class="subtext">Result:</span><br>
	<div style="display: table-cell;"><div style="display: table-cell; width: 80px; height: 80px; vertical-align: middle; text-align: center;">{$myicon}</div></div>
	<div style="margin-top: 4px;"><button type="button" class="button"  onclick="var iurl = document.getElementById('icon').value; /*alert('URL = '+iurl);*/ document.getElementById('uavatar').src = iurl;">Refresh Icon</button></div>
  </td>
</tr>
<tr>
  <td class="sformleft">Show Email</td>
  <td class="sformright">{$form_elements['show_email']}</td>
</tr>
</table>
<div class="sformstrip">Social Media</div>
<table class="sformtable" style="border-spacing:1px">
<tr>
  <td class="sformleft"><label for="discord">Discord ID</label><br><span class="subtext">Recommended format: username</span></td>
  <td class="sformright"><input type="text" size="40" name="discord" id="discord" value="{$user['discord']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="twitter">Twitter ID</label><br><span class="subtext">Do not put a URL or include the @ in this field!</span></td>
  <td class="sformright"><input type="text" size="40" name="twitter" id="twitter" value="{$user['twitter']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="bluesky">Bluesky ID</label><br><span class="subtext">Do not put a URL or include the @ in this field!</span></td>
  <td class="sformright"><input type="text" size="40" name="bluesky" id="bluesky" value="{$user['bluesky']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="youtube">YouTube Channel URL</label><br><span class="subtext">Only post the URL to your YouTube channel!</span></td>
  <td class="sformright"><input type="text" size="40" name="youtube" id="youtube" value="{$user['youtube']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="twitch">Twitch ID</label><br><span class="subtext">Do not put a URL to your Twitch profile in this field!</span></td>
  <td class="sformright"><input type="text" size="40" name="twitch" id="twitch" value="{$user['twitch']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="reddit">Reddit ID</label><br><span class="subtext">Do not put a URL or include the /u/ in this field!</span></td>
  <td class="sformright"><input type="text" size="40" name="reddit" id="reddit" value="{$user['reddit']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="steam">Steam ID</label><br><span class="subtext">Do not post URL to your Steam profile!</span></td>
  <td class="sformright"><input type="text" size="40" name="steam" id="steam" value="{$user['steam']}" class="textbox"></td>
</tr>
<!-- <tr>
  <td class="sformleft">AIM ID</td>
  <td class="sformright"><input type="text" size="40" name="aim" value="{$user['aim']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft">MSN ID</td>
  <td class="sformright"><input type="text" size="40" name="msn" value="{$user['msn']}" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft">YIM ID</td>
  <td class="sformright"><input type="text" size="40" name="yim" value="{$user['yim']}" class="textbox"></td>
</tr> Obsolete -->
</table>
<div class="sformstrip">Time Settings</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Time Zone</td>
  <td class="sformright">{$form_elements['timezone']}</td>
</tr>
<tr>
  <td class="sformleft">Daylight Savings</td>
  <td class="sformright">{$form_elements['dst']} Check box if Daylight Savings Time is in effect</td>
</tr>
<tr>
  <td class="sformleft">Current Time</td>
  <td class="sformright"><span class="highlight">{$form_elements['time']}</span></td>
</tr>
</table>
<div class="sformstrip">Browsing Defaults</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Default Order</td>
  <td class="sformright">{$form_elements['order_by']} {$form_elements['order']}</td>
</tr>
<tr>
  <td class="sformleft">Skin</td>
  <td class="sformright">{$form_elements['skin']}</td>
</tr>
<tr>
  <td class="sformleft">Items Per Page</td>
  <td class="sformright">{$form_elements['items']}</td>
</tr>
<tr>
  <td class="sformleft">Display Thumbnails</td>
  <td class="sformright">{$form_elements['show_thumbs']}</td>
</tr>
</table>
<div class="sformstrip">Message Settings</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Send messages when I receive comments on my submissions</td>
  <td class="sformright">{$form_elements['use_comment_msg']}</td>
</tr>
<tr>
  <td class="sformleft">Combine messages into single digest for comments received while offline</td>
  <td class="sformright">{$form_elements['use_comment_digest']}</td>
</tr>
</table>
<div class="sformstrip">Change Password. &nbsp;Leave blank to keep existing password.</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft"><label for="opass">Old Password</label></td>
  <td class="sformright"><input type="password" size="40" name="opass" id="opass" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="npass1">New Password (8 characters, at least 1 letter and 1 number)</label></td>
  <td class="sformright"><input type="password" size="40" name="npass1" id="npass1" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="npass2">Retype Password</label></td>
  <td class="sformright"><input type="password" size="40" name="npass2" id="npass2" class="textbox"></td>
</tr>
</table>
<div class="sformstrip" style="text-align:center"><input type="submit" value="Submit Changes" class="button"></div>
</div>
</form>
HTML;
}

function public_type_row ($select, $uid) {
global $STD;
return <<<HTML
<div class="sform">
<form method="post" name="changetype" action="{$STD->tags['root_url']}act=user&param=09&uid={$uid}">
<div class="sformstrip">
Currently Viewing: {$select} <input type="submit" value="Change" class="button" />
</div>
</form>
HTML;
}

function manage_type_row ($select) {
global $STD;
return <<<HTML
<div class="sform">
<form method="post" name="changetype" action="{$STD->tags['root_url']}act=user&param=03">
<div class="sformstrip">
Currently Viewing: {$select} <input type="submit" value="Change" class="button" />
</div>
</form>
HTML;
}

function manage_start_rows () {
global $STD;
return <<<HTML
<table class="sformtable" style="border-spacing:1px;">
HTML;
}

function manage_end_rows ($pages, $order, $order_url) {
global $STD;
return <<<HTML
<tr>
  <td class="sformtitle" colspan="2">
  <form method="post" action="{$order_url}">
  <input type="submit" name="reorder" value="Re-Order" class="button">
  {$order}
  </form>
</td></tr>
</table>
<div class="sformstrip">Pages: {$pages}</div>
</div>
HTML;
}

function request_remove ($rid, $submission, $form_url, $reason) {
global $STD;
return <<<HTML
<div class="sform">
<form method="post" action="{$form_url}">
<input type="hidden" name="rid" value="{$rid}" />
<div class="sformstrip">Request Submission Removal</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Submission</td>
  <td class="sformright">{$submission}</td>
</tr>
<tr>
  <td class="sformleft">Reason</td>
  <td class="sformright"><textarea name="reason" cols="40" rows="6" class="textbox">{$reason}</textarea></td>
</tr>
</table>
<div class="sformstrip" style="text-align:center"><input type="submit" value="Submit Request" class="button" /></div>
</div>
HTML;
}

		function usercomment ($user, $nn = 0, $page = 1, $pages = 1) {
		global $STD;
		$output = '';
		$pmarker = '';
		
		//pages marker
		if ($pages > 1)
		{
			$pagination = '';
			for ($j = 1; $j <= $pages; $j ++)
			{
				$pstyle = "font-weight:normal;";
				if ($page == $j)
				{
					$pstyle = "font-weight:bold;";
				}
				$PURL = $STD->encode_url($_SERVER['PHP_SELF']."?act=user&param=10&uid=".$user[0]['uid']."&page=".$j);
				$pagination .= '<a href="'.$PURL.'" style="text-decoration:underline; display: inline-block; '.$pstyle.'">'.$j.'</a>&nbsp;&nbsp;';
			}
			$pmarker = '<div class="sformstrip">
							Pages: <span style="font-weight:normal">('.$pages.')</span>&nbsp;&nbsp;'.$pagination.'
						</div>
						<div class="sformdark" style="height: 6px;"></div>';
		}
		
		//build all of the comments
		if ($nn == 0)
		{
			$output .= '<td class="sformstrip" style="height:25px;text-align:center;">No comments have been made by this user.</td>';
		}
		for ($i = 0; $i < $nn; $i ++)
			{
				if ($user[$i]['type'] == 1)
				{
					$URL = $STD->encode_url($_SERVER['PHP_SELF']."?act=resdb&param=2&c=".$user[$i]['rt']."&id=".$user[$i]['rid']."#c".$user[$i]['cid']);
				}
				else 
				{
					$URL = $STD->encode_url($_SERVER['PHP_SELF']."?act=main&param=2&id=".$user[$i]['rid']."#c".$user[$i]['cid']);
				}
				$output .= '<tr>
								<td style="width:55%;" class="sformstrip">
									<b>'.$STD->make_date_time($user[$i]['date']).'</b>
								</td>
								<td class="sformstrip" style="text-align:right;padding:2px">
									<a href="'.$URL.'"> See Page </a>
								</td>
							</tr>
							<tr>
								<td class="sformblock" style="width:100%;" colspan="2">'.$user[$i]['message'].'</td>
							</tr>
							<tr>
								<td style="height:6px;" colspan="2" class="sformdark"></td>
							</tr>
							';
			}
		return '<div class="sform">'.$pmarker.'<table class="sformtable" style="border-spacing:1px;"><tbody>'.$output.'</tbody></table>'.$pmarker.'</div>';
	}

}