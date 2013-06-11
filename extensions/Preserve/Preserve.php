<?php

if (!defined('MEDIAWIKI'))
	die();

/**
 * Enables a regular user to specify the authorizations of a page.
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
$wgAutoloadClasses['Preserve'] = __DIR__ . '/Preserve.class.php';


// $wgHooks['xx'][] = 'Preserve::hookXX'; 

$wgHooks['MediaWikiPerformAction'][] = 'Preserve::hookMediaWikiPerformAction';
$wgHooks['SkinTemplateNavigation'][] = 'Preserve::hookSkinTemplateNavigation';