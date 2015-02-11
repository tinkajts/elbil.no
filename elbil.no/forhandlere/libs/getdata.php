<?php
/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * @see Zend_Gdata
 */
Zend_Loader::loadClass('Zend_Gdata');

/**
 * @see Zend_Gdata_ClientLogin
 */
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

/**
 * @see Zend_Gdata_Spreadsheets
 */
Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');

/**
 * @see Zend_Gdata_App_AuthException
 */
Zend_Loader::loadClass('Zend_Gdata_App_AuthException');

/**
 * @see Zend_Http_Client
 */
Zend_Loader::loadClass('Zend_Http_Client');




//$url = "https://spreadsheets.google.com/ccc?key=0AgCoMh1l19kjdEZ3cTRtNmFoMkFaTHJDUC1KVkRDNFE&hl=no";

//http://spreadsheets.google.com/feeds/list/0AgCoMh1l19kjdEZ3cTRtNmFoMkFaTHJDUC1KVkRDNFE/public/values?alt=json

 //$email = "tinkajts@gmail.com";
 //$pass = "J5cyep!trekt5501";
 
 class GoogleSpreadsheet
{
    /**
     * Constructor
     *
     * @param  string $email
     * @param  string $password
     * @return void
     */
    public function __construct($email, $password)
    {
        try {
          $client = Zend_Gdata_ClientLogin::getHttpClient($email, $password,
                    Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME);
        } catch (Zend_Gdata_App_AuthException $ae) {
          exit("Error: ". $ae->getMessage() ."\nCredentials provided were email: [$email] and password [$password].\n");
        }

        $this->gdClient = new Zend_Gdata_Spreadsheets($client);
        $this->currKey = '';
        $this->currWkshtId = '';
        $this->listFeed = '';
        $this->rowCount = 0;
        $this->columnCount = 0;
        $this->detaljistWkshId = '';
        $this->bilmerkelisteWkshId = '';
    }

    function GetSpreadsheetFeed($id){
    	$feed = $this->gdClient->getSpreadsheetFeed();
    	//$this->printFeed($feed);
    	
    	//$currWkshtId = explode('/', $feed->entries[0]->id->text);
    	foreach($feed->entries as $sheets){
    		$currWkshtId = explode('/', $sheets->id->text);
    		//print_r($currWkshtId);
    		if($currWkshtId[5] == $id){
    			$this->currKey = $currWkshtId[5];
    			return;
    		}
    	}    	
    	//$this->currKey = $currWkshtId[5];
    	if(!isset($this->currKey)){
    		error_log("Fant ikke ønsket regneark med id: $id ");
    		return null;
    	} 
    }
    
	public function GetWorksheet()
    {
    	
        $query = new Zend_Gdata_Spreadsheets_DocumentQuery();
        $query->setSpreadsheetKey($this->currKey);
        $feed = $this->gdClient->getWorksheetFeed($query);
        //print "== Available Worksheets ==\n";
        //$this->printFeed($feed);
        
        //$currWkshtId = explode('/', $feed->entries[$input]->id->text);
        //$this->currWkshtId = $currWkshtId[8];
        
        
        $detaljist = explode('/', $feed->entries[0]->id->text);
		$this->detaljistWkshId = $detaljist[8];

		$bilmerkeliste = explode('/', $feed->entries[1]->id->text);
		$this->bilmerkelisteWkshId = $bilmerkeliste[8];
        

    }
    
    
     /**
     * listGetAction
     *
     * @return void
     */
    public function listGetAction($type)
    {
        $query = new Zend_Gdata_Spreadsheets_ListQuery();
        $query->setSpreadsheetKey($this->currKey);
        if($type=="detaljist"){
        	$query->setWorksheetId($this->detaljistWkshId);
        }
        else{
        	$query->setWorksheetId($this->bilmerkelisteWkshId);	
        }
        
        $this->listFeed = $this->gdClient->getListFeed($query);
        //print "entry id | row-content in column A | column-header: cell-content\n".
        //      "Please note: The 'dump' command on the list feed only dumps data until the first blank row is encountered.\n\n";
		$tmpArr = array();
        foreach($this->listFeed->entries as $entry) {
        	if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) {
                $tmpArr[] = array($entry->title->text, $entry->content->text);        		
            }
        }
        //print_r($this->listFeed);
        //$this->printFeed($this->listFeed);
        return $tmpArr;
    }
    
    
 /**
     * printFeed
     *
     * @param  Zend_Gdata_Gbase_Feed $feed
     * @return void
     */
    public function printFeed($feed)
    {
        $i = 0;
        foreach($feed->entries as $entry) {
            if ($entry instanceof Zend_Gdata_Spreadsheets_CellEntry) {
                print $entry->title->text .' '. $entry->content->text . "\n";
            } else if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) {
                print $i .' '. $entry->title->text .' | '. $entry->content->text . "\n";
            } else {
                print $i .' '. $entry->title->text . "\n";
            }
            $i++;
        }
    }
}