
<?php 
	require_once "includes/engineHeader.php";
    templates::display('header'); 
    // Insert Form Definintions 
    recurseInsert("includes/dispOptionsForm.php","php"); 
?>

<header> 
    <h1 class="click-test"> Display Options Page </h1>
</header> 

<section>
    <div id="UploadImageForm">
	   {form name="displayOptions" display="form" addGet="true"}
    </div>
    
    <div>
        
	</div>

</section>


<?php
    // Add JS in the Lower Part of the Body Before the footer to keep from blocking render 
    include 'includes/jsIncludes.php'; 
    templates::display('footer'); 
?>

<!-- SCRAPPED --> 

<!--  <script charset="utf-8">

//     $('.date-range').on('click', function () {
//      //console.log('click is working');
//      // Create a Box to for each start and end date in the case of multiple ranges
//         $('.displayOptionInputs').append('<div class="dateRanges"> </div>');

//             var inputClass = $('.dateRanges');
//             inputClass.append('<div class="startRange"> <span> Start Date Range: </span> {local var="monthSelect"} {local var="daySelect"} {local var="yearSelect"} </div>');
//             inputClass.append('<div class="endRange"> <span> End Date Range: </span> {local var="monthSelectEnd"} {local var="daySelectEnd"} {local var="yearSelectEnd"}</div>');
//             inputClass.append('<a href="javascript:void(0)" class="removeButton"> Remove Weekday </a>');
         
//     });

//  $('.time-range').on('click', function () {
//      console.log('time range click');

//      $('.displayOptionInputs').append('<div class="timeRanges"></div>');
//      var inputClass = $('.timeRanges');
//      inputClass.append('<div class="startRange_time"> <span> Start Time: </span> {local var="startTime"}');
//      inputClass.append('<div class="endRange_time"> <span> End Time: </span> {local var="endTime"}');


//      $('.time-range').off(); // Turn off event Listener 
//  });


//  $('.weekday').on('click', function () {
//      console.log('days in week opened.');

//      if($('.displayDays').length === 0) { 
//          $('.displayOptionInputs').append('<div class="displayDays"></div>');
//          var myDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
//          console.log(myDays.length);

//          for (var i = 0; i < myDays.length; i++) {
//              console.log("working");
//              $('.displayDays').append('<label>' + myDays[i] + '</label> <input name="weekday" value"' + myDays[i] + '" type="radio" /> ');
//          }
//      }

//  });

 </script> --> 


