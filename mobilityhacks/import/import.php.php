<?php

/* 
 * Roy Derks - hello@hackteam.io
 */

// Load OTP data
$file_otp = '../data/history_Sun04Dec2016013129.json';
$json_otp = json_decode(file_get_contents($file_otp), true);

// Load GPX date
// Credit: https://mapstogpx.com
// Based on: https://goo.gl/maps/K8KAcvK7nHE2
$file_gpx = '../data/gpxroute_berlin.json';
$json_gpx = json_decode(file_get_contents($file_gpx), true);

//API Url
$api_url = 'http://37.139.5.194/api/api.php';
$api_url = $api_url . '/route-test';

//Initiate cURL.
$ch = curl_init($api_url);


// Set mock vehicle and route ids
$vehicle_id = 1;
$route_id = 2;

// Sort OTP data ASC
sort($json_otp['AppEuropcar']);

// Set first record datetime
$timestamp = $json_otp['AppEuropcar'][0]['timestamp'];
$timestamp = strtotime($timestamp);

foreach ($json_gpx['points'] as $points) { 

    $route_id = '2';  
    
    if(isset($points['type'])) {
        
        $lng = $points['lng'];
        $lat = $points['lat'];
        
        $distanceblock = $points['dist']['val'];
        
        // create timestamp
        foreach ($points['timeto'] as &$timeto) {
            $timestamp = $timestamp + ($timeto['val']*60);
            $timeblock = ($timeto['val']*60);
        }  
        
        // Loop for corresponding OTP data
        foreach ($json_otp['AppEuropcar'] as $AppEuropcar) {
            
            $route_timestamp = strtotime($AppEuropcar['timestamp']);
            
            if ($timestamp == $route_timestamp) {

                if(isset($AppEuropcar['gps'])) {
                    $gps = $AppEuropcar['gps'];
                } else $gps = false;

                if(isset($AppEuropcar['speed'])) {
                    $speed = $AppEuropcar['speed'];
                } else $speed = false;

                if(isset($AppEuropcar['rpm'])) {
                    $rpm = $AppEuropcar['rpm'];
                } else $rpm = false;
                
                if ($speed > '120') {
                    $vel_score = '-1';
                } else $vel_score = '1';
                
                if (($rpm < '2000') || ($rpm > '4200')) {
                    $rpm_score = '-1';
                } else $rpm_score = '1';

                //The JSON data.
                $jsonData = array(
                    'timestamp' => $timestamp,
                    'lng' => $lng,
                    'lat' => $lat,
                    'speed' => $speed,
                    'rpm' => $rpm,
                    'vel_score' => $vel_score,
                    'rpm_score' => $rpm_score,
                    'distanceblock' => $distanceblock,
                    'timeblock' => $timeblock,
                    'vehicle_id' => $vehicle_id,
                    'route_id' => $route_id
                );

                //Encode the array into JSON.
                $jsonDataEncoded = json_encode($jsonData);

                //Tell cURL that we want to send a POST request.
                curl_setopt($ch, CURLOPT_POST, 1);

                //Attach our encoded JSON string to the POST fields.
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

                //Set the content type to application/json
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

                //Execute the request
                $result = curl_exec($ch);
                
            }
    
        }
        
        $type = $points['type'];
        $dir = htmlspecialchars($points['dir']);
    
    }
} 

    
