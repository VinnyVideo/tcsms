<?php
//------------------------------------------------------------------
// Penguinia Content Management System 1.0
//------------------------------------------------
// Copyright 2005 Justin Aquadro
// 
// component/main.php --
// Main page index, news display, commenting, and reporting
//------------------------------------------------------------------

$component = new component_main;

class component_main {
	
	var $html		= "";
	var $output		= "";
	
	function init () {
		global $STD, $IN;
		
		$this->html = $STD->template->useTemplate('main');
		
		switch ($IN['param']) {
			case  2: $this->show_news_single(); break;
			case  3: $this->add_comment(); break;
			case  4: $this->do_delete_comment(); break;
			case  5: $this->show_report(); break;
			case  6: $this->do_report(); break;
			case  7: $this->show_staff(); break;
			case  8: $this->show_news_archive(); break;
			case  9: $this->show_edit_comment(); break;
			case 10: $this->do_edit_comment(); break;
			case 11: $this->show_add_comment(); break;
			case 12: $this->show_sec_image(); break;
			case 13: $this->do_like(); break;
			case 14: $this->do_unlike(); break;
			case 15: $this->request_username(); break;
			case 16: $this->send_username(); break;
			case 17: $this->add_fav(); break;
			case 18: $this->remove_fav(); break;
			case 19: $this->show_recent_comments(); break;
			default: $this->show_news(); break;
		}
		
		$STD->template->display( $this->output );
	//	$TPL->template = $this->output;
	//	$TPL->display();
	}
	
	function show_news () {
		global $STD;
		
		require_once ROOT_PATH.'lib/news.php';
		
		$this->output .= $STD->global_template->page_header('Updates');
		$this->output .= $this->html->news_header();
		
		$NEWS = new news;
		$NEWS->query_use('n_user');
		$NEWS->query_limit('0', '10');
		$NEWS->query_order('date','DESC');
		$NEWS->getAll();
		
		while ($NEWS->nextItem()) {
			$data = $NEWS->data;
			$data['date'] = $STD->make_date_time($data['date']);
			$data['author'] = $STD->format_username($data, 'nu_');
			$data['icon'] = $STD->get_user_icon($data, 'nu_');
			$data['message'] = $STD->untag_urls($data['message']);
			$this->output .= $this->html->news_row($data);
		}
		
		$this->output .= $this->html->news_footer();
		$this->output .= $STD->global_template->page_footer();
	}
	
	function show_news_single () {
		global $STD, $IN;
		
		require_once ROOT_PATH.'lib/news.php';
		require_once ROOT_PATH.'lib/message.php';
		
		if (!empty($IN['st']) && $IN['st'] == 'new') {
			$this->last_unread_comments(2, $IN['id'], "act=main&param=02&id={$IN['id']}");
		}
		
		$this->output .= $STD->global_template->page_header('Updates');
		$this->output .= $this->html->news_header();
		
		$NEWS = new news;
		$NEWS->query_use('n_user');
		
		if (!$NEWS->get($IN['id']))	
			$STD->error("News entry does not exist");
		
		$data = $NEWS->data;
		$data['date'] = $STD->make_date_time($data['date']);
		$data['author'] = $STD->format_username($data, 'nu_');
		$data['icon'] = $STD->get_user_icon($data, 'nu_');
		$data['message'] = $STD->untag_urls($data['message']);
		$this->output .= $this->html->news_row($data);
		
		$this->output .= $this->html->news_footer();
		
		// Comments
		$this->build_comments(2, $IN['id'], "act=main&param=02&id={$IN['id']}");

		$this->output .= $STD->global_template->page_footer();
	}
		
	function add_comment () {
		global $CFG, $STD, $DB, $IN;
		
		require_once ROOT_PATH.'lib/message.php';
		
		if (!$STD->user['can_comment'])
			$STD->error("You do not have permission to leave comments.");
		
		if (strlen($IN['message']) < 8)
			$STD->error("Comment is insufficient length.");
		
		$IN['message'] = $STD->limit_string($IN['message'], 4096);
		
		require_once ROOT_PATH.'lib/parser.php';
		$PARSER = new parser;
		
		$IN['message'] = preg_replace("/\[\/quote\]<br \/><br \/>/i", "[/quote]<br>", $IN['message']);
		$IN['message'] = $PARSER->convert($IN['message']);
		
		$COM = new comment;
		
		if ($IN['type'] == 1) {
			require_once ROOT_PATH.'lib/resource.php';
			
			$module = $STD->modules->new_module( $IN['c'] );
			//$module = new $MODULE['class_name'];
			$module->init();
		
			$RES = new resource;
			$RES->query_use('r_user');
			if (!$RES->get($IN['id']))
				$STD->error("Invalid resource selected");
			
			$COM->create(array('rid' => $IN['id'],
							   'uid' => $STD->user['uid'],
							   'message' => $IN['message'],
							   'type' => $IN['type'],
							   'ip' => $_SERVER['REMOTE_ADDR'],
							   'hash' => md5($IN['id'].$STD->user['uid'].$IN['message']) ));
			
			$RES->data['comments']++;
			$RES->data['comment_date'] = time();
			$RES->update();
			
			$location = "act=resdb&param=02&c={$IN['c']}&id={$IN['id']}&st=new";
			
			// Notify user
			if ($RES->data['uid'] != $STD->user['uid'] &&
				$RES->data['ru_use_comment_msg'] == 1 &&
				($RES->data['ru_use_comment_digest'] == 0 || (time()-$RES->data['ru_last_activity']) < 15*60))
			{
				$MSG = new message;
				$MSG->data['receiver'] = $RES->data['uid'];
				$MSG->data['owner'] = $RES->data['uid'];
				$MSG->data['title'] = "Comment received on submission";
				$MSG->data['message'] = "You have received a new comment on your submission: <a href='{%site_url%}?$location'>{$RES->data['title']}</a>";
				$MSG->dispatch();
				
				$MSG->data['conversation'] = $MSG->data['mid'];
				$MSG->update();
			}
		} else {
			require_once ROOT_PATH.'lib/news.php';
			
			$NEWS = new news;
			if (!$NEWS->get($IN['id']))
				$STD->error("Invalid news item selected");
			
			$COM->create(array('rid' => $IN['id'],
							   'uid' => $STD->user['uid'],
							   'message' => $IN['message'],
							   'type' => $IN['type'],
							   'ip' => $_SERVER['REMOTE_ADDR'],
							   'hash' => md5($IN['id'].' '.$STD->user['uid'].' '.$IN['message']) ));
			
			$NEWS->data['comments']++;
			$NEWS->update();
			
			$location = "act=news&param=02&id={$IN['id']}";
		}
		
		$COM->insert();
		
		$STD->userobj->data['comments']++;
		$STD->userobj->update();
		
		$location = $STD->encode_url($_SERVER['PHP_SELF'], $location);
		$location = str_replace("&amp;", '&', $location);
		
		header("Location: $location");
		exit;
	}
	
