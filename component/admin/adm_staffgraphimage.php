<?php

require "graph.php";

$file = file("https://mfgg.net/staff_log.txt");

$segdat = array();
$segdat[6330] = 0;		// Gatete
$segdat[7546] = 0;		// VinnyVideo
$segdat[9691] = 0;		// Mors
$segdat[10567] = 0;		// Shikaternia
$segdat[12896] = 0;		// Langton
$segdat[14786] = 0;		// Fun With Despair
$segdat[16161] = 0;		// ReynLynxPSI
$segdat[17063] = 0;		// Luigibonus
$segdat[19113] = 0;		// Matrix
$segdat[20531] = 0;		// MewMewPsychic

//retired

//$segdat[41] = 0;		// Yoshiman
//$segdat[562] = 0;		// Black Squirrel
//$segdat[2687] = 0;	// MegaTailzChao
//$segdat[7] = 0;		// ShadowMan
//$segdat[8] = 0;		// Trasher
//$segdat[13] = 0;		// Parakarry
//$segdat[3575] = 0;	// Ylle
//$segdat[68] = 0;		// Press Start
//$segdat[785] = 0;		// Pucifur
//$segdat[2867] = 0;	// Bacteriophage
//$segdat[3586] = 0;	// Char
//$segdat[7253] = 0;	// Alex
//$segdat[4748] = 0;	// Elyk
//$segdat[579] = 0;		// Guinea
//$segdat[17] = 0;		// Nite
//$segdat[1847] = 0;	// Miles
//$segdat[7029] = 0;	// DJ Coco
//$segdat[2567] = 0;	// Ultramario
//$segdat[2369] = 0;	// Gato
//$segdat[11138] = 0;	// Dustinvgmaster
//$segdat[3751] = 0;    // Chaoxys
//$segdat[4237] = 0;	// Zero Kirby
//$segdat[9318] = 0;	// Yoshin
//$segdat[4335] = 0;	// Vitiman
//$segdat[6] = 0;		// Thunder Dragon
//$segdat[9318] = 0;	// Yoshin
//$segdat[4335] = 0;	// Vitiman
//$segdat[13124] = 0;	// Cruise Elroy
//$segdat[5993] = 0;	// Yoshbert
//$segdat[14509] = 0;	// SilverVortex
//$segdat[6896] = 0;	// Hypernova
//$segdat[11667] = 0;	// EvilYoshiToes
//$segdat[2] = 0;			// Retriever II
//$segdat[53] = 0;		// Techokami

$key_list = array(6330,7546,9691,10567,12896,14786,16161,17063,19113,20531);

$cutoff = 0;
if (!empty($_GET['time']) && $_GET['time'] == 'week') {
	$cutoff = time() - 3600*24*7;
} elseif (!empty($_GET['time']) && $_GET['time'] == 'month') {
	$cutoff = time() - 3600*24*30;
} elseif (!empty($_GET['time']) && $_GET['time'] == 'year') {
	$cutoff = time() - 3600*24*365;
}

for ($x=0; $x<sizeof($file); $x++) {
	$ln = rtrim($file[$x]);
	$lns = explode("\t", $ln);
	
	$dps = explode(".", $lns[2]);
	$date = mktime(0,0,0,$dps[0],$dps[1],$dps[2]);
	
	if ($date < $cutoff) {
		continue;
	}
	
	if ( in_array( $lns[0], $key_list ) ) {
		$segdat[$lns[0]]++;
	}
}

arsort($segdat);

$segment_data = array();
$color_data = array();
$color_data[] = array('name' => 'border', 'color' => '000000');
$color_data[] = array('name' => 'bg', 'color' => 'FFFFFF');

$x = 0;

