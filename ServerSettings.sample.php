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
$wgScriptPath = "";
$wgArticlePath = "/$1";
$wgUsePathInfo = true;
$wgScriptExtension = ".php";

## The protocol and server name to use in fully-qualified URLs
$wgServer = "http://skrc.localhost";

$wgEmergencyContact = "contact@seizam.com";
$wgPasswordSender = "contact@seizam.com";

## Database settings
$wgDBtype = "mysql";
$wgDBserver = "localhost";
$wgDBname = "skrc-db";
$wgDBuser = "skrc-user";
$wgDBpassword = "skrc-password";

# MySQL specific settings
$wgDBprefix = "";

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

$wgImageMagickConvertCommand = "/usr/bin/convert";

## Set $wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publically accessible from the web.
$wgCacheDirectory = "/srv/wiki/cache";

# Path to the GNU diff3 utility. Used for conflict resolution.
$wgDiff3 = "/usr/bin/diff3";

# Where are stored user files
$wgUploadDirectory = '/srv/wiki/uploads';