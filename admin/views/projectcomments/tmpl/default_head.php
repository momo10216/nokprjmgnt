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

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listDirn	= $this->escape($this->state->get('list.direction'));
$listOrder	= $this->escape($this->state->get('list.ordering'));
?>
<tr>
	<th width="1%">
		<?php echo JHtml::_('grid.checkall'); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_PROJECT_LABEL', 'p.title', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_TITLE_LABEL', 'c.title', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_CREATED_DATE_LABEL', 'c.createddate', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_CREATED_BY_LABEL', 't.createdby', $listDirn, $listOrder); ?>
	</th>
</tr>
