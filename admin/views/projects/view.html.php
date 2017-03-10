<?php
/**
* @version	$Id$
* @package	Joomla
* @subpackage	NoK-PrjMgnt
* @copyright	Copyright (c) 2017 Norbert Kümin. All rights reserved.
* @license	http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
* @author	Norbert Kuemin
* @authorEmail	momo_102@bluemail.ch
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * Persons View
 */
class NoKPrjMgntViewProjects extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Persons view display method
	 * @return void
	 */
	function display($tpl = null)  {
		NoKPrjMgntHelper::addSidebar('projects');
		// Get data from the model
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		switch($this->getLayout()) {
			case "import":
				$this->addToolbarImport();
				break;
			default:
				$this->addToolbarList();
				break;
		}
		$this->sidebar = JHtmlSidebar::render();
		// Display the template
		parent::display($tpl);
	}

	protected function addToolbarList() {
		$canDo = JHelperContent::getActions('com_nokprjmgnt', 'category', $this->state->get('filter.category_id'));
		$user  = JFactory::getUser();
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('COM_NOKPRJMGNT_PROJECTS_TITLE'), 'stack todo');
		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('COM_NOKPRJMGNT', 'core.create'))) > 0 ) {
			JToolbarHelper::addNew('project.add');
		}
		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
			JToolbarHelper::editList('project.edit');
		}
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			JToolbarHelper::deleteList('', 'projects.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state')) {
			JToolbarHelper::trash('projects.delete');
		}
		// Add a export button
		JToolBarHelper::custom('projects.export', 'export.png', 'export_f2.png', JText::_('JTOOLBAR_EXPORT'), false);
		// Add a import button
		if ($user->authorise('core.create', 'COM_NOKPRJMGNT')) {
			JToolBarHelper::custom('todos.import', 'import.png', 'import_f2.png', JText::_('JTOOLBAR_IMPORT'), false);
		}
		if ($user->authorise('core.admin', 'COM_NOKPRJMGNT')) {
			JToolbarHelper::preferences('com_nokprjmgnt');
		}
		//JToolbarHelper::help('JHELP_CONTENT_ARTICLE_MANAGER');
	}

	protected function addToolbarImport() {
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('COM_NOKPRJMGNT_PROJECTS_TITLE'), 'stack todo');
		JToolBarHelper::custom('todos.import_cancel', 'cancel.png', 'cancel_f2.png', JText::_('JTOOLBAR_CLOSE'), false);
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields() {
		return array (
			'p.title' => JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_TITLE_LABEL'),
			'p.priority' => JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_PRIORITY_LABEL'),
			'p.duedate' => JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_DUE_DATE_LABEL')
		);
	}
}
?>