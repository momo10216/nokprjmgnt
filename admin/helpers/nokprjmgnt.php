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

class NoKPrjMgntHelper extends JHelperContent {
	public static function addSubmenu($vName) {
		JHtmlSidebar::addEntry(
			JText::_('COM_NOKPRJMGNT_MENU_PROJECTS'),
			'index.php?option=com_nokprjmgnt&view=projects',
			$vName == 'projects'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_NOKPRJMGNT_MENU_PROJECT_COMMENTS'),
			'index.php?option=com_nokprjmgnt&view=projectcomments',
			$vName == 'projectcomments'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_NOKPRJMGNT_MENU_TASKS'),
			'index.php?option=com_nokprjmgnt&view=tasks',
			$vName == 'tasks'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_NOKPRJMGNT_MENU_TASK_COMMENTS'),
			'index.php?option=com_nokprjmgnt&view=taskcomments',
			$vName == 'taskcomments'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_NOKPRJMGNT_MENU_CATEGORIES'),
			'index.php?option=com_categories&view=categories&extension=com_nokprjmgnt',
			$vName == 'categories'
		);
	}
}
?>
