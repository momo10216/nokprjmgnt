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
$uriEdit->setVar('view','projectcomment');
$uriEdit->setVar('option','com_nokprjmgnt');
$uriEdit->setVar('id',$this->item->id);
$uriDelete = new JURI(JURI::Root().'/index.php');
$uriDelete->setVar('layout','delete');
$uriDelete->setVar('Itemid','');
$uriDelete->setVar('view','projectcomment');
$uriDelete->setVar('option','com_nokprjmgnt');
$uriDelete->setVar('id',$this->item->id);
$deleteConfirmMsg = JText::_("COM_NOKPRJMGNT_COMMENT_CONFIRM_DELETE");
$uriProject = new JURI(JURI::Root().'/index.php');
$uriProject->setVar('layout','detail');
$uriProject->setVar('Itemid','');
$uriProject->setVar('view','project');
$uriProject->setVar('option','com_nokprjmgnt');
$uriProject->setVar('id',$this->item->project_id);
?>
<h1><?php echo JText::_("COM_NOKPRJMGNT_PROJECT_COMMENT_TITLE_LABEL").': '.$this->item->title; ?></h1>
<p>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_FIELD_TITLE_LABEL").':<a href="'.$uriProject->toString().'">'.$this->item->project.'</a>'; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_PUBLISHED_LABEL").':'.$this->item->published; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_CREATED_BY_LABEL").':'.$this->item->createdby; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_CREATED_DATE_LABEL").':'.$this->item->createddate; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_MODIFIED_BY_LABEL").':'.$this->item->modifieddby; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_COMMENT_FIELD_MODIFIED_DATE_LABEL").':'.$this->item->modifieddate; ?>
	<?php if ($this->canDo->get('core.edit')): ?>
		<a style="text-decoration: none;" href="<?php echo $uriEdit->toString(); ?>"><span class="icon-edit"></span></a>
		<a style="text-decoration: none;" href="<?php echo $uriDelete->toString(); ?>" onClick="return confirm('<?php echo $deleteConfirmMsg; ?>');"><span class="icon-trash"></span></a>
	<?php endif; ?>
</p>
<?php echo $this->item->description; ?>
