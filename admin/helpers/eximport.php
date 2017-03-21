<?php
/**
* @version	$Id$
* @package	Joomla
* @subpackage	NoK-PrjMgnt
* @copyright	Copyright (c) 2017 Norbert K�min. All rights reserved.
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
		'Projects' => array('Projects', 'Project', array(
			'Tasks' => array('Tasks', 'Task', array())
		))
	);
	private static $_component = 'NoKPrjMgnt';

	public static function export() {
		$xmlRoot = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><'.self::$_component.'></'.self::$_component.'>');
		self::_exportList($xmlRoot,self::$_modelStructure);
		self::_saveXML($xmlRoot->asXML(),'export-'.date('Ymd').'.xml');
	}

	public static function import($xmltext) {
		$xmlRoot = new SimpleXMLElement($xmltext);
		self::_importList($xmlRoot,self::$_modelStructure);
	}

	private static function _exportList(&$xmlNode, $list, $parentId='') {
		$db = JFactory::getDBO();
		foreach($list as $modelName => $exportProp) {
			list($listName, $entryName, $childs) = $exportProp;
			$xmlList = $xmlNode->addChild($listName);
			$model = JControllerLegacy::getInstance(self::$_component)->getModel($modelName);
			$query = $model->getExportQuery($parentId);
			$excludeFields = $model->getExportExcludeFields();
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			foreach($rows as $row) {
				$xmlEntry = $xmlList->addChild($entryName);
				foreach($row as $field => $value) {
					if (array_search($field,$excludeFields) === false) {
						$xmlEntry->addAttribute($field,$value);
					}
				}
				if (isset($childs) && is_array($childs) && (count($childs)>0)) {
					self::_exportList($xmlEntry,$childs,$row['id']);
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

	private static function _importList(&$xmlNode, $list, $parentRow=array()) {
		foreach ($xmlNode->children() as $listChild) {
			list($modelName,$importProp) = self::_getModelEntryByListName($list,$listChild->getName());
			if (!empty($modelName) && (count($importProp)>0)) {
				foreach ($listChild->children() as $entryChild) {
				}
			}
		}
	}

	private static function _getModelEntryByListName($list, $name) {
		foreach($list as $modelName => $importProp) {
			list($listName,$entryName,$childs) = $importProp;
			if ($listName == $name) {
				return array($modelName,$importProp);
			}
			if (isset($childs) && is_array($childs) && (count($childs)>0)) {
				list($resultModel, $resultImportProp) = self::_getModelEntryByListName($child,$name);
				if (!empty($resultModel)) { return array($resultModel, $resultImportProp); }
			}
		}
		return array('',array());
	}
}
?>
