<?php
echo "<h2>Update/Generate Directory File</h2>";

global $wpdb;
$table_pcps = $wpdb->prefix . 'cncsearch_pcps';
$query_fqueries = $wpdb->get_results( "SELECT pcps_primary_specialty FROM $table_pcps" );
foreach ($query_fqueries  as $query_fquery) {
		$query_get_types[] = $query_fquery->pcps_primary_specialty;
	}
	$query_get_types = array_unique($query_get_types);
	sort($query_get_types);
	foreach ( $query_get_types as $query_get_type ) 
		{
		echo "<h1>$query_get_type</h1>";
		//echo "SELECT pcps_city FROM $table_pcps WHERE pcps_primary_specialty = '$query_get_type'";
		$query_get_cities = $wpdb->get_results( "SELECT pcps_city FROM $table_pcps WHERE pcps_primary_specialty = '$query_get_type'");
		//print_r($query_get_cities);
		foreach ($query_get_cities as $query_get_city) {
			# code...
			$query_get_new_cities[] = $query_get_city->pcps_city; 
		}
		
		$query_get_new_cities  = array_unique($query_get_new_cities);
		sort($query_get_new_cities);
		//print_r($query_get_new_cities);
		//$query_get_cities = array_unique($query_get_cities);
		//print_r($query_get_cities);
			foreach ($query_get_new_cities as $query_get_new_city) {
				# code...
				$query_get_physicians = $wpdb->get_results( "SELECT * FROM $table_pcps WHERE pcps_primary_specialty = '$query_get_type' AND pcps_city = '$query_get_new_city' ORDER BY pcps_full_name" );
				$rowCount = $wpdb->num_rows;
				if($rowCount > 0){
					echo "<h2>$query_get_new_city</h2>";
				}
				
				foreach ($query_get_physicians as $query_get_physician) {
						//print_r($query_get_physician);
						//need to add sort function for physician name 
													# code...
							echo "<div style='margin-top:5px;'>";
							echo $query_get_physician->pcps_full_name." ".$query_get_physician->pcps_degree."<br>";
							echo $query_get_physician->pcps_address." ".$query_get_physician->pcps_street."<br>";
							echo $query_get_physician->pcps_city.", ".$query_get_physician->pcps_state." ".$query_get_physician->pcps_zipcode."<br>";
							echo $query_get_physician->pcps_phone;
							echo "</div>";
						}
						
						# code...
					}	
			}

		
?>