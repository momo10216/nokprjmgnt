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
defined('_JEXEC') or die; // no direct access

$tasksColumn = array('title','priority','duedate','status');
$tasksDetailColumn = 'title';
$tasksBorderType = 'none';
$showBorder = false;
$showHeader = true;
$details = true;

// Prepare
$component = 'com_nokprjmgnt';
$tasksModel = JControllerLegacy::getInstance('NoKPrjMgnt')->getModel('Tasks');
$taskItems = $tasksModel->getProjectFormItems($this->item->id);
$tasksHeader = $tasksModel->getHeader($tasksColumn);
$tasksAlign = $tasksModel->getAlign($tasksColumn);
$uriEdit = new JURI(JURI::Root().'/index.php');
$uriEdit->setVar('layout','form');
$uriEdit->setVar('Itemid','');
$uriEdit->setVar('view','task');
$uriEdit->setVar('option',$component);
$uriEdit->setVar('project_id',$this->item->id);
$uriDelete = new JURI(JURI::Root().'/index.php');
$uriDelete->setVar('layout','delete');
$uriDelete->setVar('Itemid','');
$uriDelete->setVar('view','task');
$uriDelete->setVar('option',$component);
$uriDetail = new JURI(JURI::Root().'/index.php');
$uriDetail->setVar('layout','detail');
$uriDetail->setVar('Itemid','');
$uriDetail->setVar('view','task');
$uriDetail->setVar('option',$component);
$modify = JHelperContent::getActions('com_nokprjmgnt','project',$this->item->id)->get('core.edit');
$tasksColumnCount = count($tasksColumn);

// Filter

// Header
switch ($tasksBorderType) {
	case "row":
		$borderStyle = 'border-top-style:solid; border-width:1px';
		break;
	case "grid":
		$borderStyle = 'border-style:solid; border-width:1px';
		break;
	default:
		$borderStyle = 'border-style:none; border-width:0px';
		break;
}
if ($tasksBorderType == 'none') {
	echo '<table border="0" style="'.$borderStyle.'">'."\n";
} else {
	echo '<table border="0" cellspacing="0" cellpadding="0" style="'.$borderStyle.'">'."\n";
}
echo '<tr>';
foreach ($tasksColumn as $col) {
	if ($tasksHeader[$col] != '') {
		echo '<th';
		if (isset($tasksAlign[$col]) && !empty($tasksAlign[$col])) {
			echo ' align="'.$tasksAlign[$col].'"';
		}
		echo '>';
		if ($showHeader) {
			echo $tasksHeader[$col];
		}
		echo '</th>';
	}
}
echo '<th align="left">';
if ($modify) {
	echo '<a style="text-decoration: none;" href="'.$uriEdit->toString().'"><span class="icon-new"></span></a>';
}
echo '</th>';
echo '</tr>'."\n";

// Items
if (is_array($taskItems) && (count($taskItems)>0)) {
	$deleteConfirmMsg = JText::_("COM_NOKPRJMGNT_TASK_CONFIRM_DELETE");
	foreach($taskItems as $item) {
		$row = (array) $item;
		echo "<tr>\n";
		if ($details) {
			$uriDetail->setVar('id',$item->id);
		}
		for($j=0;$j<$tasksColumnCount;$j++) {
			$field = $tasksColumn[$j];
			if (!empty($field)) {
				$data = $row[$field];
				$data = str_replace('0000-00-00 00:00:00','',$data);
				$data = str_replace(' 00:00:00','',$data);
				echo '<td';
				if (isset($tasksAlign[$field]) && !empty($tasksAlign[$field])) {
					echo ' align="'.$tasksAlign[$field].'"';
				}
				echo '>';
				if ($details && (($tasksDetailColumn == "") || ($tasksDetailColumn == $field))) {
					echo "<a href=\"".$uriDetail->toString()."\">".$data."</a>";
				} else {
					echo $data;
				}
				echo "</td>";
			}
		}
		echo '<td stype="'.$borderStyle.'">';
		if ($modify) {
			$uriEdit->setVar('id',$item->id);
			echo '<a style="text-decoration: none;" href="'.$uriEdit->toString().'"><span class="icon-edit"></span></a>';
		}
		echo '</td>';
		echo '<td stype="'.$borderStyle.'">';
		if ($modify) {
			$uriDelete->setVar('id',$item->id);
			echo '<a style="text-decoration: none;" href="'.$uriDelete->toString().'" onClick="return confirm(\''.$deleteConfirmMsg.'\');"><span class="icon-trash"></span></a>';
		}
		echo '</td>';
		echo "</tr>\n";
	}
}

// Footer
?>
</table>