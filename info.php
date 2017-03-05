<?php
	include('sql.php');
	$name = $_GET["name"];
	$res=call_sql("select a.location_id, woeid, coordinates, c.*, group_concat(d.grape separator ', ') as grapes from location a inner join vinyard b on (a.location_id=b.location_id) left join wine c on (b.vinyard=c.vinyard) left join grapes d on (c.wine_id=d.wine_id)where a.name =  '$name'");
	if($res[0][name]==NULL && isset($name)){
		$res=call_sql("select a.location_id, woeid, coordinates, b.*, group_concat(c.language separator ', ') as languages from location a inner join region b on (a.location_id=b.location_id) left join languages c on (b.region_id=c.region_id) where a.name =  '$name'");
		echo '<table style="width:70%;border: 1px solid black;>"';
		for($i=0;$i<count($res[0]);$i++){
			echo "<tr>";
			$keys = array_keys($res[0]);
			echo "<td>".$keys[$i]."</td>";
			echo "<td>".$res[0][$keys[$i]]."</td>";
			echo "</tr>";
		}
		echo '</table>';
	} elseif($res==""){
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
	$co=str_replace(" ","",$res[0]['coordinates']);

	$weather_data = json_decode(file_get_contents("http://api.wunderground.com/api/d3dcc2a04d05c0ca/geolookup/q/$co.json"),true);
	//add code to lookup weather from weatherstation
	$city = str_replace(" ","_",$weather_data['location']['city']);
	$country = $weather_data['location']['country'];
	$city_weather = json_decode(file_get_contents("http://api.wunderground.com/api/d3dcc2a04d05c0ca/conditions/q/$country/$city.json"),true);
	$city_forecast = json_decode(file_get_contents("http://api.wunderground.com/api/d3dcc2a04d05c0ca/forecast/q/$country/$city.json"),true);
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['1']['title'].":<br><br>";
	echo $city_forecast['forecast']['txt_forecast']['forecastday']['1']['fcttext_metric']."<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['2']['title'].":<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['2']['fcttext_metric']."<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['3']['title'].":<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['3']['fcttext_metric']."<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['4']['title'].":<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['4']['fcttext_metric']."<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['5']['title'].":<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['5']['fcttext_metric']."<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['6']['title'].":<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['6']['fcttext_metric']."<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['7']['title'].":<br><br>";
	echo  $city_forecast['forecast']['txt_forecast']['forecastday']['7']['fcttext_metric']."<br><br>";
	
?>
<script>
var weather = <?php echo json_encode($weather_data); ?>;
var city_weather = <?php echo json_encode($city_weather); ?>;
var city_forecast = <?php echo json_encode($city_forecast); ?>;
var sql_res = <?php echo json_encode($res); ?>;
var keys = <?php echo json_encode($keys); ?>;
</script>
