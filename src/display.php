
<?php
	require_once "engineHeader.php";
    $localvars = localvars::getInstance();
    $imageID   = $_GET['MYSQL']['imageID'];

    if(validate::getInstance()->integer($imageID)){
        $sqlID = $imageID;
    } else {
        $sqlID = NULL;
    }

    $db        = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO
    $sql       = sprintf("SELECT * FROM imageAds WHERE imageAds.ID = %s",
        $sqlID
    );
    $sqlResult = $db->query($sql);

    if($sqlResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg('Error getting the weekdays to the database');
    }

    $imageURI = NULL;
    while($row = $sqlResult->fetch()) {
        $imageURI = $row['imageAd'];
    }

    print sprintf("<img src='%s'/>", $imageURI);

    $localvars->set("dbStatusImage",errorHandle::prettyPrint());
?>

{local var="dbStatusImage"}