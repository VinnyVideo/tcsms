<?php

$component = new component_adm_staffgraph;

class component_adm_staffgraph {
	
	var $html		= "";
	var $output		= "";
	
	function init () {
		global $STD, $IN;
		
		$this->html = $STD->template->useTemplate('adm_conf');
		
		$this->output .= $STD->global_template->page_header("Staff Graph");
		if (empty($_GET['time']))
			$this->output .= $STD->global_template->message("<img src=\"/admin.php?act=staffgraphimage\" alt='Staff Graph'>");
		else
			$this->output .= $STD->global_template->message("<img src=\"/admin.php?act=staffgraphimage&time=".$_GET['time']."\" alt='Staff Graph'>");

		$STD->template->display( $this->output );
	}
}
?>