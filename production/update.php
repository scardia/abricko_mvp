<?php
include('assets/config.nic.php');

$data= getGeoJson();


$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL,"https://geoprocessor.geocontext.info/buffer/?params={\"radius\":20}");
curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch2, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

$switched_data = curl_exec($ch2);

$jj = json_decode($switched_data);

$feats = $jj->features;

$arrs = array_chunk($feats, 1000);

foreach ($arrs as $chunk) {
    $data = array(
        "type" => "FeatureCollection",
        "features" => $chunk
    );
    $data = json_encode($data);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"https://api.mapbox.com/datasets/v1/abricko/yields?access_token=sk.eyJ1IjoiYWJyaWNrbyIsImEiOiJjanJmeGozaXkxbjIxNDNwZmdjY2Y0cGYxIn0.AbVPFxvc3e3yWigedoZK5Q");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 


    // In real life you should use something like:
    // curl_setopt($ch, CURLOPT_POSTFIELDS, 
    //          http_build_query(array('postvar1' => 'value1')));

    // Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

}
curl_close ($ch);
die($server_output);