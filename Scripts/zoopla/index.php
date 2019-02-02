<?php
include('config.nic.php');
include('library/Requests.php');
error_reporting(E_ALL);
function doFlush()
{
    echo str_repeat(' ', 1024*64);
    flush();
}
$con=mysqli_connect(db_host, db_user, db_password, db_database);
// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
Requests::register_autoloader();
$session = new Requests_Session(myWebsite);
$session->headers['X-ContactAuthor'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
$session->useragent = myAgent;
$startTime = date("d/m/Y h:i:sa", time());

getPagenation();
die();

function getUrls()
{
    $mydata=getHTMLusingCURL('https://www.zoopla.co.uk/sitemap/');
    $doc=new DOMDocument();
    $load = @$doc->loadHTML($mydata);
    if ($load) {
        $pNode=$doc->getElementbyId('mbody');
        foreach ($pNode->getElementsByTagName('ul') as $Node1) {
            if ($Node1->getAttribute('class')!= 'bottom') {
                foreach ($Node1->getElementsByTagName('li') as $Node2) {
                    $aNode=$Node2->getElementsByTagName('a');
                    if (!is_null($aNode->item(0))) {
                        $Link=$aNode->item(0)->getAttribute('href');
                        echo "\n".$Link;
                        $Link=$Link."\r\n";
                        //myRegions($Link);
                        //getListingData();
                        //myRegions($Link);
                        file_put_contents($rootDir.'myUrl.txt', $Link, FILE_APPEND | LOCK_EX);
                    }
                }
            }
        }
    }
}

function zipUrls()
{
    $rootDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
    $myUrls=readMyFile($rootDir."myUrl.txt");
    $myUrl = explode("\n", $myUrls);
    $total= count($myUrl)-1;
    for ($i=0;$i<=$total;$i++) {
        $purl=$myUrl[$i];
        $mydata=chkError($purl);
        $doc=new DOMDocument();
        $load = @$doc->loadHTML($mydata);

        if ($load) {
            $pNode=$doc->getElementbyId('mbody');
            foreach ($pNode->getElementsByTagName('div') as $pNode1) {
                if ((strpos('me' .$pNode1->getAttribute('class'), 'split2r') > 0)) {
                    foreach ($pNode1->getElementsByTagName('ul') as $Node1) {
                        foreach ($Node1->getElementsByTagName('li') as $Node2) {
                            $aNode=$Node2->getElementsByTagName('a');
                            if (!is_null($aNode->item(0))) {
                                $Link=$aNode->item(0)->getAttribute('href');
                                echo "\n".$Link;
                                $Link=$Link."\r\n";
                                //myRegions($Link);
                                //getListingData();
                                //myRegions($Link);
                                file_put_contents($rootDir.'zipUrls.txt', $Link, FILE_APPEND | LOCK_EX);
                            }
                        }
                    }
                }
            }
        }
    }
}

function listingUrls()
{
    $rootDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
    $myUrls=readMyFile($rootDir."zipUrls.txt");
    $myUrl = explode("\n", $myUrls);
    $total= count($myUrl)-1;
    for ($i=0;$i<=$total;$i++) {
        $purl=$myUrl[$i];
        $mydata=chkError($purl);
        $doc=new DOMDocument();
        $load = @$doc->loadHTML($mydata);

        if ($load) {
            $pNode=$doc->getElementbyId('mbody');
            foreach ($pNode->getElementsByTagName('div') as $pNode1) {
                if ((strpos('me' .$pNode1->getAttribute('class'), 'split2r') > 0)) {
                    foreach ($pNode1->getElementsByTagName('ul') as $Node1) {
                        foreach ($Node1->getElementsByTagName('li') as $Node2) {
                            $aNode=$Node2->getElementsByTagName('a');
                            if (!is_null($aNode->item(0))) {
                                $Link=$aNode->item(0)->getAttribute('href');
                                echo "\n".$Link;
                                $Link=$Link."\r\n";
                                //myRegions($Link);
                                //getListingData();
                                //myRegions($Link);
                                file_put_contents($rootDir.'listingsUrls.txt', $Link, FILE_APPEND | LOCK_EX);
                            }
                        }
                    }
                }
            }
        }
    }
}
function getPagenation()
{
    $rootDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
    $myUrls=readMyFile($rootDir."listingsUrls.txt");
    $myUrl = explode("\n", $myUrls);
    $startUrl=readMyFile($rootDir."startUrl.txt");
    $total= count($myUrl)-1;
    echo "total urls are--> ".$total;
    //doFlush();
    for ($i=$startUrl;$i<=$total;$i++) {
        file_put_contents($rootDir.'startUrl.txt', $i);
        $purl=$myUrl[$i];
        if (stripos('me'.$purl, 'studio')<1) {
            $purl=trim($purl)."?page_size=100";
            echo "\n---url is-->".$purl;
            //doFlush();
            $mydata=chkError($purl);
            $doc=new DOMDocument();
            $load = @$doc->loadHTML($mydata);
            if (stripos('me'.$purl, 'sale')) {
                $type=1;
            } else {
                $type=0;
            }
            //echo $mydata;
            //saveMyFile('test.html', $mydata, 1);
            if ($load) {
                foreach ($doc->getElementsByTagName('div') as $Node2) {
                    if ((strpos('me'.$Node2->getAttribute('class'), 'listing-results-utils-view clearfix bg-muted') > 0)) {
                        foreach ($Node2->getElementsByTagName('span') as $Node3) {
                            if ((strpos('me'.$Node3->getAttribute('class'), 'listing-results-utils-count') > 0)) {
                                $totRecords=$Node3->nodeValue;
                                $totRecords=explode("of", $totRecords);
                                $totalRecords=$totRecords[1];
                                $totalRecords=str_replace(",", "", $totalRecords);
                                echo "\n------Total pages are-->".$totPages=ceil($totalRecords/100);
                                //doFlush();
                                for ($z=1;$z<=$totPages;$z++) {
                                    $purl=trim($purl)."&pn=".$z;
                                    echo "\n---------listing url is-->  ".$purl;
                                    //doFlush();
                                    $str=explode("/", $purl);
                                    $zipCode=strtoupper(($str[4]));
                                    getData($purl, $type, $zipCode);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
function getData($purl, $type, $zipCode)
{
    $mydata=chkError($purl);
    $doc=new DOMDocument();
    $load = @$doc->loadHTML($mydata);
    //echo $mydata;
    //saveMyFile('test.html', $mydata, 1);
    if ($load) {
        $Node=$doc->getElementbyId('content');
        foreach ($Node->getElementsByTagName('li') as $Node2) {
            if ((strpos('me'.$Node2->getAttribute('class'), 'srp clearfix') > 0)) {
                $title='';$address='';$city='';$sAddress='';$beds='';$baths='';
                $rRoom='';$price='';$imgLink='';$myUrl='';

                foreach ($Node2->getElementsByTagName('div') as $Node3) {
                    if ((strpos('me' .$Node3->getAttribute('class'), 'listing-results-right clearfix') > 0)) {//status-wrapper
                        foreach ($Node3->getElementsByTagName('span') as $Node7) {
                            if (strpos('me' .$Node7->getAttribute('class'), 'num-beds') > 0) {
                                $beds=$Node7->nodeValue;
                            } elseif (strpos('me' .$Node7->getAttribute('class'), 'num-baths') > 0) {
                                $baths=$Node7->nodeValue;
                            } elseif (strpos('me' .$Node7->getAttribute('class'), 'num-reception') > 0) {
                                $rRoom=$Node7->nodeValue;
                            }
                        }

                        foreach ($Node3->getElementsByTagName('h2') as $Node8) {
                            if ((strpos('me' .$Node8->getAttribute('class'), 'listing-results-attr') > 0)) {
                                $title=$Node9=$Node8->getElementsByTagName('a')->item(0)->nodeValue;
                            }
                        }
                        foreach ($Node3->getElementsByTagName('a') as $Node6) {
                            if (strpos('me' .$Node6->getAttribute('class'), 'listing-results-price text-price') > 0) {
                                $extraChar='';
                                $sNode=$Node6->getElementsByTagName('span');
                                if (!is_null($sNode)) {
                                    $sNode1=$sNode->item(0);
                                    if (!is_null($sNode1)) {
                                        $extraChar=$sNode1->nodeValue;
                                    }
                                }
                                $price=getPrice(str_ireplace($extraChar, '', $Node6->nodeValue));
                                $myUrl=myWebsite.$Node6->getAttribute('href');
                            } elseif (strpos('me' .$Node6->getAttribute('class'), 'listing-results-address') > 0) {
                                $address=$Node6->nodeValue;
                                $str1=explode(",", $address);
                                $sAddress='';
                                $city='';
                                if (count($str1)>0) {
                                    if (count($str1)==1) {
                                        //echo "str===1";echo "\n sAddress is".
                                        $sAddress=$str1[0];
                                    } elseif (count($str1)==2) {
                                        //echo "str===2";echo "\n zipcode is".
                                        $sAddress=$str1[0];
                                        //$zipcode=$str1[1];
                                    } elseif (count($str1)==3) {
                                        //echo "str===3";echo "\n sAddress is".echo "\n City is".echo "\n zipcode is ".
                                        $sAddress=$str1[0];
                                        $city=$str1[1];
                                        //$zipcode=$str1[2];
                                    } elseif (count($str1)==4) {
                                        //echo "str===4";echo "\n sAddress is".echo "\n City is".echo "\n zipcode is ".
                                        $sAddress=$str1[0].", ".$str1[1];
                                        $city=$str1[2];
                                        //$zipcode=$str1[3];
                                    } elseif (count($str1)==5) {
                                        //echo "str===4";echo "\n sAddress is".echo "\n City is".echo "\n zipcode is ".
                                        $sAddress=$str1[0].", ".$str1[1].", ".$str1[2];
                                        $city=$str1[3];
                                        //$zipcode=$str1[4];
                                    }
                                }
                            }
                        }
                    } elseif ((strpos('me' .$Node3->getAttribute('class'), 'status-wrapper') > 0)) {//
                        $iNode=$Node3->getElementsByTagName('img');
                        if (!is_null($iNode->item(0))) {
                            $imgLink=$iNode->item(0)->getAttribute('src');
                            $imgLink=str_ireplace("354/255", "645/430", $imgLink);
                            echo "\n image link is:".$imgLink;
                        }
                    }
                }
                $latLang=getLatLang($myUrl);
                $str=explode('|', $latLang);
                $latitude=$str[0];
                $longitude=$str[1];
                //echo"\n latitude is--->".$latitude;
                //echo"\n longitude is--->".$longitude;
                insertData($title, $address, $sAddress, $city, $zipCode, $latitude, $longitude, $beds, $price, $imgLink, $myUrl, $type);
            }
        }
    }
    unset($doc);
}
echo "Done!";

function getLatLang($myUrl)
{
    echo"\n Url  is--->".$myUrl;
    $mydata=chkError($myUrl);
    if ($mydata!="") {
        if (stripos('me'.$mydata, 'https://r.zoocdn.com/assets/map-pin.png') > 0) {
            $str=explode('https://r.zoocdn.com/assets/map-pin.png', $mydata);
            if (stripos('me'.$str[0], 'latitude":') > 0) {
                $str1=explode('latitude":', $str[0]);
                $str2=explode(',', $str1[1]);
                $latitude=trim($str2[0]);
                $str3=explode(',', $str2[1]);
                $str2=str_ireplace('"longitude":', '', $str3[0]);
                $longitude=trim(str_ireplace('}', '', $str2));
            }
            return $latitude."|".$longitude;
        }
    }
}
//====================================================================
function insertData($title, $address, $sAddress, $city, $zipCode, $latitude, $longitude, $beds, $price, $imgLink, $myUrl, $type)
{
    $m2Price=0;
    global $con , $mfrom;
    if ((int)($beds)>0) {
        if ($type==1) {
            $m2Price=((int)($price))/(int)($beds);
        } else {
            $m2Price=((int)($price) *12)/(int)($beds);
        }
    }
    //echo "\n------------- Property url is --->".$myUrl;
    if ($type==1) {
        $qry1="INSERT ignore INTO `st_listings_sale`(`title`, `address`, `street`, `city`, `zipcode`, `latitude`, `longitude`, `bedRoom`, `price`,`m2Price`, `imgLink`, `url`,`provider`) VALUES ('".trim($title)."','".trim($address)."','".trim($sAddress)."','".trim($city)."','".trim($zipCode)."','".trim($latitude)."','".trim($longitude)."','".trim($beds)."','".trim($price)."','".trim($m2Price)."','".trim($imgLink)."','".trim($myUrl)."','".myWebsite."')";
    } elseif ($type==0) {
        $qry1="INSERT ignore INTO `st_listings_rent`(`title`, `address`, `street`, `city`, `zipcode`, `latitude`, `longitude`, `bedRoom`, `price`,`m2Price`, `imgLink`, `url`,`provider`) VALUES ('".trim($title)."','".trim($address)."','".trim($sAddress)."','".trim($city)."','".trim($zipCode)."','".trim($latitude)."','".trim($longitude)."','".trim($beds)."','".trim($price)."','".trim($m2Price)."','".trim($imgLink)."','".trim($myUrl)."','".myWebsite."')";
    }
    //echo "<br> qry is---->".$qry1."<br>";
    mysqli_query($con, $qry1);
}
//mysqli_close($con);
//====================================================================
function getProxy()
{
    $myproxy='';
    $try=0;
    $myARR=file(proxyFile);
    $count = count($myARR);
    while ($myproxy=='') {
        $x = rand(0, $count);
        echo $myproxy = $myARR[$x] ;//"'".$myARR[$x]."'";
        if ($try>3 && $myproxy=='') {
            $myproxy = 'empty';
        }
        $try++;
    }
    if ($myproxy == 'empty') {
        return array();
    }
    return array('proxy' => $myproxy);
    //return array('proxy' => array($myproxy,proxyUserName,proxyPassword));
}
//====================================================================
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
//====================================================================
function saveMyFile($myFile, $myStr, $append = 0)
{
    if ($append == 1) {
        file_put_contents($myFile, $myStr, FILE_APPEND | LOCK_EX);
    } else {
        file_put_contents($myFile, $myStr);
    }
    return true;
}
//====================================================================
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
//=======================================================================
function replaceDoubleQuotes($myStr)
{
    return str_replace("  ", " ", str_replace("\n", " ", str_replace("'", "''", str_replace("\\''", "\\'", $myStr))));
}
function getPrice($myStr)
{
    $myStr = trim(str_replace("$", "", $myStr));
    $myStr = trim(str_replace("£", "", $myStr));
    $myStr = trim(str_replace("€", "", $myStr));
    $myStr = trim(str_replace("pcm", "", $myStr));
    $myStr = str_replace(chr(194), " ", $myStr);
    $myStr = trim(str_replace(",", "", $myStr));
    $myStr = trim(str_replace("+", ",", $myStr));
    $myStr = trim(str_replace(strtolower("UK"), "", strtolower($myStr)));
    $myStr = trim(str_replace(strtolower("delivery"), "", strtolower($myStr)));
    $myStr = preg_replace('/[[:^print:]]/', '', $myStr);
    $myStr = trim(str_replace("  ", "", $myStr));
    $myStr = trim(str_replace(" ", "", $myStr));
    /*$myArr = explode(",", $myStr);
    if (count($myArr) == 3) {
        $myArr1 = array("a" => strval(trim($myArr[0])), "b" => strval(trim($myArr[1])), "c" => strval(trim($myArr[2])));
    }
    if (count($myArr) == 2) {
        $myArr1 = array("a" => strval(trim($myArr[0])), "b" => strval(trim($myArr[1])));
    }
    if (count($myArr) == 1) {
        $myArr1 = array("a" => strval(trim($myArr[0])));
    }
    $myStr = array_sum($myArr1);*/
    return $myStr;
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
//-----------------------------------------------------------------------------------------------------------------
