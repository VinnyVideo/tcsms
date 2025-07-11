<?php
//------------------------------------------------------------------
// Penguinia Content Management System 1.0
//------------------------------------------------
// Copyright 2005 Justin Aquadro
// 
// component/modules/gfx.php --
// Graphics Root Type module
//------------------------------------------------------------------

require_once ROOT_PATH.'component/modules/module_base.php';
require_once ROOT_PATH.'lib/resource.php';

class mod_gfx extends module {
	
	function init () {
		global $CFG;
		
		$this->extable = $CFG['db_pfx'].'_res_gfx';
		
		$this->file_restrictions = array(
			'file'	=> array(
				'mime'	=> array('application/zip','application/x-zip-compressed','audio/x-zipped-mod','image/png','image/x-png','image/gif','image/x-gif'),
				'ext'	=> array('ZIP','PNG','GIF'),
				'size'	=> array(0, 1024*1024*3, '0B', '3MB'),
			),
			'thumbnail'	=> array(
				'mime'	=> array('image/gif','image/png','image/x-png','image/x-gif'),
				'ext'	=> array('PNG','GIF'),
				'size'	=> array(0, 1024*30, '0B', '30KB'),
				'width'	=> array(100, 100),
				'height'=> array(100, 100),
			),);
	}
	
	function get_max_sizes() {
		return array('file'			=> $this->file_restrictions['file']['size'][3],
					 'thumbnail'	=> $this->file_restrictions['thumbnail']['size'][3]);
	}
	
	function return_ex_data (&$resdata) {
		$exdata = array();
		
		$exdata['e.views']		= (!isset($resdata['views']))		? 0		: $resdata['views'];
		$exdata['e.downloads']	= (!isset($resdata['downloads']))	? 0		: $resdata['downloads'];
		$exdata['e.file']		= (!isset($resdata['file']))		? ''	: $resdata['file'];
		$exdata['e.thumbnail']	= (!isset($resdata['thumbnail']))	? ''	: $resdata['thumbnail'];
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
			$this->error_save("You must chose a value for the format, contents, and franchise categories.", 'submit');
		
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
		
		if (empty($_FILES['thumbnail']) || empty($_FILES['thumbnail']['name']))
			$STD->error("You must provide a thumbnail.");
		
		// Advanced Checking
		$this->check_file_restrictions('file', 'file', 'submit');
		$this->check_file_restrictions('thumbnail', 'thumbnail', 'submit');
	}
	
	function user_manage_data_check () {
		global $IN, $STD;
		
		$this->common_data_check();
		
		if (empty($IN['reason']))
			$STD->error("You must give a reason for this update.  This will appear in your submission's update box.  Your changes may not be accepted without a valid reason.");
		
		// Advanced Checking
		if (!empty($_FILES['file']['name']))
			$this->check_file_restrictions('file', 'file');
		
		if (!empty($_FILES['thumbnail']['name']))
			$this->check_file_restrictions('thumbnail', 'thumbnail');
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
		
		if (!empty($_FILES['thumbnail']['name']))
			$this->check_file_restrictions('thumbnail', 'thumbnail');
		
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
		
		$data['thumbnail'] = $this->get_thumbnail($row);
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
			if (isset($err['cat1']))
				$selected = array_merge($selected, $err['cat1']);
			if (isset($err['cat2']))
				$selected = array_merge($selected, $err['cat2']);
			if (isset($err['cat3']))
				$selected = array_merge($selected, $err['cat3']);
			if (isset($err['cat4']))
				$selected = array_merge($selected, $err['cat4']);
			if (isset($err['cat5']))
				$selected = array_merge($selected, $err['cat5']);
			if (isset($err['cat6']))
				$selected = array_merge($selected, $err['cat6']);
		}
		
		$data['cat1'] = $this->make_catset('TYPE', $access, $selected);
		$data['cat2'] = $this->make_catset('RIP_TYPE', $access, $selected);
		$data['cat3'] = $this->make_catsetmulti('GEN_CAT', $access, $selected);
		$data['cat4'] = $this->make_catsetmulti('GAME', $access, $selected);
		$data['cat5'] = $this->make_catsetmulti('CHAR', $access, $selected);
		$data['cat6'] = $this->make_catset('FRANCHISE', $access, $selected);
		
