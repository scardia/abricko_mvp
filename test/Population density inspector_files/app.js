mapboxgl.accessToken = 'pk.eyJ1Ijoic2FoaWw5NiIsImEiOiJjanBhdTF3MmUyY2FzM3FweDR2a21leGgxIn0.ZTpWeMbbJeYd345EkPy-NQ';
const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/light-v9',
    center: [-2.12482, 57.1408],
    zoom: 13
});

map.on('load', function() {
    map.getCanvas().style.cursor = 'pointer';
    // Create a popup, but don't add it to the map yet.
    var popup = new mapboxgl.Popup({
        closeButton: true,
        closeOnClick: true
    });
    map.on('mouseenter', 'yields_dots', function(e) {
        // Change the cursor style as a UI indicator.
        map.getCanvas().style.cursor = 'pointer';

        var coordinates = e.features[0].geometry.coordinates.slice();
        var title = e.features[0].properties.title;
        var imgLink = e.features[0].properties.imgLink;
        var url = e.features[0].properties.url;
        var address = e.features[0].properties.address;
        var yeildVal = parseFloat(e.features[0].properties.yield);
        if (isNaN(yeildVal)) {
            yeildVal = '';
        } else {
            yeildVal = yeildVal.toFixed(2);
        }

        // Ensure that if the map is zoomed out such that multiple
        // copies of the feature are visible, the popup appears
        // over the copy being pointed to.
        while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
            coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
        }

        // Populate the popup and set its coordinates
        // based on the feature found.
        popup.setLngLat(coordinates)
            .setHTML(
                '<div style="Width:275px;height:auto;background-color: #fff;"><a style="text-decoration: none;" target="_blank" href="' +
                url + '"><h3>' +
                title + '</h3><p>' + address + '</p><p><b>Yeild: </b>' + yeildVal +
                '</p></a></div>')
            .addTo(map);
        var x = document.getElementById("rightCorner");
        if (x.style.display === "none") {
            x.style.display = "block";
            var image = document.getElementById("imgCorner");
            image.src = imgLink;
            //$('.imgCorner').attr("src", imgLink);
        } else {
            x.style.display = "block";
        }
    });

    map.on('mouseleave', 'yields_dots', function() {
        //map.getCanvas().style.cursor = '';
        var x = document.getElementById("rightCorner");
        if (x.style.display === "block") {
            x.style.display = "none";
        }
    });
});
var minimap = new mapboxgl.Map({
    container: 'minimap', // container id
    style: 'mapbox://styles/mapbox/light-v9', //stylesheet location
    center: [-2.212918, 53.509865], // starting position
    interactive: false,
    hash: false,
    zoom: 3.1
});

// legend
var scale = new mapboxgl.Map({
    container: 'scale', // container id
    style: 'mapbox://styles/mapbox/light-v9', //styles/sahil96/cjpmijh9r03dy2rp1qz8fhpqt', //stylesheet location
    center: [0, 0], // starting position
    pitch: 60,
    hash: false,
    zoom: 15, // starting zoom
});
// add geocoder
//map.addControl(
//    new mapboxgl.Geocoder({
//        'container': document.querySelector('.geocoder'),
//        'placeholder': 'Explore any UK city...',
//        'country': 'uk'
//    })
//);
var km = 0.006383179578579702;
var halfKm = 0.004495196886323735;
var tinySquare =

    // set up legend
    {
        "type": "FeatureCollection",
        "features": [{
            "type": "Feature",
            "properties": {
                "pkm2": 16000
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [0, halfKm],
                        [halfKm, 0],
                        [0, -halfKm],
                        [-halfKm, 0],
                        [0, halfKm]
                    ]
                ]
            }
        }]
    };
/*
scale.on('load', function () {
    scale.addSource('london-pro', {
            "type": "geojson",
            "data": tinySquare
        })
        .addLayer({
            'id': 'extrusions',
            'type': 'fill',
            'source': 'square',
            'paint': {
                'fill-color': '#eee',
                'fill-extrude-base': 0,
                'fill-extrude-height': {
                    "stops": [
                        [0, 10],
                        [1450000, 20000]
                    ],
                    "property": "pkm2",
                    "base": 1
                },
                'fill-opacity': 0.75
            },
            'paint.tilted': {
                'fill-opacity': 0.9
            }
        })
})*/

// set up minimap

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
        .setPopup(popup) // sets a popup on this marker
        //popup.setHTML('<p>' + city.properties.city + '</p>')
        .addTo(minimap);

    document.getElementById('city' + index).setAttribute('onclick', 'jumpToCity(' + index + ')')
})

