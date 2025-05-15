<?php

class template_global
{
  private string $title ='';

  function message($msg)
  {
    return <<<HTML
      <br>
      <div class="sform" style="width:75%; margin-left:auto; margin-right:auto">
        <table class="sformtable">
          <tr>
            <td style="width:100%;">$msg</td>
          </tr>
        </table>
      </div>
      HTML;
  }

  function error($msg)
  {
    return <<<HTML
      <br>
      <div class="sform" style="width:75%; margin-left:auto; margin-right:auto">
        <div class="sformstrip">Error</div>
        <table class="sformtable">
          <tr>
            <td>
              {$msg}
              <p><a href='javascript:history.go(-1)'>Return to previous page</a></p>
            </td>
          </tr>
          <tr><td class="sformdark"></td></tr>
        </table>
      </div>
      HTML;
  }

  function offline($msg)
  {
    global $STD;
    return <<<HTML
      <br>
      <div class="sform" style="width:75%; margin-left:auto; margin-right:auto">
        <div class="sformstrip">Site Offline</div>
        <div class="sformblock">$msg</div>
        <div class="sformstrip">Maintenance Login</div>
        <form method="post" action="{$STD->tags['root_url']}act=login&amp;param=02">
          <table class="sformtable">
            <tr>
              <td class="sformleft">Username</td>
              <td class="sformright">
                <input type="text" name="username" size="40" class="textbox">
              </td>
            </tr>
            <tr>
              <td class="sformleft">Password</td>
              <td class="sformright">
                <input type="password" name="password" size="40" class="textbox">
              </td>
            </tr>
          </table>
          <div class="sformstrip" style="text-align:center">
            <input type="submit" value="Login" class="button">
          </div>
        </form>
      </div>
      HTML;
}

  function html_head()
  {
    global $STD;

    $title = ($this->title ? "{$this->title} - " : '') . 'MFGG - Mario Fan Games Galaxy';
    
    return <<<"HTML"
      <head>
        <title>{$title}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon" href="{$STD->tags['template_path']}/favicon.ico">
        <link rel="stylesheet" href="{$STD->tags['template_path']}/css/style.css" type="text/css">
        <link rel="stylesheet" href="{$STD->tags['base_template_path']}/css/style.css" type="text/css">
        <!--<script src="https://mfgg.net/snow.js"></script>-->
        <base href="https://mfgg.net/">
      </head>
      HTML;
  }

  function site_header()
  {
    global $STD;
    return <<<HTML
      <div class="header">
        <div style="background: url({$STD->tags['image_path']}/header_br.png); border-spacing:0px; width:100%;">
          <a href="{$STD->tags['root_url']}act=main">
            <img src="{$STD->tags['image_path']}/header.png" alt="Logo Image" class="logo" style="display:inline; vertical-align:middle">
          </a>
        </div>
      </div>
      HTML;
  }

