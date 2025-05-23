<?php
//------------------------------------------------------------------
// Taloncrossing Submission Management System 1.0
//------------------------------------------------
// Copyright 2005 Justin Aquadro
// 
// component/login.php --
// handles side display of login and login pages
//------------------------------------------------------------------

$component = new component_login;

class component_login {
	
	var $html	= null;
	var $output = '';
	
	function init() {
		global $IN, $STD;
		
		$this->html = $STD->template->useTemplate('login');
		
		switch ($IN['param']) {
			case 1: $this->show_register(); break;
			case 2: $this->do_login(); break;
			case 3: $this->do_logout(); break;
			case 4: $this->do_register(); break;
			case 5: $this->show_lost_password(); break;
			case 6: $this->do_password_dispatch(); break;
			case 7: $this->validate_change(); break;
			case 8: $this->do_change_password(); break;
			case 9: $this->show_lost_username(); break;
			case 10: $this->force_password_change(); break;
		}
		
		$STD->template->display( $this->output );
	}
	
	function gettime() {
		$time = time();
		return $time;
	}
	
	function show_register () {
		global $IN, $STD, $DB, $CFG, $session;
		
		$sess = $session->sess_id;
		
		$this->output .= $STD->global_template->page_header('Register');
		
		$url = $STD->encode_url($_SERVER['PHP_SELF'],'act=login&param=04');
		
		// generate anti-bot code
		
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$str = '';
	
		for ( $x=0; $x<6; $x++ ) {
			$idx = rand(0, strlen($chars) - 1 );
			$str .= $chars[$idx]; // 3/23/2025 Vinny fix - had been curly braces, a deprecated style
		}
		
		$time = time();
		$ctime = $time - 3600;
		
		$DB->query( "DELETE FROM {$CFG['db_pfx']}_sec_images 
					 WHERE sessid = '{$sess}' OR time > '$ctime'" );
		
		$DB->query( "INSERT INTO {$CFG['db_pfx']}_sec_images (sessid,time,regcode) VALUES 
					 ('{$sess}','$time','$str')" );
		
		$this->output .= $this->html->register( $url, $STD->make_form_token() );
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function do_login () {
		global $IN, $STD, $session;

		if (empty($IN['username']) || empty($IN['password']))
			$STD->error("The username or password field was left blank.");
		
		if (!$session->check_login($IN['username'], $IN['password'], 1))
			$STD->error("The username or password is incorrect");

		if ($STD->user['new_password'] == 1) {
			header("Location: ".$STD->encode_url($_SERVER['PHP_SELF']));
		}
		else { // OLD PASSWORD!!! CHANGE IT!!! NOW!!!
			header("Location: ".$_SERVER['PHP_SELF'].'?act=login&param=10');
		}
		exit;
	}
	
	function do_logout () {
		global $STD, $session;
		
		$session->check_logout();
		
		header("Location: ".$STD->encode_url($_SERVER['PHP_SELF']));
		exit;
	}
	
	function do_register () {
		global $IN, $STD, $CFG, $DB, $session;
		
		//$sess = $session->sess_id;
		
		// Form Validation
		//if (!$STD->validate_form($IN['security_token']))
		//	$STD->error("The registration request did not originate from this site, or you attempted to repeat a completed transaction.");
		
		// Captcha
		/*
		$DB->query( "SELECT regcode FROM {$CFG['db_pfx']}_sec_images WHERE sessid = '{$sess}'" );
		$row = $DB->fetch_row();
		
		if ( !$row ) {
			$STD->error( "Security Code Invalid" );
		}
		elseif ( strtolower($row['regcode']) != strtolower($IN['regcode']) ) {
			$STD->error( "Security Code Invalid" );
		}*/

		$captcha;
        if(isset($_POST['g-recaptcha-response'])){
          $captcha=$_POST['g-recaptcha-response'];
        }
        if(!$captcha){
          $STD->error( "Please complete the captcha" );
        }
        $secretKey = "6LeCUbIUAAAAAEYnvv5ZZMBEYiReXE9d7p8wtKbb";
		$ip = $_SERVER['REMOTE_ADDR'];
		
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
        $response = file_get_contents($url);
		$responseKeys = json_decode($response,true);
		
        if(!$responseKeys["success"]) {
			$STD->error( "Captcha failed." );
		}
		
		// Fields

		if (strlen($IN['password']) < 8) {
			$STD->error("Your password needs to be 8 characters or more.");
		}
		
		if (!preg_match("@[0-9]@", $IN['password'])) {
			$STD->error("Your password must include at least one number!");
		}
		
		if (!preg_match("@[a-zA-Z]@", $IN['password'])) {
			$STD->error("Your password must include at least one letter!");
		}   
		
		if (empty($IN['username']) || empty($IN['password']) || empty($IN['email']))
			$STD->error("One or more required fields were left blank.");
		
		if (!preg_match($STD->get_regex('email'), $IN['email']))
			$STD->error("Email address is invalid.");
		
		if (!preg_match($STD->get_regex('url'), $IN['image']))
			$IN['image'] = '';
		
		$IN['username'] = $STD->standard_char($IN['username']);
		
		// Check if username is a duplicate
		$user = new user;
		if ($user->getByName($IN['username']))
			$STD->error("The selected username is already in use.  Please chose another one.");
		
		// Check for banned email addresses
		$mails = explode(",", $CFG['emaillist']);
		
		// validate email banlist - make 'em lowercase to stop people from fooling me
		foreach ($mails as $fe) {
			if (empty($fe))
				continue;
				
			if (strtolower($fe) == strtolower($IN['email']))
				$STD->error("Email address is invalid.");
		}
			
		// Process Form
		$user->data['username'] = $IN['username'];
		$user->data['password'] = password_hash($IN['password'], PASSWORD_DEFAULT);
		$user->data['new_password'] = 1;
		$user->data['email']	= $IN['email'];
		$user->data['icon']		= $IN['image'];
		$user->data['skin']		= 0;
		$user->data['show_thumbs'] = 1;
		$user->data['use_comment_msg'] = 1;
		$user->data['use_comment_digest'] = 1;
		$user->data['registered_ip']	= $_SERVER['REMOTE_ADDR'];
		$user->data['cookie']	= md5(uniqid(rand()));
		$user->data['join_date']	= time();
		$user->data['gid']		= 5;
		
		if (!$user->insert())
			$STD->error("Failed to create new user account.  Please contact an Administrator.");
		
			
		// Display Success
	//	$TPL->setTemplate('message');

		$url = $STD->encode_url($_SERVER['PHP_SELF']);
		$username = htmlspecialchars($IN['username']);
		$message = "Congratulations, your new account, <b>$username</b>, has been registered.<br><br>
					Use the login form on the left side to log in for the first time.  From the menu under your name, 
					you'll be able to submit new files to the site, manage and update your existing submissions, 
					change your viewing preferences for this site, and view messages tracking your submissions.
					<p align='center'><a href='$url'>Return to the main page</a></p>";
		
		$this->output = $STD->global_template->message($message);
	//	$TPL->addTag('message', $message);
		
		$STD->clear_form_token();
	}
	
	function show_lost_password () {
		global $IN, $STD;
		
		if (!empty($STD->user['uid']))
			$STD->error("This form is for recovering lost passwords.  Please change your password in your preferences page.");
			
		$this->output .= $STD->global_template->page_header('Lost Password');
		
		$url = $STD->encode_url($_SERVER['PHP_SELF'],'act=login&param=06&type=pass');
		
		$this->output .= $this->html->lost_password( $url, $STD->make_form_token() );
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function show_lost_username () {
		global $IN, $STD;
		
		if (!empty($STD->user['uid']))
			$STD->error("This form is for recovering lost passwords.  Please change your password in your preferences page.");
			
		$this->output .= $STD->global_template->page_header('Lost Username');
		
		$url = $STD->encode_url($_SERVER['PHP_SELF'],'act=login&param=06&type=user');
		
		$this->output .= $this->html->lost_username( $url, $STD->make_form_token() );
		
		$this->output .= $STD->global_template->page_footer();
	}
	
	function do_password_dispatch () {
		global $IN, $STD, $DB, $CFG;
		
		// Form Validation
		//if (!$STD->validate_form($IN['security_token']))
		//	$STD->error("The registration request did not originate from this site, or you attempted to repeat a completed transaction.");
		
		if ($IN['type'] == 'pass' && empty($IN['username']))
			$STD->error("You must enter your username.");
		elseif ($IN['type'] == 'user' && empty($IN['email']))
			$STD->error("You must enter your email address.");
		
		// Check if username exists
		$user = new user;
		if ($IN['type'] == 'pass')
			$user->query_condition("u.username = '{$IN['username']}'");
		else
			$user->query_condition("u.email = '{$IN['email']}'");
		
		$user->getAll();
		if (!$user->nextItem()) {
			if ($IN['type'] == 'pass')
				$STD->error("The specified user does not exist.");
			else
				$STD->error("The specified email address is not on record.");
		}
		
		//if (!$user->getByName($IN['username']))
		//	$STD->error("The specified user does not exist.");
		
		// See if this user has already set a password reset request recently
		$time = time();
		$time_lim = $time - 3600;
		$DB->query("SELECT lid FROM {$CFG['db_pfx']}_mail_log 
					WHERE ip = '{$_SERVER['REMOTE_ADDR']}' AND type = 2 AND date > $time_lim");
		if ($DB->get_num_rows() > 0)
			$STD->error("You can only submit one password change request per hour.");
		
		// Dispatch an email
		
		$url = "{$CFG['root_url']}/index.php?act=login&param=07&val={$user->data['cookie']}";
		$message = "{$user->data['username']},\n\nThis email is being sent to you because you requested to recover a lost password.  This message was sent from {$_SERVER['REMOTE_ADDR']}.  If you did not request this message, or this is not your IP, ignore this message and contact an administrator.\n\nTo proceed with changing your password, follow the link below:\n$url\n\nThis link contains sensitive information and should not be shared with anyone, just as you would not share your password.\n\nBest regards,\n{$CFG['site_name']} staff\n";
		
		mail($user->data['email'], "Lost Password Request", $message);
		
		// Log action
		$DB->query("INSERT INTO {$CFG['db_pfx']}_mail_log (uid,type,date,ip,recipient) VALUES (0,
					'2',$time,'{$_SERVER['REMOTE_ADDR']}','{$user->data['uid']}')");
		
		// Done here
		
		$url = $STD->encode_url($_SERVER['PHP_SELF']);
		$message = "An email has been sent to the address on file with further instructions on changing your password.
					If no email shows up, contact an administrator.
					<p align='center'><a href='$url'>Return to the main page</a></p>";
		
		$this->output = $STD->global_template->message($message);
		
		$STD->clear_form_token();
	}
	
	function validate_change () {
		global $STD, $IN;
		
		if (empty($IN['val']))
			$STD->error("Invalid validation link supplied.");
		
		if (!empty($STD->user['uid']))
			$STD->error("This form is for recovering lost passwords.  Please change your password in your preferences page.");
		
		$user = new user;
		$user->query_condition("cookie = '{$IN['val']}'");
		$user->getAll();
		if (!$user->nextItem())
			$STD->error("Invalid validation link supplied.");
		
		$this->output .= $STD->global_template->page_header('Reset Lost Password');
		
		$url = $STD->encode_url($_SERVER['PHP_SELF'],'act=login&param=08');
		
		$this->output .= $this->html->change_password( $url, $STD->make_form_token(), $user->data['cookie'] );
		
		$this->output .= $STD->global_template->page_footer();
	}
		
	function force_password_change() {
		global $STD, $IN;

		if ($STD->user['new_password'] == 1)
			$STD->error("Please change your password in your preferences page.");

		$this->output .= $STD->global_template->page_header('Please Change Your Password to Continue');
		
		$url = $STD->encode_url($_SERVER['PHP_SELF'],'act=login&param=08');
		
		$this->output .= $this->html->change_password( $url, $STD->make_form_token(), $STD->user['cookie'] );
		
		$this->output .= $STD->global_template->page_footer();
	}
		

	function do_change_password() {
		global $STD, $IN, $session;
				
		if (strlen($IN['pass1']) < 8) {
			$STD->error("Your password needs to be 8 characters or more.");
		}
		
		if (!preg_match("@[0-9]@", $IN['pass1'])) {
			$STD->error("Your password must include at least one number!");
		}
		
		if (!preg_match("@[a-zA-Z]@", $IN['pass1'])) {
			$STD->error("Your password must include at least one letter!");
		}     
		
		if (empty($IN['username']) || empty($IN['pass1']) || empty($IN['pass2'])) {
			$STD->error("You must fill out all the fields in the form.");
		}

		if ($IN['pass1'] != $IN['pass2']) {
			$STD->error("Your passwords do not match.  Please go back and correct this.");
		}

		if (!empty($STD->user['uid']) && (md5($IN['pass1']) == $STD->user['password'] || password_verify($IN['pass1'], $STD->user['password']))) {
			$STD->error("You can't use the same password as before.");
		}

		$user = new user;
		$user->query_condition("cookie = '{$IN['cookie']}'");
		if (!$user->getByName($IN['username']))
			$STD->error("The username you supplied is not valid for this change request.");
		
		$user->data['password'] = password_hash($IN['pass1'], PASSWORD_DEFAULT);
		$user->data['new_password'] = 1;
		$user->update();

		// Log out if already logged in
		if (!empty($STD->user['uid'])) {
			$session->check_logout();
		}
		
		// Done here
		
		$url = $STD->encode_url($_SERVER['PHP_SELF']);
		$message = "Your password has successfully been changed.  You may now log in.
					<p align='center'><a href='$url'>Return to the main page</a></p>";
		
		$this->output = $STD->global_template->message($message);
		
		$STD->clear_form_token();
	}

}

?>
