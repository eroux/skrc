<?php

/**
 * This class extends implementation of MediaWiki "read" action permission
 */
class ReadAction {

	/**
	 * Checks page restrictions for "read" action
	 * @param Title $title reference to the title in question (see the use in $IP/includes/Title.php)
	 * @param User $user reference to the current user (see the use in $IP/includes/Title.php)
	 * @param string $action action concerning the title in question
	 * @param mixed $result User permissions error to add. If none, return true.
	 * $result can be returned as a single error message key (string), or an
	 * array as one message key with parameters
	 * @return boolean true to continue hook processing or false to abort
	 * the processing of the hook.
	 */
	public static function hookGetUserPermissionsErrorsRead(&$title, &$user, $action, &$result) {
		if ($action == 'read') {
			$error = self::checkPageReadRestrictions($title, $user);
			if (count($error) > 0) {
				$result = $error;
				return false; // stop hook processing
			}
		}
		return true; // continue hook processing
	}

	/**
	 * Ensures the user can do "read" when "edit".
	 * @param Title $title reference to the title in question (see the use in $IP/includes/Title.php)
	 * @param User $user reference to the current user (see the use in $IP/includes/Title.php)
	 * @param string $action action concerning the title in question
	 * @param mixed $result User permissions error to add. If none, return true.
	 * $result can be returned as a single error message key (string), or an
	 * array as one message key with parameters
	 * @return boolean true to continue hook processing or false to abort
	 * the processing of the hook.
	 */
	public static function hookGetUserPermissionsErrorsEdit(&$title, &$user, $action, &$result) {
		if ($action == 'edit') {
			$result = $title->getUserPermissionsErrors('read', $user);
			if (count($result) > 0) {
				return false; // stop hook processing
			}
		}
		return true; // continue hook processing
	}

	/**
	 * Ensures the user can do "edit" when "upload" (=when user uploads a file).
	 * This behaviour is hardcoded in UploadBase->verifyTitlePermissions() around line 566, but
	 * no fully checked everywhere, as in ImagePage.php around line 649 :
	 * <code>$canUpload = $this->getTitle()->userCan( 'upload', $this->getContext()->getUser() );</code>
	 * This hook should fix this.
	 * @param Title $title reference to the title in question (see the use in $IP/includes/Title.php)
	 * @param User $user reference to the current user (see the use in $IP/includes/Title.php)
	 * @param string $action action concerning the title in question
	 * @param mixed $result User permissions error to add. If none, return true.
	 * $result can be returned as a single error message key (string), or an
	 * array as one message key with parameters
	 * @return boolean true to continue hook processing or false to abort
	 * the processing of the hook.
	 */
	public static function hookGetUserPermissionsErrorsUpload(&$title, &$user, $action, &$result) {
		if ($action == 'upload') {
			$result = $title->getUserPermissionsErrors('edit', $user);
			if (count($result) > 0) {
				return false; // stop hook processing
			}
		}
		return true; // continue hook processing
	}

	/**
	 * Do not transclude if "read" is protected
	 * @param Parser $parser
	 * @param Title $title
	 * @param boolean $skip indicates whether to not load content
	 * @param mixed $id False for the current revision, int for a specific revision
	 * @return boolean whether to continue hook processing
	 */
	public static function hookBeforeParserFetchTemplateAndtitle($parser, $title, &$skip, &$id) {
		if ($title->isProtected('read')) {
			wfDebugLog('ReadAction', 'page "'.$title->getPrefixedDBkey().'" is read protected, so it cannot be transcluded');
			$skip = true; // content not transcluded
		}
		return !$skip;
	}

