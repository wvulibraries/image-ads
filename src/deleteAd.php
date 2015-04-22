<?php
    require_once "includes/engine/engineHeader.php";
    templates::display('header'); 

    // Delete a Record and Display Conditions  
    // ========================================================================
    $imageID   =  $_GET['MYSQL']['imageID']; 
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName')); 

    // DB And SQL Statements
    $sqlRemoveDispCond = sprintf("DELETE FROM displayConditions WHERE imageAdID=".$imageID);
    $sqlRemoveImg      = sprintf("DELETE FROM imageAds WHERE ID=".$imageID);

     var_dump($sqlRemoveImg);

    // // remove the image from the DB 
    // // remove the conditions associated with that image
     $db->query($sqlRemoveImg);  
     $db->query($sqlRemoveDispCond);

?>

<h2> Something Here </h2> 