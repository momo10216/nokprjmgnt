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

JLoader::register('NoKPrjMgntHelper', __DIR__.'/helpers/nokprjmgnt.php', true);

// Get an instance of the controller prefixed by ClubManagement
$controller = JControllerLegacy::getInstance('NoKPrjMgnt');
 
// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->get('task'));
 
// Redirect if set by the controller
$controller->redirect();
?>
