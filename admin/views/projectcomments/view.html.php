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
class NoKPrjMgntViewProjectComments extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Persons view display method
	 * @return void
	 */
	function display($tpl = null)  {
		NoKPrjMgntHelper::addSidebar('projectcomments');
		// Get data from the model
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		$this->addToolbarList(false, false);
		$this->sidebar = JHtmlSidebar::render();
		// Display the template
		parent::display($tpl);
	}

	protected function addToolbarList($allowNew = true, $allowEdit = true) {
		$canDo = JHelperContent::getActions('com_nokprjmgnt','project',$item->project_id);
		$user  = JFactory::getUser();
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('COM_NOKPRJMGNT_PROJECT_COMMENTS_TITLE'), 'stack todo');
		if ($canDo->get('core.edit')) {
			// only for project admins
			JToolbarHelper::publish('projectcomment.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('projectcomment.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			if ($this->state->get('filter.published') == -2) {
				JToolbarHelper::deleteList('', 'projectcomment.delete', 'JTOOLBAR_EMPTY_TRASH');
			} else {
				JToolbarHelper::trash('projectcomment.delete');
			}
		}
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
			'project' => JText::_('COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_PROJECT_LABEL'),
			'c.title' => JText::_('COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_TITLE_LABEL'),
			'c.createdate' => JText::_('COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_CREATE_DATE_LABEL')
		);
	}
}
?>
