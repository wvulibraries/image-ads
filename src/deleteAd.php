<?php
    require_once "engineHeader.php";
    templates::display('header'); 

    // Delete a Record and Display Conditions  
    // ========================================================================
    $imageID   =  $_GET['MYSQL']['imageID']; 
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName')); 

    // verify that $imageID is an integer. 

    // DB And SQL Statements
    $sqlRemoveDispCond = sprintf("DELETE FROM displayConditions WHERE imageAdID=?");
    $sqlRemoveImg      = sprintf("DELETE FROM imageAds WHERE ID=? LIMIT 1");

    // // remove the image from the DB 
    // // remove the conditions associated with that image
     $sqlImgResult = $db->query($sqlRemoveImg, array($imageID));     
     if($sqlImgResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlImgResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg('Error deleting record'); 
     } else { 
        // Success message forward back home 
       errorHandle::successMsg('You have deleted the record!');
     }

     $sqlDisCondResult = $db->query($sqlRemoveDispCond, array($imageID));
     if($sqlDisCondResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlDisCondResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg(getResultMessage("systemsPolicyError")); 
     }

     $localvars->set("resultMessage",errorHandle::prettyPrint());

?>

{local var="resultMessage"}

<section> 
    <a href="addNewImage.php" class="button"> Add New Image </a>
    <a href="index.php" class="button"> Back to Home </a>
</section>