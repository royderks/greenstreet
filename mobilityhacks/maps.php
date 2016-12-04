<?php

include_once('functions.php');

?>


<!DOCTYPE html>
<html>
<head>
	<title>GreenStreet</title>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<meta charset="utf-8">

        <link rel="stylesheet" type="text/css" href="style.css">
       
</script>

<?php

$rewards = 'http://37.139.5.194/api/api.php/route-test?columns=rpm_score&transform=1';
$rewards = file_get_contents($rewards);
$rewards = json_decode($rewards, true);

$total_reward = 0;

$rewards_string = '[';
    foreach ($rewards['route-test'] as $reward) {

        $total_reward = ($total_reward + ($reward['rpm_score']));
        
        if ($total_reward <= '0') { $total_reward = 0; }
        
        $rewards_string .= $total_reward . ',';

    }
$rewards_string .= ']';

$coordinates = 'data/coordinates.json';
$coordinates = file_get_contents($coordinates);

?>

</head>
<body>
    
    
    <section class='header'>
        
        <div class='center'>
                <h1>GreenStreet</h1>
        </div>
        
    </section>
    
    <div class="topcorner">
        <div class="box">
            <span id="text"></span>
        </div>
    </div>
    
    <section class='map' id='map'></section>
           
    <section class='footer'>
        <img alt="Europcar logo" src="https://www.europcar.de/files/live/sites/Europcar/files/Meganav/NEW-Logo-size%20fit%20for%20header.png">
    </section> 

    <!-- The Modal -->
    <div id="myModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
        <!--<span class="close">x</span>-->
          <p><h2>You collected <?php $total_reward; ?></h2></p>
          <p>Hereby you're not only saving the climate</p> 
      </div>

    </div>

</body>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxE-eJjn5ymVd6yaiUqSp8_KWwCGdPHSE&v=3.exp&amp;libraries=geometry"></script>
<script>
    // Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
    
    // User Rewards  
    var splittedText = <?php echo $rewards_string; ?>;

    function loopThroughSplittedText(splittedText) {
        displayValue(splittedText,0);
    }

    function displayValue(arr, i){
        if(i<arr.length){
            setTimeout(function(){
                document.getElementById('text').innerHTML = arr[i];
                console.log(arr[i])
                displayValue(arr,i+1);
            },2200)
        } else { 
            
            //modal.style.display = "block"; 
            
        }
    }
    
    loopThroughSplittedText(splittedText)
    
    // Google Maps scripts
    function initialize() {

        var map = new google.maps.Map(document.getElementById("map"), {
	
            zoom: 13,
            
            center: {lat: pathCoords[0].lat, lng: pathCoords[0].lng},
	
            mapTypeId: google.maps.MapTypeId.ROADMAP
			
        });

        autoRefresh(map);

    }

    function moveMarker(map, marker, latlng) {

        marker.setPosition(latlng);
	
        map.panTo(latlng);
	
    }

    function autoRefresh(map) {

        var i, route, marker;
			
        route = new google.maps.Polyline({
            path: [],
            geodesic : true,
            strokeColor: '#090',
            strokeOpacity: 1.0,
            strokeWeight: 4,
            editable: false,
            map:map
        });
		
        marker=new google.maps.Marker({map:map,icon:"images/marker.png"});
	
        for (i = 0; i < pathCoords.length; i++) {
				
            setTimeout(function (coords)
	
            {
	
                var latlng = new google.maps.LatLng(coords.lat, coords.lng);
		
                route.getPath().push(latlng);
		
                moveMarker(map, marker, latlng);
		
            }, 2000 * i, pathCoords[i]);
	
        }
	
    }

    google.maps.event.addDomListener(window, 'load', initialize);
                

    var pathCoords = <?php echo $coordinates; ?>;
	</script>
</html> 