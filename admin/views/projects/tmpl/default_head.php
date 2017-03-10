<?php
/**
* @version	$Id$
* @package	Joomla
* @subpackage	NoK-ToDo
* @copyright	Copyright (c) 2017 Norbert Kümin. All rights reserved.
* @license	http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
* @author	Norbert Kuemin
* @authorEmail	momo_102@bluemail.ch
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listDirn	= $this->escape($this->state->get('list.direction'));
$listOrder	= $this->escape($this->state->get('list.ordering'));
?>
<tr>
	<th width="1%" class="hidden-phone">
		<?php echo JHtml::_('grid.checkall'); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_NOKPRJMGNT_PROJECT_FIELD_TITLE_LABEL', 'p.title', $listDirn, $listOrder); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JHtml::_('grid.sort', 'COM_NOKPRJMGNT_PROJECT_FIELD_CATEGORY_LABEL', 'c.title', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_NOKPRJMGNT_PROJECT_FIELD_STATUS_LABEL', 'p.status', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_NOKPRJMGNT_PROJECT_FIELD_PRIORITY_LABEL', 'p.priority', $listDirn, $listOrder); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JHtml::_('grid.sort', 'COM_NOKPRJMGNT_PROJECT_FIELD_DUE_DATE_LABEL', 'p.duedate', $listDirn, $listOrder); ?>
	</th>
</tr>