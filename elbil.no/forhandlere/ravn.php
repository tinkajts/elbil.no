<?php
ini_set('html_errors', true);
include_once 'libs/smarty/Smarty.class.php';
$url = "http://sparc.option.no/?id";
$chargerpointId=null;
$kontakt = null;
$fullscreen = false;
header('Content-Type: text/html; charset=utf-8');

//if(isset($_GET['id'])){
//	$chargerpointId = $_GET['id'];	
//}
$inUse = null;

if(isset($_GET['inuse'])){
	$inUse = $_GET['inuse'];
}


$chargerpointId = 171;

if(isset($_GET['kontakt'])){
	$kontakt = $_GET['kontakt'];
}

if(isset($_GET['mode'])){
	if(trim($_GET['mode'])== "full"){
		$fullscreen = true;
	}
}

if(isset($chargerpointId) && isset($kontakt)){
	//echo "getKontaktStatus<br/>";
	//print_r(getKontaktStatus($url, $chargerpointId, $kontakt));
	$arr = getKontaktStatus($url, $chargerpointId, $kontakt);
	  
}
elseif(isset($chargerpointId) && !isset($kontakt)){
	$smarty = new Smarty;
	// retain current cache lifetime for each specific display call
	$smarty->setCaching(Smarty::CACHING_LIFETIME_SAVED);
	
	// set the cache_lifetime for index.tpl to 1 minutes
	$smarty->setCacheLifetime(60);
	
	//print_r(getChargerPointStatus($url, $chargerpointId));
	
	if($fullscreen){
		if(!$smarty->isCached('fullscreen.tpl')) {
			$arr = getChargerPointStatus($url, $chargerpointId);
			$arr2 = null;
			$arrKontakter = array();
			$antallKontakter = $arr['numberInUse'] + $arr['numberFree'];
			for($i=1; $i<=$antallKontakter;$i++){
				$arr2 = getKontaktStatus($url, $chargerpointId, $i);
				$oppdatert = date_parse($arr2['lastUpdated']);
				$arr2['lastUpdated'] = sprintf("%02d:%02d %02d.%02d.%s", $oppdatert['hour'],$oppdatert['minute'],$oppdatert['day'], $oppdatert['month'], $oppdatert['year']);
				$sistlading = date_parse($arr2['lastChargeFinished']);
				if($sistlading['hour']==0){
					$sistlading = "Aldri brukt";	
				}
				else{
					$sistlading = sprintf("%02d:%02d %02d.%02d.%s", $sistlading['hour'],$sistlading['minute'],$sistlading['day'], $sistlading['month'], $sistlading['year']);
				}
				$arr2['lastChargeFinished'] =$sistlading;
				$arrKontakter[] = $arr2;							
			}
			$smarty->assign("arrKontak", $arrKontakter);
			if(isset($_GET['debug']) && $_GET['debug']=="true")
				echo "Ikke cached";
		}
		$smarty->display("fullscreen.tpl");
	}
	else{
		if(!$smarty->isCached('gadget.tpl')) {		
			$arr = getChargerPointStatus($url, $chargerpointId);
			$antallKontakter = $arr['numberInUse'] + $arr['numberFree'];
			
			$ibruk = $arr['numberInUse'];
			global $inUse;
			$ibruk = $inUse;
			$title = null;
			if($ibruk <=2){
				$img="NYtrafikklysLEDIG.png";
				$title="0-2 punkter i bruk";
			}
			elseif($ibruk >=3 && $ibruk < 5){
				$img="NYtrafikklysHALVFULL.png";
				$title="3-4 punkter i bruk";			
			}
			elseif($ibruk >=5){
				$img="NYtrafikklysFULL.png";
				$title="5-6 punkter i bruk";
			}
			$smarty->assign("img", $img);
			$smarty->assign("title", $title);
			if(isset($_GET['debug']) && $_GET['debug']=="true")
				echo "Ikke cached";
		}
		$smarty->display("gadget.tpl");
				
	}	
}
else{
	echo "Params not set";	
}


function getChargerPointStatus($url, $id='171'){
	$data = file_get_contents($url .= "=$id");
	//echo $data;
	$arrData = array();
	
	if(isset($data)){
		$arrTemp2 = split("#", $data);
		$arrTemp = array(); 
		foreach($arrTemp2 as $val){
			if(isset($val) && trim($val) !=''){
				$arrTemp[] = $val;
			}	
		}
		//print_r($arrTemp);
		if(is_array($arrTemp) && count($arrTemp)==5){
			$arrData['chargerpointId'] = $arrTemp[0];
			$arrData['numberInUse'] =  $arrTemp[1];
			$arrData['numberFree'] =  $arrTemp[2];
			$arrData['lastUpdated'] = $arrTemp[3];
			$arrData['totalUsedKWH'] = $arrTemp[4];
			
		}
	return $arrData;
	}
	return null;
	
}

function getKontaktStatus($url, $id, $kontakt){
	$url .= "=$id&kontakt=$kontakt";
	//echo "$url<br/>";
	$data = file_get_contents($url);
	//echo $data; 
	$arrData = array();
	
	if(isset($data)){
		$arrTemp2 = split("#", $data);
		$arrTemp = array(); 
		foreach($arrTemp2 as $val){
			if(isset($val) && trim($val) !=''){
				$arrTemp[] = $val;
			}	
		}
		if(is_array($arrTemp)){
			$arrData['chargerpointId'] = $arrTemp[0];
			$arrData['inUse'] =  $arrTemp[1];
			$arrData['lastUpdated'] =  $arrTemp[2];
			$arrData['lastChargeFinished'] =  $arrTemp[3];
			$arrData['volts'] =  $arrTemp[4];
			$arrData['wattsUsed'] =  $arrTemp[5];
			$arrData['totalKwhUsed'] =  $arrTemp[6];
			if(isset($arrTemp[7])){
				$arrData['avgTimeUsed'] =  $arrTemp[7];
			}
			else{
				$arrData['avgTimeUsed'] = 0;
			}
			
			if(isset($arrTemp[8])){
				$arrData['avgKWHUsedPrChargeing'] = $arrTemp[8];
			}
			else{
				$arrData['avgKWHUsedPrChargeing'] = 0;
			}
		}
	return $arrData;
	}
	return null;
	
}