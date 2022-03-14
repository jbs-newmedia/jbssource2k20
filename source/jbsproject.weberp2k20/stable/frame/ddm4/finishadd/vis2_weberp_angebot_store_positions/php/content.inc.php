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

$current_angebot=$this->getAfterFinishElementOption($element, 'object');

if ($current_angebot->getAngebotId()==0) {
	$current_angebot->setAngebotId($this->getIndexElementStorage());
	$current_angebot->load();
}

$current_angebot->setPositionenByNumericArray($_POST);
$current_angebot->savePositionen();

?>