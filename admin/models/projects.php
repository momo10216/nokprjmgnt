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
	private $_groupId2Title = array();
	private $_groupTitle2Id = array();
	private $_assetRule = '';
	private $_componentAssetId = '';
	
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
				$assetRow = $this->_getAssetRowByName('com_nokprjmgnt.project.'.$row['id']);
				$json = $assetRow['rules'];
				$row['asset_rules'] = $this->_mapGroupId2Title($json);
				unset($row['asset_id']);
				$rows[$key] = $row;
			}
		}
		return $rows;
	}

	public function importPreSave($row) {
		$this->_assetRules = $row['asset_rules'];
		unset($row['asset_rules']);
		return $row;
	}

	public function importPostSave($row, $id) {
		if (empty($this->_componentAssetId)) {
			$assetRow = $this->_getAssetRowByName('com_nokprjmgnt');
			$this->_componentAssetId = $assetRow['id'];
		}
		$this->_setAssetRulesById($row['asset_id'],$this->_componentAssetId,'com_nokprjmgnt.project.'.$id, $row['title'], $this->_mapGroupTitle2Id($this->_assetRules));
	}

	private function _mapGroupId2Title($json) {
		if (!empty($json)) {
			$this->_loadGroupData();
			$rules = json_decode($json,true);
			if (!empty($rules)) {
				foreach($rules as $key => $rule) {
					$newRule = array();
					foreach($rule as $groupId => $value) {
						if(isset($this->_groupId2Title[$groupId])) {
							$newRule[$this->_groupId2Title[$groupId]] = $value;
						}
					}
					$rules[$key] = $newRule;
				}
				return json_encode($rules);
			}
		}
		return '';
	}

	private function _mapGroupTitle2Id($json) {
		if (!empty($json)) {
			$this->_loadGroupData();
			$rules = json_decode($json,true);
			if (!empty($rules)) {
				foreach($rules as $key => $rule) {
					$newRule = array();
					foreach($rule as $viewlevelTitle => $value) {
						$newRule[$this->_groupTitle2Id[$viewlevelTitle]] = $value;
					}
					$rules[$key] = $newRule;
				}
				return json_encode($rules);
			}
		}
		return '';
	}

	private function _getAssetRowByName($name) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('a.*')
				->from($db->quoteName('#__assets','a'))
				->where($db->quoteName('a.name').'='.$db->quote($name));
			$db->setQuery($query);
			$result = $db->loadAssocList();
			if ($result) { return $result[0]; }
			return false;
	}
	
	private function _setAssetRulesById($assetId, $parentAssetId, $name, $title, $json) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$fields = array(
			$db->quoteName('a.parent_id').'='.$db->quote($parentAssetId),
			$db->quoteName('a.name').'='.$db->quote($name),
			$db->quoteName('a.title').'='.$db->quote($title),
			$db->quoteName('a.rules').'='.$db->quote($json)
		);
		$query
			->update($db->quoteName('#__assets','a'))
			->set($fields)
			->where($db->quoteName('a.id').'='.$db->quote($assetId));
		$db->setQuery($query);
		$db->query();
	}

	private function _loadGroupData() {
		if ((count($this->_groupId2Title) < 1) || (count($this->_groupTitle2Id) < 1)) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('g.id','g.title')))
				->from($db->quoteName('#__usergroups','g'));
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			foreach ($rows as $row) {
				$this->_groupId2Title[$row['id']] = $row['title'];
				$this->_groupTitle2Id[$row['title']] = $row['id'];
			}
		}
	}
}
?>
