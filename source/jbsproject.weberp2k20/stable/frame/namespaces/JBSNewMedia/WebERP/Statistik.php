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

class Statistik {

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
	private array $scale=[];

	/**
	 * @var array
	 */
	private array $umsatz_netto=[];

	/**
	 * @var array
	 */
	private array $umsatz_brutto=[];

	/**
	 * @var array
	 */
	private array $anzahl_rechnungen=[];

	/**
	 * @var array
	 */
	private array $umsatz_prorechnung_brutto=[];

	/**
	 * @var array
	 */
	private array $umsatz_prorechnung_netto=[];

	/**
	 * Statistik constructor.
	 *
	 * @param int $mandant_id
	 */
	public function __construct(int $mandant_id=0) {
		if ($mandant_id>0) {
			$this->setMandantId($mandant_id);
		}
	}

	/**
	 * @return bool
	 */
	public function init() {
		$this->scale=[];
		$this->umsatz_netto=[];
		$this->umsatz_brutto=[];
		$this->anzahl_rechnungen=[];
		$this->umsatz_prorechnung_brutto=[];
		$this->umsatz_prorechnung_netto=[];

		return true;
	}

	/**
	 * @param string $key
	 * @param int $value
	 * @return bool
	 */
	public function setScale(string $key, int $value):bool {
		$this->scale[$key]=$value;

		return true;
	}

	/**
	 * @param string $key
	 * @return int|null
	 */
	public function getScale(string $key):?int {
		if (!isset($this->scale[$key])) {
			return null;
		}

		return $this->scale[$key];
	}

	public function getStatistik() {
		$brutto[date('Y')]=0;
		$netto[date('Y')]=0;
		$rechnungen[date('Y')]=0;
		$rechnungen_brutto[date('Y')]=0;
		$rechnungen_netto[date('Y')]=0;

		$max_brutto=0;
		$max_rechnungen=0;

		$QgetData=self::getConnection();
		$QgetData->prepare('SELECT * FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id: AND rechnung_storniert=:rechnung_storniert:');
		$QgetData->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
		$QgetData->bindInt(':rechnung_storniert:', 0);
		$QgetData->bindInt(':mandant_id:', $this->getMandantId());
		foreach ($QgetData->query() as $rechnung) {
			if (!isset($brutto[substr($rechnung['rechnung_datum'], 0, 4)])) {
				$brutto[substr($rechnung['rechnung_datum'], 0, 4)]=0;
			}
			if (!isset($netto[substr($rechnung['rechnung_datum'], 0, 4)])) {
				$netto[substr($rechnung['rechnung_datum'], 0, 4)]=0;
			}
			if (!isset($rechnungen[substr($rechnung['rechnung_datum'], 0, 4)])) {
				$rechnungen[substr($rechnung['rechnung_datum'], 0, 4)]=0;
			}

			$brutto[substr($rechnung['rechnung_datum'], 0, 4)]+=$rechnung['rechnung_gesamt_brutto'];
			$netto[substr($rechnung['rechnung_datum'], 0, 4)]+=$rechnung['rechnung_gesamt_netto'];
			$rechnungen[substr($rechnung['rechnung_datum'], 0, 4)]++;

			if ($brutto[substr($rechnung['rechnung_datum'], 0, 4)]>$max_brutto) {
				$max_brutto=$brutto[substr($rechnung['rechnung_datum'], 0, 4)];
			}

			if ($rechnungen[substr($rechnung['rechnung_datum'], 0, 4)]>$max_rechnungen) {
				$max_rechnungen=$rechnungen[substr($rechnung['rechnung_datum'], 0, 4)];
			}

		}

		$this->setScale('umsatz', ceil(ceil($max_brutto)/10000)*10000*1.25);
		$this->setScale('rechnungen', ceil(ceil($max_rechnungen)/100)*100*1.25);

		ksort($brutto);
		ksort($netto);
		ksort($rechnungen);

		$max_rechnungen_brutto=0;

		$_netto=[];
		foreach ($netto as $key=>$value) {
			$_netto[$key]=intval($value);
			$rechnungen_netto[$key]=intval(round($value/$rechnungen[$key]));
		}
		$_brutto=[];
		foreach ($brutto as $key=>$value) {
			$_brutto[$key]=intval($value);
			$rechnungen_brutto[$key]=intval(round($value/$rechnungen[$key]));
			if ($rechnungen_brutto[$key]>$max_rechnungen_brutto) {
				$max_rechnungen_brutto=$rechnungen_brutto[$key];
			}
		}
		$_rechnungen=[];
		foreach ($rechnungen as $key=>$value) {
			$_rechnungen[$key]=intval($value);
		}

		ksort($rechnungen_netto);
		ksort($rechnungen_brutto);

		$max_rechnungen_brutto=(ceil(intval($max_rechnungen_brutto)/1000)*1000)*1.25;

		$_rechnungen_netto=[];
		foreach ($rechnungen_netto as $key=>$value) {
			$_rechnungen_netto[$key]=intval($value);
		}
		$_rechnungen_brutto=[];
		foreach ($rechnungen_brutto as $key=>$value) {
			$_rechnungen_brutto[$key]=intval($value);
		}
		$this->umsatz_netto=$_netto;
		$this->umsatz_brutto=$_brutto;
		$this->anzahl_rechnungen=$_rechnungen;
		$this->umsatz_prorechnung_brutto=$rechnungen_brutto;
		$this->umsatz_prorechnung_netto=$rechnungen_netto;
	}

	/**
	 * @return array
	 */
	public function getUmsatzNetto():array {
		return $this->umsatz_netto;
	}

	/**
	 * @return array
	 */
	public function getUmsatzBrutto():array {
		return $this->umsatz_brutto;
	}

	/**
	 * @return array
	 */
	public function getAnzahlRechnungen():array {
		return $this->anzahl_rechnungen;
	}

	/**
	 * @return array
	 */
	public function getUmsatzProRechnungBrutto():array {
		return $this->umsatz_prorechnung_brutto;
	}

	/**
	 * @return array
	 */
	public function getUmsatzProRechnungNetto():array {
		return $this->umsatz_prorechnung_netto;
	}

}

?>