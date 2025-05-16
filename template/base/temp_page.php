<?php

class template_page {

	function invalid_module () {
	global $STD;
	return <<<HTML
		<div class="sformblock" style="text-align:center"><br>Invalid Module Requested<br>&nbsp;</div>
		HTML;
	}

	function page ($url, $content) {
	global $STD;
	return <<<HTML
		<div class="sform">
			<div class="sformblock">
				{$content}
			</div>
		</div>
		HTML;
	}

}