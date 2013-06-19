<?php

if (!defined('MEDIAWIKI'))
	die();

// CONFIGURATION SECTION
// =====================

if (!isset($wgOwnerLevels)) {
	$wgOwnerLevels = array();
}

if (!isset($wgOwnerActions)) {
	$wgOwnerActions = array();
}

if (!isset($wgOwnerCanAlwaysRead)) {
	$wgOwnerCanAlwaysRead = true;
}

/**
 * CONFIGURATION EXAMPLES
 * 
 * 
 * Add a new restriction level 'owner' (in the protect form),
 * desginating only the owner of each page,
 * that sysops can bypass
 * 
 *   // Declares the owner level in this extension, with its bypass right
 *   $wgOwnerLevels = array('owner' => 'superowner');
 * 
 *   // Adds the new level to the protect form
 *   $wgRestrictionLevels[] = 'owner';
 * 
 *   // Everyone can "be (THE) owner", but this extension now filter this level on each page to the real owner
 *   $wgGroupPermissions['*']['owner'] = true;
 * 
 *   // Sysops bypass
 *   $wgGroupPermissions['sysop']['superowner'] = true;
 * 
 * 
 * Grant each page's owner the 'protect' right,
 * and sysops can still 'protect' any page
 * 
 *   // Only owner can 'protect', except for users having 'protectany' right
 *   $wgOwnerActions = array('preserve' => 'preserveany'); 
 * 
 *   // Everyone can 'protect', but this extension now filter on each page to the real owner
 *   $wgGroupPermissions['*']['preserve'] = true;            
 * 
 *   // Sysops bypass
 *   $wgGroupPermissions['sysop']['preserveany'] = true
 * 
 */

// END OF CONFIGURATION SECTION

/**
 * Makes some restriction levels and actions limited to the owner.
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

$wgHooks['getUserPermissionsErrors'][] = 'OwnerRight::hookGetUserPermissionsErrorsCheckRestrictions';
$wgHooks['getUserPermissionsErrors'][] = 'OwnerRight::hookGetUserPermissionsErrorsCheckAction';

if ($wgOwnerCanAlwaysRead) {
	$wgHooks['checkPageReadRestrictions'][] = 'OwnerRight::hookCheckPageReadRestrictions'; // owner can always read
}
