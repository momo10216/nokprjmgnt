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
 
jimport('joomla.form.formfield');
jimport('joomla.application.component.helper');

// The class name must always be the same as the filename (in camel case)
class JFormFieldPmProjectColumn extends JFormField {
	//The field class must know its own type through the variable $type.
	protected $type = 'pmprojectcolumn';
 
	public function getInput() {
		$param = JComponentHelper::getParams('com_nokprjmgnt');
		$fields = array(
			"" => JText::_('COM_NOKPRJMGNT_SELECT_FIELD'),
			"id" => JText::_('COM_NOKPRJMGNT_COMMON_FIELD_ID_LABEL'),
			"title" => JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_TITLE_LABEL'),
			"description" => JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_DESCRIPTION_LABEL'),
			"category_title" => JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_CATEGORY_LABEL'),
			"priority" => JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_PRIORITY_LABEL'),
			"duedate" => JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_DUE_DATE_LABEL'),
			"status" => JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_STATUS_LABEL'),
			"custom1" => $param->get('custom1'),
			"custom2" => $param->get('custom2'),
			"custom3" => $param->get('custom3'),
			"custom4" => $param->get('custom4'),
			"custom5" => $param->get('custom5'),
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
