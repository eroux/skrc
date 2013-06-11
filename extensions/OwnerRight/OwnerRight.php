<?php

if (!defined('MEDIAWIKI'))
	die();

// MEDIAWIKI CONFIGURATION SECTION
// ===============================
// The next lines must not be overridden by LocalSettings or other extensions.
// This MediaWiki configuration is necessary to ensure this extension works properly.

// Adds the beowner level is in level restriction list
// Restriction levels should be user groups, but in the core they are treated as rights.
// We therefore create the RIGHT (= action) "beowner" (Be Owner)
if (!in_array('beowner', $wgRestrictionLevels)) {
	$wgRestrictionLevels[] = 'beowner'; // append at the end
}

// Everyone can be an owner
// but in absolute terms, this should granted to the same group as rights 'createpage' and 'createtalk'.
$wgGroupPermissions['*']['beowner'] = true;
// this extension raises error for actions restricted to THE owner of a resource, except for sysops who have the besuperowner right
$wgGroupPermissions['sysop']['besuperowner'] = true;

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
	'name' => 'OwnerRight',
	'descriptionmsg' => 'ownerright-desc',
	'version' => 0.1,
	'author' => array('Seizam SàRL'),
	'url' => 'http://atelier.seizam.com',
);

$wgExtensionMessagesFiles['OwnerRight'] = __DIR__ . '/OwnerRight.i18n.php';
$wgAutoloadClasses['OwnerRight'] = __DIR__ . '/OwnerRight.class.php';

$wgHooks['getUserPermissionsErrors'][] = 'OwnerRight::hookGetUserPermissionsErrors'; 
