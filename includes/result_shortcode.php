<?php
function form_result() {
ob_start(); // begin output buffering
//print_r($_POST); echo "<br>";

//include MapBuilder File
include 'class.MapBuilder.php';

//Get File path url
$file = dirname(__FILE__);
$dirlocation= plugin_dir_url($file);

$locationarea = $_POST['locationarea'];

switch ($locationarea) {
	case "radius":
		$postlat = $_POST['latitudeposition'];
		$postlng = $_POST['longitudeposition'];
		$postmile = $_POST['radious_locationarea'];
		$mysearchlocationposition = $_POST[searchlocationposition];
		break;
	case "address":
		$address = $_POST['miles'];
		$prepAddr = str_replace(' ','+',$address);
		$geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
	 	$geooutput = json_decode($geocode);
	 	$postlat = $geooutput->results[0]->geometry->location->lat;
		$postlng = $geooutput->results[0]->geometry->location->lng;
		$postmile = "30";
		$mysearchlocationposition = $geooutput->results[0]->formatted_address;
		break;
	case "zipcode":
		$address = $_POST['miles'];
		$prepAddr = str_replace(' ','+',$address);
		$geocode =file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
	 	$geooutput = json_decode($geocode);
	 	$postlat = $geooutput->results[0]->geometry->location->lat;
		$postlng = $geooutput->results[0]->geometry->location->lng;
		$postmile = "30";
		$mysearchlocationposition = $geooutput->results[0]->formatted_address;
		break;
}

?>
<div id="cnc-main">
	
	<?php
	global $wpdb;
	//echo $postmile;
	//echo $postlat." ".$postlng." ".$_POST[searchlocationposition];
	//SELECT *, ( 3959 * acos( cos( radians(32.5) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians(-96.5) ) + sin( radians(32.5) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM wpcnc1_cncsearch_pcps HAVING distance < 20 AND pcps_last_name LIKE '%William%' ORDER BY distance 

	$querytablehead = $_POST[looking_for];
	switch ($querytablehead) {
		case "physician":
			# code...
			$table = $wpdb->prefix . 'cncsearch_pcps';
			$physician_name = $_POST[physician_name];
			$physician_practice = $_POST[physician_practice];
			$physician_type = $_POST[physician_type];

			if (empty($physician_type) && empty($physician_practice) && empty($physician_name))
			{
				//echo "Case 0";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (!empty($physician_type) && !empty($physician_practice) && !empty($physician_name)) {
				//echo "Case ABC";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_last_name LIKE '%$physician_name%' AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_primary_specialty = '$physician_type' AND pcps_group_name = '$physician_practice' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_last_name LIKE '%$physician_name%' AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_primary_specialty = '$physician_type' AND pcps_group_name = '$physician_practice' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (!empty($physician_type) && empty($physician_practice) && empty($physician_name)) {
				//echo "Case C";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_primary_specialty = '$physician_type' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_primary_specialty = '$physician_type' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (empty($physician_type) && !empty($physician_practice) && empty($physician_name)) {
				//echo "Case B";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_group_name = '$physician_practice' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_group_name = '$physician_practice' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (empty($physician_type) && empty($physician_practice) && !empty($physician_name)) {
				//echo "Case A";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_last_name LIKE '%$physician_name%' AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_last_name LIKE '%$physician_name%' AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (!empty($physician_type) && !empty($physician_practice) && empty($physician_name)) {
				//echo "Case BC";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_group_name = '$physician_practice' AND pcps_primary_specialty = '$physician_type' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_group_name = '$physician_practice' AND pcps_primary_specialty = '$physician_type' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (!empty($physician_type) && empty($physician_practice) && !empty($physician_name)) {
				//echo "Case AC";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_last_name LIKE '%$physician_name%' AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_primary_specialty = '$physician_type' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_last_name LIKE '%$physician_name%' AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_primary_specialty = '$physician_type' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (empty($physician_type) && !empty($physician_practice) && !empty($physician_name)) {
				//echo "Case AB";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_last_name LIKE '%$physician_name%' AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_group_name = '$physician_practice' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( pcps_lat ) ) * cos( radians( pcps_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( pcps_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND pcps_last_name LIKE '%$physician_name%' AND pcps_year = $_POST[year] AND pcps_hmo_ppo = '$_POST[type]' AND pcps_group_name = '$physician_practice' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			
			break;

		case "specialist":
			# code...
			$table = $wpdb->prefix . 'cncsearch_spc';
			$specialist_name = $_POST[specialist_name];
			$specialist_practice = $_POST[specialist_practice];
			$specialist_type = $_POST[specialist_type];

			if (empty($specialist_type) && empty($specialist_practice) && empty($specialist_name))
			{
				//echo "Case 0";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (!empty($specialist_type) && !empty($specialist_practice) && !empty($specialist_name)) {
				//echo "Case ABC";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_last_name LIKE '%$specialist_name%' AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_primary_specialty = '$specialist_type' AND spc_group_name = '$specialist_practice' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_last_name LIKE '%$specialist_name%' AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_primary_specialty = '$specialist_type' AND spc_group_name = '$specialist_practice' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (!empty($specialist_type) && empty($specialist_practice) && empty($specialist_name)) {
				//echo "Case C";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_primary_specialty = '$specialist_type' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_primary_specialty = '$specialist_type' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (empty($specialist_type) && !empty($specialist_practice) && empty($specialist_name)) {
				//echo "Case B";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_group_name = '$specialist_practice' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_group_name = '$specialist_practice' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (empty($specialist_type) && empty($specialist_practice) && !empty($specialist_name)) {
				//echo "Case A";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_last_name LIKE '%$specialist_name%' AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_last_name LIKE '%$specialist_name%' AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (!empty($specialist_type) && !empty($specialist_practice) && empty($specialist_name)) {
				//echo "Case BC";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_group_name = '$specialist_practice' AND spc_primary_specialty = '$specialist_type' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_group_name = '$specialist_practice' AND spc_primary_specialty = '$specialist_type' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (!empty($specialist_type) && empty($specialist_practice) && !empty($specialist_name)) {
				//echo "Case AC";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_last_name LIKE '%$specialist_name%' AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_primary_specialty = '$specialist_type' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_last_name LIKE '%$specialist_name%' AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_primary_specialty = '$specialist_type' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (empty($specialist_type) && !empty($specialist_practice) && !empty($specialist_name)) {
				//echo "Case AB";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_last_name LIKE '%$specialist_name%' AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_group_name = '$specialist_practice' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( spc_lat ) ) * cos( radians( spc_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( spc_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND spc_last_name LIKE '%$specialist_name%' AND spc_year = $_POST[year] AND spc_hmo_ppo = '$_POST[type]' AND spc_group_name = '$specialist_practice' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			break;

    	case "facility":
    		# code...
	    	$table = $wpdb->prefix . 'cncsearch_facilities';
			$facility_name = $_POST[facility_name];
			$facility_type = $_POST[facility_type];

			if (empty($facility_type) && empty($facility_name))
			{
				//echo "Case 0";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( fac_lat ) ) * cos( radians( fac_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( fac_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND fac_year = $_POST[year] AND fac_hmo_ppo = '$_POST[type]' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( fac_lat ) ) * cos( radians( fac_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( fac_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND fac_year = $_POST[year] AND fac_hmo_ppo = '$_POST[type]' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (!empty($facility_type) && empty($facility_name)) {
				//echo "Case A";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( fac_lat ) ) * cos( radians( fac_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( fac_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND fac_year = $_POST[year] AND fac_hmo_ppo = '$_POST[type]' AND fac_primary_specialty = '$facility_type' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( fac_lat ) ) * cos( radians( fac_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( fac_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND fac_year = $_POST[year] AND fac_hmo_ppo = '$_POST[type]' AND fac_facility_type = '$facility_type' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (empty($facility_type) && !empty($facility_name)) {
				//echo "Case B";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( fac_lat ) ) * cos( radians( fac_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( fac_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND fac_last_name LIKE '%$facility_name%' AND fac_year = $_POST[year] AND fac_hmo_ppo = '$_POST[type]' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( fac_lat ) ) * cos( radians( fac_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( fac_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND fac_facility_name LIKE '%$facility_name%' AND fac_year = $_POST[year] AND fac_hmo_ppo = '$_POST[type]' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
			elseif (!empty($facility_type) && !empty($facility_name)) {
				//echo "Case AB";
				//echo "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( fac_lat ) ) * cos( radians( fac_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( fac_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND fac_last_name LIKE '%$facility_name%' AND fac_year = $_POST[year] AND fac_hmo_ppo = '$_POST[type]' AND fac_primary_specialty = '$facility_type' ORDER BY distance";
				$queries = $wpdb->get_results( "SELECT *, ( 3959 * acos( cos( radians($postlat) ) * cos( radians( fac_lat ) ) * cos( radians( fac_lng ) - radians($postlng) ) + sin( radians($postlat) ) * sin( radians( fac_lat ) ) ) ) AS distance FROM $table HAVING distance < $postmile AND fac_facility_name LIKE '%$facility_name%' AND fac_year = $_POST[year] AND fac_hmo_ppo = '$_POST[type]' AND fac_facility_type  = '$facility_type' ORDER BY distance" );	
				$rowCount = $wpdb->num_rows;
			}
		
			break;
	}

	switch ($rowCount) {
    case 0:
        $echoresult = "result";
        break;
    case 1:
        $echoresult = "result";
        break;
    default:
        $echoresult = "results";
        break;
	}

	$displayresulttablehead = $_POST[looking_for];
	switch ($displayresulttablehead ) {
    case "facility":
        echo "We found ".$rowCount." ".$echoresult." for you! and located under ".$postmile." miles from ".$mysearchlocationposition." for ".$_POST[facility_type]."";
		// Create MapBuilder object.
		$map = new MapBuilder();

		// Set map's center position by latitude and longitude coordinates. 
		$map->setCenter($postlat, $postlng);
		//$map->setCenter(48.860181, 2.3249648);

		// Set the default map type.
		$map->setMapTypeId(MapBuilder::MAP_TYPE_ID_ROADMAP);

		// Set width and height of the map.
		$map->setSize(1124, 400);

		// Set default zoom level.
		$map->setZoom(14);

		// Make zoom control compact.
		$map->setZoomControlStyle(MapBuilder::ZOOM_CONTROL_STYLE_SMALL);

		// Define locations and add markers with custom icons and attached info windows.
		foreach ($queries as $query) {
			if($query->fac_street) { $fac_street = $query->fac_street.","; }
			$address = $query->fac_address.", ".$fac_street." ".$query->fac_city.", ".$query->fac_state." ".$query->fac_zipcode;
			$locations[] = array($query->fac_full_name, $query->fac_lat, $query->fac_lng, $address);
		}

		foreach ($locations as $i => $location) {
		    $map->addMarker($location[1], $location[2], array(
		        'title' => $location[0], 
		        'icon' => $dirlocation . '/icon/icon1.png', 
		        'html' => '<b>' . $location[0] . '<br>' . $location[3] . '</b>', 
		        'infoCloseOthers' => true
		    ));
		}

		$map->show();

		echo "<table>";
		echo "<tr><th>Full Name</th><th>Address</th><th>Phone / Fax No </th></tr>";
		foreach ($queries as $query) {
		if($query->fac_street) { $fac_street = $query->fac_street.","; }
		echo "<tr><td>".$query->fac_facility_name."</td><td>".$query->fac_address.", ".$fac_street." ".$query->fac_city.", ".$query->fac_state." ".$query->fac_zipcode."<br>Distance from your location : ".$query->distance." miles</td><td>Tel ".$query->fac_phone."<br>Fax ".$query->fac_fax."</td></tr>";
		}
        break;
    case "specialist":
    	echo "We found ".$rowCount." ".$echoresult." for you! and located under ".$postmile." miles from ".$mysearchlocationposition." for ".$_POST[specialist_type]."";
    	// Create MapBuilder object.
		$map = new MapBuilder();

		// Set map's center position by latitude and longitude coordinates. 
		$map->setCenter($postlat, $postlng);
		//$map->setCenter(48.860181, 2.3249648);

		// Set the default map type.
		$map->setMapTypeId(MapBuilder::MAP_TYPE_ID_ROADMAP);

		// Set width and height of the map.
		$map->setSize(1124, 400);

		// Set default zoom level.
		$map->setZoom(14);

		// Make zoom control compact.
		$map->setZoomControlStyle(MapBuilder::ZOOM_CONTROL_STYLE_SMALL);

		// Define locations and add markers with custom icons and attached info windows.
		foreach ($queries as $query) {
			if($query->spc_street) { $spc_street = $query->spc_street.","; }
			$address = $query->spc_address.", ".$spc_street." ".$query->spc_city.", ".$query->spc_state." ".$query->spc_zipcode;
			$locations[] = array($query->spc_full_name, $query->spc_lat, $query->spc_lng, $address);
		}

		foreach ($locations as $i => $location) {
		    $map->addMarker($location[1], $location[2], array(
		        'title' => $location[0], 
		        'icon' => $dirlocation . '/icon/icon1.png', 
		        'html' => '<b>' . $location[0] . '<br>' . $location[3] . '</b>', 
		        'infoCloseOthers' => true
		    ));
		}

		$map->show();

		echo "<table>";
		echo "<tr><th>Full Name</th><th>Address</th><th>Phone No</th><th>Panel</th><th>Group Name</th></tr>";
		foreach ($queries as $query) {
		if($query->spc_secondary_specialty) { $spc_secondary_specialty = " and ".$query->spc_secondary_specialty; }

		echo "<tr><td><a href='#' class='update_user_button' value='$query->id'>".$query->spc_full_name."</a><br>".$query->spc_degree."</td><td>".$query->spc_address.", ".$spc_street." ".$query->spc_city.", ".$query->spc_state." ".$query->spc_zipcode."<br>Distance from your location : ".$query->distance." miles</td><td>Tel ".$query->spc_phone."</td><td>".$query->spc_panel_status."</td><td>".$query->spc_group_name."</td></tr>";
		?>
		<div class="modal hide fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="Details Information" aria-hidden="true">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                 <h4 class="modal-title">Detail Information of <?php echo $query->spc_full_name; ?></h4>
					</div>
		            <div class="modal-body">
		            	<div class="row">
						  <div class="col-xs-12 col-md-8"><?php echo $query->spc_full_name.", ".$query->spc_degree; ?></div>
						  <div class="col-xs-12 col-md-8">Specialist in <?php echo $query->spc_primary_specialty." ".$spc_secondary_specialty; ?></div>
						  <?php if($query->spc_secondary_languages) { ?> <div class="col-xs-12 col-md-8"><i class="fa fa-language"></i> Also speak in <?php echo $query->spc_secondary_languages; ?></div> <?php } ?>
						  <div class="col-xs-6 col-md-4"><i class="fa fa-map-marker"></i> <?php echo $query->spc_address.", ".$spc_street." ".$query->spc_city.", ".$query->spc_state." ".$query->spc_zipcode; ?></div>
						</div>
		            	<div class="row">
						  <div class="col-xs-6 col-md-4"><i class="fa fa-phone-square"></i> <?php echo $query->spc_phone; ?></div>
						  <div class="col-xs-6 col-md-4"><i class="fa fa-fax"></i> <?php echo $query->spc_fax; ?></div>
						  <div class="col-xs-6 col-md-4"><i class="fa fa-users"></i> <?php echo $query->spc_group_name; ?></div>
						</div>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		            </div>
		        </div>
		    </div>
		</div>
		<?php
		}
		break;
    default:
		echo "We found ".$rowCount." ".$echoresult." for you! and located under ".$postmile." miles from ".$mysearchlocationposition." for ".$_POST[physician_type]."";
		
		// Create MapBuilder object.
		$map = new MapBuilder();

		// Set map's center position by latitude and longitude coordinates. 
		$map->setCenter($postlat, $postlng);

		$map->setMapTypeId(MapBuilder::MAP_TYPE_ID_ROADMAP);
	
		// Set width and height of the map.
		$map->setSize(1124, 400);

		// Set default zoom level.
		$map->setZoom(14);

		// Make zoom control compact.
		$map->setZoomControlStyle(MapBuilder::ZOOM_CONTROL_STYLE_SMALL);

		// Define locations and add markers with custom icons and attached info windows.
		foreach ($queries as $query) {
			if($query->pcps_street) { $pcps_street = $query->pcps_street.","; }
			$address = $query->pcps_address.", ".$pcps_street." ".$query->pcps_city.", ".$query->pcps_state." ".$query->pcps_zipcode;
			$locations[] = array($query->pcps_full_name, $query->pcps_lat, $query->pcps_lng, $address);
		}

		foreach ($locations as $i => $location) {
		    $map->addMarker($location[1], $location[2], array(
		        'title' => $location[0], 
		        'icon' => $dirlocation . 'icon/icon1.png', 
		        'html' => '<b>' . $location[0] . '<br>' . $location[3] . '</b>', 
		        'infoCloseOthers' => true
		    ));
		}
		$map->show();

		echo "<table>";
		echo "<tr><th>Full Name</th><th>Address</th><th>Phone No</th><th>Panel</th><th>Group Name</th></tr>";
		foreach ($queries as $query) {
		if($query->pcps_secondary_specialty) { $pcps_secondary_specialty = " and ".$query->pcps_secondary_specialty; }
	
		echo "<tr><td><a href='#' class='update_user_button' value='$query->id'>".$query->pcps_full_name."</a><br>".$query->pcps_degree."</td><td>".$query->pcps_address.", ".$pcps_street." ".$query->pcps_city.", ".$query->pcps_state." ".$query->pcps_zipcode."<br>Distance from your location : ".$query->distance." miles</td><td>Tel ".$query->pcps_phone."</td><td>".$query->pcps_panel_status."</td><td>".$query->pcps_group_name."</td></tr>";
		echo "<input type='hidden' value='$query->id'>";
		?>
		<div class="modal hide fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="Details Information" aria-hidden="true">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                 <h4 class="modal-title">Detail Information of <?php echo $query->pcps_full_name; ?></h4>
					</div>
		            <div class="modal-body">
		            	<div class="row">
						  <div class="col-xs-12 col-md-8"><?php echo $query->pcps_full_name.", ".$query->pcps_degree; ?></div>
						  <div class="col-xs-12 col-md-8">Specialist in <?php echo $query->pcps_primary_specialty." ".$pcps_secondary_specialty; ?></div>
						  <?php if($query->pcps_secondary_languages) { ?> <div class="col-xs-12 col-md-8"><i class="fa fa-language"></i> Also speak in <?php echo $query->pcps_secondary_languages; ?></div> <?php } ?>
						  <div class="col-xs-6 col-md-4"><i class="fa fa-map-marker"></i> <?php echo $query->pcps_address.", ".$pcps_street." ".$query->pcps_city.", ".$query->pcps_state." ".$query->pcps_zipcode; ?></div>
						</div>
		            	<div class="row">
						  <div class="col-xs-6 col-md-4"><i class="fa fa-phone-square"></i> <?php echo $query->pcps_phone; ?></div>
						  <div class="col-xs-6 col-md-4"><i class="fa fa-fax"></i> <?php echo $query->pcps_fax; ?></div>
						  <div class="col-xs-6 col-md-4"><i class="fa fa-users"></i> <?php echo $query->pcps_group_name; ?></div>
						</div>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		            </div>
		        </div>
		    </div>
		</div>
		<?php
		}
		?>
		<?php
	}
	

	#foreach($_POST as $key => $value){
	# 	echo $key." => ".$value." <br>";
	# }
	#		echo "<div id='cnc_dialog' title='Details Information'>
	#			<p>Name: $query->pcps_full_name</p>
	#			<p>Address: $query->pcps_address, $pcps_street $query->pcps_city, $query->pcps_state $query->pcps_zipcode</p>
	#			<p>Telephone Number: $query->pcps_phone</p>
	#			<p>Fax Number: $query->pcps_fax</p>
	#		  </div>";

	?>
	</table>
</div>
<a href="javascript:void(0);" id="printPage">Print Out the Result</a> 
<input type="button" onclick="window.print()" value="Print Table" /> 

<?php
$output = ob_get_contents(); // end output buffering
ob_end_clean(); // grab the buffer contents and empty the buffer
return $output;
}

add_shortcode('cncsearchresult', 'form_result');

?>