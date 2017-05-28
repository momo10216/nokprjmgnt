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
class NoKPrjMgntModelTaskComments extends JModelList {
	private $tableName = '#__nok_pm_task_comments';
	private $tableAlias = 'c';
	private $_userId2Login = array();
	private $_userLogin2Id = array();

	public function __construct($config = array()) {
		if (!isset($config['filter_fields']) || empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'c.id',
				'project_id', 't.project_id',
				'task_id', 'c.task_id',
				'published', 'c.published',
				'title', 'c.title',
				'project', 'p.title',
				'createddate', 'c.createddate',
				'createdby', 'c.createdby'
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
		parent::populateState('c.title', 'asc');
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
			->select(array('c.id', 'c.title', 'c.published', 'c.createddate', 'c.createdby', 'c.task_id', 't.project_id', 't.title AS task', 'p.title AS project'))
			->from($db->quoteName($this->tableName,$this->tableAlias))
			->join('LEFT', $db->quoteName('#__nok_pm_tasks', 't').' ON ('.$db->quoteName('c.task_id').'='.$db->quoteName('t.id').')');
			->join('LEFT', $db->quoteName('#__nok_pm_projects', 'p').' ON ('.$db->quoteName('t.project_id').'='.$db->quoteName('p.id').')');
		// special filtering (houshold, excludeid).
		$app = JFactory::getApplication();
		// Filter by search in name.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('c.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(c.title LIKE ' . $search . ' OR t.title LIKE ' . $search . ' OR p.title LIKE ' . $search . ')');
			}
		}
		// Add the list ordering clause.
		$orderColText = $this->state->get('list.ordering', 'c.createddate');
		$orderDirn = $this->state->get('list.direction', 'desc');
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
		return array();
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
			$query->where($db->quoteName($this->tableAlias.'.task_id').' = '.$db->quote($parentId));
		}
		$db->setQuery($query);
		$rows = $db->loadAssocList();
		return $rows;
	}
}
?>