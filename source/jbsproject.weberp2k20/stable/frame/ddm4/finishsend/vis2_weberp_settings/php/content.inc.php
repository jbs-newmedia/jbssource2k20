<?php

/**
 * This file is part of the VIS2:WebERP package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebERP
 * @link https://jbs-newmedia.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$VIS2_WebERP_Verwaltung=new \JBSNewMedia\WebERP\Verwaltung($this->getGroupOption('mandant_id', 'data'));
$VIS2_WebERP_Verwaltung->setUserId($this->getGroupOption('user_id', 'data'));

foreach ($this->getFinishElementOption($element, 'data') as $values) {
	if (!isset($values['type'])) {
		$values['type']='string';
	}
	switch ($values['type']) {
		case 'bool':
			$VIS2_WebERP_Verwaltung->writeBoolVar($values['key'], $this->getDoSendElementStorage($values['value']));
			break;
		case 'int':
			$VIS2_WebERP_Verwaltung->writeIntVar($values['key'], $this->getDoSendElementStorage($values['value']));
			break;
		case 'float':
			$VIS2_WebERP_Verwaltung->writeFloatVar($values['key'], $this->getDoSendElementStorage($values['value']));
			break;
		case 'text':
			$VIS2_WebERP_Verwaltung->writeTextVar($values['key'], $this->getDoSendElementStorage($values['value']));
			break;
		default:
			$VIS2_WebERP_Verwaltung->writeStringVar($values['key'], $this->getDoSendElementStorage($values['value']));
			break;
	}

}

osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>$this->getGroupMessage('send_success_title')]);

?>