<?php

class PreserveAction extends FormAction {

	protected static $ACTION = 'preserve';
	
	protected $restrictionLevels;
	protected $restrictionTypes;

	protected function __construct($page, $context = null) {
		parent::__construct($page, $context);
		$this->restrictionLevels = null;
		$this->restrictionTypes = null;
	}

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

	protected function getAccessibleRestrictionLevels() {
		if ($this->restrictionLevels == null) {

			global $wgRestrictionLevels, $wgPreserveRestrictionLevels, $wgPreserveShowAllLevels;

			if ($wgPreserveRestrictionLevels === true) {
				$this->restrictionLevels = $wgRestrictionLevels;
			} else {
				$this->restrictionLevels = array_intersect($wgPreserveRestrictionLevels, $wgRestrictionLevels);
			}

			$user = $this->getUser();

			if (!$wgPreserveShowAllLevels) {
				// seen in ProtectionForm.php buildSelector() around line 565
				// don't let them choose levels above their own (aka so they can still unprotect and edit the page)
				foreach ($this->restrictionLevels as $key => $level) {
					// seen in ProtectionForm.php buildSelector() around line 565
					// don't let them choose levels above their own (aka so they can still unprotect and edit the page)
					if ($level == 'sysop') {
						//special case, rewrite sysop to protect and editprotected
						if ($user->isAllowedAny('protect', 'editprotected'))
							continue;
					} else {
						if ($user->isAllowed($level))
							continue;
					}
					// hide the level
					unset($this->restrictionLevels[$key]);
				}
			}
		}

		return $this->restrictionLevels;
	}

	protected function getAllRestrictionTypes() {
		return $this->getTitle()->getRestrictionTypes();
	}

	protected function getVisibleRestrictionTypes() {
		if ($this->restrictionTypes == null) {

			global $wgPreserveRestrictionTypes;

			if ($wgPreserveRestrictionTypes === true) {
				$this->restrictionTypes = $this->getAllRestrictionTypes();
			} else {
				$this->restrictionTypes = array_intersect($wgPreserveRestrictionTypes, $this->getAllRestrictionTypes());
			}

		}
		return $this->restrictionTypes;
	}

	protected function getActionRestriction($action) {
		// Pull the actual restriction from the DB (seen in ProtectionForm.php around line 98)
		// Currently, MediaWiki "protect" action form requires individual selections,
		// but the db allows multiples separated by commas.
		// This reproduce the same behavior.
		return implode('', $this->getTitle()->getRestrictions($action));
	}

	protected function getAllRestrictions() {
		$restrictions = array();
		foreach($this->getAllRestrictionTypes() as $action) {
			$restrictions[$action] = $this->getActionRestriction($action);
		}
		return $restrictions;
	}

	protected function getFormFields() {
		$formDescriptor = array();

		foreach ($this->getVisibleRestrictionTypes() as $action) {
			$formDescriptor[$action] = $this->getActionField($action);
		}

		return $formDescriptor;
	}

	protected function getActionField($action) {

		$fieldDescriptor = array(
			'type' => 'radio',
			'section' => 'section-'.$action,
			'label-message' => 'restriction-' . $action,
			'options' => array()
		);

		$currentLevel = $this->getActionRestriction($action);
		$fieldDescriptor['default'] = $currentLevel;

		$accessibleLevels = $this->getAccessibleRestrictionLevels();

		global $wgPreserveDeselectableLevels;

		$actionIsEditable = 
				in_array($currentLevel, $accessibleLevels) 
				|| $wgPreserveDeselectableLevels === true 
				|| ( is_array($wgPreserveDeselectableLevels) && in_array($currentLevel, $wgPreserveDeselectableLevels) );

		if ($actionIsEditable) {

			// adds all accessible restriction levels
			foreach ($accessibleLevels as $level) {
				$fieldDescriptor['options'][$this->getRestrictionLevelText($level)] = $level;
			}
			// if necessary, adds the current level
			if (!in_array($currentLevel, $accessibleLevels)) {
				$fieldDescriptor['options'][$this->getRestrictionLevelText($currentLevel, true)] = $currentLevel;
			}

		} else {

			// we only add one disabled radio
			$fieldDescriptor['options'][$this->getRestrictionLevelText($currentLevel)] = $currentLevel;
			$fieldDescriptor['disabled'] = true;

		}

		return $fieldDescriptor;
	}

	/**
	 * 
	 * @param string $level A restriction level
	 * @param boolean $canDeselectOnly
	 * @return string parsed HTML
	 */
	protected function getRestrictionLevelText($level, $canDeselectOnly = false) {
		if ($level == '') {
			$msgKey = 'protect-default';
		} else {
			$msgKey = "protect-level-{$level}";
		}
		
		$msg = wfMessage($msgKey);
		
		if (!$msg->exists()) {
			$msg = wfMessage('protect-fallback', $level);
		}
		
		if ($canDeselectOnly) {
			$msg = wfMessage('preserve-wrapunsetonly', $msg->parse());
		}
		
		return $msg->parse();
	}

	protected function alterForm(HTMLForm $form) {
		// $form->setWrapperLegendMsg( 'filerevert-legend' );
		$form->addHeaderText(wfMessage('preserve-header')->parse());
		$form->setSubmitTextMsg('preserve-submit');
	}

	public function onSubmit($data) {
		$old = $this->getAllRestrictions();	
		$new = array_merge($old, $data);

		wfDebugLog('Preserve', 'onSubmit() OLD  (' . implode(', ', array_keys($old)) .') = ('.  implode(', ',$old).')');
		wfDebugLog('Preserve', 'onSubmit() DATA (' . implode(', ', array_keys($data)) .') = ('.  implode(', ',$data).')');
		wfDebugLog('Preserve', 'onSubmit() NEW  (' . implode(', ', array_keys($new)) .') = ('.  implode(', ',$new).')');

		// Update the article's restriction field, and leave a log entry.
		//  array "set of restriction keys"
		//  array "expiry per restriction type expiration"
		//  int "Set to false if cascading protection isn't allowed"
		//  string "reason"
		//  User "The user updating the restrictions"
		// return Status::newFatal( 'readonlytext', wfReadOnlyReason() );
		$cascade = false; // necessary because this parameter is passed by reference
		return $this->page->doUpdateRestrictions($new, array(), $cascade, 'Preserve', $this->getUser());
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
