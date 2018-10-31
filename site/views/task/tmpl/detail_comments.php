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

//TODO: Make this configurable
$metaColumns = array('createdby','createddate');

// Prepare
$component = 'com_nokprjmgnt';
$commentModelName = 'TaskComments';
$view = 'taskcomment';
$currentUser = JFactory::getUser()->get('name');
$commentModel = JControllerLegacy::getInstance('NoKPrjMgnt')->getModel($commentModelName);
$commentModel->setSort(array('`c`.`createddate` ASC'));
$commentModel->setWhere(array('c.task_id='.$this->item->id));
$commentItems = $commentModel->getItems();
$commentHeader = $commentModel->getHeader($metaColumns);
$projectId = $this->item->project_id;
$taskId = $this->item->id;
$uriEdit = new JURI(JURI::Root().'/index.php');
$uriEdit->setVar('layout','form');
$uriEdit->setVar('Itemid','');
$uriEdit->setVar('view',$view);
$uriEdit->setVar('option',$component);
$uriEdit->setVar('project_id',$projectId);
$uriEdit->setVar('task_id',$taskId);
$uriEdit->setVar('redirect',urlencode(JFactory::getURI()->toString()));
$uriDelete = new JURI(JURI::Root().'/index.php');
$uriDelete->setVar('layout','delete');
$uriDelete->setVar('Itemid','');
$uriDelete->setVar('view',$view);
$uriDelete->setVar('option',$component);
$uriDelete->setVar('redirect',urlencode(JFactory::getURI()->toString()));
$add = JHelperContent::getActions('com_nokprjmgnt','project',$projectId)->get('core.edit');

// Items
if (is_array($commentItems) && (count($commentItems)>0)) {
	$deleteConfirmMsg = JText::_('COM_NOKPRJMGNT_COMMENT_CONFIRM_DELETE');
	foreach($commentItems as $item) {
		$row = (array) $item;
		echo "<div class=\"comment\">\n";
		echo "\t<h2 class=\"commenttitle\">".$row['title']."</h2>\n";
		echo "\t<div class=\"commentmeta\">";
		foreach ($metaColumns as $metaCol) {
			if (isset($commentHeader[$metaCol]) && !empty($commentHeader[$metaCol])) {
				echo $commentHeader[$metaCol].':';
				if (isset($row[$metaCol]) && !empty($row[$metaCol])) {
					echo $row[$metaCol];
				}
			} else {
				if (isset($row[$metaCol]) && !empty($row[$metaCol])) {
					echo $row[$metaCol];
				}
			}
			echo ' ';
		}
		if ($currentUser == $row['createdby']) {
			$uriEdit->setVar('id',$item->id);
			echo '<a style="text-decoration: none;" href="'.$uriEdit->toString().'"><span class="icon-edit"></span></a> ';
			$uriDelete->setVar('id',$item->id);
			echo '<a style="text-decoration: none;" href="'.$uriDelete->toString().'" onClick="return confirm(\''.$deleteConfirmMsg.'\');"><span class="icon-trash"></span></a>';
		}
		echo "</div>\n";
		echo "\t<div class=\"commenttext\">".$row['description']."</div>\n";
		echo "</div>\n";
		echo "<hr class=\"commentseparator\"/>\n";
	}
}
if ($add) {
	$uriEdit->setVar('id','');
	echo '<a style="text-decoration: none;" href="'.$uriEdit->toString().'"><span class="icon-new"></span></a>';
}
?>
