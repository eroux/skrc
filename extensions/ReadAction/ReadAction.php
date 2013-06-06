<?php

if (!defined('MEDIAWIKI'))
	die();

// MEDIAWIKI CONFIGURATION SECTION
// ===============================
// The next lines must not be overridden by LocalSettings or other extensions.
// This MediaWiki configuration is necessary to ensure this extension works properly.

// Adds the read right is in restricted actions list
if (!in_array('read', $wgRestrictionTypes)) {
	$wgRestrictionTypes[] = 'read';
}

# Disable feeds, see http://www.mediawiki.org/wiki/Extension:PageSecurity#Disable_feeds
$wgFeedClasses = array();
// as written in FeedUtils.php: no "privileged" version should end up in the cache.
$wgFeed = false; // Disable syndication feeds (RSS & Atom, Recentchanges, Newpages, etc)
$wgFeedCacheTimeout = 0; // Even enabled, no cache for feed
$wgFeedDiffCutoff = 0; // No diff in RSS/Atom feed
$wgAdvertisedFeedTypes = array(); // no feed

// END OF MEDIAWIKI CONFIGURATION SECTION


/**
 * Extends implementation of MediaWiki "read" action permission
 *
 * @file
 * @ingroup Extensions
 * @author Seizam SàRL <contact@seizam.com>
 * @license Creative Commons Attribution Non-Commercial Share Alike.
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

