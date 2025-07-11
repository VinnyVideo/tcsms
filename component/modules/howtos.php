<?php
//------------------------------------------------------------------
// Taloncrossing Submission Management System 1.0
//------------------------------------------------
// Copyright 2005 Justin Aquadro
// 
// component/modules/howtos.php --
// How-tos Root Type module
//------------------------------------------------------------------

require_once ROOT_PATH.'component/modules/module_base.php';
require_once ROOT_PATH.'lib/resource.php';

class mod_howtos extends module {
	
	function init () {
		global $CFG;
		
		$this->extable = $CFG['db_pfx'].'_res_howtos';
		
		$this->file_restrictions = array(
			'file'	=> array(
				'mime'	=> array('application/zip','application/x-zip-compressed','audio/x-zipped-mod','text/plain','text/html'),
				'ext'	=> array('ZIP','TXT','HTM','HTML'),
				'size'	=> array(0, 1024*1024*8, '0B', '8MB'),
			),
		);
	}
	
	function get_max_sizes() {
		return array('file'			=> $this->file_restrictions['file']['size'][3]);
	}
	
	function return_ex_data (&$resdata) {
		$exdata = array();
		
		$exdata['e.views']		= (!isset($resdata['views']))		? 0		: $resdata['views'];
		$exdata['e.downloads']	= (!isset($resdata['downloads']))	? 0		: $resdata['downloads'];
		$exdata['e.file']		= (!isset($resdata['file']))		? ''	: $resdata['file'];
		$exdata['e.file_mime']	= (!isset($resdata['file_mime']))	? ''	: $resdata['file_mime'];
		
		return $exdata;
	}
	
	function extra_order () {
		
		$order_names = array('n' => 'Downloads', 'v' => 'Views');
		$order_list = array('n' => 'e.downloads', 'v' => 'e.views');
		
		return array($order_names, $order_list);
	}
	
	function update_block ($module, $time) {
		global $STD;
		
		// Initialize
		$RES = new resource;
		$RES->module = $module;
		$RES->query_use('extention', $module['mid']);
		$RES->query_use('r_user');
		$RES->query_use('filter_single');
		$RES->query_condition("r.accept_date >= '$time'");
		$RES->query_condition("fg.keyword = 'TYPE'");
		$RES->getByType($module['mid']);
			
		$num_items = 0;
		$html = "<div class='sformstrip'>Sprites</div><table class='sformtable' cellspacing='1'>";
		
		
		while ($RES->nextItem())
		{
			$RES->data['url'] = $STD->encode_url('index.php', "act=resdb&param=02&c={$RES->data['type']}&id={$RES->data['rid']}");
			$RES->data['username'] = $STD->format_username($RES->data, 'ru_');
			
			(!empty($RES->data['l_short_name']))
				? $RES->data['type'] = $RES->data['l_short_name'] : $RES->data['type'] = $RES->data['l_name'];
			
			//$output .= $shtml->news_update_block_row( $RES->data );
			$html .= "<tr><td class='sformleftw'><a href='$item'><b>{$RES->data['title']}</b></a></td>
					  <td class='sformleftw' width='15%'>[$type]</td>
					  <td class='sformleftw' width='30%'>By {$username}</td></tr>";
			$num_items++;
		}
		
		$html .= "</table>";
		
		if (!$num_items)
			$html = '';
		
		return $html;
	}
	
	//-------------------------------------------------------------------------------------------------
	// Data Check Functions
	//-------------------------------------------------------------------------------------------------
	
	function common_data_check () {
		global $IN, $STD;
		
		// Check for completed required fields
		if (empty($IN['cat1']) || empty($IN['cat2']))
			$this->error_save("You must chose a value for the target application", 'submit');
		
		if (empty($IN['title']))
			$this->error_save("You must provide a title.");
		
		if (empty($IN['description']))
			$this->error_save("You must provide a description.");
	}
	
	function user_submit_data_check () {
		global $IN, $STD;
		
		$this->common_data_check();
		
		if (empty($_FILES['file']) || empty($_FILES['file']['name']))
			$STD->error("You must provide a file.");
		
		// Advanced Checking
		$this->check_file_restrictions('file', 'file', 'submit');
	}
	
