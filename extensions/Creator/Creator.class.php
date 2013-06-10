<?php

class Creator {

	/**
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

		foreach ($title->getRestrictions($action) as $right) {
			if (($right == 'creator') &&
					( $user->isAnon() || (!self::isCreator($user, $title) && !$user->isAllowed('bypasscreator') ) )) {
				$result = array(array('only-creator-can', $action));
				return false; // halt
			}
		}

		return true; // continue hook processing
	}

	/**
	 * 
	 * @param User $user
	 * @param Title $title
	 * @return boolean
	 */
	public static function isCreator($user, $title) {
		$result = false;
		if (wfRunHooks('isCreator', array($title, $user, &$result))) {
			$firstRevision = $title->getFirstRevision();
			$creator = $firstRevision ? $firstRevision->getRawUser() : 0; // Fetch revision's user id
			$result = $creator == $user->getId();
		}
		return $result;
	}

}
