<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?> 


<!DOCTYPE html>
<html>
<head>
	<title>GreenStreet</title>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">

</head>

<html>
    <body>
        
        <section class='header'>
        
        <div class='center'>
                <h1>GreenStreet</h1>  
        </div>
        
    </section>
        
        <section class="content">

<form class="form">
	
	<ul id="progressbar">
		<li class="active">Select Car</li>
		<li>Choose Driving Style</li>
	</ul>
	
	<fieldset>
		<h2>Select Car</h2>
		<h3></h3>
		
                <?php
                
                    $vehicle = 'http://37.139.5.194/api/api.php/vehicle?transform=1';
                    $vehicle = file_get_contents($vehicle);                    
                    $vehicle = json_decode($vehicle, true);
                    
                    foreach($vehicle['vehicle'] as $vehicle){

                ?>
                
                <div class="next select-car">
                
                    <p><img src="<?php echo $vehicle['image']; ?>" alt="<?php echo $vehicle['model']; ?>" /></p>
                
                    <p><strong><?php echo $vehicle['brand']; ?></strong></p>                   
                
                    <p><?php echo $vehicle['model']; ?></p>
                
                </div> 
                
                 
                <?php
                
                }
                          
                ?>      
                
	</fieldset>
	<fieldset>
		<h2>Driving Style</h2>
		
                <div class="button-box">
                
                    <a href="#" class="next grey action-button">Regular<a>

                </div>
                
                <div class="button-box">
                
                    <a href="maps.php" class="next action-button">Eco+<a>
                    
                    <p>Save up to €30!</p>
                    
                </div>
                
		
	</fieldset>

</form>

        </section>
        
        <section class='footer'>
        <img alt="Europcar logo" src="https://www.europcar.de/files/live/sites/Europcar/files/Meganav/NEW-Logo-size%20fit%20for%20header.png">
    </section>

<!-- jQuery -->
<script src="http://thecodeplayer.com/uploads/js/jquery-1.9.1.min.js" type="text/javascript"></script>
<!-- jQuery easing plugin -->
<script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>

<script type="text/javascript">
    //jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

$(".next").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	next_fs = $(this).parent().next();
	
	//activate next step on progressbar using the index of next_fs
	$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
	
	//show the next fieldset 
	next_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'transform': 'scale('+scale+')'});
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});

$(".previous").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	previous_fs = $(this).parent().prev();
	
	//de-activate current step on progressbar
	$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
	
	//show the previous fieldset
	previous_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left});
			previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});

$(".submit").click(function(){
	return false;
})
</script>