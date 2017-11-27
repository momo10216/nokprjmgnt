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

class NoKPrjMgntViewTask extends JViewLegacy {
	protected $form;
	protected $item;
	protected $state;
	protected $canDo;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$this->form	= $this->get('Form');
		$this->item	= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo	= NoKPrjMgntHelper::getActions('com_nokprjmgnt', 'task', $this->item->id);
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar() {
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		// Built the actions for new and existing records.
		$canDo		= $this->canDo;
		JToolbarHelper::title(JText::_('COM_NOKPRJMGNT_TASKS_PAGE_'.($isNew ? 'ADD' : 'EDIT')), 'pencil-2 article-add');

		// For new records, check the create permission.
		if ($isNew && $canDo->get('core.create')) {
			JToolbarHelper::apply('task.apply');
			JToolbarHelper::save('task.save');
			JToolbarHelper::save2new('task.save2new');
			JToolbarHelper::cancel('task.cancel');
		} else {
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId)) {
				JToolbarHelper::apply('task.apply');
				JToolbarHelper::save('task.save');
				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create')) {
					JToolbarHelper::save2new('task.save2new');
				}
			}
			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				JToolbarHelper::save2copy('task.save2copy');
			}
			JToolbarHelper::cancel('task.cancel', 'JTOOLBAR_CLOSE');
		}
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COM_NOKPRJMGNT_TASK_MANAGER_EDIT');
	}
}
?>
