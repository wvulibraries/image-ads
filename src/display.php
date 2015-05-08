
<?php
	require_once "engineHeader.php";

    $localvars = localvars::getInstance();

    if (!validate::getInstance()->integer($_GET['MYSQL']['imageID'])) {
        print "";
        exit;
    } 

    $db        = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO
    $sql       = sprintf("SELECT * FROM imageAds WHERE imageAds.ID = %s LIMIT 1",
        $_GET['MYSQL']['imageID']
    );
    $sqlResult = $db->query($sql);

    $row      = $sqlResult->fetch();
    $imageURI = $row['imageAd'];

    $imageParts   = explode(";", $imageURI); // split on the ; after the mime type
    $mimeType     = substr($imageParts[0], 5); // get the information after the data: text
    $imageData    = substr($imageParts[1],7); // decode the URI
    $decodedImage = base64_decode($imageData);

    header('Content-Type:' . $mimeType);
    print $imageData;
?>