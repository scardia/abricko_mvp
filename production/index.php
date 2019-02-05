<?php
session_start();
<script type="text/javascript" src="//script.crazyegg.com/pages/scripts/0083/3687.js" async="async"></script>
$city=$_REQUEST['city'];
include('assets/config.nic.php');
if (!isset($_SESSION['user'])) {
    //header("location: login.php?city=".$city);
}

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$query = "SELECT user FROM st_users where email='".$_SESSION['user']."'";
$result = mysqli_query($con, $query);
$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
$userName= ucwords($row[user]);
//updateZipCode();
//updateYieldVal();

//$myfile = fopen("js/yields.geojson", "w") or die("Unable to open file!");
//fwrite($myfile, $data);
//fclose($myfile);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/favicon.ico" type="image/ico" />

  <title>Yield Value Inspector</title>

  <!-- Bootstrap -->
  <link href="/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="/vendors/iCheck/skins/flat/green.css" rel="stylesheet">

  <!-- bootstrap-progressbar -->
  <link href="/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <!-- JQVMap -->
  <link href="/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
  <!-- bootstrap-daterangepicker -->
  <link href="/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="/build/css/custom.min.css" rel="stylesheet">
  <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.52.0/mapbox-gl.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.52.0/mapbox-gl.css' rel='stylesheet' />
  <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.min.js'></script>
  <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css'
    type='text/css' />
  <link href='assets/base.css' rel='stylesheet' />
  <style>
    #map { position:absolute; top:0; bottom:0; width:100%; }
            #map {
                position:absolute;
                top:0; bottom:0;
                right:0;
                left:0;
                background:rgba(52,51,50,1);
                overflow:hidden;
                cursor: pointer;
            }
            #sidebar, #colorscale {
              /*background:#2A3F54 !important;*/
              width:100%;
            }

            #sidebar{
            }
            .right-corner {
              position: fixed;
              bottom: 0;
              right: 0;
            }
            .section {
              border-bottom:1px solid #1c1c1c;
            }

            .label {
              line-height:25px;
            }
            #tooltip {
              position:absolute;
              z-index:100;
              pointer-events:none;
              display:none;
              opacity:0;
            }

            .inspector:hover #tooltip {
              opacity:1;
              display:block;
            }
            .inspector .mapboxgl-canvas-container.mapboxgl-interactive {
              cursor:none;
            }
            .dot {
              width:5px;
              height:5px;
              background:white;
              border-radius:2.5px;
              position:absolute;
            }
            .line {
              position:absolute;
              height:0px;
              width:30px;
              transform:rotate(45deg);
              border-top:3px solid #333;
              transform-origin:left center;
              z-index:-1;
              margin-top:-1.5px;
            }
            .bubble{
              background:#333;
              color:white;
              padding:6px;
              margin:15px;
            }
            #legend {
              height:20px;
              width:100%;
              background: -webkit-linear-gradient(left, #160e23 0%, #00617f 20%, #55e9ff 100%);
            }

            /* form elements & geocoder overrides*/

            .mapboxgl-ctrl-geocoder {
              min-width:0px;
              margin:0px !important;
            }
            button {
               margin-bottom: 0px !important;
               margin-right: 0px !important;
           }
            .mapboxgl-ctrl-geocoder, .mapboxgl-ctrl-geocoder ul{
              width:100%;
              -webkit-filter:invert(100%);
              color:white;

            }
            .mapboxgl-ctrl-geocoder ul > li.active > a,
            .mapboxgl-ctrl-geocoder ul > li > a:hover {
              color:#fff;
              font-weight:bold;
              background:#404040;
            }
            .mapboxgl-ctrl-geocoder input[type='text'],
            .mapboxgl-ctrl-geocoder input[type='text']:focus {
              color:black;
            }

            ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
              color: #333;
            }
            .mapboxgl-ctrl-geocoder ul,
            li.active {
              color: white;
            }
            .fill-dark .rounded-toggle {
              background:#1c1c1c;
              color:#222;
              padding:0px;
              margin:0px;
            }
            .dark .rounded-toggle > *{
              color:#999;
            }
            .fill-dark .rounded-toggle input[type=radio]:checked + label{
              background:#ccc;
              color:black;
              font-weight:bold;
            }

            .mapboxgl-ctrl-geocoder ul > li > a {
              /*color:#ccc;*/
            }
            /* legend stuff */

            #scale{
              width:120px;
              margin:10px;
              height:120px;
              z-index:-99;
            }
            #scale canvas{
              overflow:hidden;
              pointer-events:none;
            }
            .tilted #colorscale {
              display:none;
            }
            .tilted #scale {
              z-index:99;
              background:rgba(0,0,0,0.75);
              border-radius:50%;
            }

            #scale .mapboxgl-ctrl,
            #minimap .mapboxgl-ctrl{
              display:none;
            }
            .icon:not(.big):after,
            .rcon:not(.big):after{
              margin:0px;
              font-size:18px;
            }


            #minimap{
              height:220px;
              cursor:crosshair;
            }

            .marker {
              width:8px;
              height:8px;
              border-radius:50%;
              border:2px solid #333;
              background:#55e9ff;
              cursor:pointer;
              margin-left:-6px;
              margin-top:-6px;
              transition:all 0.2s;
            }

            .rangeslider{
                position: relative;
                margin-top: -5px;
                margin-left: 20px;
            }
            .rangeslider input{
                position: absolute;
            }

            .marker:hover{
              background:orange;
              width:16px;
              height:16px;
              margin-left:-10px;
              margin-top:-10px;
              border:2px solid white;
            }
            .mapboxgl-popup .mapboxgl-popup-tip{
              opacity:0;
            }
            .mapboxgl-popup-content {
              background:#333333;
              padding:4px 8px;
            }
            .mapboxgl-ctrl-geocoder{
                /*margin-right:550% !important;*/
            }
            .mapboxgl-ctrl-group{
                /*margin-right:400% !important;*/
            }
            .mobile{
              display:none;
            }
            input[type='range']:nth-child(1)::-webkit-slider-thumb{
              z-index : 1000;
            }

            #imgCorner{
              object-fit: cover;
            }
            
