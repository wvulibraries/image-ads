<?php

	function getAllAds() {
        //DB Connection and SQL Statements    
        $localvars = localvars::getInstance();
        $db        = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO
		$sql       = sprintf("SELECT * FROM imageAds");
		$sqlResult = $db->query($sql);
        $data      = NULL;  
        
		if ($sqlResult->error()) {
			print "ERROR GETTING ADS"; 
			return(FALSE);
		}
        
		if ($sqlResult->rowCount() < 1) {
			print "NO ADS FOUND"; 
			return FALSE;
		}
        
        while($row = $sqlResult->fetch()) {
            print "Ads Found!";
            print $row;
            
            foreach($row as $r) { 
                $i = 0; 
                print "Hello World" . $i; 
                $i++; 
            }
        }  
        
        //$numOfRows = $sqlResult->rowCount(); 
        //print $numOfRows; 
     
        // Working on displaying our current Ads and also setting up the information to render out on a website 
        // $localvars->set("displayAllAds", getAllAds());
        
       
    }

    getAllAds(); 
?> 