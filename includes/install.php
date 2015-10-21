<?php
function cncsearch_install()
{
    global $wpdb;

    $table_pcps = $wpdb->prefix . 'cncsearch_pcps';
    $structure_pcps = "CREATE TABLE $table_pcps (
        id INT(9) NOT NULL AUTO_INCREMENT,
        pcps_year INT(4) NOT NULL,
        pcps_hmo_ppo VARCHAR(20) NOT NULL,
        pcps_full_name VARCHAR(255) NOT NULL,
        pcps_first_name VARCHAR(255) NOT NULL,
        pcps_last_name VARCHAR(255) NOT NULL,
        pcps_middle_name VARCHAR(255) NOT NULL,
        pcps_suffix_name VARCHAR(255) NOT NULL,
        pcps_degree VARCHAR(255) NOT NULL,
        pcps_phone VARCHAR(255) NOT NULL,
        pcps_fax VARCHAR(255) NOT NULL,
        pcps_primary_specialty VARCHAR(255) NOT NULL,
        pcps_secondary_specialty VARCHAR(255) NOT NULL,
        pcps_address VARCHAR(255) NOT NULL,
        pcps_street VARCHAR(255) NOT NULL,
        pcps_city VARCHAR(255) NOT NULL,
        pcps_state VARCHAR(255) NOT NULL,
        pcps_zipcode INT(5) NOT NULL,
        pcps_country VARCHAR(255) NOT NULL,
        pcps_panel_status VARCHAR(255) NOT NULL,
        pcps_secondary_languages VARCHAR(255) NOT NULL,
        pcps_group_name VARCHAR(255) NOT NULL,
        pcps_lat VARCHAR(255) NULL,
        pcps_lng VARCHAR(255) NULL,
	UNIQUE KEY id (id)
    ) $charset_collate;";

    $table_specialists = $wpdb->prefix . 'cncsearch_spc';
    $structure_spc = "CREATE TABLE $table_specialists (
        id INT(9) NOT NULL AUTO_INCREMENT,
        spc_year INT(4) NOT NULL,
        spc_hmo_ppo VARCHAR(20) NOT NULL,
        spc_full_name VARCHAR(255) NOT NULL,
        spc_first_name VARCHAR(255) NOT NULL,
        spc_last_name VARCHAR(255) NOT NULL,
        spc_middle_name VARCHAR(255) NULL,
        spc_suffix_name VARCHAR(255) NULL,
        spc_degree VARCHAR(255) NOT NULL,
        spc_pcp VARCHAR(255) NULL,
        spc_phone VARCHAR(255) NOT NULL,
        spc_fax VARCHAR(255) NULL,
        spc_primary_specialty VARCHAR(255) NOT NULL,
        spc_secondary_specialty VARCHAR(255) NULL,
        spc_address VARCHAR(255) NOT NULL,
        spc_street VARCHAR(255) NULL,
        spc_city VARCHAR(255) NOT NULL,
        spc_state VARCHAR(255) NOT NULL,
        spc_zipcode INT(5) NOT NULL,
        spc_panel_status VARCHAR(255) NOT NULL,
        spc_secondary_languages VARCHAR(255) NULL,
        spc_group_name VARCHAR(255) NOT NULL,
        spc_lat VARCHAR(255) NULL,
        spc_lng VARCHAR(255) NULL,
    UNIQUE KEY id (id)
    ) $charset_collate;";

    $table_facilities = $wpdb->prefix . 'cncsearch_facilities';
    $structure_facilities = "CREATE TABLE $table_facilities (
        id INT(9) NOT NULL AUTO_INCREMENT,
        fac_year INT(4) NOT NULL,
        fac_hmo_ppo VARCHAR(20) NOT NULL,
        fac_facility_name VARCHAR(255) NOT NULL,
        fac_facility_type VARCHAR(255) NOT NULL,
        fac_phone VARCHAR(255) NOT NULL,
        fac_fax VARCHAR(255) NOT NULL,
        fac_address VARCHAR(255) NOT NULL,
        fac_street VARCHAR(255) NOT NULL,
        fac_city VARCHAR(255) NOT NULL,
        fac_state VARCHAR(255) NOT NULL,
        fac_zipcode INT(5) NOT NULL,
        fac_lat VARCHAR(255) NULL,
        fac_lng VARCHAR(255) NULL,
    UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $structure_pcps );
    dbDelta( $structure_spc );
    dbDelta( $structure_facilities );
    //$wpdb->query($structure_pcps);

    //load data into table
    //$sql2 = "LOAD DATA LOCAL INFILE 'table_pcps.csv' INTO TABLE $table_pcps FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\\r\\n' IGNORE 1 ROWS";
    //$loaddata = $wpdb->get_results($sql2);
    //if (!$loaddata) {
    //  die('Could not load data from file into table: ' .mysql_error());
    //}

    //$query_pcps = $wpdb->get_results ( "LOAD DATA INFILE 'table_pcps.csv' INTO TABLE $table_pcps FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS" );
}
?>