input[type=range],
input[type=range]::-webkit-slider-thumb {
  -webkit-appearance:none;
  margin:0; padding:0; border:0;
  }
input[type=range] {
  display:inline-block!important;
  vertical-align:middle;
  height:12px;
  padding:0 2px;
  border:2px solid transparent;
  background:rgba(0,0,0,0.25);
  min-width:100px;
  overflow:hidden;
  cursor:pointer;
  }
  input[type=range]::-ms-fill-upper { background:transparent; }
  input[type=range]::-ms-fill-lower { background:rgba(255,255,255,0.25); }

/* Browser thingies */
input[type=range]::-moz-range-track { opacity:0; z-index: 100; }
input[type=range]::-moz-range-track { opacity:0; z-index: 100; }
input[type=range]::-webkit-range-track { opacity:0; z-index: 100; }
input[type=range]::-ms-tooltip      { display:none;  }

/* For whatever reason, these need to be defined
 * on their own so dont group them */
input[type=range]::-webkit-slider-thumb {
  background:rgba(255,255,255,0.75);
  height:12px; width:20px;
  border-radius:3px;
  cursor:ew-resize;
  box-shadow:rgba(255,255,255,0.25) -1200px 0 0 1200px;
  z-index: 10000;
  }
input[type=range]::-ms-thumb {
  margin:0;padding:0;border:0;
  background:rgba(255,255,255,0.75);
  height:12px; width:20px;
  border-radius:3px;
  cursor:ew-resize;
  box-shadow:rgba(255,255,255,0.25) -1200px 0 0 1200px;
  z-index: 10000;
  }
input[type=range]::-moz-range-thumb {
  margin:0;padding:0;border:0;
  background:rgba(255,255,255,0.75);
  height:12px; width:20px;
  border-radius:3px;
  cursor:ew-resize;
  box-shadow:rgba(255,255,255,0.25) -1200px 0 0 1200px;
  z-index: 10000;
  }

