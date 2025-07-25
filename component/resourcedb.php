<?php
//------------------------------------------------------------------
// Taloncrossing Submission Management System 1.0
//------------------------------------------------
// Copyright 2005 Justin Aquadro
// 
// component/resourcedb.php --
// Displays submissions
//------------------------------------------------------------------

$component = new component_resourcedb;

class component_resourcedb {
	
	var $html		= "";
	var $mod_html	= "";
	var $output		= "";
	
	function init () {
		global $IN, $STD;
		
		require_once ROOT_PATH.'lib/resource.php';
		
		$this->html = $STD->template->useTemplate('resdb');
		
		if (!empty($IN['c'])) {
			$module = $STD->modules->get_module($IN['c']);
			
			if (!$module)
				$STD->error("The selected module does not exist.");
			
			$this->mod_html = $STD->template->useTemplate( $module['template'] );
		}

		// If the user hasn't changed their password yet then force them to do so to view games.
		if ($STD->user['new_password'] != 1) {
			header("Location: ".$_SERVER['PHP_SELF'].'?act=login&param=10');
		}
		
		switch ($IN['param']) {
			case 1: $this->show_list(); break;
			case 2: $this->show_page(); break;
			case 3: $this->do_download(); break;
			case 4: $this->version_history(); break;
			case 5: $this->show_top_games(); break;
			case 6: $this->do_play(); break;
		}
		
		//$TPL->template = $this->output;
		$STD->template->display( $this->output );
	}
	
