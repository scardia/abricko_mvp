mapboxgl.accessToken = 'pk.eyJ1Ijoic2FoaWw5NiIsImEiOiJjanBhdTF3MmUyY2FzM3FweDR2a21leGgxIn0.ZTpWeMbbJeYd345EkPy-NQ';

var map = new mapboxgl.Map({
    container: 'map', // container id
    style: 'mapbox://styles/sahil96/cjqwc303p0ryc2smsjcxsqekv',//'mapbox://styles/sahil96/cjq3mab2x8v9s2rqacdeia5a0',
    center:[2.2426,53.4808], // starting position [lng, lat] [-2.12482, 57.1408],
    zoom:05 // starting zoom
});
// Full Screen Optiomn to main map
map.addControl(new mapboxgl.FullscreenControl());

var emptyGeojson = {
    "type": "FeatureCollection",
    "features": []
};
//setting mini Map
var minimap = new mapboxgl.Map({
    container: 'minimap', // container id
    style: 'mapbox://styles/sahil96/cjq4nghdh6fpp2smv1c0ww0e1',//'mapbox://styles/sahil96/cjphssb7k08k02skaug8c77aq', //stylesheet location
    center: [-1.000918, 56.009865], // starting position [-2.212918, 53.509865],
    interactive: false,
    hash: false,
    zoom: 3.1
});

var colors = {
    light: '#b2ffff',
    average: '#8edfff',
    dark: '#1974d2',
    highlight: 'orange'
}

var heights = {
}

map.on('load', function() {

    var yields_dots = GetJson('js/yields.geojson');
    const coll = [];
    for (let f of yields_dots.features) {
        coll.push(turf.buffer(f, 50, {
            units: 'meters'
        }));
    }
    var yields = turf.featureCollection(coll);
    //set up data sources
    map.addSource('yields', {
            'type': 'geojson',
            'data': yields
        })
        .addSource('yields_dots', {
            'type': 'geojson',
            'data': yields_dots
        })
        .addSource('highlight', {
            'type': 'geojson',
            'data': emptyGeojson
        })
        .addSource('radiusHighlight', {
            'type': 'geojson',
            'data': emptyGeojson
        })


    //set up data layers
    map
    .addLayer({
        'id': 'yields',
        'type': 'fill-extrusion',
        'source': 'yields',
        'paint': {
            'fill-extrusion-color': [ 'interpolate', ['exponential', 0], ['get', 'yield'], 0, colors.dark, 5, colors.average, 10, colors.light ],
            'fill-extrusion-base': 0,
            'fill-extrusion-height': [ 'interpolate', ['exponential', 0], ['get', 'yield'], 0, 0, 5, 300, 10, 1000 ],
            //'fill-opacity-transition': {'duration':1000},
            'fill-extrusion-opacity': 1,
        },
        'paint.tilted': {
            'fill-extrusion-opacity': 0.9
        }
    }, 'airport-label')
    .addLayer({
        'id': 'radiusHighlight',
        'type': 'fill-extrusion',
        'source': 'radiusHighlight',
        'paint': {
            'fill-extrusion-height': {
                "stops": [
                    [0, 0],
                    [1450000, 0]
                ],
                "property": "yield",
                "base": 0
            },
            'fill-extrusion-color': {
                "stops": [
                    [0, '#e53e0e'],
                    [145000, '#f1f075']
                ],
                "property": "yield",
                "base": 1
            },
            'fill-extrusion-opacity': 1
        },
        'paint.tilted': {
            'fill-extrusion-height': {
                "stops": [
                    [0, 10],
                    [20, 4000]
                ],
                "property": "yield",
                "base": 1
            }
        }
    }, 'water').addLayer({
        'id': 'highlighted_fill',
        'type': 'line',
        'source': 'highlight',
        'paint': {
            'line-color': colors.highlight
        },
        'paint.tilted': {
            'line-opacity': 0
        }
    }, 'airport-label')
    .addLayer({
        'id': 'highlighted_extrusion',
        'type': 'fill-extrusion',
        'source': 'highlight',
        'paint': {
            'fill-extrusion-color': colors.highlight,
            'fill-extrusion-base': 0,
            'fill-extrusion-height': {
                "stops": [
                    [0, 10],
                    [20, 3000]
                ],
                "property": "yield",
                "base": 1
            },
            'fill-extrusion-opacity': 0
        },
        'paint.tilted': {
            'fill-extrusion-opacity': 0.5
        }
    }, 'airport-label');
})