	function user_manage_data_check () {
		global $IN, $STD;
		
		$this->common_data_check();
		
		if (empty($IN['reason']))
			$STD->error("You must give a reason for this update.  This will appear in your submission's update box.  Your changes may not be accepted without a valid reason.");
		
		// Advanced Checking
		if (!empty($_FILES['file']['name']))
			$this->check_file_restrictions('file', 'file');
	}
	
	function acp_data_check () {
		global $IN, $STD;
		
		$this->common_data_check();
		
		if (empty($IN['author']) && empty($IN['author_override']))
			$STD->error("You must provide either a valid Creator/Username, or a Username Override, or both.");
		
		if (empty($IN['admincomment']) && empty($IN['omit_comment']) && !empty($IN['author']))
			$STD->error("You did not choose to omit an admin comment.  Please go back and enter one.");
		
		// Advanced Checking
		if (!empty($_FILES['file']['name']))
			$this->check_file_restrictions('file', 'file');
		
		if (!empty($IN['author'])) {
			$USER = new user;
			if (!$USER->getByName($IN['author']))
				$STD->error("Invalid Creator/Username entered.  Leave blank to now associate a registered user.");
		}
	}
	
	//-------------------------------------------------------------------------------------------------
	// Data Display Prep Functions
	//-------------------------------------------------------------------------------------------------
	
	function common_prep_data (&$row) {
		global $IN, $STD;
		
		$data['rid'] = $row['rid'];
		$data['type'] = $row['type'];
		$data['description'] = $row['description'];
		$data['title'] = $row['title'];
		$data['username'] = $row['ru_username'];
		$data['author_override'] = $row['author_override'];
		$data['website_override'] = $row['website_override'];
		$data['weburl_override'] = $row['weburl_override'];
		$data['views'] = $row['views'];
		$data['downloads'] = $row['downloads'];
		$data['update_reason'] = $row['update_reason'];
		$data['comments'] = $row['comments'];

		(empty($row['created']))
			? $data['created'] = 'Unknown'
			: $data['created'] = $STD->make_date_time($row['created']);
		(empty($row['updated']))
			? $data['updated'] = 'Never'
			: $data['updated'] = $STD->make_date_time($row['updated']);
		
		$data['file'] = "file/{$IN['c']}/{$row['file']}";
		
		$module = $STD->modules->get_module($data['type']);
		
		if (!empty($module['full_name'])) // 4/9/2025 test fix
			$data['type_name'] = $module['full_name'];
		
		return $data;
	}
	
	//-------------------------------------------------------------------------------------------------
	// Data Display Prep Functions :: Editing Subset
	//-------------------------------------------------------------------------------------------------
	
	function common_edit_prep_data (&$row) {
		global $IN, $STD, $DB, $CFG, $session;
		
		$data = $this->common_prep_data($row);
		
		$data['description'] = $STD->br2nl($data['description']);
		
		// Build Category Elements
		
		$DB->query("SELECT l.fid,l.name,g.keyword,m.fid as fhit FROM {$CFG['db_pfx']}_filter_list l ".
				   "LEFT JOIN {$CFG['db_pfx']}_filter_use u ON (l.gid = u.gid) ".
				   "LEFT JOIN {$CFG['db_pfx']}_filter_group g ON (l.gid = g.gid) ".
				   "LEFT OUTER JOIN {$CFG['db_pfx']}_filter_multi m ON (l.fid = m.fid AND m.rid = '{$data['rid']}') ".
				   "WHERE u.mid = '{$IN['c']}' ORDER BY l.name");

		$access = array();
		$selected = array();
		while ($arow = $DB->fetch_row()) {
			$access[] = $arow;
			if ($arow['fhit'] > 0)
				$selected[] = $arow['fid'];
		}
		
		$session->touch_data ('err_save');
		if (!empty ($session->data['err_save']) ) {
			$err = $session->data['err_save'];
			if (!empty($err['cat1']))
				$selected = array_merge($selected, $err['cat1']);
			if (isset($err['cat2']))
				$selected = array_merge($selected, $err['cat2']);
		}
		
		$data['cat1'] = $this->make_catsetmulti('TARGET_APP', $access, $selected);
		$data['cat2'] = $this->make_catset('FRANCHISE', $access, $selected);

		$data['cat1'] = $STD->make_checkboxlist('cat1[]', $data['cat1']['value'], $data['cat1']['name'], $data['cat1']['sel']);
		$data['cat2'] = $STD->make_select_box('cat2', $data['cat2']['value'], $data['cat2']['name'], $data['cat2']['sel'], 'selectbox');

		return $data;
	}
	
