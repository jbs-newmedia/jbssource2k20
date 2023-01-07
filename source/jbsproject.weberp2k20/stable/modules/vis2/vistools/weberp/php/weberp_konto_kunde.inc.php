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

use osWFrame\Core\Network;
use osWFrame\Core\SessionMessageStack;

$VIS2_Mandant->directEmptyMandant($osW_Template->buildhrefLink(\osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getDefaultPage()));

$Verwaltung=new \JBSNewMedia\WebERP\Verwaltung($VIS2_Mandant->getId());
$kunden_details=$Verwaltung->getKundeById(\osWFrame\Core\Settings::catchIntGetValue('kunde_id'));

if ($kunden_details==[]) {
	SessionMessageStack::addMessage('session', 'warning', ['msg'=>'Kunde nicht gefunden']);
	Network::directHeader($osW_Template->buildhrefLink(\osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getDefaultPage()));
}

$Konto=new \JBSNewMedia\WebERP\Konto($VIS2_Mandant->getId());
$Konto->loadPostenByKunde($kunden_details['kunde_id']);
$Konto->loadKundenKontenByKunde($kunden_details['kunde_id']);
$ibans=[];
foreach ($Konto->getKundenKonten(false) as $konto) {
	$ibans[]=$konto['konto_iban'];
}
$Konto->loadBuchungenByIBAN($ibans);
$positionen=[];

$buchung_select=[];
foreach ($Konto->getPosten(false) as $pos) {
	$positionen[$pos['rechnung_datum']][]=$pos;
	for ($i=1;$i<=3;$i++) {
		if ($pos['buchung_id_'.$i]>0) {
			$buchung_select[$pos['buchung_id_'.$i]]='*';
		}

	}
}

foreach ($Konto->getBuchungen(false) as $pos) {
	if (isset($buchung_select[$pos['buchung_id']])) {
		$buchung_select[$pos['buchung_id']]='***'.$Verwaltung->formatNumber($pos['buchung_betrag']).' Euro - '.$pos['buchung_verwendungszweck'];
	} else {
		$buchung_select[$pos['buchung_id']]=$Verwaltung->formatNumber($pos['buchung_betrag']).' Euro - '.$pos['buchung_verwendungszweck'];
	}

	$positionen[$pos['buchung_buchungtag']][]=$pos;
}
krsort($buchung_select);
krsort($positionen);

if (\osWFrame\Core\Settings::getAction()=='dosend') {
	foreach ($Konto->getPosten(false) as $pos) {
		$set_unpaid=true;
		$update=false;
		for ($i=1; $i<=3; $i++) {
			$buchung_id=\osWFrame\Core\Settings::catchIntPostValue('R'.$pos['rechnung_nr'].'_b'.$i);

			if ($pos['buchung_id_'.$i]!=$buchung_id) {
				$update=true;
			}

			if ($buchung_id!=0) {
				$set_unpaid=false;
				if ($pos['buchung_id_'.$i]!=$buchung_id) {
					$Konto->setBuchung2Rechnung($pos['rechnung_nr'], $buchung_id, $i, $VIS2_User->getId());
				}
			}
		}

		if (($set_unpaid===true)&&($update===true)) {
			$Konto->setBuchungAsUnPaid($pos['rechnung_nr'], $VIS2_User->getId());
		}
	}

	$Konto=new \JBSNewMedia\WebERP\Konto($VIS2_Mandant->getId());
	$Konto->loadPostenByKunde($kunden_details['kunde_id']);
	$Konto->loadKundenKontenByKunde($kunden_details['kunde_id']);
	$ibans=[];
	foreach ($Konto->getKundenKonten(false) as $konto) {
		$ibans[]=$konto['konto_iban'];
	}
	$Konto->loadBuchungenByIBAN($ibans);
	$positionen=[];

	$buchung_select=[];
	foreach ($Konto->getPosten(false) as $pos) {
		$positionen[$pos['rechnung_datum']][]=$pos;
		for ($i=1;$i<=3;$i++) {
			if ($pos['buchung_id_'.$i]>0) {
				$buchung_select[$pos['buchung_id_'.$i]]='*';
			}

		}
	}

	foreach ($Konto->getBuchungen(false) as $pos) {
		if (isset($buchung_select[$pos['buchung_id']])) {
			$buchung_select[$pos['buchung_id']]='***'.$Verwaltung->formatNumber($pos['buchung_betrag']).' Euro - '.$pos['buchung_verwendungszweck'];
		} else {
			$buchung_select[$pos['buchung_id']]=$Verwaltung->formatNumber($pos['buchung_betrag']).' Euro - '.$pos['buchung_verwendungszweck'];
		}

		$positionen[$pos['buchung_buchungtag']][]=$pos;
	}
	krsort($buchung_select);
	krsort($positionen);

}

$buchung_select=[''=>'Bitte wählen', -1=>'Ohne Buchung ausgleichen']+$buchung_select;

$osW_Template->setVar('ibans', $ibans);
$osW_Template->setVar('kunden_details', $kunden_details);
$osW_Template->setVar('positionen', $positionen);
$osW_Template->setVar('Verwaltung', $Verwaltung);
$osW_Template->setVar('buchung_select', $buchung_select);

?>