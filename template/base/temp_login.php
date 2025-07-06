<?php

class template_login {

function register ($reg_url, $token) {
global $STD;
return <<<HTML
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="sform">
<form method="post" action="{$reg_url}">
<input type="hidden" name="security_token" value="{$token}">
<div class="sformstrip">Welcome to registration</div>
<div class="sformblock">Registering an account will allow you to submit files or comment on other user's work, among
  other things.  Fields marked with an asterisk (<span class="highlight">*</span>) are required.
  All other fields are optional.  Remember to visit your <span class="highlight">Preferences</span> page after
  registering to customize your browsing preferences.
</div>
<div class="sformstrip">Fill out the form below to register a new account</div>
<table class="sformtable">
<tr>
  <td class="sformleft"><label for="username">Username</label><span class="highlight">*</span></td>
  <td class="sformright"><input type="text" id="username" name="username" size="40" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="password">Password</label> (8 characters, at least 1 letter and 1 number)<span class="highlight">*</span></td>
  <td class="sformright"><input type="password" id="password" name="password" size="40" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="email">Email</label><span class="highlight">*</span></td>
  <td class="sformright"><input type="text" id="email" name="email" size="40" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft"><label for="image">Avatar URL</label></td>
  <td class="sformright"><input type="text" id="image" name="image" size="40" value="" class="textbox"></td>
</tr>
<!--<tr>
  <td class="sformleft">Website Name</td>
  <td class="sformright"><input type="text" name="website" size="40" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft">Website Address</td>
  <td class="sformright"><input type="text" name="weburl" value="http://" size="40" class="textbox"></td>
</tr>-->
</table>
<div class="sformstrip">Security Check</div>
<table class="sformtable">
<tr>
  <td class="sformleft">Click the checkbox right next to "I'm not a robot" to prove that you're not a spambot.</td>
  <td class="sformright">
    <!--<table border="0" cellpadding="0" cellspacing="0">
      <tr><td align="center"><img src="{$STD->tags['root_url']}act=main&param=12&dd={$token}" /></td></tr>
      <tr><td align="center"><input type="text" name="regcode" size="6" class="textbox" style="margin-top: 3px"></td></tr>
    </table>-->
    <div class="g-recaptcha" data-sitekey="6LeCUbIUAAAAAJyMRDz9a-xWkczsmXNNg_Awxurk"></div>
  </td>
</tr>
</table>
<div class="sformstrip" style="text-align:center">
  <input type="submit" name="submit" value="Register" class="button">
</div>
</form>
</div>
HTML;
}

function lost_password ($reg_url, $token) {
global $STD;
return <<<HTML
<div class="sform">
  <form method="post" action="{$reg_url}">
    <input type="hidden" name="security_token" value="{$token}">
    <div class="sformstrip">Recover a lost password</div>
    <div class="sformblock">Use this form to change your password if you've forgotton it.  A link to a page where you can choose a new password will be emailed to the address on your account.</div>
    <div class="sformstrip">Fill out the form below</div>
      <table class="sformtable">
        <tr>
          <td class="sformleft"><label for="username">Your username</label></td>
          <td class="sformright"><input type="text" id="username" name="username" size="40" class="textbox"></td>
        </tr>
      </table>
    <div class="sformstrip" style="text-align:center"><input type="submit" value="Submit" class="button"></div>
  </form>
</div>
HTML;
}

function change_password ($reg_url, $token, $cookie) {
global $STD;
return <<<HTML
<div class="sform">
<form method="post" action="{$reg_url}">
<input type="hidden" name="security_token" value="{$token}">
<input type="hidden" name="cookie" value="{$cookie}">
<div class="sformstrip">Change your password</div>
<table class="sformtable">
<tr>
  <td class="sformleft">Enter your username</td>
  <td class="sformright"><input type="text" name="username" size="40" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft">Enter new password</td>
  <td class="sformright"><input type="password" name="pass1" size="40" class="textbox"></td>
</tr>
<tr>
  <td class="sformleft">Retype new password</td>
  <td class="sformright"><input type="password" name="pass2" size="40" class="textbox"></td>
</tr>
</table>
<div class="sformstrip" style="text-align:center"><input type="submit" class="button" value="Submit"></div>
</div>
HTML;
}

}

?>