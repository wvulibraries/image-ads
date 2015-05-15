Rotating Images Ads
==============

This application was developed to create a place for users to be able to manage image ads that are typically used in rotating or carousel fashion on the Libraries home page. 

**Manage (View)**

The Manage state is used to see all currently active images and get a general overview of what images are stored and the data that they are going to have with them.  When look at the manage state you will see the following information, 

 - Enabled : is the image going to show
 - Priority : Low or High Priority will be determine order and if limited if it will show.  
 - Link : The link that the image will go to when clicked 
 - Display Dates : The dates that the image will show
 - Display Times : The times that the image will show
 - Display Weekdays : The days in specific that the image will show 
 
 **Input Areas (Create Update Delete)**
 
The areas will allow you to use forms to create update or delete images you have already stored.  The bulk of the users time will be spent in these sections.  

**Front End Use** 

The application is setup to send a JSON file to the html browser by using an AJAX Call. The following JQuery will display the images in a list file.   

```	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <script type='text/javascript'>
        $(document).ready(function() {

            $.ajax({
                dataType:'json',
                url:'http://localhost:8090/admin/image_manager/includes/ajax/getImages.php',
                data: '',
                success:function() {
                    console.log('success');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                },
                complete: function(data){
                    var jsonData = data.responseJSON;

                    for (var i = 0; i < jsonData.length; i++) {
                        var buildList = '<li class="slider-object"><a href="' + jsonData[i].actionURL +'">';
                        buildList += '<img src="'+ jsonData[i].imageAd +'" alt="' + jsonData[i].altText + '" title="' + jsonData[i].name + '" />';
                        buildList += '</a></li>';

                        $('.slider').append(buildList);
                    }
                },
            });

        });
    </script>
 ```
    
    
If the number of images recieved needs to be limited this can be done by adding a querystring to the end of the url.  An example of this would be if the images need to be limited to 7.  

	http://localhost:8090/admin/image_manager/includes/ajax/getImages.php?limit=7


   