	function show_list() {
		global $DB, $IN, $CFG, $TAG, $STD, $session;
		
		if (empty($IN['st']))
			$IN['st'] = 0;
		
		if (empty($IN['o']))
			$IN['o'] = '';
			
		if (empty($IN['filter']))
			$IN['filter'] = '';
			
		// Should we re-format the filter?
		if (!empty($IN['filter']) && is_array($IN['filter'])) {
			$nf = '';
			reset($IN['filter']);
			//while (list($k,$v) = each($IN['filter'])) {
			foreach ( $IN['filter'] as $k => $v ) {
				if ($v > 0)
					$nf .= ",{$k}.{$v}";
			}
			$nf = preg_replace("/^,/", "", $nf);

			$url = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=01&c={$IN['c']}&o={$IN['o']}&filter=$nf");
			$url = str_replace("&amp;", "&", $url);
			header("Location: $url");
			exit;
		}
		
		// Should we re-format the order?
		if (!empty($IN['o1'])) {
			$order = "{$IN['o1']},{$IN['o2']}";
			
			$url = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=01&c={$IN['c']}&st={$IN['st']}&o=$order&filter={$IN['filter']}");
			$url = str_replace("&amp;", "&", $url);
			header("Location: $url");
			exit;
		}

		// On Then!
		$module_record = $STD->modules->get_module($IN['c']);

		if ($module_record['hidden'])
			$STD->error("This module cannot be indexed.");
			
		$module = $STD->modules->new_module($IN['c']);

		$module->init();
		
		//------------------------------------------------
		// Filter Boxes
		//------------------------------------------------
		
		if (empty($IN['filter']))
			$filter = array();
		elseif (!is_array($IN['filter'])) {
			$filter = array();
			$tfilter = explode(',', $IN['filter']);
			//while (list(,$v) = each($tfilter)) {
			foreach ( $tfilter as $v ) {
				$pair = explode(".", $v);
				$filter[$pair[0]] = $pair[1];
			}
		}
		else
			$filter = $IN['filter'];

		$DB->query("SELECT f.fid,f.gid,f.name,g.name AS group_name,g.keyword,u.precedence AS ugid ".
		           "FROM {$CFG['db_pfx']}_filter_use u ".
				   "LEFT OUTER JOIN {$CFG['db_pfx']}_filter_group g ON (g.gid = u.gid) ".
				   "LEFT OUTER JOIN {$CFG['db_pfx']}_filter_list f ON (f.gid = g.gid) ".
				   "WHERE u.mid = {$module_record['mid']} ORDER BY f.name");
		
		$groups = array();
		while ($row = $DB->fetch_row()) {
			$gid = $row['gid'];
			$uid = $row['ugid'];
			if (!isset($groups[$uid]))
				$groups[$uid] = array('narr' => array('---'), 'varr' => array(0), 'gif' => $gid, 'gn' => $row['group_name']);
			
			$groups[$uid]['gid'] = $gid;
			$groups[$uid]['narr'][] = $row['name'];
			$groups[$uid]['varr'][] = $row['fid'];
		}
		
		ksort($groups);
		
		$boxes = '';
		//while (list($k,$v) = each($groups)) {
		foreach ( $groups as $k => $v ) {
			$k = $v['gid'];
			(!empty($filter[$k]))
				? $selected = $filter[$k] : $selected = 0;
				
			$box = $STD->make_select_box("filter[$k]", $v['varr'], $v['narr'], $selected, 'selectbox');
			$boxes .= $this->html->filter_box($v['gn'], $box);
		}
		
		//------------------------------------------------
		// Start Page
		//------------------------------------------------
		
		$filter_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=01&c={$IN['c']}&o={$IN['o']}");
		
		$this->output .= $STD->global_template->page_header($module_record['full_name']);

		$this->output .= $this->html->filter_row($boxes, $filter_url);
		
		//------------------------------------------------
		// Sort out filtering, ordering, etc
		//------------------------------------------------

		$order_names = array('t' => 'Title', /*'a' => 'Author',*/ 'd' => 'Date', /*'u' => 'Last Updated',*/ 'c' => 'Comments', 'cd' => 'Comment Date');
		$order_list = array('t' => 'r.title', 'a' => "CONCAT(r.author_override,IFNULL(ru.username,''))",
						    'd' => 'r.created', 'u' => 'IF(r.update_accept_date>0,r.update_accept_date,r.created)', //OLD: 'd' => 'r.rid'; r.updated
						    'c' => 'r.comments', 'cd' => 'IF(r.comment_date>0,r.comment_date,r.created)');
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
	//	$order_links = $STD->order_links( $order_list, $order_url, $order_default );

		$selbox1 = $STD->make_select_box('o1', array_keys($order_names), array_values($order_names), $order_default[0], 'selectbox');
		$selbox2 = $STD->make_select_box('o2', array('a','d'), array('Ascending Order','Descending Order'), $order_default[1], 'selectbox');

		$order_url = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=01&c={$IN['c']}&st={$IN['st']}&filter={$IN['filter']}");

		$this->output .= $this->html->start_rows("$selbox1$selbox2", $order_url);

		//------------------------------------------------
		// Resource Rows
		//------------------------------------------------
		
		$RES = new resource;
		$RES->query_use('extention', $module_record['mid']);
		$RES->query_use('r_user');
		if (sizeof($filter) > 0)
			$RES->query_use('filter', $filter);
		$RES->query_order($order[0], $order[1]);
		$RES->query_limit($IN['st'], $STD->get_page_prefs());
		$RES->query_condition('r.queue_code IN (0,2)');
		$RES->query_condition('r.accept_date > 0');
		$RES->getByType($IN['c']);

		$rowlist = array();
		
		while ($RES->nextItem()) {
			$data = $module->resdb_prep_data($RES->data);
			$this->output .= $this->mod_html->resdb_row($data);
		}
		
		$DB->free_result();
		
		$RES->query_unuse('extention');
		$RES->query_unuse('r_user');
		$RES->clear_condition();
		$RES->query_condition('r.queue_code IN (0,2)');
		$RES->query_condition('r.accept_date > 0');
		
		//------------------------------------------------
		// Page Numbering and Ordering
		//------------------------------------------------
		
		$order_p = join(',', $order_default);
		
		$rcnt = $RES->countByType($IN['c']);
		$pages = $STD->paginate($IN['st'], $rcnt['cnt'], $STD->get_page_prefs(), "act=resdb&param=01&c={$IN['c']}&o=$order_p&filter={$IN['filter']}");
		
		$this->output .= $this->html->end_rows();
		$this->output .= $this->html->row_footer($pages, "$selbox1$selbox2", $order_url);
		
		$this->output .= $STD->global_template->page_footer();
	}


