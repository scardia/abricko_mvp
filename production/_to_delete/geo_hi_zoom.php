<?php
include_once('assets/config.nic.php');

function getGeoJsonHigh()
{
    global $con;
    $qry="SELECT distinct `id`,`title`, `latitude`, `longitude`, `yieldValue`, `imgLink`, `url` FROM `st_listings_sale` WHERE `yieldValue`>0 ORDER by `yieldValue`";
    //return $qry;
    $result=mysqli_query($con, $qry);
    $data=array();
    if (mysqli_num_rows($result) >0) {
        while ($row = $result->fetch_array()) {
            $y=doubleval(number_format(($row['yieldValue']), 2, '.', ''));
            $langLat='['.$row['latitude'].','.$row['longitude'].']';
            $data[] = array("type"=>"Feature","geometry"=>array("type"=>"Point","coordinates"=> array((float) $row['longitude'],(float) $row['latitude'] )),
                "properties"=>array("yield"=>$y));
        }
    }
    $data1=array("type"=> "FeatureCollection","features"=>$data);//'{"type": "FeatureCollection","features": '.$data1.'}';
    $data1= json_encode($data1);
    return $data1;
}

$fname = "js/yields.high.geojson";
$tt = filemtime($fname);

$dump = 0;

if(strtotime('-1 day', date('Y-m-d')) == date('Y-m-d', tt)) {
    $dump = 1;
}

$data = '';

if($dump){
    $data = getGeoJsonHigh();
    $myfile = fopen($fname, "w") or die("Unable to open file!");
    fwrite($myfile, $data);
    fclose($myfile);
} else {
    $myfile = fopen($fname, "r");
    $data = fread($myfile, filesize($fname));
}

echo $data;

