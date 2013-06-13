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
$wgLanguageCode = "en";

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

# remove the link to the talk page for non-logged in users, because they won't be able to edit it
$wgShowIPinHeader = false;

# Customizes core permissions

$wgGroupPermissions['*']['createaccount'] = false; // only 'sysop' can
$wgGroupPermissions['*']['edit'] = false; // only 'user' (authenticated) can
$wgGroupPermissions['*']['createpage'] = false; // only 'user' (authenticated) can
$wgGroupPermissions['*']['createtalk'] = false; // only 'user' (authenticated) can
$wgGroupPermissions['*']['writeapi'] = false; // only 'user' (authenticated) can

$wgGroupPermissions['*']['preserve'] = true; // THIS LINE MAY BE REMOVED LATER

$wgGroupPermissions['bureaucrat']['createaccount'] = true;


# Adds adherent's user groups, set relations and their restriction levels

$wgGroupPermissions['user']['adherent'] = true;

$wgGroupPermissions['acarya']['adherent'] = true;
$wgGroupPermissions['acarya']['acarya'] = true;

$wgGroupPermissions['lama']['adherent'] = true;
$wgGroupPermissions['lama']['acarya'] = true;
$wgGroupPermissions['lama']['lama'] = true;

$wgGroupPermissions['vajracarya']['adherent'] = true;
$wgGroupPermissions['vajracarya']['acarya'] = true;
$wgGroupPermissions['vajracarya']['lama'] = true;
$wgGroupPermissions['vajracarya']['vajracarya'] = true;


# Sysops can access all restriction levels

$wgGroupPermissions['sysop']['adherent'] = true;
$wgGroupPermissions['sysop']['acarya'] = true;
$wgGroupPermissions['sysop']['lama'] = true;
$wgGroupPermissions['sysop']['vajracarya'] = true;


// Configure a owner restriction level, with its bypass for sysops
$wgOwnerLevels = array('owner' => 'superowner');
$wgGroupPermissions['*']['owner'] = true;
$wgGroupPermissions['sysop']['superowner'] = true;


# Adds additional levels that can be selected on the 'page protection' page.
# Default levels are ''(everyone), autoconfirmed and sysop
$wgRestrictionLevels = array(
	'', // everyone
	'adherent',
	'acarya',
	'lama',
	'vajracarya',
	'owner', // only the owner of the page
	'sysop'
);

# Sets restriction levels available on the preserve action page
$wgPreserveRestrictionLevels = array(
	'', // everyone
	'adherent',
	'acarya',
	'lama',
	'vajracarya',
);

# Sets restriction types available on the preserve action page
$wgPreserveRestrictionTypes = array ( 'read', 'edit', 'upload');

# Always show all levels
$wgPreserveShowAllLevels = true;

# Users can change a restriction set to 'owner'
$wgPreserveDeselectableLevels = array('owner');


# Grants to page's owner access to the preserve action (and sysops the bypass right)
$wgGroupPermissions['*']['preserve'] = true;
$wgOwnerActions = array('preserve' => 'preserveany');
$wgGroupPermissions['sysop']['preserveany'] = true;


## Extensions

# Citations
require_once( "$IP/extensions/Cite/Cite.php" );

# Clickable Images
# require_once( "$IP/extensions/ImageMap/ImageMap.php" );

# Forms
require_once( "$IP/extensions/InputBox/InputBox.php" );

# Advanced scipting in wikitext
require_once( "$IP/extensions/ParserFunctions/ParserFunctions.php" );

# Pdf Handler
require_once( "$IP/extensions/PdfHandler/PdfHandler.php" );

# <poem> tag
require_once( "$IP/extensions/Poem/Poem.php" );

# Code colorification
# require_once( "$IP/extensions/SyntaxHighlight_GeSHi/SyntaxHighlight_GeSHi.php" );

# Advanced skin features
require_once( "$IP/extensions/Vector/Vector.php" );

# Advanced editing interface
require_once( "$IP/extensions/WikiEditor/WikiEditor.php" );
$wgWikiEditorFeatures['toolbar']['global'] = true;
$wgWikiEditorFeatures['toolbar']['user'] = false;
$wgWikiEditorFeatures['dialogs']['global'] = true;
$wgWikiEditorFeatures['dialogs']['user'] = false;
$wgWikiEditorFeatures['preview']['user'] = false;
$wgWikiEditorFeatures['publish']['user'] = false;
$wgWikiEditorFeatures['toc']['user'] = false;

# =[ ReadAction ]=
# Extends "read" right
require_once( "$IP/extensions/ReadAction/ReadAction.php" );


# =[ Language Selector ]=
#Auto select user language and adds language selection menu
require_once( "$IP/extensions/LanguageSelector/LanguageSelector.php" );
# Supported languages
$wgLanguageSelectorLanguages = array('bo','en','fr');
# Displayed languages
$wgLanguageSelectorLanguagesShorthand = array('bo','en','fr');
# Method of language selection
$wgLanguageSelectorDetectLanguage = LANGUAGE_SELECTOR_PREFER_CLIENT_LANG; # Automatic selection regarding browser
# Where to put the language selection dropdown menu
$wgLanguageSelectorLocation = LANGUAGE_SELECTOR_AS_PORTLET; # In the left toolbox

# =[ Authorizations ]=
require_once( "$IP/extensions/Preserve/Preserve.php" );

# =[ OwnerRight ]=
require_once( "$IP/extensions/OwnerRight/OwnerRight.php" );
