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

$VIS2_Mandant->directEmptyMandant($osW_Template->buildhrefLink(\osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getDefaultPage()));

$datei=\osWFrame\Core\Settings::catchIntGetValue('datei');
$ordner=\osWFrame\Core\Settings::catchIntGetValue('ordner');

if ($datei>0) {
	$datei=$VIS2_WebDMS_Verwaltung->getDatei($datei);
	if ($datei==[]) {
		\osWFrame\Core\Settings::dieScript('Datei nicht vorhanden');
	}

	header('Content-Length: '.filesize($datei['dokument_file']));
	header("Content-type:application/pdf");
	header("Content-Disposition:attachment;filename=".\osWFrame\Core\StringFunctions::outputUrlString($datei['current']['ordner_titel'].'-'.$datei['dokument_titel']).".pdf");
	readfile($datei['dokument_file']);
	\osWFrame\Core\Settings::dieScript();
}

$explorer=$VIS2_WebDMS_Verwaltung->getExplorer($ordner);

$osW_Template->setVar('explorer', $explorer);

?>