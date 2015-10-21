<?php

function form_creation( $atts, $content = null ) {
	ob_start(); // begin output buffering

//Need to ativate the second line when on live server
//$str = file_get_contents('http://www.geoplugin.net/json.gp?ip='.$_SERVER['REMOTE_ADDR']);

$titan = TitanFramework::getInstance( 'my-theme' );
$mySavedValue = $titan->getOption( 'cnc_select_rd_page' );
$rd_post = get_post($mySavedValue );
$rd_slug = $rd_post->post_name;

$str = file_get_contents('http://www.geoplugin.net/json.gp?ip=207.235.125.49');
$json = json_decode($str, true); 
$latitude = $json[geoplugin_latitude];
$longitude = $json[geoplugin_longitude];
$searchlocation = $json[geoplugin_city].", ".$json[geoplugin_region];

global $wpdb;

$table_pcps = $wpdb->prefix . 'cncsearch_pcps';
$query_pcps = $wpdb->get_results( "SELECT pcps_primary_specialty FROM $table_pcps" );
foreach ($query_pcps as $query_pcp) {
		$pcps_types[] = $query_pcp->pcps_primary_specialty;
	}
$pcps_types = array_unique($pcps_types);

$table_specialists = $wpdb->prefix . 'cncsearch_spc';
$query_specialists = $wpdb->get_results( "SELECT spc_primary_specialty FROM $table_specialists" );
foreach ($query_specialists as $query_specialist) {
		$specialist_types[] = $query_specialist->spc_primary_specialty;
	}
$specialist_types = array_unique($specialist_types);

$table_facilities = $wpdb->prefix . 'cncsearch_facilities';
$query_facilities = $wpdb->get_results( "SELECT fac_facility_type FROM $table_facilities" );
foreach ($query_facilities as $query_facilitie) {
		$facility_types[] = $query_facilitie->fac_facility_type;
	}
$facility_types = array_unique($facility_types);
//print_r($facility_types);
?>
	
	<div id="cnc-main">

	<div class="cnc_location_details">
		<script language="Javascript">
			document.write("We detect your location is "+geoplugin_city()+", "+geoplugin_region()+", "+geoplugin_countryName());
	 	</script>
	</div>
	
	<form method="POST" action="<?php bloginfo('url');?>/<?php echo $rd_slug; ?>/" id="cnc_search_form">
	<p class="cnc_headtitle">Find A Primary Care Physician<span class="required">*</span></p>
	<select name="year" class="half">
		<option value="2015">2015</option>
		<option value="2016">2016</option>
	</select>
	<select name="type" class="half">
		<option value="HMO">HMO</option>
		<option value="PPO">PPO</option>
	</select>

	<p class="cnc_headtitle">What you looking for?</p>
	<select name="looking_for" id="looking_for" class="eightyfefth">
		<option value="default" selected="selected">Select</option>
		<option value="physician">Find a Provider</option>
		<option value="specialist">A Physician by Specialty</option>
		<option value="facility">A Facility</option>
	</select>
	
	<div id="dPhysician" class="cnc_sub_class">
		<label for="specify" class="cnc_subtitle">Physician Name</label> <input type="text" name="physician_name" placeholder="Physician Last Name"/>
		<label for="specify" class="cnc_subtitle">Practice Name</label> <input type="text" name="physician_practice" placeholder="Practice Name"/>
		<label for="specify" class="cnc_subtitle">Physician Type</label> 
			<select name="physician_type" id="physician_type">
				<option value="" selected="selected">Select the Physician</option>
				<?php
				foreach ( $pcps_types as $pcps_type ) 
				{
					echo '<option value="'.$pcps_type.'">'.$pcps_type.'</option>';
				}
				?>
			</select>
	</div>

	<div id="dSpecialist" class="cnc_sub_class">
		<label for="specify" class="cnc_subtitle">Specialist Name</label> <input type="text" name="specialist_name" placeholder="Specialist Last Name"/>
		<label for="specify" class="cnc_subtitle">Practice Name</label> <input type="text" name="specialist_practice" placeholder="Practice Name"/>
		<label for="specify" class="cnc_subtitle">Specialist Type</label> 
			<select name="specialist_type" id="specialist_type">
				<option value="" selected="selected">Select the Specialist</option>
				<?php
				foreach ( $specialist_types as $specialist_type ) 
				{
					echo '<option value="'.$specialist_type.'">'.$specialist_type.'</option>';
				}
				?>
			</select>
	</div>

	<div id="dFacility" class="cnc_sub_class">
		<label for="specify" class="cnc_subtitle">Facility Type</label> 
			<select name="facility_type" id="facility_type">
				<option value="" selected="selected">Select the Facility Type</option>
				<?php
				foreach ( $facility_types as $facility_type ) 
				{
					echo '<option value="'.$facility_type.'">'.$facility_type.'</option>';
				}
				?>
			</select>
		<label for="specify" class="cnc_subtitle">Facility Name</label> <input type="text" name="facility_name" placeholder="Facility Name"/>
	</div>


	<p class="cnc_headtitle">Where you looking for?<span class="required">*</span></p>
	<select id="search_location_type" name="locationarea" class="twothree">
		<option value="radius">Radius</option>
		<option value="address">Address</option>
		<option value="zipcode">Zip Code</option>
	</select>

	<div id="otherdropdownType" class="sevenfive">
		<select id="radius_value" name="radious_locationarea" class="twothree">
			<option value="5">5</option>
			<option value="10">10</option>
			<option value="15">15</option>
			<option value="20">20</option>
			<option value="25">25</option>
			<option value="30">30</option>
		</select>
	</div>

	<div id="othertextType" class="sevenfive">
		<input type="text" name="miles" placeholder=""/>
	</div>
	
	<input type="hidden" id="mylocationlatitudeposition" name="latitudeposition" value="<?php echo $latitude; ?>" />
	<input type="hidden" id="mylocationlongitudeposition" name="longitudeposition" value="<?php echo $longitude; ?>" />
	<input type="hidden" id="searchlocationposition" name="searchlocationposition" value="<?php echo $searchlocation; ?>" />

	<button type="submit" class="cns_search" >Search!</button>
	</form>
</div>
<?php
	$output = ob_get_contents(); // end output buffering
    ob_end_clean(); // grab the buffer contents and empty the buffer
    return $output;
}
add_shortcode('cncsearch', 'form_creation');

?>