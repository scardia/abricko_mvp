
const request =  require('request');
//const rp = require('request-promise');
const ch = require('cheerio');
const assert = require('assert');
const MongoClient = require('mongodb').MongoClient;

//const db_host = '35.178.174.195';
//const db_host = 'ec2-35-178-174-195.eu-west-2.compute.amazonaws.com';
const db_host = 'localhost'
const db_port = '27017';
const db_name = 'abricko';

//// connect with username and password
//const db_username = 'abricko';
//const db_password = encodeURIComponent('|uutg00u}:IIP})FkC5T');
//const db_url = 'mongodb://' + db_username + ':' + db_password + '@' + db_host + ':' + db_port + '/' + db_name;

/// connect without username and password
const db_url = 'mongodb://' + db_host + ':' + db_port;

const db_rightmove_sales_coll_name = 'rightmove_sales';
const db_coll_name = 'zoopla_rentals2'


var dbClient = null;


const zooplaBaseURL = 'https://www.zoopla.co.uk';
const zooplaCitySearchBaseURL = 'https://www.zoopla.co.uk/to-rent/property/';
const zooplaNextPageVariant = '/?identifier=<cityName>&q=<cityName>&radius=0&pn=<nextPageIndex>';

// safety guard, since each page consists of 25 item, it assumes that no city has more than 2500 (even LONDON)
const maxPaginationSize = 100;

/*
  Rationale:
The freaking Zoopla does not provide where the crawling URLs resides
Thus, we need to query all the cities from the sales collection and query the data from zoopla
for each city (no need to make another HTTP call to get the longitude and latitude info) we get the relevant
info directly from the main search results.
*/



async function mainProgram() {
    try {
	const dbHandle = await openDBConnection();
	
	var cities = await getCitiesFromRightMove();
	console.log('Retrieved Cities from Right Move = ' + cities);
	

	for (i = 0; i < cities.length; i++) {
	    var currentIndex = 1;
	    console.log('Retrieving rentals for city: [' + cities[i] + ']');
	    var hasNext = await getRentalsForCity2(cities[i], currentIndex);
	    while(hasNext && currentIndex < maxPaginationSize) {
		hasNext = await getRentalsForCity2(cities[i], ++currentIndex);
	    }
	}
	
	await closeDBConnection(dbHandle);
	
    }catch(e){
	console.log('my exception: ' + e);
    }
}


function openDBConnection() {
    return new Promise ( (resolve, reject) => {

	dbHandle = new MongoClient(db_url);
	dbHandle.connect((err, dbh) => {
	    if (err)
		reject(err);
	    else {
		console.log('established connection to the db');
		dbClient = dbh.db(db_name);
		resolve(dbh);
	    }
	});

    });
}

function closeDBConnection(dbHandle) {
    return new Promise ( (resolve, reject) => {
	try {
	    console.log('closing the connection to the db');
	    dbHandle.close();
	} catch (e) {
	    reject(e);
	}
    });
}

function getCitiesFromRightMove() {
    return new Promise ( (resolve, reject) => {
	dbClient.collection(db_rightmove_sales_coll_name).distinct('city', ((err, cities) => {
	    if (err) {
		console.log('error = ' + err); reject(err);
	    }
	    else {
		resolve(cities);
	    }
	    
	}));

    });												   
}



/// Fire !
mainProgram();


function getRentalsForCity2(cityName, currentIndex) {

    return new Promise ((resolve, reject) => {

	var mainSearchURL = zooplaCitySearchBaseURL + cityName;
	if (currentIndex != 1) {
	    var temp = zooplaNextPageVariant.replace(/<cityName>/g, cityName);
	    temp = temp.replace('<nextPageIndex>', currentIndex);
	    mainSearchURL = zooplaCitySearchBaseURL + cityName + temp;
	}


	var options = {
	    url: mainSearchURL,
	    method: 'GET',
	    headers: {
		'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.4'
	    },
	    json: true
	};

	request(options, (error, response, body) => {

	    if (error) resolve(false);
	    if (response.statusCode != 200) resolve(false);

	    const $ = ch.load(body);


	    var elemCount = $('#content > div.listing-results-utils-view.clearfix.bg-muted > div.split3l > span.listing-results-utils-count').text();
	    if (elemCount == null || elemCount == 'null' || elemCount == '' ) {
		resolve(false);
	    }


	    	    
	    $('#content > ul').first().find('li > div[class=listing-results-wrapper]').each( (index, item) => {
		const roomCount = parseInt($(item).find('div > div.listing-results-right.clearfix > h3 > span.num-icon.num-beds').text());
		
		const priceText = $(item).find('div > div.listing-results-right.clearfix > a').text().trim();
   		const priceTextWithCurrency = (priceText.split('pcm')[0]).replace(/,/g, '');
		const currency = priceTextWithCurrency.substring(0, 1);
		const price = parseInt(priceTextWithCurrency.substring(1, 15));

				
		const url = zooplaBaseURL + $(item).find('div > div.listing-results-right.clearfix > a').attr('href').split('?')[0];

		var shallISkip = false;

    		if (isNaN(price) || isNaN(roomCount)) {
		    console.log("Couldn't obtain numerical values for price: [" + price + "] or roomCount: [" +
			    roomCount + "], early loop out... url: [" + url + "]");
		    shallISkip = true;
		}

		if (!shallISkip) {

		    const yearlyRentPricePerM2 = price * 12 / roomCount;
//		    const salePricePerM2 = price / roomCount;
		    const addressDescription = $(item).find('div > div.listing-results-right.clearfix > span > a').text();
		    const tempArr = addressDescription.split(',');
		
		    const probableZip = (tempArr[tempArr.length - 1]).split(' ');
		    const zip = probableZip[probableZip.length - 1];

		    const dbItem = {'provider':'zoopla.co.uk','url': url, //'longitude': l, 'latitude': latitude,
//			  location: {
//			      type: "Point",
//			      coordinates: [longitude, latitude]
//			  },
				'zip': zip, 'city': cityName, 'price': price, 'roomCount': roomCount,
				 'yearlyRentPricePerM2': yearlyRentPricePerM2,  'currency': currency};


		
		    dbClient.collection(db_coll_name).insertOne(dbItem, function(dberr, res) { });

		} // end of a proper item

		

	    }); // end of for each loop
	    
	    // if there is a next item resolve to true, otherwise go with false
    	    var exists = false;

	    $('#content > div.paginate.bg-muted > a').each ((index, elem) => {
		if ($(elem).text() == 'Next') {
		    exists = true;
		} 
	    });

	    if (exists) {
		console.log('will continue with the next page, mainSearchURL = ' + mainSearchURL + ', currentIndex = ' + currentIndex);
		resolve(true);
	    } else {
		console.log('No further pages exist for: ' + mainSearchURL);
		resolve(false);
	    }
	    

		
	    

	}); /// end of request
	
    }); /// encd of promise

} // end of function 
