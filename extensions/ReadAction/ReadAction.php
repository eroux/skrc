<?php

if (!defined('MEDIAWIKI'))
	die();

// MEDIAWIKI CONFIGURATION SECTION
// ===============================
// The next lines must not be overridden by LocalSettings or other extensions.
// This MediaWiki configuration is necessary to ensure this extension works properly.

// Adds the read right is in restricted actions list
if (!in_array('read', $wgRestrictionTypes)) {
	array_unshift($wgRestrictionTypes, 'read'); // prepend at the first place
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


$wgHooks['getUserPermissionsErrors'][] = 'ReadAction::hookGetUserPermissionsErrorsRead'; // checks "read" is not protected
$wgHooks['getUserPermissionsErrors'][] = 'ReadAction::hookGetUserPermissionsErrorsEdit'; // checks "read" for "edit" 
$wgHooks['getUserPermissionsErrors'][] = 'ReadAction::hookGetUserPermissionsErrorsUpload'; // checks "edit" for "upload"
$wgHooks['BeforeParserFetchTemplateAndtitle'][] = 'ReadAction::hookBeforeParserFetchTemplateAndtitle'; // do not transclude if "read" is protected
$wgHooks['ArticleProtectComplete'][] = 'ReadAction::hookArticleProtectComplete'; // clears caches
$wgHooks['SearchUpdate'][] = 'ReadAction::hookSearchUpdate'; // hides "read" protected pages

$wgHooks['UploadForm:initial'][] = 'ReadAction::hookUploadFormBeforeProcessing'; // checks the user can really upload
$wgHooks['UploadForm:BeforeProcessing'][] = 'ReadAction::hookUploadFormBeforeProcessing'; // checks the user can really upload
$wgHooks['ImgAuthBeforeStream'][] = 'ReadAction::hookImgAuthBeforeStreamArchive'; // modifies the title to always point to the real title
$wgHooks['ImgAuthBeforeStream'][] = 'ReadAction::hookImgAuthBeforeStreamDeleted'; // checks rights as Special:Undelete does
