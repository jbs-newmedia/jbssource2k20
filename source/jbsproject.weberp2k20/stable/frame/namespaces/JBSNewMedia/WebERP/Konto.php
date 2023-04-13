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
	protected array $buchungen=[];

	/**
	 * @var array
	 */
	protected array $posten=[];

	/**
	 * @var array
	 */
	protected array $offene_posten=[];

	/**
	 * @var array
	 */
	protected array $kunden_konten=[];

	/**
	 * @var array
	 */
	protected array $kunden_konten_zuordnen=[];

	/**
	 * @var array
	 */
	protected array $kunden_konten_zuordnen_list=[];

	/**
	 * @var array
	 */
	protected array $konten_buchung_zuordnen=[];

	/**
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
		$QselectData->prepare('SELECT * FROM :table_weberp_buchung: WHERE mandant_id=:mandant_id: ORDER BY buchung_id ASC');
		$QselectData->bindTable(':table_weberp_buchung:', 'weberp_buchung');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		foreach ($QselectData->query() as $buchnung) {
			$buchnung['buchung_betrag']=floatval($buchnung['buchung_betrag']);
			$this->buchungen[$buchnung['buchung_id']]=$buchnung;
		}

		return true;
	}

	/**
	 * @param array|string $iban
	 * @return bool
	 */
	public function loadBuchungenByIBAN(array|string $iban):bool {
		if (!is_array($iban)) {
			$iban=[$iban];
		}
		if ($iban==[]) {
			return false;
		}
		$this->buchungen=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_buchung: WHERE mandant_id=:mandant_id: AND buchung_iban IN (:buchung_iban:) ORDER BY buchung_id ASC');
		$QselectData->bindTable(':table_weberp_buchung:', 'weberp_buchung');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindRaw(':buchung_iban:', '"'.implode('","', $iban).'"');
		foreach ($QselectData->query() as $buchnung) {
			$buchnung['buchung_betrag']=floatval($buchnung['buchung_betrag']);
			$this->buchungen[$buchnung['buchung_id']]=$buchnung;
		}

		return true;
	}

	/**
	 * @param array|string $iban
	 * @return bool
	 */
	public function loadOffeneBuchungenByIBAN(array|string $iban, int $kunde_id):bool {
		if (!is_array($iban)) {
			$iban=[$iban];
		}
		if ($iban==[]) {
			return false;
		}
		$this->buchungen=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT b.* FROM :table_weberp_buchung: AS b LEFT JOIN :table_weberp_rechnung: AS r ON (r.kunde_id=:kunde_id: AND r.mandant_id=:mandant_id: AND (r.buchung_id_1=b.buchung_id OR r.buchung_id_2=b.buchung_id OR r.buchung_id_3=b.buchung_id)) WHERE b.mandant_id=:mandant_id: AND b.buchung_iban IN (:buchung_iban:) AND r.rechnung_id IS NULL ORDER BY b.buchung_id ASC');
		$QselectData->bindTable(':table_weberp_buchung:', 'weberp_buchung');
		$QselectData->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':kunde_id:', $kunde_id);
		$QselectData->bindRaw(':buchung_iban:', '"'.implode('","', $iban).'"');
		foreach ($QselectData->query() as $buchnung) {
			$buchnung['buchung_betrag']=floatval($buchnung['buchung_betrag']);
			$this->buchungen[$buchnung['buchung_id']]=$buchnung;
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getBuchungen($check_load=true):array {
		if (($check_load===true)&&($this->buchungen==[])) {
			$this->loadBuchungen();
		}

		return $this->buchungen;
	}

	/**
	 * @return bool
	 */
	public function loadPosten():bool {
		$this->posten=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id: ORDER BY rechnung_id ASC');
		$QselectData->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		foreach ($QselectData->query() as $rechnung) {
			$rechnung['rechnung_gesamt_brutto']=floatval($rechnung['rechnung_gesamt_brutto']);
			$rechnung['rechnung_gesamt_netto']=floatval($rechnung['rechnung_gesamt_netto']);
			$rechnung['rechnung_gesamt_mwst']=floatval($rechnung['rechnung_gesamt_mwst']);
			$this->posten[$rechnung['rechnung_id']]=$rechnung;
		}

		return true;
	}

	/**
	 * @param array|int $kunde_id
	 * @return bool
	 */
	public function loadPostenByKunde(array|int $kunde_id):bool {
		if (!is_array($kunde_id)) {
			$kunde_id=[$kunde_id];
		}
		$this->posten=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id: AND kunde_id IN (:kunde_id:) ORDER BY rechnung_id ASC');
		$QselectData->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindRaw(':kunde_id:', implode(',', $kunde_id));
		foreach ($QselectData->query() as $rechnung) {
			$rechnung['rechnung_gesamt_brutto']=floatval($rechnung['rechnung_gesamt_brutto']);
			$rechnung['rechnung_gesamt_netto']=floatval($rechnung['rechnung_gesamt_netto']);
			$rechnung['rechnung_gesamt_mwst']=floatval($rechnung['rechnung_gesamt_mwst']);
			$this->posten[$rechnung['rechnung_id']]=$rechnung;
		}

		return true;
	}

	/**
	 * @param $check_load
	 * @return array
	 */
	public function getPosten($check_load=true):array {
		if (($check_load===true)&&($this->posten==[])) {
			$this->loadPosten();
		}

		return $this->posten;
	}

	/**
	 * @return bool
	 */
	public function loadOffenePosten():bool {
		$this->offene_posten=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id: AND rechnung_bezahlt=:rechnung_bezahlt: AND rechnung_storniert=:rechnung_storniert: ORDER BY rechnung_id ASC');
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
	 * @param array|int $kunde_id
	 * @return bool
	 */
	public function loadOffenePostenByKunde(array|int $kunde_id):bool {
		if (!is_array($kunde_id)) {
			$kunde_id=[$kunde_id];
		}
		$this->offene_posten=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id: AND rechnung_bezahlt=:rechnung_bezahlt: AND rechnung_storniert=:rechnung_storniert: AND kunde_id IN (:kunde_id:) ORDER BY rechnung_id ASC');
		$QselectData->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':rechnung_bezahlt:', 0);
		$QselectData->bindInt(':rechnung_storniert:', 0);
		$QselectData->bindRaw(':kunde_id:', implode(',', $kunde_id));
		foreach ($QselectData->query() as $rechnung) {
			$rechnung['rechnung_gesamt_brutto']=floatval($rechnung['rechnung_gesamt_brutto']);
			$rechnung['rechnung_gesamt_netto']=floatval($rechnung['rechnung_gesamt_netto']);
			$rechnung['rechnung_gesamt_mwst']=floatval($rechnung['rechnung_gesamt_mwst']);
			$this->offene_posten[$rechnung['rechnung_id']]=$rechnung;
		}

		return true;
	}

	/**
	 * @param $check_load
	 * @return array
	 */
	public function getOffenePosten($check_load=true):array {
		if (($check_load===true)&&($this->offene_posten==[])) {
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
	 * @param array|int $kunde_id
	 * @return bool
	 */
	public function loadKundenKontenByKunde(array|int $kunde_id):bool {
		if (!is_array($kunde_id)) {
			$kunde_id=[$kunde_id];
		}
		$this->kunden_konten=[];
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_kunde_konto: WHERE mandant_id=:mandant_id: AND kunde_id IN (:kunde_id:)');
		$QselectData->bindTable(':table_weberp_kunde_konto:', 'weberp_kunde_konto');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindRaw(':kunde_id:', implode(',', $kunde_id));
		foreach ($QselectData->query() as $kundenkonto) {
			$this->kunden_konten[$kundenkonto['konto_id']]=$kundenkonto;
		}

		return true;
	}

	/**
	 * @param $check_load
	 * @return array
	 */
	public function getKundenKonten($check_load=true):array {
		if (($check_load===true)&&($this->kunden_konten==[])) {
			$this->loadKundenKonten();
		}

		return $this->kunden_konten;
	}

	/**
	 * @return array
	 */
	public function loadKundenKontenZuordnen():bool {
		$this->kunden_konten_zuordnen=[];
		$this->kunden_konten_zuordnen_list=[];
		foreach ($this->getOffenePosten() as $offener_posten) {
			foreach ($this->getBuchungen() as $buchung) {
				if (((strpos($buchung['buchung_verwendungszweck'], $offener_posten['rechnung_kunde_nr'])>0)&&(strpos($buchung['buchung_verwendungszweck'], $offener_posten['rechnung_nr'])>0)&&($buchung['buchung_betrag']==$offener_posten['rechnung_gesamt_brutto']))||((strpos($buchung['buchung_verwendungszweck'], $offener_posten['rechnung_nr'])>0)&&($buchung['buchung_betrag']==$offener_posten['rechnung_gesamt_brutto']))||((strpos($buchung['buchung_verwendungszweck'], $offener_posten['rechnung_kunde_nr'])>0)&&($buchung['buchung_betrag']==$offener_posten['rechnung_gesamt_brutto']))) {
					$this->kunden_konten_zuordnen[$buchung['buchung_iban']]['konto']=$buchung;
					$this->kunden_konten_zuordnen[$buchung['buchung_iban']]['kunde']=$offener_posten;
					$this->kunden_konten_zuordnen[$buchung['buchung_iban']]['offener_posten'][]=$offener_posten;
					if ($offener_posten['rechnung_kunde_firma']!='') {
						$this->kunden_konten_zuordnen_list[$buchung['buchung_iban']]=$offener_posten['rechnung_kunde_nr'].' - '.$offener_posten['rechnung_kunde_firma'];
					} else {
						$this->kunden_konten_zuordnen_list[$buchung['buchung_iban']]=$offener_posten['rechnung_kunde_nr'].' - '.$offener_posten['rechnung_kunde_vorname'].' '.$offener_posten['rechnung_kunde_nachname'];
					}
				}
			}
		}

		uasort($this->kunden_konten_zuordnen_list, function($a, $b) {
			$aa=explode(' - ', $a);
			$bb=explode(' - ', $b);

			return $aa[0]<=>$bb[0];
		});

		/**
		 * unset used ibans
		 */
		$Verwaltung=new Verwaltung($this->getMandantId());
		foreach ($Verwaltung->getKundenKonten(false, 'konto_iban') as $iban=>$konto) {
			if (isset($this->kunden_konten_zuordnen[$iban])) {
				unset($this->kunden_konten_zuordnen[$iban]);
				unset($this->kunden_konten_zuordnen_list[$iban]);
			}
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getKundenKontenZuordnen():array {
		if ($this->kunden_konten_zuordnen==[]) {
			$this->loadKundenKontenZuordnen();
		}

		return $this->kunden_konten_zuordnen;
	}

	/**
	 * @return array
	 */
	public function getKundenKontenZuordnenAsList():array {
		if ($this->kunden_konten_zuordnen==[]) {
			$this->loadKundenKontenZuordnen();
		}

		return $this->kunden_konten_zuordnen_list;
	}

	/**
	 * @param int $kunde_id
	 * @param string $iban
	 * @param int $user_id
	 * @return bool
	 */
	public function createKundenKonto(int $kunde_id, string $iban, int $user_id=0):bool {
		$Qset=self::getConnection();
		$Qset->prepare('INSERT INTO :table_weberp_kunde_konto: (kunde_id, mandant_id, konto_iban, konto_ispublic, konto_create_time, konto_create_user_id, konto_update_time, konto_update_user_id) VALUES (:kunde_id:, :mandant_id:, :konto_iban:, :konto_ispublic:, :konto_create_time:, :konto_create_user_id:, :konto_update_time:, :konto_update_user_id:)');
		$Qset->bindTable(':table_weberp_kunde_konto:', 'weberp_kunde_konto');
		$Qset->bindInt(':kunde_id:', $kunde_id);
		$Qset->bindInt(':mandant_id:', $this->getMandantId());
		$Qset->bindString(':konto_iban:', $iban);
		$Qset->bindInt(':konto_ispublic:', 1);
		$Qset->bindRaw(':konto_create_time:', time());
		$Qset->bindInt(':konto_create_user_id:', $user_id);
		$Qset->bindRaw(':konto_update_time:', time());
		$Qset->bindInt(':konto_update_user_id:', $user_id);
		$Qset->execute();

		return true;
	}

	/**
	 * @param string $modus
	 * @return bool
	 */
	public function loadKontenBuchungZuordnen(string $modus=''):bool {
		$this->konten_buchung_zuordnen=[];
		$kunde_ibans=[];
		$konten=[];
		foreach ($this->getKundenKonten() as $konto) {
			$konten[$konto['konto_iban']]=$konto;
			$kunde_ibans[$konto['kunde_id']][]=$konto['konto_iban'];
		}

		foreach ($kunde_ibans as $kunde_id=>$ibans) {
			$this->loadOffenePostenByKunde($kunde_id);
			if ($modus=='konto') {
				$this->loadBuchungenByIBAN($ibans, $kunde_id);
				$this->checkPostenBuchungKontoKunde($kunde_id, $this->getOffenePosten(false), $this->getBuchungen(false));
			} else {
				$this->loadOffeneBuchungenByIBAN($ibans, $kunde_id);
				$this->checkPostenBuchungKunde($kunde_id, $this->getOffenePosten(false), $this->getBuchungen(false ));
			}
		}

		return true;
	}

	/**
	 * @param array $offene_posten
	 * @param array $buchungen
	 * @return bool
	 */
	protected function checkPostenBuchungKunde(int $kunde_id, array $offene_posten, array $buchungen):bool {
		if ($offene_posten==[]) {
			return false;
		}

		krsort($offene_posten);

		$Verwaltung = new Verwaltung($this->getMandantId());
		$this->konten_buchung_zuordnen[$kunde_id]=[];
		$this->konten_buchung_zuordnen[$kunde_id]['details']=$Verwaltung->getKundeById($kunde_id);
		$buchungen_list=[];
		$buchungen_list[]='Bitte wählen';
		$buchungen_list[-1]='Ohne Buchung ausgleichen';
		foreach ($buchungen as $pos) {
			if ($pos['buchung_valutadatum']>date('Ymd', (time()-(60*60*24*365)))) {
				$buchungen_list[$pos['buchung_id']]=$Verwaltung->formatNumber($pos['buchung_betrag']).' Euro - '.$pos['buchung_verwendungszweck'].' - '.substr($pos['buchung_valutadatum'], 6, 2).'.'.substr($pos['buchung_valutadatum'], 4, 2).'.'.substr($pos['buchung_valutadatum'], 0, 4);
			}
		}
		$this->konten_buchung_zuordnen[$kunde_id]['offene_posten']=$offene_posten;
		$this->konten_buchung_zuordnen[$kunde_id]['buchungen']=$buchungen_list;

		return true;
	}

	/**
	 * @param array $offene_posten
	 * @param array $buchungen
	 * @return bool
	 */
	protected function checkPostenBuchungKontoKunde(int $kunde_id, array $offene_posten, array $buchungen):bool {
		if ($offene_posten!=[]) {
			foreach ($offene_posten as $offener_posten) {
				foreach ($buchungen as $buchung) {
					if ($buchung['buchung_betrag']==$offener_posten['rechnung_gesamt_brutto']) {
						$this->konten_buchung_zuordnen[$kunde_id][md5(serialize($offener_posten).serialize($buchung))]=['offener_posten'=>$offener_posten, 'buchung'=>$buchung];
						$this->konten_buchung_zuordnen_list[$kunde_id][md5(serialize($offener_posten).serialize($buchung))]=$offener_posten['rechnung_kunde_nr'].' - '.$offener_posten['rechnung_nr'].' - '.$offener_posten['rechnung_gesamt_brutto'].' - '.$buchung['buchung_betrag'];
					}
				}
			}
		}

		return true;
	}

	/**
	 * @param string $modus
	 * @return array
	 */
	public function getKontenBuchungZuordnen(string $modus=''):array {
		if ($this->konten_buchung_zuordnen==[]) {
			$this->loadKontenBuchungZuordnen($modus);
		}

		return $this->konten_buchung_zuordnen;
	}

	/**
	 * @param int $rechnung_nr
	 * @param int $buchung_id
	 * @param int $pos
	 * @param int $user_id
	 * @return bool
	 */
	public function setBuchung2Rechnung(int $rechnung_nr, int $buchung_id, int $pos=0, int $user_id=0):bool {
		$Qset=self::getConnection();
		$Qset->prepare('UPDATE :table_weberp_rechnung: SET rechnung_bezahlt=:rechnung_bezahlt:, :buchung:=:buchung_id:, rechnung_update_time=:rechnung_update_time:, rechnung_update_user_id=:rechnung_update_user_id: WHERE mandant_id=:mandant_id: AND rechnung_nr=:rechnung_nr:');
		$Qset->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
		$Qset->bindInt(':rechnung_bezahlt:', 1);
		$Qset->bindRaw(':buchung:', 'buchung_id_'.$pos);
		$Qset->bindInt(':buchung_id:', $buchung_id);
		$Qset->bindInt(':rechnung_update_time:', time());
		$Qset->bindInt(':rechnung_update_user_id:', $user_id);
		$Qset->bindInt(':mandant_id:', $this->getMandantId());
		$Qset->bindInt(':rechnung_nr:', $rechnung_nr);
		$Qset->execute();

		return true;
	}

	/**
	 * @param int $rechnung_nr
	 * @param int $user_id
	 * @return bool
	 */
	public function setBuchungAsUnPaid(int $rechnung_nr, int $user_id=0):bool {
		$Qset=self::getConnection();
		$Qset->prepare('UPDATE :table_weberp_rechnung: SET rechnung_bezahlt=:rechnung_bezahlt:, buchung_id_1=:buchung_id:, buchung_id_2=:buchung_id:, buchung_id_3=:buchung_id:, rechnung_update_time=:rechnung_update_time:, rechnung_update_user_id=:rechnung_update_user_id: WHERE mandant_id=:mandant_id: AND rechnung_nr=:rechnung_nr:');
		$Qset->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
		$Qset->bindInt(':rechnung_bezahlt:', 0);
		$Qset->bindInt(':buchung_id:', 0);
		$Qset->bindInt(':rechnung_update_time:', time());
		$Qset->bindInt(':rechnung_update_user_id:', $user_id);
		$Qset->bindInt(':mandant_id:', $this->getMandantId());
		$Qset->bindInt(':rechnung_nr:', $rechnung_nr);
		$Qset->execute();

		return true;
	}

}

?>