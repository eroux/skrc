<?php

if (!defined('MEDIAWIKI'))
	die();

/**
 * Extends implementation of MediaWiki "read" action permission
 *
 * @file
 * @ingroup Extensions
 * @author Seizam SàRL <contact@seizam.com>
 * @license GNU General Public License version 3 or any later version <http://www.gnu.org/copyleft/gpl.html>
 */
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'ReadAction',
	'descriptionmsg' => 'readaction-desc',
	'version' => 0.1,
	'author' => array('Seizam SàRL'),
	'url' => 'http://atelier.seizam.com',
);

$wgExtensionMessagesFiles['ReadAction'] = __DIR__ . '/ReadAction.i18n.php';
$wgAutoloadClasses['ReadAction'] = __DIR__ . '/ReadAction.class.php';

$wgHooks['getUserPermissionsErrors'][] = 'ReadAction::hookGetUserPermissionsErrors';
$wgHooks['BeforeParserFetchTemplateAndtitle'][] = 'ReadAction::hookBeforeParserFetchTemplateAndtitle';
$wgHooks['ArticleProtectComplete'][] = 'ReadAction::hookArticleProtectComplete';
$wgHooks['SearchUpdate'][] = 'ReadAction::hookSearchUpdate';

// ensures the read right is in restricted actions list
if (!in_array('read', $wgRestrictionTypes)) {
	$wgRestrictionTypes[] = 'read';
}