	/**
	 * Clears cache
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
	 * Hides page content when "read" is protected 
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
	 * Checks title permissions the same way as during final upload processing.
	 * Hooked to:
	 * <ul>
	 * <li><b>UploadForm:initial</b> : called just before the upload form is generated</li>
	 * <li><b>UploadForm:BeforeProcessing</b> : called just before the upload data are processed<br />
	 * NOTE: during this hook, the same verification is done as MediaWiki core during final upload
	 * processing, but it avoids stashing some file for an upload that will never succed.</li>
	 * </ul>
	 * @param SpecialPage $specialUploadObj current SpecialUpload page object
	 */
	public static function hookUploadFormBeforeProcessing($specialUploadObj) {
		// get the title of the file to be uploaded.
		$fileTitle = $specialUploadObj->mUpload->getTitle();
		if (!is_null($fileTitle)) {

			$user = $specialUploadObj->getUser();

			// copy from UploadBase->verifyTitlePermissions() around line 566
			// "edit" is checked by "upload" with hookGetUserPermissionsErrorsUpload
			$permErrorsUpload = $fileTitle->getUserPermissionsErrors('upload', $user);
			if (!$fileTitle->exists()) {
				$permErrorsCreate = $fileTitle->getUserPermissionsErrors('create', $user);
			} else {
				$permErrorsCreate = array();
			}
			if ( $permErrorsUpload || $permErrorsCreate) {
				$permErrors = array_merge($permErrorsUpload, wfArrayDiff2($permErrorsCreate, $permErrorsUpload));
				$specialUploadObj->getOutput()->showPermissionsErrorPage($permErrors);
				return false; // break SpecialUpload page init/processing
			}
		}

		return true; // continue hook processing
	}

	/**
	 * Makes sur the $title always point to the main title, and never to a fake
	 * in case of an archive
	 * @global type $wgContLang
	 * @param Title $title
	 * @param string $path
	 * @param string $name
	 * @param array $result
	 * @return boolean
	 * @todo To be implemented
	 */
	public static function hookImgAuthBeforeStreamArchive($title, $path, $name, $result) {
		// As written in LocalFile.php around line 1408
		// * name for archived file is <timestamp of archiving>!<name>
		//   $archiveName = wfTimestamp( TS_MW ) . '!'. $this->getName();
		// * and archives are under 'archive' folder
		//   $archiveRel = 'archive/' . $this->getHashPath() . $archiveName;
		// * (and archive's thumbs under '/thumb/archive/')
		if (( strpos($path, '/archive/') === 0 ) || ( strpos($path, '/thumb/archive/') === 0 )) {

			// identifies the real title name, as seen in ImagePage.php around line 1066
			$exploded = explode('!', $name, 2);
			if (isset($exploded[1])) { // this test is safer than unsing list()

				$name = $exploded[1];

				// instanciate the title the same way img_auth (around line 113) 
				$title = Title::makeTitleSafe(NS_FILE, $name);
				if ($title instanceof Title) {

					return true; // ok
				}
			}

			// generate the same error as img_auth in this case
			$result = array('img-auth-accessdenied', 'img-auth-badtitle', 'raw' => $name);
			return false;
		}
		return true;
	}

	/**
	 * Makes sur the the user as sufficient rights to consult deleted files
	 * @global type $wgContLang
	 * @param Title $title
	 * @param string $path
	 * @param string $name
	 * @param array $result
	 * @return boolean
	 * @todo To be implemented
	 */
	public static function hookImgAuthBeforeStreamDeleted($title, $path, $name, $result) {
		global $wgUser;

		if (( strpos($path, '/deleted/') === 0 ) && (!$wgUser->isAllowed('deletedhistory') )) {
			$result = array('img-auth-accessdenied', 'img-auth-noread', 'raw' => $name);
			return false;
		}

		return true;
	}

	/**
	 * Checks read restriction for the $user on the $title
	 * @param Title $title
	 * @param User $user
	 * @return array Array of error, empty if no restriction apply
	 */
	public static function checkPageReadRestrictions(&$title, &$user) {
		$errors = array();
		foreach ($title->getRestrictions('read') as $right) {
			if ($right == 'sysop') {
				$right = 'protect'; // Backwards compatibility, rewrite sysop -> protect
			}
			if ($right != '' && !$user->isAllowed($right)) {
				$errors[] = array('readaction-restricted', $right);
			}
		}

		wfDebugLog('ReadAction', 'checks read restriction for "' . $user->getName() . '" on "' . $title->getPrefixedDBkey() . '" : ' . (count($errors) > 0 ? 'NOT ALLOWED' : 'OK'));

		return $errors;
	}

}
