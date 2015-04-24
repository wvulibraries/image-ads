<?php
	$patterns = templates::getTemplatePatterns();
	if (isset($patterns['formBuilder'])) {
		print '{form display="assets"}';
	}
	//echo "ECHO ECHO ECHO!";
	$localvars = localvars::getInstance();
?>

<link rel="stylesheet" type="text/css" href="{local var="baseDirectory"}/css/basic.css" />
