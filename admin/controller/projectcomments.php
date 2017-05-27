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

defined('_JEXEC') or die;

class NoKPrjMgntControllerProjectComments extends JControllerAdmin {
	public function getModel($name = 'ProjectComment', $prefix = 'NoKPrjMgntModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	protected function postDeleteHook(JModelLegacy $model, $ids = null) {
	}

	public function delete() {
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		//TODO: Delete tasks for project
		$model = $this->getModel('ProjectComment');
		$model->delete($cid);
		$this->setRedirect(JRoute::_('index.php?option='.$this->option, false));
	}
}
?>
