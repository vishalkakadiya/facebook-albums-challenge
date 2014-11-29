<?php

	ini_set('max_execution_time', 300);

	require_once('libs/Facebook/GraphObject.php');
	require_once('libs/Facebook/GraphSessionInfo.php');
	require_once('libs/Facebook/Entities/AccessToken.php');
	require_once('libs/Facebook/HttpClients/FacebookHttpable.php');
	require_once('libs/Facebook/HttpClients/FacebookCurl.php');
	require_once('libs/Facebook/HttpClients/FacebookCurlHttpClient.php');
	require_once('libs/Facebook/FacebookSession.php' );
	require_once('libs/Facebook/FacebookRedirectLoginHelper.php' );
	require_once('libs/Facebook/FacebookRequest.php' );
	require_once('libs/Facebook/FacebookResponse.php' );
	require_once('libs/Facebook/FacebookSDKException.php' );
	require_once('libs/Facebook/FacebookRequestException.php' );
	require_once('libs/Facebook/FacebookAuthorizationException.php' );

	
	$fb_app_id = 'your-fb-app-id'; // Goto your Fb app->settings
	$fb_secret_id = 'your-fb-app-secret-id'; // Goto your Fb app->settings

	//fb_login_url is same url which is added in facebook app->settings.
	$fb_login_url = 'your login url or index url where the response is come'; 
	$fb_logout_url = 'your logout url';

	session_start();

?>
