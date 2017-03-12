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
?>
<h1><?php echo JText::_("COM_NOKPRJMGNT_PROJECT_LABEL").': '.$this->item->title; ?></h1>
<p>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_FIELD_CATEGORY_LABEL").':'.$this->item->category_title; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_FIELD_STATUS_LABEL").':'.$this->item->status; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_FIELD_PRIORITY_LABEL").':'.$this->item->priority; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_FIELD_DUE_DATE_LABEL").':'.$this->item->duedate; ?>
</p>
<?php echo $this->item->description; ?>