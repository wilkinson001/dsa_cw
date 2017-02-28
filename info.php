<?php
	include('sql.php');
	$name = $_GET["name"];
	$res=call_sql("select a.location_id, woeid, coordinates, c.* from location a left join vinyard b on (a.location_id=b.location_id) left join wine c on (b.vinyard=c.vinyard)where a.name =  '$name'");
	if($res==""){
		echo "no results, contact administrator";
	} else {
		echo '<table style="width:70%;border: 1px solid black;>"';
		for($i=0;$i<count($res[0]);$i++){
			echo "<tr>";
			$keys = array_keys($res[0]);
			echo "<td>".$keys[$i]."</td>";
			echo "<td>".$res[0][$keys[$i]]."</td>";
			echo "</tr>";
		}
		echo '</table>';
	}
?>
<script>
var sql_res = <?php echo json_encode($res); ?>;
var keys = <?php echo json_encode($keys); ?>;
</script>