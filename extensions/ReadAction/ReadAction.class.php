<?php

/**
 * This class extends implementation of MediaWiki "read" action permission
 */
class ReadAction {

	/**
	 * To interrupt/advise the "user can do X to Y article" check
	 * @param Title $title reference to the title in question (see the use in $IP/includes/Title.php)
	 * @param User $user reference to the current user (see the use in $IP/includes/Title.php)
	 * @param string $action action concerning the title in question
	 * @param mixed $result User permissions error to add. If none, return true.
	 * $result can be returned as a single error message key (string), or an
	 * array as one message key with parameters
	 * @return boolean true to continue hook processing or false to abort
	 * the processing of the hook.
	 */
	public static function hookGetUserPermissionsErrors(&$title, &$user, $action, &$result) {
		wfDebugLog('ReadAction', 'GetUserPermissionsErrors: "'.$action.'" for ' . $user->getName() . '" on "' . $title->getPrefixedDBkey() . '"');
		if ($action == 'read') {
			$error = self::checkPageReadRestrictions($title, $user);
			if (count($error) > 0) {
				wfDebugLog('ReadAction', 'read permission error for "' . $user->getName() . '" on "' . $title->getPrefixedDBkey() . '"');

				$result = $error;
				return false; // stop hook processing
			}
		}
		return true; // continue hook processing
	}

	/**
	 * 
	 * @param Parser $parser
	 * @param Title $title
	 * @param boolean $skip indicates whether to not load content
	 * @param mixed $id False for the current revision, int for a specific revision
	 * @return boolean whether to continue hook processing
	 */
	public static function hookBeforeParserFetchTemplateAndtitle($parser, $title, &$skip, &$id) {
		if ($title->isProtected('read')) {
			wfDebugLog('ReadAction', 'page "'.$title->getPrefixedDBkey().'" is read protected, so cannot be transcluded');
			$skip = true; // content not transcluded
		}
		return !$skip;
	}

	/**
	 * @param Article $article the article object that was protected
	 * @param User $user the user object who did the protection
	 * @param boolean $protect whether it was a protect or an unprotect
	 * @param string $reason Reason for protect
	 */
	public static function hookArticleProtectComplete(&$article, &$user, $protect, $reason) {
		$title = $article->getTitle();

		// purge cache of the page
		$title->mRestrictions = array();
		$title->mRestrictionsLoaded = false;
		WikiPage::onArticleEdit($title); // this put update in $wgDeferredUpdateList
		wfDoUpdates(); // this execute all updates in $wgDeferredUpdateList
		$title->invalidateCache();

		// force update of search engine
		$searchUpdate = new SearchUpdate($title->getArticleId(), $title->getPrefixedDBkey(), Revision::newFromTitle($title)->getText());
		$searchUpdate->doUpdate(); // run hook SearchUpdate

		return true; // continue hook processing 
	}

	/**
	 * 
	 * @param int $id The page id
	 * @param int $namespace The page namespace index, i.e. one of the NS_xxxx constants.
	 * @param string $title The page title text form (spaces not underscores) of the main part
	 * @param string $text The text being indexed
	 */
	public static function hookSearchUpdate($id, $namespace, $title, &$text) {
		$title = Title::newFromID($id);
		if ($title && $title->isProtected('read')) {
			$text = ''; // hide from search
		}
		return true; // continue hook processing 
	}

	/**
	 * 
	 * @param type $title
	 * @param type $user
	 * @return array Array of error, empty if no restriction apply
	 */
	public static function checkPageReadRestrictions(&$title, &$user) {

		wfDebugLog('ReadAction', 'check read for "' . $user->getName() . '" on "' . $title->getPrefixedDBkey() . '"');

		$errors = array();
		foreach ($title->getRestrictions('read') as $right) {
			if ($right == 'sysop') {
				$right = 'protect'; // Backwards compatibility, rewrite sysop -> protect
			}
			if ($right != '' && !$user->isAllowed($right)) {
				$errors[] = array('readaction-restricted', $right);
			}
		}

		return $errors;
	}

}
