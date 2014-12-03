<?php

session_start();

//============  Google Move Code ======================//

if ( isset( $_GET['album_download_directory'] ) ) {
	$album_download_directory = $_GET['album_download_directory'];
	$album_download_directory = '../'.$album_download_directory;
} else {
	header('location:../index.php');
}

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_Photos');
//Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');


function getAuthSubHttpClient() {
	if ( isset( $_SESSION['google_session_token'] ) ) {
		$client = Zend_Gdata_AuthSub::getHttpClient( $_SESSION['google_session_token'] );
		return $client;
	}
}

$gp = new Zend_Gdata_Photos(getAuthSubHttpClient(), "Google-DevelopersGuide-1.0");
$entry = new Zend_Gdata_Photos_AlbumEntry();


function add_new_album( $entry, $gp, $album_download_directory, $album_name ) {
	$new_album_name = str_replace( " ", "_", $album_name );
	$new_album_name = $new_album_name.'_'.uniqid();

	$entry->setTitle( $gp->newTitle( $new_album_name ) );
	$entry->setSummary( $gp->newSummary("Album added by Facebook Album Challenge") );
	$gp->insertAlbumEntry( $entry );

	$path = $album_download_directory.$album_name;
	if ( file_exists( $path ) ) {
		$photos = scandir( $path );

		foreach ( $photos as $photo ) {
			if ( $photo != "." && $photo != ".." ) {
				$photo_path = $path.'/'.$photo;
				add_new_photo_to_album( $gp, $photo_path, $new_album_name );
			}
		}
	}	
}

function add_new_photo_to_album( $gp, $path, $new_album_name ) {
	$user_name = "default";
	$file_name = $path;
	$photo_name = "Photo added by Facebook Album Challenge";
	$photo_caption = "Photo added by Facebook Album Challenge";
	$photo_tags = "Photo, Facebook-Album-Challenge";

	$fd = $gp->newMediaFileSource( $file_name );
	$fd->setContentType("image/jpeg");

	// Create a PhotoEntry
	$photo_entry = $gp->newPhotoEntry();

	$photo_entry->setMediaSource( $fd );
	$photo_entry->setTitle( $gp->newTitle( $photo_name ) );
	$photo_entry->setSummary( $gp->newSummary( $photo_caption ) );

	// add some tags
	$photo_media = new Zend_Gdata_Media_Extension_MediaKeywords();
	$photo_media->setText( $photo_tags );
	$photo_entry->mediaGroup = new Zend_Gdata_Media_Extension_MediaGroup();
	$photo_entry->mediaGroup->keywords = $photo_media;

	// We use the AlbumQuery class to generate the URL for the album
	$album_query = $gp->newAlbumQuery();

	$album_query->setUser( $user_name );
	//$albumQuery->setAlbumId($albumId);
	$album_query->setAlbumName( $new_album_name );

	// We insert the photo, and the server returns the entry representing
	// that photo after it is uploaded
	//$insertedEntry = $gp->insertPhotoEntry( $photoEntry, $albumQuery->getQueryUrl() );
	$gp->insertPhotoEntry( $photo_entry, $album_query->getQueryUrl() );
}

if ( isset( $album_download_directory ) ) {
	if ( file_exists( $album_download_directory ) ) {
		$album_names = scandir( $album_download_directory );

		foreach ( $album_names as $album_name ) {
			if ( $album_name != "." && $album_name != "..") {
				add_new_album( $entry, $gp, $album_download_directory, $album_name );
			}
		}

		$unlink_folder = rtrim( $album_download_directory, "/" );
		require_once('../unlink_directory.php');
		$unlink_directory = new unlink_directory();
		$unlink_directory->remove_directory( $unlink_folder );
	}
	$response = 1;
} else {
	$response = 0;
}


if ( isset( $_GET['ajax'] ) )
	echo $response;
else
	header('location:../index.php?response='.$response);


?>