	function submit_prep_data () {
		global $IN, $STD, $session;
		
		$res = new resource;
		
		// Recover from error?
		$session->touch_data ('err_save');
		if (!empty ($session->data['err_save']) ) {
			$err = $session->data['err_save'];
			$res->create($err);
		}
		else
			$res->create();

		$res->data = array_merge($res->data, $res->clear_prefix($this->return_ex_data($res->data), 'e.'));
		$res->data['rid'] = 0;
		$res->data['ru_username'] = '';

		$data = $this->common_edit_prep_data($res->data);

		return $data;
	}
	
	function manage_prep_data (&$row) {
		global $IN, $STD;
		
		$data = $this->common_edit_prep_data($row);

		$data['author_override'] = '';
		if (preg_match($STD->get_regex('nat_delim'), $row['author_override'])) {
			$add_authors = preg_split($STD->get_regex('nat_delim'), $row['author_override']);
			array_shift($add_authors);
			$data['author_override'] = @join(', ', $add_authors);
		}

		return $data;
	}
	
	function acp_edit_prep_data (&$row) {
		global $IN, $STD;
		
		$data = $this->common_edit_prep_data($row);
		
		empty($row['ru_website'])
			? $data['website'] = "<img src='{$STD->tags['image_path']}/not_visible.gif' alt='[X]' title='User Website: None'>"
			: $data['website'] = "<img src='{$STD->tags['image_path']}/visible.gif' alt='[O]' title='User Website: {$row['ru_website']}'>";
			
		empty($row['ru_weburl'])
			? $data['weburl'] = "<img src='{$STD->tags['image_path']}/not_visible.gif' alt='[X]' title='User Website: None'>"
			: $data['weburl'] = "<img src='{$STD->tags['image_path']}/visible.gif' alt='[O]' title='User Website: {$row['ru_weburl']}'>";
		
		$uurl = $STD->encode_url($_SERVER['PHP_SELF'], "act=ucp&param=02&u={$row['uid']}");
		empty($row['ru_username'])
			? $data['usericon'] = "<img src='{$STD->tags['image_path']}/not_visible.gif' alt='[X]' title='No User Associated'>"
			: $data['usericon'] = "<a href='$uurl'><img src='{$STD->tags['image_path']}/visible.gif' alt='[O]' title='Click to view user'></a>";

	//	($STD->user['acp_users'] && !empty($row['ru_username']))
	//		? $data['usericon']['v'] = 'Click to View User'
	//		: $data['usericon']['linkvis'] = 0;
		
		return $data;
	}
	
	//-------------------------------------------------------------------------------------------------
	// Data Display Prep Functions :: Viewing Subset
	//-------------------------------------------------------------------------------------------------
	
	function common_view_prep_data (&$row) {
		global $IN, $STD, $DB, $CFG;
		
		$data = $this->common_prep_data($row);
		
		$data['author'] = $STD->format_username($row, 'ru_');
		$data['email_icon'] = $this->get_email_icon($row, 'ru_');
		$data['website_icon'] = $this->get_website_icon($row, 'ru_');
		$data['filesize'] = $this->get_filesize($row);
		
		$data['title'] = $STD->safe_display($data['title']);
		$data['compat_icons'] = array();
		$compat_names = array();
		
		if (!empty($IN['id'])) {
			$DB->query("SELECT l.fid,l.name,l.short_name,g.keyword,m.fid as fhit FROM {$CFG['db_pfx']}_filter_list l ".
					   "LEFT JOIN {$CFG['db_pfx']}_filter_use u ON (l.gid = u.gid) ".
					   "LEFT JOIN {$CFG['db_pfx']}_filter_group g ON (l.gid = g.gid) ".
					   "LEFT OUTER JOIN {$CFG['db_pfx']}_filter_multi m ON (l.fid = m.fid AND m.rid = '{$data['rid']}') ".
					   "WHERE u.mid = '{$IN['c']}'");

			while ($arow = $DB->fetch_row()) {
				if ($arow['fhit'] > 0 && $arow['keyword'] == 'TARGET_APP') {
					$data['compat_icons'][] = $arow['short_name'];
					$compat_names[] = $arow['name'];
				}
			}
			
			for ($x=0; $x<sizeof($data['compat_icons']); $x++) {
				$src = "{$STD->tags['root_path']}/template/modules/{$data['type']}/{$data['compat_icons'][$x]}.gif";
				$data['compat_icons'][$x] = "<img src=\"$src\" alt=\"{$data['compat_icons'][$x]}\" title=\"{$compat_names[$x]}\"> ";
			}
		}
		
		$data['compat_icons'] = @join($data['compat_icons']);
		
		return $data;
	}
	
