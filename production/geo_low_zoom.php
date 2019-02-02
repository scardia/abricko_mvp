<?php
include('assets/config.nic.php');

function getGeoJsonLow()
{
    global $con;
    $minx=$_REQUEST['minx'];
    $miny=$_REQUEST['miny'];
    $maxx=$_REQUEST['maxx'];
    $maxy=$_REQUEST['maxy'];
    $qry="SELECT distinct `id`,`title`, `latitude`, `longitude`, `yieldValue`, `imgLink`, `url` 
    FROM `st_listings_sale` 
    WHERE `yieldValue` > 0 and (`latitude` BETWEEN ".$miny." AND ".$maxy.") AND (`longitude` BETWEEN ".$minx." AND ".$maxx.") 
    ORDER by `yieldValue`";
    //return $qry;
    $result=mysqli_query($con, $qry);
    $data=array();
    if (mysqli_num_rows($result) >0) {
        while ($row = $result->fetch_array()) {
            $y=doubleval(number_format(($row['yieldValue']), 2, '.', ''));
            $langLat='['.$row['latitude'].','.$row['longitude'].']';
            $data[] = array("type"=>"Feature","geometry"=>array("type"=>"Point","coordinates"=> array((float) $row['longitude'],(float) $row['latitude'] )),
                "properties"=>array("name"=>$row['title'],"id"=>$row['id'],"yield"=>$y,"image"=>$row['imgLink'], "url"=>$row['url'],));
        }
    }
    $data1=array("type"=> "FeatureCollection","features"=>$data);//'{"type": "FeatureCollection","features": '.$data1.'}';
    $data1= json_encode($data1);
    return $data1;
}

$data= getGeoJsonLow();
echo ($data);