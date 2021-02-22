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

namespace JBSNewMedia\WebERP;

use osWFrame\Core as osWFrame;
use VIS2\Core as VIS2;

class Konto {

	use osWFrame\BaseStaticTrait;
	use osWFrame\BaseConnectionTrait;
	use VIS2\BaseMandantTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=0;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var array
	 */
	private array $buchungen=[];

	/**
	 * @var array
	 */
	private array $offene_posten=[];

	/**
	 * @var array
	 */
	private array $kunden_konten=[];

	/**
	 * @var array
	 */
	private array $buchungs_ausgleich=[];

	/**
	 * Konto constructor.
	 *
	 * @param int $mandant_id
	 */
	public function __construct(int $mandant_id=0) {
		$this->init();
		$this->setMandantId($mandant_id);
	}

	/*
	public function dump() {
		print_a($this->rechnung_details);
		print_a($this->kunde_details);
		print_a($this->rechnung_positionen);
	}
	*/

	/**
	 * @return bool
	 */
	public function init():bool {
		$this->buchungen=[];
		$this->offene_posten=[];

		return true;
	}

	/**
	 * @param string $file
	 * @param string $format
	 * @return array
	 */
	public function importBuchungen(string $file, string $format, int $vis_user_id):array {
		$key='';
		$time=time();
		$result=[];
		$result['message']=0;
		$result['type']='';
		$result['new']=0;
		$result['old']=0;
		$result['all']=0;

		if ($format=='csv-mt940') {
			if (($handle=fopen($file, "r"))!==false) {
				while (($data=fgetcsv($handle, 1000, ';', '"'))!==false) {
					foreach ($data as $k=>$v) {
						$data[$k]=utf8_encode($v);
					}
					if ($key=='') {
						if (md5(serialize($data))!='127197b3322a7881311147f0be91191f') {
							$result['message']='Datei hat das falsche Format.';
							$result['type']='error';

							return $result;
						}
						$key='a:11:{i:0;s:13:"Auftragskonto";i:1;s:11:"Buchungstag";i:2;s:11:"Valutadatum";i:3;s:12:"Buchungstext";i:4;s:16:"Verwendungszweck";i:5;s:33:"Beguenstigter/Zahlungspflichtiger";i:6;s:11:"Kontonummer";i:7;s:3:"BLZ";i:8;s:6:"Betrag";i:9;s:8:"Waehrung";i:10;s:4:"Info";}';
					} else {
						$result['all']++;

						$check=md5(serialize($data));

						$Qget=self::getConnection();
						$Qget->prepare('SELECT * FROM :table_weberp_buchung: WHERE mandant_id=:mandant_id: AND buchung_checksum=:buchung_checksum:');
						$Qget->bindTable(':table_weberp_buchung:', 'weberp_buchung');
						$Qget->bindInt(':mandant_id:', $this->getMandantId());
						$Qget->bindString(':buchung_checksum:', $check);
						$Qget->execute();
						if ($Qget->rowCount()==0) {
							$result['new']++;
							$Qinsert=self::getConnection();
							$Qinsert->prepare('INSERT INTO :table_weberp_buchung: (buchung_checksum, buchung_auftragskonto, buchung_buchungtag, buchung_valutadatum, buchung_text, buchung_verwendungszweck, buchung_kontoinhaber, buchung_iban, buchung_bic, buchung_betrag, buchung_waehrung, buchung_info, buchung_create_time, buchung_create_user_id, buchung_update_time, buchung_update_user_id, mandant_id) VALUE (:buchung_checksum:, :buchung_auftragskonto:, :buchung_buchungtag:, :buchung_valutadatum:, :buchung_text:, :buchung_verwendungszweck:, :buchung_kontoinhaber:, :buchung_iban:, :buchung_bic:, :buchung_betrag:, :buchung_waehrung:, :buchung_info:, :buchung_create_time:, :buchung_create_user_id:, :buchung_update_time:, :buchung_update_user_id:, :mandant_id:)');
							$Qinsert->bindTable(':table_weberp_buchung:', 'weberp_buchung');
							$Qinsert->bindString(':buchung_checksum:', $check);
							$Qinsert->bindString(':buchung_auftragskonto:', $data[0]);
							$Qinsert->bindInt(':buchung_buchungtag:', '20'.substr($data[1], 6, 2).substr($data[1], 3, 2).substr($data[1], 0, 2));
							$Qinsert->bindInt(':buchung_valutadatum:', '20'.substr($data[2], 6, 2).substr($data[2], 3, 2).substr($data[2], 0, 2));
							$Qinsert->bindString(':buchung_text:', $data[3]);
							$Qinsert->bindString(':buchung_verwendungszweck:', $data[4]);
							$Qinsert->bindString(':buchung_kontoinhaber:', $data[5]);
							$Qinsert->bindString(':buchung_iban:', $data[6]);
							$Qinsert->bindString(':buchung_bic:', $data[7]);
							$Qinsert->bindFloat(':buchung_betrag:', str_replace(',', '.', $data[8]));
							$Qinsert->bindString(':buchung_waehrung:', $data[9]);
							$Qinsert->bindString(':buchung_info:', $data[10]);
							$Qinsert->bindInt(':buchung_create_time:', $time);
							$Qinsert->bindInt(':buchung_create_user_id:', $vis_user_id);
							$Qinsert->bindInt(':buchung_update_time:', $time);
							$Qinsert->bindInt(':buchung_update_user_id:', $vis_user_id);
							$Qinsert->bindInt(':mandant_id:', $this->getMandantId());
							$Qinsert->execute();
						} else {
							$result['old']++;
						}
					}
				}
			}
			fclose($handle);
			$result['message']='Import erfolgreich (Alle: '.$result['all'].' | Neu: '.$result['new'].' | Alt: '.$result['old'].').';
			$result['type']='success';
		} else {
			$result['message']='Falsches Format ausgewählt.';
			$result['type']='error';
		}

		return $result;
	}

