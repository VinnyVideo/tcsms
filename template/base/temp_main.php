<?php

class template_main {

function news_header () {
global $STD;
return <<<HTML
HTML;
}

function news_archive_header ($from, $to) {
global $STD;
return <<<HTML
<div class="sform">
  <form method="post" action="{$STD->tags['root_url']}act=main&amp;param=08">
    <div class="sformstrip">Select a range of updates</div>
      <table class="sformtable" style="border-spacing:1px;">
        <tr>
          <td class="sformleftw" style="width:50%; font-weight:bold;">From: &nbsp; {$from['m']} {$from['d']} {$from['y']}</td>
          <td class="sformleftw" style="width:50%; font-weight:bold;">To: &nbsp; {$to['m']} {$to['d']} {$to['y']}</td>
        </tr>
      </table>
    <div class="sformstrip" style="text-align:center;"><input type="submit" value="Go" class="button"></div>
  </form>
</div>
<br>
HTML;
}

function news_footer () {
global $STD;
return <<<HTML
HTML;
}

function news_row ($news) {
global $STD;
return <<<HTML
<div class="sform">
  <table class="sformtable" style="border-spacing:0px;">
    <tr class="sformstrip">
      <td style="height:25px;width:150px;padding:3px;text-align:center;">
        <b class="highlight stafflink">{$news['author']}</b>
      </td>
      <td style="height:25px;padding:3px;">
        <div style="font-weight:bold; font-size: 14pt; color:#FFF600;">{$news['title']}</div>
        <div style="padding-top:4px; text-align:left; font-weight:normal; font-style:italic; font-size: 8pt; color:#CDCFFE;">{$news['date']}</div>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;padding:3px;"><br>{$news['icon']}</td>
      <td colspan="2">
        {$news['message']}<br>&nbsp;
      </td>
    </tr>
    <tr>
      <td class="topstrip" style="padding:3px;"></td>
      <td class="topstrip" colspan="2" style="padding:3px;">
        <a href="{$STD->tags['root_url']}act=main&amp;param=02&amp;id={$news['nid']}">View Comments ({$news['comments']})</a> | 
        <a href="{$STD->tags['root_url']}act=main&amp;param=02&amp;id={$news['nid']}&amp;exp=1#reply">Leave Comment</a>
      </td>
    </tr>
    <tr>
      <td style="height:6px;padding:3px;" colspan="3" class="sformdark"></td>
    </tr>
  </table>
</div>
<br>
HTML;
}

function news_update_header () {
global $STD;
return <<<HTML
<span class='highlight'><b>Recent Additions</b></span>
<br>
<div class='newsform'>
HTML;
}

function news_update_footer () {
global $STD;
return <<<HTML
</div>
HTML;
}

function news_no_updates () {
global $STD;
return <<<HTML
<table class='sformtable' style='border-spacing:1px;'><tr>
  <td class='newsstrip' style='height:25px;text-align:center;'>No recent additions since last update.</td>
</tr></table>
HTML;
}

function news_gen_mod_header ($name) {
global $STD;
return <<<HTML
<div class='newsstrip'>{$name}</div>
HTML;
}

function news_gen_mod_footer () {
global $STD;
return <<<HTML
HTML;
}

function news_gen_block_header ($name) {
global $STD;
return <<<HTML
<table class='sformtable' style='border-spacing:1px;'>
HTML;
}

function news_gen_block_header_col ($name, $id) {
global $STD;
return <<<HTML
<div class="newssubstrip" style="text-align: center">
  <a href="javascript:show_hide('$id');" style="text-decoration:underline" class="outlink">Click to see newly added $name</a></div>
<table id="$id" class='sformtable' style='display:none;border-spacing:1px;'>
HTML;
}

function news_gen_block_footer () {
global $STD;
return <<<HTML
</table>
HTML;
}

function news_gen_updblock_header ($name) {
global $STD;
return <<<HTML
<div class='newssubstrip'>Updated $name</div>
<table class='sformtable' style='border-spacing:1px;'>
HTML;
}

function news_gen_updblock_header_col ($name, $id) {
global $STD;
return <<<HTML
<div class="newssubstrip" style="text-align: center">
  <a href="javascript:show_hide('$id');" style="text-decoration:underline" class="outlink">Click to see updated $name</a></div>
<table id="$id" class='sformtable' style='display:none;border-spacing:1px;'>
HTML;
}

function news_gen_updblock_footer () {
global $STD;
return <<<HTML
</table>
HTML;
}

function news_gen_block_row ($res) {
global $STD;
return <<<HTML
<tr>
  <td class='newsleftw'><a href='{$res['url']}'><b>{$res['title']}</b></a></td>
  <td class='newsleftw' style="width:30%;">By {$res['username']}</td>
</tr>
HTML;
}

function comments_header () {
global $STD;
return <<<HTML
<script>
<!--
  function check_delete () {
  	form_check = confirm('Are you sure you want to delete this comment?');
  	
  	if (form_check == true) {
  		return true;
  	} else {
  		return false;
  	}
  }
-->
</script>
<table style="width:95%;">
    <tr>
      <td class="tablecell1" colspan="1">
        <span class="boxheader">Comments</span>
      </td>
    </tr>
</table>
<div class="sform" id="comments">
<table class="sformtable" style="border-spacing:0px;table-layout: fixed;">
HTML;
}

function comments_footer ($pages, $url) {
global $STD;
return <<<HTML
</table>
<div class="sformstrip">
    Pages: {$pages} <span style='font-weight:normal'>| <a href='$url&amp;st=new'>Last Unread</a></span>
</div>
</div>
<br>
HTML;
}

function comments_add ($comment_url, $aexpand) {
global $STD;
return <<<HTML
<div style="text-align:right">
  <span style="font-size:14pt"><a id="reply" href="javascript:show_hide('addc');">Add Comment</a></span>
</div>
<br>
<div style="text-align: center; margin-left: auto; margin-right: auto;">
</div>
<br>
<div class="sform" id="addc" style="$aexpand">
  <form method="post" action="{$comment_url}">
  <table class="sformtable" style="border-spacing:0px;">
    <tr>
      <td style="text-align:center;padding:2px;">
        <br>
        <textarea name="message" title="Enter your comment here" cols="55" rows="12" class="textbox"></textarea>
        <br>
        <input type="submit" value="Add Comment" class="button">
        <br>&nbsp;
      </td>
    </tr>
  </table>
  </form>
</div>
<br>
HTML;
}

function comments_none () {
global $STD;
return <<<HTML
<tr>
<td class="sformstrip" style="height:25px;text-align:center">No comments have been left.</td>
</tr>
HTML;
}

function comments_row ($comment) {
global $STD;

//Like button mechanism
$like_button = "";
$user_id = 0;
$l_sep = '';
$ctag = '';
if ($STD->user['can_comment'])
{
	$like_button = "<a href='{$comment['like_url']}'>Like</a>";
	if (intval($comment['lcount']) > 0)
	{
		$l_sep = ' | ';
	}
}
for ($j = 0; $j < intval($comment['lcount']); $j ++)
{
	$user_id = $STD->user['uid'];
	if (intval($user_id) == intval($comment['luid'][$j]))
	{
		$like_button = "<a href='{$comment['unlike_url']}'>Unlike</a> "; //.intval($user_id)
		$l_sep = ' | ';
	}
	$user_id = 0;
}

$user_id = $STD->user['uid'];
if (intval($user_id) == intval($comment['uid']))
{
	$like_button = "";
	$l_sep = '';
}


if (($like_button != "") || (intval($comment['lcount']) > 0))
{
	$ctag = 'sformsubstrip';
}

if (!isset($comment['edit_header']))
{
	$comment['edit_header'] = '';
}

return <<<HTML
<tr>
  <td class="sformstrip" style="width:80px;{$comment['edit_header']}">
    <span id="c{$comment['cid']}"></span><div style="width: 80px; height: 80px;">{$comment['avatar']}</div>
  </td>
  <td class="sformstrip" style="background-position:right top;">
  <b class="highlight">{$comment['author']}</b><br>
    {$comment['date']}
  </td>
  <td class="sformstrip" style="text-align:right; margin:4px;">
    <span style="vertical-align:middle">{$comment['report_icon']}</span>
    <span style="vertical-align:middle">{$comment['delete_icon']}</span>
    <span style="vertical-align:middle">{$comment['edit_icon']}</span>
    <span style="vertical-align:middle">{$comment['quote_icon']}</span>
  </td>
</tr>
<tr>
  <td class="sformblock" colspan="3">
    {$comment['message']}
    <br>&nbsp;
  </td>
</tr>
<tr>
  <td class="{$ctag}" style="width:100%;" colspan="3">
    {$like_button}{$l_sep}{$comment['like']} 
  </td>
</tr>
<tr>
  <td style="height:6px;" colspan="3" class="sformdark">
  </td>
</tr>
HTML;
}

function comments_edit ($comment, $chtml, $comment_url) {
global $STD;
return <<<HTML
<div class="sform">
<table class="sformtable" style="border-spacing:0px;">
{$chtml}
</table>
</div>
<br>
<form method="post" action="{$comment_url}">
<div class="sform">
<div class="sformstrip">Edit the comment below</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Comment</td>
  <td class="sformright"><textarea name="message" title="Enter your edited comment here" cols="50" rows="10" class="textbox">{$comment}</textarea></td>
</tr>
</table>
<div class="sformstrip" style="text-align:center"><input type="submit" value="Save Edit" class="button"></div>
</div>
</form>
HTML;
}

function report_sub ($id, $url, $title) {
global $STD;
return <<<HTML
<form method='post' action='{$url}'>
<input type='hidden' name='id' value='{$id}'>
<div class="sform">
<div class="sformstrip">Report a submission</div>
<table class="sformtable" style="border-spacing:1px;">
  <tr>
    <td class="sformleft"><b>Submission</b></td>
    <td class="sformright"><b>{$title}</b></td>
  </tr>
  <tr>
    <td class="sformleft"><label for="report"><b>Report</b></label><br>Enter your report in this box to alert site staff to a submission theft or objectionable content.</td>
    <td class="sformright"><textarea rows='10' cols='50' id='report' name='report' class="textbox"></textarea></td>
  </tr>
</table>
<div class="sformstrip" style="text-align:center"><input type='submit' value='Send Report' class='button'></div>
</div>
</form>
HTML;
}

function report_sub_com ($id, $url, $title, $c_author) {
global $STD;
return <<<HTML
<form method='post' action='{$url}'>
<input type='hidden' name='id' value='{$id}'>
<div class="sform">
<div class="sformstrip">Report a comment</div>
<table class="sformtable" style='border-spacing:1px;'>
  <tr>
    <td class="sformleft"><b>Submission</b></td>
    <td class="sformright"><b>{$title}</b></td>
  </tr>
  <tr>
    <td class="sformleft"><b>Comment By</b></td>
    <td class="sformright">{$c_author}</td>
  </tr>
  <tr>
    <td class="sformleft"><label for="report"><b>Report</b></label><br>Enter your report in this box to alert site staff to objectionable content in this comment.</td>
    <td class="sformright"><textarea rows='10' cols='50' id='report' name='report' class="textbox"></textarea></td>
  </tr>
</table>
<div class="sformstrip" style="text-align:center"><input type='submit' value='Send Report' class='button'></div>
</div>
</form>
HTML;
}

function report_news_com ($id, $url, $title, $c_author) {
global $STD;
return <<<HTML
<form method='post' action='{$url}'>
<input type='hidden' name='id' value='{$id}'>
<div class="sform">
<div class="sformstrip">Report a comment</div>
<table class="sformtable" style="border-spacing:1px;">
  <tr>
    <td class="sformleft"><b>News Entry</b></td>
    <td class="sformright"><b>{$title}</b></td>
  </tr>
  <tr>
    <td class="sformleft"><b>Comment By</b></td>
    <td class="sformright">{$c_author}</td>
  </tr>
  <tr>
    <td class="sformleft"><label for="report"><b>Report</b></label><br>Enter your report in this box to alert site staff to objectionable content in this comment.</td>
    <td class="sformright"><textarea rows='10' cols='50' id='report' name='report' class="textbox"></textarea></td>
  </tr>
</table>
<div class="sformstrip" style="text-align:center"><input type='submit' value='Send Report' class='button'></div>
</div>
</form>
HTML;
}

function report_msg ($id, $url, $title, $m_author) {
global $STD;
return <<<HTML
<form method='post' action='{$url}'>
<input type='hidden' name='id' value='{$id}'>
<div class="sform">
<div class="sformstrip">Report a message</div>
<table class="sformtable" style="border-spacing:1px;">
  <tr>
    <td class="sformleft"><b>Sender</b></td>
    <td class="sformright">{$m_author}</td>
  </tr>
  <tr>
    <td class="sformleft"><b>Subject</b></td>
    <td class="sformright">{$title}</td>
  </tr>
  <tr>
    <td class="sformleft"><label for="report"><b>Report</b></label><br>Enter your report in this box to alert site staff to objectionable content in this message.</td>
    <td class="sformright"><textarea rows='10' cols='50' id='report' name='report' class="textbox"></textarea></td>
  </tr>
</table>
<div class="sformstrip" style="text-align:center"><input type='submit' value='Send Report' class='button'></div>
</div>
</form>
HTML;
}

function staff_page () {
global $STD;
return file_get_contents(dirname(__FILE__)."/../staff_page.html");
}

function comments_add_full ($comment, $comment_url) {
global $STD;
return <<<HTML
<form method="post" action="{$comment_url}">
<div class="sform">
<div class="sformstrip">Add your comment below</div>
<table class="sformtable" style="border-spacing:1px;">
<tr>
  <td class="sformleft">Comment</td>
  <td class="sformright"><textarea name="message" title="Enter your comment here" cols="50" rows="10" class="textbox">{$comment}</textarea></td>
</tr>
</table>
<div class="sformstrip" style="text-align:center"><input type="submit" value="Add comment" class="button"></div>
</div>
</form>
HTML;
}


//function build_likes ($cid,$type=2,$cc,$id) {
function build_likes ($type=2,$cid,$cc,$id) { // 4/16/2025 PHP 8 test
	global $STD, $IN, $DB, $CFG;
	//=======================================================
			//BEGIN - LIKE SYSTEM (Hypernova)
			
			//query - obtain list of likes by the comment ID
			$what = $DB->format_db_where_string(array('l.cid' => $cid)); 
			$DB->query("SELECT l.lid, l.uid, l.cid, u.username FROM {$CFG['db_pfx']}_likes l ".
			"INNER JOIN {$CFG['db_pfx']}_users u ON (l.uid=u.uid) ".
			"WHERE $what");
			
			//count number of likes
			$lcount = $DB->get_num_rows();
			
			//populate the list of likes
			$total_likes = array();
			while ($lrow = $DB->fetch_row())
			{
				array_push($total_likes,$lrow);
			}
			
			//Obtain like/unlike URLs
			$small_salt = "enterSaltHere";
			$hashbrown = md5($cid.$small_salt);
			$like_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=13&cid={$cid}&type={$type}&c={$cc}&rid={$id}&hash={$hashbrown}"); //old cid value: str_rot13($cid.$small_salt)
			$unlike_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=14&cid={$cid}&type={$type}&c={$cc}&rid={$id}&hash={$hashbrown}");
			
			//list users who liked this comment
			$like_list = ''; //used for list of likes
			$like_button = '';
			if ($STD->user['can_comment'])
			{
				$like_button = "<a href={$like_url}>Like</a>";
			}
			if ($lcount > 0)
			{
				$sum_score = 0;
				
				//add separator
				if ($like_button != '')
				{
					$like_list .= ' | ';
				}
				
				//format like (plural) depends on the number of likes
				if ($lcount == 1)
				{
					$like_list .= $lcount.' like from: ';
				}
				else
				{
					$like_list .= $lcount.' likes from: ';
				}
				
				//list all users
				for ($ii = 0; $ii < $lcount; $ii ++)
				{
					$uid_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=01&uid=".$total_likes[$ii]['uid']);
					if ($ii < $lcount-1)
					{
						$like_list .= "<a href='{$uid_url}'>{$total_likes[$ii]['username']}</a>, ";
					}
					else
					{
						$like_list .= "<a href='{$uid_url}'>{$total_likes[$ii]['username']}</a>"; //last listed user will exclude ","
					}
					
					//if my user ID shows up in the list, replace the like button with unlike button
					if ($STD->user['uid'] = $total_likes[$ii]['uid'])
					{
						$like_button = "<a href={$unlike_url}>Unlike</a>";
					}
				}
			}
			
			//output
			return $like_button.$like_list;
			
			//END - LIKE SYSTEM (Hypernova)
			//=======================================================
}

//Username request form template - Hypernova
function report_username ($username,$url) {
global $STD;
return <<<HTML
<form method='post' action='{$url}'>
<div class="sform">
<!-- <div class="sformstrip">Request Username Change</div> -->
<table class="sformtable" style="border-spacing:1px;">
  <tr>
    <td class="sformleft"><b>Username</b><br>Enter your username request in this box. Please understand that in some cases, your request username may be taken.</td>
    <td class="sformright"><input type="text" size="40" name="new_username" value="{$username}" class="textbox" /></td>
  </tr>
</table>
<div class="sformstrip" style="text-align:center"><input type='submit' value='Submit Request' class='button' /></div>
</div>
</form>
HTML;
}

function recent_comment_head ($html_select = '') {
global $STD;
return <<<HTML
	<div class="sform">
		<form method="post" name="changetype">
			<div class="sformstrip">
				Number of comments:
				<select name="c" id="c" size="1" class="selectbox" onchange="if(this.options[this.selectedIndex].value != -1){ document.changetype.submit() }">
					{$html_select}
				</select>
				<input type="submit" value="Change" class="button">
			</div>
		</form>
		<table class="sformtable">
			<tbody>
HTML;
}

function recent_comment_foot () {
global $STD;
return <<<HTML
			</tbody>
		</table>
	</div>
HTML;
}

function recent_comment_row ($data) {
global $STD;
return <<<HTML
	<tr>
		<td class="sformstrip" style="width:50%;">
			<a href="{$data['url']}">{$data['title']}</a>
		</td>
		<td class="sformstrip" style="text-align:right;padding:2px">
			Posted by: <b>{$data['user']}</b> at {$STD->make_date_time($data['date'])}
		</td>
	</tr>
	<tr>
		<td class="sformblock" style="width:100%;" colspan="2">{$data['message']}</td>
	</tr>
	<tr>
		<td style="height:6px;" colspan="2" class="sformdark"></td>
	</tr>
HTML;
}
}
?>