	function resdb_prep_data (&$row) {
		global $IN, $STD, $session;
		
		$data = $this->common_view_prep_data($row);
		
		$data['created'] = $STD->make_date_short($row['created']);
		
		if (strlen($data['description']) > 250)
			$data['description'] = $STD->nat_substr($data['description'], 250) . ' ...';

		$data['file_url'] = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=02&c={$IN['c']}&id={$data['rid']}");
		$data['dl_url'] = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=03&c={$IN['c']}&id={$data['rid']}");
		
		$page_icon = "<img src=\"{$STD->tags['global_image_path']}/viewpagevw.gif\" alt=\"[Page]\" style=\"display:inline; vertical-align:middle\" title=\"View Submission's Page\">";
		$dl_icon = "<img src=\"{$STD->tags['global_image_path']}/viewpagedn.gif\" alt=\"[DL]\" style=\"display:inline; vertical-align:middle\" title=\"Download Submission\">";
		
		if (empty ($session->data['rr']) ) $session->data['rr'] = array();
		$rr = empty ($session->data['rr'][$data['rid']]) ? 0 : $session->data['rr'][$data['rid']];
		
		if ($row['comment_date'] > $STD->user['last_visit'] && 
			$row['comment_date'] > $rr)
		{
			$c_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=02&c={$IN['c']}&id={$data['rid']}&st=new");
			$data['new_comments'] = "<a href=\"$c_url\"><img src=\"{$STD->tags['global_image_path']}/newcomment.gif\" alt=\"[NEW]\" style=\"display:inline; vertical-align:middle\" title=\"Go to last unread comment\"></a>";
		} else {
			$data['new_comments'] = '';
		}
		
		$data['page_icon'] = "<a href=\"{$data['file_url']}\">$page_icon</a>";
		$data['dl_icon'] = "<a href=\"{$data['dl_url']}\">$dl_icon</a>";
		
		(!$row['updated'])
			? $data['updated'] = ''
			: $data['updated'] = 'Updated: ' . $STD->make_date_short($row['updated']);
			
		if (!empty($row['updated']) && time() - $row['updated'] < 60*60*24*14)
			$data['updated'] = "<span class='highlight'>{$data['updated']}</span>";
			
		return $data;
	}
	
	function resdb_prep_page_data (&$res) {
		global $IN, $STD, $DB, $CFG;
		
		$data = $this->common_view_prep_data($res);
		
		$data['created'] = $STD->make_date_time($res['created']);

		if (time() - $res['updated'] < 60*60*24*14)
			$data['updated'] = "<span class='highlight'>{$data['updated']}</span>";
		
		$dl_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=03&c={$IN['c']}&id={$IN['id']}");
		($data['filesize'] == 'File Unavailable')
			? $data['download_text'] = 'Download Unavailable'
			: $data['download_text'] = "<a href='$dl_url'>View / Download</a>";
		
		// Version History
		
		$data['version_history'] = '';
		$dblist = $this->get_version_history($IN['id']);
		$rows_returned = $DB->get_num_rows();
		if ($rows_returned == 0)
			$data['version_history'] = "<tr><td colspan='2' style='text-align:center'>No History</td></tr>";

		for ($x=0; $x<min(2,$rows_returned); $x++) {
			$row = $DB->fetch_row($dblist);
			$vdate = $STD->make_date_short($row['date']);
			$data['version_history'] .= "<tr><td style='width:25%;'><b>$vdate&nbsp;</b></td>
										   <td style='width:75%;'>{$row['change']}</td></tr>";
		}
		
		if ($rows_returned > 2)	
			$data['version_history'] .= "<tr><td colspan='2' style='text-align:center;'><br><a href='javascript:version_history()'>
										 View Complete History</a></td></tr>";
		
		return $data;
	}
	
