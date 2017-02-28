<?php
if(!function_exists('call_sql')){//check if function exists (was getting errors from includes)
	function call_sql($sql){
		include('db_connection.php');//include connection to the database
		$result = mysqli_query($connection,$sql, MYSQLI_ASSOC);//get results of query
		while($row=$result->fetch_assoc()){//add results to rows of array
			$rows[] = $row;
		}
		$result->close();//close results
		if(!isset($rows)){return $rows[] = "";}
		return $rows;//return results
	}
}
?>