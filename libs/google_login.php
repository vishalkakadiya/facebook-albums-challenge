<?php

session_start();

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_Photos');
//Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');

if ( isset( $_GET['token'] ) ) {
	$_SESSION['google_session_token'] = Zend_Gdata_AuthSub::getAuthSubSessionToken( $_GET['token'] );

	$params = "";
	foreach($_GET as $key => $value){
	  $params = $params . $key . '=' . $value . '&';
	}
	//$params = $params . 'ajax=';
	$params = rtrim( $params, '&' );
	header("location:../download_album.php?".$params);
}

?>


<html>
	<head>
		<title>Facebook Album</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script src="resources/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="resources/css/bootstrap.min.css" />
		<style>
		
		#login-div {
			text-align: center;
			margin-top: 15%;
		}
		
		#login-link {
			width: 40%;
			height: 16%;
			text-align: center;
			padding-top: 20px;
			font-size: 40px;
		}
		
		</style>
	</head>
	<body>
		<!-- Navigation -->
		<nav class="navbar navbar-inverse navbar-fixed-top" style="position:relative;" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand active" style="color:#FFF;" href="#">Facebook Album Challenge</a>
				</div>
			</div>
			<!-- /.container -->
		</nav>
		
		
		<div class="container">
			<?php
				//============  Google Move Code ======================//

				function getCurrentUrl() {
					global $_SERVER;

					$php_request_uri = htmlentities(substr($_SERVER['REQUEST_URI'], 0,
					strcspn($_SERVER['REQUEST_URI'], "\n\r")), ENT_QUOTES);

					if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
						$protocol = 'https://';
					} else {
						$protocol = 'http://';
					}
					$host = $_SERVER['HTTP_HOST'];
					if ($_SERVER['SERVER_PORT'] != '' &&
						(($protocol == 'http://' && $_SERVER['SERVER_PORT'] != '80') ||
						($protocol == 'https://' && $_SERVER['SERVER_PORT'] != '443'))) {
							$port = ':' . $_SERVER['SERVER_PORT'];
					} else {
						$port = '';
					}
					return $protocol . $host . $port . $php_request_uri;
				}

				function getAuthSubUrl() {
					$next = getCurrentUrl();
					$scope = 'http://picasaweb.google.com/data';
					$secure = 0;
					$session = 1;
					return Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session);
				}
			?>

			<div id="login-div" class="row">
				<a id="login-link" class="btn btn-danger btn-lg" href="<?php echo getAuthSubUrl();?>">
					Google
				</a>
			</div>

		</div>
	</body>
</html>