	function do_delete_comment () {
		global $CFG, $STD, $IN;
		
		require_once ROOT_PATH.'lib/message.php';
		
		$COM = new comment;
		if (!$COM->get($IN['cid']))
			$STD->error("Attempt to delete a comment that does not exist.");
			
		$perm = 0;
		if ($STD->user['delete_comment'] && $COM->data['uid'] == $STD->user['uid']) {
			$perm = 1;
		} elseif ($STD->user['moderator']) {
			$perm = 1;
		}
		
		if (!$perm)
			$STD->error("You do not have permission to delete comments.");

		if ($IN['type'] == 1) {
			require_once ROOT_PATH.'lib/resource.php';
			
			$module = $STD->modules->new_module( $IN['c'] );
			$module->init();
		
			$RES = new resource;
			if (!$RES->get($COM->data['rid']))
				$STD->error("Invalid resource selected");
			
			$LCOM = new comment;
			$LCOM->query_order('date','DESC');
			$LCOM->query_condition('type = 1');
			$LCOM->query_condition("rid = '{$RES->data['rid']}'");
			$LCOM->query_condition("cid <> '{$IN['cid']}'");
			$LCOM->query_limit(0,1);
			$LCOM->getAll();
			
			if ($LCOM->nextItem()) {
				$RES->data['comment_date'] = $LCOM->data['date'];
			} else {
				$RES->data['comment_date'] = 0;
			}
			
			$RES->data['comments']--;
			$RES->update();
		}
		else if ($IN['type'] == 2) {
			require_once ROOT_PATH.'lib/news.php';
			
			$NEWS = new news;
			if (!$NEWS->get($COM->data['rid']))
				$STD->error("Invalid news item selected");
			
			$NEWS->data['comments']--;
			$NEWS->update();
		}
		
		$COM->remove();
		
		switch ($IN['type']) {
			case '1': $url = "act=resdb&param=02&c={$IN['c']}&id={$IN['rid']}"; break;
			case '2': $url = "act=main&param=02&id={$IN['rid']}"; break;
			default	: $url = ""; break;
		}
		
		$url = $STD->encode_url($_SERVER['PHP_SELF'], $url);
		$url = str_replace("&amp;", "&", $url);
		
		header("Location: $url");
		exit;
	}
	
