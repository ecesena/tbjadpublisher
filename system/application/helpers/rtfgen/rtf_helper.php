<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Example use
include("class_rtf.php");
//include("Encoding.php");

function generateRTF($htmlStr, $docTitle, $filename) {
	$rtf = new rtf("config.php");
	$rtf->setTitle($docTitle);
	$rtf->addColour("#000000");
	$rtf->addText($htmlStr);
	$rtf->getDocument($filename);
}
?>
