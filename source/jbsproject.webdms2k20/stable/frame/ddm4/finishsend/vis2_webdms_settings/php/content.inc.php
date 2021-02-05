<?php

/**
 * This file is part of the VIS2:WebDMS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebDMS
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$VIS2_WebDMS_Verwaltung=new \JBSNewMedia\WebDMS\Verwaltung($this->getGroupOption('mandant_id', 'data'));
$VIS2_WebDMS_Verwaltung->setUserId($this->getGroupOption('user_id', 'data'));

foreach ($this->getFinishElementOption($element, 'data') as $values) {
	if (!isset($values['type'])) {
		$values['type']='string';
	}
	switch ($values['type']) {
		case 'bool':
			$VIS2_WebDMS_Verwaltung->writeBoolVar($values['key'], $this->getDoSendElementStorage($values['value']));
			break;
		case 'int':
			$VIS2_WebDMS_Verwaltung->writeIntVar($values['key'], $this->getDoSendElementStorage($values['value']));
			break;
		case 'float':
			$VIS2_WebDMS_Verwaltung->writeFloatVar($values['key'], $this->getDoSendElementStorage($values['value']));
			break;
		case 'text':
			$VIS2_WebDMS_Verwaltung->writeTextVar($values['key'], $this->getDoSendElementStorage($values['value']));
			break;
		default:
			$VIS2_WebDMS_Verwaltung->writeStringVar($values['key'], $this->getDoSendElementStorage($values['value']));
			break;
	}

}

osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>$this->getGroupMessage('send_success_title')]);

?>