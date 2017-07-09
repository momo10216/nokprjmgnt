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
$curi = JFactory::getURI();
$uriSave = new JURI($curi->toString());
if (empty($this->item->task_id)) {
	$taskId = JFactory::getURI()->getVar('task_id');
} else {
	$taskId = $this->item->task_id;
}
$projectId = JFactory::getURI()->getVar('project_id');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo $uriSave->toString(); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid form-horizontal-desktop">
				<div class="span12">
					<?php echo $this->form->renderField('title'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'description')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'description', JText::_('COM_NOKPRJMGNT_COMMENT_TAB_DESCRIPTION', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid form-horizontal-desktop">
					<?php echo $this->form->getInput('description'); ?>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'custom', JText::_('COM_NOKPRJMGNT_COMMENT_TAB_RECORDINFO', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid form-horizontal-desktop">
					<div class="span6">
						<?php echo $this->form->renderField('id'); ?>
						<?php echo $this->form->renderField('createdby'); ?>
						<?php echo $this->form->renderField('createddate'); ?>
						<?php echo $this->form->renderField('modifiedby'); ?>
						<?php echo $this->form->renderField('modifieddate'); ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<p align="center">
		<button type="submit">
			<?php echo JText::_('JSAVE') ?>
		</button>
		<button type="submit" onClick="document.adminForm.taskcomment.value='cancel';">
			<?php echo JText::_('JCANCEL') ?>
		</button>
	</p>
	<input type="hidden" name="option" value="com_nokprjmgnt" />
	<input type="hidden" name="task" value="save" />
	<?php if (isset($this->item)) {
		echo "\t\t<input type=\"hidden\" name=\"jform[id]\" value=\"".$this->item->id."\" />";
	}
	echo "\t\t<input type=\"hidden\" name=\"jform[task_id]\" value=\"".$taskId."\" />";?>
	<?php echo JHtml::_('form.token'); ?>
</form>