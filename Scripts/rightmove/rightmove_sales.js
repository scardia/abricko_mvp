

const puppeteer = require('puppeteer');
const rp = require('request-promise');
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

const db_coll_name = 'rightmove_sales';

var dbClient = null;



//// Fire !
(async () => {
    try {

	/*
	MongoClient.connect(db_url, function (err, mdb) {
	    assert.equal(null, err);
	    dbClient = mdb;
	    console.log('DB Connection to: ' + db_host + ' at port: ' + db_port + ' to database: ' + db_name + ' is successful');
    	    dbClient.collection(db_coll_name).drop (function(err, res) {
		if (err) {
		    console.log('Error in dropping ' + db_coll_name + ', it might be the first run...');
		} if (res) {
		    console.log(db_coll_name + ' dropped succesfully');
		}

		dbClient.createCollection(db_coll_name, function(err2, res2) {
		    assert.equal(null, err2);
		    console.log('Collection: [' + db_coll_name + '] has been created successfully: ' + res2);
		});
		
	    });

	});
	*/


	// Establish a DB connection first
	const dbHandle = new MongoClient(db_url);
	dbHandle.connect(function (err) {
	    assert.equal(null, err);
	    dbClient = dbHandle.db(db_name);
	    
	    dbClient.collection(db_coll_name).drop(function(err, res){
		if (err) {
		    console.log('Error in dropping ' + db_coll_name + ', it might be the first run...');
		} if (res) {
		    console.log(db_coll_name + ' dropped succesfully');
		}

		dbClient.createCollection(db_coll_name, function(err2, res2) {
		    assert.equal(null, err2);
		    console.log('Collection: [' + db_coll_name + '] has been created successfully: ' + res2);
		});
		
	    });

	});


	// spider init
	const browser = await puppeteer.launch({headless: true});
	const viewPort= {width:1280, height:960};
	const page = await browser.newPage();
	page.setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.4');
	//page.setViewPort(viewPort);
	//await page.setViewport(viewPort);

	const url = 'https://www.rightmove.co.uk/property-for-sale/search.html';

	await page.goto(url);

	await page.waitForSelector('ul.footerlinks');
	
	const alphabeticalLists =  await page.$$eval('ul.footerlinks li a[href]', aTags => aTags.map(a => a.getAttribute("href")));
	//console.log(alphabeticalLists);



	for (alphabeticalIndex in alphabeticalLists) {
	    if (alphabeticalLists[alphabeticalIndex].indexOf('https://www.rightmove.co.uk/uk-property-search') != -1) {
		console.log(alphabeticalLists[alphabeticalIndex]);
		let alphabeticalURL = alphabeticalLists[alphabeticalIndex];
		await alphabeticalListParser(alphabeticalURL, page);
		//break;
	    }
	}	

	


	await browser.close();
	console.log('browser session is killed');
	

	// close the connection to the database
	console.log('closing the connection to the dabase');
	setTimeout(function () {
	    dbClient.close();
	}, 50000);

    } catch (e) {
	console.log('my error', e);
    }

})();



async function alphabeticalListParser (url, page) {
    console.log('entered alphabetical list parser with: ' + url);

    try {
    await page.goto(url, {
                waitUntil: 'networkidle2',
                timeout: 3000000
    });
    }catch(e) {
	console.log('alphabeticalListParser goto exception...' + e)
    }
    
    await page.waitForSelector('#sitefooter');
    const cityURLs = await page.$$eval('div.regionindex ul a[href]', aTags => aTags.map(a => a.getAttribute("href")));

    for (cityIndex in cityURLs) {
	if (cityURLs[cityIndex].indexOf('https://www.rightmove.co.uk/property-for-sale/') != -1) {
	    let cityURL = cityURLs[cityIndex];
	    await cityParser(cityURL, page);
	    //break;
	}
    }
}

