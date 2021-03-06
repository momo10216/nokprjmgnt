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

$uriEdit = new JURI(JURI::Root().'/index.php');
$uriEdit->setVar('layout','form');
$uriEdit->setVar('Itemid','');
$uriEdit->setVar('view','project');
$uriEdit->setVar('option','com_nokprjmgnt');
$uriEdit->setVar('id',$this->item->id);
$uriDelete = new JURI(JURI::Root().'/index.php');
$uriDelete->setVar('layout','delete');
$uriDelete->setVar('Itemid','');
$uriDelete->setVar('view','project');
$uriDelete->setVar('option','com_nokprjmgnt');
$uriDelete->setVar('id',$this->item->id);
$deleteConfirmMsg = JText::_("COM_NOKPRJMGNT_PROJECT_CONFIRM_DELETE");
?>
<h1><?php echo JText::_("COM_NOKPRJMGNT_PROJECT_LABEL").': '.$this->item->title; ?></h1>
<p>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_FIELD_CATEGORY_LABEL").':'.$this->item->category_title; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_FIELD_STATUS_LABEL").':'.$this->item->status; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_FIELD_PRIORITY_LABEL").':'.$this->item->priority; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_FIELD_DUE_DATE_LABEL").':'.$this->item->duedate; ?>
	<?php if ($this->canDo->get('core.edit')): ?>
		<a style="text-decoration: none;" href="<?php echo $uriEdit->toString(); ?>"><span class="icon-edit"></span></a>
	<?php endif; ?>
	<?php if ($this->canDo->get('core.delete')): ?>
		<a style="text-decoration: none;" href="<?php echo $uriDelete->toString(); ?>" onClick="return confirm('<?php echo $deleteConfirmMsg; ?>');"><span class="icon-trash"></span></a>
	<?php endif; ?>
</p>
<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'description')); ?>
<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'description', JText::_('COM_NOKPRJMGNT_PROJECT_TAB_DESCRIPTION', true)); ?>
<?php echo $this->item->description; ?>
<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tasks', JText::_('COM_NOKPRJMGNT_PROJECT_TAB_TASKS', true)); ?>
<?php echo $this->loadTemplate('tasks'); ?>
<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'comments', JText::_('COM_NOKPRJMGNT_PROJECT_TAB_COMMENTS', true)); ?>
<?php echo $this->loadTemplate('comments'); ?>
<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php echo JHtml::_('bootstrap.endTabSet'); ?>
