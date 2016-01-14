<?php
    // Setup for Vagrant Users - Vagrant Box is the following credentials for login 
    $databaseOptions = array(
        'username' => 'username',
        'password' => 'password' 
    );
    
    // Pull in the login stuff for production value 
    require_once '/home/www.libraries.wvu.edu/phpincludes/databaseConnectors/database.lib.wvu.edu.remote.php';
     
   // DB Name and login steup for the rest of the app 
    $databaseOptions['dbName'] = 'rotatingImageAds'; // change this to your app db name 
    $db                        = db::create('mysql', $databaseOptions, 'appDB');
    //var_dump($db); // dumps the variable 

    //echo "EINCLUDES IS WORKING!";
    //
    if (EngineAPI::VERSION >= "4.0") {
    $localvars  = localvars::getInstance();
    $localvarsFunction = array($localvars,'set');
}
else {
    $localvarsFunction = array("localvars","add");
}

call_user_func_array($localvarsFunction,array("cssExt","less"));
call_user_func_array($localvarsFunction,array("cssURL","/css/2012"));
call_user_func_array($localvarsFunction,array("jsURL", "/javascript/2012"));
call_user_func_array($localvarsFunction,array("imgURL","/images/2012"));
call_user_func_array($localvarsFunction,array("styleRel","")); 
?>