	function build_comments ($type, $id, $url='') {
		global $STD, $IN, $DB, $CFG;
		
		if (empty($IN['st']))
			$IN['st'] = 0;
		
		$this->html = $STD->template->useTemplate('main');
		
		//$output = '';
		$this->output .= $this->html->comments_header(); //
		
		$current_user = -1;
		if (!$STD->user['uid'])
		{
			$current_user = intval($STD->user['uid']);
		}
		
		$COM = new comment;
		$COM->query_use('c_user');
		$COM->query_order('c.date','ASC');
		$COM->query_limit($IN['st'], '40');
		$COM->query_condition("c.type = '$type'");
		$COM->query_condition("c.rid = '$id'");
		$COM->getAll();
		
		$num_comments = 0;
		
		while ($COM->nextItem()) {
			switch ($type) {
				case 1: $stype = 2; break;
				case 2: $stype = 4; break;
			}
			
			
			$delete_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=04&type={$type}&c={$IN['c']}&rid={$IN['id']}&cid={$COM->data['cid']}");
			$edit_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=09&type={$type}&c={$IN['c']}&rid={$IN['id']}&cid={$COM->data['cid']}");
			$report_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=05&type={$stype}&id={$COM->data['cid']}");
			$quote_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=11&q={$COM->data['cid']}");
			
			$COM->data['author'] = $STD->format_username($COM->data, 'cu_');
			$COM->data['type'] = $type;
			$COM->data['c'] = $IN['c'];
			$COM->data['rid'] = $IN['id'];
			
			//$COM->data['email_icon'] = $STD->get_email_icon($COM->data, 'cu_');
			//$COM->data['website_icon'] = $STD->get_website_icon($COM->data, 'cu_');
			$COM->data['date'] = $STD->make_date_time($COM->data['date']);
			$COM->data['avatar'] = $STD->get_user_icon($COM->data, 'cu_');

			($STD->user['moderator'] || ($STD->user['delete_comment'] && $STD->user['uid'] == $COM->data['uid']))
				? $COM->data['delete_icon'] = "<a href='$delete_url' onclick='return check_delete();'><img src='{$STD->tags['global_image_path']}/delete.gif' alt='[Del]' title='Delete Comment'></a>"
				: $COM->data['delete_icon'] = '';
			($STD->user['moderator'] || ($STD->user['edit_comment'] && $STD->user['uid'] == $COM->data['uid']))
				? $COM->data['edit_icon'] = "<a href='$edit_url'><img src='{$STD->tags['image_path']}/edit.gif' alt='[Edit]' title='Edit Comment'></a>"
				: $COM->data['edit_icon'] = '';
			($STD->user['moderator'] || $STD->user['can_report'])
				? $COM->data['report_icon'] = "<a href='$report_url'><img src='{$STD->tags['image_path']}/report.gif' alt='[Report]' title='Report Comment'></a>"
				: $COM->data['report_icon'] = '';
			($STD->user['can_comment'])
				? $COM->data['quote_icon'] = "<a href='$quote_url'><img src='{$STD->tags['image_path']}/quote.gif' alt='[Quote]' title='Quote Comment'></a>"
				: $COM->data['quote_icon'] = '';
			
			$tempdata[$num_comments] = $COM->data;
			$num_comments++;
		}
		//$DB->close_db();
		
		$rcnt = $COM->countAll();
		$pages = $STD->paginate($IN['st'], $rcnt['cnt'], 40, $url);
		
		for ($cc = 0; $cc < $num_comments; $cc ++)
		{
			$COM2 = new stdClass();
			$COM2->data = $tempdata[$cc];
			
			//=======================================================
			//BEGIN - LIKE SYSTEM (Hypernova)
			//Hypernova's comment: These codes started very clean and organized until there's a bug that the Like/Unlike button shows up. I ended up scratching my head for hours and fixed it. Unfortunately I am quite lazy to come back and re-organize the code.
			
			//query - obtain list of likes by the comment ID
			$what = $DB->format_db_where_string(array('l.cid' => $COM2->data['cid'])); 
			$l_query = "SELECT l.lid, l.uid as usid, l.cid, u.username, g.name_prefix, g.name_suffix ".
			"FROM {$CFG['db_pfx']}_likes l ".
			"INNER JOIN {$CFG['db_pfx']}_users u ON (l.uid=u.uid) ".
			"INNER JOIN {$CFG['db_pfx']}_groups g ON (g.gid=u.gid) ".
			"WHERE $what";
			//establish Alt MySQL connection
			$alt_connection = mysqli_connect($CFG['db_host'],$CFG['db_user'],$CFG['db_pass'],$CFG['db_db']);
			
			//throw an error if connection failed
			if ($alt_connection->connect_errno)
			{
				$STD->error("CRITICAL ERROR: Failed to connect to MySQL: (" . $alt_connection->connect_errno . ") " . $current_connection->connect_error);
			}
			
			//perform the query
			$lcount = 0;
			$total_likes = array();
			if (mysqli_multi_query($alt_connection,$l_query))
			{
				do
				{
					// Store first result set
					if ($result = mysqli_store_result($alt_connection))
					{
						// Fetch one and one row
						while ($lrow = mysqli_fetch_row($result))
						{
							array_push($total_likes,$lrow);
							$lcount += 1;
						}
						// Free result set
						mysqli_free_result($result);
					}
				}
				while (mysqli_more_results($alt_connection));
			}
			
			//shut down MySQL
			mysqli_close($alt_connection);
					
			//count number of likes
			//$lcount = $DB->get_num_rows();
			
			//populate the list of likes
			/*while ($lrow = $DB->fetch_row())
			{
				array_push($total_likes,$lrow);
			}*/
			
			//Obtain like/unlike URLs
			$small_salt = "enterSaltHere";
			$hashbrown = md5($COM2->data['cid'].$small_salt);
			$COM2->data['like_url'] = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=13&cid={$COM2->data['cid']}&type={$type}&c={$IN['c']}&rid={$IN['id']}&hash={$hashbrown}"); //old cid: str_rot13($COM2->data['cid'].$small_salt)
			$COM2->data['unlike_url'] = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=14&cid={$COM2->data['cid']}&type={$type}&c={$IN['c']}&rid={$IN['id']}&hash={$hashbrown}");
			$COM2->data['lcount'] = $lcount;
			
			//list users who liked this comment
			$like_list = ''; //used for list of likes
			if ($lcount > 0)
			{
				$sum_score = 0;
				
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
					$uid_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=01&uid=".$total_likes[$ii][1]);
					//$u_format[$ii] = $STD->format_username_uid($total_likes[$ii][1]); //$total_likes[$ii][3];
					if ($ii < $lcount-1)
					{
						$like_list .= "<a href='{$uid_url}'>{$total_likes[$ii][4]}{$total_likes[$ii][3]}{$total_likes[$ii][5]}</a>, "; //$u_format[$ii].", ";
					}
					else
					{
						$like_list .= "<a href='{$uid_url}'>{$total_likes[$ii][4]}{$total_likes[$ii][3]}{$total_likes[$ii][5]}</a>"; //last listed user will exclude "," ;;;;; //$u_format[$ii];//
					}
					
					//save all of the user IDs
					$COM2->data['luid'][$ii] = $total_likes[$ii][1];
				}
			}
			
			//output
			$COM2->data['like'] = $like_list;
			
			//END - LIKE SYSTEM (Hypernova)
			//=======================================================
			
			$this->output .= $this->html->comments_row($COM2->data); //
		}
		
		if (!$num_comments)
			$this->output .= $this->html->comments_none(); //$this->output
		
		$this->output .= $this->html->comments_footer($pages, $STD->encode_url($_SERVER['PHP_SELF'], $url)); //$this->output
		
		(!empty($IN['exp']))
			? $aexpand = '' : $aexpand = 'display:none';
			
		if ($STD->user['can_comment'])
			$this->output .= $this->html->comments_add($STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=03&type={$type}&c={$IN['c']}&id={$IN['id']}"), $aexpand); ////$this->output
		
		return $this->output;//$this->output
	}
	
	function last_unread_comments ($type, $id, $url) {
		global $STD, $IN, $session;
		
		$date = $STD->user['last_visit'];
		
		if (empty ($session->data['rr']) ) $session->data['rr'] = array();
		$rr = empty ($session->data['rr'][$id]) ? 0 : $session->data['rr'][$id];
		
		if ($rr > $date)
			$date = $rr;
		
		$COM = new comment;
		$COM->query_condition("c.type = '{$type}'");
		$COM->query_condition("c.rid = '{$id}'");
		$COM->query_condition("date < '{$date}'");
		$cnt = $COM->countAll();
		$st = ($cnt['cnt']+1) - (($cnt['cnt']+1) % 40);
		
		$COM = new comment;
		$COM->query_condition("c.type = '{$type}'");
		$COM->query_condition("c.rid = '{$id}'");
		$COM->query_condition("date >= '{$date}'");
		$COM->query_order('c.date', 'asc');
		$COM->query_limit(0, 1);
		$COM->getAll();
		
		$row = $COM->nextItem();
		if ($row) {
			$entry = "#c{$COM->data['cid']}";
		} else {
			$COM = new comment;
			$COM->query_condition("c.type = '{$type}'");
			$COM->query_condition("c.rid = '{$id}'");
			$COM->query_order('c.date', 'desc');
			$COM->query_limit(0, 1);
			$COM->getAll();
			
			$row = $COM->nextItem();
			if ($row)
				$entry = "#c{$COM->data['cid']}";
			else
				$entry = '';
				
			$st = $cnt['cnt'] - ($cnt['cnt'] % 40);
		}
		
		$url = $STD->encode_url($_SERVER['PHP_SELF'], "{$url}&st={$st}{$entry}");
		$url = str_replace("&amp;", "&", $url);
		header("Location: $url");
		exit;
	}
	
