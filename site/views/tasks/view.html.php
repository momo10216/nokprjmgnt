<?php
/**
* @version	$Id$
* @package	Joomla
* @subpackage	NoK-ToDo
* @copyright	Copyright (c) 2017 Norbert Kümin. All rights reserved.
* @license	http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
* @author	Norbert Kuemin
* @authorEmail	momo_102@bluemail.ch
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;
class NoKPrjMgntViewTasks extends JViewLegacy {
	protected $items;
	protected $pageHeading = 'COM_NOKPRJMGNT_PAGE_TITLE_DEFAULT';
	protected $paramsComponent;
	protected $paramsMenuEntry;
	protected $user;
	protected $viewAccessLevels;
	protected $componentCanDo;

	function display($tpl = null) {
		$this->componentCanDo = JHelperContent::getActions('com_nokprjmgnt');
		// Init variables
		$this->state = $this->get('State');
		if ($this->getLayout() =='form') {
			$this->getModel()->setUseAlias(false);
		}
		$this->user = JFactory::getUser();
		$app = JFactory::getApplication();
		$this->document = JFactory::getDocument();
		if ($this->getLayout() == 'userlist'){
			$this->items = $this->getModel()->getUserItems();
			$this->setLayout('default');
		} else {
			$this->items = $this->get('Items');
		}
		$this->form = $this->get('Form');
		$this->paramsComponent = $this->state->get('params');
		$menu = $app->getMenu();
		if (is_object($menu)) {
			$currentMenu = $menu->getActive();
			if (is_object($currentMenu)) {
				$this->paramsMenuEntry = $currentMenu->params;
			}
		}
		$this->viewAccessLevels = JAccess::getAuthorisedViewLevels($this->user->id);
		// Init document
		JFactory::getDocument()->setMetaData('robots', 'noindex, nofollow');
		parent::display($tpl);
	}
}
?>