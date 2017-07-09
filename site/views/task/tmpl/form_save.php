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
// Check for request forgeries.
JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
// Initialise variables.
$app = JFactory::getApplication();
$model = $this->getModel();
// Get the data from the form POST
$data = JRequest::getVar('jform', array(), 'post', 'array');
$uri = JFactory::getURI();
$id = $uri->getVar('id');
$redirect = $uri->getVar('redirect');
$projectId = $uri->getVar('project_id');
if (!$id) {
	// Now insert the loaded data to the database via a function in the model
	$status = $model->storeData($data, $projectId);
} else {
	// Now update the loaded data to the database via a function in the model
	$status = $model->storeData($data, $projectId, $id);
}
// Check if ok and display appropriate message.  This can also have a redirect if desired.
if ($status !== false) {
	JFactory::getApplication()->enqueueMessage(JText::_('COM_NOKPRJMGNT_DATA_SAVED'));
} else {
	JError::raiseError( 4711, JText::_('COM_NOKPRJMGNT_DATA_NOT_SAVED') );
}
if (!empty($redirect)) {
	JFactory::getApplication()->redirect($redirect);
}
?>