  function site_menu()
  {
    global $STD;

    $login = match (true) {
      !$STD->user['uid'] => $this->menu_login(),
      !empty($STD->user['acp_access'])
      => $this->menu_loggedin($STD->user['username'], $STD->user['new_msgs'], $this->admin_link()),
      default
      => $this->menu_loggedin($STD->user['username'], $STD->user['new_msgs'], ''),
    };

    $affiliates = file_get_contents(dirname(__FILE__) . "/../affiliates.html");
    return <<<HTML
      <aside style="width:160px">
        <div class="canvas_left">
          <div class="menu">
            <div class="menutitle2">Main</div>
            <div class="menusection">
              <div class="menuitemsidebar"><a href="{$STD->tags['root_url']}act=main">Home<img alt="Home" src="{$STD->tags['global_image_path']}/mainMainHome.png"></a></div>
              <div class="menuitemsidebar"><a href="{$STD->tags['root_url']}act=staff">Staff<img alt="Staff" src="{$STD->tags['global_image_path']}/mainMainStaff.png"></a></div>
              <div class="menuitemsidebarlong"><a href="{$STD->tags['root_url']}act=submit&amp;param=01"><span>Submission Rules</span><img alt="Submission Rules" src="{$STD->tags['global_image_path']}/mainMainRules.png"></a></div>
              <div class="menuitemsidebarlong"><a href="{$STD->tags['root_url']}act=page&amp;name=rules"><span>Comment Rules</span><img alt="Comment/Message Rules" src="{$STD->tags['global_image_path']}/mainCommRules.png"></a></div>
              <div class="menuitemsidebarlong"><a href="{$STD->tags['root_url']}act=resdb&amp;param=05&amp;c=2"><span>Hall of Fame</span><img alt="MFGG Hall of Fame" src="{$STD->tags['global_image_path']}/mainMainHall.png"></a></div>
            </div>
          </div>
          <div class="menu">
            <div class="menutitle2">Community</div>
            <div class="menusection">
              <div class="menuitemsidebar"><a href="https://forums.mfgg.net/">Forums<img alt="Forums" src="{$STD->tags['global_image_path']}/mainCommForums.png"></a></div>
              <div class="menuitemsidebar"><a href="https://discord.gg/jchgfw5" target="_blank">Discord<img alt="Discord" src="{$STD->tags['global_image_path']}/mainCommDiscord.png"></a></div>
              <div class="menuitemsidebar"><a href="https://wiki.mfgg.net/">Wiki<img alt="Wiki" src="template/images/mainCommWiki.png"></a></div>
              <div class="menuitemsidebar"><a href="https://twitter.com/OfficialMFGG">Twitter<img alt="Twitter" src="template/images/mainCommTwitter.png"></a></div>
            </div>
          </div>
          <div class="menu">
            <div class="menutitle2">Content</div>
            <div class="menusection">
              <div class="menuitemsidebar"><a href="{$STD->tags['root_url']}act=resdb&amp;param=01&amp;c=2">Games<img alt="Games" src="{$STD->tags['global_image_path']}/mainContentGames.png"></a></div>
              <div class="menuitemsidebar"><a href="{$STD->tags['root_url']}act=resdb&amp;param=01&amp;c=7">Hacks<img alt="Hacks and Mods" src="{$STD->tags['global_image_path']}/mainMainBPS.png"></a></div>
              <div class="menuitemsidebar"><a href="{$STD->tags['root_url']}act=resdb&amp;param=01&amp;c=1">Sprites<img alt="Sprites" src="{$STD->tags['global_image_path']}/mainContentSprites.png"></a></div>
              <div class="menuitemsidebar"><a href="{$STD->tags['root_url']}act=resdb&amp;param=01&amp;c=4">Tutorials<img alt="Tutorials" src="{$STD->tags['global_image_path']}/mainContentHowto.png"></a></div>
              <div class="menuitemsidebar"><a href="{$STD->tags['root_url']}act=resdb&amp;param=01&amp;c=5">Sounds<img alt="Sounds" src="{$STD->tags['global_image_path']}/mainContentSounds.png"></a></div>
              <div class="menuitemsidebar"><a href="{$STD->tags['root_url']}act=resdb&amp;param=01&amp;c=6">Misc<img alt="Misc" src="{$STD->tags['global_image_path']}/mainContentMisc.png"></a></div>
            </div>
          </div>
          <div class="menu">
            <div class="menutitle2">Other</div>
            <div class="menusection">
              <div class="menuitemsidebarlong"><a href="{$STD->tags['root_url']}act=main&amp;param=08"><span>Updates Archive</span><img alt="Archives" src="{$STD->tags['global_image_path']}/mainMainArchive.png"></a></div>
              <div class="menuitemsidebarlong"><a href="{$STD->tags['root_url']}act=page&amp;name=patcher"><span>BPS Patcher</span><img alt="BPS Patcher" src="{$STD->tags['global_image_path']}/mainMainBPS.png"></a></div>
              <div class="menuitemsidebarlong"><a href="{$STD->tags['root_url']}act=main&amp;param=19"><span>New Comments</span><img alt="New Comments" src="{$STD->tags['global_image_path']}/mainCommRules.png"></a></div>
            </div>
          </div>
          <div class="menu">
            <div class="menutitle2">Search</div>
            <div class="menusection">
              <form method="get" action="{$STD->tags['root_url']}">
                <input type="hidden" name="act" value="search">
                <input type="hidden" name="param" value="02">
                <div class="menuitem">
                  <input type="text" name="search" title="Search" size="14" class="sidetextbox">
                </div>
                <div class="menuitem">
                  <input type="submit" value="Go" class="sidebutton"> 
                  <a href="{$STD->tags['root_url']}act=search&amp;param=01">Advanced</a>
                </div>
              </form>
            </div>
          </div>
        </div>
        <br>
        {$login}
        <br>
        <div class="menu">
            {$affiliates}
            <br>
            <div class="menu" style="padding: 0px; border:0px solid #444444; border-bottom: 0px solid #444444; display: none;">
              <div style="background: none; height: 6px;"></div>
              <div class="menusection" style="padding: 0px; background-color: #585858;"></div>
            </div>
        </div>
      </aside>
      HTML;
  }

  function menu_login()
  {
    global $STD;
    return <<<HTML
      <form method="post" action="{$STD->tags['root_url']}act=login&amp;param=02">
        <div class="menu">
            <div class="menutitle">Login</div>
            <div class="menusection">
              <div class="menuitem">
                  <input type="text" name="username" placeholder="username" title="Enter your username" size="13" class="sidetextbox">
              </div>
              <div class="menuitem">
                  <input type="password" name="password" placeholder="password" title="Enter your password" size="13" class="sidetextbox">
              </div>
              <div class="menuitem" style="font-size:8pt">
                  <a href="{$STD->tags['root_url']}act=login&amp;param=05">I lost my password</a>
              </div>
              <div class="menuitem">
                  <input type="submit" name="submit" value="Login" class="sidebutton">
              </div>
            </div>
            <div class="menutitle">Not Registered?</div>
            <div class="menusection">
              <div class="menuitem">
                <a href="{$STD->tags['root_url']}act=login&amp;param=01" title="Register!">
                  <img src="{$STD->tags['global_image_path']}/signup.png" alt="Sign Up">
                </a>
              </div>
            </div>
        </div>
      </form>
      HTML;
  }

