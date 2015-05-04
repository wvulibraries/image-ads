<?php
	$patterns = templates::getTemplatePatterns();
	if (isset($patterns['formBuilder'])) {
		print '{form display="assets"}';
	}
	//echo "ECHO ECHO ECHO!";
	$localvars = localvars::getInstance();
?>

<title> Image Add Manager </title> 
<link rel="stylesheet" type="text/css" href="css/basic.css" />
