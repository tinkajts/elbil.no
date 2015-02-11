<?php
//header('Content-Type:text/html; charset=utf-8');
$arrFylker = array(
	'0'=>array("Velg fylke","7"),
	'01'=>array("Østfold","8"),
	'02'=>array("Akershus","9", "Oslo"),
	'03'=>array("Oslo","11"),
	'04'=>array("Hedmark","7"),
	'05'=>array("Oppland","7"),
	'06'=>array("Buskerud","7"),
	'07'=>array("Vestfold","8", "Lardal Vestfold"),
	'08'=>array("Telemark","7"),
	'09'=>array("Aust-Agder","8"),
	'10'=>array("Vest-Agder","8", "Konsmo Vest-Agder"),
	'11'=>array("Rogaland","7", "Strand Rogaland"),
	'12'=>array("Hordaland","7", "Kvam Hordaland"),
	'14'=>array("Sogn og Fjordane","7"),
	'15'=>array("Møre og Romsdal","6", "Molde"),
	'16'=>array("Sør-Trøndelag","6", "Trondheim"),
	'17'=>array("Nord-Trøndelag","6"),
	'18'=>array("Nordland","6"),
	'19'=>array("Troms","6", "Balsfjord Troms"),
	'20'=>array("Finnmark","5")
);

$fylkesId = null;
if(isset($_REQUEST['fylke'])){
	$fylkesId = $_REQUEST['fylke'];
}
//prod
$gKey = "ABQIAAAA06AEl0NrEnwqHtAURte90RR1RG9JamQR3fthoCxd3svhKZvWORQogj1YZd7KD2a3FvO8aKOv8YwA9A";

//print_r($_POST);

$filter_offentlig = "true";
$filter_offentlig_string = "checked='checked'";
if(isset($_REQUEST['adresse']) && !isset($_POST['filter_offentlig'])){
	$filter_offentlig = "false";
	$filter_offentlig_string = "";
}

$filter_avgift = "false";
$filter_avgift_string = "";
if(isset($_POST['filter_avgift'])){
	if($_POST['filter_avgift'] =="on"){
		$filter_avgift = "true";
		$filter_avgift_string = "checked='checked'";
	}
}

$filter_hurtiglading = "false";
$filter_hurtiglading_string = "";
if(isset($_POST['filter_hurtiglading'])){
	if($_POST['filter_avgift'] =="on"){
		$filter_hurtiglading = "true";
		$filter_hurtiglading_string = "checked='checked'";
	}
}

$filter_soknorge = "true";
$filter_soknorge_string = "checked='checked'";
/*
if(isset($_REQUEST['adresse']) && !isset($_POST['filter_soknorge'])){
	$filter_soknorge = "false";
	$filter_soknorge_string = "";
}
*/
$acuracy = 11;
$acuracyString ='';
//Oslo
$lat = "59.91382";
$long = "10.73874";

$adress = "";

$searchedfor = "";
if(isset($_GET['zoom']) && is_numeric($_GET['zoom'])){
	$acuracy = $_GET['zoom'];
	$acuracyString = "?zoom=$acuracy";
}