	//Show Top 10 games
	function show_top_games() {
		global $DB, $IN, $CFG, $TAG, $STD, $session;
		
		//hardcoding some stuff that used to be in parameters and user settings
		$c = 2;
		$length = 10;
		if (empty($IN['year']))
			$o = array("b", "d");
		else
			$o = array("y", "d");

		// On Then!
		$module_record = $STD->modules->get_module($c);
		$module = $STD->modules->new_module($c);

		$module->init();
		
		//------------------------------------------------
		// Start Page
		//------------------------------------------------
		
		$this->output .= $STD->global_template->page_header("MFGG Hall of Fame");
		
		//------------------------------------------------
		// Sort out filtering, ordering, etc
		//------------------------------------------------

		$order_list = array('d' => 'r.created', 'u' => 'IF(r.update_accept_date>0,r.update_accept_date,r.created)');
									
		$ex_order = $module->extra_order();
		$order_list = array_merge($order_list, $ex_order[1]);
		
		$order = $STD->order_translate( $order_list, $o );
		if (empty($IN['year']))
			$list_name = "MFGG";
		else
			$list_name = $IN['year'];
		$this->output .= $this->html->start_rows($list_name, "");

		//------------------------------------------------
		// Resource Rows
		//------------------------------------------------
		
		$RES = new resource;
		$RES->query_use('extention', $module_record['mid']);
		$RES->query_use('r_user');
		$RES->query_order($order[0], $order[1]);
		$RES->query_limit(0, $length);
		$RES->query_condition('r.queue_code IN (0,2)');
		$RES->query_condition('r.accept_date > 0');
		if ($list_name != "MFGG") {
			$RES->query_condition('r.created > '.strtotime("01.01.".$list_name));
			$RES->query_condition('r.created < '.strtotime("01.01.".(string)((int)$list_name+1)));
			$filter = array();
			$filter["6"] = "46";
			$RES->query_use('filter', $filter);
		}
		$RES->getByType($c);

		$rowlist = array();
		
		while ($RES->nextItem()) {
			$data = $module->resdb_prep_data($RES->data);
			$this->output .= $this->mod_html->resdb_row($data);
		}
		
		$DB->free_result();
		
		$RES->query_unuse('extention');
		$RES->query_unuse('r_user');
		$RES->clear_condition();
		$RES->query_condition('r.queue_code IN (0,2)');
		$RES->query_condition('r.accept_date > 0');
		
		//------------------------------------------------
		// Page Numbering and Ordering
		//------------------------------------------------
		
		$this->output .= $this->html->end_rows();
		$this->output .= $this->html->row_footer("", $list_name, "");
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function show_page () {
		global $DB, $IN, $CFG, $STD, $session;
		
		require_once ROOT_PATH.'lib/message.php';
		require_once ROOT_PATH.'component/main.php';
		
		if (!empty($IN['st']) && $IN['st'] == 'new') {
			$component->last_unread_comments(1, $IN['id'], "act=resdb&param=02&c={$IN['c']}&id={$IN['id']}");
		}
		
		$module = $STD->modules->new_module($IN['c']);
		$module->init();
		
		$module_record = $STD->modules->get_module($IN['c']);
		
		//------------------------------------------------
		// Resource
		//------------------------------------------------
	
		$RES = new resource;
		$RES->query_use('extention', $module_record['mid']);
		$RES->query_use('r_user');

		if (!is_numeric($IN['id']))
			$STD->error("Nice try, nerd!");

		if (!$RES->get($IN['id']))
			$STD->error("Invalid resource selected");
		
		if (!in_array($RES->data['queue_code'], array(0,2)) && $RES->data['uid'] != $STD->user['uid'])
			$STD->error("You do not have permission to view this resource.");

		$data = $module->resdb_prep_page_data($RES->data);
		
		$data['report_url'] = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=05&type=1&id={$IN['id']}");
		
		//FAVORITE URL GENERATION
		$small_salt = "enterSaltHere";
		$hashbrown = md5($IN['id'].$small_salt);
		$data['fav_url'] = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=17&type=1&c={$IN['c']}&rid={$IN['id']}&hash={$hashbrown}");
		$data['unfav_url'] = $STD->encode_url($_SERVER['PHP_SELF'], "act=main&param=18&type=1&c={$IN['c']}&rid={$IN['id']}&hash={$hashbrown}");
		
		//DETERMINE IF THE SUBMISSION IS FAVORITED OR NOT
		$data['my_fav'] = false;
		$DB->query("SELECT * FROM {$CFG['db_pfx']}_bookmarks WHERE rid={$IN['id']} AND uid={$STD->user['uid']}");
		if ($DB->get_num_rows() > 0) {
			$data['my_fav'] = true;
		}
		
		//GET NUMBER OF FAVS ON ME
		$DB->query("SELECT * FROM {$CFG['db_pfx']}_bookmarks WHERE rid={$IN['id']}");
		$data['total_fav'] = $DB->get_num_rows();
		
		$this->output .= $STD->global_template->page_header($module_record['full_name']);
		$this->output .= $this->mod_html->resdb_page($data);
		
		//------------------------------------------------
		// Comments
		//------------------------------------------------

		$this->output .= $component->build_comments(1, $IN['id'], "act=resdb&param=02&c={$IN['c']}&id={$IN['id']}");
		
		$this->output .= $STD->global_template->page_footer();
		
		$RES->data['views']++;
		$RES->update();
		
		$session->touch_data ('rr');
		//var_dump($STD->user['last_visit']);
		//var_dump($RES->data['comment_date']);
		if (($STD->user['last_visit'] != 0) && ($RES->data['comment_date'] > $STD->user['last_visit'])) {
			//$session->data['rr'][$RES->data['rid']] = time();
			$session->data['rr'][$RES->data['rid']] = 1; // 5/31/2025 change - I think this is safer than using the time function
			//$session->add_read_resource($RES->data['rid']);
			//$session->save_read_resources();
		}
	}
	
	function do_download () {
		global $DB, $IN, $CFG, $STD, $session;
		
		require ROOT_PATH.'mime_info.php';
		
		$valid = explode(",", $CFG['link_domains']);
		$pass = 0;
		
		foreach ($valid as $v) {
			if (!isset($_SERVER['HTTP_REFERER'])) {
				$pass = 1;
			} else if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $v) !== FALSE) {
				$pass = 1;
			}
		}
		
