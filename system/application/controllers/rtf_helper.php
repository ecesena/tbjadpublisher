<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Example use
require_once 'PHPRtfLite.php';

function generateRTF($htmlStr, $docTitle, $filename) {
	PHPRtfLite::registerAutoloader();
	$rtf = new PHPRtfLite();
	$section = $rtf->addSection();
    $section->writeText($htmlStr, new PHPRtfLite_Font(8, 'Times New Roman', '#000'), new PHPRtfLite_Font(8), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_JUSTIFY));
	$rtf->sendRtf($filename);
}

?>