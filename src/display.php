
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

    $imageURI = NULL;
    while($row = $sqlResult->fetch()) {
        $imageURI = $row['imageAd'];
    }

    $imageParts = explode(";", $imageURI); // split on the ; after the mime type
    $mimeType = substr($imageParts[0], 5); // get the information after the data: text
    $imageData = substr($imageParts[1],7); // decode the URI
    $decodedImage = base64_decode($imageData);



    header('Content-Type:' . $mimeType);
    print $imageData;
?>