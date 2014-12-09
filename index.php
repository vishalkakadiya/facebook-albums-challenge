<!DOCTYPE html>
<html>
	<head>
		<title>Facebook Album Challenge</title>

		<link rel="shortcut icon" type="image/jpg" href="libs/resources/img/favicon.jpg"/>
		<link rel="stylesheet" type="text/css" href="libs/resources/css/jquery.fancybox.css" />
		<link rel="stylesheet" type="text/css" href="libs/resources/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="libs/resources/css/style.css" />

		<script src="libs/resources/js/jquery-2.1.1.min.js"></script>
		<script src="libs/resources/js/spin.min.js"></script>
		<script src="libs/resources/js/jquery.fancybox.js" type="text/javascript" charset="utf-8"></script>
		<script src="libs/resources/js/bootstrap.min.js"></script>
	</head>
	<body>

	<?php
	
		require_once( 'includes.php' );

		use Facebook\GraphObject;
		use Facebook\GraphSessionInfo;
		use Facebook\Entities\AccessToken;
		use Facebook\HttpClients\FacebookHttpable;
		use Facebook\HttpClients\FacebookCurl;
		use Facebook\HttpClients\FacebookCurlHttpClient;
		use Facebook\FacebookSession;
		use Facebook\FacebookRedirectLoginHelper;
		use Facebook\FacebookRequest;
		use Facebook\FacebookResponse;
		use Facebook\FacebookSDKException;
		use Facebook\FacebookRequestException;
		use Facebook\FacebookAuthorizationException;


		FacebookSession::setDefaultApplication( $fb_app_id, $fb_secret_id );

		// login helper with redirect_uri
		$helper = new FacebookRedirectLoginHelper( $fb_login_url );
		
		// see if a existing session exists
		if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
			// create new session from saved access_token
			$session = new FacebookSession( $_SESSION['fb_token'] );

			try {
				if ( !$session->validate() ) {
				  $session = null;
				}
			} catch ( Exception $e ) {
				// catch any exceptions
				$session = null;
			}
		}  
		 
		if ( !isset( $session ) || $session === null ) {
			try {
				$session = $helper->getSessionFromRedirect();
			} catch( FacebookRequestException $ex ) {
				print_r( $ex );
			} catch( Exception $ex ) {
				print_r( $ex );
			}
		}

		$google_session_token = "";

		// see if we have a session
		if ( isset( $session ) ) {

			require_once( 'libs/resize_image.php' );

			$_SESSION['fb_login_session'] = $session;
			$_SESSION['fb_token'] = $session->getToken();

			// create a session using saved token or the new one we generated at login
			//$session = new FacebookSession( $session->getToken() );
			
			$request_user_details = new FacebookRequest( $session, 'GET', '/me?fields=id,name' );
			$response_user_details = $request_user_details->execute();
			$user_details = $response_user_details->getGraphObject()->asArray();
			
			$user_id = $user_details['id'];
			$user_name = $user_details['name'];
			
			
			if ( isset( $_SESSION['google_session_token'] ) ) {
				$google_session_token = $_SESSION['google_session_token'];
			}
?>

				<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
					<div class="container">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div id="nav-menu" class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-menu">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="#" id="username">
								<img src="<?php echo 'https://graph.facebook.com/'.$user_id.'/picture';?>" id="user_photo" class="img-circle" />
								<span style="margin-left: 5px;"><?php echo $user_name;?></span>
							</a>
						</div>

						<!-- Collect the nav links, forms, and other content for toggling -->
						<div id="navbar-collapse-menu" class="collapse navbar-collapse menu-links">
							<ul class="nav navbar-nav pull-right">
								<li>
									<a href="#" id="download-all-albums" class="center">
										<span class="btn btn-primary col-md-12">
											Download All
										</span>
									</a>
								</li>
								<li>
									<a href="#" id="download-selected-albums" class="center">
										<span class="btn btn-warning col-md-12">
											Download Selected
										</span>
									</a>
								</li>
								<li>
									<a href="#" id="move_all" class="center">
										<span class="btn btn-success col-md-12">
											Move All
										</span>
									</a>
								</li>
								<li>
									<a href="#" id="move-selected-albums" class="center">
										<span class="btn btn-info col-md-12">
											Move Selected
										</span>
									</a>
								</li>
								<li>
									<a href="<?php echo $helper->getLogoutUrl( $session, $fb_logout_url );?>" class="center">
										<span class="btn btn-danger col-md-12">
											Logout
										</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</nav>

				<div class="container" id="main-div">
					<div class="row">
						<span id="loader" class="navbar-fixed-top"></span>

						<div class="modal fade" id="download-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">
											<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
										</button>
										<h4 class="modal-title" id="myModalLabel">Albums Report</h4>
									</div>
									<div class="modal-body" id="display-response">
										<!-- Response is displayed over here -->
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<!--<ul id="thumbnails">-->

						<?php

							// graph api request for user data
							$request_albums = new FacebookRequest( $session, 'GET', '/me/albums?fields=id,cover_photo,from,name' );
							$response_albums = $request_albums->execute();
							
							// get response
							$albums = $response_albums->getGraphObject()->asArray();

							if ( !empty( $albums ) ) {
								foreach ( $albums['data'] as $album ) {
									$album = (array) $album;
									
									$request_album_photos = new FacebookRequest( $session,'GET', '/'.$album['id'].'/photos?fields=source' );
									$response_album_photos = $request_album_photos->execute();			
									$album_photos = $response_album_photos->getGraphObject()->asArray();
									
									if ( !empty( $album_photos ) ) {
										foreach ( $album_photos['data'] as $album_photo ) {
											$album_photo = (array) $album_photo;

											if ( $album['cover_photo'] == $album_photo['id'] ) {
												$album_cover_photo = $album_photo['source'];
												$album_resized_cover_photo = 'libs/resources/albums/covers/'.$album['cover_photo'].'_350X420.jpg';

												if ( !file_exists( $album_resized_cover_photo ) )
													smart_resize_image($album_cover_photo , null, 350 , 420 , false , $album_resized_cover_photo , false , false , 100 );
													

										?>
												<div class="col-sm-6 col-md-4">
													<div class="thumbnail no-border center">
														<h4><?php echo $album['name'].' ('.count($album_photos['data']).')';?></h4>
														<a href="<?php echo $album_photo['source'];?>" class="fancybox" rel="<?php echo $album['id'];?>">
														  <img src="<?php echo $album_resized_cover_photo;?>" class="image-responsive img-rounded" alt="<?php echo $album['name'];?>" />
														</a>

														<div class="caption">
															<button rel="<?php echo $album['id'].','.$album['name'];?>" class="single-download btn btn-primary pull-left" title="Download Album">
																<span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
															</button>

															<input type="checkbox" class="select-album" value="<?php echo $album['id'].','.$album['name'];?>" />

															<span rel="<?php echo $album['id'].','.$album['name'];?>" class="move-single-album btn btn-danger pull-right" title="Move to Google">
																<span class="glyphicon glyphicon-export" aria-hidden="true"></span>
															</span>
														</div>
													</div>
												</div>
										<?php
											} else {
										?>
											<a href="<?php echo $album_photo['source'];?>" class="fancybox" rel="<?php echo $album['id'];?>" style="display:none;"></a>
											
										<?php
											}
										}
									}
								}
							}
						?>
						<!--</ul>-->
					</div>
				</div>

<?php
		} else {
			$perm = array( "scope" => "email,user_photos" );
?>
			<nav class="navbar navbar-inverse" role="navigation">
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="#">Facebook Album Challenge</a>
					</div>
				</div>
			</nav>

			<div id="login-div" class="row">
				<a id="login-link" class="btn btn-primary btn-lg" href="<?php echo $helper->getLoginUrl( $perm );?>">
					Facebook
				</a>
			</div>

<?php   } ?>

		<script type="text/javascript" charset="utf-8">
			$( document ).ready(function() {
				var opts = {
				  lines: 20, // The number of lines to draw
				  length: 50, // The length of each line
				  width: 20, // The line thickness
				  radius: 30, // The radius of the inner circle
				  corners: 1, // Corner roundness (0..1)
				  rotate: 0, // The rotation offset
				  direction: 1, // 1: clockwise, -1: counterclockwise
				  color: '#000', // #rgb or #rrggbb or array of colors
				  speed: 1, // Rounds per second
				  trail: 60, // Afterglow percentage
				  shadow: true, // Whether to render a shadow
				  hwaccel: false, // Whether to use hardware acceleration
				  className: 'spinner', // The CSS class to assign to the spinner
				  zIndex: 2e9, // The z-index (defaults to 2000000000)
				  top: '50%', // Top position relative to parent
				  left: '50%' // Left position relative to parent
				};
				var target = document.getElementById('loader');

				$('.fancybox').fancybox({
					autoPlay: true
				});

				function append_download_link(url) {
					var spinner = new Spinner(opts).spin(target);

					$.ajax({
						url:url,
						success:function(result){
							$("#display-response").html(result);
							spinner.stop();
							$("#download-modal").modal({
								show: true
							});
						}
					});
				}

				function get_all_selected_albums() {
					var selected_albums;
					var i = 0;
					$(".select-album").each(function () {
						if ($(this).is(":checked")) {
							if (!selected_albums) {
								selected_albums = $(this).val();
							} else {
								selected_albums = selected_albums + "/" + $(this).val();
							}
						}
					});

					return selected_albums;
				}

				$(".single-download").on("click", function() {
					var rel = $(this).attr("rel");
					var album = rel.split(",");

					append_download_link("download_album.php?zip=1&single_album="+album[0]+","+album[1]);
				});

				$("#download-selected-albums").on("click", function() {
					var selected_albums = get_all_selected_albums();
					append_download_link("download_album.php?zip=1&selected_albums="+selected_albums);
				});

				$("#download-all-albums").on("click", function() {
					append_download_link("download_album.php?zip=1&all_albums=all_albums");
				});


				function getParameterByName(name) {
					name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
					var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
						results = regex.exec(location.search);
					return results === null ? "null" : decodeURIComponent(results[1].replace(/\+/g, " "));
				}

				function display_message( response ) {
					if ( response == 1 ) {
						$("#display-response").html('<div class="alert alert-success" role="alert">Album(s) is successfully moved to Picasa</div>');
						$("#download-modal").modal({
							show: true
						});
					} else if ( response == 0 ) {
						console.log(response);
						$("#display-response").html('<div class="alert alert-danger" role="alert">Due to some reasons album(s) is not moves to Picasa</div>');
						$("#download-modal").modal({
							show: true
						});
					}
				}

				get_params();

				function get_params() {
					var response = getParameterByName('response');
					display_message(response);
				}
				

				var google_session_token = '<?php echo $google_session_token;?>';

				function move_to_picasa(param1, param2) {
					if (google_session_token) {
						var spinner = new Spinner(opts).spin(target);

						$.ajax({
							url:"download_album.php?ajax=1&"+param1+"="+param2,
							success:function(result){
								spinner.stop();
								display_message(result);
							}
						});
					} else {
						window.location.href = "libs/google_login.php?"+param1+"="+param2;
					}
				}

				$(".move-single-album").on("click", function() {
					var single_album = $(this).attr("rel");
					move_to_picasa("single_album", single_album);
				});

				$("#move-selected-albums").on("click", function() {
					var selected_albums = get_all_selected_albums();
					move_to_picasa("selected_albums", selected_albums);
				});

				$("#move_all").on("click", function() {
					move_to_picasa("all_albums", "all_albums");
				});
			});
		</script>
	</body>
</html>
