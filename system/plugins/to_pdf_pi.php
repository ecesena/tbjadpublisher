<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename) 
{
    require_once("dompdf/dompdf_config.inc.php");
    
    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->render();
	
	$opts["Attachment"] = 0;
    $dompdf->stream($filename.".pdf", $opts);
}
?>