<?php

	require_once '/home/www.libraries.wvu.edu/phpincludes/engine/engineAPI/4.0/engine.php';
	$engine = EngineAPI::singleton();
	errorHandle::errorReporting(errorHandle::E_ALL);

	// Set localVars and engineVars variables
		$localvars  = localvars::getInstance();
		$enginevars = enginevars::getInstance();
	
	// These are specific to EngineAPI and pulling the appropriate files
	// recurseInsert("acl.php","php"); 
		recurseInsert("includes/engine/vars.php","php"); // sets vars 
		recurseInsert('includes/engine/engineIncludes.php',"php");

		formBuilder::ajaxHandler();

		recurseInsert("includes/engine/headerIncludes.php","php");
		templates::load("library2012.2col");
?>