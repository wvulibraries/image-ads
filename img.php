<?php
require("engineIncludeStatement.php");
$eAPI = EngineAPI::singleton();
$eAPI->dbConnect("database","adManager",TRUE);
localvars::dbImport("settings", "id", "value");

function noImgFound($n){
    global $eAPI;
    if(function_exists('gd_info')){
        $imgX = (isset($eAPI->cleanGet['HTML']['w'])) ? (int)isset($eAPI->cleanGet['HTML']['w']) : localvars::get('maxImgWidth', 200);
        $imgY = (isset($eAPI->cleanGet['HTML']['h'])) ? (int)isset($eAPI->cleanGet['HTML']['h']) : localvars::get('maxImgHeight', 200);

        header("Content-type: image/png");
        $img = imagecreatetruecolor($imgX, $imgY);
        $colorTxt = imagecolorallocate($img, 153, 153, 153);
        imagefilledrectangle($img, 0, 0, $imgX, $imgY, imagecolorallocate($img, 255, 255, 255));
        imagestring($img, 5, 5, 5, "Image Not Available", $colorTxt);
        imagestring($img, 4, 5, 25, "[Code: $n]", $colorTxt);

        imagepng($img);
        imagedestroy($img);
    }else{
        die("Invalid ID!");
    }
}

if(is_numeric($eAPI->cleanGet['MYSQL']['id'])){
    $dbIpGroups = $eAPI->openDB->query(sprintf("SELECT `name`,`img`,`imgType` FROM `ads` WHERE `ID`='%s'", $eAPI->cleanGet['MYSQL']['id']));
    if($dbIpGroups['result'] and mysql_num_rows($dbIpGroups['result'])){
        list($imgName, $imgData, $imgType) = mysql_fetch_row($dbIpGroups['result']);
        header('Content-type: '.$imgType);
        header('Content-Disposition: filename="'.$imgName.'"');
        die($imgData);
    }else{
        // No ad found!
        noImgFound(1);
    }
}elseif($eAPI->cleanGet['MYSQL']['id'] == sessionID()){
    $sessionImg = sessionGet('adImage');
    if(isset($sessionImg)){
        header('Content-type: '.$sessionImg['type']);
        die($sessionImg['data']);
    }else{
        noImgFound(2);
    }
}else{
    noImgFound(3);
}
