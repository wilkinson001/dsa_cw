<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css" />
  </head>
<body>
<header>
<H1>DSA Assignment</H1>
</header>

<?php
	include('sql.php');
	include('keys.php');
	
			echo "<br>";
	$name = $_GET['name'];
	$res=call_sql("select a.location_id, woeid, coordinates, c.*, group_concat(d.grape separator ', ') as grapes from location a inner join vinyard b on (a.location_id=b.location_id) left join wine c on (b.vinyard=c.vinyard) left join grapes d on (c.wine_id=d.wine_id)where a.name =  '$name'");
	if($res[0]['name']==NULL && isset($name)){
		$res=call_sql("select a.location_id, woeid, coordinates, b.*, group_concat(c.language separator ', ') as languages from location a inner join region b on (a.location_id=b.location_id) left join languages c on (b.region_id=c.region_id) where a.name =  '$name'");
		echo '<table style="width:80%;border: 1px solid black;background-color:white;margin:auto;">';
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
		echo '<table style="width:80%;border: 1px solid white;background-color:black;margin:auto;">';
		for($i=0;$i<count($res[0]);$i++){
			echo "<tr>";
			$keys = array_keys($res[0]);
			echo "<td>".$keys[$i]."</td>";
			echo "<td>".$res[0][$keys[$i]]."</td>";
			echo "</tr>";
		}
		echo '</table>';
		echo "<br>";
	}
	
	//weather
	$co=str_replace(" ","",$res[0]['coordinates']);
	$weather_data = json_decode(file_get_contents("http://api.wunderground.com/api/$weather_key/geolookup/q/$co.json"),true);
	//add code to lookup weather from weatherstation
	$city = str_replace(" ","_",$weather_data['location']['city']);
	$country = $weather_data['location']['country'];
	$city_weather = json_decode(file_get_contents("http://api.wunderground.com/api/$weather_key/conditions/q/$country/$city.json"),true);
	$city_forecast = json_decode(file_get_contents("http://api.wunderground.com/api/$weather_key/forecast/q/$country/$city.json"),true);
	
	echo "<br>";
	echo "<H2> Local Weather forecast</H2>";
	echo "<div class='weather'>";
	if(isset($city_forecast['forecast'])){
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['1']['title'].": ";
		echo $city_forecast['forecast']['txt_forecast']['forecastday']['1']['fcttext_metric']."<br><br>";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['2']['title']." :";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['2']['fcttext_metric']."<br><br>";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['3']['title'].": ";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['3']['fcttext_metric']."<br><br>";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['4']['title'].": ";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['4']['fcttext_metric']."<br><br>";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['5']['title'].": ";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['5']['fcttext_metric']."<br><br>";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['6']['title'].": ";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['6']['fcttext_metric']."<br><br>";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['7']['title'].": ";
		echo  $city_forecast['forecast']['txt_forecast']['forecastday']['7']['fcttext_metric']."<br><br>";
		
	} else {
		
		echo "Weather Data Unavailable.";
	}
	echo "</div>";
	require_once './phpFlickr.php' ;
	
	//set access tokens
		echo "<div class='flickr'>";
	$app_key = '6e94c0d005796512dd09ffe6c8f5c552' ;
	$app_secret = '14ce22be7703306b' ;
	$flickr = new phpFlickr( $app_key , $app_secret ) ;


	$option = array(
		'text' => $name ,		// Auto search
		'per_page' => 10 ,			// Number per page
		'extras' => 'url_q,url_c' , 		//Image Size
	) ;
	
	//loop through array and perform get request
	foreach( array( 'text' , 'per_page') as $val )
	{
		if( isset( $_GET[ $val ] ) && $_GET[ $val ] != '' )
		{
			$option[ $val ] = $_GET[ $val ] ;
		}
	}	
	$result = $flickr->photos_search( $option ) ;
	$json = json_encode( $result );
	$obj = json_decode( $json ) ;
	$html = '' ;
	
	//gallery position
	$html .= '<ul style="margin:0 10% 0; padding:0;padding:0; overflow:hidden; text-align:center;">' ;
	
	//output each image
	
	foreach( $obj->photo as $photo )
	{
		
		//Set Thumbnail data
		$t_src = $photo->url_q ;		// Thumbnail Image URL
		$t_width = $photo->width_q ;	// thumbnail width
		$t_height = $photo->height_q ;	// thumbnail height
		$o_src = ( isset($photo->url_c) ) ? $photo->url_c : $photo->url_q ;		//Image URL
		
		
		$html .= '<li style="float:left; margin:1px; padding:0; overflow:hidden; height:112.5px">' ;
		$html .= 	'<a href="' . $o_src . '" target="_blank">' ; //If click image goes to img url
		$html .= 		'<img src="' . $t_src . '" width="' . $t_width . '" height="' . $t_height . '" style="max-width:100%; height:auto">' ;
		$html .= 	'</a>' ;
		$html .= '</li>' ;
	}
	$html .= '</ul>' ;
	echo "</div>";
	
?>
<script>
var weather = <?php echo json_encode($weather_data); ?>;
var city_weather = <?php echo json_encode($city_weather); ?>;
var city_forecast = <?php echo json_encode($city_forecast); ?>;
var sql_res = <?php echo json_encode($res); ?>;
var keys = <?php echo json_encode($keys); ?>;
</script>
<html>
	<head>
		<meta charset="UTF-8">

		<!-- Set Viewport -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<font face="Verdana" size="2">
	</head>
<?php echo $html ?>
</body>
</html>