foreach( $segdat as $k=>$v ) {
	switch ($k) {
	case 6:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Thunder Dragon');
		$color_data[] = array('name' => 'c'.$x, 'color' => '0A6A13');
		break;
	case 7:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'ShadowMan');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'F7E99D');
		break;
	case 8:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Trasher');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'D11F1F');
		break;
	case 13:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Parakarry');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'FFAD1D');
		break;
	case 17:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Nite Shadow');
		$color_data[] = array('name' => 'c'.$x, 'color' => '003366');
		break;
	case 41:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'DJ Yoshiman');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'A864A8');
		break;
	case 68:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Press Start');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'E527E7');
		break;
	case 562:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Black Squirrel');
		$color_data[] = array('name' => 'c'.$x, 'color' => '760870');
		break;
	case 579:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Guinea');
		$color_data[] = array('name' => 'c'.$x, 'color' => '3CB23A');
		break;
	case 785:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Pucifur');
		$color_data[] = array('name' => 'c'.$x, 'color' => '804040');
		break;
	case 1847:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Miles');
		$color_data[] = array('name' => 'c'.$x, 'color' => '7000AF');
		break;
	case 2369:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Gato');
		$color_data[] = array('name' => 'c'.$x, 'color' => '875009');
		break;
	case 2567:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Ultramario');
		$color_data[] = array('name' => 'c'.$x, 'color' => '95A3EB');
		break;
	case 2687:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'MegaTailzChao');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'C6E2FF');
		break;
	case 2867:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Bacteriophage');
		$color_data[] = array('name' => 'c'.$x, 'color' => '7A727A');
		break;
	case 3575:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Ylle');
		$color_data[] = array('name' => 'c'.$x, 'color' => '875009');
		break;
	case 3751:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Chaoxys');
		$color_data[] = array('name' => 'c'.$x, 'color' => '6105F3');
		break;
	case 4237:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Zero Kirby');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'AA5500');
		break;
	case 4335:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Vitiman');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'FFCC00');
		break;
	case 4748:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Elyk');
		$color_data[] = array('name' => 'c'.$x, 'color' => '123CDE');
		break;
	case 5993:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Yoshbert');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'DFFF00');
		break;
	case 6330:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Gatete');
		$color_data[] = array('name' => 'c'.$x, 'color' => '95E544');
		break;
	case 6896:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Hypernova');
		$color_data[] = array('name' => 'c'.$x, 'color' => '009DFE');
		break;
	case 7029:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'DJ Coco');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'E81791');
		break;
	case 7253:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Alex');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'DFFF00');
		break;
	case 7546:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'VinnyVideo');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'FF9933');
		break;
	case 9318:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Yoshin');
		$color_data[] = array('name' => 'c'.$x, 'color' => '803A5E');
		break;
	case 9691:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Mors');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'FC1E24');
		break;
	case 11138:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Dustinvgmaster');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'C80909');
		break;
	case 11667:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'EvilYoshiToes');
		$color_data[] = array('name' => 'c'.$x, 'color' => '94D2FF');
		break;
	case 12896:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Langton');
		$color_data[] = array('name' => 'c'.$x, 'color' => '00ffdf');
		break;
	case 13124:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Cruise Elroy');
		$color_data[] = array('name' => 'c'.$x, 'color' => '2FB6FF');
		break;
	case 14786:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Fun With Despair');
		$color_data[] = array('name' => 'c'.$x, 'color' => '9500E5');
		break;
	case 16161:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'ReynLynxPSI');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'F07CBE');
		break;
	case 10567:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Shikaternia');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'FFDD00');
		break;
	case 17063:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Luigibonus');
		$color_data[] = array('name' => 'c'.$x, 'color' => '32CD32');
		break;
	case 19113:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'Matrix');
		$color_data[] = array('name' => 'c'.$x, 'color' => '3A376E');
		break;
	case 20531:
		$segment_data[] = array('data' => $v, 'exp' => 0, 'border' => 1, 'name' => 'MewMewPsychic');
		$color_data[] = array('name' => 'c'.$x, 'color' => 'FF9EEE');
		break;
	}
	
	$x++;
}

$data = array('center_x' => 140,
			  'center_y' => 140,
			  'circle_width' => 250,
			  'circle_height' => 250,
			  'image_width' => 480,
			  'image_height' => 440,
			  'colors_data' => $color_data,
			  'split_chart' => 0,
			  'segment_data' => $segment_data);

$chart = new piechart();
$chart->init(serialize($data));
$chart->build();
$chart->draw();

//echo "<pre>" . print_r($segdat,1);

?>