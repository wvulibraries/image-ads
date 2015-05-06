<?php
    function deleteRecord($imageID){
        // Delete a Record and Display Conditions
        // ========================================================================
        $localvars = localvars::getInstance();
        $db        = db::get($localvars->get('dbConnectionName'));

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
            errorHandle::errorMsg("systemsPolicyError");
         }

         $localvars->set("resultMessage",errorHandle::prettyPrint());
    }

    function deleteCanceled() {
        $cancledMsg = "<div class='warning'> Your request to delete the image has been canceled.  </div>";
        $localvars->set("resultMessage",$cancledMsg);
    }
?>
