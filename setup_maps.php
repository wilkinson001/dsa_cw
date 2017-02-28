<?php
	//include sql.php to run query
	include('sql.php');
	//setup query
	$query = "select b.location_id,b.name as name, coordinates,region_id, description, country  from region a left join location b on (a.location_id=b.location_id)";
	//return query
	$results = call_sql($query);
?>