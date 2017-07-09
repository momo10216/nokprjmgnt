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
defined('_JEXEC') or die; // no direct access
// Initialise variables.
$uri = JFactory::getURI();
$id = $uri->getVar('id');
$redirect = $uri->getVar('redirect');
$model = $this->getModel();
// Delete record
$status = $model->delete($id);
if ($status !== false) {
	JFactory::getApplication()->enqueueMessage(JText::_('COM_NOKPRJMGNT_DATA_DELETED'));
} else {
	JError::raiseError( 4711, JText::_('COM_NOKPRJMGNT_DATA_DELETE_FAILED') );
}
if (!empty($redirect)) {
	JFactory::getApplication()->redirect($redirect);
}
 ?>