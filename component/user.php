<?php
//------------------------------------------------------------------
// Penguinia Content Management System 1.0
//------------------------------------------------
// Copyright 2005 Justin Aquadro
// 
// component/user.php --
// Handles User CP and other user functions
//------------------------------------------------------------------

$component = new component_user;

class component_user {

	var $html 		= "";
	var $mod_html 	= "";
	var $output		= "";
	var $title		= "";
	
	function init () {
		global $IN, $STD;
		
		$this->html = $STD->template->useTemplate('user');
		
		if (!empty($IN['c'])) {
			$module = $STD->modules->get_module($IN['c']);
			$this->mod_html = $STD->template->useTemplate( $module['template'] );
		}
		
		switch ($IN['param']) {
			case 1: $this->show_user(); break;
			case 2: $this->show_ucp_prefs(); break;
			case 3: $this->show_manage_sub_list(); break;
			case 4: $this->do_edit_prefs(); break;
			case 5:	$this->get_email(); break;
			case 6: $this->show_manage_item(); break;
			case 7: $this->do_manage_item() ; break;
			case 8: $this->do_req_remove(); break;
			case 9: $this->show_public_user(); break;
			case 10: $this->show_user_comments(); break;
			case 11: $this->show_favorites(); break;
		}
		
		//$TPL->template = $this->output;
		$STD->template->display( $this->output, $this->title );
	}
	