		$data['cat1'] = $STD->make_select_box('cat1', $data['cat1']['value'], $data['cat1']['name'], $data['cat1']['sel'], 'selectbox');
		$data['cat2'] = $STD->make_select_box('cat2', $data['cat2']['value'], $data['cat2']['name'], $data['cat2']['sel'], 'selectbox');
		$data['cat3'] = $STD->make_checkboxlist('cat3[]', $data['cat3']['value'], $data['cat3']['name'], $data['cat3']['sel']);
		$data['cat4'] = $STD->make_checkboxlist('cat4[]', $data['cat4']['value'], $data['cat4']['name'], $data['cat4']['sel']);
		$data['cat5'] = $STD->make_checkboxlist('cat5[]', $data['cat5']['value'], $data['cat5']['name'], $data['cat5']['sel']);
		$data['cat6'] = $STD->make_select_box('cat6', $data['cat6']['value'], $data['cat6']['name'], $data['cat6']['sel'], 'selectbox');
		
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
			$data['author_override'] = join(', ', $add_authors);
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
		global $IN, $STD;
		
		$data = $this->common_prep_data($row);
		
		$data['title'] = $STD->safe_display($data['title']);
		
		$data['author'] = $STD->format_username($row, 'ru_');
		$data['email_icon'] = $this->get_email_icon($row, 'ru_');
		$data['website_icon'] = $this->get_website_icon($row, 'ru_');
		$data['filesize'] = $this->get_filesize($row);
		
