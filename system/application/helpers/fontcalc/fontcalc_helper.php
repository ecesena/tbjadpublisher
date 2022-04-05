<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
global $charwidths;
include("fonts/times.php");
include("fonts/timesb.php");
include("fonts/timesi.php");
include("fonts/timesbi.php");

function calculateWidth($htmlStr) {
	$htmlStr = $htmlStr . "  ";
	$colWidth = 2; //inches
	$fontSize = 8; //points
	
	$colPointWidth = 16519; //$colWidth * $fontSize * 72 * 1000;
	
	global $charwidths;
	$font = $charwidths['times'];
	
	$isBold = false;
	$isItalic = false;
	
	$word = "";
	$wordWidth = 0;
	$line = "";
	$lineWidth = 0;
	$curFontFace = 'n';
	$allLines = array();
	$numLines = 1;
	$ret = "";
	for($i=0; $i<=strlen($htmlStr); $i++) {
		$curChar = substr($htmlStr, $i, 1);
		
		if($curChar == " " || $curChar == "-") {
			if($wordWidth + $lineWidth <= $colPointWidth) {
				$line .= $word . " ";
				$lineWidth += $wordWidth + 100;
			} else {
				$allLines[] = $line;
				$line = $word . " ";
				$lineWidth = $wordWidth + 100;
				$numLines++;
			}
			$word = "";
			$wordWidth = 0;
		} elseif($curChar == "<") {
			$tag = "";
			$j = 1;
			while(($char = substr($htmlStr, $i+$j, 1)) && $char != ">" && $char != " ") {
				$tag .= $char;
				$j++;
			}
			
			$tag == strtolower($tag);
			if($tag == "em") {
				$isItalic = true;
				$i += $j;
			} elseif($tag == "/em") {
				$isItalic = false;
				$i += $j;
			} elseif($tag == "strong") {
				$isBold = true;
				$i += $j;
			} elseif($tag == "/strong") {
				$isBold = false;
				$i += $j;
			} elseif($tag == "br" || $tag == "br /" || $tag == "br/" || $tag == "p") {
				$allLines[] = $line;
				$line = $word . " ";
				$lineWidth = $wordWidth + 100;
				$numLines++;

				$word = "";
				$wordWidth = 0;
			} else {
				$word .= $curChar;
				$wordWidth += $font[$curFontFace][$curChar];
			}
			
			$curFontFace = "";
			if($isBold)
				$curFontFace .= "b";
			if($isItalic)
				$curFontFace .= "i";

			if($curFontFace == "")
				$curFontFace = "n";
		} else {
			$word .= $curChar;
			$wordWidth += $font[$curFontFace][$curChar];
		}
	}

	return $numLines/9.38;
}

function inchesToPages($inches) {
	$inchesPerColumn = 13.5;
	$numColumns = 5;
	
	$pageInches = $inchesPerColumn * $numColumns;
	
	return round($inches / $pageInches, 2);
}




?>