	function show_user() {
		global $IN, $STD, $DB, $CFG;
		
		require_once ROOT_PATH.'lib/resource.php';
		
		$user = new user;
		$user->query_use('group');
		
		if (empty($IN['uid']) || !$user->get($IN['uid']))
			$STD->error("User does not exist.");
		
		$where = $DB->format_db_where_string(array('c.uid' => $user->data['uid'])); 
		$DB->query("SELECT c.rid, c.date, c.message, c.type, r.type as rt, c.uid FROM ".$CFG['db_pfx']."_comments c INNER JOIN ".$CFG['db_pfx']."_resources r ON (c.rid = r.rid)".
								   "WHERE $where ORDER BY date DESC");
		$count = $DB->get_num_rows();
		
		$RES = new resource;
		$RES->query_condition("r.uid = {$user->data['uid']}");
		$subs = $RES->countAll();
		
		$user->data['join_date'] = $STD->make_date_time($user->data['join_date']);
		$user->data['submissions'] = $subs['cnt'];
		$user->data['reviews'] = 0;
		
		$email = "<i>Not Provided</i>";
		if (!empty($user->data['email']) && $user->data['show_email']) {
			$email = $user->data['email'];
			$email = str_replace(' ', '%20', $email);
			$email = str_replace('@', ' _AT_ ', $email);
			$email = str_replace('.', ' _DOT_ ', $email);
			$email_im = "<img src='{$_SERVER['PHP_SELF']}?act=user&param=05&uid={$user->data['uid']}' alt='$email'>";
			$email = "<a href='mailto:$email'>$email_im</a>";
		}
		$user->data['email'] = $email;
		
		$website = "<i>Not Provided</i>";
		if (!empty($user->data['website']))
			$website = $user->data['website'];
		elseif (!empty($user->data['weburl']))
			$website = $user->data['weburl'];
		if (!empty($user->data['weburl']))
			$website = "<a href='{$user->data['weburl']}'>$website</a>";
		$user->data['website'] = $website;
		
		$user->data['aim'] = (!empty($user->data['aim'])) ? $user->data['aim'] : "<i>Not Available</i>";
		$user->data['icq'] = (!empty($user->data['icq'])) ? $user->data['icq'] : "<i>Not Provided</i>";
		$user->data['msn'] = (!empty($user->data['msn'])) ? $user->data['msn'] : "<i>Not Available</i>";
		$user->data['yim'] = (!empty($user->data['yim'])) ? $user->data['yim'] : "<i>Not Available</i>";
		$user->data['discord'] = (!empty($user->data['discord'])) ? $user->data['discord'] : "<i>Not Provided</i>";
		$user->data['twitter'] = (!empty($user->data['twitter'])) ? "<a href='https://twitter.com/".$user->data['twitter']."'>@".$user->data['twitter']."</a>" : "<i>Not Provided</i>";
		$user->data['twitch'] = (!empty($user->data['twitch'])) ? "<a href='https://twitch.tv/".$user->data['twitch']."'>".$user->data['twitch']."</a>" : "<i>Not Provided</i>";
		$user->data['youtube'] = (!empty($user->data['youtube'])) ? "<a href='https://youtube.com/".$user->data['youtube']."'>YouTube Channel</a>" : "<i>Not Provided</i>";
		$user->data['steam'] = (!empty($user->data['steam'])) ? "<a href='https://steamcommunity.com/id/".$user->data['steam']."'>".$user->data['steam']."</a>" : "<i>Not Provided</i>";
		$user->data['reddit'] = (!empty($user->data['reddit'])) ? "<a href='https://reddit.com/u/".$user->data['reddit']."'>/u/".$user->data['reddit']."</a>" : "<i>Not Provided</i>";
		$user->data['bluesky'] = (!empty($user->data['bluesky'])) ? "<a href='https://bsky.app/profile/".$user->data['bluesky'].".bsky.social'>".$user->data['bluesky']."</a>" : "<i>Not Provided</i>";
		$user->data['comments'] = $count;
		
		//get user avatar
		if (empty($user->data['icon']))
		{
			$user->data['icon'] = "<img id='uavatar' src='{$CFG['default_icon']}' alt='No Icon' class='avatar'>";
		}
		else
		{
			$img_url = preg_replace("/^http:/i", "https:", $user->data['icon']);
			$user->data['icon'] = "<img id='uavatar' src='{$img_url}' alt='User Icon' class='avatar' onerror='this.src=".'"'.$CFG['default_icon_defective'].'";'."'>";
		}
		
		$this->output .= $STD->global_template->page_header($user->data['username']);
		$this->output .= $this->html->userpage($user->data);
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function show_ucp_prefs() {
		global $STD, $CFG, $DB, $session;
		
		require_once ROOT_PATH.'lib/userlib.php';
		
		$auth = new session;
		$auth_error = $auth->authorize();
		
		
		
		if (!$STD->user['uid'])
		{
			$fail = '';
			if (!empty($STD->sess_failure))
			{
				$fail = "<br> Error code: ".$STD->sess_failure;
			}
			$STD->error("You must be registered to access account preferences. This could also caused by bad cookie/session. If you are already logged in, please try clearing your browser cache and cookies. And also try log out and log back in. (uid: ".$STD->user['uid'].") ".$fail);
		}
		
		// We need to generate our skins list
		$query = $DB->query("SELECT sid,name FROM {$CFG['db_pfx']}_skins WHERE hidden = '0'");
		
		$skin_val = array(0); $skin_name = array('---');
		while ($srow = $DB->fetch_row()) {
			$skin_val[] = $srow['sid'];
			$skin_name[] = $srow['name'];
		}
		
		$form_elements = array();
		
		$form_elements['order_by'] = $STD->make_select_box('def_order_by', array('','d','t','a','u'), array('---','Date','Title','Author','Last Update'), $STD->user['def_order_by'], 'selectbox');
		$form_elements['order'] = $STD->make_select_box('def_order', array('','a','d'), array('---','Ascending Order','Descending Order'), $STD->user['def_order'], 'selectbox');
		$form_elements['skin'] = $STD->make_select_box('skin', $skin_val, $skin_name, $STD->user['skin'], 'selectbox');
		$form_elements['items'] = $STD->make_select_box('items_per_page', array('0','20','40','60','80','100'), array('---','20','40','60','80','100'), $STD->user['items_per_page'], 'selectbox');
		$form_elements['show_email'] = $STD->make_yes_no('show_email', $STD->user['show_email']);
		$form_elements['timezone'] = $STD->timezone_box($STD->user['timezone']);
		$form_elements['dst'] = $STD->make_checkbox('dst', 1, $STD->user['dst']);
		$form_elements['time'] = $STD->make_date_time(time());
		$form_elements['max_dims'] = $CFG['max_icon_dims'];
		$form_elements['show_thumbs'] = $STD->make_yes_no('show_thumbs', $STD->user['show_thumbs']);
		$form_elements['use_comment_msg'] = $STD->make_yes_no('use_comment_msg', $STD->user['use_comment_msg']);
		$form_elements['use_comment_digest'] = $STD->make_yes_no('use_comment_digest', $STD->user['use_comment_digest']);
		
		if (empty($STD->user['icon_dims'])) {
			$STD->user['icon_dimw'] = '';
			$STD->user['icon_dimh'] = '';
		} else {
			$dims = explode("x", $STD->user['icon_dims']);
			$STD->user['icon_dimw'] = $dims[0];
			$STD->user['icon_dimh'] = $dims[1];
		}
		
		//$this->output .= $STD->global_template->page_header('Preferences');
		$this->title = "Preferences";
		$this->output .= $this->html->prefs_page($STD->user, $form_elements, $STD->make_form_token());
		//$this->output .= $STD->global_template->page_footer();
	}
	
	function do_edit_prefs () {
		global $STD, $DB, $IN, $CFG;
		
		$updates = array();
		
		// Validation
		if (!$STD->validate_form($IN['security_token']))
			$STD->error("The update request did not originate from this site, or your request has already been processed.");
		
		require_once ROOT_PATH.'lib/userlib.php';
	
	$auth = new session;
		$auth_error = $auth->authorize();
		
		if (!$STD->user['uid'])
			$STD->error("You must be registered to perform this action.");
	
		if ($STD->user['uid'] != $IN['uid'])
			$STD->error("Attempt to modify another user's account data.");
		
		if (!preg_match($STD->get_regex('email'), $IN['email']))
			$STD->error("Email address is invalid.");
		
		/*if (!preg_match($STD->get_regex('url'), $IN['weburl']) || empty($IN['website']))
			$IN['weburl'] = '';
		
		if (!preg_match($STD->get_regex('url'), $IN['icon']))
			$IN['icon'] = '';*/
		
		if ($IN['dimw'] != "" || $IN['dimh'] != "") {
			$max_dims = explode("x", $CFG['max_icon_dims']);
		
			$IN['dimw'] = max($IN['dimw'], 1);
			$IN['dimw'] = min($IN['dimw'], $max_dims[0]);
			$IN['dimh'] = max($IN['dimh'], 1);
			$IN['dimh'] = min($IN['dimh'], $max_dims[1]);
		}
		
		// Password Change
		if (!empty($IN['opass']) || !empty($IN['npass1']) || !empty($IN['npass2'])) {
			if (strlen($IN['npass1']) < 8) {
				$STD->error("Your password needs to be 8 characters or more.");
			}
			if (!preg_match("@[0-9]@", $IN['npass1'])) {
				$STD->error("Your password must include at least one number!");
			}
			if (!preg_match("@[a-zA-Z]@", $IN['npass1'])) {
				$STD->error("Your password must include at least one letter!");
			}     
			if (empty($IN['opass']))
				$STD->error("You must type in your old password to change your password.");
			if (empty($IN['npass1']))
				$STD->error("You must provide a new password to change your password.  If you do not want to change your password, leave all password fields blank.");
			if (empty($IN['npass2']))
				$STD->error("You must retype your new password to change your password.");
			if ($IN['npass1'] != $IN['npass2'])
				$STD->error("Your new passwords did not match.  Please retype them.");
			if (!password_verify($IN['opass'], $STD->user['password']))
				$STD->error("Your old password was incorrect.  You must provide a correct password to change your password.");
			$updates['password'] = password_hash($IN['npass1'], PASSWORD_DEFAULT);
			$updates['new_password'] = 1;
		}
		
		if (empty($IN['dst']))
			$IN['dst'] = 0;
		
		$updates['email'] = $IN['email'];
		$updates['website'] = $IN['website'];
		$updates['weburl'] = $IN['weburl'];
		$updates['icon'] = $IN['icon'];
		/*$updates['icq'] = $IN['icq'];
		$updates['aim'] = $IN['aim'];
		$updates['msn'] = $IN['msn'];
		$updates['yim'] = $IN['yim'];*/
		$updates['discord'] = $IN['discord'];
		$updates['twitter'] = $IN['twitter'];
		$updates['twitch'] = $IN['twitch'];
		$updates['youtube'] = $IN['youtube'];
		$updates['reddit'] = $IN['reddit'];
		$updates['steam'] = $IN['steam'];
		$updates['bluesky'] = $IN['bluesky'];
		$updates['def_order_by'] = $IN['def_order_by'];
		$updates['def_order'] = $IN['def_order'];

		if ($IN['website'] == "SMFGG") {
			$updates['skin'] = 9;
		}
		else  {
			$updates['skin'] = $IN['skin'];
		}
		
		$updates['items_per_page'] = $IN['items_per_page'];
		$updates['show_email'] = $IN['show_email'];
		$updates['timezone'] = $IN['timezone'];
		$updates['dst'] = $IN['dst'];
		$updates['icon_dims'] = "{$IN['dimw']}x{$IN['dimh']}";
		$updates['show_thumbs'] = $IN['show_thumbs'];
		$updates['use_comment_msg'] = $IN['use_comment_msg'];
		$updates['use_comment_digest'] = $IN['use_comment_digest'];
		
		$fields = $DB->format_db_update_values($updates);
		$where = $DB->format_db_where_string(array('uid'	=> $IN['uid']));
		$DB->query("UPDATE ".$CFG['db_pfx']."_users SET ".$fields." WHERE ".$where);
		//$DB->query("UPDATE {$CFG['db_pfx']}_users SET $fields WHERE $where");
		
		//------------------------------------------------
		// Output
		//------------------------------------------------
		
		$url = $STD->encode_url($_SERVER['PHP_SELF']);
		$url2 = $STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=02");
		
	//	$this->output .= $this->html->page_header();
		$this->output .= $STD->global_template->message("Your account preferences were updated successfully.
								 <p align='center'><a href='$url2'>Return to User Preferences</a><br>
								 <a href='$url'>Return to the main page</a></p>");
	//	$this->output .= $this->html->page_footer();
		
		$STD->clear_form_token();
	}
	
	function show_manage_sub_list () {
		global $STD, $CFG, $DB, $IN;
		
		require_once ROOT_PATH.'lib/resource.php';
		require_once ROOT_PATH.'lib/userlib.php';
		$auth = new session;
		$auth_error = $auth->authorize();
		
		
		if (!$STD->user['can_modify'])
			$STD->error("You do not have permission to modify your submissions. (uid: ".$STD->user['uid'].", can_modify: ".$STD->user['can_modify'].")".$auth_error);
		
		if (empty($IN['st']))
			$IN['st'] = 0;
		
		if (empty($IN['o']))
			$IN['o'] = null;
		
		// Should we re-format the order?
		if (!empty($IN['o1'])) {
			$order = "{$IN['o1']},{$IN['o2']}";
			
			$url = $STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=03&c={$IN['c']}&st={$IN['st']}&o=$order");
			$url = str_replace("&amp;", "&", $url);
			header("Location: $url");
			exit;
		}
		
		// Did we change the target?
		if (!empty($_POST['c'])) {
			$url = $STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=03&c={$IN['c']}&st={$IN['st']}&o={$IN['o']}");
			$url = str_replace("&amp;", "&", $url);
			header("Location: $url");
			exit;
		}

		//------------------------------------------------
		// Make sure we have a default module
		//------------------------------------------------
		
		$module_record = null;
		$STD->modules->load_module_list();
		
		$name_arr = array(); $val_arr = array();
		reset($STD->modules->module_set);
		//while (list(,$row) = each ($STD->modules->module_set)) {
		foreach ( $STD->modules->module_set as $row ) {
			$val_arr[] = $row['mid'];
			$name_arr[] = $row['full_name'];

			if ((empty($module_record) && empty($IN['c'])) || $row['mid'] == $IN['c']) {
				$module_record = $row;
				$module = $STD->modules->new_module($row['mid']);

				$this->mod_html = $STD->template->useTemplate( $module_record['template'] );
			}
		}
		
		if (empty($IN['c']))
			$IN['c'] = $val_arr[0];
		
		$js = "onchange=\"if(this.options[this.selectedIndex].value != -1){ document.changetype.submit() }\"";
		$type_list = $STD->make_select_box('c', $val_arr, $name_arr, $IN['c'], 'selectbox', $js);
		
		$module->init();
		
		//------------------------------------------------
		// Ordering and stuff
		//------------------------------------------------
		
		$order_names = array('t' => 'Title', 'a' => 'Author', 'd' => 'Date', 'u' => 'Updated');
		$order_list = array('t' => 'r.title', 'a' => "CONCAT(r.author_override,IFNULL(ru.username,''))",
						    'd' => 'r.rid', 'u' => 'IF(r.updated>0,r.updated,r.rid)');
		$order_default = array($CFG['default_order_by'], $CFG['default_order']);
		
		$ex_order = $module->extra_order();
		$order_names = array_merge($order_names, $ex_order[0]);
		$order_list = array_merge($order_list, $ex_order[1]);
		
		// Set some defaults for the order boxes
		if (!empty($STD->user['order_def_by']))
			$order_default[0] = $STD->user['order_def_by'];
		if (!empty($STD->user['order_def']))
			$order_default[1] = $STD->user['order_def'];
		if (!empty($IN['o']))
			$order_default = explode(',', $IN['o']);
		
		$order = $STD->order_translate( $order_list, $order_default );
		
		//------------------------------------------------
		// Start Page
		//------------------------------------------------
		
		$this->output .= $STD->global_template->page_header('My Submissions');
		$this->output .= $this->html->manage_type_row($type_list);
		
		$this->output .= $this->html->manage_start_rows();
		
		//------------------------------------------------
		// Resource Rows
		//------------------------------------------------
		
		$RES = new resource;
		$RES->query_use('extention', $module_record['mid']);
		$RES->query_use('r_user');
		$RES->query_order($order[0], $order[1]);
		$RES->query_limit($IN['st'], $STD->get_page_prefs());
		$RES->query_condition("r.uid = '{$STD->user['uid']}' AND r.queue_code <> 5");
		$RES->getByType($IN['c']);
		
		$rowlist = array();

		while ($RES->nextItem()) {
			$data = $module->resdb_prep_data($RES->data);
			if ($RES->data['queue_code'] > 0)
				$data['title'] .= $this->html->queue_text();
				
			$this->output .= $this->mod_html->manage_row($data, $IN['c']);
		}

		$DB->free_result();
		
		//------------------------------------------------
		// Page numbering and ordering
		//------------------------------------------------
		
		$order_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=03&c={$IN['c']}&st={$IN['st']}");
		$order_p = join(',', $order_default);
		
		$rcnt = $RES->countByType($IN['c']);
		$pages = $STD->paginate($IN['st'], $rcnt['cnt'], $STD->get_page_prefs(), "act=user&param=03&c={$IN['c']}&o={$IN['o']}");
		
		$selbox1 = $STD->make_select_box('o1', array_keys($order_names), array_values($order_names), $order_default[0], 'selectbox');
		$selbox2 = $STD->make_select_box('o2', array('a','d'), array('Ascending Order','Descending Order'), $order_default[1], 'selectbox');
		
		$this->output .= $this->html->manage_end_rows($pages, "$selbox1 $selbox2", $order_url);
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function get_email() {
		global $STD, $DB, $IN, $CFG;
		
		$user = new user;
		
		if (empty($IN['uid']) || !$user->get($IN['uid']))
			$STD->error("User does not exist.");
		
		if (empty($user->data['email']) || !$user->data['show_email'])
			$STD->error("This user's email address is not available.");
			
		$imglen = strlen($user->data['email'])*7 + 2;
		
		// Create Email image
		$im = imagecreate($imglen, 14);
		$bg = imagecolorallocate($im, 255, 255, 255);
		$txtcolor = imagecolorallocate($im, 0, 0, 0);
		
		imagestring($im, 3, 1, 0, $user->data['email'], $txtcolor);
		
		header("Content-type: image/png");
		imagepng($im);
		exit;
	}
	
	function show_manage_item () {
		global $STD, $DB, $IN, $CFG;
		
		require_once ROOT_PATH.'lib/resource.php';
		
		if (!$STD->user['can_modify'])
			$STD->error("You do not have permission to modify your submissions.");
		
		$module_record = $STD->modules->get_module($IN['c']);
		
		$RES = new resource;
		$RES->query_use('extention', $module_record['mid']);
		$RES->query_use('r_user');
		if (!$RES->get($IN['rid']))
			$STD->error("Invalid resource selected");
		
		if ($RES->data['ghost'] > 0) {
			if (!$RES->get($RES->data['ghost']))
				$STD->error("Invalid ghost data encountered");
			$RES->data['rid'] = $IN['rid'];
		}
		
		if ($RES->data['uid'] != $STD->user['uid'])
			$STD->error("You do not have permission to modify this submission.");
		
		//------------------------------------------------
		// Format Data
		//------------------------------------------------
		
		$module = $STD->modules->new_module($IN['c']);
		$module->init();
		
		$data = $module->manage_prep_data($RES->data);
		
		//------------------------------------------------
		// Output
		//------------------------------------------------
		
		$this->output .= $STD->global_template->page_header('Modify Submission');
		
		$this->output .= $this->mod_html->manage_page($data, $STD->make_form_token(), $module->get_max_sizes());
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function do_manage_item() {
		global $STD, $DB, $IN, $CFG;
		
		// Are we redirecting?
		if (!empty($IN['rem'])) {
			$STD->clear_form_token();
			$this->show_remove();
			return;
		}
		
		// No - carry on
		if (!$STD->validate_form($IN['security_token']))
			$STD->error("The submission request did not originate from this site, or you attempted to repeat a completed transaction.");
		
		//if (!$STD->user['can_modify'])
		//	$STD->error("You do not have permission to modify your submissions.");
		
		$module = $STD->modules->new_module($IN['c']);
		if (!$module)
			$STD->error("Suitable module could not be found.");
		
		// Raw clean values (Remember to undo before display!)
		
		if (isset($IN['title']))
			$IN['title'] = $STD->rawclean_value($_POST['title']);
		
		$module->init();
		
		$module->user_manage_data_check();
		$RES = $module->user_update_manage_data();
		
		//------------------------------------------------
		// Output
		//------------------------------------------------
		
	//	$this->output .= $this->html->page_header();

		$username = htmlspecialchars($STD->user['username']);
		$url = $STD->encode_url($_SERVER['PHP_SELF']);
		$message = "Thank you, $username.  Your submission has been updated.  Your submission has been placed back 
			in the moderation queue for approval.  If there is a problem with your changes, your submission will be 
			rolled back to its previous state.  In the meantime, you can continue to make changes to your submission.
			<p align='center'><a href='$url'>Return to the main page</a></p>";
		
		$this->output .= $STD->global_template->message($message);
		
	//	$this->output .= $this->html->page_footer();
		
		$STD->clear_form_token();
	}
	
	function show_remove () {
		global $STD, $IN;
		
		require_once ROOT_PATH.'lib/resource.php';
		
		if (empty($IN['reason']))
			$IN['reason'] = '';
			
		$RES = new resource;
		$RES->query_use('r_user');
		
		if (!$RES->get($IN['rid']))
			$STD->error("Could not find resource.");
		
		if ($STD->user['uid'] != $RES->data['uid'])
			$STD->error("You cannot remove submissions that don't belong to you.");
		
		$form_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=08");
		
		$this->output .= $STD->global_template->page_header('Request Removal');
		
		$this->output .= $this->html->request_remove($IN['rid'], $RES->data['title'], $form_url, $IN['reason']);
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function do_req_remove () {
		global $STD, $IN;
		
		require_once ROOT_PATH.'lib/resource.php';
		require_once ROOT_PATH.'lib/message.php';
		
		if (empty($IN['rid']))
			$STD->error("No resource specified.");
		
		$RES = new resource;
		
		if (!$RES->get($IN['rid']))
			$STD->error("Could not find resource.");
		
		if ($STD->user['uid'] != $RES->data['uid'])
			$STD->error("You cannot remove submissions that don't belong to you.");
		
		if (empty($IN['reason']))
			$STD->error("You must provide justification for your request.");
		
		$ACPM = new acp_message;
			
		$ACPM->data['sender'] = $STD->user['uid'];
		$ACPM->data['date'] = time();
		$ACPM->data['title'] = "Removal Request: {$RES->data['title']}";
		$ACPM->data['message'] = $STD->limit_string($IN['reason'], 10240);
		$ACPM->data['type'] = 5;
		$ACPM->data['aux'] = $IN['rid'];
			
		$ACPM->insert();
		
		//------------------------------------------------
		// Message
		//------------------------------------------------

		$url = $STD->encode_url($_SERVER['PHP_SELF']);
		
		$message = "Your request has been sent to the site staff for review.
			<p align='center'><a href='$url'>Return to the main page</a></p>";
		
		$this->output .= $STD->global_template->message($message);
	}
	
	//A Copy of show_manage_sub_list for public, take in a uid ***************
	function show_public_user () {
		global $STD, $CFG, $DB, $IN;
		
		require_once ROOT_PATH.'lib/resource.php';
		
		//Taken from show_user()**************
		$user = new user;
		$user->query_use('group');
		
		if (empty($IN['uid']) || !$user->get($IN['uid']))
			$STD->error("User does not exist.");		
		//End Taken from show_user()*************

		//Public page won't need this check*******************
		//if (!$STD->user['can_modify'])
		//	$STD->error("You do not have permission to modify your submissions.");
		
		if (empty($IN['st']))
			$IN['st'] = 0;
		
		if (empty($IN['o']))
			$IN['o'] = null;
		
		// Should we re-format the order?
		if (!empty($IN['o1'])) {
			$order = "{$IN['o1']},{$IN['o2']}";
			
			$url = $STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=09&c={$IN['c']}&st={$IN['st']}&o=$order&uid={$user->data['uid']}");
			$url = str_replace("&amp;", "&", $url);
			header("Location: $url");
			exit;
		}
		
		// Did we change the target?
		if (!empty($_POST['c'])) {
			$url = $STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=09&c={$IN['c']}&st={$IN['st']}&o={$IN['o']}&uid={$user->data['uid']}");
			$url = str_replace("&amp;", "&", $url);
			header("Location: $url");
			exit;
		}

		//------------------------------------------------
		// Make sure we have a default module
		//------------------------------------------------
		
		$module_record = null;
		$STD->modules->load_module_list();
		
		$name_arr = array(); $val_arr = array();
		reset($STD->modules->module_set);
		//while (list(,$row) = each ($STD->modules->module_set)) {
		foreach ( $STD->modules->module_set as $row ) {  // 3/22/2025 Vinny PHP fix
			$val_arr[] = $row['mid'];
			$name_arr[] = $row['full_name'];

			if ((empty($module_record) && empty($IN['c'])) || $row['mid'] == $IN['c']) {
				$module_record = $row;
				$module = $STD->modules->new_module($row['mid']);

				$this->mod_html = $STD->template->useTemplate( $module_record['template'] );
			}
		}
		
		if (empty($IN['c']))
			$IN['c'] = $val_arr[0];
		
		$js = "onchange=\"if(this.options[this.selectedIndex].value != -1){ document.changetype.submit() }\"";
		$type_list = $STD->make_select_box('c', $val_arr, $name_arr, $IN['c'], 'selectbox', $js);
		
		$module->init();
		
		//------------------------------------------------
		// Ordering and stuff
		//------------------------------------------------
		
		$order_names = array('t' => 'Title', 'a' => 'Author', 'd' => 'Date', 'u' => 'Updated');
		$order_list = array('t' => 'r.title', 'a' => "CONCAT(r.author_override,IFNULL(ru.username,''))",
						    'd' => 'r.rid', 'u' => 'IF(r.updated>0,r.updated,r.rid)');
		$order_default = array($CFG['default_order_by'], $CFG['default_order']);
		
		$ex_order = $module->extra_order();
		$order_names = array_merge($order_names, $ex_order[0]);
		$order_list = array_merge($order_list, $ex_order[1]);
		
		// Set some defaults for the order boxes
		if (!empty($STD->user['order_def_by']))
			$order_default[0] = $STD->user['order_def_by'];
		if (!empty($STD->user['order_def']))
			$order_default[1] = $STD->user['order_def'];
		if (!empty($IN['o']))
			$order_default = explode(',', $IN['o']);
		
		$order = $STD->order_translate( $order_list, $order_default );
		
		//------------------------------------------------
		// Start Page
		//------------------------------------------------
		
		//*********************User's Name Added******************************
		$this->output .= $STD->global_template->page_header($user->data['username'].'\'s Submissions');
		$this->output .= $this->html->public_type_row($type_list, $user->data['uid']);
		
		$this->output .= $this->html->manage_start_rows();
		
		//------------------------------------------------
		// Resource Rows
		//------------------------------------------------
		
		$RES = new resource;
		$RES->query_use('extention', $module_record['mid']);
		$RES->query_use('r_user');
		$RES->query_order($order[0], $order[1]);
		$RES->query_limit($IN['st'], $STD->get_page_prefs());
		$RES->query_condition("r.uid = '{$user->data['uid']}' AND r.queue_code IN (0,2)"); //UID from URL***********
		$RES->getByType($IN['c']);
		
		$rowlist = array();

		while ($RES->nextItem()) {
			$data = $module->resdb_prep_data($RES->data);

			$this->output .= $this->mod_html->public_row($data, $IN['c']); //****manage_row will change to public_row, an dI will make Templates for each type of submission.
		}

		$DB->free_result();
		
		//------------------------------------------------
		// Page numbering and ordering
		//------------------------------------------------
		
		$order_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=09&c={$IN['c']}&st={$IN['st']}&uid={$user->data['uid']}");
		$order_p = join(',', $order_default);
		
		$rcnt = $RES->countByType($IN['c']);
		$pages = $STD->paginate($IN['st'], $rcnt['cnt'], $STD->get_page_prefs(), "act=user&param=09&c={$IN['c']}&o={$IN['o']}&uid={$user->data['uid']}");
		
		$selbox1 = $STD->make_select_box('o1', array_keys($order_names), array_values($order_names), $order_default[0], 'selectbox');
		$selbox2 = $STD->make_select_box('o2', array('a','d'), array('Ascending Order','Descending Order'), $order_default[1], 'selectbox');
		
		$this->output .= $this->html->manage_end_rows($pages, "$selbox1 $selbox2", $order_url);
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	//User's comments (Written by Hypernova with Retriever II's help)
	function show_user_comments () {
		//init
		global $IN, $STD, $DB, $CFG;
		require_once ROOT_PATH.'lib/resource.php';
		require_once ROOT_PATH.'lib/message.php';
		
		//get user obj
		$user = new user;
		$user->query_use('group');
		
		//USER DOESN'T EXISTS
		if (empty($IN['uid']) || !$user->get($IN['uid']))
			$STD->error("User does not exist.");
		
		//DB QUERY OBJ BUILD
		$that = new comment;
		$that->query_build();
		
		
		//QUERY
		$where = $DB->format_db_where_string(array('c.uid' => $user->data['uid'])); 
		$DB->query("SELECT c.rid, c.date, c.message, c.type, r.type as rt, c.uid, c.cid FROM ".$CFG['db_pfx']."_comments c INNER JOIN ".$CFG['db_pfx']."_resources r ON (c.rid = r.rid)".//SELECT rid, date, message, type FROM ".$CFG['db_pfx']."_comments ". 
								   "WHERE $where ORDER BY date DESC");
		$count = $DB->get_num_rows();
		
		//calculate
		$limit = $STD->user['items_per_page']; //max items per page
		if (empty($STD->user['items_per_page']))
		{
			$limit = 20;
		}
		
		//count number of pages
		$pages = floor(intval($count)/$limit); //minus the remainder
		$pages_modulo = intval($count) % $limit; //get remainder
		if ($pages_modulo > 0) //if there's a remainder
		{
			$pages += 1; //add a page for remainder
		}
		
		//current page
		$page = 1; //default
		if (!empty($IN['page']))
		{
			$page = intval($IN['page']);
			$page = min(max($page,1),$pages); //keep $page in range
		}
		
		//get contents from a specific page based on the page # coord
		$nn = 0;
		$outcome = array();
		$final_count = 0;
		while ($row = $DB->fetch_row())
		{
			if (($nn >= ($page-1)*intval($limit)) && ($nn < $page*intval($limit)))
			{
				if ($page == $pages)
				{
					if ($nn < ($pages*intval($limit))-(intval($limit)-$pages_modulo))//if ($nn < ($pages*intval($limit))-$pages_modulo)
					{
						array_push($outcome,$row);
					}
					$final_count = $pages_modulo;
				}
				else
				{
					array_push($outcome,$row);
					$final_count = intval($limit);
				}
			}
			$nn ++;
		}
		
		//output
		$this->output .= $STD->global_template->page_header($user->data['username']."'s Comments");
		$this->output .= $this->html->usercomment($outcome,$final_count,$page,$pages);
		//$this->output .= 'Limit: '.$limit."<br>";
		//$this->output .= 'Rows: '.$count."<br>";
		//$this->output .= 'Page: '.$page."<br>";
		//$this->output .= 'Pages: '.$pages."<br>";
		//$this->output .= 'Stuffs: '.implode(",",$outcome[0])."<br>";
		//$this->output .= 'Row counts: '.$count."<br>";
		//$this->output .= 'SQL counts: '.$nn."<br>";
		//$this->output .= 'Type: '.gettype($outcome[0])."<br>";
		//$this->output .= 'Array Size: '.sizeof($outcome[0])."<br>";
		//$this->output .= 'Stuffs: '.implode(",",$outcome[0])."<br>";
		//$this->output .= 'Row counts: '.$count."<br>";
		//$this->output .= 'SQL counts: '.$nn."<br>";
		$this->output .= $STD->global_template->page_footer();
	}
	
	//List of users (Written by Hypernova) -- WIP
	function show_user_list () {
		/*
		//init
		global $IN, $STD, $DB, $CFG;
		require_once ROOT_PATH.'lib/resource.php';
		require_once ROOT_PATH.'lib/message.php';
		
		//get user obj
		$user = new user;
		$user->query_use('group');
		
		//USER DOESN'T EXISTS
		if (empty($IN['uid']) || !$user->get($IN['uid']))
			$STD->error("User does not exist.");
		
		//DB QUERY OBJ BUILD
		$that = new comment;
		$that->query_build();
		
		
		//QUERY
		$where = $DB->format_db_where_string(array('c.uid' => $user->data['uid'])); 
		$DB->query("SELECT c.rid, c.date, c.message, c.type, r.type as rt, c.uid, c.cid FROM ".$CFG['db_pfx']."_comments c INNER JOIN ".$CFG['db_pfx']."_resources r ON (c.rid = r.rid)".//SELECT rid, date, message, type FROM ".$CFG['db_pfx']."_comments ". 
								   "WHERE $where ORDER BY date DESC");
		$count = $DB->get_num_rows();
		
		//calculate
		$limit = $STD->user['items_per_page']; //max items per page
		if (empty($STD->user['items_per_page']))
		{
			$limit = 20;
		}
		
		//count number of pages
		$pages = floor(intval($count)/$limit); //minus the remainder
		$pages_modulo = intval($count) % $limit; //get remainder
		if ($pages_modulo > 0) //if there's a remainder
		{
			$pages += 1; //add a page for remainder
		}
		
		//current page
		$page = 1; //default
		if (!empty($IN['page']))
		{
			$page = intval($IN['page']);
			$page = min(max($page,1),$pages); //keep $page in range
		}
		
		//get contents from a specific page based on the page # coord
		$nn = 0;
		$outcome = array();
		$final_count = 0;
		while ($row = $DB->fetch_row())
		{
			if (($nn >= ($page-1)*intval($limit)) && ($nn < $page*intval($limit)))
			{
				if ($page == $pages)
				{
					if ($nn < ($pages*intval($limit))-(intval($limit)-$pages_modulo))//if ($nn < ($pages*intval($limit))-$pages_modulo)
					{
						array_push($outcome,$row);
					}
					$final_count = $pages_modulo;
				}
				else
				{
					array_push($outcome,$row);
					$final_count = intval($limit);
				}
			}
			$nn ++;
		}
		
		//output
		$this->output .= $STD->global_template->page_header($user->data['username']."'s Comments");
		$this->output .= $this->html->usercomment($outcome,$final_count,$page,$pages);
		//$this->output .= 'Limit: '.$limit."<br>";
		//$this->output .= 'Rows: '.$count."<br>";
		//$this->output .= 'Page: '.$page."<br>";
		//$this->output .= 'Pages: '.$pages."<br>";
		//$this->output .= 'Stuffs: '.implode(",",$outcome[0])."<br>";
		//$this->output .= 'Row counts: '.$count."<br>";
		//$this->output .= 'SQL counts: '.$nn."<br>";
		//$this->output .= 'Type: '.gettype($outcome[0])."<br>";
		//$this->output .= 'Array Size: '.sizeof($outcome[0])."<br>";
		//$this->output .= 'Stuffs: '.implode(",",$outcome[0])."<br>";
		//$this->output .= 'Row counts: '.$count."<br>";
		//$this->output .= 'SQL counts: '.$nn."<br>";
		$this->output .= $STD->global_template->page_footer();*/
	}
	
	//Show my favorites (Written by Hypernova - 2020)
	function show_favorites () {
		//SECTION 1 -------------------------------- init important global stuffs
		global $STD, $IN, $CFG, $DB;
		require_once ROOT_PATH.'lib/resource.php';
		//require_once ROOT_PATH.'lib/database.php';//db_drivers/mysql.php';
		require_once ROOT_PATH.'template/base/submission_list.php';
		
		//check if user is logged in or not
		if (!$STD->user['uid']) {
			$STD->error("You must be logged in to check your favorites.");
		}
		
		//SECTION 2 -------------------------------- use query to grab the current submission type
		$r_table = "";
		$SUB_ERROR = false;
		if (empty($IN['c'])) {
			if (!empty($_POST['c'])) {
				$IN['c'] = $_POST['c'];
			}
			else { $IN['c'] = 1; }
		}
		switch ($IN['c']) {
			case(1): $r_table = "gfx"; break;
			case(2): $r_table = "games"; break;
			case(4): $r_table = "howtos"; break;
			case(5): $r_table = "sounds"; break;
			case(6): $r_table = "misc"; break;
			case(7): $r_table = "hacks"; break;
			default: $SUB_ERROR = true; break; //INCOMPATIBLE SUBMISSIONS (3 counts as well since it's a review)
		}
		if ($SUB_ERROR == true) {
			$STD->error("Invalid submission selected for favorites!");
		}
		
		//SECTION 3 -------------------------------- Sorting purpose
		$sort = 'r.created';
		if (!empty($IN['sort']) || !empty($_POST['sort'])) {
			switch ($IN['sort']) {
				case(1): $sort = 'r.title'; break;
				case(2): $sort = 'u.username'; break;
				case(3): $sort = 'r.created'; break;
				case(4): $sort = 'r.updated'; break;
				case(5): $sort = 's.downloads'; break;
				case(6): $sort = 's.views'; break;
			}
		}
		else {
			$IN['sort'] = 0;
		}
		$order = "DESC";
		if (!empty($IN['asc']) || !empty($_POST['asc'])) {
			if ($IN['asc'] == 1) {
				$order = "ASC";
			}
		}
		else {
			$IN['asc'] = 0;
		}
		
		//SECTION 4 -------------------------------- run the query
		$query = "SELECT ".
				"r.rid, ".
				"r.title, ".
				"r.description, ".
				"r.author_override, ".
				"r.website_override, ".
				"r.weburl_override, ".
				"r.created, ".
				"r.updated, ".
				"r.uid, ".
				"u.username, ".
				"g.name_prefix, ".
				"g.name_suffix, ".
				"b.type AS c, ".
				"s.* ".
			" FROM {$CFG['db_pfx']}_bookmarks AS b ".
				" INNER JOIN {$CFG['db_pfx']}_resources AS r ON (b.rid = r.rid) ".
				" LEFT OUTER JOIN {$CFG['db_pfx']}_users AS u ON (u.uid = r.uid) ".
				" INNER JOIN {$CFG['db_pfx']}_res_{$r_table} AS s ON (s.eid = r.eid) ".
				" LEFT OUTER JOIN {$CFG['db_pfx']}_groups g ON (g.gid = u.gid) ".
			" WHERE b.type='{$IN['c']}' AND b.uid='{$STD->user['uid']}' ".
			" ORDER BY {$sort} {$order}";
		$DB->query($query);
		$count = $DB->get_num_rows(); //how many rows this query ends up with? Useful for determine pages.
		
		//SECTION 5 -------------------------------- Calculate the current page, number of pages needed
		//determine the number of items needed per page
		$limit = $STD->user['items_per_page']; //max items per page
		if (empty($STD->user['items_per_page'])) {
			$limit = 20;
		}
		//determine the number of pages to show them all
		$data = array();
		$data['pages'] = floor(intval($count) / $limit) - 1; //minus the remainder
		$pages_modulo = intval($count) % $limit; //get remainder
		if ($pages_modulo > 0) { //if there's a remainder
			$data['pages'] += 1; //add a page for remainder
		}
		//current page
		$data['page'] = 0; //default
		if (!empty($IN['st']))
		{
			$data['page'] = round(intval($IN['st']) / $limit);
			$data['page'] = min(max($data['page'], 0), $data['pages']); //keep $page in range
		}
		else { $IN['st'] = 0; }
		
		
		//SECTION 6 -------------------------------- BEFORE pulling data - make sure the essential functions are ready
		function fav_row_pull ($result) {
			global $STD, $IN;
			//***** FORMAT ***** part 1 ***** Begin formatting the commonly used elements
			//format username
			$result['author'] = '<b>N/A</b>';
			if (!empty($result['username']) && !empty($result['uid']) && $result['uid'] > 0)
				$result['author'] = $result['name_prefix'].$result['username'].$result['name_suffix'];
			if (!empty($result['author_override'])) 
				$result['author'] = $result['name_prefix'].$result['author_override'].$result['name_suffix'];
			if (!empty($result['uid']) && $result['uid'] > 0) 
				$result['author'] = "<a href='".$STD->encode_url($_SERVER['PHP_SELF'], "act=user&param=01&uid={$result['uid']}")."'>{$result['author']}</a>";
			//format dates
			$result['created'] = $STD->make_date_short($result['created']);
			if ($result['updated'] == 0) {
				$result['updated'] = '';
			}
			else {
				$result['updated'] = "Updated: ".$STD->make_date_short($result['updated']);
			}
			//bug fix
			$result['type_title'] = '';
			//limit description length
			$result['description'] = $STD->nat_substr($result['description'], 250).' ...';
			
			//***** FORMAT ***** part 2 ***** Format each of the submissions on the list
			//format GFX, Games, Hacks 
			if (($IN['c'] == 1) || ($IN['c'] == 2) || ($IN['c'] == 7)) {
				$thumb_file = $result['thumbnail'];
				$result['thumbnail'] = "<img src='{$STD->tags['root_path']}/thumbnail/{$IN['c']}/{$thumb_file}' alt='Thumbnail'></img>"; //Thumbnail
				return format_favrow_thumb($result); //OUTPUT - use $list .= fav_row_pull($result);
			}
			//format How-Tos
			if ($IN['c'] == 4) {
				return format_favrow_plain($result); //OUTPUT - use $list .= fav_row_pull($result);
			}
			//format Sounds, Misc
			if (($IN['c'] == 5) || ($IN['c'] == 6)) {
				$ico_file = $result['type1'];
				$result['type1'] = "<img src='{$STD->tags['root_path']}/template/modules/{$IN['c']}/{$ico_file}.gif' img='Icon'></img>"; //Thumbnail
				return format_favrow_icon($result); //OUTPUT - use $list .= fav_row_pull($result);
			}
		}
		function line_trim($str, $num = 10) {
			$lines = explode("\n", $str);
			$firsts = array_slice($lines, 0, $num);
			return implode("\n", $firsts);
		}
		
		//SECTION 7 -------------------------------- Prepare menus
		//Submission kinds
		$html_select_submission = '';
		$hss_name = '';
		for ($i = 1; $i <= 7; $i ++) {
			switch ($i) {
				case (1): $hss_name = "Graphics"; break;
				case (2): $hss_name = "Games"; break;
				case (4): $hss_name = "How-Tos"; break;
				case (5): $hss_name = "Sounds, Music"; break;
				case (6): $hss_name = "Miscellaneous"; break;
				case (7): $hss_name = "Hacks &amp; Mods"; break;
			}
			if ($i <> 3) {
				if ($i == $IN['c']) {
					$html_select_submission .= '<option value="'.$i.'" selected="selected">'.$hss_name.'</option>';
				}
				else {
					$html_select_submission .= '<option value="'.$i.'">'.$hss_name.'</option>';
				}
			}
		}
		//Sort kind
		$html_select_sort_kind = '';
		for ($i = 1; $i <= 6; $i ++) {
			$hssk_name = '';
			switch ($i) {
				case (1): $hssk_name = "Title"; break;
				case (2): $hssk_name = "Author"; break;
				case (3): $hssk_name = "Date"; break;
				case (4): $hssk_name = "Updated"; break;
				case (5): $hssk_name = "Downloads"; break;
				case (6): $hssk_name = "Views"; break;
			}
			if ($i == $IN['sort']) {
				$html_select_sort_kind .= '<option value="'.$i.'" test="Sort Type" selected="selected">'.$hssk_name.'</option>';
			}
			else {
				$html_select_sort_kind .= '<option value="'.$i.'" test="Sort Type" >'.$hssk_name.'</option>';
			}
		}
		
		//Sort direction
		$html_select_sort_dir = '';
		for ($i = 0; $i <= 1; $i ++) {
			$hssd_name = '';
			switch ($i) {
				case (0): $hssd_name = "Descending Order"; break;
				case (1): $hssd_name = "Ascending Order"; break;
			}
			if ($i == $IN['asc']) {
				$html_select_sort_dir .= '<option value="'.$i.'" title="Sort Direction" selected="selected">'.$hssd_name.'</option>';
			}
			else {
				$html_select_sort_dir .= '<option value="'.$i.'" title="Sort Direction">'.$hssd_name.'</option>';
			}
		}
		
		//SECTION 8 -------------------------------- while it is pulling each submission data, format the submission page
		$list = favorite_list_head($html_select_submission);
		//DEBUG USE _______________________ $list .= "IN['c'] = ".$IN['c'].'</br>'."r_table = ".$r_table.'</br>';
		$nn = 0;
		$final_count = 0;
		if ($count == 0) { //Nothing found on the results
			$list .= "<tr><div style='text-align: center; padding: 6px; width: 100%;'>No favorites found</div></tr>";
		}
		//DEBUG USE _______________________ $list .= "PAGE = ".$data['page']."<br>";
		while ($result = $DB->fetch_row()) { //This is used for each of the row
			// DEBUG USE _______________________ $list .= "1st +".$nn."<br>"; 
			if ($nn >= $data['page'] * intval($limit) && $nn < ($data['page'] + 1) * intval($limit))
			{
				// DEBUG USE _______________________ $list .= "2nd +".$nn."<br>"; //
				if ($data['page'] == $data['pages'])
				{
					// DEBUG USE _______________________ $list .= "3rd +A".$nn."<br>"; 
					if ($nn < ((($data['pages'] + 2) * intval($limit)) - $pages_modulo))
					{
						// DEBUG USE _______________________ $list .= "4th+".$nn."<br>";
						$list .= fav_row_pull($result);
					}
					$final_count = $pages_modulo;
				}
				else
				{
					// DEBUG USE _______________________ $list .= "3rd +B".$nn."<br>";
					$list .= fav_row_pull($result);
					$final_count = intval($limit);
				}
			}
			$nn ++;
		}
		$paginate_url = "act=user&param=11&c={$IN['c']}&sort={$IN['sort']}&asc={$IN['asc']}";
		$paginate = $STD->paginate ($IN['st'], $count, $limit, $paginate_url);
		$list .= favorite_list_foot($paginate, $html_select_sort_kind, $html_select_sort_dir);
		//Final update for title
		switch ($IN['c']) {
			case (1): $hss_name = "Graphics"; break;
			case (2): $hss_name = "Games"; break;
			case (4): $hss_name = "How-Tos"; break;
			case (5): $hss_name = "Sounds, Music"; break;
			case (6): $hss_name = "Miscellaneous"; break;
			case (7): $hss_name = "Hacks &amp; Mods"; break;
		}
		
		
		//SECTION 9 -------------------------------- OUTPUT
		$this->output .= $STD->global_template->page_header('My Favorites - '.$hss_name);
		$this->output .= $list;//$this->mod_html->manage_page($list, $STD->make_form_token(), $module->get_max_sizes());
		$this->output .= $STD->global_template->page_footer();
	}
}

?>
