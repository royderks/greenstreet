<?php

/* 
 * Roy Derks - hello@hackteam.io
 * MobilityHacks 2016
 * 
 */


function getRewards() {

    $rewards = 'http://37.139.5.194/api/api.php/route-test?columns=rpm_score&transform=1';
    $rewards = file_get_contents($rewards);
    $rewards = json_decode($rewards, true);

    $rewards_string = '[';
    foreach ($rewards['route-test'] as $reward) {

        $rewards_string .= $reward['rpm_score'] . ',';

    }
    $rewards_string .= ']';

}

?>