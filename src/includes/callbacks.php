<? php
function insertImage($processor,$data) {
    $message = "No conditions passed"; 
    // Testing the Files Logic 
    if($_FILES['imageAd']['name']) { 
        print "FILES FOUND";
        // noerrors detected 
        if(!$_FILES['imageAd']['error']) {
            $message = "Great Job"; 
        } else { 
            $message = "Something went Wrong Upload Error: " . $_FILES['imageAd']['error'];   
        }  
    }
    echo $message; 
    return $data; 
    print $data; 
}

?> 