async function cityParser(url, page) {
    console.log('entered city parser with url: ' + url);
    var tempCityURLArr = url.split('/');
    var tempLength = tempCityURLArr.length;
    var tempCityWithHtml = tempCityURLArr[tempLength - 1];
    var currentCityName = tempCityWithHtml.split('.')[0];
    console.log('currentCityName: [' + currentCityName + ']');
    
    try {
    await page.goto(url, {
                waitUntil: 'networkidle2',
                timeout: 3000000
    });
    }catch (e) {
	console.log ('cityParser, goto exception... ' + e );
    }

    //await page.waitForSelector('div > div.propertyCard-wrapper > div.propertyCard-content > div.propertyCard-section');
    await page.waitForSelector('div > div.propertyCard-wrapper');

    
    const nextInfo = await page.$eval('#l-container > div.l-propertySearch-paginationWrapper > div > div > div > div.pagination-pageSelect > span:nth-child(4)', span => span.innerText);
    console.log('nextInfo: [' + nextInfo + ']');
    const numberOfPages = parseInt(nextInfo, 10);
    

    const itemURLs = await page.$$eval('div.propertyCard-details a[href]', aTags => aTags.map(a => a.getAttribute("href")));
    for (itemIndex in itemURLs) {
	if (itemURLs[itemIndex].indexOf('/property-for-sale/property-') != -1) {
	    let itemURL = 'https://www.rightmove.co.uk' + itemURLs[itemIndex];
//	    console.log(itemURL);
	    //	    await itemParser(itemURL, page);
	    itemParser2(itemURL, currentCityName);
	}
    }

    if (numberOfPages > 1) {
	//console.log('number of pages... ' + numberOfPages);
	//await page.goto(url);
	//await page.waitForSelector('div > div.propertyCard-wrapper > div.propertyCard-content > div.propertyCard-section');
	//await page.waitForNavigation({ waitUntil: 'networkidle2' });

	await page.waitForSelector('#l-container > div.l-propertySearch-paginationWrapper > div > div > div > div:nth-child(3) > button');
	await page.$eval('#l-container > div.l-propertySearch-paginationWrapper > div > div > div > div:nth-child(3) > button', button => button.click());
	try {
	    await page.waitForNavigation({ waitUntil: 'networkidle2' });
	}catch (e) {
	    console.log('error in page.waitForNavigation cityParser, url: ' + url );
	}
	await nextPageParser(page, 2, numberOfPages, currentCityName);

    }
}




async function nextPageParser(page, current, limit, city) {
    
    console.log('Entered nextPageParser ' + page.mainFrame().url() + ' with currentIndex: ' + current + ', limit: ' + limit);
    //await page.waitForSelector('div > div.propertyCard-wrapper > div.propertyCard-content > div.propertyCard-section');
    await page.waitForSelector('div > div.propertyCard-wrapper');

    var currentURL = page.mainFrame().url();

    if (current <= limit) {
	const itemURLs = await page.$$eval('div.propertyCard-details a[href]', aTags => aTags.map(a => a.getAttribute("href")));
	for (itemIndex in itemURLs) {
	    if (itemURLs[itemIndex].indexOf('/property-for-sale/property-') != -1) {
		let itemURL = 'https://www.rightmove.co.uk' + itemURLs[itemIndex];
//		console.log(itemURL);
		
		//	await itemParser(itemURL, page);

		itemParser2(itemURL, city);
	    }
	}
    }

    // recursively proceed to the next page
    if (current < limit ) {

	//await page.goto(currentURL);
	//await page.waitForSelector('div > div.propertyCard-wrapper > div.propertyCard-content > div.propertyCard-section');
	
	console.log('invoking via current index: ' + (current + 1) + ', limit: ' + limit);
	await page.waitForSelector('#l-container > div.l-propertySearch-paginationWrapper > div > div > div > div:nth-child(3) > button');
	await page.$eval('#l-container > div.l-propertySearch-paginationWrapper > div > div > div > div:nth-child(3) > button', button => button.click());
	try {
	    await page.waitForNavigation({ waitUntil: 'networkidle2'});
	} catch (e) {
	    console.log('error in page.waitForNavigation nextPageParser, url: ' + currentURL );
	}
	await nextPageParser(page, (current + 1), limit, city);
    } else {
	console.log('page: ' + currentURL + ' has finalized');
    }
    
}

