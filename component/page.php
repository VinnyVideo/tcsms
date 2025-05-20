<?php
//------------------------------------------------------------------
// Taloncrossing Submission Management System 1.0
//------------------------------------------------
// Copyright 2005 Justin Aquadro
// Modification by Mors, 2019
// 
// component/page.php --
// An easier system for adding custom pages
//------------------------------------------------------------------

$component = new component_page;

class component_page {
	
	var $html		= "";
	var $mod_html	= "";
	var $output		= "";
	
	function init () {
		global $STD, $IN, $session;
		
		$this->html = $STD->template->useTemplate('page');
		
		$this->show_page($IN['name']);
		
		$STD->template->display( $this->output );
	}
	
	function show_page ($name) {
		global $IN, $STD;
		
		$file = file_get_contents(ROOT_PATH."component/pages/$name.txt");
		$content = substr($file, strpos($file, "\n")+1 );
		$title = strtok($file, "\n");

		$url = $STD->encode_url($_SERVER['PHP_SELF'], "act=page&name=$name");
			
		$this->output .= $STD->global_template->page_header("$title");
		
		$this->output .= $this->html->page($url,$content);
		
		$this->output .= $STD->global_template->page_footer();
	}
	
}	
?>