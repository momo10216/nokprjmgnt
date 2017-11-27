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

function showError($msg) {
	echo $msg;
}
$task = JRequest::getVar('task');
switch ($task) {
	case 'save':
		echo $this->loadTemplate('save');
		break;
	case 'cancel':
		showError(JText::_('COM_NOKPRJMGNT_DATA_NOT_SAVED'));
		break;
	case 'edit':
	default:
		$uri = JFactory::getURI();
		$id = $uri->getVar('id');
		echo $this->loadTemplate('edit');
		break;
}
?>
