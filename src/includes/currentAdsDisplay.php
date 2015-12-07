<?php

    $localvars = localvars::getInstance();
    recurseInsert("includes/formatImgRecords.php","php");

    try {
            $displayAdRecords =  getImagesFromDB();

            if (!$displayAdRecords) {
                throw new Exception("no images");
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
                print "<img src='$imgProperties' alt='test image display for admins'/>";
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
                if($imgProperties <= 5 ){
                   print  "<div class='image-priority low'> <span class='display'> <i class='fa fa-circle-thin'></i> Low Priority - $imgProperties </span></div>";
                }
                else {
                    print "<div class='image-priority high'><span class='display'> <i class='fa fa-exclamation-circle'></i> High Priority - $imgProperties </span></div>";
                }
                print "</li>";
            }
            else if ($recordsIndex == "altText" ) {
                print " <h3> Image Description: </h3> <li>";
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
               $tempWeekdayValuesArray = array();
               if($value === "weekdays" && !isnull($dispRecord)) {
                    foreach($dispRecord as $weekday => $weekbool) {
                        if($weekday == "monday" && $weekbool == 1) {
                            array_push($tempWeekdayValuesArray, "Monday");
                        }
                        if($weekday == "tuesday" && $weekbool == 1) {
                             array_push($tempWeekdayValuesArray, "Tuesday");
                        }
                        if($weekday == "wednesday" && $weekbool == 1) {
                             array_push($tempWeekdayValuesArray, "Wednesday");
                        }
                        if($weekday == "thursday" && $weekbool == 1) {
                             array_push($tempWeekdayValuesArray, "Thursday");
                        }
                        if($weekday == "friday" && $weekbool == 1) {
                             array_push($tempWeekdayValuesArray, "Friday");
                        }
                        if($weekday == "saturday" && $weekbool == 1) {
                             array_push($tempWeekdayValuesArray,"Saturday");
                        }
                        if($weekday == "sunday" && $weekbool == 1) {
                             array_push($tempWeekdayValuesArray,"Sunday");
                        }
                    }

                    if(!is_empty($tempWeekdayValuesArray)){
                        print implode(", ", $tempWeekdayValuesArray);
                    }
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
    } catch (Exception $e) {
    }
?>