input[type=range]:disabled::-moz-range-thumb { cursor: default;}
input[type=range]:disabled::-ms-thumb { cursor: default;}
input[type=range]:disabled::-webkit-slider-thumb { cursor: default;}
input[type=range]:disabled { cursor: default; }

            @media (max-width: 800px) {
              .desktop {
                display: none;
              }

              .mobile{
                display:block;
              }
              #map {
                left:0px;
              }
            }

            .cornerbanner{
              background-color: rgba(0,0,0, 0.5);
              height: 50px;
              width: 100%;
              position:absolute;
              bottom:0px;
              margin:0;
            }
        </style>

<body class="nav-sm" style="background-color:#2A2A2A;">
  <div class="container body">
    <div class="main_container">

      <!-- sidebar menu -->
      <?php include('sidebar.php');?>
      <!--sidebar menu-->
      <!-- /top navigation -->

      <!-- page content-->
      <div class="right_col" role="main" style="background-color:#2A2A2A;">
        <!-- <iframe src="index0012.php" class="row10 col12" style="min-height:760px;position: relative;border:none;height: 100%;width:100%; overflow: hidden;" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true"></iframe>-->
        <div class="row" style="border:0px solid black;background-color:#404040!important;">
          <div class="col-md-3 col-sm-3 col-xs-4 tile_stats_count" style="border:0px solid black;background-color:#404040!important;">
            <div class='pin-topleft z100 dark fill-dark desktop' id='sidebar'>
              <div class='pad1 geocoder z100 pin-top' id="geocoder"></div>
              <div id='minimap' class='section'>
              </div>
              <div class='pad1 section clearfix'>
                <span class='small uppercase label strong'>Visualization</span>
                <div class='rounded-toggle fr col4'>
                  <input id='pizza' type='radio' name='rtoggle' value='pizza'>
                  <label for='pizza' class='col6 center' onclick='tilt(false)'>2D</label>
                  <input id='penny' type='radio' name='rtoggle' value='penny' checked='checked'>
                  <label for='penny' class='col6 center' onclick='tilt(true)'>3D</label>
                </div>
              </div>
              <div class='pad1 section clearfix '>
                <span class='small uppercase label quiet'>roads</span>
                <div class='rounded-toggle fr col4'>
                  <input id='roadson' type='radio' name='roads' value='roadson' checked='checked'>
                  <label for='roadson' class='col6 center' onclick='toggleRoads(true)'>ON</label>
                  <input id='roadsoff' type='radio' name='roads' value='roadsoff'>
                  <label for='roadsoff' class='col6 center' onclick='toggleRoads(false)'>OFF</label>
                </div>
              </div>
              <div class='pad1 section clearfix'>
                <span class='small uppercase label quiet'>Labels</span>
                <div class='rounded-toggle fr col4'>
                  <input id='labelon' type='radio' name='labels' value='labelon' checked='checked'>
                  <label for='labelon' class='col6 center' onclick='toggleLabels(true)'>ON</label>
                  <input id='labeloff' type='radio' name='labels' value='labeloff'>
                  <label for='labeloff' class='col6 center' onclick='toggleLabels(false)'>OFF</label>
                </div>
              </div>

              <fieldset class="pad1 section">
                <span class="fl small uppercase label quiet" style="line-height:18px">yields</span>
                <span class="fl small uppercase label quiet" style="line-height:18px">
                <span id="minval">0</span>
                  <span>%</span>
                  </span>

                <!--<div class="col12 rounded-toggle fr" style="clear:both;">
                  <input id='filters_low' type='radio' name='filters' value='low'>
                  <label for='filters_low' class='col4 center' onclick='setup(0,5)'>&lt; 5%</label>
                  <input id='filters_med' type='radio' name='filters' value='medium'>
                  <label for='filters_med' class='col4 center' onclick='setup(5,10)'>5% - 10%</label>
                  <input id='filters_hi' type='radio' name='filters' value='high'>
                  <label for='filters_hi' class='col4 center' onclick='setup(10,25)'>&gt; 10%</label>
                </div>-->
                <!--<div class="col12" style="clear:both;">
                  <span class="small uppercase label quiet" style="line-height:18px">Min</span>-->
                  <div class='col8 fr'>
                    <input type="range" value="0" min="0" max="25" step="0.5" id="filter_from" class="filter">
                  </div>
                <!--</div>-->
                
                <!--<div class="col12" style="clear:both;">
                  <span class="small uppercase label quiet" style="line-height:18px">Max</span>
                  <span class="small uppercase label quiet" style="line-height:18px" id="maxval"></span>
                  <span class="small uppercase label quiet" style="line-height:18px">%</span>
                  <div class='col8 fr '>
                    <input type="range" value="15" min="0" max="25" step="0.5" id="filter_to" class="filter col12">
                  </div>
                </div>-->

              </fieldset>

              <fieldset class='pad1 section'>
                <span class='fl small uppercase label quiet' style='line-height:18px'>Adjust Scale</span>
                <div class='col8 fr'>
                  <input type="range" value="0" min="0" max="25" step="0.5" id="slider" class="col12">
                </div>
              </fieldset>
            </div>
            <div class='pin-bottomright desktop' id='legends'>
              <div class='pin-bottomright dark pad1' id='colorscale' style='margin:10px'>
                <div id='legend'></div>
                <span class='fl small quiet uppercase'>0</span>
                <span class='fr small quiet uppercase' id='max'></span>
                <span class='center block small quiet uppercase icon account'>/km<sup>2</sup></span>
              </div>
              <div id='scale' class='pin-bottomright dark'>
                <div class='rcon small account z100 quiet center pin-bottom pad1y code' id='people'>4000</div>
              </div>
            </div>
          </div>
          <div class="col-md-09 col-sm-09 col-xs-9 tile_stats_count" style="height: 60vh;border:1px solid black;">

            <!--min-height:500px;-->
            <div id='map'></div>
            <div id='tooltip' class='dark'>
              <div class='dot'></div>
              <div class='line'></div>
              <div class='bubble'>
                <span class="rcon account strong" id='blockcount'></span>
                <span class="quiet small">
                  (<span id='blockdensity'></span>/km<sup>2</sup>)
                </span>
                <div id="address" class="small quiet"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" style="height: 34vh;">
          <div class="row col-12 col-md-12 p-0" style="height: 100%;background-color:#2A2A2A;margin:0px">
            <!--min-height:260px;-->
            <div class="col-8 col-md-8" id="" style="float:left;background-color:#2A2A2A;padding-left: 0px;">
              <div class="title uppercase label strong" style="padding-bottom: 0; margin-bottom: -10px;"><h2>Top yields</h2></div>
              <div class="col-12 col-md-12" style="height: 34vh;" id="chartContainer"></div>
            </div>
            <div class="col-4 col-md-4" style="height: 100%;border:0px solid black;padding-right: 0px; float:right;" id='rightCorner'>
              <img id='imgCorner' src='assets/abricko_logo_trans.png' alt='Property Image' style="min-width:100%; min-height:100%;" />
            </div>
          </div>
          <script src="assets/canvasjs.min.js"></script>
        </div>
      </div>
    </div>
  </div>
  <!-- jQuery -->
  <script src="/vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="/vendors/fastclick/lib/fastclick.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="/build/js/custom.min.js"></script>
  <script src="js/turf.js"></script>
  <script src='js/app.js' rel='stylesheet'></script>
  <script>
      var city = '<?php echo $city; ?>';
      if (city!=''){
        var url='';
        url='https://api.mapbox.com/geocoding/v5/mapbox.places/'+ encodeURI(city) +'.json?access_token=pk.eyJ1IjoiYWJyaWNrbyIsImEiOiJjanJhaGxlYzcwaG40NDRsaHhocXdocDVhIn0.hVzJBL6S1alSJ_-bbKc9QQ';
        var data=GetJson(url);
        var langLat =(data['features'][0]['center']);
        map.flyTo({
            center: langLat,
            zoom: 11
        });
      }
  </script>
</body>

</html>