/* adding interactivity on layer*/
map.on('load', function() {
    map.getCanvas().style.cursor = 'context-menu';
    // Create a popup, but don't add it to the map yet.
    var popup = new mapboxgl.Popup({
        closeButton: true,
        closeOnClick: true
    });
    map.on('mouseenter', 'yields', function(e) {
        // Change the cursor style as a UI indicator.
        map.getCanvas().style.cursor = 'pointer';
        var coordinates = e.features[0].geometry.coordinates[0][0];
        var title = e.features[0].properties.name;
        var imgLink = e.features[0].properties.imgLink;
        var url = e.features[0].properties.url;
        var address = e.features[0].properties.address;
        var YieldVal = parseFloat(e.features[0].properties.yield);
        var zipcode = e.features[0].properties.zipcode;
        repImage(imgLink);
        if (isNaN(YieldVal)) {
            YieldVal = '';
        } else {
            YieldVal = YieldVal.toFixed(2);
        }
        while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
            coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
        }
        popup.setLngLat(coordinates)
            .setHTML('<div style="min-Width:250px;height:auto;background-color: #333333;"><a style="text-decoration: none;" target="_blank" href="' + url + '"><h3>' + title + '</h3><h4>' + address +
            '</h4><p><b>Yield Value: </b>' + YieldVal + '% </p></a></div>')
            .addTo(map);
    });
    map.on('mouseleave', 'yields', function() {
        $('.mapboxgl-popup-content').mouseover(function () {
            popup.addTo(map);
        });
        $('.mapboxgl-popup-content').mouseleave(function () {
            popup.remove();
        });
        popup.remove();
        map.getCanvas().style.cursor = 'context-menu';
    });

        
});

map.on('click', 'yields', function (e) {
    var coordinates = e.features[0].geometry.coordinates[0][0];
    var url = e.features[0].properties.url;
    window.open(url);
});

//set heights of the buildings
document.querySelector('#slider').addEventListener('change', function(){
      setScale(parseInt(this.value));
  });

function setScale(max){
    max = 40-max;
    if (_3d==true){
        document.querySelector('#max').innerHTML = max+'+';
        map.setPaintProperty('yields','fill-extrusion-height',{"stops": [[0,40],[max,4500],], "property": "yield", "base": 1})
    }
  }

// add geocoder to mini map
var geocoder = new MapboxGeocoder({
    accessToken: mapboxgl.accessToken
});

document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

//mouse move evebt for selecting top ten records
map.on('moveend', function() {
    var ll = map.getBounds();
    var lon_min = ll.getWest();
    var lat_min = ll.getSouth();
    var lon_max = ll.getEast();
    var lat_max = ll.getNorth();
    getTopTen1(lon_min, lat_min, lon_max, lat_max);
});

//getting top ten records between lang/lat of four corners
function getTopTen1(minx, miny, maxx, maxy){
    myUrl = 'main.php?qry=getTopTen1&minx='+minx+'&miny='+miny+'&maxx='+maxx+'&maxy='+maxy;
    $.ajax({
        url: myUrl,
        type: 'GET',
        dataType: "text json",
        success: function (res) {
            if (res!=""){
                res.sort(function(a, b){
                    return a.y - b.y;
                });
                setTimeout(function(){loadbars(res);},200);
            }
        }
    });
}

// loading data bars in chart
function loadbars(myData) {
    //alert(myData);
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        axisX: {
            interval: 1
        },
        theme: "dark1",
        data: [
            {
                mouseover: function(e){
                    repImage(e.dataPoint.url);//url
                    //new mapboxgl.Marker({color: 'red'});
                },
                click: function(e){
                     map.flyTo({center: [e.dataPoint.lang,e.dataPoint.lat],zoom:17});
                     //window.open(e.dataPoint.pUrl);//pUrl
                },
                type: "bar",
                color: "#4f81bc",
                indexLabelFontWeight: 300,
                indexLabelFontFamily: "Verdana",
                toolTipContent: "<b>{label}</b><br>Yield: {y}%",//label //y
                dataPoints: myData
           },
       ]
    });
    chart.render();
}

