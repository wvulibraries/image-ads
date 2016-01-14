<?php
	$patterns = templates::getTemplatePatterns();
	if (isset($patterns['formBuilder'])) {
		print '{form display="assets"}';
	}
	//echo "ECHO ECHO ECHO!";
	$localvars = localvars::getInstance();
?>

<title> Image Add Manager </title> 
<link rel="stylesheet" type="text/css" href="{local var="baseDirectory"}/css/basic.css" />
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>
