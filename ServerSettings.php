<?php
# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
## For more information on customizing the URLs
## (like /w/index.php/Page_title to /wiki/Page_title) please see:
## http://www.mediawiki.org/wiki/Manual:Short_URL
$wgScriptPath = "/skrc";
#$wgArticlePath = "/$1";
#$wgUsePathInfo = true;
$wgScriptExtension = ".php";

## The protocol and server name to use in fully-qualified URLs
#$wgServer = "http://atelier/skrc";

$wgEmergencyContact = "contact@seizam.com";
$wgPasswordSender = "contact@seizam.com";

## Database settings
$wgDBtype = "mysql";
$wgDBserver = "localhost";
$wgDBname = "skrc";
$wgDBuser = "skrc";
$wgDBpassword = "skrc";
$wgDBprefix = ""; //MySQL

## Install Keys
$wgSecretKey = "8b96b177ed2e10473b77b8e07f3363f532380f761d77d12e9b9a0c8e43abf6fb";
$wgUpgradeKey = "d71ad26d0f782ac1";

# MySQL table options to use during installation or update
$wgDBTableOptions = "ENGINE=InnoDB, DEFAULT CHARSET=binary";

# Experimental charset support for MySQL 5.0.
$wgDBmysql5 = false;

## Shared memory settings
$wgMainCacheType = CACHE_ACCEL;
$wgMemCachedServers = array();

## If you use ImageMagick (or any other shell command) on a
## Linux server, this will need to be set to the name of an
## available UTF-8 locale
$wgShellLocale = "en_US.utf8";

## Uploads and images

# Where are stored user files
$wgUploadDirectory = '/Users/clement/atelier/skrcfiles';
# Entry point for files
$wgUploadPath = "$wgScriptPath/img_auth.php";

$wgUseImageMagick = false;
# $wgImageMagickConvertCommand = "/usr/bin/convert";

## Set $wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publically accessible from the web.
# $wgCacheDirectory = "/srv/wiki/cache";

# Path to the GNU diff3 utility. Used for conflict resolution.
$wgDiff3 = "/usr/bin/diff3";

# Query string length limit for ResourceLoader. You should only set this if
# your web server has a query string length limit (then set it to that limit),
# or if you have suhosin.get.max_value_length set in php.ini (then set it to
# that value)
$wgResourceLoaderMaxQueryLength = -1;