function repImage(imgLink){
    var image = document.getElementById("imgCorner");
    image.src = imgLink;
}

//adding Cities To miniMap
var greatPlaces = GetJson('./assets/greatPlaces.json');
greatPlaces.features.forEach(function(city, index) {
    // create the popup
    var popup = new mapboxgl.Popup({
            closeButton: false
        })
        .setHTML('<p style="color: coral;">' + city.properties.city + '</p>');

    // create DOM element for the marker
    var el = document.createElement('div');
    el.className = 'marker';
    el.id = 'city' + index;
    // create the marker
    new mapboxgl.Marker(el)
        .setLngLat(city.geometry.coordinates)
        //.setPopup(popup) // sets a popup on this marker
        //popup.setHTML('<p>' + city.properties.city + '</p>')
        .addTo(minimap);

    document.getElementById('city' + index).setAttribute('onclick', 'jumpToCity(' + index + ')')
})
// as per the name suggests
function jumpToCity(index) {
    var city = greatPlaces.features[index];
    document.querySelector('#slider').value = city.properties.scale;
    map.jumpTo({
        center: city.geometry.coordinates
    });
    //setScale(city.properties.scale)
}

// minimap flyTo interactivity
minimap.on('mouseup', function(e) {
    var coords = (minimap.unproject(e.point))
    map.jumpTo({
        center: coords
    })
})

// show/hide labels
function toggleLabels(truthiness) {
    var visibility = truthiness ? 'visible' : 'none'
    map.style.stylesheet.layers.forEach(function(layer) {
        if (layer.type === 'symbol') map.setLayoutProperty(layer.id, 'visibility', visibility)
    })
}

// show/hide roads
function toggleRoads(truthiness) {
    var visibility = truthiness ? 'visible' : 'none'
    map.style.stylesheet.layers.forEach(function(layer) {
        if (layer.type === 'line') map.setLayoutProperty(layer.id, 'visibility', visibility)
    })
}

// map tilt functionality
function tilt(eh) {
    if (eh == true){
        _3d=true;
        map.setPaintProperty('yields','fill-extrusion-height',{"stops": [[0,40],[15,4500],], "property": "yield", "base": 1})
    } else {
        _3d=false;
        map.setPaintProperty('yields','fill-extrusion-height',{"stops": [[0,1],[0,1],], "property": "yield", "base": 1})
    }
    var state = !eh ? {
        pitch: 0,
        klass: [''],
    } : {
        pitch: 70,
        klass: ['tilted'],
    }
    map
        .easeTo({
            pitch: state.pitch
        })
}

//function for retriving json data from url
function GetJson(yourUrl) {
    var Httpreq = new XMLHttpRequest(); // a new request
    Httpreq.open("GET", yourUrl, false);
    Httpreq.send(null);
    return JSON.parse(Httpreq.responseText);
}

function setInspector(mode) {
    inspector = mode;
    var klass = mode === 'none' ? '' : 'inspector'
    mapObj.classList = klass;

    if (mode === 'none') {
        map.getSource('radiusHighlight').setData(emptyGeojson)
        map.getSource('highlight').setData(emptyGeojson)
    }
    if (mode === 'radius') document.querySelector('#address').innerHTML = 'Within 500 meters of here'
}

var _3d = true;


function setFilter(from, to) {
    //alert(from + ' , ' + to)
    //map.setFilter('yields',["<=", 'yield', to]);
    var myID = $(this).attr('for');
    var myDiv = $(this).parent();
    $(myDiv).find('input').each(function(e){
        $(this).removeAttr('checked');
    });
    $('#'+myID).attr('checked','checked');
    //map.setFilter('yields',[">=", 'yield', from],["<=", 'yield', to]);
    if (to==5){
        map.setFilter('yields',["<", 'yield', to]);
    }else if (from==5 && to==10) {
        var new_Filter = ["all",[">=", 'yield', from],["<=", 'yield', to]];
        map.setFilter('yields',new_Filter);
    }else if (from==10) {
        map.setFilter('yields',[">", 'yield', from]);
    }
}