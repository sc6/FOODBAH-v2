<!DOCTYPE HTML>

<? 
	require_once 'functions/private/connect.php';
	require_once 'functions/private/queries.php';


	$term 			= $_GET['search'];		//the search term (name of vendor)
	$loc 			= $_GET['location'];	//location input; can be zip, city, etc.
	$client_long 	= $_GET['long'];		//client longitude obtained via API
	$client_lat 	= $_GET['lat'];			//client latitude

	$vendor_long 	= 0;
	$vendor_lat 	= 0;
	
	if(!isset($_GET['long'])) $client_long = -118.281775;	//temp: default long
	if(!isset($_GET['lat'])) $client_lat = 34.025653;		//temp: default lat if not set
	
	$distance = -1.0; 	//distance from client to vendor, in miles
	
	$results_by_distance = array();

	class VendorResult {
		public $name = "unknown";
		public $address = "123";
		public $city = "unknown";
		public $zip = 11111;
		public $distance = -1.1;
		
		function __construct($id, $facility, $address, $city, $zip, $distance) {
			$this->id = $id;
			$this->facility = $facility;
			$this->address = $address;
			$this->city = $city;
			$this->zip = $zip;
			$this->distance = $distance;
		}
	}

?>


<HTML>

<head>
<title>Searching: "<?=$term?>" - FOODBAH</title>
<link rel="stylesheet" type="text/css" href="/assets/header.css" />
<link rel="stylesheet" type="text/css" href="/assets/search.css" />
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Montserrat:700' rel='stylesheet' type='text/css'>
</head>

<body>

<!--Header-->
<header>
	<div class="header_content_area">
		<a class="logo" href="/">FOODBAH</a>
		<a class="register" href="#">Register</a>
		<a class="signIn" href="#">Sign in</a>
	</div>
</header>

<!--Location API-->
<script>
function getLocation() {
    if (navigator.geolocation) {
		//refresh using GET input...
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        //print some error message...
		
    }
}
function showPosition(position) {
    var client_latitude = position.coords.latitude;
	var client_longitude = position.coords.longitude;
	<? if(!isset($_GET['long']) || !isset($_GET['lat'])) { ?>
		window.location.href = 'http://foodbah.com/search.php?search=<?=$_GET['search']?>&long='+client_longitude+'&lat='+client_latitude;
	<? } ?>
}
getLocation();
</script>


<div class="spacer_100"></div>

<h1>Your results</h1>

<div class="spacer_100"></div>

<div class="result_container">

	<?
	//gets array of VendorResults
	$result = searchVendorsByName($mysqli, $term, $client_lat, $client_long);

	if(count($result) > 0) printf("There are %d results.<br><br>", count($result));
	else printf("No results; please try again.");
	
	//Prints search results, in order of closest distance
	$i = 0;
	foreach ($result as $vendor_result) {?>
		<div class="restaurant_result_large">
			<img src="http://placehold.it/200x150" />
			<div class="details">
				<a class="vendor_link" href="/vendors/?id=<?=$vendor_result->id?>"><?=$vendor_result->facility?></a><br>
				<?=$vendor_result->address?><br>
				<?=$vendor_result->city?>, CA <?=$vendor_result->zip?><br><br>
				There are 0 posts about this place.<br><br>
				(About <?=$vendor_result->distance?> mi. away)
			</div>
		</div>	
	<? 
		if(++$i > 10) break;	//prints 10 results, maximum
	} ?>
	
</div>





</body>

</HTML>

