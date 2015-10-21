<?php
echo "<h2>CNC Search - Update Location Latitude and Longitude of Address</h2>";

global $wpdb;

$table = $wpdb->prefix . 'cncsearch_pcps';
$queries = $wpdb->get_results( "SELECT * FROM $table WHERE pcps_lat IS NULL AND pcps_lng IS NULL" );	
$rowCount = $wpdb->num_rows;

if($rowCount=='0')
{
	echo "<h3>We not found any results without Latitude and Longitude from Physician Table!</h3>";
}
$count = 1; 

//$address = '4951 Long Prairie Rd, Ste 120, Flower Mound, TX 75028'; // Google HQ
foreach ($queries as $query) {

		if($query->pcps_street) { $pcps_street = $query->pcps_street.","; }
		$address = $query->pcps_address.", ".$pcps_street." ".$query->pcps_city.", ".$query->pcps_state.", ".$query->pcps_zipcode;
		$prepAddr = str_replace(' ','+',$address);
		$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
	 	//print_r($geocode);
		$output= json_decode($geocode);
	 
		$lat = $output->results[0]->geometry->location->lat;
		$long = $output->results[0]->geometry->location->lng;

		$updatequeries = $wpdb->get_results( "UPDATE $table SET pcps_lat = $lat, pcps_lng = $long WHERE id = $query->id" );
	 
		echo $count.": ".$address.' Lat: '.$lat.' Long: '.$long.'<br>';
	
	$count++;
}


$table = $wpdb->prefix . 'cncsearch_spc';
$queries = $wpdb->get_results( "SELECT * FROM $table WHERE spc_lat IS NULL AND spc_lng IS NULL" );	
$rowCount = $wpdb->num_rows;

if($rowCount=='0')
{
	echo "<h3>We not found any results without Latitude and Longitude from Specilist Table!</h3>";
}
$count = 1; 

//$address = '4951 Long Prairie Rd, Ste 120, Flower Mound, TX 75028'; // Google HQ
foreach ($queries as $query) {

		if($query->spc_street) { $spc_street = $query->spc_street.","; }
		$address = $query->spc_address.", ".$spc_street." ".$query->spc_city.", ".$query->spc_state.", ".$query->spc_zipcode;
		$prepAddr = str_replace(' ','+',$address);
		$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
	 
		$output= json_decode($geocode);
	 
		$lat = $output->results[0]->geometry->location->lat;
		$long = $output->results[0]->geometry->location->lng;

		$updatequeries = $wpdb->get_results( "UPDATE $table SET spc_lat = $lat, spc_lng = $long WHERE id = $query->id" );
	 
		echo $count.": ".$address.' Lat: '.$lat.' Long: '.$long.'<br>';
	
	$count++;
}

$table = $wpdb->prefix . 'cncsearch_facilities';
$queries = $wpdb->get_results( "SELECT * FROM $table WHERE fac_lat IS NULL AND fac_lng IS NULL" );	
$rowCount = $wpdb->num_rows;

if($rowCount=='0')
{
	echo "<h3>We not found any results without Latitude and Longitude from Facilist Table!</h3>";
}
$count = 1; 

//$address = '4951 Long Prairie Rd, Ste 120, Flower Mound, TX 75028'; // Google HQ
foreach ($queries as $query) {

		if($query->fac_street) { $fac_street = $query->fac_street.","; }
		$address = $query->fac_address.", ".$fac_street." ".$query->fac_city.", ".$query->fac_state.", ".$query->fac_zipcode;
		$prepAddr = str_replace(' ','+',$address);
		$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
	 
		$output= json_decode($geocode);
	 
		$lat = $output->results[0]->geometry->location->lat;
		$long = $output->results[0]->geometry->location->lng;

		$updatequeries = $wpdb->get_results( "UPDATE $table SET fac_lat = $lat, fac_lng = $long WHERE id = $query->id" );
	 
		echo $count.": ".$address.' Lat: '.$lat.' Long: '.$long.'<br>';
	
	$count++;
}
////SELECT id, ( 3959 * acos( cos( radians(32) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians(-96) ) + sin( radians(32) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM wpcnc1_cncsearch_pcps HAVING distance < 25 ORDER BY distance LIMIT 0 , 20SELECT *, ( 3959 * acos( cos( radians(32.5) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians(-96.5) ) + sin( radians(32.5) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM wpcnc1_cncsearch_pcps HAVING distance < 25 ORDER BY distance
?>