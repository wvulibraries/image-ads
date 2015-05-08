<?php
function getImagesFromDB() {
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO
    $sql       = sprintf("SELECT imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.monday, displayConditions.tuesday, displayConditions.wednesday, displayConditions.thursday, displayConditions.friday, displayConditions.saturday, displayConditions.sunday, displayConditions.timeStart, displayConditions.timeEnd FROM imageAds LEFT JOIN displayConditions ON displayConditions.imageAdID = imageAds.ID");
    $sqlResult = $db->query($sql);
    $data      = NULL;
    $URLpath = "http://$_SERVER[HTTP_HOST]/admin/image_manager";

    if ($sqlResult->error()) {
        print "ERROR GETTING ADS  -- the error -- " . $sqlResult->errorMsg();
        return FALSE;
    }

    if ($sqlResult->rowCount() < 1) {
        print '
                <div class="getStarted">
                    <h2> Project Description </h2>
                    <p>
                        It looks like you have not added any images yet, so you have come to this page.
                        The purpose of this application is to manage images for the Library homepage.
                        There will be many features that you can add and disable as create new images that
                        will work as controls for how the image will display.
                    </p>
                    <h3> Getting Started! </h3>
                    <p>
                        For best use, please use images that have been created by a designer to match
                        size and brand restrictions that may be in place.  Once you have your images,
                        simply click on <a href="{local var="baseDirectory"}/addNewImage/"> <i class="fa fa-plus-square"></i> Add New Images</a>, located in the left menu.
                    </p>
                </div>
              ';
        return FALSE;
    }

    $displayAdRecords = array();  // Create an Array to parse the data in there

    while($row = $sqlResult->fetch()) {

        $tempAdArray = array(
            'name'      => htmlSanitize($row['name']),
            'ID'        => $row['ID'],
            'enabled'   => $row['enabled'],
            'priority'  => $row['priority'],
            'altText'   => htmlSanitize($row['altText']),
            'actionURL' => htmlSanitize($row['actionURL']),
        );

        $tempDispArray = array(
            'dateStart' => $row['dateStart'],
            'dateEnd'   => $row['dateEnd'],
            'timeStart' => $row['timeStart'],
            'timeEnd'   => $row['timeEnd'],
        );

        $tempWeekdayArray = array(
            'monday'    => $row['monday'],
            'tuesday'   => $row['tuesday'],
            'wednesday' => $row['wednesday'],
            'thursday'  => $row['thursday'],
            'friday'    => $row['friday'],
            'saturday'  => $row['saturday'],
            'sunday'    => $row['sunday']
        );

        $tempDispArray['weekdays'] = $tempWeekdayArray;

        if(!array_key_exists('imageAd', $tempAdArray)){
            $imageURL = sprintf("%s/display.php?imageID=%s",
                    $URLpath,
                    $row['ID']
            );
            $tempAdArray['imageAd'] = $imageURL;
        }

        $displayAdRecords[$row['ID']]["imageInfo"] = $tempAdArray;
        $displayAdRecords[$row['ID']]['display'][] = $tempDispArray;
    }

    return $displayAdRecords;
}



?>
