<?php

class Preserve extends HTMLForm {

	public static $ACTION = 'preserve';

	/**
	 * 
	 * @param Title $title
	 */
	public function __construct($title) {
		parent::__construct(self::buildFormDescriptor($title));

		$this->setTitle($title);
		$this->setAction($title->getLocalURL('action=' . self::$ACTION));
		$this->addHeaderText(wfMessage('preserve-headertext')->parse());
		$this->setMessagePrefix('preserve');
		$this->setSubmitCallback(array($this, 'processForm'));
		$this->setSubmitText(wfMessage('preserve-submittext')->text());
	}

	/**
	 * Process the submitted form.
	 */
	public function processForm() {
		wfDebugLog('Preserve', 'processForm()');
	}

	/**
	 * Build a form descriptor for a title.
	 * @global type $wgRestrictionLevels
	 * @param Title $title
	 * @return array
	 */
	protected static function buildFormDescriptor($title) {
		$formDescriptor = array();
		global $wgRestrictionLevels;

		foreach ($title->getRestrictionTypes() as $type) {

			$formDescriptor[$type] = array(
				'type' => 'radio',
				'label' => $type,
				'options' => array()
			);

			foreach ($wgRestrictionLevels as $level) {
				$formDescriptor[$type]['options'][$level] = $level;
			}

			// Pull the actual restriction from the DB (seen in ProtectionForm.php around line 98)
			// Currently, MediaWiki "protect" action form requires individual selections,
			// but the db allows multiples separated by commas.
			// This reproduce the same behavior.
			$formDescriptor[$type]['default'] = implode('', $title->getRestrictions($type));
		}

		return $formDescriptor;
	}

	/**
	 * Adds the Preserve action.
	 * @param SkinTemplate $skinTemplate
	 * @param array $links
	 * @return boolean
	 */
	public static function hookSkinTemplateNavigation(&$skinTemplate, &$links) {

		$title = $skinTemplate->getTitle();

		if (isset($title) && ( $title->getNamespace() != NS_SPECIAL ) && $title->exists()) {

			global $wgRequest;
			$links['actions'][self::$ACTION] = array(
				'class' => ( $wgRequest->getVal('action') == self::$ACTION) ? 'selected' : '',
				'text' => wfMessage('preserve-actiontext')->text(),
				'href' => $title->getLocalURL('action=' . self::$ACTION)
			);
		}
		return true;
	}

	/**
	 * Override MediaWiki::performAction(). Use this to do something completely 
	 * different, after the basic globals have been set up, but before ordinary 
	 * actions take place.
	 * Note: To prevent the standard performAction class from doing anything,
	 * return false from this hook.
	 * @param OutputPage $output
	 * @param Page $article
	 * @param Title $title
	 * @param User $user
	 * @param WebRequest $request
	 * @param MediaWiki $wiki
	 * @return boolean
	 */
	public static function hookMediaWikiPerformAction($output, $article, $title, $user, $request, $wiki) {

		if ($request->getVal('action') != self::$ACTION) {
			return true;
		}

		$form = new Preserve($title);
		$form->show();

		return false;
	}

}
