<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="http://www.thebusinessjournal.com/images/favicon.ico" /> 
<link rel="stylesheet" href="<?=$this->config->item('base_url')?>css/main.css" type="text/css" />
<link rel="stylesheet" href="<?=$this->config->item('base_url')?>css/thickbox.css" type="text/css" />

<link rel="stylesheet" href="<?=$this->config->item('base_url')?>css/scal/stylemain.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=$this->config->item('base_url')?>css/scal/scal.css" type="text/css" media="screen"/>

<script src="/javascript/scripts.js" type="text/javascript"></script>
<script src="/javascript/prototype.js" type="text/javascript"></script>
<script src="/javascript/scal.js" type="text/javascript"></script>


<script src="/javascript/jquery.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery.noConflict();
</script>
<script src="/javascript/ckeditor/ckeditor.js"></script>
<script src="/javascript/ckeditor/adapters/jquery.js"></script>
<script src="/javascript/thickbox.js" type="text/javascript"></script>

<script src="/javascript/maintenance.js" type="text/javascript"></script>

<?=isset($extra_header_content) ? $extra_header_content : "" ?>

<?php
$titleArray = array("legal"=>"Legal Advertising System", "user"=>"", "maintenance"=>"");
$cssArray = array("legal"=>"legal.css");
$controller = $this->uri->segment(1);

$titleSuffix = ($controller != "" && $titleArray[$controller]!=""?" | ".$titleArray[$controller]:"");
?>
<title><?=$title_for_layout?><?=$titleSuffix?> | The Business Journal</title>

<? if($controller != "" && array_key_exists($controller, $cssArray)): ?>
<link rel="stylesheet" href="<?=$this->config->item('base_url')?>css/<?=$cssArray[$controller]?>" type="text/css" />
<? endif; ?>
</head>

<body>

<div id='<?=(isset($use_small_header) && $use_small_header==true?"headerSmall":"header")?>'>

</div>

<? if(isset($error_messages) && is_array($error_messages)): ?>
<div class="errMsg">
<?=implode("<br />", $error_messages);?>
</div>
<? endif; ?>

<? if($this->session->userdata('errs') != ""): ?>
<div class="errMsg">
<?=$this->session->userdata('errs')?>
<? $this->session->unset_userdata('errs')?>
</div>
<? endif; ?>

<div id="main_content">

<?=$content_for_layout?>

</div>


</body>
</html>