  function menu_loggedin($username, $messages, $extra)
  {
    global $STD;
    return <<<HTML
      <div class="menu">
        <div class="menutitle">
          <a href="{$STD->tags['root_url']}act=user&amp;param=01&amp;uid={$STD->user['uid']}">{$username}</a>
        </div>
        <div class="menusection">
            <div class="menuitem"><a href="{$STD->tags['root_url']}act=user&amp;param=02">Preferences</a></div>
            <div class="menuitem"><a href="{$STD->tags['root_url']}act=msg&amp;param=01">Messages ({$messages})</a></div>
            <div class="menuitem"><a href="{$STD->tags['root_url']}act=user&amp;param=11">My Favorites</a></div>
            <div class="menuitem"><a href="{$STD->tags['root_url']}act=user&amp;param=03">My Submissions</a></div>
            <div class="menuitem"><a href="{$STD->tags['root_url']}act=submit&amp;param=02">Submit File</a></div>
            <div class="menuitem"><a href="{$STD->tags['root_url']}act=login&amp;param=03">Log Out</a></div>
            {$extra}
        </div>
      </div>
      HTML;
  }

  function content($content)
  {
    global $STD;
    return <<<HTML
      <div class="canvas_center">
          <br>
          {$STD->global_template_ui->new_message()}
          {$content}
      </div>
      HTML;
  }

  function page_header($title)
  {
    global $STD;

    $this->title = $title;

    if (in_array($STD->tags["skin"], [3, 6, 7])) {
      return <<<HTML
        <div class="header_region">
          <table style="width:100%; margin-left: auto; margin-right: auto; border-spacing:0px;">
            <tr>
              <td style="background:url({$STD->tags['image_path']}/pipe_bg.gif); padding:0px;">
                <img src="{$STD->tags['image_path']}/pipe_left.gif" style="float:left" alt="Left Pipe Background">
              </td>
              <td style="background:url({$STD->tags['image_path']}/pipe_bg.gif); padding:0px;">
                <img src="{$STD->tags['image_path']}/pipe_right.gif" alt="Right Pipe Background" style="display:inline; vertical-align: top">
                <span class="boxpageheader">{$title}</span>
                <img src="{$STD->tags['image_path']}/pipe_left.gif" alt="Left Pipe Background" style="display:inline; vertical-align: top">
              </td>
              <td style="background:url({$STD->tags['image_path']}/pipe_bg.gif); padding:0px;">
                <img src="{$STD->tags['image_path']}/pipe_right.gif" style="float:right" alt="Right Pipe Background">
              </td>
            </tr>
          </table>
        </div>
        HTML;
    }


    return <<<HTML
      <div class="navigation">{$title}</div>
      <div class="header_region" style="text-align:center"></div>
      HTML;
  }

  function page_footer()
  {
    return '';
  }

  function site_footer()
  {
    return <<<HTML
      <footer class="canvas_center copyright">
        All Nintendo material is &copy; Nintendo. MFGG does not own any user-submitted content, which is &copy; the
        submitter or a third party. All remaining material is &copy; MFGG. MFGG is a non-profit site with no affiliation to Nintendo.
        <br><br>
        <div style="text-align:center;">
          Powered by Taloncrossing SMS v1.1.1, &copy; 2006-2025 <a href='https://www.taloncrossing.com' class='outlink'>Taloncrossing.com</a>. Modified by Hypernova, Mors, and VinnyVideo.
        </div>
        <br>
      </footer>
      HTML;
  }

  function new_messages($msg)
  {
    return <<<HTML
      <div class="message">You have a new message: <b>$msg</b></div>
      <br>
      HTML;
  }

  function admin_link()
  {
    return <<<HTML
      <hr>
      <div class="menuitem"><a href="admin.php">Admin CP</a></div>
      HTML;
  }

  // Not a true skin component
  function wrapper($template, $out)
  {
    global $STD;

    $container = match (true) {
      isset($STD->offline) => $template->offline($out),
      isset($STD->popup_window) => $out,
      default => implode('', [
        $template->site_menu(),
        $template->content($out),
      ])
    };

    return <<<HTML
      <!doctype html>
      <html lang="en">
        {$template->html_head()}
        <body>
          <script src="{$STD->tags['template_path']}/js/global.js"></script>
          {$template->site_header()}
          <div class="container">
            $container
          </div>
          {$template->site_footer()}
          <script src="{$STD->tags['base_template_path']}/js/bigpicture.js"></script>
        </body>
      </html>
      HTML;
  }
}
