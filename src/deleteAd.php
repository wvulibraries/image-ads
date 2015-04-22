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

    // // remove the image from the DB 
    // // remove the conditions associated with that image
     $sqlImgResult = $db->query($sqlRemoveImg);     
     if($sqlImgResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlImgResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg(getResultMessage("systemsPolicyError")); 
        return false; 
     } else { 
        // Success message for 3 seconds 
        echo "<div class='success'> Your have successfully deleted the records </div>"; 
     }

     $sqlDisCondResult = $db->query($sqlRemoveDispCond);
     if($sqlDisCondResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlDisCondResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg(getResultMessage("systemsPolicyError")); 
        return false; 
     }

?>