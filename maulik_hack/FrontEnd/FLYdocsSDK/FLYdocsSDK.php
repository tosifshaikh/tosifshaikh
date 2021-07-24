<?php
/**
 * Copyright 2019 FLYdocs.
 *
 * SDK Docs
 * Supported APIS & Methods
 * /login/CheckLogin	POST
 * /asset/get_listing	GET
 * /listing/get_listing	GET
 * /listing/add			POST
 * /listing/update		POST
 * /listing/delete		GET 
 * /single/get_listing	GET
 * /single/upload		POST
 * /single/update		POST
 *
 * REQUEST Notes 
 * function api and Upload will require following
 * 	- URL
 *	- Params (method/access_token/data)
 *
 * RESPONSE Notes
 * Response will return data in following json object keys
 * 		- success (0 / 1)
 *		- message 
 *		- data (returned data from response)
 */
class FLYdocsSDK{

	public $API_URL;
	public static $CURL_OPTS = array(
	CURLOPT_HEADER=>false,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 60,
	CURLOPT_HTTPHEADER => array('Content-Type: application/json')
	);
	public $format = array("jpg","jpeg");
	public $requiredParams = array("method","access_token","data");
	public $uploadParams = array("filedata","filename","filesize");
	
	public $e;
	
	function __construct()
	{
		$this->API_URL = "http://localhost/maulik_hack/backend/index.php";
		
	}
	function validateParams($params,$upload = 0)
	{
		$s=1;
		foreach($this->requiredParams as $p)
		{
			if(!in_array($p,array_keys($params)))
			{
				$missing[] = $p;
				$s=0;
			}	
		}
		if($upload){
			foreach($this->uploadParams as $p)
			{
				if(!in_array($p,array_keys($params["data"])))
				{
					$missing[] = $p;
					$s=0;
				}	
			}
		}
		if(!$s){
			$msg="mising params:".implode(",",$missing);
			$this->setErrorMessage($msg);
		}
		return $s;
	}
	function init()
	{
		return $ch = curl_init();
	}
	function execute($ch)
	{
		$result = curl_exec($ch);
		if ($result === false && empty($opts[CURLOPT_IPRESOLVE])) {
			$this->setErrorMessage("CONNECTION_FAILED");
			return $this->error();
		}
		curl_close($ch);
		return $result;
	}
	function setErrorMessage($msg)
	{
		$this->e = $msg;
	}
	
	
	function api($urlpart,$params)
	{		
		if(!$this->validateParams($params)){return $this->error();}
		$ch = $this->init();
		$opts = self::$CURL_OPTS;
		
		if($params['method'] == "post"){
			$opts[CURLOPT_POST] = TRUE;
			$opts[CURLOPT_POSTFIELDS] = $params['data'];
			$URL = $this->API_URL.$urlpart;
			$opts[CURLOPT_HTTPHEADER]=array("Accept: application/json");
		}else if ($params['method'] == "get")
		{
			$URL = sprintf("%s?%s", $this->API_URL.$urlpart, http_build_query($params['data']));
		}
		$opts[CURLOPT_URL] = $URL;
		curl_setopt_array($ch, $opts);		
		return $this->execute($ch);
		
	}
	function upload($urlpart,$params)
	{
		
		if(!$this->validateParams($params,1)){return $this->error();}
		$data =  $params['data'];
		$filedata = $data['filedata'];
		$filename = $data['filename'];
		$filesize = $data['filesize'];
		
		$ch = $this->init();
		$opts = self::$CURL_OPTS;
		$URL = $this->API_URL.$urlpart;
		$opts[CURLOPT_URL] = $URL;
		$opts[CURLOPT_POST] = 1;
		$opts[CURLOPT_POSTFIELDS] = array("filedata" => "@$filedata", "filename" => $filename);
		$opts[CURLOPT_HTTPHEADER] = array("Content-Type:multipart/form-data");;
		$opts[CURLOPT_INFILESIZE] = $filesize;
		
		curl_setopt_array($ch, $opts);
		return $this->execute($ch);
		
	}
	function uploadImg($urlpart,$params)
	{
	
		$ch = curl_init();
		$postData =  $params['data'];
		$opts = self::$CURL_OPTS;
		$URL = $this->API_URL.$urlpart;
		$opts[CURLOPT_URL] = $URL;
		$opts[CURLOPT_POST] = 1;
		$opts[CURLOPT_HTTPHEADER] = array("Content-Type:multipart/form-data");
		$opts[CURLOPT_POSTFIELDS] = $postData;
		$opts[CURLOPT_INFILESIZE] = $postData['filesize'];
		$URL = $this->API_URL.$urlpart;
		curl_setopt_array($ch, $opts);
		$result = curl_exec($ch);
		if ($result === false && empty($opts[CURLOPT_IPRESOLVE])) {
			$result = json_encode(array("error"=>"CONNECTION_FAILED"));
		}
		curl_close($ch);
		return $result;
	}
	
	function error()
	{
		$er = array(
			"success"=>0,
			"message"=>$this->e,
			"data"=>array(),
		);
		return json_encode($er);
	}
	
	
}
?>