<?php 
include('sql.php');
//this is done so query can be changed in background, more of a proof than something actually useful
include('setup_maps.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
	    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>DSA Assignment</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

<body>
<header>
<H1>DSA Assignment</H1>
</header>
<p> Use and integration of external API's (20 marks): <br>

i) Mapping: Use a mapping API to display (at least two) maps showing the Regions you have selected.<br>

Note that 6(+) Wines should be shown on each map using appropriate icons. MouseOver on the icons should show (some) data drawn from your dataset. MouseClick on icons should load another page showing details (photos, description etc.) of a specific Wine drawn from Wikipedia or other external resource or your own database.<br>

Deliverable: Maps of Regions/Countries with icons showing Wines realted to the selected Region/Country.(12 marks)<br>

ii) Weather: Use a weather API to generate a display of the current and forecast weather for the your selected Region or Country (a major city or town in the selected place - or perhaps Vinyards that produces that type of Grape.)<br> <p>


<!--Start of Map One -->
	<H2> Region One - Ardenee</H2>
<div id="map"  style="width:80%;height:500px;margin:auto;float:center"></div>

<!--Start of Map Two -->
	<H2> Region Two - South Africa </H2>
<div id="map2" style="width:80%;height:500px;margin:auto;float:center"></div>

<script>
//get results from setup_maps.php into js var
var php_results = <?php echo json_encode($results); ?>;
//split coordinates to lat and long
var coordinates1 = php_results[0]['coordinates'].split(", ");
var coordinates2 = php_results[1]['coordinates'].split(", ");
var contentString = '<div id="content"><h3>'+ php_results[0]['name']+'</h3> </div>';
var contentString_2 = '<div id="content_2"><h3>'+ php_results[1]['name']+'</h3> </div>';
function myMap() {
	//setup top map
	var myCenter = new google.maps.LatLng(coordinates1[0],coordinates1[1]);
	document.getElementById("map").name = php_results[0]['region_id'];
	var mapCanvas = document.getElementById("map");
	var mapOptions = {center: myCenter, zoom: 7};
	var map = new google.maps.Map(mapCanvas, mapOptions);
	var marker = new google.maps.Marker({position:myCenter});
	marker.setMap(map);
	google.maps.event.addListener(marker,'click',function() {
		  console.log('Clicked on marker');//Change to function for on click
		  window.open(document.URL.replace("_index2.php","info.php?name=")+php_results[0]['name']);
	});
	//Add mouseover and mouseout events
	var infowindow = new google.maps.InfoWindow();
	google.maps.event.addListener(marker,'mouseover', function() {
		infowindow.setContent(contentString);
		infowindow.open(map, marker);
	});
	google.maps.event.addListener(marker,'mouseout', function() {
		setTimeout(function(){infowindow.close();},150);
	});
	//setup bottom map
	var myCenter2 = new google.maps.LatLng(coordinates2[0],coordinates2[1]);
	document.getElementById("map").name = php_results[1]['region_id'];
	var mapCanvas2 = document.getElementById("map2");
	var mapOptions2 = {center: myCenter2, zoom: 6};
	var map2 = new google.maps.Map(mapCanvas2, mapOptions2);
	var marker_2 = new google.maps.Marker({position:myCenter2});
	marker_2.setMap(map2);
	google.maps.event.addListener(marker_2,'click',function() {
		console.log('Clicked on marker_2');//Change to function for on click
		window.open(document.URL.replace("_index2.php","info.php?name=")+php_results[1]['name']);
	});
	//Add mouseover and mouseout events
    var infowindow_2 = new google.maps.InfoWindow();
	google.maps.event.addListener(marker_2,'mouseover', function() {
		infowindow_2.setContent(contentString_2);
		infowindow_2.open(map2, marker_2);
	});
	google.maps.event.addListener(marker_2,'mouseout', function() {
		setTimeout(function(){infowindow_2.close();},150);
	});
 <?php 
	//get data for markers
	$res=call_sql("select a.vinyard, b.name as name, region_id, coordinates, description, alcohol, dryness_sweetness, producer, bottle_size, vintage,c.name as wname from vinyard a left join location b on (a.location_id=b.location_id) left join wine c on (a.vinyard=c.vinyard)");
	//iterate over each row returned
	for($x=0;$x<count($res);$x++){
		//split coordinates into lat and long
		$co = explode(", ",$res[$x]['coordinates']);
		$co2=str_replace(" ","",$res[$x]['coordinates']);
		//get weather data
		$weather_data = json_decode(file_get_contents("http://api.wunderground.com/api/d3dcc2a04d05c0ca/geolookup/q/$co2.json"),true);
		$city = str_replace(" ","_",$weather_data['location']['city']);
		$country = $weather_data['location']['country'];
		$city_weather = json_decode(file_get_contents("http://api.wunderground.com/api/d3dcc2a04d05c0ca/conditions/q/$country/$city.json"),true);
		$city_forecast = json_decode(file_get_contents("http://api.wunderground.com/api/d3dcc2a04d05c0ca/forecast/q/$country/$city.json"),true);
		//echo javascript setting up marker
		echo "var myCenter$x = new google.maps.LatLng($co[0],$co[1]);";
		echo "var marker$x = new google.maps.Marker({position:myCenter$x});";
		//setup variables to use in marker
		//work out which map to plot point on
		if($res[$x]['region_id']==1){
			$map="map";
		} else {
			$map="map2";
		}
		$name = $res[$x]['name'];
		$wname = $res[$x]['wname'];
		$description = $res[$x]['description'];
		$vintage = $res[$x]['vintage'];
		$alcohol = $res[$x]['alcohol'];
		$producer = $res[$x]['producer'];
		$bsize = $res[$x]['bottle_size'];
		$dry_sweet = $res[$x]['dryness_sweetness'];
		$current_rain = $city_weather['current_observation']['precip_today_string'];
		$current_temp = $city_weather['current_observation']['temperature_string'];
		$current_wind = $city_weather['current_observation']['wind_string'];
		$forecast_1 = $city_forecast['forecast']['txt_forecast']['forecastday']['1']['fcttext_metric'];
		$forecast_1_day = $city_forecast['forecast']['txt_forecast']['forecastday']['1']['title'];
		$forecast_2 = $city_forecast['forecast']['txt_forecast']['forecastday']['2']['fcttext_metric'];
		$forecast_2_day = $city_forecast['forecast']['txt_forecast']['forecastday']['2']['title'];
		$forecast_3 = $city_forecast['forecast']['txt_forecast']['forecastday']['3']['fcttext_metric'];
		$forecast_3_day = $city_forecast['forecast']['txt_forecast']['forecastday']['3']['title'];
		$forecast_4 = $city_forecast['forecast']['txt_forecast']['forecastday']['4']['fcttext_metric'];
		$forecast_4_day = $city_forecast['forecast']['txt_forecast']['forecastday']['4']['title'];
		$forecast_5 = $city_forecast['forecast']['txt_forecast']['forecastday']['5']['fcttext_metric'];
		$forecast_5_day = $city_forecast['forecast']['txt_forecast']['forecastday']['5']['title'];
		$forecast_6 = $city_forecast['forecast']['txt_forecast']['forecastday']['6']['fcttext_metric'];
		$forecast_6_day = $city_forecast['forecast']['txt_forecast']['forecastday']['6']['title'];
		$forecast_7 = $city_forecast['forecast']['txt_forecast']['forecastday']['7']['fcttext_metric'];
		$forecast_7_day = $city_forecast['forecast']['txt_forecast']['forecastday']['7']['title'];
		//$forecast_weather = $res[$x]['dryness_sweetness'];
		echo "marker$x.setMap($map);";
		//echo content of infowindow
		echo "var contentString$x =
			'<div id=\"content$x\">'+ 
			'<h3 id = \"title$x\">$name</h3>'+
			'<div id = \"bodycontent$x\">'+
			'<p>$wname<br>'+
			'Producer: $producer<br>'+
			'$description<br>'+
			'Vintage: $vintage<br>'+
			'Alcohol: $alcohol%<br>'+
			'Bottle Size: $bsize ml<br>'+
			'Dryness/Sweetness: $dry_sweet<br>'+
			'Todays Rainfall: $current_rain<br>'+
			'Todays Temperature: $current_temp<br>'+
			'Todays Wind: $current_wind<br>'+
			'$forecast_1_day : $forecast_1<br>'+
			'$forecast_2_day : $forecast_2<br>'+
			'$forecast_3_day : $forecast_3<br>'+
			'$forecast_4_day : $forecast_4<br>'+
			'$forecast_5_day : $forecast_5<br>'+
			'$forecast_6_day : $forecast_6<br>'+
			'</p>'+
			'</div>'+
			'</div>';";
						
		echo "var infowindow$x = new google.maps.InfoWindow();";
		//add mouseover and mouseout event
		echo "google.maps.event.addListener(marker$x,'mouseover', function() {";
		echo "infowindow$x.setContent(contentString$x);";
		echo "infowindow$x.open($map, marker$x);});";
		echo "google.maps.event.addListener(marker$x,'mouseout', function() {";
		echo "setTimeout(function(){infowindow$x.close();},150);});";
		//add onlick event
		echo "google.maps.event.addListener(marker$x,'click',function() {";
		echo "console.log('Clicked on marker$x');";//Change to function for on click
		echo "window.open(document.URL.replace('_index2.php','info.php?name=')+'$name');";
		echo "});";
	}

	
 ?>
 
}
//set above sql query to javascript var for debugging
var sql_res = <?php echo json_encode($res); ?>;
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASXH_VuR10UOH_WmjTcYz7KNSTPIPBI5E&callback=myMap"></script>

<P> 
    Placeholder:http://biostall.com/demos/google-maps-v3-api-codeigniter-library/multiplemaps
	AIzaSyASXH_VuR10UOH_WmjTcYz7KNSTPIPBI5E&callback=initMap">
	
    </P>



<!--
To use this code on your website, get a free API key from Google.
Read more at: http://www.w3schools.com/graphics/google_maps_basic.asp
-->

</body>
</html>