<?php

class template_submit {

function submit_page ($urlparts, $token, $type_list, $def_disp) {
global $STD, $global_error;
return "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
<script src='https://mfgg.net/jquery.form.js'></script>
<script>
$(document).ready(

function upload_content() 
{
	
  var bar = $('#bar');
  var percent = $('#percent');
  $('#uploadme').ajaxForm({
    beforeSubmit: function() {
      /*document.getElementById('progress_div').style.display='block';*/
      var percentVal = '0%';
	  bar.css('background-color','#a0e57b');
      bar.width(percentVal)
      percent.html(percentVal);
    },

    uploadProgress: function(event, position, total, percentComplete) {
      var percentVal = percentComplete + '%';
      bar.width(percentVal);
      percent.html(percentVal);
    },
    
	success: function() {
		
		var error = 0;
		
		/*alert(error);*/
		if (error == 0)
		{
           var percentVal = 'Done!';
	       bar.css('background-color','#55DDFF');
           percent.html(percentVal);". //I purposely broke the line so I can disable the new uploader
		   "$('#submitstat').html('Your'ssssss submission has been uploaded! If it does not show up in <b>My Submissions</b> as (Queued). Please double check and see if you missed any details.<br><br>If your content is in Queue, it will be reviewed by our site staff before it is listed on this site. Please understand that your submission may take some time in the queue. You can check your submissions by visiting".' <a href="https://mfgg.net/index.php?act=user&param=03">My Submissions</a>.'."');
		}
		else
		{
			var percentVal = 'Fail!';
            bar.css('background-color','#CC2222');
            percent.html(percentVal);
			$('#submitstat').html('Please double-check your submission by making sure the files are correct and all of the forms are filled! In addition, please ensure the you have a stable internet connection.');
			document.getElementById('exform_submit').disabled = false;
			$('#foverlay').css('display','none');
		}
	    
	  /*jQuery.ajax({
        type: 'POST',
        url: './component/submit.php',
        dataType: 'json',
        data: {functionname: 'do_submit'},
		});*/
    },

    error: function(data) {
      var percentVal = 'Fail!';
      bar.css('background-color','#CC2222');
      percent.html(percentVal);
	  $('#submitstat').html('Please double-check your submission by making sure the files are correct and all of the forms are filled! In addition, please ensure the you have a stable internet connection.');
	  document.getElementById('exform_submit').disabled = false;
	  $('#foverlay').css('display','none');
    }
	
	/*complete: function(xhr) {
      if(xhr.responseText)
      {
        document.getElementById().innerHTML=xhr.responseText;
      }
    }*/
  }); 
});

function do_form() {
	empty = 0;
	$('textarea, select').each(function() {
		if($(this).val() === '') {
			empty = 1;
		}
	});
	if (empty == 1)
	{
		alert('There are forms that are left blank.');
	}
	else
	{
		disable_element('exform_submit');
		show('exform_submsg');
		show('progress_div');
		scroll_page();
		$('#foverlay').css('display','initial');
		upload_content();
		
		return true;
	}
	
}

function scroll_page() {
	window.scrollBy(0,100);
}
</script>
<style>
.progress 
{
  position:relative; 
  width:400px; 
  border: 1px solid #ddd; 
  padding: 1px; 
  border-radius: 3px; 
  margin: auto;
}
.bar 
{ 
  background-color: #a0e57b; 
  width:0%; 
  height:20px; 
  border-radius: 3px; 
  transition: all cubic-bezier(0, 0, 0.09, 0.92) 200ms;
}
.percent
{ 
  position:absolute; 
  display:inline-block; 
  width: 100%;
    height: 100%;
    text-align: center;
    left: 0px;
    top: 0px;
    line-height: 22px;
	text-shadow: 1px 1px #000, -1px 1px #000, -1px -1px #000, 1px -1px #000, 0px 1px #000, -1px 0px #000, 0px -1px #000, 1px 0px #000;
    font-weight: 600;
}
</style>

<div class='sform'>
  <form method='get' name='subselect' action='{$urlparts['form1']}' enctype='multipart/form-data'>
    {$urlparts['sess']}
    <input type='hidden' name='act' value='submit'>
    <input type='hidden' name='param' value='{$urlparts['param1']}'>
    <div class='sformstrip'>Choose a submission type.</div>
    <table class='sformtable' style='border-spacing:1px;'>
      <tr>
        <td class='sformleft'><label for='submissiontype'>Submission Type</label></td>
        <td class='sformright'>{$type_list}</td>
      </tr>
    </table>
  </form>

  <form method='post' name='subform' action='{$urlparts['form2']}' enctype='multipart/form-data' onsubmit='return do_form()' id='uploadme'>
    <div id='mainformcont' style='position: relative;'>
      <div id='foverlay' style='position: absolute; width: 100%; height: 100%; background: rgba(0,0,0,0.6);display:none;'></div>
      <input type='hidden' name='security_token' value='{$token}'>
      <input type='hidden' name='c' value='{$STD->tags['c']}'>
      <div id='page' style='{$def_disp['style']}'>
        {$def_disp['module']}
      </div>
      <div id='select_page' style='{$def_disp['astyle']}'>
        <div class='sformblock' style='text-align:center'><br>Select a submission type to expand the form<br>&nbsp;</div>
      </div>
      <div class='sformstrip' style='text-align:center'><input type='submit' value='Submit!' id='exform_submit' class='button'></div>
    </div>

    <div id='exform_submsg' class='sformblock' style='display:none; text-align:center'><br>
    <div class='progress' id='progress_div' style='display:none;'>
      <div class='bar' id='bar'></div>
      <div class='percent' id='percent'>0%</div>
    </div><br><br>
    <div id='submitstat'>Your submission is being transfered.  Please do not leave this page until the transfer is completed.</div><br>&nbsp;</div>
    <div id='foverlay' style='position: absolute; width: 100%; height: 100%; background: rgba(0,0,0,0.6);display:none;'></div>
  </form>
</div>
";
}

function type_select ($options) {
global $STD;
return <<<HTML
<select name="c" id="submissiontype" size="1" class="selectbox" title="Select Box" onchange="if(this.options[this.selectedIndex].value != -1){ document.subselect.submit() }">
{$options}
</select> <input type="submit" value="Change" class="button">
HTML;
}

function invalid_module () {
global $STD;
return <<<HTML
<div class="sformblock" style="text-align:center"><br>Invalid Module Requested<br>&nbsp;</div>
HTML;
}

function rules ($url, $rules, $show_extra) {
global $STD;
return <<<HTML
<div class="sform">
 <div class="sformblock" style="{$show_extra}">
  <span class='highlight' style="font-weight:bold">Welcome to the MFGG submission page.  Because this is your first time making a submission, 
  please review the rules below to ensure your submission gets posted on the site.  After you complete 
  your first submission, you will not be shown this page again.</span><br><br></div>
 <div class="sformblock">{$rules}</div>
 <div class="sformblock" style="text-align:center;{$show_extra}">
   <form method='post' action='$url'>
   <input type='checkbox' name='rules_agree' title="Rules Checkbox" class="checkbox"> I have read the rules and agree to follow them.<br>
   <input type='submit' name='rules_continue' value='Continue' class="button"></form></div>
 </div>
HTML;
}

}