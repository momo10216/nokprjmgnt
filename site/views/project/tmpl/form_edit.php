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
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'description', JText::_('COM_NOKPRJMGNT_PROJECT_TAB_DESCRIPTION', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid form-horizontal-desktop">
					<?php echo $this->form->getInput('description'); ?>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'common', JText::_('COM_NOKPRJMGNT_PROJECT_TAB_COMMON', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid form-horizontal-desktop">
					<?php echo $this->form->renderField('catid'); ?>
					<?php echo $this->form->renderField('priority'); ?>
					<?php echo $this->form->renderField('duedate'); ?>
					<?php echo $this->form->renderField('status'); ?>
					<?php echo $this->form->renderField('access'); ?>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'custom', JText::_('COM_NOKPRJMGNT_PROJECT_TAB_CUSTOM_AND_RECORDINFO', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid form-horizontal-desktop">
					<div class="span6">
						<?php echo $this->form->renderField('custom1'); ?>
						<?php echo $this->form->renderField('custom2'); ?>
						<?php echo $this->form->renderField('custom3'); ?>
						<?php echo $this->form->renderField('custom4'); ?>
						<?php echo $this->form->renderField('custom5'); ?>
					</div>
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
		<?php if ($this->canDo->get('core.admin')) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_NOKPRJMGNT_PROJECT_TAB_PERMISSIONS')); ?>
				<?php echo $this->form->getInput('rules'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<p align="center">
		<button type="submit">
			<?php echo JText::_('JSAVE') ?>
		</button>
		<button type="submit" onClick="document.adminForm.task.value='cancel';">
			<?php echo JText::_('JCANCEL') ?>
		</button>
	</p>
	<input type="hidden" name="option" value="com_nokprjmgnt" />
	<input type="hidden" name="task" value="save" />
	<?php if (isset($this->item)) {
		echo "\t\t<input type=\"hidden\" name=\"id\" value=\"".$this->item->id."\" />";
	} ?>
	<?php echo JHtml::_('form.token'); ?>
</form>