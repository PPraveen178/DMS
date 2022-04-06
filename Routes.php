<?php
/************************

Project : DMS API
Version : v2
Author : Muthu Kumaran
Email : muthu.kumaran@pqc.co.jp

File Name : Routes.php
Description : Requests route organising main handler

************************/

// processRoutes process the URI into routing params
function processRoutes()
{
	$uri=$_SERVER['REQUEST_URI'];
	$uri=str_replace('/DMS-API/v2/', '', $uri);
	$uriArray=explode('/',$uri);
	$response['module']=$uriArray[0];
	$uriArrCount=count($uriArray);

	if($uriArrCount==2)
	{
		$response['data']=$uriArray[1];
	}
	else if($uriArrCount==3)
	{
		$response['submodule']=$uriArray[1];
		$response['data']=$uriArray[2];
	}
	else if($uriArrCount<1&&$uriArrCount>3)
	{
		$response['module']=400;
	}
	$response['req_method'] = $_SERVER["REQUEST_METHOD"];
	$response['input_data'] = json_decode(file_get_contents('php://input'), TRUE);
	$response['access_token'] = getBearerToken();
	$response['workspace'] = extractWorkSpace();
	$response['webclient'] = extractWebClient();
	$response['platform'] = extractPlatform();
	return $response;
}
/** 
 * Get header Authorization
 * */
function getAuthorizationHeader(){
	$headers = null;
	if (isset($_SERVER['Authorization']))
	{
		$headers = trim($_SERVER["Authorization"]);
	}
	else if (isset($_SERVER['HTTP_AUTHORIZATION']))
	{
		//Nginx or fast CGI
		$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	}
	elseif (function_exists('apache_request_headers'))
	{
		$requestHeaders = apache_request_headers();
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		if (isset($requestHeaders['Authorization']))
		{
			$headers = trim($requestHeaders['Authorization']);
		}
	}
	return $headers;
}
/**
* get access token from header
* */
function getBearerToken()
{
	$headers = getAuthorizationHeader();
	// HEADER: Get the access token from the header
	if (!empty($headers))
	{
		if (preg_match('/Bearer\s(\S+)/', $headers, $matches))
		{
			return $matches[1];
		}
	}
	return null;
}
/* */
function extractWorkSpace()
{
	$workspace="";
	if (isset($_SERVER['Workspace']))
	{
		$workspace = trim($_SERVER["Workspace"]);
	}
	else if (isset($_SERVER['HTTP_WORKSPACE']))
	{
		//Nginx or fast CGI
		$workspace = trim($_SERVER["HTTP_WORKSPACE"]);
	}
	elseif (function_exists('apache_request_headers'))
	{
		$requestHeaders = apache_request_headers();
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		if (isset($requestHeaders['workspace']))
		{
			$workspace = trim($requestHeaders['workspace']);
		}
	}
	return $workspace;
}
/* */
function extractWebClient()
{
	$webclient=false;
	if (isset($_SERVER['webclient']))
	{
		$webclient = trim($_SERVER["webclient"]);
	}
	else if (isset($_SERVER['HTTP_WEBCLIENT']))
	{
		//Nginx or fast CGI
		$webclient = trim($_SERVER["HTTP_WEBCLIENT"]);
	}
	elseif (function_exists('apache_request_headers'))
	{
		$requestHeaders = apache_request_headers();
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		if (isset($requestHeaders['webclient']))
		{
			$webclient = trim($requestHeaders['webclient']);
		}
	}
	return $webclient;
}
function extractPlatform()
{
	$platform="";
	if (isset($_SERVER['platform']))
	{
		$platform = trim($_SERVER["platform"]);
	}
	else if (isset($_SERVER['HTTP_PLATFORM']))
	{
		//Nginx or fast CGI
		$platform = trim($_SERVER["HTTP_PLATFORM"]);
	}
	elseif (function_exists('apache_request_headers'))
	{
		$requestHeaders = apache_request_headers();
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		if (isset($requestHeaders['platform']))
		{
			$platform = trim($requestHeaders['platform']);
		}
	}
	return $platform;
}
?>