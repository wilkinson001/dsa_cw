<?php 
include('sql.php');
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
function myMap() {
var contentString1 = '<?php echo $name1; ?><br>some shizzle';
var contentString2 = '<?php echo $name2; ?><br>Some shizzle';
  var myCenter = new google.maps.LatLng(<?php echo $coordinates1[0]; ?>,<?php echo $coordinates1[1]; ?>);
  document.getElementById("map").name = "<?php echo $name1; ?>";
  var mapCanvas = document.getElementById("map");
  var mapOptions = {center: myCenter, zoom: 5};
  var map = new google.maps.Map(mapCanvas, mapOptions);
  var marker = new google.maps.Marker({position:myCenter});
  marker.setMap(map);
  google.maps.event.addListener(marker,'click',function() {
    var infowindow = new google.maps.InfoWindow({
      content: contentString1
    });
  infowindow.open(map,marker);
  });
  
  var myCenter2 = new google.maps.LatLng(<?php echo $coordinates2[0]; ?>,<?php echo $coordinates2[1]; ?>);
  document.getElementById("map").name = "<?php echo $name2; ?>";
  var mapCanvas2 = document.getElementById("map2");
  var mapOptions2 = {center: myCenter2, zoom: 5};
  var map2 = new google.maps.Map(mapCanvas2, mapOptions2);
  var marker2 = new google.maps.Marker({position:myCenter2});
  marker2.setMap(map2);
  google.maps.event.addListener(marker2,'click',function() {
    var infowindow = new google.maps.InfoWindow({
      content: contentString2
    });
  infowindow.open(map2,marker2);
  });
}
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
