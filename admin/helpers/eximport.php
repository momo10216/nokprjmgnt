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

/*
More Info http://php.net/manual/en/book.simplexml.php
*/

// No direct access
defined('_JEXEC') or die('Restricted access');
 
class ExImportHelper {
	private static $_modelStructure = array(
		'Projects' => array('Projects', 'Project', '#__nok_pm_projects', '', '', array(
			'Tasks' => array('Tasks', 'Task', '#__nok_pm_tasks', 'id', 'project_id', array())
		))
	);
	private static $_component = 'NoKPrjMgnt';

	public static function export() {
		$xmlRoot = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><'.self::$_component.'></'.self::$_component.'>');
		self::_exportList($xmlRoot,self::$_modelStructure);
		self::_saveXML($xmlRoot->asXML(),'export-'.date('Ymd').'.xml');
	}

	public static function import($xmltext) {
		$xml = new SimpleXMLElement($xmltext); 
	}


	private static function _exportList(&$xmlNode, $list, $parentRow=array()) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		foreach($list as $modelName => $exportProp) {
			$xmlList = $xmlNode->addChild($exportProp[0]);
			$model = JControllerLegacy::getInstance(self::$_component)->getModel($modelName);
			$query
				->select(array('*'))
				->from($db->quoteName($exportProp[2]));
			if (!empty($parentRow)) {
				$query->where($db->quoteName($exportProp[4]).' = '.$db->quote($parentRow[$exportProp[3]]));
			}
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			foreach($rows as $row) {
				$xmlEntry = $xmlList->addChild($exportProp[1]);
				foreach($row as $field => $value) {
					$xmlEntry->addAttribute($field,$value);
				}
				if (isset($exportProp[5]) && is_array($exportProp[5]) && (count($exportProp[5]) >0)) {
					self::_exportList($xmlEntry,$exportProp[5],$row);
				}
			}
		}
	}

	private static function _saveXML($xmltext, $filename) {
		header('Content-Type: text/xml; charset=utf-8');
		header('Content-Length: '.strlen($xmltext));
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Pragma: no-cache');
		print $xmltext;
		// Close the application.
		$app = JFactory::getApplication();
		$app->close();
	}
}
?>
