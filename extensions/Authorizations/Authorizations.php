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
	'name' => 'Authorizations',
	'descriptionmsg' => 'authorizations-desc',
	'version' => 0.1,
	'author' => array('Seizam SàRL'),
	'url' => 'http://atelier.seizam.com',
);

$wgExtensionMessagesFiles['Authorizations'] = __DIR__ . '/Authorizations.i18n.php';
$wgAutoloadClasses['Authorizations'] = __DIR__ . '/Authorizations.class.php';


$wgHooks['xx'][] = 'Authorizations::hookXX'; 

$wgHooks['MediaWikiPerformAction'][] = 'Authorizations::hookMediaWikiPerformAction';
$wgHooks['SkinTemplateNavigation'][] = 'Authorizations::hookSkinTemplateNavigation';