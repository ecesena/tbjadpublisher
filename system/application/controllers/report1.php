<?php

class Report extends Controller {

	function Report() {
		parent::Controller();
	}

	function generateRTF($jobNumber) {
		$cd = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']) . "reports";

		require($cd . "/../system/application/config/database.php");
		$dbServer = $db['default']['hostname'];
		$dbName = $db['default']['database'];
		$dbUser = $db['default']['username'];
		$dbPass = $db['default']['password'];
		$reportName = "TBJ Proof";

		$args = array("JobNumber"=>$jobNumber, "ResourceDir"=>$cd);

		$argsStr = "";
		foreach($args as $k=>$v) {
			$argsStr .= " \"$k\" \"$v\"";
		}

		$cmd = "java -cp \"$cd/bin;$cd/bin/jasperreports-3.5.0.jar;$cd/bin/commons-digester-2.0.jar;$cd/bin/commons-logging-1.1.1.jar;$cd/bin/commons-beanutils-1.8.0.jar;$cd/bin/commons-collections-3.2.1.jar;$cd/bin/iText-2.1.5.jar;$cd/bin/mysql-connector-java-3.1.14-bin.jar\" JRGenerateRTF \"$dbServer\" \"$dbName\" \"$dbUser\" \"$dbPass\" \"$cd/$reportName\" $argsStr";
		shell_exec("$cmd");

		header("Content-Type: application/msword\n");
		header("Content-disposition: inline; filename=\"".$reportName." $jobNumber.rtf\"\n");
		echo file_get_contents($cd . "/" . $reportName . ".rtf");
	}

	function generatePDF($jobNumber) {
		$cd = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']) . "reports";

		require($cd . "/../system/application/config/database.php");
		$dbServer = $db['default']['hostname'];
		$dbName = $db['default']['database'];
		$dbUser = $db['default']['username'];
		$dbPass = $db['default']['password'];
		$reportName = "TBJ Proof";

		$args = array("JobNumber"=>$jobNumber, "ResourceDir"=>$cd);

		$argsStr = "";
		foreach($args as $k=>$v) {
			$argsStr .= " \"$k\" \"$v\"";
		}

		$cmd = "java -cp \"$cd/bin;$cd/bin/jasperreports-3.5.0.jar;$cd/bin/commons-digester-2.0.jar;$cd/bin/commons-logging-1.1.1.jar;$cd/bin/commons-beanutils-1.8.0.jar;$cd/bin/commons-collections-3.2.1.jar;$cd/bin/iText-2.1.5.jar;$cd/bin/mysql-connector-java-3.1.14-bin.jar\" JRGeneratePDF \"$dbServer\" \"$dbName\" \"$dbUser\" \"$dbPass\" \"$cd/$reportName\" $argsStr";
		shell_exec("$cmd");

		header("Content-Type: application/pdf\n");
		header("Content-disposition: inline; filename=\"".$reportName." $jobNumber.pdf\"\n");
		echo file_get_contents($cd . "/" . $reportName . ".pdf");
	}
}

?>