	//-------------------------------------------------------------------------------------------------
	// Data Manipulating and Updating Functions
	//-------------------------------------------------------------------------------------------------
	
	function common_update_data () {
		global $IN, $STD;
		
		$auxdata = array();
		
		$RES = new resource;
		$RES->query_use('extention', str_replace('tsms_', '', $this->extable));
		
		if (!isset($IN['rid']) || !$RES->get($IN['rid']))
			$RES->create();
		
		$ORIG = $RES->data;
		
		$RES->data['title'] = $IN['title'];
		$RES->data['description'] = $IN['description'];
		
		$auxdata['target_app'] = $IN['cat1'];
		$auxdata['cat_franchise'] = $IN['cat2'];
		
		return array($RES, $auxdata, $ORIG);
	}
	
	function user_update_submit_data () {
		global $IN, $STD;
		
		list($RES, $auxdata) = $this->common_update_data();
		
		$RES->data['uid'] = $STD->user['uid'];
		$RES->data['type'] = $IN['c'];
		$RES->data['queue_code'] = 1;
		
		$RES->data['file'] = $this->move_file('file', 'file', 'submit');
		$RES->data['file_mime'] = $_FILES['file']['type'];

		$RES->insert();
		
		$values = array($auxdata['target_app'], $auxdata['cat_franchise']);
		$this->add_filters($RES->data['rid'], $values);
		
		$RES->data['catwords'] = $this->make_catwords( $RES->data['rid'] );
		
		$RES->query_unuse('extention');
		$RES->update();
		
		return $RES;
	}
	
	function user_update_manage_data () {
		global $IN, $STD;
		
		list ($RES, $auxdata, $ORIG) = $this->common_update_data();
		
		$RES->data['author_override'] = '';
		if (!empty($IN['author_override'])) {
			$add_authors = preg_split($STD->get_regex('nat_delim'), $IN['author_override']);
			$add_authors = @join(', ', $add_authors);
			$RES->data['author_override'] = "{$STD->user['username']}, $add_authors";
		}
		
		if (!empty($_FILES['file']['name']))
			$RES->data['updated'] = time();
		
		$RES->data['update_reason'] = $IN['reason'];
		
		if (!empty($_FILES['file']['name'])) {
			$RES->data['file'] = $this->move_file('file', 'file');
			$RES->data['file_mime'] = $_FILES['file']['type'];
		}
		
		// Make ghost
		$fields = $RES->data;
		$RES->data = $ORIG;
		
		$ghost = $RES->create_ghost($fields);
		
		// Add Filters
		$this->clear_filters($ghost->data['rid']);
		
		$values = array($auxdata['target_app'], $auxdata['cat_franchise']);
		$this->add_filters($ghost->data['rid'], $values);
		
		$ghost->data['catwords'] = $this->make_catwords( $ghost->data['rid'] );
		
		$ghost->query_unuse('extention');
		$ghost->update();
		
		return $RES;
	}
	
	function acp_update_data () {
		global $IN, $STD;
		
		list($RES, $auxdata) = $this->common_update_data();
		
		$RES->data['author_override'] = $IN['author_override'];
		$RES->data['website_override'] = $IN['website_override'];
		$RES->data['weburl_override'] = $IN['weburl_override'];
		
		if (!empty($IN['file_name']))
			$RES->data['file'] = $IN['file_name'];
		
		if (!empty($IN['author'])) {
			$USER = new user;
			$USER->getByName($IN['author']);
			$RES->data['uid'] = $USER->data['uid'];
		} else {
			$RES->data['uid'] = 0;
		}
		
		if (!empty($_FILES['file']['name'])) {
			$RES->data['file'] = $this->move_file('file', 'file');
			$RES->data['file_mime'] = $_FILES['file']['type'];
		}
		
		// Add Filters
		$this->clear_filters($IN['rid']);
		
		$values = array($auxdata['target_app'], $auxdata['cat_franchise']);
		$this->add_filters($IN['rid'], $values);
		
		// Keywords
		
		$RES->data['catwords'] = $this->make_catwords( $RES->data['rid'] );
		
		$RES->update();
		
		return $RES;
	}

}