if(isset($_GET['pos']) && $_GET['pos'][0] == "(" && !isset($_POST['adresse'])){
	//pos = (59.92079,10.74841)

	$posArr = explode(",",substr($_GET['pos'],1,-1));
	if(is_array($posArr) && count($posArr)==2){
		$lat = $posArr[0];
		$long = $posArr[1];
	}
	//print_r($posArr);
}
else{
	if((isset($_REQUEST['adresse']) && $_REQUEST['adresse'] != "Søk etter sted / by") || (isset($fylkesId) && $fylkesId != 0)){
		$searchedfor = "Viser ";
		if($filter_soknorge ==="true"){
			$adress = urlencode("Norge "); // . $_REQUEST['adresse']);

		}

		if(isset($_REQUEST['adresse']) && $_REQUEST['adresse'] != "Søk etter sted / by"){
			$adress .=  urlencode($_REQUEST['adresse']);
			$searchedfor .= htmlspecialchars($_REQUEST['adresse']);
			$fylkesId = null;
		}
		else{
			$searchedfor .= htmlspecialchars($arrFylker[$fylkesId][0]);
			if(isset($arrFylker[$fylkesId][2])){
				$adress .=  urlencode($arrFylker[$fylkesId][2]);
			}
			else{
				$adress .=  urlencode($arrFylker[$fylkesId][0]);
			}
			if(isset($_GET['zoom']) && is_numeric($_GET['zoom'])){
				$acuracy = $_GET['zoom'];
			}
			else{
				$acuracy = $arrFylker[$fylkesId][1];
			}

		}

	}else{
		$adress = urlencode("Norway Oslo");
		//$searchedfor = "Viser Oslo";
		$searchedfor = "";
		if(isset($_GET['zoom']) && is_numeric($_GET['zoom'])){
			$acuracy = $_GET['zoom'];
		}
		else{
			$acuracy = 6;
		}

		//Oslo
		$lat = "59.91382";
		$long = "10.73874";
	}
	//error_log("Søk etter: $adress");
	$geocodeUrl = "http://maps.google.com/maps/geo?q=$adress&output=json&sensor=false&key=$gKey";

	$obj = json_decode(file_get_contents($geocodeUrl));
	if(isset($obj->Placemark)){
		$point = $obj->Placemark[0]->Point->coordinates;

		$lat = $point[1];
		$long = $point[0];
	}
}

$fylkesdrop = "<select name=\"fylke\">\n";
foreach($arrFylker as $fId=>$fylke){
	if($fId==$fylkesId){
		$fylkesdrop .= "<option value=\"$fId\" selected=\"selected\">$fylke[0]</option>\n";
	}
	else{
	 	$fylkesdrop .= "<option value=\"$fId\">$fylke[0]</option>\n";
	}
}
$fylkesdrop .= "</select>\n";

//localhost
//$bilmerker = json_decode(file_get_contents("http://localhost/elbilforhandleroversikt/datasource.php?type=bilmerker"));
$bilmerker = json_decode(file_get_contents("http://elbil.no/forhandlere/datasource.php?type=bilmerker"));

$bilmerkerDrop = "<select id=\"filterbilmerke\" name=\"bilmerke\" onchange=\"setfilter(); return false;\">\n";
$bilmerkerDrop .= "<option value=\"0\">Alle bilmerker</option>\n";
if(is_array($bilmerker)){
	foreach($bilmerker as $merkeId=>$merke){
			$merkeNr = $merkeId+2;
		 	$bilmerkerDrop .= "<option value=\"$merkeNr\">$merke[0]</option>\n";
	}
}
$bilmerkerDrop .= "</select>\n";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<script
	src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=<?php echo $gKey; ?>"
	type="text/javascript"></script>
<script type="text/javascript" src="script/jquery-1.3.2.min.js"></script>
<link rel="stylesheet" type="text/css"
	href="style/forhandleroversikt.css" />
<title>ELBIL.no - Forhandleroversikt</title>
</head>

<body>
<div style="width: 600px">
<form action="forhandleroversikt.php<?php echo $acuracyString;?>" method="post">
	<div style="margin-bottom: 5px; float: left;">
	 	<input title="Søk etter sted / by" onclick="this.value='';" type="text" name="adresse" style="width: 150px; color: #cdcdcd" value="Søk etter sted / by"></input>
		<input type="submit" value="Søk" /><span style="margin-left: 10px; font-weight: bold;"><?php echo $searchedfor;?></span>
	</div>
	<div style="float: right;"><strong style="margin-left: 10px;">Filtrer på bilmerke:</strong> <?php echo $bilmerkerDrop; ?></div>
	<div id="map" style="clear: both; width: 600px; height: 350px"></div>
	<div style="margin-top: 10px; text-align: center;">
		<?php echo $fylkesdrop; ?><input type="submit" value="Søk" /><a style="margin-left: 10px;" href="forhandleroversikt.php" title="Tilbake til Norgeskart">Tilbake til Norgeskart</a>
	</div>
