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
class NoKPrjMgntModelTaskComments extends JModelList {
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
	protected $_model = 'comments';
	protected $_component = 'com_nokprjmgnt';
	protected $_context = 'com_nokprjmgnt.comments';

	private function getFields() {
		return array (
			"id" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_ID_LABEL',true),'`t`.`id`','right'),
			"project_id" => array(JText::_('COM_NOKPRJMGNT_TASK_COMMENT_FIELD_PROJECT_LABEL',true),'`t`.`id`','right'),
			"task_id" => array(JText::_('COM_NOKPRJMGNT_TASK_COMMENT_FIELD_TASK_LABEL',true),'`t`.`id`','right'),
			"title" => array(JText::_('COM_NOKPRJMGNT_TASK_COMMENT_FIELD_TITLE_LABEL',true),'`t`.`description`',''),
			"description" => array(JText::_('COM_NOKPRJMGNT_TASK_COMMENT_FIELD_DESCRIPTION_LABEL',true),'`t`.`description`',''),
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
			->from($db->quoteName('#__nok_pm_task_comments','c'))
			->join('LEFT', $db->quoteName('#__nok_pm_tasks', 't').' ON ('.$db->quoteName('c.task_id').'='.$db->quoteName('t.id').')');
			->join('LEFT', $db->quoteName('#__nok_pm_projects', 'p').' ON ('.$db->quoteName('t.project_id').'='.$db->quoteName('p.id').')');
		// Get configurations
		$where = array();
		$sort = array();
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
}
?>
