<?php
include('assets/config.nic.php');
include_once('geoPHP.inc');

$data= getGeoJson();

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://api.mapbox.com/datasets/v1/abricko/abricko.bck0ollv?access_token=pk.eyJ1IjoiYWJyaWNrbyIsImEiOiJjanJhaGxlYzcwaG40NDRsaHhocXdocDVhIn0.hVzJBL6S1alSJ_-bbKc9QQ");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 


// In real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS, 
//          http_build_query(array('postvar1' => 'value1')));

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close ($ch);
die($server_output);