	function show_report () {
		global $CFG, $STD, $DB, $IN;
		
		if (!$STD->user['can_report'] && !$STD->user['moderator'])
			$STD->error("You must be logged in and have permission to report objectionable submissions.");
		
		if ($IN['type'] == 1) {
			require_once ROOT_PATH.'lib/resource.php';
			
			$OBJ = new resource;
			$OBJ->get($IN['id']);
		} elseif (in_array($IN['type'], array(2,4))) {
			require_once ROOT_PATH.'lib/message.php';
			
			$OBJ = new comment;
			$OBJ->query_use('c_user');
			switch ($IN['type']) {
				case 2: $OBJ->query_use('resource'); break;
				case 4: $OBJ->query_use('news'); break;
			}
			$OBJ->get($IN['id']);
		} elseif ($IN['type'] == 3) {
			require_once ROOT_PATH.'lib/message.php';
			
			$OBJ = new message;
			$OBJ->query_use('s_user');
			$OBJ->get($IN['id']);
		}
		
		if (!$OBJ)
			$STD->error("This subject for this report does not exist");
		
		if ($IN['type'] == 3 && $OBJ->data['receiver'] != $STD->user['uid'])
			$STD->error("You cannot report a message you do not own.");
		
		$this->output .= $STD->global_template->page_header('Send Report');
		
		$rep_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=06&type={$IN['type']}");
		
		switch ($IN['type']) {
			case 1: $this->output .= $this->html->report_sub($IN['id'], $rep_url, $OBJ->data['title']); break;
			case 2: $this->output .= $this->html->report_sub_com($IN['id'], $rep_url, $OBJ->data['r_title'], $OBJ->data['cu_username']); break;
			case 3: $this->output .= $this->html->report_msg($IN['id'], $rep_url, $OBJ->data['title'], $OBJ->data['su_username']); break;
			case 4: $this->output .= $this->html->report_news_com($IN['id'], $rep_url, $OBJ->data['n_title'], $OBJ->data['cu_username']); break;
		}
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function do_report () {
		global $CFG, $STD, $DB, $IN;
		
		if (!$STD->user['can_report'] && !$STD->user['moderator'])
			$STD->error("You must be logged in and have permission to report objectionable submissions.");
		
		if (empty($IN['report']))
			$STD->error("You must provide an explaination for your report.");
		
		$IN['report'] = $STD->limit_string($IN['report'], 2048);
		
		if ($IN['type'] == 1) {
			require_once ROOT_PATH.'lib/resource.php';
			
			$OBJ = new resource;
			$OBJ->query_use('r_user');
			$OBJ->get($IN['id']);
		} elseif (in_array($IN['type'], array(2,4))) {
			require_once ROOT_PATH.'lib/message.php';
			
			$OBJ = new comment;
			$OBJ->query_use('c_user');
			switch ($IN['type']) {
				case 2: $OBJ->query_use('r_user'); break;
				case 4: $OBJ->query_use('n_user'); break;
			}
			$OBJ->get($IN['id']);
		} elseif ($IN['type'] == 3) {
			require_once ROOT_PATH.'lib/message.php';
			
			$OBJ = new message;
			$OBJ->query_use('s_user');
			$OBJ->get($IN['id']);
		}
		
		if (!$OBJ)
			$STD->error("The subject for this report does not exist.");
		
		if ($IN['type'] == 3 && $OBJ->data['receiver'] != $STD->user['uid'])
			$STD->error("You cannot report a message you do not own.");
		
		switch ($IN['type']) {
			case 1: $rep_url = "act=resdb&param=02&c={$OBJ->data['type']}&id={$OBJ->data['rid']}"; break;
			case 2: $rep_url = "act=resdb&param=02&c={$OBJ->data['r_type']}&id={$OBJ->data['r_rid']}"; break;
			case 3: $rep_url = "act=msg&param=01"; break;
			case 4: $rep_url = "act=main&param=02&id={$OBJ->data['n_nid']}"; break;
		}
		
		require ROOT_PATH.'lib/parser.php';
		$PARSER = new parser;
		
		$rep_url = $STD->encode_url($_SERVER['PHP_SELF'], $rep_url);
		
		switch ($IN['type']) {
			case 1:
				$title = "Reported: {$OBJ->data['title']}";
				$mesg1 = "Reported Submission: <a href='$rep_url'>{$OBJ->data['title']}</a> by {$OBJ->data['ru_username']}<br><br>";
				$mesg2 = "";
				$retrn = "Return to viewing submission";
				$rtype = 1; break;
			case 2:
				$title = "Reported: Comment";
				$mesg1 = "Reported Comment:<br><br><div class='rep_box'>{$OBJ->data['message']}</div><br>Comment By: {$OBJ->data['cu_username']}";
				$mesg2 = "<br>In: <a href='$rep_url'>{$OBJ->data['r_title']}</a><br><br>";
				$retrn = "Return to viewing submission";
				$rtype = 2; break;
			case 3:
				$title = "Reported: Personal Message";
				$mesg1 = "Reported Personal Message:<br>Subject: {$OBJ->data['title']}<br><br>";
				$mesg2 = "<div class='rep_box'>{$OBJ->data['message']}</div><br>Message By: {$OBJ->data['su_username']}<br><br>";
				$retrn = "Return to message center";
				$rtype = 3; break;
			case 4:
				$title = "Reported: Comment";
				$mesg1 = "Reported Comment:<br><br><div class='rep_box'>{$OBJ->data['message']}</div><br>Comment By: {$OBJ->data['cu_username']}";
				$mesg2 = "<br>In News Entry: <a href='$rep_url'>{$OBJ->data['n_title']}</a><br><br>";
				$retrn = "Return to viewing news entry";
				$rtype = 2; break;
		}
		
		$IN['report'] = $PARSER->convert($IN['report']);
		$message = $mesg1.$mesg2.
				   "------------------------------------------------------<br>".
				   "Reported By: {$STD->user['username']}<br><br>".
				   "The above submission was reported for the following reason:<br><br>{$IN['report']}";
		
		$insert = $DB->format_db_values(array('sender'	=> $STD->user['uid'],
											  'date'	=> time(),
											  'title'	=> $title,
											  'message'	=> $message,
											  'type'	=> $rtype,
											  'aux'		=> $IN['id']));
		$DB->query("INSERT INTO {$CFG['db_pfx']}_admin_msg ({$insert['FIELDS']}) VALUES ({$insert['VALUES']})");
		
		//------------------------------------------------
		// Message
		//------------------------------------------------
		
		$url2 = $STD->encode_url($_SERVER['PHP_SELF'], '');
		
		$message = "The report was successfully sent to the site staff.
			Your report will be reviewed by a staff member shortly.
			<p align='center'><a href='$rep_url'>$retrn</a><br>
			<a href='$url2'>Return to the main page</a></p>";
		
		$this->output .= $STD->global_template->message($message);
		
	}
	
	function show_staff () {
		global $STD;

		$this->output .= $STD->global_template->page_header('Staff');
		
		$this->output .= $this->html->staff_page();
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function show_news_archive () {
		global $STD, $IN;
		
		require_once ROOT_PATH.'lib/news.php';
		
		// Get date constraints
		$curr_yr = gmdate("Y", $STD->translate_date( time() ));
		
		$NEWSOLD = new news;
		$NEWSOLD->query_order('date','ASC');
		$NEWSOLD->query_limit('0', '1');
		$NEWSOLD->getAll();
		
		if (!$NEWSOLD->nextItem())
			$base_yr = 1980;
		else
			$base_yr = gmdate("Y", $STD->translate_date( $NEWSOLD->data['date']) );
		
		// Get selected dates
		
		if (!empty($IN['from_d'])) {
			$from_day = $IN['from_d'];
			$from_mon = $IN['from_m'];
			$from_year = $IN['from_y'];
			$to_day = $IN['to_d'];
			$to_mon = $IN['to_m'];
			$to_year = $IN['to_y'];
		} else {
			$from_day = gmdate("j", $STD->translate_date( time() ));
			$from_mon = gmdate("n", $STD->translate_date( time() ));
			$from_year = $curr_yr;
			$to_day = 1;
			$to_mon = $from_mon;
			$to_year = $curr_yr;
		}
		
		$upper = gmmktime(0, 0, 0, $from_mon, $from_day, $from_year)+60*60*24-1;
		$lower = gmmktime(0, 0, 0, $to_mon, $to_day, $to_year);
		$ordir = 'DESC';
		
		if ($upper < $lower) {
			$STD->swap($upper, $lower);
			$ordir = 'ASC';
		}
		
		// Build date arrays
		$curr_day = gmdate("j", $STD->translate_date( time() ));
		$curr_mon = gmdate("n", $STD->translate_date( time() ));
		
		$d_array = range(1, 31);
		$y_array = range($base_yr, $curr_yr);
		$m_array = range(1, 12);
		$m_array_n = array('January','February','March','April','May','June','July','August','September','October','November','December');
		
		$from = array();
		$from['d'] = $STD->make_select_box('from_d', $d_array, $d_array, $from_day, 'selectbox');
		$from['m'] = $STD->make_select_box('from_m', $m_array, $m_array_n, $from_mon, 'selectbox');
		$from['y'] = $STD->make_select_box('from_y', $y_array, $y_array, $from_year, 'selectbox');
		
		$to = array();
		$to['d'] = $STD->make_select_box('to_d', $d_array, $d_array, $to_day, 'selectbox');
		$to['m'] = $STD->make_select_box('to_m', $m_array, $m_array_n, $to_mon, 'selectbox');
		$to['y'] = $STD->make_select_box('to_y', $y_array, $y_array, $to_year, 'selectbox');
		
		// Begin Output
		
		$this->output .= $STD->global_template->page_header('Updates Archive');
		$this->output .= $this->html->news_archive_header($from, $to);
		
		$NEWS = new news;
		$NEWS->query_use('n_user');
	//	$NEWS->query_limit('0', '10');
		$NEWS->query_order('date',$ordir);
		$NEWS->query_condition("date >= '$lower'");
		$NEWS->query_condition("date <= '$upper'");
		$NEWS->getAll();
		
		while ($NEWS->nextItem()) {
			$data = $NEWS->data;
			$data['date'] = $STD->make_date_time($data['date']);
			$data['author'] = $STD->format_username($data, 'nu_');
			$data['icon'] = $STD->get_user_icon($data, 'nu_');
			$data['message'] = preg_replace ("/\{%site_url%\}/", '', $data['message']);
			$this->output .= $this->html->news_row($data);
		}
		
		$this->output .= $this->html->news_footer();
		$this->output .= $STD->global_template->page_footer();
	}
	
	function show_edit_comment () {
		global $STD, $IN;
		
		require_once ROOT_PATH.'lib/message.php';
		require_once ROOT_PATH.'lib/parser.php';
		
		$PARSER = new parser;
		
		$COM = new comment;
		$COM->query_use('c_user');
		
		if (!$COM->get($IN['cid']))
			$STD->error("Attempt to edit a comment that does not exist.");
		
		$perm = 0;
		if ($STD->user['uid'] == $COM->data['uid'] && $STD->user['edit_comment']) {
			$perm = 1;
		} elseif ($STD->user['moderator']) {
			$perm = 1;
		}
		
		if (!$perm)
			$STD->error("You do not have permission to edit this comment.");
		
		// Generate comment
		$COM->data['author'] = $STD->format_username($COM->data, 'cu_');
		$COM->data['date'] = $STD->make_date_time($COM->data['date']);
		$COM->data['report_icon'] = '';
		$COM->data['delete_icon'] = '';
		$COM->data['edit_icon'] = '';
		$COM->data['quote_icon'] = '';
		$COM->data['like_url'] = '';
		$COM->data['lcount'] = 0;
		$COM->data['avatar'] = '';
		$COM->data['like'] = '';
		$COM->data['edit_header'] = 'display: none;';
		$chtml = $this->html->comments_row($COM->data);
		
		$COM->data['message'] = $PARSER->unconvert($COM->data['message']);
		
		$curl = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=10&type={$IN['type']}&c={$IN['c']}&rid={$IN['rid']}&cid={$IN['cid']}");
		
		$this->output = $STD->global_template->page_header("Edit Comment");
		
		$this->output .= $this->html->comments_edit($COM->data['message'], $chtml, $curl);
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function do_edit_comment () {
		global $STD, $IN;
		
		require_once ROOT_PATH.'lib/message.php';
		require_once ROOT_PATH.'lib/parser.php';
		
		$PARSER = new parser;
		$COM = new comment;
		
		if (!$COM->get($IN['cid']))
			$STD->error("Attempt to edit a comment that does not exist.");
		
		$perm = 0;
		if ($STD->user['uid'] == $COM->data['uid'] && $STD->user['edit_comment']) {
			$perm = 1;
		} elseif ($STD->user['moderator']) {
			$perm = 1;
		}
		
		if (!$perm)
			$STD->error("You do not have permission to edit this comment.");
		
		if (strlen($IN['message']) < 8)
			$STD->error("Comment is insufficient length.");
		
		$COM->data['message'] = $PARSER->convert($IN['message']);
		
		$COM->update();
		
		switch ($IN['type']) {
			case '1': $url = "act=resdb&param=02&c={$IN['c']}&id={$IN['rid']}"; break;
			case '2': $url = "act=main&param=02&id={$IN['rid']}"; break;
			default	: $url = ""; break;
		}
		
		$url = $STD->encode_url($_SERVER['PHP_SELF'], $url);
		$url = str_replace("&amp;", "&", $url);
		
		header("Location: $url");
		exit;
	}
	
	function show_add_comment () {
		global $STD, $IN, $CFG, $session;
		
		if (!$STD->user['can_comment'])
			$STD->error("You do not have permission to leave comments.");
			
		require_once ROOT_PATH.'lib/message.php';
		require_once ROOT_PATH.'lib/parser.php';
		
		$PARSER = new parser;
		
		$COM = new comment;
		$COM->query_use('resource');
		$COM->query_use('c_user');
		
		if (!$COM->get($IN['q']))
			$STD->error("Attempt to edit a comment that does not exist.");
		
		$date = $STD->make_date_time($COM->data['date']);
		$message = $PARSER->unconvert($COM->data['message']);
		
		if ($CFG['quote_nesting'] == 0)
			$message = preg_replace("/\[quote(=.+?)?\](.*)\[\/quote\]\n*/is", "", $message);
		
		$message = preg_replace("/\n*$/", "", $message);
		
		if (strpos($COM->data['cu_username'], ',') !== false)
			$COM->data['cu_username'] = "\"" . $COM->data['cu_username'] . "\"";
			
		$data = "[quote=".$COM->data['cu_username'].",".$date."]".$message."[/quote]\n";
		
		$type = $COM->data['type'];
		$c = $COM->data['r_type'];
		$id = $COM->data['rid'];
		
		$curl = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=03&type={$type}&c={$c}&id={$id}");
		
		$this->output = $STD->global_template->page_header("Add Comment");
		
		$this->output .= $this->html->comments_add_full($data, $curl);
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function show_sec_image () {
		global $STD, $DB, $CFG, $session;
		
		$sess = $session->sess_id;

		$DB->query( "SELECT regcode FROM {$CFG['db_pfx']}_sec_images WHERE sessid = '{$sess}'" );
		$row = $DB->fetch_row();
		
		if ( !$row ) {
			$STD->captcha( " " );
		}
		else {
			$STD->captcha( $row['regcode'] );
		}
		
		//exit;
	}
	
//LIKE A COMMENT - by Hypernova (2019)
	function do_like () {
		global $CFG, $STD, $DB, $IN;
		
		//Muted/banned users cannot leave likes
		if (!$STD->user['can_comment'])
			$STD->error("You do not have permission to leave likes. (UID = {$STD->user['uid']})");
		
		
		//salt (prevent MySQL injection)
		$small_salt = "enterSaltHere";
		
		//INVALID INPUTS
		if (empty($IN['cid']))
		{
			$STD->error("Invalid comment selected.");
		}
		
		//security token check
		$hashbrown2 = md5($IN['cid'].$small_salt);
		if ($IN['hash'] == $hashbrown2) {
			//PASS - $STD->error("CORRECT security token! DEBUG PURPOSE: IN['cid'] = {$IN['cid']}; in['hash'] = {$IN['hash']}; hashbrown2 = {$hashbrown2}; uid = {$STD->user['uid']}");
		}
		else {
			$STD->error("Invalid security token!");
		}
		
		//FAILSAFE SYSTEM
		$like_failsafe = 0;
		//$fs_query = "SELECT * FROM tsms_likes WHERE uid={$STD->user['uid']} AND cid={$cidin}";
		
		//I have to manually write a query because I have absolutely no idea how $DB->query() works even after going over all of the source codes. ~Hypernova
		$where = $DB->format_db_where_string(array('username' => $IN['new_username'])); 
		$DB->query("SELECT * FROM {$CFG['db_pfx']}_likes WHERE cid={$IN['cid']} AND uid={$STD->user['uid']}"); //{$cidin} is OBSOLETE
		$num_rows = $DB->get_num_rows();
		if ($num_rows > 0)
		{
			$like_failsafe = 1;
		}
		
		//insert the like data into database
		if ($like_failsafe == 0)
		{
			$DB->query("INSERT INTO {$CFG['db_pfx']}_likes (`uid`, `cid`) VALUES ('{$STD->user['uid']}', '{$IN['cid']}') ".
				"ON DUPLICATE KEY UPDATE cid = '{$cidin}'");//*/
			/*$DB->query("INSERT INTO {$CFG['db_pfx']}_likes (`uid`, `cid`) ".
						"SELECT * FROM DUAL ".
						"WHERE NOT EXISTS ( SELECT * FROM {$CFG['db_pfx']}_likes WHERE uid = '{$STD->user['uid']}' AND cid = '{$cidin}' ) ".
						"LIMIT 1;");*/
		}
		
		//refresh
		switch ($IN['type']) {
			case '1': $url = "act=resdb&param=02&c={$IN['c']}&id={$IN['rid']}#c{$IN['cid']}"; break;
			case '2': $url = "act=main&param=02&id={$IN['rid']}#c{$IN['cid']}"; break;
			default	: $url = ""; break;
		}
		
		$url = $STD->encode_url($_SERVER['PHP_SELF'], $url);
		$url = str_replace("&amp;", "&", $url);
		
		header("Location: $url");

		exit;
	}
	
	//UNLIKE A COMMENT - by Hypernova (2019)
	function do_unlike () {
		global $CFG, $STD, $DB, $IN;
		
		//Muted/banned users cannot leave likes
		/*if (!$STD->user['can_comment'])
			$STD->error("You do not have permission to modify likes.");*/
		
		//salt (prevent MySQL injection)
		$small_salt = "enterSaltHere";
		
		//INVALID INPUTS
		if (empty($IN['cid']))
		{
			$STD->error("Invalid comment selected.");
		}
		
		//security token check
		$hashbrown2 = md5($IN['cid'].$small_salt);
		if ($IN['hash'] == $hashbrown2) {
			//$STD->error("CORRECT security token! DEBUG PURPOSE: IN['cid'] = {$IN['cid']}; in['hash'] = {$IN['hash']}; hashbrown2 = {$hashbrown2}; uid = {$STD->user['uid']}");
		}
		else {
			$STD->error("Invalid security token!");
		}
		
		//insert the like data into database
		$DB->query("DELETE FROM {$CFG['db_pfx']}_likes WHERE cid={$IN['cid']} AND uid={$STD->user['uid']}");
		
		//refresh
		switch ($IN['type']) {
			case '1': $url = "act=resdb&param=02&c={$IN['c']}&id={$IN['rid']}#c{$IN['cid']}"; break;
			case '2': $url = "act=main&param=02&id={$IN['rid']}#c{$IN['cid']}"; break;
			default	: $url = ""; break;
		}
		
		$url = $STD->encode_url($_SERVER['PHP_SELF'], $url);
		$url = str_replace("&amp;", "&", $url);
		
		header("Location: $url");
		
		exit;
	}
	
	//Used for username change request - by Hypernova (2019)
	function request_username () {
		global $CFG, $STD, $DB, $IN;
		
		//ERROR - guest or user that can't report
		if (!$STD->user['can_report'])
			$STD->error("You must be logged in and have permission to request username change.");
		
		//Not sure tbh
		//$user = new user;
		//$user->query_use('group');
		
		//Output
		$this->output .= $STD->global_template->page_header('Username Change Request'); //title
		
		$rep_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=16"); //Submit URL
		
		$this->output .= $this->html->report_username($STD->user['username'],$rep_url); //Generate content
		
		$this->output .= $STD->global_template->page_footer(); //footer
	}
	
	
	//Send username change request - by Hypernova (2019)
	function send_username () {
		global $CFG, $STD, $DB, $IN;
		
		//ERROR - guest or user that can't report
		if (!$STD->user['can_report'])
			$STD->error("You must be logged in and have permission to request username change.");
		
		//ERROR - blank username
		if (empty($IN['new_username']))
			$STD->error("The requested username is left blank.");
		
		//TEST - new username input
		//$STD->error('Your username request is: "'.$IN["new_username"].'".'); 
		
		//ERROR - username is the same as before
		/*$current = $STD->user['username'];
		$new_u = $IN['new_username'];
		if ($current == $new_u)
			$STD->error('The requested username is the same as your current username.');*/
		
		//QUERY - check user database see if the new name shows up
		$where = $DB->format_db_where_string(array('username' => $IN['new_username'])); 
		$DB->query("SELECT username FROM ".$CFG['db_pfx']."_users ".
								   "WHERE $where");
		$count = $DB->get_num_rows();
		
		//ERROR - username is taken
		if ($count > 0)
		{
			$STD->error("The requested username is taken by another user.");
		}
		
		//Limit username length
		$IN['new_username'] = $STD->limit_string($IN['new_username'], 32);
		
		//Format message
		$title = "Request: username change";
		$retrn = "Return to user preferences";
		$rtype = 3;
		
		$message = "<br>Requested Username: <u>".$IN['new_username']."</u><br>".
				   "------------------------------------------------------<br>".
				   "Requested By: {$STD->user['username']}<br>";
		
		//Send message (register to database)
		$insert = $DB->format_db_values(array('sender'	=> $STD->user['uid'],
											  'date'	=> time(),
											  'title'	=> $title,
											  'message'	=> $message,
											  'type'	=> 6,
											  'aux'		=> 6,
											  'special_data'		=> $IN['new_username']));
		$DB->query("INSERT INTO {$CFG['db_pfx']}_admin_msg ({$insert['FIELDS']}) VALUES ({$insert['VALUES']})");
		
		//------------------------------------------------
		// Message
		//------------------------------------------------
		
		$url2 = $STD->encode_url($_SERVER['PHP_SELF'], '');
		
		//DONE!
		$message = "The request to change your name to <b>".$IN['new_username']."</b> was successfully sent to the site staff.
			Your request will be reviewed by a main site admin as soon as they are available.<br><br>
			<b><u>If you are not able to login using your old username, please use your new username.</u></b> 
			<p align='center'><a href='".$STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=02")."'>$retrn</a><br>
			<a href='$url2'>Return to the main page</a></p>";
		
		$this->output .= $STD->global_template->message($message);
		
	}
	
	//FAVORITE A SUBMISSION - by Hypernova (2020)
	function add_fav () {
		global $CFG, $STD, $DB, $IN;
		
		//Guest users cannot use the fav system
		if (!$STD->user['uid'])
			$STD->error("You must be logged in to favorite a submission!");//(UID = {$STD->user['uid']})
		
		//INVALID INPUTS
		if (empty($IN['rid']))
		{
			$STD->error("Invalid submission selected.");
		}
		
		//Regenerate and check hash
		$small_salt = "enterSaltHere";
		$hashbrown2 = md5($IN['rid'].$small_salt);
		if ($IN['hash'] == $hashbrown2) {
			//$STD->error("CORRECT security token! DEBUG PURPOSE: IN['rid'] = {$IN['rid']}; in['hash'] = {$IN['hash']}; hashbrown2 = {$hashbrown2}; uid = {$STD->user['uid']}");
		}
		else {
			//$STD->error("Invalid security token!"); // Commented out because it's probably not really needed - this needs a rewrite
		}
		
		//Manually check for duplicate first
		$num_rows = 0;
		$DB->query("SELECT * FROM {$CFG['db_pfx']}_bookmarks WHERE rid={$IN['rid']} AND uid={$STD->user['uid']}");
		$num_rows = $DB->get_num_rows();
		$like_failsafe = 0;
		if ($num_rows > 0)
		{
			$like_failsafe = 1;
			//$STD->error("You tried to favorite the submission for more than once! (Result: {$num_rows} row(s))");
		}
		
		//insert the like data into database
		if ($like_failsafe == 0)
		{
			$DB->query("INSERT INTO {$CFG['db_pfx']}_bookmarks (`uid`, `rid`, `type`) VALUES ('{$STD->user['uid']}', '{$IN['rid']}', '{$IN['c']}') ".
				"ON DUPLICATE KEY UPDATE rid = '{$IN['rid']}'");//*/
			/*$DB->query("INSERT INTO {$CFG['db_pfx']}_likes (`uid`, `cid`) ".
						"SELECT * FROM DUAL ".
						"WHERE NOT EXISTS ( SELECT * FROM {$CFG['db_pfx']}_likes WHERE uid = '{$STD->user['uid']}' AND cid = '{$cidin}' ) ".
						"LIMIT 1;");*/
		}
		
		//refresh
		$url = "act=resdb&param=02&c={$IN['c']}&id={$IN['rid']}";
		$url = $STD->encode_url($_SERVER['PHP_SELF'], $url);
		$url = str_replace("&amp;", "&", $url);
		
		header("Location: $url");

		exit;
	}
	
	//REMOVE A FAVORITE - by Hypernova (2020)
	function remove_fav () {
		global $CFG, $STD, $DB, $IN;
		
		//Guest users cannot use the fav system
		if (!$STD->user['uid'])
			$STD->error("You must be logged in to favorite a submission!");//(UID = {$STD->user['uid']})
		
		//INVALID INPUTS
		if (empty($IN['rid']))
		{
			$STD->error("Invalid submission selected.");
		}
		
		//regenrate and check hash
		$small_salt = "enterSaltHere";
		$hashbrown2 = md5($IN['rid'].$small_salt);
		if ($IN['hash'] == $hashbrown2) {
			//$STD->error("CORRECT security token! DEBUG PURPOSE: IN['rid'] = {$IN['rid']}; in['hash'] = {$IN['hash']}; hashbrown2 = {$hashbrown2}; uid = {$STD->user['uid']}");
		}
		else {
			$STD->error("Invalid security token!");
		}
		
		//insert the like data into database
		$DB->query("DELETE FROM {$CFG['db_pfx']}_bookmarks WHERE rid={$IN['rid']} AND uid={$STD->user['uid']}");
		
		//refresh
		$url = "act=resdb&param=02&c={$IN['c']}&id={$IN['rid']}#c{$cidin}";
		$url = $STD->encode_url($_SERVER['PHP_SELF'], $url);
		$url = str_replace("&amp;", "&", $url);
		
		header("Location: $url");
		
		exit;
	}

	//SHOW RECENT COMMENTS - by Hypernova (2020) - W.I.P.
	function show_recent_comments() {
		//----------------------------------------------
		//INIT
		//----------------------------------------------
		//init external class/obj/func
		global $IN, $STD, $DB, $CFG;
		require_once ROOT_PATH.'lib/resource.php';
		require_once ROOT_PATH.'lib/message.php';
		$this->html = $STD->template->useTemplate('main');
		
		//INVALID/FILTER INPUTS
		if (empty($IN['c'])) {
			$IN['c'] = 20;
		}
		$limit = preg_replace('/[^0-9]/', '', $IN['c']);
		if ($limit == '') {
			$limit = 20;
		} else if ($limit > 100) {
			$limit = 100;
		}
		
		//COMMENT OBJ
		$comment = new comment;
		
		//QUERY	
		$rc_query = <<<"SQL"
			(
				SELECT c.*, u.username, r.title, g.name_prefix, g.name_suffix, r.type AS rt, r.rid 
				FROM {$CFG['db_pfx']}_comments AS c 
					LEFT JOIN {$CFG['db_pfx']}_users AS u ON (u.uid = c.uid) 
					LEFT JOIN {$CFG['db_pfx']}_groups g ON (g.gid = u.gid) 
					LEFT JOIN {$CFG['db_pfx']}_resources r ON (c.rid = r.rid) 
				WHERE c.type = 1
				ORDER BY date DESC 
				LIMIT {$limit}
			) 
			UNION ALL 
			( 
				SELECT c.*, u.username, r.title, g.name_prefix, g.name_suffix, '' AS rt, r.nid AS rid 
				FROM {$CFG['db_pfx']}_comments AS c 
					LEFT JOIN {$CFG['db_pfx']}_users AS u ON (u.uid = c.uid) 
					LEFT JOIN {$CFG['db_pfx']}_groups g ON (g.gid = u.gid) 
					LEFT JOIN {$CFG['db_pfx']}_news r ON (r.nid = c.rid) 
				WHERE c.type = 2
				ORDER BY date DESC 
				LIMIT {$limit}
			) 
			ORDER BY date DESC 
			LIMIT {$limit}
		SQL;
		$DB->query($rc_query);
		$count = $DB->get_num_rows();
		$comment = $DB;
		
		//----------------------------------------------
		//HEADER
		//----------------------------------------------
		//Menu for number of comments to show
		$html_select_submission = '';
		$hss_name = '';
		for ($i = 1; $i <= 4; $i ++) {
			switch ($i) {
				case (1): $hss_name = "10"; break;
				case (2): $hss_name = "20"; break;
				case (3): $hss_name = "50"; break;
				case (4): $hss_name = "100"; break;
			}
			if ($hss_name == $IN['c']) {
				$html_select_submission .= '<option value="'.$hss_name.'" selected="selected">'.$hss_name.'</option>';
			} else {
				$html_select_submission .= '<option value="'.$hss_name.'">'.$hss_name.'</option>';
			}
		}
		//elements
		$this->output .= $STD->global_template->page_header("Recent {$limit} Comments");
		$this->output .= $this->html->recent_comment_head($html_select_submission);
		
		//----------------------------------------------
		//COMMENT
		//----------------------------------------------
		while ($row = $DB->fetch_row()) {
			//determine whether it's a submission or update (news)
			if ($row['type'] == 1) {
				$row['url'] = $STD->encode_url($_SERVER['PHP_SELF']."?act=resdb&param=2&c={$row['rt']}&id={$row['rid']}#c{$row['cid']}");
				switch ($row['rt']) {
					case (1): $row['title'] = "[Graphic] ".$row['title']; break;
					case (2): $row['title'] = "[Game] ".$row['title']; break;
					case (3): $row['title'] = "[Review] ".$row['title']; break;
					case (4): $row['title'] = "[How-to] ".$row['title']; break;
					case (5): $row['title'] = "[Sound] ".$row['title']; break;
					case (6): $row['title'] = "[Misc] ".$row['title']; break;
					case (7): $row['title'] = "[Hack] ".$row['title']; break;
					default: $row['title'] = "[SUBMISSION] ".$row['title']; break;
				}
			} else {
				$row['url'] = $STD->encode_url($_SERVER['PHP_SELF']."?act=main&param=2&id={$row['rid']}#c{$row['cid']}");
				$row['title'] = "[UPDATE] ".$row['title'];
			}
			
			//format username
			$row['user'] = $row['name_prefix'].$row['username'].$row['name_suffix'];
			$row['user'] = "<a href='".$STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=01&uid={$row['uid']}")."'>{$row['user']}</a>";
			
			//Add each row for output
			$this->output .= $this->html->recent_comment_row($row); 
		}
		
		//----------------------------------------------
		//FOOTER
		//----------------------------------------------
		$this->output .= $this->html->recent_comment_foot();
		$this->output .= $STD->global_template->page_footer();
	}
}