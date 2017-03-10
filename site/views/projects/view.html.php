<?php
/**
* @version	$Id$
* @package	Joomla
* @subpackage	NoK-ToDo
* @copyright	Copyright (c) 2017 Norbert K�min. All rights reserved.
* @license	http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
* @author	Norbert Kuemin
* @authorEmail	momo_102@bluemail.ch
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;
class NoKPrjMgntViewProjects extends JViewLegacy {
	protected $item;
	protected $pageHeading = 'COM_NOKPRJMGNT_PAGE_TITLE_DEFAULT';
	protected $paramsComponent;
	protected $paramsMenuEntry;
	protected $user;

	function display($tpl = null) {
		// Init variables
		$this->state = $this->get('State');
		if ($this->getLayout() =='form') {
			$this->getModel()->setUseAlias(false);
			$uri = JFactory::getURI();
			$id = $uri->getVar('id');
			if (!$id) $id = JRequest::getVar('id');
			if (!$id) $id = $this->state->get('project.id');
			if (!$id) {
				$this->idList = $this->getModel()->getProjectListForCurrentUser();
			} else {
				$this->idList = array($id);
			}
			if (count($this->idList) == 1) $this->getModel()->setPk($this->idList[0]);
		}
		$this->user = JFactory::getUser();
		$app = JFactory::getApplication();
		$this->document = JFactory::getDocument();
		$this->items = $this->get('Items');
		$this->form = $this->get('Form');
		$menu = $app->getMenu();
		if (is_object($menu)) {
			$currentMenu = $menu->getActive();
			if (is_object($currentMenu)) {
				$this->paramsMenuEntry = $currentMenu->params;
			}
		}
		// Init document
		JFactory::getDocument()->setMetaData('robots', 'noindex, nofollow');
		parent::display($tpl);
	}
}
?>