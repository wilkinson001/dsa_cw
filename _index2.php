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
    <title>SDSA Assignment</title>
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

<H1>DSA Assignment</H1>

  <P><B>2. Use and integration of external API's (20 marks):</B></P>

   <P>
   i) Mapping: Use a mapping API to display (at least two) maps showing the Regions you have selected.
   Note that 6(+) Wines should be shown on each map using appropriate icons. 
   MouseOver on the icons should show (some) data drawn from your dataset. 
   MouseClick on icons should load another page showing details (photos, description etc.) of a specific Wine drawn from Wikipedia or other external resource or your own database.
   Deliverable: Maps of Regions/Countries with icons showing Wines realted to the selected Region/Country.(12 marks)
   <P>

   <P>
   ii) Weather: Use a weather API to generate a display of the current and forecast weather for the your selected Region or Country
   (a major city or town in the selected place - or perhaps Vinyards that produces that type of Grape.)
   Deliverable: Display current and forecast weather data. (8 marks)
   </P>




<!--Start of Map One -->
	<H2> Region One </H2>
<div id="map" style="width:90%;height:500px;float:center"></div>

<!--Start of Map Two -->
	<H2> Region Two </H2>
<div id="map2" style="width:90%;height:500px;float:center"></div>

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
	$res=call_sql("select a.vinyard, a.name as name, region_id, coordinates, description, alcohol, dryness_sweetness, producer, bottle_size, vintage,c.name as wname from vinyard a left join location b on (a.location_id=b.location_id) left join wine c on (a.vinyard=c.vinyard)");
	//iterate over each row returned
	for($x=0;$x<count($res);$x++){
		//split coordinates into lat and long
		$co = explode(", ",$res[$x]['coordinates']);
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
		echo "console.log('Clicked on marker$x')";//Change to function for on click
		echo "});";
	}

	
 ?>
 
}
//set above sql query to javascript var for debugging
var sql_res = <?php echo json_encode(
	call_sql("select a.vinyard, a.name as name, region_id, coordinates, description, alcohol, dryness_sweetness, producer, bottle_size, vintage,c.name as wname from vinyard a left join location b on (a.location_id=b.location_id) left join wine c on (a.vinyard=c.vinyard)")
); ?>;
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