<?php
    
    require_once "engineHeaderClean.php";

    if (!validate::getInstance()->integer($_GET['MYSQL']['imageID'])) {
        exit;
    }

    $localvars = localvars::getInstance();

    $db        = db::get($localvars->get('dbConnectionName')); 
    $sql       = sprintf("SELECT * FROM imageAds WHERE imageAds.ID = ? LIMIT 1");
    $sqlResult = $db->query($sql,array($_GET['MYSQL']['imageID']));
    $row       = $sqlResult->fetch();

    header('Content-Type:image/gif');
    print base64_decode($row['imageAd']);
?>