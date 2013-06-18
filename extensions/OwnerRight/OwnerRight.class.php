<?php

class OwnerRight {

	/**
	 * When an action is restricted (via the protect form) to a LEVEL, 
	 * MediaWiki core only checks whether the user has the LEVEL right (in
	 * $wgGroupPermission, except when LEVEL="sysop" cheked right is "protect").
	 * When this LEVEL is an owner level (defined in $wgOwnerLevels), this 
	 * hook really checks whether the user is the owner or has the corresponding
	 * bypass right. 
	 * @param Title $title reference to the title in question (see the use in $IP/includes/Title.php)
	 * @param User $user reference to the current user (see the use in $IP/includes/Title.php)
	 * @param string $action action concerning the title in question
	 * @param mixed $result User permissions error to add. If none, return true.
	 * $result can be returned as a single error message key (string), or an
	 * array as one message key with parameters
	 * @return boolean true to continue hook processing or false to abort
	 * the processing of the hook.
	 */
	public static function hookGetUserPermissionsErrorsCheckRestrictions(&$title, &$user, $action, &$result) {
		global $wgOwnerLevels;
		foreach ($title->getRestrictions($action) as $restriction) {
			$errors = self::getOwnerDoErrors($title, $user, $restriction, $wgOwnerLevels);
			if (count($errors) > 0) {
				wfDebugLog('OwnerRight', "action {$action} on {$title->getPrefixedText()} is restricted to owner level {$restriction}, user {$user->getName()} is not the owner");
				$result = $errors;
				return false; // stop hook processing
			}
		}

		return true; // continue hook processing
	}

	/**
	 * When MediaWiki checks permissions for an owner action (defined in $wgOwnerActions),
	 * this hook checks whether the current user is the owner of has the corresponding bypass right.
	 * @param Title $title reference to the title in question (see the use in $IP/includes/Title.php)
	 * @param User $user reference to the current user (see the use in $IP/includes/Title.php)
	 * @param string $action action concerning the title in question
	 * @param mixed $result User permissions error to add. If none, return true.
	 * $result can be returned as a single error message key (string), or an
	 * array as one message key with parameters
	 * @return boolean true to continue hook processing or false to abort
	 * the processing of the hook.
	 */
	public static function hookGetUserPermissionsErrorsCheckAction(&$title, &$user, $action, &$result) {
		global $wgOwnerActions;
		$errors = self::getOwnerDoErrors($title, $user, $action, $wgOwnerActions);
		if (count($errors) > 0) {
			wfDebugLog('OwnerRight', "action {$action} is restricted to the owner, user {$user->getName()} is not the owner of {$title->getPrefixedDBkey()}");
			$result = $errors;
			return false; // stop hook processing
		}
		return true; // continue hook processing
	}

	/**
	 * 
	 * @param Title $title
	 * @param User $user
	 * @param array $errors
	 * @return boolean
	 */
	public static function hookCheckPageReadRestrictions($title, $user, &$errors) {
		if (OwnerRight::isOwner($user, $title)) {
			$errors = array();
			return false;
		}
		return true;
	}

	/**
	 * @param Title $title
	 * @param User $user
	 * @param string $do Action or restriction level
	 * @param array $context Owner actions or levels
	 * @return array
	 */
	public static function getOwnerDoErrors($title, $user, $do, $context) {
		$errors = array();
		if (array_key_exists($do, $context)) {
			// $do in the $context = need to check
			if (!$user->isAllowed($context[$do]) && !self::isOwner($user, $title)) {
				$errors = array(array('only-owner-can', $do));
			}
		}
		return $errors;
	}

	/**
	 * Checks the $user is the "owner" of the $title making sure he is the first revisioner.
	 * Hook "isOwner" can be used to override this behavior.
	 * @param User $user
	 * @param Title $title
	 * @return boolean
	 */
	public static function isOwner($user, $title) {
		$result = false;
		if (wfRunHooks('isOwner', array($title, $user, &$result))) {
			// no hook halts, so makes sure $user is the first revisioner of $title
			if ($user->isAnon()) {
				return false;
			}
			$firstRevision = $title->getFirstRevision();
			$owner = $firstRevision ? $firstRevision->getRawUser() : 0; // Fetch revision's user id
			$result = $owner == $user->getId();
		}
		return $result;
	}

}
