<?php

/**
 * This file is part of the VIS2:WebERP package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebERP
 * @link https://jbs-newmedia.com
 * @license MIT License
 *
 * @var \JBSNewMedia\WebERP\Konto $Konto
 */

$Konto=$this->getFinishElementOption($element, 'konto');
$modus=$this->getFinishElementOption($element, 'modus');

$Daten=$Konto->getKundenKontenZuordnen();
if ($modus=='kontozuordnen') {
	foreach ($Konto->getKundenKontenZuordnenAsList() as $iban=>$title) {
		$iban_check=\osWFrame\Core\Settings::catchIntPostValue('iban_'.$iban);
		if ($iban_check==1) {
			$Konto->createKundenKonto($Daten[$iban]['kunde']['kunde_id'], $iban, $this->getGroupOption('user_id', 'data'));
		}
	}
	osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>'Alle Konten wurden erfolgreich zugeordnet.']);
}

if ($modus=='buchungzuordnen') {
	if ($Konto->getKontenBuchungZuordnenAsList()!=[]) {
		foreach ($Konto->getKontenBuchungZuordnen() as $kunde_id=>$list) {
			foreach ($list as $buchung) {
				$buchung_check=\osWFrame\Core\Settings::catchIntPostValue('buchung_'.md5(serialize($buchung['offener_posten']).serialize($buchung['buchung'])));
				if ($buchung_check==1) {
					$Konto->setBuchungAsPaid($buchung, $this->getGroupOption('user_id', 'data'));
				}
			}
		}
	}
	osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>'Alle Buchungen wurden erfolgreich zugeordnet.']);
}

?>