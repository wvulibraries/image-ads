<?php

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
            'imageAd'   => $row['imageAd'],
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
            //'weekdays'  => $row['weekdays']
        );

        $displayAdRecords[$row['ID']]["imageInfo"] = $tempAdArray;
        $displayAdRecords[$row['ID']]['display'][] = $tempDispArray;
    }


    foreach($displayAdRecords as $imageRecords) {
        $imgID; // Create Varible for the ID
        $imgName; // Creat Variable for the Name

        print "<ul class='current-images'>";

        foreach($imageRecords["imageInfo"] as $recordsIndex => $imgProperties) {
            if($recordsIndex == "name") {
                $imgName = $imgProperties;
                print "<h2>" . $imgProperties . "</h2>";
            }
            elseif($recordsIndex == "imageAd"){
                echo '<img src="', $imgProperties ,'"> ';
            }
            elseif ($recordsIndex == "ID") {
                $imgID = $imgProperties;
            }
            else if ($recordsIndex == "enabled"){
                print "<li>";
                if($imgProperties == 0 ){
                   print  "<div class='image-status disabled'><span class='display'> <i class='fa fa-eye-slash'></i> Disabled </span></div>";
                }
                else {
                    print "<div class='image-status enabled'><span class='display'> <i class='fa fa-eye'></i> Enabled </span></div>";
                }
            }
            else if ($recordsIndex == "priority"){
                if($imgProperties == 0 ){
                   print  "<div class='image-priority low'> <span class='display'> <i class='fa fa-circle-thin'></i> Low Priority </span></div>";
                }
                else {
                    print "<div class='image-priority high'><span class='display'> <i class='fa fa-exclamation-circle'></i> High Priority </span></div>";
                }
                print "</li>";
            }
            else if ($recordsIndex == "altText" ) {
                print " <h3> Alt Text: </h3> <li>";
                print $imgProperties . "</li>";
            }
            else if ($recordsIndex == "actionURL" ) {
                print " <h3> Link: </h3> <li>";
                print "<a href='" . $imgProperties . "'>" . $imgProperties . "</a></li>";
            }
            else {
                print "<li>" . $imgProperties . "</li>";
            }
        }

        print " <h3> Display Dates: </h3> ";

        foreach($imageRecords["display"] as $index => $displayRecords) {

            foreach($displayRecords as $value => $dispRecord){
                if($value === "dateStart" && !isnull($dispRecord)) {
                    print "<li>";
                    print "<span class='start-date-range'>";
                    print date("m/d/Y", $dispRecord) . " - ";
                    print "</span>";
                }
                elseif($value === "dateEnd" && !isnull($dispRecord)) {
                    print "<span class='end-date-range'>";
                    print date("m/d/Y", $dispRecord);
                    print "</span> </li>";
                }
            }
        }

        print " <h3> Display Times: </h3> ";

        foreach($imageRecords["display"] as $index => $displayRecords) {

            foreach($displayRecords as $value => $dispRecord){
               if($value === "timeStart" && !isnull($dispRecord)) {
                    print "<li> <span class='start-time-range'>";
                    print date("h:i a", $dispRecord) .  " - ";
                    print "</span>";
                }
                elseif($value === "timeEnd" && !isnull($dispRecord)) {
                    print "<span class='end-time-range'>";
                    print date("h:i a", $dispRecord);
                    print "</span> </li>";
                }
            }
        }

        print " <h3> Display Weekdays: </h3> ";

        foreach($imageRecords["display"] as $index => $displayRecords) {
            foreach($displayRecords as $value => $dispRecord){
               if($value === "weekdays" && !isnull($dispRecord)) {
                    print "<li><p>" . $dispRecord . "</p></li>";
                }
            }
        }


        $baseDir = $localvars->get('baseDirectory');

        $editDir =  sprintf ("<a href='%s%s' class='%s'> EDIT IMAGE </a>",
                $baseDir,
                "/editImage/?imageID=$imgID",
                "edit button");

        $deleteDir =  sprintf ("<a href='%s%s' class='%s'> DELETE IMAGE </a>",
                $baseDir,
                "/deleteImage/?imageID=$imgID",
                "delete button");

        print "<li>";
            print $editDir . " " . $deleteDir;
        print "</li>";

        print "</ul>";
    }

?>