<?php

if (!defined('MEDIAWIKI'))
	die();

// Adds the action preserve
$wgActions['preserve'] = true;

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
