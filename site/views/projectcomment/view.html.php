<?php
/**
* @version	$Id$
* @package	Joomla
* @subpackage	NoK-PrjMgnt
* @copyright	Copyright (c) 2017 Norbert Kuemin. All rights reserved.
* @license	http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
* @author	Norbert Kuemin
* @authorEmail	momo_102@bluemail.ch
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class NoKPrjMgntViewProjectComment extends JViewLegacy {
	protected $item;
	protected $pageHeading = 'COM_NOKPRJMGNT_PAGE_TITLE_DEFAULT';
	protected $paramsComponent;
	protected $paramsMenuEntry;
	protected $user;
	protected $viewAccessLevels;
	protected $canDo;

	function display($tpl = null) {
		// Init variables
		$this->state = $this->get('State');
		$this->user = JFactory::getUser();
		$app = JFactory::getApplication();
		$this->document = JFactory::getDocument();
		$id = JFactory::getURI()->getVar('id');
		if (!empty($id)) {
			$this->item = $this->getModel()->getItem($id);
			$this->canDo = JHelperContent::getActions('com_nokprjmgnt','project',$this->item->project_id);
		} else{
			$this->canDo = JHelperContent::getActions('com_nokprjmgnt');
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
		// Init document
		JFactory::getDocument()->setMetaData('robots', 'noindex, nofollow');
		parent::display($tpl);
	}
}
?>
