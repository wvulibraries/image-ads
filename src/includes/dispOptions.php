<?php
	$currentDate = date("Y-m-d"); // year month day for best comparisons
	$currentTime = NULL; 

	// Setup Years  
	$lowestYear = date('Y'); 
	$highestYear = date('Y', strtotime("+5 year")); 

	// Test case shows that the year is adding 5 years to the lowest year, which is the current year. 
	print "<p style='margin-top:50px;'>";
	print $lowestYear . "<br><br>";
	print $highestYear; 
	print "</p>";  


	// Date Ranges 
	



	// // Test Data  
	// $dataBegin = "01/01/2011"; 
	// $dataEnd = "10/20/2020"; 
	// $beginRange = date('Y-m-d', strtotime($dataBegin)); 
	// $endRange = date('Y-m-d', strtotime($dataEnd)); 
	// $dateRangeToday = date('Y-m-d', strtotime($today));

	// // Test case to test if the dates can compare to each other 
	// if (($dateRangeToday > $beginRange) && ($dateRangeToday < $endRange)) {
	// 	print "Condition Has Passed"; 
	// } else { 
	// 	print "Condition Has Failed"; 
	// }


?>



<h1> Test Cases </h1>



<?php
$lowestYear = 1980;
$highestYear = 2010;
?>
 
Month:
<select name="month">
<?php foreach(range(1,12) as $month): ?> 
<option value="<?php echo $month;?>"><?php echo date("F",strtotime("0000-$month"));?></option>
<?php endforeach ?>
</select>
 
 
Day:
<select name="day">
<?php foreach(range(1,31)as $day): ?>
<option value="<?php echo $day;?>"><?php echo $day;?></option> 
<?php endforeach ?>
</select>
 
 
Year:
<select name="year">
<?php foreach (range($lowestYear,$highestYear) as $year):?>
<option value="<?php echo $year;?>"><?php echo $year;?></option>
<?php endforeach?>
</select>
 
Hour:
<select name="hour">
<?php foreach (range(0,23) as $hour):?>
<option value="<?php echo $hour;?>"><?php echo $hour;?></option>
<?php endforeach?>
</select>
 
Minute:
<select name="minute">
<?php foreach (range(0,50) as $minute):?>
<option value="<?php echo $minute;?>"><?php echo $minute;?></option>
<?php endforeach?>
</select>