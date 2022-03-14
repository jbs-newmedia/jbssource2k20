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

$osW_Scripts=new \osWFrame\Core\Scripts(__FILE__);

if ($osW_Scripts->checkLock()===true) {
	$VIS2_Main=new \VIS2\Core\Main();
	$VIS2_Main->setTool('weberp');

	$ts=\JBSNewMedia\WebERP\Verwaltung::getSepaTimeStamp();

	$VIS2_Mandant=new \VIS2\Core\Mandant($VIS2_Main->getToolId());
	foreach ($VIS2_Mandant->getMandanten() as $mandant_id=>$mandant) {
		$VIS2_WebERP_Verwaltung=new \JBSNewMedia\WebERP\Verwaltung($mandant_id);
		if ($VIS2_WebERP_Verwaltung->getIntVar('cronuser')!==null) {
			$VIS2_WebERP_Verwaltung->setUserId($VIS2_WebERP_Verwaltung->getIntVar('cronuser'));
		} else {
			$VIS2_WebERP_Verwaltung->setUserId(0);
		}

		$cron_rechnung=$VIS2_WebERP_Verwaltung->createRechnungen();

		$sepa_ein=$VIS2_WebERP_Verwaltung->getSepaEingang();
		$sepa_aus=$VIS2_WebERP_Verwaltung->getSepaAusgang();

		$cron_ein=$VIS2_WebERP_Verwaltung->getStringVar('cronein');
		$cron_aus=$VIS2_WebERP_Verwaltung->getStringVar('cronaus');

		if (($cron_ein!=='')&&($cron_ein!==null)) {
			$cron_ein=explode("\n", trim($cron_ein));
			foreach ($cron_ein as $json_file) {
				$json_content=json_decode(file_get_contents(str_replace('$$$ts$$$', $ts, $json_file)), true);
				if ($json_content!==null) {
					foreach ($json_content as $content) {
						$sepa_ein[]=$content;
					}
				}
			}
		}

		if (($cron_aus!=='')&&($cron_aus!==null)) {
			$cron_aus=explode("\n", trim($cron_aus));
			foreach ($cron_aus as $json_file) {
				$json_content=json_decode(file_get_contents(str_replace('$$$ts$$$', $ts, $json_file)), true);
				if ($json_content!==null) {
					foreach ($json_content as $content) {
						$sepa_aus[]=$content;
					}
				}
			}
		}

		if (($cron_rechnung!==[])||($sepa_ein!==[])||($sepa_aus!==[])) {
			$Mailer=new \JBSNewMedia\WebERP\Mail($VIS2_WebERP_Verwaltung);
			$Mailer->addAddress($VIS2_WebERP_Verwaltung->getStringVar('email_buchhaltung'));
			$Mailer->setFrom($VIS2_WebERP_Verwaltung->getStringVar('email_buchhaltung'), $VIS2_WebERP_Verwaltung->getStringVar('firma'));
			$Mailer->setSubject('WebERP Cronjob '.date('Y-m'));

			if ($sepa_ein!==[]) {
				$Mailer->addStringAttachment(\JBSNewMedia\WebERP\Verwaltung::getSepaXML($sepa_ein, 'CORE', 'Eingang.'.date('Y-m'), 'SEPA.Eingang.'.date('d.m.Y'), $VIS2_WebERP_Verwaltung->getStringVar('firma'), $VIS2_WebERP_Verwaltung->getStringVar('firma'), str_replace(' ', '', $VIS2_WebERP_Verwaltung->getStringVar('iban')), $VIS2_WebERP_Verwaltung->getStringVar('bic'), $VIS2_WebERP_Verwaltung->getStringVar('glaeubigerid')), 'SEPA.Eingang.'.date('d.m.Y').'.xml');
			}
			if ($sepa_aus!==[]) {
				$Mailer->addStringAttachment(\JBSNewMedia\WebERP\Verwaltung::getSepaXML($sepa_aus, 'TRF', 'Ausgang.'.date('Y-m'), 'SEPA.Ausgang.'.date('d.m.Y'), $VIS2_WebERP_Verwaltung->getStringVar('firma'), $VIS2_WebERP_Verwaltung->getStringVar('firma'), str_replace(' ', '', $VIS2_WebERP_Verwaltung->getStringVar('iban')), $VIS2_WebERP_Verwaltung->getStringVar('bic'), $VIS2_WebERP_Verwaltung->getStringVar('glaeubigerid')), 'SEPA.Ausgang.'.date('d.m.Y').'.xml');
			}

			$content=[];

			if ($cron_rechnung!=[]) {
				if (count($cron_rechnung)==1) {
					$content[]='Es wurde eine Rechnung erstellt:';
				} else {
					$content[]='Es wurden '.count($cron_rechnung).' Rechnungen erstellt:';
				}
				$content[]='';
				$content[]='';
				$content[]='<table>';
				$content[]='<tr><td align="right">kunde_nr</td><td align="right">rechnung_nr</td><td align="right">rechnung_gesamt_netto</td><td align="right">rechnung_gesamt_brutto</td></tr>';
				foreach ($cron_rechnung as $rechnung) {
					$content[]='<tr><td align="right">'.$rechnung['kunde_nr'].'</td><td align="right">'.$rechnung['rechnung_nr'].'</td><td align="right">'.$rechnung['rechnung_gesamt_netto'].'</td><td align="right">'.$rechnung['rechnung_gesamt_brutto'].'</td></tr>';
				}
				$content[]='</table>';
				$content[]='';
				$content[]='';
			} else {
				$content[]='Es wurde keine Rechnung erstellt.';
				$content[]='';
				$content[]='';
			}

			$Mailer->MsgHTML(implode("\n", $content));
			$Mailer->send();
		}
	}
	$osW_Scripts->clearLock();
	echo 'Cronjob verarbeitet ('.date('Y-m-d H:i:s').')';
} else {
	echo 'Cronjob arbeitet noch ('.date('Y-m-d H:i:s').')';
}

?>