function jumpToCity(index) {
    var city = greatPlaces.features[index];
    document.querySelector('#slider').value = city.properties.scale;
    map.jumpTo({
        center: city.geometry.coordinates
    });
    setScale(city.properties.scale)
}

// minimap flyTo interactivity
minimap.on('mouseup', function(e) {
    var coords = (minimap.unproject(e.point))
    map.jumpTo({
        center: coords
    })
})

// add geocoder
// map.addControl(
//     new mapboxgl.Geocoder({
//         'container': document.querySelector('.geocoder'),
//         'placeholder': 'Explore any UK city...',
//         'country': 'uk'
//     })
// );

//bind slider to scale-adjusting function
//document.querySelector('#slider')
//    .addEventListener('change', function () {
//        alert(this.value);
//        setScale(parseInt(this.value))
//    })

function GetJson(yourUrl) {
    var Httpreq = new XMLHttpRequest(); // a new request
    Httpreq.open("GET", yourUrl, false);
    Httpreq.send(null);
    return JSON.parse(Httpreq.responseText);
}

//oft-used DOM elements
var tooltip = document.querySelector('#tooltip');
var blockCount = document.querySelector('#blockcount');
var blockDensity = document.querySelector('#blockdensity');
var mapObj = document.querySelector('#map');
var canvas = document.querySelector('.mapboxgl-canvas-container.mapboxgl-interactive');
// app state
var currentBlock; //block id of current block to throttle geocoder
var inspector = 'block'; //inspector mode (block, radius, none)

var emptyGeojson = {
    "type": "FeatureCollection",
    "features": []
};


