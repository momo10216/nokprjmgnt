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
 
// The class name must always be the same as the filename (in camel case)
class JFormFieldUserList extends JFormField {
	//The field class must know its own type through the variable $type.
	protected $type = 'userlist';
 
	public function getInput() {
		$fields = array();
		$multiple = '';
		if (isset($this->element["multiple"]) && ($this->element['multiple'] == 'true')) {
			$multiple = 'multiple ';
		} else {
			if (!isset($this->element["hide_none"]) || ($this->element["hide_none"] != "true")) {
				$fields[""] = JText::alt('COM_NOKPRJMGNT_DO_NOT_USE', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname));
			}
		}
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
		->select(array('u.id', 'u.name'))
		->from($db->quoteName('#__users','u'))
		->order($db->quoteName('u.name'));
		$db->setQuery($query);
		$results = $db->loadRowList();
		foreach($results as $result) {
			$fields[$result[0]] = $result[1];
		}
		if (is_array($this->value)) {
			$values = $this->value;
		} else {
			$values = array($this->value);
			foreach($values as $value) {
				if (!array_key_exists($this->value, $fields) && (empty($multiple) || !empty($this->value))) {
					$fields[$this->value] = $this->value;
				}
			}
		}
		$option = "";
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
