<?php

function favorite_list_head ($html_select_submission) { ///index.php?act=user&param=11&sort={$order}&asc={$asc}
	global $STD;
	return <<<HTML
		<div class="sform">
			<form method="post" name="changetype">
				<div class="sformstrip">
					Submission:
					<select name="c" id="c" size="1" class="selectbox" title="Select Submission Category" onchange="if(this.options[this.selectedIndex].value != -1){ document.changetype.submit() }">
						{$html_select_submission}
					</select>
					<input type="submit" value="Change" class="button">
				</div>
			</form>
			<table class="sformtable" style="border-spacing:1px;">
				<tbody>
HTML;
}

function favorite_list_foot ($paginate, $sort_kind, $sort_dir) { ///index.php?act=user&param=11&c={$c}
	global $STD;
	return <<<HTML
			
					<tr>
						<td class="sformsubstrip" colspan="2">
							<form method="post">
								<input type="submit" name="reorder" value="Re-Order" title="Change Submission Order" class="button">
								<span> </span>
								<select name="sort" id="sort" size="1" title="Select Submission Type" class="selectbox">
									{$sort_kind}
								</select>
								<span> </span>
								<select name="asc" id="asc" size="1" title="Select Submission Sort Order" class="selectbox">
									{$sort_dir}
								</select>
							</form>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="sformstrip">
				Pages: {$paginate}
			</div>
		</div>
HTML;
//Pages: <span style="font-weight:normal">(1)</span>  <a href="/index.php?act=user&amp;param=11&amp;c=6&amp;o=&amp;st=0" style="text-decoration:underline; font-weight:bold">1</a>
}

function format_favrow_thumb ($res) {
global $STD;
return <<<HTML
<tr>
  <td class="sformlowline" style="padding:0px;border-right:1px solid gray;width:100px;text-align:center;">
    <a id="res_{$res['rid']}" href="{$STD->tags['root_url']}act=resdb&param=02&c={$res['c']}&id={$res['rid']}">
    {$res['thumbnail']}</a>
  </td>
  <td class="sformlowline" style="padding:0px;text-align:left;height:100px;vertical-align:top;">
    <table style="border-spacing:0px;width:100%;height:100px">
      <tr>
        <td style="height:25%;width:50%;padding:2px;" class="sformsubstrip">
          <a href="{$STD->tags['root_url']}act=resdb&param=02&c={$res['c']}&id={$res['rid']}">
          <b>{$res['title']}</b></a>
        </td>
        <td style="height:25%;width:20%;padding:2px;" class="sformstrip">
	      By: <b>{$res['author']}</b>
        </td>
        <td class="sformstrip" style="height:25%;width:30%;text-align:right;padding:2px;">
          {$res['type_title']}
        </td>
      </tr>
      <tr>
        <td style="vertical-align:top;width:100%;height:50%;padding:2px;" colspan="3">
           {$res['description']}
        </td>
      </tr>
      <tr>
        <td style="vertical-align:bottom;padding:2px;">
          <table style="border-spacing:0px;width:100%;">
            <tr>
              <td style="width:33%;padding:0px;" class="subtext">
                <span class="vertical-align:middle">Downloads: <b>{$res['downloads']}</b></span>
              </td>
              <td style="width:33%;padding:0px;">
                &nbsp;
              </td>
            </tr>
          </table>
        </td>
        <td style="vertical-align:bottom;width:100%;padding:2px;" colspan="2">
          <table style="border-spacing:0px;width:100%;">
            <tr>
              <td style="width:50%;padding:0px;" class="subtext">
                Added: {$res['created']}
              </td>
              <td style="width:50%;padding:0px;" class="subtext">
                {$res['updated']}
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
HTML;
}

function format_favrow_plain ($res) {
global $STD;
return <<<HTML
<tr>
  <!--<td class="sformlowline" style="padding:0px;border-right:1px solid gray;width:100%;" align="center">
    <a id="res_{$res['rid']}" />
  </td>-->
  <td class="sformlowline" style="padding:0px;text-align:left;height:100px;vertical-align:top;">
    <table style="border-spacing:0px;width:100%;height:100px;">
      <tr>
        <td style="height:25%;width:50%;padding:2px;" class="sformsubstrip">
          <a href="{$STD->tags['root_url']}act=resdb&param=02&c={$res['c']}&id={$res['rid']}">
          <b>{$res['title']}</b></a>
        </td>
        <td style="height:25%;width:20%;padding:2px;" class="sformstrip">
	      By: <b>{$res['author']}</b>
        </td>
        <td class="sformstrip" style="height:25%;width:30%;text-align:right;padding:1px">
          {$res['type_title']}
        </td>
      </tr>
      <tr>
        <td style="vertical-align:top;width:100%;height:50%;padding:2px;" colspan="3">
           {$res['description']}
        </td>
      </tr>
      <tr>
        <td>
          <table style="vertical-align:bottom;border-spacing:0px;width:100%;">
            <tr>
              <td style="width:33%;padding:0px;" class="subtext">
                <span class="vertical-align:middle">Downloads: <b>{$res['downloads']}</b></span>
              </td>
              <td style="width:33%;padding:0px;">
                &nbsp;
              </td>
            </tr>
          </table>
        </td>
        <td style="vertical-align:bottom;width:100%;" colspan="2">
          <table style="border-spacing:0px;width:100%;">
            <tr>
              <td style="width:50%;padding:0px;" class="subtext">
                Added: {$res['created']}
              </td>
              <td style="width:50%;padding:0px;" class="subtext">
                {$res['updated']}
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
HTML;
}

function format_favrow_icon ($res) {
global $STD;
return <<<HTML
<tr>
  <!--<td class="sformlowline" style="padding:0px;border-right:1px solid gray;width:100px;" align="center">
    <a id="res_{$res['rid']}" />
  </td>-->
  <td class="sformlowline" style="padding:0px;text-align:left;height:100px;vertical-align:top;">
    <table style="border-spacing:0px;width:100%;height:100px;">
      <tr>
        <td style="height:25%;width:50%;padding:2px;" class="sformsubstrip">
          <a href="{$STD->tags['root_url']}act=resdb&param=02&c={$res['c']}&id={$res['rid']}">
          <b>{$res['title']}</b></a>
        </td>
        <td style="height:25%;width:20%;padding:2px;" class="sformstrip">
	      By: <b>{$res['author']}</b>
        </td>
        <td class="sformstrip" style="height:25%;width:30%;text-align:right;padding:1px">
          {$res['type_title']}
        </td>
      </tr>
      <tr>
        <td style="vertical-align:top;width:100%;height:50%;padding:2px;" colspan="3">
			<table><tr>
				<td>{$res['type1']}</td>
				<td><span>{$res['description']}</span></td>
			</tr></table>
        </td>
      </tr>
      <tr>
        <td style="vertical-align:bottom;">
          <table style="border-spacing:0px;width:100%;">
            <tr>
              <td style="width:33%;padding:0px;" class="subtext">
                <span class="vertical-align:middle">Downloads: <b>{$res['downloads']}</b></span>
              </td>
              <td style="width:33%;padding:0px;">
                &nbsp;
              </td>
            </tr>
          </table>
        </td>
        <td style="vertical-align:bottom;width:100%;" colspan="2">
          <table style="border-spacing:0px;width:100%;">
            <tr>
              <td style="width:50%;padding:0px;" class="subtext">
                Added: {$res['created']}
              </td>
              <td style="width:50%;padding:0px;" class="subtext">
                {$res['updated']}
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
HTML;
}

?>