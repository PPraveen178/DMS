<?php
function responseData($response=array(), $request=array())
{
	$code=$response['code'];
	define('CODE_DESC', array(
		'200'=>"OK",
		'201'=>"Created",
		'202'=>"Accepted",
		'204'=>"No Content",
		'301'=>"Moved Permanently",
		'302'=>"Found",
		'302'=>"See Other",
		'304'=>"Not Modified",
		'307'=>"Temporary Redirect",
		'400'=>"Bad Request",
		'401'=>"Unauthorized",
		'403'=>"Forbidden",
		'404'=>"Not Found",
		'405'=>"Method not allowed",
		'406'=>"Not Acceptable",
		'412'=>"Precondition Failed",
		'415'=>"Unsupported Media Type",
		'500'=>"Internal Server Error",
		'501'=>"Not Implemented"
	));

	if(Common::getWebclient()==true)
	{
		$code=200;
	}

	header( 'HTTP/1.1 '.$code.' '.CODE_DESC[$code] );
	$filepath="log/";
	$fileName="activity-log-".date("Ymd").".log";
	error_log("Request : ".json_encode($request)."\n Response : ".
			json_encode($response)."\n ============= \n", 3,
			$filepath.$fileName);
	if(@$response['block']==1)
	{
		unset($response['block']);
		$response['status']="error";
	}
	if(strtolower(Common::getPlatform())=="android")
	{
		$response['current_build']=ANDROID_BUILD_NO;
	}
	echo json_encode($response);
}
?>