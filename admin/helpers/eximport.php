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

/*
More Info http://php.net/manual/en/book.simplexml.php
*/

// No direct access
defined('_JEXEC') or die('Restricted access');
 
class TableHelper {
	public static function export() {
		$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><{$root}></{$root}>"); 
		return $xml->asXML();
	}

	public static function import($xmltext) {
		$xml = new SimpleXMLElement($xmltext); 
	}

}
?>
