<?php

if (!defined('MEDIAWIKI'))
	die();

// Adds the action preserve
$wgActions['preserve'] = true;

if (!isset($wgPreserveRestrictionTypes)) {
	$wgPreserveRestrictionTypes = true; // true means "= $wgRestrictionTypes" OR array( 'edit', 'upload', ...)
}

if (!isset($wgPreserveRestrictionLevels)) {
	$wgPreserveRestrictionLevels = true; // true means "= $wgRestrictionLevels" OR array( '', 'autoconfirmed', ...)
}

if (!isset($wgPreserveShowAllLevels)) {
	 $wgPreserveShowAllLevels = true; // true OR false
}

if (!isset($wgPreserveEnableDisallowedLevels)) {
	$wgPreserveEnableDisallowedLevels = true; // true OR false OR array ( 'sysop', ...)
}

/**
 * Enables a regular user to preserve resources of other people's actions.
 *
 * @file
 * @ingroup Extensions
 * @author Seizam SàRL <contact@seizam.com>
 * @license GNU General Public License version 3 or any later version <http://www.gnu.org/copyleft/gpl.html>
 */
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Preserve',
	'descriptionmsg' => 'preserve-desc',
	'version' => 0.1,
	'author' => array('Seizam SàRL'),
	'url' => 'http://atelier.seizam.com',
);

$wgExtensionMessagesFiles['Preserve'] = __DIR__ . '/Preserve.i18n.php';
$wgAutoloadClasses['PreserveAction'] = __DIR__ . '/PreserveAction.php';

$wgHooks['SkinTemplateNavigation'][] = 'PreserveAction::hookSkinTemplateNavigation'; // adds the preserve action link