</form>
</div>
<script type="text/javascript">
    var jsondata = null;
	var startLat = '<?php echo $lat; ?>';
	var startLong = '<?php echo $long; ?>';
	var zoomlevel = <?php echo $acuracy; ?>;
	var bounds = null;
	container = document.getElementById("map");

	var map;
	var markers = new Array( );


    var TheMap = new GMap2(container);
    var filterHurtiglading = <?php echo $filter_hurtiglading; ?>;
    var filterAvgiftfri = <?php echo $filter_avgift; ?>;
    var filterOffentlig = <?php echo $filter_offentlig; ?>;

    TheMap.setCenter(new GLatLng(startLat, startLong), zoomlevel);
    TheMap.enableScrollWheelZoom();
    var customUI = TheMap.getDefaultUI();
    // Remove MapType.G_HYBRID_MAP
    customUI.maptypes.hybrid = false;
    TheMap.setUI(customUI);
	UpdateCenter();
	//Hent detaljistliste
	function UpdateCenter(){
		bounds = TheMap.getBounds();
		getChargerJson(bounds);
	}


	//Parse resposen fra Detaljislistesøket
	function parseJsonResponse(data, textStatus, XMLHttpRequest){
		jsondata = data;
		if(TheMap){
			for (var i = 0; i < jQuery(data).length; i++) {
				arrpunkt = data[i].geopunkt.split(',');
				editLat = 1.0*arrpunkt[0];
				editLng = 1.0*arrpunkt[1];

				//bilde = 'cp_img_missing50.jpg" title="Hjelp oss! Send inn bilde til post@nobil.no';

				strMerker = '';
				if(data[i].kjøretøy instanceof Array){
	        		for ( var j in data[i].kjøretøy){
	        			if(j!=0){strMerker += ", " + data[i].kjøretøy[j].navn;}
	        			else{strMerker += data[i].kjøretøy[j].navn;}
	                }
	        	}
				else{
					if(data[i].kjøretøy && data[i].kjøretøy.navn){
						strMerker = data[i].kjøretøy.navn;
					}
					else{
						console.log("Ikke noe data i index " + i);
					}
				}
				markers.push({firmanavn:data[i].firmanavn, adresse: data[i].adresse, kjøretøy:data[i].kjøretøy, poststed:data[i].poststed, posisjon:data[i].geopunkt, latlng:new GLatLng(editLat,editLng),url:data[i].web,      name: '<div style="marging:10px; float:left; vertical-align: top;"><a href="'+ data[i].web +'" title="Se detaljer" target="_blank"><strong>' + data[i].firmanavn + '</strong></a><br/><a href="'+ data[i].web +'" title="Se detaljer" target="_blank">' + data[i].adresse + '</a><br/>Merke(r): ' + strMerker + '</div>'});
			}
		}
		initialize_gmap();
	}

	//Opprett karticon for detaljistene og legg til et klikkevent på dette som viser adresse, webside og merker.
	function create_gmarker( marker, filterHurtiglading, filterAvgiftfri, filterOffentlig )	{
		var tinyIcon = new GIcon();
		tinyIcon.image = "http://maps.gstatic.com/intl/no_no/mapfiles/ms/icons/blue-dot.png";
		//tinyIcon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
		tinyIcon.iconSize = new GSize(30, 30);
		//tinyIcon.shadowSize = new GSize(30, 30);
		tinyIcon.iconAnchor = new GPoint(30, 30);
		tinyIcon.infoWindowAnchor = new GPoint(5, 1);

		// Set up our GMarkerOptions object
		markerOptions = { icon:tinyIcon};

	    var gmarker = new GMarker( marker.latlng, markerOptions );
	    GEvent.addListener( gmarker, 'click', function( ) {
	                gmarker.openInfoWindowHtml( marker.name );
	        }
	    );
	    return gmarker;
	}

	//Legg på detaljistikonlag fra lagringsobjektet som er fylt opp med data fra server via Ajax
	function initialize_gmap( )
	{
	    if ( GBrowserIsCompatible( ) )
	    {
	    	tmpfilterHurtiglading = jQuery("#filter-hurtiglading").is(':checked');
	    	tmpfilterAvgiftfri = jQuery("#filter-avgiftfri").is(':checked');
	    	tmpfilterOffentlig = jQuery("#filter-offentlig").is(':checked');
	    	if( tmpfilterHurtiglading != filterHurtiglading || tmpfilterAvgiftfri != filterAvgiftfri || tmpfilterOffentlig != filterOffentlig){
	    		TheMap.clearOverlays();
	    	}
	        for ( var i in markers ){

				if( tmpfilterHurtiglading != filterHurtiglading || tmpfilterAvgiftfri != filterAvgiftfri || tmpfilterOffentlig != filterOffentlig){
					marker = create_gmarker( markers[ i ], tmpfilterHurtiglading, tmpfilterAvgiftfri, tmpfilterOffentlig );
					if(marker != null){
						TheMap.addOverlay(marker);
					}
				}

				marker = create_gmarker( markers[ i ], tmpfilterHurtiglading, tmpfilterAvgiftfri, tmpfilterOffentlig);
				if(marker != null){
					TheMap.addOverlay(marker);
				}

	        }
	        //filterHurtiglading = tmpfilterHurtiglading;
	        //filterAvgiftfri = tmpfilterAvgiftfri;
	        //filterOffentlig = tmpfilterOffentlig;

	    }
	}

	function getChargerJson(bound){
		var idList = "";
		if(markers.length > 0){
			for(var i=0; i<markers.length; i++){
				if(i>0 & markers[i].id!=''){
					idList +=",";
				}
				if(markers[i].id){
					idList += markers[i].id;
				}
	        }
		}

		jQuery.ajax({
			  type: 'POST',
			  url: 'datasource.php',
			  success: parseJsonResponse,
			  dataType: 'json'
			});
	}

	//Flytt kartet til angitt posisjon fra koordinatpunkt
	function PanToCoord(Lat,Lon){
		var LocalLat,LocalLon,SplitPos;
		Lat=Lat.replace(new RegExp(/\s+/)," ");
		Lon=Lon.replace(new RegExp(/\s+/)," ");
		if((Lat.indexOf(",")> 0)||(Lat.indexOf(" ")>=0)){
			SplitPos=Lat.indexOf(",");
		if(SplitPos<=0)SplitPos=Lat.indexOf(" ");
			LocalLat=Lat.slice(0,SplitPos);
			LocalLon=Lat.slice(SplitPos+1);
		}
		else
		if((Lon.indexOf(",")>=0)||(Lon.indexOf(" ")>=0)){
			SplitPos=Lon.indexOf(",");
		if(SplitPos<=0)SplitPos=Lon.indexOf(" ");
			LocalLon=Lon.slice(0,SplitPos);
			LocalLat=Lon.slice(SplitPos+1);
		}
		else{
			LocalLat=Lat;LocalLon=Lon;
		}
		TheMap.panTo(new GLatLng(LocalLat,LocalLon));
	}

	function setfilter(){
		showThisId = jQuery('#filterbilmerke').val();
		TheMap.clearOverlays();
        for ( var i in markers )
        {
			if(showThisId != 0){
				var show=false;
	        	biltype = markers[i].kjøretøy;

	        	if(biltype instanceof Array){
	        		for ( var j in biltype){
	        			if(biltype[j].id == showThisId){
	        				show = true;
	        			}
	                }
	        	}
	        	else{
	        		if(biltype.id == showThisId){
	        			show = true;
	        		}
	        	}
				if(show){
	        	marker = create_gmarker( markers[ i ], tmpfilterHurtiglading, tmpfilterAvgiftfri, tmpfilterOffentlig );
					if(marker != null){
						TheMap.addOverlay(marker);
					}
				}
			}
			else{
				marker = create_gmarker( markers[ i ], tmpfilterHurtiglading, tmpfilterAvgiftfri, tmpfilterOffentlig );
				if(marker != null){
					TheMap.addOverlay(marker);
				}
			}

        }
		return false;
	}
    </script>
</body>
</html>