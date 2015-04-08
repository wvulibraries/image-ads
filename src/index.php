
<?php 
	require_once "includes/engineHeader.php";
    templates::display('header'); 
    // Insert Form Definintions 
    recurseInsert("includes/imageForm.php","php"); 
    // Insert DipslayCurrentAds Logic 
    recurseInsert("includes/currentAdsDisplay.php", "php"); 
?>

<header> 
    <h1 class="click-test"> Advertising Manager Home </h1>
</header> 

<section>
    <div id="UploadImageForm">
	   {form name="imageAdForm" display="form" addGet="true"}
       {form name="imageAdForm" display="edit" expandable="true" addGet="true"}
    </div>
    
    <div>
        
	</div>

</section>


<?php
    // Add JS in the Lower Part of the Body Before the footer to keep from blocking render 
    include 'includes/jsIncludes.php'; 
    templates::display('footer'); 
?>

<script charset="utf-8">
	// HOLY COW YOU CAN ADD PHP THROUGH JS 
    /*
	$(".click-test").on('click', function(e) {
		e.preventDefault();
		$(this).before('<?php echo "Something Stupid" ?>');
	});
    */ 
</script>


