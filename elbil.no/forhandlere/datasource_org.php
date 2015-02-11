<?php
//phpinfo();
header('Content-Type: text/plain; charset=UTF-8');
//header('Content-type: application/json');
//header('Content-type: application/json; charset=UTF-8');

set_include_path('libs');

require_once 'Lite.php';
$email = "tinkajts@gmail.com";
$pass = "J5cyep!trekt55";


$data = null;

// Set a id for this cache
$id = 'forhandleroversikt';

// Set a few options
$options = array(
    'cacheDir' => 'tmp/',
    'lifeTime' => 3600
);

// Create a Cache_Lite object
$Cache_Lite = new Cache_Lite($options);


if(@$_GET['clearcache'] == 'ja'){
  $Cache_Lite->clean();
}
// Test if thereis a valide cache for this id

//echo "data fra cache: " . unserialize($Cache_Lite->get($id));

if ($data = unserialize($Cache_Lite->get($id))) {
	//echo "CACHED!!!<br/>\n";
} else {

	require_once 'getdata.php';
	$doc = new GoogleSpreadsheet($email, $pass);

	$doc->GetSpreadsheetFeed();
	$doc->GetWorksheet();

	$arrDetaljist = $doc->listGetAction("detaljist");
	$arrBilmerke = $doc->listGetAction("bilmerke");
	if(isset($arrDetaljist) && isset($arrBilmerke) && count($arrBilmerke)>0 && count($arrDetaljist) >0){

		$arrBilmerke = fixArrData($arrBilmerke, true, null);
		$arrDetaljist = fixArrData($arrDetaljist, false, $arrBilmerke);
		$data = serialize(array($arrBilmerke, $arrDetaljist));

		$Cache_Lite->save($data, $id);
	}
	else{
		$data = serialize(array("No Data Found"));
		$Cache_Lite->save($data, $id);
	}
}

if(!is_array($data)){
	$data = unserialize($data);
}

//$arrBilmerke = fixArrData($data[0], true, null);
//$arrDetaljist = fixArrData($data[1], false, $arrBilmerke);

//print_r($arrBilmerke);
//print_r($arrDetaljist);
$arrDetaljist = $data[1];
$arrBilmerke = $data[0];
if(isset($_GET['type']) && $_GET['type']=='bilmerker'){
	echo json_encode($arrBilmerke);
}
else{
	echo json_encode($arrDetaljist);
}


function fixArrData($arr, $bil, $arrBilMerke) {
	$rows = array();
	

	foreach($arr as $key=>$val){
		if(!$bil){
			
			$col1 = $val[0];
			$tmpArr = convertDetaljistString2Array($val[1], array("kjøretøy"=>$col1), $arrBilMerke);
		}
		else{
			//var_dump($val);
			//die;
			$col1 = $val[0];
			$tmpArr = convertBilTyperString2Array($val[1], array("kjøretøy"=>$col1));
			$tmpArr = $val;
		}
		$rows[] = $tmpArr;
	}

	return $rows;
}

function convertDetaljistString2Array($str, $col, $arrBil){
	$pos = strpos($str, "geopunkt:");
	if($pos !== false){
		$str1 = substr($str,0, $pos-2);
		$strGeopos = substr($str,$pos);
		$pos = strpos($str1, "kjøretøyid:");

		$strStart = substr($str1, 0, $pos-2);
		$strKjøretøy = substr($str1, $pos);

		$arrStart = explode(', ', $strStart);
		if(is_array($arrStart)){
			$tmpArr = array();
			
			//Vil ikke ha med kjøretøykollonne fra regneark. Skal bygges opp basert på kjøretøyid-ene
			//$vals = array_values($col);
			//$tmpArr[key($col)] = $vals[0];
				
			foreach($arrStart as $item){
				$arrItem = explode(': ', $item);
				if($arrItem[0] == "web" && !strstr($arrItem[1], 'http://') ){
					$arrItem[1] = "http://" . $arrItem[1];
				}
				$tmpArr[$arrItem[0]] = $arrItem[1];
			}
		}
		$arrStart = $tmpArr;


		$arrKjøretøy = explode(': ', $strKjøretøy);
		if(is_array($arrKjøretøy) && count($arrKjøretøy) == 2){
			$tmpArr = array();
			//$arrKjøretøy[1] = "2,3,4,5";
			$arrK = array();
			if(strpos($arrKjøretøy[1], ',')>0 ){
				$tmpArr = explode(',', $arrKjøretøy[1]);
				foreach($tmpArr as $kid){
					//$biltypeNavn = $arrBil[$kid-2][0];
					//var_dump($biltypeNavn);
					//var_dump($arrBil[$kid-2][0]);
					//array_push($arrK, array($kid=>$arrBil[$kid-2][0]));
					//$arrStart[$arrKjøretøy[0]][$kid] = $arrBil[$kid-2][0];
					$arrStart['kjøretøy'][] = array('id' => $kid, 'navn' => $arrBil[$kid-2][0]);
					
				}
				//$arrKjøretøy[1] = $tmpArr;
				//$tmpArr = array();
				//$arrStart[$arrKjøretøy[0]] = $arrK;
			}
			else{
				//$arrStart[$arrKjøretøy[0]][$arrKjøretøy[1]] =  $arrBil[$arrKjøretøy[1]-2][0];
				//error_log(print_r($arrKjøretøy, 1));
				$arrStart['kjøretøy'] = array('id' => $arrKjøretøy[1], 'navn' => $arrBil[$arrKjøretøy[1]-2][0]);
			}
		}


		$tmpArr = array();
		$tmpArr = explode(': ', $strGeopos);

		$arrStart[$tmpArr[0]] = $tmpArr[1];

		return $arrStart;
	}
}

function convertBilTyperString2Array($str, $col){
	$arrStart = explode(', ', $str);
	if(is_array($arrStart)){
		$tmpArr = array();
		$vals = array_values($col);
		$tmpArr[key($col)] = $vals[0];
		foreach($arrStart as $item){
			if(trim($item) != ''){
				$arrItem = explode(': ', $item);
				$tmpArr[$arrItem[0]] = $arrItem[1];
			}
		}
	}
	return $tmpArr;

}
