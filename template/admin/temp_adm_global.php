<?php

class template_adm_global
{

  function message(string $message): string
  {
    return <<<HTML
      <br>
      {$message}
      HTML;
  }

  function error(string $error): string
  {
    return <<<HTML
      <tr>
        <td class="header">
        Error
        </td>
      </tr>
      <tr>
        <td class="body">
          <br>
          {$error}
        </td>
      </tr>
      HTML;
  }

  function html_head(): string
  {
    global $STD;
    return <<<HTML
      <head>
        <title>Admin Control Panel</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <script>
          function quote(name, id) {
            var elem = document.notepadform.notepad;
            elem.value += "[quote="+name+"]"+document.getElementById('msg'+id).innerHTML + "[/quote]\\n";
            elem.focus();
          }
        </script>
        <script src="{$STD->tags['template_path']}/global.js"></script>
        <style>
            body { font-family: Verdana, Arial, Helvetica, Sans-Serif; font-size:10pt }
            a:link, a:visited {text-decoration:none; color: #0000FF }
            .header { background: #5D669A; color: #FFFFFF; font-size:14pt; width:100%; padding:4px; }
            .subheader { background: #7D86BA; color: #FFFFFF; font-size:13pt; width:100% }
            .body { background: #E1E4F9; color: #000000; font-size:11pt; width:100%; padding:4px; }
            .textbox { background: #F6F7FF; color:#000000; font-size:10pt; border:1px solid #000000; padding:1px }
            .selectbox { background: #F6F7FF; color:#000000; font-size:10pt }
            .button { background: #B8BDDF; color:#000000; font-size:10pt; border:1px solid #000000 }
            .category { color: #CB3723; font-size:12pt; font-weight:bold }
            .title_fixed { font-weight: bold; font-size:11pt; color: #4B4D5F; width: 30% }
            .title { font-weight: bold; font-size:11pt; color: #4B4D5F }
            .highlight { color: #2749DC }
            .highlight2 { color: #CB3723 }
            .options { font-size:12pt; color: #4B4D5F }
            .options2 { font-size:12pt; color: #4B4D5F; background-color: #C8CDEF }
            .field_fixed { font-size:12pt; color: #4B4D5F; width: 70% }
            .field { font-size:12pt; color:#4B4D5F }
            .field2 { font-size:12pt; color: #4B4D5F; background-color: #C8CDEF }
            .options_small { font-size:10pt; color: #4B4D5F }
            .options_small2 { font-size:10pt; color: #4B4D5F; background-color: #C8CDEF }
            
            .errheader { background: #F56C65; color: #FFFFFF; font-size:14pt; width:100% }
            .errbody { background: #FFCDCB; color: #000000; font-size:11pt; width:100% }
            
            .dis_button { border: 1px solid #C8CDEF; }
            .click_button { border: 1px solid #C8CDEF; }
            .click_button:hover { border: 1px solid #7D86BA; background-color: #B8BDDF; }
            .rep_box { border: 1px dashed #000000; width:90%; margin-left: auto; margin-right: auto; }
            .rep_box .quotetitle { width: 90%; margin-left: auto; margin-right: auto; border-bottom: 1px solid black; }
            .rep_box .quote { width: 90%; margin-left: auto; margin-right: auto; }
            
            .rowfield { width: 90%; border: 1px solid #000000; text-align: left; }
            .rowtable { width: 100%; border: 0px; font-size: 10pt; text-align: left; }
            .rowstrip { background-color: #9CA4D4; padding: 3px; font-weight: bold; }
            .rowcell1 { background-color: #E1E4F9; padding: 3px; }
            .rowcell2 { background-color: #C8CDEF; padding: 3px; }
            .rowcell3 { background-color: #B8BDDF; padding: 3px; }
            .rowcell4 { background-color: #99A1C9; padding: 3px; }
            .rowtitle { background-color: #7D86BA; padding: 5px; color: #FFFFFF; font-weight: bold; font-size: 10pt; }
            .rowtitle a:link, .rowtitle a:visited { color: #FFFFFF; text-decoration: underline; }
            .tabactive { background-color: #7D86BA; padding: 5px; color: #FFFFFF; font-weight: bold; border:1px solid #000000; padding:5px; border-bottom:0px; }
            .tabactive a:link, .tabactive a:visited { color: #FFFFFF; }
            .tabinactive { background-color: #B8BDDF; padding: 3px; border:1px solid #000000; border-bottom:0px; padding:3px; }
            .tabinactive a:link, .tabinactive a:visited { color: #000000; }
            .quotetitle { width: 95%; padding: 4px; padding-left: 0px; margin-left: auto; margin-right: auto; margin-top: 2px; font-size: 8pt; font-weight: bold; }
            .quote { width: 95%; padding: 4px; margin-left: auto; margin-right: auto; margin-bottom: 2px; border: 1px solid #004F00; background-color: #E1E4F9; }
            .canquote { cursor: pointer; }
            .field_fixed img { max-width: 640px;}
          .leftmenu { margin: 10px 0; }
          .leftmenu:first-child { margin-top: 0; }
        </style>
      </head>
      HTML;
  }

  function html_body(string $site_url, string $contents)
  {
    global $STD;
    return <<<HTML
      <body>
        <div style="margin: 10px">
          <table style="border-spacing:0px;border:2px solid #000000;width:100%;">
            <tr>
              <td class="header">Admin Control Panel</td>
            </tr>
            <tr>
              <td class="body">
              <table style="width:100%">
              <tr>
                <td style="width:50%" class="options">
                  <a href="{$STD->tags['root_url']}act=main">ACP Home</a> | <a href="{$site_url}">Site Home</a>
                </td>
                <td style="width:50%;text-align:right" class="options">
                  Logged in as: <b>{$STD->user['username']}</b> (<a href="{$STD->tags['root_url']}act=login&amp;param=03">Log out</a>)
                </td>
              </tr>
              <tr>
                <td style="width:100%" colspan="2" class="options">
                  <span>Nothing to see here.</span>
                <!--<a href="{$STD->tags['root_url']}act=webhook">Push the latest update to the MFGG Discord and recalculate the scores.</a>-->
                </td>
              </tr>
              </table>
              </td>
            </tr>
          </table>
        </div>
        <div style="margin: 10px">
          <table>
          <tr>
            $contents
          </tr>
          </table>
        </div>
      </body>
      HTML;
  }

  function site_menu($modq_menu)
  {
    global $STD;
    return <<<HTML
      <td style="width:15%;vertical-align:top;">
        <table class="leftmenu">
          <tr>
            <td class="header">
              Submissions
            </td>
          </tr>
          <tr>
            <td class="body" style="font-size:10pt">
              {$modq_menu}
            </td>
          </tr>
        </table>

        <table class="leftmenu">
          <tr>
            <td class="header">
            Users
            </td>
          </tr>
          <tr>
            <td class="body" style="font-size:10pt">
            :: <a href="{$STD->tags['root_url']}act=ucp&amp;param=01">Manage Users</a><br>
            :: <a href="{$STD->tags['root_url']}act=ucp&amp;param=14">Find Users</a><br>
            :: <a href="{$STD->tags['root_url']}act=ucp&amp;param=07">Manage Groups</a><br>
            :: <a href="{$STD->tags['root_url']}act=ucp&amp;param=06">Ban Settings</a>
            </td>
          </tr>
        </table>

        <table class="leftmenu">
          <tr>
            <td class="header">
            News
            </td>
          </tr>
          <tr>
            <td class="body" style="font-size:10pt">
            :: <a href="{$STD->tags['root_url']}act=news&amp;param=01">New Entry</a><br>
            :: <a href="{$STD->tags['root_url']}act=news&amp;param=03">Modify Entry</a>
            </td>
          </tr>
        </table>

        <table class="leftmenu">
          <tr>
            <td class="header">
            Manage
            </td>
          </tr>
          <tr>
            <td class="body" style="font-size:10pt">
            :: <a href="{$STD->tags['root_url']}act=manage&amp;param=01">Message Ctr</a><br>
            :: <a href="{$STD->tags['root_url']}act=manage&amp;param=05">Site On/Off</a><br>
            :: <a href="{$STD->tags['root_url']}act=conf&amp;param=01">Filter Groups</a><br>
            :: <a href="{$STD->tags['root_url']}act=panel&amp;param=01">Panels</a><br>
            :: <a href="{$STD->tags['root_url']}act=webhook">Manually Push Update to Discord</a><br>
            :: <a href="{$STD->tags['root_url']}act=manage&amp;param=08">Manually Recalculate Scores</a>
            </td>
          </tr>
        </table>

        <table class="leftmenu">
          <tr>
            <td class="header">
              Staff Graph
            </td>
          </tr>
          <tr>
            <td class="body" style="font-size:10pt">
            :: <a href="{$STD->tags['root_url']}act=staffgraph&amp;time=week">Weekly</a><br>
            :: <a href="{$STD->tags['root_url']}act=staffgraph&amp;time=month">Monthly</a><br>
            :: <a href="{$STD->tags['root_url']}act=staffgraph&amp;time=year">Yearly</a><br>
            :: <a href="{$STD->tags['root_url']}act=staffgraph">All Time</a><br>
            :: <a href="{$STD->tags['root_url']}act=staffgraph2&amp;time=year">Global Yearly</a><br>
            :: <a href="{$STD->tags['root_url']}act=staffgraph2">Global All Time</a>
            </td>
          </tr>
        </table>
      </td>
      <td style="width:2%">
        &nbsp;
      </td>
      HTML;
  }

  function content(string $content)
  {
    return <<<HTML
      <td style="width:83%;vertical-align:top">
        <table style="width:100%;border:2px solid #000000">
          $content
        </table>
      </td>
      HTML;
  }

  function page_header($title)
  {
    return <<<HTML
      <tr>
        <td class="header">
        {$title}
        </td>
      </tr>
      <tr>
        <td class="body">
      HTML;
  }

  function page_footer()
  {
    return <<<HTML
        </td>
      </tr>
      HTML;
  }

  // Not a true skin component
  function wrapper($template, $out)
  {
    global $CFG, $STD;

    $htmlHead = $template->html_head();
    $htmlBody = $template->body(
      $CFG['root_url'] . '/index.php',
      implode('', [
        $template->site_menu($STD->global_template_ui->modq_menu()),
        $template->content($out),
      ])
    );

    return <<<HTML
    <!doctype html>
    <html lang="en">
      $htmlHead
      $htmlBody
    </html>
    HTML;
  }
}