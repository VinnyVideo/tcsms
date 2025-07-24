<?php

class template_resdb {

function filter_row ($boxes, $f_url) {
global $STD;
return <<<HTML
  <form method="post" action="{$f_url}">
    <div class="sform">
      <div class="sformstrip">Narrow Selection</div>
      <div class="sformblock">
        {$boxes}
      </div>
      <div class="sformstrip">
        <input type="submit" value="Refine Selection" class="button">
      </div>
    </div>
  </form>
<br>
HTML;
}

function filter_box ($name, $box) {
global $STD;
return <<<HTML
<table style="display:inline"><tr><td><b>{$name}:</b><br>{$box}</td></tr></table>
HTML;
}

function start_rows ($order, $order_url) {
global $STD;
$order_part = '<tr>
<td class="sformsubstrip" colspan="2">
<form method="post" action="'.$order_url.'">
<input type="submit" name="reorder" value="Re-Order" class="button">
'.$order.'
</form>
</td></tr>';
if ($order_url == "") {
  $order_part = '<tr><td class="sformstrip" colspan="2" style="text-align:center; font-weight:bold; font-size: 14pt; color:#FFF600;"><b>Top 10 Games of '.$order.'</b></td>';
}
return <<<HTML
<div class="sform">
<table class="sformtable" style="border-spacing:1px;">
{$order_part}
HTML;
}

function end_rows () {
global $STD;
return <<<HTML
HTML;
}

function row_footer ($pages, $order, $order_url) {
global $STD;

function year_entry ($list_year, $current_year) {
  global $STD;
  if ($list_year == $current_year) {
    $weight = "bold";
  }
  else {
    $weight = "normal";
  }
  $text = '<a href="'.$STD->tags['root_url'].'act=resdb&param=05&c=2&year='.$list_year.'" style="text-decoration:underline; font-weight:'.$weight.'">'.$list_year.'</a>';
  return $text;
}

$pages_part = '<tr>
<td class="sformtitle" colspan="2">
</td></tr>
</table>
<div class="sformstrip">
  Pages: '.$pages.'
</div>';
if ($pages == "") {
  if ($order == "MFGG") {
    $weight = "bold";
  }
  else {
    $weight = "normal";
  }
  $pages_part = '
  <tr>
  <td class="sformtitle" colspan="2" style="text-align: center">
  <br>
  </td></tr>
  </table>
  <div class="sformstrip">
  Years:
  <a href="'.$STD->tags['root_url'].'act=resdb&param=05&c=2" style="text-decoration:underline; font-weight:'.$weight.'">All Time</a>, 
  '.year_entry("2025", $order).', 
  '.year_entry("2024", $order).', 
  '.year_entry("2023", $order).', 
  '.year_entry("2022", $order).', 
  '.year_entry("2021", $order).', 
  '.year_entry("2020", $order).', 
  '.year_entry("2019", $order).', 
  '.year_entry("2018", $order).', 
  '.year_entry("2017", $order).', 
  '.year_entry("2016", $order).', 
  '.year_entry("2015", $order).', 
  '.year_entry("2014", $order).', 
  '.year_entry("2013", $order).', 
  '.year_entry("2012", $order).', 
  '.year_entry("2011", $order).', 
  '.year_entry("2010", $order).', 
  '.year_entry("2009", $order).', 
  '.year_entry("2008", $order).', 
  '.year_entry("2007", $order).', 
  '.year_entry("2006", $order).', 
  '.year_entry("2005", $order).', 
  '.year_entry("2004", $order).', 
  '.year_entry("2003", $order).'
  </div>';
}
return <<<HTML
{$pages_part}
</div>
<br>
HTML;
}

function version_history ($vh, $title) {
global $STD;
return <<<HTML
<div class="sform">
<div class="sformstrip">Complete Update History: {$title}</div>
<table class="sformtable" style="border-spacing:1px;">
  {$vh}
</table>
</div>
HTML;
}

function version_row ($date, $desc) {
global $STD;
return <<<HTML
<tr>
  <td class="sformleftw" style="width:20%; font-weight:bold">{$date}</td>
  <td class="sformright">{$desc}</td>
</tr>
HTML;
}

function version_empty () {
global $STD;
return <<<HTML
<tr>
  <td class="sformleftw" colspan="2" style="text-align:center">No History</td>
</tr>
HTML;
}

}
?>