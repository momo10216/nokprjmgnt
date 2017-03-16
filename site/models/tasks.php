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
defined('_JEXEC') or die;
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
// Include dependancy of the component helper
jimport('joomla.application.component.helper');
class NoKPrjMgntModelProjects extends JModelList {
	/**
	 * @since   1.6
	 */
	private $pk = '0';
	private $useAlias= true;
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
			"id" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_ID_LABEL',true),'`t`.`id`'),
			"title" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_TITLE_LABEL',true),'`t`.`title`'),
			"project_title" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_TITLE_LABEL',true),'`t`.`title`'),
			"description" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_DESCRIPTION_LABEL',true),'`t`.`description`'),
			"category_title" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_CATEGORY_LABEL',true),'`c`.`title`'),
			"priority" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_PRIORITY_LABEL',true),'`t`.`priority`'),
			"duedate" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_DUE_DATE_LABEL',true),'`t`.`duedate`'),
			"status" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_STATUS_LABEL',true),'`t`.`status`'),
			"createdby" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_CREATEDBY_LABEL',true),'`p`.`createdby`'),
			"createddate" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_CREATEDDATE_LABEL',true),'`p`.`createddate`'),
			"modifiedby" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_MODIFIEDBY_LABEL',true),'`p`.`modifiedby`'),
			"modifieddate" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_MODIFIEDDATE_LABEL',true),'`p`.`modifieddate`')
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
			->join('LEFT', $db->quoteName('#__nok_pm_projects', 'c').' ON ('.$db->quoteName('t.project_id').'='.$db->quoteName('p.id').')')
			->join('LEFT', $db->quoteName('#__categories', 'c').' ON ('.$db->quoteName('p.catid').'='.$db->quoteName('c.id').')')
			->where('t.project_id = ' . (int) $pk);
		// Get configurations
		$where = array();
		$sort = array();
		$this->paramsComponent = $this->state->get('params');
		$app = JFactory::getApplication();
		$currentMenu = $app->getMenu()->getActive();
		if (is_object( $currentMenu )) {
			// Menu filter
			$this->paramsMenuEntry = $currentMenu->params;
			$statuslist = $this->paramsMenuEntry->get('status');
			if (count($statuslist) > 0) {
				array_push($where,$db->quoteName('t.status').' IN ('.implode(',',$db->quote($statuslist)).')');
			}
			$catid = $this->paramsMenuEntry->get('catid');
			if ($catid != '0') {
				array_push($where,$db->quoteName('p.catid').' = '.$db->quote($catid));
			}
			// Menu sort
			for ($i=1;$i<=4;$i++) {
				$fieldKeyCol = 'sort_column_'.$i;
				$fieldKeyDir = 'sort_direction_'.$i;
				$key = $this->paramsMenuEntry->get($fieldKeyCol);
				if (!empty($key)) {
					if (isset($allFields[$key]) && !empty($allFields[$key])) {
						$fieldname = $allFields[$key][1];
						array_push($sort, $fieldname.' '.$this->paramsMenuEntry->get($fieldKeyDir));
					}
				}
			}
		}
		$where = array_merge($where, $this->_where);
		$sort = array_merge($where, $this->_sort);
		if (!empty($this->projectId)) {
			array_push($where,$db->quoteName('t.project_id').' = '.$db->quote($this->projectId));
		}
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
		$this->_where = array($db->quoteName('t.project_id').' = '.$db->quoteName($projectId));
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
				array_push($fields,$field[0]);
			} else {
				array_push($fields,$col);
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
}
?>