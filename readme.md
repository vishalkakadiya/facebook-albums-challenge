Facebook Album's Downloader and uploader to Picasa+Google
=====================================

Working Demo :  <a href="https://evening-basin-3281.herokuapp.com">Facebook Albums Challenge</a> 

Working:

PART 1 :

User Login using Facebook credentials.
Ask user to give permission to access of email,cover_photo,name and photos.
Application fetches all Albums which is added by user or in which user is tagged.


PART 2 :

Albums are displayed with a Thumbnail, Album Name.
When a user clicks on Album cover-photo, all photos for that album are displayed in full screen slideshow.

A "Download" link(in blue) is displayed for each album.
When user clicks on "Download" link, jquery(Ajax) processes PHP script to collect photos for that album, Zip them and prompts "Download Zip Folder" Link to user for download.

An checkbox is displayed for each album.
A "Download Selected Albums" link is displayed at top.
When user clicks on "Download Selected Albums" link, jquery(Ajax) processes PHP script to collect photos for all checked albums, Zip them and prompts "Download Zip Folder" Link to user for download.

A "Download All Albums" link is displayed at top.
When user clicks on "Download All Albums" link, jquery(Ajax) processes PHP script to collect photos for all albums, Zip them and prompts "Download Zip Folder" Link to user for download.

All the time while albums are download and processed into zip, a loading spinner is showing.


PART 3 :

NOTE : At first time if user is not login to google account then it sends to login page and asks to grant access from user. 

A "Move" link(in Red) is displayed for each album.
When user clicks on "Move" link, jquery(Ajax) processes PHP script to collect photos for that album and upload into PicasaWeb of Google.

An checkbox is displayed for each album.
A "Move Selected Albums" link is displayed at top.
When user clicks on "Move Selected Albums" link, jquery(Ajax) processes PHP script to collect photos for all checked albums and upload into PicasaWeb of Google.

A "Move All Albums" link is displayed at top.
When user clicks on "Move All Albums" link, jquery(Ajax) processes PHP script to collect photos for all albums and upload into PicasaWeb of Google.

All the time while albums are processed to move, a loading spinner is showing.


Importance

An clear responsive application which is works on Desktop, Tablets and mobile.
Works on code optimization(make functions whenever need).
Mobile/Tablet users having move and download links available at top even he/she scroll down at the bottom of the page.
Mobile/Tablet users also having "Zip Download Link" available on screen even he/she scroll down at the bottom of the page.



Platforms:
PHP


Library Used:
==========================================================
Facebook PHP SDK
----------------------
The Facebook SDK for PHP provides developers with a modern, native library for accessing the Graph API and 
taking advantage of Facebook Login. Usually this means you're developing with PHP for a Facebook Canvas app, 
building your own website, or adding server-side functionality to an app.
More information and examples: https://developers.facebook.com/docs/reference/php/4.0.0

Picasa Google PHP Library
Used for uploading albums on https://picasaweb.google.com

For download Php library: https://developers.google.com/picasa-web/code
More information and examples: https://developers.google.com/picasa-web/docs/1.0/developers_guide_php

FancyBox
FancyBox is a tool that offers a nice and elegant way to add zooming functionality for images, html content and multi-media on your webpages.
More information and examples: http://www.fancyapps.com/fancybox/

Spin.js
Spin.js dynamically creates spinning activity indicators that can be used as resolution-independent replacement for AJAX loading GIFs.
More information and examples: http://fgnass.github.io/spin.js/

Twitter Bootstrap
Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web.
More information and examples: http://getbootstrap.com/


Scripting Languages:
Jquery
Ajax


Styling:
Css


How To use 
================================================

=> First of all Go on https://developers.facebook.com/
=> From menu select Apps->Add a New App->WWW->Give Name an create new app id.
=> Test version of another -> select No
=> Choose your category -> clicks Create App ID.
=> Right-Top corner select skip quick start.

=> After that in your app go to Settings
	Add:
		-> Namespace
		-> Contact Email

=> In settings +Add Platform-> Select Website
	Add:
		-> Site Url
		-> Domain
		NOTE : even if localhost url also works.

=> NOTE: if you want the all photos permission of users then you need to approve your facebook app first.


=> Download our app from github
=> put this in root directory(Wamp => www, xampp => htdocs)
=> unzip it.
=> go to includes.php
	Set:
		$fb_app_id = 'your-fb-app-key';
		$fb_secret_id = 'your-fb-app-secret-key';
	
		//fb_login_url is same url which is added in facebook app->settings.
		$fb_login_url = 'your login url or index url where the response is come'; 
		$fb_logout_url = 'your logout url';

=> Run the index.php page and have fun
