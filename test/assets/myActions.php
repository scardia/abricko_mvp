<?php
function getTopTen()
{
    global $con;
    $zipcode=$_REQUEST['zipcode'];
    $qry="SELECT distinct `title`, `latitude`, `longitude`, `yieldValue`, `imgLink`, `url` FROM `st_listings_sale` WHERE `zipcode`='".$zipcode."' and `yieldValue`>0 ORDER by `yieldValue` DESC LIMIT 0,10";
    $result=mysqli_query($con, $qry);
    $data=array();
    if (mysqli_num_rows($result) >0) {
        while ($row = $result->fetch_array()) {
            $y=doubleval(number_format(($row['yieldValue']), 2, '.', ''));
            $yield=strval($y);
            $yield=$yield."%";
            $title=$row['title'];//str_replace(" for sale", "", $row['title']);
            $data[] = array("label"=>$title,"y"=>$y,"url"=>$row['imgLink'],"indexLabel"=>$yield,"pUrl"=>$row['url'],"lat"=>$row['latitude'],"lang"=>$row['longitude']);
        }
    }
    return json_encode($data);
}
