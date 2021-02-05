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

class Sepa {

	use osWFrame\BaseStaticTrait;
	use osWFrame\BaseConnectionTrait;
	use VIS2\BaseMandantTrait;
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
	private array $sepa_details=[];

	/**
	 * @var array
	 */
	private array $konto_details=[];

	/**
	 * @var array
	 */
	private array $kunde_details=[];

	/**
	 * @var int
	 */
	private int $sepa_id=0;

	/**
	 * @var int
	 */
	private int $konto_id=0;

	/**
	 * @var int
	 */
	private int $kunde_id=0;

	/**
	 * @var bool
	 */
	private bool $loaded=false;

	/**
	 * Sepa constructor.
	 *
	 * @param $mandant_id
	 * @param int $sepa_id
	 */
	public function __construct($mandant_id, int $sepa_id=0) {
		$this->init();
		$this->setMandantId($mandant_id);
		if ($sepa_id>0) {
			$this->setSepaId($sepa_id);
			$this->load();
		}
	}

	/**
	 * @return bool
	 */
	public function init():bool {
		$this->sepa_details=[];
		$this->konto_details=[];
		$this->kunde_details=[];
		$this->setSepaId(0);
		$this->setKontoId(0);
		$this->setKundeId(0);
		$this->setLoaded(false);

		return true;
	}

	/**
	 * @param bool $value
	 * @return bool
	 */
	public function setLoaded(bool $value):bool {
		$this->loaded=$value;

		return true;
	}

	/**
	 * @return bool
	 */
	public function getLoaded():bool {
		return $this->loaded;
	}

	/**
	 * @param int $sepa_id
	 * @return bool
	 */
	public function setSepaId(int $sepa_id=0):bool {
		$this->sepa_id=$sepa_id;

		return true;
	}

	/**
	 * @return int
	 */
	public function getSepaId():int {
		return $this->sepa_id;
	}

	/**
	 * @param int $konto_id
	 * @return bool
	 */
	public function setKontoId(int $konto_id=0):bool {
		$this->konto_id=$konto_id;

		return true;
	}

	/**
	 * @return int
	 */
	public function getKontoId():int {
		return $this->konto_id;
	}

	/**
	 * @param int $kunde_id
	 * @return bool
	 */
	public function setKundeId(int $kunde_id=0):bool {
		$this->kunde_id=$kunde_id;

		return true;
	}

	/**
	 * @return int
	 */
	public function getKundeId():int {
		return $this->kunde_id;
	}

	/**
	 * @return bool
	 */
	public function load():bool {
		if ($this->loadDetails()!==true) {
			$this->init();

			return false;
		}

		if ($this->loadKonto()!==true) {
			$this->init();

			return false;
		}

		if ($this->loadKunde()!==true) {
			$this->init();

			return false;
		}

		$this->setLoaded(true);

		return true;
	}

	/**
	 * @return bool
	 */
	public function loadDetails():bool {
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_kunde_sepa: WHERE mandant_id=:mandant_id: AND sepa_id=:sepa_id:');
		$QselectData->bindTable(':table_weberp_kunde_sepa:', 'weberp_kunde_sepa');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':sepa_id:', $this->getSepaId());
		$QselectData->execute();
		if ($QselectData->rowCount()==1) {
			$this->sepa_details=$QselectData->fetch();
			$this->setKontoId($this->sepa_details['konto_id']);

			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getDetails():array {
		return $this->sepa_details;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getDetailValue(string $key):string {
		if (isset($this->sepa_details[$key])) {
			return $this->sepa_details[$key];
		}

		return '';
	}

	/**
	 * @return bool
	 */
	public function loadKonto():bool {
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_kunde_konto: WHERE mandant_id=:mandant_id: AND konto_id=:konto_id:');
		$QselectData->bindTable(':table_weberp_kunde_konto:', 'weberp_kunde_konto');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':konto_id:', $this->getKontoId());
		$QselectData->execute();
		if ($QselectData->rowCount()==1) {
			$this->konto_details=$QselectData->fetch();
			$this->setKundeId($this->konto_details['kunde_id']);

			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getKonto():array {
		return $this->konto_details;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getKontoValue(string $key):string {
		if (isset($this->konto_details[$key])) {
			return $this->konto_details[$key];
		}

		return '';
	}

	/**
	 * @return bool
	 */
	public function loadKunde():bool {
		$QselectData=self::getConnection();
		$QselectData->prepare('SELECT * FROM :table_weberp_kunde: WHERE mandant_id=:mandant_id: AND kunde_id=:kunde_id:');
		$QselectData->bindTable(':table_weberp_kunde:', 'weberp_kunde');
		$QselectData->bindInt(':mandant_id:', $this->getMandantId());
		$QselectData->bindInt(':kunde_id:', $this->getKundeId());
		$QselectData->execute();
		if ($QselectData->rowCount()==1) {
			$this->kunde_details=$QselectData->fetch();

			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getKunde():array {
		return $this->kunde_details;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getKundeValue(string $key):string {
		if (isset($this->kunde_details[$key])) {
			return $this->kunde_details[$key];
		}

		return '';
	}

	/**
	 * @return string
	 */
	public function getPath():string {
		$dir=osWFrame\Settings::getStringVar('settings_abspath').'data'.DIRECTORY_SEPARATOR.'weberp'.DIRECTORY_SEPARATOR.'sepa'.DIRECTORY_SEPARATOR;
		if (osWFrame\Filesystem::isDir($dir)!==true) {
			osWFrame\Filesystem::makeDir($dir);
		}

		return $dir;
	}

}

?>