map.on('load', function() {

    var yields_dots = GetJson('./assets/yields.geojson');
    const coll = [];
    for (let f of yields_dots.features) {
        coll.push(turf.buffer(f, 50, {
            units: 'meters'
        }));
    }
    var yields = turf.featureCollection(coll);
    console.log(yields);
    //set up data sources
    map.addSource('population', {
            'type': 'vector',
            'url': 'mapbox://peterqliu.d0vin3el'
        })
        .addSource('yields', {
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
    map.addLayer({
        'id': 'highlighted_extrusion',
        'type': 'fill',
        'source': 'highlight',
        'paint': {
            'fill-color': 'orange',
            /*'fill-extrude-base': 0,
            'fill-extrude-height': {
                "stops": [
                    [0, 10],
                    [1450000, 20000]
                ],
                "property": "pkm2",
                "base": 1
            },*/
            'fill-opacity': 0
        },
        'paint.tilted': {
            'fill-opacity': 0.5
        }
    }).addLayer({
        'id': 'yields',
        'type': 'fill-extrusion',
        'source': 'yields',
        //'source-layer': 'outgeojson',
        'paint': {
            'fill-extrusion-color': {
                "stops": [
                    [0, '#10476a'],
                    [1, '#10476a'],
                    [2, '#004e66'],
                    [3, '#004e66'],
                    [4, '#004e66'],
                    [5, '#004e66'],
                    [6, '#005069'],
                    [7, '#005069'],
                    [8, '#005069'],
                    [9, '#61d9ed'],
                    [10, '#61d9ed'],
                    [11, '#61d9ed'],
                    [12, '#61d9ed']
                ],
                "property": "yield",
                "base": 0
            },
            'fill-extrusion-base': 0,
            'fill-extrusion-height': {
                "stops": [
                    [1, 100],
                    [2, 200],
                    [3, 300],
                    [4, 400],
                    [5, 500],
                    [6, 600],
                    [7, 700],
                    [8, 800],
                    [9, 900],
                    [10, 1000],
                    [11, 1100],
                    [12, 1200]
                ],
                "property": "yield",
                "base": 0
            },
            //'fill-opacity-transition': {'duration':1000},
            'fill-extrusion-opacity': 1
        },
        'paint.tilted': {
            'fill-extrusion-opacity': 1
        }
    }).addLayer({
        'id': 'yields_dots',
        'type': 'symbol',
        'source': 'yields_dots',
        //'source-layer': 'outgeojson',
        'layout': {
            //"icon-image": "rocket-15",
            //"text-field": "yield",
            "text-font": ["Open Sans Semibold", "Arial Unicode MS Bold"],
            "text-offset": [0, 0.0],
            "text-anchor": "top"
        }
    })

    // sync map to legend

    map
        .on('rotate', function() {
            scale
                .setBearing(map.getBearing())
        })
        .on('zoom', function() {
            var multiplier = map.getZoom() - 12;
            document.querySelector('#people').innerHTML = Math.ceil(4000 * Math.pow(1 / 8,
                multiplier));
        })
        .on('move', function() {
            scale
                .setPitch(map.getPitch() * 0.7) //fudge factor to get it to look right on the lower-left corner
        })

    // map tooltip functionality
    map
        .on('mousemove', function(e) {


            if (inspector === 'block') {
                var features = map.queryRenderedFeatures(e.point, {
                    layers: ["yields"]
                });
                var ft = features[0];

                var showTooltip = ft && ft.properties.p > 0 && e.originalEvent.which === 0;

                if (showTooltip) {
                    mapObj.classList = 'inspector'

                    tooltip.style.transform = 'translateX(' + e.point.x + 'px) translateY(' + e.point
                        .y + 'px)'
                    blockCount.innerHTML = ft.properties.p
                    blockDensity.innerHTML = ft.properties.pkm2;

                    map.getSource('highlight').setData(ft)
                    canvas.style.cursor =
                        'mapboxgl-canvas-container mapboxgl-interactive inspector'

                    if (ft.properties.id !== currentBlock) {
                        var coord = ft.geometry.coordinates[0][0];
                        var queryURL = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + coord +
                            '.json?access_token=pk.eyJ1IjoicGV0ZXJxbGl1IiwiYSI6ImpvZmV0UEEifQ._D4bRmVcGfJvo1wjuOpA1g'
                        mapboxgl.util.getJSON(queryURL, function(err, resp) {
                            currentBlock = ft.properties.id;
                            var address = resp.features[0].address + ' ' + resp.features[0]
                                .text;
                            document.querySelector('#address').innerHTML = 'Near ' +
                                address.replace('undefined ', '');
                        })
                    }
                } else {
                    map.getSource('highlight').setData(emptyGeojson)
                    mapObj.classList = ''
                }
            }

            if (inspector === 'radius') {
                mapObj.classList = 'inspector'

                var pt = map.unproject(e.point)
                //var circle = (drawCircle([pt.lng, pt.lat],0.5));
                //map.getSource('highlight').setData(circle)
                updateBlocks(pt, 0.5)
                tooltip.style.transform = 'translateX(' + e.point.x + 'px) translateY(' + e.point.y +
                    'px)'

            }

        })
        .on('mouseout', function() {
            map.getSource('radiusHighlight').setData(emptyGeojson)
            map.getSource('highlight').setData(emptyGeojson)
        })
    setScale(2500000)
})

map.on('load', function() {
    map.getCanvas().style.cursor = 'pointer';
    // Create a popup, but don't add it to the map yet.
    var popup = new mapboxgl.Popup({
        closeButton: true,
        closeOnClick: true
    });
    map.on('mouseenter', 'yields', function(e) {
        // Change the cursor style as a UI indicator.
        map.getCanvas().style.cursor = 'pointer';

        var coordinates = e.features[0].geometry.coordinates.slice();
        var title = e.features[0].properties.title;
        var imgLink = e.features[0].properties.imgLink;
        var url = e.features[0].properties.url;
        var address = e.features[0].properties.address;
        var YieldVal = parseFloat(e.features[0].properties.yieldValue);
        var zipcode = e.features[0].properties.zipcode;
        if (isNaN(YieldVal)) {
            YieldVal = '';
        } else {
            YieldVal = YieldVal.toFixed(2);
        }

        // Ensure that if the map is zoomed out such that multiple
        // copies of the feature are visible, the popup appears
        // over the copy being pointed to.
        while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
            coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
        }
        popup.setLngLat(coordinates)
            .setHTML('<div style="Width:275px;height:auto;background-color: #fff;"><a style="text-decoration: none;" target="_blank" href="' + url + '"><h3>' + title + '</h3><h4>' + address +
                '</h4><p><b>Yield Value: </b>' + YieldVal + '% </p></a></div>')
            .addTo(map);
        //alert(zipcode);
        getTopTen(zipcode);
        var x = document.getElementById("rightCorner");
        x.style.display = "block";
        var image = document.getElementById("imgCorner");
        image.src = imgLink;
    });

    map.on('mouseleave', 'yields', function(e) {
        //var x = document.getElementById("rightCorner");
        $('.mapboxgl-popup').mouseover(function() {
            popup.addTo(map);
        });
        $('.mapboxgl-popup').mouseleave(function() {
            popup.remove();
        });
        popup.remove();
    });
});
// map tilt functionality
function tilt(eh) {
    var state = !eh ? {
        pitch: 0,
        klass: ['']
    } : {
        pitch: 50,
        klass: ['tilted']
    }

    document.querySelector('#legends').className = 'pin-bottomright scale ' + state.klass[0]
    map
        .easeTo({
            pitch: state.pitch
        })
        .setClasses(state.klass)

    scale.fire('resize');
}

// adjust scale
function setScale(max) {
    max = 3950000 - max;
    document.querySelector('#max').innerHTML = max + '+';
    /*scale
        .setPaintProperty('extrusions', 'fill-extrude-height', {
            "stops": [
                [0, 10],
                [max, 20000]
            ],
            "property": "pkm2",
            "base": 1
        })*/
    /*map.setPaintProperty('fills', 'fill-color', {
            "stops": [
                [0, '#160e23'],
                [max * 0.02, '#00617f'],
                [max * 0.1, '#55e9ff']
            ],
            "property": "yield",
            "base": 1
        })*/
    /*.setPaintProperty('extrusions', 'fill-color', {
        "stops": [
            [0, '#160e23'],
            [max * 0.02, '#00617f'],
            [max * 0.1, '#55e9ff']
        ],
        "property": "pkm2",
        "base": 1
    })
    .setPaintProperty('extrusions', 'fill-extrude-height', {
        "stops": [
            [0, 10],
            [max, 20000]
        ],
        "property": "pkm2",
        "base": 1
    })*/
    /*map.setPaintProperty('highlighted_extrusion', 'fill-extrude-height', {
        "stops": [
            [0, 10],
            [max, 20000]
        ],
        "property": "pkm2",
        "base": 1
    })*/
}

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

function pointBuffer(pt, radius, units, resolution) {
    var ring = []
    var resMultiple = 360 / resolution;
    for (var i = 0; i < resolution; i++) {
        var spoke = turf.destination(pt, radius, i * resMultiple, units);
        ring.push(spoke.geometry.coordinates);
    }
    if ((ring[0][0] !== ring[ring.length - 1][0]) && (ring[0][1] != ring[ring.length - 1][1])) {
        ring.push([ring[0][0], ring[0][1]]);
    }
    return turf.polygon([ring])
}

function drawCircle(center, radius) {
    var pt = turf.point(center);
    var circle = pointBuffer(pt, radius, 'kilometers', 22)
    circle.properties.bbox = turf.bbox(circle)
    circle.properties.pkm2 = 0;
    return circle
}

function drawRadiusCircle() {
    var edgeX = turf.destination(turf.point([lnglat.lng, lnglat.lat]), radius, 90, 'kilometers');
    var pixelRadius = (map.project(edgeX.geometry.coordinates).x - map.project(lnglat).x);

    radiusCircle
        .style({
            width: 2 * pixelRadius + 'px',
            height: 2 * pixelRadius + 'px'
        })
}

function updateBlocks(lnglat, radius) {
    var circle = drawCircle([lnglat.lng, lnglat.lat], radius)

    //calculate extent of circle
    var circleExtent = turf.bbox(circle);
    var nw = map.project([circleExtent[0], circleExtent[1]]);
    var se = map.project([circleExtent[2], circleExtent[3]]);
    nw = [nw.x, nw.y];
    se = [se.x, se.y];

    //get blocks within the circle's extent
    var geometryOutput = map.queryRenderedFeatures([nw, se], {
        layers: ['yields']
    });

    var intersectedBlocks = [];
    var totalPop = 0;

    var ruler = cheapRuler(lnglat.lat, 'meters');
    geometryOutput.forEach(function(poly) {
        var density = poly.properties.pkm2;
        try {

            var poly = turf.polygon(poly.geometry.coordinates);
            poly.properties.bbox = turf.bbox(poly);

            //calculate intersect only if it collides at all
            var intersect = turf.intersect(poly, circle)
            intersect.properties.pkm2 = density;

            //if there is an intersect,
            if (intersect !== undefined) {
                // add intersected geometry to featurecollection
                intersectedBlocks.push(intersect)

                //add intersected population to the total
                var blockPop = ruler.area(poly.geometry.coordinates) * density;
                totalPop += blockPop
            }
        } catch (e) {
            return;
        }
    });

    blockCount.innerHTML = parseInt(totalPop);
    blockDensity.innerHTML = parseInt(totalPop / 0.79);
    map.getSource('radiusHighlight')
        .setData(turf.featureCollection(intersectedBlocks));
}
