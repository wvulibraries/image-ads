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
            //print "Ads Found!";
            //print $row . "<br>";
            //print $sqlResult; 
            //print $row['nameOfImage'] . "<br>" .
            //      $row['enable'] . "<br>" ;
            
            print "<p class='something'>";
                // This is decontructing the array in the while loop.  
                // using $i is the index of the associative array $r is the value.  
                foreach($row as $i=>$r) { 
                    print "<strong>" . $i . "</strong>  " . $r . "<br>";      
                }
            print "</p>"; 
            
        }  
        
        //$numOfRows = $sqlResult->rowCount(); 
        //print $numOfRows; 
     
        // Working on displaying our current Ads and also setting up the information to render out on a website 
        // $localvars->set("displayAllAds", getAllAds());
        
       
    }

    getAllAds(); 
?> 