		return $data;
	}
	
	function resdb_prep_data (&$row) {
		global $IN, $STD, $session, $DB, $CFG;
		
		$data = $this->common_view_prep_data($row);
		
		$data['created'] = $STD->make_date_short($row['created']);
		
		if (strlen($data['description']) > 250)
			$data['description'] = $STD->nat_substr($data['description'], 250) . ' ...';

		$data['file_url'] = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=02&c={$IN['c']}&id={$data['rid']}");
		$data['dl_url'] = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=03&c={$IN['c']}&id={$data['rid']}");
		
		$page_icon = "<img src=\"{$STD->tags['global_image_path']}/viewpagevw.gif\" alt=\"[Page]\" style=\"display:inline; vertical-align:middle\" title=\"View Submission's Page\">";
		$dl_icon = "<img src=\"{$STD->tags['global_image_path']}/viewpagedn.gif\" alt=\"[DL]\" style=\"display:inline; vertical-align:middle\" title=\"Download Submission\">";
		
		$data['page_icon'] = "<a href=\"{$data['file_url']}\">$page_icon</a>";
		$data['dl_icon'] = "<a href=\"{$data['dl_url']}\">$dl_icon</a>";
		
		if (empty($session->data['rr']) ) $session->data['rr'] = array();
		$rr = empty ($session->data['rr'][$data['rid']]) ? 0 : $session->data['rr'][$data['rid']];
		
		if ($row['comment_date'] > $STD->user['last_visit'] && 
			$row['comment_date'] > $rr)
		{
			$c_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=02&c={$IN['c']}&id={$data['rid']}&st=new");
			$data['new_comments'] = "<a href=\"$c_url\"><img src=\"{$STD->tags['global_image_path']}/newcomment.gif\" alt=\"[NEW]\" style=\"display:inline; vertical-align:middle\" title=\"Go to last unread comment\"></a>";
		} else {
			$data['new_comments'] = '';
		}
		
	
		(!$row['updated'])
			? $data['updated'] = ''
			: $data['updated'] = 'Updated: ' . $STD->make_date_short($row['updated']);
			
		if (!empty($row['updated']) && time() - $row['updated'] < 60*60*24*14)
			$data['updated'] = "<span class='highlight'>{$data['updated']}</span>";
		
		if (!$STD->user['show_thumbs'])
			$data['thumbnail'] = '';
		
		//ADD Resource type
		$DB2_Q = "SELECT l.fid,l.name,l.short_name,g.keyword,m.fid as fhit FROM {$CFG['db_pfx']}_filter_list l ".
				   "LEFT JOIN {$CFG['db_pfx']}_filter_use u ON (l.gid = u.gid) ".
				   "LEFT JOIN {$CFG['db_pfx']}_filter_group g ON (l.gid = g.gid) ".
				   "LEFT OUTER JOIN {$CFG['db_pfx']}_filter_multi m ON (l.fid = m.fid AND m.rid = '{$data['rid']}') ".
				   "WHERE u.mid = '{$IN['c']}' AND m.rid = '{$data['rid']}'";
		
		$data['type_title'] = "---";
		$type_a = "";
		$type_b = "";
		
		//establish Alt MySQL connection
		$mysql_tc = mysqli_connect($CFG['db_host'],$CFG['db_user'],$CFG['db_pass'],$CFG['db_db']);
			
			//throw an error if connection failed
			if ($mysql_tc->connect_errno)
			{
				$STD->error("CRITICAL ERROR: Failed to connect to MySQL: (" . $mysql_tc->connect_errno . ") " . $current_connection->connect_error);
			}
			
			//perform the query
			$t_output = array();
			$tcount = 0;
			if (mysqli_multi_query($mysql_tc,$DB2_Q))
			{
				do
				{
					// Store first result set
					if ($result4 = mysqli_store_result($mysql_tc))
					{
						// Fetch one and one row
						while ($ttrow = mysqli_fetch_row($result4))
						{
							array_push($t_output,$ttrow);
							$tcount += 1;
							if ($ttrow[3] == 'TYPE') {
								$type_a = $ttrow[1];
							}
							if ($ttrow[3] == 'RIP_TYPE') {
								$type_b = $ttrow[1];
							}
						}
						// Free result set
						mysqli_free_result($result4);
					}
				}
				while (mysqli_more_results($mysql_tc));
			}
			
			//shut down MySQL
			mysqli_close($mysql_tc);
		
		{
			//if ($t_output['keyword'] == 'TYPE') {
				//$type_a = $t_output['name']['TYPE'];
			//}
			
			//if ($t_output['keyword'] == 'RIP_TYPE') {
				//$type_b = $t_output['RIP_TYPE']['name'];
			//}
		}
		$data['type_title'] = $type_a." - ".$type_b;

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
		
		// File Info
		$DB->query("SELECT l.fid,l.name,l.short_name,g.keyword,m.fid as fhit FROM {$CFG['db_pfx']}_filter_list l ".
				   "LEFT JOIN {$CFG['db_pfx']}_filter_use u ON (l.gid = u.gid) ".
				   "LEFT JOIN {$CFG['db_pfx']}_filter_group g ON (l.gid = g.gid) ".
				   "LEFT OUTER JOIN {$CFG['db_pfx']}_filter_multi m ON (l.fid = m.fid AND m.rid = '{$data['rid']}') ".
				   "WHERE u.mid = '{$IN['c']}' AND m.rid = '{$data['rid']}'");
		
		$data['type_type'] = "Other";
		$data['type_desc'] = '';
		$data['type_rip'] = 'Other';
		$data['type_rip_desc'] = '';
		while ($arow = $DB->fetch_row()) {
			if ($arow['keyword'] == 'TYPE') {
				$data['type_type'] = $arow['name'];
				switch ($arow['short_name']) {
					case 'SHEETS': $data['type_desc'] = "Sprites and animation frames saved in a static image medium."; break;
					case 'KLIK' : $data['type_desc'] = "Graphics for use in Clickteam Products (The Games Factory, Click and Create, Multimedia Fusion, etc.)"; break;
					case 'TILES' : $data['type_desc'] = "Backgrounds broken into a set of tiles."; break;
					case 'ANIGIF' : $data['type_desc'] = "Pre-animated sprites saved as a collection of animated GIFs for easy importing into Game Maker and Multimedia Fusion 2."; break;
				}
			}
			
			if ($arow['keyword'] == 'RIP_TYPE') {
				$data['type_rip'] = $arow['name'];
				switch ($arow['name']) {
					case 'Ripped': $data['type_rip_desc'] = "Sprites ripped directly from a game."; break;
					case 'Edited' : $data['type_rip_desc'] = "Modification of existing sprites."; break;
					case 'Original' : $data['type_rip_desc'] = "Sprites that are made completely from scratch."; break;
				}
			}
		}
		
		// Version History
		
		$data['version_history'] = '';
		$dblist = $this->get_version_history($IN['id']);
		$rows_returned = $DB->get_num_rows();
		if ($rows_returned == 0)
			$data['version_history'] = "<tr><td colspan='2' style='text-align:center;'>No History</td></tr>";

		for ($x=0; $x<min(2,$rows_returned); $x++) {
			$row = $DB->fetch_row($dblist);
			$vdate = $STD->make_date_short($row['date']);
			$data['version_history'] .= "<tr><td style='width:25%; vertical-align:top'><b>$vdate&nbsp;</b></td>
										   <td style='width:75%; vertical-align:top'>{$row['change']}</td></tr>";
		}
		
		if ($rows_returned > 2)	
			$data['version_history'] .= "<tr><td colspan='2' align='center'><br><a href='javascript:version_history()'>
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
		
		$auxdata['cat_type'] = $IN['cat1'];
		$auxdata['cat_riptype'] = $IN['cat2'];	
		empty($IN['cat3']) ? $auxdata['cat_gencat'] = array() : $auxdata['cat_gencat'] = $IN['cat3'];
		empty($IN['cat4']) ? $auxdata['cat_game'] = array() : $auxdata['cat_game'] = $IN['cat4'];
		empty($IN['cat5']) ? $auxdata['cat_char'] = array() : $auxdata['cat_char'] = $IN['cat5'];
		$auxdata['cat_franchise'] = $IN['cat6'];
		
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
		$RES->data['thumbnail'] = $this->move_file('thumbnail', 'thumbnail', 'submit');

		$RES->insert();
		
		$values = array($auxdata['cat_type'], $auxdata['cat_riptype'], $auxdata['cat_gencat'], 
						$auxdata['cat_game'], $auxdata['cat_char'], $auxdata['cat_franchise']);
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
			$add_authors = join(', ', $add_authors);
			$RES->data['author_override'] = "{$STD->user['username']}, $add_authors";
		}
		
		if (!empty($_FILES['file']['name']))
			$RES->data['updated'] = time();
		
		$RES->data['update_reason'] = $IN['reason'];
		
		if (!empty($_FILES['file']['name'])) {
			$RES->data['file'] = $this->move_file('file', 'file');
			$RES->data['file_mime'] = $_FILES['file']['type'];
		}
		
		if (!empty($_FILES['thumbnail']['name']))
			$RES->data['thumbnail'] = $this->move_file('thumbnail', 'thumbnail');
		
		// Make ghost
		$fields = $RES->data;
		$RES->data = $ORIG;
		
		$ghost = $RES->create_ghost($fields);
		
		// Add Filters
		$this->clear_filters($ghost->data['rid']);
		
		$values = array($auxdata['cat_type'], $auxdata['cat_riptype'], $auxdata['cat_gencat'], 
						$auxdata['cat_game'], $auxdata['cat_char'], $auxdata['cat_franchise']);
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
		if (!empty($IN['thumbnail_name']))
			$RES->data['thumbnail'] = $IN['thumbnail_name'];
		
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
		
		if (!empty($_FILES['thumbnail']['name']))
			$RES->data['thumbnail'] = $this->move_file('thumbnail', 'thumbnail');
		
		// Add Filters
		$this->clear_filters($IN['rid']);
		
		$values = array($auxdata['cat_type'], $auxdata['cat_riptype'], $auxdata['cat_gencat'], 
						$auxdata['cat_game'], $auxdata['cat_char'], $auxdata['cat_franchise']);
		$this->add_filters($IN['rid'], $values);
		
		// Keywords
		
		$RES->data['catwords'] = $this->make_catwords( $RES->data['rid'] );
		
		$RES->update();
		
		return $RES;
	}

}