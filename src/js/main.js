
// Add Image to the server and parse the data. 
(function addImagePreview(){  
    // Gloabal Variable for image Input 
    // =====================================================
    var imageInput = elmId('imgFile');  
    var imgName = elmId('imgName'); 
    var previewID = "imgPreview"; 
    var addElm = document.createElement("div");
    var makeElm = imageInput.parentNode.appendChild(addElm); 
    
    
    // Event Listener and change function for detecting input
    //======================================================
    imageInput.addEventListener("change", function(e) {
        // is there a file that we can loop through 
        var fileLength = this.files.length; 
        // Make sure this file is an image 
        for(var i = 0; i < fileLength; i++) {
            file = this.files[i]; 
            //----------------------------------------------
            // Regular Exp for looking at all the image types 
            if(!file.type.match(/image.*/)) {
                console.log("not an image"); 
            } else { 
               // console.log("is an image");    
               // console.log("Filename:" + file.name);
               // Ajax File Reader for setting up Data URI Image Preview
                var renderPreview = new FileReader(); 
                renderPreview.onload = function(e) { 
                 // Create the Preview 
                  renderHTML(
                        "<p><strong>Image Preview:</strong><br />" +
                        '<img src="' + e.target.result + '" /></p>'
                    ); 
                  // Add Image Details to Parts of the Application Form 
                 imgName.value = file.name; 
			     }
                renderPreview.readAsDataURL(file);
                
                
            } //----------------------------------------------  
          }
        }, false); 
    
    // Event Listener and change function for detecting input
    //======================================================
    
    // Creating the privew function to show the inner HTML of the image and the rest of the stuff 
    // @TODO ==>  Try to figure out a dryer method here 
    function renderHTML(theContent) {
        
         /*   var elmInDom = elmId(previewID); 
            if(elmInDom) {
                console.log("pass");    
                // Remove all of them 
                
                
                // Add the new one 
               // var addElm = document.createElement("div"); 
                //addElm.id = previewID;  
                //imageInput.parentNode.appendChild(addElm).innerHTML = theContent; 
            } else { 
                console.log("fail");    
                // Create div to put the the content or rendered HTML Objects into
                // Then append that div to show the content 
                var addElm = document.createElement("div"); 
                addElm.id = previewID;  
                imageInput.parentNode.appendChild(addElm).innerHTML = theContent; 
            }    */
        
        // Removed the other stuff because it was doing it again and again adding more than one preview.  
        // I didn't want more than one preview or more than one appearance of images.   
        
        makeElm.innerHTML = theContent; 
     }
 
    // Making it easier to traverse the dom similar to how JQuery does it 
    function elmId(targetID) {
        return document.getElementById(targetID);
    }

    function elmClass(targetClass) { 
        return document.querySelector(targetClass); 
    }

    
    
})();


