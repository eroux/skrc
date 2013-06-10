<?php

if (!defined('MEDIAWIKI'))
	die();

// MEDIAWIKI CONFIGURATION SECTION
// ===============================
// The next lines must not be overridden by LocalSettings or other extensions.
// This MediaWiki configuration is necessary to ensure this extension works properly.

// Adds the read right is in restricted actions list
if (!in_array('creator', $wgRestrictionLevels)) {
	array_unshift($wgRestrictionLevels, 'creator'); // prepend at the first place
}

// Everyone can be a creator, but in absolute terms, this should granted to be 
// the same group as rights 'createpage' and 'createtalk'.
$wgGroupPermissions['*']['creator'] = true;
// this extension raises error for actions restricted to THE creator, except for sysops
$wgGroupPermissions['sysop']['bypasscreator'] = true;

// END OF MEDIAWIKI CONFIGURATION SECTION

/**
 * Implements a "creator" restriction level.
 * @file
 * @ingroup Extensions
 * @author Seizam SàRL <contact@seizam.com>
 * @license GNU General Public License version 3 or any later version <http://www.gnu.org/copyleft/gpl.html>
 */
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'creator',
	'descriptionmsg' => 'creator-desc',
	'version' => 0.1,
	'author' => array('Seizam SàRL'),
	'url' => 'http://atelier.seizam.com',
);

$wgExtensionMessagesFiles['Creator'] = __DIR__ . '/Creator.i18n.php';
$wgAutoloadClasses['Creator'] = __DIR__ . '/Creator.class.php';

$wgHooks['getUserPermissionsErrors'][] = 'Creator::hookGetUserPermissionsErrors'; 
