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

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_nokprjmgnt&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm">
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
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_NOKPRJMGNT_PROJECT_TAB_COMMON', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid form-horizontal-desktop">
					<div class="span6">
						<?php echo $this->form->renderField('description'); ?>
					</div>
					<div class="span6">
						<?php echo $this->form->renderField('catid'); ?>
						<?php echo $this->form->renderField('priority'); ?>
						<?php echo $this->form->renderField('duedate'); ?>
						<?php echo $this->form->renderField('status'); ?>
						<?php echo $this->form->renderField('read_asset_id'); ?>
						<?php echo $this->form->renderField('modify_asset_id'); ?>
					</div>
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
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="project.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>