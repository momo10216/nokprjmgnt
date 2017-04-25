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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
jimport('joomla.application.component.helper');

// The class name must always be the same as the filename (in camel case)
class JFormFieldPmTaskColumn extends JFormField {
	//The field class must know its own type through the variable $type.
	protected $type = 'pmtaskcolumn';
 
	public function getInput() {
		$fields = array(
			"" => JText::_('COM_NOKPRJMGNT_SELECT_FIELD'),
			"id" => JText::_('COM_NOKPRJMGNT_COMMON_FIELD_ID_LABEL'),
			"project_id" => JText::_('COM_NOKPRJMGNT_TASK_FIELD_PROJECT_ID_LABEL'),
			"project_title" => JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_TITLE_LABEL'),
			"title" => JText::_('COM_NOKPRJMGNT_TASK_FIELD_TITLE_LABEL'),
			"description" => JText::_('COM_NOKPRJMGNT_TASK_FIELD_DESCRIPTION_LABEL'),
			"priority" => JText::_('COM_NOKPRJMGNT_TASK_FIELD_PRIORITY_LABEL'),
			"progress" => JText::_('COM_NOKPRJMGNT_TASK_FIELD_PROGRESS_LABEL'),
			"duedate" => JText::_('COM_NOKPRJMGNT_TASK_FIELD_DUE_DATE_LABEL'),
			"status" => JText::_('COM_NOKPRJMGNT_TASK_FIELD_STATUS_LABEL'),
			"responsible_user_id" => JText::_('COM_NOKPRJMGNT_TASK_FIELD_RESPONSIBLE_LABEL'),
			"assign_user_ids" => JText::_('COM_NOKPRJMGNT_TASK_FIELD_ASSIGN_LABEL'),
			"createdby" => JText::_('COM_NOKPRJMGNT_COMMON_FIELD_CREATEDBY_LABEL'),
			"createddate" => JText::_('COM_NOKPRJMGNT_COMMON_FIELD_CREATEDDATE_LABEL'),
			"modifiedby" => JText::_('COM_NOKPRJMGNT_COMMON_FIELD_MODIFIEDBY_LABEL'),
			"modifieddate" => JText::_('COM_NOKPRJMGNT_COMMON_FIELD_MODIFIEDDATE_LABEL')
		);
		$option = '';
		$multiple = '';
		if (isset($this->element['multiple']) && ($this->element['multiple'] == 'true')) {
			$multiple = 'multiple ';
		}
		if (is_array($this->value)) {
			$values = $this->value;
		} else {
			$values = array($this->value);
		}
		foreach(array_keys($fields) as $key) {
			$option .= '<option value="'.$key.'"';
			if (array_search($key,$values) !== false)  {
				$option .= ' selected';
			}
			$option .= '>'.$fields[$key].'</option>';
		}
		return '<select '.$multiple.'id="'.$this->id.'" name="'.$this->name.'">'.$option.'</select>';
        }
}
?>