		if (!$pass) {
			$newurl = $STD->encode_url($_SERVER['PHP_SELF'], "act=resdb&param=02&c={$IN['c']}&id={$IN['id']}");
			$STD->error("You are attempting to download a file from another site.  
						 Not only is hotlinking a theft of our bandwidth, but the sites that typically hotlink our 
						 files have no permission from the authors to distribute their work.<br><br>
						 You can download this file by <a href='$newurl'>visiting its page</a>.");
		}
		
		$module = $STD->modules->new_module($IN['c']);
		$module->init();
		
		$module_record = $STD->modules->get_module($IN['c']);
		
		$RES = new resource;
		$RES->query_use('extention', $module_record['mid']);
		
		if (!$RES->get($IN['id']))
			$STD->error("Invalid resource selected");
		
		if (!in_array($RES->data['queue_code'], array(0,2)) && $RES->data['uid'] != $STD->user['uid'])
			$STD->error("You do not have permission to view this resource.");

		$file = ROOT_PATH."file/{$IN['c']}/{$RES->data['file']}";

		if (!file_exists($file))	
			$STD->error("The download for <b>{$RES->data['title']}</b> does not exist.");
		
		// count consecutive downloads
		$session->touch_data ('last_dl', 'consec_dl');
		
		if ($session->data['last_dl'] == $IN['id'])
			$session->data['consec_dl']++;
		else
			$session->data['consec_dl'] = 1;
		
		if ($session->data['consec_dl'] > $CFG['max_consec_dl'])
			$STD->error("You cannot download the same submission more than {$CFG['max_consec_dl']} times consecutively.");
		
		$session->data['last_dl'] = $IN['id'];
		
		// Fetch file data
		$filesize = filesize($file);
		
		$type = $RES->data['file_mime'];
		$name = preg_replace("/[0-9]*(\.\w+)$/", "\\1", $RES->data['file']);
		
		$RES->data['downloads']++;
		$RES->update();

		// extention..
		$filebits = explode('.', $file);
		$ext = strtoupper( $filebits[ sizeof($filebits)-1 ] );
		
		$disposition = "attachment";
		
		if (isset($MIME_INFO[$ext]))
			$disposition = $MIME_INFO[$ext][1];
		
		while (ob_end_clean());
		//session_write_close();
		
		/*if (preg_match ("/gzip/i", $_SERVER['HTTP_ACCEPT_ENCODING']) ) {
			$cachefile = ROOT_PATH."file/c_{$IN['c']}/{$RES->data['file']}.gz";
			if (!file_exists ($cachefile) ) {
				$dt = file_get_contents ($file);
				$gzdt = gzencode ($dt, 9);
				$gzfp = fopen ($cachefile, 'w');
				fwrite ($gzfp, $gzdt);
				fclose ($gzfp);
			}
			$filesize = filesize ($cachefile);
			$file = $cachefile;
			
			header("Content-Encoding: gzip");
		}*/
	
		header("Cache-Control: ");
		header("Pragma: ");
		header("Content-Length: {$filesize}");
		header("Content-Type: {$type}");
		header("Content-Transfer-Encoding: binary");
		
		header("Content-Disposition: {$disposition}; filename=\"{$name}\"");
		header("Content-Description: \"{$name}\"");

		$fp = fopen($file, "rb");
		fpassthru($fp);
		fclose($fp);

		exit;
	}
	
