<?php
//------------------------------------------------------------------
// Taloncrossing Submission Management System 1.0
//------------------------------------------------
// Copyright 2005 Justin Aquadro
// Modification by Mors, 2019
// 
// component/submit_done.php --
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
		
		$title = "N/A";
		if (!empty($IN['msg']))
		{
			if (!empty($IN['done']))
			{
				$title = "Notice";
			}
			else
			{
				$title = "Error";
			}
		}
		else
		{
			$IN['msg'] = '';
		}
		
		//$this->show_page($IN['msg'],$title);
		$url = $STD->encode_url($_SERVER['PHP_SELF'], "act=notice&msg=".$IN['msg']);
		
		$this->output .= $STD->global_template->page_header("$title");
		
		$this->output .= $this->html->page($url,'<span style="text-align: center; display: block;">'.htmlspecialchars(str_rot13($IN['msg']), ENT_QUOTES).'</span>');
		
		$STD->template->display( $this->output );
	}
	
	
}	
?>