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

$VIS2_Mandant->directEmptyMandant($osW_Template->buildhrefLink(\osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getDefaultPage()));

$Verwaltung=new \JBSNewMedia\WebERP\Verwaltung($VIS2_Mandant->getId());
$Konto=new \JBSNewMedia\WebERP\Konto($VIS2_Mandant->getId());

if ($Konto->getKontenBuchungZuordnen()!=[]) {
	$Konto->getKontenBuchungZuordnen();

	if (\osWFrame\Core\Settings::getAction()=='dosend') {
		foreach ($Konto->getKontenBuchungZuordnen() as $kunde_id=>$kunde) {
			foreach ($kunde['offene_posten'] as $date=>$pos) {
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
		}
	}
}

$osW_Template->setVar('Konto', $Konto);
$osW_Template->setVar('Verwaltung', $Verwaltung);

?>