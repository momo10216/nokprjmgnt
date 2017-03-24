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
class NoKPrjMgntModelProjects extends JModelList {
	private $tableName = '#__nok_pm_projects';
	private $tableAlias = 'p';
	private $_viewlevelId2Title = array();
	private $_viewlevelTitle2Id = array();
	private $_assetRule = '';
	
	public function __construct($config = array()) {
		if (!isset($config['filter_fields']) || empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'p.id',
				'title', 'p.title',
				'category', 'c.title',
				'priority', 'p.priority',
				'duedate', 'p.duedate',
				'status', 'p.status',
				'createddate', 'p.createddate',
				'createdby', 'p.createdby'
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
		parent::populateState('p.title', 'asc');
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
			->select(array('p.id', 'p.title', 'c.title AS category', 'p.priority', 'p.duedate', 'p.status', 'p.createddate', 'p.createdby'))
			->from($db->quoteName('#__nok_pm_projects','p'))
			->join('LEFT', $db->quoteName('#__categories', 'c').' ON ('.$db->quoteName('p.catid').'='.$db->quoteName('c.id').')');
		// special filtering (houshold, excludeid).
		$app = JFactory::getApplication();
		// Filter by search in name.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('p.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(p.title LIKE ' . $search . ')');
			}
		} else {
			if (!empty($whereExt)) {
				$query->where($whereExt);
			}
		}
		// Add the list ordering clause.
		$orderColText = $this->state->get('list.ordering', 'p.title');
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
		return '';
	}

	public function getExImportUniqueKeyFields() {
		return array('title');
	}

	public function getExImportForeignKeys() {
		return array(
			'access' => array('#__viewlevels','v','id',array('title' => 'access_title')),
			'catid' => array('#__categories','c','id',array('alias' => 'category_alias'))
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
		$db->setQuery($query);
		$rows = $db->loadAssocList();
		foreach($rows as $key => $row) {
			if (!empty($row['asset_id'])) {
				$json = $this->_getAssetRulesById($row['asset_id']);
				$row['asset_rules'] = $this->_mapVewlevelsId2Title($json);
				unset($row['asset_id']);
			}
		}
		return $rows;
	}

	public function importPreSave($row) {
		$this->_assetRules = $row['asset_rules'];
		unset($row['asset_rules']);
		return $row;
	}

	public function importPostSave($id, $row) {
		$this->_setAssetRulesByName('com_nokprjmgnt.project.'.$id, $this->_mapVewlevelsId2Title($this->_assetRules));
	}

	private function _mapVewlevelsId2Title($json) {
		$this->_loadViewData();
		$rules = json_decode($json,true);
		foreach($rules as $key => $rule) {
			$newRule = array();
			foreach($rule as $viewlevelId => $value) {
				$newRule[$this->_viewlevelId2Title[$viewlevelId]] = $value;
			}
			$rules[$key] = $newRule;
		}
		return json_encode($rules);
	}

	private function _mapVewlevelsTitle2Id($json) {
		$this->_loadViewData();
		$rules = json_decode($json,true);
		foreach($rules as $key => $rule) {
			$newRule = array();
			foreach($rule as $viewlevelTitle => $value) {
				$newRule[$this->_viewlevelTitle2Id[$viewlevelTitle]] = $value;
			}
			$rules[$key] = $newRule;
		}
		return json_encode($rules);
	}

	private function _getAssetRulesById($id) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('a.rules')))
				->from($db->quoteName('#__assets','a'))
				->where($db->quoteName('a.id').'='.$db->quote($id));
			$db->setQuery($query);
			$result = $db->loadAssocList();
			if ($result) { return $result[0][0]; }
			return false;
	}
	
	private function _setAssetRulesByName($name, $json) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query
				->update($db->quoteName('#__assets','a'))
				->set($db->quoteName('a.rules').'='.$db->quote($json))
				->where($db->quoteName('a.name').'='.$db->quote($name));

			$db->setQuery($query);
			$db->query();
	}

	private function _loadViewData() {
		if ((count($this->_viewlevelId2Title) < 1) || (count($this->_viewlevelTitle2Id) < 1)) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('v.id','v.title')))
				->from($db->quoteName('#__viewlevels','v'));
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			foreach ($rows as $row) {
				$this->_viewlevelId2Title[$row['id']] = $row['title'];
				$this->_viewlevelTitle2Id[$row['title']] = $row['id'];
			}
		}
	}
}
?>