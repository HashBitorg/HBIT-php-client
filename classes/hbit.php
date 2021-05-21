<?php
namespace Hashbit\HBIT-php-client;
/* Class to handle HBIT API request */
class HbitApi {

	const GENESIS_TIME = 1619179200;
	const GENESIS_BLOCKID = '16931772122968336255';

	public function __construct($aInput=null){
		$this->timeout = 7;
		$this->bOutputRequest = false;
		$this->initialize();
		if(!empty($aInput))
			$this->aInput=$aInput;
	}

	public function initialize(){ 
		$sNode=defined('NODE')?NODE:'http://127.0.0.1:11120';
		if(substr($sNode,0,4)=='http') {
			list($this->protocol,$this->host,$this->hbit_port) = explode(':',$sNode); //
			$this->host = str_replace('//','',$this->host);
		}
		else {
			list($this->protocol,$this->host) = explode(':',$sNode); //
			if(strpos($this->host,'@')>0)
				list($this->user,$this->host) = explode('@',$this->host); //
		}
	}

	/* Make the request with to the HBIT server
	 * $this->aInput is a key/value pair array of the API query or a string of the type key1=val1&key2=val2...
	 * returns the HBIT response string (json)
	 */ 
	protected function _request(){
		if(empty($this->aInput))
			die('Error: empty or unknown input'."\n");
		if(!empty($this->timeout))
			$sTimeOut = 'timeout '.$this->timeout;
		else
			$sTimeOut = '';
		if(is_array($this->aInput)) {
			$sQuery = str_replace('%2A','',http_build_query($this->aInput));
		} else {
			$sQuery = str_replace('*','',$this->aInput);
		}
			switch($this->protocol) {
				case 'https':
				case 'http':
					$this->sCmd = $sTimeOut.' curl -sk --data "'.$sQuery.'" '.$this->protocol.'://'. $this->host .':'.$this->hbit_port.'/hbit';
					break;
				case 'ssh':
					if(in_array($this->host,array('127.0.0.1','localhost')))
						$this->sCmd = $sTimeOut.' '. PHP_LIB .'commands/bootstrap -http -json "' . $sQuery.'"';
					else
						$this->sCmd = $sTimeOut.' ssh '.$this->user.'@'. $this->host .' '. EXTERNAL_NXT_PHP_LIB .'commands/bootstrap -http -json "' . $sQuery.'"';
					break;
			}
			if($this->bOutputRequest)
				echo "Request : ".$this->sCmd."\n";
			$sJson = exec($this->sCmd);
			return $sJson;
		}

    /* function to be used to obtain request response.
     * It treats the API response object based on $attribute 
     * - if $attribute is an array => returns array of value
     * - if $attribute is a string => returns string value 
     * - if $attribute is empty => returns the full response object 
    */ 
	function getResponse($attribute='') {
		$sResp = $this->_request();
		if(empty($sResp)) {
			$this->oResponse = new \stdClass;
			$this->oResponse->errorCode=1;
			$this->oResponse->errorDescription='HBIT node Timeout';
			return $this->oResponse;
		}
		$this->oResponse = json_decode($sResp);
		if(!empty($attribute)) {
			if(is_array($attribute)) { // we only want specific properties
				foreach($attribute as $property) {
					$aResponse[$property] = $this->oResponse->$property;
					}
					return $aResponse;
			} else {
				return $this->oResponse->$attribute; // we only want one property
			}
		}
		else
			return $this->oResponse; // we want full object output
	}
}