	/**
	 * @return bool
	 */
	public function loadBuchungen():bool {
		$this->buchungen=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_buchung: WHERE mandant_id=:mandant_id: ORDER BY buchung_id DESC');
		$QselectData->bindTable(':table_weberp_buchung:', 'weberp_buchung');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		foreach ($QselectData->query() as $buchnung) {
			$buchnung['buchung_betrag']=floatval($buchnung['buchung_betrag']);
			$this->buchungen[$buchnung['buchung_id']]=$buchnung;
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getBuchungen():array {
		if ($this->buchungen==[]) {
			$this->loadBuchungen();
		}

		return $this->buchungen;
	}

	/**
	 * @return bool
	 */
	public function loadOffenePosten():bool {
		$this->offene_posten=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id: AND rechnung_bezahlt=:rechnung_bezahlt: AND rechnung_storniert=:rechnung_storniert: ORDER BY rechnung_id DESC');
		$QselectData->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':rechnung_bezahlt:', 0);
		$QselectData->bindInt(':rechnung_storniert:', 0);
		foreach ($QselectData->query() as $rechnung) {
			$rechnung['rechnung_gesamt_brutto']=floatval($rechnung['rechnung_gesamt_brutto']);
			$rechnung['rechnung_gesamt_netto']=floatval($rechnung['rechnung_gesamt_netto']);
			$rechnung['rechnung_gesamt_mwst']=floatval($rechnung['rechnung_gesamt_mwst']);
			$this->offene_posten[$rechnung['rechnung_id']]=$rechnung;
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getOffenePosten():array {
		if ($this->offene_posten==[]) {
			$this->loadOffenePosten();
		}

		return $this->offene_posten;
	}

	/**
	 * @return bool
	 */
	public function loadKundenKonten():bool {
		$this->kunden_konten=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_kunde_konto: WHERE mandant_id=:mandant_id:');
		$QselectData->bindTable(':table_weberp_kunde_konto:', 'weberp_kunde_konto');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		foreach ($QselectData->query() as $kundenkonto) {
			$this->kunden_konten[$kundenkonto['konto_id']]=$kundenkonto;
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getKundenKonten():array {
		if ($this->kunden_konten==[]) {
			$this->loadKundenKonten();
		}

		return $this->kunden_konten;
	}

	/**
	 * @return array
	 */
	public function loadBuchungsAusgleich():bool {
		$this->buchungs_ausgleich=[];
		$this->buchungs_ausgleich['ok']=[];
		$this->buchungs_ausgleich['evtl']=[];
		foreach ($this->getOffenePosten() as $offener_posten) {
			foreach ($this->getBuchungen() as $buchung) {
				if ((strpos($buchung['buchung_verwendungszweck'], $offener_posten['rechnung_kunde_nr'])>0)&&(strpos($buchung['buchung_verwendungszweck'], $offener_posten['rechnung_nr'])>0)&&($buchung['buchung_betrag']==$offener_posten['rechnung_gesamt_brutto'])) {
					$this->buchungs_ausgleich['ok'][]=['offener_posten'=>$offener_posten, 'buchung_evtl'=>$buchung_evtl];
				} elseif ((strpos($buchung['buchung_verwendungszweck'], $offener_posten['rechnung_nr'])>0)&&($buchung['buchung_betrag']==$offener_posten['rechnung_gesamt_brutto'])) {
					$this->buchungs_ausgleich['ok'][]=['offener_posten'=>$offener_posten, 'buchung_evtl'=>$buchung_evtl];
				} elseif (((strpos($buchung['buchung_verwendungszweck'], $offener_posten['rechnung_nr'])>0))&&($buchung['buchung_betrag']>=$offener_posten['rechnung_gesamt_brutto'])) {
					$this->buchungs_ausgleich['evtl'][]=['offener_posten'=>$offener_posten, 'buchung_evtl'=>$buchung_evtl];
				}
			}
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getBuchungsAusgleich():array {
		if ($this->buchungs_ausgleich==[]) {
			$this->loadBuchungsAusgleich();
		}

		return $this->buchungs_ausgleich;
	}

	public function getBuchungsAusgleichAsList():array {
		if ($this->buchungs_ausgleich==[]) {
			$this->loadBuchungsAusgleich();
		}

		$ar_todo=[];
		$ar_todo['ok']=[];
		$ar_todo['evtl']=[];

		foreach ($this->buchungs_ausgleich['ok'] as $buchungs_ausgleich) {
			$ar_todo['ok'][$buchungs_ausgleich['offener_posten']['rechnung_nr']]='KdNr '.$buchungs_ausgleich['offener_posten']['rechnung_kunde_nr'].', ReNr '.$buchungs_ausgleich['offener_posten']['rechnung_nr'].', Betrag '.\osWFrame\Core\Math::formatNumber($buchungs_ausgleich['offener_posten']['rechnung_gesamt_brutto']).' Euro';
		}

		foreach ($this->buchungs_ausgleich['evtl'] as $buchungs_ausgleich) {
			$ar_todo['evtl'][$buchungs_ausgleich['offener_posten']['rechnung_nr']]='KdNr '.$buchungs_ausgleich['offener_posten']['rechnung_kunde_nr'].', ReNr '.$buchungs_ausgleich['offener_posten']['rechnung_nr'].', Betrag '.\osWFrame\Core\Math::formatNumber($buchungs_ausgleich['offener_posten']['rechnung_gesamt_brutto']).' Euro';
		}

		return $ar_todo;
	}

	/**
	 * @param array $set_data
	 * @param int $vis_user_id
	 * @return array
	 */
	public function setBuchungenAsPaid(array $set_data, int $vis_user_id):array {
		if ($this->buchungs_ausgleich==[]) {
			$this->loadBuchungsAusgleich();
		}

		$time=time();
		$result=[];
		$result['message']=0;
		$result['type']='';
		$result['ok']=0;

		foreach ($set_data as $k=>$v) {
			$renr=0;
			if (strstr($k, 'buchung_passend_')) {
				$renr=str_replace('buchung_passend_', '', $k);
			}
			if (strstr($k, 'buchung_ueberzahlt_')) {
				$renr=str_replace('buchung_ueberzahlt_', '', $k);
			}
			$renr=intval($renr);
			if ($renr>0) {
				$result['ok']++;
				$Qset=self::getConnection();
				$Qset->prepare('UPDATE :table_weberp_rechnung: SET rechnung_bezahlt=:rechnung_bezahlt:, rechnung_update_time=:rechnung_update_time:, rechnung_update_user_id=:rechnung_update_user_id: WHERE mandant_id=:mandant_id: AND rechnung_nr=:rechnung_nr:');
				$Qset->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
				$Qset->bindInt(':rechnung_bezahlt:', 1);
				$Qset->bindInt(':rechnung_update_time:', $time);
				$Qset->bindInt(':rechnung_update_user_id:', $vis_user_id);
				$Qset->bindInt(':mandant_id:', $this->getMandantId());
				$Qset->bindInt(':rechnung_nr:', $renr);
				$Qset->execute();
			}
		}

		$result['message']='Zuweisung der Buchungen erfolgreich (Alle: '.$result['ok'].').';
		$result['type']='success';

		return $result;
	}

}

?>