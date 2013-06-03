<?php
# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

# Include of ServerSetting.php where Server Side settings are stored
require_once("$IP/ServerSettings.php");

# Register i18n file
$wgExtensionMessagesFiles['LocalSettings'] = __DIR__ . '/LocalSettings.i18n.php';

## Uncomment this to disable output compression
# $wgDisableOutputCompression = true;

$wgSitename = "ShangpaWiki";
$wgMetaNamespace = "Project";

## The relative URL path to the skins directory
$wgStylePath = "$wgScriptPath/skins";

## The relative URL path to the logo.  Make sure you change this from the default,
## or else you'll overwrite your logo when you upgrade!
$wgLogo = "$wgStylePath/common/images/wiki.png";

## UPO means: this is also a user preference option

$wgEnableEmail = true;
$wgEnableUserEmail = true; # UPO

$wgEnotifUserTalk = true; # UPO
$wgEnotifWatchlist = true; # UPO
$wgEmailAuthentication = true;

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads = true;
$wgUploadPath = "$wgScriptPath/img_auth.php";
$wgImgAuthPublicTest = false; // false = bypass "public wiki" test because we are not
$wgUseImageMagick = true;

# InstantCommons allows wiki to use images from http://commons.wikimedia.org
$wgUseInstantCommons = false;

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
#$wgHashedUploadDirectory = false;

# Site language code, should be one of the list in ./languages/Names.php
$wgLanguageCode = "en-gb";

$wgSecretKey = "8b96b177ed2e10473b77b8e07f3363f532380f761d77d12e9b9a0c8e43abf6fb";

# Site upgrade key. Must be set to a string (default provided) to turn on the
# web installer while LocalSettings.php is in place
$wgUpgradeKey = "d71ad26d0f782ac1";

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'standard', 'nostalgia', 'cologneblue', 'monobook', 'vector':
$wgDefaultSkin = "vector";

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "http://creativecommons.org/licenses/by-nc-sa/3.0/";
$wgRightsText = "Creative Commons Attribution Non-Commercial Share Alike";
$wgRightsIcon = "{$wgStylePath}/common/images/cc-by-nc-sa.png";

# Query string length limit for ResourceLoader. You should only set this if
# your web server has a query string length limit (then set it to that limit),
# or if you have suhosin.get.max_value_length set in php.ini (then set it to
# that value)
$wgResourceLoaderMaxQueryLength = -1;


# Permissions

$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['*']['edit'] = false;


# Extensions

require_once( "$IP/extensions/Cite/Cite.php" );

require_once( "$IP/extensions/ImageMap/ImageMap.php" );

require_once( "$IP/extensions/InputBox/InputBox.php" );

require_once( "$IP/extensions/ParserFunctions/ParserFunctions.php" );

require_once( "$IP/extensions/PdfHandler/PdfHandler.php" );

require_once( "$IP/extensions/Poem/Poem.php" );

require_once( "$IP/extensions/SyntaxHighlight_GeSHi/SyntaxHighlight_GeSHi.php" );

require_once( "$IP/extensions/Vector/Vector.php" );

require_once( "$IP/extensions/WikiEditor/WikiEditor.php" );

require_once( "$IP/extensions/ReadAction/ReadAction.php" );
