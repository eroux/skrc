<?php

if (!defined('MEDIAWIKI'))
	die();

// Adds the action preserve
$wgActions['preserve'] = true;

// Visible restrictions in the preserve action form
if (!isset($wgPreserveRestrictionTypes)) {
	$wgPreserveRestrictionTypes = true; // true means "= $wgRestrictionTypes" , or list as an array: "array( 'edit', 'upload', ...)"
}

// Visible levels in the preserve action form
if (!isset($wgPreserveRestrictionLevels)) {
	$wgPreserveRestrictionLevels = true; // true means "= $wgRestrictionLevels" , or list as an array: "array( '', 'autoconfirmed', ...)"
}

// Show all $wgPreserveRestrictionLevels, or filter according to user rights ?
if (!isset($wgPreserveShowAllLevels)) {
	 $wgPreserveShowAllLevels = true; // true means 'show all' , false means 'filter'
}

// Allow user to change the restriction level of an action even if he doesn't belong to the group of the current restriction level
if (!isset($wgPreserveDeselectableLevels)) {
	$wgPreserveDeselectableLevels = true; // true means 'can change all levels', false means 'forbid if he doesn't belong to current level', or list levels that he can always change as an array: "array ( 'sysop', ...)"
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
