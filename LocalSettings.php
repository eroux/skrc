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
$wgImgAuthPublicTest = false; // false = bypass "public wiki" test because we are not


# InstantCommons allows wiki to use images from http://commons.wikimedia.org
$wgUseInstantCommons = false;

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
#$wgHashedUploadDirectory = false;

# Site language code, should be one of the list in ./languages/Names.php
$wgLanguageCode = "en-gb";

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'standard', 'nostalgia', 'cologneblue', 'monobook', 'vector':
$wgDefaultSkin = "vector";

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
$wgRightsPage = "Project:Copyright"; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "http://creativecommons.org/licenses/by-nc-sa/3.0/";
$wgRightsText = "Creative Commons Attribution Non-Commercial Share Alike";
$wgRightsIcon = "{$wgStylePath}/common/images/cc-by-nc-sa.png";


# Permissions

$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['*']['edit'] = false;

$wgGroupPermissions['khenpo']['khenpo'] = true;

$wgGroupPermissions['lama']['khenpo'] = true;
$wgGroupPermissions['lama']['lama'] = true;

$wgGroupPermissions['vajracarya']['khenpo'] = true;
$wgGroupPermissions['vajracarya']['lama'] = true;
$wgGroupPermissions['vajracarya']['vajracarya'] = true;

$wgGroupPermissions['sysop']['khenpo'] = true;
$wgGroupPermissions['sysop']['lama'] = true;
$wgGroupPermissions['sysop']['vajracarya'] = true;

# adds additional levels that can be selected on the 'page protection' page.
# (default = "everyone", autoconfirmed and sysop)
$wgRestrictionLevels[] = 'khenpo';
$wgRestrictionLevels[] = 'lama';
$wgRestrictionLevels[] = 'vajracarya';

# customizes "autoconfirmed" group criteria with real criteria
$wgAutoConfirmAge = 3600*24*5; // changes from 0 to 5 days
$wgAutoConfirmCount = 10; // changes from 0 to 10 edits
$wgAutopromote['autoconfirmed'][] = array( APCOND_EMAILCONFIRMED ); // new criteria


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
$wgWikiEditorFeatures['toolbar']['global'] = true;
$wgWikiEditorFeatures['toolbar']['user'] = false;
$wgWikiEditorFeatures['dialogs']['global'] = true;
$wgWikiEditorFeatures['dialogs']['user'] = false;
$wgWikiEditorFeatures['preview']['user'] = false;
$wgWikiEditorFeatures['publish']['user'] = false;
$wgWikiEditorFeatures['toc']['user'] = false;

require_once( "$IP/extensions/ReadAction/ReadAction.php" );
