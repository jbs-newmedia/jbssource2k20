<?php

/**
 * This file is part of the VIS2:WebERP package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebERP
 * @link https://jbs-newmedia.com
 * @license MIT License
 */

if ((isset($options['name']))&&($options['name']!='')) {
	$_columns[$options['name']]=array(
		'name'=>$options['name'],
		'order'=>(isset($_order[$options['name']]))?true:false,
		'search'=>(isset($_search[$options['name']]))?true:false,
		'hidden'=>(isset($_hidden[$options['name']]))?true:false,
	);
}

$this->incCounter('list_view_elements');

?>