<?php
include('config.nic.php');
include('library/Requests.php');
function doFlush()
{
    echo str_repeat(' ', 1024*64);
    flush();
}
/*
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
$con=mysqli_connect('localhost', 'root', '', 'properties');*/
//$con=mysqli_connect(db_host, db_user, db_password, db_database);
// Check connection

if (!isset($_REQUEST['city']) || $_REQUEST['city']=='') {
    $city=$_REQUEST['city'];
}

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
Requests::register_autoloader();
$session = new Requests_Session(myWebsite);
$session->headers['X-ContactAuthor'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
$session->useragent = myAgent;
getListingUrls();
/*
$mydata=getHTMLusingCURL('https://www.rightmove.co.uk/property-for-sale/search.html');
$doc=new DOMDocument();
$load = @$doc->loadHTML($mydata);
if ($load) {
    foreach ($doc->getElementsByTagName('ul') as $Node1) {
        if ($Node1->getAttribute('class')== 'footerlinks') {
            foreach ($Node1->getElementsByTagName('li') as $Node2) {
                $aNode=$Node2->getElementsByTagName('a');
                if (!is_null($aNode->item(0))) {
                    $Link=$aNode->item(0)->getAttribute('href');
                    if(strpos('me'.$Link, 'https://www.rightmove.co.uk/uk-property-search')>0){
                        echo "\n".$Link;
                        //myRegions($Link);
                        getListingData();
                    }
                    //myRegions($Link);
                    //file_put_contents('myUrl.txt', $Link, FILE_APPEND | LOCK_EX);
                }
            }
        }
    }
}
function myRegions($myUrl)
{
    $mydata1=getHTMLusingCURL($myUrl);//regionindex
    $doc1=new DOMDocument();
    $load1 = @$doc1->loadHTML($mydata1);
    if ($load1) {
        foreach ($doc1->getElementsByTagName('div') as $mNode1) {
            if ((strpos('me' .$mNode1->getAttribute('class'), 'regionindex') > 0)) {
                foreach ($mNode1->getElementsByTagName('ul') as $mNode2) {
                    foreach ($mNode1->getElementsByTagName('a') as $mNode2) {
                        $mLink=$mNode2->getAttribute('href');
                        $mLink=$mLink."\r\n";
                        file_put_contents('myRegionUrl.txt', $mLink, FILE_APPEND | LOCK_EX);
                    }
                }
            }
        }
    }
}
*/
//----------------------------------------------------------------------------------------------------------------
function getListingUrls()
{
    $type=0;
    $rootDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
    $myUrls=readMyFile($rootDir."myRegionUrl.txt");
    $myUrl = explode("\n", $myUrls);
    $total= readMyFile($rootDir."startUrl.txt");
    for ($i=$total;$i>=0;$i--) {
        file_put_contents('startUrl.txt', $i);
        $pUrl= str_ireplace(myWebsite, '', $myUrl[$i]);
        echo "\nFeching records from the url No.---> ".$i;
        echo "\nFeching records from the url -----> ".$pUrl;
        $mydata2=chkError($pUrl);
        $doc2=new DOMDocument();
        $load2 = @$doc2->loadHTML($mydata2);
        if ($load2) {
            $pNode=$doc2->getElementbyId('searchHeader');
            if (!is_null($pNode)) {
                $pNode1=$pNode->getElementsByTagName('span');
                if (!is_null($pNode1)) {
                    $totRecords=$pNode1->item(0)->nodeValue;
                }
                foreach ($doc2->getElementsByTagName('input') as $pNode2) {
                    if ((strpos('me' .$pNode2->getAttribute('name'), 'locationIdentifier') > 0)) {
                        $locationId=$pNode2->getAttribute('value');
                    }
                }
                if (stripos('me'.$pUrl, 'sale')) {
                    $type=1;
                } else {
                    $type=0;
                }
            }
            getListingData($locationId, $totRecords, $type);
        }
    }
}
//----------------------------------------------------------------------------------------------------------------
function getListingData($locationId, $totRecords, $type)
{
    //echo "\n tot records are 12   --->".$totRecords;
    $totRecords=str_replace(",", "", $totRecords);
    //echo "\n totRecords divided by 24  ---->".$totRecords/24;
    //echo ceil($totRecords/24);
    $totPages=ceil($totRecords/24);
    echo "\n tot pages are --  ".$totPages;
    $y=0;
    if ($totPages>50) {
        $totPages=50;
    }
    for ($i=0;$i<=$totPages;$i++) {
        if ($type==0) {
            echo "\n Getting Record from Rent listings ---------->";
            //doFlush();
            echo $myUrl='https://www.rightmove.co.uk/api/_search?locationIdentifier='.$locationId.'&numberOfPropertiesPerPage=24&radius=0.0&sortType=6&index='.$y.'&viewType=LIST&channel=RENT&areaSizeUnit=sqft&isFetching=false';
        //doFlush();
        } else {
            echo "\n Getting Record from sale listings ---------->";
            //doFlush();
            echo $myUrl='https://www.rightmove.co.uk/api/_search?locationIdentifier='.$locationId.'&numberOfPropertiesPerPage=24&radius=0.0&sortType=2&index='.$y.'&viewType=LIST&channel=BUY&areaSizeUnit=sqft&isFetching=false';
            //doFlush();
        }
        $mydata3=getHTMLusingCURL($myUrl);
        echo "\n";
        //file_put_contents($y.'.txt', $mydata3, FILE_APPEND | LOCK_EX);
        if ($mydata3!="") {
            $arr = json_decode($mydata3, true);
            //print_r($arr);
            $arr1=($arr["properties"]);

            while (list($key, $val) = each($arr["properties"])) {
                //echo "Key: ".$key."; Value: ".$val."<br />\n";
                while (list($key1, $val1)=each($val)) {
                    if (trim($key1) == 'bedrooms') {
                        //echo "</br> bedrooms -->".
                        $bedrooms=$val1;
                    }
                    if (trim($key1) == 'displayAddress') {
                        $address=$val1;
                        $str1=explode(",", $address);
                        $sAddress='';
                        $city='';
                        $zipcode='';
                        if (count($str1)>0) {
                            if (count($str1)==1) {
                                //echo "str===1";echo "\n sAddress is".
                                $sAddress=$str1[0];
                            } elseif (count($str1)==2) {
                                //echo "str===2";echo "\n sAddress is".
                                $sAddress=$str1[0];
                                $city=$str1[1];
                            } elseif (count($str1)==3) {
                                //echo "str===3";echo "\n sAddress is".echo "\n City is".echo "\n zipcode is ".
                                $sAddress=$str1[0];
                                $city=$str1[1];
                                $zipcode=$str1[2];
                            } elseif (count($str1)==4) {
                                //echo "str===4";echo "\n sAddress is".echo "\n City is".echo "\n zipcode is ".
                                $sAddress=$str1[0].", ".$str1[1];
                                $city=$str1[2];
                                $zipcode=$str1[3];
                            } elseif (count($str1)==5) {
                                //echo "str===4";echo "\n sAddress is".echo "\n City is".echo "\n zipcode is ".
                                $sAddress=$str1[0].", ".$str1[1].", ".$str1[2];
                                $city=$str1[3];
                                $zipcode=$str1[4];
                            }
                        }
                    }
                    if (trim($key1) == 'propertySubType') {
                        //echo "</br> type---->".
                        $type1=$val1;
                        $title=$bedrooms." bedroom ".$type1." for sale";
                    }
                    if (trim($key1) == 'price') {
                        while (list($key2, $val2)=each($val1)) {
                            if (trim($key2) == 'amount') {
                                //echo "</br> Amount -->".
                                $price=$val2;
                            }
                        }
                    }
                    if (trim($key1) == 'propertyUrl') {
                        //echo "</br> Url---->".
                        $url=myWebsite.$val1;
                    }
                    if (trim($key1) == 'location') {
                        while (list($key3, $val3)=each($val1)) {
                            if (trim($key3) == 'latitude') {
                                //echo "</br> latitude -->".
                                $latitude=$val3;
                            } elseif (trim($key3) == 'longitude') {
                                //echo "</br> longitude -->".
                                $longitude=$val3;
                            }
                        }
                    }
                    if (trim($key1) == 'propertyImages') {
                        while (list($key3, $val3)=each($val1)) {
                            if (trim($key3) == 'mainImageSrc') {
                                //echo "</br> imgurl -->".
                                $imgLink=$val3;
                                insertData($title, $address, $sAddress, $city, $zipcode, $latitude, $longitude, $bedrooms, $price, $imgLink, $url, $type);
                            }
                        }
                    }
                }
            }
        }
        $y=$y+24;
    }
}
//----------------------------------------------------------------------------------------------------------------
function insertData($title, $address, $sAddress, $city, $zipcode, $latitude, $longitude, $beds, $price, $imgLink, $myUrl, $type)
{
    $m2Price=0;
    global $con , $mfrom;
    if ((int)($beds)>0) {
        if ($type==0) {
            $m2Price=((int)($price) *12)/(int)($beds);
        } else {
            $m2Price=((int)($price))/(int)($beds);
        }
    }
    echo "\n-----------property Title  is --->".$title;
    echo "\n-----------property url  is --->".$myUrl;
    //die();
    if ($type==1) {
        $qry1="INSERT ignore INTO `st_listings_sale`(`title`, `address`, `street`, `city`, `zipcode`, `latitude`, `longitude`, `bedRoom`, `price`,`m2Price`, `imgLink`, `url`,`provider`) VALUES ('".trim($title)."','".trim($address)."','".trim($sAddress)."','".trim($city)."','".trim($zipcode)."','".trim($latitude)."','".trim($longitude)."','".trim($beds)."','".trim($price)."','".trim($m2Price)."','".trim($imgLink)."','".trim($myUrl)."','".myWebsite."')";
    } elseif ($type==0) {
        $qry1="INSERT ignore INTO `st_listings_rent`(`title`, `address`, `street`, `city`, `zipcode`, `latitude`, `longitude`, `bedRoom`, `price`,`m2Price`, `imgLink`, `url`,`provider`) VALUES ('".trim($title)."','".trim($address)."','".trim($sAddress)."','".trim($city)."','".trim($zipcode)."','".trim($latitude)."','".trim($longitude)."','".trim($beds)."','".trim($price)."','".trim($m2Price)."','".trim($imgLink)."','".trim($myUrl)."','".myWebsite."')";
    }
    //echo "<hr>";
    mysqli_query($con, $qry1);
}
//----------------------------------------------------------------------------------------------------------------
function readMyFile($myFile)
{
    if (file_exists($myFile)==true) {
        $fh = fopen($myFile, 'r');
        if (filesize($myFile)>0) {
            $theData = fread($fh, filesize($myFile));
        }
        fclose($fh);
        return $theData;
    }
}
//-----------------------------------------------------------------------------------------------------------------
function chkError($myUrl)
{
    global $session;
    $html=1;
    while ($html==1) {
        //$options = getProxy();
        $response = $session->get($myUrl);//, array(), $options);
        if (is_object($response)) {
            $html = $response->body;
            if (stripos($html, 'We have detected unusual traffic activity originating from your IP address.')!==false || $html== "" || stripos($html, 'Missing header/body separator')!==false || stripos($html, 'Failed to connect') !==false) {
                $html = 1;
            }
        }
    }
    return $html;
}
//-----------------------------------------------------------------------------------------------------------------
function getHTMLusingCURL($myUrl)
{
    $timeout = 200;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $myUrl);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
    //curl_setopt($ch, CURLOPT_COOKIEFILE, Cookie_File_Path);
    //curl_setopt($ch, CURLOPT_COOKIEJAR,  Cookie_File_Path);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $page_html = curl_exec($ch);
    curl_close($ch);
    return $page_html;
}
