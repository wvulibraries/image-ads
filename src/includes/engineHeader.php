<?php

	require_once '/home/www.libraries.wvu.edu/phpincludes/engine/engineAPI/4.0/engine.php';
	$engine = EngineAPI::singleton();
	errorHandle::errorReporting(errorHandle::E_ALL);

	// Set localVars and engineVars variables
		$localvars  = localvars::getInstance();
		$enginevars = enginevars::getInstance();
	
	// These are specific to EngineAPI and pulling the appropriate files
//		recurseInsert("acl.php","php"); 
		recurseInsert("includes/vars.php","php");
		recurseInsert('includes/engineIncludes.php',"php");

		formBuilder::ajaxHandler();

		recurseInsert("engineHeader.php","php");
		templates::load("library2012.2col");

	// Looks like I need to change this area 
    // This creates a variable for the directory to rooms folder 
    // $localvars->set("roomResBaseDir","/services/rooms");
?>