	function do_play () {
		global $IN, $STD;
		
		$module = $STD->modules->new_module($IN['c']);
		$module->init();
		$module_record = $STD->modules->get_module($IN['c']);
		$RES = new resource;
		$RES->query_use('extention', $module_record['mid']);
		
		if (!$RES->get($IN['id']))
			$STD->error("Invalid resource selected");
		
		if (!in_array($RES->data['queue_code'], array(0,2)) && $RES->data['uid'] != $STD->user['uid'])
			$STD->error("You do not have permission to view this resource.");
		
		/*var_dump("Module: ", $module, "<br><br>");
		var_dump("Module Record: ", $module_record, "<br><br>");
		var_dump("IN['c']: ", $IN['c'], "<br><br>");
		var_dump("IN['id']: ", $IN['id'], "<br><br>");
		var_dump("RES: ", $RES, "<br><br>");
		var_dump("module_record[mid]: ", $module_record['mid'], "<br><br>");
		var_dump("RES->data: ", $RES->data, "<br><br>");
		var_dump("Queue Code: ", $RES->data['queue_code'], "<br><br>");
		var_dump("RES->data[plays]: ", $RES->data['plays'], "<br><br>");
		var_dump("RES->data[downloads]: ", $RES->data['downloads'], "<br><br>");
		var_dump("RES->data[file_html5]: ", $RES->data['file_html5'], "<br><br>");
		var_dump($STD->encode_url("https://mfgg.net/html5/{$RES->data['file_html5']}/"));*/
		$RES->data['plays']++;
		$RES->update();
		
		$redirect_url = $STD->encode_url("https://mfgg.net/html5/{$RES->data['file_html5']}/");
		
		header("Location: $redirect_url");
		exit;
	}
	
	function version_history () {
		global $DB, $IN, $CFG, $STD, $session;
		
		$RES = new resource;
		
		if (!$RES->get($IN['rid']))
			$STD->error("Invalid resource selected");
		
		if (!in_array($RES->data['queue_code'], array(0,2)) && $RES->data['uid'] != $STD->user['uid'])
			$STD->error("You do not have permission to view this resource.");
		
		$fields = array('rid'	=> $IN['rid']);
		$where = $DB->format_db_where_string($fields);
		
		$list = $DB->query("SELECT * FROM {$CFG['db_pfx']}_version WHERE {$where} ORDER BY date DESC");
		
		$vh = '';
		if ($DB->get_num_rows() == 0)
			$vh = $this->html->version_empty();
		
		while ($row = $DB->fetch_row()) {
			$vdate = $STD->make_date_short($row['date']);
			$vh .= $this->html->version_row($vdate, $row['change']);
		}
		
		$STD->popup_window = 1;
		$this->output .= $this->html->version_history($vh, $RES->data['title']);
	}
}
?>