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
?>
<?php foreach($this->items as $i => $item): ?>
        <tr class="row<?php echo $i % 2; ?>">
                <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'projectcomments.'); ?>
                </td>
                <td>
                        <?php echo $item->project; ?>
                </td>
                <td>
                        <?php echo $item->title; ?>
                </td>
                <td>
                        <?php echo $item->createddate; ?>
                </td>
                <td>
                        <?php echo $item->createdby; ?>
                </td>
        </tr>
<?php endforeach; ?>
