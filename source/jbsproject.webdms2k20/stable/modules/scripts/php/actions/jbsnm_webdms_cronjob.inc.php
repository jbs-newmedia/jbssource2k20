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

$osW_Scripts=new \osWFrame\Core\Scripts(__FILE__);

if ($osW_Scripts->checkLock()===true) {
	$VIS2_Main=new \VIS2\Core\Main();
	$VIS2_Main->setTool('webdms');

	$ts=time();

	$VIS2_Mandant=new \VIS2\Core\Mandant($VIS2_Main->getToolId());
	foreach ($VIS2_Mandant->getMandanten() as $mandant_id=>$mandant) {
		$VIS2_WebDMS_Verwaltung=new \JBSNewMedia\WebDMS\Verwaltung($mandant_id);
		if ($VIS2_WebDMS_Verwaltung->getIntVar('cronuser')!==null) {
			$VIS2_WebDMS_Verwaltung->setUserId($VIS2_WebDMS_Verwaltung->getIntVar('cronuser'));
		} else {
			$VIS2_WebDMS_Verwaltung->setUserId(0);
		}

		foreach ($VIS2_WebDMS_Verwaltung->getDokumente2OCR(10, 0) as $dokument) {
			if ($VIS2_WebDMS_Verwaltung->getStringVar('api')!==null) {
				$ch=curl_init($VIS2_WebDMS_Verwaltung->getStringVar('api'));
				$pdf=\osWFrame\Core\Settings::getStringVar('settings_abspath').$dokument['dokument_file'];
				$cfile=curl_file_create($pdf, 'application/pdf', basename($pdf));
				$result=false;
				$data=['pdf_upload'=>$cfile, 'action'=>'all'];
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$result=curl_exec($ch);
				$response=json_decode($result, true);
				if (($response!==null)&&($response['status']===true)) {
					$file_ocr=base64_decode($response['file_ocr']);
					$file_ocr_name=str_replace('.pdf', '.ocr.pdf', $dokument['dokument_file']);
					file_put_contents(\osWFrame\Core\Settings::getStringVar('settings_abspath').$file_ocr_name, $file_ocr);

					$VIS2_WebDMS_Verwaltung->updateDokument($dokument['dokument_id'], 'dokument_index_1', implode(' ', $response['extract']), 'string');
					$VIS2_WebDMS_Verwaltung->updateDokument($dokument['dokument_id'], 'dokument_file_ocr', $file_ocr_name, 'string');
					$VIS2_WebDMS_Verwaltung->updateDokument($dokument['dokument_id'], 'dokument_file_ocr_name', basename($file_ocr_name), 'string');
					$VIS2_WebDMS_Verwaltung->updateDokument($dokument['dokument_id'], 'dokument_file_ocr_type', $dokument['dokument_file_type'], 'string');
					$VIS2_WebDMS_Verwaltung->updateDokument($dokument['dokument_id'], 'dokument_file_ocr_size', filesize(\osWFrame\Core\Settings::getStringVar('settings_abspath').$file_ocr_name), 'integer');
					$VIS2_WebDMS_Verwaltung->updateDokument($dokument['dokument_id'], 'dokument_file_ocr_md5', md5_file(\osWFrame\Core\Settings::getStringVar('settings_abspath').$file_ocr_name), 'string');
					$VIS2_WebDMS_Verwaltung->updateDokument($dokument['dokument_id'], 'dokument_index_time', $ts, 'integer');
				}
			}
		}
	}
	$osW_Scripts->clearLock();
	echo 'Cronjob verarbeitet ('.date('Y-m-d H:i:s').')';
} else {
	echo 'Cronjob arbeitet noch ('.date('Y-m-d H:i:s').')';
}

?>