
<?php
	require_once "engineHeader.php";

    $localvars = localvars::getInstance();

    if (!validate::getInstance()->integer($_GET['MYSQL']['imageID'])) {
        print "";
        exit;
    } 

    $db        = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO
    $sql       = sprintf("SELECT * FROM imageAds WHERE imageAds.ID = ? LIMIT 1");
    $sqlResult = $db->query($sql,array($_GET['MYSQL']['imageID']));

    $row      = $sqlResult->fetch();
    $imageURI = $row['imageAd'];

    // determine $mimeType
    $mimeType = 'image/png';

    header('Content-Type:' . $mimeType);
    print $row['imageAd'];
?>