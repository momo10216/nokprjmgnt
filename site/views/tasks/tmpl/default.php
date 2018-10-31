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

$details = false;
$projectId = $this->paramsMenuEntry->get('project_id');
$uriEdit = new JURI(JURI::Root().'/index.php');
$uriEdit->setVar('layout','form');
$uriEdit->setVar('Itemid','');
$uriEdit->setVar('view','task');
$uriEdit->setVar('option','com_nokprjmgnt');
$uriEdit->setVar('project_id',$projectId);
$uriEdit->setVar('redirect',urlencode(JFactory::getURI()->toString()));
$uriDelete = new JURI(JURI::Root().'/index.php');
$uriDelete->setVar('layout','delete');
$uriDelete->setVar('Itemid','');
$uriDelete->setVar('view','task');
$uriDelete->setVar('option','com_nokprjmgnt');
$uriDelete->setVar('redirect',urlencode(JFactory::getURI()->toString()));
if ($this->paramsMenuEntry->get('detail_enable') != '0') {
	$details = true;
	$uriDetail = new JURI(JURI::Root().'/index.php');
	$uriDetail->setVar('layout','detail');
	$uriDetail->setVar('Itemid','');
	$uriDetail->setVar('view','task');
	$uriDetail->setVar('option','com_nokprjmgnt');
	$uriDetail->setVar('redirect',urlencode(JFactory::getURI()->toString()));
}
// Get columns
$cols = array();
for ($i=1;$i<=20;$i++) {
	$field = 'column_'.$i;
	$cols[] = $this->paramsMenuEntry->get($field);
}
$colcount = count($cols);

// Style
$pbWidth = $this->paramsComponent->get('progress_width');
if (empty($pbWidth)) { $pbWidth = '100px'; }
$pbHeight = $this->paramsComponent->get('progress_height');
if (empty($pbHeight)) { $pbHeight = '12px'; }
$pbColor = $this->paramsComponent->get('progress_color');
?>
<style type="text/css" media="screen">
.pb-border {
	border: 1px solid #ccc !important;
}

.pb-bar {
	height: <?php echo $pbHeight; ?>;
	background-color: <?php echo $pbColor; ?>;
}
.pb-value {
	font-size: smaller;
}
</style>
<?php

// Display
$border='border-style:solid; border-width:1px';
$width='';
if ($this->paramsMenuEntry->get('width') != '0') {
	$width='width="'.$this->paramsMenuEntry->get('width').'" ';
}
if ($this->paramsMenuEntry->get('table_center') == '1') echo "<center>\n";
if ($this->paramsMenuEntry->get('border_type') != '') {
	echo '<table '.$width.'border="0" cellspacing="0" cellpadding="'.$this->paramsMenuEntry->get('cellpadding').'" style="'.$border.'">'."\n";
} else {
	echo '<table '.$width.'border="0" cellspacing="0" cellpadding="'.$this->paramsMenuEntry->get('cellpadding').'" style="border-style:none; border-width:0px">'."\n";
}
$header = $this->getModel()->getHeader($cols);
$aligns = $this->getModel()->getAlign($cols);
echo '<tr>';
foreach ($cols as $col) {
	if ($header[$col] != '') {
		echo '<th';
		if (isset($aligns[$col]) && !empty($aligns[$col])) {
			echo ' align="'.$aligns[$col].'"';
		}
		echo '>';
		if ($this->paramsMenuEntry->get('show_header', '1') == '1') {
			echo $header[$col];
		}
		echo '</th>';
	}
}
echo '<th align="left">';
if ($this->componentCanDo->get('core.create') && !empty($projectId)) {
	echo '<a style="text-decoration: none;" href="'.$uriEdit->toString().'"><span class="icon-new"></span></a>';
}
echo '</th>';
echo '</tr>'."\n";
$detailColumn = $this->paramsMenuEntry->get('detail_column_link');
//echo "<pre>".$detailColumn."</pre>";
if ($this->items) {
	$deleteConfirmMsg = JText::_('COM_NOKPRJMGNT_TASK_CONFIRM_DELETE');
	switch ($this->paramsMenuEntry->get( "border_type")) {
		case "row":
			$borderStyle = " style=\"border-top-style:solid; border-width:1px\"";
			break;
		case "grid":
			$borderStyle = " style=\"".$border."\"";
			break;
		default:
			$borderStyle = "";
			break;
	}
	foreach($this->items as $item) {
		$itemCanDo = JHelperContent::getActions('com_nokprjmgnt','project',$item->id);
		$row = (array) $item;
		echo "<tr>\n";
		if ($details) {
			$uriDetail->setVar('id',$item->id);
			$uriDetail->setVar('project_id',$item->project_id);
		}
		for($j=0;$j<$colcount;$j++) {
			$field = $cols[$j];
			if (!empty($field)) {
				$data = $row[$field];
				$data = str_replace('0000-00-00 00:00:00','',$data);
				$data = str_replace(' 00:00:00','',$data);
				if (($field == 'responsible_user_id') || ($field == 'assign_user_ids')) {
					$data = $this->getModel()->getConvertUserIdsToNames($data);
				}
				if ($field == 'progress') {
					if ($data == '') { $data = '0'; }
					$data = '<div class="pb-border"><div class="pb-bar" style="width:'.$data.'%;"><span class="pb-value">'.$data.'%</span></div></div>';
				}
				echo '<td';
				if (isset($aligns[$field]) && !empty($aligns[$field])) {
					echo ' align="'.$aligns[$field].'"';
				}
				echo $borderStyle.'>';
				if ($details && (($detailColumn == "") || ($detailColumn == $field))) {
					echo "<a href=\"".$uriDetail->toString()."\">".$data."</a>";
				} else {
					echo $data;
				}
				echo "</td>";
			}
		}
		echo "<td".$borderStyle.">";
		if ($itemCanDo->get('core.edit')) {
			$uriEdit->setVar('id',$item->id);
			echo '<a style="text-decoration: none;" href="'.$uriEdit->toString().'"><span class="icon-edit"></span></a>';
		}
		echo '</td>';
		echo "<td".$borderStyle.">";
		if ($itemCanDo->get('core.delete')) {
			$uriDelete->setVar('id',$item->id);
			echo '<a style="text-decoration: none;" href="'.$uriDelete->toString().'" onClick="return confirm(\''.$deleteConfirmMsg.'\');"><span class="icon-trash"></span></a>';
		}
		echo '</td>';
		echo "</tr>\n";
	}
}
echo "</table>\n";
if ($this->paramsMenuEntry->get( "table_center") == "1") echo "</center>\n";
?>
