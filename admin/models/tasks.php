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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * ClubManagementList Model
 */
class NoKPrjMgntModelTasks extends JModelList {
	private $tableName = '#__nok_pm_tasks';
	private $tableAlias = 't';
	private $_userId2Login = array();
	private $_userLogin2Id = array();

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
			->from($db->quoteName($this->tableName,$this->tableAlias))
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

	public function getExImportTableName() {
		return $this->tableName;
	}

	public function getExImportPrimaryKey() {
		return 'id';
	}

	public function getExImportParentFieldName() {
		return 'project_id';
	}

	public function getExImportUniqueKeyFields() {
		return array('project_id','title');
	}

	public function getExImportForeignKeys() {
		return array(
			'responsible_user_id' => array('#__users','u1','id',array('username' => 'responsible_user_login'))
		);
	}

	public function getExportExcludeFields() {
		return array_merge(
			array($this->getExImportPrimaryKey()),
			array_keys($this->getExImportForeignKeys())
		);
	}

	public function getExportData($parentId='') {
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Set table
		$query->from($db->quoteName($this->tableName,$this->tableAlias));
		// Select fields to be exported
		$fields = array($this->tableAlias.'.*');
		foreach ($this->getExImportForeignKeys() as $fkKey => $fkProperty) {
			list($table, $talias, $pk, $uniqueFields) = $fkProperty;
			foreach ($uniqueFields as $uniqueField => $newFieldName) {
				if (empty($talias)) {
					array_push($fields, $uniqueField.' AS '.$newFieldName);
				} else {
					array_push($fields, $talias.'.'.$uniqueField.' AS '.$newFieldName);
				}
			}
			$query->join('LEFT', $db->quoteName($table,$talias).' ON ('.$db->quoteName($this->tableAlias.'.'.$fkKey).'='.$db->quoteName($talias.'.'.$pk).')');
		}
		$query->select($fields);
		if (!empty($parentId)) {
			$query->where($db->quoteName($this->tableAlias.'.project_id').' = '.$db->quote($parentId));
		}
		$db->setQuery($query);
		$rows = $db->loadAssocList();
		foreach(array_keys($rows) as $key) {
			if (!empty($rows[$key]['assign_user_ids'])) {
				$rows[$key]['assign_user_ids'] = $this->mapUserId2Login($rows[$key]['assign_user_ids']);
			}
		}
		return $rows;
	}

	public function importPreSave($data) {
		$data['assign_user_ids'] = $this->mapUserLogin2Id($data['assign_user_ids']);
		return $data;
	}

	private function mapUserId2Login($userIds, $delimiter=',') {
		$this->loadUserData();
		$userIdList = explode($delimiter,$userIds);
		$userLoginList = array();
		foreach($userIdList as $userId) {
			array_push($userLoginList,$this->_userId2Login[$userId]);
		}
		return implode($delimiter,$userLoginList);
	}

	private function mapUserLogin2Id($userLogins, $delimiter=',') {
		$this->loadUserData();
		$userLoginList = explode($delimiter,$userLogins);
		$userIdList = array();
		foreach($userLoginList as $userLogin) {
			if (isset($this->_userLogin2Id[$userLogin])) {
				array_push($userIdList,$this->_userLogin2Id[$userLogin]);
			}
		}
		return implode($delimiter,$userIdList);
	}

	private function loadUserData() {
		if ((count($this->_userId2Login) < 1) || (count($this->_userLogin2Id) < 1)) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('u.id','u.username')))
				->from($db->quoteName('#__users','u'));
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			foreach ($rows as $row) {
				$this->_userId2Login[$row['id']] = $row['username'];
				$this->_userLogin2Id[$row['username']] = $row['id'];
			}
		}
	}
}
?>