async function itemParser(url, page) {

    try {

    await page.goto(url, {
                waitUntil: 'networkidle2',
                timeout: 3000000
            });
    await page.waitForSelector('#description > div > div.right.desc-widgets > div:nth-child(2) > div > a > img');
    
    const srcURL = await page.$eval('#description > div > div.right.desc-widgets > div:nth-child(2) > div > a > img', img => img.getAttribute("src"));

    const latitude = srcURL.split('latitude=')[1].split('&longitude=')[0];
    const longitude = srcURL.split('longitude=')[1].split('&zoomLevel')[0];

    //console.log('sourceURL = ' + srcURL + ', latitude = ' + latitude + ', longitude = ' + longitude);

    var price = await page.$eval('#propertyHeaderPrice > strong', strong => strong.innerText);
    price = price.trim();
    var zip = await page.$eval('#primaryContent > div.row.one-col.property-header > div > div > div.property-header-bedroom-and-price > div > address', address => address.innerText);
    try{
	zip = await page.$eval('div > div > div > ul > li > span.prices-list-address', span => span.innerText);
    }catch(e) {
	//console.log('error in parsing... ' + e);
    }
    var temp = zip.split(',');
    zip = temp[temp.length - 1];
    zip = zip.trim();

    if (zip.indexOf(' ') != -1) {
//	console.log('initially zip was ' + zip);
	zip = zip.split(' ')[0];
    }

    const listingTitle = await page.$eval('#primaryContent > div.row.one-col.property-header > div > div > div.property-header-bedroom-and-price > div > h1', h1 => h1.innerText);
    const roomCount = listingTitle.split('bedroom')[0];

    //console.log('price: ' + price + ', zip: ' + zip + ', roomCount: ' + roomCount);

    const item = {'provider':'rightmove.co.uk', 'url': url, 'latitude': latitude, 'longitude': longitude, 'zip': zip, 'price': price, 'roomCount': roomCount, squareMeters: 'NA', 'currency': '£'};

    dbClient.collection('properties_for_sale').insertOne(item, function(err, res) {
	assert.equal(null, err);
	console.log('item added to properties_for_sale: ' + url + ', status: ' + res);
    });

    } catch(e) {
	console.log('caught an exception for url: ' + url + ', ex: ' + e);
    }
				    
}

// we don't really need the page param
// and we don't need a real browser... 
function itemParser2(url, cityName) {
    var options = {
	uri: url,
	transform: function(body) {
	    return ch.load(body);
	},
	headers: {
	    'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.4'
	},
	json : true
    };

    
    rp(options)
	.then(function ($) {

    	    const priceText = $('#propertyHeaderPrice > strong').text().trim();
	    const priceTextWithCurrency = (priceText.split(' ')[0]).replace(/,/g, ''); 
	    const currency = priceTextWithCurrency.substring(0, 1);
	    const price = parseInt(priceTextWithCurrency.substring(1, 15));

	    

	    const roomCount = parseInt($('#primaryContent > div.row.one-col.property-header > div > div > div.property-header-bedroom-and-price > div > h1').text().split('bedroom')[0]);
	  
	    const imgSrcURL = $('#description > div > div.right.desc-widgets > div:nth-child(2) > div > a > img').attr("src");

    	    const latitude = parseFloat((imgSrcURL == null) ? '0.000000' : imgSrcURL.split('latitude=')[1].split('&longitude=')[0]);
	    const longitude = parseFloat((imgSrcURL == null) ? '0.000000' : imgSrcURL.split('longitude=')[1].split('&zoomLevel')[0]);

    	    if (isNaN(price) || isNaN(roomCount)) {
		console.log("Couldn't obtain numerical values for price: [" + price + "] or roomCount: [" + roomCount + "], early loop out... url: [" + url + "]");
		return;
	    }


	    const salePricePerM2 = price / roomCount;


	    const zipSection = $('#secondaryContent > div:nth-child(5) > div > div > div > ul > li:nth-child(1) > span.prices-list-address').text();
	    const titleZipSection = $('#primaryContent > div.row.one-col.property-header > div > div > div.property-header-bedroom-and-price > div > address').text();
	    const sectionZip = ((zipSection != '') ? zipSection : titleZipSection);
	    const tempZip = sectionZip.split(',');
	    const zip = tempZip[tempZip.length - 1].trim();

	    //const item = {'provider':'rightmove.co.uk', 'url': url, 'latitude': latitude, 'longitude': longitude, 'zip': zip, 'price': price, 'roomCount': roomCount, squareMeters: 'NA', 'currency': '£'};
	    	    const item = {'provider':'rightmove.co.uk','url': url, 'longitude': longitude, 'latitude': latitude,
			  location: {
			      type: "Point",
			      coordinates: [longitude, latitude]
			  },
				  'zip': zip, 'city': cityName, 'price': price, 'roomCount': roomCount, 'salePricePerM2': salePricePerM2,  'currency': currency};

	    //console.log('will insert item as url' + url +  ', latitude: ' + latitude +  ', longitude: ' + longitude +  ', zip: ' +  zip +
		//	', price: ' +  price + ', roomCount: ' + roomCount);

	    
	    
	    dbClient.collection(db_coll_name).insertOne(item, function(err, res) {
		assert.equal(null, err);
		//console.log('item added to properties_for_sale: ' + url + ', status: ' + res);
	    });

    
	    
	})
	.catch(function (err) {
	    console.log('exception on Fetching..... ' + err + ', url = ' + url);
	});
	
}
