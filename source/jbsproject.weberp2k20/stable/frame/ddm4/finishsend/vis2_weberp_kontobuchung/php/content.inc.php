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

$Konto=$this->getFinishElementOption($element, 'konto');
$result=$Konto->setBuchungenAsPaid($_POST, $this->getGroupOption('user_id', 'data'));

if (in_array($result['type'], ['success', 'danger'])) {
	osWFrame\Core\SessionMessageStack::addMessage('session', $result['type'], ['msg'=>$result['message']]);
}

?>