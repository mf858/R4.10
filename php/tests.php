<?php
$mots_json = file_get_contents("../json/mots.json");
$mots = json_decode($mots_json,true);
 
foreach($mots as $domain){
    $liste_mots = $domain["keywords"];
    print_r($liste_mots);
} 
?>