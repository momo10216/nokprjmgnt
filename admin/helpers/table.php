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
 
class TableHelper {
	public static static function updateCommonFieldsOnSave(&$table) {
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		$db	= JFactory::getDbo();
		if (empty($table->id)) {
				$table->createddate = $date->toSql();
				$table->createdby = $user->get('name');
		}
		$table->modifieddate = $date->toSql();
		$table->modifiedby = $user->get('name');
	}
}
?>
