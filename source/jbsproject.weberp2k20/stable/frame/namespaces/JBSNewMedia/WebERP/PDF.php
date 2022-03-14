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

class PDF extends \JBSNewMedia\Core\PDF {

	use osWFrame\BaseStaticTrait;
	use BaseVerwaltungTrait;

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
	private array $jbs_kunde_details=[];

	/**
	 * @var array
	 */
	private array $jbs_konto_details=[];

	/**
	 * @var array
	 */
	private array $jbs_sepa_details=[];

	/**
	 * @var array
	 */
	private array $jbs_vorgang_details=[];

	/**
	 * @var array
	 */
	private array $jbs_positionen_details=[];

	/**
	 * @var array
	 */
	private array $jbs_schreiben_details=[];

	/**
	 * @param array $kunde_details
	 * @return bool
	 */
	public function setKundeDetails(array $kunde_details):bool {
		$this->jbs_kunde_details=$kunde_details;

		return true;
	}

	/**
	 * @return array
	 */
	public function getKundeDetails():array {
		return $this->jbs_kunde_details;
	}

	/**
	 * @param $key
	 * @return string|null
	 */
	public function getKundeDetail(string $key):?string {
		if (isset($this->jbs_kunde_details[$key])) {
			return $this->jbs_kunde_details[$key];
		}

		return null;
	}

	/**
	 * @param string $key
	 * @param $value
	 * @return bool
	 */
	public function setKundeDetail(string $key, $value):bool {
		$this->jbs_kunde_details[$key]=$value;

		return true;
	}

	/**
	 * @param array $konto_details
	 * @return bool
	 */
	public function setKontoDetails(array $konto_details):bool {
		$this->jbs_konto_details=$konto_details;

		return true;
	}

	/**
	 * @return array
	 */
	public function getKontoDetails():array {
		return $this->jbs_konto_details;
	}

	/**
	 * @param $key
	 * @return string|null
	 */
	public function getKontoDetail(string $key):?string {
		if (isset($this->jbs_konto_details[$key])) {
			return $this->jbs_konto_details[$key];
		}

		return null;
	}

	/**
	 * @param string $key
	 * @param $value
	 * @return bool
	 */
	public function setKontoDetail(string $key, $value):bool {
		$this->jbs_konto_details[$key]=$value;

		return true;
	}

	/**
	 * @param array $sepa_details
	 * @return bool
	 */
	public function setSepaDetails(array $sepa_details):bool {
		$this->jbs_sepa_details=$sepa_details;

		return true;
	}

	/**
	 * @return array
	 */
	public function getSepaDetails():array {
		return $this->jbs_sepa_details;
	}

	/**
	 * @param $key
	 * @return string|null
	 */
	public function getSepaDetail(string $key):?string {
		if (isset($this->jbs_sepa_details[$key])) {
			return $this->jbs_sepa_details[$key];
		}

		return null;
	}

	/**
	 * @param string $key
	 * @param $value
	 * @return bool
	 */
	public function setSepaDetail(string $key, $value):bool {
		$this->jbs_sepa_details[$key]=$value;

		return true;
	}

	/**
	 * @param array $vorgang_details
	 * @return bool
	 */
	public function setVorgangDetails(array $vorgang_details):bool {
		$this->jbs_vorgang_details=$vorgang_details;

		return true;
	}

	/**
	 * @return array
	 */
	public function getVorgangDetails():array {
		return $this->jbs_vorgang_details;
	}

	/**
	 * @param $key
	 * @return string|null
	 */
	public function getVorgangDetail(string $key):?string {
		if (isset($this->jbs_vorgang_details[$key])) {
			return $this->jbs_vorgang_details[$key];
		}

		return null;
	}

	/**
	 * @param string $key
	 * @param $value
	 * @return bool
	 */
	public function setVorgangDetail(string $key, $value):bool {
		$this->jbs_vorgang_details[$key]=$value;

		return true;
	}

	/**
	 * @param array $positionen_details
	 * @return bool
	 */
	public function setPositionenDetails(array $positionen_details):bool {
		$this->jbs_positionen_details=$positionen_details;

		return true;
	}

	/**
	 * @return array
	 */
	public function getPositionenDetails():array {
		return $this->jbs_positionen_details;
	}

	/**
	 * @param array $schreiben_details
	 * @return bool
	 */
	public function setSchreibenDetails(array $schreiben_details):bool {
		$this->jbs_schreiben_details=$schreiben_details;

		return true;
	}

	/**
	 * @return array
	 */
	public function getSchreibenDetails():array {
		return $this->jbs_schreiben_details;
	}

	/**
	 * @param $key
	 * @return string|null
	 */
	public function getSchreibenDetail(string $key):?string {
		if (isset($this->jbs_schreiben_details[$key])) {
			return $this->jbs_schreiben_details[$key];
		}

		return null;
	}

	/**
	 * @param string $key
	 * @param $value
	 * @return bool
	 */
	public function setSchreibenDetail(string $key, $value):bool {
		$this->jbs_schreiben_details[$key]=$value;

		return true;
	}

	/**
	 * @param int $position
	 * @param string $key
	 * @return string|null
	 */
	public function getPositionenDetail(int $position, string $key):?string {
		if ((isset($this->jbs_positionen_details[$position]))&&(isset($this->jbs_positionen_details[$position][$key]))) {
			return $this->jbs_positionen_details[$position][$key];
		}

		return null;
	}

	/**
	 * @param int $position
	 * @param string $key
	 * @param $value
	 * @return bool
	 */
	public function setPositionenDetail(int $position, string $key, $value):bool {
		if (!isset($this->jbs_positionen_details[$position])) {
			return false;
		}

		$this->jbs_positionen_details[$position][$key]=$value;

		return true;
	}

	/**
	 * @return bool
	 */
	public function generateSepa():bool {
		$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.$this->getVerwaltung()->getStringVar('profile').DIRECTORY_SEPARATOR.'sepa_pdf.php';
		if (osWFrame\Filesystem::existsFile($file)) {
			include $file;
		} else {
			$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'sepa_pdf.php';
			if (osWFrame\Filesystem::existsFile($file)) {
				include $file;
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function generateRechnung():bool {
		$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.$this->getVerwaltung()->getStringVar('profile').DIRECTORY_SEPARATOR.'rechnung_pdf.php';
		if (osWFrame\Filesystem::existsFile($file)) {
			include $file;
		} else {
			$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'rechnung_pdf.php';
			if (osWFrame\Filesystem::existsFile($file)) {
				include $file;
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function generateAngebot():bool {
		$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.$this->getVerwaltung()->getStringVar('profile').DIRECTORY_SEPARATOR.'angebot_pdf.php';
		if (osWFrame\Filesystem::existsFile($file)) {
			include $file;
		} else {
			$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'angebot_pdf.php';
			if (osWFrame\Filesystem::existsFile($file)) {
				include $file;
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function generateSchreiben():bool {
		$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.$this->getVerwaltung()->getStringVar('profile').DIRECTORY_SEPARATOR.'schreiben_pdf.php';
		if (osWFrame\Filesystem::existsFile($file)) {
			include $file;
		} else {
			$file=osWFrame\Settings::getStringVar('settings_abspath').osWFrame\Settings::getStringVar('resource_path').'weberp'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'schreiben_pdf.php';
			if (osWFrame\Filesystem::existsFile($file)) {
				include $file;
			}
		}

		return true;
	}

}

?>