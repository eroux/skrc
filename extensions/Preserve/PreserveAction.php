<?php

class PreserveAction extends FormAction {

	protected static $ACTION = 'preserve';

	public function getName() {
		return self::$ACTION;
	}

	public function getRestriction() {
		return self::$ACTION;
	}

	protected function getPageTitle() {
		return $this->msg('preserve-title', $this->getTitle()->getText());
	}

	protected function getDescription() {
		return '';
	}

	protected function getFormFields() {
		$formDescriptor = array();

		$title = $this->getTitle();
		global $wgRestrictionLevels;

		foreach ($title->getRestrictionTypes() as $action) {

			$formDescriptor[$action] = array(
				'type' => 'radio',
				'section' => 'section-' . $action,
				'label-message' => 'restriction-' . $action,
				'options' => array()
			);

			foreach ($wgRestrictionLevels as $level) {
				$formDescriptor[$action]['options'][$this->getRestrictionLevelText($level)] = $level;
			}

			// Pull the actual restriction from the DB (seen in ProtectionForm.php around line 98)
			// Currently, MediaWiki "protect" action form requires individual selections,
			// but the db allows multiples separated by commas.
			// This reproduce the same behavior.
			$formDescriptor[$action]['default'] = implode('', $title->getRestrictions($action));
		}

		return $formDescriptor;
	}

	/**
	 * Prepare the label for a protection selector option
	 *
	 * @param string $level A restriction level
	 * @return string
	 */
	protected function getRestrictionLevelText($level) {
		if ($level == '') {
			return wfMessage('protect-default')->parse();
		} else {
			$msg = wfMessage("protect-level-{$level}");
			if ($msg->exists()) {
				return $msg->parse();
			}
			return wfMessage('protect-fallback', $level)->parse();
		}
	}

	protected function alterForm(HTMLForm $form) {
		// $form->setWrapperLegendMsg( 'filerevert-legend' );
		$form->addHeaderText(wfMessage('preserve-header')->parse());
		$form->setSubmitTextMsg('preserve-submit');
	}

	public function onSubmit($data) {
		wfDebugLog('Preserve', 'onSubmit() (' . implode(', ', array_keys($data)) .') = ('.  implode(', ',$data).')');
		// Update the article's restriction field, and leave a log entry.
		//  array "set of restriction keys"
		//  array "expiry per restriction type expiration"
		//  int "Set to false if cascading protection isn't allowed"
		//  string "reason"
		//  User "The user updating the restrictions"
		// return Status::newFatal( 'readonlytext', wfReadOnlyReason() );
		$cascade = false; // necessary because this parameter is passed by reference
		return $this->page->doUpdateRestrictions($data, array(), $cascade, 'Preserve', $this->getUser());
	}

	public function onSuccess() {
		wfDebugLog('Preserve', 'onSuccess()');
		$this->getOutput()->addWikiMsg('preserve-success', $this->getTitle()->getText());
		$this->getOutput()->returnToMain(false, $this->getTitle());
	}

	/**
	 * Adds the Preserve action to content navigation links.
	 * @param SkinTemplate $skinTemplate
	 * @param array $links
	 * @return boolean
	 */
	public static function hookSkinTemplateNavigation(&$skinTemplate, &$links) {

		$title = $skinTemplate->getRelevantTitle(); // getTitle() may return the special page like in move action
		$titleNamespace = $title->getNamespace();

		$user = $skinTemplate->getUser();

		if (isset($title) && (!in_array($titleNamespace, array(NS_SPECIAL, NS_MEDIAWIKI)) ) && $title->exists() && $title->quickUserCan(self::$ACTION, $user)) {

			global $wgRequest;
			$links['actions'][self::$ACTION] = array(
				'class' => ( $wgRequest->getVal('action') == self::$ACTION) ? 'selected' : false,
				'text' => wfMessage('preserve-action')->text(),
				'href' => $title->getLocalURL('action=' . self::$ACTION)
			);
		}

		return true;
	}

}
