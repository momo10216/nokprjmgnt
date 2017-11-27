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

// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
// Include dependancy of the component helper
jimport('joomla.application.component.helper');
class NoKPrjMgntModelTasks extends JModelList {
	/**
	 * @since   1.6
	 */
	private $pk = '0';
	private $useAlias= true;
	private $_userList = array();
	private $_where = array();
	private $_sort = array();
	protected $view_item = 'tasks';
	protected $_item = null;
	protected $_membershipItems = null;
	protected $_model = 'tasks';
	protected $_component = 'com_nokprjmgnt';
	protected $_context = 'com_nokprjmgnt.tasks';

	private function getFields() {
		return array (
			"id" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_ID_LABEL',true),'`t`.`id`','right'),
			"project_id" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_PROJECT_ID_LABEL',true),'`t`.`project_id`','right'),
			"title" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_TITLE_LABEL',true),'`t`.`title`','left'),
			"project_title" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_TITLE_LABEL',true),'`p`.`title`','left'),
			"project_access" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_ACCESS_LABEL',true),'`p`.`access`','left'),
			"description" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_DESCRIPTION_LABEL',true),'`t`.`description`',''),
			"category_title" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_CATEGORY_LABEL',true),'`c`.`title`','left'),
			"priority" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_PRIORITY_LABEL',true),'`t`.`priority`','right'),
			"progress" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_PROGRESS_LABEL',true),'`t`.`progress`','left'),
			"duedate" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_DUE_DATE_LABEL',true),'`t`.`duedate`','left'),
			"status" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_STATUS_LABEL',true),'`t`.`status`','left'),
			"responsible_user_id" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_RESPONSIBLE_LABEL',true),'`t`.`responsible_user_id`','left'),
			"assign_user_ids" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_ASSIGN_LABEL',true),'`t`.`assign_user_ids`','left'),
			"createdby" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_CREATEDBY_LABEL',true),'`p`.`createdby`','left'),
			"createddate" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_CREATEDDATE_LABEL',true),'`p`.`createddate`','left'),
			"modifiedby" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_MODIFIEDBY_LABEL',true),'`p`.`modifiedby`','left'),
			"modifieddate" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_MODIFIEDDATE_LABEL',true),'`p`.`modifieddate`','left')
		);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication();
		$params = $app->getParams();
		$this->setState('params', $params);
		$this->setState('filter.published',1);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string    An SQL query
	 * @since   1.6
	 */
	protected function getListQuery() {
		$user = JFactory::getUser();
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields from the hello table
		$allFields = $this->getFields();
		$fields = array();
		foreach (array_keys($allFields) as $key) {
			if (isset($allFields[$key]) && !empty($allFields[$key])) {
				$field = $allFields[$key];
				array_push($fields,$field[1].' AS '.$key);
			}
		}
		$query->select($fields)
			->from($db->quoteName('#__nok_pm_tasks','t'))
			->join('LEFT', $db->quoteName('#__nok_pm_projects', 'p').' ON ('.$db->quoteName('t.project_id').'='.$db->quoteName('p.id').')')
			->join('LEFT', $db->quoteName('#__categories', 'c').' ON ('.$db->quoteName('p.catid').'='.$db->quoteName('c.id').')');
		// Get configurations
		$where = array();
		$sort = array();
		$this->paramsComponent = $this->state->get('params');
		$app = JFactory::getApplication();
		$currentMenu = $app->getMenu()->getActive();
		if (is_object($currentMenu)) {
			// Menu filter
			$this->paramsMenuEntry = $currentMenu->params;
			$statuslist = $this->paramsMenuEntry->get('status');
			if ((count($statuslist) > 0) && ((count($statuslist) > 1) || !empty($statuslist[0]))) {
				array_push($where,$db->quoteName('t.status').' IN ('.implode(',',$db->quote($statuslist)).')');
			}
			$catid = $this->paramsMenuEntry->get('catid');
			if ($catid != '0') {
				array_push($where,$db->quoteName('p.catid').' = '.$db->quote($catid));
			}
			$projectId = $this->paramsMenuEntry->get('project_id');
			if (!empty($projectId)) {
				array_push($where,$db->quoteName('t.project_id').' = '.$db->quote($projectId));
			}
			// Menu sort
			for ($i=1;$i<=4;$i++) {
				$fieldKeyCol = 'sort_column_'.$i;
				$fieldKeyDir = 'sort_direction_'.$i;
				$key = $this->paramsMenuEntry->get($fieldKeyCol);
				if (!empty($key)) {
					if (isset($allFields[$key]) && !empty($allFields[$key])) {
						$fieldname = $allFields[$key][1];
						if ($key == 'duedate') { $fieldname = 'IF(IFNULL('.$fieldname.",'0000-00-00 00:00:00')='0000-00-00 00:00:00','2999-12-31 00:00:00',".$fieldname.")"; }
						array_push($sort, $fieldname.' '.$this->paramsMenuEntry->get($fieldKeyDir));
					}
				}
			}
		}
		array_push($where, $db->quoteName('p.access').' IN ('.implode(',',$user->getAuthorisedViewLevels()).')');
		$where = array_merge($where, $this->_where);
		$sort = array_merge($sort, $this->_sort);
		// Use filter
		if (count($where) > 0) {
			$query->where(implode(' AND ',$where));
		}
		// Use sort
		if (count($sort) > 0) {
			$query->order(implode(', ',$sort));
		}
//echo $query;
		return $query;
	}

	public function getProjectFormItems($projectId) {
		$db = JFactory::getDBO();
		$this->_where = array($db->quoteName('t.project_id').' = '.$db->quote($projectId));
		$result = $this->getItems();
		$this->_where = array();
		return $result;
	}

	public function getUserItems() {
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$userId = $user->get('id');
		$where = '('.$db->quoteName('t.responsible_user_id').' = '.$db->quote($userId);
		$where .= "OR CONCAT(',',`t`.`assign_user_ids`,',') LIKE '%,".$userId.",%')";
		$this->_where = array($where);
		$result = $this->getItems();
		$this->_where = array();
		return $result;
	}

	public function getHeader($cols) {
		$fields = array();
		$allFields = $this->getFields();
		foreach ($cols as $col) {
			if (isset($allFields[$col])) {
				$field = $allFields[$col];
				$fields[$col] = $field[0];
			} else {
				$fields[$col] = $col;
			}
		}
		return $fields;
	}

	public function getAlign($cols) {
		$fields = array();
		$allFields = $this->getFields();
		foreach ($cols as $col) {
			if (isset($allFields[$col])) {
				$field = $allFields[$col];
				$fields[$col] = $field[2];
			}
		}
		return $fields;
	}

	public function translateFieldsToColumns($searchFields, $removePrefix=true) {
		$result = array();
		$allFields = $this->getFields();
		foreach($searchFields as $field) {
			if (isset($allFields[$field]) && !empty($allFields[$field])) {
				if ($removePrefix) {
					$resultField = str_replace('`p`.', '' , $allFields[$field][1]);
					$resultField = str_replace('`c`.', '' , $resultField);
					$resultField = str_replace('`', '' , $resultField);
					array_push($result,$resultField);
				} else {
					array_push($result,$allFields[$field][1]);
				}
			}
		}
		return $result;
	}

	public function getConvertUserIdsToNames($text) {
		if (count($this->_userList) < 1) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query
				->select(array('u.id', 'u.name'))
				->from($db->quoteName('#__users','u'));
			$db->setQuery($query);
			$results = $db->loadRowList();
			foreach($results as $result) {
				$this->_userList[$result[0]] = $result[1];
			}
			$this->_userList['0'] = '';
		}
		$userIds = explode(',',$text);
		$userNames = array();
		foreach($userIds as $userId) {
			if (!empty($userId)) {
				if (isset($this->_userList[$userId])) {
					array_push($userNames,$this->_userList[$userId]);
				} else {
					array_push($userNames,$userId);
				}
			}
		}
		return implode(',',$userNames);
	}
}
?>
