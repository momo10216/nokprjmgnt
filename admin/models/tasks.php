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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * ClubManagementList Model
 */
class NoKPrjMgntModelTasks extends JModelList {
	public function __construct($config = array()) {
		if (!isset($config['filter_fields']) || empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 't.id',
				'title', 't.title',
				'priority', 't.priority',
				'duedate', 't.duedate',
				'status', 't.status',
				'createddate', 't.createddate',
				'createdby', 't.createdby'
			);
			$app = JFactory::getApplication();
		}
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication();
		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout')) {
			$this->context .= '.' . $layout;
		}
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		// List state information.
		parent::populateState('t.title', 'asc');
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery() {
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields from the hello table
		$query
			->select(array('t.id', 't.title', 't.priority', 't.duedate', 't.status', 'p.title AS project'))
			->from($db->quoteName('#__nok_pm_tasks','t'))
			->join('LEFT', $db->quoteName('#__nok_pm_projects', 'p').' ON ('.$db->quoteName('t.project_id').'='.$db->quoteName('p.id').')');
		// special filtering (houshold, excludeid).
		$app = JFactory::getApplication();
		// Filter by search in name.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('t.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(t.title LIKE ' . $search . ')');
			}
		}
		// Add the list ordering clause.
		$orderColText = $this->state->get('list.ordering', 'p.name,p.firstname');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$orderCols = explode(",",$orderColText);
		$orderEntry = array();
		foreach ($orderCols as $orderCol) {
			array_push($orderEntry,$db->escape($orderCol . ' ' . $orderDirn));
		}
		$query->order(implode(", ",$orderEntry));
		return $query;
	}

	public function getExImportPrimaryKey() {
		return 'id';
	}

	public function getExImportForeignKeys() {
		return array(
			'responsible_user_id' => array('#__users','u','id',array('username' => 'responsible_user_login'))
		);
	}

	public function getExportExcludeFields() {
		return array_merge(
			array('id', 'project_id'),
			array_keys($this->getExImportForeignKeys())
		);
	}

	public function getExportQuery($parentId='') {
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Set table
		$query->from($db->quoteName('#__nok_pm_tasks','t'));
		// Select fields to be exported
		$fields = array('t.*');
		foreach ($this->getExImportForeignKeys() as $fkKey => $fkProperty) {
			list($table, $talias, $pk, $uniqueFields) = $fkProperty;
			foreach ($uniqueFields as $uniqueField => $newFieldName) {
				array_push($fields, $talias.'.'.$uniqueField.' AS '.$newFieldName);
			}
			$query->join('LEFT', $db->quoteName($table,$talias).' ON ('.$db->quoteName('t.'.$fkKey).'='.$db->quoteName($talias.'.'.$pk).')');
		}
		$query->select($fields);
		if (!empty($parentId)) {
			$query->where($db->quoteName('project_id').' = '.$db->quote($parentId));
		}
echo $query;
		return $query;
	}
}
?>