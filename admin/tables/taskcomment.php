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
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Task table class
 */
class NoKPrjMgntTableTaskComment extends JTable {
	/**
	* Constructor
	*
	* @param object Database connector object
	*/
	function __construct(&$db) {
		parent::__construct('#__nok_pm_task_comments', 'id', $db);
	}

	/**
	 * Load a row
	 *
	 * @param   mixed    An optional primary key value to load the row by, or an array of fields to match.
	 *                   If not set the instance property value is used.
	 * @param   boolean  True to reset the default values before loading the new row.
	 *
	 * @return  boolean  True on success, false if row not found.
	 *
	 * @since   11.1
	 */
	public function load($keys = null, $reset = true) {
		$result = parent::load($keys,$reset);
		return $result;
	}

	/**
	 * Stores a row
	 *
	 * @param   boolean  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   11.1
	 */
	public function store($updateNulls = false) {
		JLoader::register('TableHelper', __DIR__.'/../helpers/table.php', true);
		TableHelper::updateCommonFieldsOnSave($this);
		return parent::store($updateNulls);
	}
}
?>
