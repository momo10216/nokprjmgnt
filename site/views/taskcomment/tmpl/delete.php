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
$model = $this->getModel();
// Delete record
$status = $model->delete($id);
if ($status !== false) {
	echo JText::_("COM_NOKPRJMGNT_DATA_DELETED");
} else {
	echo JText::_("COM_NOKPRJMGNT_DATA_DELETE_FAILED");
}
?>
