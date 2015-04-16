<?php
    $localvars = localvars::getInstance();
    $localvars->set('dbConnectionName', 'appDB');

    $db = db::get($localvars->get('dbConnectionName'));

    //echo "VARS IS WORKING!";
?>